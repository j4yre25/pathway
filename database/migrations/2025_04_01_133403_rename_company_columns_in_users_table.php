<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCompanyColumnsInUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename the columns
            $table->renameColumn('company_hr_dob', 'dob');
            $table->renameColumn('company_hr_gender', 'gender');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert the column names back to their original names
            $table->renameColumn('dob', 'company_dob');
            $table->renameColumn('gender', 'company_gender');
        });
    }
}