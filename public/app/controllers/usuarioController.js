

app.controller('usuarioController', function($scope, $http, API_URL) {

    $scope.departamentos = [];
    $scope.idcargo_del = 0;
    $scope.idusuario = 0;
    $scope.modalstate = '';

    $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };

    $scope.initLoad = function(pageNumber){

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };

        $http.get(API_URL + 'usuario/getUsuarios?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).
        success(function(response){
            $scope.usuarios = response.data;
            $scope.totalItems = response.total;
        });
    };

    $scope.toggle = function(modalstate, id) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':

                $http.get(API_URL + 'usuario/getRoles').success(function(response){

                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].namerol, id: response[i].idrol})
                    }
                    $scope.roles = array_temp;
                    $scope.rol = '';

                    $http.get(API_URL + 'usuario/getEmpleados').success(function(response){

                        console.log(response);

                        var longitud_e = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud_e; i++){
                            array_temp.push({label: response[i].persona.lastnamepersona + ' ' + response[i].persona.namepersona, id: response[i].idempleado})
                        }
                        $scope.empleados = array_temp;
                        $scope.empleado = '';

                        $scope.usuario = '';
                        $scope.password = '';

                        $scope.form_title = "Nuevo Usuario";
                        $scope.nombrerol = '';
                        $('#modalActionCargo').modal('show');

                    });

                });

                break;
            case 'edit':

                $scope.form_title = "Editar Usuario";
                $scope.idusuario = id;


                $http.get(API_URL + 'usuario/getRoles').success(function(response){

                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].namerol, id: response[i].idrol})
                    }
                    $scope.roles = array_temp;
                    $scope.rol = '';

                    $http.get(API_URL + 'usuario/getEmpleados').success(function(response){

                        var longitud_e = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud_e; i++){
                            array_temp.push({label: response[i].persona.lastnamepersona + ' ' + response[i].persona.namepersona, id: response[i].idempleado})
                        }
                        $scope.empleados = array_temp;
                        $scope.empleado = '';

                        $http.get(API_URL + 'usuario/' + id).success(function(response){

                            $scope.rol = response.idrol;

                            if (response.idempleado !== null) {
                                $scope.empleado = response.idempleado;
                            }

                            $scope.usuario = response.usuario;
                            $scope.password = '';

                            $('#modalActionCargo').modal('show');

                        });

                    });

                });


                break;

            default:
                break;
        }
    };

    $scope.Save = function (){

        var empleado = null;

        if ($scope.empleado !== '') {
            empleado = $scope.empleado;
        }

        var data = {
            idrol: $scope.rol,
            usuario: $scope.usuario,
            password: $scope.password,
            idempleado: empleado
        };

        switch ( $scope.modalstate) {
            case 'add':
                $http.post(API_URL + 'usuario', data ).success(function (response) {

                    $('#modalActionCargo').modal('hide');

                    if (response.success == true) {
                        $scope.idusuario = 0;
                        $scope.initLoad(1);
                        $scope.message = 'Se insertó correctamente el Usuario...';
                        $('#modalMessage').modal('show');
                        $scope.hideModalMessage();
                    }
                    else {

                        if (response.exists !== undefined) {
                            $scope.message_error = 'Ya existe ese Usuario...';
                        } else {
                            $scope.message_error = 'Ha ocurrido un error..';
                        }

                        $('#modalMessageError').modal('show');
                    }
                });
                break;
            case 'edit':
                $http.put(API_URL + 'usuario/'+ $scope.idusuario, data ).success(function (response) {

                    if (response.success == true) {
                        $scope.initLoad(1);
                        $('#modalActionCargo').modal('hide');
                        $scope.message = 'Se editó correctamente el Usuario seleccionado';
                        $('#modalMessage').modal('show');
                    } else {
                        if (response.exists !== undefined) {
                            $scope.message_error = 'Ya existe ese Usuario registrado...';
                        } else {
                            $scope.message_error = 'Ha ocurrido un error al intentar editar el Usuario seleccionado...';
                        }
                        $('#modalMessageError').modal('show');
                    }

                    $scope.hideModalMessage();

                }).error(function (res) {

                });
                break;
        }
    };

    $scope.savePermisos = function () {

        console.log($scope.permisos);

        var data = {
            idrol: $scope.idrol,
            permisos: $scope.permisos
        };

        $http.post(API_URL + 'rol/savePermisos', data ).success(function (response) {

            $('#modalPermisos').modal('hide');

            if (response.success == true) {

                $scope.message = 'Se actualizó correctamente los permisos correspondiente al rol...';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();

            }
            else {

                $scope.message_error = 'Ha ocurrido un error...';
                $('#modalMessageError').modal('show');

            }
        });

    };

    $scope.showModalConfirm = function (item) {
        $scope.idusuario = item.idusuario;
        $scope.cargo_seleccionado = item.usuario;
        $('#modalConfirmDelete').modal('show');
    };

    $scope.delete = function(){
        $http.delete(API_URL + 'usuario/' + $scope.idusuario).success(function(response) {
            if(response.success == true){
                $scope.initLoad(1);
                $('#modalConfirmDelete').modal('hide');
                $scope.idusuario = 0;
                $scope.message = 'Se eliminó correctamente el Usuario seleccionado...';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();

            } else {

                if (response.exists == true) {
                    $scope.message_error = 'El Rol no puede ser eliminado porque esta asignado a un Usuario...';
                } else {
                    $scope.message_error = 'Ha ocurrido un error al intentar eliminar el rol seleccionado...';
                }

                $('#modalMessageError').modal('show');
                //$('#modalConfirmDelete').modal('hide');
            }
        });

    };

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };

    $scope.initLoad(1);

});
