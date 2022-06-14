<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.Rut.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/carga_select.js"></script>

<script type="text/javascript">
$(document).ready(function() {
  $("input[type=text]").focus().select();
});
</script>

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Mantenedor de perfiles</div><div class="subtitulo">Agregar o eliminar perfil a funcionarios.</div>
	</div>

	<div class="div-form-uno">

			<form id="foconsulta" class="form-horizontal"  method="post" name="foconsulta">
			<center>			

			<div class="row-inline-normal">
				<label>Funcionario <span class="required">*</span></label>
				<select id="funcionario" name="funcionario" class="input-md" required="required">
					<option value="">Seleccionar Gestor</option>	
				<?php 
				foreach ($fun as $fun) { ?>
					<option value="<?php echo $fun->fun_rut; ?>"><?php echo $fun->nombre; ?></option><?php
				} 
				?>
				</select>
			</div>

			<div class="row-inline-normal">
				<label>Perfil <span class="required">*</span></label>
				<select id="perfil" name="perfil" class="input-md" required="required">
					<option value="">Seleccionar Perfil</option>	
				<?php 
				foreach ($perfil as $perfil) { ?>
					<option value="<?php echo $perfil->cod_perfil; ?>"><?php echo $perfil->gls_perfil." (".$perfil->cod_perfil.")"; ?></option><?php
				} 
				?>
				</select>
			</div>
			</center>

			<center>
			<div style="margin-top: 8px;margin-bottom: 17px;">
				<button type="submit" class="btn btn-success" title="Agregar Registros">Agregar Perfil</button>
			</div>
			</center>

			<div class="msj-recepcion" id="div_mensaje"></div>

			</form>
		
		<div id="result"></div>
	</div>	

<div id="loading" style="display: none;">
<img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
</div>  


    <div id="tabla">

    </div>


</div>

<script type="text/javascript">
	
	miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Admin/GuardarPerfiles'?>";

	$(document).ready(function() {
	    miurl2 = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Admin/ConsultaPerfiles'?>";

	    var $container = $("#tabla");
        $container.load(miurl2);

         $('#form, #fat, #foconsulta').submit(function() {
	    	
	        $.ajax({
	            type: 'POST',
	            url: miurl,
	            data: $(this).serialize(),
	            // Mostramos un mensaje con la respuesta de PHP
	            success: function(data) {
	            	$('#Info').fadeIn(1000).html(data);	            	
	            	$container.load(miurl2);
			    	$('#div_mensaje').empty();
			    	 $('#div_mensaje').append("<li>"+data+ "</li>"); 
			    	 $('#div_mensaje').fadeIn('slow').delay(3000).hide(0);
	            }
	        })        
	        return false;
	    });


	})
</script>
