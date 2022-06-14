<?php

date_default_timezone_set('America/Santiago'); 

class AudienciasController extends Controller
{
	public $layout='//layouts/column3';
	public function actionCrearFicha()
	{

		if(!Yii::app()->user->isGuest){
			//$control_audiencia = new ControlAudiencia;

			//$datos = $control_audiencia->getDatos();

			//$this->render('CrearFicha', array('control_audiencia' => $control_audiencia, 'datos' => $datos));	

			
			 $this->redirect('http://172.17.123.19/sia_old/inicio/index.php?r=Audiencias/CrearFicha&rut='.Yii::app()->user->id.'&fis='.Yii::app()->user->getState('fiscalia'));


		}
		else $this->redirect(array('Site/Logout')); 
		
	}

	public function actionDetalles(){
		//require_once '../sia/inicio/protected/views/conect/conexion_saf.php';
		require_once (Yii::app()->basePath . '/views/conect/conexion_saf.php');
		if(!Yii::app()->user->isGuest){
			$ruc = $_POST['ruc'];
			$datos = oci_parse($conn, "SELECT   C.COD_FISCALIA as FISCALIA,
				C.IDF_ROLUNICO as RUC,
				ff.IDF_RUTFISCAL as RUT_FISCAL,
				ff.IDF_PATERNO as PATERNO,
				ff.IDF_MATERNO as MATERNO,
				ff.IDF_NOMBRES as NOMBRES
				from MINPROD.TATP_CASO C                     
				inner join  MINPROD.TASG_ASIGNACION asg  on asg.crr_caso = C.crr_idcaso
				inner join  MINPROD.TATP_FUNFISCALIA ff  on asg.IDF_RUTFISCAL = ff.IDF_RUTFISCAL
				where asg.EST_ASIGNACION = 1
				and idf_rolunico='".$ruc."'");
			oci_execute($datos);

			$i=0; $flag=false; 
			$data = array();
			if(oci_fetch($datos)){
				$rut=oci_result($datos, 'RUT_FISCAL');
				$fiscalia=oci_result($datos, 'FISCALIA');
				$ruc=oci_result($datos, 'RUC');
				$paterno=oci_result($datos, 'PATERNO');
				$materno=oci_result($datos, 'MATERNO');
				$nombres=oci_result($datos, 'NOMBRES');



			    						//exploto $rut fiscal
				$rut_limpio=explode("-", $rut);
				$cadena= (string)(int)$rut_limpio[0];
				$guion = "-";
				$digito = $rut_limpio[1];
				$rut_completo = $cadena.$guion.$digito;									
				$buscar_nombre = Funcionario::Model()->findBySql("SELECT fr.fun_rut, fr.fun_email, fr.fun_nombre, fr.fun_ap_paterno, fr.fun_ap_materno FROM funcionario fr LEFT JOIN fr.funcionario_fiscalia ff on fr.fun_rut = ff.fun_rut 
					WHERE fr.fun_rut = '$rut_completo' and ff.funfis_ind_vigencia= 1");
				$data['nombre_fiscal'] = $paterno." ".$materno." ".$nombres;
				$data['fun_rut'] = $rut_completo;  
				$data['fiscalia'] = $fiscalia;
				
				echo json_encode($data);
				
			}


		}  
		else $this->redirect(array('Site/Logout')); 
	}

	public function actionFiscales($id_fiscalia = false){
		if(!Yii::app()->user->isGuest){
			$consulta = Funcionario::model()->findAllBySql("SELECT f.fun_rut as rut, f.fun_nombre as nombre, f.fun_ap_paterno as ap, f.fun_ap_materno as am, f.crg_codigo as cargo FROM funcionario f left join funcionario_fiscalia ff on ff.fun_rut = f.fun_rut where ff.funfis_ind_vigencia = 1 and ff.fis_codigo = '".$id_fiscalia."' and f.crg_codigo IN (27,28)  order by f.fun_ap_paterno asc");
			echo '<option value="">Seleccionar Fiscal</option>';
			foreach ($consulta as $fiscales) {
				echo '<option value="'.$fiscales['rut'].'">'.$fiscales['ap']." ".$fiscales['am']." ".$fiscales['nombre'].'</option>';
			}
		}  
		else $this->redirect(array('Site/Logout')); 
	}

	public function actionAddaudiencia(){
		if(!Yii::app()->user->isGuest){

			$model = new ControlAudiencia;	
			$ruc = $_POST['rut'];
			$rut_fiscal = $_POST['rut_fiscal'];
			$flag=false; 
			$transaction=$model->dbConnection->beginTransaction();
			try{
				
				$model = Yii::app()->db->createCommand()->insert('diligencia.control_audiencia', array('idf_rolunico'=> $ruc, 'rut_fiscal'=> $rut_fiscal, 'rut_fiscal_asiste' => $_POST['fiscal_asiste'], 'fun_rut' => Yii::app()->user->id, 'fis_codigo' => Yii::app()->user->getState('fiscalia')));

				//consultar si existe el registro con la misma fecha

				$flag=true;   
				$transaction->commit();    
				} //END TRY
				catch(Exception $e){ //en este bloque se accede si se ha producido una exception en el bloque try
					Yii::app()->user->setFlash('danger', 'No se han guardado los registros.');
					$transaction->rollBack(); 
					
				} 
				if($flag==true){                                    
					Yii::app()->user->setFlash('success','El registro se ha creado exitosamente.');
					$this->redirect(array('Audiencias/CrearFicha'));
					
				}
				
			}  
			else $this->redirect(array('Site/Logout')); 
		}

		public function actionImprimirAudiencia($id){
	 	//me conecto a saf    	 
			require_once (Yii::app()->basePath . '/views/conect/conexion_saf.php');
			
			$extrae_datos = ControlAudiencia::model()->findBySql("SELECT idf_rolunico, rut_fiscal_asiste 
				from control_audiencia WHERE cod_audiencia='".$id."' ");

			Yii::app()->db_diligencia->createCommand(' UPDATE `control_audiencia` SET `ind_impresion`=2 WHERE cod_audiencia="'.$id.'" and ind_impresion=1')->query(); 

			$datos = oci_parse($conn, "SELECT DISTINCT CASO.IDF_ROLUNICO AS RUC,
				ff.IDF_RUTFISCAL as RUT_FISCAL,
				FISCALIA.GLS_DESCFISCALIA AS FISCALIA,
				ff.IDF_PATERNO AS F_AP_PATERNO, 
				ff.IDF_MATERNO AS F_AP_MATERNO,
				ff.IDF_NOMBRES AS F_NOMBRE,
				TG_MATERIA.GLS_MATERIA AS DELITO 		
				FROM minprod.tatp_caso caso
				INNER JOIN minprod.tatp_delito delito on delito.crr_caso = caso.crr_idcaso
				INNER JOIN minprod.tges_relacion rel on rel.crr_caso = caso.crr_idcaso and rel.crr_caso = delito.crr_caso and rel.crr_delito = delito.crr_iddelito
				LEFT JOIN  minprod.tatp_sujeto sujeto      on sujeto.crr_idsujeto = rel.crr_imputado          
				LEFT JOIN  minprod.tatp_persona per        on per.crr_idpersona = sujeto.crr_persona
				LEFT JOIN  minprod.tatp_personadoc pdoc    on pdoc.crr_persona = per.crr_idpersona
				left join  MINPROD.TASG_ASIGNACION asg  on asg.crr_caso = caso.crr_idcaso
				left join  MINPROD.TATP_FUNFISCALIA ff  on asg.IDF_RUTFISCAL = ff.IDF_RUTFISCAL
				INNER JOIN minprod.tatp_fiscalia fiscalia on fiscalia.cod_fiscalia=caso.cod_fiscalia and fiscalia.cod_region in (8)
				inner join MINPROD.TG_MATERIA tg_materia on tg_materia.cod_materia = delito.cod_delito
				WHERE caso.idf_rolunico in ('".$extrae_datos['idf_rolunico']."') and 
				asg.est_asignacion = '1'");
			oci_execute($datos);
			$i=0; $flag=false; 
			$model = array();
			if(oci_fetch($datos)){
				$model['ruc']=oci_result($datos, 'RUC');
				$model['fiscalia']=oci_result($datos, 'FISCALIA');
				$model['delito']=oci_result($datos, 'DELITO');
				$model['fiscal_causa'] = oci_result($datos, 'F_AP_PATERNO')." ".oci_result($datos, 'F_AP_MATERNO')." ".oci_result($datos, 'F_NOMBRE');
			//nombre del fiscal que asiste.
				$buscar_nombre = Funcionario::Model()->findBySql("SELECT fr.fun_rut, fr.fun_email, fr.fun_nombre, fr.fun_ap_paterno, fr.fun_ap_materno FROM funcionario fr LEFT JOIN fr.funcionario_fiscalia ff on fr.fun_rut = ff.fun_rut 
					WHERE fr.fun_rut = '".$extrae_datos['rut_fiscal_asiste']."' and ff.funfis_ind_vigencia= 1");

				$model['fiscal_asiste'] = (isset($buscar_nombre->fun_nombre)?$buscar_nombre->fun_nombre:"Sin Información")." ".(isset($buscar_nombre->fun_ap_paterno)?$buscar_nombre->fun_ap_paterno:"Sin Información")." ".(isset($buscar_nombre->fun_ap_materno)?$buscar_nombre->fun_ap_materno:"Sin Información");

			}

			$imputado = oci_parse($conn, "SELECT DISTINCT 
				TRIM(per.idf_nombres) AS IMP_NOMBRES,
				TRIM(per.idf_paterno) AS IMP_AP_PATERNO,
				TRIM(per.idf_materno) AS IMP_AP_MATERNO   
				FROM minprod.tatp_caso caso
				INNER JOIN minprod.tatp_delito delito on delito.crr_caso = caso.crr_idcaso
				INNER JOIN minprod.tges_relacion rel on rel.crr_caso = caso.crr_idcaso and rel.crr_caso = delito.crr_caso and rel.crr_delito = delito.crr_iddelito
				LEFT JOIN  minprod.tatp_sujeto sujeto      on sujeto.crr_idsujeto = rel.crr_imputado          
				LEFT JOIN  minprod.tatp_persona per        on per.crr_idpersona = sujeto.crr_persona
				LEFT JOIN  minprod.tatp_personadoc pdoc    on pdoc.crr_persona = per.crr_idpersona
				INNER JOIN minprod.tatp_fiscalia fiscalia on fiscalia.cod_fiscalia=caso.cod_fiscalia and fiscalia.cod_region in (8)
				inner join MINPROD.TG_MATERIA tg_materia on tg_materia.cod_materia = delito.cod_delito
				WHERE caso.idf_rolunico in ('".$extrae_datos['idf_rolunico']."')");
			oci_execute($imputado);
			$i=0; $flag=false;

			$nrows = oci_fetch_all($imputado, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

			//var_dump($res);

			/*$imputados = array();
			if(oci_fetch_all($imputado)){
				$imputados['nombre_imp'] = oci_result($imputado, 'IMP_NOMBRES')." ".oci_result($imputado, 'IMP_AP_PATERNO');
    			//$imputados['ap_paterno_imp'] = oci_result($imputado, 'IMP_AP_PATERNO');
    			//$imputados['ap_materno_imp'] = oci_result($imputado, 'IMP_AP_MATERNO');
    			//array_push($imputados, $imputado);
			}
			var_dump($imputados);

			/*$imputados = array();
			if(oci_fetch_array($imputado)){
				$imputados['nombre_imp'] = oci_result($imputado, 'IMP_NOMBRES');
				$imputados['ap_paterno_imp'] = oci_result($imputado, 'IMP_AP_PATERNO');
				$imputados['ap_materno_imp'] = oci_result($imputado, 'IMP_AP_MATERNO');
				
			}*/
			
			
			

		 $mPDF1 = Yii::app()->ePdf->mpdf('utf-8','LEGAL','','',15,5,5,5,9,9,'P'); //Esto lo pueden configurar como quieren, para eso deben de entrar en la web de MPDF para ver todo lo que permite.
		 //$mPDF1->SetPageSize("A4");
		 $mPDF1->useOnlyCoreFonts = true;
		 $mPDF1->SetTitle("Control de Audiencias");
		 $mPDF1->SetAuthor("UGI-FR");	
		 $mPDF1->showWatermarkText = true;
		 $mPDF1->watermark_font = 'DejaVuSansCondensed';
		 $mPDF1->watermarkTextAlpha = 0.1;
		 $mPDF1->SetDisplayMode('fullpage');
		 $mPDF1->SetFooter(' {DATE j/m/Y}|Página {PAGENO}|Nombre cualquiera');	
		 if($model->fis_codigo==801){
		 	$mPDF1->WriteHTML($this->renderPartial('pdfCaratula', array('model'=>$model, 'imputados' => $res), true));
		 }else{
		 	$mPDF1->WriteHTML($this->renderPartial('pdfCaratula', array('model'=>$model, 'imputados' => $res), true));
		 }
		 
		 $mPDF1->Output('sia_control_audiencias'.date('YmdHis'),'I'); 
		 exit;
	}//fin caratula


	public function actionPrintMasivo(){

		//me conecto a saf    	 
		require_once (Yii::app()->basePath . '/views/conect/conexion_saf.php');
		//Yii::app()->db_diligencia->createCommand(' UPDATE `control_audiencia` SET `ind_impresion`=2 WHERE idf_rolunico="'.$id.'" ')->query(); 

		$mPDF1 = Yii::app()->ePdf->mpdf('utf-8','LEGAL','','',15,5,5,5,9,9,'P'); //Esto lo pueden configurar como quieren, para eso deben de entrar en la web de MPDF para ver todo lo que permite.
		$mPDF1->useOnlyCoreFonts = true;
		$mPDF1->SetTitle("Control de Audiencias");
		$mPDF1->SetAuthor("UGI-FR");	
		$mPDF1->showWatermarkText = true;
		$mPDF1->watermark_font = 'DejaVuSansCondensed';
		$mPDF1->watermarkTextAlpha = 0.1;
		$mPDF1->SetDisplayMode('fullpage');
		$mPDF1->SetFooter(' {DATE j/m/Y}|Página {PAGENO}|Nombre cualquiera');
		

		$extrae_datos = ControlAudiencia::model()->findAllBySql("SELECT idf_rolunico, rut_fiscal_asiste 
			from control_audiencia WHERE ind_impresion = '1' and fis_codigo= '".Yii::app()->user->getState('fiscalia')."' and 
			fun_rut='".Yii::app()->user->id."'");

		foreach($extrae_datos as $listar ){
				

			$datos = oci_parse($conn, "SELECT DISTINCT CASO.IDF_ROLUNICO AS RUC,
				ff.IDF_RUTFISCAL as RUT_FISCAL,
				FISCALIA.GLS_DESCFISCALIA AS FISCALIA,
				ff.IDF_PATERNO AS F_AP_PATERNO, 
				ff.IDF_MATERNO AS F_AP_MATERNO,
				ff.IDF_NOMBRES AS F_NOMBRE,
				TG_MATERIA.GLS_MATERIA AS DELITO 		
				FROM minprod.tatp_caso caso
				INNER JOIN minprod.tatp_delito delito on delito.crr_caso = caso.crr_idcaso
				INNER JOIN minprod.tges_relacion rel on rel.crr_caso = caso.crr_idcaso and rel.crr_caso = delito.crr_caso and rel.crr_delito = delito.crr_iddelito
				LEFT JOIN  minprod.tatp_sujeto sujeto      on sujeto.crr_idsujeto = rel.crr_imputado          
				LEFT JOIN  minprod.tatp_persona per        on per.crr_idpersona = sujeto.crr_persona
				LEFT JOIN  minprod.tatp_personadoc pdoc    on pdoc.crr_persona = per.crr_idpersona
				left join  MINPROD.TASG_ASIGNACION asg  on asg.crr_caso = caso.crr_idcaso
				left join  MINPROD.TATP_FUNFISCALIA ff  on asg.IDF_RUTFISCAL = ff.IDF_RUTFISCAL
				INNER JOIN minprod.tatp_fiscalia fiscalia on fiscalia.cod_fiscalia=caso.cod_fiscalia and fiscalia.cod_region in (8)
				inner join MINPROD.TG_MATERIA tg_materia on tg_materia.cod_materia = delito.cod_delito
				WHERE caso.idf_rolunico in ('".$listar->idf_rolunico."') and 
				asg.est_asignacion = '1'");
			oci_execute($datos);
			$i=0; $flag=false; 
			$model = array();
			if(oci_fetch($datos)){
				$model['ruc']=oci_result($datos, 'RUC');
				$model['fiscalia']=oci_result($datos, 'FISCALIA');
				$model['delito']=oci_result($datos, 'DELITO');
				$model['fiscal_causa'] = oci_result($datos, 'F_AP_PATERNO')." ".oci_result($datos, 'F_AP_MATERNO')." ".oci_result($datos, 'F_NOMBRE');
			//nombre del fiscal que asiste.
				$buscar_nombre = Funcionario::Model()->findBySql("SELECT fr.fun_rut, fr.fun_email, fr.fun_nombre, fr.fun_ap_paterno, fr.fun_ap_materno FROM funcionario fr LEFT JOIN fr.funcionario_fiscalia ff on fr.fun_rut = ff.fun_rut 
					WHERE fr.fun_rut = '".$listar->rut_fiscal_asiste."' and ff.funfis_ind_vigencia= 1");

				$model['fiscal_asiste'] = (isset($buscar_nombre->fun_nombre)?$buscar_nombre->fun_nombre:"Sin Información")." ".(isset($buscar_nombre->fun_ap_paterno)?$buscar_nombre->fun_ap_paterno:"Sin Información")." ".(isset($buscar_nombre->fun_ap_materno)?$buscar_nombre->fun_ap_materno:"Sin Información");

			}

			$imputado = oci_parse($conn, "SELECT DISTINCT 
				TRIM(per.idf_nombres) AS IMP_NOMBRES,
				TRIM(per.idf_paterno) AS IMP_AP_PATERNO,
				TRIM(per.idf_materno) AS IMP_AP_MATERNO   
				FROM minprod.tatp_caso caso
				INNER JOIN minprod.tatp_delito delito on delito.crr_caso = caso.crr_idcaso
				INNER JOIN minprod.tges_relacion rel on rel.crr_caso = caso.crr_idcaso and rel.crr_caso = delito.crr_caso and rel.crr_delito = delito.crr_iddelito
				LEFT JOIN  minprod.tatp_sujeto sujeto      on sujeto.crr_idsujeto = rel.crr_imputado          
				LEFT JOIN  minprod.tatp_persona per        on per.crr_idpersona = sujeto.crr_persona
				LEFT JOIN  minprod.tatp_personadoc pdoc    on pdoc.crr_persona = per.crr_idpersona
				INNER JOIN minprod.tatp_fiscalia fiscalia on fiscalia.cod_fiscalia=caso.cod_fiscalia and fiscalia.cod_region in (8)
				inner join MINPROD.TG_MATERIA tg_materia on tg_materia.cod_materia = delito.cod_delito
				WHERE caso.idf_rolunico in ('".$listar->idf_rolunico."')");
			oci_execute($imputado);
			$i=0; $flag=false;

			/*$imputados = array();
			if(oci_fetch($imputado)){
				$imputados['nombre_imp'] = oci_result($imputado, 'IMP_NOMBRES');
				$imputados['ap_paterno_imp'] = oci_result($imputado, 'IMP_AP_PATERNO');
				$imputados['ap_materno_imp'] = oci_result($imputado, 'IMP_AP_MATERNO');
			}*/
			$nrows = oci_fetch_all($imputado, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
			
			$mPDF1->WriteHTML($this->renderPartial('pdfCaratula', array('model'=>$model, 'imputados' => $res), true));
			

			$mPDF1->AddPage();
			
			
		}

		
		//Yii::app()->db_diligencia->createCommand(' UPDATE `tcmc_caratula` SET `ind_impresion`=1 WHERE ind_impresion=0 and fun_rut="'.Yii::app()->user->getState('rut').'" ')->query(); 

		Yii::app()->db_diligencia->createCommand(' UPDATE `control_audiencia` SET `ind_impresion`=2 WHERE ind_impresion=1 and fun_rut="'.Yii::app()->user->id.'"')->query(); 
		$mPDF1->Output('sia_control_audiencias'.date('YmdHis'),'I');  
		exit;	

	}//fin caratula

	// Uncomment the following methods and override them if needed
	
	/*public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}*/
	
}