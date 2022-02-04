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
        return view('flight.list', ['flights' => DB::table('flights')->paginate(10)]);   
    }
    
    /**
     * create
     *
     * @param Request $request
     * @return object
     */
    public function create(Request $request)
    {
        if ($request->isMethod('post') && $request) {
            $validated = $this->processValidate($request);
            try {
                $flight = $this->processData($validated, $request, new Flight, Lang::get('Flight is created!'));
                if ($flight) {
                    return redirect()->action([self::class, 'update'], ['id' => $flight->id]);
                }
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }
        return view('flight.create');
    }

    /**
     * update
     *
     * @param int $id
     * @param Request $request
     * @return object
     */
    public function update(int $id, Request $request)
    {
        $flight =  Flight::where('id', $id)->first();
        if (!$flight) {
           session()->flash('message', Lang::get('This flight did not exists!'));
           return redirect()->action([self::class, 'list']);
        }

        if ($request->isMethod('post') && $request) {
            $validated = $this->processValidate($request);
            try {
                $this->processData($validated, $request, $flight, Lang::get('Flight is updated!'));
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }
        
        return view('flight.update', ['flight' => $flight]);
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
     * @param string $message
     * @return object
     */
    private function processData(array $validated, Request $request, Flight $flight, string $message)
    {
        if ($validated && $request && $flight && $message) {
            $removeOldFiles = [];
            $flight->destination = $validated['destination'];
            $flight->price = $validated['price'];
            $flight->date = $validated['date'];
            if (!empty($request->file('destination_image')) && !empty(Storage::putFile('public/destination', $request->file('destination_image')))) {   
                $pathImage = Storage::putFile('public/destination', $request->file('destination_image'));
                if (!empty($flight->destination_image) && (sha1_file(public_path(Storage::url($flight->destination_image))) != sha1_file(public_path(Storage::url($pathImage))))) {
                    $removeOldFiles[] = $flight->destination_image;
                }
                $flight->destination_image = Storage::putFile('public/destination', $request->file('destination_image'));
            }

            if (!empty($request->file('destination_data')) && !empty(Storage::putFile('public/destination', $request->file('destination_data')))) {
                $pathImageData = Storage::putFile('public/destination', $request->file('destination_data'));
                if (!empty($flight->destination_data) && (sha1_file(public_path(Storage::url($flight->destination_data))) != sha1_file(public_path(Storage::url($pathImageData))))) {
                    $removeOldFiles[] = $flight->destination_data;
                }
                $flight->destination_data = Storage::putFile('public/destination', $request->file('destination_data'));
            }

            $flight->save();

            $this->removeFilesFromFileSystem($removeOldFiles);
            ProcessFlight::dispatch($flight);
            session()->flash('message', $message);
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

    /**
     * delete
     * @param int $id
     * @return object
     */
    public function delete(int $id)
    {
        if (!$id || !Flight::find($id)) {
            session()->flash('message', Lang::get('This flight did not exists!'));
            return redirect()->action([self::class, 'list']);
        }
        $flight = Flight::find($id);
        $files = [$flight->destination_image, $flight->destination_data];
        if (!empty($files)) {
            $this->removeFilesFromFileSystem($files);
        }
        $flight->delete();
        session()->flash('message', Lang::get('This flight was removed!'));
        return redirect()->action([self::class, 'list']);
    }
}
