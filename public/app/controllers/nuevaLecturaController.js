

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
        $scope.tarifa_basica = 0;
        $scope.excedente = 0;
        $scope.valormesesatrasados = 0;

        $scope.Cliente = 0;
        $scope.Configuracion = '';
        $scope.ConfiguracionServicios = '';

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

                if (response.success == true) {
                    if (response.suministro.length == 0){
                        $scope.message = 'No existe registro del Número de Suministro Insertado...';
                        $('#modalMessage').modal('show');
                    } else {

                        $http.get(API_URL + 'nuevaLectura/getInfoClienteByID/'+ response.suministro[0].cliente.idcliente)
                            .success(function(response){

                                $scope.Cliente = response[0];
                                console.log($scope.Cliente);

                        });

                        $http.get(API_URL + 'nuevaLectura/getConfiguracionContable').success(function(response){

                                $scope.Configuracion = response;
                                console.log(response);

                                /*for(var x = 0; x < $scope.Configuracion.length; x++){

                                    if($scope.Configuracion[x].Descripcion === "CONT_IVA_VENTA"){
                                        if($scope.Configuracion[x].IdContable === null){
                                            $scope.Valida="1";
                                            QuitarClasesMensaje();
                                            $("#titulomsm").addClass("btn-danger");
                                            $("#msm").modal("show");
                                            $scope.Mensaje="La venta necesita la cuenta contable de IVA DE VENTA";
                                        }
                                    }
                                }
                                if(String($scope.Configuracion[0].IdContable)==""){
                                    QuitarClasesMensaje();
                                    $("#titulomsm").addClass("btn-danger");
                                    $("#msm").modal("show");
                                    $scope.Mensaje="La venta necesita que llene los campos de configuracion esten llenos para poder realizar esta transaccion";
                                }*/

                        });

                        $http.get(API_URL + 'nuevaLectura/getConfiguracionServicio').success(function(response){
                                $scope.ConfiguracionServicios = response;
                                console.log($scope.ConfiguracionServicios);
                        });

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

                            $scope.getValueRublos($scope.consumo, response.suministro[0].tarifaaguapotable.idtarifaaguapotable);

                            $scope.nombre_cliente = response.suministro[0].cliente.persona.razonsocial;
                            $scope.barrio = response.suministro[0].calle.barrio.namebarrio;
                            $scope.calle = response.suministro[0].calle.namecalle;
                            $scope.tarifa = response.suministro[0].tarifaaguapotable.nametarifaaguapotable;

                            $('#btn_export_pdf').prop('disabled', false);
                            $('#btn_print_pdf').prop('disabled', false);
                            $('#btn_save').prop('disabled', false);

                        }

                    }
                } else {

                    if (response.flag == 'no_exists') {
                        $scope.message = 'No se ha creado el Cobro para este suministro en el periodo...';
                        $('#modalMessage').modal('show');
                    } else {
                        $scope.message = 'Ya se ha realizado lectura al Nro. de Suministro seleccionado en el periodo...';
                        $('#modalMessage').modal('show');
                    }


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
                $scope.meses_atrasados = response.cant_meses_atrasados;
                $scope.rubros = response.value_tarifas;
                $scope.excedente = response.excedente;
                $scope.tarifa_basica = response.tarifa_basica;
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

            var text_mes = '';
            for (var i = 1; i < 13; i++){
                if ($scope.meses[i].id == $scope.s_mes) {
                    text_mes = $scope.meses[i].name;
                }
            }

            /*var longitud = ($scope.rubros).length;

            var array_rubros = [];

            for (var i = 0; i < longitud; i++) {
                var object = {
                    nombrerubro: (($scope.rubros)[i].nombrerubro).trim(),
                    valorrubro: ($scope.rubros)[i].valorrubro,
                }
                array_rubros.push(object);
            }*/

            /*
             * --------------------------------- CONTABILIDAD ----------------------------------------------------------
             */

            var Transaccion = {
                fecha: convertDatetoDB($scope.t_fecha_ing),
                idtipotransaccion: 6,
                numcomprobante: 1,
                descripcion: 'Registro de Nueva Lectura'
            };

            //Asiento contable Partida doble 	ay123
            var RegistroC = [];


            //Asiento contable cliente -- el cliente por lo genearal es un activo entonces el cliente aumenta una deuda por el debe
            var cliente = {
                idplancuenta: $scope.Cliente.idplancuenta,
                concepto: $scope.Cliente.concepto,
                controlhaber: $scope.Cliente.controlhaber,
                tipocuenta: $scope.Cliente.tipocuenta,
                Debe: $scope.total,
                Haber: 0,
                Descipcion: ''
            };

            RegistroC.push(cliente);

            //--Ingreso del item servicio

            for (var x = 0; x < $scope.rubros.length; x++){

                for(var z = 0; z < $scope.ConfiguracionServicios.length; z++){

                    if ($scope.rubros[x].nombreservicio === 'Consumo Tarifa Básica' && $scope.ConfiguracionServicios[z].configuracion.optionname === 'SERV_TARIFAB_LECT') {

                        var itemproductoservicio = {
                            idplancuenta: $scope.ConfiguracionServicios[z].contabilidad[0].idplancuenta_ingreso,
                            concepto: $scope.ConfiguracionServicios[z].contabilidad[0].concepto,
                            controlhaber: $scope.ConfiguracionServicios[z].contabilidad[0].controlhaber,
                            tipocuenta: $scope.ConfiguracionServicios[z].contabilidad[0].tipocuenta,
                            Debe: 0,
                            Haber: (parseFloat($scope.rubros[x].valor)).toFixed(4),
                            Descipcion:''
                        };
                        RegistroC.push(itemproductoservicio);

                    } else if ($scope.rubros[x].nombreservicio === 'Excedente' && $scope.ConfiguracionServicios[z].configuracion.optionname === 'SERV_EXCED_LECT') {

                        var itemproductoservicio = {
                            idplancuenta: $scope.ConfiguracionServicios[z].contabilidad[0].idplancuenta_ingreso,
                            concepto: $scope.ConfiguracionServicios[z].contabilidad[0].concepto,
                            controlhaber: $scope.ConfiguracionServicios[z].contabilidad[0].controlhaber,
                            tipocuenta: $scope.ConfiguracionServicios[z].contabilidad[0].tipocuenta,
                            Debe: 0,
                            Haber: (parseFloat($scope.rubros[x].valor)).toFixed(4),
                            Descipcion:''
                        };
                        RegistroC.push(itemproductoservicio);

                    } else if ($scope.rubros[x].nombreservicio === 'ALCANTARILLADO' && $scope.ConfiguracionServicios[z].configuracion.optionname === 'SERV_ALCANT_LECT') {

                        var itemproductoservicio = {
                            idplancuenta: $scope.ConfiguracionServicios[z].contabilidad[0].idplancuenta_ingreso,
                            concepto: $scope.ConfiguracionServicios[z].contabilidad[0].concepto,
                            controlhaber: $scope.ConfiguracionServicios[z].contabilidad[0].controlhaber,
                            tipocuenta: $scope.ConfiguracionServicios[z].contabilidad[0].tipocuenta,
                            Debe: 0,
                            Haber: (parseFloat($scope.rubros[x].valor)).toFixed(4),
                            Descipcion:''
                        };
                        RegistroC.push(itemproductoservicio);

                    } else if ($scope.rubros[x].nombreservicio === 'RECOGIDA DESECHOS SOLIDOS' && $scope.ConfiguracionServicios[z].configuracion.optionname === 'SERV_RRDDSS_LECT') {

                        var itemproductoservicio = {
                            idplancuenta: $scope.ConfiguracionServicios[z].contabilidad[0].idplancuenta_ingreso,
                            concepto: $scope.ConfiguracionServicios[z].contabilidad[0].concepto,
                            controlhaber: $scope.ConfiguracionServicios[z].contabilidad[0].controlhaber,
                            tipocuenta: $scope.ConfiguracionServicios[z].contabilidad[0].tipocuenta,
                            Debe: 0,
                            Haber: (parseFloat($scope.rubros[x].valor)).toFixed(4),
                            Descipcion:''
                        };
                        RegistroC.push(itemproductoservicio);

                    } else if ($scope.rubros[x].nombreservicio === 'MEDIO AMBIENTE' && $scope.ConfiguracionServicios[z].configuracion.optionname === 'SERV_MEDAMB_LECT') {

                        var itemproductoservicio = {
                            idplancuenta: $scope.ConfiguracionServicios[z].contabilidad[0].idplancuenta_ingreso,
                            concepto: $scope.ConfiguracionServicios[z].contabilidad[0].concepto,
                            controlhaber: $scope.ConfiguracionServicios[z].contabilidad[0].controlhaber,
                            tipocuenta: $scope.ConfiguracionServicios[z].contabilidad[0].tipocuenta,
                            Debe: 0,
                            Haber: (parseFloat($scope.rubros[x].valor)).toFixed(4),
                            Descipcion:''
                        };
                        RegistroC.push(itemproductoservicio);

                    }

                }

            }


            //--Ingreso del item producto o servicio

            //-- ICE venta
            /*if(parseFloat($scope.ValICE)>0){
                var iceventa={};
                for(i=0;i<$scope.Configuracion.length;i++){
                    if($scope.Configuracion[i].Descripcion=="CONT_ICE_VENTA"){
                        var auxcosto=$scope.Configuracion[i].Contabilidad;
                        iceventa=auxcosto[0];
                    }
                }
                var ice={
                    idplancuenta: iceventa.idplancuenta,
                    concepto: iceventa.concepto,
                    controlhaber: iceventa.controlhaber,
                    tipocuenta: iceventa.tipocuenta,
                    Debe: 0,
                    Haber: parseFloat($scope.ValICE),
                    Descipcion: ''
                };
                RegistroC.push(ice);
            }*/
            //-- ICE venta


            //--Iva venta
            var ivaventa = {};

            for(var i = 0; i < $scope.Configuracion.length; i++){
                if($scope.Configuracion[i].Descripcion === "CONT_IVA_VENTA"){
                    var auxcosto = $scope.Configuracion[i].Contabilidad;
                    ivaventa = auxcosto[0];
                }
            }

            var iva = {
                idplancuenta: ivaventa.idplancuenta,
                concepto: ivaventa.concepto,
                controlhaber: ivaventa.controlhaber,
                tipocuenta: ivaventa.tipocuenta,
                Debe: 0,
                Haber: 0, //parseFloat($scope.ValIVA),
                Descipcion:''
            };

            RegistroC.push(iva);
            //--Iva venta

            var Contabilidad={
                transaccion: Transaccion,
                registro: RegistroC
            };

            var transaccion_venta_full={
                DataContabilidad: Contabilidad
            };

            /*var transaccionfactura = {
                datos: JSON.stringify(transaccion_venta_full)
            };*/

            /*
             * --------------------------------- FIN CONTABILIDAD ------------------------------------------------------
             */


            var filters = {
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
                 rubros: $scope.rubros
             };


            var lectura_data = {
                fechalectura: convertDatetoDB($scope.t_fecha_ing),
                anno: $scope.s_anno,
                mes: $scope.s_mes,
                numerosuministro: $scope.t_no_suministro,
                lecturaanterior: $scope.lectura_anterior,
                lecturaactual: $scope.lectura_actual,
                consumo: $scope.consumo,
                tarifa_basica: $scope.tarifa_basica,
                excedente: $scope.excedente,
                valormesesatrasados: $scope.valormesesatrasados,
                mesesatrasados: parseInt($scope.meses_atrasados),
                total: $scope.total,
                rubros: $scope.rubros,
                idcliente: $scope.Cliente.idcliente,
                contabilidad: JSON.stringify(transaccion_venta_full),

                pdf: JSON.stringify(filters)

            };

            //console.log(lectura_data);


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