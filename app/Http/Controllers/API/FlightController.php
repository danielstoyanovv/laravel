<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flight;
use App\Jobs\ProcessFlight;
use Illuminate\Support\Facades\Log;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Flight::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post') && $request) {
            $error = false;
            $validated = $this->processValidate($request);
            //Log::error($validated);
            //Log::error($request->all());
            try {
                $flight = $this->processData($validated, $request, new Flight);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }    
            if (!empty($flight)) {
                return response()->json($flight);
            }
            return response()->json(['error' => $error]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $flight = Flight::find($id);
        $error = false;
        if (!$flight) {
           return response()->json(['error' => 'This flight did not exists!']);
        }
        return response()->json($flight);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $flight =  Flight::find($id);
        if (!$flight) {
            return response()->json(['error' => 'This flight did not exists!']);
        }
        $error = false;
        if ($request->isMethod('patch') && $request) {
            $validated = $this->processValidate($request);
            try {
                $this->processData($validated, $request, $flight);
                return response()->json($flight);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }
        return response()->json(['error' => $error]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * process validate
     *
     * @param Request $request
     * @return array
     */
    private function processValidate(Request $request): array
    {
        return $request->validate([
            'destination' => 'required|max:50',
            'price' => 'required|numeric',
            'date' => 'required|date_format:Y-m-d H:i:s',
            'destination_image' => 'mimes:jpg,bmp,png|max:10240'
        ]);
    }

    /**
     * process data
     *
     * @param array $validated
     * @param Request $request
     * @param Flight $flight
     * @return object
     */
    private function processData(array $validated, Request $request, Flight $flight)
    {
        if ($validated && $request && $flight) {
            $removeOldFiles = [];
            $flight->destination = $validated['destination'];
            $flight->price = $validated['price'];
            $flight->date = $validated['date'];

            if (!empty($request['files']['destination_image']['name'])) {
                $flight->destination_image = 'public/destination/' . $request['files']['destination_image']['name'];
                if (!empty($request['files']['destination_image']['content'])) {
                    $fullFilePathName = storage_path('app/public/destination/') . $request['files']['destination_image']['name'];
                    file_put_contents($fullFilePathName, base64_decode($request['files']['destination_image']['content']));
                }
            }

            if (!empty($request['files']['destination_data']['name'])) {
                $flight->destination_data = 'public/destination/' . $request['files']['destination_data']['name'];
                if (!empty($request['files']['destination_data']['content'])) {
                    $fullFilePathName = storage_path('app/public/destination/') . $request['files']['destination_data']['name'];
                    file_put_contents($fullFilePathName, base64_decode($request['files']['destination_data']['content']));
                }
            }

            $flight->save();
            $this->removeFilesFromFileSystem($removeOldFiles);
            ProcessFlight::dispatch($flight);
            return $flight;
        }
    }

    /**
     * remove Files From File System
     * @param array $files
     * @return void
     */
    private function removeFilesFromFileSystem(array $files)
    {
        if ($files) {
            Storage::delete($files);
        }
    }
}
