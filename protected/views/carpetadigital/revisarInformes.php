<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">


<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Listado de Informes pendientes de revisión</div><div class="subtitulo"></div>
		<div class="subtitulo">Listado de informes registrados en el sistema, pendientes de revisión del fiscal asignado.</div>
	</div>

	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     

    <div class="col-md-33">
		<div class="row-inline-medi">
			<label>Fiscal:</label>
			<select name="fiscal" id="fiscal" onchange="consultar(event);" >
				<option value="">Seleccionar fiscal</option>
				<?php 
				foreach ($fiscal as $c => $value) {	   									    	
					echo '<option value="'.$fiscal[$c]->rut_saf .'">'. $fiscal[$c]->cuenta_nombre.' '. $fiscal[$c]->cuenta_apellido.'</option>';
				}
				?>
			</select>
		</div>	
	</div>      


	<div id="tareas" style="padding: 10px;">
		
	</div>

</div>

<script type="text/javascript">
	$(document).ready(function() {

	fiscal=$("#fiscal").prop("value");
	document.getElementById("loading").style.display = "block" ;

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpetadigital/ListarInformes'?>";
	    $.ajax({
		    url: urltable,
		    type: 'POST',
		    data: {fiscal: fiscal},
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

	fiscal=$("#fiscal").prop("value");
	document.getElementById("loading").style.display = "block" ;

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpetadigital/ListarInformes'?>";
	    $.ajax({
		    url: urltable,
		    type: 'POST',
		    data: {fiscal: fiscal},
		    success: function(result){  
		                      
		        $('#tareas').empty();
	    		$('#tareas').append(result); 
		        document.getElementById("loading").style.display = "none"; 

		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;
 }
</script>


<script type="text/javascript">
	function verCarpeta(ruta){	
		document.getElementById("loading").style.display = "block";
	
		window.open(ruta , ruta , "width=1400,height=720,scrollbars=NO,status=yes, toolbar=no, top=200, left=200, directories=0, titlebar=0");
		document.getElementById("loading").style.display = "none";
	}
</script>

<script type="text/javascript">
	function revisarDoc(id){
		var r = confirm("Estas segur@ que deseas dar por revisado el informe?");
		if (r == true) {

			urlrev = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/RevisarDocumento'?>";
			$.ajax({
			    url: urlrev,
			    type: 'POST',
			    data: {id: id},
			    success: function(result){ 		

			    	consultar();

			    }//fin success

			});//fin post tabla según gestor seleccionad
			return false;

		}
	}
</script>