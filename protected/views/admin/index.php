<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<div class="pagina">

<div class="div-titulo">	

	<div class="titulo">Bienvenidos <?php echo $fi->fis_nombre;?></div>
	

	<div class="subtitulo">	
		Perfil: <?php  if(isset($perfil->gls_perfil)) echo $perfil->gls_perfil; echo " (".Yii::app()->user->getState('perfil').")"; ?>
		/ Equipo <?php  if(isset($tguni->uni_codigo)) echo $tguni->uni_descripcion; ?>
		
		<?php  if(Yii::app()->user->getState('fun_fis') >= 2){
			?><span class='btn btn-warning' onclick='cambiarFiscalia()'>Cambiar de Fiscalía</span><?php
		}
		?>

	</div>
</div>
 <?php date_default_timezone_set('America/Santiago'); ?>

<?php echo date('Y-m-d'); ?>

<?=date('m/d/y g:ia');?>


<?php

if( Yii::app()->user->getState('perfil')==13 || 	 
	Yii::app()->user->getState('perfil')==3 ||
	Yii::app()->user->getState('perfil')==4 ||
	Yii::app()->user->getState('perfil')==6 ||
	Yii::app()->user->getState('perfil')==7 ||
	Yii::app()->user->getState('perfil')==8 ||
	Yii::app()->user->getState('perfil')==9 ||
	Yii::app()->user->getState('perfil')==11 ||
	Yii::app()->user->getState('perfil')==12 ||
	Yii::app()->user->getState('perfil')==14 ){
	?>

<div class="col-md-33">
	<h4 class="titulo_index">Partes</h4>
	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/RegistrarDenuncia'?>"> 
		<p>Recepcionar Oficina de Partes</p></a>
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/DerivarDenuncia'?>"> 
		<p>Derivar Partes para Ingreso a SAF</p></a>
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/DeclararIngreso'?>"> 
		<p>Declarar Ingreso del Parte en SAF</p></a>
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/RecepcionarParte'?>"> 
		<p>Recepcionar Partes </p></a>
	</div>


	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/ConsultarRecepcionados'?>">
		<p>Consultar Partes Recepcionados</p></a> 
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/ConsultarDerivado'?>">
		<p>Consultar Partes Derivados</p></a>
	</div>

	</div>



<!--
	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href=""><p>Consultar Partes Pendientes de Ingreso</p></a>
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href=""><p>Listado Partes con Control de Detención</p></a>
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href=""><p>Listado Partes Pendientes de Recepción</p></a>
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href=""><p>Listado Partes Derivados Pendientes de Ingreso</p></a>
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href=""><p>Listado de Sujetos a Control de Detención</p></a>
	</div>
-->



<?php
}
?>





<div class="col-md-33">
	<h4 class="titulo_index">Diligencias</h4>

	<?php
	if( Yii::app()->user->getState('perfil')==13
	|| Yii::app()->user->getState('perfil')==3
	|| Yii::app()->user->getState('perfil')==4
	|| Yii::app()->user->getState('perfil')==6
	|| Yii::app()->user->getState('perfil')==8
	|| Yii::app()->user->getState('perfil')==11
	|| Yii::app()->user->getState('perfil')==12 ){ 
	?>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/MisDiligenciasEjecutadas'?>">
		<p>Minutos Ejecutados</p></a>
	</div>

	<?php
	}
	?>


	<?php
	if( Yii::app()->user->getState('perfil')==13
	|| Yii::app()->user->getState('perfil')==1 
	|| Yii::app()->user->getState('perfil')==2 
	|| Yii::app()->user->getState('perfil')==3 
	|| Yii::app()->user->getState('perfil')==4 
	|| Yii::app()->user->getState('perfil')==11 
	|| Yii::app()->user->getState('perfil')==12
	|| Yii::app()->user->getState('perfil')==16 ){ 
	?>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/RegistrarTarea'?>">
		<p><strong>Decretar</strong> Diligencias/Tareas</p></a>
	</div>

	<?php
	}
	?>


	<?php
	if( Yii::app()->user->getState('perfil')==13
	|| Yii::app()->user->getState('perfil')==1 
	|| Yii::app()->user->getState('perfil')==2 
	|| Yii::app()->user->getState('perfil')==3 
	|| Yii::app()->user->getState('perfil')==4 
	|| Yii::app()->user->getState('perfil')==11 
	|| Yii::app()->user->getState('perfil')==12
	|| Yii::app()->user->getState('perfil')==16 ){ 
	?>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/RegistrarTareaAsignar'?>">
		<p><strong>Decretar</strong> y Asignar Diligencias/Tareas</p></a>
	</div>

	<?php
	}
	?>


	<?php
	if( Yii::app()->user->getState('perfil')==13
	|| Yii::app()->user->getState('perfil')==4
	|| Yii::app()->user->getState('perfil')==7
	|| Yii::app()->user->getState('perfil')==9
	|| Yii::app()->user->getState('perfil')==10
	|| Yii::app()->user->getState('perfil')==12
	|| Yii::app()->user->getState('perfil')==15
	|| Yii::app()->user->getState('perfil')==16
	|| Yii::app()->user->getState('perfil')==17 ){ 
	?>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/RegistrarTareaAdmin'?>">
		<p><strong>Decretar</strong> Tareas Administrativas (Bloques de Tiempo)</p></a>
	</div>

	<?php
	}
	?>

<!--
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////   ASIGNAR DILIGENCIAS DE LA BOLSA DE TRABAJO  /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
-->

	<?php
	if( Yii::app()->user->getState('perfil')==13
	|| Yii::app()->user->getState('perfil')==2
	|| Yii::app()->user->getState('perfil')==3
	|| Yii::app()->user->getState('perfil')==4
	|| Yii::app()->user->getState('perfil')==5
	|| Yii::app()->user->getState('perfil')==6
	|| Yii::app()->user->getState('perfil')==7
	|| Yii::app()->user->getState('perfil')==14
	|| Yii::app()->user->getState('perfil')==15
	|| Yii::app()->user->getState('perfil')==17 ){ 
	?>

	<div style="display: flex;margin-top: 8px;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/AsignarBancoTarea'?>">
		<p><strong>Asignar</strong> Diligencias de la bolsa de trabajo</p></a>
	</div>

	<?php
	}
	?>


	<?php
	if( Yii::app()->user->getState('perfil')==13
	|| Yii::app()->user->getState('perfil')==2
	|| Yii::app()->user->getState('perfil')==3
	|| Yii::app()->user->getState('perfil')==4
	|| Yii::app()->user->getState('perfil')==5
	|| Yii::app()->user->getState('perfil')==6
	|| Yii::app()->user->getState('perfil')==7
	|| Yii::app()->user->getState('perfil')==14
	|| Yii::app()->user->getState('perfil')==15
	|| Yii::app()->user->getState('perfil')==17 ){ 
	?>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/AsignarTareaCAso'?>">
		<p><strong>Asignar</strong> Diligencias según Caso</p></a>
	</div>

		
	<?php
	}
	?>


<?php
	if( Yii::app()->user->getState('perfil')==13
	|| Yii::app()->user->getState('perfil')==3
	|| Yii::app()->user->getState('perfil')==4
	|| Yii::app()->user->getState('perfil')==6
	|| Yii::app()->user->getState('perfil')==7
	|| Yii::app()->user->getState('perfil')==8
	|| Yii::app()->user->getState('perfil')==9
	|| Yii::app()->user->getState('perfil')==11
	|| Yii::app()->user->getState('perfil')==12
	|| Yii::app()->user->getState('perfil')==14
	|| Yii::app()->user->getState('perfil')==15 ){
	?>

	<div style="display: flex;margin-top: 8px;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/MisBloques'?>">
		<p><strong>Ejecutar</strong> Bloques de Tiempo</p></a>
	</div>

	<?php
	}
	?>



	<?php
	if( Yii::app()->user->getState('perfil')==13
	|| Yii::app()->user->getState('perfil')==3
	|| Yii::app()->user->getState('perfil')==4
	|| Yii::app()->user->getState('perfil')==6
	|| Yii::app()->user->getState('perfil')==7
	|| Yii::app()->user->getState('perfil')==8
	|| Yii::app()->user->getState('perfil')==9
	|| Yii::app()->user->getState('perfil')==11
	|| Yii::app()->user->getState('perfil')==12
	|| Yii::app()->user->getState('perfil')==14
	|| Yii::app()->user->getState('perfil')==15 ){
	?>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/MisDiligencias'?>">
		<p><strong>Ejecutar</strong> Diligencias Asignadas</p></a>
	</div>

	<?php
	}
	?>



	<?php
	if( Yii::app()->user->getState('perfil')==13
	|| Yii::app()->user->getState('perfil')==3
	|| Yii::app()->user->getState('perfil')==4
	|| Yii::app()->user->getState('perfil')==6
	|| Yii::app()->user->getState('perfil')==7
	|| Yii::app()->user->getState('perfil')==8
	|| Yii::app()->user->getState('perfil')==9
	|| Yii::app()->user->getState('perfil')==11
	|| Yii::app()->user->getState('perfil')==12
	|| Yii::app()->user->getState('perfil')==14
	|| Yii::app()->user->getState('perfil')==15 ){
	?>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/EjecutarTareaCAso'?>">
		<p><strong>Ejecutar</strong> Diligencias Pendientes según Caso</p></a>
	</div>
	
	<?php
	}
	?>


    
	<?php
	if( Yii::app()->user->getState('perfil')==13
	|| Yii::app()->user->getState('perfil')==3
	|| Yii::app()->user->getState('perfil')==4
	|| Yii::app()->user->getState('perfil')==6
	|| Yii::app()->user->getState('perfil')==7
	|| Yii::app()->user->getState('perfil')==8
	|| Yii::app()->user->getState('perfil')==9
	|| Yii::app()->user->getState('perfil')==11
	|| Yii::app()->user->getState('perfil')==12
	|| Yii::app()->user->getState('perfil')==14
	|| Yii::app()->user->getState('perfil')==15 ){
	?>



	<div style="display: flex;margin-top: 8px;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ConsultaCaso'?>">
		<p><strong>Consultar</strong> Diligencias según Caso</p></a>
	</div>
	

	<?php
	}
	?>



<!--
	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ConsultaCaso'?>">
		<p><strong>Consultar</strong>  Documentos SAF ingresados por Fiscal</p></a>
	</div>
	

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ConsultaCaso'?>">
		<p><strong>Consultar</strong>  Documentos SAF ingresados por Caso</p></a>
	</div>



	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ConsultaCaso'?>">
		<p><strong>Consultar</strong>  Especies Ingresadas</p></a>
	</div>
-->

	<?php
	if( Yii::app()->user->getState('perfil')==13
    || Yii::app()->user->getState('perfil')==1
    || Yii::app()->user->getState('perfil')==2
    || Yii::app()->user->getState('perfil')==3
    || Yii::app()->user->getState('perfil')==4
    || Yii::app()->user->getState('perfil')==11
    || Yii::app()->user->getState('perfil')==12
    || Yii::app()->user->getState('perfil')==16  ){
	?>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/DiligenciasDecretadas'?>">
		<p>Listado de Diligencias Decretadas</p></a>
	</div>
	
	<?php
	}
	?>


	<?php
	if( Yii::app()->user->getState('perfil')<>19 ){
	?>


	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/DiligenciasAsignadas'?>">
		<p>Listado de Diligencias Asignadas Pendientes de Ejecución</p></a>
	</div>


	<?php
	}
	?>



	<?php
	if( Yii::app()->user->getState('perfil')==13
	|| Yii::app()->user->getState('perfil')==4){
	?>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/DiligenciasTodas'?>">
		<p>Listado de TODAS diligencias DECRETADAS </p></a>
	</div>
	
	<?php
	}
	?>


<!--
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////   REPORTE DILIGENCIAS ANULADAS                /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
-->

	<?php
	if( Yii::app()->user->getState('perfil')==13
	|| Yii::app()->user->getState('perfil')==2
	|| Yii::app()->user->getState('perfil')==3
	|| Yii::app()->user->getState('perfil')==4
	|| Yii::app()->user->getState('perfil')==5
	|| Yii::app()->user->getState('perfil')==6
	|| Yii::app()->user->getState('perfil')==7
	|| Yii::app()->user->getState('perfil')==14
	|| Yii::app()->user->getState('perfil')==15
	|| Yii::app()->user->getState('perfil')==17 ){ 
	?>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/DiligenciasAnuladas'?>">
		<p><strong>Listado </strong>diligencias anuladas </p></a>
	</div>

	<?php
	}
	?>

	
	<?php
	if( Yii::app()->user->getState('perfil')<>19 ){
	?>

	<div style="display: flex;margin-top: 8px;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/EliminarTareaCAso'?>">
		<p><strong>Eliminar</strong> Diligencias según Caso</p></a>
	</div>
	
	<?php
	}
	?>


	<?php
	if( Yii::app()->user->getState('perfil')==13
	|| Yii::app()->user->getState('perfil')==4 ){ 
	?>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/EliminarTareaAdmin'?>">
		<p><strong>Eliminar</strong> bloques de tiempo</p></a> 
	</div>
	<?php
	}
	?>



	
</div>


	
	<?php
	if( Yii::app()->user->getState('perfil')<>19 ){
	?>


<div class="col-md-33">
	<h4 class="titulo_index">Carpetas</h4>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/ConsultarUbicacion'?>">
		<p><strong>Consultar </strong>Ubicación de una Carpeta</p></a>
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/RecepcionarCarpeta'?>">
		<p><strong>Recepcionar</strong> Carpetas</p></a>
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/MoverCarpeta'?>">
		<p><strong>Mover</strong> Carpetas a Funcionario o Ubicación Física</p></a>
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png"> 
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/MisCarpetas'?>">
		<p>Listado Carpetas en mi Oficina</p></a>
	</div>

	<div style="display: flex;"> 
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/PendienteRecepcion'?>">
		<p>Listado Carpetas Pendientes de Recepcionar</p></a>
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/MisMovimientos'?>">
		<p>Listado Mis Movimientos de Carpeta</p></a> 
	</div>


</div>

	<?php
	}
	?>



<div class="col-md-33">
	<h4 class="titulo_index">Reportes</h4>


	<?php
	if( Yii::app()->user->getState('perfil')==6
		// Yii::app()->user->getState('perfil')==3 
		// Yii::app()->user->getState('perfil')==4 
		// Yii::app()->user->getState('perfil')==5 
		|| Yii::app()->user->getState('perfil')==6
		|| Yii::app()->user->getState('perfil')==7
		|| Yii::app()->user->getState('perfil')==8
		|| Yii::app()->user->getState('perfil')==14
		|| Yii::app()->user->getState('perfil')==13
		|| Yii::app()->user->getState('perfil')==15 ){
	?>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/ReporteIndividual'?>">
		<p>Reporte Individual Carga Ejecutada</p></a> 
	</div>

	
	<?php
	}

	if( Yii::app()->user->getState('perfil')==2
		|| Yii::app()->user->getState('perfil')==3 
		|| Yii::app()->user->getState('perfil')==4 
		|| Yii::app()->user->getState('perfil')==5 
		|| Yii::app()->user->getState('perfil')==6
		|| Yii::app()->user->getState('perfil')==7
		|| Yii::app()->user->getState('perfil')==9
		|| Yii::app()->user->getState('perfil')==10
		|| Yii::app()->user->getState('perfil')==11
		|| Yii::app()->user->getState('perfil')==12
		|| Yii::app()->user->getState('perfil')==13
		|| Yii::app()->user->getState('perfil')==14
		|| Yii::app()->user->getState('perfil')==15
		|| Yii::app()->user->getState('perfil')==16
		|| Yii::app()->user->getState('perfil')==17 ){
	?>

  <!--
	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/ReporteUnidad'?>">
		<p>Reporte Asignación de Carga</p></a> 
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/ReporteUnidadDiligencias'?>">
		<p>Reporte Asignación de Carga (Lista Diligencias)</p></a> 
	</div>

    -->



	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/ReportePlazo'?>">
		<p>Reporte Carga Laboral según Fecha de Plazo</p></a> 
	</div>


	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/ReporteUnidadDiligencias'?>">
		<p>Reporte Carga Laboral según Fecha de Plazo (Lista Diligencias)</p></a> 
	</div>


	<?php
	}
	?>

</div>



	







<!--en desarrollo-->









	<?php
	if( Yii::app()->user->getState('perfil')==13 ){
	?>


<div class="col-md-33">
	<h4 class="titulo_index">Reportes Administrador UGI</h4>


	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/ReporteIndividual'?>">
		<p>Reporte Individual Carga Ejecutada</p></a> 
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/ReporteUnidad'?>">
		<p>Reporte Asignación de Carga</p></a> 
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/ReporteUnidadDiligencias'?>">
		<p>Reporte Carga Laboral según Fecha de Plazo (Lista Diligencias)</p></a> 
	</div>

	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/ReportePlazo'?>">
		<p>Reporte Carga Laboral según Fecha de Plazo</p></a> 
	</div>



	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/ReporteDiligenciasFiscal'?>">
		<p>Reporte Diligencias decretadas por Fiscales en SIA 2020</p></a> 
	</div>


	<div style="display: flex;">
		<img style="height: 100%;" src="<?php echo Yii::app()->baseUrl; ?>/images/vineta.png">
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/CarpetaTerminada'?>">
		<p>Envío a bodega CUCT..... en desarrollo</p></a> 
	</div>



</div>

	
	<?php
	}
	?>

<!--MODAL-->

		<div id="openConsulta" class="modalDialog">
			<div class="col-md-12 servicio">
				<a href="#close" title="Close" class="close">X</a>					
					<div class="titulo_form">Cambiar de Fiscalía</div>
					<div class="item" style="text-align: center;">
					    <div id="datos2">
    	
     					</div>
					    	
					</div>
			</div>	
		</div>
	
<!--FIN-->

<script type="text/javascript">
	function cambiarFiscalia(){

		miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Admin/ModificarFiscaliaAsociada'?>";
		
		miurlfin = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Admin/index#openConsulta'?>";		

		$.ajax({
		   	url: miurl,
		   	type: 'POST',
		   	success: function(result){
		   		 $('#datos2').html(result);
		   		 window.location.href = miurlfin;   
		   	}//fin success
		});

		return false;
	}
</script>


<script type="text/javascript">
	function guardarFiscalia(){

		var fis = $("#fisca").val(); 

		miurlfin = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Admin/index'?>";	

		urlmod = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Admin/GuardarFiscaliaFunAsociado'?>";
	    $.ajax({
		    url: urlmod,
		    type: 'POST',
		    data: {fis: fis},
		    success: function(data){  
		        

		       window.location.href = miurlfin;   

		             
		    }//fin success
		});//fin post tabla según gestor seleccionado	


	}

</script>


</div>
