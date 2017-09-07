<?php

namespace App\Http\Controllers\Reembolso;

use App\Modelos\SRI\SRI_ComprobanteReembolso;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReembolsoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reembolso.index');
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

        $reembolso = new SRI_ComprobanteReembolso();

        $reembolso->iddocumentocompra = $request->input('iddocumentocompra');

        $reembolso->idtipoidentificacion = $request->input('idtipoidentificacion');
        $reembolso->idtipocomprobante = $request->input('idtipocomprobante');
        $reembolso->numdocidentific = $request->input('numdocidentific');
        $reembolso->numdocumentoreembolso = $request->input('numdocumentoreembolso');
        $reembolso->noauthreembolso = $request->input('noauthreembolso');
        $reembolso->fechaemisionreembolso = $request->input('fechaemisionreembolso');
        $reembolso->ivacero = $request->input('ivacero');
        $reembolso->iva = $request->input('iva');
        $reembolso->ivanoobj = $request->input('ivanoobj');
        $reembolso->ivaexento = $request->input('ivaexento');
        $reembolso->montoiva = $request->input('montoiva');
        $reembolso->montoice = $request->input('montoice');

        if ($reembolso->save()) {
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
