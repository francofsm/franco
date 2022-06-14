<?php

/**
 * This is the model class for table "anular_denuncia".
 *
 * The followings are the available columns in table 'anular_denuncia':
 * @property integer $cod_anularden
 * @property string $cod_denuncia
 * @property string $num_denuncia
 * @property string $fec_ingreso
 * @property integer $cod_origencaso
 * @property string $gls_procedencia
 * @property string $fun_rut
 * @property string $fec_registro
 */
class AnularDenuncia extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'anular_denuncia';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod_denuncia, fun_rut, fec_registro', 'required'),
			array('cod_origencaso', 'numerical', 'integerOnly'=>true),
			array('cod_denuncia', 'length', 'max'=>100),
			array('num_denuncia', 'length', 'max'=>25),
			array('fun_rut', 'length', 'max'=>12),
			array('gls_procedencia', 'length', 'max'=>500),
			array('fec_ingreso', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_anularden, cod_denuncia, num_denuncia, fec_ingreso, cod_origencaso, gls_procedencia, fun_rut, fec_registro', 'safe', 'on'=>'search'),
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
			'cod_anularden' => 'Cod Anularden',
			'cod_denuncia' => 'Cod Denuncia',
			'num_denuncia' => 'Num Denuncia',
			'fec_ingreso' => 'Fec Ingreso',
			'cod_origencaso' => 'Cod Origencaso',
			'gls_procedencia' => 'Gls Procedencia',
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

		$criteria->compare('cod_anularden',$this->cod_anularden);
		$criteria->compare('cod_denuncia',$this->cod_denuncia,true);
		$criteria->compare('num_denuncia',$this->num_denuncia,true);
		$criteria->compare('fec_ingreso',$this->fec_ingreso,true);
		$criteria->compare('cod_origencaso',$this->cod_origencaso);
		$criteria->compare('gls_procedencia',$this->gls_procedencia,true);
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
	 * @return AnularDenuncia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
