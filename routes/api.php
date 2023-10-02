<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

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


Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('signin', 'login')->name('login');
});

Route::prefix('documents')->middleware('auth:api')->controller(DocumentController::class)->group(function () {
    Route::post('/', 'addDocument')->name('addDocument');
    Route::get('/', 'listDocuments')->name('listDocuments');
    Route::get('/{id}', 'fetchDocument')->name('fetchDocument');
});
