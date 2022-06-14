<?php

class TestController extends Controller{

	public $layout='//layouts/column2';

	public function filters(){
		return array('accessControl', 'postOnly + delete',);
	}

		public function actionTest()
	{
		$model=new LoginForm;
		$this->render('test', array("model"=>$model));		
	}


	public function actionIndex(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{



	    	$this->render('index');
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionPDF(){    

		 $file1 = "http://172.17.123.241/F803/Digito4/2100241252-4/2100241252-4.pdf";
	     $file2 = "http://172.17.123.241/F803/Digito4/2100241252-4/2100241252-4.pdf";

	     $html = "THIS IS A ONE PAGE PDF";

	     $mPDF1 = Yii::app()->ePdf->mpdf('utf-8','A4','','',15,5,5,5,9,9,'P'); //Esto lo pueden configurar como quieren, para eso deben de entrar en la web de MPDF para ver todo lo que permite.
	     $mPDF1->useOnlyCoreFonts = true;
	     $mPDF1->SetTitle("Antencedentes para fijación de Audiencia CONTROL DE DETENCIÓN");
	     $mPDF1->SetAuthor("PRAI-FR"); 
	     $mPDF1->showWatermarkText = true;
	     $mPDF1->watermark_font = 'DejaVuSansCondensed';
	     $mPDF1->watermarkTextAlpha = 0.1;
	     $mPDF1->SetDisplayMode('fullpage');
	     $mPDF1->SetFooter(' {DATE j/m/Y}|Página {PAGENO}|Nombre cualquiera');  

	     $mPDF1->WriteHTML(utf8_encode($html));
	     $mPDF1->AddPage();
		 $mPDF1->SetImportUse();

	     $file = "http://172.17.123.241/F803/Digito4/2100241252-4/2100241252-4.pdf";
		 $pagecount = $mPDF1->SetSourceFile($file);
		 $tplId = $mPDF1->ImportPage($pagecount);
		 $mPDF1->UseTemplate($tplId);

	     $mPDF1->Output('UNIRPDF','I'); 
	     exit;  

	}


	public function actionDescargarDoc($id){
	
		$ruta=$id;
		//echo var_dump($ruta);
		
		$src=file_get_contents($ruta);		
		         
        header("Content-Type: text/plain");
		header("Content-disposition: attachment; filename=".$ruta);
		header("Pragma: no-cache");						  
		echo $src;
		exit;            
	}  


	public function actionVerDocumento(){

		$id = $_POST['id'];

		echo '<object data="'.$id.'#view=FitH" type="application/pdf"  style="width: 100%;    min-height: 900px;" >
			<embed src="'.$id.'#view=FitH" type="application/pdf" style="width: 100%;" />
			</object>';



	}

	////////////************* Agregar Minuta a la Audiencia  ////////////////

	public function actionAgregarMinuta($id){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	
			$idaud = CarpetaAudiencia::model()->getAudienciaMinuta($id);
			$clased=TgClasedoc::model()->getDocMinuta();
			//$dup = EivgInterviniente::model()->getDupla();     
			$this->render('AdjuntarMinuta', array('idaud'=>$idaud,'clased'=>$clased));	     
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionGuardarDatosMinuta(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new CarpetaDigital;
	    	$flag=false;	    
	        $transaction=$model->dbConnection->beginTransaction();   
	        try{  

	        	if( empty($_POST['ruc']) ) throw new Exception('Error, debes indicar un ruc válido');
	        	//if( empty($_POST['clase']) ) throw new Exception('Error, debes indicar una clasificación del documento');
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
				else throw new Exception('Error al guardar Minuta');	
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
					'message'=> 'Minuta incorporada con éxito'
				));
			}

	    }//FIN ELSE
	}//FIN FUNCION GUARDAR


/****   NO SE OCUPARA POR AHORA   *///////
public function actionVerDocumentosAudiencia(){

		$doc = CarpetaDigital::model()->getPartesCargados();

		echo "<table id='listadoc' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width: 85px'>RUC</th>
				<th>CLASE</th>
				<th>FECHA SUBIDA</th>
				<th>RESPONSABLE</th>
				<th>VER</th>
				<th>X</th>
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

			echo "<td><a href='".$ruta."'><img src='".Yii::app()->baseUrl."/images/iconos/pdf.png'/></a></td>";

			echo "<td> <span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimDoc(this.id)'>X</span></td>";

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


	public function actionUploadMinuta($ruc){	
		
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
		    $fecha = date('d-m-Y');
		 
		    for ($i = 0; $i < $count; $i++) { 

		    	$fis=Yii::app()->user->getState('fiscalia');
		    	$ext = explode("-", $ruc);	
		    	$digito=$ext[1];
		    	$preruta="\\\\172.17.123.241\\WEB\\F".$fis."\\Digito".$digito."\\".$ruc;

		    	if(!file_exists($preruta)){		    		
		    		mkdir($preruta, 0777);
		    	}

		    	$clase=24;

		    	$tipo = TgClasedoc::model()->findByPk($clase);
		    	$nombre=$_FILES['arr']['name'][$i];
		    	$nombre=$fecha.'_'.utf8_encode($nombre);
		    	$ruta=$preruta.'\\' .$ruc.'_'.$tipo->gls_clasecodigo.'_'.$nombre;	    
		    	

		    	$model=new CarpetaDigital;
				$model->idf_rolunico=$ruc;
				$model->fec_actividad=date('Y-m-d'); 
				$model->cod_clasedoc=$clase;
				$model->cod_estadocarpdig=1;

				$array_file = explode("\\", $ruta);	

				$ruta = "http://".$array_file[2]."/".$array_file[4]."/".$array_file[5]."/".$array_file[6]."/".$array_file[7];
				$nombre_doc = $array_file[7];

				$model->gls_nomdoc=$nombre_doc;
				$model->gls_ruta=$ruta;
				$model->fis_codigo=Yii::app()->user->getState('fiscalia');
				$model->ind_vigencia=1;
				$model->fun_rut=Yii::app()->user->id;
				$model->fec_registro=date('Y-m-d h:m:s'); 

				if($model->save()){					
		    		$ruta=utf8_decode($ruta);	
			        move_uploaded_file($_FILES['arr']['tmp_name'][$i], $ruta);			       

		    		echo json_encode(array(
					    'status' => 'success',
					    'message'=> $ruta
					));
				}
				else{
					echo json_encode(array(
						'status' => 'error',
						'message'=> '#error al intentar guardar minuta'
					));
				}//finelse        	
				
		    }//fin for
		}
	}//FIN FUNCTION

//FIN CONTROLADOR
}