<?php


class CarpetaAudiencia extends CActiveRecord
{

	public $getListadoAudiencia;
	public $getAudienciaMinuta;
	public $getListadoAudienciaFiscal;
	public $getListadoAudienciaSala1Fiscal;
	public $getListadoAudienciaControles;
	public $getListadoAudienciaSala2;
	public $getListadoAudienciatop;
	public $getListadoAudienciasFiscalia;
	public $getMinutaAud;


	public $responsable;
	public $audiencia;
	public $salaaud;
	public $horaaud;
	public $horaaud1;
	public $horaaud2;
	public $digitalizada;
	public $gls_ruta;
	public $salaa;
	public $fiscal;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'carpeta_audiencia';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idf_rolunico, fis_codigo, fec_audiencia, cod_tipaudiencia, fun_rut, fec_registro', 'required'),
			array('cod_tipaudiencia, cod_carpdig, cod_carpdigresultado', 'numerical', 'integerOnly'=>true),
			array('idf_rolunico', 'length', 'max'=>15),
			array('fis_codigo', 'length', 'max'=>3),
			array('fun_rut', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_carpdigresultado, cod_carpaud, idf_rolunico, fis_codigo, fec_audiencia, cod_tipaudiencia, cod_carpdig, fun_rut, fec_registro', 'safe', 'on'=>'search'),
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
			'cod_carpaud' => 'Cod Carpaud',
			'idf_rolunico' => 'Idf Rolunico',
			'fis_codigo' => 'Fis Codigo',
			'fec_audiencia' => 'Fec Audiencia',
			'cod_tipaudiencia' => 'Cod TipoAudiencia',
			'cod_carpdig' => 'Cod Carpdig',
			'cod_carpdigresultado' => 'Resultado Audiencia',
			'fun_rut' => 'Fun Rut',
			'fec_registro' => 'Fec Registro',
		);
	}

	/* Listado de Auidiencias registradas por funcionario  *///
	public function getListadoAudiencia(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, CONCAT(f.fun_nombre, " ",f.fun_ap_paterno) responsable, aud.gls_tipaudiencia audiencia, salaaud.sala_aud salaaud, hora.hora horaaud, cd.idf_rolunico digitalizada');
		$criteria->join ='INNER JOIN fr.funcionario f on f.fun_rut=t.fun_rut
						  INNER JOIN tg_tipaudiencia aud on aud.cod_tipaudiencia = t.cod_tipaudiencia
						  INNER JOIN tg_salaaud salaaud on salaaud.cod_salaaud = t.cod_salaaud
						  INNER JOIN tg_hora hora on hora.cod_hora = t.cod_hora
						  LEFT JOIN carpeta_digital cd on cd.idf_rolunico = t.idf_rolunico';
		$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");			
		$criteria->addCondition("date(t.fec_registro) = '".date('Y-m-d')."'");	
		//$criteria->addCondition("t.idf_rolunico = '".$ruc."'");	
		//$criteria->addCondition("t.fis_codigo = '".$fisc."'");	
		$criteria->group = "t.idf_rolunico, t.cod_salaaud, t.cod_hora";
		$criteria->order = 't.cod_hora, t.cod_carpaud ASC';
	
		$this->getListadoAudiencia=CarpetaAudiencia::model()->findAll($criteria);	
		return $this->getListadoAudiencia; 
	}


	/* Listado de Audiencias disponibles para Fiscal AGREGAR ESTADO DE AUDIENCIA *///
	public function getListadoAudienciaFiscal($fecha){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, aud.gls_tipaudiencia audiencia, cd.gls_ruta gls_ruta, concat(f2.cuenta_nombre, " ",f2.cuenta_apellido) fiscal, hora.hora horaaud');
		$criteria->join ='INNER JOIN tg_tipaudiencia aud on aud.cod_tipaudiencia = t.cod_tipaudiencia
						  INNER JOIN tg_hora hora on hora.cod_hora = t.cod_hora
						  LEFT JOIN carpeta_digital cd on cd.cod_carpdig = t.cod_carpdig
						  LEFT JOIN causa_vigente cv on cv.idf_rolunico = t.idf_rolunico
						  LEFT JOIN fr.cuenta_generica f2 on f2.rut_saf = cv.cuenta_rut';
		//$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");			
		$criteria->addCondition("date(t.fec_audiencia) = '".$fecha."'");
		$criteria->addCondition("t.cod_tipaudiencia <> 1");	
		$criteria->addCondition("t.cod_salaaud = 1");	 /// sala 1
		//$criteria->addCondition("t.idf_rolunico = '".$ruc."'");	
		//$criteria->addCondition("t.fis_codigo = '".$fisc."'");	
		$criteria->group = "t.idf_rolunico";
		$criteria->order = 't.cod_hora, t.cod_carpaud ASC';
	
		$this->getListadoAudienciaFiscal=CarpetaAudiencia::model()->findAll($criteria);	
		return $this->getListadoAudienciaFiscal; 
	}



	/* Listado de Audiencias disponibles para Fiscal AGREGAR ESTADO DE AUDIENCIA *///
	public function getListadoAudienciaSala1Fiscal($fecha){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, aud.gls_tipaudiencia audiencia,hora.hora horaaud1, cd.gls_ruta gls_ruta, concat(f2.cuenta_nombre, " ",f2.cuenta_apellido) fiscal');
		$criteria->join ='INNER JOIN tg_tipaudiencia aud on aud.cod_tipaudiencia = t.cod_tipaudiencia
						  INNER JOIN tg_hora hora on hora.cod_hora = t.cod_hora
						  LEFT JOIN carpeta_digital cd on cd.cod_carpdig = t.cod_carpdig
						  LEFT JOIN causa_vigente cv on cv.idf_rolunico = t.idf_rolunico
						  LEFT JOIN fr.cuenta_generica f2 on f2.rut_saf = cv.cuenta_rut';
		//$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");			
		$criteria->addCondition("date(t.fec_audiencia) = '".$fecha."'");
		$criteria->addCondition("t.cod_tipaudiencia <> 1");	
		$criteria->addCondition("t.cod_salaaud = 1");	 /// sala 1
		//$criteria->addCondition("t.idf_rolunico = '".$ruc."'");	
		//$criteria->addCondition("t.fis_codigo = '".$fisc."'");	
		$criteria->group = "t.idf_rolunico";
		$criteria->order = 't.cod_hora, t.cod_carpaud ASC';
	
		$this->getListadoAudienciaSala1Fiscal=CarpetaAudiencia::model()->findAll($criteria);	
		return $this->getListadoAudienciaSala1Fiscal; 
	}





	/* Listado de Controles de detención disponibles para Fiscal AGREGAR ESTADO DE AUDIENCIA *///
	public function getListadoAudienciaControles($fecha){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, aud.gls_tipaudiencia audiencia, cd.gls_ruta gls_ruta');
		$criteria->join ='INNER JOIN tg_tipaudiencia aud on aud.cod_tipaudiencia = t.cod_tipaudiencia
						  LEFT JOIN carpeta_digital cd on cd.cod_carpdig = t.cod_carpdig';
		//$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");			
		$criteria->addCondition("date(t.fec_audiencia) = '".$fecha."'");
		$criteria->addCondition("t.cod_tipaudiencia = 1");	
		$criteria->addCondition("t.cod_salaaud = 3");  /// sala controles	
		//$criteria->addCondition("t.fis_codigo = '".$fisc."'");	
		$criteria->order = 'aud.gls_tipaudiencia ASC';
	
		$this->getListadoAudienciaControles=CarpetaAudiencia::model()->findAll($criteria);	
		return $this->getListadoAudienciaControles; 
	}

	/* Listado de Controles de detención disponibles para Fiscal AGREGAR ESTADO DE AUDIENCIA *///
	public function getListadoAudienciaSala2($fecha){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, aud.gls_tipaudiencia audiencia, hora.hora horaaud2, cd.gls_ruta gls_ruta, concat(f2.cuenta_nombre, " ",f2.cuenta_apellido) fiscal');
		$criteria->join ='INNER JOIN tg_tipaudiencia aud on aud.cod_tipaudiencia = t.cod_tipaudiencia
		 				  INNER JOIN tg_hora hora on hora.cod_hora = t.cod_hora
						  LEFT JOIN carpeta_digital cd on cd.cod_carpdig = t.cod_carpdig
						  LEFT JOIN causa_vigente cv on cv.idf_rolunico = t.idf_rolunico
						  LEFT JOIN fr.cuenta_generica f2 on f2.rut_saf = cv.cuenta_rut';
		//$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");			
		$criteria->addCondition("date(t.fec_audiencia) = '".$fecha."'");
		$criteria->addCondition("t.cod_tipaudiencia <> 1");	
		$criteria->addCondition("t.cod_salaaud = 2");	 /// sala 2
		//$criteria->addCondition("t.fis_codigo = '".$fisc."'");	
		$criteria->group = "t.idf_rolunico";
		$criteria->order = 't.cod_hora, t.cod_carpaud ASC';
	
		$this->getListadoAudienciaSala2=CarpetaAudiencia::model()->findAll($criteria);	
		return $this->getListadoAudienciaSala2; 
	}


	/* Listado de Controles de detención disponibles para Fiscal AGREGAR ESTADO DE AUDIENCIA *///
	public function getListadoAudienciatop($fecha,$fisc){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, aud.gls_tipaudiencia audiencia, hora.hora horaaud2, cd.gls_ruta gls_ruta, concat(f2.cuenta_nombre, " ",f2.cuenta_apellido) fiscal, sa.sala_aud salaa');
		$criteria->join ='INNER JOIN tg_tipaudiencia aud on aud.cod_tipaudiencia = t.cod_tipaudiencia
		 				  INNER JOIN tg_hora hora on hora.cod_hora = t.cod_hora
						  LEFT JOIN carpeta_digital cd on cd.cod_carpdig = t.cod_carpdig
						  LEFT JOIN causa_vigente cv on cv.idf_rolunico = t.idf_rolunico
						  LEFT JOIN fr.cuenta_generica f2 on f2.rut_saf = cv.cuenta_rut
						  INNER JOIN tg_salaaud sa on sa.cod_salaaud = t.cod_salaaud';
		//$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");			
		$criteria->addCondition("date(t.fec_audiencia) = '".$fecha."'");
		//$criteria->addCondition("t.cod_tipaudiencia <> 1");	
		$criteria->addCondition("t.cod_salaaud in (4,5)");	 /// Top - Corte
		$criteria->addCondition("t.fis_codigo = '".$fisc."'");	
		$criteria->group = "t.idf_rolunico";
		$criteria->order = 't.cod_hora, t.cod_carpaud ASC';
	
		$this->getListadoAudienciatop=CarpetaAudiencia::model()->findAll($criteria);	
		return $this->getListadoAudienciatop; 
	}


	public function getListadoAudienciasFiscalia($fecha,$cod_sala){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, aud.gls_tipaudiencia audiencia, hora.hora horaaud, cd.gls_ruta gls_ruta, concat(f2.cuenta_nombre, " ",f2.cuenta_apellido) fiscal');
		$criteria->join ='INNER JOIN tg_tipaudiencia aud on aud.cod_tipaudiencia = t.cod_tipaudiencia
		 				  INNER JOIN tg_hora hora on hora.cod_hora = t.cod_hora
						  LEFT JOIN carpeta_digital cd on cd.cod_carpdig = t.cod_carpdig
						  LEFT JOIN causa_vigente cv on cv.idf_rolunico = t.idf_rolunico
						  LEFT JOIN fr.cuenta_generica f2 on f2.rut_saf = cv.cuenta_rut';
		//$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");			
		$criteria->addCondition("date(t.fec_audiencia) = '".$fecha."'");
		//$criteria->addCondition("t.cod_tipaudiencia <> 1");	
		$criteria->addCondition("t.cod_salaaud = '".$cod_sala."'");	 /// sala elegida
		//$criteria->addCondition("t.fis_codigo = '".$fisc."'");	
		$criteria->group = "t.idf_rolunico";
		$criteria->order = 't.cod_hora, t.cod_carpaud ASC';
	
		$this->getListadoAudienciasFiscalia=CarpetaAudiencia::model()->findAll($criteria);	
		return $this->getListadoAudienciasFiscalia; 
	}



	/* Audiencia para la minuta *///
	public function getAudienciaMinuta($id){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, aud.gls_tipaudiencia audiencia');
		$criteria->join ='INNER JOIN tg_tipaudiencia aud on aud.cod_tipaudiencia = t.cod_tipaudiencia';
		$criteria->addCondition("t.cod_carpaud = '".$id."'");	
		//$criteria->order = 'aud.gls_tipaudiencia ASC';
	
		$this->getAudienciaMinuta=CarpetaAudiencia::model()->findAll($criteria);	
		return $this->getAudienciaMinuta; 
	}

	

	/**** Minuta para eliminar  -  No utilizable*////

	/*	public function getMinutaAud($id){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		//$criteria->join ='INNER JOIN g_tipaudiencia aud on aud.cod_tipaudiencia = t.cod_tipaudiencia';
		$criteria->addCondition("t.cod_carpdig = '".$id."'");	
	
		$this->getMinutaAud=CarpetaAudiencia::model()->findAll($criteria);	
		return $this->getMinutaAud; 
	}*/



	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_carpaud',$this->cod_carpaud);
		$criteria->compare('idf_rolunico',$this->idf_rolunico,true);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('fec_audiencia',$this->fec_audiencia,true);
		$criteria->compare('cod_tipaudiencia',$this->cod_clasedoc);
		$criteria->compare('cod_carpdig',$this->cod_carpdig);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('fec_registro',$this->fec_registro,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
