
$(function () {
    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'YYYY-MM-DD'
    });
});

app.controller('cobroServicioController',  function($scope, $http, API_URL) {

    $scope.item_select = 0;
    $scope.Cliente = 0;
    $scope.select_cuenta = null;

    $scope.initLoad = function(){

        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'YYYY-MM-DD'
        });

        $http.get(API_URL + 'cobroservicio/getCobrosServicios').success(function(response){

            console.log(response);

            var longitud = response.length;

            for (var i = 0; i < longitud; i++) {
                var longitud_cobros = response[i].cont_cuentasporcobrar.length;

                var suma = 0;

                for (var j = 0; j < longitud_cobros; j++) {
                    suma += parseFloat(response[i].cont_cuentasporcobrar[j].valorpagado);
                }

                var complete_name = {
                    value: suma.toFixed(2),
                    writable: true,
                    enumerable: true,
                    configurable: true
                };
                Object.defineProperty(response[i], 'valorcobrado', complete_name);

                if (suma == response[i].total) {

                    var estado_pago = {
                        value: 'PAGADO',
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'estado_pago', estado_pago);

                } else {

                    var estado_pago = {
                        value: 'NO PAGADO',
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'estado_pago', estado_pago);

                }
            }

            $scope.list = response;

        });

    };

    $scope.generate = function () {
        $http.get(API_URL + 'cobroservicio/generate').success(function(response){

            console.log(response);

            if (response.success == true) {
                $scope.initLoad();

                $scope.message = 'Se ha generado los cobros del mes actual correctamente...';
                $('#modalMessage').modal('show');

            }
            else {
                $scope.message_error = 'Ha ocurrido un error...';
                $('#modalMessageError').modal('show');
            }

        });
    };

    $scope.printer = function (item) {

        $scope.infoCliente(item.idcliente);

        var subtotal = 0;

        setTimeout(function(){

            var longitud_i = item.solicitudservicio.catalogoitem_solicitudservicio.length;

            if (longitud_i > 0) {

                for (var i = 0; i < longitud_i; i++) {
                    subtotal += parseFloat(item.solicitudservicio.catalogoitem_solicitudservicio[i].valor);
                }

            }

            var porcentaje_iva_cliente = parseFloat($scope.Cliente.porcentaje);

            var total_iva = 0;

            if(porcentaje_iva_cliente != 0){
                total_iva = (subtotal * porcentaje_iva_cliente) / 100;
            }

            var total = subtotal + total_iva;

            var date_p = (item.fechacobro).split('-');
            var date_p0 = date_p[1] + '/' + date_p[0];

            var partial_date = {
                value: date_p0,
                writable: true,
                enumerable: true,
                configurable: true
            };
            Object.defineProperty(item, 'partial_date', partial_date);

            var subtotalfactura = {
                value: subtotal.toFixed(2),
                writable: true,
                enumerable: true,
                configurable: true
            };
            Object.defineProperty(item, 'subtotalfactura', subtotalfactura);

            var iva = {
                value: total_iva.toFixed(2),
                writable: true,
                enumerable: true,
                configurable: true
            };
            Object.defineProperty(item, 'iva', iva);

            var porcentaje_iva = {
                value: porcentaje_iva_cliente.toFixed(2),
                writable: true,
                enumerable: true,
                configurable: true
            };
            Object.defineProperty(item, 'porcentaje_iva', porcentaje_iva);

            var totalfactura = {
                value: total.toFixed(2),
                writable: true,
                enumerable: true,
                configurable: true
            };
            Object.defineProperty(item, 'totalfactura', totalfactura);

            console.log(item);

             var a = {
                item: item
             };

            //console.log(JSON.stringify(a));

            //var accion = API_URL + "cobroservicio/print/" + JSON.stringify(a);

            /*$("#WPrint_head").html("Libro Diario");
            $("#WPrint").modal("show");
            $("#bodyprint").html("<object width='100%' height='600' data='"+accion+"'></object>");*/

             $http.post(API_URL + 'cobroservicio/print', a).success(function(response){
                  //console.log(response);

                  /*var ventana = window.open(response.url);
                  setTimeout(function(){ ventana.print(); }, 2000);*/

                 $("#WPrint_head").html("Impresion");
                 $("#WPrint").modal("show");
                 $("#bodyprint").html("<object width='100%' height='600' data='"+response.url+"'></object>");

             });

        }, 3000);

    };

    /*
     ---------------------------------CUENTAS POR COBRAR-----------------------------------------------------------------
     */

    $scope.autoAssignDate = function () {

        $scope.fecharegistro = $('#fecharegistro').val();

    };

    $scope.showPlanCuenta = function () {

        $http.get(API_URL + 'empleado/getPlanCuenta').success(function(response){
            console.log(response);
            $scope.cuentas = response;
            $('#modalPlanCuenta').modal('show');
        });

    };

    $scope.selectCuenta = function () {
        var selected = $scope.select_cuenta;

        $scope.cuenta_employee = selected.concepto;

        $('#modalPlanCuenta').modal('hide');
    };

    $scope.click_radio = function (item) {
        $scope.select_cuenta = item;
    };

    $scope.showModalListCobro = function (item) {

        $scope.item_select = item;

        console.log(item);

        if (item.valortotalventa !== undefined) {
            if (item.valortotalventa !== item.valorcobrado) {
                $('#btn-cobrar').prop('disabled', false);
            } else {
                $('#btn-cobrar').prop('disabled', true);
            }
        } else {
            if (item.total !== item.valorcobrado) {
                $('#btn-cobrar').prop('disabled', false);
            } else {
                $('#btn-cobrar').prop('disabled', true);
            }
        }



        $scope.infoCliente(item.idcliente);

        if (item.iddocumentoventa !== undefined && item.iddocumentoventa !== null) {
            $http.get(API_URL + 'cuentasxcobrar/getCobros/' + item.iddocumentoventa).success(function(response){

                $scope.listcobro = response;

                $scope.valorpendiente = (item.valortotalventa - item.valorcobrado).toFixed(2);

                $('#listCobros').modal('show');

            });
        } else if (item.idcobroservicio !== undefined) {
            $http.get(API_URL + 'cuentasxcobrar/getCobrosServices/' + item.idcobroservicio).success(function(response){

                $scope.listcobro = response;

                $scope.valorpendiente = (item.total - item.valorcobrado).toFixed(2);

                $('#listCobros').modal('show');

            });
        } else {
            $http.get(API_URL + 'cuentasxcobrar/getCobrosLecturas/' + item.idcobroagua).success(function(response){

                $scope.listcobro = response;

                $scope.valorpendiente = (item.total - item.valorcobrado).toFixed(2);

                $('#listCobros').modal('show');

            });
        }
    };

    $scope.showModalFormaCobro = function () {

        $scope.getFormaPago();

        $http.get(API_URL + 'cuentasxcobrar/getLastID').success(function(response){

            $('.datepicker').datetimepicker({
                locale: 'es',
                format: 'YYYY-MM-DD',
                //format: 'DD/MM/YYYY',
                minDate: $scope.fecha_i
            });


            $('#fecharegistro').val($scope.fecha_i);

            $scope.fecharegistro = $scope.fecha_i;

            $scope.select_cuenta = null;

            $scope.nocomprobante = parseInt(response) + 1;
            $scope.valorrecibido = '';
            $scope.cuenta_employee = '';
            $('#fecharegistro').val('');

            $('#formCobros').modal('show');
        });

        /*$scope.select_cuenta = null;

        $scope.nocomprobante = '';
        $scope.valorrecibido = '';
        $scope.cuenta_employee = '';
        $('#fecharegistro').val('');

        $('#formCobros').modal('show');*/
    };

    $scope.getFormaPago = function () {
        $http.get(API_URL + 'DocumentoCompras/getFormaPago').success(function(response){

            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];
            for (var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].nameformapago, id: response[i].idformapago})
            }

            $scope.listformapago = array_temp;
            $scope.formapago = array_temp[0].id;

        });
    };


    $scope.infoCliente = function (idcliente) {
        $http.get(API_URL + 'nuevaLectura/getInfoClienteByID/'+ idcliente).success(function(response){

            $scope.Cliente = response[0];
            console.log($scope.Cliente);

        });
    };

    $scope.saveCobro = function () {

        $('#btn-ok').prop('disabled', true);

        var id = 0;
        var type = '';

        var descripcion = '';

        if ($scope.item_select.iddocumentoventa !== undefined) {
            descripcion = 'Cuentas x Cobrar Factura: ' + $scope.item_select.numdocumentoventa;
        } else if ($scope.item_select.idcobroservicio !== undefined) {
            descripcion = 'Cuentas x Cobrar Solicitud Servicio';
        } else {
            descripcion = 'Cuentas x Cobrar Toma Lectura';
        }


        /*
         * --------------------------------- CONTABILIDAD --------------------------------------------------------------
         */

        var Transaccion = {
            fecha: $('#fecharegistro').val(),
            idtipotransaccion: 4,
            numcomprobante: 1,
            descripcion: descripcion
        };

        var RegistroC = [];

        var cliente = {
            idplancuenta: $scope.Cliente.idplancuenta,
            concepto: $scope.Cliente.concepto,
            controlhaber: $scope.Cliente.controlhaber,
            tipocuenta: $scope.Cliente.tipocuenta,
            Debe: 0,
            Haber: parseFloat($scope.valorrecibido),
            Descipcion: ''
        };

        RegistroC.push(cliente);

        var cobro = {
            idplancuenta: $scope.select_cuenta.idplancuenta,
            concepto: $scope.select_cuenta.concepto,
            controlhaber: $scope.select_cuenta.controlhaber,
            tipocuenta: $scope.select_cuenta.tipocuenta,
            Debe: parseFloat($scope.valorrecibido),
            Haber: 0,
            Descipcion: ''
        };

        RegistroC.push(cobro);

        var Contabilidad = {
            transaccion: Transaccion,
            registro: RegistroC
        };

        var transaccion_venta_full = {
            DataContabilidad: Contabilidad
        };

        /*
         * --------------------------------- FIN CONTABILIDAD ----------------------------------------------------------
         */

        if ($scope.item_select.iddocumentoventa !== undefined && $scope.item_select.iddocumentoventa !== null) {
            id = $scope.item_select.iddocumentoventa;
            type = 'venta';
        } else if ($scope.item_select.idcobroservicio !== undefined) {
            id = $scope.item_select.idcobroservicio;
            type = 'servicio';
        } else {
            id = $scope.item_select.idcobroagua;
            type = 'lectura';
        }


        if (parseFloat($scope.valorpendiente) >= parseFloat($scope.valorrecibido)) {

            var data = {
                idcliente: $scope.Cliente.idcliente,
                nocomprobante: $scope.nocomprobante,
                fecharegistro: $('#fecharegistro').val(),
                idformapago: $scope.formapago,
                cobrado: $scope.valorrecibido,
                cuenta: $scope.select_cuenta.idplancuenta,
                iddocumentoventa: id,
                type: type,
                contabilidad: JSON.stringify(transaccion_venta_full)
            };

            console.log(data);

            console.log($scope.select_cuenta);

            $http.post(API_URL + 'cuentasxcobrar', data ).success(function (response) {

                $('#formCobros').modal('hide');

                if (response.success == true) {
                    $scope.initLoad();
                    $scope.showModalListCobro($scope.item_select);

                    $scope.message = 'Se insertó correctamente el Cobro...';
                    $('#modalMessage').modal('show');
                    //$scope.hideModalMessage();
                }
                else {
                    $scope.message_error = 'Ha ocurrido un error...';
                    $('#modalMessageError').modal('show');
                }
            });

        } else {

            $scope.message_error = 'El valor del Cobrado no puede ser superior al A Cobrar...';
            $('#modalMessageError').modal('show');

        }


    };

    /*
     ----------------------------------FIN CUENTAS POR COBRAR------------------------------------------------------------
     */

    $scope.fechaByFilter = function(){

        var f = new Date();

        //var toDay = f.getDate() + '/' + (f.getMonth() + 1) + '/' + f.getFullYear();

        var toDay = f.getFullYear() + '-' + (f.getMonth() + 1) + '-' + f.getDate();

        var primerDia = new Date(f.getFullYear(), f.getMonth(), 1);

        //var firthDayMonth = primerDia.getDate() + '/' + (f.getMonth() + 1) + '/' + f.getFullYear();

        var firthDayMonth = f.getFullYear() + '-' + (f.getMonth() + 1) + '-' + primerDia.getDate();

        $('#fechainicio').val(firthDayMonth);
        $('#fechafin').val(toDay);

        $scope.fechainicio = firthDayMonth;
        $scope.fechafin = toDay;

    };

    $scope.fechaByFilter();
    $scope.initLoad();

});
