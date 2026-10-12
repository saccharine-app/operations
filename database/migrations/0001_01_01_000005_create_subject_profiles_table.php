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
        Schema::create('subject_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Polymorphic link. This ensures 1:1 row isolation while sharing the table schema.
            $table->uuidMorphs('owner');

            // Biographical Data
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('last_name_at_birth')->nullable();

            // Add a set of virtual columns for computed names (name, full_name, sort_name)
            // $table->computedNames(hasMiddleName: true); 

            // Creates a lightning-fast B-Tree index on the pre-computed column.
            // $table->index('full_name');

            // Vital Statistics
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            
            // Demographics & Identity
            $table->string('ssn_sin')->nullable();
            $table->boolean('ssn_sin_verified')->default(false);
            $table->string('marital_status')->nullable();
            $table->string('sex')->nullable();
            $table->string('occupation')->nullable();
            $table->string('industry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_profiles');
    }
};
