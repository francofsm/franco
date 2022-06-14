<?php

date_default_timezone_set('America/Santiago'); 

class ReporteController extends Controller{

	public $layout='//layouts/column2';

	public function filters(){
		return array('accessControl', 'postOnly + delete',);
	}


	public function actionReporteDiligenciasFiscal(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$fis = Fiscalia::model()->findAll('fis_codigo in (8,801,802,803,805,806,807,810,813,814)');

	    	$this->render('reporteDiligenciasFiscal', array('fis'=>$fis));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionReporteUnidad(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$uni = Unidad::model()->getUnidades();

	    	$this->render('reporteUnidad', array('uni'=>$uni));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionReporteUnidadDiligencias(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$uni = Unidad::model()->getUnidades();

	    	$this->render('reporteUnidadDiligencias', array('uni'=>$uni));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionReporteIndividual(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$fun = Funcionario::model()->getFuncionariosReporte();

	    	$this->render('reporteIndividual', array('fun'=>$fun));
	    }//FIN ELSE
	}//FIN FUNCION INDEX






//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////                  REPORTES      PLAZO              //////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function actionReportePlazo(){		
	    if(Yii::app()->user->isGuest)
	            $this->redirect(Yii::app()->createUrl('site/logout'));
	    else{

	    	$uni = Unidad::model()->getUnidades();

	    	$this->render('reportePlazos', array('uni'=>$uni));
	    }//FIN ELSE
	}//FIN FUNCION INDEX


	public function actionDetalleReportePlazoMinAsignados(){
		
		$rut=$_POST['rut'];
		$fec_ini=$_POST['fec'];
		$fec_fin=$_POST['fec_fin'];

		$ta = BancoTarea::model()->getReporteMinAsignados($rut, $fec_ini, $fec_fin);

		echo "<table id='listdetalle' class='table table-striped table-bordered display nowrap' cellspacing='0' width='100%'>
        <thead align='left'>
            <tr>
            	<th>N°</th>
				<th>RUC</th>
				<th>DILIGENCIA</th>
				<th>TIEMPO</th>				
				<th>COMENTARIOS</th>
				
				<th>FECHA ASIGNACION</th>
				<th>FECHA PLAZO</th>
				<th>ESTADO</th>
				<th>ASGINADO A:</th>
				<th>DECRETADO POR:</th>
				
            </tr>
        </thead>";
		echo "<tbody>";		
		$n=1;
		foreach ($ta as $ta) {	
			$fecha=$ta['fec_tarea'];
			$fecasig=$ta['fec_asignacion'];
			echo "<tr>"; 
			echo "<td>".$n."</td>"; 
			echo "<td>".$ta['idf_rolunico']."</td>";
			echo "<td>".$ta['tarea']."</td>";
			echo "<td>".$ta['tiempo']."</td>"; 
	
			echo "<td>".$ta['gls_comentario']."</td>"; 
			echo "<td>".date("d-m-Y", strtotime($fecasig))."</td>"; 

			echo "<td>".date("d-m-Y", strtotime($fecha))."</td>"; 
			
			echo "<td>".$ta['estado']."</td>"; 
			echo "<td>".$ta['funcionario']."</td>"; 
			echo "<td>".$ta['responsable']."</td>"; 
			

			$n++;
		}		
		echo "</tbody>"; 
		echo "</table>";		

		echo "<script type='text/javascript'>
			$( document ).ready(function() {
				var table = $('#listdetalle').DataTable( {
								        
			        'language': {
						'lengthMenu': 'Registros por página _MENU_ ',
						'zeroRecords': 'No se han encontrado resultados',
						'info': 'Mostrando página _PAGE_ de _PAGES_',
						'infoEmpty': 'No hay registros disponibles',
						'infoFiltered': '(filtrado en un total de _MAX_ registros)',
						'search': 'Buscar',
						'paginate': {
							'next': 'Siguiente',
							'previous': 'Anterior'
						}
					},
			        dom: 'Bfrtip',
					buttons: [					
						{
							extend: 'print',
							text: 'Imprimir',
				            exportOptions: {
				                stripHtml: false,
				                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
							}
						}
	                ]

				} );
			});    

		</script>";

	}//FIN FUNCTION	

//FIN CONTROLADOR
}