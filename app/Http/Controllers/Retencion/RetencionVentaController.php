<?php

namespace App\Http\Controllers\Retencion;

use App\Modelos\Facturacionventa\venta;
use App\Modelos\Retencion\DetalleRetencion;
use App\Modelos\Retencion\RetencionFuenteVenta;
use App\Modelos\Retencion\RetencionVenta;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RetencionVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('retencion.index_retencionVenta');
    }

    public function getRetenciones(Request $request)
    {

        $filter = json_decode($request->get('filter'));

        $retencion = null;

        if ($filter->year != null && $filter->month != null) {
            $retencion = RetencionVenta::whereRaw('EXTRACT( YEAR FROM fecha) = ' . $filter->year . ' AND EXTRACT( MONTH FROM fecha) = ' . $filter->month);
        } else if ($filter->year != null) {
            $retencion = RetencionVenta::whereRaw('EXTRACT( YEAR FROM fecha) = ' . $filter->year);
        } else if ($filter->month != null) {
            $retencion = RetencionVenta::whereRaw('EXTRACT( MONTH FROM fecha) = ' . $filter->month);
        }

        if ($filter->search != null) {
            if ($retencion != null) {
                $retencion->whereRaw("razonsocial LIKE '%" . $filter->search . "%'");
            } else {
                $retencion = RetencionVenta::whereRaw("razonsocial LIKE '%" . $filter->search . "%'");
            }
        }

        if ($retencion != null) {
            $retencion = $retencion->orderBy('fecha', 'desc')->paginate(10);
        } else {
            $retencion = RetencionVenta::orderBy('fecha', 'desc')->paginate(10);
        }

        return $retencion;

    }

    public function getRetencionesByVenta($id)
    {
        return RetencionFuenteVenta::join('detalleretencion', 'detalleretencion.iddetalleretencion', '=', 'retencionfuenteventa.iddetalleretencion')
            ->where('idretencionventa', $id)->get();
    }

    public function getCodigos($codigo)
    {
        return DetalleRetencion::where('codigosri', 'LIKE', '%' . $codigo . '%')->get();
    }

    public function getVentas($codigo)
    {
        return venta::join('cliente', 'cliente.codigocliente', '=', 'documentoventa.codigocliente')
            ->whereRaw("documentoventa.codigoventa::text ILIKE '%" . $codigo . "%'")->get();
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
        $retencionCompra = new RetencionVenta();

        $retencionCompra->numeroretencion2 = $request->input('numeroretencion');
        $retencionCompra->codigoventa = $request->input('codigocompra');
        //$retencionCompra->numerodocumentoproveedor = $request->input('numerodocumentoproveedor');
        $retencionCompra->fecha = $request->input('fecha');
        $retencionCompra->razonsocial = $request->input('razonsocial');
        $retencionCompra->documentoidentidad = $request->input('documentoidentidad');
        $retencionCompra->direccion = $request->input('direccion');
        //$retencionCompra->ciudad = $request->input('ciudad');
        $retencionCompra->autorizacion = $request->input('autorizacion');
        $retencionCompra->totalretencion = $request->input('totalretencion');

        if ($retencionCompra->save()) {

            $retenciones = $request->input('retenciones');

            foreach ($retenciones as $item) {
                $retencion = new RetencionFuenteVenta();
                //$retencion->numeroretencion = $request->input('numeroretencion');
                $retencion->idretencionventa = $retencionCompra->idretencionventa;
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

            return response()->json(['success' => true, 'idretencioncompra' => $retencionCompra->idretencionventa]);

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
        $retencionCompra = RetencionVenta::find($id);

        $retencionCompra->numeroretencion2 = $request->input('numeroretencion');
        $retencionCompra->codigoventa = $request->input('codigocompra');
        //$retencionCompra->numerodocumentoproveedor = $request->input('numerodocumentoproveedor');
        $retencionCompra->fecha = $request->input('fecha');
        $retencionCompra->razonsocial = $request->input('razonsocial');
        $retencionCompra->documentoidentidad = $request->input('documentoidentidad');
        $retencionCompra->direccion = $request->input('direccion');
        //$retencionCompra->ciudad = $request->input('ciudad');
        $retencionCompra->autorizacion = $request->input('autorizacion');
        $retencionCompra->totalretencion = $request->input('totalretencion');

        if ($retencionCompra->save()) {

            $retenciones = $request->input('retenciones');

            RetencionFuenteVenta::where('idretencionventa', $id)->delete();

            foreach ($retenciones as $item) {
                $retencion = new RetencionFuenteVenta();
                //$retencion->numeroretencion = $request->input('numeroretencion');
                $retencion->idretencionventa = $retencionCompra->idretencionventa;
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
