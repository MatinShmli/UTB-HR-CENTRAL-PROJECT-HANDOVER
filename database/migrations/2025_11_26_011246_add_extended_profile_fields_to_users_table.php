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
        Schema::table('users', function (Blueprint $table) {
            // Personal Information - Additional fields
            $table->string('place_of_birth')->nullable()->after('birthdate');
            $table->string('citizenship')->nullable()->after('nationality');
            $table->text('address_of_country_of_origin')->nullable()->after('address');
            $table->text('address_in_brunei')->nullable()->after('address_of_country_of_origin');
            
            // Employment Information
            $table->string('position')->nullable()->after('faculty_school_centre_section');
            $table->string('faculty')->nullable()->after('position');
            $table->string('department')->nullable()->after('faculty');
            $table->string('employment_type')->nullable()->after('department');
            $table->string('salary_scale')->nullable()->after('employment_type');
            $table->decimal('current_salary', 10, 2)->nullable()->after('salary_scale');
            $table->string('duration_of_appointment')->nullable()->after('current_salary');
            
            // Spouse Information (all optional)
            $table->string('spouse_full_name')->nullable()->after('duration_of_appointment');
            $table->string('spouse_ic_number')->nullable()->after('spouse_full_name');
            $table->string('spouse_citizenship')->nullable()->after('spouse_ic_number');
            $table->string('spouse_workplace')->nullable()->after('spouse_citizenship');
            $table->string('spouse_position')->nullable()->after('spouse_workplace');
            $table->string('spouse_department')->nullable()->after('spouse_position');
            $table->text('spouse_address_country_of_origin')->nullable()->after('spouse_department');
            $table->text('spouse_address_brunei')->nullable()->after('spouse_address_country_of_origin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'place_of_birth',
                'citizenship',
                'address_of_country_of_origin',
                'address_in_brunei',
                'position',
                'faculty',
                'department',
                'employment_type',
                'salary_scale',
                'current_salary',
                'duration_of_appointment',
                'spouse_full_name',
                'spouse_ic_number',
                'spouse_citizenship',
                'spouse_workplace',
                'spouse_position',
                'spouse_department',
                'spouse_address_country_of_origin',
                'spouse_address_brunei',
            ]);
        });
    }
};
