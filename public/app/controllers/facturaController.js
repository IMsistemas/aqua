app.controller('facturaController', function($scope, $http, API_URL) {
    $scope.cobroagua_aux = [];

    $scope.factura = [];
    $scope.aux = [];
    $scope.item = 0;


    $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };

    $scope.initLoad = function (pageNumber) {
        $http.get(API_URL + 'factura/verifyPeriodo').success(function(response){
            (response.success == false) ? $('#btn-generate').prop('disabled', false) : $('#btn-generate').prop('disabled', false);
        });

        $('.datepicker_a').datetimepicker({
            locale: 'es',
            format: 'YYYY'
        }).on('dp.change', function (e) {
            $scope.initLoad(1);
        });

        $scope.array_temp = [];
        $scope.aux = [];
        $scope.servicio='';
        $scope.tarifa = '';
        $scope.a = '';


        $scope.t_anio = $('#t_anio').val();

        if ($scope.s_mes == undefined) {
            var m = null;
        } else var m = $scope.s_mes;

        if ($scope.t_busqueda == undefined) {
            var search = null;
        } else var search = $scope.t_busqueda;

        var filtros = {
            estado: $scope.s_estado,
            mes: m,
            anio: $scope.t_anio,
            search: search
        };

        $http.get(API_URL + 'factura/getCobroAgua?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){
            console.log(response);

            /*var longitud = (response.data).length;
            for (var i = 0; i < longitud; i++) {
                var complete_name = {
                    value: response.data[i].cliente.apellidos + ', ' + response.data[i].cliente.nombres,
                    writable: true,
                    enumerable: true,
                    configurable: true
                };
                Object.defineProperty(response.data[i].cliente, 'complete_name', complete_name);
            }*/
            $scope.factura = response.data;
            $scope.totalItems = response.total;
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

        $http.get(API_URL + 'factura/getMultas').success(function(response){

            console.log(item);
            console.log(response);

            $scope.num_factura = item.idcobroagua;

            $scope.mes = Auxiliar(yearmonth(item.fechacobro));
            $scope.multa = '';

            $scope.documentoidentidad_cliente = item.suministro.cliente.persona.numdocidentific;
            $scope.nom_cliente = item.suministro.cliente.persona.razonsocial;
            $scope.direcc_cliente = item.suministro.cliente.persona.direccion;
            $scope.telf_cliente = item.suministro.cliente.persona.celphone;

            var arreg = [];
            var total = 0.00;

            if (item != null) {
                if (item.valortarifabasica == null) {
                    var valores_atrasados = {
                        nombre: "Consumo Agua" + ' - ' +  $scope.mes  ,
                        valor: 0.00,
                        id: 0
                    }
                }else {
                    var consumo_agua = {
                        nombre: "Consumo Agua" + ' - ' +  $scope.mes ,
                        valor: item.valortarifabasica,
                        id: 0
                    }
                }
                arreg.push(consumo_agua);

                if (item.valorexcedente == null) {
                    var excedente_agua = {
                        nombre: "Excedente Agua" + ' - ' +  $scope.mes,
                        valor:  0.00,
                        id: 0
                    }

                } else {
                    var excedente_agua = {
                        nombre: "Excedente Agua" + ' - ' +  $scope.mes,
                        valor: item.valorexcedente,
                        id: 0
                    }
                }
                arreg.push(excedente_agua);

                if (item.valormesesatrasados == null) {
                    var valores_atrasados = {
                        nombre: "Valores Atrasados",
                        valor: 0.00,
                        id: 0
                    }
                }else
                {
                    var valores_atrasados = {
                        nombre: "Valores Atrasados",
                        valor: item.valormesesatrasados,
                        id: 0
                    }
                }
                arreg.push(valores_atrasados);
            }

            if (item.catalogoitem_cobroagua != null) {
                if( item.catalogoitem_cobroagua.length > 0) {
                    $scope.servicios = item.catalogoitem_cobroagua;

                    for (var a = 0; a < $scope.servicios.length; a++) {
                        var auxiliar = {
                            nombre: $scope.servicios[a].cont_catalogitem.nombreproducto,
                            valor: $scope.servicios[a].valor,
                            id: 0
                        };
                        arreg.push(auxiliar);
                    }
                }
            } else {
                /*if( item.cliente.servicioscliente.length > 0) {
                    $scope.servicios = item.catalogoitem_cobroagua;

                    for (var a = 0; a < $scope.servicios.length; a++) {
                        var auxiliar = {
                            nombre: $scope.servicios[a].serviciojunta.nombreservicio,
                            valor: $scope.servicios[a].valor,
                            id: 0
                        }
                        arreg.push(auxiliar);
                    }
                }*/
            }


            if (response.length > 0){
                for (var j = 0; j < response.length; j++) {

                    var otrosvalores = {
                        nombre: response[j].nombreotrosvalores,
                        valor: 0.00,
                        id: response[j].idotrosvalores
                    };

                    if (item.otrosvalores_cobroagua.length > 0){
                        for (var x = 0; x < item.otrosvalores_cobroagua.length; x++) {
                            if (otrosvalores.id == item.otrosvalores_cobroagua[x].idotrosvalores) {
                                otrosvalores.valor = item.otrosvalores_cobroagua[x].valor;
                            }
                        }
                    }

                    arreg.push(otrosvalores);
                }
            }

            if (arreg.length > 0) {
                for (var i = 0; i < arreg.length; i++) {
                    total = total + parseFloat(arreg[i].valor);
                }
            }

            $scope.total = total.toFixed(2);

            $scope.aux_modal = arreg;

            if (item.estadopagado == true) {

                $('#footer-modal-factura').hide();

                $('#btn-save').prop('disabled', true);
                $('#btn-pagar').prop('disabled', true);
            } else {
                $('#footer-modal-factura').show();

                $('#btn-save').prop('disabled', false);
                $('#btn-pagar').prop('disabled', false);
            }


            $('#modalFactura').modal('show');
            // $scope.total =  parseFloat($scope.multa_asamblea) + parseFloat($scope.valores_atrasados) + parseFloat($scope.excedente_agua) + parseFloat($scope.consumo_agua);
        });

    };

    $scope.reCalculateTotal = function () {
        var total = 0;
        for (var i = 0; i < $scope.aux_modal.length; i++) {
            total = total + parseFloat($scope.aux_modal[i].valor);
        }

        $scope.total = total.toFixed(2);
    };

    $scope.generate = function () {

        $http.get(API_URL + 'factura/generate').success(function(response){
            if (response.result = '1') {
                $scope.initLoad();
                $scope.message = 'Se ha generado los cobros del mes actual correctamente...';
                $('#modalMessage').modal('show');
            } else if (response.result = '2') {
                $scope.message = 'No existen registros para generar cobros de agua en el mes...';
                $('#modalMessage').modal('show');
            }
        });

    };

    $scope.save = function () {
          var data = {
              rubros: $scope.aux_modal,
              no_factura: $scope.num_factura,
              total: $scope.total
          };

          $http.post(API_URL + 'factura', data).success(function(response){
            $scope.initLoad();
            $scope.message = 'Se actualizó correctamente la Factura';
            $('#modalMessage').modal('show');
            $scope.hideModalMessage();
        });
    };

    $scope.pagar = function(){
        var id = $scope.num_factura;
        $http.put(API_URL + 'factura/'+ id, true ).success(function (response) {
            $scope.initLoad();

            $('#modalFactura').modal('hide');

            $('#footer-modal-factura').hide();

            $('#btn-save').prop('disabled', true);
            $('#btn-pagar').prop('disabled', true);
            $scope.message = 'Se efectuó el pago correctamente';
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

    $scope.Filtrar= function  () {

        $scope.t_anio = $('#t_anio').val();

        if ($scope.t_anio == undefined) {
            var a = null;
        } else var a = $scope.t_anio;

        if ($scope.s_mes == undefined) {
            var m = null;
        } else var m = $scope.s_mes;

        var filtros = {
            estado: $scope.s_estado,
            mes: m,
            anio: $scope.t_anio
        }

        $http.get(API_URL + 'factura/Filtrar/' + JSON.stringify(filtros) + '?page=1').success(function(response){
            console.log(response);
            var longitud = response.data.length;
            for (var i = 0; i < longitud; i++) {
                var complete_name = {
                    value: response.data[i].cliente.apellidos + ', ' + response.data[i].cliente.nombres,
                    writable: true,
                    enumerable: true,
                    configurable: true
                };
                Object.defineProperty(response.data[i].cliente, 'complete_name', complete_name);
            }
            $scope.factura = response.data;
            $scope.totalItems = response.total;

        });

        }

    $scope.printer = function (item) {

        var subtotal = 0;

        if (item.catalogoitem_cobroagua.length > 0) {

            for (var i = 0; i < item.catalogoitem_cobroagua.length; i++) {
                subtotal += parseFloat(item.catalogoitem_cobroagua[i].valor);
            }

        }

        if (item.otrosvalores_cobroagua.length > 0) {

            for (var i = 0; i < item.otrosvalores_cobroagua.length; i++) {
                subtotal += parseFloat(item.otrosvalores_cobroagua[i].valor);
            }

        }

        subtotal += parseFloat(item.valorexcedente);
        subtotal += parseFloat(item.valortarifabasica);
        subtotal += parseFloat(item.valormesesatrasados);

        var porcentaje_iva_cliente = parseFloat(item.suministro.cliente.sri_tipoimpuestoiva.porcentaje);

        var total_iva = 0;

        if(porcentaje_iva_cliente != 0){
            total_iva = (subtotal * porcentaje_iva_cliente) / 100;
        }

        var total = subtotal + total_iva;

        var date_p = (item.lectura.fechalectura).split('-');
        var date_p0 = date_p[1] + '/' + date_p[0];

        var partial_date = {
            value: date_p0,
            writable: true,
            enumerable: true,
            configurable: true
        };
        Object.defineProperty(item, 'partial_date', partial_date);


        var subtotalfactura = {
            value: subtotal.toFixed(2),
            writable: true,
            enumerable: true,
            configurable: true
        };
        Object.defineProperty(item, 'subtotalfactura', subtotalfactura);

        var iva = {
            value: total_iva.toFixed(2),
            writable: true,
            enumerable: true,
            configurable: true
        };
        Object.defineProperty(item, 'iva', iva);

        var totalfactura = {
            value: total.toFixed(2),
            writable: true,
            enumerable: true,
            configurable: true
        };
        Object.defineProperty(item, 'totalfactura', totalfactura);

        console.log(item);

        var a = {
            item: item
        };

        $http.post(API_URL + 'factura/print', a).success(function(response){
            console.log(response);

            var ventana = window.open(response.url);
            setTimeout(function(){ ventana.print(); }, 2000);

        });

    };

    $scope.onlyDecimal = function ($event) {
        var k = $event.keyCode;
        if (k == 8 || k == 0) return true;
        var patron = /\d/;
        var n = String.fromCharCode(k);
        if (n == ".") {
            return true;
        } else {
            if(patron.test(n) == false){
                $event.preventDefault();
            }
            else return true;
        }
    };

    $scope.onlyCharasterAndSpace = function ($event) {

        var k = $event.keyCode;
        if (k == 8 || k == 0) return true;
        var patron = /^([a-zA-Záéíóúñ\s]+)$/;
        var n = String.fromCharCode(k);

        if(patron.test(n) == false){
            $event.preventDefault();
            return false;
        }
        else return true;

    };

    $scope.onlyNumber = function ($event) {

        var k = $event.keyCode;
        if (k == 8 || k == 0) return true;
        var patron = /\d/;
        var n = String.fromCharCode(k);

        if (n == ".") {
            return true;
        } else {

            if(patron.test(n) == false){
                $event.preventDefault();
            }
            else return true;
        }
    };

    $scope.sort = function(keyname){
        $scope.sortKey = keyname;
        $scope.reverse = !$scope.reverse;
    };

    $scope.Estado();
    $scope.initLoad(1);
    //$scope.Servicio();
    $scope.Anio();
    $scope.Meses();


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
    }).on('dp.change', function (e) {
        $scope.initLoad(1);
    });
});


function Auxiliar  (mes_anio) {
    var mes = mes_anio;
    var elem = mes.split('-');
    anio = elem[1];
    mes_letra = elem[0];

    return mes_letra;
}


function yearmonth  (fecha) {
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