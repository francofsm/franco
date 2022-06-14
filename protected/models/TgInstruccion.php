<?php


class TgInstruccion extends CActiveRecord
{

	public $getDiligencias; 
	public $getTareasAdmin; 
	public $familia;


	public $getLisTareaReporteCompara; 
	public $getDiligenciasTodas; 

	public $getLisTareaReporteEjecutada; 

	public function tableName(){
		return 'tg_instruccion';
	}

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gls_instruccion, tiempo_instruccion, cod_faminstruccion', 'required'),
			array('tiempo_instruccion, cod_faminstruccion, ind_vigencia', 'numerical', 'integerOnly'=>true),
			array('gls_instruccion', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_instruccion, gls_instruccion, tiempo_instruccion, cod_faminstruccion, ind_vigencia', 'safe', 'on'=>'search'),
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
			'cod_instruccion' => 'Cod Instruccion',
			'gls_instruccion' => 'Gls Instruccion',
			'tiempo_instruccion' => 'Tiempo Instruccion',
			'cod_faminstruccion' => 'Cod Faminstruccion',
		);
	}



	public function getLisTareaReporteCompara($funs, $fec_ini, $fec_fin){

		$criteria = new CDbCriteria(array('order'=>'gls_instruccion ASC'));
		$criteria->select = array('t.*');
		$criteria->join ='INNER JOIN banco_tarea bt on bt.cod_instruccion=t.cod_instruccion';
		$criteria->addCondition("bt.fun_asignado in (".$funs.") ");
		//$criteria->addCondition("t.cod_instruccion <> 153 ");

		$criteria->addCondition("bt.cod_estinstruccion in (1,2,3,4,5)");	
			

		$criteria->addCondition("date(bt.fec_tarea) >= '".$fec_ini."'");	
		$criteria->addCondition("date(bt.fec_tarea) <= '".$fec_fin."'");	


		$criteria->group = "t.cod_instruccion";

		$this->getLisTareaReporteCompara=TgInstruccion::model()->findAll($criteria);	
		return $this->getLisTareaReporteCompara; 
	}


	public function getLisTareaReporteEjecutada($funs, $fec_ini, $fec_fin){

		$criteria = new CDbCriteria(array('order'=>'gls_instruccion ASC'));
		$criteria->select = array('t.*');
		$criteria->join ='INNER JOIN banco_tarea bt on bt.cod_instruccion=t.cod_instruccion';
		$criteria->addCondition("bt.fun_asignado in (".$funs.") ");
		//$criteria->addCondition("t.cod_instruccion <> 153 ");

		$criteria->addCondition("bt.cod_estinstruccion = 5");	
			

		$criteria->addCondition("date(bt.fec_cambioest) >= '".$fec_ini."'");	
		$criteria->addCondition("date(bt.fec_cambioest) <= '".$fec_fin."'");	


		$criteria->group = "t.cod_instruccion";

		$this->getLisTareaReporteEjecutada=TgInstruccion::model()->findAll($criteria);	
		return $this->getLisTareaReporteEjecutada; 
	}


	public function getDiligenciasTodas(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, fam.gls_faminstruccion familia');
		$criteria->join ='INNER JOIN tg_familiainstruccion fam on fam.cod_faminstruccion=t.cod_faminstruccion';

		$criteria->order = 'gls_instruccion ASC';	

		$this->getDiligenciasTodas=TgInstruccion::model()->findAll($criteria);	
		return $this->getDiligenciasTodas; 
	}



	public function getDiligencias(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		//$criteria->compare('cod_faminstruccion',3);
		$criteria->addCondition("t.cod_faminstruccion = 3 ");	
		$criteria->addCondition("t.ind_vigencia = 1 ");	
		$criteria->order = 'gls_instruccion ASC';	

		$this->getDiligencias=TgInstruccion::model()->findAll($criteria);	
		return $this->getDiligencias; 
	}


	public function getTareasAdmin(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		//$criteria->compare('cod_faminstruccion',3);
		$criteria->addCondition("t.cod_faminstruccion = 2 ");	
		$criteria->addCondition("t.ind_vigencia = 1 ");	
		
		$criteria->order = 'gls_instruccion ASC';	

		$this->getTareasAdmin=TgInstruccion::model()->findAll($criteria);	
		return $this->getTareasAdmin; 
	}


	public function search(){
		$criteria=new CDbCriteria;

		$criteria->compare('cod_instruccion',$this->cod_instruccion);
		$criteria->compare('gls_instruccion',$this->gls_instruccion,true);
		$criteria->compare('tiempo_instruccion',$this->tiempo_instruccion);
		$criteria->compare('cod_faminstruccion',$this->cod_faminstruccion);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TgInstruccion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
