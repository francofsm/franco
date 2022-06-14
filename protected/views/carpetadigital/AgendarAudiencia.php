<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<div class="pagina">


	<div class="div-titulo">		
		<div class="titulo">Registrar RUC para Audiencias</div><div class="subtitulo"></div>
		<div class="subtitulo">Permite registrar el RUC y el tipo de audiencia para generar listado a Fiscal.</div>
	</div>


	<div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>



	<div class="col-md-6">

		<div style="margin-left: 35px;padding: 16px 85px;">
 		<form action="" id="formoid" method="post"	> 		

	     <div class="row-inline-rev">
			<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
			<input type="text" id="ruc" name="ruc" onchange="validacionRuc(this.value)" autofocus value="<?php if(isset($ruc)) echo $ruc; ?>" />
		</div>


		<div class="row-inline-normal">
			<label>Fecha Audiencia <span class="required">*</span></label>
			<input type="date" id="fec_audiencia" name="fec_audiencia" class="input-md" required="required" value="<?php echo date('Y-m-d'); ?>"></input>
			</div>

		<div class="row-inline-rev">
			<label>Tipo Audiencia</label>
			<select name="tipoaud" id="tipoaud">
			<?php 
				foreach ($tipaud as $c => $value) {	   									    	
					echo '<option value="'.$tipaud[$c]->cod_tipaudiencia .'">'. $tipaud[$c]->gls_tipaudiencia .'</option>';
				}
			?>
			</select>			
		</div>	

		<div class="row-inline-rev">
			<label>Sala Audiencia</label>
			<select name="salaaud" id="salaaud">
			<?php 
				foreach ($sala as $c => $value) {	   									    	
					echo '<option value="'.$sala[$c]->cod_salaaud .'">'. $sala[$c]->sala_aud .'</option>';
				}
			?>
			</select>			
		</div>	

		<div class="row-inline-rev">
			<label>Hora Audiencia</label>
			<select name="horaaud" id="horaaud">
			<?php 
				foreach ($hora as $c => $value) {	   									    	
					echo '<option value="'.$hora[$c]->cod_hora .'">'. $hora[$c]->hora .'</option>';
				}
			?>
			</select>			
		</div>	

		<div class="row-inline-rev">	
			<button id="btn_control" name="guardar" class="btn btn-primary">Guardar</button>
		</div>
    
    	</form>
    	</div>

	</div><!--fin div 4-->

    <div class="col-md-6">
    	<div id="tabla">
    		<h4>Listado de Audiencias Ingresadas. </h4><span class="glyphicon glyphicon-hand-left"></span>
    	</div>
    </div>


</div>

<script type="text/javascript">
	
	function elimDocAud(id){		
		urldel = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/EliminarAudienciaCargada'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urldel,
		      type: 'POST',
		      data: {id: id},
		      success: function(data){  
		     
		            urlver = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/VerListadoAudiencia'?>";
					$.ajax({
					    url: urlver,
					    type: 'POST',
					    success: function(result){ 
							$('#tabla').empty();
				    		$('#tabla').append(result); 				    		
					    }//fin success
					});//fin post tabla según gestor seleccionad
		            document.getElementById("loading").style.display = "none"; 
		           
		      }//fin success
		});
		return false;
	}
</script>

<script type="text/javascript">
	$(document).ready(function() {	
		document.getElementById("loading").style.display = "block";
		urlver = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/VerListadoAudiencia'?>";
		$.ajax({
		    url: urlver,
		    type: 'POST',
		    success: function(result){ 
				$('#tabla').empty();
	    		$('#tabla').append(result); 
	    		document.getElementById("loading").style.display = "none";
		    }//fin success
		});//fin post tabla según gestor seleccionad
	return false;
	});	
</script>

<script type="text/javascript">
	 $("#formoid").submit(function(event) {

	 	document.getElementById("loading").style.display = "block";

	 	var datastring = $("#formoid").serialize(); 
	 	urlsave = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/GuardarDatosAudiencia'?>";
	 	$.ajax({
	        url: urlsave,
	        type: 'POST',
	        data: datastring,
	        dataType: "json",
	        success: function(result){  
	            if(result.status === "success") {

		    		document.getElementById("ruc").value=""; 
		    		//document.getElementById("file").value="";
		    		//document.getElementById("adjunto1").value="";

					urlver = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CarpetaDigital/VerListadoAudiencia'?>";
					$.ajax({
					    url: urlver,
					    type: 'POST',
					    success: function(result){ 
							$('#tabla').empty();
				    		$('#tabla').append(result); 
				    		document.getElementById("loading").style.display = "none";
					    }//fin success
					});//fin post tabla según gestor seleccionad
	
	            }
	            else{
	            	alert(result.message)
	            }
	        }
	    });
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