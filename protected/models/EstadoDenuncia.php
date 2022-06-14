<?php

/**
 * This is the model class for table "estado_denuncia".
 *
 * The followings are the available columns in table 'estado_denuncia':
 * @property integer $cod_estadoden
 * @property integer $cod_denuncia
 * @property integer $cod_estcarpeta
 * @property string $fec_registro
 * @property string $fun_rut
 * @property string $fun_responsable
 */
class EstadoDenuncia extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'estado_denuncia';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod_denuncia, cod_estcarpeta, fec_registro, fun_rut, fun_responsable', 'required'),
			array('cod_denuncia, cod_estcarpeta', 'numerical', 'integerOnly'=>true),
			array('fun_rut, fun_responsable', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_estadoden, cod_denuncia, cod_estcarpeta, fec_registro, fun_rut, fun_responsable', 'safe', 'on'=>'search'),
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
			'cod_estadoden' => 'Cod Estadoden',
			'cod_denuncia' => 'Cod Denuncia',
			'cod_estcarpeta' => 'Cod Estcarpeta',
			'fec_registro' => 'Fec Registro',
			'fun_rut' => 'Fun Rut',
			'fun_responsable' => 'Fun Responsable',
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

		$criteria->compare('cod_estadoden',$this->cod_estadoden);
		$criteria->compare('cod_denuncia',$this->cod_denuncia);
		$criteria->compare('cod_estcarpeta',$this->cod_estcarpeta);
		$criteria->compare('fec_registro',$this->fec_registro,true);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('fun_responsable',$this->fun_responsable,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EstadoDenuncia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
