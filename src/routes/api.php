<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\APIs\AppointmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIs\BarberController;
use App\Http\Controllers\APIs\ServiceController;
use App\Http\Controllers\APIs\CustomerController;
use App\Http\Controllers\APIs\BookingController;
use App\Http\Controllers\APIs\GalleryController;
use App\Http\Controllers\APIs\CloseDayController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\APIs\HomeViewController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/barbers', BarberController::class);
Route::resource('/services', ServiceController::class);
Route::get('/gallery',[GalleryController::class,'index']);
Route::get('booking_time',function(){
    $booking_time=DB::table('booking_time')->select('uniqueid','time_period')->get();
    return response()->json($booking_time);
});

Route::post('/add-customer',[CustomerController::class, 'store']);
Route::delete('/delete-customer/{id}',[CustomerController::class,'destroy']);
Route::put('/update-customer/{id}',[CustomerController::class,'update']);
Route::post('/detail-customer',[CustomerController::class,'detail']);
Route::get('/index',[CustomerController::class. 'index']);

Route::get('/check',[AppointmentController::class,'check']);
Route::get('/booking_summary',[AppointmentController::class,'booking_summary']);
Route::post('/check',[AppointmentController::class,'check']);
Route::post('/cancel-booking', [BookingController::class, 'cancel']);
Route::post('/check-bookings', [BookingController::class, 'checkBookings']);
Route::post('/staff-bookings', [BookingController::class, 'checkStaffBookings']);
Route::post('/time-booking', [BookingController::class, 'checkTimeBooking']);
Route::post('/booking-anybarber',[BookingController::class,'timeBookingAnybarber']);

//Route::get('/fill-amount',[BookingController::class,'fillAmount']);
Route::get('/close-days',[CloseDayController::class,'index']);

Route::post('/create-booking',[BookingController::class,'store']);
Route::post('/booking-list',[BookingController::class,'bookingList']);
Route::put('/update-booking/{id}',[BookingController::class,'update']);
Route::delete('/delete-booking/{id}',[BookingController::class,'destroy']);
Route::post('/customer/register', [CustomerController::class, 'register']);
Route::post('/customer/login', [CustomerController::class, 'login']);
Route::post('/customer/detail/{id}',[CustomerController::class,'detail']);

Route::get('/address', [AddressController::class, 'getAddress']);

Route::post('/booking-status', [App\Http\Controllers\AppointmentController::class, 'changeStatus']);

Route::get('/get-chart', [DashboardController::class, 'chartValue']);

Route::post('/auth/gmail',[CustomerController::class,'gmail']);
Route::post('/auth/facebook',[FacebookController::class,'facebookLogin']);
// Route::get('/auth/facebook/callback',[FacebookController::class,'handleFacebookCallback']);


Route::get('/home-views',[HomeViewController::class,'home']);
Route::post('/barber_commission',[AppointmentController::class,'barberCommission']);
Route::delete('/barber_commission_delete',[AppointmentController::class,'barberCommissionDelete']);
