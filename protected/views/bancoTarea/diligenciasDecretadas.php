<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<script src="../start/protected/extensions/barcode/JsBarcode.all.js"></script>



<div class="pagina">




	<div class="div-titulo">		
		<div class="titulo">Listado de Diligencias Decretadas</div><div class="subtitulo">Consultar todas las diligencias decretadas según la fecha en que decretó</div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     


	<div class="col-md-33">
	<div class="row-inline-rev" style="margin-left: 100px;padding: 20px;border: 1px solid #d3ced2;">
		<label style="font-size: 18px;font-weight: 600;">Fecha Inicio Decreta<span class="required">*</span></label>
		<input style="font-size: 19px;padding: 4px;" type="date" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" onchange="consultar(event);"></input>
	</div>		
	</div>

	<div class="col-md-33">
	<div class="row-inline-rev" style="margin-left: 100px;padding: 20px;border: 1px solid #d3ced2;">
		<label style="font-size: 18px;font-weight: 600;">Fecha Fin Decreta<span class="required">*</span></label>
		<input style="font-size: 19px;padding: 4px;" type="date" id="fin" name="fin" value="<?php echo date('Y-m-d'); ?>" onchange="consultar(event);"></input>
	</div>		
	</div>


<div>

	</div>



		<div id="tareas" style="padding: 10px;">
		
	
		</div>

<script type="text/javascript">
	function consultar(e){


  	var fec = $("#fecha").change({ dateFormat: "yyyy/mm/dd" }).val()
  	var fec_fin = $("#fin").change({ dateFormat: "yyyy/mm/dd" }).val()

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarDiligenciasDecretadas'?>";
	    $.ajax({
		    type: 'POST',
		    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarDiligenciasDecretadas&fec='?>"+fec+'&fec_fin='+fec_fin,
		    success: function(result){  

		    	$('#tareas').empty();
	    		$('#tareas').append(result); 

		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;

}
</script>

<script type="text/javascript">
	$(document).ready(function() {


	var fec = $("#fecha").change({ dateFormat: "yyyy/mm/dd" }).val();
	var fec_fin = $("#fin").change({ dateFormat: "yyyy/mm/dd" }).val()

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarDiligenciasDecretadas'?>";
	    $.ajax({
		    type: 'POST',
		    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarDiligenciasDecretadas&fec='?>"+fec+'&fec_fin='+fec_fin,
		    success: function(result){  

		    	$('#tareas').empty();
	    		$('#tareas').append(result); 
		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;

	});	
</script>