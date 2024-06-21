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
        Schema::create('flying_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('pilot1_id');
            $table->string('pilot1_role');
            $table->integer('pilot2_id');
            $table->string('pilot2_role');
            $table->integer('aircraft_id');
            $table->date('date');
            $table->string('fron_sector');
            $table->string('to_sector');
            $table->timestamp('departure_time')->nullable();
            $table->timestamp('arrival_time')->nullable();
            $table->enum('is_night',['yes','no'])->default('no');
            $table->timestamp('night_from_time')->nullable();
            $table->timestamp('night_to_time')->nullable();
            $table->string('flying_type');
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
        Schema::dropIfExists('flying_logs');
    }
};
