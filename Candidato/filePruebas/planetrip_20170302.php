<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('../include/Configuracion.php');
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . 'adodb-pager.inc.php');

include_once ('include/conexion.php');

	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . "SeguridadCandidatos.php");

//Tiene que recoger todos los datos de la prueba, así como los comunes

$_usuario=$_POST["usuario"];
if (empty($_usuario)){
	session_start();
	$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
	echo '<script language="javascript" type="text/javascript">top.location.replace("' . constant("HTTP_SERVER") . 'msg.php");</script>';
	exit;
}

?>
<!--
	NOTES:
	1. All tokens are represented by '$' sign in the template.
	2. You can write your code only wherever mentioned.
	3. All occurrences of existing tokens will be replaced by their appropriate values.
-->

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>planetrip</title>
<!-- write your code here -->


<script src="https://code.createjs.com/createjs-2015.11.26.min.js"></script>
<script src="https://code.jquery.com/jquery-1.3.2.min.js" type="text/javascript"></script>
<!--  <script src="planetrip.js.php?1462963789854"></script> -->
<script>
<?php
	include_once("planetrip.js.php");
?>
var canvas, stage, exportRoot;
function init() {
	// --- write your JS code here ---

	canvas = document.getElementById("canvas");
	images = images||{};
	ss = ss||{};

	var loader = new createjs.LoadQueue(false);
	loader.addEventListener("fileload", handleFileLoad);
	loader.addEventListener("complete", handleComplete);
	loader.loadFile({src:"images/planetrip_atlas_P_.json?1462963789854", type:"spritesheet", id:"planetrip_atlas_P_"}, true);
	loader.loadFile({src:"images/planetrip_atlas_NP_.json?1462963789854", type:"spritesheet", id:"planetrip_atlas_NP_"}, true);
	loader.loadManifest(lib.properties.manifest);
}

function handleFileLoad(evt) {
	if (evt.item.type == "image") { images[evt.item.id] = evt.result; }
}

function handleComplete(evt) {
	var queue = evt.target;
	ss["planetrip_atlas_P_"] = queue.getResult("planetrip_atlas_P_");
	ss["planetrip_atlas_NP_"] = queue.getResult("planetrip_atlas_NP_");
	exportRoot = new lib.planetrip();

	stage = new createjs.Stage(canvas);
	stage.addChild(exportRoot);
	stage.update();
	stage.enableMouseOver();

	createjs.Ticker.setFPS(lib.properties.fps);
	createjs.Ticker.addEventListener("tick", stage);
}

</script>

<!-- write your code here -->

</head>
<body onload="init();" style="background-color:#D4D4D4;margin:0px;">
	<canvas id="canvas" width="1124" height="600" style="background-color:#FFFFFF"></canvas>
</body>
</html>
