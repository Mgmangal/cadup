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
        Schema::create('master_assigns', function (Blueprint $table) {
            $table->id();
            $table->integer('master_id');
            $table->integer('certificate_id');
            $table->enum('is_mendatory',['yes','no'])->default('no');
            $table->enum('is_active',['yes','no'])->default('yes'); 
            $table->enum('is_for',['user','aircraft'])->default('user');  
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
        Schema::dropIfExists('master_assigns');
    }
};
