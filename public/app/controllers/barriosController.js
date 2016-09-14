app.controller('barriosController', function($scope, $http, API_URL) {
    //retrieve barrios listing from API
    $scope.barrios=[];
    document.getElementById("idbarrio").disabled = true;
    $scope.idbarrio="";
    $scope.idparroquia=0;
    $scope.nombrebarrio="";
    $scope.idbarrio_del=0;
    $scope.initLoad = function(){
    $http.get(API_URL + "barrios/gestion/"+$scope.idparroquia)
        .success(function(response) {
                $scope.barrios = response;        

            });
    }
    $scope.initLoad();
    $scope.ordenarColumna = 'estaprocesada';
    //show modal form
    $scope.toggle = function(modalstate, idbarrio, nombrebarrio) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nuevo Barrio";
                $http.get(API_URL + 'barrios/maxid')
                        .success(function(response) {
                            console.log(response);
                            $scope.idbarrio = response;
                            $scope.nombrebarrio = "";
                        });
                
                break;
            case 'edit':
                $scope.form_title = "Editar barrio";
                $scope.idbarrio = idbarrio;
                $scope.nombrebarrio=nombrebarrio.trim();  
                break;
            default:
                break;
        }
         $('#myModal').modal('show');
     
    }

    //al mo mento que le den click al ng-click getInfo() ejecutamos la funcion

    //save new record / update existing record
    $scope.save = function(modalstate, idbarrio,idparroquia) {
        var url = API_URL + "barrios/gestion";    
        $scope.idparroquia=idparroquia; 
        
        //append barrio id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/actualizarbarrio/" + idbarrio;
        }else{
            url += "/guardarbarrio/"+$scope.idparroquia ;
        }
         $scope.barrio={
            idbarrio: $scope.idbarrio,
            nombrebarrio: $scope.nombrebarrio
        };
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.barrio),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            $scope.initLoad();
            $('#myModal').modal('hide');
            console.log(response);
            $scope.message = response;
             $('#modalMessage').modal('show');
             setTimeout("$('#modalMessage').modal('hide')",5000);
        }).error(function(response) {
            console.log($scope.barrio);
            console.log(response);
            alert('Ha ocurrido un error');
        });
    }

    //delete record
     $scope.showModalConfirm = function(idbarrio,nombrebarrio){
        $scope.idbarrio_del = idbarrio;
        $scope.barrio_seleccionado = nombrebarrio;
        $('#modalConfirmDelete').modal('show');
    }

    $scope.destroyBarrio = function(){
        $http.delete(API_URL + 'barrios/gestion/eliminarbarrio/' + $scope.idbarrio_del).success(function(response) {
            $scope.initLoad();
            $('#modalConfirmDelete').modal('hide');
            $scope.idcanton_del = 0;
            $scope.message = response;
            $('#modalMessage').modal('show')
            setTimeout("$('#modalMessage').modal('hide')",5000);
            });
    }
});
