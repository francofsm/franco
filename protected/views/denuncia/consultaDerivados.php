<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Partes derivados para Ingresar a SAF</div><div class="subtitulo">Permite anular las derivaciones.</div>
	</div>


	<div class="derivarparte">	

<?php $fecha_inicio = date('Y-m')."-01"; ?>

	<div class="row-inline-rev" style="margin-left: 100px;padding: 20px;">
		<label style="font-size: 18px;font-weight: 600;">Fecha Ingreso Inicio <span class="required">*</span></label>
		<input style="font-size: 19px;padding: 4px;" type="date" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" onchange="consultar(event);"></input>
	</div>		



	<div class="row-inline-rev" style="margin-left: 100px;padding: 20px;">
		<label style="font-size: 18px;font-weight: 600;">Fecha Ingreso Fin <span class="required">*</span></label>
		<input style="font-size: 19px;padding: 4px;" type="date" id="fin" name="fin" value="<?php echo date('Y-m-d'); ?>" onchange="consultar(event);"></input>
	</div>		

	 	

		<div id="loading" style="display: none;">
			<img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading2.gif" alt="Loading..." />
		</div> 

	</div>


	<div id="partes" style="padding: 10px;overflow-x: auto;">
		
			
	</div>


</div>


<script type="text/javascript">
	$(document).ready(function() {

	document.getElementById("loading").style.display = "block"; 

	var fec = $("#fecha").change({ dateFormat: "yyyy/mm/dd" }).val();
	var fec_fin = $("#fin").change({ dateFormat: "yyyy/mm/dd" }).val()

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/ListarParteDerivado&fec='?>"+fec+'&fec_fin='+fec_fin,

	    $.ajax({
		    url: urltable,
		    type: 'POST',
		    success: function(result){  
		        $('#partes').empty();
	    		$('#partes').append(result);  
	    		document.getElementById("loading").style.display = "none";  
		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;


	});	
</script>

<script type="text/javascript">
	function anularParte(id){

		var r = confirm("Estas segur@ que deseas anular la derivación?");
		if (r == true) {
		 
		 document.getElementById("loading").style.display = "block"; 
		 
		  urlanular = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/AnularDerivacion&id='?>"+id,

		  $.ajax({
		    url: urlanular,
		    type: 'POST',
		    success: function(result){  
		        
	

  			var fec = $("#fecha").change({ dateFormat: "yyyy/mm/dd" }).val()
  			var fec_fin = $("#fin").change({ dateFormat: "yyyy/mm/dd" }).val()


		    $.ajax({
			    type: 'POST',
			    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/ListarParteDerivado&fec='?>"+fec+'&fec_fin='+fec_fin,
			    success: function(result){  

			    	$('#partes').empty();
		    		$('#partes').append(result);
		    		document.getElementById("loading").style.display = "none";  

			             
			    }//fin success
			});//fin post tabla según gestor seleccionado

	
		    }//fin success
		  });//fin post tabla 

		return false;

		} //fin if
	}
</script>


<script type="text/javascript">
	function consultar(e){

	document.getElementById("loading").style.display = "block"; 

  	var fec = $("#fecha").change({ dateFormat: "yyyy/mm/dd" }).val()
  	var fec_fin = $("#fin").change({ dateFormat: "yyyy/mm/dd" }).val()


	    $.ajax({
		    type: 'POST',
		    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/ListarParteDerivado&fec='?>"+fec+'&fec_fin='+fec_fin,
		    success: function(result){  

		    	$('#partes').empty();
	    		$('#partes').append(result);
	    		document.getElementById("loading").style.display = "none";  

		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;

}
</script>