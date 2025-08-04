<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla export_personalidad
**/
class Export_personalidadDB
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

		$newId = $cEntidad->getIdEmpresa();
		$iCont = 0;
		$sql  = "SELECT COUNT(idEmpresa) AS Max FROM export_personalidad ";
		$sql .= "WHERE ";
		$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$iCont = $arr['Max'];
			}
		}
		if ($iCont > 0 ){
			//Existe el registro
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error Registro Existe [" . constant("MNT_ALTA") . "][Export_personalidadDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			return 0;
		}

		$sql = "INSERT INTO export_personalidad (";
		$sql .= "idEmpresa" . ",";
		$sql .= "descEmpresa" . ",";
		$sql .= "idProceso" . ",";
		$sql .= "descProceso" . ",";
		$sql .= "idCandidato" . ",";
		$sql .= "nombre" . ",";
		$sql .= "apellido1" . ",";
		$sql .= "apellido2" . ",";
		$sql .= "email" . ",";
		$sql .= "dni" . ",";
		$sql .= "idPrueba" . ",";
		$sql .= "descPrueba" . ",";
		$sql .= "fecPrueba" . ",";
		$sql .= "idBaremo" . ",";
		$sql .= "descBaremo" . ",";
		$sql .= "fecAltaProceso" . ",";
		$sql .= "correctas" . ",";
		$sql .= "contestadas" . ",";
		$sql .= "percentil" . ",";
		$sql .= "ir" . ",";
		$sql .= "ip" . ",";
		$sql .= "por" . ",";
		$sql .= "estilo" . ",";
		$sql .= "idSexo" . ",";
		$sql .= "descSexo" . ",";
		$sql .= "idEdad" . ",";
		$sql .= "descEdad" . ",";
		$sql .= "idFormacion" . ",";
		$sql .= "descFormacion" . ",";
		$sql .= "idNivel" . ",";
		$sql .= "descNivel" . ",";
		$sql .= "idArea" . ",";
		$sql .= "descArea" . ",";
		$sql .= "cobrado" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($cEntidad->getIdEmpresa(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescEmpresa(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdProceso(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescProceso(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdCandidato(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNombre(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getApellido1(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getApellido2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getEmail(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDni(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdPrueba(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescPrueba(), false) . ",";
		$sql .= $aux->DBDate($cEntidad->getFecPrueba()) . ",";
		$sql .= $aux->qstr($cEntidad->getIdBaremo(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescBaremo(), false) . ",";
		$sql .= $aux->DBDate($cEntidad->getFecAltaProceso()) . ",";
		$sql .= $aux->qstr($cEntidad->getCorrectas(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getContestadas(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPercentil(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIr(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIp(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPor(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getEstilo(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdSexo(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescSexo(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdEdad(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescEdad(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdFormacion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescFormacion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdNivel(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescNivel(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdArea(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescArea(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCobrado(), false) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Export_personalidadDB]";
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
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : $cEntidad->getIdEmpresa());
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : $cEntidad->getIdEmpresa());
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

		$sql  = "SELECT MAX() AS Max FROM export_personalidad ";
		$sql  .=" WHERE idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$rs = $aux->Execute($sql);
		$newId=0;
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][Export_personalidadDB]";
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

		$sql = "UPDATE export_personalidad SET ";
		$sql .= "idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ", ";
		$sql .= "descEmpresa=" . $aux->qstr($cEntidad->getDescEmpresa(), false) . ", ";
		$sql .= "idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . ", ";
		$sql .= "descProceso=" . $aux->qstr($cEntidad->getDescProceso(), false) . ", ";
		$sql .= "idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . ", ";
		$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
		$sql .= "apellido1=" . $aux->qstr($cEntidad->getApellido1(), false) . ", ";
		$sql .= "apellido2=" . $aux->qstr($cEntidad->getApellido2(), false) . ", ";
		$sql .= "email=" . $aux->qstr($cEntidad->getEmail(), false) . ", ";
		$sql .= "dni=" . $aux->qstr($cEntidad->getDni(), false) . ", ";
		$sql .= "idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . ", ";
		$sql .= "descPrueba=" . $aux->qstr($cEntidad->getDescPrueba(), false) . ", ";
		$sql .= "fecPrueba=" . $aux->DBDate($cEntidad->getFecPrueba()) . ",";
		$sql .= "idBaremo=" . $aux->qstr($cEntidad->getIdBaremo(), false) . ", ";
		$sql .= "descBaremo=" . $aux->qstr($cEntidad->getDescBaremo(), false) . ", ";
		$sql .= "fecAltaProceso=" . $aux->DBDate($cEntidad->getFecAltaProceso()) . ",";
		$sql .= "correctas=" . $aux->qstr($cEntidad->getCorrectas(), false) . ", ";
		$sql .= "contestadas=" . $aux->qstr($cEntidad->getContestadas(), false) . ", ";
		$sql .= "percentil=" . $aux->qstr($cEntidad->getPercentil(), false) . ", ";
		$sql .= "ir=" . $aux->qstr($cEntidad->getIr(), false) . ", ";
		$sql .= "ip=" . $aux->qstr($cEntidad->getIp(), false) . ", ";
		$sql .= "por=" . $aux->qstr($cEntidad->getPor(), false) . ", ";
		$sql .= "estilo=" . $aux->qstr($cEntidad->getEstilo(), false) . ", ";
		$sql .= "idSexo=" . $aux->qstr($cEntidad->getIdSexo(), false) . ", ";
		$sql .= "descSexo=" . $aux->qstr($cEntidad->getDescSexo(), false) . ", ";
		$sql .= "idEdad=" . $aux->qstr($cEntidad->getIdEdad(), false) . ", ";
		$sql .= "descEdad=" . $aux->qstr($cEntidad->getDescEdad(), false) . ", ";
		$sql .= "idFormacion=" . $aux->qstr($cEntidad->getIdFormacion(), false) . ", ";
		$sql .= "descFormacion=" . $aux->qstr($cEntidad->getDescFormacion(), false) . ", ";
		$sql .= "idNivel=" . $aux->qstr($cEntidad->getIdNivel(), false) . ", ";
		$sql .= "descNivel=" . $aux->qstr($cEntidad->getDescNivel(), false) . ", ";
		$sql .= "idArea=" . $aux->qstr($cEntidad->getIdArea(), false) . ", ";
		$sql .= "descArea=" . $aux->qstr($cEntidad->getDescArea(), false) . ", ";
		$sql .= "cobrado=" . $aux->qstr($cEntidad->getCobrado(), false) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][Export_personalidadDB]";
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
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : $cEntidad->getIdEmpresa());
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : $cEntidad->getIdEmpresa());
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
			//Borramos el registro de la Entidad.
			$sql  ="DELETE FROM export_personalidad ";
			$sql  .="WHERE ";
			if ($cEntidad->getIdEmpresa() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
			}
			if ($cEntidad->getIdProceso() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " ";
			}
			if ($cEntidad->getIdCandidato() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " ";
			}
			if ($cEntidad->getIdPrueba() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
			}
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][Export_personalidadDB]";
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
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : $cEntidad->getIdEmpresa());
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : $cEntidad->getIdEmpresa());
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

		$sql = "SELECT *  FROM export_personalidad WHERE ";
		$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setDescEmpresa($arr['descEmpresa']);
					$cEntidad->setIdProceso($arr['idProceso']);
					$cEntidad->setDescProceso($arr['descProceso']);
					$cEntidad->setIdCandidato($arr['idCandidato']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setApellido1($arr['apellido1']);
					$cEntidad->setApellido2($arr['apellido2']);
					$cEntidad->setEmail($arr['email']);
					$cEntidad->setDni($arr['dni']);
					$cEntidad->setIdPrueba($arr['idPrueba']);
					$cEntidad->setDescPrueba($arr['descPrueba']);
					$cEntidad->setFecPrueba($arr['fecPrueba']);
					$cEntidad->setIdBaremo($arr['idBaremo']);
					$cEntidad->setDescBaremo($arr['descBaremo']);
					$cEntidad->setFecAltaProceso($arr['fecAltaProceso']);
					$cEntidad->setCorrectas($arr['correctas']);
					$cEntidad->setContestadas($arr['contestadas']);
					$cEntidad->setPercentil($arr['percentil']);
					$cEntidad->setIr($arr['ir']);
					$cEntidad->setIp($arr['ip']);
					$cEntidad->setPor($arr['por']);
					$cEntidad->setEstilo($arr['estilo']);
					$cEntidad->setIdSexo($arr['idSexo']);
					$cEntidad->setDescSexo($arr['descSexo']);
					$cEntidad->setIdEdad($arr['idEdad']);
					$cEntidad->setDescEdad($arr['descEdad']);
					$cEntidad->setIdFormacion($arr['idFormacion']);
					$cEntidad->setDescFormacion($arr['descFormacion']);
					$cEntidad->setIdNivel($arr['idNivel']);
					$cEntidad->setDescNivel($arr['descNivel']);
					$cEntidad->setIdArea($arr['idArea']);
					$cEntidad->setDescArea($arr['descArea']);
					$cEntidad->setCobrado($arr['cobrado']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][Export_personalidadDB]";
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
		$sql.="SELECT * FROM export_personalidad ";
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresa IN (" . $cEntidad->getIdEmpresa() . ")";
		}
		if ($cEntidad->getDescEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descEmpresa) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescEmpresa() . "%") . ")";
		}
		if ($cEntidad->getIdProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			//$sql .="idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false);
			$sql .="idProceso IN (" . $cEntidad->getIdProceso() . ")";
		}
		if ($cEntidad->getDescProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
//			$sql .="UPPER(descProceso) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescProceso() . "%") . ")";
			$pos = strpos($cEntidad->getDescProceso(), ",");
			if ($pos === false) {
				$sql .="UPPER(descProceso) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescProceso() . "%") . ")";
				//$sql .="descProceso IN ('" . $cEntidad->getDescProceso() . "')";
			}else {
				$sql .="descProceso IN (" . $cEntidad->getDescProceso() . ")";
			}
		}
		if ($cEntidad->getIdCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false);
		}
		if ($cEntidad->getNombre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nombre) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNombre() . "%") . ")";
		}
		if ($cEntidad->getApellido1() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(apellido1) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getApellido1() . "%") . ")";
		}
		if ($cEntidad->getApellido2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(apellido2) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getApellido2() . "%") . ")";
		}
		if ($cEntidad->getEmail() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(email) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getEmail() . "%") . ")";
		}
		if ($cEntidad->getDni() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(dni) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDni() . "%") . ")";
		}
		if ($cEntidad->getIdPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPrueba IN (" . $cEntidad->getIdPrueba() . ")";
		}
		if ($cEntidad->getDescPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
// 			$pos = strpos($cEntidad->getDescPrueba(), ",");
// 			if ($pos === false) {
// 				$sql .="UPPER(descPrueba) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescPrueba() . "%") . ")";
// 			}else {
// 				$aDesc = explode(",", $cEntidad->getDescPrueba());
// 				$sOr = "";
// 				for ($i=0, $max = sizeof($aDesc); $i < $max; $i++){
// 					$sOr .=" OR UPPER(descPrueba) LIKE UPPER(" . $aux->qstr("%" . $aDesc[$i] . "%") . ")";
// 				}
// 				if (!empty($sOr)){
// 					$sOr = substr($sOr, 3);
// 				}
// 				$sql .=$sOr;
// 			}
			$sql .="descPrueba IN (" . $cEntidad->getDescPrueba() . ")";
		}
		if ($cEntidad->getFecPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecPrueba>=" . $aux->qstr($cEntidad->getFecPrueba() . " 00:00:00", false);
		}
		if ($cEntidad->getFecPruebaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecPrueba<=" . $aux->qstr($cEntidad->getFecPruebaHast() . " 23:59:59", false);
		}
		if ($cEntidad->getIdBaremo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idBaremo=" . $aux->qstr($cEntidad->getIdBaremo(), false);
		}
		if ($cEntidad->getDescBaremo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descBaremo) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescBaremo() . "%") . ")";
		}
		if ($cEntidad->getFecAltaProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAltaProceso>=" . $aux->qstr($cEntidad->getFecAltaProceso(), false);
		}
		if ($cEntidad->getFecAltaProcesoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAltaProceso<=" . $aux->qstr($cEntidad->getFecAltaProcesoHast(), false);
		}
		if ($cEntidad->getCorrectas() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(correctas) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCorrectas() . "%") . ")";
		}
		if ($cEntidad->getContestadas() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(contestadas) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getContestadas() . "%") . ")";
		}
		if ($cEntidad->getPercentil() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="percentil>=" . $aux->qstr($cEntidad->getPercentil(), false);
		}
		if ($cEntidad->getPercentilHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="percentil<=" . $aux->qstr($cEntidad->getPercentilHast(), false);
		}
		if ($cEntidad->getIr() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="ir>=" . $aux->qstr($cEntidad->getIr(), false);
		}
		if ($cEntidad->getIrHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="ir<=" . $aux->qstr($cEntidad->getIrHast(), false);
		}
		if ($cEntidad->getIp() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="ip>=" . $aux->qstr($cEntidad->getIp(), false);
		}
		if ($cEntidad->getIpHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="ip<=" . $aux->qstr($cEntidad->getIpHast(), false);
		}
		if ($cEntidad->getPor() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="por>=" . $aux->qstr($cEntidad->getPor(), false);
		}
		if ($cEntidad->getPorHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="por<=" . $aux->qstr($cEntidad->getPorHast(), false);
		}
		if ($cEntidad->getEstilo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(estilo) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getEstilo() . "%") . ")";
		}
		if ($cEntidad->getIdSexo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idSexo=" . $aux->qstr($cEntidad->getIdSexo(), false);
		}
		if ($cEntidad->getDescSexo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descSexo) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescSexo() . "%") . ")";
		}
		if ($cEntidad->getIdEdad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEdad=" . $aux->qstr($cEntidad->getIdEdad(), false);
		}
		if ($cEntidad->getDescEdad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descEdad) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescEdad() . "%") . ")";
		}
		if ($cEntidad->getIdFormacion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idFormacion=" . $aux->qstr($cEntidad->getIdFormacion(), false);
		}
		if ($cEntidad->getDescFormacion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descFormacion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescFormacion() . "%") . ")";
		}
		if ($cEntidad->getIdNivel() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idNivel=" . $aux->qstr($cEntidad->getIdNivel(), false);
		}
		if ($cEntidad->getDescNivel() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descNivel) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescNivel() . "%") . ")";
		}
		if ($cEntidad->getIdArea() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idArea=" . $aux->qstr($cEntidad->getIdArea(), false);
		}
		if ($cEntidad->getDescArea() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descArea) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescArea() . "%") . ")";
		}
		if ($cEntidad->getCobrado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="cobrado=" . $aux->qstr($cEntidad->getCobrado(), false);
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
// 		if (!empty($sql)){
// 			$sql .=" GROUP BY idEmpresa, idProceso, idCandidato, idPrueba, idBaremo ";
// 		}

		if ($cEntidad->getOrderBy() != ""){
			$sql .=" ORDER BY " . $cEntidad->getOrderBy();
			if ($cEntidad->getOrder() != ""){
				$sql .=" " . $cEntidad->getOrder();
			}
		}
		$this->sSQL=$sql;
		//echo $sql;
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

		require_once(constant("DIR_WS_COM") . "Export_personalidad/Export_personalidad.php");
		$cEntidadAnterior	= new Export_personalidad();
		$cEntidadAnterior->setIdEmpresa($cEntidad->getIdEmpresa());
		$cEntidadAnterior->setIdProceso($cEntidad->getIdProceso());
		$cEntidadAnterior->setIdCandidato($cEntidad->getIdCandidato());
		$cEntidadAnterior->setIdPrueba($cEntidad->getIdPrueba());
		$cEntidadAnterior = $this->readEntidad($cEntidadAnterior);

		$sql = "UPDATE export_personalidad SET  ";
		return $retorno;
	}
}//Fin de la Clase Export_personalidadDB
?>
