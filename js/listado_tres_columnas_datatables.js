$( document ).ready(function() {
				var table = $('#listado_tres_columnas').DataTable( {					
			        
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
				                columns: [ 0, 1, 2]
				            }
						},
						{
							extend: 'excelHtml5',
							exportOptions: {
								columns: [ 0, 1, 2]
							}
						}
	                ]

				} );
			}); 