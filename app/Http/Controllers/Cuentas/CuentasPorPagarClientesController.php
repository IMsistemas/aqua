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
        $cuentaPorPagar->documentoidentidad = $request->input('cliente.documentoidentidad');
        $cuentaPorPagar->fechaperiodo = date("Y-m-d H:i:s");
        $cuentaPorPagar->valor = $request->input('valor');
        $cuentaPorPagar->save();
    }

    public function getAll()
    {
        $cuentas = CuentasPorPagarClientes::join('cliente', 'cuentasporpagarclientes.documentoidentidad', '=',
                                                    'cliente.documentoidentidad')
                                        ->select('cliente.apellido', 'cliente.nombre', 'cliente.documentoidentidad',
                                            'cuentasporpagarclientes.fecha', 'cuentasporpagarclientes.valor')
                                        ->get();

        return $cuentas;
    }

    public function getByFilter($filter)
    {

        $filter = json_decode($filter);

        $cuentas = CuentasPorPagarClientes::join('cliente', 'cuentasporpagarclientes.documentoidentidad', '=',
                                        'cliente.documentoidentidad')
                                        ->select('cliente.apellido', 'cliente.nombre', 'cliente.documentoidentidad',
                                            'cuentasporpagarclientes.fecha', 'cuentasporpagarclientes.valor');


        if($filter->text != null && $filter->text != ''){
            $cuentas->whereRaw("cliente.nombre LIKE '%" . $filter->text . "%' OR cliente.apellido LIKE '%" . $filter->text . "%' ");
        }

        return $cuentas->get();

    }

}
