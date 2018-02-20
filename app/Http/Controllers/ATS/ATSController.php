<?php

namespace App\Http\Controllers\ATS;

use App\Modelos\Contabilidad\Cont_DocumentoCompra;
use App\Modelos\SRI\SRI_Establecimiento;
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
        //
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
            ->join('proveedor', 'proveedor.idparte', '=', 'sri_parte.idparte')
            ->get();


        for ($i = 0; $i < count($compras); $i++) {

            $detalleCompras = $xml->createElement('detalleCompras');
            $detalleCompras = $compras->appendChild($detalleCompras);

            $vcodSustento = $compras[$i]->codigosrisustento;
            $codSustento = $xml->createElement('codSustento', $vcodSustento);
            $detalleCompras->appendChild($codSustento);

            $vtpIdProv = $atsFILA['tpldProv'];
            $tpIdProv = $xml->createElement('tpIdProv', $vtpIdProv);
            $tpIdProv = $detalleCompras->appendChild($tpIdProv);

            $vidProv = $atsFILA['idProv'];
            $idProv = $xml->createElement('idProv', $vidProv);
            $idProv = $detalleCompras->appendChild($idProv);

            $vtipoComprobante = $atsFILA['tipoComprobante']; // el tipo de comprobante de la base GUIA TABLA 4 OK
            $vtipoComprobante = str_pad($vtipoComprobante, 2,"0", STR_PAD_LEFT);
            $tipoComprobante = $xml->createElement('tipoComprobante', $vtipoComprobante);
            $tipoComprobante = $detalleCompras->appendChild($tipoComprobante);

            $vparteRel = $atsFILA['parteRel'];
            $parteRel = $xml->createElement('parteRel', $vparteRel);
            $parteRel = $detalleCompras->appendChild($parteRel);

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
