<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Ejecutar Diligencias/Tareas según Caso</div><div class="subtitulo">Puedes consultar las diligencias pendientes de ejecución por Caso y ejecutar las diligencias que indiques en el listado</div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     

     <div class="row-inline-rev">
		<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
		<input type="text" id="ruc" name="ruc" onkeyup = "if(event.keyCode == 13) enterRuc(this.value)" autofocus/>
	</div>

	<div style="position: absolute;margin-top: 11px;margin-left: 100px;z-index: 1;">
		<span name='eliminar' class='btn btn-primary' onclick='elimDilEjecuta()'>Limpiar Tabla de Diligencias para ejecutar</span>	
	</div>

	<div class="btn-parte-consulta">
		<button id="btn_recepcion" name="ejecutar" class="btn btn-info" onclick="cargaLaboral()">Consultar Carga Laboral</button>
	</div>



	<div class="btn-fecha-ejecutar">
		<label>Fecha Ejecución<span class="required">*</span></label>
			<input type="date" id="fec" name="fec" value="<?php echo date('Y-m-d'); ?>"></input>
	</div>

	<div class="btn-parte-pendiente">
		<button id="btn_control" name="asignar" class="btn btn-primary" onclick="ejecutar()">Guardar Ejecutar</button>
	</div>

		<div id="tareas" style="padding: 10px;">
		
		</div>


<!--MODAL-->

		<div id="openConsulta" class="modalDialog">
			<div class="col-md-12">
				<a href="#close" title="Close" class="close">X</a>	
				<div class="col-md-6 servicio">
					<div class="titulo_form">Minutos Ejecutados del día</div>
					<div class="item" style="text-align: center;">
					    <div id="datos2">
    	
     					</div>
					    	
					</div>				
					
				</div>	
			</div>	
		</div>
	
<!--FIN-->
</div>

<script type="text/javascript">
	$(".chosen_fun").chosen();
</script>

<script type="text/javascript">
	function cargaLaboral(){

		miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/consultaCargaLaboralFun'?>";
		
		miurlfin = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/EjecutarTareaCAso#openConsulta'?>";		

		$.ajax({
		   	url: miurl,
		   	type: 'POST',
		   	success: function(result){
		   		 $('#datos2').html(result);
		   		 window.location.href = miurlfin;   
		   	}//fin success
		});

		return false;
	}
</script>


<script type="text/javascript">
	function elimDilEjecuta(){
		document.getElementById("loading").style.display = "block" ;
	    $.ajax({
		    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/LimpiarTemporalEjecutar'?>",
		    type: 'POST',		   
		    success: function(result){  
		                      
		    urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpEjecutar'?>";
		    $.ajax({
			    url: urltable,
			    type: 'POST',
			    success: function(result){  
			                      
			        $('#tareas').empty();
		    		$('#tareas').append(result); 
			        document.getElementById("loading").style.display = "none" ;     
			    }//fin success
			});//fin post tabla según gestor seleccionado

		
		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

		return false;

	}
</script>

<script type="text/javascript">
	function elimTarea(id){		
		document.getElementById("loading").style.display = "block" ;
	    $.ajax({
		    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/EliminarTareaTemporalEjecutar&id='?>"+id,
		    type: 'POST',		   
		    success: function(result){  
		                      
		    urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpEjecutar'?>";
		    $.ajax({
			    url: urltable,
			    type: 'POST',
			    success: function(result){  
			                      
			        $('#tareas').empty();
		    		$('#tareas').append(result); 
			        document.getElementById("loading").style.display = "none" ;     
			    }//fin success
			});//fin post tabla según gestor seleccionado

		
		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

		return false;

	}
</script>

<script type="text/javascript">
	$(document).ready(function() {


	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpEjecutar'?>";
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
function enterRuc($ruc){
        buscar=$("#ruc").prop("value")
        var ruc = buscar.toUpperCase();

		$.ajax({
			type: 'POST',
			url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/DilPendientesEjecutar&ruc='?>"+ruc,
			dataType: "json",
			success: function(data) {
				if(data.status === "success") {
					urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpEjecutar'?>";
				    $.ajax({
					    url: urltable,
					    type: 'POST',
					    success: function(result){  
					                      
					        $('#tareas').empty();
				    		$('#tareas').append(result); 
					        document.getElementById("loading").style.display = "none" ;   
					        document.getElementById("ruc").value=""; 
							document.getElementById("ruc").focus();  
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
    function ejecutar(){

		
		var fec = $("#fec").change({ dateFormat: "yyyy/mm/dd" }).val();

	 	if(fec!=""){
	 		document.getElementById("loading").style.display = "block" ;

	 		$.ajax({
			type: 'POST',
			url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/GuardarEjecutarCaso&fec='?>"+fec,
			dataType: "json",
			  	success: function(data) {
			   		
			   		if(data.status === "success") {		   			

			   			urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpEjecutar'?>";
					    $.ajax({
						    url: urltable,
						    type: 'POST',
						    success: function(result){  
						                      
						        $('#tareas').empty();
					    		$('#tareas').append(result); 
						        document.getElementById("loading").style.display = "none" ;   
						        document.getElementById("ruc").value=""; 
								document.getElementById("ruc").focus();  
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
    }//FIN FUNCION ELIMINAR	
</script>