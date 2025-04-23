<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Add new columns based on the template
            $table->string('graduate_projects_title')->after('id'); // Project title
            $table->text('graduate_projects_description')->nullable()->after('graduate_projects_title'); // Project description
            $table->string('graduate_projects_role')->after('graduate_projects_description'); // Role in the project
            $table->date('graduate_projects_start_date')->nullable()->after('graduate_projects_role'); // Start date
            $table->date('graduate_projects_end_date')->nullable()->after('graduate_projects_start_date'); // End date
            $table->string('graduate_projects_url')->nullable()->after('graduate_projects_end_date'); // Project URL
            $table->text('graduate_projects_tech')->nullable()->after('graduate_projects_url'); // Technologies used (comma-separated string)
            $table->text('graduate_projects_key_accomplishments')->nullable()->after('graduate_projects_tech'); // Key accomplishments
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop the columns if the migration is rolled back
            $table->dropColumn('graduate_projects_title');
            $table->dropColumn('graduate_projects_description');
            $table->dropColumn('graduate_projects_role');
            $table->dropColumn('graduate_projects_start_date');
            $table->dropColumn('graduate_projects_end_date');
            $table->dropColumn('graduate_projects_url');
            $table->dropColumn('graduate_projects_tech');
            $table->dropColumn('graduate_projects_key_accomplishments');
        });
    }
}