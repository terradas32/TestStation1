<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla historico_cambios
**/
class Historico_cambiosDB
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

		$newId = 1;

		$sql = "INSERT INTO wi_historico_cambios (";
		$sql .= "fecCambio" . ",";
		$sql .= "funcionalidad" . ",";
		$sql .= "modo" . ",";
		$sql .= "query" . ",";
		$sql .= "ip" . ",";
		$sql .= "idUsuario" . ",";
		$sql .= "idUsuarioTipo" . ",";
		$sql .= "login" . ",";
		$sql .= "nombre" . ",";
		$sql .= "apellido1" . ",";
		$sql .= "apellido2" . ",";
		$sql .= "email" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getFuncionalidad(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getModo(), false) . ",";
		$sql .= $aux->qstr(addslashes($cEntidad->getQuery()), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIp(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdUsuario(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdUsuarioTipo(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getLogin(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNombre(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getApellido1(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getApellido2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getEmail(), false) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getIdUsuario(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdUsuario(), false) . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Historico_cambiosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			return 0;
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

		$sql  = "SELECT MAX() AS Max FROM wi_historico_cambios ";
		$sql  .=" WHERE fecCambio=" . $aux->qstr($cEntidad->getFecCambio(), false) . " ";
		$rs = $aux->Execute($sql);
		$newId=0;
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][Historico_cambiosDB]";
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

		$sql = "UPDATE wi_historico_cambios SET ";
		$sql .= "fecCambio=" . $aux->sysTimeStamp . ",";
		$sql .= "funcionalidad=" . $aux->qstr($cEntidad->getFuncionalidad(), false) . ", ";
		$sql .= "modo=" . $aux->qstr($cEntidad->getModo(), false) . ", ";
		$sql .= "query=" . $aux->qstr($cEntidad->getQuery(), false) . ", ";
		$sql .= "ip=" . $aux->qstr($cEntidad->getIp(), false) . ", ";
		$sql .= "idUsuario=" . $aux->qstr($cEntidad->getIdUsuario(), false) . ", ";
		$sql .= $aux->qstr(($cEntidad->getIdUsuarioTipo() != "") ? $cEntidad->getIdUsuarioTipo() : "0", false) . ",";
		$sql .= "login=" . $aux->qstr($cEntidad->getLogin(), false) . ", ";
		$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
		$sql .= "apellido1=" . $aux->qstr($cEntidad->getApellido1(), false) . ", ";
		$sql .= "apellido2=" . $aux->qstr($cEntidad->getApellido2(), false) . ", ";
		$sql .= "email=" . $aux->qstr($cEntidad->getEmail(), false) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getIdUsuario(), false) ;
		$sql .= " WHERE ";
		$sql .="fecCambio=" . $aux->qstr($cEntidad->getFecCambio(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][Historico_cambiosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
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
			$sql  ="DELETE FROM wi_historico_cambios ";
			$sql  .="WHERE ";
			if ($cEntidad->getFecCambio() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="fecCambio=" . $aux->qstr($cEntidad->getFecCambio(), false) . " ";
			}
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][Historico_cambiosDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}
		}else $retorno=false;
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

		$sql = "SELECT *  FROM wi_historico_cambios WHERE ";
		$sql  .="fecCambio=" . $aux->qstr($cEntidad->getFecCambio(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setFecCambio($arr['fecCambio']);
					$cEntidad->setFuncionalidad($arr['funcionalidad']);
					$cEntidad->setModo($arr['modo']);
					$cEntidad->setQuery($arr['query']);
					$cEntidad->setIp($arr['ip']);
					$cEntidad->setIdUsuario($arr['idUsuario']);
					$cEntidad->setIdUsuarioTipo($arr['idUsuarioTipo']);
					$cEntidad->setLogin($arr['login']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setApellido1($arr['apellido1']);
					$cEntidad->setApellido2($arr['apellido2']);
					$cEntidad->setEmail($arr['email']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][Historico_cambiosDB]";
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
		$sql.="SELECT * FROM wi_historico_cambios ";
		if ($cEntidad->getFecCambio() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecCambio=" . $aux->qstr($cEntidad->getFecCambio(), false);
		}
		if ($cEntidad->getFuncionalidad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(funcionalidad) LIKE UPPER('%" . $cEntidad->getFuncionalidad() . "%')";
		}
		if ($cEntidad->getModo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(modo) LIKE UPPER('%" . $cEntidad->getModo() . "%')";
		}
		if ($cEntidad->getQuery() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(query) LIKE UPPER('%" . $cEntidad->getQuery() . "%')";
		}
		if ($cEntidad->getIp() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(ip) LIKE UPPER('%" . $cEntidad->getIp() . "%')";
		}
		if ($cEntidad->getIdUsuario() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(idUsuario) LIKE UPPER('%" . $cEntidad->getIdUsuario() . "%')";
		}
		if ($cEntidad->getIdUsuarioTipo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(idUsuarioTipo) LIKE UPPER('%" . $cEntidad->getIdUsuarioTipo() . "%')";
		}
		if ($cEntidad->getLogin() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(login) LIKE UPPER('%" . $cEntidad->getLogin() . "%')";
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
		$sql = "UPDATE wi_historico_cambios SET  ";
		return $retorno;
	}
}//Fin de la Clase Historico_cambiosDB
?>
