

app.controller('puntoventaController', function($scope, $http, API_URL) {

    $scope.puntoventa = [];
    $scope.idpuntoventa_del = 0;
    $scope.modalstate = '';
    $scope.confirmacion=false;

     $scope.bloquearGuardar = function(){
       //document.formpuntoventa.guardar.disabled=true;
    };


    $scope.initLoad = function(){

        $http.get(API_URL + 'puntoventa/getExistEstablecimiento').success(function(response){

            if (response.success === true) {
                $('#info_alert').hide();
                $('#btn_add').prop('disabled', false);
                $http.get(API_URL + 'puntoventa/getpuntoventas').success(function(response){
                    $scope.puntoventas = response;
                });

            } else {
                $('#info_alert').show();
                $('#btn_add').prop('disabled', true);
            }

        });


    };

    $scope.verificarEmision = function(){
        $http.get(API_URL + 'puntoventa/verificaremision/'+$scope.codigo).success(function(response){
            if (response.length!=0) {
                $scope.confirmacion=true;
            }else{
                $scope.confirmacion=false;
            }
        });
        console.log($scope.confirmacion);
    };

    $scope.calculateLength = function(field, length) {
                        var text = $("#" + field).val();
                        var longitud = text.length;
                        if (longitud == length) {
                            $("#" + field).val(text);
                            return text;
                        } else {
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
                            $("#" + field).val(relleno + text);
                            console.log(relleno+text);
                            return relleno+text;
                        }
                    };

    $scope.onlyNumber = function ($event, length, field) {

        if (length != undefined) {
            var valor = $('#' + field).val();
            if (valor.length == length) $event.preventDefault();
        }
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
            console.log(field);
        };

    $scope.toggle = function(modalstate, id) {
        $scope.modalstate = modalstate;
        switch (modalstate) {
            case 'add':
                $scope.$broadcast('angucomplete-alt:clearInput');
                $scope.form_title = "Nuevo Punto Venta";
                $scope.codigo = '';

                $http.get(API_URL + 'puntoventa/verificarvacio').success(function(response){
                    //console.log(response);

                    if (parseInt(response) === 0) {
                        $scope.message="Para crear un Punto de Venta primero debe crear al menos un Empleado.";
                        $('#modalEmpleadoVacio').modal('show');

                    }else{
                        $http.get(API_URL + 'puntoventa/cargaestablecimiento').success(function(response) {
                            $scope.establecimiento=response[0].razonsocial;
                            $('#modalActionPuntoventa').modal('show');
                        });
                    }

                });
                break;
            case 'edit':
                $scope.form_title = "Editar Punto Venta";
                $scope.idc = id;
                console.log('editar');
                $http.get(API_URL + 'puntoventa/cargarpuntoventa/' + id).success(function(response) {
                    $scope.codigo=response[0].codigoptoemision;
                    $scope.establecimiento=response[0].razonsocial;
                    $scope.empleado=response[0].numdocidentific+' '+response[0].namepersona+' '+response[0].lastnamepersona;
                    $('#modalActionPuntoventa').modal('show');
                });
                    break;
            default:
                break;
        }
    };

    $scope.Save = function (){
        console.log($scope.modalstate);
        if ($scope.Empleado== undefined) {
            var data = {
            codigoemision: $scope.codigo,
            identificacionempleado:0
            };
        } else {
            var data = {
            codigoemision: $scope.codigo,
            identificacionempleado: $scope.Empleado.originalObject.numdocidentific
            };
        }
       

        console.log(data);

        switch ( $scope.modalstate) {
            case 'add':
                $http.post(API_URL + 'puntoventa', data ).success(function (response) {
                    if (response.success == true) {
                        $scope.initLoad();
                        $('#modalActionPuntoventa').modal('hide');
                        $scope.message = 'Se insertó correctamente el punto de venta...';
                        $('#modalMessage').modal('show');
                        $scope.hideModalMessage();
                    }
                    else {
                        $('#modalActionPuntoventa').modal('hide');
                        $scope.message_error = 'Ya existe ese puntoventa...';
                        $('#modalMessageError').modal('show');
                    }
                });
                break;
            case 'edit':
                $http.put(API_URL + 'puntoventa/'+ $scope.idc, data ).success(function (response) {
                    $scope.initLoad();
                    $('#modalActionPuntoventa').modal('hide');
                    $scope.message = 'Se editó correctamente el puntoventa seleccionado';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }).error(function (res) {

                });
                break;
        }
    };

    $scope.showModalConfirm = function (idpuntoventa) {
        $scope.idpuntoventa_del = idpuntoventa;
        $('#modalConfirmDelete').modal('show');
    };

    $scope.delete = function(){
        $http.delete(API_URL + 'puntoventa/' + $scope.idpuntoventa_del).success(function(response) {
            if(response.success == true){
                $scope.initLoad();
                $('#modalConfirmDelete').modal('hide');
                $scope.idpuntoventa_del = 0;
                $scope.message = 'Se eliminó correctamente el puntoventa seleccionado...';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();

            } else {
                $scope.message_error = 'El puntoventa no puede ser eliminado porque esta asignado a un colaborador...';
                $('#modalMessageError').modal('show');
                $('#modalConfirmDelete').modal('hide');
            }
        });

    };

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };


    $scope.initLoad();
});
