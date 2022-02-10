<?php

namespace App\Http\Controllers;

use App\Models\Passenger;

class PassengerController extends Controller
{
    /**
     * list 
     *
     * @return object
     */
    public function list()
    {
        return view('flight.passenger.list', [
            'passengers' => Passenger::paginate(10)
        ]);
    }
}
