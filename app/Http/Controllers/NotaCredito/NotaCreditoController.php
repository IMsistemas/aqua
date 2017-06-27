<?php

namespace App\Http\Controllers\NotaCredito;

use App\Modelos\Bodegas\Bodega;
use App\Modelos\Suministros\Suministro;
use Illuminate\Http\Request;



use App\Modelos\Clientes\Cliente;
use App\Modelos\Contabilidad\Cont_Bodega;
use App\Modelos\Contabilidad\Cont_FormaPago;
use App\Modelos\Contabilidad\Cont_PuntoDeVenta;
use App\Modelos\Contabilidad\Cont_CatalogItem;
use App\Modelos\Nomina\Empleado;
use App\Modelos\Configuracion\ConfiguracionSystem;
use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Contabilidad\Cont_Transaccion;
use App\Http\Controllers\Contabilidad\CoreContabilidad;
use App\Http\Controllers\CatalogoProductos\CoreKardex;
use App\Modelos\Contabilidad\Cont_DocumentoNotaCreditFactura;
use App\Modelos\Contabilidad\Cont_ItemNotaCreditFactura;
use App\Modelos\Contabilidad\Cont_RegistroCliente;
use App\Modelos\Contabilidad\Cont_FormaPagoDocumentoVenta;
use App\Modelos\Contabilidad\Cont_Kardex;
//use App\Modelos\Facturacionventa\puntoventa;


use App\Http\Requests;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\Facades\Session;

class NotaCreditoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('notacredito/notacredito');
    }

    /**
     * Obtener la informacion de un cliente en especifico
     *
     * @param $getInfoCliente
     * @return mixed
     */
    public function getInfoClienteXCIRuc($getInfoCliente)
    {
        //return Cliente::where('documentoidentidad', 'LIKE', '%' . $getInfoCliente . '%')->limit(1)->get();
        return Cliente::join("persona","persona.idpersona","=","cliente.idpersona")
            ->join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=", "cliente.idtipoimpuestoiva")
            ->join("cont_plancuenta", "cont_plancuenta.idplancuenta","=","cliente.idplancuenta")
            ->whereRaw("persona.numdocidentific LIKE '%".$getInfoCliente."%'")
            ->limit(1)
            ->get();
    }
    /**
     * Ontener la informacion de una bodega
     *
     * @param $texto
     * @return mixed
     */
    public function getinfoBodegas($texto)
    {
        return Bodega::whereRaw("idbodega ILIKE '%" . $texto . "%' or nombrebodega ILIKE '%" . $texto . "%'")->get();
    }
    /**
     * Ontener todas las bodegas
     *
     *
     * @return mixed
     */
    public function getAllbodegas()
    {
        //return Bodega::all();
        return Cont_Bodega::join("cont_plancuenta","cont_plancuenta.idplancuenta","=","cont_bodega.idplancuenta")->get();
    }
    /**
     * Ontener la informacion de una producto
     *
     * @param $texto
     * @return mixed
     */
    public function getinfoProducto($texto)
    {
        return catalogoproducto::where('nombreproducto', 'LIKE', '%' . $texto . '%')->get();
    }
    /**
     * obtener informacion de un empleado con su punto de venta
     *
     *
     * @return mixed
     */
    public function getPuntoVentaEmpleado()
    {
        // return puntoventa::all();
        return  Cont_PuntoDeVenta::join("sri_establecimiento","sri_establecimiento.idestablecimiento","=","cont_puntoventa.idestablecimiento")
            ->join("empleado","empleado.idempleado","=","cont_puntoventa.idempleado")
            ->join("persona","persona.idpersona","=","empleado.idpersona")
            ->get();
    }
    /**
     * Obtener la forma de pago para la venta
     *
     *
     * @return mixed
     */
    public function getFormaPago()
    {
        return   Cont_FormaPago::all();
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
        $aux_data= ConfiguracionSystem::whereRaw(" optionname='CONT_IRBPNR_NC' OR optionname='SRI_RETEN_IVA_NC' OR optionname='CONT_PROPINA_NC' OR optionname='SRI_RETEN_RENTA_NC' OR optionname='CONT_IVA_NC' OR optionname='CONT_ICE_NC' ")->get();
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
    /**
     * obtener productos por bodega
     *
     * @param $id
     * @return mixed
     */
    public function getProductoPorBodega($id)
    {

        return Cont_CatalogItem::join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=","cont_catalogitem.idtipoimpuestoiva")
            //->join("sri_tipoimpuestoice","sri_tipoimpuestoice.idtipoimpuestoice","=","cont_catalogitem.idtipoimpuestoice")
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
            ->whereRaw(" upper(cont_catalogitem.codigoproducto) LIKE upper('%$id%') ")
            ->get();
        //return Cont_CatalogItem::whereRaw("codigoproducto::text LIKE '%" . $id . "%'")
        //->get() ;

        //return  catalogoproducto::join('productoenbodega', 'productoenbodega.codigoproducto', '=', 'catalogoproducto.codigoproducto')
        //->where("productoenbodega.idbodega", $id)->get();
        /*return productoenbodega::with(
           [
               'bodega', 'catalogoproducto',
               'bodega' => function ($query) use ($id){
                           $query->where('idbodega',$id);
                       }
           ])->get();
       */

    }

    public function getProductoPorSuministro()
    {

        return Cont_CatalogItem::join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=","cont_catalogitem.idtipoimpuestoiva")
            //->join("sri_tipoimpuestoice","sri_tipoimpuestoice.idtipoimpuestoice","=","cont_catalogitem.idtipoimpuestoice")
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
            //->whereRaw(" upper(cont_catalogitem.codigoproducto) LIKE upper('%$id%') OR cont_catalogitem.idcatalogitem = 7  OR cont_catalogitem.idcatalogitem = 2")
            ->whereRaw(" cont_catalogitem.idcatalogitem = 7  OR cont_catalogitem.idcatalogitem = 2")
            ->get();
        //return Cont_CatalogItem::whereRaw("codigoproducto::text LIKE '%" . $id . "%'")
        //->get() ;

        //return  catalogoproducto::join('productoenbodega', 'productoenbodega.codigoproducto', '=', 'catalogoproducto.codigoproducto')
        //->where("productoenbodega.idbodega", $id)->get();
        /*return productoenbodega::with(
           [
               'bodega', 'catalogoproducto',
               'bodega' => function ($query) use ($id){
                           $query->where('idbodega',$id);
                       }
           ])->get();
       */

    }
    /**
     * Obtener todos los servicios
     *
     *
     * @return mixed
     */
    public function getAllservicios()
    {
        return catalogoservicio::all();
    }
    /**
     * Obtener todos los servicios
     *
     *
     * @return mixed
     */
    public function getDocVenta()
    {   $lastVta=Cont_DocumentoNotaCreditFactura::all();
        return $lastVta->last();
    }
    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $aux = $request->all();
        $filtro=json_decode($aux["datos"]);
        //--Parte contable
        $id_transaccion= CoreContabilidad::SaveAsientoContable( $filtro->DataContabilidad);
        //--Fin parte contable


        //--Parte invetario kardex
        for($x=0;$x<count($filtro->Datakardex);$x++){
            $filtro->Datakardex[$x]->idtransaccion=$id_transaccion;
        }
        $id_kardex= CoreKardex::GuardarKardex($filtro->Datakardex);
        //--Fin Parte invetario kardex

        $filtro->DataVenta->idtransaccion=$id_transaccion;

        $aux_docventa=(array) $filtro->DataVenta;
        $docventa=Cont_DocumentoNotaCreditFactura::create($aux_docventa);
        $aux_addVenta=Cont_DocumentoNotaCreditFactura::all();
        for($x=0;$x<count($filtro->DataItemsVenta);$x++){
            $filtro->DataItemsVenta[$x]->iddocumentonotacreditfactura=$aux_addVenta->last()->iddocumentonotacreditfactura;
        }
        $aux_itemventa=(array) $filtro->DataItemsVenta;
        //$itemventa=Cont_ItemNotaCreditFactura::create($aux_itemventa);
        for($x=0;$x<count($filtro->DataItemsVenta);$x++){
            Cont_ItemNotaCreditFactura::create((array) $filtro->DataItemsVenta[$x]);
        }

        $registrocliente = array(
            'idcliente' => $docventa->idcliente,
            'idtransaccion' => $id_transaccion,
            'fecha' => $docventa->fecharegistroncf,
            'haber' => $filtro->DataContabilidad->registro[0]->Haber, //primera posicion es cliente
            'debe' => 0,
            'numerodocumento' => "".$aux_addVenta->last()->iddocumentonotacreditfactura."",
            'estadoanulado' => false);
        $aux_registrocliente=Cont_RegistroCliente::create($registrocliente);


        /*$aux= DB::table('cont_formapago_documentoventa')->insert([
            ['idformapago' => $filtro->Idformapagoventa, 'iddocumentoventa' => $aux_addVenta->last()->iddocumentoventa]
        ]);*/




        return 1;

        //$datos["documentoventa"]
        //$datos["productosenventa"]
        //$datos["serviciosenventa"]

        /*$aux_venta = venta::create($datos["documentoventa"]);
        foreach ($datos["productosenventa"] as $producto) {
            productosenventa::create(
                [
                    'codigoventa'=> $aux_venta->codigoventa,
                    'codigoproducto'=> $producto["codigoproducto"],
                    'idbodega'=> $producto["idbodega"],
                    'cantidad'=> $producto["cantidad"],
                    'precio'=> $producto["precio"],
                    'preciototal'=> $producto["preciototal"],
                    'porcentajeiva'=> $producto["porcentajeiva"]

                ]);
        }
        foreach ($datos["serviciosenventa"] as $servicio) {
            serviciosenventa::create(
                [
                    'codigoventa'=>$aux_venta->codigoventa,
                    'idservicio'=> $servicio["idservicio"]
                ]);
        }
        return $aux_venta->codigoventa;*/
    }

    /**
     *
     *
     * @param $filtro
     * @return mixed
     */
    public function getVentas($filtro)
    {
        //$filtro = json_decode($filtro);
        //--Parte contable

        //$id_transaccion= CoreContabilidad::SaveAsientoContable($filtro->DataContabilidad);
        /* //--Fin parte contable
         //--Parte invetario kardex
         for($x=0;$x<count($filtro->Datakardex);$x++){
             $filtro->Datakardex[$x]->idtransaccion=$id_transaccion;
         }
         $id_kardex= CoreKardex::GuardarKardex($filtro->Datakardex);
         //--Fin Parte invetario kardex

         $filtro->DataVenta->idtransaccion=$id_transaccion;

         $aux_docventa=(array) $filtro->DataVenta;
         $docventa=Cont_DocumentoNotaCreditFactura::create($aux_docventa);
         $aux_addVenta=Cont_DocumentoNotaCreditFactura::all();
         for($x=0;$x<count($filtro->DataItemsVenta);$x++){
             $filtro->DataItemsVenta[$x]->iddocumentoventa=$aux_addVenta->last()->iddocumentoventa;
         }
         $aux_itemventa=(array) $filtro->DataItemsVenta;
         //$itemventa=Cont_ItemNotaCreditFactura::create($aux_itemventa);
         for($x=0;$x<count($filtro->DataItemsVenta);$x++){
             Cont_ItemNotaCreditFactura::create((array) $filtro->DataItemsVenta[$x]);
         }

         $registrocliente = array(
             'idcliente' => $docventa->idcliente,
             'idtransaccion' => $id_transaccion,
             'fecha' => $docventa->fecharegistroventa,
             'debe' => $filtro->DataContabilidad->registro[0]->Debe, //primera posicion es cliente
             'haber' => 0,
             'numerodocumento' => "".$aux_addVenta->last()->iddocumentoventa."",
             'estadoanulado' => false);
         $aux_registrocliente=Cont_RegistroCliente::create($registrocliente);
         */


        /*$formapagoventa = array(
            'idformapago' => $filtro->Idformapagoventa,
            'iddocumentoventa' => $aux_addVenta->last()->iddocumentoventa);
        $aux_formapagoVenta=Cont_FormaPagoDocumentoVenta::create($formapagoventa);*/
        /*$aux_formapagoVenta=new Cont_FormaPagoDocumentoVenta;
        $aux_formapagoVenta->idformapago=$filtro->Idformapagoventa;
        $aux_formapagoVenta->iddocumentoventa=$aux_addVenta->last()->iddocumentoventa;
        $aux_formapagoVenta->save();*/



        /*$aux= DB::table('cont_formapago_documentoventa')->insert([
             ['idformapago' => $filtro->Idformapagoventa, 'iddocumentoventa' => $aux_addVenta->last()->iddocumentoventa]
         ]);*/
        return 1;

        /*$aux_filtro="";
        if($filtro->PuntoVenta != null  && $filtro->PuntoVenta!="" ){
            $aux_filtro .=" AND puntoventa.idpuntoventa='".$filtro->PuntoVenta."' ";
        }
        if($filtro->Establecimiento != null  && $filtro->Establecimiento!="" ){
            $aux_filtro .=" AND puntoventa.idestablecimiento='".$filtro->Establecimiento."' ";
        }
        if($filtro->Estado != null  && $filtro->Estado!="" ){
            $aux_filtro .=" AND documentoventa.estapagada='".$filtro->Estado."' ";
        }
        if($filtro->Anulada != null  && $filtro->Anulada!="" ){
            $aux_filtro .=" AND documentoventa.estaanulada='".$filtro->Anulada."' ";
        }

        return venta:: join('cliente', 'cliente.codigocliente','=','documentoventa.codigocliente')
                        ->join("puntoventa","puntoventa.idpuntoventa","=","documentoventa.idpuntoventa")
                        ->whereRaw("(documentoidentidad LIKE '%".$filtro->RucOcLiente."%'  OR CONCAT(apellidos, ' ', nombres) LIKE '%".$filtro->RucOcLiente."%' )".$aux_filtro
                                  )->get();*/
    }

    /**
     * obtener todos los filtros
     *
     *
     * @return mixed
     */
    //public function getallFitros()
    public function getallFitros(Request $request)
    {
        //return Cont_DocumentoNotaCreditFactura::whereRaw(" estadoanulado=false ")->get();

        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $estado = $filter->estado;
        $data = null;
        $aux_query="";
        if ($search!="") {
            $aux_query.=" AND (numdocumentoventa LIKE '%".$search."%' OR nroautorizacionventa LIKE '%".$search."%' )";
        }
        $aux_estado="false";
        if($estado=="I"){
            $aux_estado="true";
        }

        $data= Cont_DocumentoNotaCreditFactura::with('cliente.persona')
            // ->whereRaw(" estadoanulado=false ".$aux_query."" );
            ->whereRaw(" estadoanulado=".$aux_estado." ".$aux_query."" );
        return $data->paginate(10);

        /*
        $establecimiento= establecimiento::all();
        $puntoventa=puntoventa::all();
        $aux_data = array(
            "establecimiento" => $establecimiento,
            "puntoventa" => $puntoventa,
        );
        return  $aux_data;*/
    }
    /**
     * anular venta
     *
     *
     * @return mixed
     */
    public function anularVenta($id)
    {

        /*$aux_prodv= productosenventa:: where("codigoventa","=",$id)->delete();
        $aux_servv= serviciosenventa:: where("codigoventa","=",$id)->delete();
        $aux_venta= venta::where("codigoventa", $id)
                    ->update(['estaanulada' => 't']);*/
        Cont_DocumentoNotaCreditFactura::whereRaw("iddocumentonotacreditfactura=$id")
            ->update(['estadoanulado' => 't']);
        $aux_venta=Cont_DocumentoNotaCreditFactura::find($id);
        CoreContabilidad::AnularAsientoContable($aux_venta->idtransaccion);
        Cont_Kardex::whereRaw("idtransaccion=".$aux_venta->idtransaccion." ")
            ->update(['estadoanulado' => 'f']);
        /*Cont_Transaccion::whereRaw("idtransaccion=".."")
                        ->update(['estadoanulado' => 'f']);*/
        return  1;
    }
    /**
     * anular venta
     *
     *
     * @return mixed
     */
    public function confirmarcobro($id)
    {
        $aux_venta= venta::where("codigoventa", $id)
            ->update(['estapagada' => 't']);
        return  $aux_venta;
    }

    /**
     * Datos de la venta para editar
     *
     *
     * @return mixed
     */
    public function getVentaXId($id)
    {
        /*$aux_venta= venta::with('productosenventa.producto','serviciosenventa','cliente','pago','puntoventa.empleado')
        ->where("documentoventa.codigoventa","=", $id)->get();

        $aux_puntoVenta= puntoventa::with('empleado', 'establecimiento')->where("idpuntoventa","=",$aux_venta[0]->idpuntoventa)->limit(1)->get();
        $aux_cliente=cliente::where("codigocliente","=",$aux_venta[0]->codigocliente)->get();
        $aux_data = array(
            "venta" => $aux_venta,
            "puntoventa" => $aux_puntoVenta,
            "cliente"=> $aux_cliente
        );
        return $aux_data;*/
        /*return Cont_DocumentoNotaCreditFactura::with("cliente","Cont_ItemNotaCreditFactura.cont_catalogoitem","cont_puntoventa")
                                    ->whereRaw(" iddocumentoventa='$id' ")
                                    ->get();*/
        $datadocventa=Cont_DocumentoNotaCreditFactura::whereRaw(" iddocumentonotacreditfactura='$id' ")->get();
        $dataclient=Cliente::join("persona","persona.idpersona","=","cliente.idpersona")
            ->join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=", "cliente.idtipoimpuestoiva")
            ->join("cont_plancuenta", "cont_plancuenta.idplancuenta","=","cliente.idplancuenta")
            ->whereRaw(" cliente.idcliente=".$datadocventa[0]->idcliente." ")
            ->get();
        /*$dataitemventa=Cont_ItemNotaCreditFactura::with("cont_catalogoitem")
                                ->whereRaw(" iddocumentoventa=$id ")
                                ->get();*/
        $dataitemventa=Cont_ItemNotaCreditFactura::join("cont_catalogitem","cont_catalogitem.idcatalogitem","=","cont_itemnotacreditfactura.idcatalogitem")
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
            ->whereRaw(" iddocumentonotacreditfactura=$id ")
            ->get();

        $dataConta=Cont_Transaccion::whereRaw(" idtransaccion=".$datadocventa[0]->idtransaccion."")->get();
        $full_data_venta= array(
            'Venta' => $datadocventa,
            'Cliente' => $dataclient,
            'Items' => $dataitemventa,
            'Contabilidad'=> $dataConta
        );
        return $full_data_venta;

    }
    /**
     * Actualiza la venta
     *
     * @param  \Illuminate\Http\Request  $request, $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){

        $datos = $request->all();
        //$datos["documentoventa"]
        //$datos["productosenventa"]
        //$datos["serviciosenventa"]
        //return $datos["documentoventa"];
        $aux_venta = venta::where("codigoventa","=",$id)
            ->update($datos["documentoventa"]);
        //borrar para no buscar
        $aux_prodv= productosenventa:: where("codigoventa","=",$id)->delete();
        $aux_servv= serviciosenventa:: where("codigoventa","=",$id)->delete();
        // se crean de nuevo los productos o servicios
        foreach ($datos["productosenventa"] as $producto) {
            productosenventa::create(
                [
                    'codigoventa'=> $id,
                    'codigoproducto'=> $producto["codigoproducto"],
                    'idbodega'=> $producto["idbodega"],
                    'cantidad'=> $producto["cantidad"],
                    'precio'=> $producto["precio"],
                    'preciototal'=> $producto["preciototal"],
                    'porcentajeiva'=> $producto["porcentajeiva"]

                ]);
        }
        foreach ($datos["serviciosenventa"] as $servicio) {
            serviciosenventa::create(
                [
                    'codigoventa'=>$id,
                    'idservicio'=> $servicio["idservicio"]
                ]);
        }
        return $id;
    }
    /**
     * Excell
     *
     * @param  $id
     * @return excell
     */
    public function excel($id)
    {
        //$producto = $this->getVentaXId($id);

        $docventa = $this->getVentaXId($id);

        \Excel::create('documentoventa', function($excel) use($docventa){

            $excel->sheet('Venta', function($sheet) use($docventa) {

                $aux_venta=$docventa["venta"][0];
                $aux_cliente=$docventa["cliente"][0];

                $sheet->setOrientation('landscape');

                $sheet->mergeCells('B5:I5');
                $sheet->cells('B5:I5', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->row(5, array('','Venta'));

                $sheet->row(8, array('','Fecha Registro:',$aux_venta["fecharegistrocompra"] ,'Registro Compra No:',str_pad($aux_venta["codigoventa"], 7, "0", STR_PAD_LEFT)));
                $sheet->row(9, array('','Datos Cliente'));
                $sheet->row(10, array('','Ruc/CI:',$aux_cliente["documentoidentidad"],'Razón Social:',$aux_cliente["apellidos"]." ".$aux_cliente["nombres"]));

                $sheet->row(11, array('','Telefono:',$aux_cliente["telefonosecundariodomicilio"],'Direccion:',$aux_cliente["direcciondomicilio"]));

                $sheet->row(12, array('','Datos Documento'));

                $sheet->row(13, array('','Numero de documento:',$aux_venta["numerodocumento"],'Autorización:',$aux_venta["autorizacionfacturar"]));
                $sheet->row(14, array('','Forma Pago:',$aux_venta["pago"]->nombreformapago,'Vendedor', $aux_venta["puntoventa"]->empleado->apellidos." ".$aux_venta["puntoventa"]->empleado->nombres));
                $sheet->row(15, array('','Detalle Compra'));
                $sheet->row(16, array('','T. Venta','Bodega','Cod. Prod','Detalle','Cant.','PVP Unitario','IVA','Total'));

                $sheet->cells('B20:I20', function($cells) {
                    $cells->setFontWeight('bold');

                });

                $i = 17;
                foreach ($aux_venta["productosenventa"] as $item){

                    $sheet->row($i, array("",'Producto',$item["idbodega"],$item["codigoproducto"],$item["producto"]->nombreproducto,$item["cantidad"],$item["precio"],$item["porcentajeiva"],$item["cantidad"]*$item["precio"]));
                    $i++;
                }
                $i++;
                $sheet->row($i, array('','Comentario:',$aux_venta["comentario"],"","","Descuento:",$aux_venta["procentajedescuentocompra"],"Subtotal 14%",$aux_venta["subtotalivaventa"]));
                $i++;
                $sheet->row($i, array('','','','','',"","",'Subtotal 0%:',$aux_venta["subtotalnoivaventa"]));
                $i++;
                $sheet->row($i, array('','','','','','','','Descuento:',$aux_venta["descuentoventa"]));
                $i++;
                $sheet->row($i, array('','','','','','','','Otros:',$aux_venta["otrosvalores"]));
                $i++;
                $sheet->row($i, array('','','','','','','','IVA:',$aux_venta["ivaventa"]));
                $i++;
                $sheet->row($i, array('','','','','','','','Total:',$aux_venta["totalventa"]));

                $objDrawing = new \PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('img/logo.png')); //your image path
                $objDrawing->setCoordinates('B2');
                $objDrawing->setWorksheet($sheet);

            });

        })->export('xls');
    }

    /**
     * procesos para imprimir
     *
     *
     * @return mixed
     */
    public function imprimir($id)
    {
        $docventa=$this->getVentaXId($id);
        $aux_venta=$docventa["venta"][0];
        $aux_cliente=$docventa["cliente"][0];
        $imprimir= true;
        $view =  \View::make('Facturacionventa.printdocventa', compact('aux_venta','aux_cliente','imprimir','id'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('documentoventa');
    }


    public function getSuministroByFactura()
    {
        return Session::get('suministro_to_facturar');
    }
}
