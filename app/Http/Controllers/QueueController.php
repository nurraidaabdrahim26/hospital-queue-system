<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use App\Services\TwilioSmsService;

class QueueController extends Controller
{
    // Show form to register new patient
    public function create()
    {
        $departments = Department::where('id', '!=', 1)->get(); // Exclude Administration department
        return view('queues.create', compact('departments'));
    }

    // Store new patient in queue
    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'patient_nric' => 'required|string|max:20',
            'patient_phone' => 'required|string|max:15',
            'patient_address' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
        ]);

        // Generate queue number (e.g., EMER-001, CARD-001)
        $department = Department::find($request->department_id);
        $departmentCode = strtoupper(substr($department->name, 0, 4));
        
        $lastQueue = Queue::where('department_id', $request->department_id)
                         ->whereDate('created_at', today())
                         ->orderBy('id', 'desc')
                         ->first();

        $queueNumber = $lastQueue ? 
            intval(explode('-', $lastQueue->queue_number)[1]) + 1 : 1;

        $formattedQueueNumber = $departmentCode . '-' . str_pad($queueNumber, 3, '0', STR_PAD_LEFT);

        // Create the queue entry
        $queue = Queue::create([
            'queue_number' => $formattedQueueNumber,
            'patient_name' => $request->patient_name,
            'patient_nric' => $request->patient_nric,
            'patient_phone' => $request->patient_phone,
            'patient_address' => $request->patient_address,
            'department_id' => $request->department_id,
            'status' => 'waiting',
            'position' => $queueNumber,
        ]);

        return redirect()->route('queues.create')
                         ->with('success', 'Patient registered successfully! Queue Number: ' . $formattedQueueNumber);
    }

    // Show queue status for patients
    public function checkStatus()
    {
        return view('queues.check-status');
    }

    // Process queue check by NRIC
    public function getStatus(Request $request)
    {
        $request->validate([
            'patient_nric' => 'required|string|max:20',
        ]);

        $queue = Queue::where('patient_nric', $request->patient_nric)
                     ->whereDate('created_at', today())
                     ->first();

        if (!$queue) {
            return back()->with('error', 'No queue found with this NRIC for today.');
        }

        // Calculate position (how many people before this patient)
        $position = Queue::where('department_id', $queue->department_id)
                        ->whereDate('created_at', today())
                        ->where('id', '<', $queue->id)
                        ->where('status', 'waiting')
                        ->count();

        $currentlyCalled = Queue::where('department_id', $queue->department_id)
                               ->whereDate('created_at', today())
                               ->where('status', 'called')
                               ->orderBy('updated_at', 'desc')
                               ->first();

        return view('queues.status-result', compact('queue', 'position', 'currentlyCalled'));
    }

    // Show QR code page
    public function showQR()
    {
        return view('queues.qr-page');
    }

    // Show queue management page for staff
    public function manage()
    {
        $user = Auth::user();
        $departments = \App\Models\Department::all();
        
        // For admin, show all queues; for staff, show only their department
        if ($user->isAdmin()) {
            $queues = Queue::whereDate('created_at', today())
                          ->orderBy('department_id')
                          ->orderBy('position', 'asc')
                          ->get();
        } else {
            $queues = Queue::where('department_id', $user->department_id)
                          ->whereDate('created_at', today())
                          ->orderBy('position', 'asc')
                          ->get();
        }

        return view('queues.manage', compact('queues', 'departments'));
    }

    // Call next patient and send SMS notifications
    public function callNext(Request $request, $departmentId)
    {
        $department = Department::findOrFail($departmentId);
        
        // Find the next waiting patient
        $nextQueue = Queue::where('department_id', $departmentId)
                         ->whereDate('created_at', today())
                         ->where('status', 'waiting')
                         ->orderBy('position', 'asc')
                         ->first();

        if ($nextQueue) {
            // Update current patient status to "called"
            $nextQueue->update(['status' => 'called']);

            // Send SMS to patients who are 3 positions ahead
            $this->sendAdvanceNotifications($departmentId, $nextQueue->position);

            return redirect()->back()->with('success', 'Called: ' . $nextQueue->queue_number);
        }

        return redirect()->back()->with('error', 'No more patients in queue');
    }

    // Send SMS to patients who are about to be called
    protected function sendAdvanceNotifications($departmentId, $currentPosition)
    {
        $smsService = new TwilioSmsService();
        
        // Find patients who are 3 positions ahead of current
        $patientsToNotify = Queue::where('department_id', $departmentId)
                            ->whereDate('created_at', today())
                            ->where('status', 'waiting')
                            ->where('position', '<=', $currentPosition + 3)
                            ->where('position', '>', $currentPosition)
                            ->get();

        foreach ($patientsToNotify as $patient) {
            $positionAhead = $patient->position - $currentPosition;
            $smsService->sendQueueNotification(
                $patient->patient_phone,
                $patient->queue_number,
                $positionAhead,
                $patient->department->name
            );
        }
    }

    public function call(Queue $queue) {
        // Mark queue as called
    }

    public function complete(Queue $queue) {
        // Mark queue as completed
    }

    public function cancel(Queue $queue) {
        // Cancel queue
    }

    public function callFirst() {
        // Call first patient in queue
    }
    }