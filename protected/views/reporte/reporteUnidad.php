<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Reporte Asignación de Carga</div><div class="subtitulo"></div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>    

     	<div>
  		<form action="#" id="formoid" method="post"	>	

		<div class="row-inline-medi">
			<label>Unidad:</label>
			<select name="unidad" id="unidad">
				<option value="">Seleccionar Unidad</option>
				<?php 
					foreach ($uni as $c => $value) {	   	
						if(isset($_POST['unidad']) && $_POST['unidad']==$uni[$c]->uni_codigo ){
							echo '<option value="'.$uni[$c]->uni_codigo .'" selected >'. $uni[$c]->uni_descripcion.'</option>';
						}								    	
						else{
							echo '<option value="'.$uni[$c]->uni_codigo .'">'. $uni[$c]->uni_descripcion.'</option>';
						}
						
					}//fin foreach
				?>
			</select>
		</div>

		<?php $fecha_inicio = date('Y-m')."-01"; ?>

		<div class="row-inline-rev">
				<label>Fecha Asignación Inicio<span class="required">*</span></label>
				<input type="date" id="fec" name="fec" value="<?php if(isset($_POST['fec'])) echo $_POST['fec']; else echo $fecha_inicio; ?>"></input>
		</div>

		<div class="row-inline-rev">
				<label>Fecha Asignación Fin<span class="required">*</span></label>
				<input type="date" id="fec_fin" name="fec_fin" value="<?php if(isset($_POST['fec_fin'])) echo $_POST['fec_fin']; else echo date('Y-m-d'); ?>"></input>
		</div>
		

		<div class="row-inline-rev">
			<input type="submit" id="submitButton"  name="submitButton" class="btn btn-info" value="Consultar Asignación">
		</div>

		<div id="error_msj" style="display: none"></div>

		</form>
		</div><!--fin div tvwork-->

<div style="overflow-x: auto;">
	<?php
	if( isset($_POST['unidad']) ){ 

		?>
		<table class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
			<tr>
				<th></th>
				<?php
				$uni=$_POST['unidad'];

				$fun = Funcionario::model()->getFuncionarioUnidad($uni);
				$funs='1';
				$array_min=$fun;
				$array_eje=$fun;
				$array_dil=$fun;
				$array_list=$fun;
				$i=0;
				foreach ($fun as $fun) {							
					echo "<th>".$fun['nombre']."</th>";
					if($i==0) $funs = " '".$fun['fun_rut']."' ";
					else $funs .= ",'".$fun['fun_rut']."' ";	
					$i++;
				}			
				?>

			</tr>
			<tr style="background-color: #d8f3d4;">
				<th>Minutos Asignados</th>
				<?php
				$fec_ini = $_POST['fec'];
				$fec_fin = $_POST['fec_fin'];
				foreach ($array_min as $array_min) {	
					$rut = $array_min['fun_rut'];						
					$cant = BancoTarea::model()->getTotalMinutosAsignados($rut, $fec_ini, $fec_fin);
					if(isset($cant[0]->total)) echo "<td>".number_format($cant[0]->total)."</td>"; 				
					else echo "<td></td>";		
				}
				
				?>
			</tr>

			<tr style="background-color: #d8f3d4;">
				<th>Minutos Ejecutados</th>
				<?php
				$fec_ini = $_POST['fec'];
				$fec_fin = $_POST['fec_fin'];
				foreach ($array_eje as $array_eje) {	
					$rut = $array_eje['fun_rut'];						
					$cant = BancoTarea::model()->getTotalMinutosEjecutados($rut, $fec_ini, $fec_fin);
					if(isset($cant[0]->total)) echo "<td>".number_format($cant[0]->total)."</td>"; 				
					else echo "<td></td>";		
				}
				
				?>
			</tr>


			<tr style="background-color: #d8f3d4;">
				<th>Tareas Asignadas</th>
				<?php
				foreach ($array_dil as $array_dil) {	
					$rut = $array_dil['fun_rut'];						
					$cant = BancoTarea::model()->getTotalTareasAsignadas($rut, $fec_ini, $fec_fin);
					if(isset($cant[0]->total) && $cant[0]->total>0) echo "<td>".$cant[0]->total."</td>"; 
					else echo "<td></td>";			
						
				}
				?>
			</tr>
			<tr style="background-color: #d4f3ed;">
				<th>Turno Presencial</th>
				<?php
				foreach ($array_list as $array_list) {	
					$rut = $array_list['fun_rut'];						
					$cant = BancoTarea::model()->getTotalTurnoPresencial($rut, $fec_ini, $fec_fin);
					if(isset($cant[0]->total) && $cant[0]->total>0) echo "<td>".$cant[0]->total."</td>"; 
					else echo "<td></td>";			
						
				}
				?>
			</tr>

		</table>
		<br>
		<?php
	}		
	?>
</div>

</div>

<script type="text/javascript">
	$(".chosen_fun").chosen();
</script>
