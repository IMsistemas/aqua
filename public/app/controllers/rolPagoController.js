/**
 * Created by daniel on 02/07/17.
 */

app.controller('rolPagoController', function ($scope,$http,API_URL) {

    $scope.ingresos1 = [];
    $scope.ingresos2 = [];
    $scope.ingresos3 = [];
    $scope.beneficios = [];
    $scope.deducciones = [];
    $scope.benefadicionales = [];

    $scope.valortotalCantidad = 0;
    $scope.valortotalIngreso = 0;
    $scope.valortotalIngresoBruto = 0;

    var ss = 0;
    var dc = 0;
    var hc = 0;
    var f1 = 0;
    var x = 0;
    var num = 0;

    $scope.initLoad = function () {

        $scope.getDataEmpresa();

        $scope.getDataEmpleado();

        $scope.getConceptos();

        $scope.diascalculo = 30;
        $scope.horascalculo  = 240;

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

    $scope.getConceptos = function () {

        $http.get(API_URL + 'rolPago/getConceptos').success(function(response){

            var long = response.length;
            for(var i = 0; i < long; i++){
                if(response[i].id_categoriapago === 1 && response[i].grupo === '1'){

                    var cantidad = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cant', cantidad);

                    var valor1 = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'valor', valor1);

                    var valorTotal = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'valor', valorTotal);

                    var observacion = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'obs', observacion);

                    $scope.ingresos1.push(response[i]);

                }
                if(response[i].id_categoriapago === 1 && response[i].grupo === '2'){

                    var cantidad = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cant', cantidad);

                    var valor1 = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'valor', valor1);

                    var valorTotal = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'valor', valorTotal);

                    var observacion = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'obs', observacion);

                    $scope.ingresos2.push(response[i]);

                }
                if(response[i].id_categoriapago === 1 && response[i].grupo === '3'){

                    var cantidad = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cant', cantidad);

                    var valor1 = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'valor', valor1);

                    var valorTotal = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'valor', valorTotal);

                    var observacion = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'obs', observacion);

                    $scope.ingresos3.push(response[i]);

                }
                if(response[i].id_categoriapago === 2){
                    $scope.beneficios.push(response[i]);

                }
                if(response[i].id_categoriapago === 3){
                    $scope.deducciones.push(response[i]);

                }
                if(response[i].id_categoriapago === 4){
                    $scope.benefadicionales.push(response[i]);

                }
            }
        });
    };

    $scope.fillDataEmpleado = function () {

        var idempleado = $scope.empleado;

        $http.get(API_URL + 'rolPago/getDataEmpleado/'+ idempleado).success(function(response){

            if(response.length !== 0){
                $scope.identificacion = response[0].numdocidentific;
                $scope.cargo = response[0].namecargo;
                $scope.sueldo = response[0].salario;

            }

        });

    };

    $scope.calcValoresIngresos1 = function (item) {

        ss = $scope.sueldo;
        dc = $scope.diascalculo;
        hc = $scope.horascalculo;
        x = item.cant;

        if(item.formulavalor !== '' || item.formulavalor !== null){
            f1 = eval(item.formulavalor);
            item.valor = f1.toFixed(2);
        }
        if(item.formulatotal !== '' || item.formulatotal !== null){
            var total = eval(item.formulatotal);
            item.valorTotal = total.toFixed(2);

            var longitud = $scope.ingresos1.length;

            $scope.baseiess = 0;
            $scope.valortotalIngreso = 0;
            $scope.valortotalCantidad = 0;

            for (var i = 0; i < longitud; i++) {

                if ($scope.ingresos1[i].cant !== undefined && $scope.ingresos1[i].valorTotal !== undefined) {
                    if($scope.ingresos1[i].aportaiess === true){
                        $scope.baseiess = parseFloat($scope.baseiess) + parseFloat($scope.ingresos1[i].valorTotal);
                    }
                    $scope.valortotalIngreso = parseFloat($scope.valortotalIngreso) + parseFloat($scope.ingresos1[i].valorTotal);
                    $scope.valortotalCantidad = parseInt($scope.valortotalCantidad) + parseInt($scope.ingresos1[i].cant);
                }
            }
        }
    };

    $scope.calcValoresIngresos2 = function (item) {

        ss = $scope.sueldo;
        dc = $scope.diascalculo;
        hc = $scope.horascalculo;
        x = item.cant;

        if(item.formulavalor !== '' || item.formulavalor !== null){
            f1 = eval(item.formulavalor);
            item.valor = f1.toFixed(2);
        }

        if(item.formulatotal !== '' || item.formulatotal !== null){
            var total = eval(item.formulatotal);
            item.valorTotal = total.toFixed(2);

            var longitud = $scope.ingresos2.length;

            //$scope.baseiess = 0;
            //$scope.valortotalIngreso = 0;
            $scope.valortotalIngresoBruto = 0;

            for (var i = 0; i < longitud; i++) {

                if ($scope.ingresos2[i].cant !== undefined && $scope.ingresos2[i].valorTotal !== undefined) {
                    if($scope.ingresos2[i].aportaiess === true){
                        $scope.baseiess = parseFloat($scope.baseiess) + parseFloat($scope.ingresos2[i].valorTotal);
                    }
                    $scope.valortotalIngresoBruto = parseFloat($scope.valortotalIngresoBruto) + parseFloat($scope.valortotalIngreso) + parseFloat($scope.ingresos2[i].valorTotal);
                }

            }


        }

    };

});