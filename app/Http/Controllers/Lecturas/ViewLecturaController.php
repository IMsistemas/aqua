<?php

namespace App\Http\Controllers\Lecturas;

use App\Modelos\Lecturas\Lectura;
use App\Modelos\Sectores\Barrio;
use App\Modelos\Sectores\Calle;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
     * Obtener las lecturas insertadas
     *
     * @return mixed
     */
    public function getLecturas()
    {
    	return Lectura::join('suministro', 'lectura.idsuministro', '=', 'suministro.idsuministro')
    						->join('calle', 'suministro.idcalle', '=', 'calle.idcalle')
    						->join('cliente', 'suministro.idcliente', '=', 'cliente.idcliente')
                            ->join('persona', 'cliente.idpersona', '=', 'persona.idpersona')
                            ->join('cobroagua', 'lectura.idlectura', '=', 'cobroagua.idlectura')
                            //->join('facturacobro', 'facturacobro.idcobroagua', '=', 'cobroagua.idcobroagua')
                            ->select('lectura.idlectura', 'lectura.idsuministro', 'lecturaanterior', 'lectura.observacion', 'fechalectura',
                                        'lecturaactual', 'consumo', 'calle.namecalle', 'suministro.*', 'cliente.*', 'persona.*')
                            ->whereRaw('EXTRACT( MONTH FROM fechalectura) = ' . date('m'))
                            ->whereRaw('EXTRACT( YEAR FROM fechalectura) = ' . date('Y'))
                            ->get();
    }

    /**
     * Obtener las lecturas insertadas filtradas
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

        $lecturas = Lectura::join('suministro', 'lectura.idsuministro', '=', 'suministro.idsuministro')
                            ->join('calle', 'suministro.idcalle', '=', 'calle.idcalle')
                            ->join('cliente', 'suministro.idcliente', '=', 'cliente.idcliente')
                            ->join('persona', 'cliente.idpersona', '=', 'persona.idpersona')
                            ->join('cobroagua', 'lectura.idlectura', '=', 'cobroagua.idlectura')
                            ->select('lectura.idlectura', 'lectura.idsuministro', 'lecturaanterior', 'lectura.observacion', 'fechalectura',
                                'lecturaactual', 'consumo', 'calle.namecalle', 'suministro.*', 'cliente.*', 'persona.*');

        if(count($array_filters) == 1){
            $array_filters[0][2] = "'" . $array_filters[0][2] . "'";
            $lecturas->whereRaw(implode(' ', $array_filters[0]));        
        } else {        
            $lecturas->where($array_filters);        
        }

        if($filter->mes != null && $filter->mes != '0'){
            $lecturas->whereRaw('EXTRACT( MONTH FROM lectura.fechalectura) = ' . $filter->mes);       
        }

        if($filter->anno != null && $filter->anno != ''){
            $lecturas->whereRaw('EXTRACT( YEAR FROM lectura.fechalectura) = ' . $filter->anno);       
        }

        if($filter->text != null && $filter->text != ''){
            $lecturas->whereRaw("persona.namepersona ILIKE '%" . $filter->text . "%' OR persona.lastnamepersona ILIKE '%" . $filter->text . "%' ");
        }

        return $lecturas->get();

    }

    /**
     * obtener los barrios
     *
     * @return mixed
     */
    public function getBarrios()
    {
        return Barrio::orderBy('namebarrio', 'asc')->get();
    }

    /**
     * Obtener las calles insertadas que pertenecen al barrio entrado por parametro
     *
     * @param $idbarrio
     * @return mixed
     */
    public function getCalles($idbarrio)
    {
        return Calle::where('idbarrio', $idbarrio)->orderBy('namecalle', 'asc')->get();
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

            if ($lectura->lecturaactual != $item->lecturaactual) {

                if($lectura->lecturaanterior < $item->lecturaactual) {
                    $lectura->lecturaactual = $item->lecturaactual;
                    $lectura->consumo = $lectura->lecturaactual - $lectura->lecturaanterior;
                    //$this->updateRubrosValue($lectura->consumo, $tarifa, $numerosuministro, $idlectura);
                } else {
                    return response()->json(['success' => false]);
                }

            }

            $lectura->observacion = $item->observacion;
            $lectura->save();
        }

        return response()->json(['success' => true]);
    }

}
