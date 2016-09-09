<?php

namespace App\Http\Controllers\Lecturas;

use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Cuentas\RubroFijo;
use App\Modelos\Lecturas\Lectura;
use App\Modelos\Tarifas\CostoTarifa;
use App\Modelos\Tarifas\ExcedenteTarifa;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ViewLecturaController extends Controller
{

    public function index()
    {
        return view('Lecturas.index_viewLectura');
    }

    public function getLecturas()
    {
    	return Lectura::join('suministro', 'lectura.numerosuministro', '=', 'suministro.numerosuministro')
    						->join('calle', 'suministro.idcalle', '=', 'calle.idcalle')
    						->join('cliente', 'suministro.documentoidentidad', '=', 'cliente.documentoidentidad')
                            ->select('idlectura', 'lectura.numerosuministro', 'lecturaanterior',
                                        'lecturaactual', 'consumo', 'calle.nombrecalle', 'cliente.nombre',
                                        'cliente.apellido')
                            ->get();
    }

}
