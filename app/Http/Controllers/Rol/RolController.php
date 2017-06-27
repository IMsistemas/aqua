<?php

namespace App\Http\Controllers\Rol;

use App\Modelos\Rol\Permiso;
use App\Modelos\Rol\PermisoRol;
use App\Modelos\Rol\Rol;
use App\Modelos\Usuario\Usuario;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('rol.index');
    }

    /**
     * Obtener todos los roles de manera ascendente
     *
     * @return mixed
     */
    public function getRoles(Request $request)
    {
        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $rol = null;

        if ($search != null) {
            $rol = Rol::whereRaw("rol.namerol ILIKE '%" . $search . "%'")->orderBy('namerol', 'asc');
        } else {
            $rol = Rol::orderBy('namerol', 'asc');
        }

        return $rol->paginate(10);
    }

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRolByID($id)
    {
        return Rol::where('idrol', $id)->get();
    }

    public function getPermisos($id)
    {
        return Permiso::with([
            'permiso_rol' => function ($query) use ($id) {
                $query->where('idrol', $id);
            }

        ])->orderBy('namepermiso', 'asc')->get();
    }

    public function getPermisosRol()
    {
        $id = Session::get('users')[0]->idrol;

        return Permiso::with([
            'permiso_rol' => function ($query) use ($id) {
                $query->where('idrol', $id);
            }

        ])->orderBy('namepermiso', 'asc')->get();
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
        $count = Rol::where('namerol', $request->input('namerol'))->count();

        if ($count > 0) {
            return response()->json(['success' => false]);
        } else {
            $cargo = new Rol();
            $cargo->namerol = $request->input('namerol');

            if ($cargo->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }

    public function savePermisos(Request $request)
    {

        $idrol = $request->input('idrol');

        foreach ($request->input('permisos') as $element) {

            $result = PermisoRol::where('idrol', $idrol)->where('idpermiso', $element['idpermiso'])->get();

            if (count($result) == 0) {

                $permiso_rol = new PermisoRol();
                $permiso_rol->idrol = $idrol;
                $permiso_rol->idpermiso = $element['idpermiso'];
                $permiso_rol->state = $element['state'];

                if ($permiso_rol->save() == false) {
                    return response()->json(['success' => false]);
                }

            } else {

                $object = PermisoRol::where('idrol', $idrol)->where('idpermiso', $element['idpermiso']);

                if ($object->update(['state' => $element['state']]) == false){
                    return response()->json(['success' => false]);
                }

            }

        }

        return response()->json(['success' => true]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $count = Rol::where('namerol', $request->input('namerol'))->count();

        if ($count > 0) {
            return response()->json(['success' => false, 'repeat' => true]);
        } else {
            $rol = Rol::find($id);
            $rol->namerol = $request->input('namerol');
            if ($rol->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'repeat' => false]);
            }
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
        $count = Usuario::where('idrol',$id)->count();
        if ($count > 0) {
            return response()->json(['success' => false, 'exists' => true]);
        } else {
            $rol = Rol::find($id);

            if ($rol->delete()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'exists' => false]);
            }
        }
    }
}
