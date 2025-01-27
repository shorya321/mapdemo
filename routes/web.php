<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\GeocodingController;
use App\Http\Controllers\BookingController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [SearchController::class, 'index']);
Route::get('/s', [SearchController::class, 'search']);
Route::get('/vehicle/{vehicle}', [SearchController::class, 'show']);

Route::get('/api/geocoding/autocomplete', [GeocodingController::class, 'autocomplete']);


// Route::get('/payment', [PaymentController::class, 'showPaymentPage'])->name('payment.show');
// Route::post('/payment/create-intent', [PaymentController::class, 'createPaymentIntent'])->name('payment.intent');
// Route::post('/webhook/stripe', [PaymentController::class, 'handleWebhook'])->name('webhook.stripe');
// Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
// Route::post('/bookings', [PaymentController::class, 'createBooking']);
// Route::post('/webhook/stripe', [PaymentController::class, 'handlePaymentWebhook']);

Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/success', [BookingController::class, 'success'])->name('booking.success');