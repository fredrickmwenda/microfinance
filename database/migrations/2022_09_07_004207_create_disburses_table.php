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
        Schema::create('disburses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('loan_id');
            $table->integer('disbursed_by');
            $table->integer('disbursed_to');
            //payment method
            $table->enum('payment_method', ['cash', 'mpesa', 'cheque', 'bank']);
            // $table->string('mpesa_code')->nullable();
            // $table->string('cheque_number')->nullable();
            // $table->string('bank_name')->nullable();
            // $table->string('account_number')->nullable();
            // $table->string('account_name')->nullable();
            // $table->string('branch')->nullable();
            // $table->string('branch_code')->nullable();
            $table->string('result_type')->nullable();
            $table->string('result_code')->nullable();
            $table->string('result_desc')->nullable();
            $table->string('originator_conversation_id')->nullable();
            $table->string('conversation_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->enum ('transaction_status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('transaction_amount')->nullable();
            $table->string('transaction_receipt')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('b2c_working_account_available_funds')->nullable();
            $table->string('b2c_charges_paid_account_available_funds')->nullable();
            $table->string('b2c_receiver_party_public_name')->nullable();
            $table->string('b2c_charges_paid_account_available_funds_currency')->nullable();
           
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
        Schema::dropIfExists('disburses');
    }
};
