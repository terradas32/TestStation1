<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla consumos
**/
class ConsumosDB
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
        
		$sql = "INSERT INTO consumos (";
		$sql .= "idEmpresa" . ",";
		$sql .= "idProceso" . ",";
		$sql .= "idCandidato" . ",";
		$sql .= "codIdiomaIso2" . ",";
		$sql .= "idPrueba" . ",";
		$sql .= "codIdiomaInforme" . ",";
		$sql .= "idTipoInforme" . ",";
		$sql .= "idBaremo" . ",";
		$sql .= "nomEmpresa" . ",";
		$sql .= "nomProceso" . ",";
		$sql .= "nomCandidato" . ",";
		$sql .= "apellido1" . ",";
		$sql .= "apellido2" . ",";
		$sql .= "dni" . ",";
		$sql .= "mail" . ",";
		$sql .= "nomPrueba" . ",";
		$sql .= "nomInforme" . ",";
		$sql .= "nomBaremo" . ",";
		$sql .= "concepto" . ",";
		$sql .= "unidades" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($cEntidad->getIdEmpresa(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdProceso(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdCandidato(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdPrueba(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCodIdiomaInforme(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdTipoInforme(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdBaremo(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNomEmpresa(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNomProceso(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNomCandidato(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getApellido1(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getApellido2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDni(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getMail(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNomPrueba(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNomInforme(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNomBaremo(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getConcepto(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUnidades(), false) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ConsumosDB]";
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
	
		$sql  = "SELECT MAX() AS Max FROM consumos ";
		$sql  .=" WHERE idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " AND codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " AND codIdiomaInforme=" . $aux->qstr($cEntidad->getCodIdiomaInforme(), false) . " AND idTipoInforme=" . $aux->qstr($cEntidad->getIdTipoInforme(), false) . " ";
		$rs = $aux->Execute($sql);
		$newId=0;
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][ConsumosDB]";
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
	
		$sql = "UPDATE consumos SET ";
		$sql .= "idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ", ";
		$sql .= "idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . ", ";
		$sql .= "idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . ", ";
		$sql .= "codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . ", ";
		$sql .= "idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . ", ";
		$sql .= "codIdiomaInforme=" . $aux->qstr($cEntidad->getCodIdiomaInforme(), false) . ", ";
		$sql .= "idTipoInforme=" . $aux->qstr($cEntidad->getIdTipoInforme(), false) . ", ";
		$sql .= "idBaremo=" . $aux->qstr($cEntidad->getIdBaremo(), false) . ", ";
		$sql .= "nomEmpresa=" . $aux->qstr($cEntidad->getNomEmpresa(), false) . ", ";
		$sql .= "nomProceso=" . $aux->qstr($cEntidad->getNomProceso(), false) . ", ";
		$sql .= "nomCandidato=" . $aux->qstr($cEntidad->getNomCandidato(), false) . ", ";
		$sql .= "apellido1=" . $aux->qstr($cEntidad->getApellido1(), false) . ", ";
		$sql .= "apellido2=" . $aux->qstr($cEntidad->getApellido2(), false) . ", ";
		$sql .= "dni=" . $aux->qstr($cEntidad->getDni(), false) . ", ";
		$sql .= "mail=" . $aux->qstr($cEntidad->getMail(), false) . ", ";
		$sql .= "nomPrueba=" . $aux->qstr($cEntidad->getNomPrueba(), false) . ", ";
		$sql .= "nomInforme=" . $aux->qstr($cEntidad->getNomInforme(), false) . ", ";
		$sql .= "nomBaremo=" . $aux->qstr($cEntidad->getNomBaremo(), false) . ", ";
		$sql .= "concepto=" . $aux->qstr($cEntidad->getConcepto(), false) . ", ";
		$sql .= "unidades=" . $aux->qstr($cEntidad->getUnidades(), false) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " AND codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " AND codIdiomaInforme=" . $aux->qstr($cEntidad->getCodIdiomaInforme(), false) . " AND idTipoInforme=" . $aux->qstr($cEntidad->getIdTipoInforme(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ConsumosDB]";
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
			$sql  ="DELETE FROM consumos ";
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
			if ($cEntidad->getCodIdiomaIso2() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " ";
			}
			if ($cEntidad->getIdPrueba() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
			}
			if ($cEntidad->getCodIdiomaInforme() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="codIdiomaInforme=" . $aux->qstr($cEntidad->getCodIdiomaInforme(), false) . " ";
			}
			if ($cEntidad->getIdTipoInforme() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idTipoInforme=" . $aux->qstr($cEntidad->getIdTipoInforme(), false) . " ";
			}
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ConsumosDB]";
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
	
		$sql = "SELECT *  FROM consumos WHERE ";
		$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " AND codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " AND codIdiomaInforme=" . $aux->qstr($cEntidad->getCodIdiomaInforme(), false) . " AND idTipoInforme=" . $aux->qstr($cEntidad->getIdTipoInforme(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setIdProceso($arr['idProceso']);
					$cEntidad->setIdCandidato($arr['idCandidato']);
					$cEntidad->setCodIdiomaIso2($arr['codIdiomaIso2']);
					$cEntidad->setIdPrueba($arr['idPrueba']);
					$cEntidad->setCodIdiomaInforme($arr['codIdiomaInforme']);
					$cEntidad->setIdTipoInforme($arr['idTipoInforme']);
					$cEntidad->setIdBaremo($arr['idBaremo']);
					$cEntidad->setNomEmpresa($arr['nomEmpresa']);
					$cEntidad->setNomProceso($arr['nomProceso']);
					$cEntidad->setNomCandidato($arr['nomCandidato']);
					$cEntidad->setApellido1($arr['apellido1']);
					$cEntidad->setApellido2($arr['apellido2']);
					$cEntidad->setDni($arr['dni']);
					$cEntidad->setMail($arr['mail']);
					$cEntidad->setNomPrueba($arr['nomPrueba']);
					$cEntidad->setNomInforme($arr['nomInforme']);
					$cEntidad->setNomBaremo($arr['nomBaremo']);
					$cEntidad->setConcepto($arr['concepto']);
					$cEntidad->setUnidades($arr['unidades']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][ConsumosDB]";
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
		$sql.="SELECT * FROM consumos ";
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
		if ($cEntidad->getCodIdiomaIso2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false);
		}
		if ($cEntidad->getIdPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false);
		}
		if ($cEntidad->getCodIdiomaInforme() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="codIdiomaInforme=" . $aux->qstr($cEntidad->getCodIdiomaInforme(), false);
		}
		if ($cEntidad->getIdTipoInforme() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTipoInforme=" . $aux->qstr($cEntidad->getIdTipoInforme(), false);
		}
		if ($cEntidad->getIdBaremo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idBaremo=" . $aux->qstr($cEntidad->getIdBaremo(), false);
		}
		if ($cEntidad->getNomEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nomEmpresa) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNomEmpresa() . "%") . ")";
		}
		if ($cEntidad->getNomProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nomProceso) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNomProceso() . "%") . ")";
		}
		if ($cEntidad->getNomCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nomCandidato) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNomCandidato() . "%") . ")";
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
		if ($cEntidad->getDni() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(dni) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDni() . "%") . ")";
		}
		if ($cEntidad->getMail() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(mail) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getMail() . "%") . ")";
		}
		if ($cEntidad->getNomPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nomPrueba) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNomPrueba() . "%") . ")";
		}
		if ($cEntidad->getNomInforme() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nomInforme) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNomInforme() . "%") . ")";
		}
		if ($cEntidad->getNomBaremo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nomBaremo) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNomBaremo() . "%") . ")";
		}
		if ($cEntidad->getConcepto() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(concepto) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getConcepto() . "%") . ")";
		}
		if ($cEntidad->getUnidades() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="unidades>=" . $aux->qstr($cEntidad->getUnidades(), false);
		}
		if ($cEntidad->getUnidadesHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="unidades<=" . $aux->qstr($cEntidad->getUnidadesHast(), false);
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
//		echo $sql;
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
		$sql.="SELECT * FROM consumos ";
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresa IN (" . $cEntidad->getIdEmpresa() . ") ";
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
		if ($cEntidad->getCodIdiomaIso2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false);
		}
		if ($cEntidad->getIdPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false);
		}
		if ($cEntidad->getCodIdiomaInforme() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="codIdiomaInforme=" . $aux->qstr($cEntidad->getCodIdiomaInforme(), false);
		}
		if ($cEntidad->getIdTipoInforme() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTipoInforme=" . $aux->qstr($cEntidad->getIdTipoInforme(), false);
		}
		if ($cEntidad->getIdBaremo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idBaremo=" . $aux->qstr($cEntidad->getIdBaremo(), false);
		}
		if ($cEntidad->getNomEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nomEmpresa) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNomEmpresa() . "%") . ")";
		}
		if ($cEntidad->getNomProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nomProceso) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNomProceso() . "%") . ")";
		}
		if ($cEntidad->getNomCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nomCandidato) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNomCandidato() . "%") . ")";
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
		if ($cEntidad->getDni() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(dni) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDni() . "%") . ")";
		}
		if ($cEntidad->getMail() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(mail) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getMail() . "%") . ")";
		}
		if ($cEntidad->getNomPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nomPrueba) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNomPrueba() . "%") . ")";
		}
		if ($cEntidad->getNomInforme() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nomInforme) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNomInforme() . "%") . ")";
		}
		if ($cEntidad->getNomBaremo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nomBaremo) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNomBaremo() . "%") . ")";
		}
		if ($cEntidad->getConcepto() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(concepto) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getConcepto() . "%") . ")";
		}
		if ($cEntidad->getUnidades() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="unidades>=" . $aux->qstr($cEntidad->getUnidades(), false);
		}
		if ($cEntidad->getUnidadesHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="unidades<=" . $aux->qstr($cEntidad->getUnidadesHast(), false);
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
//		echo $sql;
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

		$sql = "UPDATE consumos SET  ";
		return $retorno;
	}
}//Fin de la Clase ConsumosDB
?>