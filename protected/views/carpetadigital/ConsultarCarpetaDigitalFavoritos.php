<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src='../start/js/validar_ruc.js' ></script>

<div class="pagina">

	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>

	<div class="col-md-12">
		<div style="border-bottom: 1px solid #dddddd;padding: 8px;">
 			<h4>Consultar Documentos FAVORITOS</h4>
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
										//	//		    data: {ruc: ruc},
										//			    success: function(result){ 		 	

													    	//alert(result)
															//consultar tabla de documentos
											    			urldoc = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultarDocumentosFavoritos'?>";
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
										//	//	    		
													    }//fin success
										//	//		});//fin post tabla según gestor seleccionad*/
										    		
										//	//    }//fin succes consulta documentos de saf en server 19

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

	});	
</script>



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
											    			urldoc = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultarDocumentosFavoritos'?>";
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

