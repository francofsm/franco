<?php

date_default_timezone_set('America/Santiago'); 

class CorrespondenciaController extends Controller{

	public $layout='//layouts/column2';

	public function filters(){
		return array('accessControl', 'postOnly + delete',);
	}


	public function actionRecepDocAct(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
    
			$this->render('RecepDocAct');	     
	    }//FIN ELSE
	}//FIN FUNCION INDEX

	////////////// guardar documentos

	public function actionUploadInforme($ruc){
		
		$count = count($_FILES['arr']['name']); // arr from fd.append('arr[]')
		$var="";
		$flag=false;

		if ( $count == 0 ) {
		   echo 'Error: ' . $_FILES['arr']['error'][0] . '<br>';
		}
		else {

			$array [] = "";
			$id [] = "";
		    $i = 0;
		 
		    for ($i = 0; $i < $count; $i++) { 

		    	$fecha=date('Y-m-d');

		    	$preruta = "\\\\172.17.123.21\\temp_carpeta_digital\\informe\\".$fecha; 

		    	if(!file_exists($preruta)){		    		
		    		mkdir($preruta, 0777);
		    	}

		    	$nombre=$_FILES['arr']['name'][$i];
		    	$ruta=$preruta.'\\' .$nombre;    	
		    	
		    	if(file_exists($ruta)){

		    		$ruta=utf8_decode($ruta);	

			        move_uploaded_file($_FILES['arr']['tmp_name'][$i], $ruta);
			        $var=$ruta;

		    		echo json_encode(array(
					    'status' => 'error',
					    'message'=> $var
					));
		    	}	
		    	else{
		    		$ruta=utf8_decode($ruta);	

			        move_uploaded_file($_FILES['arr']['tmp_name'][$i], $ruta);
			        $var=$ruta;

			        echo json_encode(array(
						'status' => 'success',
						'message'=> $var
					));
		    	}
		        
		        	       	
				
		    }//fin for
		}
	}//FIN FUNCTION

	////////////// guardar registros

	public function actionGuardarCorrespondencia(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new SafRecepdoc;
	    	$flag=false;	    
	        $transaction=$model->dbConnection->beginTransaction();   
	        try{  

	        	if( empty($_POST['ruc']) ) throw new Exception('Error, debes indicar un ruc válido');
	        	if( empty($_POST['fec_actividad']) ) throw new Exception('Error, debes indicar una fecha de actividad');
	        	if( empty($_POST['rucrel']) ) throw new Exception('Error, debes indicar un sujeto');
	        	if( empty($_POST['fiscal']) ) throw new Exception('Error, debes indicar fiscal asignado');
	        	if( empty($_POST['idrelacion']) ) throw new Exception('Error, no se obtuvo el id relación.');
	        	if( empty($_POST['adjunto1']) ) throw new Exception('Error, debe adjuntar un documento válido');

				$model->idf_rolunico=$_POST['ruc'];
				$model->fis_codigo=$_POST['fiscalia'];
				$model->fis_usuario=Yii::app()->user->getState('fiscalia');
				$model->fun_rut=Yii::app()->user->id;
				$model->crr_sujeto=$_POST['rucrel'];
				$model->sujeto=$_POST['rucrel'];
				$model->crr_relacion=$_POST['idrelacion'];
				$model->idf_rutfiscal=$_POST['fiscal'];
				$model->fec_actividad=$_POST['fec_actividad'];
				if(isset($_POST['comentario'])){
					$model->gls_comentario=$_POST['comentario'];
				}
				$model->gls_rutatemp = $_POST['adjunto1'];
				$model->fec_registro=date('Y-m-d h:m:s'); 
				$model->estado_caso=$_POST['estado_caso'];

				$model->estado=0;
				//estados 0: ingresado, 1: registrado en saf, 2: anulado

				if($model->save()){
					$flag=true;
				}
				else throw new Exception('Error al guardar la correspondencia');	
	        	$transaction->commit();
	        }//FIN TRY
		    catch(Exception $e){ //en este bloque se accede si se ha producido una exception en el bloque try
				 	echo json_encode(array(
					    'status' => 'error',
					    'message'=> $e->getMessage()
					));
				    $transaction->rollBack();
				    $flag=false;  
			} 

			if($flag==true){
				echo json_encode(array(
					'status' => 'success',
					'message'=> 'Correspondencia guardada con éxito'
				));
			}

	    }//FIN ELSE
	}//FIN FUNCION GUARDAR


/////////////////// LISTADO DE DOCUMENTOS INGRESADOS     ///////////////

	public function actionListadoDoc(){
	
		$instruccion = SafRecepDoc::model()->getListadoDoc(); 

		echo "<table id='listadoc' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
                <th>N°</th>
                <th>RUC</th>
                <th>FISCAL</th>
                <th>COMENTARIOS</th>
                <th>FECHA RECEP. DOC</th>
                <th>FECHA REGISTRO</th>
                <th>ESTADO CASO</th>
                <th>ELIMINAR</th>
            </tr>
        </thead>";

		echo "<tbody>";
		
		$n=1; $i=1;
		foreach ($instruccion as $listar) {
			
			echo "<tr class='carpetas'>";
			echo "<td>".$n."</td>";
			echo "<td>".$listar['idf_rolunico']."</td>";	

			if($listar['fiscal'] == "") echo "<td>".$listar['idf_rutfiscal']."</td>";
			else echo "<td>".$listar['fiscal']."</td>";
	
			echo "<td>".$listar['gls_comentario']."</td>";
			echo "<td>".date("d-m-Y", strtotime($listar['fec_actividad']))."</td>";
			echo "<td>".date("d-m-Y", strtotime($listar['fec_registro']))."</td>";
			echo "<td>".$listar['estado_caso']."</td>";

			$id = $listar['cod_safrecepdoc'];
			echo "<td> <span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimDoc(this.id)'>X</span></td>";

			echo "</tr>";
			$n++;
		}
		
		echo "</tbody>"; 
		echo "</table>";


		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listadoc').DataTable( {
					'ordering': false,
					'language': {
						'lengthMenu': 'Registros por página _MENU_ ',
						'zeroRecords': 'No se han encontrado resultados',
						'info': 'Mostrando página _PAGE_ de _PAGES_',
						'infoEmpty': 'No hay registros disponibles',
						'infoFiltered': '(filtrado en un total de _MAX_ registros)',
						'search': 'Buscar',
						'paginate': {
							'next': 'Siguiente',
							'previous': 'Anterior'
						}
					} 
				} );		
			
			});    

		</script>";

	}//FIN LISTAR DOCUMENTOS INGRESADOS


	public function actionEliminarDoc(){

		$id= $_POST['id'];
		$model = SafRecepdoc::model()->findByPk($id);	
		$model->estado = 2; 
		$model->save();
	
	}//fin function

	////////////////////  REVISAR INFORMES //////////////////////////
	


//FIN CONTROLADOR
}