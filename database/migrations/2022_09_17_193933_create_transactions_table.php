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
            $table->integer('customer_id');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_reference')->nullable();
            $table->string('transaction_code')->nullable();
            //get from the webhook
            $table->string('transaction_amount')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('remaining_balance')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('transaction_details')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('transaction_bill_number')->nullable();
            $table->string('transaction_order_amount')->nullable();
            $table->string('transaction_service_charge')->nullable();
            $table->string('transaction_served_by')->nullable();
            $table->string('transaction_remarks')->nullable();
            //bank details
            $table->string('bank_reference')->nullable();
            $table->string('bank_transaction_type')->nullable();
            $table->string('bank_account')->nullable();
            //callback type
            $table->string('callback_type')->nullable();
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
