<?php

namespace App\Http\Controllers\Facturacionventa;

use Illuminate\Http\Request;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Bodegas\Bodega;
use App\Modelos\Nomina\Empleado;


use App\Modelos\Facturacionventa\establecimiento;
use App\Modelos\Facturacionventa\puntoventa;
use App\Modelos\Facturacionventa\formapagoventa;
use App\Modelos\Facturacionventa\configuracioncontable;
use App\Modelos\Facturacionventa\productoenbodega;
use App\Modelos\Facturacionventa\CatalogoProducto;
use App\Modelos\Facturacionventa\catalogoservicio;
use App\Modelos\Facturacionventa\venta;
use App\Modelos\Facturacionventa\productosenventa;
use App\Modelos\Facturacionventa\serviciosenventa;


use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use DateTime;
use DB;



class DocumentoVenta extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Facturacionventa/index');
        //return view('Facturacionventa/aux_index');
    }
    /**
     * Obtener la informacion de un cliente en especifico
     *
     * @param $getInfoCliente
     * @return mixed
     */
    public function getInfoClienteXCIRuc($getInfoCliente)
    {
        return Cliente::where('documentoidentidad', 'LIKE', '%' . $getInfoCliente . '%')->limit(1)->get();
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
        return Bodega::all();
    }
    /**
     * Ontener la informacion de una producto
     *
     * @param $texto
     * @return mixed
     */
    public function getinfoProducto($texto)
    {				
    	return CatalogoProducto::where('nombreproducto', 'LIKE', '%' . $texto . '%')->get();
    }
    /**
     * obtener informacion de un empleado con su punto de venta
     *
     * 
     * @return mixed
     */
    public function getPuntoVentaEmpleado()
    {               
        return  puntoventa::with('empleado', 'establecimiento')->limit(1)->get();
    }
    /**
     * Obtener la forma de pago para la venta 
     *
     * 
     * @return mixed
     */
    public function getFormaPago()
    {               
        return   formapagoventa::all();
    }
    /**
     * Obtener configuracion contable
     *
     * 
     * @return mixed
     */
    public function getCofiguracioncontable()
    {               
        return   configuracioncontable::all();
    }
    /**
     * obtener productos por bodega
     *
     * @param $id
     * @return mixed
     */
    public function getProductoPorBodega($id)
    {   

        return  catalogoproducto::join('productoenbodega', 'productoenbodega.codigoproducto', '=', 'catalogoproducto.codigoproducto')
                ->where("productoenbodega.idbodega", $id)->get();
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
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datos = $request->all();
        //$datos["documentoventa"]
        //$datos["productosenventa"]
        //$datos["serviciosenventa"]

        $aux_venta = venta::create($datos["documentoventa"]);
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
        return 1;
    }

    /**
     * 
     *
     * @param $filtro
     * @return mixed
     */
    public function getVentas($filtro)
    {
        $filtro = json_decode($filtro);
        $aux_filtro="";
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
                                  )->get();
    }

    /**
     * obtener todos los filtros
     *
     * 
     * @return mixed
     */
    public function getallFitros()
    {               
        $establecimiento= establecimiento::all();
        $puntoventa=puntoventa::all();
        $aux_data = array(
            "establecimiento" => $establecimiento,
            "puntoventa" => $puntoventa,
        );
        return  $aux_data;
    }
    /**
     * obtener todos los filtros
     *
     * 
     * @return mixed
     */
    public function anularVenta($id)
    {               
        $aux_prodv= productosenventa:: where("codigoventa","=",$id)->delete();
        $aux_servv= serviciosenventa:: where("codigoventa","=",$id)->delete();
        $aux_venta= venta::where("codigoventa", $id)
                    ->update(['estaanulada' => 't']);
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
    // $aux_puntoVenta= puntoventa::where("idpuntoventa","=",$aux_venta[0]->idpuntoventa)->get();
        $aux_venta= venta::with('productosenventa','serviciosenventa','cliente')->where("documentoventa.codigoventa","=", $id)->get();
        $aux_puntoVenta= puntoventa::with('empleado', 'establecimiento')->where("idpuntoventa","=",$aux_venta[0]->idpuntoventa)->limit(1)->get();
        $aux_cliente=cliente::where("codigocliente","=",$aux_venta[0]->codigocliente)->get();
        $aux_data = array(
            "venta" => $aux_venta,
            "puntoventa" => $aux_puntoVenta,
            "cliente"=> $aux_cliente
        );
        return $aux_data;
    }


}
