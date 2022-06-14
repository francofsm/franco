<?php

class TgActividadsaf extends CActiveRecord
{

	public $getDocumentosActividadRuc;


	public function tableName()
	{
		return 'tg_actividadsaf';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gls_actividad, gls_subtipactividad, gls_subsubtipactividad', 'required'),
			array('tip_actividad, tip_subtipactividad, tip_subsubtipactividad, cod_judicial', 'numerical', 'integerOnly'=>true),
			array('gls_actividad', 'length', 'max'=>50),
			array('gls_subtipactividad', 'length', 'max'=>80),
			array('gls_subsubtipactividad', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_actsaf, tip_actividad, tip_subtipactividad, tip_subsubtipactividad, gls_actividad, gls_subtipactividad, gls_subsubtipactividad, cod_judicial', 'safe', 'on'=>'search'),
		);
	}

	
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
			'cod_actsaf' => 'Cod Actsaf',
			'tip_actividad' => 'Tip Actividad',
			'tip_subtipactividad' => 'Tip Subtipactividad',
			'tip_subsubtipactividad' => 'Tip Subsubtipactividad',
			'gls_actividad' => 'Gls Actividad',
			'gls_subtipactividad' => 'Gls Subtipactividad',
			'gls_subsubtipactividad' => 'Gls Subsubtipactividad',
			'cod_judicial' => 'Cod Judicial',
		);
	}

	

	/*****    ASOCIA LAS ACTIVIDADES A LOS DOCUMENTOS DE SAF  ****/
	public function getDocumentosActividadRuc($act,$sact,$ssact){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		$criteria->addCondition("t.tip_actividad = '".$act."' ");	
		$criteria->addCondition("t.tip_subtipactividad = '".$sact."' ");	
		$criteria->addCondition("t.tip_subsubtipactividad = '".$ssact."' ");
		$this->getDocumentosActividadRuc=TgActividadsaf::model()->findAll($criteria);	
		return $this->getDocumentosActividadRuc; 
	}




	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_actsaf',$this->cod_actsaf);
		$criteria->compare('tip_actividad',$this->tip_actividad);
		$criteria->compare('tip_subtipactividad',$this->tip_subtipactividad);
		$criteria->compare('tip_subsubtipactividad',$this->tip_subsubtipactividad);
		$criteria->compare('gls_actividad',$this->gls_actividad,true);
		$criteria->compare('gls_subtipactividad',$this->gls_subtipactividad,true);
		$criteria->compare('gls_subsubtipactividad',$this->gls_subsubtipactividad,true);
		$criteria->compare('cod_judicial',$this->cod_judicial);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TgActividadsaf the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
