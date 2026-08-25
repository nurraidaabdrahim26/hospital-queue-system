<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use App\Models\SmsLog;

class TwilioSmsService
{
    protected $client;
    protected $fromNumber;
    protected $isEnabled;

    public function __construct()
    {
        $this->isEnabled = config('services.twilio.enabled', false);
        
        if ($this->isEnabled) {
            $this->client = new Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );
            $this->fromNumber = config('services.twilio.from');
        }
    }

    /**
     * Send SMS message
     */
    public function sendSms($to, $message)
    {
        // Check if SMS is enabled
        if (!$this->isEnabled) {
            Log::warning('SMS service is disabled. Message not sent.', ['to' => $to]);
            return false;
        }

        try {
            // Format phone number
            $formattedTo = $this->formatPhoneNumber($to);
            
            // Send SMS via Twilio
            $message = $this->client->messages->create(
                $formattedTo,
                [
                    'from' => $this->fromNumber,
                    'body' => $message
                ]
            );

            Log::info('SMS sent successfully', [
                'to' => $formattedTo,
                'message_sid' => $message->sid,
                'status' => $message->status
            ]);

            return [
                'success' => true,
                'message_sid' => $message->sid,
                'status' => $message->status
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send SMS', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send queue alert with template
     */
    public function sendQueueAlert($to, $patientName, $queueNumber, $department, $patientsAhead, $customMessage = null)
    {
        if ($customMessage) {
            $message = $customMessage;
        } else {
            $message = $this->generateQueueAlertMessage($patientName, $queueNumber, $department, $patientsAhead);
        }

        return $this->sendSms($to, $message);
    }

    /**
     * Generate queue alert message based on position
     */
    protected function generateQueueAlertMessage($patientName, $queueNumber, $department, $patientsAhead)
    {
        $settings = \App\Models\SmsSetting::getAllSettings();
        $waitTime = ($settings['default_wait_time'] ?? 5) * $patientsAhead;

        $variables = [
            '{patient_name}' => $patientName,
            '{queue_number}' => $queueNumber,
            '{patients_ahead}' => $patientsAhead,
            '{wait_time}' => $waitTime,
            '{department}' => $department
        ];

        // Select template based on patients ahead
        if ($patientsAhead <= 3 && $patientsAhead > 0) {
            $templateKey = $patientsAhead . '_ahead_message';
            $template = $settings[$templateKey] ?? $this->getDefaultMessage($patientsAhead);
        } elseif ($patientsAhead === 0) {
            $template = $settings['immediate_call_message'] ?? $this->getDefaultImmediateCallMessage();
        } else {
            $template = "Dear {patient_name}, your queue number {queue_number} has been registered at {department}.
            There are currently {patients_ahead} patients ahead of you. We will notify you when there are only 3 patients remaining.";
        }

        return str_replace(array_keys($variables), array_values($variables), $template);
    }

    /**
     * Get default message templates
     */
    protected function getDefaultMessage($patientsAhead)
    {
        $messages = [
            3 => "Dear {patient_name}, Alert! Your queue number {queue_number} is coming up soon. 
            There are only {patients_ahead} patients ahead of you. Please proceed to the waiting area. Expected wait time: approximately {wait_time} minutes.",
            2 => "Dear {patient_name}, Get ready! Your queue number {queue_number} will be called soon. 
            Only {patients_ahead} patients ahead of you. Please proceed to waiting area. Estimated wait: {wait_time} minutes.",
            1 => "Dear {patient_name}, You're next! Queue number {queue_number} has only 1 patient ahead. 
            Please be at the waiting area. Your turn is in about {wait_time} minutes."
        ];

        return $messages[$patientsAhead] ?? "Dear {patient_name}, your queue number {queue_number} update: {patients_ahead} patients ahead.";
    }

    protected function getDefaultImmediateCallMessage()
    {
        return "URGENT: Dear {patient_name}, your queue number {queue_number} is NOW BEING CALLED. Please proceed to the counter immediately.";
    }

    /**
     * Get Twilio account balance
     */
    public function getBalance()
    {
        if (!$this->isEnabled) {
            return null;
        }

        try {
            return $this->client->balance->fetch();
        } catch (\Exception $e) {
            Log::error('Failed to fetch Twilio balance', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Format phone number to E.164 format
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters except +
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // If phone doesn't start with +, assume it's local and add country code
        if (!str_starts_with($phone, '+')) {
            // Default to Malaysia country code if no country code provided
            $phone = '+60' . ltrim($phone, '0');
        }
        
        return $phone;
    }

    /**
     * Check if SMS service is enabled
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Validate phone number format
     */
    public function validatePhoneNumber($phone)
    {
        $formatted = $this->formatPhoneNumber($phone);
        
        // Basic E.164 format validation
        return preg_match('/^\+[1-9]\d{1,14}$/', $formatted);
    }
}