<?php

namespace App\Http\Controllers\CatalogoProductos;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Contabilidad\Cont_ProductoBodega;
use App\Modelos\Contabilidad\Cont_Kardex;

use Carbon\Carbon;
use DateTime;
use DB;


class CoreKardex extends Controller
{
	/**
	 *
	 *
	 * Guardar Kardex
	 *
	 */
	public static function GuardarKardex($items)
	{	$aux_kardex=new CoreKardex;
		foreach ($items as $item) {
			$itembodega=Cont_ProductoBodega::whereRaw("idcatalogitem=".$item->idcatalogitem." AND idbodega=".$item->idbodega)->get();	//Busca si el producto exisnte en bodega
		
			if(count($itembodega)==0){ // si no exixte el producot en bodega se lo añade a la bodega
				$aux_kardex->AgregarItemEnBodega($item);
			}else{ //si ya existe el producto en la bodega se hace el kardex directo 
				$aux_kardex->RegistroKardex($itembodega[0]->idproducto_bodega,$item);
			}
		}
		return 1;
	}
	/**
	 *
	 *
	 * Añadir nuevo item a la bodega para incialar el registro en el kardex 
	 *
	 */
	public function AgregarItemEnBodega($item)
	{	
		$aux_kardex=new CoreKardex;
		$aux_itembodega= array(
			'idcatalogitem' => $item->idcatalogitem ,
			'idbodega' => $item->idbodega 
			 );
		$aux_nuevoitembodega=Cont_ProductoBodega::create($aux_itembodega);
		$aux_kardex->RegistroKardex($aux_nuevoitembodega->idproducto_bodega,$item);
	}
	/**
	 *
	 * 
	 * Guardar el Karedex (registro del intem por las bodegas)
	 *
	 */
	public function RegistroKardex($iditembodega,$registro)
	{
		$aux_registrokaredex = array(
			'idtransaccion' => $registro->idtransaccion,
			'idproducto_bodega'=>$iditembodega,
			'fecharegistro' =>$registro->fecharegistro,
			'cantidad'=> $registro->cantidad,
			'costounitario' => $registro->costounitario,
			'costototal' => $registro->costototal,
			'tipoentradasalida' => $registro->tipoentradasalida,
			'estadoanulado' => true,
			'descripcion' => $registro->descripcion
			 );	
		$aux_nuevoregistrokardex=Cont_Kardex::create($aux_registrokaredex);
	}
}