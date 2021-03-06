
app.controller('recaudacionCobroController',  function($scope, $http, API_URL) {


    $scope.item_select = 0;
    $scope.Cliente = 0;
    $scope.select_cuenta = null;

    $scope.listSelected = [];

    $scope.pago_anular = '';

    $scope.fecha_i = '';

    $scope.initLoad = function(){

        $http.get(API_URL + 'factura/verifyPeriodo').success(function(response){
            (response.success == false) ? $('#btn-generate').prop('disabled', false) : $('#btn-generate').prop('disabled', false);
        });

        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'YYYY-MM-DD'
        });

        var filter = {
            inicio: $('#fechainicio').val(),
            fin: $('#fechafin').val()
        };

        $http.get(API_URL + 'recaudacioncobro/getFacturas?filter=' + JSON.stringify(filter)).success(function(response){

            console.log(response);

            var listado = [];

            var longitud = response.length;

            for (var i = 0; i < longitud; i++) {

                if (response[i].total === null && response[i].total !== undefined ) {
                    response[i].total = 0;
                }

                var longitud_cobros = response[i].cont_cuentasporcobrar.length;

                var suma = 0;

                for (var j = 0; j < longitud_cobros; j++) {

                    if (response[i].cont_cuentasporcobrar[j].estadoanulado === false){
                        suma += parseFloat(response[i].cont_cuentasporcobrar[j].valorpagado);
                    }

                }

                var complete_name = {
                    value: suma.toFixed(2),
                    writable: true,
                    enumerable: true,
                    configurable: true
                };
                Object.defineProperty(response[i], 'valorcobrado', complete_name);


                var acobrar = {
                    value: '0',
                    writable: true,
                    enumerable: true,
                    configurable: true
                };
                Object.defineProperty(response[i], 'acobrar', acobrar);

                //------------------------------------------------------------------------------------------------------

                var cuotas = {
                    value: '0.00',
                    writable: true,
                    enumerable: true,
                    configurable: true
                };
                Object.defineProperty(response[i], 'cuotas', cuotas);

                if (response[i].suministro !== undefined) {

                    if (response[i].suministro.length > 0) {

                        var cant_cuotas = parseInt(response[i].suministro[0].dividendocredito);
                        var totalsuministro_venta = parseFloat(response[i].valortotalventa);

                        var valor_cuota = totalsuministro_venta / cant_cuotas;

                        response[i].cuotas = valor_cuota.toFixed(2);

                        /*for (var x = 0; x < cant_cuotas; x++) {

                            var obj = response[i];

                            obj.valortotalventa = valor_cuota.toFixed(2);

                            listado.push(obj);

                        }*/

                    }

                }

                listado.push(response[i]);

                //------------------------------------------------------------------------------------------------------



            }

            console.log(listado);

            $scope.list = listado;

        });

    };

    $scope.generate = function () {

        var result_agua = false;
        var result_servicio = false;

        $http.get(API_URL + 'factura/generate').success(function(response){

            //console.log(response);

            if (response.success === true) {
                result_agua = true;
            }

            $http.get(API_URL + 'cobroservicio/generate').success(function(response){

                //console.log(response);

                if (response.success === true) {
                    result_servicio = true;
                }

                if (result_agua === true && result_servicio === true) {

                    $scope.initLoad();

                    $scope.message = 'Se ha generado los cobros de Lecturas/Servicios del mes actual correctamente...';
                    $('#modalMessage').modal('show');
                } else {
                    $scope.message_error = 'Ha ocurrido un error al intentar generar los Cobros respectivos al mes...';
                    $('#modalMessageError').modal('show');
                }


            });

        });



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
        console.log(item);
        $scope.select_cuenta = item;
    };

    $scope.showModalListCobro = function (item) {

        console.log(item);

        $scope.item_select = item;

        if (item.fecharegistroventa !== undefined) {
            $scope.fecha_i = item.fecharegistroventa;
        } else {
            $scope.fecha_i = item.fechacobro;
        }

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

        if (item.iddocumentoventa !== undefined && item.iddocumentoventa !== null && item.idlectura === undefined) {
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

        console.log($scope.listcobro)
    };

    $scope.pushListSelect = function (tipo, id, item) {

        //console.log($('#' + tipo + id));

        var checked = $('#' + tipo + id).prop('checked');

        if (checked === true) {

            if ($scope.listSelected.length === 0) {
                item.acobrar = item.valortotalventa - item.valorcobrado;
                $scope.listSelected.push(item);
            } else {
                if ($scope.listSelected[0].idcliente === item.idcliente){
                    item.acobrar = item.valortotalventa - item.valorcobrado;
                    $scope.listSelected.push(item);
                } else {

                }
            }

        } else {
            item.acobrar = '0';
            var pos = $scope.listSelected.indexOf(item);
            $scope.listSelected.splice(pos, 1);
        }

        console.log($scope.listSelected);
    };

    $scope.showModalListCobro2 = function () {


        console.log($scope.listSelected);

        //$('#listCobros').modal('show');

        var longitud = $scope.listSelected.length;

        for (var i = 0; i < longitud; i++) {

            var item = $scope.listSelected[i];


            /*if ($scope.listSelected[i].iddocumentoventa !== undefined && $scope.listSelected[i].iddocumentoventa !== '') {



            } else if ($scope.listSelected[i].idcobroservicio !== undefined && $scope.listSelected[i].idcobroservicio !== '') {



            } else if ($scope.listSelected[i].idcobroagua !== undefined && $scope.listSelected[i].idcobroagua !== '') {



            }*/

            console.log(item);

            $scope.item_select = item;

            if (item.fecharegistroventa !== undefined) {
                $scope.fecha_i = item.fecharegistroventa;
            } else {
                $scope.fecha_i = item.fechacobro;
            }

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

            if (item.iddocumentoventa !== undefined && item.iddocumentoventa !== null && item.idlectura === undefined) {
                $http.get(API_URL + 'cuentasxcobrar/getCobros/' + item.iddocumentoventa).success(function(response){

                    $scope.listcobro = response;

                    //$scope.valorpendiente = (item.valortotalventa - item.valorcobrado).toFixed(2);

                    //$('#listCobros').modal('show');

                });
            } else if (item.idcobroservicio !== undefined) {
                $http.get(API_URL + 'cuentasxcobrar/getCobrosServices/' + item.idcobroservicio).success(function(response){

                    $scope.listcobro = response;

                    //$scope.valorpendiente = (item.total - item.valorcobrado).toFixed(2);

                    //$('#listCobros').modal('show');

                });
            } else {
                $http.get(API_URL + 'cuentasxcobrar/getCobrosLecturas/' + item.idcobroagua).success(function(response){

                    $scope.listcobro = response;

                    //$scope.valorpendiente = (item.total - item.valorcobrado).toFixed(2);

                    //$('#listCobros').modal('show');

                });
            }

            console.log($scope.listcobro)

        }


        $('#listCobros').modal('show');

    };

    $scope.showModalConfirm = function(item){
        $scope.pago_anular = item.idcuentasporcobrar;

        $('#modalConfirmAnular').modal('show');
    };

    $scope.showModalFormaCobro = function () {

        $scope.getFormaPago();

        $http.get(API_URL + 'cuentasxcobrar/getLastID').success(function(response){

            if (response === '') {
                response = 0;
            }

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
            //$('#fecharegistro').val('');

            var longitud = $scope.listSelected.length;

            var acobrar = 0;
            var total = 0;

            for (var i = 0; i < longitud; i++) {

                acobrar = acobrar + parseFloat($scope.listSelected[i].acobrar);
                total = total + parseFloat($scope.listSelected[i].total);

            }

            //var pendiente =

            $scope.valorpendiente = (acobrar).toFixed(2);
            $scope.valorrecibido = (acobrar).toFixed(2);


            $http.get(API_URL + 'cuentasxcobrar/getDefaultCxC').success(function(response0){

                //console.log(response0);

                $scope.cuenta_employee = response0[0].concepto;

                $http.get(API_URL + 'cuentasxcobrar/getCuentaCxC/' + response0[0].optionvalue).success(function(response){

                    $scope.select_cuenta = response[0];

                    $('#formCobros').modal('show');
                });


            });
        });

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

    /*
    -----------------------------------------------------------------------------------------------------------------
     */

    $scope.autoAssignDate = function () {

        $scope.fecharegistro = $('#fecharegistro').val();

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

        /*if ($scope.concepto !== undefined) {
            descripcion = $scope.concepto;
        }*/


        if (descripcion === '') {
            if ($scope.item_select.iddocumentoventa !== undefined) {
                descripcion = 'Cuentas x Cobrar Factura: ' + $scope.item_select.numdocumentoventa;
            } else if ($scope.item_select.idcobroservicio !== undefined) {
                descripcion = 'Cuentas x Cobrar Solicitud Servicio';
            } else {
                descripcion = 'Cuentas x Cobrar Toma Lectura';
            }
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
            Descipcion: descripcion
        };

        RegistroC.push(cliente);

        var cobro = {
            idplancuenta: $scope.select_cuenta.idplancuenta,
            concepto: $scope.select_cuenta.concepto,
            controlhaber: $scope.select_cuenta.controlhaber,
            tipocuenta: $scope.select_cuenta.tipocuenta,
            Debe: parseFloat($scope.valorrecibido),
            Haber: 0,
            Descipcion: descripcion
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

        var longitud = $scope.listSelected.length;

        for (var i = 0; i < longitud; i++) {
            if ($scope.listSelected[i].idcobroservicio !== undefined) {
                id = $scope.listSelected[i].idcobroservicio;
                type = 'servicio';
            } else if ($scope.listSelected[i].idcobroagua !== undefined) {
                id = $scope.listSelected[i].idcobroagua;
                type = 'lectura';
            } else {
                id = $scope.listSelected[i].iddocumentoventa;
                type = 'venta';
            }

            var type_trans = {
                value: type,
                writable: true,
                enumerable: true,
                configurable: true
            };

            Object.defineProperty($scope.listSelected[i], 'type', type_trans);
        }

        /*if ($scope.item_select.idcobroservicio !== undefined) {
            id = $scope.item_select.idcobroservicio;
            type = 'servicio';
        } else if ($scope.item_select.idcobroagua !== undefined) {
            id = $scope.item_select.idcobroagua;
            type = 'lectura';
        } else {
            id = $scope.item_select.iddocumentoventa;
            type = 'venta';
        }*/

        if (parseFloat($scope.valorpendiente) >= parseFloat($scope.valorrecibido)) {

            var data = {
                idcliente: $scope.Cliente.idcliente,
                nocomprobante: $scope.nocomprobante,
                fecharegistro: $('#fecharegistro').val(),
                idformapago: $scope.formapago,
                cobrado: $scope.valorrecibido,
                cuenta: $scope.select_cuenta.idplancuenta,
                iddocumentoventa: id,
                descripcion: descripcion,
                type: type,
                listSelected: $scope.listSelected,
                contabilidad: JSON.stringify(transaccion_venta_full)
            };

            console.log(data);

            //console.log($scope.select_cuenta);

            $http.post(API_URL + 'cuentasxcobrar', data ).success(function (response) {

                $('#formCobros').modal('hide');

                if (response.success === true) {
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

    $scope.anular = function(){

        var object = {
            idcuentasporcobrar: $scope.pago_anular
        };

        $http.post(API_URL + 'cuentasxcobrar/anular', object).success(function(response) {

            $('#modalConfirmAnular').modal('hide');

            if(response.success === true){
                $scope.initLoad(1);
                $scope.pago_anular = 0;
                $scope.message = 'Se ha anulado el cobro seleccionado...';
                $('#modalMessage').modal('show');

                $scope.showModalListCobro($scope.item_select);

                //$('#btn-anular').prop('disabled', true);

            } else {
                $scope.message_error = 'Ha ocurrido un error al intentar anular el cobro seleccionado...';
                $('#modalMessageError').modal('show');
            }

        });
    };

    /*
     -----------------------------------------------------------------------------------------------------------------
     */

    $scope.printComprobante = function(id) {

        var accion = API_URL + 'cuentasxcobrar/printComprobante/' + id;

        $('#WPrint_head').html('Comprobante de Ingreso');

        $('#WPrint').modal('show');

        $('#bodyprint').html("<object width='100%' height='600' data='" + accion + "'></object>");
    };

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

    /*
     -----------------------------------------------------------------------------------------------------------------
     */

    $scope.printer = function (item) {


        console.log(item);

        var subtotal = 0;

        if (item.catalogoitem_cobroagua.length > 0) {

            for (var i = 0; i < item.catalogoitem_cobroagua.length; i++) {
                subtotal += parseFloat(item.catalogoitem_cobroagua[i].valor);
            }

        }

        if (item.otrosvalores_cobroagua.length > 0) {

            for (var i = 0; i < item.otrosvalores_cobroagua.length; i++) {
                subtotal += parseFloat(item.otrosvalores_cobroagua[i].valor);
            }

        }

        subtotal += parseFloat(item.valorexcedente);
        subtotal += parseFloat(item.valortarifabasica);
        subtotal += parseFloat(item.valormesesatrasados);

        var porcentaje_iva_cliente = parseFloat(item.suministro.cliente.sri_tipoimpuestoiva.porcentaje);

        var total_iva = 0;

        if(porcentaje_iva_cliente != 0){
            total_iva = (subtotal * porcentaje_iva_cliente) / 100;
        }

        var total = subtotal + total_iva;

        var date_p = (item.lectura.fechalectura).split('-');
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

        $http.post(API_URL + 'factura/print', a).success(function(response){
            console.log(response);

            /*var ventana = window.open(response.url);
            setTimeout(function(){ ventana.print(); }, 2000);*/

            $("#WPrint_head").html("Impresion");
            $("#WPrint").modal("show");
            $("#bodyprint").html("<object width='100%' height='600' data='"+response.url+"'></object>");

        });

    };

    $scope.printerServicio = function (item) {

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

    $scope.ShowModalFactura = function (item) {

        $http.get(API_URL + 'factura/getMultas').success(function(response){

            console.log(item);
            console.log(response);

            $scope.num_factura = item.idcobroagua;

            $scope.mes = Auxiliar(yearmonth(item.fechacobro));
            $scope.multa = '';

            $scope.documentoidentidad_cliente = item.suministro.cliente.persona.numdocidentific;
            $scope.nom_cliente = item.suministro.cliente.persona.razonsocial;
            $scope.direcc_cliente = item.suministro.cliente.persona.direccion;
            $scope.telf_cliente = item.suministro.cliente.persona.celphone;

            var arreg = [];
            var total = 0.00;

            /*if (item != null) {
                if (item.valortarifabasica == null) {
                    var valores_atrasados = {
                        nombre: "Consumo Agua" + ' - ' +  $scope.mes  ,
                        valor: 0.00,
                        id: 0
                    }
                }else {
                    var consumo_agua = {
                        nombre: "Consumo Agua" + ' - ' +  $scope.mes ,
                        valor: item.valortarifabasica,
                        id: 0
                    }
                }
                arreg.push(consumo_agua);

                if (item.valorexcedente == null) {
                    var excedente_agua = {
                        nombre: "Excedente Agua" + ' - ' +  $scope.mes,
                        valor:  0.00,
                        id: 0
                    }

                } else {
                    var excedente_agua = {
                        nombre: "Excedente Agua" + ' - ' +  $scope.mes,
                        valor: item.valorexcedente,
                        id: 0
                    }
                }
                arreg.push(excedente_agua);

                if (item.valormesesatrasados == null) {
                    var valores_atrasados = {
                        nombre: "Valores Atrasados",
                        valor: 0.00,
                        id: 0
                    }
                }else
                {
                    var valores_atrasados = {
                        nombre: "Valores Atrasados",
                        valor: item.valormesesatrasados,
                        id: 0
                    }
                }
                arreg.push(valores_atrasados);
            }*/

            if (item.catalogoitem_cobroagua != null) {
                if( item.catalogoitem_cobroagua.length > 0) {
                    $scope.servicios = item.catalogoitem_cobroagua;

                    for (var a = 0; a < $scope.servicios.length; a++) {
                        var auxiliar = {
                            nombre: $scope.servicios[a].cont_catalogitem.nombreproducto,
                            valor: $scope.servicios[a].valor,
                            id: 0
                        };
                        arreg.push(auxiliar);
                    }
                }
            } else {
                /*if( item.cliente.servicioscliente.length > 0) {
                    $scope.servicios = item.catalogoitem_cobroagua;

                    for (var a = 0; a < $scope.servicios.length; a++) {
                        var auxiliar = {
                            nombre: $scope.servicios[a].serviciojunta.nombreservicio,
                            valor: $scope.servicios[a].valor,
                            id: 0
                        }
                        arreg.push(auxiliar);
                    }
                }*/
            }


            //------------multas-----------------------------------

            /*if (response.length > 0){
                for (var j = 0; j < response.length; j++) {

                    var otrosvalores = {
                        nombre: response[j].nombreotrosvalores,
                        valor: 0.00,
                        id: response[j].idotrosvalores
                    };

                    if (item.otrosvalores_cobroagua.length > 0){
                        for (var x = 0; x < item.otrosvalores_cobroagua.length; x++) {
                            if (otrosvalores.id == item.otrosvalores_cobroagua[x].idotrosvalores) {
                                otrosvalores.valor = item.otrosvalores_cobroagua[x].valor;
                            }
                        }
                    }

                    arreg.push(otrosvalores);
                }
            }*/

            //------------fin multas-----------------------------------

            if (arreg.length > 0) {
                for (var i = 0; i < arreg.length; i++) {
                    total = total + parseFloat(arreg[i].valor);
                }
            }

            $scope.total = total.toFixed(2);

            $scope.aux_modal = arreg;

            $scope.item_select = item;

            if (item.estadopagado == true) {

                $('#footer-modal-factura').hide();

                $('#btn-save').prop('disabled', true);
                $('#btn-pagar').prop('disabled', true);
            } else {
                $('#footer-modal-factura').show();

                $('#btn-save').prop('disabled', false);
                $('#btn-pagar').prop('disabled', false);
            }


            $('#modalFactura').modal('show');
            // $scope.total =  parseFloat($scope.multa_asamblea) + parseFloat($scope.valores_atrasados) + parseFloat($scope.excedente_agua) + parseFloat($scope.consumo_agua);
        });

    };


    $scope.fechaByFilter();
    $scope.initLoad();

});
