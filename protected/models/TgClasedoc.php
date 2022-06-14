<?php

class TgClasedoc extends CActiveRecord
{

	public $getDocAud;
	public $getDocMinuta;


	public function tableName()
	{
		return 'tg_clasedoc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gls_clasedoc, gls_clasecodigo, gls_aplica, ind_vigencia', 'required'),
			array('ind_vigencia', 'numerical', 'integerOnly'=>true),
			array('gls_clasedoc', 'length', 'max'=>999),
			array('gls_clasecodigo, gls_aplica', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ind_privacidad, cod_clasedoc, gls_clasedoc, gls_clasecodigo, gls_aplica, ind_vigencia, ind_orden', 'safe', 'on'=>'search'),
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
			'cod_clasedoc' => 'Cod Clasedoc',
			'gls_clasedoc' => 'Gls Clasedoc',
			'gls_clasecodigo' => 'Gls Clasecodigo',
			'gls_aplica' => 'Gls Aplica',
			'ind_vigencia' => 'Ind Vigencia',
			'ind_privacidad' => 'Ind ind_privacidad',
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

		$criteria->compare('cod_clasedoc',$this->cod_clasedoc);
		$criteria->compare('gls_clasedoc',$this->gls_clasedoc,true);
		$criteria->compare('gls_clasecodigo',$this->gls_clasecodigo,true);
		$criteria->compare('gls_aplica',$this->gls_aplica,true);
		$criteria->compare('ind_vigencia',$this->ind_vigencia);
		$criteria->compare('ind_orden',$this->ind_orden);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public function getDocAud(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		//$criteria->order = 't.cod_clasedoc ASC';
		//$criteria->compare('t.cod_clasedoc', 2);
		$criteria->addCondition("t.cod_clasedoc in (2,22,23)");
		$this->getDocAud=TgClasedoc::model()->findAll($criteria);	
		return $this->getDocAud; 
	}

	public function getDocMinuta(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		//$criteria->order = 't.cod_clasedoc ASC';
		//$criteria->compare('t.cod_clasedoc', 2);
		$criteria->addCondition("t.cod_clasedoc in (24)");
		$this->getDocMinuta=TgClasedoc::model()->findAll($criteria);	
		return $this->getDocMinuta; 
	}




	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TgClasedoc the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
