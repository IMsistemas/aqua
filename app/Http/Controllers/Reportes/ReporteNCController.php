<?php

namespace App\Http\Controllers\Reportes;

use App\Modelos\Contabilidad\Cont_DocumentoNotaCreditFactura;
use App\Modelos\SRI\SRI_Establecimiento;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReporteNCController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reportes.reporteNC');
    }

    public function getNC(Request $request)
    {

        $filter = json_decode($request->get('filter'));

        return  Cont_DocumentoNotaCreditFactura::join('cliente', 'cliente.idcliente', '=', 'cont_documentonotacreditfactura.idcliente')
            ->join('persona','persona.idpersona','=','cliente.idpersona')
            ->select('persona.razonsocial', 'cont_documentonotacreditfactura.*')
            ->whereRaw("cont_documentonotacreditfactura.fecharegistroncf BETWEEN '" . $filter->inicio . "' AND '"  . $filter->fin . "'")
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

    public function getNCPrint($parametro)
    {

        $filter = json_decode($parametro);

        return  Cont_DocumentoNotaCreditFactura::join('cliente', 'cliente.idcliente', '=', 'cont_documentonotacreditfactura.idcliente')
            ->join('persona','persona.idpersona','=','cliente.idpersona')
            ->select('persona.razonsocial', 'cont_documentonotacreditfactura.*')
            ->whereRaw("cont_documentonotacreditfactura.fecharegistroncf BETWEEN '" . $filter->FechaI . "' AND '"  . $filter->FechaF . "'")
            ->get();
    }

    public function reporte_print($parametro)
    {
        ini_set('max_execution_time', 300);

        $filtro = json_decode($parametro);

        $aux_empresa = SRI_Establecimiento::all();
        $comprobacion = $this->getNCPrint($parametro);
        $today = date("Y-m-d H:i:s");

        $view =  \View::make('reportes.reporteNCPrint', compact('filtro','comprobacion','today','aux_empresa'))->render();

        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadHTML($view);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('reportNC_' . $today);
    }
}
