<?php 
namespace App\Http\Controllers\Sectores;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modelos\Sectores\Provincia;



class ProvinciaController extends Controller
{
	public function index()
	{
		return $provincias=DB::table('provincia')->get();
	}

	public function show($idprovincia)
	{
		return $provincia=DB::table('provincia')->where('idprovincia',$idprovincia)->get();
	}

	public function getUltimoCodigoProvincia()
	{
		$provincia=DB::table('provincia')->orderBy('idprovincia')->get();
		$length = count($provincia);
		$provincia=DB::max('idprovincia');
				
		if($provincia==NULL){
			$idProvincia='PRO00001';
		}else{
			$idProvincia=$provincia[$length-1]->idprovincia;
			$identificadorLetras=substr($idProvincia, 0,-5);//obtiene las tetras del idProvincia de Provincia
			$identificadorNumero=substr($idProvincia, 3); //obtiene las tetras del idProvincia de Provincia
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
			
			$idProvincia=$identificadorLetras.$identificadorNumero;
			
		}
		return $provincia=DB::table('provincia')->get();
		//$provincia->idprovincia =$idProvincia;
		//return $provincia;
	}

	public function postCrearProvincia(Request $request)
	{

		$provincia= new Provincia;
		$provincia->idprovincia = $request->input('idprovincia');
		$provincia->nombreprovincia = $request->input('nombreprovincia');
		$provincia->save();
		return 'El Provincia fue creado correctamente con su documento de identidad'.$provincia->idprovincia;
	}


	public function postActualizarProvincia(Request $request)
	{
		$provincia = Provincia::find($request->input('idprovincia'));
		$provincia->idprovincia = $request->input('idprovincia');
		$provincia->nombreprovincia = $request->input('nombreprovincia');

		$provincia->save();
		return "Se actualizo correctamente".$provincia->idprovincia;

	}

	public function postEliminarProvincia(EliminarProvinciaRequest $request)
	{
		$provincia = Provincia::find($request->get('idprovincia'));
		$provincia->cantones()->delete();
		$provincia->delete();
		return redirect('/validado/provincias')->with('eliminado', 'el Barrio fue eliminado');
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}

}
