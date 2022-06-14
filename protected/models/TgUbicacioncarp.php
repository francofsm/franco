<?php

/**
 * This is the model class for table "tg_ubicacioncarp".
 *
 * The followings are the available columns in table 'tg_ubicacioncarp':
 * @property integer $cod_ubicacion
 * @property string $gls_ubicacion
 * @property string $fis_codigo
 * @property integer $ind_vigencia
 * @property string $fec_registro
 * @property integer $ind_centralizado
 */
class TgUbicacioncarp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tg_ubicacioncarp';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gls_ubicacion, fis_codigo, ind_vigencia, fec_registro', 'required'),
			array('ind_vigencia, ind_centralizado', 'numerical', 'integerOnly'=>true),
			array('gls_ubicacion', 'length', 'max'=>500),
			array('fis_codigo', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_ubicacion, gls_ubicacion, fis_codigo, ind_vigencia, fec_registro, ind_centralizado', 'safe', 'on'=>'search'),
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
			'cod_ubicacion' => 'Cod Ubicacion',
			'gls_ubicacion' => 'Gls Ubicacion',
			'fis_codigo' => 'Fis Codigo',
			'ind_vigencia' => 'Ind Vigencia',
			'fec_registro' => 'Fec Registro',
			'ind_centralizado' => 'Ind Centralizado',
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

		$criteria->compare('cod_ubicacion',$this->cod_ubicacion);
		$criteria->compare('gls_ubicacion',$this->gls_ubicacion,true);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('ind_vigencia',$this->ind_vigencia);
		$criteria->compare('fec_registro',$this->fec_registro,true);
		$criteria->compare('ind_centralizado',$this->ind_centralizado);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TgUbicacioncarp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
