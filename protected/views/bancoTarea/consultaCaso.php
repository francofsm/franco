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
		<div class="titulo">Consultar Diligencias según Caso</div><div class="subtitulo">Diligencias decretadas de un caso y sus respectivos estados.</div>
	</div>

	<div class="div-form-uno">

			<form id="foconsulta" class="form-horizontal"  method="post" name="foconsulta">
			<center>			

			<div class="row-inline-normal">
				<label>Ruc <span class="required">*</span></label>
				<input type="text" id="ruc" name="rolunico"  maxlength="12" placeholder="Ingrese RUC" required="required" />
			</div>
			</center>

			<center>
			<div style="margin-top: 8px;margin-bottom: 17px;">
				<button type="submit" class="btn btn-success" title="Agregar Registros">Consultar</button>
			</div>
			</center>

			<div class="msj-recepcion" id="div_mensaje"></div>

			</form>
		
		<div id="result"></div>
	</div>	

	<div id="loading" style="display: none;">
		<img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading2.gif" alt="Loading..." />
	</div> 


	<span class="label label-info">Diligencias decretadas.</span>	
    <div style="min-height: 980px;" id="tabla">

    </div>

</div>



<script type="text/javascript">	
	$(document).ready(function() {
	
	   miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ConsultaDetalleCaso'?>";

	   $('#form, #fat, #foconsulta').submit(function() {
	   		
	   		var ruc = $("#ruc").val();	

	   		if(ruc!=""){
	   		
	   		document.getElementById('loading').style.display= 'block';
	   			
	    	$.ajax({
	    		url: miurl,
	    		type: 'POST',
	    		data: {valor: ruc},
	    		success: function(result){
	    			$("#tabla").html(result);	 
	    			 document.getElementById('ruc').value="";   
	    			 document.getElementById('loading').style.display= 'none';						
	    		}
	    	});
	    	return false;
	   		}
	    	

	    });   

	});
</script>