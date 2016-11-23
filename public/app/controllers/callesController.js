app.controller('callesController', function($scope, $http, API_URL) {

    $scope.calles = [];
    $scope.idcalle_del = 0;

    $scope.initLoad = function () {
        $http.get(API_URL + 'calle/getCalles').success(function (response) {
            console.log(response);
            $scope.calles = response;

        });
    };

    $scope.FiltroCalle = function () {
        $http.get(API_URL + 'calle/getBarrio').success(function (response) {
            var longitud = response.length;
            var array_temp = [{label: '--Zonas --', id: 0}];
            for (var i = 0; i < longitud; i++) {
                array_temp.push({label: response[i].nombrebarrio, id: response[i].idbarrio})
            }
            $scope.barrioss = array_temp;
            $scope.s_barrio = 0;
        });
    };

    $scope.FiltrarPorBarrio = function (){
        $scope.aux = 0;
        $scope.aux = $scope.s_barrio;
        if($scope.aux > 0)
        {
            $http.get(API_URL + 'calle/getCallesByBarrio/'+ $scope.aux).success(function(response) {
                console.log(response);
                $scope.calles = response;
            });
        }
        else {  $scope.initLoad();
        }
    }

    $scope.viewModalAdd = function () {
        $http.get(API_URL + 'calle/getBarrio').success(function (response) {
            var longitud = response.length;
            //var array_temp = [{label: '--Seleccione--', id: 0}];
            var array_temp = [];
            for (var i = 0; i < longitud; i++) {
                array_temp.push({label: response[i].nombrebarrio, id: response[i].idbarrio})
            }
            $scope.barrios = array_temp;
        });

        $http.get(API_URL + 'calle/getLastID').success(function(response){
            console.log(response);

            $scope.codigo = response.id;
            $scope.date_ingreso = now();

            $scope.nombrecalle = '';
            $scope.observacionCalle = '';

            $('#modalNueva').modal('show');
        });

    }

    $scope.saveCalle = function () {
       var data = {
            nombrecalle: $scope.nombrecalle,
            idbarrio: $scope.t_barrio
        };

        $http.post(API_URL + 'calle', data ).success(function (response) {

            $scope.initLoad();

            $('#modalNueva').modal('hide');
            $scope.message = 'Se insertó correctamente la Transversal';
            $('#modalMessage').modal('show');
            $scope.hideModalMessage();

        }).error(function (res) {

        });

    };

    $scope.viewModalAdd = function () {
        $http.get(API_URL + 'calle/getBarrio').success(function (response) {
            var longitud = response.length;
            //var array_temp = [{label: '--Seleccione--', id: 0}];
            var array_temp = [];
            for (var i = 0; i < longitud; i++) {
                array_temp.push({label: response[i].nombrebarrio, id: response[i].idbarrio})
            }
            $scope.barrios = array_temp;
        });

        $http.get(API_URL + 'calle/getLastID').success(function(response){
            console.log(response);

            $scope.codigo = response.id;
            $scope.date_ingreso = now();

            $scope.nombrecalle = '';
            $scope.observacionCalle = '';

            $('#modalNueva').modal('show');
        });

    }

    $scope.showModalDelete = function (item) {
        $scope.idcalle_del = item.idcalle;
        $scope.nom_calle = item.nombrecalle;
        $('#modalDelete').modal('show');
    };

    $scope.delete = function(){
        $http.delete(API_URL + 'calle/' + $scope.idcalle_del).success(function(response) {
            $('#modalDelete').modal('hide');
            if(response.success == true){
                $scope.initLoad();
                $scope.idcalle_del = 0;
                $scope.message = 'Se eliminó correctamente la Transversal seleccionada...';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            } else {
                $scope.message_error = 'La Transversal no puede ser eliminada  porque esta relacionada con un suministro...';
                $('#modalMessageError').modal('show');
            }
        });

    };

    $scope.editar = function ()  {

        var c = 0;
        for (var i = 0; i <  $scope.calles.length; i++)
        {
            if( $scope.calles[i].nombrecalle == ""){
                c ++ ;
            }
        }

        if(c > 0 )
        {
            $scope.message_error  = 'Existen Transversales con nombres en blanco, por favor llene ese campo... ';
            $('#modalMessageError').modal('show');
        } else
        {
            var arr_calle = { arr_calle: $scope.calles };

            $http.post(API_URL + 'calle/editar_calle', arr_calle).success(function(response){
                console.log(response);
                $scope.initLoad();
                $scope.message = 'Se editaron correctamente las Transversales';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            });
        }







    };



    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };


    $scope.initLoad();
    $scope.FiltroCalle();




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
