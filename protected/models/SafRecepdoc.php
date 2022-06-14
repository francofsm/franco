<?php

class SafRecepdoc extends CActiveRecord
{

	public $getListadoDoc; 

	public $fiscal; 

	public function tableName()
	{
		return 'saf_recepdoc';
	}



	public function rules()
	{
		return array(
			array('idf_rolunico, fis_codigo, fis_usuario, fun_rut, crr_sujeto, sujeto, crr_relacion, idf_rutfiscal, fec_actividad, fec_registro', 'required'),
			array('crr_sujeto, crr_relacion, estado, encarpetado, impreso', 'numerical', 'integerOnly'=>true),
			array('idf_rolunico', 'length', 'max'=>15),
			array('fis_codigo, fis_usuario', 'length', 'max'=>3),
			array('fun_rut, idf_rutfiscal, usuario_enc, estado_caso, usuario_imp', 'length', 'max'=>12),
			array('sujeto', 'length', 'max'=>150),
			array('gls_comentario', 'length', 'max'=>500),
			array('fecha_enc, fecha_imp', 'safe'),
			array('gls_rutatemp, cod_safrecepdoc, idf_rolunico, fis_codigo, fis_usuario, fun_rut, crr_sujeto, sujeto, crr_relacion, idf_rutfiscal, fec_actividad, gls_comentario, fec_registro, estado, encarpetado, usuario_enc, fecha_enc, estado_caso, impreso, usuario_imp, fecha_imp', 'safe', 'on'=>'search'),
		);
	}


	public function relations()
	{
		return array(
		);
	}


	public function attributeLabels()
	{
		return array(
			'cod_safrecepdoc' => 'Cod Safrecepdoc',
			'idf_rolunico' => 'Idf Rolunico',
			'fis_codigo' => 'Fis Codigo',
			'fis_usuario' => 'Fis Usuario',
			'fun_rut' => 'Fun Rut',
			'crr_sujeto' => 'Crr Sujeto',
			'sujeto' => 'Sujeto',
			'crr_relacion' => 'Crr Relacion',
			'idf_rutfiscal' => 'Idf Rutfiscal',
			'fec_actividad' => 'Fec Actividad',
			'gls_comentario' => 'Gls Comentario',
			'fec_registro' => 'Fec Registro',
			'estado' => 'Estado',
			'encarpetado' => 'Encarpetado',
			'usuario_enc' => 'Usuario Enc',
			'fecha_enc' => 'Fecha Enc',
			'estado_caso' => 'Estado Caso',
			'impreso' => 'Impreso',
			'usuario_imp' => 'Usuario Imp',
			'fecha_imp' => 'Fecha Imp',
			'gls_rutatemp' => 'gls_rutatemp', 
		);
	}


	public function getListadoDoc(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, CONCAT(f.cuenta_nombre," ",f.cuenta_apellido) AS fiscal'); //
		$criteria->join ='LEFT JOIN fr.cuenta_generica f on f.cuenta_rut=t.idf_rutfiscal';

		$criteria->compare('t.fis_usuario',Yii::app()->user->getState('fiscalia'));
		$criteria->compare('t.estado',0);
		$criteria->order = 't.fec_registro DESC';

		$this->getListadoDoc=SafRecepDoc::model()->findAll($criteria);	
		return $this->getListadoDoc; 
	}


	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_safrecepdoc',$this->cod_safrecepdoc);
		$criteria->compare('idf_rolunico',$this->idf_rolunico,true);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('fis_usuario',$this->fis_usuario,true);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('crr_sujeto',$this->crr_sujeto);
		$criteria->compare('sujeto',$this->sujeto,true);
		$criteria->compare('crr_relacion',$this->crr_relacion);
		$criteria->compare('idf_rutfiscal',$this->idf_rutfiscal,true);
		$criteria->compare('fec_actividad',$this->fec_actividad,true);
		$criteria->compare('gls_comentario',$this->gls_comentario,true);
		$criteria->compare('fec_registro',$this->fec_registro,true);
		$criteria->compare('estado',$this->estado);
		$criteria->compare('encarpetado',$this->encarpetado);
		$criteria->compare('usuario_enc',$this->usuario_enc,true);
		$criteria->compare('fecha_enc',$this->fecha_enc,true);
		$criteria->compare('estado_caso',$this->estado_caso,true);
		$criteria->compare('impreso',$this->impreso);
		$criteria->compare('usuario_imp',$this->usuario_imp,true);
		$criteria->compare('fecha_imp',$this->fecha_imp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SafRecepdoc the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
