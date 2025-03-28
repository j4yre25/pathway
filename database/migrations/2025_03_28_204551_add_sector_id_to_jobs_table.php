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
        Schema::table('jobs', function (Blueprint $table) {
            $table->foreignId('sector_id')->nullable()->constrained()->onDelete('cascade'); // Add the sector_id column
        });
    }
    
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign(['sector_id']); // Drop foreign key if it exists
            $table->dropColumn('sector_id'); // Remove the column if rolling back
        });
    }
};
