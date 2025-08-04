<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


require_once('include/Configuracion.php');
include_once('include/Idiomas.php');

define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
include(constant("DIR_ADODB") . 'adodb.inc.php');
require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");

include_once ('include/conexion.php');

?>
<!doctype html>
<html lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, WI2.2 www.negociainternet.com" />
<?php include('include/metatags.php');?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos-comunes.css" type="text/css" />
	<link rel="stylesheet" href="estilos/estilos-candidato.css" type="text/css" />
	 <script src="codigo/common.js"></script>
	 <script src="codigo/codigo.js"></script>
	 <script src="codigo/comun.js"></script>
	 <script src="codigo/noback.js"></script>
	 <script src="codigo/jQuery1.4.2.js"></script>
</head>
<body >

<div >
		<div id="head" class="candidato" style="background-image: none !important;position: absolute;width: 65%; height: auto; z-index: 15; top: 10%; left: 30%; margin: -100px 0 0 -150px;">
				<div class="logo">
					<a href="index.php" title="Inicio"><img src="graf/logo.jpg" alt="#" title="#" /></a>
				</div><!-- Fin de logo -->
		</div>
		<br />
		<!-- Fin de la cabecera -->
		<div id="cuerpo">
				<div id="aviso" class="acc_cand" style="position: absolute;width: 65%; height: auto; z-index: 15; top: 30%; left: 30%; margin: -100px 0 0 -150px;">
						<h2 ><?php echo constant("STR_AVISO_LEGAL");?></h2>
					<div class="textos">
					<h3 class="expSeccion">PSICOLOGOS EMPRESARIALES y ASOCIADOS, S.A.,  Pº Pintor Rosales, 44 1º Dcha, 28008 Madrid, España.</h3>
					<p>Inscrita en el Registro Mercantil de Madrid, tomo 10.641, libro 0, sección 8, hoja M-168579, CIF: A78301934</p>
					<p>Todos los derechos reservados</p>
					<p>1. Los derechos de propiedad intelectual del web psicologosempresariales.es, su código fuente, diseño, estructura de navegación, bases de datos y los distintos elementos en él contenidos son titularidad de PSICOLOGOS EMPRESARIALES y ASOCIADOS, S.A. a quien corresponde el ejercicio exclusivo de los derechos de explotación de los mismos en cualquier forma y, en especial, los derechos de reproducción, distribución, comunicación pública y transformación.</p>
					<p>2. Estas condiciones generales regulan el acceso y utilización del sitio web psicologosempresariales.es que PSICOLOGOS EMPRESARIALES y ASOC. S.A pone gratuitamente a disposición de los usuarios de Internet. El acceso al mismo implica su aceptación sin reservas. La utilización de determinados servicios ofrecidos en este sitio se regirá además por las condiciones particulares previstas en cada caso, las cuales se entenderán aceptadas por el mero uso de tales servicios.</p> 
					<p>3. Se autoriza la visualización, impresión y descarga parcial del contenido de la web sólo y exclusivamente si concurren las siguientes condiciones:</p>
					<ul class="listas"> 
						<li>Que sea compatible con los fines de la web psicologosempresariales.es.</li>
						<li>Que se realice con el exclusivo ánimo de obtener la información contenida para uso personal y privado. Se prohíbe expresamente su utilización con fines comerciales o para su distribución, comunicación pública, transformación o descompilación.</li> 
						<li>Que ninguno de los contenidos relacionados en esta web sean modificados de manera alguna.</li>
						<li>Que ningún gráfico, icono o imagen disponible en esta web sea utilizado, copiado o distribuido separadamente del texto o resto de imágenes que lo acompañan.</li>
					</ul>
					<p>4. PSICOLOGOS EMPRESARIALES y ASOCIADOS, S.A. se reserva la facultad de efectuar, en cualquier momento y sin necesidad de previo aviso, modificaciones y actualizaciones de la información contenida en su web, de la configuración y presentación de éste y de las condiciones de acceso.</p>
					<p>5. PSICOLOGOS EMPRESARIALES y ASOCIADOS, S.A. no garantiza la inexistencia de interrupciones o errores en el acceso al web, en su contenido, ni que éste se encuentre actualizado, aunque desarrollará sus mejores esfuerzos para, en su caso, evitarlos, subsanarlos o actualizarlos.</p>
					<p>6. Tanto el acceso a este web como el uso que pueda hacerse de la información contenida en el mismo es de la exclusiva responsabilidad de quien lo realiza. PSICOLOGOS EMPRESARIALES y ASOCIADOS, S.A.  no responderá de ninguna consecuencia, daño o perjuicio que pudieran derivarse de dicho acceso o uso de la información. PSICOLOGOS EMPRESARIALES y ASOCIADOS, S.A.  no se hace responsable de los posibles errores de seguridad que se puedan producir ni de los posibles daños que puedan causarse al sistema informático del usuario (hardware y software), los ficheros o documentos almacenados en el mismo, como consecuencia de la presencia de virus en el ordenador del usuario utilizado para la conexión a los servicios y contenidos de la web, de un mal funcionamiento del navegador o del uso de versiones no actualizadas del mismo.</p>
					<p>7. PSICOLOGOS EMPRESARIALES y ASOCIADOS, S.A. no asume responsabilidad alguna derivada de la concesión o contenidos de los enlaces de terceros a los que se hace referencia en la web, ni garantiza la ausencia de virus u otros elementos en los mismos que puedan producir alteraciones en el sistema informático (hardware y software), los documentos o los ficheros del usuario, excluyendo cualquier responsabilidad por los daños de cualquier clase causados al usuario por este motivo.</p>
					<p>8. PSICOLOGOS EMPRESARIALES y ASOCIADOS, S.A. es titular de los derechos de propiedad industrial referidos a sus productos y servicios, y específicamente de los relativos a la marca registrada " PSICOLOGOS EMPRESARIALES". Respecto a las citas de productos y servicios de terceros PSICOLOGOS EMPRESARIALES y ASOCIADOS, S.A reconoce a favor de sus titulares los correspondientes derechos de propiedad industrial e intelectual, no implicando su sola mención o aparición en el web la existencia de derechos o responsabilidad alguna de PSICOLOGOS EMPRESARIALES sobre los mismos, como tampoco respaldo, patrocinio, recomendación por parte de PSICOLOGOS EMPRESARIALES y ASOCIADOS, S.A.</p>
					<p>9. La utilización no autorizada de la información contenida en esta web, su reventa, así como la lesión de los derechos de Propiedad Intelectual o Industrial de PSICOLOGOS EMPRESARIALES y ASOCIADOS, S.A., dará lugar a las responsabilidades legalmente establecidas.</p> 
					<p>10. Todo enlace de terceros a esta web debe serlo a su página principal o de entrada.</p>
					<p>11. PSICOLOGOS EMPRESARIALES y ASOCIADOS, S.A. y el usuario, con renuncia expresa a cualquier otro fuero, se someten al de los juzgados y tribunales del domicilio del usuario para cualquier controversia que pudiera derivarse del acceso a esta web. En el caso de que el usuario tenga su domicilio fuera de España, PSICOLOGOS EMPRESARIALES y ASOCIADOS, S.A. y el usuario se someten, con renuncia expresa a cualquier otro fuero, a los juzgados y tribunales de la ciudad de Madrid (España).</p>	
					</div>	
				</div> <!-- Fin de accesos -->
		</div><!-- Fin de cuerpo -->
</div><!-- Fin de la pagina -->
</body>
</html>
<?php
 ?>
