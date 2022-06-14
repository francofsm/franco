<?php


class PerfilFuncionario extends CActiveRecord
{


	public $nombre;
	public $perfil;
	public $fiscalia; 

	public $getFuncionarioPerfil;


	
	public function tableName()
	{
		return 'perfil_funcionario';
	}



	public function rules()
	{
		return array(
			array('cod_perfil, fun_rut, ind_vigencia, fec_registro', 'required'),
			array('cod_perfil, ind_vigencia', 'numerical', 'integerOnly'=>true),
			array('fun_rut', 'length', 'max'=>12),
			array('cod_perfun, cod_perfil, fun_rut, ind_vigencia, fec_registro', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cod_perfun' => 'Cod Perfun',
			'cod_perfil' => 'Cod Perfil',
			'fun_rut' => 'Fun Rut',
			'ind_vigencia' => 'Ind Vigencia',
			'fec_registro' => 'Fec Registro',
		);
	}



	public function getFuncionarioPerfil(){

		$criteria = new CDbCriteria(array('order'=>'fun_ap_paterno ASC'));
		$criteria->select = array('t.*, CONCAT(FUN.fun_ap_paterno," ",FUN.fun_nombre," ",FUN.fun_nombre2) AS nombre, PER.gls_perfil perfil, FIS.fis_nombre fiscalia');
		$criteria->join ='INNER JOIN FR.FUNCIONARIO FUN ON FUN.fun_rut=t.fun_rut
		INNER JOIN FR.FUNCIONARIO_FISCALIA FF ON FF.fun_rut=t.fun_rut AND FF.funfis_ind_vigencia=1
		INNER JOIN FR.FISCALIA FIS ON FIS.fis_codigo=FF.fis_codigo
		INNER JOIN TG_PERFIL PER ON PER.cod_perfil=t.cod_perfil';
		$criteria->compare('ind_vigencia',1);
		$criteria->addCondition("t.cod_perfun= (select max(e.cod_perfun) from perfil_funcionario e where e.fun_rut=t.fun_rut)");
		$criteria->group = "t.fun_rut";

		$this->getFuncionarioPerfil=PerfilFuncionario::model()->findAll($criteria);	
		return $this->getFuncionarioPerfil; 
	}




	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_perfun',$this->cod_perfun);
		$criteria->compare('cod_perfil',$this->cod_perfil);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('ind_vigencia',$this->ind_vigencia);
		$criteria->compare('fec_registro',$this->fec_registro,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PerfilFuncionario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
