<?php

namespace App\Http\Controllers\Contabilidad;

use App\Modelos\Contabilidad\Cont_PuntoDeVenta;
use App\Modelos\SRI\SRI_Establecimiento;
use App\Modelos\Nomina\Empleado;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PuntoVentaController  extends Controller
{
    /**
     * Mostrar una lista de los recursos de Puntoventa
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Puntoventa.index_ptoventa');
    }

    /**
     * Obtener todos los Puntoventa de manera ascendente
     *
     * @return mixed
     */

    public function getEmpleado($texto)
    {
        return Empleado::join('persona','empleado.idpersona','=','persona.idpersona')
        ->whereRaw("persona.namepersona ilike '%".$texto."%' or persona.lastnamepersona ilike '%".$texto."%'")
        ->get();
    }


    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getpuntoventaByID($id)
    {
        return Puntoventa::where('idpuntoventa', $id)->orderBy('namepuntoventa')->get();
    }

    public function verificarCodigo($codigoemision)
    {
        return Cont_Puntoventa::where('codigoptoemision', $codigoemisión)
        ->get();
    }

    public function getEstablecimiento()
    {
        return SRI_Establecimento::select('sri_establecimiento.razonsocial')->get();
    }

    /**
     * Obtener los Puntoventa filtrados
     *
     * @param $filter
     * @return mixed
     */
    public function getByFilter($filter)
    {
        $filter = json_decode($filter);

        return Puntoventa::orderBy('idpuntoventa', 'asc')
                      ->whereRaw("puntoventa.namepuntoventa ILIKE '%" . $filter->text . "%' ")
                      ->get();
    }

    /**
     * Almacenar un recurso puntoventa recién creado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $puntoventa1 = Puntoventa::where('namepuntoventa', $request->input('nombrepuntoventa'))->count();

        if ($puntoventa1 > 0) {
            return response()->json(['success' => false]);
        } else {
            $puntoventa = new puntoventa();
            $puntoventa->namepuntoventa = $request->input('nombrepuntoventa');

            if ($puntoventa->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }

    /**
     * Mostrar un recurso puntoventa especifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $puntoventa = Cont_PuntoDeVenta::join('empleado','empleado.idempleado','=','cont_puntoventa.idempleado')
            ->join('persona','persona.idpersona','=','empleado.idpersona')
            ->join('sri_establecimiento','sri_establecimiento.idestablecimiento','=','cont_puntoventa.idestablecimiento')
            ->select('sri_establecimiento.razonsocial','cont_puntoventa.idpuntoventa','persona.namepersona','cont_puntoventa.codigoptoemision')
            ->get();
        return $puntoventa;
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
        $puntoventa = Puntoventa::find($id);
        $puntoventa->namepuntoventa = $request->input('nombrepuntoventa');
        if ($puntoventa->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
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
        $empleado = Empleado::where('idpuntoventa',$id)->count();
        if ($empleado > 0) {
            return response()->json(['success' => false]);
        } else {
            $puntoventa = puntoventa::find($id);
            $puntoventa->delete();
            return response()->json(['success' => true]);
        }
    }
}
