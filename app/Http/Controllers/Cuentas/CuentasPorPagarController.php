<?php

namespace App\Http\Controllers\Cuentas;

use App\Http\Controllers\Contabilidad\CoreContabilidad;
use App\Modelos\Contabilidad\Cont_CuentasPorPagar;
use App\Modelos\Contabilidad\Cont_DocumentoCompra;
use App\Modelos\Contabilidad\Cont_RegistroContable;
use App\Modelos\Contabilidad\Cont_RegistroProveedor;
use App\Modelos\Proveedores\Proveedor;
use App\Modelos\SRI\SRI_Establecimiento;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CuentasPorPagarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Cuentas.cuentasxpagar');
    }

    public function getFacturas(Request $request)
    {
        $filter = json_decode($request->get('filter'));

        return Cont_DocumentoCompra::with('cont_cuentasporpagar')
                            ->join('proveedor', 'proveedor.idproveedor', '=', 'cont_documentocompra.idproveedor')
                            ->join('persona','persona.idpersona','=','proveedor.idpersona')
                            ->whereRaw("cont_documentocompra.fecharegistrocompra BETWEEN '" . $filter->inicio . "' AND '"  . $filter->fin . "'")
                            ->get();
    }

    public function getCobros($id)
    {
        return Cont_CuentasPorPagar::join('cont_formapago', 'cont_formapago.idformapago', '=', 'cont_cuentasporpagar.idformapago')
                                        ->where('iddocumentocompra', $id)->get();
    }


    /**
     * Obtener la informacion de un proveedor en especifico
     *
     * @param $idcliente
     * @return mixed
     */
    public function getInfoClienteByID($idcliente)
    {

        return Proveedor::join("persona","persona.idpersona","=","proveedor.idpersona")
                            ->join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=", "proveedor.idtipoimpuestoiva")
                            ->join("cont_plancuenta", "cont_plancuenta.idplancuenta","=","proveedor.idplancuenta")
                            ->whereRaw("proveedor.idproveedor = ".$idcliente)
                            ->limit(1)
                            ->get();
    }

    public function getLastID()
    {
        return Cont_CuentasPorPagar::max('idcuentasporpagar');
    }


    public function anular(Request $request)
    {
        $iddocumentocompra = $request->input('iddocumentocompra');

        $compra = Cont_DocumentoCompra::find($iddocumentocompra);
        $compra->estadoanulado =  true;

        if ($compra->save()) {

            CoreContabilidad::AnularAsientoContable($compra->idtransaccion);

            $result = Cont_Kardex::whereRaw('idtransaccion = ' . $compra->idtransaccion)
                ->update(['estadoanulado' => true]);

            if ($result == false) {
                return response()->json(['success' => false]);
            }

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
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
            'idproveedor' => $request->input('idproveedor'),
            'idtransaccion' => $id_transaccion,
            'fecha' => date('Y-m-d'),
            'debe' => $filtro->DataContabilidad->registro[0]->Debe, //primera posicion es cliente
            'haber' => 0,
            'numerodocumento' => "",
            'estadoanulado' => false
        ];

        $aux_registrocliente  = Cont_RegistroProveedor::create($registrocliente);

        /*
         * ----------------------------------------CONTABILIDAD-------------------------------------------------------
         */

        $cuenta = new Cont_CuentasPorPagar();

        $cuenta->nocomprobante = $request->input('nocomprobante');
        $cuenta->idformapago = $request->input('idformapago');
        $cuenta->valorpagado = $request->input('pagado');
        $cuenta->fecharegistro = $request->input('fecharegistro');
        $cuenta->idplancuenta = $request->input('cuenta');
        $cuenta->idtransaccion = $id_transaccion;
        $cuenta->descripcion = $request->input('descripcion');

        $cuenta->iddocumentocompra = $request->input('iddocumentocompra');

        if ($cuenta->save()) {

            $cuenta2 = Cont_CuentasPorPagar::find($cuenta->idcuentasporpagar);
            $cuenta2->nocomprobante = $cuenta->idcuentasporpagar;

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

    private function getPagosPrint($id)
    {

        $pago = Cont_CuentasPorPagar::selectRaw('cont_cuentasporcobrar.idcuentasporcobrar, cont_cuentasporcobrar.valorpagado,
                                        cont_cuentasporcobrar.fecharegistro, cont_cuentasporcobrar.descripcion, 
                                        cont_cuentasporcobrar.idtransaccion, persona.razonsocial')
                            ->join('cont_documentocompra', 'cont_documentocompra.iddocumentocompra', '=', 'cont_cuentasporcobrar.iddocumentocompra')
                            ->join('cliente', 'cliente.idcliente', '=', 'cont_documentocompra.idcliente')
                            ->join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
                            ->where('idcuentasporcobrar', $id)->get();


        $registro = Cont_RegistroContable::join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cont_registrocontable.idplancuenta')
            ->selectRaw('cont_registrocontable.idtransaccion, 
                                        cont_registrocontable.idplancuenta,cont_registrocontable.debe, cont_registrocontable.haber, 
                                        cont_registrocontable.descripcion,cont_plancuenta.jerarquia, cont_plancuenta.concepto')
            ->where('cont_registrocontable.idtransaccion', $pago[0]->idtransaccion)
            ->orderBy('cont_registrocontable.debe', 'desc')->get();

        return [$pago, $registro];
    }

    public function printComprobanteEgreso($params)
    {
        ini_set('max_execution_time', 300);

        $aux_empresa = SRI_Establecimiento::all();

        $result = $this->getPagosPrint($params);

        $cobro = $result[0];
        $registro = $result[1];

        $today = date("Y-m-d H:i:s");

        $view =  \View::make('Cuentas.comprobanteEgresoPrint', compact('today', 'cobro', 'registro', 'aux_empresa'))->render();

        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadHTML($view);

        return $pdf->stream('comprobEgreso_' . $today);
    }
}
