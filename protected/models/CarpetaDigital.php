<?php

class CarpetaDigital extends CActiveRecord
{

	public $getClasesRuc; 
	public $getClasesRucFavoritos; 
	public $getDocumentosRuc;
	public $getPartesCargados; 
	public $getCausasPrec;
	public $getEstadoParte;
	public $getDocumentosPublicos; 
	public $getDocumentosPublicosfecha;
	public $getDocumentosRucTodos; 
	public $getDocumentosDigitalesRuc;
	public $getDocumentosFavoritos;
	public $getInformesNoRevisados; 

	public $privacidad;
	public $responsable;
	public $gls_clasedoc; 
	public $fiscal; 

	public function tableName()
	{
		return 'carpeta_digital';
	}

	public function rules()
	{
		return array(
			array('idf_rolunico, fis_codigo, fec_actividad, cod_clasedoc, cod_estadocarpdig, gls_nomdoc, gls_ruta, fun_rut, fec_registro', 'required'),
			array('crr_idactividad, cod_clasedoc, cod_estadocarpdig', 'numerical', 'integerOnly'=>true),
			array('idf_rolunico', 'length', 'max'=>15),
			array('fis_codigo', 'length', 'max'=>3),
			array('gls_nomdoc, gls_ruta', 'length', 'max'=>999),
			array('fun_rut', 'length', 'max'=>12),
			array('ind_vigencia, ind_reservado, cod_carpdig, idf_rolunico, fis_codigo, fec_actividad, crr_idactividad, tip_actividad, tip_subtipactividad, tip_subsubtipactividad, cod_clasedoc, cod_estadocarpdig, cod_control, cod_prioritario, gls_nomdoc, gls_ruta, fun_rut, fec_registro', 'safe', 'on'=>'search'),
		);
	}

	public function relations(){
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'cod_carpdig' => 'Cod Carpdig',
			'idf_rolunico' => 'Idf Rolunico',
			'fis_codigo' => 'Fis Codigo',
			'fec_actividad' => 'Fec Actividad',
			'crr_idactividad' => 'Crr Idactividad',
			'cod_clasedoc' => 'Cod Clasedoc',
			'cod_estadocarpdig' => 'Cod Estadoparte',
			'cod_control' => 'Cod Control',
			'cod_prioritario' => 'Cod Prioritario',
			'gls_nomdoc' => 'Gls Nomdoc',
			'gls_ruta' => 'Gls Ruta',
			'fun_rut' => 'Fun Rut',
			'fec_registro' => 'Fec Registro',
			'ind_reservado' => 'ind_reservado', 
			'ind_vigencia' => 'ind_vigencia', 
		);
	}

	public function getInformesNoRevisados($fiscal){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, concat(f.cuenta_nombre, " ",f.cuenta_apellido) fiscal');
		$criteria->join ='LEFT JOIN causa_vigente v on v.idf_rolunico=t.idf_rolunico
						  LEFT JOIN fr.cuenta_generica f on f.rut_saf=v.cuenta_rut';

		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");	

		if($fiscal <> ""){
			$criteria->addCondition("v.cuenta_rut = '".$fiscal."' ");	
		}
		
		$criteria->addCondition("t.cod_clasedoc = 12 "); 
		$criteria->addCondition("t.cod_estadocarpdig = 1 "); 
		$criteria->addCondition("t.ind_vigencia = 1 ");
		$criteria->group = "t.idf_rolunico,t.gls_nomdoc";	
		$criteria->order = 't.fec_registro ASC';
		$this->getInformesNoRevisados=CarpetaDigital::model()->findAll($criteria);	
		return $this->getInformesNoRevisados; 
	}

	public function getDocumentosDigitalesRuc($ruc){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, c.gls_clasedoc');
		$criteria->join ='INNER JOIN tg_clasedoc c on c.cod_clasedoc=t.cod_clasedoc';
		$criteria->addCondition("t.idf_rolunico = '".$ruc."' "); 
		$criteria->addCondition("t.ind_vigencia = 1 ");
		$criteria->order = 'c.ind_orden ASC';
		$this->getDocumentosDigitalesRuc=CarpetaDigital::model()->findAll($criteria);	
		return $this->getDocumentosDigitalesRuc; 
	}

	public function getDocumentosRucTodos($ruc){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, c.gls_clasedoc');
		$criteria->join ='INNER JOIN tg_clasedoc c on c.cod_clasedoc=t.cod_clasedoc';
		$criteria->addCondition("t.idf_rolunico = '".$ruc."' "); 
		$criteria->addCondition("t.crr_idactividad is null ");
		$criteria->addCondition("t.ind_vigencia = 1 ");
		$this->getDocumentosRucTodos=CarpetaDigital::model()->findAll($criteria);	
		return $this->getDocumentosRucTodos; 
	}

	public function getDocumentosPublicos($ruc){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, c.gls_clasedoc');
		$criteria->join ='INNER JOIN tg_clasedoc c on c.cod_clasedoc=t.cod_clasedoc';
		$criteria->addCondition("t.idf_rolunico = '".$ruc."' ");
		$criteria->addCondition("c.ind_privacidad = 1 ");
		$criteria->addCondition("t.ind_reservado <> 1 ");
		$criteria->addCondition("t.ind_vigencia = 1 ");
	
		$criteria->order = 'c.ind_orden ASC';	
		$this->getDocumentosPublicos=CarpetaDigital::model()->findAll($criteria);	
		return $this->getDocumentosPublicos; 
	}


	public function getDocumentosPublicosfecha($ruc){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, c.gls_clasedoc');
		$criteria->join ='INNER JOIN tg_clasedoc c on c.cod_clasedoc=t.cod_clasedoc';
		$criteria->addCondition("t.idf_rolunico = '".$ruc."' ");
		$criteria->addCondition("c.ind_privacidad = 1 ");
		$criteria->addCondition("t.ind_reservado <> 1 ");
		$criteria->addCondition("t.ind_vigencia = 1 ");
	
		$criteria->order = 't.fec_actividad ASC';	
		$this->getDocumentosPublicosfecha=CarpetaDigital::model()->findAll($criteria);	
		return $this->getDocumentosPublicosfecha; 
	}


	public function getDocumentosRuc($ruc, $clase){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, c.gls_clasedoc');
		$criteria->join ='INNER JOIN tg_clasedoc c on c.cod_clasedoc=t.cod_clasedoc';
		$criteria->addCondition("t.idf_rolunico = '".$ruc."' ");	
		$criteria->addCondition("t.cod_clasedoc = '".$clase."' ");	
		$criteria->addCondition("t.ind_vigencia = 1 ");
		$criteria->order = 't.fec_actividad DESC';	
		$this->getDocumentosRuc=CarpetaDigital::model()->findAll($criteria);	
		return $this->getDocumentosRuc; 
	}


	public function getDocumentosFavoritos($ruc, $clase){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, c.gls_clasedoc');
		$criteria->join ='INNER JOIN tg_clasedoc c on c.cod_clasedoc=t.cod_clasedoc';
		$criteria->addCondition("t.idf_rolunico = '".$ruc."' ");	
		$criteria->addCondition("t.cod_clasedoc = '".$clase."' ");	
		$criteria->addCondition("t.ind_vigencia = 1 ");
		$criteria->addCondition("t.ind_favorito = 1 ");
		$criteria->order = 't.fec_actividad DESC';	
		$this->getDocumentosFavoritos=CarpetaDigital::model()->findAll($criteria);	
		return $this->getDocumentosFavoritos; 
	}

	public function getEstadoParte($ruc){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*');
		$criteria->addCondition("t.idf_rolunico = '".$ruc."' ");	
		$criteria->addCondition("t.cod_clasedoc in (10,15,38,39,49)");
		//$criteria->addCondition("t.cod_estadocarpdig = 1");
		$criteria->addCondition("t.ind_vigencia = 1 ");
		$criteria->order = 't.cod_carpdig DESC';	
		$this->getEstadoParte=CarpetaDigital::model()->findAll($criteria);	
		return $this->getEstadoParte; 
	}	

	public function getClasesRuc($ruc){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, c.gls_clasedoc, c.ind_privacidad privacidad');
		$criteria->join ='INNER JOIN tg_clasedoc c on c.cod_clasedoc=t.cod_clasedoc';
		$criteria->addCondition("t.idf_rolunico = '".$ruc."' ");
		$criteria->addCondition("t.ind_vigencia = 1 ");
		$criteria->group = "t.cod_clasedoc";				
		$criteria->order = 'c.ind_orden ASC';	
		$this->getClasesRuc=CarpetaDigital::model()->findAll($criteria);	
		return $this->getClasesRuc; 
	}


	public function getClasesRucFavoritos($ruc){
		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, c.gls_clasedoc, c.ind_privacidad privacidad');
		$criteria->join ='INNER JOIN tg_clasedoc c on c.cod_clasedoc=t.cod_clasedoc';
		$criteria->addCondition("t.idf_rolunico = '".$ruc."' ");
		$criteria->addCondition("t.ind_vigencia = 1 ");
		$criteria->addCondition("t.ind_favorito = 1 ");
		$criteria->group = "t.cod_clasedoc";				
		$criteria->order = 'c.ind_orden ASC';	
		$this->getClasesRucFavoritos=CarpetaDigital::model()->findAll($criteria);	
		return $this->getClasesRucFavoritos; 
	}



	public function getPartesCargados(){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, CONCAT(f.fun_nombre, " ",f.fun_ap_paterno) responsable, c.gls_clasedoc');
		$criteria->join ='INNER JOIN fr.funcionario f on f.fun_rut=t.fun_rut
						  INNER JOIN tg_clasedoc c on c.cod_clasedoc=t.cod_clasedoc';

		$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");		
		$criteria->addCondition("t.fis_codigo = '".Yii::app()->user->getState('fiscalia')."' ");
		$criteria->addCondition("t.cod_clasedoc <> 21");	
		$criteria->addCondition("date(t.fec_registro) >= '".date('Y-m-d')."'");	
		$criteria->addCondition("t.ind_vigencia = 1 ");	
		$criteria->order = 't.idf_rolunico,c.gls_clasedoc ASC';
	
		$this->getPartesCargados=CarpetaDigital::model()->findAll($criteria);	
		return $this->getPartesCargados; 
	}


	public function getCausasPrec($fisc){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, CONCAT(f.fun_nombre," ",f.fun_nombre2," ",f.fun_ap_paterno) responsable, c.gls_clasedoc');
		$criteria->join ='INNER JOIN fr.funcionario f on f.fun_rut=t.fun_rut
						  INNER JOIN tg_clasedoc c on c.cod_clasedoc=t.cod_clasedoc';
		$criteria->addCondition("t.cod_estadocarpdig = 1");
		$criteria->addCondition("t.cod_clasedoc in (10,15,38,39,49)"); //se incorpora querella, parte detenido y denuncia
		$criteria->addCondition("t.fis_codigo = '".$fisc."'");	
		$criteria->addCondition("t.ind_vigencia = 1 ");
		$criteria->group = "t.idf_rolunico";
		$criteria->order = 't.cod_carpdig DESC';
	
		$this->getCausasPrec=CarpetaDigital::model()->findAll($criteria);	
		return $this->getCausasPrec; 
	}


	/*** no se utilizará por ahora  **//////
	public function getDocumentosAudiencia($ruc,$fisc){

		$criteria = new CDbCriteria();
		$criteria->select = array('t.*, CONCAT(f.fun_nombre, " ",f.fun_ap_paterno) responsable, c.gls_clasedoc');
		$criteria->join ='INNER JOIN fr.funcionario f on f.fun_rut=t.fun_rut
						  INNER JOIN tg_clasedoc c on c.cod_clasedoc=t.cod_clasedoc';
		$criteria->addCondition("t.fun_rut = '".Yii::app()->user->id."' ");		
		$criteria->addCondition("t.cod_clasedoc in (2,22,23)");	
		$criteria->addCondition("date(t.fec_registro) = '".date('Y-m-d')."'");	
		$criteria->addCondition("t.idf_rolunico = '".$ruc."'");	
		$criteria->addCondition("t.fis_codigo = '".$fisc."'");	
		$criteria->order = 'c.gls_clasedoc ASC';
	
		$this->getDocumentosAudiencia=CarpetaDigital::model()->findAll($criteria);	
		return $this->getDocumentosAudiencia; 
	}


	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('cod_carpdig',$this->cod_carpdig);
		$criteria->compare('idf_rolunico',$this->idf_rolunico,true);
		$criteria->compare('fis_codigo',$this->fis_codigo,true);
		$criteria->compare('fec_actividad',$this->fec_actividad,true);
		$criteria->compare('crr_idactividad',$this->crr_idactividad);
		$criteria->compare('tip_actividad',$this->tip_actividad);
		$criteria->compare('tip_subtipactividad',$this->tip_subtipactividad);
		$criteria->compare('tip_subsubtipactividad',$this->tip_subsubtipactividad);
		$criteria->compare('cod_clasedoc',$this->cod_clasedoc);
		$criteria->compare('cod_estadocarpdig',$this->cod_estadocarpdig);
		$criteria->compare('gls_nomdoc',$this->gls_nomdoc,true);
		$criteria->compare('gls_ruta',$this->gls_ruta,true);
		$criteria->compare('fun_rut',$this->fun_rut,true);
		$criteria->compare('fec_registro',$this->fec_registro,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}



/**
	* Método que permite realizar la busqueda avanzada en la carpeta digital por distintos parametros enviados
	* por el usuario
	* @method getConsultaAvanzada
	* @author Freddy
	* @param array $input Son los parámetros enviados del formulario a buscar
	* @return ActiveRecord $resp con el resultado de la busqueda
	*/

	 public function getConsultaAvanzada($input)
	 {
	 	$criteria = new CDbCriteria();
	 	$criteria->select = array("*");

	 	#Se pregunta por cada parametro enviado si es distinto de vacio,si es verdadero hará la busqueda por ese parámetro

	 	if($input["claseDocumental"] <> "")
	 	{
	 		$criteria->addCondition("t.cod_clasedoc IN (".$input["claseDocumental"].") ");	
	 	}

	 	if($input["ruc"] <> "")
	 	{
	 		$criteria->addSearchCondition("t.idf_rolunico",$input["ruc"],$escape=true,$operator='AND',$like='LIKE');
	 	}

	 	if($input["nombreDocumento"] <> "")
	 	{
	 		$criteria->addSearchCondition("t.gls_nomdoc",$input["nombreDocumento"],$escape=true,$operator='AND',$like='LIKE');
	 	}

	 	if($input["alias"] <> "")
	 	{
	 		$criteria->addSearchCondition("t.gls_alias",$input["alias"],$escape=true,$operator='AND',$like='LIKE');
	 		#$criteria->compare("t.gls_alias",$input["alias"],true);
	 	}

	 	if($input["favorito"] <> "" && $input["favorito"] == "true")
	 	{
	 		$criteria->addCondition("t.ind_favorito IN (1) ");	
	 	}

	 	#Buscamos por rango de fecha del registro
	 	if($input["fDesde"] <> "" && $input["fHasta"] <> "")
	 	{
	 		$criteria->addCondition("t.fec_registro >= '".date("Y-m-d",strtotime($input["fDesde"]))."' AND t.fec_registro <= '".date("Y-m-d",strtotime($input["fDesde"]))."'");
	 	}else if($input["fDesde"] <> "")
	 	{
	 		$criteria->addCondition("t.fec_registro >= '".date("Y-m-d",strtotime($input["fDesde"]))."'");
	 	}else if($input["fHasta"] <> "")
	 	{
	 		$criteria->addCondition("t.fec_registro <= '".date("Y-m-d",strtotime($input["fDesde"]))."'");
	 	}

	 	#Buscamos por rango de fecha de la actividad
	 	if($input["fDesdeAct"] <> "" && $input["fHastaAct"] <> "")
	 	{
	 		$criteria->addCondition("t.fec_actividad >= '".date("Y-m-d",strtotime($input["fDesdeAct"]))."' AND t.fec_actividad <= '".date("Y-m-d",strtotime($input["fDesdeAct"]))."'");
	 	}else if($input["fDesdeAct"] <> "")
	 	{
	 		$criteria->addCondition("t.fec_actividad >= '".date("Y-m-d",strtotime($input["fDesdeAct"]))."'");
	 	}else if($input["fHastaAct"] <> "")
	 	{
	 		$criteria->addCondition("t.fec_actividad <= '".date("Y-m-d",strtotime($input["fDesdeAct"]))."'");
	 	}

	 	$criteria->select = array('t.*,c.*');
	 	$criteria->join ='INNER JOIN tg_clasedoc c on c.cod_clasedoc=t.cod_clasedoc';
	 	$criteria->limit = 200;#Limite de resultados
	 	$criteria->order = "cod_carpdig DESC";

	 	$resp = CarpetaDigital::model()->findAll($criteria);
	 	
	 	return $resp;
	 	
	 }













	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
