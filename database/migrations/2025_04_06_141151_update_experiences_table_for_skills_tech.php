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
        Schema::table('experiences', function (Blueprint $table) {
            // Add a JSON column for graduate_experience_skills_tech
            $table->json('graduate_experience_skills_tech')->nullable()->after('graduate_experience_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            // Drop the graduate_experience_skills_tech column
            $table->dropColumn('graduate_experience_skills_tech');
        });
    }
};