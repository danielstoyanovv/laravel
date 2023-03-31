<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\FlightsCrew;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\FlightCrewController as FrontEndFlightCrewController;
use App\Http\Controllers\Controller;

class FlightCrewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
                $flightCrew = $this->processData($validated, $request, new FlightsCrew(), __('Flight crew is created!'));
                if ($flightCrew) {
                    return redirect()->action([self::class, 'update'], ['id' => $flightCrew->id]);
                }
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }
        return view('auth.flight.crew.create', [
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
        return $request->validate(
            [
            'main_captain' => 'required|max:50',
            'captain' => 'required|max:50',
            'crew_member_1' => 'required|max:50',
            'crew_member_2' => 'required|max:50',
            'crew_member_3' => 'required|max:50',
            'flight_id' => 'required',
        ],
            [
                'flight_id.required' => __('Please select flight destination!')
            ]
        );
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
            session()->flash('message', __('This flight crew did not exists!'));
            return redirect()->action([self::class, 'create']);
        }

        if ($request->isMethod('post') && $request) {
            $validated = $this->processValidate($request);
            try {
                $this->processData($validated, $request, $flightCrew, __('Flight crew is updated!'));
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }

        return view('auth.flight.crew.update', [
            'flightCrew' => $flightCrew,
            'flights' => DB::table('flights')->select('id', 'destination')->get()
        ]);
    }

     /**
     * delete
     * @param int $id
     * @return object
     */
    public function delete(int $id)
    {
        if (!$id || !FlightsCrew::find($id)) {
            session()->flash('message', __('This flight crew did not exists!'));
            return redirect()->action([self::class, 'create']);
        }
        $flightsCrew = FlightsCrew::find($id);
        $flightsCrew->delete();
        session()->flash('message', __('This flight crew was removed!'));
        return redirect()->action([FrontEndFlightCrewController::class, 'list']);
    }
}
