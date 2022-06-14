<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="../start/protected/extensions/barcode/JsBarcode.all.js"></script>

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Causas Revisión Preclasificador</div><div class="subtitulo"></div>
		<div class="subtitulo">Desde RUC, es posible acceder a Ficha caso.</div>
	</div>

	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     


	<div id="tareas" style="padding: 10px;">
		
	</div>

</div>


<script type="text/javascript">
	$(document).ready(function() {


	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpetadigital/ListarCausasPrec'?>";
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
