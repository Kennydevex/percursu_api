<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use User;
use UserCollection;
use UserResource;
use Folk;
use Common;
use Illuminate\Http\Request;
use App\Http\Requests\System\UserRequest;

class UserController extends Controller
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
        $users = User::all();
        $users->each(function ($users) {
            $users->folk;
            $users->roles;
            $users->permissions;
        });
        return new UserCollection($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $folk = Common::createFolk($request);
        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'status' => $request->status,
            'folk_id' => $folk->id,
        ]);

        if (!$user) {
            $folk->delete();
            return;
        }

        $user->givePermissionTo($request->permissions);
        $user->assignRole($request->roles);
       
        return response()->json([
            'msg' => 'Utilizador registado com sucesso',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\System\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        $user = User::whereUsername($username)->firstOrFail();
        $user->folk;
        $user->roles;
        $user->permissions;
        return new UserResource($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\System\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $username)
    {
        $user = User::whereUsername($username)->firstOrFail();
        $folk = Folk::findOrfail($user->folk->id);
        $folk->update([
            'name' => $request->input('folk.name'),
            'lastname' => $request->input('folk.lastname'),
            'gender' => $request->input('folk.gender'),
            'avatar' => $request->input('folk.avatar'),
            'ic' => $request->input('folk.ic'),
            'nif' => $request->input('folk.nif'),
            'birthdate' => $request->input('folk.birthdate'),
        ]);
        $user->update([
            'email' => $request->email,
            'username' => $request->username,
            'status' => $request->status,
        ]);



        $user->syncPermissions($request->permissions);
        $user->syncRoles($request->roles);

        return response()->json([
            'msg' => 'Dados do utilizador atualizado com sucesso!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\System\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $ids = explode(",", $id);
        User::destroy($ids);
        return response()->json([
            'msg' => 'Utilizador eliminado com sucesso',
        ]);
    }

    // My methods
    public function changeUserActivation($id)
    {
        $user = User::findOrfail($id);
        $user->status = !$user->status;
        $user->update();

        return response()->json([
            'msg' => 'Operação efetuado do com sucesso ',
        ]);
    }

    public function usersWithoutPartner()
    {
        return response()->json([
            'msg' => 'Pegal prop ',
        ]);
    }
}
