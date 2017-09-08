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

    $scope.idcomprobantereembolso = 0;

    $scope.initLoad = function (pageNumber) {

        if ($scope.t_busqueda == undefined || $scope.t_busqueda == '') {
            var search = null;
        } else var search = $scope.t_busqueda;

        var filtros = {
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

    $scope.edit = function(item){

        $http.get(API_URL + 'cliente/getTipoIdentificacion').success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].nameidentificacion, id: response[i].idtipoidentificacion})
            }
            $scope.idtipoidentificacion = array_temp;
            $scope.tipoidentificacion = item.idtipoidentificacion;

            $http.get(API_URL + 'DocumentoVenta/getTipoComprobante').success(function(response){

                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                for (var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].namecomprobante, id: response[i].idtipocomprobante})
                }

                $scope.listtipocomprobante = array_temp;
                $scope.tipocomprobante = item.idtipocomprobante;


                $scope.numdocidentific = item.numdocidentific;

                var arrayNoReembolso = (item.numdocumentoreembolso).split('-');

                $scope.t_establ = arrayNoReembolso[0];
                $scope.t_pto = arrayNoReembolso[1];
                $scope.t_secuencial = arrayNoReembolso[2];

                $('#t_nrocompra').val((item.numdocumentocompra).toString());

                $scope.$broadcast('angucomplete-alt:changeInput', 't_nrocompra', (item.numdocumentocompra).toString());

                $scope.t_nroautorizacion = item.noauthreembolso;
                $scope.fechaemisioncomprobante = item.fechaemisionreembolso;
                $scope.t_tarifaivacero = item.ivacero;
                $scope.t_tarifadifcero = item.iva;
                $scope.t_tarifanoobj = item.ivanoobj;
                $scope.t_tarifaex = item.ivaexento;
                $scope.t_montoiva = item.montoiva;
                $scope.t_montoice = item.montoice;

                $scope.idcomprobantereembolso = item.idcomprobantereembolso;

                $scope.action_comp = 'Editar';

                $('#modalAction').modal('show');
            });


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

                $scope.numdocidentific = '';

                $scope.t_establ = '';
                $scope.t_pto = '';
                $scope.t_secuencial = '';

                $('#t_nrocompra').val('');
                $scope.$broadcast('angucomplete-alt:changeInput', 't_nrocompra', '');
                $scope.$broadcast('angucomplete-alt:clearInput', 't_nrocompra');

                $scope.t_nroautorizacion = '';
                $scope.fechaemisioncomprobante = '';
                $scope.t_tarifaivacero = '';
                $scope.t_tarifadifcero = '';
                $scope.t_tarifanoobj = '';
                $scope.t_tarifaex = '';
                $scope.t_montoiva = '';
                $scope.t_montoice = '';

                $scope.idcomprobantereembolso = 0;

                $scope.action_comp = 'Agregar';

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

        if ($scope.idcomprobantereembolso === 0) {

            $http.post(API_URL + 'reembolso', comprobante ).success(function (response) {

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

        } else {

            $http.put(API_URL + 'reembolso/'+ $scope.idcomprobantereembolso, comprobante ).success(function (response) {

                $scope.initLoad();

                $('#modalAction').modal('hide');

                if (response.success === true) {
                    $scope.initLoad(1);
                    $scope.message = 'Se editó correctamente el Comprobante de Reembolso seleccionado';

                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }
                else {

                    $scope.message_error = 'Ha ocurrido un error al intentar editar el Comprobante de Reembolso seleccionado..';

                    $('#modalMessageError').modal('show');
                }


            }).error(function (res) {

            });
        }

    };


    $scope.showModalConfirm = function (item) {

        console.log(item);

        $scope.idcomprobantereembolso = item.idcomprobantereembolso;
        $scope.comprobante_selecc = item.numdocumentoreembolso;
        $('#modalConfirmDelete').modal('show');
    };

    $scope.delete = function(){
        $http.delete(API_URL + 'reembolso/' + $scope.idcomprobantereembolso).success(function(response) {

            $('#modalConfirmDelete').modal('hide');

            if(response.success === true){

                $scope.initLoad(1);
                $scope.idcomprobantereembolso = 0;
                $scope.message = 'Se eliminó correctamente el Comprobante de Reembolso seleccionado...';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();

            } else {

                $scope.message_error = 'Ha ocurrido un error al intentar eliminar el Comprobante de Reembolso seleccionado..';
                $('#modalMessageError').modal('show');

            }
        });

    };


    $scope.valueFecha = function () {

        $scope.fechaemisioncomprobante = $('#fechaemisioncomprobante').val();

    };


    $scope.showDataPurchase = function (object) {

        //console.log(object);

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

