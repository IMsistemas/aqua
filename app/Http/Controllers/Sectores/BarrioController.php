<?php 
namespace App\Http\Controllers\Sectores;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modelos\Sectores\Provincia;
use App\Modelos\Sectores\Canton;
use App\Modelos\Sectores\Parroquia;
use App\Modelos\Sectores\Barrio;

class BarrioController extends Controller
{
	public function index($idparroquia)
	{
		return $barrios=DB::table('barrio')->where('idparroquia',$idparroquia)->get();
	}
	public function show($idbarrio)
	{
		return $barrio=DB::table('barrio')->where('idbarrio',$idbarrio)->get();
	}

	public function getCrearBarrio(Request $request)
	{
		$barrio=DB::table('barrio')->orderBy('idbarrio')->get();
		$length = count($barrio);
				
		if($barrio==NULL){
			$idBarrio='BAR00001';
		}else{
			$idBarrio=$barrio[$length-1]->idbarrio;
			$identificadorLetras=substr($idBarrio, 0,-5);//obtiene las tetras del idBarrio de Provincia
			$identificadorNumero=substr($idBarrio, 3); //obtiene las tetras del idBarrio de Provincia
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
			
			$idBarrio=$identificadorLetras.$identificadorNumero;
			
		}
		
		$idParroquia=$request->get('idparroquia');
		$parroquia=parroquia::Select('nombreparroquia')->where('idparroquia',$idParroquia)->get();
		$nombreParroquia=$parroquia[0]->nombreparroquia;
		
		return view('barrios.crear-barrio', ['idParroquia' => $idParroquia,'nombreParroquia' => $nombreParroquia,'idBarrio' => $idBarrio]);
	}

	public function postCrearBarrio(Request $request,$idparroquia)
	{
		$barrio= new Barrio;
		$barrio->idbarrio = $request->input('idbarrio');
		$barrio->idparroquia = $idparroquia;
		$barrio->nombrebarrio = $request->input('nombrebarrio');
		$barrio->save();
		return 'El barrio fue creado correctamente con su documento de identidad'.$barrio->idbarrio;
	}

	public function getActualizarBarrio($idbarrio)
	{
		$barrio = Barrio::find($idbarrio);
		$parroquia=Parroquia::Select('nombreparroquia')->where('idparroquia',$barrio->idparroquia)->get();
		$nombreParroquia=$parroquia[0]->nombreparroquia;
		return view('barrios.actualizar-barrio', ['barrio' => $barrio]);
	}

	public function postActualizarBarrio(ActualizarBarrioRequest $request)
	{
		$barrio = Barrio::find($request->get('idbarrio'));
		$barrio->nombrebarrio = $request->get('nombrebarrio');
		$barrio->save();
		return redirect("/validado/barrios?idparroquia=$barrio->idparroquia")->with('actualizado', 'El barrio se actualizó');

	}

	public function postEliminarBarrio(EliminarBarrioRequest $request)
	{
		$barrio = Barrio::find($request->get('idbarrio'));
		$idparroquia=$barrio->idparroquia;
		$barrio->calles()->delete();
		$barrio->delete();
		return redirect("/validado/barrios?idparroquia=$idparroquia")->with('eliminado', 'el Barrio fue eliminado');
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}

	/*=============================Kevin Tambien :-( =========================*/
	public function getBarriosCalles(){
		return Barrio::with('calle')->get();
	}

}
