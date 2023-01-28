<?php

declare(strict_types=1);

use Canvas\Http\Controllers\PostController;
use Canvas\Http\Controllers\ProfileController;
use Canvas\Http\Controllers\SearchController;
use Canvas\Http\Controllers\TagController;
use Canvas\Http\Controllers\TopicController;
use Canvas\Http\Controllers\TrafficController;
use Canvas\Http\Controllers\UploadsController;
use Canvas\Http\Controllers\UserController;
use Canvas\Http\Controllers\ViewController;
use Canvas\Http\Middleware\AuthenticateSession;
use Canvas\Http\Middleware\VerifyAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware([AuthenticateSession::class])->group(function () {
    Route::prefix('api')->as('canvas.')->group(function () {
        Route::apiSingleton('profile', ProfileController::class);

        Route::apiResource('posts', PostController::class);

        Route::middleware([VerifyAdmin::class])->group(function () {
            Route::apiResource('tags', TagController::class);
            Route::get('tags/{tag}/posts', [TagController::class, 'posts'])->name('tags.posts');

            Route::apiResource('topics', TopicController::class);
            Route::get('topics/{topic}/posts', [TagController::class, 'posts'])->name('tags.posts');

            Route::apiResource('users', UserController::class);
            Route::get('users/{user}/posts', [UserController::class, 'posts'])->name('users.posts');
        });

        Route::prefix('uploads')->group(function () {
            Route::post('/', [UploadsController::class, 'store'])->name('uploads.store');
            Route::delete('/', [UploadsController::class, 'destroy'])->name('uploads.destroy');
        });

        Route::prefix('search')->group(function () {
            Route::get('posts', [SearchController::class, 'posts'])->name('search.posts');

            Route::middleware([VerifyAdmin::class])->group(function () {
                Route::get('tags', [SearchController::class, 'tags'])->name('search.tags');
                Route::get('topics', [SearchController::class, 'topics'])->name('search.topics');
                Route::get('users', [SearchController::class, 'users'])->name('search.users');
            });
        });

        Route::prefix('traffic')->group(function () {
            Route::get('views', [TrafficController::class, 'views'])->name('traffic.views');
            Route::get('visits', [TrafficController::class, 'visits'])->name('traffic.visits');
            Route::get('chart', [TrafficController::class, 'chart'])->name('traffic.chart');
            Route::get('sources', [TrafficController::class, 'sources'])->name('traffic.sources');
            Route::get('pages', [TrafficController::class, 'pages'])->name('traffic.pages');
            Route::get('countries', [TrafficController::class, 'countries'])->name('traffic.countries');
            Route::get('devices', [TrafficController::class, 'devices'])->name('traffic.devices');
        });
    });

    Route::get('/{view?}', [ViewController::class, 'index'])->where('view', '(.*)')->name('canvas');
});
