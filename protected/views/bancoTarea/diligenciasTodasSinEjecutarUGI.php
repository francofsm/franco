<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Listado de TODAS las Diligencias Sin Ejecutar UGI</div><div class="subtitulo"></div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     




		<div id="tareas" style="padding: 10px;">
		
		</div>

<script type="text/javascript">
	$(document).ready(function() {

		mes=$("#mes").prop("value");
		document.getElementById("loading").style.display = "block" ;

		urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListardiligenciasTodasSinEjecutarUGI'?>"

	    $.ajax({
		    type: 'POST',
		    url: urltable,
		    success: function(result){  

		    	$('#tareas').empty();
	    		$('#tareas').append(result); 	                      
	    		 document.getElementById("loading").style.display = "none"; 
		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;

	});	
</script>

<script type="text/javascript">
	function consultar(e){

	document.getElementById("loading").style.display = "block" ;

	mes=$("#mes").prop("value");

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListardiligenciasTodasSinEjecutarUGI'?>"
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
