<?php


class CausaVigente extends CActiveRecord
{
	
	public $getListadoVigentes;
	public $getListadoVigAsignadas;
	public $fiscal;
	public $idcarpdig;

	public function tableName(){
		return 'causa_vigente';
	}

	public function rules(){

		return array(
			array('fis_codigo, cuenta_rut, idf_rolunico, riff, cod_materia, fec_recepcion, dias, gls_tipactividad, ind_formalizado, ind_pp, num_dias_trans, gls_estcaso, tip_actividad, subtip_actividad, subsubtip_actividad, ind_snactividad', 'required'),
			array('cod_materia, dias_tramitacion, dias, num_dias_trans, tip_actividad, subtip_actividad, subsubtip_actividad, ind_snactividad, ind_jud', 'numerical', 'integerOnly'=>true),
			array('fis_codigo', 'length', 'max'=>3),
			array('cuenta_rut', 'length', 'max'=>12),
			array('idf_rolunico', 'length', 'max'=>15),
			array('riff', 'length', 'max'=>100),
			array('gls_tipactividad, gls_estcaso', 'length', 'max'=>200),
			array('ind_formalizado, ind_pp', 'length', 'max'=>50),
			array('fec_ult_tramitacion, fec_hecho', 'safe'),

			array('cod_vigente, fis_codigo, cuenta_rut, idf_rolunico, riff, cod_materia, fec_recepcion, dias_tramitacion, fec_ult_tramitacion, dias, gls_tipactividad, ind_formalizado, ind_pp, fec_hecho, num_dias_trans, gls_estcaso, tip_actividad, subtip_actividad, subsubtip_actividad, ind_snactividad, ind_jud, fec_asig', 'safe', 'on'=>'search'),
		);
	}

	public function relations(){
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cod_vigente' => 'Cod Vigente',
			'fis_codigo' => 'Fis Codigo',
			'cuenta_rut' => 'Cuenta Rut',
			'idf_rolunico' => 'Idf Rolunico',
			'riff' => 'Riff',
			'cod_materia' => 'Cod Materia',
			'fec_recepcion' => 'Fec Recepcion',
			'dias_tramitacion' => 'Dias Tramitacion',
			'fec_ult_tramitacion' => 'Fec Ult Tramitacion',
			'dias' => 'Dias',
			'gls_tipactividad' => 'Gls Tipactividad',
			'ind_formalizado' => 'Ind Formalizado',
			'ind_pp' => 'Ind Pp',
			'fec_hecho' => 'Fec Hecho',
			'num_dias_trans' => 'Num Dias Trans',
			'gls_estcaso' => 'Gls Estcaso',
			'tip_actividad' => 'Tip Actividad',
			'subtip_actividad' => 'Subtip Actividad',
			'subsubtip_actividad' => 'Subsubtip Actividad',
			'ind_snactividad' => 'Ind Snactividad',
			'ind_jud' => 'Ind Jud',
			'fec_asig' => 'Fec Asig',
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




		public function getListadoVigentes($rutfiscal){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, concat(f2.cuenta_nombre, " ",f2.cuenta_apellido) fiscal');
		$criteria->join ='INNER JOIN fr.cuenta_generica f2 on f2.rut_saf=t.cuenta_rut';
		
		//$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		//$criteria->addCondition("t.cuenta_rut = '".Yii::app()->user->id."' ");
		$criteria->addCondition("t.cuenta_rut = '".$rutfiscal."' ");
		//$criteria->addCondition("t.fis_codigo = 803");
		//$criteria->addCondition("date(t.fec_registro)='".date('Y-m-d')."'");
		$criteria->group = "t.idf_rolunico";
		$criteria->order = 't.fec_asig DESC';
	
		$this->getListadoVigentes=CausaVigente::model()->findAll($criteria);	
		return $this->getListadoVigentes; 
	}

		public function getListadoVigAsignadas($rutfiscal){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, concat(f2.cuenta_nombre, " ",f2.cuenta_apellido) fiscal, cd.cod_carpdig idcarpdig');
		$criteria->join ='INNER JOIN fr.cuenta_generica f2 on f2.rut_saf=t.cuenta_rut
						  INNER JOIN carpeta_digital cd on cd.idf_rolunico = t.idf_rolunico';
		$criteria->addCondition("t.cuenta_rut = '".$rutfiscal."' ");
		$criteria->addCondition("cd.ind_revfiscal = 0 ");
		$criteria->addCondition("cd.cod_clasedoc in (10,15,38,39,41,49)");
		
		$criteria->group = "t.idf_rolunico";
		$criteria->order = 't.fec_asig DESC';
	
		$this->getListadoVigAsignadas=CausaVigente::model()->findAll($criteria);	
		return $this->getListadoVigAsignadas; 
		}






	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cod_vigente',$this->cod_vigente);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('cuenta_rut',$this->cuenta_rut,true);
		$criteria->compare('idf_rolunico',$this->idf_rolunico,true);
		$criteria->compare('riff',$this->riff,true);
		$criteria->compare('cod_materia',$this->cod_materia);
		$criteria->compare('fec_recepcion',$this->fec_recepcion,true);
		$criteria->compare('dias_tramitacion',$this->dias_tramitacion);
		$criteria->compare('fec_ult_tramitacion',$this->fec_ult_tramitacion,true);
		$criteria->compare('dias',$this->dias);
		$criteria->compare('gls_tipactividad',$this->gls_tipactividad,true);
		$criteria->compare('ind_formalizado',$this->ind_formalizado,true);
		$criteria->compare('ind_pp',$this->ind_pp,true);
		$criteria->compare('fec_hecho',$this->fec_hecho,true);
		$criteria->compare('num_dias_trans',$this->num_dias_trans);
		$criteria->compare('gls_estcaso',$this->gls_estcaso,true);
		$criteria->compare('tip_actividad',$this->tip_actividad);
		$criteria->compare('subtip_actividad',$this->subtip_actividad);
		$criteria->compare('subsubtip_actividad',$this->subsubtip_actividad);
		$criteria->compare('ind_snactividad',$this->ind_snactividad);
		$criteria->compare('ind_jud',$this->ind_jud);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CausaVigente the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
