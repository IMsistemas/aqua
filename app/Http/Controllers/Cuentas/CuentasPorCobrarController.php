<?php

namespace App\Http\Controllers\Cuentas;

use App\Http\Controllers\Contabilidad\CoreContabilidad;
use App\Modelos\Clientes\Cliente;
use App\Modelos\Contabilidad\Cont_DocumentoVenta;
use App\Modelos\Contabilidad\Cont_RegistroCliente;
use App\Modelos\Cuentas\CuentasporCobrar;
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



        return  Cont_DocumentoVenta::with('cont_cuentasporcobrar')
                        ->join('cliente', 'cliente.idcliente', '=', 'cont_documentoventa.idcliente')
                        ->join('persona','persona.idpersona','=','cliente.idpersona')
                        ->whereRaw("cont_documentoventa.fecharegistroventa BETWEEN '" . $filter->inicio . "' AND '"  . $filter->fin . "'")
                        ->get();
    }

    public function getCobros($id)
    {
        return CuentasporCobrar::join('cont_formapago', 'cont_formapago.idformapago', '=', 'cont_cuentasporcobrar.idformapago')
                                    ->where('iddocumentoventa', $id)->get();
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

        if ($request->input('iddocumentoventa') != 0) {
            $cuenta->iddocumentoventa = $request->input('iddocumentoventa');
        }

        if ($cuenta->save()) {
            return response()->json(['success' => true]);
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
