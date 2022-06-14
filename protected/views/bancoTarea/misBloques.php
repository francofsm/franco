<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">


<script src="../start/protected/extensions/barcode/JsBarcode.all.js"></script>

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Ejecutar Bloques de Tiempo Asignadas</div><div class="subtitulo"></div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     

     <!--<div style="width: 250px;">
		<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
		<input type="text" id="ruc" name="ruc" onkeyup = "if(event.keyCode == 13) enterRuc(this.value)" autofocus/>
	</div>-->

	<div class="btn-parte-consulta">
		<button id="btn_recepcion" name="ejecutar" class="btn btn-info" onclick="cargaLaboral()">Consultar Carga Laboral</button>
	</div>

	<div class="btn-parte-control">
		<button id="btn_recepcion" name="ejecutar" class="btn btn-warning" onclick="ejecutar()">Ejecutar</button>
	</div>

	<div class="btn-parte-pendiente">
		<button id="btn_control" name="anular" class="btn btn-primary" onclick="anular()">Anular</button>
	</div>


	<div style="float: right;margin-top: 12px;margin-right: 10px;width: 400px;">
		<label>Observaciones al Ejecutar/Anular</label>
		<textarea style="height: 55px;" name="obs" id="obs"></textarea>
	</div>


	<div id="tareas" style="padding: 10px;">
		
		</div>

<!--MODAL-->

		<div id="openConsulta" class="modalDialog">
			<div class="col-md-12">
				<a href="#close" title="Close" class="close">X</a>	
				<div class="col-md-6 servicio">
					<div class="titulo_form">Minutos Ejecutados del día</div>
					<div class="item" style="text-align: center;">
					    <div id="datos2">
    	
     					</div>
					    	
					</div>				
					
				</div>	
			</div>	
		</div>
	
<!--FIN-->

</div>

<script type="text/javascript">
	function cargaLaboral(){

		miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/consultaCargaLaboralFun'?>";
		
		miurlfin = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/MisDiligencias#openConsulta'?>";		

		$.ajax({
		   	url: miurl,
		   	type: 'POST',
		   	success: function(result){
		   		 $('#datos2').html(result);
		   		 window.location.href = miurlfin;   
		   	}//fin success
		});

		return false;
	}
</script>

<script type="text/javascript">
	$(".chosen_fun").chosen();
	$(".chosen_dil").chosen();
</script>

<script type="text/javascript">
	$(document).ready(function() {


	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarMisBloques'?>";
	    $.ajax({
		    url: urltable,
		    type: 'POST',
		    success: function(result){  
		                      
		        $('#tareas').empty();
	    		$('#tareas').append(result); 
		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;

	});	
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
                  	document.getElementById("ejecutar["+i+"]").checked = true;
                    encontradoResultado=true;
 		       }//fin buscar==codigo
 		     
 		       $("#ruc").val(""); 		       
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

    function MarcarTodosAnular(source) {
        var aInputs = document.getElementsByTagName('input');
        for (var i=0;i<aInputs.length;i++) {
            if (aInputs[i] != source && aInputs[i].className == source.className) {
                aInputs[i].checked = source.checked;
            }
        }
    }
</script>

 <script type="text/javascript">
    function anular(){

    	var anular=[]; 
    	var checkedValue = null;  
		var inputElements = document.getElementsByClassName('checAnular');

		for(var i=0; inputElements[i]; ++i){
		      if(inputElements[i].checked){
		           checkedValue = inputElements[i].value;
		           anular[i]=checkedValue;		           
		      }
		}//FIN FOR

		var obs = $("#obs").val(); 

	 	if(anular!=""){
	 		document.getElementById("loading").style.display = "block" ;

	 		$.ajax({
			type: 'POST',
			url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/GuardarAnularTareaFun&anular='?>"+anular+'&obs='+obs,
			dataType: "json",
			  	success: function(data) {
			   		
			   		if(data.status === "success") {
			   			urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarMisBloques'?>";
					    $.ajax({
						    url: urltable,
						    type: 'POST',
						    success: function(result){  
						                      
						        $('#tareas').empty();
					    		$('#tareas').append(result); 
					    		alert(data.message);
						             
						    }//fin success
						});//fin post tabla según gestor seleccionado
			   			document.getElementById("loading").style.display = "none" ;
			   			document.getElementById("obs").value=""; 
			   			
			   		}
			   		else{
			   			document.getElementById("loading").style.display = "none" ;
			   			alert(data.message);
			   		}		    		
				}//FIN SUCCESS					
			});
			return false;
	 	}
    }//FIN FUNCION ELIMINAR	
</script>

 <script type="text/javascript">
    function ejecutar(){

    	var ejec=[]; 
    	var checkedValue = null;  
		var inputElements = document.getElementsByClassName('checEjecutar');

		for(var i=0; inputElements[i]; ++i){
		      if(inputElements[i].checked){
		           checkedValue = inputElements[i].value;
		           ejec[i]=checkedValue;		           
		      }
		}//FIN FOR

		var obs = $("#obs").val(); 

	 	if(ejec!=""){
	 		document.getElementById("loading").style.display = "block" ;

	 		$.ajax({
			type: 'POST',
			url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/GuardarEjecutarTareaFun&ejec='?>"+ejec+'&obs='+obs,
			dataType: "json",
			  	success: function(data) {
			   		
			   		if(data.status === "success") {
			   			urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarMisBloques'?>";
					    $.ajax({
						    url: urltable,
						    type: 'POST',
						    success: function(result){  
						                      
						        $('#tareas').empty();
					    		$('#tareas').append(result); 
					    		alert(data.message);
						             
						    }//fin success
						});//fin post tabla según gestor seleccionado
						document.getElementById("obs").value=""; 
			   			document.getElementById("loading").style.display = "none" ;
			   			
			   		}
			   		else{
			   			document.getElementById("loading").style.display = "none" ;
			   			alert(data.message);
			   		}		    		
				}//FIN SUCCESS					
			});
			return false;
	 	}
    }//FIN FUNCION ELIMINAR	
</script>