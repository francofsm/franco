<?php

class FavoritoDocumento extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'favorito_documento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idf_rolunico, cod_carpdig, ind_favorito, fun_rut, fec_registro', 'required'),
			array('cod_carpdig, ind_favorito', 'numerical', 'integerOnly'=>true),
			array('idf_rolunico', 'length', 'max'=>15),
			array('fun_rut', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_favoritodoc, idf_rolunico, cod_carpdig, ind_favorito, fun_rut, fec_registro', 'safe', 'on'=>'search'),
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
			'cod_favoritodoc' => 'Cod Favoritodoc',
			'idf_rolunico' => 'Idf Rolunico',
			'cod_carpdig' => 'Cod Carpdig',
			'ind_favorito' => 'Ind Favorito',
			'fun_rut' => 'Fun Rut',
			'fec_registro' => 'Fec Registro',
		);
	}

	
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_favoritodoc',$this->cod_favoritodoc);
		$criteria->compare('idf_rolunico',$this->idf_rolunico,true);
		$criteria->compare('cod_carpdig',$this->cod_carpdig);
		$criteria->compare('ind_favorito',$this->ind_favorito);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('fec_registro',$this->fec_registro,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
