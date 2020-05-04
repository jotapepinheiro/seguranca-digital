<?php

namespace App\Http\Controllers;

use App\Role;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class RoleController extends Controller
{

    private $role;
    private $permission;

    /**
     * Create a new controller instance.
     *
     * @param Role $role
     * @param Permission $permission
     */
    public function __construct(Role $role, Permission $permission)
    {
        $this->middleware("auth:api");
        $this->role = $role;
        $this->permission = $permission;
    }

    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        return $this->role->orderBy('id','DESC')->paginate(5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {
        return $this->permission->pluck('display_name','id');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'display_name' => 'required',
            'description' => 'required',
            'permissions' => 'required',
        ]);

        //create the new role
        $role = new Role();
        $role->name = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->save();

        //attach the selected permissions
        foreach ($request->input('permissions') as $key => $value) {
            $role->attachPermission($value);
        }

        return response()->json(['data' => ['message' => 'Role created successfully']]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $role = $this->role->with('permissions')->findOrFail($id);

        return response()->json($role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        $role = $this->role->findOrFail($id);
        $permissions = $this->permission->get();

        DB::table("permission_role")
            ->where("role_id",$id)
            ->pluck('permission_id')
            ->toArray();

        return response()->json([$role, $permissions]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'display_name' => 'required',
            'description' => 'required',
            'permissions' => 'required',
        ]);

        //Find the role and update its details
        $role = $this->role->findOrFail($id);
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->save();

        //delete all permissions currently linked to this role
        DB::table("permission_role")->where("role_id",$id)->delete();

        //attach the new permissions to the role
        foreach ($request->input('permissions') as $key => $value) {
            $role->attachPermission($value);
        }

        return response()->json(['data' => ['message' => 'Role updated successfully']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $role = $this->role->findOrFail($id);

        $role->delete();

        return response()->json(['data' => ['message' => 'Role deleted successfully']]);
    }

}
