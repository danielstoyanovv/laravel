<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $roles = Role::orderBy('id', 'DESC')->paginate(5);
        return view('roles.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('roles.create', compact('permission'));
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
                $role = Role::create(['name' => $request->input('name')]);
                $role->syncPermissions($request->input('permission'));

                return redirect()->route('roles.index')
                                ->with('success', 'Role created successfully');
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        if (!$role) {
            session()->flash('message', __('This role did not exists!'));
            return redirect()->route('roles.create');
        }
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id)
    {
        $role = Role::find($id);
        if (!$role) {
            session()->flash('message', __('This role did not exists!'));
            return redirect()->route('roles.create');
        }
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
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
                $role = Role::find($id);
                $role->name = $request->input('name');
                $role->save();
                $role->syncPermissions($request->input('permission'));

                return redirect()->route('roles.index')
                                ->with('success', 'Role updated successfully');
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                //die($e->getMessage());
            }
        }
        return redirect()->route('roles.index');
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
            DB::table("roles")->where('id', $id)->delete();
            return redirect()->route('roles.index')
                            ->with('success', 'Role deleted successfully');
        }
        return redirect()->route('roles.index');
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
            'permission' => 'required',
        ]);
    }
}
