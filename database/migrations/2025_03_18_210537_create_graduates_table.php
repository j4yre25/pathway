<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('graduates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // Change 'institutions' to 'programs' to reference the correct table
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->year('year_graduated');
            $table->enum('employment_status', ['Employed', 'Underemployed', 'Unemployed'])->default('Unemployed');
            $table->string('current_job_title')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('graduates');
    }
};