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
        Schema::create('none_flying_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->timestamp('from_dates')->nullable();
            $table->timestamp('to_dates')->nullable();
            $table->string('reason');
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
        Schema::dropIfExists('none_flying_logs');
    }
};
