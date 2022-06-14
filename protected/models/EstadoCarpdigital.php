<?php

/**
 * This is the model class for table "estado_carpdigital".
 *
 * The followings are the available columns in table 'estado_carpdigital':
 * @property integer $cod_estadoc
 * @property integer $cod_carpdig
 * @property integer $cod_estadocarpdig
 * @property string $fun_rut
 * @property string $fec_registro
 */
class EstadoCarpdigital extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'estado_carpdigital';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod_carpdig, cod_estadocarpdig, fun_rut, fec_registro', 'required'),
			array('cod_carpdig, cod_estadocarpdig', 'numerical', 'integerOnly'=>true),
			array('fun_rut', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_estadoc, cod_carpdig, cod_estadocarpdig, fun_rut, fec_registro', 'safe', 'on'=>'search'),
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
			'cod_estadoc' => 'Cod Estadoc',
			'cod_carpdig' => 'Cod Carpdig',
			'cod_estadocarpdig' => 'Cod Estadocarpdig',
			'fun_rut' => 'Fun Rut',
			'fec_registro' => 'Fec Registro',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_estadoc',$this->cod_estadoc);
		$criteria->compare('cod_carpdig',$this->cod_carpdig);
		$criteria->compare('cod_estadocarpdig',$this->cod_estadocarpdig);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('fec_registro',$this->fec_registro,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EstadoCarpdigital the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
