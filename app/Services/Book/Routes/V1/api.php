<?php

use App\Services\Book\Http\Controllers\V1\BookController;
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
| All bellow routes are prefixed with api/v1/Book in
| App/Services/Book/Book/RouteServiceProvider
|
*/

// Controllers should live in App/Services/Book/Http/Controllers

Route::apiResource('books', BookController::class);
