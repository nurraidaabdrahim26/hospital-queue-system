<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('queue_number');
            $table->string('patient_name');
            $table->string('patient_nric'); // Malaysian IC number
            $table->string('patient_phone');
            $table->text('patient_address')->nullable();
            $table->foreignId('department_id')->constrained('departments');
            $table->string('status')->default('waiting'); // waiting, called, completed
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
