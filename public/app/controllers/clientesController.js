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

        $scope.initLoad = function () {
            $http.get(API_URL + 'cliente/getClientes').success(function(response){
                console.log(response);
                $scope.clientes = response;
            });
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
         *  ACTION FOR CLIENT-------------------------------------------------------------------
         */

        $scope.edit = function (item) {
            $scope.t_codigocliente = item.codigocliente;
            $scope.t_fecha_ingreso = $scope.convertDatetoDB(item.fechaingreso, true);
            $scope.t_doc_id = item.documentoidentidad;
            $scope.t_email = item.correo;
            $scope.t_apellidos = item.apellidos;
            $scope.t_nombres = item.nombres;
            $scope.t_telf_principal = item.telefonoprincipaldomicilio;
            $scope.t_telf_secundario = item.telefonosecundariodomicilio;
            $scope.t_celular = item.celular;
            $scope.t_direccion = item.direcciondomicilio;
            $scope.t_telf_principal_emp = item.telefonoprincipaltrabajo;
            $scope.t_telf_secundario_emp = item.telefonosecundariotrabajo;
            $scope.t_direccion_emp = item.direcciontrabajo;

            $http.get(API_URL + 'cliente/getTipoCliente').success(function(response) {
                var longitud = response.length;
                var array_temp = [{label: '--Seleccione--', id: 0}];
                //var array_temp = [];
                for (var i = 0; i < longitud; i++) {
                    array_temp.push({label: response[i].nombretipo, id: response[i].id})
                }
                $scope.tipo_cliente = array_temp;

                $scope.t_tipocliente = parseInt(item.id);

                $scope.title_modal_cliente = 'Editar Cliente';

                $('#modalAddCliente').modal('show');
            });

            /*var id =  item.id;
            $http.delete(API_URL + 'cliente/getTipoClienteByID/'+id).success(function(response){
               console.log(response);
               $scope.t_tipocliente = response.id;
            });*/


        };

        $scope.saveCliente = function () {
            if($scope.t_tipocliente > 0) {
                var data = {
                    fechaingreso: $scope.convertDatetoDB($scope.t_fecha_ingreso),
                    codigocliente: $scope.t_doc_id,
                    apellido: $scope.t_apellidos,
                    nombre: $scope.t_nombres,
                    telefonoprincipal: $scope.t_telf_principal,
                    telefonosecundario: $scope.t_telf_secundario,
                    celular: $scope.t_celular,
                    direccion: $scope.t_direccion,
                    telfprincipalemp: $scope.t_telf_principal_emp,
                    telfsecundarioemp: $scope.t_telf_secundario_emp,
                    direccionemp: $scope.t_direccion_emp,
                    tipocliente: $scope.t_tipocliente,
                    email: $scope.t_email
                };

                var url = API_URL + "cliente";

                if ($scope.t_codigocliente == 0) {

                    $http.post(url, data).success(function (response) {
                        $scope.initLoad();
                        $('#modalAddCliente').modal('hide');
                        $scope.message = 'Se insertó correctamente el cliente...';
                        $('#modalMessage').modal('show');
                    }).error(function (res) {
                        console.log(res);
                    });

                } else {
                    url += '/' + $scope.t_codigocliente;

                    $http.put(url, data).success(function (response) {
                        $scope.initLoad();
                        $('#modalAddCliente').modal('hide');
                        $scope.message = 'Se edito correctamente el Cliente seleccionado...';
                        $('#modalMessage').modal('show');
                    }).error(function (res) {

                    });
                }
            }else {
                $scope.message_error = 'Debe seleccionar el tipo de cliente...';
                $('#modalMessageError').modal('show');

            }

        };

        $scope.deleteCliente = function(){
            $http.delete(API_URL + 'cliente/' + $scope.codigocliente_del).success(function(response) {
                $scope.initLoad();
                $('#modalDeleteCliente').modal('hide');
                $scope.codigocliente_del = 0;
                $scope.message = 'Se eliminó correctamente el Cliente seleccionado...';
                $('#modalMessage').modal('show');
            });
        };

        $scope.showModalAddCliente = function () {
            $scope.t_codigocliente = 0;
            $scope.t_fecha_ingreso = $scope.nowDate();
            $scope.t_doc_id = '';
            $scope.t_apellidos = '';
            $scope.t_nombres = '';
            $scope.t_telf_principal = '';
            $scope.t_telf_secundario = '';
            $scope.t_celular = '';
            $scope.t_direccion = '';
            $scope.t_telf_principal_emp = '';
            $scope.t_telf_secundario_emp = '';
            $scope.t_direccion_emp = '';
            $scope.t_email = '';
            $scope.t_tipocliente = 0;

            $http.get(API_URL + 'cliente/getTipoCliente').success(function(response) {
                var longitud = response.length;
                var array_temp = [{label: '--Seleccione--', id: 0}];
                //var array_temp = [];
                for (var i = 0; i < longitud; i++) {
                    array_temp.push({label: response[i].nombretipo, id: response[i].id})
                }
                $scope.tipo_cliente = array_temp;
            });

            $scope.title_modal_cliente = 'Nuevo Cliente';

            $('#modalAddCliente').modal('show');
        };

        $scope.showModalDeleteCliente = function (item) {
            $scope.codigocliente_del = item.codigocliente;
            $scope.nom_cliente = item.apellidos + ' ' + item.nombres;
            $('#modalDeleteCliente').modal('show');
        };

        $scope.showModalInfoCliente = function (item) {
            $scope.name_cliente = item.apellidos + ' ' + item.nombres;
            $scope.identify_cliente = item.documentoidentidad;
            $scope.fecha_solicitud = item.fechaingreso;
            $scope.address_cliente = item.direcciondomicilio;
            $scope.email_cliente = item.correo;
            $scope.celular_cliente = item.celular;
            $scope.telf_cliente = item.telefonoprincipaldomicilio + ' / ' + item.telefonosecundariodomicilio;
            $scope.telf_cliente_emp = item.telefonoprincipaltrabajo + ' / ' + item.telefonosecundariotrabajo;

            $scope.tipo_cliente = item.tipocliente.nombretipo;

            if (item.estaactivo == true){
                $scope.estado_solicitud = 'Activo';
            } else {
                $scope.estado_solicitud = 'Inactivo';
            }

             /*var id =  item.id;
             console.log(id);
             $http.delete(API_URL + 'cliente/getTipoClienteByID/'+id).success(function(response){
                 console.log(responde);
            // $scope.tipo_cliente = response.nombretipo;
             });*/

            $('#modalInfoCliente').modal('show');

        };

        $scope.initLoad();

        /*
         *  ACTIONS FOR SOLICITUD SUMINISTRO-------------------------------------------------------------------
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
                var array_temp = [{label: '-- Seleccione --', id: 0}];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nombretarifaaguapotable, id: response[i].idtarifaaguapotable})
                }
                $scope.tarifas = array_temp;
                $scope.s_suministro_tarifa = 0;
            });
        };

        $scope.getBarrios = function () {
            $http.get(API_URL + 'cliente/getBarrios').success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: 0}];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nombrebarrio, id: response[i].idbarrio})
                }
                $scope.barrios = array_temp;
                $scope.s_suministro_zona = 0;

                $scope.calles = [{label: '-- Seleccione --', id: 0}];
                $scope.s_suministro_transversal = 0;
            });
        };

        $scope.getCalles = function(){
            var idbarrio = $scope.s_suministro_zona;

            if (idbarrio != 0) {
                $http.get(API_URL + 'cliente/getCalles/' + idbarrio).success(function(response){
                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: 0}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].nombrecalle, id: response[i].idcalle})
                    }
                    $scope.calles = array_temp;
                    $scope.s_suministro_transversal = 0;
                });
            } else {
                $scope.calles = [{label: '-- Seleccione --', id: 0}];
                $scope.s_suministro_transversal = 0;
            }
        };

        $scope.getDividendo = function () {
            $http.get(API_URL + 'cliente/getDividendos').success(function(response){
                var dividendos = response[0].dividendo;

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
                $scope.t_suministro_marca = '';
                $scope.t_suministro_costomedidor = '';

                $('#t_suministro_marca').prop('disabled', true);
                $('#t_suministro_costomedidor').prop('disabled', true);
            } else {
                $('#t_suministro_marca').prop('disabled', false);
                $('#t_suministro_costomedidor').prop('disabled', false);

                $scope.t_suministro_marca = $scope.marcaproducto;
                $scope.t_suministro_costomedidor = $scope.precioproducto;
            }

            $scope.calculateTotalSuministro();
        };

        $scope.calculateTotalSuministro = function () {

            if ($scope.t_suministro_aguapotable != '' && $scope.t_suministro_alcantarillado != '' &&
                $scope.t_suministro_cuota != '' && $scope.s_suministro_credito != 0 && $scope.s_suministro_credito != '') {

                var total_partial = parseFloat($scope.t_suministro_aguapotable) + parseFloat($scope.t_suministro_alcantarillado);

                if ($scope.t_suministro_costomedidor != ''){
                    total_partial += parseFloat($scope.t_suministro_costomedidor);
                }

                total_partial -= parseFloat($scope.t_suministro_cuota);

                var total = total_partial / $scope.s_suministro_credito;

                $scope.total_partial = total_partial;
                $scope.credit_cant = $scope.s_suministro_credito;
                $scope.total_suministro = total;

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
            $scope.getInfoMedidor();
            $scope.getLastIDSolSuministro();
            $scope.getLastIDSuministro();
            $scope.getTarifas();
            $scope.getBarrios();
            $scope.getDividendo();

            $scope.t_suministro_medidor = false;
            $scope.nom_cliente_suministro = $scope.objectAction.apellidos + ', ' + $scope.objectAction.nombres;

            $('#btn-save-solsuministro').prop('disabled', false);
            $('#btn-process-solsuministro').prop('disabled', true);

            $('#modalActionSuministro').modal('show');
        };

        $scope.saveSolicitudSuministro = function () {
            var data = {
                idtarifa: $scope.s_suministro_tarifa,
                idcalle: $scope.s_suministro_transversal,
                garantia: $scope.t_suministro_garantia,
                codigocliente: $scope.objectAction.codigocliente,
                direccionsuministro: $scope.t_suministro_direccion,
                telefonosuministro: $scope.t_suministro_telf,
                idproducto: $scope.idproducto,
                valor: $scope.total_suministro,
            };

            $http.post(API_URL + 'cliente/storeSolicitudSuministro', data).success(function(response){
                if(response.success == true){
                    $scope.initLoad();
                    $scope.idsolicitud_to_process = response.idsolicitud;
                    $('#btn-save-solsuministro').prop('disabled', true);
                    $('#btn-process-solsuministro').prop('disabled', false);
                    $scope.message = 'Se ha ingresado la solicitud deseada correctamente...';
                    $('#modalMessage').modal('show');
                }
            });
        };

        /*
         *  ACTIONS FOR SOLICITUD SERVICIOS-------------------------------------------------------------------
         */

        $scope.getServicios = function () {
            $http.get(API_URL + 'cliente/getServicios').success(function(response){

                var html_servicios = '';

                var longitud = response.length;

                for (var i = 0; i < longitud; i++) {
                    var temp = '<div class="col-sm-4 col-xs-12">';
                    temp += '<input type="checkbox" id="idserviciojunta_' + response[i].idserviciojunta + '"> ' + response[i].nombreservicio;
                    temp += '</div>';

                    html_servicios += temp;
                }

                $('#list_servicios').html(html_servicios);

            });
        };

        $scope.getLastIDSolicServicio = function () {
            $http.get(API_URL + 'cliente/getLastID/solicitudservicio').success(function(response){
                $scope.num_solicitud_servicio = response.id;
            });
        };

        /*
         *  ACTIONS FOR SOLICITUD OTROS-------------------------------------------------------------------
         */

        $scope.getLastIDOtros = function () {
            $http.get(API_URL + 'cliente/getLastID/solicitudotro').success(function(response){
                $scope.num_solicitud_otro = response.id;
            });
        };

        $scope.saveSolicitudOtro = function () {
            var solicitud = {
                codigocliente: $scope.h_codigocliente_otro,
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
                }
            });
        };

        /*
         *  ACTIONS FOR SOLICITUD OTROS-------------------------------------------------------------------
         */

        $scope.getLastIDMantenimiento = function () {
            $http.get(API_URL + 'cliente/getLastID/solicitudmantenimiento').success(function(response){
                $scope.num_solicitud_mant = response.id;
            });
        };

        /*
         *  FUNCTION TO PROCESS---------------------------------------------------------------------
         */

        $scope.procesarSolicitud = function (id_btn) {
            var url = '';

            if (id_btn == 'btn-process-setnombre'){
                url = API_URL + 'cliente/processSolicitudSetName/' + $scope.idsolicitud_to_process;
            } else if (id_btn == 'btn-process-fraccion') {
                url = API_URL + 'cliente/processSolicitudFraccion/' + $scope.idsolicitud_to_process;
            } else {
                url = API_URL + 'cliente/processSolicitud/' + $scope.idsolicitud_to_process;
            }

            var data = {
                idsolicitud: $scope.idsolicitud_to_process
            };

            $http.put(url, data ).success(function (response) {
                $scope.idsolicitud_to_process = 0;

                $('#' + id_btn).prop('disabled', true);

                $('#modalActionSuministro').modal('hide');
                $('#modalActionOtro').modal('hide');
                $('#modalActionSetNombre').modal('hide');
                $('#modalActionFraccion').modal('hide');

                $('#modalAction').modal('hide');

                $scope.message = 'Se procesó correctamente la solicitud...';
                $('#modalMessage').modal('show');

            }).error(function (res) {

            });
        };

        /*
         *  SHOW MODAL ACTION-----------------------------------------------------------------------
         */

        $scope.showModalAction = function (item) {
            $scope.objectAction = item;
            $('#modalAction').modal('show');
        };

        $scope.actionServicio = function () {

            $scope.getLastIDSolicServicio();
            $scope.getServicios();

            $scope.t_fecha_process = $scope.nowDate();
            $scope.h_codigocliente = $scope.objectAction.codigocliente;
            $scope.documentoidentidad_cliente = $scope.objectAction.documentoidentidad;
            $scope.nom_cliente = $scope.objectAction.apellidos + ' ' + $scope.objectAction.nombres;
            $scope.direcc_cliente = $scope.objectAction.direcciondomicilio;
            $scope.telf_cliente = $scope.objectAction.telefonoprincipaldomicilio;
            $scope.celular_cliente = $scope.objectAction.celular;
            $scope.telf_trab_cliente = $scope.objectAction.telefonoprincipaltrabajo;

            $('#modalActionServicio').modal('show');
        };

        $scope.actionOtro = function () {
            $scope.getLastIDOtros();

            $scope.t_fecha_otro = $scope.nowDate();
            $scope.h_codigocliente_otro = $scope.objectAction.codigocliente;
            $scope.documentoidentidad_cliente_otro = $scope.objectAction.documentoidentidad;
            $scope.nom_cliente_otro = $scope.objectAction.apellidos + ' ' + $scope.objectAction.nombres;
            $scope.direcc_cliente_otro = $scope.objectAction.direcciondomicilio;
            $scope.telf_cliente_otro = $scope.objectAction.telefonoprincipaldomicilio;
            $scope.celular_cliente_otro = $scope.objectAction.celular;
            $scope.telf_trab_cliente_otro = $scope.objectAction.telefonoprincipaltrabajo;

            $scope.t_observacion_otro = '';
            $('#btn-process-otro').prop('disabled', true);

            $('#modalActionOtro').modal('show');
        };

        $scope.actionSetName = function () {
            /*$scope.getTerrenosByCliente();
            $scope.getIdentifyClientes();
            $scope.getLastIDSetNombre();*/

            $scope.t_fecha_setnombre = $scope.nowDate();
            $scope.h_codigocliente_setnombre = $scope.objectAction.codigocliente;
            $scope.documentoidentidad_cliente_setnombre = $scope.objectAction.documentoidentidad;
            $scope.nom_cliente_setnombre = $scope.objectAction.apellidos + ' ' + $scope.objectAction.nombres;
            $scope.direcc_cliente_setnombre = $scope.objectAction.direcciondomicilio;
            $scope.telf_cliente_setnombre = $scope.objectAction.telefonoprincipaldomicilio;
            $scope.celular_cliente_setnombre = $scope.objectAction.celular;
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

            $scope.t_observacion_setnombre = '';


            $('#btn-process-setnombre').prop('disabled', false);
            $('#modalActionSetNombre').modal('show');
        };

        $scope.actionMantenimiento = function () {
            $scope.getLastIDMantenimiento();
            /*$scope.getTerrenosFraccionByCliente();
            $scope.getIdentifyClientesFraccion();*/

            $scope.t_fecha_mant = $scope.nowDate();
            $scope.h_codigocliente_mant = $scope.objectAction.codigocliente;
            $scope.documentoidentidad_cliente_mant = $scope.objectAction.documentoidentidad;
            $scope.nom_cliente_mant = $scope.objectAction.apellidos + ' ' + $scope.objectAction.nombres;
            $scope.direcc_cliente_mant = $scope.objectAction.direcciondomicilio;
            $scope.telf_cliente_mant = $scope.objectAction.telefonoprincipaldomicilio;
            $scope.celular_cliente_mant = $scope.objectAction.celular;
            $scope.telf_trab_cliente_mant = $scope.objectAction.telefonoprincipaltrabajo;

            $scope.t_observacion_mant = '';

            $('#btn-process-mant').prop('disabled', true);
            $('#modalActionMantenimiento').modal('show');
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