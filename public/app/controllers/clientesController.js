
    app.filter('formatDate', function(){
        return function(texto){
            return convertDatetoDB(texto, true);
        }
    });

    app.controller('clientesController', function($scope, $http, API_URL) {

        $scope.clientes = [];
        $scope.codigocliente_del = 0;

        $scope.idsolicitud_to_process = 0;
        $scope.objectAction = null;

        $scope.marcaproducto = '';
        $scope.precioproducto = '';
        $scope.idproducto = '';

        $scope.list_suministros = [];
        $scope.list_clientes = [];

        $scope.services = [];

        $scope.tasainteres = 0;

        /*$scope.initLoad = function () {

            $http.get(API_URL + 'cliente/getConfiguracion').success(function(response){

                console.log(response);

                $scope.tasainteres = parseFloat(response[0].tasainteres);

            });

            $http.get(API_URL + 'cliente/getClientes').success(function(response){
                var longitud = response.length;
                for (var i = 0; i < longitud; i++) {
                    var complete_name = {
                        value: response[i].apellidos + ', ' + response[i].nombres,
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i], 'complete_name', complete_name);
                }
                $scope.clientes = response;
            });
        };*/

        $scope.nowDate = function () {
            var now = new Date();
            var dd = now.getDate();
            if (dd < 10) dd = '0' + dd;
            var mm = now.getMonth() + 1;
            if (mm < 10) mm = '0' + mm;
            var yyyy = now.getFullYear();
            return dd + "\/" + mm + "\/" + yyyy;
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

        /*
         *  ACTION FOR CLIENTE------------------------------------------------------------------------------------------
         */

        $scope.idcliente = 0;

        $scope.idpersona = 0;
        $scope.id = 0;
        $scope.select_cuenta = null;
        $scope.select_item = null;

        $scope.pageChanged = function(newPage) {
            $scope.initLoad(newPage);
        };

        $scope.initLoad = function (pageNumber) {



            $http.get(API_URL + 'cliente/getTasaInteres').success(function(response){
                $scope.tasainteres = parseFloat(response[0].optionvalue);
            });


            if ($scope.busqueda == undefined) {
                var search = null;
            } else var search = $scope.busqueda;

            var filtros = {
                search: search
            };

            $http.get(API_URL + 'cliente/getClientes?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

                $scope.clientes = response.data;
                $scope.totalItems = response.total;
            });
        };

        $scope.showDataPurchase = function (object) {
            if (object != undefined && object.originalObject != undefined) {

                console.log(object.originalObject);

                $scope.documentoidentidadempleado = object.originalObject.numdocidentific;
                $scope.apellido = object.originalObject.lastnamepersona;
                $scope.nombre = object.originalObject.namepersona;
                $scope.direccion = object.originalObject.direccion;
                $scope.celular = object.originalObject.celphone;
                $scope.correo = object.originalObject.email;
                $scope.tipoidentificacion = object.originalObject.idtipoidentificacion;
                $scope.idpersona = object.originalObject.idpersona;

                $scope.objectPerson = {
                    idperson: object.originalObject.idpersona,
                    identify: object.originalObject.numdocidentific
                };
            }
        };

        $scope.focusOut = function () {

            if ($scope.documentoidentidadempleado !== null && $scope.documentoidentidadempleado !== '' && $scope.documentoidentidadempleado !== undefined) {

                $http.get(API_URL + 'cliente/searchDuplicate/' + $scope.documentoidentidadempleado).success(function(response){

                    if (response.success === false) {

                        $http.get(API_URL + 'empleado/getPersonaByIdentify/' + $scope.documentoidentidadempleado).success(function(response){

                            var longitud = response.length;

                            if (longitud > 0) {
                                $scope.idpersona = response[0].idpersona;
                            }

                        });

                        $scope.$broadcast('angucomplete-alt:changeInput', 'documentoidentidadempleado', $scope.documentoidentidadempleado);

                    } else {

                        $scope.message_error = 'Ya existe un cliente insertado con el mismo Número de Identificación';
                        $('#modalMessageError').modal('show');

                    }

                });

            }

        };

        $scope.inputChanged = function (str) {
            $scope.documentoidentidadempleado = str;
        };

        $scope.showPlanCuenta = function () {

            $http.get(API_URL + 'empleado/getPlanCuenta').success(function(response){

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

        $scope.showModalAddCliente = function () {

            $('.datepicker').datetimepicker({
                locale: 'es',
                format: 'DD/MM/YYYY'
            });

            $http.get(API_URL + 'cliente/getTipoIdentificacion').success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nameidentificacion, id: response[i].idtipoidentificacion})
                }
                $scope.idtipoidentificacion = array_temp;
                $scope.tipoidentificacion = '';

                $http.get(API_URL + 'cliente/getTipoCliente').success(function(response){
                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].nametipocliente, id: response[i].idtipocliente})
                    }
                    $scope.tipocliente = array_temp;
                    $scope.s_tipocliente = '';

                    $http.get(API_URL + 'cliente/getImpuestoIVA').success(function(response){
                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nametipoimpuestoiva, id: response[i].idtipoimpuestoiva})
                        }
                        $scope.imp_iva = array_temp;
                        $scope.iva = '';


                        $http.get(API_URL + 'cliente/getIVADefault').success(function(response){

                            if (response[0].optionvalue !== null && response[0].optionvalue !== '') {
                                $scope.iva = parseInt(response[0].optionvalue);
                            }



                            $scope.t_codigocliente = 0;
                            $scope.t_fecha_ingreso = $scope.nowDate();

                            $scope.idcliente = 0;
                            $scope.documentoidentidadempleado = '';
                            $scope.$broadcast('angucomplete-alt:changeInput', 'documentoidentidadempleado', '');
                            $scope.$broadcast('angucomplete-alt:clearInput', 'documentoidentidadempleado');
                            $scope.idpersona = 0;
                            $scope.apellido = '';
                            $scope.nombre = '';
                            $scope.telefonoprincipal = '';
                            $scope.telefonosecundario = '';
                            $scope.celular = '';
                            $scope.direccion = '';
                            $scope.telefonoprincipaltrabajo = '';
                            $scope.telefonosecundariotrabajo = '';
                            $scope.direcciontrabajo = '';
                            $scope.correo = '';
                            $scope.cuenta_employee = '';

                            $scope.select_cuenta = null;

                            $scope.title_modal_cliente = 'Nuevo Cliente';

                            $('#modalAddCliente').modal('show');

                        });




                    });

                });

            });

        };

        $scope.saveCliente = function () {

            var url = API_URL + 'cliente';

            var fechaingreso = $('#t_fecha_ingreso').val();

            var data = {

                // datos de persona

                documentoidentidadempleado: $scope.documentoidentidadempleado,
                correo: $scope.correo,
                celular: $scope.celular,
                tipoidentificacion: $scope.tipoidentificacion,
                apellidos: $scope.apellido,
                nombres: $scope.nombre,
                direccion: $scope.direccion,

                // datos de cliente

                fechaingreso: convertDatetoDB(fechaingreso),
                idpersona:  $scope.idpersona,
                cuentacontable: $scope.select_cuenta.idplancuenta,
                impuesto_iva: $scope.iva,

                telefonoprincipaldomicilio: $scope.telefonoprincipal,
                telefonosecundariodomicilio: $scope.telefonosecundario,
                telefonoprincipaltrabajo: $scope.telefonoprincipaltrabajo,
                telefonosecundariotrabajo: $scope.telefonosecundariotrabajo,
                direcciontrabajo: $scope.direcciontrabajo,

                tipocliente: $scope.s_tipocliente

            };

            console.log(data);

            $('#btn-saveCliente').prop('disabled', true);

            if ($scope.idcliente == 0) {
                $http.post(url, data ).success(function (response) {

                    console.log(response);

                    $('#btn-saveCliente').prop('disabled', false);
                    $('#modalAction').modal('hide');

                    if (response.success === true) {
                        $scope.initLoad(1);
                        $scope.message = 'Se guardó correctamente la información del Cliente...';
                        $('#modalAddCliente').modal('hide');
                        $('#modalMessage').modal('show');
                        $scope.hideModalMessage();
                    }
                    else {

                        $('#modalAddCliente').modal('hide');

                        if (response.type_error_exists == true) {
                            $scope.message_error = 'Ya existe un cliente insertado con el mismo Número de Identificación';
                        } else {
                            $scope.message_error = 'Ha ocurrido un error..';
                        }

                        $('#modalMessageError').modal('show');
                    }
                });
            } else {

                $http.put(url + '/' + $scope.idcliente, data ).success(function (response) {

                    $('#btn-saveCliente').prop('disabled', false);

                    if (response.success == true) {
                        $scope.idpersona = 0;
                        $scope.idcliente = 0;
                        $scope.initLoad(1);
                        $scope.message = 'Se editó correctamente la información del Cliente...';
                        $('#modalAddCliente').modal('hide');
                        $('#modalMessage').modal('show');
                        $scope.hideModalMessage();
                    }
                    else {
                        $('#modalAddCliente').modal('hide');
                        $scope.message_error = 'Ha ocurrido un error..';
                        $('#modalMessageError').modal('show');
                    }
                }).error(function (res) {

                });
            }
        };

        $scope.showModalInfoCliente = function (item) {
            $scope.name_cliente = item.razonsocial;
            $scope.identify_cliente = item.numdocidentific;
            $scope.fecha_solicitud = item.fechaingreso;
            $scope.address_cliente = item.direccion;
            $scope.email_cliente = item.email;
            $scope.celular_cliente = item.celphone;
            $scope.telf_cliente = item.telefonoprincipaldomicilio + ' / ' + item.telefonosecundariodomicilio;
            $scope.telf_cliente_emp = item.telefonoprincipaltrabajo + ' / ' + item.telefonosecundariotrabajo;

            if (item.estado == true){
                $scope.estado_solicitud = 'Activo';
            } else {
                $scope.estado_solicitud = 'Inactivo';
            }

            $('#modalInfoCliente').modal('show');

        };

        $scope.showModalEditCliente = function (item) {

            $('.datepicker').datetimepicker({
                locale: 'es',
                format: 'DD/MM/YYYY'
            });

            $http.get(API_URL + 'cliente/getTipoIdentificacion').success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nameidentificacion, id: response[i].idtipoidentificacion})
                }
                $scope.idtipoidentificacion = array_temp;
                $scope.tipoidentificacion = item.idtipoidentificacion;


                $http.get(API_URL + 'cliente/getTipoCliente').success(function(response){
                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].nametipocliente, id: response[i].idtipocliente})
                    }
                    $scope.tipocliente = array_temp;
                    $scope.s_tipocliente = item.idtipocliente;

                    $http.get(API_URL + 'cliente/getImpuestoIVA').success(function(response){
                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nametipoimpuestoiva, id: response[i].idtipoimpuestoiva})
                        }
                        $scope.imp_iva = array_temp;
                        $scope.iva = item.idtipoimpuestoiva;

                        console.log(item);

                        $scope.idpersona = item.idpersona;

                        $scope.idcliente = item.idcliente;

                        //$scope.t_codigocliente = 0;

                        $scope.t_fecha_ingreso = convertDatetoDB(item.fechaingreso, true);
                        $scope.documentoidentidadempleado = item.numdocidentific;
                        $scope.$broadcast('angucomplete-alt:changeInput', 'documentoidentidadempleado', item.numdocidentific);

                        $scope.apellido = item.lastnamepersona;
                        $scope.nombre = item.namepersona;
                        $scope.telefonoprincipal = item.telefonoprincipaldomicilio;
                        $scope.telefonosecundario = item.telefonoprincipaltrabajo;
                        $scope.celular = item.celphone;
                        $scope.direccion = item.direccion;
                        $scope.telefonoprincipaltrabajo = item.telefonoprincipaltrabajo;
                        $scope.telefonosecundariotrabajo = item.telefonosecundariotrabajo;
                        $scope.direcciontrabajo = item.direcciontrabajo;
                        $scope.correo = item.email;
                        $scope.cuenta_employee = item.concepto;

                        var objectPlan = {
                            idplancuenta: item.idplancuenta,
                            concepto: item.concepto
                        };

                        $scope.select_cuenta = objectPlan;

                        $scope.title_modal_cliente = 'Editar Cliente';

                        $('#modalAddCliente').modal('show');

                    });

                });




            });
        };

        $scope.showModalDeleteCliente = function (item) {
            $scope.idcliente = item.idcliente;
            $http.get(API_URL + 'cliente/getIsFreeCliente/' + item.idcliente).success(function(response){
                if (response == 0) {
                    $scope.nom_cliente = item.razonsocial;
                    $('#modalDeleteCliente').modal('show');
                } else {
                    $scope.message_info = 'No se puede eliminar el cliente seleccionado, ya presenta solicitudes a su nombre...';
                    $('#modalMessageInfo').modal('show');
                }
            });

        };

        $scope.deleteCliente = function(){
            $http.delete(API_URL + 'cliente/' + $scope.idcliente).success(function(response) {
                $scope.initLoad(1);
                $('#modalDeleteCliente').modal('hide');
                $scope.idcliente = 0;
                $scope.message = 'Se eliminó correctamente el Cliente seleccionado...';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            });
        };

        $scope.initLoad(1);


        /*
         *  ACTIONS FOR SOLICITUD SUMINISTRO----------------------------------------------------------------------------
         */

        $scope.getLastIDSolSuministro = function () {
            $http.get(API_URL + 'cliente/getLastID/solsuministro').success(function(response){
                $scope.num_solicitud_suministro = response.id;
            });
        };

        $scope.getLastIDSuministro = function () {
            $http.get(API_URL + 'cliente/getLastID/suministro').success(function(response){
                $scope.t_suministro_nro = response.id;
            });
        };

        $scope.getTarifas = function () {
            $http.get(API_URL + 'cliente/getTarifas').success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nametarifaaguapotable, id: response[i].idtarifaaguapotable})
                }
                $scope.tarifas = array_temp;
                $scope.s_suministro_tarifa = '';
            });
        };

        $scope.getBarrios = function () {
            $http.get(API_URL + 'cliente/getBarrios').success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].namebarrio, id: response[i].idbarrio})
                }
                $scope.barrios = array_temp;
                $scope.s_suministro_zona = '';

                $scope.calles = [{label: '-- Seleccione --', id: ''}];
                $scope.s_suministro_transversal = '';
            });
        };

        $scope.getCalles = function() {
            var idbarrio = $scope.s_suministro_zona;

            if (idbarrio != 0) {
                $http.get(API_URL + 'cliente/getCalles/' + idbarrio).success(function(response){
                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].namecalle, id: response[i].idcalle})
                    }
                    $scope.calles = array_temp;
                    $scope.s_suministro_transversal = '';
                });
            } else {
                $scope.calles = [{label: '-- Seleccione --', id: 0}];
                $scope.s_suministro_transversal = 0;
            }
        };

        $scope.getDividendo = function () {
            $http.get(API_URL + 'cliente/getDividendos').success(function(response){

                var dividendos = parseInt(response[0].optionvalue);

                var array_temp = [{label: '-- Seleccione --', id: 0}];

                for (var i = 1; i <= dividendos; i++) {
                    array_temp.push({label: i, id: i})
                }

                $scope.creditos = array_temp;
                $scope.s_suministro_credito = 0;
            });
        };

        $scope.getInfoMedidor = function () {
            $http.get(API_URL + 'cliente/getInfoMedidor').success(function(response){
                $scope.marcaproducto = response[0].marca;
                $scope.precioproducto = response[0].precioproducto;
                $scope.idproducto = response[0].idproducto;

                $scope.t_suministro_marca = $scope.marcaproducto;
                $scope.t_suministro_costomedidor = $scope.precioproducto;

            });
        };

        $scope.deshabilitarMedidor = function () {
            if ($scope.t_suministro_medidor == true) {

                $scope.iditem = 0;
                $scope.t_suministro_marca = '';
                $scope.t_suministro_costomedidor = '';

                $('#t_suministro_marca').prop('disabled', true);
                $('#t_suministro_costomedidor').prop('disabled', true);

                $scope.getListItem();

            } else {

                $('#t_suministro_marca').prop('disabled', true);
                $('#t_suministro_costomedidor').prop('disabled', true);

                $scope.t_suministro_marca = $scope.marcaproducto;
                $scope.t_suministro_costomedidor = $scope.precioproducto;
            }

            $scope.calculateTotalSuministro();
        };

        $scope.calculateTotalSuministro = function () {

            if ($scope.t_suministro_aguapotable != '' && $scope.t_suministro_alcantarillado != '' &&
                $scope.t_suministro_cuota != '' && $scope.s_suministro_credito != 0 && $scope.s_suministro_credito != '') {

                /*var n = $scope.s_suministro_credito / 12;

                var C = parseFloat($scope.t_suministro_aguapotable) + parseFloat($scope.t_suministro_alcantarillado);
                if ($scope.t_suministro_costomedidor != ''){
                    C += parseFloat($scope.t_suministro_costomedidor);
                }

                C -= parseFloat($scope.t_suministro_cuota);

                var I = ((n * C) * $scope.tasainteres) / 100;

                var M = C + I;

                var cuotas = M / $scope.s_suministro_credito;*/


                var n = $scope.s_suministro_credito / 12;


                //console.log($scope.t_suministro_costomedidor);

                var C = parseFloat($scope.t_suministro_aguapotable) + parseFloat($scope.t_suministro_alcantarillado);
                if ($scope.t_suministro_costomedidor !== '' && $scope.t_suministro_costomedidor !== undefined){
                    C += parseFloat($scope.t_suministro_costomedidor);
                }


                var I = n * ($scope.tasainteres / 100) * C;

                var M = C + I;

                M = M - parseFloat($scope.t_suministro_cuota);

                //var cuotas = (M - parseFloat($scope.t_suministro_cuota)) / $scope.s_suministro_credito;

                var cuotas = M / $scope.s_suministro_credito;

                $scope.total_partial = M.toFixed(2);
                $scope.credit_cant = $scope.s_suministro_credito;
                $scope.total_suministro = cuotas.toFixed(2);


                /*var total_partial = parseFloat($scope.t_suministro_aguapotable) + parseFloat($scope.t_suministro_alcantarillado);

                if ($scope.t_suministro_costomedidor != ''){
                    total_partial += parseFloat($scope.t_suministro_costomedidor);
                }

                total_partial -= parseFloat($scope.t_suministro_cuota);

                var total = total_partial / $scope.s_suministro_credito;

                $scope.total_partial = total_partial.toFixed(2);
                $scope.credit_cant = $scope.s_suministro_credito;
                $scope.total_suministro = total.toFixed(2);*/

                $('#info_partial').show();
                $('#info_total').show();
            } else {
                $scope.total_partial = 0;
                $scope.credit_cant = 0;
                $scope.total_suministro = 0;
                $('#info_partial').hide();
                $('#info_total').hide();
            }
        };

        $scope.actionSuministro = function () {
            //$scope.getInfoMedidor();
            $scope.getLastIDSolSuministro();
            $scope.getLastIDSuministro();
            $scope.getTarifas();
            $scope.getBarrios();
            $scope.getDividendo();

            $scope.t_suministro_medidor = false;
            $scope.nom_cliente_suministro = $scope.objectAction.razonsocial;

            $scope.t_suministro_direccion = '';
            $scope.t_suministro_telf = '';
            $scope.t_suministro_aguapotable = '';
            $scope.t_suministro_alcantarillado = '';
            $scope.t_suministro_garantia = '';
            $scope.t_suministro_cuota = '';

            $('#info_partial').hide();
            $('#info_total').hide();

            $('#btn-process-solsuministro').prop('disabled', true);

            $('#modalActionSuministro').modal('show');
        };

        $scope.saveSolicitudSuministro = function () {
            $('#btn-save-solsuministro').prop('disabled', true);

            var data = {
                idtarifa: $scope.s_suministro_tarifa,
                idcalle: $scope.s_suministro_transversal,
                garantia: $scope.t_suministro_garantia,
                codigocliente: $scope.objectAction.idcliente,
                direccionsuministro: $scope.t_suministro_direccion,
                telefonosuministro: $scope.t_suministro_telf,
                idproducto: $scope.idproducto,
                valor: $scope.total_suministro,
                dividendos: $scope.s_suministro_credito,
                valor_partial: $scope.total_partial,
                formapago: $scope.s_suministro_formapago
            };

            console.log(data);

            $http.post(API_URL + 'cliente/storeSolicitudSuministro', data).success(function(response){
                if(response.success == true){
                    $scope.initLoad();
                    $scope.idsolicitud_to_process = response.idsolicitud;

                    $('#btn-process-solsuministro').prop('disabled', false);
                    $scope.message = 'Se ha ingresado la solicitud deseada correctamente...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }
            });
        };

        $scope.procesarSolicitudSuministro = function () {

            $('#btn-process-solsuministro').prop('disabled', true);

            /*if ($scope.t_suministro_medidor == false || $scope.t_suministro_medidor == 0 || $scope.t_suministro_medidor == 'off') {
                var tiene = 'SI'
            } else {
                var tiene = 'NO'
            }*/

            var tarifa = $('#s_suministro_tarifa option:selected').text();
            var zona = $('#s_suministro_zona option:selected').text();
            var transversal = $('#s_suministro_transversal option:selected').text();

            /*if ($scope.t_suministro_marca == undefined){
                $scope.t_suministro_marca = '';
            }*/

            /*if ($scope.t_suministro_costomedidor == undefined){
                $scope.t_suministro_costomedidor = '0.00';
            }*/

            /*if ($scope.iditem == undefined) {
                $scope.iditem = null;
            }*/

            var data_to_pdf = {
                tarifa: tarifa,
                zona: zona,
                transversal: transversal,
                no_suministro: $scope.t_suministro_nro,
                nomcliente: $scope.nom_cliente_suministro,
                ci: $scope.objectAction.numdocidentific,
                telefono: $scope.t_suministro_telf,
                direccion: $scope.t_suministro_direccion,
                agua_potable: $scope.t_suministro_aguapotable,
                alcantarillado: $scope.t_suministro_alcantarillado,
                garantia: $scope.t_suministro_garantia,
                cuota_inicial: $scope.t_suministro_cuota,
                valor: $scope.total_suministro,
                dividendos: $scope.s_suministro_credito,
                valor_partial: $scope.total_partial,
                total_suministro: $scope.total_suministro,

                /*tiene_medidor: tiene,
                marca_medidor: $scope.t_suministro_marca,
                costo_medidor: $scope.t_suministro_costomedidor,*/
            };

            var data = {
                idtarifa: $scope.s_suministro_tarifa,
                idcalle: $scope.s_suministro_transversal,

                agua_potable: $scope.t_suministro_aguapotable,
                alcantarillado: $scope.t_suministro_alcantarillado,
                garantia: $scope.t_suministro_garantia,
                cuota_inicial: $scope.t_suministro_cuota,
                valor: $scope.total_suministro,
                dividendos: $scope.s_suministro_credito,
                valor_partial: $scope.total_partial,

                codigocliente: $scope.objectAction.idcliente,
                direccionsuministro: $scope.t_suministro_direccion,
                telefonosuministro: $scope.t_suministro_telf,

                //idproducto: $scope.iditem,

                idsolicitud: $scope.num_solicitud_suministro,

                formapago: $scope.s_suministro_formapago,

                data_to_pdf: JSON.stringify(data_to_pdf)
            };


            console.log(data);

            var url = API_URL + 'cliente/processSolicitudSuministro/' + $scope.idsolicitud_to_process;

            $http.put(url, data ).success(function (response) {
                $scope.idsolicitud_to_process = 0;

                $('#modalActionSuministro').modal('hide');
                $('#modalAction').modal('hide');

                $scope.message = 'Se procesó correctamente la solicitud...';
                $('#modalMessage').modal('show');

                $scope.hideModalMessage();

            }).error(function (res) {

            });
        };

        $scope.getListItem = function () {

            $scope.select_item = null;

            $http.get(API_URL + 'cliente/getItems').success(function(response){

                $scope.items = response;

                $('#modalRegistroItem').modal('show');

            });
        };

        $scope.selectItems = function (item) {
            $scope.select_item = item;
        };

        $scope.assignItems = function () {

            if ($scope.select_item == null) {

                $scope.message_info = 'Seleccione un producto a asignar...';
                $('#modalMessageInfo').modal('show');

            } else {

                $scope.iditem = $scope.select_item.idcatalogitem;
                $scope.t_suministro_marca = $scope.select_item.codigoproducto;
                $scope.t_suministro_costomedidor = $scope.select_item.precioventa;

                $('#modalRegistroItem').modal('hide');

            }

        };

        /*
         *  ACTIONS FOR SOLICITUD SERVICIOS-----------------------------------------------------------------------------
         */

        $scope.getExistsSolicitudServicio = function () {
            var codigocliente = $scope.objectAction.idcliente;
            $http.get(API_URL + 'cliente/getExistsSolicitudServicio/' + codigocliente).success(function(response){
                if (response.success == false){
                    $scope.actionServicioShow();
                } else {
                    var msg = 'El cliente: "' + $scope.objectAction.razonsocial;
                    msg += '"; ya presenta una Solicitud de Servicios';
                    $scope.message_info = msg;
                    $('#modalMessageInfo').modal('show');
                }
            });
        };

        $scope.getServicios = function () {
            $http.get(API_URL + 'cliente/getServicios').success(function(response){
                var longitud = response.length;
                var array_temp = [];
                for (var i = 0; i < longitud; i++) {
                    var object_service = {
                        idserviciojunta: response[i].idcatalogitem,
                        nombreservicio: response[i].nombreproducto,
                        valor: 0
                    };
                    array_temp.push(object_service);
                }
                $scope.services = array_temp;
            });
        };

        $scope.getLastIDSolicServicio = function () {
            $http.get(API_URL + 'cliente/getLastID/solicitudservicio').success(function(response){
                $scope.num_solicitud_servicio = response.id;
            });
        };

        $scope.actionServicio = function () {
            $scope.getExistsSolicitudServicio();
        };

        $scope.actionServicioShow = function () {
            /*$scope.getLastIDSolicServicio();*/
            $scope.getServicios();

            //console.log($scope.objectAction);

            $scope.t_fecha_process = $scope.nowDate();
            $scope.h_codigocliente = $scope.objectAction.idcliente;
            $scope.documentoidentidad_cliente = $scope.objectAction.numdocidentific;
            $scope.nom_cliente = $scope.objectAction.lastnamepersona + ', ' + $scope.objectAction.namepersona;
            $scope.direcc_cliente = $scope.objectAction.direccion;
            $scope.telf_cliente = $scope.objectAction.telefonoprincipaldomicilio;
            $scope.celular_cliente = $scope.objectAction.celphone;
            $scope.telf_trab_cliente = $scope.objectAction.telefonoprincipaltrabajo;
            /*$scope.tipo_tipo_cliente = $scope.objectAction.tipocliente.nombretipo;*/

            $('#btn-save-servicio').prop('disabled', false);
            $('#btn-process-servicio').prop('disabled', true);

            $('#modalActionServicio').modal('show');
        };

        $scope.saveSolicitudServicio = function () {
            $('#btn-save-servicio').prop('disabled', true);

            var solicitud = {
                codigocliente: $scope.objectAction.idcliente,
                servicios: $scope.services
            };

            $http.post(API_URL + 'cliente/storeSolicitudServicios', solicitud).success(function(response){
                if(response.success == true){
                    $scope.initLoad(1);
                    $scope.idsolicitud_to_process = response.idsolicitud;

                    $('#btn-process-servicio').prop('disabled', false);
                    $scope.message = 'Se ha ingresado la solicitud deseada correctamente...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }
            });
        };

        /*
         *  ACTIONS FOR SOLICITUD OTROS---------------------------------------------------------------------------------
         */

        $scope.getLastIDOtros = function () {
            $http.get(API_URL + 'cliente/getLastID/solicitudotro').success(function(response){
                $scope.num_solicitud_otro = response.id;
            });
        };

        $scope.saveSolicitudOtro = function () {
            $('#btn-save-otro').prop('disabled', true);
            var solicitud = {
                codigocliente: $scope.objectAction.idcliente,
                observacion: $scope.t_observacion_otro
            };
            $http.post(API_URL + 'cliente/storeSolicitudOtro', solicitud).success(function(response){

                if(response.success == true){
                    $scope.initLoad();
                    $scope.idsolicitud_to_process = response.idsolicitud;
                    $('#btn-save-otro').prop('disabled', true);
                    $('#btn-process-otro').prop('disabled', false);
                    $scope.message = 'Se ha ingresado la solicitud deseada correctamente...';
                    $('#modalMessage').modal('show');

                    $scope.hideModalMessage();
                }
            });
        };

        $scope.actionOtro = function () {
            $scope.getLastIDOtros();

            $scope.t_fecha_otro = $scope.nowDate();
            $scope.h_codigocliente_otro = $scope.objectAction.idcliente;
            $scope.documentoidentidad_cliente_otro = $scope.objectAction.numdocidentific;
            $scope.nom_cliente_otro = $scope.objectAction.razonsocial;
            $scope.direcc_cliente_otro = $scope.objectAction.direccion;
            $scope.telf_cliente_otro = $scope.objectAction.telefonoprincipaldomicilio;
            $scope.celular_cliente_otro = $scope.objectAction.celphone;
            $scope.telf_trab_cliente_otro = $scope.objectAction.telefonoprincipaltrabajo;

            $scope.t_observacion_otro = '';
            $('#btn-process-otro').prop('disabled', true);

            $('#modalActionOtro').modal('show');
        };

        /*
         *  ACTIONS FOR SOLICITUD MANTENIMIENTO-------------------------------------------------------------------------
         */

        $scope.getLastIDMantenimiento = function () {
            $http.get(API_URL + 'cliente/getLastID/solicitudmantenimiento').success(function(response){
                $scope.num_solicitud_mant = response.id;
            });
        };

        $scope.getSuministros = function () {
            var codigocliente = $scope.objectAction.idcliente;
            $http.get(API_URL + 'cliente/getSuministros/' + codigocliente).success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                $scope.list_suministros = [];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].direccionsumnistro, id: response[i].idsuministro});
                    $scope.list_suministros.push(response[i]);
                }
                $scope.suministro_mant = array_temp;
                $scope.s_suministro_mant = '';
            });
        };

        $scope.showInfoSuministro = function () {

            var numerosuministro = $scope.s_suministro_mant;
            if (numerosuministro != 0 && numerosuministro != undefined) {
                var longitud = $scope.list_suministros.length;

                for (var i = 0; i < longitud; i++) {
                    if (numerosuministro == $scope.list_suministros[i].idsuministro) {
                        $scope.zona_mant = $scope.list_suministros[i].calle.barrio.namebarrio;
                        $scope.transversal_mant = $scope.list_suministros[i].calle.namecalle;
                        $scope.tarifa_mant = $scope.list_suministros[i].tarifaaguapotable.nametarifaaguapotable;

                        break;
                    }
                }
            } else {
                $scope.zona_mant = '';
                $scope.transversal_mant = '';
                $scope.tarifa_mant = '';
            }
        };

        $scope.saveSolicitudMantenimiento = function () {
            $('#btn-save-mant').prop('disabled', true);
            var solicitud = {
                codigocliente: $scope.objectAction.idcliente,
                numerosuministro: $scope.s_suministro_mant,
                observacion: $scope.t_observacion_mant
            };
            $http.post(API_URL + 'cliente/storeSolicitudMantenimiento', solicitud).success(function(response){
                if(response.success == true){
                    $scope.initLoad();
                    $scope.idsolicitud_to_process = response.idsolicitud;

                    $('#btn-process-mant').prop('disabled', false);
                    $scope.message = 'Se ha ingresado la solicitud deseada correctamente...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }
            });
        };

        $scope.actionMantenimiento = function () {
            $scope.getLastIDMantenimiento();
            $scope.getSuministros();

            $scope.t_fecha_mant = $scope.nowDate();
            $scope.h_codigocliente_mant = $scope.objectAction.idcliente;
            $scope.documentoidentidad_cliente_mant = $scope.objectAction.numdocidentific;
            $scope.nom_cliente_mant = $scope.objectAction.razonsocial;
            $scope.direcc_cliente_mant = $scope.objectAction.direccion;
            $scope.telf_cliente_mant = $scope.objectAction.telefonoprincipaldomicilio;
            $scope.celular_cliente_mant = $scope.objectAction.celphone;
            $scope.telf_trab_cliente_mant = $scope.objectAction.telefonoprincipaltrabajo;

            $scope.zona_mant = '';
            $scope.transversal_mant = '';
            $scope.tarifa_mant = '';

            var array_temp = [{label: '-- Seleccione --', id: 0}];
            $scope.suministro_mant = array_temp;
            $scope.s_suministro_mant = 0;

            $scope.t_observacion_mant = '';

            $('#btn-process-mant').prop('disabled', true);
            $('#modalActionMantenimiento').modal('show');
        };

        /*
         *  ACTIONS FOR SOLICITUD CAMBIO NOMBRE-------------------------------------------------------------------------
         */

        $scope.getLastSetName = function () {
            $http.get(API_URL + 'cliente/getLastID/solicitudcambionombre').success(function(response){
                $scope.num_solicitud_setnombre = response.id;
            });
        };

        $scope.getIdentifyClientes = function () {
            var idcliente = $scope.objectAction.codigocliente;
            $http.get(API_URL + 'cliente/getIdentifyClientes/' + idcliente).success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: 0}];
                $scope.list_clientes = [];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].documentoidentidad, id: response[i].codigocliente});
                    $scope.list_clientes.push(response[i]);
                }
                return array_temp;

                /*$scope.clientes_setN = array_temp;
                $scope.s_ident_new_client_setnombre = 0;*/
            });
        };

        $scope.showInfoClienteForSetName = function (object) {

            console.log(object);

            if (object.originalObject != undefined) {
                var codigocliente = object.originalObject.cliente[0].idcliente;

                if (codigocliente != 0 && codigocliente != undefined) {

                    $http.get(API_URL + 'cliente/getInfoCliente/' + codigocliente).success(function(response){
                        $scope.nom_new_cliente_setnombre = object.originalObject.razonsocial;
                        $scope.direcc_new_cliente_setnombre = object.originalObject.direccion;
                        $scope.telf_new_cliente_setnombre = response[0].telefonoprincipaldomicilio;
                        $scope.celular_new_cliente_setnombre = object.originalObject.celphone;
                        $scope.telf_trab_new_cliente_setnombre = response[0].telefonoprincipaltrabajo;
                        $scope.h_codigocliente_new = codigocliente;
                    });

                } else {
                    $scope.nom_new_cliente_setnombre = '';
                    $scope.direcc_new_cliente_setnombre = '';
                    $scope.telf_new_cliente_setnombre = '';
                    $scope.celular_new_cliente_setnombre = '';
                    $scope.telf_trab_new_cliente_setnombre = '';
                }
            }

        };

        $scope.getSuministrosForSetName = function () {
            var codigocliente = $scope.objectAction.idcliente;
            $http.get(API_URL + 'cliente/getSuministros/' + codigocliente).success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                $scope.list_suministros = [];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].direccionsumnistro, id: response[i].idsuministro});
                    $scope.list_suministros.push(response[i]);
                }
                $scope.suministro_setN = array_temp;
                $scope.s_suministro_setnombre = '';
            });
        };

        $scope.showInfoSuministroForSetName = function () {

            var numerosuministro = $scope.s_suministro_setnombre;

            if (numerosuministro != 0 && numerosuministro != undefined) {
                var longitud = $scope.list_suministros.length;

                for (var i = 0; i < longitud; i++) {
                    if (numerosuministro == $scope.list_suministros[i].idsuministro) {
                        $scope.zona_setnombre = $scope.list_suministros[i].calle.barrio.namebarrio;
                        $scope.transversal_setnombre = $scope.list_suministros[i].calle.namecalle;
                        $scope.tarifa_setnombre = $scope.list_suministros[i].tarifaaguapotable.nametarifaaguapotable;

                        break;
                    }
                }
            } else {
                $scope.zona_setnombre = '';
                $scope.transversal_setnombre = '';
                $scope.tarifa_setnombre = '';
            }
        };

        $scope.saveSolicitudCambioNombre = function () {
            $('#btn-save-setnombre').prop('disabled', true);

            var solicitud = {
                codigocliente: $scope.objectAction.idcliente,
                codigoclientenuevo: $scope.h_codigocliente_new,
                numerosuministro: $scope.s_suministro_setnombre
            };

            $http.post(API_URL + 'cliente/storeSolicitudCambioNombre', solicitud).success(function(response){
                if(response.success == true){
                    $scope.initLoad();
                    $scope.idsolicitud_to_process = response.idsolicitud;

                    $('#btn-process-setnombre').prop('disabled', false);
                    $scope.message = 'Se ha ingresado la solicitud deseada correctamente...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }
            });
        };

        $scope.procesarSolicitudSetName = function () {
            $('#btn-process-setnombre').prop('disabled', true);
            var data = {
                codigoclientenuevo: $scope.h_codigocliente_new
            };
            var numerosuministro = $scope.s_suministro_setnombre;
            var url = API_URL + 'cliente/updateSetNameSuministro/' + numerosuministro;

            $http.put(url, data).success(function (response) {
                if (response.success == true){
                    $scope.procesarSolicitud('btn-process-setnombre');
                }
            }).error(function (res) {

            });
        };

        $scope.actionSetName = function () {
            $scope.getLastSetName();
            $scope.getSuministrosForSetName();
            //$scope.getIdentifyClientes();

            $scope.t_fecha_setnombre = $scope.nowDate();
            $scope.h_codigocliente_setnombre = $scope.objectAction.idcliente;
            $scope.documentoidentidad_cliente_setnombre = $scope.objectAction.numdocidentific;
            $scope.nom_cliente_setnombre = $scope.objectAction.razonsocial;
            $scope.direcc_cliente_setnombre = $scope.objectAction.direccion;
            $scope.telf_cliente_setnombre = $scope.objectAction.telefonoprincipaldomicilio;
            $scope.celular_cliente_setnombre = $scope.objectAction.celphone;
            $scope.telf_trab_cliente_setnombre = $scope.objectAction.telefonoprincipaltrabajo;

            $scope.junta_setnombre = '';
            $scope.toma_setnombre = '';
            $scope.canal_setnombre = '';
            $scope.derivacion_setnombre = '';
            $scope.cultivo_setnombre = '';
            $scope.area_setnombre = '';
            $scope.caudal_setnombre = '';
            $scope.nom_new_cliente_setnombre = '';
            $scope.direcc_new_cliente_setnombre = '';
            $scope.telf_new_cliente_setnombre = '';
            $scope.celular_new_cliente_setnombre = '';
            $scope.telf_trab_new_cliente_setnombre = '';

            $scope.zona_setnombre = '';
            $scope.transversal_setnombre = '';
            $scope.tarifa_setnombre = '';

            $scope.t_observacion_setnombre = '';


            $('#btn-process-setnombre').prop('disabled', true);
            $('#modalActionSetNombre').modal('show');
        };

        /*
         *  FUNCTION TO PROCESS-----------------------------------------------------------------------------------------
         */

        $scope.procesarSolicitud = function (id_btn) {

            $('#' + id_btn).prop('disabled', true);

            var url = API_URL + 'cliente/processSolicitud/' + $scope.idsolicitud_to_process;

            var data = {
                idsolicitud: $scope.idsolicitud_to_process
            };

            $http.put(url, data ).success(function (response) {
                $scope.idsolicitud_to_process = 0;

                $('#modalActionSuministro').modal('hide');
                $('#modalActionServicio').modal('hide');
                $('#modalActionOtro').modal('hide');
                $('#modalActionSetNombre').modal('hide');
                $('#modalActionMantenimiento').modal('hide');
                $('#modalAction').modal('hide');

                $scope.message = 'Se procesó correctamente la solicitud...';
                $('#modalMessage').modal('show');

                $scope.hideModalMessage();

            }).error(function (res) {

            });
        };

        /*
         *  SHOW MODAL ACTION-------------------------------------------------------------------------------------------
         */

        $scope.showModalAction = function (item) {
            $scope.objectAction = item;

            $http.get(API_URL + 'cliente/getSuministroByClient/' + item.idcliente).success(function(response){

                if (response == 0) {
                    $('#btnSetName').prop('disabled', true);
                    $('#btnMantenimiento').prop('disabled', true);
                    $('#btnOtras').prop('disabled', true);
                } else {
                    $('#btnSetName').prop('disabled', false);
                    $('#btnMantenimiento').prop('disabled', false);
                    $('#btnOtras').prop('disabled', false);
                }

                $('#modalAction').modal('show');

            });


        };

        $scope.sort = function(keyname){
            $scope.sortKey = keyname;
            $scope.reverse = !$scope.reverse;
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

        $scope.onlyNumber = function ($event) {

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
    });

    function convertDatetoDB(now, revert){
        if (revert == undefined){
            var t = now.split('/');
            return t[2] + '-' + t[1] + '-' + t[0];
        } else {
            var t = now.split('-');
            return t[2] + '/' + t[1] + '/' + t[0];
        }
    }