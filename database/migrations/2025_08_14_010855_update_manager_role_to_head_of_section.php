<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update any existing users with 'Manager' role to 'Head Of Section'
        DB::table('users')
            ->where('role', 'Manager')
            ->update(['role' => 'Head Of Section']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert any users with 'Head Of Section' role back to 'Manager'
        DB::table('users')
            ->where('role', 'Head Of Section')
            ->update(['role' => 'Manager']);
    }
};
