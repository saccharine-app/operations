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
        Schema::create('facilities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type'); // Application-enforced enum
            $table->string('name');
            
            // Self-referencing hierarchy
            $table->foreignUuid('parent_facility_id')->nullable();
            
            $table->string('address_street')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_state_province')->nullable();
            $table->string('address_country')->nullable();
            $table->string('address_postal_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('license_number')->nullable();
            
            $table->jsonb('extended_meta')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });

        // Apply the self-referencing constraint AFTER the primary key exists
        Schema::table('facilities', function (Blueprint $table) {
            $table->foreign('parent_facility_id')
                ->references('id')
                ->on('facilities')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
