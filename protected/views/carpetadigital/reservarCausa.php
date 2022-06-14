<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<script src='../start/js/validar_ruc.js' ></script>

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Reserva de causa</div><div class="subtitulo"></div>
		<div class="subtitulo">Permite reservar información confidencial de las causa e indicar que funcionarios tienen permiso de lectura.</div>
	</div>

	<div id="loading" style="display: none;">
	 	<img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
    </div>

	<div class="col-md-6">	

	    <div class="row-inline-rev">
			<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
			<input type="text" id="ruc" name="ruc" onchange="validacionRuc(this.value)" autofocus value="<?php if(isset($ruc)) echo $ruc; ?>" />
		</div>

		<div class="col-md-7" >
			<label style="font-weight: bolder;font-size: 14px;">Selección de Funcionarios</label>
			<select name="fun" id="fun" class="chosen_fun" required="required"  >
				<option value="">Seleccionar Funcionario</option>
				<?php 
				foreach ($fun as $c => $value) {	   									    	
					echo '<option value="'.$fun[$c]->fun_rut .'">'. $fun[$c]->fun_ap_paterno.' '.$fun[$c]->fun_nombre.' '.$fun[$c]->fun_nombre2 .'</option>';
				}
				?>
			</select>
			<div id="select_fun"></div>
		</div>			

		<div class="col-md-12" style="margin-top: 20px;">
			<button id="btn_control" name="guardar" class="btn btn-primary" onclick="GuardarDatos()">Reservar Causa</button>
		</div>
    

	</div><!--fin div 4-->

    <div class="col-md-6">
    	<div id="tabla">
    		
    	</div>
    </div>


</div>

<script type="text/javascript">
	$(".chosen_fun").chosen();
</script>

<script type="text/javascript">
	function listarFuncionarios(){
		urlfun = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ListarSeleccionFuncionario'?>";

		$.ajax({
		      url: urlfun,
		      type: 'POST',
		      dataType: "json",
		      success: function(result){  
		          if(result.status === "success") {
		              $('#select_fun').empty();
		              $('#select_fun').append(result.message);   
		          }else alert("error");    
		      }//fin success
		});
	}
</script>

<script type="text/javascript">
	function listarPermisosReservados(){
		urlpermisos = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/ListarCausasReservadas'?>";

		$.ajax({
		      url: urlpermisos,
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
		listarFuncionarios();
		listarPermisosReservados();
		document.getElementById("loading").style.display = "none";

	});	
</script>

<script type="text/javascript">
	function validacionRuc($ruc){

		campo=$("#ruc").prop("value")
		if ( validaRuc(campo) ){	
			
		}
		else{		
			
		}

        
	}
</script>

<script type="text/javascript">
$( ".chosen_fun" ).change(function() {

		var fun = $("#fun").val(); 
		urlfun = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/GuardarSeleccionFuncionario'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
	        url: urlfun,
	        type: 'POST',
	        data: {fun: fun},
	        dataType: "json",
	        success: function(result){  
	            if(result.status === "success") {
	                listarFuncionarios(); 
	                document.getElementById("loading").style.display = "none";
	            }
	            else{
	                alert(result.message)      
	            }     
	        }//fin success
	    });
	    return false;
});
</script>


<script type="text/javascript">
	function elimfun(id){		
		urldel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/EliminarSeleccionFuncionario'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urldel,
		      type: 'POST',
		      data: {id: id},
		      dataType: "json",
		      success: function(result){  
		          if(result.status === "success") {
		            listarFuncionarios();
		            document.getElementById("loading").style.display = "none";
		          }else alert("error");    
		      }//fin success
		});
		return false;
	}
</script>

<script type="text/javascript">
	function elimfuntodo(id){		
		urldel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/EliminarSeleccionFuncionarioTodos'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urldel,
		      type: 'POST',
		      data: {id: id},
		      dataType: "json",
		      success: function(result){  
		          if(result.status === "success") {
		              listarFuncionarios();
		              document.getElementById("loading").style.display = "none"; 
		          }else alert("error");    
		      }//fin success
		});
		return false;
	}
</script>

<script type="text/javascript">
	function elimPermiso(id){

		var r = confirm("Estas segur@ que deseas eliminar los permisos del funcionario sobre causa reservada?");
		if (r == true) {
			urldel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/EliminarPermisosReservados'?>";
			document.getElementById("loading").style.display = "block";
			$.ajax({
			      url: urldel,
			      type: 'POST',
			      data: {id: id},
			      dataType: "json",
			      success: function(result){  
			          if(result.status === "success") {
			              listarPermisosReservados();
			              document.getElementById("loading").style.display = "none"; 
			          }else alert("error");    
			      }//fin success
			});
			return false;
		}	
		
	}
</script>


<script type="text/javascript">
 	function GuardarDatos(){
 		var ruc = $("#ruc").val(); 

		if ( validaRuc(ruc) ){

			urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/GuardarCausaReservada'?>";
			document.getElementById("loading").style.display = "block";
			$.ajax({
			      url: urlsave,
			      type: 'POST',
			      data: {ruc: ruc},
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