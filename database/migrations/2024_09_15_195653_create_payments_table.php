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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Nullable user_id
            $table->string('payment_for'); // e.g., 'sfa', 'bill'
            $table->unsignedBigInteger('reference_id')->nullable(); // Nullable reference_id
            $table->string('payment_method'); // e.g., 'credit_card', 'paypal'
            $table->string('status'); // e.g., 'pending', 'completed', 'failed'
            $table->text('payment_details'); // JSON or text details about the payment
            $table->decimal('amount', 8, 2); // Amount with 2 decimal places
            $table->timestamps();
            // Add foreign key constraint if users table exists
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
