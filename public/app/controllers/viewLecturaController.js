

    app.controller('viewLecturaController', function($scope, $http, API_URL) {

        $scope.lecturas = [];
        $scope.lecturasUpdate=[];

        $scope.initData = function(){

            $scope.loadBarrios();

            $http.get(API_URL + 'verLectura/getLecturas').success(function(response){
                $scope.lecturas = response;
            });

        }        
        

        $scope.save=function() {

            //console.log($scope.lecturasUpdate);

            $http.put(API_URL + 'verLectura/update/' + JSON.stringify($scope.lecturasUpdate)).success(function(response){
                console.log(response);

                $scope.lecturasUpdate = [];
            });

        };
        
        $scope.prepareUpdate=function (lectura) {

            var longitud = ($scope.lecturasUpdate).length;

            if (longitud == 0){
                $scope.lecturasUpdate.push(lectura);
            } else {
                var flag = false;
                for (var i = 0; i < longitud; i++) {
                    if(($scope.lecturasUpdate)[i].idlectura == lectura.idlectura){
                        
                        ($scope.lecturasUpdate)[i] = lectura;    

                        flag = true;
                    }
                }

                if (flag == false)  $scope.lecturasUpdate.push(lectura);
            }
            
        };

        $scope.loadBarrios = function(){
            $http.get(API_URL + 'verLectura/getBarrios').success(function(response){
                var longitud = response.length;
                var array_temp = [];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nombrebarrio, id: response[i].idbarrio})
                }
                $scope.barrios = array_temp;
            });
        }

        $scope.loadCalles = function(){
            var idbarrio = $scope.s_barrio;

            $http.get(API_URL + 'verLectura/getCalles/' + idbarrio).success(function(response){
                var longitud = response.length;
                var array_temp = [];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nombrecalle, id: response[i].idcalle})
                }
                $scope.calles = array_temp;
            });
        }

        
        $scope.searchByFilter = function(){

            if($scope.s_anno == undefined){
                var anno = null;
            } else var anno = $scope.s_anno;

            if($scope.s_mes == undefined){
                var mes = null;
            } else var mes = $scope.s_mes;

            if($scope.s_barrio == undefined){
                var barrio = null;
            } else var barrio = $scope.s_barrio;

            if($scope.s_calle == undefined){
                var calle = null;
            } else var calle = $scope.s_calle;

            if($scope.t_search == undefined){
                var t_search = null;
            } else var t_search = $scope.t_search;


            var filters = {
                anno: anno,
                mes: mes,
                barrio: barrio,
                calle: calle,
                text: t_search
            }

            console.log(filters);

            $http.get(API_URL + 'verLectura/getByFilter/' + JSON.stringify(filters)).success(function(response){
                $scope.lecturas = response;
            });
        }

        $scope.initData();

    });

    $(function(){
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
