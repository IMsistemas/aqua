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

            $http.get(API_URL + 'retencionCompra/getRetenciones?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){
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

            $http.get(API_URL + 'retencionCompras/' + $scope.idretencion).success(function(response){

                $scope.t_fechaingreso = $scope.convertDatetoDB(response[0].fecharetencion, true);
                $scope.t_nroretencion = (response[0].numeroretencion).trim();

                $('#t_nrocompra').val((response[0].codigocompra).toString());

                $scope.$broadcast('angucomplete-alt:changeInput', 't_nrocompra', (response[0].codigocompra).toString());

                $scope.t_rucci = response[0].numerodocumentoproveedor;
                $scope.t_razonsocial = response[0].razonsocialproveedor;
                $scope.t_phone = response[0].telefonoproveedor;
                $scope.t_direccion = response[0].direccionproveedor;
                $scope.t_ciudad = response[0].nombreciudad;
                $scope.t_tipocomprobante = response[0].nombretipocomprobante;

                var serial = (response[0].serialretencion).split('-');

                $scope.t_establ = serial[0];
                $scope.t_pto = serial[1];
                $scope.t_secuencial = serial[2];

                $scope.t_nroautorizacion = response[0].autorizacion;

                $('#btn-createrow').prop('disabled', false);

                $scope.baseimponible = response[0].subtotalnoivacompra;
                $scope.baseimponibleIVA = response[0].ivacompra;

                $http.get(API_URL + 'retencionCompra/getRetencionesByCompra/' + $scope.idretencion).success(function(data){

                    var longitud = data.length;
                    for (var i = 0; i < longitud; i++){
                        var object_row = {
                            year: (response[0].fecharetencion).split('-')[0],
                            codigo: data[i].codigosri,
                            detalle: data[i].concepto,
                            id: data[i].iddetalleretencion,
                            baseimponible: '0.00',
                            porciento: data[i].poecentajeretencion,
                            valor: data[i].valorretenido
                        };

                        if (data[i].idtiporetencion == 1) {
                            object_row.baseimponible = response[0].subtotalnoivacompra;
                        } else {
                            object_row.baseimponible = response[0].ivacompra;
                        }

                        ($scope.itemretencion).push(object_row);
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                    $scope.recalculateTotal();
                    $('#btn-export').show();
                    $scope.active = '1';
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


        $scope.newForm = function () {
            $scope.t_fechaingreso = $scope.nowDate();
            $scope.t_nroretencion = '';

            $('#t_nrocompra').val('');
            $scope.t_nrocompra = '';
            $scope.$broadcast('angucomplete-alt:changeInput', 't_nrocompra', '');

            $scope.t_rucci = '';
            $scope.t_razonsocial = '';
            $scope.t_phone = '';
            $scope.t_direccion = '';
            $scope.t_ciudad = '';
            $scope.t_tipocomprobante = '';

            $scope.t_establ = '';
            $scope.t_pto = '';
            $scope.t_secuencial = '';

            $scope.t_nroautorizacion = '';

            $('#btn-createrow').prop('disabled', true);
            $('#btn-export').hide();

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
                valor: '0.00'
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

        $scope.save = function () {

            $scope.t_establ = $('#t_establ').val();
            $scope.t_pto = $('#t_pto').val();
            $scope.t_secuencial = $('#t_secuencial').val();

            var data = {
                numeroretencion: $scope.t_nroretencion,
                codigocompra: $('#t_nrocompra').val(),
                numerodocumentoproveedor: $scope.t_establ + '-' + $scope.t_pto + '-' + $scope.t_secuencial,
                fecha: $scope.convertDatetoDB($scope.t_fechaingreso),
                razonsocial: $scope.t_razonsocial,
                documentoidentidad: $scope.t_rucci,
                direccion: $scope.t_direccion,
                ciudad: $scope.t_ciudad,
                autorizacion: $scope.t_nroautorizacion,
                totalretencion: $scope.t_total,
                retenciones: $scope.itemretencion
            };

            var url = API_URL + 'retencionCompras';

            if ($scope.idretencion == 0) {
                $http.post(url, data).success(function (response) {
                    if (response.success == true) {
                        $scope.idretencion = response.idretencioncompra;
                        $('#btn-export').show();
                        $scope.message = 'Se insertó correctamente las Retenciones seleccionadas...';
                        $('#modalMessage').modal('show');
                        $scope.hideModalMessage();
                    } else {
                        $scope.message_error = 'Ha ocurrido un error al intentar guardar las Retenciones...';
                        $('#modalMessageError').modal('show');
                    }
                }).error(function (res) {});
            } else {
                $http.put(url + '/' + $scope.idretencion, data).success(function (response) {
                    if (response.success == true) {
                        $scope.message = 'Se actualizó correctamente las Retenciones seleccionadas...';
                        $('#modalMessage').modal('show');
                        $scope.hideModalMessage();
                    } else {
                        $scope.message_error = 'Ha ocurrido un error al intentar actualizar las Retenciones...';
                        $('#modalMessageError').modal('show');
                    }
                }).error(function (res) {});
            }
        };

        $scope.showInfoRetencion = function (object, data) {

            if (object.originalObject != undefined) {

                console.log(object.originalObject);

                data.id = object.originalObject.iddetalleimpuestoretencion;
                data.codigo = object.originalObject.codigosri;
                data.detalle = object.originalObject.namedetalleimpuestoretencion;
                data.tipo = object.originalObject.sri_tipoimpuestoretencion.nametipoimpuestoretencion;
                data.porciento = object.originalObject.porcentaje;

                var porciento = parseFloat(data.porciento);

                if (object.originalObject.idtiporetencion == 1) {
                    var baseimponible = parseFloat($scope.baseimponible);
                    data.baseimponible = $scope.baseimponible;
                } else {
                    var baseimponible = parseFloat($scope.baseimponibleIVA);
                    data.baseimponible = $scope.baseimponibleIVA;
                }

                var result = (porciento / 100) *  baseimponible;

                data.valor = result.toFixed(2);

                $scope.recalculateTotal();

                /*var codigocliente = object.originalObject.codigocliente;

                 if (codigocliente != 0 && codigocliente != undefined) {

                 $http.get(API_URL + 'cliente/getInfoCliente/' + codigocliente).success(function(response){
                 $scope.nom_new_cliente_setnombre = response[0].apellidos + ', ' + response[0].nombres;
                 $scope.direcc_new_cliente_setnombre = response[0].direcciondomicilio;
                 $scope.telf_new_cliente_setnombre = response[0].telefonoprincipaldomicilio;
                 $scope.celular_new_cliente_setnombre = response[0].celular;
                 $scope.telf_trab_new_cliente_setnombre = response[0].telefonoprincipaltrabajo;
                 $scope.h_codigocliente_new = codigocliente;
                 });

                 } else {
                 $scope.nom_new_cliente_setnombre = '';
                 $scope.direcc_new_cliente_setnombre = '';
                 $scope.telf_new_cliente_setnombre = '';
                 $scope.celular_new_cliente_setnombre = '';
                 $scope.telf_trab_new_cliente_setnombre = '';
                 }*/
            } else {
                data.codigo = '';
                data.id = 0;
                data.detalle = '';
                data.tipo = '';
                data.porciento = '0.00';
                data.baseimponible = '0.00';
            }

        };

        $scope.showDataPurchase = function (object) {

            console.log(object);

            if (object.originalObject != undefined) {

                $scope.t_rucci = object.originalObject.proveedor.persona.numdocidentific;
                $scope.t_razonsocial = object.originalObject.proveedor.persona.razonsocial;
                $scope.t_nroautorizacion = object.originalObject.sri_comprobanteretencion.noauthcomprobante;
                var nocomprobante = object.originalObject.sri_comprobanteretencion.nocomprobante;

                nocomprobante = nocomprobante.split('-');

                $scope.t_establ = nocomprobante[0];
                $scope.t_pto = nocomprobante[1];
                $scope.t_secuencial = nocomprobante[2];

                /*$scope.t_phone = object.originalObject.telefonoproveedor;
                $scope.t_direccion = object.originalObject.direccionproveedor;
                $scope.t_tipocomprobante = object.originalObject.nombretipocomprobante;
                $scope.t_ciudad = object.originalObject.nombreciudad;*/

                //$scope.baseimponible = object.originalObject.subtotalnoivacompra;
                $scope.baseimponible = object.originalObject.subtotalconimpuestocompra;

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
        }

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

