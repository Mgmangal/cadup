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
        Schema::create('data_transfers', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('data_type'); // Type of data being transferred
            $table->unsignedBigInteger('data_id'); // ID of the user from whom data is transferred
            $table->string('from_section'); // Section from which data is transferred
            $table->unsignedBigInteger('from_user'); // ID of the user from whom data is transferred
            $table->string('to_section'); // Section to which data is transferred
            $table->unsignedBigInteger('to_user'); // ID of the user to whom data is transferred
            $table->text('description')->nullable(); // Description of the transfer
            $table->string('status')->default('pending'); // Status of the transfer
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraints (optional, if you have users table)
            $table->foreign('from_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('to_user')->references('id')->on('users')->onDelete('cascade');
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_transfers');
    }
};
