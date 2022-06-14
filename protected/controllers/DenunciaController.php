<?php

date_default_timezone_set('America/Santiago'); 

class DenunciaController extends Controller{

	public $layout='//layouts/column2';

	public function filters(){
		return array('accessControl', 'postOnly + delete',);
	}

	public function actionRegistrarDenuncia(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$tipdenun=TgTipdenuncia::model()->findAll('');
	    	$origen=TgOrigencaso::model()->findAll('');	
	    	$comi = Comisaria::model()->getComisaria();
	    	$desta = TgDestacamento::model()->getDestacamento();

	    	$this->render('registrarDenuncia', array('tipdenun'=>$tipdenun,'origen'=>$origen,'comi'=>$comi,'desta'=>$desta));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////          LISTAR PARTES RECEPCIONADOS        ////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionListarPartes(){
		
		$ta = Denuncia::model()->getParteRecepcionado();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>#</th>				
				<th style='width:70px;'>DENUNCIA</th>
				<th style='width:70px;'>N°</th>
				<th>ORIGEN</th>
				<th>DESTACAM./COMISARIA</th>
				<th>PROCEDENCIA</th>
				<th>FEC INGRESO</th>
				<th>OBSERVACIONES</th>
				<th>ESTADO</th>
				<th>PENDIENTE</th>
				<th>CONTROL</th>
				<th>X</th>
				<th>RESPONSABLE</th>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=0;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_ingreso'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['tipo']."</td>"; 
			echo "<td>".$ta['num_denuncia']."</td>"; 
			echo "<td>".$ta['origen']."</td>"; 
			echo "<td>".$ta['destaca']."<br>".$ta['comisaria']."</td>";    
			echo "<td>".$ta['gls_procedencia']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>"; 
			echo "<td>".$ta['obs_denuncia']."</td>"; 

			if($ta['ind_pendiente'] == 1) echo "<td>PENDIENTE</td>"; 
			elseif($ta['ind_control'] == 1) echo "<td>CONTROL</td>";  
			else echo "<td></td>";  

			echo "<td style='text-align: center;'>";
			echo '<input type="checkbox" class="checPendiente" name="pendiente[<?php echo $i; ?>]" id="pendiente[<?php echo $i; ?>]" value="'.$ta["cod_denuncia"].'">';
			echo "</td>"; 

			echo "<td style='text-align: center;'>";
			echo '<input type="checkbox" class="checControl" name="control[<?php echo $i; ?>]" id="control[<?php echo $i; ?>]" value="'.$ta["cod_denuncia"].'">';
			echo "</td>"; 

			if($ta['fun_rut'] == Yii::app()->user->id){
				echo "<td style='text-align: center;'>";
				echo '<input type="checkbox" class="checElim" name="eliminar[<?php echo $i; ?>]" id="eliminar[<?php echo $i; ?>]" value="'.$ta["cod_denuncia"].'">';
				echo "</td>";
			}
			else{
				echo "<td style='text-align: center;'>";
				echo "</td>";
			}

			echo "<td>".$ta['responsable']."</td>"; 			

			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 9999,
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
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 8, 12 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 12 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	




	public function actionGuardarParte(){

		$den=new Denuncia;		
		$flag=false;
	    $transaction=$den->dbConnection->beginTransaction();   
	    try{
	    	if( empty($_POST['tipdenun']) ) throw new Exception('Error, debe seleccionar tipo de denuncia.');
	    	if( empty($_POST['num']) ) throw new Exception('Error, debe indicar número o rol del parte.'); 
	    	if( empty($_POST['fec_ingreso']) ) throw new Exception('Error, debe seleccionar fecha de ingreso.'); 
	    	if( empty($_POST['origen']) ) throw new Exception('Error, debe seleccionar origen del caso.'); 

	    	if( $_POST['num']<> "" && $_POST['num_hasta']<>"" ){

	    		$i=$_POST['num'];

	    		$dif = $_POST['num_hasta'] - $_POST['num']; 
	    		if($dif > 100){
	    			throw new Exception('Error, estas seleccionando un rango sobre 100. Por seguridad puedes registrar de hasta 100 partes.'); 
	    		}

	            while( $i <= $_POST['num_hasta'] ){

	            	$den->fis_codigo=Yii::app()->user->getState('fiscalia');	
		            $den->cod_tipdenuncia=$_POST['tipdenun'];
		            $den->cod_tipdenuncia=$_POST['tipdenun'];
		            $den->num_denuncia=$i;
		            $den->fec_ingreso=$_POST['fec_ingreso'];			
					$den->cod_origencaso=$_POST['origen'];	
					$den->cod_destaca=$_POST['desta'];	
					$den->id_comisaria=$_POST['comi'];	
					$den->gls_procedencia=strtoupper($_POST['proced']);	
					$den->funcionario_entrega=strtoupper($_POST['funentrega']);	
					$den->obs_denuncia=strtoupper($_POST['obs']);	
					$den->obs_denuncia=strtoupper($_POST['obs']);	
					$den->cod_estcarpeta=1;	
					$den->fec_cambioest=date('Y-m-d h:m'); 	
					$den->ind_recepcion=1; 	
					$den->fun_rut=Yii::app()->user->id;	
					$den->fec_registro=date('Y-m-d h:m'); 	
					if($den->save()){
						$flag=true;	
						$i++;	
						$den=new Denuncia;		
					}	
					else throw new Exception('Error, no se pudo guardar el parte'); 	
				}		

	    	}//fin num hasta <> ""
	    	else{
					$den->fis_codigo=Yii::app()->user->getState('fiscalia');	
		            $den->cod_tipdenuncia=$_POST['tipdenun'];
		            $den->cod_tipdenuncia=$_POST['tipdenun'];
		            $den->num_denuncia=$_POST['num'];
		            $den->fec_ingreso=$_POST['fec_ingreso'];			
					$den->cod_origencaso=$_POST['origen'];	
					$den->cod_destaca=$_POST['desta'];	
					$den->id_comisaria=$_POST['comi'];	
					$den->gls_procedencia=strtoupper($_POST['proced']);	
					$den->funcionario_entrega=strtoupper($_POST['funentrega']);	
					$den->obs_denuncia=strtoupper($_POST['obs']);	
					$den->obs_denuncia=strtoupper($_POST['obs']);	
					$den->cod_estcarpeta=1;	
					$den->fec_cambioest=date('Y-m-d h:m'); 	
					$den->ind_recepcion=1; 	
					$den->fun_rut=Yii::app()->user->id;	
					$den->fec_registro=date('Y-m-d h:m'); 	
					if($den->save()){
						$flag=true;							
					}//fin if save
	    	}//fin else	
			
			
			
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
				'message'=> 'Recepción de Parte(s) ha sido almacenado exitosamente'
			));
		}//

	}//FIN FUNCTION


	///
	///


	public function actionPartePendiente($pendiente){	
		if(!Yii::app()->user->isGuest){	
			
			$array = explode(",", $pendiente); 
			$flag=false;
			foreach ($array as $c => $value) {
				$model=$this->loadModel($array[$c]);
				if($model->ind_pendiente==1){
					$model->ind_pendiente=0;
					$model->fec_ingreso=date('Y-m-d h:m');
				} 
				else $model->ind_pendiente=1;
				if($model->save()) $flag=true;				
			}//FIN FOREACH

		    if($flag==true){                                 
	          Yii::app()->user->setFlash('Guardado',"Parte(s) registrados pendientes, correctamente modificado(s)."); 			
	        }else{
	        	Yii::app()->user->setFlash('error',"Error al intentar modificar estado del parte."); 			
	        }//FIN ELSE		
		}
		else $this->redirect(array('Site/Logout')); 
	}//FIN FUNCION PARTES PENDIENTES


	public function actionParteControl($control){	
		if(!Yii::app()->user->isGuest){	
			
			$array = explode(",", $control); 
			$flag=false;
			foreach ($array as $c => $value) {
				$model=$this->loadModel($array[$c]);
				if($model->ind_control==1) $model->ind_control=0;
				else $model->ind_control=1;

				if($model->save()) $flag=true;				
			}//FIN FOREACH

		    if($flag==true){                                 
	          Yii::app()->user->setFlash('Guardado',"Parte(s) registrados con Control, correctamente modificado(s)."); 			
	        }else{
	        	Yii::app()->user->setFlash('error',"Error al intentar modificar estado del parte."); 			
	        }//FIN ELSE		
		}
		else $this->redirect(array('Site/Logout')); 
	}//FIN FUNCION PARTES EN CONTROL


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////     ELIMINAR PARTES        ////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionEliminarPartes($elim){	
		if(!Yii::app()->user->isGuest){		
			
			$array = explode(",", $elim); 
			$flag=false;

			foreach ($array as $c => $value) {

				//echo var_dump($array[$c]);

				$model=Denuncia::model()->findByPk($array[$c]);	
							
				$parte=new AnularDenuncia;
				$parte->cod_denuncia=$model->cod_denuncia;
				$parte->num_denuncia=$model->num_denuncia;
				$parte->fec_ingreso=$model->fec_ingreso;
				$parte->cod_origencaso=$model->cod_origencaso;
				$parte->gls_procedencia=$model->gls_procedencia;
				$parte->fun_rut=Yii::app()->user->id;
				$parte->fec_registro=date('Y-m-d h:m');

				if($parte->save()){
					$this->loadModel($model->cod_denuncia)->delete(); 
					$flag=true;
				}					
				
			}

		    if($flag==true){                                 
	            Yii::app()->user->setFlash('Guardado',"Parte(s) eliminado correctamente."); 				
	        }else{
	        	Yii::app()->user->setFlash('error',"Debe seleccionar minímo 1 parte para eliminar."); 			
	        }//FIN ELSE		
		}
		else $this->redirect(array('Site/Logout')); 
	}//FIN FUNCION ELIMINAR PARTES

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////      DERIVAR PARTE          ////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	public function actionDerivarDenuncia(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{	    	
	    	$ges = Funcionario::model()->getGestor();

	        $this->render('derivarDenuncia', array('ges'=>$ges));  
	    }//FIN ELSE
	}//FIN FUNCION INDEX



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////          LISTAR PARTES PARA DERIVAR          ////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionListarPartesDerivar(){
		
		$ta = Denuncia::model()->getParteRecepcionado();

		$act=TgInstruccion::model()->findAll('cod_instruccion in (2,3,4,5,6,7,8,9,136) order by gls_instruccion ASC');

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>#</th>				
				<th style='width:70px;'>DENUNCIA</th>
				<th style='width:70px;'>N°</th>
				<th>ORIGEN</th>
				<th>PROCEDENCIA</th>
				<th>FEC INGRESO</th>
				<th>OBSERVACIONES</th>
				<th>ESTADO</th>
				<th>ACTIVIDAD</th>
				<th>DERIVAR</th>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=0;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_ingreso'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['tipo']."</td>"; 
			echo "<td>".$ta['num_denuncia']."</td>"; 
			echo "<td>".$ta['origen']."</td>"; 
			echo "<td>".$ta['gls_procedencia']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>"; 
			echo "<td>".$ta['obs_denuncia']."</td>"; 

			if($ta['ind_pendiente'] == 1) echo "<td>PENDIENTE</td>"; 
			elseif($ta['ind_control'] == 1) echo "<td>CONTROL</td>";  
			else echo "<td></td>";  

			echo "<td>";
			echo '<select name="acti[<?php echo $i; ?>]" class="checActividad" id="acti[<?php echo $i; ?>]">';
				foreach ($act as $c => $value) {	       
					echo '<option value="'. $act[$c]->cod_instruccion .'">'. $act[$c]->gls_instruccion.' - Min: '.$act[$c]->tiempo_instruccion .'</option>';		
				}
			echo "</select>";		
			echo "</td>"; 

			echo "<td style='text-align: center;'>";
			echo '<input type="checkbox" class="checDerivar" name="derivar[<?php echo $i; ?>]" id="derivar[<?php echo $i; ?>]"  value="'.$ta["cod_denuncia"].'">';	
			echo "</td>"; 


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


	public function actionDerivarParteSelec($derivar, $gestor, $activ, $fec){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	

	    	$estado=new EstadoDenuncia;
	    	$flag=false;
	    	$suma=0;
	        $transaction=$estado->dbConnection->beginTransaction();   
	        try{
	        	if( empty($derivar) ) throw new Exception('Error, debe seleccionar minímo 1 parte para derivar.');
	        	if( empty($gestor) ) throw new Exception('Error, debe seleccionar un gestor.');	   
	        	if( empty($fec) ) throw new Exception('Error, debe seleccionar fecha de diligencia.');   

	        	$array = explode(",", $derivar); 
	        	$actividades = explode(",", $activ); 
	        	foreach ($array as $c => $value) {

	        		$actualizar=Denuncia::model()->findByPk($array[$c]);
					$actualizar->cod_estcarpeta=2;
					$actualizar->fec_cambioest=date('Y-m-d h:m');
					$actualizar->fun_asignado=$gestor;	
					$actualizar->fec_asignacion=date('Y-m-d h:m');
					$actualizar->cod_instruccion=$actividades[$c];	
					if($actualizar->save()){
						$estado->cod_denuncia=$array[$c];	  
						$estado->cod_estcarpeta=2;	
						$estado->fec_registro=date('Y-m-d h:m');
						$estado->fun_rut=$gestor;	  
						$estado->fun_responsable=Yii::app()->user->id; 

						if($estado->save()){
							
							$tarea=new BancoTarea;
							$tarea->idf_rolunico=$array[$c];
							$tarea->fis_codigo=Yii::app()->user->getState('fiscalia');
							$tarea->cod_instruccion=$actividades[$c];	
							$tarea->gls_comentario='Ingreso de parte';	
							$tarea->fec_tarea=date('Y-m-d');
							$tarea->fec_asignacion=date('Y-m-d h:m');
							$tarea->cod_estinstruccion=3;
							$tarea->fec_cambioest=date('Y-m-d h:m');
							$tarea->fun_asignado=$gestor;	 
							$tarea->fun_rut=Yii::app()->user->id; 
							$tarea->fec_registro=date('Y-m-d h:m');
							$tarea->cod_prioridad=1;
							$tarea->uni_codigo=Yii::app()->user->getState('unidad');	
										
							if($tarea->save()){
								$flag=true;	
							
								$estado=new EstadoDenuncia;
							}
							else throw new Exception('Error, problema al generar la tarea.');	


							$flag=true;		
						}//FIN ESTADO PARTE SAVE
						else throw new Exception('Error, no se puede grabar derivación.');	
					}//fin actualizar
					else throw new Exception('Error, no se puede actualizar derivación.');		        		     

				}//FIN FOREACH

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
				    'message'=> 'Parte(s) derivados con éxito.'
				));
			}//

	    }//FIN ELSE
	}//FIN FUNCION INDEX




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////      DECLARAR INGRESO      ////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	public function actionDeclararIngreso(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{	
	    	

	        $this->render('declararIngreso');  
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionListarPartesIngreso(){
		
		$ta = Denuncia::model()->getParteRecepcionado();

		$act=TgInstruccion::model()->findAll('cod_instruccion in (2,3,4,5,6,7,8,9,136) order by gls_instruccion ASC');

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>#</th>				
				<th style='width:70px;'>DENUNCIA</th>
				<th style='width:70px;'>N°</th>
				<th>ORIGEN</th>
				<th>PROCEDENCIA</th>
				<th>FEC INGRESO</th>
				<th>OBSERVACIONES</th>
				<th>ESTADO</th>
				<th>ACTIVIDAD</th>
				<th>DECLARAR</th>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=0;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_ingreso'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['tipo']."</td>"; 
			echo "<td>".$ta['num_denuncia']."</td>"; 
			echo "<td>".$ta['origen']."</td>"; 
			echo "<td>".$ta['gls_procedencia']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>"; 
			echo "<td>".$ta['obs_denuncia']."</td>"; 

			if($ta['ind_pendiente'] == 1) echo "<td>PENDIENTE</td>"; 
			elseif($ta['ind_control'] == 1) echo "<td>CONTROL</td>";  
			else echo "<td></td>";  

			echo "<td>";
			echo '<select name="acti[<?php echo $i; ?>]" class="checActividad" id="acti[<?php echo $i; ?>]">';
				foreach ($act as $c => $value) {	       
					echo '<option value="'. $act[$c]->cod_instruccion .'">'. $act[$c]->gls_instruccion.' - Min: '.$act[$c]->tiempo_instruccion .'</option>';		
				}
			echo "</select>";		
			echo "</td>"; 

			echo "<td style='text-align: center;'>";
			echo '<input type="checkbox" class="checDerivar" name="derivar[<?php echo $i; ?>]" id="derivar[<?php echo $i; ?>]"  value="'.$ta["cod_denuncia"].'">';	
			echo "</td>"; 


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




	public function actionDeclararParteSelec($derivar, $activ){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	       	$estado=new EstadoDenuncia;
	    	$flag=false;
	    	$suma=0;
	        $transaction=$estado->dbConnection->beginTransaction();   
	        try{
	        	if( empty($derivar) ) throw new Exception('Error, debe seleccionar minímo 1 parte para declarar su ingreso.');
	        	

	        	$array = explode(",", $derivar); 
	        	$actividades = explode(",", $activ); 
	        	foreach ($array as $c => $value) {

	        		$actualizar=Denuncia::model()->findByPk($array[$c]);
					$actualizar->cod_estcarpeta=5;
					$actualizar->fec_cambioest=date('Y-m-d h:m');
					$actualizar->fun_asignado=Yii::app()->user->id; 
					$actualizar->fec_asignacion=date('Y-m-d h:m');
					$actualizar->cod_instruccion=$actividades[$c];	
					if($actualizar->save()){
						$estado->cod_denuncia=$array[$c];	  
						$estado->cod_estcarpeta=5;	
						$estado->fec_registro=date('Y-m-d h:m');
						$estado->fun_rut=Yii::app()->user->id; 
						$estado->fun_responsable=Yii::app()->user->id; 

						if($estado->save()){
							
							$tarea=new BancoTarea;
							$tarea->idf_rolunico=$array[$c];
							$tarea->fis_codigo=Yii::app()->user->getState('fiscalia');
							$tarea->cod_instruccion=$actividades[$c];	
							$tarea->gls_comentario='Ingreso de parte';	
							$tarea->fec_tarea=date('Y-m-d');
							$tarea->fec_asignacion=date('Y-m-d h:m');
							$tarea->cod_estinstruccion=5;
							$tarea->fec_cambioest=date('Y-m-d h:m');
							$tarea->fun_asignado=Yii::app()->user->id; 
							$tarea->fun_rut=Yii::app()->user->id; 
							$tarea->fec_registro=date('Y-m-d h:m');
							$tarea->cod_prioridad=1;
							$tarea->uni_codigo=Yii::app()->user->getState('unidad');	
										
							if($tarea->save()){
								$flag=true;	
								$estado=new EstadoDenuncia;
							}
							else throw new Exception('Error, problema al generar la tarea.');	
								
						}//FIN ESTADO PARTE SAVE
						else throw new Exception('Error, no se puede grabar la declaración de ingreso.');	
					}//fin actualizar
					else throw new Exception('Error, no se puede actualizar la declaración de ingreso.');	
	        		     

				}//FIN FOREACH

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
				    'message'=> 'Parte(s) ingresados con éxito.'
				));
			}//

	    }//FIN ELSE
	}//FIN FUNCION INDEX



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////      DECLARAR INGRESO      ////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	public function actionRecepcionarParte(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{	
	    	

	        $this->render('recepcionarParte');  
	    }//FIN ELSE
	}//FIN FUNCION INDEX




	public function actionListarRecepcionParte(){
		
		$ta = Denuncia::model()->getRecepcionarParte();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>#</th>				
				<th style='width:70px;'>DENUNCIA</th>
				<th style='width:70px;'>N°</th>
				<th>ORIGEN</th>
				<th>PROCEDENCIA</th>
				<th>FEC INGRESO</th>
				<th>OBSERVACIONES</th>
			
				<th>ACTIVIDAD</th>
				<th>RECEPCIONAR</th>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=0;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_ingreso'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['tipo']."</td>"; 
			echo "<td>".$ta['num_denuncia']."</td>"; 
			echo "<td>".$ta['origen']."</td>"; 
			echo "<td>".$ta['gls_procedencia']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>"; 
			echo "<td>".$ta['obs_denuncia']."</td>";  

			echo "<td>".$ta['instruccion']."</td>";

			echo "<td style='text-align: center;'>";

			
			echo '<input type="checkbox" class="checDerivar" name="derivar[<?php echo $i; ?>]" id="derivar[<?php echo $i; ?>]"  value="'.$ta["cod_denuncia"].'">';	

			
			echo "</td>"; 


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



	public function actionGuardarRecepcionarParte($derivar){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	       	$estado=new EstadoDenuncia;
	    	$flag=false;
	    	$suma=0;
	        $transaction=$estado->dbConnection->beginTransaction();   
	        try{
	        	if( empty($derivar) ) throw new Exception('Error, debe seleccionar minímo 1 parte para recepcionar.');
	        	
	        	$array = explode(",", $derivar); 
	        	foreach ($array as $c => $value) {
	        		$cod = $array[$c];
	        		$actualizar=Denuncia::model()->findByPk($array[$c]);
					$actualizar->cod_estcarpeta=5;
					$actualizar->fec_cambioest=date('Y-m-d h:m');
		
					if($actualizar->save()){
						$estado->cod_denuncia=$array[$c];	  
						$estado->cod_estcarpeta=5;	
						$estado->fec_registro=date('Y-m-d h:m');
						$estado->fun_rut=Yii::app()->user->id; 
						$estado->fun_responsable=Yii::app()->user->id; 

						if($estado->save()){
							
							
							$sql = BancoTarea::model()->getDenunciaTarea($cod);
							foreach ($sql as $sql) {	
								$id = $sql['cod_bntarea'];
							}							
					
							$act=BancoTarea::model()->findByPk($id);
							$act->cod_estinstruccion=5;
							$act->fec_cambioest=date('Y-m-d h:m');
							
							if($act->save()){
								$flag=true;	
								$estado=new EstadoDenuncia;
							}
							else throw new Exception('Error, problema al generar la tarea.');	
								
						}//FIN ESTADO PARTE SAVE
						else throw new Exception('Error, no se puede grabar la recepcion del parte.');	
					}//fin actualizar
					else throw new Exception('Error, no se puede actualizar la recepción del parte.');	
	        		     

				}//FIN FOREACH

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
				    'message'=> 'Parte(s) recepcionados con éxito.'
				));
			}//

	    }//FIN ELSE
	}//FIN FUNCION INDEX	


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////     CONSULTAR DERIVADOS           ////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	public function actionConsultarDerivado(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{	
	    	

	        $this->render('consultaDerivados');  
	    }//FIN ELSE
	}//FIN FUNCION INDEX



	public function actionListarParteDerivado($fec, $fec_fin){
		
		$ta = Denuncia::model()->getParteDerivado($fec, $fec_fin);

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            				
				<th style='width:70px;'>DENUNCIA</th>
				<th style='width:70px;'>N°</th>
				<th>ORIGEN</th>
				<th>PROCEDENCIA</th>
				<th>FEC INGRESO</th>
				<th>OBSERVACIONES</th>
			
				
				<th>ASIGNADO A:</th>
				<th>FECHA ASIGNACIÓN</th>
				<th>ANULAR</th>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=0;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_ingreso'];
			$fec_asig=$ta['fec_asignacion'];
			echo "<tr>"; 
	
			echo "<td>".$ta['tipo']."</td>"; 
			echo "<td>".$ta['num_denuncia']."</td>"; 
			echo "<td>".$ta['origen']."</td>"; 
			echo "<td>".$ta['gls_procedencia']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>"; 
			echo "<td>".$ta['obs_denuncia']."</td>";  

			echo "<td>".$ta['funcionario']."</td>";
			echo "<td>".date("d-m-Y", strtotime($fec_asig))."</td>"; 

			echo "<td style='text-align: center;'>";

			if($ta['fun_rut'] == Yii::app()->user->id || Yii::app()->user->id == '12701148-6' ||  Yii::app()->user->id == '17342911-8' ||  Yii::app()->user->id == '15222023-5' ){
			echo '<input type="checkbox" class="checDerivar" name="derivar[<?php echo $i; ?>]" id="'.$ta["cod_denuncia"].'"  value="'.$ta["cod_denuncia"].'" onclick="anularParte(this.id)"">';	
			}

			echo "</td>"; 


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
							extend: 'print',
							text: 'Imprimir',
				            exportOptions: {
				                stripHtml: false,
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	





//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////     CONSULTAR DERIVADOS           ////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	public function actionConsultarRecepcionados(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{	  
	        $this->render('consultaRecepcionados');  
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionListarParteRecepcionados($fec, $fec_fin){
		
		$ta = Denuncia::model()->getListParteRecepcionado($fec, $fec_fin);

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            				
				<th style='width:70px;'>DENUNCIA</th>
				<th style='width:70px;'>N°</th>
				<th>ORIGEN</th>
				<th>PROCEDENCIA</th>
				<th>FEC INGRESO</th>
				<th>OBSERVACIONES</th>
				<th>ASIGNADO A:</th>
				<th>FECHA ASIGNACIÓN</th>
				<th>DERIVADO POR:</th>
				<th>ESTADO</th>
				<th>FECHA ESTADO</th>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=0;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_ingreso'];
			$fec_asig=$ta['fec_asignacion'];
			$fec_cambioest=$ta['fec_cambioest'];
			echo "<tr>"; 
	
			echo "<td>".$ta['tipo']."</td>"; 
			echo "<td>".$ta['num_denuncia']."</td>"; 
			echo "<td>".$ta['origen']."</td>"; 
			echo "<td>".$ta['gls_procedencia']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>"; 
			echo "<td>".$ta['obs_denuncia']."</td>";  

			echo "<td>".$ta['funcionario']."</td>";

			if($fec_asig<>"") echo "<td>".date("d-m-Y", strtotime($fec_asig))."</td>"; 
			else echo "<td></td>"; 
			echo "<td>".$ta['responsable']."</td>";
			

			echo "<td>".$ta['estado']."</td>";

 			if($fec_cambioest<>"") echo "<td>".date("d-m-Y", strtotime($fec_cambioest))."</td>"; 
			else echo "<td></td>"; 

			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 20,
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
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 8, 9 ]
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


	public function actionAnularDerivacion($id){
		
		$model=Denuncia::model()->findByPk($id);
		$model->fun_asignado="";
		$model->fec_asignacion=null;
		$model->cod_estcarpeta=4;
		if($model->save()){

			$cod=$model->cod_denuncia;

			$estado=new EstadoDenuncia;
			$estado->cod_denuncia=$cod;	  
			$estado->cod_estcarpeta=4;	
			$estado->fec_registro=date('Y-m-d h:m');
			$estado->fun_rut=Yii::app()->user->id; 
			$estado->fun_responsable=Yii::app()->user->id; 

			if($estado->save()){
			
				$sql = BancoTarea::model()->getDenunciaTarea($cod);
				foreach ($sql as $sql) {	
					$codigo = $sql['cod_bntarea'];
				}	

				if(isset($codigo)){
					$this->loadTarea($codigo)->delete();
				}

						
				
			}

				
		}
		

	}//FIN FUNCTION	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////      LOAD MODEL            ////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////



	public function loadModel($id){
		$model=Denuncia::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function loadTarea($id){
		$model=BancoTarea::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}



//FIN CONTROLADOR
}