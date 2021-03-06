
$(function () {
    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'YYYY-MM-DD'
    });
});

app.controller('reporteVentaBalanceController',  function($scope, $http, API_URL) {


    $scope.initLoad = function(){

        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'YYYY-MM-DD'
        });

        var filter = {
            inicio: $('#fechainicio').val(),
            fin: $('#fechafin').val()
        };

        $http.get(API_URL + 'reporteventabalance/getVentasBalance?filter=' + JSON.stringify(filter)).success(function(response){

            console.log(response);

            var array_item = [];
            var longitud = response.length;

            for (var i = 0; i < longitud; i++) {

                if (array_item.length == 0) {

                    var t_f = 0;

                    if (parseFloat(response[i].debe_c) != 0) {
                        t_f = parseFloat(response[i].debe_c);
                    } else {
                        t_f = parseFloat(response[i].haber_c);
                    }

                    var object = {
                        idplancuenta: response[i].idplancuenta,
                        jerarquia: response[i].jerarquia,
                        concepto: response[i].concepto,
                        totalfactura: t_f.toFixed(2)
                    };
                    array_item.push(object);
                } else {

                    var longitud_t = array_item.length;

                    var state = false;

                    for (var j = 0; j < longitud_t; j++) {
                        if (response[i].idplancuenta == array_item[j].idplancuenta) {

                            var t_f = 0;

                            if (parseFloat(response[i].debe_c) != 0) {
                                t_f = parseFloat(response[i].debe_c);
                            } else {
                                t_f = parseFloat(response[i].haber_c);
                            }

                            var t = array_item[j].totalfactura + t_f;

                            array_item[j].totalfactura = parseFloat(t).toFixed(2);
                            state = true;
                        }
                    }

                    if (state === false) {

                        var t_f = 0;

                        if (parseFloat(response[i].debe_c) != 0) {
                            t_f = parseFloat(response[i].debe_c);
                        } else {
                            t_f = parseFloat(response[i].haber_c);
                        }

                        var object = {
                            idplancuenta: response[i].idplancuenta,
                            jerarquia: response[i].jerarquia,
                            concepto: response[i].concepto,
                            totalfactura: t_f.toFixed(2)
                        };
                        array_item.push(object);

                    }

                }

            }

            var total = 0;

            var longitud_array_item = array_item.length;

            for (var i = 0; i < longitud_array_item; i++) {
                total = total + parseFloat(array_item[i].totalfactura);
            }

            $scope.list = array_item;
            $scope.total = total.toFixed(2);
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
            FechaF: $('#fechafin').val()
        };

        var accion = API_URL + 'reporteventabalance/reporte_print/' + JSON.stringify(filtro);

        $('#WPrint_head').html('Reporte Ventas / Balance');

        $('#WPrint').modal('show');

        $('#bodyprint').html("<object width='100%' height='600' data='" + accion + "'></object>");
    };

    $scope.fechaByFilter();
    $scope.initLoad();

});
