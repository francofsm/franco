<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Cambiar de Fiscalía en SIA a funcionario</div><div class="subtitulo"></div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     

	<div id="tabla" style="padding: 10px;">
		
	</div>

<!--MODAL-->


		<div id="openConsulta" class="modalDialog">
			<div class="col-md-12 servicio">
				<a href="#close" title="Close" class="close">X</a>					
					<div class="titulo_form">Cambiar de Fiscalía en SIA a funcionarios</div>
					<div class="item" style="text-align: center;">
					    <div id="datos2">
    	
     					</div>
					    	
					</div>
			</div>	
		</div>
	
<!--FIN-->

</div>

<script type="text/javascript">
	$(document).ready(function() {


	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Admin/ListarFuncionariosFun'?>";
	$.ajax({
	    url: urltable,
	    type: 'POST',
	    success: function(result){  
		                      
	        $('#tabla').empty();
	   		$('#tabla').append(result); 
		             
	    }//fin success
	});//fin post tabla según gestor seleccionado

	return false;

	});	
</script>

<script type="text/javascript">
	function modificarEquipo(id){

		urlmod = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Admin/ModificarFiscaliaFun'?>";
	    $.ajax({
		    url: urlmod,
		    type: 'POST',
		    data: {id: id},
		    success: function(result){  
		         
		         miform = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Admin/ModificarFiscalia#openConsulta'?>";	

		        $('#datos2').html(result);
		   		 window.location.href = miform
		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

	}
</script>

<script type="text/javascript">
	function guardarFiscalia(){
		var fun = $("#fun").val(); 
		var fis = $("#fisca").val(); 
		urlmod = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Admin/GuardarFiscaliaFun'?>";
	    $.ajax({
		    url: urlmod,
		    type: 'POST',
		    data: {fun: fun, fis: fis},
		    success: function(data){  
		         
		        urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Admin/ListarFuncionariosFun'?>";
				$.ajax({
				    url: urltable,
				    type: 'POST',
				    success: function(result){  
					     
					    miadmin = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Admin/ModificarFiscalia'?>";	

		 
		   		 		window.location.href = miadmin

				        $('#tabla').empty();
				   		$('#tabla').append(result); 
					             
				    }//fin success
				});//fin post tabla según gestor seleccionado

		             
		    }//fin success
		});//fin post tabla según gestor seleccionado	


	}

</script>
