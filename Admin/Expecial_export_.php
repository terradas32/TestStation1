<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

ob_start();
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "ProcesoTablas/ProcesoTablas.php");
	require_once(constant("DIR_WS_COM") . "ToXLS.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . 'Empresas/Empresas.php');
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
	
	
include_once ('include/conexion.php');
	$cUtilidades	= new Utilidades();

	$sPaso=0;
	
	if (!empty($_POST['fPasosNext'])){
		$sPaso = $_POST['fPasosNext'];
	}
	$_POST['fPasosNext'] = $sPaso;
	$sEmpresa = "";
	$sEmpresaOrigen = "";
	if ($_POST['fPasosNext'] == 0)
	{
		//Primera llamada desde tests-station vieja
		if (isset($_REQUEST['call']) && !empty($_REQUEST['call']))
		{
			$sEmpresa = base64_decode($_REQUEST['call']);
			if (!empty($sEmpresa)){
				$aEmpresa = explode("=", $sEmpresa);
				//La empresa puede consultar ???
		//EN PRODUCCION (Diru Admin y las que cuelgan) id: 4492
		//$DIRHU_ADMIN = 4492;
		$DIRHU_ADMIN = 3966; // Cambiado urgencia CONFIDENTIA-PE
	
				$cEmpresaDIRHU = new Empresas();
				$cEmpresaDIRHUDB = new EmpresasDB($conn);
				$sHijosDIRHU = $cEmpresaDIRHUDB->getHijos($DIRHU_ADMIN);
				if (!empty($sHijosDIRHU)){
					$sHijosDIRHU .= $DIRHU_ADMIN;
				}else{
					$sHijosDIRHU = $DIRHU_ADMIN;
				}
				$aDIRHU= explode(",", $sHijosDIRHU);
				if (in_array($aEmpresa[1], $aDIRHU)){
    				$_REQUEST['LSTIdEmpresaOrigen'] = $aEmpresa[1];
    				$sEmpresaOrigen=$aEmpresa[1];
				}else{
					echo("00003 - " . constant("ERR_NO_AUTORIZADO"));
					exit;
				}
			}else{
				echo("00001 - " . constant("ERR_NO_AUTORIZADO"));
				exit;
			}
		}else{
			echo("00000 - " . constant("ERR_NO_AUTORIZADO"));
			exit;
		}
	}else{
		$sEmpresaOrigen =(!empty($_POST['LSTIdEmpresaOrigen'])) ? $_POST['LSTIdEmpresaOrigen'] : "-1";
	}
	
	$sWhereComboEMPRESAS="";
	if (!empty($sEmpresaOrigen)){
		$sIdEmpresaOrigen =$sEmpresaOrigen;
		//cogemos todo slos hijos del padre logado
		require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
		require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
		$cEmpresaPadre = new Empresas();
		$cEmpresaPadreDB = new EmpresasDB($conn);
		$sHijos = $cEmpresaPadreDB->getHijos($sIdEmpresaOrigen);
		if (!empty($sHijos)){
			$sHijos .= $sIdEmpresaOrigen;
		}else{
			$sHijos = $sIdEmpresaOrigen;
		}
//		echo $sIdEmpresaOrigen . "<br />**";
		$sWhereComboEMPRESAS = " idEmpresa IN (" . $sHijos . ") ";
//		echo $sWhereComboEMPRESAS;
	}
	$sWhereComboPROCESOS="";
	if (!empty($_REQUEST['LSTIdEmpresa'])){
		$sWhereComboPROCESOS = " empresa='" . $_REQUEST['LSTIdProceso'] . "'";
	}


	$comboEMPRESAS	= new Combo($conn,"LSTIdEmpresa","idEmpresa",$conn->Concat("usuario", "' - '", "nombre"),"Descripcion","empresas","",constant("SLC_OPCION"), $sWhereComboEMPRESAS,"","orden");
	$comboPROCESOS	= new Combo($conn,"LSTIdProceso","idProceso","nombre","Descripcion","procesos","",constant("SLC_OPCION"), $sWhereComboPROCESOS,"","");

	$sMensaje = "";
	$sDesde=(!empty($_POST['LSTFecDesde'])) ? $_POST['LSTFecDesde'] : "";
	$sHasta=(!empty($_POST['LSTFecHasta'])) ? $_POST['LSTFecHasta'] : "";
	$sEmpresa=(!empty($_REQUEST['LSTIdEmpresa'])) ? $_REQUEST['LSTIdEmpresa'] : "";
	$sProceso=(!empty($_POST['LSTIdProceso'])) ? $_POST['LSTIdProceso'] : "";
	$iConsumos=0;
	$newId=0;
	$bPrepago=false;
	$bExportar=true;
//	echo $sPaso; 
	switch ($sPaso)
	{
		case 1:
			//Sacamos todos los procesos que tengan dado de alta alguna de estas pruebas
			$sql="  SELECT * FROM respuestas_pruebas ";
			$sql.=" WHERE ";
			$sql.=" idEmpresa = '" . $sEmpresa . "'";
			if (!empty($sDesde)){
				$sql.=" AND fecAlta >= '" . $sDesde . " 00:00:00' ";
			}
			if (!empty($sHasta)){
				$sql.=" AND fecAlta <= '" . $sHasta . " 23:59:59' ";
			}
			if (!empty($sProceso)){
				$sql.=" AND idProceso = '" . $sProceso . "'";
			}
			$sql.=" AND idPrueba IN(16, 26, 8)";	// NIPS o VIPS o ELT
			$rsRESPUESTAS_PRUEBAS = $conn->Execute($sql);
			
			$sql = "SELECT MAX(id) AS Max FROM export_especial ";
			$RsID = $conn->Execute($sql);
			if ($RsID){
				while ($arr = $RsID->FetchRow()){
					$newId = $arr['Max'] + 1;
				}
			}else{
				echo("MAX::" . constant("ERR"));
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][EdadesDB]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				exit;
			}
			$sSQL ="";
			$aRespuestasNIPS	= array("","");
			$aRespuestasVIPS	= array("","");
			$aRespuestasELT		= array("","");
			$iConsumos=0;
			$i=0;
			require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
			require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
			$cCandidatosDB = new CandidatosDB($conn);
			
			while (!$rsRESPUESTAS_PRUEBAS->EOF)
			{
				$aRespuestasNIPS	= array("","");
				$aRespuestasVIPS	= array("","");
				$aRespuestasELT		= array("","");
				$iPercentilNIPS		= "";
				$iPercentilVIPS		= "";
				if ($i ==0){
					$sSQL ="INSERT INTO `export_especial` (`id`, `empresa`, `pass`, `empleado`, `apellido1`, `apellido2`, `email`, `fecPrueba`, `correctas_nips`, `contestadas_nips`, `percentil_nips`, `correctas_vips`, `contestadas_vips`, `percentil_vips`, `puntuacion_elt`) VALUES ";
				}
				//miramos el inicio de la contraseña
				//aRespuestas (puntuacion == Correctas,ultima_pregunta==contestadas)
				
				if ($rsRESPUESTAS_PRUEBAS->fields['idPrueba'] == "16"){
					$aRespuestasNIPS	= getCorrectas($conn, "16", $rsRESPUESTAS_PRUEBAS);
					$iConsumos = $iConsumos + 5;	// 5 es la tarifa por el infome en TS viejo
					$iPercentilNIPS = getPercentilNIPS($aRespuestasNIPS[0]);
				}
				if ($rsRESPUESTAS_PRUEBAS->fields['idPrueba'] == "26"){
					$aRespuestasVIPS	= getCorrectas($conn, "26", $rsRESPUESTAS_PRUEBAS);
					$iConsumos = $iConsumos + 5;	// 5 es la tarifa por el infome en TS viejo
					$iPercentilVIPS = getPercentilVIPS($aRespuestasVIPS[0]);
				}
				if ($rsRESPUESTAS_PRUEBAS->fields['idPrueba'] == "8"){
					$aRespuestasELT		= getPuntuacionELT($conn, "8", $rsRESPUESTAS_PRUEBAS);
					$iConsumos = $iConsumos + 5;	// 5 es la tarifa por el infome en TS viejo
				}
				$cCandidatos = new Candidatos();
				$cCandidatos->setIdEmpresa($rsRESPUESTAS_PRUEBAS->fields['idEmpresa']);
				$cCandidatos->setIdProceso($rsRESPUESTAS_PRUEBAS->fields['idProceso']);
				$cCandidatos->setIdCandidato($rsRESPUESTAS_PRUEBAS->fields['idCandidato']);
				$cCandidatos = $cCandidatosDB->readEntidad($cCandidatos);
				$sSQL .="(" . $newId . ", '" . $rsRESPUESTAS_PRUEBAS->fields['idEmpresa'] . "', '" . $rsRESPUESTAS_PRUEBAS->fields['idEmpresa'] . $rsRESPUESTAS_PRUEBAS->fields['idProceso'] . $rsRESPUESTAS_PRUEBAS->fields['idCandidato'] . $rsRESPUESTAS_PRUEBAS->fields['idPrueba'] . $rsRESPUESTAS_PRUEBAS->fields['codIdiomaIso2'] . "', '" . addslashes($cCandidatos->getNombre()) . "', '" . addslashes($cCandidatos->getApellido1()) . "', '" . addslashes($cCandidatos->getApellido2()) . "', '" . $cCandidatos->getMail() . "', '" . $rsRESPUESTAS_PRUEBAS->fields['fecAlta'] . "', '" . $aRespuestasNIPS[0] . "', '" . $aRespuestasNIPS[1] . "', '" . $iPercentilNIPS . "', '" . $aRespuestasVIPS[0] . "', '" . $aRespuestasVIPS[1] . "', '" . $iPercentilVIPS . "', '" . $aRespuestasELT[0] . "'),";
				$rsRESPUESTAS_PRUEBAS->MoveNext();
				$i++;
			}

			if (!empty($sSQL)){
				$sSQL = substr($sSQL, 0, strlen($sSQL)-1) . ";";
//				echo $sSQL;exit;
				if($conn->Execute($sSQL) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Expecial_export]";
					error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo("INS::" . constant("ERR"));
					exit;
				}else{
					$sMensaje = "<br />Se exportaran " . $i . " Registros.";
					$sMensaje .= "<br />Esta operación puede durar varios minutos.";
					$sMensaje .= "<br /><br />Pulse Exportar si está seguro.";
					include('Template/Expecial_Export_/mntexpecial_export.php');
				}
			}else{
				$sMensaje = "No hay resultados.";
				include('Template/Expecial_Export_/mntexpecial_export.php');
			}
			break;
		default:
			include('Template/Expecial_Export_/mntexpecial_export.php');
			break;
	} // end switch
	
	function getCorrectas($conn, $sPrueba, $rowRESPUESTAS_PRUEBAS){
		require_once(constant("DIR_WS_COM") . "Utilidades.php");
		$cUtilidades	= new Utilidades();
		
		$sSQL="SELECT * ";
		$sSQL.="FROM respuestas_pruebas_items ";
		$sSQL.="WHERE idEmpresa='" . $rowRESPUESTAS_PRUEBAS->fields['idEmpresa'] . "' ";
		$sSQL.="AND idProceso='" . $rowRESPUESTAS_PRUEBAS->fields['idProceso'] . "' ";
		$sSQL.="AND idCandidato='" . $rowRESPUESTAS_PRUEBAS->fields['idCandidato'] . "' ";
		$sSQL.="AND idPrueba='" . $rowRESPUESTAS_PRUEBAS->fields['idPrueba'] . "' ";
		$sSQL.="AND codIdiomaIso2='" . $rowRESPUESTAS_PRUEBAS->fields['codIdiomaIso2'] . "' ";
		$sSQL.="ORDER BY idProceso,idCandidato,idItem ASC";
		
		$vVector = $conn->Execute($sSQL);
		trataValores($vVector, "respuestas_pruebas_items");
		
		$puntuacion=0;
		$ultima_pregunta=0;
			
		$sSQL="SELECT SUM(valor) AS valor, COUNT(*) AS respuestas ";
		$sSQL.="FROM respuestas_pruebas_items ";
		$sSQL.="WHERE idEmpresa='" . $rowRESPUESTAS_PRUEBAS->fields['idEmpresa'] . "' ";
		$sSQL.="AND idProceso='" . $rowRESPUESTAS_PRUEBAS->fields['idProceso'] . "' ";
		$sSQL.="AND idCandidato='" . $rowRESPUESTAS_PRUEBAS->fields['idCandidato'] . "' ";
		$sSQL.="AND idPrueba='" . $rowRESPUESTAS_PRUEBAS->fields['idPrueba'] . "' ";
		$sSQL.="AND codIdiomaIso2='" . $rowRESPUESTAS_PRUEBAS->fields['codIdiomaIso2'] . "' ";
//		echo "<br />" . $sSQL;
		$rs = $conn->Execute($sSQL);
		$i=0;
		$puntuacion=0;
		$ultima_pregunta = 0;
		while (!$rs->EOF)
		{
			$puntuacion = $rs->fields['valor'];
			$ultima_pregunta = $rs->fields['respuestas'];
				$rs->MoveNext();
			$i++;
		}

		return array($puntuacion,$ultima_pregunta);
	}
	
	function getPuntuacionELT($conn, $sPrueba, $rowRESPUESTAS_PRUEBAS){
		$puntuacion=0;
		$ultima_pregunta=0;
		
		$sSQL="SELECT SUM(valor) AS valor, COUNT(*) AS respuestas ";
		$sSQL.="FROM respuestas_pruebas_items ";
		$sSQL.="WHERE idEmpresa='" . $rowRESPUESTAS_PRUEBAS->fields['idEmpresa'] . "' ";
		$sSQL.="AND idProceso='" . $rowRESPUESTAS_PRUEBAS->fields['idProceso'] . "' ";
		$sSQL.="AND idCandidato='" . $rowRESPUESTAS_PRUEBAS->fields['idCandidato'] . "' ";
		$sSQL.="AND idPrueba='" . $rowRESPUESTAS_PRUEBAS->fields['idPrueba'] . "' ";
		$rs = $conn->Execute($sSQL);
		$i=0;
		$puntuacion=0;
		$ultima_pregunta = 0;
		while (!$rs->EOF)
		{
			$puntuacion = $rs->fields['valor'];
			$ultima_pregunta = $rs->fields['respuestas'];
				$rs->MoveNext();
			$i++;
		}
		
		return array($puntuacion,$ultima_pregunta);
	}
	
	function getPercentilNIPS($pd){
		//BAREMO ESTANDAR
		$Baremo_Estandar=0;
		if ($pd==0) $Baremo_Estandar=1;
		if ($pd==1) $Baremo_Estandar=1;
		if ($pd==2) $Baremo_Estandar=2;
		if ($pd==3) $Baremo_Estandar=5;
		if ($pd==4) $Baremo_Estandar=12;
		if ($pd==5) $Baremo_Estandar=21;
		if ($pd==6) $Baremo_Estandar=34;
		if ($pd==7) $Baremo_Estandar=50;
		if ($pd==8) $Baremo_Estandar=66;
		if ($pd==9) $Baremo_Estandar=79;
		if ($pd==10) $Baremo_Estandar=88;
		if ($pd==11) $Baremo_Estandar=95;
		if ($pd==12) $Baremo_Estandar=98;
		if ($pd==13) $Baremo_Estandar=99;
		if ($pd==14) $Baremo_Estandar=99;
		if ($pd==15) $Baremo_Estandar=99;
		return $Baremo_Estandar;
	}
	
	function getPercentilVIPS($pd){
		//BAREMO ESTANDAR
		$Baremo_Estandar=0;
	    if ($pd >= 0 && $pd < 12) $Baremo_Estandar=1;
	    if ($pd==12) $Baremo_Estandar=2;
	    if ($pd==13) $Baremo_Estandar=3;
	    if ($pd==14) $Baremo_Estandar=5;
	    if ($pd==15) $Baremo_Estandar=8;
	    if ($pd==16) $Baremo_Estandar=12;
	    if ($pd==17) $Baremo_Estandar=16;
	    if ($pd==18) $Baremo_Estandar=24;
	    if ($pd==19) $Baremo_Estandar=31;
	    if ($pd==20) $Baremo_Estandar=38;
	    if ($pd==21) $Baremo_Estandar=46;
	    if ($pd==22) $Baremo_Estandar=54;
	    if ($pd==23) $Baremo_Estandar=66;
	    if ($pd==24) $Baremo_Estandar=73;
	    if ($pd==25) $Baremo_Estandar=79;
	    if ($pd==26) $Baremo_Estandar=84;
	    if ($pd==27) $Baremo_Estandar=88;
	    if ($pd==28) $Baremo_Estandar=93;
	    if ($pd==29) $Baremo_Estandar=96;
	    if ($pd==30) $Baremo_Estandar=97;
	    if ($pd==31) $Baremo_Estandar=98;
	    if ($pd>=32 && $pd<=39) $Baremo_Estandar=99;

	    return $Baremo_Estandar;
	}
	function trataValores($rs, $nombre){
		$i=0;
		while (!$rs->EOF){
			setValor($rs, $nombre, $i);
			$i++;
			$rs->MoveNext();
		}
	}
	function setValor($rsLine, $nombre, $i){
		global $conn;
		global $cUtilidades;
		global $_idEmpresa;
		global $_descEmpresa;
		global $_idProceso;
		global $_descProceso;
		global $_codIdiomaIso2;
		global $_descIdiomaIso2;
		global $_idPrueba;
		global $_descPrueba;
		global $_idCandidato;
		
		require_once(constant("DIR_WS_COM") . "Items/ItemsDB.php");
		require_once(constant("DIR_WS_COM") . "Items/Items.php");
		$cItems = new Items();
		$cItems->setCodIdiomaIso2($rsLine->fields['codIdiomaIso2']);
		$cItems->setIdPrueba($rsLine->fields['idPrueba']);
		$cItems->setIdItem($rsLine->fields['idItem']);
//		$cItems->setIdOpcion($rsLine->fields['idOpcion']);
		
		$cItemsDB = new ItemsDB($conn);
		$cItems = $cItemsDB->readEntidad($cItems);
		$sValor="";
		$sValor=$cUtilidades->getValorCalculadoPRUEBAS($rsLine,$cItems, $conn);
		if ($i==0){
			$_idEmpresa=$rsLine->fields['idEmpresa'];
			$_descEmpresa=$rsLine->fields['descEmpresa'];
			$_idProceso=$rsLine->fields['idProceso'];
			$_descProceso=$rsLine->fields['descProceso'];
			$_codIdiomaIso2=$rsLine->fields['codIdiomaIso2'];
			$_descIdiomaIso2=$rsLine->fields['descIdiomaIso2'];
			$_idPrueba=$rsLine->fields['idPrueba'];
			$_descPrueba=$rsLine->fields['descPrueba'];
			$_idCandidato=$rsLine->fields['idCandidato'];
		}
		$sWHERE = ""; 
		$sWHERE .= " WHERE idEmpresa='" . $rsLine->fields['idEmpresa'] . "' ";
		$sWHERE .= " AND idProceso='" . $rsLine->fields['idProceso'] . "' ";
		$sWHERE .= " AND idCandidato='" . $rsLine->fields['idCandidato'] . "' ";
		$sWHERE .= " AND codIdiomaIso2='" . $rsLine->fields['codIdiomaIso2'] . "' ";
		$sWHERE .= " AND idPrueba='" . $rsLine->fields['idPrueba'] . "' ";
		$sWHERE .= " AND idItem='" . $rsLine->fields['idItem'] . "' ";
		$sWHERE .= " AND idOpcion='" . $rsLine->fields['idOpcion'] . "' ";
		
		$sSQL = " UPDATE " . $nombre . " SET valor='" . $sValor . "' " . $sWHERE;
//		echo "<br />" . $sSQL;
		$conn->Execute($sSQL);
	}
	
?>