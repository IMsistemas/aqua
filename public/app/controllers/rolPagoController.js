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

    $scope.listCuentas = [];

    var field = "";

    var ss = 0;
    var dc = 0;
    var hc = 0;
    var f1 = 0;
    var x = 0;
    var baseiess = 0;
    var aux_max = 0;

    $scope.initLoad = function () {

        $scope.getCuentas();

        $scope.getDataEmpresa();

        $scope.getDataEmpleado();

        setTimeout(function(){ $scope.getConceptos(); }, 1500);

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

    $scope.getCuentas = function () {
        $http.get(API_URL + 'rolPago/getCuentas').success(function(response){

            $scope.listCuentas = response;

        });
    };

    $scope.searchCuenta = function (stringCuenta) {

        var cuentas = [];

        if (stringCuenta.lastIndexOf(',') === -1) {
            cuentas.push(stringCuenta);
        } else {
            cuentas = stringCuenta.split(',');
        }

        var result = [];
        var longitud = cuentas.length;
        var longitud_cuentas = $scope.listCuentas.length;

        for (var i = 0; i < longitud; i++) {

            for (var j = 0; j < longitud_cuentas; j++) {

                if (parseInt(cuentas[i]) === parseInt($scope.listCuentas[j].idplancuenta)) {
                    result.push($scope.listCuentas[j]);
                }

            }

        }

        return result;

    };

    $scope.getConceptos = function () {

        $http.get(API_URL + 'rolPago/getConceptos').success(function(response){

            var long = response.length;
            for(var i = 0; i < long; i++){

                //------------------------------------------------------------------------------------------------------
                var contabilidad = {
                    value: '',
                    writable: true,
                    enumerable: true,
                    configurable: true
                };
                Object.defineProperty(response[i], 'contabilidad', contabilidad);

                if (response[i].confignomina.length !== 0) {
                    response[i].contabilidad = $scope.searchCuenta(response[i].confignomina[0].cuenta);
                }

                //------------------------------------------------------------------------------------------------------

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
                        value: "",
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'cant', cantidad);

                    var valor1 = {
                        value: impuesto,
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

            console.log(response);
        });
    };

    $scope.fillDataEmpleado = function () {

        $scope.ingresos1.forEach(function(item){
            item.cant
        });

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
            f1 = parseFloat(eval(item.formulavalor));
            item.valor = f1.toFixed(2);
        }
        if(item.formulatotal !== '' && item.formulatotal !== null){
            var total = parseFloat(eval(item.formulatotal));
            item.valorTotal = total.toFixed(2);
        }

        $scope.baseiess = 0;
        $scope.valortotalIngreso = 0;
        $scope.valortotalCantidad = 0;

        $scope.ingresos1.forEach(function(item){
            if (item.cant !== undefined && item.cant !== "" && item.valorTotal !== undefined ) {

                $scope.valortotalCantidad = parseInt($scope.valortotalCantidad) + parseInt(item.cant);

                if ($scope.valortotalCantidad <= 30){
                    if(item.aportaiess === true){
                        $scope.baseiess = parseFloat($scope.baseiess) + parseFloat(item.valorTotal);
                    }

                    $scope.valortotalIngreso = parseFloat($scope.valortotalIngreso) + parseFloat(item.valorTotal);
                    $scope.valortotalIngresoBruto = parseFloat($scope.valortotalIngreso);
                }
                else{
                    $scope.valortotalCantidad = parseInt($scope.valortotalCantidad) - parseInt(item.cant);
                    item.cant = "";
                    item.valor = "";
                    item.valorTotal = "";

                    $scope.message_error = "El numero de dias introducidos no puede ser mayor al numero de dias calculos."
                    $('#modalError').modal('show');

                }
            }
        });

        $scope.ingresos2.forEach(function(item){

            if (item.cant !== undefined && item.valorTotal !== "") {
                if(item.aportaiess === true){
                    $scope.baseiess = parseFloat($scope.baseiess) + parseFloat(item.valorTotal);
                }
                $scope.valortotalIngresoBruto = parseFloat($scope.valortotalIngresoBruto) + parseFloat(item.valorTotal);
            }
        });

        $scope.ingresos3.forEach(function(item){
            var aux_total=(item.valorTotal.toString()!=="")?parseFloat(item.valorTotal):0;
            var aux_porcentaje=parseFloat(item.cant);
            var aux_valor_porcentaje=(($scope.baseiess*aux_porcentaje)/100);
            item.valormax = parseFloat(aux_valor_porcentaje).toFixed(2);
            if (item.valorTotal !== undefined && item.valorTotal !== ""){
                if(aux_total>aux_valor_porcentaje){
                    item.valorTotal = "";
                    $scope.message_error = "El valor introducido no puede ser mayor al 20% de la base de aporte al IESS. Por favor introduzca un nuevo valor."
                    $('#modalError').modal('show');
                }else{
                    $scope.valortotalIngresoBruto = parseFloat($scope.valortotalIngresoBruto) + parseFloat(aux_total);
                }
            }
        });

        $scope.total_deducciones = 0;
        $scope.ingresoBruto_deducciones = $scope.valortotalIngresoBruto;
        $scope.deducciones.forEach(function (item) {
            x = (item.cant !== "") ?  item.cant : 0;
            baseiess = parseFloat($scope.baseiess);
            if(item.formulatotal !== '' && item.formulatotal !== null){
                var total = parseFloat(eval(item.formulatotal));
                item.valorTotal = total.toFixed(2);
            }
            if (item.valorTotal !== undefined && item.valorTotal !== "") {
                $scope.ingresoBruto_deducciones = parseFloat($scope.ingresoBruto_deducciones) - parseFloat(item.valorTotal);
                $scope.total_deducciones = parseFloat($scope.total_deducciones) + parseFloat(item.valorTotal);
                $scope.ingresoBruto_beneficios = parseFloat($scope.ingresoBruto_deducciones) + parseFloat($scope.total_beneficios);
            }
        });

        $scope.total_beneficios = 0;
        $scope.ingresoBruto_beneficios = $scope.ingresoBruto_deducciones;
        $scope.beneficios.forEach(function (item) {
            x = (item.cant !== "") ?  item.cant : 0;
            baseiess = parseFloat($scope.baseiess);
            if(item.formulatotal !== '' && item.formulatotal !== null){
                var total = parseFloat(eval(item.formulatotal));
                item.valorTotal = total.toFixed(2);
            }

            if (item.valorTotal !== undefined && item.valorTotal !== "") {
                $scope.ingresoBruto_beneficios = parseFloat($scope.ingresoBruto_beneficios) + parseFloat(item.valorTotal);
                $scope.total_beneficios = parseFloat($scope.total_beneficios) + parseFloat(item.valorTotal);
            }
        });

        $scope.total_adicionales = 0;
        $scope.sueldoliquido  = $scope.ingresoBruto_beneficios;
        $scope.benefadicionales.forEach(function (item) {
            x = (item.cant !== "") ?  item.cant : 0;
            baseiess = parseFloat($scope.baseiess);
            if(item.formulatotal !== '' && item.formulatotal !== null){
                var total = parseFloat(eval(item.formulatotal));
                item.valorTotal = total.toFixed(2);
            }

            if (item.valorTotal !== undefined && item.valorTotal !== "") {
                $scope.total_adicionales = parseFloat($scope.total_adicionales) + parseFloat(item.valorTotal);
                $scope.total_empresarial = parseFloat($scope.total_adicionales) + parseFloat($scope.sueldoliquido);
            }
        });
    };

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