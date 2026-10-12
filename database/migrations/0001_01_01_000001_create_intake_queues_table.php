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
        Schema::create('intake_queues', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Explicit UUID primary key
            $table->enum('status', ['pending', 'promoted', 'spam'])->default('pending');
            $table->string('submitted_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->jsonb('raw_payload')->nullable(); // Captures unpredictable webhooks
            $table->string('source_channel')->nullable();
            $table->timestamps(); // Automatically handles created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intake_queues');
    }
};
