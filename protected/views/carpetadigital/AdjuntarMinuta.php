<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<div class="pagina">


		<div class="div-titulo">		
			<div class="titulo">Seleccionar Minuta para Audiencia</div><div class="subtitulo"></div>
			<div class="subtitulo">Permite registrar asociar la minuta a la audiencia seleccionada.</div>
		</div>


		<div id="loading" style="display: none;">
			<img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
	    </div>
	    <br><br>


	<div class="row-inline-med">

		<!--<div style="margin-left: 35px;padding: 16px 85px;">  -->
 		
 		<form action="" id="formoid" method="post"	> 		

			<div class="row-inline-med">
				<?php 
				foreach ($idaud as $idaud) { ?>
				<label><font size=4>RUC:   </font><font size=3 color="'#3433FF'"><b><?php echo $idaud->idf_rolunico; ?></b></font></label>
				<br><br>	
				<label><font size=4>AUDIENCIA:  </font><font size=3 color="'#3433FF'"><b><?php echo $idaud->audiencia; ?></b></font></label>	
				<br><br>
				<label><font size=4>FECHA AUD.:  </font><font size=3 color="'#3433FF'"><b><?php echo date("d-m-Y", strtotime( $idaud->fec_audiencia)); ?></b></font></label>
				<br><br>
				<input type="hidden" id="ruc" name="ruc" value="<?php echo $idaud->idf_rolunico; ?>">
				<input type="hidden" id="cod" name="cod" value="<?php echo $idaud->cod_carpaud; ?>">

				<?php
				} 
				?>
			</div>


			<div class="row-inline-rev">
				<label>Clase documental</label>
				<select name="clase" id="clase">
				<?php 
					foreach ($clased as $c => $value) {	   									    	
						echo '<option value="'.$clased[$c]->cod_clasedoc .'">'. $clased[$c]->gls_clasedoc .'</option>';
					}
				?>
				</select>			
			</div>	
	     	<br><br>

		    <div class="row-inline-normal">
				<input type="file" name="file[]" id="file" accept="application/pdf"  onchange="adjuntar(this.value)"> 
				<input id="adjunto1" name="adjunto1" type="hidden">
			</div>
			<br><br>


			<div class="row-inline-medi">	
				<button id="btn_control" name="guardar" class="btn btn-primary">Guardar</button>
			</div>
        	<br><br>
	
			<div class="row-inline-rev">	
				<span class="btn btn-success" title="Cerrar" onclick="CerrarPagina()">Cerrar</span>
			</div>

    	</form>
    </div>



</div>  <!--pagina -->


<script type="text/javascript">
	 $("#formoid").submit(function(event) {

	 	document.getElementById("loading").style.display = "block";

	 	var datastring = $("#formoid").serialize(); 
	 	urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/GuardarDatosMinuta'?>";
	 	$.ajax({
	        url: urlsave,
	        type: 'POST',
	        data: datastring,
	        dataType: "json",
	        success: function(result){  
	            if(result.status === "success") {

		    		//document.getElementById("ruc").value=""; 
		    		document.getElementById("file").value="";
		    		document.getElementById("adjunto1").value="";
				
	            }
	            else{
	            	alert(result.message)
	            }
	        }
	    });
	    return false;
	});
</script>


<script type="text/javascript">
function adjuntar(val) {

	var ruc = $("#ruc").val();
	var clase = $("#clase").val();	

		miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/UploadParte&ruc='?>"+ruc+'&clase='+clase

	    var fd = new FormData();
	    var c=0;
	    var file_data,arr;

	    $('input[type="file"]').each(function(){
	        file_data = $('input[type="file"]')[c].files;
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
	        dataType: "json",
		    success: function(result) {
		    	if(result.status === "success") {
		    		alert(result.message)
		    		document.getElementById("adjunto1").value=result.message; 
		    	}
		    	else{
		    		alert("Estas sobreescribiendo el documento")
		    		document.getElementById("adjunto1").value=result.message; 
		    	}	    
		    }//FIN SUCCESS POST 1
		})        
		return false;
}
</script>


<script type="text/javascript">	

  function CerrarPagina()  { 
	miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/AudienciasFiscal'?>";
	window.location.href = miurl;
	return false; 
    
}


</script>