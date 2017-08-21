<?php

namespace App\Http\Controllers\Cuentas;

use App\Http\Controllers\Contabilidad\CoreContabilidad;
use App\Modelos\Clientes\Cliente;
use App\Modelos\Contabilidad\Cont_DocumentoVenta;
use App\Modelos\Contabilidad\Cont_RegistroCliente;
use App\Modelos\Contabilidad\Cont_RegistroContable;
use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Cuentas\CobroServicio;
use App\Modelos\Cuentas\CuentasporCobrar;
use App\Modelos\SRI\SRI_Establecimiento;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CuentasPorCobrarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Cuentas.cuentasxcobrar');
    }

    public function getFacturas(Request $request)
    {
        $filter = json_decode($request->get('filter'));

        $factura = Cont_DocumentoVenta::with('cont_cuentasporcobrar')
                                        ->join('cliente', 'cliente.idcliente', '=', 'cont_documentoventa.idcliente')
                                        ->join('persona','persona.idpersona','=','cliente.idpersona')
                                        ->whereRaw("cont_documentoventa.fecharegistroventa BETWEEN '" . $filter->inicio . "' AND '"  . $filter->fin . "'")
                                        ->get();

        $cobroservicio = CobroServicio::with('cont_cuentasporcobrar')
                                        ->join('solicitudservicio', 'solicitudservicio.idsolicitudservicio', '=', 'cobroservicio.idsolicitudservicio')
                                        ->join('solicitud', 'solicitudservicio.idsolicitud', '=', 'solicitud.idsolicitud')
                                        ->join('cliente', 'cliente.idcliente', '=', 'solicitud.idcliente')
                                        ->join('persona', 'cliente.idpersona', '=', 'persona.idpersona')
                                        ->whereRaw("cobroservicio.fechacobro BETWEEN '" . $filter->inicio . "' AND '"  . $filter->fin . "'")
                                        ->orderBy('fechacobro', 'desc')->get();

        $cobroagua_lectura = CobroAgua::with('cont_cuentasporcobrar')
                                        ->join('suministro', 'suministro.idsuministro', '=', 'cobroagua.idsuministro')
                                        ->join('cliente', 'cliente.idcliente', '=', 'suministro.idcliente')
                                        ->join('persona', 'cliente.idpersona', '=', 'persona.idpersona')
                                        ->whereRaw("cobroagua.fechacobro BETWEEN '" . $filter->inicio . "' AND '"  . $filter->fin . "'")
                                        ->whereRaw("cobroagua.total IS NOT NULL")
                                        ->orderBy('fechacobro', 'desc')->get();

        $result = [];

        foreach ($factura as $item) {
            $result[] = $item;
        }

        foreach ($cobroagua_lectura as $item0) {
            $result[] = $item0;
        }

        foreach ($cobroservicio as $item1) {
            $result[] = $item1;
        }

        return $result;
    }

    public function getCobros($id)
    {
        return CuentasporCobrar::join('cont_formapago', 'cont_formapago.idformapago', '=', 'cont_cuentasporcobrar.idformapago')
                                    ->where('iddocumentoventa', $id)->get();
    }

    public function getCobrosServices($id)
    {
        return CuentasporCobrar::join('cont_formapago', 'cont_formapago.idformapago', '=', 'cont_cuentasporcobrar.idformapago')
                                    ->where('idcobroservicio', $id)->get();
    }

    public function getCobrosLecturas($id)
    {
        return CuentasporCobrar::join('cont_formapago', 'cont_formapago.idformapago', '=', 'cont_cuentasporcobrar.idformapago')
                                    ->where('idcobroagua', $id)->get();
    }

    public function getLastID()
    {
        return CuentasporCobrar::max('idcuentasporcobrar');
    }

    public function anular(Request $request)
    {
        $idcuentasporcobrar = $request->input('idcuentasporcobrar');

        $cuenta = CuentasporCobrar::find($idcuentasporcobrar);
        $cuenta->estadoanulado =  true;

        if ($cuenta->save()) {

            CoreContabilidad::AnularAsientoContable($cuenta->idtransaccion);

            return response()->json(['success' => true]);

        } else {

            return response()->json(['success' => false]);

        }
    }

    /**
     * Obtener la informacion de un cliente en especifico
     *
     * @param $idcliente
     * @return mixed
     */
    public function getInfoClienteByID($idcliente)
    {

        return Cliente::join("persona","persona.idpersona","=","cliente.idpersona")
            ->join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=", "cliente.idtipoimpuestoiva")
            ->join("cont_plancuenta", "cont_plancuenta.idplancuenta","=","cliente.idplancuenta")
            ->whereRaw("cliente.idcliente = ".$idcliente)
            ->limit(1)
            ->get();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*
         * ----------------------------------------CONTABILIDAD-------------------------------------------------------
         */

        $filtro = json_decode($request->input('contabilidad'));

        //--Parte contable
        $id_transaccion = CoreContabilidad::SaveAsientoContable( $filtro->DataContabilidad);
        //--Fin parte contable

        $registrocliente = [
            'idcliente' => $request->input('idcliente'),
            'idtransaccion' => $id_transaccion,
            'fecha' => date('Y-m-d'),
            'debe' => $filtro->DataContabilidad->registro[0]->Debe, //primera posicion es cliente
            'haber' => 0,
            'numerodocumento' => "",
            'estadoanulado' => false
        ];

        $aux_registrocliente  = Cont_RegistroCliente::create($registrocliente);

        /*
         * ----------------------------------------CONTABILIDAD-------------------------------------------------------
         */

        $cuenta = new CuentasporCobrar();

        $cuenta->nocomprobante = $request->input('nocomprobante');
        $cuenta->idformapago = $request->input('idformapago');
        $cuenta->valorpagado = $request->input('cobrado');
        $cuenta->fecharegistro = $request->input('fecharegistro');
        $cuenta->idplancuenta = $request->input('cuenta');
        $cuenta->idtransaccion = $id_transaccion;
        $cuenta->descripcion = $request->input('descripcion');

        if ($request->input('type') == 'venta') {
            if ($request->input('iddocumentoventa') != 0) {
                $cuenta->iddocumentoventa = $request->input('iddocumentoventa');
            }
        } else if ($request->input('type') == 'servicio') {
            $cuenta->idcobroservicio = $request->input('iddocumentoventa');
        } else {
            $cuenta->idcobroagua = $request->input('iddocumentoventa');
        }

        $cuenta->estadoanulado = false;

        if ($cuenta->save()) {

            $cuenta2 = CuentasporCobrar::find($cuenta->idcuentasporcobrar);
            $cuenta2->nocomprobante = $cuenta->idcuentasporcobrar;

            if ($cuenta2->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }


        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getCobroPrint($id)
    {
        $cobro = CuentasporCobrar::where('idcuentasporcobrar', $id)->get();

        $cobro1 = CuentasporCobrar::selectRaw('cont_cuentasporcobrar.idcuentasporcobrar, cont_cuentasporcobrar.valorpagado,
                                        cont_cuentasporcobrar.fecharegistro, cont_cuentasporcobrar.descripcion, 
                                        cont_cuentasporcobrar.idtransaccion, persona.razonsocial');

        if ($cobro[0]->idcobroagua != null) {

            $cobro1 = $cobro1->join('cobroagua', 'cobroagua.idcobroagua', '=', 'cont_cuentasporcobrar.idcobroagua')
                            ->join('suministro', 'suministro.idcliente', '=', 'cobroagua.idsuministro')
                            ->join('cliente', 'cliente.idcliente', '=', 'suministro.idcliente');

        } elseif ($cobro[0]->idcobroservicio != null) {

            $cobro1 = $cobro1->join('cobroservicio', 'cobroservicio.idcobroservicio', '=', 'cont_cuentasporcobrar.idcobroservicio')
                            ->join('cliente', 'cliente.idcliente', '=', 'cobroservicio.idcliente');

        } else {

            $cobro1 = $cobro1->join('cont_documentoventa', 'cont_documentoventa.iddocumentoventa', '=', 'cont_cuentasporcobrar.iddocumentoventa')
                            ->join('cliente', 'cliente.idcliente', '=', 'cont_documentoventa.idcliente');

        }

        $resultCobro = $cobro1->join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
                                ->where('idcuentasporcobrar', $id)->get();



        $registro = Cont_RegistroContable::join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cont_registrocontable.idplancuenta')
                                ->selectRaw('cont_registrocontable.idtransaccion, 
                                        cont_registrocontable.idplancuenta,cont_registrocontable.debe, cont_registrocontable.haber, 
                                        cont_registrocontable.descripcion,cont_plancuenta.jerarquia, cont_plancuenta.concepto')
                                ->where('cont_registrocontable.idtransaccion', $resultCobro[0]->idtransaccion)
                                ->orderBy('cont_registrocontable.debe', 'desc')->get();

        return [$resultCobro, $registro];
    }

    public function printComprobanteIngreso($params)
    {
        ini_set('max_execution_time', 300);

        $aux_empresa = SRI_Establecimiento::all();

        $result = $this->getCobroPrint($params);

        $cobro = $result[0];
        $registro = $result[1];

        $today = date("Y-m-d H:i:s");

        $view =  \View::make('Cuentas.comprobanteIngresoPrint', compact('today', 'cobro', 'registro', 'aux_empresa'))->render();

        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadHTML($view);

        return $pdf->stream('comprobIngreso_' . $today);
    }
}
