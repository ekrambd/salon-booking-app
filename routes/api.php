<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BaseController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['throttle:60,1'])->group(function () {

	//user register materials
	Route::get('/specialities', [BaseController::class, 'specialities']);
	Route::get('/services', [BaseController::class, 'services']);
	Route::get('/working-days', [BaseController::class, 'workingDays']);
	Route::get('working-time-ranges', [BaseController::class, 'workingTimeRanges']);

	//barber auth
	Route::prefix('barber')->group(function () {
	    Route::post('signup', [BaseController::class, 'barberSignup']);
	    Route::post('signin', [BaseController::class, 'barberSignin']);
	});

	Route::post('user-register', [AuthController::class, 'userRegister']);
	Route::middleware('auth:sanctum')->group( function () {
		Route::prefix('barber')->group(function () {
		    Route::post('signout', [BaseController::class, 'barberSignout']);
		});
	}); 
});