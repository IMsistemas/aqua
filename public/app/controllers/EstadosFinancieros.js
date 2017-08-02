app.controller('Contabilidad', function($scope, $http, API_URL) {
    $scope.GenerarPlanCuentasTipo="B"; //Por default selecciona estado de resultados
    $scope.FechaI=first(); //Cargar por default el primer dia del año actual
    $scope.FechaF=now();  // Cargar por default el dia actual 
    $scope.Mensaje="";  // Mensaje de validacion
    $scope.CuentasContables=[];//Cargar las cuentas contables

    $scope.TipoestadoF="E"; //Tipo estado financiero de cueta para agregar una madre
    $scope.ConceptoCCM=""; //concepto cuenta madre
    $scope.TipoCuenta=""; // tipo cuenta madre
    $scope.CodigoSRICCM=""; //codigo sri

    $scope.FechaIASC=now(); //Cargar por default el primer dia del año actual

    $scope.RegistroC=[];

    $scope.FechaRI=first(); //Cargar por default el primer dia del año actual
    $scope.FechaRF=now();  // Cargar por default el dia actual 

    $scope.EstadoSave="";

    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'DD/MM/YYYY',
        ignoreReadonly: true
    });

    ///---
    $scope.GenereraFiltroPlanCuentas=function(){
        var aux_fechai=$("#FechaI").val();
        var aux_fechaf=$("#FechaF").val();
        var FiltroCuentasC={
            Tipo : $scope.GenerarPlanCuentasTipo,
            FechaI: convertDatetoDB(aux_fechai),
            FechaF: convertDatetoDB(aux_fechaf)
        };
        $http.get(API_URL + 'estadosfinacieros/plancuentastipo/'+JSON.stringify(FiltroCuentasC))
        .success(function(response){
            console.log(response);
            $scope.CuentasContables=response;
        });
    };
    ///---
    $scope.AgregarCuentaMadre=function(){
        $("#AddCCMadre").modal("show");
    };
    ///---
    $scope.GuardarCCMadre=function(){
        if($scope.ConceptoCCM !=""){
            if($scope.TipoCuenta!=""){
                $scope.SendDataSaveCCMadre();
            }else{
                QuitarClasesMensaje();
                $("#titulomsm").addClass("btn-warning");
                $("#msm").modal("show");
                $scope.Mensaje="Seleccione un tipo de cuenta";    
            }
        }else{
            QuitarClasesMensaje();
            $("#titulomsm").addClass("btn-warning");
            $("#msm").modal("show");
            $scope.Mensaje="Agregre un concepto";
        }
    };
    $scope.SendDataSaveCCMadre=function(){
        var validacioncontrolhaber="+";
        switch($scope.TipoCuenta){
            case "A":
                validacioncontrolhaber="-";
                break;
            case "I":
                validacioncontrolhaber="-";
                break;
            default:
                validacioncontrolhaber="+";
                break;
        };

        var CuentaContable={
            concepto: $scope.ConceptoCCM,
            codigosri : $scope.CodigoSRICCM,
            tipoestadofinanz : $scope.TipoestadoF,
            estado: true,
            controlhaber: validacioncontrolhaber,
            jerarquia: "100000",
            tipocuenta: $scope.TipoCuenta
        };
        $http.post(API_URL+'Contabilidad',CuentaContable)
                .success(function (response) {
                    QuitarClasesMensaje();
                    $("#titulomsm").addClass("btn-success");
                    $scope.Mensaje="Se guardo correctamente";
                    $("#msm").modal("show");
                    $scope.GenereraFiltroPlanCuentas();
                    $scope.ClearCuentaMadre();
                    $("#AddCCMadre").modal("hide");
        });
    };
    $scope.ClearCuentaMadre=function(){
        $scope.TipoestadoF="E"; 
        $scope.ConceptoCCM=""; 
        $scope.TipoCuenta=""; 
        $scope.CodigoSRICCM=""; 
    };
    ///---
    $scope.aux_cuentaMadre={};
    $scope.AgregarCuentahija=function(cuenta){
        $scope.ClearCuentaMadre();
        $scope.aux_cuentaMadre=cuenta;
        $("#AddCCNodo").modal("show");
    };
    $scope.GuardarCCNodo=function(){
        if($scope.ConceptoCCM !=""){
            $scope.SendDataSaveCCnodo();
        }else{
            QuitarClasesMensaje();
            $("#titulomsm").addClass("btn-warning");
            $("#msm").modal("show");
            $scope.Mensaje="Agregre un concepto";
        }
    };
    $scope.SendDataSaveCCnodo=function(){
        var CuentaContable={
            concepto: $scope.ConceptoCCM,
            codigosri : $scope.CodigoSRICCM,
            tipoestadofinanz : $scope.aux_cuentaMadre.tipoestadofinanz,
            estado: true,
            controlhaber: $scope.aux_cuentaMadre.controlhaber,
            jerarquia: $scope.aux_cuentaMadre.jerarquia,
            tipocuenta: $scope.aux_cuentaMadre.tipocuenta
        };
        $http.post(API_URL+'Contabilidad',CuentaContable)
                .success(function (response) {
                    if(response!="Error"){
                        QuitarClasesMensaje();
                        $("#titulomsm").addClass("btn-success");
                        $scope.Mensaje="Se guardo correctamente";
                        $("#msm").modal("show");
                        $scope.GenereraFiltroPlanCuentas();
                        $scope.ClearCuentaMadre();
                        $("#AddCCNodo").modal("hide");
                    }else{
                        QuitarClasesMensaje();
                        $("#titulomsm").addClass("btn-danger");
                        $scope.Mensaje="No se puede agregar una subcuenta por que la cuenta contable tiene asientos contables asigandos";
                        $("#msm").modal("show");
                        $scope.GenereraFiltroPlanCuentas();
                        $scope.ClearCuentaMadre();
                        $("#AddCCNodo").modal("hide");
                    }
        });
        $scope.aux_cuentaMadre={};// limpiar aux cuenta madre
    };
    ///---
    $scope.ModificarCuentaC=function(cuenta){
        $("#ModifyCCNodo").modal("show");
        $scope.aux_cuentaMadre={
            idplancuenta:cuenta.idplancuenta,
            concepto : cuenta.concepto,
            codigosri : cuenta.codigosri,
            tipoestadofinanz : cuenta.tipoestadofinanz,
            estado : cuenta.estado,
            controlhaber : cuenta.controlhaber,
            jerarquia : cuenta.jerarquia,
            tipocuenta : cuenta.tipocuenta
        };
        $scope.ConceptoCCM=$scope.aux_cuentaMadre.concepto;
        $scope.CodigoSRICCM=$scope.aux_cuentaMadre.codigosri;
    };
    $scope.GuardarModificacionNodo=function(){
        if($scope.ConceptoCCM !=""){
            $scope.SendDataSaveModifyCCnodo();
        }else{
            QuitarClasesMensaje();
            $("#titulomsm").addClass("btn-warning");
            $("#msm").modal("show");
            $scope.Mensaje="Agregre un concepto";
        }
    };
    $scope.SendDataSaveModifyCCnodo=function(){
        $scope.aux_cuentaMadre.concepto=$scope.ConceptoCCM;
        $scope.aux_cuentaMadre.codigosri=$scope.CodigoSRICCM;
        $http.put(API_URL+'Contabilidad/'+$scope.aux_cuentaMadre.idplancuenta,$scope.aux_cuentaMadre)
                .success(function (response) {
                    QuitarClasesMensaje();
                    $("#titulomsm").addClass("btn-success");
                    $scope.Mensaje="Se guardo correctamente";
                    $("#msm").modal("show");
                    $scope.GenereraFiltroPlanCuentas();
                    $scope.ClearCuentaMadre();
                    $("#ModifyCCNodo").modal("hide");
        });
        $scope.aux_cuentaMadre={};// limpiar aux cuenta madre
    };
    ///---
    $scope.BorrarCuentaC=function(cuenta){
        $scope.aux_cuentaMadre=cuenta;
        $("#msmBorarCC").modal("show");
    };
    $scope.okBorrarCuenta=function(){
        $http.get(API_URL + 'estadosfinacieros/borrarcuenta/'+JSON.stringify($scope.aux_cuentaMadre))
        .success(function(response){
            if(response=="Ok"){
                QuitarClasesMensaje();
                $("#titulomsm").addClass("btn-success");
                $scope.Mensaje="Se borro correctamente";
                $("#msm").modal("show");
                $scope.GenereraFiltroPlanCuentas();
                $scope.ClearCuentaMadre();
                $("#msmBorarCC").modal("hide");
            }else{
                QuitarClasesMensaje();
                $("#titulomsm").addClass("btn-danger");
                if(response=="Error1"){
                    $scope.Mensaje="La cuenta seleccionada tiene subcuentas";
                }else{
                    $scope.Mensaje="La cuenta seleccionada tiene asientos contables";
                }
                $("#msmBorarCC").modal("hide");
                $("#msm").modal("show");
            }
        });
    };
    ///---
    $scope.NumeroIASC=1;
    $scope.AddAsientoContable=function(){
        $("#AddAsc").modal("show");
        $scope.EstadoSave="N";
        $scope.LoadTipoTransaccion();
        $scope.ClearAsientoContable();
        $scope.AddIntemCotable();
        $scope.AddIntemCotable();
    };
    ///---
    $scope.NumeroComprobante=function(){
        if($scope.tipotransaccion!=""){
            var aux_filtronumero={
                idtipotransaccion: $scope.tipotransaccion
            };
            $http.get(API_URL + 'estadosfinacieros/numcomp/'+JSON.stringify(aux_filtronumero))
            .success(function(response){
                if(parseInt(response)>0){
                    $scope.NumeroIASC=parseInt(response);
                }else{
                    $scope.NumeroIASC=1;
                }
            });
        }else{
            $scope.NumeroIASC=1;
        }
    };
    ///---
    $scope.AddIntemCotable=function(){
        var item={
            idplancuenta:"",
            aux_jerarquia:"",
            concepto:"",
            controlhaber:"",
            tipocuenta:'',
            Debe:0,
            Haber:0,
            Descipcion:""
        };
        $scope.RegistroC.push(item);
    };
    ///---
    $scope.BorrarFilaAsientoContable=function(item){
        var posicion= $scope.RegistroC.indexOf(item);
         $scope.RegistroC.splice(posicion,1);
         $scope.SumarDebeHaber();
    };
    ///---
    $scope.listatipotransaccion=[];
    $scope.tipotransaccion="";
    $scope.LoadTipoTransaccion=function(){
      $http.get(API_URL + 'transacciones/alltipotransacciones')
        .success(function(response){
            $scope.listatipotransaccion=response;
        });  
    };
    ///---
    $scope.aux_plancuentas=[];
    $scope.aux_cuentabuscar={};
    $scope.BuscarCuentaContable=function(registro){
        $scope.aux_cuentabuscar=registro;
        $("#PlanContable").modal("show");
        $http.get(API_URL + 'estadosfinacieros/plancontabletotal')
        .success(function(response){
            $scope.aux_plancuentas=response;
        });
    };
    ///---
    $scope.AsignarCuentaContable=function(cuenta){
        $scope.aux_cuentabuscar.idplancuenta=cuenta.idplancuenta;
        $scope.aux_cuentabuscar.aux_jerarquia=cuenta.aux_jerarquia;
        $scope.aux_cuentabuscar.concepto=cuenta.concepto;
        $scope.aux_cuentabuscar.controlhaber=cuenta.controlhaber;
        $scope.aux_cuentabuscar.tipocuenta=cuenta.tipocuenta;
        $("#PlanContable").modal("hide");
        $scope.FiltraCuenta="";
    };
    ///---
    $scope.AsientoContable=function(){

        if($scope.tipotransaccion!=""){
            if($scope.NumeroIASC!=""){
                if($scope.ValidaRegistriContable()==true){
                    if($scope.ValidaDebeHaber()==true){
                        $scope.ProcesarDatosAsientoContable();
                    }else{
                        QuitarClasesMensaje();
                        $("#titulomsm").addClass("btn-warning");
                        $scope.Mensaje="El Debe no es igual a el Haber";
                        $("#msm").modal("show");    
                    }
                }else{
                    QuitarClasesMensaje();
                    $("#titulomsm").addClass("btn-warning");
                    $scope.Mensaje="Transacción contable erronea";
                    $("#msm").modal("show");    
                }
            }else{
                QuitarClasesMensaje();
                $("#titulomsm").addClass("btn-warning");
                $scope.Mensaje="Ingrese un número de egreo o ingreso";
                $("#msm").modal("show");
            }
        }else{
            QuitarClasesMensaje();
            $("#titulomsm").addClass("btn-warning");
            $scope.Mensaje="Seleccione un tipo de transacción";
            $("#msm").modal("show");
        }
    };
    ///---
    $scope.ProcesarDatosAsientoContable=function () {
        $("#procesarinfomracion").modal("show");
        if($scope.EstadoSave=="N"){
            var aux_fecha=convertDatetoDB($("#FechaIASC").val());
            var Transaccion={
                fecha: aux_fecha,
                idtipotransaccion: $scope.tipotransaccion,
                numcomprobante: $scope.NumeroIASC,
                descripcion: $scope.DescripcionASC
            };

            var Contabilidad={
                transaccion: Transaccion,
                registro: $scope.RegistroC
            };
            $http.get(API_URL + 'estadosfinacieros/asc/'+JSON.stringify(Contabilidad))
            .success(function(response){
               if(!isNaN(response)){
                    QuitarClasesMensaje();
                    $("#procesarinfomracion").modal("hide");
                    $("#titulomsm").addClass("btn-success");
                    $scope.Mensaje="Se Guardo Correctamente";
                    $("#AddAsc").modal("hide");
                    $("#msm").modal("show");
                    $scope.ClearAsientoContable();
               }else{
                    QuitarClasesMensaje();
                    $("#titulomsm").addClass("btn-danger");
                    $scope.Mensaje="Error Al Guardar El Asiento Contable";
                    $("#msm").modal("show");
               }
               $scope.EstadoSave="";
            });
        }
        if($scope.EstadoSave=="M"){
            var aux_fecha=convertDatetoDB($("#FechaIASC").val());
            $scope.auxTransaccionEditar.idtipotransaccion=$scope.tipotransaccion;
            $scope.auxTransaccionEditar.fechatransaccion=aux_fecha;
            $scope.auxTransaccionEditar.numcomprobante=$scope.NumeroIASC;
            $scope.auxTransaccionEditar.descripcion=$scope.DescripcionASC;
            var Contabilidad={
                transaccion: $scope.auxTransaccionEditar,
                registro: $scope.RegistroC
            };
            
            $http.get(API_URL + 'estadosfinacieros/Editarasc/'+JSON.stringify(Contabilidad))
            .success(function(response){
               if(!isNaN(response)){
                    QuitarClasesMensaje();
                    $("#procesarinfomracion").modal("hide");
                    $("#titulomsm").addClass("btn-success");
                    $scope.Mensaje="Se Guardo Correctamente";
                    $("#AddAsc").modal("hide");
                    $("#msm").modal("show");
                    $scope.ClearAsientoContable();
               }else{
                    QuitarClasesMensaje();
                    $("#titulomsm").addClass("btn-danger");
                    $scope.Mensaje="Error Al Guardar El Asiento Contable";
                    $("#msm").modal("show");
               }
               $scope.EstadoSave="";
            });
        }
    };
    ///---
    $scope.ClearAsientoContable=function(){
        $scope.tipotransaccion="";
        $scope.NumeroIASC=1;
        $scope.DescripcionASC="";
        $scope.RegistroC=[];
        $scope.aux_sumdebe=0;
        $scope.aux_sumhaber=0;
        $scope.aux_sumdebedif=0;
        $scope.aux_sumhaberdif=0;
    };
    ///---
    $scope.aux_sumdebe=0.0;
    $scope.aux_sumhaber=0.0;
    
    $scope.aux_sumdebedif=0.0;
    $scope.aux_sumhaberdif=0.0;
    $scope.SumarDebeHaber=function() {
        var aux_debe=0.0;
        var aux_haber=0.0;

        var aux_debedif=0.0;
        var aux_haberdif=0.0;
        for(x=0;x<$scope.RegistroC.length;x++){
            if($scope.RegistroC[x].Debe!="") aux_debe+=parseFloat($scope.RegistroC[x].Debe);
            if($scope.RegistroC[x].Haber!="") aux_haber+=parseFloat($scope.RegistroC[x].Haber);
        }
       /* $scope.aux_sumdebe=aux_debe;
        $scope.aux_sumhaber=aux_haber;

        aux_debedif=aux_debe-aux_haber;
        aux_haberdif=aux_haber-aux_debe;
        
        $scope.aux_sumdebedif=aux_debedif;
        $scope.aux_sumhaberdif=aux_haberdif;*/ 

        $scope.aux_sumdebe=aux_debe.toFixed(4);
        $scope.aux_sumhaber=aux_haber.toFixed(4);

        aux_debedif=aux_debe-aux_haber;
        aux_haberdif=aux_haber-aux_debe;
        
        $scope.aux_sumdebedif=aux_debedif.toFixed(4);
        $scope.aux_sumhaberdif=aux_haberdif.toFixed(4);       

    };
    ///---
    $scope.ValidaRegistriContable=function(){
        var aux_validacion=0;
        for(x=0;x<$scope.RegistroC.length;x++){
            if($scope.RegistroC[x].concepto!=""){
                if(($scope.RegistroC[x].Debe!="0" & $scope.RegistroC[x].Haber=="0") || ($scope.RegistroC[x].Debe=="0" & $scope.RegistroC[x].Haber!="0") ){
                     aux_validacion=1;
                }else{
                    aux_validacion=0;
                    return false;
                }
            }else{
                aux_validacion=0;
                return false;
            }
        }
        if(aux_validacion==1) return true;
    };
    ///---
    $scope.ValidaDebeHaber=function(){
        var aux_debe=0.0;
        var aux_haber=0.0;
        for(x=0;x<$scope.RegistroC.length;x++){
            if($scope.RegistroC[x].Debe!="") aux_debe+=parseFloat($scope.RegistroC[x].Debe);
            if($scope.RegistroC[x].Haber!="") aux_haber+=parseFloat($scope.RegistroC[x].Haber);
        }
        if(aux_debe==aux_haber){
            return true;
        }else{
            return false;
        }
    };
    ///---
    $scope.RegistroCuentaContable=[];
    $scope.aux_CuentaContableSelc={};
    $scope.EstadoAsc="Ac";
    $scope.RegistroContableCuenta=function(cuenta) {
        $scope.aux_CuentaContableSelc=cuenta;
        $scope.LoadRegistroCuenta();
    };
    ///---
    $scope.aux_total_debe=0;
    $scope.aux_total_haber=0;
    $scope.aux_total_saldo=0;
    $scope.LoadRegistroCuenta=function() {
        $scope.aux_total_debe=0;
        $scope.aux_total_haber=0;
        $scope.aux_total_saldo=0;
        if($scope.aux_CuentaContableSelc.idplancuenta!=undefined){
            var aux_estado=$("#EstadoAsc").val();
            var estado='true';
            if(aux_estado!="Ac"){
                estado='false';
            }
            $("#procesarinfomracion").modal("show");
            var aux_fechai=$("#FechaRI").val();
            var aux_fechaf=$("#FechaRF").val();
            var filtroregistro={
                idplancuenta: $scope.aux_CuentaContableSelc.idplancuenta,
                controlhaber: $scope.aux_CuentaContableSelc.controlhaber,
                Fechai: convertDatetoDB(aux_fechai),
                Fechaf: convertDatetoDB(aux_fechaf),
                Estado: estado
            };
          $http.get(API_URL + 'estadosfinacieros/registrocuenta/'+JSON.stringify(filtroregistro))
            .success(function(response){
                //console.log(response);
                $scope.RegistroCuentaContable=response;
                $scope.RegistroCuentaContable.forEach(function(item){
                    $scope.aux_total_debe+=parseFloat(item.debe_c);
                    $scope.aux_total_haber+=parseFloat(item.haber_c);
                    $scope.aux_total_saldo+=parseFloat(item.saldo);
                });
                $scope.aux_total_debe=$scope.aux_total_debe.toFixed(4);
                $scope.aux_total_haber=$scope.aux_total_haber.toFixed(4);
                $scope.aux_total_saldo=$scope.aux_total_saldo.toFixed(4);
                $("#procesarinfomracion").modal("hide");
            });
        }else{
                $("#procesarinfomracion").modal("hide");
                QuitarClasesMensaje();
                $("#titulomsm").addClass("btn-info");
                $scope.Mensaje="Seleccione Una Cuenta Contable";
                $("#msm").modal("show");
        }
        
    };
    ///---
    $scope.ProcesoModificarAsientoCt=function(registro) {
        /// Por definir edicion
        $("#AddAsc").modal("show");
        $scope.DatosModificarAsientoCt(registro.idtransaccion);
        $scope.EstadoSave="M";
    };
    ///---
    $scope.auxTransaccionEditar={};
    $scope.DatosModificarAsientoCt=function(id_transaccion){
        $scope_auxTransaccionEditar={};
       $http.get(API_URL + 'estadosfinacieros/datosasc/'+id_transaccion)
        .success(function(response){
            $scope.auxTransaccionEditar={
                idtransaccion: response[0].idtransaccion,
                idtipotransaccion : response[0].idtipotransaccion,
                numcontable: response[0].numcontable,
                fechatransaccion: response[0].fechatransaccion,
                numcomprobante: response[0].numcomprobante,
                descripcion : response[0].descripcion
            };
            $scope.RenderRegistroContble(response[0].cont_registrocontable);
        }); 
    };
    ///---
    $scope.RenderRegistroContble=function(registro){
        //$("#tipotransaccion").val($scope.auxTransaccionEditar.idtipotransaccion);
        
        $scope.tipotransaccion=String($scope.auxTransaccionEditar.idtipotransaccion);
        $("#FechaIASC").val(convertDatetoDB($scope.auxTransaccionEditar.fechatransaccion,"true"));
        $scope.NumeroIASC=$scope.auxTransaccionEditar.numcomprobante;
        $scope.DescripcionASC=$scope.auxTransaccionEditar.descripcion;
            

        $scope.RegistroC=[];
        for(x=0;x<registro.length;x++){
            var item={
                idplancuenta:registro[x].cont_plancuentas.idplancuenta,
                concepto: registro[x].cont_plancuentas.concepto,
                aux_jerarquia: $scope.orden_plan_cuenta(registro[x].cont_plancuentas.jerarquia),
                controlhaber: registro[x].cont_plancuentas.controlhaber,
                tipocuenta: registro[x].cont_plancuentas.tipocuenta,
                Debe:  parseFloat(registro[x].debe),
                Haber: parseFloat(registro[x].haber),
                Descipcion: registro[x].descripcion
            };
            $scope.RegistroC.push(item);
        }
        $scope.SumarDebeHaber();
    };

    $scope.orden_plan_cuenta=function(orden){
        var aux_orden=orden.split(".");
        var aux_numero_orden="";
        var aux_numero_completar="";
        var tam=aux_orden.length;
        if(tam>0){
            for(var x=0;x<tam;x++){
                if(x<3){
                    aux_numero_orden+=aux_orden[x];
                }else if(x>=3){
                    if(x==3){
                        aux_numero_completar=aux_orden[x];
                        if( aux_numero_completar.length==1){
                            aux_numero_completar="0"+aux_numero_completar;
                        }
                        aux_numero_orden+=aux_numero_completar;
                    }else if(x>3){
                        aux_numero_orden+=aux_orden[x];
                    }

                }
            }
        }else{
           aux_numero_orden=orden; 
        }
        return aux_numero_orden;
    };
    ///--- 
    $scope.aux_registroBorrar={};
    $scope.ProcesoBorrarAsientoCt=function(registro) {
        /// Por definir edicion
        $scope.aux_registroBorrar=registro;
        $("#BorraTransaccion").modal("show");
    };
    ///---
    $scope.ConfirmarBorrarTransaccion=function(){
        $("#BorraTransaccion").modal("hide");
        $scope.BorrarAsientoContable($scope.aux_registroBorrar.idtransaccion);
    };
    ///---
    $scope.BorrarAsientoContable=function (id_transaccion) {
        $http.get(API_URL + 'estadosfinacieros/borrarasc/'+id_transaccion)
        .success(function(response){
            if(response.success==true){
                QuitarClasesMensaje();
                $("#titulomsm").addClass("btn-success");
                $scope.Mensaje="Se Anulo Correctamente El Asiento Contable";
                $("#msm").modal("show");
                $scope.LoadRegistroCuenta();
            }else{
                QuitarClasesMensaje();
                $("#titulomsm").addClass("btn-danger");
                $scope.Mensaje="Error Al Anular El Asiento Contable";
                $("#msm").modal("show");
            }
        });
    };
    ///---
    $scope.formato_dinero=function(amount, signo){
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

    ///---
    $scope.print_asc=function(item) {
        console.log(item.cont_transaccion.idtransaccion);
        $scope.transaccion={
            idtransaccion:item.cont_transaccion.idtransaccion
        };
        var accion = API_URL + "estadosfinacieros/print_asc/"+JSON.stringify($scope.transaccion);
        $("#WPrint_head").html("Asiento Contable");
        $("#WPrint").modal("show");
        $("#bodyprint").html("<object width='100%' height='600' data='"+accion+"'></object>"); 

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
});