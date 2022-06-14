<?php

class PermisoReserva extends CActiveRecord
{

	public $getReservaCausa;
	public $getCausasReservadas; 

	public $total; 
	public $nombre; 

	public function tableName()
	{
		return 'permiso_reserva';
	}

	public function rules()
	{
		return array(
			array('idf_rolunico, fis_codigo, fun_rut, fun_responsable, fec_registro, ind_vigencia', 'required'),
			array('ind_vigencia', 'numerical', 'integerOnly'=>true),
			array('idf_rolunico', 'length', 'max'=>15),
			array('fis_codigo', 'length', 'max'=>3),
			array('fun_rut, fun_responsable', 'length', 'max'=>12),
			array('cod_permisoreserva, idf_rolunico, fis_codigo, fun_rut, fun_responsable, fec_registro, ind_vigencia', 'safe', 'on'=>'search'),
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
			'cod_permisoreserva' => 'Cod Permisoreserva',
			'idf_rolunico' => 'Idf Rolunico',
			'fis_codigo' => 'Fis Codigo',
			'fun_rut' => 'Fun Rut',
			'fun_responsable' => 'Fun Responsable',
			'fec_registro' => 'Fec Registro',
			'ind_vigencia' => 'Ind Vigencia',
		);
	}

	public function getReservaCausa($ruc){
		$criteria = new CDbCriteria();
		$criteria->select = array('count(t.cod_permisoreserva) total');
		$criteria->addCondition("t.idf_rolunico = '".$ruc."' ");
		$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");	
		$criteria->addCondition("t.ind_vigencia = 1 ");			

		$this->getReservaCausa=PermisoReserva::model()->findAll($criteria);	
		return $this->getReservaCausa; 
	}

	public function getCausasReservadas(){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, concat(f.fun_nombre, " ", f.fun_nombre2, " ",fun_ap_paterno) as nombre');
		$criteria->join ='INNER JOIN fr.funcionario f on f.fun_rut=t.fun_rut';
		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");	
		$criteria->addCondition("t.ind_vigencia = 1 ");		

		$this->getCausasReservadas=PermisoReserva::model()->findAll($criteria);	
		return $this->getCausasReservadas;
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('cod_permisoreserva',$this->cod_permisoreserva);
		$criteria->compare('idf_rolunico',$this->idf_rolunico,true);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('fun_responsable',$this->fun_responsable,true);
		$criteria->compare('fec_registro',$this->fec_registro,true);
		$criteria->compare('ind_vigencia',$this->ind_vigencia);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
