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
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('loan_id');
            $table->integer('customer_id');

            #the admin who is giving the loan
            $table->integer('created_by');
            #loan amount
            $table->integer('approved_by')->nullable();
            $table->double('loan_amount');
            #loan request payment is set to 1500 this is the amount the customer will pay to request for a loan
            $table->double('processing_fee');
            $table->integer('loan_duration');
            $table->double('loan_interest');
            $table->double('loan_installment_payment')->nullable();
            #loan total payment
            $table->double('loan_total_payment');

            #check if the loan is approved
            $table->enum('loan_status', ['pending', 'approved', 'rejected'])->default('pending');
            #loan type
            $table->enum('loan_payment_type', ['one_time', 'installment'])->default('one_time');
            $table->text('loan_purpose')->nullable();
            #late payment fee
            $table->double('late_payment_fee')->default(0);
            $table->date('approved_at')->nullable();
            $table->date('loan_start_date')->nullable();
            #loan end date
            $table->date('loan_end_date')->nullable();
            #loan first payment date
            $table->date('loan_first_payment_date')->nullable();
            #loan last payment date
            $table->date('loan_last_payment_date')->nullable();

            #number of installments, this is the number of times the customer will pay the loan
            $table->integer('number_of_installments')->nullable();
            #money to be paid per installment
            #status of the loan if is paid, not yet paid, or partially paid, or defaulted
            $table->enum('loan_payment_status', ['paid', 'not_paid', 'partially_paid', 'defaulted'])->default('not_paid');
       
            #loan rejected by, reason for rejection, and date of rejection
            $table->integer('rejected_by')->nullable();
            $table->string('rejected_reason')->nullable();
            $table->date('rejected_at')->nullable();

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
        Schema::dropIfExists('loans');
    }
};
