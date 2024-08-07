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

//Register merchant
Route::post('/auth/register-merchant', [App\Http\Controllers\Api\Auth\RegisterController::class, 'merchantRegister']);

//Category routes
Route::get('/categories', [App\Http\Controllers\Api\Category\CategoryController::class, 'index']);

//middleware bearer token
Route::middleware('bearer')->group(function () {
// Otp routes
Route::post('/auth/send-otp', [App\Http\Controllers\Api\Auth\OtpController::class, 'sendOtp']);
Route::post('/auth/verify-otp', [App\Http\Controllers\Api\Auth\OtpController::class, 'verifyOtp']);

// Forgot password routes
Route::post('/auth/forgot-password', [App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'forgotPassword']);
Route::post('/auth/otp/verification', [App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'otpCheck']);
Route::post('/auth/reset-password', [App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'resetPassword']);

//Merchants
Route::get('/merchants', [App\Http\Controllers\Api\User\MerchantController::class, 'index']);

//Merchant by category
Route::get('/merchants/category/{category_id}', [App\Http\Controllers\Api\Category\CategoryController::class, 'merchantByCategory']);

//report
Route::post('/report', [App\Http\Controllers\Api\Report\ReportController::class, 'store']);

//search
Route::post('/search', [App\Http\Controllers\Api\ScoutSearch\SearchController::class, 'searchText']);
Route::post('/filter', [App\Http\Controllers\Api\ScoutSearch\SearchController::class, 'filter']);

});


###########################
Route::middleware('token')->group(function () {
//customer register
Route::post('/auth/customer-info/{user_id}', [App\Http\Controllers\Api\Auth\RegisterController::class, 'customerInfo']);

//merchant registeration
Route::post('/register/merchant/{user_id}', [App\Http\Controllers\Api\Auth\MerchantRegisterationController::class, 'register']);

//notifications
Route::get('/notifications/{user_id}', [App\Http\Controllers\Api\Notification\NotificationController::class, 'userNotifications']);

Route::get('/account/user/{user_id}', [App\Http\Controllers\Api\User\UserController::class, 'userInfo']);

Route::get('/reviews/{user_id}', [App\Http\Controllers\Api\Review\ReviewController::class, 'index']);

//Reviews
Route::post('/review', [App\Http\Controllers\Api\Review\ReviewController::class, 'store']);
Route::put('/review/{review_id}', [App\Http\Controllers\Api\Review\ReviewController::class, 'update']);
Route::delete('/review/{review_id}', [App\Http\Controllers\Api\Review\ReviewController::class, 'destroy']);

//bookings
Route::post('/booking/new/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'store']);
Route::put('/booking/accept/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'accept']);
Route::put('/booking/reject/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'reject']);
Route::put('/booking/complete/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'complete']);

Route::get('/bookings/customer/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'customer']);
Route::get('/bookings/merchant/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'merchant']);
Route::get('/bookings/chart/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'chart']);

//customer
Route::get('/bookings/accepted/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'accepted']);
Route::get('/bookings/rejected/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'rejected']);
Route::get('/bookings/completed/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'completed']);

//merchant
Route::get('/merchant/accepted/bookings/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'acceptedMerchantBookings']);
Route::get('/merchant/rejected/bookings/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'rejectedMerchantBookings']);
Route::get('/merchant/completed/bookings/{user_id}', [App\Http\Controllers\Api\Booking\BookingController::class, 'completedMerchantBookings']);

//account update
Route::post('/account/{user_id}', [App\Http\Controllers\Api\User\AccountController::class, 'update']);

//merchant account update
Route::post('/merchant-account/{user_id}', [App\Http\Controllers\Api\User\AccountController::class, 'updateMerchantUser']);

//change password
Route::post('/change-password/{user_id}', [App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'changePassword']);

//update identity
Route::post('/identity/{user_id}', [App\Http\Controllers\Api\User\AccountController::class, 'updateIdentity']);

//save interest
Route::post('/interest/{user_id}', [App\Http\Controllers\Api\Interest\InterestController::class, 'store']);
Route::get('/interest/user/{user_id}', [App\Http\Controllers\Api\Interest\InterestController::class, 'index']);

//save merchants
Route::post('/save/merchant/{user_id}/{merchant_id}', [App\Http\Controllers\Api\SavedmerchantController::class, 'store']);
//list merchant saved
Route::get('/saved/merchant/{user_id}', [App\Http\Controllers\Api\SavedmerchantController::class, 'index']);

//delete user
Route::delete('/destroy/user/{user_id}', [App\Http\Controllers\Api\User\AccountController::class, 'destroy']);

//support
Route::post('/support/{user_id}', [App\Http\Controllers\Api\User\AccountController::class, 'support']);

//store thumbnail
Route::post('/thumbnail/{user_id}', [App\Http\Controllers\Api\User\ThumbnailController::class, 'store']);
//update thumbnail
Route::post('/thumbnail/{user_id}/{thumbnail_id}', [App\Http\Controllers\Api\User\ThumbnailController::class, 'update']);

//store work schedule time
Route::post('/schedule/{user_id}', [App\Http\Controllers\Api\User\ScheduleController::class, 'store']);
//update work schedule time
Route::post('/schedule/{user_id}/{schedule_id}', [App\Http\Controllers\Api\User\ScheduleController::class, 'update']);

//update merchant info
Route::post('/merchant-info/{user_id}', [App\Http\Controllers\Api\User\AccountController::class, 'updateMerchant']);

Route::get('/merchant/dashboard/{user_id}', [App\Http\Controllers\Api\Dashboard\MerchantController::class, 'index']);

Route::post('/auth/logout/user/{user_id}', [App\Http\Controllers\Api\Auth\LogOutController::class, 'logout']);


//referral page
Route::get('/referral/{user_id}', [App\Http\Controllers\Api\Pages\AffiliateController::class, 'index']);

//payment
Route::post('booking/payment', [App\Http\Controllers\Api\Payment\BookingController::class, 'createPayment']);
Route::post('booking/payment/verify', [App\Http\Controllers\Api\Payment\BookingController::class, 'verifyPayment']);


//wallet
Route::get('/stash/{user_id}', [App\Http\Controllers\Api\Stash\StashController::class, 'index']);
Route::post('/stash/fund/{user_id}', [App\Http\Controllers\Api\Stash\StashController::class, 'store']);

//withdrawal
Route::prefix('withdrawal')->group(function () {
    Route::post('/{user_id}', [App\Http\Controllers\Api\Stash\WithdrawalController::class, 'store']);
    Route::post('/verify/{user_id}', [App\Http\Controllers\Api\Stash\WithdrawalController::class, 'verifyWithdrawal']);
});

//bank
Route::prefix('bank')->group(function () {
    Route::get('/list', [App\Http\Controllers\Api\Stash\BankController::class, 'banks']);
    Route::get('/{user_id}', [App\Http\Controllers\Api\Stash\BankController::class, 'index']);
    Route::post('/{user_id}', [App\Http\Controllers\Api\Stash\BankController::class, 'store']);
    Route::delete('/{user_id}/{bank_id}', [App\Http\Controllers\Api\Stash\BankController::class, 'delete']);
});

});
##########################

//flutterwave payment webhook
Route::post('/stash/account/funding', [App\Http\Controllers\Api\Stash\StashController::class, 'topUp']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
