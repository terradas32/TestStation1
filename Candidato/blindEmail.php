<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('./include/Configuracion.php');
	if (!isset($_REQUEST["fLang"])){
		$_REQUEST["fLang"] = $_REQUEST["fCodIdiomaIso2"];
	}
	include('include/Idiomas.php');
	//include_once(constant("DIR_WS_INCLUDE") . 'SeguridadCandidatos.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");

	require_once(constant("DIR_WS_COM") . "Combo.php");
	

include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

    
    $cCandidatosDB = new CandidatosDB($conn);
  	
    
    $cCandidato = new Candidatos();
    //$cCandidato  = $_cEntidadCandidatoTK;
	$bBlindEmailStatus=0;
    if (!empty($_POST["fIdEmpresa"]) && !empty($_POST["fIdProceso"]) && !empty($_POST["fMailCan"])){
    	$cCandidato->setIdEmpresa($_POST["fIdEmpresa"]);
    	$cCandidato->setIdProceso($_POST["fIdProceso"]);
    	$cCandidato->setMail($_POST["fMailCan"]);
    	$sSQL = $cCandidatosDB->readLista($cCandidato);
    	//echo $sSQL;
    	$rsCandidatos = $conn->Execute($sSQL);
    	if ($rsCandidatos->recordCount() > 0){
    		$sToken = $rsCandidatos->fields['token'];
    		$bBlindEmailStatus=1;
    	}
    }
?>
<input type="hidden" name="fBlindEmailStatus" value="<?php echo $bBlindEmailStatus; ?>" />
<?php 
if ($bBlindEmailStatus==1){?>
	<p>
	<?php echo sprintf(constant("MSG_YA_REGISTRADO_ALTAS_CIEGAS_CONTINUAR"),$sLang, $sToken);?>
	</p>
<?php
 }?>