<?php

namespace App\Http\Controllers;

use App\Models\FlightsCrew;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FlightCrewController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request)
    {
        $response = view('flight.crew.list', [
            'flightsCrew' => FlightsCrew::paginate(10),
            'flights' => Flight::select('id', 'destination')->get()
        ]);

        $currentPageCacheKey = "flight_crew_list_" . $request->query->getInt('page', 1);
        Cache::add($currentPageCacheKey, $response->render(), 86400);

        return Cache::get($currentPageCacheKey);
    }
}
