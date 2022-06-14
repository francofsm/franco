<?php

class DilTmp extends CActiveRecord
{

	public $getSelectDil; 
	public $tarea;


	public function tableName()
	{
		return 'dil_tmp';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fun_rut, cod_instruccion', 'required'),
			array('cod_instruccion', 'numerical', 'integerOnly'=>true),
			array('fun_rut', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_diltmp, fun_rut, cod_instruccion', 'safe', 'on'=>'search'),
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
			'cod_diltmp' => 'Cod Diltmp',
			'fun_rut' => 'Fun Rut',
			'cod_instruccion' => 'Cod Instruccion',
		);
	}


	public function getSelectDil($rut){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tg.gls_instruccion tarea');
		$criteria->join ='INNER JOIN tg_instruccion tg on tg.cod_instruccion=t.cod_instruccion';
		$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");	
		$this->getSelectDil=DilTmp::model()->findAll($criteria);	
		return $this->getSelectDil; 
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_diltmp',$this->cod_diltmp);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('cod_instruccion',$this->cod_instruccion);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DilTmp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
