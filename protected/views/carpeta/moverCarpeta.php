<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>


<script type="text/javascript">
$(document).ready(function() {
  $("input[type=text]").focus().select();
});
</script>


<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Movimiento de Carpeta</div><div class="subtitulo">Indica que entregarás la carpeta a otro funcionario y la guardarás en bodega</div>
	</div>

	<div class="div-form-uno">

			<form id="fomover" class="form-horizontal"  method="post" name="fomover">
			<center> 
			

			<div class="row-inline-normal">
				<label>Tipo Ubicación</label>
				<select id="tipubicacion" name="tipubicacion" class="input-md">
					<option value="">Seleccionar Tipo Bodega</option>	
				<?php 
				foreach ($tipu as $tipu) { ?>
					<option value="<?php echo $tipu->cod_tipubicacion; ?>"><?php echo $tipu->gls_tipubicacion; ?></option><?php
				} 
				?>
				</select>
			</div>


			<div class="row-inline-normal">
				<label>Ubicación</label>
				<select id="ubicacion" name="ubicacion" class="input-md">
					<option value="">Seleccionar Ubicación</option>	
				
				</select>
			</div>

			<div class="row-inline-normal">
				<label>Casillero</label>
				<select id="casillero" name="casillero" class="input-md">
					<option value="">Seleccionar Casillero</option>	
				<?php 
				foreach ($cas as $cas) { ?>
					<option value="<?php echo $cas->cod_casillero; ?>"><?php echo $cas->gls_casillero; ?></option><?php
				} 
				?>
				</select>
			</div>

			<div class="row-inline-normal">
				<label>Observaciones</label>
				<textarea style="height: 55px;" name="obs" id="obs"></textarea>
			</div>

			<div class="row-inline-normal">
				<label>Ruc <span class="required">*</span></label>
				<input type="text" id="ruc" name="ruc" class="input-md required ruc" oninput="checkRut(this)"  maxlength="12" minlength="12" placeholder="Ingrese RUC" required="" />
			</div>
			</center>

			<center>
			<div style="margin-top: 8px;margin-bottom: 17px;">
				<button type="submit" class="btn btn-success" title="Agregar Registros">Realizar Movimiento</button>
			</div>
			</center>

			<div id="error_msj" style="display: none;margin-left: auto;text-align: right;width: 173px;margin-right: 200px;"></div>

			</form>
		
		<div id="result"></div>
	</div>


	<div id="loading" style="display: none;">
		<img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading2.gif" alt="Loading..." />
	</div> 

	<div class="btn-limpiar-mov">
		<button id="btn_recepcion" name="ejecutar" class="btn btn-info" onclick="limpiar()">Limpiar Tabla de Movimientos</button>
	</div>
	
    <div id="historia">

    </div>	

</div>


<script type="text/javascript">
	
$(document).ready(function() {
       

    mimov = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/ListMoverCarpeta'?>";
    $.ajax({
        type: 'POST',
        url: mimov,            
        success: function(data) {						
	     	$('#historia').empty();
			$('#historia').append(data); 
		}
    });


    	    //tipo_ubicacion 
	    $("#tipubicacion").change(function () {

	    	miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/Categoria'?>";

	    	var tipubicacion = $(this).val();
	    	$.ajax({
	    		url: miurl,
	    		type: 'POST',
	    		data: {valor: tipubicacion},
	    		success: function(result){
	    			$("#ubicacion").html(result);
	    		}
	    	});
	    }); 

});
</script>

<script type="text/javascript">
	function limpiar(){
		mirlim = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/LimpiarMovimientos'?>";
        $.ajax({
            type: 'POST',
            url: mirlim,            
            success: function(data) {						
	            
            	mirece = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/ListRecepcionCarpeta'?>";
		        $.ajax({
		            type: 'POST',
		            url: mirece,
		            data: $(this).serialize(),	            
		            success: function(data) {						
			            
		            	$('#historia').empty();
			   			$('#historia').append(data); 

		            }
		        });

            }//fin succes
        });
	}
</script>

<script type="text/javascript">
	function elimMov(id){		
		urlelim = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/EliminarMovimiento'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urlelim,
		      type: 'POST',
		      data: {id: id},
		      success: function(result){  

		        mimov = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/ListMoverCarpeta'?>";
		        $.ajax({
		            type: 'POST',
		            url: mimov,		                    
		            success: function(data) {						
			            
		            	$('#historia').empty();
			   			$('#historia').append(data); 
			   			document.getElementById("loading").style.display = "none";
		            }
		        });
				      
		      }//fin success
		});
		return false;
	}
</script>

<script type="text/javascript">
	$('#fomover').submit(function() {
		var campo = $("#ruc").val(); 

		if ( validaRuc(campo) ){
			document.getElementById("error_msj").style.display = "none";
			document.getElementById('loading').style.display= 'block';
		    miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/GuardarMovimientoCarpeta'?>";
			$.ajax({
		            type: 'POST',
		            url: miurl,
		            data: $(this).serialize(),	 
		            dataType: "json",           
		            success: function(result) {						
		            
		            	if(result.status === "success") {
			                mirece = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/ListMoverCarpeta'?>";
					        $.ajax({
					            type: 'POST',
					            url: mirece,	            
					            success: function(data) {						
						            
					            	$('#historia').empty();
						   			$('#historia').append(data); 
						   			document.getElementById("loading").style.display = "none";
			                		document.getElementById('ruc').value="";

					            }
					        });
			                
			            }
			            else{
			                alert(result.message); 
			                document.getElementById('loading').style.display= 'none';
			            }  
			    	 		    	 
		            }
		    })        
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
				alert("El valor ingresado no corresponde a un RUC válido");			
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
		alert("Debe ingresar un digito verificador válido");		
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

