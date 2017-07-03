<?php

namespace App\Http\Controllers\Retencion;

use App\Http\Controllers\Contabilidad\CoreContabilidad;
use App\Modelos\Compras\CompraProducto;
use App\Modelos\Configuracion\ConfiguracionSystem;
use App\Modelos\Contabilidad\Cont_DocumentoCompra;
use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Retencion\DetalleRetencion;
use App\Modelos\Retencion\DetalleRetencion_Iva;
use App\Modelos\Retencion\DetalleRetencionFuente;
use App\Modelos\Retencion\RetencionCompra;
use App\Modelos\Retencion\RetencionFuenteCompra;
use App\Modelos\SRI\SRI_ComprobanteRetencion;
use App\Modelos\SRI\SRI_DetalleImpuestoRetencion;
use App\Modelos\SRI\SRI_RetencionCompra;
use App\Modelos\SRI\SRI_RetencionDetalleCompra;
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

        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $retencion = SRI_ComprobanteRetencion::join('cont_documentocompra', 'cont_documentocompra.idcomprobanteretencion', '=', 'sri_comprobanteretencion.idcomprobanteretencion')
            ->with('cont_documentocompra.proveedor.persona', 'cont_documentocompra.sri_retencioncompra.sri_retenciondetallecompra');


        if ($search != null) {
            $retencion = $retencion->whereRaw("sri_comprobanteretencion.nocomprobante LIKE '%" . $search . "%'");
        }

        return $retencion->orderBy('fechaemisioncomprob', 'desc')->paginate(8);

    }

    public function getConfigContabilidad()
    {
        $config = ConfiguracionSystem::whereRaw("optionname = 'SRI_RETEN_IVA_COMPRA' OR optionname = 'SRI_RETEN_RENTA_COMPRA'")->get();

        $configcontable = [];

        foreach ($config as $item) {
            $aux_contable = null;

            if($item->optionvalue != '' && $item->optionvalue != null){
                $aux_contable = Cont_PlanCuenta::where('idplancuenta', $item->optionvalue)->get();
            }

            $configventa = [
                'idconfiguracionsystem' => $item->idconfiguracionsystem,
                'idplancuenta' => $item->optionvalue,
                'optionname' => $item->optionname,
                'contabilidad'=> $aux_contable
            ];

            $configcontable[] = $configventa;
        }

        return response()->json($configcontable);
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
        $compra = Cont_DocumentoCompra::with('proveedor.persona', 'proveedor.cont_plancuenta', 'sri_comprobanteretencion')
                            ->where('idcomprobanteretencion', '!=', null)
                            ->whereRaw('cont_documentocompra.iddocumentocompra NOT IN (SELECT sri_retencioncompra.iddocumentocompra FROM sri_retencioncompra)')
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

    public function getLastIDRetencion()
    {
        $result = SRI_RetencionCompra::max('idretencioncompra');

        return $result;
    }

    public function anularRetencion(Request $request)
    {
        $idretencion = $request->input('idretencion');

        $retencionCompra = SRI_RetencionCompra::find($idretencion);
        $retencionCompra->estadoanulado = true;

        if ($retencionCompra->save()) {

            $result = CoreContabilidad::AnularAsientoContable($retencionCompra->idtransaccion);

            if ($result == false) {
                return response()->json(['success' => false]);
            }

            return response()->json(['success' => true]);

        } else {
            return response()->json(['success' => false]);
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

        $dataContabilidad = json_decode($request->input('dataContabilidad'));

        $id_transaccion = CoreContabilidad::SaveAsientoContable($dataContabilidad);

        $retencionCompra = new SRI_RetencionCompra();

        $retencionCompra->iddocumentocompra = $request->input('iddocumentocompra');
        $retencionCompra->idtransaccion = $id_transaccion;
        $retencionCompra->estadoanulado = false;

        if ($retencionCompra->save()) {

            $retenciones = $request->input('retenciones');

            foreach ($retenciones as $item) {
                $retencion = new SRI_RetencionDetalleCompra();
                $retencion->idretencioncompra = $retencionCompra->idretencioncompra;
                $retencion->iddetalleimpuestoretencion = $item['id'];
                $retencion->porcentajeretenido = $item['porciento'];
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

        $retencion = SRI_ComprobanteRetencion::join('cont_documentocompra', 'cont_documentocompra.idcomprobanteretencion', '=', 'sri_comprobanteretencion.idcomprobanteretencion')
            ->with('cont_documentocompra.proveedor.persona', 'cont_documentocompra.proveedor.cont_plancuenta', 'cont_documentocompra.sri_retencioncompra.sri_retenciondetallecompra.sri_detalleimpuestoretencion.sri_tipoimpuestoretencion')
            ->orderBy('fechaemisioncomprob', 'desc')
            ->where('sri_comprobanteretencion.idcomprobanteretencion', $id)->get();

        return $retencion;

        /*return RetencionCompra::join('documentocompra', 'documentocompra.codigocompra', '=', 'retencioncompra.codigocompra')
                                ->join('proveedor', 'proveedor.idproveedor', '=', 'documentocompra.idproveedor')
                                ->join('sector', 'proveedor.idsector', '=', 'sector.idsector')
                                ->join('ciudad', 'sector.idciudad', '=', 'ciudad.idciudad')
                                ->join('tipocomprobante', 'tipocomprobante.codigocomprbante', '=', 'documentocompra.codigocomprbante')
                                ->select('documentocompra.*', 'tipocomprobante.nombretipocomprobante', 'retencioncompra.numeroretencion',
                                            'retencioncompra.fecha AS fecharetencion', 'retencioncompra.autorizacion', 'retencioncompra.totalretencion',
                                            'retencioncompra.numerodocumentoproveedor AS serialretencion', 'ciudad.nombreciudad','proveedor.*')
                                ->where('idretencioncompra', $id)->get();*/

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
