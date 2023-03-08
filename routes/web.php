<?php

declare(strict_types=1);

use Canvas\Http\Controllers\ImageController;
use Canvas\Http\Controllers\PostController;
use Canvas\Http\Controllers\SearchController;
use Canvas\Http\Controllers\StatController;
use Canvas\Http\Controllers\TagController;
use Canvas\Http\Controllers\TopicController;
use Canvas\Http\Controllers\UserController;
use Canvas\Http\Controllers\ViewController;
use Canvas\Http\Middleware\AuthenticateSession;
use Canvas\Http\Middleware\VerifyAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware([AuthenticateSession::class])->group(function () {
    Route::prefix('api')->as('canvas.')->group(function () {
        Route::prefix('posts')
            ->as('posts.')
            ->group(function () {
                Route::get('/', [PostController::class, 'index'])->name('index');
                Route::get('create', [PostController::class, 'create'])->name('create');
                Route::get('{id}', [PostController::class, 'show'])->name('show');
                Route::get('{id}/stats', [PostController::class, 'stats'])->name('stats');
                Route::put('{id}', [PostController::class, 'store'])->name('store');
                Route::delete('{id}', [PostController::class, 'destroy'])->name('destroy');
            });

        Route::middleware([VerifyAdmin::class])->group(function () {
            Route::prefix('tags')
                ->as('tags.')
                ->group(function () {
                    Route::get('/', [TagController::class, 'index'])->name('index');
                    Route::get('create', [TagController::class, 'create'])->name('create');
                    Route::get('{id}', [TagController::class, 'show'])->name('show');
                    Route::get('{id}/posts', [TagController::class, 'posts'])->name('posts');
                    Route::put('{id}', [TagController::class, 'store'])->name('store');
                    Route::delete('{id}', [TagController::class, 'destroy'])->name('destroy');
                });

            Route::prefix('topics')
                ->as('topics.')
                ->group(function () {
                    Route::get('/', [TopicController::class, 'index'])->name('index');
                    Route::get('create', [TopicController::class, 'create'])->name('create');
                    Route::get('{id}', [TopicController::class, 'show'])->name('show');
                    Route::get('{id}/posts', [TopicController::class, 'posts'])->name('posts');
                    Route::put('{id}', [TopicController::class, 'store'])->name('store');
                    Route::delete('{id}', [TopicController::class, 'destroy'])->name('destroy');
                });
        });

        Route::prefix('users')
            ->as('users.')
            ->group(function () {
                Route::middleware([VerifyAdmin::class])->group(function () {
                    Route::get('/', [UserController::class, 'index'])->name('index');
                    Route::get('create', [UserController::class, 'create'])->name('create');
                    Route::get('{id}/posts', [UserController::class, 'posts'])->name('posts');
                    Route::delete('{id}', [UserController::class, 'destroy'])->name('destroy');
                });

                Route::get('{id}', [UserController::class, 'show'])->name('show');
                Route::put('{id}', [UserController::class, 'store'])->name('store');
            });

        Route::prefix('images')
            ->as('images.')
            ->group(function () {
                Route::put('/', [ImageController::class, 'store'])->name('store');
                Route::delete('/', [ImageController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('search')
            ->as('search.')
            ->group(function () {
                Route::get('posts', [SearchController::class, 'posts'])->name('posts');

                Route::middleware([VerifyAdmin::class])->group(function () {
                    Route::get('tags', [SearchController::class, 'tags'])->name('tags');
                    Route::get('topics', [SearchController::class, 'topics'])->name('topics');
                    Route::get('users', [SearchController::class, 'users'])->name('users');
                });
            });

        Route::prefix('stats')
            ->as('stats.')
            ->group(function () {
                Route::get('views', [StatController::class, 'views'])->name('views');
                Route::get('visits', [StatController::class, 'visits'])->name('visits');
                Route::get('chart', [StatController::class, 'chart'])->name('chart');
                Route::get('sources', [StatController::class, 'sources'])->name('sources');
                Route::get('pages', [StatController::class, 'pages'])->name('pages');
                Route::get('countries', [StatController::class, 'countries'])->name('countries');
                Route::get('devices', [StatController::class, 'devices'])->name('devices');
            });
    });

    Route::get('/{view?}', [ViewController::class, 'index'])->where('view', '(.*)')->name('canvas');
});
