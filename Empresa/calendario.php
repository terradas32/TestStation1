<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

	require_once('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	include_once(constant("DIR_WS_INCLUDE") . "calendario.php");
	$tiempo_actual = time();
	if (!isset($_REQUEST['dia']) || $_REQUEST['dia'] ==""){
		$_REQUEST['dia'] = date("d", $tiempo_actual);
	}
	$_REQUEST['dia']=(strlen($_REQUEST['dia'])==1) ? "0" . $_REQUEST['dia'] : $_REQUEST['dia'];
	if (!isset($_REQUEST['mes']) || $_REQUEST['mes'] ==""){
		$_REQUEST['mes'] = date("n", $tiempo_actual);
	}
	$_REQUEST['mes']=(strlen($_REQUEST['mes'])==1) ? "0" . $_REQUEST['mes'] : $_REQUEST['mes'];
	if (!isset($_REQUEST['ano']) || $_REQUEST['ano'] =="")	$_REQUEST['ano'] = date("Y", $tiempo_actual);
	$fFecha=$_REQUEST['dia'] . "/" . $_REQUEST['mes'] . "/" . $_REQUEST['ano'];
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, Wi2.22 www.negociainternet.com" />

	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<link rel="stylesheet" href="estilos/jquery.alerts.css" type="text/css" />
	<title>Calendario</title>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
<script language="javascript" type="text/javascript">
var winwidth = ((window.screen.availWidth - 74) / 2);
var winheight = ((window.screen.availHeight -74) / 2);
this.moveTo(winwidth,winheight);
function addY(){
	var	f=document.forms[0];
	f.ano.value = (parseInt(f.ano.value,10) + 1);
}
function delY(){
	var	f=document.forms[0];
	f.ano.value = (parseInt(f.ano.value,10) - 1);
}
function addM(){
	var	f=document.forms[0];
	if (f.mes.value == 12){
		f.mes.value = 1;
		addY();
	}else{
		f.mes.value = (parseInt(f.mes.value,10) + 1);
	}
	f.submit();
}
function delM(){
	var	f=document.forms[0];
	if (f.mes.value == 1){
		f.mes.value = 12;
		delY();
	}else{
		f.mes.value = (parseInt(f.mes.value,10) - 1);
	}
	f.submit();
}
function selectedD(fecha){
	var oc = "<?php echo (isset($_GET['openerNombre'])) ? $_GET['openerNombre'] : $_POST['openerNombre'];?>";
	try{
		objOc = eval("opener.document.forms[0]." + oc);
	} catch(exc){
		alert("<?php echo constant("ERR_FORM_ERROR");?>");
		top.window.close();
	}
	objOc.value = fecha;
	top.window.close();
}
function validaOpener(){
	if (opener.closed){
		top.window.close();
	}else{
		try{
			var of = opener.document.forms[0];
		} catch(exc){
			top.window.close();
		}
		if (of != null){
			var oc = "<?php echo (isset($_GET['openerNombre'])) ? $_GET['openerNombre'] : $_POST['openerNombre'];?>";
			try{
				objOc = eval("opener.document.forms[0]." + oc);
			} catch(exc){
				alert("<?php echo constant("ERR_FORM_ERROR");?>");
				top.window.close();
			}
		}else{
			top.window.close();
		}
	}
}
</script>
</head>
<body>
<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<?php mostrar_calendario($_REQUEST['mes'], $_REQUEST['ano'], "fFecSolicitud");?>
<input type="hidden" name="dia" value="<?php echo $_REQUEST['dia'];?>" />
<input type="hidden" name="mes" value="<?php echo $_REQUEST['mes'];?>" />
<input type="hidden" name="ano" value="<?php echo $_REQUEST['ano'];?>" />
<input type="hidden" name="fFecha" value="<?php echo $fFecha;?>" />
<input type="hidden" name="openerNombre" value="<?php echo (isset($_GET['openerNombre'])) ? $_GET['openerNombre'] : $_POST['openerNombre'];?>" />
</form>
</body>
</html>
