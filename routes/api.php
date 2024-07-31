<?php

use App\Http\Controllers\Api\RoleController as ApiRoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;



Route::post('register', [AuthController::class, 'register'])->middleware(['check.age','check.email.domain']);
Route::post('login', [AuthController::class, 'login']);

// Group routes that require authentication with the CheckToken middleware
Route::middleware(['check.token'])->group(function () {
    // Route::middleware(['custom.auth'])->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'userDetails']);
    Route::post('roles', [RoleController::class, 'createRole']);
    Route::post('assign-roles', [RoleController::class, 'assignRoles']);
    Route::get('user-roles', [RoleController::class, 'userRoles']);
});

