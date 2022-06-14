<?php


class RevCausafiscal extends CActiveRecord
{
	



	public function tableName()
	{
		return 'rev_causafiscal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod_carpdig, cod_estadorevfiscal, fun_rut, fec_registro', 'required'),
			array('cod_carpdig, cod_estadorevfiscal', 'numerical', 'integerOnly'=>true),
			array('fun_rut', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cod_revcausaf, cod_carpdig, cod_estadorevfiscal, fun_rut, fec_registro', 'safe', 'on'=>'search'),
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
			'cod_revcausaf' => 'Cod Revcausaf',
			'cod_carpdig' => 'Cod Carpdig',
			'cod_estadorevfiscal' => 'Cod Estadorevfiscal',
			'fun_rut' => 'Fun Rut',
			'fec_registro' => 'Fec Registro',
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

		$criteria->compare('cod_revcausaf',$this->cod_revcausaf);
		$criteria->compare('cod_carpdig',$this->cod_carpdig);
		$criteria->compare('cod_estadorevfiscal',$this->cod_estadorevfiscal);
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
	 * @return RevCausafiscal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
