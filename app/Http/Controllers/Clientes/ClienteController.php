<?php

namespace App\Http\Controllers\Clientes;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Clientes\TipoCliente;

use App\Modelos\Configuraciones\Configuracion;
use App\Modelos\Cuentas\CuentasPorPagarClientes;
use App\Modelos\Sectores\Barrio;
use App\Modelos\Sectores\Calle;
use App\Modelos\Servicios\ServicioJunta;
use App\Modelos\Solicitud\Solicitud;
use App\Modelos\Solicitud\SolicitudOtro;
use App\Modelos\Solicitud\SolicitudServicio;
use App\Modelos\Solicitud\SolicitudSuministro;
use App\Modelos\Suministros\Producto;
use App\Modelos\Suministros\Suministro;
use App\Modelos\Tarifas\Tarifa;
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
        return view('Clientes/index');
    }

    public function getClientes()
    {
        //return Cliente::orderBy('fechaingreso', 'asc')->get();
        return Cliente::with('tipocliente')->orderBy('fechaingreso', 'asc')->get();
    }

    public function getTipoCliente()
    {
        return TipoCliente::orderBy('nombretipo', 'asc')->get();
    }

    public function getLastID($table)
    {
        $max = null;

        if ($table == 'solicitudservicio') {
            $max = SolicitudServicio::max('idsolicitudservicio');
        }  else if ($table == 'solicitudotro') {
            $max = SolicitudOtro::max('idsolicitudotro');
        } else if ($table == 'solsuministro') {
            $max = SolicitudSuministro::max('idsolicitudsuministro');
        } else if ($table == 'suministro') {
            $max = Suministro::max('numerosuministro');
        } else if ($table == 'solicitudcambionombre') {
            $max = SolicitudCambioNombre::max('idsolicitudcambionombre');
        } else if ($table == 'solicitudreparticion') {
            $max = SolicitudReparticion::max('idsolicitudreparticion');
        }

        if ($max != null){
            $max += 1;
        } else $max = 1;

        return response()->json(['id' => $max]);
    }

    public function getIdentifyClientes($idcliente)
    {
        $cliente = json_decode($idcliente);

        return Cliente::where('codigocliente', '!=', $cliente->codigocliente)
                        ->orderBy('documentoidentidad', 'asc')->get();
    }

    public function getClienteByIdentify($idcliente)
    {
        $cliente = json_decode($idcliente);

        return Cliente::where('codigocliente', $cliente->codigocliente)->get();
    }

    public function getServicios()
    {
        return ServicioJunta::orderBy('nombreservicio')->get();
    }

    public function getTarifas()
    {
        return Tarifa::orderBy('nombretarifaaguapotable', 'asc')->get();
    }

    public function getBarrios()
    {
        return Barrio::orderBy('nombrebarrio', 'asc')->get();
    }

    /**
     * Obtener las calles de un barrio ordenadas ascendentemente
     *
     * @param $idbarrio
     * @return mixed
     */
    public function getCalles($idbarrio)
    {
        return Calle::where('idbarrio', $idbarrio)->orderBy('nombrecalle', 'asc')->get();
    }

    public function getDividendos()
    {
        return Configuracion::all();
    }

    public function getInfoMedidor()
    {
        return Producto::where('idproducto', 1)->get();
    }

    public function storeSolicitudSuministro(Request $request)
    {
        $suministro = new Suministro();
        $suministro->idcalle = $request->input('idcalle');
        $suministro->codigocliente = $request->input('codigocliente');
        $suministro->idtarifaaguapotable = $request->input('idtarifa');
        $suministro->direccionsumnistro = $request->input('direccionsuministro');
        $suministro->telefonosuministro = $request->input('telefonosuministro');
        $suministro->fechainstalacionsuministro = date('Y-m-d');
        $suministro->idproducto = $request->input('idproducto');

        if ($suministro->save() != false) {
            $solicitudsuministro = new SolicitudSuministro();
            $solicitudsuministro->codigocliente = $request->input('codigocliente');
            $solicitudsuministro->fechasolicitud = date('Y-m-d');
            $solicitudsuministro->estaprocesada = false;
            $solicitudsuministro->direccioninstalacion = $request->input('direccionsuministro');
            $solicitudsuministro->telefonosuminstro = $request->input('telefonosuministro');
            $solicitudsuministro->numerosuministro = $suministro->numerosuministro;

            if ($solicitudsuministro->save() != false) {

                if ($request->input('garantia') != '' && $request->input('garantia') != 0) {
                    $cxp_cliente = new CuentasPorPagarClientes();
                    $cxp_cliente->codigocliente = $request->input('codigocliente');
                    $cxp_cliente->valor = $request->input('garantia');
                    $cxp_cliente->fecha = date('Y-m-d');
                    if ($cxp_cliente->save() != false) {
                        return response()->json(['success' => true]);
                    } else return response()->json(['success' => false]);
                } else return response()->json(['success' => true]);

            } else return response()->json(['success' => false]);

        } else return response()->json(['success' => false]);

    }

    public function store(Request $request)
    {
        $cliente = new Cliente();

        $cliente->documentoidentidad = $request->input('codigocliente');
        $cliente->fechaingreso = $request->input('fechaingreso');
        $cliente->apellidos = $request->input('apellido');
        $cliente->nombres = $request->input('nombre');
        $cliente->celular = $request->input('celular');
        $cliente->correo = $request->input('email');
        $cliente->direcciondomicilio = $request->input('direccion');
        $cliente->telefonoprincipaldomicilio = $request->input('telefonoprincipal');
        $cliente->telefonosecundariodomicilio = $request->input('telefonosecundario');
        $cliente->direcciontrabajo = $request->input('direccionemp');
        $cliente->telefonoprincipaltrabajo = $request->input('telfprincipalemp');
        $cliente->telefonosecundariotrabajo = $request->input('telfsecundarioemp');
        $cliente->id = $request->input('tipocliente');
        $cliente->estaactivo = true;

        $cliente->save();

        return response()->json(['success' => true]);
    }

    public function storeSolicitudOtro(Request $request)
    {
        $solicitudriego = new SolicitudOtro();
        $solicitudriego->codigocliente = $request->input('codigocliente');
        $solicitudriego->fechasolicitud = date('Y-m-d');
        $solicitudriego->estaprocesada = false;
        $solicitudriego->descripcion = $request->input('observacion');

        $result = $solicitudriego->save();

        $max_idsolicitud = SolicitudOtro::where('idsolicitudotro', $solicitudriego->idsolicitudotro)->get();

        return ($result) ? response()->json(['success' => true, 'idsolicitud' => $max_idsolicitud[0]->idsolicitud]) :
            response()->json(['success' => false]);
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
        $cliente = Cliente::find($id);

        $cliente->documentoidentidad = $request->input('codigocliente');
        $cliente->fechaingreso = $request->input('fechaingreso');
        $cliente->apellidos = $request->input('apellido');
        $cliente->nombres = $request->input('nombre');
        $cliente->celular = $request->input('celular');
        $cliente->correo = $request->input('email');
        $cliente->direcciondomicilio = $request->input('direccion');
        $cliente->telefonoprincipaldomicilio = $request->input('telefonoprincipal');
        $cliente->telefonosecundariodomicilio = $request->input('telefonosecundario');
        $cliente->direcciontrabajo = $request->input('direccionemp');
        $cliente->telefonoprincipaltrabajo = $request->input('telfprincipalemp');
        $cliente->telefonosecundariotrabajo = $request->input('telfsecundarioemp');
        $cliente->id = $request->input('tipocliente');

        $cliente->save();

        return response()->json(['success' => true]);
    }



    public function processSolicitud(Request $request, $id)
    {
        $solicitud = Solicitud::find($id);
        $solicitud->estaprocesada = true;
        $solicitud->fechaprocesada = date('Y-m-d');
        $solicitud->save();

        return response()->json(['success' => true]);
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
        $cliente->delete();
        return response()->json(['success' => true]);
    }
}
