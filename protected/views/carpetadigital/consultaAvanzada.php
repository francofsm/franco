<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/jquery-ui/jquery-ui.min.js"></script>
<script src='../start/js/validar_ruc.js' ></script>
<!-- <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/js/jquery-ui/jquery-ui.min.css"> -->
<!-- <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/js/jquery-ui/jquery-ui.theme.min.css"> -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/js/jquery-ui/themes/themes/blitzer/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/js/jquery-ui/themes/themes/blitzer/theme.css">

<div class="pagina">

	<div id="loading" style="display: none;">
		<img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
	</div>

		<!-- <div style="border-bottom: 1px solid #dddddd;padding: 8px;">
			<h4>Consulta de documentos digitales</h4>
		</div> -->
		<div class="div-titulo">		
			<div class="titulo">Consulta de documentos digitales</div><div class="subtitulo"></div>
			<div class="subtitulo"></div>
		</div>


		<div class="div-form-uno">
			<div class="row-inline-rev">
				<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
				<input type="text" id="ruc" name="ruc" autofocus value="<?php if(isset($ruc)) echo $ruc; ?>">
			</div>

			<div class="row-inline-rev">
				<label style="font-weight: bolder;font-size: 14px;">Clase Documental:</label> 
				<select class="form-control" name="claseDocumental" id="claseDocumental">
					<option value="">Seleccionar...</option>
					<?php foreach($claseDocumental as $value):?>
						<option value="<?=$value->cod_clasedoc?>"><?=$value->gls_clasedoc?></option>
					<?php endforeach;?>
				</select>
			</div>

			<div class="row-inline-rev">
				<label style="font-weight: bolder;font-size: 14px;">Nombre del documento:</label> 
				<input type="text" id="nombreDocumento" name="nombreDocumento" autofocus value="<?php if(isset($nombreDocumento)) echo $nombreDocumento; ?>" class="form-control"/>
			</div>

			<div class="row-inline-rev">
				<label style="font-weight: bolder;font-size: 14px;">Favorito:</label> 
				<input type="checkbox" name="favorito" id="favorito" autofocus value="1" class="form-control">
			</div>

			<div class="row-inline-rev">
				<label style="font-weight: bolder;font-size: 14px;">Alias:</label> 
				<input type="text" id="alias" name="alias" autofocus value="<?php if(isset($alias)) echo $alias; ?>" class="form-control"/>
			</div>
		</div>
		Fecha de Registro
		<div class="div-form-uno">
			<div class="row-inline-rev">
				<label style="font-weight: bolder;font-size: 14px;">Desde <i class="fa fa-calendar fa-lg" id="fDesdeTrigger" style="cursor:pointer;" title="Seleccionar Fecha"></i></label> 
				<input readonly type="text" id="fDesde" name="fDesde" autofocus value="<?php if(isset($fDesde)) echo $fDesde; ?>" class="form-control"/>
			</div>

			<div class="row-inline-rev">
				<label style="font-weight: bolder;font-size: 14px;">Hasta <i class="fa fa-calendar fa-lg" id="fHastaTrigger" style="cursor:pointer;" title="Seleccionar Fecha"></i></label> 
				<input readonly type="text" id="fHasta" name="fHasta" autofocus value="<?php if(isset($fHasta)) echo $fHasta; ?>" class="form-control"/>
			</div>

		</div>

		Fecha de Actividad
		<div class="div-form-uno">
			<div class="row-inline-rev">
				<label style="font-weight: bolder;font-size: 14px;">Desde <i class="fa fa-calendar fa-lg" id="fDesdeActTrigger" style="cursor:pointer;" title="Seleccionar Fecha"></i></label> 
				<input readonly type="text" id="fDesdeAct" name="fDesdeAct" autofocus value="<?php if(isset($fDesdeAct)) echo $fDesde; ?>" class="form-control"/>
			</div>

			<div class="row-inline-rev">
				<label style="font-weight: bolder;font-size: 14px;">Hasta <i class="fa fa-calendar fa-lg" id="fHastaActTrigger" style="cursor:pointer;" title="Seleccionar Fecha"></i></label> 
				<input readonly type="text" id="fHastaAct" name="fHastaAct" autofocus value="<?php if(isset($fHastaAct)) echo $fHasta; ?>" class="form-control"/>
			</div>
			<br>
			<button id="btn_control" name="asignar" class="btn btn-primary" onclick="consultaAvanzada()">Consultar</button>
			<a href="<?php echo Yii::app()->getBaseUrl(true)?>/index.php?r=CarpetaDigital/ConsultaAvanzada" class="btn btn-danger" >Limpiar</a>
		</div>

		<?php if(1==2):?>

			<hr>	
			<div class="row">
				<div class="form-group">
					<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
					<input type="text" id="ruc" name="ruc" autofocus value="<?php if(isset($ruc)) echo $ruc; ?>">
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<label style="font-weight: bolder;font-size: 14px;">Clase Documental:</label> 
					<select class="form-control" name="claseDocumental" id="claseDocumental">
						<option value="">Seleccionar...</option>
						<?php foreach($claseDocumental as $value):?>
							<option value="<?=$value->cod_clasedoc?>"><?=$value->gls_clasedoc?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<label style="font-weight: bolder;font-size: 14px;">Nombre del documento:</label> 
					<input type="text" id="nombreDocumento" name="nombreDocumento" autofocus value="<?php if(isset($nombreDocumento)) echo $nombreDocumento; ?>" class="form-control"/>
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<label style="font-weight: bolder;font-size: 14px;">Favorito:</label> 
					<input type="checkbox" name="favorito" id="favorito" autofocus value="1" class="form-control">
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<label style="font-weight: bolder;font-size: 14px;">Alias:</label> 
					<input type="text" id="alias" name="alias" autofocus value="<?php if(isset($alias)) echo $alias; ?>" class="form-control"/>
				</div>
			</div>

			<!-- <div class="row">
				<div class="form-group">
					<label style="font-weight: bolder;font-size: 14px;">Fecha de Registro</label> 
					<input type="text" id="alias" name="alias" autofocus value="<?php if(isset($alias)) echo $alias; ?>" class="form-control"/>
				</div>
			</div> -->

			<div class="row">
				<div class="col-md-12">
					<div >
						<h4>Fecha de Registro</h4>
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<label style="font-weight: bolder;font-size: 14px;">Desde <i class="fa fa-calendar fa-lg" id="fDesdeTrigger" style="cursor:pointer;" title="Seleccionar Fecha"></i></label> 
						<input readonly type="text" id="fDesde" name="fDesde" autofocus value="<?php if(isset($fDesde)) echo $fDesde; ?>" class="form-control"/>
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<label style="font-weight: bolder;font-size: 14px;">Hasta <i class="fa fa-calendar fa-lg" id="fHastaTrigger" style="cursor:pointer;" title="Seleccionar Fecha"></i></label> 
						<input readonly type="text" id="fHasta" name="fHasta" autofocus value="<?php if(isset($fHasta)) echo $fHasta; ?>" class="form-control"/>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div >
						<h4>Fecha Actividad</h4>
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<label style="font-weight: bolder;font-size: 14px;">Desde <i class="fa fa-calendar fa-lg" id="fDesdeActTrigger" style="cursor:pointer;" title="Seleccionar Fecha"></i></label> 
						<input readonly type="text" id="fDesdeAct" name="fDesdeAct" autofocus value="<?php if(isset($fDesdeAct)) echo $fDesde; ?>" class="form-control"/>
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<label style="font-weight: bolder;font-size: 14px;">Hasta <i class="fa fa-calendar fa-lg" id="fHastaActTrigger" style="cursor:pointer;" title="Seleccionar Fecha"></i></label> 
						<input readonly type="text" id="fHastaAct" name="fHastaAct" autofocus value="<?php if(isset($fHastaAct)) echo $fHasta; ?>" class="form-control"/>
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<button id="btn_control" name="asignar" class="btn btn-primary" onclick="consultaAvanzada()">Consultar</button>
				<a href="<?php echo Yii::app()->getBaseUrl(true)?>/index.php?r=CarpetaDigital/ConsultaAvanzada" class="btn btn-danger" >Limpiar</a>
			</div>
		<?php endif ?>

		<div class="col-md-12">
			<hr>
			<div id="documentos"></div>
		</div>

	</div>


	<script type="text/javascript">
		$(function(){
	// Fechas Actividad

	// Los trigger se activan cuando se hace click, en este caso sobre los iconos del calendario
	$("#fDesdeActTrigger").click(function(){
		$("#fDesdeAct").focus();
	});

	$( "#fDesdeAct").datepicker({
		dateFormat : 'dd-mm-yy',
		maxDate:"<?=date("d-m-Y")?>",//Fecha maxima a buscar por el calendario, es decir hasta la fecha actual
	});

	$("#fHastaActTrigger").click(function(){
		$("#fHastaAct").focus();
	});

	$( "#fHastaAct").datepicker({
		dateFormat : 'dd-mm-yy',
		maxDate:"<?=date("d-m-Y")?>",//Fecha maxima a buscar por el calendario, es decir hasta la fecha actual
	});

	// Fechas Registro

	$("#fDesdeTrigger").click(function(){
		$("#fDesde").focus();
	});

	$( "#fDesde").datepicker({
		dateFormat : 'dd-mm-yy',
		maxDate:"<?=date("d-m-Y")?>",//Fecha maxima a buscar por el calendario, es decir hasta la fecha actual
	});

	$("#fHastaTrigger").click(function(){
		$("#fHasta").focus();
	});
	$( "#fHasta").datepicker({
		dateFormat : 'dd-mm-yy',
	});
})

		function consultaAvanzada()
		{
		// Almacenamos en variables los datos de los campos a buscar.
		let ruc = $("#ruc").val();
		let claseDocumental = $("#claseDocumental").val();
		let nombreDocumento = $("#nombreDocumento").val();
		let favorito = $("#favorito").is(":checked");
		let alias = $("#alias").val();
		let fDesde = $("#fDesde").val();
		let fHasta = $("#fHasta").val();
		let fDesdeAct = $("#fDesdeAct").val();
		let fHastaAct = $("#fHastaAct").val();

		// Comprobaremos que se haya escogido al menos 1 opcion, excluyendo el indicador "Favorito"

		if(ruc == "" && claseDocumental == "" && alias == "" && fDesde == "" && fHasta == "" && fDesdeAct == "" && fHastaAct == "")
		{
			alert("Debe escoger al menos 1 opcion");
			return false;
		}

		let urlsecreta = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ConsultaAvanzada'?>";
		// Agregamos al objeto data todos las variables que se desean buscar, el modelo hara la validacion que buscar y que no según lo ingresado por el usuario
		let data = (
		{
			ruc:ruc,
			claseDocumental:claseDocumental,
			nombreDocumento:nombreDocumento,
			favorito:favorito,
			alias:alias,
			fDesde:fDesde,
			fHasta:fHasta,
			fDesdeAct:fDesdeAct,
			fHastaAct:fHastaAct,
			cmd:"buscar"});
		$.ajax({
			type:"POST",
			url:urlsecreta,
			data:data,
			beforeSend : function()
			{
				document.getElementById("loading").style.display = "block";
			},
			success : function ( response ) {
				$("#documentos").html(response);
			},
			complete : function(){
				document.getElementById("loading").style.display = "none";

			}
		});

	}
</script>