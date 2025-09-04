<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla imp_pruebas_papel
**/
class Imp_pruebas_papelDB
{
	
	/**
	* Declaración de las variables de Entidad.
	**/
		var $conn; //Conexión con la BBDD

		var $sSQL; //Última query ejecutada

		var $msg_Error; //Array con los mensajes de Warning y Errores

	/**
	* Crea un objeto de la clase y almacena en él la 
	* conexión que utilizará con la base de datos
	* @param conn Conexion a traves de la cual
	* realizar las operaciones sobre la base de datos
	**/
	function __construct(&$conn)
	{
		$this->conn			= $conn;
		$this->msg_Error			= array();
	}

	/***********************************************************************
	* Inserta una entidad en la base de datos.
	* @param entidad Entidad a insertar con Datos
	* @return long Numero de ID de la entidad
	* @exception Exception Error al ejecutar la acción
	*  en la base de datos
	***********************************************************************/
	function insertar($cEntidad)
	{
		$aux			= $this->conn;
	
		$newId = $this->getSiguienteId($cEntidad);
	
		require_once(constant("DIR_WS_COM") . "Upload.php");
		$img0 = new Upload("fFichero");
		$sDirImg="imgImp_pruebas_papel";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if (!$img0->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img0->get_errores();
			return 0;
		}else{
			$cEntidad->setFichero($img0->getPath_WS());
		}
		$cEntidad->setIdFicheroSabana($newId);
		$sql = "INSERT INTO imp_pruebas_papel (";
		$sql .= "idFicheroSabana" . ",";
		$sql .= "idPrueba" . ",";
		$sql .= "idEmpresa" . ",";
		$sql .= "proceso" . ",";
		$sql .= "fichero" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($newId, false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdPrueba(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdEmpresa(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getProceso(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getFichero(), false) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Imp_pruebas_papelDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			return 0;
		}
		else{
			require_once(constant("DIR_WS_COM") . "Historico_cambios/Historico_cambiosDB.php");
			require_once(constant("DIR_WS_COM") . "Historico_cambios/Historico_cambios.php");
			$cEntidadHistorico_cambiosDB	= new Historico_cambiosDB($aux);  // Entidad DB Historico_cambios
			$cEntidadHistorico_cambios		= new Historico_cambios();  // Entidad Historico_cambios
			require_once(constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
			require_once(constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
			$_cEntidadUsuariosDB	= new UsuariosDB($aux);
			$_cEntidadUsuarios		= new Usuarios();
			$_cEntidadUsuarios->setIdUsuario($cEntidad->getUsuAlta());
			$_cEntidadUsuariosDB->readEntidad($_cEntidadUsuarios);
			$cEntidadHistorico_cambios->setFuncionalidad(basename($_SERVER['PHP_SELF']));
			$cEntidadHistorico_cambios->setModo(constant("MNT_ALTA"));
			$cEntidadHistorico_cambios->setQuery($sql);
			$cEntidadHistorico_cambios->setIp($_SERVER['REMOTE_ADDR']);
			$cEntidadHistorico_cambios->setIdUsuario($_cEntidadUsuarios->getIdUsuario());
			$cEntidadHistorico_cambios->setIdUsuarioTipo($_cEntidadUsuarios->getIdUsuarioTipo());
			$cEntidadHistorico_cambios->setLogin($_cEntidadUsuarios->getLogin());
			$cEntidadHistorico_cambios->setNombre($_cEntidadUsuarios->getNombre());
			$cEntidadHistorico_cambios->setApellido1($_cEntidadUsuarios->getApellido1());
			$cEntidadHistorico_cambios->setApellido2($_cEntidadUsuarios->getApellido2());
			$cEntidadHistorico_cambios->setEmail($_cEntidadUsuarios->getEmail());
			$cEntidadHistorico_cambios->setUsuAlta($cEntidad->getUsuAlta());
			$cEntidadHistorico_cambios->setUsuMod($cEntidad->getUsuMod());
			$HistId	= $cEntidadHistorico_cambiosDB->insertar($cEntidadHistorico_cambios);
			if (empty($HistId)){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error [GUARDANDO HISTORICO][" . constant("MNT_ALTA") . "][UsuariosDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				header('Location: ' . constant("HTTP_SERVER") . 'index.php');
				exit;
			}else{
				if (!$this->importar($img0, $cEntidad)){
					return 0;
				}else{
//					$this->seProcedure($cEntidad);
				}
			}
		}
		
		return $newId;
	}

	/******************************************************************************************
	* Devuelve una cadena para insertar un valor de 
	* secuencia clave de tipo ID.
	* @return String nuevo id.
	*****************************************************************************************/
	function getSiguienteId($cEntidad)
	{
		$aux			= $this->conn;
	
		$sql  = "SELECT MAX(idFicheroSabana) AS Max FROM imp_pruebas_papel ";
		$sql  .="";
		$rs = $aux->Execute($sql);
		$newId=0;
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][Imp_pruebas_papelDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		
		}
		return $newId;
	}

	/*************************************************************************
	* Modifica una entidad en la base de datos.
	* @param entidad Entidad a modificar con Datos
	* @return boolean Estado de la modificación
	* @exception Exception Error al ejecutar la acción
	*  en la base de datos
	************************************************************************/
	function modificar($cEntidad)
	{
		$aux			= $this->conn;
		require_once(constant("DIR_WS_COM") . "Upload.php");
		$img0 = new Upload("fFichero");
		$sDirImg="imgImp_pruebas_papel";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if (!$img0->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img0->get_errores();
			return false;
		}else{
			$cEntidad->setFichero($img0->getPath_WS());
		}
	
		$sql = "UPDATE imp_pruebas_papel SET ";
		$sql .= "idFicheroSabana=" . $aux->qstr($cEntidad->getIdFicheroSabana(), false) . ", ";
		$sql .= "idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . ", ";
		$sql .= "idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ", ";
		$sql .= "proceso=" . $aux->qstr($cEntidad->getProceso(), false) . ", ";
		
		if ($cEntidad->getFichero() != "")
			$sql .= "fichero=" . $aux->qstr($cEntidad->getFichero(), false) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idFicheroSabana=" . $aux->qstr($cEntidad->getIdFicheroSabana(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][Imp_pruebas_papelDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
		else{
			require_once(constant("DIR_WS_COM") . "Historico_cambios/Historico_cambiosDB.php");
			require_once(constant("DIR_WS_COM") . "Historico_cambios/Historico_cambios.php");
			$cEntidadHistorico_cambiosDB	= new Historico_cambiosDB($aux);  // Entidad DB Historico_cambios
			$cEntidadHistorico_cambios		= new Historico_cambios();  // Entidad Historico_cambios
			require_once(constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
			require_once(constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
			$_cEntidadUsuariosDB	= new UsuariosDB($aux);
			$_cEntidadUsuarios		= new Usuarios();
			$_cEntidadUsuarios->setIdUsuario($cEntidad->getUsuAlta());
			$_cEntidadUsuariosDB->readEntidad($_cEntidadUsuarios);
			$cEntidadHistorico_cambios->setFuncionalidad(basename($_SERVER['PHP_SELF']));
			$cEntidadHistorico_cambios->setModo(constant("MNT_ALTA"));
			$cEntidadHistorico_cambios->setQuery($sql);
			$cEntidadHistorico_cambios->setIp($_SERVER['REMOTE_ADDR']);
			$cEntidadHistorico_cambios->setIdUsuario($_cEntidadUsuarios->getIdUsuario());
			$cEntidadHistorico_cambios->setIdUsuarioTipo($_cEntidadUsuarios->getIdUsuarioTipo());
			$cEntidadHistorico_cambios->setLogin($_cEntidadUsuarios->getLogin());
			$cEntidadHistorico_cambios->setNombre($_cEntidadUsuarios->getNombre());
			$cEntidadHistorico_cambios->setApellido1($_cEntidadUsuarios->getApellido1());
			$cEntidadHistorico_cambios->setApellido2($_cEntidadUsuarios->getApellido2());
			$cEntidadHistorico_cambios->setEmail($_cEntidadUsuarios->getEmail());
			$cEntidadHistorico_cambios->setUsuAlta($cEntidad->getUsuAlta());
			$cEntidadHistorico_cambios->setUsuMod($cEntidad->getUsuMod());
			$HistId	= $cEntidadHistorico_cambiosDB->insertar($cEntidadHistorico_cambios);
			if (empty($HistId)){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error [GUARDANDO HISTORICO][" . constant("MNT_ALTA") . "][UsuariosDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				header('Location: ' . constant("HTTP_SERVER") . 'index.php');
				exit;
			}
		}
		
		return $retorno;
	}

	/*************************************************************************
	* Borra una entidad en la base de datos.
	* @param entidad Entidad a borrar contiene los datos de condición
	* @return boolean Estado del borrado
	* @exception Exception Error al ejecutar la acción
	*  en la base de datos
	************************************************************************/
	function borrar($cEntidad)
	{
		$aux			= $this->conn;
		$this->msg_Error			= array();
		$and			= false;
		$retorno			= true;
	
		if ($retorno){
			$sSQL = 'DELETE FROM `pruebas_papel` WHERE carga=' . $cEntidad->getIdFicheroSabana();
			if($aux->Execute($sSQL) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][Imp_pruebas_papelDB][Pruebas_papel]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}else{
				//Borramos el registro de la Entidad.
				$sql  ="DELETE FROM imp_pruebas_papel ";
				$sql  .="WHERE ";
				if ($cEntidad->getIdFicheroSabana() != ""){
					$sql .= $this->getSQLAnd($and);
					$and = true;
					$sql  .="idFicheroSabana=" . $aux->qstr($cEntidad->getIdFicheroSabana(), false) . " ";
				}
				if($aux->Execute($sql) === false){
					$retorno=false;
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][Imp_pruebas_papelDB]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				}
			}
		}else $retorno=false;
		if ($retorno){
			require_once(constant("DIR_WS_COM") . "Historico_cambios/Historico_cambiosDB.php");
			require_once(constant("DIR_WS_COM") . "Historico_cambios/Historico_cambios.php");
			$cEntidadHistorico_cambiosDB	= new Historico_cambiosDB($aux);  // Entidad DB Historico_cambios
			$cEntidadHistorico_cambios		= new Historico_cambios();  // Entidad Historico_cambios
			require_once(constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
			require_once(constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
			$_cEntidadUsuariosDB	= new UsuariosDB($aux);
			$_cEntidadUsuarios		= new Usuarios();
			$_cEntidadUsuarios->setIdUsuario($cEntidad->getUsuAlta());
			$_cEntidadUsuariosDB->readEntidad($_cEntidadUsuarios);
			$cEntidadHistorico_cambios->setFuncionalidad(basename($_SERVER['PHP_SELF']));
			$cEntidadHistorico_cambios->setModo(constant("MNT_ALTA"));
			$cEntidadHistorico_cambios->setQuery($sql);
			$cEntidadHistorico_cambios->setIp($_SERVER['REMOTE_ADDR']);
			$cEntidadHistorico_cambios->setIdUsuario($_cEntidadUsuarios->getIdUsuario());
			$cEntidadHistorico_cambios->setIdUsuarioTipo($_cEntidadUsuarios->getIdUsuarioTipo());
			$cEntidadHistorico_cambios->setLogin($_cEntidadUsuarios->getLogin());
			$cEntidadHistorico_cambios->setNombre($_cEntidadUsuarios->getNombre());
			$cEntidadHistorico_cambios->setApellido1($_cEntidadUsuarios->getApellido1());
			$cEntidadHistorico_cambios->setApellido2($_cEntidadUsuarios->getApellido2());
			$cEntidadHistorico_cambios->setEmail($_cEntidadUsuarios->getEmail());
			$cEntidadHistorico_cambios->setUsuAlta($cEntidad->getUsuAlta());
			$cEntidadHistorico_cambios->setUsuMod($cEntidad->getUsuMod());
			$HistId	= $cEntidadHistorico_cambiosDB->insertar($cEntidadHistorico_cambios);
			if (empty($HistId)){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error [GUARDANDO HISTORICO][" . constant("MNT_ALTA") . "][UsuariosDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				header('Location: ' . constant("HTTP_SERVER") . 'index.php');
				exit;
			}
		}
		
		return $retorno;
	}

	/*************************************************************************
	* Consulta en la base de datos recogiendo la información
	* recibida por la entidad, esta forma de consultar genera
	* un <b>solo</b> registro conteniendo la información
	* de la entidad recibida. Este metodo se utiliza para efectuar
	* consultas concretas de un solo registro.
	* @param entidad Entidad con la información basica a consultar
	* @exception Exception Error al ejecutar la acción
	*  en la base de datos
	* @return cEntidad con la información recuperada.
	*************************************************************************/
	function readEntidad($cEntidad)
	{
		$aux			= $this->conn;
	
		$sql = "SELECT *  FROM imp_pruebas_papel WHERE ";
		$sql  .="idFicheroSabana=" . $aux->qstr($cEntidad->getIdFicheroSabana(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdFicheroSabana($arr['idFicheroSabana']);
					$cEntidad->setIdPrueba($arr['idPrueba']);
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setProceso($arr['proceso']);
					$cEntidad->setFichero($arr['fichero']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][Imp_pruebas_papelDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		
		}
		return $cEntidad;
	}

	/*************************************************************************
	* Lista en la base de datos recogiendo la información
	* recibida por la entidad, Este metodo se utiliza para búsquedas
	* de uno o varios registros.
	* @param entidad Entidad con la información de la Búsqueda
	* @return Vector Lista recuperada
	*************************************************************************/
	function readLista($cEntidad)
	{
		$aux			= $this->conn;
	
		$sql="";
		$and = false;
		$sql.="SELECT * FROM imp_pruebas_papel ";
		if ($cEntidad->getIdFicheroSabana() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idFicheroSabana=" . $aux->qstr($cEntidad->getIdFicheroSabana(), false);
		}
		if ($cEntidad->getIdPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false);
		}
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false);
		}
		if ($cEntidad->getProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(proceso) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getProceso() . "%") . ")";
		}
		if ($cEntidad->getFichero() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(fichero) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getFichero() . "%") . ")";
		}
		if ($cEntidad->getFecAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta>=" . $aux->qstr($cEntidad->getFecAlta(), false);
		}
		if ($cEntidad->getFecAltaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta<=" . $aux->qstr($cEntidad->getFecAltaHast(), false);
		}
		if ($cEntidad->getFecMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod>=" . $aux->qstr($cEntidad->getFecMod(), false);
		}
		if ($cEntidad->getFecModHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod<=" . $aux->qstr($cEntidad->getFecModHast(), false);
		}
		if ($cEntidad->getUsuAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="usuAlta=" . $aux->qstr($cEntidad->getUsuAlta(), false);
		}
		if ($cEntidad->getUsuMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false);
		}
		if ($cEntidad->getOrderBy() != ""){
			$sql .=" ORDER BY " . $cEntidad->getOrderBy();
			if ($cEntidad->getOrder() != ""){
				$sql .=" " . $cEntidad->getOrder();
			}
		}
		$this->sSQL=$sql;
		return $sql;
	}

	function getSQLWhere($bFlag)
	{
		if (!$bFlag)	return " WHERE ";
		else	return " AND ";
	}

	function getSQLAnd($bFlag)
	{
		if ($bFlag)	return " AND ";
		else	return " ";
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

	function quitaImagen($cEntidad)
	{
		$aux			= $this->conn;
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");

		require_once(constant("DIR_WS_COM") . "Imp_pruebas_papel/Imp_pruebas_papel.php");
		$cEntidadAnterior	= new Imp_pruebas_papel();
		$cEntidadAnterior->setIdFicheroSabana($cEntidad->getIdFicheroSabana());
		$cEntidadAnterior = $this->readEntidad($cEntidadAnterior);

		$sql = "UPDATE imp_pruebas_papel SET  ";
		if (strtolower($cEntidad->getFichero()) == "on"){
			$sql .= "fichero='', ";
		}
		$sql = substr($sql,0,strlen($sql)-2);
		$sql .= " WHERE ";
		$sql .="idFicheroSabana=" . $aux->qstr($cEntidad->getIdFicheroSabana(), false) . " ";

		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][Imp_pruebas_papelDB::quitaImagen()]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}else{
			if (strtolower($cEntidad->getFichero()) == "on"){
				$cEntidad->setFichero('');
				@unlink($spath . $cEntidadAnterior->getFichero());
				//Borramos la miñatura
				$name= basename($cEntidadAnterior->getFichero());
				$namePath=str_replace($name,"m" . $name, $cEntidadAnterior->getFichero());
				@unlink($spath . $namePath);
			}
		}
		return $retorno;
	}
	
	function importar(&$oImg, $cEntidad)
	{
		$aux			= $this->conn;
		require_once(constant("DIR_WS_COM") . "Utilidades.php");
		$cUtilidades = new Utilidades();
		
		$retorno		= 1;
		$bMagic = true;
		//Vamos a pasar los datos de un fichero con lo que no tiene el escapado automatico de PHP y hay que escaparlo
		if (false){
			$bMagic = false;
		}
		$sDelimitador = ";";
		$src_type = $oImg->src_type;
		switch ($src_type)
		{
			case "text/comma-separated-values":
			case "text/csv":
			case "application/octet-stream":
			case "text/plain":
			case "application/vnd.ms-excel":
				
				$sEncerrado = "\"";
				
				$fp = @fopen($oImg->getPath_WS(),"r");
				
				if (!$fp) {
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . " Error NO se encuentra el fichero [" . $oImg->getPath_WS() . "]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					return 0;
				}
				$aSRCCabecera;
				$iSRCColumnas = 0;
				$i = 0;
				while (($data = fgetcsv($fp, 32000, $sDelimitador, $sEncerrado )) !== FALSE)
				{
					if ($i == 0)
					{
						$aSRCCabecera	=	$data;
						$iSRCColumnas = sizeof($aSRCCabecera);
					}else{
						break;
					}
					$i++;
				}
				fclose($fp);
				
				//Plantillas para confirmación del fichero
				$aCabecera = $this->getCabFile();
				$iColumnas = sizeof($aCabecera);
				//////////////////////////////////////////
				
				if ($iSRCColumnas != $iColumnas){
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . " Error columnas [Tiene: " . $iSRCColumnas  . "][Se espera: " . $iColumnas . "]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					return 0;
				}else{
					$aCab=array_keys($aCabecera);
					for ($i=0; $i < $iColumnas; $i++){
						if (trim($aSRCCabecera[$i]) != trim($aCab[$i]))
						{
							$this->msg_Error	= array();
							$sTypeError	=	date('d/m/Y H:i:s') . " Error Formato columnas [Se espera: " . $aCab[$i] . " en columna " . ($i+1) . "][No " . $aSRCCabecera[$i] . "]";
							$this->msg_Error[]	= $sTypeError;
							error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
							return 0;
						}
					}
				}
				$retorno = is_file(constant("DIR_WS_COM") . "Pruebas_papel/Pruebas_papelDB.php");
				if (!$retorno){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error Class Not Found [" . constant("MNT_ALTA") . "][Imp_pruebas_papelDB::Pruebas_papelDB]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " ->	" . $sSQL . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					return 0;
				}
				if ($retorno){
					require_once(constant("DIR_WS_COM") . "Pruebas_papel/Pruebas_papelDB.php");
					require_once(constant("DIR_WS_COM") . "Pruebas_papel/Pruebas_papel.php");
					$cPruebas_papelDB	= new Pruebas_papelDB($aux);  // Entidad DB
					$cPruebas_papel	= new Pruebas_papel();  // Entidad
					
					$fp = @fopen($oImg->getPath_WS(),"r");
					
					if (!$fp) {
						$this->msg_Error	= array();
						$sTypeError	=	date('d/m/Y H:i:s') . " Error NO se encuentra el fichero [" . $oImg->getPath_WS() . "]";
						$this->msg_Error[]	= $sTypeError;
						error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						return 0;
					}
					$sSQL = 'DELETE FROM `pruebas_papel` WHERE carga=' . $cEntidad->getIdFicheroSabana();
					if($aux->Execute($sSQL) === false){
						echo("<br>Error SQL:<br>" . $sSQL);
						exit;
					}
					set_time_limit(0);
					$iCont = 0;
					$i = 0;
					$sSQLPP="";
					while (($data = fgetcsv($fp, 32000, $sDelimitador, $sEncerrado )) !== FALSE)
					{
						if ($i > 0)
						{
							if(strlen($data[0]) > 0)
							{
								for($j=0; $j < $iColumnas; $j++)
								{
									if($aCabecera[$aSRCCabecera[$j]] != "")
									{
					   					$funcionSet = "set" . $aCabecera[$aSRCCabecera[$j]];
   										$cPruebas_papel->$funcionSet($data[$j]);
					   				}
								}
								$cPruebas_papelDB->bMagic = $bMagic;
								$cPruebas_papel->setCarga($cEntidad->getIdFicheroSabana());
								$cPruebas_papel->setUsuAlta($cEntidad->getUsuAlta());
								$cPruebas_papel->setUsuMod($cEntidad->getUsuMod());
								$sSQLPP .= $cPruebas_papelDB->getInsertar($cPruebas_papel, $iCont);
								$iCont++;
							}
						}
						$i++;
					}
					if (empty($iCont)){
						$this->msg_Error	= array();
						$sTypeError	=	date('d/m/Y H:i:s') . " Error NO hay datos que cargar [" . constant("MNT_ALTA") . "][[Imp_pruebas_papelDB][Pruebas_papelDB]";
						$this->msg_Error[]	= $sTypeError;
						error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						$retorno=0;
					}else{
//						echo $sSQLPP;exit;
						$sSQLPP = substr($sSQLPP, 0, -1);
						$sSQLPP .=";";
						if($aux->Execute($sSQLPP) === false){
							$this->msg_Error	= array();
							$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Imp_pruebas_papelDB][" . $aux->ErrorMsg() . "]";
							$this->msg_Error[]	= $sTypeError;
							error_log($sTypeError . " ->\t" . $sSQLPP . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
							return 0;
						}
					}
					fclose($fp);
					
					//Validamos los datos cargados
					$cPruebas_papel	= new Pruebas_papel();  // Entidad
					$cPruebas_papel->setCarga($cEntidad->getIdFicheroSabana());
					$sSQL_Valida = $cPruebas_papelDB->readLista($cPruebas_papel);
					$rsPP = $aux->Execute($sSQL_Valida);
					switch ($cEntidad->getIdPrueba())
					{
						case 24:	//Prisma
							$sMalContestadas="";
							$sBienContestadas="";
							$iBien=0;
							$iMal=0;
							if($rsPP->recordCount() > 0){
								
								while(!$rsPP->EOF){
									$sCadena = $rsPP->fields['RESULTADO'];
									$sLCadena = strlen(str_replace(" ", "", $sCadena));
									$codigo = $rsPP->fields['CODIGO'];
									
									// Si la cadena contiene algún espacio quiere decir que han dejado preguntas sin responder
									$aConEspacios = explode(" " , $sCadena);
									//Si la cadena contiene algún interrogante quiere decir que han respondido en la misma pregunta la misma mejor y peor.
									$aConInterrogantes = explode("?" , $sCadena);
									
									if ($sLCadena < 192){
										$sTypeError	=	" Error Faltan respuestas EN REGISTRO CON CÓDIGO:: " . $rsPP->fields['CODIGO'];
										$this->msg_Error[]	= $sTypeError;
										
									}
									if (sizeof($aConEspacios) > 1){
										$sTypeError	=	" Error Tiene espacios en blanco EN REGISTRO CON CÓDIGO:: " . $rsPP->fields['CODIGO'];
										$this->msg_Error[]	= $sTypeError;
									}
									if (sizeof($aConInterrogantes) > 1){
										$sTypeError	=	" Error Tiene interrogantes EN REGISTRO CON CÓDIGO:: " . $rsPP->fields['CODIGO'];
										$this->msg_Error[]	= $sTypeError;
										
									}
									if(($sLCadena < 192) || (sizeof($aConEspacios) > 1) || (sizeof($aConInterrogantes) > 1)){
										$sMalContestadas .= ", " . $rsPP->fields['CODIGO'];
										$retorno=0;
										$iMal++;
									}else{
										$sBienContestadas .= ", " . $rsPP->fields['CODIGO'];
										$iBien++;
									}
									$rsPP->MoveNext();	
								}
							}
							if (!$retorno){
								//Borramos el fichero cargado y los datos de pruebas_papel
								$sSQL = 'DELETE FROM `pruebas_papel` WHERE carga=' . $cEntidad->getIdFicheroSabana();
								$aux->Execute($sSQL);
		
								$sql  ="DELETE FROM imp_pruebas_papel ";
								$sql  .="WHERE ";
								$sql  .="idFicheroSabana=" . $aux->qstr($cEntidad->getIdFicheroSabana(), false) . " ";
								$aux->Execute($sql);
							}else{
								$sCodIdiomaIso2 = 'es';
								$sCodIdiomaInforme = 'es';
								$sIdBaremo = 0;
								$sIdModoRealizacion = 2; //Administrado
								//Tratamos los datos a insertar
								//1º Insertamos el proceso
								require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
								require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
								$cProcesosDB	= new ProcesosDB($aux);  // Entidad DB
								$cProcesos	= new Procesos();  // Entidad
								$cProcesos->setIdEmpresa($cEntidad->getIdEmpresa());
								$cProcesos->setNombre($cEntidad->getProceso());
								$cProcesos->setDescripcion("Importados candidatos fichero de carga [" . basename($oImg->getPath_WS()) . "][" . date('d/m/Y H:i:s') . "]");
								$date = date('Y-m-d H:i:s');
								$cProcesos->setFechaInicio($date);
								$date = date('Y-m-d 23:59:59', strtotime('+1 week'));
								$cProcesos->setFechaFin($date);
								$cProcesos->setIdModoRealizacion($sIdModoRealizacion);	
								$idProceso = $cProcesosDB->insertar($cProcesos);
								if (!empty($idProceso))
								{
									$arrayGeneraInformes = array();
									//Asignamos la prueba
									require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
									require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
									$cProcesoPruebasDB	= new Proceso_pruebasDB($aux);  // Entidad DB
									$cProcesoPruebas	= new Proceso_pruebas();  // Entidad
										
									$cProcesoPruebas->setIdProceso($idProceso);
									$cProcesoPruebas->setIdEmpresa($cEntidad->getIdEmpresa());
									$cProcesoPruebas->setIdPrueba($cEntidad->getIdPrueba());
									$cProcesoPruebas->setCodIdiomaIso2($sCodIdiomaIso2);
									
									require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
									require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");					
									$cProcesoBaremosDB = new Proceso_baremosDB($aux);  // Entidad
									$cProcesoBaremos = new Proceso_baremos();  // Entidad
									$cProcesoBaremos->setIdProceso($idProceso);
									$cProcesoBaremos->setIdEmpresa($cEntidad->getIdEmpresa());
									$cProcesoBaremos->setIdPrueba($cEntidad->getIdPrueba());
									$cProcesoBaremos->setIdBaremo($sIdBaremo);
									$cProcesoBaremos->setCodIdiomaIso2($sCodIdiomaIso2);

									require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
									require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");					
									$cProcesoInformesDB = new Proceso_informesDB($aux);  // Entidad
									$cProcesoInformes = new Proceso_informes();  // Entidad
									$cProcesoInformes->setIdProceso($idProceso);
									$cProcesoInformes->setIdEmpresa($cEntidad->getIdEmpresa());
									$cProcesoInformes->setIdPrueba($cEntidad->getIdPrueba());
									$cProcesoInformes->setIdBaremo($sIdBaremo);
									$cProcesoInformes->setCodIdiomaIso2($sCodIdiomaIso2);
									$cProcesoInformes->setCodIdiomaInforme($sCodIdiomaInforme);
									$cProcesoInformes->setIdTipoInforme(16);	//Perfil Básico + Perfil General (prisma)
																		
									$newIdPP = $cProcesoPruebasDB->insertar($cProcesoPruebas);
									$newIdPB = $cProcesoBaremosDB->insertar($cProcesoBaremos);
									$newIdPI = $cProcesoInformesDB->insertar($cProcesoInformes);
									
									//Recogemos la información cargada en la tabla pruebas_papel
									require_once(constant("DIR_WS_COM") . "Pruebas_papel/Pruebas_papelDB.php");
									require_once(constant("DIR_WS_COM") . "Pruebas_papel/Pruebas_papel.php");
									$cPPDB	= new Pruebas_papelDB($aux);  // Entidad DB
									$cPP	= new Pruebas_papel();  // Entidad
									$cPP->setCarga($cEntidad->getIdFicheroSabana());
									$sqlPP = $cPPDB->readLista($cPP);
									$rsPP = $aux->Execute($sqlPP);
									
									require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");									
									require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
									$cCandidatosDB	= new CandidatosDB($aux);  // Entidad
									
									require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
									$cRespuestas_pruebasDB	= new Respuestas_pruebasDB($aux);  // Entidad
									require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
									$cRespPruebasItemsDB	= new Respuestas_pruebas_itemsDB($aux);  // Entidad
									
									$iPP = 0;
									$sSQLRP = "";
									$comboDESC_EMPRESAS		= new Combo($aux,"_fDescEmpresa","idEmpresa","nombre","Descripcion","empresas","","","idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false),"","orden");
									$sDescEmpresa = $comboDESC_EMPRESAS->getDescripcionCombo($cEntidad->getIdEmpresa());
									$comboDESC_PROCESOS		= new Combo($aux,"_fDescProceso","idProceso","nombre","Descripcion","procesos","","","idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($idProceso, false),"","fecMod");
									$sDescProceso = $comboDESC_PROCESOS->getDescripcionCombo($idProceso);
									$sDescIdiomaIso2 = "Español";
									$comboDESC_PRUEBAS		= new Combo($aux,"_fDescPrueba","idPrueba","nombre","Descripcion","pruebas","","","codIdiomaIso2=" . $aux->qstr($sCodIdiomaIso2, false),"","fecMod");
									$sDescPrueba = $comboDESC_PRUEBAS->getDescripcionCombo($cEntidad->getIdPrueba());
									
									while(!$rsPP->EOF)
									{
										$cCandidatos	= new Candidatos();  // Entidad
										$CODIGO = $rsPP->fields['CODIGO'];
										$EDAD = $rsPP->fields['EDAD'];
										$SEXO = $rsPP->fields['SEXO'];
										$NOMBRE = $rsPP->fields['NOMBRE'];
										$APELLIDO1 = $rsPP->fields['APELLIDO1'];
										$APELLIDO2 = $rsPP->fields['APELLIDO2'];
										$sCadena = $rsPP->fields['RESULTADO'];
										$sLCadena = strlen($sCadena);
										
										$cCandidatos->setIdProceso($idProceso);
										$cCandidatos->setIdEmpresa($cEntidad->getIdEmpresa());
										$cCandidatos->setMail($CODIGO . "@papel.es");
										if (!empty($NOMBRE)){
											$cCandidatos->setNombre($NOMBRE);
										}else{
											$cCandidatos->setNombre($CODIGO);
										}
										$cCandidatos->setApellido1($APELLIDO1);
										$cCandidatos->setApellido2($APELLIDO2);
										if($SEXO == "V"){
											$SEXO="1";
										}else{
											$SEXO="2";
										}
										$cCandidatos->setIdSexo($SEXO);
										$cCandidatos->setInformado("0");
										$cCandidatos->setFinalizado("1");
										$cCandidatos->setUsuAlta($cEntidad->getUsuAlta());
										$cCandidatos->setUsuMod($cEntidad->getUsuMod());
										
										$sDescCandidato = $NOMBRE . ' ' . $APELLIDO1 . $APELLIDO2 . ' (' . $cCandidatos->getMail() . ')';
										
										$newIdC = $cCandidatosDB->insertar($cCandidatos);

										//Insertamos en respuestas_pruebas
										require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
										
										$cRespuestas_pruebas	= new Respuestas_pruebas();  // Entidad
										$cRespuestas_pruebas->setIdEmpresa($cEntidad->getIdEmpresa());
										$cRespuestas_pruebas->setDescEmpresa($sDescEmpresa);
										$cRespuestas_pruebas->setIdProceso($idProceso);
										$cRespuestas_pruebas->setDescProceso($sDescProceso);
										$cRespuestas_pruebas->setIdCandidato($newIdC);
										$cRespuestas_pruebas->setDescCandidato($sDescCandidato);
										$cRespuestas_pruebas->setCodIdiomaIso2($sCodIdiomaIso2);
										$cRespuestas_pruebas->setDescIdiomaIso2($sDescIdiomaIso2);
										$cRespuestas_pruebas->setIdPrueba($cEntidad->getIdPrueba());
										$cRespuestas_pruebas->setDescPrueba($sDescPrueba);
										$cRespuestas_pruebas->setFinalizado(1);
										$cRespuestas_pruebas->setLeidoEjemplos(1);
										$cRespuestas_pruebas->setLeidoInstrucciones(1);
										$cRespuestas_pruebas->setMinutos_test(0);
										$cRespuestas_pruebas->setSegundos_test(0);
										$cRespuestas_pruebas->setUsuAlta($cEntidad->getUsuAlta());
										$cRespuestas_pruebas->setUsuMod($cEntidad->getUsuMod());
										$sSQLRP .= $cRespuestas_pruebasDB->getInsertar($cRespuestas_pruebas, $iPP);
										//Instanciamos para guardar los ITEms
										require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
										
										set_time_limit(0);
										$i=0;
										$iPagina=1;
										$iPregunta=1;
										$iRespuesta ="1";
										$sPregunta = "";
										$sqlRespuestas="";
										$iOrden=0;
										//Por página se presentan tres items
										//Si marcado primero es A, quiere decir q de ese bloque d 3 items,
										//MEJOR es A
										//Si marcado segundo es C, quiere decir q de ese bloque d 3 items,
										//PEOR es C
										// Otro ejemplo:
										//Si marcado primero es C, quiere decir q de ese bloque d 3 items,
										//MEJOR es C
										//Si marcado segundo es B, quiere decir q de ese bloque d 3 items,
										//PEOR es B
										$sOpcionMEJOR ="";
										$sOpcionPEOR ="";
										$sOpcionSINSELECCIONAR ="";
										$itemsXPagina=3;
										$itemsEmpezarDesde=0;
										$rsItems = "";
										$sqlRespuestasNueva="";
										$sSQLRPI = "";
										while($i < $sLCadena)
										{
											if($i%2==0){
												$sSQItems = "SELECT * FROM `items` WHERE `codIdiomaIso2` = '" . $sCodIdiomaIso2 . "' AND idPrueba ='" . $cEntidad->getIdPrueba() . "' ORDER BY orden ";
												$sSQItems .= "LIMIT " . $itemsEmpezarDesde . " , " . $itemsXPagina;
												$rsItems = $aux->Execute($sSQItems);
												
												$iOrden++;
												$sOpcionMEJOR = $sCadena[$i];
												$sOpcion = $sCadena[$i];
												switch ($sOpcion)
												{
													case "A":
														$rsItems->Move(0);
														break;
													case "B":
														$rsItems->Move(1);
														break;
													case "C":
														$rsItems->Move(2);
														break;
													default:
														echo "<br />ERROR OPCION Mejor: " . $sOpcion;
														echo "<br />SQL: " . $sSQItems;
														exit;
												}
												//Mejor
												$sDescOpcion = 'Mejor';
//												$sqlRespuestasNueva.="INSERT INTO respuestas_pruebas_items (idEmpresa, descEmpresa, idProceso, descProceso, idcandidato, descCandidato, codIdiomaIso2, descIdiomaIso2, idPrueba, descPrueba, idItem, descItem, idOpcion, descOpcion, codigo, orden, valor, fecAlta, fecMod) VALUES ";
//												$sqlRespuestasNueva.="('" . $cEntidad->getIdEmpresa() . "', 'nombreEmpresa', '" . $idProceso . "', '" . $cEntidad->getProceso() . "', '" . $newIdC . "', '" . $cCandidatos->getNombre($NOMBRE) . " " . $cCandidatos->getApellido1($APELLIDO1) . " " . $cCandidatos->getApellido2($APELLIDO2) . " (" . $cCandidatos->getMail() . ")', 'es', 'Español', '" . $cEntidad->getIdPrueba() . "', '" . $rsItems->fields['idItem'] . "', '" . $rsItems->fields['enunciado'] . "', '1', 'Mejor', 'M', '" . $rsItems->fields['orden'] . "', '2', now(), now());";
												
												$cRespPruebasItems	= new Respuestas_pruebas_items();  // Entidad
											    $cRespPruebasItems->setIdEmpresa($cEntidad->getIdEmpresa());
												$cRespPruebasItems->setIdProceso($idProceso);
												$cRespPruebasItems->setIdCandidato($newIdC);
												$cRespPruebasItems->setIdPrueba($cEntidad->getIdPrueba());
												$cRespPruebasItems->setCodIdiomaIso2($sCodIdiomaIso2);
												$cRespPruebasItems->setIdItem($rsItems->fields['idItem']);
												$cRespPruebasItems->setIdOpcion(1);
												$cRespPruebasItems->setOrden($rsItems->fields['orden']);	
												$cRespPruebasItems->setUsuAlta($cEntidad->getUsuAlta());
												$cRespPruebasItems->setUsuMod($cEntidad->getUsuMod());
												$cRespPruebasItems->setDescEmpresa($sDescEmpresa);
												$cRespPruebasItems->setDescProceso($sDescProceso);
												$cRespPruebasItems->setDescCandidato($sDescCandidato);
												$cRespPruebasItems->setDescIdiomaIso2($sDescIdiomaIso2);
												$cRespPruebasItems->setDescPrueba($sDescPrueba);
												$cRespPruebasItems->setDescItem($rsItems->fields['enunciado']);
												$cRespPruebasItems->setDescOpcion($sDescOpcion);
												
												
												$sSQLRPI .= $cRespPruebasItemsDB->getInsertar($cRespPruebasItems, $i);
												
											
											}else{
												$iOrden++;
												$sOpcionPEOR = $sCadena[$i];
												$sOpcion = $sCadena[$i];
												switch ($sOpcion)
												{
													case "A":
														$rsItems->Move(0);
														break;
													case "B":
														$rsItems->Move(1);
														break;
													case "C":
														$rsItems->Move(2);
														break;
													default:
														echo "<br />ERROR OPCION Peor: " . $sOpcion;
														echo "<br />SQL: " . $sSQItems;
														exit;
												}
												//Peor
												$sDescOpcion = 'Peor';
//												$sqlRespuestasNueva.="INSERT INTO respuestas_pruebas_items (idEmpresa, descEmpresa, idProceso, descProceso, idcandidato, descCandidato, codIdiomaIso2, descIdiomaIso2, idPrueba, descPrueba, idItem, descItem, idOpcion, descOpcion, codigo, orden, valor, fecAlta, fecMod) VALUES ";
//												$sqlRespuestasNueva.="('" . $cEntidad->getIdEmpresa() . "', 'nombreEmpresa', '" . $idProceso . "', '" . $cEntidad->getProceso() . "', '" . $newIdC . "', '" . $cCandidatos->getNombre($NOMBRE) . " " . $cCandidatos->getApellido1($APELLIDO1) . " " . $cCandidatos->getApellido2($APELLIDO2) . " (" . $cCandidatos->getMail() . ")', 'es', 'Español', '" . $cEntidad->getIdPrueba() . "', '" . $rsItems->fields['idItem'] . "', '" . $rsItems->fields['enunciado'] . "', '2', 'Peor', 'P', '" . $rsItems->fields['orden'] . "', '0', now(), now());";
												$iOrden++;
												
												$cRespPruebasItems	= new Respuestas_pruebas_items();  // Entidad
											    $cRespPruebasItems->setIdEmpresa($cEntidad->getIdEmpresa());
												$cRespPruebasItems->setIdProceso($idProceso);
												$cRespPruebasItems->setIdCandidato($newIdC);
												$cRespPruebasItems->setIdPrueba($cEntidad->getIdPrueba());
												$cRespPruebasItems->setCodIdiomaIso2($sCodIdiomaIso2);
												$cRespPruebasItems->setIdItem($rsItems->fields['idItem']);
												$cRespPruebasItems->setIdOpcion(2);
												$cRespPruebasItems->setOrden($rsItems->fields['orden']);	
												$cRespPruebasItems->setUsuAlta($cEntidad->getUsuAlta());
												$cRespPruebasItems->setUsuMod($cEntidad->getUsuMod());	
												$cRespPruebasItems->setDescEmpresa($sDescEmpresa);
												$cRespPruebasItems->setDescProceso($sDescProceso);
												$cRespPruebasItems->setDescCandidato($sDescCandidato);
												$cRespPruebasItems->setDescIdiomaIso2($sDescIdiomaIso2);
												$cRespPruebasItems->setDescPrueba($sDescPrueba);
												$cRespPruebasItems->setDescOpcion($sDescOpcion);
												
												$sSQLRPI .= $cRespPruebasItemsDB->getInsertar($cRespPruebasItems, $i);
												
												
												$sOpcionSINSELECCIONAR= $this->getSinSeleccionarPRISMA($iPregunta, $CODIGO, $sOpcionMEJOR, $sOpcionPEOR);
												$sOpcion = $sOpcionSINSELECCIONAR;
												switch ($sOpcion)
												{
													case "A":
														$rsItems->Move(0);
														break;
													case "B":
														$rsItems->Move(1);
														break;
													case "C":
														$rsItems->Move(2);
														break;
													default:
														echo "<br />ERROR OPCION SIN SELECCIONAR: " . $sOpcion;
														echo "<br />SQL: " . $sSQItems;
														exit;
												}
												
												//Sin contestar
												$sDescOpcion = '';
//												$sqlRespuestasNueva.="INSERT INTO respuestas_pruebas_items (idEmpresa, descEmpresa, idProceso, descProceso, idcandidato, descCandidato, codIdiomaIso2, descIdiomaIso2, idPrueba, descPrueba, idItem, descItem, idOpcion, descOpcion, codigo, orden, valor, fecAlta, fecMod) VALUES ";
//												$sqlRespuestasNueva.="('" . $cEntidad->getIdEmpresa() . "', 'nombreEmpresa', '" . $idProceso . "', '" . $cEntidad->getProceso() . "', '" . $newIdC . "', '" . $cCandidatos->getNombre($NOMBRE) . " " . $cCandidatos->getApellido1($APELLIDO1) . " " . $cCandidatos->getApellido2($APELLIDO2) . " (" . $cCandidatos->getMail() . ")', 'es', 'Español', '" . $cEntidad->getIdPrueba() . "', '" . $rsItems->fields['idItem'] . "', '" . $rsItems->fields['enunciado'] . "', '0', '', '', '" . $rsItems->fields['orden'] . "', '1', now(), now());";
												
												$cRespPruebasItems	= new Respuestas_pruebas_items();  // Entidad
											    $cRespPruebasItems->setIdEmpresa($cEntidad->getIdEmpresa());
												$cRespPruebasItems->setIdProceso($idProceso);
												$cRespPruebasItems->setIdCandidato($newIdC);
												$cRespPruebasItems->setIdPrueba($cEntidad->getIdPrueba());
												$cRespPruebasItems->setCodIdiomaIso2($sCodIdiomaIso2);
												$cRespPruebasItems->setIdItem($rsItems->fields['idItem']);
												$cRespPruebasItems->setIdOpcion(0);
												$cRespPruebasItems->setOrden($rsItems->fields['orden']);	
												$cRespPruebasItems->setUsuAlta($cEntidad->getUsuAlta());
												$cRespPruebasItems->setUsuMod($cEntidad->getUsuMod());	
												$cRespPruebasItems->setDescEmpresa($sDescEmpresa);
												$cRespPruebasItems->setDescProceso($sDescProceso);
												$cRespPruebasItems->setDescCandidato($sDescCandidato);
												$cRespPruebasItems->setDescIdiomaIso2($sDescIdiomaIso2);
												$cRespPruebasItems->setDescPrueba($sDescPrueba);
												$cRespPruebasItems->setDescOpcion($sDescOpcion);
												
												$sSQLRPI .= $cRespPruebasItemsDB->getInsertar($cRespPruebasItems, $i);
												
											}
											if(($i+1)%2==0){
												$itemsEmpezarDesde = $itemsEmpezarDesde + $itemsXPagina;
												$iPregunta++;
												$iPagina++;
											}
											$i++;
										}
										//	echo $sSQLRPI;exit;
										$sSQLRPI = substr($sSQLRPI, 0, -1);
										$sSQLRPI .=";";
										if($aux->Execute($sSQLRPI) === false){
											$this->msg_Error	= array();
											$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Imp_pruebas_papelDB][" . $aux->ErrorMsg() . "]";
											$this->msg_Error[]	= $sTypeError;
											error_log($sTypeError . " ->\t" . $sSQLRPI . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
											return 0;
										}
										//Mandamos Generar el informe para que esté disponible en la descarga
										//Parámetros por orden
										// es proceso 1
										// MODO 627
										// fIdTipoInforme Prisma Informe completo 3
										// fCodIdiomaIso2 Idioma del informe es
										// fIdPrueba Prueba prisma 24
										// fIdEmpresa Id de empresa  3788
										// fIdProceso Id del proceso 3
										// fIdCandidato Id Candidato 1
										// fCodIdiomaIso2Prueba Idioma prueba es
										// fIdBaremo Id Baremo, prisma no tiene , le pasamos 1
										$cmd = constant("DIR_FS_PATH_PHP") . ' ' . str_replace("Candidato", "Admin", constant("DIR_FS_DOCUMENT_ROOT")) . '/Informes_candidato.php 1 627 ' . $cProcesoInformes->getIdTipoInforme() . ' ' . $cProcesoInformes->getCodIdiomaInforme() . ' ' . $cEntidad->getIdPrueba() . ' ' . $cEntidad->getIdEmpresa() . ' ' . $cProcesoPruebas->getIdProceso() . ' ' . $newIdC . ' ' . $cProcesoInformes->getCodIdiomaIso2() . ' 1';
																			
										//$cUtilidades->execInBackground($cmd);
										$cmdPost = constant("DIR_WS_GESTOR") . 'Informes_candidato.php?MODO=627&fIdTipoInforme=' . $cProcesoInformes->getIdTipoInforme() . '&fCodIdiomaIso2=' . $cProcesoInformes->getCodIdiomaInforme() . '&fIdPrueba=' . $cEntidad->getIdPrueba() . '&fIdEmpresa=' . $cEntidad->getIdEmpresa() . '&fIdProceso=' . $cProcesoPruebas->getIdProceso() . '&fIdCandidato=' . $newIdC . '&fCodIdiomaIso2Prueba=' . $cProcesoInformes->getCodIdiomaIso2() . '&fIdBaremo=1';
										$arrayGeneraInformes[] = $cmdPost;
//										error_log("GENERA PDF BACKGROUN ->\t" . $cmdPost . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
// DESCOMENTAR PARA GENERAR PDF										$cUtilidades->backgroundPost($cmdPost);
										$iPP++;
										$rsPP->MoveNext();	
									}
//									echo $sSQLRP;exit;
									$sSQLRP = substr($sSQLRP, 0, -1);
									$sSQLRP .=";";
									if($aux->Execute($sSQLRP) === false){
										$this->msg_Error	= array();
										$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Imp_pruebas_papelDB][" . $aux->ErrorMsg() . "]";
										$this->msg_Error[]	= $sTypeError;
										error_log($sTypeError . " ->\t" . $sSQLRP . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
										return 0;
									}

//									echo $sqlRespuestasNueva;
//									$aux->Execute($sqlRespuestasNueva);
									//Lanzamos los indormes en back Ground
									for ($inf=0, $max = sizeof($arrayGeneraInformes); $inf < $max; $inf++){
										$cUtilidades->backgroundPost($arrayGeneraInformes[$inf]);
										sleep(5);
//										error_log("GENERA PDF BACKGROUN ->\t" . $arrayGeneraInformes[$inf] . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
									}
								}
							}
							$sMalContestadas = substr($sMalContestadas, 1 , strlen($sMalContestadas));
							$sBienContestadas = substr($sBienContestadas, 1 , strlen($sBienContestadas));
									
							break;
						default:
							//Borramos el fichero cargado y los datos de pruebas_papel
							$sSQL = 'DELETE FROM `pruebas_papel` WHERE carga=' . $cEntidad->getIdFicheroSabana();
							$aux->Execute($sSQL);
	
							$sql  ="DELETE FROM imp_pruebas_papel ";
							$sql  .="WHERE ";
							$sql  .="idFicheroSabana=" . $aux->qstr($cEntidad->getIdFicheroSabana(), false) . " ";
							$aux->Execute($sql);
							$this->msg_Error	= array();
							$sTypeError	=	date('d/m/Y H:i:s') . " Error NO se encuentra una prueba válida.";
							$this->msg_Error[]	= $sTypeError;
							error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
							$retorno=0;
							break;
					}
				}
				break;
			default:
				$retorno = 0;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error Tipo Fichero  [" . $src_type . "][Formato no soportado]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				break;
		} // end switch
		return $retorno;
	}

	function getCabFile()
	{
		$campos=array("CODIGO"=>"CODIGO",
			"EDAD"=>"EDAD",
			"SEXO"=>"SEXO",
			"NOMBRE"=>"NOMBRE",
			"APELLIDO1"=>"APELLIDO1",
			"APELLIDO2"=>"APELLIDO2",
			"RESULTADO"=>"RESULTADO",
			"ORDEN"=>"ORDEN");
		return $campos;
	}
	function getSinSeleccionarPRISMA($iPregunta, $CODIGO, $sMEJOR, $sPEOR){
		$sOpcion = $sMEJOR . $sPEOR;
		$sSinSeleccionar="";
		switch ($sOpcion)
		{
			case "AB":
				$sSinSeleccionar="C";
				break;
			case "AC":
				$sSinSeleccionar="B";
				break;
			case "BA":
				$sSinSeleccionar="C";
				break;
			case "BC":
				$sSinSeleccionar="A";
				break;
			case "CA":
				$sSinSeleccionar="B";
				break;
			case "CB":
				$sSinSeleccionar="A";
				break;
			default:
				echo "<br />ERROR OPCION Ha puesto como Mejor y Peor: " . $sOpcion;
				echo "<br />Código:" . $CODIGO . " En la pregunta:" . $iPregunta ;
				exit;
		}
		return $sSinSeleccionar;
	}
}//Fin de la Clase Imp_pruebas_papelDB
?>