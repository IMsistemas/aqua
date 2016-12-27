/**
 * Created by Raidel Berrillo Gonzalez on 26/12/2016.
 */


    app.filter('formatDate', function(){
        return function(fecha){
            var array_month = [
                'Ene', 'Feb', 'Marz', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'
            ];
            var t = fecha.split('-');
            return t[2] + '-' + array_month[t[1] - 1] + '-' + t[0];
        }
    });

    app.controller('retencionComprasIndexController', function($scope, $http, API_URL) {

        $scope.tiporetencion = [
            { id: 0, name: '-- Todos --' },
            { id: 1, name: 'Retención IVA' },
            { id: 2, name: 'Retención Fuente a la Renta' }
        ];
        $scope.s_tiporetencion = 0;
        $scope.retencion = [];

        $scope.initLoad = function (pageNumber) {
            $http.get(API_URL + 'retencionCompra/getRetenciones?page=' + pageNumber).success(function(response){
                $scope.retencion = response.data;
                $scope.totalItems = response.total;
            });
        };

        $scope.initLoad();

        $scope.pageChanged = function(newPage) {
            $scope.initLoad(newPage);
        };

        $scope.loadFormPage = function(id){
            window.open('retencionCompra/form/' + id, '_blank');
        };

    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'DD/MM/YYYY',
            ignoreReadonly: false
        });
    });