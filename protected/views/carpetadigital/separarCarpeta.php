<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<script src='../start/js/validar_ruc.js' ></script>

<div class="pagina">
	<div class="div-titulo">		
		<div class="titulo">Separación de causa</div><div class="subtitulo">Desde la causa que deseas separar, debes seleccionar los documentos a copiar a la nueva causa e indicar el ruc generado</div>
	</div>

	<div id="loading" style="display: none;">
	 	<img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
    </div>

	<div class="col-md-7">	

	    <div class="row-inline-rev">
			<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
			<input type="text" id="ruc" name="ruc" onchange="validacionRuc(this.value)" autofocus value="<?php if(isset($ruc)) echo $ruc; ?>" />
		</div>

		<div class="row-inline-rev">
			<label style="font-weight: bolder;font-size: 14px;">RUC NUEVO:</label> 
			<input type="text" id="nuevo" name="nuevo" onchange="validacionRucNuevo(this.value)" />
		</div>

		<div id="docs" style="padding: 4px;margin-right: 23px;">
			
		</div>

		<div class="col-md-12" id="btn_submit" style="margin-top: 20px;">
			<button id="btn_control" name="guardar"  class="btn btn-primary" onclick="GuardarDatos()">Separar Causa</button>
		</div>
    

	</div><!--fin div 4-->

    <div class="col-md-5">
    	<div id="tabla">
    		
    	</div>
    </div>


</div>

<script type="text/javascript">
	function listarDocumentos(){
		document.getElementById("loading").style.display = "block";	
		var ruc = $("#ruc").val(); 
		urlist = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/DocumentosRuc'?>";

		$.ajax({
		    url: urlist,
		    type: 'POST',
		    data: {ruc: ruc},
		    success: function(result){  
		        $('#docs').empty();
		        $('#docs').append(result);   
		        document.getElementById("loading").style.display = "none";
		    }//fin success
		});
	}
</script>


<script type="text/javascript">
	function listarCausasSeparadas(){
		urlsepara = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ListarCausasSeparadas'?>";

		$.ajax({
		      url: urlsepara,
		      type: 'POST',
		      success: function(result){  
		          $('#tabla').empty();
		          $('#tabla').append(result);      
		      }//fin success
		});
	}
</script>

<script type="text/javascript">
	$(document).ready(function() {
		document.getElementById("loading").style.display = "block";
		listarCausasSeparadas();
		document.getElementById("loading").style.display = "none";

	});	

	function validacionRuc($ruc){

		campo=$("#ruc").prop("value")
		if ( validaRuc(campo) ){	
			listarDocumentos();
		}
		else{		
			
		}

        
	}

	function validacionRucNuevo($nuevo){
		nuevo=$("#nuevo").prop("value")
		if ( validaRuc(nuevo) ){	
			document.getElementById("btn_submit").style.display = "block";
		}
		else{		
			document.getElementById("btn_submit").style.display = "none";
		}
	}

	function MarcarTodosSeparar(source) {
        var aInputs = document.getElementsByTagName('input');
        for (var i=0;i<aInputs.length;i++) {
            if (aInputs[i] != source && aInputs[i].className == source.className) {
                aInputs[i].checked = source.checked;
            }
        }
    }

 	function GuardarDatos(){
 		var ruc = $("#ruc").val(); 
 		var nuevo = $("#nuevo").val(); 

 		var separa=[]; 
    	var checkedValue = null;  
		var inputElements = document.getElementsByClassName('checSeparar');

		var j=0;
		for(var i=0; inputElements[i]; ++i){
		      if(inputElements[i].checked){
		           checkedValue = inputElements[i].value;
		           separa[j]=checkedValue;		
		           ++j;           
		      }
		}//FIN FOR

		if ( validaRuc(nuevo) ){

			document.getElementById("loading").style.display = "block";
			$.ajax({
			      url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/GuardarSepararCausa&ruc='?>"+ruc+'&nuevo='+nuevo+'&separa='+separa,
			      type: 'POST',
			      dataType: "json",
			      success: function(result){  
			          if(result.status === "success") {
			              location.reload();
			          }else{
			          	document.getElementById("loading").style.display = "none"; 
			          	alert(result.message)
			          } 
			      }//fin success
			});
			return false;

		}//fin if

 	}
</script>