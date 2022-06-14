<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Derivar Partes</div><div class="subtitulo">Derivación de partes para ingreso en SAF</div>
	</div>


	<div class="derivarparte">	

		<div class="row-fec-parte">
			<label>Gestor <span class="required">*</span></label>
			<select name="gestor" id="gestor">
				<option value="">Seleccionar Gestor</option>
				<?php 
			    foreach ($ges as $c => $value) {	       
			       echo '<option value="'. $ges[$c]->fun_rut .'">'. $ges[$c]->nombre .'</option>';
			    }
				?>
			</select>
		</div>

		<div class="row-gestor-parte">
			<label>Fecha <span class="required">*</span></label>
			<input type="date" id="fec" name="fec" min="<?php echo date("Y-m-d"); ?>" class="input-md" required="required" value="<?php echo date('Y-m-d'); ?>"></input>
		</div>


		<div class="div_mensaje_derivar_parte" style="display: none;" id="div_mensaje_disponible"></div>


	 	<div class="btn-derivar-parte">
			<button id="btn_derivar" name="derivar" class="btn btn-warning" onclick="DerivarParte()">Derivar</button>
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

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/ListarPartesDerivar'?>";
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

		var gestor = $("#gestor").val();
		var fec = $("#fec").change({ dateFormat: "yyyy/mm/dd" }).val();

	 	if(derivar!="" && gestor!="" && fec!=""){
	 		document.getElementById("loading").style.display = "block" ;

	 		$.ajax({
			    type: 'POST',
			    url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Denuncia/DerivarParteSelec&derivar='?>"+derivar+'&gestor='+gestor+'&activ='+activ+'&fec='+fec,
			    dataType:"json",
			
					success: function(data) {

						 if(data.status === "success") {

							swal({
							  title: "Partes derivados con éxito",
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
	 	else alert("Debe seleccionar un gestor y minímo 1 parte");

    }//FIN FUNCION ELIMINAR	
</script>