<?php
    header('Content-Type: text/html; charset=utf-8');
    header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    // No es compatible con noback header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
//
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005 - Negocia Internet                                |
// +----------------------------------------------------------------------+
// | Este proceso se encarga de actualizar las tablas de testStation      |
// | de la Base de datos Antigua a la nueva.                              |
// +----------------------------------------------------------------------+
// | Author: Pedro Borregán <pborregan@negociainternet.com>               |
// +----------------------------------------------------------------------+
//
// $Id: ProcesoTablas.php,v 1.0 11/01/2011
//
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');

//Seguridad para ejecución del proceso por personal autorizado.
if (!isset($_GET["fAutorizado"]) || empty($_GET["fAutorizado"]) || $_GET["fAutorizado"] != "69"){
	 echo(constant("ERR_NO_AUTORIZADO"));
	 exit;
}

	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	

	/////////////////////////////////		MYSQL		//////////////////////////////////
include_once ('include/conexion.php');
	
//	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
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
/* Array de definición de tablas
 * array ( "TablaOrigen"  => "TablaDestino", array (
 * 			"CampoOrigen0" => "CampoDestino0",
 *	 		"CampoOrigen1" => "CampoDestino1"
 * 		)
 * );
 */
    $aTablas = array ( "empresas"  => "empresas", array (
			"id" => "idEmpresa",
			"nombre" => "nombre",
			"usuario" => "usuario",
			"pass" => "password",
			"mail" => "mail",
			"mail2" => "mail2",
			"mail3" => "mail3",
			"cliente" => "",
			"fecha_alta" => "fecAlta",
			"fecha_caducidad" => "",
			"logo" => "pathLogo",
			"ncandidatos" => "ncandidatos",
			"mailcorreccion" => "",
			"prepago" => "prepago",
			"dongles" => "dongles",
			"pais" => "idPais",
			"umbral_aviso" => "umbral_aviso",
			"distribuidor" => "distribuidor",
			"propietario" => "idPadre",
			"procedencia" => "direccion",
			"correocandidato" => ""
		), 
	  "administradores"  => "wi_usuarios", array (
			"id" => "idUsuario",
			"usuario" => "login",
			"contraseña" => "password",
			"nivel" => "idUsuarioTipo"
		),
	  "test"  => "pruebas", array (
			"test" => "nombre",
			"orden" => "observaciones",
			"letra" => "idPrueba"
		),
	  "otraTabla"  => "tabla2", array (
			"id" => "idEmpresa",
			"nombre" => "nombre",
			"usuario" => "usuario",
			"pass" => "password",
			"mail" => "mail",
			"mail2" => "mail2",
			"mail3" => "mail3",
			"cliente" => "",
			"fecha_alta" => "fecAlta",
			"fecha_caducidad" => "",
			"logo" => "pathLogo",
			"ncandidatos" => "ncandidatos",
			"mailcorreccion" => "direccion",
			"prepago" => "prepago",
			"dongles" => "dongles",
			"pais" => "idPais",
			"umbral_aviso" => "umbral_aviso",
			"distribuidor" => "distribuidor",
			"propietario" => "idPadre",
			"procedencia" => "",
			"correocandidato" => ""
		)
	);
	
	$aRetorno = array ( 
		"sTablaMySQL" => "",
		"sCampoMySQL" => "",
		"sValorMySQL" => ""
	);
    
/*
	$sFileName = "empresas";
	$sql="SELECT * FROM " . $sFileName ;
	$MsListaEMPRESAS =& $connMssql->Execute($sql);
	creaSQL($MsListaEMPRESAS, $connMssql, $sFileName);
	echo "<br />empresas";
	
    $sFileName = "administradores";
	$sql="SELECT * FROM " . $sFileName ;
	$MsListaADMINISTRADORES =& $connMssql->Execute($sql);
	creaSQL($MsListaADMINISTRADORES, $connMssql, $sFileName);
    echo "<br />administradores";
*/

    $sFileName = "test";	
	$sql="SELECT * FROM " . $sFileName ;
	$MsListaTEST =& $connMssql->Execute($sql);
	creaSQL($MsListaTEST, $connMssql, $sFileName);
    echo "<br />test";

/*
	cargaItemsPrueba("nips", "o", "es");
	echo "<br />        nips - es";
	cargaItemsPrueba("nips", "o", "en");
	echo "<br />        nips - en";
	cargaItemsPrueba("nips", "o", "pt");
	echo "<br />        nips - pt";
*/

//	cargaItemsPrueba("nips1", "n", "es");
//	echo "<br />        nips1 - es";
//	cargaItemsPrueba("nips1", "n", "en");
//	echo "<br />        nips1 - en";
//	cargaItemsPrueba("nips1", "n", "pt");
//	echo "<br />        nips1 - pt";

/*
	cargaItemsPrueba("vips", "y", "es");
	echo "<br />        vips - es";
	cargaItemsPrueba("vips", "y", "en");
	echo "<br />        vips - en";
	cargaItemsPrueba("vips", "y", "pt");
	echo "<br />        vips - pt";
*/
/*
	cargaItemsPrueba("vips1", "v", "es");
	echo "<br />        vips1 - es";
	cargaItemsPrueba("vips1", "v", "en");
	echo "<br />        vips1 - en";
	cargaItemsPrueba("vips1", "v", "pt");
	echo "<br />        vips1 - pt";
*/

//	cargaItemsPrueba("prisma", "w", "es");
//	echo "<br />        prisma - es";
//	cargaItemsPrueba("prisma", "w", "cat");
//	echo "<br />        prisma - ca";
//	cargaItemsPrueba("prisma", "w", "en");
//	echo "<br />        prisma - en";
//	cargaItemsPrueba("prisma", "w", "pt");
//	echo "<br />        prisma - pt";

/*
	cargaItemsPrueba("cpl", "g", "es");
	echo "<br />        cpl - es";
*/

//	cargaItemsPrueba("cpl32", "q", "es");
//	echo "<br />        cpl32 - es";
//	cargaItemsPrueba("cpl32", "q", "en");
//	echo "<br />        cpl32 - en";
//	cargaItemsPrueba("cpl32", "q", "cat");
//	echo "<br />        cpl32 - ca";
//	cargaItemsPrueba("cpl32", "q", "pt");
//	echo "<br />        cpl32 - pt";

	echo "<br /><strong>*** F I N ***</strong>";
	
	function creaSQL($result, $connMssql, $sTablaSQLSERVER)
	{
		global $conn;
		global $aRetorno;
		
		$aTablaMySQL = array();
		$i=0;
		$arr = $result->GetArray();
		for ($j=0, $max=sizeof($arr); $j < $max; $j++){
			$aProp = $arr[$j];
			$aRetorno = getCampoValorMySQL($sTablaSQLSERVER);
			$sql=" INSERT INTO " . $aRetorno["sTablaMySQL"] . " ";
			$sKeys= "";
			$sValues= "";
		    foreach($aProp as $k => $v) {
		    	$aRetorno = getCampoValorMySQL($sTablaSQLSERVER, $k, $aProp[$k]);
		    	if (!empty($aRetorno["sCampoMySQL"])){
	                 $sKeys.= "," . $aRetorno["sCampoMySQL"];
	                 $sValues.= "," . $conn->qstr($aRetorno["sValorMySQL"], 0);
		    	}
			}
			$sql.= "(" . substr($sKeys, 1) . ")";
			$sql.= " VALUES ";
			$sql.= "(" . substr($sValues, 1) . ")";
			crea_fichero(constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . "SQLFiles/" . $aRetorno["sTablaMySQL"] . ".sql",str_replace("\r\n","\\n", $sql) . ";\n", $j, $aRetorno["sTablaMySQL"]);			
		}
		casuisticasEspeciales($aRetorno["sTablaMySQL"]);
	}
	function crea_fichero($file, $content, $i, $sTabla)
	{
		if ($i > 0 ){
			$handle=fopen($file,"a") or die ("Error creando Fichero" . $file);
		}else{
			if (!vaciarTabla($sTabla)){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]::vaciarTabla()";
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$handle=fopen($file,"w") or die ("Error creando Fichero" . $file);
		}
		
		fwrite($handle,$content) or die ("No se puede escribir en el Fichero" . $file);
		fclose($handle);
		if (!cargaMySQL($content)){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]::cargaMySQL()";
			error_log($sTypeError . " ->\t" . $content . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
	}
	function ftp($archivo_destino, $archivo_fuente, $servidor_ftp=C_SERVIDOR_FTP, $nombre_usuario_ftp=C_NOMBRE_USUARIO_FTP, $contrasenya_ftp=C_CONTRASENYA_FTP)
	{
		// establecer una conexion basica
		$id_con = ftp_connect($servidor_ftp); 
		// inicio de sesion con nombre de usuario y contrasenya
		$resultado_login = ftp_login($id_con, $nombre_usuario_ftp, $contrasenya_ftp); 
		// chequear la conexion
		if ((!$id_con) || (!$resultado_login)) { 
			echo "¡La conexión FTP ha fallado!\n";
			echo "Se ha intentado la conexion con $servidor_ftp para el " . "usuario $nombre_usuario_ftp\n"; 
			exit; 
		} else {
			echo "Conectado con $servidor_ftp, para el usuario $nombre_usuario_ftp\n";
		}
		if (!ftp_chdir($id_con,C_DIR_DESTINO_FTP)) { echo "No se puede entrar en el directorio de FTP.\n"; die(); }
		// cargar el archivo
		$carga = ftp_put($id_con, $archivo_destino, $archivo_fuente, FTP_BINARY); 
		// chequear el status de la carga
		if (!$carga) { 
			echo "¡La carga FTP ha fallado!\n";
		} else {
			echo "Se ha cargado $archivo_fuente a $servidor_ftp como $archivo_destino\n";
		}
		// cierra la secuencia FTP
		ftp_close($id_con); 
	}
function fetchURL( $url )
{
	$url_parsed = parse_url($url);
	$host = $url_parsed["host"];
	$port = $url_parsed["port"];
	if ($port==0)
		$port = 80;
	$path = $url_parsed["path"];

	//if url is http://example.com without final "/"
	//I was getting a 400 error
	if (empty($path))
		$path="/";

	//redirection if url is in wrong format
	if (empty($host)):
		$host="www.negociainternet.com";
		$path="/index.html";
	endif;

	if ($url_parsed["query"] != "")
		$path .= "?".$url_parsed["query"];
	$out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
	$fp = fsockopen($host, $port, $errno, $errstr, 30);
	fwrite($fp, $out);
	$body = false;
	while (!feof($fp)) {
		echo fgets($fp, 128);
	}
	fclose($fp);
	return $in;
}
function getCampoValorMySQL($sFileName, $sCampo="", $sValor=""){
	if ($sFileName!=""){
		$aRetorno["sTablaMySQL"] = getTabla($sFileName);
		if ($sCampo!=""){
			$aRetorno["sCampoMySQL"] = getCampo($sFileName, $sCampo); 
		}
		$aRetorno["sValorMySQL"] = getValor($sFileName, $sCampo, $sValor); 
	}
	return $aRetorno;
}
function getTabla($sFileName){
	global $aTablas;
    foreach($aTablas as $k => $v) {
		if (strval($k) == $sFileName){
			return $v;
		}
	}
	return "";	
}

function getCampo($sFileName, $sCampo){
	global $aTablas;
	$i=0;
    foreach($aTablas as $k => $v) {
    	if (strval($k) == $sFileName){
//    		echo "<br />" . $k . " :-:" . $sFileName;
			//Miro el campo
			$aCampos = $aTablas[$k+($i/2)];	//array de la definicion de campos.
//			echo "<br />$i";
//			echo "<br />";
//			print_r($aCampos);
//			echo "<br />";
//			echo "<br />";
//			echo "--------------------------";
//			exit;
			foreach($aCampos as $a => $b) {
				if (strval($a) == $sCampo){
//					echo "<br />" . $a . " :-:" . $sCampo;
//					echo "<br />" . $a . " :-:" . $sCampo;exit;
					return $aCampos[$a];
				}
			}
		}
		$i++;
	}
	return "";	
}

function getValor($sFileName, $sCampo, $sValor){
	global $aTablas;
	global $connMssql;
	global $conn;
	
	$sRetorno = $sValor;
	
	//Casuisticas para valores por tabla campo
	switch ($sFileName)
	{
		case "empresas":
			switch ($sCampo)
			{
				case "pais":
					// tratar el pais para devolver codIso
					$aCorelacionPais = array ( 
						"esp" => "714",
						"arg" => "032",
						"chi" => "152",
						"col" => "170",
						"mex" => "484",
						"dom" => "214",
						"nca" => "558",
						"gua" => "320"
					);
				    foreach($aCorelacionPais as $k => $v) {
				    	if ($k == $sValor){
				    		return $v;
						}
					}
					break;
				case "pass":
					// tratar la password
						return md5($sValor);
					break;
				case "propietario":
					// Si es Psicologos (id=3788) el Padre es 0 indentacion=0
					// Si el valor es "psicologos" el idPadre=3788 indentacion=1
					// Si el valor es "980026"(CONFIDENTIA-PE) el idPadre es 3966
					// Si el valor es "RRIOCALVO" el idPadre es 3976
					// Si el valor es "320011" el idPadre es 3302
					switch ($sValor)
					{
						case "psicologos":
						case "SHLBARCELONA":
						case "0":
						case "":
							return constant("EMPRESA_PE");
							break;
						default:
							$sql="SELECT * FROM " . $sFileName;
							$sql.=" WHERE usuario=" . $conn->qstr($sValor, 0);
							$result = $connMssql->Execute($sql);
							$arr = $result->GetArray();
							for ($j=0, $max=sizeof($arr); $j < $max; $j++){
								$aProp = $arr[$j];
							    foreach($aProp as $k => $v) {
							    	if ($k == "id"){
							    		return $v;
							    	}
								}
							}
							break;
						} // end switch
					break;
				case "logo":
					if (!empty($sValor)){
						//Hay que hacer una subida del fichero de imágen
						$sOrigenFile = 'http://www.test-station.es/image/logos/' . $sValor;
						$sDestinoFile = 'imgEmpresas/' . $sValor;
						if (!@copy($sOrigenFile, constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDestinoFile)){
							@file_put_contents(constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDestinoFile, file_get_contents($sOrigenFile));
						}
						return $sDestinoFile;
					}
					break;
				default:
					break;
			} // end switch
			break;
		case "administradores":
			switch ($sCampo)
			{
				case "contraseña":
					// tratar la password
					return md5($sValor);
					break;
				case "nivel":
					if ($sValor == "1"){	//Para expertos el 1 es el Administrador que para nosotros es el 0
						return "0";
					}
					break;
				default:
					break;
			} // end switch
			break;
		case "test":
			switch ($sCampo)
			{
				case "orden":
					return "Orden TS: " . $sValor . " ";
					break;
				case "letra":
					return getIdPruebaPorLetra($sValor);
					break;
				default:
					break;
			} // end switch
			break;
		case "otraTabla":
			switch ($sCampo)
			{
				case "campo1":
					break;
				case "campo2":
					break;
				default:
					break;
			} // end switch
			break;
		default:
			break;
	} // end switch
	return $sRetorno;
}
function casuisticasEspeciales($sTablaMySQL){
	global $aTablas;
	global $connMssql;
	global $conn;
//	echo "<br />" . $sTablaMySQL;
	//Casuisticas ESPECIALES para las tablas
	switch ($sTablaMySQL)
	{
		case "empresas":
			//1º Todos la indentacion=1 menos Pisicologos
			$sql= "UPDATE " . $sTablaMySQL . " SET indentacion=1, fecMod=now() WHERE idEmpresa !=" . constant("EMPRESA_PE") . ";\n";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			//2º Psicologos lo ponemos como distribuidor=1, idPadre=0, orden=1, indentacion=0
			$sql= "UPDATE " . $sTablaMySQL . " SET distribuidor=1, idPadre=0, orden=1, indentacion=0, fecMod=now() WHERE idEmpresa=" . constant("EMPRESA_PE") . ";\n";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			//3º Al resto le ponemos orden=2
			$sql= "UPDATE " . $sTablaMySQL . " SET orden=2 WHERE idEmpresa !=" . constant("EMPRESA_PE") . ";\n";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}

			require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
			require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
			
			$cEntidadVar	= new Empresas();  // Entidad
			
			$cEntidadDB	= new EmpresasDB($conn);  // Entidad DB
			$cEntidad	= new Empresas();  // Entidad
			
			$cDentroDe		= new Empresas();  // Entidad
			$cDespuesDe	= new Empresas();  // Entidad
			
			$sql = "SELECT * FROM empresas WHERE distribuidor=1 AND orden=2";
			$listaDistribuidor = $conn->Execute($sql);
			if( !ini_get('safe_mode') ){
            	set_time_limit(0);
        	}
			$cEntidadDB	= new EmpresasDB($conn);  // Entidad DB
			$iPos= 0;
			while (!$listaDistribuidor->EOF)
			{
				$cEntidad	= new Empresas();  // Entidad
				$cDentroDe	= new Empresas();  // Entidad
				//Recuperamos todos los hijos de la entidad antes de ser modificada.
				$sHijosPosActual = $cEntidadDB->getHijo($listaDistribuidor->fields['idEmpresa']);
				if (empty($sHijosPosActual)){
					$iHijosPosActual = 0;
				}else{
					$aHijosPosActual = explode(',',substr($sHijosPosActual, 0, strlen($sHijosPosActual)-1));
					$iHijosPosActual = sizeof($aHijosPosActual);
				}
				
				$cDentroDe->setIdEmpresa($listaDistribuidor->fields['idEmpresa']);
				$cDentroDe = $cEntidadDB->readEntidad($cDentroDe);

				$sql = "UPDATE empresas SET ";
				$sql .= "orden=" . $conn->qstr($cDentroDe->getOrden()+$iPos+1, 0) . " ";
				$sql .="WHERE ";
				$sql .="idEmpresa=" . $listaDistribuidor->fields['idEmpresa'] . " ";
				$conn->Execute($sql);
				
				if ($iHijosPosActual > 0)
				{
					// si movemos el padre movemos sus hijos
					for ($i = 0; $i < $iHijosPosActual; $i++)
					{
						if ($i == 0){
							$iPos++;
						}
						$sql = "UPDATE empresas SET ";
						$sql .= "indentacion=" . $conn->qstr($cDentroDe->getIndentacion()+1, 0) . ", ";
						$sql .= "orden=" . $conn->qstr($cDentroDe->getOrden()+$iPos+$i+1, 0) . " ";
						$sql .="WHERE ";
						$sql .="idEmpresa=" . $aHijosPosActual[$i] . " ";
						
						$conn->Execute($sql);
					}
					$iPos = ($iPos+$iHijosPosActual-1);
				}
				$iPos++;
				$listaDistribuidor->MoveNext();
			}
			$iPos = ($iPos+$iHijosPosActual+1);

			$sql = "SELECT * FROM empresas WHERE orden=2";
			$listaDistribuidor = $conn->Execute($sql);
			while (!$listaDistribuidor->EOF)
			{
				$sql = "UPDATE empresas SET ";
				$sql .= "orden=" . $conn->qstr($iPos, 0) . " ";
				$sql .="WHERE ";
				$sql .="idEmpresa=" . $listaDistribuidor->fields['idEmpresa'] . " ";
				$conn->Execute($sql);
				$iPos++;
				$listaDistribuidor->MoveNext();
			}
			$cEntidadVar->setOrden(0);
			$cEntidadDB->getListaRenumera($cEntidadVar);
			
			
			/////////// ****************************************************** //////////////
			/////////// AHORA TRATAMOS LA PARTE DE SEGURIDAD DE EMPRESA //////////////
			/////////// ****************************************************** //////////////
			
			//5º Damos de Alta los perfiles iniciales para empresa
			$sql= "DELETE FROM emp_perfiles ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			//6º Introducimos los perfiles, Iniciales para *EMPRESA*
			$sql=" INSERT INTO emp_perfiles ";
			$sql .=" (idPerfil, descripcion, fecAlta, fecMod) ";
			$sql .=" VALUES ";
			$sql .=" (0, 'Distribuidor', now(), now()), ";
			$sql .=" (1, 'Empresa', now(), now()); ";
			
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			
			//7º Asigno para cada EMPRESA el perfil asignado
			//Recogemos las empresas
			$sql= "SELECT * FROM " . $sTablaMySQL . " ";
			$listaEmpresas = $conn->Execute($sql);
			$sUsuarioTipo = "";
			while (!$listaEmpresas->EOF)
			{
				//Si viene marcado como distribuidor el perfil es "0" -> Distribuidor 
				$sUsuarioTipo = (!empty($listaEmpresas->fields['distribuidor'])) ? "0" : "1";
				
				$sql = "DELETE FROM empresas_perfiles ";
				$sql .="WHERE ";
				$sql .="idEmpresa=" . $listaEmpresas->fields['idEmpresa'] . " ";
				$sql .=" AND ";
				$sql .="idPerfil=" . $sUsuarioTipo . " ";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql=" INSERT INTO empresas_perfiles";
				$sql .=" (idEmpresa, idPerfil, fecAlta, fecMod) ";
				$sql .=" VALUES ";
				$sql .=" (" . $listaEmpresas->fields['idEmpresa'] . ", " . $sUsuarioTipo . ", now(), now()); ";
			
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$listaEmpresas->MoveNext();
			}
			//8º Para el perfil de distribuidor doy de alta la funcionalidad de gestión de empresas
			//:: Empresas y procesos
			$sql="INSERT INTO empresas_perfiles_funcionalidades (idPerfil, idFuncionalidad, modificar, borrar, fecAlta, fecMod, usuAlta, usuMod) ";
			$sql .="SELECT 0 AS Distribuidor, 21 AS Padre, 'on' AS modificar, 'on' AS borrar, now() AS FA, now() AS FM, 0 AS UA, 0 AS UM ";
			$sql .="FROM wi_funcionalidades wf ";
			$sql .="WHERE wf.url='Empresas.php'";
			//No controlo el error de posibles filas dublicadas para no tener que borrar
			$conn->Execute($sql);
			$sql="INSERT INTO empresas_perfiles_funcionalidades (idPerfil, idFuncionalidad, modificar, borrar, fecAlta, fecMod, usuAlta, usuMod) ";
			$sql .="SELECT 0 AS Distribuidor, wf.idFuncionalidad, 'on' AS modificar, 'on' AS borrar, now() AS FA, now() AS FM, 0 AS UA, 0 AS UM ";
			$sql .="FROM wi_funcionalidades wf ";
			$sql .="WHERE wf.url='ProcesoProcesos.php'";
			//No controlo el error de posibles filas dublicadas para no tener que borrar
			$conn->Execute($sql);
			$sql="INSERT INTO empresas_perfiles_funcionalidades (idPerfil, idFuncionalidad, modificar, borrar, fecAlta, fecMod, usuAlta, usuMod) ";
			$sql .="SELECT 0 AS Distribuidor, wf.idFuncionalidad, 'on' AS modificar, 'on' AS borrar, now() AS FA, now() AS FM, 0 AS UA, 0 AS UM ";
			$sql .="FROM wi_funcionalidades wf ";
			$sql .="WHERE wf.url='Empresas.php'";
			//No controlo el error de posibles filas dublicadas para no tener que borrar
			$conn->Execute($sql);
			  
			/////////// ****************************************************** //////////////
			/////////// FIN DE LA PARTE DE SEGURIDAD DE EMPRESA 
			/////////// ****************************************************** //////////////
			
			break;
		case "wi_usuarios":
			//1º Todos la fecha de modificación
			$sql= "UPDATE " . $sTablaMySQL . " SET fecAlta=now(), fecMod=now();\n";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			//2º el login al nombre 
			$sql= "UPDATE " . $sTablaMySQL . " SET nombre=login;\n";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			//3º si hay un Admin lo borramos y ponemos el nuestro 
			$sql= "DELETE FROM " . $sTablaMySQL . " ";
			$sql .="WHERE ";
			$sql .="upper(login)=" . $conn->qstr("ADMIN", 0) . " ";
			
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			//4º Insertamos nuestro Admin 
			$sql=" INSERT INTO " . $sTablaMySQL . " ";
			$sql .=" (idUsuario, idUsuarioTipo, login, password, nombre, apellido1, email, fecAlta, fecMod) ";
			$sql .=" VALUES ";
			$sql .=" (0, 0, 'Admin', '" . md5("mordor01") . "', 'Administrador', 'Sistema', 'sistemas@negociainternet.com', now(), now()); ";
			
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			//5º BORRAMOS los perfiles
			$sql= "DELETE FROM wi_perfiles ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			//5º Introducimos los perfiles, ya que están Jard-code
			$sql=" INSERT INTO wi_perfiles ";
			$sql .=" (idPerfil, descripcion, fecAlta, fecMod) ";
			$sql .=" VALUES ";
			$sql .=" (0, 'Administrador', now(), now()), ";
			$sql .=" (2, 'Comercial', now(), now()), ";
			$sql .=" (3, 'Producción', now(), now()), ";
			$sql .=" (4, 'Contabilidad', now(), now()); ";
			
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			//5º Asignamos los Perfiles como tipos de usuario
			$sql = "DELETE FROM wi_usuarios_tipos ";
			$sql .="WHERE ";
			$sql .="idUsuarioTipo IN(2,3,4);";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql=" INSERT INTO wi_usuarios_tipos ";
			$sql .=" (idUsuarioTipo, descripcion, fecAlta, fecMod) ";
			$sql .=" VALUES ";
			$sql .=" (2, 'Comercial', now(), now()), ";
			$sql .=" (3, 'Producción', now(), now()), ";
			$sql .=" (4, 'Contabilidad', now(), now()); ";
			
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			//6º Asigno para cada usuario el perfil asignado
			//Recogemos los uaurios
			$sql= "SELECT * FROM " . $sTablaMySQL . " ";
			$listaUsuarios = $conn->Execute($sql);
			while (!$listaUsuarios->EOF)
			{
				$sql = "DELETE FROM wi_usuarios_perfiles ";
				$sql .="WHERE ";
				$sql .="idUsuario=" . $listaUsuarios->fields['idUsuario'] . " ";
				$sql .=" AND ";
				$sql .="idPerfil=" . $listaUsuarios->fields['idUsuarioTipo'] . " ";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql=" INSERT INTO wi_usuarios_perfiles";
				$sql .=" (idUsuario, idPerfil, fecAlta, fecMod) ";
				$sql .=" VALUES ";
				$sql .=" (" . $listaUsuarios->fields['idUsuario'] . ", " . $listaUsuarios->fields['idUsuarioTipo'] . ", now(), now()); ";
			
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$listaUsuarios->MoveNext();
			}
			break;
		case "pruebas":
			//1º Introducimos los tipos de pruebas
			$sql = "DELETE FROM tipos_prueba ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			
			$sql=" INSERT INTO tipos_prueba ";
			$sql .=" (idTipoPrueba, descripcion, fecAlta, fecMod) ";
			$sql .=" VALUES ";
			$sql .=" (1, '360º', now(), now()), ";
			$sql .=" (2, 'Aptitudes', now(), now()), ";
			$sql .=" (3, 'Competencias', now(), now()), ";
			$sql .=" (4, 'Estilo de Aprendizaje', now(), now()), ";
			$sql .=" (5, 'Inglés', now(), now()), ";
			$sql .=" (6, 'Motivaciones', now(), now()), ";
			$sql .=" (7, 'Personalidad', now(), now()), ";
			$sql .=" (8, 'Varias', now(), now()); ";
			
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			
			//2º modificamos con los datos proporcionados por Marta en correo del 18/01/2011 subjet: Incidencias proyecto.xls
			//360 ********************* 
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='360º', ";
			$sql.= "idTipoPrueba='1', ";
			$sql.= "logoPrueba='imgPruebas/2/es/cec.jpg', ";
			$sql.= "duracion='0', ";
			$sql.= "codigo='" . getLetraPorId(2) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\nEspecífico de Casbega.') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =2;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='360º', ";
			$sql.= "idTipoPrueba='1', ";
			$sql.= "logoPrueba='imgPruebas/3/es/cec.jpg', ";
			$sql.= "duracion='0', ";
			$sql.= "codigo='" . getLetraPorId(3) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\nGenérico base para la customización de cada cliente.') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =3;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='360º', ";
			$sql.= "idTipoPrueba='1', ";
			$sql.= "duracion='0', ";
			$sql.= "codigo='" . getLetraPorId(17) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\nEn desuso.') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =17;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='360º', ";
			$sql.= "idTipoPrueba='1', ";
			$sql.= "duracion='0', ";
			$sql.= "codigo='" . getLetraPorId(27) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\nEspecífico de Danone?.') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =27;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			//APTITUDES *********************
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Aptitudes', ";
			$sql.= "idTipoPrueba='2', ";
			$sql.= "duracion='35', ";
			$sql.= "logoPrueba='imgPruebas/14/es/nips.jpg', ";
			$sql.= "codigo='" . getLetraPorId(14) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\nSupuestamente en desuso.') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =14;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Aptitudes', ";
			$sql.= "idTipoPrueba='2', ";
			$sql.= "logoPrueba='imgPruebas/16/es/nips.jpg', ";
			$sql.= "capturaPantalla='imgPruebas/16/es/NipsEs.JPG', ";
			$sql.= "cabecera='imgPruebas/16/es/cabecera.jpg', ";
			$sql.= "duracion='30', ";
			$sql.= "codigo='" . getLetraPorId(16) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\n') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =16;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Aptitudes', ";
			$sql.= "idTipoPrueba='2', ";
			$sql.= "duracion='20', ";
			$sql.= "logoPrueba='imgPruebas/20/es/tic.jpg', ";
			$sql.= "codigo='" . getLetraPorId(20) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\n') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =20;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Aptitudes', ";
			$sql.= "idTipoPrueba='2', ";
			$sql.= "logoPrueba='imgPruebas/21/es/tac.jpg', ";
			$sql.= "duracion='10', ";
			$sql.= "codigo='" . getLetraPorId(21) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\n') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =21;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Aptitudes', ";
			$sql.= "idTipoPrueba='2', ";
			$sql.= "logoPrueba='imgPruebas/23/es/vips.jpg', ";
			$sql.= "duracion='25', ";
			$sql.= "codigo='" . getLetraPorId(23) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\nSupuestamente en desuso.') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =23;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Aptitudes', ";
			$sql.= "idTipoPrueba='2', ";
			$sql.= "logoPrueba='imgPruebas/26/es/vips.jpg', ";
			$sql.= "duracion='18', ";
			$sql.= "codigo='" . getLetraPorId(26) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\n') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =26;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			//COMPETENCIAS *********************
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Competencias', ";
			$sql.= "idTipoPrueba='3', ";
			$sql.= "duracion='0', ";
			$sql.= "codigo='" . getLetraPorId(19) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\nEspecífico de Mercedes Benz, pero no sé si esta es la ultima versión') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =19;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Competencias', ";
			$sql.= "idTipoPrueba='3', ";
			$sql.= "logoPrueba='imgPruebas/11/es/toc.jpg', ";
			$sql.= "duracion='0', ";
			$sql.= "codigo='" . getLetraPorId(11) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\n') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =11;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}

			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Estilo de Aprendizaje', ";
			$sql.= "idTipoPrueba='4', ";
			$sql.= "logoPrueba='imgPruebas/5/es/etoa.jpg', ";
			$sql.= "duracion='0', ";
			$sql.= "codigo='" . getLetraPorId(5) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\n') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =5;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Inglés', ";
			$sql.= "idTipoPrueba='5', ";
			$sql.= "logoPrueba='imgPruebas/8/es/elt-first.jpg', ";
			$sql.= "duracion='20', ";
			$sql.= "codigo='" . getLetraPorId(8) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\n') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =8;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Motivaciones', ";
			$sql.= "idTipoPrueba='6', ";
			$sql.= "logoPrueba='imgPruebas/13/es/cml.jpg', ";
			$sql.= "duracion='0', ";
			$sql.= "codigo='" . getLetraPorId(13) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\n') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =13;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Personalidad', ";
			$sql.= "idTipoPrueba='7', ";
			$sql.= "duracion='0' ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba IN(4,10,12,24);\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "codigo='" . getLetraPorId(4) . "' ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba=4;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "codigo='" . getLetraPorId(10) . "' ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba=10;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "codigo='" . getLetraPorId(12) . "' ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba=12;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "codigo='" . getLetraPorId(24) . "', ";
			$sql.= "logoPrueba='imgPruebas/24/es/prisma.jpg', ";
			$sql.= "preguntasPorPagina=3 ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba=24;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Personalidad', ";
			$sql.= "idTipoPrueba='7', ";
			$sql.= "logoPrueba='imgPruebas/7/es/cpl.jpg', ";
			$sql.= "duracion='0', ";
			$sql.= "codigo='" . getLetraPorId(7) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\n???') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =7;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
				$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Personalidad', ";
			$sql.= "idTipoPrueba='7', ";
			$sql.= "logoPrueba='imgPruebas/18/es/cpl.jpg', ";
			$sql.= "duracion='0', ";
			$sql.= "codigo='" . getLetraPorId(18) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\nEn desuso.') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =18;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}

			$sql= "UPDATE " . $sTablaMySQL . " SET ";
			$sql.= "descripcion='Varias', ";
			$sql.= "idTipoPrueba='8', ";
			$sql.= "duracion='99', ";
			$sql.= "codigo='" . getLetraPorId(25) . "', ";
			$sql.= "observaciones=CONCAT(observaciones,'\nEspecífico de Mercedes Benz, pero no sé si esta es la ultima versión.') ";
			$sql.= " WHERE ";
			$sql.= "IdPrueba =25;\n ";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql = "DELETE FROM " . $sTablaMySQL . " WHERE codIdiomaIso2 !='es'";
			if($conn->Execute($sql) === false){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$sql = "SELECT * FROM " . $sTablaMySQL . " WHERE codIdiomaIso2='es'";
			$listaPruebas = $conn->Execute($sql);
			if( !ini_get('safe_mode') ){
            	set_time_limit(0);
        	}
			//"en", "ca", "pt";
			while (!$listaPruebas->EOF)
			{
				$sql=" INSERT INTO " . $sTablaMySQL . " ";
				$sql .=" (idPrueba, codIdiomaIso2, codigo, nombre, descripcion, idTipoPrueba, observaciones, duracion, logoPrueba, capturaPantalla, cabecera, bajaLog, fecAlta, fecMod, usuAlta, usuMod) ";
				$sql .=" VALUES ";
				$sql .=" (" . $conn->qstr($listaPruebas->fields['idPrueba'], 0) . ",";
				$sql .=" 'en', ";
				$sql .= $conn->qstr($listaPruebas->fields['codigo'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['nombre'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['descripcion'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['idTipoPrueba'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['observaciones'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['duracion'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['logoPrueba'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['capturaPantalla'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['cabecera'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['bajaLog'], 0) . ",";
				$sql .=" now(),";
				$sql .=" now(),";
				$sql .=" 0,";
				$sql .=" 0);";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
							$sql=" INSERT INTO " . $sTablaMySQL . " ";
				$sql .=" (idPrueba, codIdiomaIso2, codigo, nombre, descripcion, idTipoPrueba, observaciones, duracion, logoPrueba, capturaPantalla, cabecera, bajaLog, fecAlta, fecMod, usuAlta, usuMod) ";
				$sql .=" VALUES ";
				$sql .=" (" . $conn->qstr($listaPruebas->fields['idPrueba'], 0) . ",";
				$sql .=" 'ca', ";
				$sql .= $conn->qstr($listaPruebas->fields['codigo'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['nombre'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['descripcion'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['idTipoPrueba'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['observaciones'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['duracion'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['logoPrueba'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['capturaPantalla'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['cabecera'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['bajaLog'], 0) . ",";
				$sql .=" now(),";
				$sql .=" now(),";
				$sql .=" 0,";
				$sql .=" 0);";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql=" INSERT INTO " . $sTablaMySQL . " ";
				$sql .=" (idPrueba, codIdiomaIso2, codigo, nombre, descripcion, idTipoPrueba, observaciones, duracion, logoPrueba, capturaPantalla, cabecera, bajaLog, fecAlta, fecMod, usuAlta, usuMod) ";
				$sql .=" VALUES ";
				$sql .=" (" . $conn->qstr($listaPruebas->fields['idPrueba'], 0) . ",";
				$sql .=" 'pt', ";
				$sql .= $conn->qstr($listaPruebas->fields['codigo'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['nombre'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['descripcion'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['idTipoPrueba'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['observaciones'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['duracion'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['logoPrueba'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['capturaPantalla'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['cabecera'], 0) . ",";
				$sql .= $conn->qstr($listaPruebas->fields['bajaLog'], 0) . ",";
				$sql .=" now(),";
				$sql .=" now(),";
				$sql .=" 0,";
				$sql .=" 0);";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$listaPruebas->MoveNext();
			}
			break;
		case "otraTabla":
			break;
		default:
			break;
	} // end switch
//	return $sRetorno;
}
function vaciarTabla($sTablaMySQL){
	
	global $conn;
	
	$retorno=true;
	$sql= "TRUNCATE TABLE " . $sTablaMySQL . ";\n";
	if($conn->Execute($sql) === false){
		$retorno=false;
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
	}
	return $retorno;
}
function cargaMySQL($sql){
	
	global $conn;
	
	$retorno=true;
	if($conn->Execute($sql) === false){
		$retorno=false;
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
	}
	return $retorno;
}
function cargaItemsPrueba($sPueba, $sLetra, $sIdiomaTS){
	
	global $connMssql;
	global $conn;
	$aMejorPeor = array ("es" => array (
								"Mejor" => "Mejor",
								"Peor" => "Peor"
								),
						"ca" => array (
								"Mejor" => "Millor",
								"Peor" => "Pitjor"
								),
						"en" => array (
								"Mejor" => "Most",
								"Peor" => "Least"
								),
						"de" => array (
								"Mejor" => "Am meisten",
								"Peor" => "Am wenigsten"
								),
						"fr" => array (
								"Mejor" => "Plus proche",
								"Peor" => "Moins proche"
								),
						"it" => array (
								"Mejor" => "Most",
								"Peor" => "Least"
								),
						"pt" => array (
								"Mejor" => "Mais",
								"Peor" => "Menos"
								)
	);
	
	$sCodISO2 = "es";
	$tablaITEMS = $sPueba . "_" . $sIdiomaTS;	//A la tabla de opciones
	$tablaSOLUCIONES = $sPueba . "_soluciones";
	$tablaENUNCIADOS = $sPueba . "_text_" . $sIdiomaTS;
	
	$id = getIdPruebaPorLetra($sLetra);
	
	switch ($sIdiomaTS){
		case "cat":
			$sCodISO2="ca";
			break;
		default:
			$sCodISO2=$sIdiomaTS;
			break;
	} // end switch
	$sMejor="";
	$sPeor="";

    foreach($aMejorPeor as $k => $v) {
    	if (strval($k) == $sCodISO2){
			$aCampos = $aMejorPeor[$k];	//array de la definicion de campos.
			foreach($aCampos as $a => $b) {
				if (strval($a) == "Mejor")	$sMejor=$b;
				if (strval($a) == "Peor")	$sPeor=$b;
			}
			if (!empty($sMejor) && !empty($sPeor))	break;
		}
		if (!empty($sMejor) && !empty($sPeor))	break;
	}
	
	switch ($sPueba){
		case "nips":
			$sql= "SELECT * FROM  " . $tablaENUNCIADOS . " ORDER BY id;\n";
			$result = $connMssql->Execute($sql);
			$arr = $result->GetArray();
			$sqlI= "INSERT INTO items ";
			$sqlC="idItem, enunciado, descripcion, path1, path2, path3, path4";
			$sqlC.= ", idPrueba, codIdiomaIso2, orden, correcto";
			$sqlV=" VALUES ";
			$sqlVal="";
			$sqlValTotal="";
			$idLinea = "";
			$bHayRegistros=false;
			for ($j=0, $max=sizeof($arr); $j < $max; $j++){
				$bHayRegistros=true;
				$aProp = $arr[$j];
				$sqlVal="";
			    foreach($aProp as $k => $v) {
//			    	echo "<br />$k -> $v"; 
			    	if(empty($sqlVal)){
			    		$idLinea = $v;
			    	}
			    	if ($k == "grafico" ||
			    		$k == "grafico1" || 
			    		$k == "grafico2" ||
			    		$k == "grafico3" ||
			    		$k == "grafico4" ){
			    			if (empty($v)){
			    				$sqlVal.=$conn->qstr($v, 0) . ",";
			    			}else{
			    				$sOrigenFile = 'http://www.test-station.es/' . $sPueba . '/image/' . $sIdiomaTS . '/img' . $v . ".jpg";
			    				$sDir = "imgItems/" . $id . "/" . $sCodISO2 . "/";
								$sDestinoFile = $sDir . "img" . $v . ".jpg";
								chk_dir(constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDir);
								if (!@copy($sOrigenFile, constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDestinoFile)){
									@file_put_contents(constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDestinoFile, file_get_contents($sOrigenFile));
								}
			    				$sqlVal.=$conn->qstr($sDestinoFile, 0) . ",";
			    			}
			    		}else{
			    			$sqlVal.=$conn->qstr($v, 0) . ",";
			    		}
				}
				$sql= "SELECT * FROM  " . $tablaSOLUCIONES . " WHERE item=" . $idLinea . ";\n";
				$result = $connMssql->Execute($sql);
				$arr1 = $result->GetArray();
				$sCorrecta="";
				for ($i=0, $max1=sizeof($arr1); $i < $max1; $i++){
					$aProp1 = $arr1[$i];
				    foreach($aProp1 as $k => $v) {
				    	if($k == "correcta"){
				    		$sCorrecta = $conn->qstr($v, 0);
				    		break;
				    	}
					}
					if (!empty($sCorrecta)){
						break;
					}
				}
				$sqlVal.= $id . ", '" . $sCodISO2 . "', " . $idLinea . ", " . $sCorrecta;
				$sqlValTotal.="\n" . "(" . $sqlVal . "),";
			}
			if ($bHayRegistros){
				//BORRAMOS TODOS LOS ITEMS DE ESA PRUEBA
				$sql = "DELETE FROM items where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= "SELECT * FROM  " . $tablaITEMS . " ORDER BY pregunta;\n";
				$result = $connMssql->Execute($sql);
				$arr = $result->GetArray();
				$sqlI= "INSERT INTO opciones ";
				$sqlC="idOpcion, idPrueba, codIdiomaIso2, idItem, codigo, descripcion";
	//			$sqlC.= " ";
				$sqlV=" VALUES ";
				$sqlVal="";
				$sqlValTotal="";
				$idLinea = "";
				$iColumnas=0;
	
				for ($j=0, $max=sizeof($arr); $j < $max; $j++){
					$aProp = $arr[$j];
	//				echo sizeof($aProp);exit;
	//				$sqlVal="";
				    foreach($aProp as $k => $v) {
				    	if ($k != "pregunta"){
				    		$sqlVal.="\n(" . $iColumnas . ", " . $id . ", " . $conn->qstr($sCodISO2, 0) . ", " . ($j+1) . ", " . $conn->qstr($k, 0) . ", " . $conn->qstr($v, 0) . "),";
				    		$iColumnas++;
				    	}else{
				    		$iColumnas=1;
				    	}
					}
				}
				//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA
				$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlVal, 0, -1) . ";";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA 
				//QUE LA DESCRIPCION ESTÁ VACIO
				$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "' AND descripcion='';";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
			}
			break;
		case "nips1":
			//Parecido a nips, pero no tenemos la plantilla de corrección.
			$sql= "SELECT * FROM  " . $tablaENUNCIADOS . " ORDER BY id;\n";
			echo "";
			$result = $connMssql->Execute($sql);
			$arr = $result->GetArray();
			$sqlI= "INSERT INTO items ";
			$sqlC="idItem, enunciado, path1";
			$sqlC.= ", idPrueba, codIdiomaIso2, orden, correcto";
			$sqlV=" VALUES ";
			$sqlVal="";
			$sqlValTotal="";
			$idLinea = "";
			$bHayRegistros=false;
			for ($j=0, $max=sizeof($arr); $j < $max; $j++){
				$bHayRegistros=true;
				$aProp = $arr[$j];
				$sqlVal="";
			    foreach($aProp as $k => $v) {
			    	if(empty($sqlVal)){
			    		$idLinea = $v;
			    	}
			    	if ($k == "grafico" ||
			    		$k == "grafico1" || 
			    		$k == "grafico2" ||
			    		$k == "grafico3" ||
			    		$k == "grafico4" ){
			    			if (empty($v)){
			    				$sqlVal.=$conn->qstr($v, 0) . ",";
			    			}else{
			    				$sOrigenFile = 'http://www.test-station.es/' . $sPueba . '/image/' . $sIdiomaTS . '/img' . $v . ".jpg";
			    				$sDir = "imgItems/" . $id . "/" . $sCodISO2 . "/";
								$sDestinoFile = $sDir . "img" . $v . ".jpg";
								chk_dir(constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDir);
								if (!@copy($sOrigenFile, constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDestinoFile)){
									@file_put_contents(constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDestinoFile, file_get_contents($sOrigenFile));
								}
			    				$sqlVal.=$conn->qstr($sDestinoFile, 0) . ",";
			    			}
			    		}else{
			    			$sqlVal.=$conn->qstr($v, 0) . ",";
			    		}
				}
				$sCorrecta="''";
				$sqlVal.= $id . ", '" . $sCodISO2 . "', " . $idLinea . ", " . $sCorrecta;
				$sqlValTotal.="\n" . "(" . $sqlVal . "),";
			}
			if ($bHayRegistros){
				//BORRAMOS TODOS LOS ITEMS DE ESA PRUEBA
				$sql = "DELETE FROM items where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= "SELECT * FROM  " . $tablaITEMS . " ORDER BY pregunta;\n";
				$result = $connMssql->Execute($sql);
				$arr = $result->GetArray();
				$sqlI= "INSERT INTO opciones ";
				$sqlC="idOpcion, idPrueba, codIdiomaIso2, idItem, codigo, descripcion";
	//			$sqlC.= " ";
				$sqlV=" VALUES ";
				$sqlVal="";
				$sqlValTotal="";
				$idLinea = "";
				$iColumnas=0;
	
				for ($j=0, $max=sizeof($arr); $j < $max; $j++){
					$aProp = $arr[$j];
	//				echo sizeof($aProp);exit;
	//				$sqlVal="";
				    foreach($aProp as $k => $v) {
				    	if ($k != "pregunta"){
				    		$sqlVal.="\n(" . $iColumnas . ", " . $id . ", " . $conn->qstr($sCodISO2, 0) . ", " . ($j+1) . ", " . $conn->qstr($k, 0) . ", " . $conn->qstr($v, 0) . "),";
				    		$iColumnas++;
				    	}else{
				    		$iColumnas=1;
				    	}
					}
				}
				//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA
				$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlVal, 0, -1) . ";";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA 
				//QUE LA DESCRIPCION ESTÁ VACIO
				$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "' AND descripcion='';";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
			}
			break;
		case "vips":
			$sql= "SELECT * FROM  " . $tablaENUNCIADOS . " ORDER BY id;\n";
			$result = $connMssql->Execute($sql);
			$arr = $result->GetArray();
			$sqlI= "INSERT INTO items ";
			$sqlC="idItem, enunciado";
			$sqlC.= ", idPrueba, codIdiomaIso2, orden, correcto";
			$sqlV=" VALUES ";
			$sqlVal="";
			$sqlValTotal="";
			$idLinea = "";
			$bHayRegistros=false;
			for ($j=0, $max=sizeof($arr); $j < $max; $j++){
				$bHayRegistros=true;
				$aProp = $arr[$j];
				$sqlVal="";
			    foreach($aProp as $k => $v) {
			    	if(empty($sqlVal)){
			    		$idLinea = $v;
			    	}
			    	if ($k == "grafico" ||
			    		$k == "grafico1" || 
			    		$k == "grafico2" ||
			    		$k == "grafico3" ||
			    		$k == "grafico4" ){
			    			if (empty($v)){
			    				$sqlVal.=$conn->qstr($v, 0) . ",";
			    			}else{
			    				$sOrigenFile = 'http://www.test-station.es/' . $sPueba . '/image/' . $sIdiomaTS . '/img' . $v . ".jpg";
			    				$sDir = "imgItems/" . $id . "/" . $sCodISO2 . "/";
								$sDestinoFile = $sDir . "img" . $v . ".jpg";
								chk_dir(constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDir);
								if (!@copy($sOrigenFile, constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDestinoFile)){
									@file_put_contents(constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDestinoFile, file_get_contents($sOrigenFile));
								}
			    				$sqlVal.=$conn->qstr($sDestinoFile, 0) . ",";
			    			}
			    		}else{
			    			$sqlVal.=$conn->qstr($v, 0) . ",";
			    		}
				}
				$sql= "SELECT * FROM  " . $tablaSOLUCIONES . " WHERE item=" . $idLinea . ";\n";
				$result = $connMssql->Execute($sql);
				$arr1 = $result->GetArray();
				$sCorrecta="";
				for ($i=0, $max1=sizeof($arr1); $i < $max1; $i++){
					$aProp1 = $arr1[$i];
				    foreach($aProp1 as $k => $v) {
				    	if($k == "correcta"){
				    		$sCorrecta = $conn->qstr($v, 0);
				    		break;
				    	}
					}
					if (!empty($sCorrecta)){
						break;
					}
				}
				$sqlVal.= $id . ", '" . $sCodISO2 . "', " . $idLinea . ", " . $sCorrecta;
				$sqlValTotal.="\n" . "(" . $sqlVal . "),";
			}
			if ($bHayRegistros){
				//BORRAMOS TODOS LOS ITEMS DE ESA PRUEBA
				$sql = "DELETE FROM items where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
//				echo $sql;exit; 
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= "SELECT * FROM  " . $tablaITEMS . " ORDER BY pagina, id;\n";
//				echo $sql;exit; 
				$result = $connMssql->Execute($sql);
				$arr = $result->GetArray();
				$sqlI= "INSERT INTO opciones ";
				$sqlC="idItem, descripcion, idOpcion, ";
				$sqlC.= "idPrueba, codIdiomaIso2";
				$sqlV=" VALUES ";
				$sqlVal="";
				$sqlValTotal="";
				$idLinea = "";
				$iPagina=0;
				for ($j=0, $max=sizeof($arr); $j < $max; $j++){
					$aProp = $arr[$j];
					$sqlVal="";
				    foreach($aProp as $k => $v) {
				    	if ($k != "id"){
				    		$sqlVal.=$conn->qstr($v, 0) . ",";
				    	}else{
				    		if ($iPagina != $aProp["pagina"]){
				    			$idLinea=1;
				    			$sqlVal.=$conn->qstr($idLinea, 0) . ",";
				    		}else{
				    			$idLinea++;
				    			$sqlVal.=$conn->qstr($idLinea, 0) . ",";
				    		}
				    	}
				    	$iPagina = $aProp["pagina"];
					}
					$sqlVal.= $id . ", " . $conn->qstr($sCodISO2, 0);
					$sqlValTotal.="\n" . "(" . $sqlVal . "),";
				}
				//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA
				$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA 
				//QUE LA DESCRIPCION ESTÁ VACIO
				$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "' AND descripcion='';";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
			}
			break;
		case "vips":
			$sql= "SELECT * FROM  " . $tablaENUNCIADOS . " ORDER BY id;\n";
			$result = $connMssql->Execute($sql);
			$arr = $result->GetArray();
			$sqlI= "INSERT INTO items ";
			$sqlC="idItem, enunciado";
			$sqlC.= ", idPrueba, codIdiomaIso2, orden, correcto";
			$sqlV=" VALUES ";
			$sqlVal="";
			$sqlValTotal="";
			$idLinea = "";
			$bHayRegistros=false;
			for ($j=0, $max=sizeof($arr); $j < $max; $j++){
				$bHayRegistros=true;
				$aProp = $arr[$j];
				$sqlVal="";
			    foreach($aProp as $k => $v) {
			    	if(empty($sqlVal)){
			    		$idLinea = $v;
			    	}
			    	if ($k == "grafico" ||
			    		$k == "grafico1" || 
			    		$k == "grafico2" ||
			    		$k == "grafico3" ||
			    		$k == "grafico4" ){
			    			if (empty($v)){
			    				$sqlVal.=$conn->qstr($v, 0) . ",";
			    			}else{
			    				$sOrigenFile = 'http://www.test-station.es/' . $sPueba . '/image/' . $sIdiomaTS . '/img' . $v . ".jpg";
			    				$sDir = "imgItems/" . $id . "/" . $sCodISO2 . "/";
								$sDestinoFile = $sDir . "img" . $v . ".jpg";
								chk_dir(constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDir);
								if (!@copy($sOrigenFile, constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDestinoFile)){
									@file_put_contents(constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDestinoFile, file_get_contents($sOrigenFile));
								}
			    				$sqlVal.=$conn->qstr($sDestinoFile, 0) . ",";
			    			}
			    		}else{
			    			$sqlVal.=$conn->qstr($v, 0) . ",";
			    		}
				}
				$sql= "SELECT * FROM  " . $tablaSOLUCIONES . " WHERE item=" . $idLinea . ";\n";
				$result = $connMssql->Execute($sql);
				$arr1 = $result->GetArray();
				$sCorrecta="";
				for ($i=0, $max1=sizeof($arr1); $i < $max1; $i++){
					$aProp1 = $arr1[$i];
				    foreach($aProp1 as $k => $v) {
				    	if($k == "correcta"){
				    		$sCorrecta = $conn->qstr($v, 0);
				    		break;
				    	}
					}
					if (!empty($sCorrecta)){
						break;
					}
				}
				$sqlVal.= $id . ", '" . $sCodISO2 . "', " . $idLinea . ", " . $sCorrecta;
				$sqlValTotal.="\n" . "(" . $sqlVal . "),";
			}
			if ($bHayRegistros){
				//BORRAMOS TODOS LOS ITEMS DE ESA PRUEBA
				$sql = "DELETE FROM items where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
//				echo $sql;exit; 
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= "SELECT * FROM  " . $tablaITEMS . " ORDER BY pagina, id;\n";
//				echo $sql;exit; 
				$result = $connMssql->Execute($sql);
				$arr = $result->GetArray();
				$sqlI= "INSERT INTO opciones ";
				$sqlC="idItem, descripcion, idOpcion, ";
				$sqlC.= "idPrueba, codIdiomaIso2";
				$sqlV=" VALUES ";
				$sqlVal="";
				$sqlValTotal="";
				$idLinea = "";
				$iPagina=0;
				for ($j=0, $max=sizeof($arr); $j < $max; $j++){
					$aProp = $arr[$j];
					$sqlVal="";
				    foreach($aProp as $k => $v) {
				    	if ($k != "id"){
				    		$sqlVal.=$conn->qstr($v, 0) . ",";
				    	}else{
				    		if ($iPagina != $aProp["pagina"]){
				    			$idLinea=1;
				    			$sqlVal.=$conn->qstr($idLinea, 0) . ",";
				    		}else{
				    			$idLinea++;
				    			$sqlVal.=$conn->qstr($idLinea, 0) . ",";
				    		}
				    	}
				    	$iPagina = $aProp["pagina"];
					}
					$sqlVal.= $id . ", " . $conn->qstr($sCodISO2, 0);
					$sqlValTotal.="\n" . "(" . $sqlVal . "),";
				}
				//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA
				$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA 
				//QUE LA DESCRIPCION ESTÁ VACIO
				$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "' AND descripcion='';";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
			}
			break;
		case "vips1":
			//Parecido a vips, pero no tenemos la plantilla de corrección
			$sql= "SELECT * FROM  " . $tablaENUNCIADOS . " ORDER BY id;\n";
			$result = $connMssql->Execute($sql);
			$arr = $result->GetArray();
			$sqlI= "INSERT INTO items ";
			$sqlC="idItem, enunciado";
			$sqlC.= ", idPrueba, codIdiomaIso2, orden, correcto";
			$sqlV=" VALUES ";
			$sqlVal="";
			$sqlValTotal="";
			$idLinea = "";
			$bHayRegistros=false;
			for ($j=0, $max=sizeof($arr); $j < $max; $j++){
				$bHayRegistros=true;
				$aProp = $arr[$j];
				$sqlVal="";
			    foreach($aProp as $k => $v) {
			    	if(empty($sqlVal)){
			    		$idLinea = $v;
			    	}
			    	if ($k == "grafico" ||
			    		$k == "grafico1" || 
			    		$k == "grafico2" ||
			    		$k == "grafico3" ||
			    		$k == "grafico4" ){
			    			if (empty($v)){
			    				$sqlVal.=$conn->qstr($v, 0) . ",";
			    			}else{
			    				$sOrigenFile = 'http://www.test-station.es/' . $sPueba . '/image/' . $sIdiomaTS . '/img' . $v . ".jpg";
			    				$sDir = "imgItems/" . $id . "/" . $sCodISO2 . "/";
								$sDestinoFile = $sDir . "img" . $v . ".jpg";
								chk_dir(constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDir);
								if (!@copy($sOrigenFile, constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDestinoFile)){
									@file_put_contents(constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDestinoFile, file_get_contents($sOrigenFile));
								}
			    				$sqlVal.=$conn->qstr($sDestinoFile, 0) . ",";
			    			}
			    		}else{
			    			$sqlVal.=$conn->qstr($v, 0) . ",";
			    		}
				}
				$sCorrecta="''";
				$sqlVal.= $id . ", '" . $sCodISO2 . "', " . $idLinea . ", " . $sCorrecta;
				$sqlValTotal.="\n" . "(" . $sqlVal . "),";
			}
			if ($bHayRegistros){
				//BORRAMOS TODOS LOS ITEMS DE ESA PRUEBA
				$sql = "DELETE FROM items where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
//				echo $sql;exit; 
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= "SELECT * FROM  " . $tablaITEMS . " ORDER BY pagina, id;\n";
//				echo $sql;exit; 
				$result = $connMssql->Execute($sql);
				$arr = $result->GetArray();
				$sqlI= "INSERT INTO opciones ";
				$sqlC="idItem, descripcion, idOpcion, ";
				$sqlC.= "idPrueba, codIdiomaIso2";
				$sqlV=" VALUES ";
				$sqlVal="";
				$sqlValTotal="";
				$idLinea = "";
				$iPagina=0;
				for ($j=0, $max=sizeof($arr); $j < $max; $j++){
					$aProp = $arr[$j];
					$sqlVal="";
				    foreach($aProp as $k => $v) {
				    	if ($k != "id"){
				    		$sqlVal.=$conn->qstr($v, 0) . ",";
				    	}else{
				    		if ($iPagina != $aProp["pagina"]){
				    			$idLinea=1;
				    			$sqlVal.=$conn->qstr($idLinea, 0) . ",";
				    		}else{
				    			$idLinea++;
				    			$sqlVal.=$conn->qstr($idLinea, 0) . ",";
				    		}
				    	}
				    	$iPagina = $aProp["pagina"];
					}
					$sqlVal.= $id . ", " . $conn->qstr($sCodISO2, 0);
					$sqlValTotal.="\n" . "(" . $sqlVal . "),";
				}
				//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA
				$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA 
				//QUE LA DESCRIPCION ESTÁ VACIO
				$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "' AND descripcion='';";
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
			}
			break;
		case "prisma":
		case "cpl":
		case "cpl32":	
			//Esta prueba tiene 2 posibles respuestas (mejor,peor)
			//NO hay plantilla de corrección
			//No hay texto de la pregunta
			$sql= "SELECT * FROM  " . $tablaITEMS . " ORDER BY pagina, id;\n";
//			echo "<br />1::" . $sql; 
			$result = $connMssql->Execute($sql);
			$arr = $result->GetArray();
			$sqlI= "INSERT INTO items ";
			$sqlC="idItem";
			$sqlC.= ", enunciado, idPrueba, codIdiomaIso2, orden, correcto";
			$sqlV=" VALUES ";
			$sqlVal="";
			$sqlValTotal="";
			$idLinea = "";
			$bHayRegistros=false;
			for ($j=0, $max=sizeof($arr); $j < $max; $j++){
				$bHayRegistros=true;
				$aProp = $arr[$j];
				$sqlVal="";
			    foreach($aProp as $k => $v) {
			    	if(empty($sqlVal)){
			    		$idLinea = $v;
			    	}
			    	if ($k != "pagina"){
		    			$sqlVal.=$conn->qstr($v, 0) . ",";
			    	}
				}
				$sCorrecta="''";
				$sEnunciado="''";
				$sqlVal.= $id . ", '" . $sCodISO2 . "', " . $idLinea . ", " . $sCorrecta;
				$sqlValTotal.="\n" . "(" . $sqlVal . "),";
			}
			if ($bHayRegistros){
				//BORRAMOS TODOS LOS ITEMS DE ESA PRUEBA
				$sql = "DELETE FROM items where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
//				echo "<br />2::" . $sql;
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
//				echo "<br />3::" . $sql;
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
/*				
				$sql= "SELECT * FROM  " . $tablaITEMS . " ORDER BY pagina, id;\n";
				echo "<br />4::" . $sql;
				$result = $connMssql->Execute($sql);
				$arr = $result->GetArray();
				$sqlI= "INSERT INTO opciones ";
				$sqlC="idItem, descripcion, idOpcion, ";
				$sqlC.= "idPrueba, codIdiomaIso2";
				$sqlV=" VALUES ";
				$sqlVal="";
				$sqlValTotal="";
				$idLinea = "";
				$iPagina=0;
				for ($j=0, $max=sizeof($arr); $j < $max; $j++){
					$aProp = $arr[$j];
					$sqlVal="";
				    foreach($aProp as $k => $v) {
				    	if ($k != "id"){
				    		$sqlVal.=$conn->qstr($v, 0) . ",";
				    	}else{
				    		if ($iPagina != $aProp["pagina"]){
				    			$idLinea=1;
				    			$sqlVal.=$conn->qstr($idLinea, 0) . ",";
				    		}else{
				    			$idLinea++;
				    			$sqlVal.=$conn->qstr($idLinea, 0) . ",";
				    		}
				    	}
				    	$iPagina = $aProp["pagina"];
					}
					$sqlVal.= $id . ", " . $conn->qstr($sCodISO2, 0);
					$sqlValTotal.="\n" . "(" . $sqlVal . "),";
				}
				
				//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA
				$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
				echo "<br />5::" . $sql;
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
				echo "<br />6::" . $sql;
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
*/
				//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA 
				$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "';";
//				echo "<br />7::" . $sql;
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
/*
				//BORRAMOS TODAS LAS OPCIONES VALORES DE ESA PRUEBA 
				$sql = "DELETE FROM opciones_valores ";
				$sql .="WHERE idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
				echo "<br />8::" . $sql;
				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
*/
				//SELECCIONAMOS TODOS LOS ITEMS DE ESA PRUEBA
				$sql = "SELECT * FROM items where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
//				echo "<br />9::" . $sql;
				$listaOpciones = $conn->Execute($sql);
				while (!$listaOpciones->EOF)
				{
					//Mejor
					$sql=" INSERT INTO opciones";
					$sql .=" (idPrueba, codIdiomaIso2, idItem, idOpcion, descripcion, fecAlta, fecMod) ";
					$sql .=" VALUES ";
					$sql .=" (" . $listaOpciones->fields['idPrueba'] . ", " . $conn->qstr($listaOpciones->fields['codIdiomaIso2'], 0) . ", " . $listaOpciones->fields['idItem'] . ", 1, '" . $sMejor . "', now(), now()); ";
//					echo "<br />10::" . $sql;
					if($conn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					//Peor
					$sql=" INSERT INTO opciones";
					$sql .=" (idPrueba, codIdiomaIso2, idItem, idOpcion, descripcion, fecAlta, fecMod) ";
					$sql .=" VALUES ";
					$sql .=" (" . $listaOpciones->fields['idPrueba'] . ", " . $conn->qstr($listaOpciones->fields['codIdiomaIso2'], 0) . ", " . $listaOpciones->fields['idItem'] . ", 2, '" . $sPeor . "', now(), now()); ";
//					echo "<br />11::" . $sql;
					if($conn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$listaOpciones->MoveNext();
				}				
			}
			break;
		default:
			$sCodISO2=$sIdiomaTS;
			break;
	} // end switch
}
function getIdPruebaPorLetra($letra){
	$abc=array('', 'a','b','c','d','e','f','g','h','i','j','k','l','m','n','ñ','o','p','q','r','s','t','u','v','w','x','y','z');
	return array_search($letra, $abc);
}
function getLetraPorId($id){
	$abc=array('', 'a','b','c','d','e','f','g','h','i','j','k','l','m','n','ñ','o','p','q','r','s','t','u','v','w','x','y','z');
	return $abc[$id];
}
	/**
	* Chequea si el directorio existe, si no, lo crea con atributos (chmod=777).
	* @param String destpath
	* @return boolean
	*/
	function chk_dir($path, $mode = 0777) //creates directory tree recursively
	{
		$dirs = explode('/', $path);
		$pos = strrpos($path, ".");
		if ($pos === false) { // note: three equal signs
			// not found, means path ends in a dir not file
			$subamount=0;
		}else	$subamount=1;
		
		for ($c=0;$c < count($dirs) - $subamount; $c++) {
			$thispath="";
			for ($cc=0; $cc <= $c; $cc++)
				$thispath .= $dirs[$cc] . '/';
			if (!file_exists($thispath)){
				$oldumask = umask(0);
				mkdir($thispath, $mode);
				umask($oldumask);
			}
		}
		return true;
	}
?> 