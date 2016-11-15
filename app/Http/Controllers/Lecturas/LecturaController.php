<?php

namespace App\Http\Controllers\Lecturas;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Lecturas\Lectura;
use App\Modelos\Servicios\ServicioAguaPotable;
use App\Modelos\Servicios\ServicioJunta;
use App\Modelos\Tarifas\CostoTarifa;
use App\Modelos\Tarifas\ExcedenteTarifa;
use App\Modelos\Suministros\Suministro;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
     * Obtener la informacion
     *
     * @param $filter
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo($filter)
    {
        $filter = json_decode($filter);

        $count = Lectura::where('numerosuministro', $filter->id)
                            ->whereRaw('EXTRACT( MONTH FROM fechalectura) =' . $filter->month)
                            ->whereRaw('EXTRACT( YEAR FROM fechalectura) =' . $filter->year)
                            ->count();

        if ($count == 0) {
            $suministro = Suministro::with('cliente', 'aguapotable', 'calle.barrio')
                                        ->where('suministro.numerosuministro', $filter->id)
                                        ->get();
            $lectura = Lectura::where('numerosuministro', $filter->id)
                                ->orderBy('idlectura', 'desc')
                                ->take(1)
                                ->get();
            $result_array = ['success' => true, 'suministro' => $suministro, 'lectura' => $lectura];
        } else {
            $result_array = ['success' => false];
        }

        return response()->json($result_array);
    }

    /**
     * Calcular el valor de la Tarifa Basica
     *
     * @param $consumo
     * @param $tarifa
     * @return mixed
     */
    private function calculateTarifaBasica($consumo, $tarifa)
    {
        //-------------------Valor Consumo: Tarifa Basica----------------------------------------------

        $tarifa = CostoTarifa::where('apartirdenm3', '<=', $consumo)
                                ->where('idtarifaaguapotable', $tarifa)
                                ->orderBy('apartirdenm3', 'desc')
                                ->take(1)
                                ->get();

        $value = $tarifa[0]->valortarifa;

        settype($value, 'float');

        return $value;
    }

    /**
     * Calcular el Excedente
     *
     * @param $consumo
     * @param $tarifa
     * @return int
     */
    private function calculateExcedente($consumo, $tarifa)
    {
        //-------------------Excedente: 0 || (consumo - 15) * % ----------------------------------------

        $excedente = ExcedenteTarifa::where('desdenm3', '<=', $consumo)
                                    ->where('idtarifaaguapotable', $tarifa)
                                    ->orderBy('desdenm3', 'desc')
                                    ->take(1)
                                    ->get();

        if (count($excedente) == 0) {
            $value = 0;
        } else {
            $value = ($consumo - 15) * $excedente[0]->valorexcedente;
        }

        return $value;
    }

    /**
     * Calcular valor total y cantidad de meses atrasados
     *
     * @param $numerosuministro
     * @return array
     */
    private function calculateMonthAtrasados($numerosuministro)
    {
        //-------------------Valores Atrasados--------------------------------------------------------

        $atraso = CobroAgua::where('estapagado', false)
                                ->whereRaw('mesesatrasados IS NOT NULL')
                                ->whereRaw('valormesesatrasados IS NOT NULL')
                                ->where('numerosuministro', $numerosuministro)
                                ->whereRaw('EXTRACT( MONTH FROM fecha) = (EXTRACT( MONTH FROM now()) - 1)')
                                ->get();

        if (count($atraso) == 0){
            $valormesesatrasados = 0;
            $mesesatrasados = 0;
        } else {
            if ($atraso[0]->mesesatrasados == 0){
                $mesesatrasados = 1;
            } else {
                $mesesatrasados = $atraso[0]->mesesatrasados + 1;
            }
            $valormesesatrasados = $atraso[0]->valor;
            settype($valormesesatrasados, 'float');
        }

        return ['cant_meses_atrasados' => $mesesatrasados, 'valor_meses_atrasados' => $valormesesatrasados];
    }

    /**
     * Calcular los valores de cada servicio de junta insertados
     *
     * @param $idtarifa
     * @param $valueTarifa
     * @param $valueExcedente
     * @return array
     */
    private function calculateServiciosJunta($idtarifa, $valueTarifa, $valueExcedente)
    {
        $servicios_junta = ServicioJunta::all();

        $array_servicios = [];

        foreach ($servicios_junta as $servicio) {
            $object = ServicioAguaPotable::where('idtarifaaguapotable', $idtarifa)
                                            ->where('idserviciojunta', $servicio->idserviciojunta)
                                            ->get();
            if ($object[0]->esporcentaje == true) {
                $value = ($valueTarifa + $valueExcedente) * $object[0]->valor;
            } else {
                $value = $object[0]->valor;
            }
            settype($value, 'float');
            $array_servicios[] = ['nombreservicio' => $servicio->nombreservicio, 'valor' => $value];
        }

        return $array_servicios;
    }

    /**
     * Calculo general de la lectura
     *
     * @param $consumo
     * @param $tarifa
     * @param $numerosuministro
     * @return array
     */
    public function calculate($consumo, $tarifa, $numerosuministro)
    {
        $tarifa_basica = $this->calculateTarifaBasica($consumo, $tarifa);
        $excedente = $this->calculateExcedente($consumo, $tarifa);
        $meses_atrasados = $this->calculateMonthAtrasados($numerosuministro);
        $servicios = $this->calculateServiciosJunta($tarifa, $tarifa_basica, $excedente);

        $array_tarifabasica = ['nombreservicio' => 'Consumo Tarifa Básica', 'valor' => $tarifa_basica];
        $array_excedente = ['nombreservicio' => 'Excedente', 'valor' => $excedente];
        $array_valoratrasado = ['nombreservicio' => 'Valores Atrasados', 'valor' => $meses_atrasados['valor_meses_atrasados']];

        $value_return = [$array_tarifabasica, $array_excedente, $array_valoratrasado];

        foreach ($servicios as $servicio) {
            $value_return[] = $servicio;
        }

        return [
            'value_tarifas' => $value_return, 'cant_meses_atrasados' => $meses_atrasados['cant_meses_atrasados'],
            'excedente' => $excedente, 'valor_meses_atrasados' => $meses_atrasados['valor_meses_atrasados']
        ];
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
        $lectura->excedente = $request->input('excedente');
        $lectura->consumo = $request->input('consumo');
        $lectura->save();

        $cobroagua = CobroAgua::where('numerosuministro', $request->input('numerosuministro'))
                                ->whereRaw('EXTRACT( MONTH FROM fecha) = ' . $request->input('mes'))
                                ->whereRaw('EXTRACT( YEAR FROM fecha) = ' . $request->input('anno'))
                                ->get()->first();

        $cobroagua->idlectura =  $lectura->idlectura;
        $cobroagua->valorexcedente = $request->input('excedente');
        $cobroagua->mesesatrasados = $request->input('mesesatrasados');
        $cobroagua->valormesesatrasados = $request->input('valormesesatrasados');
        $cobroagua->valor = $request->input('total');
        $cobroagua->estapagado = false;
        $cobroagua->save();

        $cliente = Cliente::join('suministro', 'suministro.codigocliente', '=', 'cliente.codigocliente')
                            ->select('cliente.correo', 'cliente.nombres', 'cliente.apellidos')
                            ->where('suministro.numerosuministro', '=', $request->input('numerosuministro'))
                            ->get();


        if ($cliente[0]->correo != '' && $cliente[0]->correo != null) {
            $correo_cliente = $cliente[0]->correo;
            $nombre_cliente = $cliente[0]->apellido . ' ' . $cliente[0]->nombre;

            $data = json_decode($request->input('pdf'));
            $data1 = [];

            $view = \View::make('Lecturas.pdf_body_email_newLectura', compact('data1', 'data'))->render();

            $curl = curl_init('https://aguapotable.ip-zone.com/ccm/admin/api/version/2/&type=json');

            $rcpt = [
                [ 'name' => $nombre_cliente, 'email' => $correo_cliente ],
                [ 'name' => 'Luis Antonio Vinueza', 'email' => 'lvinueza@imnegocios.com' ],
                [ 'name' => 'Kevin Chicaiza', 'email' => 'kchicaiza@imnegocios.com' ],
                [ 'name' => 'Christian Rios', 'email' => 'crios@imnegocios.com' ]
            ];

            $postData = [
                'function' => 'sendMail',
                'apiKey' => 'uMntDiD5ZNFl8uBxa5Gl2GOkiuAlbL5LYj4bI7Xh',
                'subject' => 'Factura Agua - Prueba de Modulo Lectura - Correo Generado desde Modulo',
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
