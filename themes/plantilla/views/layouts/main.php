<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $this->pageTitle;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

   

    <!-- Le styles -->
    <link href="<?php echo Yii::app()->theme->baseUrl;?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/buttons.dataTables.min.css" rel="stylesheet">

    <!--mensaje alert-->
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/sweetalert.css" rel="stylesheet">
    <script src="<?php echo Yii::app()->theme->baseUrl;?>/js/sweetalert-dev.js"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->    
  </head>
  <body>

<div class="navbar navbar-static-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="<?php echo Yii::app()->baseUrl; ?>/index.php"><img src="<?php echo Yii::app()->baseUrl; ?>/images/logo.png"/></a>
        
      <div class="nav-collapse collapse pull-right">       
       <?php $this->widget('application.extensions.eflatmenu.EFlatMenu', array(          
          'items'=>array( 
            array('label'=>'Inicio', 'url'=>array('/Admin/index'), 'icon-class'=>'fa-home','visible'=>!Yii::app()->user->isGuest),


            array('label'=>'Diligencias', 'url'=>'#', 'items' => array(   
                array('label' => '<strong>Decretar</strong> Diligencias/Tarea', 'url'=>array('/BancoTarea/RegistrarTarea'),
                  'visible'=>( Yii::app()->user->getState('perfil')==13
  || Yii::app()->user->getState('perfil')==1 
  || Yii::app()->user->getState('perfil')==2 
  || Yii::app()->user->getState('perfil')==3 
  || Yii::app()->user->getState('perfil')==4 
  //|| Yii::app()->user->getState('perfil')==11 
  || Yii::app()->user->getState('perfil')==12
  || Yii::app()->user->getState('perfil')==16 )  ),


                array('label' => '<strong>Decretar</strong> y Asignar Diligencias/Tarea', 'url'=>array('/BancoTarea/RegistrarTareaAsignar'),
                  'visible'=>( Yii::app()->user->getState('perfil')==13
  || Yii::app()->user->getState('perfil')==1 
  || Yii::app()->user->getState('perfil')==2 
  || Yii::app()->user->getState('perfil')==3 
  || Yii::app()->user->getState('perfil')==4 
  || Yii::app()->user->getState('perfil')==11 
  || Yii::app()->user->getState('perfil')==12
  || Yii::app()->user->getState('perfil')==16 ) ),



                array('label' => '<strong>Decretar</strong> Tareas Administrativas', 'url'=>array('/BancoTarea/RegistrarTareaAdmin'),
                  'visible'=>( Yii::app()->user->getState('perfil')==13
  || Yii::app()->user->getState('perfil')==4
  || Yii::app()->user->getState('perfil')==7
  || Yii::app()->user->getState('perfil')==9
  || Yii::app()->user->getState('perfil')==10
  || Yii::app()->user->getState('perfil')==12
  || Yii::app()->user->getState('perfil')==15
  || Yii::app()->user->getState('perfil')==16
  || Yii::app()->user->getState('perfil')==17 ) ),



                array('label' => '<strong>Asignar</strong> Diligencias de la bolsa de trabajo', 'url'=>array('/Centralizado/AsignarBancoTarea'),
                  'visible'=>( Yii::app()->user->getState('perfil')==13
  || Yii::app()->user->getState('perfil')==2
  || Yii::app()->user->getState('perfil')==3
  || Yii::app()->user->getState('perfil')==4
  || Yii::app()->user->getState('perfil')==5
  || Yii::app()->user->getState('perfil')==6
  || Yii::app()->user->getState('perfil')==7
  || Yii::app()->user->getState('perfil')==14
  || Yii::app()->user->getState('perfil')==15
  || Yii::app()->user->getState('perfil')==17 )),


                array('label' => '<strong>Asignar</strong> Diligencias según Caso', 'url'=>array('/Centralizado/AsignarTareaCAso'),
                  'visible'=>( Yii::app()->user->getState('perfil')==13
  || Yii::app()->user->getState('perfil')==2
  || Yii::app()->user->getState('perfil')==3
  || Yii::app()->user->getState('perfil')==4
  || Yii::app()->user->getState('perfil')==5
  || Yii::app()->user->getState('perfil')==6
  || Yii::app()->user->getState('perfil')==7
  || Yii::app()->user->getState('perfil')==14
  || Yii::app()->user->getState('perfil')==15
  || Yii::app()->user->getState('perfil')==17 )),

                array('label' => '<strong>Ejecutar</strong> Bloques de tiempo asignados', 'url'=>array('/BancoTarea/MisBloques'),
                  'visible'=>( Yii::app()->user->getState('perfil')==13
  || Yii::app()->user->getState('perfil')==3
  || Yii::app()->user->getState('perfil')==4
  || Yii::app()->user->getState('perfil')==6
  || Yii::app()->user->getState('perfil')==7
  || Yii::app()->user->getState('perfil')==8
  || Yii::app()->user->getState('perfil')==9
  || Yii::app()->user->getState('perfil')==11
  || Yii::app()->user->getState('perfil')==12
  || Yii::app()->user->getState('perfil')==14
  || Yii::app()->user->getState('perfil')==15 )),



                array('label' => '<strong>Ejecutar</strong> Diligencias Asignadas', 'url'=>array('/BancoTarea/MisDiligencias'),
                  'visible'=>( Yii::app()->user->getState('perfil')==13
  || Yii::app()->user->getState('perfil')==3
  || Yii::app()->user->getState('perfil')==4
  || Yii::app()->user->getState('perfil')==6
  || Yii::app()->user->getState('perfil')==7
  || Yii::app()->user->getState('perfil')==8
  || Yii::app()->user->getState('perfil')==9
  || Yii::app()->user->getState('perfil')==11
  || Yii::app()->user->getState('perfil')==12
  || Yii::app()->user->getState('perfil')==14
  || Yii::app()->user->getState('perfil')==15 )),


                  array('label' => '<strong>Ejecutar</strong> Diligencias Pendientes según Caso', 'url'=>array('/Centralizado/EjecutarTareaCAso'),
                  'visible'=>( Yii::app()->user->getState('perfil')==13
  || Yii::app()->user->getState('perfil')==3
  || Yii::app()->user->getState('perfil')==4
  || Yii::app()->user->getState('perfil')==6
  || Yii::app()->user->getState('perfil')==7
  || Yii::app()->user->getState('perfil')==8
  || Yii::app()->user->getState('perfil')==9
  || Yii::app()->user->getState('perfil')==11
  || Yii::app()->user->getState('perfil')==12
  || Yii::app()->user->getState('perfil')==14
  || Yii::app()->user->getState('perfil')==15 )),






                array('label' => '<strong>Eliminar</strong> diligencias según Caso', 'url'=>array('/Centralizado/EliminarTareaCAso'),
                  'visible'=>( Yii::app()->user->getState('perfil')==13
  || Yii::app()->user->getState('perfil')==2
  || Yii::app()->user->getState('perfil')==3
  || Yii::app()->user->getState('perfil')==4
  || Yii::app()->user->getState('perfil')==5
  || Yii::app()->user->getState('perfil')==6
  || Yii::app()->user->getState('perfil')==7
  || Yii::app()->user->getState('perfil')==14
  || Yii::app()->user->getState('perfil')==15
  || Yii::app()->user->getState('perfil')==17 )),



                array('label' => '<strong>Eliminar</strong> bloques de tiempo', 'url'=>array('/BancoTarea/EliminarTareaAdmin'),
                  'visible'=>( Yii::app()->user->getState('perfil')==13
  || Yii::app()->user->getState('perfil')==4)),



                array('label' => 'Consultar Diligencias según Caso', 'url'=>array('/BancoTarea/ConsultaCaso')),
               
                array('label' => 'Listado de Diligencias Decretadas', 'url'=>array('/BancoTarea/DiligenciasDecretadas'),
                  'visible'=>( Yii::app()->user->getState('perfil')==13
                  || Yii::app()->user->getState('perfil')==1
                  || Yii::app()->user->getState('perfil')==2
                  || Yii::app()->user->getState('perfil')==3
                  || Yii::app()->user->getState('perfil')==4
                  || Yii::app()->user->getState('perfil')==11
                  || Yii::app()->user->getState('perfil')==12
                  || Yii::app()->user->getState('perfil')==16 )
              ),

                array('label' => 'Listado de Diligencias TODAS Decretadas UGI', 'url'=>array('/BancoTarea/DiligenciasTodasSinEjecutarUGI'),
                  'visible'=>( Yii::app()->user->getState('perfil')==13)
                      ),


            ),'visible'=>!Yii::app()->user->isGuest),


            array('label'=>'Carpetas', 'url'=>'#', 'items' => array(   
                array('label' => 'Consultar Ubicación', 'url'=>array('/Carpeta/ConsultarUbicacion')),
                array('label' => 'Recepcionar', 'url'=>array('/Carpeta/RecepcionarCarpeta')),
                array('label' => 'Mover', 'url'=>array('/Carpeta/MoverCarpeta')),
              //  array('label' => 'Transferir', 'url'=>array('/Carpeta/TransferirCarpeta')),
                
            ),'visible'=>!Yii::app()->user->isGuest),

            array('label'=>'Reportes', 'url'=>'#', 'items' => array(                     
                     array('label' => 'Plazos de Cierre', 'url'=>array('/DiligenciasSaf/PlazosCierre')),

            ),'visible'=>(Yii::app()->user->getState('perfil')==13 
                      || Yii::app()->user->getState('perfil')==1  
                      || Yii::app()->user->getState('perfil')==3  
                      || Yii::app()->user->getState('perfil')==4                     
                      || Yii::app()->user->getState('perfil')==6
                      || Yii::app()->user->getState('perfil')==7
                      || Yii::app()->user->getState('perfil')==8
                      || Yii::app()->user->getState('perfil')==9                       
                      || Yii::app()->user->getState('perfil')==11
                      || Yii::app()->user->getState('perfil')==12
                      || Yii::app()->user->getState('perfil')==14
                      || Yii::app()->user->getState('perfil')==15)
            ),
              
            array('label'=>'Herramientas', 'url'=>'#', 'items' => array(                     
                   //  array('label' => 'Plazos de Cierre', 'url'=>array('/DiligenciasSaf/PlazosCierre')),
                     array('label' => 'Caratula', 'url'=>array('/Admin/Caratula')),  
                     array('label' => 'CUCT', 'url'=>array('/Admin/Cuct')),  
                     array('label' => 'Hoja Audiencia', 'url'=>array('/Audiencias/CrearFicha')),  

                     array('label' => 'Control de Detención', 'url'=>array('/Admin/ControlDetencion')),  

                   //   array('label' => 'Registro de Documentos', 'url'=>array('/DiligenciasSaf/IngresoDocSAF')),
                    
                     array('label' => 'Documentos SAF Ingresados por Fiscal', 'url'=>array('/DiligenciasSaf/ReporteDocSAF')),
                     array('label' => 'Documentos SAF Ingresados por RUC', 'url'=>array('/DiligenciasSaf/ReporteDocRucSAF')),
                     array('label' => 'Encarpetado de Documentos', 'url'=>array('/DiligenciasSaf/EncarpetadoDocs')),
                     array('label' => 'Impresión de Documentos', 'url'=>array('/DiligenciasSaf/ImpresionDocs')),
                 //    array('label' => 'Impresión de Documentos', 'url'=>array('/DiligenciasSaf/ImpresionDocs')),
                    

            ),'visible'=>(Yii::app()->user->getState('perfil')==13 
                      || Yii::app()->user->getState('perfil')==1
                      || Yii::app()->user->getState('perfil')==2
                      || Yii::app()->user->getState('perfil')==3  
                      || Yii::app()->user->getState('perfil')==4                     
                      || Yii::app()->user->getState('perfil')==6
                      || Yii::app()->user->getState('perfil')==7
                      || Yii::app()->user->getState('perfil')==8
                      || Yii::app()->user->getState('perfil')==9                       
                      || Yii::app()->user->getState('perfil')==11
                      || Yii::app()->user->getState('perfil')==12
                      || Yii::app()->user->getState('perfil')==14
                      || Yii::app()->user->getState('perfil')==15)
            ),

        /*      
            array('label'=>'Agenda EIVG', 'url'=>'#', 'items' => array(                     
                     array('label' => 'Reserva de Hora para EIVG', 'url'=>array('/AgendaEIVG/ReservarHoraEIVG')),
                     array('label' => 'Calendario EIVG', 'url'=>array('/AgendaEIVG/CalendarioEIVG')),
                     array('label' => 'Listado EIVG Agendadas', 'url'=>array('/AgendaEIVG/ListadoAgendaEIVG')),
                   //  array('label' => 'Documentos SAF Ingresados por RUC', 'url'=>array('/DiligenciasSaf/ReporteDocRucSAF')),
                   //  array('label' => 'Encarpetado de Documentos', 'url'=>array('/DiligenciasSaf/EncarpetadoDocs')),
                   //  array('label' => 'Impresión de Documentos', 'url'=>array('/DiligenciasSaf/ImpresionDocs')),   

            ),'visible'=>(Yii::app()->user->getState('perfil')==13
               || Yii::app()->user->id=='15615576-4')
            ),

////fun_rut=Yii::app()->user->id;*/

//////////////////////******* MENU DIGITALIZADORES   **///

            array('label'=>'Ingreso Carpetas Digitalizadas', 'url'=>'#', 'items' => array(                  
                    array('label' => 'Ingreso de Documentos', 'url'=>array('/CarpetaDigital/GuardarDocumentosDigitales')),  
                    ),'visible'=>((Yii::app()->user->getState('fiscalia')==805 || Yii::app()->user->getState('fiscalia')==8 || Yii::app()->user->getState('fiscalia')==815) && (Yii::app()->user->getState('perfil')==13
                      || Yii::app()->user->getState('perfil')==19 || Yii::app()->user->getState('perfil')==4))
             ),


////////////////////////////////////////////////////////////

            array('label'=>'Carpetas Digitalizadas', 'url'=>'#', 'items' => array(                  
                    array('label' => 'Ingreso de Parte/Documentos', 'url'=>array('/CarpetaDigital/GuardarParte')),  
                //    array('label' => 'Eliminar Documentos', 'url'=>array('/CarpetaDigital/EliminarDocumentoDigital'),'visible'=>(Yii::app()->user->getState('perfil')==13)),  
                    array('label' => 'Revisión Causas Preclasificador', 'url'=>array('/CarpetaDigital/CausasPrec')),  
                    array('label' => 'Ingreso de Informes', 'url'=>array('/Correspondencia/RecepDocAct')),                      
                  //  array('label' => 'Causas Vigentes por Fiscal', 'url'=>array('/CarpetaDigital/CausasVigentesFiscal')),  
                    array('label' => 'Consultar Causa', 'url'=>array('/CarpetaDigital/consultarCarpetaDigital')),
                    array('label' => 'Revisión de Informes', 'url'=>array('/CarpetaDigital/RevisarInformes')), 
                  //  array('label' => 'Reservar Causa', 'url'=>array('/CarpetaDigital/ReservarCausa')),  
                    array('label' => 'Separar Causa', 'url'=>array('/CarpetaDigital/SepararCarpeta')),  
                  //  array('label' => 'Revisión de Informes', 'url'=>array('/CarpetaDigital/RevisarInformes')), 
                    array('label'=>'Audiencias', 'url'=>'#', 'items' => array(  
                      array('label' => 'Registro Audiencias', 'url'=>array('/CarpetaDigital/AgendarAudiencia')), 
                      array('label' => 'Audiencias Sala 1', 'url'=>array('/CarpetaDigital/AudienciasFiscal')),
                      array('label' => 'Audiencias Sala 2', 'url'=>array('/CarpetaDigital/AudienciasSala2')),
                      array('label' => 'Controles de detención', 'url'=>array('/CarpetaDigital/AudienciasControles')), 
                      array('label' => 'Audiencias TOP/Corte', 'url'=>array('/CarpetaDigital/AudienciasTopCorte')), 
                    )),
                    
            ),'visible'=>(Yii::app()->user->getState('fiscalia')==803  
                      && (Yii::app()->user->getState('perfil')==13
                      || Yii::app()->user->getState('perfil')==2
                      || Yii::app()->user->getState('perfil')==4
                      || Yii::app()->user->getState('perfil')==3
                      || Yii::app()->user->getState('perfil')==8
                      || Yii::app()->user->getState('perfil')==11
                      || Yii::app()->user->getState('perfil')==14))
             ),

          array('label'=>'Carpetas Digitalizadas', 'url'=>'#', 'items' => array(                  
                    array('label' => 'Ingreso de Parte/Documentos', 'url'=>array('/CarpetaDigital/GuardarParte')),  
                //    array('label' => 'Eliminar Documentos', 'url'=>array('/CarpetaDigital/EliminarDocumentoDigital'),'visible'=>(Yii::app()->user->getState('perfil')==13)),  
                    array('label' => 'Revisión Causas Preclasificador', 'url'=>array('/CarpetaDigital/CausasPrec')),  
                    array('label' => 'Ingreso de Informes', 'url'=>array('/Correspondencia/RecepDocAct')),                      
                  //  array('label' => 'Causas Vigentes por Fiscal', 'url'=>array('/CarpetaDigital/CausasVigentesFiscal')),  
                    array('label' => 'Consultar Causa', 'url'=>array('/CarpetaDigital/consultarCarpetaDigital')),
                    array('label' => 'Revisión de Informes', 'url'=>array('/CarpetaDigital/RevisarInformes')), 
                  //  array('label' => 'Reservar Causa', 'url'=>array('/CarpetaDigital/ReservarCausa')),  
                    array('label' => 'Separar Causa', 'url'=>array('/CarpetaDigital/SepararCarpeta')),  
                  //  array('label' => 'Revisión de Informes', 'url'=>array('/CarpetaDigital/RevisarInformes')), 
                    array('label'=>'Audiencias', 'url'=>'#', 'items' => array(  
                      array('label' => 'Registro Audiencias', 'url'=>array('/CarpetaDigital/AgendarAudiencia')), 
                      array('label' => 'Listado de Audiencias', 'url'=>array('/CarpetaDigital/AudienciasFiscalia')),
                    )),
                    
            ),'visible'=>(Yii::app()->user->getState('fiscalia')==8 || Yii::app()->user->getState('fiscalia')==805 || Yii::app()->user->getState('fiscalia')==815
                        && (Yii::app()->user->getState('perfil')==13
                      || Yii::app()->user->getState('perfil')==4
                      || Yii::app()->user->getState('perfil')==3
                      || Yii::app()->user->getState('perfil')==8
                      || Yii::app()->user->getState('perfil')==11
                      || Yii::app()->user->getState('perfil')==14))
             ),


            array('label'=>'Carpetas Digitalizadas Fiscal', 'url'=>'#', 'items' => array(  
                    array('label' => 'Causas Vigentes por Fiscal', 'url'=>array('/CarpetaDigital/CausasVigentesFiscal')),
                    array('label' => 'Causas Asignadas sin Revisión Fiscal', 'url'=>array('/CarpetaDigital/CausasAsignadasFiscal')),
                    array('label' => 'Consultar Causa', 'url'=>array('/CarpetaDigital/consultarCarpetaDigital')),
                    array('label' => 'Ingreso de Documentos', 'url'=>array('/CarpetaDigital/GuardarParte')),
                    array('label' => 'Revisión de Informes', 'url'=>array('/CarpetaDigital/RevisarInformes')),  
                    array('label' => 'Reservar Causa', 'url'=>array('/CarpetaDigital/ReservarCausa')),  
                    array('label'=>'Audiencias', 'url'=>'#', 'items' => array(  
                          array('label' => 'Audiencias Sala 1', 'url'=>array('/CarpetaDigital/AudienciasFiscal')),
                          array('label' => 'Audiencias Sala 2', 'url'=>array('/CarpetaDigital/AudienciasSala2')),
                          array('label' => 'Controles de detención', 'url'=>array('/CarpetaDigital/AudienciasControles')), 
                          array('label' => 'Audiencias TOP/Corte', 'url'=>array('/CarpetaDigital/AudienciasTopCorte')), 
                          )),
            ),'visible'=>(Yii::app()->user->getState('fiscalia')==803
                        && (Yii::app()->user->getState('perfil')==13 
                        || Yii::app()->user->getState('perfil')==1
                        || Yii::app()->user->getState('perfil')==4))
          ),


            array('label'=>'Carpetas Digitalizadas Fiscal', 'url'=>'#', 'items' => array(  
                    array('label' => 'Causas Vigentes por Fiscal', 'url'=>array('/CarpetaDigital/CausasVigentesFiscal')),
                    array('label' => 'Causas Asignadas sin Revisión Fiscal', 'url'=>array('/CarpetaDigital/CausasAsignadasFiscal')),
                    array('label' => 'Revisión Causas Preclasificador', 'url'=>array('/CarpetaDigital/CausasPrec')),  
                    array('label' => 'Consultar Causa', 'url'=>array('/CarpetaDigital/consultarCarpetaDigital')),
                    array('label' => 'Ingreso de Documentos', 'url'=>array('/CarpetaDigital/GuardarParte')), 
                    array('label' => 'Ingreso de Informes', 'url'=>array('/Correspondencia/RecepDocAct')),
                    array('label' => 'Revisión de Informes', 'url'=>array('/CarpetaDigital/RevisarInformes')),  
                    array('label' => 'Reservar Causa', 'url'=>array('/CarpetaDigital/ReservarCausa')),  
                    array('label'=>'Audiencias', 'url'=>'#', 'items' => array(  
                          array('label' => 'Listado Audiencias', 'url'=>array('/CarpetaDigital/AudienciasFiscalia')),
                          //array('label' => 'Audiencias TOP/Corte', 'url'=>array('/CarpetaDigital/AudienciasTopCorte')), 
                          )),
            ),'visible'=>(Yii::app()->user->getState('fiscalia')==8 || Yii::app()->user->getState('fiscalia')==805 || Yii::app()->user->getState('fiscalia')==815
                        && (Yii::app()->user->getState('perfil')==13 
                        || Yii::app()->user->getState('perfil')==1
                        || Yii::app()->user->getState('perfil')==4
                        || Yii::app()->user->getState('perfil')==19))
          ),



            array('label'=>'Admin', 'url'=>'#', 'items' => array(                     
                    array('label' => 'Perfiles', 'url'=>array('/Admin/Pefiles')
                      ,'visible'=>(Yii::app()->user->getState('perfil')==13)),  

                    array('label' => 'Ausencias', 'url'=>array('/Admin/Ausencia')),  

                    array('label' => 'Administrar Equipos', 'url'=>array('/Admin/AdminEquipos')),

                    array('label' => 'Diligencias', 'url'=>array('/Admin/Diligencia')
                      ,'visible'=>(Yii::app()->user->getState('perfil')==13)),  

                    array('label' => 'Cambiar de Fiscalía', 'url'=>array('/Admin/ModificarFiscalia')
                      ,'visible'=>(Yii::app()->user->getState('perfil')==13)),  

                    array('label' => 'Pruebas', 'url'=>array('/Test/Index')
                      ,'visible'=>(Yii::app()->user->getState('perfil')==13)),  

            ),'visible'=>(Yii::app()->user->getState('perfil')==13) ),

            array('label'=>'Cerrar Sesión ('.Yii::app()->user->name.')', 'url'=>Yii::app()->baseUrl.'/index.php?r=site/logout','visible'=>!Yii::app()->user->isGuest)         
          ),
        )); ?>       

      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<?php if(!empty($this->breadcrumbs)):?>
  <div class="container" style="padding: 0">
  <div class="row-fluid">
<center>
  
  <div class="span12">
      <?php $this->widget('zii.widgets.CBreadcrumbs', array(
        'htmlOptions'=>array("style"=>"margin: 10px 0;"),
        'links'=>$this->breadcrumbs,
      )); ?><!-- breadcrumbs -->
  </div>

      </center>
  </div> 
  </div>


<?php endif?>
<?php if(($msgs=Yii::app()->user->getFlashes())!==null and $msgs!==array()):?>
  <div class="container" style="padding-top:0">
    <div class="row-fluid">

    

      <div class="span12">
        <?php foreach($msgs as $type => $message):?>
          <div class="alert alert-<?php echo $type?>">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4><?php echo ucfirst($type)?>!</h4>
            <?php echo $message?>
          </div>
        <?php endforeach;?>
      </div>


    </div>


  </div>
<?php endif;?>
<?php echo $content;?>


 <section class="footer-credits">
    <div class="container">
        <ul class="clearfix">
           <li>© <?php echo date("Y"); ?> Sistemas Regionales. Fiscalía Regional del Bío-Bío.</li>
           
        </ul>
    </div>
    <!--close footer-credits container-->
</section>

  </body>
  
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/buttons.flash.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/buttons.colVis.min.js"></script>

</html>