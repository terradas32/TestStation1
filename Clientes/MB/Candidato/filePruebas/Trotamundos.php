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

//Datos comunes
$_ruta=$_POST["ruta"];
$_proceso=$_POST["proceso"];
$_convocatoria=$_POST["convocatoria"];
$_sala=$_POST["sala"];
$_prueba=$_POST["prueba"];
$_usuario=$_POST["usuario"];
$_pantalla=$_POST["pantalla"];
$_segundos=$_POST["segundos"];
$_minutos=$_POST["minutos"];
$_segundos2=$_POST["segundos2"];
$_minutos2=$_POST["minutos2"];
$_campolibre=$_POST["campoLibre"];

if (empty($_usuario)){
	session_start();
	$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
	echo '<script language="javascript" type="text/javascript">top.location.replace("' . constant("HTTP_SERVER") . 'msg.php");</script>';
	exit;	
}
//Datos particulares, items de cada prueba
$_c3item2d3bj1 = $_POST["c3item2d3bj1"];   
$_c3item2d3bk0 = $_POST["c3item2d3bk0"];   
$_c3item2d3bl0 = $_POST["c3item2d3bl0"];   
$_c3item2d3bm0 = $_POST["c3item2d3bm0"];   
$_c3item2d3bh0 = $_POST["c3item2d3bh0"];   
$_c3item2d3bi0 = $_POST["c3item2d3bi0"];   
$_c3item2d3ak0 = $_POST["c3item2d3ak0"];   
$_c3item2d3al0 = $_POST["c3item2d3al0"];   
$_c3item2d3am0 = $_POST["c3item2d3am0"];   
$_c3item2d3an0 = $_POST["c3item2d3an0"];   
$_c3item2d3bf0 = $_POST["c3item2d3bf0"];   
$_c3item2d3bg0 = $_POST["c3item2d3bg0"];   
$_c3item1d1d1  = $_POST["c3item1d1d1"];    
$_c3item1d1e0  = $_POST["c3item1d1e0"];    
$_c3item2d3af0 = $_POST["c3item2d3af0"];   
$_c3item2d3ag0 = $_POST["c3item2d3ag0"];   
$_c3item2d3ah0 = $_POST["c3item2d3ah0"];   
$_c3item2d3ai0 = $_POST["c3item2d3ai0"];   
$_c3item2d3aj0 = $_POST["c3item2d3aj0"];   
$_c3item1d1a0  = $_POST["c3item1d1a0"];    
$_c3item1d1b1  = $_POST["c3item1d1b1"];    
$_c3item1d1c1  = $_POST["c3item1d1c1"];    
$_c3item2d3bn0 = $_POST["c3item2d3bn0"];   
$_c3item2d3cf1 = $_POST["c3item2d3cf1"];   
$_c3item2d3cg0 = $_POST["c3item2d3cg0"];   
$_c3item2d3ch0 = $_POST["c3item2d3ch0"];   
$_c3item2d3ci0 = $_POST["c3item2d3ci0"];   
$_c3item2d3cj0 = $_POST["c3item2d3cj0"];   
$_c3item2d3ck0 = $_POST["c3item2d3ck0"];   
$_c3item2d3cl0 = $_POST["c3item2d3cl0"];   
$_c3item2d3cm0 = $_POST["c3item2d3cm0"];   
$_c3item2d3cn1 = $_POST["c3item2d3cn1"];   
$_c3item2d3df0 = $_POST["c3item2d3df0"];   
$_c3item2d3dg0 = $_POST["c3item2d3dg0"];   
$_c3item2d3dh1 = $_POST["c3item2d3dh1"];   
$_c3item2d3di0 = $_POST["c3item2d3di0"];   
$_c3item2d3dj0 = $_POST["c3item2d3dj0"];   
$_c3item2d3dk0 = $_POST["c3item2d3dk0"];   
$_c3item2d3dl1 = $_POST["c3item2d3dl1"];   
$_c3item2d3dm0 = $_POST["c3item2d3dm0"];   
$_c3item2d3dn0 = $_POST["c3item2d3dn0"];   
$_c3item2d3ef0 = $_POST["c3item2d3ef0"];   
$_c3item2d3eg0 = $_POST["c3item2d3eg0"];   
$_c3item2d3eh0 = $_POST["c3item2d3eh0"];   
$_c3item2d3ei0 = $_POST["c3item2d3ei0"];   
$_c3item2d3ej0 = $_POST["c3item2d3ej0"];   
$_c3item2d3ek0 = $_POST["c3item2d3ek0"];   
$_c3item2d3el0 = $_POST["c3item2d3el0"];   
$_c3item2d3em0 = $_POST["c3item2d3em0"];   
$_c3item2d3en0 = $_POST["c3item2d3en0"];   
$_c3item3d1a1  = $_POST["c3item3d1a1"];    
$_c3item3d1b1  = $_POST["c3item3d1b1"];    
$_c3item3d1c0  = $_POST["c3item3d1c0"];    
$_c3item3d4d   = $_POST["c3item3d4d"];     
$_c3item3d1e0  = $_POST["c3item3d1e0"];    
$_c3item4d3af0 = $_POST["c3item4d3af0"];   
$_c3item4d3ag1 = $_POST["c3item4d3ag1"];   
$_c3item4d3ah0 = $_POST["c3item4d3ah0"];   
$_c3item4d3ai0 = $_POST["c3item4d3ai0"];   
$_c3item4d3aj0 = $_POST["c3item4d3aj0"];   
$_c3item4d4ak  = $_POST["c3item4d4ak"];    
$_c3item4d3al0 = $_POST["c3item4d3al0"];   
$_c3item4d4am  = $_POST["c3item4d4am"];    
$_c3item4d3an0 = $_POST["c3item4d3an0"];   
$_c3item4d3bf0 = $_POST["c3item4d3bf0"];   
$_c3item4d3bg0 = $_POST["c3item4d3bg0"];   
$_c3item4d3bh0 = $_POST["c3item4d3bh0"];   
$_c3item4d4bi  = $_POST["c3item4d4bi"];    
$_c3item4d3bj0 = $_POST["c3item4d3bj0"];   
$_c3item4d3bk0 = $_POST["c3item4d3bk0"];   
$_c3item4d3bl0 = $_POST["c3item4d3bl0"];   
$_c3item4d4bm  = $_POST["c3item4d4bm"];    
$_c3item4d3bn1 = $_POST["c3item4d3bn1"];   
$_c3item4d3cf0 = $_POST["c3item4d3cf0"];   
$_c3item4d3cg0 = $_POST["c3item4d3cg0"];   
$_c3item4d3ch0 = $_POST["c3item4d3ch0"];   
$_c3item4d3ci0 = $_POST["c3item4d3ci0"];   
$_c3item4d3cj0 = $_POST["c3item4d3cj0"];   
$_c3item4d3ck0 = $_POST["c3item4d3ck0"];   
$_c3item4d3cl0 = $_POST["c3item4d3cl0"];   
$_c3item4d3cm0 = $_POST["c3item4d3cm0"];   
$_c3item4d3cn0 = $_POST["c3item4d3cn0"];   
$_c3item4d3df0 = $_POST["c3item4d3df0"];   
$_c3item4d3dg0 = $_POST["c3item4d3dg0"];   
$_c3item4d3dh0 = $_POST["c3item4d3dh0"];   
$_c3item4d3di0 = $_POST["c3item4d3di0"];   
$_c3item4d3dj0 = $_POST["c3item4d3dj0"];   
$_c3item4d3dk0 = $_POST["c3item4d3dk0"];   
$_c3item4d3dl0 = $_POST["c3item4d3dl0"];   
$_c3item4d3dm0 = $_POST["c3item4d3dm0"];   
$_c3item4d3dn0 = $_POST["c3item4d3dn0"];   
$_c3item4d3ef0 = $_POST["c3item4d3ef0"];   
$_c3item4d3eg0 = $_POST["c3item4d3eg0"];   
$_c3item4d3eh0 = $_POST["c3item4d3eh0"];   
$_c3item4d3ei0 = $_POST["c3item4d3ei0"];   
$_c3item4d3ej0 = $_POST["c3item4d3ej0"];   
$_c3item4d3ek0 = $_POST["c3item4d3ek0"];   
$_c3item4d3el0 = $_POST["c3item4d3el0"];   
$_c3item4d3em0 = $_POST["c3item4d3em0"];   
$_c3item4d3en0 = $_POST["c3item4d3en0"];   
$_c3item5d6a0  = $_POST["c3item5d6a0"];    
$_c3item5d6b0  = $_POST["c3item5d6b0"];    
$_c3item5d6c0  = $_POST["c3item5d6c0"];    
$_c3item5d6d0  = $_POST["c3item5d6d0"];    
$_c3item5d6e0  = $_POST["c3item5d6e0"];    
$_c3item5d6f0  = $_POST["c3item5d6f0"];    
$_c3item5d6g0  = $_POST["c3item5d6g0"];    
$_c3item5d6h1  = $_POST["c3item5d6h1"];    
$_c3item5d6i0  = $_POST["c3item5d6i0"];    
$_c3item5d6j0  = $_POST["c3item5d6j0"];    
$_c3item6d6a0  = $_POST["c3item6d6a0"];    
$_c3item6d6b1  = $_POST["c3item6d6b1"];    
$_c3item6d6c0  = $_POST["c3item6d6c0"];    
$_c3item6d6d0  = $_POST["c3item6d6d0"];    
$_c3item6d6e0  = $_POST["c3item6d6e0"];    
$_c3item6d6f0  = $_POST["c3item6d6f0"];    
$_c3item6d6g0  = $_POST["c3item6d6g0"];    
$_c3item6d6h0  = $_POST["c3item6d6h0"];    
$_c3item6d6i0  = $_POST["c3item6d6i0"];    
$_c3item6d6j0  = $_POST["c3item6d6j0"];    
$_c3item7d6a0  = $_POST["c3item7d6a0"];    
$_c3item7d6b0  = $_POST["c3item7d6b0"];    
$_c3item7d6c0  = $_POST["c3item7d6c0"];    
$_c3item7d6d0  = $_POST["c3item7d6d0"];    
$_c3item7d6e1  = $_POST["c3item7d6e1"];    
$_c3item7d6f0  = $_POST["c3item7d6f0"];    
$_c3item7d6g0  = $_POST["c3item7d6g0"];    
$_c3item7d6h0  = $_POST["c3item7d6h0"];    
$_c3item7d6i0  = $_POST["c3item7d6i0"];    
$_c3item7d6j0  = $_POST["c3item7d6j0"];    
$_c3item8d6a0  = $_POST["c3item8d6a0"];    
$_c3item8d6b0  = $_POST["c3item8d6b0"];    
$_c3item8d6c1  = $_POST["c3item8d6c1"];    
$_c3item8d6d0  = $_POST["c3item8d6d0"];    
$_c3item8d6e0  = $_POST["c3item8d6e0"];    
$_c3item8d6f0  = $_POST["c3item8d6f0"];    
$_c3item8d6g0  = $_POST["c3item8d6g0"];    
$_c3item8d6h0  = $_POST["c3item8d6h0"];    
$_c3item8d6i0  = $_POST["c3item8d6i0"];    
$_c3item8d6j0  = $_POST["c3item8d6j0"];    
$_c3item9d6a0  = $_POST["c3item9d6a0"];    
$_c3item9d6b0  = $_POST["c3item9d6b0"];    
$_c3item9d6c0  = $_POST["c3item9d6c0"];    
$_c3item9d6d0  = $_POST["c3item9d6d0"];    
$_c3item9d6e1  = $_POST["c3item9d6e1"];    
$_c3item9d6f0  = $_POST["c3item9d6f0"];    
$_c3item9d6g0  = $_POST["c3item9d6g0"];    
$_c3item9d6h0  = $_POST["c3item9d6h0"];    
$_c3item9d6i0  = $_POST["c3item9d6i0"];    
$_c3item9d6j0  = $_POST["c3item9d6j0"];    
$_c3item10d6a0 = $_POST["c3item10d6a0"];   
$_c3item10d6b0 = $_POST["c3item10d6b0"];   
$_c3item10d6c0 = $_POST["c3item10d6c0"];   
$_c3item10d6d1 = $_POST["c3item10d6d1"];   
$_c3item10d6e0 = $_POST["c3item10d6e0"];   
$_c3item10d6f0 = $_POST["c3item10d6f0"];   
$_c3item10d6g0 = $_POST["c3item10d6g0"];   
$_c3item10d6h0 = $_POST["c3item10d6h0"];   
$_c3item10d6i0 = $_POST["c3item10d6i0"];   
$_c3item10d6j0 = $_POST["c3item10d6j0"];   
$_c3item11d2b1 = $_POST["c3item11d2b1"];   
$_c3item11d2c0 = $_POST["c3item11d2c0"];   
$_c3item12d5a0 = $_POST["c3item12d5a0"];   
$_c3item12d5b1 = $_POST["c3item12d5b1"];   
$_c3item13d2a0 = $_POST["c3item13d2a0"];   
$_c3item13d2b1 = $_POST["c3item13d2b1"];   
$_c3item14d5a0 = $_POST["c3item14d5a0"];   
$_c3item14d5b1 = $_POST["c3item14d5b1"];   
$_c3item15d2b1 = $_POST["c3item15d2b1"];   
$_c3item15d2c0 = $_POST["c3item15d2c0"];   
$_c3item15d2d0 = $_POST["c3item15d2d0"];   
$_c3item15d2e1 = $_POST["c3item15d2e1"];   
$_c3item16d2a0 = $_POST["c3item16d2a0"];   
$_c3item16d2c1 = $_POST["c3item16d2c1"];   
$_c3item17d5a0 = $_POST["c3item17d5a0"];   
$_c3item17d5b0 = $_POST["c3item17d5b0"];   
$_c3item17d5c1 = $_POST["c3item17d5c1"];   
$_c3item18d5a0 = $_POST["c3item18d5a0"];   
$_c3item18d5b1 = $_POST["c3item18d5b1"];   
$_c3item18d5c0 = $_POST["c3item18d5c0"];   
$_c3item19d5a0 = $_POST["c3item19d5a0"];   
$_c3item19d5b0 = $_POST["c3item19d5b0"];   
$_c3item19d5c1 = $_POST["c3item19d5c1"];   
$_c3item20d5a0 = $_POST["c3item20d5a0"];   
$_c3item20d5b0 = $_POST["c3item20d5b0"];   
$_c3item20d5c1 = $_POST["c3item20d5c1"];   
$_c3item21d5a1 = $_POST["c3item21d5a1"];   
$_c3item21d5b0 = $_POST["c3item21d5b0"];   
$_c3item21d5c0 = $_POST["c3item21d5c0"];   

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Trotamundos</title>

<script src="http://code.createjs.com/easeljs-0.8.1.min.js"></script>
<script src="http://code.createjs.com/tweenjs-0.6.1.min.js"></script>
<script src="http://code.createjs.com/movieclip-0.8.1.min.js"></script>
<script src="http://code.createjs.com/preloadjs-0.6.1.min.js"></script>
<script src="Trotamundos.js"></script>

<script>
var canvas, stage, exportRoot;

function init() {
	canvas = document.getElementById("canvas");
	images = images||{};
	ss = ss||{};

	var loader = new createjs.LoadQueue(false);
	loader.addEventListener("fileload", handleFileLoad);
	loader.addEventListener("complete", handleComplete);
loader.loadFile({src:"images/Trotamundos_atlas_.json", type:"spritesheet", id:"Trotamundos_atlas_"}, true);
	loader.loadManifest(lib.properties.manifest);
}

function handleFileLoad(evt) {
	if (evt.item.type == "image") { images[evt.item.id] = evt.result; }
}

function handleComplete(evt) {
	var queue = evt.target;
	ss["Trotamundos_atlas_"] = queue.getResult("Trotamundos_atlas_");
	exportRoot = new lib.Trotamundos();

	stage = new createjs.Stage(canvas);
	stage.addChild(exportRoot);
	stage.update();
	stage.enableMouseOver();

	createjs.Ticker.setFPS(lib.properties.fps);
	createjs.Ticker.addEventListener("tick", stage);
}
</script>
</head>

<body onload="init();" style="background-color:#D4D4D4">
	<canvas id="canvas" width="1024" height="600" style="background-color:#FFFFFF"></canvas>
</body>
</html>