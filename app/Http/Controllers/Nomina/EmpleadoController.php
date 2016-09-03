<?php

namespace App\Http\Controllers\Nomina;

use App\Modelos\Nomina\Cargo;
use App\Modelos\Nomina\Empleado;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EmpleadoController extends Controller
{

    public function index()
    {
        return view('Nomina.index_empleado');
    }

    public function getEmployees()
    {
        return Empleado::join('cargo', 'empleado.idcargo', '=', 'cargo.idcargo')
                            ->select('empleado.nombre', 'empleado.apellido', 'empleado.telefonoprincipal',
                                        'empleado.celular', 'empleado.documentoidentidadempleado',
                                        'cargo.nombrecargo')
                            ->orderBy('empleado.apellido', 'asc')
                            ->get();

    }

    public function getAllPositions()
    {
        return Cargo::orderBy('nombrecargo', 'asc')->get();
    }

    public function store(Request $request)
    {
        $result = Empleado::create($request->all());
        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

    public function show($id)
    {
        $empleado = Empleado::join('cargo', 'empleado.idcargo', '=', 'cargo.idcargo')
            ->select('empleado.nombre', 'empleado.apellido', 'empleado.telefonoprincipal',
                'empleado.celular', 'empleado.documentoidentidadempleado', 'empleado.telefonosecundario', 'empleado.fechaingreso',
                'empleado.direccion', 'empleado.correo', 'empleado.idcargo','cargo.nombrecargo')
            ->where('empleado.documentoidentidadempleado', '=', $id)
            ->get();

        return response()->json($empleado);
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::find($id);
        $empleado->fill($request->all());
        $empleado->save();
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $empleado = Empleado::find($id);
        $empleado->delete();
        return response()->json(['success' => true]);
    }

}
