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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->date('birthday')->nullable();
            $table->date('anniversary_date')->nullable();
            $table->date('premium_due_date')->nullable();
            $table->string('policy_name')->nullable();
            $table->string('policy_number')->nullable();
            $table->string('company_name')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
