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
                $table->string('company_hr_gender')->nullable()->after('company_hr_full_name');
                $table->date('company_hr_birthday')->nullable()->after('company_hr_gender');
                $table->string('company_hr_address')->nullable()->after('company_hr_birthday');
                $table->string('hr_contact_number')->nullable()->after('company_hr_address');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
