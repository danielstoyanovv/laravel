<?php

namespace App\Http\Controllers;

use App\Models\FlightsCrew;
use App\Models\Flight;

class FlightCrewController extends Controller
{
    /**
     * list
     *
     * @return object
     */
    public function list()
    {
        return view('flight.crew.list', [
            'flightsCrew' => FlightsCrew::paginate(10),
            'flights' => Flight::select('id', 'destination')->get()
        ]);
    }
}
