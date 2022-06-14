<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Reporte Carga Laboral según Fecha Plazo de las Diligencias</div><div class="subtitulo"></div>
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


<!--MODAL-->

		<div id="openConsulta" class="modalDetalle">
			<div class="col-md-12">
				<a href="#close" title="Close" class="close">X</a>	
				<div class="col-md-6 servicio">
					<div class="titulo_form">Detalle minutos asignados</div>
					<div class="item" style="text-align: center;">
					    <div id="datos2">
    	
     					</div>
					    	
					</div>				
					
				</div>	
			</div>	
		</div>
	
<!--FIN-->


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
			<tr style="background-color: #eaeaea;">
				<th>Minutos Asignados</th>
				<?php
				$fec_ini = $_POST['fec'];
				$fec_fin = $_POST['fec_fin'];

				$disponible=array();

				$i=0;
				foreach ($array_min as $array_min) {	
					$rut = $array_min['fun_rut'];						
					$cant = BancoTarea::model()->getTotalMinutosAsignadosPlazo($rut, $fec_ini, $fec_fin);
					if(isset($cant[0]->total)){
						$disponible[$i]=$cant[0]->total;
						echo "<td style='text-align: center;' class='td_detalle' id='".$rut."' onclick='verDetalle(this.id)'>".number_format($cant[0]->total)."</td>"; 
					}
					else{
						$disponible[$i]=0;
						echo "<td></td>";	
					} 				
					$i++; 	
				}
				
				?>
			</tr>

			<tr style="background-color: #d8f3d4;">
				<th>Minutos Ejecutados</th>
				<?php
				$fec_ini = $_POST['fec'];
				$fec_fin = $_POST['fec_fin'];

				$ejecutado=array();
				$j=0;
				foreach ($array_eje as $array_eje) {	
					$rut = $array_eje['fun_rut'];						
					$cant = BancoTarea::model()->getTotalMinutosEjecutadosPlazo($rut, $fec_ini, $fec_fin);
					if(isset($cant[0]->total)){
						$ejecutado[$j]=$cant[0]->total;
						echo "<td style='text-align: center;'>".number_format($cant[0]->total)."</td>"; 	
					} 			
					else{
						$ejecutado[$j]=0;
						echo "<td></td>";		
					} 
					$j++;
				}
				
				?>
			</tr>


			<!--<tr style="background-color: #d8f3d4;">
				<th>Min. Disponibles</th>
				<?php
				$i=0;
				foreach ($disponible as $disponible) {	
					$dispo=$disponible-$ejecutado[$i];
					if($dispo > 0){
						echo "<td style='text-align: center;font-weight: 600;'>".number_format($dispo)."</td>"; 	
					}
					else{
						echo "<td></td>"; 	
					}
						
					$i++;		
				}
				
				?>
			</tr>-->


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


<script type="text/javascript">
	function verDetalle(id){

		miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/DetalleReportePlazoMinAsignados'?>";

		var fec = $("#fec").change({ dateFormat: "yyyy/mm/dd" }).val()
		var fec_fin = $("#fec_fin").change({ dateFormat: "yyyy/mm/dd" }).val()

		var rut=id;
		miurlfin = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/ReportePlazo#openConsulta'?>";		

		$.ajax({
		   	url: miurl,
		   	type: 'POST',
		   	data: {rut: rut, fec: fec, fec_fin: fec_fin},
		   	success: function(result){
		   		 $('#datos2').html(result);
		   		 window.location.href = miurlfin;   
		   	}//fin success
		});

		return false;
	}
</script>