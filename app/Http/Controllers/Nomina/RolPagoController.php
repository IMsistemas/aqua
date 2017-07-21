<?php

namespace App\Http\Controllers\Nomina;

use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Nomina\ConceptoPago;
use App\Modelos\Nomina\Empleado;
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

    public function show($id)
    {

    }


}
