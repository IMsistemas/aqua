<?php

namespace App\Http\Controllers\ATS;

use App\Modelos\Contabilidad\Cont_DocumentoCompra;
use App\Modelos\Contabilidad\Cont_DocumentoVenta;
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
        $numestabruc = '002';

        $empresa = SRI_Establecimiento::all();

        $idinformante = explode('-', $empresa[0]->ruc)[2];


        header("Content-Type: text/html;charset=utf-8");

        $xml = new \DomDocument('1.0', 'UTF-8');
        $iva = $xml->createElement('iva');
        $iva = $xml->appendChild($iva);

        $TipoIDInformante = $xml->createElement('tipoidinformante', $tipoidinformante);
        $iva->appendChild($TipoIDInformante);

        $IdInformante = $xml->createElement('idinformante', $idinformante);
        $iva->appendChild($IdInformante);

        $razonSocial = $xml->createElement('razonsocial', $empresa[0]->razonsocial);
        $iva->appendChild($razonSocial);

        $Anio = $xml->createElement('anio',$year);
        $iva->appendChild($Anio);

        $Mes = $xml->createElement('mes', $month);
        $iva->appendChild($Mes);

        $numEstabRuc = $xml->createElement('numEstabRuc',$numestabruc);
        $iva->appendChild($numEstabRuc);

        $totalVentas = $xml->createElement('totalVentas', 0);
        $iva->appendChild($totalVentas);

        $codigoOperativo = $xml->createElement('codigoOperativo', $codigooperativo);
        $iva->appendChild($codigoOperativo);


        $compras = Cont_DocumentoCompra::join('sri_sustentotributario', 'sri_sustentotributario.idsustentotributario', '=', 'cont_documentocompra.idsustentotributario')
            ->join('sri_tipocomprobante', 'sri_tipocomprobante.idtipocomprobante', '=', 'cont_documentocompra.idtipocomprobante')
            ->join('proveedor', 'proveedor.idproveedor', '=', 'cont_documentocompra.idproveedor')
            ->join('persona', 'persona.idpersona', '=', 'proveedor.idpersona')
            ->join('proveedor', 'proveedor.idparte', '=', 'sri_parte.idparte')
            ->selectRaw('cont_documentocompra.*, sri_tipocomprobante.*, persona.numdocidentific, sri_parte.codigoats AS relacionado')
            ->get();


        for ($i = 0; $i < count($compras); $i++) {

            $detalleCompras = $xml->createElement('detalleCompras');
            $detalleCompras = $compras->appendChild($detalleCompras);

            $vcodSustento = $compras[$i]->codigosrisustento;
            $codSustento = $xml->createElement('codSustento', $vcodSustento);
            $detalleCompras->appendChild($codSustento);

            $vtpIdProv = 01;
            $tpIdProv = $xml->createElement('tpIdProv', $vtpIdProv);
            $tpIdProv = $detalleCompras->appendChild($tpIdProv);

            $vidProv = $compras[$i]->numdocidentific;
            $idProv = $xml->createElement('idProv', $vidProv);
            $idProv = $detalleCompras->appendChild($idProv);

            $vtipoComprobante = $compras[$i]->codigosri; // el tipo de comprobante de la base GUIA TABLA 4 OK
            $vtipoComprobante = str_pad($vtipoComprobante, 2,"0", STR_PAD_LEFT);
            $tipoComprobante = $xml->createElement('tipoComprobante', $vtipoComprobante);
            $tipoComprobante = $detalleCompras->appendChild($tipoComprobante);

            $vparteRel = $compras[$i]->relacionado;
            $parteRel = $xml->createElement('parteRel', $vparteRel);
            $parteRel = $detalleCompras->appendChild($parteRel);


            //----------------------------------------------------------------------------------------------------------

            $vfechaRegistro = $compras[$i]->fecharegistrocompra;
            $fechaRegistro = $xml->createElement('fechaRegistro', $vfechaRegistro);
            $fechaRegistro = $detalleCompras->appendChild($fechaRegistro);


            $vestablecimiento = explode('-', $compras[$i]->numdocumentocompra)[0];
            $establecimiento = $xml->createElement('establecimiento', $vestablecimiento);
            $establecimiento = $detalleCompras->appendChild($establecimiento);

            $vpuntoEmision = explode('-', $compras[$i]->numdocumentocompra)[1];
            $puntoEmision = $xml->createElement('puntoEmision', $vpuntoEmision);
            $puntoEmision = $detalleCompras->appendChild($puntoEmision);

            $vsecuencial = explode('-', $compras[$i]->numdocumentocompra)[2];
            $secuencial = $xml->createElement('secuencial', $vsecuencial);
            $secuencial = $detalleCompras->appendChild($secuencial);

            $vfechaEmision = $compras[$i]->fechaemisioncompra;
            $fechaEmision = $xml->createElement('fechaEmision', $vfechaEmision);
            $fechaEmision = $detalleCompras->appendChild($fechaEmision);

            $vautorizacion = $compras[$i]->nroautorizacioncompra;
            $autorizacion = $xml->createElement('autorizacion', $vautorizacion);
            $autorizacion = $detalleCompras->appendChild($autorizacion);

            $vbaseNoGraIva = $compras[$i]->subtotalnoobjivacompra;
            $baseNoGraIva = $xml->createElement('baseNoGraIva', $vbaseNoGraIva);
            $baseNoGraIva = $detalleCompras->appendChild($baseNoGraIva);

            $vbaseImponible = $compras[$i]->subtotalcerocompra;
            $baseImponible = $xml->createElement('baseImponible', $vbaseImponible);
            $baseImponible = $detalleCompras->appendChild($baseImponible);

            $vbaseImpGrav = $compras[$i]->subtotalconimpuestocompra;
            $baseImpGrav = $xml->createElement('baseImpGrav', number_format($vbaseImpGrav, 2, '.', ''));
            $baseImpGrav = $detalleCompras->appendChild($baseImpGrav);

            $vbaseImpExe = $compras[$i]->subtotalexentivacompra;
            $baseImpExe = $xml->createElement('baseImpExe', $vbaseImpExe);
            $baseImpExe = $detalleCompras->appendChild($baseImpExe);

            $vmontoIce = $compras[$i]->icecompra;
            $montoIce = $xml->createElement('montoIce', $vmontoIce);
            $montoIce = $detalleCompras->appendChild($montoIce);

            $vmontoIva = $compras[$i]->ivacompra;
            $montoIva = $xml->createElement('montoIva', $vmontoIva);
            $montoIva = $detalleCompras->appendChild($montoIva);

            $retencion = SRI_RetencionCompra::where('iddocumentocompra', $compras[$i]->iddocumentocompra)
                                            ->where('estadoanulado', false)->get();

            $retenciondetalle = SRI_RetencionDetalleCompra::where('idretencioncompra', $retencion[0]->idretencioncompra)
                                                            ->where('iddetalleimpuestoretencion', 21)->get();

            if (count($retenciondetalle) > 0){
                $value10 = $retenciondetalle[0]->valorretenido;
            } else {
                $value10 = '0.00';
            }

            $vvalRetBien10 = $value10;
            $valRetBien10 = $xml->createElement('valRetBien10', $vvalRetBien10);
            $valRetBien10 = $detalleCompras->appendChild($valRetBien10);


            //---------20%

            $retenciondetalle = SRI_RetencionDetalleCompra::where('idretencioncompra', $retencion[0]->idretencioncompra)
                ->where('iddetalleimpuestoretencion', 22)->get();

            if (count($retenciondetalle) > 0){
                $value20 = $retenciondetalle[0]->valorretenido;
            } else {
                $value20 = '0.00';
            }

            $vvalRetServ20 = $value20;
            $valRetServ20 = $xml->createElement('valRetServ20', $vvalRetServ20);
            $valRetServ20 = $detalleCompras->appendChild($valRetServ20);

            //---------30%

            $retenciondetalle = SRI_RetencionDetalleCompra::where('idretencioncompra', $retencion[0]->idretencioncompra)
                ->where('iddetalleimpuestoretencion', 23)->get();

            if (count($retenciondetalle) > 0){
                $value30 = $retenciondetalle[0]->valorretenido;
            } else {
                $value30 = '0.00';
            }

            $vvalorRetBienes = $value30;
            $valorRetBienes = $xml->createElement('valorRetBienes', $vvalorRetBienes);
            $valorRetBienes = $detalleCompras->appendChild($valorRetBienes);

            //---------50%

            $retenciondetalle = SRI_RetencionDetalleCompra::where('idretencioncompra', $retencion[0]->idretencioncompra)
                ->where('iddetalleimpuestoretencion', 24)->get();

            if (count($retenciondetalle) > 0){
                $value50 = $retenciondetalle[0]->valorretenido;
            } else {
                $value50 = '0.00';
            }

            $vvalRetServ50 = $value50;
            $valRetServ50 = $xml->createElement('valRetServ50', $vvalRetServ50);
            $valRetServ50 = $detalleCompras->appendChild($valRetServ50);

            //---------70%

            $retenciondetalle = SRI_RetencionDetalleCompra::where('idretencioncompra', $retencion[0]->idretencioncompra)
                                                            ->where('iddetalleimpuestoretencion', 25)->get();

            if (count($retenciondetalle) > 0){
                $value70 = $retenciondetalle[0]->valorretenido;
            } else {
                $value70 = '0.00';
            }

            $vvalorRetServicios = $value70;
            $valorRetServicios = $xml->createElement('valorRetServicios', $vvalorRetServicios);
            $valorRetServicios = $detalleCompras->appendChild($valorRetServicios);

            //---------100%

            $retenciondetalle = SRI_RetencionDetalleCompra::where('idretencioncompra', $retencion[0]->idretencioncompra)
                ->where('iddetalleimpuestoretencion', 26)->get();

            if (count($retenciondetalle) > 0){
                $value100 = $retenciondetalle[0]->valorretenido;
            } else {
                $value100 = '0.00';
            }

            $vvalRetServ100 = $value100;
            $valRetServ100 = $xml->createElement('valRetServ100', $vvalRetServ100);
            $valRetServ100 = $detalleCompras->appendChild($valRetServ100);


            $comprob_reemb = SRI_ComprobanteReembolso::where('iddocumentocompra', $compras[$i]->iddocumentocompra)
                                                        ->get();

            $vtotbasesImpReemb = '0.00';

            if (count($comprob_reemb) > 0) {

                foreach ($comprob_reemb as $item_r) {

                    $vtotbasesImpReemb += $item_r->ivacero + $item_r->iva + $item_r->ivanoobj + $item_r->ivaexento;

                }

            }

            $totbasesImpReemb = $xml->createElement('totbasesImpReemb', $vtotbasesImpReemb);
            $totbasesImpReemb = $detalleCompras->appendChild($totbasesImpReemb);

            $pagoExterior = $xml->createElement('pagoExterior');
            $pagoExterior = $detalleCompras->appendChild($pagoExterior);

            $vpagoLocExt = '01';
            $pagoLocExt = $xml->createElement('pagoLocExt', $vpagoLocExt);
            $pagoLocExt = $pagoExterior->appendChild($pagoLocExt);

            $vpaisEfecPago = "NA"; // TODO: VALIDAR generar en la base codigos segun paises tabla 16 ficha, es condicional si es pago no residente cod 02 se usa este campo
            $paisEfecPago = $xml->createElement('paisEfecPago', $vpaisEfecPago);
            $paisEfecPago = $pagoExterior->appendChild($paisEfecPago);

            $vaplicConvDobTrib = "NA";
            $aplicConvDobTrib = $xml->createElement('aplicConvDobTrib', $vaplicConvDobTrib);
            $aplicConvDobTrib = $pagoExterior->appendChild($aplicConvDobTrib);

            $vpagExtSujRetNorLeg = "NA";
            $pagExtSujRetNorLeg = $xml->createElement('pagExtSujRetNorLeg', $vpagExtSujRetNorLeg);
            $pagExtSujRetNorLeg = $pagoExterior->appendChild($pagExtSujRetNorLeg);

            if (count($comprob_reemb) > 0) {

                $reembolsos = $xml->createElement('reembolsos');
                $reembolsos = $detalleCompras->appendChild($reembolsos);

                foreach ($comprob_reemb as $item_r) {

                    $reembolso = $xml->createElement('reembolso');
                    $reembolso = $reembolsos->appendChild($reembolso);

                    $tipoc = SRI_TipoComprobante::where('idtipocomprobante', $item_r->$item_r)->get();

                    $tipo_temp = str_pad($tipoc[0]->codigosri, 2, "0", STR_PAD_LEFT);

                    $tipoComprobanteReemb = $xml->createElement('tipoComprobanteReemb', $tipo_temp);
                    $tipoComprobanteReemb = $reembolso->appendChild($tipoComprobanteReemb);


                    //$vtpIdProvReemb = $cJSONReembolso['t_reem_ident']; // TODO: no sale el tipo sino la identificacion cambiar por tipo
                    $tpIdProvReemb = $xml->createElement('tpIdProvReemb', '01');
                    $tpIdProvReemb = $reembolso->appendChild($tpIdProvReemb);


                    $vidProvReemb = $item_r->numdocidentific;
                    $idProvReemb = $xml->createElement('idProvReemb', $vidProvReemb);
                    $idProvReemb = $reembolso->appendChild($idProvReemb);

                    $vestablecimientoReemb = explode('-', $item_r->numdocidentific)[0];
                    $establecimientoReemb = $xml->createElement('establecimientoReemb', $vestablecimientoReemb);
                    $establecimientoReemb = $reembolso->appendChild($establecimientoReemb);

                    $vpuntoEmisionReemb = explode('-', $item_r->numdocidentific)[1];
                    $puntoEmisionReemb = $xml->createElement('puntoEmisionReemb', $vpuntoEmisionReemb);
                    $puntoEmisionReemb = $reembolso->appendChild($puntoEmisionReemb);

                    $vsecuencialReemb = explode('-', $item_r->numdocidentific)[2];
                    $secuencialReemb = $xml->createElement('secuencialReemb', $vsecuencialReemb);
                    $secuencialReemb = $reembolso->appendChild($secuencialReemb);

                    $vfechaEmisionReemb = $item_r->fechaemisionreembolso;
                    $fechaEmisionReemb = $xml->createElement('fechaEmisionReemb', $vfechaEmisionReemb);
                    $fechaEmisionReemb = $reembolso->appendChild($fechaEmisionReemb);

                    $vautorizacionReemb = $item_r->noauthreembolso;
                    $autorizacionReemb = $xml->createElement('autorizacionReemb', $vautorizacionReemb);
                    $autorizacionReemb = $reembolso->appendChild($autorizacionReemb);

                    $vbaseImponibleReemb = $item_r->ivacero;
                    $vbaseImponibleReemb = str_replace(array("$", " "), '', $vbaseImponibleReemb); //TODO: validar que la variable  $vbaseImponibleReemb quede como formato numero sino toca hacer un cast
                    $baseImponibleReemb = $xml->createElement('baseImponibleReemb', number_format($vbaseImponibleReemb, 2, '.', ''));
                    $baseImponibleReemb = $reembolso->appendChild($baseImponibleReemb);

                    $vbaseImpGravReemb = $item_r->iva;
                    $baseImpGravReemb = $xml->createElement('baseImpGravReemb', $vbaseImpGravReemb);
                    $baseImpGravReemb = $reembolso->appendChild($baseImpGravReemb);

                    $vbaseNoGraIvaReemb = $item_r->ivanoobj;
                    $baseNoGraIvaReemb = $xml->createElement('baseNoGraIvaReemb', $vbaseNoGraIvaReemb);
                    $baseNoGraIvaReemb = $reembolso->appendChild($baseNoGraIvaReemb);

                    $vbaseImpExeReemb = $item_r->ivaexento;
                    $baseImpExeReemb = $xml->createElement('baseImpExeReemb', $vbaseImpExeReemb);
                    $baseImpExeReemb = $reembolso->appendChild($baseImpExeReemb);

                    $vmontoIceReemb = $item_r->montoice;
                    $montoIceReemb = $xml->createElement('montoIceRemb', $vmontoIceReemb);
                    $montoIceReemb = $reembolso->appendChild($montoIceReemb);

                    $vmontoIvaReemb = $item_r->montoiva;
                    $montoIvaReemb = $xml->createElement('montoIvaRemb', $vmontoIvaReemb);
                    $montoIvaReemb = $reembolso->appendChild($montoIvaReemb);


                }

            }

            $retenciondetalleRENTA = SRI_RetencionDetalleCompra::join('sri_detalleimpuestoretencion', 'sri_detalleimpuestoretencion.iddetalleimpuestoretencion', '=', 'sri_retenciondetallecompra.iddetalleimpuestoretencion')
                                                            ->where('idretencioncompra', $retencion[0]->idretencioncompra)
                                                            ->whereRaw('iddetalleimpuestoretencion NOT IN (21,22,23,24,25,26)')->get();

            if (count($retenciondetalleRENTA) > 0 && count($comprob_reemb) == 0){

                $air = $xml->createElement('air');
                $air = $detalleCompras->appendChild($air);

                foreach ($retenciondetalleRENTA as $item_r) {

                    $detalleAir = $xml->createElement('detalleAir');
                    $detalleAir = $air->appendChild($detalleAir);

                    $vcodRetAir = $item_r->codigosri;
                    $codRetAir = $xml->createElement('codRetAir', $vcodRetAir);
                    $codRetAir = $detalleAir->appendChild($codRetAir);

                    $vbaseImpAir = $compras[$i]->subtotalsinimpuestocompra;
                    $baseImpAir = $xml->createElement('baseImpAir', number_format($vbaseImpAir, 2, '.', ''));
                    $baseImpAir = $detalleAir->appendChild($baseImpAir);

                    $vporcentajeAir = $item_r->porcentajeretenido;
                    $porcentajeAir = $xml->createElement('porcentajeAir', $vporcentajeAir);
                    $porcentajeAir = $detalleAir->appendChild($porcentajeAir);

                    $vvalRetAir = $item_r->valorretenido;
                    $valRetAir = $xml->createElement('valRetAir', number_format($vvalRetAir, 2, '.', ''));
                    $valRetAir = $detalleAir->appendChild($valRetAir);

                }


                $estabRetencion1 = $xml->createElement('estabRetencion1', explode('-', $retencion[0]->nocomprobante)[0]);
                $estabRetencion1 = $detalleCompras->appendChild($estabRetencion1);

                $ptoEmiRetencion1 = $xml->createElement('ptoEmiRetencion1', explode('-', $retencion[0]->nocomprobante)[1]);
                $ptoEmiRetencion1 = $detalleCompras->appendChild($ptoEmiRetencion1);

                $secRetencion1 = $xml->createElement('secRetencion1', explode('-', $retencion[0]->nocomprobante)[2]);
                $secRetencion1 = $detalleCompras->appendChild($secRetencion1);

                $autRetencion1 = $xml->createElement('autRetencion1', $retencion[0]->noauthcomprobante);
                $autRetencion1 = $detalleCompras->appendChild($autRetencion1);

                $fechaEmiRet1 = $xml->createElement('fechaEmiRet1', $retencion[0]->fechaemisioncomprob);
                $fechaEmiRet1 = $detalleCompras->appendChild($fechaEmiRet1);


            }



        }

        $ventas = Cont_DocumentoVenta::join('cliente', 'cliente.idcliente', '=', 'cont_documentoventa.idcliente')
                                        ->join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
                                        ->join('cliente', 'cliente.idparte', '=', 'sri_parte.idparte')
            ->join('sri_tipocomprobante', 'sri_tipocomprobante.idtipocomprobante', '=', 'cont_documentoventa.idtipocomprobante')
            ->selectRaw('cont_documentoventa.*, sri_tipocomprobante.*, persona.numdocidentific, sri_parte.codigoats AS relacionado')
                                        ->get();

        for ($j = 0; $j < count($ventas); $j++) {

            $detalleVentas = $xml->createElement('detalleVentas');
            $detalleVentas = $ventas->appendChild($detalleVentas);

            $vtpIdCliente = '05';
            $tpIdCliente = $xml->createElement('tpIdCliente', $vtpIdCliente);
            $tpIdCliente = $detalleVentas->appendChild($tpIdCliente);

            $vidCliente = $ventas[$j]->numdocidentific;
            $idCliente = $xml->createElement('idCliente', $vidCliente);
            $idCliente = $detalleVentas->appendChild($idCliente);

            $vparteRelVtas = $ventas[$j]->relacionado;
            $parteRelVtas = $xml->createElement('parteRelVtas',$vparteRelVtas);
            $parteRelVtas = $detalleVentas->appendChild($parteRelVtas);

            $vtipoComprobante = $ventas[$j]->codigosri;
            $vtipoComprobante1 = str_pad($vtipoComprobante, 2,"0", STR_PAD_LEFT);
            $tipoComprobante = $xml->createElement('tipoComprobante', $vtipoComprobante1);
            $tipoComprobante = $detalleVentas->appendChild($tipoComprobante);

            $vtipoEmision = 'F';
            $tipoEmision = $xml->createElement('tipoEmision', $vtipoEmision);
            $tipoEmision = $detalleVentas->appendChild($tipoEmision);

            $vnumeroComprobantes = 1;
            $numeroComprobantes = $xml->createElement('numeroComprobantes',$vnumeroComprobantes);
            $numeroComprobantes = $detalleVentas->appendChild($numeroComprobantes);

            $vbaseNoGraIva = $ventas[$j]->subtotalnoobjivaventa;
            $baseNoGraIva = $xml->createElement('baseNoGraIva', $vbaseNoGraIva);
            $baseNoGraIva = $detalleVentas->appendChild($baseNoGraIva);

            $vbaseImponible = $ventas[$j]->subtotalceroventa;
            $baseImponible = $xml->createElement('baseImponible', $vbaseImponible);
            $baseImponible = $detalleVentas->appendChild($baseImponible);

            $vbaseImpGrav = $ventas[$j]->subtotalconimpuestoventa;
            $baseImpGrav = $xml->createElement('baseImpGrav', number_format($vbaseImpGrav, 2, '.', ''));
            $baseImpGrav = $detalleVentas->appendChild($baseImpGrav);

            $vmontoIva = $ventas[$j]->ivacompra;
            $montoIva = $xml->createElement('montoIva', $vmontoIva);
            $montoIva = $detalleVentas->appendChild($montoIva);


            $vmontoIce = $ventas[$j]->icecompra;
            $montoIce = $xml->createElement('montoIce', $vmontoIce);
            $montoIce = $detalleVentas->appendChild($montoIce);

            $valorRetIva = $xml->createElement('valorRetIva', '0.00');
            $valorRetIva = $detalleVentas->appendChild($valorRetIva);

            $valorRetRenta = $xml->createElement('valorRetRenta', '0.00');
            $valorRetRenta = $detalleVentas->appendChild($valorRetRenta);

        }


        $xml->formatOutput = true;

        $dir = '/uploads/ATS';

        if (! is_dir(public_path() . $dir)) {
            mkdir(public_path() . $dir);
        }

        $ubicacionXML = $dir . '/AT'. $year . '_' . $month . '.xml';

        $xml->save($ubicacionXML);

        return response()->json(['success' => true]);

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
