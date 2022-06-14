<?php

date_default_timezone_set('America/Santiago'); 

class VruralController extends Controller{

	public $layout='//layouts/column2';

	public function filters(){
		return array('accessControl', 'postOnly + delete',);
		  }
	



	public function actionRegistroVR(){		
	    if(Yii::app()->user->isGuest)
	        $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	      
	      	$fis=Yii::app()->user->getState('fiscalia');
	    	$fun = TgVictimavr::model()->getVictimasvr();

	    	$this->render('RegistroVR', array('fun'=>$fun));
	    }//FIN ELSE
	}//





}//FIN CONTROLLER