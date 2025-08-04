<?php 
/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla sec_resultados_informescomp
**/
class Sec_resultadosinformescompDB 
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
		
	
		$newId = $cEntidad->getIDCANDIDATO();
		$iCont = 0;
		$sql  = "SELECT COUNT(IDCANDIDATO) AS Max FROM sec_resultados_informescomp ";
		$sql .= "WHERE ";
		$sql .="IDCANDIDATO=" . $aux->qstr($cEntidad->getIDCANDIDATO()) . " AND IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO()) . " ";
		
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$iCont = $arr['Max'];
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][OpcionesDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		
		}
		
		if ($iCont > 0 ){
			//Existe el registro

			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . "Error Registro Existe [" . constant("MNT_ALTA") . "][Sec_resultados_informescompBD]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			return 0;
		}
	
		$sql = "INSERT INTO sec_resultados_informescomp (";
		$sql .= "IDCANDIDATO" . ",";
		$sql .= "IDPROCESO" . ",";
		$sql .= "IDCOMPETENCIA" . ",";
		$sql .= "CODCOMPETENCIA" . ",";
		$sql .= "DESCCOMPETENCIA" . ",";
		$sql .= "RESULTCOMPETENCIA" . ",";
		$sql .= "RESULTSOBRE10" . ",";
		$sql .= "MEDIAPONDERADA" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($cEntidad->getIDCANDIDATO()) . ",";
		$sql .= $aux->qstr($cEntidad->getIDPROCESO()) . ",";
		$sql .= $aux->qstr($cEntidad->getIDCOMPETENCIA()) . ",";
		$sql .= $aux->qstr($cEntidad->getCODCOMPETENCIA()) . ",";
		$sql .= $aux->qstr($cEntidad->getDESCCOMPETENCIA()) . ",";
		$sql .= $aux->qstr(str_replace("'", "", $cEntidad->getRESULTCOMPETENCIA())) . ",";
		$sql .= $aux->qstr(str_replace("'", "", $cEntidad->getRESULTSOBRE10())) . ",";
		$sql .= $aux->qstr(str_replace("'", "", $cEntidad->getMEDIAPONDERADA())) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= 0 . ",";
		$sql .= 0 . ")";

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
	
		$sql  = "SELECT MAX() AS Max FROM sec_resultados_informescomp ";
		$sql  .=" WHERE IDCANDIDATO=" . $aux->qstr($cEntidad->getIDCANDIDATO()) . " AND IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO()) . " ";
		
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
	function modificar($cEntidad)
	{
		$aux			= $this->conn;
	
		$sql = "UPDATE sec_resultados_informescomp SET ";
		$sql .= "IDCANDIDATO=" . $aux->qstr($cEntidad->getIDCANDIDATO()) . ", ";
		$sql .= "IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO()) . ", ";
		$sql .= "IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA()) . ", ";
		$sql .= "CODCOMPETENCIA=" . $aux->qstr($cEntidad->getCODCOMPETENCIA()) . ", ";
		$sql .= "DESCCOMPETENCIA=" . $aux->qstr($cEntidad->getDESCCOMPETENCIA()) . ", ";
		$sql .= "RESULTCOMPETENCIA=" . $aux->qstr($cEntidad->getRESULTCOMPETENCIA()) . ", ";
		$sql .= "RESULTSOBRE10=" . $aux->qstr($cEntidad->getRESULTSOBRE10()) . ", ";
		$sql .= "MEDIAPONDERADA=" . $aux->qstr($cEntidad->getMEDIAPONDERADA()) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod()) ;
		$sql .= " WHERE ";
		$sql .="IDCANDIDATO=" . $aux->qstr($cEntidad->getIDCANDIDATO()) . " AND IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO()) . " ";
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
			$sql  ="DELETE FROM sec_resultados_informescomp ";
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
		
	
		$sql = "SELECT IDCANDIDATO,IDPROCESO,IDCOMPETENCIA,CODCOMPETENCIA,DESCCOMPETENCIA,RESULTCOMPETENCIA,RESULTSOBRE10,MEDIAPONDERADA," . "fecAlta" . "," . "fecMod" . ",usuAlta,usuMod FROM sec_resultados_informescomp WHERE ";
		$sql  .="IDCANDIDATO=" . $aux->qstr($cEntidad->getIDCANDIDATO()) . " AND IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO()) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
		
					$cEntidad->setIDCANDIDATO($arr['IDCANDIDATO']);
					$cEntidad->setIDPROCESO($arr['IDPROCESO']);
					$cEntidad->setIDCOMPETENCIA($arr['IDCOMPETENCIA']);
					$cEntidad->setCODCOMPETENCIA($arr['CODCOMPETENCIA']);
					$cEntidad->setDESCCOMPETENCIA($arr['DESCCOMPETENCIA']);
					$cEntidad->setRESULTCOMPETENCIA($arr['RESULTCOMPETENCIA']);
					$cEntidad->setRESULTSOBRE10($arr['RESULTSOBRE10']);
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
	function consultarPorCod($cEntidad)
	{
		
		$aux			= $this->conn;
		
	
		$sql = "SELECT IDCANDIDATO,IDPROCESO,IDCOMPETENCIA,CODCOMPETENCIA,DESCCOMPETENCIA,RESULTCOMPETENCIA,RESULTSOBRE10,MEDIAPONDERADA," . "fecAlta" . "," . "fecMod" . ",usuAlta,usuMod FROM sec_resultados_informescomp WHERE ";
		$sql  .="IDCANDIDATO=" . $aux->qstr($cEntidad->getIDCANDIDATO()) . " AND IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO()) . " AND CODCOMPETENCIA=" . $aux->qstr($cEntidad->getCODCOMPETENCIA())+ " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
		
					$cEntidad->setIDCANDIDATO($arr['IDCANDIDATO']);
					$cEntidad->setIDPROCESO($arr['IDPROCESO']);
					$cEntidad->setIDCOMPETENCIA($arr['IDCOMPETENCIA']);
					$cEntidad->setCODCOMPETENCIA($arr['CODCOMPETENCIA']);
					$cEntidad->setDESCCOMPETENCIA($arr['DESCCOMPETENCIA']);
					$cEntidad->setRESULTCOMPETENCIA($arr['RESULTCOMPETENCIA']);
					$cEntidad->setRESULTSOBRE10($arr['RESULTSOBRE10']);
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
		$sql.="SELECT * FROM sec_resultados_informescomp ";
		if ($cEntidad->getIDCANDIDATO() != null && $cEntidad->getIDCANDIDATO() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="IDCANDIDATO=" . $aux->qstr($cEntidad->getIDCANDIDATO());
		}
		if ($cEntidad->getIDPROCESO() != null && $cEntidad->getIDPROCESO() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO());
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
			$sql .="UPPER(CODCOMPETENCIA) LIKE UPPER('%" . $cEntidad->getCODCOMPETENCIA() . "%')";
		}
		if ($cEntidad->getDESCCOMPETENCIA() != null && $cEntidad->getDESCCOMPETENCIA() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="UPPER(DESCCOMPETENCIA) LIKE UPPER('%" . $cEntidad->getDESCCOMPETENCIA() . "%')";
		}
		if ($cEntidad->getRESULTCOMPETENCIA() != null && $cEntidad->getRESULTCOMPETENCIA() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="RESULTCOMPETENCIA>=" . $aux->qstr($cEntidad->getRESULTCOMPETENCIA());
		}
		if ($cEntidad->getRESULTCOMPETENCIAHast() != null && $cEntidad->getRESULTCOMPETENCIAHast() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="RESULTCOMPETENCIA<=" . $aux->qstr($cEntidad->getRESULTCOMPETENCIAHast());
		}
		if ($cEntidad->getRESULTSOBRE10() != null && $cEntidad->getRESULTSOBRE10() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="RESULTSOBRE10>=" . $aux->qstr($cEntidad->getRESULTSOBRE10());
		}
		if ($cEntidad->getRESULTSOBRE10Hast() != null && $cEntidad->getRESULTSOBRE10Hast() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="RESULTSOBRE10<=" . $aux->qstr($cEntidad->getRESULTSOBRE10Hast());
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
			$sql .="fecAlta>=" . $aux->qstr($cEntidad->getFecAlta());
		}
		if ($cEntidad->getFecAltaHast() != null && $cEntidad->getFecAltaHast() != ""){
			$sql .= this.getSQLWhere($and);
			$and = true;
			$sql .="fecAlta<=" . $aux->qstr($cEntidad->getFecAltaHast());
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



}//Fin de la Clase Sec_resultadosinformescompDB
?>