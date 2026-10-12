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
        Schema::create('case_contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Polymorphic link.
            $table->morphs('owner');

            $table->uuid('master_contact_id')->nullable(true); // Optional link for drift-detection
            $table->string('role')->nullable(false);
            $table->string('relationship_to_subject')->nullable(true);
            $table->string('first_name')->nullable(false);
            $table->string('middle_name')->nullable(true);
            $table->string('last_name')->nullable(false);
            $table->string('address_street')->nullable(true);
            $table->string('address_city')->nullable(true);
            $table->string('address_state_province')->nullable(true);
            $table->string('address_zip_postal_code')->nullable(true);
            $table->string('email_address')->nullable(true);
            $table->string('phone_number')->nullable(true);
            $table->boolean('is_sync_ignored')->default(false);

            $table->softDeletes()->index(); // Adds 'deleted_at' timestamp column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_contacts');
    }
};
