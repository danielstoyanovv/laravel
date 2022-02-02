<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Jobs\ProcessFlight;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
                'destination_image' => 'mimes:jpg,bmp,png|max:10240'
            ]);
            try {
                $this->processData($validated, $request);
            } catch (\Exception $e) {
                Log::error($exception->getMessage());
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

    /**
     * process data
     *
     * @param array $validated
     * @param Request $request
     * @return object
     */
    private function processData(array $validated, Request $request)
    {
        if ($validated) {
            $flight = new Flight;
            $flight->destination = $validated['destination'];
            $flight->price = $validated['price'];
            $flight->date = $validated['date'];
            if (!empty($request->file('destination_image'))) {
                $pathImage = Storage::putFile('public/destination', $request->file('destination_image'));
                if (!empty($pathImage)) {
                    $flight->destination_image = $pathImage;
                }
            }

            if (!empty($request->file('destination_data'))) {
                $pathImageData = Storage::putFile('public/destination', $request->file('destination_data'));
                if (!empty($pathImageData)) {
                    $flight->destination_data = $pathImageData;
                }
            }

            $flight->save();
            ProcessFlight::dispatch($flight);
            return redirect()->back()->withSuccess(Lang::get('Flight is created!'));
        }
    }
}
