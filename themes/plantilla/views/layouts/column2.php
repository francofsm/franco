<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="container" style="padding-top:0">
	<div class="row-fluid">
		<div class="span12">
			<?php
				$this->widget('zii.widgets.CMenu', array(
					'items'=>$this->menu,
					'htmlOptions'=>array('class'=>'nav nav-pills'),
				));
			?>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<?php echo $content; ?>
		</div>
	</div>
</div>
<?php $this->endContent(); ?>

<script type="text/javascript">
	$( document ).ready(function() {
		var table = $('#asginar').DataTable( {
			"pageLength": 9000,
			"ordering": false,
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
						extend: 'excelHtml5',
						exportOptions: {
							columns: [ 0, 1, 2, 3, 4, 5, 6 ]
						}
					}
                ]
			
		} );		
	});
</script>

<script type="text/javascript">
	$( document ).ready(function() {
		var table = $('#indicador').DataTable( {
			"pageLength": 9000,
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
						extend: 'excelHtml5',
						exportOptions: {
							columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20 ]
						}
					}
                ]
			
		} );		
	});
</script>

<script type="text/javascript">
	$( document ).ready(function() {
		var table = $('#asignar-diligencias').DataTable( {
			"pageLength": 20,
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
			'order': [] 
		} );

		$('#asignar-diligencias tbody').on( 'click', 'tr', function () {
			if ( $(this).hasClass('selected') ) {
				$(this).removeClass('selected');
			}
			else {
				table.$('tr.selected').removeClass('selected');
				$(this).addClass('selected');
			}
		} );
		$('#button').click( function () {
			table.row('.selected').remove().draw( false );
		} );		
	});
</script>
<script type="text/javascript">
	$( document ).ready(function() {
		var table = $('#recep-diligencia').DataTable( {
			"pageLength": 900,
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
						extend: 'excelHtml5',
						exportOptions: {
							columns: [ 0, 1, 2, 3, 4 ]
						}
					}
					/*extend: 'excel',					
					exportOptions: {
						columns: [ 0, 1, 2, 3, 4, 5 ]
					},
					'colvis'*/
                ]
			
		} );		
	});
</script>

<script type="text/javascript">
	
	$(document).ready( function () {
		var table = $('#mis-diligencias').DataTable( {
			"pageLength": 10,
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
						extend: 'excelHtml5',
						exportOptions: {
							columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
						}
					}
					/*extend: 'excel',					
					exportOptions: {
						columns: [ 0, 1, 2, 3, 4, 5 ]
					},
					'colvis'*/
                ]
                
		} ); 
		
	} );

</script>

<script type="text/javascript">
	
	$(document).ready( function () {
		var table = $('#lista-funcionarios').DataTable( {
			"pageLength": 10,
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
						extend: 'excelHtml5',
						exportOptions: {
							columns: [ 0, 1, 2, 3 ]
						}
					}
					/*extend: 'excel',					
					exportOptions: {
						columns: [ 0, 1, 2, 3, 4, 5 ]
					},
					'colvis'*/
                ]
                
		} ); 
		
	} );

</script>