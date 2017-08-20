<?php

namespace App\Http\Controllers\Reportes;

use App\Modelos\Contabilidad\Cont_ItemCompra;
use App\Modelos\Contabilidad\Cont_ItemVenta;
use App\Modelos\Nomina\Departamento;
use App\Modelos\SRI\SRI_Establecimiento;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReporteCCController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reportes.reporteCentroCosto');
    }

    public function getCentroCosto(Request $request)
    {

        $filter = json_decode($request->get('filter'));


        if ($filter->tipo == 'G') {

            $result = Cont_ItemCompra::join('departamento', 'cont_itemcompra.iddepartamento', '=', 'departamento.iddepartamento')
                ->join('cont_documentocompra','cont_documentocompra.iddocumentocompra','=','cont_itemcompra.iddocumentocompra')
                ->join('cont_catalogitem','cont_catalogitem.idcatalogitem','=','cont_itemcompra.idcatalogitem')
                ->whereRaw("cont_documentocompra.fecharegistrocompra BETWEEN '" . $filter->inicio . "' AND '"  . $filter->fin . "'");

            if ($filter->cc != '0') {
                $result = $result->where('departamento.iddepartamento', $filter->cc);
            }

        } else {

            $result = Cont_ItemVenta::join('cont_documentoventa', 'cont_documentoventa.iddocumentoventa', '=', 'cont_itemventa.iddocumentoventa')
                ->join('departamento', 'cont_documentoventa.iddepartamento', '=', 'departamento.iddepartamento')
                ->join('cont_catalogitem','cont_catalogitem.idcatalogitem','=','cont_itemventa.idcatalogitem')
                ->whereRaw("cont_documentoventa.fecharegistroventa BETWEEN '" . $filter->inicio . "' AND '"  . $filter->fin . "'");

            if ($filter->cc != '0') {
                $result = $result->where('departamento.iddepartamento', $filter->cc);
            }
        }

        return $result->get();
    }

    public function getListCC()
    {
        return Departamento::where('centrocosto', true)->get();
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

    public function reporte_print($parametro)
    {
        ini_set('max_execution_time', 300);

        $filtro = json_decode($parametro);

        $aux_empresa = SRI_Establecimiento::all();

        $today = date("Y-m-d H:i:s");

        $view =  \View::make('reportes.reporteCCPrint', compact('filtro','today','aux_empresa'))->render();

        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadHTML($view);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('reportCC_' . $today);
    }
}
