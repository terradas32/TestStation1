<?php 
/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla sec_respuestas_dimensiones
**/
class Sec_respuestasdimensionesDB 
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
	function __construct($conn)
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
		
		$iCont;
		$sql = "";
		
			//$newId = String.valueOf((Integer.parseInt(getSiguienteId(conConexion,"SELECT " . SQL.cGetId("IDRESPUESTA") . " FROM sec_respuestas_dimensiones"))));
		$newId = $this->getSiguienteId($cEntidad);		
	
		$sql = "INSERT INTO sec_respuestas_dimensiones (";
		$sql .= "IDRESPUESTA" . ",";
		$sql .= "IDCANDIDATO" . ",";
		$sql .= "IDPROCESO" . ",";
		$sql .= "DNI" . ",";
		$sql .= "IDCOMPETENCIA" . ",";
		$sql .= "CODCOMPETENCIA" . ",";
		$sql .= "IDDIMENSION" . ",";
		$sql .= "CODDIMENSION" . ",";
		$sql .= "DESCDIMENSION" . ",";
		$sql .= "PUNTUACIONDIRECTA" . ",";
		$sql .= "PUNTSOBRE10" . ",";
		$sql .= "MEDIAPONDERADA" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($newId) . ",";
		$sql .= $aux->qstr($cEntidad->getIDCANDIDATO()) . ",";
		$sql .= $aux->qstr($cEntidad->getIDPROCESO()) . ",";
		$sql .= $aux->qstr($cEntidad->getDNI()) . ",";
		$sql .= $aux->qstr($cEntidad->getIDCOMPETENCIA()) . ",";
		$sql .= $aux->qstr($cEntidad->getCODCOMPETENCIA()) . ",";
		$sql .= $aux->qstr($cEntidad->getIDDIMENSION()) . ",";
		$sql .= $aux->qstr($cEntidad->getCODDIMENSION()) . ",";
		$sql .= $aux->qstr($cEntidad->getDESCDIMENSION()) . ",";
		$sql .= $aux->qstr(str_replace("'", "", $cEntidad->getPUNTUACIONDIRECTA())) . ",";
		$sql .= $aux->qstr(str_replace("'", "", $cEntidad->getPUNTSOBRE10())) . ",";
		$sql .= $aux->qstr(str_replace("'", "", $cEntidad->getMEDIAPONDERADA())) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= "0" . ",";
		$sql .= "0" . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][OpcionesDB]";
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
		
	}	// insertar
	
	/******************************************************************************************
	* Devuelve una cadena para insertar un valor de 
	* secuencia clave de tipo ID.
	* @return String nuevo id.
	*****************************************************************************************/
	function getSiguienteId($cEntidad)
	{
		$aux			= $this->conn;
	
		$sql  = "SELECT MAX(IDRESPUESTA) AS Max FROM sec_respuestas_dimensiones ";
		$sql  .="";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][OpcionesDB]";
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
	function  modificar($cEntidad)
	{
		$aux			= $this->conn;
	
		$sql = "UPDATE sec_respuestas_dimensiones SET ";
		$sql .= "IDRESPUESTA=" . $aux->qstr($cEntidad->getIDRESPUESTA()) . ", ";
		$sql .= "IDCANDIDATO=" . $aux->qstr($cEntidad->getIDCANDIDATO()) . ", ";
		$sql .= "IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO()) . ", ";
		$sql .= "DNI=" . $aux->qstr($cEntidad->getDNI()) . ", ";
		$sql .= "IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA()) . ", ";
		$sql .= "CODCOMPETENCIA=" . $aux->qstr($cEntidad->getCODCOMPETENCIA()) . ", ";
		$sql .= "IDDIMENSION=" . $aux->qstr($cEntidad->getIDDIMENSION()) . ", ";
		$sql .= "CODDIMENSION=" . $aux->qstr($cEntidad->getCODDIMENSION()) . ", ";
		$sql .= "DESCDIMENSION=" . $aux->qstr($cEntidad->getDESCDIMENSION()) . ", ";
		$sql .= "PUNTUACIONDIRECTA=" . $aux->qstr($cEntidad->getPUNTUACIONDIRECTA()) . ", ";
		$sql .= "PUNTSOBRE10=" . $aux->qstr($cEntidad->getPUNTSOBRE10()) . ", ";
		$sql .= "MEDIAPONDERADA=" . $aux->qstr($cEntidad->getMEDIAPONDERADA()) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod()) ;
		$sql .= " WHERE ";
		$sql .="IDRESPUESTA=" . $aux->qstr($cEntidad->getIDRESPUESTA()) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][OpcionesDB]";
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
			//Borramos el registro de la Entidad.
			$sql  ="DELETE FROM sec_respuestas_dimensiones ";
			$sql  .=" WHERE ";
			if ($cEntidad->getIDRESPUESTA() != ""){
				$sql .= this.getSQLAnd($and);
				$and = true;
				$sql  .="IDRESPUESTA=" . $aux->qstr($cEntidad->getIDRESPUESTA()) . " ";
			}
		if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][OpcionesDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
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
	}	// Borrar
	
	/*************************************************************************
	* Borra una entidad en la base de datos.
	* @param entidad Entidad a borrar contiene los datos de condición
	* @return boolean Estado del borrado
	* @exception Exception Error al ejecutar la acción
	*  en la base de datos
	************************************************************************/
	function borrarDim($cEntidad) 
	{
		$aux			= $this->conn;
		$this->msg_Error			= array();
		$and			= false;
		$retorno			= true;
		
			//Borramos el registro de la Entidad.
			$sql  ="DELETE FROM sec_respuestas_dimensiones ";
			$sql  .=" WHERE ";
			if ($cEntidad->getIDCANDIDATO() != ""){
				$sql .= this.getSQLAnd($and);
				$and = true;
				$sql  .="IDCANDIDATO=" . $aux->qstr($cEntidad->getIDCANDIDATO()) . " ";
			}
			if ($cEntidad->getIDPROCESO() != ""){
				$sql .= this.getSQLAnd($and);
				$and = true;
				$sql  .="IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO()) . " ";
			}
			if ($cEntidad->getDNI() != null && $cEntidad->getDNI() != ""){
				$sql .= this.getSQLWhere($and);
				$and = true;
				$sql .="UPPER(DNI) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDNI() . "%") . ")";
			}
			if ($cEntidad->getIDCOMPETENCIA() != ""){
				$sql .= this.getSQLAnd($and);
				$and = true;
				$sql  .="IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA()) . " ";
			}
			if ($cEntidad->getCODCOMPETENCIA() != ""){
				$sql .= this.getSQLAnd($and);
				$and = true;
				$sql  .="CODCOMPETENCIA=" . $aux->qstr($cEntidad->getCODCOMPETENCIA()) . " ";
			}
			if ($cEntidad->getIDDIMENSION() != ""){
				$sql .= this.getSQLAnd($and);
				$and = true;
				$sql  .="IDDIMENSION=" . $aux->qstr($cEntidad->getIDDIMENSION()) . " ";
			}
			if ($cEntidad->getCODDIMENSION() != ""){
				$sql .= this.getSQLAnd($and);
				$and = true;
				$sql  .="CODDIMENSION=" . $aux->qstr($cEntidad->getCODDIMENSION()) . " ";
			}
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][OpcionesDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}
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
	}	// Borrar
	
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
	function consultar($cEntidad)
	{
		
		$aux			= $this->conn;
		
	
		$sql = "SELECT IDRESPUESTA,IDCANDIDATO,IDPROCESO,DNI,IDCOMPETENCIA,CODCOMPETENCIA,IDDIMENSION,CODDIMENSION,DESCDIMENSION,PUNTUACIONDIRECTA,PUNTSOBRE10,MEDIAPONDERADA," . "fecAlta" . "," . "fecMod" . ",usuAlta,usuMod FROM sec_respuestas_dimensiones WHERE ";
		$sql  .="IDRESPUESTA=" . $aux->qstr($cEntidad->getIDRESPUESTA()) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
		
					$cEntidad->setIDRESPUESTA($arr['IDRESPUESTA']);
					$cEntidad->setIDCANDIDATO($arr['IDCANDIDATO']);
					$cEntidad->setIDPROCESO($arr['IDPROCESO']);
					$cEntidad->setDNI($arr['DNI']);
					$cEntidad->setIDCOMPETENCIA($arr['IDCOMPETENCIA']);
					$cEntidad->setCODCOMPETENCIA($arr['CODCOMPETENCIA']);
					$cEntidad->setIDDIMENSION($arr['IDDIMENSION']);
					$cEntidad->setCODDIMENSION($arr['CODDIMENSION']);
					$cEntidad->setDESCDIMENSION($arr['DESCDIMENSION']);
					$cEntidad->setPUNTUACIONDIRECTA($arr['PUNTUACIONDIRECTA']);
					$cEntidad->setPUNTSOBRE10($arr['PUNTSOBRE10']);
					$cEntidad->setMEDIAPONDERADA($arr['MEDIAPONDERADA']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][OpcionesDB]";
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
	function consultaCompleta($cEntidad)
	{
		
		$aux			= $this->conn;
	
		$sql = "SELECT IDRESPUESTA,IDCANDIDATO,IDPROCESO,DNI,IDCOMPETENCIA,CODCOMPETENCIA,IDDIMENSION,CODDIMENSION,DESCDIMENSION,PUNTUACIONDIRECTA,PUNTSOBRE10,MEDIAPONDERADA," . "fecAlta" . "," . "fecMod" . ",usuAlta,usuMod FROM sec_respuestas_dimensiones WHERE ";
		$sql  .="IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO()) . " AND IDCANDIDATO=" . $aux->qstr($cEntidad->getIDCANDIDATO()) . " AND CODDIMENSION = " . $aux->qstr($cEntidad->getCODDIMENSION()) +" AND UPPER(DESCDIMENSION) LIKE UPPER('%" . $cEntidad->getDESCDIMENSION() . "%') AND UPPER(DNI) LIKE UPPER('%" . $cEntidad->getDNI() . "%') ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
		
					$cEntidad->setIDRESPUESTA($arr['IDRESPUESTA']);
					$cEntidad->setIDCANDIDATO($arr['IDCANDIDATO']);
					$cEntidad->setIDPROCESO($arr['IDPROCESO']);
					$cEntidad->setDNI($arr['DNI']);
					$cEntidad->setIDCOMPETENCIA($arr['IDCOMPETENCIA']);
					$cEntidad->setCODCOMPETENCIA($arr['CODCOMPETENCIA']);
					$cEntidad->setIDDIMENSION($arr['IDDIMENSION']);
					$cEntidad->setCODDIMENSION($arr['CODDIMENSION']);
					$cEntidad->setDESCDIMENSION($arr['DESCDIMENSION']);
					$cEntidad->setPUNTUACIONDIRECTA($arr['PUNTUACIONDIRECTA']);
					$cEntidad->setPUNTSOBRE10($arr['PUNTSOBRE10']);
					$cEntidad->setMEDIAPONDERADA($arr['MEDIAPONDERADA']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][OpcionesDB]";
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
	
		$sql="";
		$and = false;
		
		$sql.="SELECT * FROM sec_respuestas_dimensiones ";
		if ($cEntidad->getIDRESPUESTA() != null && $cEntidad->getIDRESPUESTA() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="IDRESPUESTA=" . $aux->qstr($cEntidad->getIDRESPUESTA());
		}
		if ($cEntidad->getIDCANDIDATO() != null && $cEntidad->getIDCANDIDATO() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="IDCANDIDATO=" . $aux->qstr($cEntidad->getIDCANDIDATO());
		}
		if ($cEntidad->getIDCANDIDATOHast() != null && $cEntidad->getIDCANDIDATOHast() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="IDCANDIDATO<=" . $aux->qstr($cEntidad->getIDCANDIDATOHast());
		}
		if ($cEntidad->getDNI() != null && $cEntidad->getDNI() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="UPPER(DNI) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDNI() . "%") . ")";
		}
		if ($cEntidad->getIDPROCESO() != null && $cEntidad->getIDPROCESO() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="IDPROCESO>=" . $aux->qstr($cEntidad->getIDPROCESO());
		}
		if ($cEntidad->getIDPROCESOHast() != null && $cEntidad->getIDPROCESOHast() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="IDPROCESO<=" . $aux->qstr($cEntidad->getIDPROCESOHast());
		}
		if ($cEntidad->getIDCOMPETENCIA() != null && $cEntidad->getIDCOMPETENCIA() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="IDCOMPETENCIA>=" . $aux->qstr($cEntidad->getIDCOMPETENCIA());
		}
		if ($cEntidad->getIDCOMPETENCIAHast() != null && $cEntidad->getIDCOMPETENCIAHast() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="IDCOMPETENCIA<=" . $aux->qstr($cEntidad->getIDCOMPETENCIAHast());
		}
		if ($cEntidad->getCODCOMPETENCIA() != null && $cEntidad->getCODCOMPETENCIA() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="UPPER(CODCOMPETENCIA) LIKE UPPER('%" . $cEntidad->getCODCOMPETENCIA() . "')";
		}
		if ($cEntidad->getIDDIMENSION() != null && $cEntidad->getIDDIMENSION() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="IDDIMENSION>=" . $aux->qstr($cEntidad->getIDDIMENSION());
		}
		if ($cEntidad->getIDDIMENSIONHast() != null && $cEntidad->getIDDIMENSIONHast() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="IDDIMENSION<=" . $aux->qstr($cEntidad->getIDDIMENSIONHast());
		}
		if ($cEntidad->getCODDIMENSION() != null && $cEntidad->getCODDIMENSION() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="UPPER(CODDIMENSION) LIKE UPPER('%" . $cEntidad->getCODDIMENSION() . "')";
		}
		if ($cEntidad->getDESCDIMENSION() != null && $cEntidad->getDESCDIMENSION() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="UPPER(DESCDIMENSION) LIKE UPPER('%" . $cEntidad->getDESCDIMENSION() . "%')";
		}
		if ($cEntidad->getPUNTUACIONDIRECTA() != null && $cEntidad->getPUNTUACIONDIRECTA() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="PUNTUACIONDIRECTA>=" . $aux->qstr($cEntidad->getPUNTUACIONDIRECTA());
		}
		if ($cEntidad->getPUNTUACIONDIRECTAHast() != null && $cEntidad->getPUNTUACIONDIRECTAHast() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="PUNTUACIONDIRECTA<=" . $aux->qstr($cEntidad->getPUNTUACIONDIRECTAHast());
		}
		if ($cEntidad->getPUNTSOBRE10() != null && $cEntidad->getPUNTSOBRE10() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="PUNTSOBRE10>=" . $aux->qstr($cEntidad->getPUNTSOBRE10());
		}
		if ($cEntidad->getPUNTSOBRE10Hast() != null && $cEntidad->getPUNTSOBRE10Hast() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="PUNTSOBRE10<=" . $aux->qstr($cEntidad->getPUNTSOBRE10Hast());
		}
		if ($cEntidad->getMEDIAPONDERADA() != null && $cEntidad->getMEDIAPONDERADA() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="MEDIAPONDERADA>=" . $aux->qstr($cEntidad->getMEDIAPONDERADA());
		}
		if ($cEntidad->getMEDIAPONDERADAHast() != null && $cEntidad->getMEDIAPONDERADAHast() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="MEDIAPONDERADA<=" . $aux->qstr($cEntidad->getMEDIAPONDERADAHast());
		}
		if ($cEntidad->getFecAlta() != null && $cEntidad->getFecAlta() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="fecAlta>=" . SQL.cFechaHora($cEntidad->getFecAlta()+ " 23:59:59");
		}
		if ($cEntidad->getFecAltaHast() != null && $cEntidad->getFecAltaHast() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="fecAlta<=" . SQL.cFechaHora($cEntidad->getFecAltaHast()+ " 23:59:59");
		}
		if ($cEntidad->getFecMod() != null && $cEntidad->getFecMod() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="fecMod>=" . $aux->qstr($cEntidad->getFecMod());
		}
		if ($cEntidad->getFecModHast() != null && $cEntidad->getFecModHast() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="fecMod<=" . $aux->qstr($cEntidad->getFecModHast());
		}
		if ($cEntidad->getUsuAlta() != null && $cEntidad->getUsuAlta() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="usuAlta=" . $aux->qstr($cEntidad->getUsuAlta());
		}
		if ($cEntidad->getUsuMod() != null && $cEntidad->getUsuMod() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="usuMod=" . $aux->qstr($cEntidad->getUsuMod());
		}
		if ($cEntidad->getOrderBy() != null && $cEntidad->getOrderBy() != ""){
			$sql .=" ORDER BY " . $cEntidad->getOrderBy();
			if ($cEntidad->getOrder() != null && $cEntidad->getOrder() != ""){
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


}//Fin de la Clase Sec_respuestasdimensionesDB
?>