<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla peticiones_dongles
**/
class Peticiones_donglesDB
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
		$bOk=true;
		$newId = $this->getSiguienteId($cEntidad);

		$sql = "INSERT INTO peticiones_dongles (";
		$sql .= "idPeticion" . ",";
		$sql .= "idEmpresa" . ",";
		$sql .= "idEmpresaReceptora" . ",";
		$sql .= "nDongles" . ",";
		$sql .= "estado" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($newId, false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdEmpresa(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdEmpresaReceptora(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNDongles(), false) . ",";
		$sql .= $aux->qstr(intval($cEntidad->getEstado()), false) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
		if($aux->Execute($sql) === false){
			$bOk=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Peticiones_donglesDB]";
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
		if($bOk){
			$this->enviaEmailPeticion($cEntidad);
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

		$sql  = "SELECT MAX(idPeticion) AS Max FROM peticiones_dongles ";
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
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][Peticiones_donglesDB]";
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

		$sql = "UPDATE peticiones_dongles SET ";
		$sql .= "idPeticion=" . $aux->qstr($cEntidad->getIdPeticion(), false) . ", ";
		$sql .= "idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ", ";
		$sql .= "idEmpresaReceptora=" . $aux->qstr($cEntidad->getIdEmpresaReceptora(), false) . ", ";
		$sql .= "nDongles=" . $aux->qstr($cEntidad->getNDongles(), false) . ", ";
		$sql .= "estado=" . $aux->qstr(intval($cEntidad->getEstado()), false) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idPeticion=" . $aux->qstr($cEntidad->getIdPeticion(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][Peticiones_donglesDB]";
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
		if($retorno){
			if($cEntidad->getEstado()=="1"){
				require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
				require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");

				$cEmpresa = new Empresas();
				$cEmpresaDB = new EmpresasDB($aux);
				$cEmpresa->setIdEmpresa($cEntidad->getIdEmpresa());
				$cEmpresa = $cEmpresaDB->readEntidadSinPass($cEmpresa);
				$cEmpresa->setDongles($cEmpresa->getDongles() + $cEntidad->getNDongles());
				$cEmpresaDB->modificar($cEmpresa);
			}
			if($cEntidad->getEstado()!="0"){
				$this->enviaEmailAceptacionRechazo($cEntidad);
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
			$sql  ="DELETE FROM peticiones_dongles ";
			$sql  .="WHERE ";
			if ($cEntidad->getIdPeticion() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idPeticion=" . $aux->qstr($cEntidad->getIdPeticion(), false) . " ";
			}
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][Peticiones_donglesDB]";
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

		$sql = "SELECT *  FROM peticiones_dongles WHERE ";
		$sql  .="idPeticion=" . $aux->qstr($cEntidad->getIdPeticion(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdPeticion($arr['idPeticion']);
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setIdEmpresaReceptora($arr['idEmpresaReceptora']);
					$cEntidad->setNDongles($arr['nDongles']);
					$cEntidad->setEstado($arr['estado']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][Peticiones_donglesDB]";
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
		$sql.="SELECT * FROM peticiones_dongles ";
		if ($cEntidad->getIdPeticion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPeticion=" . $aux->qstr($cEntidad->getIdPeticion(), false);
		}
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false);
		}
		if ($cEntidad->getIdEmpresaReceptora() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresaReceptora>=" . $aux->qstr($cEntidad->getIdEmpresaReceptora(), false);
		}
		if ($cEntidad->getIdEmpresaReceptoraHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresaReceptora<=" . $aux->qstr($cEntidad->getIdEmpresaReceptoraHast(), false);
		}
		if ($cEntidad->getNDongles() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="nDongles>=" . $aux->qstr($cEntidad->getNDongles(), false);
		}
		if ($cEntidad->getNDonglesHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="nDongles<=" . $aux->qstr($cEntidad->getNDonglesHast(), false);
		}
		if ($cEntidad->getEstado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="estado>=" . $aux->qstr($cEntidad->getEstado(), false);
		}
		if ($cEntidad->getEstadoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="estado<=" . $aux->qstr($cEntidad->getEstadoHast(), false);
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

	/*************************************************************************
	* Lista en la base de datos recogiendo la información
	* recibida por la entidad, Este metodo se utiliza para búsquedas
	* de uno o varios registros.
	* @param entidad Entidad con la información de la Búsqueda
	* @return Vector Lista recuperada
	*************************************************************************/
	function readListaIN($cEntidad)
	{
		$aux			= $this->conn;

		$sql="";
		$and = false;
		$or = false;
		$sql.="SELECT * FROM peticiones_dongles ";
		if ($cEntidad->getIdPeticion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPeticion=" . $aux->qstr($cEntidad->getIdPeticion(), false);
		}
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$or=true;
			$sql .="idEmpresa IN (" . $cEntidad->getIdEmpresa() . ") ";
		}
		if ($cEntidad->getIdEmpresaReceptora() != ""){
			$sql .= $this->getSQLOR($or);
			$and = true;
			$sql .="idEmpresaReceptora>=" . $aux->qstr($cEntidad->getIdEmpresaReceptora(), false);
		}
		if ($cEntidad->getIdEmpresaReceptoraHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresaReceptora<=" . $aux->qstr($cEntidad->getIdEmpresaReceptoraHast(), false);
		}
		if ($cEntidad->getNDongles() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="nDongles>=" . $aux->qstr($cEntidad->getNDongles(), false);
		}
		if ($cEntidad->getNDonglesHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="nDongles<=" . $aux->qstr($cEntidad->getNDonglesHast(), false);
		}
		if ($cEntidad->getEstado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="estado>=" . $aux->qstr($cEntidad->getEstado(), false);
		}
		if ($cEntidad->getEstadoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="estado<=" . $aux->qstr($cEntidad->getEstadoHast(), false);
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
//		echo $sql;
//		exit;
		$this->sSQL=$sql;
		return $sql;
	}
	function getSQLWhere($bFlag)
	{
		if (!$bFlag)	return " WHERE ";
		else	return " AND ";
	}

	function getSQLOR($bFlag)
	{
		if (!$bFlag)	return " WHERE ";
		else	return " OR ";
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

		$sql = "UPDATE peticiones_dongles SET  ";
		return $retorno;
	}

	function enviaEmailAceptacionRechazo($cEntidad){
		$aux			= $this->conn;
		require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
		require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
		require_once(constant("DIR_WS_COM") . "Notificaciones/NotificacionesDB.php");
		require_once(constant("DIR_WS_COM") . "Notificaciones/Notificaciones.php");
		require_once(constant("DIR_WS_COM") . "Notificaciones_tipos/Notificaciones_tiposDB.php");
		require_once(constant("DIR_WS_COM") . "Notificaciones_tipos/Notificaciones_tipos.php");


		$cNotificacionesDB = new NotificacionesDB($aux);
		$cNotificaciones = new Notificaciones();

		$cNotificaciones_tiposDB = new Notificaciones_tiposDB($aux);
		$cNotificaciones_tipos = new Notificaciones_tipos();

		$cEmpresaDB = new EmpresasDB($aux);

		$cEmpresa = new Empresas();
		$cEmpresa->setIdEmpresa($cEntidad->getIdEmpresa());
		$cEmpresa = $cEmpresaDB->readEntidad($cEmpresa);

		$cEmpresaPadre = new Empresas();
		$cEmpresaPadre->setIdEmpresa($cEntidad->getIdEmpresaReceptora());
		$cEmpresaPadre = $cEmpresaDB->readEntidad($cEmpresaPadre);

		if($cEntidad->getEstado()=="1"){
			$cNotificaciones_tipos->setIdTipoNotificacion("6");
			$cNotificaciones->setIdTipoNotificacion("6");
		}
		if($cEntidad->getEstado()=="2"){
			$cNotificaciones_tipos->setIdTipoNotificacion("7");
			$cNotificaciones->setIdTipoNotificacion("7");
		}
		$cNotificaciones->setIdNotificacion("1");
		$cNotificaciones = $cNotificacionesDB->readEntidad($cNotificaciones);

		$cNotificaciones = $cNotificacionesDB->parseaHTML($cNotificaciones,$cEmpresaPadre , null,null ,null ,null ,$cEntidad ,null ,null);

		$sSubject=$cNotificaciones->getAsunto();
		$sBody=$cNotificaciones->getCuerpo();
		$sAltBody=strip_tags($cNotificaciones->getCuerpo());
		require_once constant("DIR_WS_COM") . 'PHPMailer/Exception.php';
		require_once constant("DIR_WS_COM") . 'PHPMailer/PHPMailer.php';
		require_once constant("DIR_WS_COM") . 'PHPMailer/SMTP.php';

		//instanciamos un objeto de la clase phpmailer al que llamamos
		//por ejemplo mail
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);  //PHPMailer instance with exceptions enabled
		$mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
		try {
			//Server settings
			//$mail->SMTPDebug = 2; 					                //Enable verbose debug output
			$mail->isSMTP();                                        //Send using SMTP                  
			$mail->Host = constant("HOSTMAIL");						//Set the SMTP server to send through
			$mail->SMTPAuth   = true;                               //Enable SMTP authentication
			$mail->Username = constant("MAILUSERNAME");             //SMTP username
			$mail->Password = constant("MAILPASSWORD");             //SMTP password
			$mail->SMTPSecure = constant("MAIL_ENCRYPTION");							    //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port      = constant("PORTMAIL");                                //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above


			$mail->CharSet = 'utf-8';
			$mail->Debugoutput = 'html';

			// Borro las direcciones de destino establecidas anteriormente
			$mail->clearAllRecipients();

			//Con la propiedad Mailer le indicamos que vamos a usar un
			//servidor smtp
			$mail->Mailer = constant("MAILER");

			//Asignamos a Host el nombre de nuestro servidor smtp
			$mail->Host = constant("HOSTMAIL");

			//Le indicamos que el servidor smtp requiere autenticaciÃ³n
			$mail->SMTPAuth = true;

			//Le decimos cual es nuestro nombre de usuario y password
			$mail->Username = constant("MAILUSERNAME");
			$mail->Password = constant("MAILPASSWORD");

			//Indicamos cual es nuestra dirección de correo y el nombre que
			//queremos que vea el usuario que lee nuestro correo
			//$mail->From = $cEmpresaPadre->getMail();
			$mail->From = constant("EMAIL_CONTACTO");
			$mail->AddReplyTo($cEmpresaPadre->getMail(), $cEmpresaPadre->getNombre());
			$mail->FromName = $cEmpresaPadre->getNombre();
				$nomEmpresa = $cEmpresaPadre->getNombre(); 

			//Asignamos asunto y cuerpo del mensaje
			//El cuerpo del mensaje lo ponemos en formato html, haciendo
			//que se vea en negrita
			$mail->Subject = $nomEmpresa . " - " . $sSubject;
			$mail->Body = $sBody;

			//Definimos AltBody por si el destinatario del correo no admite
			//email con formato html
			$mail->AltBody = $sAltBody;

			//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar
			//una cuenta gratuita y voy a usar attachments, por tanto lo pongo a 120
			$mail->Timeout=120;

			//Indicamos el fichero a adjuntar si el usuario seleccionÃ³ uno en el formulario
			$archivo="none";
			if ($archivo !="none") {
				$mail->AddAttachment($archivo,$archivo_name);
			}

			//Indicamos cuales son las direcciones de destino del correo
			$mail->AddAddress($cEmpresa->getMail(), $cEmpresa->getNombre());
			if($cEmpresa->getMail2()!=""){
				$mail->AddAddress($cEmpresa->getMail2(), $cEmpresa->getNombre());
			}
			if($cEmpresa->getMail3()!=""){
				$mail->AddAddress($cEmpresa->getMail3(), $cEmpresa->getNombre());
			}

			//se envia el mensaje, si no ha habido problemas la variable $success
			//tendra el valor true
			$exito=false;
			//Si el mensaje no ha podido ser enviado se realizaran 2 intentos mas
			//como mucho para intentar enviar el mensaje, cada intento se hara 2 s
			//segundos despues del anterior, para ello se usa la funcion sleep
			$intentos=1;
			while((!$exito)&&($intentos<2)&&($mail->ErrorInfo!="SMTP Error: Data not accepted"))
			{
			sleep(2);
				//echo $mail->ErrorInfo;
				$exito = $mail->Send();
				$intentos=$intentos+1;
			}

			//La clase phpmailer tiene un pequeño bug y es que cuando envia un mail con
			//attachment la variable ErrorInfo adquiere el valor Data not accepted, dicho
			//valor no debe confundirnos ya que el mensaje ha sido enviado correctamente
			if ($mail->ErrorInfo=="SMTP Error: Data not accepted") {
				$exito=true;
			}
			// Borro las direcciones de destino establecidas anteriormente
			$mail->ClearAddresses();
		} catch (PHPMailer\PHPMailer\Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";exit;
		}
	    return $exito;
	}
	function enviaEmailPeticion($cEntidad){
		$aux			= $this->conn;
		require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
		require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
		require_once(constant("DIR_WS_COM") . "Notificaciones/NotificacionesDB.php");
		require_once(constant("DIR_WS_COM") . "Notificaciones/Notificaciones.php");
		require_once(constant("DIR_WS_COM") . "Notificaciones_tipos/Notificaciones_tiposDB.php");
		require_once(constant("DIR_WS_COM") . "Notificaciones_tipos/Notificaciones_tipos.php");


		$cNotificacionesDB = new NotificacionesDB($aux);
		$cNotificaciones = new Notificaciones();

		$cNotificaciones_tiposDB = new Notificaciones_tiposDB($aux);
		$cNotificaciones_tipos = new Notificaciones_tipos();

		$cEmpresaSolicitaDB = new EmpresasDB($aux);
		$cEmpresaTramitaDB = new EmpresasDB($aux);

		$cEmpresaSolicita = new Empresas();
		$cEmpresaSolicita->setIdEmpresa($cEntidad->getIdEmpresa());
		$cEmpresaSolicita = $cEmpresaSolicitaDB->readEntidad($cEmpresaSolicita);

		$cEmpresaTramita = new Empresas();
		$cEmpresaTramita->setIdEmpresa($cEntidad->getIdEmpresaReceptora());
		$cEmpresaTramita = $cEmpresaTramitaDB->readEntidad($cEmpresaTramita);
		$cNotificaciones->setIdTipoNotificacion("5");

		$cNotificaciones->setIdNotificacion("1");
		$cNotificaciones = $cNotificacionesDB->readEntidad($cNotificaciones);

		$cNotificaciones = $cNotificacionesDB->parseaHTML($cNotificaciones,$cEmpresaSolicita , null,null ,null ,null ,$cEntidad ,null ,null);

		$sSubject=$cNotificaciones->getAsunto();
		$sBody=$cNotificaciones->getCuerpo();
		$sAltBody=strip_tags($cNotificaciones->getCuerpo());

		require_once constant("DIR_WS_COM") . 'PHPMailer/Exception.php';
		require_once constant("DIR_WS_COM") . 'PHPMailer/PHPMailer.php';
		require_once constant("DIR_WS_COM") . 'PHPMailer/SMTP.php';

		//instanciamos un objeto de la clase phpmailer al que llamamos
		//por ejemplo mail
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);  //PHPMailer instance with exceptions enabled
		$mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
		try {
			//Server settings
			//$mail->SMTPDebug = 2; 					                //Enable verbose debug output
			$mail->isSMTP();                                        //Send using SMTP                  
			$mail->Host = constant("HOSTMAIL");						//Set the SMTP server to send through
			$mail->SMTPAuth   = true;                               //Enable SMTP authentication
			$mail->Username = constant("MAILUSERNAME");             //SMTP username
			$mail->Password = constant("MAILPASSWORD");             //SMTP password
			$mail->SMTPSecure = constant("MAIL_ENCRYPTION");							    //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port      = constant("PORTMAIL");                                //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above


			$mail->CharSet = 'utf-8';
			$mail->Debugoutput = 'html';

			// Borro las direcciones de destino establecidas anteriormente
			$mail->clearAllRecipients();

			//Con la propiedad Mailer le indicamos que vamos a usar un
			//servidor smtp
			$mail->Mailer = constant("MAILER");

			//Asignamos a Host el nombre de nuestro servidor smtp
			$mail->Host = constant("HOSTMAIL");

			//Le indicamos que el servidor smtp requiere autenticaciÃ³n
			$mail->SMTPAuth = true;

			//Le decimos cual es nuestro nombre de usuario y password
			$mail->Username = constant("MAILUSERNAME");
			$mail->Password = constant("MAILPASSWORD");

			//Indicamos cual es nuestra dirección de correo y el nombre que
			//queremos que vea el usuario que lee nuestro correo
			//$mail->From = $cEmpresaSolicita->getMail();
			$mail->From = constant("EMAIL_CONTACTO");
			$mail->AddReplyTo($cEmpresaSolicita->getMail(), $cEmpresaSolicita->getNombre());
			$mail->FromName = $cEmpresaSolicita->getNombre();
				$nomEmpresa = $cEmpresaSolicita->getNombre();

			//Asignamos asunto y cuerpo del mensaje
			//El cuerpo del mensaje lo ponemos en formato html, haciendo
			//que se vea en negrita
			$mail->Subject = $nomEmpresa . " - " . $sSubject;
			$mail->Body = $sBody;

			//Definimos AltBody por si el destinatario del correo no admite
			//email con formato html
			$mail->AltBody = $sAltBody;

			//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar
			//una cuenta gratuita y voy a usar attachments, por tanto lo pongo a 120
			$mail->Timeout=120;

			//Indicamos el fichero a adjuntar si el usuario seleccionÃ³ uno en el formulario
			$archivo="none";
			if ($archivo !="none") {
				$mail->AddAttachment($archivo,$archivo_name);
			}
			//Indicamos cuales son las direcciones de destino del correo
			$mail->AddAddress($cEmpresaTramita->getMail(), $cEmpresaTramita->getNombre());

			//se envia el mensaje, si no ha habido problemas la variable $success
			//tendra el valor true
			$exito=false;
			//Si el mensaje no ha podido ser enviado se realizaran 2 intentos mas
			//como mucho para intentar enviar el mensaje, cada intento se hara 2 s
			//segundos despues del anterior, para ello se usa la funcion sleep
			$intentos=1;
			while((!$exito)&&($intentos<2)&&($mail->ErrorInfo!="SMTP Error: Data not accepted"))
			{
			sleep(2);
				//echo $mail->ErrorInfo;
				$exito = $mail->Send();
				$intentos=$intentos+1;
			}

			//La clase phpmailer tiene un pequeño bug y es que cuando envia un mail con
			//attachment la variable ErrorInfo adquiere el valor Data not accepted, dicho
			//valor no debe confundirnos ya que el mensaje ha sido enviado correctamente
			if ($mail->ErrorInfo=="SMTP Error: Data not accepted") {
				$exito=true;
			}
			// Borro las direcciones de destino establecidas anteriormente
			$mail->ClearAddresses();
		} catch (PHPMailer\PHPMailer\Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";exit;
		}
	    return $exito;
	}
}//Fin de la Clase Peticiones_donglesDB
?>
