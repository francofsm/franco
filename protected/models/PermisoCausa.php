<?php

class PermisoCausa extends CActiveRecord
{

	public $getPermisoCausa; 
	public $total;

	public function tableName()
	{
		return 'permiso_causa';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fis_codigo, cuenta_rut, fun_rut, ind_fiscalasig', 'required'),
			array('ind_fiscalasig', 'numerical', 'integerOnly'=>true),
			array('fis_codigo', 'length', 'max'=>3),
			array('cuenta_rut, fun_rut', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_permisocausa, fis_codigo, cuenta_rut, fun_rut, ind_fiscalasig', 'safe', 'on'=>'search'),
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
			'cod_permisocausa' => 'Cod Permisocausa',
			'fis_codigo' => 'Fis Codigo',
			'cuenta_rut' => 'Cuenta Rut',
			'fun_rut' => 'Fun Rut',
			'ind_fiscalasig' => 'Ind Fiscalasig',
		);
	}

	public function getPermisoCausa($fiscal){

		$criteria = new CDbCriteria();
		$criteria->select = array('count(t.cod_permisocausa) total');
		$criteria->addCondition("t.cuenta_rut = '".$fiscal."' ");
		$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");		

		$this->getPermisoCausa=PermisoCausa::model()->findAll($criteria);	
		return $this->getPermisoCausa; 
	}


	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_permisocausa',$this->cod_permisocausa);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('cuenta_rut',$this->cuenta_rut,true);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('ind_fiscalasig',$this->ind_fiscalasig);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PermisoCausa the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
