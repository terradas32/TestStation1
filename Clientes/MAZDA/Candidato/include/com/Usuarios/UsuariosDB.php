<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla usuarios
**/
class UsuariosDB
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

		$newId = $cEntidad->getIdUsuario();
		$iCont = 0;
		$sql  = "SELECT COUNT(idUsuario) AS Max FROM wi_usuarios ";
		$sql .= "WHERE ";
		$sql .= "UPPER(login)=" . $aux->qstr(strtoupper($cEntidad->getLogin()), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$iCont = $arr['Max'];
			}
		}
		if ($iCont > 0 ){
			//Existe un usuario con ese Login
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error Usuario Existe [" . constant("MNT_ALTA") . "][UsuariosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			return 0;
		}

        $newId = $this->getSiguienteId($cEntidad);

		$sql = "INSERT INTO wi_usuarios (";
		$sql .= "idUsuario" . ",";
		$sql .= "idUsuarioTipo" . ",";
	  $sql .= (trim($cEntidad->getLogin()) != "") ? "login" . "," : "";
	  $sql .= (trim($cEntidad->getPassword()) != "") ? "password" . "," : "";
		$sql .= "nombre" . ",";
		$sql .= "apellido1" . ",";
		$sql .= "apellido2" . ",";
		$sql .= "email" . ",";
	  $sql .= (trim($cEntidad->getLoginCorreo()) != "") ? "loginCorreo" . "," : "";
	  $sql .= (trim($cEntidad->getPasswordCorreo()) != "") ? "passwordCorreo" . "," : "";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr( $newId) . ",";
		$sql .= $aux->qstr($cEntidad->getIdUsuarioTipo(), false) . ",";
		$sql .= (trim($cEntidad->getLogin()) != "") ? $aux->qstr($cEntidad->getLogin(), false) . "," : "";
    $sql .= (trim($cEntidad->getPassword()) != "") ? $aux->qstr(password_hash($cEntidad->getPassword(), PASSWORD_BCRYPT), false) . "," : "";
    $sql .= $aux->qstr($cEntidad->getNombre(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getApellido1(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getApellido2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getEmail(), false) . ",";
		$sql .= (trim($cEntidad->getLoginCorreo()) != "") ? $aux->qstr($cEntidad->getLoginCorreo(), false) . "," : "";
	  $sql .= (trim($cEntidad->getPasswordCorreo()) != "") ? $aux->qstr($cEntidad->getPasswordCorreo(), false) . "," : "";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][UsuariosDB]";
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

		$sql  = "SELECT MAX(idUsuario) AS Max FROM wi_usuarios ";

		$rs = $aux->Execute($sql);
		$newId=0;
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][UsuariosDB]";
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

		$sql = "UPDATE wi_usuarios SET ";
		$sql .= "idUsuario=" . $aux->qstr($cEntidad->getIdUsuario(), false) . ", ";
		$sql .= $aux->qstr(($cEntidad->getIdUsuarioTipo() != "") ? $cEntidad->getIdUsuarioTipo() : "0", false) . ",";
	  $sql .= (trim($cEntidad->getLogin()) != "") ? "login=" . $aux->qstr($cEntidad->getLogin(), false) . "," : "";
	  $sql .= (trim($cEntidad->getPassword()) != "") ? "password=" . $aux->qstr(password_hash($cEntidad->getPassword(), PASSWORD_BCRYPT), false) . "," : "";
		$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
		$sql .= "apellido1=" . $aux->qstr($cEntidad->getApellido1(), false) . ", ";
		$sql .= "apellido2=" . $aux->qstr($cEntidad->getApellido2(), false) . ", ";
		$sql .= "email=" . $aux->qstr($cEntidad->getEmail(), false) . ", ";
    $sql .= (trim($cEntidad->getLoginCorreo()) != "") ? "loginCorreo=" . $aux->qstr($cEntidad->getLoginCorreo(), false) . "," : "";
    $sql .= (trim($cEntidad->getPasswordCorreo()) != "") ? "passwordCorreo=" . $aux->qstr($cEntidad->getPasswordCorreo(), false) . "," : "";
		$sql .= "bajaLog=" . $aux->qstr($cEntidad->getBajaLog(), false) . ", ";
		if ($cEntidad->getBajaLog() == '1'){
			$sql .= "fecBaja=" . $aux->sysTimeStamp . ", ";
		}else{
			$sql .= "fecBaja=NULL, ";
		}
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idUsuario=" . $aux->qstr($cEntidad->getIdUsuario(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][UsuariosDB]";
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
		$retorno = is_file(constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfilesDB.php");
		if (!$retorno){
			$retorno=false;
			$sTypeError	=	date('d/m/Y H:i:s') . " Error Class Not Found [" . constant("MNT_BORRAR") . "][UsuariosDB::Usuarios_perfilesDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->	" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
		//Borramos las dependencias (Borrado Físico).
		if ($retorno)
		{
			require_once(constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfilesDB.php");
			require_once(constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfiles.php");
			$cUsuarios_perfilesDB	= new Usuarios_perfilesDB($aux);  // Entidad DB
			$cUsuarios_perfiles	= new Usuarios_perfiles();  // Entidad
			$cUsuarios_perfiles->setIdUsuario($cEntidad->getIdUsuario());
			if (!$cUsuarios_perfilesDB->borrar($cUsuarios_perfiles))
			{
				$retorno=false;
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][UsuariosDB::Usuarios_perfilesDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->	" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}
		}

		if ($retorno){
		//Borramos el registro de la Entidad (Borrado Lógico).
		$sql = "UPDATE wi_usuarios SET ";
		$sql .= "fecBaja=" . $aux->sysTimeStamp . ",";
		$sql .= "bajaLog='1'";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ", ";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) . " ";
			$sql  .="WHERE ";
			if ($cEntidad->getIdUsuario() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idUsuario=" . $aux->qstr($cEntidad->getIdUsuario(), false) . " ";
			}
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][UsuariosDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
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

		$sql = "SELECT *  FROM wi_usuarios WHERE ";
		$sql  .="idUsuario=" . $aux->qstr($cEntidad->getIdUsuario(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdUsuario($arr['idUsuario']);
					$cEntidad->setIdUsuarioTipo($arr['idUsuarioTipo']);
					$cEntidad->setLogin($arr['login']);
					$cEntidad->setPassword($arr['password']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setApellido1($arr['apellido1']);
					$cEntidad->setApellido2($arr['apellido2']);
					$cEntidad->setEmail($arr['email']);
					$cEntidad->setLoginCorreo($arr['loginCorreo']);
					$cEntidad->setPasswordCorreo($arr['passwordCorreo']);
					$cEntidad->setFecBaja($arr['fecBaja']);
					$cEntidad->setBajaLog($arr['bajaLog']);
					$cEntidad->setUltimoLogin($arr['ultimoLogin']);
					$cEntidad->setToken($arr['token']);
					$cEntidad->setUltimaAcc($arr['ultimaAcc']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][UsuariosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}
		return $cEntidad;
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
	function Login($cEntidad)
	{
		$aux			= $this->conn;

		$sql = "SELECT  * FROM wi_usuarios WHERE ";
		$sql  .="login=" . $aux->qstr($cEntidad->getLogin(), false) . " AND ";
		//$sql  .="password=" . $aux->qstr(password_hash($cEntidad->getPassword(), PASSWORD_BCRYPT), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				if (password_verify($cEntidad->getPassword(), $arr['password'])){
					return $arr;
				}
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [Login][UsuariosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}
//antiguo
		$sql = "SELECT  * FROM wi_usuarios WHERE ";
		$sql  .="login=" . $aux->qstr($cEntidad->getLogin(), false) . " AND ";
		$sql  .="password=" . $aux->qstr(md5($cEntidad->getPassword()), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				return $arr;
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [Login][UsuariosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}
		return array();
	}

	function getLogin($cEntidad)
	{
		$aux			= $this->conn;

		$sql = "SELECT * FROM wi_usuarios WHERE ";
		$sql  .="login=" . $aux->qstr($cEntidad->getLogin(), false);
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				return $arr;
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getLogin][UsuariosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}
		return array();
	}
	/*************************************************************************
	* Modifica una entidad en la base de datos.
	* @param entidad Entidad a modificar con Datos
	* @return boolean Estado de la modificación
	* @exception Exception Error al ejecutar la acción
	*  en la base de datos
	************************************************************************/
	function ultimoLogin($cEntidad)
	{
		$aux			= $this->conn;
		$retorno=true;

		$sql = "UPDATE wi_usuarios SET ";
		$sql .= "ultimoLogin=" . $aux->sysTimeStamp . " ";
		$sql .= " WHERE ";
		$sql .="idUsuario=" . $aux->qstr($cEntidad->getIdUsuario(), false) . " ";

		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][UsuariosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
		return $retorno;
	}

	/*************************************************************************
	 * Modifica una entidad en la base de datos.
	 * @param entidad Entidad a modificar con Datos
	 * @return long Numero de ID de la entidad
	 * @exception Exception Error al consultar informacion
	 *  en la base de datos
	 ************************************************************************/
	function ActualizaToken($cEntidad)
	{
		$aux			= $this->conn;

		// Construimos SQL
		$sql = "UPDATE wi_usuarios SET ";
		$sql .="token=" . $aux->qstr($cEntidad->getToken(), false) . ", ";
		$sql .="ultimaAcc=" . $aux->sysTimeStamp . " ";
		$sql .=" WHERE ";
		$sql .="idUsuario=" . $aux->qstr($cEntidad->getIdUsuario(), false) . " ";

		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "-Token][UsuariosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}

		return $retorno;
	}	// Modficar


	/*************************************************************************
	 * Consulta en la base de datos recogiendo la informacion
	 * recibida por la entidad, esta forma de consultar genera
	 * un <b>solo</b> registro conteniendo la informacion
	 * de la entidad recibida. Este metodo se utiliza para efectuar
	 * consultas concretas de un solo registro.
	 * @param entidad Entidad con la informacion basica a consultar
	 * @exception SQLException Error al recuperar la informacion
	 * de la base de datos
	 * @return Usuarios Informacion recuperada
	 *************************************************************************/
	function usuarioPorToken($cEntidad)
	{

		$aux			= $this->conn;

		$sql = "SELECT * FROM wi_usuarios ";
		$sql  .= "WHERE ";
		$sql  .= "token=" . $aux->qstr($cEntidad->getToken(), false) . " ";
		$sql  .= "AND ";
		$sql  .= "bajaLog=0";

		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdUsuario($arr['idUsuario']);
					$cEntidad->setIdUsuarioTipo($arr['idUsuarioTipo']);
					$cEntidad->setLogin($arr['login']);
					$cEntidad->setPassword($arr['password']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setApellido1($arr['apellido1']);
					$cEntidad->setApellido2($arr['apellido2']);
					$cEntidad->setEmail($arr['email']);
					$cEntidad->setLoginCorreo($arr['loginCorreo']);
					$cEntidad->setPasswordCorreo($arr['passwordCorreo']);
					$cEntidad->setFecBaja($arr['fecBaja']);
					$cEntidad->setBajaLog($arr['bajaLog']);
					$cEntidad->setUltimoLogin($arr['ultimoLogin']);
					$cEntidad->setToken($arr['token']);
					$cEntidad->setUltimaAcc($arr['ultimaAcc']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [usuarioPorToken][UsuariosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}
		return $cEntidad;

	}

	function isUsuarioActivo($cEntidad)
	{
		$aux			= $this->conn;
		$bRetorno = false;
		$sql = "";
		$iH	= 24;
		$iM	= 60;
		$iS	= 60;
		$iTiempoMinutos = $iH + $iM + $iS;

		$sql ="SELECT ";
		$sql .="TIMEDIFF(now(), ultimaAcc) as Diferencia ";
		$sql .="FROM wi_usuarios ";
		$sql .="WHERE ";
		$sql .="idUsuario=" . $aux->qstr($cEntidad->getIdUsuario(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$Diferencia=$arr['Diferencia'];
				$aDiferencia=explode(":", $Diferencia);
				$iH=$aDiferencia[0]*60;
				$iM=$aDiferencia[1];
				$iS=$aDiferencia[2]/60;
				$iTiempoMinutos = floor($iH + $iM + $iS);
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [isUsuarioActivo][UsuariosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}

		if ($iTiempoMinutos <= constant("TIMEOUT_SESION")){
			$bRetorno = $this->ActualizaToken($cEntidad);
		}

		return $bRetorno;

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
		$sql.="SELECT * FROM wi_usuarios ";
		if ($cEntidad->getIdUsuario() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idUsuario=" . $aux->qstr($cEntidad->getIdUsuario(), false);
		}
		if ($cEntidad->getIdUsuarioTipo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idUsuarioTipo=" . $aux->qstr($cEntidad->getIdUsuarioTipo(), false);
		}
		if ($cEntidad->getLogin() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(login) LIKE UPPER('%" . $cEntidad->getLogin() . "%')";
		}
		if ($cEntidad->getPassword() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(password) LIKE UPPER('%" . $cEntidad->getPassword() . "%')";
		}
		if ($cEntidad->getNombre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nombre) LIKE UPPER('%" . $cEntidad->getNombre() . "%')";
		}
		if ($cEntidad->getApellido1() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(apellido1) LIKE UPPER('%" . $cEntidad->getApellido1() . "%')";
		}
		if ($cEntidad->getApellido2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(apellido2) LIKE UPPER('%" . $cEntidad->getApellido2() . "%')";
		}
		if ($cEntidad->getEmail() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(email) LIKE UPPER('%" . $cEntidad->getEmail() . "%')";
		}
		if ($cEntidad->getLoginCorreo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(loginCorreo) LIKE UPPER('%" . $cEntidad->getLoginCorreo() . "%')";
		}
		if ($cEntidad->getPasswordCorreo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(passwordCorreo) LIKE UPPER('%" . $cEntidad->getPasswordCorreo() . "%')";
		}
		if ($cEntidad->getFecBaja() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecBaja>=" . $aux->qstr($cEntidad->getFecBaja(), false);
		}
		if ($cEntidad->getFecBajaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecBaja<=" . $aux->qstr($cEntidad->getFecBajaHast(), false);
		}
		if ($cEntidad->getBajaLog() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="bajaLog=" . $aux->qstr($cEntidad->getBajaLog(), false);
		}
		if ($cEntidad->getUltimoLogin() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="ultimoLogin>=" . $aux->qstr($cEntidad->getUltimoLogin(), false);
		}
		if ($cEntidad->getUltimoLoginHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="ultimoLogin<=" . $aux->qstr($cEntidad->getUltimoLoginHast(), false);
		}
		if ($cEntidad->getToken() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(token) LIKE UPPER('%" . $cEntidad->getToken() . "%')";
		}
		if ($cEntidad->getUltimaAcc() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="ultimaAcc>=" . $aux->qstr($cEntidad->getUltimaAcc(), false);
		}
		if ($cEntidad->getUltimaAccHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="ultimaAcc<=" . $aux->qstr($cEntidad->getUltimaAccHast(), false);
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
		$_SESSION['SQLUsuariosDB'] = $sql;
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

	function getUltimaSQL()
	{
		$this->sSQL = $_SESSION['SQLUsuariosDB'];
		return $this->sSQL;
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

		$sql = "UPDATE wi_usuarios SET  ";
		return $retorno;
	}
}//Fin de la Clase UsuariosDB
?>
