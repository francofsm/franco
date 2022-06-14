<?php

/**
 * This is the model class for table "delito".
 *
 * The followings are the available columns in table 'delito':
 * @property integer $cod_materia
 * @property string $gls_materia
 * @property string $nom_materia
 * @property integer $gls_vigencia
 * @property integer $clasif_hechodelictual
 * @property integer $orden_priorizado
 * @property integer $cod_agrupadel
 */
class Delito extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'delito';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gls_materia, clasif_hechodelictual, orden_priorizado', 'required'),
			array('gls_vigencia, clasif_hechodelictual, orden_priorizado, cod_agrupadel', 'numerical', 'integerOnly'=>true),
			array('gls_materia', 'length', 'max'=>150),
			array('nom_materia', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_materia, gls_materia, nom_materia, gls_vigencia, clasif_hechodelictual, orden_priorizado, cod_agrupadel', 'safe', 'on'=>'search'),
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
			'cod_materia' => 'Cod Materia',
			'gls_materia' => 'Gls Materia',
			'nom_materia' => 'Nom Materia',
			'gls_vigencia' => 'Gls Vigencia',
			'clasif_hechodelictual' => 'Clasif Hechodelictual',
			'orden_priorizado' => 'Orden Priorizado',
			'cod_agrupadel' => 'Cod Agrupadel',
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

		$criteria->compare('cod_materia',$this->cod_materia);
		$criteria->compare('gls_materia',$this->gls_materia,true);
		$criteria->compare('nom_materia',$this->nom_materia,true);
		$criteria->compare('gls_vigencia',$this->gls_vigencia);
		$criteria->compare('clasif_hechodelictual',$this->clasif_hechodelictual);
		$criteria->compare('orden_priorizado',$this->orden_priorizado);
		$criteria->compare('cod_agrupadel',$this->cod_agrupadel);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_fr;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Delito the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
