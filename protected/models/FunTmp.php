<?php

class FunTmp extends CActiveRecord
{

	public $getSelectFun; 
	public $funcionario;

	public function tableName()
	{
		return 'fun_tmp';
	}

	public function rules()
	{
		return array(
			array('fun_rut, fun_responsable', 'required'),
			array('fun_rut, fun_responsable', 'length', 'max'=>12),
			array('cod_funtmp, fun_rut, fun_responsable', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'cod_funtmp' => 'Cod Funtmp',
			'fun_rut' => 'Fun Rut',
			'fun_responsable' => 'Fun Responsable',
		);
	}

	public function getSelectFun(){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, concat(f.fun_ap_paterno, " ", f.fun_nombre, " ", f.fun_nombre2) as funcionario');
		$criteria->join ='INNER JOIN fr.funcionario f on f.fun_rut=t.fun_rut';
		$criteria->addCondition("t.fun_responsable = '".Yii::app()->user->id."' ");	

		$this->getSelectFun=FunTmp::model()->findAll($criteria);	
		return $this->getSelectFun; 

	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('cod_funtmp',$this->cod_funtmp);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('fun_responsable',$this->fun_responsable,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
