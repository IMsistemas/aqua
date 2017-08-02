<?php

namespace App\Http\Controllers\Contabilidad;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Contabilidad\Cont_RegistroContable;
use App\Modelos\Contabilidad\Cont_Transaccion;

use App\Http\Controllers\Contabilidad\CoreContabilidad;


use App\Modelos\SRI\SRI_Establecimiento;


use Carbon\Carbon;
use DateTime;
use DB;


class Plandecuetas extends Controller
{
    /**
     * Carga la vista
     *
     * 
     */
    public function index()
    {   
        return view('Estadosfinancieros/PlandeCuentasContables');
        //return view('Estadosfinancieros/aux_PlandeCuentasContables');
    }
    /**
     *
     * Carga el pla de cuentas segun los parametros pasados
     *
     *
     */
    public function getplancuentasportipo($filtro)
    {
        $filtro = json_decode($filtro);
        /*return Cont_PlanCuenta::where("tipoestadofinanz","=",$filtro->Tipo)
                ->orderBy('jerarquia', 'ASC')
                ->get();
        */
        /*$aux_sqlplan="SELECT * , ";
        $aux_sqlplan.=" (SELECT count(*)  FROM cont_plancuenta aux WHERE aux.jerarquia <@ pl.jerarquia) madreohija, ";
        $aux_sqlplan.=" f_balancecuentacontable(pl.idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance ";
        $aux_sqlplan.=" FROM cont_plancuenta pl";
        $aux_sqlplan.=" WHERE pl.tipoestadofinanz='".$filtro->Tipo."' ";
        $aux_sqlplan.=" ORDER BY pl.jerarquia; ";
        $aux_data=DB::select($aux_sqlplan);*/
        $aux_plan=Cont_PlanCuenta::selectRaw("*")
                ->selectRaw("(SELECT count(*)  FROM cont_plancuenta aux WHERE aux.jerarquia <@ cont_plancuenta.jerarquia) madreohija")
                ->selectRaw("f_balancecuentacontable(cont_plancuenta.idplancuenta,'".$filtro->FechaI."','".$filtro->FechaF."') balance")
                ->whereRaw(" cont_plancuenta.tipoestadofinanz='".$filtro->Tipo."' ")
                ->orderBy('jerarquia', 'ASC')
                ->get();
        $aux_data_plan=array();
        foreach ($aux_plan as $item) {
            $aux_item = array(
                'balance' => $item->balance ,
                'codigosri' => $item->codigosri ,
                'concepto' => $item->concepto ,
                'controlhaber' => $item->controlhaber ,
                'created_at' => $item->created_at ,
                'estado' => $item->estado ,
                'idplancuenta' => $item->idplancuenta ,
                'jerarquia' => $item->jerarquia ,
                'madreohija' => $item->madreohija ,
                'tipocuenta' => $item->tipocuenta ,
                'tipoestadofinanz' => $item->tipoestadofinanz ,
                'updated_at' => $item->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($item->jerarquia),
                 );  
                array_push($aux_data_plan, $aux_item); 
        }

        //return $aux_plan;                
        return $aux_data_plan;
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
    public function store(Request $request)
    {
        $datos = $request->all();
        $aux_respuesta="";
        if($datos["jerarquia"]=="100000"){// creando cuenta madre
            $aux_cuentacm = Cont_PlanCuenta::create($datos);
            $datos["jerarquia"]=$aux_cuentacm->idplancuenta."";
            $aux_cuentacm1= Cont_PlanCuenta::where("idplancuenta","=",$aux_cuentacm->idplancuenta)
                            ->update($datos);
            $aux_respuesta=$aux_cuentacm1;
        }else{//creando cuenta nodo o hija
            //Buscar nodo siguiente

            $aux_cuentamadre=$datos["jerarquia"];
            $aux_registro=Cont_RegistroContable::whereHas("cont_plancuentas",function($tbl) use ($aux_cuentamadre) { // Validacion de que no tengo registro contable para crear una cuenta hija
                                                    $tbl->where("jerarquia","=",$aux_cuentamadre);
                                                })
                                                ->get();
            if(count($aux_registro)==0){
                $results = DB::select("SELECT (count(*)+1) as nivel FROM cont_plancuenta WHERE jerarquia ~ '$aux_cuentamadre.*{1}'");
                $datos["jerarquia"]=$datos["jerarquia"].".".$results[0]->nivel;
                $aux_cuentanodo = Cont_PlanCuenta::create($datos);
                $aux_respuesta= $aux_cuentanodo;  
            }else{
                $aux_respuesta= "Error"; //Cuenta contable ya tiene dinero
            }
        }
        return $aux_respuesta;
    }
    public function update(Request $request, $id){
        $datos = $request->all();
        $aux_respuesta=Cont_PlanCuenta::where("idplancuenta","=",$id)
                        ->update($datos);
        return $aux_respuesta;
    }
    public function deletecuenta($filtro){
        $filtro = json_decode($filtro);
        $results = DB::select("SELECT count(*) as nivel FROM cont_plancuenta WHERE jerarquia ~ '".$filtro->jerarquia.".*{1}'");
        if($results[0]->nivel=="0"){
            $aux_registro=Cont_RegistroContable::where("idplancuenta","=",$filtro->idplancuenta)->get();
            if(count($aux_registro)>0){ // Tiene asientos contable
                return "Error2";
            }else{
                $cuenta=Cont_PlanCuenta::find($filtro->idplancuenta);
                $respuesta=$cuenta->delete();
                return "Ok";
            }
        }else{
            return "Error1"; // Es cuenta hija
        }
    }
    /**
     *
     * Plan cotable total
     *
     */
    public function plancontabletotal(){
       /* $aux_sqlplan="SELECT * , ";
        $aux_sqlplan.=" (SELECT count(*)  FROM cont_plancuenta aux WHERE aux.jerarquia <@ pl.jerarquia) madreohija ";
        $aux_sqlplan.=" FROM cont_plancuenta pl";
        $aux_sqlplan.=" ORDER BY pl.jerarquia; ";
        $aux_data=DB::select($aux_sqlplan);
        return $aux_data;*/
        $aux_plan=Cont_PlanCuenta::selectRaw("*")
                ->selectRaw("(SELECT count(*)  FROM cont_plancuenta aux WHERE aux.jerarquia <@ cont_plancuenta.jerarquia) madreohija")
                ->orderBy('jerarquia', 'ASC')
                ->get();
        $aux_data_plan=array();
        foreach ($aux_plan as $item) {
            $aux_item = array(
                'balance' => $item->balance ,
                'codigosri' => $item->codigosri ,
                'concepto' => $item->concepto ,
                'controlhaber' => $item->controlhaber ,
                'created_at' => $item->created_at ,
                'estado' => $item->estado ,
                'idplancuenta' => $item->idplancuenta ,
                'jerarquia' => $item->jerarquia ,
                'madreohija' => $item->madreohija ,
                'tipocuenta' => $item->tipocuenta ,
                'tipoestadofinanz' => $item->tipoestadofinanz ,
                'updated_at' => $item->updated_at ,
                'aux_jerarquia' => $this->orden_plan_cuenta($item->jerarquia),
                 );  
                array_push($aux_data_plan, $aux_item); 
        }              
        return $aux_data_plan;
    }
    /**
     *
     *
     * Guardando el asiento contable "Llamada desde el  core contable"
     *
     */
    public function GuardarAsientoContable($transaccion)
    {
        $transaccion = json_decode($transaccion);
        $res=CoreContabilidad::SaveAsientoContable($transaccion);
        return $res;
    }
    /**
     *
     *
     * Load Registro Cuenta Contable
     *
     */
    public function LoadRegistroContable($filtro)
    {
        $filtro = json_decode($filtro);
        $data=Cont_RegistroContable::with("cont_transaccion.cont_tipotransaccion","cont_plancuentas")
                                    ->whereRaw("cont_registrocontable.idplancuenta=".$filtro->idplancuenta." AND  cont_registrocontable.fecha>='".$filtro->Fechai."' AND cont_registrocontable.fecha<='".$filtro->Fechaf."' AND  cont_registrocontable.estadoanulado=".$filtro->Estado." ")
                                    ->orderBy('cont_registrocontable.fecha', 'asc')
                                    ->get();
        //return $data;
        $saldocuenta=0;
        $datosconsaldo = array();
        foreach ($data as $registro) {
            if($filtro->controlhaber=="-"){
                if($registro->debe_c>0 & $registro->haber_c==0){
                   $saldocuenta=$saldocuenta+$registro->debe_c;
                }elseif($registro->debe_c==0 & $registro->haber_c>0){
                    $saldocuenta=$saldocuenta-$registro->haber_c;
                }
            }elseif($filtro->controlhaber=="+"){
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
                'saldo' => $saldocuenta );
            array_push($datosconsaldo, $aux_cuenta);
        }
        return $datosconsaldo;
    }
    /**
     *
     * Borra Asiento Contable
     *
     *
     */
    public function BorrarAsientoContable($id)
    {
        //return CoreContabilidad::BorrarAsientoContable($id); 
        return CoreContabilidad::AnularAsientoContable($id); 
    }
    /**
     *
     * Obtener Datos Asiento Contable Para Modificar
     *
     *
     */
    public function DatosAsientoContable($id)
    {
        /*return Cont_Transaccion::with("cont_registrocontable.cont_plancuentas")
                                ->whereRaw("cont_transaccion.idtransaccion=".$id."")
                                ->get();*/
        return Cont_Transaccion::with(["cont_registrocontable.cont_plancuentas","cont_registrocontable"=>function($query){
                                    $query->orderBy("debe","DESC");
                                }])
                                ->whereRaw("cont_transaccion.idtransaccion=".$id."")
                                ->get();
    }
    /**
     *
     * 
     * Modificar  el asiento contable "Llamada desde el  core contable"
     *
     */
    public function EditarAsientoContable($transaccion)
    {
        $transaccion = json_decode($transaccion);
        $res=CoreContabilidad::ModifyAsientoContable($transaccion);
        return $res;
    }
    /**
     *
     *
     * Numero de comprobante por tipo de transaccion contable
     *
     */
    public function NumComprobante($filtro)
    {   $filtro = json_decode($filtro);
        $aux_numero = DB::select("SELECT (MAX(numcomprobante)+1) numero FROM cont_transaccion WHERE idtipotransaccion='".$filtro->idtipotransaccion."'");
        $aux_numerocomprobante=$aux_numero[0]->numero;
        return $aux_numerocomprobante;
    }

    /**
     *
     *
     * imprimir asiento contable 
     *
     */

    public function print_asc($parametro)
    {
        ini_set('max_execution_time', 300);
        $filtro = json_decode($parametro);
        $data_asc=$this->DatosAsientoContable($filtro->idtransaccion);

        //dd($data_asc);

        $aux_empresa =SRI_Establecimiento::all();
        $today=date("Y-m-d H:i:s");
        $view =  \View::make('Estadosfinancieros.asiento_contable', compact('filtro','data_asc','today','aux_empresa'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
       // $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("print_asiento_contable".$today."");

    }




}
