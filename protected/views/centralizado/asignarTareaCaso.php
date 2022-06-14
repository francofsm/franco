<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Asignar Diligencias según Caso</div>
				<div class="subtitulo">
			<?php echo $fi->fis_nombre;?>/ Equipo <?php  if(isset($tguni->uni_codigo)) echo $tguni->uni_descripcion; ?>
		</div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     


<div class="tabla_min_asign" id="tabla_min">
</div>


<div class="div_asignacion">
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
		</div>

	<div class="row-inline-rev">
			<label>Fecha Plazo<span class="required">*</span></label>
			<input type="date" id="fec" name="fec" value="<?php echo date('Y-m-d'); ?>"></input>
	</div>


     <div class="row-inline-rev">
		<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
		<input type="text" id="ruc" name="ruc" onkeyup = "if(event.keyCode == 13) enterRuc(this.value)" autofocus/>
	</div>

	<div style="margin-top: -40px;margin-left: 290px;">
	    <label  style="font-weight: bolder;font-size: 14px;">Incluir Mov. de Carpeta</label>
	    <input type="checkbox" name="carp" id="carp">	                
	</div>

</div>



	<div style="position: absolute;margin-top: 11px;margin-left: 100px;z-index: 1;">
		<span name='eliminar' class='btn btn-primary' onclick='elimDilAsigna()'>Limpiar Tabla de Diligencias para Asignar</span>	
	</div>

	<div class="btn-parte-pendiente">
		<button id="btn_control" name="asignar" class="btn btn-primary" onclick="asignar()">Guardar Asignación</button>
	</div>

		<div id="tareas" style="padding: 10px;">
		
		</div>

</div>



<script type="text/javascript">
	function elimDilAsigna(){
		document.getElementById("loading").style.display = "block" ;
	    $.ajax({
		    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/LimpiarTemporalAsignar'?>",
		    type: 'POST',		   
		    success: function(result){  
		                      
		    urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpAsignar'?>";
		    $.ajax({
			    url: urltable,
			    type: 'POST',
			    success: function(result){  
			                      
			        $('#tareas').empty();
		    		$('#tareas').append(result); 
			        document.getElementById("loading").style.display = "none" ;  

			        urlmin = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/MinAsignadoCaso'?>";
			        $.ajax({
					    url: urlmin,
					    type: 'POST',
					    success: function(data){  
					                      
					        $('#tabla_min').empty();
				    		$('#tabla_min').append(data);   

					    }//fin success
					});//fin post tabla según gestor seleccionado 

			    }//fin success
			});//fin post tabla según gestor seleccionado

		
		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

		return false;

	}
</script>



<script type="text/javascript">
	$(".chosen_fun").chosen();
</script>

<script type="text/javascript">
	function elimTarea(id){		
		document.getElementById("loading").style.display = "block" ;
	    $.ajax({
		    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/EliminarTareaTemporal&id='?>"+id,
		    type: 'POST',		   
		    success: function(result){  
		                      
		    urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpAsignar'?>";
		    $.ajax({
			    url: urltable,
			    type: 'POST',
			    success: function(result){  
			                      
			        $('#tareas').empty();
		    		$('#tareas').append(result); 
			        document.getElementById("loading").style.display = "none" ;     

			        urlmin = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/MinAsignadoCaso'?>";
			        $.ajax({
					    url: urlmin,
					    type: 'POST',
					    success: function(data){  
					                      
					        $('#tabla_min').empty();
				    		$('#tabla_min').append(data);   

					    }//fin success
					});//fin post tabla según gestor seleccionado 


			    }//fin success
			});//fin post tabla según gestor seleccionado

		
		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

		return false;

	}
</script>

<script type="text/javascript">
	$(document).ready(function() {


	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpAsignar'?>";
	    $.ajax({
		    url: urltable,
		    type: 'POST',
		    success: function(result){  
		                      
		        $('#tareas').empty();
	    		$('#tareas').append(result); 

	    		urlmin = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/MinAsignadoCaso'?>";
		        $.ajax({
				    url: urlmin,
				    type: 'POST',
				    success: function(data){  
				                      
				        $('#tabla_min').empty();
			    		$('#tabla_min').append(data);   

				    }//fin success
				});//fin post tabla según gestor seleccionado  	

		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;

	});	
</script>

<script type="text/javascript">
function enterRuc($ruc){
        buscar=$("#ruc").prop("value")
        var ruc = buscar.toUpperCase();

		$.ajax({
			type: 'POST',
			url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/DilPendientesAsignar&ruc='?>"+ruc,
			dataType: "json",
			success: function(data) {
				if(data.status === "success") {
					urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpAsignar'?>";
				    $.ajax({
					    url: urltable,
					    type: 'POST',
					    success: function(result){  
					                      
					        $('#tareas').empty();
				    		$('#tareas').append(result); 
					        document.getElementById("loading").style.display = "none" ;   
					        document.getElementById("ruc").value=""; 
							document.getElementById("ruc").focus();  


							urlmin = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/MinAsignadoCaso'?>";
					        $.ajax({
							    url: urlmin,
							    type: 'POST',
							    success: function(data){  
							                      
							        $('#tabla_min').empty();
						    		$('#tabla_min').append(data);   

							    }//fin success
							});//fin post tabla según gestor seleccionado 

					    }//fin success
					});//fin post tabla según gestor seleccionad
				}
				else{
					alert(data.message);
				}
			}//FIN SUCCESS	

		});
}
</script>

<script type="text/javascript">
    function MarcarTodos(source) {
        var aInputs = document.getElementsByTagName('input');
        for (var i=0;i<aInputs.length;i++) {
            if (aInputs[i] != source && aInputs[i].className == source.className) {
                aInputs[i].checked = source.checked;
            }
        }
    }
	function MarcarTodosElim(source) {
        var aInputs = document.getElementsByTagName('input');
        for (var i=0;i<aInputs.length;i++) {
            if (aInputs[i] != source && aInputs[i].className == source.className) {
                aInputs[i].checked = source.checked;
            }
        }
    }
    
</script>

 <script type="text/javascript">
    function asignar(){

		var fun = $("#fun").val();
		var fec = $("#fec").change({ dateFormat: "yyyy/mm/dd" }).val();

		if( document.getElementById("carp").checked == true ){
			var carp = 1;
		}
		else{
			var carp = 0;
		}

		
	 	if(fun!="" || fec!=""){
	 		document.getElementById("loading").style.display = "block" ;

	 		$.ajax({
			type: 'POST',
			url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/GuardarAsignacionCaso&fun='?>"+fun+'&fec='+fec+'&carp='+carp,
			dataType: "json",
			  	success: function(data) {
			   		
			   		if(data.status === "success") {		   			

			   			urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpAsignar'?>";
					    $.ajax({
						    url: urltable,
						    type: 'POST',
						    success: function(result){  
						                      
						        $('#tareas').empty();
					    		$('#tareas').append(result); 
						        document.getElementById("loading").style.display = "none" ;   
						        document.getElementById("ruc").value=""; 
								document.getElementById("ruc").focus(); 

								urlmin = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/MinAsignadoCaso'?>";
						        $.ajax({
								    url: urlmin,
								    type: 'POST',
								    success: function(data){  
								                      
								        $('#tabla_min').empty();
							    		$('#tabla_min').append(data);   

								    }//fin success
								});//fin post tabla según gestor seleccionado 

						    }//fin success
						});//fin post tabla según gestor seleccionad			   		
			   			
			   		}
			   		else{
			   			document.getElementById("loading").style.display = "none" ;
			   			alert(data.message);
			   		}		    		
				}//FIN SUCCESS					
			});
			return false;
	 	}
	 	else{
	 		alert("Debe seleccionar un funcionario y fecha de plazo");
	 	}
    }//FIN FUNCION ELIMINAR	
</script>