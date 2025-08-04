<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla candidatos_pagos_tpv
**/
class Candidatos_pagos_tpvDB
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
	
		$sql = "INSERT INTO candidatos_pagos_tpv (";
		$sql .= "idRecarga" . ",";
		$sql .= "idEmpresa" . ",";
		$sql .= "idProceso" . ",";
		$sql .= "idCandidato" . ",";
		$sql .= "localizador" . ",";
		$sql .= "descripcion" . ",";
		$sql .= "impBase" . ",";
		$sql .= "impImpuestos" . ",";
		$sql .= "impBaseImpuestos" . ",";
		$sql .= "email" . ",";
		$sql .= "nombre" . ",";
		$sql .= "apellidos" . ",";
		$sql .= "direccion" . ",";
		$sql .= "codPostal" . ",";
		$sql .= "ciudad" . ",";
		$sql .= "telefono1" . ",";
		$sql .= "codEstado" . ",";
		$sql .= "codAutorizacion" . ",";
		$sql .= "codError" . ",";
		$sql .= "desError" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($newId, false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdEmpresa(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdProceso(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdCandidato(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getLocalizador(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescripcion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getImpBase(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getImpImpuestos(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getImpBaseImpuestos(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getEmail(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNombre(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getApellidos(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDireccion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCodPostal(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCiudad(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getTelefono1(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCodEstado(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCodAutorizacion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCodError(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDesError(), false) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Candidatos_pagos_tpvDB]";
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
	
		$sql  = "SELECT MAX(idRecarga) AS Max FROM candidatos_pagos_tpv ";
		$sql  .=" WHERE idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$rs = $aux->Execute($sql);
		$newId=0;
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][Candidatos_pagos_tpvDB]";
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
	
		$sql = "UPDATE candidatos_pagos_tpv SET ";
		$sql .= "idRecarga=" . $aux->qstr($cEntidad->getIdRecarga(), false) . ", ";
		$sql .= "idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ", ";
		$sql .= "idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . ", ";
		$sql .= "idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . ", ";
		$sql .= "localizador=" . $aux->qstr($cEntidad->getLocalizador(), false) . ", ";
		$sql .= "descripcion=" . $aux->qstr($cEntidad->getDescripcion(), false) . ", ";
		$sql .= "impBase=" . $aux->qstr($cEntidad->getImpBase(), false) . ", ";
		$sql .= "impImpuestos=" . $aux->qstr($cEntidad->getImpImpuestos(), false) . ", ";
		$sql .= "impBaseImpuestos=" . $aux->qstr($cEntidad->getImpBaseImpuestos(), false) . ", ";
		$sql .= "email=" . $aux->qstr($cEntidad->getEmail(), false) . ", ";
		$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
		$sql .= "apellidos=" . $aux->qstr($cEntidad->getApellidos(), false) . ", ";
		$sql .= "direccion=" . $aux->qstr($cEntidad->getDireccion(), false) . ", ";
		$sql .= "codPostal=" . $aux->qstr($cEntidad->getCodPostal(), false) . ", ";
		$sql .= "ciudad=" . $aux->qstr($cEntidad->getCiudad(), false) . ", ";
		$sql .= "telefono1=" . $aux->qstr($cEntidad->getTelefono1(), false) . ", ";
		$sql .= "codEstado=" . $aux->qstr($cEntidad->getCodEstado(), false) . ", ";
		$sql .= "codAutorizacion=" . $aux->qstr($cEntidad->getCodAutorizacion(), false) . ", ";
		$sql .= "codError=" . $aux->qstr($cEntidad->getCodError(), false) . ", ";
		$sql .= "desError=" . $aux->qstr($cEntidad->getDesError(), false) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idRecarga=" . $aux->qstr($cEntidad->getIdRecarga(), false) . " AND idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][Candidatos_pagos_tpvDB]";
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
			//Borramos el registro de la Entidad.
			$sql  ="DELETE FROM candidatos_pagos_tpv ";
			$sql  .="WHERE ";
			if ($cEntidad->getIdRecarga() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idRecarga=" . $aux->qstr($cEntidad->getIdRecarga(), false) . " ";
			}
			if ($cEntidad->getIdEmpresa() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
			}
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][Candidatos_pagos_tpvDB]";
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
	
		$sql = "SELECT *  FROM candidatos_pagos_tpv WHERE ";
		$sql  .="idRecarga=" . $aux->qstr($cEntidad->getIdRecarga(), false) . " AND idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdRecarga($arr['idRecarga']);
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setIdProceso($arr['idProceso']);
					$cEntidad->setIdCandidato($arr['idCandidato']);
					$cEntidad->setLocalizador($arr['localizador']);
					$cEntidad->setDescripcion($arr['descripcion']);
					$cEntidad->setImpBase($arr['impBase']);
					$cEntidad->setImpImpuestos($arr['impImpuestos']);
					$cEntidad->setImpBaseImpuestos($arr['impBaseImpuestos']);
					$cEntidad->setEmail($arr['email']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setApellidos($arr['apellidos']);
					$cEntidad->setDireccion($arr['direccion']);
					$cEntidad->setCodPostal($arr['codPostal']);
					$cEntidad->setCiudad($arr['ciudad']);
					$cEntidad->setTelefono1($arr['telefono1']);
					$cEntidad->setCodEstado($arr['codEstado']);
					$cEntidad->setCodAutorizacion($arr['codAutorizacion']);
					$cEntidad->setCodError($arr['codError']);
					$cEntidad->setDesError($arr['desError']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][Candidatos_pagos_tpvDB]";
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
		$sql.="SELECT * FROM candidatos_pagos_tpv ";
		if ($cEntidad->getIdRecarga() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idRecarga=" . $aux->qstr($cEntidad->getIdRecarga(), false);
		}
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false);
		}
		if ($cEntidad->getIdProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false);
		}
		if ($cEntidad->getIdCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false);
		}
		if ($cEntidad->getLocalizador() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(localizador) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getLocalizador() . "%") . ")";
		}
		if ($cEntidad->getDescripcion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descripcion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescripcion() . "%") . ")";
		}
		if ($cEntidad->getImpBase() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="impBase>=" . $aux->qstr($cEntidad->getImpBase(), false);
		}
		if ($cEntidad->getImpBaseHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="impBase<=" . $aux->qstr($cEntidad->getImpBaseHast(), false);
		}
		if ($cEntidad->getImpImpuestos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="impImpuestos>=" . $aux->qstr($cEntidad->getImpImpuestos(), false);
		}
		if ($cEntidad->getImpImpuestosHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="impImpuestos<=" . $aux->qstr($cEntidad->getImpImpuestosHast(), false);
		}
		if ($cEntidad->getImpBaseImpuestos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="impBaseImpuestos>=" . $aux->qstr($cEntidad->getImpBaseImpuestos(), false);
		}
		if ($cEntidad->getImpBaseImpuestosHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="impBaseImpuestos<=" . $aux->qstr($cEntidad->getImpBaseImpuestosHast(), false);
		}
		if ($cEntidad->getEmail() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(email) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getEmail() . "%") . ")";
		}
		if ($cEntidad->getNombre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nombre) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNombre() . "%") . ")";
		}
		if ($cEntidad->getApellidos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(apellidos) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getApellidos() . "%") . ")";
		}
		if ($cEntidad->getDireccion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(direccion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDireccion() . "%") . ")";
		}
		if ($cEntidad->getCodPostal() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(codPostal) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCodPostal() . "%") . ")";
		}
		if ($cEntidad->getCiudad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(ciudad) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCiudad() . "%") . ")";
		}
		if ($cEntidad->getTelefono1() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(telefono1) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getTelefono1() . "%") . ")";
		}
		if ($cEntidad->getCodEstado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(codEstado) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCodEstado() . "%") . ")";
		}
		if ($cEntidad->getCodAutorizacion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(codAutorizacion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCodAutorizacion() . "%") . ")";
		}
		if ($cEntidad->getCodError() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(codError) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCodError() . "%") . ")";
		}
		if ($cEntidad->getDesError() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(desError) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDesError() . "%") . ")";
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

	function readListaIN($cEntidad)
	{
		$aux			= $this->conn;
	
		$sql="";
		$and = false;
		$sql.="SELECT * FROM candidatos_pagos_tpv ";
		if ($cEntidad->getIdRecarga() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idRecarga=" . $aux->qstr($cEntidad->getIdRecarga(), false);
		}
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresa IN(" . $cEntidad->getIdEmpresa() . ") ";
		}
		if ($cEntidad->getIdProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false);
		}
		if ($cEntidad->getIdCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false);
		}
		if ($cEntidad->getLocalizador() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(localizador) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getLocalizador() . "%") . ")";
		}
		if ($cEntidad->getDescripcion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descripcion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescripcion() . "%") . ")";
		}
		if ($cEntidad->getImpBase() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="impBase>=" . $aux->qstr($cEntidad->getImpBase(), false);
		}
		if ($cEntidad->getImpBaseHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="impBase<=" . $aux->qstr($cEntidad->getImpBaseHast(), false);
		}
		if ($cEntidad->getImpImpuestos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="impImpuestos>=" . $aux->qstr($cEntidad->getImpImpuestos(), false);
		}
		if ($cEntidad->getImpImpuestosHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="impImpuestos<=" . $aux->qstr($cEntidad->getImpImpuestosHast(), false);
		}
		if ($cEntidad->getImpBaseImpuestos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="impBaseImpuestos>=" . $aux->qstr($cEntidad->getImpBaseImpuestos(), false);
		}
		if ($cEntidad->getImpBaseImpuestosHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="impBaseImpuestos<=" . $aux->qstr($cEntidad->getImpBaseImpuestosHast(), false);
		}
		if ($cEntidad->getEmail() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(email) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getEmail() . "%") . ")";
		}
		if ($cEntidad->getNombre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nombre) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNombre() . "%") . ")";
		}
		if ($cEntidad->getApellidos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(apellidos) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getApellidos() . "%") . ")";
		}
		if ($cEntidad->getDireccion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(direccion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDireccion() . "%") . ")";
		}
		if ($cEntidad->getCodPostal() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(codPostal) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCodPostal() . "%") . ")";
		}
		if ($cEntidad->getCiudad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(ciudad) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCiudad() . "%") . ")";
		}
		if ($cEntidad->getTelefono1() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(telefono1) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getTelefono1() . "%") . ")";
		}
		if ($cEntidad->getCodEstado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(codEstado) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCodEstado() . "%") . ")";
		}
		if ($cEntidad->getCodAutorizacion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(codAutorizacion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCodAutorizacion() . "%") . ")";
		}
		if ($cEntidad->getCodError() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(codError) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCodError() . "%") . ")";
		}
		if ($cEntidad->getDesError() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(desError) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDesError() . "%") . ")";
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

		require_once(constant("DIR_WS_COM") . "Candidatos_pagos_tpv/Candidatos_pagos_tpv.php");
		$cEntidadAnterior	= new Candidatos_pagos_tpv();
		$cEntidadAnterior->setIdRecarga($cEntidad->getIdRecarga());
		$cEntidadAnterior->setIdEmpresa($cEntidad->getIdEmpresa());
		$cEntidadAnterior = $this->readEntidad($cEntidadAnterior);

		$sql = "UPDATE candidatos_pagos_tpv SET  ";
		return $retorno;
	}
}//Fin de la Clase Candidatos_pagos_tpvDB
?>