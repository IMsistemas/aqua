

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

                $scope.lectura_anterior = 0;
                $scope.lectura_actual = 0;
                $scope.consumo = 0;

                $scope.meses_atrasados = 0;

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

                    $('#btn_export_pdf').prop('disabled', false);
                    $('#btn_save').prop('disabled', false);
                }

            });
        }

        $scope.createTableRubros = function(){

            $http.get(API_URL + 'nuevaLectura/getRubros').success(function(response) {

                $scope.rubros = response;

                $scope.total = '$ 0.00';

            });
        }

        $scope.getValueRublos = function(consumo, tarifa){

            var id = $scope.t_no_suministro;

            var url = API_URL + 'nuevaLectura/getRubros/' + consumo + '/' + tarifa + '/' + id;

            $http.get(url).success(function(response) {

                $scope.rubros = response[0];
                $scope.meses_atrasados =  response[1].mesesatrasados;

                var longitud = ($scope.rubros).length;
                var suma = 0;
                for(var i = 0; i < longitud; i++){
                    suma += parseFloat(($scope.rubros)[i].valorrubro);
                }

                $scope.total = suma;


            });
        }

        $scope.confirmSave = function(){
            $('#modalConfirm').modal('show');
        }

        $scope.save = function(){
            $('#modalConfirm').modal('hide');
            $('#myModalProgressBar').modal('show');

            var longitud = ($scope.rubros).length;

            var array_rubros = [];

            for (var i = 0; i < longitud; i++) {
                var object = {
                    nombrerubro: (($scope.rubros)[i].nombrerubro).trim(),
                    valorrubro: ($scope.rubros)[i].valorrubro,
                }
                array_rubros.push(object);
            }

            var filters = {
                fecha: $scope.t_fecha_ing,
                no_lectura: $scope.t_no_lectura,
                anno: $scope.s_anno,
                mes: $scope.s_mes,
                suministro: $scope.t_no_suministro,
                lectura: $scope.t_lectura,
                nombre_cliente: $scope.nombre_cliente,
                barrio: $scope.barrio,
                calle: $scope.calle,
                tarifa: $scope.tarifa,

                lectura_anterior: $scope.lectura_anterior,
                lectura_actual: $scope.lectura_actual,
                consumo: $scope.consumo,
                meses_atrasados: $scope.meses_atrasados,
                total: $scope.total,
                rubros: array_rubros
            }

            $scope.lectura_data = {
                fechalectura: convertDatetoDB($scope.t_fecha_ing),

                anno: $scope.s_anno,
                mes: $scope.s_mes,

                numerosuministro: $scope.t_no_suministro,
                lecturaanterior: $scope.lectura_anterior,
                lecturaactual: $scope.lectura_actual,
                consumo: $scope.consumo,

                excedente: $scope.rubros[1].valorrubro,
                mesesatrasados: $scope.rubros[2].valorrubro,
                total: $scope.total,

                pdf: JSON.stringify(filters),

            };

            var url = API_URL + "nuevaLectura";

            $http.post(url, $scope.lectura_data ).success(function (response) {

                $('#myModalProgressBar').modal('hide');
                $('#btn_save').prop('disabled', true);
                $('#btn_export_pdf').prop('disabled', true);


                $scope.t_no_suministro = '';
                $scope.t_lectura = '';
                $scope.nombre_cliente = '';
                $scope.barrio = '';
                $scope.calle = '';
                $scope.tarifa = '';
                $scope.lectura_anterior = '';
                $scope.lectura_actual = '';
                $scope.consumo = '';
                $scope.meses_atrasados = 0;
                $scope.total = '$0.00';

                $scope.initData();

                $scope.message = 'Se guardó y envio el correo como adjunto satisfactoriamente la Lectura nueva...';
                $('#modalMessage').modal('show');

            }).error(function (res) {
                console.log(res);
            });

        }

        $scope.exportToPDF = function () {

            var longitud = ($scope.rubros).length;

            var array_rubros = [];

            for (var i = 0; i < longitud; i++) {
                var object = {
                    nombrerubrofijo: (($scope.rubros)[i].nombrerubrofijo).trim(),
                    valorrubro: ($scope.rubros)[i].valorrubro,
                }
                array_rubros.push(object);
            }


            var filters = {
                fecha: convertDatetoDB($scope.t_fecha_ing),
                no_lectura: $scope.t_no_lectura,
                anno: $scope.s_anno,
                mes: $scope.s_mes,
                suministro: $scope.t_no_suministro,
                lectura: $scope.t_lectura,
                nombre_cliente: $scope.nombre_cliente,
                barrio: $scope.barrio,
                calle: $scope.calle,
                tarifa: $scope.tarifa,

                lectura_anterior: $scope.lectura_anterior,
                lectura_actual: $scope.lectura_actual,
                consumo: $scope.consumo,
                meses_atrasados: $scope.meses_atrasados,
                total: $scope.total,
                rubros: array_rubros
            }

           window.open('nuevaLectura/exportToPDF/' + JSON.stringify(filters));

            /*$http.get(API_URL + 'nuevaLectura/exportToPDF/' + JSON.stringify(filters)).success(function(response){
                //window.open('data:application/pdf;base64, ' + btoa(escape(encodeURIComponent( response ))));
            });*/

            /*$http.get(API_URL + 'verLectura/getByFilter/' + JSON.stringify(filters)).success(function(response){
                $scope.lecturas = response;
            });*/
        }

        $scope.initData();

    });

    $(function(){
        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'DD/MM/YYYY'
        });

        $('.datepicker_a').datetimepicker({
            locale: 'es',
            format: 'YYYY'
        });
    })

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