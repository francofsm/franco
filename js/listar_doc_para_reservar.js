$( document ).ready(function() {
	var table = $('#listar_para_reservar').DataTable( {					
        'scrollY': '300px',
					'scrollCollapse': true,
					'paging': false,
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
					}
				} );
			});