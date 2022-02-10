<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\FlightCrewController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\Auth\FlightController as AuthFlightController;
use App\Http\Controllers\Auth\FlightCrewController as AuthFlightCrewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test/', [TestController::class, 'show']);

Route::get('/flight/list', [FlightController::class, 'list']);

Route::get('/flightcrew/list', [FlightCrewController::class, 'list']);

Route::get('/passenger/create', [PassengerController::class, 'create']);

Route::post('/passenger/create', [PassengerController::class, 'create']);

Route::get('/passenger/update/id/{id}', [PassengerController::class, 'update']);

Route::post('/passenger/update/id/{id}', [PassengerController::class, 'update']);

Route::get('/passenger/list', [PassengerController::class, 'list']);

Auth::routes();

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');

Route::get('/auth/flight/create', [AuthFlightController::class, 'create']);

Route::post('/auth/flight/create', [AuthFlightController::class, 'create']);

Route::get('/auth/flight/update/id/{id}', [AuthFlightController::class, 'update']);

Route::post('/auth/flight/update/id/{id}', [AuthFlightController::class, 'update']);

Route::get('/auth/flight/delete/id/{id}', [AuthFlightController::class, 'delete']);

Route::get('/auth/flightcrew/create', [AuthFlightCrewController::class, 'create']);

Route::post('/auth/flightcrew/create', [AuthFlightCrewController::class, 'create']);

Route::get('/auth/flightcrew/update/id/{id}', [AuthFlightCrewController::class, 'update']);

Route::post('/auth/flightcrew/update/id/{id}', [AuthFlightCrewController::class, 'update']);

Route::get('/auth/flightcrew/delete/id/{id}', [AuthFlightCrewController::class, 'delete']);