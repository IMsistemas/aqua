<?php

namespace App\Http\Controllers\Nomina;

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

    public function show($id)
    {

    }


}
