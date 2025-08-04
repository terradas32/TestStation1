<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla tipos_tpv
**/
class Tipos_tpvDB
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
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
		$cUtilidades	= new Utilidades();
	
		$newId = $this->getSiguienteId($cEntidad);
	
		$sql = "INSERT INTO tipos_tpv (";
		$sql .= "idTipoTpv" . ",";
		$sql .= "descripcion" . ",";
		$sql .= "TERMINAL_TYPE" . ",";
		$sql .= "OPERATION_TYPE" . ",";
		$sql .= "URL_NOTIFY" . ",";
		$sql .= "URL_OK" . ",";
		$sql .= "URL_NOOK" . ",";
		$sql .= "SERVICE_ACTION" . ",";
		$sql .= "bajaLog" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($newId, false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescripcion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getTERMINAL_TYPE(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getOPERATION_TYPE(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getURL_NOTIFY(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getURL_OK(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getURL_NOOK(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getSERVICE_ACTION(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getBajaLog(), false) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Tipos_tpvDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			return 0;
		}
		else{
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambiosDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambios.php");
			$cEntidadHistorico_cambiosDB	= new Historico_cambiosDB($aux);  // Entidad DB Historico_cambios
			$cEntidadHistorico_cambios		= new Historico_cambios();  // Entidad Historico_cambios
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
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
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : "0");
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : "0");
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
	
		$sql  = "SELECT MAX(idTipoTpv) AS Max FROM tipos_tpv ";
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
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][Tipos_tpvDB]";
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
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
		$cUtilidades	= new Utilidades();
	
		$sql = "UPDATE tipos_tpv SET ";
		$sql .= "idTipoTpv=" . $aux->qstr($cEntidad->getIdTipoTpv(), false) . ", ";
		$sql .= "descripcion=" . $aux->qstr($cEntidad->getDescripcion(), false) . ", ";
		$sql .= "TERMINAL_TYPE=" . $aux->qstr($cEntidad->getTERMINAL_TYPE(), false) . ", ";
		$sql .= "OPERATION_TYPE=" . $aux->qstr($cEntidad->getOPERATION_TYPE(), false) . ", ";
		$sql .= "URL_NOTIFY=" . $aux->qstr($cEntidad->getURL_NOTIFY(), false) . ", ";
		$sql .= "URL_OK=" . $aux->qstr($cEntidad->getURL_OK(), false) . ", ";
		$sql .= "URL_NOOK=" . $aux->qstr($cEntidad->getURL_NOOK(), false) . ", ";
		$sql .= "SERVICE_ACTION=" . $aux->qstr($cEntidad->getSERVICE_ACTION(), false) . ", ";
		$sql .= "bajaLog=" . $aux->qstr($cEntidad->getBajaLog(), false) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idTipoTpv=" . $aux->qstr($cEntidad->getIdTipoTpv(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][Tipos_tpvDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
		else{
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambiosDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambios.php");
			$cEntidadHistorico_cambiosDB	= new Historico_cambiosDB($aux);  // Entidad DB Historico_cambios
			$cEntidadHistorico_cambios		= new Historico_cambios();  // Entidad Historico_cambios
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
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
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : "0");
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : "0");
			$HistId	= $cEntidadHistorico_cambiosDB->insertar($cEntidadHistorico_cambios);
			if (empty($HistId)){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error [GUARDANDO HISTORICO][" . constant("MNT_ALTA") . "][Tipos_tpvDBDB]";
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
			//Borramos el registro de la Entidad.
		$sql = "UPDATE tipos_tpv SET ";
		$sql .= "bajaLog='1'";
			$sql  .="WHERE ";
			if ($cEntidad->getIdTipoTpv() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idTipoTpv=" . $aux->qstr($cEntidad->getIdTipoTpv(), false) . " ";
			}
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][Tipos_tpvDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}
		}else $retorno=false;
		if ($retorno){
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambiosDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambios.php");
			$cEntidadHistorico_cambiosDB	= new Historico_cambiosDB($aux);  // Entidad DB Historico_cambios
			$cEntidadHistorico_cambios		= new Historico_cambios();  // Entidad Historico_cambios
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
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
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : "0");
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : "0");
			$HistId	= $cEntidadHistorico_cambiosDB->insertar($cEntidadHistorico_cambios);
			if (empty($HistId)){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error [GUARDANDO HISTORICO][" . constant("MNT_ALTA") . "][Tipos_tpvDBDB]";
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
	
		$sql = "SELECT *  FROM tipos_tpv WHERE ";
		$sql  .="idTipoTpv=" . $aux->qstr($cEntidad->getIdTipoTpv(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdTipoTpv($arr['idTipoTpv']);
					$cEntidad->setDescripcion($arr['descripcion']);
					$cEntidad->setTERMINAL_TYPE($arr['TERMINAL_TYPE']);
					$cEntidad->setOPERATION_TYPE($arr['OPERATION_TYPE']);
					$cEntidad->setURL_NOTIFY($arr['URL_NOTIFY']);
					$cEntidad->setURL_OK($arr['URL_OK']);
					$cEntidad->setURL_NOOK($arr['URL_NOOK']);
					$cEntidad->setSERVICE_ACTION($arr['SERVICE_ACTION']);
					$cEntidad->setBajaLog($arr['bajaLog']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][Tipos_tpvDB]";
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
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
		$cUtilidades	= new Utilidades();
	
		$sql="";
		$and = false;
		$sql.="SELECT * FROM tipos_tpv ";
		if ($cEntidad->getIdTipoTpv() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTipoTpv=" . $aux->qstr($cEntidad->getIdTipoTpv(), false);
		}
		if ($cEntidad->getDescripcion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descripcion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescripcion() . "%") . ")";
		}
		if ($cEntidad->getTERMINAL_TYPE() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(TERMINAL_TYPE) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getTERMINAL_TYPE() . "%") . ")";
		}
		if ($cEntidad->getOPERATION_TYPE() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(OPERATION_TYPE) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getOPERATION_TYPE() . "%") . ")";
		}
		if ($cEntidad->getURL_NOTIFY() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(URL_NOTIFY) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getURL_NOTIFY() . "%") . ")";
		}
		if ($cEntidad->getURL_OK() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(URL_OK) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getURL_OK() . "%") . ")";
		}
		if ($cEntidad->getURL_NOOK() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(URL_NOOK) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getURL_NOOK() . "%") . ")";
		}
		if ($cEntidad->getSERVICE_ACTION() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(SERVICE_ACTION) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getSERVICE_ACTION() . "%") . ")";
		}
		if ($cEntidad->getBajaLog() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="bajaLog>=" . $aux->qstr($cEntidad->getBajaLog(), false);
		}
		if ($cEntidad->getBajaLogHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="bajaLog<=" . $aux->qstr($cEntidad->getBajaLogHast(), false);
		}
		if ($cEntidad->getFecAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta>=" . $aux->qstr($cUtilidades->cFechaToMySQL($cEntidad->getFecAlta()), false);
		}
		if ($cEntidad->getFecAltaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta<=" . $aux->qstr($cUtilidades->cFechaToMySQL($cEntidad->getFecAltaHast()), false);
		}
		if ($cEntidad->getFecMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod>=" . $aux->qstr($cUtilidades->cFechaToMySQL($cEntidad->getFecMod()), false);
		}
		if ($cEntidad->getFecModHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod<=" . $aux->qstr($cUtilidades->cFechaToMySQL($cEntidad->getFecModHast()), false);
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
		if ($cEntidad->getGroupBy() != ""){
			$sql .=" GROUP BY " . $cEntidad->getGroupBy();
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

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_tpv/Tipos_tpv.php");
		$cEntidadAnterior	= new Tipos_tpv();
		$cEntidadAnterior->setIdTipoTpv($cEntidad->getIdTipoTpv());
		$cEntidadAnterior = $this->readEntidad($cEntidadAnterior);

		$sql = "UPDATE tipos_tpv SET  ";
		return $retorno;
	}
}//Fin de la Clase Tipos_tpvDB
?>