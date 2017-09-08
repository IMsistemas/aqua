/**
 * Created by Raidel Berrillo Gonzalez on 26/12/2016.
 */


app.filter('formatDate', function(){
    return function(fecha){
        var array_month = [
            'Ene', 'Feb', 'Marz', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'
        ];
        var t = fecha.split('-');
        return t[2] + '-' + array_month[t[1] - 1] + '-' + t[0];
    }
});

app.controller('reembolsoComprasController', function($scope, $http, API_URL) {

    $('.datepicker_a').datetimepicker({
        locale: 'es',
        format: 'YYYY'
    }).on('dp.change', function (e) {
        $scope.initLoad(1);
    });

    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'YYYY-MM-DD',
        ignoreReadonly: false
    });

    $scope.tiporetencion = [
        { id: 0, name: '-- Tipos de Retención --' },
        { id: 1, name: 'Retención IVA' },
        { id: 2, name: 'Retención Fuente a la Renta' }
    ];
    $scope.s_tiporetencion = 0;

    $scope.codigosretencion = [
        { id: 0, name: '-- Códigos de Retención --' }
    ];
    $scope.s_codigoretencion = 0;

    $scope.meses = [
        { id: 0, name: '-- Meses --' },
        { id: 1, name: 'Enero' },
        { id: 2, name: 'Febrero' },
        { id: 3, name: 'Marzo' },
        { id: 4, name: 'Abril' },
        { id: 5, name: 'Mayo' },
        { id: 6, name: 'Junio' },
        { id: 7, name: 'Julio' },
        { id: 8, name: 'Agosto' },
        { id: 9, name: 'Septiembre' },
        { id: 10, name: 'Octubre' },
        { id: 11, name: 'Noviembre' },
        { id: 12, name: 'Diciembre' }
    ];
    $scope.s_month = 0;

    $scope.retencion = [];

    $scope.idretencion = 0;

    $scope.itemretencion = [];
    $scope.baseimponible = 0;
    $scope.baseimponibleIVA = 0;
    $scope.idretencion = 0;
    $scope.retencion = '';

    $scope.iddocumentocompra = 0;

    $scope.ProveedorContable = null;

    $scope.initLoad = function (pageNumber) {
        $scope.idretencion = 0;

        $scope.t_year = $('#t_year').val();

        if ($scope.s_month == 0) {
            var m = null;
        } else var m = $scope.s_month;

        if ($scope.t_year == '') {
            var y = null;
        } else var y = $scope.t_year;

        if ($scope.t_busqueda == undefined || $scope.t_busqueda == '') {
            var search = null;
        } else var search = $scope.t_busqueda;

        var filtros = {
            month: m,
            year: y,
            search: search
        };

        //console.log(filtros);

        $http.get(API_URL + 'reembolso/getReembolsos?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            console.log(response.data);

            $scope.comprobantes = response.data;
            $scope.totalItems = response.total;

        });
    };

    $scope.initLoad(1);

    $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };

    $scope.loadFormPage = function(){


    };

    $scope.getCodigosRetencion = function () {
        var tipo = $scope.s_tiporetencion;

        if (tipo != 0) {
            $http.get(API_URL + 'retencionCompra/getCodigosRetencion/' + tipo).success(function(response){
                var longitud = response.length;
                var array_temp = [{ id: 0, name: '-- Códigos de Retención --' }];
                for (var i = 0; i < longitud; i++) {
                    array_temp.push({id: response[i].iddetalleretencionfuente, name: response[i].codigoSRI})
                }
                $scope.codigosretencion = array_temp;
                $scope.s_codigoretencion = 0;
            });
        } else {
            $scope.codigosretencion = [
                { id: 0, name: '-- Códigos de Retención --' }
            ];
            $scope.s_codigoretencion = 0;
        }
    };

    $scope.getLastIDRetencion = function () {

        $http.get(API_URL + 'retencionCompra/getLastIDRetencion').success(function(response){

            if (response != null && response != 0) {
                $scope.t_nroretencion = parseInt(response) + 1;
            } else {
                $scope.t_nroretencion = 1;
            }

        });
    };

    $scope.getTipoPagoComprobante = function () {

        $http.get(API_URL + 'DocumentoCompras/getTipoPagoComprobante').success(function(response){

            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];

            for (var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].tipopagoresidente, id: response[i].idpagoresidente})
            }

            $scope.listtipopago = array_temp;
            $scope.tipopago = array_temp[0].id

        });

    };

    $scope.getPaisPagoComprobante = function () {

        $http.get(API_URL + 'DocumentoCompras/getPaisPagoComprobante').success(function(response){

            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];

            for (var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].pais, id: response[i].idpagopais})
            }

            $scope.listpaispago = array_temp;
            $scope.paispago = array_temp[0].id

        });

    };

    $scope.getProveedores = function () {
        $http.get(API_URL + 'retencionCompra/getProveedores').success(function(response){

            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].razonsocial, id: response[i].idproveedor})
            }
            $scope.listproveedor = array_temp;
            $scope.proveedor = '';

        });
    };

    $scope.searchAPI = function(userInputString, timeoutPromise) {
        return $http.post(API_URL + 'retencionCompra/getCompras', {q: userInputString, idproveedor: $scope.proveedor}, {timeout: timeoutPromise});
    };

    $scope.newForm = function () {

        $http.get(API_URL + 'cliente/getTipoIdentificacion').success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].nameidentificacion, id: response[i].idtipoidentificacion})
            }
            $scope.idtipoidentificacion = array_temp;
            $scope.tipoidentificacion = '';

            $http.get(API_URL + 'DocumentoVenta/getTipoComprobante').success(function(response){

                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                for (var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].namecomprobante, id: response[i].idtipocomprobante})
                }

                $scope.listtipocomprobante = array_temp;
                $scope.tipocomprobante = '';

                $('#modalAction').modal('show');
            });


        });

    };


    $scope.save = function () {

        var numdocumentoreembolso = $('#t_establ').val() + '-' + $('#t_pto').val() + '-' + $('#t_secuencial').val();

        var comprobante = {
            iddocumentocompra: 2,
            idtipoidentificacion: $scope.tipoidentificacion,
            idtipocomprobante: $scope.tipocomprobante,
            numdocidentific: $scope.numdocidentific,
            numdocumentoreembolso: numdocumentoreembolso,
            noauthreembolso: $scope.t_nroautorizacion,
            fechaemisionreembolso: $scope.fechaemisioncomprobante,
            ivacero: $scope.t_tarifaivacero,
            iva: $scope.t_tarifadifcero,
            ivanoobj: $scope.t_tarifanoobj,
            ivaexento: $scope.t_tarifaex,
            montoiva: $scope.t_montoiva,
            montoice: $scope.t_montoice
        };

        console.log(comprobante);

        var url = API_URL + 'reembolso';

        $http.post(url, comprobante ).success(function (response) {

            console.log(response);

            $('#modalAction').modal('hide');

            if (response.success === true) {
                $scope.initLoad(1);
                $scope.message = 'Se guardó correctamente la información del Comprobante de Reembolso...';

                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            }
            else {

                $scope.message_error = 'Ha ocurrido un error..';

                $('#modalMessageError').modal('show');
            }
        });

    };


    $scope.valueFecha = function () {

        $scope.fechaemisioncomprobante = $('#fechaemisioncomprobante').val();

    };





    $scope.showDataPurchase = function (object) {

        console.log(object);

    };

    $scope.nowDate = function () {
        var now = new Date();
        var dd = now.getDate();
        if (dd < 10) dd = '0' + dd;
        var mm = now.getMonth() + 1;
        if (mm < 10) mm = '0' + mm;
        var yyyy = now.getFullYear();
        return dd + "\/" + mm + "\/" + yyyy;
    };

    $scope.convertDatetoDB = function (now, revert){
        if (revert == undefined){
            var t = now.split('/');
            return t[2] + '-' + t[1] + '-' + t[0];
        } else {
            var t = now.split('-');
            return t[2] + '/' + t[1] + '/' + t[0];
        }
    };

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };

    $scope.onlyDecimal = function ($event) {
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

    $scope.onlyCharasterAndSpace = function ($event) {

        var k = $event.keyCode;
        if (k == 8 || k == 0) return true;
        var patron = /^([a-zA-Záéíóúñ\s]+)$/;
        var n = String.fromCharCode(k);

        if(patron.test(n) == false){
            $event.preventDefault();
            return false;
        }
        else return true;

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

    $scope.calculateLength = function(field, length) {
        var text = $("#" + field).val();
        var longitud = text.length;
        if (longitud == length) {
            $("#" + field).val(text);
        } else {
            var diferencia = parseInt(length) - parseInt(longitud);
            var relleno = '';
            if (diferencia == 1) {
                relleno = '0';
            } else {
                var i = 0;
                while (i < diferencia) {
                    relleno += '0';
                    i++;
                }
            }
            $("#" + field).val(relleno + text);
        }
    };


});

$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'DD/MM/YYYY',
        ignoreReadonly: false
    });

    /*$('.datepicker_a').datetimepicker({
        locale: 'es',
        format: 'YYYY'
    }).on('dp.change', function (e) {
        $scope.initLoad(1);
    });*/
});

