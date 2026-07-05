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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); // birthday, anniversary, premium_due, festival, custom
            $table->string('source')->default('system'); // client_field, manual_custom, future_system
            $table->string('title');
            $table->date('event_date')->nullable(); // The actual date of the event
            $table->date('reminder_date'); // The date the reminder should trigger
            $table->string('status')->default('pending'); // pending, sent, failed, cancelled
            $table->timestamp('sent_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
