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
use App\Modelos\Sectores\Calle;

class CalleController extends Controller
{
	public function index()
	{
		return $calles=DB::table('calle')->get();
	}

	public function show($idcalle)
	{
		return $calle=DB::table('calle')->where('idcalle',$idcalle)->get();
	}

	public function getCrearCalle(Request $request)
	{
		$calle=DB::table('calle')->orderBy('idcalle')->get();

		$length = count($calle);
				
		if($calle==NULL){
			$idCalle='CAL00001';
		}else{
			$idCalle=$calle[$length-1]->idcalle;
			$identificadorLetras=substr($idCalle, 0,-5);//obtiene las tetras del idCalle de Provincia
			$identificadorNumero=substr($idCalle, 3); //obtiene las tetras del idCalle de Provincia
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
			
			$idCalle=$identificadorLetras.$identificadorNumero;
			
		}
		
		$idBarrio=$request->get('idbarrio');
		$barrio=Barrio::Select('nombrebarrio')->where('idbarrio',$idBarrio)->get();
		$nombreBarrio=$barrio[0]->nombrebarrio;
		
		return view('calles.crear-calle', ['idBarrio' => $idBarrio,'nombreBarrio' => $nombreBarrio,'idCalle' => $idCalle]);
	}

	public function postCrearCalle(CrearCalleRequest $request)
	{
		Calle::create
		(
			[
				'idcalle' => $request->get('idcalle'),
				'idbarrio' => $request->get('idbarrio'),
				'nombrecalle' => $request->get('nombrecalle'),
			]
		);
		$idbarrio=$request->get('idbarrio');

		return redirect("/validado/calles?idbarrio=$idbarrio")->with('creado', 'El Barrio ha sido creado');
	}

	public function getActualizarCalle($idcalle)
	{
		$calle = Calle::find($idcalle);
		$barrio=Barrio::Select('nombrebarrio')->where('idbarrio',$calle->idbarrio)->get();
		$nombreBarrio=$barrio[0]->nombrebarrio;
		return view('calles.actualizar-calle', ['calle' => $calle,'nombreBarrio' => $nombreBarrio]);
	}

	public function postActualizarCalle(ActualizarCalleRequest $request)
	{
		$calle = Calle::find($request->get('idcalle'));
		$calle->nombrecalle = $request->get('nombrecalle');
		$calle->referencia = $request->get('referencia');
		$calle->save();
		return redirect("/validado/calles?idbarrio=$calle->idbarrio")->with('actualizado', 'El barrio se actualizó');

	}

	public function postEliminarCalle(EliminarCalleRequest $request)
	{

		$calle = Calle::find($request->get('idcalle'));
		$idbarrio=$calle->idbarrio;
		$calle->delete();
		return redirect("/validado/calles?idbarrio=$idbarrio")->with('eliminado', 'la calle fue eliminado');
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}

}