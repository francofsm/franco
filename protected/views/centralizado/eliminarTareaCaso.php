<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Eliminar Diligencias/Tareas por Caso</div><div class="subtitulo">Permite eliminar diligencias pendientes de ejecución</div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     


     <div class="row-inline-rev">
		<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
		<input type="text" id="ruc" name="ruc" onkeyup = "if(event.keyCode == 13) enterRuc(this.value)" autofocus/>
	</div>

	<div style="position: absolute;margin-top: 11px;margin-left: 100px;z-index: 1;">
		<span name='eliminar' class='btn btn-primary' onclick='elimDilEliminar()'>Limpiar Tabla de Diligencias para Eliminar</span>	
	</div>


	<div class="btn-parte-pendiente">
		<button id="btn_control" name="eliminar" class="btn btn-primary" onclick="eliminar()">Guardar Eliminar</button>
	</div>

		<div id="tareas" style="padding: 10px;">
		
		</div>


</div>



<script type="text/javascript">
	function elimDilEliminar(){
		document.getElementById("loading").style.display = "block" ;
	    $.ajax({
		    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/LimpiarTemporalEliminar'?>",
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
		    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/EliminarTareaTmpEliminar&id='?>"+id,
		    type: 'POST',		   
		    success: function(result){  
		                      
		    urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpEliminar'?>";
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


	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpEliminar'?>";
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
			url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/DilPendientesEliminar&ruc='?>"+ruc,
			dataType: "json",
			success: function(data) {
				if(data.status === "success") {
					urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpEliminar'?>";
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
    function eliminar(){

	 	document.getElementById("loading").style.display = "block" ;

	 	$.ajax({
			type: 'POST',
			url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/GuardarEliminarCaso'?>",
			dataType: "json",
			  	success: function(data) {
			   		
			   		if(data.status === "success") {		   			

			   			urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligenciasTmpEliminar'?>";
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
	 	
    }//FIN FUNCION ELIMINAR	
</script>