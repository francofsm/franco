<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Consulta Carga Laboral Ejecutada</div><div class="subtitulo"></div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>    

     	<div>
  		<form action="#" id="formoid" method="post"	>	

  		<?php
  			if(Yii::app()->user->getState('perfil')==13 ){
  				?>
	  			<div class="row-inline-medi">
					<label>FUNCIONARIO:</label>
					<select name="fun" id="fun" class="chosen_fun" >
						<option value="">Seleccionar Funcionario</option>
						<?php 
							foreach ($fun as $c => $value) {	   									    	
								if(isset($_POST['fun']) && $_POST['fun']==$fun[$c]->fun_rut ){
									echo '<option value="'.$fun[$c]->fun_rut .'" selected>'. $fun[$c]->fun_ap_paterno.' '.$fun[$c]->fun_nombre.' '.$fun[$c]->fun_nombre2 .'</option>';
								}								    	
								else{
									echo '<option value="'.$fun[$c]->fun_rut .'">'. $fun[$c]->fun_ap_paterno.' '.$fun[$c]->fun_nombre.' '.$fun[$c]->fun_nombre2 .'</option>';
								}

							}
						?>
					</select>
				</div>
				<?php
  			}
  			else{
				?>
	  			<div class="row-inline-medi">
					<label>FUNCIONARIO:</label>
					<select name="fun" id="fun" >
						<?php 
							foreach ($fun as $c => $value) {	   									    	
								echo '<option value="'.$fun[$c]->fun_rut .'">'. $fun[$c]->fun_ap_paterno.' '.$fun[$c]->fun_nombre.' '.$fun[$c]->fun_nombre2 .'</option>';
							}
						?>
					</select>
				</div>
				<?php
  			}
  		?>

		<?php $fecha_inicio = date('Y-m')."-01"; ?>

		<div class="row-inline-rev">
				<label>Fecha Inicio<span class="required">*</span></label>
				<input type="date" id="fec" name="fec" value="<?php if(isset($_POST['fec'])) echo $_POST['fec']; else echo $fecha_inicio; ?>"></input>
		</div>

		<div class="row-inline-rev">
				<label>Fecha Fin<span class="required">*</span></label>
				<input type="date" id="fec_fin" name="fec_fin" value="<?php if(isset($_POST['fec_fin'])) echo $_POST['fec_fin']; else echo date('Y-m-d'); ?>"></input>
		</div>
		

		<div class="row-inline-rev">
			<input type="submit" id="submitButton"  name="submitButton" class="btn btn-info" value="Consultar Asignación">
		</div>

		<div id="error_msj" style="display: none"></div>

		</form>
		</div><!--fin div tvwork-->

<div>
	<?php
	if( isset($_POST['fun']) ){ 
		$rut=$_POST['fun'];
		?>
		<table class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
			<tr>
				<th>Tareas</th>
				<?php
				$comienzo = new DateTime($_POST['fec']);
				$final = new DateTime($_POST['fec_fin']);

				// Necesitamos modificar la fecha final en 1 día para que aparezca en el bucle
				$final = $final->modify('+1 day');

				$intervalo = DateInterval::createFromDateString('1 day');
				$periodo = new DatePeriod($comienzo, $intervalo, $final);

				foreach ($periodo as $dt) {
				    echo "<th>".$dt->format('d-m')."</th>";
				}
				?>
				<th>TOTAL</th>

			</tr>
			
			<tr style="background-color: #ececec;">
				<th>Minutos Asignados</th>
				<?php
				$valors=0;
				foreach ($periodo as $dt) {
				    //echo "<th>".$dt->format('d-m')."</th>";
				    $fecha=$dt->format('Y-m-d');
					$sumas = BancoTarea::model()->getTotalMinutosAsignadosReporteIndividual($rut, $fecha);
					$valors = $valors + $sumas[0]->total;
					echo "<th>".$sumas[0]->total. "</th>";	
				}
				?>
				<th><?php echo $valors;	 ?></th>
			</tr>


			<tr>
				<th  style='background-color: #d3ffd2;'>Minutos Ejecutados</th>
				<?php
				$valor=0;
				foreach ($periodo as $dt) {
				    //echo "<th>".$dt->format('d-m')."</th>";
				    $fecha=$dt->format('Y-m-d');
					$sum = BancoTarea::model()->getTotalMinutosReporteIndividual($rut, $fecha);
					$valor = $valor + $sum[0]->total;
					echo "<th style='background-color: #d3ffd2;'>".$sum[0]->total. "</th>";	
				}
				?>
				<th  style='background-color: #d3ffd2;'><?php echo $valor;	 ?></th>
			</tr>

			<?php
				
				$funs = "'".$rut."'";
				$fec_ini = $_POST['fec'];
				$fec_fin = $_POST['fec_fin'];
				$total=0;		
				$ta = TgInstruccion::model()->getLisTareaReporteEjecutada($funs, $fec_ini, $fec_fin);
				foreach ($ta as $ta) {
					echo "<tr>";
					echo "<th>".$ta['gls_instruccion']." / ".$ta['tiempo_instruccion']." min.</th>";

					$codta=$ta['cod_instruccion'];

					$cant_dil=0;
					foreach ($periodo as $dt) {
					    //echo "<th>".$dt->format('d-m')."</th>";
					    $fecha=$dt->format('Y-m-d');
						$sum = BancoTarea::model()->getTotalDiligenciasReporteIndividual($rut, $fecha, $codta);
						$cant_dil = $cant_dil + $sum[0]->total;
						if($sum[0]->total > 0) echo "<td>".($sum[0]->total*$ta['tiempo_instruccion']). "</td>";	
						else echo "<td></td>";	
					}
					echo "<th>".$cant_dil*$ta['tiempo_instruccion']. "</th>";		
	

					echo "</tr>";
				}//fin foreach
			?>

		</table>

		<?php
	}		
	?>
</div>

</div>

<script type="text/javascript">
	$(".chosen_fun").chosen();
</script>
