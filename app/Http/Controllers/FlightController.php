<?php

namespace App\Http\Controllers;

use App\Models\Flight;

class FlightController extends Controller
{
    /**
     * list 
     *
     * @return object
     */
    public function list()
    {
        return view('flight.list', [
            'flights' => Flight::paginate(10)
        ]);
    }
}
