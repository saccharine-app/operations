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
        Schema::create('case_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
        
            // Application-enforced Enums
            $table->string('status')->default('lead');
            
            // Dynamic Lookup Pointers
            $table->foreignUuid('assigned_staff_id')->nullable()->constrained('staff_members')->nullOnDelete();
            
            $table->softDeletes()->index(); // Adds 'deleted_at' timestamp column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_files');
    }
};
