<?php

namespace App\Http\Controllers\Cuentas;

use App\Modelos\Cuentas\CuentasPorCobrarSuministro;
use App\Modelos\Suministros\Suministro;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CuentasPorCobrarSuministroController extends Controller
{

    public function index()
    {
        return view('Cuentas.cuentacobrar_cliente');
    }

    public function ingresarCuenta(Request $request){
        $cuentaPorCobrar = new CuentasPorCobrarSuministro();
        $suministros = Suministro::all();

        $cuentaPorCobrar->cuotainicial =            $request->input('cuotainicial');
        $cuentaPorCobrar->numerosuministro =        count($suministros);  
        $cuentaPorCobrar->idsolicitud =             $request->input('idsolicitud');
        $cuentaPorCobrar->documentoidentidad =      $request->input('documentoidentidad');
        $cuentaPorCobrar->fecha =                   date("Y-m-d H:i:s");
        $cuentaPorCobrar->dividendos =              $request->input('dividendos');
        $cuentaPorCobrar->pagototal =               $request->input('pagototal');
        $cuentaPorCobrar->pagoporcadadividendo =    $request->input('pagoporcadadividendo');
        $cuentaPorCobrar->save();
    }

    public function getAll()
    {

        return CuentasPorCobrarSuministro::with('cliente', 'suministro')->orderBy('fecha', 'asc')->get();
    }


}
