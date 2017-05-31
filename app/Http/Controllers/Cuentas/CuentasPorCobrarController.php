<?php

namespace App\Http\Controllers\Cuentas;

use App\Modelos\Contabilidad\Cont_DocumentoVenta;
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
        $cuenta = new CuentasporCobrar();

        $cuenta->nocomprobante = $request->input('nocomprobante');
        $cuenta->idformapago = $request->input('idformapago');
        $cuenta->valorpagado = $request->input('cobrado');
        $cuenta->fecharegistro = $request->input('fecharegistro');
        $cuenta->idplancuenta = $request->input('cuenta');

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
