<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullName');
            $table->string('graduate_professional_title')->nullable();
            $table->string('graduate_email')->unique();
            $table->string('graduate_phone')->nullable();
            $table->string('graduate_location')->nullable();
            $table->date('graduate_birthdate')->nullable();
            $table->string('graduate_gender')->nullable();
            $table->string('graduate_ethnicity')->nullable();
            $table->string('graduate_address')->nullable();
            $table->text('graduate_about_me')->nullable();
            $table->string('graduate_picture_url')->default('path/to/default/image.jpg');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}