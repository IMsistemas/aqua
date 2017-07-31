<?php

namespace App\Http\Controllers\Nomina;

use App\Http\Controllers\Contabilidad\CoreContabilidad;
use App\Modelos\Configuracion\ConfigNomina;
use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Nomina\ConceptoPago;
use App\Modelos\Nomina\Empleado;
use App\Modelos\Nomina\RolPago;
use App\Modelos\SRI\SRI_Establecimiento;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RolPagoController extends Controller
{
    public function index()
    {
        return view('RolPago/index');
    }

    public function getDataEmpresa()
    {
        return SRI_Establecimiento::get();
    }

    public function getEmpleados()
    {
        return Empleado::join('persona', 'persona.idpersona', '=', 'empleado.idpersona')
            ->join('cargo', 'cargo.idcargo', '=', 'empleado.idcargo')
            ->select('empleado.*', 'cargo.namecargo', 'persona.*' )->get();

    }

    public function getDataEmpleado($id)
    {
        return Empleado::join('persona', 'persona.idpersona', '=', 'empleado.idpersona')
            ->join('cargo', 'cargo.idcargo', '=', 'empleado.idcargo')
            ->select('empleado.*', 'cargo.namecargo', 'persona.*' )
            ->where('persona.idpersona', $id)->get();

    }

    public function getExistsConfig()
    {
        return ConfigNomina::count();
    }

    public function getCuentas()
    {
        return Cont_PlanCuenta::get();
    }

    public function getConceptos()
    {

        return ConceptoPago::with('confignomina')->orderBy('id_conceptospago', 'asc')->get();

        /*return ConceptoPago::selectRaw("*, COALESCE ((SELECT configuracionsystem.optionvalue FROM configuracionsystem
                        WHERE configuracionsystem.idconfiguracionsystem= COALESCE((rrhh_conceptospago.id_configurationsystem),0)),'') 
                                    AS impuesto")
                            ->orderBy('rrhh_conceptospago.id_conceptospago', 'asc')->get();*/

        /*return ConceptoPago::join('rrhh_categoriapago', 'rrhh_categoriapago.id_categoriapago', '=', 'rrhh_conceptospago.id_categoriapago')
            ->select('rrhh_categoriapago.*', 'rrhh_conceptospago.*' )
            ->orderBy('id_conceptospago', 'asc')->get();*/
    }

    public function getPlanCuenta()
    {
        return Cont_PlanCuenta::selectRaw('
                 * , (SELECT count(*)  FROM cont_plancuenta aux WHERE aux.jerarquia <@ cont_plancuenta.jerarquia) AS madreohija
            ')->orderBy('jerarquia', 'asc')->get();
    }

    public function getRoles()
    {
        //SELECT * FROM rrhh_rolpago WHERE id_conceptopago = 2 AND fecha = (SELECT MAX(fecha)  FROM rrhh_rolpago)

        return RolPago::join('empleado', 'empleado.idempleado', '=', 'rrhh_rolpago.id_empleado')
            ->join('empleado', 'empleado.idempleado', '=', 'rrhh_rolpago.id_empleado')
            ->where('id_conceptopago', 2)
                            ->whereRaw('fecha = (SELECT MAX(fecha) FROM rrhh_rolpago)')->get();
    }

    public function show($id)
    {

    }

    public function store(Request $request)
    {

        $dataContabilidad = json_decode($request->input('dataContabilidad'));

        $id_transaccion = CoreContabilidad::SaveAsientoContable($dataContabilidad);

        //return response()->json(['success' => true, 'idtransaccion' => $id_transaccion]);

        if($id_transaccion !== 0){
            $roles = $request->input('dataRoldePago');

            foreach ($roles as $item) {
                $rol = new RolPago();
                $rol->id_empleado = $request->input('idempleado');
                $rol->id_conceptopago = $item['id_conceptospago'];
                $rol->diascalculo = $request->input('diascalculo');
                if($item['cantidad'] === ""){
                    $rol->valormedida = 0;
                }else $rol->valormedida = $item['cantidad'];

                if($item['valorTotal'] === ""){
                    $rol->valormoneda = 0;
                }else $rol->valormoneda = $item['valorTotal'];

                $rol->horascalculo = $request->input('horascalculo');
                $rol->observacion = $item['observacion'];
                $rol->fecha = $request->input('fecha');
                $rol->numtransaccion = $id_transaccion;

                if ($rol->save() == false) {
                    return response()->json(['success' => false]);
                }
            }
            if ($rol->save() == true) {
                return response()->json(['success' => true]);
            }
        }


    }


}
