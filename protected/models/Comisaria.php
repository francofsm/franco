<?php


class Comisaria extends CActiveRecord
{


	public $getComisaria;


	public function tableName()
	{
		return 'comisaria';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod_comisaria, gls_comisaria, cod_prefectura, fis_codigo', 'required'),
			array('cod_comisaria, cod_prefectura, fis_codigo', 'numerical', 'integerOnly'=>true),
			array('gls_comisaria', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_comisaria, cod_comisaria, gls_comisaria, cod_prefectura, fis_codigo', 'safe', 'on'=>'search'),
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
			'id_comisaria' => 'Id Comisaria',
			'cod_comisaria' => 'Cod Comisaria',
			'gls_comisaria' => 'Gls Comisaria',
			'cod_prefectura' => 'Cod Prefectura',
			'fis_codigo' => 'Fis Codigo',
		);
	}

	public function getComisaria(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');	
		$criteria->addCondition('fis_codigo='.Yii::app()->user->getState('fiscalia'));
		$criteria->order = 't.cod_comisaria ASC';	

		$this->getComisaria=Comisaria::model()->findAll($criteria);	
		return $this->getComisaria; 

	}


	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_comisaria',$this->id_comisaria);
		$criteria->compare('cod_comisaria',$this->cod_comisaria);
		$criteria->compare('gls_comisaria',$this->gls_comisaria,true);
		$criteria->compare('cod_prefectura',$this->cod_prefectura);
		$criteria->compare('fis_codigo',$this->fis_codigo);

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
	 * @return Comisaria the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
