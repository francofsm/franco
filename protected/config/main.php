<?php

Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'SIA',
	'language'=>'es',
	'theme'=>'plantilla',

	// preloading 'log' component
	//'theme'=>'bootstrap',
	'preload'=>array('log', 'bootstrap'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.coco.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'admin',
			
			'ipFilters'=>array('127.0.0.1','172.17.123.100','172.17.123.191','172.17.123.93'),   // IP para Servidor 47
			//'ipFilters'=>array('146.83.198.35','::1'),
			 'generatorPaths'=>array(
                'bootstrap.gii',
            ),
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			//'authExpires' => 1200, //sessione di 10 minuti
		),
		'bootstrap'=>array(
	    'class'=>'application.extensions.bootstrap.components.Bootstrap',
		),
		 'ePdf' => array(
                        'class' => 'ext.yii-pdf.EYiiPdf',
                        'params' => array(
                            'mpdf'=> array(
                                'librarySourcePath' => 'application.vendor.mpdf.*',
                                'constants'=> array(
                                    '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                                ),
                                'class'=>'mpdf', // the literal class filename to be loaded from the vendors folder
                                'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
                                    'mode'              => '', //  This parameter specifies the mode of the new document.
                                    'format'            => 'A4', // format A4, A5, ...
                                    'default_font_size' => 0, // Sets the default document font size in points (pt)
                                    'default_font'      => '', // Sets the default font-family for the new document.
                                    'mgl'               => 15, // margin_left. Sets the page margins for the new document.
                                    'mgr'               => 15, // margin_right
                                    'mgt'               => 16, // margin_top
                                    'mgb'               => 16, // margin_bottom
                                    'mgh'               => 9, // margin_header
                                    'mgf'               => 9, // margin_footer
                                    'orientation'       => 'P', // landscape or portrait orientation
                                )
                            )
        )),

		'Smtpmail'=>array(
		'class'=>'application.extensions.smtpmail.PHPMailer',
            'Host'=>"exchange.minpublico.cl",
            'Username'=>'ugi_octava@minpublico.cl',
            'Password'=>'ugioctava1',
            'Mailer'=>'smtp',
            'Port'=>25,
            'SMTPAuth'=>true, 
        ),
        'format' => array(
		  'datetimeFormat'=>"d M, Y h:m:s a",
		  'dateFormat'=>'d-m-Y',   // <-----ESTE ES
		 ),
        	

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=bd_sia',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),

		'db_fr'=>array(
			'class'=>'CDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=fr',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),	
	
		'db_vr'=>array(
			'class'=>'CDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=vr',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),


		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		'session' => array(

			'class' => 'CDbHttpSession',
            'timeout' => 99999,

        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'ldap'      => array(
                    'host'      => '172.18.1.7',
                    'port'      => 389,
                    'domain'    => 'minpublico.cl',
		),
		// this is used in contact page
		'adminEmail'=>'@minpublico.cl',
	),
);
