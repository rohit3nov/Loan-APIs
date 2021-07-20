<?php
/*
 * Author: Rohit Pandita(rohit3nov@gmail.com)
 */

Route::group(['middleware' => ['auth:api','auth.api.once'], 'prefix' => 'v1'], function () {
    $controller = "\App\Components\CoreComponent\Modules\Repayment\RepaymentController";
    Route::post('/repayments/get/{id}', $controller . '@apiGetRepayment')->name('api.repayments.get');
    Route::post('/repayments/pay/{id}', $controller . '@apiPay')->name('api.repayments.pay');
});
