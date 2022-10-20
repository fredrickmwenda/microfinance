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
            $table->string('disbursement_id');
            $table->integer('loan_id');
            $table->string('disbursement_amount');
            $table->integer('disbursed_by');
            $table->integer('disbursed_to');
            // transaction code
            $table->string('transaction_code');
            // phone number
            $table->string('phone_number');
            $table->enum('payment_method', ['cash', 'mpesa', 'cheque', 'bank']); 
            $table->enum ('status', ['pending', 'success', 'failed'])->default('pending');      
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
