
$(function () {
    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'YYYY-MM-DD'
    });
});

app.controller('reporteComprasController',  function($scope, $http, API_URL) {


    $scope.initLoad = function(){

        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'YYYY-MM-DD'
        });

        var filter = {
            inicio: $('#fechainicio').val(),
            fin: $('#fechafin').val()
        };

        $http.get(API_URL + 'reportecompra/getCompras?filter=' + JSON.stringify(filter)).success(function(response){

            var longitud = response.length;

            $scope.totalsubconimp = 0;
            $scope.totalsubsinimp = 0;
            $scope.totalsubcero = 0;
            $scope.totalsubnoobj = 0;
            $scope.totalsubex = 0;
            $scope.totaliva = 0;
            $scope.totalice = 0;
            $scope.totaldesc = 0;
            $scope.total = 0;

            for (var i = 0; i < longitud; i++) {
                $scope.totalsubconimp += parseFloat(response[i].subtotalconimpuestocompra);
                $scope.totalsubsinimp += parseFloat(response[i].subtotalsinimpuestocompra);
                $scope.totalsubcero += parseFloat(response[i].subtotalcerocompra);
                $scope.totalsubnoobj += parseFloat(response[i].subtotalnoobjivacompra);
                $scope.totalsubex += parseFloat(response[i].subtotalexentivacompra);
                $scope.totaliva += parseFloat(response[i].ivacompra);
                $scope.totalice += parseFloat(response[i].icecompra);
                $scope.totaldesc += parseFloat(response[i].totaldescuento);
                $scope.total += parseFloat(response[i].valortotalcompra);
            }

            $scope.totalsubconimp = '$ ' + $scope.totalsubconimp.toFixed(2);
            $scope.totalsubsinimp = '$ ' + $scope.totalsubsinimp.toFixed(2);
            $scope.totalsubcero = '$ ' + $scope.totalsubcero.toFixed(2);
            $scope.totalsubnoobj = '$ ' + $scope.totalsubnoobj.toFixed(2);
            $scope.totalsubex = '$ ' + $scope.totalsubex.toFixed(2);
            $scope.totaliva = '$ ' + $scope.totaliva.toFixed(2);
            $scope.totalice = '$ ' + $scope.totalice.toFixed(2);
            $scope.totaldesc = '$ ' + $scope.totaldesc.toFixed(2);
            $scope.total = '$ ' + $scope.total.toFixed(2);

            $scope.list = response;

        });

    };

    $scope.fechaByFilter = function(){

        var f = new Date();

        //var toDay = f.getDate() + '/' + (f.getMonth() + 1) + '/' + f.getFullYear();

        var toDay = f.getFullYear() + '-' + (f.getMonth() + 1) + '-' + f.getDate();

        var primerDia = new Date(f.getFullYear(), f.getMonth(), 1);

        //var firthDayMonth = primerDia.getDate() + '/' + (f.getMonth() + 1) + '/' + f.getFullYear();

        var firthDayMonth = f.getFullYear() + '-' + (f.getMonth() + 1) + '-' + primerDia.getDate();

        $('#fechainicio').val(firthDayMonth);
        $('#fechafin').val(toDay);

        $scope.fechainicio = firthDayMonth;
        $scope.fechafin = toDay;

    };

    $scope.fechaByFilter();
    $scope.initLoad();

});
