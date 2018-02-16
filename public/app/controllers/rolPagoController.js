/**
 * Created by daniel on 02/07/17.
 */

app.controller('rolPagoController', function ($scope,$http,$parse,API_URL) {

    $scope.listado = true;

    $scope.sueldos = [];
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

    $scope.numdocumento = 0;
    $scope.idtransaccion = 0;
    $scope.estadoanulado = false;

    $scope.cuentaLiquida = '';
    $scope.dataSueldoLiquido = '';
    $scope.dataSueldoBasico = '';

    $scope.dataRoldePago = [];

    $scope.fieldconcepto = '';
    $scope.fieldid = '';

    $scope.rolSelected = null;

    var ss = 0;
    var dc = 0;
    var hc = 0;
    var f1 = 0;
    var x = 0;
    var baseiess = 0;
    var aux_max = 0;

    $scope.porcentageFR = 0;

    $scope.initLoad = function () {

        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'YYYY-MM-DD',
            ignoreReadonly: true
        });

        $('.datepickerP').datetimepicker({
            locale: 'es',
            format: 'YYYY-MM',
            ignoreReadonly: true
        });


        $scope.sueldos = [];
        $scope.ingresos1 = [];
        $scope.ingresos2 = [];
        $scope.ingresos3 = [];
        $scope.beneficios = [];
        $scope.deducciones = [];
        $scope.benefadicionales = [];

        $scope.getCuentas();

        $scope.getDataEmpresa();

        $scope.getDataEmpleado();

        $scope.getRoles();

        //setTimeout(function(){ $scope.getConceptos(); }, 1500);

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
                array_temp.push({label: response[i].namepersona + " " + response[i].lastnamepersona, id: response[i].idempleado})
            }

            $scope.empleados = array_temp;
            $scope.empleadoFiltro = '';
            $scope.empleado = '';

        });

    };

    $scope.getCuentas = function () {
        $http.get(API_URL + 'rolPago/getCuentas').success(function(response){

            $scope.listCuentas = response;

            $scope.getConceptos();

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

        $scope.sueldos = [];
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

        $http.get(API_URL + 'rolPago/getExistsConfig').success(function(response){

            if (response == 0) {

                $scope.message_info = 'Para realizar el rol de Pago debe llenar la Configuracion de Nómina en Configuración de Sistema...';

                $('#modalMessageInfo').modal('show');

            } else {

                $http.get(API_URL + 'rolPago/getConceptos').success(function(response){

                    console.log(response);

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

                        if(response[i].id_conceptospago === 1){

                            $scope.cuentaLiquida = {
                                idplancuenta: response[i].contabilidad[0].idplancuenta,
                                concepto: response[i].contabilidad[0].concepto
                            };

                            $scope.sueldo_liquido = response[i].contabilidad[0].concepto;
                            $scope.sueldo_liquido_h = response[i].contabilidad[0].idplancuenta;

                            var cantidad = {
                                value: "",
                                writable: true,
                                enumerable: true,
                                configurable: true
                            };
                            Object.defineProperty(response[i], 'cantidad', cantidad);

                            var observacion = {
                                value: "",
                                writable: true,
                                enumerable: true,
                                configurable: true
                            };
                            Object.defineProperty(response[i], 'observacion', observacion);

                            var valorTotal = {
                                value: "",
                                writable: true,
                                enumerable: true,
                                configurable: true
                            };
                            Object.defineProperty(response[i], 'valorTotal', valorTotal);
                            $scope.sueldos.push(response[i]);
                        }

                        if(response[i].id_conceptospago === 2){

                            var cantidad = {
                                value: "",
                                writable: true,
                                enumerable: true,
                                configurable: true
                            };
                            Object.defineProperty(response[i], 'cantidad', cantidad);

                            var observacion = {
                                value: "",
                                writable: true,
                                enumerable: true,
                                configurable: true
                            };
                            Object.defineProperty(response[i], 'observacion', observacion);

                            var valorTotal = {
                                value: "",
                                writable: true,
                                enumerable: true,
                                configurable: true
                            };
                            Object.defineProperty(response[i], 'valorTotal', valorTotal);
                            $scope.sueldos.push(response[i]);
                        }


                        if(response[i].id_categoriapago === 1 && response[i].grupo === '1'){

                            var cantidad = {
                                value: "",
                                writable: true,
                                enumerable: true,
                                configurable: true
                            };
                            Object.defineProperty(response[i], 'cantidad', cantidad);

                            var valor1 = {
                                value: "",
                                writable: true,
                                enumerable: true,
                                configurable: true
                            };
                            Object.defineProperty(response[i], 'valor1', valor1);

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
                            Object.defineProperty(response[i], 'observacion', observacion);

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
                            Object.defineProperty(response[i], 'cantidad', cantidad);

                            var valor1 = {
                                value: response[i].confignomina[0].value_imp,
                                writable: true,
                                enumerable: true,
                                configurable: true
                            };
                            Object.defineProperty(response[i], 'valor1', valor1);

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
                            Object.defineProperty(response[i], 'observacion', observacion);

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
                            Object.defineProperty(response[i], 'cantidad', cantidad);

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
                            Object.defineProperty(response[i], 'observacion', observacion);

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
                                value: response[i].confignomina[0].value_imp,
                                writable: true,
                                enumerable: true,
                                configurable: true
                            };
                            Object.defineProperty(response[i], 'cantidad', cantidad);

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
                            Object.defineProperty(response[i], 'observacion', observacion);

                            var cuenta = {
                                value: "",
                                writable: true,
                                enumerable: true,
                                configurable: true
                            };
                            Object.defineProperty(response[i], 'cuenta', cuenta);

                            if (parseInt(response[i].id_conceptospago) === 17 ) {
                                $scope.porcentageFR = response[i].cantidad;
                            }

                            $scope.beneficios.push(response[i]);

                        }
                        if(response[i].id_categoriapago === 3){

                            var cantidad = {
                                value: response[i].confignomina[0].value_imp,
                                writable: true,
                                enumerable: true,
                                configurable: true
                            };
                            Object.defineProperty(response[i], 'cantidad', cantidad);

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
                            Object.defineProperty(response[i], 'observacion', observacion);

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
                                value: response[i].confignomina[0].value_imp,
                                writable: true,
                                enumerable: true,
                                configurable: true
                            };
                            Object.defineProperty(response[i], 'cantidad', cantidad);

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
                            Object.defineProperty(response[i], 'observacion', observacion);

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

            }

        });

    };

    $scope.fillDataEmpleado = function () {

        //$scope.getConceptos();

        $scope.ingresos1.forEach(function(item){
            item.cantidad
        });

        var idempleado = $scope.empleado;

        $http.get(API_URL + 'rolPago/getDataEmpleado/'+ idempleado).success(function(response){

            if(response.length !== 0){

                $scope.identificacion = response[0].numdocidentific;
                $scope.cargo = response[0].namecargo;
                $scope.sueldo = response[0].salario;

                var f = new Date();

                var hoy =  f.getFullYear() + '-' + (f.getMonth() + 1) + '-' + f.getDate();

                var dias = $scope.restaFechas(response[0].fechaingreso,hoy);


                $scope.beneficios.forEach(function (value) {

                    if (parseInt(value.id_conceptospago) === 17 ) {

                        if (dias >= 365) {

                            value.cantidad = $scope.porcentageFR;

                        } else {

                            value.cantidad = 0;

                        }

                    }

                });

            }

        });

    };

    $scope.calcValores = function (item) {

        ss = $scope.sueldo;
        dc = $scope.diascalculo;
        hc = $scope.horascalculo;
        baseiess = $scope.baseiess;
        x = (item.cantidad !== "") ?  item.cantidad : 0;
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
            if (item.cantidad !== undefined && item.cantidad !== "" && item.valorTotal !== undefined ) {

                $scope.valortotalCantidad = parseInt($scope.valortotalCantidad) + parseInt(item.cantidad);

                if ($scope.valortotalCantidad <= 30){
                    if(item.aportaiess === true){
                        $scope.baseiess = (parseFloat($scope.baseiess) + parseFloat(item.valorTotal)).toFixed(2);
                    }

                    $scope.valortotalIngreso = (parseFloat($scope.valortotalIngreso) + parseFloat(item.valorTotal)).toFixed(2);
                    $scope.valortotalIngresoBruto = (parseFloat($scope.valortotalIngreso)).toFixed(2);
                }
                else{
                    $scope.valortotalCantidad = parseInt($scope.valortotalCantidad) - parseInt(item.cantidad);
                    item.cantidad = "";
                    item.valor1 = "";
                    item.valorTotal = "";

                    $scope.message_error = "El numero de dias introducidos no puede ser mayor al numero de dias calculos."
                    $('#modalError').modal('show');

                }
            }
        });

        $scope.ingresos2.forEach(function(item){

            if (item.cantidad !== undefined && item.valorTotal !== "") {
                if(item.aportaiess === true){
                    $scope.baseiess = parseFloat($scope.baseiess) + parseFloat(item.valorTotal);
                }
                $scope.valortotalIngresoBruto = (parseFloat($scope.valortotalIngresoBruto) + parseFloat(item.valorTotal)).toFixed(2);
            }
        });

        $scope.ingresos3.forEach(function(item){
            var aux_total=(item.valorTotal.toString()!=="")?parseFloat(item.valorTotal):0;
            var aux_porcentaje=parseFloat(item.cantidad);
            var aux_valor_porcentaje=(($scope.baseiess*aux_porcentaje)/100);
            item.valormax = parseFloat(aux_valor_porcentaje).toFixed(2);
            if (item.valorTotal !== undefined && item.valorTotal !== ""){
                if(aux_total>aux_valor_porcentaje){
                    item.valorTotal = "";
                    $scope.message_error = "El valor introducido no puede ser mayor al 20% de la base de aporte al IESS. Por favor introduzca un nuevo valor."
                    $('#modalError').modal('show');
                }else{
                    $scope.valortotalIngresoBruto = (parseFloat($scope.valortotalIngresoBruto) + parseFloat(aux_total)).toFixed(2);
                }
            }
        });

        $scope.total_deducciones = 0;
        $scope.ingresoBruto_deducciones = $scope.valortotalIngresoBruto;
        $scope.deducciones.forEach(function (item) {
            x = (item.cantidad !== "") ?  item.cantidad : 0;
            baseiess = parseFloat($scope.baseiess);
            if(item.formulatotal !== '' && item.formulatotal !== null){
                var total = parseFloat(eval(item.formulatotal));
                item.valorTotal = total.toFixed(2);
            }
            if (item.valorTotal !== undefined && item.valorTotal !== "") {
                $scope.ingresoBruto_deducciones = (parseFloat($scope.ingresoBruto_deducciones) - parseFloat(item.valorTotal)).toFixed(2);
                $scope.total_deducciones = (parseFloat($scope.total_deducciones) + parseFloat(item.valorTotal)).toFixed(2);
                $scope.ingresoBruto_beneficios = (parseFloat($scope.ingresoBruto_deducciones) + parseFloat($scope.total_beneficios)).toFixed(2);
            }
        });

        $scope.total_beneficios = 0;
        $scope.ingresoBruto_beneficios = $scope.ingresoBruto_deducciones;
        $scope.beneficios.forEach(function (item) {
            x = (item.cantidad !== "") ?  item.cantidad : 0;
            baseiess = parseFloat($scope.baseiess);
            if(item.formulatotal !== '' && item.formulatotal !== null){
                var total = parseFloat(eval(item.formulatotal));
                item.valorTotal = total.toFixed(2);
            }

            if (item.valorTotal !== undefined && item.valorTotal !== "") {
                $scope.ingresoBruto_beneficios = (parseFloat($scope.ingresoBruto_beneficios) + parseFloat(item.valorTotal)).toFixed(2);
                $scope.total_beneficios = (parseFloat($scope.total_beneficios) + parseFloat(item.valorTotal)).toFixed(2);
            }
        });

        $scope.total_adicionales = 0;
        $scope.sueldoliquido  = $scope.ingresoBruto_beneficios;
        $scope.benefadicionales.forEach(function (item) {
            x = (item.cantidad !== "") ?  item.cantidad : 0;
            baseiess = parseFloat($scope.baseiess);
            if(item.formulatotal !== '' && item.formulatotal !== null){
                var total = parseFloat(eval(item.formulatotal));
                item.valorTotal = total.toFixed(2);
            }

            if (item.valorTotal !== undefined && item.valorTotal !== "") {
                $scope.total_adicionales = (parseFloat($scope.total_adicionales) + parseFloat(item.valorTotal)).toFixed(2);
                $scope.total_empresarial = (parseFloat($scope.total_adicionales) + parseFloat($scope.sueldoliquido)).toFixed(2);
            }
        });

    };

    $scope.showPlanCuenta = function (field_concepto, field_id) {

        $scope.fieldconcepto = field_concepto;
        $scope.fieldid = field_id;

        $http.get(API_URL + 'configuracion/getPlanCuenta').success(function(response){
            $scope.cuentas = response;
            $('#modalPlanCuenta').modal('show');
        });

    };

    $scope.selectCuenta = function () {
        var selected = $scope.select_cuenta;

        $scope.cuentaLiquida = $scope.select_cuenta;

        var fieldconcepto = $parse($scope.fieldconcepto);
        fieldconcepto.assign($scope, selected.concepto);

        var fieldid = $parse($scope.fieldid);
        fieldid.assign($scope, selected.idplancuenta);

        $('#modalPlanCuenta').modal('hide');

    };

    $scope.click_radio = function (item) {
        $scope.select_cuenta = item;
    };

    $scope.valueFecha = function () {

        $scope.fecha = $('#fecha').val();

    };

    $scope.valuePeriodo = function () {

        $scope.periodo = $('#periodo').val();

    };

    $scope.InicioList=function() {
        $scope.listado =  true;
        $scope.initLoad(1);
    };

    $scope.getRoles = function () {

        $http.get(API_URL + 'rolPago/getRoles').success(function(response){

            $scope.roles = response;

        });
    };

    $scope.activeForm = function (action) {

        $scope.listado = false;

        $('#btn-save').show();
    };

    $scope.viewInfoRol = function (item) {

        /*$scope.sueldos = [];
        $scope.ingresos1 = [];
        $scope.ingresos2 = [];
        $scope.ingresos3 = [];
        $scope.beneficios = [];
        $scope.deducciones = [];
        $scope.benefadicionales = [];*/

        $http.get(API_URL + 'rolPago/getRolPago/' + item.numdocumento).success(function(response){

            console.log(response);

            $scope.rolSelected = item.numdocumento;

            $scope.estadoanulado = item.estadoanulado;
            $scope.numdocumento = item.numdocumento;
            $scope.empleado = item.id_empleado;
            $scope.identificacion = response[0].numdocidentific;
            $scope.cargo = response[0].namecargo;
            $scope.sueldo = response[0].salario;
            $scope.fecha = item.fecha;
            $scope.periodo = response[0].periodo;

            var longitud = response.length;

            for (var i = 0; i < longitud; i++) {

                for (var j = 0; j < $scope.ingresos1.length; j++) {
                    if (response[i].id_conceptopago === $scope.ingresos1[j].id_conceptospago) {
                        $scope.ingresos1[j].valorTotal = response[i].valormoneda;
                        $scope.ingresos1[j].cantidad = response[i].valormedida;
                    }
                }

                for (var x = 0; x < $scope.ingresos2.length; x++) {
                    if (response[i].id_conceptopago === $scope.ingresos2[x].id_conceptospago) {
                        $scope.ingresos2[x].valorTotal = response[i].valormoneda;
                        $scope.ingresos2[x].cantidad = response[i].valormedida;
                    }
                }

                for (var x = 0; x < $scope.ingresos3.length; x++) {
                    if (response[i].id_conceptopago === $scope.ingresos3[x].id_conceptospago) {
                        $scope.ingresos3[x].valorTotal = response[i].valormoneda;
                        $scope.ingresos3[x].cantidad = response[i].valormedida;
                    }
                }

                for (var x = 0; x < $scope.beneficios.length; x++) {
                    if (response[i].id_conceptopago === $scope.beneficios[x].id_conceptospago) {
                        $scope.beneficios[x].valorTotal = response[i].valormoneda;
                        $scope.beneficios[x].cantidad = response[i].valormedida;
                    }
                }

                for (var x = 0; x < $scope.deducciones.length; x++) {
                    if (response[i].id_conceptopago === $scope.deducciones[x].id_conceptospago) {
                        $scope.deducciones[x].valorTotal = response[i].valormoneda;
                        $scope.deducciones[x].cantidad = response[i].valormedida;
                    }
                }

                for (var x = 0; x < $scope.benefadicionales.length; x++) {
                    if (response[i].id_conceptopago === $scope.benefadicionales[x].id_conceptospago) {
                        $scope.benefadicionales[x].valorTotal = response[i].valormoneda;
                        $scope.benefadicionales[x].cantidad = response[i].valormedida;
                    }
                }

            }


            if (response[0].id_conceptopago === $scope.sueldos[0].id_conceptospago) {

                $scope.cuentaLiquida = {
                    idplancuenta: $scope.sueldos[0].contabilidad[0].idplancuenta,
                    concepto: $scope.sueldos[0].contabilidad[0].concepto
                };

                $scope.sueldo_liquido = $scope.sueldos[0].contabilidad[0].concepto;
                $scope.sueldo_liquido_h = $scope.sueldos[0].contabilidad[0].idplancuenta;

            }

            $scope.calcValores(response[2]);

            $scope.listado = false;

            $('#btn-save').hide();

        });

    };

    $scope.save = function () {

        var nameempleado = '';

        for (var j = 0; $scope.empleados.length; j++) {
            if (parseInt($scope.empleado) === parseInt($scope.empleados[j].id)) {
                nameempleado = $scope.empleados[j].label;
                break;
            }
        }

        /*
         * -------------------------INICIO CONTABILIDAD-------------------------------------------------------------
         */

        var descripcion = 'ROL PAGO A: ' + nameempleado;
        var fecha = $('#fecha').val();
        /*var array_fecha = fecha.split("-");
        var anno = array_fecha[0];
        var mes = array_fecha[1];*/

        var array_fecha = $scope.periodo.split("-");
        var anno = array_fecha[0];
        var mes = array_fecha[1];

        var transaccion = {
            fecha: fecha,
            idtipotransaccion: 12,
            numcomprobante: 1,
            descripcion: descripcion
        };

        var registroC = [];

        //--------------------------------------CONCEPTOS SUELDOS-------------------------------------------------------

        $scope.sueldos[0].valorTotal = (parseFloat($scope.sueldoliquido)).toFixed(4);
        $scope.sueldos[1].valorTotal = (parseFloat($scope.valortotalIngreso)).toFixed(4);

        var longitud_sueldos = $scope.sueldos.length;

        for (var i = 0; i < longitud_sueldos; i++) {

            if ($scope.sueldos[i].id_conceptospago === 1 && $scope.sueldos[i].valorTotal !== '') {

                var itemliquido = {
                    idplancuenta: $scope.sueldo_liquido_h,
                    concepto: $scope.sueldo_liquido,
                    controlhaber: $scope.sueldos[i].contabilidad[0].controlhaber,
                    tipocuenta: $scope.sueldos[i].contabilidad[0].tipocuenta,
                    Haber: 0,
                    Debe: $scope.sueldos[i].valorTotal,
                    Descipcion: descripcion
                };

                registroC.push(itemliquido);
            }

            if ($scope.sueldos[i].id_conceptospago === 2 && $scope.sueldos[i].valorTotal !== '') {
                var itembasico = {
                    idplancuenta: $scope.sueldos[i].contabilidad[0].idplancuenta,
                    concepto: $scope.sueldos[i].contabilidad[0].concepto,
                    controlhaber: $scope.sueldos[i].contabilidad[0].controlhaber,
                    tipocuenta: $scope.sueldos[i].contabilidad[0].tipocuenta,
                    Haber: $scope.sueldos[i].valorTotal,
                    Debe: 0,
                    Descipcion: descripcion
                };

                registroC.push(itembasico);
            }

        }
        //--------------------------------------CONCEPTOS INGRESO 2-----------------------------------------------------

        var longitud_ingreso2 = $scope.ingresos2.length;

        for (var i = 0; i < longitud_ingreso2; i++) {

            if ($scope.ingresos2[i].valorTotal !== '') {
                var item = {
                    idplancuenta: $scope.ingresos2[i].contabilidad[0].idplancuenta,
                    concepto: $scope.ingresos2[i].contabilidad[0].concepto,
                    controlhaber: $scope.ingresos2[i].contabilidad[0].controlhaber,
                    tipocuenta: $scope.ingresos2[i].contabilidad[0].tipocuenta,
                    Haber: (parseFloat($scope.ingresos2[i].valorTotal)).toFixed(4),
                    Debe: 0,
                    Descipcion: descripcion
                };

                registroC.push(item);
            }

        }

        //--------------------------------------CONCEPTOS INGRESO 3-----------------------------------------------------

        var longitud_ingreso3 = $scope.ingresos3.length;

        for (var i = 0; i < longitud_ingreso3; i++) {

            if ($scope.ingresos3[i].valorTotal !== '') {
                var item0 = {
                    idplancuenta: $scope.ingresos3[i].contabilidad[0].idplancuenta,
                    concepto: $scope.ingresos3[i].contabilidad[0].concepto,
                    controlhaber: $scope.ingresos3[i].contabilidad[0].controlhaber,
                    tipocuenta: $scope.ingresos3[i].contabilidad[0].tipocuenta,
                    Haber: (parseFloat($scope.ingresos3[i].valorTotal)).toFixed(4),
                    Debe: 0,
                    Descipcion: descripcion
                };

                registroC.push(item0);
            }

        }

        //--------------------------------------CONCEPTOS BENEFICIOS----------------------------------------------------

        var longitud_beneficios = $scope.beneficios.length;

        for (var i = 0; i < longitud_beneficios; i++) {

            if ($scope.beneficios[i].valorTotal !== '') {
                var item1 = {
                    idplancuenta: $scope.beneficios[i].contabilidad[0].idplancuenta,
                    concepto: $scope.beneficios[i].contabilidad[0].concepto,
                    controlhaber: $scope.beneficios[i].contabilidad[0].controlhaber,
                    tipocuenta: $scope.beneficios[i].contabilidad[0].tipocuenta,
                    Haber: (parseFloat($scope.beneficios[i].valorTotal)).toFixed(4),
                    Debe: 0,
                    Descipcion: descripcion
                };

                registroC.push(item1);
            }

        }

        //--------------------------------------CONCEPTOS DEDUCCIONES---------------------------------------------------

        var longitud_deducciones= $scope.deducciones.length;

        for (var i = 0; i < longitud_deducciones; i++) {

            if ($scope.deducciones[i].valorTotal !== '') {
                var item2 = {
                    idplancuenta: $scope.deducciones[i].contabilidad[0].idplancuenta,
                    concepto: $scope.deducciones[i].contabilidad[0].concepto,
                    controlhaber: $scope.deducciones[i].contabilidad[0].controlhaber,
                    tipocuenta: $scope.deducciones[i].contabilidad[0].tipocuenta,
                    Debe: (parseFloat($scope.deducciones[i].valorTotal)).toFixed(4),
                    Haber: 0,
                    Descipcion: descripcion
                };

                registroC.push(item2);
            }

        }

        //--------------------------------------CONCEPTOS BENEFICIOS ADICIONALES----------------------------------------

        var longitud_benefadicionales= $scope.benefadicionales.length;

        for (var i = 0; i < longitud_benefadicionales; i++) {

            if ($scope.benefadicionales[i].valorTotal !== '') {
                var item_a = {
                    idplancuenta: $scope.benefadicionales[i].contabilidad[0].idplancuenta,
                    concepto: $scope.benefadicionales[i].contabilidad[0].concepto,
                    controlhaber: $scope.benefadicionales[i].contabilidad[0].controlhaber,
                    tipocuenta: $scope.benefadicionales[i].contabilidad[0].tipocuenta,
                    Haber: (parseFloat($scope.benefadicionales[i].valorTotal)).toFixed(4),
                    Debe: 0,
                    Descipcion: descripcion
                };

                var item_b = {
                    idplancuenta: $scope.benefadicionales[i].contabilidad[1].idplancuenta,
                    concepto: $scope.benefadicionales[i].contabilidad[1].concepto,
                    controlhaber: $scope.benefadicionales[i].contabilidad[1].controlhaber,
                    tipocuenta: $scope.benefadicionales[i].contabilidad[1].tipocuenta,
                    Debe: (parseFloat($scope.benefadicionales[i].valorTotal)).toFixed(4),
                    Haber: 0,
                    Descipcion: descripcion
                };

                registroC.push(item_a);
                registroC.push(item_b);
            }

        }


        //--------------------------------------SUELDO LIQUIDO----------------------------------------------------------

        /*var sueldoliquido = {
            idplancuenta: $scope.cuentaLiquida.idplancuenta,
            concepto: $scope.cuentaLiquida.concepto,
            controlhaber: $scope.cuentaLiquida.controlhaber,
            tipocuenta: $scope.cuentaLiquida.tipocuenta,
            Debe: (parseFloat($scope.sueldoliquido)).toFixed(4),
            Haber: 0,
            Descipcion: descripcion
        };

        registroC.push(sueldoliquido);

        //--------------------------------------SUELDO BASICO-----------------------------------------------------------

        var sueldobasico = {
            idplancuenta: $scope.dataSueldoBasico.contabilidad[0].idplancuenta,
            concepto: $scope.dataSueldoBasico.contabilidad[0].concepto,
            controlhaber: $scope.dataSueldoBasico.contabilidad[0].controlhaber,
            tipocuenta: $scope.dataSueldoBasico.contabilidad[0].tipocuenta,
            Haber: (parseFloat($scope.valortotalIngreso)).toFixed(4),
            Debe: 0,
            Descipcion: descripcion
        };

        registroC.push(sueldobasico);*/

        var Contabilidad={
            transaccion: transaccion,
            registro: registroC
        };

        /*
         * -------------------------FIN CONTABILIDAD E INICIO DEL ROL DE PAGO----------------------------------------------------------------
         */

        $scope.dataRoldePago = $scope.sueldos.concat($scope.ingresos1.concat($scope.ingresos2.concat($scope.ingresos3.concat($scope.beneficios.concat($scope.deducciones.concat($scope.benefadicionales))))));
        console.log($scope.dataRoldePago);

        var data_full = {
            dataContabilidad: JSON.stringify(Contabilidad),
            idempleado: $scope.empleado,
            diascalculo: $scope.diascalculo,
            horascalculo: $scope.horascalculo,
            fecha: fecha,
            periodo: $scope.periodo,
            numdocumento: parseInt($scope.empleado.toString() + mes + anno),
            dataRoldePago: $scope.dataRoldePago
        };

        console.log(data_full);

        var url = API_URL + 'rolPago';

        $http.post(url, data_full).success(function (response) {
            if (response.success === true) {

                $scope.rolSelected = parseInt($scope.empleado.toString() + mes + anno);

                //$scope.idretencion = response.idretencioncompra;
                //$('#btn-export').show();
                $scope.message = 'Se insertó correctamente el rol de pago del trabajador seleccionado...';
                $('#modalMessage').modal('show');
                //$scope.hideModalMessage();


            } else {
                $scope.message_error = 'Ha ocurrido un error al intentar guardar el rol de pago...';
                $('#modalMessageError').modal('show');
            }
        }).error(function (res) {});

    };

    $scope.showModalConfirm = function(item){

        $scope.numdocumento = item.numdocumento;
        $scope.idtransaccion = item.numtransaccion;

        $('#modalConfirmAnular').modal('show');
    };

    $scope.anularRol = function(){

        var object = {
            numdocumento: $scope.numdocumento,
            idtransaccion: $scope.idtransaccion
        };

        $http.post(API_URL + 'rolPago/anularRol', object).success(function(response) {

            $('#modalConfirmAnular').modal('hide');

            if(response.success === true){
                $scope.initLoad(1);
                $scope.numdocumento = 0;
                $scope.message = 'Se ha anulado el rol de pago seleccionado...';
                $('#modalMessage').modal('show');

                $('#btn-anular').prop('disabled', true);

            } else {
                $scope.message_error = 'Ha ocurrido un error al intentar anular el rol de pago seleccionado...';
                $('#modalError').modal('show');
            }

        });
    };

    $scope.restaFechas = function(f1, f2) {

        var aFecha1 = f1.split('-');
        var aFecha2 = f2.split('-');
        var fFecha1 = Date.UTC(aFecha1[0],aFecha1[1]-1,aFecha1[2]);
        var fFecha2 = Date.UTC(aFecha2[0],aFecha2[1]-1,aFecha2[2]);
        var dif = fFecha2 - fFecha1;
        return Math.floor(dif / (1000 * 60 * 60 * 24));

    };

    $scope.printRol = function() {

        var accion = API_URL + 'rolPago/reporte_print/' + $scope.rolSelected;

        $('#WPrint_head').html('Rol de Pago');

        $('#bodyprint').html("<object width='100%' height='600' data='" + accion + "'></object>");

        $('#WPrint').modal('show');


    };

});