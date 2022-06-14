<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Causas Asignadas SIN REVISIÓN</div><div class="subtitulo">Desde RUC, es posible acceder a Ficha caso. Permite diferenciar por causas Judicializadas/No Judicializadas, Con Actividad/Sin Actividad</div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>    

     	<div>
  		<form action="#" id="formoid" method="post"	>	

		<div class="row-inline-medi">
			<label>SELECCIONE FISCAL:</label>
			<select name="fun" id="fun" class="chosen_fun" >
				<option value="">Seleccionar Fiscal</option>
				<?php 
					foreach ($fun as $c => $value) {	   									    	
						echo '<option value="'.$fun[$c]->rut_saf.'">'. $fun[$c]->cuenta_nombre.' '.$fun[$c]->cuenta_apellido .'</option>';
					}
				?>
			</select>
		</div>

		<div class="row-inline-rev">
			<input type="submit" id="submitButton"  name="submitButton" class="btn btn-info" value="Consultar">
		</div>

		<div id="error_msj" style="display: none"></div>

		</form>
		</div><!--fin div tvwork-->

		<div>
			<div id="tareas" style="padding: 10px;">
		
			
			</div>
		</div>

</div>

<script type="text/javascript">
	$(".chosen_fun").chosen();
</script>

<script type="text/javascript">
 $("#formoid").submit(function(event) {
    	

		var datastring = $("#formoid").serialize(); 
    	document.getElementById("loading").style.display = "block";
    	urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ListarCausasAsignadasFiscal'?>";

		$.ajax({
	        url: urlsave,
	        type: 'POST',
	        data: datastring,
	        success: function(result){  
	                
 				$('#tareas').empty();
				$('#tareas').append(result); 
				document.getElementById("loading").style.display = "none";
	           
	        }//fin success
	    });
	    return false;
	    
	

	});
</script>


<script type="text/javascript">
	function RevcausaFiscal(id){
		var r = confirm("Estas segur@ que deseas dar por revisada la causa?");
		if (r == true) {

			urlrev = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/RevisarcausaFiscal'?>";
			$.ajax({
			    url: urlrev,
			    type: 'POST',
			    data: {id: id},
			    success: function(result){ 		

				var datastring = $("#formoid").serialize(); 
			    document.getElementById("loading").style.display = "block";
			    urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ListarCausasAsignadasFiscal'?>";

					$.ajax({
				        url: urlsave,
				        type: 'POST',
				        data: datastring,
				        success: function(result){  
				                
			 				$('#tareas').empty();
							$('#tareas').append(result); 
							document.getElementById("loading").style.display = "none";
				           
				        }//fin success
				    });
				    //return false;
			    //	consultar();

			    }//fin success

			});//fin post tabla según gestor seleccionad
			return false;

		}
	}
</script>