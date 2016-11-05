
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
            { id: 5, name: '-- Todos --' },
            { id: 4, name: 'Riego' },
            { id: 3, name: 'Cambio de Nombre' },
            { id: 2, name: 'Fraccionamiento' },
            { id: 1, name: 'Otros' }
        ];

        $scope.t_tipo_solicitud = 5;

        $scope.initLoad = function () {
            $http.get(API_URL + 'solicitud/getSolicitudes').success(function(response){

                console.log(response);

                var list = [];

                var riego = response.riego;

                if (riego.length > 0) {

                    var length_riego = riego.length;

                    for (var i = 0; i < length_riego; i++) {
                        var object_riego = {
                            no_solicitud : riego[i].idsolicitud,
                            fecha: riego[i].fechasolicitud,
                            cliente: riego[i].cliente.apellido + ' ' + riego[i].cliente.nombre,
                            direccion: riego[i].cliente.direcciondomicilio,
                            telefono: riego[i].cliente.telefonoprincipaldomicilio,
                            tipo: 'Riego',
                            estado: riego[i].estaprocesada,
                            fechaprocesada: riego[i].fechaprocesada,
                            terreno: riego[i].terreno,
                            no_solicitudriego: riego[i].idsolicitudriego
                        };

                        list.push(object_riego);
                    }

                }

                var otro = response.otro;

                if (otro.length > 0) {

                    var length_otro = otro.length;

                    for (var i = 0; i < length_otro; i++) {
                        var object_otro = {
                            no_solicitud : otro[i].idsolicitud,
                            fecha: otro[i].fechasolicitud,
                            cliente: otro[i].cliente.apellido + ' ' + otro[i].cliente.nombre,
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
                            cliente: setnombre[i].cliente.apellido + ' ' + setnombre[i].cliente.nombre,
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

                var reparticion = response.reparticion;

                if (reparticion.length > 0) {

                    var length_reparticion = reparticion.length;

                    for (var i = 0; i < length_reparticion; i++) {
                        var object_reparticion = {
                            no_solicitud : reparticion[i].idsolicitud,
                            fecha: reparticion[i].fechasolicitud,
                            cliente: reparticion[i].cliente.apellido + ' ' + reparticion[i].cliente.nombre,
                            othercliente: reparticion[i].codigonuevocliente,
                            direccion: reparticion[i].cliente.direcciondomicilio,
                            telefono: reparticion[i].cliente.telefonoprincipaldomicilio,
                            tipo: 'Repartición',
                            estado: reparticion[i].estaprocesada,
                            areanueva: reparticion[i].nuevaarea,
                            fechaprocesada: reparticion[i].fechaprocesada,
                            no_solicitudreparticion: reparticion[i].idsolicitudreparticion
                        };

                        list.push(object_reparticion);
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

                console.log(response);

                var list = [];

                var riego = response.riego;

                if (riego.length > 0) {

                    var length_riego = riego.length;

                    for (var i = 0; i < length_riego; i++) {
                        var object_riego = {
                            no_solicitud : riego[i].idsolicitud,
                            fecha: riego[i].fechasolicitud,
                            cliente: riego[i].cliente.apellido + ' ' + riego[i].cliente.nombre,
                            direccion: riego[i].cliente.direcciondomicilio,
                            telefono: riego[i].cliente.telefonoprincipaldomicilio,
                            tipo: 'Riego',
                            estado: riego[i].estaprocesada,
                            fechaprocesada: riego[i].fechaprocesada,
                            terreno: riego[i].terreno
                        };

                        list.push(object_riego);
                    }

                }

                var otro = response.otro;

                if (otro.length > 0) {

                    var length_otro = otro.length;

                    for (var i = 0; i < length_otro; i++) {
                        var object_otro = {
                            no_solicitud : otro[i].idsolicitud,
                            fecha: otro[i].fechasolicitud,
                            cliente: otro[i].cliente.apellido + ' ' + otro[i].cliente.nombre,
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
                            cliente: setnombre[i].cliente.apellido + ' ' + setnombre[i].cliente.nombre,
                            direccion: setnombre[i].cliente.direcciondomicilio,
                            telefono: setnombre[i].cliente.telefonoprincipaldomicilio,
                            tipo: 'Cambio de Nombre',
                            estado: setnombre[i].estaprocesada,

                            fechaprocesada: setnombre[i].fechaprocesada,
                            terreno: setnombre[i].terreno
                        };

                        list.push(object_setnombre);
                    }

                }

                var reparticion = response.reparticion;

                if (reparticion.length > 0) {

                    var length_reparticion = reparticion.length;

                    for (var i = 0; i < length_reparticion; i++) {
                        var object_reparticion = {
                            no_solicitud : reparticion[i].idsolicitud,
                            fecha: reparticion[i].fechasolicitud,
                            cliente: reparticion[i].cliente.apellido + ' ' + reparticion[i].cliente.nombre,
                            direccion: reparticion[i].cliente.direcciondomicilio,
                            telefono: reparticion[i].cliente.telefonoprincipaldomicilio,
                            tipo: 'Repartición',
                            estado: reparticion[i].estaprocesada,
                            areanueva: reparticion[i].nuevaarea,
                            fechaprocesada: reparticion[i].fechaprocesada

                        };

                        list.push(object_reparticion);
                    }

                }

                $scope.solicitudes = list;


            });

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
