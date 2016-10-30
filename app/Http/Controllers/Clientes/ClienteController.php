<?php

namespace App\Http\Controllers\Clientes;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Clientes\TipoCliente;

use App\Modelos\Solicitud\Solicitud;
use App\Modelos\Solicitud\SolicitudOtro;
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

        if ($table == 'solicitudriego') {
            $max = SolicitudRiego::max('idsolicitudriego');
        }  else if ($table == 'solicitudotro') {
            $max = SolicitudOtro::max('idsolicitudotro');
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
