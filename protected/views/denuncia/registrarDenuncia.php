<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Registrar Denuncias</div><div class="subtitulo">Registro de partes recepcionados</div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>


<div>
	<div class="div-form-uno">	
		<form id="forecep" class="form-horizontal"  method="post" name="forecep">

		<div class="row-inline-normal">
			<label>Denuncia <span class="required">*</span></label>
			<select id="tipdenun" name="tipdenun" class="input-md">
				<option value="">Seleccionar Tipo de Denuncia</option>	
			<?php 
			foreach ($tipdenun as $den) { ?>
				<option value="<?php echo $den->cod_tipdenuncia; ?>"><?php echo $den->gls_tipdenuncia; ?></option><?php
			} 
			?>
			</select>
		</div>
		<div class="row-inline-small">
			<label>N° Denuncia</label>
			<input type="text" id="num" name="num" maxlength="200" placeholder="N°"/>
		</div>
		<div class="row-inline-small">
			<label>N° Hasta</label>
			<input type="text" id="num_hasta" name="num_hasta" maxlength="200" placeholder="N°"/>
		</div>
		<div class="row-inline-min">
			<label>Fecha <span class="required">*</span></label>
			<input type="date" id="fec_ingreso" name="fec_ingreso" value="<?php echo date('Y-m-d'); ?>" required="required"></input>
		</div>
		<div class="row-inline-normal">
			<label>Origen Caso <span class="required">*</span></label>
			<select id="origen" name="origen" class="input-md">
				<option value="">Seleccionar Origen</option>	
			<?php 
			foreach ($origen as $ori) { ?>
				<option value="<?php echo $ori->cod_origencaso; ?>"><?php echo $ori->gls_origencaso; ?></option><?php
			} 
			?>
			</select>
		</div>

		<div class="row-inline-normal">
			<label>Comisaria<span class="required">*</span></label>
			<select id="comi" name="comi" class="input-md" required="required">	
			<?php 
			foreach ($comi as $comi) { ?>
				<option value="<?php echo $comi->id_comisaria; ?>"><?php echo $comi->gls_comisaria; ?></option><?php
			} 
			?>
			</select>
		</div>
		
		<div class="row-inline-normal">
			<label>Destacamento<span class="required">*</span></label>
			<select id="desta" name="desta" class="input-md"  required="required">	
			<?php 
			foreach ($desta as $desta) { ?>
				<option value="<?php echo $desta->cod_destaca; ?>"><?php echo $desta->gls_destacamento; ?></option><?php
			} 
			?>
			</select>
		</div>

		<div class="row-inline-normal">
			<label>Procedencia</label>
			<input type="text" id="proced" name="proced" placeholder="1° Comisaria de ..." style="text-transform:uppercase;" />
		</div>
		<div class="row-inline-normal">
			<label>Fun. Entrega</label>
			<input type="text" id="funentrega" name="funentrega" placeholder="Cabo ..." style="text-transform:uppercase;"/>
		</div>
		<div class="row-inline-normal">
			<label>Observaciones</label>
			<textarea id="obs" name="obs" style="text-transform:uppercase;"></textarea>
		</div>


		<div class="row-inline-normal">
			<button type="submit" class="btn btn-success" title="Agregar Registros">Recepción</button>
		</div>
	

		<div class="msj-recepcion" id="div_mensaje"></div>

		</form>
	
		<div id="result"></div>
	</div>


<!--tabla-->

 	<div class="btn-derivar-partemasivo">
 		<a href="<?php echo Yii::app()->createUrl("Denuncia/DerivarDenuncia")?>">
		<button id="btn_derivar" name="derivar" class="btn btn-info">Derivar Partes</button></a>
	</div>

	 <div class="btn-declarar-parteingresado"> 
		<a href="<?php echo Yii::app()->createUrl("Denuncia/DeclararIngreso")?>">
		<button id="btn_declarar" name="declarar" class="btn btn-info">Declarar Partes Ingresados</button></a>
	</div>

 	<div class="btn-eliminar-parte">
		<button id="btn_control" name="pendiente" class="btn btn-default" onclick="PartePendiente()">Pendiente</button>
	</div>

	<div class="btn-parte-a-control">
		<button id="btn_control" name="control" class="btn btn-primary" onclick="ParteControl()">Control</button>
	</div>

	<div class="btn-partes-pendientes">
		<button id="btn_eliminar" name="eliminar" class="btn btn-warning" onclick="EliminarParte()">Eliminar</button>		
	</div>


	<div id="partes" style="padding: 10px;overflow-x: auto;">
		
			
	</div>

</div>

</div>

<script type="text/javascript">
    function PartePendiente(){

    	var j=0;
    	var pendiente=[];
    	var checkedValue = null; 
		var inputElements = document.getElementsByClassName('checPendiente');
		for(var i=0; inputElements[i]; ++i){
		      if(inputElements[i].checked){
		           checkedValue = inputElements[i].value;
		           pendiente[j]=checkedValue;
		           j++;
		      }
		}//FIN FOR

	 	if(pendiente!=""){
	 		document.getElementById("loading").style.display = "block" ;

	 		$.ajax({
			    type: 'POST',
			    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/PartePendiente&pendiente='?>"+pendiente,

			    	success: function(data) {
			    		urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/ListarPartes'?>";
					    $.ajax({
						    url: urltable,
						    type: 'POST',
						    success: function(result){  
						        $('#partes').empty();
					    		$('#partes').append(result);  
						    }//fin success
						});//fin post tabla según gestor seleccionado
						document.getElementById("loading").style.display = "none" ;	    		
					}
					
			});
	 	}

    }//FIN FUNCION PARTE PENDIENTE	
</script>

<script type="text/javascript">
	  function ParteControl(){

    	var j=0;
    	var control=[];
    	var checkedValue = null; 
		var inputElements = document.getElementsByClassName('checControl');
		for(var i=0; inputElements[i]; ++i){
		      if(inputElements[i].checked){
		           checkedValue = inputElements[i].value;
		           control[j]=checkedValue;
		           j++;
		      }
		}//FIN FOR

	 	if(control!=""){
	 		document.getElementById("loading").style.display = "block" ;

	 		$.ajax({
			    type: 'POST',
			    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/ParteControl&control='?>"+control,

			    	success: function(data) {
			    		urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/ListarPartes'?>";
					    $.ajax({
						    url: urltable,
						    type: 'POST',
						    success: function(result){  
						        $('#partes').empty();
					    		$('#partes').append(result);  
						    }//fin success
						});//fin post tabla según gestor seleccionado
						document.getElementById("loading").style.display = "none" ;	  		    		
					}
					
			});
	 	}

    }//FIN FUNCION PARTE EN CONTROL	
</script>

<script type="text/javascript">
	    function EliminarParte(){

    	var j=0;
    	var elim=[];
    	var checkedValue = null; 
		var inputElements = document.getElementsByClassName('checElim');
		for(var i=0; inputElements[i]; ++i){
		      if(inputElements[i].checked){
		           checkedValue = inputElements[i].value;
		           elim[j]=checkedValue;
		           j++;
		      }
		}//FIN FOR

		//alert(elim);

	 	if(elim!=""){
	 		//alert(elim);
	 		document.getElementById("loading").style.display = "block" ;
	 		$.ajax({
			    type: 'POST',
			    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/EliminarPartes&elim='?>"+elim,

			    	success: function(data) {
			    		urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/ListarPartes'?>";
					    $.ajax({
						    url: urltable,
						    type: 'POST',
						    success: function(result){  
						        $('#partes').empty();
					    		$('#partes').append(result);  
						    }//fin success
						});//fin post tabla según gestor seleccionado
						document.getElementById("loading").style.display = "none" ;	  	    		
					}
					
			});
	 	}

    }//FIN FUNCION ELIMINAR	
</script>


<script type="text/javascript">
	$(".chosen_fun").chosen();
	$(".chosen_dil").chosen();
</script>

<script type="text/javascript">
	$(document).ready(function() {

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/ListarPartes'?>";
	    $.ajax({
		    url: urltable,
		    type: 'POST',
		    success: function(result){  
		        $('#partes').empty();
	    		$('#partes').append(result);  
		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;


	});	
</script>

<script type="text/javascript">
	 $("#forecep").submit(function(event) {

	 	$('#div_mensaje').empty();

	 	var datastring = $("#forecep").serialize(); 
    	document.getElementById("loading").style.display = "block";
    	urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/GuardarParte'?>";

		$.ajax({
	        url: urlsave,
	        type: 'POST',
	        data: datastring,
	        dataType: "json",
	        success: function(result){  
	            if(result.status === "success") {	    


	              	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/ListarPartes'?>";
				    $.ajax({
					    url: urltable,
					    type: 'POST',
					    success: function(result){  
					        $('#partes').empty();
				    		$('#partes').append(result);  
					    }//fin success
					});//fin post tabla según gestor seleccionado

					$('#div_mensaje').empty(); 
			    	$('#div_mensaje').append("<li>"+result.message+ "</li>"); 
			    	$('#div_mensaje').fadeIn('slow').delay(4000).hide(0);	

				    document.getElementById("tipdenun").value=""; 
				    document.getElementById("num").value=""; 
					document.getElementById("num_hasta").value=""; 
					document.getElementById("origen").value=""; 
					document.getElementById("proced").value=""; 
					document.getElementById("funentrega").value=""; 
					document.getElementById("obs").value=""; 

					document.getElementById("loading").style.display = "none";
	            }//fin succes
	            else{
	            	document.getElementById("loading").style.display = "none";					

			    	$('#div_mensaje').empty(); 
			    	$('#div_mensaje').append("<li>"+result.message+ "</li>"); 
			    	$('#div_mensaje').fadeIn('slow').delay(4000).hide(0);       
	            }     
	        }//fin success
	    });
	    return false;


	 });
</script>