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

	// //change password
 //    Route::post('change-password', [AuthController::class, 'changePassword']);

	//user register materials
	Route::get('/specialities', [BaseController::class, 'specialities']);
	Route::get('/services', [BaseController::class, 'services']);
	Route::get('/working-days', [BaseController::class, 'workingDays']);
	Route::get('working-time-ranges', [BaseController::class, 'workingTimeRanges']);

	Route::get('/payment-methods', [BaseController::class, 'paymentMethods']);



	//barber auth
	Route::prefix('barber')->group(function () {
	    Route::post('signup', [BaseController::class, 'barberSignup']);
	    Route::post('signin', [BaseController::class, 'barberSignin']);
	});

	Route::post('user-signup', [BaseController::class, 'userSignup']);
	Route::post('user-signin', [BaseController::class, 'userSignin']);
	Route::middleware('auth:sanctum')->group( function () {
		Route::post('change-password', [BaseController::class, 'changePassword']);
		//booking
		Route::post('save-booking', [BaseController::class, 'saveBooking']);
		Route::post('user-reject-booking', [BaseController::class, 'userRejectBooking']);
		Route::post('user-cancel-booking', [BaseController::class, 'userCancelBooking']);
		Route::post('user-reschedule-booking', [BaseController::class, 'userRescheduleBooking']);

		Route::prefix('barber')->middleware('checkRole:service_provider')->group(function () {
		    Route::post('signout', [BaseController::class, 'barberSignout']);
		    Route::get('profile', [BaseController::class, 'barberProfile']);
		    Route::post('profile-update', [BaseController::class, 'barberProfileUpdate']);
		    Route::post('change-activation-status', [BaseController::class, 'changeActivationStatus']);
		    Route::post('booking-accept', [BaseController::class, 'barberBookingAccept']);
		    Route::post('booking-reject', [BaseController::class, 'barberBookingReject']);
		    Route::post('booking-lists', [BaseController::class, 'bookingLists']);
		    Route::post('booking-cancel', [BaseController::class, 'barberBookingCancel']);
		    Route::post('booking-status-change', [BaseController::class, 'bookingStatusChange']);
		    Route::post('save-withdraw-request', [BaseController::class, 'saveWithdrawRequest']);
		    Route::post('earning-withdraw-summary', [BaseController::class, 'earingWithdrawSummary']);
		    Route::post('earning-histories', [BaseController::class, 'earningHistories']);
		    Route::post('withdraw-histories', [BaseController::class, 'withdrawHistories']);
		    Route::post('upcoming-appointments', [BaseController::class, 'upcomingAppointments']);
		    Route::post('rating-lists', [BaseController::class, 'barberRatingLists']);
		});

		Route::post('user-signout', [BaseController::class, 'userSignout']);
		Route::get('/user-details', [BaseController::class, 'userDetails']);
		Route::post('user-profile-update', [BaseController::class, 'userProfileUpdate']);
		Route::post('barber-fav', [BaseController::class, 'barberFav']);
		Route::get('/my-fav-lists', [BaseController::class, 'myFavLists']);
		Route::post('/save-barber-rating', [BaseController::class, 'saveBarberRating']);
		Route::post('/home-barber-lists', [BaseController::class, 'homeBarberLists']);
	    Route::get('/barber-details/{id}', [BaseController::class, 'barberDetails']);

	}); 
});