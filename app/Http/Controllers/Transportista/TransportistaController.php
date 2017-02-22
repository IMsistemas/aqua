<?php

namespace App\Http\Controllers\Transportista;

use App\Modelos\Persona;
use App\Modelos\SRI\SRI_TipoIdentificacion;
use App\Modelos\Transportista\Transportista;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransportistaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Transportista.index');
    }


    /**
     * Obtener todos los transportistas
     *
     * @param Request $request
     * @return mixed
     */
    public function getTransportista(Request $request)
    {
        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $transportista = null;

        $transportista = Transportista::join('persona', 'persona.idpersona', '=', 'transportista.idpersona');

        if ($search != null) {
            $transportista = $transportista->whereRaw("persona.razonsocial LIKE '%" . $search . "%'");
        }

        return $transportista->orderBy('fechaingreso', 'desc')->paginate(10);
    }


    /**
     * Obtener todos los tipos de identificacion
     *
     * @return mixed
     */
    public function getTipoIdentificacion()
    {
        return SRI_TipoIdentificacion::orderBy('nameidentificacion', 'asc')->get();
    }


    /**
     * Obtener y devolver los numeros de identificacion que concuerden con el parametro a buscar
     *
     * @param $identify
     * @return mixed
     */
    public function getIdentify($identify)
    {
        return Persona::whereRaw("numdocidentific::text ILIKE '%" . $identify . "%'")
            ->whereRaw('idpersona NOT IN (SELECT idpersona FROM transportista)')
            ->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $state = false;

        if ($request->input('idpersona') == 0) {
            $persona = new Persona();
        } else {
            $persona = Persona::find($request->input('idpersona'));
            $state = true;
        }

        $persona->numdocidentific = $request->input('documentoidentidadempleado');
        $persona->email = $request->input('correo');
        $persona->celphone = $request->input('celular');
        $persona->idtipoidentificacion = $request->input('tipoidentificacion');
        $persona->razonsocial = $request->input('razonsocial');
        $persona->direccion = $request->input('direccion');

        if ($state == false) {
            $persona->lastnamepersona = $request->input('razonsocial');
            $persona->namepersona = $request->input('razonsocial');
        }

        if ($persona->save()) {
            $transportista = new Transportista();
            $transportista->fechaingreso = $request->input('fechaingreso');
            $transportista->estado = true;
            $transportista->idpersona = $persona->idpersona;
            $transportista->placa = $request->input('placa');
            $transportista->telefonoprincipal = $request->input('telefonoprincipal');

            if ($transportista->save()) {
                return response()->json(['success' => true]);
            } else return response()->json(['success' => false]);

        } else return response()->json(['success' => false]);

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
        $state = false;

        if ($request->input('idpersona') == 0) {
            $persona = new Persona();
        } else {
            $persona = Persona::find($request->input('idpersona'));
            $state = true;
        }

        $persona->numdocidentific = $request->input('documentoidentidadempleado');
        $persona->email = $request->input('correo');
        $persona->celphone = $request->input('celular');
        $persona->idtipoidentificacion = $request->input('tipoidentificacion');
        $persona->razonsocial = $request->input('razonsocial');
        $persona->direccion = $request->input('direccion');

        if ($state == false) {
            $persona->lastnamepersona = $request->input('razonsocial');
            $persona->namepersona = $request->input('razonsocial');
        }

        if ($persona->save()) {
            $transportista = Transportista::find($id);
            $transportista->fechaingreso = $request->input('fechaingreso');
            $transportista->estado = true;
            $transportista->idpersona = $persona->idpersona;
            $transportista->placa = $request->input('placa');
            $transportista->telefonoprincipal = $request->input('telefonoprincipal');

            if ($transportista->save()) {
                return response()->json(['success' => true]);
            } else return response()->json(['success' => false]);

        } else return response()->json(['success' => false]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transportista = Transportista::find($id);
        if ($transportista->delete()) {
            return response()->json(['success' => true]);
        }
        else return response()->json(['success' => false]);
    }
}
