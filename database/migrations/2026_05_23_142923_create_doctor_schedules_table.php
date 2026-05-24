<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->unsignedBigInteger('doctor_id');
            $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->integer('slot_duration')->default(30)->comment('Duration in minutes');
            $table->timestamp('created_at')->nullable()->useCurrent();
            
            // Indexes
            $table->primary('schedule_id');
            $table->index('doctor_id');
            $table->index('day_of_week');
            $table->index('is_available');
            $table->index(['doctor_id', 'day_of_week']);
            $table->index(['doctor_id', 'is_available']);
            
            // Foreign key
            $table->foreign('doctor_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};