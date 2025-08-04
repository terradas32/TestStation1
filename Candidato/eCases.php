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
	     * Recogemos el request de la prueba
	     ****************************************************************/
		$sRuta='';
		$sProceso='';
		$sPrueba='';
		$sUsuario='';

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
		//cogemos los datos de Prueba
    	$aPrueba=explode("=", $aLlamada[2]);
		if ($aPrueba[0] !="" && $aPrueba[1] !=""){
			$sPrueba=$aPrueba[1];
		}else{
			echo "Error P";
			exit;
		}
		//cogemos los datos de usuario que es el 5
    	$aUsuario=explode("=", $aLlamada[3]);
		if ($aUsuario[0] !="" && $aUsuario[1] !=""){
			$sUsuario=$aUsuario[1];
		}else{
			echo "Error U";
			exit;
		}
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
function loginEcases(){
	window.location.replace("<?php echo constant("HTTP_SERVER_ECASES");?>");
}
//]]>
</script>
</head>
<?php
$bEsECases = true;
//$sIrECases = "document.forms[0].submit();";
$sIrECases = "loginEcases();";

?>
<body bgcolor="<?php echo $cPruebas->getEstiloOpciones();?>" onload="noBack();history.go(+1);<?php echo $sIrECases;?>" onpageshow="if (event.persisted) noBack();" onunload="" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" scroll="no">
<form method="post" name="eCases" action="<?php echo constant("HTTP_SERVER_ECASES");?>" >
<script >
<!--
function noBack(){
	window.history.forward();
}
window.history.forward();
//-->
</script>
<?php

	//Ponemos todo en ocultos
//	echo "<br />Es HTML";
//	echo "<br />Fichero:" . $sRootFilePruebas . $cPruebas->getCodigo();
	echo '<input type="hidden" name="ruta" value="' . $sRuta . '" />';
	echo '<input type="hidden" name="proceso" value="' . $cCandidatos->getIdProceso() . '" />';
	echo '<input type="hidden" name="prueba" value="' . $cPruebas->getIdPrueba() . '" />';
	echo '<input type="hidden" name="usuario" value="' . $cCandidatos->getToken() . '" />';

?>
<input type="hidden" name="sSubmit" value="1"  />
<input type="hidden" name="sTK" value="<?php echo $cCandidatos->getToken();?>"  />
</form>
</body>
</html>
