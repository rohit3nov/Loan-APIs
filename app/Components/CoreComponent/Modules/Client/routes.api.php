<?php
/*
 * Author: Rohit Pandita(rohit3nov@gmail.com)
 */

Route::group(['middleware' => ['auth:api','auth.api.once'], 'prefix' => 'v1'], function () {
    $controller = "\App\Components\CoreComponent\Modules\Client\ClientController";
    Route::post('/clients/create', $controller . '@apiCreateClient')->name('api.clients.create');
    Route::post('/clients/get/{id?}', $controller . '@apiGetClient')->name('api.clients.get');
});
