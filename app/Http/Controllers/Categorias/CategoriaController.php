<?php

namespace App\Http\Controllers\Categorias;
use App\Modelos\Contabilidad\Cont_Categoria;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
    	$resultado = DB::table('cont_categoria')
                  ->select(DB::raw('subpath(jerarquia,-1,1) as nivel'))
                  ->whereRaw("nlevel(jerarquia) =".$id )
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
    	 return Cont_Categoria::orderBy('jerarquia', 'asc')
    	 	->whereRaw('nlevel(jerarquia) = 1')
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
    	$resultado = DB::table('cont_categoria')
    	->select(DB::raw('subpath(jerarquia,-1,1) as nivel'))
    	->whereRaw("jerarquia <@ '".$id."' and nlevel(jerarquia) = ".$nivelNumerico)    	
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
        $filterCategorias = ($filter->catId != null)?" and jerarquia <@ '".$filter->catId."'":"";  
        $ltree = str_replace(' ','',$filter->text);
        $array =  Cont_Categoria::orderBy('jerarquia', 'asc')
    	 		->whereRaw("( jerarquia <@ '".$ltree."' OR nombrecategoria ILIKE '%" . $filter->text . "%') ".$filterCategorias)       
                ->get();
        
        $ids = array();
        $items = array();
        foreach($array as $key => $val) {
          	$ids[$key] = $val['jerarquia'];
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
    	$data = $request->all();
    	$date = Carbon::Today();
    	$data['created_at'] = $data['updated_at']  = $date;		
        $result = Cont_Categoria::create($data);
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
        $categoria = Cont_Categoria::find($id);
        return response()->json($categoria);
    }
    
    public function getCategoriaToDelete($id)
    {    	
    	$categorias = Cont_Categoria::orderBy('jerarquia', 'asc')
    	->whereRaw("jerarquia <@ '".$id ."'")
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
        $date = Carbon::Today();
        
        foreach ($categorias as $item) {
            $categoria = Cont_Categoria::find($item->idcategoria);
            $categoria->nombrecategoria = $item->nombrecategoria;  
            $categoria->updated_at = $date;
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
    	$categorias = Cont_Categoria::orderBy('jerarquia', 'asc')
    	 		->whereRaw("jerarquia <@ '".$id ."'")       
                ->get();    	
    	foreach ($categorias as $item){
    		$item->delete();
    	}
       
        return response()->json(['success' => true]);
    }
}
