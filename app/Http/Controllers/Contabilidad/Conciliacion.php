<?php

namespace App\Http\Controllers\Contabilidad;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Contabilidad\Cont_RegistroContable;
use App\Modelos\Contabilidad\Cont_Transaccion;

use App\Modelos\SRI\SRI_Establecimiento;

use App\Modelos\Contabilidad\Cont_Conciliacion;

use Carbon\Carbon;
use DateTime;
use DB;


class Conciliacion extends Controller
{
	/**
     * Carga la vista
     *
     * 
     */
    public function index()
    {   
        return view('Estadosfinancieros.ConciliacionView');
    }
    /**
     *
     *
     * guardar conciliacion
     *
     */
    public function save_conciliacion($parametro)
    {
    	$data = json_decode($parametro);
    	$aux_conciliacion=Cont_Conciliacion::create((array) $data);
    	return $aux_conciliacion;
    }
     /**
     *
     *
     * lista de cuentas a conciliar
     *
     */
     public function get_cuentas_conciliar($parametro)
     {
     	$filtro = json_decode($parametro);
     	return Cont_RegistroContable::with("cont_transaccion","cont_plancuentas")
     								->whereRaw(" cont_registrocontable.idplancuenta='".$filtro->idplancuenta."' AND cont_registrocontable.idconciliacion IS NULL  AND cont_registrocontable.estadoanulado=true")
     								->orderBy('cont_registrocontable.fecha', 'ASC')
     								->get();
     }
     /**
     *
     *
     * conciliar desconciliar
     *
     */
     public function conciliar_desconciliar($parametro)
     {
     	$data = json_decode($parametro);
     	if($data->select==true){ //concilia la cuenta seleccionada
     		$registro=Cont_RegistroContable::find($data->idregistrocontable);
     		$registro->idconciliacion=$data->idconciliacion;
     		if($registro->save()){
     			return 1;
     		}else{
     			return 0;
     		}
     	}elseif ($data->select==false) { //desconcilia la cuenta seleccionada
     		$registro=Cont_RegistroContable::find($data->idregistrocontable);
     		$registro->idconciliacion=NULL;
     		if($registro->save()){
     			return 1;
     		}else{
     			return 0;
     		}
     	}
     }
     /**
     *
     *
     * cerrar conciliacion
     *
     */
     public function close_conciliacion($parametro)
     {
     	$data = json_decode($parametro);
     	$aux_conci=Cont_Conciliacion::find($data->idconciliacion);
     	$aux_conci->estadoconciliacion='2'; //conciliacion finalizada
     	if($aux_conci->save()){
     		return 1;
     	}else{
     		return 0;
     	}
     }
     /**
     *
     *
     * conciliacion anterior por cuenta contable 
     *
     */
     public function data_before_conciliacion($parametro)
     {
     	$filtro = json_decode($parametro);
     	return Cont_Conciliacion::whereRaw(" idplancuenta='".$filtro->idplancuenta."'  AND estadoanulado=FALSE ")
     							->orderBy("idconciliacion","DESC")
     							->limit(1)
     							->get();
     }
     /**
     *
     *
     * lista de conciliaciones 
     *
     */
     public function getallFitros(Request $request)
    {     
        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $estado = $filter->estado;
        $data = null;
        $aux_query="";
        if ($search!="") {
            $aux_query.=" AND (cont_conciliacion.descripcion LIKE '%".$search."%'  )";
        }
        $aux_estado="false";
        if($estado=="I"){
            $aux_estado="true";
        } 

        $data= Cont_Conciliacion::with('cont_plancuenta')
                                ->whereRaw(" cont_conciliacion.estadoanulado=".$aux_estado." ".$aux_query."" )
                                ->orderBy("cont_conciliacion.idplancuenta", "ASC");
        return $data->paginate(10);
    }
    /**
     *
     *
     * cargar cuetas conciliadas o a conciliar
     *
     */
    public function reload_conciliacion($parametro)
    {
    	$filtro = json_decode($parametro);
    	if($filtro->estadoconciliacion=="2"){
    		return Cont_RegistroContable::with("cont_transaccion","cont_plancuentas")
     								->whereRaw(" cont_registrocontable.idplancuenta='".$filtro->idplancuenta."' AND cont_registrocontable.idconciliacion='".$filtro->idconciliacion."'  AND cont_registrocontable.estadoanulado=true ")
     								->orderBy('cont_registrocontable.fecha', 'ASC')
     								->get();
    	}else{
    		return Cont_RegistroContable::with("cont_transaccion","cont_plancuentas")
     								->whereRaw(" cont_registrocontable.idplancuenta='".$filtro->idplancuenta."' AND (cont_registrocontable.idconciliacion='".$filtro->idconciliacion."' OR cont_registrocontable.idconciliacion IS NULL ) AND cont_registrocontable.estadoanulado=true ")
     								->orderBy('cont_registrocontable.fecha', 'ASC')
     								->get();
    	}
    }
     /**
     *
     *
     * anular conciliacion 
     *
     */
     public function anular_conciliacion($parametro)
     {
     	$filtro = json_decode($parametro);
     	$registro=Cont_RegistroContable::whereRaw(" idconciliacion='".$filtro->idconciliacion."' ")
     									->update(['idconciliacion' => NULL]);

     	$conciliacion=Cont_Conciliacion::whereRaw(" idconciliacion='".$filtro->idconciliacion."' ")
     									->update(['estadoanulado' => true]);
     }
}