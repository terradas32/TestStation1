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
	
include_once ('include/conexion.php');
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$connMssql = NewADOConnection('ado_mssql'); 
		$connMssql->charPage = 65001;	//utf8
		$dsn ='Provider=SQLNCLI;Server=' . constant("DB_HOST_MS") . ';Database=' . constant("DB_DATOS_MS") . ';Uid=' . constant("DB_USUARIO_MS") . ';Pwd=' . constant("DB_PASSWORD_MS");  
		$connMssql->Connect($dsn);
		$connMssql->SetFetchMode(constant("ADODB_FETCH_ASSOC")); 
		if (empty($connMssql)){			
	        echo(constant("ERR") . " MS SQL SERVER");
			exit;
	    }
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$cUtilidades	= new Utilidades();
	$cEntidad	= new ProcesoTablas($conn, $connMssql);
	$sPaso=0;
	//Pasos del proceso
	$sPasos="1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60";
	if (!empty($_POST['fPasosNext'])){
		$aPasos = explode(",", $_POST['fPasosNext']);
		$sPaso = $aPasos[0];
		unset($aPasos[0]);
		$sPasos=implode(",", $aPasos);;
	}
	$_POST['fPasosNext'] = $sPasos;
	if ($sPaso == 1){
		if (!empty($_POST['fPass']) && $_POST['fPass'] != constant("DB_PASSWORD")){
			echo("00000 - " . constant("ERR_NO_AUTORIZADO"));
			exit;
		}
	}
	echo  "->" . $sPaso;
	switch ($sPaso)
	{
		case 1:
			$sFileName = "empresas";
			$sql="SELECT * FROM " . $sFileName ;
			$MsListaEMPRESAS =& $connMssql->Execute($sql);
			$cEntidad->creaSQL($MsListaEMPRESAS, $connMssql, $sFileName);
			break;
		case 2:
		    $sFileName = "administradores";
			$sql="SELECT * FROM " . $sFileName ;
			$connMssql = $cEntidad->connMssql;
			$MsListaADMINISTRADORES =& $connMssql->Execute($sql);
			$cEntidad->creaSQL($MsListaADMINISTRADORES, $connMssql, $sFileName);
			break;
		case 3:
		    $sFileName = "test";	
			$sql="SELECT * FROM " . $sFileName ;
			$connMssql = $cEntidad->connMssql;
			$MsListaTEST =& $connMssql->Execute($sql);
			$cEntidad->creaSQL($MsListaTEST, $connMssql, $sFileName);
			break;
		case 4:
			$cEntidad->cargaCompetencias();
			break;
		case 5:
			$cEntidad->cargaEscalas();
			break;
		case 6:
			$cEntidad->cargaItemsPrueba("nips", "o", "es");
			break;
		case 7:
			$cEntidad->cargaBaremosResultados();
			break;
		case 8:
			$cEntidad->cargaItemsPrueba("nips", "o", "pt");
			break;
		case 9:
			$cEntidad->cargaItemsPrueba("nips1", "n", "es");
			break;
		case 10:
			$cEntidad->cargaItemsPrueba("nips1", "n", "en");
			break;
		case 11:
			$cEntidad->cargaItemsPrueba("nips1", "n", "pt");
			break;
		case 12:
			$cEntidad->cargaItemsPrueba("vips", "y", "es");
			break;
		case 13:
			// NO HAY EN INGLÉS
			//$cEntidad->cargaItemsPrueba("vips", "y", "en");
			break;
		case 14:
			$cEntidad->cargaItemsPrueba("vips", "y", "pt");
			break;
		case 15:
			$cEntidad->cargaItemsPrueba("vips1", "v", "es");
			break;
		case 16:
			$cEntidad->cargaItemsPrueba("vips1", "v", "en");
			break;
		case 17:
			$cEntidad->cargaItemsPrueba("vips1", "v", "pt");
			break;
		case 18:
			$cEntidad->cargaItemsPrueba("prisma", "w", "es");
			break;
		case 19:
			$cEntidad->cargaItemsPrueba("prisma", "w", "cat");
			break;
		case 20:
			$cEntidad->cargaItemsPrueba("prisma", "w", "en");
			break;
		case 21:
			$cEntidad->cargaItemsPrueba("prisma", "w", "pt");
			break;
		case 22:
		    $cEntidad->cargaEscaLasCompetenciasPrisma("w");
			break;
		case 23:
			$cEntidad->cargaItemsInversosPrisma("w");
			break;
		case 24:
			$cEntidad->cargaItemsPrueba("cpl", "g", "es");
			break;
		case 25:
			$cEntidad->cargaItemsPrueba("cpl32", "q", "es");
			break;
		case 26:
			$cEntidad->cargaItemsPrueba("cpl32", "q", "en");
			break;
		case 27:
			$cEntidad->cargaItemsPrueba("cpl32", "q", "cat");
			break;
		case 28:
			$cEntidad->cargaItemsPrueba("cpl32", "q", "pt");
			break;
		case 29:
			$cEntidad->cargaAreas();
			break;
		case 30:
			$cEntidad->cargaBaremos(16);//nips
			break;
		case 31:
			$cEntidad->cargaBaremos(24);//prisma
			break;
		case 32:
			$cEntidad->cargaBaremos(26);//vips
			break;
		case 33:
			$cEntidad->borrarCandidatos();//mySQL
			break;
		case 34:
			$cEntidad->cargaCorreosBase();//mySQL
			break;
		case 35:
			$cEntidad->cargaCorreosLiterales();//mySQL
			break;
		case 36:
			$cEntidad->borrarCorreosProceso();//mySQL
			break;
		case 37:
			$cEntidad->borrarDescargasInformes();//mySQL
			break;
		case 38:
			$cEntidad->cargaEdades();//mySQL
			break;
		case 39:
			$cEntidad->cargaEjemplosPruebas();//mySQL
			break;
		case 40:
			$cEntidad->borrarEmpresas_accesos();//mySQL
			break;
		case 41:
			$cEntidad->cargaFormaciones();//mySQL
			break;
		case 42:
			$cEntidad->cargaInformes_pruebas();//mySQL
			break;
		case 43:
			$cEntidad->cargaInstrucciones_pruebas();//mySQL
			break;
		case 44:
			$cEntidad->cargaNivelesJerarquicos();//mySQL
			break;
		case 45:
			$cEntidad->cargaNotificaciones();//mySQL
			break;			
		case 46:
			$cEntidad->cargaOpciones_ejemplos();//mySQL
			break;
		case 47:
			$cEntidad->cargaSexos();//mySQL
			break;
		case 48:
			//$cEntidad->cargaOpciones_ItemsESP();//mySQL
			break;
		case 49:
			$cEntidad->cargaEjemplos_ayudas();//mySQL
			break;
		case 50:
			$cEntidad->cargaPruebas_ayudas();//mySQL
			break;
		case 51:
			$cEntidad->actualizaItems_Nips();//mySQL
			break;
		case 52:
			$cEntidad->cargaTablasInformes();//mySQL
			break;			
		case 53:
			$cEntidad->cargaSignos();//mySQL
			break;
		case 54:
			$cEntidad->cargaSeccionesInformes();//mySQL
			break;
		case 55:
			$cEntidad->cargaTextosSecciones();//mySQL
			break;
		case 56:
			$cEntidad->cargaRangosIr();//mySQL
			break;
		case 57:
			$cEntidad->cargaRangosIp();//mySQL
			break;
		case 58:
			$cEntidad->cargaRangosTextos();//mySQL
			break;	
		case 59:
			$cEntidad->cargaEmk_charsets();//mySQL
			break;
		case 60:
			$cEntidad->cargaModo_realizacion();//mySQL
			break;
		default:
			break;
	} // end switch
	include('Template/ProcesoTablas/mntprocesotablas.php');
?>