<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::orderBy('id', 'desc')->paginate(8);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'names' => 'required|string|max:50',
            'name' => 'required|string|max:20|unique:users|',
            'password' => 'required|string|max:20',
            'role' => 'required'
        ], [], [
            'names' => 'Nombres completos',
            'name' => 'Nombre de usuario',
            'password' => 'Contraseña',
            'role' => 'Rol'
        ]);

        if($request->input('role')=='administrador' || $request->input('role')=='vendedor'){
            $user = new User();
            $user->names = $request->input('names');
            $user->name = $request->input('name');
            $user->password = bcrypt($request->input('password'));
            if($request->input('role')=='administrador'){
                $user->role = 'administrador';
            }else{
                $user->role = 'vendedor';
            }
            $user->save();
        }else{
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'names' => 'required|string|max:50',
            'name' => 'required|string|max:20|unique:users,id',
            'role' => 'required'
        ], [], [
            'names' => 'Nombres completos',
            'name' => 'Nombre de usuario',
            'role' => 'Rol'
        ]);
        $user = User::findOrFail($id);
        $user->names = $request->input('names');
        $user->name = $request->input('name');
        if($request->input('password')){
            $user->password = bcrypt($request->input('password'));
        }
        $user->role = $request->input('role');
        $user->save();
        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(\Auth::user()->id == $id){
            return response()->json(['msg'=>'No se puede eliminar a sí mismo'], 404);
        }else{
            $user = User::findOrFail($id);
            $user->delete();
        }
    }
}
