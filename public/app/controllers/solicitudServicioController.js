
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

        $scope.initLoad = function () {
            $http.get(API_URL + 'solicitud/getSolicitudes').success(function(response){

                var list = [];

                console.log(response);

                var suministro = response.suministro;
                if (suministro.length > 0) {
                    var length_suministro = suministro.length;
                    for (var i = 0; i < length_suministro; i++) {
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
                        var object_suministro = {
                            no_solicitud : suministro[i].idsolicitud,
                            fecha: suministro[i].fechasolicitud,
                            cliente: suministro[i].cliente.apellidos + ', ' + suministro[i].cliente.nombres,
                            direccion: suministro[i].cliente.direcciondomicilio,
                            telefono: suministro[i].cliente.telefonoprincipaldomicilio,
                            tipo: 'Suministro',
                            estado: suministro[i].estaprocesada,
                            fechaprocesada: suministro[i].fechaprocesada,
                            terreno: suministro[i].terreno,
                            no_solicitudsuministro: suministro[i].idsolicitudsuministro
                        };

                        list.push(object_suministro);
                    }

                }

                var otro = response.otro;

                if (otro.length > 0) {

                    var length_otro = otro.length;

                    for (var i = 0; i < length_otro; i++) {
                        var object_otro = {
                            no_solicitud : otro[i].idsolicitud,
                            fecha: otro[i].fechasolicitud,
                            cliente: otro[i].cliente.apellidos + ', ' + otro[i].cliente.nombres,
                            direccion: otro[i].cliente.direcciondomicilio,
                            telefono: otro[i].cliente.telefonoprincipaldomicilio,
                            tipo: 'Otra Solicitud',
                            estado: otro[i].estaprocesada,

                            descripcion: otro[i].descripcion,
                            fechaprocesada: otro[i].fechaprocesada
                        };

                        list.push(object_otro);
                    }

                }

                var setnombre = response.setname;

                if (setnombre.length > 0) {

                    var length_setnombre = setnombre.length;

                    for (var i = 0; i < length_setnombre; i++) {
                        var object_setnombre = {
                            no_solicitud : setnombre[i].idsolicitud,
                            fecha: setnombre[i].fechasolicitud,
                            cliente: setnombre[i].cliente.apellidos + ', ' + setnombre[i].cliente.nombres,
                            othercliente: setnombre[i].codigonuevocliente,
                            direccion: setnombre[i].cliente.direcciondomicilio,
                            telefono: setnombre[i].cliente.telefonoprincipaldomicilio,
                            tipo: 'Cambio de Nombre',
                            estado: setnombre[i].estaprocesada,

                            fechaprocesada: setnombre[i].fechaprocesada,
                            terreno: setnombre[i].terreno,
                            no_solicitudsetnombre: setnombre[i].idsolicitudcambionombre
                        };

                        list.push(object_setnombre);
                    }

                }

                var servicio = response.servicio;

                if (servicio.length > 0) {

                    var length_servicio = servicio.length;

                    for (var i = 0; i < length_servicio; i++) {
                        var object_servicio = {
                            no_solicitud : servicio[i].idsolicitud,
                            fecha: servicio[i].fechasolicitud,
                            cliente: servicio[i].cliente.apellidos + ', ' + servicio[i].cliente.nombres,
                            //othercliente: servicio[i].codigonuevocliente,
                            direccion: servicio[i].cliente.direcciondomicilio,
                            telefono: servicio[i].cliente.telefonoprincipaldomicilio,
                            tipo: 'Servicio',
                            estado: servicio[i].estaprocesada,
                            areanueva: servicio[i].nuevaarea,
                            fechaprocesada: servicio[i].fechaprocesada,
                            no_solicitudservicio: servicio[i].idsolicitudservicio
                        };

                        list.push(object_servicio);
                    }

                }

                var mantenimiento = response.mantenimiento;

                if (mantenimiento.length > 0) {

                    var length_mantenimiento = mantenimiento.length;

                    for (var i = 0; i < length_mantenimiento; i++) {
                        var object_mantenimiento = {
                            no_solicitud : mantenimiento[i].idsolicitud,
                            fecha: mantenimiento[i].fechasolicitud,
                            cliente: mantenimiento[i].cliente.apellidos + ', ' + mantenimiento[i].cliente.nombres,
                            //othercliente: servicio[i].codigonuevocliente,
                            direccion: mantenimiento[i].cliente.direcciondomicilio,
                            telefono: mantenimiento[i].cliente.telefonoprincipaldomicilio,
                            tipo: 'Mantenimiento',
                            estado: mantenimiento[i].estaprocesada,
                            areanueva: mantenimiento[i].nuevaarea,
                            fechaprocesada: mantenimiento[i].fechaprocesada,
                            no_solicitudservicio: mantenimiento[i].idsolicitudservicio
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

        $scope.actionOtro = function (solicitud) {

            $scope.num_solicitud_otro = solicitud.data.idsolicitudotro;

            $scope.t_fecha_otro = solicitud.data.fechasolicitud;
            $scope.h_codigocliente_otro = solicitud.data.cliente.codigocliente;
            $scope.documentoidentidad_cliente_otro = solicitud.data.cliente.documentoidentidad;
            $scope.nom_cliente_otro = solicitud.data.cliente.apellidos + ', ' + solicitud.data.cliente.nombres;
            $scope.direcc_cliente_otro = solicitud.data.cliente.direcciondomicilio;
            $scope.telf_cliente_otro = solicitud.data.cliente.telefonoprincipaldomicilio;
            $scope.celular_cliente_otro = solicitud.data.cliente.celular;
            $scope.telf_trab_cliente_otro = solicitud.data.cliente.telefonoprincipaltrabajo;

            $scope.t_observacion_otro = solicitud.data.descripcion;

            if(solicitud.data.estaprocesada == true) {
                $('#btn-save-otro').prop('disabled', true);
                $('#btn-process-otro').prop('disabled', true);
            } else {
                $('#btn-save-otro').prop('disabled', false);
                $('#btn-process-otro').prop('disabled', false);
            }

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

        $scope.actionMantenimiento = function (solicitud) {
            //$scope.getLastIDMantenimiento();
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

            //$('#btn-process-mant').prop('disabled', true);
            $('#modalActionMantenimiento').modal('show');
        };





        $scope.info = function (solicitud) {
            if(solicitud.tipo == 'Otra Solicitud') {
                $scope.actionOtro(solicitud);
            } else if(solicitud.tipo == 'Mantenimiento') {
                $scope.actionMantenimiento(solicitud);
            }
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
