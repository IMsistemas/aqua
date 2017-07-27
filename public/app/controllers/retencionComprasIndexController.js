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

    app.controller('retencionComprasController', function($scope, $http, API_URL) {

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

        $scope.active = '0';

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

            console.log(filtros);

            $http.get(API_URL + 'retencionCompra/getRetenciones?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

                //console.log(response.data);

                /*var longitud = response.data.length;
                for (var i = 0; i < longitud; i++) {

                    var longitud_sri_retenciondetallecompra = (response.data[i].sri_retenciondetallecompra).length;
                    var total = 0;

                    for (var j = 0; j < longitud_sri_retenciondetallecompra; j++) {
                        total += parseFloat(response.data[i].sri_retenciondetallecompra[j].valorretenido);
                    }

                    var total_retenido = {
                        value: total.toFixed(2),
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response.data[i], 'total_retenido', total_retenido);
                }*/

                var longitud = response.data.length;
                for (var i = 0; i < longitud; i++) {

                    var longitud_sri_retenciondetallecompra = (response.data[i].cont_documentocompra[0].sri_retencioncompra).length;

                    var total = 0;

                    if (longitud_sri_retenciondetallecompra > 0) {

                        var longitud_detalleretencion = response.data[i].cont_documentocompra[0].sri_retencioncompra[0].sri_retenciondetallecompra.length;


                        for (var j = 0; j < longitud_detalleretencion; j++) {

                            var valorretenido = response.data[i].cont_documentocompra[0].sri_retencioncompra[0].sri_retenciondetallecompra[j].valorretenido;

                            total += parseFloat(valorretenido);
                        }


                    }

                    var total_retenido = {
                        value: total.toFixed(2),
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response.data[i], 'total_retenido', total_retenido);
                }

                $scope.retencion = response.data;
                $scope.totalItems = response.total;
            });
        };

        $scope.initLoad(1);

        $scope.pageChanged = function(newPage) {
            $scope.initLoad(newPage);
        };

        $scope.loadFormPage = function(id){

            $scope.idretencion = id;



            $scope.estados = [
                { id: 1, name: 'SI' },
                { id: 2, name: 'NO' }
            ];

            $scope.regimenfiscal = 1;
            $scope.convenio = 1;
            $scope.normalegal = 1;

            $scope.getProveedores();

            $http.get(API_URL + 'DocumentoCompras/getPaisPagoComprobante').success(function(response){

                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];

                for (var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].pais, id: response[i].idpagopais})
                }

                $scope.listpaispago = array_temp;
                $scope.paispago = array_temp[0].id;

                $http.get(API_URL + 'DocumentoCompras/getTipoPagoComprobante').success(function(response){

                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];

                    for (var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].tipopagoresidente, id: response[i].idpagoresidente})
                    }

                    $scope.listtipopago = array_temp;
                    $scope.tipopago = array_temp[0].id;

                    $http.get(API_URL + 'retencionCompras/' + $scope.idretencion).success(function(response){

                        console.log(response);

                        $scope.retencion = response[0];

                        $scope.tipopago = response[0].idpagoresidente;
                        $('#fechaemisioncomprobante').val(response[0].fechaemisioncomprob);

                        if (response[0].regimenfiscal === true) {
                            $scope.regimenfiscal = 1;
                        } else $scope.regimenfiscal = 2;

                        if (response[0].conveniotributacion === true) {
                            $scope.convenio = 1;
                        } else $scope.convenio = 2;


                        if (response[0].normalegal === true) {
                            $scope.normalegal = 1;
                        } else $scope.normalegal = 2;

                        if (response[0].idpagopais !== null) {
                            $scope.paispago = response[0].idpagopais;
                        }

                        if (response[0].idpagoresidente === 1) {
                            $('#paispago').prop('disabled', true);
                        } else $('#paispago').prop('disabled', false);

                        $scope.ProveedorContable = response[0].cont_documentocompra[0].proveedor.cont_plancuenta;

                        $scope.iddocumentocompra = response[0].cont_documentocompra[0].iddocumentocompra;

                        $scope.t_fechaingreso = $scope.convertDatetoDB(response[0].fechaemisioncomprob, true);

                        $('#t_nrocompra').val((response[0].numdocumentocompra).toString());

                        $scope.$broadcast('angucomplete-alt:changeInput', 't_nrocompra', (response[0].numdocumentocompra).toString());

                        $scope.t_rucci = response[0].cont_documentocompra[0].proveedor.persona.numdocidentific;
                        $scope.t_razonsocial = response[0].cont_documentocompra[0].proveedor.persona.razonsocial;

                        var serial = (response[0].nocomprobante).split('-');

                        $scope.t_establ = serial[0];
                        $scope.t_pto = serial[1];
                        $scope.t_secuencial = serial[2];

                        $scope.t_nroautorizacion = response[0].noauthcomprobante;

                        $scope.baseimponible = response[0].cont_documentocompra[0].subtotalsinimpuestocompra;
                        $scope.baseimponibleIVA = response[0].cont_documentocompra[0].ivacompra;

                        $scope.proveedor = response[0].idproveedor;

                        var longitud_r = response[0].cont_documentocompra[0].sri_retencioncompra.length;

                        $scope.itemretencion = [];

                        if (longitud_r > 0) {

                            var longitud = response[0].cont_documentocompra[0].sri_retencioncompra[0].sri_retenciondetallecompra.length;

                            for (var i = 0; i < longitud; i++) {

                                var item = response[0].cont_documentocompra[0].sri_retencioncompra[0].sri_retenciondetallecompra[i];

                                var object_row = {
                                    year: (response[0].fechaemisioncomprob).split('-')[0],
                                    codigo: item.sri_detalleimpuestoretencion.codigosri,
                                    tipo: item.sri_detalleimpuestoretencion.sri_tipoimpuestoretencion.nametipoimpuestoretencion,
                                    detalle: item.sri_detalleimpuestoretencion.namedetalleimpuestoretencion,
                                    id: item.iddetalleimpuestoretencion,
                                    baseimponible: '0.00',
                                    porciento: item.porcentajeretenido,
                                    valor: item.valorretenido
                                };

                                if (item.sri_detalleimpuestoretencion.idtipoimpuestoretencion === 1) {
                                    object_row.baseimponible = $scope.baseimponible;
                                } else {
                                    object_row.baseimponible = $scope.baseimponibleIVA;
                                }

                                ($scope.itemretencion).push(object_row);
                                $('[data-toggle="tooltip"]').tooltip();

                            }

                            $('#btn-export').show();

                            $('#btn_save').prop('disabled', true);

                            $('#btn-createrow').prop('disabled', true);

                            $('.btn_delete').attr("disabled", true);

                            $(function(){
                                $("button.btn_delete").attr("disabled", true);
                            });


                        } else {
                            $('#btn-createrow').prop('disabled', false);
                            $('#btn_save').prop('disabled', false);

                            $(function(){
                                $("button.btn_delete").attr("disabled", false);
                            });
                        }

                        $scope.recalculateTotal();

                        $scope.active = '1';

                        $('#btn-createrow').prop('disabled', true);

                    });


                });

            });

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

            $scope.getLastIDRetencion();
            $scope.getTipoPagoComprobante();
            $scope.getPaisPagoComprobante();
            $scope.getProveedores();

            $scope.t_fechaingreso = $scope.nowDate();
            $scope.t_nroretencion = '';

            $('#t_nrocompra').val('');
            $scope.t_nrocompra = '';
            $scope.$broadcast('angucomplete-alt:changeInput', 't_nrocompra', '');
            $scope.$broadcast('angucomplete-alt:clearInput', 't_nrocompra');

            $scope.t_rucci = '';
            $scope.t_razonsocial = '';
            $scope.t_phone = '';
            $scope.t_direccion = '';
            $scope.t_ciudad = '';
            $scope.t_tipocomprobante = '';

            $scope.t_establ = '';
            $scope.t_pto = '';
            $scope.t_secuencial = '';

            // Campos de Comprobante-------

            $scope.estados = [
                { id: 1, name: 'SI' },
                { id: 2, name: 'NO' }
            ];

            $scope.regimenfiscal = 1;
            $scope.convenio = 1;
            $scope.normalegal = 1;

            $('#fechaemisioncomprobante').val('');

            $('#t_establ_c').val('000');
            $('#t_pto_c').val('000');
            $('#t_secuencial_c').val('000000000');


            $scope.t_nroautorizacion = '';

            $('#btn-createrow').prop('disabled', true);
            $('#btn-export').hide();

            $(function(){
                $("button.btn_delete").attr("disabled", false);
            });

            $scope.baseimponible = 0;
            $scope.baseimponibleIVA = 0;
            $scope.t_total = '';
            $scope.itemretencion = [];

            $scope.active = '1';
        };

        $scope.returnList = function() {
            $scope.active = '0';
            $scope.initLoad(1);
        };

        $scope.createRow = function () {

            //var base = parseFloat($scope.baseimponible).toFixed(2);

            var object_row = {
                year: ($scope.t_fechaingreso).split('/')[2],
                codigo: '',
                detalle: '',
                tipo: '',
                id:0,
                baseimponible: '0.00',
                porciento: '0.00',
                valor: '0.00',
                contabilidad: null
            };

            ($scope.itemretencion).push(object_row);
            $('[data-toggle="tooltip"]').tooltip();
        };

        $scope.recalculateRow = function (item) {

            if (item.porciento == ''){
                item.porciento = '0.00';
            }

            var porciento = parseFloat(item.porciento);
            var baseimponible = parseFloat(item.baseimponible);
            var result = (porciento / 100) *  baseimponible;
            item.valor = result.toFixed(2);
            $scope.recalculateTotal();
        };

        $scope.recalculateTotal = function() {
            var length = $scope.itemretencion.length;
            var total = 0;

            for (var i = 0; i < length; i++) {
                total += parseFloat($scope.itemretencion[i].valor);
            }

            $scope.t_total = total.toFixed(2);
        };

        $scope.deleteRow = function (item) {

            var index = ($scope.itemretencion).indexOf(item);

            var temp = $scope.itemretencion;
            var t2 = [];

            delete temp[index];

            for (var i = 0; i < temp.length; i++) {
                if(temp[i] != undefined) {
                    t2.push(temp[i]);
                }
            }

            $scope.itemretencion = t2;

            $scope.recalculateTotal();
        };

        $scope.valueFecha = function () {

            $scope.fechaemisioncomprobante = $('#fechaemisioncomprobante').val();

        };

        $scope.save = function () {

            $('#btn_save').prop('disabled', true);

            $scope.t_establ = $('#t_establ').val();
            $scope.t_pto = $('#t_pto').val();
            $scope.t_secuencial = $('#t_secuencial').val();


            /*
             * -------------------------INICIO CONTABILIDAD-------------------------------------------------------------
             */

            var descripcion = 'RETENCION COMPRA A: ' + $('#t_nrocompra').val();

            var transaccion = {
                fecha: $scope.t_fechaingreso,
                idtipotransaccion: 7,
                numcomprobante: 1,
                descripcion: descripcion
            };

            var registroC = [];

            var proveedor = {
                idplancuenta: $scope.ProveedorContable.idplancuenta,
                concepto: $scope.ProveedorContable.concepto,
                controlhaber: $scope.ProveedorContable.controlhaber,
                tipocuenta: $scope.ProveedorContable.tipocuenta,
                Debe: $scope.t_total,
                Haber: 0,
                Descipcion: descripcion
            };

            registroC.push(proveedor);

            var longitud_item = $scope.itemretencion.length;

            for (var i = 0; i < longitud_item; i++) {

                var item = {
                    idplancuenta: $scope.itemretencion[i].contabilidad.idplancuenta,
                    concepto: $scope.itemretencion[i].contabilidad.concepto,
                    controlhaber: $scope.itemretencion[i].contabilidad.controlhaber,
                    tipocuenta: $scope.itemretencion[i].contabilidad.tipocuenta,
                    Haber: (parseFloat($scope.itemretencion[i].valor)).toFixed(4),
                    Debe: 0,
                    Descipcion: descripcion
                };

                registroC.push(item);

            }

            var Contabilidad={
                transaccion: transaccion,
                registro: registroC
            };


            /*
             * -------------------------FIN CONTABILIDAD----------------------------------------------------------------
             */

            var pais = null;

            if ($scope.paispago !== null && $scope.paispago !== undefined && $scope.paispago !== '') {
                pais = $scope.paispago;
            }

            var dataComprobante = {
                tipopago: $scope.tipopago,
                paispago: pais,
                regimenfiscal: $scope.regimenfiscal,
                convenio: $scope.convenio,
                normalegal: $scope.normalegal,
                fechaemisioncomprobante: $('#fechaemisioncomprobante').val(),
                nocomprobante: $('#t_establ').val() + '-' + $('#t_pto').val() + '-' + $('#t_secuencial').val(),
                noauthcomprobante: $scope.t_nroautorizacion
            };

            var data_full = {
                dataContabilidad: JSON.stringify(Contabilidad),
                iddocumentocompra: $scope.iddocumentocompra,
                retenciones: $scope.itemretencion,
                dataComprobante: dataComprobante
            };

            console.log(data_full);

            var url = API_URL + 'retencionCompras';

            $http.post(url, data_full).success(function (response) {
                if (response.success == true) {
                    $scope.idretencion = response.idretencioncompra;
                    //$('#btn-export').show();
                    $scope.message = 'Se insertó correctamente las Retenciones seleccionadas...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                    $('#btn-createrow').prop('disabled', true);

                } else {
                    $scope.message_error = 'Ha ocurrido un error al intentar guardar las Retenciones...';
                    $('#modalMessageError').modal('show');
                }
            }).error(function (res) {});

        };

        $scope.anularRetencion = function(){

            var object = {
                idretencion: $scope.idretencion
            };

            $http.post(API_URL + 'retencionCompra/anularRetencion', object).success(function(response) {

                $('#modalConfirmAnular').modal('hide');

                if(response.success == true){
                    $scope.idretencion = 0;
                    $scope.initLoad(1);
                    $scope.message = 'Se ha anulado la Retención seleccionada...';
                    $('#modalMessage').modal('show');

                } else {
                    $scope.message_error = 'Ha ocurrido un error al intentar anular la Retención seleccionada...';
                    $('#modalMessageError').modal('show');
                }

            });
        };

        $scope.showModalConfirmAnular = function(item){

            console.log(item);

            $scope.idretencion = item.cont_documentocompra[0].sri_retencioncompra[0].idretencioncompra;
            $scope.numseriecompra = item.nocomprobante;
            $('#modalConfirmAnular').modal('show');
        };

        $scope.showModalConfirmAnular2 = function(){

            var item = $scope.retencion;

            console.log(item);

            $scope.idretencion = item.cont_documentocompra[0].sri_retencioncompra[0].idretencioncompra;
            $scope.numseriecompra = item.nocomprobante;
            $('#modalConfirmAnular').modal('show');
        };

        $scope.showInfoRetencion = function (object, data) {

            if (object.originalObject != undefined) {

                console.log(object.originalObject);

                if (object.originalObject.cont_plancuenta !== null) {
                    data.id = object.originalObject.iddetalleimpuestoretencion;
                    data.codigo = object.originalObject.codigosri;
                    data.detalle = object.originalObject.namedetalleimpuestoretencion;
                    data.tipo = object.originalObject.sri_tipoimpuestoretencion.nametipoimpuestoretencion;
                    data.porciento = object.originalObject.porcentaje;
                    data.contabilidad = object.originalObject.cont_plancuenta;

                    var porciento = parseFloat(data.porciento);

                    if (object.originalObject.idtipoimpuestoretencion == 1) {
                        var baseimponible = parseFloat($scope.baseimponible);
                        data.baseimponible = $scope.baseimponible;
                    } else {
                        var baseimponible = parseFloat($scope.baseimponibleIVA);
                        data.baseimponible = $scope.baseimponibleIVA;
                    }

                    var result = (porciento / 100) *  baseimponible;

                    data.valor = result.toFixed(2);

                    $scope.recalculateTotal();

                } else {
                    $scope.message_error = 'El Código de Retención seleccionado no tiene cuenta contable asignado...';
                    $('#modalMessageError').modal('show');
                }

            } else {
                data.codigo = '';
                data.id = 0;
                data.detalle = '';
                data.tipo = '';
                data.porciento = '0.00';
                data.baseimponible = '0.00';
                data.contabilidad = null;
            }

        };

        $scope.showDataPurchase = function (object) {

            console.log(object);

            if (object.originalObject != undefined) {

                $scope.ProveedorContable = object.originalObject.proveedor.cont_plancuenta;

                $scope.iddocumentocompra = object.originalObject.iddocumentocompra;

                $scope.t_rucci = object.originalObject.proveedor.persona.numdocidentific;
                $scope.t_razonsocial = object.originalObject.proveedor.persona.razonsocial;

                /*$scope.t_nroautorizacion = object.originalObject.sri_comprobanteretencion.noauthcomprobante;
                var nocomprobante = object.originalObject.sri_comprobanteretencion.nocomprobante;

                nocomprobante = nocomprobante.split('-');

                $scope.t_establ = nocomprobante[0];
                $scope.t_pto = nocomprobante[1];
                $scope.t_secuencial = nocomprobante[2];*/

                //$scope.baseimponible = object.originalObject.subtotalnoivacompra;
                $scope.baseimponible = object.originalObject.subtotalsinimpuestocompra;

                $scope.baseimponibleIVA = object.originalObject.ivacompra;

                //$('#t_nrocompra').val(object.originalObject.codigocompra);

                $('#btn-createrow').prop('disabled', false);
            }

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

        $scope.convertDatetoDB = function (now, revert) {
            if (revert == undefined){
                var t = now.split('/');
                return t[2] + '-' + t[1] + '-' + t[0];
            } else {
                var t = now.split('-');
                return t[2] + '/' + t[1] + '/' + t[0];
            }
        };

        $scope.typeResident = function () {

            $scope.paispago = '';

            if ($scope.tipopago == '1') {

                $('#paispago').prop('disabled', true);

            } else {

                $('#paispago').prop('disabled', false);

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

