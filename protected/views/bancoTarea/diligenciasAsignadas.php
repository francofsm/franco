<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Listado de Diligencias Asignadas Pendientes de Ejecución</div><div class="subtitulo"></div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     


	<div class="col-md-33">
		<div class="row-inline-medi">
			<label>MES DECRETA:</label>
			<select name="mes" id="mes" onchange="consultar(event);" >
				<?php 
				$array_mes = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

					foreach ($meses as $c => $value) {	   									    	
						echo '<option value="'.$meses[$c]->mes_decreta .'">'. $array_mes[$meses[$c]->mes_decreta].'</option>';
					}
				?>
			</select>
		</div>	
	</div>  


		<div id="tareas" style="padding: 10px;">
		
		</div>

<script type="text/javascript">
	$(document).ready(function() {

		mes=$("#mes").prop("value");
		document.getElementById("loading").style.display = "block" ;
		
		urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarDiligenciasAsignadas&mes='?>"+mes,
	    $.ajax({
		    type: 'POST',
		    url: urltable,
		    success: function(result){  
		    	document.getElementById("loading").style.display = "none" ;   
		    	$('#tareas').empty();
	    		$('#tareas').append(result); 	                      

		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;

	});	
</script>

<script type="text/javascript">
	function consultar(e){

	document.getElementById("loading").style.display = "block" ;

	mes=$("#mes").prop("value");

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarDiligenciasAsignadas&mes='?>"+mes,
	    $.ajax({
		    url: urltable,
		    type: 'POST',
		    success: function(result){  
		        document.getElementById("loading").style.display = "none" ;           
		        $('#tareas').empty();
	    		$('#tareas').append(result); 
		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;
 }
</script>
