<?php

/**
 * This is the model class for table "tmp_ejecutar".
 *
 * The followings are the available columns in table 'tmp_ejecutar':
 * @property integer $cod_tmpejecutar
 * @property integer $cod_bntarea
 * @property string $fun_rut
 * @property string $fec_registro
 */
class TmpEjecutar extends CActiveRecord
{

	public $getListTmpEjecutar; 
	public $getExisteCodigo;

	public $tarea;
	public $ruc;
	public $fec_decreta;
	public $comen;
	public $prioridad;
	public $fun;
	public $estado; 
	public $priori; 
	public $cod_prioridad; 
	public $tiempo;


	public function tableName()
	{
		return 'tmp_ejecutar';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod_bntarea, fun_rut, fec_registro', 'required'),
			array('cod_bntarea', 'numerical', 'integerOnly'=>true),
			array('fun_rut', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_tmpejecutar, cod_bntarea, fun_rut, fec_registro', 'safe', 'on'=>'search'),
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
			'cod_tmpejecutar' => 'Cod Tmpejecutar',
			'cod_bntarea' => 'Cod Bntarea',
			'fun_rut' => 'Fun Rut',
			'fec_registro' => 'Fec Registro',
		);
	}


	public function getListTmpEjecutar(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, bn.cod_prioridad prioridad, bn.idf_rolunico ruc, bn.fec_registro fec_decreta, bn.gls_comentario comen, concat(f.fun_nombre," ",f.fun_ap_paterno) fun, ti.gls_instruccion tarea, te.gls_estinstruccion estado, tp.gls_prioridad priori, bn.cod_prioridad cod_prioridad, ti.tiempo_instruccion tiempo');
		$criteria->join ='INNER JOIN banco_tarea bn on bn.cod_bntarea=t.cod_bntarea
						  INNER JOIN tg_instruccion ti on ti.cod_instruccion=bn.cod_instruccion
						  INNER JOIN tg_estinstruccion te on te.cod_estinstruccion=bn.cod_estinstruccion
						  INNER JOIN tg_prioridad tp on tp.cod_prioridad=bn.cod_prioridad
						  LEFT JOIN fr.funcionario f on f.fun_rut=bn.fun_asignado';	
		$criteria->addCondition("bn.cod_estinstruccion in (1,2,3,4,6)");
		$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");	
			
		$criteria->order = 't.cod_tmpejecutar DESC';
	
		$this->getListTmpEjecutar=TmpEjecutar::model()->findAll($criteria);	
		return $this->getListTmpEjecutar; 
	}


	public function getExisteCodigo($id){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');	
		$criteria->compare('t.cod_bntarea',$id);		
		$this->getExisteCodigo=TmpEjecutar::model()->findAll($criteria);	
		return $this->getExisteCodigo; 
	}


	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_tmpejecutar',$this->cod_tmpejecutar);
		$criteria->compare('cod_bntarea',$this->cod_bntarea);
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
	 * @return TmpEjecutar the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
