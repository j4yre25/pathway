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
            // Add new columns
            $table->string('company_hr_first_name')->after('company_hr_full_name')->nullable();
            $table->string('company_hr_last_name')->after('company_hr_first_name')->nullable();
        });

        // Split the existing data
        DB::table('users')->get()->each(function ($record) {
            $fullName = $record->company_hr_full_name;
            $nameParts = explode(' ', $fullName, 2); // Split into first and last name

            DB::table('users')
                ->where('id', $record->id)
                ->update([
                    'company_hr_first_name' => $nameParts[0] ?? null,
                    'company_hr_last_name' => $nameParts[1] ?? null,
                ]);
        });

        // Drop the old column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_hr_full_name');
            $table->dropColumn('company_hr_address');
            $table->dropColumn('company_hr_gender');
            $table->dropColumn('company_hr_dob');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
