<?php

namespace App\Http\Controllers\Clientes;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Clientes\TipoCliente;

use App\Modelos\Configuracion\ConfiguracionSystem;
use App\Modelos\Contabilidad\Cont_CatalogItem;
use App\Modelos\Cuentas\CatalogoItemSolicitudServicio;
use App\Modelos\Cuentas\CuentasPorCobrarSuministro;
use App\Modelos\Cuentas\CuentasPorPagarClientes;
use App\Modelos\Persona;
use App\Modelos\Sectores\Barrio;
use App\Modelos\Sectores\Calle;
use App\Modelos\Servicios\ServicioJunta;
use App\Modelos\Servicios\ServiciosCliente;
use App\Modelos\Solicitud\Solicitud;
use App\Modelos\Solicitud\SolicitudCambioNombre;
use App\Modelos\Solicitud\SolicitudMantenimiento;
use App\Modelos\Solicitud\SolicitudOtro;
use App\Modelos\Solicitud\SolicitudServicio;
use App\Modelos\Solicitud\SolicitudSuministro;
use App\Modelos\SRI\SRI_Establecimiento;
use App\Modelos\SRI\SRI_Parte;
use App\Modelos\SRI\SRI_TipoEmpresa;
use App\Modelos\SRI\SRI_TipoIdentificacion;
use App\Modelos\SRI\SRI_TipoImpuestoIva;
use App\Modelos\Suministros\Producto;
use App\Modelos\Suministros\Suministro;
use App\Modelos\Suministros\SuministroCatalogItem;
use App\Modelos\Tarifas\TarifaAguaPotable;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ClienteController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Clientes/index_cliente');
    }

    /**
     * Obtener los clientes paginados
     *
     * @param Request $request
     * @return mixed
     */
    public function getClientes(Request $request)
    {
        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $estado = $filter->estado;

        $cliente = null;

        $cliente = Cliente::join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
                            ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cliente.idplancuenta')
                            ->with('sri_tipoempresa', 'sri_parte')
                            ->select('cliente.*', 'persona.*', 'cont_plancuenta.*');

        if ($search != null) {
            $cliente = $cliente->whereRaw("(persona.razonsocial ILIKE '%" . $search . "%' OR persona.numdocidentific ILIKE '%" . $search . "%' OR CONCAT(persona.lastnamepersona, ' ', persona.namepersona) ILIKE '%" . $search . "%')");
        }

        if ($estado != '0') {
            if ($estado == '1') {
                $cliente = $cliente->where('cliente.estado', true);
            } else {
                $cliente = $cliente->where('cliente.estado', false);
            }
        }

        return $cliente->orderBy('lastnamepersona', 'asc')->paginate(8);
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

    public function getTipoEmpresa()
    {
        return SRI_TipoEmpresa::orderBy('nametipoempresa', 'asc')->get();
    }

    public function getTipoParte()
    {
        return SRI_Parte::orderBy('nameparte', 'asc')->get();
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
            ->whereRaw('idpersona NOT IN (SELECT idpersona FROM cliente)')
            ->get();
    }

    public function getIVADefault()
    {

        return ConfiguracionSystem::where('optionname', 'SRI_IVA_DEFAULT')
                                    ->orWhere('optionname','CONT_CLIENT_DEFAULT')
                                    ->selectRaw("*, (SELECT concepto FROM cont_plancuenta 
                                                            WHERE cont_plancuenta.idplancuenta = (configuracionsystem.optionvalue)::INT 
                                                            AND configuracionsystem.optionname <> 'SRI_IVA_DEFAULT') ")
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
     * Obtener el listado de los tipos de impuestos IVA
     *
     * @return mixed
     */
    public function getImpuestoIVA()
    {
        return SRI_TipoImpuestoIva::orderBy('nametipoimpuestoiva', 'asc')->get();
    }

    public function getItems()
    {
        return Cont_CatalogItem::where('idclaseitem', 1)->orderBy('codigoproducto', 'asc')->get();
    }

    public function getTipoCliente()
    {
        return TipoCliente::orderBy('nametipocliente', 'asc')->get();
    }

    public function searchDuplicate($numidentific)
    {
        $result = $this->searchExist($numidentific);
        return response()->json(['success' => $result]);
    }

    private function searchExist($numidentific)
    {
        $count = Cliente::join('persona', 'cliente.idpersona', '=', 'persona.idpersona')
            ->where('persona.numdocidentific', $numidentific)->count();

        return ($count >= 1) ? true : false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($this->searchExist($request->input('documentoidentidadempleado'))) {

            return response()->json(['success' => false, 'type_error_exists' => true]);

        } else {

            if ($request->input('idpersona') == 0) {
                $persona = new Persona();
            } else {
                $persona = Persona::find($request->input('idpersona'));
            }

            $persona->numdocidentific = $request->input('documentoidentidadempleado');
            $persona->email = $request->input('correo');
            $persona->celphone = $request->input('celular');
            $persona->idtipoidentificacion = $request->input('tipoidentificacion');
            $persona->razonsocial = $request->input('nombres') . ' ' . $request->input('apellidos');
            $persona->lastnamepersona = $request->input('apellidos');
            $persona->namepersona = $request->input('nombres');
            $persona->direccion = $request->input('direccion');

            if ($persona->save()) {
                $cliente = new Cliente();
                $cliente->fechaingreso = $request->input('fechaingreso');
                $cliente->estado = $request->input('estado');
                $cliente->idpersona = $persona->idpersona;
                $cliente->idplancuenta = $request->input('cuentacontable');
                $cliente->idtipoimpuestoiva = $request->input('impuesto_iva');
                $cliente->telefonoprincipaldomicilio = $request->input('telefonoprincipaldomicilio');
                $cliente->telefonosecundariodomicilio = $request->input('telefonosecundariodomicilio');
                $cliente->telefonoprincipaltrabajo = $request->input('telefonoprincipaltrabajo');
                $cliente->telefonosecundariotrabajo = $request->input('telefonosecundariotrabajo');
                $cliente->direcciontrabajo = $request->input('direcciontrabajo');
                $cliente->idtipoempresa = $request->input('idtipoempresa');
                $cliente->idparte = $request->input('idparte');

                $cliente->idtipocliente = $request->input('tipocliente');

                if ($cliente->save()) {
                    return response()->json(['success' => true]);
                } else return response()->json(['success' => false]);

            } else return response()->json(['success' => false]);

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $persona = Persona::find($request->input('idpersona'));

        $persona->numdocidentific = $request->input('documentoidentidadempleado');
        $persona->email = $request->input('correo');
        $persona->celphone = $request->input('celular');
        $persona->idtipoidentificacion = $request->input('tipoidentificacion');
        $persona->razonsocial = $request->input('nombres') . ' ' . $request->input('apellidos');
        $persona->lastnamepersona = $request->input('apellidos');
        $persona->namepersona = $request->input('nombres');
        $persona->direccion = $request->input('direccion');

        if ($persona->save()) {
            $cliente = Cliente::find($id);
            $cliente->fechaingreso = $request->input('fechaingreso');
            $cliente->estado = $request->input('estado');
            $cliente->idpersona = $persona->idpersona;
            $cliente->idplancuenta = $request->input('cuentacontable');
            $cliente->idtipoimpuestoiva = $request->input('impuesto_iva');
            $cliente->telefonoprincipaldomicilio = $request->input('telefonoprincipaldomicilio');
            $cliente->telefonosecundariodomicilio = $request->input('telefonosecundariodomicilio');
            $cliente->telefonoprincipaltrabajo = $request->input('telefonoprincipaltrabajo');
            $cliente->telefonosecundariotrabajo = $request->input('telefonosecundariotrabajo');
            $cliente->direcciontrabajo = $request->input('direcciontrabajo');
            $cliente->idtipoempresa = $request->input('idtipoempresa');
            $cliente->idparte = $request->input('idparte');

            if ($cliente->save()) {
                return response()->json(['success' => true]);
            } else return response()->json(['success' => false]);

        } else return response()->json(['success' => false]);
    }

    /**
     * Obtener si el cliente esta relacionado a alguna solicitud
     *
     * @param $codigocliente
     * @return mixed
     */
    public function getIsFreeCliente($codigocliente)
    {
        return Solicitud::where('idcliente', $codigocliente)->count();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        if ($cliente->delete()) {
            return response()->json(['success' => true]);
        } else return response()->json(['success' => false]);
    }

    private function getListClient()
    {
        return Cliente::join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
            ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cliente.idplancuenta')
            ->with('sri_tipoempresa', 'sri_parte')
            ->select('cliente.*', 'persona.*', 'cont_plancuenta.*')->orderBy('lastnamepersona', 'asc')->get();
    }

    public function reporte_print()
    {
        ini_set('max_execution_time', 3000);

        $filtro = $this->getListClient();

        $aux_empresa = SRI_Establecimiento::all();

        $today = date("Y-m-d H:i:s");

        $view =  \View::make('Clientes.reporteClientePrint', compact('filtro','today','aux_empresa'))->render();

        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadHTML($view);

        $pdf->setPaper('A4', 'landscape');

        return @$pdf->stream('reportCC_' . $today);
    }


}
