<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/dataTables/jquery.js"></script>
<div class="pagina">

<?php



require_once '../start/protected/vendor/autoload.php';
use iio\libmergepdf\Merger;


$documentos = ["../start/tmp/2100241252-4.pdf", "../start/tmp/documento.pdf"];

# Crear el "combinador"
$combinador = new Merger;

# Agregar archivo en cada iteración
foreach ($documentos as $documento) {
    $combinador->addFile($documento);
}

# Y combinar o unir
$salida = $combinador->merge();

$nombreArchivo = "combinado.pdf";

header("Content-type:application/pdf");
header("Content-disposition: inline; filename=$nombreArchivo");
header("content-Transfer-Encoding:binary");
header("Accept-Ranges:bytes");
# Imprimir salida luego de encabezados
echo $salida;

/*
    Aquí puedes hacer más cosas pero asegúrate
    de no imprimir absolutamente nada; en este caso
    pongo exit para terminar el script inmediatamente
*/
exit;

?>




</div>