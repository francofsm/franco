<?php
date_default_timezone_set('America/Santiago'); 

class CentralizadoController extends Controller{

	public $layout='//layouts/column2';

	public function filters(){
		return array('accessControl', 'postOnly + delete',);
	}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////     CONSULTAR DILIGENCIAS DECRETADAS    ////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionDiligenciasAnuladas(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	        $this->render('diligenciasAnuladas');  
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionListarDiligenciasAnuladas(){
		
		$ta = BancoTarea::model()->getListDiligenciasAnuladas();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:85px;'>RUC</th>
				<th>FISCAL</th>
				<th>DILIGENCIA</th>
				<th>COMENTARIOS</th>
				<th>FECHA DECRETA</th>
				<th>PRIORIDAD</th>
				<th>FECHA ASIGNACION</th>
				<th>ASIGNADO A:</th>
				<th>ESTADO ACTUAL</th>				
				<th>FECHA ESTADO ACTUAL</th>
				<th>OBSERVACIONES</th>
				<th style='width: 300px;'>CODIGODEBARRARUC</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($ta as $ta) {	
			$fecha_ta=$ta['fec_registro'];
			$fecha=$ta['fec_asignacion'];
			$fecha_cam=$ta['fec_cambioest'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			if($ta['idf_rolunico'] <> 'Tarea') echo "<td>".$ta['idf_rolunico']."</td>"; 
			else echo "<td></td>";
			echo "<td>".$ta['fiscal']."</td>"; 
			echo "<td>".$ta['tarea']."</td>"; 
			echo "<td>".$ta['gls_comentario']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha_ta))."</td>";
			if($ta['cod_prioridad']==3) echo "<td><span class='badge-danger'>".$ta['priori']."</span></td>";
			else echo "<td>".$ta['priori']."</td>";
			//echo "<td>".$ta['gls_comentario']."</td>"; 
			if($fecha<>"") echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			else echo "<td></td>";

			echo "<td>".$ta['funcionario']."</td>"; 

			echo "<td>".$ta['estado']."</td>"; 
			
			if($fecha_cam<>"") echo "<td>".date("d-m-Y", strtotime($fecha_cam))."</td>";
			else echo "<td></td>";

			echo "<td>".$ta['gls_observacion']."</td>"; 

			if($ta['idf_rolunico'] <> 'Tarea'){
			?> <td><img src="../start/protected/extensions/barcode/barcode.php?text=<?php echo $ta['idf_rolunico']; ?>&codetype=Code39&print=true" /></td><?php
			}
			else echo "<td></td>";
		
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 30,
						'columnDefs': [
			            {
			                'targets': [ 12 ],
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
				                stripHtml: false
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////                                        ////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////




	public function actionAsignarBancoTarea(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$fun = Funcionario::model()->getFuncionariosTodos();
	    	$meses = BancoTarea::model()->getMesesBolsa();
	    	if( Yii::app()->user->getState('perfil') == 13 ){
	    		$uni = Unidad::model()->findAll('fis_codigo='.Yii::app()->user->getState('fiscalia'));
	    	}
	    	else{
	    		$uni = Unidad::model()->findAll('uni_codigo='.Yii::app()->user->getState('unidad'));
	    	}

	    	$unidade=Yii::app()->user->getState('unidad');
	    	$tguni = Unidad::model()->findByPk($unidade);

	    	$fiscalias=Yii::app()->user->getState('fiscalia');
	    	$fi = Fiscalia::model()->findByPk($fiscalias);
	    	

	    	$this->render('asginarBancoTarea', array('fun'=>$fun, 'meses'=>$meses, 'uni'=>$uni, 'tguni'=>$tguni, 'fi'=>$fi));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionAsignarTareaCAso(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$fun = Funcionario::model()->getFuncionariosTodos();

	    	$unidade=Yii::app()->user->getState('unidad');
	    	$tguni = Unidad::model()->findByPk($unidade);

	    	$fiscalias=Yii::app()->user->getState('fiscalia');
	    	$fi = Fiscalia::model()->findByPk($fiscalias);
	    	
	    	
	    	$this->render('asignarTareaCaso', array('fun'=>$fun, 'tguni'=>$tguni, 'fi'=>$fi));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionEjecutarTareaCAso(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$fun = Funcionario::model()->getFuncionariosTodos();

	    	$this->render('ejecutarTareaCaso', array('fun'=>$fun));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionEliminarTareaCAso(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$fun = Funcionario::model()->getFuncionariosTodos();

	    	$this->render('eliminarTareaCaso', array('fun'=>$fun));
	    }//FIN ELSE
	}//FIN FUNCION INDEX

	public function loadTarea($id){
		$model=BancoTarea::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


//ELIMINAR TMP
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////  	GUARDAR EN TEMP ELIMINAR DILIGENCIAS PENDIENTES DE EJECUTAR                ///////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionDilPendientesEliminar($ruc){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 

	   		$model=new TmpEliminar;
	    	$flag=false;
	        $transaction=$model->dbConnection->beginTransaction();   
	        try{      	
	        	$dil = BancoTarea::model()->getDilPendientes($ruc);	
	        	
	        	foreach ($dil as $dil){ 	        	
	        		$existe = TmpEliminar::model()->getExisteCodigo($dil->cod_bntarea);	
	        		if(!$existe){
	        			
		        		$model->cod_bntarea=$dil->cod_bntarea;
						$model->fun_rut=Yii::app()->user->id;	
						$model->fec_registro=date('Y-m-d H-i-s'); 					
						if($model->save()){
							$flag=true;
							$model=new TmpEliminar;
						}	
						else throw new Exception('Error, no se puede obtener diligencias del RUC indicado');	        			
	        		}//fin if existe
	        		else throw new Exception('No se encontraron diligencias para eliminar');	
	        	}	        		
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
					'message'=> $ruc
				));
			}//
			elseif($flag==false){
				echo json_encode(array(
					'status' => 'success',
					'message'=> 'No se encontraron diligencias/tareas pendientes de eliminar'
				));
			}//

	    }//FIN ELSE
	}//FIN FUNCION GUARDAR eliminar

//EJECUTAR
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////  	GUARDAR EN TEMP EJECUTAR DILIGENCIAS PENDIENTES DE EJECUTAR                ///////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionDilPendientesEjecutar($ruc){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 

	   		$model=new TmpEjecutar;
	    	$flag=false;
	        $transaction=$model->dbConnection->beginTransaction();   
	        try{      	
	        	$dil = BancoTarea::model()->getDilPendientes($ruc);	
	        	
	        	foreach ($dil as $dil){ 	        	
	        		$existe = TmpEjecutar::model()->getExisteCodigo($dil->cod_bntarea);	
	        		if(!$existe){
	        			
		        		$model->cod_bntarea=$dil->cod_bntarea;
						$model->fun_rut=Yii::app()->user->id;	
						$model->fec_registro=date('Y-m-d H-i-s'); 					
						if($model->save()){
							$flag=true;
							$model=new TmpEjecutar;
						}	
						else throw new Exception('Error, no se puede obtener diligencias del RUC indicado');	        			
	        		}//fin if existe
	        		else throw new Exception('No se encontraron diligencias para ejecutar');	
	        	}	        		
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
					'message'=> $ruc
				));
			}//
			elseif($flag==false){
				echo json_encode(array(
					'status' => 'success',
					'message'=> 'No se encontraron diligencias/tareas pendientes de ejecutar'
				));
			}//

	    }//FIN ELSE
	}//FIN FUNCION GUARDAR ASIGNACION

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////   SE GUARDAN DILIGENCIAS PISTOLEADAS PENDIENTES DE EJECUTAR     ///////////////////////////////
////////////////////////////////   SE GUARDAN DILIGENCIAS PISTOLEADAS PENDIENTES DE EJECUTAR     ///////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//ASIGNAR POR CASO
	public function actionDilPendientesAsignar($ruc){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 

	   		$model=new TmpAsignar;
	    	$flag=false;
	        $transaction=$model->dbConnection->beginTransaction();   
	        try{      	
	        	$dil = BancoTarea::model()->getDilPendientes($ruc);	
	        	
	        	foreach ($dil as $dil){ 	        	
	        		$existe = TmpAsignar::model()->getExisteCodigo($dil->cod_bntarea);	
	        		if(!$existe){
	        			
		        		$model->cod_bntarea=$dil->cod_bntarea;
						$model->fun_rut=Yii::app()->user->id;	
						$model->fec_registro=date('Y-m-d H-i-s'); 					
						if($model->save()){
							$flag=true;
							$model=new TmpAsignar;
						}	
						else throw new Exception('Error, no se puede obtener diligencias del RUC indicado');	        			
	        		}//fin if existe
	        		else throw new Exception('No se encontraron diligencias para asignar');	
	        	}	        		
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
					'message'=> $ruc
				));
			}//
			elseif($flag==false){
				echo json_encode(array(
					'status' => 'success',
					'message'=> 'No se encontraron diligencias/tareas pendientes de ejecutar'
				));
			}//

	    }//FIN ELSE
	}//FIN FUNCION GUARDAR ASIGNACION


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////   LISTAR DILIGENCIAS EN TEMPORAL PARA ELIMINAR    //////////////////////////////////////////////
////////////////////////////////   LISTAR DILIGENCIAS EN TEMPORAL PARA ELIMINAR      ////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionListarDiligenciasTmpEliminar(){
		
		$ta = TmpEliminar::model()->getListTmpEliminar();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:80px;'>RUC</th>
				<th>FISCAL</th>
				<th>DILIGENCIA</th>
				<th>FECHA DECRETA</th>
				<th>PRIORIDAD</th>
				<th>COMENTARIOS</th>
				<th>ESTADO</th>
				<th>ASIGNADO A:</th>
				<th class='check_misdil' style='width:30px;text-align:center'>ELIMINAR</th>

            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_registro'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['ruc']."</td>"; 
			echo "<td>".$ta['fiscal']."</td>";
			echo "<td>".$ta['tarea']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			if($ta['cod_prioridad']==3) echo "<td><span class='badge-danger'>".$ta['priori']."</span></td>";
			else echo "<td>".$ta['priori']."</td>";  
			echo "<td>".$ta['comen']."</td>"; 
			echo "<td>".$ta['estado']."</td>"; 
			echo "<td>".$ta['fun']."</td>"; 
			$id=$ta['cod_tmpeliminar'];
			echo "<td style='text-align:center'><span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimTarea(this.id)'>X</span></td>";
			
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 9999,
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
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////   LISTAR DILIGENCIAS EN TEMPORAL PARA EJECUTARR    //////////////////////////////////////////////
////////////////////////////////   LISTAR DILIGENCIAS EN TEMPORAL PARA EJECUTARR      ////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionListarDiligenciasTmpEjecutar(){
		
		$ta = TmpEjecutar::model()->getListTmpEjecutar();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:80px;'>RUC</th>
				<th>DILIGENCIA</th>
				<th>TIEMPO</th>
				<th>FECHA DECRETA</th>
				<th>PRIORIDAD</th>
				<th>COMENTARIOS</th>
				<th>ESTADO</th>
				<th>ASIGNADO A:</th>
				<th class='check_misdil' style='width:30px;text-align:center'>ELIMINAR</th>

            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_registro'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['ruc']."</td>"; 
			echo "<td>".$ta['tarea']."</td>";
			echo "<td>".$ta['tiempo']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			if($ta['cod_prioridad']==3) echo "<td><span class='badge-danger'>".$ta['priori']."</span></td>";
			else echo "<td>".$ta['priori']."</td>";  
			echo "<td>".$ta['comen']."</td>"; 
			echo "<td>".$ta['estado']."</td>"; 
			echo "<td>".$ta['fun']."</td>"; 
			$id=$ta['cod_tmpejecutar'];
			echo "<td style='text-align:center'><span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimTarea(this.id)'>X</span></td>";
			
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
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
								columns: [ 0, 1, 2, 3, 4, 5 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////   LISTAR DILIGENCIAS EN TEMPORAL PARA ASIGNAR    //////////////////////////////////////////////
////////////////////////////////   LISTAR DILIGENCIAS EN TEMPORAL PARA ASIGNAR      ////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionMinAsignadoCaso(){
		
		$ta = TmpAsignar::model()->getMinTmpAsignar();

		echo "<table class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
				<th>Min. Asignado</th>
            </tr>
        </thead>";
		echo "<tbody>";	
	
		foreach ($ta as $ta) {	
			echo "<tr>"; 
			echo "<td>".$ta['total']."</td>";
			echo "</tr>"; 
		}		
		echo "</tbody>"; 
		echo "</table>";		



	}//FIN FUNCTION

	public function actionListarDiligenciasTmpAsignar(){
		
		$ta = TmpAsignar::model()->getListTmpAsignar();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:80px;'>RUC</th>
				<th>FISCAL</th>
				<th>DILIGENCIA</th>
				<th>TIEMPO</th>
				<th>FECHA DECRETA</th>
				<th>PRIORIDAD</th>
				<th>COMENTARIOS</th>
				<th>ESTADO</th>
				<th>ASIGNADO A:</th>
				<th>DECRETADO POR: </th> 
				<th class='check_misdil' style='width:30px;text-align:center'>ELIMINAR</th>

            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_registro'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['ruc']."</td>"; 
			echo "<td>".$ta['fiscal']."</td>";
			echo "<td>".$ta['tarea']."</td>";
			echo "<td>".$ta['tiempo']."</td>";  
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			if($ta['cod_prioridad']==3) echo "<td><span class='badge-danger'>".$ta['priori']."</span></td>";
			else echo "<td>".$ta['priori']."</td>";   
			echo "<td>".$ta['comen']."</td>"; 
			echo "<td>".$ta['estado']."</td>"; 
			echo "<td>".$ta['fun']."</td>"; 
			echo "<td>".$ta['responsable']."</td>";  
			$id=$ta['cod_tmpasignar'];
			echo "<td style='text-align:center'><span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimTarea(this.id)'>X</span></td>";
			
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
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
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////   LISTAR DILIGENCIAS        //////////////// //////////////////////////////////////////////
////////////////////////////////   LISTAR DILIGENCIAS       //////////////// //////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	public function actionListarDiligencias($mes=false, $uni=false){
		
		if(!isset($uni)) $uni='';
		
	
		$ta = BancoTarea::model()->getListDiligencias($mes,$uni);

		echo "<table id='listardil' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:80px;'>RUC</th>
				<th>FISCAL</th>
				<th>DILIGENCIA</th>
				<th>TIEMPO</th>
				<th>FECHA DECRETA</th>
				<th>PRIORIDAD</th>
				<th>COMENTARIOS</th>
				<th>ESTADO</th>
		
				<th>DECRETADO POR: </th>
				<th class='check_misdil' style='width:30px;text-align:center'>ASIGNAR<input type='checkbox' class='checAsignar' onClick='MarcarTodos(this)' /></th>
				<th>CODIGODEBARRACU</th>

            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_registro'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['idf_rolunico']."</td>";
			echo "<td>".$ta['fiscal']."</td>"; 
			echo "<td>".$ta['tarea']."</td>";
			echo "<td>".$ta['tiempo']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			if($ta['cod_prioridad']==3) echo "<td><span class='badge-danger'>".$ta['priori']."</span></td>";
			else echo "<td>".$ta['priori']."</td>";  
			echo "<td>".$ta['gls_comentario']."</td>"; 
			echo "<td>".$ta['estado']."<br>".$ta['gls_observacion']."</td>"; 
	
			echo "<td>".$ta['responsable']."</td>"; 

			$id=$ta['cod_bntarea'];
			echo "<td style='text-align:center'><input type='checkbox' class='checAsignar' name='asignar[".$i."]' id='asignar[".$i."]' value='".$id."' onclick='minasing()' ></td>";
			?> <td><img src="../start/protected/extensions/barcode/barcode.php?text=<?php echo $ta['idf_rolunico']; ?>&codetype=Code39&print=true" /></td><?php


			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		


		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listardil').DataTable( {
					'pageLength': 9999,
						'columnDefs': [
			            {
			                'targets': [ 11 ],
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
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 11 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9  ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////   GUARDAR ASIGNACIÓN TABLA      //////////////// //////////////////////////////////////////////
////////////////////////////////   GUARDAR ASIGNACIÓN TABLA      //////////////// //////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	public function actionGuardarAsignacion($asignar,$fun,$fec,$carp){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$estado=new EstadoTarea;
	    	$x=0;
	    	$flag=false;
	    	$rucs=array();
	        $transaction=$estado->dbConnection->beginTransaction();   
	        try{
	        	if( empty($asignar) ) throw new Exception('Error, debe seleccionar minímo 1 tarea para asignar.');
	        	$array = explode(",", $asignar); 

	        	foreach ($array as $c => $value) {
	        		if($array[$c]<>"on" && $array[$c]<>""){

						$estado->cod_bntarea=$array[$c];
						$estado->cod_estinstruccion=3;	
						$estado->fec_registro=date('Y-m-d H-i-s'); 		
						$estado->fun_rut=$fun;
						$estado->fun_responsable=Yii::app()->user->id;	

						if($estado->save()){
							$model=BancoTarea::model()->findByPk($array[$c]);	
							$model->cod_estinstruccion=3;	
							$model->fec_cambioest=$fec; 	
							$model->fec_asignacion=date('Y-m-d H:i:s'); 
							$model->fec_tarea=$fec; 							
							$model->fun_asignado=$fun;
							if($model->save()){


								if(  !( in_array( $model->idf_rolunico, $rucs ) ) && ($carp==1) ){	

									$carpet=new Carpeta;
									$carpet->idf_rolunico=$model->idf_rolunico; 
					        		$carpet->fis_codigo=$model->fis_codigo;
					        		$carpet->cod_estcarpeta=2; 
					        		$carpet->fec_registro=date('Y-m-d H:i:s'); 
					        		$carpet->cod_tipubicacion=1; 
					        		$carpet->cod_ubicacion=$model->fun_asignado;
					        		$carpet->ind_ultmov=1; 
					        		$carpet->fun_responsable=Yii::app()->user->id;	

					        		if($carpet->save()){
					        			$ind_carp=true;
					        			$rucs[$x]=$carpet->idf_rolunico;	
					        		}
					        		else throw new Exception('Error, no se pudo mover la carpeta');    				
			        				
			        			}//FIN RUC SOLO 1 VEZ

								$x++;       			
	        					$flag=true;	
								$estado=new EstadoTarea;
							}//FIN SAVE MODEL
							else throw new Exception('Error al actualizar información');	
							
						}//FIN GUARDAR ESTADO
						else throw new Exception('Error al guardar asignación de la tarea');	
							
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
					'message'=> 'Se ejecutaron '.$x.' diligencia(s)'
				));
			}//
	    }//FIN ELSE
	}//FIN FUNCION INDEX

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////   GUARDAR ASIGNACIÓN POR CASO    //////////////// //////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionGuardarAsignacionCaso($fun,$fec,$carp){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$estado=new EstadoTarea;
	    	$x=0;
	    	$flag=false;
	    	$rucs=array();
	        $transaction=$estado->dbConnection->beginTransaction();   
	        try{
	        	if( empty($fun) ) throw new Exception('Error, debe seleccionar un funcionario.');
	        	
	        	$ta = TmpAsignar::model()->getListTmpAsignar();
	        	foreach ($ta as $ta) {
						$codigo=$ta['cod_tmpasignar'];
						$id=$ta['cod_bntarea'];
						$estado->cod_bntarea=$ta['cod_bntarea'];
						$estado->cod_estinstruccion=3;	
						$estado->fec_registro=date('Y-m-d H-i-s'); 		
						$estado->fun_rut=$fun;
						$estado->fun_responsable=Yii::app()->user->id;	

						if($estado->save()){
							$model=BancoTarea::model()->findByPk($id);	
							$model->cod_estinstruccion=3;	
							$model->fec_tarea=$fec; 
							$model->fec_asignacion=date('Y-m-d H-i-s'); 	 			
							$model->fec_cambioest=date('Y-m-d H-i-s'); 	 							
							$model->fun_asignado=$fun;
							if($model->save()){

								if(  !( in_array( $model->idf_rolunico, $rucs ) ) && ($carp==1) ){	

									$carpet=new Carpeta;
									$carpet->idf_rolunico=$model->idf_rolunico; 
					        		$carpet->fis_codigo=$model->fis_codigo;
					        		$carpet->cod_estcarpeta=2; 
					        		$carpet->fec_registro=date('Y-m-d H:i:s'); 
					        		$carpet->cod_tipubicacion=1; 
					        		$carpet->cod_ubicacion=$model->fun_asignado;
					        		$carpet->ind_ultmov=1; 
					        		$carpet->fun_responsable=Yii::app()->user->id;	

					        		if($carpet->save()){
					        			$ind_carp=true;
					        			$rucs[$x]=$carpet->idf_rolunico;	
					        		}
					        		else throw new Exception('Error, no se pudo mover la carpeta');    				
			        				
			        			}//FIN RUC SOLO 1 VEZ
			        			
								$x++;       			
	        					$flag=true;	
								$estado=new EstadoTarea;

								$this->loadTareaTmp($codigo)->delete();	
							}//FIN SAVE MODEL
							else throw new Exception('Error al actualizar información');	
							
						}//FIN GUARDAR ESTADO
						else throw new Exception('Error al guardar asignación de la tarea');
	        	}

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
					'message'=> 'Se asignaron las tareas de '.$x.' caso(s)'
				));
			}//
	    }//FIN ELSE
	}//FIN FUNCION INDEX




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////   GUARDAR EJECUCIÓN POR CASO   //////////////////////////////////////////////////////////////
////////////////////////////////   GUARDAR EJECUCIÓN POR CASO    //////////////// //////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionGuardarEjecutarCaso($fec){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$estado=new EstadoTarea;
	    	$x=0;
	    	$flag=false;
	        $transaction=$estado->dbConnection->beginTransaction();   
	        try{
	        	if( empty($fec) ) throw new Exception('Error, debe seleccionar fecha de ejecución.');
	        	
	        	$ta = TmpEjecutar::model()->getListTmpEjecutar();
	        	foreach ($ta as $ta) {
						$codigo=$ta['cod_tmpejecutar'];
						$id=$ta['cod_bntarea'];
						$estado->cod_bntarea=$ta['cod_bntarea'];
						$estado->cod_estinstruccion=5;	
						$estado->fec_registro=date('Y-m-d H-i-s'); 		
						$estado->fun_rut=Yii::app()->user->id;
						$estado->fun_responsable=Yii::app()->user->id;	

						if($estado->save()){
							$model=BancoTarea::model()->findByPk($id);	
							$model->cod_estinstruccion=5;	
							if(is_null($model->fec_tarea)) $model->fec_tarea=$fec; 							
							$model->fec_asignacion=$fec; 
							$model->fec_cambioest=$fec; 							
							$model->fun_asignado=Yii::app()->user->id;
							if($model->save()){
								$x++;       			
	        					$flag=true;	
								$estado=new EstadoTarea;

								$this->loadTareaTmpEjecutar($codigo)->delete();	
							}//FIN SAVE MODEL
							else throw new Exception('Error al actualizar información');	
							
						}//FIN GUARDAR ESTADO
						else throw new Exception('Error al guardar ejecución de la tarea');
	        	}

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
					'message'=> 'Se ejecutaron las tareas de '.$x.' caso(s)'
				));
			}//
	    }//FIN ELSE
	}//FIN FUNCION INDEX



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////   GUARDAR ELIMINAR POR CASO     //////////////////////////////////////////////////////////////
////////////////////////////////   GUARDAR ELIMINAR POR CASO     //////////////// //////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionGuardarEliminarCaso(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$estado=new EstadoTarea;
	    	$x=0;
	    	$flag=false;
	        $transaction=$estado->dbConnection->beginTransaction();   
	        try{
	       	        	
	        	$ta = TmpEliminar::model()->getListTmpEliminar();
	        	foreach ($ta as $ta) {
						$codigo=$ta['cod_tmpeliminar'];
						$id=$ta['cod_bntarea'];
						$estado->cod_bntarea=$ta['cod_bntarea'];
						$estado->cod_estinstruccion=7;	
						$estado->fec_registro=date('Y-m-d H-i-s'); 		
						$estado->fun_rut=Yii::app()->user->id;
						$estado->fun_responsable=Yii::app()->user->id;	

						if($estado->save()){
							$model=BancoTarea::model()->findByPk($id);	
							$model->cod_estinstruccion=7;	
							if(is_null($model->fec_tarea)) $model->fec_tarea=date('Y-m-d'); 
							if(is_null($model->fec_asignacion)) $model->fec_asignacion=date('Y-m-d H-i-s'); 
							$model->fec_cambioest=date('Y-m-d H-i-s'); 								
							$model->fun_asignado=Yii::app()->user->id;
							if($model->save()){
								$x++;       			
	        					$flag=true;	
								$estado=new EstadoTarea;

								$this->loadEliminarTmpEstado($codigo)->delete();	
							}//FIN SAVE MODEL
							else throw new Exception('Error al actualizar información');	
							
						}//FIN GUARDAR ESTADO
						else throw new Exception('Error al guardar eliminar la tarea');
	        	}

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
					'message'=> 'Se eliminaron las tareas de '.$x.' caso(s)'
				));
			}//
	    }//FIN ELSE
	}//FIN FUNCION eliminar 




//LIMPIAR TABLA TEMPORAL PARA EJECUTAR//LIMPIAR TABLA TEMPORAL PARA EJECUTAR//LIMPIAR TABLA TEMPORAL PARA EJECUTAR//LIMPIAR TABLA TEMPORAL PARA EJECUTAR
//LIMPIAR TABLA TEMPORAL PARA EJECUTAR//LIMPIAR TABLA TEMPORAL PARA EJECUTAR//LIMPIAR TABLA TEMPORAL PARA EJECUTAR//LIMPIAR TABLA TEMPORAL PARA EJECUTAR
//LIMPIAR TABLA TEMPORAL PARA EJECUTAR//LIMPIAR TABLA TEMPORAL PARA EJECUTAR//LIMPIAR TABLA TEMPORAL PARA EJECUTAR//LIMPIAR TABLA TEMPORAL PARA EJECUTAR

	public function actionLimpiarTemporalEjecutar(){	 
		
		$rut=Yii::app()->user->id;
		TmpEjecutar::model()->deleteAll("fun_rut ='" .$rut . "'");

	}//FIN FUNCION 


//ELIMINAR TAREA PARA EJECUTAR//
	public function actionEliminarTareaTemporalEjecutar($id){	 
		
		$this->loadTareaTmpEjecutar($id)->delete();	

	}//FIN FUNCION 

	public function loadTareaTmpEjecutar($id){
		$model=TmpEjecutar::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

//LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR/////
//LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR/////
//LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR/////

	public function actionLimpiarTemporalAsignar(){	 
		
		$rut=Yii::app()->user->id;
		TmpAsignar::model()->deleteAll("fun_rut ='" .$rut . "'");

	}//FIN FUNCION 


//ELIMINAR TAREA POR ASIGNAR//
	public function actionEliminarTareaTemporal($id){	 
		
		$this->loadTareaTmp($id)->delete();	

	}//FIN FUNCION 

	public function loadTareaTmp($id){
		$model=TmpAsignar::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

//LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR/////
//LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR/////
//LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR///////LIMPIAR TABLA TEMPORAL PARA ASIGNAR/////

	public function actionLimpiarTemporalEliminar(){	 
		
		$rut=Yii::app()->user->id;
		TmpEliminar::model()->deleteAll("fun_rut ='" .$rut . "'");

	}//FIN FUNCION 



//ELIMINAR TAREA PARA ELIMINAR//
	public function actionEliminarTareaTmpEliminar($id){	 
		
		$this->loadEliminarTmpEstado($id)->delete();	

	}//FIN FUNCION 

	public function loadEliminarTmpEstado($id){
		$model=TmpEliminar::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}





//FIN CONTROLADOR
}