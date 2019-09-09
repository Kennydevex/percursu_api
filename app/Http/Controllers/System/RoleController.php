<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
// use Role;
use Illuminate\Http\Request;
use App\Http\Resources\System\RoleCollection;
use App\Http\Resources\System\Role as RoleResource;
use App\Http\Requests\System\RoleRequest;


use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;

// $permission = Permission::create(['name' => 'edit articles']);


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return new RoleCollection($roles);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return response()->json([
            'msg' => 'Operação efetuada com sucesso!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\System\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $roleData = Role::findOrfail($role);
        return new RoleResource($roleData);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\System\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $role = Role::findOrfail($role);
        $role->update([
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
     * @param  \App\Models\System\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrfail($id);
        $role->delete();
        return response()->json([
            'msg' => 'Função eliminada com sucesso',

        ]);
    }
}
