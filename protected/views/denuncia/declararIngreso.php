<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Declarar Ingreso del Parte en SAF</div><div class="subtitulo"></div>
	</div>


	<div class="derivarparte">	


	 	<div class="btn-derivar-parte">
			<button id="btn_derivar" name="derivar" class="btn btn-warning" onclick="DerivarParte()">Declarar Ingreso</button>
		</div>

		<div id="loading" style="display: none;">
			<img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading2.gif" alt="Loading..." />
		</div> 

	</div>


	<div id="partes" style="padding: 10px;overflow-x: auto;">
		
			
	</div>


</div>


<script type="text/javascript">
	$(document).ready(function() {

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/ListarPartesIngreso'?>";
	    $.ajax({
		    url: urltable,
		    type: 'POST',
		    success: function(result){  
		        $('#partes').empty();
	    		$('#partes').append(result);  
		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;


	});	
</script>


<script type="text/javascript">
    function DerivarParte(){

    	var j=0;
    	var derivar=[];
    	var activ=[];

    	var checkedValue = null; 
    	var tempActiv = null;

		var inputElements = document.getElementsByClassName('checDerivar');
		var inputActividad = document.getElementsByClassName('checActividad');
		for(var i=0; inputElements[i]; ++i){
		      if(inputElements[i].checked){
		           checkedValue = inputElements[i].value;
		           derivar[j]=checkedValue;

		           tempActiv = inputActividad[i].value;
		           activ[j]=tempActiv;
		           j++;
		      }
		}//FIN FOR

		//alert(derivar);

	 	if(derivar!=""){
	 		document.getElementById("loading").style.display = "block" ;

	 		$.ajax({
			    type: 'POST',
			    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/DeclararParteSelec&derivar='?>"+derivar+'&activ='+activ,
			    dataType:"json",
			
					success: function(data) {

						 if(data.status === "success") {

							swal({
							  title: "Partes declarados como ingresados en SAF con éxito",
							  text: "Cargando nuevos datos......",
							  type: "success",
							  timer: 10,
							  showConfirmButton: false
							}, function(){							      
							      window.location.reload(true);
							      document.getElementById("loading").style.display = "none" ;
							});

			           } else if(data.status === "error") {

							swal({
							  title: "No se puede asignar.",
							  text: data.message,
							  type: "error",
							  timer: 10,
							  showConfirmButton: true
							}, function(){
							      document.getElementById("loading").style.display = "none" ;
							});

			
			            }//fin else			    					    		
					}//fin succes
					
					
			});
			return false;
	 	}
	 	else alert("Debe seleccionar minímo 1 parte");

    }//FIN FUNCION ELIMINAR	
</script>