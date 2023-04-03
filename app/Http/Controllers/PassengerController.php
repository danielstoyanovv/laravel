<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class PassengerController extends Controller
{
    /**
     * list
     *
     * @return object
     */
    public function list(Request $request)
    {
        $response = view('flight.passenger.list', [
            'passengers' => Passenger::paginate(10)
        ]);

        $currentPageCacheKey = "flight_passenger_list_" . $request->query->getInt('page', 1);
        Cache::add($currentPageCacheKey, $response->render(), 86400);

        return Cache::get($currentPageCacheKey);
    }
}
