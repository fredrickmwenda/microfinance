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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('logo')->nullable();
            $table->json('settings')->nullable();
            $table->string('paybill')->nullable();
            //env variables
            $table->string('env_merchant_id')->nullable();
            $table->string('env_merchant_key')->nullable();
            $table->string('env_merchant_secret')->nullable();
            $table->string('env_merchant_account')->nullable();
            $table->string('env_merchant_email')->nullable();
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
        Schema::dropIfExists('payment_gateways');
    }
};
