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
        Schema::table('cpd_applications', function (Blueprint $table) {
            // Modify the status enum to include 'rework'
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected', 'rework'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cpd_applications', function (Blueprint $table) {
            // Revert back to original enum values
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected'])->default('draft')->change();
        });
    }
};
