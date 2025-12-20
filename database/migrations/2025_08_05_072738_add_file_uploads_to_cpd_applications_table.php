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
            // File upload fields for various documents
            $table->string('event_brochure')->nullable()->comment('Event brochure or flyer');
            $table->string('paper_abstract')->nullable()->comment('Paper abstract or summary');
            $table->string('previous_certificate')->nullable()->comment('Certificate from previous event');
            $table->string('publication_paper')->nullable()->comment('Published paper or article');
            $table->string('travel_documents')->nullable()->comment('Travel itinerary or documents');
            $table->string('accommodation_details')->nullable()->comment('Accommodation booking details');
            $table->string('budget_breakdown')->nullable()->comment('Detailed budget breakdown');
            $table->string('other_documents')->nullable()->comment('Other supporting documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cpd_applications', function (Blueprint $table) {
            $table->dropColumn([
                'event_brochure',
                'paper_abstract', 
                'previous_certificate',
                'publication_paper',
                'travel_documents',
                'accommodation_details',
                'budget_breakdown',
                'other_documents'
            ]);
        });
    }
};
