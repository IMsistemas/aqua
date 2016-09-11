<?php

namespace App\Http\Controllers\Lecturas;

use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Cuentas\RubroFijo;
use App\Modelos\Lecturas\Lectura;
use App\Modelos\Tarifas\CostoTarifa;
use App\Modelos\Tarifas\ExcedenteTarifa;
use App\Modelos\Suministros\Suministro;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LecturaController extends Controller
{

    /**
     * Retorna la vista de la nueva Lectura
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('Lecturas.index_nuevaLectura');
    }


    /**
     * Retorna el ultimo id insertado + 1
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLastID()
    {
        $last_id = Lectura::max('idlectura');
        ($last_id == 0) ? $last_id = 1 : $last_id += 1;
        return response()->json(['lastID' => $last_id]);
    }


    /**
     * Retorna los datos del recurso mediante el id entrado por parametro
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        $lectura = Suministro::join('tarifa', 'suministro.idtarifa', '=', 'tarifa.idtarifa')
                            ->join('cliente', 'suministro.documentoidentidad', '=', 'cliente.documentoidentidad')
                            ->join('calle', 'suministro.idcalle', '=', 'calle.idcalle')
                            ->join('barrio', 'calle.idbarrio', '=', 'barrio.idbarrio')
                            ->select('tarifa.nombretarifa', 'calle.nombrecalle', 'barrio.nombrebarrio',
                                        'cliente.apellido', 'cliente.nombre', 'tarifa.idtarifa', 
                                        DB::raw('(SELECT lecturaactual FROM lectura WHERE lectura.numerosuministro = suministro.numerosuministro LIMIT 1)'))
                            ->where('suministro.numerosuministro', '=', $id)
                            ->get();


        return response()->json($lectura);
    }


    /**
     * Retorna un array de los rubros existentes fijos y variables
     *
     * @return array
     */
    public function getRubros()
    {
        $rubrofijo = DB::select('SELECT * FROM rubrofijo');

        $rubrovariable = DB::select('
                                    
                                SELECT idrubrovariable AS idrubrofijo, nombrerubrovariable AS nombrerubrofijo,
                                (
                                SELECT costorubro FROM rubrosvariablescuenta, cobroagua 
                                    WHERE rubrovariable.idrubrovariable = rubrosvariablescuenta.idrubrovariable
                                    AND cobroagua.idcuenta = rubrosvariablescuenta.idcuenta
                                ) AS valorrubro
                                FROM rubrovariable


                            ');

        $result = array_merge($rubrofijo, $rubrovariable);

        return $result;
    }


    /**
     * Retorna los valores ya calculados de los rubros existentes en base a los parametros
     *
     * @param $consumo
     * @param $tarifa
     * @param $numerosuministro
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRubrosValue($consumo, $tarifa, $numerosuministro)
    {
        $tarifabasica = DB::table('costotarifa')
                                ->select(DB::raw('MAX(valorconsumo) AS valorconsumo'))
                                ->where([
                                    ['idtarifa', '=', $tarifa], ['apartirdenm3', '<', $consumo]
                                ])->get();

        $value_tarifa_excedente = DB::table('excedentetarifa')
                                ->select(DB::raw('MAX(valorconsumo) AS valorconsumo'))
                                ->where([
                                    ['idtarifa', '=', $tarifa], ['desdenm3', '<=', $consumo]
                                ])->get();

        if($tarifabasica[0]->valorconsumo == null || $tarifabasica[0]->valorconsumo == ''){
            $tarifabasica[0]->valorconsumo = 0;
        }

        if ($value_tarifa_excedente == null || $value_tarifa_excedente == 0){
            $excedente = 0;
        } else {
            $excedente = ($consumo - 15) * $value_tarifa_excedente[0]->valorconsumo;
        }

        $medioambiente = RubroFijo::find(1)->valorrubro;

        $object_alcantarillado = RubroFijo::find(2)->valorrubro;
        $alcantarillado = ($tarifabasica[0]->valorconsumo + $excedente) * $object_alcantarillado;

        $object_ddss = RubroFijo::find(3)->valorrubro;
        $ddss = ($tarifabasica[0]->valorconsumo + $excedente) * $object_ddss;


        $estaPaga = CobroAgua::where([
                                ['numerosuministro', '=', $numerosuministro],
                                ['estapagada', '=', false]
                            ])->count();

        $rubrovariable = DB::select('
                                    
                                SELECT idrubrovariable AS idrubrofijo, nombrerubrovariable AS nombrerubrofijo,
                                (
                                SELECT costorubro FROM rubrosvariablescuenta, cobroagua 
                                    WHERE rubrovariable.idrubrovariable = rubrosvariablescuenta.idrubrovariable
                                    AND cobroagua.idcuenta = rubrosvariablescuenta.idcuenta
                                    AND cobroagua.numerosuministro = ' . $numerosuministro . '
                                ) AS valorrubro
                                FROM rubrovariable


                            ');



        return response()->json([
            'tarifabasica' => $tarifabasica[0]->valorconsumo,
            'excedente' => $excedente,
            'medioambiente' => $medioambiente,
            'alcantarillado' => $alcantarillado,
            'mesesatrasados' => $estaPaga,
            'ddss' => $ddss
        ]);
    }


    /**
     * Almacena el recurso de Lectura
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //$lectura = Lectura::create($request->all());
        
        $lectura = new Lectura();
        $lectura->numerosuministro = $request->input('numerosuministro');
        $lectura->fechalectura = $request->input('fechalectura');
        $lectura->lecturaactual = $request->input('lecturaactual');
        $lectura->lecturaanterior = $request->input('lecturaanterior');
        $lectura->consumo = $request->input('consumo');

        $lectura->save();

        $cobroagua = new CobroAgua();
        $cobroagua->numerosuministro = $request->input('numerosuministro');
        $cobroagua->idlectura =  $lectura->idlectura;
        $cobroagua->valorconsumo = $request->input('consumo');

        $cobroagua->valorexcedente = $request->input('excedente');
        $cobroagua->mesesatrasados = $request->input('mesesatrasados');
        $cobroagua->total = $request->input('total');

        $cobroagua->save();


        //return ($lectura) ? response()->json(['success' => true, 'lastID' => $lectura->idlectura]) : response()->json(['success' => false]);

        return response()->json(['success' => true]);


    }

}
