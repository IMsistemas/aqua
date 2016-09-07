<?php 
namespace App\Http\Controllers\Sectores;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modelos\Sectores\Canton;
use App\Modelos\Sectores\Provincia;
use App\Modelos\Sectores\Parroquia;

class ParroquiaController extends Controller
{
	public function index($idcanton)
	{
		return $parroquias=DB::table('parroquia')->where('idcanton',$idcanton)->get();
	}

	public function getCrearParroquia(Request $request)
	{
		
		$parroquia=DB::table('parroquia')->orderBy('idparroquia')->get();
		$length = count($parroquia);
				
		if($parroquia==NULL){
			$idParroquia='PAR00001';
		}else{
			$idParroquia=$parroquia[$length-1]->idparroquia;
			$identificadorLetras=substr($idParroquia, 0,-5);//obtiene las tetras del idParroquia de Provincia
			$identificadorNumero=substr($idParroquia, 3); //obtiene las tetras del idParroquia de Provincia
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
			
			$idParroquia=$identificadorLetras.$identificadorNumero;
			
		}
		
		$idCanton=$request->get('idcanton');
		$canton=Canton::Select('nombrecanton')->where('idcanton',$request->get('idcanton'))->get();
		$nombreCanton=$canton[0]->nombrecanton;
		

		return view('parroquias.crear-parroquia', ['idCanton' => $idCanton,'nombreCanton' => $nombreCanton,'idParroquia' => $idParroquia]);
	}

	public function postCrearParroquia(CrearParroquiaRequest $request)
	{
		Parroquia::create
		(
			[
				'idparroquia' => $request->get('idparroquia'),
				'idcanton' => $request->get('idcanton'),
				'nombreparroquia' => $request->get('nombreparroquia'),
			]
		);


		$idcanton=$request->get('idcanton');
		

		return redirect("/validado/parroquias?idcanton=$idcanton")->with('creado', 'El Barrio ha sido creado');
	}

	public function getActualizarParroquia($idparroquia)
	{
		$parroquia = Parroquia::find($idparroquia);		
		$canton=Canton::Select('nombrecanton')->where('idcanton',$parroquia->idcanton)->get();
		$nombreCanton=$canton[0]->nombreprovincia;
		return view('parroquias.actualizar-parroquia',['parroquia' => $parroquia,'nombreCanton' => $nombreCanton]);
	}

	public function postActualizarParroquia(ActualizarParroquiaRequest $request)
	{
		$parroquia = Parroquia::find($request->get('idparroquia'));
		$parroquia->nombreparroquia = $request->get('nombreparroquia');
		$parroquia->save();
		return redirect("/validado/parroquias?idcanton=$parroquia->idcanton")->with('actualizado', 'El parroquia se actualizó');
	}

	public function postEliminarParroquia(EliminarParroquiaRequest $request)
	{
		$parroquia = Parroquia::find($request->get('idparroquia'));
		$idcanton=$parroquia->idcanton;
		$parroquia->barrios()->delete();
		$parroquia->delete();
		return redirect("/validado/parroquias?idcanton=$idcanton")->with('eliminado', 'el parroquia fue eliminado');
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}

}
