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
        Schema::create('cpd_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpd_application_id')->constrained('cpd_applications')->onDelete('cascade');
            $table->foreignId('recommended_by_user_id')->constrained('users')->onDelete('cascade');
            
            // Section 1: Details of Applicant
            $table->string('applicant_name');
            $table->string('applicant_post');
            $table->string('programme_area');
            $table->string('faculty_school_centre_section');
            
            // Section 2: Details of Event
            $table->enum('event_type', ['Training Course', 'Meeting', 'Conference', 'Seminar', 'Workshop', 'Working Visit', 'Work Placement', 'Industrial Placement']);
            $table->string('event_title');
            $table->date('event_date');
            
            // Section 3: Suitability in Attending Event
            $table->text('suitability_statement');
            $table->text('reasons_for_recommendation');
            $table->text('expectations_upon_completion');
            $table->text('recommended_benefits_allowances');
            $table->string('staff_recommended_to_discharge_duties');
            
            // Section 4: Comments and Recommendation of Dean/Director
            $table->text('dean_comments_recommendation')->nullable();
            $table->boolean('has_contributed_to_consultancy_fund')->default(false);
            $table->text('consultancy_fund_details')->nullable();
            $table->boolean('recommend_use_consultancy_fund')->default(false);
            $table->text('consultancy_fund_rejection_reason')->nullable();
            
            // Signatures and dates
            $table->string('programme_leader_name');
            $table->string('programme_leader_post');
            $table->timestamp('programme_leader_signed_at')->nullable();
            
            $table->string('dean_name');
            $table->string('dean_post');
            $table->timestamp('dean_signed_at')->nullable();
            
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpd_recommendations');
    }
};
