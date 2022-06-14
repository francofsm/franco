<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<script src="../start/protected/extensions/barcode/JsBarcode.all.js"></script>



<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Listado Carpetas movidas</div><div class="subtitulo">Listado de movimientos de carpetas realizados</div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     


	<div class="col-md-33">
			<label>FUNCIONARIO:</label>
			<select name="ubicacion" id="ubicacion" class="chosen_fun" onchange="consultar(event);">
				
				<?php 
					foreach ($fun as $c => $value) {	   									    	
						echo '<option value="'.$fun[$c]->fun_rut .'">'. $fun[$c]->fun_ap_paterno.' '.$fun[$c]->fun_nombre.' '.$fun[$c]->fun_nombre2 .'</option>';
					}
				?>
			</select>
		</div>


<div>

	</div>



		<div id="carpetas" style="padding: 10px;">
		
	
		</div>


<script type="text/javascript">
	$(".chosen_fun").chosen();
</script>

<script type="text/javascript">
	function consultar(e){


	var cod = $("#ubicacion").val(); 

  	$.ajax({
	    type: 'POST',
	    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/ListarMisMovimientos&cod='?>"+cod,
	    success: function(result){  

	    	$('#carpetas').empty();
	   		$('#carpetas').append(result); 

		             
	    }//fin success
	});//fin post tabla según gestor seleccionado

	return false;


}
</script>

<script type="text/javascript">
	$(document).ready(function() {

	var cod = $("#ubicacion").val(); 

  	$.ajax({
	    type: 'POST',
	    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/ListarMisMovimientos&cod='?>"+cod,
	    success: function(result){  

	    	$('#carpetas').empty();
	   		$('#carpetas').append(result); 

		             
	    }//fin success
	});//fin post tabla según gestor seleccionado

	return false;

	});	
</script>