<?php

namespace App\Http\Controllers\Nomina;

use App\Modelos\Nomina\Cargo;
use App\Modelos\Nomina\Empleado;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EmpleadoController extends Controller
{

    /**
     * Devolver la vista
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('Nomina.index_empleado');
    }

    /**
     * Obtener todos los empleados
     *
     * @return mixed
     */
    public function getEmployees()
    {
       return Empleado::with('cargo')->orderBy('fechaingreso', 'asc')->get();
    }


    /**
     * Obtener todos los cargos
     *
     * @return mixed
     */
    public function getAllPositions()
    {
        return Cargo::orderBy('nombrecargo', 'asc')->get();
    }

    /**
     * Almacenar el recurso empleado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request)
    {
        $empleado = new Empleado();

        $empleado->fechaingreso = $request->input('fechaingreso');
        $empleado->documentoidentidadempleado = $request->input('documentoidentidadempleado');
        $empleado->idcargo = $request->input('idcargo');
        $empleado->apellidos = $request->input('apellidos');
        $empleado->nombres = $request->input('nombres');
        $empleado->telefonoprincipaldomicilio = $request->input('telefonoprincipaldomicilio');
        $empleado->telefonosecundariodomicilio = $request->input('telefonosecundariodomicilio');
        $empleado->celular = $request->input('celular');
        $empleado->direcciondomicilio = $request->input('direcciondomicilio');
        $empleado->correo = $request->input('correo');
        $empleado->salario = $request->input('salario');

        $empleado->save();

        return response()->json(['success' => true]);
    }



    /**
     * Mostrar un recurso empleado especifico.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
       return Empleado::with('cargo')->where('documentoidentidadempleado', $id) ->get();
    }

    /**
     * Actualizar el recurso empleado seleccionado
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $empleado = Empleado::find($id);
        //$empleado->fechaingreso = $request->input('fechaingreso');
        $empleado->documentoidentidadempleado = $request->input('documentoidentidadempleado');
        $empleado->idcargo = $request->input('idcargo');
        $empleado->apellidos = $request->input('apellidos');
        $empleado->nombres = $request->input('nombres');
        $empleado->telefonoprincipaldomicilio = $request->input('telefonoprincipaldomicilio');
        $empleado->telefonosecundariodomicilio = $request->input('telefonosecundariodomicilio');
        $empleado->celular = $request->input('celular');
        $empleado->direcciondomicilio = $request->input('direcciondomicilio');
        $empleado->correo = $request->input('correo');
        $empleado->salario = $request->input('salario');

        $empleado->save();

        return response()->json(['success' => true]);
    }

    /**
     * Eliminar el recurso empleado seleccionado
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $empleado = Empleado::find($id);
        $empleado->delete();
        return response()->json(['success' => true]);
    }

}
