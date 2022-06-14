<?php

class Funcionario extends CActiveRecord
{


	public $nombre; 
	public $unidad;
	public $cargo;
	public $fiscalia;

	public $getFuncionariosTodos;
	public $getFunFiscalia; 
	public $getFuncionariosEquipos;

	public $getGestor; 
	public $getAllFuncionarios; 

	public $getFuncionarioUnidad; 

	public $getFuncionariosReporte;

	public $getFuncionariosAdmin; 

	public $getFiscales; 

	public function tableName()
	{
		return 'funcionario';
	}



	public function rules()
	{
		return array(
			array('fun_rut, fun_nombre, fun_nombre2, fun_ap_paterno, fun_ap_materno, fun_login, fun_clave, fun_email, fun_grado', 'required'),
			array('fun_ind_vigente, crg_codigo, est_codigo', 'numerical', 'integerOnly'=>true),
			array('fun_rut', 'length', 'max'=>12),
			array('fun_nombre, fun_nombre2, fun_ap_paterno, fun_ap_materno', 'length', 'max'=>200),
			array('fun_login, fun_clave, fun_email', 'length', 'max'=>100),
			array('fun_grado', 'length', 'max'=>5),
			array('fun_foto', 'length', 'max'=>500),
			array('fun_sigla', 'length', 'max'=>4),
			array('fun_fecha_nac', 'safe'),
			array('fun_rut, fun_nombre, fun_nombre2, fun_ap_paterno, fun_ap_materno, fun_login, fun_clave, fun_email, fun_grado, fun_ind_vigente, fun_fecha_nac, fun_foto, crg_codigo, est_codigo, fun_sigla', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'fun_rut' => 'Fun Rut',
			'fun_nombre' => 'Fun Nombre',
			'fun_nombre2' => 'Fun Nombre2',
			'fun_ap_paterno' => 'Fun Ap Paterno',
			'fun_ap_materno' => 'Fun Ap Materno',
			'fun_login' => 'Fun Login',
			'fun_clave' => 'Fun Clave',
			'fun_email' => 'Fun Email',
			'fun_grado' => 'Fun Grado',
			'fun_ind_vigente' => 'Fun Ind Vigente',
			'fun_fecha_nac' => 'Fun Fecha Nac',
			'fun_foto' => 'Fun Foto',
			'crg_codigo' => 'Crg Codigo',
			'est_codigo' => 'Est Codigo',
			'fun_sigla' => 'Fun Sigla',
		);
	}


///////////////////////////////           REPORTES        ///////////////////////////////////////////////////

	public function getFiscales($fis,$fec_ini,$fec_fin){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, CONCAT(t.fun_nombre," ",t.fun_ap_paterno) AS nombre, F.fis_descripcion fiscalia');
		$criteria->join ='INNER JOIN FR.FUNCIONARIO_FISCALIA FIS ON FIS.fun_rut=t.fun_rut
						  INNER JOIN FR.FISCALIA F ON F.fis_codigo=FIS.fis_codigo
						  INNER JOIN bd_sia.banco_tarea bn on bn.fun_rut=t.fun_rut';

		$criteria->addCondition("FIS.fis_codigo = '".$fis."' ");
		$criteria->addCondition("t.crg_codigo in (27,28,30) ");
		$criteria->compare('FIS.funfis_ind_vigencia',1);

		$criteria->addCondition("bn.fec_registro >= '".$fec_ini."'");	
		$criteria->addCondition("bn.fec_registro <= '".$fec_fin."'");	

		$criteria->group = "t.fun_rut";
		$criteria->order = 't.fun_ap_paterno ASC';

		$this->getFiscales=Funcionario::model()->findAll($criteria);	
		return $this->getFiscales; 
	}


	public function getFuncionarioUnidad($uni){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, CONCAT(t.fun_nombre," ",t.fun_ap_paterno) AS nombre, F.fis_descripcion fiscalia');
		$criteria->join ='INNER JOIN FR.FUNCIONARIO_FISCALIA FIS ON FIS.fun_rut=t.fun_rut
						  INNER JOIN FR.FISCALIA F ON F.fis_codigo=FIS.fis_codigo';

		$criteria->addCondition("FIS.uni_codigo = '".$uni."' ");
		$criteria->addCondition("t.crg_codigo in (11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26) ");
		$criteria->compare('FIS.funfis_ind_vigencia',1);

		$criteria->order = 't.fun_ap_paterno ASC';

		$this->getFuncionarioUnidad=Funcionario::model()->findAll($criteria);	
		return $this->getFuncionarioUnidad; 
	}


	public function getFuncionariosReporte(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, CONCAT(t.fun_nombre," ",t.fun_ap_paterno) AS nombre, F.fis_descripcion fiscalia');
		$criteria->join ='INNER JOIN FR.FUNCIONARIO_FISCALIA FIS ON FIS.fun_rut=t.fun_rut
						  INNER JOIN FR.FISCALIA F ON F.fis_codigo=FIS.fis_codigo';
		
		$criteria->addCondition("t.crg_codigo in (11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26, 30) ");

		if(Yii::app()->user->getState('perfil')<>13 ){
			$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");	
			$criteria->compare('FIS.fis_codigo', Yii::app()->user->getState('fiscalia'));
		}
		$criteria->compare('FIS.funfis_ind_vigencia',1);
		$criteria->order = 't.fun_ap_paterno ASC';

		$this->getFuncionarioUnidad=Funcionario::model()->findAll($criteria);	
		return $this->getFuncionarioUnidad; 
	}


///////////////////////////////           REPORTES        ///////////////////////////////////////////////////


	public function getAllFuncionarios(){

		$criteria = new CDbCriteria(array('order'=>'fun_ap_paterno ASC'));
		$criteria->select = array('t.*, CONCAT(t.fun_ap_paterno," ",t.fun_nombre," ",t.fun_nombre2) AS nombre');
		$criteria->join ='INNER JOIN FR.FUNCIONARIO_FISCALIA FIS ON FIS.fun_rut=t.fun_rut
		INNER JOIN FR.FISCALIA FS ON FS.fis_codigo=FIS.fis_codigo';
		$criteria->compare('FS.cod_region', 8);
		$criteria->compare('FIS.funfis_ind_vigencia',1);

		$this->getAllFuncionarios=Funcionario::model()->findAll($criteria);	
		return $this->getAllFuncionarios; 
	}



	public function getGestor(){

		$criteria = new CDbCriteria(array('order'=>'fun_ap_paterno ASC'));
		$criteria->select = array('t.*, CONCAT(t.fun_ap_paterno," ",t.fun_nombre," ",t.fun_nombre2) AS nombre');
		$criteria->join ='INNER JOIN FR.FUNCIONARIO_FISCALIA FIS ON FIS.fun_rut=t.fun_rut';
		$criteria->compare('FIS.fis_codigo', Yii::app()->user->getState('fiscalia'));
		$criteria->compare('FIS.funfis_ind_vigencia',1);

		$this->getGestor=Funcionario::model()->findAll($criteria);	
		return $this->getGestor; 
	}




	public function getFunFiscalia($fis){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, CONCAT(t.fun_nombre," ",t.fun_ap_paterno) AS nombre, F.fis_descripcion fiscalia');
		$criteria->join ='INNER JOIN FR.FUNCIONARIO_FISCALIA FIS ON FIS.fun_rut=t.fun_rut
						  INNER JOIN FR.FISCALIA F ON F.fis_codigo=FIS.fis_codigo';
		$criteria->compare('FIS.fis_codigo', $fis);
		$criteria->compare('FIS.funfis_ind_vigencia',1);

		$criteria->order = 't.fun_ap_paterno ASC';

		$this->getFunFiscalia=Funcionario::model()->findAll($criteria);	
		return $this->getFunFiscalia; 
	}


	public function getFuncionariosEquipos(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, CONCAT(t.fun_nombre," ",t.fun_nombre2," ",t.fun_ap_paterno) AS nombre, uni.uni_descripcion unidad, car.crg_descripcion cargo, ff.fis_nombre fiscalia');
		$criteria->join ='INNER JOIN FR.FUNCIONARIO_FISCALIA FIS ON FIS.fun_rut=t.fun_rut
						  LEFT JOIN fr.unidad uni on uni.uni_codigo=FIS.uni_codigo
						  LEFT JOIN fr.fiscalia ff on ff.fis_codigo=FIS.fis_codigo
						  INNER JOIN fr.cargo car on car.crg_codigo=t.crg_codigo';
		if(Yii::app()->user->getState('perfil')<>13 ){
			$criteria->compare('FIS.fis_codigo', Yii::app()->user->getState('fiscalia'));
		}
		$criteria->compare('FIS.funfis_ind_vigencia',1);
		$criteria->addCondition("FF.fis_codigo in (8,801,802,803,805,806,807,810,813,814)");	
		$criteria->order = 't.fun_ap_paterno ASC';	

		$this->getFuncionariosEquipos=Funcionario::model()->findAll($criteria);	
		return $this->getFuncionariosEquipos; 
	}

	
	public function getFuncionariosTodos(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		$criteria->join ='INNER JOIN FR.FUNCIONARIO_FISCALIA FIS ON FIS.fun_rut=t.fun_rut';
		$criteria->compare('FIS.fis_codigo', Yii::app()->user->getState('fiscalia'));
		//$criteria->compare('FIS.uni_codigo',Yii::app()->user->getState('unidad'));
		$criteria->compare('FIS.funfis_ind_vigencia',1);
		$criteria->order = 't.fun_ap_paterno ASC';	

		$this->getFuncionariosTodos=Funcionario::model()->findAll($criteria);	
		return $this->getFuncionariosTodos; 
	}



	public function getFuncionariosAdmin(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		$criteria->join ='INNER JOIN FR.FUNCIONARIO_FISCALIA FIS ON FIS.fun_rut=t.fun_rut';

		if(Yii::app()->user->getState('perfil')<>13 ){
			$criteria->compare('FIS.fis_codigo', Yii::app()->user->getState('fiscalia'));
			//$criteria->compare('FIS.uni_codigo',Yii::app()->user->getState('unidad'));
			$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");	
		}

	
		$criteria->compare('FIS.funfis_ind_vigencia',1);
		$criteria->order = 't.fun_ap_paterno ASC';	

		$this->getFuncionariosAdmin=Funcionario::model()->findAll($criteria);	
		return $this->getFuncionariosAdmin; 
	}


//////////////////////   FISCALES CAUSAS  VIGENTES   - CARPETA DIGITAL /////////////

	public function getFiscalesVigentes($fis){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, CONCAT(t.fun_nombre," ",t.fun_ap_paterno) AS nombre, F.fis_descripcion fiscalia');
		$criteria->join ='INNER JOIN FR.FUNCIONARIO_FISCALIA FIS ON FIS.fun_rut=t.fun_rut
						  INNER JOIN FR.FISCALIA F ON F.fis_codigo=FIS.fis_codigo
						  INNER JOIN bd_sia.banco_tarea bn on bn.fun_rut=t.fun_rut';

		$criteria->addCondition("FIS.fis_codigo = '".$fis."' ");
		$criteria->addCondition("t.crg_codigo in (27,28,30) ");
		$criteria->compare('FIS.funfis_ind_vigencia',1);

		$criteria->group = "t.fun_rut";
		$criteria->order = 't.fun_ap_paterno ASC';

		$this->getFiscales=Funcionario::model()->findAll($criteria);	
		return $this->getFiscales; 
	}


//////  HASTA AQUI   /////



	public function search(){
		$criteria=new CDbCriteria;

		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('fun_nombre',$this->fun_nombre,true);
		$criteria->compare('fun_nombre2',$this->fun_nombre2,true);
		$criteria->compare('fun_ap_paterno',$this->fun_ap_paterno,true);
		$criteria->compare('fun_ap_materno',$this->fun_ap_materno,true);
		$criteria->compare('fun_login',$this->fun_login,true);
		$criteria->compare('fun_clave',$this->fun_clave,true);
		$criteria->compare('fun_email',$this->fun_email,true);
		$criteria->compare('fun_grado',$this->fun_grado,true);
		$criteria->compare('fun_ind_vigente',$this->fun_ind_vigente);
		$criteria->compare('fun_fecha_nac',$this->fun_fecha_nac,true);
		$criteria->compare('fun_foto',$this->fun_foto,true);
		$criteria->compare('crg_codigo',$this->crg_codigo);
		$criteria->compare('est_codigo',$this->est_codigo);
		$criteria->compare('fun_sigla',$this->fun_sigla,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_fr;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Funcionario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
