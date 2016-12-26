<?php

namespace App\Http\Controllers\Retencion;

use App\Modelos\Compras\CompraProducto;
use App\Modelos\Retencion\DetalleRetencionFuente;
use App\Modelos\Retencion\RetencionCompra;
use App\Modelos\Retencion\RetencionFuenteCompra;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RetencionCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('retencion.index_retencionCompra');
    }

    public function form()
    {
        return view('retencion.form_retencionCompra');
    }

    public function getRetenciones(Request $request)
    {
        return RetencionCompra::orderBy('fecha', 'desc')->paginate(5);
    }

    public function getCodigos($codigo)
    {
        return DetalleRetencionFuente::where('codigoSRI', 'LIKE', '%' . $codigo . '%')->get();
    }

    public function getCompras($codigo)
    {
        return CompraProducto::join('proveedor', 'proveedor.idproveedor', '=', 'documentocompra.idproveedor')
                            ->join('tipocomprobante', 'tipocomprobante.codigocomprbante', '=', 'documentocompra.codigocomprbante')
                            ->whereRaw("documentocompra.codigocompra::text ILIKE '%" . $codigo . "%'")->get();
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
        $retencionCompra = new RetencionCompra();

        $retencionCompra->numeroretencion = $request->input('numeroretencion');
        $retencionCompra->codigocompra = $request->input('codigocompra');
        $retencionCompra->numerodocumentoproveedor = $request->input('numerodocumentoproveedor');
        $retencionCompra->fecha = $request->input('fecha');
        $retencionCompra->razonsocial = $request->input('razonsocial');
        $retencionCompra->documentoidentidad = $request->input('documentoidentidad');
        $retencionCompra->direccion = $request->input('direccion');
        $retencionCompra->ciudad = $request->input('ciudad');
        $retencionCompra->autorizacion = $request->input('autorizacion');
        $retencionCompra->totalretencion = $request->input('totalretencion');

        if ($retencionCompra->save()) {

            $retenciones = $request->input('retenciones');

            foreach ($retenciones as $item) {
                $retencion = new RetencionFuenteCompra();
                //$retencion->numeroretencion = $request->input('numeroretencion');
                $retencion->idretencioncompra = $retencionCompra->idretencioncompra;
                /*$retencion->iddetalleretencionfuente = $item->id;
                $retencion->descripcion = $item->detalle;
                $retencion->poecentajeretencion = $item->porciento;
                $retencion->valorretenido = $item->valor;*/

                $retencion->iddetalleretencionfuente = $item['id'];
                $retencion->descripcion = $item['detalle'];
                $retencion->poecentajeretencion = $item['porciento'];
                $retencion->valorretenido = $item['valor'];

                if ($retencion->save() == false) {
                    return response()->json(['success' => false]);
                }
            }

            return response()->json(['success' => true]);

        } else return response()->json(['success' => false]);
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
