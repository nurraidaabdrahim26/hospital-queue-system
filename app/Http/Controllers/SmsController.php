<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioSmsService;
use App\Models\Queue;
use App\Models\SmsLog;
use App\Models\SmsSetting;
use Illuminate\Support\Facades\Auth;

class SmsController extends Controller
{
    protected $smsService;

    public function __construct(TwilioSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * SMS Dashboard - Main SMS Management Page
     */
    public function dashboard()
    {
        $balance = $this->getBalance();
        $todayStats = $this->getTodayStats();
        $recentQueues = $this->getRecentQueues();

        return view('sms.dashboard', [
            'balance' => $balance,
            'todayStats' => $todayStats,
            'recentQueues' => $recentQueues,
        ]);
    }

    /**
     * Send Single SMS Form
     */
    public function sendForm()
    {
        return view('sms.send');
    }

    /**
     * Send Single SMS
     */
    public function sendSms(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string|max:160',
        ]);

        $result = $this->smsService->sendSms(
            $request->phone_number,
            $request->message
        );

        if ($result) {
            // Log the SMS
            SmsLog::create([
                'phone_number' => $request->phone_number,
                'message' => $request->message,
                'type' => 'manual',
                'sent_by' => Auth::id(),
                'status' => 'sent'
            ]);

            return redirect()->route('sms.dashboard')->with('success', 'SMS sent successfully!');
        }

        return back()->with('error', 'Failed to send SMS. Please check your settings.');
    }

    /**
     * Send Queue Alert SMS
     */
    public function sendQueueAlert(Request $request)
    {
        $request->validate([
            'queue_id' => 'required|exists:queues,id',
            'phone_number' => 'required|string',
            'patients_ahead' => 'required|integer|min:0',
        ]);

        try {
            $queue = Queue::with('department')->findOrFail($request->queue_id);
            
            // Authorization check
            if (!Auth::user()->isAdmin() && $queue->department_id !== Auth::user()->department_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to send SMS for this patient.'
                ], 403);
            }

            $message = $this->generateQueueMessage($queue, $request->patients_ahead);
            
            $result = $this->smsService->sendSms($request->phone_number, $message);

            if ($result) {
                // Log the SMS
                SmsLog::create([
                    'phone_number' => $request->phone_number,
                    'message' => $message,
                    'type' => 'queue_alert',
                    'sent_by' => Auth::id(),
                    'queue_id' => $queue->id,
                    'status' => 'sent'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Queue alert sent successfully!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to send SMS.'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send Call Notification
     */
    public function sendCallNotification($queueId)
    {
        try {
            $queue = Queue::with('department')->findOrFail($queueId);
            
            // Authorization check
            if (!Auth::user()->isAdmin() && $queue->department_id !== Auth::user()->department_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to send SMS for this patient.'
                ], 403);
            }

            if (empty($queue->patient_phone)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient phone number not available.'
                ], 400);
            }

            $message = "URGENT: Dear {$queue->patient_name}, your queue number {$queue->queue_number} at {$queue->department->name} is NOW BEING CALLED. Please proceed to the counter immediately.";

            $result = $this->smsService->sendSms($queue->patient_phone, $message);

            if ($result) {
                // Log the SMS
                SmsLog::create([
                    'phone_number' => $queue->patient_phone,
                    'message' => $message,
                    'type' => 'call_notification',
                    'sent_by' => Auth::id(),
                    'queue_id' => $queue->id,
                    'status' => 'sent'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Call notification sent successfully!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to send call notification.'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * SMS Settings Page
     */
    public function settings()
    {
        $settings = SmsSetting::all()->pluck('value', 'key')->toArray();
        $balance = $this->getBalance();
        
        return view('sms.settings', [
            'balance' => $balance,
            'settings' => $settings
        ]);
    }

    /**
     * Update SMS Settings
     */
    public function updateSettings(Request $request)
    {
        // Add validation if needed
        $validated = $request->validate([
            'default_wait_time' => 'nullable|integer',
            'sms_character_limit' => 'nullable|integer',
            'auto_send_enabled' => 'required|in:0,1',
            'enable_sms_notifications' => 'required|in:0,1',
        ]);

        foreach ($request->except(['_token', '_method']) as $key => $value) {
            SmsSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        return redirect()->route('sms.settings')->with('success', 'SMS settings updated successfully!');
    }

    /**
     * SMS Test Page
     */
    public function test()
    {
        return view('sms.test', [
            'balance' => $this->getBalance()
        ]);
    }

    /**
     * Send Test SMS
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string',
        ]);

        $result = $this->smsService->sendSms(
            $request->phone_number,
            $request->message
        );

        if ($result) {
            // Log test SMS
            SmsLog::create([
                'phone_number' => $request->phone_number,
                'message' => $request->message,
                'type' => 'test',
                'sent_by' => Auth::id(),
                'status' => 'sent'
            ]);

            return back()->with('success', 'Test SMS sent successfully!');
        }

        return back()->with('error', 'Failed to send test SMS. Please check your Twilio configuration.');
    }

    /**
     * SMS History
     */
    public function history()
    {
        $smsLogs = SmsLog::with(['queue', 'user'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('sms.history', compact('smsLogs'));
    }

    /**
     * Helper Methods
     */
    private function getBalance()
    {
        $balanceObject = $this->smsService->getBalance();
        
        if (is_object($balanceObject) && property_exists($balanceObject, 'balance')) {
            return [
                'amount' => $balanceObject->balance,
                'currency' => $balanceObject->currency ?? 'USD'
            ];
        }

        return ['amount' => 0, 'currency' => 'USD'];
    }

    private function getTodayStats()
    {
        return [
            'sent_today' => SmsLog::whereDate('created_at', today())->count(),
            'queue_alerts' => SmsLog::whereDate('created_at', today())->where('type', 'queue_alert')->count(),
            'call_notifications' => SmsLog::whereDate('created_at', today())->where('type', 'call_notification')->count(),
        ];
    }

    private function getRecentQueues()
    {
        if (!Auth::user()->isAdmin()) {
            return Queue::where('department_id', Auth::user()->department_id)
                        ->whereDate('created_at', today())
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
        }

        return Queue::whereDate('created_at', today())
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
    }

    private function generateQueueMessage($queue, $patientsAhead)
    {
        $settings = SmsSetting::all()->pluck('value', 'key')->toArray();
        $waitTime = ($settings['default_wait_time'] ?? 5) * $patientsAhead;

        $variables = [
            '{patient_name}' => $queue->patient_name,
            '{queue_number}' => $queue->queue_number,
            '{patients_ahead}' => $patientsAhead,
            '{wait_time}' => $waitTime,
            '{department}' => $queue->department->name
        ];

        if ($patientsAhead <= 3 && $patientsAhead > 0) {
            $template = $settings[$patientsAhead . '_ahead_message'] ?? $this->getDefaultMessage($patientsAhead);
        } elseif ($patientsAhead === 0) {
            $template = $settings['immediate_call_message'] ?? $this->getDefaultImmediateCallMessage();
        } else {
            $template = "Dear {patient_name}, your queue number {queue_number} has been registered at {department}. There are currently {patients_ahead} patients ahead of you.";
        }

        return str_replace(array_keys($variables), array_values($variables), $template);
    }

    private function getDefaultMessage($patientsAhead)
    {
        $messages = [
            3 => "Dear {patient_name}, Alert! Your queue number {queue_number} is coming up soon. There are only {patients_ahead} patients ahead of you. Please proceed to the waiting area.",
            2 => "Dear {patient_name}, Get ready! Your queue number {queue_number} will be called soon. Only {patients_ahead} patients ahead of you.",
            1 => "Dear {patient_name}, You're next! Queue number {queue_number} has only 1 patient ahead. Please be at the waiting area."
        ];

        return $messages[$patientsAhead] ?? "Dear {patient_name}, your queue number {queue_number} update: {patients_ahead} patients ahead.";
    }

    private function getDefaultImmediateCallMessage()
    {
        return "URGENT: Dear {patient_name}, your queue number {queue_number} at {department} is NOW BEING CALLED. Please proceed to the counter immediately.";
    }

        public function editSettings()
        {
            $settings = SmsSetting::all()->pluck('value', 'key')->toArray();
            
            return view('sms.settings-edit', [
                'balance' => $this->getBalance(),
                'settings' => $settings
            ]);
        }
    }