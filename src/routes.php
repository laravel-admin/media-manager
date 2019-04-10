<?php

Route::group(['middleware' => 'web'], function () {
    Route::group(config('media.routes.frontend.options'), function () {
        Route::get('img/{imagestyle}/{id}/{slug?}', config('media.routes.frontend.controller') . '@img');
        Route::get('file/{id}/{slug?}', config('media.routes.frontend.controller') . '@file');
    });

    if (class_exists(LaravelAdmin\Base\BaseServiceProvider::class)) {
        Route::group(config('admin.route_group') + ['middleware' => config('admin.route_middleware')], function () {
            Route::resource('media/ajax', LaravelAdmin\MediaManager\Controllers\AjaxController::class, ['only' => ['index', 'store', 'show'], 'names' => [
                'index' => config('media.routes.ajax.name') . 'index',
                'store' => config('media.routes.ajax.name') . 'store',
                'show' => config('media.routes.ajax.name') . 'show',
            ]]);

            Route::resource('media', LaravelAdmin\MediaManager\Controllers\CrudController::class);
            Route::resource('media-tiles', LaravelAdmin\MediaManager\Controllers\CrudController::class);
        });
    } else {
        Route::group(config('media.routes.ajax.options'), function () {
            Route::resource('media/ajax', config('media.routes.ajax.controller'), ['only' => ['index', 'store'], 'names' => [
                'index' => config('media.routes.ajax.name') . 'index',
                'store' => config('media.routes.ajax.name') . 'store',
            ]]);
        });

        Route::group(config('media.routes.backend.options'), function () {
            Route::resource('media', config('media.routes.backend.controller'), ['names' => [
                 'index' => config('media.routes.backend.name') . 'index',
                 'create' => config('media.routes.backend.name') . 'create',
                 'store' => config('media.routes.backend.name') . 'store',
                 'edit' => config('media.routes.backend.name') . 'edit',
                 'show' => config('media.routes.backend.name') . 'show',
                 'update' => config('media.routes.backend.name') . 'update',
                 'destroy' => config('media.routes.backend.name') . 'destroy',
            ]]);
        });
    }
});
