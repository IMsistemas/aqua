<?php

namespace App\Http\Controllers\Categorias;
use App\Modelos\Categoria;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoriaController extends Controller
{
    /**
     * Mostrar una lista de los recursos de categorias
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('categorias.index_categoria');
    }

    /**
     * Obtener la ultima categoria
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function lastCategoria($id)
    {    	
    	$resultado = DB::table('categoria')
                  ->select(DB::raw('subpath(idcategoria,-1,1) as nivel'))
                  ->whereRaw("nlevel(idcategoria) =".$id )
    			  ->get();
    	$lastID = 0;
    	foreach ($resultado as $item){
    		if($item->nivel > $lastID){
    			$lastID = $item->nivel;
    		}
    	}
    	$lastID ++;
        return response()->json(['lastId' => $lastID]);
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
     * Obtener la ultima subcategoria
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function lastSubCategoria($id)
    {
    	$nivel = str_replace('.', '', $id);
    	$nivelNumerico = strlen($nivel) + 1; 
    	$resultado = DB::table('categoria')
    	->select(DB::raw('subpath(idcategoria,-1,1) as nivel'))
    	->whereRaw("idcategoria <@ '".$id."' and nlevel(idcategoria) = ".$nivelNumerico)    	
    	->get();
    	$lastID = 0;
    	foreach ($resultado as $item){
    		if($item->nivel > $lastID){
    			$lastID = $item->nivel;
    		}
    	}
    	$lastID ++;
    	return response()->json(['lastId' => $lastID]);
    }

    /**
     * Obtener las categorias filtradas
     *
     * @param $filter
     * @return mixed
     */
    public function getByFilter($filter)
    {
        $filter = json_decode($filter);
        $filterCategorias = ($filter->catId != null)?" and idcategoria <@ '".$filter->catId."'":"";  
        $ltree = str_replace(' ','',$filter->text);
        $array =  Categoria::orderBy('idcategoria', 'asc')
    	 		->whereRaw("( idcategoria <@ '".$ltree."' OR nombrecategoria ILIKE '%" . $filter->text . "%') ".$filterCategorias)       
                ->get();
        
        $ids = array();
        $items = array();
        foreach($array as $key => $val) {
          	$ids[$key] = $val['idcategoria'];
           	$items[$key] = $val;
        }
        array_multisort($ids, SORT_NATURAL, $items);
        return $items;
                
    }

    /**
     * Almacenar una categoria recién creada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = Categoria::create($request->all());
        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

    /**
     * Mostrar una categoria especifica.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoria = Categoria::find($id);
        return response()->json($categoria);
    }
    
    public function getCategoriaToDelete($id)
    {    	
    	$categorias = Categoria::orderBy('idcategoria', 'asc')
    	->whereRaw("idcategoria <@ '".$id ."'")
    	->get();
    	$categoria = $categorias[0];
    	$categoria->hijos = count($categorias);
    	return response()->json($categoria);
    }

    

    /**
     * Actualiza las categorias actualizadas
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($request)
    {        
        $categorias = json_decode($request);
        foreach ($categorias as $item) {
            $categoria = Categoria::find($item->idcategoria);
            $categoria->nombrecategoria = $item->nombrecategoria;            
            $categoria->save();
        }
        return response()->json(['success' => true]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$categorias = Categoria::orderBy('idcategoria', 'asc')
    	 		->whereRaw("idcategoria <@ '".$id ."'")       
                ->get();    	
    	foreach ($categorias as $item){
    		$item->delete();
    	}
       
        return response()->json(['success' => true]);
    }
}
