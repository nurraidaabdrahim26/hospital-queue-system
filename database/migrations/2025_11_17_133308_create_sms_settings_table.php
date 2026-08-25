<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->text('description')->nullable();
            $table->string('category')->default('general');
            $table->timestamps();
        });

        // Insert default SMS settings
        $this->insertDefaultSettings();
    }

    private function insertDefaultSettings()
    {
        $settings = [
            // Message Templates
            [
                'key' => 'three_ahead_message',
                'value' => 'Dear {patient_name}, Alert! Your queue number {queue_number} is coming up soon. There are only {patients_ahead} patient(s) ahead of you. Please proceed to the waiting area. Expected wait time: approximately {wait_time} minutes. Thank you.',
                'description' => 'Message template for when there are 3 patients ahead',
                'category' => 'templates'
            ],
            [
                'key' => 'two_ahead_message',
                'value' => 'Dear {patient_name}, Get ready! Your queue number {queue_number} will be called soon. Only {patients_ahead} patients ahead of you. Please proceed to waiting area. Estimated wait: {wait_time} minutes.',
                'description' => 'Message template for when there are 2 patients ahead',
                'category' => 'templates'
            ],
            [
                'key' => 'one_ahead_message',
                'value' => 'Dear {patient_name}, You\'re next! Queue number {queue_number} has only 1 patient ahead. Please be at the waiting area. Your turn is in about {wait_time} minutes.',
                'description' => 'Message template for when there is 1 patient ahead',
                'category' => 'templates'
            ],
            [
                'key' => 'immediate_call_message',
                'value' => 'URGENT: Dear {patient_name}, your queue number {queue_number} at {department} is NOW BEING CALLED. Please proceed to the counter immediately. Thank you.',
                'description' => 'Message template for immediate call notification',
                'category' => 'templates'
            ],
            
            // General Settings
            [
                'key' => 'default_wait_time',
                'value' => '5',
                'description' => 'Default wait time per patient in minutes',
                'category' => 'general'
            ],
            [
                'key' => 'auto_send_enabled',
                'value' => '1',
                'description' => 'Enable auto-send alerts when patients reach certain positions',
                'category' => 'general'
            ],
            [
                'key' => 'sms_character_limit',
                'value' => '160',
                'description' => 'Maximum characters per SMS message',
                'category' => 'general'
            ],
            [
                'key' => 'enable_sms_notifications',
                'value' => '1',
                'description' => 'Enable SMS notifications system-wide',
                'category' => 'general'
            ]
        ];

        foreach ($settings as $setting) {
            \App\Models\SmsSetting::create($setting);
        }
    }

    public function down()
    {
        Schema::dropIfExists('sms_settings');
    }
};