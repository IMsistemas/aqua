

app.controller('suministrosController', function($scope, $http, API_URL) {


    $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };

    $scope.initLoad = function (pageNumber) {
        $http.get(API_URL + 'suministros/getsuministros?page=' + pageNumber).success(function (response) {
            //console.log(response);

            //$scope.suministros = response;

            $scope.suministros = response.data;
            $scope.totalItems = response.total;
        });
    };

    $scope.numFactura = function (suministro) {
        if (suministro.cont_documentoventa != null) {
            var establecimiento = (suministro.cont_documentoventa.cont_puntoventa.sri_establecimiento.ruc).split('-')[0];
            var ptoemision = suministro.cont_documentoventa.cont_puntoventa.codigoptoemision;
            var secuencial = suministro.cont_documentoventa.iddocumentoventa;

            var num_factura = establecimiento + '-' + ptoemision + '-' + $scope.calculateLength(String(secuencial), 9);

            return num_factura;
        } else {
            return '';
        }
    };

    $scope.Filtro = function () {
        $http.get(API_URL + 'calle/getBarrio').success(function (response) {
            var longitud = response.length;
            var array_temp = [{label: '-- Todos --', id: 0}];
            for (var i = 0; i < longitud; i++) {
                array_temp.push({label: response[i].namebarrio, id: response[i].idbarrio})
            }
            $scope.zonass = array_temp;
            $scope.s_zona = 0;
        });
        $scope.transversaless = [{label: '-- Todos --', id: 0}];
        $scope.s_transversales = 0;
    };

    $scope.FiltrarPorBarrio = function (){
        $http.get(API_URL + 'suministros/getCallesByBarrio/'+ $scope.s_zona).success(function (response) {
           // console.log(response);
            var longitud = response.length;
            var array_temp = [{label: '-- Todos --', id: 0}];
            for (var i = 0; i < longitud; i++) {
                array_temp.push({label: response[i].namecalle, id: response[i].idcalle})
            }
            $scope.transversaless = array_temp;
            $scope.s_transversales = 0;
        });
        $scope.aux = 0;
        $scope.aux = $scope.s_zona;
        if($scope.aux > 0)
        {
        $http.get(API_URL + 'suministros/getSuministrosByBarrio/'+ $scope.aux).success(function(response) {
            console.log(response);
            var longitud = response.length;
            for (var i = 0; i < longitud; i++) {
                var complete_name = {
                    value: response[i].cliente.nombres + ' ' + response[i].cliente.apellidos,
                    writable: true,
                    enumerable: true,
                    configurable: true
                };
                Object.defineProperty(response[i].cliente, 'complete_name', complete_name);
            }
            $scope.suministros = response;


            });
        }
        else {

            $scope.initLoad(1);
        }
    };

    $scope.FiltrarPorCalle = function (){

        $scope.aux1 = $scope.s_zona;
        $scope.aux2 = $scope.s_transversales;

        if($scope.aux2 > 0)
        {
            $http.get(API_URL + 'suministros/getSuministrosByCalle/'+ $scope.s_transversales).success(function(response) {
                console.log(response);
                var longitud = response.length;
                for (var i = 0; i < longitud; i++) {
                    var complete_name = {
                        value: response[i].cliente.nombres + ' ' + response[i].cliente.apellidos,
                        writable: true,
                        enumerable: true,
                        configurable: true
                    };
                    Object.defineProperty(response[i].cliente, 'complete_name', complete_name);
                }
                $scope.suministros = response;

            });
        }
        else {

            $scope.FiltrarPorBarrio();
        }
    };

    $scope.getSuministro = function (numeroSuministro) {
        $http.get(API_URL + 'suministros/suministroById/' + numeroSuministro).success(function (response) {

            $scope.nombre_apellido = response[0].cliente.persona.razonsocial;
            $scope.numerosuministro = response[0].idsuministro;
            $scope.fechainstalacionsuministro = response[0].fechainstalacion;
            $scope.zona = response[0].calle.barrio.namebarrio;
            $scope.direccionsumnistro = response[0].direccionsumnistro;
            $scope.telefonosuministro = response[0].telefonosuministro;
            $scope.transversal = response[0].calle.namecalle;

            $('#modalVerSuministro').modal('show');

        }).error(function (response) {
            $scope.mensajeError = "Error al cargar el suministro";
            $('#modalError').modal('show');
        });
    };

    $scope.modalEditarSuministro = function (object) {

        $http.get(API_URL + 'cliente/getBarrios').success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].namebarrio, id: response[i].idbarrio})
            }
            $scope.barrios = array_temp;
            $scope.s_suministro_zona = object.calle.idbarrio;

        });

        $http.get(API_URL + 'cliente/getCalles/' + object.calle.idbarrio).success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].namecalle, id: response[i].idcalle})
            }
            $scope.calles = array_temp;
            $scope.s_suministro_transversal = object.calle.idcalle;

            $scope.t_ruc = object.cliente.persona.numdocidentific;
            $scope.t_cliente = object.cliente.persona.razonsocial;

            $scope.t_suministro_telf = object.telefonosuministro;
            $scope.t_suministro_direccion = object.direccionsumnistro;

            $scope.idsuministro = object.idsuministro;
            $scope.fechainstalacionsuministro = object.fechainstalacion;

            $('#editar-suministro').modal('show');

        });

    };

    $scope.editarSuministro = function () {
        var data = {
            idcalle: $scope.s_suministro_transversal,
            direccionsuministro: $scope.t_suministro_direccion,
            telefonosuministro: $scope.t_suministro_telf
        };

        $http.put(API_URL + 'suministros/' + $scope.idsuministro, data).success(function (response) {
            $scope.initLoad(1);
            $('#editar-suministro').modal('hide');
            $scope.message = 'Se editó correctamente el Suministro seleccionado...';
            $('#modalConfirmacion').modal('show');
            $scope.hideModalMessage();
        });
    };

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalConfirmacion').modal('hide')", 3000);
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

        if (idbarrio != 0 && idbarrio != '' && idbarrio != undefined) {
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
            $scope.calles = [{label: '-- Seleccione --', id: ''}];
            $scope.s_suministro_transversal = '';
        }
    };

    $scope.getByFilter = function (aux) {
        if(aux==1) {
            $http.get(API_URL + 'suministros/getCallesByBarrio/' + $scope.s_zona).success(function (response) {
                // console.log(response);
                var longitud = response.length;
                var array_temp = [{label: '-- Todos --', id: 0}];
                for (var i = 0; i < longitud; i++) {
                    array_temp.push({label: response[i].namecalle, id: response[i].idcalle})
                }
                $scope.transversaless = array_temp;
                $scope.s_transversales = 0;
            });
        }
        $scope.aux = 0;
        $scope.aux = $scope.s_zona;
        if($scope.aux > 0)
        {

        var filter = {
            barrio: $scope.s_zona,
            calle: $scope.s_transversales
        };

        $http.get(API_URL + 'suministros/getSuministrosByBarrio/' + JSON.stringify(filter)).success(function (response) {
            console.log(response);

           var longitud = response.length;

            var temp = [];

            for (var i = 0; i < longitud; i++) {
                if (response[i].calle != null) {
                    if (response[i].calle.barrio != null) {
                        temp.push(response[i]);
                    }
                }
            }


            var longitud = temp.length;
            for (var i = 0; i < longitud; i++) {
                var complete_name = {
                    value: temp[i].cliente.nombres + ' ' + temp[i].cliente.apellidos,
                    writable: true,
                    enumerable: true,
                    configurable: true
                };
                Object.defineProperty(temp[i].cliente, 'complete_name', complete_name);
            }
            $scope.suministros = temp;

        });
        }
        else {

            $scope.initLoad(1);
        }
    };

    $scope.loadViewFactura = function (idsuministro) {

        /*$( "#bodyfactura" ).load( "DocumentoVenta", function( response, status, xhr ) {
            if ( status == "error" ) {
                var msg = "Sorry but there was an error: ";
                $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
            }
        });*/


        //console.log(API_URL + 'suministros/getSuministroForFactura/' + idsuministro);

        $http.get(API_URL + 'suministros/getSuministroForFactura/' + idsuministro).success(function(response){

            $scope.currentProjectUrl = '';

            $scope.currentProjectUrl = API_URL + 'DocumentoVenta?flag_suministro=1';
            $("#aux_venta").html("<object height='450px' width='100%' data='"+$scope.currentProjectUrl+"'></object>");
            $('#modalFactura').modal('show');
        });

    };

    $scope.calculateLength = function(text, length) {

        var longitud = text.length;
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
        return relleno + text;
    };

    $scope.initLoad(1);
    $scope.Filtro();

    /*
    -------------------------------------------------------------------------------------------------------------------
     */


});