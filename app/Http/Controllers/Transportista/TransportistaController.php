<?php

namespace App\Http\Controllers\Transportista;

use App\Modelos\Persona;
use App\Modelos\Proveedores\Proveedor;
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
            $transportista = $transportista->whereRaw("persona.razonsocial ILIKE '%" . $search . "%'");
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


    public function getProveedores()
    {
        return Proveedor::join('persona', 'proveedor.idpersona', '=', 'persona.idpersona')->get();
    }

    public function searchDuplicate($numidentific)
    {
        $result = $this->searchExist($numidentific);
        return response()->json(['success' => $result]);
    }

    private function searchExist($numidentific)
    {
        $count = Transportista::join('persona', 'transportista.idpersona', '=', 'persona.idpersona')
                                ->where('persona.numdocidentific', $numidentific)->count();

        return ($count >= 1) ? true : false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($this->searchExist($request->input('documentoidentidadempleado'))) {

            return response()->json(['success' => false, 'type_error_exists' => true]);

        } else {

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
                $transportista->idproveedor = $request->input('idproveedor');

                if ($transportista->save()) {
                    return response()->json(['success' => true]);
                } else return response()->json(['success' => false]);

            } else return response()->json(['success' => false]);

        }

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
        //$state = false;

        /*if ($request->input('idpersona') == 0) {
            $persona = new Persona();
        } else {
            $persona = Persona::find($request->input('idpersona'));
            $state = true;
        }*/

        $persona = Persona::find($request->input('idpersona_edit'));

        $persona->numdocidentific = $request->input('documentoidentidadempleado');
        $persona->email = $request->input('correo');
        $persona->celphone = $request->input('celular');
        $persona->idtipoidentificacion = $request->input('tipoidentificacion');
        $persona->razonsocial = $request->input('razonsocial');
        $persona->direccion = $request->input('direccion');

        $persona->lastnamepersona = $request->input('razonsocial');
        $persona->namepersona = $request->input('razonsocial');

        /*if ($state == false) {
            $persona->lastnamepersona = $request->input('razonsocial');
            $persona->namepersona = $request->input('razonsocial');
        }*/

        if ($persona->save()) {
            $transportista = Transportista::find($id);
            $transportista->fechaingreso = $request->input('fechaingreso');
            $transportista->estado = true;
            $transportista->idpersona = $persona->idpersona;
            $transportista->placa = $request->input('placa');
            $transportista->telefonoprincipal = $request->input('telefonoprincipal');
            $transportista->idproveedor = $request->input('idproveedor');

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

        if ($this->getCountTransportistaUtilizado($id) > 0) {

            return response()->json(['success' => false, 'exists' => true]);

        } else {

            $transportista = Transportista::find($id);
            if ($transportista->delete()) {
                return response()->json(['success' => true]);
            }
            else return response()->json(['success' => false]);

        }

    }

    private function getCountTransportistaUtilizado($id)
    {
        $whereRaw = 'idtransportista IN (SELECT idtransportista FROM cont_documentoguiaremision) ';

        $count = Transportista::where('idtransportista', $id)->whereRaw($whereRaw)->count();

        return $count;
    }
}
