<?php

namespace App\Http\Controllers\Contabilidad;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Contabilidad\Cont_RegistroContable;
use App\Modelos\Contabilidad\Cont_Transaccion;

use App\Modelos\SRI\SRI_Establecimiento;

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
     * Consultar libro diario por parametro de 2 fechas!
     * el libro diario trae todas las transacciones 
     * 
     */
    public function get_libro_diario($parametro)
    {
    	$filtro = json_decode($parametro);
    	$aux_estado="";
    	if($filtro->Estado=="1"){ //true
    		$aux_estado=" AND cont_transaccion.estadoanulado='true' ";
    	}elseif($filtro->Estado=="2"){
    		$aux_estado=" AND cont_transaccion.estadoanulado='false' ";
    	}
    	/*return Cont_RegistroContable::with("cont_transaccion.cont_tipotransaccion","cont_plancuentas")
    								->whereRaw(" cont_registrocontable.fecha >='".$filtro->FechaI."' AND cont_registrocontable.fecha<='".$filtro->FechaF."' ".$aux_estado." ")
    								->orderBy('cont_registrocontable.fecha', 'ASC')
    								->orderBy('cont_registrocontable.idtransaccion', 'ASC')
    								->get();*/
        /*return Cont_Transaccion::with("cont_registrocontable","cont_tipotransaccion", "cont_registrocontable.cont_plancuentas")
                           ->whereRaw(" cont_transaccion.fechatransaccion >='".$filtro->FechaI."' AND cont_transaccion.fechatransaccion<='".$filtro->FechaF."' ".$aux_estado." ")
                           ->orderBy('cont_transaccion.fechatransaccion', 'ASC')
                           ->orderBy('cont_transaccion.idtransaccion', 'ASC')
                           ->get();*/
        return Cont_Transaccion::with(["cont_registrocontable","cont_tipotransaccion", "cont_registrocontable.cont_plancuentas","cont_registrocontable"                    =>function($query){
                                    $query->orderBy("debe","DESC");
                                }])
                           ->whereRaw(" cont_transaccion.fechatransaccion >='".$filtro->FechaI."' AND cont_transaccion.fechatransaccion<='".$filtro->FechaF."' ".$aux_estado." ")
                           ->orderBy('cont_transaccion.fechatransaccion', 'ASC')
                           ->orderBy('cont_transaccion.idtransaccion', 'ASC')
                           ->get();
    }
    public function get_libro_diario_vprint($parametro)
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
    							//->whereRaw("tipoestadofinanz='B'  AND (jerarquia ~ '*.*{1}' OR jerarquia ~ '*.*{2}' )") // primer y segundo nivel
                                ->whereRaw("tipoestadofinanz='B' ")
    							->orderBy("jerarquia","ASC")
    							->get();
        $aux_data_plan=array();
        foreach ($balance as $item) {
            $aux_item = array(
                'balance' => $item->balance ,
                'codigosri' => $item->codigosri ,
                'concepto' => $item->concepto ,
                'controlhaber' => $item->controlhaber ,
                'created_at' => $item->created_at ,
                'estado' => $item->estado ,
                'idplancuenta' => $item->idplancuenta ,
                'jerarquia' => $item->jerarquia ,
                'balance' => $item->balance ,
                'tipocuenta' => $item->tipocuenta ,
                'tipoestadofinanz' => $item->tipoestadofinanz ,
                'updated_at' => $item->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($item->jerarquia),
                 );  
                array_push($aux_data_plan, $aux_item); 
        }


    	$estado_resultados=Cont_PlanCuenta::selectRaw("*")
    							->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
    							//->whereRaw("tipoestadofinanz='E'  AND (jerarquia ~ '*.*{1}' )") // primer y segundo nivel 
                                ->whereRaw("tipoestadofinanz='E'  ")
    							->orderBy("jerarquia","ASC")
    							->get();
        $aux_data_plan_estador=array();
        foreach ($estado_resultados as $item) {
            $aux_item = array(
                'balance' => $item->balance ,
                'codigosri' => $item->codigosri ,
                'concepto' => $item->concepto ,
                'controlhaber' => $item->controlhaber ,
                'created_at' => $item->created_at ,
                'estado' => $item->estado ,
                'idplancuenta' => $item->idplancuenta ,
                'jerarquia' => $item->jerarquia ,
                'balance' => $item->balance ,
                'tipocuenta' => $item->tipocuenta ,
                'tipoestadofinanz' => $item->tipoestadofinanz ,
                'updated_at' => $item->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($item->jerarquia),
                 );  
                array_push($aux_data_plan_estador, $aux_item); 
        }
    	//array_push($datos_estado_resultados, $balance);
        //array_push($datos_estado_resultados, $estado_resultados);
        array_push($datos_estado_resultados, $aux_data_plan); //balance
        array_push($datos_estado_resultados, $aux_data_plan_estador);//estado resultados
    	

    	///activo aumenta por el debe y se calcula todo 
    	$aux_total_activo=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
    									->selectRaw("COALESCE(SUM(cont_registrocontable.debe_c)-SUM(cont_registrocontable.haber_c),0) AS total_activo ")
    									->whereRaw("cont_plancuenta.tipocuenta='A' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha<='".$filtro->FechaF."'  ")
    									->get();
    	///pasivo aumenta por el haber y se calcula todo 
    	$aux_total_pasivo=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
    									->selectRaw("COALESCE(SUM(cont_registrocontable.haber_c)-SUM(cont_registrocontable.debe_c),0) AS total_pasivo")
    									->whereRaw("cont_plancuenta.tipocuenta='P' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha<='".$filtro->FechaF."'  ")
    									->get();

    	///patrimonio aumenta por el haber y se calcula todo 
    	$aux_total_patrimonio=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
    									->selectRaw("COALESCE(SUM(cont_registrocontable.haber_c)-SUM(cont_registrocontable.debe_c),0) AS total_patrimonio")
    									->whereRaw("cont_plancuenta.tipocuenta='PT' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha<='".$filtro->FechaF."'  ")
    									->get();

    	///ingreso aumenta por el debe y se calcula en el periodo seleccionado
    	$aux_total_ingresos=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
    									->selectRaw("COALESCE(SUM(cont_registrocontable.debe_c)-SUM(cont_registrocontable.haber_c),0.0) AS total_ingreso ")
    									->whereRaw("cont_plancuenta.tipocuenta='I' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha>='".$filtro->FechaI."' AND cont_registrocontable.fecha<='".$filtro->FechaF."' ")
    									->get();
    	///costo aumenta por el haber y se calcula en el periodo seleccionado
    	$aux_total_costo=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
    									->selectRaw("COALESCE(SUM(cont_registrocontable.haber_c)-SUM(cont_registrocontable.debe_c),0.0) AS total_costo ")
    									->whereRaw("cont_plancuenta.tipocuenta='C' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha>='".$filtro->FechaI."' AND cont_registrocontable.fecha<='".$filtro->FechaF."'  ")
    									->get();
    	///gasto aumenta por el haber y se calcula en el periodo seleccionado
    	$aux_total_gasto=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
    									->selectRaw("COALESCE(SUM(cont_registrocontable.haber_c)-SUM(cont_registrocontable.debe_c),0.0) AS total_gasto ")
    									->whereRaw("cont_plancuenta.tipocuenta='G' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha>='".$filtro->FechaI."' AND cont_registrocontable.fecha<='".$filtro->FechaF."'   ")
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
    public function orden_plan_cuenta($orden)
    {
        $aux_orden=explode('.', $orden);
        $aux_numero_orden="";
        $aux_numero_completar="";
        $tam=count($aux_orden);
        if($tam>0){
              for($x=0;$x<$tam;$x++){
                if($x<3){
                    $aux_numero_orden.=$aux_orden[$x];
                }elseif($x>=3){
                    if($x==3){
                        $aux_numero_completar=$aux_orden[$x];
                        if(strlen ((string)$aux_numero_completar)==1){
                            $aux_numero_completar="0".$aux_numero_completar;
                        }
                        $aux_numero_orden.=$aux_numero_completar;
                    }elseif($x>3){
                        $aux_numero_orden.=$aux_orden[$x];
                    }

                }
            }
        }else{
           $aux_numero_orden=$orden;
        }
        
        return $aux_numero_orden;
    }
    /**
     * Consultar balance de comprobacion  por parametro de 2 fechas
     * analiza el comportamiento de la contabilidad 
     * 
     */
    public function get_balance_de_comprobacion($parametro)
    {
        $filtro = json_decode($parametro);
        return Cont_PlanCuenta::selectRaw("*")
                                ->selectRaw("f_saldocuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."') aux_saldo")
                                ->selectRaw("f_saldo_debehaber_cuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."',1) debe")
                                ->selectRaw("f_saldo_debehaber_cuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."',2) haber")
                                ->selectRaw("(CASE WHEN f_saldo_debehaber_cuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."',1) > f_saldo_debehaber_cuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."',2) THEN (CASE WHEN f_saldocuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."')<0 THEN (f_saldocuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."')*(-1)) ELSE f_saldocuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."') END) ELSE 0 END) saldo_debe")
                                ->selectRaw("(CASE WHEN f_saldo_debehaber_cuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."',1) < f_saldo_debehaber_cuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."',2) THEN (CASE WHEN f_saldocuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."')<0 THEN (f_saldocuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."')*(-1)) ELSE f_saldocuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."') END) ELSE 0 END) saldo_haber")
                                ->whereRaw("f_saldocuentacontable(idplancuenta, '".$filtro->FechaI."', '".$filtro->FechaF."')<>0 ")
                                ->orderBy("jerarquia","ASC")
                                ->get();
    }
    /**
     * Consultar balance contable  por parametro de 2 fechas
     * analiza el comportamiento de la contabilidad 
     * 
     */
    public function get_balance_contable($parametro)
    {
        $filtro = json_decode($parametro);
        $balance_activo=Cont_PlanCuenta::selectRaw("*")
                                ->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
                                ->selectRaw("f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') saldo ")
                                ->whereRaw("tipoestadofinanz='B' AND tipocuenta='A'  AND  (f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."')!=0 OR f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."')!=0 ) ")
                                ->orderBy("jerarquia","ASC")
                                ->get();
        $aux_data_plan=array();
        $cont_aux=0;
        $aux_data_balance_activo=array();
        foreach ($balance_activo as $item) {
            if($cont_aux==0){ //activo
                $activo_aux = Cont_PlanCuenta::selectRaw("*")
                                        ->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
                                        ->selectRaw("f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') saldo ")
                                        ->whereRaw("jerarquia ~ '*{1}' AND tipocuenta='A' ")
                                        ->orderBy("jerarquia","ASC")
                                        ->get();
                $aux_item = array(
                'balance' => $activo_aux[0]->balance ,
                'codigosri' => $activo_aux[0]->codigosri ,
                'concepto' => $activo_aux[0]->concepto ,
                'controlhaber' => $activo_aux[0]->controlhaber ,
                'created_at' => $activo_aux[0]->created_at ,
                'estado' => $activo_aux[0]->estado ,
                'idplancuenta' => $activo_aux[0]->idplancuenta ,
                'jerarquia' => $activo_aux[0]->jerarquia ,
                'saldo' => $activo_aux[0]->saldo ,
                'tipocuenta' => $activo_aux[0]->tipocuenta ,
                'tipoestadofinanz' => $activo_aux[0]->tipoestadofinanz ,
                'updated_at' => $activo_aux[0]->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($activo_aux[0]->jerarquia),
                 );
                 if($activo_aux[0]->idplancuenta!=$item->idplancuenta){
                    array_push($aux_data_balance_activo, $aux_item);  
                 }  
            }
            $aux_item = array(
                'balance' => $item->balance ,
                'codigosri' => $item->codigosri ,
                'concepto' => $item->concepto ,
                'controlhaber' => $item->controlhaber ,
                'created_at' => $item->created_at ,
                'estado' => $item->estado ,
                'idplancuenta' => $item->idplancuenta ,
                'jerarquia' => $item->jerarquia ,
                'saldo' => $item->saldo ,
                'tipocuenta' => $item->tipocuenta ,
                'tipoestadofinanz' => $item->tipoestadofinanz ,
                'updated_at' => $item->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($item->jerarquia),
                 );  
                array_push($aux_data_balance_activo, $aux_item); 
                $cont_aux++;
        }

        $balance_pasivo=Cont_PlanCuenta::selectRaw("*")
                                ->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
                                ->selectRaw("f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') saldo ")
                                ->whereRaw("tipoestadofinanz='B' AND tipocuenta='P'  AND  (f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."')!=0 OR f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."')!=0 ) ")
                                ->orderBy("jerarquia","ASC")
                                ->get();

        $cont_aux=0;
        $aux_data_balance_pasivo=array();
        foreach ($balance_pasivo as $item) {
            if($cont_aux==0){ // pasivo
                $pasivo_aux=Cont_PlanCuenta::selectRaw("*")
                                ->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
                                ->selectRaw("f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') saldo ")
                                ->whereRaw("jerarquia ~ '*{1}' AND tipocuenta='P'  ")
                                ->orderBy("jerarquia","ASC")
                                ->get();
                $aux_item = array(
                'balance' => $pasivo_aux[0]->balance ,
                'codigosri' => $pasivo_aux[0]->codigosri ,
                'concepto' => $pasivo_aux[0]->concepto ,
                'controlhaber' => $pasivo_aux[0]->controlhaber ,
                'created_at' => $pasivo_aux[0]->created_at ,
                'estado' => $pasivo_aux[0]->estado ,
                'idplancuenta' => $pasivo_aux[0]->idplancuenta ,
                'jerarquia' => $pasivo_aux[0]->jerarquia ,
                'saldo' => $pasivo_aux[0]->saldo ,
                'tipocuenta' => $pasivo_aux[0]->tipocuenta ,
                'tipoestadofinanz' => $pasivo_aux[0]->tipoestadofinanz ,
                'updated_at' => $pasivo_aux[0]->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($pasivo_aux[0]->jerarquia),
                 );
                 if($pasivo_aux[0]->idplancuenta!=$item->idplancuenta){
                    array_push($aux_data_balance_pasivo, $aux_item); 
                 }    
            }
            $aux_item = array(
                'balance' => $item->balance ,
                'codigosri' => $item->codigosri ,
                'concepto' => $item->concepto ,
                'controlhaber' => $item->controlhaber ,
                'created_at' => $item->created_at ,
                'estado' => $item->estado ,
                'idplancuenta' => $item->idplancuenta ,
                'jerarquia' => $item->jerarquia ,
                'saldo' => $item->saldo ,
                'tipocuenta' => $item->tipocuenta ,
                'tipoestadofinanz' => $item->tipoestadofinanz ,
                'updated_at' => $item->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($item->jerarquia),
                 );  
                array_push($aux_data_balance_pasivo, $aux_item); 
                $cont_aux++;
        }

        $balance_patrimonio=Cont_PlanCuenta::selectRaw("*")
                                ->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
                                ->selectRaw("f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') saldo ")
                                ->whereRaw("tipoestadofinanz='B' AND tipocuenta='PT'  AND  (f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."')!=0 OR f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."')!=0 ) ")
                                ->orderBy("jerarquia","ASC")
                                ->get();
        $aux_data_balance_patrimonio=array();
        $cont_aux=0;
        foreach ($balance_patrimonio as $item) {
            if($cont_aux==0){
                 $patrimonio_aux=Cont_PlanCuenta::selectRaw("*")
                                ->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
                                ->selectRaw("f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') saldo ")
                                ->whereRaw("jerarquia ~ '*{1}' AND tipocuenta='PT'  ")
                                ->orderBy("jerarquia","ASC")
                                ->get();
                $aux_item = array(
                'balance' => $patrimonio_aux[0]->balance ,
                'codigosri' => $patrimonio_aux[0]->codigosri ,
                'concepto' => $patrimonio_aux[0]->concepto ,
                'controlhaber' => $patrimonio_aux[0]->controlhaber ,
                'created_at' => $patrimonio_aux[0]->created_at ,
                'estado' => $patrimonio_aux[0]->estado ,
                'idplancuenta' => $patrimonio_aux[0]->idplancuenta ,
                'jerarquia' => $patrimonio_aux[0]->jerarquia ,
                'saldo' => $patrimonio_aux[0]->saldo ,
                'tipocuenta' => $patrimonio_aux[0]->tipocuenta ,
                'tipoestadofinanz' => $patrimonio_aux[0]->tipoestadofinanz ,
                'updated_at' => $patrimonio_aux[0]->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($item->jerarquia),
                 );
                 if($patrimonio_aux[0]->idplancuenta!=$item->idplancuenta){
                    array_push($aux_data_balance_patrimonio, $aux_item); 
                 }  
            }
            $aux_item = array(
                'balance' => $item->balance ,
                'codigosri' => $item->codigosri ,
                'concepto' => $item->concepto ,
                'controlhaber' => $item->controlhaber ,
                'created_at' => $item->created_at ,
                'estado' => $item->estado ,
                'idplancuenta' => $item->idplancuenta ,
                'jerarquia' => $item->jerarquia ,
                'saldo' => $item->saldo ,
                'tipocuenta' => $item->tipocuenta ,
                'tipoestadofinanz' => $item->tipoestadofinanz ,
                'updated_at' => $item->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($item->jerarquia),
                 );  
                array_push($aux_data_balance_patrimonio, $aux_item); 
                $cont_aux++;
        }

        ///ingreso aumenta por el debe y se calcula en el periodo seleccionado
        $aux_total_ingresos=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
            ->selectRaw("COALESCE(SUM(cont_registrocontable.haber_c)-SUM(cont_registrocontable.debe_c),0.0) AS total_ingreso ")
            ->whereRaw("cont_plancuenta.tipocuenta='I' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha>='".$filtro->FechaI."' AND cont_registrocontable.fecha<='".$filtro->FechaF."'  ")
            ->get();
        ///costo aumenta por el haber y se calcula en el periodo seleccionado
        $aux_total_costo=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
            //->selectRaw("COALESCE(SUM(cont_registrocontable.haber_c)-SUM(cont_registrocontable.debe_c),0.0) AS total_costo ")
            ->selectRaw("COALESCE(SUM(cont_registrocontable.debe_c)-SUM(cont_registrocontable.haber_c),0.0) AS total_costo ")
            ->whereRaw("cont_plancuenta.tipocuenta='C' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha>='".$filtro->FechaI."' AND cont_registrocontable.fecha<='".$filtro->FechaF."'  ")
            ->get();
        ///gasto aumenta por el haber y se calcula en el periodo seleccionado
        $aux_total_gasto=Cont_PlanCuenta::join("cont_registrocontable","cont_registrocontable.idplancuenta","=","cont_plancuenta.idplancuenta")
            ->selectRaw("COALESCE(SUM(cont_registrocontable.debe_c)-SUM(cont_registrocontable.haber_c),0.0) AS total_gasto ")
            ->whereRaw("cont_plancuenta.tipocuenta='G' AND cont_registrocontable.estadoanulado=TRUE AND cont_registrocontable.fecha>='".$filtro->FechaI."' AND cont_registrocontable.fecha<='".$filtro->FechaF."'  ")
            ->get();
        /// utilidad
        $aux_utilidad= ( ((float) $aux_total_ingresos[0]->total_ingreso) -  ((float) $aux_total_costo[0]->total_costo) -  ((float) $aux_total_gasto[0]->total_gasto) );

        $aux_data_plan = array(
            'Activo' => $aux_data_balance_activo,
            'Pasivo' => $aux_data_balance_pasivo,
            'Patrimonio' => $aux_data_balance_patrimonio,
            'Utilidad'=>$aux_utilidad,
            'Ingresos'=>$aux_total_ingresos[0]->total_ingreso,
            'Costos'=>$aux_total_costo[0]->total_costo,
            'Gastos'=>$aux_total_gasto[0]->total_gasto,
            );
        return $aux_data_plan;
    }
    /**
     * Consultar estado de resultados  por parametro de 2 fechas
     * analiza el comportamiento de la contabilidad 
     * 
     */
    public function get_estado_de_resultados($parametro)
    {
        $filtro = json_decode($parametro);
        $balance_ingreso=Cont_PlanCuenta::selectRaw("*")
                                ->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
                                ->selectRaw("f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') saldo ")
                                ->whereRaw("tipoestadofinanz='E' AND tipocuenta='I'  AND  (f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."')!=0 OR f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."')!=0 ) ")
                                ->orderBy("jerarquia","ASC")
                                ->get();
        $aux_data_plan=array();
        $aux_data_balance_ingreso=array();
        $cont_aux=0;

        foreach ($balance_ingreso as $item) {
            if($cont_aux==0){
                $ingreso_aux=Cont_PlanCuenta::selectRaw("*")
                                ->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
                                ->selectRaw("f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') saldo ")
                                ->whereRaw("jerarquia ~ '*{1}' AND tipocuenta='I'  ")
                                ->orderBy("jerarquia","ASC")
                                ->get();
                $aux_item = array(
                'balance' => $ingreso_aux[0]->balance ,
                'codigosri' => $ingreso_aux[0]->codigosri ,
                'concepto' => $ingreso_aux[0]->concepto ,
                'controlhaber' => $ingreso_aux[0]->controlhaber ,
                'created_at' => $ingreso_aux[0]->created_at ,
                'estado' => $ingreso_aux[0]->estado ,
                'idplancuenta' => $ingreso_aux[0]->idplancuenta ,
                'jerarquia' => $ingreso_aux[0]->jerarquia ,
                'saldo' => $ingreso_aux[0]->saldo ,
                'tipocuenta' => $ingreso_aux[0]->tipocuenta ,
                'tipoestadofinanz' => $ingreso_aux[0]->tipoestadofinanz ,
                'updated_at' => $ingreso_aux[0]->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($ingreso_aux[0]->jerarquia),
                 );
                 if($ingreso_aux[0]->idplancuenta!=$item->idplancuenta){
                    array_push($aux_data_balance_ingreso, $aux_item); 
                 }  

            }
            $aux_item = array(
                'balance' => $item->balance ,
                'codigosri' => $item->codigosri ,
                'concepto' => $item->concepto ,
                'controlhaber' => $item->controlhaber ,
                'created_at' => $item->created_at ,
                'estado' => $item->estado ,
                'idplancuenta' => $item->idplancuenta ,
                'jerarquia' => $item->jerarquia ,
                'saldo' => $item->saldo ,
                'tipocuenta' => $item->tipocuenta ,
                'tipoestadofinanz' => $item->tipoestadofinanz ,
                'updated_at' => $item->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($item->jerarquia),
                 );  
                array_push($aux_data_balance_ingreso, $aux_item); 
                $cont_aux++;
        }

        $balance_costo=Cont_PlanCuenta::selectRaw("*")
                                ->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
                                ->selectRaw("f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') saldo ")
                                ->whereRaw("tipoestadofinanz='E' AND tipocuenta='C'  AND  (f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."')!=0 OR f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."')!=0 ) ")
                                ->orderBy("jerarquia","ASC")
                                ->get();

        $aux_data_balance_costo=array();
        $cont_aux=0;
        foreach ($balance_costo as $item) {
            if($cont_aux==0){
                $costo_aux=Cont_PlanCuenta::selectRaw("*")
                                ->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
                                ->selectRaw("f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') saldo ")
                                ->whereRaw("jerarquia ~ '*{1}' AND tipocuenta='C'  ")
                                ->orderBy("jerarquia","ASC")
                                ->get();
                $aux_item = array(
                'balance' => $costo_aux[0]->balance ,
                'codigosri' => $costo_aux[0]->codigosri ,
                'concepto' => $costo_aux[0]->concepto ,
                'controlhaber' => $costo_aux[0]->controlhaber ,
                'created_at' => $costo_aux[0]->created_at ,
                'estado' => $costo_aux[0]->estado ,
                'idplancuenta' => $costo_aux[0]->idplancuenta ,
                'jerarquia' => $costo_aux[0]->jerarquia ,
                'saldo' => $costo_aux[0]->saldo ,
                'tipocuenta' => $costo_aux[0]->tipocuenta ,
                'tipoestadofinanz' => $costo_aux[0]->tipoestadofinanz ,
                'updated_at' => $costo_aux[0]->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($costo_aux[0]->jerarquia),
                 ); 
                 if($costo_aux[0]->idplancuenta!=$item->idplancuenta){
                    array_push($aux_data_balance_costo, $aux_item); 
                 } 
            }
            $aux_item = array(
                'balance' => $item->balance ,
                'codigosri' => $item->codigosri ,
                'concepto' => $item->concepto ,
                'controlhaber' => $item->controlhaber ,
                'created_at' => $item->created_at ,
                'estado' => $item->estado ,
                'idplancuenta' => $item->idplancuenta ,
                'jerarquia' => $item->jerarquia ,
                'saldo' => $item->saldo ,
                'tipocuenta' => $item->tipocuenta ,
                'tipoestadofinanz' => $item->tipoestadofinanz ,
                'updated_at' => $item->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($item->jerarquia),
                 );  
                array_push($aux_data_balance_costo, $aux_item); 
                $cont_aux++;
        }

        $balance_gasto=Cont_PlanCuenta::selectRaw("*")
                                ->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
                                ->selectRaw("f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') saldo ")
                                ->whereRaw("tipoestadofinanz='E' AND tipocuenta='G'  AND  (f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."')!=0 OR f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."')!=0 ) ")
                                ->orderBy("jerarquia","ASC")
                                ->get();
        $aux_data_balance_gasto=array();
        $cont_aux=0;
        foreach ($balance_gasto as $item) {
            if($cont_aux==0){
                $gasto_aux=Cont_PlanCuenta::selectRaw("*")
                                ->selectRaw("f_balancecuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ")
                                ->selectRaw("f_saldocuentacontable(idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') saldo ")
                                ->whereRaw("jerarquia ~ '*{1}' AND tipocuenta='G'  ")
                                ->orderBy("jerarquia","ASC")
                                ->get();
                $aux_item = array(
                'balance' => $gasto_aux[0]->balance ,
                'codigosri' => $gasto_aux[0]->codigosri ,
                'concepto' => $gasto_aux[0]->concepto ,
                'controlhaber' => $gasto_aux[0]->controlhaber ,
                'created_at' => $gasto_aux[0]->created_at ,
                'estado' => $gasto_aux[0]->estado ,
                'idplancuenta' => $gasto_aux[0]->idplancuenta ,
                'jerarquia' => $gasto_aux[0]->jerarquia ,
                'saldo' => $gasto_aux[0]->saldo ,
                'tipocuenta' => $gasto_aux[0]->tipocuenta ,
                'tipoestadofinanz' => $gasto_aux[0]->tipoestadofinanz ,
                'updated_at' => $gasto_aux[0]->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($gasto_aux[0]->jerarquia),
                 );  
                 if($gasto_aux[0]->idplancuenta!=$item->idplancuenta){
                    array_push($aux_data_balance_gasto, $aux_item); 
                 }
            }
            $aux_item = array(
                'balance' => $item->balance ,
                'codigosri' => $item->codigosri ,
                'concepto' => $item->concepto ,
                'controlhaber' => $item->controlhaber ,
                'created_at' => $item->created_at ,
                'estado' => $item->estado ,
                'idplancuenta' => $item->idplancuenta ,
                'jerarquia' => $item->jerarquia ,
                'saldo' => $item->saldo ,
                'tipocuenta' => $item->tipocuenta ,
                'tipoestadofinanz' => $item->tipoestadofinanz ,
                'updated_at' => $item->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($item->jerarquia),
                 );  
                array_push($aux_data_balance_gasto, $aux_item); 
                $cont_aux++;
        }
        $aux_data_plan = array(
            'Ingreso' => $aux_data_balance_ingreso,
            'Costo' => $aux_data_balance_costo,
            'Gasto' => $aux_data_balance_gasto 
            );
        return $aux_data_plan;
    }

    /**
     * Calcular el cambio del patrimonio entre 2 fechas seleccionadas solo calcula las transacciones activas
     * 
     * 
     */
    public function estado_cambio_patrimonio($parametro)
    {
        $filtro = json_decode($parametro);
        return Cont_PlanCuenta::selectRaw("*")
                                ->selectRaw("f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaI."') balance1")
                                ->selectRaw("f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaF."') balance2")
                                ->selectRaw("(CASE WHEN f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaF."')>f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaI."') THEN ((f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaF."'))- (f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaI."'))) WHEN f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaF."')=f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaI."') THEN 0 END ) AS Incremento")
                                ->selectRaw("(CASE WHEN f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaF."')<f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaI."') THEN ((f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaI."'))- (f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaF."'))) WHEN f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaF."')=f_balancecuentacontable(cont_plancuenta.idplancuenta,'','".$filtro->FechaI."') THEN 0 END ) AS Disminucion")
                                ->whereRaw("tipocuenta='PT' AND (SELECT count(*)  FROM cont_plancuenta aux WHERE aux.jerarquia <@ cont_plancuenta.jerarquia)=1  ")
                                ->orderBy("cont_plancuenta.jerarquia","ASC")
                                ->get();
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
        $aux_empresa =SRI_Establecimiento::all();
    	$libro_diario=$this->get_libro_diario($parametro); 
        //$libro_diario=$this->get_libro_diario_vprint($parametro);
        $today=date("Y-m-d H:i:s");
        $view =  \View::make('Estadosfinancieros.libro_diario', compact('filtro','libro_diario','today','aux_empresa'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("libro_diario_".$today."");
    }
    /**
     * Imprimir libro mayor
     * 
     * 
     */
    public function print_libro_mayor($parametro)
    {	
    	ini_set('max_execution_time', 300);
    	$filtro = json_decode($parametro);
    	$libro_mayor=$this->get_libro_mayor($parametro);
        $aux_empresa =SRI_Establecimiento::all();
        $today=date("Y-m-d H:i:s");
        $view =  \View::make('Estadosfinancieros.libro_mayor', compact('filtro','libro_mayor','today','aux_empresa'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("libro_mayor_".$today."");
    }
     /**
     * Imprimir estado de resultados
     * 
     * 
     */
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
      /**
     * Imprimir estado de cambios en el patrimonio
     * 
     * 
     */
    public function print_estado_cambios_patrimonio($parametro)
    {
        ini_set('max_execution_time', 300);
        $filtro = json_decode($parametro);
        $estado_patrimonio=$this->estado_cambio_patrimonio($parametro);
        $aux_empresa =SRI_Establecimiento::all();
        $today=date("Y-m-d H:i:s");
        $view =  \View::make('Estadosfinancieros.cambios_patrimonio', compact('filtro','estado_patrimonio','today','aux_empresa'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
       // $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("estado_patrimonio_".$today."");
    }
    /**
     * Imprimir balance general 
     * 
     * 
     */
    public function print_balace_general($parametro)
    {
        ini_set('max_execution_time', 300);
        $filtro = json_decode($parametro);
        $balance_general_contable =$this->get_balance_contable($parametro);
        $aux_empresa =SRI_Establecimiento::all();
        $today=date("Y-m-d H:i:s");
        $view =  \View::make('Estadosfinancieros.balance_general', compact('filtro','balance_general_contable','today','aux_empresa'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
       // $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("balance_general_".$today."");
    }
    /**
     * Imprimir estado de resultados
     * 
     * 
     */
    public function print_estado_de_resultados($parametro)
    {
        ini_set('max_execution_time', 300);
        $filtro = json_decode($parametro);
        $estador =$this->get_estado_de_resultados($parametro);
        $aux_empresa =SRI_Establecimiento::all();
        $today=date("Y-m-d H:i:s");
        $view =  \View::make('Estadosfinancieros.estado_de_resultados', compact('filtro','estador','today','aux_empresa'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
       // $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("estado_resultados_".$today."");
    }
    /**
     * Imprimir balance de comprobacion
     * 
     * 
     */
    public function print_balance_de_comprobacion($parametro)
    {
        ini_set('max_execution_time', 300);
        $filtro = json_decode($parametro);
        $aux_empresa =SRI_Establecimiento::all();
        $comprobacion =$this->get_balance_de_comprobacion($parametro);
        $today=date("Y-m-d H:i:s");
        $view =  \View::make('Estadosfinancieros.balance_de_comprobacion', compact('filtro','comprobacion','today','aux_empresa'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
       // $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("estado_resultados_".$today."");
    }
}