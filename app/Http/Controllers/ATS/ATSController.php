<?php

namespace App\Http\Controllers\ATS;

use App\Modelos\Contabilidad\Cont_DocumentoCompra;
use App\Modelos\Contabilidad\Cont_DocumentoVenta;
use App\Modelos\Contabilidad\Cont_FormaPagoDocumentoVenta;
use App\Modelos\Contabilidad\Cont_PuntoDeVenta;
use App\Modelos\SRI\SRI_ComprobanteReembolso;
use App\Modelos\SRI\SRI_Establecimiento;
use App\Modelos\SRI\SRI_RetencionCompra;
use App\Modelos\SRI\SRI_RetencionDetalleCompra;
use App\Modelos\SRI\SRI_TipoComprobante;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ATSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ATS.index_ats');
    }

    public function getFiles()
    {

        $dir = public_path() . '/uploads/ATS';

        $filehandle = opendir($dir);

        $files = [];

        while ($file = readdir($filehandle)) {

            if ($file != '.' && $file != '..') {

                $files[] = $file;

            }

        }

        return $files;

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
        $year = $request->input('year');
        $month = $request->input('month');

        $tipoidinformante = 'R';
        $codigooperativo = 'IVA';
        $numestabruc = '001';

        $empresa = SRI_Establecimiento::all();

        $idinformante = explode('-', $empresa[0]->ruc)[2];


        header("Content-Type: text/html;charset=utf-8");

        $xml = new \DOMDocument('1.0', 'UTF-8');
        $iva = $xml->createElement('iva');
        $iva = $xml->appendChild($iva);

        $TipoIDInformante = $xml->createElement('TipoIDInformante', $tipoidinformante);
        $iva->appendChild($TipoIDInformante);

        $IdInformante = $xml->createElement('IdInformante', $idinformante);
        $iva->appendChild($IdInformante);

        $razonSocial = $xml->createElement('razonSocial', $empresa[0]->razonsocial);
        $iva->appendChild($razonSocial);

        $Anio = $xml->createElement('Anio',$year);
        $iva->appendChild($Anio);

        $Mes = $xml->createElement('Mes', $month);
        $iva->appendChild($Mes);

        $numEstabRuc = $xml->createElement('numEstabRuc',$numestabruc);
        $iva->appendChild($numEstabRuc);

        $totalVentas = $xml->createElement('totalVentas', $this->getTotalVentas($year, $month));
        $iva->appendChild($totalVentas);

        $codigoOperativo = $xml->createElement('codigoOperativo', $codigooperativo);
        $iva->appendChild($codigoOperativo);


        $compras = Cont_DocumentoCompra::join('sri_sustentotributario', 'sri_sustentotributario.idsustentotributario', '=', 'cont_documentocompra.idsustentotributario')
            ->join('sri_tipocomprobante', 'sri_tipocomprobante.idtipocomprobante', '=', 'cont_documentocompra.idtipocomprobante')
            ->join('proveedor', 'proveedor.idproveedor', '=', 'cont_documentocompra.idproveedor')
            ->join('persona', 'persona.idpersona', '=', 'proveedor.idpersona')
            //->join('proveedor', 'proveedor.idparte', '=', 'sri_parte.idparte')
            ->selectRaw("cont_documentocompra.*, sri_tipocomprobante.*, persona.numdocidentific, sri_sustentotributario.*")
            ->whereRaw('EXTRACT( MONTH FROM cont_documentocompra.fecharegistrocompra ) = ' . $month)
            ->whereRaw('EXTRACT( YEAR FROM cont_documentocompra.fecharegistrocompra ) = ' . $year)
            ->get();


        $comprasTag = $xml->createElement('compras');
        $comprasTag = $iva->appendChild($comprasTag);

        for ($i = 0; $i < count($compras); $i++) {

            $detalleCompras = $xml->createElement('detalleCompras');
            $detalleCompras = $comprasTag->appendChild($detalleCompras);

            $codSustento = $xml->createElement('codSustento', $compras[$i]->codigosrisustento);
            $detalleCompras->appendChild($codSustento);

            $tpIdProv = $xml->createElement('tpIdProv', '01');
            $detalleCompras->appendChild($tpIdProv);

            $idProv = $xml->createElement('idProv', $compras[$i]->numdocidentific);
            $detalleCompras->appendChild($idProv);

            $vtipoComprobante = str_pad($compras[$i]->codigosri, 2,'0', STR_PAD_LEFT);
            $tipoComprobante = $xml->createElement('tipoComprobante', $vtipoComprobante);
            $detalleCompras->appendChild($tipoComprobante);

            $parteRel = $xml->createElement('parteRel', 'NO');
            $detalleCompras->appendChild($parteRel);

            $temp_fecha_registro = explode('-', $compras[$i]->fecharegistrocompra);
            $vfechaRegistro = $temp_fecha_registro[2] . '/' . $temp_fecha_registro[1] . '/' . $temp_fecha_registro[0];
            $fechaRegistro = $xml->createElement('fechaRegistro', $vfechaRegistro);
            $detalleCompras->appendChild($fechaRegistro);

            $establecimiento = $xml->createElement('establecimiento', explode('-', $compras[$i]->numdocumentocompra)[0]);
            $detalleCompras->appendChild($establecimiento);

            $puntoEmision = $xml->createElement('puntoEmision', explode('-', $compras[$i]->numdocumentocompra)[1]);
            $detalleCompras->appendChild($puntoEmision);

            $secuencial = $xml->createElement('secuencial', explode('-', $compras[$i]->numdocumentocompra)[2]);
            $detalleCompras->appendChild($secuencial);

            $temp_fechaem_registro = explode('-', $compras[$i]->fechaemisioncompra);
            $vfechaEmision = $temp_fechaem_registro[2] . '/' . $temp_fechaem_registro[1] . '/' . $temp_fechaem_registro[0];
            $fechaEmision = $xml->createElement('fechaEmision', $vfechaEmision);
            $detalleCompras->appendChild($fechaEmision);

            $autorizacion = $xml->createElement('autorizacion', $compras[$i]->nroautorizacioncompra);
            $detalleCompras->appendChild($autorizacion);

            $baseNoGraIva = $xml->createElement('baseNoGraIva', $compras[$i]->subtotalnoobjivacompra);
            $detalleCompras->appendChild($baseNoGraIva);

            $baseImponible = $xml->createElement('baseImponible', $compras[$i]->subtotalcerocompra);
            $detalleCompras->appendChild($baseImponible);

            $vbaseImpGrav = $compras[$i]->subtotalconimpuestocompra;
            $baseImpGrav = $xml->createElement('baseImpGrav', number_format($vbaseImpGrav, 2, '.', ''));
            $detalleCompras->appendChild($baseImpGrav);

            $baseImpExe = $xml->createElement('baseImpExe', $compras[$i]->subtotalexentivacompra);
            $detalleCompras->appendChild($baseImpExe);

            $montoIce = $xml->createElement('montoIce', $compras[$i]->icecompra);
            $detalleCompras->appendChild($montoIce);

            $montoIva = $xml->createElement('montoIva', $compras[$i]->ivacompra);
            $detalleCompras->appendChild($montoIva);

            $retencion = SRI_RetencionCompra::where('iddocumentocompra', $compras[$i]->iddocumentocompra)
                                            ->where('estadoanulado', false)->get();

            $retenciondetalle = [];

            if (count($retencion) > 0) {
                $retenciondetalle = SRI_RetencionDetalleCompra::where('idretencioncompra', $retencion[0]->idretencioncompra)
                                                                ->where('iddetalleimpuestoretencion', 21)->get();
            }

            if (count($retenciondetalle) > 0){
                $value10 = $retenciondetalle[0]->valorretenido;
            } else {
                $value10 = '0.00';
            }

            $valRetBien10 = $xml->createElement('valRetBien10', $value10);
            $detalleCompras->appendChild($valRetBien10);

            //---------20%

            if (count($retencion) > 0) {
                $retenciondetalle = SRI_RetencionDetalleCompra::where('idretencioncompra', $retencion[0]->idretencioncompra)
                    ->where('iddetalleimpuestoretencion', 22)->get();
            }

            if (count($retenciondetalle) > 0){
                $value20 = $retenciondetalle[0]->valorretenido;
            } else {
                $value20 = '0.00';
            }

            $valRetServ20 = $xml->createElement('valRetServ20', $value20);
            $detalleCompras->appendChild($valRetServ20);

            //---------30%

            if (count($retencion) > 0) {
                $retenciondetalle = SRI_RetencionDetalleCompra::where('idretencioncompra', $retencion[0]->idretencioncompra)
                    ->where('iddetalleimpuestoretencion', 23)->get();
            }

            if (count($retenciondetalle) > 0){
                $value30 = $retenciondetalle[0]->valorretenido;
            } else {
                $value30 = '0.00';
            }

            $valorRetBienes = $xml->createElement('valorRetBienes', $value30);
            $detalleCompras->appendChild($valorRetBienes);

            //---------50%

            if (count($retencion) > 0) {
                $retenciondetalle = SRI_RetencionDetalleCompra::where('idretencioncompra', $retencion[0]->idretencioncompra)
                    ->where('iddetalleimpuestoretencion', 24)->get();
            }

            if (count($retenciondetalle) > 0){
                $value50 = $retenciondetalle[0]->valorretenido;
            } else {
                $value50 = '0.00';
            }

            $valRetServ50 = $xml->createElement('valRetServ50', $value50);
            $detalleCompras->appendChild($valRetServ50);

            //---------70%

            if (count($retencion) > 0) {
                $retenciondetalle = SRI_RetencionDetalleCompra::where('idretencioncompra', $retencion[0]->idretencioncompra)
                    ->where('iddetalleimpuestoretencion', 25)->get();
            }

            if (count($retenciondetalle) > 0){
                $value70 = $retenciondetalle[0]->valorretenido;
            } else {
                $value70 = '0.00';
            }

            $valorRetServicios = $xml->createElement('valorRetServicios', $value70);
            $detalleCompras->appendChild($valorRetServicios);

            //---------100%

            if (count($retencion) > 0) {
                $retenciondetalle = SRI_RetencionDetalleCompra::where('idretencioncompra', $retencion[0]->idretencioncompra)
                    ->where('iddetalleimpuestoretencion', 26)->get();
            }

            if (count($retenciondetalle) > 0){
                $value100 = $retenciondetalle[0]->valorretenido;
            } else {
                $value100 = '0.00';
            }

            $valRetServ100 = $xml->createElement('valRetServ100', $value100);
            $detalleCompras->appendChild($valRetServ100);


            $comprob_reemb = SRI_ComprobanteReembolso::where('iddocumentocompra', $compras[$i]->iddocumentocompra)
                                                        ->get();

            $vtotbasesImpReemb = '0.00';

            if (count($comprob_reemb) > 0) {

                foreach ($comprob_reemb as $item_r) {

                    $vtotbasesImpReemb += $item_r->ivacero + $item_r->iva + $item_r->ivanoobj + $item_r->ivaexento;

                }

            }

            $totbasesImpReemb = $xml->createElement('totbasesImpReemb', $vtotbasesImpReemb);
            $detalleCompras->appendChild($totbasesImpReemb);

            $pagoExterior = $xml->createElement('pagoExterior');
            $pagoExterior = $detalleCompras->appendChild($pagoExterior);

            $pagoLocExt = $xml->createElement('pagoLocExt', '01');
            $pagoExterior->appendChild($pagoLocExt);

            $paisEfecPago = $xml->createElement('paisEfecPago', 'NA');
            $pagoExterior->appendChild($paisEfecPago);

            $aplicConvDobTrib = $xml->createElement('aplicConvDobTrib', 'NA');
            $pagoExterior->appendChild($aplicConvDobTrib);

            $pagExtSujRetNorLeg = $xml->createElement('pagExtSujRetNorLeg', 'NA');
            $pagoExterior->appendChild($pagExtSujRetNorLeg);

            if (count($comprob_reemb) > 0) {

                $reembolsos = $xml->createElement('reembolsos');
                $reembolsos = $detalleCompras->appendChild($reembolsos);

                foreach ($comprob_reemb as $item_r) {

                    $reembolso = $xml->createElement('reembolso');
                    $reembolso = $reembolsos->appendChild($reembolso);

                    $tipoc = SRI_TipoComprobante::where('idtipocomprobante', $item_r->$item_r)->get();

                    $tipo_temp = str_pad($tipoc[0]->codigosri, 2, "0", STR_PAD_LEFT);

                    $tipoComprobanteReemb = $xml->createElement('tipoComprobanteReemb', $tipo_temp);
                    $reembolso->appendChild($tipoComprobanteReemb);

                    $tpIdProvReemb = $xml->createElement('tpIdProvReemb', '01');
                    $reembolso->appendChild($tpIdProvReemb);

                    $idProvReemb = $xml->createElement('idProvReemb', $item_r->numdocidentific);
                    $reembolso->appendChild($idProvReemb);

                    $establecimientoReemb = $xml->createElement('establecimientoReemb', explode('-', $item_r->numdocidentific)[0]);
                    $reembolso->appendChild($establecimientoReemb);

                    $puntoEmisionReemb = $xml->createElement('puntoEmisionReemb', explode('-', $item_r->numdocidentific)[1]);
                    $reembolso->appendChild($puntoEmisionReemb);

                    $secuencialReemb = $xml->createElement('secuencialReemb', explode('-', $item_r->numdocidentific)[2]);
                    $reembolso->appendChild($secuencialReemb);

                    $temp_fecha_registroreemb = explode('-', $item_r->fechaemisionreembolso);
                    $vfechaEmisionReemb = $temp_fecha_registroreemb[2] . '/' . $temp_fecha_registroreemb[1] . '/' . $temp_fecha_registroreemb[0];
                    $fechaEmisionReemb = $xml->createElement('fechaEmisionReemb', $vfechaEmisionReemb);
                    $reembolso->appendChild($fechaEmisionReemb);

                    $autorizacionReemb = $xml->createElement('autorizacionReemb', $item_r->noauthreembolso);
                    $reembolso->appendChild($autorizacionReemb);

                    $vbaseImponibleReemb = $item_r->ivacero;
                    $baseImponibleReemb = $xml->createElement('baseImponibleReemb', number_format($vbaseImponibleReemb, 2, '.', ''));
                    $reembolso->appendChild($baseImponibleReemb);

                    $baseImpGravReemb = $xml->createElement('baseImpGravReemb', $item_r->iva);
                    $reembolso->appendChild($baseImpGravReemb);

                    $baseNoGraIvaReemb = $xml->createElement('baseNoGraIvaReemb', $item_r->ivanoobj);
                    $reembolso->appendChild($baseNoGraIvaReemb);

                    $baseImpExeReemb = $xml->createElement('baseImpExeReemb', $item_r->ivaexento);
                    $reembolso->appendChild($baseImpExeReemb);

                    $montoIceReemb = $xml->createElement('montoIceRemb', $item_r->montoice);
                    $reembolso->appendChild($montoIceReemb);

                    $montoIvaReemb = $xml->createElement('montoIvaRemb', $item_r->montoiva);
                    $reembolso->appendChild($montoIvaReemb);

                }

            }

            $retenciondetalleRENTA = [];

            if (count($retencion) > 0) {
                $retenciondetalleRENTA = SRI_RetencionDetalleCompra::join('sri_detalleimpuestoretencion', 'sri_detalleimpuestoretencion.iddetalleimpuestoretencion', '=', 'sri_retenciondetallecompra.iddetalleimpuestoretencion')
                    ->where('idretencioncompra', $retencion[0]->idretencioncompra)
                    ->whereRaw('iddetalleimpuestoretencion NOT IN (21,22,23,24,25,26)')->get();
            }


            if (count($retenciondetalleRENTA) > 0 && count($comprob_reemb) == 0){

                $air = $xml->createElement('air');
                $air = $detalleCompras->appendChild($air);

                foreach ($retenciondetalleRENTA as $item_r) {

                    $detalleAir = $xml->createElement('detalleAir');
                    $detalleAir = $air->appendChild($detalleAir);

                    $codRetAir = $xml->createElement('codRetAir', $item_r->codigosri);
                    $detalleAir->appendChild($codRetAir);

                    $baseImpAir = $xml->createElement('baseImpAir', $compras[$i]->subtotalsinimpuestocompra);
                    $detalleAir->appendChild($baseImpAir);

                    $porcentajeAir = $xml->createElement('porcentajeAir', $item_r->porcentajeretenido);
                    $detalleAir->appendChild($porcentajeAir);

                    $valRetAir = $xml->createElement('valRetAir', $item_r->valorretenido);
                    $detalleAir->appendChild($valRetAir);

                }

                $estabRetencion1 = $xml->createElement('estabRetencion1', explode('-', $retencion[0]->nocomprobante)[0]);
                $detalleCompras->appendChild($estabRetencion1);

                $ptoEmiRetencion1 = $xml->createElement('ptoEmiRetencion1', explode('-', $retencion[0]->nocomprobante)[1]);
                $detalleCompras->appendChild($ptoEmiRetencion1);

                $secRetencion1 = $xml->createElement('secRetencion1', explode('-', $retencion[0]->nocomprobante)[2]);
                $detalleCompras->appendChild($secRetencion1);

                $autRetencion1 = $xml->createElement('autRetencion1', $retencion[0]->noauthcomprobante);
                $detalleCompras->appendChild($autRetencion1);

                $temp_fecha_registroRent = explode('-', $retencion[0]->fechaemisioncomprob);
                $fechaEmiRet1 = $temp_fecha_registroRent[2] . '/' . $temp_fecha_registroRent[1] . '/' . $temp_fecha_registroRent[0];
                $fechaEmiRet1 = $xml->createElement('fechaEmiRet1', $fechaEmiRet1);
                $detalleCompras->appendChild($fechaEmiRet1);


            }


        }

        $ventas = Cont_DocumentoVenta::join('cliente', 'cliente.idcliente', '=', 'cont_documentoventa.idcliente')
                                        ->join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
                                        //->join('cliente', 'cliente.idparte', '=', 'sri_parte.idparte')
            ->join('sri_tipocomprobante', 'sri_tipocomprobante.idtipocomprobante', '=', 'cont_documentoventa.idtipocomprobante')
            ->selectRaw('cont_documentoventa.*, sri_tipocomprobante.*, persona.numdocidentific')
            ->whereRaw('EXTRACT( MONTH FROM cont_documentoventa.fecharegistroventa ) = ' . $month)
            ->whereRaw('EXTRACT( YEAR FROM cont_documentoventa.fecharegistroventa ) = ' . $year)
            ->get();

        $ventasTag = $xml->createElement('ventas');
        $ventasTag = $iva->appendChild($ventasTag);

        for ($j = 0; $j < count($ventas); $j++) {

            $detalleVentas = $xml->createElement('detalleVentas');
            $detalleVentas = $ventasTag->appendChild($detalleVentas);

            $tpIdCliente = $xml->createElement('tpIdCliente', '05');
            $detalleVentas->appendChild($tpIdCliente);

            $idCliente = $xml->createElement('idCliente', $ventas[$j]->numdocidentific);
            $detalleVentas->appendChild($idCliente);

            $parteRelVtas = $xml->createElement('parteRelVtas', 'NO');
            $detalleVentas->appendChild($parteRelVtas);

            $vtipoComprobante1 = str_pad($ventas[$j]->codigosri, 2,"0", STR_PAD_LEFT);
            $tipoComprobante = $xml->createElement('tipoComprobante', $vtipoComprobante1);
            $detalleVentas->appendChild($tipoComprobante);

            $tipoEmision = $xml->createElement('tipoEmision', 'F');
            $detalleVentas->appendChild($tipoEmision);

            $numeroComprobantes = $xml->createElement('numeroComprobantes',1);
            $detalleVentas->appendChild($numeroComprobantes);

            $baseNoGraIva = $xml->createElement('baseNoGraIva', $ventas[$j]->subtotalnoobjivaventa);
            $detalleVentas->appendChild($baseNoGraIva);

            $baseImponible = $xml->createElement('baseImponible', $ventas[$j]->subtotalceroventa);
            $detalleVentas->appendChild($baseImponible);

            $baseImpGrav = $xml->createElement('baseImpGrav', $ventas[$j]->subtotalconimpuestoventa);
            $detalleVentas->appendChild($baseImpGrav);

            $montoIva = $xml->createElement('montoIva', $ventas[$j]->ivacompra);
            $detalleVentas->appendChild($montoIva);

            $montoIce = $xml->createElement('montoIce', $ventas[$j]->icecompra);
            $detalleVentas->appendChild($montoIce);

            $valorRetIva = $xml->createElement('valorRetIva', '0.00');
            $detalleVentas->appendChild($valorRetIva);

            $valorRetRenta = $xml->createElement('valorRetRenta', '0.00');
            $detalleVentas->appendChild($valorRetRenta);

            $formaPagoR = Cont_FormaPagoDocumentoVenta::join('cont_formapago', 'cont_formapago.idformapago', '=', 'cont_formapago_documentoventa.idformapago')
                ->where('iddocumentoventa',  $ventas[$j]->iddocumentoventa)->get();

            if (count($formaPagoR) > 0) {

                $formasDePago = $xml->createElement('formasDePago');
                $formasDePago = $detalleVentas->appendChild($formasDePago);

                $formaPago = $xml->createElement('formaPago', $formaPagoR[0]->codigosri);
                $formasDePago->appendChild($formaPago);

            }


        }

        $ventasEstablecimiento = $xml->createElement('ventasEstablecimiento');
        $ventasEstablecimiento = $iva->appendChild($ventasEstablecimiento);
        $ventaEst = $xml->createElement('ventaEst');
        $ventaEst = $ventasEstablecimiento->appendChild($ventaEst);

        $codEstab = $xml->createElement('codEstab', '001');
        $ventaEst->appendChild($codEstab);

        $ventasEstab = $xml->createElement('ventasEstab',$this->getTotalVentas($year, $month));
        $ventaEst->appendChild($ventasEstab);

        $ivaComp = $xml->createElement('ivaComp', '0.00');
        $ventaEst->appendChild($ivaComp);

        $xml->formatOutput = true;

        $dir = '/uploads/ATS';

        if (! is_dir(public_path() . $dir)) {
            mkdir(public_path() . $dir);
        }

        $ubicacionXML = public_path() . $dir . '/AT-'. $year . '_' . $month . '.xml';

        $xml->save($ubicacionXML);

        return response()->json(['success' => true]);

    }

    private function getTotalVentas($year, $month)
    {
        $result = Cont_DocumentoVenta::selectRaw('SUM(valortotalventa) AS total')
            ->whereRaw('EXTRACT( MONTH FROM cont_documentoventa.fecharegistroventa ) = ' . $month)
            ->whereRaw('EXTRACT( YEAR FROM cont_documentoventa.fecharegistroventa ) = ' . $year)
            ->get();

        return $result[0]->total;
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
