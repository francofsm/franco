<?php

/**
 * This is the model class for table "reserva_documento".
 *
 * The followings are the available columns in table 'reserva_documento':
 * @property integer $cod_reservadoc
 * @property string $idf_rolunico
 * @property integer $cod_carpdig
 * @property integer $ind_reserva
 * @property string $fun_rut
 * @property string $fec_registro
 */
class ReservaDocumento extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'reserva_documento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idf_rolunico, cod_carpdig, ind_reserva, fun_rut, fec_registro', 'required'),
			array('cod_carpdig, ind_reserva', 'numerical', 'integerOnly'=>true),
			array('idf_rolunico', 'length', 'max'=>15),
			array('fun_rut', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_reservadoc, idf_rolunico, cod_carpdig, ind_reserva, fun_rut, fec_registro', 'safe', 'on'=>'search'),
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
			'cod_reservadoc' => 'Cod Reservadoc',
			'idf_rolunico' => 'Idf Rolunico',
			'cod_carpdig' => 'Cod Carpdig',
			'ind_reserva' => 'Ind Reserva',
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

		$criteria->compare('cod_reservadoc',$this->cod_reservadoc);
		$criteria->compare('idf_rolunico',$this->idf_rolunico,true);
		$criteria->compare('cod_carpdig',$this->cod_carpdig);
		$criteria->compare('ind_reserva',$this->ind_reserva);
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
	 * @return ReservaDocumento the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
