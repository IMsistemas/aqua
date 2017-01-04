<?php

namespace App\Http\Controllers\Lecturas;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Facturas\Factura;
use App\Modelos\Lecturas\Lectura;
use App\Modelos\Servicios\ServicioAguaPotable;
use App\Modelos\Servicios\ServicioJunta;
use App\Modelos\Servicios\ServiciosEnFactura;
use App\Modelos\Tarifas\CostoTarifa;
use App\Modelos\Tarifas\ExcedenteTarifa;
use App\Modelos\Suministros\Suministro;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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
     * Obtener la informacion
     *
     * @param $filter
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo($filter)
    {
        $filter = json_decode($filter);

        $count = CobroAgua::where('numerosuministro', $filter->id)
                            ->whereRaw('EXTRACT( MONTH FROM fecha) =' . $filter->month)
                            ->whereRaw('EXTRACT( YEAR FROM fecha) =' . $filter->year)
                            ->get();

        if (count($count) == 0) {
            $result_array = ['success' => false, 'flag' => 'no_exists'];
        } else {
            if ($count[0]->idlectura == null) {
                $suministro = Suministro::with('cliente', 'aguapotable', 'calle.barrio')
                    ->where('suministro.numerosuministro', $filter->id)
                    ->get();
                $lectura = Lectura::where('numerosuministro', $filter->id)
                    ->orderBy('idlectura', 'desc')
                    ->take(1)
                    ->get();
                $result_array = ['success' => true, 'suministro' => $suministro, 'lectura' => $lectura];
            } else {
                $result_array = ['success' => false, 'flag' => 'exists'];
            }
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

        $atraso = CobroAgua::with([
                                    'factura' => function ($query) {
                                        $query->where('estapagada', false);
                                    }
                                ])
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
            $valormesesatrasados = $atraso[0]['factura']->totalfactura;
            settype($valormesesatrasados, 'float');
        }

        return ['cant_meses_atrasados' => $mesesatrasados, 'valor_meses_atrasados' => $valormesesatrasados, 'id' => 0];
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
            $array_servicios[] = ['nombreservicio' => $servicio->nombreservicio, 'valor' => $value, 'id' => $object[0]->idserviciojunta];
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

        $array_tarifabasica = ['nombreservicio' => 'Consumo Tarifa Básica', 'valor' => $tarifa_basica, 'id' => 0];
        $array_excedente = ['nombreservicio' => 'Excedente', 'valor' => $excedente, 'id' => 0];
        $array_valoratrasado = ['nombreservicio' => 'Valores Atrasados', 'valor' => $meses_atrasados['valor_meses_atrasados'], 'id' => 0];

        $value_return = [$array_tarifabasica, $array_excedente, $array_valoratrasado];

        foreach ($servicios as $servicio) {
            $value_return[] = $servicio;
        }

        return [
            'value_tarifas' => $value_return, 'cant_meses_atrasados' => $meses_atrasados['cant_meses_atrasados'],
            'excedente' => $excedente, 'valor_meses_atrasados' => $meses_atrasados['valor_meses_atrasados'],
            'tarifa_basica' => $tarifa_basica
        ];
    }

    /**
     * Almacena el recurso de Lectura y envia correo
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
        $cobroagua->valortarifabasica = $request->input('tarifa_basica');
        $cobroagua->estapagado = false;
        $cobroagua->save();

        $factura = Factura::find($cobroagua->idfactura);
        $factura->totalfactura = $request->input('total');
        $factura->save();

        $servicios = $request->input('rubros');

        foreach ($servicios as $item) {
            if ($item['id'] != 0) {
                $serviciofactura = new ServiciosEnFactura();
                $serviciofactura->idserviciojunta = $item['id'];
                $serviciofactura->idfactura = $cobroagua->idfactura;
                $serviciofactura->valor = $item['valor'];
                $serviciofactura->save();
            }
        }

        $cliente = Cliente::join('suministro', 'suministro.codigocliente', '=', 'cliente.codigocliente')
                            ->select('cliente.correo', 'cliente.nombres', 'cliente.apellidos')
                            ->where('suministro.numerosuministro', '=', $request->input('numerosuministro'))
                            ->get();

        if ($cliente[0]->correo != '' && $cliente[0]->correo != null) {
            $correo_cliente = $cliente[0]->correo;
            $data = json_decode($request->input('pdf'));

            Mail::send('Lecturas.pdf_body_email_newLectura',['data' => $data] , function($message) use ($correo_cliente)
            {
                $message->from('notificaciones@aqua.ec', 'Junta Administradora de Agua Potable y Alcantarillado Parroquia Ayora');

                $message->to($correo_cliente);
                $message->to('raidelbg84@gmail.com')->subject('Prefactura Lectura!');

                /*$message->bcc('christian.imnegocios@gmail.com');
                $message->bcc('kevin.imnegocios@gmail.com');
                $message->bcc('raidelbg84@gmail.com');*/
                //$message->bcc('luis.imnegocios@gmail.com')->subject('Prefactura Lectura!');
            });
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
