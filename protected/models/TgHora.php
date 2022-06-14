<?php


class TgHora extends CActiveRecord
{
	
 public $getHora;



	public function tableName()
	{
		return 'tg_hora';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hora', 'required'),
			array('hora', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_hora, hora', 'safe', 'on'=>'search'),
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
			'cod_hora' => 'Cod Hora',
			'hora' => 'Hora',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_hora',$this->cod_hora);
		$criteria->compare('hora',$this->hora,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}




	public function getHora(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		$criteria->order = 't.cod_hora ASC';

		$this->getHora=TgHora::model()->findAll($criteria);	
		return $this->getHora; 
	}

	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
