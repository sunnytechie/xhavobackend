<?php

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
// Compare this snippet from routes/api.php:
//login routes
Route::post('/auth/login', [App\Http\Controllers\Api\Auth\LoginController::class, 'login']);
//Register routes
Route::post('/auth/register-customer', [App\Http\Controllers\Api\Auth\RegisterController::class, 'customerRegister']);
Route::post('/auth/customer-info/{user_id}', [App\Http\Controllers\Api\Auth\RegisterController::class, 'customerInfo']);

// Otp routes
Route::post('/auth/send-otp', [App\Http\Controllers\Api\Auth\OtpController::class, 'sendOtp']);
Route::post('/auth/verify-otp', [App\Http\Controllers\Api\Auth\OtpController::class, 'verifyOtp']);

// Forgot password routes
Route::post('/auth/forgot-password', [App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'resetPassword']);

//Category routes
Route::get('/categories', [App\Http\Controllers\Api\Category\CategoryController::class, 'index']);

//middleware bearer token
Route::middleware('bearer')->group(function () {
//Merchants
Route::get('/merchants', [App\Http\Controllers\Api\User\MerchantController::class, 'index']);

//Reviews
Route::get('/reviews/{user_id}', [App\Http\Controllers\Api\Review\ReviewController::class, 'index']);
Route::post('/reviews', [App\Http\Controllers\Api\Review\ReviewController::class, 'store']);
Route::put('/reviews/{review_id}', [App\Http\Controllers\Api\Review\ReviewController::class, 'update']);
Route::delete('/reviews/{review_id}', [App\Http\Controllers\Api\Review\ReviewController::class, 'destroy']);

//bookings
Route::post('/booking/new', [App\Http\Controllers\Api\Booking\BookingController::class, 'store']);
Route::put('/booking/accept', [App\Http\Controllers\Api\Booking\BookingController::class, 'accept']);
Route::put('/booking/reject', [App\Http\Controllers\Api\Booking\BookingController::class, 'reject']);
Route::put('/booking/complete', [App\Http\Controllers\Api\Booking\BookingController::class, 'complete']);
Route::get('/bookings/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'index']);
Route::get('/bookings/accepted/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'accepted']);
Route::get('/bookings/rejected/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'rejected']);
Route::get('/bookings/completed/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'completed']);

//notifications
Route::get('/notifications/{user_id}', [App\Http\Controllers\Api\Notification\NotificationController::class, 'userNotifications']);

//report
Route::post('/report', [App\Http\Controllers\Api\Report\ReportController::class, 'store']);

//search
Route::get('/search', [App\Http\Controllers\Api\Search\SearchController::class, 'search']);
Route::get('/filter', [App\Http\Controllers\Api\Search\SearchController::class, 'filter']);

//account update
Route::post('/account/{user_id}', [App\Http\Controllers\Api\User\AccountController::class, 'update']);

//change password
Route::post('/change-password/{user_id}', [App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'changePassword']);

//update identity
Route::post('/identity/{user_id}', [App\Http\Controllers\Api\User\AccountController::class, 'updateIdentity']);

//save interest
Route::post('/interest/{user_id}', [App\Http\Controllers\Api\Interest\InterestController::class, 'store']);

//delete user
Route::delete('/destroy/user/{user_id}', [App\Http\Controllers\Api\User\AccountController::class, 'destroy']);

//support
Route::post('/support/{user_id}', [App\Http\Controllers\Api\User\AccountController::class, 'support']);

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
