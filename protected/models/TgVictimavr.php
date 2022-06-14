<?php


class TgVictimavr extends CActiveRecord
{

	public $getVictimasvr;


	public function tableName()
	{
		return 'tg_victimavr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sigla_vicvr, victimavr', 'required'),
			array('sigla_vicvr', 'length', 'max'=>15),
			array('victimavr', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_victimavr, sigla_vicvr, victimavr', 'safe', 'on'=>'search'),
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
			'cod_victimavr' => 'Cod Victimavr',
			'sigla_vicvr' => 'Sigla Vicvr',
			'victimavr' => 'Victimavr',
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

		$criteria->compare('cod_victimavr',$this->cod_victimavr);
		$criteria->compare('sigla_vicvr',$this->sigla_vicvr,true);
		$criteria->compare('victimavr',$this->victimavr,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}



	public function getVictimasvr(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		//$criteria->join ='INNER JOIN bd_sia.banco_tarea bn on bn.cuenta_rut=t.cuenta_rut';
		//$criteria->addCondition("t.fis_codigo = '".$fis."' ");
		//$criteria->compare('t.ind_vig',1);
		//$criteria->group = "t.cuenta_rut";
		//$criteria->order = 't.cuenta_nombre ASC';

		$this->getVictimasvr=TgVictimavr::model()->findAll($criteria);	
		return $this->getVictimasvr; 
	}






	public function getDbConnection()
	{
		return Yii::app()->db_vr;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TgVictimavr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
