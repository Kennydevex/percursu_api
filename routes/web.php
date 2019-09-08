<?php
Route::prefix('api')->group(function () {
    Route::group(['namespace' => 'System', 'prefix' => 'v1',], function ($router) {
        Route::get('users', 'UserController@index');
        Route::post('user', 'UserController@store');
    });
});
