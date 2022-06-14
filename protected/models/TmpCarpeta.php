<?php

class TmpCarpeta extends CActiveRecord
{

	public $getRecepcionadas; 
	public $getMovidas; 

	public $estado; 
	public $casillero;
	public $fun;
	public $ubicacion; 


	public function tableName()
	{
		return 'tmp_carpeta';
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
			array('cod_carpeta, cod_tmpcarpeta, idf_rolunico, fis_codigo, cod_estcarpeta, cod_casillero, fec_registro, cod_tipubicacion, cod_ubicacion, gls_observacion, ind_ultmov, fun_responsable', 'safe', 'on'=>'search'),
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
			'cod_tmpcarpeta' => 'Cod Tmpcarpeta',
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


	public function getRecepcionadas($rut){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, te.gls_estcarpeta estado, tc.gls_casillero casillero, CONCAT(f.fun_nombre, " ",f.fun_ap_paterno) fun, tu.gls_ubicacion ubicacion');
		$criteria->join ='INNER JOIN tg_estadocarpeta te on te.cod_estcarpeta=t.cod_estcarpeta
						  LEFT JOIN tg_casillero tc on tc.cod_casillero=t.cod_casillero
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.cod_ubicacion
						  LEFT JOIN tg_ubicacioncarp tu on tu.cod_ubicacion=t.cod_ubicacion';

		$criteria->addCondition("t.cod_estcarpeta = '3' ");
		$criteria->addCondition("t.fun_responsable = '".$rut."' ");	
		
		$criteria->order = 't.cod_tmpcarpeta DESC';
	
		$this->getRecepcionadas=TmpCarpeta::model()->findAll($criteria);	
		return $this->getRecepcionadas; 
	}


	public function getMovidas($rut){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, te.gls_estcarpeta estado, tc.gls_casillero casillero, CONCAT(f.fun_nombre, " ",f.fun_ap_paterno) fun, tu.gls_ubicacion ubicacion');
		$criteria->join ='INNER JOIN tg_estadocarpeta te on te.cod_estcarpeta=t.cod_estcarpeta
						  LEFT JOIN tg_casillero tc on tc.cod_casillero=t.cod_casillero
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.cod_ubicacion
						  LEFT JOIN tg_ubicacioncarp tu on tu.cod_ubicacion=t.cod_ubicacion';

		$criteria->addCondition("t.cod_estcarpeta = '2' ");
		$criteria->addCondition("t.fun_responsable = '".$rut."' ");	
		
		$criteria->order = 't.cod_tmpcarpeta DESC';
	
		$this->getMovidas=TmpCarpeta::model()->findAll($criteria);	
		return $this->getMovidas; 
	}



	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_tmpcarpeta',$this->cod_tmpcarpeta);
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
	 * @return TmpCarpeta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
