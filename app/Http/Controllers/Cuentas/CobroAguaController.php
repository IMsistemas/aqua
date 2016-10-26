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

  /**
  *Retorna las cuentas dependiendo del la fecha solicitada
  **/
    public function index(){
        $fechaPrimerDia = $this->fechaPrimerDia();
        $fechaUltimoDia = $this->fechaUltimoDia();

        return CobroAgua::with('suministro.cliente','suministro.tarifa','lectura')
        ->whereBetween('fechaperiodo',[$fechaPrimerDia,$fechaUltimoDia])
        ->get();
    }

    /**
    *Genera las facuras del presente periodo, solo si aun no se han generado las facturas para el presente periodo
    **/
    public function generarFacturas(){
        $suministros = Suministro::all();
        foreach ($suministros as $suministro) {
            $nuevoCobro = new CobroAgua();
            $nuevoCobro->fechaperiodo = date("Y-m-d H:i:s");;
            $nuevoCobro->numerosuministro = $suministro->numerosuministro;
            $nuevoCobro->estapagada = false;
            $nuevoCobro->save();   
        }
        $this->agregarOtrosRubros();
        return [];
     }

     /**
     *Genera campos de costo 0 para la tabla debiles RubrosVariablesCuenta, en el caso de RubrosFijosCuenta se carga el costo de la tabla RubroFijo
     **/
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

    /**
    *Guarda los rubros fijos y variables para antes de la toma de lecturas
    **/
    public function guardarRubros(Request $request,$idcuenta){
        $cobroAgua = CobroAgua::find($idcuenta);

        $rubrosVariablesNuevos = $request->input('rubrosvariables');
        $rubrosFijosNuevos = $request->input('rubrosfijos');

        $rubrosFijosCuenta = $cobroAgua->rubrosfijos();
        $rubrosVariablesCuenta = $cobroAgua->rubrosvariables();

        

        foreach ($rubrosVariablesNuevos as $rubroVariable) {
          $rubrosVariablesCuenta->updateExistingPivot(
            $rubroVariable['idrubrovariable'],['costorubro' => $rubroVariable['costorubro'] ]
          );     
        }
    }


    public function pagarCuenta($idcuenta){
      $cobroAgua = CobroAgua::find($idcuenta);
      $cobroAgua->estapagada = true;
      $cobroAgua->save();
      return[];

    }
   

  	/**
  	*Retorna una cuenta con el suministro, el dueño del suministro su tarifa y sus rubros variables
  	**/
  	public function getCuenta($numeroCuenta){
      $elCobro = CobroAgua::with('suministro.cliente','suministro.tarifa','suministro.calle.barrio','lectura','rubrosvariables','rubrosfijos')->where('idcuenta',$numeroCuenta)->get(); 
      return $elCobro;
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

  public function generarPDF($numeroCuenta){

    $elCobro = CobroAgua::with('suministro.cliente','suministro.tarifa','suministro.calle.barrio','lectura','rubrosvariables','rubrosfijos')->where('idcuenta',$numeroCuenta)->get(); 
    $rubrosfijos=$elCobro->rubrosfijos;
    $totalRubrosFijos=0;
    foreach ($rubrosfijos as $rubrosfijo) {
        $totalRubrosFijos=$totalRubrosFijos+$rubrosfijo->pivot->costorubro;
    }
    $rubrosvariables=$elCobro->rubrosvariables;
    $totalRubrosVariables=0;
    foreach ($rubrosvariables as $rubrosvariable) {
        $totalRubrosVariables=$totalRubrosVariables+$rubrosvariable->pivot->costorubro;
    }
    $totalcuenta=$elCobro->valorconsumo+$elCobro->valorexedente+$elCobro->valorconsumo+$elCobro->valormesesatrasados+$totalRubrosFijos+$totalRubrosVariables;
    

    $data =  [
          'razonsocial'=>$elCobro->cliente->apellido." ".$elCobro->cliente->nombre,
          'documentoidentidad'    => $elCobro->cliente->documentoidentidad,
          'numerosuministro'        => $elCobro->suministro->numerosuministro,
          'direccionsuministro'   => $elCobro->suministro->direccionsuministro,
          'telefonosuministro'   => $elCobro->suministro->telefonosuministro,
          'fechaactual'    => $elCobro->telefonosuministro,

          'periodo'    => $elCobro->fechaperiodo,
          'lecturaanterior'               => $elCobro->lectura->lecturaanterior,

          'lecturaactual'      => $elCobro->lectura->lecturaactual,
          'consumo'                => $elCobro->lectura->consumo, 
          'subrosfijos'=>$elCobro->rubrosfijos,
          'subrosvariables'=>$elCobro->rubrosvariables,

          'totalcuenta' =>$totalcuenta, 


        ];

           //dd($cuentaPorCobrar);
        $view =  \View::make('factura', compact('data'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('factura');

    }
    
}
