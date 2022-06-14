<?php

class BancoTarea extends CActiveRecord
{

	public $tarea; 
	public $funcionario; 
	public $min_ejecutado; 
	public $estado; 
	public $tiempo;
	public $priori;
	public $responsable; 
	public $total;
	public $fiscal;
	public $mes_decreta; 
	public $asignador; 

	public $getMesesBolsa; 

	public $getListadoInstruidas; 
	public $getListadoInstruidasAsig; 
	public $getListMisDiligencias; 
	public $getListMisBloques; 

	public $getCargaLaboralFun; 
	public $getListDiligencias;
	public $getListMisDiligenciasEjecu; 
	public $getCargaLaboralFunFecha;

	public $getDilPendientes; 
	public $getDiligenciaCaso; 
	public $getListadoAdmin; 

	public $getListDiligenciasAsignadas; 
	public $getListDiligenciasDecretadas;
	public $getListDiligenciasAsignadasTodas;  
	public $getListDiligenciasAnuladas; 
	public $getListDiligenciasTodasSinEjecutarUGI;


	///Reportes
	public $getTotalMinutosAsignados; 
	public $getTotalMinutosAsignadosPlazo; 


	public $getTotalMinutosEjecutados; 
	public $getTotalMinutosEjecutadosPlazo; 

	public $getTotalTareasAsignadas; 
	public $getLisTareaReporteComparativo; 
	public $getTotalMinutosReporteIndividual; 
	public $getTotalMinutosAsignadosReporteIndividual; 

	public $getTotalDiligenciasReporteIndividual; 


	public $getTotalTurnoPresencial; 

	public $getFiscalAsignado; 

	public $getDenunciaTarea; 

	public $getDiligenciasCuentaFiscal; 
	public $getDiligenciasCuentaFiscalAsignado; 
	public $getDiligenciasCuentaFiscalEjecutado; 
	public $getDiligenciasCuentaFiscalPendiente; 


	public $getMesesAsignadas; 
	public $getMesesTodas; 


	public $getTareasAdminFun; 

	/*DETALLE REPORTES*/
	//REPORTE PLAZO
	public $getReporteMinAsignados;


	public function tableName()
	{
		return 'banco_tarea';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fis_codigo, cod_instruccion, cod_estinstruccion, fun_rut, cod_prioridad, fec_registro', 'required'),
			array('cod_instruccion, cod_estinstruccion, ind_dias, cod_prioridad, cod_plantrabajo', 'numerical', 'integerOnly'=>true),
			array('idf_rolunico', 'length', 'max'=>15),
			array('fis_codigo', 'length', 'max'=>3),
			array('fun_asignado, fun_rut, cuenta_rut', 'length', 'max'=>12),
			array('fec_tarea, fec_asignacion, fec_cambioest', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('gls_observacion, cuenta_rut, uni_codigo, cod_bntarea, idf_rolunico, fis_codigo, cod_instruccion, gls_comentario, fec_tarea, fec_asignacion, cod_estinstruccion, fec_cambioest, fun_asignado, fun_rut, ind_dias, cod_prioridad, cod_plantrabajo, fec_registro', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cod_bntarea' => 'Cod Bntarea',
			'idf_rolunico' => 'Idf Rolunico',
			'fis_codigo' => 'Fis Codigo',
			'cod_instruccion' => 'Cod Instruccion',
			'gls_comentario' => 'Gls Comentario',
			'fec_tarea' => 'Fec Tarea',
			'fec_asignacion' => 'Fec Asignacion',
			'cod_estinstruccion' => 'Cod Estinstruccion',
			'fec_cambioest' => 'Fec Cambioest',
			'fun_asignado' => 'Fun Asignado',
			'fun_rut' => 'Fun Rut',
			'ind_dias' => 'Ind Dias',
			'cod_prioridad' => 'Cod Prioridad',
			'cod_plantrabajo' => 'Cod Plantrabajo',
			'fec_registro' => 'Fec Registro',
		);
	}



////////////////////////////////////         ELIMINAR            /////////////////////////////////////////////////////////
 
	public function getTareasAdminFun($rut, $fec_ini, $fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, tg.tiempo_instruccion tiempo, concat(fun.fun_nombre, " ", fun.fun_ap_paterno) responsable, concat(fundos.fun_nombre, " ", fundos.fun_ap_paterno) funcionario');

		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  INNER JOIN fr.funcionario fun on fun.fun_rut=t.fun_rut
						  INNER JOIN fr.funcionario fundos on fundos.fun_rut=t.fun_asignado';

		$criteria->addCondition("t.fun_asignado = '".$rut."' ");
		$criteria->addCondition("date(t.fec_tarea) >= '".$fec_ini."'");	
		$criteria->addCondition("date(t.fec_tarea) <= '".$fec_fin."'");	

		$criteria->addCondition("tg.cod_faminstruccion = 2");	

		$this->getTareasAdminFun=BancoTarea::model()->findAll($criteria);	
		return $this->getTareasAdminFun; 
	}


////////////////////////////////////    detalle reporte plazo         /////////////////////////////////////////////////////////
	public function getReporteMinAsignados($rut, $fec_ini, $fec_fin){

		$criteria = new CDbCriteria();

		$criteria->select = array('t.*, tg.gls_instruccion tarea, concat(f.fun_ap_paterno, " ",f.fun_nombre," ",f.fun_nombre2) funcionario, concat(f2.fun_ap_paterno, " ",f2.fun_nombre," ",f2.fun_nombre2) responsable, tp.gls_prioridad priori, te.gls_estinstruccion estado, tg.tiempo_instruccion tiempo');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  INNER JOIN fr.funcionario f on f.fun_rut=t.fun_asignado
						  INNER JOIN fr.funcionario f2 on f2.fun_rut=t.fun_rut
						  INNER JOIN tg_prioridad tp on tp.cod_prioridad=t.cod_prioridad
						  INNER JOIN tg_estinstruccion te on te.cod_estinstruccion=t.cod_estinstruccion';


		$criteria->addCondition("t.fun_asignado = '".$rut."' ");
		$criteria->addCondition("t.fec_tarea >= '".$fec_ini."'");	
		$criteria->addCondition("t.fec_tarea <= '".$fec_fin."'");	

		$criteria->addCondition("t.cod_estinstruccion in (1,2,3,4,5)");
		

		$this->getReporteMinAsignados=BancoTarea::model()->findAll($criteria);	
		return $this->getReporteMinAsignados; 
	}



////////////////////////////////////         REPORTES            /////////////////////////////////////////////////////////
	public function getDiligenciasCuentaFiscalPendiente($rut, $fec_ini, $fec_fin, $fis){

		$criteria = new CDbCriteria();
		$criteria->select = array('count(t.cod_bntarea) total');
		$criteria->addCondition("t.fun_rut = '".$rut."' ");
		$criteria->addCondition("t.fis_codigo = '".$fis."' ");
		$criteria->addCondition("t.cod_estinstruccion = 1 ");	
		
		$criteria->addCondition("date(t.fec_registro) >= '".$fec_ini."'");	
		$criteria->addCondition("date(t.fec_registro) <= '".$fec_fin."'");	

		

		$this->getDiligenciasCuentaFiscalPendiente=BancoTarea::model()->findAll($criteria);	
		return $this->getDiligenciasCuentaFiscalPendiente; 
	}


	public function getDiligenciasCuentaFiscalEjecutado($rut, $fec_ini, $fec_fin, $fis){

		$criteria = new CDbCriteria();
		$criteria->select = array('count(t.cod_bntarea) total');
		$criteria->addCondition("t.fun_rut = '".$rut."' ");
		$criteria->addCondition("t.fis_codigo = '".$fis."' ");
		$criteria->addCondition("t.cod_estinstruccion = 5 ");	
		
		$criteria->addCondition("date(t.fec_registro) >= '".$fec_ini."'");	
		$criteria->addCondition("date(t.fec_registro) <= '".$fec_fin."'");	

		

		$this->getDiligenciasCuentaFiscalEjecutado=BancoTarea::model()->findAll($criteria);	
		return $this->getDiligenciasCuentaFiscalEjecutado; 
	}


	public function getDiligenciasCuentaFiscalAsignado($rut, $fec_ini, $fec_fin, $fis){

		$criteria = new CDbCriteria();
		$criteria->select = array('count(t.cod_bntarea) total');
		$criteria->addCondition("t.fun_rut = '".$rut."' ");
		$criteria->addCondition("t.fis_codigo = '".$fis."' ");
		$criteria->addCondition("t.cod_estinstruccion = 3 ");	
		
		$criteria->addCondition("date(t.fec_registro) >= '".$fec_ini."'");	
		$criteria->addCondition("date(t.fec_registro) <= '".$fec_fin."'");	

		

		$this->getDiligenciasCuentaFiscalAsignado=BancoTarea::model()->findAll($criteria);	
		return $this->getDiligenciasCuentaFiscalAsignado; 
	}
// 
	public function getDiligenciasCuentaFiscal($rut, $fec_ini, $fec_fin, $fis){

		$criteria = new CDbCriteria();
		$criteria->select = array('count(t.cod_bntarea) total');
		$criteria->addCondition("t.fun_rut = '".$rut."' ");
		$criteria->addCondition("t.fis_codigo = '".$fis."' ");
		$criteria->addCondition("t.cod_estinstruccion in (1,2,3,5) ");	
		
		$criteria->addCondition("date(t.fec_registro) >= '".$fec_ini."'");	
		$criteria->addCondition("date(t.fec_registro) <= '".$fec_fin."'");	

		

		$this->getDiligenciasCuentaFiscal=BancoTarea::model()->findAll($criteria);	
		return $this->getDiligenciasCuentaFiscal; 
	}

	public function getDenunciaTarea($cod){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		$criteria->addCondition("t.idf_rolunico = '".$cod."' ");
		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		$criteria->addCondition("t.fun_asignado = '".Yii::app()->user->id."' ");
		

		$this->getDenunciaTarea=BancoTarea::model()->findAll($criteria);	
		return $this->getDenunciaTarea; 
	}


// 12/08  kro se cambia fec_asignacion por fec_plazo
	public function getTotalMinutosAsignados($rut, $fec_ini, $fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('sum(tg.tiempo_instruccion) total');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion';
		$criteria->addCondition("t.fun_asignado = '".$rut."' ");
		$criteria->addCondition("t.fec_tarea >= '".$fec_ini."'");	
		$criteria->addCondition("t.fec_tarea <= '".$fec_fin."'");	

		$criteria->addCondition("t.cod_estinstruccion in (1,2,3,4,5)");

		//$criteria->addCondition("t.cod_instruccion <> 153");	----TURNO PRESENCIAL NO SE EXCLUYE 12/08/2020


		$this->getTotalMinutosAsignados=BancoTarea::model()->findAll($criteria);	
		return $this->getTotalMinutosAsignados; 
	}


	public function getTotalMinutosAsignadosPlazo($rut, $fec_ini, $fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('sum(tg.tiempo_instruccion) total');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion';
		$criteria->addCondition("t.fun_asignado = '".$rut."' ");
		$criteria->addCondition("t.fec_tarea >= '".$fec_ini."'");	
		$criteria->addCondition("t.fec_tarea <= '".$fec_fin."'");	

		$criteria->addCondition("t.cod_estinstruccion in (1,2,3,4,5)");
		

		//$criteria->addCondition("t.cod_instruccion <> 153");	----TURNO PRESENCIAL NO SE EXCLUYE 10/08/2020


		$this->getTotalMinutosAsignadosPlazo=BancoTarea::model()->findAll($criteria);	
		return $this->getTotalMinutosAsignadosPlazo; 
	}



	public function getTotalMinutosEjecutados($rut, $fec_ini, $fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('sum(tg.tiempo_instruccion) total');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion';
		$criteria->addCondition("t.fun_asignado = '".$rut."' ");
		$criteria->addCondition("date(t.fec_asignacion) >= '".$fec_ini."'");	
		$criteria->addCondition("date(t.fec_asignacion) <= '".$fec_fin."'");	

		$criteria->addCondition("cod_estinstruccion = 5 ");
		$criteria->addCondition("t.cod_instruccion <> 153");	

		$this->getTotalMinutosEjecutados=BancoTarea::model()->findAll($criteria);	
		return $this->getTotalMinutosEjecutados; 
	}

	public function getTotalMinutosEjecutadosPlazo($rut, $fec_ini, $fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('sum(tg.tiempo_instruccion) total');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion';

		$criteria->addCondition("t.fun_asignado = '".$rut."' ");
		$criteria->addCondition("t.fec_tarea >= '".$fec_ini."'");	
		$criteria->addCondition("t.fec_tarea <= '".$fec_fin."'");	

		$criteria->addCondition("cod_estinstruccion = 5 ");
		//$criteria->addCondition("t.cod_instruccion <> 153");		----TURNO PRESENCIAL NO SE ECXLUYE 10/08/2020 

		$this->getTotalMinutosEjecutadosPlazo=BancoTarea::model()->findAll($criteria);	
		return $this->getTotalMinutosEjecutadosPlazo; 
	}


// 12/08  kro se cambia fec_asignacion por fec_plazo
	public function getTotalTareasAsignadas($rut, $fec_ini, $fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('count(cod_bntarea) total');
		$criteria->addCondition("t.fun_asignado = '".$rut."' ");
		$criteria->addCondition("t.fec_tarea >= '".$fec_ini."'");	
		$criteria->addCondition("t.fec_tarea <= '".$fec_fin."'");

		$criteria->addCondition("t.cod_estinstruccion in (1,2,3,4,5)");


		$criteria->addCondition("cod_instruccion <> 153");	//-TURNO PRESENCIAL SE MANTIENE EXCLUIDO 12/08



		$this->getTotalTareasAsignadas=BancoTarea::model()->findAll($criteria);	
		return $this->getTotalTareasAsignadas; 
	}



	public function getTotalTurnoPresencial($rut, $fec_ini, $fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('count(cod_bntarea) total');
		$criteria->addCondition("t.fun_asignado = '".$rut."' ");
		$criteria->addCondition("date(t.fec_asignacion) >= '".$fec_ini."'");	
		$criteria->addCondition("date(t.fec_asignacion) <= '".$fec_fin."'");
		$criteria->addCondition("cod_instruccion = 153");	



		$this->getTotalTurnoPresencial=BancoTarea::model()->findAll($criteria);	
		return $this->getTotalTurnoPresencial; 
	}







	public function getLisTareaReporteComparativo($rut, $codta, $fec_ini, $fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('count(cod_bntarea) total');
		$criteria->addCondition("t.cod_instruccion = '".$codta."' ");
		$criteria->addCondition("t.fun_asignado = '".$rut."' ");
		$criteria->addCondition("date(t.fec_tarea) >= '".$fec_ini."'");	
		$criteria->addCondition("date(t.fec_tarea) <= '".$fec_fin."'");	


		$criteria->addCondition("t.cod_estinstruccion in (1,2,3,4,5)");


		$this->getLisTareaReporteComparativo=BancoTarea::model()->findAll($criteria);	
		return $this->getLisTareaReporteComparativo; 
	}


	public function getTotalMinutosReporteIndividual($rut, $fecha){

		$criteria = new CDbCriteria();
		$criteria->select = array('sum(tg.tiempo_instruccion) total');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion';
		$criteria->addCondition("t.fun_asignado = '".$rut."' ");
		$criteria->addCondition("t.cod_estinstruccion = 5 ");	
		$criteria->addCondition("date(t.fec_cambioest) = '".$fecha."'");	


		$this->getTotalMinutosReporteIndividual=BancoTarea::model()->findAll($criteria);	
		return $this->getTotalMinutosReporteIndividual; 
	}


	public function getTotalMinutosAsignadosReporteIndividual($rut, $fecha){

		$criteria = new CDbCriteria();
		$criteria->select = array('sum(tg.tiempo_instruccion) total');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion';
		$criteria->addCondition("t.fun_asignado = '".$rut."' ");
		$criteria->addCondition("t.cod_estinstruccion in (1,2,3,4,5) ");	
		$criteria->addCondition("t.fec_tarea = '".$fecha."'");	


		$this->getTotalMinutosAsignadosReporteIndividual=BancoTarea::model()->findAll($criteria);	
		return $this->getTotalMinutosAsignadosReporteIndividual; 
	}



	public function getTotalDiligenciasReporteIndividual($rut, $fecha, $codta){

		$criteria = new CDbCriteria();
		$criteria->select = array('count(t.cod_bntarea) total');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion';
		$criteria->addCondition("t.fun_asignado = '".$rut."' ");
		$criteria->addCondition("t.cod_instruccion = '".$codta."'");	
		$criteria->addCondition("t.cod_estinstruccion = 5 ");	
		$criteria->addCondition("date(t.fec_cambioest) = '".$fecha."'");	


		$this->getTotalDiligenciasReporteIndividual=BancoTarea::model()->findAll($criteria);	
		return $this->getTotalDiligenciasReporteIndividual; 
	}


////////////////////////////////////         REPORTES            /////////////////////////////////////////////////////////






	public function getDilPendientes($ruc){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		$criteria->addCondition("t.idf_rolunico = '".$ruc."' ");	
		$criteria->addCondition("t.cod_estinstruccion in (1,2,3,4,6)");	
		
		$criteria->order = 't.cod_bntarea DESC';
	
		$this->getDilPendientes=BancoTarea::model()->findAll($criteria);	
		return $this->getDilPendientes; 
	}





	public function getListadoAdmin(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, concat(f.fun_ap_paterno, " ",f.fun_nombre," ",f.fun_nombre2) funcionario, concat(f2.fun_ap_paterno, " ",f2.fun_nombre," ",f2.fun_nombre2) responsable, tp.gls_prioridad priori, te.gls_estinstruccion estado, tg.tiempo_instruccion tiempo');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  INNER JOIN fr.funcionario f on f.fun_rut=t.fun_asignado
						  INNER JOIN fr.funcionario f2 on f2.fun_rut=t.fun_rut
						  INNER JOIN tg_prioridad tp on tp.cod_prioridad=t.cod_prioridad
						  INNER JOIN tg_estinstruccion te on te.cod_estinstruccion=t.cod_estinstruccion';

		$criteria->addCondition("t.cod_prioridad = 4 ");	
		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");

		$criteria->addCondition("t.cod_estinstruccion not in (5,6)");

		$criteria->addCondition("date(t.fec_registro)='".date('Y-m-d')."'");
		
		$criteria->order = 't.cod_bntarea DESC';
	
		$this->getListadoAdmin=BancoTarea::model()->findAll($criteria);	
		return $this->getListadoAdmin; 
	}






	public function getListDiligenciasAsignadas($mes){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, concat(f.fun_ap_paterno, " ",f.fun_nombre," ",f.fun_nombre2) funcionario, concat(f2.fun_ap_paterno, " ",f2.fun_nombre," ",f2.fun_nombre2) responsable, tp.gls_prioridad priori, te.gls_estinstruccion estado, tg.tiempo_instruccion tiempo, concat(cu.cuenta_nombre, " ", cu.cuenta_apellido) fiscal');
		$criteria->join ='LEFT JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.fun_asignado
						  LEFT JOIN fr.funcionario f2 on f2.fun_rut=t.fun_rut
						  LEFT JOIN fr.cuenta_generica cu on cu.cuenta_rut=t.cuenta_rut
						  LEFT JOIN tg_prioridad tp on tp.cod_prioridad=t.cod_prioridad
						  LEFT JOIN tg_estinstruccion te on te.cod_estinstruccion=t.cod_estinstruccion';

		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		$criteria->addCondition("t.cod_estinstruccion = 3 ");	

		$criteria->addCondition("MONTH(t.fec_registro) = '".$mes."' ");	

		$criteria->order = 't.cod_prioridad DESC, t.cod_bntarea DESC';
	
		$this->getListDiligenciasAsignadas=BancoTarea::model()->findAll($criteria);	
		return $this->getListDiligenciasAsignadas; 
	}


	public function getListDiligenciasDecretadas($fec, $fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, concat(f.fun_ap_paterno, " ",f.fun_nombre," ",f.fun_nombre2) funcionario, concat(f2.fun_ap_paterno, " ",f2.fun_nombre," ",f2.fun_nombre2) responsable, tp.gls_prioridad priori, te.gls_estinstruccion estado, tg.tiempo_instruccion tiempo, concat(cu.cuenta_nombre, " ", cu.cuenta_apellido) fiscal');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.fun_asignado
						  LEFT JOIN fr.funcionario f2 on f2.fun_rut=t.fun_rut
						  LEFT JOIN fr.cuenta_generica cu on cu.cuenta_rut=t.cuenta_rut
						  INNER JOIN tg_prioridad tp on tp.cod_prioridad=t.cod_prioridad
						  INNER JOIN tg_estinstruccion te on te.cod_estinstruccion=t.cod_estinstruccion';

		$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");	

		$criteria->addCondition("date(t.fec_registro) >= '".$fec."'");	
		$criteria->addCondition("date(t.fec_registro) <= '".$fec_fin."'");	


		$criteria->order = 't.cod_prioridad DESC, t.cod_bntarea DESC';
	
		$this->getListDiligenciasDecretadas=BancoTarea::model()->findAll($criteria);	
		return $this->getListDiligenciasDecretadas; 
	}



	public function getListDiligenciasAnuladas(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, concat(f.fun_ap_paterno, " ",f.fun_nombre," ",f.fun_nombre2) funcionario, concat(f2.fun_ap_paterno, " ",f2.fun_nombre," ",f2.fun_nombre2) responsable, tp.gls_prioridad priori, te.gls_estinstruccion estado, tg.tiempo_instruccion tiempo, concat(cu.cuenta_nombre, " ", cu.cuenta_apellido) fiscal');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.fun_asignado
						  LEFT JOIN fr.funcionario f2 on f2.fun_rut=t.fun_rut
						  LEFT JOIN fr.cuenta_generica cu on cu.cuenta_rut=t.cuenta_rut
						  INNER JOIN tg_prioridad tp on tp.cod_prioridad=t.cod_prioridad
						  INNER JOIN tg_estinstruccion te on te.cod_estinstruccion=t.cod_estinstruccion';

		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");	

		$criteria->addCondition("t.cod_estinstruccion = 6 ");	

		$criteria->order = 't.cod_prioridad DESC, t.cod_bntarea DESC';
	
		$this->getListDiligenciasAnuladas=BancoTarea::model()->findAll($criteria);	
		return $this->getListDiligenciasAnuladas; 
	}



	public function getListDiligenciasAsignadasTodas($mes){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, tg.tiempo_instruccion tiempo, concat(f.fun_ap_paterno, " ",f.fun_nombre," ",f.fun_nombre2) funcionario, concat(f2.fun_ap_paterno, " ",f2.fun_nombre," ",f2.fun_nombre2) responsable, tp.gls_prioridad priori, te.gls_estinstruccion estado, tg.tiempo_instruccion tiempo, concat(cu.cuenta_nombre, " ", cu.cuenta_apellido) fiscal');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.fun_asignado
						  LEFT JOIN fr.funcionario f2 on f2.fun_rut=t.fun_rut
						  LEFT JOIN fr.cuenta_generica cu on cu.cuenta_rut=t.cuenta_rut
						  INNER JOIN tg_prioridad tp on tp.cod_prioridad=t.cod_prioridad
						  INNER JOIN tg_estinstruccion te on te.cod_estinstruccion=t.cod_estinstruccion';

		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		$criteria->addCondition("t.cod_estinstruccion in (1,2,3,5) ");	
		$criteria->addCondition("MONTH(t.fec_registro) = '".$mes."' ");	

		$criteria->order = 't.cod_prioridad DESC, t.cod_bntarea DESC';
	
		$this->getListDiligenciasAsignadasTodas=BancoTarea::model()->findAll($criteria);	
		return $this->getListDiligenciasAsignadasTodas; 
	}


	public function getListDiligenciasTodasSinEjecutarUGI(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, tg.tiempo_instruccion tiempo, concat(f.fun_ap_paterno, " ",f.fun_nombre," ",f.fun_nombre2) funcionario, concat(f2.fun_ap_paterno, " ",f2.fun_nombre," ",f2.fun_nombre2) responsable, tp.gls_prioridad priori, te.gls_estinstruccion estado, tg.tiempo_instruccion tiempo, concat(cu.cuenta_nombre, " ", cu.cuenta_apellido) fiscal');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.fun_asignado
						  LEFT JOIN fr.funcionario f2 on f2.fun_rut=t.fun_rut
						  LEFT JOIN fr.cuenta_generica cu on cu.cuenta_rut=t.cuenta_rut
						  INNER JOIN tg_prioridad tp on tp.cod_prioridad=t.cod_prioridad
						  INNER JOIN tg_estinstruccion te on te.cod_estinstruccion=t.cod_estinstruccion';

		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		$criteria->addCondition("t.cod_estinstruccion in (1,2,3) ");	
		$criteria->addCondition("tg.cod_faminstruccion in (3) ");	
		//$criteria->addCondition("MONTH(t.fec_registro) = '".$mes."' ");	

		$criteria->order = 't.cod_prioridad DESC, t.cod_bntarea DESC';
	
		$this->getListDiligenciasTodasSinEjecutarUGI=BancoTarea::model()->findAll($criteria);	
		return $this->getListDiligenciasTodasSinEjecutarUGI; 
	}

	public function getDiligenciaCaso($id){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, concat(f.fun_ap_paterno, " ",f.fun_nombre," ",f.fun_nombre2) funcionario, concat(f2.fun_ap_paterno, " ",f2.fun_nombre," ",f2.fun_nombre2) responsable, tp.gls_prioridad priori, te.gls_estinstruccion estado, concat(f3.fun_ap_paterno, " ",f3.fun_nombre," ",f3.fun_nombre2) asignador');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.fun_asignado
						  LEFT JOIN fr.funcionario f2 on f2.fun_rut=t.fun_rut
						  INNER JOIN tg_prioridad tp on tp.cod_prioridad=t.cod_prioridad
						  INNER JOIN tg_estinstruccion te on te.cod_estinstruccion=t.cod_estinstruccion
						  LEFT JOIN estado_tarea est on est.cod_bntarea=t.cod_bntarea and est.cod_estinstruccion=3 and est.cod_esttarea= (select max(e.cod_esttarea) from estado_tarea e where e.cod_bntarea=est.cod_bntarea)
						  LEFT JOIN fr.funcionario f3 on f3.fun_rut=est.fun_responsable';

		$criteria->addCondition("t.idf_rolunico = '".$id."' ");	

	
		$criteria->order = 't.cod_bntarea DESC';	

		$this->getDiligenciaCaso=BancoTarea::model()->findAll($criteria);	
		return $this->getDiligenciaCaso; 
	}




	public function getFiscalAsignado($id){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, concat(cu.cuenta_nombre, " ", cu.cuenta_apellido) fiscal');

		$criteria->join ='LEFT JOIN fr.cuenta_generica cu on cu.cuenta_rut=t.cuenta_rut';						  
		$criteria->addCondition("t.idf_rolunico = '".$id."' ");	


		$this->getFiscalAsignado=BancoTarea::model()->findAll($criteria);	
		return $this->getFiscalAsignado; 
	}


	public function getMesesBolsa(){

		$criteria = new CDbCriteria();
		$criteria->select = array('MONTH(fec_registro) as mes_decreta');	
		
		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		$criteria->addCondition("t.cod_estinstruccion in (1,2,6)");	

		
		
		if( Yii::app()->user->getState('fiscalia') == 801 ){
			$criteria->addCondition("t.uni_codigo = '".Yii::app()->user->getState('unidad')."' ");
		}

		$criteria->group = "MONTH(fec_registro)";
	
		$this->getMesesBolsa=BancoTarea::model()->findAll($criteria);	
		return $this->getMesesBolsa; 
	}


	public function getMesesAsignadas(){

		$criteria = new CDbCriteria();
		$criteria->select = array('MONTH(fec_registro) as mes_decreta');	
		
		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		$criteria->addCondition("t.cod_estinstruccion = 3 ");	
		//$criteria->addCondition("t.uni_codigo = '".Yii::app()->user->getState('unidad')."' ");

		$criteria->group = "MONTH(fec_registro)";
	
		$this->getMesesAsignadas=BancoTarea::model()->findAll($criteria);	
		return $this->getMesesAsignadas; 
	}

	public function getMesesTodas(){

		$criteria = new CDbCriteria();
		$criteria->select = array('MONTH(fec_registro) as mes_decreta');	
		
		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		$criteria->addCondition("t.cod_estinstruccion in (1,2,3,4,5) ");	
		//$criteria->addCondition("t.uni_codigo = '".Yii::app()->user->getState('unidad')."' ");

		$criteria->group = "MONTH(fec_registro)";
	
		$this->getMesesTodas=BancoTarea::model()->findAll($criteria);	
		return $this->getMesesTodas; 
	}



	public function getListDiligencias($mes, $uni){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, te.gls_estinstruccion estado, tp.gls_prioridad priori, concat(f.fun_ap_paterno, " ",f.fun_nombre," ",f.fun_nombre2) funcionario, concat(f2.fun_ap_paterno, " ",f2.fun_nombre," ",f2.fun_nombre2) responsable, tg.tiempo_instruccion tiempo, concat(cu.cuenta_nombre, " ", cu.cuenta_apellido) fiscal');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  INNER JOIN tg_estinstruccion te on te.cod_estinstruccion=t.cod_estinstruccion
						  INNER JOIN tg_prioridad tp on tp.cod_prioridad=t.cod_prioridad
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.fun_asignado
						  LEFT JOIN fr.funcionario f2 on f2.fun_rut=t.fun_rut
						  LEFT JOIN fr.cuenta_generica cu on cu.cuenta_rut=t.cuenta_rut';	
		
		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		$criteria->addCondition("t.cod_estinstruccion in (1,2,6)");	
		$criteria->addCondition("tg.cod_faminstruccion = 3");	

		$criteria->addCondition("MONTH(t.fec_registro) = '".$mes."' ");	

		if( Yii::app()->user->getState('fiscalia') == 801 ){
			$criteria->addCondition("t.uni_codigo = '".$uni."' ");	
		}

		$criteria->order = 't.cod_prioridad DESC, t.cod_bntarea ASC, t.fec_registro ASC';
		//$criteria->limit= 000;	
		$this->getListDiligencias=BancoTarea::model()->findAll($criteria);	
		return $this->getListDiligencias; 
	}

	public function getCargaLaboralFun(){

		$criteria = new CDbCriteria();
		$criteria->select = array('sum(tg.tiempo_instruccion) min_ejecutado');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion';
		$criteria->addCondition("t.fun_asignado = '".Yii::app()->user->id."' ");			
		$criteria->addCondition("t.cod_estinstruccion = 5 ");	
		$criteria->addCondition("date(t.fec_cambioest)='".date('Y-m-d')."'");		
		$this->getCargaLaboralFun=BancoTarea::model()->findAll($criteria);	
		return $this->getCargaLaboralFun; 
	}


	public function getCargaLaboralFunFecha($fec, $fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('sum(tg.tiempo_instruccion) min_ejecutado');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion';
		
		$criteria->addCondition("t.fun_asignado = '".Yii::app()->user->id."' ");			
		$criteria->addCondition("t.cod_estinstruccion = 5 ");	



		$criteria->addCondition("date(t.fec_cambioest) >= '".$fec."'");	
		$criteria->addCondition("date(t.fec_cambioest) <= '".$fec_fin."'");	

		$this->getCargaLaboralFunFecha=BancoTarea::model()->findAll($criteria);	
		return $this->getCargaLaboralFunFecha; 
	}


	public function getListadoInstruidas(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, tp.gls_prioridad priori, tg.tiempo_instruccion tiempo');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  INNER JOIN tg_prioridad tp on tp.cod_prioridad=t.cod_prioridad';
		$criteria->addCondition("t.cod_estinstruccion = 1 ");
		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");		

		$criteria->addCondition("date(t.fec_registro)='".date('Y-m-d')."'");	
			
		$criteria->order = 't.cod_bntarea DESC';	

		$this->getListadoInstruidas=BancoTarea::model()->findAll($criteria);	
		return $this->getListadoInstruidas; 
	}

	public function getListadoInstruidasAsig(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, concat(f.fun_ap_paterno, " ",f.fun_nombre," ",f.fun_nombre2) funcionario, tp.gls_prioridad priori, tg.tiempo_instruccion tiempo ');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  INNER JOIN fr.funcionario f on f.fun_rut=t.fun_asignado
						  INNER JOIN tg_prioridad tp on tp.cod_prioridad=t.cod_prioridad';

		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");	
		$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");				

		$criteria->compare('t.cod_estinstruccion', 3);

		$criteria->addCondition("date(t.fec_registro)='".date('Y-m-d')."'");

		$criteria->order = 't.cod_bntarea DESC';	

		$this->getListadoInstruidasAsig=BancoTarea::model()->findAll($criteria);	
		return $this->getListadoInstruidasAsig; 
	}

	public function getListMisDiligencias(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, concat(f.fun_ap_paterno, " ",f.fun_nombre," ",f.fun_nombre2) funcionario, concat(f2.fun_ap_paterno, " ",f2.fun_nombre," ",f2.fun_nombre2) responsable, tp.gls_prioridad priori, tg.tiempo_instruccion tiempo, concat(cu.cuenta_nombre, " ", cu.cuenta_apellido) fiscal');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  INNER JOIN fr.funcionario f on f.fun_rut=t.fun_asignado
						  INNER JOIN fr.funcionario f2 on f2.fun_rut=t.fun_rut
						  LEFT JOIN fr.cuenta_generica cu on cu.cuenta_rut=t.cuenta_rut
						  INNER JOIN tg_prioridad tp on tp.cod_prioridad=t.cod_prioridad';
		$criteria->addCondition("t.fun_asignado = '".Yii::app()->user->id."' ");			
		$criteria->addCondition("t.cod_estinstruccion = 3 ");	
		$criteria->order = 't.cod_prioridad DESC, t.cod_bntarea DESC';	
		$criteria->limit= 1500;

		$this->getListMisDiligencias=BancoTarea::model()->findAll($criteria);	
		return $this->getListMisDiligencias; 
	}

	public function getListMisBloques(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, concat(f.fun_ap_paterno, " ",f.fun_nombre," ",f.fun_nombre2) funcionario, concat(f2.fun_ap_paterno, " ",f2.fun_nombre," ",f2.fun_nombre2) responsable, tp.gls_prioridad priori, tg.tiempo_instruccion tiempo, concat(cu.cuenta_nombre, " ", cu.cuenta_apellido) fiscal');
		$criteria->join ='LEFT JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.fun_asignado
						  LEFT JOIN fr.funcionario f2 on f2.fun_rut=t.fun_rut
						  LEFT JOIN fr.cuenta_generica cu on cu.cuenta_rut=t.cuenta_rut
						  INNER JOIN tg_prioridad tp on tp.cod_prioridad=t.cod_prioridad';

		$criteria->addCondition("t.fun_asignado = '".Yii::app()->user->id."' ");			
		$criteria->addCondition("tg.cod_faminstruccion = 2 ");	

		$criteria->addCondition("t.cod_estinstruccion = 3 ");	

		$criteria->addCondition("t.fec_tarea <= '".date('Y-m-d')."' ");

		$criteria->order = 't.cod_prioridad DESC, t.cod_bntarea DESC';	
		$criteria->limit= 500;

		$this->getListMisBloques=BancoTarea::model()->findAll($criteria);	
		return $this->getListMisBloques; 
	}



	public function getListMisDiligenciasEjecu($fec, $fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea, concat(f.fun_ap_paterno, " ",f.fun_nombre," ",f.fun_nombre2) funcionario, te.gls_estinstruccion estado, tg.tiempo_instruccion tiempo');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion
						  INNER JOIN fr.funcionario f on f.fun_rut=t.fun_asignado
						  INNER JOIN tg_estinstruccion te on te.cod_estinstruccion=t.cod_estinstruccion';

		$criteria->addCondition("t.fun_asignado = '".Yii::app()->user->id."' ");			
		$criteria->addCondition("t.cod_estinstruccion = 5 ");	

		$criteria->addCondition("date(t.fec_cambioest) >= '".$fec."'");	
		$criteria->addCondition("date(t.fec_cambioest) <= '".$fec_fin."'");	

		$criteria->order = ' t.cod_bntarea DESC';	

		$this->getListMisDiligenciasEjecu=BancoTarea::model()->findAll($criteria);	
		return $this->getListMisDiligenciasEjecu; 
	}
	

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_bntarea',$this->cod_bntarea);
		$criteria->compare('idf_rolunico',$this->idf_rolunico,true);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('cod_instruccion',$this->cod_instruccion);
		$criteria->compare('gls_comentario',$this->gls_comentario,true);
		$criteria->compare('fec_tarea',$this->fec_tarea,true);
		$criteria->compare('fec_asignacion',$this->fec_asignacion,true);
		$criteria->compare('cod_estinstruccion',$this->cod_estinstruccion);
		$criteria->compare('fec_cambioest',$this->fec_cambioest,true);
		$criteria->compare('fun_asignado',$this->fun_asignado,true);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('ind_dias',$this->ind_dias);
		$criteria->compare('cod_prioridad',$this->cod_prioridad);
		$criteria->compare('cod_plantrabajo',$this->cod_plantrabajo);
		$criteria->compare('fec_registro',$this->fec_registro,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BancoTarea the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
