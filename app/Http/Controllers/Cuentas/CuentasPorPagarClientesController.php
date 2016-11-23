<?php

namespace App\Http\Controllers\Cuentas;


use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Modelos\Cuentas\CuentasPorPagarClientes;

class CuentasPorPagarClientesController extends Controller
{

    public function index()
    {
        return view('Cuentas.cuentapagar_cliente');
    }
 
     public function ingresarCuenta(Request $request){
        $cuentaPorPagar = new CuentasPorPagarClientes();
        $cuentaPorPagar->documentoidentidad = $request->input('documentoidentidad');
        $cuentaPorPagar->fecha = date("Y-m-d H:i:s");
        $cuentaPorPagar->valor = $request->input('valor');
        $cuentaPorPagar->save();
    }

    public function getAll()
    {
        return CuentasPorPagarClientes::with('cliente')->orderBy('fecha', 'asc')->get();

    }

}
