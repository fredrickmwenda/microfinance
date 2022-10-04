<?php

Route::group(['middleware' => ['web']], function () {

    Route::prefix(config('user-manager.path'))->group(function () {

        Route::resource('/users', 'Tafaqari\UserManager\Controllers\UserController');
        Route::resource('/roles', 'Tafaqari\UserManager\Controllers\RoleController');
        Route::resource('/permissions', 'Tafaqari\UserManager\Controllers\PermissionController');

    });
});