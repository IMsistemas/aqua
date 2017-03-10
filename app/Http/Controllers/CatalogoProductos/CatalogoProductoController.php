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
     * Almacenar el producto
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
    	$image = Input::file('foto');
    	$data = $request->all();
    	$date = Carbon::Today();
    	$data['created_at'] = $data['updated_at']  = $date;
    
    	 
    	if(is_object($image)){
    		$destinationPath = 'uploads/productos';
    		$name = rand(0, 9999).'_'.$image->getClientOriginalName();
    		if(!$image->move($destinationPath, $name)) {
    			return response()->json(['success' => false]);
    		}
    		$data['foto'] = $destinationPath.'/'. $name;
    	}
    	 
    	$result = Cont_CatalogItem::create($data);
    	 
    	return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    	 
    }
    
    /**
     * Mostrar un recurso producto especifico.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
     { 	 	
     	
     	return Cont_CatalogItem::join('sri_tipoimpuestoiva', 'sri_tipoimpuestoiva.idtipoimpuestoiva', '=', 'cont_catalogitem.idtipoimpuestoiva')
		     	->leftJoin('sri_tipoimpuestoice', 'sri_tipoimpuestoice.idtipoimpuestoice', '=', 'cont_catalogitem.idtipoimpuestoice')
		     	->join('cont_plancuenta as p1', 'p1.idplancuenta', '=', 'cont_catalogitem.idplancuenta')
		     	->leftJoin('cont_plancuenta as p2', 'p2.idplancuenta', '=', 'cont_catalogitem.idplancuenta_ingreso')
		     	->join('cont_claseitem', 'cont_claseitem.idclaseitem', '=', 'cont_catalogitem.idclaseitem')
		     	->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idcategoria')
		     	->select('cont_catalogitem.*','sri_tipoimpuestoiva.nametipoimpuestoiva','sri_tipoimpuestoice.nametipoimpuestoice', 'cont_claseitem.nameclaseitem', 'cont_categoria.nombrecategoria', 'cont_categoria.jerarquia','p1.concepto', 'p2.concepto as c2')
		     	->whereRaw("cont_catalogitem.idcatalogitem = '".$id."'")
		     	->first() ;
	
     	
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
    	$image = Input::file('foto');
    	$producto = Cont_CatalogItem::find($id);
    	$date = Carbon::Today();
    	$data = $request->all();
    	$data['updated_at']  = $date;
    	if(is_object($image)){
    		if (file_exists($producto->foto)) {
    			unlink($producto->foto);
    		}
    		$destinationPath = 'uploads/productos';
    		$name = rand(0, 9999).'_'.$image->getClientOriginalName();
    		if(!$image->move($destinationPath, $name)) {
    			return response()->json(['success' => false]);
    		}
    		$data['foto'] = $destinationPath.'/'. $name;
    	}   	 
    	if(!($data['idplancuenta_ingreso']>0)){
    		unset($data['idplancuenta_ingreso']);
    	}
    	$producto->fill($data);
    	$producto->update();
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
    	$producto = Cont_CatalogItem::find($id);
    	if (file_exists($producto->foto)) {
    		unlink($producto->foto);
    	}
    	$producto->delete();
    	return response()->json(['success' => true]);
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
    	
    	$filterCategorias = ($filter->subId != null)?" and idcategoria = '".$filter->subId."'":"";
    	
    	$ltree = str_replace(' ','',$filter->text);
    	return  Cont_CatalogItem::orderBy('codigoproducto', 'asc')
    	->whereRaw("( idcatalogitem::text like '%" . $filter->text . "%' or nombreproducto ILIKE '%" . $filter->text . "%') ".$filterCategorias)
    	->get();
    }

    /**
     * Obtener base del producto nuevo
     *
     * @return mixed
     */
   
    
     


    
    public function anularProducto($param)
    {
    	$param = json_decode($param);
    	$producto = CatalogoProducto::find($param->id);
    	$$productobodega->estado = $param->estado;
    	$producto->save();
    	return response()->json(['success' => true]);
    }

}
