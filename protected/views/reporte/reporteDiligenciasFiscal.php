<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Reporte diligencias decretadas por fiscales en SIA 2020</div><div class="subtitulo"></div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>    

     	<div>
  		<form action="#" id="formoid" method="post"	>	

		<div class="row-inline-medi">
			<label>Fiscalía:</label>
			<select name="fisc" id="fisc">
				<option value="">Seleccionar Unidad</option>
				<?php 
					foreach ($fis as $c => $value) {	   	
						if(isset($_POST['fisc']) && $_POST['fisc']==$fis[$c]->fis_codigo ){
							echo '<option value="'.$fis[$c]->fis_codigo .'" selected >'. $fis[$c]->fis_nombre.'</option>';
						}								    	
						else{
							echo '<option value="'.$fis[$c]->fis_codigo .'">'. $fis[$c]->fis_nombre.'</option>';
						}
						
					}//fin foreach
				?>
			</select>
		</div>

		<?php $fecha_inicio = date('Y-m')."-01"; ?>

		<div class="row-inline-rev">
				<label>Fecha Decreta Inicio<span class="required">*</span></label>
				<input type="date" id="fec" name="fec" value="<?php if(isset($_POST['fec'])) echo $_POST['fec']; else echo $fecha_inicio; ?>"></input>
		</div>

		<div class="row-inline-rev">
				<label>Fecha Decreta Fin<span class="required">*</span></label>
				<input type="date" id="fec_fin" name="fec_fin" value="<?php if(isset($_POST['fec_fin'])) echo $_POST['fec_fin']; else echo date('Y-m-d'); ?>"></input>
		</div>
		

		<div class="row-inline-rev">
			<input type="submit" id="submitButton"  name="submitButton" class="btn btn-info" value="Consultar">
		</div>

		<div id="error_msj" style="display: none"></div>

		</form>
		</div><!--fin div tvwork-->

<div style="overflow-x: auto;">
	<?php
	if( isset($_POST['fisc']) ){ 

		?>
		<table class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
			<tr>
				<th>Cuenta Fiscal</th>
				<th>Dil. Pendiente de Asignar</th>
				<th>Dil. Asignado</th>
				<th>Dil. Ejecutadas</th>
				<th>Total Decretadas</th>
			</tr>

			<?php
				$fis=$_POST['fisc'];

				$fec_ini = $_POST['fec'];
				$fec_fin = $_POST['fec_fin'];

				$fun = Funcionario::model()->getFiscales($fis, $fec_ini, $fec_fin);
				$funs='1';
				$array_min=$fun;

				

				$i=0;
				foreach ($fun as $fun) {
					echo "<tr>";							
					echo "<td>".$fun['fun_ap_paterno'].", ".$fun['fun_nombre']." ".$fun['fun_nombre2']."</td>";
					if($i==0) $funs = " '".$fun['fun_rut']."' ";
					else $funs .= ",'".$fun['fun_rut']."' ";	

					$rut=$fun['fun_rut'];

					$pendi = BancoTarea::model()->getDiligenciasCuentaFiscalPendiente($rut, $fec_ini, $fec_fin, $fis);
					if(isset($pendi[0]->total)) echo "<td style='text-align: center;'>".number_format($pendi[0]->total)."</td>"; 				
					else echo "<td></td>";	

					$asig = BancoTarea::model()->getDiligenciasCuentaFiscalAsignado($rut, $fec_ini, $fec_fin, $fis);
					if(isset($asig[0]->total)) echo "<td style='text-align: center;'>".number_format($asig[0]->total)."</td>"; 				
					else echo "<td></td>";		

					$ejec = BancoTarea::model()->getDiligenciasCuentaFiscalEjecutado($rut, $fec_ini, $fec_fin, $fis);
					if(isset($ejec[0]->total)) echo "<td style='text-align: center;'>".number_format($ejec[0]->total)."</td>"; 				
					else echo "<td></td>";	

					$cant = BancoTarea::model()->getDiligenciasCuentaFiscal($rut, $fec_ini, $fec_fin, $fis);
					if(isset($cant[0]->total)) echo "<td style='text-align: center;'>".number_format($cant[0]->total)."</td>"; 				
					else echo "<td></td>";		


					echo "</tr>";
					$i++;
				}			
			?>


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
