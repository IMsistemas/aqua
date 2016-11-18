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

        $scope.initLoad = function () {
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
         *  ACTION FOR CLIENTE------------------------------------------------------------------------------------------
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
                        $scope.message = 'Se editó correctamente el Cliente seleccionado...';
                        $('#modalMessage').modal('show');
                    }).error(function (res) {

                    });
                }

                $scope.hideModalMessage();

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

                $scope.hideModalMessage();
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
            $http.get(API_URL + 'cliente/getIsFreeCliente/' + $scope.codigocliente_del).success(function(response){
                if (response == 0) {
                    $scope.nom_cliente = item.apellidos + ' ' + item.nombres;
                    $('#modalDeleteCliente').modal('show');
                } else {
                    $scope.message_info = 'No se puede eliminar el cliente seleccionado, ya presenta solicitudes a su nombre...';
                    $('#modalMessageInfo').modal('show');
                }
            });
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

        $scope.getCalles = function() {
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

                $scope.total_partial = total_partial.toFixed(2);
                $scope.credit_cant = $scope.s_suministro_credito;
                $scope.total_suministro = total.toFixed(2);

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
            var data = {
                idtarifa: $scope.s_suministro_tarifa,
                idcalle: $scope.s_suministro_transversal,
                garantia: $scope.t_suministro_garantia,
                codigocliente: $scope.objectAction.codigocliente,
                direccionsuministro: $scope.t_suministro_direccion,
                telefonosuministro: $scope.t_suministro_telf,
                idproducto: $scope.idproducto,
                valor: $scope.total_suministro,
                dividendos: $scope.s_suministro_credito,
                valor_partial: $scope.total_partial
            };

            $http.post(API_URL + 'cliente/storeSolicitudSuministro', data).success(function(response){
                if(response.success == true){
                    $scope.initLoad();
                    $scope.idsolicitud_to_process = response.idsolicitud;
                    $('#btn-save-solsuministro').prop('disabled', true);
                    $('#btn-process-solsuministro').prop('disabled', false);
                    $scope.message = 'Se ha ingresado la solicitud deseada correctamente...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }
            });
        };

        $scope.procesarSolicitudSuministro = function () {
            var data = {
                idtarifa: $scope.s_suministro_tarifa,
                idcalle: $scope.s_suministro_transversal,
                garantia: $scope.t_suministro_garantia,
                codigocliente: $scope.objectAction.codigocliente,
                direccionsuministro: $scope.t_suministro_direccion,
                telefonosuministro: $scope.t_suministro_telf,
                idproducto: $scope.idproducto,
                valor: $scope.total_suministro,
                dividendos: $scope.s_suministro_credito,
                valor_partial: $scope.total_partial,
                idsolicitud: $scope.idsolicitud_to_process
            };

            var url = API_URL + 'cliente/processSolicitudSuministro/' + $scope.idsolicitud_to_process;

            $http.put(url, data ).success(function (response) {
                $scope.idsolicitud_to_process = 0;

                $('#btn-process-solsuministro').prop('disabled', true);

                $('#modalActionSuministro').modal('hide');
                $('#modalAction').modal('hide');

                $scope.message = 'Se procesó correctamente la solicitud...';
                $('#modalMessage').modal('show');

                $scope.hideModalMessage();

            }).error(function (res) {

            });
        };

        /*
         *  ACTIONS FOR SOLICITUD SERVICIOS-----------------------------------------------------------------------------
         */

        $scope.getExistsSolicitudServicio = function () {
            var codigocliente = $scope.objectAction.codigocliente;
            $http.get(API_URL + 'cliente/getExistsSolicitudServicio/' + codigocliente).success(function(response){
                if (response.length == 0){
                    $scope.actionServicioShow();
                } else {
                    var msg = 'El cliente: "' + $scope.objectAction.apellidos + ', ' + $scope.objectAction.nombres;
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
                        idserviciojunta: response[i].idserviciojunta,
                        nombreservicio: response[i].nombreservicio,
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
            $scope.getLastIDSolicServicio();
            $scope.getServicios();

            $scope.t_fecha_process = $scope.nowDate();
            $scope.h_codigocliente = $scope.objectAction.codigocliente;
            $scope.documentoidentidad_cliente = $scope.objectAction.documentoidentidad;
            $scope.nom_cliente = $scope.objectAction.apellidos + ', ' + $scope.objectAction.nombres;
            $scope.direcc_cliente = $scope.objectAction.direcciondomicilio;
            $scope.telf_cliente = $scope.objectAction.telefonoprincipaldomicilio;
            $scope.celular_cliente = $scope.objectAction.celular;
            $scope.telf_trab_cliente = $scope.objectAction.telefonoprincipaltrabajo;
            $scope.tipo_tipo_cliente = $scope.objectAction.tipocliente.nombretipo;

            $('#modalActionServicio').modal('show');
        };

        $scope.saveSolicitudServicio = function () {
            var solicitud = {
                codigocliente: $scope.objectAction.codigocliente,
                servicios: $scope.services
            };

            $http.post(API_URL + 'cliente/storeSolicitudServicios', solicitud).success(function(response){
                if(response.success == true){
                    $scope.initLoad();
                    $scope.idsolicitud_to_process = response.idsolicitud;
                    $('#btn-save-servicio').prop('disabled', true);
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

                    $scope.hideModalMessage();
                }
            });
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

        /*
         *  ACTIONS FOR SOLICITUD MANTENIMIENTO-------------------------------------------------------------------------
         */

        $scope.getLastIDMantenimiento = function () {
            $http.get(API_URL + 'cliente/getLastID/solicitudmantenimiento').success(function(response){
                $scope.num_solicitud_mant = response.id;
            });
        };

        $scope.getSuministros = function () {
            var codigocliente = $scope.objectAction.codigocliente;
            $http.get(API_URL + 'cliente/getSuministros/' + codigocliente).success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: 0}];
                $scope.list_suministros = [];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].direccionsumnistro, id: response[i].numerosuministro});
                    $scope.list_suministros.push(response[i]);
                }
                $scope.suministro_mant = array_temp;
                $scope.s_suministro_mant = 0;
            });
        };

        $scope.showInfoSuministro = function () {
            var numerosuministro = $scope.s_suministro_mant;
            if (numerosuministro != 0 && numerosuministro != undefined) {
                var longitud = $scope.list_suministros.length;

                for (var i = 0; i < longitud; i++) {
                    if (numerosuministro == $scope.list_suministros[i].numerosuministro) {
                        $scope.zona_mant = $scope.list_suministros[i].calle.barrio.nombrebarrio;
                        $scope.transversal_mant = $scope.list_suministros[i].calle.nombrecalle;
                        $scope.tarifa_mant = $scope.list_suministros[i].aguapotable.nombretarifaaguapotable;

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
            var solicitud = {
                codigocliente: $scope.objectAction.codigocliente,
                numerosuministro: $scope.s_suministro_mant,
                observacion: $scope.t_observacion_mant
            };
            $http.post(API_URL + 'cliente/storeSolicitudMantenimiento', solicitud).success(function(response){
                if(response.success == true){
                    $scope.initLoad();
                    $scope.idsolicitud_to_process = response.idsolicitud;
                    $('#btn-save-mant').prop('disabled', true);
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
            $scope.h_codigocliente_mant = $scope.objectAction.codigocliente;
            $scope.documentoidentidad_cliente_mant = $scope.objectAction.documentoidentidad;
            $scope.nom_cliente_mant = $scope.objectAction.apellidos + ' ' + $scope.objectAction.nombres;
            $scope.direcc_cliente_mant = $scope.objectAction.direcciondomicilio;
            $scope.telf_cliente_mant = $scope.objectAction.telefonoprincipaldomicilio;
            $scope.celular_cliente_mant = $scope.objectAction.celular;
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
                $scope.clientes_setN = array_temp;
                $scope.s_ident_new_client_setnombre = 0;
            });
        };

        $scope.showInfoClienteForSetName = function () {
            var codigocliente = $scope.s_ident_new_client_setnombre;

            if (codigocliente != 0 && codigocliente != undefined) {
                var longitud = $scope.list_clientes.length;

                for (var i = 0; i < longitud; i++) {
                    if (codigocliente == $scope.list_clientes[i].codigocliente) {
                        $scope.nom_new_cliente_setnombre = $scope.list_clientes[i].apellidos + ', ' + $scope.list_clientes[i].nombres;
                        $scope.direcc_new_cliente_setnombre = $scope.list_clientes[i].direcciondomicilio;
                        $scope.telf_new_cliente_setnombre = $scope.list_clientes[i].telefonoprincipaldomicilio;
                        $scope.celular_new_cliente_setnombre = $scope.list_clientes[i].celular;
                        $scope.telf_trab_new_cliente_setnombre = $scope.list_clientes[i].telefonoprincipaltrabajo;

                        break;
                    }
                }
            } else {
                $scope.nom_new_cliente_setnombre = '';
                $scope.direcc_new_cliente_setnombre = '';
                $scope.telf_new_cliente_setnombre = '';
                $scope.celular_new_cliente_setnombre = '';
                $scope.telf_trab_new_cliente_setnombre = '';
            }
        };

        $scope.getSuministrosForSetName = function () {
            var codigocliente = $scope.objectAction.codigocliente;
            $http.get(API_URL + 'cliente/getSuministros/' + codigocliente).success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: 0}];
                $scope.list_suministros = [];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].direccionsumnistro, id: response[i].numerosuministro});
                    $scope.list_suministros.push(response[i]);
                }
                $scope.suministro_setN = array_temp;
                $scope.s_suministro_setnombre = 0;
            });
        };

        $scope.showInfoSuministroForSetName = function () {
            var numerosuministro = $scope.s_suministro_setnombre;

            if (numerosuministro != 0 && numerosuministro != undefined) {
                var longitud = $scope.list_suministros.length;

                for (var i = 0; i < longitud; i++) {
                    if (numerosuministro == $scope.list_suministros[i].numerosuministro) {
                        $scope.zona_setnombre = $scope.list_suministros[i].calle.barrio.nombrebarrio;
                        $scope.transversal_setnombre = $scope.list_suministros[i].calle.nombrecalle;
                        $scope.tarifa_setnombre = $scope.list_suministros[i].aguapotable.nombretarifaaguapotable;

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
            var solicitud = {
                codigocliente: $scope.objectAction.codigocliente,
                codigoclientenuevo: $scope.s_ident_new_client_setnombre,
                numerosuministro: $scope.s_suministro_setnombre
            };
            $http.post(API_URL + 'cliente/storeSolicitudCambioNombre', solicitud).success(function(response){
                if(response.success == true){
                    $scope.initLoad();
                    $scope.idsolicitud_to_process = response.idsolicitud;
                    $('#btn-save-setnombre').prop('disabled', true);
                    $('#btn-process-setnombre').prop('disabled', false);
                    $scope.message = 'Se ha ingresado la solicitud deseada correctamente...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }
            });
        };

        $scope.procesarSolicitudSetName = function () {
            var data = {
                codigoclientenuevo: $scope.s_ident_new_client_setnombre
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
            $scope.getIdentifyClientes();

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
            var url = API_URL + 'cliente/processSolicitud/' + $scope.idsolicitud_to_process;

            var data = {
                idsolicitud: $scope.idsolicitud_to_process
            };

            $http.put(url, data ).success(function (response) {
                $scope.idsolicitud_to_process = 0;

                $('#' + id_btn).prop('disabled', true);

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
            $('#modalAction').modal('show');
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