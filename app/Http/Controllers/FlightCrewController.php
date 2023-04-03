<?php

namespace App\Http\Controllers;

use App\Models\FlightsCrew;
use App\Models\Flight;
use Illuminate\Support\Facades\Cache;

class FlightCrewController extends Controller
{
    /**
     * list
     *
     * @return object
     */
    public function list()
    {
        $response = view('flight.crew.list', [
            'flightsCrew' => FlightsCrew::paginate(10),
            'flights' => Flight::select('id', 'destination')->get()
        ]);

        Cache::add('flight_crew_list', $response->render(), 86400);

        return Cache::get('flight_crew_list');
    }
}
