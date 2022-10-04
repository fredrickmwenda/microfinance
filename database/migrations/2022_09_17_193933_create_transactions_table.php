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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('loan_id');
            $table->integer('user_id');
            $table->integer('branch_id');
            $table->integer('customer_id');
            $table->integer('transaction_type_id');
            $table->integer('payment_gateway_id')->nullable();
            $table->integer('transaction_code')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('transaction_amount')->nullable();
            $table->string('remaining_balance')->nullable();
            //number of transactions
            $table->integer('transaction_count')->nullable();

            // $table->string('result_type')->nullable();
            // $table->string('result_code')->nullable();
            // $table->string('result_desc')->nullable();
            // $table->string('originator_conversation_id')->nullable();
            // $table->string('conversation_id')->nullable();
            // $table->string('transaction_id')->nullable();
            // $table->enum ('transaction_status', ['pending', 'success', 'failed'])->default('pending');
            // $table->string('transaction_amount')->nullable();
            // $table->string('transaction_receipt')->nullable();
            // $table->string('transaction_date')->nullable();
            // $table->string('phone_number')->nullable();
            // $table->string('b2c_working_account_available_funds')->nullable();
            // $table->string('b2c_charges_paid_account_available_funds')->nullable();
            // $table->string('b2c_receiver_party_public_name')->nullable();
            // $table->string('b2c_charges_paid_account_available_funds_currency')->nullable();
            // $table->integer('loan_id')->nullable();
            // $table->integer('payment_gateway_id')->nullable();
            // $table->integer('customer_id')->nullable();
            // $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
