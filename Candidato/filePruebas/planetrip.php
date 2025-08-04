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
function cierreventana()
{
    self.close();
}
function pasarvariable(finalizado){

			$.post("<?php echo $_POST["ruta"];?>grabaprueba1.php",{

				ruta:"<?php echo $_POST["ruta"];?>",

				proceso:"<?php echo $_POST["proceso"];?>",
				convocatoria:"<?php echo $_POST["convocatoria"];?>",
				sala:"<?php echo $_POST["sala"];?>",
				prueba:"<?php echo $_POST["prueba"];?>",
				usuario:"<?php echo $_POST["usuario"];?>",
				pantalla:pantalla,
				segundos:segundos,
				minutos:minutos,
				segundos2:segundos2,
				minutos2:minutos2,
				campolibre:campolibre,
       			finalizado:finalizado,

				c3item2d3bj1 : c3item2d3bj1,
				c3item2d3bk0 : c3item2d3bk0,
				c3item2d3bl0 : c3item2d3bl0,
				c3item2d3bm0 : c3item2d3bm0,
				c3item2d3bh0 : c3item2d3bh0,
				c3item2d3bi0 : c3item2d3bi0,
				c3item2d3ak0 : c3item2d3ak0,
				c3item2d3al0 : c3item2d3al0,
				c3item2d3am0 : c3item2d3am0,
				c3item2d3an0 : c3item2d3an0,
				c3item2d3bf0 : c3item2d3bf0,
				c3item2d3bg0 : c3item2d3bg0,
				c3item1d1d1  : c3item1d1d1,
				c3item1d1e0  : c3item1d1e0,
				c3item2d3af0 : c3item2d3af0,
				c3item2d3ag0 : c3item2d3ag0,
				c3item2d3ah0 : c3item2d3ah0,
				c3item2d3ai0 : c3item2d3ai0,
				c3item2d3aj0 : c3item2d3aj0,
				c3item1d1a0  : c3item1d1a0,
				c3item1d1b1  : c3item1d1b1,
				c3item1d1c1  : c3item1d1c1,
				c3item2d3bn0 : c3item2d3bn0,
				c3item2d3cf1 : c3item2d3cf1,
				c3item2d3cg0 : c3item2d3cg0,
				c3item2d3ch0 : c3item2d3ch0,
				c3item2d3ci0 : c3item2d3ci0,
				c3item2d3cj0 : c3item2d3cj0,
				c3item2d3ck0 : c3item2d3ck0,
				c3item2d3cl0 : c3item2d3cl0,
				c3item2d3cm0 : c3item2d3cm0,
				c3item2d3cn1 : c3item2d3cn1,
				c3item2d3df0 : c3item2d3df0,
				c3item2d3dg0 : c3item2d3dg0,
				c3item2d3dh1 : c3item2d3dh1,
				c3item2d3di0 : c3item2d3di0,
				c3item2d3dj0 : c3item2d3dj0,
				c3item2d3dk0 : c3item2d3dk0,
				c3item2d3dl1 : c3item2d3dl1,
				c3item2d3dm0 : c3item2d3dm0,
				c3item2d3dn0 : c3item2d3dn0,
				c3item2d3ef0 : c3item2d3ef0,
				c3item2d3eg0 : c3item2d3eg0,
				c3item2d3eh0 : c3item2d3eh0,
				c3item2d3ei0 : c3item2d3ei0,
				c3item2d3ej0 : c3item2d3ej0,
				c3item2d3ek0 : c3item2d3ek0,
				c3item2d3el0 : c3item2d3el0,
				c3item2d3em0 : c3item2d3em0,
				c3item2d3en0 : c3item2d3en0,
				c3item3d1a1  : c3item3d1a1,
				c3item3d1b1  : c3item3d1b1,
				c3item3d1c0  : c3item3d1c0,
				c3item3d4d   : c3item3d4d,
				c3item3d1e0  : c3item3d1e0,
				c3item4d3af0 : c3item4d3af0,
				c3item4d3ag1 : c3item4d3ag1,
				c3item4d3ah0 : c3item4d3ah0,
				c3item4d3ai0 : c3item4d3ai0,
				c3item4d3aj0 : c3item4d3aj0,
				c3item4d4ak  : c3item4d4ak,
				c3item4d3al0 : c3item4d3al0,
				c3item4d4am  : c3item4d4am,
				c3item4d3an0 : c3item4d3an0,
				c3item4d3bf0 : c3item4d3bf0,
				c3item4d3bg0 : c3item4d3bg0,
				c3item4d3bh0 : c3item4d3bh0,
				c3item4d4bi  : c3item4d4bi,
				c3item4d3bj0 : c3item4d3bj0,
				c3item4d3bk0 : c3item4d3bk0,
				c3item4d3bl0 : c3item4d3bl0,
				c3item4d4bm  : c3item4d4bm,
				c3item4d3bn1 : c3item4d3bn1,
				c3item4d3cf0 : c3item4d3cf0,
				c3item4d3cg0 : c3item4d3cg0,
				c3item4d3ch0 : c3item4d3ch0,
				c3item4d3ci0 : c3item4d3ci0,
				c3item4d3cj0 : c3item4d3cj0,
				c3item4d3ck0 : c3item4d3ck0,
				c3item4d3cl0 : c3item4d3cl0,
				c3item4d3cm0 : c3item4d3cm0,
				c3item4d3cn0 : c3item4d3cn0,
				c3item4d3df0 : c3item4d3df0,
				c3item4d3dg0 : c3item4d3dg0,
				c3item4d3dh0 : c3item4d3dh0,
				c3item4d3di0 : c3item4d3di0,
				c3item4d3dj0 : c3item4d3dj0,
				c3item4d3dk0 : c3item4d3dk0,
				c3item4d3dl0 : c3item4d3dl0,
				c3item4d3dm0 : c3item4d3dm0,
				c3item4d3dn0 : c3item4d3dn0,
				c3item4d3ef0 : c3item4d3ef0,
				c3item4d3eg0 : c3item4d3eg0,
				c3item4d3eh0 : c3item4d3eh0,
				c3item4d3ei0 : c3item4d3ei0,
				c3item4d3ej0 : c3item4d3ej0,
				c3item4d3ek0 : c3item4d3ek0,
				c3item4d3el0 : c3item4d3el0,
				c3item4d3em0 : c3item4d3em0,
				c3item4d3en0 : c3item4d3en0,
				c3item5d6a0  : c3item5d6a0,
				c3item5d6b0  : c3item5d6b0,
				c3item5d6c0  : c3item5d6c0,
				c3item5d6d0  : c3item5d6d0,
				c3item5d6e0  : c3item5d6e0,
				c3item5d6f0  : c3item5d6f0,
				c3item5d6g0  : c3item5d6g0,
				c3item5d6h1  : c3item5d6h1,
				c3item5d6i0  : c3item5d6i0,
				c3item5d6j0  : c3item5d6j0,
				c3item6d6a0  : c3item6d6a0,
				c3item6d6b1  : c3item6d6b1,
				c3item6d6c0  : c3item6d6c0,
				c3item6d6d0  : c3item6d6d0,
				c3item6d6e0  : c3item6d6e0,
				c3item6d6f0  : c3item6d6f0,
				c3item6d6g0  : c3item6d6g0,
				c3item6d6h0  : c3item6d6h0,
				c3item6d6i0  : c3item6d6i0,
				c3item6d6j0  : c3item6d6j0,
				c3item7d6a0  : c3item7d6a0,
				c3item7d6b0  : c3item7d6b0,
				c3item7d6c0  : c3item7d6c0,
				c3item7d6d0  : c3item7d6d0,
				c3item7d6e1  : c3item7d6e1,
				c3item7d6f0  : c3item7d6f0,
				c3item7d6g0  : c3item7d6g0,
				c3item7d6h0  : c3item7d6h0,
				c3item7d6i0  : c3item7d6i0,
				c3item7d6j0  : c3item7d6j0,
				c3item8d6a0  : c3item8d6a0,
				c3item8d6b0  : c3item8d6b0,
				c3item8d6c1  : c3item8d6c1,
				c3item8d6d0  : c3item8d6d0,
				c3item8d6e0  : c3item8d6e0,
				c3item8d6f0  : c3item8d6f0,
				c3item8d6g0  : c3item8d6g0,
				c3item8d6h0  : c3item8d6h0,
				c3item8d6i0  : c3item8d6i0,
				c3item8d6j0  : c3item8d6j0,
				c3item9d6a0  : c3item9d6a0,
				c3item9d6b0  : c3item9d6b0,
				c3item9d6c0  : c3item9d6c0,
				c3item9d6d0  : c3item9d6d0,
				c3item9d6e1  : c3item9d6e1,
				c3item9d6f0  : c3item9d6f0,
				c3item9d6g0  : c3item9d6g0,
				c3item9d6h0  : c3item9d6h0,
				c3item9d6i0  : c3item9d6i0,
				c3item9d6j0  : c3item9d6j0,
				c3item10d6a0 : c3item10d6a0,
				c3item10d6b0 : c3item10d6b0,
				c3item10d6c0 : c3item10d6c0,
				c3item10d6d1 : c3item10d6d1,
				c3item10d6e0 : c3item10d6e0,
				c3item10d6f0 : c3item10d6f0,
				c3item10d6g0 : c3item10d6g0,
				c3item10d6h0 : c3item10d6h0,
				c3item10d6i0 : c3item10d6i0,
				c3item10d6j0 : c3item10d6j0,
				c3item11d2b1 : c3item11d2b1,
				c3item11d2c0 : c3item11d2c0,
				c3item12d5a0 : c3item12d5a0,
				c3item12d5b1 : c3item12d5b1,
				c3item13d2a0 : c3item13d2a0,
				c3item13d2b1 : c3item13d2b1,
				c3item14d5a0 : c3item14d5a0,
				c3item14d5b1 : c3item14d5b1,
				c3item15d2b1 : c3item15d2b1,
				c3item15d2c0 : c3item15d2c0,
				c3item15d2d0 : c3item15d2d0,
				c3item15d2e1 : c3item15d2e1,
				c3item16d2a0 : c3item16d2a0,
				c3item16d2c1 : c3item16d2c1,
				c3item17d5a0 : c3item17d5a0,
				c3item17d5b0 : c3item17d5b0,
				c3item17d5c1 : c3item17d5c1,
				c3item18d5a0 : c3item18d5a0,
				c3item18d5b1 : c3item18d5b1,
				c3item18d5c0 : c3item18d5c0,
				c3item19d5a0 : c3item19d5a0,
				c3item19d5b0 : c3item19d5b0,
				c3item19d5c1 : c3item19d5c1,
				c3item20d5a0 : c3item20d5a0,
				c3item20d5b0 : c3item20d5b0,
				c3item20d5c1 : c3item20d5c1,
				c3item21d5a1 : c3item21d5a1,
				c3item21d5b0 : c3item21d5b0,
				c3item21d5c0 : c3item21d5c0
				});

}
</script>

<!-- write your code here -->

</head>
<body onload="init();" style="background-color:#D4D4D4;margin:0px;">
	<canvas id="canvas" width="1124" height="600" style="background-color:#FFFFFF"></canvas>
</body>
</html>
