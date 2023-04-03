<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use Illuminate\Support\Facades\Cache;

class PassengerController extends Controller
{
    /**
     * list
     *
     * @return object
     */
    public function list()
    {
        $response = view('flight.passenger.list', [
            'passengers' => Passenger::paginate(10)
        ]);

        Cache::add('flight_passenger_list', $response->render(), 86400);

        return Cache::get('flight_passenger_list');
    }
}
