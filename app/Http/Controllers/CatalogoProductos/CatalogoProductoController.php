<?php

namespace App\Http\Controllers\CatalogoProductos;

use App\Modelos\CatalogoProductos\CatalogoProducto;
use App\Modelos\Categoria;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class CatalogoProductoController extends Controller
{

    /**
     * Devolver la vista
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('catalogoproductos.index_catalogo');
    }

    /**
     * Obtener las categorias para filtro
     *
     * @return mixed
     */
    public function getCategoriasToFilter()
    {
    	return Categoria::orderBy('idcategoria', 'asc')
    	->whereRaw('nlevel(idcategoria) = 1')
    	->get();
    	 
    }

    /**
     * Obtener los productos filtrados
     *
     * @param $filter
     * @return mixed
     */
    public function getCatalogoProductos($filter)
    {
    	$meses = array('ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic');      	
    	$filter = json_decode($filter);
    	
    	$date = $filter->text;
    	
    	foreach ($meses as $mes){      		
    		if(strpos($date,$mes)!== false){    			
    			$date = str_replace($mes, str_pad(array_search($mes,$meses)+1,2,0,STR_PAD_LEFT), $date);    			
    			if(strpos($date,'-')!== false){
    				$dateArray = explode('-',$date);
    				if(count($dateArray)>1){
    					if(count($dateArray)>2){
    						$date = $dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0];
    					} else {
    						$date = $dateArray[1].'-'.$dateArray[0];
    					}
    				}   				
    			}
    		}
    	}   
    	
    	$filterCategorias = ($filter->catId != null)?" and idcategoria <@ '".$filter->catId."'":"";    	
    	$filterCategorias .= ($filter->linId != null)?" and idcategoria <@ '".$filter->linId."'":"";
    	$filterCategorias .= ($filter->subId != null)?" and idcategoria <@ '".$filter->subId."'":"";
    	
    	$ltree = str_replace(' ','',$filter->text);
    	return  CatalogoProducto::orderBy('codigoproducto', 'asc')
    	->whereRaw("( codigoproducto::text like '%" . $filter->text . "%' or fechaingreso::text like '%" . $date . "%' or nombreproducto ILIKE '%" . $filter->text . "%') ".$filterCategorias)
    	->get();
    }

    /**
     * Obtener base del producto nuevo
     *
     * @return mixed
     */
    public function getLastCatalogoProducto()
    {
        $producto = new CatalogoProducto();		
		$producto->codigoproducto = CatalogoProducto::max('codigoproducto') +1;
		$date = Carbon::Today();
		$producto->fechaingreso = $date->format('Y-m-d');
		return $producto;
    }
    
    public function getCategoriasHijas($filter)
    {
    	$filter = json_decode($filter);
    	return Categoria::orderBy('idcategoria', 'asc')
    	->whereRaw("nlevel(idcategoria) = ".$filter->nivel. " and idcategoria <@ '".$filter->padre."'")
    	->get();
    
    }    


    /**
     * Almacenar el producto
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
    	$image = Input::file('rutafoto');
    	$destinationPath = 'uploads/productos';
    	$date = Carbon::Today();
    	$name = rand(0, 9999).'_'.$image->getClientOriginalName();
    	if(!$image->move($destinationPath, $name)) {
    		return response()->json(['success' => false]);
    	} else {
    		
    		$producto = new CatalogoProducto();
    		$producto->nombreproducto = $request->input('nombreproducto');
    		$producto->idcategoria = $request->input('idcategoria');
    		$producto->fechaingreso =  $request->input('fechaingreso');    		
    		$producto->rutafoto = $destinationPath.'/'. $name;    		
    		$producto->save();
    	}
    	return response()->json(['success' => true]);
    	
    }

    /**
     * Mostrar un recurso producto especifico.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return CatalogoProducto::find($id);
    }

    /**
     * Actualizar el recurso producto seleccionado
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
    	$image = Input::file('rutafoto');
    	$producto = CatalogoProducto::find($id);
    	$date = Carbon::Today();
    	if(is_object($image)){
    		if (file_exists($producto->rutafoto)) {
    			unlink($producto->rutafoto);
    		}
    		$destinationPath = 'uploads/productos';    		
    		$name = rand(0, 9999).'_'.$image->getClientOriginalName();
    		if(!$image->move($destinationPath, $name)) {
    			return response()->json(['success' => false]);
    		}
    		$producto->rutafoto = $destinationPath.'/'. $name;
    	}
    	
   		$producto->nombreproducto = $request->input('nombreproducto');
    	$producto->idcategoria = $request->input('idcategoria');
    	$producto->fechaingreso =  $request->input('fechaingreso');     	
    	$producto->save();    	
        return response()->json(['success' => true]);
    }

    /**
     * Eliminar el recurso producto seleccionado
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
       $producto = CatalogoProducto::find($id);
       if (file_exists($producto->rutafoto)) {
       		unlink($producto->rutafoto);
       }
        $producto->delete();
        return response()->json(['success' => true]);
    }
    
    public function anularProducto($param)
    {
    	$param = json_decode($param);
    	$producto = CatalogoProducto::find($param->id);
    	$$productobodega->estado = $param->estado;
    	$producto->save();
    	return response()->json(['success' => true]);
    }

}
