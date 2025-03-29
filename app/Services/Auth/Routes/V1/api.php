<?php

use App\Services\Auth\Http\Controllers\V1\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Service - API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for this service.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
| All bellow routes are prefixed with api/v1/Auth in
| App/Services/Auth/RouteServiceProvider
|
*/

// Controllers should live in App/Services/Auth/Http/Controllers

Route::post('login', [AuthController::class,"login"])->name('login');

Route::middleware('auth:sanctum')->post('logout', [AuthController::class,"logout"])->name('logout');
