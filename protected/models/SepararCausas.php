<?php

class SepararCausas extends CActiveRecord
{

	public $getSeparaciones; 

	public function tableName()
	{
		return 'separar_causas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idf_rolunico, idf_rolunico_original, fun_rut, fec_registro, ind_vigencia', 'required'),
			array('ind_vigencia', 'numerical', 'integerOnly'=>true),
			array('idf_rolunico, idf_rolunico_original', 'length', 'max'=>15),
			array('fun_rut', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_serpararcausa, idf_rolunico, idf_rolunico_original, fun_rut, fec_registro, ind_vigencia', 'safe', 'on'=>'search'),
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
			'cod_serpararcausa' => 'Cod Serpararcausa',
			'idf_rolunico' => 'Idf Rolunico',
			'idf_rolunico_original' => 'Idf Rolunico Original',
			'fun_rut' => 'Fun Rut',
			'fec_registro' => 'Fec Registro',
			'ind_vigencia' => 'Ind Vigencia',
		);
	}

	public function getSeparaciones(){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		//$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		$this->getSeparaciones=SepararCausas::model()->findAll($criteria);	
		return $this->getSeparaciones; 
	}


	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_serpararcausa',$this->cod_serpararcausa);
		$criteria->compare('idf_rolunico',$this->idf_rolunico,true);
		$criteria->compare('idf_rolunico_original',$this->idf_rolunico_original,true);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('fec_registro',$this->fec_registro,true);
		$criteria->compare('ind_vigencia',$this->ind_vigencia);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SepararCausas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
