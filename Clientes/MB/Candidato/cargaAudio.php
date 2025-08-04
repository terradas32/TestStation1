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

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");



    $cCandidato = new Candidatos();
    $cCandidato  = $_cEntidadCandidatoTK;


    /****************************************************************
     * Si se ha pulsado el enlace de escuchar audio, se valida que sólo sea dos veces
     ****************************************************************/
    if(!empty($_POST['fIdPrueba']) && !empty($_POST['fIdEmpresa']) &&
    !empty($_POST['fIdProceso']) && !empty($_POST['fIdCandidato']) &&
    !empty($_POST['fCodIdiomaIso2']) && !empty($_POST['sTKCandidatos']) )
    {
    	$sql = "SELECT *  FROM kpmg_audio WHERE ";
		$sql .=" idEmpresa=" . $conn->qstr($_POST['fIdEmpresa'], false);
		$sql .=" AND idProceso=" . $conn->qstr($_POST['fIdProceso'], false);
		$sql .=" AND idPrueba=" . $conn->qstr($_POST['fIdPrueba'], false);
		$sql .=" AND codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false);
		$sql .=" AND idCandidato=" . $conn->qstr($_POST['fIdCandidato'], false);

		$rs = $conn->Execute($sql);
		$nVeces = 0;
    	while ($arr = $rs->FetchRow()){
			$nVeces = $arr['nVecesDescargado'];
		}
				echo '
		<div id="flashContent">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="500" height="100" id="locucion' . $_POST['fIdPrueba'] . '" align="middle">
				<param name="movie" value="locucion' . $_POST['fIdPrueba'] . '.swf" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#ffffff" />
				<param name="play" value="true" />
				<param name="loop" value="true" />
				<param name="wmode" value="window" />
				<param name="scale" value="showall" />
				<param name="menu" value="true" />
				<param name="devicefont" value="false" />
				<param name="salign" value="" />
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="FlashVars" value="veces=' . $nVeces . '&ruta=' . constant("HTTP_SERVER") . '&fIdPrueba=' . $_POST['fIdPrueba'] . '&fIdEmpresa=' . $_POST['fIdEmpresa'] . '&fIdProceso=' . $_POST['fIdProceso'] . '&fIdCandidato=' . $_POST['fIdCandidato'] . '&fCodIdiomaIso2=' . $_POST['fCodIdiomaIso2'] . '&sTKCandidatos=' . $_POST['sTKCandidatos'] . '" />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="locucion' . $_POST['fIdPrueba'] . '.swf" width="500" height="100">
					<param name="movie" value="locucion' . $_POST['fIdPrueba'] . '.swf" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#ffffff" />
					<param name="play" value="true" />
					<param name="loop" value="true" />
					<param name="wmode" value="window" />
					<param name="scale" value="showall" />
					<param name="menu" value="true" />
					<param name="devicefont" value="false" />
					<param name="salign" value="" />
					<param name="allowScriptAccess" value="sameDomain" />
					<param name="FlashVars" value="veces=' . $nVeces . '&ruta=' . constant("HTTP_SERVER") . '&fIdPrueba=' . $_POST['fIdPrueba'] . '&fIdEmpresa=' . $_POST['fIdEmpresa'] . '&fIdProceso=' . $_POST['fIdProceso'] . '&fIdCandidato=' . $_POST['fIdCandidato'] . '&fCodIdiomaIso2=' . $_POST['fCodIdiomaIso2'] . '&sTKCandidatos=' . $_POST['sTKCandidatos'] . '" />
				<!--<![endif]-->
					<a href="http://www.adobe.com/go/getflash">
						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obtener Adobe Flash Player" />
					</a>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>
		</div>
				';

    }else{
    	echo "Faltan datos.";
    }
?>
