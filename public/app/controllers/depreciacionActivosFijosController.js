	app.controller('depreciacionActivosFijosController', function ($scope,$http,API_URL,Upload) {
						
	$scope.CampoIncidencia = [];
	$scope.CampoMantencion = [];
	$scope.CamposTraslado = [];
	$scope.MensajeSinRegistrosIncidencias=false;
	$scope.MensajeSinRegistrosMantenciones= false;
	$scope.MensajeSinRegistrosTraslados= false;
	$scope.CamposIncidencias= false;
	$scope.BotonDepreciacion= true;

//obtener la fecha actual
	var hoy = new Date();
	var dd =hoy.getDay();
	var mm = hoy.getMonth()+1; 
	var mm2 = hoy.getMonth()+2; 
	var yyyy = hoy.getFullYear();
	var FechaDepreciacion = $scope.DiaDepreciacion+'-0'+mm2+'-'+yyyy;

	//Formatear la fecha
	var DiaActual = yyyy+'-'+mm+'-'+dd;
	var DiasDelMes = 30;

	var Transaccion = {
		 fecha: DiaActual,												// fecha que se realiza la transaccion con el formato que esta
		 idtipotransaccion: 1,											// preguntale a gilberto que tipo de transaccion es una depreciacion
		 numcomprobante: 1,												// dejalo asi como esta
		 descripcion: 'Registro de Asiento Contable depreciación'		// cualquier texto	
	};

	var dataDerepciado={

		depreciado : 1

	}

	$scope.VerificarUltimaDepreciacion= function () {	

			$http.get(API_URL+ 'Activosfijos/ObtenerUltimaDepreciacion').success(function (responsefecha) {
				

				if (responsefecha == 0 ) {

					$scope.BotonDepreciacion= false;

				}else{

					var FechaUltimaDepreciacion = responsefecha[0].fecha;

					var ArrayAnoMes = FechaUltimaDepreciacion.split("-");

					var AnoMes = ArrayAnoMes[0] +'-'+ ArrayAnoMes[1];

					$scope.DiaDepreciacion  =  ArrayAnoMes[2];

					console.log(AnoMes);

					var DiaActual = yyyy+'-0'+mm;

					if (AnoMes ==  DiaActual) {


						$scope.BotonDepreciacion= true;
						$('#palabraejecutar').html("El <b>"+$scope.DiaDepreciacion+'-0'+mm2+'-'+yyyy+"</b> se debrá ejecutar la proxima depreciación");
						$('#iconok').removeClass('glyphicon glyphicon glyphicon-ok');
						$('#iconok').addClass('glyphicon glyphicon-calendar');
						
					}
					else{

						$scope.BotonDepreciacion= false;
						$('#palabraejecutar').html("Ejecutar Depreciación");
						$('#iconok').removeClass('glyphicon glyphicon-calendar');
						$('#iconok').addClass('glyphicon glyphicon glyphicon-ok');
						
					}

				}

			});

		}

		$scope.GetAllActivosFijos = function () {
			console.log("hola");

			$scope.VerificarUltimaDepreciacion();
			//quitar la clase de la imagen y la palabra del boton actualizar y colocar la imagen animada y la palabra Actualizando...

				$('#icon').removeClass('glyphicon glyphicon glyphicon-refresh');
				$('#icon').addClass('fa fa-refresh fa-spin fa-fw');
				$('#palabra').html('Actualizando...')

			//Mostrar todas las compras que solo sean activo fijos

			$http.get(API_URL + 'Activosfijos/AllActivosfijosAlta').success(function (response) {

			//quitar la clase de la imagen animada, la palabra Actualizando... y colocar la imagen estatica del boton y la palabra actualizar

				$('#icon').removeClass('fa fa-refresh fa-spin fa-fw');
				$('#icon').addClass('glyphicon glyphicon glyphicon-refresh');
				$('#palabra').html('Actualizar');

				
				$http.get(API_URL + 'Activosfijos/AllActivosfijosSinAlta').success(function (response2) {

					var arrayWithDuplicates = response.concat(response2);

					function removeDuplicates(originalArray, prop) {
					     var newArray = [];
					     var lookupObject  = {};

					     for(var i in originalArray) {
					        lookupObject[originalArray[i][prop]] = originalArray[i];
					     }

					     for(i in lookupObject) {
					         newArray.push(lookupObject[i]);
					     }
					      return newArray;
					 }

					var uniqueArray = removeDuplicates(arrayWithDuplicates, "iditemcompra");

					$scope.AllActivosFijos = uniqueArray;

					$scope.actualizarcampobaja = function (iddetalleitemactivofijo) {
									
						var data = {

							baja : 1

						}

						$http.post(API_URL + 'Activosfijos/ActualizarCampoBaja/'+ iddetalleitemactivofijo, data)
					}

					
					$scope.EjecuatarDepreciacion = function () {

						$('#iconok').removeClass('glyphicon glyphicon glyphicon-refresh');
						$('#iconok').addClass('fa fa-refresh fa-spin fa-fw');
						$('#palabraejecutar').html('Depreciando Activos fijos...')

						$scope.BotonDepreciacion= true;

						//-----------------------CALCULO DEPRECIACION ACTIVOS FIJO QUE SE HAN DEPRECIADO AL MENOS UNA VEZ-------------------//

						// Altas que ya fueron depreciada al menos una vez
						$http.get(API_URL + 'Activosfijos/ObtenerDepreciados').success(function (responsedepreciados) {
								
									
							$http.get(API_URL+ 'Activosfijos/ObtenerUltimaDepreciacion').success(function (responsefecha) {


							if (responsedepreciados !=0) {

								for (var i = 0; i < responsedepreciados.length; i++) {

									$http.get(API_URL + 'Activosfijos/DevolverDatosDeDetealleItemActivosFijos/' + responsedepreciados[i].iddetalleitemactivofijo).success(function (responsedatosdepreciados) {
										
										for (var i = 0; i < responsedatosdepreciados.length; i++) {

											var DepreciacionAnual = responsedatosdepreciados[i].preciounitario/responsedatosdepreciados[i].vidautil;
		
											var DepreciacionMensual = DepreciacionAnual/12;

											console.log('Depreciacion anual: '+DepreciacionAnual);

											var DepreciacionDiaria =  DepreciacionMensual/30;

											if (dd == 31 || mm==02  && dd==28 || mm==02  && dd==29) {

												dd = 30;
											}else{
												dd=dd;
											}

											var dataresponsedatosdepreciados=[];

											
												var FechaUltimaDepreciacion = responsefecha[0].fecha;

												var ArrayAno = FechaUltimaDepreciacion.split("-");

												var AnoUltimaDepreciacion = ArrayAno[0];

												var MesUltimaDeoreciacion =  ArrayAno[1];

												var DiaUltimaDepreciacion= ArrayAno[2];

												var FechaDepreciacion = DiaUltimaDepreciacion+'-0'+mm2+'-'+yyyy;
												

												if (AnoUltimaDepreciacion ==  yyyy) {

													var DepreciacionEnMeses = (mm - MesUltimaDeoreciacion) - 1;

													var DiasRestantesDesdeLaUltimaDepreciacion = 30 - DiaUltimaDepreciacion;

													var DepreciacionEnDiasDeLosMesesPasados = (DepreciacionEnMeses * 30)

													var DepreciacionTotalEnDias= DepreciacionEnDiasDeLosMesesPasados + DiasRestantesDesdeLaUltimaDepreciacion + dd;

													var Depreciacion = DepreciacionDiaria * DepreciacionTotalEnDias;

													console.log('Dias totales de depreciacion: '+DepreciacionTotalEnDias);
													console.log('cantidad de dinero para la depreciacion: '+Depreciacion);
													console.log('los años son iguales');

												}

												if (AnoUltimaDepreciacion !=  yyyy) {

													var MesesDesdeLaUltimaDepreciacion = ((12 - MesUltimaDeoreciacion) - 1) + mm;

													var DepreciacionEnDiasDelosMesesPasado = MesesDesdeLaUltimaDepreciacion * 30;

													var DiasRestantesDelMesDeLaUltimaDepreciacion = 30 - DiaUltimaDepreciacion;

													var DepreciacionTotalEnDias = DepreciacionEnDiasDelosMesesPasado + DiasRestantesDelMesDeLaUltimaDepreciacion + dd;

													var Depreciacion = DepreciacionDiaria * DepreciacionTotalEnDias;

													//console.log('Dias totales de depreciacion: '+DepreciacionTotalEnDias);
													//console.log('cantidad de dinero para la depreciacion: '+Depreciacion);
													//console.log('los años son diferentes');


												}

												var DatosTablaRegistroActivoFijo = {

												iddetalleitemactivofijo : responsedatosdepreciados[i].iddetalleitemactivofijo,
												idtransaccion 			: 1,
												fecha 					: DiaActual, 
												debe 					: Depreciacion,
												haber					: 0,	
												numerodocumento			: responsedatosdepreciados[i].numdocumentocompra,

											}

											$scope.idplancuentagasto = responsedatosdepreciados[i].idplancuentagasto;
											
											//-----------CREAR ASIENTO CONTABLE -----------//

											$http.get(API_URL + 'Activosfijos/ObtenerDatosCuentaDepreciacion/' + responsedatosdepreciados[i].idplancuentadepreciacion).success(function (responsedatosdepreciacion) {

													$http.get(API_URL + 'Activosfijos/ObtenerDatosCuentaGasto/' +$scope.idplancuentagasto).success(function (responsedatosgastos) {
												
												var RegistroC = [];	
													
													for (var i = 0; i < responsedatosdepreciacion.length; i++) {
														responsedatosdepreciacion[i]	

														var activofijo1 = {																				// todo es igual en aplicar que el de arriba
														    idplancuenta 		: responsedatosdepreciacion[i].idplancuenta,        																		// eso es un ejemplo, lo cual debes poner el idplancuenta de quien pertenece...es decir si aplica a empleado por ejemplo
														    concepto 			: responsedatosdepreciacion[i].concepto,                       															// es el concepto de ese idplancuenta de la tabla cont_plancuenta
														    controlhaber 		: responsedatosdepreciacion[i].controlhaber,               																// lo mismo, el campo controlhaber de cont_plancuenta
														    tipocuenta 			: responsedatosdepreciacion[i].tipocuenta,                    															// idem a tipocuenta
														    Debe 				: 0,                                                                                     // este total,
														    Haber 				: Depreciacion,
														    Descipcion 			: ''                                                                               // si quieres dejalo asi
														}

													}

																								
											
												for (var i = 0; i < responsedatosgastos.length; i++) {
													
													var activofijo2 = {																				// todo es igual en aplicar que el de arriba
													    idplancuenta 		: responsedatosgastos[i].idplancuenta,        																		// eso es un ejemplo, lo cual debes poner el idplancuenta de quien pertenece...es decir si aplica a empleado por ejemplo
													    concepto 			: responsedatosgastos[i].concepto,                       															// es el concepto de ese idplancuenta de la tabla cont_plancuenta
													    controlhaber 		: responsedatosgastos[i].controlhaber,               																// lo mismo, el campo controlhaber de cont_plancuenta
													    tipocuenta 			: responsedatosgastos[i].tipocuenta,                    															// idem a tipocuenta
													    Debe 				: Depreciacion,                                                                                     // este total,
													    Haber 				: 0,
													    Descipcion 			: ''                                                                               // si quieres dejalo asi
													}
												}

												

											RegistroC.push(activofijo1);

											RegistroC.push(activofijo2);



											//------esto es para preparar los datos para su envio------//

												var Contabilidad={
												    transaccion: Transaccion,
												    registro: RegistroC
												};

												var transaccion_venta_full={
												    DataContabilidad: Contabilidad
												};

												var transaccionfactura = {
												    datos: JSON.stringify(transaccion_venta_full)
												};

												

											//----------- FIN ASIENTO CONTABLE -----------//

											//--------datos a guardar en la tabla Cont_registroactivofijo--------//								
											
											$http.post(API_URL+ 'Activosfijos/GuardarAsientoContable', transaccionfactura).success(function (responseidtransaccion) {
											
												var objetonuevo =Object.defineProperty(DatosTablaRegistroActivoFijo,'idtransaccion',{value:responseidtransaccion});

												dataresponsedatosdepreciados.push(objetonuevo);

												console.log(dataresponsedatosdepreciados);

												for (var i = 0; i < dataresponsedatosdepreciados.length; i++) {
												//console.log(dataresponsedatosdepreciados[i].idtransaccion);
												var guardardepreciados= 6;
												$http.post(API_URL + 'Activosfijos/GuardarAltaActivosfijos/'+guardardepreciados,dataresponsedatosdepreciados[i])

												$http.post(API_URL + 'Activosfijos/ActualizarCampoDepreciado/' + dataresponsedatosdepreciados[i].iddetalleitemactivofijo, dataDerepciado)
												
												$('#iconok').removeClass('fa fa-refresh fa-spin fa-fw');
												$('#iconok').addClass('glyphicon glyphicon-calendar');
												$('#palabraejecutar').html("El <b>"+FechaDepreciacion+"</b> se debrá ejecutar la proxima depreciación");
												
												}

												
											})	

										});//
												});//
										
																					
										}
										

									});

								}


							}else{
								
							}


							});	



						});


					//-----------------------CALCULO DEPRECIACION ACTIVOS FIJO QUE NUNCA SE HAN DEPRECIADO-------------------//

						// Altas que nunca han sido depreciada
						$http.get(API_URL + 'Activosfijos/ObtenerNoDepreciados').success(function (responsenodepreciados) {
							
							if (responsenodepreciados != 0 ) {

								for (var i = 0; i < responsenodepreciados.length; i++) {

									$http.get(API_URL + 'Activosfijos/DevolverDatosDeDetealleItemActivosFijos/' + responsenodepreciados[i].iddetalleitemactivofijo).success(function (responsedatosnodepreciados) {
										
										for (var i = 0; i < responsedatosnodepreciados.length; i++) {

											//$scope.iddetalleitemactivofijo = responsedatosnodepreciados[i].iddetalleitemactivofijo;
											//$scope.numdocumentocompra = responsedatosnodepreciados[i].numdocumentocompra;

											var dataresponsedatosnodepreciados =[];
											var DepreciacionAnual = responsedatosnodepreciados[i].preciounitario/responsedatosnodepreciados[i].vidautil;

											var DepreciacionMensual = DepreciacionAnual/12;
											//console.log(responsedatosnodepreciados[i]);


											//console.log(responsedatosnodepreciados[i].preciounitario);

											if (dd == 31 || mm==02  && dd==28 || mm==02  && dd==29) {

												dd = 30;
											}else{
												dd=dd;
											}

											var DepreciacionDiario = DepreciacionMensual/30;
											
											var DiasRestantesDelMes = DiasDelMes - dd;
											var DepreciacionPorDiasRestantes = DepreciacionDiario * DiasRestantesDelMes;
											
											$scope.idplancuentagasto = responsedatosnodepreciados[i].idplancuentagasto;
											$scope.idplancuentadepreciacion = responsedatosnodepreciados[i].idplancuentadepreciacion;

												var DatosTablaRegistroActivoFijo = {

												iddetalleitemactivofijo : responsedatosnodepreciados[i].iddetalleitemactivofijo,
												idtransaccion 			: 1,
												fecha 					: DiaActual, 
												debe 					: DepreciacionPorDiasRestantes,
												haber					: 0,	
												numerodocumento			: responsedatosnodepreciados[i].numdocumentocompra,

											}
											//-----------CREAR ASIENTO CONTABLE -----------//
											var RegistroC = [];	

											$http.get(API_URL + 'Activosfijos/ObtenerDatosCuentaGasto/' + $scope.idplancuentagasto).success(function (responsedatosgastos) {
												
												$http.get(API_URL + 'Activosfijos/ObtenerDatosCuentaDepreciacion/' + $scope.idplancuentadepreciacion).success(function (responsedatosdepreciacion) {
	
													for (var i = 0; i < responsedatosdepreciacion.length; i++) {
													

														var activofijo1 = {																				// todo es igual en aplicar que el de arriba
														    idplancuenta 		: responsedatosdepreciacion[i].idplancuenta,        																		// eso es un ejemplo, lo cual debes poner el idplancuenta de quien pertenece...es decir si aplica a empleado por ejemplo
														    concepto 			: responsedatosdepreciacion[i].concepto,                       															// es el concepto de ese idplancuenta de la tabla cont_plancuenta
														    controlhaber 		: responsedatosdepreciacion[i].controlhaber,               																// lo mismo, el campo controlhaber de cont_plancuenta
														    tipocuenta 			: responsedatosdepreciacion[i].tipocuenta,                    															// idem a tipocuenta
														    Debe 				: 0,                                                                                     // este total,
														    Haber 				: DepreciacionPorDiasRestantes,
														    Descipcion 			: ''                                                                               // si quieres dejalo asi
														}

													}

																								
											
												for (var i = 0; i < responsedatosgastos.length; i++) {
													
													var activofijo2 = {																				// todo es igual en aplicar que el de arriba
													    idplancuenta 		: responsedatosgastos[i].idplancuenta,        																		// eso es un ejemplo, lo cual debes poner el idplancuenta de quien pertenece...es decir si aplica a empleado por ejemplo
													    concepto 			: responsedatosgastos[i].concepto,                       															// es el concepto de ese idplancuenta de la tabla cont_plancuenta
													    controlhaber 		: responsedatosgastos[i].controlhaber,               																// lo mismo, el campo controlhaber de cont_plancuenta
													    tipocuenta 			: responsedatosgastos[i].tipocuenta,                    															// idem a tipocuenta
													    Debe 				: DepreciacionPorDiasRestantes,                                                                                     // este total,
													    Haber 				: 0,
													    Descipcion 			: ''                                                                               // si quieres dejalo asi
													}
												}

												

												RegistroC.push(activofijo1);

												RegistroC.push(activofijo2);

												console.log(RegistroC);



											//------esto es para preparar los datos para su envio------//

												var Contabilidad={
												    transaccion: Transaccion,
												    registro: RegistroC
												};

												var transaccion_venta_full={
												    DataContabilidad: Contabilidad
												};

												var transaccionfactura = {
												    datos: JSON.stringify(transaccion_venta_full)
												};

												

											//----------- FIN ASIENTO CONTABLE -----------//

											//--------datos a guardar en la tabla Cont_registroactivofijo--------//										

										
											$http.post(API_URL+ 'Activosfijos/GuardarAsientoContable', transaccionfactura).success(function (responseidtransaccion) {
												
												var objetonuevo =Object.defineProperty(DatosTablaRegistroActivoFijo,'idtransaccion',{value:responseidtransaccion});

												dataresponsedatosnodepreciados.push(objetonuevo);

												console.log(dataresponsedatosnodepreciados);

												for (var i = 0; i < dataresponsedatosnodepreciados.length; i++) {
													//$scope.dataresponsedatosnodepreciados= dataresponsedatosnodepreciados[i].idtransaccion;
													var guardardepreciados= 6;
													$http.post(API_URL + 'Activosfijos/GuardarAltaActivosfijos/'+guardardepreciados,dataresponsedatosnodepreciados[i])


													$http.post(API_URL + 'Activosfijos/ActualizarCampoDepreciado/' + dataresponsedatosnodepreciados[i].iddetalleitemactivofijo, dataDerepciado)
												
													$('#iconok').removeClass('fa fa-refresh fa-spin fa-fw');
													$('#iconok').addClass('glyphicon glyphicon-calendar');;
													$('#palabraejecutar').html("El <b>"+FechaDepreciacion+"</b> se debrá ejecutar la proxima depreciación");
												}

													});//
												});//	
											});

													
										}

										
									});


								}

								
							}else{


							}

						});

						//--------coloca el compo DEPRECIADO de la tabla Cont_detalleitemactivofijo en 1--------//
						
						//setTimeout($scope.actualizarcampodepreciado(), 1150000);	
				}
				
				
				});	

			});

		}


		$scope.ShowModalGestionActivo = function (idcatalogitem,iditemcompra) {

			$scope.CamposIncidencias=false;
			//verificar si la compra tiene alta

				$http.get(API_URL + 'Activosfijos/VerificarAltaCompra/' + iditemcompra).success(function (response) {

					//verificar si no tiene alta

					if (response == 0) {


						//verificar si el numero de activo ingresado ya esta en uso
							$scope.NumActivo = function () {

								var numactivo = $('#numactivo').val();
									
								$http.get(API_URL + 'Activosfijos/ObtenerNumActivo/' + numactivo).success(function (response) {


									//si lo está
									if (response==1) {

										$scope.alerta = true;
										$('#btn-save-formularioAltaActivoFijo').attr('disabled',true);
									}

									//si no lo está
									else{
										$scope.alerta = false;
									}

								});

							}


							$('#incidencia1,#mantencion1,#traslado1,#baja1').css('display','none');
							$('#mensaje').html('Debe realizar el alta a la compra del Activo Fijo para visualizar todas las opciones');
							$('#mensaje').css({'padding':'10px','margin-left':'160px','color':'#8a6d3b'});
							$('#mensaje').removeClass('ocultar');
							$("#numactivo").removeAttr("readonly","readonly");
							$('#vidautil,#cuenta_contable_depreciacion,#cuenta_contable_gastos').removeAttr('disabled',false);
							console.log('no tiene alta');

								

									$scope.GuardarAltaActivoFijo = function (iddetalleitemactivofijo) {

										$('#btn-save-formularioAltaActivoFijo').attr('disabled',true);

										//data para guardar el alta de activo fijo
									var alta = 1;

									var data = {

										iditemactivofijo         : $scope.iditemactivofijo,
										iditemcompra   			 : $scope.iditemcompra,
										idempleado    			 : $scope.idresponsable,
										idplancuentadepreciacion : $scope.id_cuenta_contable_depreciacion,
										idplancuentagasto 	     : $scope.id_cuenta_contable_gastos,
										numactivo				 : $scope.numactivo,
										vidautil				 : $scope.vidautil,
										fechaalta				 : $scope.DiaActual,
										valorsalvamento	         : null,
										precioventa				 : $scope.precioventa,
										estado					 : $scope.estado,
										ubicacion				 : $scope.ubicacion,
										observacion				 : $scope.observacion,
										depreciado 				 : 0,
										baja 					 : 0
									}

								
									$http.post(API_URL + 'Activosfijos/GuardarAltaActivosfijos/'+ alta, data).success(function (response) {
									
										$scope.GetAllActivosFijos();

										$scope.mensaje1 = 'Se ha dado de alta satisfactoriamente al Activo Fijo';
									
										setTimeout("$('#modalMensaje1').modal('show')", 1000);
										setTimeout("$('#modalMensaje1').modal('hide')", 3000);

										$('#incidencia1,#mantencion1,#traslado1,#baja1').css('display','block');

										
										$('#mensaje').addClass('ocultar');
										
							
									});

								}




							//si tiene alta
					}else  {	

						//si hacen click en el boton con el ID btn-save-formularioIncidenciaActivoFijo

								$('#btn-save-formularioIncidenciaActivoFijo').click(function () {
								$(this).attr('disabled',true);	
								$scope.GuardarIncidenciaActivoFijo();
								$scope.CampoIncidencia = [];

							});

						//si hacen click en el boton con el ID btn-save-formularioMantencionActivoFijo

							$('#btn-save-formularioMantencionActivoFijo').click(function () {

								$(this).attr('disabled',true);
								$scope.GuardarMantencionActivoFijo();
								$scope.CampoMantencion = [];

							});


							//si hacen click en el boton con el ID btn-save-formularioTrasladoActivoFijo

							$('#btn-save-formularioTrasladoActivoFijo').click(function () {
								//$(this).attr('disabled',true);
								$scope.GuardarTrasladoActivoFijo();
								$scope.CamposTraslado = [];

							});



							$('#vidautil,#cuenta_contable_depreciacion,#cuenta_contable_gastos').attr('disabled',true)
							$('#mensaje').addClass('ocultar');
							$('#incidencia1,#mantencion1,#traslado1,#baja1').css('display','block');


							var iditemcompra = response[0].iditemcompra;
							



							//obtener los datos del alta				
						$http.get(API_URL + 'Activosfijos/ObtenerDatosAlta/' + iditemcompra).success(function (response) {
							
							$("#numactivo").attr("readonly","readonly");

							for (var i = 0; i < response.length; i++) {

								
								$scope.numactivo = response[i].numactivo;
								$scope.vidautil = response[i].vidautil;
								$scope.precioventa = response[i].precioventa;
								$('#Responsable_value').val(response[i].namepersona);
								$scope.idresponsable = response[i].idpersona;
								$scope.ubicacion = response[i].ubicacion;
								$scope.cuenta_contable_depreciacion = response[i].concepto;
								$scope.id_cuenta_contable_depreciacion = response[i].idplancuentadepreciacion;
								$scope.observacion =  response[i].observacion;
								$scope.iddetalleitemactivofijo = response[i].iddetalleitemactivofijo;
								
								var iddetalleitemactivofijo = response[i].iddetalleitemactivofijo;

								var estado = response[i].estado;

								//si la consulta devuelve el estado 1 el <option> activo se selecciona
								if (estado == 1) {

									$('#inactivo').removeAttr('selected','selected');
									$('#activo').attr('selected','selected');


								//si la consulta no devuelve el estado 1 el <option> inactivo se selecciona	
								}else{
									
									$('#activo').removeAttr('selected','selected');
									$('#inactivo').attr('selected','selected');
								}



								//si tiene Alta al ejecutar la funcion GuardarAltaActivoFijo se editan los campos que se selecciones que ya se guardaron en el Alta
									$scope.GuardarAltaActivoFijo = function (iddetalleitemactivofijo) {
						

									var data = {

										precioventa: $scope.precioventa,
										estado     : $scope.estado,
										idempleado : $scope.idresponsable,
										ubicacion  : $scope.ubicacion,
										observacion: $scope.observacion


									}

									$http.put(API_URL + 'Activosfijos/depreciacionActivosFijos/' + iddetalleitemactivofijo ,data).success(function () {
										
										$scope.mensaje1 = 'Se ha actualizado el alta exitosamente';
										setTimeout("$('#modalMensaje1').modal('show')", 1000);
										setTimeout("$('#modalMensaje1').modal('hide')", 3000);


									});



								}
							}


							




							//consultar en la BD si esta alta posee baja
							$http.get(API_URL + 'Activosfijos/ObtenerBaja/' + $scope.iddetalleitemactivofijo).success(function (response) {
							
								//si no tiene baja
								if (response == 0) {

									$scope.CampoFechaBaja=false;
									$scope.CampoConceptoBaja = false;
									$scope.CampoDescripcionBaja = false;
									$scope.BotonVerificarBajaActivoFijo= false;



									$scope.InputPrecioVenta=false;
									$scope.InputEstado=false;
									$('#Responsable_value').attr('disabled',false);
									$scope.InputUbicacion= false;
									$scope.InputObservacionAlta=false;
									$scope.CampofechaMantencion=false;
									$scope.CampoTipoMantencion=false;
									$scope.CampoObservacionMantencion=false;
									$scope.InputObservacionAlta=false;
									$scope.ConceptoBaja2=null;
									$scope.seleccione="--Seleccione--";
									$scope.fechaBaja=null;
									$scope.DescripcionBaja = null;

						
								}

								//si tiene baja
								else{	
									

									var fechaformateada = new Date(response[0].fecha);
									

									$scope.fechaBaja=fechaformateada;
									$scope.ConceptoBaja2 = response[0].concepto;
									$scope.DescripcionBaja = response[0].descripcion;
									
									$scope.seleccione="";
									
									$scope.CampoFechaBaja=true;
									$scope.CampoConceptoBaja = true;
									$scope.CampoDescripcionBaja = true;
									$scope.BotonVerificarBajaActivoFijo= true;
									$scope.InputPrecioVenta=true;
									$scope.InputEstado=true;
									$scope.InputResponsableAlta=true;
									$scope.InputUbicacion= true;
									$('#Responsable_value').attr('disabled',true);
									$scope.CampofechaMantencion=true;
									$scope.CampoTipoMantencion=true;
									$scope.CampoObservacionMantencion=true;
									$scope.InputObservacionAlta=true;
								}
							});





							//consultar en la BD por el ID si el alta seleccionada tiene incidencia
							$http.get(API_URL + 'Activosfijos/ObtenerIncidencia/' + $scope.iddetalleitemactivofijo).success(function (response) {
							

								//si no tiene incidencia 
								if (response == 0) {

									$scope.MensajeSinRegistrosIncidencias=true;
									console.log('no tiene incidencias');
									$scope.DatosIncidencias=false;
									$scope.CamposIncidencias= true;
									$scope.BotonGuardarIncidencias=false;
									setTimeout($scope.BotonAgregarIncidencia= false, 5000);
									$scope.VerIncidencias = true;
									$scope.CampoIncidencia = [];


						
								}

								//si tiene incidencia 
								else{	

										$scope.MensajeSinRegistrosIncidencias=false;
										console.log('si tiene incidencias');
										$scope.BotonAgregarIncidencia= false;
										$scope.response = response;
										$scope.VerIncidencias = false;
										$scope.DatosIncidencias = response;
									}
							});



							//consultar en la BD por el ID si el alta seleccionada tiene mantenciones

							$http.get(API_URL + 'Activosfijos/ObtenerMantencion/'+ $scope.iddetalleitemactivofijo).success(function (response) {
							
								//si no tiene mantencion

								if (response == 0) {
									console.log('no tiene mantencion')

									$scope.MensajeSinRegistrosMantenciones=true;
									$scope.VerMantenciones=true;
									$scope.DatosMantencion=false;
									$scope.CamposMantencion= true;
									$scope.VerMantencion=true;
								}

								//si tiene mantencion

								else{

								
									console.log('si tiene mantencion')
									$scope.MensajeSinRegistrosMantenciones=false;
									$scope.DatosMantenciones = response;
									$scope.DatosMantencion=true;
									$scope.CamposMantencion= false;
									$scope.VerMantencion=false;
								}
								
							});




							//consultar en la BD por el ID si el alta seleccionada tiene traslados

							$http.get(API_URL + 'Activosfijos/ObtenerTraslados/'+ $scope.iddetalleitemactivofijo).success(function (response) {
							
								//si no tiene traslados

								if (response == 0) {
									console.log('no tiene traslados')
									$scope.DatoTraslado=false;
									$scope.CampoTraslado=true;
									$scope.VerTraslados=true;
									$scope.MensajeSinRegistrosTraslados=true;
									
									
								}

								//si tiene traslados

								else{

									console.log('si tiene traslados');

									$scope.MuestraTraslados= function (argument) {
										var traslado= [];

										for (var i = 0; i < response[0].length; i++) {
												response[0][i].namepersonaorigen;

											for (var i = 0; i < response[1].length; i++) {
												response[1][i].namepersonadestino;	

												var datos = {

													fecha 				:response[0][i].fecha,
													namepersonaorigen	:response[0][i].namepersonaorigen,
													namepersonadestino  :response[1][i].namepersonadestino
												} 

												traslado.push(datos);
													
											}

												$scope.DatosTraslado = traslado;
										}

									}

									$scope.MuestraTraslados();

									
								
									
									$scope.DatoTraslado=true;
									$scope.CampoTraslado=false;
									$scope.VerTraslados=false;
									$scope.MensajeSinRegistrosTraslados=false;
									}

									
								
								
							});





						});	

							//obtener el concepto y ID del Plan Cuenta Gastos
							$http.get(API_URL + 'Activosfijos/ObtenerPlanCuentaGasto/' + iditemcompra).success(function (response) {
									
									for (var i = 0; i < response.length; i++) {
										
										$scope.cuenta_contable_gastos= response[i].concepto;
										$scope.id_cuenta_contable_gastos = response[i].idplancuentagasto;
									}

							});			
					}	

							//obtener los tipos de mantencion
							$http.get(API_URL + 'Activosfijos/ObtenerTiposMantencion').success(function (response) {
									
									$scope.tiposmantencion = response;

							});

							//obtener los conceptos de baja

							$http.get(API_URL + 'Activosfijos/ObtenerConceptoBaja').success(function (response) {
									
									$scope.Conceptobaja = response;

							});

				});


		

							//Obtener codigocatalog y nombreproducto
							$http.get(API_URL + 'Activosfijos/ActivoFijoIndividual/' + idcatalogitem).success(function (response) {

								//limpiamos todos los campo llenables
								$scope.numactivo= null;
								$scope.precioventa=null;
								$scope.vidautil=null;
								$scope.ubicacion=null;
								$scope.observacion=null;
								$scope.cuenta_contable_gastos = null;
								$scope.id_cuenta_contable_gastos = null;
								$scope.cuenta_contable_depreciacion = null;
								$scope.id_cuenta_contable_depreciacion = null;
								$scope.idresponsable = null; 
								$('#Responsable_value').val('');
								$('#inactivo').removeAttr('selected','selected');
								$('#activo').removeAttr('selected','selected');

								//obtener la fecha actual
								var hoy = new Date();
								var dd = hoy.getDate();
								var mm = hoy.getMonth()+1; 
								var yyyy = hoy.getFullYear();

								//Asignar los datos a los campos no llenables

								console.log(response);

								$scope.DiaActual = yyyy+'-'+mm+'-'+dd;
								$scope.codigo = response[0].codigoproducto;
								$scope.detalle = response[0].nombreproducto;
								$scope.iditemactivofijo = response[0].iditemactivofijo;

									
									//Asignar los datos faltantees				
									$http.get(API_URL + 'Activosfijos/ObtenerDemasDatos/' + iditemcompra).success(function (response) {

										$scope.iditemcompra = response[0].iditemcompra;
										$scope.fechaadquisicion = response[0].fechaemisioncompra;

									});


								$('#ModalGestionActivo').modal('show');

							});


		}



		
		$scope.Plancuentas = function (numero) {

			//Obtener plan de cuentas completo

			$http.get(API_URL + 'Activosfijos/getPlanCuentas').success(function (response) {

				$scope.PlanCuentas= response;
			
				$('#modalCuentas').modal('show');

				//Sí es cuenta_contable_depreciacion

				if (numero==0) {


					$scope.click_radio = function (cuentas) {

		       			 $scope.cuenta_contable_depreciacion=cuentas.concepto;
		       			 $scope.id_cuenta_contable_depreciacion=cuentas.idplancuenta;
		       			 $('#modalCuentas').modal('hide');

   					}

				}

				//Sí es cuenta_contable_gastos

				if (numero==1) {


					$scope.click_radio = function (cuentas) {

		      			 $scope.cuenta_contable_gastos=cuentas.concepto;
		       			 $scope.id_cuenta_contable_gastos=cuentas.idplancuenta;
		       			 $('#modalCuentas').modal('hide');

   					}

				}

			});
		}



		$scope.idempleado = function () {

			 $('.angucomplete-dropdown').click(function () {
			 	
			 	var responsable = $('#Responsable_value').val();
				
				$http.get(API_URL + 'Activosfijos/AllResponsable/' + responsable).success(function (response) {

					$scope.idresponsable = response[0].idempleado;	


				
				});

			});

		}



		$scope.idempleadoorigen= function (index) {

			 $('#Origen'+index+'_dropdown').click(function () {
			 	
			 	var responsable = $('#Origen'+index+'_value').val();

			 	if (responsable == '' ) { 

			 		$('#idresponsableorigen').val('');
			 	}

			 	
				$http.get(API_URL + 'Activosfijos/AllResponsable/' + responsable).success(function (response) {

					$scope.idresponsableorigen = response[0].idempleado;

				$scope.origen=	$('#idresponsableorigen'+index).val($scope.idresponsableorigen);


				
					if ($scope.origen != '' || $scope.destino !=  ''  || $scope.itemm.fechaTraslado != '' ) {

						$scope.formularioTrasladoActivoFijo = false;
					}else{

						$scope.formularioTrasladoActivoFijo = true;
					}
				
				});

			});

		}



		$scope.idempleadodestino= function (index) {

			 $('#Destino'+index+'_dropdown').click(function () {
			 	
			 	var responsable = $('#Destino'+index+'_value').val();

			 	if (responsable == '' ) { 

			 		$('#idresponsabledestino').val('');	
			 	}

			 	
				$http.get(API_URL + 'Activosfijos/AllResponsable/' + responsable).success(function (response) {

					$scope.idresponsabledestino = response[0].idempleado;

				$scope.destino=	$('#idresponsabledestino'+index).val($scope.idresponsabledestino);		

					if ($scope.origen != '' || $scope.destino !=  ''  || $scope.itemm.fechaTraslado != '' ) {

						$scope.formularioTrasladoActivoFijo = false;
					}else{

						$scope.formularioTrasladoActivoFijo = true;
					}
				});

			});

			
		}


		
		$scope.GuardarIncidenciaActivoFijo = function () {

			var incidencia = 2;

			 var data = [];
			 var fecha = $('.fecha').val();
			 $scope.CampoIncidencia.forEach(function(itemm) { 
	             var row = {
	                iddetalleitemactivofijo :$scope.iddetalleitemactivofijo,
	                descripcion				:itemm.descripcion,
	                fecha 					:itemm.fecha
	                
	            }
	           data.push(row);
	     
	         });

	         var datas = {
	         	data : data
	         }
		
			$http.post(API_URL + 'Activosfijos/GuardarAltaActivosfijos/'+ incidencia,datas).success(function (response) {

				$scope.mensaje1 = 'Se ha guardado La incidencia exitosamente';
				setTimeout("$('#modalMensaje1').modal('show')", 1000);
				setTimeout("$('#modalMensaje1').modal('hide')", 3000);
				$('.fecha').val('');
				$('.descripcion').val('');
				$scope.MostrarIncidencias();
				$http.get(API_URL + 'Activosfijos/ObtenerIncidencia/' + $scope.iddetalleitemactivofijo).success(function (incidencias) {
					$scope.resultadoincidencias=incidencias;
					$scope.DatosIncidencias = incidencias;
					$scope.VerIncidencias = false;
					$scope.MensajeSinRegistrosIncidencias=false;
				});


			});

		}


		$scope.GuardarMantencionActivoFijo = function () {

			var mantencion = 3;


			 var data = [];

			 $scope.CampoMantencion.forEach(function(itemm) { 
	            var row = {

	                iddetalleitemactivofijo :$scope.iddetalleitemactivofijo,
	                ObservacionMantencion	:itemm.ObservacionMantencion,
	                fechaMantencion			:itemm.fechaMantencion,
	                IdTipoMantencion		:itemm.TipoMantencion
	                
	            }
	           data.push(row);
	     
	         });

	         var datas = {
	         	data : data
	         }


			$http.post(API_URL + 'Activosfijos/GuardarAltaActivosfijos/' + mantencion , datas).success(function () {

				$scope.mensaje1 = 'Se ha guardado La Mantención exitosamente';
				setTimeout("$('#modalMensaje1').modal('show')", 1000);
				setTimeout("$('#modalMensaje1').modal('hide')", 3000);
				$('.fechaMantencion').val('');
				$('#seleccione').attr('selected',true);
				setTimeout("$('#seleccione').attr('selected',false)", 1000);
				$scope.ObservacionMantencion = null;
				 var fecha = $('.fechaMantencion').val('');
				 $scope.IdTipoMantencion = null;
				 $('.ObservacionMantencion').val('');
				 $scope.MostrarIncidencias();
				$http.get(API_URL + 'Activosfijos/ObtenerMantencion/' + $scope.iddetalleitemactivofijo).success(function (mantencion) {
					
					$scope.DatosMantenciones = mantencion;
					//$scope.responsemanteciones = mantencion;
					$scope.MostrarMantencion();
					$scope.MensajeSinRegistrosMantenciones=false;
					$scope.CamposMantenciones=false;
					$scope.DatosMantencion=true;

				});

				
			});
			


		}





		$scope.GuardarTrasladoActivoFijo = function () {

			var traslado = 4;

			 var data = [];

			 $scope.CamposTraslado.forEach(function(itemm) { 
			 	

	            var row = {

	                iddetalleitemactivofijo :$scope.iddetalleitemactivofijo,
	                IdEmpleadoOrigen		:itemm.ResponsableOrigen.originalObject.idempleado,
	                IdEmpleadoDestino 		:itemm.ResponsableDestino.originalObject.idempleado,
	                fechaTraslado			:itemm.fechaTraslado,
	               
	               
	            }
	           data.push(row);

	           
	     
	         });

	         var datas = {
	         	data : data
	         }


			$http.post(API_URL + 'Activosfijos/GuardarAltaActivosfijos/' + traslado , datas).success(function () {

				$scope.mensaje1 = 'Se ha guardado el Traslado exitosamente';
				setTimeout("$('#modalMensaje1').modal('show')", 1000);
				setTimeout("$('#modalMensaje1').modal('hide')", 3000);
				$scope.BotonGuararTraslado=false;
				
				$http.get(API_URL + 'Activosfijos/ObtenerTraslados/' + $scope.iddetalleitemactivofijo).success(function (traslado) {
					
					$scope.DatosTraslado = traslado;
					$scope.MuestraTraslados();
					$scope.MensajeSinRegistrosTraslados=false;
					$scope.CampoTraslado=false;
					$scope.DatoTraslado=true;

				});

				
			});
			


		}


		$scope.GuardarBajaActivoFijo = function () {
			
			var baja = 5;

			var data = {

				iddetalleitemactivofijo: $scope.iddetalleitemactivofijo,
				idconceptobajaaf 	   : $scope.ConceptoBaja,
				fechabaja 			   : $scope.fechaBaja,
				descripcionbaja 	   : $scope.DescripcionBaja	

			}

			$http.post(API_URL + 'Activosfijos/GuardarAltaActivosfijos/'+ baja , data).success(function () {
				setTimeout("$('#modalMensaje2').modal('hide')", 1000);
				$scope.CampoFechaBaja=true;
				$scope.CampoConceptoBaja = true;
				$scope.CampoDescripcionBaja = true;
				$scope.BotonVerificarBajaActivoFijo= true;
				$scope.InputPrecioVenta=true;
				$scope.InputEstado=true;
				$('#Responsable_value').attr('disabled',true);
				$scope.InputUbicacion= true;
				$scope.InputObservacionAlta=true;
				$scope.CampofechaMantencion=true;
				$scope.CampoTipoMantencion=true;
				$scope.CampoObservacionMantencion=true;
				$scope.InputObservacionAlta=true;
				$scope.mensaje1 = 'Se ha dado de baja exitosamente';
				setTimeout("$('#modalMensaje1').modal('show')", 1000);
				setTimeout("$('#modalMensaje1').modal('hide')", 3000);
				$scope.actualizarcampobaja($scope.iddetalleitemactivofijo);


			});



		}


		$scope.VerificarBajaActivoFijo = function () {

				$scope.mensaje2 = '¿Esta seguro de que desea dar de baja.?';
				setTimeout("$('#modalMensaje2').modal('show')", 1000);
		}










	$scope.ElimimarFilaInicidencia = function (IdbotonEliminar) {

		$scope.CampoIncidencia.splice(IdbotonEliminar,1);

		if ($scope.CampoIncidencia == '') {

			$scope.BotonGuardarIncidencias=false;
		}
		else{
			$scope.BotonGuardarIncidencias=true;
		}

		
	}


	$scope.ElimimarFilaMantencion = function (IdbotonEliminar) {

		$scope.CampoMantencion.splice(IdbotonEliminar,1);

		if ($scope.CampoMantencion == '') {

			$scope.BotonGuardarMantencion=false;
		}
		else{
			$scope.BotonGuardarMantencion=true;
		}

		
	}	


	$scope.ElimimarFilaTraslado = function (IdbotonEliminar) {

		$scope.CamposTraslado.splice(IdbotonEliminar,1);

		if ($scope.CamposTraslado == '') {

			$scope.BotonGuararTraslado=false;
		}
		else{
			$scope.BotonGuararTraslado=true;
		}

		
	}		


	$scope.CrearFila = function (tipo) {


			if (tipo == 'incidencia') {
				$scope.DatosIncidencias=false;
				$scope.CamposIncidencias= true;
				$scope.BotonGuardarIncidencias=true;

				$scope.CampoIncidencia.push($scope.NuevaFilaIncidencia());
			}

			if (tipo == 'mantencion') {
				$scope.DatosMantencion=false;
				$scope.CamposMantenciones=true;
				$scope.BotonGuardarMantencion=true;

				$scope.CampoMantencion.push($scope.NuevaFilaMantencion());

			}

			if (tipo == 'traslado') {


				$scope.DatoTraslado=false;
				$scope.CampoTraslado=true;
				$scope.BotonGuararTraslado=true;

				$scope.CamposTraslado.push($scope.NuevaFilaTraslado());

			}



	};

	    $scope.NuevaFilaIncidencia = function(){

             return {
             	iddetalleitemactivofijo:null,
                fecha:null,
                descripcion:null
          
       		}
       	};


       	 $scope.NuevaFilaMantencion = function(){
             
             return {
             	fechaMantencion: 		null,
                TipoMantencion: 		null,
                IdTipoMantencion: 		null,
                ObservacionMantencion:  null,
                iddetalleitemactivofijo:null
          
       		}
       	};

       	$scope.NuevaFilaTraslado = function () {
       		
       		return {

       			fechaTraslado: 			null,
 				ResponsableDestino: 	null,
 				ResponsableOrigen: 		null,
 				iddetalleitemactivofijo:null


       		}
       	};



		$scope.MostrarIncidencias =function () {
			$scope.DatosIncidencias=true;
			$scope.CamposIncidencias= false;
			$scope.BotonGuardarIncidencias= false;
			
			$scope.DatosIncidencias = $scope.response;
			
		}

		$scope.MostrarMantencion =function () {
			$scope.DatosMantencion=true;
			$scope.CamposMantenciones= false;
			$scope.BotonGuardarMantencion=false;
			
			$scope.DatosMantenciones = $scope.DatosMantenciones;
			
		}


		$scope.MostrarTraslados =function () {

			$scope.DatoTraslado=true;
			$scope.CampoTraslado= false;
			$scope.BotonGuararTraslado=false;
			
			$scope.MuestraTraslados();
			
		}

	})
		app.filter('estado',function () {

						return function (estado) {
							if (estado==0) {
								return estado ='Inactivo';
							}if (estado==1) {
								return estado = 'Activo';
							}if (!estado) {
								return estado = 'Debe dar de alta para colocar el estado';
							}
						}
					})

