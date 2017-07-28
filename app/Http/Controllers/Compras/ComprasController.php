<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\CatalogoProductos\CoreKardex;
use App\Http\Controllers\Contabilidad\CoreContabilidad;
use App\Modelos\Configuracion\ConfiguracionSystem;
use App\Modelos\Contabilidad\Cont_Bodega;
use App\Modelos\Contabilidad\Cont_DocumentoCompra;
use App\Modelos\Contabilidad\Cont_FormaPago;
use App\Modelos\Contabilidad\Cont_FormaPagoDocumentoCompra;
use App\Modelos\Contabilidad\Cont_ItemCompra;
use App\Modelos\Contabilidad\Cont_ItemVenta;
use App\Modelos\Contabilidad\Cont_Kardex;
use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Contabilidad\Cont_RegistroProveedor;
use App\Modelos\Contabilidad\Cont_Transaccion;
use App\Modelos\Nomina\Departamento;
use App\Modelos\Persona;
use App\Modelos\Proveedores\Proveedor;
use App\Modelos\SRI\SRI_ComprobanteRetencion;
use App\Modelos\SRI\SRI_PagoPais;
use App\Modelos\SRI\SRI_PagoResidente;
use App\Modelos\SRI\SRI_Sustento_Comprobante;
use App\Modelos\SRI\SRI_SustentoTributario;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ComprasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('compras.index');
    }

    public function getCompras(Request $request)
    {
        $filter = json_decode($request->get('filter'));
        $filterCombo = ($filter->proveedorId != null) ? " and cont_documentocompra.idproveedor = " . $filter->proveedorId : "";

        if($filter->estado != null){
            $opcion = boolval($filter->estado)? "true" : "false";
            $filterCombo .= ' and cont_documentocompra.estadoanulado = '.$opcion;
        }

        return  Cont_DocumentoCompra::join('proveedor', 'proveedor.idproveedor', '=', 'cont_documentocompra.idproveedor')
            ->join('persona','persona.idpersona','=','proveedor.idpersona')
            ->select('persona.razonsocial', 'cont_documentocompra.*')
            ->whereRaw("(cont_documentocompra.iddocumentocompra::text ILIKE '%" . $filter->text . "%'
                            or persona.razonsocial ILIKE '%" . $filter->text . "%' OR cont_documentocompra.numdocumentocompra ILIKE '%" . $filter->text . "%')
                            		".$filterCombo)
            ->orderBy('cont_documentocompra.iddocumentocompra', 'desc')
            ->paginate(8);

    }

    public function getProveedorByFilter()
    {
        return Proveedor::with('persona')->get();
    }

    public function getBodegas()
    {
        return Cont_Bodega::orderBy('namebodega', 'asc')->get();
    }

    public function getSustentoTributario()
    {
        return SRI_SustentoTributario::orderBy('namesustento', 'asc')->get();
    }

    public function getTipoComprobante($idsustento)
    {
        return SRI_Sustento_Comprobante::join('sri_tipocomprobante', 'sri_sustento_comprobante.idtipocomprobante', '=', 'sri_tipocomprobante.idtipocomprobante')
                                        ->where('idsustentotributario', $idsustento)
                                        ->select('sri_tipocomprobante.idtipocomprobante', 'sri_tipocomprobante.namecomprobante')
                                        ->orderBy('sri_tipocomprobante.namecomprobante', 'asc')->get();
    }

    public function getCentrosCostos()
    {
        return Departamento::where('centrocosto', true)->get();
    }

    public function getFormaPago()
    {
        return Cont_FormaPago::orderBy('nameformapago', 'asc')->get();
    }

    public function getProveedorByIdentify($identify)
    {
        return Persona::with('proveedor.sri_tipoimpuestoiva', 'proveedor.cont_plancuenta')
                        ->whereRaw("numdocidentific::text ILIKE '%" . $identify . "%'")
                        ->whereRaw('idpersona IN (SELECT idpersona FROM proveedor)')
                        ->get();
    }

    public function getTipoPagoComprobante()
    {
        return SRI_PagoResidente::orderBy('idpagoresidente', 'asc')->get();
    }

    public function getPaisPagoComprobante()
    {
        return SRI_PagoPais::orderBy('pais', 'asc')->get();
    }

    /**
     * Obtener configuracion contable
     *
     *
     * @return mixed
     */
    public function getCofiguracioncontable()
    {
        //return   configuracioncontable::all();
        $aux_data= ConfiguracionSystem::whereRaw(" optionname='CONT_IRBPNR_COMPRA' OR optionname='SRI_RETEN_IVA_COMPRA' OR optionname='CONT_PROPINA_COMPRA' OR optionname='SRI_RETEN_RENTA_COMPRA' OR optionname='CONT_IVA_COMPRA' OR optionname='CONT_ICE_COMPRA' ")->get();
        $aux_configcontable=array();
        foreach ($aux_data as $i) {
            $aux_contable="";
            if($i->optionvalue!=""){
                $aux_contable=Cont_PlanCuenta::whereRaw("idplancuenta=".$i->optionvalue." ")->get();
            }
            $configventa = array(
                'Id' => $i->idconfiguracionsystem,
                'IdContable'=> $i->optionvalue,
                'Descripcion'=>$i->optionname,
                'Contabilidad'=>$aux_contable );
            array_push($aux_configcontable, $configventa);
        }
        return $aux_configcontable;

    }

    public function getLastIDCompra()
    {
        $result = Cont_DocumentoCompra::max('iddocumentocompra');

        return $result;
    }

    public function anularCompra(Request $request)
    {
        $iddocumentocompra = $request->input('iddocumentocompra');

        $compra = Cont_DocumentoCompra::find($iddocumentocompra);
        $compra->estadoanulado =  true;

        if ($compra->save()) {

            CoreContabilidad::AnularAsientoContable($compra->idtransaccion);

            $result = Cont_Kardex::whereRaw('idtransaccion = ' . $compra->idtransaccion)
                                        ->update(['estadoanulado' => true]);

            if ($result == false) {
                return response()->json(['success' => false]);
            }

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    private function getDuplicateNumber($idproveedor, $number)
    {
        $count = Cont_DocumentoCompra::where('idproveedor', $idproveedor)
                                        ->where('numdocumentocompra', $number)->count();

        return $count;
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

        $aux = $request->all();

        $filtro = json_decode($aux["datos"]);

        if ($this->getDuplicateNumber($filtro->DataCompra->idproveedor, $filtro->DataCompra->numdocumentocompra) == 0) {

            //$filtro = $request->input('datos');

            //--Parte contable
            $id_transaccion = CoreContabilidad::SaveAsientoContable($filtro->DataContabilidad);
            //--Fin parte contable


            //--Parte invetario kardex

            $longitud_kardex = count($filtro->Datakardex);

            for($x=0; $x < $longitud_kardex; $x++){
                $filtro->Datakardex[$x]->idtransaccion=$id_transaccion;
            }
            $id_kardex = CoreKardex::GuardarKardex($filtro->Datakardex);

            //--Fin Parte invetario kardex

            $filtro->DataCompra->idtransaccion = $id_transaccion;

            $aux_docventa = (array)$filtro->DataCompra;

            $docventa = Cont_DocumentoCompra::create($aux_docventa);

            if ($docventa != false) {

                $aux_addVenta = Cont_DocumentoCompra::all();

                $lastIDCompra = $aux_addVenta->last()->iddocumentocompra;

                $longitud_items = count($filtro->DataItemsCompra);

                for($x = 0; $x < $longitud_items; $x++) {
                    $filtro->DataItemsCompra[$x]->iddocumentocompra = $lastIDCompra;
                }

                $aux_itemventa = (array) $filtro->DataItemsCompra;
                //$itemventa=Cont_ItemVenta::create($aux_itemventa);

                for($x = 0; $x < $longitud_items; $x++){
                    $result_items = Cont_ItemCompra::create((array) $filtro->DataItemsCompra[$x]);

                    if ($result_items == false) {
                        return response()->json(['success' => false]);
                    }

                }

                $registrocliente = [
                    'idproveedor' => $docventa->idproveedor,
                    'idtransaccion' => $id_transaccion,
                    'fecha' => $docventa->fecharegistrocompra,
                    'haber' => $filtro->DataContabilidad->registro[0]->Haber, //primera posicion es cliente
                    'debe' => 0,
                    'numerodocumento' => "" . $lastIDCompra."",
                    'estadoanulado' => false
                ];

                $aux_registrocliente = Cont_RegistroProveedor::create($registrocliente);

                if ($aux_registrocliente == false) {
                    return response()->json(['success' => false]);
                }

                //----------Insert data Comprobante retencion--------------------------------

                /*if ($filtro->dataComprobante != null) {

                    $comprobante = new SRI_ComprobanteRetencion();

                    $comprobante->idpagoresidente = $filtro->dataComprobante->tipopago;
                    $comprobante->idpagopais = $filtro->dataComprobante->paispago;
                    $comprobante->regimenfiscal = $filtro->dataComprobante->regimenfiscal;
                    $comprobante->conveniotributacion = $filtro->dataComprobante->convenio;
                    $comprobante->normalegal = $filtro->dataComprobante->normalegal;
                    $comprobante->fechaemisioncomprob = $filtro->dataComprobante->fechaemisioncomprobante;
                    $comprobante->nocomprobante = $filtro->dataComprobante->nocomprobante;
                    $comprobante->noauthcomprobante = $filtro->dataComprobante->noauthcomprobante;

                    if ($comprobante->save()) {

                        $id = $comprobante->idcomprobanteretencion;

                        $last_c = Cont_DocumentoCompra::find($lastIDCompra);
                        $last_c->idcomprobanteretencion = $id;

                        if ($last_c->save() == false) {
                            return response()->json(['success' => false]);
                        }

                    } else {
                        return response()->json(['success' => false]);
                    }

                }*/

                $formapago = new Cont_FormaPagoDocumentoCompra();

                $formapago->idformapago = $filtro->Idformapagocompra;
                $formapago->iddocumentocompra = $lastIDCompra;

                if ($formapago->save() == false){
                    return response()->json(['success' => false]);
                }

                return response()->json(['success' => true]);

            } else {
                return response()->json(['success' => false]);
            }

        } else {
            return response()->json(['success' => false, 'document_exist' => true]);
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
        $compra = Cont_DocumentoCompra::with('proveedor.persona', 'proveedor.sri_tipoimpuestoiva', 'sri_comprobanteretencion', 'sri_sustentotributario',
                        'sri_tipocomprobante', 'cont_formapago_documentocompra')
                    ->where('iddocumentocompra', $id)->get();

        $dataitemcompra = Cont_ItemCompra::join("cont_catalogitem","cont_catalogitem.idcatalogitem","=","cont_itemcompra.idcatalogitem")
            ->join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=","cont_catalogitem.idtipoimpuestoiva")
            ->selectRaw("*")
            ->selectRaw("sri_tipoimpuestoiva.porcentaje as PorcentIva ")
            ->selectRaw(" (SELECT aux_ice.porcentaje FROM sri_tipoimpuestoice aux_ice WHERE aux_ice.idtipoimpuestoice=cont_catalogitem.idtipoimpuestoice ) as PorcentIce ")
            ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as concepto")
            ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as controlhaber")
            ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as tipocuenta")
            ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as conceptoingreso")
            ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as controlhaberingreso")
            ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as tipocuentaingreso")
            ->selectRaw("(SELECT f_costopromedioitem(cont_catalogitem.idcatalogitem,'') ) as CostoPromedio")
            ->whereRaw(" iddocumentocompra=$id ")
            ->get();

        $dataConta=Cont_Transaccion::whereRaw(" idtransaccion=" . $compra[0]->idtransaccion."")->get();

        $full_data= [
            'Compra' => $compra, 'Items' => $dataitemcompra,'Contabilidad'=> $dataConta
        ];

        return $full_data;
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
