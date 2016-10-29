

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
        { id: 1, name: 'Procesado' },
    ];

    $scope.t_estado = 3;

    $scope.tipo = [
        { id: 5, name: '-- Todos --' },
        { id: 4, name: 'Riego' },
        { id: 3, name: 'Cambio de Nombre' },
        { id: 2, name: 'Fraccionamiento' },
        { id: 1, name: 'Otros' },
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

    //---------------------------------------------------------------------------------------------

    $scope.getBarrios = function(idbarrio){
        $http.get(API_URL + 'cliente/getBarrios').success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: 0}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].nombrebarrio, id: response[i].idbarrio})
            }
            $scope.barrios = array_temp;

            if (idbarrio == undefined) {
                $scope.t_junta = 0;
            } else {
                $scope.t_junta = idbarrio;
            }
        });
    };

    $scope.getTomas = function(idbarrio, idcalle){

        var idbarrio_search = 0;

        if (idbarrio == undefined){
            idbarrio_search = $scope.t_junta;
        } else {
            idbarrio_search = idbarrio;
        }

        $http.get(API_URL + 'cliente/getTomas/' + idbarrio_search).success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: 0}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].nombrecalle, id: response[i].idcalle})
            }
            $scope.tomas = array_temp;

            if (idcalle == undefined){
                $scope.t_toma = 0;
            } else {
                $scope.t_toma = idcalle;
            }

        });

    };

    $scope.getCanales = function(idcalle, idcanal){

        var idcalle_search = 0;

        if (idcalle == undefined){
            idcalle_search = $scope.t_toma;
        } else {
            idcalle_search = idcalle;
        }

        $http.get(API_URL + 'cliente/getCanales/' + idcalle_search).success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: 0}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].nombrecanal, id: response[i].idcanal})
            }
            $scope.canales = array_temp;

            if (idcanal == undefined) {
                $scope.t_canal = 0;
            } else {
                $scope.t_canal = idcanal;
            }
        });

    };

    $scope.getDerivaciones = function(idcanal, idderivacion){

        var idcanal_search = 0;

        if (idcanal == undefined) {
            idcanal_search = $scope.t_canal;
        } else {
            idcanal_search = idcanal;
        }

        $http.get(API_URL + 'cliente/getDerivaciones/' + idcanal_search).success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: 0}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].nombrederivacion, id: response[i].idderivacion})
            }
            $scope.derivaciones = array_temp;

            if (idderivacion == undefined) {
                $scope.t_derivacion = 0;
            } else {
                $scope.t_derivacion = idderivacion;
            }
        });

    };

    $scope.getTarifas = function(idtarifa){
        $http.get(API_URL + 'cliente/getTarifas').success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: 0}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].nombretarifa, id: response[i].idtarifa})
            }
            $scope.tarifas = array_temp;

            if (idtarifa == undefined) {
                $scope.t_tarifa = 0;
            } else {
                $scope.t_tarifa = idtarifa;
            }
        });
    };

    $scope.getCultivos = function(idtarifa, idcultivo){

        var idtarifa_search = 0;

        if (idtarifa == undefined) {
            idtarifa_search = $scope.t_tarifa;
        } else {
            idtarifa_search = idtarifa;
        }

        $http.get(API_URL + 'cliente/getCultivos/' + idtarifa_search).success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: 0}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].nombrecultivo, id: response[i].idcultivo})
            }
            $scope.cultivos = array_temp;

            if (idcultivo == undefined) {
                $scope.t_cultivo = 0;
            } else {
                $scope.t_cultivo = idcultivo;
            }
        });

    };

    $scope.calculate = function () {
        $scope.calculateCaudal();
        $scope.calculateValor();
    };

    $scope.calculateCaudal = function () {
        $http.get(API_URL + 'cliente/getConstante').success(function(response){
            var area = parseInt($scope.t_area);
            var constante = parseFloat(response[0].constante);

            var caudal_result = (area / 1000) * constante;

            $scope.calculate_caudal = caudal_result.toFixed(2);
        });
    };

    $scope.calculateValor = function () {
        var area = $scope.t_area;

        $http.get(API_URL + 'cliente/calculateValor/' + area).success(function(response){
            $scope.valor_total = parseFloat(response.costo).toFixed(2);
        });
    };

    $scope.calculateFraccion = function () {

        if ($scope.t_area_fraccion != '' && $scope.t_area_fraccion != undefined){
            $scope.calculateCaudalFraccion();
            $scope.calculateValorFraccion();
        }

    };

    $scope.calculateCaudalFraccion = function () {
        $http.get(API_URL + 'cliente/getConstante').success(function(response){
            var area = parseInt($scope.t_area_fraccion);
            var constante = parseFloat(response[0].constante);

            var caudal_result = (area / 1000) * constante;

            $scope.caudal_new_fraccion = caudal_result.toFixed(2);
        });
    };

    $scope.calculateValorFraccion = function () {
        var area = $scope.t_area_fraccion;

        $http.get(API_URL + 'cliente/calculateValor/' + area).success(function(response){
            $scope.valor_new_fraccion = parseFloat(response.costo).toFixed(2);
        });
    };


    $scope.getTerrenosByCliente = function (idcliente, idterreno) {

        var codigocliente = 0;

        if (idcliente == undefined) {
            codigocliente = $scope.terrenos_fraccion;
        } else {
            codigocliente = idcliente;
        }

        var idcliente_search = {
            codigocliente: codigocliente
        };

        $http.get(API_URL + 'cliente/getTerrenosByCliente/' + JSON.stringify(idcliente_search)).success(function(response){
            console.log(response);

            $scope.list_terrenos = response;

            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: 0}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].area, id: response[i].idterreno})
            }

            $scope.terrenos_setN = array_temp;

            if (idterreno == undefined){
                $scope.t_terrenos_setnombre = 0;
            } else {
                $scope.t_terrenos_setnombre = idterreno;
            }


        });
    };

    $scope.getTerrenosFraccionByCliente = function (idcliente, idterreno) {

        var codigocliente = 0;

        if (idcliente == undefined) {
            codigocliente = $scope.t_terrenos_fraccion;
        } else {
            codigocliente = idcliente;
        }

        var idcliente_search = {
            codigocliente: codigocliente
        };

        $http.get(API_URL + 'cliente/getTerrenosByCliente/' + JSON.stringify(idcliente_search)).success(function(response){
            console.log(response);

            $scope.list_terrenos = response;

            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: 0}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].area, id: response[i].idterreno})
            }

            $scope.terrenos_fraccion = array_temp;
            if (idterreno == undefined){
                $scope.t_terrenos_fraccion = 0;
            } else {
                $scope.t_terrenos_fraccion = idterreno;
            }
        });
    };

    $scope.getIdentifyClientes = function (idcliente, idcliente_selected) {
        var codigocliente = 0;

        if (idcliente == undefined) {
            codigocliente = $scope.t_terrenos_setnombre;
        } else {
            codigocliente = idcliente;
        }

        var idcliente_search = {
            codigocliente: codigocliente
        };

        $http.get(API_URL + 'cliente/getIdentifyClientes/' + JSON.stringify(idcliente_search)).success(function(response){
            console.log(response);

            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: 0}];
            var selected = 0;

            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].documentoidentidad, id: response[i].codigocliente});

                if (idcliente_selected != undefined && idcliente_selected == response[i].codigocliente) {
                    selected = i;
                }
            }

            //$('.selectpicker').selectpicker('refresh');
            //$('.selectpicker').selectpicker();

            $scope.clientes_setN = array_temp;
            //$('.selectpicker').selectpicker('refresh');

            if(idcliente_selected == undefined) {
                $scope.t_ident_new_client_setnombre = 0;
            } else {
                $scope.t_ident_new_client_setnombre = response[selected].codigocliente;

                $scope.nom_new_cliente_setnombre = response[selected].apellido + ' ' + response[selected].nombre;
                $scope.direcc_new_cliente_setnombre = response[selected].direcciondomicilio;
                $scope.telf_new_cliente_setnombre = response[selected].telefonoprincipaldomicilio;
                $scope.celular_new_cliente_setnombre = response[selected].celular;
                $scope.telf_trab_new_cliente_setnombre = response[selected].telefonoprincipaltrabajo;
            }

        });
    };

    $scope.getIdentifyClientesFraccion = function (idcliente, idcliente_selected) {
        var codigocliente = 0;

        if (idcliente == undefined) {
            codigocliente = $scope.t_ident_new_client_fraccion;
        } else {
            codigocliente = idcliente;
        }

        var idcliente_search = {
            codigocliente: codigocliente
        };

        $http.get(API_URL + 'cliente/getIdentifyClientes/' + JSON.stringify(idcliente_search)).success(function(response){
            console.log(response);

            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: 0}];
            var selected = 0;

            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].documentoidentidad, id: response[i].codigocliente});

                if (idcliente_selected != undefined && idcliente_selected == response[i].codigocliente) {
                    selected = i;
                }
            }

            //$('.selectpicker').selectpicker('refresh');
            //$('.selectpicker').selectpicker();

            $scope.clientes_fraccion = array_temp;
            //$('.selectpicker').selectpicker('refresh');

            if(idcliente_selected == undefined) {
                $scope.t_ident_new_client_fraccion = 0;
            } else {
                $scope.t_ident_new_client_fraccion = response[selected].codigocliente;

                $scope.nom_new_cliente_fraccion = response[selected].apellido + ' ' + response[selected].nombre;
            }
        });
    };

    $scope.searchInfoTerreno = function () {

        console.log($scope.list_terrenos);

        var longitud = ($scope.list_terrenos).length;

        for (var i = 0; i < longitud; i++){
            if ($scope.list_terrenos[i].idterreno == $scope.t_terrenos_setnombre){
                $scope.junta_setnombre = $scope.list_terrenos[i].derivacion.canal.calle.barrio.nombrebarrio;
                $scope.toma_setnombre = $scope.list_terrenos[i].derivacion.canal.calle.nombrecalle;
                $scope.canal_setnombre = $scope.list_terrenos[i].derivacion.canal.nombrecanal;
                $scope.derivacion_setnombre = $scope.list_terrenos[i].derivacion.nombrederivacion;
                $scope.cultivo_setnombre = $scope.list_terrenos[i].cultivo.nombrecultivo;
                $scope.area_setnombre = $scope.list_terrenos[i].area;
                $scope.caudal_setnombre = $scope.list_terrenos[i].caudal;

                break;
            }
        }

    };

    $scope.searchInfoTerrenoFraccion = function () {

        console.log($scope.list_terrenos);

        var longitud = ($scope.list_terrenos).length;

        for (var i = 0; i < longitud; i++){
            if ($scope.list_terrenos[i].idterreno == $scope.t_terrenos_fraccion){
                $scope.junta_fraccion = $scope.list_terrenos[i].derivacion.canal.calle.barrio.nombrebarrio;
                $scope.toma_fraccion = $scope.list_terrenos[i].derivacion.canal.calle.nombrecalle;
                $scope.canal_fraccion = $scope.list_terrenos[i].derivacion.canal.nombrecanal;
                $scope.derivacion_fraccion = $scope.list_terrenos[i].derivacion.nombrederivacion;
                $scope.cultivo_fraccion = $scope.list_terrenos[i].cultivo.nombrecultivo;
                $scope.area_fraccion = $scope.list_terrenos[i].area;
                $scope.caudal_fraccion = $scope.list_terrenos[i].caudal;
                $scope.valor_fraccion = $scope.list_terrenos[i].valoranual;
                break;
            }
        }

    };

    $scope.getClienteByIdentify = function () {
        var idcliente = {
            codigocliente: $scope.t_ident_new_client_setnombre
        };

        $http.get(API_URL + 'cliente/getClienteByIdentify/' + JSON.stringify(idcliente)).success(function(response){
            console.log(response);

            $scope.h_new_codigocliente_setnombre = response[0].codigocliente;
            $scope.nom_new_cliente_setnombre = response[0].apellido + ' ' + response[0].nombre;
            $scope.direcc_new_cliente_setnombre = response[0].direcciondomicilio;
            $scope.telf_new_cliente_setnombre = response[0].telefonoprincipaldomicilio;
            $scope.celular_new_cliente_setnombre = response[0].celular;
            $scope.telf_trab_new_cliente_setnombre = response[0].telefonoprincipaltrabajo;

        });
    };

    $scope.getClienteByIdentifyFraccion = function () {
        var idcliente = {
            codigocliente: $scope.t_ident_new_client_fraccion
        };

        $http.get(API_URL + 'cliente/getClienteByIdentify/' + JSON.stringify(idcliente)).success(function(response){
            console.log(response);

            $scope.h_new_codigocliente_fraccion = response[0].codigocliente;
            $scope.nom_new_cliente_fraccion = response[0].apellido + ' ' + response[0].nombre;
            $scope.direcc_new_cliente_fraccion = response[0].direcciondomicilio;
            $scope.telf_new_cliente_fraccion = response[0].telefonoprincipaldomicilio;
            $scope.celular_new_cliente_fraccion = response[0].celular;
            $scope.telf_trab_new_cliente_fraccion = response[0].telefonoprincipaltrabajo;

        });
    };

    //--------------------------------------------------------------------------------------------

    $scope.showModalProcesar = function(solicitud) {
        $scope.num_solicitud_process = solicitud.no_solicitud;
        $scope.cliente_process = solicitud.cliente;
        $scope.tipo_process = solicitud.tipo;

        $scope.idsolicitud = solicitud.no_solicitud;

        if (solicitud.tipo == 'Cambio de Nombre'){
            $scope.idsolicitud = solicitud.no_solicitudsetnombre;
            $('#modalProcesarSetNombre').modal('show');
        } else if (solicitud.tipo == 'Repartición'){
            $scope.idsolicitud = solicitud.no_solicitudreparticion
            $('#modalProcesarFraccion').modal('show');
        } else if (solicitud.tipo == 'Riego'){
            $('#modalProcesarRiego').modal('show');
        } else {
            $('#modalProcesar').modal('show');
        }
    };

    $scope.procesarSolicitud = function () {
        var url = API_URL + 'solicitud/getSolicitudOtro/' + $scope.idsolicitud;

        $http.get(url).success(function(response){

            console.log(response);

            $scope.num_solicitud_otro = response[0].idsolicitudotro;
            $scope.t_fecha_otro = response[0].fechasolicitud;
            $scope.documentoidentidad_cliente_otro = response[0].cliente.documentoidentidad;
            $scope.nom_cliente_otro = response[0].cliente.apellido + ' ' + response[0].cliente.nombre;
            $scope.direcc_cliente_otro = response[0].cliente.direcciondomicilio;
            $scope.telf_cliente_otro = response[0].cliente.telefonoprincipaldomicilio;
            $scope.celular_cliente_otro = response[0].cliente.celular;
            $scope.telf_trab_cliente_otro = response[0].cliente.telefonoprincipaltrabajo;
            $scope.t_observacion_otro = response[0].descripcion;

            $('#modalProcesar').modal('hide');

            $('#modalActionOtro').modal('show');
        });

    };

    $scope.saveSolicitudOtro = function () {

        var solicitud = {
            codigocliente: $scope.h_codigocliente_otro,
            observacion: $scope.t_observacion_otro,
            fecha_solicitud: $scope.t_fecha_otro
        };

        $http.put(API_URL + 'solicitud/updateSolicitudOtro/' + $scope.num_solicitud_otro, solicitud).success(function(response){
            if(response.success == true){
                $scope.initLoad();
                //$('#modalActionOtro').modal('hide');
                $scope.message = 'Se ha actualizado la solicitud correctamente...';
                $('#modalMessage').modal('show');
            }
        });

    };

    $scope.procesarSolicitudOtro = function() {
        var url = API_URL + 'solicitud/' + $scope.idsolicitud;

        var data = {
            idsolicitud: $scope.idsolicitud
        };

        $http.put(url, data ).success(function (response) {
            $scope.initLoad();

            $scope.idsolicitud = 0;
            $('#modalActionOtro').modal('hide');
            $scope.message = 'Se procesó correctamente la solicitud seleccionada...';
            $('#modalMessage').modal('show');

        }).error(function (res) {

        });
    };


    $scope.showSolicitudRiego = function () {
        var url = API_URL + 'solicitud/getSolicitudRiego/' + $scope.idsolicitud;

        $http.get(url).success(function(response){

            console.log(response);

            $scope.num_solicitud_riego = response[0].idsolicitudriego;
            $scope.t_fecha_process = response[0].fechasolicitud;
            $scope.documentoidentidad_cliente = response[0].cliente.documentoidentidad;
            $scope.nom_cliente = response[0].cliente.apellido + ' ' + response[0].cliente.nombre;
            $scope.direcc_cliente = response[0].cliente.direcciondomicilio;
            $scope.telf_cliente = response[0].cliente.telefonoprincipaldomicilio;
            $scope.celular_cliente = response[0].cliente.celular;
            $scope.telf_trab_cliente = response[0].cliente.telefonoprincipaltrabajo;

            $scope.nro_terreno = response[0].idterreno;

            $scope.getTarifas(response[0].terreno.idtarifa);
            $scope.getCultivos(response[0].terreno.idtarifa, response[0].terreno.idcultivo);

            var idbarrio = response[0].terreno.derivacion.canal.calle.idbarrio;
            var idcalle = response[0].terreno.derivacion.canal.idcalle;
            var idcanal = response[0].terreno.derivacion.idcanal;
            var idderivacion = response[0].terreno.idderivacion;

            $scope.getBarrios(idbarrio);
            $scope.getTomas(idbarrio, idcalle);
            $scope.getCanales(idcalle, idcanal);
            $scope.getDerivaciones(idcanal, idderivacion);

            $scope.t_area = response[0].terreno.area;
            $scope.calculate_caudal = response[0].terreno.caudal;
            $scope.valor_total = response[0].terreno.valoranual;

            $scope.t_observacion_riego = response[0].observacion;

            $('#modalProcesarRiego').modal('hide');

            $('#modalActionRiego').modal('show');
        });

    };

    $scope.saveSolicitudRiego = function () {

        var solicitud = {
            fecha_solicitud: $scope.t_fecha_process,
            codigocliente: $scope.h_codigocliente,
            idbarrio: $scope.t_junta,
            idcultivo: $scope.t_cultivo,
            area: $scope.t_area,
            caudal: $scope.calculate_caudal,
            valoranual: $scope.valor_total,
            idtarifa: $scope.t_tarifa,
            idderivacion : $scope.t_derivacion,
            observacion: $scope.t_observacion_riego
        };

        $http.put(API_URL + 'solicitud/updateSolicitudRiego/' + $scope.num_solicitud_riego, solicitud).success(function(response){
            if(response.success == true){
                $scope.initLoad();
                //$('#modalActionRiego').modal('hide');
                $scope.message = 'Se ha actualizado la solicitud correctamente...';
                $('#modalMessage').modal('show');
            }
        });

    };

    $scope.procesarSolicitudRiego = function() {
        var url = API_URL + 'solicitud/' + $scope.idsolicitud;

        var data = {
            idsolicitud: $scope.idsolicitud
        };

        $http.put(url, data ).success(function (response) {
            $scope.initLoad();

            $scope.idsolicitud = 0;
            $('#modalActionRiego').modal('hide');
            $scope.message = 'Se procesó correctamente la solicitud seleccionada...';
            $('#modalMessage').modal('show');

        }).error(function (res) {

        });
    };



    $scope.showSolicitudSetN = function () {
        var url = API_URL + 'solicitud/getSolicitudSetN/' + $scope.idsolicitud;

        $http.get(url).success(function(response){

            console.log(response);

            $scope.getTerrenosByCliente(response[0].codigocliente, response[0].idterreno);

            $scope.getIdentifyClientes(response[0].codigocliente, response[0].codigonuevocliente);

            $scope.h_codigocliente_setnombre = response[0].codigocliente;
            $scope.h_new_codigocliente_setnombre = response[0].codigonuevocliente;

            $scope.num_solicitud_setnombre = response[0].idsolicitudcambionombre;
            $scope.t_fecha_setnombre = response[0].fechasolicitud;
            $scope.documentoidentidad_cliente_setnombre = response[0].cliente.documentoidentidad;
            $scope.nom_cliente_setnombre = response[0].cliente.apellido + ' ' + response[0].cliente.nombre;
            $scope.direcc_cliente_setnombre = response[0].cliente.direcciondomicilio;
            $scope.telf_cliente_setnombre = response[0].cliente.telefonoprincipaldomicilio;
            $scope.celular_cliente_setnombre = response[0].cliente.celular;
            $scope.telf_trab_cliente_setnombre = response[0].cliente.telefonoprincipaltrabajo;

            $scope.junta_setnombre = response[0].terreno.derivacion.canal.calle.barrio.nombrebarrio;
            $scope.toma_setnombre = response[0].terreno.derivacion.canal.calle.nombrecalle;
            $scope.canal_setnombre = response[0].terreno.derivacion.canal.nombrecanal;
            $scope.derivacion_setnombre = response[0].terreno.derivacion.nombrederivacion;
            $scope.cultivo_setnombre = response[0].terreno.cultivo.nombrecultivo;
            $scope.area_setnombre = response[0].terreno.area;
            $scope.caudal_setnombre = response[0].terreno.caudal;

            $scope.t_observacion_setnombre = response[0].observacion;

            $('#modalProcesarSetNombre').modal('hide');

            $('#modalActionSetNombre').modal('show');
        });

    };

    $scope.saveSolicitudSetName = function () {

        var solicitud = {
            fecha_solicitud: $scope.t_fecha_setnombre,
            codigocliente_new: $scope.h_new_codigocliente_setnombre,
            codigocliente_old: $scope.h_codigocliente_setnombre,
            idterreno: $scope.t_terrenos_setnombre,
            observacion: $scope.t_observacion_setnombre
        };

        $http.put(API_URL + 'solicitud/updateSolicitudSetName/' + $scope.num_solicitud_setnombre, solicitud).success(function(response){

            if(response.success == true){
                $scope.initLoad();
                //$('#modalActionSetNombre').modal('hide');

                $scope.message = 'Se ha actualizado la solicitud correctamente...';
                $('#modalMessage').modal('show');
            }

        });

    };

    $scope.procesarSolicitudSetN = function () {
        var url = API_URL + 'solicitud/processSolicitudSetName/' + $scope.idsolicitud;

        var data = {
            idsolicitud: $scope.idsolicitud
        };

        $http.put(url, data ).success(function (response) {
            $scope.initLoad();

            $scope.idsolicitud = 0;
            $('#modalActionSetNombre').modal('hide');
            $scope.message = 'Se procesó correctamente la solicitud seleccionada...';
            $('#modalMessage').modal('show');

        }).error(function (res) {

        });
    };


    $scope.showSolicitudFraccion = function () {
        var url = API_URL + 'solicitud/getSolicitudFraccion/' + $scope.idsolicitud;

        $http.get(url).success(function(response){

            console.log(response);

            $scope.getTerrenosFraccionByCliente(response[0].codigocliente, response[0].idterreno);

            $scope.getIdentifyClientesFraccion(response[0].codigocliente, response[0].codigonuevocliente);

            $scope.h_codigocliente_fraccion = response[0].codigocliente;
            $scope.h_new_codigocliente_fraccion = response[0].codigonuevocliente;

            $scope.num_solicitud_fraccion = response[0].idsolicitudreparticion;
            $scope.t_fecha_fraccion = response[0].fechasolicitud;
            $scope.documentoidentidad_cliente_fraccion = response[0].cliente.documentoidentidad;
            $scope.nom_cliente_fraccion = response[0].cliente.apellido + ' ' + response[0].cliente.nombre;
            $scope.direcc_cliente_fraccion = response[0].cliente.direcciondomicilio;
            $scope.telf_cliente_fraccion = response[0].cliente.telefonoprincipaldomicilio;
            $scope.celular_cliente_fraccion = response[0].cliente.celular;
            $scope.telf_trab_cliente_fraccion = response[0].cliente.telefonoprincipaltrabajo;

            $scope.junta_fraccion = response[0].terreno.derivacion.canal.calle.barrio.nombrebarrio;
            $scope.toma_fraccion = response[0].terreno.derivacion.canal.calle.nombrecalle;
            $scope.canal_fraccion = response[0].terreno.derivacion.canal.nombrecanal;
            $scope.derivacion_fraccion = response[0].terreno.derivacion.nombrederivacion;
            $scope.cultivo_fraccion = response[0].terreno.cultivo.nombrecultivo;
            $scope.area_fraccion = response[0].terreno.area;
            $scope.caudal_fraccion = response[0].terreno.caudal;

            $scope.valor_fraccion = response[0].terreno.valoranual;

            $scope.t_area_fraccion = response[0].nuevaarea;

            $scope.calculateFraccion();

            $scope.t_observacion_fraccion = response[0].observacion;

            $('#modalProcesarFraccion').modal('hide');

            $('#modalActionFraccion').modal('show');
        });

    };

    $scope.saveSolicitudFraccion = function () {

        var solicitud = {
            codigocliente_new: $scope.h_new_codigocliente_fraccion,
            codigocliente_old: $scope.h_codigocliente_fraccion,
            idterreno: $scope.t_terrenos_fraccion,
            observacion: $scope.t_observacion_fraccion,
            fecha_solicitud: $scope.t_fecha_fraccion,
            area: $scope.t_area_fraccion,
            caudal: $scope.caudal_new_fraccion,
            valoranual: $scope.valor_new_fraccion,
        };

        console.log(solicitud);

        $http.put(API_URL + 'solicitud/updateSolicitudFraccion/' + $scope.num_solicitud_fraccion, solicitud).success(function(response){

            if(response.success == true){
                $scope.initLoad();
                //$('#modalActionRiego').modal('hide');

                $scope.message = 'Se ha actualizado la solicitud correctamente...';
                $('#modalMessage').modal('show');
            }

        });

    };

    $scope.procesarSolicitudFraccion = function () {
        var url = API_URL + 'solicitud/processSolicitudFraccion/' + $scope.idsolicitud;

        var data = {
            idsolicitud: $scope.idsolicitud
        };

        $http.put(url, data ).success(function (response) {
            $scope.initLoad();

            $scope.idsolicitud = 0;
            $('#modalActionFraccion').modal('hide');
            $scope.message = 'Se procesó correctamente la solicitud seleccionada...';
            $('#modalMessage').modal('show');

        }).error(function (res) {

        });
    };

    $scope.info = function (solicitud) {
        if(solicitud.tipo == 'Otra Solicitud') {
            $scope.no_info_otro = solicitud.no_solicitud;
            $scope.ingresada_info_otro = convertDatetoDB(solicitud.fecha, true);
            $scope.procesada_info_otro = convertDatetoDB(solicitud.fechaprocesada, true);
            $scope.cliente_info_otro = solicitud.cliente;
            $scope.descripcion_info_otro = solicitud.descripcion;
            $('#modalInfoSolOtros').modal('show');
        }

        if(solicitud.tipo == 'Riego') {
            $scope.no_info_riego = solicitud.no_solicitud;
            $scope.ingresada_info_riego = convertDatetoDB(solicitud.fecha, true);
            $scope.procesada_info_riego = convertDatetoDB(solicitud.fechaprocesada, true);
            $scope.cliente_info_riego = solicitud.cliente;

            $scope.noterreno_info_riego = solicitud.terreno.idterreno;
            $scope.area_info_riego = solicitud.terreno.area;

            $scope.junta_info_riego = solicitud.terreno.derivacion.canal.calle.barrio.nombrebarrio;
            $scope.toma_info_riego = solicitud.terreno.derivacion.canal.calle.nombrecalle;
            $scope.canal_info_riego = solicitud.terreno.derivacion.canal.nombrecanal;
            $scope.derivacion_info_riego = solicitud.terreno.derivacion.nombrederivacion;

            $('#modalInfoSolRiego').modal('show');
        }

        if(solicitud.tipo == 'Repartición') {
            $scope.no_info_fraccion = solicitud.no_solicitud;
            $scope.ingresada_info_fraccion = convertDatetoDB(solicitud.fecha, true);
            $scope.procesada_info_fraccion = convertDatetoDB(solicitud.fechaprocesada, true);
            $scope.cliente_info_fraccion = solicitud.cliente;
            $scope.area_info_fraccion = solicitud.areanueva;

            $scope.searchOtherClient('reparticion', solicitud.othercliente);

        }

        if(solicitud.tipo == 'Cambio de Nombre') {
            $scope.no_info_setN = solicitud.no_solicitud;
            $scope.ingresada_info_setN = convertDatetoDB(solicitud.fecha, true);
            $scope.procesada_info_setN = convertDatetoDB(solicitud.fechaprocesada, true);
            $scope.cliente_info_setN = solicitud.cliente;

            $scope.noterreno_info_setN = solicitud.terreno.idterreno;
            $scope.area_info_setN = solicitud.terreno.area;

            $scope.junta_info_setN = solicitud.terreno.derivacion.canal.calle.barrio.nombrebarrio;
            $scope.toma_info_setN = solicitud.terreno.derivacion.canal.calle.nombrecalle;
            $scope.canal_info_setN = solicitud.terreno.derivacion.canal.nombrecanal;
            $scope.derivacion_info_setN = solicitud.terreno.derivacion.nombrederivacion;

            $scope.searchOtherClient('setname', solicitud.othercliente);


        }
    };

    $scope.searchOtherClient = function (type, idcliente) {
        $http.get(API_URL + 'solicitud/getIdentifyCliente/' + idcliente).success(function(response){
            console.log(response);
            if (type == 'setname'){
                $scope.cliente_a_info_setN = response[0].apellido + ' ' + response[0].nombre;
                $('#modalInfoSolSetName').modal('show');
            } else {
                $scope.cliente_a_info_fraccion = response[0].apellido + ' ' + response[0].nombre;
                $('#modalInfoSolFraccion').modal('show');
            }
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
