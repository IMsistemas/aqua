<?php
 
namespace App\Http\Controllers\Cuentas;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Cuentas\RubroFijo;
use App\Modelos\Cuentas\RubroVariable;
use App\Modelos\Suministros\Suministro;
use App\Modelos\Clientes\Cliente;
use App\Modelos\Tarifas\Tarifa;
use App\Modelos\Sectores\Calle;

class CobroAguaController extends Controller
{

	/**
	=========================================Kevin======================================================
	**/ 

    public function generarFacturas(){
        $suministros = Suministro::all();
        foreach ($suministros as $suministro) {
            $nuevoCobro = new CobroAgua();
            $nuevoCobro->fechaperiodo = date("Y-m-d H:i:s");;
            $nuevoCobro->numerosuministro = $suministro->numerosuministro;
            $nuevoCobro->save();   
        }
        $this->agregarOtrosRubros();
        return [];
    }

    private function agregarOtrosRubros(){
      $rubrosVariables = RubroVariable::all();
      $rubrosFijos = RubroFijo::all();
      $cobrosAgua = CobroAgua::all();
      foreach ($cobrosAgua as $cobroAgua) {
        foreach ($rubrosVariables as $rubroVariable) {
          $cobroAgua->rubrosvariables()->attach($rubroVariable->idrubrovariable,['costorubro' => 0]);
        }
        foreach ($rubrosFijos as $rubroFijo) {
          $cobroAgua->rubrosfijos()->attach($rubroFijo->idrubrofijo,['costorubro' => $rubroFijo->costorubro]);
        }
        
      }
    }   

    public function index(){
        $fechaPrimerDia = $this->fechaPrimerDia();
        $fechaUltimoDia = $this->fechaUltimoDia();

        return CobroAgua::with('suministro.cliente','suministro.tarifa','lectura')
        ->whereBetween('fechaperiodo',[$fechaPrimerDia,$fechaUltimoDia])
        ->get();
    }

	/**
	*Retorna todas las cuentas con los suministros, los clientes y tarifas del suministro
	**/
	public function getCuentas(){
		return dd(CobroAgua::with('suministro.cliente','suministro.tarifa','suministro.calle.barrio','lectura')->get());
	}

	/**
	*Retorna una cuenta con el suministro, el dueño del suministro y su tarifa
	**/
	public function getCuenta($numeroCuenta){
    return CobroAgua::with('suministro.cliente','suministro.tarifa','suministro.calle.barrio','lectura','rubrosfijos','rubrosvariables')->where('idcuenta',$numeroCuenta)->get(); 
  }

  public function getRubrosVariablesCuenta(){

  }
    /**
  }
	*Retorna los rubros variables de la junta
	**/
    public function getRubrosVariables(){
    	return RubroVariable::all();
    }

    /**
	*Retorna los rubros fijos de la junta
	**/
    public function getRubrosFijos(){
    	return RubroFijo::all();
    }

    /**
	*Guarda los rubros fijos y variables para antes de la toma de lecturas
	**/
    public function guardarRubros(Request $request,$numeroCobro){
        $cobroAgua = CobroAgua::find($numeroCobro);
        $rubrosFijos = $cobroAgua->rubrosfijos;
        $rubrosVariables = $cobroAgua->rubrosvariables;
        
        $rubrosfijos->idcuenta = $numeroCobro;
        $rubrosfijos->idrubrofijo = $request->input('');
        $rubrosfijos->costorubro = $request->input('costoRubroFijo');
       
        $rubrosvariables->idcuenta = $numeroCobro;
        $rubrosvariables->idrubrovariable = $request->input('');
        $rubrosvariables->idrubrovariable = $request->input('costoRubroFijo');

        $rubrosfijos->save();
        $rubrosvariables->save();

        return 'Se agregaron los valores de otros rubros con exito';

    }



  /** ultimo dia del mes **/
  private function fechaUltimoDia() { 
      $month = date('m');
      $year = date('Y');
      $day = date("d", mktime(0,0,0, $month+1, 0, $year));
 
      return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
  }
 
  /** primero dia del mes **/
  private function fechaPrimerDia() {
      $month = date('m');
      $year = date('Y');
      return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
  }

    /**
	====================================================================================================
	**/
    
}
