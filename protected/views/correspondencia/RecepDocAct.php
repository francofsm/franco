<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src='../start/js/validar_ruc.js' ></script>

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Ingreso de Correspondencia recibida</div><div class="subtitulo"></div>
		<div class="subtitulo"></div>
	</div>

	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     


	<div class="div-form-uno">

		<form id="formcorres" class="form-horizontal"  method="post" name="formcorres">

			<div class="row-inline-rev">
				<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
				<input type="text" id="ruc" name="ruc" onchange="validacionRuc(this.value)" autofocus required="" />
			</div>

			<div class="row-inline-normal">
				<label>Fecha Recepción Documento <span class="required">*</span></label>
				<input type="date" id="fec_actividad" name="fec_actividad" class="input-md" required="required" value="<?php echo date('Y-m-d'); ?>"></input>
			</div>

			<div class="row-inline-normal">
				<label>Sujeto <span class="required">*</span></label>
				<select id="rucrel" name="rucrel" class="input-md" required="required">
					<option value="">Seleccionar Sujeto</option>	
				</select>
			</div>

			<div class="row-inline-normal">
				<label>Fiscal</label>
				<select id="fiscal" name="fiscal" class="input-md" required="required">
					
				</select>
			</div>

		    <div class="row-inline-normal">
				<label>Estado Caso</label>
				<input type="text" id="estado_caso" name="estado_caso">
			</div>

	     	<div class="row-inline-normal">	
				<label>RIF</label>
				<input type="text" id="rif" name="rif">
			</div>
		
			<div id="datos_hidden">
				
			</div>

			<input type="hidden" id="usuario" name="usuario" value="<?php echo Yii::app()->user->id; ?>">

			<br>
			<div class="row-instruir-observa">
				<label>Comentarios (Máximo 250 caracteres)</label>
				<textarea style="height: 40px;" name="comentario" id="comentario"></textarea>
			</div>

			<div class="row-inline-normal" style="min-height: 29px;margin-top: 25px;">	
				<input type="file" name="file[]" id="file" accept="application/pdf"  disabled onchange="adjuntar(this.value)"> 
				<input id="adjunto1" name="adjunto1" type="hidden">
			</div>

			<div style="margin-top: 27px;margin-bottom: 17px;">
				<button type="submit" class="btn btn-success" title="Agregar Registros">Agregar</button>
			</div>		

			<div class="msj-guardado" id="div_mensaje"></div>
		</form>


		<div style="text-align: right;">
			<button type="submit" class="btn btn-success" title="GrabarSAF"  onclick="GrabarSAF()">Grabar en SAF</button>
		</div>

		<div id="result"></div>

	</div>

	<div id="tabla">
		
	</div>



</div>

<script type="text/javascript">

$(document).ready(function() {

	 urlver = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Correspondencia/ListadoDoc'?>";
     var $container = $("#tabla");
     $container.load(urlver);

  })
</script>

<script type="text/javascript">
function adjuntar(val) {

	document.getElementById("loading").style.display = "block";

	var ruc = $("#ruc").val();

		miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Correspondencia/UploadInforme&ruc='?>"+ruc

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
		    		alert("Estas sobreescribiendo el documento")
		    		document.getElementById("adjunto1").value=result.message; 
		    	}	    
		    }//FIN SUCCESS POST 1
		})        
		return false;
}
</script>


<script type="text/javascript">
	function validacionRuc($ruc){

		ruc=$("#ruc").prop("value")
		if ( validaRuc(ruc) ){

			document.getElementById("file").disabled = false;

			document.getElementById("loading").style.display = "block";			

			urlver = "http://172.17.123.19/conect_saf/consulta_sujetos_correspondencia.php";

			$.ajax({
			    url: urlver,
			    type: 'GET',
			    data: {ruc: ruc},
			    success: function(result){ 
	    			$("#rucrel").html(result);
	    			
	    			urlfis = "http://172.17.123.19/conect_saf/consulta_fiscal_correspondencia.php";
					$.ajax({
					    url: urlfis,
					    type: 'GET',
					    data: {ruc: ruc},
					    success: function(result){ 
					    	$("#fiscal").html(result);
			    			
			    			urlestado = "http://172.17.123.19/conect_saf/consulta_estado_correspondencia.php";
							$.ajax({
							    url: urlestado,
							    type: 'GET',
							    data: {ruc: ruc},
							    success: function(result){ 
							    	document.getElementById("estado_caso").value = result; 

					    			urlrif = "http://172.17.123.19/conect_saf/consulta_rif_correspondencia.php";
									$.ajax({
									    url: urlrif,
									    type: 'GET',
									    data: {ruc: ruc},
									    success: function(result){ 
									    	document.getElementById("rif").value = result; 
									    	
							    			urlid = "http://172.17.123.19/conect_saf/consulta_idrelacion_correspondencia.php";
											$.ajax({
											    url: urlid,
											    type: 'GET',
											    data: {ruc: ruc},
											    success: function(result){ 

											    	$('#datos_hidden').empty();
	    											$('#datos_hidden').append(result); 
											    	
									    			document.getElementById("loading").style.display = "none";	
									    		}
									    	});//fin consulta_idrelacion_correspondencia

							    		}
							    	});//fin consulta_rif_correspondencia
					    		}
					    	});//fin consulta_estado_correspondencia
			    		}
			    	});//fin consulta_fiscal_correspondencia

	    		}
	    	}); // fin consulta_sujetos_correspondencia
			
		}
		else{
			document.getElementById("file").disabled = true;
		}

        
	}
</script>


<script type="text/javascript">
	 $("#formcorres").submit(function(event) {

	 	document.getElementById("loading").style.display = "block";

	 	var datastring = $("#formcorres").serialize(); 
	 	urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Correspondencia/GuardarCorrespondencia'?>";
	 	$.ajax({
	        url: urlsave,
	        type: 'POST',
	        data: datastring,
	        dataType: "json",
	        success: function(result){  
	            if(result.status === "success") {

		    		document.getElementById("ruc").value=""; 
		    		document.getElementById("rucrel").value="";
		    		document.getElementById("fiscal").value="";
		    		document.getElementById("estado_caso").value="";
		    		document.getElementById("rif").value="";
		    		document.getElementById("idrelacion").value="";
		    		document.getElementById("comentario").value="";
		    		document.getElementById("file").value="";
		    		document.getElementById("adjunto1").value="";

					urlver = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Correspondencia/ListadoDoc'?>";
				    var $container = $("#tabla");
				    $container.load(urlver);

				    document.getElementById("loading").style.display = "none";
	
	            }
	            else{
	            	alert(result.message)
	            	document.getElementById("loading").style.display = "none";
	            }
	        }
	    });
	    return false;
	});
</script>


<script type="text/javascript">
	function elimDoc(id){	

		var r = confirm("Estas segur@ que deseas eliminar la Correspondencia?");
		if (r == true) {
			urldel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Correspondencia/EliminarDoc'?>";
			document.getElementById("loading").style.display = "block";
			$.ajax({
			      url: urldel,
			      type: 'POST',
			      data: {id: id},
			      success: function(data){  
			     
			            urlver = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Correspondencia/ListadoDoc'?>";
					    var $container = $("#tabla");
					    $container.load(urlver);
			            document.getElementById("loading").style.display = "none"; 
			           
			      }//fin success
			});
			return false;		
		}//fin consulta

	}
</script>

<script type="text/javascript">	

  function GrabarSAF(){ 

  	document.getElementById("loading").style.display = "block";  

  	usuario=$("#usuario").prop("value") 

  	urlgrabar = "http://172.17.123.19/conect_saf/guardar_correspondencia.php";

    $.ajax({
	    type: 'GET',
	    url: urlgrabar,
	    data: {usuario: usuario},
   	
   	    success: function(result){

   	    	alert(result)
			urlver = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Correspondencia/ListadoDoc'?>";
			var $container = $("#tabla");
			$container.load(urlver);
			document.getElementById("loading").style.display = "none"; 

	    }//fin success

     });

	return false;
	};
</script>

<!--
if (result==1){
				alert("error al grabar en SAF, contactar al administrador")
			}
			else{
				urlver = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Correspondencia/ListadoDoc'?>";
				var $container = $("#tabla");
				$container.load(urlver);

				document.getElementById("loading").style.display = "none"; 

			} //fin else-->