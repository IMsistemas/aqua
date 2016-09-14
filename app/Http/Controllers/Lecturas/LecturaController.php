<?php

namespace App\Http\Controllers\Lecturas;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Cuentas\RubroFijo;
use App\Modelos\Lecturas\Lectura;
use App\Modelos\Tarifas\CostoTarifa;
use App\Modelos\Tarifas\ExcedenteTarifa;
use App\Modelos\Suministros\Suministro;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
     * Almacena el recurso de Lectura y envia correo adjuntando la misma en formato pdf
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $lectura = new Lectura();
        $lectura->numerosuministro = $request->input('numerosuministro');
        $lectura->fechalectura = $request->input('fechalectura');
        $lectura->lecturaactual = $request->input('lecturaactual');
        $lectura->lecturaanterior = $request->input('lecturaanterior');
        $lectura->consumo = $request->input('consumo');

        $lectura->save();

        $cobroagua = CobroAgua::where('numerosuministro', '=', $request->input('numerosuministro'))
                                ->whereRaw('EXTRACT( MONTH FROM fechaperiodo) = ' . $request->input('mes'))
                                ->whereRaw('EXTRACT( YEAR FROM fechaperiodo) = ' . $request->input('anno'))
                                ->get()->first();

        $cobroagua->idlectura =  $lectura->idlectura;
        $cobroagua->valorconsumo = $request->input('consumo');

        $cobroagua->valorexcedente = $request->input('excedente');
        $cobroagua->mesesatrasados = $request->input('mesesatrasados');
        $cobroagua->total = $request->input('total');

        $cobroagua->save();

        $cliente = Cliente::join('suministro', 'suministro.documentoidentidad', '=', 'cliente.documentoidentidad')
                            ->select('cliente.correo')
                            ->where('suministro.numerosuministro', '=', $request->input('numerosuministro'))
                            ->get();

        $correo_cliente = $cliente[0]->correo;
        $correo_cliente = 'raidelbg84@gmail.com';

        $data = json_decode($request->input('pdf'));
        $data1 = [];

        $view = \View::make('Lecturas.pdf_email_newLectura', compact('data1', 'data'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view)->save(storage_path('app/public') . '/myfile.pdf');

        Mail::send('Lecturas.email',['correo_cliente' => $correo_cliente] , function($message) use ($correo_cliente)
        {

            $message->from('raidelbg84@gmail.com', 'Junta Administradora de Agua Potable y Alcantarillado Parroquia Ayora');

            $message->to($correo_cliente)->subject('Factura Lectura!');

            $message->attach(storage_path('app/public') . '/myfile.pdf');

        });

        return response()->json(['success' => true]);
    }


    /**
     * Exportar la nueva Lectura a PDF
     *
     * @param $data
     * @return mixed
     */
    public function exportToPDF($data)
    {
        $data = json_decode($data);
        $data1 = [];

        $view = \View::make('Lecturas.pdf_newLectura', compact('data1', 'data'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream('test.pdf');
    }

}
