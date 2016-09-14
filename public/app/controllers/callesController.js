app.controller('callesController', function($scope, $http, API_URL) {
    //retrieve calles listing from API
    document.getElementById("idcalle").disabled = true;
    $scope.calles=[];
    $scope.idcalle="";
    $scope.nombrecalle="";
    $scope.idcalle_del=0;
    $scope.initLoad = function(){
    $http.get(API_URL + "calles/gestion/"+$scope.idbarrio)
        .success(function(response) {
                $scope.calles = response;             

            });
    }
    $scope.initLoad();
    $scope.ordenarColumna = 'estaprocesada';
    //show modal form
    $scope.toggle = function(modalstate, idcalle, nombrecalle) {
        $scope.modalstate = modalstate;
        console.log(nombrecalle);
        console.log(idcalle);
        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nueva calle";
                $http.get(API_URL + 'calles/maxid')
                        .success(function(response) {
                            console.log(response);
                            $scope.idcalle = response;
                             $scope.nombrecalle = ""
                        });
                break;
            case 'edit':
                $scope.form_title = "Editar calle";
                $scope.idcalle = idcalle;
                $scope.nombrecalle=nombrecalle.trim();
                break;
            default:
                break;
        }
         $('#myModal').modal('show');
     
    }
    //al mo mento que le den click al ng-click getInfo() ejecutamos la funcion

    //save new record / update existing record
    $scope.save = function(modalstate, idcalle) {
        var url = API_URL + "calles/gestion";    
        console.log(idcalle); 
        
        //append calle id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/actualizarcalle/" + idcalle;
        }else{
            url += "/guardarcalle/"+$scope.idbarrio ;
        }
         $scope.calle={
            idcalle: $scope.idcalle,
            nombrecalle: $scope.nombrecalle
        };
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.calle),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            $scope.initLoad();
            $('#myModal').modal('hide');
                $scope.message = response;
             $('#modalMessage').modal('show');
             setTimeout("$('#modalMessage').modal('hide')",5000);
        }).error(function(response) {
            console.log($scope.calle);
            console.log(response);
            alert('Ha ocurrido un error');
        });
    }

    //delete record
 $scope.showModalConfirm = function(idcalle,nombrecalle){
        $scope.idcalle_del = idcalle;
        $scope.calle_seleccionado = nombrecalle.trim();
            $('#modalConfirmDelete').modal('show');
    }

    $scope.destroyCalle = function(){
        $http.delete(API_URL + 'calles/gestion/eliminarcalle/' + $scope.idcalle_del).success(function(response) {
            $scope.initLoad();
            $('#modalConfirmDelete').modal('hide');
            $scope.idcalle_del = 0;
            $scope.message = response;
            $('#modalMessage').modal('show')
            setTimeout("$('#modalMessage').modal('hide')",5000);
            });
    }
});
