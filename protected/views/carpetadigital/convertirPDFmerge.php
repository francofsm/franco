<!DOCTYPE html>
<html>
<link href="<?php echo Yii::app()->theme->baseUrl;?>/css/bootstrap.min.css" rel="stylesheet">
<head>
	<title><?=$this->pageTitle?></title>
</head>
<body>

<div class="content-fluid">
<div class="row">
	<center><img src="<?=Yii::app()->baseUrl?>/images/loading.gif" id="loading" width="100" height="100" style="width: 100px;height: 100px;top:50%;left:50%"></center>
</div>
</div>

<center>
	<div id="documentPreview" style="width:100%"></div>
</center>

</body>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/js/jquery.min.js"></script>
<script language= javascript type="text/javascript">

	$(function(){
		loading();
	})

	function MiFuncionJS(ruta)
	{
		// En produccion se debe cambiar la direccion ip
		let servidor = '172.17.123.21';
		finLoading();
		$("#documentPreview").html('<embed src="http://'+servidor+'/sia/pdf/'+ruta+'" style="width:100%;height:950px">');
	}

	function loading()
	{	
		let procesar = <?=(isset($_GET["procesar"]) && $_GET["procesar"] <> "" ? $_GET["procesar"] : 0)?>;
		if(procesar == 0)
		{
			setTimeout(function(){
			window.location.href = window.location.href+'&procesar=1';
			},1000);
		}
	}

	function finLoading()
	{
		$("#loading").hide();
	}

</script>
<?php
if(isset($_GET["procesar"]) && $_GET["procesar"]):
ini_set("memory_limit","1024M"); #Memoria limite a usar para el procesamiento de los PDF [Union y Marcadores] si es necesario se puede aumentar
set_time_limit(0); #Tiempo de ejecucion ilimitado en el procesamiento de los PDF
//$ruta1='"C:\docs';
$inicial="";
$fis=Yii::app()->user->getState('fiscalia');

$ruta_base='"\\\\EIVG-VIII\WEB\F'.$fis.'\Digito';

$digito=substr($ruc,11,1);
$valor1=$ruta_base.$digito.chr(92).$ruc.chr(92);

//$valor1_muestra=$ruta_muestra.$digito.chr(92).$ruc.chr(92); ////PROBANDO

#Arreglo que almacenara los marcadores de los pdfs y las rutas
$arrayBookMarks = [];
$rutaSIAU = $_SERVER["DOCUMENT_ROOT"]."/sia/pdf";
$carpetaTemporal = $rutaSIAU."/tmp";

#Si la carpeta temporal [tmp] no existe se crea
if(!is_dir($carpetaTemporal))
{
	mkdir($carpetaTemporal,0644,true);
}

foreach ($model as $model) { 
#Generamos la ruta del archivo segun fiscalia del archivo
$fis = $model->fis_codigo;
$ruta_base='"\\\\EIVG-VIII\WEB\F'.$fis.'\Digito';
$digito=substr($ruc,11,1);
$valor1=$ruta_base.$digito.chr(92).$ruc.chr(92);

$inicioruta= substr($model['gls_ruta'],7,4);
#Extraemos las categorias SAF
$act=$model['tip_actividad'];
$sact=$model['tip_subtipactividad'];
$ssact=$model['tip_subsubtipactividad'];

$actividad = TgActividadsaf::model()->getDocumentosActividadRuc($act,$sact,$ssact);

	if ($inicioruta=='EIVG')
		{
		if ($fis==8)	
			{
			$nombrearchivo=substr($model['gls_ruta'],41);
			$extensionArchivo = strtoupper(pathinfo($nombrearchivo,PATHINFO_EXTENSION));
			
			}

		 else 
		 	{
		 	$nombrearchivo=substr($model['gls_ruta'],43);
		 	$extensionArchivo = strtoupper(pathinfo($nombrearchivo,PATHINFO_EXTENSION));
			
			}
		 }
	else
		{
		$nombrearchivo=substr($model['gls_ruta'],48);
		$extensionArchivo = strtoupper(pathinfo($nombrearchivo,PATHINFO_EXTENSION));
			
		}

		#Guardamos los parametros en el arreglo $arrayBookmarks
		$arrayBookMarks[] = [
		#"Pdf" => $valor1.$nombrearchivo,
		"Pdf" => '"'.$rutaSIAU."/tmp/".$model["cod_carpdig"].'.PDF',
		"Ruta" => Yii::app()->getBaseUrl(),
		"Bookmark" => $model["gls_clasedoc"],
		"NombreDocumento" => $model["gls_nomdoc"],
		"Extension" => $extensionArchivo,
		"TipoActividad" => $model['gls_clasedoc'],
		"GlosaActividad" => $actividad,
		"FechaActividad" => $model["fec_actividad"],
		#"existe" => file_exists(str_replace('"','',utf8_decode(($valor1.$nombrearchivo)))) ? 1 : 0,
		#"existeNombre" => str_replace('"','',utf8_decode($valor1.$nombrearchivo)),
		];

		if($extensionArchivo == "PDF"):
			#file_put_contents($rutaSIAU."/tmp/".$model["cod_carpdig"].".PDF", file_get_contents(str_replace('http://','\\',utf8_decode(($model["gls_ruta"])))));
			file_put_contents($rutaSIAU."/tmp/".$model["cod_carpdig"].".PDF", file_get_contents(str_replace(['http://172.17.123.241','/','http:\\\\EIVG-VIII'],['\\\\EIVG-VIII\WEB','\\','\\\\EIVG-VIII\WEB',],utf8_decode($model["gls_ruta"]))));
		endif;
$valor2=$valor1.$nombrearchivo.'" ';

$inicial = $inicial.$valor2;

}
#echo "<pre>";
#print_r($arrayBookMarks);
#echo "</pre>";

/*
RUC de pruebas
2010050547-K
2100168526-8
2110006557-3

2100693123-2

2000194157-8 <- 189 documentos

2001102328-3
1801190195-2

2100682541-6
2000473503-0

*/

$comando = NULL;
$fileName = $_GET["ruc"]."_CarpetaSIAU.PDF";
$destinoSIAU = $rutaSIAU."/".$fileName;

#Consulta si el archivo existe
if(file_exists($destinoSIAU))
{
	#Si existe eliminamos la version anterior y guardar la nueva
	unlink($destinoSIAU);
}

$gs = '"C:\Program Files\gs\gs9.54.0\bin\gswin64c.exe"';
$comandoGS = '-q -dBATCH -dNOPAUSE -sDEVICE=pdfwrite -sOutputFile='.$destinoSIAU;

$caracterABuscar = ["á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","¤",")","("];
$caracterAReemplazar = ["a","e","i","o","u","A","E","I","O","O","n","N","N","",""];

#Recorremos el nuevo arreglo con los marcadores y concatenamos la estructura del ghostscript
foreach($arrayBookMarks as $value):

#Procesamos solo los archivos PDF
if($value["Extension"] == "PDF"):

#Si es SAF generamos la estructura del marcador correspondiente
if($value["TipoActividad"] == "Documentos SAF")
{
	$glosaSAF = NULL;
	if(isset($value["GlosaActividad"][0]->gls_actividad) && trim($value["GlosaActividad"][0]->gls_actividad) <> "")
	{
		$glosaSAF.= $value["GlosaActividad"][0]->gls_actividad;
	}

	if(isset($value["GlosaActividad"][0]->gls_subtipactividad) && trim($value["GlosaActividad"][0]->gls_subtipactividad) <> "")
	{
		$glosaSAF.= " - ".$value["GlosaActividad"][0]->gls_subtipactividad;
	}

	if(isset($value["GlosaActividad"][0]->gls_subsubtipactividad) && trim($value["GlosaActividad"][0]->gls_subsubtipactividad) <> "")
	{
		$glosaSAF.= " - ".$value["GlosaActividad"][0]->gls_subsubtipactividad;
	}
	
	$comando.=' -c "[/View [/XYZ null null null] /Title ('.str_replace($caracterABuscar,$caracterAReemplazar,$value["Bookmark"]).' - '.str_replace($caracterABuscar,$caracterAReemplazar,$glosaSAF).' - '.date("d-m-Y",strtotime($value["FechaActividad"])).') /OUT pdfmark" -f '.$value["Pdf"].'" ';
}else{
	$comando.=' -c "[/View [/XYZ null null null] /Title ('.str_replace($caracterABuscar,$caracterAReemplazar,$value["Bookmark"]).' - '.str_replace($caracterABuscar,$caracterAReemplazar,$value["NombreDocumento"]).') /OUT pdfmark" -f '.$value["Pdf"].'" ';
}

endif;

endforeach;

#Nombre del archivo temporal que almacenará las instrucciones para eviar el limite de caracteres del MS-DOS
$filenameTXT = $_GET["ruc"].".txt";
#Carpeta de destino del archivo txt generado, RUC.TXT
$destinoTXT = $rutaSIAU."/".$filenameTXT;

#Consulta si el archivo existe
if(file_exists($destinoTXT))
{
	#Si existe eliminamos la version anterior y guardar la nueva
	unlink($destinoTXT);
}

#Se almacena en el archivo de destino txt las instrucciones antes generadas
file_put_contents($destinoTXT, $comandoGS.$comando);

#Ejecutamos la instruccion del archivo txt por consola, el @ permite ejecutar archivos externos
exec($gs." @".$destinoTXT,$salida,$retorno);

#Si retorno es 0, sin errores
if(intval($retorno) == 0)
{
	#Una vez procesado con exito, eliminamos los archivos temporales generados
	foreach ($arrayBookMarks as $key => $value) {
		$tmpRoute = str_replace('"',"",$value["Pdf"]);
		if(file_exists($tmpRoute))
		{
			unlink($tmpRoute);
		}
	}

	#Eliminamos el archivo TXT con las instrucciones
	if(file_exists($destinoTXT))
	{
		unlink($destinoTXT);
	}

	echo "<script>MiFuncionJS('".$fileName."');</script>";
}else{
	$error = NULL;
	$error.=" <div class='alert alert-danger' role='alert'>";
	$error.="Se han encontrado los siguientes errores:<br>";
	foreach($salida as $key => $value):
		$error.=$value."<br>";
		endforeach;
	$error.="</div>";
	echo $error;

	#Eliminamos los archivos temporales generados si hubo un error.
	foreach ($arrayBookMarks as $key => $value) {
		$tmpRoute = str_replace('"',"",$value["Pdf"]);
		if(file_exists($tmpRoute))
		{
			unlink($tmpRoute);
		}
	}

	#Eliminamos el archivo TXT con las instrucciones
	if(file_exists($destinoTXT))
	{
		unlink($destinoTXT);
	}
	echo "<script>finLoading();</script>";
}
endif;

?>
</html>

