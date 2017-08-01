
$(function () {
    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'YYYY-MM-DD'
    });
});

app.controller('reporteCCController',  function($scope, $http, API_URL) {

    $scope.centrocosto = [];

    $scope.initLoad = function(){

        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'YYYY-MM-DD'
        });

        var cc = '0';

        if ($scope.searchCC !== undefined) {
            cc = $scope.searchCC;
        }

        var filter = {
            inicio: $('#fechainicio').val(),
            fin: $('#fechafin').val(),
            cc: cc
        };

        $http.get(API_URL + 'reportecentrocosto/getCentroCosto?filter=' + JSON.stringify(filter)).success(function(response){

            var longitud = response.length;

            console.log(response);

            var centrocosto = [];

            for (var i = 0; i < longitud; i++) {

                if (centrocosto.length === 0) {

                    var cc = {
                        iddepartamento: response[i].iddepartamento,
                        namedepartamento: response[i].namedepartamento,
                        total: response[i].preciototal,
                        costos: [
                            {
                                numdocumentocompra: response[i].numdocumentocompra,
                                fecharegistrocompra: response[i].fecharegistrocompra,
                                detalle_item: response[i].codigoproducto + ' - ' + response[i].nombreproducto,
                                cantidad: response[i].cantidad,
                                preciounitario: response[i].preciounitario,
                                preciototal: response[i].preciototal
                            }
                        ]
                    };

                    centrocosto.push(cc);

                } else {

                    var longitud_cc = centrocosto.length;
                    var flag = false;

                    for (var j = 0; j < longitud_cc; j++) {

                        if (centrocosto[j].iddepartamento === response[i].iddepartamento) {

                            var item = {
                                numdocumentocompra: response[i].numdocumentocompra,
                                fecharegistrocompra: response[i].fecharegistrocompra,
                                detalle_item: response[i].codigoproducto + ' - ' + response[i].nombreproducto,
                                cantidad: response[i].cantidad,
                                preciounitario: response[i].preciounitario,
                                preciototal: response[i].preciototal
                            };

                            centrocosto[j].costos.push(item);
                            centrocosto[j].total = parseFloat(centrocosto[j].total) + parseFloat(response[i].preciototal);
                            centrocosto[j].total = centrocosto[j].total.toFixed(4);

                            flag = true;

                        }

                    }

                    if (flag === false) {

                        var cc = {
                            iddepartamento: response[i].iddepartamento,
                            namedepartamento: response[i].namedepartamento,
                            total: response[i].preciototal,
                            costos: [
                                {
                                    numdocumentocompra: response[i].numdocumentocompra,
                                    fecharegistrocompra: response[i].fecharegistrocompra,
                                    detalle_item: response[i].codigoproducto + ' - ' + response[i].nombreproducto,
                                    cantidad: response[i].cantidad,
                                    preciounitario: response[i].preciounitario,
                                    preciototal: response[i].preciototal
                                }
                            ]
                        };

                        centrocosto.push(cc);

                    }

                }

            }

            $scope.list = response;

            $scope.centrocosto = centrocosto;

            console.log(centrocosto);

        });

    };

    $scope.searchListCC = function(){
        $http.get(API_URL + 'reportecentrocosto/getListCC').success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione Centro de Costo --', id: '0'}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].namedepartamento, id: response[i].iddepartamento})
            }
            $scope.search_cc = array_temp;
            $scope.searchCC = '0';
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

    $scope.printReport = function() {

        var filtro = {
            FechaI: $('#fechainicio').val(),
            FechaF: $('#fechafin').val(),
            centroscosto: $scope.centrocosto
        };

        var accion = API_URL + 'reportecentrocosto/reporte_print/' + JSON.stringify(filtro);

        $('#WPrint_head').html('Reporte Centro de Costos');

        $('#WPrint').modal('show');

        $('#bodyprint').html("<object width='100%' height='600' data='" + accion + "'></object>");
    };

    $scope.fechaByFilter();

    $scope.initLoad();
    $scope.searchListCC();

});

