
$(function () {
    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'YYYY-MM-DD'
    });
});

app.controller('cuentasporCobrarController',  function($scope, $http, API_URL) {


    $scope.item_select = 0;

    $scope.select_cuenta = null;

    $scope.initLoad = function(){

        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'YYYY-MM-DD'
        });

        var filter = {
            inicio: $('#fechainicio').val(),
            fin: $('#fechafin').val()
        };

        $http.get(API_URL + 'cuentasxcobrar/getFacturas?filter=' + JSON.stringify(filter)).success(function(response){

            $scope.list = response;

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

        $http.get(API_URL + 'cuentasxcobrar/getCobros/' + item.iddocumentoventa).success(function(response){

            $scope.listcobro = response;

            $('#listCobros').modal('show');

        });

    };

    $scope.showModalFormaCobro = function () {

        $scope.getFormaPago();

        $('#formCobros').modal('show');
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

    $scope.saveCobro = function () {

        var iddocumentoventa = 0;

        if ($scope.item_select.iddocumentoventa !== null && $scope.item_select.iddocumentoventa !== undefined) {
            iddocumentoventa = $scope.item_select.iddocumentoventa;
        }

        var data = {
            nocomprobante: $scope.nocomprobante,
            fecharegistro: $('#fecharegistro').val(),
            idformapago: $scope.formapago,
            cobrado: $scope.valorrecibido,
            cuenta: $scope.select_cuenta.idplancuenta,
            iddocumentoventa: iddocumentoventa
        };

        console.log(data);

        $http.post(API_URL + 'cuentasxcobrar', data ).success(function (response) {

            $('#formCobros').modal('hide');

            if (response.success == true) {
                $scope.showModalListCobro($scope.item_select);

                $scope.message = 'Se insertó correctamente el Cobro...';
                $('#modalMessage').modal('show');
                //$scope.hideModalMessage();
            }
            else {
                $scope.message_error = 'Ya existe ese Cargo...';
                $('#modalMessageError').modal('show');
            }
        });

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
