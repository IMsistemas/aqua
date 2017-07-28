app.controller('ConciliacionC', function($scope, $http, API_URL) {
    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'DD/MM/YYYY',
        ignoreReadonly: true
    });
    $("#alert").hide();

    $scope.viewconciliacion=1;

    $scope.txt_descripcion="";
    $scope.txt_balanceI="0";
    $scope.txt_balanceF="";

    $scope.aux_cuenta_contable_conciliada="";
    $scope.aux_data_conciliacion={};
    $scope.aux_balance_inicial=0;
    $scope.aux_balance_final=0;
    $scope.aux_conciliacion_egreso_ingreso="";
    $scope.aux_diferencia_conciliacion="";

    $scope.aux_conciliacion_egreso_ingreso=0;

    ///---Nueva Conciliacion
    $scope.new_conciliacion=function(){
        $("#data_conciliacion").modal("show");
    };
    ///--- Plan de Cuentas
    $scope.aux_plancuentas=[];
    $scope.BuscarCuentaContable=function(registro){
        $("#PlanContable").modal("show");
        $http.get(API_URL + 'estadosfinacieros/plancontabletotal')
        .success(function(response){
            $scope.aux_plancuentas=response;
        });
    };
    ///--- Cuenta Contable
    $scope.aux_cuenta_contable={};
    $scope.aux_descipcion_cuenta="";
    $scope.AsignarCuentaContable=function(cuenta){
        $scope.aux_cuenta_contable=cuenta;
        $scope.aux_descipcion_cuenta=cuenta.concepto;

        $scope.data_before_cuenta();
        
        $("#PlanContable").modal("hide");
    };
    ///---
    $scope.data_before_cuenta=function() {
        $http.get(API_URL + 'Conciliacion/data_before_cuenta/'+JSON.stringify($scope.aux_cuenta_contable))
        .success(function(response){
            console.log(response);
            if(response[0]!=undefined && response[0]!=null){
                $scope.aux_balance_inicial=parseFloat(response[0].balancefinal);
                $scope.txt_balanceI=$scope.aux_balance_inicial;

                $scope.aux_conciliacion_egreso_ingreso=$scope.aux_balance_inicial;
            }else{
                $scope.aux_balance_inicial="0.000";


                $scope.txt_balanceI=0;
                $scope.aux_conciliacion_egreso_ingreso=0;
            }
        });
    };
    ///---
   

    $scope.creat_edit_conciliacion=function(){
        if($scope.aux_cuenta_contable.idplancuenta!=undefined){
            var conciliacion_data={
                descripcion:$scope.txt_descripcion,
                fecha:convertDatetoDB($("#FechaI").val()),
                balanceinicial:$scope.txt_balanceI,
                balancefinal:$scope.txt_balanceF,
                idplancuenta:$scope.aux_cuenta_contable.idplancuenta, 
                estadoanulado:false, 
                estadoconciliacion:'0'
            };
            $http.get(API_URL + 'Conciliacion/save_conciliacion/'+JSON.stringify(conciliacion_data))
            .success(function(response){
               console.log(response);
               $scope.viewconciliacion=2;
               $scope.aux_data_conciliacion=response;
               if(parseInt(response.idconciliacion)>0){
                    $scope.Mensaje="Se creo correctamente la conciliacion";
                    $("#msm").modal("show");
                    $("#data_conciliacion").modal("hide");
                    $scope.txt_descripcion="";
                    $scope.txt_balanceI="0";
                    $scope.txt_balanceF="";
                    $scope.aux_cuenta_contable={};


                    $scope.aux_balance_inicial=$scope.aux_data_conciliacion.balanceinicial;
                   $scope.aux_balance_inicial=(parseFloat($scope.aux_balance_inicial)==0)?"0.000":parseFloat($scope.aux_balance_inicial);
                    //$scope.aux_balance_inicial=$scope.aux_balance_inicial.toFixed(4);

                    $scope.aux_balance_final=$scope.aux_data_conciliacion.balancefinal;
                    

                    $scope.get_asientos_contables_new();
               }else{
                $scope.Mensaje="Error al crear la conciliacion";
                $("#msm").modal("show"); 
               }
            });
        }else{
            $scope.Mensaje="Seleccione una cuenta contable";
            $("#msm").modal("show"); 
        }
    };
    ///--- 
    $scope.list_ingresos=[];
    $scope.list_egresos=[];
    $scope.get_asientos_contables_new=function(){
        $http.get(API_URL + 'Conciliacion/get_cuentas_conciliar/'+JSON.stringify($scope.aux_data_conciliacion))
            .success(function(response){
               console.log(response);
               if(response.length>0){
                $scope.render_data_conciliacion(response);
               }else{
                $scope.Mensaje="La cuenta contable no tiene transacciones";
                $("#msm").modal("show"); 
               }
               
            });
    };
    ///---
    $scope.aux_no_clarificado_ingresos=0;
    $scope.aux_no_clarificado_egresos=0;

    $scope.aux_no_contclarificado_ingresos=0;
    $scope.aux_no_contclarificado_egresos=0;


    $scope.aux_clarificado_ingresos=0;
    $scope.aux_clarificado_egresos=0;

    $scope.aux_contclarificado_ingresos=0;
    $scope.aux_contclarificado_egresos=0;

    $scope.check_i=false;
    $scope.render_data_conciliacion=function(data) {
        $scope.list_ingresos=[];
        $scope.list_egresos=[];

        $scope.aux_no_clarificado_ingresos=0;
        $scope.aux_no_clarificado_egresos=0;

        $scope.aux_no_contclarificado_ingresos=0;
        $scope.aux_no_contclarificado_egresos=0;


        $scope.aux_clarificado_ingresos=0;
        $scope.aux_clarificado_egresos=0;

        $scope.aux_contclarificado_ingresos=0;
        $scope.aux_contclarificado_egresos=0;

        $scope.aux_conciliacion_egreso_ingreso=$scope.aux_balance_inicial;
        console.log(data);

        $scope.aux_cuenta_contable_conciliada=data[0].cont_plancuentas.concepto;
        if(data[0].cont_plancuentas.controlhaber=="+"){ // se busca las cuentas que aumentan por haber = a ingresos , y debe = egresos
            data.forEach(function(item){

                if(parseFloat(item.haber_c)>0 &&  parseFloat(item.debe_c)==0){
                    $scope.list_ingresos.push(item);

                    if(parseInt(item.idconciliacion)>0){ //tiene conciliacion
                        $scope.aux_clarificado_ingresos+=parseFloat(item.haber_c);
                        $scope.aux_contclarificado_ingresos+=1;
                    }else{
                        $scope.aux_no_clarificado_ingresos+=parseFloat(item.haber_c);
                        $scope.aux_no_contclarificado_ingresos+=1;
                    }
                    

                }else if(parseFloat(item.haber_c)==0 &&  parseFloat(item.debe_c)>0){
                    $scope.list_egresos.push(item);

                    if(parseInt(item.idconciliacion)>0){ //tiene conciliacion
                        $scope.aux_clarificado_egresos+=parseFloat(item.debe_c);
                        $scope.aux_contclarificado_egresos+=1;
                    }else{
                        $scope.aux_no_clarificado_egresos+=parseFloat(item.debe_c);
                        $scope.aux_no_contclarificado_egresos+=1;
                    }
                }
            });
        }else{ // se busca las cuentas que aumentan por debe =  ingresos , y haber = egresos
            data.forEach(function(item){
                

                if(parseFloat(item.haber_c)==0 &&  parseFloat(item.debe_c)>0){
                    $scope.list_ingresos.push(item);

                    if(parseInt(item.idconciliacion)>0){//tiene conciliacion
                        $scope.aux_clarificado_ingresos+=parseFloat(item.debe_c);
                        $scope.aux_contclarificado_ingresos+=1;
                    }else{
                        $scope.aux_no_clarificado_ingresos+=parseFloat(item.debe_c);
                        $scope.aux_no_contclarificado_ingresos+=1;
                    }

                }else if(parseFloat(item.haber_c)>0 &&  parseFloat(item.debe_c)==0){
                    $scope.list_egresos.push(item);

                    if(parseInt(item.idconciliacion)>0){//tiene conciliacion
                        $scope.aux_clarificado_egresos+=parseFloat(item.haber_c);
                        $scope.aux_contclarificado_egresos+=1;
                    }else{
                        $scope.aux_no_clarificado_egresos+=parseFloat(item.haber_c);
                        $scope.aux_no_contclarificado_egresos+=1;
                    }
                }
            });
        }

        $scope.aux_conciliacion_egreso_ingreso = ((parseFloat($scope.aux_conciliacion_egreso_ingreso)) + (parseFloat($scope.aux_clarificado_ingresos)-parseFloat( $scope.aux_clarificado_egresos))).toFixed(4);
        $scope.aux_diferencia_conciliacion= (parseFloat($scope.aux_balance_final)-parseFloat($scope.aux_conciliacion_egreso_ingreso)).toFixed(4);
    };
    ///---
    
    $scope.proc_conciliacion=function(input,item,sumrest){
        var estado=true;
        if(sumrest==1){//Ingresos suma
            if($("#i"+input).prop('checked')){ //suma
                $scope.aux_conciliacion_egreso_ingreso=(parseFloat($scope.aux_conciliacion_egreso_ingreso))+(parseFloat(item.debe_c)+parseFloat(item.haber_c));

                $scope.aux_no_contclarificado_ingresos-=1;
                $scope.aux_contclarificado_ingresos+=1;

                $scope.aux_no_clarificado_ingresos-=(parseFloat(item.debe_c)+parseFloat(item.haber_c));
                $scope.aux_clarificado_ingresos+=(parseFloat(item.debe_c)+parseFloat(item.haber_c));

                estado=true;
            }else{ //resta
                $scope.aux_conciliacion_egreso_ingreso=(parseFloat($scope.aux_conciliacion_egreso_ingreso))-(parseFloat(item.debe_c)+parseFloat(item.haber_c));

                $scope.aux_no_contclarificado_ingresos+=1;
                $scope.aux_contclarificado_ingresos-=1;

                $scope.aux_no_clarificado_ingresos+=(parseFloat(item.debe_c)+parseFloat(item.haber_c));
                $scope.aux_clarificado_ingresos-=(parseFloat(item.debe_c)+parseFloat(item.haber_c));

                estado=false;
            }
        }else if(sumrest==2){ // Egresos resta
            if($("#e"+input).prop('checked')){//resta
                $scope.aux_conciliacion_egreso_ingreso=(parseFloat($scope.aux_conciliacion_egreso_ingreso))-(parseFloat(item.debe_c)+parseFloat(item.haber_c));

                $scope.aux_no_contclarificado_egresos-=1;
                $scope.aux_contclarificado_egresos+=1;

                $scope.aux_no_clarificado_egresos-=(parseFloat(item.debe_c)+parseFloat(item.haber_c));
                $scope.aux_clarificado_egresos+=(parseFloat(item.debe_c)+parseFloat(item.haber_c));

                estado=true;
            }else{//suma
                $scope.aux_conciliacion_egreso_ingreso=(parseFloat($scope.aux_conciliacion_egreso_ingreso))+(parseFloat(item.debe_c)+parseFloat(item.haber_c));
                $scope.aux_no_contclarificado_egresos+=1;
                $scope.aux_contclarificado_egresos-=1;

                $scope.aux_no_clarificado_egresos+=(parseFloat(item.debe_c)+parseFloat(item.haber_c));
                $scope.aux_clarificado_egresos-=(parseFloat(item.debe_c)+parseFloat(item.haber_c));

                estado=false;
            }
        }

        var data_concilia_desconcilia_cuenta={
            idregistrocontable:item.idregistrocontable,
            idconciliacion: $scope.aux_data_conciliacion.idconciliacion,
            select: estado
        };
        $scope.pre_conciliacion(data_concilia_desconcilia_cuenta);

        $scope.aux_diferencia_conciliacion=(parseFloat($scope.aux_balance_final)-parseFloat($scope.aux_conciliacion_egreso_ingreso)).toFixed(4);
    };
    $scope.pre_conciliacion=function(data){
        $http.get(API_URL + 'Conciliacion/conciliar_desconciliar/'+JSON.stringify(data))
            .success(function(response){
                if(parseInt(response)==1){
                    $("#alert").show();
                    setTimeout(function(){  $("#alert").hide(); },1000);
                }
        });
    };
    ///---
    $scope.close_conciliar=function(){
        if(parseFloat($scope.aux_diferencia_conciliacion)==0){
            $http.get(API_URL + 'Conciliacion/close_conciliacion/'+JSON.stringify($scope.aux_data_conciliacion))
            .success(function(response){
               console.log(response);
               if(parseInt(response)==1){
                $scope.Mensaje="Conciliacion finalizada";
                $("#msm").modal("show"); 

                $scope.clear();
                $scope.pageChanged(1);
                $scope.viewconciliacion=1;


               }else{
                $scope.Mensaje="Error al finalizar la conciliacion";
                $("#msm").modal("show");     

                $scope.clear();
                $scope.pageChanged(1);
                $scope.viewconciliacion=1;
               }
            });
        }else{
            $scope.Mensaje="La diferencia debe ser cero para finalizar la conciliacion";
            $("#msm").modal("show"); 
        }
    };
    $scope.clear=function(){
        $scope.list_ingresos=[];
        $scope.list_egresos=[];

        $scope.aux_no_clarificado_ingresos=0;
        $scope.aux_no_clarificado_egresos=0;

        $scope.aux_no_contclarificado_ingresos=0;
        $scope.aux_no_contclarificado_egresos=0;


        $scope.aux_clarificado_ingresos=0;
        $scope.aux_clarificado_egresos=0;

        $scope.aux_contclarificado_ingresos=0;
        $scope.aux_contclarificado_egresos=0;
    };
    ///--
    $scope.busquedaconciliacion="";
    $scope.cmb_estado="A";
    $scope.allConciliacion=[];
    $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };
    $scope.initLoad = function(pageNumber){
        var aux=false;
        if($scope.cmb_estado=="A"){
            aux=false;
        }else{
            aux=true;
        }
        var filtros = {
            search: $scope.busquedaconciliacion,
            estado: aux
        };
        $http.get(API_URL + 'Conciliacion/getAllFitros?page=' + pageNumber + '&filter=' + JSON.stringify(filtros))
            .success(function(response){
                $scope.allConciliacion = response.data;
                $scope.totalItems = response.total;
                console.log(response);
         });
    };
    $scope.initLoad(1);
    ///---
    $scope.reload_conciliacion=function(item) {
        $scope.viewconciliacion=2;
        $scope.aux_data_conciliacion=item;
        $scope.aux_balance_inicial=$scope.aux_data_conciliacion.balanceinicial;


        $scope.aux_balance_final=$scope.aux_data_conciliacion.balancefinal;

        $http.get(API_URL + 'Conciliacion/reload_conciliacion/'+JSON.stringify($scope.aux_data_conciliacion))
            .success(function(response){
                $scope.render_data_conciliacion(response);
        });
    };
    ///---
    $scope.anular_conciliacion=function (item) {
      $http.get(API_URL + 'Conciliacion/anular_conciliacion/'+JSON.stringify(item))
            .success(function(response){
             $scope.initLoad(1);   
        });  
    };
    ///---
    $scope.total_cuenta_conciliar=function(debe,haber){
        return $scope.formato_dinero((parseFloat(debe)+parseFloat(haber)).toFixed(4),"$");
    };

    ///---
    $scope.onlyNumber = function ($event, length, field) {

        if (length != undefined) {
            var valor = $('#' + field).val();
            if (valor.length == length) $event.preventDefault();
        }

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
    ///---
    $scope.formato_dinero=function(amount, signo){
        if(amount==""){
            return "";
        }
        amount += ''; // por si pasan un numero en vez de un string
        amount = parseFloat(amount.replace(/[^0-9\.-]/g, '')); // elimino cualquier cosa que no sea numero o punto
        // si es mayor o menor que cero retorno el valor formateado como numero
        amount = '' + amount.toFixed(4);
        var amount_parts = amount.split('.'),
            regexp = /(\d+)(\d{3})/;
        while (regexp.test(amount_parts[0]))
            amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');
        return signo+" "+amount_parts.join('.')
    };
});



function convertDatetoDB(now, revert){
    if (revert == undefined){
        var t = now.split('/');
        return t[2] + '-' + t[1] + '-' + t[0];
    } else {
        var t = now.split('-');
        return t[2] + '/' + t[1] + '/' + t[0];
    }
}

function now(){
    var now = new Date();
    var dd = now.getDate();
    if (dd < 10) dd = '0' + dd;
    var mm = now.getMonth() + 1;
    if (mm < 10) mm = '0' + mm;
    var yyyy = now.getFullYear();
    return dd + "\/" + mm + "\/" + yyyy;
}
function first(){
    var now = new Date();
    var yyyy = now.getFullYear();
    return "01/01/"+ yyyy;
}

function completarNumer(valor){
    if(valor.toString().length>0){
        var i=5;
        var completa="0";
        var aux_com=i-valor.toString().length;
        for(x=0;x<aux_com;x++){
            completa+="0";
        }
    }
    return completa+valor.toString();
}

function QuitarClasesMensaje() {
    $("#titulomsm").removeClass("btn-primary");
    $("#titulomsm").removeClass("btn-warning");
    $("#titulomsm").removeClass("btn-success");
    $("#titulomsm").removeClass("btn-info");
    $("#titulomsm").removeClass("btn-danger");
}
$(document).ready(function(){
    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'DD/MM/YYYY',
        ignoreReadonly: true
    });
    $("#alert").hide();
});