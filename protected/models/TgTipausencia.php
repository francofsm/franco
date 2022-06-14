<?php


class TgTipausencia extends CActiveRecord
{


	public $getMinutosAusencia; 
	
	public function tableName()
	{
		return 'tg_tipausencia';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gls_tipausencia, tiempo_ausencia, ind_vigencia', 'required'),
			array('tiempo_ausencia, ind_vigencia', 'numerical', 'integerOnly'=>true),
			array('gls_tipausencia', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_tipausencia, gls_tipausencia, tiempo_ausencia, ind_vigencia', 'safe', 'on'=>'search'),
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
			'cod_tipausencia' => 'Cod Tipausencia',
			'gls_tipausencia' => 'Gls Tipausencia',
			'tiempo_ausencia' => 'Tiempo Ausencia',
			'ind_vigencia' => 'Ind Vigencia',
		);
	}


	public function getMinutosAusencia($id){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		$criteria->compare('cod_tipausencia',$id);
		$this->getMinutosAusencia=TgTipausencia::model()->findAll($criteria);	
		return $this->getMinutosAusencia; 
	}


	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_tipausencia',$this->cod_tipausencia);
		$criteria->compare('gls_tipausencia',$this->gls_tipausencia,true);
		$criteria->compare('tiempo_ausencia',$this->tiempo_ausencia);
		$criteria->compare('ind_vigencia',$this->ind_vigencia);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TgTipausencia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
