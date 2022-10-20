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
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique()->nullable();
            $table->string('phone');
            $table->string('national_id');
            $table->integer('created_by');
            $table->integer('branch_id');
            $table->enum('status', ['active', 'inactive']);
            $table->string('guarantor_first_name');
            $table->string('guarantor_last_name');
            $table->string('guarantor_phone');
            $table->integer('guarantor_national_id');
            $table->string('guarantor_address')->nullable();
            $table->string('guarantor_email')->unique()->nullable();
            $table->string('referee_first_name');
            $table->string('referee_last_name');
            $table->string('referee_relationship');
            $table->string('referee_phone');
            $table->string('next_of_kin_first_name');
            $table->string('next_of_kin_last_name');
            $table->string('next_of_kin_phone');
            $table->string('next_of_kin_relationship');
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
        Schema::dropIfExists('customers');
    }
};
