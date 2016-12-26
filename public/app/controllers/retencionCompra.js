/**
 * Created by Raidel Berrillo Gonzalez on 15/12/2016.
 */

    app.controller('retencionComprasController', function($scope, $http, API_URL) {

        $scope.tiporetencion = [
            { id: 0, name: '-- Todos --' },
            { id: 1, name: 'Retención IVA' },
            { id: 2, name: 'Retención Fuente a la Renta' }
        ];
        $scope.s_tiporetencion = 0;

        $scope.itemretencion = [];
        $scope.baseimponible = 0;

        $scope.loadFormPage = function(){
            window.open('retencionCompra/form', '_blank');
        };

        $scope.createRow = function () {

            var base = parseFloat($scope.baseimponible).toFixed(2);

            var object_row = {
                year: '2016',
                codigo: '',
                detalle: '',
                id:0,
                baseimponible: base,
                porciento: '0.00',
                valor: '0.00'
            };

            ($scope.itemretencion).push(object_row);
        };

        $scope.recalculateRow = function (item) {
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
                //codigocompra: $scope.t_nrocompra,
                codigocompra: $('#t_nrocompra').val(),
                numerodocumentoproveedor: $scope.t_establ + '-' + $scope.t_pto + '-' + $scope.t_secuencial,
                fecha: $scope.convertDatetoDB($scope.t_fechaingreso),
                razonsocial: $scope.t_razonsocial,
                documentoidentidad: $scope.t_rucci,
                direccion: $scope.t_direccion,
                ciudad: null,
                autorizacion: $scope.t_nroautorizacion,
                totalretencion: $scope.t_total,
                retenciones: $scope.itemretencion
            };

            console.log(data);

            var url = API_URL + 'retencionCompras';

            $http.post(url, data).success(function (response) {

                if (response.success == true) {
                    $scope.message = 'Se insertó correctamente las Retenciones seleccionadas...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                } else {
                    $scope.message_error = 'Ha ocurrido un error al intentar guardar las Retenciones...';
                    $('#modalMessageError').modal('show');
                }

            }).error(function (res) {

            });
        };

        $scope.showInfoRetencion = function (object, data) {

            if (object.originalObject != undefined) {
                data.id = object.originalObject.iddetalleretencionfuente;
                data.codigo = object.originalObject.codigoSRI;
                data.detalle = object.originalObject.nombreretencioniva;
                data.porciento = object.originalObject.porcentajevigente;

                var porciento = parseFloat(data.porciento);
                var baseimponible = parseFloat(data.baseimponible);

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
                data.porciento = '0.00';
            }

        };

        $scope.showDataPurchase = function (object) {
            console.log(object.originalObject);

            if (object.originalObject != undefined) {
                $scope.t_rucci = object.originalObject.numerodocumentoproveedor;
                $scope.t_razonsocial = object.originalObject.razonsocialproveedor;
                $scope.t_phone = object.originalObject.telefonoproveedor;
                $scope.t_direccion = object.originalObject.direccionproveedor;
                $scope.t_tipocomprobante = object.originalObject.nombretipocomprobante;

                $scope.baseimponible = object.originalObject.subtotalnoivacompra;

                $('#t_nrocompra').val(object.originalObject.codigocompra);

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
        }

        $scope.t_fechaingreso = $scope.nowDate();

    });


    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'DD/MM/YYYY',
            ignoreReadonly: false
        });
    });
