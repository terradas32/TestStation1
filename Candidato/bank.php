<?php
	require_once('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	//que espere antes de la ejecución del script
	sleep(2.5);

//Inicialización de variables
/**********************************************************************************
	El OPENER tiene que tener dos campos obligatoriamente que son:
		- MODOOLD --> Se guarda el modo original como fué llamado el padre.
		- ACCION  --> Guarda el ACTION al que llamará el puente en el submit.
**********************************************************************************/
?>
<!doctype html>
	<html lang="<?php  echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, Wi2.22 www.negociainternet.com" />
<title>Window</title>
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	 <script src="codigo/common.js"></script>
	 <script src="codigo/codigo.js"></script>
	 <script src="codigo/comun.js"></script>
	 <script src="codigo/noback.js"></script>
	 <script src="codigo/jQuery1.4.2.js"></script>
<script   >
//<![CDATA[
function _body_onload(){	loff();	}
function _body_onunload(){	lon();	}
//]]>
</script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0" alt="" /><strong><?php  echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
<form name="Formulario" method="post" action="" >
<script   >
//<![CDATA[
var long=opener.document.forms[0].length;
fo=opener.document.forms[0];
var sTraza="";
resizeTo(opener.document.documentElement.clientWidth-50,opener.document.documentElement.clientHeight);
moveTo(25,25);

for ( b = 0 ; b < long ; b++)
{
	sTraza = '<input type="hidden" name="'+fo.elements[b].name+'" value="'+fo.elements[b].value+'">';
	document.write(sTraza);
}

document.forms[0].action=fo.ACCION.value;
//fo.MODO.value = fo.MODOOLD.value;
document.forms[0].submit();
//]]>
</script>
</form>
</body>
</html>
