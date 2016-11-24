app.controller('facturaController', function($scope, $http, API_URL) {
    $scope.cobroagua_aux = [];

    $scope.cobroagua = [];
    $scope.aux = [];
    $scope.item = 0;

    $scope.initLoad = function () {
        $http.get(API_URL + 'factura/verifyPeriodo').success(function(response){
            (response.count == 0) ? $('#btn-generate').prop('disabled', false) : $('#btn-generate').prop('disabled', true);
        });

        $scope.array_temp = [];
        $scope.aux = [];
        $scope.servicio='';
        $scope.tarifa = '';
        $scope.a = '';

        $http.get(API_URL + 'factura/getCobroAgua').success(function(response){
            console.log(response);
            $scope.cobroagua = response;
        });
    };

    $scope.Servicio = function () {
        $http.get(API_URL + 'factura/getServicios').success(function (response) {
           // console.log(response);
            var longitud = response.length;
            var array_temp = [{label: '--Servicios --', id: 0}];
            for (var i = 0; i < longitud; i++) {
                array_temp.push({label: response[i].nombreservicio, id: response[i].idserviciojunta})
            }
            $scope.servicioss = array_temp;
            $scope.s_servicio = 0;
        });
    };

    $scope.Anio = function () {
            var array_temp = [{label: '--Año --', id: 0}];
            $scope.anios = array_temp;
            $scope.s_anio = 0;
    };

    $scope.Meses = function () {
            var array_temp = [
                {label: '-- Mes --', id: 0},
                {label: 'Enero', id: 1},
                {label: 'Febrero',id: 2},
                {label: 'Marzo', id: 3},
                {label: 'Abril' , id: 4},
                {label: 'Mayo',id: 5},
                {label: 'Junio', id: 6},
                {label: 'Julio', id: 7},
                {label: 'Agosto' , id: 8},
                {label: 'Septiembre', id: 9},
                {label: 'Octubre' , id: 10},
                {label: 'Noviembre' , id: 11},
                {label: 'Diciembre' ,id: 12}];

            $scope.meses = array_temp;
            $scope.s_mes = 0;
    };

    $scope.Estado = function () {
            var array_temp = [{label: '--Estado --', id: 0},{label: 'PAGADA', id: 1},{label: 'NO PAGADA ', id: 2}];

            $scope.estadoss = array_temp;
            $scope.s_estado = 0;
    };

    $scope.ShowModalFactura = function (item) {
        console.log(item);

        $scope.num_factura = item.factura.idfactura;
        $scope.mes = Auxiliar (yearmonth(item.fecha));
        $scope.multa = '';

        $scope.documentoidentidad_cliente = item.suministro.cliente.documentoidentidad;
        $scope.nom_cliente = item.suministro.cliente.nombres + ' ' + item.suministro.cliente.apellidos;
        $scope.direcc_cliente = item.suministro.cliente.direcciondomicilio;
        $scope.telf_cliente = item.suministro.cliente.celular;

        var arreg = [];
        var total = 0.00;

        if (item.valortarifabasica == null) {
            var valores_atrasados = {
                nombre: "Consumo Agua" + ' - ' +  $scope.mes  ,
                valor: 0.00
            }
        }else {
            var consumo_agua = {
                nombre: "Consumo Agua" + ' - ' +  $scope.mes ,
                valor: item.valortarifabasica
            }
        }
        arreg.push(consumo_agua);

        if (item.valorexcedente == null) {
            var excedente_agua = {
                nombre: "Excedente Agua" + ' - ' +  $scope.mes,
                valor:  0.00
            }

        } else {
            var excedente_agua = {
                nombre: "Excedente Agua" + ' - ' +  $scope.mes,
                valor: item.valorexcedente
            }
        }
            arreg.push(excedente_agua);

        if (item.valormesesatrasados == null) {
            var valores_atrasados = {
                nombre: "Valores Atrasados",
                valor: 0.00
            }
        }else
            {
                var valores_atrasados = {
                    nombre: "Valores Atrasados",
                    valor: item.valormesesatrasados
                }
            }
                arreg.push(valores_atrasados);


            if( item.factura.serviciosenfactura.length > 0) {
                $scope.servicios = item.factura.serviciosenfactura;

                for (var a = 0; a < $scope.servicios.length; a++) {
                    var auxiliar = {
                        nombre: $scope.servicios[a].serviciojunta.nombreservicio,
                        valor: $scope.servicios[a].valor
                    }
                    arreg.push(auxiliar);
                }
            }

        for (var i = 0; i < arreg.length; i++) {
            total = total + parseFloat(arreg[i].valor);
        }

        $scope.total = total;


            $scope.aux_modal = arreg;
        $('#modalFactura').modal('show');
        // $scope.total =  parseFloat($scope.multa_asamblea) + parseFloat($scope.valores_atrasados) + parseFloat($scope.excedente_agua) + parseFloat($scope.consumo_agua);
    };

    $scope.generate = function () {

        $http.get(API_URL + 'factura/generate').success(function(response){
            if (response.result = '1') {
                $scope.initLoad();
                $scope.message = 'Se ha generado los cobros del mes actual correctamente...';
                $('#modalMessage').modal('show');
            } else if (response.result = '2') {
                $scope.message = 'No existen registros a generar cobros en el mes...';
                $('#modalMessage').modal('show');
            }
        });

    };

    $scope.pagar = function(){
        $http.put(API_URL + 'factura/'+ $scope.item ).success(function (response) {
            $scope.initLoad();
            $('#modalFactura').modal('hide');
            $scope.message = 'Se efectuo el pago correctamente';
            $('#modalMessage').modal('show');
            $scope.hideModalMessage();
        });
    }

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };

    $scope.FormatoFecha = function  (fecha) {
        var Date = fecha;
        var elem = Date.split('-');
        dia = elem[2];
        mes = elem[1];
        anio = elem[0];

        return dia  + '-' + mes + '-' + anio;
    }

    $scope.Pagada = function  (aux) {
       if(aux == true)
           return "PAGADA";
        else
            return "NO PAGADA";
    }

    $scope.yearmonth  = function (fecha) {
        var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        var Date = fecha;
        var elem = Date.split('-');
        dia = elem[2];
        mes = elem[1];
        anio = elem[0];

        if(mes < 10){
            var aux_mes = mes.split('')
            console.log(aux_mes[1]);
            segundo_numero = aux_mes[1];
            return meses[segundo_numero -1] + ' - ' + anio;
        }else {
            return meses[mes -1] + ' - ' + anio;
        }
    }

    $scope.initLoad();
    $scope.Servicio();
    $scope.Anio();
    $scope.Meses();
    $scope.Estado();

});

$(function(){

    $('[data-toggle="tooltip"]').tooltip();

    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'DD/MM/YYYY'
    });

    $('.datepicker_a').datetimepicker({
        locale: 'es',
        format: 'YYYY'
    });
});


function Auxiliar  (mes_anio)
{
    var mes = mes_anio;
    var elem = mes.split('-');
    anio = elem[1];
    mes_letra = elem[0];

    return mes_letra;
}


function yearmonth  (fecha)
{
    var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    var Date = fecha;
    var elem = Date.split('-');
    dia = elem[2];
    mes = elem[1];
    anio = elem[0];

    if(mes < 10){
        var aux_mes = mes.split('')
        console.log(aux_mes[1]);
        segundo_numero = aux_mes[1];
        return meses[segundo_numero -1] + ' - ' + anio;
    }else {
        return meses[mes -1] + ' - ' + anio;
    }
}