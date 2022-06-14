<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Asignar Diligencias de la bolsa de trabajo</div>
		<div class="subtitulo">
			<?php echo $fi->fis_nombre;?>/ Equipo <?php  if(isset($tguni->uni_codigo)) echo $tguni->uni_descripcion; ?>
		</div>
	</div>


	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>     


<div class="tabla_min_asign" id="tabla_min">

	<table class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
				<th>Min. Asignado</th>
            </tr>
        </thead>
		<tbody>	
		<tr>
			<td id="min_asig">-</td>
		</tr>
		</tbody>
	</table>

</div>

<div class="div_asignacion">
	
		<div class="row-inline-medi">
			<label>FUNCIONARIO:</label>
			<select name="fun" id="fun" class="chosen_fun" >
				<option value="">Seleccionar Funcionario</option>
				<?php 
					foreach ($fun as $c => $value) {	   									    	
						echo '<option value="'.$fun[$c]->fun_rut .'">'. $fun[$c]->fun_ap_paterno.' '.$fun[$c]->fun_nombre.' '.$fun[$c]->fun_nombre2 .'</option>';
					}
				?>
			</select>
		</div>

	<div class="row-inline-rev">
			<label>Fecha Plazo<span class="required">*</span></label>
			<input type="date" id="fec" name="fec" value="<?php echo date('Y-m-d'); ?>"></input>
	</div>

     <div style="width: 250px;">
		<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
		<input type="text" id="ruc" name="ruc" onkeyup = "if(event.keyCode == 13) enterRuc(this.value)" autofocus/>
	</div>


	<div style="margin-top: -40px;margin-left: 290px;">
	    <label  style="font-weight: bolder;font-size: 14px;">Incluir Mov. de Carpeta</label>
	    <input type="checkbox" name="carp" id="carp">	                
	</div>

</div>


	<div class="btn-parte-pendiente">
	
		<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/ReportePlazo'?>" target="_BLANK">
		<p  class="button_reporte_comparativo" style="margin-left: -40px;margin-top: -60px;margin-bottom: 20px;">Reporte Comparativo por Unidad</p></a> 


		<button id="btn_control" name="asignar" class="btn btn-primary" onclick="asignar()">Asignar</button>


	</div>

	<div>
		<h3>Filtro Bolsa de Trabajo</h3>

		<div class="row-inline-medi">
			<label>MES DECRETA:</label>
			<select name="mes" id="mes" onchange="consultar(event);" >
				<?php 
				$array_mes = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

					foreach ($meses as $c => $value) {	   									    	
						echo '<option value="'.$meses[$c]->mes_decreta .'">'. $array_mes[$meses[$c]->mes_decreta].'</option>';
					}
				?>
			</select>
		</div>

		<div class="row-inline-medi">
			<label>EQUIPO:</label>
			<select name="equipo" id="equipo" onchange="consultar(event);" >
			<!--<option value="0">No registra equipo</option>-->
				<?php 
					foreach ($uni as $c => $value) {	   									    	
						echo '<option value="'.$uni[$c]->uni_codigo .'">'. $uni[$c]->uni_descripcion.'</option>';
					}
				?>
			</select>
		</div>

	</div>
	<div id="tareas" style="padding: 10px;">
	


	</div>

</div>

<script type="text/javascript">
	$(".chosen_fun").chosen();
</script>

<script type="text/javascript">
	function consultar(e){

	document.getElementById("loading").style.display = "block" ;

	mes=$("#mes").prop("value");
	uni=$("#equipo").prop("value");

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligencias&mes='?>"+mes+'&uni='+uni,
	    $.ajax({
		    url: urltable,
		    type: 'POST',
		    success: function(result){  
		        document.getElementById("loading").style.display = "none" ;           
		        $('#tareas').empty();
	    		$('#tareas').append(result); 
		             
		    }//fin success
		});//fin post tabla según gestor seleccionado

	return false;
 }
</script>

<script type="text/javascript">
	function minasing(){

		var suma=0;
        j=1;

		$("#listardil tr").find('td:eq(4)').each(function () {	

		 	if(document.getElementById("asignar["+j+"]").checked == true){		 
		 		tiempo = $(this).html();	
		 		suma = parseFloat(suma)  + parseFloat(tiempo);	 
		 	}
		 	j++;

	 	})


	 	$('#min_asig').empty();
	    $('#min_asig').append(suma); 

	}
</script>

<script type="text/javascript">
	$(document).ready(function() {

	mes=$("#mes").prop("value");
	uni=$("#equipo").prop("value");

	

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligencias&mes='?>"+mes+'&uni='+uni,
	    $.ajax({
		    url: urltable,
		    type: 'POST',
		    success: function(result){  
		                      
		        $('#tareas').empty();
	    		$('#tareas').append(result); 

	    		$('#min_asig').empty();
	    		$('#min_asig').append("-"); 
	    		
		             
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
        $("#listardil tr").find('td:eq(1)').each(function () {
 		
               codigo = $(this).html();
               if(codigo==buscar){                   
                    trDelResultado=$(this).parent();                    
                    ruc=trDelResultado.find("td:eq(1)").html();              
                  	document.getElementById("asignar["+i+"]").checked = true;
                    encontradoResultado=true;
 		       }//fin buscar==codigo
 		     
 		       $("#ruc").val(""); 		       
 		i++;
        })


		var suma=0;
        j=1;

		$("#listardil tr").find('td:eq(4)').each(function () {	

		 	if(document.getElementById("asignar["+j+"]").checked == true){		 
		 		tiempo = $(this).html();	
		 		suma = parseFloat(suma)  + parseFloat(tiempo);	 
		 	}
		 	j++;

	 	})


	 	$('#min_asig').empty();
	    $('#min_asig').append(suma); 

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
	function MarcarTodosElim(source) {
        var aInputs = document.getElementsByTagName('input');
        for (var i=0;i<aInputs.length;i++) {
            if (aInputs[i] != source && aInputs[i].className == source.className) {
                aInputs[i].checked = source.checked;
            }
        }
    }
    
</script>

 <script type="text/javascript">
    function asignar(){

    	var asignar=[]; 
    	var checkedValue = null;  
		var inputElements = document.getElementsByClassName('checAsignar');

		for(var i=0; inputElements[i]; ++i){
		      if(inputElements[i].checked){
		           checkedValue = inputElements[i].value;
		           asignar[i]=checkedValue;		           
		      }
		}//FIN FOR

		var fun = $("#fun").val();
		var fec = $("#fec").change({ dateFormat: "yyyy/mm/dd" }).val();

		if( document.getElementById("carp").checked == true ){
			var carp = 1;
		}
		else{
			var carp = 0;
		}



	 	if(asignar!="" && fun!="" && fec!=""){
	 		document.getElementById("loading").style.display = "block" ;

	 		$.ajax({
			type: 'POST',
			url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/GuardarAsignacion&asignar='?>"+asignar+'&fun='+fun+'&fec='+fec+'&carp='+carp,
			dataType: "json",
			  	success: function(data) {
			   		
			   		if(data.status === "success") {

			   			mes=$("#mes").prop("value");
						uni=$("#equipo").prop("value");

						urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Centralizado/ListarDiligencias&mes='?>"+mes+'&uni='+uni,
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
			   			
			   		}
			   		else{
			   			document.getElementById("loading").style.display = "none" ;
			   			alert(data.message);
			   		}		    		
				}//FIN SUCCESS					
			});
			return false;
	 	}
	 	else{
	 		alert("Debes seleccionar un funcionario y fecha de asignación");
	 	}
    }//FIN FUNCION ELIMINAR	
</script>