<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\Flight;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Jobs\ProcessFlight;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        // $this->middleware('permission:flight-list|flight-create|flight-edit|flight-delete', ['only' => ['index','show']]);
        $this->middleware('permission:flight-create', ['only' => ['create','store']]);
        $this->middleware('permission:flight-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:flight-delete', ['only' => ['destroy']]);
    }
    /**
     * create
     *
     * @param Request $request
     * @return object
     */
    public function create(Request $request)
    {
        return view('auth.flight.create');
    }


    /**
     * store
     *
     * @param Request $request
     * @return object
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post') && $request) {
            $validated = $this->processValidate($request);
            try {
                $flight = $this->processData($validated, $request, new Flight(), Lang::get('Flight is created!'));
                if ($flight) {
                    return redirect()->route('flights.show', $flight->id);
                }
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }
        return view('auth.flight.create');
    }
    /**
     * show
     *
     * @param int $id
     * @param Request $request
     * @return object
     */
    public function show(int $id, Request $request)
    {
        $flight =  Flight::where('id', $id)->first();
        if (!$flight) {
            session()->flash('message', Lang::get('This flight did not exists!'));
            return redirect()->route('flights.create');
        }

        return view('auth.flight.show', [
            'flight' => $flight
        ]);
    }

    /**
     * update
     *
     * @param int $id
     * @param Request $request
     * @return object
     */
    public function edit(int $id, Request $request)
    {
        $flight =  Flight::where('id', $id)->first();
        if (!$flight) {
            session()->flash('message', Lang::get('This flight did not exists!'));
            return redirect()->action([self::class, 'create']);
        }

        return view('auth.flight.edit', [
            'flight' => $flight
        ]);
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
            return redirect()->action([self::class, 'create']);
        }

        if ($request->isMethod('patch') && $request) {
            $validated = $this->processValidate($request);
            try {
                $this->processData($validated, $request, $flight, Lang::get('Flight is updated!'));
                return redirect()->route('flights.show', $flight->id);
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }

        return view('auth.flight.edit', [
            'flight' => $flight
        ]);
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
    public function destroy(int $id)
    {
        if (!$id || !Flight::find($id)) {
            session()->flash('message', Lang::get('This flight did not exists!'));
            return redirect()->action([self::class, 'create']);
        }
        $flight = Flight::find($id);
        $files = [$flight->destination_image, $flight->destination_data];
        if (!empty($files)) {
            $this->removeFilesFromFileSystem($files);
        }
        $flight->delete();
        session()->flash('message', Lang::get('This flight was removed!'));
        return redirect()->action([self::class, 'index']);
    }

    /**
     * list
     *
     * @return object
     */
    public function index()
    {
        return view('auth.flight.index', [
            'flights' => Flight::paginate(10)
        ]);
    }
}
