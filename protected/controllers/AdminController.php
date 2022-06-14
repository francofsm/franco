<?php

date_default_timezone_set('America/Santiago'); 

class AdminController extends Controller{

	public $layout='//layouts/column2';

	public function filters(){
		return array('accessControl', 'postOnly + delete',);
	}

	public function actionIndex(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$id=Yii::app()->user->getState('perfil');
	    	$perfil = TgPerfil::model()->findByPk($id);

	    	$uni=Yii::app()->user->getState('unidad');
	    	$tguni = Unidad::model()->findByPk($uni);

	    	$fiscalias=Yii::app()->user->getState('fiscalia');
	    	$fi = Fiscalia::model()->findByPk($fiscalias);

	    	//echo ($fi;

	    	$this->render('index', array('perfil'=>$perfil, 'tguni'=>$tguni, 'fi'=>$fi));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionCaratula(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	        $this->redirect('http://172.17.123.19/herramientas/start/index.php?r=Caratula/CrearCaratula&rut='.Yii::app()->user->id.'&fis='.Yii::app()->user->getState('fiscalia'));

	    }//FIN ELSE
	}//FIN FUNCION INDEX

	public function actionControlDetencion(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	        $this->redirect('http://172.17.123.19/centralizado/control_detencion/index.php?r=Admin/Index&rut='.Yii::app()->user->id.'&fis='.Yii::app()->user->getState('fiscalia'));

	    }//FIN ELSE
	}//FIN FUNCION INDEX

	


	public function actionCuct(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	        $this->redirect('http://172.17.123.17/sistemas/gestion_local/index.php?r=CuctConsultacarp/EnvioBodega&rut='.Yii::app()->user->id.'&fis='.Yii::app()->user->getState('fiscalia'));

	    }//FIN ELSE
	}//FIN FUNCION INDEX

	public function actionAdminEquipos(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$id=Yii::app()->user->getState('perfil');
	    	$perfil = TgPerfil::model()->findByPk($id);
	    	$this->render('adminEquipos', array('perfil'=>$perfil));
	    }//FIN ELSE
	}//FIN FUNCION INDEX



	public function actionListarFuncionarios(){
		
		$fun = Funcionario::model()->getFuncionariosEquipos();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th style='width:25px;'>N°</th>
            	<th style='width:95px;'>RUT</th>
				<th>NOMBRE</th>
				<th>FISCALIA</th>
				<th>UNIDAD</th>
				<th>CARGO</th>
				<th class='check_misdil' style='width:30px;text-align:center'>MODIFICAR</th>

            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($fun as $fun) {	
			
			echo "<tr>"; 
			echo "<td>".$n."</td>";
			echo "<td>".$fun['fun_rut']."</td>";
			echo "<td>".$fun['nombre']."</td>";
			echo "<td>".$fun['fiscalia']."</td>";
			echo "<td>".$fun['unidad']."</td>";
			echo "<td>".$fun['cargo']."</td>";
			$id=$fun['fun_rut'];
			if(Yii::app()->user->getState('perfil')==13){
				echo "<td style='text-align:center'><span id='".$id."' name='eliminar' class='btn btn-warning' onclick='modificarEquipo(this.id)'>Editar</span></td>";
			}
			else{
				echo "<td style='text-align:center'></td>";
			}
			
			
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 50,
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

	}//FIN FUNCTION	



	public function actionModificarEquipoFun(){
		$id=$_POST['id'];
		$model=Funcionario::model()->findByPk($id);


		$uni = Unidad::model()->getUnidades();

		echo "<div>"; 

		echo "<select name='fun' id='fun' >
				<option value='".$model->fun_rut."'>".$model->fun_nombre." ".$model->fun_nombre2." ".$model->fun_ap_paterno."</option>";
		echo "</select>";

		echo "<label>FUNCIONARIO:</label>
				<select name='unidad' id='unidad' class='chosen_uni' >
				<option value=''>Seleccionar Unidad</option>";

					foreach ($uni as $uni) {	   									    	
						echo '<option value='.$uni['uni_codigo'].'>'.$uni['uni_descripcion'].' ('.$uni['fis_codigo'].')</option>';
					}
		
		echo "</select>";
		echo '<button id="btn_recepcion" name="guardar" class="btn btn-info" onclick="guardarUnidad()">Guardar Unidad</button>';



		echo "</div>"; 

	}


	public function actionGuardarUnidadFun(){

		$fun=$_POST['fun'];
	    $unidad=$_POST['unidad'];

		$existe=FuncionarioFiscalia::model()->findAll('fun_rut="'.$fun.'" and funfis_ind_vigencia=1');
		$model=FuncionarioFiscalia::model()->findByPk($existe[0]->funfis_codigo);
		$model->uni_codigo=$unidad;
		$model->save();

	}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////           PERFILES              ///////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionPefiles(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$fun = Funcionario::model()->getAllFuncionarios();
	    	$perfil=TgPerfil::model()->findAll('');

	        $this->render('perfiles', array('fun'=>$fun, 'perfil'=>$perfil));  
	    }//FIN ELSE
	}//FIN FUNCION INDEX

	public function actionConsultaPerfiles(){

		$fun = PerfilFuncionario::model()->getFuncionarioPerfil();
		

		echo "<table id='tabla_perfiles' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
                <th>RUT</th>
                <th>NOMBRE</th>
                <th>PERFIL</th>
                <th>VIGENCIA</th>
                <th>FISCALIA</th>
                <th>ELIMINAR</th>
            </tr>
        </thead>";

		echo "<tbody>";		

		$n=1;
		foreach ($fun as $listar) {	

			echo "<tr class='carpetas'>";
			echo "<td>".$listar['fun_rut']."</td>";
			echo "<td>".$listar['nombre']."</td>"; 
			echo "<td>".$listar['perfil']."</td>"; 
			if($listar['ind_vigencia']==1) echo "<td>Vigente</td>"; 
			else echo "<td>No vigente</td>"; 
			echo "<td>".$listar['fiscalia']."</td>"; 
			echo "<td>".CHtml::link('<img src="'.Yii::app()->baseUrl.'/images/iconos/eliminar.png" alt="Revisar Causa" />', Yii::app()->createUrl("Admin/EliminarPerfil", array("id"=>$listar['cod_perfun'])))."</td>";
			echo "</tr>";
			$n++;
		}
		
		echo "</tbody>"; 
		echo "</table>";

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#tabla_perfiles').DataTable( {
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
				                columns: [ 0, 1, 2, 3, 4 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN CARPETA consulta

	public function actionEliminarPerfil($id){
		$this->loadPerfil($id)->delete();			

		Yii::app()->user->setFlash('Eliminado',"Pefil Eliminado"); 
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('Pefiles'));
	}//fin function


	public function actionGuardarPerfiles(){

		$model=new PerfilFuncionario;
		$flag=false;
	    $transaction=$model->dbConnection->beginTransaction();   
	    try{

	    	if(empty($_POST['funcionario'])) { throw new Exception('Debe seleccionar un funcionario o fiscal.'); }
	        if(empty($_POST['perfil'])) { throw new Exception('Debe seleccionar mínimo un perfil'); }

	        $fun=$_POST['funcionario'];
	        $per=$_POST['perfil'];

	        $existe=PerfilFuncionario::model()->findAll('fun_rut="'.$fun.'" and ind_vigencia=1');
	        if($existe){
	        	$actualizar=PerfilFuncionario::model()->findByPk($existe[0]->cod_perfun);
	        	$actualizar->ind_vigencia=0;
	        	if($actualizar->save()){
					$model->cod_perfil=$per;
					$model->fun_rut=$fun;
					$model->ind_vigencia=1; 
					$model->fec_registro=date('Y-m-d H-i-s');			
					if($model->save()){
						$flag=true;	
					} 
					else throw new Exception('Error, al grabar perfil');	
	        	}//FIN GUARDAR ACTUALIZAR
	        	else throw new Exception('Error, no se pudo actualizar vigencia del perfil');	
	        }//FIN SI EXISTE
	        else{
				$model->cod_perfil=$per;
				$model->fun_rut=$fun;
				$model->ind_vigencia=1; 
				$model->fec_registro=date('Y-m-d H-i-s');			
				if($model->save()){
					$flag=true;	
				} 
				else throw new Exception('Error, al grabar perfil');	        	
	        }//FIN ELSE NO EXISTE		
	
	    	$transaction->commit();
	    }//FIN TRY

	    catch(Exception $e){ //en este bloque se accede si se ha producido una exception en el bloque try
		 	echo '<span class="label label-warning">'.$e->getMessage();
		    $transaction->rollBack();  
		 } 
		 if($flag==true){
		 	
			echo '<span class="label label-success">Se registra perfil correctamente.<\span>';
		 }//
	}//FIN FUNCTION



	public function loadPerfil($id){
		$model=PerfilFuncionario::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}







///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////  AUSENCIAS   //////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionAusencia(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	    	$fun = Funcionario::model()->getGestor();
	    	$tip = TgTipausencia::model()->findAll('');

	        $this->render('ausencia', array('fun'=>$fun, 'tip'=>$tip));  
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionConsultaAusencias(){

		$aus = Ausencia::model()->getFuncionarioAusencias();
		

		echo "<table id='ingresos' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	
                <th>RUT</th>
                <th>NOMBRE</th>
                <th>AUSENCIA</th>
                <th>FECHA AUSENCIA</th>
                <th>OBSERVACIONES</th>
                <th>ELIMINAR</th>
            </tr>
        </thead>";

		echo "<tbody>";		

		$n=1;
		foreach ($aus as $listar) {	

			echo "<tr class='carpetas'>"; 
			//echo "<td>".$listar['fisca']."</td>"; 
			echo "<td>".$listar['fun_rut']."</td>"; 
			echo "<td>".$listar['funcionario']."</td>";
			echo "<td>".$listar['glsausencia']."</td>";
			echo "<td>".$listar['fec_ausencia']."</td>";
			echo "<td>".$listar['Observaciones']."</td>";
			echo "<td>".CHtml::link('<img src="'.Yii::app()->baseUrl.'/images/iconos/eliminar.png" alt="Revisar Causa" />', Yii::app()->createUrl("Admin/EliminarAusencia", array("id"=>$listar['cod_ausencia'])))."</td>";
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
					} 
				} );

				$('#ingresos tbody').on( 'click', 'tr', function () {
					if ( $(this).hasClass('selected') ) {
						$(this).removeClass('selected');
					}
					else {
						table.$('tr.selected').removeClass('selected');
						$(this).addClass('selected');
					}
				} );
				$('#button').click( function () {
					table.row('.selected').remove().draw( false );
				} );		
			});    

		</script>";

	}//FIN CARPETA consulta

	public function actionGuardarAusencia(){

		$model=new Ausencia;
		$flag=false;
	    $transaction=$model->dbConnection->beginTransaction();   
	    try{

	    	if(empty($_POST['funcionario'])) { throw new Exception('Debe seleccionar un funcionario o fiscal.'); }
	        if(empty($_POST['ausencia'])) { throw new Exception('Debe seleccionar un tipo de ausencia'); }
	        if(empty($_POST['fec_inicio'])) { throw new Exception('Debe seleccionar minímo para un día'); }

	        $model->cod_tipausencia=$_POST['ausencia'];
	        $model->fun_rut=$_POST['funcionario'];
	        $model->fec_registro=date('Y-m-d H-i-s');	
	        $model->Observaciones=$_POST['observaciones'];

	        if($_POST['fec_inicio']<>"" &&  $_POST['fec_hasta']<>"" ){
	        	$fecha=$_POST['fec_hasta'];
	        	$fec_act=$_POST['fec_inicio'];
				$dias = (strtotime($fecha) - strtotime($fec_act)) / 60 / 60 / 24;
				$dias=intval($dias)+1;
				$i=0;
				while($i<$dias){

					$aus=new Ausencia;
					$aus->cod_tipausencia=$model->cod_tipausencia;
					$aus->fun_rut=$model->fun_rut;
					$aus->fec_registro=$model->fec_registro;
					$aus->fec_ausencia=date('Y-m-d', strtotime('+'.$i.' day', strtotime($fec_act))); 
					$aus->Observaciones=$model->Observaciones;


					if($aus->save()){
						$aus=new Ausencia;
						$flag=true;
					} 
					else throw new Exception('ERROR, no se pudo registrar ausencia(s).');	
					$i++;
				}//fin while
	        }//fin if post fechas <> ""
	        else{
	        	$model->fec_ausencia=$_POST['fec_inicio']; 

				//CONSULTA DISPONIBILIDAD DE AUSENCIA
				$act = BancoInstruccion::model()->getMinutosDis($model->fun_rut,$model->fec_ausencia);	
				$ausen = Ausencia::model()->getAusencias($model->fun_rut,$model->fec_ausencia);	
				$tipoaus = TgTipausencia::model()->getMinutosAusencia($model->cod_tipausencia);	


	        	if($model->save()){	        		
	        		$flag=true;
	        	} 
				else throw new Exception('ERROR, no se pudo registrar ausencia(s).');	
	        }//fin else
	
	    	$transaction->commit();
	    }//FIN TRY

	    catch(Exception $e){ //en este bloque se accede si se ha producido una exception en el bloque try
		 	echo $e->getMessage();
		    $transaction->rollBack();  
		 } 
		 if($flag==true){		 	
			echo 'Se registra ausencia correctamente.';
		 }//
	}//FIN FUNCTION

	public function actionEliminarAusencia($id){
		$this->loadAusencia($id)->delete();			

		Yii::app()->user->setFlash('Eliminado',"Ausencia Eliminada"); 
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('Ausencia'));
	}//fin function





//////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////     Cambiar de Fiscalia     ////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionModificarFiscalia(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{


	    	$this->render('modificarFiscalia');
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionModificarFiscaliaFun(){
		$id=$_POST['id'];
		$model=Funcionario::model()->findByPk($id);


		$fis = Fiscalia::model()->getFiscalias();

		echo "<div>"; 

		echo "<select name='fun' id='fun' >
				<option value='".$model->fun_rut."'>".$model->fun_nombre." ".$model->fun_nombre2." ".$model->fun_ap_paterno."</option>";
		echo "</select>";

		echo "<label>FUNCIONARIO:</label>
				<select name='fisca' id='fisca' class='chosen_uni' >
				<option value=''>Seleccionar Fiscalía</option>";

					foreach ($fis as $fis) {	   									    	
						echo '<option value='.$fis['fis_codigo'].'>'.$fis['fis_nombre'].' ('.$fis['fis_codigo'].')</option>';
					}
		
		echo "</select>";
		echo '<button id="btn_recepcion" name="guardar" class="btn btn-info" onclick="guardarFiscalia()">Guardar Fiscalía</button>';



		echo "</div>"; 

	}


	public function actionModificarFiscaliaAsociada(){
		
		$model = FuncionarioFiscalia::model()->getFiscaliasAso();	

		echo "<div>"; 

		echo "<label>FISCALIA:</label>
				<select name='fisca' id='fisca'>";

				foreach ($model as $model) {	   									    	
					echo '<option value='.$model['fis_codigo'].'>'.$model['fiscalia'].' ('.$model['fis_codigo'].') / Equipo: '.$model['unidad'].'</option>';
				}
		
		echo "</select>";
		echo '<button id="btn_recepcion" name="guardar" class="btn btn-info" onclick="guardarFiscalia()">Guardar Fiscalía</button>';



		echo "</div>"; 

	}


	public function actionListarFuncionariosFun(){
		
		$fun = Funcionario::model()->getFuncionariosEquipos();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th style='width:25px;'>N°</th>
            	<th style='width:95px;'>RUT</th>
				<th>NOMBRE</th>
				<th>FISCALIA</th>
				<th>CARGO</th>
				<th class='check_misdil' style='width:30px;text-align:center'>MODIFICAR</th>

            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($fun as $fun) {	
			
			echo "<tr>"; 
			echo "<td>".$n."</td>";
			echo "<td>".$fun['fun_rut']."</td>";
			echo "<td>".$fun['nombre']."</td>";
			echo "<td>".$fun['fiscalia']."</td>";
			echo "<td>".$fun['cargo']."</td>";
			$id=$fun['fun_rut'];
			if(Yii::app()->user->getState('perfil')==13){
				echo "<td style='text-align:center'><span id='".$id."' name='eliminar' class='btn btn-warning' onclick='modificarEquipo(this.id)'>Editar</span></td>";
			}
			else{
				echo "<td style='text-align:center'></td>";
			}
			
			
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 50,
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

	}//FIN FUNCTION	


	public function actionGuardarFiscaliaFun(){

		$fun=$_POST['fun'];
	    $fiscalia=$_POST['fis'];

		$existe=FuncionarioFiscalia::model()->findAll('fun_rut="'.$fun.'" and funfis_ind_vigencia=1');
		$model=FuncionarioFiscalia::model()->findByPk($existe[0]->funfis_codigo);
		$model->fis_codigo=$fiscalia;
		$model->save();

	}

	public function actionGuardarFiscaliaFunAsociado(){

	    $fiscalia=$_POST['fis'];

	    Yii::app()->user->setState('fiscalia', $fiscalia);

	    $getuni=FuncionarioFiscalia::model()->findAll('fun_rut="'.Yii::app()->user->id.'" and fis_codigo="'.$fiscalia.'" and funfis_ind_vigencia=1');

		Yii::app()->user->setState('unidad', $getuni[0]->uni_codigo);

	}


//////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////     mantenedor de diligencias   ////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionDiligencia(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{


	    	$this->render('diligencia');
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionModificarDiligencia(){
		$id=$_POST['id'];
		$model=TgInstruccion::model()->findByPk($id);


		$fami = TgFamiliainstruccion::model()->findAll();

		echo "<div>"; 

		echo "<select name='dil' id='dil' >
				<label>DILIGENCIA:</label>
				<option value='".$model->cod_instruccion."'>".$model->gls_instruccion."</option>";
		echo "</select>";


		echo "<label>MINUTOS:</label>";
		echo '<input type="text" id="tiempo" name="tiempo" value="'.$model->tiempo_instruccion.'" />';


		echo "<label>FAMILIA:</label>
				<select name='fami' id='fami' >";
					foreach ($fami as $fami) {	   			
						if( $fami['cod_faminstruccion'] == $model->cod_faminstruccion ){
							echo '<option value='.$fami['cod_faminstruccion'].' selected>'.$fami['gls_faminstruccion'].' </option>';
						}					    	
						else{
							echo '<option value='.$fami['cod_faminstruccion'].'>'.$fami['gls_faminstruccion'].' </option>';
						}
					}
		
		echo "</select>";

		echo "<label>ESTADO:</label>
				<select name='est' id='est' >";

				if( $model->ind_vigencia == 1 ){
					echo '<option value="1" selected >Vigente</option>'; 
					echo '<option value="0" >No Vigente</option>'; 
				}
				else{
					echo '<option value="1" >Vigente</option>'; 
					echo '<option value="0" selected>No Vigente</option>'; 
				}
		
		
		echo "</select>";
		echo '<button id="btn_recepcion" name="guardar" class="btn btn-info" onclick="guardarDiligencia()">Guardar</button>';



		echo "</div>"; 

	}


	public function actionGuardarModificarDiligencia(){

		$id=$_POST['dil'];

	    $model=TgInstruccion::model()->findByPk($id);
	    $model->tiempo_instruccion = $_POST['tiempo'];
	    $model->cod_faminstruccion = $_POST['fami'];
	    $model->ind_vigencia = $_POST['est'];

		$model->save();

	}


	public function actionListarDiligencias(){
		
		$dil = TgInstruccion::model()->getDiligenciasTodas();

		echo "<table id='listarea' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th style='width:95px;'>CODIGO</th>
				<th>NOMBRE</th>
				<th>TIEMPO</th>
				<th>FAMILIA</th>
				<th>ESTADO</th>
				<th class='check_misdil' style='width:30px;text-align:center'>MODIFICAR</th>

            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1; $i=1;
		foreach ($dil as $dil) {	
			
			echo "<tr>"; 
			echo "<td>".$dil['cod_instruccion']."</td>";
			echo "<td>".$dil['gls_instruccion']."</td>";
			echo "<td>".$dil['tiempo_instruccion']."</td>";
			echo "<td>".$dil['familia']."</td>";

			if( $dil['ind_vigencia'] == 1 ){
				echo "<td>Vigente</td>";
			}
			else{
				echo "<td>No Vigente</td>";	
			}

			
			$id=$dil['cod_instruccion'];
			if(Yii::app()->user->getState('perfil')==13){
				echo "<td style='text-align:center'><span id='".$id."' name='eliminar' class='btn btn-warning' onclick='modificarDil(this.id)'>Editar</span></td>";
			}
			else{
				echo "<td style='text-align:center'></td>";
			}
			
			
			$n++; $i++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listarea').DataTable( {
					'pageLength': 50,
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
								columns: [ 0, 1, 2, 3, 4]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	

//FIN CONTROLADOR
}