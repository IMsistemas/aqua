<?php

namespace App\Http\Controllers\Bodegas;

use App\Modelos\Contabilidad\Cont_Bodega;
use App\Modelos\Categoria;
use App\Modelos\Proveedores\Sectores;
use App\Modelos\Proveedores\Ciudades;
use App\Modelos\Proveedores\Provincias;
use App\Modelos\Nomina\Empleado;

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
    	return Empleado::join('persona', 'persona.idpersona', '=', 'empleado.idpersona')
    				->whereRaw("persona.namepersona ilike '%".$nombre."%' or persona.lastnamepersona ilike '%".$nombre."%'")->get();
    
    }
    
    public function getEmpleadoByBodega($id)
    {
    	return Empleado::join('cont_bodega', 'empleado.idempleado', '=', 'cont_bodega.idempleado')
    				->join('persona', 'persona.idpersona', '=', 'empleado.idpersona')
    				->join('cargo', 'empleado.idcargo', '=', 'cargo.idcargo')
    				->select('empleado.*','cargo.namecargo','persona.*')
			    	->whereRaw("cont_bodega.idbodega = '".$id."'")
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
    	$filterSector .= ($filter->ciudadId != null)?" and canton.idcanton = ".$filter->ciudadId:"";
    	$filterSector .= ($filter->sectorId != null)?" and cont_bodega.idparroquia = ".$filter->sectorId:"";
    	
    	    	
    	return Cont_Bodega::join('empleado', 'empleado.idempleado', '=', 'cont_bodega.idempleado')
    					->join('persona', 'persona.idpersona', '=', 'empleado.idpersona')
    					->join('parroquia', 'parroquia.idparroquia', '=', 'cont_bodega.idparroquia')
    					->join('canton', 'parroquia.idcanton', '=', 'canton.idcanton')
    					->join('provincia', 'canton.idprovincia', '=', 'provincia.idprovincia')
                        ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cont_bodega.idplancuenta')
                        ->select(
                        		DB::raw("(persona.lastnamepersona || ' ' || persona.namepersona) as bodeguero ")
                        		,'persona.email', 'cont_bodega.*', 'cont_plancuenta.concepto',
                        		DB::raw("(provincia.nameprovincia||'/'||canton.namecanton||'/'||parroquia.nameparroquia) as ubicacion"))
                            ->whereRaw("(cont_bodega.idbodega::text ILIKE '%" . $filter->text . "%'
                                    or cont_bodega.namebodega ILIKE '%" . $filter->text . "%' 
                            		or (provincia.nameprovincia||'/'||canton.namecanton||'/'||parroquia.nameparroquia) ILIKE '%" . $filter->text . "%'                             		
                            		or (persona.lastnamepersona||' '||persona.namepersona) ILIKE '%" . $filter->text . "%' )                             		
                            		".$filterSector)
                            ->orderBy('cont_bodega.idbodega', 'asc')
                            ->get();
    	
    }

    /**
     * Obtener base de la bodega nueva
     *
     * @return mixed
     */
    public function getLastBodega()
    {
        $bodega = new Cont_Bodega();		
		$bodega->idbodega = Cont_Bodega::max('idbodega') +1;
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
    	$result = Cont_Bodega::create($datos);
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
        return Cont_Bodega::join('parroquia', 'parroquia.idparroquia', '=', 'cont_bodega.idparroquia')
    					->join('canton', 'parroquia.idcanton', '=', 'canton.idcanton')
    					->join('provincia', 'canton.idprovincia', '=', 'provincia.idprovincia')
                        ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cont_bodega.idplancuenta')
                        ->select('cont_bodega.*','canton.idcanton','provincia.idprovincia', 'cont_plancuenta.concepto')
                        ->whereRaw("cont_bodega.idbodega = '".$id."'")
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
    	$bodega = Cont_Bodega::find($id);
    	$bodega->fill($request->all());
    	$bodega->update();
    	return response()->json(['success' => true]);
    }

    public function anularBodega($param)
    {
    	$param = json_decode($param);
    	$bodega = Cont_Bodega::find($param->id);
    	$bodega->estado = $param->estado;
    	$bodega->save();
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
    	
    		$bodega = Cont_Bodega::find($id);
    		$bodega->delete();
    		return response()->json(['success' => true]);
    	
    }

}
