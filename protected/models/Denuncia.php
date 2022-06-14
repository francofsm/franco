<?php


class Denuncia extends CActiveRecord
{

	public $getParteRecepcionado; 
	public $getRecepcionarParte; 
	public $getParteDerivado; 
	public $getListParteRecepcionado; 

	public $origen; 
	public $tipo;
	public $destaca; 
	public $comisaria;
	public $instruccion;
	public $funcionario;
	public $estado; 
	public $responsable; 

	public function tableName()
	{
		return 'denuncia';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod_tipdenuncia, fec_ingreso, cod_origencaso, cod_estcarpeta, fec_cambioest, ind_recepcion, fun_rut, fec_registro', 'required'),
			array('cod_tipdenuncia, cod_origencaso, cod_destaca, id_comisaria, cod_estcarpeta, ind_pendiente, ind_control, ind_recepcion', 'numerical', 'integerOnly'=>true),
			array('fis_codigo', 'length', 'max'=>3),
			array('num_denuncia', 'length', 'max'=>25),
			array('gls_procedencia, funcionario_entrega', 'length', 'max'=>500),
			array('obs_denuncia', 'length', 'max'=>900),
			array('fun_asignado, fun_rut', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_denuncia, fis_codigo, cod_tipdenuncia, num_denuncia, fec_ingreso, cod_origencaso, cod_destaca, id_comisaria, gls_procedencia, funcionario_entrega, obs_denuncia, cod_estcarpeta, fec_cambioest, fun_asignado, fec_asignacion, ind_pendiente, ind_control, ind_recepcion, fun_rut, fec_registro', 'safe', 'on'=>'search'),
		);
	}


	public function relations(){
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'cod_denuncia' => 'Cod Denuncia',
			'fis_codigo' => 'Fis Codigo',
			'cod_tipdenuncia' => 'Cod Tipdenuncia',
			'num_denuncia' => 'Num Denuncia',
			'fec_ingreso' => 'Fec Ingreso',
			'cod_origencaso' => 'Cod Origencaso',
			'cod_destaca' => 'Cod Destaca',
			'id_comisaria' => 'Id Comisaria',
			'gls_procedencia' => 'Gls Procedencia',
			'funcionario_entrega' => 'Funcionario Entrega',
			'obs_denuncia' => 'Obs Denuncia',
			'cod_estcarpeta' => 'Cod Estcarpeta',
			'fec_cambioest' => 'Fec Cambioest',
			'fun_asignado' => 'Fun Asignado',
			'fec_asignacion' => 'Fec Asignacion',
			'ind_pendiente' => 'Ind Pendiente',
			'ind_control' => 'Ind Control',
			'ind_recepcion' => 'Ind Recepcion',
			'fun_rut' => 'Fun Rut',
			'fec_registro' => 'Fec Registro',
		);
	}


	public function getListParteRecepcionado($fec, $fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tip.gls_tipdenuncia tipo, ori.gls_origencaso origen, des.gls_destacamento destaca, comi.gls_comisaria comisaria, concat(fun.fun_nombre, " ", fun.fun_ap_paterno) funcionario, est.gls_estcarpeta estado, concat(fun2.fun_nombre, " ", fun2.fun_ap_paterno) responsable');	
		$criteria->join ='INNER JOIN tg_tipdenuncia tip on tip.cod_tipdenuncia=t.cod_tipdenuncia
						  INNER JOIN tg_origencaso ori on ori.cod_origencaso=t.cod_origencaso
						  LEFT JOIN fr.tg_destacamento des on des.cod_destaca=t.cod_destaca
						  LEFT JOIN fr.comisaria comi on comi.id_comisaria=t.id_comisaria
						  LEFT JOIN fr.funcionario fun on fun.fun_rut=t.fun_asignado
						  LEFT JOIN estado_denuncia estado on estado.cod_denuncia=t.cod_denuncia and estado.cod_estcarpeta=2
						  LEFT JOIN fr.funcionario fun2 on fun2.fun_rut=estado.fun_responsable
						  LEFT JOIN tg_estadocarpeta est on est.cod_estcarpeta=t.cod_estcarpeta';

		$criteria->addCondition('t.fis_codigo='.Yii::app()->user->getState('fiscalia'));
		$criteria->addCondition("date(t.fec_ingreso) >= '".$fec."'");	
		$criteria->addCondition("date(t.fec_ingreso) <= '".$fec_fin."'");	
	
		$criteria->order = 't.cod_denuncia DESC';	

		$this->getListParteRecepcionado=Denuncia::model()->findAll($criteria);	
		return $this->getListParteRecepcionado; 
	}



	public function getParteDerivado($fec, $fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tip.gls_tipdenuncia tipo, ori.gls_origencaso origen, des.gls_destacamento destaca, comi.gls_comisaria comisaria, concat(fun.fun_nombre, " ", fun.fun_ap_paterno) funcionario');	
		$criteria->join ='INNER JOIN tg_tipdenuncia tip on tip.cod_tipdenuncia=t.cod_tipdenuncia
						  INNER JOIN tg_origencaso ori on ori.cod_origencaso=t.cod_origencaso
						  LEFT JOIN fr.tg_destacamento des on des.cod_destaca=t.cod_destaca
						  LEFT JOIN fr.comisaria comi on comi.id_comisaria=t.id_comisaria
						  LEFT JOIN fr.funcionario fun on fun.fun_rut=t.fun_asignado';

		$criteria->addCondition('t.fis_codigo='.Yii::app()->user->getState('fiscalia'));
		$criteria->addCondition('t.cod_estcarpeta=2');
		$criteria->addCondition("date(t.fec_ingreso) >= '".$fec."'");	
		$criteria->addCondition("date(t.fec_ingreso) <= '".$fec_fin."'");	

		$criteria->order = 't.cod_denuncia DESC';	

		$this->getParteDerivado=Denuncia::model()->findAll($criteria);	
		return $this->getParteDerivado; 
	}


	public function getParteRecepcionado(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tip.gls_tipdenuncia tipo, ori.gls_origencaso origen, des.gls_destacamento destaca, comi.gls_comisaria comisaria, concat(f.fun_nombre, " ", f.fun_ap_paterno) as responsable');	
		$criteria->join ='INNER JOIN tg_tipdenuncia tip on tip.cod_tipdenuncia=t.cod_tipdenuncia
						  INNER JOIN tg_origencaso ori on ori.cod_origencaso=t.cod_origencaso
						  LEFT JOIN fr.tg_destacamento des on des.cod_destaca=t.cod_destaca
						  LEFT JOIN fr.comisaria comi on comi.id_comisaria=t.id_comisaria
						  LEFT JOIN fr.funcionario f on f.fun_rut=t.fun_rut';
		$criteria->addCondition('t.fis_codigo='.Yii::app()->user->getState('fiscalia'));
		$criteria->addCondition('t.cod_estcarpeta in (1,4)');

		$criteria->order = 't.cod_denuncia ASC, t.num_denuncia ASC, date(t.fec_ingreso) ASC';	

		$this->getParteRecepcionado=Denuncia::model()->findAll($criteria);	
		return $this->getParteRecepcionado; 
	}


	public function getRecepcionarParte(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, tip.gls_tipdenuncia tipo, ori.gls_origencaso origen, des.gls_destacamento destaca, comi.gls_comisaria comisaria, ti.gls_instruccion instruccion');	
		$criteria->join ='INNER JOIN tg_tipdenuncia tip on tip.cod_tipdenuncia=t.cod_tipdenuncia
						  INNER JOIN tg_origencaso ori on ori.cod_origencaso=t.cod_origencaso
						  LEFT JOIN fr.tg_destacamento des on des.cod_destaca=t.cod_destaca
						  LEFT JOIN fr.comisaria comi on comi.id_comisaria=t.id_comisaria
						  LEFT JOIN tg_instruccion ti on ti.cod_instruccion=t.cod_instruccion'; 

		$criteria->addCondition('t.fis_codigo='.Yii::app()->user->getState('fiscalia'));
		$criteria->addCondition("t.fun_asignado = '".Yii::app()->user->id."' ");
		$criteria->addCondition('t.cod_estcarpeta=2');
		$criteria->order = 't.cod_denuncia DESC';	

		$this->getRecepcionarParte=Denuncia::model()->findAll($criteria);	
		return $this->getRecepcionarParte; 
	}





	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_denuncia',$this->cod_denuncia);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('cod_tipdenuncia',$this->cod_tipdenuncia);
		$criteria->compare('num_denuncia',$this->num_denuncia,true);
		$criteria->compare('fec_ingreso',$this->fec_ingreso,true);
		$criteria->compare('cod_origencaso',$this->cod_origencaso);
		$criteria->compare('cod_destaca',$this->cod_destaca);
		$criteria->compare('id_comisaria',$this->id_comisaria);
		$criteria->compare('gls_procedencia',$this->gls_procedencia,true);
		$criteria->compare('funcionario_entrega',$this->funcionario_entrega,true);
		$criteria->compare('obs_denuncia',$this->obs_denuncia,true);
		$criteria->compare('cod_estcarpeta',$this->cod_estcarpeta);
		$criteria->compare('fec_cambioest',$this->fec_cambioest,true);
		$criteria->compare('fun_asignado',$this->fun_asignado,true);
		$criteria->compare('fec_asignacion',$this->fec_asignacion,true);
		$criteria->compare('ind_pendiente',$this->ind_pendiente);
		$criteria->compare('ind_control',$this->ind_control);
		$criteria->compare('ind_recepcion',$this->ind_recepcion);
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
	 * @return Denuncia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
