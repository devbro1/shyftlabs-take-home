<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\ActionManagerController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\WorkflowNodeController;
use App\Http\Controllers\NotificationLogController;
use App\Http\Controllers\PostalCodeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TemplateController;
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
    Route::resource('countries', CountryController::class);

    Route::get('cached-translations/{lang}', [TranslationController::class, 'getCachedTranslation']);
    Route::get('cached-translations/{lang}/{namespace}', [TranslationController::class, 'getCachedTranslation']);

    Route::middleware('verified')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::resource('announcements', AnnouncementController::class);
            Route::get('users/default_user', [UserController::class, 'getDefaultUser']);
            Route::get('users/me', [UserController::class, 'getCurrentUser']);
            Route::resource('users', UserController::class);

            Route::get('tokens/ping', [TokenController::class, 'ping']);

            Route::resource('emails', NotificationLogController::class)->only(['show']);
            Route::resource('files', FileController::class)->only(['index', 'show', 'store', 'destroy']);
            Route::resource('leads', LeadController::class);
            Route::get('leads/{lead}/history', [LeadController::class, 'getHistory']);
            Route::post('leads/{lead}/notes', [LeadController::class, 'addNote']);
            Route::resource('workflows', WorkflowController::class);
            Route::resource('workflow-nodes', WorkflowNodeController::class);
            // Route::resource('workflow-nodes', WorkflowNodeController::class);
            Route::resource('permissions', App\Http\Controllers\PermissionController::class);
            Route::get('roles/default', [RoleController::class, 'default']);
            Route::resource('roles', RoleController::class);
            Route::resource('services', ServiceController::class);
            Route::resource('stores', App\Http\Controllers\StoreController::class);
            Route::resource('appointments', App\Http\Controllers\AppointmentController::class);
            Route::get('users/{user}/appointments', [App\Http\Controllers\AppointmentController::class, 'getUserAppointments']);
            Route::resource('templates', TemplateController::class);
            Route::resource('companies', App\Http\Controllers\CompanyController::class);

            Route::resource('leads/{lead}/actions', App\Http\Controllers\ActionController::class)->parameters([
                'actions' => 'action_workflow_node',
            ]);

            Route::resource('actions', ActionManagerController::class); // to manage Action: activate, manage global config

            // Route::post('leads/{lead}/actions/:id', [ActionController::class, 'store']);
            // Route::put('leads/{lead}/actions', [ActionController::class,'update'])->parameters([
            //     'actions' => 'action_workflow_node'
            // ]);
            // Route::resource('leads/{lead}/actions', ActionController::class);
            Route::resource('service-availabilities', App\Http\Controllers\ServiceAvailabilityController::class);

            Route::get('phpinfo', [App\Http\Controllers\SystemController::class, 'phpinfo']);

            Route::get('translations/namespaces', [TranslationController::class, 'getNamespaces']);
            Route::resource('translations', TranslationController::class);
        });
    });

    Route::middleware('auth_optional:api')->group(function () {
        Route::resource('postal-codes', PostalCodeController::class);
        Route::post('leads', [LeadController::class, 'store']);
        Route::get('services', [ServiceController::class, 'index']);
        Route::get('tokens/impersonate/{user}', [TokenController::class, 'impersonate']);
    });
});

Route::any('/{any}', function (Request $request) {
    return redirect('/api/documentation');
});
