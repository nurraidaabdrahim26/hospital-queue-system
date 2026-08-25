<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->text('message');
            $table->enum('type', ['manual', 'queue_alert', 'call_notification', 'test', 'auto_alert']);
            $table->enum('status', ['sent', 'failed', 'delivered', 'undelivered'])->default('sent');
            $table->foreignId('queue_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sent_by')->constrained('users')->onDelete('cascade');
            $table->text('error_message')->nullable();
            $table->string('message_sid')->nullable(); // Twilio message SID
            $table->timestamps();
            
            $table->index(['type', 'created_at']);
            $table->index('queue_id');
            $table->index('sent_by');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms_logs');
    }
};