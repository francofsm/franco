<?php


class Carpeta extends CActiveRecord
{


	public $getHistoria; 
	public $getTransferidas; 
	public $getPendienteRecepcionFun; 
	public $getMisCarpetasFun; 
	public $getMisMovimientosFun; 

	public $fun;
	public $ubicacion;
	public $estado;
	public $casillero; 
	public $fiscalia; 
	public $origen;

	public function tableName()
	{
		return 'carpeta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idf_rolunico, fis_codigo, cod_estcarpeta, fec_registro, cod_tipubicacion, cod_ubicacion, fun_responsable', 'required'),
			array('cod_estcarpeta, cod_casillero, cod_tipubicacion, ind_ultmov', 'numerical', 'integerOnly'=>true),
			array('idf_rolunico', 'length', 'max'=>15),
			array('fis_codigo', 'length', 'max'=>3),
			array('cod_ubicacion, fun_responsable', 'length', 'max'=>12),
			array('gls_observacion', 'length', 'max'=>900),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_carpeta, idf_rolunico, fis_codigo, cod_estcarpeta, cod_casillero, fec_registro, cod_tipubicacion, cod_ubicacion, gls_observacion, ind_ultmov, fun_responsable', 'safe', 'on'=>'search'),
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
			'cod_carpeta' => 'Cod Carpeta',
			'idf_rolunico' => 'Idf Rolunico',
			'fis_codigo' => 'Fis Codigo',
			'cod_estcarpeta' => 'Cod Estcarpeta',
			'cod_casillero' => 'Cod Casillero',
			'fec_registro' => 'Fec Registro',
			'cod_tipubicacion' => 'Cod Tipubicacion',
			'cod_ubicacion' => 'Cod Ubicacion',
			'gls_observacion' => 'Gls Observacion',
			'ind_ultmov' => 'Ind Ultmov',
			'fun_responsable' => 'Fun Responsable',
		);
	}



	public function getPendienteRecepcionFun($rut){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, te.gls_estcarpeta estado, tc.gls_casillero casillero, CONCAT(f.fun_nombre, " ",f.fun_ap_paterno) fun, tu.gls_ubicacion ubicacion, CONCAT(f2.fun_nombre, " ",f2.fun_ap_paterno) origen');
		$criteria->join ='INNER JOIN tg_estadocarpeta te on te.cod_estcarpeta=t.cod_estcarpeta
						  LEFT JOIN tg_casillero tc on tc.cod_casillero=t.cod_casillero
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.cod_ubicacion
						  LEFT JOIN fr.funcionario f2 on f2.fun_rut=t.fun_responsable
						  LEFT JOIN tg_ubicacioncarp tu on tu.cod_ubicacion=t.cod_ubicacion';

		$criteria->addCondition("t.cod_ubicacion = '".$rut."' ");	
		$criteria->addCondition("t.cod_estcarpeta = 2 ");	
		$criteria->addCondition("t.cod_carpeta = (select max(b.cod_carpeta) from carpeta b where b.idf_rolunico=t.idf_rolunico)");
		
		$criteria->order = 't.cod_carpeta DESC';
	
		$this->getPendienteRecepcionFun=Carpeta::model()->findAll($criteria);	
		return $this->getPendienteRecepcionFun; 
	}



	public function getMisCarpetasFun($rut){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, te.gls_estcarpeta estado, tc.gls_casillero casillero, CONCAT(f.fun_nombre, " ",f.fun_ap_paterno) fun, tu.gls_ubicacion ubicacion, CONCAT(f2.fun_nombre, " ",f2.fun_ap_paterno) origen');
		$criteria->join ='INNER JOIN tg_estadocarpeta te on te.cod_estcarpeta=t.cod_estcarpeta
						  LEFT JOIN tg_casillero tc on tc.cod_casillero=t.cod_casillero
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.cod_ubicacion
						  LEFT JOIN fr.funcionario f2 on f2.fun_rut=t.fun_responsable
						  LEFT JOIN tg_ubicacioncarp tu on tu.cod_ubicacion=t.cod_ubicacion';

		$criteria->addCondition("t.cod_ubicacion = '".$rut."' ");	
		$criteria->addCondition("t.cod_estcarpeta = 3 ");	
		$criteria->addCondition("t.cod_carpeta = (select max(b.cod_carpeta) from carpeta b where b.idf_rolunico=t.idf_rolunico)");
		
		$criteria->order = 't.cod_carpeta DESC';
	
		$this->getMisCarpetasFun=Carpeta::model()->findAll($criteria);	
		return $this->getMisCarpetasFun; 
	}



	public function getMisMovimientosFun($rut){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, te.gls_estcarpeta estado, tc.gls_casillero casillero, CONCAT(f.fun_nombre, " ",f.fun_ap_paterno) fun, tu.gls_ubicacion ubicacion, CONCAT(f2.fun_nombre, " ",f2.fun_ap_paterno) origen');
		$criteria->join ='INNER JOIN tg_estadocarpeta te on te.cod_estcarpeta=t.cod_estcarpeta
						  LEFT JOIN tg_casillero tc on tc.cod_casillero=t.cod_casillero
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.cod_ubicacion
						  LEFT JOIN fr.funcionario f2 on f2.fun_rut=t.fun_responsable
						  LEFT JOIN tg_ubicacioncarp tu on tu.cod_ubicacion=t.cod_ubicacion';

		$criteria->addCondition("t.fun_responsable = '".$rut."' ");	
		$criteria->addCondition("t.cod_estcarpeta in (2,3) ");	
		$criteria->addCondition("t.cod_carpeta = (select max(b.cod_carpeta) from carpeta b where b.idf_rolunico=t.idf_rolunico)");
		
		$criteria->order = 't.cod_carpeta DESC';
	
		$this->getMisMovimientosFun=Carpeta::model()->findAll($criteria);	
		return $this->getMisMovimientosFun; 
	}




	public function getHistoria($ruc){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, te.gls_estcarpeta estado, tc.gls_casillero casillero, CONCAT(f.fun_nombre, " ",f.fun_ap_paterno) fun, tu.gls_ubicacion ubicacion, CONCAT(f2.fun_nombre, " ",f2.fun_ap_paterno) origen');
		$criteria->join ='INNER JOIN tg_estadocarpeta te on te.cod_estcarpeta=t.cod_estcarpeta
						  LEFT JOIN tg_casillero tc on tc.cod_casillero=t.cod_casillero
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.cod_ubicacion
						  LEFT JOIN fr.funcionario f2 on f2.fun_rut=t.fun_responsable
						  LEFT JOIN tg_ubicacioncarp tu on tu.cod_ubicacion=t.cod_ubicacion';

		$criteria->addCondition("t.idf_rolunico = '".$ruc."' ");	
		
		$criteria->order = 't.fec_registro DESC';
	
		$this->getHistoria=Carpeta::model()->findAll($criteria);	
		return $this->getHistoria; 
	}


	public function getTransferidas(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, te.gls_estcarpeta estado, tc.gls_casillero casillero, CONCAT(f.fun_nombre, " ",f.fun_ap_paterno) fun, tu.gls_ubicacion ubicacion, fis.fis_nombre fiscalia');
		$criteria->join ='INNER JOIN tg_estadocarpeta te on te.cod_estcarpeta=t.cod_estcarpeta
						  LEFT JOIN tg_casillero tc on tc.cod_casillero=t.cod_casillero
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.cod_ubicacion
						  LEFT JOIN tg_ubicacioncarp tu on tu.cod_ubicacion=t.cod_ubicacion
						  INNER JOIN fr.fiscalia fis on fis.fis_codigo=t.fis_codigo';


		$criteria->addCondition("t.cod_ubicacion = '1' ");	
		$criteria->addCondition("t.fun_responsable = '".Yii::app()->user->id."' ");	
		
		$criteria->order = 't.cod_carpeta DESC';
	
		$this->getTransferidas=Carpeta::model()->findAll($criteria);	
		return $this->getTransferidas; 
	}




	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_carpeta',$this->cod_carpeta);
		$criteria->compare('idf_rolunico',$this->idf_rolunico,true);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('cod_estcarpeta',$this->cod_estcarpeta);
		$criteria->compare('cod_casillero',$this->cod_casillero);
		$criteria->compare('fec_registro',$this->fec_registro,true);
		$criteria->compare('cod_tipubicacion',$this->cod_tipubicacion);
		$criteria->compare('cod_ubicacion',$this->cod_ubicacion,true);
		$criteria->compare('gls_observacion',$this->gls_observacion,true);
		$criteria->compare('ind_ultmov',$this->ind_ultmov);
		$criteria->compare('fun_responsable',$this->fun_responsable,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Carpeta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
