
/*$(function () {
    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'YYYY-MM-DD'
    });


});*/

app.controller('cuentasporPagarController',  function($scope, $http, API_URL) {


    $scope.item_select = 0;
    $scope.Cliente = 0;
    $scope.select_cuenta = null;

    $scope.fecha_i = '';

    $scope.initLoad = function(){

        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'YYYY-MM-DD'
        });

        var filter = {
            inicio: $('#fechainicio').val(),
            fin: $('#fechafin').val()
        };

        $http.get(API_URL + 'cuentasxpagar/getFacturas?filter=' + JSON.stringify(filter)).success(function(response){

            console.log(response);

            $scope.list = response;

            var longitud = response.length;

            for (var i = 0; i < longitud; i++) {

                if (response[i].total === null && response[i].total !== undefined ) {
                    response[i].total = 0;
                }

                var longitud_cobros = response[i].cont_cuentasporpagar.length;

                var suma = 0;

                for (var j = 0; j < longitud_cobros; j++) {
                    suma += parseFloat(response[i].cont_cuentasporpagar[j].valorpagado);
                }

                var complete_name = {
                    value: suma.toFixed(2),
                    writable: true,
                    enumerable: true,
                    configurable: true
                };
                Object.defineProperty(response[i], 'valorcobrado', complete_name);

            }

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
        $scope.select_cuenta = item;
    };

    $scope.showModalListCobro = function (item) {

        $scope.item_select = item;

        $scope.fecha_i = item.fecharegistrocompra;

        if (item.valortotalcompra !== undefined) {
            if (item.valortotalcompra !== item.valorcobrado) {
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

        $scope.infoCliente(item.idproveedor);

        if (item.iddocumentocompra !== undefined && item.iddocumentocompra !== null) {
            $http.get(API_URL + 'cuentasxpagar/getCobros/' + item.iddocumentocompra).success(function(response){

                $scope.listcobro = response;

                $scope.valorpendiente = (item.valortotalcompra - item.valorcobrado).toFixed(2);

                $('#listCobros').modal('show');

            });
        }
    };

    $scope.showModalFormaCobro = function () {

        $scope.getFormaPago();

        $http.get(API_URL + 'cuentasxpagar/getLastID').success(function(response){

            $('.datepicker').datetimepicker({
                locale: 'es',
                format: 'YYYY-MM-DD',
                //format: 'DD/MM/YYYY',
                minDate: $scope.fecha_i
            });

            console.log($scope.fecha_i);

            $('#fecharegistro').val($scope.fecha_i);

            $scope.fecharegistro = $scope.fecha_i;

            $scope.select_cuenta = null;

            $scope.nocomprobante = parseInt(response) + 1;
            $scope.valorrecibido = '';
            $scope.cuenta_employee = '';
            $('#fecharegistro').val('');

            $('#formCobros').modal('show');
        });

        /*$('.datepicker').datetimepicker({
            locale: 'es',
            format: 'DD/MM/YYYY'
        });

        $scope.select_cuenta = null;

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

    /*
    -----------------------------------------------------------------------------------------------------------------
     */

    $scope.infoCliente = function (idcliente) {
        $http.get(API_URL + 'cuentasxpagar/getInfoClienteByID/'+ idcliente).success(function(response){

            $scope.Cliente = response[0];
            console.log($scope.Cliente);

        });
    };

    $scope.saveCobro = function () {

        var descripcion = 'Cuentas x Pagar Factura de Compra';

        /*
         * --------------------------------- CONTABILIDAD --------------------------------------------------------------
         */

        var Transaccion = {
            fecha: $('#fecharegistro').val(),
            idtipotransaccion: 5,
            numcomprobante: 1,
            descripcion: descripcion
        };

        var RegistroC = [];

        var proveedor = {
            idplancuenta: $scope.Cliente.idplancuenta,
            concepto: $scope.Cliente.concepto,
            controlhaber: $scope.Cliente.controlhaber,
            tipocuenta: $scope.Cliente.tipocuenta,
            Debe: 0,
            Haber: parseFloat($scope.valorrecibido),
            Descipcion: ''
        };

        RegistroC.push(proveedor);

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

        if (parseFloat($scope.valorpendiente) >= parseFloat($scope.valorrecibido)) {

            var data = {
                idproveedor: $scope.Cliente.idproveedor,
                nocomprobante: $scope.nocomprobante,
                fecharegistro: $('#fecharegistro').val(),
                idformapago: $scope.formapago,
                pagado: $scope.valorrecibido,
                cuenta: $scope.select_cuenta.idplancuenta,
                iddocumentocompra: $scope.item_select.iddocumentocompra,
                contabilidad: JSON.stringify(transaccion_venta_full)
            };

            console.log(data);

            console.log($scope.select_cuenta);

            $http.post(API_URL + 'cuentasxpagar', data ).success(function (response) {

                $('#formCobros').modal('hide');

                if (response.success == true) {
                    $scope.initLoad();
                    $scope.showModalListCobro($scope.item_select);

                    $scope.message = 'Se insertó correctamente el Pago...';
                    $('#modalMessage').modal('show');
                    //$scope.hideModalMessage();
                }
                else {
                    $scope.message_error = 'Ha ocurrido un error...';
                    $('#modalMessageError').modal('show');
                }
            });

        } else {

            $scope.message_error = 'El valor del Pagado no puede ser superior al A Pagar...';
            $('#modalMessageError').modal('show');

        }


    };

    /*
     -----------------------------------------------------------------------------------------------------------------
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
