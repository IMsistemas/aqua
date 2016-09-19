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
	public function index($idbarrio)
	{
		return $calles=DB::table('calle')->where('idbarrio',$idbarrio)->get();
	}

	public function mostrar($idcalle){
		return $calle=DB::table('calle')->where('idcalle',$idcalle)->get();
	}

	public function maxId()
	{
		$calle=Calle::max('idcalle');
				
		if($calle==NULL){
			$calle='CAL00001';
		}else{
			$identificadorLetras=substr($calle, 0,-5);//obtiene las tetras del calle de Provincia
			$identificadorNumero=substr($calle, 3); //obtiene las tetras del calle de Provincia
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
			
			$calle=$identificadorLetras.$identificadorNumero;
			return $calle;
		}	
		
	}

	public function postCrearCalle(Request $request,$idbarrio)
	{
		$calle= new Calle;
		$calle->idcalle = $request->input('idcalle');
		$calle->idbarrio = $idbarrio;
		$calle->nombrecalle = $request->input('nombrecalle');
		$calle->save();
		return 'El calle fue creada exitosamente';
	}

	public function postActualizarCalle(Request $request,$idcalle)
	{
		$calle = Calle::find($idcalle);
		$calle->nombrecalle = $request->input('nombrecalle');
		$calle->save();
		return "Se actualizo exitosamente";

	}

	public function destroy($idcalle)
	{
		$calle = Calle::find($idcalle);
		$calle->delete();
		return "Se elimino exitosamente";	
		/*$calle = Calle::find($request->get('idcalle'));
		$idbarrio=$calle->idbarrio;
		$calle->delete();
		return redirect("/validado/calles?idbarrio=$idbarrio")->with('eliminado', 'la calle fue eliminado');*/
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}

}