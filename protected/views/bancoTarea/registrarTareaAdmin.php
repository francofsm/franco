<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Decretar Tareas Administrativas (Bloques de Tiempo)</div><div class="subtitulo">
			<?php echo $fi->fis_nombre;?>/ Equipo <?php  if(isset($tguni->uni_codigo)) echo $tguni->uni_descripcion; ?>
		</div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>

     <div class="col-md-3"> 
     	<div class="form-tvwork">
  		<form action="" id="formoid" method="post"	>	

		<div class="row-inline-medi">
			<label>FUNCIONARIO:</label>
			<select name="fun" id="fun" class="chosen_fun" >
				<option value="">Seleccionar Funcionario</option>
				<?php 
					foreach ($fun as $c => $value) {	   									    	
						echo '<option value="'.$fun[$c]->fun_rut .'">'. $fun[$c]->fun_ap_paterno.' '.$fun[$c]->fun_nombre.' '.$fun[$c]->fun_nombre2 .'</option>';
					}
				?>
			</select>
		</div><br><br>

		<div class="row-inline-medi">
			<label>Tareas:</label>
			<select name="tarea" id="tarea" class="chosen_dil" >
				<option value="">Seleccionar Tarea</option>
				<?php 
					foreach ($tareas as $c => $value) {	   									    	
						echo '<option value="'.$tareas[$c]->cod_instruccion .'">'. $tareas[$c]->gls_instruccion .' ( '.$tareas[$c]->tiempo_instruccion.' min.)</option>';
					}
				?>
			</select>
			<div id="select_dil"></div>
		</div>	

		<br><br><br>
		<div class="row-inline-rev">
			<label>Fecha Inicio<span class="required">*</span></label>
			<input type="date" id="fec_ini" name="fec_ini" value="<?php echo date('Y-m-d'); ?>"></input>
		</div><br>

		<div class="row-inline-rev">
			<label>Fecha Fin<span class="required">*</span></label>
			<input type="date" id="fec_fin" name="fec_fin" value="<?php echo date('Y-m-d'); ?>"></input>
		</div><br>

		<div class="row-inline-medi">
			<label>Observaciones</label>
			<textarea style="height: 85px;" name="obs" id="obs"></textarea>
		</div><br>


		<!--<div class="row-inline-medi">
			<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
			<input type="text" id="ruc" name="ruc" maxlength="12" minlength="12"  autofocus  />
		</div>-->
		

		<div class="row-inline-rev">
			<input type="submit" id="submitButton"  name="submitButton" class="btn btn-info" value="Guardar Tarea">
		</div>

		<div id="error_msj" style="display: none"></div>

		</form>
		</div><!--fin div tvwork-->
     </div>


     <div class="col-md-7"> 
		<div id="tareas" style="padding: 10px;">
		
			
		</div>
     </div>	

</div>


<script type="text/javascript">
	$(".chosen_fun").chosen();
	$(".chosen_dil").chosen();
</script>

<script type="text/javascript">
	$(document).ready(function() {

	urldil = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/SeleccionTarea'?>";

	$.ajax({
	      url: urldil,
	      type: 'POST',
	      dataType: "json",
	      success: function(result){  
	          if(result.status === "success") {
	              $('#select_dil').empty();
	              $('#select_dil').append(result.message);   
	          }else alert("error");    
	      }//fin success
	});

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarTareaAdmin'?>";
	    $.ajax({
		    url: urltable,
		    type: 'POST',
		    success: function(result){  
		                      
		        $('#tareas').empty();
	    		$('#tareas').append(result);   
	    		
		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;


	});	
</script>

<script type="text/javascript">
	function elimdil(id){		
		urldel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/EliminarSeleccionTarea'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urldel,
		      type: 'POST',
		      data: {id: id},
		      dataType: "json",
		      success: function(result){  
		          if(result.status === "success") {
		              $('#select_dil').empty();
		              $('#select_dil').append(result.message);  
		              document.getElementById("loading").style.display = "none"; 
		          }else alert("error");    
		      }//fin success
		});
		return false;
	}
</script>

<script type="text/javascript">
	function elimdiltodo(id){		
		urldel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/EliminarSeleccionTareaTodos'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urldel,
		      type: 'POST',
		      data: {id: id},
		      dataType: "json",
		      success: function(result){  
		          if(result.status === "success") {
		              $('#select_dil').empty();
		              $('#select_dil').append(result.message);  
		              document.getElementById("loading").style.display = "none"; 
		          }else alert("error");    
		      }//fin success
		});
		return false;
	}
</script>

<script type="text/javascript">
	function elimTarea(id){		
		urlelim = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/EliminarTarea'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urlelim,
		      type: 'POST',
		      data: {id: id},
		      success: function(result){  

		        urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarTareaAdmin'?>";
				    $.ajax({
					    url: urltable,
					    type: 'POST',
					    success: function(result){  
					        document.getElementById("loading").style.display = "none";              
					        $('#tareas').empty();
				    		$('#tareas').append(result);   
				    		
					             
					    }//fin success
					});//fin post tabla según gestor seleccionado
				      
		      }//fin success
		});
		return false;
	}
</script>

<script type="text/javascript">
$( ".chosen_dil" ).change(function() {

		var tarea = $("#tarea").val(); 
		urltarea = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/GuardarSeleccionTarea'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
	        url: urltarea,
	        type: 'POST',
	        data: {tarea: tarea},
	        dataType: "json",
	        success: function(result){  
	            if(result.status === "success") {
	                $('#select_dil').empty();
	                $('#select_dil').append(result.message);   
	                document.getElementById("loading").style.display = "none";
	            }
	            else{
	                alert(result.message)      
	            }     
	        }//fin success
	    });
	    return false;
});
</script>

<script type="text/javascript">
 $("#formoid").submit(function(event) {
    
    var fun = $("#fun").val(); 
    var fec = $("#fec_ini").change({ dateFormat: "yyyy/mm/dd" }).val()
    var fecfin = $("#fec_fin").change({ dateFormat: "yyyy/mm/dd" }).val()
    if(fun==""){
    	 document.getElementById("error_msj").style.display = "block";
    	 $('#error_msj').empty();
         $('#error_msj').append("Error, debes seleccionar funcionario");        
    }
    else if(fec==""){
    	 document.getElementById("error_msj").style.display = "block";
    	 $('#error_msj').empty();
         $('#error_msj').append("Error, debes seleccionar una fecha de la Tarea");        
    }
    else{
    	
    	 $('#error_msj').empty();
    	 document.getElementById("error_msj").style.display = "none";

    	var datastring = $("#formoid").serialize(); 
    	document.getElementById("loading").style.display = "block";
    	urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/GuardarTareaAdmin'?>";

		$.ajax({
	        url: urlsave,
	        type: 'POST',
	        data: datastring,
	        dataType: "json",
	        success: function(result){  
	            if(result.status === "success") {	    
 
	              	
	              	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarTareaAdmin'?>";
				    $.ajax({
					    url: urltable,
					    type: 'POST',
					    success: function(result){  
					        document.getElementById("loading").style.display = "none";              
					        $('#tareas').empty();
				    		$('#tareas').append(result);   
				    		
					             
					    }//fin success
					});//fin post tabla según gestor seleccionado

				    document.getElementById("ruc").value=""; 					
				    document.getElementById("obs").value=""; 					
					document.getElementById("loading").style.display = "none";
	            }//fin succes
	            else if(result.status === "error") {
	            	document.getElementById("loading").style.display = "none";
					document.getElementById("error_msj").style.display = "block";
			    	 $('#error_msj').empty();
			         $('#error_msj').append(result.message);        
	            }     
	        }//fin success
	    });
	    return false;
    }
   	
   	return false;

 });
</script>