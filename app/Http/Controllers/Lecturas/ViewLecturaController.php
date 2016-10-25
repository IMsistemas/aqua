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

    /**
     * Retorna la vista de Consulta de Lectura
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('Lecturas.index_viewLectura');
    }


    /**
     * Retorna las lecturas insertadas
     *
     * @return mixed
     */
    public function getLecturas()
    {
    	return Lectura::join('suministro', 'lectura.numerosuministro', '=', 'suministro.numerosuministro')
    						->join('calle', 'suministro.idcalle', '=', 'calle.idcalle')
    						->join('cliente', 'suministro.codigocliente', '=', 'cliente.codigocliente')
                            ->select('idlectura', 'lectura.numerosuministro', 'lecturaanterior', 'observacion',
                                        'lecturaactual', 'consumo', 'calle.nombrecalle', 'cliente.nombre',
                                        'cliente.apellido')
                            ->get();
    }


    /**
     * Retorna las lecturas insertadas filtradas
     *
     * @param $filter
     * @return mixed
     */
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
                            ->join('cliente', 'suministro.codigocliente', '=', 'cliente.codigocliente')
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

        if($filter->text != null && $filter->text != ''){
            $lecturas->whereRaw("cliente.nombre LIKE '%" . $filter->text . "%' OR cliente.apellido LIKE '%" . $filter->text . "%' ");       
        }

        return $lecturas->get();

    }


    /**
     * Retorna los barrios insertados
     *
     * @return mixed
     */
    public function getBarrios()
    {
        return Barrio::orderBy('nombrebarrio', 'asc')->get(); 
    }


    /**
     * Retorna las calles insertadas que pertenecen al barrio entrado por parametro
     *
     * @param $idbarrio
     * @return mixed
     */
    public function getCalles($idbarrio)
    {
        return Calle::where('idbarrio', $idbarrio)->orderBy('nombrecalle', 'asc')->get(); 
    }


    /**
     * Actualiza el recurso de lectura en Lectura Actual y Observacion
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
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
