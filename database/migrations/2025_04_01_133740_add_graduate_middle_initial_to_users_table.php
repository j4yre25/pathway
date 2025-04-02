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
            // Add the graduate_middle_initial column
            $table->string('graduate_middle_initial')->nullable(); // Adjust the type and constraints as needed
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the graduate_middle_initial column if it exists
            $table->dropColumn('graduate_middle_initial');
        });
    }
};
