app.controller('facturaController', function($scope, $http, API_URL) {
    $scope.cobroagua_aux = [];

    $scope.cobroagua = [];
    $scope.aux = [];
    $scope.item = 0;

    $scope.initLoad = function () {
        $http.get(API_URL + 'factura/verifyPeriodo').success(function(response){
            (response.count == 0) ? $('#btn-generate').prop('disabled', false) : $('#btn-generate').prop('disabled', true);
        });

        $scope.array_temp = [];
        $scope.aux = [];
        $scope.servicio='';
        $scope.tarifa = '';
        $scope.a = '';

        $http.get(API_URL + 'factura/getCobroAgua').success(function(response){
            console.log(response);
            $scope.cobroagua_aux = response;

            $scope.array_temp = response;
              for(var i = 0; i < $scope.array_temp.length; i++){
                  if($scope.array_temp[i].estapagado == false)
                  {
                      $scope.array_temp[i].estapagado = 'NO PAGADA';
                  }
                  else {
                      $scope.array_temp[i].estapagado = 'PAGADA';
                  }

                  $scope.temp = $scope.array_temp[i].suministro.cliente.tipocliente.serviciostipocliente;

                  for(var j = 0; j < $scope.temp.length; j++) {
                        $scope.servicio = $scope.servicio + ' / ' + $scope.temp[j].serviciojunta.nombreservicio;
                        $scope.a =  $scope.temp[j].serviciojunta.serviciosaguapotable;
                      for(var a = 0; a < $scope.a.length; a++) {
                          $scope.tarifa = $scope.tarifa + ' / ' + $scope.a[a].aguapotable.nombretarifaaguapotable;
                      }
                  }
                  if($scope.array_temp[i].idlectura == null)
                  {
                      var data = {
                          tarifas :  $scope.tarifa,
                          servicios : $scope.servicio,
                          factura:  $scope.array_temp[i].idfactura,
                          fecha: FormatoFecha ($scope.array_temp[i].fecha),
                          periodo:  yearmonth ($scope.array_temp[i].fecha),
                          suministro: $scope.array_temp[i].numerosuministro,
                          direccion: $scope.array_temp[i].suministro.direccionsumnistro,
                          telefono: $scope.array_temp[i].suministro.telefonosuministro,
                          estado: $scope.array_temp[i].estapagado,
                          total: $scope.array_temp[i].factura.totalfactura,
                          consumo: null,
                          idcobroagua: $scope.array_temp[i].idcobroagua
                      }

                  }else {

                  var data = {
                          tarifas :  $scope.tarifa,
                          servicios : $scope.servicio,
                          factura:  $scope.array_temp[i].idfactura,
                          fecha: FormatoFecha ($scope.array_temp[i].fecha),
                          periodo:  yearmonth ($scope.array_temp[i].fecha),
                          suministro: $scope.array_temp[i].numerosuministro,
                          direccion: $scope.array_temp[i].suministro.direccionsumnistro,
                          telefono: $scope.array_temp[i].suministro.telefonosuministro,
                          estado: $scope.array_temp[i].estapagado,
                          total: $scope.array_temp[i].factura.totalfactura,
                          consumo: $scope.array_temp[i].lectura.consumo,
                         idcobroagua: $scope.array_temp[i].idcobroagua
                      }
                  }

                  $scope.aux.push(data);
              }
              $scope.cobroagua = $scope.aux;
        });
    };

    $scope.Servicio = function () {
        $http.get(API_URL + 'factura/getServicios').success(function (response) {
           // console.log(response);
            var longitud = response.length;
            var array_temp = [{label: '--Servicios --', id: 0}];
            for (var i = 0; i < longitud; i++) {
                array_temp.push({label: response[i].nombreservicio, id: response[i].idserviciojunta})
            }
            $scope.servicioss = array_temp;
            $scope.s_servicio = 0;
        });
    };

    $scope.Anio = function () {
            var array_temp = [{label: '--Año --', id: 0}];
            $scope.anios = array_temp;
            $scope.s_anio = 0;
    };

    $scope.Meses = function () {
            var array_temp = [
                {label: '-- Mes --', id: 0},
                {label: 'Enero', id: 1},
                {label: 'Febrero',id: 2},
                {label: 'Marzo', id: 3},
                {label: 'Abril' , id: 4},
                {label: 'Mayo',id: 5},
                {label: 'Junio', id: 6},
                {label: 'Julio', id: 7},
                {label: 'Agosto' , id: 8},
                {label: 'Septiembre', id: 9},
                {label: 'Octubre' , id: 10},
                {label: 'Noviembre' , id: 11},
                {label: 'Diciembre' ,id: 12}];

            $scope.meses = array_temp;
            $scope.s_mes = 0;
    };

    $scope.Estado = function () {
            var array_temp = [{label: '--Estado --', id: 0},{label: 'PAGADA', id: 1},{label: 'NO PAGADA ', id: 2}];

            $scope.estadoss = array_temp;
            $scope.s_estado = 0;
    };

    $scope.ShowModalFactura = function (item) {
        console.log(item);
        $scope.item = item.idcobroagua;
        $scope.num_factura = item.factura;
        $scope.mes = Auxiliar(item.periodo);
        $scope.multa = '';
        $scope.multaa = 0, 00;
        var arreg = [];

        for (var i = 0; i < $scope.cobroagua_aux.length; i++) {
            if ($scope.cobroagua_aux[i].suministro.numerosuministro == item.suministro) {
                $scope.documentoidentidad_cliente = $scope.cobroagua_aux[i].suministro.cliente.documentoidentidad;
                $scope.nom_cliente = $scope.cobroagua_aux[i].suministro.cliente.nombres + ' ' + $scope.cobroagua_aux[0].suministro.cliente.apellidos;
                $scope.direcc_cliente = $scope.cobroagua_aux[i].suministro.cliente.direcciondomicilio;
                $scope.telf_cliente = $scope.cobroagua_aux[i].suministro.cliente.celular;


                if ($scope.cobroagua_aux[i].valorexcedente == null) {
                    var excedente_agua = {
                        nombre: "Excedente Agua",
                        valor:  0.00
                    }

                } else {
                    var excedente_agua = {
                        nombre: "Excedente Agua",
                        valor: $scope.cobroagua_aux[i].valorexcedente
                    }
                }
                    arreg.push(excedente_agua);



                if ($scope.cobroagua_aux[i].valormesesatrasados == null) {
                    var valores_atrasados = {
                        nombre: "Valores Atrasados",
                        valor: 0.00
                    }
                }else
                    {
                        var valores_atrasados = {
                            nombre: "Valores Atrasados",
                            valor: $scope.cobroagua_aux[i].valormesesatrasados
                        }
                    }
                        arreg.push(valores_atrasados);





                if ($scope.cobroagua_aux[i].valortarifabasica == null) {
                    var valores_atrasados = {
                        nombre: "Consumo Agua",
                        valor: 0.00
                    }
                }else {
                    var consumo_agua = {
                        nombre: "Consumo Agua",
                        valor: $scope.cobroagua_aux[i].valortarifabasica
                    }
                }
                    arreg.push(consumo_agua);



                if( $scope.cobroagua_aux[i].factura.serviciosenfactura.length > 0) {
                    $scope.servicios = $scope.cobroagua_aux[i].factura.serviciosenfactura;

                    for (var a = 0; a < $scope.servicios.length; a++) {
                        var auxiliar = {
                            nombre: $scope.servicios[a].serviciojunta.nombreservicio,
                            valor: $scope.servicios[a].valor
                        }
                        arreg.push(auxiliar);
                    }
                }



                }
        }
        $scope.aux_modal = $scope.arreg;
       // $scope.total =  parseFloat($scope.multa_asamblea) + parseFloat($scope.valores_atrasados) + parseFloat($scope.excedente_agua) + parseFloat($scope.consumo_agua);

        $('#modalFactura').modal('show');
    };

                /* $scope.ShowModalFactura = function (item) {
                     console.log(item);
                     $scope.num_factura = item.factura;
                     $scope.mes = Auxiliar(item.periodo);
                     $scope.multa = '';
                     $scope.multaa = 0,00;

                     for(var i = 0; i < $scope.cobroagua_aux.length; i++) {
                         if($scope.cobroagua_aux[i].suministro.numerosuministro == item.suministro) {
                             $scope.documentoidentidad_cliente = $scope.cobroagua_aux[i].suministro.cliente.documentoidentidad;
                             $scope.nom_cliente = $scope.cobroagua_aux[i].suministro.cliente.nombres + ' ' + $scope.cobroagua_aux[0].suministro.cliente.apellidos;
                             $scope.direcc_cliente = $scope.cobroagua_aux[i].suministro.cliente.direcciondomicilio;
                             $scope.telf_cliente = $scope.cobroagua_aux[i].suministro.cliente.celular;

                             if ($scope.cobroagua_aux[i].valorexcedente == null) {
                                 $scope.excedente_agua = 0, 00;
                             } else {
                                 $scope.excedente_agua = $scope.cobroagua_aux[i].valorexcedente;
                             }
                             if ($scope.cobroagua_aux[i].valormesesatrasados == null) {
                                 $scope.valores_atrasados = 0, 00;
                             } else {
                                 $scope.valores_atrasados = $scope.cobroagua_aux[i].valormesesatrasados;
                             }

                             $scope.consumo_agua = $scope.cobroagua_aux[i].valortarifabasica;

                             if( $scope.cobroagua_aux[i].factura.otrosvaloresfactura.length > 0)
                             {
                                 $scope.multa =  $scope.cobroagua_aux[i].factura.otrosvaloresfactura;
                                 for(var a = 0; a < $scope.multa.length; a++) {
                                     $scope.aux=  $scope.multa[a].valor;
                                     $scope.multaa += parseFloat ($scope.aux);
                                 }
                                 $scope.multa_asamblea =  $scope.multaa;
                             }else
                             {
                                 $scope.multa_asamblea = 0,00;
                             }
                             if( $scope.cobroagua_aux[i].factura.serviciosenfactura.length > 0) {
                                 $scope.servicios = $scope.cobroagua_aux[i].factura.serviciosenfactura;
                                 for (var a = 0; a < $scope.servicios.length; a++) {

                                     if ($scope.servicios[a].serviciojunta.nombreservicio == 'DESECHOS SOLIDOS') {
                                         $scope.desechos_solidos = $scope.servicios.valor;
                                     }

                                     if ($scope.servicios[a].serviciojunta.nombreservicio == 'ALCANTARILLADO') {
                                         $scope.desechos_solidos = $scope.servicios.valor;
                                     }

                                     if ($scope.servicios[a].serviciojunta.nombreservicio == 'MEDIO AMBIENTE') {
                                         $scope.desechos_solidos = $scope.servicios.valor;
                                     }

                                 }
                             }

                        }
                     }
                     $scope.total =  parseFloat($scope.multa_asamblea) + parseFloat($scope.valores_atrasados) + parseFloat($scope.excedente_agua) + parseFloat($scope.consumo_agua);

                     $('#modalFactura').modal('show');
                 };*/

    $scope.generate = function () {

        $http.get(API_URL + 'factura/generate').success(function(response){
            if (response.result = '1') {
                $scope.initLoad();
                $scope.message = 'Se ha generado los cobros del mes actual correctamente...';
                $('#modalMessage').modal('show');
            } else if (response.result = '2') {
                $scope.message = 'No existen registros a generar cobros en el mes...';
                $('#modalMessage').modal('show');
            }
        });

    };

    $scope.pagar = function(){
        $http.put(API_URL + 'factura/'+ $scope.item ).success(function (response) {
            $scope.initLoad();
            $('#modalFactura').modal('hide');
            $scope.message = 'Se efectuo el pago correctamente';
            $('#modalMessage').modal('show');
            $scope.hideModalMessage();
        });
    }



    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };

    $scope.initLoad();
    $scope.Servicio();
    $scope.Anio();
    $scope.Meses();
    $scope.Estado();

});

$(function(){

    $('[data-toggle="tooltip"]').tooltip();

    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'DD/MM/YYYY'
    });

    $('.datepicker_a').datetimepicker({
        locale: 'es',
        format: 'YYYY'
    });
});


function yearmonth  (fecha)
{
    var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    var Date = fecha;
    var elem = Date.split('-');
    dia = elem[2];
    mes = elem[1];
    anio = elem[0];

    if(mes < 10){
        var aux_mes = mes.split('')
        console.log(aux_mes[1]);
        segundo_numero = aux_mes[1];
        return meses[segundo_numero -1] + ' - ' + anio;
    }else {
        return meses[mes -1] + ' - ' + anio;
    }
}


function FormatoFecha  (fecha)
{
    var Date = fecha;
    var elem = Date.split('-');
    dia = elem[2];
    mes = elem[1];
    anio = elem[0];

     return dia  + '-' + mes + '-' + anio;
}



function Auxiliar  (mes_anio)
{
    var mes = mes_anio;
    var elem = mes.split('-');
    anio = elem[1];
    mes_letra = elem[0];

    return mes_letra;
}