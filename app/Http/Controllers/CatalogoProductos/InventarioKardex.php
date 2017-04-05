<?php

namespace App\Http\Controllers\CatalogoProductos;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Contabilidad\Cont_Kardex;
use App\Modelos\Contabilidad\Cont_ProductoBodega;
use App\Modelos\Contabilidad\Cont_Bodega;
use App\Modelos\Contabilidad\Cont_Categoria;
use App\Modelos\Contabilidad\Cont_CatalogItem;




use Carbon\Carbon;
use DateTime;
use DB;


class InventarioKardex extends Controller
{

	/**
     * Carga la vista
     *
     * 
     */
    public function index()
    {   
        return view('catalogoproductos/InvearioKardex');
    }
    /**
     *
     * Cargar todoas la bodegas
     *
     */
    public function cargarbodegas()
    {
    	return Cont_Bodega::all();
    }
    /**
     *
     * Cargar categorias nivel 1
     *
     */
    public function cargarcategoria()
    {
    	return Cont_Categoria::whereRaw(" jerarquia  ~ '*{1}' ")
    							->orderBy('jerarquia', 'asc')
    							->get();
    	;	
    }
    /**
     *
     * Cargar categorias nivel 2
     *
     */
    public function cargarsubcategoria($id)
    {
    	$aux_nivel1=Cont_Categoria::find($id);
    	return Cont_Categoria::whereRaw("jerarquia <@ '".$aux_nivel1->jerarquia."' AND  idcategoria!=$id ")
    							->orderBy('jerarquia', 'asc')
    							->get();			
    }
    /**
     *
     * Cargar invetario
     *
     */
    public function cargarinvetarioporbodega(Request $request)
    {
    	$filtro = json_decode($request->get('filter'));
    	/*return 	Cont_CatalogItem::selectRaw("*")
    							->selectRaw("(SELECT f_cantidaditemxbodega(idcatalogitem,".$filtro->Bodega.",'".$filtro->Fecha."') ) as Cantidad")
    							->selectRaw("(SELECT f_costopromedioitem(idcatalogitem,'".$filtro->Fecha."') ) as CostoPromedio")
    							->whereRaw(" idclaseitem=1 ")
    							->get();*/
    	$aux_subcategoria="";
    	$aux_search="";
    	if($filtro->SubCategria!=""){
    		$aux_subcategoria=" AND cont_catalogitem.idcategoria=".$filtro->SubCategria." ";
    	}
    	if($filtro->Search!=""){
    		$aux_search=" AND ( upper(cont_catalogitem.nombreproducto) LIKE upper('%".$filtro->Search."%')  OR upper(cont_catalogitem.codigoproducto) LIKE upper('%".$filtro->Search."%') ) ";
    	}
    	return Cont_CatalogItem:: join("cont_producto_bodega","cont_producto_bodega.idcatalogitem","=","cont_catalogitem.idcatalogitem")
    							->selectRaw("*")
    							->selectRaw("(SELECT f_cantidaditemxbodega(cont_catalogitem.idcatalogitem,".$filtro->Bodega.",'".$filtro->Fecha."') ) as Cantidad")
    							->selectRaw("(SELECT f_costopromedioitem(cont_catalogitem.idcatalogitem,'".$filtro->Fecha."') ) as CostoPromedio")
    							->whereRaw("cont_catalogitem.idclaseitem=1 AND cont_producto_bodega.idbodega=".$filtro->Bodega." ".$aux_subcategoria." ".$aux_search)
                                ->paginate(10);
    							//->get();
    }
    /**
     *
     * Kardex Producto
     *
     */
    public function kardexitem($filtro)
    {
    	$filtro = json_decode($filtro);
    	$estado="true";
    	if($filtro->Estado!="A"){
    		$estado="false";
    	}
    	$aux_data= Cont_Kardex::with("cont_transaccion.cont_tipotransaccion")
    						->whereRaw("idproducto_bodega=".$filtro->idproducto_bodega." AND fecharegistro BETWEEN '".$filtro->FechaI."' AND '".$filtro->FechaF."' AND estadoanulado='".$estado."' ")
    						->orderBy('fecharegistro', 'ASC')
    						->get();
    						//print_r($aux_data);
    	$aux_cantidad=0;
    	$aux_total=0;
    	$aux_costop=0;
    	$aux_kardex=array();
    	foreach ($aux_data as $item) {
    		if($item->tipoentradasalida==1){ // Entradas
    			$aux_cantidad=$aux_cantidad+$item->cantidad;
    			$aux_total=$aux_total+$item->costototal;

    			if($aux_cantidad!=0){
    				$aux_costop=($aux_total/$aux_cantidad);
    			}else{
    				$aux_costop=0;
    			}

    			$aux_entrada=array(
    			"idkardex"=> $item->idkardex, 
    			"idtransaccion"=> $item->idtransaccion, 
    			"idproducto_bodega"=>$item->idproducto_bodega,
    			"fecharegistro"=> $item->fecharegistro, 
    			"descripcion"=> $item->descripcion,
    			"cantidadE"=> $item->cantidad,
    			"costounitarioE"=> $item->costounitario, 
    			"costototalE"=> $item->costototal,
    			"cantidadS"=> "",
    			"costounitarioS"=> "", 
    			"costototalS"=> "",
    			"CantidadT"=>$aux_cantidad,
    			"CostoP"=>$aux_costop,
    			"TotalV"=>$aux_total,
    			"transaccion"=> $item->cont_transaccion
    			);
    			array_push($aux_kardex, $aux_entrada);
    		}else{
    			
    			if($aux_cantidad!=0){
    				$aux_costop=($aux_total/$aux_cantidad);
    			}else{
    				$aux_costop=0;
    			}

    			$aux_cantidad=$aux_cantidad-$item->cantidad;
    			$aux_total=$aux_total-$item->costototal;
    			$aux_salida=array(
    			"idkardex"=> $item->idkardex, 
    			"idtransaccion"=> $item->idtransaccion, 
    			"idproducto_bodega"=>$item->idproducto_bodega,
    			"fecharegistro"=> $item->fecharegistro, 
    			"descripcion"=> $item->descripcion,
    			"cantidadE"=> "",
    			"costounitarioE"=> "", 
    			"costototalE"=> "",
    			"cantidadS"=> $item->cantidad,
    			"costounitarioS"=>$item->costounitario, 
    			"costototalS"=> $item->costototal,
    			"CantidadT"=>$aux_cantidad,
    			"CostoP"=>$aux_costop,
    			"TotalV"=>$aux_total,
    			"transaccion"=> $item->cont_transaccion
    			);
    			array_push($aux_kardex, $aux_salida);
    		}
    	}
    	return $aux_kardex;
    }
}