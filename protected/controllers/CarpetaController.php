<?php

date_default_timezone_set('America/Santiago'); 

class CarpetaController extends Controller{

	public $layout='//layouts/column2';

	public function filters(){
		return array('accessControl', 'postOnly + delete',);
	}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////          UBICACIÓN DE CARPETA        ///////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




	public function actionCarpetaTerminada(){		

		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$fun = Funcionario::model()->getFuncionariosTodos();

	    	$this->render('carpetaTerminada', array('fun'=>$fun));
	    }//FIN ELSE
	}//FIN FUNCION INDEX



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionValidarRuc(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$tipu=TgTipubicacion::model()->findAll('');
	    	$ubi=TgUbicacioncarp::model()->findAll('fis_codigo="'.Yii::app()->user->getState('fiscalia').'" and ind_vigencia=1 ');
			$cas=TgCasillero::model()->findAll('ind_vigencia=1');

	    	$this->render('validarRuc', array('tipu'=>$tipu,'ubi'=>$ubi,'cas'=>$cas));
	    }//FIN ELSE
	}//FIN FUNCION INDEX

	/////


	public function actionPendienteRecepcion(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$fun = Funcionario::model()->getFuncionariosAdmin();
	    	
	    	$this->render('pendienteRecepcion', array('fun'=>$fun));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionListarCarpetasPendientesRecepcion($cod){
		
		$car = Carpeta::model()->getPendienteRecepcionFun($cod);

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:85px;'>RUC</th>
				<th>ORIGEN</th>
				<th>ESTADO</th>
				<th>FECHA MOVIMIENTO</th>
				<th>COMENTARIOS</th>
				<th>RECEPCION<input type='checkbox' class='checRecepcion' onClick='MarcarTodos(this)' /></th>
				<th style='width: 300px;'>CODIGODEBARRARUC</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($car as $car) {	
		
			$fecha=$car['fec_registro'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$car['idf_rolunico']."</td>"; 
			echo "<td>".$car['origen']."</td>";
			echo "<td>".$car['estado']."</td>"; 

			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			echo "<td>".$car['gls_observacion']."</td>";

			$id=$car['cod_carpeta'];
			$ruc=$car['idf_rolunico'];
			echo "<td style='text-align:center'><input type='checkbox' class='checRecepcion' name='recepcion[".$ruc."]' id='recepcion[".$i."]' value='".$ruc."'></td>";
			?> <td><img src="../start/protected/extensions/barcode/barcode.php?text=<?php echo $car['idf_rolunico']; ?>&codetype=Code39&print=true" /></td><?php			
				
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 300,
									        
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
				                columns: [ 0, 1, 2, 3, 4, 5, 6 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	


	public function actionGuardarRecepcionMasivo($recep){	
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new Carpeta;	
	    	$flag=false;
	    	$x=0;
	        $transaction=$model->dbConnection->beginTransaction();   
	        try{
	        	if( empty($recep) ) throw new Exception('Error, debe seleccionar minímo una carpeta para recepción.');
	        	$array = explode(",", $recep); 
	        	foreach ($array as $c => $value) {
	        		if($array[$c]<>"on"){
						
						$model->idf_rolunico=$array[$c];
		        		$model->fis_codigo=Yii::app()->user->getState('fiscalia');
		        		$model->cod_estcarpeta=3; 		        		
		        		$model->fec_registro=date('Y-m-d H:m:s'); 	
		        		$model->cod_tipubicacion=1; 
		        		$model->cod_ubicacion=Yii::app()->user->id;   		
		        		$model->gls_observacion="Recepcion masiva";
		        		$model->ind_ultmov=1; 
		        		$model->fun_responsable=Yii::app()->user->id;

						if($model->save()){
							$flag=true;								
							$model=new Carpeta;	
							$x++;
						} 
						else throw new Exception('Error al recepcionar la carpeta');											
	        		}//FIN IF <> ON
	        	}//FIN FOREACH
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
					'message'=> 'Se recepcionaron '.$x.' caso(s)'
				));
			}//

	    }//FIN ELSE
	}//FIN FUNCION INDEX




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////         MOVIMIENTOS DE CARPETAS REALIZADOS   ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionMisMovimientos(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$fun = Funcionario::model()->getFuncionariosAdmin();
	    	
	    	$this->render('misMovimientos', array('fun'=>$fun));
	    }//FIN ELSE
	}//FIN FUNCION INDEX



	public function actionListarMisMovimientos($cod){
		
		$car = Carpeta::model()->getMisMovimientosFun($cod);

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:85px;'>RUC</th>
				<th>ORIGEN</th>
				<th>ESTADO</th>
				<th>DESTINO</th>
				<th>FECHA MOVIMIENTO</th>
				<th>COMENTARIOS</th>
				<th style='width: 300px;'>CODIGODEBARRARUC</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($car as $car) {	
		
			$fecha=$car['fec_registro'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$car['idf_rolunico']."</td>"; 
			echo "<td>".$car['origen']."</td>";
			echo "<td>".$car['estado']."</td>"; 

			if($car['ubicacion']<>"") echo "<td>".$car['ubicacion']."</td>";
			else echo "<td>".$car['fun']."</td>";
			
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			echo "<td>".$car['gls_observacion']."</td>";
			
			?> <td><img src="../start/protected/extensions/barcode/barcode.php?text=<?php echo $car['idf_rolunico']; ?>&codetype=Code39&print=true" /></td><?php			
				
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 30,
									        
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
				                columns: [ 0, 1, 2, 3, 4, 5, 6 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////          CARPETAS EN MI OFICINA       ///////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionMisCarpetas(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$fun = Funcionario::model()->getFuncionariosAdmin();
	    	
	    	$this->render('misCarpetas', array('fun'=>$fun));
	    }//FIN ELSE
	}//FIN FUNCION INDEX



	public function actionListarMisCarpetas($cod){
		
		$car = Carpeta::model()->getMisCarpetasFun($cod);

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:85px;'>RUC</th>
				<th>ORIGEN</th>
				<th>ESTADO</th>
				<th>FECHA MOVIMIENTO</th>
				<th>COMENTARIOS</th>
				<th style='width: 300px;'>CODIGODEBARRARUC</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($car as $car) {	
		
			$fecha=$car['fec_registro'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$car['idf_rolunico']."</td>"; 
			echo "<td>".$car['origen']."</td>";
			echo "<td>".$car['estado']."</td>"; 

			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			echo "<td>".$car['gls_observacion']."</td>";
			
			?> <td><img src="../start/protected/extensions/barcode/barcode.php?text=<?php echo $car['idf_rolunico']; ?>&codetype=Code39&print=true" /></td><?php			
				
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 30,
									        
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
				                columns: [ 0, 1, 2, 3, 4, 5, 6 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////          UBICACIÓN DE CARPETA        ///////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




	public function actionConsultarUbicacion(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$fun = Funcionario::model()->getFuncionariosTodos();

	    	$this->render('consultarUbicacion', array('fun'=>$fun));
	    }//FIN ELSE
	}//FIN FUNCION INDEX



	public function actionRecepcionarCarpeta(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$tipu=TgTipubicacion::model()->findAll('');
	    	$ubi=TgUbicacioncarp::model()->findAll('fis_codigo="'.Yii::app()->user->getState('fiscalia').'" and ind_vigencia=1 ');
			$cas=TgCasillero::model()->findAll('ind_vigencia=1');

	    	$this->render('recepcionarCarpeta', array('tipu'=>$tipu,'ubi'=>$ubi,'cas'=>$cas));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionMoverCarpeta(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$tipu=TgTipubicacion::model()->findAll('');
	    	$ubi=TgUbicacioncarp::model()->findAll('fis_codigo="'.Yii::app()->user->getState('fiscalia').'" and ind_vigencia=1 ');
			$cas=TgCasillero::model()->findAll('ind_vigencia=1');

	    	$this->render('moverCarpeta', array('tipu'=>$tipu,'ubi'=>$ubi,'cas'=>$cas));
	    }//FIN ELSE
	}//FIN FUNCION INDEX

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////            TRANSFERIR CARPETA         ///////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




	public function actionTransferirCarpeta(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$fis=Fiscalia::model()->findAll('fis_codigo<>'.Yii::app()->user->getState('fiscalia'));

	    	$this->render('transferirCarpeta', array('fis'=>$fis));
	    }//FIN ELSE
	}//FIN FUNCION INDEX



	public function actionGuardarTransferenciaCarpeta(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new Carpeta;
	    	
	    	$flag=false;

	        $transaction=$model->dbConnection->beginTransaction();   
	        try{  
	        	if( empty($_POST['ruc']) ) throw new Exception('Error, indicar un ruc para transferir.');

	
	        		$model->idf_rolunico=$_POST['ruc']; 
	        		$model->fis_codigo=$_POST['fiscalia']; 
	        		$model->cod_estcarpeta=2; 
	        		$model->fec_registro=date('Y-m-d H-i-s'); 	
	        		$model->cod_tipubicacion=2; 
	        		$model->cod_ubicacion=1;    
	        		$model->gls_observacion=$_POST['obs'];
	        		$model->ind_ultmov=1; 
	        		$model->fun_responsable=Yii::app()->user->id;

	        		if($model->save()){	 
	        			$flag=true;
		        	}
        			else throw new Exception('Error, no se puede transferir la Carpeta');	       		
        		
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
					'message'=> 'Se transfiere con éxito'
				));
			}//

	    }//FIN ELSE
	}//FIN FUNCION GUARDAR ASIGNACION




	public function actionListTransferirCarpeta(){

		$rut=Yii::app()->user->id;

		$ca = Carpeta::model()->getTransferidas($rut);

		$resultado = "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
		        <thead align='left'>
		            <tr>
		            	<th style='width:30px;'>N°</th>
						<th style='width:80px;'>RUC</th>
						<th>ULTIMO MOV.</th>
						<th>UBICACIÓN</th>
			
						<th>FECHA</th>
						<th>COMENTARIOS</th>
						<th class='check_misdil' style='width:30px;text-align:center'>ELIMINAR</th>

		            </tr>
		        </thead>";
		$resultado .=  "<tbody>";		
		$n=1; $i=1;
		foreach ($ca as $ca) {	
			$fecha=$ca['fec_registro'];
			$resultado .=  "<tr>"; 
			$resultado .=  "<td>".$n."</td>"; 
			$resultado .=  "<td>".$ca['idf_rolunico']."</td>"; 
			$resultado .=  "<td>".$ca['estado']."</td>"; 
			$resultado .=  "<td>".$ca['fiscalia']."</td>"; 

			if($ca['ubicacion']<>"") $resultado .=  "<td>".$ca['ubicacion']."</td>"; 
			else $resultado .=  "<td>".$ca['fun']."</td>"; 
			

			$resultado .=  "<td>".date("d-m-Y", strtotime($fecha))."</td>"; 
			$resultado .=  "<td>".$ca['gls_observacion']."</td>"; 
			$id=$ca['cod_carpeta'];
			$resultado .= "<td style='text-align:center'><span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimMov(this.id)'>X</span></td>";
					
			$n++; $i++;
		}		
		$resultado .= "</tbody>"; 
			$resultado .= "</table>";		

		$resultado .= "<script type='text/javascript'>
					$( document ).ready(function() {
						var table = $('#listarea').DataTable( {
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
									extend: 'excelHtml5',
									exportOptions: {
										columns: [ 0, 1, 2, 3, 4, 5, 6 ]
									}
								}
			                ]

						} );
					});    

				</script>";

		echo $resultado;

	}






/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////           LIMPIAR MOVIMIENTOS         ///////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	public function actionLimpiarMovimientos(){

		$rut=Yii::app()->user->id;
		TmpCarpeta::model()->deleteAll("fun_responsable ='" .$rut . "'");

	}


	public function actionEliminarMovimiento(){
		$id=$_POST['id'];
		Carpeta::model()->deleteAll("cod_carpeta ='" .$id . "'");
		TmpCarpeta::model()->deleteAll("cod_carpeta ='" .$id . "'");

	}


	public function actionGuardarRecepcionCarpeta(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new Carpeta;
	    	
	    	$flag=false;

	        $transaction=$model->dbConnection->beginTransaction();   
	        try{  
	        	if( empty($_POST['ruc']) ) throw new Exception('Error, indicar un ruc para recepcionar.');

	
	        		$model->idf_rolunico=$_POST['ruc']; 
	        		$model->fis_codigo=Yii::app()->user->getState('fiscalia');
	        		$model->cod_estcarpeta=3; 
	        		$model->cod_casillero=$_POST['casillero']; 
	        		$model->fec_registro=date('Y-m-d H-i-s'); 	
	        		if($_POST['ubicacion'] <> ""){
	        			$model->cod_tipubicacion=2; 
	        			$model->cod_ubicacion=$_POST['ubicacion'];
	        		}
	        		else{
	        			$model->cod_tipubicacion=1; 
	        			$model->cod_ubicacion=Yii::app()->user->id;
	        		}
	        		
	        		$model->gls_observacion=$_POST['obs'];
	        		$model->ind_ultmov=1; 
	        		$model->fun_responsable=Yii::app()->user->id;

	        		if($model->save()){	 
	        			$tmp=new TmpCarpeta;
	        			$tmp->idf_rolunico=$model->idf_rolunico; 
		        		$tmp->fis_codigo=Yii::app()->user->getState('fiscalia');
		        		$tmp->cod_estcarpeta=3; 
		        		$tmp->cod_casillero=$model->cod_casillero; 
		        		$tmp->fec_registro=date('Y-m-d H-i-s'); 	
		        		$tmp->cod_tipubicacion=$model->cod_tipubicacion; 
		        		$tmp->cod_ubicacion=$model->cod_ubicacion;
		        		$tmp->gls_observacion=$model->gls_observacion;
		        		$tmp->ind_ultmov=1; 
		        		$tmp->fun_responsable=Yii::app()->user->id;
		        		$tmp->cod_carpeta=$model->cod_carpeta;
		        		if($tmp->save()){
		        			$flag=true;
		        		}
		        	}
        			else throw new Exception('Error, no se puede recepcionar la Carpeta');	       		
        		
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
					'message'=> 'Se recepciono con éxito'
				));
			}//

	    }//FIN ELSE
	}//FIN FUNCION GUARDAR ASIGNACION






	public function actionGuardarMovimientoCarpeta(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new Carpeta;
	    	
	    	$flag=false;

	        $transaction=$model->dbConnection->beginTransaction();   
	        try{  
	        	if( empty($_POST['ruc']) ) throw new Exception('Error, indicar un ruc para recepcionar.');

	
	        		$model->idf_rolunico=$_POST['ruc']; 
	        		$model->fis_codigo=Yii::app()->user->getState('fiscalia');
	        		$model->cod_estcarpeta=2; 
	        		$model->cod_casillero=$_POST['casillero']; 
	        		$model->fec_registro=date('Y-m-d H-i-s'); 	
	        		$model->cod_tipubicacion=$_POST['tipubicacion']; 
	        		$model->cod_ubicacion=$_POST['ubicacion'];	        		
	        		$model->gls_observacion=$_POST['obs'];
	        		$model->ind_ultmov=1; 
	        		$model->fun_responsable=Yii::app()->user->id;

	        		if($model->save()){	 
	        			$tmp=new TmpCarpeta;
	        			$tmp->idf_rolunico=$model->idf_rolunico; 
		        		$tmp->fis_codigo=Yii::app()->user->getState('fiscalia');
		        		$tmp->cod_estcarpeta=2; 
		        		$tmp->cod_casillero=$model->cod_casillero; 
		        		$tmp->fec_registro=date('Y-m-d H-i-s'); 	
		        		$tmp->cod_tipubicacion=$model->cod_tipubicacion; 
		        		$tmp->cod_ubicacion=$model->cod_ubicacion;
		        		$tmp->gls_observacion=$model->gls_observacion;
		        		$tmp->ind_ultmov=1; 
		        		$tmp->fun_responsable=Yii::app()->user->id;
		        		$tmp->cod_carpeta=$model->cod_carpeta;
		        		if($tmp->save()){
		        			$flag=true;
		        		}
		        	}
        			else throw new Exception('Error, no se puede mover la Carpeta');	       		
        		
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
					'message'=> 'Se mueve con éxito'
				));
			}//

	    }//FIN ELSE
	}//FIN FUNCION GUARDAR ASIGNACION








	public function actionListRecepcionCarpeta(){

		$rut=Yii::app()->user->id;

		$ca = TmpCarpeta::model()->getRecepcionadas($rut);

		$resultado = "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
		        <thead align='left'>
		            <tr>
		            	<th style='width:30px;'>N°</th>
						<th style='width:80px;'>RUC</th>
						<th>ULTIMO MOV.</th>
						<th>UBICACIÓN</th>
						<th>CASILLERO</th>
						<th>FECHA</th>
						<th>COMENTARIOS</th>
						<th class='check_misdil' style='width:30px;text-align:center'>ELIMINAR</th>
						<th>CODIGODEBARRARUC</th>

		            </tr>
		        </thead>";
		$resultado .=  "<tbody>";		
		$n=1; $i=1;
		foreach ($ca as $ca) {	
			$fecha=$ca['fec_registro'];
			$resultado .=  "<tr>"; 
			$resultado .=  "<td>".$n."</td>"; 
			$resultado .=  "<td>".$ca['idf_rolunico']."</td>"; 
			$resultado .=  "<td>".$ca['estado']."</td>"; 

			if($ca['ubicacion']<>"") $resultado .=  "<td>".$ca['ubicacion']."</td>"; 
			else $resultado .=  "<td>".$ca['fun']."</td>"; 
			
			$resultado .=  "<td>".$ca['casillero']."</td>"; 
			$resultado .=  "<td>".date("d-m-Y", strtotime($fecha))."</td>"; 
			$resultado .=  "<td>".$ca['gls_observacion']."</td>"; 
			$id=$ca['cod_carpeta'];
			$resultado .= "<td style='text-align:center'><span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimMov(this.id)'>X</span></td>";

			$resultado .= '<td><img src="../start/protected/extensions/barcode/barcode.php?text='.$ca["idf_rolunico"].'&codetype=Code39&print=true" /></td>';
					
			$n++; $i++;
		}		
		$resultado .= "</tbody>"; 
			$resultado .= "</table>";		

		$resultado .=  "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					
						'columnDefs': [
			            {
			                'targets': [ 8 ],
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
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 8 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6 ]
							}
						}
	                ]

				} );
			});    

		</script>";

		echo $resultado;

	}







public function actionListMoverCarpeta(){

		$rut=Yii::app()->user->id;

		$ca = TmpCarpeta::model()->getMovidas($rut);

		$resultado = "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
		        <thead align='left'>
		            <tr>
		            	<th style='width:30px;'>N°</th>
						<th style='width:80px;'>RUC</th>
						<th>ULTIMO MOV.</th>
						<th>UBICACIÓN</th>
						<th>CASILLERO</th>
						<th>FECHA</th>
						<th>COMENTARIOS</th>
						<th class='check_misdil' style='width:30px;text-align:center'>ELIMINAR</th>
						<th>CODIGODEBARRARUC</th>

		            </tr>
		        </thead>";
		$resultado .=  "<tbody>";		
		$n=1; $i=1;
		foreach ($ca as $ca) {	
			$fecha=$ca['fec_registro'];
			$resultado .=  "<tr>"; 
			$resultado .=  "<td>".$n."</td>"; 
			$resultado .=  "<td>".$ca['idf_rolunico']."</td>"; 
			$resultado .=  "<td>".$ca['estado']."</td>"; 

			if($ca['ubicacion']<>"") $resultado .=  "<td>".$ca['ubicacion']."</td>"; 
			else $resultado .=  "<td>".$ca['fun']."</td>"; 
			
			$resultado .=  "<td>".$ca['casillero']."</td>"; 
			$resultado .=  "<td>".date("d-m-Y", strtotime($fecha))."</td>"; 
			$resultado .=  "<td>".$ca['gls_observacion']."</td>"; 
			$id=$ca['cod_carpeta'];
			$resultado .= "<td style='text-align:center'><span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimMov(this.id)'>X</span></td>";

			$resultado .= '<td><img src="../start/protected/extensions/barcode/barcode.php?text='.$ca["idf_rolunico"].'&codetype=Code39&print=true" /></td>';
					
			$n++; $i++;
		}		
		$resultado .= "</tbody>"; 
			$resultado .= "</table>";		

		$resultado .=  "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					
						'columnDefs': [
			            {
			                'targets': [ 8 ],
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
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 8 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6 ]
							}
						}
	                ]

				} );
			});    

		</script>";

		echo $resultado;

	}







	public function actionListarHistoriaCarpeta(){
		
		$ruc=$_POST['ruc'];
		$ca = Carpeta::model()->getHistoria($ruc);

		if(isset($ca) ){
			if(isset($ca[0]->ubicacion)){
				echo "<div class='ubicacionActual'><h3>Ubicación Actual: ".$ca[0]->ubicacion." </h3></div>";
				echo "<br>";
				echo "<h3>Historia de Movimientos del RUC ".$ca[0]->idf_rolunico." </h3>";

			}
			elseif(isset($ca[0]->fun)){
				echo "<div class='ubicacionActual'><h3>Ubicación Actual: ".$ca[0]->fun." </h3></div>";
				echo "<br>";
				echo "<h3>Historia de Movimientos del RUC ".$ca[0]->idf_rolunico." </h3>";
			}
			else{
				 echo "<div class='ubicacionActual'><h3>No se registran movimientos de Carpetas </h3></div>";
				 echo "<br>";
			}

			

		}	
		else{
			 echo "<div class='ubicacionActual'><h3>No se registran movimientos de Carpeta </h3></div>";
			 echo "<br>";
		}

		
		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th style='width:25px;'>N°</th>
            	<th style='width:95px;'>FECHA</th>
				<th style='width:90px;'>RUC</th>
				<th>ORIGEN</th>
				<th>ULTIMO MOV.</th>
				<th>UBICACIÓN</th>
				<th>CASILLERO</th>
				
				<th>COMENTARIOS</th>
				<th class='check_misdil' style='width:30px;text-align:center'>ELIMINAR</th>
				<th>CODIGODEBARRARUC</th>

            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($ca as $ca) {	
			$fecha=$ca['fec_registro'];
			echo "<tr>"; 
			echo "<td>".$n."</td>";
			echo "<td>".date("d-m-Y H:i", strtotime($fecha))."</td>"; 
			echo "<td>".$ca['idf_rolunico']."</td>";
			echo "<td>".$ca['origen']."</td>";
			echo "<td>".$ca['estado']."</td>";
			if($ca['ubicacion']<>"") echo "<td>".$ca['ubicacion']."</td>";
			else echo "<td>".$ca['fun']."</td>";
			echo "<td>".$ca['casillero']."</td>";
			
			echo "<td>".$ca['gls_observacion']."</td>";
			$id=$ca['cod_carpeta'];
			if(Yii::app()->user->getState('perfil')==13 || Yii::app()->user->id == $ca['fun_responsable']){
				echo "<td style='text-align:center'><span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimMov(this.id)'>X</span></td>";
			}
			else{
				echo "<td style='text-align:center'></td>";
			}
			
			?> <td><img src="../start/protected/extensions/barcode/barcode.php?text=<?php echo $ca['idf_rolunico']; ?>&codetype=Code39&print=true" /></td><?php

			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					
						'columnDefs': [
			            {
			                'targets': [ 9 ],
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
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 9 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
							}
						}
	                ]

				} );
			});    

		</script>";


	}//FIN FUNCTION	




	public function actionCategoria(){

		if(Yii::app()->user->isGuest)
			$this->redirect(array('Site/Logout'));
		else{
			$id = $_POST['valor'];
			$fis=Yii::app()->user->getState('fiscalia');
			//persona
			if ($id == 1) {
				echo '<option value="">Seleccione Funcionario</option>';
				$fun = Funcionario::model()->getFunFiscalia($fis);
				foreach ($fun as $fun) {	
					echo '<option value="'.$fun['fun_rut'].'">'.$fun['fun_ap_paterno'].' '.$fun['fun_nombre'].' '.$fun['fun_nombre2'].'</option>';
				}
			}//FIN IF ID ==1
			elseif($id == 2){

				$consulta=TgUbicacioncarp::model()->findAll('fis_codigo="'.Yii::app()->user->getState('fiscalia').'" and ind_vigencia=1');
				echo '<option value="">Seleccionar Bodega</option>';
				foreach ($consulta as $bod) {
					echo '<option value="'.$bod['cod_ubicacion'].'">'.$bod['gls_ubicacion'].'</option>';
				}//FIN FOREACH
			}
		}//FIN ELSE		
	}//fin categorias




	public function loadCarpeta($id){
		$model=Carpeta::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


//FIN CONTROLADOR
}