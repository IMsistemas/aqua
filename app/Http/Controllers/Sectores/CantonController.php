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
	public function index()
	{
		
		return //Canton::with('provincia')->get();//
		$cantones=DB::table('canton')->get();
		//with('canton.idcanton','provincia.nombreprovincia','canton.nombrecanton')->get();
	}

	public function show($idcanton)
	{
		return $canton=DB::table('canton')->where('idcanton',$idcanton)->get();
	}

	public function getUltimoCodigoCanton(Request $request)
	{
		
		$canton=DB::table('canton')->orderBy('idcanton')->get();
		$length = count($canton);
				
		if($canton==NULL){
			$idCanton='CAN00001';
		}else{
			$idCanton=$canton[$length-1]->idcanton;
			$identificadorLetras=substr($idCanton, 0,-5);//obtiene las tetras del idcanton de canton
			$identificadorNumero=substr($idCanton, 3); //obtiene las tetras del idcanton de canton
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
			
			$idCanton=$identificadorLetras.$identificadorNumero;
			
		}
		
		$idcanton=$request->get('idcanton');
		$canton=canton::Select('nombrecanton')->where('idcanton',$request->get('idcanton'))->get();
		$nombrecanton=$canton[0]->nombrecanton;
		

		return view('cantones.crear-canton', ['idCanton' => $idCanton,'nombrecanton' => $nombrecanton,'idcanton' => $idcanton]);
	}

	public function postCrearCanton(CrearCantonRequest $request)
	{
	

		Canton::create
		(
			[
				'idcanton' => $request->get('idcanton'),
				'idcanton' => $request->get('idcanton'),
				'nombrecanton' => $request->get('nombrecanton'),
			]
		);


		$idcanton=$request->get('idcanton');
		

		return redirect("/validado/cantones?idcanton=$idcanton")->with('creado', 'El Barrio ha sido creado');
	}

	public function getActualizarCanton($idcanton)
	{
		$canton = Canton::find($idcanton);
		$canton=canton::Select('nombrecanton')->where('idcanton',$canton->idcanton)->get();
		$nombrecanton=$canton[0]->nombrecanton;
		return view('cantones.actualizar-canton', ['canton' => $canton,'nombrecanton' => $nombrecanton]);
	}

	public function postActualizarCanton(ActualizarCantonRequest $request)
	{
		$canton = Canton::find($request->get('idcanton'));
		$canton->idcanton=$request->get('idcanton');
		$canton->nombrecanton=$request->get('nombrecanton');
		$canton->save();
		return redirect("/validado/cantones?idcanton=$canton->idcanton")->with('actualizado', 'El canton se actualizó');
	}

	public function postEliminarCanton(EliminarCantonRequest $request)
	{
		$canton = Canton::find($request->get('idcanton'));
		$parroquia=DB::table('parroquia')->where('idcanton',$request->get('idcanton'))->get();
		$idparroquia=$parroquia[0]->idparroquia;
		$barrios=DB::table('barrio')->where('idparroquia',$idparroquia)->get();
		$barrio=Barrio::find($barrios[0]->idbarrios);
		$idcanton=$canton->idcanton;
		$barrio->calles()->delete();
		$parroquia->barrios()->delete();
		$canton->parroquias()->delete();
		$canton->delete();
		return redirect("/validado/cantones?idcanton=$idcanton")->with('eliminado', 'el canton fue eliminado');
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}

}
