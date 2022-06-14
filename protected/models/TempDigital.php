<?php


class TempDigital extends CActiveRecord
{

	public $getDocumentosTemporales; 

	public function tableName()
	{
		return 'temp_digital';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idf_rolunico, fis_codigo, fec_actividad, cod_clasedoc, cod_estadoparte, gls_nomdoc, gls_ruta, fun_rut, fec_registro', 'required'),
			array('crr_idactividad, cod_clasedoc, cod_estadoparte', 'numerical', 'integerOnly'=>true),
			array('idf_rolunico', 'length', 'max'=>15),
			array('fis_codigo', 'length', 'max'=>3),
			array('gls_nomdoc, gls_ruta', 'length', 'max'=>999),
			array('fun_rut', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_tempdig, idf_rolunico, fis_codigo, fec_actividad, crr_idactividad, tip_actividad, tip_subtipactividad, tip_subsubtipactividad, cod_clasedoc, cod_estadoparte, gls_nomdoc, gls_ruta, fun_rut, fec_registro', 'safe', 'on'=>'search'),
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
			'cod_tempdig' => 'Cod Tempdig',
			'idf_rolunico' => 'Idf Rolunico',
			'fis_codigo' => 'Fis Codigo',
			'fec_actividad' => 'Fec Actividad',
			'crr_idactividad' => 'Crr Idactividad',
			'cod_clasedoc' => 'Cod Clasedoc',
			'cod_estadoparte' => 'Cod Estadoparte',
			'gls_nomdoc' => 'Gls Nomdoc',
			'gls_ruta' => 'Gls Ruta',
			'fun_rut' => 'Fun Rut',
			'fec_registro' => 'Fec Registro',
		);
	}



	public function getDocumentosTemporales($ruc){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');

		$criteria->addCondition("t.idf_rolunico = '".$ruc."' ");	
		
		$this->getDocumentosTemporales=TempDigital::model()->findAll($criteria);	
		return $this->getDocumentosTemporales; 
	}


	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_tempdig',$this->cod_tempdig);
		$criteria->compare('idf_rolunico',$this->idf_rolunico,true);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('fec_actividad',$this->fec_actividad,true);
		$criteria->compare('crr_idactividad',$this->crr_idactividad);
		$criteria->compare('tip_actividad',$this->tip_actividad);
		$criteria->compare('tip_subtipactividad',$this->tip_subtipactividad);
		$criteria->compare('tip_subsubtipactividad',$this->tip_subsubtipactividad);
		$criteria->compare('cod_clasedoc',$this->cod_clasedoc);
		$criteria->compare('cod_estadoparte',$this->cod_estadoparte);
		$criteria->compare('gls_nomdoc',$this->gls_nomdoc,true);
		$criteria->compare('gls_ruta',$this->gls_ruta,true);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('fec_registro',$this->fec_registro,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TempDigital the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
