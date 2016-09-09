<?php

namespace App\Http\Controllers\Lecturas;

use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Cuentas\RubroFijo;
use App\Modelos\Lecturas\Lectura;
use App\Modelos\Tarifas\CostoTarifa;
use App\Modelos\Tarifas\ExcedenteTarifa;
use App\Modelos\Sectores\Barrio;
use App\Modelos\Sectores\Calle;
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
                            ->select('idlectura', 'lectura.numerosuministro', 'lecturaanterior', 'observacion',
                                        'lecturaactual', 'consumo', 'calle.nombrecalle', 'cliente.nombre',
                                        'cliente.apellido')
                            ->get();
    }


    public function getByFilter($filter)
    {
        
        $filter = json_decode($filter);


        $array_filters = [];

        if($filter->barrio != null && $filter->barrio != ''){
            $array_filters[] = ['calle.idbarrio', '=', $filter->barrio];       
        }

        if($filter->calle != null && $filter->calle != ''){
            $array_filters[] = ['calle.idcalle', '=', $filter->calle];       
        }



        $lecturas = Lectura::join('suministro', 'lectura.numerosuministro', '=', 'suministro.numerosuministro')
                            ->join('calle', 'suministro.idcalle', '=', 'calle.idcalle')
                            ->join('cliente', 'suministro.documentoidentidad', '=', 'cliente.documentoidentidad')
                            ->join('barrio', 'barrio.idbarrio', '=', 'calle.idbarrio')
                            ->select('idlectura', 'lectura.numerosuministro', 'lecturaanterior', 'observacion',
                                        'lecturaactual', 'consumo', 'calle.nombrecalle', 'cliente.nombre',
                                        'cliente.apellido');

        if(count($array_filters) == 1){

            $array_filters[0][2] = "'" . $array_filters[0][2] . "'";

            $lecturas->whereRaw(implode(' ', $array_filters[0]));
        
        } else {
        
            $lecturas->where($array_filters);
        
        }

        if($filter->mes != null && $filter->mes != ''){
            $lecturas->whereRaw('EXTRACT( MONTH FROM lectura.fechalectura) = ' . $filter->mes);       
        }

        if($filter->anno != null && $filter->anno != ''){
            $lecturas->whereRaw('EXTRACT( YEAR FROM lectura.fechalectura) = ' . $filter->anno);       
        }

        return $lecturas->get();

    }

    public function getBarrios()
    {
        return Barrio::orderBy('nombrebarrio', 'asc')->get(); 
    }

    public function getCalles($idbarrio)
    {
        return Calle::where('idbarrio', $idbarrio)->orderBy('nombrecalle', 'asc')->get(); 
    }

    public function update($request)
    {
        
        $params = json_decode($request);

        foreach ($params as $item) {
            $lectura = Lectura::find($item->idlectura);
            $lectura->lecturaactual = $item->lecturaactual;
            $lectura->observacion = $item->observacion;
            $lectura->save();
        }

        return response()->json(['success' => true]);
    }

}
