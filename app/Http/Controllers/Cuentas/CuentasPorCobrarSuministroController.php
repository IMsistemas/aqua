<?php

namespace App\Http\Controllers\Cuentas;

use App\Modelos\Cuentas\CuentasPorCobrarSuministro;
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
        $cuentaPorCobrar->documentoidentidad = $request->input('documentoidentidad');
        $cuentaPorCobrar->fecha = date("Y-m-d H:i:s");
        $cuentaPorCobrar->dividendos = $request->input('dividendos');
        $cuentaPorCobrar->pagototal = $request->input('pagototal');
        $cuentaPorCobrar->pagoporcadadividendo = $request->input('pagoporcadadividendo');
        $cuentaPorCobrar->save();
    }

    public function getAll()
    {
        $cuentas = CuentasPorCobrarSuministro::join(
            'cliente', 'cuentaporcobrarsuministro.documentoidentidad', '=',
            'cliente.documentoidentidad')
            ->select('cliente.apellido', 'cliente.nombre', 'cliente.documentoidentidad',
                'cuentaporcobrarsuministro.fecha', 'cuentaporcobrarsuministro.dividendos',
                'cuentaporcobrarsuministro.pagototal', 'cuentaporcobrarsuministro.pagoporcadadividendo',
                'cuentaporcobrarsuministro.numerosuministro')
            ->get();

        return $cuentas;
    }

    public function getByFilter($filter)
    {

        $filter = json_decode($filter);

        $cuentas = CuentasPorCobrarSuministro::join('cliente', 'cuentaporcobrarsuministro.documentoidentidad', '=',
            'cliente.documentoidentidad')
            ->select('cliente.apellido', 'cliente.nombre', 'cliente.documentoidentidad',
                'cuentaporcobrarsuministro.fecha', 'cuentaporcobrarsuministro.dividendos',
                'cuentaporcobrarsuministro.pagototal', 'cuentaporcobrarsuministro.pagoporcadadividendo',
                'cuentaporcobrarsuministro.numerosuministro');


        if($filter->text != null && $filter->text != ''){
            $cuentas->whereRaw("cliente.nombre LIKE '%" . $filter->text . "%' OR cliente.apellido LIKE '%" . $filter->text . "%' ");
        }

        return $cuentas->get();

    }
}
