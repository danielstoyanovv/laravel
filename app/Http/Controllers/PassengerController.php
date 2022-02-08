<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Passenger;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;


class PassengerController extends Controller
{
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
                $model = $this->processData($validated, $request, new Passenger, Lang::get('Passenger is created!'));               
               if ($model) {
                    return redirect()->action([self::class, 'update'], ['id' => $model->id]);
                }
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }
        return view('flight.passenger.create', [
            'flights' => DB::table('flights')->select('id', 'destination')->get()
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
            'name' => 'required|max:50',
            'flight_id' => 'required'
            
        ],
         [
            'flight_id.required' => 'Please select the flight!' 
        ]);
    }

    /**
     * process data
     *
     * @param array $validated
     * @param Request $request
     * @param Passenger $model
     * @param string $message
     * @return object|bool
     */
    private function processData(array $validated, Request $request, Passenger $model, string $message)
    {
        if ($validated && $request && $model && $message) {
            foreach ($validated as $k => $v) {
                $model->$k = $v;
            }
            $model->save();
            session()->flash('message', $message);
            return $model;
        }
        return false;
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
        $model = Passenger::find($id);
        if (!$model) {
           session()->flash('message', Lang::get('This passenger did not exists!'));
           return redirect()->action([self::class, 'create']);
        }

        if ($request->isMethod('post') && $request) {
            $validated = $this->processValidate($request);
            try {
                $this->processData($validated, $request, $model, Lang::get('Passenger is updated!'));
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }
        
        return view('flight.passenger.update', [
            'passenger' => $model,
            'flights' => DB::table('flights')->select('id', 'destination')->get()
        ]);
    }

    /**
     * list 
     *
     * @return object
     */
    public function list()
    {
        return view('flight.passenger.list', [
            'passengers' => Passenger::paginate(10)
        ]);
    }
}
