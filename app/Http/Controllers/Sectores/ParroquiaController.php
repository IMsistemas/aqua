<?php 
namespace App\Http\Controllers\Sectores;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Modelos\Sectores\Parroquia;

class ParroquiaController extends Controller
{
	public function index($idcanton)
	{
		return $parroquias=DB::table('parroquia')->where('idcanton',$idcanton)->orderBy('idparroquia')->get();
	}
	public function show($idparroquia)
	{
		return $parroquia=DB::table('parroquia')->where('idparroquia',$idparroquia)->get();
	}
	public function maxId()
	{
		
		$parroquia=Parroquia::max('idparroquia');
				
		if($parroquia==NULL){
			$parroquia='PAR00001';
		}else{
			$identificadorLetras=substr($parroquia, 0,-5);//obtiene las tetras del parroquia de Provincia
			$identificadorNumero=substr($parroquia, 3); //obtiene las tetras del parroquia de Provincia
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
			
			$parroquia=$identificadorLetras.$identificadorNumero;
			return $parroquia;
			
		}
	}

	public function postCrearParroquia(Request $request,$idcanton)
	{
		$parroquia= new Parroquia;
		$parroquia->idparroquia = $request->input('idparroquia');
		$parroquia->idcanton = $idcanton;
		$parroquia->nombreparroquia = $request->input('nombreparroquia');
		$parroquia->save();
		return 'La Parroquia fue creada correctamente con su código '.$parroquia->idparroquia;
	}

	public function postActualizarParroquia(Request $request,$idparroquia)
	{

		$parroquia=DB::table('parroquia')->where('idparroquia',$idparroquia)->get();
		$nombreparroquiaupdate=$parroquia[0]->nombreparroquia =$request->input('nombreparroquia');
		$result = DB::table('parroquia')->where('idparroquia', $idparroquia)->update(array('nombreparroquia' => $nombreparroquiaupdate));
		//return $result;
		return "Se actualizo correctamente".$parroquia[0]->idparroquia;
	}

	public function destroy($idparroquia)
	{
		$parroquia=DB::table('parroquia')->where('idparroquia',$idparroquia)->get();
		$result = DB::table('parroquia')->where('idparroquia', $idparroquia)->delete();
		return "Se elimino correctamente".$idparroquia;
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}

}
