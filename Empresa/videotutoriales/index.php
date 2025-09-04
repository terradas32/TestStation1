<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

$nonce = bin2hex(random_bytes(16));

if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../include/Configuracion.php");
}
include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, WI2.2 www.negociainternet.com" />

		<title>Video Tutoriales</title>
	<meta name="title" content="CUESTIONARIO DE PERSONALIDAD LABORAL,EVALUACIÓN DEL DESEMPEÑO,SSESSMENT Y DEVELOPMENT CENTRE,MANAGEMENT AUDIT,EVALUACIÓN 360º,EVALUACIÓN DEL DESEMPEÑO,GESTIÓN POR COMPETENCIAS,ANÁLISIS Y DESCRIPCIÓN DE PUESTOS,CLIMA Y CULTURA,PRUEBAS DE APTITUDES,EJERCICIOS DE SIMULACION DE GESTION,CENTRO PSICOMÉTRICO PARA APLICACIONES DE TEST,CUESTIONARIOS EN MADRID, BARCELONA,ARGENTINA,CHILE" />
	<meta name="KeyWords" content="CUESTIONARIO DE PERSONALIDAD LABORAL,EVALUACIÓN DEL DESEMPEÑO,CENTRO PSICOMÉTRICO PARA APLICACIONES DE TEST,CUESTIONARIOS EN MADRID, BARCELONA,ARGENTINA,CHILE" />
	<meta name="description" content="People Experts le ofrece las mejores SOLUCIONES para la gestión de las personas en el ámbito laboral. Contamos con una serie de técnicas psicometricas, de alta fiabilidad y validez, especialmente diseñadas por nuestro departamento de I+D+i y que constituyen un soporte especialmente útil para la toma de decisiones a nivel de Selección, Evaluación y Desarrollo." />
	<meta name="GOOGLEBOT" content="INDEX,FOLLOW,ALL" />
	<meta name="robots" content="index,follow,all" />
	<meta name="distribution" content="global" />
	<meta name="page-topic" content="PERSONALIDAD LABORAL,EVALUACIÓN DEL DESEMPEÑO,EVALUACIÓN 360º,GESTIÓN POR COMPETENCIAS,ANÁLISIS DE PUESTOS,ENCUESTA DE CLIMA,APTITUDES,CUESTIONARIOS ON LINE" />
	<meta name="Classification" content="Cuestionario de Personalidad Laboral,Evaluación del desempeño,Selección de personal,Management Audit,Evaluación 360º,Gestión por Competencias,Encuesta de Clima,Centro Psicométrico para aplicaciones de test y Cuestionarios en Madrid,Barcelona,Argentina,Chile" />
	<meta name="REVISIT" content="4 days"/>
	<meta name="revisit-after" content="4 days" />
	<meta name="Identifier-URL" content="http://www.test-station.com" />
	<meta name="DC.Identifier" content="http://www.test-station.com" />
	<meta name="language" content="es" />
	<meta http-equiv="pragma" content="cache" />
	<meta name="author" content="Negocia Internet, S.L.L. Diseño web" />
	<meta name="copyright" content="(c) Negocia Internet, S.L.L." />
	<meta http-equiv="reply-to" content="info@psicologosempresariales.es" />
	<meta name="origen" content="People Experts" />
	<meta name="organization" content="Psicólogos Empresariales y Asoc. S.A." />
	<meta name="locality" content="Madrid, España" />
	<meta name="lang" content="es" />
	<link rel="shortcut icon" href="../favicon.ico" />
	<link rel="stylesheet" href="../estilos/estilos-comunes.css" type="text/css" />
	<link rel="stylesheet" href="../estilos/estilos-candidato.css" type="text/css" />
<style type="text/css">
body,html{width:auto; height:auto; margin: 0; padding: 0; }
#pagina {margin: 1% auto 20px auto;}
#videotutoriales{max-width:850px; max-height:580px; width: 100%; height: 100%; text-align: left;}
#videotutoriales ul li {margin: 3px 0px 0px 0px;padding:0px 6px;list-style: inside;font-size: 130%;}
#videotutoriales ul li a.enlacesidiomas{text-decoration: none;font-weight: normal;color: #000;}
#videotutoriales ul li a.enlacesidiomas:hover{text-decoration: underline;font-weight: normal;color: #1491ED;}
#cuerpo h2 {font-size: 2.7em;}
</style>

</head>
<body onload="autoComplete();">
<div id="pagina">
    <div id="head" class="empresa">
        <div class="logo">
        <img src="../graf/logo.jpg" alt="People Experts" title="People Experts" />
        </div><!-- Fin de logo -->
    <h1><?php echo constant("STR_EMPRESA");?></h1>
    </div><!-- Fin de la cabecera -->
    <div id="cuerpo">
        <h2><?php echo constant("STR_DESC_MENU_134");?></h2>
				<div id="videotutoriales">
					<ul>
					<?php
					$the_array = Array();
					$handle = opendir('./' . $sLang);
					while (false !== ($file = readdir($handle))) {
					   if ($file != "." && $file != ".." && $file != "index.php") {
					   $the_array[] = $file;
					   }
					}
					closedir($handle);
					sort ($the_array);

					foreach($the_array as $val){
						$info = pathinfo($val);
						echo "<li><a class=\"enlacesidiomas\" target=\"_blank\" href=\"".$sLang ."/" . rawurlencode($val) . "\">" . $info['filename'] . "</a></li>";
					}
					?>
					</ul>
				</div>


    </div><!-- Fin de cuerpo -->
</div><!-- Fin de la pagina -->
</body>
</html>
