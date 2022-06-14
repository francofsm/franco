<?php

require_once '../start/protected/vendor/autoload.php';
use iio\libmergepdf\Merger;




//$file5="\\\\172.17.123.241\\WEB\\F803\\Digito0\\2100241271-0\\2100241271-0.pdf";

//$file6="\\\\172.17.123.241\\WEB\\F803\\Digito2\\2001301209-2\\2001301209-2De-AudienciasSTEBAN-ENRIQUE-CORDOVA-FORCAEL156167663-75436089.pdf";

//$documentos = [$file5, $file6];

# Crear el "combinador"
$combinador = new Merger;

# Agregar archivo en cada iteración
foreach ($model as $model) {

	$array_file = explode("/", $model['gls_ruta']);	

	if( isset($array_file[10]) ){
		$ruta = "\\\\EIVG-VIII\\WEB\\".$array_file[3]."\\".$array_file[4]."\\".$array_file[5]."\\".$array_file[6]."\\".$array_file[7]."\\".$array_file[8]."\\".$array_file[9]."\\".$array_file[10];
	}
	if( isset($array_file[9]) ){
		$ruta = "\\\\EIVG-VIII\\WEB\\".$array_file[3]."\\".$array_file[4]."\\".$array_file[5]."\\".$array_file[6]."\\".$array_file[7]."\\".$array_file[8]."\\".$array_file[9];
	}
	if( isset($array_file[8]) ){
		$ruta = "\\\\EIVG-VIII\\WEB\\".$array_file[3]."\\".$array_file[4]."\\".$array_file[5]."\\".$array_file[6]."\\".$array_file[7]."\\".$array_file[8];
	}
	if( isset($array_file[7]) ){
		$ruta = "\\\\EIVG-VIII\\WEB\\".$array_file[3]."\\".$array_file[4]."\\".$array_file[5]."\\".$array_file[6]."\\".$array_file[7];
	}
	elseif( isset($array_file[6]) ){
		$ruta = "\\\\EIVG-VIII\\WEB\\".$array_file[3]."\\".$array_file[4]."\\".$array_file[5]."\\".$array_file[6];
	}
	elseif( isset($array_file[5]) ){
		$ruta = "\\\\EIVG-VIII\\WEB\\".$array_file[3]."\\".$array_file[4]."\\".$array_file[5];
	}
	elseif( isset($array_file[4]) ){
		$ruta = "\\\\EIVG-VIII\\WEB\\".$array_file[3]."\\".$array_file[4];
	}
	elseif( isset($array_file[3]) ){
		$ruta = "\\\\EIVG-VIII\\WEB\\".$array_file[3];
	}

	
    $combinador->addFile($ruta);
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