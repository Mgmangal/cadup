<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id')->nullable();
            $table->string('salutation')->nullable();
            $table->string('name');
            $table->string('profile')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->integer('designation')->nullable();
            $table->json('department')->nullable();
            $table->json('section')->nullable();
            $table->json('jobfunction')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('user_type', ['admin', 'user'])->default('user');
            $table->string('address')->nullable();
            $table->enum('is_adt',['yes','no'])->default('yes');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
