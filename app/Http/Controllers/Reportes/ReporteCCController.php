<?php

namespace App\Http\Controllers\Reportes;

use App\Modelos\Contabilidad\Cont_ItemCompra;
use App\Modelos\Nomina\Departamento;
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

        $result = Cont_ItemCompra::join('departamento', 'cont_itemcompra.iddepartamento', '=', 'departamento.iddepartamento')
            ->join('cont_documentocompra','cont_documentocompra.iddocumentocompra','=','cont_itemcompra.iddocumentocompra')
            ->join('cont_catalogitem','cont_catalogitem.idcatalogitem','=','cont_itemcompra.idcatalogitem')
            ->whereRaw("cont_documentocompra.fecharegistrocompra BETWEEN '" . $filter->inicio . "' AND '"  . $filter->fin . "'");

        if ($filter->cc != '0') {
            $result = $result->where('departamento.iddepartamento', $filter->cc);
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
}
