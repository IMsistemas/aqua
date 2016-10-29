<?php

namespace App\Http\Controllers\Clientes;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Clientes\TipoCliente;

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

        $table = json_decode($table);

        if ($table->name == 'solicitudriego') {
            $max = SolicitudRiego::max('idsolicitudriego');
        } else if ($table->name == 'terreno') {
            $max = Terreno::max('idterreno');
        } else if ($table->name == 'solicitudotro') {
            $max = SolicitudOtro::max('idsolicitudotro');
        } else if ($table->name == 'solicitudcambionombre') {
            $max = SolicitudCambioNombre::max('idsolicitudcambionombre');
        } else if ($table->name == 'solicitudreparticion') {
            $max = SolicitudReparticion::max('idsolicitudreparticion');
        }

        if ($max != null){
            $max += 1;
        } else {
            $max = 1;
        }

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
