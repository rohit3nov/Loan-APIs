<?php
/*
 * Author: Rohit Pandita(rohit3nov@gmail.com)
 */

Route::group(['middleware' => ['auth:api','auth.api.once'], 'prefix' => 'v1'], function () {
    $controller = "\App\Components\CoreComponent\Modules\Loan\LoanController";
    Route::post('/loans/create', $controller . '@apiCreateLoan')->name('api.loans.create');
    Route::post('/loans/update', $controller . '@apiUpdateLoan')->name('api.loans.update');
    Route::post('/loans/get/{id?}', $controller . '@apiGetLoan')->name('api.loans.get');
});
