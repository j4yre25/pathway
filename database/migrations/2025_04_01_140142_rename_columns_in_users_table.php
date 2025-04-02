<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename the columns
            $table->renameColumn('company_telephone_number', 'telephone_number');
            $table->renameColumn('company_contact_number', 'contact_number');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert the column names back to their original names
            $table->renameColumn('telephone_number', 'company_telephone_number');
            $table->renameColumn('contact_number', 'company_contact_number');
        });
    }
};
