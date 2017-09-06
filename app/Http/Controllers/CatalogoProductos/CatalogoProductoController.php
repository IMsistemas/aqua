<?php

namespace App\Http\Controllers\CatalogoProductos;

use App\Http\Controllers\Contabilidad\CoreContabilidad;
use App\Modelos\CatalogoProductos\CatalogoProducto;
use App\Modelos\Categoria;
use App\Modelos\Contabilidad\Cont_Bodega;
use App\Modelos\Contabilidad\Cont_Itemactivofijo;
use App\Modelos\Contabilidad\Cont_CatalogItem;
use App\Modelos\Contabilidad\Cont_ClaseItem;
use App\Modelos\Contabilidad\Cont_Kardex;
use App\Modelos\Contabilidad\Cont_OpenBalanceItems;
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

        return Cont_CatalogItem::orderBy('idcatalogitem', 'desc')->paginate(5);


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

    public function getIVADefault()
    {
        return ConfiguracionSystem::where('optionname', 'SRI_IVA_DEFAULT')->get();
    }

    public function getBodegas()
    {
        return Cont_Bodega::join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cont_bodega.idplancuenta')
                            ->get();
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

    public function getOpenBalanceProducto($id)
    {
        return Cont_OpenBalanceItems::with('cont_plancuenta')->where('idcatalogitem', $id)->get();
    }
    
    /**
     * Almacenar el producto
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $catalogo = new Cont_CatalogItem();
    	$image = Input::file('foto');
    	//$data = $request->all();
    	$date = Carbon::Today();
    	//$data['created_at'] = $data['updated_at']  = $date;
        /*if ($request->input('codigoemision'==null)) {
            $data['idtipoimpuestoice'] =undefined;
        }*/
            $catalogo->idtipoimpuestoiva = $request->input('idtipoimpuestoiva');

            if ($request->input('idtipoimpuestoice')!=null) {
               $catalogo->idtipoimpuestoice = $request->input('idtipoimpuestoice');
            }
            
            //$catalogo->idplancuenta = $request->input('idplancuenta');

            if ($request->input('idplancuenta_ingreso') != null) {
                $catalogo->idplancuenta_ingreso = $request->input('idplancuenta_ingreso');
            }

            //$catalogo->idplancuenta_ingreso =$request->input('idplancuenta_ingreso');

            $catalogo->idclaseitem = $request->input('idclaseitem');
            $catalogo->idcategoria = $request->input('idcategoria');
            $catalogo->nombreproducto = $request->input('nombreproducto');
            $catalogo->codigoproducto = $request->input('codigoproducto');

            if ($request->input('precioventa') != null && $request->input('precioventa') != '') {
                $catalogo->precioventa = $request->input('precioventa');
            }


            $catalogo->created_at = $date;
            $catalogo->updated_at = $date;
    	if(is_object($image)){
    		$destinationPath = 'uploads/productos';
    		$name = rand(0, 9999).'_'.$image->getClientOriginalName();
    		if(!$image->move($destinationPath, $name)) {
    			return response()->json(['success' => false]);
    		}
            $catalogo->foto = $destinationPath.'/'. $name;
    		//$data['foto'] = $destinationPath.'/'. $name;
    	}


            /*$catalogo = new Cont_CatalogItem();
            $catalogo->idtipoimpuestoiva = $request->input('idtipoimpuestoiva');;
            //$catalogo->idtipoimpuestoice = $request->input('codigoemision');;
            $catalogo->idplancuenta = $request->input('idplancuenta');
            $catalogo->idplancuenta_ingreso =$request->input('idplancuenta_ingreso');;
            $catalogo->idclaseitem = $request->input('idclaseitem');;
            $catalogo->idcategoria = $request->input('idcategoria');
            $catalogo->nombreproducto = $request->input('nombreproducto');;
            $catalogo->idestablecimiento = $request->input('idestablecimiento');;
            $catalogo->codigoproducto = $request->input('codigoproducto');
            $catalogo->precioventa = $request->input('precioventa');;
            $catalogo->save();**/
            $catalogo->save();
    	    //$result = Cont_CatalogItem::create($data);
            if ($catalogo->idclaseitem==3) {
                $id = $catalogo::all();
                     
                $guardarItemactivofijo = new  Cont_Itemactivofijo($request->all());
                $guardarItemactivofijo->idcatalogitem =$id->last()->idcatalogitem;
                $guardarItemactivofijo->save();
            }
            
    	 
    	//return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
            return response()->json(['success' => true]);
    	 
    }

    public function saveOpenBalance(Request $request)
    {
        $aux = $request->all();

        $filtro = json_decode($aux['datos']);

        $id_transaccion = CoreContabilidad::SaveAsientoContable($filtro->DataContabilidad);

        $filtro->Datakardex[0]->idtransaccion = $id_transaccion;
        CoreKardex::GuardarKardex($filtro->Datakardex);

        $filtro->DataOpenBalance->idtransaccion = $id_transaccion;

        $object_newOpenBalance = new Cont_OpenBalanceItems();
        $object_newOpenBalance->idtransaccion = $filtro->DataOpenBalance->idtransaccion;
        $object_newOpenBalance->idcatalogitem = $filtro->DataOpenBalance->idcatalogitem;
        $object_newOpenBalance->idbodega = $filtro->DataOpenBalance->idbodega;
        $object_newOpenBalance->idplancuenta = $filtro->DataOpenBalance->idplancuenta;
        $object_newOpenBalance->fecha = $filtro->DataOpenBalance->fecha;
        $object_newOpenBalance->totalstock = $filtro->DataOpenBalance->totalstock;
        $object_newOpenBalance->totalvalor = $filtro->DataOpenBalance->totalvalor;

        if ($object_newOpenBalance->save()) {

            return response()->json(['success' => true]);

        } else {

            return response()->json(['success' => false]);

        }
    }

    public function anularOB(Request $request)
    {
        $idopenbalanceitems = $request->input('idopenbalanceitems');

        $ob = Cont_OpenBalanceItems::find($idopenbalanceitems);
        $ob->estadoanulado = true;

        if ($ob->save()) {

            CoreContabilidad::AnularAsientoContable($ob->idtransaccion);

            $result = Cont_Kardex::whereRaw('idtransaccion = ' . $ob->idtransaccion)
                ->update(['estadoanulado' => false]);

            if ($result == false) {
                return response()->json(['success' => false]);
            }

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
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
		     	//->join('cont_plancuenta as p1', 'p1.idplancuenta', '=', 'cont_catalogitem.idplancuenta')
		     	->leftJoin('cont_plancuenta as p2', 'p2.idplancuenta', '=', 'cont_catalogitem.idplancuenta_ingreso')
		     	->join('cont_claseitem', 'cont_claseitem.idclaseitem', '=', 'cont_catalogitem.idclaseitem')
		     	->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idcategoria')
		     	->select('cont_catalogitem.*','sri_tipoimpuestoiva.nametipoimpuestoiva','sri_tipoimpuestoice.nametipoimpuestoice', 'cont_claseitem.nameclaseitem', 'cont_categoria.nombrecategoria', 'cont_categoria.jerarquia', 'p2.concepto as c2')
		     	->whereRaw("cont_catalogitem.idcatalogitem = '".$id."'")
		     	->first();
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
    	$catalogo = Cont_CatalogItem::find($id);
    	$date = Carbon::Today();
    	//$data = $request->all();
    	//$data['updated_at']  = $date;
    	
    	
    	if(is_object($image)){
    		if (file_exists($catalogo->foto)) {
    			unlink($catalogo->foto);
    		}
    		$destinationPath = 'uploads/productos';
    		$name = rand(0, 9999).'_'.$image->getClientOriginalName();
    		if(!$image->move($destinationPath, $name)) {
    			return response()->json(['success' => false]);
    		}
            $catalogo->foto = $destinationPath.'/'. $name;
    		//$data['foto'] = $destinationPath.'/'. $name;
    	}   	 
    	/*if(!($data['idplancuenta_ingreso']>0)){
    		unset($data['idplancuenta_ingreso']);
    	}*/
            $catalogo->idtipoimpuestoiva = $request->input('idtipoimpuestoiva');

            if ($request->input('idtipoimpuestoice') != null && $request->input('idtipoimpuestoice') != 'null') {
                $catalogo->idtipoimpuestoice = $request->input('idtipoimpuestoice');
            }


            //$catalogo->idplancuenta = $request->input('idplancuenta');

            if ($request->input('idplancuenta_ingreso') != null && $request->input('idplancuenta_ingreso')!= 'null') {
                $catalogo->idplancuenta_ingreso = $request->input('idplancuenta_ingreso');
            }

            //$catalogo->idplancuenta_ingreso =$request->input('idplancuenta_ingreso');
            $catalogo->idclaseitem = $request->input('idclaseitem');
            $catalogo->idcategoria = $request->input('idcategoria');
            $catalogo->nombreproducto = $request->input('nombreproducto');
            $catalogo->codigoproducto = $request->input('codigoproducto');

            if ($request->input('precioventa') != null && $request->input('precioventa') != '' && $request->input('precioventa') != 'null') {
                $catalogo->precioventa = $request->input('precioventa');
            }

            //$catalogo->precioventa = $request->input('precioventa');
            $catalogo->updated_at = $date;
    	
    	   $catalogo->update();
    	//$catalogo->fill($data);
    	//$catalogo->update();
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

        if ($this->getCountItemUtilizado($id) > 0) {

            return response()->json(['success' => false, 'exists' => true]);

        } else {
            $producto = Cont_CatalogItem::find($id);
            if (file_exists($producto->foto)) {
                unlink($producto->foto);
            }
            $producto->delete();
            return response()->json(['success' => true]);
        }


    }
    
    private function getCountItemUtilizado($id)
    {
        $whereRaw = '(idcatalogitem IN (SELECT idcatalogitem FROM cont_itemcompra) ';
        $whereRaw .= 'OR idcatalogitem IN (SELECT idcatalogitem FROM cont_itemventa) ';
        $whereRaw .= 'OR idcatalogitem IN (SELECT idcatalogitem FROM cont_itemactivofijo) ';
        $whereRaw .= 'OR idcatalogitem IN (SELECT idcatalogitem FROM cont_itemnotacreditfactura) ';
        $whereRaw .= 'OR idcatalogitem IN (SELECT idcatalogitem FROM cont_producto_bodega))';

        $count = Cont_CatalogItem::where('idcatalogitem', $id)
                                    ->whereRaw($whereRaw)->count();

        return $count;
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
    	
    	$filterCategorias = ($filter->linId != null)?" and cont_categoria.jerarquia <@ '".$filter->linId."'":"";
    	
    	$filterCategorias .= ($filter->subId != null)?" and cont_catalogitem.idcategoria = '".$filter->subId."'":"";
    	
    	$ltree = str_replace(' ','',$filter->text);
    	return  Cont_CatalogItem::orderBy('codigoproducto', 'asc')
    	->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idcategoria')
        ->join('cont_claseitem', 'cont_claseitem.idclaseitem', '=', 'cont_catalogitem.idclaseitem')
    	->whereRaw("( codigoproducto::text like '%" . $filter->text . "%' or nombreproducto ILIKE '%" . $filter->text . "%') ".$filterCategorias)
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
