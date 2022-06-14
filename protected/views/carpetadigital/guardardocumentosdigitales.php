<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src='../start/js/validar_ruc.js' ></script>
<div class="pagina">


	<div class="div-titulo">		
		<div class="titulo">Ingresar Documentos Digitales</div><div class="subtitulo"></div>
		<div class="subtitulo">Permite registrar documentos digitalizados de una carpeta.</div>
	</div>

	<div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
    </div>


	<div class="col-md-5">

		<div class="form_cargar_doc">
 		<form action="" id="formoid" method="post"	> 		

	     <div class="row-inline-rev">
			<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
			<input type="text" id="ruc" name="ruc" onchange="validacionRuc(this.value)" autofocus value="<?php if(isset($ruc)) echo $ruc; ?>" />
		</div>

		<div class="row-inline-rev">
			<label>Clase documental</label>
			<select name="clase" id="clase">
				<option value="">Seleccionar tipo de documento</option>
			<?php 
				foreach ($clased as $c => $value) {	   									    	
					echo '<option value="'.$clased[$c]->cod_clasedoc .'">'. $clased[$c]->gls_clasedoc .'</option>';
				}
			?>
			</select>			
		</div>	

			<div class="row-inline-normal">
				<label>Fecha Documento <span class="required">*</span></label>
				<input type="date" id="fec_actividad" name="fec_actividad" class="input-md" required="required" value="<?php echo date('Y-m-d'); ?>"></input>
			</div>

	    <div class="row-inline-medi">
			<input type="file" name="file[]" id="file" accept="application/pdf"  disabled onchange="adjuntar(this.value)"> 
			<input id="adjunto1" name="adjunto1" type="hidden">
		</div>
		<br><br>

		<div class="row-inline-rev">	
			<button id="btn_control" name="guardar" class="btn btn-primary">Guardar</button>
		</div>

		<div id="msj_error" style="display: none"></div>
		<div id="msj_ok" style="display: none"></div>
    
    	</form>
    	</div>

    	<ol>
		
		  <li>Seleccionar <strong>clase documental</strong> según tipo de documento 
		  <p style="font-style: italic;color: red;margin-bottom: -2px;">(Importante seleccionar la clase antes de adjuntar el documento)</p></li>
		  <li>Seleccionar la fecha del documento cargado.</li>
		  <li>Adjuntar documento digital y esperar que termine de cargar.</li>
		</ol>
    	

	</div><!--fin div 4-->

    <div class="col-md-7" style="margin-left: 15px;">
    	<div id="tabla">
    		
    	</div>
    </div>


</div>

<script type="text/javascript">
	$(document).ready(function() {	
		document.getElementById("loading").style.display = "block";
		urlver = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/VerDocumentosCargados'?>";
		$.ajax({
		    url: urlver,
		    type: 'POST',
		    success: function(result){ 
				$('#tabla').empty();
	    		$('#tabla').append(result); 
	    		document.getElementById("loading").style.display = "none";
		    }//fin success
		});//fin post tabla según gestor seleccionad
	return false;
	});	
</script>

<script type="text/javascript">
	 $("#formoid").submit(function(event) {

	 	document.getElementById("loading").style.display = "block";

	 	var datastring = $("#formoid").serialize(); 
	 	urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/GuardarDocumentoDigital'?>";
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
		    	   //document.getElementById("clase").value="";

					urlver = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/VerDocumentosCargados'?>";
					$.ajax({
					    url: urlver,
					    type: 'POST',
					    success: function(result){ 
							$('#tabla').empty();
				    		$('#tabla').append(result); 
				    		document.getElementById("loading").style.display = "none";
					    }//fin success
					});//fin post tabla según gestor seleccionad
	
	            }
	            else{
	            	document.getElementById("msj_error").style.display = "block"; 
	            	$('#msj_error').empty();
	    			$('#msj_error').append(result.message);
	    			$('#msj_error').fadeIn('slow').delay(3000).hide(0);

	            	document.getElementById("loading").style.display = "none";
	            }
	        }
	    });
	    return false;
	});
</script>

<script type="text/javascript">
	function elimDoc(id){

		var r = confirm("Segur@ que deseas eliminar el documento digital?");
		if (r == true) {
			urldel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/EliminarDocumentoCargado'?>";
			document.getElementById("loading").style.display = "block";
			$.ajax({
			      url: urldel,
			      type: 'POST',
			      data: {id: id},
			      success: function(data){  
			     
			            urlver = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/VerDocumentosCargados'?>";
						$.ajax({
						    url: urlver,
						    type: 'POST',
						    success: function(result){ 
								$('#tabla').empty();
					    		$('#tabla').append(result); 				    		
						    }//fin success
						});//fin post tabla según gestor seleccionad
			            document.getElementById("loading").style.display = "none"; 
			           
			      }//fin success
			});
			return false;		
		}//fin 

	}

	function validacionRuc($ruc){

		campo=$("#ruc").prop("value")
		if ( validaRuc(campo) ){
			document.getElementById("file").disabled = false;
		}
		else{
			document.getElementById("file").disabled = true;
		}

        
	}

function adjuntar(val) {

	document.getElementById("loading").style.display = "block";

	var ruc = $("#ruc").val();
	var clase = $("#clase").val();	

		miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/UploadDocumentoDigital&ruc='?>"+ruc+'&clase='+clase

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
		    		document.getElementById("loading").style.display = "none";
		    		document.getElementById("adjunto1").value=result.message; 
		    	}
		    	else{
		    		document.getElementById("loading").style.display = "none";

		    		document.getElementById("msj_ok").style.display = "block"; 
		    		$('#msj_ok').empty();
	    			$('#msj_ok').append("Estas sobreescribiendo el documento");
	    			$('#msj_ok').fadeIn('slow').delay(3000).hide(0);

	            	document.getElementById("loading").style.display = "none";
		    		document.getElementById("adjunto1").value=result.message; 
		    	}	    
		    }//FIN SUCCESS POST 1
		})        
		return false;
}

	function verCarpeta(id){	
		document.getElementById("loading").style.display = "block";
		var ruc = $("#ruc").val(); 

		window.open(id , ruc , "width=1400,height=720,scrollbars=NO,status=yes, toolbar=no, top=200, left=200, directories=0, titlebar=0");
		document.getElementById("loading").style.display = "none";
	}
</script>