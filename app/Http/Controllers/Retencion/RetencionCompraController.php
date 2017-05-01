<?php

namespace App\Http\Controllers\Retencion;

use App\Modelos\Compras\CompraProducto;
use App\Modelos\Contabilidad\Cont_DocumentoCompra;
use App\Modelos\Retencion\DetalleRetencion;
use App\Modelos\Retencion\DetalleRetencion_Iva;
use App\Modelos\Retencion\DetalleRetencionFuente;
use App\Modelos\Retencion\RetencionCompra;
use App\Modelos\Retencion\RetencionFuenteCompra;
use App\Modelos\SRI\SRI_DetalleImpuestoRetencion;
use App\Modelos\SRI\SRI_RetencionCompra;
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

    public function form($id)
    {
        return view('retencion.form_retencionCompra', ['idretencioncompra' => $id]);
    }

    public function getRetenciones(Request $request)
    {

        /*$filter = json_decode($request->get('filter'));

        $retencion = null;

        if ($filter->year != null && $filter->month != null) {
            $retencion = SRI_RetencionCompra::whereRaw('EXTRACT( YEAR FROM fecha) = ' . $filter->year . ' AND EXTRACT( MONTH FROM fecha) = ' . $filter->month);
        } else if ($filter->year != null) {
            $retencion = SRI_RetencionCompra::whereRaw('EXTRACT( YEAR FROM fecha) = ' . $filter->year);
        } else if ($filter->month != null) {
            $retencion = SRI_RetencionCompra::whereRaw('EXTRACT( MONTH FROM fecha) = ' . $filter->month);
        }

        if ($filter->search != null) {
            if ($retencion != null) {
                $retencion->whereRaw("(razonsocial LIKE '%" . $filter->search . "%' OR numerodocumentoproveedor LIKE '%" . $filter->search . "%')");
            } else {
                $retencion = SRI_RetencionCompra::whereRaw("(razonsocial LIKE '%" . $filter->search . "%' OR numerodocumentoproveedor LIKE '%" . $filter->search . "%')");
            }
        }

        if ($retencion != null) {
            $retencion = $retencion->orderBy('fecha', 'desc')->paginate(10);
        } else {
            $retencion = SRI_RetencionCompra::orderBy('fecha', 'desc')->paginate(10);
        }*/

        $retencion = SRI_RetencionCompra::orderBy('fechaemision', 'desc')->paginate(10);

        return $retencion;

        //return RetencionCompra::orderBy('fecha', 'desc')->paginate(5);
    }

    public function getRetencionesByCompra($id)
    {
        return RetencionFuenteCompra::join('detalleretencion', 'detalleretencion.iddetalleretencion', '=', 'retencionfuentecompra.iddetalleretencion')
                                        ->where('idretencioncompra', $id)->get();
    }

    public function getCodigos($codigo)
    {
        return SRI_DetalleImpuestoRetencion::with('sri_tipoimpuestoretencion')
                    ->where('codigosri', 'LIKE', '%' . $codigo . '%')->get();
    }

    public function getCompras($codigo)
    {
        $compra = Cont_DocumentoCompra::with('proveedor.persona', 'sri_comprobanteretencion')
                            ->where('idcomprobanteretencion', '!=', null)
                            ->whereRaw("cont_documentocompra.numdocumentocompra::text ILIKE '%" . $codigo . "%'")
                            ->get();

        return $compra;
    }

    public function getCodigosRetencion($tipo)
    {
        if ($tipo == 2) {
            return DetalleRetencion::orderBy('codigosri', 'asc')->get();
            //return DetalleRetencionFuente::orderBy('codigoSRI', 'asc')->get();
        } else {
            return [];
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

                $retencion->iddetalleretencion = $item['id'];
                $retencion->descripcion = $item['detalle'];
                $retencion->poecentajeretencion = $item['porciento'];
                $retencion->valorretenido = $item['valor'];

                if ($retencion->save() == false) {
                    return response()->json(['success' => false]);
                }
            }

            return response()->json(['success' => true, 'idretencioncompra' => $retencionCompra->idretencioncompra]);

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
        return RetencionCompra::join('documentocompra', 'documentocompra.codigocompra', '=', 'retencioncompra.codigocompra')
                                ->join('proveedor', 'proveedor.idproveedor', '=', 'documentocompra.idproveedor')
                                ->join('sector', 'proveedor.idsector', '=', 'sector.idsector')
                                ->join('ciudad', 'sector.idciudad', '=', 'ciudad.idciudad')
                                ->join('tipocomprobante', 'tipocomprobante.codigocomprbante', '=', 'documentocompra.codigocomprbante')
                                ->select('documentocompra.*', 'tipocomprobante.nombretipocomprobante', 'retencioncompra.numeroretencion',
                                            'retencioncompra.fecha AS fecharetencion', 'retencioncompra.autorizacion', 'retencioncompra.totalretencion',
                                            'retencioncompra.numerodocumentoproveedor AS serialretencion', 'ciudad.nombreciudad','proveedor.*')
                                ->where('idretencioncompra', $id)->get();

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
        $retencionCompra = RetencionCompra::find($id);

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

            RetencionFuenteCompra::where('idretencioncompra', $id)->delete();

            foreach ($retenciones as $item) {
                $retencion = new RetencionFuenteCompra();
                //$retencion->numeroretencion = $request->input('numeroretencion');
                $retencion->idretencioncompra = $retencionCompra->idretencioncompra;
                $retencion->iddetalleretencion = $item['id'];
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
