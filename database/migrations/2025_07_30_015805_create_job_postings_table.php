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
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('department');
            $table->string('type'); // academic, nonacademic, skippa, tabung
            $table->string('employment_type'); // Full-time, Part-time, Contract, Temporary
            $table->date('deadline');
            $table->text('description');
            $table->text('requirements');
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->integer('applications_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
