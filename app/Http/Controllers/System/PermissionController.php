<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\System\PermissionCollection;
use App\Http\Requests\System\PermissionRequest;
use App\Http\Resources\System\Permission as PermissionResource;
use Spatie\Permission\Models\Permission;


class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        return new PermissionCollection($permissions);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permission = Permission::create(['name' => $request->name]);
        return response()->json([
            'msg' => 'Operação efetuada com sucesso!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\System\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        $permissionData = Permission::findOrfail($permission);
        return new PermissionResource($permissionData);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\System\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $permission = Permission::findOrfail($permission);
        $permission->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);

        return response()->json([
            'msg' => 'Operação efetuada com sucesso!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\System\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrfail($id);
        $permission->delete();
        return response()->json([
            'msg' => 'Permissão eliminada com sucesso',

        ]);
    }
}
