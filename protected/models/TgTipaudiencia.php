<?php

/**
 * This is the model class for table "tg_tipaudiencia".
 *
 * The followings are the available columns in table 'tg_tipaudiencia':
 * @property integer $cod_tipaudiencia
 * @property string $gls_tipaudiencia
 * @property integer $cod_audienciasaf
 * @property integer $ind_vigencia
 */
class TgTipaudiencia extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tg_tipaudiencia';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gls_tipaudiencia, cod_audienciasaf, ind_vigencia', 'required'),
			array('cod_audienciasaf, ind_vigencia', 'numerical', 'integerOnly'=>true),
			array('gls_tipaudiencia', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_tipaudiencia, gls_tipaudiencia, cod_audienciasaf, ind_vigencia', 'safe', 'on'=>'search'),
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
			'cod_tipaudiencia' => 'Cod Tipaudiencia',
			'gls_tipaudiencia' => 'Gls Tipaudiencia',
			'cod_audienciasaf' => 'Cod Audienciasaf',
			'ind_vigencia' => 'Ind Vigencia',
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

		$criteria->compare('cod_tipaudiencia',$this->cod_tipaudiencia);
		$criteria->compare('gls_tipaudiencia',$this->gls_tipaudiencia,true);
		$criteria->compare('cod_audienciasaf',$this->cod_audienciasaf);
		$criteria->compare('ind_vigencia',$this->ind_vigencia);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TgTipaudiencia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
