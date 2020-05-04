<?php

namespace App\Http\Controllers;

use App\Permission;

class PermissionController extends Controller
{
    private $permission;

    /**
     * Create a new controller instance.
     *
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        $this->middleware("auth:api");
        $this->permission = $permission;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->permission->orderBy('id','DESC')->paginate(50);
    }

    /**
     * List User by ID whith Roles and Permissions
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $permission = $this->permission->findOrFail($id);

        return response()->json($permission);
    }

    // TODO strore/update/destroy
}
