<?php 
namespace App\Http\Controllers\Sectores;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Modelos\Sectores\Provincia;


class ProvinciaController extends Controller
{
	public function index()
	{
		return $provincias=DB::table('provincia')->orderBY('idprovincia')->get();
	}

	public function show($idprovincia)
	{
		return $provincia=DB::table('provincia')->where('idprovincia',$idprovincia)->get();
	}
	public function maxId()
	{
		$provincia=Provincia::max('idprovincia');
		if($provincia==NULL){
			$provincia='PRO00001';
		}else{
			$identificadorLetras=substr($provincia, 0,-5);//obtiene las tetras del provincia de Provincia
			$identificadorNumero=substr($provincia, 3); //obtiene las tetras del provincia de Provincia
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
			
			$provincia=$identificadorLetras.$identificadorNumero;
			return $provincia;
			
		}
	}


	public function postCrearProvincia(Request $request)
	{

		$provincia= new Provincia;
		$provincia->idprovincia = $request->input('idprovincia');
		$provincia->nombreprovincia = $request->input('nombreprovincia');
		$provincia->save();
		return 'El Provincia fue creado correctamente con su código  '.$provincia->idprovincia;
	}


	public function postActualizarProvincia(Request $request,$idprovincia)
	{
		$provincia = Provincia::find($idprovincia);
		$provincia->nombreprovincia = $request->input('nombreprovincia');
		$provincia->save();
		return "Se actualizo correctamente".$provincia->idprovincia;
	}

	public function destroy($idprovincia)
	{
		$provincia = Provincia::find($idprovincia);
		$provincia->delete();
		return "Se elimino correctamente".$idprovincia;
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}

}
