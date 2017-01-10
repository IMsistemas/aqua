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
use App\Modelos\Facturacionventa\catalogoproducto;
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
     * Obtener todos los servicios
     *
     *
     * @return mixed
     */
    public function getDocVenta()
    {   $lastVta=venta::all();
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
        return $aux_venta->codigoventa;
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
     * anular venta
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
        $aux_venta= venta::with('productosenventa.producto','serviciosenventa','cliente','pago','puntoventa.empleado')
        ->where("documentoventa.codigoventa","=", $id)->get();

        $aux_puntoVenta= puntoventa::with('empleado', 'establecimiento')->where("idpuntoventa","=",$aux_venta[0]->idpuntoventa)->limit(1)->get();
        $aux_cliente=cliente::where("codigocliente","=",$aux_venta[0]->codigocliente)->get();
        $aux_data = array(
            "venta" => $aux_venta,
            "puntoventa" => $aux_puntoVenta,
            "cliente"=> $aux_cliente
        );
        return $aux_data;
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

}
