
    app.filter('formatDate', function(){
        return function(texto){
            return convertDatetoDB(texto, true);
        }
    });

    app.controller('solicitudController', function($scope, $http, API_URL) {

        $scope.solicitudes = [];
        $scope.idsolicitud = 0;

        $scope.estados = [
            { id: 3, name: '-- Todos --' },
            { id: 2, name: 'En Espera' },
            { id: 1, name: 'Procesado' }
        ];
        $scope.t_estado = 3;

        $scope.tipo = [
            { id: 6, name: '-- Todos --' },
            { id: 5, name: 'Suministro' },
            { id: 4, name: 'Servicios' },
            { id: 3, name: 'Cambio de Nombre' },
            { id: 2, name: 'Mantenimiento' },
            { id: 1, name: 'Otros' }
        ];
        $scope.t_tipo_solicitud = 6;

        $scope.idsolicitud_to_process = 0;
        $scope.objectAction = null;
        $scope.services = [];
        $scope.codigoclienteSuministro = 0;

        $scope.tasainteres = 0;

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

            $http.get(API_URL + 'solicitud/getSolicitudes?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

                console.log(response);

                var longitud = response.data.length;

                if (longitud > 0) {

                    for (var i = 0; i < longitud; i++) {

                        var tipo = '';
                        var idtipo = 0;

                        if (response.data[i].solicitudcambionombre != null) {
                            tipo = 'Cambio de Nombre';
                            idtipo = response.data[i].solicitudcambionombre;
                        } else if (response.data[i].solicitudmantenimiento != null) {
                            tipo = 'Mantenimiento';
                            idtipo = response.data[i].solicitudmantenimiento;
                        } else if (response.data[i].solicitudotro != null) {
                            tipo = 'Otra Solicitud';
                            idtipo = response.data[i].solicitudotro;
                        } else if (response.data[i].solicitudservicio != null) {
                            tipo = 'Servicio';
                            idtipo = response.data[i].solicitudservicio;
                        } else if (response.data[i].solicitudsuministro != null) {
                            tipo = 'Suministro';
                            idtipo = response.data[i].solicitudsuministro;
                        }

                        var tipo_name = {
                            value: tipo,
                            writable: true,
                            enumerable: true,
                            configurable: true
                        };
                        Object.defineProperty(response.data[i], 'tipo', tipo_name);

                        var tipo_id = {
                            value: idtipo,
                            writable: true,
                            enumerable: true,
                            configurable: true
                        };
                        Object.defineProperty(response.data[i], 'tipo_id', tipo_id);

                    }

                }

                $scope.solicitudes = response.data;
                $scope.totalItems = response.total;

                /*var list = [];

                var suministro = response.suministro;
                if (suministro.length > 0) {
                    var length_suministro = suministro.length;
                    for (var i = 0; i < length_suministro; i++) {

                        var complete_name = {
                            value: suministro[i].cliente.apellidos + ', ' + suministro[i].cliente.nombres,
                            writable: true,
                            enumerable: true,
                            configurable: true
                        };
                        Object.defineProperty(suministro[i].cliente, 'complete_name', complete_name);

                        var object_suministro = {
                            tipo: 'Suministro',
                            data: suministro[i]
                        };
                        list.push(object_suministro);
                    }
                }

                var otro = response.otro;
                if (otro.length > 0) {
                    var length_otro = otro.length;
                    for (var i = 0; i < length_otro; i++) {

                        var complete_name_otro = {
                            value: otro[i].cliente.apellidos + ', ' + otro[i].cliente.nombres,
                            writable: true,
                            enumerable: true,
                            configurable: true
                        };
                        Object.defineProperty(otro[i].cliente, 'complete_name', complete_name_otro);

                        var object_otro = {
                            tipo: 'Otra Solicitud',
                            data: otro[i]
                        };
                        list.push(object_otro);
                    }
                }

                var setnombre = response.setname;
                if (setnombre.length > 0) {
                    var length_setnombre = setnombre.length;
                    for (var i = 0; i < length_setnombre; i++) {

                        var complete_name_setnombre = {
                            value: setnombre[i].cliente.apellidos + ', ' + setnombre[i].cliente.nombres,
                            writable: true,
                            enumerable: true,
                            configurable: true
                        };
                        Object.defineProperty(setnombre[i].cliente, 'complete_name', complete_name_setnombre);

                        var object_setnombre = {
                            tipo: 'Cambio de Nombre',
                            data : setnombre[i],
                        };
                        list.push(object_setnombre);
                    }
                }

                var servicio = response.servicio;
                if (servicio.length > 0) {
                    var length_servicio = servicio.length;
                    for (var i = 0; i < length_servicio; i++) {

                        var complete_name_servicio = {
                            value: servicio[i].cliente.apellidos + ', ' + servicio[i].cliente.nombres,
                            writable: true,
                            enumerable: true,
                            configurable: true
                        };
                        Object.defineProperty(servicio[i].cliente, 'complete_name', complete_name_servicio);


                        var object_servicio = {
                            tipo: 'Servicio',
                            data: servicio[i]
                        };
                        list.push(object_servicio);
                    }
                }

                var mantenimiento = response.mantenimiento;
                if (mantenimiento.length > 0) {
                    var length_mantenimiento = mantenimiento.length;
                    for (var i = 0; i < length_mantenimiento; i++) {

                        var complete_name_mantenimiento = {
                            value: mantenimiento[i].cliente.apellidos + ', ' + mantenimiento[i].cliente.nombres,
                            writable: true,
                            enumerable: true,
                            configurable: true
                        };
                        Object.defineProperty(mantenimiento[i].cliente, 'complete_name', complete_name_mantenimiento);

                        var object_mantenimiento = {
                            tipo: 'Mantenimiento',
                            data: mantenimiento[i]
                        };
                        list.push(object_mantenimiento);
                    }
                }

                $scope.solicitudes = list;*/

            });
        };

        $scope.searchByFilter = function () {
            var filter = {
                tipo: $scope.t_tipo_solicitud,
                estado: $scope.t_estado
            };

            $http.get(API_URL + 'solicitud/getByFilter/' + JSON.stringify(filter)).success(function(response){

                var list = [];

                var suministro = response.suministro;
                if (suministro.length > 0) {
                    var length_suministro = suministro.length;
                    for (var i = 0; i < length_suministro; i++) {

                        var complete_name = {
                            value: suministro[i].cliente.apellidos + ', ' + suministro[i].cliente.nombres,
                            writable: true,
                            enumerable: true,
                            configurable: true
                        };
                        Object.defineProperty(suministro[i].cliente, 'complete_name', complete_name);

                        var object_suministro = {
                            tipo: 'Suministro',
                            data: suministro[i]
                        };
                        list.push(object_suministro);
                    }
                }

                var otro = response.otro;
                if (otro.length > 0) {
                    var length_otro = otro.length;
                    for (var i = 0; i < length_otro; i++) {

                        var complete_name_otro = {
                            value: otro[i].cliente.apellidos + ', ' + otro[i].cliente.nombres,
                            writable: true,
                            enumerable: true,
                            configurable: true
                        };
                        Object.defineProperty(otro[i].cliente, 'complete_name', complete_name_otro);

                        var object_otro = {
                            tipo: 'Otra Solicitud',
                            data: otro[i]
                        };
                        list.push(object_otro);
                    }
                }

                var setnombre = response.setname;
                if (setnombre.length > 0) {
                    var length_setnombre = setnombre.length;
                    for (var i = 0; i < length_setnombre; i++) {

                        var complete_name_setnombre = {
                            value: setnombre[i].cliente.apellidos + ', ' + setnombre[i].cliente.nombres,
                            writable: true,
                            enumerable: true,
                            configurable: true
                        };
                        Object.defineProperty(setnombre[i].cliente, 'complete_name', complete_name_setnombre);

                        var object_setnombre = {
                            tipo: 'Cambio de Nombre',
                            data : setnombre[i],
                        };
                        list.push(object_setnombre);
                    }
                }

                var servicio = response.servicio;
                if (servicio.length > 0) {
                    var length_servicio = servicio.length;
                    for (var i = 0; i < length_servicio; i++) {

                        var complete_name_servicio = {
                            value: servicio[i].cliente.apellidos + ', ' + servicio[i].cliente.nombres,
                            writable: true,
                            enumerable: true,
                            configurable: true
                        };
                        Object.defineProperty(servicio[i].cliente, 'complete_name', complete_name_servicio);


                        var object_servicio = {
                            tipo: 'Servicio',
                            data: servicio[i]
                        };
                        list.push(object_servicio);
                    }
                }

                var mantenimiento = response.mantenimiento;
                if (mantenimiento.length > 0) {
                    var length_mantenimiento = mantenimiento.length;
                    for (var i = 0; i < length_mantenimiento; i++) {

                        var complete_name_mantenimiento = {
                            value: mantenimiento[i].cliente.apellidos + ', ' + mantenimiento[i].cliente.nombres,
                            writable: true,
                            enumerable: true,
                            configurable: true
                        };
                        Object.defineProperty(mantenimiento[i].cliente, 'complete_name', complete_name_mantenimiento);

                        var object_mantenimiento = {
                            tipo: 'Mantenimiento',
                            data: mantenimiento[i]
                        };
                        list.push(object_mantenimiento);
                    }
                }

                $scope.solicitudes = list;

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
                observacion: $scope.t_observacion_otro
            };
            var idsolicitud = $scope.num_solicitud_otro;
            $http.put(API_URL + 'solicitud/updateSolicitudOtro/' + idsolicitud, solicitud).success(function(response){
                if(response.success == true){
                    $scope.initLoad();
                    $scope.message = 'Se ha actualizado la solicitud deseada correctamente...';
                    $('#modalMessage').modal('show');

                    $scope.hideModalMessage();
                }
            });
        };

        $scope.actionOtro = function (solicitud) {

            $scope.idsolicitud_to_process = solicitud.idsolicitud;

            $http.get(API_URL + 'solicitud/getSolicitudOtro/' + solicitud.tipo_id).success(function(response){

                $scope.num_solicitud_otro = solicitud.tipo_id;

                $scope.t_fecha_otro = solicitud.fechasolicitud;
                $scope.h_codigocliente_otro = solicitud.cliente.idcliente;
                $scope.documentoidentidad_cliente_otro = solicitud.cliente.persona.numdocidentific;
                $scope.nom_cliente_otro = solicitud.cliente.persona.razonsocial;
                $scope.direcc_cliente_otro = solicitud.cliente.persona.direccion;
                $scope.telf_cliente_otro = solicitud.cliente.telefonoprincipaldomicilio;
                $scope.celular_cliente_otro = solicitud.cliente.persona.celphone;
                $scope.telf_trab_cliente_otro = solicitud.cliente.telefonoprincipaltrabajo;

                $scope.t_observacion_otro = response[0].descripcion;

                if(solicitud.estadoprocesada == true) {
                    $('#t_observacion_otro').prop('disabled', true);
                    $('#btn-save-otro').prop('disabled', true);
                    $('#btn-process-otro').prop('disabled', true);
                    $('#modal-footer-otro').hide();
                } else {
                    $('#t_observacion_otro').prop('disabled', false);
                    $('#btn-save-otro').prop('disabled', false);
                    $('#btn-process-otro').prop('disabled', false);
                    $('#modal-footer-otro').show();
                }

                $('#modalActionOtro').modal('show');

            });


        };

        /*
         *  ACTIONS FOR SOLICITUD MANTENIMIENTO-------------------------------------------------------------------------
         */

        $scope.getLastIDMantenimiento = function () {
            $http.get(API_URL + 'cliente/getLastID/solicitudmantenimiento').success(function(response){
                $scope.num_solicitud_mant = response.id;
            });
        };

        $scope.getSuministros = function (codigocliente, numsuministro) {
            $http.get(API_URL + 'cliente/getSuministros/' + codigocliente).success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: 0}];
                $scope.list_suministros = [];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].direccionsumnistro, id: response[i].numerosuministro});
                    $scope.list_suministros.push(response[i]);
                }
                $scope.suministro_mant = array_temp;
                $scope.s_suministro_mant = numsuministro;
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
                numerosuministro: $scope.s_suministro_mant,
                observacion: $scope.t_observacion_mant
            };
            var idsolicitud = $scope.num_solicitud_mant;
            $http.put(API_URL + 'solicitud/updateSolicitudMantenimiento/' + idsolicitud, solicitud).success(function(response){
                if(response.success == true){
                    $scope.initLoad();
                    $scope.message = 'Se ha actualizado la solicitud deseada correctamente...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }
            });
        };

        $scope.actionMantenimiento = function (solicitud) {

            $scope.idsolicitud_to_process = solicitud.data.idsolicitud;
            $scope.getSuministros(solicitud.data.cliente.codigocliente, solicitud.data.numerosuministro);

            $scope.num_solicitud_mant = solicitud.data.idsolicitudmantenimiento;
            $scope.t_fecha_mant = solicitud.data.fechasolicitud;
            $scope.h_codigocliente_mant = solicitud.data.cliente.codigocliente;
            $scope.documentoidentidad_cliente_mant = solicitud.data.cliente.documentoidentidad;
            $scope.nom_cliente_mant = solicitud.data.cliente.apellidos + ', ' + solicitud.data.cliente.nombres;
            $scope.direcc_cliente_mant = solicitud.data.cliente.direcciondomicilio;
            $scope.telf_cliente_mant = solicitud.data.cliente.telefonoprincipaldomicilio;
            $scope.celular_cliente_mant = solicitud.data.cliente.celular;
            $scope.telf_trab_cliente_mant = solicitud.data.cliente.telefonoprincipaltrabajo;

            $scope.zona_mant = solicitud.data.suministro.calle.barrio.nombrebarrio;
            $scope.transversal_mant = solicitud.data.suministro.calle.nombrecalle;
            $scope.tarifa_mant = solicitud.data.suministro.aguapotable.nombretarifaaguapotable;

            $scope.t_observacion_mant = solicitud.data.observacion;

            if(solicitud.data.estaprocesada == true) {
                $('#s_suministro_mant').prop('disabled', true);
                $('#t_observacion_mant').prop('disabled', true);

                $('#btn-save-mant').prop('disabled', true);
                $('#btn-process-mant').prop('disabled', true);
                $('#modal-footer-mant').hide();
            } else {
                $('#s_suministro_mant').prop('disabled', false);
                $('#t_observacion_mant').prop('disabled', false);

                $('#btn-save-mant').prop('disabled', false);
                $('#btn-process-mant').prop('disabled', false);
                $('#modal-footer-mant').show();
            }

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

        $scope.getIdentifyClientes = function (idcliente, codigoclientenuevo) {
            $http.get(API_URL + 'cliente/getIdentifyClientes/' + idcliente).success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: 0}];
                $scope.list_clientes = [];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].documentoidentidad, id: response[i].codigocliente});
                    $scope.list_clientes.push(response[i]);
                }
                $scope.clientes_setN = array_temp;
                $scope.s_ident_new_client_setnombre = codigoclientenuevo;
                $scope.showInfoClienteForSetName();
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

        $scope.getSuministrosForSetName = function (codigocliente, numerosuministro) {
            $http.get(API_URL + 'cliente/getSuministros/' + codigocliente).success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: 0}];
                $scope.list_suministros = [];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].direccionsumnistro, id: response[i].numerosuministro});
                    $scope.list_suministros.push(response[i]);
                }
                $scope.suministro_setN = array_temp;
                $scope.s_suministro_setnombre = numerosuministro;
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
                codigoclientenuevo: $scope.s_ident_new_client_setnombre,
                numerosuministro: $scope.s_suministro_setnombre
            };
            var idsolicitud = $scope.num_solicitud_setnombre;
            $http.put(API_URL + 'solicitud/updateSolicitudSetName/' + idsolicitud, solicitud).success(function(response){
                if(response.success == true){
                    $scope.initLoad();
                    $scope.message = 'Se ha actualizado la solicitud deseada correctamente...';
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

        $scope.actionSetName = function (solicitud) {

            $scope.idsolicitud_to_process = solicitud.data.idsolicitud;

            $scope.getIdentifyClientes(solicitud.data.codigocliente, solicitud.data.codigoclientenuevo);

            $scope.num_solicitud_setnombre = solicitud.data.idsolicitudcambionombre;
            $scope.t_fecha_setnombre = solicitud.data.fechasolicitud;
            $scope.h_codigocliente_setnombre = solicitud.data.cliente.codigocliente;
            $scope.documentoidentidad_cliente_setnombre = solicitud.data.cliente.documentoidentidad;
            $scope.nom_cliente_setnombre = solicitud.data.cliente.apellidos + ', ' + solicitud.data.cliente.nombres;
            $scope.direcc_cliente_setnombre = solicitud.data.cliente.direcciondomicilio;
            $scope.telf_cliente_setnombre = solicitud.data.cliente.telefonoprincipaldomicilio;
            $scope.celular_cliente_setnombre = solicitud.data.cliente.celular;
            $scope.telf_trab_cliente_setnombre = solicitud.data.cliente.telefonoprincipaltrabajo;

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


            if(solicitud.data.estaprocesada == true) {

                $scope.suministro_setN = [{label: solicitud.data.suministro.direccionsumnistro, id: solicitud.data.numerosuministro}];
                $scope.s_suministro_setnombre = solicitud.data.numerosuministro;

                $scope.zona_setnombre = solicitud.data.suministro.calle.barrio.nombrebarrio;
                $scope.transversal_setnombre = solicitud.data.suministro.calle.nombrecalle;
                $scope.tarifa_setnombre = solicitud.data.suministro.aguapotable.nombretarifaaguapotable;

                $('#s_suministro_setnombre').prop('disabled', true);
                $('#s_ident_new_client_setnombre').prop('disabled', true);

                $('#btn-save-setnombre').prop('disabled', true);
                $('#btn-process-setnombre').prop('disabled', true);
                $('#modal-footer-setnombre').hide();
            } else {

                $scope.getSuministrosForSetName(solicitud.data.codigocliente, solicitud.data.numerosuministro);

                $scope.zona_setnombre = solicitud.data.suministro.calle.barrio.nombrebarrio;
                $scope.transversal_setnombre = solicitud.data.suministro.calle.nombrecalle;
                $scope.tarifa_setnombre = solicitud.data.suministro.aguapotable.nombretarifaaguapotable;

                $('#s_suministro_setnombre').prop('disabled', false);
                $('#s_ident_new_client_setnombre').prop('disabled', false);

                $('#btn-save-setnombre').prop('disabled', false);
                $('#btn-process-setnombre').prop('disabled', false);
                $('#modal-footer-setnombre').show();
            }

            $('#modalActionSetNombre').modal('show');
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

        $scope.saveSolicitudServicio = function () {
            var solicitud = {
                servicios: $scope.services
            };
            var idsolicitud = $scope.num_solicitud_servicio;
            $http.put(API_URL + 'solicitud/updateSolicitudServicio/' + idsolicitud, solicitud).success(function(response){
                if(response.success == true){
                    $scope.initLoad();
                    $scope.message = 'Se ha actualizado la solicitud deseada correctamente...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }
            });
        };

        $scope.actionServicioShow = function (solicitud) {

            $scope.idsolicitud_to_process = solicitud.data.idsolicitud;

            $scope.num_solicitud_servicio = solicitud.data.idsolicitudservicio;
            $scope.t_fecha_process = solicitud.data.fechasolicitud;
            $scope.h_codigocliente = solicitud.data.cliente.codigocliente;
            $scope.documentoidentidad_cliente = solicitud.data.cliente.documentoidentidad;
            $scope.nom_cliente = solicitud.data.cliente.apellidos + ', ' + solicitud.data.cliente.nombres;
            $scope.direcc_cliente = solicitud.data.cliente.direcciondomicilio;
            $scope.telf_cliente = solicitud.data.cliente.telefonoprincipaldomicilio;
            $scope.celular_cliente = solicitud.data.cliente.celular;
            $scope.telf_trab_cliente = solicitud.data.cliente.telefonoprincipaltrabajo;
            $scope.tipo_tipo_cliente = solicitud.data.cliente.tipocliente.nombretipo;

            var servicios = solicitud.data.cliente.servicioscliente;
            var longitud = servicios.length;
            var array_temp = [];
            for (var i = 0; i < longitud; i++) {
                var object_service = {
                    idserviciojunta: servicios[i].idserviciojunta,
                    nombreservicio: servicios[i].serviciojunta.nombreservicio,
                    valor: servicios[i].valor
                };
                array_temp.push(object_service);
            }
            $scope.services = array_temp;

            if(solicitud.data.estaprocesada == true) {
                $('#btn-save-servicio').prop('disabled', true);
                $('#btn-process-servicio').prop('disabled', true);
                $('#modal-footer-servicio').hide();
            } else {
                $('#btn-save-servicio').prop('disabled', false);
                $('#btn-process-servicio').prop('disabled', false);
                $('#modal-footer-servicio').show();
            }

            $('#modalActionServicio').modal('show');
        };

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

        $scope.getTarifas = function (idtarifaaguapotable) {
            $http.get(API_URL + 'cliente/getTarifas').success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: 0}];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nombretarifaaguapotable, id: response[i].idtarifaaguapotable})
                }
                $scope.tarifas = array_temp;
                if (idtarifaaguapotable != undefined) {
                    $scope.s_suministro_tarifa = idtarifaaguapotable;
                } else $scope.s_suministro_tarifa = 0;

            });
        };

        $scope.getBarrios = function (idbarrio) {
            $http.get(API_URL + 'cliente/getBarrios').success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: 0}];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nombrebarrio, id: response[i].idbarrio})
                }
                $scope.barrios = array_temp;

                if (idbarrio != undefined) {
                    $scope.s_suministro_zona = idbarrio;
                } else $scope.s_suministro_zona = 0;

                $scope.calles = [{label: '-- Seleccione --', id: 0}];
                $scope.s_suministro_transversal = 0;
            });
        };

        $scope.getCalles = function (idbarrio, idcalle) {

            if (idbarrio == 0 || idbarrio == undefined) {
                idbarrio = $scope.s_suministro_zona;
            }

            $http.get(API_URL + 'cliente/getCalles/' + idbarrio).success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: 0}];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nombrecalle, id: response[i].idcalle})
                }
                $scope.calles = array_temp;
                if (idcalle != undefined) {
                    $scope.s_suministro_transversal = idcalle;
                } else $scope.s_suministro_transversal = 0;

            });

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

                var C = parseFloat($scope.t_suministro_aguapotable) + parseFloat($scope.t_suministro_alcantarillado);
                if ($scope.t_suministro_costomedidor != ''){
                    C += parseFloat($scope.t_suministro_costomedidor);
                }

                var I = n * ($scope.tasainteres / 100) * C;

                var M = C + I;

                var cuotas = (M - parseFloat($scope.t_suministro_cuota)) / $scope.s_suministro_credito;

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

        $scope.actionSuministro = function (solicitud) {
            $scope.getInfoMedidor();
            //$scope.getLastIDSolSuministro();
            //$scope.getLastIDSuministro();

            $scope.codigoclienteSuministro = solicitud.data.codigocliente;

            if (solicitud.data.suministro != null) {
                $scope.getTarifas(solicitud.data.suministro.idtarifaaguapotable);
                $scope.getBarrios(solicitud.data.suministro.calle.idbarrio);
                $scope.getCalles(solicitud.data.suministro.calle.idbarrio, solicitud.data.suministro.calle.idcalle);
                $scope.t_suministro_nro = solicitud.data.numerosuministro;

                $scope.total_partial = solicitud.data.suministro.cuentaporcobrarsuministro[0].pagototal;
                $scope.credit_cant = solicitud.data.suministro.cuentaporcobrarsuministro[0].dividendos;
                $scope.total_suministro = solicitud.data.suministro.cuentaporcobrarsuministro[0].pagoporcadadividendo;

                $('#info_partial').show();
                $('#info_total').show();

                $('#fieldset_suministro_datoscosto').hide();
                $('#modal-footer-suministro').hide();

                $('#s_suministro_tarifa').prop('disabled', true);
                $('#s_suministro_zona').prop('disabled', true);
                $('#s_suministro_transversal').prop('disabled', true);
                $('#t_suministro_direccion').prop('disabled', true);
                $('#t_suministro_telf').prop('disabled', true);

            } else {
                $scope.getTarifas();
                $scope.getBarrios();
                $scope.getLastIDSuministro();

                $('#info_partial').hide();
                $('#info_total').hide();

                $('#fieldset_suministro_datoscosto').show();
                $('#modal-footer-suministro').show();

                $('#s_suministro_tarifa').prop('disabled', false);
                $('#s_suministro_zona').prop('disabled', false);
                $('#s_suministro_transversal').prop('disabled', false);
                $('#t_suministro_direccion').prop('disabled', false);
                $('#t_suministro_telf').prop('disabled', false);
            }

            $scope.getDividendo();
            $scope.num_solicitud_suministro = solicitud.data.idsolicitudsuministro;
            $scope.t_suministro_medidor = false;
            $scope.nom_cliente_suministro = solicitud.data.cliente.apellidos + ', ' + solicitud.data.cliente.nombres;

            $scope.t_suministro_direccion = solicitud.data.direccioninstalacion;
            $scope.t_suministro_telf = solicitud.data.telefonosuminstro;
            $scope.t_suministro_aguapotable = '';
            $scope.t_suministro_alcantarillado = '';
            $scope.t_suministro_garantia = '';
            $scope.t_suministro_cuota = '';

            $('#btn-process-solsuministro').prop('disabled', true);

            $('#modalActionSuministro').modal('show');
        };

        $scope.saveSolicitudSuministro = function () {

            var data = {
                direccionsuministro: $scope.t_suministro_direccion,
                telefonosuministro: $scope.t_suministro_telf,
            };
            var idsolicitud = $scope.num_solicitud_suministro;
            $http.put(API_URL + 'solicitud/updateSolicitudSuministro/' + idsolicitud, data).success(function(response){
                if(response.success == true){
                    $scope.initLoad();
                    $('#btn-process-solsuministro').prop('disabled', false);
                    $scope.message = 'Se ha actualizado la solicitud deseada correctamente...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }
            });
        };

        $scope.procesarSolicitudSuministro = function () {

            if ($scope.t_suministro_medidor == false || $scope.t_suministro_medidor == 0 || $scope.t_suministro_medidor == 'off') {
                var tiene = 'NO'
            } else {
                var tiene = 'SI'
            }

            var tarifa = $('#s_suministro_tarifa option:selected').text();
            var zona = $('#s_suministro_zona option:selected').text();
            var transversal = $('#s_suministro_transversal option:selected').text();

            var data_to_pdf = {
                tarifa: tarifa,
                zona: zona,
                transversal: transversal,
                no_suministro: $scope.t_suministro_nro,
                nomcliente: $scope.nom_cliente_suministro,
                ci: '',
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

                tiene_medidor: tiene,
                marca_medidor: $scope.t_suministro_marca,
                costo_medidor: $scope.t_suministro_costomedidor,
            };

            var data = {
                idtarifa: $scope.s_suministro_tarifa,
                idcalle: $scope.s_suministro_transversal,
                garantia: $scope.t_suministro_garantia,
                codigocliente: $scope.codigoclienteSuministro,
                direccionsuministro: $scope.t_suministro_direccion,
                telefonosuministro: $scope.t_suministro_telf,
                idproducto: $scope.idproducto,
                valor: $scope.total_suministro,
                dividendos: $scope.s_suministro_credito,
                valor_partial: $scope.total_partial,
                idsolicitud: $scope.num_solicitud_suministro,

                data_to_pdf: JSON.stringify(data_to_pdf)
            };

            var url = API_URL + 'cliente/processSolicitudSuministro/' + $scope.num_solicitud_suministro;

            $http.put(url, data ).success(function (response) {
                $scope.initLoad();
                $scope.codigoclienteSuministro = 0;
                //$('#btn-process-solsuministro').prop('disabled', true);

                $('#modalActionSuministro').modal('hide');
                //$('#modalAction').modal('hide');

                $scope.message = 'Se procesó correctamente la solicitud...';
                $('#modalMessage').modal('show');

                $scope.hideModalMessage();

            }).error(function (res) {

            });
        };

        $scope.viewPDF = function (url) {
            window.open(url);
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

                $scope.initLoad();

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

        $scope.info = function (solicitud) {
            if(solicitud.tipo == 'Otra Solicitud') {
                $scope.actionOtro(solicitud);
            } else if(solicitud.tipo == 'Mantenimiento') {
                $scope.actionMantenimiento(solicitud);
            } else if(solicitud.tipo == 'Cambio de Nombre') {
                $scope.actionSetName(solicitud);
            } else if(solicitud.tipo == 'Servicio') {
                $scope.actionServicioShow(solicitud);
            } else if(solicitud.tipo == 'Suministro') {
                $scope.actionSuministro(solicitud);
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

        $scope.sort = function(keyname){
            $scope.sortKey = keyname;
            $scope.reverse = !$scope.reverse;
        };

        $scope.initLoad();
    });

    $(function(){

        $('[data-toggle="tooltip"]').tooltip();

        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'DD/MM/YYYY'
        });

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

    function now(){
        var now = new Date();
        var dd = now.getDate();
        if (dd < 10) dd = '0' + dd;
        var mm = now.getMonth() + 1;
        if (mm < 10) mm = '0' + mm;
        var yyyy = now.getFullYear();
        return dd + "\/" + mm + "\/" + yyyy;
    }
