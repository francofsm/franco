<?php
$ruta = Ruta_Absoluta('"C:\Apache24\htdocs\tramitacion\carpetas_digitalizadas',$ruc);
$ruta_dcto = Ruta_Absoluta('"C:\Apache24\htdocs\tramitacion\documentos',$ruc);


echo $ruta;


$execString= $ruta.chr(92).$ruc.'.pdf" '.$ruta_dcto.chr(92).$nombreFichero.'"';
$fileName=$ruta.chr(92).$ruc.'-'.time().'.pdf"';
//$pdftk= '"C:\Apache24\htdocs\tramitacion\pdftk\pdftk.exe"';
$pdftk= '"C:\Program Files (x86)\PDFtk\bin\Pdftk.exe"';


//write_log("subir_documento","INICIO CONTEO PAGINAS PDFTK INFO - ",$ruc." - ".$_SESSION['cod_fiscalia']);

$pagina = Cant_Paginas( $ruc );

//write_log("subir_documento","FIN CONTEO PAGINAS PDFTK INFO - RESULTADO: ".$pagina,$ruc." - ".$_SESSION['cod_fiscalia']);

//write_log("subir_documento","INICIO MERGE PDFTK - ",$ruc." - ".$_SESSION['cod_fiscalia']);
//se agrega el archivo subido a la carpeta digital
$pdfMerge = exec("pdftk $execString cat output $fileName");

//write_log("subir_documento","FIN MERGE PDFTK - ",$ruc." - ".$_SESSION['cod_fiscalia']);

//write_log("subir_documento","INICIO DUMP PDFTK - ",$ruc." - ".$_SESSION['cod_fiscalia']);
$pdf_info = exec("pdftk $fileName dump_data output $ruta_info");

//write_log("subir_documento","FIN DUMP PDFTK - ",$ruc." - ".$_SESSION['cod_fiscalia']);

$mensaje = $nombre_dcto;
$pagina = $pagina + 1;
$agregar="BookmarkBegin
BookmarkTitle: ".$mensaje."
BookmarkLevel: 1
BookmarkPageNumber: ".$pagina;
$fp = fopen($nombreDirectorio_info."/".$ruc.'-'.$time.'.info', "a");
fputs($fp, $agregar);
fclose($fp);

//write_log("subir_documento","INICIO RESPALDO A OLD - ",$ruc." - ".$_SESSION['cod_fiscalia']);

$move = exec('MOVE /Y '.$ruta.chr(92).$ruc.'.pdf" '.$ruta.chr(92).'old'.chr(92).$ruc.'.pdf"' );

//Agregamos
$ruta_nueva = $ruta.chr(92).$ruc.'.pdf"';

//write_log("subir_documento","INICIO UPDATE INFO PDFTK - ",$ruc." - ".$_SESSION['cod_fiscalia']);

$pdf_info = exec("pdftk1 $fileName update_info $ruta_info output  $ruta_nueva");

//write_log("subir_documento","FIN UPDATE INFO PDFTK - ",$ruc." - ".$_SESSION['cod_fiscalia']);
//echo "$pdftk $fileName update_info $ruta_info output  $ruta_nueva";

//Elininamos el archivo de INFO

$move = exec('MOVE /Y '.$fileName.' '.$ruta.chr(92).'temporales'.chr(92).$ruc.'-'.$time.'.pdf"' );


$del = exec("del $ruta_info");

?>
