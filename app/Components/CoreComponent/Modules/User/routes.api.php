<?php
/*
 * Author: Rohit Pandita(rohit3nov@gmail.com)
 */

Route::group(['prefix' => 'v1'], function () {
    $controller = "\App\Components\CoreComponent\Modules\User\UserController";
    Route::post('/users/register', $controller . '@apiRegisterUser')->name('api.users.register');
    Route::post('/users/login', $controller . '@apiLoginUser')->name('api.users.login');
});
