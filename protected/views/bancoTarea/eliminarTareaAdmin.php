<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Eliminar tareas administrativas asignadas</div><div class="subtitulo">Se puede eliminar tareas administrativas decretadas a los funcionarios</div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>    

     	<div>
  		<form action="#" id="formoid" method="post"	>	

		<div class="row-inline-medi">
			<label>FUNCIONARIO:</label>
			<select name="fun" id="fun" class="chosen_fun" >
				<option value="">Seleccionar Funcionario</option>
				<?php 
					foreach ($fun as $c => $value) {	   									    	
						echo '<option value="'.$fun[$c]->fun_rut .'">'. $fun[$c]->fun_ap_paterno.' '.$fun[$c]->fun_nombre.' '.$fun[$c]->fun_nombre2 .'</option>';
					}
				?>
			</select>
		</div>

		<?php $fecha_inicio = date('Y-m')."-01"; ?>

		<div class="row-inline-rev">
				<label>Fecha Plazo Inicio<span class="required">*</span></label>
				<input type="date" id="fec" name="fec" value="<?php if(isset($_POST['fec'])) echo $_POST['fec']; else echo $fecha_inicio; ?>"></input>
		</div>

		<div class="row-inline-rev">
				<label>Fecha Plazo Fin<span class="required">*</span></label>
				<input type="date" id="fec_fin" name="fec_fin" value="<?php if(isset($_POST['fec_fin'])) echo $_POST['fec_fin']; else echo date('Y-m-d'); ?>"></input>
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
    	urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarTareasAdministrativas'?>";

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
	function elimTarea(id){	



		urlelim = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/EliminarTareaAdministrativa'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urlelim,
		      type: 'POST',
		      data: {id: id},
		      success: function(result){  

		      	var fun = $("#fun").val(); 
		      	var fec = $("#fec").change({ dateFormat: "yyyy/mm/dd" }).val()
		      	var fec_fin = $("#fec_fin").change({ dateFormat: "yyyy/mm/dd" }).val()

		        urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarTareasAdministrativas'?>";
				    $.ajax({
					    url: urltable,
					    type: 'POST',
					     data: {fun: fun, fec: fec, fec_fin: fec_fin},
					    success: function(result){  
					        document.getElementById("loading").style.display = "none";              
					        $('#tareas').empty();
				    		$('#tareas').append(result);   
				    		
					             
					    }//fin success
					});//fin post tabla según gestor seleccionado
				      
		      }//fin success
		});
		return false;

		
	}
</script>