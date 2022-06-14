<?php


class FuncionarioFiscalia extends CActiveRecord
{

	public $getFiscaliasAso; 


	public $fiscalia; 
	public $unidad; 

	public function tableName()
	{
		return 'funcionario_fiscalia';
	}


	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('funfis_ind_vigencia, uni_codigo, equipo_codigo', 'numerical', 'integerOnly'=>true),
			array('fun_rut', 'length', 'max'=>12),
			array('fis_codigo', 'length', 'max'=>3),
			array('funfis_fecha_ini, funfis_fecha_ter', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('funfis_codigo, funfis_fecha_ini, funfis_fecha_ter, funfis_ind_vigencia, fun_rut, fis_codigo, uni_codigo, equipo_codigo', 'safe', 'on'=>'search'),
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
			'funfis_codigo' => 'Funfis Codigo',
			'funfis_fecha_ini' => 'Funfis Fecha Ini',
			'funfis_fecha_ter' => 'Funfis Fecha Ter',
			'funfis_ind_vigencia' => 'Funfis Ind Vigencia',
			'fun_rut' => 'Fun Rut',
			'fis_codigo' => 'Fis Codigo',
			'uni_codigo' => 'Uni Codigo',
			'equipo_codigo' => 'Equipo Codigo',
		);
	}

	public function getFiscaliasAso(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, fis.fis_nombre fiscalia, uni.uni_descripcion unidad');

		$criteria->join ='INNER JOIN fr.fiscalia fis on fis.fis_codigo=t.fis_codigo
						  INNER JOIN fr.unidad uni on uni.uni_codigo=t.uni_codigo';

		$criteria->addCondition("t.funfis_ind_vigencia = 1 ");
		$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");	

	
		$this->getFiscaliasAso=FuncionarioFiscalia::model()->findAll($criteria);	
		return $this->getFiscaliasAso; 
	}


	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('funfis_codigo',$this->funfis_codigo);
		$criteria->compare('funfis_fecha_ini',$this->funfis_fecha_ini,true);
		$criteria->compare('funfis_fecha_ter',$this->funfis_fecha_ter,true);
		$criteria->compare('funfis_ind_vigencia',$this->funfis_ind_vigencia);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('uni_codigo',$this->uni_codigo);
		$criteria->compare('equipo_codigo',$this->equipo_codigo);

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
	 * @return FuncionarioFiscalia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
