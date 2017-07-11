
	app.controller('ActivosFijosController', function ($scope,$http,API_URL,Upload) {
			
		
			
			$scope.deshabilitarlinea= true; 
			//$scope.deshabilitarsublinea= true;		
			/*$scope.guardar= true;*/
			$scope.modalstate ='';
			$scope.warning = false;
		
			$scope.id = 0;



			$scope.GetTipoItem   = function () {

				$http.get(API_URL + 'Activosfijos/getactivosfijos').success(function (response) {

				$scope.tipoItem=response[2].nameclaseitem;
				$scope.idTipoItem=response[2].idclaseitem;
				
			
			});
			}

			$scope.GetCategorias = function () {
					$http.get(API_URL + 'Activosfijos/getCategorias').success(function (response) {
                    $scope.categorias = response;

				});	
			}


			$scope.GetTipoIva  = function () {
				$http.get(API_URL + 'Activosfijos/getTipoIva').success(function (response) {
				$scope.TipoIva=response;
		
			});
			}

	
			$scope.GetTipoIce = function () {
				$http.get(API_URL + 'Activosfijos/getTipoIce').success(function (response) {
				
				$scope.TipoIce=response;

				$scope.TipoIce.push({nametipoimpuestoice: 'No posee impuesto Ice', idtipoimpuestoice: '' });

			
			});
			}


			$scope.showPlanCuenta = function () {
				$http.get(API_URL + 'Activosfijos/getPlanCuentas').success(function (response) {
				$scope.PlanCuentas=response;
				$('#modalCuentas').modal('show');
				
			});	
			}

			$scope.showPlanCuentaedit = function () {
				$http.get(API_URL + 'Activosfijos/getPlanCuentas').success(function (response) {
				$scope.PlanCuentas=response;
				$('#modalCuentasedit').modal('show');
				
			});	
			}

			  $scope.click_radio = function (cuentas) {
       			 $scope.select_cuenta = cuentas.idplancuenta;
       			 $scope.cuenta_contable=cuentas.concepto;
       			 $scope.id_cuenta_contable=cuentas.idplancuenta;
       			 $('#modalCuentas').modal('hide');

   			};


				$scope.habilitarLinea = function () {

					if ($scope.categoria) {

						$scope.deshabilitarlinea = false;
						$http.get(API_URL + 'Activosfijos/getLinea/' + $scope.categoria).success(function (response) {
						$scope.GetLinea = response;
				
					});	

					}else{

						$scope.deshabilitarlinea = true; 	
						$scope.linea="";	
					}
					} 				

				$scope.GuardarActivoFijo = function () {

					console.log($scope.edit);

					if ($scope.ice_item == "") {

						$scope.ice_item = undefined;
					}else{
						ice: $scope.ice_item;
					}

				if($scope.foto == null && $scope.id_cuenta_contable != ''){

					var data = {

						codigoitem: $scope.codigoItem,
						detalleitem: $scope.detalleItem,
						claseitem: $scope.claseItem,
						categoria: $scope.categoria,
						iva_item: $scope.iva_item,
						ice: $scope.ice_item,
						id_cuenta: $scope.id_cuenta_contable

					}	


				}else if($scope.foto != null && $scope.id_cuenta_contable != ''){


					var name = $scope.foto.name;
					var nom = Date.now();


					var data = {

						codigoitem: $scope.codigoItem,
						detalleitem: $scope.detalleItem,
						claseitem: $scope.claseItem,
						categoria: $scope.categoria,
						iva_item: $scope.iva_item,
						foto : $scope.foto,
						nombre_foto : nom+name,
						ice: $scope.ice_item,
						id_cuenta: $scope.id_cuenta_contable

					}

					
					}else if($scope.foto == null && $scope.id_cuenta_contable == ''){


					var data = {

						codigoitem: $scope.codigoItem,
						detalleitem: $scope.detalleItem,
						claseitem: $scope.claseItem,
						categoria: $scope.categoria,
						iva_item: $scope.iva_item,
						ice: $scope.ice_item,
						id_cuenta: undefined
					}
					
						
				}else if($scope.foto != null && $scope.id_cuenta_contable == ''){

					var name = $scope.foto.name;
					var nom = Date.now();

					var data = {

						codigoitem: $scope.codigoItem,
						detalleitem: $scope.detalleItem,
						claseitem: $scope.claseItem,
						categoria: $scope.categoria,
						iva_item: $scope.iva_item,
						foto : $scope.foto,
						nombre_foto : nom+name,
						ice: $scope.ice_item,
						id_cuenta: undefined

				}

			}


				if ($scope.edit==0) {
						
					console.log(data);
						Upload.upload({
						url:API_URL + 'Activosfijos/guardaractivosfijos', 
						method:'POST',
						data: data

					}).success(function(data,status,headers,config) {
						
						$('#modalMensaje').modal('show');
						$scope.mensaje= "El Activo Fijo se a guardado satisfactoriamente...";
						setTimeout("$('#modalMensaje').modal('hide')", 2000);
						setTimeout("$('#addactivofijo').modal('hide')", 1000);
						$scope.ListarActivosFijos();
						console.log("se guardó");
					});

				}else{
						
					console.log(data);
						Upload.upload({
						url:API_URL + 'Activosfijos/actualizaractivosfijos/' + $scope.id + data

					}).success(function(data) {
						console.log(data);
						$('#modalMensaje').modal('show');
						$scope.mensaje= "El Activo Fijo se a guardado satisfactoriamente...";
						setTimeout("$('#modalMensaje').modal('hide')", 2000);
						setTimeout("$('#addactivofijo').modal('hide')", 1000);
						$scope.ListarActivosFijos();
						console.log("se edito");
					});



				}
		}


			$scope.cancelar =function() {
				$('#addactivofijo').modal('hide');
			}


			$scope.addactivofijo1 = function (modalstate, id) {
		
				if (modalstate == 'add') {

					$scope.edit = 0;		
					console.log($scope.edit);

					$scope.fotoEdit = "img/activofijo.jpeg";
					
					$scope.title= "Guardar Activo Fijo";
					$('#FromActivosFijos')[0].reset();
					$('#addactivofijo').modal('show');
					$scope.GetTipoItem();	
				}

				if (modalstate == 'edit') {

					$scope.edit = 1;

					console.log($scope.edit);
					
					$scope.id = id;

					$('#addactivofijo').modal('show');

					$scope.deshabilitarlinea = false; 
					//$scope.deshabilitarsublinea= false;	

					$scope.title="Editar Datos del Activo Fijo";
					
					$http.get(API_URL + 'Activosfijos/showactivofijo/' + id).success(function (response) {	

					var ArrayTempCategoria = [{nombrecategoria: response[0][0].nombrecategoria, idcategoria:response[0][0].jerarquia}];
					var ArrayTempLinea     = [{nombrecategoria: response[1][0].nombrecategoria, idcategoria:response[1][0].jerarquia}];
					//var ArrayTempSubLinea  = [{nombrecategoria:  response[2][0].nombrecategoria, idcategoria:response[0][0].idsublinea}];
					var ArrayTempTipoIva   = [{nametipoimpuestoiva: response[0][0].nametipoimpuestoiva, idtipoimpuestoiva:response[0][0].idtipoimpuestoiva}];
					var ArrayTempTipoIce   = [{nametipoimpuestoice: response[0][0].nametipoimpuestoice, idtipoimpuestoice:response[0][0].idtipoimpuestoice}];
				
					$scope.GetTipoItem();
					$scope.codigoItem  = response[0][0].codigoproducto;					
					$scope.detalleItem = response[0][0].nombreproducto;					
					$scope.categorias  = ArrayTempCategoria;
					$scope.GetLinea    = ArrayTempLinea;
					$scope.idlinea     = response[0][0].idlinea;
					//$scope.GetSubLinea = ArrayTempSubLinea;
					$scope.TipoIva     = ArrayTempTipoIva;
					$scope.fotoEdit = "imgActivosFijos/"+response[0][0].foto;
			
					
					if (response[0][0].idplancuenta == null && response[0][0].idtipoimpuestoice == null) {
						$scope.cuenta_contable ='No posee cuenta contable';
						$scope.id_cuenta_contable ='';
						
						$scope.TipoIce = [{nametipoimpuestoice:"No posee impuesto Ice", idtipoimpuestoice:''}];
					}
					else if (response[0][0].idplancuenta != null && response[0][0].idtipoimpuestoice != null) {
						$scope.TipoIce     = ArrayTempTipoIce;
						$scope.cuenta_contable=response[0][0].concepto;
						$scope.id_cuenta_contable = response[0][0].idplancuenta;
					}
					else if (response[0][0].idplancuenta != null && response[0][0].idtipoimpuestoice== null) {
						$scope.TipoIce = [{nametipoimpuestoice:"No posee impuesto Ice", idtipoimpuestoice : ''}];
						$scope.cuenta_contable=response[0][0].concepto;
						$scope.id_cuenta_contable = response[0][0].idplancuenta;
					}
					else if(response[0][0].idplancuenta == null && response[0][0].idtipoimpuestoice != null) {
						$scope.cuenta_contable='No posee cuenta contable';
						$scope.id_cuenta_contable = '';
						
						$scope.TipoIce = ArrayTempTipoIce;
					}
	
				});

				}	
	
			}	



			$scope.showModalConfirm = function (activo) {
				$scope.ActivoFijo = activo.codigoproducto;
				$scope.IdActivoFijo = activo.idcatalogitem;
				$scope.Idcatal= activo.iditemactivofijo;
				$scope.NomImg = activo.foto;
				$('#modalConfirmDelete').modal('show');
			}

			$scope.DeleteActivoFijo = function (iditemactfijo,idcatitem) {
					
				$http.get(API_URL + 'Activosfijos/deleteactivofijo/' +$scope.Idcatal+ '/' +$scope.IdActivoFijo+ '/' +$scope.NomImg).success(function (response) {
					console.log($scope.IdActivoFijo, $scope.Idcatal);
					$scope.ListarActivosFijos();
					$('#modalConfirmDelete').modal('hide');
					setTimeout("$('#modalMensajedelete').modal('show')",1000);
					$scope.mensajeDelete= "El Activo Fijo se ha borrado satisfactoriamente...";
					setTimeout("$('#modalMensajedelete').modal('hide')",3000);
				});
			}


			$scope.ListarActivosFijos = function() {
				
				$http.get(API_URL + 'Activosfijos/getAllActivosfijos').success(function (response) {
					$scope.activofijo = response;

				});
			}

			$scope.buscar = function () {

				$scope.palabra = $scope.busqueda;

				if ($scope.palabra  == "") {
					$scope.ListarActivosFijos();
					$scope.warning = false;
				}

				$http.get(API_URL + 'Activosfijos/getAllActivosfijosfiltrados/' + $scope.palabra).success(function (response1) {
					
					$scope.activofijo = response1;
						
					if ($scope.activofijo=="") {
						$scope.warning = true;					
					}else{
						$scope.warning = false;
					}

				});
			}

			$scope.codigo = function () {

				$scope.codigox = $scope.codigoItem;

				$http.get(API_URL + 'Activosfijos/getAllActivosfijoscodigo/' + $scope.codigox).success(function (response) {
						
					for (var i=0; i < response.length; i++) {

						if(response[i].codigoproducto != $scope.codigox){
							$scope.FromActivosFijos.$invalid = false;
							$scope.error = false;
							console.log(response[i].codigoproducto );
						

						}
						
						else if (response[i].codigoproducto == $scope.codigox) {
					
							$scope.error=true;
								console.log(response[i].codigoproducto );
								
								break;
							
						}
						
					}		

				});

			}

	});
	

