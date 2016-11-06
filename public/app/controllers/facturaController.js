app.controller('facturaController', function($scope, $http, API_URL) {

    $scope.cobroagua = [];
    $scope.aux = [];

    $scope.initLoad = function () {
        $http.get(API_URL + 'factura/verifyPeriodo').success(function(response){
            (response.count == 0) ? $('#btn-generate').prop('disabled', false) : $('#btn-generate').prop('disabled', true);
        });
        $scope.array_temp = [];
        $http.get(API_URL + 'factura/getCobroAgua').success(function(response){
           console.log(response);
           // $scope.cobroagua = response;

          /*   $scope.aux = [];
             $scope.array_temp = response;

              for(var i = 0; i < $scope.array_temp.length; i++){
                  if($scope.array_temp[i].estapagado == false)
                  {
                      $scope.array_temp[i].estapagado = 'NO PAGADA';
                  }
                  else {
                      $scope.array_temp[i].estapagado = 'PAGADA';
                    //  $scope.array_temp[i].fecha =  yearmonth ($scope.array_temp[i].fecha);
                  }
                  $scope.id = $scope.array_temp[i].factura.codigocliente;

                  $http.get(API_URL + 'factura/getServiciosXCobro/' + $scope.id).success(function(response) {
                      $scope.servicio='';

                      for(var e = 0; e < response.length; e++)
                      {
                          $scope.servicio = $scope.servicio + '/' + response[e].serviciojunta.nombreservicio;
                      }

                  });

                      var data = {
                          servicios : $scope.servicio,
                          factura:  $scope.array_temp[i].idfactura,
                          fecha: $scope.array_temp[i].fecha,
                          periodo:  yearmonth ($scope.array_temp[i].fecha),
                          suministro: $scope.array_temp[i].numerosuministro,
                          direccion: $scope.array_temp[i].suministro.direccionsumnistro,
                          telefono: $scope.array_temp[i].suministro.telefonosuministro,
                          estado: $scope.array_temp[i].estapagado,
                          total: $scope.array_temp[i].valor
                      }
                  console.log(data);

                  $scope.aux.push(data);
              }
              $scope.cobroagua = $scope.aux;*/

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
            var array_temp = [{label: '--Mes --', id: 0}];
            $scope.mesess = array_temp;
            $scope.s_mes = 0;
    };

    $scope.Estado = function () {
            var array_temp = [{label: '--Estado --', id: 0}];
            $scope.estadoss = array_temp;
            $scope.s_estado = 0;
    };

    $scope.ShowModalFactura = function () {
        $('#modalFactura').modal('show');
    };

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






    $scope.initLoad();
    $scope.Servicio();
    $scope.Anio();
    $scope.Meses();
    $scope.Estado();

});


function yearmonth  (fecha)
{
    var Date = fecha;
    var elem = Date.split('-');
    dia = elem[2];
    mes = elem[1];
    año = elem[0];

    return mes + ' - ' + año;
}