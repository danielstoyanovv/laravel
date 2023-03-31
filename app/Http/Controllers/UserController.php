<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id', 'DESC')->paginate(5);
        return view('users.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post') && $request) {
            $validated = $this->processValidate($request);
            try {
                $validated['password'] = Hash::make($validated['password']);
                $user = User::create($validated);
                $user->assignRole($request->input('roles'));
                return redirect()->route('users.index')->with('success', __('User created successfully'));
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id)
    {
        $user = User::find($id);
        if (!$user) {
            session()->flash('message', __('This user did not exists!'));
            return redirect()->route('users.create');
        }
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id)
    {
        $user = User::find($id);
        if (!$user) {
            session()->flash('message', __('This user did not exists!'));
            return redirect()->route('users.create');
        }
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request  $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id)
    {
        if ($request->isMethod('patch') && $request) {
            $validated = $this->processValidate($request);
            try {
                $validated['password'] = Hash::make($validated['password']);
                //var_dump($validated); die;
                $user = User::find($id);
                $user->update($validated);
                DB::table('model_has_roles')->where('model_id', $id)->delete();
                $user->assignRole($request->input('roles'));

                return redirect()->route('users.index')
                                ->with('success', 'User updated successfully');
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                //die($e->getMessage());
            }
        }
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        if ($id) {
            User::find($id)->delete();
            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        }
        return redirect()->route('users.index');
    }

    /**
     * process validate
     *
     * @param Request $request
     * @return array
     */
    private function processValidate(Request $request): array
    {
        return $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|same:confirm-password',
        ]);
    }
}
