<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\CatalogoProductos\CoreKardex;
use App\Http\Controllers\Contabilidad\CoreContabilidad;
use App\Modelos\Configuracion\ConfiguracionSystem;
use App\Modelos\Contabilidad\Cont_Bodega;
use App\Modelos\Contabilidad\Cont_DocumentoCompra;
use App\Modelos\Contabilidad\Cont_FormaPago;
use App\Modelos\Contabilidad\Cont_ItemCompra;
use App\Modelos\Contabilidad\Cont_ItemVenta;
use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Contabilidad\Cont_RegistroProveedor;
use App\Modelos\Persona;
use App\Modelos\Proveedores\Proveedor;
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

            $longitud_items = count($filtro->DataItemsCompra);

            for($x = 0; $x < $longitud_items; $x++) {
                $filtro->DataItemsCompra[$x]->iddocumentocompra=$aux_addVenta->last()->iddocumentocompra;
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
                'numerodocumento' => "".$aux_addVenta->last()->iddocumentocompra."",
                'estadoanulado' => false
            ];

            $aux_registrocliente = Cont_RegistroProveedor::create($registrocliente);

            if ($aux_registrocliente == false) {
                return response()->json(['success' => false]);
            }



            /*$aux= DB::table('cont_formapago_documentoventa')->insert([
                ['idformapago' => $filtro->Idformapagoventa, 'iddocumentoventa' => $aux_addVenta->last()->iddocumentoventa]
            ]);*/


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