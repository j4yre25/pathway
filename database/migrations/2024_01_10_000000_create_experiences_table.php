<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('graduate_experience_title');
            $table->string('graduate_experience_company');
            $table->date('graduate_experience_start_date');
            $table->date('graduate_experience_end_date')->nullable();
            $table->string('graduate_experience_address')->nullable();
            $table->text('graduate_experience_description')->nullable();
            $table->enum('graduate_experience_employment_type', [
                'Full-time',
                'Part-time',
                'Contract',
                'Temporary',
                'Internship',
                'Freelance',
                'Remote'
            ]);
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('experiences');
    }
};