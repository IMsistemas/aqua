

    app.controller('nuevaLecturaController', function($scope, $http, API_URL) {

        $scope.meses = [
            { id: 0, name: '-- Seleccione --' },
            { id: 1, name: 'Enero' },
            { id: 2, name: 'Febrero' },
            { id: 3, name: 'Marzo' },
            { id: 4, name: 'Abril' },
            { id: 5, name: 'Mayo' },
            { id: 6, name: 'Junio' },
            { id: 7, name: 'Julio' },
            { id: 8, name: 'Agosto' },
            { id: 9, name: 'Septiembre' },
            { id: 10, name: 'Octubre' },
            { id: 11, name: 'Noviembre' },
            { id: 12, name: 'Diciembre' }
        ];

        $scope.rubros = [];
        $scope.excedente = 0;
        $scope.valormesesatrasados = 0;

        $scope.initData = function(){

            //$scope.createTableRubros();

            $http.get(API_URL + 'nuevaLectura/lastId').success(function(response){

                var now = new Date();
                var dd = now.getDate();
                if (dd < 10) dd = '0' + dd;
                var mm = now.getMonth() + 1;
                //if (mm < 10) mm = '0' + mm;
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

        };

        $scope.loadInfo = function(){

            var filter = {
                id: $scope.t_no_suministro,
                month: $scope.s_mes,
                year: $scope.s_anno
            };

            $http.get(API_URL + 'nuevaLectura/getInfo/' + JSON.stringify(filter)).success(function(response) {
                console.log(response);

                if (response.success == true){
                    if (response.suministro.length == 0){
                        $scope.message = 'No exite registro del Número de Suministro Insertado...';
                        $('#modalMessage').modal('show');
                    } else {
                        var lectura_anterior = 0;
                        var lectura_actual = 0;

                        if ((response.lectura).length == 0){
                            lectura_actual = $scope.t_lectura;
                        } else {
                            lectura_anterior = response.lectura[0].lecturaactual;
                            lectura_actual = $scope.t_lectura;
                        }

                        if(lectura_anterior > lectura_actual){

                            $scope.lectura_anterior = lectura_anterior;
                            $scope.message = 'La Lectura Actual debe ser superior a la Anterior...';
                            $('#modalMessage').modal('show');

                        } else {

                            $scope.lectura_anterior = lectura_anterior;
                            $scope.lectura_actual = lectura_actual;
                            $scope.consumo = parseInt(lectura_actual) - lectura_anterior;

                            $scope.getValueRublos($scope.consumo, response.suministro[0].aguapotable.idtarifaaguapotable);

                            $scope.nombre_cliente = response.suministro[0].cliente.apellidos + ', ' + response.suministro[0].cliente.nombres;
                            $scope.barrio = (response.suministro[0].calle.barrio.nombrebarrio).trim();
                            $scope.calle = (response.suministro[0].calle.nombrecalle).trim();
                            $scope.tarifa = response.suministro[0].aguapotable.nombretarifaaguapotable;

                            $('#btn_export_pdf').prop('disabled', false);
                            $('#btn_print_pdf').prop('disabled', false);
                            $('#btn_save').prop('disabled', false);

                        }

                    }
                } else {
                    $scope.message = 'Ya se ha realizado lectura al Nro. de Suministro seleccionado en el periodo...';
                    $('#modalMessage').modal('show');
                }

            });
        };

        /*$scope.createTableRubros = function(){

            $http.get(API_URL + 'nuevaLectura/getRubros').success(function(response) {

                $scope.rubros = response;

                $scope.total = '$ 0.00';

            });
        }*/

        $scope.getValueRublos = function(consumo, tarifa){
            var id = $scope.t_no_suministro;
            var url = API_URL + 'nuevaLectura/calculate/' + consumo + '/' + tarifa + '/' + id;

            $http.get(url).success(function(response) {
                console.log(response);
                $scope.meses_atrasados =  response.cant_meses_atrasados;
                $scope.rubros = response.value_tarifas;
                $scope.excedente = response.excedente;
                $scope.valormesesatrasados = response.valor_meses_atrasados;

                var longitud = ($scope.rubros).length;
                var suma = 0;
                for(var i = 0; i < longitud; i++){
                    suma += parseFloat(($scope.rubros)[i].valor);
                }
                $scope.total = suma.toFixed(2);
            });
        }

        $scope.confirmSave = function(){
            $('#modalConfirm').modal('show');
        };

        $scope.save = function(){
            $('#modalConfirm').modal('hide');
            $('#myModalProgressBar').modal('show');

            /*var text_mes = '';
            for (var i = 0; i < 12; i++){
                if ($scope.meses[i].id == $scope.s_mes) {
                    text_mes = $scope.meses[i].name;
                }
            }*/

            /*var longitud = ($scope.rubros).length;

            var array_rubros = [];

            for (var i = 0; i < longitud; i++) {
                var object = {
                    nombrerubro: (($scope.rubros)[i].nombrerubro).trim(),
                    valorrubro: ($scope.rubros)[i].valorrubro,
                }
                array_rubros.push(object);
            }*/

            /*var filters = {
                 fecha: $scope.t_fecha_ing,
                 no_lectura: $scope.t_no_lectura,
                 anno: $scope.s_anno,
                 mes: text_mes,
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
             };*/


            var lectura_data = {
                fechalectura: convertDatetoDB($scope.t_fecha_ing),
                anno: $scope.s_anno,
                mes: $scope.s_mes,
                numerosuministro: $scope.t_no_suministro,
                lecturaanterior: $scope.lectura_anterior,
                lecturaactual: $scope.lectura_actual,
                consumo: $scope.consumo,
                excedente: $scope.excedente,
                valormesesatrasados: $scope.valormesesatrasados,
                mesesatrasados: parseInt($scope.meses_atrasados),
                total: $scope.total,

                /*rubros: $scope.rubros,

                pdf: JSON.stringify(filters),*/

            };

            var url = API_URL + "nuevaLectura";

            $http.post(url, lectura_data ).success(function (response) {

                $('#myModalProgressBar').modal('hide');
                $('#btn_save').prop('disabled', true);
                $('#btn_export_pdf').prop('disabled', true);
                $('#btn_print_pdf').prop('disabled', true);

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

                $scope.rubros = [];

                $scope.initData();

                $scope.message = 'Se guardó y envio el correo como adjunto satisfactoriamente la Lectura nueva...';
                $('#modalMessage').modal('show');

            }).error(function (res) {
                console.log(res);
            });

        };

        $scope.exportToPDF = function(type) {

            var longitud = ($scope.rubros).length;

            var array_rubros = [];

            for (var i = 0; i < longitud; i++) {
                var object = {
                    nombrerubro: (($scope.rubros)[i].nombrerubro).trim(),
                    valorrubro: ($scope.rubros)[i].valorrubro,
                }
                array_rubros.push(object);
            }

            var text_mes = '';
            for (var i = 0; i < 12; i++){
                if ($scope.meses[i].id == $scope.s_mes) {
                    text_mes = $scope.meses[i].name;
                }
            }

            var filters = {
                fecha: convertDatetoDB($scope.t_fecha_ing),
                no_lectura: $scope.t_no_lectura,
                anno: $scope.s_anno,
                mes: text_mes,
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
                rubros: array_rubros,
                script: 'window.print()'
            }

                var ventana = window.open('nuevaLectura/exportToPDF/' + type + '/' + JSON.stringify(filters));

                if (type == 2){
                    setTimeout(function(){ ventana.print(); }, 2000);
                }


        }

        $scope.initData();

    });

    $(function(){

        $('[data-toggle="tooltip"]').tooltip();

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