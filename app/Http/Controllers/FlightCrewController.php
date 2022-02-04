<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlightsCrew;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class FlightCrewController extends Controller
{
    /**
     * list 
     *
     * @return object
     */
    public function list()
    {
        return view('flight.crew.list', ['flightsCrew' => DB::table('flights_crew')->paginate(10)]);
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
                $flightCrew = $this->processData($validated, $request, new FlightsCrew, Lang::get('Flight crew is created!'));               
               if ($flightCrew) {
                    return redirect()->action([self::class, 'update'], ['id' => $flightCrew->crew_id]);
                }
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }
        return view('flight.crew.create');
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
            'main_captain' => 'required|max:50',
            'captain' => 'required|max:50',
            'crew_member_1' => 'required|max:50',
            'crew_member_2' => 'required|max:50',
            'crew_member_3' => 'required|max:50',
        ]);
    }

    /**
     * process data
     *
     * @param array $validated
     * @param Request $request
     * @param FlightsCrew $flightCrew
     * @param string $message
     * @return object|bool
     */
    private function processData(array $validated, Request $request, FlightsCrew $flightCrew, string $message)
    {
        if ($validated && $request && $flightCrew && $message) {
            foreach ($validated as $k => $v) {
                $flightCrew->$k = $v;
            }
            $flightCrew->save();
            session()->flash('message', $message);
            return $flightCrew;
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
        $flightCrew = FlightsCrew::find($id);
        if (!$flightCrew) {
           session()->flash('message', Lang::get('This flight crew did not exists!'));
           return redirect()->action([self::class, 'list']);
        }

        if ($request->isMethod('post') && $request) {
            $validated = $this->processValidate($request);
            try {
                $this->processData($validated, $request, $flightCrew, Lang::get('Flight crew is updated!'));
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }
        
        return view('flight.crew.update', ['flightCrew' => $flightCrew]);
    }

     /**
     * delete
     * @param int $id
     * @return object
     */
    public function delete(int $id)
    {
        if (!$id || !FlightsCrew::find($id)) {
            session()->flash('message', Lang::get('This flight crew did not exists!'));
            return redirect()->action([self::class, 'list']);
        }
        $flightsCrew = FlightsCrew::find($id);
        $flightsCrew->delete();
        session()->flash('message', Lang::get('This flight crew was removed!'));
        return redirect()->action([self::class, 'list']);
    }
}
