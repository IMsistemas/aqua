<?php

namespace App\Http\Controllers\Reportes;

use App\Modelos\Contabilidad\Cont_RegistroContable;
use App\Modelos\SRI\SRI_Establecimiento;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReporteVentaBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reportes.reporteVentaBalance');
    }

    public function getVentasBalance(Request $request)
    {

        $filter = json_decode($request->get('filter'));

        /*return  Cont_DocumentoVenta::join('cliente', 'cliente.idcliente', '=', 'cont_documentoventa.idcliente')
            ->join('persona','persona.idpersona','=','cliente.idpersona')
            ->select('persona.razonsocial', 'cont_documentoventa.*')
            ->whereRaw("cont_documentoventa.fecharegistroventa BETWEEN '" . $filter->inicio . "' AND '"  . $filter->fin . "'")
            ->get();*/

        return Cont_RegistroContable::join('cont_transaccion', 'cont_registrocontable.idtransaccion', '=', 'cont_transaccion.idtransaccion')
                                        ->join('cont_tipotransaccion', 'cont_tipotransaccion.idtipotransaccion', '=', 'cont_transaccion.idtipotransaccion')
                                        ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cont_registrocontable.idplancuenta')
                                        ->whereRaw("cont_registrocontable.fecha BETWEEN '" . $filter->inicio . "' AND '"  . $filter->fin . "'")
                                        ->whereRaw('cont_transaccion.idtipotransaccion = 6')
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
        //
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

    public function getVentasBalancePrint($parametro)
    {

        $filter = json_decode($parametro);

        return Cont_RegistroContable::join('cont_transaccion', 'cont_registrocontable.idtransaccion', '=', 'cont_transaccion.idtransaccion')
            ->join('cont_tipotransaccion', 'cont_tipotransaccion.idtipotransaccion', '=', 'cont_transaccion.idtipotransaccion')
            ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cont_registrocontable.idplancuenta')
            ->whereRaw("cont_registrocontable.fecha BETWEEN '" . $filter->FechaI . "' AND '"  . $filter->FechaF . "'")
            ->whereRaw('cont_transaccion.idtipotransaccion = 6')
            ->get();
    }

    public function reporte_print($parametro)
    {
        ini_set('max_execution_time', 300);

        $filtro = json_decode($parametro);

        $aux_empresa = SRI_Establecimiento::all();
        $comprobacion = $this->getVentasBalancePrint($parametro);
        $today = date("Y-m-d H:i:s");

        $view =  \View::make('reportes.reporteVentaBalancePrint', compact('filtro','comprobacion','today','aux_empresa'))->render();

        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadHTML($view);

        //$pdf->setPaper('A4', 'landscape');

        return $pdf->stream('reportVentaBalance_' . $today);
    }
}
