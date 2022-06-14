<?php

class TgSalaaud extends CActiveRecord
{

	public $getSalaAud;


	public function tableName()
	{
		return 'tg_salaaud';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod_salaaud, sala_aud, cod_fiscalia, ind_vigencia', 'required'),
			array('cod_salaaud, cod_fiscalia, ind_vigencia', 'numerical', 'integerOnly'=>true),
			array('sala_aud', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_salaaud, sala_aud, cod_fiscalia, ind_vigencia', 'safe', 'on'=>'search'),
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
			'cod_salaaud' => 'Cod Salaaud',
			'sala_aud' => 'Sala Aud',
			'cod_fiscalia' => 'Cod Fiscalia',
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

		$criteria->compare('cod_salaaud',$this->cod_salaaud);
		$criteria->compare('sala_aud',$this->sala_aud,true);
		$criteria->compare('cod_fiscalia',$this->cod_fiscalia);
		$criteria->compare('ind_vigencia',$this->ind_vigencia);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public function getSalaAud(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		$criteria->addCondition("t.cod_fiscalia = '".Yii::app()->user->getState('fiscalia')."' ");
		$this->getSalaAud=TgSalaaud::model()->findAll($criteria);	
		return $this->getSalaAud; 
	}



	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
