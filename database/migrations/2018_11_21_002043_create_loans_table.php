<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Author:  Rohit Pandita(rohit3nov@gmail.com)
 */
class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(true);
            $table->integer('user_id', false, true);
            $table->foreign('user_id')->references('id')->on('users');
            $table->double('amount', 18, 2)->unsigned();
            $table->integer('duration')->unsigned();
            $table->integer('repayment_frequency')->unsigned();
            $table->double('interest_rate', 3, 2)->unsigned();
            $table->longText('remarks')->nullable();
            $table->timestamp('date_contract_start')->nullable();
            $table->timestamp('date_contract_end')->nullable();
            $table->tinyInteger('status')->default('-1');
            $table->timestamps();
            $table->softDeletes();
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
}
