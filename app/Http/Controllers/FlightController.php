<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Jobs\ProcessFlight;

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
            'flights' => DB::table('flights')->paginate(10)
        ]);   
    }
    
    /**
     * create
     *
     * @param  Request  $request
     * @return object
     */
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'destination' => 'required|max:50',
                'price' => 'required|numeric',
                'date' => 'required|date_format:Y-m-d',
            ]);
            if ($validated) {
                $flight = new Flight;
                $flight->destination = $validated['destination'];
                $flight->price = $validated['price'];
                $flight->date = $validated['date'];
                $flight->save();
                ProcessFlight::dispatch($flight);
                return redirect()->back()->withSuccess(Lang::get('Flight is created!'));
            }
        }
        return view('flight.create');
    }

    /**
     * update
     *
     * @param int $id
     * @return object
     */
    public function update(int $id)
    {
        var_dump($id);
    }
}
