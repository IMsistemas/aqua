app.controller('barrioController', function($scope, $http, API_URL) {

    $scope.barrios = [];
    $scope.idbarrio_del = 0;
    $scope.idcalle_delete=0;
    $scope.canales_calle = 0;
    $scope.aux_calles = [];
    $scope.barrio_actual = 0 ;
    $scope.aux1 = 0 ;
    $scope.calle_actual = 0;
    $scope.calless = [];
    $scope.barrio = [];


    $scope.initLoad = function () {
        $http.get(API_URL + 'barrio/getBarrios').success(function(response){
            // console.log(response);
            $scope.barrios = response;
        });
    };

    $scope.viewModalAdd = function () {
        $http.get(API_URL + 'barrio/getParroquias').success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '--Seleccione--', id: 0}];
            //var array_temp = [];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].nameparroquia, id: response[i].idparroquia})
            }
            $scope.parroquias = array_temp;
            $scope.t_parroquias = 0;

            $http.get(API_URL + 'barrio/getLastID').success(function(response){
                $scope.codigo = response.id;
                $scope.nombrebarrio = '';
                $('#btn-savebarrio').prop('disabled', false);
                $('#modalNueva').modal('show');
            });

        });

    };

    $scope.saveBarrio = function () {
        $('#btn-savebarrio').prop('disabled', true)

        var data = {
            nombrebarrio: $scope.nombrebarrio,
            idparroquia: $scope.t_parroquias,
        };

        $http.post(API_URL + 'barrio', data ).success(function (response) {

            $scope.initLoad();

            $('#modalNueva').modal('hide');
            $scope.message = 'Se insertó correctamente la Zona';
            $('#modalMessage').modal('show');
            $scope.hideModalMessage();

        }).error(function (res) {

        });
    };

    $scope.show_toma = function (idbarrio,aux0, barrio)   {

        if(aux0==2){

            if(barrio !== undefined && barrio !== null){
                $scope.barrio = barrio;}

            $http.get(API_URL + 'barrio/getBarrio').success(function (response) {
                var longitud = response.length;
                //var array_temp = [{label: '--Seleccione--', id: 0}];
                var array_temp = [];
                for (var i = 0; i < longitud; i++) {
                    array_temp.push({label: response[i].namebarrio, id: response[i].idbarrio})
                }
                $scope.barrios2 = array_temp;
                $scope.id_barrio = idbarrio;
            });

            $http.get(API_URL + 'calle/getLastID').success(function(response){
                // console.log(response);
                $scope.codigo_toma = response.id;
                $scope.nombrecalle = '';
                $scope.aux1 = aux0 ;
                $('#modalTomas').modal('hide');
                $('#btn-savebarrio').prop('disabled', false)
                $('#modalNuevaToma').modal('show');
            });
        }else {
            if(barrio !== undefined && barrio !== null){
                $scope.barrio = barrio;}

            $http.get(API_URL + 'barrio/getBarrio').success(function (response) {
                var longitud = response.length;
                //var array_temp = [{label: '--Seleccione--', id: 0}];
                var array_temp = [];
                for (var i = 0; i < longitud; i++) {
                    array_temp.push({label: response[i].nnamebarrio, id: response[i].idbarrio})
                }
                $scope.barrios2 = array_temp;
                $scope.id_barrio = idbarrio;
            });
            $http.get(API_URL + 'calle/getLastID').success(function(response){
                // console.log(response);
                $scope.codigo_toma = response.id;
                $scope.nombrecalle = '';
                $scope.aux1 = aux0 ;
                $('#btn-savecalle').prop('disabled', false)
                $('#modalNuevaToma').modal('show');
                $('#modalTomas').modal('show');

            });
        }

    };

    $scope.saveCalle = function () {
        $('#btn-savecalle').prop('disabled', true)
        var data = {
            nombrecalle: $scope.nombrecalle,
            idbarrio: $scope.id_barrio
            };
        $http.post(API_URL + 'calle', data ).success(function (response) {
            $scope.initLoad();

            $('#modalNuevaToma').modal('hide');
            $scope.message = 'Se insertó correctamente la Transversal';
            $('#modalMessage').modal('show');
            $scope.hideModalMessage();
            if( $scope.aux1==1) {
                $scope.showModalAction($scope.barrio);
            }

        }).error(function (res) {
        });
    };

    $scope.showModalAction = function (item) {
        $scope.junta_n = item.namebarrio;
        $scope.calless = item.calle ;
        $scope.barrio_actual = item.idbarrio;
        $scope.barrio = item;

        var data = {
            calles: item.calle
        };
        $http.get(API_URL + 'barrio/calles/' + item.idbarrio).success(function(response) {
            $scope.aux_calles = response;

        });
        $scope.initLoad();
        $('#modalTomas').modal('show');

    };

    $scope.showModalInfo = function (item) {
        $scope.name_junta = item.namebarrio;
        var array_tomas = item.calle;
        var text = '';
        var calles = [];

        for (var e = 0; e < array_tomas.length; e++){
            calles.push(array_tomas[e].idcalle);
            text += array_tomas[e].namecalle + ','
        }
        $scope.junta_tomas = text;
        $('#modalInfo').modal('show');
    };

    $scope.showModalDelete = function (item) {
        $scope.idbarrio_del = item.idbarrio;
        $scope.nom_junta_modular = item.namebarrio;
        $('#modalDelete').modal('show');
    };

    $scope.delete = function(){
        $http.delete(API_URL + 'barrio/' + $scope.idbarrio_del).success(function(response) {
            $('#modalDelete').modal('hide');
            if(response.success == true){
                console.log(response);
                $scope.initLoad();
                $scope.idbarrio_del = 0;
                $scope.message = 'Se eliminó correctamente la Zona seleccionada...';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            } else if(response.success == false && response.msg == 'exist_calle') {
                $scope.message_error = 'La Zona no puede ser eliminada porque contiene Transversales...';
                $('#modalMessageError').modal('show');
            }
        });
    };

    $scope.showModalDeleteCalle = function (item) {
        $scope.idcalle_delete = item.idcalle;
        $scope.nom_calle_delete = item.namecalle;
        $('#modalDeleteCalle').modal('show');
    };

    $scope.deleteCalleEnBarrio = function(){
        $http.delete(API_URL + 'calle/' + $scope.idcalle_delete).success(function(response) {
            console.log(response);
            $('#modalDeleteCalle').modal('hide');
            if(response.success == true){
                $scope.initLoad();
                $scope.idcalle_delete = 0;
                $scope.message = 'Se eliminó correctamente la Transversal seleccionada...';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();

                $scope.showModalAction($scope.barrio);

            } else {
                $scope.message_error = 'La Transversal no puede ser eliminada porque está relacionada con un Suministro...';
                $('#modalMessageError').modal('show');
            }
        });
    };

    $scope.editarCalles = function() {
        var c = 0;
        for (var i = 0; i <  $scope.aux_calles.length; i++)
        {
            if( $scope.aux_calles[i].nombrecalle == ""){
                c ++ ;
            }
        }
        if(c > 0 )
        {
            $scope.message_error  = 'Existen Calles con nombres en blanco, por favor llene ese campo... ';
            $('#modalMessageError').modal('show');
        } else {
            var arr_calle = {arr_calle: $scope.aux_calles};
            $http.post(API_URL + 'barrio/editar_calle', arr_calle).success(function (response) {
                console.log(response);
                $scope.initLoad();
                $scope.message = 'Se editaron correctamente las Transversales';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();

                /*setTimeout(function(){
                 $('#modalMessage').modal('hide');
                 }, 500);*/
                $scope.showModalAction($scope.barrio);
            });
        }
    }

    $scope.editar = function ()  {
        var c = 0;
        for (var i = 0; i <  $scope.barrios.length; i++)
        {
            if( $scope.barrios[i].nombrebarrio == ""){
                c ++ ;
            }
        }

        if(c > 0 )
        {
            $scope.message_error  = 'Existen Zonas con nombres en blanco, por favor llene ese campo... ';
            $('#modalMessageError').modal('show');
        } else
        {
            var arr_barrio = { arr_barrio: $scope.barrios };
            $http.post(API_URL + 'barrio/editar_Barrio', arr_barrio).success(function(response){
                //  console.log(response);
                $scope.initLoad();
                $scope.message= 'Se editaron correctamente las Zonas';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            });
        }

    };


    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };


    $scope.initLoad();
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