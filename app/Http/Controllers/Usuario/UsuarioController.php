<?php

namespace App\Http\Controllers\Usuario;

use App\Modelos\Nomina\Empleado;
use App\Modelos\Rol\Rol;
use App\Modelos\Usuario\Usuario;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('usuario.index');
    }

    /**
     * Obtener todos los usuarios de manera ascendente
     *
     * @return mixed
     */
    public function getUsuarios(Request $request)
    {
        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $user = null;

        if ($search != null) {
            $user = Usuario::join('rol', 'rol.idrol', '=', 'usuario.idrol')
                ->whereRaw("usuario.usuario ILIKE '%" . $search . "%' OR rol.namerol ILIKE '%" . $search . "%'")
                ->orderBy('usuario.usuario', 'asc');
        } else {
            $user = Usuario::join('rol', 'rol.idrol', '=', 'usuario.idrol')->orderBy('usuario.usuario', 'asc');
        }

        return $user->paginate(10);
    }

    public function getRoles()
    {
        return Rol::orderBy('namerol', 'asc')->get();
    }

    public function getEmpleados()
    {
        return Empleado::with('persona')->get();
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
        $result = Usuario::where('usuario', $request->input('usuario'))->count();

        if ($result == 0) {

            $user = new Usuario();
            $user->idrol = $request->input('idrol');
            $user->usuario = $request->input('usuario');

            if ($request->input('idempleado') != null) {
                $user->idempleado = $request->input('idempleado');
            }

            $user->password = Hash::make($request->input('password'));

            if ($user->save()) {
                return response()->json(['success' => true]);
            } else return response()->json(['success' => false]);

        } else {
            return response()->json(['success' => false, 'exists' => true]);
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
        return Usuario::find($id);
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

        $result = Usuario::where('usuario', $request->input('usuario'))
                            ->where('idusuario', '!=', $id)->count();

        if ($result == 0) {

            $user = Usuario::find($id);
            $user->idrol = $request->input('idrol');
            $user->usuario = $request->input('usuario');

            if ($request->input('idempleado') != null) {
                $user->idempleado = $request->input('idempleado');
            }

            $user->password = Hash::make($request->input('password'));

            if ($user->save()) {
                return response()->json(['success' => true]);
            } else return response()->json(['success' => false]);

        } else {
            return response()->json(['success' => false, 'exists' => true]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Usuario::find($id);

        if ($user->delete()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
