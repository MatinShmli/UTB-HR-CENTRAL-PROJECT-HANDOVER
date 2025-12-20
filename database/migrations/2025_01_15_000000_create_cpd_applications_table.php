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
        Schema::create('cpd_applications', function (Blueprint $table) {
            $table->id();
            
            // Section 1: Applicant Details
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ic_number');
            $table->string('passport_number')->nullable();
            $table->string('post');
            $table->string('programme_area');
            $table->string('faculty_school_centre_section');
            
            // Section 2: Event Details
            $table->enum('event_type', ['Training Course', 'Meeting', 'Conference', 'Seminar', 'Workshop', 'Working Visit', 'Work Placement', 'Industrial Placement']);
            $table->string('event_title');
            $table->date('event_date');
            $table->string('venue');
            $table->string('host_country');
            $table->string('organiser_name');
            
            // Registration Fee
            $table->decimal('registration_fee', 10, 2)->nullable();
            $table->date('registration_fee_deadline')->nullable();
            $table->enum('payment_preference', ['bursar_office', 'pay_first_submit_receipt', 'pay_first_reimburse']);
            $table->text('registration_fee_includes')->nullable();
            $table->text('other_benefits')->nullable();
            
            // Paper to be presented
            $table->string('paper_title_1')->nullable();
            $table->boolean('is_first_author_1')->default(false);
            $table->string('paper_title_2')->nullable();
            $table->boolean('is_first_author_2')->default(false);
            $table->text('relevance_to_post');
            $table->text('expectations_upon_completion');
            $table->string('publication_name')->nullable();
            
            // Section 3: Previous Event
            $table->enum('previous_event_type', ['Training Course', 'Meeting', 'Conference', 'Seminar', 'Workshop', 'Working Visit', 'Work Placement', 'Industrial Placement'])->nullable();
            $table->string('previous_event_title')->nullable();
            $table->date('previous_event_date')->nullable();
            $table->string('previous_event_venue')->nullable();
            $table->boolean('paper_published')->default(false);
            $table->string('publication_details')->nullable();
            $table->boolean('report_submitted')->default(false);
            
            // Section 4: Logistics
            // Flight
            $table->boolean('airfare_provided_by_organiser')->default(false);
            $table->string('flight_route')->nullable();
            $table->date('departure_date')->nullable();
            $table->date('arrival_date')->nullable();
            $table->enum('ticket_purchase_preference', ['cpd_secretariat', 'purchase_self_submit', 'purchase_self_reimburse'])->nullable();
            
            // Accommodation
            $table->boolean('accommodation_provided_by_organiser')->default(false);
            $table->string('hotel_name_address')->nullable();
            $table->boolean('accommodation_similar_to_venue')->default(false);
            
            // Transportation
            $table->boolean('airport_transfer_provided')->default(false);
            $table->boolean('daily_transportation_provided')->default(false);
            
            // Financial Support
            $table->boolean('daily_allowance_provided')->default(false);
            $table->string('allowance_amount')->nullable();
            
            // Visa
            $table->boolean('visa_required')->default(false);
            
            // Section 5: Consultancy Fund
            $table->boolean('contributed_to_consultancy_fund')->default(false);
            $table->decimal('contribution_amount', 10, 2)->nullable();
            $table->decimal('entitlement_amount', 10, 2)->nullable();
            $table->text('fund_purpose')->nullable();
            
            // Application Status
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected'])->default('draft');
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpd_applications');
    }
}; 