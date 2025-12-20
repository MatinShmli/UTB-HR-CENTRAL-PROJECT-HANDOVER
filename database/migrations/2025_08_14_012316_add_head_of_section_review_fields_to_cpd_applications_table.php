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
            $table->text('head_of_section_notes')->nullable()->after('admin_notes');
            $table->timestamp('head_of_section_reviewed_at')->nullable()->after('head_of_section_notes');
            $table->unsignedBigInteger('head_of_section_reviewed_by')->nullable()->after('head_of_section_reviewed_at');
            
            // Add foreign key constraint
            $table->foreign('head_of_section_reviewed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cpd_applications', function (Blueprint $table) {
            $table->dropForeign(['head_of_section_reviewed_by']);
            $table->dropColumn(['head_of_section_notes', 'head_of_section_reviewed_at', 'head_of_section_reviewed_by']);
        });
    }
};
