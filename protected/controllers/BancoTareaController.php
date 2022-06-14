<?php

date_default_timezone_set('America/Santiago'); 

class BancoTareaController extends Controller{

	public $layout='//layouts/column2';

	public function filters(){
		return array('accessControl', 'postOnly + delete',);
	}

	public function actionMisBloques(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$this->render('misBloques');
	    }//FIN ELSE
	}//FIN FUNCION INDEX



	public function actionListarMisBloques(){
		
		$ta = BancoTarea::model()->getListMisBloques();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:80px;'>RUC</th>
				<th>FISCAL</th>
				<th>DILIGENCIA</th>
				<th>T°</th>
				<th>FECHA PLAZO</th>
				<th>PRIORIDAD</th>
				<th>COMENTARIOS</th>
				
				<th>ASIGNADO A:</th>
				<th>DECRETADO POR:</th>
				<th class='check_misdil' style='width:30px;text-align:center'>EJECUTAR<input type='checkbox' class='checEjecutar' onClick='MarcarTodos(this)' /></th>
				<th style='width:30px;'>ANULAR<input type='checkbox' class='checAnular' onClick='MarcarTodosAnular(this)' /></th>
				<th>CODIGODEBARRARUC</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_tarea'];
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
			
			echo "<td>".$ta['funcionario']."</td>";
			echo "<td>".$ta['responsable']."</td>";  
			$id=$ta['cod_bntarea'];
			echo "<td style='text-align:center'><input type='checkbox' class='checEjecutar' name='ejecutar[".$i."]' id='ejecutar[".$i."]' value='".$id."'></td>";

			echo "<td  style='text-align:center'><input type='checkbox' class='checAnular' name='anular[".$i."]' id='anular[".$i."]' value='".$id."'></td>";

			?> <td><img src="../start/protected/extensions/barcode/barcode.php?text=<?php echo $ta['idf_rolunico']; ?>&codetype=Code39&print=true" /></td><?php
			
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
						'pageLength': 201,
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
				                stripHtml: false,
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 12 ]
				            }
						},
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


////////////////////////////////////////////////////////////////////////////////////
///////////////////// REGISTRAR TAREA //////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

	public function actionRegistrarTarea($ruc_decreta=false){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$tareas = TgInstruccion::model()->getDiligencias();


	    	$uni=Yii::app()->user->getState('unidad');
	    	$tguni = Unidad::model()->findByPk($uni);

	    	$fiscalias=Yii::app()->user->getState('fiscalia');
	    	$fi = Fiscalia::model()->findByPk($fiscalias);

	    	$this->render('registrarTarea', array('tareas'=>$tareas, 'tguni'=>$tguni, 'fi'=>$fi, 'ruc_decreta'=>$ruc_decreta));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionRegistrarTareaAsignar($ruc_decreta=false){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
			$fun = Funcionario::model()->getFuncionariosTodos();
	    	$tareas = TgInstruccion::model()->getDiligencias();

	    	$uni=Yii::app()->user->getState('unidad');
	    	$tguni = Unidad::model()->findByPk($uni);

	    	$fiscalias=Yii::app()->user->getState('fiscalia');
	    	$fi = Fiscalia::model()->findByPk($fiscalias);


	    	$this->render('registrarTareaAsignar', array('tareas'=>$tareas, 'fun'=>$fun, 'tguni'=>$tguni, 'fi'=>$fi, 'ruc_decreta'=>$ruc_decreta));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionRegistrarTareaAdmin(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
			$fun = Funcionario::model()->getFuncionariosTodos();
	    	$tareas = TgInstruccion::model()->getTareasAdmin();

	    	$uni=Yii::app()->user->getState('unidad');
	    	$tguni = Unidad::model()->findByPk($uni);

	    	$fiscalias=Yii::app()->user->getState('fiscalia');
	    	$fi = Fiscalia::model()->findByPk($fiscalias);
	    	
	    	$this->render('registrarTareaAdmin', array('tareas'=>$tareas, 'fun'=>$fun, 'tguni'=>$tguni, 'fi'=>$fi));
	    }//FIN ELSE
	}//FIN FUNCION INDEX



	public function actionMisDiligenciasEjecutadas(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$this->render('misDiligenciasEjecutadas');
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionMisDiligencias(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$this->render('misDiligencias');
	    }//FIN ELSE
	}//FIN FUNCION INDEX

	public function actionConsultaCargaLaboralFun(){
		$total = BancoTarea::model()->getCargaLaboralFun();
		if(isset($total[0]->min_ejecutado) && $total[0]->min_ejecutado > 0) echo "<h3>".$total[0]->min_ejecutado." minutos<h3>";
		else echo "<h3>0 minutos<h3>";
	}


	public function actionConsultaCargaLaboralFunFecha($fec, $fec_fin){
		$total = BancoTarea::model()->getCargaLaboralFunFecha($fec, $fec_fin);
		if(isset($total[0]->min_ejecutado) && $total[0]->min_ejecutado > 0) echo "<h3>".$total[0]->min_ejecutado." minutos<h3>";
		else echo "<h3>0 minutos<h3>";
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////// TAREAS SELECCIONADAS PARA INSTRUIR    ///////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function actionSeleccionTarea(){	

		$rut=Yii::app()->user->id;
		$dil = DilTmp::model()->getSelectDil($rut);
		$flag=false;

		$result = "<table id='ingresos' class='table table-striped table-bordered display nowrap'>";			
			foreach ($dil as $dil) {	
				$flag=true;
				$result .= "<tr><td>".$dil['tarea']."</td><td style='width: 35px;'> <span id='".$dil['cod_diltmp']."' name='eliminar' class='btn btn-warning' onclick='elimdil(this.id)'>X</span></td></tr>";
			}
		if($flag==true){
			$result .= "<tr><td colspan='2' style='text-align: center;'> <span id='".$rut."' name='eliminar' class='btn btn-warning' onclick='elimdiltodo(this.id)'>Resetear Diligencias</span></td></tr>";
		}

		$result .= "</table>";

		echo json_encode(array(
			'status' => 'success',
			'message'=> $result
		));

	}//FIN FUNCION 

	public function actionGuardarSeleccionTarea(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new DilTmp;
	    	$flag=false;

	        $transaction=$model->dbConnection->beginTransaction();   
	        try{  
	        		$model->fun_rut=Yii::app()->user->id;
	        		$model->cod_instruccion=$_POST['tarea']; 
	        		if($model->save()){	     
        				$flag=true;
	        		}
        			else throw new Exception('Error, no se puede asignar la tarea seleccionada');	       		
        		
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
				$rut=Yii::app()->user->id;
				$dil = DilTmp::model()->getSelectDil($rut);

				$result = "<table id='ingresos' class='table table-striped table-bordered display nowrap'>";			
				foreach ($dil as $dil) {	
					$result .= "<tr><td>".$dil['tarea']."</td><td style='width: 35px;'> <span id='".$dil['cod_diltmp']."' name='eliminar' class='btn btn-warning' onclick='elimdil(this.id)'>X</span></td></tr>";
				}
				$result .= "<tr><td colspan='2' style='text-align: center;'> <span id='".$rut."' name='eliminar' class='btn btn-warning' onclick='elimdiltodo(this.id)'>Resetear Diligencias</span></td></tr>";
				$result .= "</table>";

				echo json_encode(array(
					'status' => 'success',
					'message'=> $result
				));
			}//

	    }//FIN ELSE
	}//FIN FUNCION GUARDAR ASIGNACION

	public function actionEliminarSeleccionTarea(){	
 
		$id=$_POST['id'];
		$this->loadDil($id)->delete();	

		$rut=Yii::app()->user->id;
		$dil = DilTmp::model()->getSelectDil($rut);

		$result = "<table id='ingresos' class='table table-striped table-bordered display nowrap'>";			
			foreach ($dil as $dil) {	
				$result .= "<tr><td>".$dil['tarea']."</td><td style='width: 35px;'> <span id='".$dil['cod_diltmp']."' name='eliminar' class='btn btn-warning' onclick='elimdil(this.id)'>X</span></td></tr>";
			}
		$result .= "<tr><td colspan='2' style='text-align: center;'> <span id='".$rut."' name='eliminar' class='btn btn-warning' onclick='elimdiltodo(this.id)'>Resetear Diligencias</span></td></tr>";
		$result .= "</table>";

		echo json_encode(array(
			'status' => 'success',
			'message'=> $result
		));
	}

	public function actionEliminarSeleccionTareaTodos(){	
 
		$id=$_POST['id'];
		DilTmp::model()->deleteAll("fun_rut ='" .$id . "'");

		$result = "";

		echo json_encode(array(
			'status' => 'success',
			'message'=> $result
		));
	}


	public function loadDil($id){
		$model=DilTmp::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////     GUARDAR TAREAS EJECUTADAS FUNCIONARIO    ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionGuardarEjecutarTareaFun($ejec, $obs){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$estado=new EstadoTarea;
	    	$x=0;
	    	$flag=false;
	        $transaction=$estado->dbConnection->beginTransaction();   
	        try{
	        	if( empty($ejec) ) throw new Exception('Error, debe seleccionar minímo 1 tarea para ejecutar.');
	        	$array = explode(",", $ejec); 

	        	foreach ($array as $c => $value) {
	        		if($array[$c]<>"on" && $array[$c]<>""){

						$estado->cod_bntarea=$array[$c];
						$estado->cod_estinstruccion=5;	
						if(isset($obs))	$estado->gls_observacion=$obs;	
						$estado->fec_registro=date('Y-m-d H-i-s'); 		
						$estado->fun_rut=Yii::app()->user->id;
						$estado->fun_responsable=Yii::app()->user->id;	

						if($estado->save()){
							$model=BancoTarea::model()->findByPk($array[$c]);	
							$model->cod_estinstruccion=5;	
							if(isset($obs))	$model->gls_observacion=$obs;	
							$model->fec_cambioest=date('Y-m-d H-i-s'); 							
							$model->fun_asignado=Yii::app()->user->id;
							if($model->save()){
								$x++;       			
	        					$flag=true;	
								$estado=new EstadoTarea;
							}//FIN SAVE MODEL
							else throw new Exception('Error al actualizar información');	
							
						}//FIN GUARDAR ESTADO
						else throw new Exception('Error al guardar ejecución de la tarea');	
							
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
					'message'=> 'Se ejecutaron las tareas de '.$x.' caso(s)'
				));
			}//
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionGuardarAnularTareaFun($anular, $obs){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$estado=new EstadoTarea;
	    	$x=0;
	    	$flag=false;
	        $transaction=$estado->dbConnection->beginTransaction();   
	        try{
	        	if( empty($anular) ) throw new Exception('Error, debe seleccionar minímo 1 tarea para anular.');
	        	$array = explode(",", $anular); 

	        	foreach ($array as $c => $value) {
	        		if($array[$c]<>"on" && $array[$c]<>""){

						$estado->cod_bntarea=$array[$c];
						$estado->cod_estinstruccion=6;	
						if(isset($obs))	$estado->gls_observacion=$obs;	
						$estado->fec_registro=date('Y-m-d H-i-s'); 		
						$estado->fun_rut=Yii::app()->user->id;
						$estado->fun_responsable=Yii::app()->user->id;	

						if($estado->save()){
							$model=BancoTarea::model()->findByPk($array[$c]);	
							$model->cod_estinstruccion=6;	
							$model->fec_cambioest=date('Y-m-d H-i-s'); 							
							$model->fun_asignado=Yii::app()->user->id;
							if(isset($obs))	$model->gls_observacion=$obs;	
							if($model->save()){
								$x++;       			
	        					$flag=true;	
								$estado=new EstadoTarea;
							}//FIN SAVE MODEL
							else throw new Exception('Error al actualizar información');	
							
						}//FIN GUARDAR ESTADO
						else throw new Exception('Error al guardar anulación de la tarea');	
							
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
					'message'=> 'Se anularon las tareas de '.$x.' caso(s)'
				));
			}//
	    }//FIN ELSE
	}//FIN FUNCION INDEX




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////// GUARDAR TAREAS REGISTRADAS POR FISCAL O PRE  ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function actionGuardarTarea(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new BancoTarea;
	    	$flag=false;
	    	$ind_carp=false;

	        $transaction=$model->dbConnection->beginTransaction();   
	        try{  

	        	$rut=Yii::app()->user->id;
				$dil = DilTmp::model()->getSelectDil($rut);
						
				foreach ($dil as $dil) {
					$model->idf_rolunico=strtoupper($_POST['ruc']); 
	        		$model->fis_codigo=Yii::app()->user->getState('fiscalia');
	        		$model->cod_instruccion=$dil['cod_instruccion']; 
	        		$model->gls_comentario=$_POST['obs'];  
	        		$model->cod_estinstruccion=1; 
	        		$model->cod_prioridad=$_POST['priori'];  
	        		$model->fun_rut=Yii::app()->user->id;

	        		if( Yii::app()->user->getState('fiscalia') == 801 && Yii::app()->user->getState('unidad')==28 ){

	        			if( $model->cod_instruccion == 69 || $model->cod_instruccion == 71 || $model->cod_instruccion == 72 || $model->cod_instruccion == 73 || $model->cod_instruccion == 74
	        			||  $model->cod_instruccion == 75 ||  $model->cod_instruccion == 76 ){
	        				$model->uni_codigo=26;
	        			}
	        			else{
	        				$model->uni_codigo=Yii::app()->user->getState('unidad');
	        			}

	        		}
	        		else{
	        			$model->uni_codigo=Yii::app()->user->getState('unidad');
	        		}
	        		
	        		$model->fec_registro=date('Y-m-d h:m:s');    		
			
	        		if($model->save()){	 

	        			$estado=new EstadoTarea;
			        	$estado->cod_bntarea=$model->cod_bntarea;
						$estado->cod_estinstruccion=1;	
						$estado->fec_registro=date('Y-m-d H-i-s'); 		
						$estado->fun_rut=Yii::app()->user->id;	
						$estado->fun_responsable=Yii::app()->user->id;	  
						if($estado->save()){

							if( isset($_POST['carp']) && $ind_carp==false ){
								$carp=new Carpeta;
								$carp->idf_rolunico=$model->idf_rolunico; 
				        		$carp->fis_codigo=$model->fis_codigo;
				        		$carp->cod_estcarpeta=2; 
				        		$carp->fec_registro=date('Y-m-d H:i:s'); 
				        		$carp->cod_tipubicacion=2; 
				        		$carp->cod_ubicacion=2;
				        		$carp->ind_ultmov=1; 
				        		$carp->fun_responsable=Yii::app()->user->id;	

				        		if($carp->save()){
				        			$ind_carp=true;
				        		}
				        		else throw new Exception('Error, no se pudo mover la carpeta');
							}//fin incluye mov de carpeta

							$model=new BancoTarea;  
							$flag=true;
						}
						else throw new Exception('Error, no se puede generar el estado');

	        		}
        			else throw new Exception('Error, no se puede generar la tarea');  
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
					'message'=> 'Tarea registrada'
				));
			}//
			else{
				echo json_encode(array(
					'status' => 'error',
					'message'=> 'Debes indicar una diligencia'
				));
			}

	    }//FIN ELSE
	}//FIN FUNCION GUARDAR


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////          GUARDAR TAREASY ASIGNAR             ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function actionGuardarTareaAsignar(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new BancoTarea;
	    	$flag=false;
	    	$ind_carp=false;

	        $transaction=$model->dbConnection->beginTransaction();   
	        try{  
	        	
	        	if( empty($_POST['fun']) ) throw new Exception('Error, indicar funcionario');
	        	if( empty($_POST['fec_asig']) ) throw new Exception('Error, indicar fecha de la diligencia');

	        	$rut=Yii::app()->user->id;
				$dil = DilTmp::model()->getSelectDil($rut);
				
			
				foreach ($dil as $dil) {
					$model->idf_rolunico=strtoupper($_POST['ruc']); 
	        		$model->fis_codigo=Yii::app()->user->getState('fiscalia');
	        		$model->cod_instruccion=$dil['cod_instruccion']; 
	        		$model->gls_comentario=$_POST['obs'];  
	        		$model->cod_estinstruccion=3; 
	        		$model->cod_prioridad=$_POST['priori'];  
	        		$model->fun_asignado=$_POST['fun'];   
	        		$model->fec_tarea=$_POST['fec_asig'];
	        		$model->fec_asignacion=date('Y-m-d H-i-s'); 
	        		$model->fun_rut=Yii::app()->user->id;
	        		$model->uni_codigo=Yii::app()->user->getState('unidad');
	        		$model->fec_registro=date('Y-m-d h:m:s');    		
			
	        		if($model->save()){	 
	        			$estado=new EstadoTarea;
			        	$estado->cod_bntarea=$model->cod_bntarea;
						$estado->cod_estinstruccion=3;	
						$estado->fec_registro=date('Y-m-d H-i-s'); 		
						$estado->fun_rut=$model->fun_asignado;	
						$estado->fun_responsable=Yii::app()->user->id;	  
						if($estado->save()){

							if( isset($_POST['carp']) && $ind_carp==false ){
								$carp=new Carpeta;
								$carp->idf_rolunico=$model->idf_rolunico; 
				        		$carp->fis_codigo=$model->fis_codigo;
				        		$carp->cod_estcarpeta=2; 
				        		$carp->fec_registro=date('Y-m-d H:i:s'); 
				        		$carp->cod_tipubicacion=1; 
				        		$carp->cod_ubicacion=$model->fun_asignado;
				        		$carp->ind_ultmov=1; 
				        		$carp->fun_responsable=Yii::app()->user->id;	

				        		if($carp->save()){
				        			$ind_carp=true;
				        		}
				        		else throw new Exception('Error, no se pudo mover la carpeta');
							}//fin incluye mov de carpeta
						
							$model=new BancoTarea;  
							$flag=true;
						}
						else throw new Exception('Error, no se puede generar el estado');
	        		}
        			else throw new Exception('Error, no se puede generar la tarea');  
				
			
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
					'message'=> 'Tarea registrada'
				));
			}//
			else{
				echo json_encode(array(
					'status' => 'error',
					'message'=> 'Debes indicar Fecha y Tarea'
				));
			}

	    }//FIN ELSE
	}//FIN FUNCION GUARDAR








/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////          GUARDAR TAREAS ADMINISTRATIVAS      ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function actionGuardarTareaAdmin(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{ 
	    	$model=new BancoTarea;
	    	$flag=false;

	        $transaction=$model->dbConnection->beginTransaction();   
	        try{  

	        	if( empty($_POST['fec_ini']) ) throw new Exception('Error, indicar fecha de la tarea');

	        	if($_POST['fec_ini']<>"" &&  $_POST['fec_fin']<>"" ){
		        	$fecha=$_POST['fec_fin'];
		        	$fec_act=$_POST['fec_ini'];
					$dias = (strtotime($fecha) - strtotime($fec_act)) / 60 / 60 / 24;
					$dias=intval($dias)+1;
					$i=0;

					
					while($i<$dias){

						$rut=Yii::app()->user->id;
						$dil = DilTmp::model()->getSelectDil($rut);

						foreach ($dil as $dil) {
							if(isset($_POST['ruc']) && $_POST['ruc']<>"") $model->idf_rolunico=strtoupper($_POST['ruc']); 
	        				else $model->idf_rolunico='Tarea';	
							
			        		$model->fis_codigo=Yii::app()->user->getState('fiscalia');
			        		$model->cod_instruccion=$dil['cod_instruccion']; 
			        		$model->gls_comentario=$_POST['obs'];  
			        		$model->cod_estinstruccion=3; 
			        		$model->cod_prioridad=4;  
			        		$model->fun_asignado=$_POST['fun'];   
			        		$model->fec_tarea=date('Y-m-d', strtotime('+'.$i.' day', strtotime($fec_act))); 
			        		$model->fec_asignacion=date('Y-m-d', strtotime('+'.$i.' day', strtotime($fec_act))); 
			        		$model->fec_cambioest=date('Y-m-d', strtotime('+'.$i.' day', strtotime($fec_act))); 
			        		$model->fun_rut=Yii::app()->user->id;
			        		$model->uni_codigo=Yii::app()->user->getState('unidad');
			        		$model->fec_registro=date('Y-m-d h:m:s');    		
					
			        		if($model->save()){	 
			        			

			        			$estado=new EstadoTarea;
			        			$estado->cod_bntarea=$model->cod_bntarea;
								$estado->cod_estinstruccion=3;	
								$estado->fec_registro=date('Y-m-d H-i-s'); 		
								$estado->fun_rut=$model->fun_asignado;
								$estado->fun_responsable=Yii::app()->user->id;	  
								if($estado->save()){
									$model=new BancoTarea;  
									$flag=true;
								}
								else throw new Exception('Error, no se puede generar el estado');
		        				
			        		}
		        			else throw new Exception('Error, no se puede generar la tarea');  


						}//FIN FOREACH TAREAS
						$i++;

					}//FIN WHILE DIAS
				}//FIN IF POST FECHAS	

	        	        		
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
					'message'=> 'Tarea registrada'
				));
			}//
			else{
				echo json_encode(array(
					'status' => 'error',
					'message'=> 'Debes indicar Fecha y Tarea'
				));
			}

	    }//FIN ELSE
	}//FIN FUNCION GUARDAR


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////          LISTAR TAREAS ADMINISTRATIVAS       ////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//LISTAR TAREAS//
	public function actionListarTareaAdmin(){
		
		$ta = BancoTarea::model()->getListadoAdmin();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th>TAREA</th>
				<th>MINUTOS</th>
				<th>COMENTARIOS</th>
				<th>FECHA TAREA</th>
				<th>ASIGNADO A:</th>
				<th style='width:20px;'>X</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_tarea'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['tarea']."</td>"; 
			echo "<td>".$ta['tiempo']."</td>"; 
			echo "<td>".$ta['gls_comentario']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			echo "<td>".$ta['funcionario']."</td>"; 
			
			$id=$ta['cod_bntarea'];
			echo "<td> <span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimTarea(this.id)'>X</span></td>";

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






/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////          LISTAR TAREAS FISCAL O PRE          ////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//LISTAR TAREAS//
	public function actionListarTarea(){
		
		$ta = BancoTarea::model()->getListadoInstruidas();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:85px;'>RUC</th>
				<th>DILIGENCIA</th>
				<th>TIEMPO</th>
				<th>PRIORIDAD</th>
				<th>COMENTARIOS</th>
				<th>FECHA DECRETA</th>
				<th style='width:20px;'>X</th>
				<th>CODIGODEBARRARUC</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_registro'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['idf_rolunico']."</td>"; 
			echo "<td>".$ta['tarea']."</td>";
			echo "<td>".$ta['tiempo']."</td>"; 
			if($ta['cod_prioridad']==3) echo "<td><span class='badge-danger'>".$ta['priori']."</span></td>";
			else echo "<td>".$ta['priori']."</td>"; 
			echo "<td>".$ta['gls_comentario']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			$id=$ta['cod_bntarea'];
			echo "<td> <span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimTarea(this.id)'>X</span></td>";
			?> <td><img src="../start/protected/extensions/barcode/barcode.php?text=<?php echo $ta['idf_rolunico']; ?>&codetype=Code39&print=true" /> </td><?php

			$n++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
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

	}//FIN FUNCTION	
//FIN FUNCTIOn

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////          LISTAR TAREAS ASIGNADAS       /////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function actionListarTareaAsignada(){
		
		$ta = BancoTarea::model()->getListadoInstruidasAsig();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' >
        <thead align='left'>
            <tr>
            	<th style='width:5px;'>N°</th>
				<th style='width:90px;'>RUC</th>
				<th style='width:95px;'>DILIGENCIA</th>
				<th style='width:10px;'>T°</th>
				<th style='width:85px;'>PRIORIDAD</th>
				<th style='width:350px;'>COMENTARIOS</th>
				<th style='width:95px;'>FECHA PLAZO</th>
				<th style='width:85px;'>ASIGNADO A:</th>
				<th style='width:20px;'>X</th>
				<th style='width:2px;'>CODIGODEBARRARUC</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_tarea'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['idf_rolunico']."</td>"; 
			echo "<td>".$ta['tarea']."</td>"; 
			echo "<td>".$ta['tiempo']."</td>"; 
			if($ta['cod_prioridad']==3) echo "<td><span class='badge-danger'>".$ta['priori']."</span></td>";
			else echo "<td>".$ta['priori']."</td>";
			echo "<td>".$ta['gls_comentario']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			echo "<td>".$ta['funcionario']."</td>"; 
			$id=$ta['cod_bntarea'];
			echo "<td> <span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimTarea(this.id)'>X</span></td>";
			?> <td><img src="../start/protected/extensions/barcode/barcode.php?text=<?php echo $ta['idf_rolunico']; ?>&codetype=Code39&print=true" /></td><?php

			$n++;
		}		
		echo "</tbody>"; 
		echo "</table>";
		echo "<br>";echo "<br>";echo "<br>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 50,
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
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 9 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////          LISTAR MIS DILIGENCIAS        /////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionListarMisDiligencias(){
		
		$ta = BancoTarea::model()->getListMisDiligencias();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:80px;'>RUC</th>
				<th>FISCAL</th>
				<th>DILIGENCIA</th>
				<th>T°</th>
				<th>FECHA PLAZO</th>
				<th>PRIORIDAD</th>
				<th>COMENTARIOS</th>
				<th>ASIGNADO A:</th>
				<th>DECRETADO POR:</th>
				<th class='check_misdil' style='width:30px;text-align:center'>EJECUTAR<input type='checkbox' class='checEjecutar' onClick='MarcarTodos(this)' /></th>
				<th style='width:30px;'>ANULAR<input type='checkbox' class='checAnular' onClick='MarcarTodosAnular(this)' /></th>
				<th>CODIGODEBARRARUC</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_tarea'];
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
			echo "<td>".$ta['funcionario']."</td>";
			echo "<td>".$ta['responsable']."</td>";  

		//	$id=$ta['idf_rolunico'];
		//	echo "<td> <a href='http://172.17.123.21/sia/start/index.php?r=CarpetaDigital/consultarCarpetaDigital&ruc=".$id."' target='_blank'><span class='btn btn-warning'>Ver Causa</span></td>";

			$id=$ta['cod_bntarea'];
			echo "<td style='text-align:center'><input type='checkbox' class='checEjecutar' name='ejecutar[".$i."]' id='ejecutar[".$i."]' value='".$id."'></td>";

			echo "<td  style='text-align:center'><input type='checkbox' class='checAnular' name='anular[".$i."]' id='anular[".$i."]' value='".$id."'></td>";

			?> <td><img src="../start/protected/extensions/barcode/barcode.php?text=<?php echo $ta['idf_rolunico']; ?>&codetype=Code39&print=true" /></td><?php

			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
						'pageLength': 201,
						'scrollX': true,
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
				                stripHtml: false,
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
				            }
						},
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




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////          LISTAR MIS DILIGENCIAS        /////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionListarMisDiligenciasEjecutadas($fec, $fec_fin){
		
		$ta = BancoTarea::model()->getListMisDiligenciasEjecu($fec, $fec_fin);

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:80px;'>RUC</th>
				<th>DILIGENCIA</th>
				<th>TIEMPO</th>
				<th>FECHA DILIGENCIA</th>
				<th>PRIORIDAD</th>
				<th>COMENTARIOS</th>
				
				<th>ASIGNADO A:</th>
				<th>ESTADO</th>
				<th>FECHA EJECUCION</th>
				
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_tarea'];
			$fecha_e=$ta['fec_cambioest'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['idf_rolunico']."</td>"; 
			echo "<td>".$ta['tarea']."</td>";
			echo "<td>".$ta['tiempo']."</td>";   
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			echo "<td>".$ta['cod_prioridad']."</td>"; 
			echo "<td>".$ta['gls_comentario']."</td>"; 
			
			echo "<td>".$ta['funcionario']."</td>";
			echo "<td>".$ta['estado']."</td>";
			echo "<td>".date("d-m-Y", strtotime($fecha_e))."</td>";
			
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





//ELIMINAR TAREA POR ASIGNAR//
	public function actionEliminarTarea(){	
 
		$id=$_POST['id'];
		$this->loadTarea($id)->delete();	

	}//FIN FUNCION 

	public function loadTarea($id){
		$model=BancoTarea::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////     CONSULTAR DILIGENCIAS DECRETADAS    ////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionDiligenciasDecretadas(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	        $this->render('diligenciasDecretadas');  
	    }//FIN ELSE
	}//FIN FUNCION INDEX




	public function actionListarDiligenciasDecretadas($fec, $fec_fin){
		
		$ta = BancoTarea::model()->getListDiligenciasDecretadas($fec, $fec_fin);

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:85px;'>RUC</th>
				<th>FISCAL</th>
				<th>DILIGENCIA</th>
				<th>FECHA DECRETA</th>
				<th>PRIORIDAD</th>
				<th>OBSERVACIONES</th>
				<th>FECHA ASIGNACION</th>
				<th>ASIGNADO A:</th>
				<th>ESTADO ACTUAL</th>
				<th>FECHA ESTADO ACTUAL</th>
				<!--<th style='width: 300px;'>CODIGODEBARRARUC</th>-->
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
			echo "<td>".date("d-m-Y", strtotime($fecha_ta))."</td>";
			if($ta['cod_prioridad']==3) echo "<td><span class='badge-danger'>".$ta['priori']."</span></td>";
			else echo "<td>".$ta['priori']."</td>";
			echo "<td>".$ta['gls_comentario']."</td>"; 
			if($fecha<>"") echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			else echo "<td></td>";

			echo "<td>".$ta['funcionario']."</td>"; 

			echo "<td>".$ta['estado']."</td>"; 
			
			if($fecha_cam<>"") echo "<td>".date("d-m-Y", strtotime($fecha_cam))."</td>";
			else echo "<td></td>";

			//if($ta['idf_rolunico'] <> 'Tarea'){
			//}
			//else echo "<td></td>";
		
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
			                'targets': [ 10 ],
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
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////     CONSULTAR DILIGENCIAS ASIGNADAS    ////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionDiligenciasAsignadas(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$meses = BancoTarea::model()->getMesesAsignadas();

	        $this->render('diligenciasAsignadas', array('meses'=>$meses));  
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionListarDiligenciasAsignadas($mes=false){
		
		$ta = BancoTarea::model()->getListDiligenciasAsignadas($mes);

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:85px;'>RUC</th>
				<th>FISCAL</th>
				<th>DILIGENCIA</th>
				<th>FECHA PLAZO</th>
				<th>PRIORIDAD</th>
				
				<th>ESTADO</th>
				<th>FECHA ASIGNACION</th>
				<th>ASIGNADO A:</th>
				<th>FECHA DECRETA</th>
				<th>DECRETADO POR:</th>
				<th>CODIGODEBARRARUC</th>
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($ta as $ta) {	
			$fecha_ta=$ta['fec_tarea'];
			$fecha=$ta['fec_asignacion'];
			$fecha_decreta=$ta['fec_registro'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['idf_rolunico']."</td>"; 
			echo "<td>".$ta['fiscal']."</td>";  
			echo "<td>".$ta['tarea']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha_ta))."</td>";
			if($ta['cod_prioridad']==3) echo "<td><span class='badge-danger'>".$ta['priori']."</span></td>";
			else echo "<td>".$ta['priori']."</td>";
			//echo "<td>".$ta['gls_comentario']."</td>"; 
			
			echo "<td>".$ta['estado']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			echo "<td>".$ta['funcionario']."</td>";  

			echo "<td>".date("d-m-Y", strtotime($fecha_decreta))."</td>";
			echo "<td>".$ta['responsable']."</td>"; 

			?> <td><img src="../start/protected/extensions/barcode/barcode.php?text=<?php echo $ta['idf_rolunico']; ?>&codetype=Code39&print=true" /></td><?php
			
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		


		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 50,
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
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////     CONSULTAR DILIGENCIAS POR CASO     ////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionConsultaCaso(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	        $this->render('consultaCaso');  
	    }//FIN ELSE
	}//FIN FUNCION INDEX

	public function actionConsultaDetalleCaso(){

		$id = $_POST['valor'];

		$fis = BancoTarea::model()->getFiscalAsignado($id);

		if(isset($fis)){
			echo "<h3 class='ubicacionActual'>RUC: ".$id." </h3>";
			if(isset($fis[0]->fiscal)) echo "<div class='ubicacionActual'><h3>Fiscal Asignado: ".$fis[0]->fiscal." </h3></div>";
	
			
			
		}

		$ta = BancoTarea::model()->getDiligenciaCaso($id);
		
		echo "<table id='ingresos' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
                <th style='width:85px;'>RUC</th>
                <th>DILIGENCIA</th>
                <th>PRIORIDAD</th>
                <th>DECRETADO POR:</th>
                 <th>FECHA DECRETA</th>

                <th>COMENTARIOS</th>                
                <th>FECHA PLAZO</th>
                
                <th>ASIGNADO A:</th> 
                <th>FECHA ASIGNACION</th>
                <th>ASIGNADO POR:</th>

                <th>ESTADO</th> 
                <th>FECHA ESTADO</th> 
                
               
            </tr>
        </thead>";

		echo "<tbody>";
		
		$n=1;
		foreach ($ta as $ta) {
			$fecha=$ta['fec_tarea'];
			$fecha_asigna=$ta['fec_asignacion'];
			$fecha_de=$ta['fec_registro'];
			$fecha_est=$ta['fec_cambioest'];
			echo "<tr class='carpetas'>";
			echo "<td>".$ta['idf_rolunico']."</td>";
			echo "<td>".$ta['tarea']."</td>";
			if($ta['cod_prioridad']==3) echo "<td><span class='badge-danger'>".$ta['priori']."</span></td>";
			else echo "<td>".$ta['priori']."</td>";
			echo "<td>".$ta['responsable']."</td>";
			echo "<td>".date("d-m-Y", strtotime($fecha_de))."</td>";	


			echo "<td>".$ta['gls_comentario']."</td>";
			if($fecha <> '' && $fecha <> '0000-00-00') echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			else echo "<td></td>";
			
			echo "<td>".$ta['funcionario']."</td>";	

			if($fecha_asigna <> '' && $fecha_asigna <> '0000-00-00') echo "<td>".date("d-m-Y", strtotime($fecha_asigna))."</td>";
			else echo "<td></td>";
			
			echo "<td>".$ta['asignador']."</td>";	
				
			
			echo "<td>".$ta['estado']."</td>";
			if($fecha_est <> '' && $fecha_est <> '0000-00-00') echo "<td>".date("d-m-Y", strtotime($fecha_est))."</td>";
			elseif($fecha_asigna <> '') echo "<td>".date("d-m-Y", strtotime($fecha_asigna))."</td>";
			else echo "<td></td>";


			echo "</tr>";
			$n++;
		}
		
		echo "</tbody>"; 
		echo "</table>";

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#ingresos').DataTable( {
					
				
			        
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
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
				            }
						},
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

	}//FIN CARPETA consulta



/////////////////////// ACCESO A LAS DILIGENCIAS DE UN CASO DESDE CARPETA DIGITAL  ///////

public function actionConsultaCasoDigital($ruc_decreta=false){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	        $this->render('consultaCasoDigital', array('ruc_decreta'=>$ruc_decreta));  
	    }//FIN ELSE
	}//FIN FUNCION INDEX



public function actionConsultaDetalleCasoDigital(){

		$id = $_POST['valor'];

		$fis = BancoTarea::model()->getFiscalAsignado($id);

		if(isset($fis)){
			echo "<h3 class='ubicacionActual'>RUC: ".$id." </h3>";
			if(isset($fis[0]->fiscal)) echo "<div class='ubicacionActual'><h3>Fiscal Asignado: ".$fis[0]->fiscal." </h3></div>";
	
			
			
		}

		$ta = BancoTarea::model()->getDiligenciaCaso($id);
		
		echo "<table id='ingresos' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
                <th style='width:85px;'>RUC</th>
                <th>DILIGENCIA</th>
                <th>PRIORIDAD</th>
                <th>DECRETADO POR:</th>
                 <th>FECHA DECRETA</th>

                <th>COMENTARIOS</th>                
                <th>FECHA PLAZO</th>
                
                <th>ASIGNADO A:</th> 
                <th>FECHA ASIGNACION</th>
                <th>ASIGNADO POR:</th>

                <th>ESTADO</th> 
                <th>FECHA ESTADO</th> 
                
               
            </tr>
        </thead>";

		echo "<tbody>";
		
		$n=1;
		foreach ($ta as $ta) {
			$fecha=$ta['fec_tarea'];
			$fecha_asigna=$ta['fec_asignacion'];
			$fecha_de=$ta['fec_registro'];
			$fecha_est=$ta['fec_cambioest'];
			echo "<tr class='carpetas'>";
			echo "<td>".$ta['idf_rolunico']."</td>";
			echo "<td>".$ta['tarea']."</td>";
			if($ta['cod_prioridad']==3) echo "<td><span class='badge-danger'>".$ta['priori']."</span></td>";
			else echo "<td>".$ta['priori']."</td>";
			echo "<td>".$ta['responsable']."</td>";
			echo "<td>".date("d-m-Y", strtotime($fecha_de))."</td>";	


			echo "<td>".$ta['gls_comentario']."</td>";
			if($fecha <> '' && $fecha <> '0000-00-00') echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			else echo "<td></td>";
			
			echo "<td>".$ta['funcionario']."</td>";	

			if($fecha_asigna <> '' && $fecha_asigna <> '0000-00-00') echo "<td>".date("d-m-Y", strtotime($fecha_asigna))."</td>";
			else echo "<td></td>";
			
			echo "<td>".$ta['asignador']."</td>";	
				
			
			echo "<td>".$ta['estado']."</td>";
			if($fecha_est <> '' && $fecha_est <> '0000-00-00') echo "<td>".date("d-m-Y", strtotime($fecha_est))."</td>";
			elseif($fecha_asigna <> '') echo "<td>".date("d-m-Y", strtotime($fecha_asigna))."</td>";
			else echo "<td></td>";


			echo "</tr>";
			$n++;
		}
		
		echo "</tbody>"; 
		echo "</table>";

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#ingresos').DataTable( {
					
				
			        
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
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
				            }
						},
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

	}//FIN CARPETA consulta




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////     CONSULTAR DILIGENCIAS ASIGNADAS  TODASSSSSS  ////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionDiligenciasTodas(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$meses = BancoTarea::model()->getMesesTodas();

	        $this->render('diligenciasTodas', array('meses'=>$meses));  
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionListarDiligenciasAsignadasTODAS($mes=false){
		
		$ta = BancoTarea::model()->getListDiligenciasAsignadasTodas($mes);

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:85px;'>RUC</th>
				<th>FISCAL</th>
				<th>DILIGENCIA</th>
				<th>TIEMPO TAREA</th>
				<th>FECHA PLAZO</th>
				<th>PRIORIDAD</th>
				<th>ESTADO</th>
				<th>FECHA ESTADO</th>
				<th>COMENTARIOS</th>
				<th>FECHA ASIGNACION</th>
				<th>ASIGNADO A:</th>
				<th>FECHA DECRETA</th>
				<th>DECRETADO POR:</th>		
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($ta as $ta) {	
			$fecha_ta=$ta['fec_tarea'];
			$fecha=$ta['fec_asignacion'];
			$fecha_decreta=$ta['fec_registro'];
			$fec_estado=$ta['fec_cambioest'];

			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['idf_rolunico']."</td>"; 
			echo "<td>".$ta['fiscal']."</td>";  
			echo "<td>".$ta['tarea']."</td>"; 
			echo "<td>".$ta['tiempo']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha_ta))."</td>";
			if($ta['cod_prioridad']==3) echo "<td><span class='badge-danger'>".$ta['priori']."</span></td>";
			else echo "<td>".$ta['priori']."</td>";
			//echo "<td>".$ta['gls_comentario']."</td>"; 
			
			echo "<td>".$ta['estado']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fec_estado))."</td>";
			echo "<td>".$ta['gls_comentario']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			echo "<td>".$ta['funcionario']."</td>";  

			echo "<td>".date("d-m-Y", strtotime($fecha_decreta))."</td>";
			echo "<td>".$ta['responsable']."</td>"; 

			?> 
			<?php
			
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		


		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 50,
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
				                stripHtml: false,
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11,12, 13]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	


//////////////////////////   LISTADO TAREAS SIN EJECUTAR UGI ///////////////////


public function actionDiligenciasTodasSinEjecutarUGI(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	//$meses = BancoTarea::model()->getMesesTodas();
	        $this->render('diligenciasTodasSinEjecutarUGI');  
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionListardiligenciasTodasSinEjecutarUGI(){
		
		$ta = BancoTarea::model()->getListDiligenciasTodasSinEjecutarUGI();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th style='width:85px;'>RUC</th>
				<th>FISCAL</th>
				<th>DILIGENCIA</th>
				<th>TIEMPO TAREA</th>
				<th>FECHA PLAZO</th>
				<th>PRIORIDAD</th>
				<th>ESTADO</th>
				<th>FECHA ESTADO</th>
				<th>COMENTARIOS</th>
				<th>FECHA ASIGNACION</th>
				<th>ASIGNADO A:</th>
				<th>FECHA DECRETA</th>
				<th>DECRETADO POR:</th>		
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($ta as $ta) {	
			$fecha_ta=$ta['fec_tarea'];
			$fecha=$ta['fec_asignacion'];
			$fecha_decreta=$ta['fec_registro'];
			$fec_estado=$ta['fec_cambioest'];

			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['idf_rolunico']."</td>"; 
			echo "<td>".$ta['fiscal']."</td>";  
			echo "<td>".$ta['tarea']."</td>"; 
			echo "<td>".$ta['tiempo']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha_ta))."</td>";
			if($ta['cod_prioridad']==3) echo "<td><span class='badge-danger'>".$ta['priori']."</span></td>";
			else echo "<td>".$ta['priori']."</td>";
			//echo "<td>".$ta['gls_comentario']."</td>"; 
			
			echo "<td>".$ta['estado']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fec_estado))."</td>";
			echo "<td>".$ta['gls_comentario']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			echo "<td>".$ta['funcionario']."</td>";  

			echo "<td>".date("d-m-Y", strtotime($fecha_decreta))."</td>";
			echo "<td>".$ta['responsable']."</td>"; 

			?> 
			<?php
			
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		


		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 50,
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
				                stripHtml: false,
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11,12, 13]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	



















//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////                  ELIMINAR TAREAS                //////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	public function actionEliminarTareaAdmin(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$fun = Funcionario::model()->getFuncionariosTodos();

	    	$this->render('eliminarTareaAdmin', array('fun'=>$fun));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionListarTareasAdministrativas(){
		
		$rut=$_POST['fun'];
		$fec_ini=$_POST['fec'];
		$fec_fin=$_POST['fec_fin'];

		$ta = BancoTarea::model()->getTareasAdminFun($rut, $fec_ini, $fec_fin);

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				
				<th>DILIGENCIA</th>
				<th>TIEMPO</th>				
				<th>COMENTARIOS</th>
				<th>FECHA TAREA</th>
				<th>ASGINADO A:</th>
				<th>DECRETADO POR:</th>
				<th style='width:20px;'>X</th>
				
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_tarea'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['tarea']."</td>";
			echo "<td>".$ta['tiempo']." min.</td>"; 
	
			echo "<td>".$ta['gls_comentario']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>";
			echo "<td>".$ta['funcionario']."</td>"; 
			echo "<td>".$ta['responsable']."</td>"; 
			$id=$ta['cod_bntarea'];
			echo "<td> <span id='".$id."' name='eliminar' class='btn btn-warning' onclick='elimTarea(this.id)'>X</span></td>";
			

			$n++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
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

	}//FIN FUNCTION	


//ELIMINAR TAREA POR ASIGNAR//
	public function actionEliminarTareaAdministrativa(){	
 
		$id=$_POST['id'];

		$model=BancoTarea::model()->findByPk($id);

		$new=new ElimTarea;
		$new->cod_bntarea=$model->cod_bntarea;
		$new->idf_rolunico=$model->idf_rolunico;
		$new->fis_codigo=$model->fis_codigo;
		$new->cod_instruccion=$model->cod_instruccion;
		$new->gls_comentario=$model->gls_comentario;
		$new->fec_tarea=$model->fec_tarea;
		$new->fec_asignacion=$model->fec_asignacion;
		$new->cod_estinstruccion=$model->cod_estinstruccion;
		$new->fec_cambioest=$model->fec_cambioest;
		$new->fun_asignado=$model->fun_asignado;
		$new->fun_rut=$model->fun_rut;
		$new->ind_dias=$model->ind_dias;		
		$new->cod_prioridad=$model->cod_prioridad;
		$new->cod_plantrabajo=$model->cod_plantrabajo;
		$new->fec_registro=$model->fec_registro;
		$new->fec_eliminacion=date('Y-m-d h:m:s');  
		$new->fun_responsable=Yii::app()->user->id;  

		if($new->save()){			
			$this->loadTarea($id)->delete();	
		}
		else throw new Exception('Error, no se puede guardar');
		

	}//FIN FUNCION 



//FIN CONTROLADOR
}