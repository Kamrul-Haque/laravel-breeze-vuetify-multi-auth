<?php

use App\Http\Controllers as Controllers;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

require __DIR__ . '/auth.php';

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware('auth', 'permit:user')->name('dashboard');


Route::get('/admin', function () {
    return Inertia::render('AdminDashboard');
})->middleware('auth', 'permit:admin')->name('admin.dashboard');

Route::get('/super-admin', function () {
    return Inertia::render('SuperAdminDashboard');
})->middleware('auth', 'permit:super-admin')->name('super-admin.dashboard');


Route::group(['middleware' => ['auth', 'permit:admin,super-admin']], function () {
    Route::resource('/users', Controllers\UserController::class);
    Route::post('/users/{user}/restore', [Controllers\UserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{user}/delete', [Controllers\UserController::class, 'forceDelete'])->name('users.force.delete');
//    Route::resource('/roles', Controllers\RoleController::class)->except('show');
//    Route::get('/roles/{role}/assign-permissions', [Controllers\RoleController::class, 'assignPermissionsForm'])->name('roles.assign.permissions.form');
//    Route::post('/roles/{role}/assign-permissions', [Controllers\RoleController::class, 'assignPermissions'])->name('roles.assign.permissions');
//    Route::resource('/permissions', Controllers\PermissionController::class)->except('show');
//
//    Route::get('/users/{user}/assign-roles', [Controllers\UserController::class, 'assignRolesForm'])->name('users.assign.roles.form');
//    Route::post('/users/{user}/assign-roles', [Controllers\UserController::class, 'assignRoles'])->name('users.assign.roles');
});
