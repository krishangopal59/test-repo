<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController as AuthController;

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
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/contacts',[AuthController::class,'contacts']);
    Route::post('/add_contacts',[AuthController::class,'add_contacts']);
    Route::post('/update_contacts',[AuthController::class,'update_contacts']);
    Route::post('/get_contact',[AuthController::class,'get_contact']);
    Route::post('/delete',[AuthController::class,'delete']); 
});