<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<div class="pagina">

	<div class="div-titulo">		
		<div class="titulo">Consultar Ubicación en bodega de carpeta terminada</div><div class="subtitulo">Indica la fecha de destrucción de la causa.</div>
	</div>

	 <div id="loading" style="display: none;">
	 <img id="loading-image" src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" alt="Loading..." />
     </div>    

     <div class="text-center">
 
	     <div class="row-inline-rev">
			<label style="font-weight: bolder;font-size: 14px;">RUC:</label> 
			<input type="text" id="ruc" name="ruc" onkeyup = "if(event.keyCode == 13) enterRuc(this.value)" autofocus/>
		</div><br>

		 <div class="row-inline-rev">	
			<button id="btn_control" name="asignar" class="btn btn-primary" onclick="enterRuc(this.value)">Consultar</button>
		</div>
    	
     </div>


		<div id="historia" style="padding: 10px;">
		<?php
		if( isset($_POST['ruc'] )){
			echo "test";
		}
		?>

		
		</div>

</div>


<script type="text/javascript">
function enterRuc($ruc){
        ruc=$("#ruc").prop("value")
        ruc = ruc.toUpperCase();

		urltable = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/ListarHistoriaCarpeta'?>";
		$.ajax({
		    url: urltable,
		    type: 'POST',
		    data: {ruc: ruc},
		    success: function(result){  
					                      
		        $('#historia').empty();
		   		$('#historia').append(result); 
		        document.getElementById("loading").style.display = "none" ;   
		        document.getElementById("ruc").value=""; 
				document.getElementById("ruc").focus();  
		    }//fin success
		});//fin post tabla según gestor seleccionad
		return false;
}
</script>


<script type="text/javascript">
	function elimMov(id){		


	document.getElementById("loading").style.display = "block";


		urlelim = "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Carpeta/EliminarMovimiento'?>";
		$.ajax({
		    url: urlelim,
		    type: 'POST',
		    data: {id: id},
		    success: function(data){ 

				  document.getElementById("loading").style.display = "none" ;   
				  location.reload();

		       
		    }//fin success
		});//fin post tabla según gestor seleccionad
		return false;


	}
</script>