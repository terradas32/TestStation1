<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('./include/Configuracion.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_WS_COM") . "Items/ItemsDB.php");
	require_once(constant("DIR_WS_COM") . "Items/Items.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once(constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
	require_once(constant("DIR_WS_COM") . "Opciones/Opciones.php");
	require_once(constant("DIR_WS_COM") . "Combo.php");


include_once ('include/conexion.php');

//	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$cUtilidades = new Utilidades();
	$cCandidatos = new Candidatos();
	$cCandidatosDB = new CandidatosDB($conn);
	$cPruebas = new Pruebas();
	$cPruebasDB = new PruebasDB($conn);

    $query = explode("&", $_SERVER['QUERY_STRING']);
    if ($query[0] != "")
    {
		$sCall = stripos($query[0], "see");
		if ($sCall === false) {
			echo "Error no see see.";
			exit;
		}else{
			$aCall=explode("=", $query[0]);
			if ($aCall[0] !="" && $aCall[1] !=""){
				$sCall=$aCall[1];
			}else{
				echo "Error";
				exit;
			}
		}

		$bNoEntrar = $cUtilidades->chkChar($sCall);
		if ($bNoEntrar){
			echo "Error bye bye!!!";
			exit;
		}
		$sCall =  base64_decode($sCall);
    	if ($bNoEntrar){
			echo "** Error bye bye!!!";
			exit;
		}
	//	ruta=http://localhost/TestStation/Candidato/&proceso=53&convocatoria=53&sala=1&prueba=70&usuario=8af0077a38727d5a818c2d87eecfe92b&pantalla=0&segundos=&minutos=2&segundos2=00&minutos2=0&campolibre=
//		echo "<br />1:see:" . $sCall;exit;

	    /****************************************************************
	     * Recogemos el request de la prueba FLASH
	     ****************************************************************/
		$sRuta='';
		$sProceso='';
		$sConvocatoria='';
		$sSala='1';
		$sPrueba='';
		$sUsuario='';
		$sPantalla='';
		$sSegundos='';
		$sMinutos='';
		$sSegundos2='';
		$sMinutos2='';
		$sCampolibre='';
		$sBgcolor = '';
		$sItemsLlamada= '';
		$sRootFilePruebas = 'filePruebas/';

		//Dividimos por pares name Valor
		$aLlamada=explode("&", $sCall);
//		print_r($aLlamada);
    	//cogemos los datos de la ruta
    	$aRuta=explode("=", $aLlamada[0]);
		if ($aRuta[0] !="" && $aRuta[1] !=""){
			$sRuta=$aRuta[1];
		}else{
			echo "Error R";
			exit;
		}
        //cogemos los datos del proceso
    	$aProceso=explode("=", $aLlamada[1]);
		if ($aProceso[0] !="" && $aProceso[1] !=""){
			$sProceso=$aProceso[1];
		}else{
			echo "Error P";
			exit;
		}
		//cogemos los datos de convocatoria
    	$aConvocatoria=explode("=", $aLlamada[2]);
		if ($aConvocatoria[0] !="" && $aConvocatoria[1] !=""){
			$sConvocatoria=$aConvocatoria[1];
		}else{
			echo "Error C";
			exit;
		}
		//cogemos los datos de sala
    	$aSala=explode("=", $aLlamada[3]);
		if ($aSala[0] !="" && $aSala[1] !=""){
			$sSala=$aSala[1];
		}else{
			echo "Error SA";
			exit;
		}
		//cogemos los datos de Prueba
    	$aPrueba=explode("=", $aLlamada[4]);
		if ($aPrueba[0] !="" && $aPrueba[1] !=""){
			$sPrueba=$aPrueba[1];
		}else{
			echo "Error P";
			exit;
		}
		//cogemos los datos de usuario que es el 5
    	$aUsuario=explode("=", $aLlamada[5]);
		if ($aUsuario[0] !="" && $aUsuario[1] !=""){
			$sUsuario=$aUsuario[1];
		}else{
			echo "Error U";
			exit;
		}
		//cogemos los datos de Pantalla
    	$aPantalla=explode("=", $aLlamada[6]);
		if ($aPantalla[0] !="" && $aPantalla[1] !=""){
			$sPantalla=$aPantalla[1];
		}else{
			echo "Error P";
			exit;
		}
		//cogemos los datos de Segundos
    	$aSegundos=explode("=", $aLlamada[7]);
		if ($aSegundos[0] !=""){
			$sSegundos=$aSegundos[1];
		}else{
			echo "Error S";
			exit;
		}
    	//cogemos los datos de Minutos
    	$aMinutos=explode("=", $aLlamada[8]);
		if ($aMinutos[0] !="" && $aMinutos[1] !=""){
			$sMinutos=$aMinutos[1];
		}else{
			echo "Error M";
			exit;
		}
        //cogemos los datos de Segundos2
    	$aSegundos2=explode("=", $aLlamada[9]);
		if ($aSegundos2[0] !="" && $aSegundos2[1] !=""){
			$sSegundos2=$aSegundos2[1];
		}else{
			echo "Error S2";
			exit;
		}
        //cogemos los datos de Minutos2
    	$aMinutos2=explode("=", $aLlamada[10]);
		if ($aMinutos2[0] !="" && $aMinutos2[1] !=""){
			$sMinutos2=$aMinutos2[1];
		}else{
			echo "Error M2";
			exit;
		}
        //cogemos los datos de CampoLibre
    	$aCampoLibre=explode("=", $aLlamada[11]);
		if ($aCampoLibre[0] !=""){
			$sCampoLibre=$aCampoLibre[1];
		}else{
			echo "Error CL";
			exit;
		}
		//cogemos toodos los items de la prueba desde la 12 hasta el final
		$sItemsLlamada = "";
    	for ($i=12, $max = sizeof($aLlamada); $i < $max; $i++){
    		$sItemsLlamada .= "&" . $aLlamada[$i];
		}

//		echo $sItemsLlamada;exit;
//		echo $sUsuario;exit;
		$cPruebas->setIdPrueba($sPrueba);
		$cPruebas->setCodIdiomaIso2('es');
		$cPruebas = $cPruebasDB->readEntidad($cPruebas);
		$cCandidatos->setToken($sUsuario);
		$cCandidatos = $cCandidatosDB->candidatoPorToken($cCandidatos);
		if ($cCandidatos->getIdCandidato() == ""){
		  session_start();
		  $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
		  echo '<script   >top.location.replace("' . constant("HTTP_SERVER") . 'msg.php");</script>';
		  exit;
		}
	}else{
		echo "Error FD";
		exit;

	}
?>
<!-- saved from url=(0013)about:internet -->
<!doctype html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $cPruebas->getNombre();?></title>
<link rel="shortcut icon" href="favicon.ico" />
	 <script src="codigo/eventos.js"></script>
<script   >
//<![CDATA[
var sWidth = "100%";	//window.screen.availWidth;
var sHeight = window.screen.height;
if (!NS){
	sHeight = window.screen.height;
}else{
	sHeight = sHeight-86;
}
//]]>
</script>
</head>
<?php
$bEsFLASH = true;
$sIrHTML5 = "";
$pos = strrpos($cPruebas->getCodigo(), ".swf");
if ($pos === false) { // Si NO es plash es una HTML5
	$bEsFLASH = false;
	$sIrHTML5 = "document.forms[0].submit();";

}else {
	$bEsFLASH = true;
}
?>
<body bgcolor="<?php echo $cPruebas->getEstiloOpciones();?>" onload="noBack();history.go(+1);<?php echo $sIrHTML5;?>" onpageshow="if (event.persisted) noBack();" onunload="" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" scroll="no">
<form method="post" name="game" action="<?php echo $sRootFilePruebas . $cPruebas->getCodigo();?>" >
<script >
<!--
function noBack(){
	window.history.forward();
}
window.history.forward();
<?php
	if ($bEsFLASH){
?>
	document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" id="baloncesto" width="' + sWidth + '" height="' + sHeight + '" align="middle" type="application/x-shockwave-flash" data="<?php echo $sRootFilePruebas . $cPruebas->getCodigo();?>">');
	document.write('	<param name="allowScriptAccess" value="sameDomain" />');
	document.write('	<param name="base" value="." />');
	document.write('	<param name="movie" value="<?php echo $sRootFilePruebas . $cPruebas->getCodigo();?>" />');
	document.write('	<param name="quality" value="high" />');
	document.write('	<param name="bgcolor" value="<?php echo $cPruebas->getEstiloOpciones();?>" />');
	document.write('	<param name="menu" value="false" />');
	document.write('	<param name="scale" value="exactfit" />');
	document.write('	<param name="FlashVars" value="ruta=<?php echo $sRuta;?>&proceso=<?php echo $cCandidatos->getIdProceso();?>&convocatoria=<?php echo $cCandidatos->getIdProceso();?>&sala=<?php echo $sSala;?>&prueba=<?php echo $cPruebas->getIdPrueba();?>&usuario=<?php echo $cCandidatos->getToken();?>&pantalla=<?php echo $sPantalla;?>&segundos=<?php echo $sSegundos;?>&minutos=<?php echo $sMinutos;?>&segundos2=<?php echo $sSegundos2;?>&minutos2=<?php echo $sMinutos2;?>&campolibre=<?php echo $sCampoLibre . $sItemsLlamada;?>">');
	document.write('	<embed src="<?php echo $sRootFilePruebas . $cPruebas->getCodigo();?>" quality="high" scale="exactfit" bgcolor="<?php echo $cPruebas->getEstiloOpciones();?>" width="' + sWidth + '" height="' + sHeight + '" swLiveConnect=true id="baloncesto" name="baloncesto" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" data="<?php echo $sRootFilePruebas . $cPruebas->getCodigo();?>" flashVars="ruta=<?php echo $sRuta;?>&proceso=<?php echo $cCandidatos->getIdProceso();?>&convocatoria=<?php echo $cCandidatos->getIdProceso();?>&sala=<?php echo $sSala;?>&prueba=<?php echo $cPruebas->getIdPrueba();?>&usuario=<?php echo $cCandidatos->getToken();?>&pantalla=<?php echo $sPantalla;?>&segundos=<?php echo $sSegundos;?>&minutos=<?php echo $sMinutos;?>&segundos2=<?php echo $sSegundos2;?>&minutos2=<?php echo $sMinutos2;?>&campolibre=<?php echo $sCampoLibre . $sItemsLlamada;?>" />');
	document.write('</object>');
<?php
	}
?>
	//-->
	</script>
<?php
if (!$bEsFLASH){
	//Ponemos todo en ocultos
//	echo "<br />Es HTML";
//	echo "<br />Fichero:" . $sRootFilePruebas . $cPruebas->getCodigo();
	echo '<input type="hidden" name="ruta" value="' . $sRuta . '" />';
	echo '<input type="hidden" name="proceso" value="' . $cCandidatos->getIdProceso() . '" />';
	echo '<input type="hidden" name="convocatoria" value="' . $cCandidatos->getIdProceso() . '" />';
	echo '<input type="hidden" name="sala" value="' . $sSala . '" />';
	echo '<input type="hidden" name="prueba" value="' . $cPruebas->getIdPrueba() . '" />';
	echo '<input type="hidden" name="usuario" value="' . $cCandidatos->getToken() . '" />';
	echo '<input type="hidden" name="pantalla" value="' . $sPantalla . '" />';
	echo '<input type="hidden" name="segundos" value="' . $sSegundos . '" />';
	echo '<input type="hidden" name="minutos" value="' . $sMinutos . '" />';
	echo '<input type="hidden" name="segundos2" value="' . $sSegundos2 . '" />';
	echo '<input type="hidden" name="minutos2" value="' . $sMinutos2 . '" />';
	echo '<input type="hidden" name="campolibre" value="' . $sCampoLibre . '" />';
	$sItemsLlamada = substr($sItemsLlamada, 1);
	$aItemsLlamada = explode("&", $sItemsLlamada);
    for ($i=0, $max = sizeof($aItemsLlamada); $i < $max; $i++){
    	$aValores = explode("=", $aItemsLlamada[$i]);
    	echo '<input type="hidden" name="' . $aValores[0] . '"  value="' . $aValores[1] . '" />';
	}

}
?>
<input type="hidden" name="sSubmit" value="1"  />
<input type="hidden" name="sTK" value="<?php echo $cCandidatos->getToken();?>"  />
</form>
</body>
</html>
