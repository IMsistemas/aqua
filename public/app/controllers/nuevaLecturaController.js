

    app.controller('nuevaLecturaController', function($scope, $http, API_URL) {

        $scope.rubros = [];

        $scope.initData = function(){

            $scope.createTableRubros();

            $http.get(API_URL + 'nuevaLectura/lastId').success(function(response){

                var now = new Date();
                var dd = now.getDate();
                if (dd < 10) dd = '0' + dd;
                var mm = now.getMonth() + 1;
                if (mm < 10) mm = '0' + mm;
                var yyyy = now.getFullYear();

                $scope.t_fecha_ing = dd + "\/" + mm + "\/" + yyyy;
                $scope.s_anno = yyyy;
                $scope.s_mes = mm;
                $scope.t_no_lectura = response.lastID;

            });

        }

        $scope.loadInfo = function(){
            var id = $scope.t_no_suministro;

            $http.get(API_URL + 'nuevaLectura/' + id).success(function(response) {

                if (response.length == 0){

                    $scope.message = 'No exite registro del Número de Suministro Insertado...';
                    $('#modalMessage').modal('show');

                } else {

                    if(response[0].lecturaactual == null || response[0].lecturaactual == undefined){
                        response[0].lecturaactual = 0;
                    }

                    var lectura_anterior = parseFloat(response[0].lecturaactual);
                    var lectura_actual = parseFloat($scope.t_lectura);

                    if(lectura_anterior > lectura_actual){
                        $scope.lectura_anterior = lectura_anterior;
                        $scope.message = 'La Lectura Actual debe ser superior a la Anterior...';
                        $('#modalMessage').modal('show');

                    } else {

                        $scope.lectura_anterior = lectura_anterior;
                        $scope.lectura_actual = lectura_actual;

                        $scope.consumo = lectura_actual - lectura_anterior;


                        $scope.getValueRublos($scope.consumo, response[0].idtarifa);

                        $scope.nombre_cliente = response[0].apellido + ' ' + response[0].nombre;
                        $scope.barrio = response[0].nombrebarrio;
                        $scope.calle = response[0].nombrecalle;
                        $scope.tarifa = response[0].nombretarifa;  
                              
                    }

                }

            });
        }

        $scope.createTableRubros = function(){

            $http.get(API_URL + 'nuevaLectura/getRubros').success(function(response) {

                var object_basico = {
                    idrubrofijo: 0,
                    nombrerubrofijo: "Consumo Tarifa Básica",
                    valorrubro: 0.00
                }

                var object_excedente = {
                    idrubrofijo: 0,
                    nombrerubrofijo: "Excedente",
                    valorrubro: 0.00
                }

                var object_mesesatrasados = {
                    idrubrofijo: 0,
                    nombrerubrofijo: "Valores Atrasados",
                    valorrubro: 0.00
                }

                $scope.rubros = response;

                var longitud = ($scope.rubros).length;

                for(var i = 0; i < longitud; i++){
                    ($scope.rubros)[i].valorrubro = 0;
                }

                ($scope.rubros).unshift(object_mesesatrasados);    
                ($scope.rubros).unshift(object_excedente);
                ($scope.rubros).unshift(object_basico);

                $scope.total = '$ 0.00';

            });
        }

        $scope.getValueRublos = function(consumo, tarifa){

            var id = $scope.t_no_suministro;

            var url = API_URL + 'nuevaLectura/getRubros/' + consumo + '/' + tarifa + '/' + id;

            $http.get(url).success(function(response) {

                console.log(response);

                $scope.rubros[0].valorrubro = parseFloat(response.tarifabasica).toFixed(2);
                $scope.rubros[1].valorrubro = (response.excedente).toFixed(2);
                $scope.rubros[2].valorrubro = response.mesesatrasados;
                $scope.rubros[3].valorrubro = parseFloat(response.medioambiente).toFixed(2);
                $scope.rubros[4].valorrubro = (response.alcantarillado).toFixed(2);
                $scope.rubros[5].valorrubro = (response.ddss).toFixed(2);

                $scope.meses_atrasados =  response.mesesatrasados;   

                var longitud = ($scope.rubros).length;
                var suma = 0;
                for(var i = 0; i < longitud; i++){
                    suma += parseFloat(($scope.rubros)[i].valorrubro);
                }

                $scope.total = suma;

                console.log($scope.rubros);

            });
        }

        $scope.confirmSave = function(){
            $('#modalConfirm').modal('show');
        }

        $scope.save = function(){

            $scope.lectura_data = {
                fechalectura: convertDatetoDB($scope.t_fecha_ing),
                numerosuministro: $scope.t_no_suministro,
                lecturaanterior: $scope.lectura_anterior,
                lecturaactual: $scope.lectura_actual,
                consumo: $scope.consumo,

                excedente: $scope.rubros[1].valorrubro,
                mesesatrasados: $scope.rubros[2].valorrubro,
                total: $scope.total,
            };

            var url = API_URL + "nuevaLectura";

            $http.post(url, $scope.lectura_data ).success(function (response) {

                console.log(response);

                //$scope.initLoad();
                $('#modalConfirm').modal('hide');
                //$scope.message = 'Se inserto correctamente el Empleado';
                //$('#modalMessage').modal('show');

            }).error(function (res) {
                console.log(res);
            });

        }

        $scope.initData();

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

    function isOnlyNumberPto(field, e, length) {
        var valor = document.getElementById(field.id);
        if (length != undefined) {
            if (valor.length == length) return false;
        }
        if (valor != undefined) {
            var k = (document.all) ? e.keyCode : e.which;
            if (k == 8 || k == 0) return true;
            var patron = /\d/;
            var n = String.fromCharCode(k);
            if (n == ".") {
                if (valor.indexOf('.') != -1 || valor.length < 0) {
                    return false;
                } else return true;
            } else return patron.test(n);
        }
    }