<?php

namespace App\Http\Controllers\Lecturas;

use App\Http\Controllers\Cuentas\RubroVariable;
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
                            ->select('idlectura', 'lectura.numerosuministro', 'lecturaanterior', 'observacion', 'fechalectura',
                                        'lecturaactual', 'consumo', 'calle.nombrecalle', 'cliente.nombres',
                                        'cliente.apellidos')
                            ->whereRaw('EXTRACT( MONTH FROM fechalectura) = ' . date('m'))
                            ->whereRaw('EXTRACT( YEAR FROM fechalectura) = ' . date('Y'))
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
                            ->select('idlectura', 'lectura.numerosuministro', 'lecturaanterior', 'observacion', 'fechalectura',
                                        'lecturaactual', 'consumo', 'calle.nombrecalle', 'cliente.nombre',
                                        'cliente.apellido');

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

            if ($lectura->lecturaactual != $item->lecturaactual) {

                if($lectura->lecturaanterior < $item->lecturaactual) {
                    $lectura->lecturaactual = $item->lecturaactual;
                    $lectura->consumo = $lectura->lecturaactual - $lectura->lecturaanterior;
                    //$this->updateRubrosValue($lectura->consumo, $tarifa, $numerosuministro, $idlectura);
                }

            }

            $lectura->observacion = $item->observacion;
            $lectura->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Retorna un array de los rubros existentes fijos y variables
     *
     * @return array
     */
    public function getRubros()
    {
        $rubrofijo = RubroFijo::all();
        $rubrovariable = RubroVariable::all();

        $tarifabasica = new \stdClass();
        $tarifabasica->id = 1;
        $tarifabasica->type = 'tarifa_basica';
        $tarifabasica->nombrerubro = 'Consumo Tarifa Básica';
        $tarifabasica->valorrubro = '0.00';

        $excedente = new \stdClass();
        $excedente->id = 1;
        $excedente->type = 'excedente';
        $excedente->nombrerubro = 'Excedente';
        $excedente->valorrubro = '0.00';

        $mesesatrasados = new \stdClass();
        $mesesatrasados->id = 1;
        $mesesatrasados->type = 'mesesatrasados';
        $mesesatrasados->nombrerubro = 'Valores Atrasados';
        $mesesatrasados->valorrubro = '0.00';

        $list_rubros = [$tarifabasica, $excedente, $mesesatrasados];

        foreach ($rubrofijo as $rubro){
            $object = new \stdClass();
            $object->id = $rubro->idrubrofijo;
            $object->type = 'rubrofijo';
            $object->nombrerubro = $rubro->nombrerubrofijo;
            $object->valorrubro = '0.00';

            $list_rubros[] = $object;
        }

        foreach ($rubrovariable as $rubro){
            $object = new \stdClass();
            $object->id = $rubro->idrubrovariable;
            $object->type = 'rubrovariable';
            $object->nombrerubro = $rubro->nombrerubrovariable;
            $object->valorrubro = '0.00';

            $list_rubros[] = $object;
        }

        return $list_rubros;
    }

    public function updateRubrosValue($consumo, $tarifa, $numerosuministro, $idlectura)
    {

        $rubros = $this->getRubros();

        //-------------------Valor Consumo: Tarifa Basica----------------------------------------------

        $tarifa_basica = CostoTarifa::where('apartirdenm3', '<=', $consumo)
                                    ->where('idtarifa', $tarifa)
                                    ->orderBy('apartirdenm3', 'desc')
                                    ->take(1)
                                    ->get();

        $rubros[0]->valorrubro = $tarifa_basica[0]->valorconsumo;

        //-------------------Excedente: 0 || (consumo - 15) * % ---------------------------------------

        $excedente = ExcedenteTarifa::where('desdenm3', '<=', $consumo)
                                    ->where('idtarifa', $tarifa)
                                    ->orderBy('desdenm3', 'desc')
                                    ->take(1)
                                    ->get();

        if (count($excedente) == 0) {
            $rubros[1]->valorrubro = 0;
        } else {
            $rubros[1]->valorrubro = ($consumo - 15) * $excedente[0]->valorconsumo;
        }

        //-------------------Valores Atrasados--------------------------------------------------------

        $atraso = CobroAgua::where('estapagada', false)
                                    ->whereRaw('mesesatrasados IS NOT NULL')
                                    ->whereRaw('valormesesatrasados IS NOT NULL')
                                    ->where('numerosuministro', $numerosuministro)
                                    ->whereRaw('EXTRACT( MONTH FROM fechaperiodo) = (EXTRACT( MONTH FROM now()) - 1)')
                                    ->get();

        if (count($atraso) == 0){
            $valormesesatrasados = 0;
            $mesesatrasados = 0;
        } else {
            $valormesesatrasados = $atraso[0]->valormesesatrasados;
            $mesesatrasados = $atraso[0]->mesesatrasados;
        }

        $rubros[2]->valorrubro = $valormesesatrasados;

        //------Rubros Fijos, variables para el calculo-------------------------------------------------

        $costo_tarifa_basica = $rubros[0]->valorrubro;
        $costo_excedente = $rubros[1]->valorrubro;
        $rubrofijo = RubroFijo::all();

        //------Rubros Fijos: Alcantarillado = (Tarifa Basica + Excedente) * 30% ----------------------

        $alcantarillado = ($costo_tarifa_basica + $costo_excedente) * $rubrofijo[0]->costorubro;
        $rubros[3]->valorrubro = $alcantarillado;

        //------Rubros Fijos: DDSS = (Tarifa Basica + Excedente) * 20% -------------------------------

        $ddss = ($costo_tarifa_basica + $costo_excedente) * $rubrofijo[1]->costorubro;
        $rubros[4]->valorrubro = $ddss;

        //------Rubros Variables, variables para el calculo-------------------------------------------

        $cobroagua = CobroAgua::with('rubrosvariables')
                                    ->where('numerosuministro', '=', $numerosuministro)
                                    ->whereRaw('EXTRACT( MONTH FROM fechaperiodo) = ' . date('m'))
                                    ->whereRaw('EXTRACT( YEAR FROM fechaperiodo) = ' . date('Y'))
                                    ->get()->first();

        $list_rubrovariable = $cobroagua->rubrosvariables;

        $length_list = count($list_rubrovariable);
        $length_rubro = count($rubros);

        for($i = $length_list - 1; $i >= 0; $i--){
            $rubros[$length_rubro - 1]->valorrubro = $list_rubrovariable[$i]->pivot->costorubro;
            $length_rubro--;
        }

        $cobroaguaEdit = CobroAgua::where('numerosuministro', '=', $numerosuministro)
                                    ->where('idlectura', '=', $idlectura)
                                    ->whereRaw('EXTRACT( MONTH FROM fechaperiodo) = ' . date('m'))
                                    ->whereRaw('EXTRACT( YEAR FROM fechaperiodo) = ' . date('Y'))
                                    ->get();

        $cobroaguaEdit->idlectura = $idlectura;
        $cobroaguaEdit->consumom3 = $consumo;
        $cobroaguaEdit->valorconsumo = $tarifa_basica;
        $cobroaguaEdit->valorexcedente = $costo_excedente;
        $cobroaguaEdit->mesesatrasados = $mesesatrasados;
        $cobroaguaEdit->valormesesatrasados = $valormesesatrasados;
        //$cobroaguaEdit->total = $request->input('total');
        $cobroaguaEdit->estapagada = false;

        $cobroaguaEdit->save();

        foreach ($rubros as $rubro) {
            if ($rubro['type'] == 'rubrofijo') {
                $cobroaguaEdit->rubrosfijos()->attach($rubro['id'],['costorubro' => $rubro['valorrubro']]);
            }
        }

    }

}
