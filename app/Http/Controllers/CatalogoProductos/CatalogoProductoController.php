<?php

namespace App\Http\Controllers\CatalogoProductos;

use App\Modelos\CatalogoProductos\CatalogoProducto;
use App\Modelos\Categoria;

use App\Modelos\Contabilidad\Cont_CatalogItem;
use App\Modelos\Contabilidad\Cont_ClaseItem;
use App\Modelos\SRI\SRI_TipoImpuestoIce;
use App\Modelos\SRI\SRI_TipoImpuestoIva;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use App\Modelos\Contabilidad\Cont_Categoria;

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



    public function getCatalogoItems(Request $request)
    {
        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $cliente = null;

        return Cont_CatalogItem::orderBy('idcatalogitem', 'desc')->paginate(10);


        /*$cliente = Cliente::join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
            ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cliente.idplancuenta')
            ->select('cliente.*', 'persona.*', 'cont_plancuenta.*');

        if ($search != null) {
            $cliente = $cliente->whereRaw("persona.razonsocial ILIKE '%" . $search . "%'");
        }

        return $cliente->orderBy('fechaingreso', 'desc')->paginate(10);*/
    }

    public function getImpuestoIVA()
    {
        return SRI_TipoImpuestoIva::orderBy('nametipoimpuestoiva', 'asc')->get();
    }

    public function getImpuestoICE()
    {
        return SRI_TipoImpuestoIce::orderBy('nametipoimpuestoice', 'asc')->get();
    }

    public function getTipoItem()
    {
        return Cont_ClaseItem::orderBy('nameclaseitem', 'asc')->get();
    }
    
    /**
     * Obtener las lineas para filtro
     *
     * @return mixed
     */
    public function getCategoriasToFilter()
    {
    	return Cont_Categoria::orderBy('jerarquia', 'asc')
    	->whereRaw('nlevel(jerarquia) = 1')
    	->get();
    
    }
    
    public function getCategoriasHijas($filter)
    {
    	$filter = json_decode($filter);
    	return Cont_Categoria::orderBy('jerarquia', 'asc')
    	->whereRaw("nlevel(jerarquia) = ".$filter->nivel. " and jerarquia <@ '".$filter->padre."'")
    	->get();
    
    }
    
    public function getLastCatalogoProducto()
    {
    	$producto = new Cont_CatalogItem();
    	$producto->idcatalogitem = Cont_CatalogItem::max('idcatalogitem') +1;
    	
    	return $producto;
    }

    /**
     * -----------------------------------------------------------------------------------------------------------------
     */







    

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
   
    
     


    /**
     * Almacenar el producto
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
    	$image = Input::file('foto');
    	$destinationPath = 'uploads/productos';
    	$date = Carbon::Today();
    	$name = rand(0, 9999).'_'.$image->getClientOriginalName();
    	if(!$image->move($destinationPath, $name)) {
    		return response()->json(['success' => false]);
    	} else {    		
    		$data = $request->all();
    		$date = Carbon::Today();
    		$data['created_at'] = $data['updated_at']  = $date;
    		$data['foto'] = $destinationPath.'/'. $name;
    		$result = Cont_CatalogItem::create($data);    		
    	}
    	return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    	
    }

    /**
     * Mostrar un recurso producto especifico.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function show($id)
    {
        return CatalogoProducto::find($id);
    }*/

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
