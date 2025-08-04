<?php
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
class ProcesoTablas
{
	/**
	* Declaración de las variables de Entidad.
	**/
		var $conn; //Conexión con la BBDD mySQL
		var $connMssql; //Conexión con la BBDD sqlSERVER
		var $msg_Error; //Array con los mensajes de Warning y Errores

		/* Array de definición de tablas
		 * array ( "TablaOrigen"  => "TablaDestino", array (
		 * 			"CampoOrigen0" => "CampoDestino0",
		 *	 		"CampoOrigen1" => "CampoDestino1"
		 * 		)
		 * );
		 */
		 var $aTablas = array ( "empresas"  => "empresas", array (
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
			
		var $aRetorno = array ( 
				"sTablaMySQL" => "",
				"sCampoMySQL" => "",
				"sValorMySQL" => ""
			);
				
	/**
	* Crea un objeto de la clase y almacena en él la 
	* conexión que utilizará con la base de datos
	* @param conn Conexion a traves de la cual
	* realizar las operaciones sobre la base de datos
	**/
	function __construct(&$conn, &$connMssql)
	{
		$this->conn			= $conn;
		$this->connMssql	= $connMssql;
		$this->msg_Error	= array();
	}
	

	function creaSQL($result, $connMssql, $sTablaSQLSERVER)
	{
		$oconn	= $this->conn;
		global $aRetorno;
		
		$aTablaMySQL = array();
		$i=0;
		$arr = $result->GetArray();
		for ($j=0, $max=sizeof($arr); $j < $max; $j++){
			$aProp = $arr[$j];
			$aRetorno = $this->getCampoValorMySQL($sTablaSQLSERVER);
			$sql=" INSERT INTO " . $aRetorno["sTablaMySQL"] . " ";
			$sKeys= "";
			$sValues= "";
		    foreach($aProp as $k => $v) {
		    	$aRetorno = $this->getCampoValorMySQL($sTablaSQLSERVER, $k, $aProp[$k]);
		    	if (!empty($aRetorno["sCampoMySQL"])){
	                 $sKeys.= "," . $aRetorno["sCampoMySQL"];
	                 $sValues.= "," . $oconn->qstr($aRetorno["sValorMySQL"], 0);
		    	}
			}
			$sql.= "(" . substr($sKeys, 1) . ")";
			$sql.= " VALUES ";
			$sql.= "(" . substr($sValues, 1) . ")";
			$this->crea_fichero(constant("DIR_FS_DOCUMENT_ROOT") . "SQLFiles/" . $aRetorno["sTablaMySQL"] . ".sql",str_replace("\r\n","\\n", $sql) . ";\n", $j, $aRetorno["sTablaMySQL"]);			
		}
		$this->casuisticasEspeciales($aRetorno["sTablaMySQL"]);
	}
	
	function crea_fichero($file, $content, $i, $sTabla)
	{
		if ($i > 0 ){
			$handle=fopen($file,"a") or die ("Error creando Fichero" . $file);
		}else{
			if (!$this->vaciarTabla($sTabla)){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]::vaciarTabla()";
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
			$handle=fopen($file,"w") or die ("Error creando Fichero" . $file);
		}
		
		fwrite($handle,$content) or die ("No se puede escribir en el Fichero" . $file);
		fclose($handle);
		if (!$this->cargaMySQL($content)){
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
			$aRetorno["sTablaMySQL"] = $this->getTabla($sFileName);
			if ($sCampo!=""){
				$aRetorno["sCampoMySQL"] = $this->getCampo($sFileName, $sCampo); 
			}
			$aRetorno["sValorMySQL"] = $this->getValor($sFileName, $sCampo, $sValor); 
		}
		return $aRetorno;
	}
	
	function getTabla($sFileName){
		$aTablas = $this->aTablas;
	    foreach($aTablas as $k => $v) {
			if (strval($k) == $sFileName){
				return $v;
			}
		}
		return "";	
	}

	function getCampo($sFileName, $sCampo){
		$aTablas = $this->aTablas;
		$i=0;
	    foreach($aTablas as $k => $v) {
	    	if (strval($k) == $sFileName){
				//Miro el campo
				$aCampos = $aTablas[$k+($i/2)];	//array de la definicion de campos.
				foreach($aCampos as $a => $b) {
					if (strval($a) == $sCampo){
						return $aCampos[$a];
					}
				}
			}
			$i++;
		}
		return "";	
	}

	function getValor($sFileName, $sCampo, $sValor){
		$aTablas = $this->aTablas;
		$oconn	= $this->conn;
		$oconnMssql	= $this->connMssql;
		
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
								$sql.=" WHERE usuario=" . $oconn->qstr($sValor, 0);
								$result = $oconnMssql->Execute($sql);
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
							if (!@copy($sOrigenFile, constant("DIR_FS_DOCUMENT_ROOT") . $sDestinoFile)){
								@file_put_contents(constant("DIR_FS_DOCUMENT_ROOT") . $sDestinoFile, file_get_contents($sOrigenFile));
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
						return $this->getIdPruebaPorLetra($sValor);
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
		$aTablas = $this->aTablas;
		$oconn	= $this->conn;
		$oconnMssql	= $this->connMssql;

		//Casuisticas ESPECIALES para las tablas
		switch ($sTablaMySQL)
		{
			case "empresas":
				//1º Todos la indentacion=1 menos Pisicologos
				$sql= "UPDATE " . $sTablaMySQL . " SET indentacion=1, fecMod=now() WHERE idEmpresa !=" . constant("EMPRESA_PE") . ";\n";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//2º Psicologos lo ponemos como distribuidor=1, idPadre=0, orden=1, indentacion=0
				//Tambien le ponemos el path del logo imgEmpresas/pisicologosEmpresariales.jpg
				$sql= "UPDATE " . $sTablaMySQL . " SET cif='A78301934', pathLogo='imgEmpresas/pisicologosEmpresariales.jpg', distribuidor=1, idPadre=0, orden=1, indentacion=0, fecMod=now() WHERE idEmpresa=" . constant("EMPRESA_PE") . ";\n";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//3º Al resto le ponemos orden=2
				$sql= "UPDATE " . $sTablaMySQL . " SET orden=2 WHERE idEmpresa !=" . constant("EMPRESA_PE") . ";\n";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
	
				require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
				require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
				
				$cEntidadVar	= new Empresas();  // Entidad
				
				$cEntidadDB	= new EmpresasDB($oconn);  // Entidad DB
				$cEntidad	= new Empresas();  // Entidad
				
				$cDentroDe		= new Empresas();  // Entidad
				$cDespuesDe	= new Empresas();  // Entidad
				
				$sql = "SELECT * FROM empresas WHERE distribuidor=1 AND orden=2";
				$listaDistribuidor = $oconn->Execute($sql);
				if( !ini_get('safe_mode') ){
	            	set_time_limit(0);
	        	}
				$cEntidadDB	= new EmpresasDB($oconn);  // Entidad DB
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
					$sql .= "orden=" . $oconn->qstr($cDentroDe->getOrden()+$iPos+1, 0) . " ";
					$sql .="WHERE ";
					$sql .="idEmpresa=" . $listaDistribuidor->fields['idEmpresa'] . " ";
					$oconn->Execute($sql);
					
					if ($iHijosPosActual > 0)
					{
						// si movemos el padre movemos sus hijos
						for ($i = 0; $i < $iHijosPosActual; $i++)
						{
							if ($i == 0){
								$iPos++;
							}
							$sql = "UPDATE empresas SET ";
							$sql .= "indentacion=" . $oconn->qstr($cDentroDe->getIndentacion()+1, 0) . ", ";
							$sql .= "orden=" . $oconn->qstr($cDentroDe->getOrden()+$iPos+$i+1, 0) . " ";
							$sql .="WHERE ";
							$sql .="idEmpresa=" . $aHijosPosActual[$i] . " ";
							
							$oconn->Execute($sql);
						}
						$iPos = ($iPos+$iHijosPosActual-1);
					}
					$iPos++;
					$listaDistribuidor->MoveNext();
				}
				$iPos = ($iPos+$iHijosPosActual+1);
	
				$sql = "SELECT * FROM empresas WHERE orden=2";
				$listaDistribuidor = $oconn->Execute($sql);
				while (!$listaDistribuidor->EOF)
				{
					$sql = "UPDATE empresas SET ";
					$sql .= "orden=" . $oconn->qstr($iPos, 0) . " ";
					$sql .="WHERE ";
					$sql .="idEmpresa=" . $listaDistribuidor->fields['idEmpresa'] . " ";
					$oconn->Execute($sql);
					$iPos++;
					$listaDistribuidor->MoveNext();
				}
				$cEntidadVar->setOrden(0);
				$cEntidadDB->getListaRenumera($cEntidadVar);
				
				
				/////////// ****************************************************** //////////////
				/////////// AHORA TRATAMOS LA PARTE DE SEGURIDAD DE EMPRESA //////////////
				/////////// ****************************************************** //////////////
				
				//5º Damos de Alta los perfiles iniciales para empresa
/*				$sql= "DELETE FROM emp_perfiles ";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//6º Introducimos los perfiles, Iniciales para *EMPRESA*
				$sql=" INSERT INTO emp_perfiles ";
				$sql .=" (idPerfil, descripcion, fecAlta, fecMod) ";
				$sql .=" VALUES ";
				$sql .=" (0, 'Distribuidor', now(), now()),";
				$sql .=" (1, 'Empresa', now(), now());";
				
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
*/				
				//7º Asigno para cada EMPRESA el perfil asignado
				//Recogemos las empresas
				$sql= "SELECT * FROM " . $sTablaMySQL . " ";
				$listaEmpresas = $oconn->Execute($sql);
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
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql=" INSERT INTO empresas_perfiles";
					$sql .=" (idEmpresa, idPerfil, fecAlta, fecMod) ";
					$sql .=" VALUES ";
					$sql .=" (" . $listaEmpresas->fields['idEmpresa'] . ", " . $sUsuarioTipo . ", now(), now());";
				
					if($oconn->Execute($sql) === false){
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
				$oconn->Execute($sql);
				$sql="INSERT INTO empresas_perfiles_funcionalidades (idPerfil, idFuncionalidad, modificar, borrar, fecAlta, fecMod, usuAlta, usuMod) ";
				$sql .="SELECT 0 AS Distribuidor, wf.idFuncionalidad, 'on' AS modificar, 'on' AS borrar, now() AS FA, now() AS FM, 0 AS UA, 0 AS UM ";
				$sql .="FROM wi_funcionalidades wf ";
				$sql .="WHERE wf.url='ProcesoProcesos.php'";
				//No controlo el error de posibles filas dublicadas para no tener que borrar
				$oconn->Execute($sql);
				$sql="INSERT INTO empresas_perfiles_funcionalidades (idPerfil, idFuncionalidad, modificar, borrar, fecAlta, fecMod, usuAlta, usuMod) ";
				$sql .="SELECT 0 AS Distribuidor, wf.idFuncionalidad, 'on' AS modificar, 'on' AS borrar, now() AS FA, now() AS FM, 0 AS UA, 0 AS UM ";
				$sql .="FROM wi_funcionalidades wf ";
				$sql .="WHERE wf.url='Empresas.php'";
				//No controlo el error de posibles filas dublicadas para no tener que borrar
				$oconn->Execute($sql);
				  
				/////////// ****************************************************** //////////////
				/////////// FIN DE LA PARTE DE SEGURIDAD DE EMPRESA 
				/////////// ****************************************************** //////////////
				
				break;
			case "wi_usuarios":
				//1º Todos la fecha de modificación
				$sql= "UPDATE " . $sTablaMySQL . " SET fecAlta=now(), fecMod=now();\n";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//2º el login al nombre 
				$sql= "UPDATE " . $sTablaMySQL . " SET nombre=login;\n";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//3º si hay un Admin lo borramos y ponemos el nuestro 
				$sql= "DELETE FROM " . $sTablaMySQL . " ";
				$sql .="WHERE ";
				$sql .="upper(login)=" . $oconn->qstr("ADMIN", 0) . " ";
				
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//4º Insertamos nuestro Admin 
				$sql=" INSERT INTO " . $sTablaMySQL . " ";
				$sql .=" (idUsuario, idUsuarioTipo, login, password, nombre, apellido1, email, fecAlta, fecMod) ";
				$sql .=" VALUES ";
				$sql .=" (0, 0, 'Admin', '" . md5("mordor01") . "', 'Administrador', 'Sistema', 'sistemas@negociainternet.com', now(), now()),";
				$sql .=" (1, 0, 'jsola', '" . md5("jsola") . "', 'Juan', 'Solá', 'jsola@negociainternet.com', now(), now());";
				
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
/*				
				//5º BORRAMOS los perfiles
				$sql= "DELETE FROM wi_perfiles ";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//5º Introducimos los perfiles, ya que están Jard-code
				$sql=" INSERT INTO wi_perfiles ";
				$sql .=" (idPerfil, descripcion, fecAlta, fecMod) ";
				$sql .=" VALUES ";
				$sql .=" (0, 'Administrador', now(), now()),";
				$sql .=" (2, 'Comercial', now(), now()),";
				$sql .=" (3, 'Producción', now(), now()),";
				$sql .=" (4, 'Contabilidad', now(), now());";
				
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//5º Asignamos los Perfiles como tipos de usuario
				$sql = "DELETE FROM wi_usuarios_tipos ";
				$sql .="WHERE ";
				$sql .="idUsuarioTipo IN(2,3,4);";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql=" INSERT INTO wi_usuarios_tipos ";
				$sql .=" (idUsuarioTipo, descripcion, fecAlta, fecMod) ";
				$sql .=" VALUES ";
				$sql .=" (2, 'Comercial', now(), now()),";
				$sql .=" (3, 'Producción', now(), now()),";
				$sql .=" (4, 'Contabilidad', now(), now());";
				
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//6º Asigno para cada usuario el perfil asignado
				//Recogemos los uaurios
				$sql= "SELECT * FROM " . $sTablaMySQL . " ";
				$listaUsuarios = $oconn->Execute($sql);
				while (!$listaUsuarios->EOF)
				{
					$sql = "DELETE FROM wi_usuarios_perfiles ";
					$sql .="WHERE ";
					$sql .="idUsuario=" . $listaUsuarios->fields['idUsuario'] . " ";
					$sql .=" AND ";
					$sql .="idPerfil=" . $listaUsuarios->fields['idUsuarioTipo'] . " ";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql=" INSERT INTO wi_usuarios_perfiles";
					$sql .=" (idUsuario, idPerfil, fecAlta, fecMod) ";
					$sql .=" VALUES ";
					$sql .=" (" . $listaUsuarios->fields['idUsuario'] . ", " . $listaUsuarios->fields['idUsuarioTipo'] . ", now(), now());";
				
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$listaUsuarios->MoveNext();
				}
*/
				break;
			case "pruebas":
/*
				//1º Introducimos los tipos de pruebas
				$sql = "DELETE FROM tipos_prueba ";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				
				$sql=" INSERT INTO tipos_prueba ";
				$sql .=" (idTipoPrueba, descripcion, fecAlta, fecMod) ";
				$sql .=" VALUES ";
				$sql .=" (1, '360º', now(), now()),";
				$sql .=" (2, 'Aptitudes', now(), now()),";
				$sql .=" (3, 'Competencias', now(), now()),";
				$sql .=" (4, 'Estilo de Aprendizaje', now(), now()),";
				$sql .=" (5, 'Inglés', now(), now()),";
				$sql .=" (6, 'Motivaciones', now(), now()),";
				$sql .=" (7, 'Personalidad', now(), now()),";
				$sql .=" (8, 'Varias', now(), now());";
				
				if($oconn->Execute($sql) === false){
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
				$sql.= "codigo='" . $this->getLetraPorId(2) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\nEspecífico de Casbega.') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=2;\n ";
				if($oconn->Execute($sql) === false){
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
				$sql.= "codigo='" . $this->getLetraPorId(3) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\nGenérico base para la customización de cada cliente.') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=3;\n ";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= "UPDATE " . $sTablaMySQL . " SET ";
				$sql.= "descripcion='360º', ";
				$sql.= "idTipoPrueba='1', ";
				$sql.= "duracion='0', ";
				$sql.= "codigo='" . $this->getLetraPorId(17) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\nEn desuso.') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=17;\n ";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= "UPDATE " . $sTablaMySQL . " SET ";
				$sql.= "descripcion='360º', ";
				$sql.= "idTipoPrueba='1', ";
				$sql.= "duracion='0', ";
				$sql.= "codigo='" . $this->getLetraPorId(27) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\nEspecífico de Danone?.') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=27;\n ";
				if($oconn->Execute($sql) === false){
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
				$sql.= "codigo='" . $this->getLetraPorId(14) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\nSupuestamente en desuso.') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=14;\n ";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= "UPDATE " . $sTablaMySQL . " SET ";
				$sql.= "descripcion='Razonamiento numérico general NIPS', ";
				$sql.= "idTipoPrueba='2', ";
				$sql.= "logoPrueba='imgPruebas/16/es/nips.jpg', ";
				$sql.= "capturaPantalla='imgPruebas/16/es/NipsEs.JPG', ";
//				$sql.= "cabecera='imgPruebas/16/es/cabecera.jpg', ";
				$sql.= "cabecera='', ";
				$sql.= "duracion='30', ";
				$sql.= "codigo='" . $this->getLetraPorId(16) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\n') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=16;\n ";
				if($oconn->Execute($sql) === false){
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
				$sql.= "codigo='" . $this->getLetraPorId(20) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\n') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=20;\n ";
				if($oconn->Execute($sql) === false){
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
				$sql.= "codigo='" . $this->getLetraPorId(21) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\n') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=21;\n ";
				if($oconn->Execute($sql) === false){
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
				$sql.= "codigo='" . $this->getLetraPorId(23) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\nSupuestamente en desuso.') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=23;\n ";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= "UPDATE " . $sTablaMySQL . " SET ";
				$sql.= "descripcion='Razonamiento crítico verbal VIPS', ";
				$sql.= "idTipoPrueba='2', ";
				$sql.= "logoPrueba='imgPruebas/26/es/vips.jpg', ";
				$sql.= "capturaPantalla='imgPruebas/26/es/ViPs.png', ";
				$sql.= "duracion='18', ";
				$sql.= "codigo='" . $this->getLetraPorId(26) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\n') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=26;\n ";
				if($oconn->Execute($sql) === false){
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
				$sql.= "codigo='" . $this->getLetraPorId(19) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\nEspecífico de Mercedes Benz, pero no sé si esta es la ultima versión') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=19;\n ";
				if($oconn->Execute($sql) === false){
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
				$sql.= "codigo='" . $this->getLetraPorId(11) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\n') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=11;\n ";
				if($oconn->Execute($sql) === false){
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
				$sql.= "codigo='" . $this->getLetraPorId(5) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\n') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=5;\n ";
				if($oconn->Execute($sql) === false){
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
				$sql.= "codigo='" . $this->getLetraPorId(8) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\n') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=8;\n ";
				if($oconn->Execute($sql) === false){
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
				$sql.= "codigo='" . $this->getLetraPorId(13) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\n') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=13;\n ";
				if($oconn->Execute($sql) === false){
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
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= "UPDATE " . $sTablaMySQL . " SET ";
				$sql.= "codigo='" . $this->getLetraPorId(4) . "' ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=4;\n ";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= "UPDATE " . $sTablaMySQL . " SET ";
				$sql.= "codigo='" . $this->getLetraPorId(10) . "' ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=10;\n ";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= "UPDATE " . $sTablaMySQL . " SET ";
				$sql.= "codigo='" . $this->getLetraPorId(12) . "' ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=12;\n ";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql= "UPDATE " . $sTablaMySQL . " SET ";
				$sql.= "descripcion='Cuestionario de personalidad laboral PRISM@', ";
				$sql.= "codigo='" . $this->getLetraPorId(24) . "', ";
				$sql.= "logoPrueba='imgPruebas/24/es/prisma.jpg', ";
				$sql.= "capturaPantalla='imgPruebas/24/es/PrIsMa.png', ";
				$sql.= "preguntasPorPagina=3 ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=24;\n ";
				if($oconn->Execute($sql) === false){
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
				$sql.= "codigo='" . $this->getLetraPorId(7) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\n???') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=7;\n ";
				if($oconn->Execute($sql) === false){
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
				$sql.= "codigo='" . $this->getLetraPorId(18) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\nEn desuso.') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=18;\n ";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
	
				$sql= "UPDATE " . $sTablaMySQL . " SET ";
				$sql.= "descripcion='Varias', ";
				$sql.= "idTipoPrueba='8', ";
				$sql.= "duracion='99', ";
				$sql.= "codigo='" . $this->getLetraPorId(25) . "', ";
				$sql.= "observaciones=CONCAT(observaciones,'\nEspecífico de Mercedes Benz, pero no sé si esta es la ultima versión.') ";
				$sql.= " WHERE ";
				$sql.= "IdPrueba=25;\n ";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//Ya hemos actualizado todas las pruebas y dejamos sólo las que están en español
				$sql = "DELETE FROM " . $sTablaMySQL . " WHERE codIdiomaIso2 !='es'";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql = "SELECT * FROM " . $sTablaMySQL . " WHERE codIdiomaIso2='es'";
				$listaPruebas = $oconn->Execute($sql);
				if( !ini_get('safe_mode') ){
	            	set_time_limit(0);
	        	}
	        	//Damos de alta todas ademas del españos en "en", "ca", "pt";
				while (!$listaPruebas->EOF)
				{
					$sql=" INSERT INTO " . $sTablaMySQL . " ";
					$sql .=" (idPrueba, codIdiomaIso2, codigo, nombre, descripcion, idTipoPrueba, observaciones, duracion, logoPrueba, capturaPantalla, cabecera, preguntasPorPagina, bajaLog, fecAlta, fecMod, usuAlta, usuMod) ";
					$sql .=" VALUES ";
					$sql .=" (" . $oconn->qstr($listaPruebas->fields['idPrueba'], 0) . ",";
					$sql .=" 'en', ";
					$sql .= $oconn->qstr($listaPruebas->fields['codigo'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['nombre'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['descripcion'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['idTipoPrueba'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['observaciones'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['duracion'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['logoPrueba'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['capturaPantalla'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['cabecera'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['preguntasPorPagina'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['bajaLog'], 0) . ",";
					$sql .=" now(),";
					$sql .=" now(),";
					$sql .=" 0,";
					$sql .=" 0);";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql=" INSERT INTO " . $sTablaMySQL . " ";
					$sql .=" (idPrueba, codIdiomaIso2, codigo, nombre, descripcion, idTipoPrueba, observaciones, duracion, logoPrueba, capturaPantalla, cabecera, preguntasPorPagina, bajaLog, fecAlta, fecMod, usuAlta, usuMod) ";
					$sql .=" VALUES ";
					$sql .=" (" . $oconn->qstr($listaPruebas->fields['idPrueba'], 0) . ",";
					$sql .=" 'ca', ";
					$sql .= $oconn->qstr($listaPruebas->fields['codigo'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['nombre'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['descripcion'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['idTipoPrueba'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['observaciones'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['duracion'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['logoPrueba'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['capturaPantalla'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['cabecera'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['preguntasPorPagina'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['bajaLog'], 0) . ",";
					$sql .=" now(),";
					$sql .=" now(),";
					$sql .=" 0,";
					$sql .=" 0);";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql=" INSERT INTO " . $sTablaMySQL . " ";
					$sql .=" (idPrueba, codIdiomaIso2, codigo, nombre, descripcion, idTipoPrueba, observaciones, duracion, logoPrueba, capturaPantalla, cabecera, preguntasPorPagina, bajaLog, fecAlta, fecMod, usuAlta, usuMod) ";
					$sql .=" VALUES ";
					$sql .=" (" . $oconn->qstr($listaPruebas->fields['idPrueba'], 0) . ",";
					$sql .=" 'pt', ";
					$sql .= $oconn->qstr($listaPruebas->fields['codigo'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['nombre'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['descripcion'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['idTipoPrueba'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['observaciones'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['duracion'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['logoPrueba'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['capturaPantalla'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['cabecera'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['preguntasPorPagina'], 0) . ",";
					$sql .= $oconn->qstr($listaPruebas->fields['bajaLog'], 0) . ",";
					$sql .=" now(),";
					$sql .=" now(),";
					$sql .=" 0,";
					$sql .=" 0);";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$listaPruebas->MoveNext();
				}
				//BORRAMOS aquellas pruebas que NO esten en algún idioma en la plataforma vieja
				//Ejemplo:
				// CEC			(3)-->	es
				// CECCASBEGA	(2)-->	es
				// CEL16		(12)-->	es
				// CML			(13)-->	es
				// CPL			(7)-->	es
				// CPL16		(4)-->	es, ca
				// CPL32		(18)-->	es, ca, en, pt
				// ELT			(8)-->	  ,   , en,   	//Prueba Sólo Ingles.
				// ETOA			(5)-->	es
				// ICD			(17)-->	es
				// ICDD			(27)-->	es
				// NIPS			(16)-->	es,   ,   , pt
				// NIPS1		(14)-->	es,   , en, pt
				// PDP			(19)-->	es,   ,   ,   
				// PRISMA		(24)-->	es, ca, en, pt
				// SERC			(25)-->	es
				// TAC			(21)-->	es
				// TEC			(10)-->	es
				// TIC			(20)-->	es
				// TOC			(11)-->	es
				// VIPS			(26)-->	es,   ,   , pt
				// VIPS1		(23)-->	es,   , en, pt
				
				//Borramos todas las pruebas en otro idioma y dejamos solo español para estas pruebas
				$sql = "DELETE FROM " . $sTablaMySQL . " WHERE codIdiomaIso2 !='es' ";
				$sql .= "AND idPrueba IN(3,2,12,13,7,5,17,27,19,25,21,10,20,11)";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//Borramos todas las pruebas en otro idioma y dejamos solo ingles
				//ELT First es una prueba en ingles
				$sql = "DELETE FROM " . $sTablaMySQL . " WHERE codIdiomaIso2 !='en' ";
				$sql .= "AND idPrueba= 8";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//Para CPL16 dejamos sólo es, ca
				$sql = "DELETE FROM " . $sTablaMySQL . " WHERE codIdiomaIso2 NOT IN ('es','ca') ";
				$sql .= "AND idPrueba= 4";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//Borramos todas las pruebas en CATALAN para estas pruebas
				$sql = "DELETE FROM " . $sTablaMySQL . " WHERE codIdiomaIso2 ='ca' ";
				$sql .= "AND idPrueba IN(16,14,26,23)";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				//Borramos todas las que están en INGLÉS para estas pruebas VIPS y NIPS
				$sql = "DELETE FROM " . $sTablaMySQL . " WHERE codIdiomaIso2 ='en' ";
				$sql .= "AND idPrueba IN(16,26)";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
*/
				break;
			case "otraTabla":
				break;
			default:
				break;
		} // end switch
	//	return $sRetorno;
	}

	function vaciarTabla($sTablaMySQL){
	
		$oconn	= $this->conn;
		
		$retorno=true;
/*		
		$sql= "TRUNCATE TABLE " . $sTablaMySQL . ";\n";
		if($oconn->Execute($sql) === false){
			$retorno=false;
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
*/		
		return $retorno;
	}

	function cargaMySQL($sql){
		
		$oconn	= $this->conn;
		
		$retorno=true;
		if($oconn->Execute($sql) === false){
			$retorno=false;
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [cargaMySQL][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
		return $retorno;
	}
	
	function cargaItemsPrueba($sPueba, $sLetra, $sIdiomaTS){
		
		$oconn	= $this->conn;
		$oconnMssql	= $this->connMssql;

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
		$aVIPSOpciones = array ("es" => array (
									"Verdadero" => "Verdadero",
									"Falso" => "Falso",
									"Informacion" => "Información Insuficiente"
									),
							"ca" => array (
									"Verdadero" => "Veritable",
									"Falso" => "Fals",
									"Informacion" => "Informació Insuficient"
									),
							"en" => array (
									"Verdadero" => "True",
									"Falso" => "False",
									"Informacion" => "Insufficient Information"
									),
							"de" => array (
									"Verdadero" => "True",
									"Falso" => "False",
									"Informacion" => "Unzureichende Information"
									),
							"fr" => array (
									"Verdadero" => "True",
									"Falso" => "Faux",
									"Informacion" => "Information insuffisante"
									),
							"it" => array (
									"Verdadero" => "True",
									"Falso" => "Falso",
									"Informacion" => "Informazioni insufficienti"
									),
							"pt" => array (
									"Verdadero" => "True",
									"Falso" => "False",
									"Informacion" => "A insuficiência de informações"
									)
		);
		
		$sCodISO2 = "es";
		$tablaITEMS = $sPueba . "_" . $sIdiomaTS;	//A la tabla de opciones
		$tablaSOLUCIONES = $sPueba . "_soluciones";
		$tablaENUNCIADOS = $sPueba . "_text_" . $sIdiomaTS;
		
		$id = $this->getIdPruebaPorLetra($sLetra);
		
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
		
		$sVerdadero="";
		$sFalso="";
		$sInformacion="";
		foreach($aVIPSOpciones as $k => $v) {
	    	if (strval($k) == $sCodISO2){
				$aCampos = $aVIPSOpciones[$k];	//array de la definicion de campos.
				foreach($aCampos as $a => $b) {
					if (strval($a) == "Verdadero")	$sVerdadero=$b;
					if (strval($a) == "Falso")	$sFalso=$b;
					if (strval($a) == "Informacion")	$sInformacion=$b;
				}
				if (!empty($sVerdadero) && !empty($sFalso) && !empty($sInformacion))	break;
			}
			if (!empty($sVerdadero) && !empty($sFalso) && !empty($sInformacion))	break;
		}
		
		switch ($sPueba){
			case "nips":
/*
				$sql= "SELECT * FROM  " . $tablaENUNCIADOS . " ORDER BY id;\n";
				$result = $oconnMssql->Execute($sql);
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
				    				$sqlVal.=$oconn->qstr($v, 0) . ",";
				    			}else{
				    				$sOrigenFile = 'http://www.test-station.es/' . $sPueba . '/image/' . $sIdiomaTS . '/img' . $v . ".jpg";
				    				$sDir = "imgItems/" . $id . "/" . $sCodISO2 . "/";
									$sDestinoFile = $sDir . "img" . $v . ".jpg";
									$this->chk_dir(constant("DIR_FS_DOCUMENT_ROOT") . $sDir);
									if (!@copy($sOrigenFile, constant("DIR_FS_DOCUMENT_ROOT") . $sDestinoFile)){
										@file_put_contents(constant("DIR_FS_DOCUMENT_ROOT") . $sDestinoFile, file_get_contents($sOrigenFile));
									}
				    				$sqlVal.=$oconn->qstr($sDestinoFile, 0) . ",";
				    			}
				    		}else{
				    			$sqlVal.=$oconn->qstr($v, 0) . ",";
				    		}
					}
					$sql= "SELECT * FROM  " . $tablaSOLUCIONES . " WHERE item=" . $idLinea . ";\n";
					$result = $oconnMssql->Execute($sql);
					$arr1 = $result->GetArray();
					$sCorrecta="";
					for ($i=0, $max1=sizeof($arr1); $i < $max1; $i++){
						$aProp1 = $arr1[$i];
					    foreach($aProp1 as $k => $v) {
					    	if($k == "correcta"){
					    		$sCorrecta = $oconn->qstr($v, 0);
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
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql= "SELECT * FROM  " . $tablaITEMS . " ORDER BY pregunta;\n";
					$result = $oconnMssql->Execute($sql);
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
					    		$sqlVal.="\n(" . $iColumnas . ", " . $id . ", " . $oconn->qstr($sCodISO2, 0) . ", " . ($j+1) . ", " . $oconn->qstr($k, 0) . ", " . $oconn->qstr($v, 0) . "),";
					    		$iColumnas++;
					    	}else{
					    		$iColumnas=1;
					    	}
						}
					}
					//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA
					$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlVal, 0, -1) . ";";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA 
					//QUE LA DESCRIPCION ESTÁ VACIO
					$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "' AND descripcion='';";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
				}
*/
				break;
			case "nips1":
/*
				//Parecido a nips, pero no tenemos la plantilla de corrección.
				$sql= "SELECT * FROM  " . $tablaENUNCIADOS . " ORDER BY id;\n";
				echo "";
				$result = $oconnMssql->Execute($sql);
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
				    				$sqlVal.=$oconn->qstr($v, 0) . ",";
				    			}else{
				    				$sOrigenFile = 'http://www.test-station.es/' . $sPueba . '/image/' . $sIdiomaTS . '/img' . $v . ".jpg";
				    				$sDir = "imgItems/" . $id . "/" . $sCodISO2 . "/";
									$sDestinoFile = $sDir . "img" . $v . ".jpg";
									$this->chk_dir(constant("DIR_FS_DOCUMENT_ROOT") . $sDir);
									if (!@copy($sOrigenFile, constant("DIR_FS_DOCUMENT_ROOT") . $sDestinoFile)){
										@file_put_contents(constant("DIR_FS_DOCUMENT_ROOT") . $sDestinoFile, file_get_contents($sOrigenFile));
									}
				    				$sqlVal.=$oconn->qstr($sDestinoFile, 0) . ",";
				    			}
				    		}else{
				    			$sqlVal.=$oconn->qstr($v, 0) . ",";
				    		}
					}
					$sCorrecta="''";
					$sqlVal.= $id . ", '" . $sCodISO2 . "', " . $idLinea . ", " . $sCorrecta;
					$sqlValTotal.="\n" . "(" . $sqlVal . "),";
				}
				if ($bHayRegistros){
					//BORRAMOS TODOS LOS ITEMS DE ESA PRUEBA
					$sql = "DELETE FROM items where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql= "SELECT * FROM  " . $tablaITEMS . " ORDER BY pregunta;\n";
					$result = $oconnMssql->Execute($sql);
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
					    		$sqlVal.="\n(" . $iColumnas . ", " . $id . ", " . $oconn->qstr($sCodISO2, 0) . ", " . ($j+1) . ", " . $oconn->qstr($k, 0) . ", " . $oconn->qstr($v, 0) . "),";
					    		$iColumnas++;
					    	}else{
					    		$iColumnas=1;
					    	}
						}
					}
					//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA
					$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlVal, 0, -1) . ";";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA 
					//QUE LA DESCRIPCION ESTÁ VACIO
					$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "' AND descripcion='';";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
				}
*/
				break;
			case "vips":
/*
				//Recogemos los items
				$sql= "SELECT * FROM  " . $tablaITEMS . " ORDER BY pagina ASC;\n";
				$result = $oconnMssql->Execute($sql);
				$arr = $result->GetArray();
				$sqlI= "INSERT INTO items ";
				$sqlC="idItem, enunciado";
				$sqlC.= ", idPrueba, codIdiomaIso2, orden, correcto, descripcion";
				$sqlV=" VALUES ";
				$sqlVal="";
				$sqlValTotal="";
				$idLinea = "";
				$sEnunciado="";
				$bHayRegistros=false;
				$sPagina="";
				for ($j=0, $max=sizeof($arr); $j < $max; $j++){
					$bHayRegistros=true;
					$aProp = $arr[$j];
					$sqlVal="";
				    foreach($aProp as $k => $v) {
				    	if ($k == "id" ){
			    			$idLinea = $v;
			    			$sqlVal.=$oconn->qstr($v, 0) . ",";
				    	}
				    	if ($k == "item" ){
			    			if (empty($v)){
			    				$sEnunciado = "";
			    			}else{
								$sEnunciado = $v;
			    			}
			    			$sqlVal.=$oconn->qstr($sEnunciado, 0) . ",";
				    	}
				    	if ($k == "pagina" ){
			    			if (empty($v)){
			    				$sPagina = "";
			    			}else{
								$sPagina = $v;
			    			}
				    	}
					}
					$sql= "SELECT * FROM  " . $tablaSOLUCIONES . " WHERE item=" . $idLinea . ";\n";
					$result = $oconnMssql->Execute($sql);
					$arr1 = $result->GetArray();
					$sCorrecta="";
					for ($i=0, $max1=sizeof($arr1); $i < $max1; $i++){
						$aProp1 = $arr1[$i];
					    foreach($aProp1 as $k => $v) {
					    	if($k == "correcta"){
					    		$sCorrecta = $oconn->qstr($v, 0);
					    		break;
					    	}
						}
						if (!empty($sCorrecta)){
							break;
						}
					}
					
					$sqlVal.= $id . ", '" . $sCodISO2 . "', " . $idLinea . ", " . $sCorrecta;
					
					$sql= "SELECT * FROM  " . $tablaENUNCIADOS . " WHERE id=" . $sPagina . ";\n";
					$result = $oconnMssql->Execute($sql);
					$arr1 = $result->GetArray();
					$sDesc="";
					for ($i=0, $max1=sizeof($arr1); $i < $max1; $i++){
						$aProp1 = $arr1[$i];
					    foreach($aProp1 as $k => $v) {
					    	if($k == "texto"){
					    		$sDesc = $oconn->qstr($v, 0);
					    		break;
					    	}
						}
						if (!empty($sDesc)){
							break;
						}
					}
					$sqlVal.= ", " . $sDesc;
					
					$sqlValTotal.="\n" . "(" . $sqlVal . "),";
				}
				if ($bHayRegistros){
					//BORRAMOS TODOS LOS ITEMS DE ESA PRUEBA
					$sql = "DELETE FROM items where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					
					//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA
					$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql = "SELECT * FROM items where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
					$rsItems = $oconn->Execute($sql);
					while (!$rsItems->EOF)
					{
						$sql=" INSERT INTO opciones";
						$sql .=" (idPrueba, codIdiomaIso2, idItem, idOpcion, descripcion, codigo, fecAlta, fecMod) ";
						$sql .=" VALUES ";
						$sql .=" (" . $rsItems->fields['idPrueba'] . ", " . $oconn->qstr($rsItems->fields['codIdiomaIso2'], 0) . ", " . $rsItems->fields['idItem'] . ", 1, '" . $sVerdadero . "', 'A', now(), now());";
						if($oconn->Execute($sql) === false){
							$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
							error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
							echo(constant("ERR"));
							exit;
						}
						$sql=" INSERT INTO opciones";
						$sql .=" (idPrueba, codIdiomaIso2, idItem, idOpcion, descripcion, codigo, fecAlta, fecMod) ";
						$sql .=" VALUES ";
						$sql .=" (" . $rsItems->fields['idPrueba'] . ", " . $oconn->qstr($rsItems->fields['codIdiomaIso2'], 0) . ", " . $rsItems->fields['idItem'] . ", 2, '" . $sFalso . "', 'B', now(), now());";

						if($oconn->Execute($sql) === false){
							$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
							error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
							echo(constant("ERR"));
							exit;
						}
						$sql=" INSERT INTO opciones";
						$sql .=" (idPrueba, codIdiomaIso2, idItem, idOpcion, descripcion, codigo, fecAlta, fecMod) ";
						$sql .=" VALUES ";
						$sql .=" (" . $rsItems->fields['idPrueba'] . ", " . $oconn->qstr($rsItems->fields['codIdiomaIso2'], 0) . ", " . $rsItems->fields['idItem'] . ", 3, '" . $sInformacion . "', 'C', now(), now());";

						if($oconn->Execute($sql) === false){
							$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
							error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
							echo(constant("ERR"));
							exit;
						}
						$rsItems->MoveNext();	
					}
					//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA 
					//QUE LA DESCRIPCION ESTÁ VACIO
					$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "' AND descripcion='';";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					
				}
*/
				break;
			case "vips1":
/*
				//Parecido a vips, pero no tenemos la plantilla de corrección
				$sql= "SELECT * FROM  " . $tablaENUNCIADOS . " ORDER BY id;\n";
				$result = $oconnMssql->Execute($sql);
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
				    				$sqlVal.=$oconn->qstr($v, 0) . ",";
				    			}else{
				    				$sOrigenFile = 'http://www.test-station.es/' . $sPueba . '/image/' . $sIdiomaTS . '/img' . $v . ".jpg";
				    				$sDir = "imgItems/" . $id . "/" . $sCodISO2 . "/";
									$sDestinoFile = $sDir . "img" . $v . ".jpg";
									$this->chk_dir(constant("DIR_FS_DOCUMENT_ROOT") . $sDir);
									if (!@copy($sOrigenFile, constant("DIR_FS_DOCUMENT_ROOT") . $sDestinoFile)){
										@file_put_contents(constant("DIR_FS_DOCUMENT_ROOT") . $sDestinoFile, file_get_contents($sOrigenFile));
									}
				    				$sqlVal.=$oconn->qstr($sDestinoFile, 0) . ",";
				    			}
				    		}else{
				    			$sqlVal.=$oconn->qstr($v, 0) . ",";
				    		}
					}
					$sCorrecta="''";
					$sqlVal.= $id . ", '" . $sCodISO2 . "', " . $idLinea . ", " . $sCorrecta;
					$sqlValTotal.="\n" . "(" . $sqlVal . "),";
				}
				if ($bHayRegistros){
					//BORRAMOS TODOS LOS ITEMS DE ESA PRUEBA
					$sql = "DELETE FROM items where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
	//				echo $sql;exit; 
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql= "SELECT * FROM  " . $tablaITEMS . " ORDER BY pagina, id;\n";
	//				echo $sql;exit; 
					$result = $oconnMssql->Execute($sql);
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
					    		$sqlVal.=$oconn->qstr($v, 0) . ",";
					    	}else{
					    		if ($iPagina != $aProp["pagina"]){
					    			$idLinea=1;
					    			$sqlVal.=$oconn->qstr($idLinea, 0) . ",";
					    		}else{
					    			$idLinea++;
					    			$sqlVal.=$oconn->qstr($idLinea, 0) . ",";
					    		}
					    	}
					    	$iPagina = $aProp["pagina"];
						}
						$sqlVal.= $id . ", " . $oconn->qstr($sCodISO2, 0);
						$sqlValTotal.="\n" . "(" . $sqlVal . "),";
					}
					//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA
					$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA 
					//QUE LA DESCRIPCION ESTÁ VACIO
					$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "' AND descripcion='';";
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
				}
*/
				break;
			case "prisma":
			case "cpl":
			case "cpl32":
/*
				//Esta prueba tiene 2 posibles respuestas (mejor,peor)
				//NO hay plantilla de corrección
				//No hay texto de la pregunta
				$sql= "SELECT * FROM  " . $tablaITEMS . " ORDER BY pagina, id;\n";
	//			echo "<br />1::" . $sql; 
				$result = $oconnMssql->Execute($sql);
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
			    			$sqlVal.=$oconn->qstr($v, 0) . ",";
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
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					$sql= $sqlI . "(" . $sqlC . ")" . $sqlV . substr($sqlValTotal, 0, -1) . ";";
//					echo "<br />3::" . $sql;
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}
					//BORRAMOS TODAS LAS OPCIONES DE ESA PRUEBA 
					$sql = "DELETE FROM opciones where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "';";
	//				echo "<br />7::" . $sql;
					if($oconn->Execute($sql) === false){
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo(constant("ERR"));
						exit;
					}

					//SELECCIONAMOS TODOS LOS ITEMS DE ESA PRUEBA
					$sql = "SELECT * FROM items where idPrueba=" . $id . " AND codIdiomaIso2='" . $sCodISO2 . "'";
	//				echo "<br />9::" . $sql;
					$listaOpciones = $oconn->Execute($sql);
					while (!$listaOpciones->EOF)
					{
						//Mejor
						$sql=" INSERT INTO opciones";
						$sql .=" (idPrueba, codIdiomaIso2, idItem, idOpcion, descripcion, fecAlta, fecMod) ";
						$sql .=" VALUES ";
						$sql .=" (" . $listaOpciones->fields['idPrueba'] . ", " . $oconn->qstr($listaOpciones->fields['codIdiomaIso2'], 0) . ", " . $listaOpciones->fields['idItem'] . ", 1, '" . $sMejor . "', now(), now());";
	//					echo "<br />10::" . $sql;
						if($oconn->Execute($sql) === false){
							$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
							error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
							echo(constant("ERR"));
							exit;
						}
						//Peor
						$sql=" INSERT INTO opciones";
						$sql .=" (idPrueba, codIdiomaIso2, idItem, idOpcion, descripcion, fecAlta, fecMod) ";
						$sql .=" VALUES ";
						$sql .=" (" . $listaOpciones->fields['idPrueba'] . ", " . $oconn->qstr($listaOpciones->fields['codIdiomaIso2'], 0) . ", " . $listaOpciones->fields['idItem'] . ", 2, '" . $sPeor . "', now(), now());";
	//					echo "<br />11::" . $sql;
						if($oconn->Execute($sql) === false){
							$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
							error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
							echo(constant("ERR"));
							exit;
						}
						$listaOpciones->MoveNext();
					}				
				}
*/
				break;
			default:
				$sCodISO2=$sIdiomaTS;
				break;
		} // end switch
	}

	/***Cargamos los tipos de competencias y las competencias***/
	function cargaCompetencias(){
/*		
		$oconn	= $this->conn;
		
		$sql="";
		$sql = "TRUNCATE TABLE `tipos_competencias`";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `tipos_competencias` (`codIdiomaIso2`, `idTipoCompetencia`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="('es', 1, 'COMPETENCIAS PARA LA GESTIÓN', '', '2011-03-16 13:12:39', '2011-03-16 13:12:39', 0, 0),";
		$sql.="('es', 2, 'COMPETENCIAS TÉCNICAS', '', '2011-03-16 13:12:52', '2011-03-16 13:12:52', 0, 0),";
		$sql.="('es', 3, 'COMPETENCIAS PROMOCIÓN NEGOCIO', '', '2011-03-16 13:13:05', '2011-03-16 13:13:05', 0, 0),";
		$sql.="('es', 4, 'COMPETENCIAS INTERPERSONALES', '', '2011-03-16 13:13:23', '2011-03-16 13:13:23', 0, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `tipos_competencias` (`codIdiomaIso2`, `idTipoCompetencia`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="('en', 1, 'MANAGEMENT SKILLS','', '2011-03-16 13:12:39', '2011-03-16 13:12:39', 0, 0),";
		$sql.="('en', 2, 'TECHNICAL SKILLS','', '2011-03-16 13:12:52', '2011-03-16 13:12:52', 0, 0),";
		$sql.="('en', 3, 'POWERS BUSINESS PROMOTION','', '2011-03-16 13:13:05', '2011-03-16 13:13:05', 0, 0 ),";
		$sql.="('en', 4, 'INTERPERSONAL','', '2011-03-16 13:13:23', '2011-03-16 13:13:23', 0, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `tipos_competencias` (`codIdiomaIso2`, `idTipoCompetencia`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="('ca', 1,' COMPETÈNCIES PER A LA GESTIÓ','', '2011-03-16 13:12:39', '2011-03-16 13:12:39', 0, 0),";
		$sql.="('ca', 2,' COMPETÈNCIES TÈCNIQUES','', '2011-03-16 13:12:52', '2011-03-16 13:12:52', 0, 0),";
		$sql.="('ca', 3,' COMPETÈNCIES PROMOCIÓ NEGOCI','', '2011-03-16 13:13:05', '2011-03-16 13:13:05', 0, 0),";
		$sql.="('ca', 4,' COMPETÈNCIES INTERPERSONALS','', '2011-03-16 13:13:23', '2011-03-16 13:13:23', 0, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `tipos_competencias` (`codIdiomaIso2`, `idTipoCompetencia`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="('pt', 1, 'COMPETÊNCIAS DE GESTÃO','', '2011-03-16 13:12:39', '2011-03-16 13:12:39', 0, 0),";
		$sql.="('pt', 2, 'HABILIDADES TÉCNICAS','', '2011-03-16 13:12:52', '2011-03-16 13:12:52', 0, 0),";
		$sql.="('pt', 3, 'PROMOÇÃO COMERCIAL PODERES','', '2011-03-16 13:13:05', '2011-03-16 13:13:05', 0, 0 ),";
		$sql.="('pt', 4, 'COMPETÊNCIAS INTERPESSOAL','', '2011-03-16 13:13:23', '2011-03-16 13:13:23', 0, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		
		$sql="";
		$sql = "TRUNCATE TABLE `competencias`";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `competencias` (`codIdiomaIso2`, `idTipoCompetencia`, `idCompetencia`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="('es', 1, 1, 'Dirigir y Liderar', '', '2011-03-16 13:14:03', '2011-03-16 13:14:03', 0, 0),";
		$sql.="('es', 1, 2, 'Planificación', '', '2011-03-16 13:14:15', '2011-03-16 13:14:15', 0, 0),";
		$sql.="('es', 1, 3, 'Comunicación Social', '', '2011-03-16 13:14:28', '2011-03-16 13:14:28', 0, 0),";
		$sql.="('es', 1, 4, 'Persuasividad e Influencia', '', '2011-03-16 13:14:41', '2011-03-16 13:14:41', 0, 0),";
		$sql.="('es', 2, 1, 'Dominio de su especialidad', '', '2011-03-16 13:14:52', '2011-03-16 13:14:52', 0, 0),";
		$sql.="('es', 2, 2, 'Competencia Analítica', '', '2011-03-16 13:15:04', '2011-03-16 13:15:04', 0, 0),";
		$sql.="('es', 2, 3, 'Garantía y Promoción de Calidad', '', '2011-03-16 13:15:33', '2011-03-16 13:15:33', 0, 0),";
		$sql.="('es', 2, 4, 'Orden y Sistemática', '', '2011-03-16 13:15:48', '2011-03-16 13:15:48', 0, 0),";
		$sql.="('es', 3, 1, 'Orientacion al logro de resultados', '', '2011-03-16 13:16:10', '2011-03-16 13:16:10', 0, 0),";
		$sql.="('es', 3, 2, 'Innovación y Creatividad', '', '2011-03-16 13:16:36', '2011-03-16 13:16:36', 0, 0),";
		$sql.="('es', 3, 3, 'Orientación a la acción y decisión', '', '2011-03-16 13:16:58', '2011-03-16 13:16:58', 0, 0),";
		$sql.="('es', 3, 4, 'Visión Estratégica', '', '2011-03-16 13:17:14', '2011-03-16 13:17:14', 0, 0),";
		$sql.="('es', 4, 1, 'Habilidad Interpersonal', '', '2011-03-16 13:17:43', '2011-03-16 13:17:43', 0, 0),";
		$sql.="('es', 4, 2, 'Fexibilidad y Adaptación', '', '2011-03-16 13:18:44', '2011-03-16 13:18:44', 0, 0),";
		$sql.="('es', 4, 3, 'Resistencia a la fatiga y estrés', '', '2011-03-16 13:19:03', '2011-03-16 13:19:03', 0, 0),";
		$sql.="('es', 4, 4, 'Motivación personal en el trabajo', '', '2011-03-16 13:19:26', '2011-03-16 13:19:26', 0, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `competencias` (`codIdiomaIso2`, `idTipoCompetencia`, `idCompetencia`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="('en', 1, 1, 'Lead and Lead','', '2011-03-16 13:14:03', '2011-03-16 13:14:03', 0 , 0),";
		$sql.="('en', 1, 2, 'Planning','', '2011-03-16 13:14:15', '2011-03-16 13:14:15', 0, 0 ),";
		$sql.="('en', 1, 3, 'Social Communication','', '2011-03-16 13:14:28', '2011-03-16 13:14:28', 0, 0),";
		$sql.="('en', 1, 4, 'Persuasiveness and Influence','', '2011-03-16 13:14:41', '2011-03-16 13:14:41', 0 , 0),";
		$sql.="('en', 2, 1, 'domain of expertise','', '2011-03-16 13:14:52', '2011-03-16 13:14:52', 0, 0),";
		$sql.="('en', 2, 2, 'Analytical Competition','', '2011-03-16 13:15:04', '2011-03-16 13:15:04', 0, 0),";
		$sql.="('en', 2, 3, 'Quality Assurance and Promotion','', '2011-03-16 13:15:33', '2011-03-16 13:15:33' , 0, 0),";
		$sql.="('en', 2, 4, 'Order and Systematics','', '2011-03-16 13:15:48', '2011-03-16 13:15:48', 0 , 0),";
		$sql.="('en', 3, 1, 'results-position','', '2011-03-16 13:16:10', '2011-03-16 13:16:10' , 0, 0),";
		$sql.="('en', 3, 2, 'Innovation and Creativity','', '2011-03-16 13:16:36', '2011-03-16 13:16:36', 0 , 0),";
		$sql.="('en', 3, 3, 'Focus on action and decision','', '2011-03-16 13:16:58', '2011-03-16 13:16:58', 0, 0),";
		$sql.="('en', 3, 4, 'Strategic Vision','', '2011-03-16 13:17:14', '2011-03-16 13:17:14', 0, 0),";
		$sql.="('en', 4, 1, 'Interpersonal Skills','', '2011-03-16 13:17:43', '2011-03-16 13:17:43', 0, 0),";
		$sql.="('en', 4, 2, 'Fexibilidad and Adaptation','', '2011-03-16 13:18:44', '2011-03-16 13:18:44', 0 , 0),";
		$sql.="('en', 4, 3, 'resistance to fatigue and stress','', '2011-03-16 13:19:03', '2011-03-16 13:19:03', 0, 0),";
		$sql.="('en', 4, 4, 'personal motivation at work','', '2011-03-16 13:19:26', '2011-03-16 13:19:26' , 0, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `competencias` (`codIdiomaIso2`, `idTipoCompetencia`, `idCompetencia`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="('ca', 1, 1, 'Dirigir i Liderar','', '2011-03-16 13:14:03', '2011-03-16 13:14:03', 0 , 0),";
		$sql.="('ca', 1, 2, 'Planificació','', '2011-03-16 13:14:15', '2011-03-16 13:14:15', 0, 0 ),";
		$sql.="('ca', 1, 3, 'Comunicació Social','', '2011-03-16 13:14:28', '2011-03-16 13:14:28', 0, 0),";
		$sql.="('ca', 1, 4, 'persuasiva i Influència','', '2011-03-16 13:14:41', '2011-03-16 13:14:41', 0 , 0),";
		$sql.="('ca', 2, 1, 'Domini de la seva especialitat','', '2011-03-16 13:14:52', '2011-03-16 13:14:52', 0, 0),";
		$sql.="('ca', 2, 2, 'Competència Analítica','', '2011-03-16 13:15:04', '2011-03-16 13:15:04', 0, 0),";
		$sql.="('ca', 2, 3, 'Garantia i Promoció de Qualitat','', '2011-03-16 13:15:33', '2011-03-16 13:15:33', 0, 0),";
		$sql.="('ca', 2, 4, 'Ordre i Sistemàtica','', '2011-03-16 13:15:48', '2011-03-16 13:15:48', 0 , 0),";
		$sql.="('ca', 3, 1, 'Orientació a l\'assoliment de resultats','', '2011-03-16 13:16:10', '2011-03-16 13:16:10', 0, 0),";
		$sql.="('ca', 3, 2, 'Innovació i Creativitat','', '2011-03-16 13:16:36', '2011-03-16 13:16:36', 0 , 0),";
		$sql.="('ca', 3, 3, 'Orientació a l\'acció i decisió','', '2011-03-16 13:16:58', '2011-03-16 13:16:58', 0, 0),";
		$sql.="('ca', 3, 4, 'Visió Estratègica','', '2011-03-16 13:17:14', '2011-03-16 13:17:14', 0, 0),";
		$sql.="('ca', 4, 1, 'Habilitat Interpersonal','', '2011-03-16 13:17:43', '2011-03-16 13:17:43', 0, 0),";
		$sql.="('ca', 4, 2, 'Fexibilidad i Adaptació','', '2011-03-16 13:18:44', '2011-03-16 13:18:44', 0 , 0),";
		$sql.="('ca', 4, 3, 'Resistència a la fatiga i estrès','', '2011-03-16 13:19:03', '2011-03-16 13:19:03', 0, 0),";
		$sql.="('ca', 4, 4, 'Motivació personal en el treball','', '2011-03-16 13:19:26', '2011-03-16 13:19:26', 0, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `competencias` (`codIdiomaIso2`, `idTipoCompetencia`, `idCompetencia`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="('pt', 1, 1, 'chumbo e','', '2011-03-16 13:14:03', '2011-03-16 13:14:03', 0 , 0),";
		$sql.="('pt', 1, 2, 'Planejamento','', '2011-03-16 13:14:15', '2011-03-16 13:14:15', 0, 0 ),";
		$sql.="('pt', 1, 3, 'Comunicação Social','', '2011-03-16 13:14:28', '2011-03-16 13:14:28', 0, 0),";
		$sql.="('pt', 1, 4, 'persuasão e influência','', '2011-03-16 13:14:41', '2011-03-16 13:14:41', 0 , 0),";
		$sql.="('pt', 2, 1, 'domínio de conhecimentos','', '2011-03-16 13:14:52', '2011-03-16 13:14:52', 0, 0),";
		$sql.="('pt', 2, 2, 'Competição Analítica','', '2011-03-16 13:15:04', '2011-03-16 13:15:04', 0, 0),";
		$sql.="('pt', 2, 3, 'Garantia da Qualidade e Promoção','', '2011-03-16 13:15:33', '2011-03-16 13:15:33', 0, 0),";
		$sql.="('pt', 2, 4, 'Ordem e Sistemática','', '2011-03-16 13:15:48', '2011-03-16 13:15:48', 0 , 0),";
		$sql.="('pt', 3, 1, 'resultados posição','', '2011-03-16 13:16:10', '2011-03-16 13:16:10' , 0, 0),";
		$sql.="('pt', 3, 2, 'Inovação e Criatividade','', '2011-03-16 13:16:36', '2011-03-16 13:16:36', 0 , 0),";
		$sql.="('pt', 3, 3, 'foco na ação e decisão','', '2011-03-16 13:16:58', '2011-03-16 13:16:58', 0, 0),";
		$sql.="('pt', 3, 4, 'Visão Estratégica','', '2011-03-16 13:17:14', '2011-03-16 13:17:14', 0, 0),";
		$sql.="('pt', 4, 1, 'habilidades interpessoais','', '2011-03-16 13:17:43', '2011-03-16 13:17:43', 0, 0),";
		$sql.="('pt', 4, 2, 'Fexibilidad e Adaptação','', '2011-03-16 13:18:44', '2011-03-16 13:18:44', 0 , 0),";
		$sql.="('pt', 4, 3, 'a resistência à fadiga e estresse','', '2011-03-16 13:19:03', '2011-03-16 13:19:03', 0, 0),";
		$sql.="('pt', 4, 4, 'motivação pessoal no trabalho','', '2011-03-16 13:19:26', '2011-03-16 13:19:26' , 0, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
*/
		
	}

	/***Cargamos los bloques y las escalas***/
	function cargaEscalas(){
/*		
		$oconn	= $this->conn;

		$sql="";
		$sql = "TRUNCATE TABLE `bloques`";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `bloques` (`codIdiomaIso2`, `idBloque`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="('es', 1, 'ENERGÍAS Y MOTIVACIONES', '', '2011-03-16 10:59:39', '2011-03-16 10:59:39', 0, 0),";
		$sql.="('es', 2, 'CONTROL EMOCIONAL Y TOLERANCIA AL ESTRÉS', '', '2011-03-16 10:59:51', '2011-03-16 10:59:51', 0, 0),";
		$sql.="('es', 3, 'ORIENTACIÓN A LA RELACIÓN', '', '2011-03-16 11:00:03', '2011-03-16 11:00:03', 0, 0),";
		$sql.="('es', 4, 'ORIENTACIÓN A LAS PERSONAS', '', '2011-03-16 11:00:14', '2011-03-16 11:00:14', 0, 0),";
		$sql.="('es', 5, 'INFLUENCIA ASCENDENCIA Y MANDO', '', '2011-03-16 11:00:26', '2011-03-16 11:00:26', 0, 0),";
		$sql.="('es', 6, 'COMPETENCIAS ANALÍTICAS', '', '2011-03-16 11:00:38', '2011-03-16 11:00:38', 0, 0),";
		$sql.="('es', 7, 'POTENCIAL RECURSOS MENTALES', '', '2011-03-16 11:00:48', '2011-03-16 11:00:48', 0, 0),";
		$sql.="('es', 8, 'FLEXIBILIDAD', '', '2011-03-16 11:00:58', '2011-03-16 11:00:58', 0, 0),";
		$sql.="('es', 9, 'ESTILO DE TRABAJO', '', '2011-03-16 11:01:10', '2011-03-16 11:01:10', 0, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `bloques` (`codIdiomaIso2`, `idBloque`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql .="('en', 1, 'ENERGY AND MOTIVATION','', '2011-03-16 10:59:39', '2011-03-16 10:59:39', 0, 0),";
		$sql .="('en', 2, 'CONTROL AND EMOTIONAL STRESS TOLERANCE','', '2011-03-16 10:59:51', '2011-03-16 10:59:51', 0, 0),";
		$sql .="('en', 3, 'FOCUS ON THE RELATIONSHIP','', '2011-03-16 11:00:03', '2011-03-16 11:00:03', 0, 0),";
		$sql .="('en', 4, 'PEOPLE-ORIENTED APPROACH','', '2011-03-16 11:00:14', '2011-03-16 11:00:14', 0, 0),";
		$sql .="('en', 5, 'INFLUENCE AND CONTROL DESCENT','', '2011-03-16 11:00:26', '2011-03-16 11:00:26', 0, 0),";
		$sql .="('en', 6, 'ANALYTIC COMPETENCE','', '2011-03-16 11:00:38', '2011-03-16 11:00:38', 0, 0),";
		$sql .="('en', 7, 'POTENTIAL MENTAL RESOURCES','', '2011-03-16 11:00:48', '2011-03-16 11:00:48', 0, 0),";
		$sql .="('en', 8, 'FLEXIBILITY','', '2011-03-16 11:00:58', '2011-03-16 11:00:58', 0, 0),";
		$sql .="('en', 9, 'STYLE OF WORK','', '2011-03-16 11:01:10', '2011-03-16 11:01:10', 0, 0 );";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `bloques` (`codIdiomaIso2`, `idBloque`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql .="('ca', 1,'ENERGIES I MOTIVACIONS','', '2011-03-16 10:59:39', '2011-03-16 10:59:39', 0, 0 ),";
		$sql .="('ca', 2,'CONTROL EMOCIONAL I TOLERÀNCIA A L\'ESTRÈS','', '2011-03-16 10:59:51', '2011-03-16 10:59:51', 0, 0),";
		$sql .="('ca', 3,'ORIENTACIÓ A LA RELACIÓ','', '2011-03-16 11:00:03', '2011-03-16 11:00:03', 0, 0),";
		$sql .="('ca', 4,'ORIENTACIÓ A LES PERSONES','', '2011-03-16 11:00:14', '2011-03-16 11:00:14', 0, 0),";
		$sql .="('ca', 5,'INFLUÈNCIA ASCENDENT I COMANDAMENT','', '2011-03-16 11:00:26', '2011-03-16 11:00:26', 0, 0),";
		$sql .="('ca', 6,'COMPETÈNCIES ANALÍTIQUES','', '2011-03-16 11:00:38', '2011-03-16 11:00:38', 0, 0),";
		$sql .="('ca', 7,'POTENCIAL RECURSOS MENTALS','', '2011-03-16 11:00:48', '2011-03-16 11:00:48', 0, 0 ),";
		$sql .="('ca', 8,'FLEXIBILITAT','', '2011-03-16 11:00:58', '2011-03-16 11:00:58', 0, 0),";
		$sql .="('ca', 9,'ESTIL DE TREBALL','', '2011-03-16 11:01:10', '2011-03-16 11:01:10', 0, 0 );";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `bloques` (`codIdiomaIso2`, `idBloque`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql .="('pt', 1, 'ENERGIA E MOTIVAÇÃO','', '2011-03-16 10:59:39', '2011-03-16 10:59:39', 0, 0 ),";
		$sql .="('pt', 2, 'DE CONTROLO E TOLERÂNCIA AO ESTRESSE EMOCIONAL','', '2011-03-16 10:59:51', '2011-03-16 10:59:51', 0, 0),";
		$sql .="('pt', 3, 'FOCO NO RELACIONAMENTO','', '2011-03-16 11:00:03', '2011-03-16 11:00:03', 0, 0),";
		$sql .="('pt', 4, 'ORIENTADA PARA AS PESSOAS ABORDAGEM','', '2011-03-16 11:00:14', '2011-03-16 11:00:14', 0, 0),";
		$sql .="('pt', 5, 'INFLUÊNCIA E CONTROLE DESCIDA','', '2011-03-16 11:00:26', '2011-03-16 11:00:26', 0, 0),";
		$sql .="('pt', 6, 'COMPETÊNCIA ANALÍTICA','', '2011-03-16 11:00:38', '2011-03-16 11:00:38', 0, 0),";
		$sql .="('pt', 7, 'RECURSOS POTENCIAIS MENTAIS','', '2011-03-16 11:00:48', '2011-03-16 11:00:48', 0, 0 ),";
		$sql .="('pt', 8, 'FLEXIBILIDADE','','2011-03-16 11:00:58', '2011-03-16 11:00:58', 0, 0),";
		$sql .="('pt', 9, 'ESTILO DE TRABALHO','', '2011-03-16 11:01:10', '2011-03-16 11:01:10', 0, 0 );";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		
		$sql="";
		$sql = "TRUNCATE TABLE `escalas`";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		
		$sql="";
		$sql="INSERT INTO `escalas` (`codIdiomaIso2`, `idBloque`, `idEscala`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="('es', 1, 1, 'Dinamismo y actividad', '', '2011-03-16 11:01:44', '2011-03-16 11:01:44', 0, 0),";
		$sql.="('es', 1, 2, 'Constancia / Perseverancia', '', '2011-03-16 11:01:56', '2011-03-16 11:01:56', 0, 0),";
		$sql.="('es', 1, 3, 'Ambición de metas y logros', '', '2011-03-16 11:02:06', '2011-03-16 11:02:06', 0, 0),";
		$sql.="('es', 1, 4, 'Competitividad', '', '2011-03-16 11:02:16', '2011-03-16 11:02:16', 0, 0),";
		$sql.="('es', 1, 5, 'Decisión', '', '2011-03-16 11:02:26', '2011-03-16 11:02:26', 0, 0),";
		$sql.="('es', 2, 1, 'Tensión interna', '', '2011-03-16 11:03:24', '2011-03-16 11:03:24', 0, 0),";
		$sql.="('es', 2, 2, 'Autodominio', '', '2011-03-16 11:03:37', '2011-03-16 11:03:37', 0, 0),";
		$sql.="('es', 2, 3, 'Resistencia emocional', '', '2011-03-16 11:03:51', '2011-03-16 11:03:51', 0, 0),";
		$sql.="('es', 2, 4, 'Control Externo', '', '2011-03-16 11:04:04', '2011-03-16 11:04:04', 0, 0),";
		$sql.="('es', 2, 5, 'Confianza en los demás', '', '2011-03-16 11:04:22', '2011-03-16 11:04:22', 0, 0),";
		$sql.="('es', 2, 6, 'Actitud positiva', '', '2011-03-16 11:04:49', '2011-03-16 11:04:49', 0, 0),";
		$sql.="('es', 3, 1, 'Extroversión', '', '2011-03-16 11:05:24', '2011-03-16 11:05:24', 0, 0),";
		$sql.="('es', 3, 2, 'Afiliación / Sociabilidad', '', '2011-03-16 11:05:40', '2011-03-16 11:05:40', 0, 0),";
		$sql.="('es', 3, 3, 'Habilidad relacional', '', '2011-03-16 11:05:53', '2011-03-16 11:05:53', 0, 0),";
		$sql.="('es', 4, 1, 'Empatía', '', '2011-03-16 11:06:07', '2011-03-16 11:06:07', 0, 0),";
		$sql.="('es', 4, 2, 'Modestia', '', '2011-03-16 11:06:43', '2011-03-16 11:06:43', 0, 0),";
		$sql.="('es', 4, 3, 'Consensuador/a', '', '2011-03-16 11:06:54', '2011-03-16 11:06:54', 0, 0),";
		$sql.="('es', 5, 1, 'Persuasión', '', '2011-03-16 11:48:37', '2011-03-16 11:48:37', 0, 0),";
		$sql.="('es', 5, 2, 'Voluntad de mando y dirección', '', '2011-03-16 11:48:51', '2011-03-16 11:48:51', 0, 0),";
		$sql.="('es', 5, 3, 'Autonomía / Autodeterminación', '', '2011-03-16 11:49:05', '2011-03-16 11:49:05', 0, 0),";
		$sql.="('es', 5, 4, 'Directo / Espontáneo', '', '2011-03-16 11:49:19', '2011-03-16 11:49:19', 0, 0),";
		$sql.="('es', 6, 1, 'Análisis objetivo', '', '2011-03-16 11:49:37', '2011-03-16 11:49:37', 0, 0),";
		$sql.="('es', 6, 2, 'Análisis de riesgos', '', '2011-03-16 11:50:12', '2011-03-16 11:50:22', 0, 0),";
		$sql.="('es', 6, 3, 'Análisis de personas', '', '2011-03-16 11:50:41', '2011-03-16 11:50:41', 0, 0),";
		$sql.="('es', 7, 1, 'Profundidad conceptual', '', '2011-03-16 11:51:27', '2011-03-16 11:51:27', 0, 0),";
		$sql.="('es', 7, 2, 'Innovación y soluciones', '', '2011-03-16 11:51:42', '2011-03-16 11:51:42', 0, 0),";
		$sql.="('es', 7, 3, 'Previsión y planificación', '', '2011-03-16 11:51:55', '2011-03-16 11:51:55', 0, 0),";
		$sql.="('es', 8, 1, 'Adaptabilidad / Flexibilidad', '', '2011-03-16 11:52:11', '2011-03-16 11:52:11', 0, 0),";
		$sql.="('es', 8, 2, 'Pro-cambios', '', '2011-03-16 11:52:28', '2011-03-16 11:52:28', 0, 0),";
		$sql.="('es', 8, 3, 'Pragmatismo', '', '2011-03-16 11:52:46', '2011-03-16 11:52:46', 0, 0),";
		$sql.="('es', 9, 1, 'Orden / Organización', '', '2011-03-16 11:53:05', '2011-03-16 11:53:05', 0, 0),";
		$sql.="('es', 9, 2, 'Disciplina con normas', '', '2011-03-16 11:53:32', '2011-03-16 11:53:32', 0, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `escalas` (`codIdiomaIso2`, `idBloque`, `idEscala`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES ";
		$sql.="('en', 1, 1, 'Dynamism and activity','', '2011-03-16 11:01:44', '2011-03-16 11:01:44', 0 , 0),";
		$sql.="('en', 1, 2, 'Proof / Perseverance','', '2011-03-16 11:01:56', '2011-03-16 11:01:56', 0 , 0),";
		$sql.="('en', 1, 3, 'Ambition and achievement of goals','', '2011-03-16 11:02:06', '2011-03-16 11:02:06' , 0, 0),";
		$sql.="('en', 1, 4, 'Competitiveness','', '2011-03-16 11:02:16', '2011-03-16 11:02:16', 0, 0 ),";
		$sql.="('en', 1, 5, 'Decision','', '2011-03-16 11:02:26', '2011-03-16 11:02:26', 0, 0 ),";
		$sql.="('en', 2, 1, 'Internal stress','', '2011-03-16 11:03:24', '2011-03-16 11:03:24', 0, 0),";
		$sql.="('en', 2, 2, 'Self Control','', '2011-03-16 11:03:37', '2011-03-16 11:03:37', 0, 0 ),";
		$sql.="('en', 2, 3, 'emotional resistance','', '2011-03-16 11:03:51', '2011-03-16 11:03:51', 0, 0),";
		$sql.="('en', 2, 4, 'External Control','', '2011-03-16 11:04:04', '2011-03-16 11:04:04', 0, 0),";
		$sql.="('en', 2, 5, 'Confidence in others','', '2011-03-16 11:04:22', '2011-03-16 11:04:22', 0, 0),";
		$sql.="('en', 2, 6, 'Positive attitude','', '2011-03-16 11:04:49', '2011-03-16 11:04:49', 0, 0),";
		$sql.="('en', 3, 1, 'Extraversion','', '2011-03-16 11:05:24', '2011-03-16 11:05:24', 0, 0 ),";
		$sql.="('en', 3, 2, 'Membership / Sociability','', '2011-03-16 11:05:40', '2011-03-16 11:05:40', 0 , 0),";
		$sql.="('en', 3, 3, 'Ability relational','', '2011-03-16 11:05:53', '2011-03-16 11:05:53', 0, 0),";
		$sql.="('en', 4, 1, 'Empathy','', '2011-03-16 11:06:07', '2011-03-16 11:06:07', 0, 0 ),";
		$sql.="('en', 4, 2, 'Modesty','', '2011-03-16 11:06:43', '2011-03-16 11:06:43', 0, 0 ),";
		$sql.="('en', 4, 3, 'Consensual / a','', '2011-03-16 11:06:54', '2011-03-16 11:06:54', 0 , 0),";
		$sql.="('en', 5, 1, 'Persuasion','', '2011-03-16 11:48:37', '2011-03-16 11:48:37', 0, 0 ),";
		$sql.="('en', 5, 2, 'Will of leadership and management','', '2011-03-16 11:48:51', '2011-03-16 11:48:51' , 0, 0),";
		$sql.="('en', 5, 3, 'Autonomy / Self-Determination','', '2011-03-16 11:49:05', '2011-03-16 11:49:05', 0 , 0),";
		$sql.="('en', 5, 4, 'Direct / Spontaneous','', '2011-03-16 11:49:19', '2011-03-16 11:49:19', 0 , 0),";
		$sql.="('en', 6, 1, 'objective analysis','', '2011-03-16 11:49:37', '2011-03-16 11:49:37', 0, 0),";
		$sql.="('en', 6, 2, 'Risk Analysis','', '2011-03-16 11:50:12', '2011-03-16 11:50:22', 0 , 0),";
		$sql.="('en', 6, 3, 'Analysis of individuals','', '2011-03-16 11:50:41', '2011-03-16 11:50:41', 0 , 0),";
		$sql.="('en', 7, 1, 'conceptual depth','', '2011-03-16 11:51:27', '2011-03-16 11:51:27', 0, 0),";
		$sql.="('en', 7, 2, 'Innovation and Solutions','', '2011-03-16 11:51:42', '2011-03-16 11:51:42', 0 , 0),";
		$sql.="('en', 7, 3, 'Forecasting and planning','', '2011-03-16 11:51:55', '2011-03-16 11:51:55', 0 , 0),";
		$sql.="('en', 8, 1, 'Adaptability / Flexibility','', '2011-03-16 11:52:11', '2011-03-16 11:52:11', 0 , 0),";
		$sql.="('en', 8, 2, 'Pro-change','', '2011-03-16 11:52:28', '2011-03-16 11:52:28', 0 , 0),";
		$sql.="('en', 8, 3, 'Pragmatism','', '2011-03-16 11:52:46', '2011-03-16 11:52:46', 0, 0 ),";
		$sql.="('en', 9, 1, 'Order / Organization','', '2011-03-16 11:53:05', '2011-03-16 11:53:05', 0 , 0),";
		$sql.="('en', 9, 2, 'Discipline with rules','', '2011-03-16 11:53:32', '2011-03-16 11:53:32', 0 , 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql="INSERT INTO `escalas` (`codIdiomaIso2`, `idBloque`, `idEscala`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES ";
		$sql.="('ca', 1, 1, 'Dinamisme i activitat','', '2011-03-16 11:01:44', '2011-03-16 11:01:44', 0 , 0),";
		$sql.="('ca', 1, 2, 'Constància / Perseverança','', '2011-03-16 11:01:56', '2011-03-16 11:01:56', 0 , 0),";
		$sql.="('ca', 1, 3, 'Ambició de metes i èxits','', '2011-03-16 11:02:06', '2011-03-16 11:02:06 ' , 0, 0),";
		$sql.="('ca', 1, 4, 'Competitivitat','', '2011-03-16 11:02:16', '2011-03-16 11:02:16', 0, 0 ),";
		$sql.="('ca', 1, 5, 'Decisió','', '2011-03-16 11:02:26', '2011-03-16 11:02:26', 0, 0 ),";
		$sql.="('ca', 2, 1, 'tensió interna','', '2011-03-16 11:03:24', '2011-03-16 11:03:24', 0, 0),";
		$sql.="('ca', 2, 2, 'Autodomini','', '2011-03-16 11:03:37', '2011-03-16 11:03:37', 0, 0 ),";
		$sql.="('ca', 2, 3, 'Resistència emocional','', '2011-03-16 11:03:51', '2011-03-16 11:03:51', 0, 0),";
		$sql.="('ca', 2, 4, 'Control Extern','', '2011-03-16 11:04:04', '2011-03-16 11:04:04', 0, 0),";
		$sql.="('ca', 2, 5, 'Confiança en els altres','', '2011-03-16 11:04:22', '2011-03-16 11:04:22', 0, 0),";
		$sql.="('ca', 2, 6, 'Actitud positiva','', '2011-03-16 11:04:49', '2011-03-16 11:04:49', 0, 0),";
		$sql.="('ca', 3, 1, 'Extraversió','', '2011-03-16 11:05:24', '2011-03-16 11:05:24', 0, 0 ),";
		$sql.="('ca', 3, 2, 'Afiliació / Sociabilitat','', '2011-03-16 11:05:40', '2011-03-16 11:05:40', 0 , 0),";
		$sql.="('ca', 3, 3, 'Habilitat relacional','', '2011-03-16 11:05:53', '2011-03-16 11:05:53', 0, 0),";
		$sql.="('ca', 4, 1, 'Empatia','', '2011-03-16 11:06:07', '2011-03-16 11:06:07', 0, 0 ),";
		$sql.="('ca', 4, 2, 'Modèstia','', '2011-03-16 11:06:43', '2011-03-16 11:06:43', 0, 0 ),";
		$sql.="('ca', 4, 3, 'Consensuats / a','', '2011-03-16 11:06:54', '2011-03-16 11:06:54', 0 , 0),";
		$sql.="('ca', 5, 1, 'Persuasió','', '2011-03-16 11:48:37', '2011-03-16 11:48:37', 0, 0 ),";
		$sql.="('ca', 5, 2, 'Voluntat de comandament i direcció','', '2011-03-16 11:48:51', '2011-03-16 11:48:51 ' , 0, 0),";
		$sql.="('ca', 5, 3, 'Autonomia / Autodeterminació','', '2011-03-16 11:49:05', '2011-03-16 11:49:05', 0 , 0),";
		$sql.="('ca', 5, 4, 'Directe / Espontani','', '2011-03-16 11:49:19', '2011-03-16 11:49:19', 0 , 0),";
		$sql.="('ca', 6, 1, 'Anàlisi objectiu','', '2011-03-16 11:49:37', '2011-03-16 11:49:37', 0, 0),";
		$sql.="('ca', 6, 2, 'Anàlisi de riscos','', '2011-03-16 11:50:12', '2011-03-16 11:50:22', 0 , 0),";
		$sql.="('ca', 6, 3, 'Anàlisi de persones','', '2011-03-16 11:50:41', '2011-03-16 11:50:41', 0 , 0),";
		$sql.="('ca', 7, 1, 'Profunditat conceptual','', '2011-03-16 11:51:27', '2011-03-16 11:51:27', 0, 0),";
		$sql.="('ca', 7, 2, 'Innovació i solucions','', '2011-03-16 11:51:42', '2011-03-16 11:51:42', 0 , 0),";
		$sql.="('ca', 7, 3, 'Previsió i planificació','', '2011-03-16 11:51:55', '2011-03-16 11:51:55', 0 , 0),";
		$sql.="('ca', 8, 1, 'Adaptabilitat / Flexibilitat','', '2011-03-16 11:52:11', '2011-03-16 11:52:11', 0 , 0),";
		$sql.="('ca', 8, 2, 'Pro-canvis','', '2011-03-16 11:52:28', '2011-03-16 11:52:28', 0 , 0),";
		$sql.="('ca', 8, 3, 'Pragmatisme','', '2011-03-16 11:52:46', '2011-03-16 11:52:46', 0, 0 ),";
		$sql.="('ca', 9, 1, 'Ordre / Organització','', '2011-03-16 11:53:05', '2011-03-16 11:53:05', 0 , 0),";
		$sql.="('ca', 9, 2, 'Disciplina amb normes','', '2011-03-16 11:53:32', '2011-03-16 11:53:32', 0 , 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		
		$sql="";
		$sql="INSERT INTO `escalas` (`codIdiomaIso2`, `idBloque`, `idEscala`, `nombre`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES ";
		$sql.="('pt', 1, 1, 'Dinamismo e atividade','', '2011-03-16 11:01:44', '2011-03-16 11:01:44', 0 , 0),";
		$sql.="('pt', 1, 2, 'Prova / Perseverança','', '2011-03-16 11:01:56', '2011-03-16 11:01:56', 0 , 0),";
		$sql.="('pt', 1, 3, 'Ambição e conquista de metas','', '2011-03-16 11:02:06', '2011-03-16 11:02:06 ' , 0, 0),";
		$sql.="('pt', 1, 4, 'Competitividade','', '2011-03-16 11:02:16', '2011-03-16 11:02:16', 0, 0),";
		$sql.="('pt', 1, 5, 'a seguir Decisão','', '2011-03-16 11:02:26', '2011-03-16 11:02:26', 0, 0),";
		$sql.="('pt', 2, 1, 'stress interno','', '2011-03-16 11:03:24', '2011-03-16 11:03:24', 0, 0),";
		$sql.="('pt', 2, 2, 'Self Control','', '2011-03-16 11:03:37', '2011-03-16 11:03:37', 0, 0),";
		$sql.="('pt', 2, 3, 'a resistência emocional','', '2011-03-16 11:03:51', '2011-03-16 11:03:51', 0, 0),";
		$sql.="('pt', 2, 4, 'Controle Externo','', '2011-03-16 11:04:04', '2011-03-16 11:04:04', 0, 0),";
		$sql.="('pt', 2, 5, 'Confiança em outros','', '2011-03-16 11:04:22', '2011-03-16 11:04:22', 0, 0),";
		$sql.="('pt', 2, 6, 'Atitude positiva','', '2011-03-16 11:04:49', '2011-03-16 11:04:49', 0, 0),";
		$sql.="('pt', 3, 1, 'Extroversão','', '2011-03-16 11:05:24', '2011-03-16 11:05:24', 0, 0),";
		$sql.="('pt', 3, 2, 'Composição / Sociabilidade','', '2011-03-16 11:05:40', '2011-03-16 11:05:40', 0 , 0),";
		$sql.="('pt', 3, 3, 'a capacidade relacional','', '2011-03-16 11:05:53', '2011-03-16 11:05:53', 0, 0),";
		$sql.="('pt', 4, 1, 'Empatia','', '2011-03-16 11:06:07', '2011-03-16 11:06:07', 0, 0),";
		$sql.="('pt', 4, 2, 'Modéstia','', '2011-03-16 11:06:43', '2011-03-16 11:06:43', 0, 0),";
		$sql.="('pt', 4, 3, 'Consensual / a','', '2011-03-16 11:06:54', '2011-03-16 11:06:54', 0 , 0),";
		$sql.="('pt', 5, 1, 'Persuasão','', '2011-03-16 11:48:37', '2011-03-16 11:48:37', 0, 0),";
		$sql.="('pt', 5, 2, 'Vontade de liderança e gestão','', '2011-03-16 11:48:51', '2011-03-16 11:48:51' , 0, 0),";
		$sql.="('pt', 5, 3, 'Autonomia / Autodeterminação','', '2011-03-16 11:49:05', '2011-03-16 11:49:05', 0 , 0),";
		$sql.="('pt', 5, 4, 'Direct / espontânea','', '2011-03-16 11:49:19', '2011-03-16 11:49:19', 0 , 0),";
		$sql.="('pt', 6, 1, 'análise objetiva','', '2011-03-16 11:49:37', '2011-03-16 11:49:37', 0, 0),";
		$sql.="('pt', 6, 2, 'Análise de Risco','', '2011-03-16 11:50:12', '2011-03-16 11:50:22', 0 , 0),";
		$sql.="('pt', 6, 3, 'Análise de indivíduos','', '2011-03-16 11:50:41', '2011-03-16 11:50:41', 0 , 0),";
		$sql.="('pt', 7, 1, 'a profundidade conceitual','', '2011-03-16 11:51:27', '2011-03-16 11:51:27', 0, 0),";
		$sql.="('pt', 7, 2, 'Inovação e Soluções','', '2011-03-16 11:51:42', '2011-03-16 11:51:42', 0 , 0),";
		$sql.="('pt', 7, 3, 'previsão e planejamento','', '2011-03-16 11:51:55', '2011-03-16 11:51:55', 0 , 0),";
		$sql.="('pt', 8, 1, 'Adaptabilidade / Flexibilidade','', '2011-03-16 11:52:11', '2011-03-16 11:52:11', 0 , 0),";
		$sql.="('pt', 8, 2, 'mudar-Pro','', '2011-03-16 11:52:28', '2011-03-16 11:52:28', 0 , 0),";
		$sql.="('pt', 8, 3, 'Pragmatismo','', '2011-03-16 11:52:46', '2011-03-16 11:52:46', 0, 0),";
		$sql.="('pt', 9, 1, 'Ordem / Organização','', '2011-03-16 11:53:05', '2011-03-16 11:53:05', 0 , 0),";
		$sql.="('pt', 9, 2, 'Disciplina com as regras','', '2011-03-16 11:53:32', '2011-03-16 11:53:32', 0 , 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
*/		
	}

	function cargaEscaLasCompetenciasPrisma($sId){
		
		$oconn	= $this->conn;
		
		$id = $this->getIdPruebaPorLetra($sId);
		//Borramos / Cargamos los items de cada escala
		include('sqls/sqlEscalasItemsPrisma.php');
		
		//Borramos / Cargamos los items de cada competencia
		include('sqls/sqlCompetenciasItemsPrisma.php');
	}

	function cargaItemsInversosPrisma($sId){
/*
		$oconn	= $this->conn;
		
		$id = $this->getIdPruebaPorLetra($sId);
		
		$sql="";
		$sql = "DELETE FROM `items_inversos` where idprueba=" . $id . "";
		
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		
		$sql="";
		$sql="INSERT INTO `items_inversos` (`idPrueba`, `idItem`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="(24, 241, '2011-03-17 10:31:11', '2011-03-17 10:31:11', 0, 0),";
		$sql.="(24, 225, '2011-03-17 10:31:11', '2011-03-17 10:31:11', 0, 0),";
		$sql.="(24, 157, '2011-03-17 10:31:11', '2011-03-17 10:31:11', 0, 0),";
		$sql.="(24, 149, '2011-03-17 10:31:11', '2011-03-17 10:31:11', 0, 0),";
		$sql.="(24, 122, '2011-03-17 10:31:11', '2011-03-17 10:31:11', 0, 0),";
		$sql.="(24, 94, '2011-03-17 10:31:11', '2011-03-17 10:31:11', 0, 0),";
		$sql.="(24, 53, '2011-03-17 10:31:11', '2011-03-17 10:31:11', 0, 0),";
		$sql.="(24, 32, '2011-03-17 10:31:11', '2011-03-17 10:31:11', 0, 0),";
		$sql.="(24, 17, '2011-03-17 10:31:11', '2011-03-17 10:31:11', 0, 0);";
		
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
*/
	}
	
	function cargaAreas(){
/*
		$oconn	= $this->conn;
		
		$sql="";
		$sql = "DELETE FROM `areas` ";
		
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		
		$sql="";
		$sql.="INSERT INTO `areas` (`idArea`, `codIdiomaIso2`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="(5, 'es', 'Marketing', '2010-11-15 17:54:28', '2011-02-22 10:29:06', 1, 0),";
		$sql.="(6, 'es', 'Admin.-Contable', '2010-11-15 17:54:42', '2011-02-20 20:15:12', 1, 0),";
		$sql.="(7, 'es', 'Financiera', '2010-11-15 17:54:52', '2011-02-20 20:15:26', 1, 0),";
		$sql.="(8, 'es', 'Informática', '2010-11-15 17:55:02', '2011-02-25 10:22:31', 1, 0),";
		$sql.="(9, 'es', 'Gerencial', '2010-11-15 17:55:15', '2011-02-25 10:22:35', 1, 0),";
		$sql.="(10, 'es', 'General', '2010-11-15 17:55:27', '2011-02-25 10:22:28', 1, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql.="INSERT INTO `areas` (`idArea`, `codIdiomaIso2`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="(5, 'en', 'Marketing', '2010-11-15 17:54:28', '2011-02-22 10:29:06', 1, 0),";
		$sql.="(6, 'en', 'Admin.-Accounting', '2010-11-15 17:54:42', '2011-02-20 20:15:12', 1, 0),";
		$sql.="(7, 'en', 'Financial', '2010-11-15 17:54:52', '2011-02-20 20:15:26', 1, 0),";
		$sql.="(8, 'en', 'Computer', '2010-11-15 17:55:02', '2011-02-25 10:22:31', 1, 0),";
		$sql.="(9, 'en', 'Manager', '2010-11-15 17:55:15', '2011-02-25 10:22:35', 1, 0),";
		$sql.="(10, 'en', 'General', '2010-11-15 17:55:27', '2011-02-25 10:22:28', 1, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}		
		$sql="";
		$sql.="INSERT INTO `areas` (`idArea`, `codIdiomaIso2`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="(5, 'pt', 'Marketing', '2010-11-15 17:54:28', '2011-02-22 10:29:06', 1, 0),";
		$sql.="(6, 'pt', 'Admin. Contabilidade', '2010-11-15 17:54:42', '2011-02-20 20:15:12', 1, 0),";
		$sql.="(7, 'pt', 'Financeiro', '2010-11-15 17:54:52', '2011-02-20 20:15:26', 1, 0),";
		$sql.="(8, 'pt', 'Computador', '2010-11-15 17:55:02', '2011-02-25 10:22:31', 1, 0),";
		$sql.="(9, 'pt', 'Manager', '2010-11-15 17:55:15', '2011-02-25 10:22:35', 1, 0),";
		$sql.="(10, 'pt', 'Geral', '2010-11-15 17:55:27', '2011-02-25 10:22:28', 1, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$sql="";
		$sql.="INSERT INTO `areas` (`idArea`, `codIdiomaIso2`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
		$sql.="(5, 'ca', 'Màrqueting', '2010-11-15 17:54:28', '2011-02-22 10:29:06', 1, 0),";
		$sql.="(6, 'ca', 'Admin.-Comptable', '2010-11-15 17:54:42', '2011-02-20 20:15:12', 1, 0),";
		$sql.="(7, 'ca', 'Financera', '2010-11-15 17:54:52', '2011-02-20 20:15:26', 1, 0),";
		$sql.="(8, 'ca', 'Informàtica', '2010-11-15 17:55:02', '2011-02-25 10:22:31', 1, 0),";
		$sql.="(9, 'ca', 'Gerencial', '2010-11-15 17:55:15', '2011-02-25 10:22:35', 1, 0),";
		$sql.="(10, 'ca', 'General', '2010-11-15 17:55:27', '2011-02-25 10:22:28', 1, 0);";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
*/
	}
	function cargaBaremos($sIdPrueba){

		$oconn	= $this->conn;
		
		$sql="";
		switch ($sIdPrueba)
		{
			case 2:		//ceccasbega
				break;
			case 3:		//cec
				break;
			case 4:		//cpl16
				break;
			case 5:		//etoa
				break;
			case 7:		//cpl
				break;
			case 8:		//ELT_First
				break;
			case 10:	//tec
				break;
			case 11:	//toc
				break;
			case 12:	//cel16
				break;
			case 13:	//cml
				break;
			case 14:	//nips1
				break;
			case 16:	//nips
/*
				$sql = "DELETE FROM `baremos` WHERE idPrueba='" . $sIdPrueba . "'";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql="";
				$sql.="INSERT INTO `baremos` (`idBaremo`, `idPrueba`, `idBloque`, `idEscala`, `nombre`, `descripcion`, `observaciones`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
				$sql.="(1, 16, 0, 0, 'Baremo Estándar', 'Este baremo será el estandar aplicado a la prueba', '', '2011-02-08 08:24:08', '2011-02-08 08:24:08', 0, 0);";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
*/
				break;
			case 17:	//icd
				break;
			case 18:	//cpl32
				break;
			case 19:	//pdp
				break;
			case 20:	//tic
				break;
			case 21:	//tac
				break;
			case 23:	//vips1
				break;
			case 24:	//prisma
/*
				$sql = "DELETE FROM `baremos` WHERE idPrueba='" . $sIdPrueba . "'";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql="";
				$sql.="INSERT INTO `baremos` (`idBaremo`, `idPrueba`, `idBloque`, `idEscala`, `nombre`, `descripcion`, `observaciones`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
				$sql.="(1, 24, 1, 1, 'Baremo Estándar', 'Este baremo será el estandar aplicado a la prueba', '', '2011-03-21 17:58:30', '2011-03-21 17:58:30', 0, 0);";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
*/
				break;
			case 25:	//serc
				break;
			case 26:	//vips
/*
				$sql = "DELETE FROM `baremos` WHERE idPrueba='" . $sIdPrueba . "'";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
				$sql="";
				$sql.="INSERT INTO `baremos` (`idBaremo`, `idPrueba`, `idBloque`, `idEscala`, `nombre`, `descripcion`, `observaciones`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
				$sql.="(1, " . $sIdPrueba . ", 0, 0, 'Baremo Estándar', 'Este baremo será el estandar aplicado a la prueba', '', '2011-02-07 16:50:30', '2011-02-07 16:50:30', 0, 0);";
				if($oconn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo(constant("ERR"));
					exit;
				}
*/				
				break;
			case 27:	//icdd
				break;
			default:
				break;
		} // end switch
	}

	function borrarCandidatos(){
		
//		$this->vaciarTabla("candidatos_back");
//		$this->vaciarTabla("candidatos");
	}
	
	function cargaCorreosBase(){
		$oconn	= $this->conn;
		//Borramos los corros creados y cargamos los base
		include('sqls/sqlCorreosBase.php');
	}
	
	function cargaCorreosLiterales(){
		$oconn	= $this->conn;
		include('sqls/sqlCorreosLiterales.php');
	}
	function borrarCorreosProceso(){
		
//		$this->vaciarTabla("correos_proceso");
	}
	function borrarDescargasInformes(){
//		$this->vaciarTabla("descargas_informes");
	}
	function cargaEdades(){
		$oconn	= $this->conn;
		include('sqls/sqlEdades.php');
	}
	function cargaEjemplosPruebas(){
		$oconn	= $this->conn;
		include('sqls/sqlEjemplosPruebas.php');
	}
	function borrarEmpresas_accesos(){
//		$this->vaciarTabla("empresas_accesos");
//		$this->vaciarTabla("ficheros_carga");
//		$this->vaciarTabla("peticiones_dongles");
//		$this->vaciarTabla("proceso_baremos");
//		$this->vaciarTabla("proceso_pruebas");
//		$this->vaciarTabla("respuestas_pruebas_items");
//		$this->vaciarTabla("respuestas_pruebas");
//		$this->vaciarTabla("envios");
//		$this->vaciarTabla("wi_historico_cambios");
//		$this->vaciarTabla("proceso_informes");
//		$this->vaciarTabla("procesos");
	}
	function cargaFormaciones(){
		$oconn	= $this->conn;
		include('sqls/sqlFormaciones.php');
	}
	function cargaInformes_pruebas(){
		$oconn	= $this->conn;
		include('sqls/sqlInformes_pruebas.php');
	}
	function cargaInstrucciones_pruebas(){
		$oconn	= $this->conn;
/*
		$sql = "DELETE FROM `instrucciones_pruebas` WHERE idPrueba IN(16,24,26);"; //nips, prisma,vips
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$DescriptorFichero = fopen(constant("DIR_FS_DOCUMENT_ROOT") . "sqls/sqlInstrucciones_pruebas.sql","r");
		$content=""; 
		while(!feof($DescriptorFichero)){ 
	    	$content .= fgets($DescriptorFichero,4096);
		}
		if (!empty($content)){
			if (!$this->cargaMySQL($content)){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [cargaInstrucciones_pruebas][ProcesoTablas]::cargaMySQL()";
				error_log($sTypeError . " ->\t" . $content . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
		}
*/
	}
	function cargaNivelesJerarquicos(){
		$oconn	= $this->conn;
		include('sqls/sqlNivelesJerarquicos.php');
	}
	function cargaNotificaciones(){
		$oconn	= $this->conn;
		include('sqls/sqlNotificaciones.php');
	}
	function cargaOpciones_ejemplos(){
		$oconn	= $this->conn;
		include('sqls/sqlOpciones_ejemplos.php');
	}
	function cargaSexos(){
		$oconn	= $this->conn;
		include('sqls/sqlSexos.php');
	}

	function cargaEjemplos_ayudas(){
/*
		$oconn	= $this->conn;
		$sql = "DELETE FROM `ejemplos_ayudas` WHERE idPrueba IN(16,26);"; //nips, vips
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$DescriptorFichero = fopen(constant("DIR_FS_DOCUMENT_ROOT") . "sqls/ejemplos_ayudas.sql","r");
		$content=""; 
		while(!feof($DescriptorFichero)){ 
	    	$content .= fgets($DescriptorFichero,4096);
		}
		if (!empty($content)){
			if (!$this->cargaMySQL($content)){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [cargaEjemplos_ayudas][ProcesoTablas]::cargaMySQL()";
				error_log($sTypeError . " ->\t" . $content . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
		}
*/
	}
	function cargaPruebas_ayudas(){
/*
		$oconn	= $this->conn;
		$sql = "DELETE FROM `pruebas_ayudas` WHERE idPrueba IN(16,24,26);"; //nips, prisma,vips
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$DescriptorFichero = fopen(constant("DIR_FS_DOCUMENT_ROOT") . "sqls/pruebas_ayudas.sql","r");
		$content=""; 
		while(!feof($DescriptorFichero)){ 
	    	$content .= fgets($DescriptorFichero,4096);
		}
		if (!empty($content)){
			if (!$this->cargaMySQL($content)){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [cargaPruebas_ayudas][ProcesoTablas]::cargaMySQL()";
				error_log($sTypeError . " ->\t" . $content . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
		}
*/
	}
	function actualizaItems_Nips(){
		$oconn	= $this->conn;
		include('sqls/actualizaItemsNips.php');
	}
	function cargaTablasInformes(){
		$oconn	= $this->conn;
		include('sqls/creaTablasInformes.php');
	}
	function cargaSignos(){
		$oconn	= $this->conn;
		include('sqls/creasignos.php');
	}
	function cargaSeccionesInformes(){
		$oconn	= $this->conn;
		include('sqls/creaSecciones.php');
	}
	function cargaTextosSecciones(){
		$oconn	= $this->conn;
		include('sqls/cargaTextosSecciones.php');
	}
	function cargaRangosIr(){
		$oconn	= $this->conn;
		include('sqls/cargaRangosIr.php');
	}
	function cargaRangosIp(){
		$oconn	= $this->conn;
		include('sqls/cargaRangosIp.php');
	}
	function cargaRangosTextos(){
		$oconn	= $this->conn;
		include('sqls/cargaRangosTextos.php');
	}
	function cargaEmk_charsets(){
/*
		$oconn	= $this->conn;
		$sql = "DELETE FROM `emk_charsets`;";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$DescriptorFichero = fopen(constant("DIR_FS_DOCUMENT_ROOT") . "sqls/emk_charsets.sql","r");
		$content=""; 
		while(!feof($DescriptorFichero)){ 
	    	$content .= fgets($DescriptorFichero,4096);
		}
		if (!empty($content)){
			if (!$this->cargaMySQL($content)){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [cargaEmk_charsets][ProcesoTablas]::cargaMySQL()";
				error_log($sTypeError . " ->\t" . $content . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
		}
*/ 
	}
	function cargaModo_realizacion(){
/*
		$oconn	= $this->conn;
		$sql = "DELETE FROM `modo_realizacion`;";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$DescriptorFichero = fopen(constant("DIR_FS_DOCUMENT_ROOT") . "sqls/modo_realizacion.sql","r");
		$content=""; 
		while(!feof($DescriptorFichero)){ 
	    	$content .= fgets($DescriptorFichero,4096);
		}
		if (!empty($content)){
			if (!$this->cargaMySQL($content)){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [cargaModo_realizacion][ProcesoTablas]::cargaMySQL()";
				error_log($sTypeError . " ->\t" . $content . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
		}
*/ 
	}
	function cargaBaremosResultados(){
/*
		$oconn	= $this->conn;
		$sql = "DELETE FROM `baremos_resultados`;";
		if($oconn->Execute($sql) === false){
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			echo(constant("ERR"));
			exit;
		}
		$DescriptorFichero = fopen(constant("DIR_FS_DOCUMENT_ROOT") . "sqls/baremos_resultados.sql","r");
		$content=""; 
		while(!feof($DescriptorFichero)){ 
	    	$content .= fgets($DescriptorFichero,4096);
		}
		if (!empty($content)){
			if (!$this->cargaMySQL($content)){
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [cargaBaremosResultados][ProcesoTablas]::cargaMySQL()";
				error_log($sTypeError . " ->\t" . $content . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				echo(constant("ERR"));
				exit;
			}
		}
*/ 
	}
	
	
	
	
	
	
	
///////////////////////////////////////////////////////////////////////////////////////////////////////	
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
	
	function ver_errores($type=1)
	{
		$msg_string = "";
		foreach ($this->msg_Error as $value)
		{
			$msg_string .= $value;
			switch($type)
			{
				case 1:
					$msg_string .= "\\n";
					break;
				case 2:
					$msg_string .= "<br />";
					break;
				case 3:
					$msg_string .= "\n";
					break;
				default:
					$msg_string .= "\\n";
			}
		}
		return $msg_string;
	}
	
}//Fin de la Clase ProcesoTablas
?> 