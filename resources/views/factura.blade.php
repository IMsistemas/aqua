<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $data['numerosuministro'] }}</title>
    
  </head>
  <body>
    <table>
      <tr>
        <td>Nombre</td>
        <td colspan="3">{{ $data['razonsocial'] }}</td>
      </tr>
      <tr>
        <td>RUC/CI</td>
        <td>{{ $data['documentoidentidad'] }}</td>
        <td>Conexión</td>
        <td>{{ $data['numerosuministro'] }}</td>
      </tr>
      <tr>
        <td>Dirección</td>
        <td>{{ $data['direccionsuministro'] }}</td>
      </tr>
      <tr>
        <td>Teléfono</td>
        <td>{{ $data['telefonosuministro'] }}</td>
        <td>Fecha</td>
        <td><input name="fecha" type="text" id="fecha" value="<?php echo date("m/d/Y"); ?>" size="10" disable /></td>
      </tr>
      <tr>
        <td>Detalle</td>
        <table border="1">
          <tr>
            <td>{{ $data['periodo'] }}</td>
            <td>{{ $data['lecturaanterior'] }}</td>
            <td>{{ $data['lecturaanctual'] }}</td>
            <td>{{ $data['consumo'] }}</td>
          </tr>
          <tr>
            <td>Periodo</td>
            <td>L.Anterior</td>
            <td>L.Actual</td>
            <td>Consumo</td>
          </tr>
        </table>
      </tr>
      <tr>
         
            <table>
            <tr>
              <th>Concepto</th>
              <th>total</th>
            </tr>
            <tr>
               @foreach({{ $data['subrosvariables'] }} as $subrosvariable)
               <td>
                 $subrosvariables->nombrerubro
               </td>
               <td>
                 $subrosvariables->costorubro
               </td>
               @endforeach
            </tr>
            <tr>
               @foreach({{ $data['subrosfijos'] }} as $subrosfijo)
               <td>
                 $subrosfijo->nombrerubro
               </td>
               <td>
                 $subrosfijo->costorubro
               </td>
               @endforeach
            </tr>
            </table>
      </tr>
    </table>
  </body>
</html>