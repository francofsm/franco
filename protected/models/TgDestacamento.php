<?php


class TgDestacamento extends CActiveRecord
{

	public $getDestacamento; 

	public function tableName()
	{
		return 'tg_destacamento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod_destacamento, gls_destacamento, cod_prefectura, cod_comisaria, fis_codigo', 'required'),
			array('cod_destacamento, cod_prefectura, cod_comisaria', 'numerical', 'integerOnly'=>true),
			array('gls_destacamento', 'length', 'max'=>500),
			array('fis_codigo', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_destaca, cod_destacamento, gls_destacamento, cod_prefectura, cod_comisaria, fis_codigo', 'safe', 'on'=>'search'),
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
			'cod_destaca' => 'Cod Destaca',
			'cod_destacamento' => 'Cod Destacamento',
			'gls_destacamento' => 'Gls Destacamento',
			'cod_prefectura' => 'Cod Prefectura',
			'cod_comisaria' => 'Cod Comisaria',
			'fis_codigo' => 'Fis Codigo',
		);
	}

	public function getDestacamento(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');	
		$criteria->addCondition('fis_codigo='.Yii::app()->user->getState('fiscalia'));
		$criteria->order = 't.cod_destacamento ASC';	

		$this->getDestacamento=TgDestacamento::model()->findAll($criteria);	
		return $this->getDestacamento; 

	}
	
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_destaca',$this->cod_destaca);
		$criteria->compare('cod_destacamento',$this->cod_destacamento);
		$criteria->compare('gls_destacamento',$this->gls_destacamento,true);
		$criteria->compare('cod_prefectura',$this->cod_prefectura);
		$criteria->compare('cod_comisaria',$this->cod_comisaria);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);

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
	 * @return TgDestacamento the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
