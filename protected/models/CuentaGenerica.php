<?php


class CuentaGenerica extends CActiveRecord
{


	public $getCuentasFiscal;
	public $getCuentasFiscalesVigentes;

	public $getCuentasFiscales; 

	public function tableName()
	{
		return 'cuenta_generica';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cuenta_rut, cuenta_nombre, cuenta_apellido, ind_tcmc, rut_saf', 'required'),
			array('ind_tcmc, ind_vig', 'numerical', 'integerOnly'=>true),
			array('cuenta_rut, rut_saf', 'length', 'max'=>12),
			array('cuenta_nombre, cuenta_apellido', 'length', 'max'=>200),
			array('fis_codigo', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cuenta_rut, cuenta_nombre, cuenta_apellido, fis_codigo, ind_tcmc, ind_vig, rut_saf', 'safe', 'on'=>'search'),
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
			'cuenta_rut' => 'Cuenta Rut',
			'cuenta_nombre' => 'Cuenta Nombre',
			'cuenta_apellido' => 'Cuenta Apellido',
			'fis_codigo' => 'Fis Codigo',
			'ind_tcmc' => 'Ind Tcmc',
			'ind_vig' => 'Ind Vig',
			'rut_saf' => 'Rut Saf',
		);
	}

	public function getCuentasFiscal($fis){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		$criteria->join ='INNER JOIN bd_sia.banco_tarea bn on bn.cuenta_rut=t.cuenta_rut';

		$criteria->addCondition("t.fis_codigo = '".$fis."' ");
		$criteria->compare('t.ind_vig',1);

		$criteria->group = "t.cuenta_rut";
		
		$criteria->order = 't.cuenta_nombre ASC';

		$this->getCuentasFiscal=CuentaGenerica::model()->findAll($criteria);	
		return $this->getCuentasFiscal; 
	}


	public function getCuentasFiscalesVigentes($fis){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		//$criteria->join ='INNER JOIN bd_sia.banco_tarea bn on bn.cuenta_rut=t.cuenta_rut';

		$criteria->addCondition("t.fis_codigo = '".$fis."' ");
		$criteria->compare('t.ind_vig',1);

		$criteria->group = "t.cuenta_rut";
		
		$criteria->order = 't.cuenta_nombre ASC';

		$this->getCuentasFiscalesVigentes=CuentaGenerica::model()->findAll($criteria);	
		return $this->getCuentasFiscalesVigentes; 
	}

	public function getCuentasFiscales(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');

		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		$criteria->compare('t.ind_vig',1);
		$criteria->group = "t.cuenta_rut";		
		$criteria->order = 't.cuenta_nombre ASC';

		$this->getCuentasFiscales=CuentaGenerica::model()->findAll($criteria);	
		return $this->getCuentasFiscales; 
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cuenta_rut',$this->cuenta_rut,true);
		$criteria->compare('cuenta_nombre',$this->cuenta_nombre,true);
		$criteria->compare('cuenta_apellido',$this->cuenta_apellido,true);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('ind_tcmc',$this->ind_tcmc);
		$criteria->compare('ind_vig',$this->ind_vig);
		$criteria->compare('rut_saf',$this->rut_saf,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_fr;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CuentaGenerica the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
