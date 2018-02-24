
app.controller('graficos', function($scope, $http, API_URL) {

    var f= new Date();
    var dia=f.getDate();
    dia=(dia<10)? ("0"+dia):(dia);
    var mes = (f.getMonth()+1);
    mes =(mes<10)? ("0"+mes):(mes);
    var fechai=f.getFullYear()+"-"+mes+"-01";
    var fechaf=f.getFullYear()+"-"+mes+"-"+dia;

    $scope.balacer="Estado de Situación Financiera Hasta:"+ fechaf;
    $scope.estador="Estado de Resultados Desde:"+ fechai+"  Hasta:"+fechaf;





    $scope.generar_balance_general_grafico=function () {
        $scope.aux_formula_patrimonial=0;
        $scope.filtro_balance_general={
            FechaI: fechai,
            FechaF: fechaf,
            Estado: 1
        };
        $http.get(API_URL + 'Balance/balance_general/'+JSON.stringify($scope.filtro_balance_general))
            .success(function(response){
                console.log(response);

                var balancetitulos=["Activos","Pasivos","Patrimonio","Utilidad"];
                var balancevalores=[0,0,0,0];

                if(response.Activo[0]!=undefined || response.Activo[0]!= null ){
                    balancevalores[0]= parseFloat(response.Activo[0].balance);
                }
                if(response.Pasivo[0]!=undefined || response.Pasivo[0]!= null ){
                    balancevalores[1]= parseFloat(response.Pasivo[0].balance);
                }
                if(response.Patrimonio[0]!=undefined || response.Patrimonio[0]!= null ){
                    balancevalores[2]= parseFloat(response.Patrimonio[0].balance);
                }

                balancevalores[3]= parseFloat(response.Utilidad);



                var fulldata = [
                    {
                        label:"Activos",
                        backgroundColor: ["rgba(0,137, 123, 0.7)"],
                        data: [balancevalores[0]]
                    },
                    {
                        label: "Pasivos",
                        backgroundColor: [ "rgba(205,220, 57, 0.7)"],
                        data: [balancevalores[1]]
                    },
                    {
                        label: "Patrimonio",
                        backgroundColor: [ "rgba(255,127, 38, 0.7)"],
                        data: [balancevalores[2]]
                    },
                    {
                        label: "Utilidad",
                        backgroundColor: [ "rgba(46,125, 50, 0.7)"],
                        data: [ balancevalores[3]]
                    }
                ];

                var data = {
                    labels: [""],
                    datasets: fulldata
                };

                $("#stay_canvas1").html("");
                $("#stay_canvas1").html("<canvas id='balance'></canvas>");
                var ctx = document.getElementById('balance').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data:data,
                    options: {
                        responsive: true,
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: ''
                        }
                    }
                });

                $scope.balacer="Estado de Situación Financiera Hasta: "+ fechaf;
                $scope.balacer=$scope.balacer.toUpperCase();
                $scope.estador=$scope.estador.toUpperCase();
            });
    };


    $scope.generar_de_estado_resultados_grafico=function () {
        $scope.filtro_estado_resultados={
            FechaI:fechai,
            FechaF:fechaf,
            Estado: 1
        };
        $http.get(API_URL + 'Balance/estado_de_resultados/'+JSON.stringify($scope.filtro_estado_resultados))
            .success(function(response){
                console.log(response);

                var estadortitulos=["Ingresos","Pasivos","Patrimonio","Utilidad"];
                var estadorvalores=[0,0,0,0];

                if(response.Ingreso[0]!=undefined || response.Ingreso[0]!= null ){
                    estadorvalores[0]= parseFloat(response.Ingreso[0].balance);
                }
                if(response.Costo[0]!=undefined || response.Costo[0]!= null ){
                    estadorvalores[1]= parseFloat(response.Costo[0].balance);
                }
                if(response.Gasto[0]!=undefined || response.Gasto[0]!= null ){
                    estadorvalores[2]= parseFloat(response.Gasto[0].balance);
                }





                var fulldata = [
                    {
                        label:"Ingresos",
                        backgroundColor: ["rgba(100,181, 246, 0.7)"],
                        data: [estadorvalores[0]]
                    },
                    {
                        label: "Costos",
                        backgroundColor: [ "rgba(63,81,181, 0.7)"],
                        data: [estadorvalores[1]]
                    },
                    {
                        label: "Gastos",
                        backgroundColor: [ "rgba(229,57, 53, 0.7)"],
                        data: [estadorvalores[2]]
                    }
                ];

                var data = {
                    labels: [""],
                    datasets: fulldata
                };

                $("#stay_canvas2").html("");
                $("#stay_canvas2").html("<canvas id='etador'></canvas>");
                var ctx = document.getElementById('etador').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data:data,
                    options: {
                        responsive: true,
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: ''
                        }
                    }
                });

                $scope.estador="Estado de Resultados Desde: "+ fechai+"  Hasta: "+fechaf;
                $scope.balacer=$scope.balacer.toUpperCase();
                $scope.estador=$scope.estador.toUpperCase();
            });
    };

    $scope.generar_de_estado_resultados_grafico();
    $scope.generar_balance_general_grafico();

});
