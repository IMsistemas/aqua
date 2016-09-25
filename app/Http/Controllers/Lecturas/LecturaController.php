<?php

namespace App\Http\Controllers\Lecturas;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Cuentas\RubroFijo;
use App\Modelos\Cuentas\RubroVariable;
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

        foreach ($request->input('rubros') as $rubro) {
            if ($rubro['type'] == 'rubrofijo') {
                $cobroagua->rubrosfijos()->attach($rubro['id'],['costorubro' => $rubro['valorrubro']]);
            }
        }

        $cliente = Cliente::join('suministro', 'suministro.documentoidentidad', '=', 'cliente.documentoidentidad')
                            ->select('cliente.correo', 'cliente.nombre', 'cliente.apellido')
                            ->where('suministro.numerosuministro', '=', $request->input('numerosuministro'))
                            ->get();

        $correo_cliente = $cliente[0]->correo;
        $nombre_cliente = $cliente[0]->apellido . ' ' . $cliente[0]->nombre;

        $correo_cliente = 'raidelbg84@gmail.com';
        $nombre_cliente = 'Raidel Berrillo Gonzalez';

        $data = json_decode($request->input('pdf'));
        $data1 = [];

        $view = \View::make('Lecturas.pdf_body_email_newLectura', compact('data1', 'data'))->render();

        $curl = curl_init('https://aguapotable.ip-zone.com/ccm/admin/api/version/2/&type=json');

        $rcpt = [
            [ 'name' => $nombre_cliente, 'email' => $correo_cliente ]
        ];

        $postData = [
            'function' => 'sendMail',
            'apiKey' => 'uMntDiD5ZNFl8uBxa5Gl2GOkiuAlbL5LYj4bI7Xh',
            'subject' => 'Factura Agua',
            'html' => $view,
            'mailboxFromId' => 1,
            'mailboxReplyId' => 1,
            'mailboxReportId' => 1,
            'packageId' => 6,
            'emails' => $rcpt,
        ];

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
