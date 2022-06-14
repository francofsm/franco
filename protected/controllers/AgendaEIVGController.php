<?php

date_default_timezone_set('America/Santiago'); 

class AgendaEIVGController extends Controller{

	public $layout='//layouts/column2';

	public function filters(){
		return array('accessControl', 'postOnly + delete',);
		  }
	



	public function actionReservarHoraEIVG(){		
	    if(Yii::app()->user->isGuest)
	        $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	      
	        $this->redirect('http://172.17.123.19/herramientas/start/index.php?r=AgendaEIVG/ReservaHoraEIVG&rut='.Yii::app()->user->id.'&fis='.Yii::app()->user->getState('fiscalia').'&invitado=2');

	    }//FIN ELSE
	}//


	public function actionCalendarioEIVG(){		
	    if(Yii::app()->user->isGuest)
	        $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	      
	        $this->redirect('http://172.17.123.19/herramientas/start/index.php?r=AgendaEIVG/CalendarioFiscaliaEIVG&rut='.Yii::app()->user->id.'&fis='.Yii::app()->user->getState('fiscalia').'&invitado=2');

	    }//FIN ELSE
	}//


	public function actionListadoAgendaEIVG(){		
	    if(Yii::app()->user->isGuest)
	        $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	      
	        $this->redirect('http://172.17.123.19/herramientas/start/index.php?r=AgendaEIVG/ListadoEIVGagendadas&rut='.Yii::app()->user->id.'&fis='.Yii::app()->user->getState('fiscalia').'&invitado=2');

	    }//FIN ELSE
	}//




}//FIN CONTROLLER