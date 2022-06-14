<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<script src="../start/protected/extensions/barcode/JsBarcode.all.js"></script>



<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Carpetas pendientes de recepción</div><div class="subtitulo">Listado de carpetas en estado "Pendientes de Recepción"</div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     


	<div class="col-md-33">
			<label>FUNCIONARIO:</label>
			<select name="ubicacion" id="ubicacion" class="chosen_fun" onchange="consultar(event);">
				
				<?php 
					foreach ($fun as $c => $value) {	   									    	
						echo '<option value="'.$fun[$c]->fun_rut .'">'. $fun[$c]->fun_ap_paterno.' '.$fun[$c]->fun_nombre.' '.$fun[$c]->fun_nombre2 .'</option>';
					}
				?>
			</select>
	</div>

	<div class="col-md-33">

	     <div style="width: 250px;">
			<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
			<input type="text" id="ruc" name="ruc" onkeyup = "if(event.keyCode == 13) enterRuc(this.value)" autofocus/>
		</div>

	</div>

	<div class="col-md-33">

	<div class="btn_recepmasivo">
		<button id="btn_control" name="recepcion" class="btn btn-primary" onclick="recepcion()">Recepción</button>
	</div>

	</div>
	



		<div id="carpetas" style="padding: 10px;">
		
	
		</div>


<script type="text/javascript">
	$(".chosen_fun").chosen();
</script>

 <script type="text/javascript">
    function recepcion(){

    	var j=0;
    	var recep=[];

    	var checkedValue = null; 
		var inputElements = document.getElementsByClassName('checRecepcion');
		for(var i=0; inputElements[i]; ++i){
		      if(inputElements[i].checked){
		           checkedValue = inputElements[i].value;
		           recep[j]=checkedValue;
		           j++;
		      }
		}//FIN FOR

		//alert(recep);

	 	if(recep!=""){

	    	document.getElementById('loading').style.display= 'block';

	 		$.ajax({
			    type: 'POST',
			    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/GuardarRecepcionMasivo&recep='?>"+recep,
			    dataType: "json",
			    	success: function(data) {

			    		if(data.status === "success") {
				             alert(data.message)     
				             window.location.reload(true);	
				         }
				         else{
			                alert(data.message)      
			            } 	    		
					}
					
			});
			return false;
	 	}

    }//FIN FUNCION ELIMINAR	
</script>

<script type="text/javascript">
function enterRuc($ruc){
        buscar=$("#ruc").prop("value")
        buscar = buscar.toUpperCase();
        encontradoResultado=false; 
        i=1;
        $("#listarea tr").find('td:eq(1)').each(function () {
 			

               codigo = $(this).html();

              

               if(codigo==buscar){                   
                    trDelResultado=$(this).parent();                    
                    ruc=trDelResultado.find("td:eq(1)").html();              
                  	document.getElementById("recepcion["+i+"]").checked = true;
                    encontradoResultado=true;
                    $("#ruc").val(""); 	
 		       }//fin buscar==codigo
 		     
 		       	       
 		i++;
        })

        if(encontradoResultado!=true){
        	alert("RUC no encontrado, puede estar en otra página")
        }
}
</script>

<script type="text/javascript">
    function MarcarTodos(source) {
        var aInputs = document.getElementsByTagName('input');
        for (var i=0;i<aInputs.length;i++) {
            if (aInputs[i] != source && aInputs[i].className == source.className) {
                aInputs[i].checked = source.checked;
            }
        }
    }
</script>

<script type="text/javascript">
	function consultar(e){


	var cod = $("#ubicacion").val(); 

  	$.ajax({
	    type: 'POST',
	    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/ListarCarpetasPendientesRecepcion&cod='?>"+cod,
	    success: function(result){  

	    	$('#carpetas').empty();
	   		$('#carpetas').append(result); 

		             
	    }//fin success
	});//fin post tabla según gestor seleccionado

	return false;


}
</script>

<script type="text/javascript">
	$(document).ready(function() {

	var cod = $("#ubicacion").val(); 

  	$.ajax({
	    type: 'POST',
	    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/ListarCarpetasPendientesRecepcion&cod='?>"+cod,
	    success: function(result){  

	    	$('#carpetas').empty();
	   		$('#carpetas').append(result); 

		             
	    }//fin success
	});//fin post tabla según gestor seleccionado

	return false;

	});	
</script>