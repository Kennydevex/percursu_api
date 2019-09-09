<?php
// Route::prefix('api')->group(function () {
//     Route::group(['namespace' => 'System', 'prefix' => 'v1',], function ($router) {
//         Route::get('users', 'UserController@index');
//         Route::post('user', 'UserController@store');
//     });
// });


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

        // Route::group(['middleware' => 'jwt.auth'], function () {
        // Route::group(['namespace' => 'System', 'middleware' => ['role:super-admin|admin']], function ($router) {
        Route::group(['namespace' => 'System'], function ($router) {
            Route::resource('users', 'UserController');
            Route::get('usersWithoutPartner', 'UserController@usersWithoutPartner');
            Route::get('changeUserActivation/{id}', 'UserController@changeUserActivation');
            Route::resource('permissions', 'PermissionController');
            Route::resource('roles', 'RoleController');
        });
        // });


        Route::group(['namespace' => 'Percursu'], function ($router) {
            Route::resource('partners', 'PartnerController');
            Route::get('changePartnerActivation/{id}', 'PartnerController@changePartnerActivation');

            Route::get('changePartnerFeatured/{id}', 'PartnerController@changePartnerFeatured');
            Route::get('changePartnerPromotion/{id}', 'PartnerController@changePartnerPromotion');
            Route::get('activedPartners', 'PartnerController@activedPartners');
            Route::resource('charges', 'ChargeController');
            Route::resource('experiences', 'ExperienceController');
            Route::resource('formations', 'FormationController');
            Route::resource('medias', 'MediaController');
            Route::resource('skills', 'SkillController');
            Route::resource('companies', 'CompanyController');
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

        Route::group(['namespace' => 'CMS'], function ($router) {
            Route::resource('posts', 'PostController');
            Route::resource('tags', 'TagController');
        });

        Route::group(['namespace' => 'Helpers'], function ($router) {
            Route::resource('categories', 'CategoryController');
            Route::resource('entities', 'EntityController');
            Route::resource('folks', 'FolkController');
        });
    });
});
