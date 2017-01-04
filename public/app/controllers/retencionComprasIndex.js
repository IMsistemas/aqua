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
            { id: 0, name: '-- Tipos de Retención --' },
            { id: 1, name: 'Retención IVA' },
            { id: 2, name: 'Retención Fuente a la Renta' }
        ];
        $scope.s_tiporetencion = 0;

        $scope.codigosretencion = [
            { id: 0, name: '-- Códigos de Retención --' }
        ];
        $scope.s_codigoretencion = 0;

        $scope.meses = [
            { id: 0, name: '-- Meses --' },
            { id: 1, name: 'Enero' },
            { id: 2, name: 'Febrero' },
            { id: 3, name: 'Marzo' },
            { id: 4, name: 'Abril' },
            { id: 5, name: 'Mayo' },
            { id: 6, name: 'Junio' },
            { id: 7, name: 'Julio' },
            { id: 8, name: 'Agosto' },
            { id: 9, name: 'Septiembre' },
            { id: 10, name: 'Octubre' },
            { id: 11, name: 'Noviembre' },
            { id: 12, name: 'Diciembre' }
        ];
        $scope.s_month = 0;

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

        $scope.getCodigosRetencion = function () {
            var tipo = $scope.s_tiporetencion;

            if (tipo != 0) {
                $http.get(API_URL + 'retencionCompra/getCodigosRetencion/' + tipo).success(function(response){
                    var longitud = response.length;
                    var array_temp = [{ id: 0, name: '-- Códigos de Retención --' }];
                    for (var i = 0; i < longitud; i++) {
                        array_temp.push({id: response[i].iddetalleretencionfuente, name: response[i].codigoSRI})
                    }
                    $scope.codigosretencion = array_temp;
                    $scope.s_codigoretencion = 0;
                });
            } else {
                $scope.codigosretencion = [
                    { id: 0, name: '-- Códigos de Retención --' }
                ];
                $scope.s_codigoretencion = 0;
            }
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