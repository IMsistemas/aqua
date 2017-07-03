/**
 * Created by daniel on 02/07/17.
 */

app.controller('rolPagoController', function ($scope,$http,API_URL) {

    $scope.initLoad = function () {

        $scope.getDataEmpresa();

        $scope.getDataEmpleado();

    };

    $scope.getDataEmpresa = function () {

        $http.get(API_URL + 'rolPago/getDataEmpresa').success(function(response){

            if(response.length !== 0){
                $scope.razonsocial = response[0].razonsocial;
                $scope.nombrecomercial = response[0].nombrecomercial;
                $scope.direccion = response[0].direccionestablecimiento;

                var temp_ruc = (response[0].ruc).split('-');

                $scope.establ = temp_ruc[0];
                $scope.pto = temp_ruc[1];
                $scope.secuencial = temp_ruc[2];

            } else {

                $scope.establ = '000';
                $scope.pto = '000';
                $scope.secuencial = '0000000000000';


            }
        });
    };

    $scope.getDataEmpleado = function () {

        $http.get(API_URL + 'rolPago/getEmpleados').success(function(response){

            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].namepersona, id: response[i].idpersona})
            }
            $scope.empleados = array_temp;
            $scope.empleado = '';

        });
    };

    $scope.fillDataEmpleado = function () {

        var idempleado = $scope.empleado;

        $http.get(API_URL + 'rolPago/getDataEmpleado/'+ idempleado).success(function(response){

            console.log(response);

            if(response.length !== 0){
                $scope.identificacion = response[0].numdocidentific;
                $scope.cargo = response[0].namecargo;
                $scope.sueldo = response[0].salario;

            }

        });

    };

    }
);