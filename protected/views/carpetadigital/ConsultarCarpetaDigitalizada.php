<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src='../start/js/validar_ruc.js' ></script>

<div class="pagina">

	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>

	<div class="col-md-12">
		<div style="border-bottom: 1px solid #dddddd;padding: 8px;">
 			<h4>Consultar Causa Digitalizada EN DESARROLLO</h4>
 		</div>
	</div>

	<div class="col-md-12">

		<div class="col-md-6">
			<div class="col-md-7">
			<div style="margin-left: 35px;padding: 16px;">		

		     <div class="row-inline-rev">
				<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
				<input type="text" id="ruc" name="ruc" autofocus value="<?php if(isset($ruc)) echo $ruc; ?>" />
			</div><br>

			 <div class="row-inline-rev">	
				<button id="btn_control" name="asignar" class="btn btn-primary" onclick="consultarSAF()">Consultar</button>
			</div>	    

	    	</div>
	    	</div><!--<div class="col-md-7">-->

	    	<div class="col-md-3" id="estado_parte">

	    	</div><!--<div class="col-md-3">-->

	    </div><!--<div class="col-md-6">-->

	    <div class="col-md-6" style="margin-bottom: 15px;padding: 5px;" id="datos_causa">
	 
	    </div><!--<div class="col-md-6">-->

    </div><!--<div class="col-md-12">-->

    <div class="col-md-12">
    <!--SI EXISTO RUC COMIENZA A BUSCAR LOS DOCUMENTOS-->
    	<!--<div class="col-md-12">
	    <span class="span-rojo btn_ocultardoc" style="width: 200px;padding: 7px 30px;display: none" id="ocultar" onclick="verDocu()">Ocultar documentos</span>
	    </div>-->

	    

	    <div id="documentos">
	    	
	    </div>	  

	    

     
    </div> <!--FIN clm 12-->

</div>

<!--    se comenta porque no esta siendo utilizada en este momento  *******  KRO - 25/07/2021******

<script type="text/javascript">
	$(document).ready(function() {	

		var ruc =$("#ruc").prop("value");
		if(ruc != ""){

			if ( validaRuc(ruc) ){
			document.getElementById("loading").style.display = "block";			

			urlver = "http://172.17.123.19/conect_saf/consulta_datos_saf.php";

			$.ajax({
			    url: urlver,
			    type: 'GET',
			    data: {ruc: ruc},
			    success: function(result){ 		 	

			    	$('#datos_causa').empty();
	    			$('#datos_causa').append(result); 

	    			urlsecreta = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultaCausaReservada'?>";
					$.ajax({
					    url: urlsecreta,
					    type: 'GET',
					    data: {ruc: ruc},
					    dataType: "json",
					    success: function(result){ 
					    	if(result.status === "success") {
					    		var fiscal = $("#fiscal").val(); 
				    			urlpermiso = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultaPermisoCausa'?>";
								$.ajax({
								    url: urlpermiso,
								    type: 'GET',
								    data: {fiscal: fiscal},
								    dataType: "json",
								    success: function(result){ 		 	

								    	if(result.status === "success") {
								    		urlsaf = "http://172.17.123.19/conect_saf/consulta_documentos.php";

							    			$.ajax({
											    url: urlsaf,
											    type: 'GET',
											    data: {ruc: ruc},
											    success: function(result){ 		

											    	urlguarda = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/GuardaConsultaDocumentos'?>";
											    	$.ajax({
													    url: urlguarda,
													    type: 'POST',
													    data: {ruc: ruc},
													    success: function(result){ 		 	

													    	//alert(result)
															//consultar tabla de documentos
											    			urldoc = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultarDocumentosRuc'?>";
															$.ajax({
															    url: urldoc,
															    type: 'POST',
															    data: {ruc: ruc},
															    success: function(result){ 		 	

															    	$('#documentos').empty();
											    					$('#documentos').append(result);

											    					urlrev = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultaEstadoParte'?>";
																	$.ajax({
																	    url: urlrev,
																	    type: 'POST',
																	    data: {ruc: ruc},
																	    success: function(result){ 		 	

																	    	$('#estado_parte').empty();
													    					$('#estado_parte').append(result);				    					


																			document.getElementById("loading").style.display = "none"; 		  		   
																    		
																	    }//fin success
																	});//fin post tabla según gestor seleccionad
																  		   
														    		
															    }//fin success
															});//fin post ajax consulta tabla de documentos	    		   
												    		
													    }//fin success
													});//fin post tabla según gestor seleccionad*/
										    		
											    }//fin succes consulta documentos de saf en server 19

											});//fin post consulta documentos de saf en server 19
							            }
							            else{
							            	$('#documentos').empty();
											$('#documentos').append(result.message);
											document.getElementById("loading").style.display = "none"; 
							            }
						  		   
							    		
								    }//fin success
								});//fin get permisos  	
					    	}
					    	else{
					    		$('#documentos').empty();
								$('#documentos').append(result.message);
								document.getElementById("loading").style.display = "none";
					    	}//else no tiene permisos reservados
					    }//fin success
					});//fin get permisos 

			    }//fin success
			});//fin post tabla según gestor seleccionad
			return false;

			}//FIN VALIDA RUC	
		}//fin if existe ruc

	});	
</script>

-->


 <script type="text/javascript">
	function consultarSAF(){

		var ruc =$("#ruc").prop("value");
		if(ruc != ""){

			if ( validaRuc(ruc) ){

			document.getElementById("loading").style.display = "block";
			urlver = "http://172.17.123.19/conect_saf/consulta_datos_saf.php";
			$.ajax({
			    url: urlver,
			    type: 'GET',
			    data: {ruc: ruc},
			    success: function(result){ 		 	

			    	$('#datos_causa').empty();
	    			$('#datos_causa').append(result); 

	    			urlsecreta = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultaCausaReservada'?>";
					$.ajax({
					    url: urlsecreta,
					    type: 'GET',
					    data: {ruc: ruc},
					    dataType: "json",
					    success: function(result){ 
					    	if(result.status === "success") {
					    		var fiscal = $("#fiscal").val(); 
				    			urlpermiso = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultaPermisoCausa'?>";
								$.ajax({
								    url: urlpermiso,
								    type: 'GET',
								    data: {fiscal: fiscal},
								    dataType: "json",
								    success: function(result){ 		 	

								    	if(result.status === "success") {
								    	//	urlsaf = "http://172.17.123.19/conect_saf/consulta_documentos.php";

							    		//	$.ajax({
										//	    url: urlsaf,
										//	    type: 'GET',
										//	    data: {ruc: ruc},
										//	    success: function(result){ 		

										//	    	urlguarda = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/GuardaConsultaDocumentos'?>";
										//	    	$.ajax({
										//			    url: urlguarda,
										//			    type: 'POST',
										//			    data: {ruc: ruc},
										//			    success: function(result){ 		 	

													    //	alert(result)
													    //	var ruc =$("#ruc").prop("value");
													    //	alert(ruc)
															//consultar tabla de documentos
											    			urldoc = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultarDocumentosDigRuc'?>";
															$.ajax({
															    url: urldoc,
															    type: 'POST',
															    data: {ruc: ruc},
															    success: function(result){ 		 	

															    	$('#documentos').empty();
											    					$('#documentos').append(result);

											    					urlrev = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultaEstadoParte'?>";
																	$.ajax({
																	    url: urlrev,
																	    type: 'POST',
																	    data: {ruc: ruc},
																	    success: function(result){ 		 	

																	    	$('#estado_parte').empty();
													    					$('#estado_parte').append(result);				    					


																			document.getElementById("loading").style.display = "none"; 		  		   
																    		
																	    }//fin success
																	});//fin post tabla según gestor seleccionad
																  		   
														    		
															    }//fin success
															});//fin post ajax consulta tabla de documentos	    		   
												    		
										//			    }//fin success
										//			});//fin post tabla según gestor seleccionad*/
										    		
										//	    }//fin succes consulta documentos de saf en server 19
										//	});//fin post consulta documentos de saf en server 19
							            }
							            else{
							            	$('#documentos').empty();
											$('#documentos').append(result.message);
											document.getElementById("loading").style.display = "none"; 
							            }
						  		   
							    		
								    }//fin success
								});//fin get permisos  	
					    	}
					    	else{
					    		$('#documentos').empty();
								$('#documentos').append(result.message);
								document.getElementById("loading").style.display = "none";
					    	}//else no tiene permisos reservados
					    }//fin success
					});//fin get permisos  		   
		    		
			    }//fin success
			});//fin post tabla según gestor seleccionad
			return false;
			}//FIN VALIDA RUC 
	
		}//fin if existe ruc
	}
</script>




<script type="text/javascript">
	function verCarpeta(id){	

		document.getElementById("loading").style.display = "block";
		var ruc = $("#ruc").val(); 

		var myWindow = window.open(id , id , "width=1400,height=720,scrollbars=NO,status=yes, toolbar=no, top=200, left=200, directories=0, titlebar=0");
		document.getElementById("loading").style.display = "none";

		return false;
	}
</script>

<script type="text/javascript">
	function parteRevisado(){
		var r = confirm("Estas segur@ que deseas dar por revisada la Denuncia?");
		if (r == true) {
			var ruc = $("#ruc").val(); 	
			urlrevisar = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/GuardarEstadoParteRevisado'?>";
			$.ajax({
			    url: urlrevisar,
			    type: 'POST',
			    data: {ruc: ruc},
			    success: function(result){ 		 	
			    	
					urlrev = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultaEstadoParte'?>";
					$.ajax({
					    url: urlrev,
					    type: 'POST',
					    data: {ruc: ruc},
					    success: function(result){ 		 	
					    	$('#estado_parte').empty();
		   					$('#estado_parte').append(result);				    					
							document.getElementById("loading").style.display = "none"; 		  		   
					    }//fin success
					});//fin post tabla según gestor seleccionad	  		   
			    }//fin success

			});//fin post tabla según gestor seleccionad
		}		
	}
</script>

<script type="text/javascript">
	function descargarCarpetaSIAU(id){	

		document.getElementById("loading").style.display = "block"; 

		urldown = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/DescargarCarpetaMerge_caro&ruc='?>"+id,
		window.open(urldown , id , "width=1400,height=720,scrollbars=NO,status=yes, toolbar=no, top=200, left=200, directories=0, titlebar=0");
		document.getElementById("loading").style.display = "none"; 

		
	}
</script>


<script type="text/javascript">
	function descargarCarpetaSIAUfecha(id){	

		document.getElementById("loading").style.display = "block"; 

		urldown = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/descargarCarpetaMergeFecha&ruc='?>"+id,
		window.open(urldown , id , "width=1400,height=720,scrollbars=NO,status=yes, toolbar=no, top=200, left=200, directories=0, titlebar=0");
		document.getElementById("loading").style.display = "none"; 

		
	}
</script>


<script type="text/javascript">
	function reservarDoc(id){	

		var r = confirm("Estas segur@ que deseas excluir el documento?");
		if (r == true) {
			urlreservar = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ReservarDocumento'?>";
			$.ajax({
			    url: urlreservar,
			    type: 'POST',
			    data: {id: id},
			    success: function(result){ 		

			    	var ruc = $("#ruc").val(); 	
			    	urldoc = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultarDocumentosDigRuc'?>";
					$.ajax({
					    url: urldoc,
					    type: 'POST',
					    data: {ruc: ruc},
					    success: function(result){ 		 	
					    	$('#documentos').empty();
	    					$('#documentos').append(result);
	    				}	    	
  		   			});//fin post tabla documentos

			    }//fin success

			});//fin post tabla según gestor seleccionad
			return false;

		}//fin confirmar

	}
</script>

<script type="text/javascript">
	function elimReserva(id){	

		var r = confirm("Estas segur@ que deseas eliminar la reserva del documento?");
		if (r == true) {
			
			urlel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/EliminarReservaDocumento'?>";
			$.ajax({
			    url: urlel,
			    type: 'POST',
			    data: {id: id},
			    success: function(result){ 		

			    	var ruc = $("#ruc").val(); 	
			    	urldoc = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultarDocumentosDigRuc'?>";
					$.ajax({
					    url: urldoc,
					    type: 'POST',
					    data: {ruc: ruc},
					    success: function(result){ 		 	
					    	$('#documentos').empty();
	    					$('#documentos').append(result);
	    				}	    	
  		   			});//fin post tabla documentos

			    }//fin success

			});//fin post tabla según gestor seleccionad
			return false;
		}//fin confirmar

	}
</script>

<script type="text/javascript">
	function revisarDoc(id){
		var r = confirm("Estas segur@ que deseas dar por revisado el informe?");
		if (r == true) {

			urlrev = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/RevisarDocumento'?>";
			$.ajax({
			    url: urlrev,
			    type: 'POST',
			    data: {id: id},
			    success: function(result){ 		

			    	var ruc = $("#ruc").val(); 	
			    	urldoc = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultarDocumentosDigRuc'?>";
					$.ajax({
					    url: urldoc,
					    type: 'POST',
					    data: {ruc: ruc},
					    success: function(result){ 		 	
					    	$('#documentos').empty();
	    					$('#documentos').append(result);
	    				}	    	
  		   			});//fin post tabla documentos

			    }//fin success

			});//fin post tabla según gestor seleccionad
			return false;

		}
	}
</script>


<!-- Incorporado  BUSCAR DOCUMENTOS DE SAF ANTE EVENTOS  KRO ******-->



<script type="text/javascript">
	function ActualizarDocSAF(id)
	{
		var r = confirm("Estas segur@ que deseas actualizar los documentos de SAF?");
		if (r == true) 
		{

		var ruc = $("#ruc").val(); 	
		urlsaf = "http://172.17.123.19/conect_saf/consulta_documentos.php";

 			$.ajax({
		 	 url: urlsaf,
			    type: 'GET',
			    data: {ruc: ruc},
			    success: function(result){ 		

			    	urlguarda = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/GuardaConsultaDocumentos'?>";
			    	$.ajax({
					    url: urlguarda,
					    type: 'POST',
					    data: {ruc: ruc},
					    success: function(result){

					    var ruc = $("#ruc").val(); 	
			    			urldoc = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultarDocumentosDigRuc'?>";
							$.ajax({
					    	url: urldoc,
					    	type: 'POST',
					    	data: {ruc: ruc},
					    	success: function(result)
					    		{ 		 	
					    			$('#documentos').empty();
	    							$('#documentos').append(result);
	    						}	    	
  		   					});//fin post tabla documentos

					    }//fin success guardar documentos
					});//fin post guardar documentos*/
 				

			    }//fin success consulta documentos de saf en server 19
			});//fin post consulta documentos de saf en server 19
	
		return false;
	
		} // fin IF
	}   // fin FUNCION
</script>




<script type="text/javascript">
	function MarcarFavorito(id){	

		var r = confirm("Estas segur@ que deseas marcar el documento como FAVORITO?");
		if (r == true) {
			urlreservar = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/MarcarFavoritoDoc'?>";
			$.ajax({
			    url: urlreservar,
			    type: 'POST',
			    data: {id: id},
			    success: function(result){ 		

			    	var ruc = $("#ruc").val(); 	
			    	urldoc = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultarDocumentosDigRuc'?>";
					$.ajax({
					    url: urldoc,
					    type: 'POST',
					    data: {ruc: ruc},
					    success: function(result){ 		 	
					    	$('#documentos').empty();
	    					$('#documentos').append(result);
	    				}	    	
  		   			});//fin post tabla documentos

			    }//fin success

			});//fin post tabla según gestor seleccionad
			return false;

		}//fin confirmar

	}
</script>


<script type="text/javascript">
	function NoMarcarFavorito(id){	

		var r = confirm("Estas segur@ que deseas eliminar la marca de FAVORITO?");
		if (r == true) {
			
			urlel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/NoMarcarFavoritoDoc'?>";
			$.ajax({
			    url: urlel,
			    type: 'POST',
			    data: {id: id},
			    success: function(result){ 		

			    	var ruc = $("#ruc").val(); 	
			    	urldoc = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultarDocumentosDigRuc'?>";
					$.ajax({
					    url: urldoc,
					    type: 'POST',
					    data: {ruc: ruc},
					    success: function(result){ 		 	
					    	$('#documentos').empty();
	    					$('#documentos').append(result);
	    				}	    	
  		   			});//fin post tabla documentos

			    }//fin success

			});//fin post tabla según gestor seleccionad
			return false;
		}//fin confirmar

	}
</script>

<!--
<script type="text/javascript">
	function verDocu(){
		if( document.getElementById("listadocu").style.display == "none" ){
			document.getElementById("listadocu").style.display = "block";

			$('#visualizarDocu').removeClass('col-md-12');
			$('#visualizarDocu').addClass('col-md-7');
			
			$('#ocultar').empty();
	    	$('#ocultar').append("Ocultar documentos");

		}
		else{
			document.getElementById("listadocu").style.display = "none";
			$('#visualizarDocu').removeClass('col-md-7');
			$('#visualizarDocu').addClass('col-md-12');

			$('#ocultar').empty();
	    	$('#ocultar').append("Ver documentos");
		}		
	}
</script>-->