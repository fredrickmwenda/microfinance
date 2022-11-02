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
            // loan creation
            $table->bigIncrements('id');
            $table->string('loan_id');
            $table->integer('customer_id');
            $table->integer('created_by');
            $table->double('amount');
            $table->double('processing_fee');
            $table->integer('duration');
            $table->double('interest');
            $table->decimal('total_payable', 10, 2);         
            $table->double('total_payment')->nullable();
            $table->double('remaining_balance');
            $table->enum('status', ['pending', 'approved', 'rejected', 'disbursed', 'active','closed', 'overdue','defaulted', 'written_off'])->default('pending');
            $table->double('late_payment_fee')->default(0);
            $table->text('loan_purpose')->nullable();
            $table->enum('payment_type', ['one_time', 'installment'])->default('one_time');
            // if the loan is installment
            $table->double('installment_payment')->nullable();
            $table->integer('number_of_installments')->nullable();
            $table->date('first_payment_date')->nullable();
            $table->date('last_payment_date')->nullable();

            //loan approval
            $table->integer('approved_by')->nullable();
            $table->date('approved_at')->nullable();
            
            //loan rejection
            $table->integer('rejected_by')->nullable();
            $table->string('rejected_reason')->nullable();
            $table->date('rejected_at')->nullable();

            //loan disbursement
            $table->integer('disbursed_by')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('payment_status', ['paid', 'not_paid', 'partially_paid', 'defaulted'])->default('not_paid');

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
