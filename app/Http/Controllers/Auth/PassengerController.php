<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\Passenger;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PassengerController extends Controller
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
                DB::beginTransaction();
                $model = $this->processData($validated, $request, new Passenger(), __('Passenger is created!'));
                DB::commit();
                Cache::flush();
                if ($model) {
                    return redirect()->action([self::class, 'update'], ['id' => $model->id]);
                }
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getMessage());
            }
        }
        return view('auth.flight.passenger.create', [
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
            'name' => 'required|max:50',
            'flight_id' => 'required'
        ],
            [
               'flight_id.required' => __('Please select the flight!')
        ]
        );
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
            session()->flash('message', __('This passenger did not exists!'));
            return redirect()->action([self::class, 'create']);
        }

        if ($request->isMethod('post') && $request) {
            $validated = $this->processValidate($request);
            try {
                DB::beginTransaction();
                $this->processData($validated, $request, $model, __('Passenger is updated!'));
                DB::commit();
                Cache::flush();
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getMessage());
            }
        }

        return view('auth.flight.passenger.update', [
            'passenger' => $model,
            'flights' => DB::table('flights')->select('id', 'destination')->get()
        ]);
    }
}
