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
        Schema::create('user_certificates', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name')->nullable();
            $table->string('number')->nullable();
            $table->date('done_at')->nullable();
            $table->date('renewed_on')->nullable();
            $table->date('valid_till')->nullable();
            $table->date('next_renewal')->nullable();
            $table->text('limitations')->nullable();
            $table->text('examiner_instructor')->nullable();
            $table->enum('result',['Fit','Unfit','Temporary Unfit'])->nullable();
            $table->enum('done_on',['Simulator','Aircraft'])->nullable();
            $table->string('aircraft')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status',['Active','Inactive'])->default('Active');
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
        Schema::dropIfExists('user_certificates');
    }
};
