<?php 
namespace App\Http\Controllers\Sectores;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modelos\Sectores\Canton;
use App\Modelos\Sectores\Provincia;

class CantonController extends Controller
{
	public function index($idprovincia)
	{
		return $cantones=DB::table('canton')->where('idprovincia',$idprovincia)->get();
	}

	public function show($idcanton)
	{
		return $canton=DB::table('canton')->where('idcanton',$idcanton)->get();
	}

	public function maxId()
	{
		
		$canton=Canton::max('idcanton');
				
		if($canton==NULL){
			$canton='CAN00001';
		}else{
			$identificadorLetras=substr($canton, 0,-5);//obtiene las tetras del canton de canton
			$identificadorNumero=substr($canton, 3); //obtiene las tetras del canton de canton
			$identificadorNumero=$identificadorNumero+1;
			$longitudNumero =strlen($identificadorNumero);//obtiene el número de caracteres existentes
			//asigna el identificador numerico del siguiente registro
			switch ($longitudNumero) {
    	     	case 1:
        		$identificadorNumero='0000'.$identificadorNumero;
             	break;
    	    	case 2:
        		$identificadorNumero='000'.$identificadorNumero;
             	break;
             	case 3:
        		$identificadorNumero='00'.$identificadorNumero;
             	break;
             	case 4:
        		$identificadorNumero='0'.$identificadorNumero;
             	break;
			}
			
			$canton=$identificadorLetras.$identificadorNumero;
			return $canton;
			
		}
	}

	public function postCrearCanton(Request $request, $idprovincia)
	{
		$canton= new Canton;
		$canton->idcanton = $request->input('idcanton');
		$canton->idprovincia = $idprovincia;
		$canton->nombrecanton = $request->input('nombrecanton');
		$canton->save();
		return 'El Canton fue creado correctamente con su documento de identidad'.$canton->idcanton;
	}

	public function postActualizarCanton(Request $request,$idcanton)
	{
		$canton = Canton::find($idcanton);
		$canton->nombrecanton = $request->input('nombrecanton');
		$canton->save();
		return "Se actualizo correctamente".$canton->idcanton;
	}

	public function destroy($idcanton)
	{
		$canton = Canton::find($idcanton);
		$canton->delete();
		return "Se elimino correctamente".$idcanton;
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}

}
