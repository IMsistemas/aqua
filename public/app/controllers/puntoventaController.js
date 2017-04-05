

app.controller('puntoventaController', function($scope, $http, API_URL) {

    $scope.puntoventa = [];
    $scope.idpuntoventa_del = 0;
    $scope.modalstate = '';


    $scope.initLoad = function(){
        $http.get(API_URL + 'puntoventa/getpuntoventas').success(function(response){
            $scope.puntoventas = response;
        });
    };
    $scope.initLoad();
    $scope.verificarEmision = function(){
        $http.get(API_URL + 'puntoventa/verificaremision/'+$scope.codigo).success(function(response){
            if (response!=null) {
                $scope.confirmacion=true;
            }else{
                $scope.confirmacion=false;
            }
        });
    };

    $scope.calculateLength = function(field, length) {
                        var text = $("#" + field).val();
                        var longitud = text.length;
                        if (longitud == length) {
                            $("#" + field).val(text);
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
                $http.get(API_URL + 'puntoventa/cargaestablecimiento').success(function(response) {
                    console.log(response);
                    console.log(API_URL + 'puntoventa/cargaestablecimiento');
                    $scope.establecimiento=response.razonsocial;
                    $('#modalActionPuntoventa').modal('show');
                });
                break;
            case 'edit':

                $scope.form_title = "Editar puntoventa";
                $scope.idc = id;

                $http.get(API_URL + 'puntoventa/getpuntoventaByID/' + id).success(function(response) {
                    $scope.nombrepuntoventa = response[0].namepuntoventa.trim();
                    $('#modalActionpuntoventa').modal('show');
                });
                    break;
            default:
                break;
        }
    };

    $scope.Save = function (){

        var data = {
            nombrepuntoventa: $scope.nombrepuntoventa
        };

        switch ( $scope.modalstate) {
            case 'add':
                $http.post(API_URL + 'puntoventa', data ).success(function (response) {
                    if (response.success == true) {
                        $scope.initLoad();
                        $('#modalActionpuntoventa').modal('hide');
                        $scope.message = 'Se insertó correctamente el puntoventa...';
                        $('#modalMessage').modal('show');
                        $scope.hideModalMessage();
                    }
                    else {
                        $('#modalActionpuntoventa').modal('hide');
                        $scope.message_error = 'Ya existe ese puntoventa...';
                        $('#modalMessageError').modal('show');
                    }
                });
                break;
            case 'edit':
                $http.put(API_URL + 'puntoventa/'+ $scope.idc, data ).success(function (response) {
                    $scope.initLoad();
                    $('#modalActionpuntoventa').modal('hide');
                    $scope.message = 'Se editó correctamente el puntoventa seleccionado';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }).error(function (res) {

                });
                break;
        }
    };

    $scope.showModalConfirm = function (puntoventa) {
        $scope.idpuntoventa_del = puntoventa.idpuntoventa;
        $scope.puntoventa_seleccionado = puntoventa.namepuntoventa;
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
