<?php

namespace App\Http\Controllers\Cuentas;

use App\Http\Controllers\Contabilidad\CoreContabilidad;
use App\Modelos\Contabilidad\Cont_CuentasPorPagar;
use App\Modelos\Contabilidad\Cont_DocumentoCompra;
use App\Modelos\Contabilidad\Cont_RegistroProveedor;
use App\Modelos\Proveedores\Proveedor;
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
}
