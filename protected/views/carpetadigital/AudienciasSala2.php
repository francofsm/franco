<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Listado de Causas para Audiencia - SALA 2</div><div class="subtitulo">Desde RUC, es posible acceder a Ficha caso.</div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>    

     	<div>
  		<form action="#" id="formoid" method="post"	>	

		<div class="row-inline-medi">

					<label>Fecha Audiencia<span class="required">*</span></label>
					<input type="date" id="fec_inicio" name="fec_inicio" class="input-md" required="required" value="<?php echo date("Y-m-d",strtotime(date("Y-m-d"))); ?>"></input>
			
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
    	

		//var datastring = $("#formoid").serialize(); 
    	document.getElementById("loading").style.display = "block";

    	var fec_desde = $("#fec_inicio").change({ dateFormat: "yyyy/mm/dd" }).val();
    	
    	urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ListarCausaAudienciaSala2'?>";

		$.ajax({
	        url: urlsave,
	        type: 'POST',
	        //data: datastring,

	        data: {fec_ini: fec_desde},
	        success: function(result){  
	                
 				$('#tareas').empty();
				$('#tareas').append(result); 
				document.getElementById("loading").style.display = "none";
	           
	        }//fin success
	    });
	    return false;
	    
	

	});

	function adjuntarMinuta(val, id) {

		miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/UploadMinuta&codigo='?>"+id
		
	    var fd = new FormData();
	    var c=0;
	    var file_data,arr;
	    $('input[type="file"]').each(function(){
	        file_data = $('input[type="file"]')[c].files; // get multiple files from input file
	        console.log(file_data);
	        for(var i = 0;i<file_data.length;i++){
	            fd.append('arr[]', file_data[i]); // we can put more than 1 image file
	        }
	        c++;
	    }); 

	    $.ajax({
		    type: 'POST',
		    url: miurl,
		    data: fd,
	        contentType: false,
	        processData: false,
		    success: function(data) {
		    	  document.getElementById("loading").style.display = "block";

		    	var fec_desde = $("#fec_inicio").change({ dateFormat: "yyyy/mm/dd" }).val();
		    	
		    	urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ListarCausaAudienciaSala2'?>";

				$.ajax({
			        url: urlsave,
			        type: 'POST',
			        //data: datastring,

			        data: {fec_ini: fec_desde},
			        success: function(result){  
			                
		 				$('#tareas').empty();
						$('#tareas').append(result); 
						document.getElementById("loading").style.display = "none";
			           
			        }//fin success
			    });
			    return false;

		    }//FIN SUCCESS POST 1
		})        
		return false;
	}

	function adjuntarResultado(val, id) {

		miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/UploadMinutaResultadoS2&codigo='?>"+id
		
	    var fd = new FormData();
	    var c=0;
	    var file_data,arr;
	    $('input[type="file"]').each(function(){
	        file_data = $('input[type="file"]')[c].files; // get multiple files from input file
	        console.log(file_data);
	        for(var i = 0;i<file_data.length;i++){
	            fd.append('arr[]', file_data[i]); // we can put more than 1 image file
	        }
	        c++;
	    }); 

	    $.ajax({
		    type: 'POST',
		    url: miurl,
		    data: fd,
	        contentType: false,
	        processData: false,
		    success: function(data) {
		    	  document.getElementById("loading").style.display = "block";

		    	var fec_desde = $("#fec_inicio").change({ dateFormat: "yyyy/mm/dd" }).val();
		    	
		    	urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ListarCausaAudienciaSala2'?>";

				$.ajax({
			        url: urlsave,
			        type: 'POST',
			        //data: datastring,

			        data: {fec_ini: fec_desde},
			        success: function(result){  
			                
		 				$('#tareas').empty();
						$('#tareas').append(result); 
						document.getElementById("loading").style.display = "none";
			           
			        }//fin success
			    });
			    return false;

		    }//FIN SUCCESS POST 1
		})        
		return false;
	}
</script>

<script type="text/javascript">
	function actualizarTabla(id){

		//var datastring = $("#formoid").serialize(); 
    	document.getElementById("loading").style.display = "block";

    	var fec_desde = $("#fec_inicio").change({ dateFormat: "yyyy/mm/dd" }).val();
    	
    	urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ListarCausaAudienciaSala2'?>";

		$.ajax({
	        url: urlsave,
	        type: 'POST',
	        //data: datastring,

	        data: {fec_ini: fec_desde},
	        success: function(result){  
	                
 				$('#tareas').empty();
				$('#tareas').append(result); 
				document.getElementById("loading").style.display = "none";
	           
	        }//fin success
	    });
	    return false;
	}
</script>


<!--- ACTUALIZACION ELIMINACION DE MINUTAS Y RESULTADOS DE AUDIENCIA   ---> 

<script type="text/javascript">
	
	function elimMinAud(id){

		//alert(id);

		urldel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/EliminarMinutaAudiencia'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urldel,
		      type: 'POST',
		      data: {id: id},
		      success: function(data){  
		     //		alert("hola");
		     		var fec_desde = $("#fec_inicio").change({ dateFormat: "yyyy/mm/dd" }).val();
		    		//salaaud=$("#salaaud").prop("value");

		            urlver = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ListarCausaAudienciaSala2'?>";
					$.ajax({
					    url: urlver,
					    type: 'POST',
					   // data: {fec_ini: fec_desde, salaaud: salaaud},
					    data: {fec_ini: fec_desde},
					    success: function(result){ 
							$('#tareas').empty();
				    		$('#tareas').append(result); 				    		
					    }//fin success
					});//fin post tabla según gestor seleccionad
		            document.getElementById("loading").style.display = "none"; 
		           
		      }//fin success
		});
		return false;
	}
</script>

<script type="text/javascript">
	
	function elimResulAud(id){

	//	alert(id);
		
		urldel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/EliminarResultadoAudiencia'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urldel,
		      type: 'POST',
		      data: {id: id},
		      success: function(data){  
		     	//	alert("hola resultado");
		     		var fec_desde = $("#fec_inicio").change({ dateFormat: "yyyy/mm/dd" }).val();
		    		//salaaud=$("#salaaud").prop("value");

		            urlver = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ListarCausaAudienciaSala2'?>";
					$.ajax({
					    url: urlver,
					    type: 'POST',
					  //  data: {fec_ini: fec_desde, salaaud: salaaud},
					    data: {fec_ini: fec_desde},
					    success: function(result){ 
							$('#tareas').empty();
				    		$('#tareas').append(result); 				    		
					    }//fin success
					});//fin post tabla según gestor seleccionad
		            document.getElementById("loading").style.display = "none"; 
		           
		      }//fin success
		});
		return false;
	}
</script>