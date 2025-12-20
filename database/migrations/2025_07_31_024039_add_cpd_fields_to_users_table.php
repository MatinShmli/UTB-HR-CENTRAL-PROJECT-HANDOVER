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
            $table->string('post')->nullable()->after('passport_number');
            $table->string('programme_area')->nullable()->after('post');
            $table->string('faculty_school_centre_section')->nullable()->after('programme_area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'post',
                'programme_area',
                'faculty_school_centre_section'
            ]);
        });
    }
};
