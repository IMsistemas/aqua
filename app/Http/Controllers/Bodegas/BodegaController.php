<?php

namespace App\Http\Controllers\Bodegas;

use App\Modelos\Bodegas\Bodega;
use App\Modelos\Categoria;
use App\Modelos\Proveedores\Sectores;
use App\Modelos\Proveedores\Ciudades;
use App\Modelos\Proveedores\Provincias;
use App\Modelos\Empleado;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use DB;

class BodegaController extends Controller
{

    /**
     * Devolver la vista
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('bodegas.index_bodega');
    }

    /**
     * Obtener las provincias para filtro
     *
     * @return mixed
     */
    public function getProvincias()
    {
    	return Provincias::all();    	 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCiudades($provincia)
    {
    	$Provincia =  Provincias::find($provincia);
    	$Ciudades = $Provincia->ciudades;
    	return $Ciudades;
    
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSectores($ciudad)
    {
    	$Ciudad =  Ciudades::find($ciudad);
    	$Sectores = $Ciudad->sectores;
    	return $Sectores;
    
    }
    
    public function getEmpleado($nombre)
    {
    	return Empleado::whereRaw("nombres ilike '%".$nombre."%' or apellidos ilike '%".$nombre."%'")->get();
    
    }
    
    public function getEmpleadoByBodega($id)
    {
    	return Empleado::join('bodega', 'empleado.idempleado', '=', 'bodega.idempleado')
    				->join('cargo', 'empleado.idcargo', '=', 'cargo.idcargo')
    				->select('empleado.*','cargo.nombrecargo')
			    	->whereRaw("bodega.idbodega = '".$id."'")
			    	->first();
    
    }
    
    
    /**
     * Obtener las bodegas filtradas
     *
     * @param $filter
     * @return mixed
     */
    public function getBodegas($filter)
    {
    	$filter = json_decode($filter);
    	
    	$filterSector = ($filter->provinciaId != null)?" and provincia.idprovincia = ".$filter->provinciaId:"";
    	$filterSector .= ($filter->ciudadId != null)?" and ciudad.idciudad = ".$filter->ciudadId:"";
    	$filterSector .= ($filter->sectorId != null)?" and bodega.idsector = ".$filter->sectorId:"";
    	
    	return  Bodega::join('empleado', 'empleado.idempleado', '=', 'bodega.idempleado')
    					->join('sector', 'sector.idsector', '=', 'bodega.idsector')
    					->join('ciudad', 'sector.idciudad', '=', 'ciudad.idciudad')
    					->join('provincia', 'ciudad.idprovincia', '=', 'provincia.idprovincia')
                        ->select(
                        		DB::raw("(empleado.apellidos || ' ' || empleado.nombres) as bodeguero ")
                        		,'empleado.correo', 'bodega.*',
                        		DB::raw("(provincia.nombreprovincia||'/'||ciudad.nombreciudad||'/'||sector.nombreparroquia) as ubicacion"))
                            ->whereRaw("(bodega.idbodega ILIKE '%" . $filter->text . "%' 
                            		or (provincia.nombreprovincia||'/'||ciudad.nombreciudad||'/'||sector.nombreparroquia) ILIKE '%" . $filter->text . "%'                             		
                            		or (empleado.apellidos||' '||empleado.nombres) ILIKE '%" . $filter->text . "%' )                             		
                            		".$filterSector)
                            ->orderBy('bodega.idbodega', 'asc')
                            ->get();
    	
    }

    /**
     * Obtener base de la bodega nueva
     *
     * @return mixed
     */
    public function getLastBodega()
    {
        $bodega = new Bodega();		
		$bodega->idbodega = Bodega::max('idbodega') +1;
		$date = Carbon::Today();
		$bodega->fechaingreso = $date->format('Y-m-d');
		return $bodega;
    }
    
    public function getCategoriasHijas($filter)
    {
    	$filter = json_decode($filter);
    	return Categoria::orderBy('idcategoria', 'asc')
    	->whereRaw("nlevel(idcategoria) = ".$filter->nivel. " and idcategoria <@ '".$filter->padre."'")
    	->get();
    
    }    


    /**
     * Almacenar la bodega
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
    	$datos = $request->all();
    	unset($datos['fechaingreso']);
    	$result = Bodega::create($datos);
    	return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    	
    }

    /**
     * Mostrar un recurso bodega especifico.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return Bodega::join('sector', 'sector.idsector', '=', 'bodega.idsector')
    					->join('ciudad', 'sector.idciudad', '=', 'ciudad.idciudad')
    					->join('provincia', 'ciudad.idprovincia', '=', 'provincia.idprovincia')
                        ->select('bodega.*','ciudad.idciudad','provincia.idprovincia')
                        ->whereRaw("bodega.idbodega = '".$id."'")
                        ->first() ;
    }

    /**
     * Actualizar la bodega seleccionado
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
    	$bodega = Bodega::find($id);
    	$bodega->fill($request->all());
    	$bodega->save();
    	return response()->json(['success' => true]);
    }

    public function anularBodega($param)
    {
    	$param = json_decode($param);
    	$bodega = Bodega::find($param->id);
    	$bodega->estado = $param->estado;
    	$bodega->save();
    	return response()->json(['success' => true]);
    }
    

}
