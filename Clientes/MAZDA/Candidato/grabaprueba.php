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
    if(!empty($_REQUEST['fIdPrueba']) && !empty($_REQUEST['fIdEmpresa']) &&
    !empty($_REQUEST['fIdProceso']) && !empty($_REQUEST['fIdCandidato']) &&
    !empty($_REQUEST['fCodIdiomaIso2']) && !empty($_REQUEST['sTKCandidatos']) )
    {
    	$sql = "SELECT *  FROM KPMG_audio WHERE ";
		$sql .=" idEmpresa=" . $conn->qstr($_REQUEST['fIdEmpresa'], false);
		$sql .=" AND idProceso=" . $conn->qstr($_REQUEST['fIdProceso'], false);
		$sql .=" AND idPrueba=" . $conn->qstr($_REQUEST['fIdPrueba'], false);
		$sql .=" AND codIdiomaIso2=" . $conn->qstr($_REQUEST['fCodIdiomaIso2'], false);
		$sql .=" AND idCandidato=" . $conn->qstr($_REQUEST['fIdCandidato'], false);
	
		$rs = $conn->Execute($sql);
		if ($rs->RecordCount() <= 0)
		{
			//Es la primera pulsación
			$sql = "INSERT INTO KPMG_audio (";
			$sql .= "idEmpresa" . ",";
			$sql .= "idProceso" . ",";
			$sql .= "idPrueba" . ",";
			$sql .= "codIdiomaIso2" . ",";
			$sql .= "idCandidato" . ",";
			$sql .= "nVecesDescargado" . ",";
			$sql .= "fecAlta" . ",";
			$sql .= "fecMod" . ",";
			$sql .= "usuAlta" . ",";
			$sql .= "usuMod" . ")";
			$sql .= " VALUES (";
			$sql .= $conn->qstr($_REQUEST['fIdEmpresa'], false) . ",";
			$sql .= $conn->qstr($_REQUEST['fIdProceso'], false) . ",";
			$sql .= $conn->qstr($_REQUEST['fIdPrueba'], false) . ",";
			$sql .= $conn->qstr($_REQUEST['fCodIdiomaIso2'], false) . ",";
			$sql .= $conn->qstr($_REQUEST['fIdCandidato'], false) . ",";
			$sql .= "1,";
			$sql .= $conn->sysTimeStamp . ",";
			$sql .= $conn->sysTimeStamp . ",";
			$sql .= $conn->qstr(0, false) . ",";
			$sql .= $conn->qstr(0, false) . ")";
			if($conn->Execute($sql) === false){
				echo "Error SQL de consulta I.";
			}
		}else{
			if ($rs){
				while ($arr = $rs->FetchRow()){
					if ($arr['nVecesDescargado'] >=2){
						echo "Ha finalizado las escuchas.";
					}else{
						//Es la segunda pulsación
						$sql = "UPDATE KPMG_audio SET ";
						$sql .= "nVecesDescargado=2";
						$sql .= " WHERE ";
						$sql .=" idEmpresa=" . $conn->qstr($_REQUEST['fIdEmpresa'], false);
						$sql .=" AND idProceso=" . $conn->qstr($_REQUEST['fIdProceso'], false);
						$sql .=" AND idPrueba=" . $conn->qstr($_REQUEST['fIdPrueba'], false);
						$sql .=" AND codIdiomaIso2=" . $conn->qstr($_REQUEST['fCodIdiomaIso2'], false);
						$sql .=" AND idCandidato=" . $conn->qstr($_REQUEST['fIdCandidato'], false);
										
						if($conn->Execute($sql) === false){
							echo "Error SQL de consulta U.";
						}else{
							echo "Ha finalizado las escuchas.";
						}
					}
				}
			}else{
				echo "Error de consulta.";
			}
		}
    }else{
    	echo "Faltan datos.";
    }
?>
