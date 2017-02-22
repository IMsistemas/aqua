<?php

namespace App\Http\Controllers\Nomina;

use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Nomina\Cargo;
use App\Modelos\Nomina\Departamento;
use App\Modelos\Nomina\Empleado;
use App\Modelos\Persona;
use App\Modelos\SRI\SRI_TipoIdentificacion;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

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
     * @param Request $request
     * @return mixed
     */
    public function getEmployees(Request $request)
    {
        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $employee = null;

        $employees = Empleado::join('persona', 'persona.idpersona', '=', 'empleado.idpersona')
                                ->join('departamento', 'departamento.iddepartamento', '=', 'empleado.iddepartamento')
                                ->join('cargo', 'cargo.idcargo', '=', 'empleado.idcargo')
                                ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'empleado.idplancuenta')
                                ->select('empleado.*', 'departamento.namedepartamento', 'cargo.namecargo', 'persona.*', 'cont_plancuenta.*');

        if ($search != null) {
            $employees = $employees->whereRaw("persona.razonsocial LIKE '%" . $search . "%'");
        }

        return $employees->orderBy('fechaingreso', 'desc')->paginate(10);
    }


    /**
     * Obtener todos los cargos
     *
     * @return mixed
     */
    public function getAllPositions()
    {
        return Cargo::orderBy('namecargo', 'asc')->get();
    }


    /**
     * Obtener todos los departamentos
     *
     * @return mixed
     */
    public function getDepartamentos()
    {
        return Departamento::orderBy('namedepartamento', 'asc')->get();
    }


    /**
     * Obtener todos los tipos de identificacion
     *
     * @return mixed
     */
    public function getTipoIdentificacion()
    {
        return SRI_TipoIdentificacion::orderBy('nameidentificacion', 'asc')->get();
    }


    /**
     * Obtener las cuentas del Plan de Cuenta
     *
     * @return mixed
     */
    public function getPlanCuenta()
    {
        return Cont_PlanCuenta::orderBy('jerarquia', 'asc')->get();
    }


    /**
     * Obtener y devolver los numeros de identificacion que concuerden con el parametro a buscar
     *
     * @param $identify
     * @return mixed
     */
    public function getIdentify($identify)
    {
        return Persona::whereRaw("numdocidentific::text ILIKE '%" . $identify . "%'")
                        ->whereRaw('idpersona NOT IN (SELECT idpersona FROM empleado)')
                        ->get();
    }


    /**
     * Obtener y devolver la persona que cumpla con el numero de identificacion buscado
     *
     * @param $identify
     * @return mixed
     */
    public function getPersonaByIdentify($identify)
    {
        return Persona::whereRaw("numdocidentific::text ILIKE '%" . $identify . "%'")->get();
    }


    /**
     * Almacenar el recurso empleado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $url_file = null;

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $destinationPath = public_path() . '/uploads/empleados';
            $name = rand(0, 9999) . '_' . $image->getClientOriginalName();
            if(!$image->move($destinationPath, $name)) {
                return response()->json(['success' => false]);
            } else {
                $url_file = '/uploads/empleados/' . $name;
            }

        }
        if ($request->input('idpersona') == 0) {
            $persona = new Persona();
        } else {
            $persona = Persona::find($request->input('idpersona'));
        }
        $persona->lastnamepersona = $request->input('apellidos');
        $persona->namepersona = $request->input('nombres');
        $persona->numdocidentific = $request->input('documentoidentidadempleado');
        $persona->email = $request->input('correo');
        $persona->celphone = $request->input('celular');
        $persona->idtipoidentificacion = $request->input('tipoidentificacion');
        $persona->razonsocial = $request->input('nombres') . ' ' . $request->input('apellidos');
        $persona->direccion = $request->input('direcciondomicilio');

        if ($persona->save()) {
            $empleado = new Empleado();
            $empleado->idpersona = $persona->idpersona;
            $empleado->idcargo = $request->input('idcargo');
            $empleado->iddepartamento = $request->input('departamento');
            $empleado->idplancuenta = $request->input('cuentacontable');
            $empleado->estado = true;
            $empleado->fechaingreso = $request->input('fechaingreso');
            $empleado->telefprincipaldomicilio = $request->input('telefonoprincipaldomicilio');
            $empleado->telefsecundariodomicilio = $request->input('telefonosecundariodomicilio');
            $empleado->salario = $request->input('salario');

            if ($url_file != null) {
                $empleado->rutafoto = $url_file;
            }
            $empleado->save();
        } else {
            return response()->json(['success' => false]);
        }
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
        return Empleado::with('persona', 'cargo')->where('idempleado', $id) ->get();
    }


    /**
     * Actualizar el recurso empleado seleccionado
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEmpleado(Request $request, $id)
    {
        $url_file = null;

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $destinationPath = public_path() . '/uploads/empleados';
            $name = rand(0, 9999) . '_' . $image->getClientOriginalName();
            if(!$image->move($destinationPath, $name)) {
                return response()->json(['success' => false]);
            } else {
                $url_file = '/uploads/empleados/' . $name;
            }
        }

        $persona = Persona::find($request->input('idpersona'));;
        $persona->lastnamepersona = $request->input('apellidos');
        $persona->namepersona = $request->input('nombres');
        $persona->numdocidentific = $request->input('documentoidentidadempleado');
        $persona->email = $request->input('correo');
        $persona->celphone = $request->input('celular');
        $persona->idtipoidentificacion = $request->input('tipoidentificacion');
        $persona->razonsocial = $request->input('nombres') . ' ' . $request->input('apellidos');
        $persona->direccion = $request->input('direcciondomicilio');

        if ($persona->save()) {
            $empleado = Empleado::find($id);
            $empleado->idcargo = $request->input('idcargo');
            $empleado->iddepartamento = $request->input('departamento');
            $empleado->idplancuenta = $request->input('cuentacontable');
            $empleado->fechaingreso = $request->input('fechaingreso');
            $empleado->telefprincipaldomicilio = $request->input('telefonoprincipaldomicilio');
            $empleado->telefsecundariodomicilio = $request->input('telefonosecundariodomicilio');
            $empleado->salario = $request->input('salario');

            if ($url_file != null) {
                $empleado->rutafoto = $url_file;
            }
            $empleado->save();
        } else {
            return response()->json(['success' => false]);
        }
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
        if ($empleado->delete()) {
            return response()->json(['success' => true]);
        }
        else return response()->json(['success' => false]);
    }

}
