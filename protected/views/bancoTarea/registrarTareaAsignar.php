<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">


<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Decretar y Asignar Diligencias/Tareas</div>
		<div class="subtitulo">
			<?php echo $fi->fis_nombre;?>/ Equipo <?php  if(isset($tguni->uni_codigo)) echo $tguni->uni_descripcion; ?>
		</div>
	</div>

	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>

     <div class="col-md-3"> 
     	<div class="form-tvwork">
  		<form action="" id="formoid" method="post"	>	

		<div class="row-inline-medi">
			<label>FUNCIONARIO:</label>
			<select name="fun" id="fun" class="chosen_fun" required="required" >
				<option value="">Seleccionar Funcionario</option>
				<?php 
					foreach ($fun as $c => $value) {	   									    	
						echo '<option value="'.$fun[$c]->fun_rut .'">'. $fun[$c]->fun_ap_paterno.' '.$fun[$c]->fun_nombre.' '.$fun[$c]->fun_nombre2 .'</option>';
					}
				?>
			</select>
		</div><br><br>

		<div class="row-inline-medi">
			<label>Diligencias/Tareas:</label>
			<select name="tarea" id="tarea" class="chosen_dil" >
				<option value="">Seleccionar Tarea</option>
				<?php 
					foreach ($tareas as $c => $value) {	   									    	
						echo '<option value="'.$tareas[$c]->cod_instruccion .'">'. $tareas[$c]->gls_instruccion .' ( '.$tareas[$c]->tiempo_instruccion.' min.)</option>';
					}
				?>
			</select>
			<div id="select_dil"></div>
		</div>	

		<br><br><br>
		<div class="row-inline-rev">
			<label>Fecha Plazo<span class="required">*</span></label>
			<input type="date" id="fec_asig" name="fec_asig" value="<?php echo date('Y-m-d'); ?>"></input>
		</div><br>

		<div class="row-inline-medi">
			<label>Observaciones</label>
			<textarea style="height: 55px;" name="obs" id="obs"></textarea>
		</div><br>

		<div class="row-inline-medi">
			<label>Priorización:</label>
			<select name="priori" id="priori">
				<option value="1">Baja</option>
				<option value="2">Media</option>
				<option value="3">Alta</option>
			</select>
		</div><br>

		<div class="row-inline-medi">
	        <label  style="font-weight: bolder;font-size: 14px;">Incluir Mov. de Carpeta</label>
	        <input type="checkbox" name="carp" id="carp">	                
	     </div><br>

		<div class="row-inline-medi">
			<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
			<input type="text" id="ruc" name="ruc" maxlength="12" minlength="12"  autofocus value="<?php if(isset($ruc_decreta)) echo $ruc_decreta; ?>"  />
		</div>
		

		<div class="row-inline-rev">
			<input type="submit" id="submitButton"  name="submitButton" class="btn btn-info" value="Guardar Instrucción">
		</div>

		<div id="error_msj" style="display: none"></div>

		</form>
		</div><!--fin div tvwork-->
     </div>


     <div class="col-md-7"> 

     	<a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Reporte/ReportePlazo'?>" target="_BLANK">
		<p class="button_reporte_comparativo">Reporte Comparativo por Unidad</p></a> 
		<div id="tareas" style="padding: 10px;    position: relative;">
		
			
		</div>

	
     </div>	

</div>


<script type="text/javascript">
	$(".chosen_fun").chosen();
	$(".chosen_dil").chosen();
</script>

<script type="text/javascript">
	$(document).ready(function() {

	urldil = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/SeleccionTarea'?>";

	$.ajax({
	      url: urldil,
	      type: 'POST',
	      dataType: "json",
	      success: function(result){  
	          if(result.status === "success") {
	              $('#select_dil').empty();
	              $('#select_dil').append(result.message);   
	          }else alert("error");    
	      }//fin success
	});

	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarTareaAsignada'?>";
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
	function elimdil(id){		
		urldel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/EliminarSeleccionTarea'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urldel,
		      type: 'POST',
		      data: {id: id},
		      dataType: "json",
		      success: function(result){  
		          if(result.status === "success") {
		              $('#select_dil').empty();
		              $('#select_dil').append(result.message);  
		              document.getElementById("loading").style.display = "none"; 
		          }else alert("error");    
		      }//fin success
		});
		return false;
	}
</script>

<script type="text/javascript">
	function elimdiltodo(id){		
		urldel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/EliminarSeleccionTareaTodos'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urldel,
		      type: 'POST',
		      data: {id: id},
		      dataType: "json",
		      success: function(result){  
		          if(result.status === "success") {
		              $('#select_dil').empty();
		              $('#select_dil').append(result.message);  
		              document.getElementById("loading").style.display = "none"; 
		          }else alert("error");    
		      }//fin success
		});
		return false;
	}
</script>

<script type="text/javascript">
	function elimTarea(id){		
		urlelim = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/EliminarTarea'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urlelim,
		      type: 'POST',
		      data: {id: id},
		      success: function(result){  

		        urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarTareaAsignada'?>";
				    $.ajax({
					    url: urltable,
					    type: 'POST',
					    success: function(result){  
					        document.getElementById("loading").style.display = "none";              
					        $('#tareas').empty();
				    		$('#tareas').append(result);   
				    		
					             
					    }//fin success
					});//fin post tabla según gestor seleccionado
				      
		      }//fin success
		});
		return false;
	}
</script>

<script type="text/javascript">
$( ".chosen_dil" ).change(function() {

		var tarea = $("#tarea").val(); 
		urltarea = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/GuardarSeleccionTarea'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
	        url: urltarea,
	        type: 'POST',
	        data: {tarea: tarea},
	        dataType: "json",
	        success: function(result){  
	            if(result.status === "success") {
	                $('#select_dil').empty();
	                $('#select_dil').append(result.message);   
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
 $("#formoid").submit(function(event) {
    
    document.getElementById("error_msj").style.display = "none";


		var campo = $("#ruc").val(); 

		if ( validaRuc(campo) ){

			
		
		var datastring = $("#formoid").serialize(); 
    	document.getElementById("loading").style.display = "block";
    	urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/GuardarTareaAsignar'?>";

		$.ajax({
	        url: urlsave,
	        type: 'POST',
	        data: datastring,
	        dataType: "json",
	        success: function(data){  
	            if(data.status === "success") {	    
 					


	              	document.getElementById("error_msj").style.display = "none";
	              	urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=BancoTarea/ListarTareaAsignada'?>";
				    $.ajax({
					    url: urltable,
					    type: 'POST',
					    success: function(result){  
					        document.getElementById("loading").style.display = "none";              
					        $('#tareas').empty();
				    		$('#tareas').append(result);   
				    		
					             
					    }//fin success
					});//fin post tabla según gestor seleccionado

				    
				    document.getElementById("ruc").value=""; 
				    document.getElementById("obs").value=""; 
				
				    //document.getElementById("carp").checked = false;
					document.getElementById("ruc").focus(); 
					document.getElementById("loading").style.display = "none";
	            }//fin succes
	            else{
	            	document.getElementById("loading").style.display = "none";
					document.getElementById("error_msj").style.display = "block";
			    	 $('#error_msj').empty();
			         $('#error_msj').append(data.message);             
	            }     
	        }//fin success
	    });
	    return false;


		}	
		else{
			$('#error_msj').empty();
         	$('#error_msj').append("Error, RUC inválido");  
		}

		return false;
	});
</script>

<script type="text/javascript">
	function validaRuc(campo){
		texto= campo;
		var tmpstr = "";	
		for ( i=0; i < texto.length ; i++ )		
			if ( texto.charAt(i) != ' ' && texto.charAt(i) != '.' && texto.charAt(i) != '-' )
				tmpstr = tmpstr + texto.charAt(i);	
		texto = tmpstr;	
		largo = texto.length;	
		//Limitamos a un 11 sin digitos
		if ( largo < 11 )	
		{		
			alert("Debe ingresar el RUC completo")		
			return false;	
		}	

		for (i=0; i < largo ; i++ )	
		{			
			if ( texto.charAt(i) !="0" && texto.charAt(i) != "1" && texto.charAt(i) !="2" && texto.charAt(i) != "3" && texto.charAt(i) != "4" && texto.charAt(i) !="5" && texto.charAt(i) != "6" && texto.charAt(i) != "7" && texto.charAt(i) !="8" && texto.charAt(i) != "9" && texto.charAt(i) !="k" && texto.charAt(i) != "K" )
	 		{			
				alert("El valor ingresado no corresponde a un RUC valido");			
				//campo.focus();			
				//campo.select();			
				return false;		
			}	
		}	


		var invertido = "";	
		for ( i=(largo-1),j=0; i>=0; i--,j++ )		
			invertido = invertido + texto.charAt(i);	
		var dtexto = "";	
		dtexto = dtexto + invertido.charAt(0);	
		dtexto = dtexto + '-';	
		cnt = 0;	

		for ( i=1,j=2; i<largo; i++,j++ )	
		{		
			//alert("i=[" + i + "] j=[" + j +"]" );		
			if ( cnt == 3 )		
			{			
				//dtexto = dtexto + '.';			
				j++;			
				dtexto = dtexto + invertido.charAt(i);			
				cnt = 1;		
			}		
			else		
			{				
				dtexto = dtexto + invertido.charAt(i);			
				cnt++;		
			}	
		}	

		invertido = "";	
		for ( i=(dtexto.length-1),j=0; i>=0; i--,j++ )		
			invertido = invertido + dtexto.charAt(i);	

		campo = invertido.toUpperCase()		

		if ( revisarDigito_ruc2(campo, texto) ){
			return true;	

		}		
			

		return false;
	}
</script>


<script type="text/javascript">
function revisarDigito_ruc( dvr, campo )
{	
	dv = dvr + ""	
	if ( dv != '0' && dv != '1' && dv != '2' && dv != '3' && dv != '4' && dv != '5' && dv != '6' && dv != '7' && dv != '8' && dv != '9' && dv != 'k'  && dv != 'K')	
	{		
		alert("Debe ingresar un digito verificador valido");		
		//campo.focus();		
		//campo.select();		
		return false;	
	}	
	return true;
}

function revisarDigito_ruc2( campo, crut )
{	
	
	largo = crut.length;	
	if ( largo < 2 )	
	{		
		alert("Debe ingresar el RUC completo")		
		//campo.focus();		
		//campo.select();		
		return false;	
	}	
	if ( largo > 2 )		
		rut = crut.substring(0, largo - 1);	
	else		
		rut = crut.charAt(0);	
	dv = crut.charAt(largo-1);	
	revisarDigito_ruc( dv, campo);	

	if ( rut == null || dv == null )
		return 0	

	var dvr = '0'	
	suma = 0	
	mul  = 2	

	for (i= rut.length -1 ; i >= 0; i--)	
	{	
		suma = suma + rut.charAt(i) * mul		
		if (mul == 7)			
			mul = 2		
		else    			
			mul++	
	}	
	res = suma % 11	
	if (res==1)		
		dvr = 'k'	
	else if (res==0)		
		dvr = '0'	
	else	
	{		
		dvi = 11-res		
		dvr = dvi + ""	
	}
	if ( dvr != dv.toLowerCase() )	
	{		
		alert("EL RUC es incorrecto")		
		//campo.focus();		
		//campo.select();		
		return false	
	}

	return true
}

</script>