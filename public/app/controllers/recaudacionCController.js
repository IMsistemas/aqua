
app.controller('recaudacionCController',  function($scope, $http, API_URL) {

    $scope.item_select = 0;
    $scope.Cliente = 0;
    $scope.select_cuenta = null;

    $scope.listSelected = [];
    $scope.listItemsCobrar = [];
    $scope.listRegistro = [];
    $scope.idcliente = null;

    $scope.pago_anular = '';

    $scope.fecha_i = '';

    $scope.RegistroC=[];


    $('#modalFactura').on('hidden.bs.modal', function () {
        $scope.initLoad(1);

        if ($scope.idcliente !== null) {
            $scope.getItemsCobro($scope.idcliente);
        }

    });


    $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };

    $scope.initLoad = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search,
            estado: $scope.s_estado_search
        };

        $http.get(API_URL + 'recaudacionC/getClientes?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.clientes = response.data;
            $scope.totalItems = response.total;

        });
    };


    $scope.getItemsCobro = function (idcliente) {

        $scope.idcliente = idcliente;

        $http.get(API_URL + 'recaudacionC/getItemsCobro/' + idcliente).success(function(response){

            $scope.listItemsCobrar = [];

            var longitud = response.length;

            for (var i = 0; i < longitud; i++) {

                var item = {
                    idcatalogitem: response[i].idcatalogitem,
                    nombreproducto: response[i].nombreproducto,
                    valor: response[i].valor,
                    idcliente: response[i].idcliente,
                    acobrar: 0
                };

                $scope.listItemsCobrar.push(item);

            }

            $('#modalCobrosItems').modal('show');

        });

    };


    $scope.getRegistroCobro = function (idcliente) {

        $scope.idcliente = idcliente;

        $http.get(API_URL + 'recaudacionC/getRegistroCobro/' + idcliente).success(function(response){

            $scope.listRegistro = response;

            /*var longitud = response.length;

            for (var i = 0; i < longitud; i++) {

                var item = {
                    idcatalogitem: response[i].idcatalogitem,
                    nombreproducto: response[i].nombreproducto,
                    valor: response[i].valor,
                    idcliente: response[i].idcliente,
                    acobrar: 0
                };

                $scope.listItemsCobrar.push(item);

            }*/

            $('#modalRegistroCobros').modal('show');

        });

    };


    $scope.createFacturaItems = function () {

        var selected = [];

        var longitud = $scope.listItemsCobrar.length;

        for (var i = 0; i < longitud; i++) {

            if (parseFloat($scope.listItemsCobrar[i].acobrar) !== 0) {
                selected.push($scope.listItemsCobrar[i]);
            }

        }

        console.log(selected);

        $http.get(API_URL + 'recaudacionC/createFacturaItems?data=' + JSON.stringify(selected)).success(function(response){

            $scope.currentProjectUrl = '';

            $scope.currentProjectUrl = API_URL + 'DocumentoVenta?flag_suministro=1';
            $("#aux_venta").html("<object height='450px' width='100%' data='"+$scope.currentProjectUrl+"'></object>");
            $('#modalFactura').modal('show');

        });

    };

    $scope.showCierreCaja = function () {

        $http.get(API_URL + 'recaudacionC/getCuentasCerrar').success(function(response){


            if (response.length !== 0) {

                $scope.listCuentas = response;

                $scope.totalacerrar = 0;

                response.forEach(function (element) {

                    $scope.totalacerrar += parseFloat(element.valor);

                });

                $scope.totalacerrar = $scope.totalacerrar.toFixed(4);

                $('#listCuentasCerrar').modal('show');

            } else {

                $scope.message_error = 'No existe transaccion alguna...';
                $('#modalMessageError').modal('show');

            }


        });



    };

    $scope.AddIntemCotable=function(){
        var item={
            idplancuenta:"",
            aux_jerarquia:"",
            concepto:"",
            controlhaber:"",
            tipocuenta:'',
            Debe:0,
            Haber:0,
            Descipcion:""
        };
        $scope.RegistroC.push(item);
    };

    $scope.BorrarFilaAsientoContable=function(item){
        var posicion= $scope.RegistroC.indexOf(item);
        $scope.RegistroC.splice(posicion,1);
        //$scope.SumarDebeHaber();
    };


    $scope.aux_plancuentas=[];
    $scope.aux_cuentabuscar={};
    $scope.BuscarCuentaContable=function(registro){
        $scope.aux_cuentabuscar=registro;
        $("#PlanContable").modal("show");
        $http.get(API_URL + 'estadosfinacieros/plancontabletotal')
            .success(function(response){
                $scope.aux_plancuentas=response;
            });
    };


    $scope.AsignarCuentaContable=function(cuenta){
        $scope.aux_cuentabuscar.idplancuenta=cuenta.idplancuenta;
        $scope.aux_cuentabuscar.aux_jerarquia=cuenta.aux_jerarquia;
        $scope.aux_cuentabuscar.concepto=cuenta.concepto;
        $scope.aux_cuentabuscar.controlhaber=cuenta.controlhaber;
        $scope.aux_cuentabuscar.tipocuenta=cuenta.tipocuenta;
        $("#PlanContable").modal("hide");
        $scope.FiltraCuenta="";
    };

    $scope.ProcesarDatosAsientoContable=function () {

        var debe = 0;
        var haber = parseFloat($scope.totalacerrar);

        $scope.RegistroC.forEach(function (element) {

            debe += parseFloat(element.Debe);

        });

        if (debe !== haber) {

            $scope.message_error = 'No se puede realizar el Cierre de Caja debido a que no coinciden loas valores...';
            $('#modalMessageError').modal('show');

        } else {

            var f = new Date();

            var aux_fecha= f.getFullYear() + '-' + (f.getMonth() + 1) + '-' + f.getDate();

            var Transaccion={
                fecha: aux_fecha,
                idtipotransaccion: 3,
                numcomprobante: 0,
                descripcion: 'CIERRE CAJA: ' + f.getFullYear() + '-' + (f.getMonth() + 1) + '-' + f.getDate()
            };


            var registros = $scope.RegistroC;

            for (var i = 0; i < $scope.listCuentas.length; i++){

                var item={
                    idplancuenta: $scope.listCuentas[i].idplancuenta,
                    aux_jerarquia:$scope.listCuentas[i].jerarquia,
                    concepto:$scope.listCuentas[i].concepto,
                    controlhaber:$scope.listCuentas[i].controlhaber,
                    tipocuenta:$scope.listCuentas[i].tipocuenta,
                    Debe:0,
                    Haber:$scope.listCuentas[i].valor,
                    Descipcion:""
                };
                registros.push(item);

            }

            var Contabilidad={
                transaccion: Transaccion,
                registro: registros
            };
            $http.get(API_URL + 'estadosfinacieros/asc/'+JSON.stringify(Contabilidad))
                .success(function(response){
                    if(!isNaN(response)){

                        $http.delete(API_URL + 'recaudacionC/0' ).success(function (response) {

                            $('#formCobros').modal('hide');

                            if (response.success === true) {

                                $('#listCuentasCerrar').modal('hide');

                                $scope.message = 'Se realizo correctamente el Cierre de Caja...';
                                $('#modalMessage').modal('show');

                            }
                            else {
                                $scope.message_error = 'Ha ocurrido un error...';
                                $('#modalMessageError').modal('show');
                            }
                        });

                    }else{
                        $scope.message_error = 'Ha ocurrido un error...';
                        $('#modalMessageError').modal('show');
                    }

                });

        }

    };

    $scope.verifiedCobro = function (item) {

        if (parseFloat(item.acobrar) > parseFloat(item.valor)) {

            $scope.message_error = 'No debe situar un valor superior (' + item.acobrar + ') al valor total...';
            $('#modalMessageError').modal('show');

            item.acobrar = 0;

        } else {

            item.acobrar = parseFloat(item.acobrar).toFixed(2);

        }

    };

    $scope.onlyNumber = function ($event, length, field) {

        if (length != undefined) {
            var valor = $('#' + field).val();
            if (valor.length == length) $event.preventDefault();
        }

        var k = $event.keyCode;
        if (k == 8 || k == 0) return true;
        var patron = /\d/;
        var n = String.fromCharCode(k);

        if (n == ".") {
            return true;
        } else {

            if(patron.test(n) == false){
                $event.preventDefault();
            }
            else return true;
        }
    };

//-------------------------------------------------------------------------------------------------------------------


    $scope.getTransacciones = function (idcliente) {

        $scope.listTransacciones = [];

        $http.get(API_URL + 'recaudacionC/getFacConsumo/' + idcliente).success(function(response){

            var longitud_facConsumo = response.length;

            for (var i = 0; i < longitud_facConsumo; i++) {

                var obj = {
                    id: response[i].idcobroagua,
                    total: response[i].total,
                    fecha: response[i].fechacobro,
                    type: 'facConsumo',
                    name: 'Toma Lectura Consumo',
                    idtype : response[i].idcobroagua + '_' + 'facConsumo'
                };

                $scope.listTransacciones.push(obj)

            }

            $http.get(API_URL + 'recaudacionC/getDerechoAcometida/' + idcliente).success(function(response){


                var longitud_derAcometida = response.length;

                for (var i = 0; i < longitud_derAcometida; i++) {

                    if (response[i].valorcuotainicial !== null) {

                        var obj = {
                            id: response[i].idsuministro,
                            total: response[i].valorcuotainicial,
                            fecha: response[i].fechainstalacion,
                            type: 'derAcometida-cuotaInicial',
                            name: 'Cuota Inicial - Derecho Acometida No. Suministro: ' + response[i].idsuministro,
                            idtype : response[i].idsuministro + '_' + 'derAcometida-cuotaInicial'
                        };

                        $scope.listTransacciones.push(obj);

                    }

                    if (response[i].valorgarantia !== null) {

                        var obj = {
                            id: response[i].idsuministro,
                            total: response[i].valorgarantia,
                            fecha: response[i].fechainstalacion,
                            type: 'derAcometida-garantia',
                            name: 'Garantía - Derecho Acometida No. Suministro: ' + response[i].idsuministro,
                            idtype : response[i].idsuministro + '_' + 'derAcometida-garantia'
                        };

                        $scope.listTransacciones.push(obj);

                    }


                    if (parseInt(response[i].dividendocredito) === 1) {

                        var obj = {
                            id: response[i].idsuministro,
                            total: response[i].valortotalsuministro,
                            fecha: response[i].fechainstalacion,
                            type: 'derAcometida',
                            name: 'Derecho Acometida No. Suministro: ' + response[i].idsuministro,
                            idtype : response[i].idsuministro + '_' + 'derAcometida'
                        };

                        $scope.listTransacciones.push(obj);

                    } else {

                        var total = parseFloat(response[i].valortotalsuministro) / parseInt(response[i].dividendocredito);

                        for(var j = 0; j < parseInt(response[i].dividendocredito); j++) {

                            var obj = {
                                id: response[i].idsuministro,
                                total: total.toFixed(2),
                                fecha: response[i].fechainstalacion,
                                type: 'derAcometida',
                                name: 'Derecho Acometida (Cuota # ' + (j + 1) + ') No. Suministro: ' + response[i].idsuministro,
                                idtype : response[i].idsuministro + '_' + 'derAcometida'
                            };

                            $scope.listTransacciones.push(obj);

                        }

                    }



                }


                $http.get(API_URL + 'recaudacionC/getOtrosCargos/' + idcliente).success(function(response){

                    var longitud_otrosCargos = response.length;

                    for (var i = 0; i < longitud_otrosCargos; i++) {

                        var obj = {
                            id: response[i].idsolicitudservicio,
                            total: 0,
                            fecha: response[i].fechaprocesada,
                            type: 'otrosCargos',
                            name: 'Otros Cargos',
                            idtype : response[i].idsolicitudservicio + '_' + 'otrosCargos'
                        };

                        $scope.listTransacciones.push(obj)

                    }

                    $('#listCobros').modal('show');

                });



            });

        });

    };

    $scope.createFactura = function () {

        var selected = [];

        $(".transfer:checked").each(function() {

            var a = ($(this).val()).split('_');

            var o = {
                id: a[0],
                type: a[1]
            };

            selected.push(o);

        });

        console.log(selected);

        $http.get(API_URL + 'recaudacionC/createFactura?data=' + JSON.stringify(selected)).success(function(response){

            $scope.currentProjectUrl = '';

            $scope.currentProjectUrl = API_URL + 'DocumentoVenta?flag_suministro=1';
            $("#aux_venta").html("<object height='450px' width='100%' data='"+$scope.currentProjectUrl+"'></object>");
            $('#modalFactura').modal('show');

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

    $scope.getFacturas = function (idcliente) {

        $http.get(API_URL + 'recaudacionC/getFacturas/' + idcliente).success(function(response){

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



                listado.push(response[i]);

                //------------------------------------------------------------------------------------------------------

            }

            console.log(listado);

            $scope.list = listado;

            $('#modalCobros').modal('show');

        });

    };

    $scope.showModalListCobro2 = function () {


        console.log($scope.listSelected);

        //$('#listCobros').modal('show');

        var longitud = $scope.listSelected.length;

        for (var i = 0; i < longitud; i++) {

            var item = $scope.listSelected[i];

            console.log(item);

            $scope.item_select = item;

            $scope.fecha_i = item.fecharegistroventa;

            if (item.valortotalventa !== item.valorcobrado) {
                $('#btn-cobrar').prop('disabled', false);
            } else {
                $('#btn-cobrar').prop('disabled', true);
            }

            $scope.infoCliente(item.idcliente);

            $http.get(API_URL + 'cuentasxcobrar/getCobros/' + item.iddocumentoventa).success(function(response){

                $scope.listcobro = response;

            });

            console.log($scope.listcobro)

        }


        $('#listCobrosPago').modal('show');

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
                    $scope.showModalListCobro2($scope.item_select);

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

    $scope.infoCliente = function (idcliente) {

        $http.get(API_URL + 'nuevaLectura/getInfoClienteByID/'+ idcliente).success(function(response){

            $scope.Cliente = response[0];
            console.log($scope.Cliente);

        });

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

});
