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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ic_number');
            $table->string('ic_colour');
            $table->date('date_of_birth');
            $table->integer('age');
            $table->string('gender');
            $table->string('citizenship');
            $table->string('nationality');
            $table->string('race');
            $table->string('religion');
            $table->date('starting_of_service_date');
            $table->integer('years_of_service');
            $table->string('trained_untrained');
            $table->string('terms_of_service');
            $table->string('highest_academic_qualification');
            $table->string('current_position');
            $table->date('current_position_appointment_date');
            $table->string('salary_scale');
            $table->string('role');
            $table->string('area_of_expertise');
            $table->string('bdqf_level_taught');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
