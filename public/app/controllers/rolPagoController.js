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
    $scope.baseiess = 0;
    $scope.ingresoBruto_deducciones = 0;
    $scope.ingresoBruto_beneficios = 0;
    $scope.sueldoliquido = 0;
    $scope.total_deducciones = 0;
    $scope.total_beneficios = 0;
    $scope.total_adicionales = 0;
    $scope.total_empresarial = 0;

    var field = "";

    var ss = 0;
    var dc = 0;
    var hc = 0;
    var f1 = 0;
    var x = 0;
    var baseiess = 0;
    var aux_max = 0;

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
                var impuesto =  response[i].impuesto;
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
                    Object.defineProperty(response[i], 'valort', valorTotal);

                    var observacion = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'obs', observacion);

                    var cuenta = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cuenta', cuenta);

                    $scope.ingresos1.push(response[i]);

                }
                if(response[i].id_categoriapago === 1 && response[i].grupo === '2'){

                    var cantidad = {
                        value: impuesto,
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
                    Object.defineProperty(response[i], 'valorTotal', valorTotal);

                    var observacion = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'obs', observacion);

                    var cuenta = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cuenta', cuenta);

                    $scope.ingresos2.push(response[i]);

                }
                if(response[i].id_categoriapago === 1 && response[i].grupo === '3'){

                    var cantidad = {
                        value: "20%",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cant', cantidad);

                    var valormax = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'valormax', valormax);

                    var valorTotal = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'valorTotal', valorTotal);

                    var observacion = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'obs', observacion);

                    var cuenta = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cuenta', cuenta);

                    $scope.ingresos3.push(response[i]);

                }
                if(response[i].id_categoriapago === 2){

                    var cantidad = {
                        value: impuesto,
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cant', cantidad);

                    var valorTotal = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'valorTotal', valorTotal);

                    var cuenta = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cuenta', cuenta);

                    $scope.beneficios.push(response[i]);

                }
                if(response[i].id_categoriapago === 3){

                    var cantidad = {
                        value: impuesto,
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cant', cantidad);

                    var valorTotal = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'valorTotal', valorTotal);

                    var cuenta = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cuenta', cuenta);

                    $scope.deducciones.push(response[i]);

                }
                if(response[i].id_categoriapago === 4){
                    var cantidad = {
                        value: impuesto,
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cant', cantidad);

                    var valorTotal = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'valorTotal', valorTotal);

                    var cuenta = {
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cuenta', cuenta);

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

    $scope.calcValores = function (item) {

        ss = $scope.sueldo;
        dc = $scope.diascalculo;
        hc = $scope.horascalculo;
        baseiess = $scope.baseiess;
        x = (item.cant !== "") ?  item.cant : 0;
        aux_max = 0;

        if(item.formulavalor !== '' && item.formulavalor !== null){
            f1 = eval(item.formulavalor);
            item.valor = f1.toFixed(2);
        }
        if(item.formulatotal !== '' && item.formulatotal !== null){
            var total = parseFloat(eval(item.formulatotal));
            item.valorTotal = total.toFixed(2);
        }

        var long1 = $scope.ingresos1.length;
        var long2 = $scope.ingresos2.length;
        var long3 = $scope.ingresos3.length;
        var longb = $scope.beneficios.length;
        var longd = $scope.deducciones.length;
        var longba = $scope.benefadicionales.length;

        $scope.baseiess = 0;
        $scope.valortotalIngreso = 0;
        $scope.valortotalCantidad = 0;

        for (var i = 0; i < long1; i++) {
            if ($scope.ingresos1[i].cant !== undefined && $scope.ingresos1[i].cant !== "" && $scope.ingresos1[i].valorTotal !== undefined ) {

                $scope.valortotalCantidad = parseInt($scope.valortotalCantidad) + parseInt($scope.ingresos1[i].cant);

                if ($scope.valortotalCantidad <= 30){
                    if($scope.ingresos1[i].aportaiess === true){
                        $scope.baseiess = parseFloat($scope.baseiess) + parseFloat($scope.ingresos1[i].valorTotal);
                    }

                    $scope.valortotalIngreso = parseFloat($scope.valortotalIngreso) + parseFloat($scope.ingresos1[i].valorTotal);
                    $scope.valortotalIngresoBruto = parseFloat($scope.valortotalIngreso);
                }
                else{
                    $scope.valortotalCantidad = parseInt($scope.valortotalCantidad) - parseInt($scope.ingresos1[i].cant);
                    item.cant = "";
                    item.valor = "";
                    item.valorTotal = "";

                    $scope.message_error = "El numero de dias introducidos no puede ser mayor al numero de dias calculos."
                    $('#modalError').modal('show');

                }
            }
        }

        for (var i = 0; i < long2; i++) {

            if ($scope.ingresos2[i].cant !== undefined && $scope.ingresos2[i].valorTotal !== "") {
                if($scope.ingresos2[i].aportaiess === true){
                    $scope.baseiess = parseFloat($scope.baseiess) + parseFloat($scope.ingresos2[i].valorTotal);
                }
                $scope.valortotalIngresoBruto = parseFloat($scope.valortotalIngresoBruto) + parseFloat($scope.ingresos2[i].valorTotal);
            }
        }
        aux_max = (baseiess * parseFloat(item.cant))/100;
        for (var i = 0; i < long3; i++) {

            if ($scope.ingresos3[i].valorTotal !== undefined && $scope.ingresos3[i].valorTotal !== "") {
                if($scope.ingresos3[i].valorTotal <= aux_max){
                    $scope.valortotalIngresoBruto = parseFloat($scope.valortotalIngresoBruto) + parseFloat($scope.ingresos3[i].valorTotal);
                }
                else{

                    item.valorTotal = "";
                    $scope.ingresos3[i].valorTotal = 0;
                    $scope.message_error = "El valor introducido no puede ser mayor al 20% de la base de aporte al IESS. Por favor introduzca un nuevo valor."
                    $('#modalError').modal('show');
                }
            }
            item.valormax = aux_max;
        }

        $scope.total_deducciones = 0;
        $scope.ingresoBruto_deducciones = $scope.valortotalIngresoBruto;
        for (var i = 0; i < longd; i++) {

            if ($scope.deducciones[i].valorTotal !== undefined && $scope.deducciones[i].valorTotal !== "") {
                $scope.ingresoBruto_deducciones = parseFloat($scope.ingresoBruto_deducciones) - parseFloat($scope.deducciones[i].valorTotal);
                $scope.total_deducciones = parseFloat($scope.total_deducciones) + parseFloat($scope.deducciones[i].valorTotal);
                $scope.ingresoBruto_beneficios = parseFloat($scope.ingresoBruto_deducciones) + parseFloat($scope.total_beneficios);
            }
        }

        $scope.total_beneficios = 0;
        $scope.ingresoBruto_beneficios = $scope.ingresoBruto_deducciones;
        for (var i = 0; i < longb; i++) {

            if ($scope.beneficios[i].valorTotal !== undefined && $scope.beneficios[i].valorTotal !== "") {
                $scope.ingresoBruto_beneficios = parseFloat($scope.ingresoBruto_beneficios) + parseFloat($scope.beneficios[i].valorTotal);
                $scope.total_beneficios = parseFloat($scope.total_beneficios) + parseFloat($scope.beneficios[i].valorTotal);
            }
        }

        $scope.total_adicionales = 0;
        $scope.sueldoliquido  = $scope.ingresoBruto_beneficios;
        for (var i = 0; i < longba; i++) {

            if ($scope.benefadicionales[i].valorTotal !== undefined && $scope.benefadicionales[i].valorTotal !== "") {
                $scope.total_adicionales = parseFloat($scope.total_adicionales) + parseFloat($scope.benefadicionales[i].valorTotal);
                $scope.total_empresarial = parseFloat($scope.total_adicionales) + parseFloat($scope.sueldoliquido);
            }
        }
    };

    /*$scope.calcValoresIngresos1 = function (item) {

        ss = $scope.sueldo;
        dc = $scope.diascalculo;
        hc = $scope.horascalculo;
        baseiess = $scope.baseiess;
        x = (item.cant !== "") ?  item.cant : 0;

        if(item.formulavalor !== '' && item.formulavalor !== null){
            f1 = eval(item.formulavalor);
            item.valor = f1.toFixed(2);
        }
        if(item.formulatotal !== '' && item.formulatotal !== null){
            var total = parseFloat(eval(item.formulatotal));
            item.valorTotal = total.toFixed(2);
        }

        var long1 = $scope.ingresos1.length;

        $scope.baseiess = 0;
        $scope.valortotalIngreso = 0;
        $scope.valortotalCantidad = 0;

        for (var i = 0; i < long1; i++) {
            if ($scope.ingresos1[i].cant !== undefined && $scope.ingresos1[i].cant !== "" && $scope.ingresos1[i].valorTotal !== undefined ) {

                $scope.valortotalCantidad = parseInt($scope.valortotalCantidad) + parseInt($scope.ingresos1[i].cant);

                if ($scope.valortotalCantidad <= 30){
                    if($scope.ingresos1[i].aportaiess === true){
                        $scope.baseiess = parseFloat($scope.baseiess) + parseFloat($scope.ingresos1[i].valorTotal);
                    }

                    $scope.valortotalIngreso = parseFloat($scope.valortotalIngreso) + parseFloat($scope.ingresos1[i].valorTotal);
                    $scope.valortotalIngresoBruto = $scope.valortotalIngreso;
                }
                else{
                    $scope.valortotalCantidad = parseInt($scope.valortotalCantidad) - parseInt($scope.ingresos1[i].cant);
                    item.cant = "";
                    item.valor = "";
                    item.valorTotal = "";

                    $scope.message_error = "El numero de dias introducidos no puede ser mayor al numero de dias calculos."
                    $('#modalError').modal('show');

                }
            }
            $scope.ingresoBruto_deducciones = $scope.valortotalIngreso;
            $scope.ingresoBruto_beneficios = $scope.valortotalIngreso;
            $scope.sueldoliquido = $scope.valortotalIngreso;
        }
    };

    $scope.calcValoresIngresos2 = function (item) {

        ss = $scope.sueldo;
        dc = $scope.diascalculo;
        hc = $scope.horascalculo;
        x = (item.cant !== "") ?  item.cant : 0;
        baseiess = $scope.baseiess;

        if(item.formulavalor !== '' && item.formulavalor !== null){
            f1 = eval(item.formulavalor);
            item.valor = f1.toFixed(2);
        }

        if(item.formulatotal !== '' && item.formulatotal !== null){
            var total = parseFloat(eval(item.formulatotal));
            item.valorTotal = total.toFixed(2);

            $scope.valortotalIngresoBruto = $scope.valortotalIngreso;
            $scope.baseiess = $scope.valortotalIngreso;

            var longitud = $scope.ingresos2.length;

            for (var i = 0; i < longitud; i++) {

                if ($scope.ingresos2[i].cant !== undefined && $scope.ingresos2[i].valorTotal !== undefined) {
                    if($scope.ingresos2[i].aportaiess === true){
                        $scope.baseiess = parseFloat($scope.baseiess) + parseFloat($scope.ingresos2[i].valorTotal);
                    }
                    $scope.valortotalIngresoBruto = parseFloat($scope.valortotalIngresoBruto) + parseFloat($scope.ingresos2[i].valorTotal);
                }
                $scope.ingresoBruto_deducciones = $scope.valortotalIngresoBruto;
                $scope.ingresoBruto_beneficios = $scope.valortotalIngresoBruto;
                $scope.sueldoliquido = $scope.valortotalIngresoBruto;
            }
        }

    };

    $scope.calcValoresIngresos3 = function (item) {

        ss = $scope.sueldo;
        dc = $scope.diascalculo;
        hc = $scope.horascalculo;
        //x = (item.cant !== "") ?  item.cant : 0;
        baseiess = parseFloat($scope.baseiess);
        aux_max = (baseiess* parseFloat(item.cant))/100;
        console.log(aux_max);

        $scope.valortotalIngresoBruto = $scope.valortotalIngreso;

        var longitud = $scope.ingresos3.length;
        for (var i = 0; i < longitud; i++) {

            if ($scope.ingresos3[i].valorTotal !== undefined && $scope.ingresos3[i].valorTotal !== "") {
                if($scope.ingresos3[i].valorTotal <= aux_max){
                    $scope.valortotalIngresoBruto = parseFloat($scope.valortotalIngresoBruto) + parseFloat($scope.ingresos3[i].valorTotal);
                }
            }
            else{

                item.valorTotal = "";
                $scope.ingresos3[i].valorTotal = 0;
                $scope.message_error = "El valor introducido no puede ser mayor al 20% de la base de aporte al IESS. Por favor introduzca un nuevo valor."
                $('#modalError').modal('show');
            }

            $scope.ingresoBruto_deducciones = $scope.valortotalIngresoBruto;
            $scope.ingresoBruto_beneficios = $scope.valortotalIngresoBruto;
            $scope.sueldoliquido = $scope.valortotalIngresoBruto;

        }

    };

    $scope.calcValoresDeducciones = function (item) {

        $scope.ingresoBruto_deducciones = $scope.valortotalIngresoBruto;
        $scope.total_deducciones = 0;

        var longitud = $scope.deducciones.length;

        for (var i = 0; i < longitud; i++) {

            if ($scope.deducciones[i].valorTotal !== undefined && $scope.deducciones[i].valorTotal !== "") {
                $scope.ingresoBruto_deducciones = parseFloat($scope.ingresoBruto_deducciones) - parseFloat($scope.deducciones[i].valorTotal);
                $scope.total_deducciones = parseFloat($scope.total_deducciones) + parseFloat($scope.deducciones[i].valorTotal);
                $scope.ingresoBruto_beneficios = parseFloat($scope.ingresoBruto_deducciones) + parseFloat($scope.total_beneficios);
            }

            $scope.sueldoliquido = $scope.ingresoBruto_beneficios;
        }

    };

    $scope.calcValoresBeneficios = function (item) {

        $scope.ingresoBruto_beneficios = 0;
        $scope.total_beneficios = 0;

        var longitud = $scope.beneficios.length;
        for (var i = 0; i < longitud; i++) {

            if ($scope.beneficios[i].valorTotal !== undefined && $scope.beneficios[i].valorTotal !== "") {
                $scope.ingresoBruto_beneficios = parseFloat($scope.ingresoBruto_deducciones) + parseFloat($scope.beneficios[i].valorTotal);
                $scope.total_beneficios = parseFloat($scope.total_beneficios) + parseFloat($scope.beneficios[i].valorTotal);
            }

            $scope.sueldoliquido = $scope.ingresoBruto_beneficios;
        }

    };

    $scope.calcValoresBeneficiosA = function (item) {

        $scope.total_adicionales = 0;
        $scope.total_empresarial = 0;

        var longitud = $scope.benefadicionales.length;
        for (var i = 0; i < longitud; i++) {

            if ($scope.benefadicionales[i].valorTotal !== undefined && $scope.benefadicionales[i].valorTotal !== "") {
                $scope.total_adicionales = parseFloat($scope.total_adicionales) + parseFloat($scope.benefadicionales[i].valorTotal);
                $scope.total_empresarial = parseFloat($scope.total_adicionales) + parseFloat($scope.sueldoliquido);
            }
        }

    };*/

    $scope.showPlanCuenta = function (item) {

        field = item;

        $http.get(API_URL + 'rolPago/getPlanCuenta').success(function(response){
            $scope.cuentas = response;
            $('#modalPlanCuenta').modal('show');
        });

    };

    $scope.selectCuenta = function () {

        var selected = $scope.select_cuenta;
        field.cuenta = selected.concepto;

        $('#modalPlanCuenta').modal('hide');

    };

    $scope.click_radio = function (item) {
        $scope.select_cuenta = item;
    };

});