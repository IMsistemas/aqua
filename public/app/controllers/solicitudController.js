/*
 *Copyright © IMPACT MEDIA 2016
 *Codigo escrito por: Kevin Chicaiza
*/

/* Controlador AngularJS para el manejo de solicitudes sistema Aqua para "Juntas de Agua Potable" */

app.controller('solicitudController',
    function ($scope,$http,API_URL) {

        /*$scope.ManejoSolicitudes = function() {
            this.solicitud = {

            }

            this.solicitudes = "mickey mouse";

            this.cargarDatos = function(){
                 self = this;
                $http.get(API_URL+"suministros/solicitudes/solicitudes")
                    .success(
                        function (response) {
                            $scope.ManejoSolicitudes.solicitudes = response;
                           // console.log(self.solicitudes)
                        })

                    .error(
                        function(response){
                            
                        });
                    return self.solicitudes;
            }
        }

   */
        
         
        $scope.solicitudes=[];
        $scope.ahora = new Date();
        $scope.initLoad = function(){
            $http.get(API_URL+"suministros/solicitudes/solicitudes")
            .success(function (response) {
                $estado="true";
                $scope.estado=$estado;
                $scope.solicitudes = response;
                $scope.cantidadSolicitudes = $scope.solicitudes.length;
                var fecha = $scope.solicitudes.sort(function(a,b){
                    return (new Date(a.fechasolicitud) - new Date(b.fechasolicitud));
                });
            });
            }
        $scope.initLoad();
        $scope.ordenarColumna = 'estaprocesada';

    	$scope.modalVerSolicitud = function(id){
             $http.get(API_URL+"suministros/solicitudes/"+id)
            .success(function (response) {
                $scope.solicitud = response[0];
                $('#modalInfoSolicitud').modal('show');
            });

        }
        
        $scope.modalNuevaSolicitud = function(){
            $('#nueva-solicitud').modal('show');
        }

        $scope.modalEditarSolicitud = function(idsolicitud){
            $http.get(API_URL+"suministros/solicitudes/"+idsolicitud)
            .success(function (response) {
                $scope.solicitud = response[0];
                $('#editarSolicitud').modal('show');
            });
        }

        $scope.editarSolicitud = function(idsolicitud){
             var url = API_URL +"suministros/solicitudes/modificar/"+idsolicitud;   
             console.log($scope.solicitud); 
            $http({
                method: 'POST',
                url: url,
                data: $.param($scope.solicitud),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(response) {
                $scope.message = 'Solicitud modificada con exito';
                $('#editarSolicitud').modal('hide');
                $('#modalMessage').modal('show');
                 
            }).error(function(response) {
                $scope.messageError = 'Error al modificar solicitud';
               $('#modalMessageError').modal('show');           
            });   
        }

        $scope.generarPDF = function(idSolicitud){
            
            window.open(API_URL+"suministros/solicitudes/solicitudes/pdf/"+idSolicitud);
        }



        $scope.modalProcesaSolicitud = function(id){
            $http.get(API_URL+"suministros/solicitudes/"+id)
            .success(function (response) {

                $http.get(API_URL+"suministros/suministros")
            .success(function (response) {
                $scope.suministros = response;
                $scope.cantidadSuministros = $scope.suministros.length;
            });

            $http.get(API_URL+"tarifas/tarifas")
                .success(function (response) {
                    $scope.tarifas = response;
                });

             $http.get(API_URL+"barrios/gestion/concalles")
                .success(function (response) {
                    $scope.barrios = response;
                });

            $http.get(API_URL+"configuracion/configuracion")
                .success(function (response) {
                    $scope.configuracion = response[0];
                    $scope.nDividendos = [];
                    $scope.acometida = (parseInt($scope.configuracion.aaguapotable) + parseInt($scope.configuracion.alcantarillado));
                    for(var i = 1; i<=$scope.configuracion.dividendos; i++ ){
                        $scope.nDividendos[i] = i;
                    }
                    
                });

             $http.get(API_URL+"suministros/productos")
                .success(function (response) {
                    $scope.producto = response[0];
                });


                $scope.procesarSolicitud = response[0];
                $('#procesar-solicitud').modal('show');
            });
        }
 

        $scope.procesaSolicitud = function(id) {

            $scope.suministro.cliente = $scope.procesarSolicitud.cliente;
            $scope.suministro.direccionsuministro = $scope.procesarSolicitud.direccionsuministro;
            $scope.suministro.telefonosuministro = $scope.procesarSolicitud.telefonosuministro;
            $scope.suministro.producto = $scope.producto;

            var url = API_URL +"suministros/solicitudes/procesar/"+id;    
            $http({
                method: 'POST',
                url: url,
                data: $.param($scope.suministro),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(response) {
                 ingresarSuministro(id);
            }).error(function(response) {
                $scope.messageError = 'Error al procesar solicitud';
               $('#modalMessageError').modal('show');           
            });        
        }

        ingresarSuministro = function(id){
            var url = API_URL +"suministros/nuevo";    
             $http({
                method: 'POST',
                url: url,
                data: $.param($scope.suministro),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(response) {
                 $scope.initLoad();
                 ingresarCuentaPorCobrar(id);
                 ingresarCuentaPorPagar();
            }).error(function(response) {
                $scope.messageError = 'Error al ingresar el suministro';
                $('#modalMessage').modal('hide');
               $('#modalMessageError').modal('show');           
            }); 
        }

        ingresarCuentaPorCobrar = function(id){
            $http.get(API_URL+"configuracion/configuracion")
                .success(function (response) {
                    $scope.interes = response[0];
                });

            $http.get(API_URL+"suministros/suministros")
            .success(function (response) {
                 $scope.cuenta.numerosuministro = parseInt(response.length)+1;

            });

            if($scope.cuenta.costomedidor === undefined){
                $scope.cuenta.costomedidor = $scope.producto.costoproducto;
            }if($scope.cuenta.acometida === undefined){
                $scope.cuenta.acometida = $scope.acometida;
            }

            $scope.cuenta.idsolicitud = id 
            $scope.cuenta.documentoidentidad = $scope.suministro.cliente.documentoidentidad;
            console.info($scope.interes);
            $scope.cuenta.dividendos = $scope.meses;
            calcularCostoSolicitud($scope.cuenta.cuotainicial,$scope.cuenta.costomedidor,$scope.cuenta.acometida,$scope.cuenta.dividendos,0.10);

            console.log($scope.cuenta);
            var url = API_URL + "cuentascobrarcliente/ingresarcuenta";   
             $http({
                 method: 'POST',
                 url: url,
                 data: $.param($scope.cuenta),
                 headers: {'Content-Type': 'application/x-www-form-urlencoded'}
             }).success(function(response){
                

             }).error(function(response){
                 $scope.messageError = 'Error al ingresar cuenta por pagar';
                 $('#modalMessage').modal('hide');
                 $('#modalMessageError').modal('show');       
             });
        } 
        ingresarCuentaPorPagar = function(){
            
            if($scope.cuenta.valor === undefined){
                $scope.cuenta.valor = $scope.configuracion.garantiaaperturacalle;
            } 
            
            var url = API_URL + "cuentaspagarcliente/ingresarcuenta";   
            $http({
                method: 'POST',
                url: url,
                data: $.param($scope.cuenta),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(response){
                $scope.message = 'Solicitud procesada con exito';
                $('#procesar-solicitud').modal('hide');
                $('#modalMessage').modal('show');
            }).error(function(response){
                $scope.messageError = 'Error al ingresar cuenta por cobrar';
                $('#modalMessage').modal('hide');
                $('#modalMessageError').modal('show');       
            });
        }


            calcularCostoSolicitud = function(cuotaInicial, costomedidor,acometida, dividendos, interes){
                console.log(cuotaInicial,costomedidor,acometida,dividendos,interes);
                    cuotaInicial = parseFloat(cuotaInicial);
                    costomedidor = parseFloat(costomedidor);
                    acometida = parseFloat(acometida);
                    dividendos = parseInt(dividendos);
                    interes = parseFloat(interes);
                    console.log(cuotaInicial,costomedidor,acometida,dividendos,interes);
                    subTotal = (costomedidor + acometida) - cuotaInicial;
                    totalInteres = subTotal*interes;
                    total = subTotal+totalInteres;
                    cuota = total/dividendos;
                    $scope.cuenta.pagototal = total;
                    $scope.cuenta.pagoporcadadividendo = cuota;
            }


         
          $scope.guardarNuevoCliente = function() {
            var url = API_URL + "clientes/gestion/guardarcliente";    
            
            $http({
                method: 'POST',
                url: url,
                data: $.param($scope.solicitud.cliente),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(response) {
                 guardarSolicitud();
            }).error(function(response) {
                $scope.messageError = 'Error al ingresar el cliente';
               $('#modalMessageError').modal('show');           
            });
        }

        guardarSolicitud = function(){
            var url = API_URL + "suministros/solicitudes/nueva/solicitud";    
            
            $http({
                method: 'POST',
                url: url,
                data: $.param($scope.solicitud),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(response) {
                 $scope.initLoad();
                 $scope.message = 'Se ingreso correctamente la solicitud';
                 $('#modalMessage').modal('show');
                 $('#nueva-solicitud').modal('hide');

                 
            }).error(function(response) {
                $scope.messageError = 'Error al ingresar la solicitud';
                $('#modalMessage').modal('hide');
                $('#modalMessageError').modal('show');
            });
        }


        $scope.modalEliminarSolicitud = function(id){
            $scope.solicitudSeleccionada = id;
            $('#modalConfirmDelete').modal('show');
            
        }


        $scope.eliminarSolicitud = function(){
          $http({
                    method: 'POST',
                    url: API_URL + 'suministros/solicitudes/eliminar/' + $scope.solicitudSeleccionada,
                    data: $.param($scope.solicitudSeleccionada),
                }).success(function(data) {
                     $scope.initLoad();
                 $scope.message = 'Solicitud eliminada con exito';
                 $('#modalConfirmDelete').modal('hide');
                 $('#modalMessage').modal('show');
                        
                }).error(function(data) {
                    $scope.messageError = 'Error al eliminar a solicitud';
                    $('#modalMessageError').modal('show'); 
                      
                });
        }


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

});

/* $scope.modalNuevaSolicitudCliente = function(documento){
       $http.get(API_URL+'clientes/gestion/'+documento)
        .success(function (response) {
            $scope.clienteActual = response.data;
        });
        $('#nueva-solicitud-cliente').modal('show');
    }*/