<?php

namespace App\Http\Controllers\Contabilidad;

use App\Modelos\Contabilidad\Cont_PuntoDeVenta;
use App\Modelos\SRI\SRI_Establecimiento;
use App\Modelos\Nomina\Empleado;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

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

    public function verificarCodigo($codigoemision)
    {
        return Cont_PuntoDeVenta::where('cont_puntoventa.codigoptoemision','=', $codigoemision)
        ->get();
    }

    public function empleadoVacio($codigoemision)
    {
        return Empleado::join('cargo','cargo.idcargo','=','empleado.idcargo')
        //->join('persona','persona.idempleado','=','empleado.idempleado')
        ->where('cargo.namecargo','=',"Bodeguero")
        ->get();
    }

    public function cargaEstablecimiento()
    {
        //return $establecimiento=DB::table('sri_establecimiento')->get();
        return $establesimiento = SRI_Establecimiento::all();
        //return response()->json(['establesimiento' => $establesimiento]);
    }


    public function cargarPuntoVenta($id)
    {
             return Cont_PuntoDeVenta::join('sri_establecimiento','sri_establecimiento.idestablecimiento','=','cont_puntoventa.idestablecimiento')
             ->join('empleado','empleado.idempleado','=','cont_puntoventa.idempleado')
             ->join('persona','persona.idpersona','=','empleado.idpersona')
             ->select('sri_establecimiento.razonsocial','cont_puntoventa.codigoptoemision','persona.numdocidentific','persona.namepersona','persona.lastnamepersona')
             ->where('cont_puntoventa.idpuntoventa','=',$id)
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
             $empleado=DB::table('empleado')
            ->join('persona','persona.idpersona','=','empleado.idpersona')
            ->select('empleado.idempleado')
            ->where('persona.numdocidentific','=',$request->input('identificacionempleado'))->first();
            $puntoventa = new Cont_PuntoDeVenta();
            $puntoventa->codigoptoemision = $request->input('codigoemision');
            $puntoventa->idempleado = $empleado->idempleado;
            $puntoventa->idestablecimiento = 1;
            $puntoventa->save();
            return response()->json(['success' => true]);
    }

    /**
     * Mostrar un recurso puntoventa especifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $puntoventa = Cont_PuntoDeVenta::join('empleado','empleado.idempleado','=','cont_puntoventa.idempleado')
            ->join('persona','persona.idpersona','=','empleado.idpersona')
            ->join('sri_establecimiento','sri_establecimiento.idestablecimiento','=','cont_puntoventa.idestablecimiento')
            ->select('sri_establecimiento.razonsocial','cont_puntoventa.idpuntoventa','persona.namepersona','persona.lastnamepersona','cont_puntoventa.codigoptoemision')
            ->get();
        return $puntoventa;
    }

     public function getPuntoventa()
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
        $puntoventa = Cont_PuntoDeVenta::find($id);
        if($request->input('identificacionempleado')!=0){
             $empleado=DB::table('empleado')
            ->join('persona','persona.idpersona','=','empleado.idpersona')
            ->select('empleado.idempleado')
            ->where('persona.numdocidentific','=',$request->input('identificacionempleado'))->first();
            $puntoventa->codigoptoemision = $request->input('codigoemision');
            $puntoventa->idempleado = $empleado->idempleado;
            $puntoventa->save();
        }else{
            $puntoventa->codigoptoemision = $request->input('codigoemision');
            $puntoventa->save();
        }
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
            $puntoventa = Cont_PuntoDeVenta::find($id);
            $puntoventa->delete();
            return response()->json(['success' => true]);
        
    }
}
