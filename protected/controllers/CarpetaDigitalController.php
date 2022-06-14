<?php

date_default_timezone_set('America/Santiago'); 

class CarpetaDigitalController extends Controller{

	public $layout='//layouts/column2';

	public function filters(){
		return array('accessControl', 'postOnly + delete',);
	}


	public function actionEliminarDocumentoDigital($ruc= false){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
    	    	

	    	$this->render('eliminarDocumentoDigital', array('ruc'=>$ruc));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionGuardarParte(){		
		//require_once '../start/protected/views/conexion_saf.php';

	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$clased=TgClasedoc::model()->findAll('cod_clasedoc not in (12,21,25,26,27,28,29,30,31) order by ind_orden asc');

	    	$this->render('guardarParte', array('clased'=>$clased));
	    }//FIN ELSE
	}//FIN FUNCION INDEX

	public function actionUploadParte($ruc, $clase){
		
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

		    	$fis=Yii::app()->user->getState('fiscalia');

		    	$ext = explode("-", $ruc);	
		    	$digito=$ext[1];
		    	$preruta="\\\\EIVG-VIII\\WEB\\F".$fis."\\Digito".$digito."\\".$ruc;
		    	//$preruta="\\\\172.17.123.241\\f".$fis."\\Digito".$digito."\\".$ruc;

		    	if(!file_exists($preruta)){		    		
		    		mkdir($preruta, 0777);
		    	}

		    	/*if($clase<>10){
		    		$tipo = TgClasedoc::model()->findByPk($clase);
		    		$nombre=$_FILES['arr']['name'][$i];
		    		$nombre=utf8_encode($nombre);
		    		$ruta=$preruta.'\\' .$ruc.'_'.$tipo->gls_clasecodigo.'_'.$nombre;
		    	}
		    	else{
		    		$ruta=$preruta.'\\' .$ruc.'.pdf';
		    	} */
		    
		    		$tipo = TgClasedoc::model()->findByPk($clase);
		    		$nombre=$_FILES['arr']['name'][$i];
		    		$nombre=utf8_encode($nombre);
		    		$ruta=$preruta.'\\' .$tipo->gls_clasecodigo.'_'.$nombre;
    	

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
		    	//$ruta='\\\\172.17.123.241\\f803\\' .$_FILES['arr']['name'][$i];	      
		        	       	
				
		    }//fin for
		}
	}//FIN FUNCTION


	public function actionGuardarParteDigital(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new CarpetaDigital;
	    	$flag=false;	    
	        $transaction=$model->dbConnection->beginTransaction();   
	        try{  

	        	if( empty($_POST['ruc']) ) throw new Exception('Error, debes indicar un ruc válido');
	        	if( empty($_POST['clase']) ) throw new Exception('Error, debes indicar una clasificación del documento');
	        	if( empty($_POST['adjunto1']) ) throw new Exception('Error, debes adjuntar un documento');


				$model->idf_rolunico=$_POST['ruc'];

				$model->fec_actividad=date('Y-m-d'); 

				$model->cod_clasedoc=$_POST['clase'];
				if(isset($_POST['nuevo'])){
					$model->cod_estadocarpdig=1;	
				} 
				else{
					$model->cod_estadocarpdig=3;	
				} 
				if(isset($_POST['control'])){
					$model->cod_control=1;	

				} 
				
				if(isset($_POST['prior'])){
					$model->cod_prioritario=1;	
				} 
				$file = $_POST['adjunto1']; 
				$array_file = explode("\\", $file);	

				$ruta = "http://".$array_file[2]."/".$array_file[4]."/".$array_file[5]."/".$array_file[6]."/".$array_file[7];
				$nombre_doc = $array_file[7];

				$model->gls_nomdoc=$nombre_doc;
				$model->gls_ruta=$ruta;
				$model->fis_codigo=Yii::app()->user->getState('fiscalia');
				$model->ind_vigencia=1;
				$model->fun_rut=Yii::app()->user->id;
				$model->fec_registro=date('Y-m-d h:m:s'); 

				if($model->save()){
					$flag=true;
				}
				else throw new Exception('Error al guardar Parte');	
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
					'message'=> 'Parte guardado con éxito'
				));
			}

		/*	
			if(isset($_POST['control']) == 1){
					//$model->cod_control=1;	

				$modelo=new CarpetaAudiencia;
	    		$flag=false;	   
	    	
	        	$transaction=$modelo->dbConnection->beginTransaction();   
	        	try{  

	        	$modelo->idf_rolunico=$_POST['ruc'];
				$modelo->fis_codigo=Yii::app()->user->getState('fiscalia');
				$modelo->fec_audiencia=date('Y-m-d'); 
				$modelo->cod_tipaudiencia=1;
				$modelo->cod_salaaud=3;
				$modelo->fun_rut=Yii::app()->user->id;
				$modelo->fec_registro=date('Y-m-d h:m:s'); 
				
				if($modelo->save()){
					$flag=true;
				}
				else throw new Exception('Error al guardar datos');	
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
					//'message'=> 'Audiencia guardada con éxito'
				));
				} 

			}  /// fin guardar control */


	    }//FIN ELSE
	}//FIN FUNCION GUARDAR

	public function actionVerDocumentosCargados(){

		$doc = CarpetaDigital::model()->getPartesCargados();

		echo "<h4 style='position: absolute;margin-left: 155px;'>Mis documentos cargados del día ".date('d-m-Y')."</h4>
		<table id='listadoc' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width: 85px'>RUC</th>
				<th>CLASE</th>
				<th>FECHA SUBIDA</th>
				<th>RESPONSABLE</th>
				<th>VER</th>
				<th>ELIMINAR REGISTRO</th>
				<th>VER CAUSA</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($doc as $doc) {	
			
			$fecha=$doc['fec_registro'];

			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$doc['idf_rolunico']."</td>"; 
			echo "<td>".$doc['gls_clasedoc']."</td>"; 

			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			echo "<td>".$doc['responsable']."</td>"; 
			
			$id=$doc['cod_carpdig'];
			$ruta=$doc['gls_ruta'];

			echo "<td style='cursor:pointer;'  id='".$ruta."' onclick='verCarpeta(this.id)'  ><img src='".Yii::app()->baseUrl."/images/iconos/pdf.png'/></td>";

			echo "<td> <span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimDoc(this.id)'>X</span></td>";

			$id=$doc['idf_rolunico'];

			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/consultarCarpetaDigital&ruc=".$id."'><span class='btn btn-warning'>Ver Causa</span></td>";

			$n++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listadoc').DataTable( {					
			        
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
					},
			        dom: 'Bfrtip',
					buttons: [					
						{
							extend: 'print',
							text: 'Imprimir',
				            exportOptions: {
				                stripHtml: false,
				                columns: [ 0, 1, 2, 3]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	

//ELIMINAR DOCUMENTO//
	public function actionEliminarDocumentoCargado(){	
 		
 		$id=$_POST['id'];
 		$model=CarpetaDigital::model()->findByPk($id);

 		$elim=new EstadoCarpdigital;
 		$elim->cod_carpdig=$id;
 		$elim->cod_estadocarpdig=4;
 		$elim->fun_rut=Yii::app()->user->id;
		$elim->fec_registro=date('Y-m-d h:m:s'); 
		if($elim->save()){
			$model->ind_vigencia=0;
			$model->cod_estadocarpdig=4;
 			$model->save();
		}

 		//unlink($ruta);
		
		//$this->loadCarpetaDigital($id)->delete();	

	}//FIN FUNCION 


//// ELIMINAR DOCUMENTO DESDE LA VISUALIZACION DE DOCUMENTOS DIGITALES   /////

	public function actionEliminarDocumento(){	
 		
 		$id=$_POST['id'];
 		$model=CarpetaDigital::model()->findByPk($id);

 		$elim=new EstadoCarpdigital;
 		$elim->cod_carpdig=$id;
 		$elim->cod_estadocarpdig=4;
 		$elim->fun_rut=Yii::app()->user->id;
		$elim->fec_registro=date('Y-m-d h:m:s'); 
		if($elim->save()){
			if ($model->cod_clasedoc<>21) 
			{
			$model->ind_vigencia=0;
			$model->cod_estadocarpdig=4;
 			$model->save();
 			}
 			else
 			{
 			$model->delete();
 			}
		
		}    // fin eliminar
 
 		//unlink($ruta);
		
		//$this->loadCarpetaDigital($id)->delete();	

	}//FIN FUNCION 




	public function loadCarpetaDigital($id){
		$model=CarpetaDigital::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

	public function actionConsultarCarpetaDigital($ruc= false){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
    	    	

	    	$this->render('consultarCarpetaDigital', array('ruc'=>$ruc));
	    }//FIN ELSE
	}//FIN FUNCION INDEX

	public function actionGuardarEstadoParteRevisado(){
		$ruc=$_POST['ruc'];
		$revisados = CarpetaDigital::model()->getEstadoParte($ruc);

		$i=0;
		foreach ($revisados as $revisado) {
			$id=$revisado->cod_carpdig;
			$model = CarpetaDigital::model()->findByPk($id);
			$model->cod_estadocarpdig=2;
			if($model->save()){
				$i++;
			}
			
		}
		if($i==0) echo "error, no se encuentra la denuncia pendiente de revisión";


	}

	public function actionConsultaEstadoParte(){
		$ruc=$_POST['ruc'];

		$revisado = CarpetaDigital::model()->getEstadoParte($ruc);
		
		if( isset($revisado) && isset($revisado[0]->cod_estadocarpdig) ){
	    	if( $revisado[0]->cod_estadocarpdig==1 ){

	    		echo "<img src='".Yii::app()->baseUrl."/images/iconos/sin_revision.png'>
	    		<span class='span-verde' style='width: 150px;padding: 7px 20px;font-weight: 600;cursor: pointer;' onclick='parteRevisado()'>Indicar revisión</span>";    		
	    	}
		    else{
		    	echo "<img src='".Yii::app()->baseUrl."/images/iconos/revisado.png' >";
		    }//fin else
	    				
    	}//fin if isset 


	}

	public function actionConsultarDocumentosEliminar(){

		$ext = Yii::app()->baseUrl.'/images/iconos/pdf.png';
		$ruc=$_POST['ruc'];

		
		$doc = CarpetaDigital::model()->getDocumentosDigitalesRuc($ruc);

		echo "<table id='listado_cuatro_columnas' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>CATEGORIA</th>
				<th style='width: 85px'>FECHA</th>
				<th>DOCUMENTO</th>
				<th>TIPO</th>
				<th>VER</th>
				<th></th>
            </tr>
        </thead>";
		echo "<tbody>";		
		foreach ($doc as $doc) {	
			$fecha=$doc['fec_actividad'];
			echo "<tr>"; 
			echo "<td>".$doc['gls_clasedoc']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			echo "<td>".$doc['gls_nomdoc']."</td>"; 	
			echo "<td>";
			if($doc['privacidad']==1) echo "Público";
			else echo "Interno";
			echo "</td>";
			$id=$doc['cod_carpdig'];
			$ruta=$doc['gls_ruta'];
			echo "<td style='cursor:pointer;'  id='".$ruta."' onclick='verCarpeta(this.id)'  ><img src='".Yii::app()->baseUrl."/images/iconos/pdf.png'/></td>";
			echo "<td> <span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimDoc(this.id)'>X</span></td>";
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script src='../start/js/listado_cuatro_columnas_datatables.js' ></script>";

	}

	public function actionConsultarDocumentosRuc(){

		$ext = Yii::app()->baseUrl.'/images/iconos/pdf.png';
		$ruc=$_POST['ruc'];
		$parametros="ruc=".$ruc."&usuario=".Yii::app()->user->name."&origen=sao"; 
		$url='https://agenda.minpublico.cl/app/ficha/#/fichaCaso?cle='.base64_encode($parametros);	

		echo "<div style='float: left;margin-right: 10px;'><a href='".$url."' target='_blank'>
    	<span id='".$ruc."' name='fichacaso' class='btn btn-warning'>Ficha Caso</span></a>
    	</div>";

		//echo "<div>
    	//<span id='".$ruc."' name='descargarsaf' class='btn btn-danger' onClick='ActualizarDocSAF(this.id)'>Actualizar Docs SAF</span>
    	//</div>";

		echo "<div>
    	<span id='".$ruc."' name='descargarpdf' class='btn btn-danger' onClick='descargarCarpetaSIAU(this.id)'>Descargar Carpeta SIAU <img src='".$ext."'/></span>
    	</div>";

		echo "<div style='float: left;margin-right: 10px;'> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/consultaCasoDigital&ruc_decreta=".$ruc."' target='_blank'><span id='".$ruc."' name='diligencia' class='btn btn-warning'>Diligencias Decretadas</span></a>
    	</div>";

		echo "<div style='float: left;margin-right: 10px;'> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/RegistrarTareaAsignar&ruc_decreta=".$ruc."' target='_blank'><span id='".$ruc."' name='diligencia' class='btn btn-warning'>Asignar tareas Gestor</span></a>
    	</div>";
    	echo "<div style='float: left;margin-right: 10px;'> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/RegistrarTarea&ruc_decreta=".$ruc."' target='_blank'><span id='".$ruc."' name='diligencia' class='btn btn-warning'>Asignar tareas Bolsa Trabajo</span></a>
    	</div>";

    	/*echo "<div style='float: left;margin-right: 10px;'> <a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/consultarCarpetaDigitalFavoritos&ruc=".$ruc."' target='_blank'><span id='".$ruc."' name='diligencia' class='btn btn-warning'>Ver Documentos FAVORITOS</span></a>
    	</div>";*/


	  	/*		echo "<div>
		<a href='".Yii::app()->getBaseUrl(true)."/index.php?r=CarpetaDigital/DescargarCarpetaSIAU&ruc=ruc' target='_blank'><span class='btn btn-danger'>Descargar Carpeta SIAU <img src='".$ext."'/></span></a>
    	</div>";*/

		
		$subido = CarpetaDigital::model()->getClasesRuc($ruc);

		/*$verificador = substr($ruc, -1);	
    	$path = "A:\\Digito".$verificador."\\".$ruc."\\";
    	$ruta = "http://172.17.126.15:88/Vigentes/Digito".$verificador."/".$ruc;    	

    	if( is_dir($path) ){
    		$dir=opendir($path);
    		$carpeta = array();   
	    	$i=0;   	

	    	while($temp = readdir($dir)){
	    		//echo $temp."<br>";
	    		if($temp <> '.' && $temp <> '..'){	    			
		    		$carpeta[$i]['nombre'] = utf8_encode($temp);
		    		$carpeta[$i]['ruta'] = utf8_encode($ruta.'/'.$temp);
		    		$i++;
	    		}
	    	}//fin while
    	}//fin if dir */

		echo "<div class='col-md-8' id='listadocu'> 
			<main>";
			echo "<br><p>1- Ficha caso: para visualizar la ficha directamente, previamente debes ingresar tus <strong>credenciales en Ficha Caso</strong>";
			echo "<br><p>2- Puedes <strong>excluir temporalmente un documento de la copia SIAU</strong> con el botón <span class='btn btn-warning'>Excluir documento</span>.";

			 if(isset($subido)){ 			 	
			 	$n=0;
			 	foreach ($subido as $subido) {

			 		if ($subido['privacidad']==1){$ind='(Público)';}	
			 		if ($subido['privacidad']==2){$ind='(Interno)';}	
			 		
			 		echo "<details>";
			 			echo "<summary>".$subido['gls_clasedoc']." ".$ind."</summary>";

			 			echo "<div class='faq__content'>";

			 			$clase=$subido['cod_clasedoc'];
			    		$document = CarpetaDigital::model()->getDocumentosRuc($ruc, $clase);

			    		echo "<table table id='listarea' class='table doc_digital' cellspacing='0' width='100%'>
				    		<tbody>";				    		
				    		foreach ($document as $doc) {					
								echo "<tr>"; 
								$ext = Yii::app()->baseUrl.'/images/iconos/pdf.png';


								if ($doc['cod_clasedoc'] <> 21) 
								{
						        echo "<td style=' width: 40px;' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)' ><img src='".$ext."'/></td>"; 
						        echo "<td style=' width: 80px;'>".date("d-m-Y", strtotime($doc['fec_actividad']))."</td>"; 
								echo "<td><a href='#' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)' >".$doc['gls_nomdoc']."</a></td>"; 


								if($doc['cod_clasedoc'] == 12){

									if($doc['cod_estadocarpdig'] == 1){
										echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-danger' onClick='revisarDoc(this.id)'>No revisado</span></td>";
									}
									else{
										echo "<td><div style='color: #0e7c17; font-weight: 600;font-size: 15px;'><img src='".Yii::app()->baseUrl."/images/iconos/cheque.png'/>Revisado<div></td>";
									}// fin else informe revisado
								}// fin if clase doc es informes

								if($doc['ind_reservado'] == 1){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-danger' onClick='elimReserva(this.id)'>Eliminar exclusión</span></td>"; 
								}
								else{
									if ($subido['privacidad']==1){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-warning' onClick='reservarDoc(this.id)'>Excluir documento</span></td>"; 
									}								
								}
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-warning' onClick='elimDocumento(this.id)'>Eliminar documento</span></td>"; 


								// ind_favorito 		 0: sin marcar  1: favorito
							/*	if($doc['ind_favorito'] == 0){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-warning' onClick='MarcarFavorito(this.id)'>Marcar Favorito</span></td>"; 
								}
								else{
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-danger' onClick='NoMarcarFavorito(this.id)'>Desmarcar Favorito</span></td>"; 
									}	*/

								}
								else
								{
								$act=$doc['tip_actividad'];
								$sact=$doc['tip_subtipactividad'];
								$ssact=$doc['tip_subsubtipactividad'];

								$actividad = TgActividadsaf::model()->getDocumentosActividadRuc($act,$sact,$ssact);
 								
 								echo "<td style=' width: 40px;' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)' ><img src='".$ext."'/></td>"; 
						        echo "<td style=' width: 80px;'>".date("d-m-Y", strtotime($doc['fec_actividad']))."</td>"; 
								//echo "<td><a href='#' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)' >".$doc['gls_nomdoc']."</a></td>"; 
								
						        foreach ($actividad as $activ) {
								if ($activ['cod_judicial']==1){$indj='Judicial';}	
			 					if ($activ['cod_judicial']==2){$indj='No Judicial';}
								echo "<td><a href='#' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)'>".$activ['gls_actividad'].' - '.$activ['gls_subtipactividad'].' - '.$activ['gls_subsubtipactividad']."</a></td>"; 
								echo "<td style=' width: 50px;'>".$indj."</td>"; 
								}
								
								if($doc['ind_reservado'] == 1){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-danger' onClick='elimReserva(this.id)'>Eliminar exclusión</span></td>"; 
								}
								else{
									if ($subido['privacidad']==1){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-warning' onClick='reservarDoc(this.id)'>Excluir documento</span></td>"; 
									}								
								}

									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-warning' onClick='elimDocumento(this.id)'>Eliminar documento</span></td>";

								/*	
								if($doc['ind_favorito'] == 0){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-warning' onClick='MarcarFavorito(this.id)'>Marcar Favorito</span></td>"; 
								}
								else{
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-danger' onClick='NoMarcarFavorito(this.id)'>Desmarcar Favorito</span></td>"; 
									} */	

								}   //else tipo documentos

								echo "</tr>"; 

							}  // fin busca documentos todos de RUC	



				    	echo "</tbody>  	
				    	 </table>";

			 			echo "</div>";

			 		echo "</details>";
			 		$n++;
			 	}//FIN FOREACH DOCUMENTOS
			 	if($n == 0){
			 		
			 		echo "<h4>No se encontraron documentos digitales</h4>";
			 		
			 	}
			 }//FIN EXISTEN DOCUMENTOS

	     
		if($n <> 0){
			/*echo "</main>
			</div>
			<div class='col-md-6' id='visualizarDocu' style='padding-left: 25px;'> 
				<div id='verpdf'>
		    		
		    	</div>
			</div>	
			"; */
		}
	
	}

/*
	public function actionDescargarCarpetaSIAU(){

		$ruc=$_GET['ruc']; 

		$model = CarpetaDigital::model()->getDocumentosPublicos($ruc);

		$this->render('convertirPDF', array('ruc'=>$ruc, 'model'=>$model));
	}
*/

/////*   ACTUALIZADO CON TRABAJO REALZIADO POR EMPRESA  *//////
	public function actionDescargarCarpetaSIAU(){

		$ruc=$_GET['ruc']; 

		$model = CarpetaDigital::model()->getDocumentosPublicos($ruc);

		$this->renderPartial('convertirPDFmerge', array('ruc'=>$ruc, 'model'=>$model));

	}


	public function actionDescargarCarpetaMerge(){

		$ruc=$_GET['ruc'];
		//$ruc="2100666278-9"; 

		$model = CarpetaDigital::model()->getDocumentosPublicos($ruc);

		//$this->render('convertirPDFmerge', array('ruc'=>$ruc, 'model'=>$model));
		$this->renderPartial('convertirPDFmerge', array('ruc'=>$ruc, 'model'=>$model));
		//$this->render('pdftk', array('ruc'=>$ruc, 'model'=>$model));
	}




	public function actionDescargarCarpetaMergeFecha(){

		$ruc=$_GET['ruc'];
		//$ruc="2100666278-9"; 

		$model = CarpetaDigital::model()->getDocumentosPublicosfecha($ruc);

		$this->renderPartial('convertirPDFmergeFecha', array('ruc'=>$ruc, 'model'=>$model));
		//$this->renderPartial('convertirPDFmerge', array('ruc'=>$ruc, 'model'=>$model));
		//$this->render('pdftk', array('ruc'=>$ruc, 'model'=>$model));
	}



///////	///////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////

	public function actionConsultarCarpeta($ruc= false){		
		//require_once '../start/protected/views/conexion_saf.php';

	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
    	    	

	    	$this->render('consultarCarpeta', array('ruc'=>$ruc));
	    }//FIN ELSE
	}//FIN FUNCION INDEX



	public function actionGuardaConsultaDocumentos(){		
		
		$ruc=$_POST['ruc'];

		$temp = TempDigital::model()->getDocumentosTemporales($ruc); 
		$n=0; 
		if( isset($temp[0]->cod_tempdig) ){
		foreach ($temp as $temp) {

			$id = $temp['cod_tempdig'];
			$idact = $temp['crr_idactividad'];

			$existe=CarpetaDigital::model()->findAll('crr_idactividad="'.$idact.'" ');
			if( !isset($existe[0]->crr_idactividad) ){

				$file = $temp['gls_ruta']; 
				
			
				if(file_exists( $file ) ) {
				   
					$array_file = explode("\\", $file);	
					$nombre_doc = "";
					
					if( isset($array_file[8]) ){
						$file_limpio =str_replace(' ', '-', $array_file[8]);

						$destino=utf8_decode("C:/temp_carpeta_digital/".$file_limpio);
						$extraer_nombre = explode(".", $file_limpio);	
						$nombre_doc = $extraer_nombre[0];
						$nombre_doc = utf8_decode($nombre_doc);

					}
					elseif( isset($array_file[7]) ){
						$file_limpio =str_replace(' ', '-', $array_file[7]);

						$destino=utf8_decode("C:/temp_carpeta_digital/".$file_limpio);
						$extraer_nombre = explode(".", $file_limpio);	
						$nombre_doc = $extraer_nombre[0];
						$nombre_doc = utf8_decode($nombre_doc);

					}
					elseif( isset($array_file[6]) ){
						$file_limpio =str_replace(' ', '-', $array_file[6]);

						$destino=utf8_decode("C:/temp_carpeta_digital/".$file_limpio);
						$extraer_nombre = explode(".", $file_limpio);	
						$nombre_doc = $extraer_nombre[0];
						$nombre_doc = utf8_decode($nombre_doc);

					}
					elseif( isset($array_file[5]) ){
						$file_limpio =str_replace(' ', '-', $array_file[5]);

						$destino=utf8_decode("C:/temp_carpeta_digital/".$file_limpio);
						$extraer_nombre = explode(".", $file_limpio);	
						$nombre_doc = $extraer_nombre[0];
						$nombre_doc = utf8_decode($nombre_doc);

					}

					copy($file,$destino);

					$fis=Yii::app()->user->getState('fiscalia');

			    	$ext = explode("-", $ruc);	
			    	$digito=$ext[1];
			    	$preruta="\\\\EIVG-VIII\\WEB\\F".$fis."\\Digito".$digito."\\".$ruc;
			    	
	
			    	if(!file_exists($preruta)){		    		
			    		mkdir($preruta, 0777);
			    	}

			    	$doc_pdf = $preruta."\\".$nombre_doc.".pdf";	


			    	$ruta = "http://EIVG-VIII/F".$fis."/Digito".$digito."/".$ruc."/".$nombre_doc.".pdf";

			    	$nombre_doc = utf8_encode($nombre_doc);
			    	$ruta = utf8_encode($ruta);

			    	$model=new CarpetaDigital;
					$model->idf_rolunico=$ruc; 
					$model->fis_codigo=$fis; 
					$model->fec_actividad=$temp['fec_actividad']; 
					$model->crr_idactividad=$temp['crr_idactividad']; 
					$model->tip_actividad=$temp['tip_actividad']; 
					$model->tip_subtipactividad=$temp['tip_subtipactividad']; 
					$model->tip_subsubtipactividad=$temp['tip_subsubtipactividad']; 
					$model->cod_clasedoc=21;
					$model->cod_estadocarpdig=2;
					$model->gls_nomdoc=$nombre_doc.".pdf";		
					$model->gls_ruta=$ruta; 
					$model->ind_vigencia=1;
					$model->fun_rut=Yii::app()->user->id;
					$model->fec_registro=date('Y-m-d h:m:s');  
					if($model->save()){
						$word = new COM("Word.Application") or die ("Could not initialise Object.");
						$word->Visible = 0;
						$word->DisplayAlerts = 0;  
						$word->Documents->Open($destino);  
						$word->ActiveDocument->ExportAsFixedFormat($doc_pdf, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);
						$word->Quit(false);
						unset($word);	

						echo "SE CARGO DOCUMENTO SAF :".$nombre_doc;

						unlink($destino);
						$this->loadCarpetaTemporal($id)->delete();	
					}
					else{
						echo "ERROR, NO SE PUEDE GUARDAR DOCUMENTO DE SAF";
					}

					

				} //IF EXISTE FILE
				else {
				    echo "DOCUMENTO SAF: $file NO ENCONTRADO</br></br>";
				}


			}//FIN EXISTE
			else{
				echo "NO EXISTEN DOCUMENTOS NUEVOS";
				$this->loadCarpetaTemporal($id)->delete();	
			}
			

		}//FIN FOREACH				
	}// fin existe registro en temp digital
	else{
		echo "NO EXISTEN DOCUMENTOS NUEVOS";
	}

	}//FIN FUNCION INDEX



	public function loadCarpetaTemporal($id){
		$model=TempDigital::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}



/////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////CAUSAS PARA PRECLASIFICADOR  /////////////////////////	

	public function actionCausasPrec(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	//$fun = Funcionario::model()->getFuncionariosTodos();
			$fis=Yii::app()->user->getState('fiscalia');
	    	//$fun = CuentaGenerica::model()->getCuentasFiscalesVigentes($fis);

	    	$this->render('CausasPreclasificador');
	    }//FIN ELSE
	}//FIN FUNCION INDEX


///////////////////////////LISTADO DE CAUSAS PARA PREC  ///////////////////////////////////


public function actionListarCausasPrec(){
		
		$fisc=Yii::app()->user->getState('fiscalia');

		$ta = CarpetaDigital::model()->getCausasPrec($fisc);

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th>RUC</th>
				<th>TIPO</th>
				<th>ESTADO</th>
				<th>FECHA INGRESO</th>
				<th>INGRESADO POR</th>
				<th>VER CAUSA</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($ta as $ta) {	
			$fecha_reg=$ta['fec_registro'];
			$ruc_caso= $ta['idf_rolunico'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			$parametros="ruc=".$ta['idf_rolunico']."&usuario=".Yii::app()->user->name."&origen=sao";
 					//echo $parametros;
			$url='https://agenda.minpublico.cl/app/ficha/#/fichaCaso?cle='.base64_encode($parametros);	
			echo "<td><font color='#3433FF'>".CHtml::link($ruc_caso, $url, array('target'=>'_blank'))."</font></td>";
			if ($ta['cod_control']==1){
				echo "<td>".'CONTROL'."</td>"; 
				}
			else {
				echo "<td>".'  '."</td>";
				}
			if ($ta['cod_prioritario']==1){
				echo "<td>".'PRIORITARIO'."</td>"; 
				}
			else {
				echo "<td>".'  '."</td>";
				}
			echo "<td>".date("d-m-Y", strtotime($fecha_reg))."</td>";
			echo "<td>".$ta['responsable']."</td>";
			//echo "<td>".$ta['cuenta_nombre']."' '".$ta['cuenta_apellido']."</td>"; 
			$id=$ta['idf_rolunico'];

			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/consultarCarpetaDigital&ruc=".$id."' ><span class='btn btn-warning'>Ver Causa</span></td>";

			$n++;
		}		

		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					
						'columnDefs': [
			            {
			                'targets': [ 0 ],
			                'visible': false,
			                'searchable': false
			            },
			        ],
			        
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
					},
			        dom: 'Bfrtip',
					buttons: [					
						{
							extend: 'print',
							text: 'Imprimir',
				            exportOptions: {
				                stripHtml: false,
				                columns: [ 0, 1,2]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1,2 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	

/////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////REVISION DE CAUSAS VIGENTES   POR FISCAL  /////////////////////////	
/////////////////////////////////////////////////////////////////////////////////////////////		/////////////////////////////////////////////////////////////////////////////////////////////

	public function actionCausasVigentesFiscal(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	//$fun = Funcionario::model()->getFuncionariosTodos();
			$fis=Yii::app()->user->getState('fiscalia');
	    	$fun = CuentaGenerica::model()->getCuentasFiscalesVigentes($fis);

	    	$this->render('CausasVigentesFiscal', array('fun'=>$fun));
	    }//FIN ELSE
	}//FIN FUNCION INDEX





////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////   LISTAR CAUSAS VIGENTES FISCAL  ////////////////////////////


	public function actionListarCausasVigentesFiscal(){
		
		$rut_fiscal=$_POST['fun'];
		//$fec_ini=$_POST['fec'];
		///$fec_fin=$_POST['fec_fin'];
		$ta = CausaVigente::model()->getListadoVigentes($rut_fiscal);

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th>RUC</th>
				<th>FECHA ASIGNACION</th>
				<th>FECHA RECEPCION</th>
				<th>ULTIMA ACTIVIDAD</th>
				<th>FECHA ACTIVIDAD</th>
				<th>JUDICIALIZADA</th>
				<th>VER CAUSA</th>
				<th>ASIGNAR TAREAS GESTOR</th>
				<th>ASIGNAR TAREAS BT</th>
				<th>VER DILIGENCIAS DECRETADAS</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($ta as $ta) {	
			$fecha_recep=$ta['fec_recepcion'];
			$fecha_asig=$ta['fec_asig'];
			$fecha=$ta['fec_ult_tramitacion'];
			$ruc_caso= $ta['idf_rolunico'];
			
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			//echo "<td>".$ta['fiscal']."</td>"; 
			//echo "<td>".$ta['idf_rolunico']."</td>";

			$parametros="ruc=".$ta['idf_rolunico']."&usuario=".Yii::app()->user->name."&origen=sao";
 					//echo $parametros;
			$url='https://agenda.minpublico.cl/app/ficha/#/fichaCaso?cle='.base64_encode($parametros);	
			echo "<td><font color='#3433FF'>".CHtml::link($ruc_caso, $url, array('target'=>'_blank'))."</font></td>";

			echo "<td><font color='#3433FF'>".date("d-m-Y", strtotime($fecha_asig))."</font></td>";
			echo "<td>".date("d-m-Y", strtotime($fecha_recep))."</td>";
			//echo "<td>".$ta['cuenta_nombre']."' '".$ta['cuenta_apellido']."</td>"; 
			echo "<td>".$ta['gls_tipactividad']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			//echo "<td>".$ta['ind_jud']."</td>"; 
			if  ($ta['ind_jud']==1){
			echo "<td> Judicializada</td>";}
			else{
			echo "<td>No Judicializada</td>";}
			//if  ($ta['gls_tipactividad']<> ""){
			//echo "<td> <font color='#3433FF'>Con Actividad </font></td>";}
			//else{
			//echo "<td>Sin Actividad</td>";}
			
			$id=$ta['idf_rolunico'];

			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/consultarCarpetaDigital&ruc=".$id."' target='_blank'><span class='btn btn-warning'>Ver Causa</span></td>";

			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/RegistrarTareaAsignar&ruc_decreta=".$id."' target='_blank'><span class='btn btn-warning'>Tareas Gestor</span></a></td>";
			
			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/RegistrarTarea&ruc_decreta=".$id."' target='_blank'><span class='btn btn-warning'>Tareas BT</span></a></td>";

			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/consultaCasoDigital&ruc_decreta=".$id."' target='_blank'><span class='btn btn-warning'>Ver Diligencias</span></a></td>";
			$n++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					
						'columnDefs': [
			            {
			                'targets': [ 0 ],
			                'visible': false,
			                'searchable': false
			            },
			        ],
			        
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
					},
			        dom: 'Bfrtip',
					buttons: [					
						{
							extend: 'print',
							text: 'Imprimir',
				            exportOptions: {
				                stripHtml: false,
				                columns: [ 0, 1, 2, 3, 4, 5 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	
//FIN FUNCTIOn


	//// PERMISOS SOBRE LA CAUSA SEGÚN CUENTA FISCAL 
	public function actionConsultaPermisoCausa(){

		$fiscal=$_GET['fiscal'];

		$permiso=PermisoCausa::model()->getPermisoCausa($fiscal);

		if($permiso[0]->total == 1){
			echo json_encode(array(
				'status' => 'success',
				'message'=> 'Con permisos otorgados'
				));
		}
		else{
			echo json_encode(array(
				'status' => 'success',
				'message'=> 'Con permisos otorgados'
		// temporal permisos borrados//	'status' => 'error',
        // temporal permisos borrados//					'message'=> '<h4 style="text-align: center;background-color: bisque;
    	// temporal permisos borrados//padding: 10px 0px;">Sin permisos para acceder a la causa</h4>',
		
				));
		}//fin else

		
	}

	//// RESERVA DE LA CAUSA -- CAUSA SECRETA
	public function actionConsultaCausaReservada(){

		$ruc=$_GET['ruc'];

		$reservada=PermisoReserva::model()->findAll('idf_rolunico = "'.$ruc.'" and ind_vigencia = 1');

		if(isset($reservada[0]->idf_rolunico)){
			$permiso=PermisoReserva::model()->getReservaCausa($ruc);

			if($permiso[0]->total == 1){
				echo json_encode(array(
					'status' => 'success',
					'message'=> 'Con permisos otorgados'
					));
			}
			else{
				echo json_encode(array(
						'status' => 'error',
						'message'=> '<h4 style="text-align: center;background-color: #ff1308;padding: 10px 0px;color: #FFF;">Causa reservada - Sin permisos para acceder a la causa.</h4>',
					));
			}//fin else		
		}//fin isser existe ruc
		else{
			echo json_encode(array(
				'status' => 'success',
				'message'=> 'Causa no reservada'
			));
		}
		
	}

/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
///////////////////////   REGISTRO DE AUDIENCIAS   //////////////
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
	public function actionAgendarAudiencia(){		
		//require_once '../start/protected/views/conexion_saf.php';

	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$sala=TgSalaaud::model()->getSalaAud();
	    	$hora=Tghora::model()->getHora();
			$tipaud=TgTipaudiencia::model()->findAll();
	    	$this->render('AgendarAudiencia', array('tipaud'=>$tipaud,'sala'=>$sala,'hora'=>$hora));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


public function actionGuardarDatosAudiencia(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$modelo=new CarpetaAudiencia;
	    	$flag=false;	   
	    	
	        $transaction=$modelo->dbConnection->beginTransaction();   
	        try{  

	        	if( empty($_POST['ruc']) ) throw new Exception('Error, debes indicar un ruc válido');

	        	$modelo->idf_rolunico=$_POST['ruc'];
				$modelo->fis_codigo=Yii::app()->user->getState('fiscalia');
				$modelo->fec_audiencia=$_POST['fec_audiencia']; 
				$modelo->cod_tipaudiencia=$_POST['tipoaud'];
				$modelo->cod_salaaud=$_POST['salaaud'];
				$modelo->cod_hora=$_POST['horaaud'];
				$modelo->fun_rut=Yii::app()->user->id;
				$modelo->fec_registro=date('Y-m-d h:m:s'); 

				
				if($modelo->save()){
					$flag=true;
				}
				else throw new Exception('Error al guardar datos');	
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
					'message'=> 'Audiencia guardada con éxito'
				));
			}
			
	    }//FIN ELSE
	}//FIN FUNCION GUARDAR

	public function actionVerListadoAudiencia(){

		$doc = CarpetaAudiencia::model()->getListadoAudiencia();

		echo "<table id='listadoc' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width: 85px'>RUC</th>
				<th>ESTADO</th>
				<th>SALA</th>
				<th>HORA</th>
				<th>AUDIENCIA</th>
				<th>FECHA AUDIENCIA</th>
				<th>ELIMINAR</th>

            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($doc as $doc) {	
			
			$fecha=$doc['fec_registro'];
			$fecha_aud=$doc['fec_audiencia'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			//echo "<td>".$doc['cod_carpaud']."</td>"; 
			echo "<td>".$doc['idf_rolunico']."</td>"; 

			if ($doc['digitalizada'] <> ""){
			echo "<td>"."<b><font color='#FF0000'> DIGITALIZADA</font></b> "."</td>";				
				}
		    else{
		    	echo "<td>"."<b><font color='#FF0000'>NO DIGITALIZADA</font></b> "."</td>";
		    }//fin else
	    				
   			echo "<td>".$doc['salaaud']."</td>";
			echo "<td>".$doc['horaaud']."</td>";
			echo "<td>".$doc['audiencia']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha_aud))."</td>";
		//	echo "<td>".$doc['responsable']."</td>"; 
			$ida=$doc['cod_carpaud'];
			echo "<td> <span id='".$ida."' name='eliminar' class='btn btn-warning' onclick='elimDocAud(this.id)'>X</span></td>";
			
			$n++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listadoc').DataTable( {					
			        
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
					},
			        dom: 'Bfrtip',
					buttons: [					
						{
							extend: 'print',
							text: 'Imprimir',
				            exportOptions: {
				                stripHtml: false,
				                columns: [ 0, 1, 2, 3]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	

//ELIMINAR AUDIENCIA
	public function actionEliminarAudienciaCargada(){	
 		
 		$id=$_POST['id'];
 		$model=CarpetaAudiencia::model()->findByPk($id);

 		$elim=new ElimAudiencia;
 		$elim->cod_carpaud=$id;
 		$elim->fun_rut=Yii::app()->user->id;
		$elim->fec_registro=date('Y-m-d h:m:s'); 
		if($elim->save()){
			$this->loadAudDigital($id)->delete();	
		}

	}//FIN FUNCION 


	public function loadAudDigital($id){
		$model=CarpetaAudiencia::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}



////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
/////////////////   LISTADO DE AUDIENCIAS  /////////////////////////	
////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
	
	public function actionAudienciasFiscal(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	//$fun = Funcionario::model()->getFuncionariosTodos();
			$fis=Yii::app()->user->getState('fiscalia');
	    	//$fun = CuentaGenerica::model()->getCuentasFiscalesVigentes($fis);

	    	$this->render('AudienciasFiscal');
	    }//FIN ELSE
	}//FIN FUNCION INDEX


public function actionListarCausaAudienciaFiscal(){

		$fec_desde = $_POST['fec_ini'];
		$doc = CarpetaAudiencia::model()->getListadoAudienciaSala1Fiscal($fec_desde);

		echo "<table id='listado_cuatro_columnas' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
            	<th>HORA</th>
				<th style='width: 85px'>RUC</th>
				<th>FISCAL</th>
				<th>AUDIENCIA</th>
				<th>FECHA AUDIENCIA</th>
				<th>VER CAUSA</th>
				<th>ADJUNTAR MINUTA</th>
				<th>X</th>
				<th>ADJUNTAR RESULTADO</th>
				<th>X</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($doc as $doc) {	
			
			$fecha=$doc['fec_registro'];
			$fecha_aud=$doc['fec_audiencia'];
			$ruc_caso=$doc['idf_rolunico'];
			echo "<tr>"; 
			echo "<td>".$n."</td>";
			echo "<td>".$doc['horaaud1']."</td>"; 
			$parametros="ruc=".$doc['idf_rolunico']."&usuario=".Yii::app()->user->name."&origen=sao";
			$url='https://agenda.minpublico.cl/app/ficha/#/fichaCaso?cle='.base64_encode($parametros);	
			echo "<td><font color='#3433FF'>".CHtml::link($ruc_caso, $url, array('target'=>'_blank'))."</font></td>";
			echo "<td>".$doc['fiscal']."</td>"; 	
			echo "<td>".$doc['audiencia']."</td>"; 	
			echo "<td>".date("d-m-Y", strtotime($fecha_aud))."</td>";	
			$ida=$doc['idf_rolunico'];
			$id=$doc['cod_carpaud'];
			$id_carp=$doc['cod_carpdig'];
			$id_resul=$doc['cod_carpdigresultado'];
			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/consultarCarpetaDigital&ruc=".$ida."'><span class='btn btn-warning'>Ver Causa</span></a></td>";

			echo "<td>";
			if($doc['cod_carpdig'] == 0){
				echo "<input type='file' name=".$id."  id=".$id." onchange='adjuntarMinuta(this.value, this.id)'>";
				echo "<td>  </td>";
			}
			else{
				echo "<a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/DownloadDoc&id=".$id_carp."'><span class='btn btn-warning'>Descargar</span></a>";
				echo "<td> <span id='".$id_carp."' name='eliminar' class='btn btn-danger' onclick='elimMinAud(this.id)'>Elim Minuta</span></td>";
			}			
			echo "</td>";

			echo "<td>";
			if($doc['cod_carpdigresultado'] == 0){
				echo "<input type='file' name=".$id."  id=".$id." onchange='adjuntarResultado(this.value, this.id)'>";
				echo "<td>  </td>";
			}
			else{
				echo "<a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/DownloadDoc&id=".$id_resul."'><span class='btn btn-warning'>Descargar</span></a>";
				echo "<td> <span id='".$id_resul."' name='eliminar' class='btn btn-danger' onclick='elimResulAud(this.id)'>Elim Result.</span></td>";
			}			
			echo "</td>";	
			$n++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script src='../start/js/listado_cuatro_columnas_datatables.js' ></script>";

	}//FIN FUNCTION	

	public function actionUploadMinutaResultado($codigo){	
		
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
		    $aud = CarpetaAudiencia::model()->findByPk($codigo);
		    $ruc = $aud->idf_rolunico;
		 
		    for ($i = 0; $i < $count; $i++) { 

		    	$fis=Yii::app()->user->getState('fiscalia');

		    	$ext = explode("-", $ruc);	
		    	$digito=$ext[1];
		    	$preruta="\\\\EIVG-VIII\\WEB\\F".$fis."\\Digito".$digito."\\".$ruc;

		    	if(!file_exists($preruta)){		    		
		    		mkdir($preruta, 0777);
		    	}

				$clase=32;
		    	$tipo = TgClasedoc::model()->findByPk($clase);
		    	$nombre=$_FILES['arr']['name'][$i];
		    	$nombre=utf8_encode($nombre);
		    	$nombre = str_replace(" ","%20",$nombre);
		    	$ruta=$preruta.'\\' .$ruc.'_'.$tipo->gls_clasecodigo.'_'.$nombre;
		    
		    	$ruta=utf8_decode($ruta);	

		    	$model=new CarpetaDigital;
				$model->idf_rolunico=$ruc;
				$model->fec_actividad=date('Y-m-d');
				$model->cod_clasedoc=$clase;
				$model->cod_estadocarpdig=1;	
				$array_file = explode("\\", $ruta);

				$archivo = "http://".$array_file[2]."/".$array_file[4]."/".$array_file[5]."/".$array_file[6]."/".$array_file[7];
				$nombre_doc = $array_file[7];

				$model->gls_nomdoc=$nombre_doc;
				$model->gls_ruta=$archivo;
				$model->fis_codigo=Yii::app()->user->getState('fiscalia');
				$model->ind_vigencia=1;
				$model->fun_rut=Yii::app()->user->id;
				$model->fec_registro=date('Y-m-d h:m:s'); 

				if($model->save()){
					move_uploaded_file($_FILES['arr']['tmp_name'][$i], $ruta);
					$aud->cod_carpdigresultado=$model->cod_carpdig;
					$aud->save();

			    	echo json_encode(array(
					    'status' => 'success',
					    'message'=> $ruta
					));     
		        	  
				}
				else{
					echo json_encode(array(
					    'status' => 'error',
					    'message'=> '#error, no es posible adjuntar el resultado'
					));  
				}			         	
				
		    }//fin for
		}
	}//FIN FUNCTION



public function actionUploadMinutaResultadoS2($codigo){	
		
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
		    $aud = CarpetaAudiencia::model()->findByPk($codigo);
		    $ruc = $aud->idf_rolunico;
		 
		    for ($i = 0; $i < $count; $i++) { 

		    	$fis=Yii::app()->user->getState('fiscalia');

		    	$ext = explode("-", $ruc);	
		    	$digito=$ext[1];
		    	$preruta="\\\\EIVG-VIII\\WEB\\F".$fis."\\Digito".$digito."\\".$ruc;

		    	if(!file_exists($preruta)){		    		
		    		mkdir($preruta, 0777);
		    	}

				$clase=32;
		    	$tipo = TgClasedoc::model()->findByPk($clase);
		    	$nombre=$_FILES['arr']['name'][$i];
		    	$nombre=utf8_encode($nombre);
		    	$nombre = str_replace(" ","%20",$nombre);
		    	$ruta=$preruta.'\\' .$ruc.'_'.$tipo->gls_clasecodigo.'_'.$nombre;
		    
		    	$ruta=utf8_decode($ruta);	

		    	$model=new CarpetaDigital;
				$model->idf_rolunico=$ruc;
				$model->fec_actividad=date('Y-m-d');
				$model->cod_clasedoc=$clase;
				$model->cod_estadocarpdig=1;	
				$array_file = explode("\\", $ruta);

				$archivo = "http://".$array_file[2]."/".$array_file[4]."/".$array_file[5]."/".$array_file[6]."/".$array_file[7];
				$nombre_doc = $array_file[7];

				$model->gls_nomdoc=$nombre_doc;
				$model->gls_ruta=$archivo;
				$model->fis_codigo=Yii::app()->user->getState('fiscalia');
				$model->ind_vigencia=1;
				$model->fun_rut=Yii::app()->user->id;
				$model->fec_registro=date('Y-m-d h:m:s'); 

				if($model->save()){
					move_uploaded_file($_FILES['arr']['tmp_name'][$i], $ruta);
					$aud->cod_carpdigresultado=$model->cod_carpdig;
					$aud->save();

			    	echo json_encode(array(
					    'status' => 'success',
					    'message'=> $ruta
					));     
		        	  
				}
				else{
					echo json_encode(array(
					    'status' => 'error',
					    'message'=> '#error, no es posible adjuntar el resultado'
					));  
				}			         	
				
		    }//fin for
		}
	}//FIN FUNCTION





	public function actionUploadMinuta($codigo){	
		
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
		    $aud = CarpetaAudiencia::model()->findByPk($codigo);
		    $ruc = $aud->idf_rolunico;
		 
		    for ($i = 0; $i < $count; $i++) { 

		    	$fis=Yii::app()->user->getState('fiscalia');

		    	$ext = explode("-", $ruc);	
		    	$digito=$ext[1];
		    	$preruta="\\\\EIVG-VIII\\WEB\\F".$fis."\\Digito".$digito."\\".$ruc;

		    	if(!file_exists($preruta)){		    		
		    		mkdir($preruta, 0777);
		    	}

				$clase=24;
		    	$tipo = TgClasedoc::model()->findByPk($clase);
		    	$nombre=$_FILES['arr']['name'][$i];
		    	$nombre=utf8_encode($nombre);
		    	$nombre = str_replace(" ","%20",$nombre);
		    	$ruta=$preruta.'\\' .$ruc.'_'.$tipo->gls_clasecodigo.'_'.$nombre;
		    
		    	$ruta=utf8_decode($ruta);	

		    	$model=new CarpetaDigital;
				$model->idf_rolunico=$ruc;
				$model->fec_actividad=date('Y-m-d');
				$model->cod_clasedoc=$clase;
				$model->cod_estadocarpdig=1;	
				$array_file = explode("\\", $ruta);

				$archivo = "http://".$array_file[2]."/".$array_file[4]."/".$array_file[5]."/".$array_file[6]."/".$array_file[7];
				$nombre_doc = $array_file[7];

				$model->gls_nomdoc=$nombre_doc;
				$model->gls_ruta=$archivo;
				$model->fis_codigo=Yii::app()->user->getState('fiscalia');
				$model->ind_vigencia=1;
				$model->fun_rut=Yii::app()->user->id;
				$model->fec_registro=date('Y-m-d h:m:s'); 

				if($model->save()){
					move_uploaded_file($_FILES['arr']['tmp_name'][$i], $ruta);
					$aud->cod_carpdig=$model->cod_carpdig;
					$aud->save();

			    	echo json_encode(array(
					    'status' => 'success',
					    'message'=> $ruta
					));     
		        	  
				}
				else{
					echo json_encode(array(
					    'status' => 'error',
					    'message'=> '#error, no es posible adjuntar la minuta'
					));  
				}			         	
				
		    }//fin for
		}
	}//FIN FUNCTION





////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
/////////////////   LISTADO DE AUDIENCIAS SALA 2  /////////////////////////	
////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
	
	public function actionAudienciassala2(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	//$fun = Funcionario::model()->getFuncionariosTodos();
			$fis=Yii::app()->user->getState('fiscalia');
	    	//$fun = CuentaGenerica::model()->getCuentasFiscalesVigentes($fis);

	    	$this->render('Audienciassala2');
	    }//FIN ELSE
	}//FIN FUNCION INDEX


public function actionListarCausaAudienciaSala2(){

		$fec_desde = $_POST['fec_ini'];
		$doc = CarpetaAudiencia::model()->getListadoAudienciaSala2($fec_desde);

		echo "<table id='listado_cuatro_columnas' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
            	<th>HORA</th>
				<th style='width: 85px'>RUC</th>
				<th>FISCAL</th>
				<th>AUDIENCIA</th>
				<th>FECHA AUDIENCIA</th>
				<th>VER CAUSA</th>
				<th>ADJUNTAR MINUTA</th>
				<th>X</th>
				<th>ADJUNTAR RESULTADO</th>
				<th>X</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($doc as $doc) {	
			
			$fecha=$doc['fec_registro'];
			$fecha_aud=$doc['fec_audiencia'];
			$ruc_caso=$doc['idf_rolunico'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$doc['horaaud2']."</td>"; 
			$parametros="ruc=".$doc['idf_rolunico']."&usuario=".Yii::app()->user->name."&origen=sao";
			$url='https://agenda.minpublico.cl/app/ficha/#/fichaCaso?cle='.base64_encode($parametros);	
			echo "<td><font color='#3433FF'>".CHtml::link($ruc_caso, $url, array('target'=>'_blank'))."</font></td>";
			echo "<td>".$doc['fiscal']."</td>"; 
			echo "<td>".$doc['audiencia']."</td>"; 	
			echo "<td>".date("d-m-Y", strtotime($fecha_aud))."</td>";	
			$ida=$doc['idf_rolunico'];
			$id=$doc['cod_carpaud'];
			$id_carp=$doc['cod_carpdig'];
			$id_resul=$doc['cod_carpdigresultado'];
			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/consultarCarpetaDigital&ruc=".$ida."'><span class='btn btn-warning'>Ver Causa</span></a></td>";

			echo "<td>";
			if($doc['cod_carpdig'] == 0){
				echo "<input type='file' name=".$id."  id=".$id." onchange='adjuntarMinuta(this.value, this.id)'>";
				echo "<td>  </td>";
			}
			else{
				echo "<a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/DownloadDoc&id=".$id_carp."'><span class='btn btn-warning'>Descargar</span></a>";
				echo "<td> <span id='".$id_carp."' name='eliminar' class='btn btn-danger' onclick='elimMinAud(this.id)'>Elim Minuta</span></td>";
			}			
			echo "</td>";

			echo "<td>";
			if($doc['cod_carpdigresultado'] == 0){
				echo "<input type='file' name=".$id."  id=".$id." onchange='adjuntarResultado(this.value, this.id)'>";
				echo "<td>  </td>";
			}
			else{
				echo "<a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/DownloadDoc&id=".$id_resul."'><span class='btn btn-warning'>Descargar</span></a>";
				echo "<td> <span id='".$id_resul."' name='eliminar' class='btn btn-danger' onclick='elimResulAud(this.id)'>Elim Result.</span></td>";
			}			
			echo "</td>";	
			$n++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script src='../start/js/listado_cuatro_columnas_datatables.js' ></script>";

	}//FIN FUNCTION	








////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
/////////////////   LISTADO DE AUDIENCIAS DE CONTROL DE DETENCION  /////////////////////////	
////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
	
	public function actionAudienciasControles(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	//$fun = Funcionario::model()->getFuncionariosTodos();
			$fis=Yii::app()->user->getState('fiscalia');
	    	//$fun = CuentaGenerica::model()->getCuentasFiscalesVigentes($fis);

	    	$this->render('AudienciasControles');
	    }//FIN ELSE
	}//FIN FUNCION INDEX


public function actionListarCausaAudienciaControles(){

		$fec_desde = $_POST['fec_ini'];
		$doc = CarpetaAudiencia::model()->getListadoAudienciaControles($fec_desde);

		echo "<table id='listado_cuatro_columnas' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width: 85px'>RUC</th>
				<th>AUDIENCIA</th>
				<th>FECHA AUDIENCIA</th>
				<th>VER CAUSA</th>
				<th>ADJUNTAR RESULTADO</th>
				<th>X</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($doc as $doc) {	
			
			$fecha=$doc['fec_registro'];
			$fecha_aud=$doc['fec_audiencia'];
			$ruc_caso=$doc['idf_rolunico'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			$parametros="ruc=".$doc['idf_rolunico']."&usuario=".Yii::app()->user->name."&origen=sao";
			$url='https://agenda.minpublico.cl/app/ficha/#/fichaCaso?cle='.base64_encode($parametros);	
			echo "<td><font color='#3433FF'>".CHtml::link($ruc_caso, $url, array('target'=>'_blank'))."</font></td>";
			echo "<td>".$doc['audiencia']."</td>"; 	
			echo "<td>".date("d-m-Y", strtotime($fecha_aud))."</td>";	
			$ida=$doc['idf_rolunico'];
			$id=$doc['cod_carpaud'];
			$id_carp=$doc['cod_carpdig'];
			$id_resul=$doc['cod_carpdigresultado'];
			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/consultarCarpetaDigital&ruc=".$ida."'><span class='btn btn-warning'>Ver Causa</span></a></td>";
			echo "<td>";
			if($doc['cod_carpdigresultado'] == 0){
				echo "<input type='file' name=".$id."  id=".$id." onchange='adjuntarResultado(this.value, this.id)'>";
				echo "<td> </td>";
			}
			else{
				echo "<a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/DownloadDoc&id=".$id_resul."'><span class='btn btn-warning'>Descargar</span></a>";
				echo "<td> <span id='".$id_resul."' name='eliminar' class='btn btn-danger' onclick='elimResulAud(this.id)'>Elim Result.</span></td>";
			}			
			echo "</td>";	
			$n++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script src='../start/js/listado_cuatro_columnas_datatables.js' ></script>";

	}//FIN FUNCTION	



////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
/////////////////   LISTADO DE AUDIENCIAS TOP - CORTE /////////////////////////	
////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
	
	public function actionAudienciastopcorte(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	//$fun = Funcionario::model()->getFuncionariosTodos();
			$fis=Yii::app()->user->getState('fiscalia');
	    	//$fun = CuentaGenerica::model()->getCuentasFiscalesVigentes($fis);

	    	$this->render('AudienciasTopCorte');
	    }//FIN ELSE
	}//FIN FUNCION INDEX


public function actionListarCausaAudienciatop(){

		$fec_desde = $_POST['fec_ini'];
		$fis=Yii::app()->user->getState('fiscalia');
		$doc = CarpetaAudiencia::model()->getListadoAudienciatop($fec_desde,$fis);

		echo "<table id='listado_cuatro_columnas' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
            	<th>SALA</th>
            	<th>HORA</th>
				<th style='width: 85px'>RUC</th>
				<th>FISCAL</th>
				<th>AUDIENCIA</th>
				<th>FECHA AUDIENCIA</th>
				<th>VER CAUSA</th>
				<th>ADJUNTAR MINUTA</th>
				<th>ADJUNTAR RESULTADO</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($doc as $doc) {	
			
			$fecha=$doc['fec_registro'];
			$fecha_aud=$doc['fec_audiencia'];
			$ruc_caso=$doc['idf_rolunico'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$doc['salaa']."</td>";
			echo "<td>".$doc['horaaud2']."</td>"; 
			$parametros="ruc=".$doc['idf_rolunico']."&usuario=".Yii::app()->user->name."&origen=sao";
			$url='https://agenda.minpublico.cl/app/ficha/#/fichaCaso?cle='.base64_encode($parametros);	
			echo "<td><font color='#3433FF'>".CHtml::link($ruc_caso, $url, array('target'=>'_blank'))."</font></td>";
			echo "<td>".$doc['fiscal']."</td>"; 
			echo "<td>".$doc['audiencia']."</td>"; 	
			echo "<td>".date("d-m-Y", strtotime($fecha_aud))."</td>";	
			$ida=$doc['idf_rolunico'];
			$id=$doc['cod_carpaud'];
			$id_carp=$doc['cod_carpdig'];
			$id_resul=$doc['cod_carpdigresultado'];
			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/consultarCarpetaDigital&ruc=".$ida."'><span class='btn btn-warning'>Ver Causa</span></a></td>";

			echo "<td>";
			if($doc['cod_carpdig'] == 0){
				echo "<input type='file' name=".$id."  id=".$id." onchange='adjuntarMinuta(this.value, this.id)'>";
			}
			else{
				echo "<a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/DownloadDoc&id=".$id_carp."'><span class='btn btn-warning'>Descargar</span></a>";
			}			
			echo "</td>";

			echo "<td>";
			if($doc['cod_carpdigresultado'] == 0){
				echo "<input type='file' name=".$id."  id=".$id." onchange='adjuntarResultado(this.value, this.id)'>";
			}
			else{
				echo "<a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/DownloadDoc&id=".$id_resul."'><span class='btn btn-warning'>Descargar</span></a>";
			}			
			echo "</td>";	
			$n++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script src='../start/js/listado_cuatro_columnas_datatables.js' ></script>";

	}//FIN FUNCTION	





////////////////////////////LISTADO DE AUDIENCIAS PARA FLS MENOS TALCAHUANO   //////////////////


public function actionAudienciasFiscalia(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

			$fis=Yii::app()->user->getState('fiscalia');
	    	$sala=TgSalaaud::model()->getSalaAud();
	    	
	    	$this->render('AudienciasFiscalia', array('sala'=>$sala));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


public function actionListarAudienciasFiscalia(){

		$cod_sala =  $_POST['salaaud'];
		$fec_desde = $_POST['fec_ini'];

		$doc = CarpetaAudiencia::model()->getListadoAudienciasFiscalia($fec_desde,$cod_sala);


		echo "<table id='listado_cuatro_columnas' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
            	<th>HORA</th>
				<th style='width: 85px'>RUC</th>
				<th>FISCAL</th>
				<th>AUDIENCIA</th>
				<th>FECHA AUDIENCIA</th>
				<th>VER CAUSA</th>
				<th>ADJUNTAR MINUTA</th>
				<th> X </th>
				<th>ADJUNTAR RESULTADO</th>
				<th> X </th>
				
				
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($doc as $doc) {	
			
			$fecha=$doc['fec_registro'];
			$fecha_aud=$doc['fec_audiencia'];
			$ruc_caso=$doc['idf_rolunico'];
			echo "<tr>"; 
			echo "<td>".$n."</td>";
			echo "<td>".$doc['horaaud']."</td>"; 
			$parametros="ruc=".$doc['idf_rolunico']."&usuario=".Yii::app()->user->name."&origen=sao";
			$url='https://agenda.minpublico.cl/app/ficha/#/fichaCaso?cle='.base64_encode($parametros);	
			echo "<td><font color='#3433FF'>".CHtml::link($ruc_caso, $url, array('target'=>'_blank'))."</font></td>";
			echo "<td>".$doc['fiscal']."</td>"; 	
			echo "<td>".$doc['audiencia']."</td>"; 	
			echo "<td>".date("d-m-Y", strtotime($fecha_aud))."</td>";	
			$ida=$doc['idf_rolunico'];
			$id=$doc['cod_carpaud'];
			$id_carp=$doc['cod_carpdig'];
			$id_resul=$doc['cod_carpdigresultado'];
			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/consultarCarpetaDigital&ruc=".$ida."'><span class='btn btn-warning'>Ver Causa</span></a></td>";

			echo "<td>";
			if($doc['cod_carpdig'] == 0){
				echo "<input type='file' name=".$id."  id=".$id." onchange='adjuntarMinuta(this.value, this.id)'>";
				echo "<td>  </td>";
			}
			else{
				echo "<a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/DownloadDoc&id=".$id_carp."'><span class='btn btn-warning'>Descargar</span></a>";

				echo "<td> <span id='".$id_carp."' name='eliminar' class='btn btn-danger' onclick='elimMinAud(this.id)'>Elim Minuta</span></td>";

			}			
			
			echo "</td>";

			echo "<td>";
			if($doc['cod_carpdigresultado'] == 0){
				echo "<input type='file' name=".$id."  id=".$id." onchange='adjuntarResultado(this.value, this.id)'>";
				echo "<td>  </td>";
			}
			else{
				echo "<a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/DownloadDoc&id=".$id_resul."'><span class='btn btn-warning'>Descargar</span></a>";
			
				echo "<td> <span id='".$id_resul."' name='eliminar' class='btn btn-danger' onclick='elimResulAud(this.id)'>Elim Result.</span></td>";
			}			
			
			//$ida=$doc['cod_carpaud'];
			echo "</td>";	
			$n++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script src='../start/js/listado_cuatro_columnas_datatables.js' ></script>";

	}//FIN FUNCTION	


	public function actionEliminarMinutaAudiencia(){	
 		
 		
 		$id=$_POST['id'];
 		
 		$model=CarpetaDigital::model()->findByPk($id);
 		$modelaud=CarpetaAudiencia::model()->find('cod_carpdig='.$id.'');  //find('id=2')

		$elim=new EstadoCarpdigital;
 		$elim->cod_carpdig=$id;
 		$elim->cod_estadocarpdig=4;
 		$elim->fun_rut=Yii::app()->user->id;
		$elim->fec_registro=date('Y-m-d h:m:s'); 
		if($elim->save()){
			$model->cod_estadocarpdig=4;
			$model->ind_vigencia=0;
			$model->save();
			$modelaud->cod_carpdig=0;
			$modelaud->save();
 			//echo "ook borra minuta";	
 	 	}
	
	}//FIN FUNCION 

	public function actionEliminarResultadoAudiencia(){	
 		
 		
 		$id=$_POST['id'];
 		
 		$model=CarpetaDigital::model()->findByPk($id);
 		$modelaud=CarpetaAudiencia::model()->find('cod_carpdigresultado='.$id.'');  //find('id=2')

		$elim=new EstadoCarpdigital;
 		$elim->cod_carpdig=$id;
 		$elim->cod_estadocarpdig=4;
 		$elim->fun_rut=Yii::app()->user->id;
		$elim->fec_registro=date('Y-m-d h:m:s'); 
		if($elim->save()){
			$model->cod_estadocarpdig=4;
			$model->ind_vigencia=0;
			$model->save();
			$modelaud->cod_carpdigresultado=0;
			$modelaud->save();
 			//echo "ook borra minuta";	
 	 	}
	
	}//FIN FUNCION 


/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
////////////////////////  RESERVAR CAUSA  ////////////////////////////
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////

	public function actionReservarCausa(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$fun=Funcionario::model()->getFuncionariosTodos();

	    	$this->render('reservarCausa', array('fun'=>$fun));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionGuardarSeleccionFuncionario(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new FunTmp;
	    	$flag=false;

	        $transaction=$model->dbConnection->beginTransaction();   
	        try{  
	        		$model->fun_rut=$_POST['fun']; 
	        		$model->fun_responsable=Yii::app()->user->id;	        		
	        		if($model->save()){	     
        				$flag=true;
	        		}
        			else throw new Exception('Error, no se puede guardar funcionario seleccionado');	       		
        		
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
					'message'=> 'Registro guardado'
				));
			}//

	    }//FIN ELSE
	}//FIN FUNCION GUARDAR ASIGNACION


	public function actionListarSeleccionFuncionario(){		
 
	    	$rut=Yii::app()->user->id;
				$fun = FunTmp::model()->getSelectFun($rut);

				$result = "<table id='ingresos' class='table table-striped table-bordered display nowrap'>";			
				foreach ($fun as $fun) {	
					$result .= "<tr><td>".$fun['funcionario']."</td><td style='width: 35px;'> <span id='".$fun['cod_funtmp']."' name='eliminar' class='btn btn-warning' onclick='elimfun(this.id)'>X</span></td></tr>";
				}
				$result .= "<tr><td colspan='2' style='text-align: center;'> <span id='".$rut."' name='eliminar' class='btn btn-warning' onclick='elimfuntodo(this.id)'>Borrar lista</span></td></tr>";
				$result .= "</table>";

				echo json_encode(array(
					'status' => 'success',
					'message'=> $result
				));
	    
	}//FIN FUNCION GUARDAR ASIGNACION

	public function actionEliminarSeleccionFuncionario(){	
 
		$id=$_POST['id'];
		$this->loadFuncionariosTmp($id)->delete();	

		echo json_encode(array(
				'status' => 'success',
				'message'=> 'Registro eliminado'
			));
	}

	public function actionEliminarSeleccionFuncionarioTodos(){	
 
		$id=$_POST['id'];
		FunTmp::model()->deleteAll("fun_responsable ='" .$id . "'");

		echo json_encode(array(
			'status' => 'success',
			'message'=> 'Registros borrados'
		));
	}

	public function actionGuardarCausaReservada(){
		 if(Yii::app()->user->isGuest)
		 	$this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new PermisoReserva;
	    	$x=0;
	    	$flag=false;
	        $transaction=$model->dbConnection->beginTransaction();   
	        try{        	

	        	if( empty($_POST['ruc']) ) throw new Exception('Error, debe indicar un ruc válido.');

	        	$rut=Yii::app()->user->id;
				$fun = FunTmp::model()->getSelectFun($rut);			

				if(isset( $fun[0]->fun_rut )){

					foreach ($fun as $a => $value) {		        		

		        		$model->idf_rolunico=$_POST['ruc'];
		        		$model->fun_rut=$fun[$a]->fun_rut;  
		        		$model->fis_codigo=Yii::app()->user->getState('fiscalia');  
		        		$model->fun_responsable=Yii::app()->user->id;  
		        		$model->fec_registro=date('Y-m-d H:m:s'); 
		        		$model->ind_vigencia=1;

		        		if($model->save()){		        					
		        			$model=new PermisoReserva;
							$flag=true;
		        		}
        			}//FIN FOREACH FUNCIONARIOS

        			FunTmp::model()->deleteAll("fun_responsable ='" .Yii::app()->user->id . "'");

				}//FIN IF EXISTE ALMENOS 1 FUNCIONARIO SELECCIONADO
				else throw new Exception('Error, debe seleccionar minímo 1 funcionario para asignar permisos sobre causas reservadas.');

	        	$transaction->commit();
	        }

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
					'message'=> 'Registro guardado'
				));
			}//
	    }//fin else
	}//fin funcion


	//listar causas reservadas y funcionarios con permisos
	public function actionListarCausasReservadas(){

		$permiso=PermisoReserva::model()->getCausasReservadas();

		echo "<table id='listar_para_reservar' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>            	
				<th>RUC</th>
				<th>FUNCIONARIO</th>
				<th>FECHA</th>
				<th>X</th>
            </tr>
        </thead>";
		echo "<tbody>";	
		$i=0;			
		foreach ($permiso as $permiso) {	
			
			$fecha=$permiso['fec_registro'];

			echo "<tr>"; 
			echo "<td>".$permiso['idf_rolunico']."</td>"; 
			echo "<td>".$permiso['nombre']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";			
			$id=$permiso['cod_permisoreserva'];

			echo "<td> <span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimPermiso(this.id)'>X</span></td>";

			$i++;
		}		
		echo "</tbody>"; 
		echo "</table>";

		echo "<script src='../start/js/listar_doc_para_reservar.js' ></script>";

	}//fin funcion

	public function actionEliminarPermisosReservados(){	
 
		$id=$_POST['id'];
		$model=PermisoReserva::model()->findByPk($id);
		$model->ind_vigencia=0;

		if($model->save()){
			echo json_encode(array(
			'status' => 'success',
				'message'=> 'Registros borrados'
			));
		}//fin if
			
	}

	public function loadFuncionariosTmp($id){
		$model=FunTmp::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function actionReservarDocumento(){

		if(Yii::app()->user->isGuest)
		 	$this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
			$id=$_POST['id'];
			$model=CarpetaDigital::model()->findByPk($id);

			$reserva =new ReservaDocumento;
			$reserva->idf_rolunico=$model->idf_rolunico; 
			$reserva->cod_carpdig=$model->cod_carpdig;
			$reserva->ind_reserva=1; 
			$reserva->fun_rut=Yii::app()->user->id;
			$reserva->fec_registro=date('Y-m-d h:m:s'); 

			if($reserva->save()){
				$model->ind_reservado=1;
				$model->save();
			}	    	
	    }//fin else

	}

	public function actionEliminarReservaDocumento(){

		if(Yii::app()->user->isGuest)
		 	$this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$id=$_POST['id'];
			$model=CarpetaDigital::model()->findByPk($id);

			$reserva =new ReservaDocumento;
			$reserva->idf_rolunico=$model->idf_rolunico; 
			$reserva->cod_carpdig=$model->cod_carpdig;
			$reserva->ind_reserva=2; 
			$reserva->fun_rut=Yii::app()->user->id;
			$reserva->fec_registro=date('Y-m-d h:m:s'); 
			if($reserva->save()){
				$model->ind_reservado=0;
				$model->save();		
			}//fin save

	    }//fin else		
	}//fin function

	public function actionRevisarDocumento(){
		if(Yii::app()->user->isGuest)
		 	$this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$id=$_POST['id'];
			$model=CarpetaDigital::model()->findByPk($id);

			$revisar =new EstadoCarpdigital; 
			$revisar->cod_carpdig=$model->cod_carpdig;
			$revisar->cod_estadocarpdig=2; 
			$revisar->fun_rut=Yii::app()->user->id;
			$revisar->fec_registro=date('Y-m-d h:m:s'); 

			if($revisar->save()){
				$model->cod_estadocarpdig=2;
				$model->save();		
			}//fin save

	    }//fin else	
	}//fin function


////////////////////////////////////////////////////////////////////
/////////   LISTADO PARA REVISAR INFORMES  ////////////////////////

	public function actionRevisarInformes(){
		if(Yii::app()->user->isGuest)
		 	$this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$fiscal = CuentaGenerica::model()->getCuentasFiscales();

	    	$this->render('revisarInformes', array('fiscal'=>$fiscal));
	    }
	}//FIN FUNCION


	//listar informes pendientes de revisión
	public function actionListarInformes(){

		$fiscal= $_POST['fiscal'];
		$info=CarpetaDigital::model()->getInformesNoRevisados($fiscal);

		echo "<table id='listado_tres_columnas' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>            	
				<th>RUC</th>
				<th>FISCAL ASIG.</th>
				<th>DOCUMENTO</th>
				<th>FECHA REGISTRO</th>
				<th>VER</th>
				<th>REVISAR INFORME</th>
				<th>VER CAUSA</th>
				<th>DECRETAR TAREAS</th>
            </tr>
        </thead>";
		echo "<tbody>";	
		$i=0;			
		foreach ($info as $info) {	
			
			$fecha=$info['fec_actividad'];
			$ext = Yii::app()->baseUrl.'/images/iconos/pdf.png';

			echo "<tr>"; 
			echo "<td>".$info['idf_rolunico']."</td>"; 
			echo "<td>".$info['fiscal']."</td>"; 
			echo "<td>".$info['gls_nomdoc']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";			
			$id=$info['cod_carpdig'];
			$ruta=$info['gls_ruta'];
		
    		echo "<td><img id='".$ruta."' onclick='verCarpeta(this.id)' src='".$ext."'/></td>";

			echo "<td> <span id='".$id."' name='revisar' class='btn btn-danger' onclick='revisarDoc(this.id)'>No Revisado</span></td>";

			$id=$info['idf_rolunico'];

			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/consultarCarpetaDigital&ruc=".$id."'><span class='btn btn-warning'>Ver Causa</span></td>";

			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/RegistrarTarea&ruc_decreta=".$id."'><span class='btn btn-warning'>Decretar Tareas</span></a></td>";


			$i++;
		}		
		echo "</tbody>"; 
		echo "</table>";

		echo "<script src='../start/js/listado_tres_columnas_datatables.js' ></script>";

	}//fin funcion




////////////////////////////////////////////////////////////////////
/////////   LISTADO PARA REVIDSAR INFORMES  ////////////////////////

	public function actionSepararCarpeta(){
		if(Yii::app()->user->isGuest)
		 	$this->redirect(Yii::app()->createUrl('site/logout'));
	    else{	

	    	$this->render('separarCarpeta');
	    }
	}//FIN FUNCION


	public function actionDocumentosRuc(){

		$ruc=$_POST['ruc'];

		$doc = CarpetaDigital::model()->getDocumentosRucTodos($ruc);

		echo "<table id='listar_para_reservar' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>            	
				<th>CLASE</th>
				<th>FECHA</th>
				<th>DOCUMENTO</th>
				<th><input type='checkbox' class='checSeparar' onClick='MarcarTodosSeparar(this)' /></th>
            </tr>
        </thead>";
		echo "<tbody>";	
		$i=0;			
		foreach ($doc as $doc) {	
			
			$fecha=$doc['fec_registro'];

			echo "<tr>"; 
			echo "<td>".$doc['gls_clasedoc']."</td>"; 

			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			echo "<td>".$doc['gls_nomdoc']."</td>"; 
			$id=$doc['cod_carpdig'];

			echo "<td style='text-align:center'><input type='checkbox' class='checSeparar' name='separar[".$i."]' id='separar[".$i."]' value='".$id."'></td>";
			$i++;
		}		
		echo "</tbody>"; 
		echo "</table>";

		echo "<script src='../start/js/listar_doc_para_reservar.js' ></script>";
	}


	public function actionListarCausasSeparadas(){

		$doc = SepararCausas::model()->getSeparaciones();

		echo "<table id='listar_para_reservar' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>            	
				<th>RUC ORIGINAL</th>
				<th>RUC NUEVO</th>
				<th>FECHA</th>
				
            </tr>
        </thead>";
		echo "<tbody>";	
		$i=0;			
		foreach ($doc as $doc) {	
			
			$fecha=$doc['fec_registro'];

			echo "<tr>"; 
			echo "<td>".$doc['idf_rolunico_original']."</td>"; 
			echo "<td>".$doc['idf_rolunico']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			
			$id=$doc['cod_serpararcausa'];

			//echo "<td style='text-align:center'><input type='checkbox' class='checReservar' name='reservar[".$i."]' id='reservar[".$i."]' value='".$id."'></td>";
			$i++;
		}		
		echo "</tbody>"; 
		echo "</table>";

		echo "<script src='../start/js/listar_doc_para_reservar.js' ></script>";
	}	


	public function actionGuardarSepararCausa($ruc, $nuevo, $separa){
		 if(Yii::app()->user->isGuest)
		 	$this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new SepararCausas;
	    	$x=0;
	    	$flag=false;
	        $transaction=$model->dbConnection->beginTransaction();   
	        try{        	

	        	if( empty($ruc) ) throw new Exception('Error, debe indicar un ruc válido.');
	        	if( empty($nuevo) ) throw new Exception('Error, ruc nuevo debe ser válido.');
	        	if( empty($separa) ) throw new Exception('Error, debe seleccionar minímo 1 documento para la causa nueva.');

	        	$model->idf_rolunico=$nuevo;
	        	$model->idf_rolunico_original=$ruc;
	        	$model->fun_rut=Yii::app()->user->id;
	        	$model->fec_registro=date('Y-m-d h-m-s');
	        	$model->ind_vigencia=1;
	        	if($model->save()){
					$array = explode(",", $separa); 
		        	foreach ($array as $c => $value) {
		        		if($array[$c]<>"on" && $array[$c]<>""){

		        			$id=$array[$c];
		        			$carpeta=CarpetaDigital::model()->findByPk($id);	
		        			
		        			$sepa=new CarpetaDigital;
		        			$sepa->idf_rolunico=$nuevo;
		        			$sepa->fis_codigo=$carpeta->fis_codigo;
		        			$sepa->fec_actividad=$carpeta->fec_actividad;
		        			$sepa->cod_clasedoc=$carpeta->cod_clasedoc;
		        			$sepa->cod_estadocarpdig=$carpeta->cod_estadocarpdig;
		        			$sepa->gls_nomdoc=$carpeta->gls_nomdoc;
		        			$sepa->gls_ruta=$carpeta->gls_ruta;
		        			$sepa->fun_rut=Yii::app()->user->id;
		        			$sepa->fec_registro=date('Y-m-d h-m-s');
		        			$sepa->ind_reservado=$carpeta->ind_reservado;
		        			$sepa->ind_vigencia=$carpeta->ind_vigencia;
		        			if($sepa->save()){
		        				$flag=true;		        				
		        			}
		        			else throw new Exception('Error, no se pudo traspasar el archivo a la causa nueva');
		        		}//fin if
		        	}//fin foreach

	        	$transaction->commit();
	        	}//fin if save model        	

			    
	        }//fin commit

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
					'message'=> 'Cuasa separa con éxito'
				));
			}//
	    }//fin else
	}//fin funcion

	public function actionDownloadDoc($id){

		$carpeta=CarpetaDigital::model()->findByPk($id);

    	$src=file_get_contents($carpeta->gls_ruta);
            
        header("Content-Type: text/plain");
		header("Content-disposition: attachment; filename=".$carpeta->gls_nomdoc);
		header("Pragma: no-cache");						  
		echo $src;
		exit;   
	}
//FIN CONTROLADOR



///**************** ACTUALIZACIONES   ***********************////////////////////


		public function actionConsultarCarpetaDigitalizada($ruc= false){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
    	    	

	    	$this->render('ConsultarCarpetaDigitalizada', array('ruc'=>$ruc));
	    	}//FIN ELSE
		}//FIN FUNCION INDEX



	public function actionConsultarDocumentosDigRuc(){

		$ext = Yii::app()->baseUrl.'/images/iconos/pdf.png';
		$ruc=$_POST['ruc'];
		$parametros="ruc=".$ruc."&usuario=".Yii::app()->user->name."&origen=sao"; 
		$url='https://agenda.minpublico.cl/app/ficha/#/fichaCaso?cle='.base64_encode($parametros);	

		echo "<div style='float: left;margin-right: 10px;'><a href='".$url."' target='_blank'>
    	<span id='".$ruc."' name='fichacaso' class='btn btn-warning'>Ficha Caso</span></a>
    	</div>";

		echo "<div>
    	<span id='".$ruc."' name='descargarsaf' class='btn btn-danger' onClick='ActualizarDocSAF(this.id)'>Actualizar Docs SAF</span>
    	</div>";

		echo "<div>
    	<span id='".$ruc."' name='descargarpdf' class='btn btn-danger' onClick='descargarCarpetaSIAU(this.id)'>Descargar Carpeta SIAU <img src='".$ext."'/></span>
    	</div>";

		echo "<div>
    	<span id='".$ruc."' name='descargarpdf' class='btn btn-danger' onClick='descargarCarpetaSIAUfecha(this.id)'>Descargar Carpeta SIAU FECHA<img src='".$ext."'/></span>
    	</div>";

		echo "<div style='float: left;margin-right: 10px;'> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/consultaCasoDigital&ruc_decreta=".$ruc."' target='_blank'><span id='".$ruc."' name='diligencia' class='btn btn-warning'>Diligencias Decretadas</span></a>
    	</div>";

		echo "<div style='float: left;margin-right: 10px;'> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/RegistrarTareaAsignar&ruc_decreta=".$ruc."' target='_blank'><span id='".$ruc."' name='diligencia' class='btn btn-warning'>Asignar tareas Gestor</span></a>
    	</div>";
    	echo "<div style='float: left;margin-right: 10px;'> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/RegistrarTarea&ruc_decreta=".$ruc."' target='_blank'><span id='".$ruc."' name='diligencia' class='btn btn-warning'>Asignar tareas Bolsa Trabajo</span></a>
    	</div>";


	  	/*		echo "<div>
		<a href='".Yii::app()->getBaseUrl(true)."/index.php?r=CarpetaDigital/DescargarCarpetaSIAU&ruc=ruc' target='_blank'><span class='btn btn-danger'>Descargar Carpeta SIAU <img src='".$ext."'/></span></a>
    	</div>";*/

		
		$subido = CarpetaDigital::model()->getClasesRuc($ruc);

		/*$verificador = substr($ruc, -1);	
    	$path = "A:\\Digito".$verificador."\\".$ruc."\\";
    	$ruta = "http://172.17.126.15:88/Vigentes/Digito".$verificador."/".$ruc;    	

    	if( is_dir($path) ){
    		$dir=opendir($path);
    		$carpeta = array();   
	    	$i=0;   	

	    	while($temp = readdir($dir)){
	    		//echo $temp."<br>";
	    		if($temp <> '.' && $temp <> '..'){	    			
		    		$carpeta[$i]['nombre'] = utf8_encode($temp);
		    		$carpeta[$i]['ruta'] = utf8_encode($ruta.'/'.$temp);
		    		$i++;
	    		}
	    	}//fin while
    	}//fin if dir */

		echo "<div class='col-md-8' id='listadocu'> 
			<main>";
			echo "<br><p>1- Ficha caso: para visualizar la ficha directamente, previamente debes ingresar tus <strong>credenciales en Ficha Caso</strong>";
			echo "<br><p>2- Puedes <strong>excluir temporalmente un documento de la copia SIAU</strong> con el botón <span class='btn btn-warning'>Excluir documento</span>.";

			 if(isset($subido)){ 			 	
			 	$n=0;
			 	foreach ($subido as $subido) {

			 		if ($subido['privacidad']==1){$ind='(Público)';}	
			 		if ($subido['privacidad']==2){$ind='(Interno)';}	
			 		
			 		echo "<details>";
			 			echo "<summary>".$subido['gls_clasedoc']." ".$ind."</summary>";

			 			echo "<div class='faq__content'>";

			 			$clase=$subido['cod_clasedoc'];
			    		$document = CarpetaDigital::model()->getDocumentosRuc($ruc, $clase);

			    		echo "<table table id='listarea' class='table doc_digital' cellspacing='0' width='100%'>
				    		<tbody>";				    		
				    		foreach ($document as $doc) {					
								echo "<tr>"; 
								$ext = Yii::app()->baseUrl.'/images/iconos/pdf.png';


								if ($doc['cod_clasedoc'] <> 21) 
								{
						        echo "<td style=' width: 40px;' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)' ><img src='".$ext."'/></td>"; 
						        echo "<td style=' width: 80px;'>".date("d-m-Y", strtotime($doc['fec_actividad']))."</td>"; 
								echo "<td><a href='#' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)' >".$doc['gls_nomdoc']."</a></td>"; 


								if($doc['cod_clasedoc'] == 12){

									if($doc['cod_estadocarpdig'] == 1){
										echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-danger' onClick='revisarDoc(this.id)'>No revisado</span></td>";
									}
									else{
										echo "<td><div style='color: #0e7c17; font-weight: 600;font-size: 15px;'><img src='".Yii::app()->baseUrl."/images/iconos/cheque.png'/>Revisado<div></td>";
									}// fin else informe revisado
								}// fin if clase doc es informes

								if($doc['ind_reservado'] == 1){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-danger' onClick='elimReserva(this.id)'>Eliminar exclusión</span></td>"; 
								}
								else{
									if ($subido['privacidad']==1){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-warning' onClick='reservarDoc(this.id)'>Excluir documento</span></td>"; 
									}								
								}
								// ind_favorito 		 0: sin marcar  1: favorito
								if($doc['ind_favorito'] == 0){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-warning' onClick='MarcarFavorito(this.id)'>Marcar Favorito</span></td>"; 
								}
								else{
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-danger' onClick='NoMarcarFavorito(this.id)'>Desmarcar Favorito</span></td>"; 
									}	

								}
								else
								{
								$act=$doc['tip_actividad'];
								$sact=$doc['tip_subtipactividad'];
								$ssact=$doc['tip_subsubtipactividad'];

								$actividad = TgActividadsaf::model()->getDocumentosActividadRuc($act,$sact,$ssact);
 								
 								echo "<td style=' width: 40px;' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)' ><img src='".$ext."'/></td>"; 
						        echo "<td style=' width: 80px;'>".date("d-m-Y", strtotime($doc['fec_actividad']))."</td>"; 
								//echo "<td><a href='#' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)' >".$doc['gls_nomdoc']."</a></td>"; 
								
						        foreach ($actividad as $activ) {
								if ($activ['cod_judicial']==1){$indj='Judicial';}	
			 					if ($activ['cod_judicial']==2){$indj='No Judicial';}
								echo "<td><a href='#' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)'>".$activ['gls_actividad'].' - '.$activ['gls_subtipactividad'].' - '.$activ['gls_subsubtipactividad']."</a></td>"; 
								echo "<td style=' width: 50px;'>".$indj."</td>"; 
								}
								
								if($doc['ind_reservado'] == 1){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-danger' onClick='elimReserva(this.id)'>Eliminar exclusión</span></td>"; 
								}
								else{
									if ($subido['privacidad']==1){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-warning' onClick='reservarDoc(this.id)'>Excluir documento</span></td>"; 
									}								
								}

								if($doc['ind_favorito'] == 0){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-warning' onClick='MarcarFavorito(this.id)'>Marcar Favorito</span></td>"; 
								}
								else{
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-danger' onClick='NoMarcarFavorito(this.id)'>Desmarcar Favorito</span></td>"; 
									}	

								}

								echo "</tr>"; 

							}  // fin busca documentos todos de RUC	



				    	echo "</tbody>  	
				    	 </table>";

			 			echo "</div>";

			 		echo "</details>";
			 		$n++;
			 	}//FIN FOREACH DOCUMENTOS
			 	if($n == 0){
			 		
			 		echo "<h4>No se encontraron documentos digitales</h4>";
			 		
			 	}
			 }//FIN EXISTEN DOCUMENTOS
			
    

		if($n <> 0){
			/*echo "</main>
			</div>
			<div class='col-md-6' id='visualizarDocu' style='padding-left: 25px;'> 
				<div id='verpdf'>
		    		
		    	</div>
			</div>	
			"; */
		}
		

	}



public function actionMarcarFavoritoDoc(){

		if(Yii::app()->user->isGuest)
		 	$this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
			$id=$_POST['id'];
			$model=CarpetaDigital::model()->findByPk($id);

			$reserva =new FavoritoDocumento;
			$reserva->idf_rolunico=$model->idf_rolunico; 
			$reserva->cod_carpdig=$model->cod_carpdig;
			$reserva->ind_favorito=1; /// se marca favorito
			$reserva->fun_rut=Yii::app()->user->id;
			$reserva->fec_registro=date('Y-m-d h:m:s'); 

			if($reserva->save()){
				$model->ind_favorito=1;
				$model->save();
			}	    	
	    }//fin else

	}

	public function actionNoMarcarFavoritoDoc(){

		if(Yii::app()->user->isGuest)
		 	$this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$id=$_POST['id'];
			$model=CarpetaDigital::model()->findByPk($id);

			$reserva =new FavoritoDocumento;
			$reserva->idf_rolunico=$model->idf_rolunico; 
			$reserva->cod_carpdig=$model->cod_carpdig;
			$reserva->ind_favorito=2; /// quita la marca de favorito
			$reserva->fun_rut=Yii::app()->user->id;
			$reserva->fec_registro=date('Y-m-d h:m:s'); 
			if($reserva->save()){
				$model->ind_favorito=0;
				$model->save();		
			}//fin save

	    }//fin else		
	}//fin function



		public function actionConsultarCarpetaDigitalFavoritos($ruc){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
    	    	
	    	$this->render('ConsultarCarpetaDigitalFavoritos', array('ruc'=>$ruc));
	    	}//FIN ELSE
		}//FIN FUNCION INDEX



	public function actionConsultarDocumentosFavoritos(){

		$ext = Yii::app()->baseUrl.'/images/iconos/pdf.png';
		$ruc=$_POST['ruc'];
		$parametros="ruc=".$ruc."&usuario=".Yii::app()->user->name."&origen=sao"; 
		$url='https://agenda.minpublico.cl/app/ficha/#/fichaCaso?cle='.base64_encode($parametros);	

		echo "<div style='float: left;margin-right: 10px;'><a href='".$url."' target='_blank'>
    	<span id='".$ruc."' name='fichacaso' class='btn btn-warning'>Ficha Caso</span></a>
    	</div>";

		echo "<div style='float: left;margin-right: 10px;'> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/consultaCasoDigital&ruc_decreta=".$ruc."' target='_blank'><span id='".$ruc."' name='diligencia' class='btn btn-warning'>Diligencias Decretadas</span></a>
    	</div>";

		echo "<div style='float: left;margin-right: 10px;'> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/RegistrarTareaAsignar&ruc_decreta=".$ruc."' target='_blank'><span id='".$ruc."' name='diligencia' class='btn btn-warning'>Asignar tareas Gestor</span></a>
    	</div>";
    	echo "<div style='float: left;margin-right: 10px;'> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/RegistrarTarea&ruc_decreta=".$ruc."' target='_blank'><span id='".$ruc."' name='diligencia' class='btn btn-warning'>Asignar tareas Bolsa Trabajo</span></a>
    	</div>";
		
		$subido = CarpetaDigital::model()->getClasesRucFavoritos($ruc);

		echo "<div class='col-md-8' id='listadocu'> 
			<main>";
			echo "<br><p>1- Ficha caso: para visualizar la ficha directamente, previamente debes ingresar tus <strong>credenciales en Ficha Caso</strong>";

			 if(isset($subido)){ 			 	
			 	$n=0;
			 	foreach ($subido as $subido) {

			 		//if ($subido['privacidad']==1){$ind='(Público)';}	
			 		//if ($subido['privacidad']==2){$ind='(Interno)';}	
			 		
			 	//	echo "<details>";
			 			//echo "<summary>".$subido['gls_clasedoc']." ".$ind."</summary>";

			 	//		echo "<div class='faq__content'>";

			 			$clase=$subido['cod_clasedoc'];
			    		$document = CarpetaDigital::model()->getDocumentosFavoritos($ruc, $clase);

			    		echo "<table table id='listarea' class='table doc_digital' cellspacing='0' width='100%'>
				    		<tbody>";				    		
				    		foreach ($document as $doc) {					
								echo "<tr>"; 
								$ext = Yii::app()->baseUrl.'/images/iconos/pdf.png';

								$clased=$subido['gls_clasedoc'];
								if ($doc['cod_clasedoc'] <> 21) 
								{
						        echo "<td style=' width: 40px;' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)' ><img src='".$ext."'/></td>"; 
						        echo "<td style=' width: 40px;' id='".$clased."' </td>"; 
						        echo "<td style=' width: 80px;'>".date("d-m-Y", strtotime($doc['fec_actividad']))."</td>"; 
								echo "<td><a href='#' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)' >".$doc['gls_nomdoc']."</a></td>"; 

							// ind_favorito 		 0: sin marcar  1: favorito
								/*
								if($doc['ind_favorito'] == 0){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-warning' onClick='MarcarFavorito(this.id)'>Marcar Favorito</span></td>"; 
								}
								else{
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-danger' onClick='NoMarcarFavorito(this.id)'>Desmarcar Favorito</span></td>"; 
									}	

								*/	

								}
								else
								{
								$act=$doc['tip_actividad'];
								$sact=$doc['tip_subtipactividad'];
								$ssact=$doc['tip_subsubtipactividad'];

								$actividad = TgActividadsaf::model()->getDocumentosActividadRuc($act,$sact,$ssact);
 								
 								echo "<td style=' width: 40px;' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)' ><img src='".$ext."'/></td>"; 
 								echo "<td style=' width: 40px;' id='".$clased."' </td>";
						        echo "<td style=' width: 80px;'>".date("d-m-Y", strtotime($doc['fec_actividad']))."</td>"; 
								//echo "<td><a href='#' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)' >".$doc['gls_nomdoc']."</a></td>"; 
								
						        foreach ($actividad as $activ) {
								if ($activ['cod_judicial']==1){$indj='Judicial';}	
			 					if ($activ['cod_judicial']==2){$indj='No Judicial';}
								echo "<td><a href='#' id='".$doc['gls_ruta']."' onClick='verCarpeta(this.id)'>".$activ['gls_actividad'].' - '.$activ['gls_subtipactividad'].' - '.$activ['gls_subsubtipactividad']."</a></td>"; 
								echo "<td style=' width: 50px;'>".$indj."</td>"; 
								}
								
								/*
								if($doc['ind_favorito'] == 0){
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-warning' onClick='MarcarFavorito(this.id)'>Marcar Favorito</span></td>"; 
								}
								else{
									echo "<td><span id='".$doc['cod_carpdig']."' class='btn btn-danger' onClick='NoMarcarFavorito(this.id)'>Desmarcar Favorito</span></td>"; 
									}	
									*/

								}   ///else

								echo "</tr>"; 

							}  // fin busca documentos todos de RUC	



				    	echo "</tbody>  	
				    	 </table>";

			 	//		echo "</div>";

			 	//	echo "</details>";
			 		$n++;
			 	}//FIN FOREACH DOCUMENTOS
			 	if($n == 0){
			 		
			 		echo "<h4>No se encontraron documentos FAVORITOS</h4>";
			 		
			 	}
			 }//FIN EXISTEN DOCUMENTOS
			
    

		if($n <> 0){
			/*echo "</main>
			</div>
			<div class='col-md-6' id='visualizarDocu' style='padding-left: 25px;'> 
				<div id='verpdf'>
		    		
		    	</div>
			</div>	
			"; */
		}
		

	}



/////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////  CAUSAS ASIGNADAS SIN REVISION FISCAL  /////////////////////////	
/////////////////////////////////////////////////////////////////////////////////////////////		/////////////////////////////////////////////////////////////////////////////////////////////

	public function actionCausasAsignadasFiscal(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	//$fun = Funcionario::model()->getFuncionariosTodos();
			$fis=Yii::app()->user->getState('fiscalia');
	    	$fun = CuentaGenerica::model()->getCuentasFiscalesVigentes($fis);

	    	$this->render('CausasAsignadasFiscal', array('fun'=>$fun));
	    }//FIN ELSE
	}//FIN FUNCION INDEX





////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////   LISTAR CAUSAS ASIGNADAS SIN REVISION  FISCAL  ////////////////////////////


	public function actionListarCausasAsignadasFiscal(){
		
		$rut_fiscal=$_POST['fun'];
		//$fec_ini=$_POST['fec'];
		///$fec_fin=$_POST['fec_fin'];
		$ta = CausaVigente::model()->getListadoVigAsignadas($rut_fiscal);

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th>RUC</th>
				<th>FECHA ASIGNACION</th>
				<th>FECHA RECEPCION</th>
				<th>ULTIMA ACTIVIDAD</th>
				<th>FECHA ACTIVIDAD</th>
				<th>JUDICIALIZADA</th>
				<th>REVISAR CAUSA</th>
				<th>VER CAUSA</th>
				<th>ASIGNAR TAREAS GESTOR</th>
				<th>ASIGNAR TAREAS BT</th>
				<th>VER DILIGENCIAS DECRETADAS</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($ta as $ta) {	
			$fecha_recep=$ta['fec_recepcion'];
			$fecha_asig=$ta['fec_asig'];
			$fecha=$ta['fec_ult_tramitacion'];
			$ruc_caso= $ta['idf_rolunico'];
			
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			//echo "<td>".$ta['fiscal']."</td>"; 
			//echo "<td>".$ta['idf_rolunico']."</td>";

			$parametros="ruc=".$ta['idf_rolunico']."&usuario=".Yii::app()->user->name."&origen=sao";
 					//echo $parametros;
			$url='https://agenda.minpublico.cl/app/ficha/#/fichaCaso?cle='.base64_encode($parametros);	
			echo "<td><font color='#3433FF'>".CHtml::link($ruc_caso, $url, array('target'=>'_blank'))."</font></td>";

			echo "<td><font color='#3433FF'>".date("d-m-Y", strtotime($fecha_asig))."</font></td>";
			echo "<td>".date("d-m-Y", strtotime($fecha_recep))."</td>";
			//echo "<td>".$ta['cuenta_nombre']."' '".$ta['cuenta_apellido']."</td>"; 
			echo "<td>".$ta['gls_tipactividad']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			//echo "<td>".$ta['ind_jud']."</td>"; 
			if  ($ta['ind_jud']==1){
			echo "<td> Judicializada</td>";}
			else{
			echo "<td>No Judicializada</td>";}
			//if  ($ta['gls_tipactividad']<> ""){
			//echo "<td> <font color='#3433FF'>Con Actividad </font></td>";}
			//else{
			//echo "<td>Sin Actividad</td>";}
			
			$id=$ta['idf_rolunico'];
			$idc=$ta['idcarpdig'];
			
			echo "<td> <span id='".$idc."' name='revisar' class='btn btn-danger' onclick='RevcausaFiscal(this.id)'>No Revisado</span></td>";

			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/consultarCarpetaDigital&ruc=".$id."' target='_blank'><span class='btn btn-warning'>Ver Causa</span></td>";

			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/RegistrarTareaAsignar&ruc_decreta=".$id."' target='_blank'><span class='btn btn-warning'>Tareas Gestor</span></a></td>";
			
			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/RegistrarTarea&ruc_decreta=".$id."' target='_blank'><span class='btn btn-warning'>Tareas BT</span></a></td>";

			echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=BancoTarea/consultaCasoDigital&ruc_decreta=".$id."' target='_blank'><span class='btn btn-warning'>Ver Diligencias</span></a></td>";
			$n++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					
						'columnDefs': [
			            {
			                'targets': [ 0 ],
			                'visible': false,
			                'searchable': false
			            },
			        ],
			        
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
					},
			        dom: 'Bfrtip',
					buttons: [					
						{
							extend: 'print',
							text: 'Imprimir',
				            exportOptions: {
				                stripHtml: false,
				                columns: [ 0, 1, 2, 3, 4, 5 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	


  // REGISTRO DE LA FECHA CUANDO SE DA POR REVISADA UNA CAUSA ASIGNADA //////

	public function actionRevisarCausaFiscal(){
		
		if(Yii::app()->user->isGuest)
		 	$this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$id=$_POST['id'];
			$model=CarpetaDigital::model()->findByPk($id);

			$revisar =new Revcausafiscal; 
			$revisar->cod_carpdig=$model->cod_carpdig;
			$revisar->cod_estadorevfiscal=1; 
			$revisar->fun_rut=Yii::app()->user->id;
			$revisar->fec_registro=date('Y-m-d h:m:s'); 

			if($revisar->save()){
				$model->ind_revfiscal=1;
				$model->save();		
			}//fin save

	    }//fin else	
	}//fin function





//////////////////****************  ALMACENAMIENTO DIGITALIZADORES  01/08/2021   ***************/////////////////////////////////


public function actionGuardarDocumentosDigitales(){		
		//require_once '../start/protected/views/conexion_saf.php';

	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$clased=TgClasedoc::model()->findAll('cod_clasedoc not in (21,25,26,27,28,29,30,31) order by ind_orden asc');

	    	$this->render('guardardocumentosdigitales', array('clased'=>$clased));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionUploadDocumentoDigital($ruc, $clase){
		
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

		    	$fis=Yii::app()->user->getState('fiscalia');

		    	$ext = explode("-", $ruc);	
		    	$digito=$ext[1];
		    	$preruta="\\\\EIVG-VIII\\WEB\\F".$fis."\\Digito".$digito."\\".$ruc;
		    	//$preruta="\\\\172.17.123.241\\f".$fis."\\Digito".$digito."\\".$ruc;

		    	if(!file_exists($preruta)){		    		
		    		mkdir($preruta, 0777);
		    	}

		    	//if($clase<>10){
		    		$tipo = TgClasedoc::model()->findByPk($clase);
		    		$nombre=$_FILES['arr']['name'][$i];
		    		$nombre=utf8_encode($nombre);
		    		$ruta=$preruta.'\\' .$tipo->gls_clasecodigo.'_'.$nombre;
		    		//$ruta=$preruta.'\\' .$ruc.'_'.$tipo->gls_clasecodigo.'_'.$nombre;
		    	//}
		    	//else{
		    	//	$ruta=$preruta.'\\' .$ruc.'.pdf';
		    	//} 
		    
		    	
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
		    	//$ruta='\\\\172.17.123.241\\f803\\' .$_FILES['arr']['name'][$i];	      
		        	       	
				
		    }//fin for
		}
	}//FIN FUNCTION


	public function actionGuardarDocumentoDigital(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new CarpetaDigital;
	    	$flag=false;	    
	        $transaction=$model->dbConnection->beginTransaction();   
	        try{  

	        	if( empty($_POST['ruc']) ) throw new Exception('Error, debes indicar un ruc válido');
	        	if( empty($_POST['clase']) ) throw new Exception('Error, debes indicar una clasificación del documento');
	        	if( empty($_POST['adjunto1']) ) throw new Exception('Error, debes adjuntar un documento');


				$model->idf_rolunico=$_POST['ruc'];

				$model->fec_actividad=$_POST['fec_actividad'];  /** revisar en BD ***/

				$model->cod_clasedoc=$_POST['clase'];
				if(isset($_POST['nuevo'])){
					$model->cod_estadocarpdig=1;	
				} 
				else{
					$model->cod_estadocarpdig=3;	
				} 
				if(isset($_POST['control'])){
					$model->cod_control=1;	

				} 
				
				if(isset($_POST['prior'])){
					$model->cod_prioritario=1;	
				} 
				$file = $_POST['adjunto1']; 
				$array_file = explode("\\", $file);	

				$ruta = "http://".$array_file[2]."/".$array_file[4]."/".$array_file[5]."/".$array_file[6]."/".$array_file[7];
				$nombre_doc = $array_file[7];

				$model->gls_nomdoc=$nombre_doc;
				$model->gls_ruta=$ruta;
				$model->fis_codigo=Yii::app()->user->getState('fiscalia');
				$model->ind_vigencia=1;
				$model->ind_revfiscal=1;
				$model->fun_rut=Yii::app()->user->id;
				$model->fec_registro=date('Y-m-d h:m:s'); 

				if($model->save()){
					$flag=true;
				}
				else throw new Exception('Error al guardar Parte');	
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
					'message'=> 'Parte guardado con éxito'
				));
			}

		


	    }//FIN ELSE
	}//FIN FUNCION GUARDAR



////////////////////  FIN ALMACENAMIENTO DOGOTALIZADORES  *******////////////////////////


/**
	* Método actionConsultaAvanzada que realiza la busqueda personalizada de las carpetas digitales según parametros enviados por el usuario.
	* @author Freddy
	* @version 26-08-2021
	*/

	public function actionConsultaAvanzada()
	{
		if(Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->createUrl('site/logout'));
		else{
			#Obtenemos las clases documentales
			$claseDocumental=TgClasedoc::model()->findAll('cod_clasedoc not in (26,27,28,29,30,31) order by CAST(ind_orden AS INTEGER) asc');
			if(isset($_POST["cmd"]) && $_POST["cmd"] == "buscar")
			{
				#Si el usuario presiono "Consultar", se realiza el proceso de búsqueda
				$model = new CarpetaDigital;
				$respuesta = $model->getConsultaAvanzada($_POST);

				$table = "<table id='consultaAvanzada' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>";
				$table.="<thead align='left'>
				<tr>
					<th style='text-align:center;vertical-align:middle;'>RUC</th>
					<th style='text-align:center;vertical-align:middle;'>Clase Documental</th>
					<th style='text-align:center;vertical-align:middle;'>Fecha</th>
					<th style='text-align:center;vertical-align:middle;'>Nombre Documento</th>
					<th style='text-align:center;vertical-align:middle;'>Alias</th>
					<th style='text-align:center;vertical-align:middle;'>Ver Documento</th>
				</tr>
			</thead>";

			$table.='<tbody>';
			foreach($respuesta as $key):
			$table.='<tr>';
			$table.='<td style="text-align:center;vertical-align:middle;">'.$key->idf_rolunico.'</td>';
			$table.='<td style="text-align:center;vertical-align:middle;">'.$key->gls_clasedoc.'</td>';
			$table.='<td style="text-align:center;vertical-align:middle;">'.date("d-m-Y",strtotime($key->fec_actividad)).'</td>';
			$table.='<td style="text-align:left;vertical-align:middle;">'.$key->gls_nomdoc.'</td>';
			$table.='<td style="text-align:center;vertical-align:middle;">'.$key->gls_alias.'</td>';
			$table.='<td style="text-align:center;vertical-align:middle;"><a href="'.$key->gls_ruta.'" target="_blank"><img src="'.Yii::app()->baseUrl.'/images/iconos/pdf.png"></a></td>';
			
			$table.='</tr>';
			endforeach;
			$table.='</tbody>';
			$table.='</table>';
			

			$table.= "<script type='text/javascript'>
			$( document ).ready(function() {
				$('#consultaAvanzada').DataTable().destroy();
				var table = $('#consultaAvanzada').DataTable( {
					
						'columnDefs': [
			            {
			                'visible': false,
			                'searchable': true
			            },
			        ],
			        
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
					},
			        dom: 'Bfrtip',
					buttons: [					
						{
							extend: 'print',
							text: 'Imprimir',
				            exportOptions: {
				                stripHtml: false,
				                columns: [ 0, 1]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1]
							}
						}
	                ]

				} );
			});    

		</script>";
		echo $table;
		}else{
			$this->render("consultaAvanzada",["claseDocumental" => $claseDocumental]);
		}
	}
	}







}