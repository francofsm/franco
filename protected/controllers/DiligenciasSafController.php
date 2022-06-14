<?php

date_default_timezone_set('America/Santiago'); 

class DiligenciasSafController extends Controller{

	public $layout='//layouts/column2';

	public function filters(){
		return array('accessControl', 'postOnly + delete',);
		  }
	



	public function actionIngresoDocSAF(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	        //$this->render('crearCaratula');  
	        //$this->redirect('http://172.17.123.19/sia_old/inicio/index.php?r=Caratula/CrearCaratula');
	        
	        http://172.17.123.19/herramientas/start/index.php?r=DiligenciasSaf/consultaDocumentosFiscal

	        $this->redirect('http://172.17.123.19/herramientas/start/index.php?r=DiligenciasSaf/RecepDocAct&rut='.Yii::app()->user->id.'&fis='.Yii::app()->user->getState('fiscalia').'&invitado=1');

	    }//FIN ELSE
	}//



	public function actionReporteDocSAF(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	        //$this->render('crearCaratula');  
	        //$this->redirect('http://172.17.123.19/sia_old/inicio/index.php?r=Caratula/CrearCaratula');
	        
	        http://172.17.123.19/herramientas/start/index.php?r=DiligenciasSaf/consultaDocumentosFiscal

	        $this->redirect('http://172.17.123.19/herramientas/start/index.php?r=DiligenciasSaf/consultaDocumentosFiscal&rut='.Yii::app()->user->id.'&fis='.Yii::app()->user->getState('fiscalia').'&invitado=1');

	    }//FIN ELSE
	}//


	public function actionReporteDocRucSAF(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	        //$this->render('crearCaratula');  
	        //$this->redirect('http://172.17.123.19/sia_old/inicio/index.php?r=Caratula/CrearCaratula');
	        
	        http://172.17.123.19/herramientas/start/index.php?r=DiligenciasSaf/consultaDocumentosFiscal

	        $this->redirect('http://172.17.123.19/herramientas/start/index.php?r=DiligenciasSaf/consultaDocumentosRuc&rut='.Yii::app()->user->id.'&fis='.Yii::app()->user->getState('fiscalia').'&invitado=1');

	    }//FIN ELSE
	}//



	public function actionEncarpetadoDocs(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	        //$this->render('crearCaratula');  
	        //$this->redirect('http://172.17.123.19/sia_old/inicio/index.php?r=Caratula/CrearCaratula');
	        
	        http://172.17.123.19/herramientas/start/index.php?r=DiligenciasSaf/consultaDocumentosFiscal

	        $this->redirect('http://172.17.123.19/herramientas/start/index.php?r=DiligenciasSaf/consultaDocumentosEncarpetado&rut='.Yii::app()->user->id.'&fis='.Yii::app()->user->getState('fiscalia').'&invitado=1');

	    }//FIN ELSE
	}//

	public function actionImpresionDocs(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	        //$this->render('crearCaratula');  
	        //$this->redirect('http://172.17.123.19/sia_old/inicio/index.php?r=Caratula/CrearCaratula');
	        
	        http://172.17.123.19/herramientas/start/index.php?r=DiligenciasSaf/consultaDocumentosFiscal

	        $this->redirect('http://172.17.123.19/herramientas/start/index.php?r=DiligenciasSaf/consultaDocumentosNoImpresos&rut='.Yii::app()->user->id.'&fis='.Yii::app()->user->getState('fiscalia').'&invitado=1');

	    }//FIN ELSE
	}//



	public function actionPlazosCierre(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{
	        //$this->render('crearCaratula');  
	        //$this->redirect('http://172.17.123.19/sia_old/inicio/index.php?r=Caratula/CrearCaratula');
	        
	        http://172.17.123.19/herramientas/start/index.php?r=DiligenciasSaf/consultaDocumentosFiscal

	        $this->redirect('http://172.17.123.19/herramientas/start/index.php?r=Alertas/ReportePlazosCierreFiscalia&rut='.Yii::app()->user->id.'&fis='.Yii::app()->user->getState('fiscalia').'&invitado=1');

	    }//FIN ELSE
	}//




}//FIN CONTROLLER