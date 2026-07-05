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
        Schema::create('delivery_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reminder_id')->nullable()->constrained()->nullOnDelete();
            $table->string('channel'); // whatsapp, sms, email
            $table->string('provider'); // twilio, smtp, fake
            $table->string('recipient');
            $table->string('subject')->nullable();
            $table->text('body');
            $table->string('status'); // queued, processing, sent, failed
            $table->string('provider_message_id')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_logs');
    }
};
