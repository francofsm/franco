<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.Rut.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/carga_select.js"></script>

<script type="text/javascript">
$(document).ready(function() {
  $("input[type=text]").focus().select();
});
</script>


<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Transferir Carpeta</div><div class="subtitulo">Carpetas Transferidas</div>
	</div>

	<div class="div-form-uno">

			<form id="fomover" class="form-horizontal"  method="post" name="fomover">
			<center> 
			

		<div class="row-inline-normal">
			<label>Fiscalía <span class="required">*</span></label>
			<select id="fiscalia" name="fiscalia" class="input-md" required="">
				<option value="">Seleccionar Fiscalía</option>	
			<?php 
			foreach ($fis as $fis) { ?>
				<option value="<?php echo $fis->fis_codigo; ?>"><?php echo $fis->fis_nombre; ?></option><?php
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
				<button type="submit" class="btn btn-success" title="Agregar Registros">Transferir</button>
			</div>
			</center>

			<div id="error_msj" style="display: none;margin-left: auto;text-align: right;width: 173px;margin-right: 200px;"></div>

			</form>
		
		<div id="result"></div>
	</div>


	<div id="loading" style="display: none;">
		<img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading2.gif" alt="Loading..." />
	</div> 

	
    <div id="historia">

    </div>	

</div>


<script type="text/javascript">
	function elimMov(id){		
		urlelim = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/EliminarMovimiento'?>";
		document.getElementById("loading").style.display = "block";
		$.ajax({
		      url: urlelim,
		      type: 'POST',
		      data: {id: id},
		      success: function(result){  

		        mimov = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/ListTransferirCarpeta'?>";
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
	
	$(document).ready(function() {
       

    	mimov = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/ListTransferirCarpeta'?>";
        $.ajax({
            type: 'POST',
            url: mimov,            
            success: function(data) {						
	            
            	$('#historia').empty();
	   			$('#historia').append(data); 

            }
        });

	   // Interceptamos el evento submit
	    $('#fomover').submit(function() {

	    	document.getElementById("error_msj").style.display = "none";

	    	comprueba=0;
	    	bien=0;
 			var rut = $("#ruc").val(); 


	    	var fiscalia = $("#fiscalia").val(); 
	    	if(fiscalia==""){
		    	 document.getElementById("error_msj").style.display = "block";
		    	 $('#error_msj').empty();
		         $('#error_msj').append("Error, debe indicar una fiscalía");     
		  
		    }

			for(i = 0; i < rut.length; i++){

				var vector = ["1","2","3","4","5","6","7","8","9","0","k","K","-"];
				var n = vector.includes(rut[i]);
				if(n==false){
					comprueba=1;
				} 	

				if(rut[0] == "0" || rut[0]  ==  "1" || rut[0]  ==  "2"){
					bien=0;
				}
				else{
					comprueba=1;
				}


				if( rut[10] == "-" ){
					bien=0;
				}
				else{
					comprueba=1;
				}
				

				if( rut[11] == "-" ){
					comprueba=1;
				}


			}

			if(rut.length < 12){
				document.getElementById("error_msj").style.display = "block";
    	 		$('#error_msj').empty();
         		$('#error_msj').append("Error, RUC incompleto");    
         		return false;
			}

			else if(comprueba==1){
				document.getElementById("error_msj").style.display = "block";
    	 		$('#error_msj').empty();
         		$('#error_msj').append("Error, RUC inválido");    
         		return false;
			}
			else{


	    	document.getElementById('loading').style.display= 'block';
	    	miurl = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/GuardarTransferenciaCarpeta'?>";
	        $.ajax({
	            type: 'POST',
	            url: miurl,
	            data: $(this).serialize(),	 
	            dataType: "json",           
	            success: function(result) {						
	            
	            	if(result.status === "success") {
		                mirece = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/ListTransferirCarpeta'?>";
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



	})	 	
</script>

