<?php

namespace App\Http\Controllers\Contabilidad;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Contabilidad\Cont_RegistroContable;
use App\Modelos\Contabilidad\Cont_Transaccion;

use App\Http\Controllers\Contabilidad\CoreContabilidad;

use Carbon\Carbon;
use DateTime;
use DB;


class Balances extends Controller
{
	/**
     * Carga la vista
     *
     * 
     */
    public function index()
    {   
        return view('Estadosfinancieros/BalanceContabilidad');
    }
    /**
     * Consultar libro diario por parametro de 2 fechas
     * el libro diario trae todas las transacciones 
     * 
     */
    public function get_libro_diario($parametro)
    {
    	$filtro = json_decode($parametro);
    	$aux_estado="";
    	if($filtro->Estado=="1"){ //true
    		$aux_estado=" AND cont_registrocontable.estadoanulado='true' ";
    	}elseif($filtro->Estado=="2"){
    		$aux_estado=" AND cont_registrocontable.estadoanulado='false' ";
    	}
    	return Cont_RegistroContable::with("cont_transaccion.cont_tipotransaccion","cont_plancuentas")
    								->whereRaw(" cont_registrocontable.fecha >='".$filtro->FechaI."' AND cont_registrocontable.fecha<='".$filtro->FechaF."' ".$aux_estado." ")
    								->orderBy('cont_registrocontable.fecha', 'ASC')
    								->orderBy('cont_registrocontable.idtransaccion', 'ASC')
    								->get();
    }
    /**
     * Consultar libro mayor por parametro de 2 fechas
     * analiza el comportamiento de una cuenta entre los paramentros seleccionados 
     * 
     */
    public function get_libro_mayor($parametro)
    {
    	$filtro = json_decode($parametro);

    	$aux_estado="";
    	if($filtro->Estado=="1"){ //true
    		$aux_estado=" AND cont_registrocontable.estadoanulado='true' ";
    	}elseif($filtro->Estado=="2"){
    		$aux_estado=" AND cont_registrocontable.estadoanulado='false' ";
    	}
        $data=Cont_RegistroContable::with("cont_transaccion.cont_tipotransaccion","cont_plancuentas")
                                    ->whereRaw("cont_registrocontable.idplancuenta=".$filtro->Cuenta->idplancuenta." AND  cont_registrocontable.fecha>='".$filtro->FechaI."' AND cont_registrocontable.fecha<='".$filtro->FechaF."' ".$aux_estado." ")
                                    ->orderBy('cont_registrocontable.fecha', 'ASC')
                                    ->get();


        $aux_fechainicio=date("Y", strtotime($filtro->FechaI));
        $aux_fechainicio=$aux_fechainicio."-01-01";
        $aux_sql=" SELECT ";
        $aux_fechamenor=strtotime('-1 day',strtotime($filtro->FechaI));
        $aux_fechamenor=date( 'Y-m-d' , $aux_fechamenor );
        if($filtro->Cuenta->tipoestadofinanz=="B"){ //se calcula con una fecha
        	if($filtro->Cuenta->controlhaber=="+"){ //aumenta por del haber
        		$aux_sql.=" COALESCE(SUM(haber_c)-SUM(debe_c),0) AS ant_balance  ";
        	}else{
        		$aux_sql.=" COALESCE(SUM(debe_c)-SUM(haber_c),0) AS ant_balance  ";
        	}
        	$aux_sql.=" FROM cont_registrocontable  WHERE idplancuenta=".$filtro->Cuenta->idplancuenta." ";
        	$aux_sql.=" AND  fecha<='".$aux_fechamenor."' ".$aux_estado;
        }elseif($filtro->Cuenta->tipoestadofinanz=="E"){
        	if($filtro->Cuenta->controlhaber=="+"){ //aumenta por del haber
        		$aux_sql.=" COALESCE(SUM(haber_c)-SUM(debe_c),0) AS ant_balance  ";
        	}else{
        		$aux_sql.=" COALESCE(SUM(debe_c)-SUM(haber_c),0) AS ant_balance  ";
        	}
        	$aux_sql.=" FROM cont_registrocontable  WHERE idplancuenta=".$filtro->Cuenta->idplancuenta." ";
        	$aux_sql.=" AND fecha>='".$aux_fechainicio."' AND  fecha<='".$aux_fechamenor."' ".$aux_estado;
        }

        $saldocuenta=0;
        $aux_saldo_cuenta = DB::select($aux_sql);
        $saldocuenta=$aux_saldo_cuenta[0]->ant_balance;

        $datosconsaldo = array();
        foreach ($data as $registro) {
            if($filtro->Cuenta->controlhaber=="-"){
                if($registro->debe_c>0 & $registro->haber_c==0){
                   $saldocuenta=$saldocuenta+$registro->debe_c;
                }elseif($registro->debe_c==0 & $registro->haber_c>0){
                    $saldocuenta=$saldocuenta-$registro->haber_c;
                }
            }elseif($filtro->Cuenta->controlhaber=="+"){
                if($registro->debe_c==0 & $registro->haber_c>0){
                   $saldocuenta=$saldocuenta+$registro->haber_c;
                }elseif($registro->debe_c>0 & $registro->haber_c==0){
                    $saldocuenta=$saldocuenta-$registro->debe_c;
                }
            }
            $aux_cuenta = array(
                'cont_plancuentas' => $registro->cont_plancuentas ,
                'cont_transaccion' => $registro->cont_transaccion,
                'debe' => $registro->debe,
                'debe_c' => $registro->debe_c,
                'descripcion' => $registro->descripcion,
                'fecha' => $registro->fecha,
                'haber' => $registro->haber,
                'haber_c' => $registro->haber_c,
                'idplancuenta' => $registro->idplancuenta,
                'idregistrocontable' => $registro->idregistrocontable,
                'idtransaccion' => $registro->idtransaccion,
                'saldo' => $saldocuenta,
                'estadoanulado'=>$registro->estadoanulado );
            array_push($datosconsaldo, $aux_cuenta);
        }
        return $datosconsaldo;

    }
    /**
     * Consultar estado de resultados por parametro de 2 fechas
     * analiza el comportamiento de la contabilidad 
     * 
     */
    public function get_estado_resultados($parametro)
    {
    	$filtro = json_decode($parametro);
    	$datos_estado_resultados = array();
    	$balance=Cont_PlanCuenta::selectRaw("*")
    							->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
    							->whereRaw("tipoestadofinanz='B'  AND (jerarquia ~ '*.*{1}' OR jerarquia ~ '*.*{2}' )")
    							->orderBy("jerarquia","ASC")
    							->get();
    	$estado_resultados=Cont_PlanCuenta::selectRaw("*")
    							->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
    							->whereRaw("tipoestadofinanz='E'  AND (jerarquia ~ '*.*{1}' )")
    							->orderBy("jerarquia","ASC")
    							->get();
    	array_push($datos_estado_resultados, $balance);
    	array_push($datos_estado_resultados, $estado_resultados);

    	///activo aumenta por el debe y se calcula todo 
    	$aux_total_activo=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
    									->selectRaw("COALESCE(SUM(cont_registrocontable.debe_c)-SUM(cont_registrocontable.haber_c),0) AS total_activo ")
    									->whereRaw("cont_plancuenta.tipocuenta='A' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha<='".$filtro->FechaF."' ")
    									->get();
    	///pasivo aumenta por el haber y se calcula todo 
    	$aux_total_pasivo=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
    									->selectRaw("COALESCE(SUM(cont_registrocontable.haber_c)-SUM(cont_registrocontable.debe_c),0) AS total_pasivo")
    									->whereRaw("cont_plancuenta.tipocuenta='P' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha<='".$filtro->FechaF."' ")
    									->get();

    	///patrimonio aumenta por el haber y se calcula todo 
    	$aux_total_patrimonio=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
    									->selectRaw("COALESCE(SUM(cont_registrocontable.haber_c)-SUM(cont_registrocontable.debe_c),0) AS total_patrimonio")
    									->whereRaw("cont_plancuenta.tipocuenta='PT' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha<='".$filtro->FechaF."' ")
    									->get();

    	///ingreso aumenta por el debe y se calcula en el periodo seleccionado
    	$aux_total_ingresos=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
    									->selectRaw("COALESCE(SUM(cont_registrocontable.debe_c)-SUM(cont_registrocontable.haber_c),0.0) AS total_ingreso ")
    									->whereRaw("cont_plancuenta.tipocuenta='I' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha>='".$filtro->FechaI."' AND cont_registrocontable.fecha<='".$filtro->FechaF."' ")
    									->get();
    	///costo aumenta por el haber y se calcula en el periodo seleccionado
    	$aux_total_costo=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
    									->selectRaw("COALESCE(SUM(cont_registrocontable.haber_c)-SUM(cont_registrocontable.debe_c),0.0) AS total_costo ")
    									->whereRaw("cont_plancuenta.tipocuenta='C' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha>='".$filtro->FechaI."' AND cont_registrocontable.fecha<='".$filtro->FechaF."' ")
    									->get();
    	///gasto aumenta por el haber y se calcula en el periodo seleccionado
    	$aux_total_gasto=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
    									->selectRaw("COALESCE(SUM(cont_registrocontable.haber_c)-SUM(cont_registrocontable.debe_c),0.0) AS total_gasto ")
    									->whereRaw("cont_plancuenta.tipocuenta='G' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha>='".$filtro->FechaI."' AND cont_registrocontable.fecha<='".$filtro->FechaF."' ")
    									->get();
    	/// utilidad 

    	$aux_utilidad= ( ((float) $aux_total_ingresos[0]->total_ingreso) -  ((float) $aux_total_costo[0]->total_costo) -  ((float) $aux_total_gasto[0]->total_gasto) );
    	$balance=( ((float) $aux_total_activo[0]->total_activo ) - ((float) $aux_total_pasivo[0]->total_pasivo ) - ((float) $aux_total_patrimonio[0]->total_patrimonio) - ($aux_utilidad) );
    	$aux_balance = array(
                'total_activo' => $aux_total_activo[0]->total_activo,
                'total_pasivo' => $aux_total_pasivo[0]->total_pasivo,
                'total_patrimonio' => $aux_total_patrimonio[0]->total_patrimonio,
                'total_ingreso' => $aux_total_ingresos[0]->total_ingreso,
                'total_costo' => $aux_total_costo[0]->total_costo,
                'total_gasto' => $aux_total_gasto[0]->total_gasto,
                'utilidad' => $aux_utilidad,
                'balance'=>$balance
                );
            array_push($datos_estado_resultados, $aux_balance);

    	return $datos_estado_resultados;
    }
    /**
     * Imprimir libro diario
     * 
     * 
     */
    public function print_libro_diario($parametro)
    {
    	ini_set('max_execution_time', 300);
    	$filtro = json_decode($parametro);
    	$libro_diario=$this->get_libro_diario($parametro);
        $today=date("Y-m-d H:i:s");
        $view =  \View::make('Estadosfinancieros.libro_diario', compact('filtro','libro_diario','today'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("libro_diario_".$today."");
    }
    /**
     * Imprimir libro diario
     * 
     * 
     */
    public function print_libro_mayor($parametro)
    {	
    	ini_set('max_execution_time', 300);
    	$filtro = json_decode($parametro);
    	$libro_mayor=$this->get_libro_mayor($parametro);
        $today=date("Y-m-d H:i:s");
        $view =  \View::make('Estadosfinancieros.libro_mayor', compact('filtro','libro_mayor','today'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("libro_mayor_".$today."");
    }
    public function print_estado_resultados($parametro)
    {
    	ini_set('max_execution_time', 300);
    	$filtro = json_decode($parametro);
    	$estado_finaciero=$this->get_estado_resultados($parametro);
        $today=date("Y-m-d H:i:s");
        $view =  \View::make('Estadosfinancieros.estado_resultado', compact('filtro','estado_finaciero','today'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
       // $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("estado_resultados_".$today."");
    }
}