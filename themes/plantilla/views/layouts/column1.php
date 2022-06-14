<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="container" style="padding-top:0">
	<div class="row-fluid">
		<div class="span12">
			<?php echo $content; ?>
		</div>
	</div>
</div><!-- content -->
<?php $this->endContent(); ?>

<script type="text/javascript">
	$( document ).ready(function() {
		var table = $('#recep-parte').DataTable( {
			"pageLength": 50,
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
			'order': [] 
		} );		
	});
</script>