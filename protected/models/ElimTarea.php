<?php

/**
 * This is the model class for table "elim_tarea".
 *
 * The followings are the available columns in table 'elim_tarea':
 * @property integer $cod_elimtarea
 * @property integer $cod_bntarea
 * @property string $idf_rolunico
 * @property string $fis_codigo
 * @property integer $cod_instruccion
 * @property string $gls_comentario
 * @property string $fec_tarea
 * @property string $fec_asignacion
 * @property integer $cod_estinstruccion
 * @property string $fec_cambioest
 * @property string $fun_asignado
 * @property string $fun_rut
 * @property integer $ind_dias
 * @property integer $cod_prioridad
 * @property integer $cod_plantrabajo
 * @property string $fec_registro
 */
class ElimTarea extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'elim_tarea';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod_bntarea, fis_codigo, cod_instruccion, cod_estinstruccion, fun_rut, cod_prioridad, fec_registro', 'required'),
			array('cod_bntarea, cod_instruccion, cod_estinstruccion, ind_dias, cod_prioridad, cod_plantrabajo', 'numerical', 'integerOnly'=>true),
			array('idf_rolunico', 'length', 'max'=>15),
			array('fis_codigo', 'length', 'max'=>3),
			array('gls_comentario', 'length', 'max'=>999),
			array('fun_asignado, fun_rut, fun_responsable', 'length', 'max'=>12),
			array('fec_tarea, fec_asignacion, fec_cambioest', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_elimtarea, cod_bntarea, idf_rolunico, fis_codigo, cod_instruccion, gls_comentario, fec_tarea, fec_asignacion, cod_estinstruccion, fec_cambioest, fun_asignado, fun_rut, ind_dias, cod_prioridad, cod_plantrabajo, fec_registro, fec_eliminacion, fun_responsable', 'safe', 'on'=>'search'),
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
			'cod_elimtarea' => 'Cod Elimtarea',
			'cod_bntarea' => 'Cod Bntarea',
			'idf_rolunico' => 'Idf Rolunico',
			'fis_codigo' => 'Fis Codigo',
			'cod_instruccion' => 'Cod Instruccion',
			'gls_comentario' => 'Gls Comentario',
			'fec_tarea' => 'Fec Tarea',
			'fec_asignacion' => 'Fec Asignacion',
			'cod_estinstruccion' => 'Cod Estinstruccion',
			'fec_cambioest' => 'Fec Cambioest',
			'fun_asignado' => 'Fun Asignado',
			'fun_rut' => 'Fun Rut',
			'ind_dias' => 'Ind Dias',
			'cod_prioridad' => 'Cod Prioridad',
			'cod_plantrabajo' => 'Cod Plantrabajo',
			'fec_registro' => 'Fec Registro',
			'fec_eliminacion' => 'fec_eliminacion',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_elimtarea',$this->cod_elimtarea);
		$criteria->compare('cod_bntarea',$this->cod_bntarea);
		$criteria->compare('idf_rolunico',$this->idf_rolunico,true);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('cod_instruccion',$this->cod_instruccion);
		$criteria->compare('gls_comentario',$this->gls_comentario,true);
		$criteria->compare('fec_tarea',$this->fec_tarea,true);
		$criteria->compare('fec_asignacion',$this->fec_asignacion,true);
		$criteria->compare('cod_estinstruccion',$this->cod_estinstruccion);
		$criteria->compare('fec_cambioest',$this->fec_cambioest,true);
		$criteria->compare('fun_asignado',$this->fun_asignado,true);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('ind_dias',$this->ind_dias);
		$criteria->compare('cod_prioridad',$this->cod_prioridad);
		$criteria->compare('cod_plantrabajo',$this->cod_plantrabajo);
		$criteria->compare('fec_registro',$this->fec_registro,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ElimTarea the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
