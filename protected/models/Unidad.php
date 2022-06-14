<?php

class Unidad extends CActiveRecord
{

	public $getUnidades; 


	public function tableName()
	{
		return 'unidad';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uni_descripcion, uni_sigla, fis_codigo', 'required'),
			array('uni_descripcion', 'length', 'max'=>200),
			array('uni_sigla', 'length', 'max'=>50),
			array('fis_codigo', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('uni_codigo, uni_descripcion, uni_sigla, fis_codigo', 'safe', 'on'=>'search'),
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
			'uni_codigo' => 'Uni Codigo',
			'uni_descripcion' => 'Uni Descripcion',
			'uni_sigla' => 'Uni Sigla',
			'fis_codigo' => 'Fis Codigo',
		);
	}

	public function getUnidades(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');


		if(Yii::app()->user->getState('perfil')<>13 ){
			$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		}
			
		
		$criteria->order = 't.uni_codigo ASC';
	
		$this->getUnidades=Unidad::model()->findAll($criteria);	
		return $this->getUnidades; 
	}



	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('uni_codigo',$this->uni_codigo);
		$criteria->compare('uni_descripcion',$this->uni_descripcion,true);
		$criteria->compare('uni_sigla',$this->uni_sigla,true);
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
	 * @return Unidad the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
