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
        $suministro = Suministro::with('cliente', 'tarifa', 'calle.barrio')
                                    ->where('suministro.numerosuministro', $id)
                                    ->get();

        $lectura = Lectura::where('numerosuministro', $id)
                            ->orderBy('idlectura', 'desc')
                            ->take(1)
                            ->get();

        return response()->json(['suministro' => $suministro, 'lectura' => $lectura]);
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


        $tarifabasica = ['nombrerubro' => 'Consumo Tarifa Básica', 'valorrubro' => '0.00'];
        $excedente = ['nombrerubro' => 'Excedente', 'valorrubro' => '0.00'];
        $mesesatrasados = ['nombrerubro' => 'Valores Atrasados', 'valorrubro' => '0.00'];

        $return = [$tarifabasica, $excedente, $mesesatrasados];

        foreach ( $result as $item){
            $return[] = ['nombrerubro' => $item->nombrerubrofijo, 'valorrubro' => '0.00'];
        }

        //return $result;
        return $return;
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

        $rubros = $this->getRubros();


        $cuenta = CobroAgua::where('numerosuministro', $numerosuministro)
                            ->whereRaw('EXTRACT( MONTH FROM fechaperiodo) = EXTRACT( MONTH FROM now())')
                            ->get();



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

        //-------------------------------------------------------------------------------------------------------------

        $rubrofijo = DB::select('SELECT * FROM rubrofijo');

        //$rubrofijo = DB::select('SELECT rubrosfijoscuenta.costorubro  FROM rubrosfijoscuenta INNER JOIN rubrofijo ON rubrosfijoscuenta.idrubrofijo = rubrofijo.idrubrofijo
          //                        WHERE rubrosfijoscuenta.idcuenta = ' + $cuenta->idcuenta);

        $longitud = count($rubrofijo);

        if ($longitud > 0){
            for ($i = 0; $i < $longitud; $i++){
                if($rubrofijo[$i]->idrubrofijo == 1){

                    if ($rubrofijo[$i]->costorubro != null && $rubrofijo[$i]->costorubro != ''){
                        $rubros[$i + 3]['valorrubro'] = $rubrofijo[$i]->costorubro;
                    }

                } else {

                    if ($rubrofijo[$i]->costorubro != null && $rubrofijo[$i]->costorubro != ''){
                        $value = ($tarifabasica[0]->valorconsumo + $excedente) * $rubrofijo[$i]->costorubro;
                        $rubros[$i + 3]['valorrubro'] = $value;
                    }

                }
            }
        }

        //-------------------------------------------------------------------------------------------------------------

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

        $longitud_variable = count($rubrovariable);

        if ($longitud_variable > 0){
            for ($i = 0; $i < $longitud_variable; $i++){

                if ($rubrovariable[$i]->valorrubro != null && $rubrovariable[$i]->valorrubro != ''){
                    $rubros[$i + ($longitud + 3)]['valorrubro'] = $rubrovariable[$i]->valorrubro;
                }

            }
        }

        $rubros[0]['valorrubro'] = $tarifabasica[0]->valorconsumo;
        $rubros[1]['valorrubro'] = $excedente;
        $rubros[2]['valorrubro'] = $valormesesatrasados;

        return response()->json([
            $rubros,
            [
                'mesesatrasados' => $mesesatrasados,
            ]
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
        $cobroagua->consumom3 = $request->input('consumo');
        $cobroagua->valorconsumo = $request->input('valorconsumo');
        $cobroagua->valorexcedente = $request->input('excedente');
        $cobroagua->mesesatrasados = $request->input('mesesatrasados');
        $cobroagua->valormesesatrasados = $request->input('valormesesatrasados');
        $cobroagua->total = $request->input('total');
        $cobroagua->estapagada = false;

        $cobroagua->save();


        $cliente = Cliente::join('suministro', 'suministro.documentoidentidad', '=', 'cliente.documentoidentidad')
                            ->select('cliente.correo', 'cliente.nombre', 'cliente.apellido')
                            ->where('suministro.numerosuministro', '=', $request->input('numerosuministro'))
                            ->get();

        $correo_cliente = $cliente[0]->correo;
        $nombre_cliente = $cliente[0]->apellido . ' ' . $cliente[0]->nombre;

        $correo_cliente = 'raidelbg84@gmail.com';
        $nombre_cliente = 'Berrillo Gonzalez Raidel';

        /*$correo_cliente = 'raidelbg84@gmail.com';

        $data = json_decode($request->input('pdf'));
        $data1 = [];

        $view = \View::make('Lecturas.pdf_email_newLectura', compact('data1', 'data'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view)->save(storage_path('app/public') . '/myfile.pdf');

        Mail::send('Lecturas.email',['correo_cliente' => $correo_cliente] , function($message) use ($correo_cliente)
        {

            $message->from('aguapotable.ip-zone.com', 'Junta Administradora de Agua Potable y Alcantarillado Parroquia Ayora');

            $message->to($correo_cliente)->subject('Factura Lectura!');

            $message->attach(storage_path('app/public') . '/myfile.pdf');

        });*/

        $data = json_decode($request->input('pdf'));
        $data1 = [];

        $view = \View::make('Lecturas.pdf_body_email_newLectura', compact('data1', 'data'))->render();

        $curl = curl_init('https://aguapotable.ip-zone.com/ccm/admin/api/version/2/&type=json');

        $rcpt = array(
            array(
                'name' => $nombre_cliente,
                'email' => $correo_cliente
            )
        );

        $postData = array(
            'function' => 'sendMail',
            'apiKey' => 'uMntDiD5ZNFl8uBxa5Gl2GOkiuAlbL5LYj4bI7Xh',
            'subject' => 'Factura Agua',
            'html' => $view,
            'mailboxFromId' => 1,
            'mailboxReplyId' => 1,
            'mailboxReportId' => 1,
            'packageId' => 6,
            'emails' => $rcpt,
        );

        $post = http_build_query($postData);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $json = curl_exec($curl);
        if ($json === false) {
            die('Request failed with error: '. curl_error($curl));
        }

        $result = json_decode($json);
        if ($result->status == 0) {
            die('Bad status returned. Error: '. $result->error);
        }

        return response()->json(['success' => true]);
    }


    /**
     * Exportar la nueva Lectura a PDF
     *
     * @param $data
     * @return mixed
     */
    public function exportToPDF($type, $data)
    {
        $data = json_decode($data);
        $data1 = [];

        ($type == 1) ? $plantilla = 'Lecturas.pdf_newLectura' : $plantilla = 'Lecturas.pdf_email_newLectura';

        $view = \View::make($plantilla, compact('data1', 'data'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream('test.pdf');
    }

}
