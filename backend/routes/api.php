<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\NotificationLogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::prefix('v1')->group(function () {
    Route::post('tokens/login', [TokenController::class, 'login']);
    Route::get('tokens/logout', [TokenController::class, 'logout']);
    Route::get('tokens/secret', [TokenController::class, 'getSecret']);
    Route::post('users/register', [UserController::class, 'register']);
    Route::post('users/forgot_password', [UserController::class, 'forgotPassword']);
    Route::post('users/reset_password', [UserController::class, 'resetPassword']);
    Route::get('emails/verify/{id}/{token}', [UserController::class, 'verifyEmail']);
    Route::resource('countries', CountryController::class)->only(['show', 'index']);

    Route::get('cached-translations/{lang}', [TranslationController::class, 'getCachedTranslation']);
    Route::get('cached-translations/{lang}/{namespace}', [TranslationController::class, 'getCachedTranslation']);
    Route::post('missing-translations/{lang}/{namespace}', [TranslationController::class, 'reportMissingTranslation']);

    Route::get('ping', [App\Http\Controllers\SystemController::class, 'ping']);

    Route::middleware('verified')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::resource('announcements', AnnouncementController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
            Route::get('users/me', [UserController::class, 'getCurrentUser']);
            Route::resource('users', UserController::class)->except(['create', 'edit']);

            // Route::resource('emails', NotificationLogController::class)->only(['show']);
            Route::resource('files', FileController::class)->only(['index', 'show', 'store', 'destroy']);

            Route::resource('permissions', App\Http\Controllers\PermissionController::class)->except(['create', 'edit']);
            Route::resource('roles', RoleController::class)->except(['create', 'edit']);
            Route::get('phpinfo', [App\Http\Controllers\SystemController::class, 'phpinfo']);

            Route::get('translations/namespaces', [TranslationController::class, 'getNamespaces']);
            Route::resource('translations', TranslationController::class)->except(['create', 'edit']);

            Route::resource('students', StudentController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
        });
    });

    Route::middleware('auth_optional:api')->group(function () {
        Route::get('tokens/impersonate/{user}', [TokenController::class, 'impersonate']);
    });
});

// @hideFromAPIDocumentation
Route::any('/{any}', function (Request $request) {
    return redirect('/api/documentation');
});
