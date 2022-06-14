<?php


class Ausencia extends CActiveRecord
{

	public $tiempo; 
	public $total; 
	public $glsausencia; 
	public $funcionario;
	public $fisca;

	public $getAusencias;
	public $getFuncionarioAusencias; 

	public function tableName()
	{
		return 'ausencia';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod_tipausencia, fun_rut, fec_ausencia, fec_registro', 'required'),
			array('cod_tipausencia', 'numerical', 'integerOnly'=>true),
			array('fun_rut', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('total, tiempo, cod_ausencia, cod_tipausencia, fun_rut, fec_ausencia, fec_registro', 'safe', 'on'=>'search'),
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
			'cod_ausencia' => 'Cod Ausencia',
			'cod_tipausencia' => 'Cod Tipausencia',
			'fun_rut' => 'Fun Rut',
			'fec_ausencia' => 'Fec Ausencia',
			'fec_registro' => 'Fec Registro',
			'Observaciones'=> 'Observaciones',
		);
	}


	public function getAusencias($gestor,$fec){

		$criteria = new CDbCriteria();
		$criteria->select = array('sum(b.tiempo_ausencia) as total');
		$criteria->join ='inner join tg_tipausencia b on b.cod_tipausencia=t.cod_tipausencia and b.ind_vigencia=1';			
		$criteria->compare('t.fun_rut',$gestor);
		$criteria->compare('t.fec_ausencia',$fec);

		$this->getAusencias=Ausencia::model()->findAll($criteria);	
		return $this->getAusencias; 
	}

	public function getFuncionarioAusencias(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, b.gls_tipausencia glsausencia, CONCAT(f.fun_nombre," ",f.fun_ap_paterno) AS funcionario');
		$criteria->join ='inner join tg_tipausencia b on b.cod_tipausencia=t.cod_tipausencia and b.ind_vigencia=1
		inner join fr.funcionario f on f.fun_rut=t.fun_rut
		inner join fr.funcionario_fiscalia c on c.fun_rut=f.fun_rut';	
		$criteria->compare('c.fis_codigo',Yii::app()->user->getState('fiscalia'));	
		$criteria->group = "t.cod_ausencia";		

		$this->getFuncionarioAusencias=Ausencia::model()->findAll($criteria);	
		return $this->getFuncionarioAusencias; 
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_ausencia',$this->cod_ausencia);
		$criteria->compare('cod_tipausencia',$this->cod_tipausencia);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('fec_ausencia',$this->fec_ausencia,true);
		$criteria->compare('fec_registro',$this->fec_registro,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ausencia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
