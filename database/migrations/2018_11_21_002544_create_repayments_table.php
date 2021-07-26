<?php

use App\Components\CoreComponent\Modules\Repayment\Repayment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Author:  Rohit Pandita(rohit3nov@gmail.com)
 */
class CreateRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(true);
            $table->integer('loan_id', false, true);
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->double('amount', 18, 2)->unsigned();
            $table->integer('payment_status')->unsigned()->comment('1:paid,2:unpaid');
            $table->timestamp('due_date')->nullable();
            $table->timestamp('date_of_payment')->nullable();
            $table->longText('remarks')->nullable();
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
        Schema::dropIfExists('repayments');
    }
}
