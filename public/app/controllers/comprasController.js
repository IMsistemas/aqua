
    app.controller('comprasController',  function($scope, $http, API_URL) {

        $scope.compras = [];

        $scope.list_item = [];
        $scope.listado = true;

        $scope.estados = [
            {id: 1, nombre: 'ANULADO'},
            {id: 0, nombre: 'NO ANULADO'}
        ];

        $scope.pageChanged = function(newPage) {
            $scope.initLoad(newPage);
        };

        $scope.getProveedorByFilter = function () {
            $http.get(API_URL + 'DocumentoCompras/getProveedorByFilter').success(function(response){

                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                for (var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].persona.razonsocial, id: response[i].idproveedor})
                }
                $scope.proveedor = array_temp;
                $scope.proveedoresFiltro = array_temp[0].id;

            });
        };

        $scope.getBodegas = function () {
            $http.get(API_URL + 'DocumentoCompras/getBodegas').success(function(response){

                var longitud = response.length;
                var array_temp = [];
                for (var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].namebodega, id: response[i].idbodega})
                }

                $scope.listbodegas = array_temp;
                $scope.bodega = array_temp[0].id

            });
        };

        $scope.getSustentoTributario = function () {
            $http.get(API_URL + 'DocumentoCompras/getSustentoTributario').success(function(response){

                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                for (var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].namesustento, id: response[i].idsustentotributario})
                }

                $scope.listsustentotributario = array_temp;
                $scope.sustentotributario = array_temp[0].id;

                $scope.listtipocomprobante = [{label: '-- Seleccione --', id: ''}];
                $scope.tipocomprobante = array_temp[0].id;

            });
        };

        $scope.getTipoComprobante = function () {

            var idsustento = $scope.sustentotributario;

            var array_temp = [{label: '-- Seleccione --', id: ''}];
            $scope.listtipocomprobante = array_temp;
            $scope.tipocomprobante = array_temp[0].id;

            if (idsustento != '' && idsustento != undefined) {
                $http.get(API_URL + 'DocumentoCompras/getTipoComprobante/' + idsustento).success(function(response){

                    var longitud = response.length;

                    for (var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].namecomprobante, id: response[i].idtipocomprobante})
                    }

                    $scope.listtipocomprobante = array_temp;


                });
            }

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

        $scope.showDataProveedor = function (object) {

            if (object != undefined && object.originalObject != undefined) {

                console.log(object);

                $scope.razon = object.originalObject.razonsocial;
                $scope.direccion = object.originalObject.direccion;
                $scope.telefono = object.originalObject.proveedor[0].telefonoprincipal;
                $scope.iva = object.originalObject.proveedor[0].sri_tipoimpuestoiva.nametipoimpuestoiva;

            }

        };

        $scope.createRow = function () {

            var object_row = {
                cantidad:0,
                descuento:0,
                precioUnitario:0,
                iva: $scope.ivaPro,
                ice:0,
                total:0,
                productoObj:null,
                testObj:null
            };

            ($scope.list_item).push(object_row);

        };

        $scope.initLoad = function () {
            $scope.getProveedorByFilter();
        };

        $scope.initLoad();

        $scope.newRow = function(){
            $scope.read =  false;
            return {
                cantidad:0,
                descuento:0,
                precioUnitario:0,
                iva: $scope.ivaPro,
                ice:0,
                total:0,
                productoObj:null,
                testObj:null
            }
        };

        $scope.activeForm = function (action) {

            if (action == 0) {

                $scope.listado = false;

                $scope.t_establ = '000';
                $scope.t_pto = '000';
                $scope.t_secuencial = '000000000';



                $scope.getBodegas();
                $scope.getSustentoTributario();
                $scope.getFormaPago();


                $scope.subtotalconimpuestocompra = '0.00';
                $scope.subtotalcerocompra = '0.00';
                $scope.subtotalnoobjivacompra = '0.00';
                $scope.subtotalexentivacompra = '0.00';
                $scope.subtotalsinimpuestocompra = '0.00';
                $scope.totaldescuento = '0.00';
                $scope.valortotalcompra = '0.00';

                $scope.createRow();

            } else {

                $scope.listado = true;

            }

        };

        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'YYYY-MM-DD',
            ignoreReadonly: true
        });

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