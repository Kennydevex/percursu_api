<?php

Route::prefix('api')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::group(['namespace' => 'Auth', 'middleware' => 'api'], function ($router) {
            Route::post('register', 'AuthController@register');
            Route::post('login', 'AuthController@login');
            Route::get('refresh', 'AuthController@refresh');
            Route::middleware('auth:api')->group(function () {
                Route::get('user', 'AuthController@me');
                Route::post('logout', 'AuthController@logout');
            });
        });

        Route::group(['middleware' => 'jwt.auth'], function () {
            Route::group(['namespace' => 'System', 'middleware' => ['role:supr-admin|admin|create user|edit user']], function ($router) {
                Route::resource('users', 'UserController');
                Route::get('usersWithoutPartner', 'UserController@usersWithoutPartner');
                Route::get('changeUserActivation/{id}', 'UserController@changeUserActivation');
                Route::resource('permissions', 'PermissionController');
                Route::resource('roles', 'RoleController');
            });
        });



        Route::group(['namespace' => 'Percursu'], function ($router) {
            Route::group(['middleware' => 'jwt.auth'], function () {
                Route::group(['middleware' => ['role:super-admin|admin|manager']], function ($router) {
                    Route::resource('partners', 'PartnerController');
                    Route::get('changePartnerActivation/{id}', 'PartnerController@changePartnerActivation');
                    Route::get('changePartnerFeatured/{id}', 'PartnerController@changePartnerFeatured');
                    Route::get('changePartnerPromotion/{id}', 'PartnerController@changePartnerPromotion');
                });
                Route::resource('charges', 'ChargeController');

            });
            Route::get('partner/{username}', 'PartnerController@show');
            Route::get('activedPartners', 'PartnerController@activedPartners');
            Route::get('featuredPartners', 'PartnerController@featuredPartners');
        });

        Route::group(['namespace' => 'Helpers'], function ($router) {
            Route::resource('addresses', 'AddressController');
            Route::resource('avatars', 'AvatarController');
            Route::resource('categories', 'CategoryController');
            Route::resource('couriers', 'CourierController');
            Route::resource('entities', 'EntityController');
            Route::resource('folks', 'FolkController');
            Route::resource('locations', 'LocationController');
            Route::resource('phones', 'PhoneController');
            Route::resource('sites', 'SiteController');
            Route::resource('socials', 'SocialController');
        });

        Route::group(['namespace' => 'CMS', 'middleware' => ['role_or_permission:super-admin|admin|post manager|create post|edit post']], function ($router) {
            Route::resource('posts', 'PostController');
            Route::resource('tags', 'TagController');
        });

        Route::group(['namespace' => 'Helpers'], function ($router) {
            Route::resource('categories', 'CategoryController');
            Route::resource('entities', 'EntityController');
            Route::resource('folks', 'FolkController');
        });

        Route::group(['middleware' => 'jwt.auth'], function () {
            Route::group(['middleware' => ['role_or_permission:super-admin|admin|manager|post manager|create user|admin acess|edit user|create post|edit post']], function ($router) {
                Route::get('adminPanel', function () {
                    return;
                });
            });
        });
    });
});
