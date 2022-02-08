<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\FlightCrewController;
use App\Http\Controllers\PassengerController;

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

Route::get('/flight/create', [FlightController::class, 'create']);

Route::post('/flight/create', [FlightController::class, 'create']);

Route::get('/flight/update/id/{id}', [FlightController::class, 'update']);

Route::post('/flight/update/id/{id}', [FlightController::class, 'update']);

Route::get('/flight/delete/id/{id}', [FlightController::class, 'delete']);

Route::get('/flightcrew/list', [FlightCrewController::class, 'list']);

Route::get('/flightcrew/create', [FlightCrewController::class, 'create']);

Route::post('/flightcrew/create', [FlightCrewController::class, 'create']);

Route::get('/flightcrew/update/id/{id}', [FlightCrewController::class, 'update']);

Route::post('/flightcrew/update/id/{id}', [FlightCrewController::class, 'update']);

Route::get('/flightcrew/delete/id/{id}', [FlightCrewController::class, 'delete']);

Route::get('/passenger/create', [PassengerController::class, 'create']);

Route::post('/passenger/create', [PassengerController::class, 'create']);

Route::get('/passenger/update/id/{id}', [PassengerController::class, 'update']);

Route::post('/passenger/update/id/{id}', [PassengerController::class, 'update']);

Route::get('/passenger/list', [PassengerController::class, 'list']);




