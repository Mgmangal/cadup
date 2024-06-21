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
        Schema::create('air_crafts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('aircraft_cateogry')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('type_model')->nullable();
            $table->string('call_sign')->nullable();
            $table->enum('me_se',['SE','ME'])->default('SE');
            $table->date('operation_start_date')->nullable();
            $table->date('operation_end_date')->nullable();
            $table->json('pilots')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('air_crafts');
    }
};
