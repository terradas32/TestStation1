<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	include_once(constant("DIR_WS_INCLUDE") . 'Seguridad.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "ExportAptitudinales/ExportAptitudinalesDB.php");
	require_once(constant("DIR_WS_COM") . "ExportAptitudinales/ExportAptitudinales.php");
	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
	
include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
	$cUtilidades	= new Utilidades();
	
	$cEntidadDB	= new ExportAptitudinalesDB($conn);  // Entidad DB
	$cEntidad	= new ExportAptitudinales();  // Entidad
	$cBaremos_resultadosDB = new Baremos_resultadosDB($conn);
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	
	$sPaso=0;

	if (isset($_POST['fPasosNext'])){
		$sPaso = $_POST['fPasosNext'];
	}
	if ($_POST['MODO'] == 0){
		$sPaso=0;
		$_POST['MODO']    = constant("MNT_LISTAR");
	}
	
	$_POST['fPasosNext'] = $sPaso;
//	echo $sPaso;exit;
	$sCol1='';
	$sCol2='';
	$sHijos = "";
	
	require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
	require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
	$cEmpresaPadre = new Empresas();
	$cEmpresaPadreDB = new EmpresasDB($conn);
	$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
	//	$_EmpresaLogada = constant("EMPRESA_PE");
	if (empty($_POST["fHijos"]))
	{
		$sHijos = $cEmpresaPadreDB->getHijos($_EmpresaLogada);
		if (!empty($sHijos)){
			$sHijos .= $_EmpresaLogada;
		}else{
			$sHijos = $_EmpresaLogada;
		}
	}else{
		$sHijos = $_POST["fHijos"];
	}
	$sSQLPruebaIN = "";
	if (!empty($_POST["fIdEmpresa"])){
		$cEmpresaPadre->setIdEmpresa($_POST["fIdEmpresa"]);
		$cEmpresaPadre = $cEmpresaPadreDB->readEntidad($cEmpresaPadre);
		$sSQLPruebaIN = $cEmpresaPadre->getIdsPruebas();
		if (!empty($sSQLPruebaIN)){
			//chequeamos si el primer caracter es una coma
			if (substr($sSQLPruebaIN, 0, 1) == ","){
				$sSQLPruebaIN = substr($sSQLPruebaIN, 1);
			}
			$sSQLPruebaIN = " idPrueba IN (" . $sSQLPruebaIN . ") ";
		}
	}
	$comboEMPRESAS	= new Combo($conn,"fIdEmpresa","idEmpresa","nombre","Descripcion","empresas","",constant("SLC_OPCION"),"idEmpresa IN(" . $sHijos . ")","","orden");
	$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","",constant("SLC_OPCION"),"","","fecMod");
	$comboPRUEBASGROUP	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","","","bajaLog=0 AND listar=1","","idPrueba","");
	
	//echo('modo:' . $_POST['MODO']);
	
	if ($_POST['fPasosNext'] == 0)
	{
		if (!empty($_EmpresaLogada)){
			$_REQUEST['LSTIdEmpresaOrigen'] = $_EmpresaLogada;
   			$sEmpresaOrigen=$_EmpresaLogada;
		}else{
			echo("00001 - " . constant("ERR_NO_AUTORIZADO"));
			exit;
		}
	}else{
		$sEmpresaOrigen =(!empty($_POST['LSTIdEmpresaOrigen'])) ? $_POST['LSTIdEmpresaOrigen'] : "-1";
	}
	

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
//	echo('modo:' . $_POST['MODO']);
	
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
			$sql.=" AND idPrueba IN(" . $_POST['fIdsPruebas'] . ")";
			$sql.=" ORDER BY idCandidato, idPrueba ";	
//			echo $sql;exit;
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
			$aRespuestas	= array("","","");
			
			$iConsumos=0;
			$i=0;
			require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
			require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
			$cProcesosDB = new ProcesosDB($conn);
			
			require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
			require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
			$cCandidatosDB = new CandidatosDB($conn);
			$fecAltaProceso = "";
			while (!$rsRESPUESTAS_PRUEBAS->EOF)
			{
				$aRespuestas	= array("","","");
				
				$iPercentil		= "";

				if ($i ==0){
					$cProcesos = new Procesos();
					$cProcesos->setIdEmpresa($rsRESPUESTAS_PRUEBAS->fields['idEmpresa']);
					$cProcesos->setIdProceso($rsRESPUESTAS_PRUEBAS->fields['idProceso']);
					$cProcesos = $cProcesosDB->readEntidad($cProcesos);
					$fecAltaProceso = $cProcesos->getFecAlta();
					
					$sSQL ="INSERT INTO `export_especial` (`id`, `empresa`, `pass`, `empleado`, `apellido1`, `apellido2`, `email`, `fecPrueba`, `fecAltaProceso`";
					$sSQL .=", `prueba`, `correctas`, `contestadas`, `percentil`";
					$sSQL .=") VALUES ";
				}
			
				$aRespuestas	= getCorrectas($conn, $rsRESPUESTAS_PRUEBAS->fields['idPrueba'], $rsRESPUESTAS_PRUEBAS);
				$iConsumos = $iConsumos + 5;	// 5 es la tarifa por el infome en TS viejo
				
				$cCandidatos = new Candidatos();
				$cCandidatos->setIdEmpresa($rsRESPUESTAS_PRUEBAS->fields['idEmpresa']);
				$cCandidatos->setIdProceso($rsRESPUESTAS_PRUEBAS->fields['idProceso']);
				$cCandidatos->setIdCandidato($rsRESPUESTAS_PRUEBAS->fields['idCandidato']);
				$cCandidatos = $cCandidatosDB->readEntidad($cCandidatos);
				$sSQL .="(" . $newId . ", '" . $rsRESPUESTAS_PRUEBAS->fields['idEmpresa'] . "', '" . $rsRESPUESTAS_PRUEBAS->fields['idEmpresa'] . $rsRESPUESTAS_PRUEBAS->fields['idProceso'] . $rsRESPUESTAS_PRUEBAS->fields['idCandidato'] . $rsRESPUESTAS_PRUEBAS->fields['idPrueba'] . $rsRESPUESTAS_PRUEBAS->fields['codIdiomaIso2'] . "', '" . addslashes($cCandidatos->getNombre()) . "', '" . addslashes($cCandidatos->getApellido1()) . "', '" . addslashes($cCandidatos->getApellido2()) . "', '" . $cCandidatos->getMail() . "', '" . $rsRESPUESTAS_PRUEBAS->fields['fecAlta'] . "', '" . $fecAltaProceso . "'";
				$sSQL .=", '" . $comboPRUEBASGROUP->getDescripcionCombo($rsRESPUESTAS_PRUEBAS->fields['idPrueba']) . "', '" . $aRespuestas[0] . "', '" . $aRespuestas[1] . "', '" . $aRespuestas[2] . "'";
				$sSQL .="),";
				$rsRESPUESTAS_PRUEBAS->MoveNext();
				$i++;
			}

			if (!empty($sSQL)){
				$sSQL = substr($sSQL, 0, strlen($sSQL)-1) . ";";
				if($conn->Execute($sSQL) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Expecial_export]";
					error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo("INS::" . constant("ERR"));
					exit;
				}else{
					$sMensaje = "<br />Se exportaran " . $i . " Registros.";
					$sMensaje .= "<br />Esta operación puede durar varios minutos.";
					$sMensaje .= "<br /><br />Pulse sobre el icono Excel para descargar las puntuaciones.";
					include('Template/ExportAptitudinales/mntexportAptitudinales.php');
				}
			}else{
				$sMensaje = "No hay resultados.";
				include('Template/ExportAptitudinales/mntexportAptitudinales.php');
			}
			break;
		default:
			include('Template/ExportAptitudinales/mntexportAptitudinales.php');
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

//		echo "<br />" . $sSQL;

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
		$iPercentil = getPercentil($puntuacion, 1, $rowRESPUESTAS_PRUEBAS->fields['idPrueba']);
		return array($puntuacion, $ultima_pregunta, $iPercentil);
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
	
	function getPercentil($iPDirecta, $idBaremo, $idPrueba){

		global $cBaremos_resultadosDB;
		global $conn;
		$iPercentil = -1;
		
		$cBaremos_resultados = new Baremos_resultados();
		$cBaremos_resultados->setIdBaremo($idBaremo);
		$cBaremos_resultados->setIdPrueba($idPrueba);
		$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
//		echo "<br />" . $sqlBaremosResultados;
		$listaBaremosResultados = $conn->Execute($sqlBaremosResultados);
		$ipMin=0;
		$ipMax=0;
		// Recorremos la lista de los valores del baremo seleccionado para mirar el percentil que 
		// corresponde con la puntuación directa obtenida.
		if($listaBaremosResultados->recordCount()>0){
			while(!$listaBaremosResultados->EOF){
				
				$ipMin = $listaBaremosResultados->fields['puntMin'];
				$ipMax = $listaBaremosResultados->fields['puntMax'];
				if($ipMin <= $iPDirecta && $iPDirecta <= $ipMax){
					$iPercentil = $listaBaremosResultados->fields['puntBaremada'];
				}
				$listaBaremosResultados->MoveNext();
			}
		}
		
		return $iPercentil;
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
	
	function getPercentilTIC($pd){
		//BAREMO ESTANDAR
		$Baremo_Estandar=0;
		if ($pd==0) $Baremo_Estandar=1;
		if ($pd==1) $Baremo_Estandar=3;
		if ($pd==2) $Baremo_Estandar=5;
		if ($pd==3) $Baremo_Estandar=12;
		if ($pd==4) $Baremo_Estandar=18;
		if ($pd==5) $Baremo_Estandar=31;
		if ($pd==6) $Baremo_Estandar=46;
		if ($pd==7) $Baremo_Estandar=58;
		if ($pd==8) $Baremo_Estandar=73;
		if ($pd==9) $Baremo_Estandar=82;
		if ($pd==10) $Baremo_Estandar=90;
		if ($pd==11) $Baremo_Estandar=95;
		if ($pd==12) $Baremo_Estandar=98;
		if ($pd==13) $Baremo_Estandar=99;
		return $Baremo_Estandar;
	}
	function getPercentilTAC($pd){
		//BAREMO ESTANDAR
		$Baremo_Estandar=0;
		if ($pd==0) $Baremo_Estandar=2;
		if ($pd==1) $Baremo_Estandar=4;
		if ($pd==2) $Baremo_Estandar=8;
		if ($pd==3) $Baremo_Estandar=14;
		if ($pd==4) $Baremo_Estandar=24;
		if ($pd==5) $Baremo_Estandar=34;
		if ($pd==6) $Baremo_Estandar=50;
		if ($pd==7) $Baremo_Estandar=66;
		if ($pd==8) $Baremo_Estandar=76;
		if ($pd==9) $Baremo_Estandar=86;
		if ($pd==10) $Baremo_Estandar=92;
		if ($pd==11) $Baremo_Estandar=96;
		if ($pd==12) $Baremo_Estandar=98;
		if ($pd==13) $Baremo_Estandar=99;
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
	
	function getPercentilEN1($pd){
		$Baremo_Estandar=-1;

	    return $Baremo_Estandar;
	}
	function getPercentilVN1($pd){
		$Baremo_Estandar=-1;

	    return $Baremo_Estandar;
	}
	function getPercentilNN1($pd){
		$Baremo_Estandar=-1;

	    return $Baremo_Estandar;
	}
	function getPercentilDN1($pd){
		$Baremo_Estandar=-1;

	    return $Baremo_Estandar;
	}
	function getPercentilDIPS($pd){
		$Baremo_Estandar=0;
	    if ($pd >= 0 && $pd < 7) $Baremo_Estandar=1;
	    if ($pd==8) $Baremo_Estandar=2;
	    if ($pd==9) $Baremo_Estandar=4;
	    if ($pd==10) $Baremo_Estandar=8;
	    if ($pd==11) $Baremo_Estandar=12;
	    if ($pd==12) $Baremo_Estandar=18;
	    if ($pd==13) $Baremo_Estandar=24;
	    if ($pd==14) $Baremo_Estandar=34;
	    if ($pd==15) $Baremo_Estandar=46;
	    if ($pd==16) $Baremo_Estandar=54;
	    if ($pd==17) $Baremo_Estandar=66;
	    if ($pd==18) $Baremo_Estandar=73;
	    if ($pd==19) $Baremo_Estandar=82;
	    if ($pd==20) $Baremo_Estandar=86;
	    if ($pd==21) $Baremo_Estandar=92;
	    if ($pd==22) $Baremo_Estandar=96;
	    if ($pd==23) $Baremo_Estandar=97;
	    if ($pd>=24 && $pd<=30) $Baremo_Estandar=99;

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