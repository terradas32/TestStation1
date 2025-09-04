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
						<div style="width:80%;text-align: justify;">
							<p>Denominación&nbsp;Social:&nbsp;PSICOLOGOS EMPRESARIALES y ASOC. S.A.</p>
							<p>Nombre Comercial:&nbsp;PEOPLE EXPERTS</p>
							<p>Domicilio Social:&nbsp;Pº Pintor Rosales, 44 1º Dcha, 28008 Madrid, España.</p>
							<p>CIF / NIF:&nbsp;A – 78301934</p>
							<p>Teléfono:&nbsp;(+34) 902 99 85 34</p>
							<p>Fax:&nbsp;(+34) 91 559 12 30</p>
							<p>e-Mail:&nbsp;info@people-experts.com</p>
							<p>Inscrita en el Registro Mercantil de Madrid, tomo 10.641, libro 0, sección 8, hoja M-168579, CIF: A78301934</p>
							<p>Nombre de dominio:&nbsp;test-station.com</p>
							
							<p></p>
							<p><b>OBJETO</b></p>
							<p></p>
							<p>El prestador, responsable del sitio web, pone a disposición de los usuarios el presente documento con el que pretende dar cumplimiento a las obligaciones dispuestas en la Ley 34/2002, de Servicios de la Sociedad de la Información y del Comercio Electrónico (LSSI-CE), así como informar a todos los usuarios del sitio web respecto a cuáles son las condiciones de uso del sitio web.</p>
							<p></p>
							<p>Toda persona que acceda a este sitio web asume el papel de usuario, comprometiéndose a la observancia y cumplimiento riguroso de las disposiciones aquí dispuestas, así como a cualesquiera otra disposición legal que fuera de aplicación.</p>
							<p></p>
							<p>El prestador se reserva la facultad de efectuar, en cualquier momento y sin necesidad de previo aviso, modificaciones respecto a la información contenida en su web o en la configuración y presentación de éste.</p>
							<p></p>
							<p><b>Responsabilidad</b></p>
							<p></p>
							<p>Tanto el acceso a este web como el uso que pueda hacerse de la información contenida en el mismo es de la exclusiva responsabilidad de quien lo realiza. Por ello, el prestador no responderá de ninguna consecuencia, daño o perjuicio que pudieran derivarse de dicho acceso o uso de la información.</p>
							<p></p>
							<p>El sitio web del prestador puede utilizar cookies (pequeños archivos de información que el servidor envía al ordenador de quien accede a la página) para llevar a cabo determinadas funciones que son consideradas imprescindibles para el correcto funcionamiento y visualización del sitio. Las cookies utilizadas en el sitio web tienen, en todo caso, carácter temporal con la única finalidad de hacer más eficaz su transmisión ulterior y desaparecen al terminar la sesión del usuario. En ningún caso se utilizarán las cookies para recoger información de carácter personal.</p>
							<p></p>
							<p>El prestador sólo responderá de los errores u omisiones que figuren en la información de su titularidad, comprometiéndose respecto al resto de la información a realizar sus mejores esfuerzos para que los titulares de la misma procedan a su actualización. Los derechos derivados de el prestador en sus diferentes modalidades pertenecen a Psicólogos Empresariales S.A., por lo que su utilización no autorizada por terceros dará lugar a las responsabilidades legalmente establecidas.</p>
							<p></p>
							<p>Las eventuales referencias que se hagan en el web de a cualquier producto, servicio, proceso, enlace, hipertexto o cualquier otra información en la que se utilice la marca, el nombre comercial o el nombre del fabricante, suministrador, etc., que sean titularidad de terceros no constituirá ni implicará respaldo, patrocinio o recomendación alguna por parte de Psicólogos Empresariales S.A.</p>
							<p></p>
							<p>El prestador no se hace responsable de la información y contenidos almacenados, a título enunciativo pero no limitativo, en foros, chat´s, generadores de blogs, comentarios, redes sociales o cualesquiera otro medio que permita a terceros publicar contenidos de forma independiente en la página web del prestador. No obstante y en cumplimiento de lo dispuesto en el art. 11 y 16 de la LSSI-CE, el prestador se pone a disposición de todos los usuarios, autoridades y fuerzas de seguridad, y colaborando de forma activa en la retirada o en su caso bloqueo de todos aquellos contenidos que pudieran afectar o contravenir la legislación nacional, o internacional, derechos de terceros o la moral y el orden público. En caso de que el usuario considere que existe en el sitio web algún contenido que pudiera ser susceptible de esta clasificación, se ruega lo notifique de forma inmediata al administrador del sitio web.</p>
							<p></p>
							<p>Este sitio web ha sido revisado y probado para que funcione correctamente. En principio, puede garantizarse el correcto funcionamiento los 365 días del año, 24 horas al día. No obstante, el prestador no descarta la posibilidad de que existan ciertos errores de programación, o que acontezcan causas de fuerza mayor, catástrofes naturales, huelgas, o circunstancias semejantes que hagan imposible el acceso a la página web.</p>
							<p></p>
							<p><b>Protección de datos personales&nbsp;</b></p>
							<p><b>&nbsp;</b></p>
							<p><b>POLÍTICA DE PRIVACIDAD</b></p>
							<p></p>
							<p>PSICOLOGOS EMPRESARIALES y ASOC. S.A. le informa de que los datos que usted introduzca a través de nuestra página web ubicada en www.test-station.com serán guardados por PEOPLE EXPERTS con la más estricta confidencialidad.</p>
							<p></p>
							<p>A cualquier interesado que lo solicite se le facilitará su derecho a acceder a la información que sobre sus datos exista en el fichero, así como su derecho a rectificarlos, cancelarlos u oponerse a ellos, tal y como señala la Ley Orgánica 15/1999 de 13 de Diciembre de Protección de Datos de Carácter Personal.</p>
							<p></p>
							<p>Los datos que Ud. facilita podrán ser utilizados por esta compañía para el desarrollo de cualesquiera de las actividades propias de su negocio del sector de la prestación de servicios de consultoría, a los efectos de gestionar, administrar y prestarle los servicios que solicite.</p>
							<p></p>
							<p>Asimismo, su aceptación de la presente Política de Privacidad supone la prestación de su consentimiento expreso para que PEOPLE EXPERTS pueda remitirle comunicaciones publicitarias o promocionales por correo electrónico u otro medio de comunicación equivalente, en los términos establecidos por la Ley 34/2002, de Servicios de la Sociedad de la Información y de Comercio Electrónico. En el supuesto de no estar interesado en recibir este tipo de comunicaciones puede dirigirse a PEOPLE EXPERTS, a la dirección: Paseo del Pintor Rosales, 44 1º dcha. 28008 Madrid, o al correo electrónico info@people-experts.com, manifestando su voluntad.</p>
							<p><b>&nbsp;</b></p>
							<p><b>Propiedad Intelectual e industrial&nbsp;</b></p>
							<p></p>
							<p>El sitio web, incluyendo a título enunciativo pero no limitativo su programación, edición, compilación y demás elementos necesarios para su funcionamiento, los diseños, logotipos, texto y/o gráficos son propiedad del prestador o en su caso dispone de licencia o autorización expresa por parte de los autores. Todos los contenidos del sitio web se encuentran debidamente protegidos por la normativa de propiedad intelectual e industrial, así como inscritos en los registros públicos correspondientes.</p>
							<p></p>
							<p>Independientemente de la finalidad para la que fueran destinados, la reproducción total o parcial, uso, explotación, distribución y comercialización, requiere en todo caso de la autorización escrita previa por parte del prestador. Cualquier uso no autorizado previamente por parte del prestador será considerado un incumplimiento grave de los derechos de propiedad intelectual o industrial del autor.</p>
							<p></p>
							<p>Los diseños, logotipos, texto y/o gráficos ajenos al prestador y que pudieran aparecer en el sitio web, pertenecen a sus respectivos propietarios, siendo ellos mismos responsables de cualquier posible controversia que pudiera suscitarse respecto a los mismos. En todo caso, el prestador cuenta con la autorización expresa y previa por parte de los mismos.</p>
							<p>El prestador autoriza/no autoriza expresamente a que terceros puedan redirigir directamente a los contenidos concretos del sitio web, debiendo en todo caso redirigir al sitio web principal del prestador.</p>
							<p></p>
							<p>El prestador reconoce a favor de sus titulares los correspondientes derechos de propiedad industrial e intelectual, no implicando su sola mención o aparición en el sitio web la existencia de derechos o responsabilidad alguna del prestador sobre los mismos, como tampoco respaldo, patrocinio o recomendación por parte del mismo.</p>
							<p></p>
							<p>Para realizar cualquier tipo de observación respecto a posibles incumplimientos de los derechos de propiedad intelectual o industrial, así como sobre cualquiera de los contenidos del sitio web, puede hacerlo a través del siguiente correo electrónico.</p>
							<p></p>
							<p></p>
							<p><b>Ley Aplicable y Jurisdicción&nbsp;</b></p>
							<p></p>
							<p>Para la resolución de todas las controversias o cuestiones relacionadas con el presente sitio web o de las actividades en él desarrolladas, será de aplicación la legislación española, a la que se someten, con renuncia expresa a cualquier otro fuero, siendo competentes para la resolución de todos los conflictos derivados o relacionados con su uso los Juzgados y Tribunales de la ciudad de Madrid (España).</p>
							<p></p>
							<p><p></p>
							<p><b>Comunicaciones a través de correo electrónico</b></p>
							<p></p>
							<p>Todos los mensajes y, en su caso, los ficheros anexos son confidenciales y propiedad de PSICOLOGOS EMPRESARIALES y ASOC. S.A., especialmente en lo que respecta a los datos personales, y se dirigen exclusivamente al destinatario referenciado. Si usted no lo es y lo ha recibido por error o tiene conocimiento del mismo por cualquier motivo, le rogamos que nos lo comunique por este medio y proceda a destruirlo o borrarlo, y que en todo caso se abstenga de utilizar, reproducir, alterar, archivar o comunicar a terceros el presente mensaje y ficheros anexos, todo ello bajo pena de incurrir en responsabilidades legales. El emisor no garantiza la integridad, rapidez o seguridad del presente correo, ni se responsabiliza de posibles perjuicios derivados de la captura, incorporaciones de virus o cualesquiera otras manipulaciones efectuadas por terceros<em>.</em></p>
							</p>
						</div>
				</div><!-- Fin de accesos -->
		</div><!-- Fin de cuerpo -->
</div><!-- Fin de la pagina -->
</body>
</html>
<?php
 ?>
