<?php

/**
 * This is the model class for table "fiscalia".
 *
 * The followings are the available columns in table 'fiscalia':
 * @property string $fis_codigo
 * @property string $fis_descripcion
 * @property string $fis_nombre
 * @property string $fis_direccion
 * @property string $fis_telefono
 * @property integer $ind_fiscalia_local
 * @property string $fis_sigla
 * @property integer $cod_region
 * @property integer $com_codigo
 * @property integer $cod_macrozona
 */
class Fiscalia extends CActiveRecord
{

	public $getFiscalias;

	public function tableName()
	{
		return 'fiscalia';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fis_codigo, fis_descripcion, fis_nombre, fis_direccion, ind_fiscalia_local, cod_region, com_codigo, cod_macrozona', 'required'),
			array('ind_fiscalia_local, cod_region, com_codigo, cod_macrozona', 'numerical', 'integerOnly'=>true),
			array('fis_codigo', 'length', 'max'=>3),
			array('fis_descripcion, fis_nombre, fis_direccion, fis_telefono', 'length', 'max'=>200),
			array('fis_sigla', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('fis_codigo, fis_descripcion, fis_nombre, fis_direccion, fis_telefono, ind_fiscalia_local, fis_sigla, cod_region, com_codigo, cod_macrozona', 'safe', 'on'=>'search'),
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
			'fis_codigo' => 'Fis Codigo',
			'fis_descripcion' => 'Fis Descripcion',
			'fis_nombre' => 'Fis Nombre',
			'fis_direccion' => 'Fis Direccion',
			'fis_telefono' => 'Fis Telefono',
			'ind_fiscalia_local' => 'Ind Fiscalia Local',
			'fis_sigla' => 'Fis Sigla',
			'cod_region' => 'Cod Region',
			'com_codigo' => 'Com Codigo',
			'cod_macrozona' => 'Cod Macrozona',
		);
	}


	public function getFiscalias(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');


		$criteria->addCondition("t.fis_codigo in (8,801,802,803,805,806,807,810,812,813,814,815)");

			
		
		$criteria->order = 't.fis_codigo ASC';
	
		$this->getFiscalias=Fiscalia::model()->findAll($criteria);	
		return $this->getFiscalias; 
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('fis_descripcion',$this->fis_descripcion,true);
		$criteria->compare('fis_nombre',$this->fis_nombre,true);
		$criteria->compare('fis_direccion',$this->fis_direccion,true);
		$criteria->compare('fis_telefono',$this->fis_telefono,true);
		$criteria->compare('ind_fiscalia_local',$this->ind_fiscalia_local);
		$criteria->compare('fis_sigla',$this->fis_sigla,true);
		$criteria->compare('cod_region',$this->cod_region);
		$criteria->compare('com_codigo',$this->com_codigo);
		$criteria->compare('cod_macrozona',$this->cod_macrozona);

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
	 * @return Fiscalia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
