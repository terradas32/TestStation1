<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla respuestas_pruebas
**/
class Respuestas_pruebasDB
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
		$sql  = "SELECT COUNT(idEmpresa) AS Max FROM respuestas_pruebas ";
		$sql .= "WHERE ";
		$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " AND codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$iCont = $arr['Max'];
			}
		}
		if ($iCont > 0 ){
			//Existe el registro
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error Registro Existe [" . constant("MNT_ALTA") . "][Respuestas_pruebasDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			return 0;
		}
		
		$comboDESC_EMPRESAS		= new Combo($aux,"_fDescEmpresa","idEmpresa","nombre","Descripcion","empresas","","","idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false),"","orden");
		$cEntidad->setDescEmpresa($comboDESC_EMPRESAS->getDescripcionCombo($cEntidad->getIdEmpresa()));
		$comboDESC_PROCESOS		= new Combo($aux,"_fDescProceso","idProceso","nombre","Descripcion","procesos","","","idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false),"","fecMod");
		$cEntidad->setDescProceso($comboDESC_PROCESOS->getDescripcionCombo($cEntidad->getIdProceso()));
		$comboDESC_CANDIDATOS	= new Combo($aux,"_fDescCandidato","idCandidato",$aux->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","","","idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false),"","fecMod");
		$cEntidad->setDescCandidato($comboDESC_CANDIDATOS->getDescripcionCombo($cEntidad->getIdCandidato()));
		$comboDESC_WI_IDIOMAS	= new Combo($aux,"_fDescIdiomaIso2","codIdiomaIso2","nombre","Descripcion","wi_idiomas","","","","","fecMod");
		$cEntidad->setDescIdiomaIso2($comboDESC_WI_IDIOMAS->getDescripcionCombo($cEntidad->getCodIdiomaIso2()));
		$comboDESC_PRUEBAS		= new Combo($aux,"_fDescPrueba","idPrueba","nombre","Descripcion","pruebas","","","codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false),"","fecMod");
		$cEntidad->setDescPrueba($comboDESC_PRUEBAS->getDescripcionCombo($cEntidad->getIdPrueba()));
		
		$sql = "INSERT INTO respuestas_pruebas (";
		$sql .= "idEmpresa" . ",";
		$sql .= "descEmpresa" . ",";
		$sql .= "idProceso" . ",";
		$sql .= "descProceso" . ",";
		$sql .= "idCandidato" . ",";
		$sql .= "descCandidato" . ",";
		$sql .= "codIdiomaIso2" . ",";
		$sql .= "descIdiomaIso2" . ",";
		$sql .= "idPrueba" . ",";
		$sql .= "descPrueba" . ",";
		$sql .= "finalizado" . ",";
		$sql .= "leidoInstrucciones" . ",";
		$sql .= "leidoEjemplos" . ",";
		$sql .= "minutos_test" . ",";
		$sql .= "segundos_test" . ",";
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
		$sql .= $aux->qstr($cEntidad->getDescCandidato(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescIdiomaIso2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdPrueba(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescPrueba(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getFinalizado(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getLeidoInstrucciones(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getLeidoEjemplos(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getMinutos_test(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getSegundos_test(), false) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Respuestas_pruebasDB]";
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

	/***********************************************************************
	* Inserta una entidad en la base de datos.
	* @param entidad Entidad a insertar con Datos
	* @return long Numero de ID de la entidad
	* @exception Exception Error al ejecutar la acción
	*  en la base de datos
	***********************************************************************/
	function getInsertar($cEntidad, $iCont)
	{
		$aux			= $this->conn;
	
		$newId = $cEntidad->getIdEmpresa();
		$sql = "";
		if (empty($iCont)){
			$sql = "INSERT INTO respuestas_pruebas (";
			$sql .= "idEmpresa" . ",";
			$sql .= "descEmpresa" . ",";
			$sql .= "idProceso" . ",";
			$sql .= "descProceso" . ",";
			$sql .= "idCandidato" . ",";
			$sql .= "descCandidato" . ",";
			$sql .= "codIdiomaIso2" . ",";
			$sql .= "descIdiomaIso2" . ",";
			$sql .= "idPrueba" . ",";
			$sql .= "descPrueba" . ",";
			$sql .= "finalizado" . ",";
			$sql .= "leidoInstrucciones" . ",";
			$sql .= "leidoEjemplos" . ",";
			$sql .= "minutos_test" . ",";
			$sql .= "segundos_test" . ",";
			$sql .= "fecAlta" . ",";
			$sql .= "fecMod" . ",";
			$sql .= "usuAlta" . ",";
			$sql .= "usuMod" . ")";
			$sql .= " VALUES ";
		}
		$sql .= "(" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescEmpresa(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdProceso(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescProceso(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdCandidato(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescCandidato(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescIdiomaIso2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdPrueba(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescPrueba(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getFinalizado(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getLeidoInstrucciones(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getLeidoEjemplos(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getMinutos_test(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getSegundos_test(), false) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . "),";

		return $sql;
	}
	
	/******************************************************************************************
	* Devuelve una cadena para insertar un valor de 
	* secuencia clave de tipo ID.
	* @return String nuevo id.
	*****************************************************************************************/
	function getSiguienteId($cEntidad)
	{
		$aux			= $this->conn;
	
		$sql  = "SELECT MAX() AS Max FROM respuestas_pruebas ";
		$sql  .=" WHERE idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " AND codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$rs = $aux->Execute($sql);
		$newId=0;
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][Respuestas_pruebasDB]";
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

		$comboDESC_EMPRESAS		= new Combo($aux,"_fDescEmpresa","idEmpresa","nombre","Descripcion","empresas","","","idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false),"","orden");
		$cEntidad->setDescEmpresa($comboDESC_EMPRESAS->getDescripcionCombo($cEntidad->getIdEmpresa()));
		$comboDESC_PROCESOS		= new Combo($aux,"_fDescProceso","idProceso","nombre","Descripcion","procesos","","","idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false),"","fecMod");
		$cEntidad->setDescProceso($comboDESC_PROCESOS->getDescripcionCombo($cEntidad->getIdProceso()));
		$comboDESC_CANDIDATOS	= new Combo($aux,"_fDescCandidato","idCandidato",$aux->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","","","idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false),"","fecMod");
		$cEntidad->setDescCandidato($comboDESC_CANDIDATOS->getDescripcionCombo($cEntidad->getIdCandidato()));
		$comboDESC_WI_IDIOMAS	= new Combo($aux,"_fDescIdiomaIso2","codIdiomaIso2","nombre","Descripcion","wi_idiomas","","","","","fecMod");
		$cEntidad->setDescIdiomaIso2($comboDESC_WI_IDIOMAS->getDescripcionCombo($cEntidad->getCodIdiomaIso2()));
		$comboDESC_PRUEBAS		= new Combo($aux,"_fDescPrueba","idPrueba","nombre","Descripcion","pruebas","","","codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false),"","fecMod");
		$cEntidad->setDescPrueba($comboDESC_PRUEBAS->getDescripcionCombo($cEntidad->getIdPrueba()));
		
		$sql = "UPDATE respuestas_pruebas SET ";
		$sql .= "idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ", ";
		$sql .= "descEmpresa=" . $aux->qstr($cEntidad->getDescEmpresa(), false) . ", ";
		$sql .= "idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . ", ";
		$sql .= "descProceso=" . $aux->qstr($cEntidad->getDescProceso(), false) . ", ";
		$sql .= "idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . ", ";
		$sql .= "descCandidato=" . $aux->qstr($cEntidad->getDescCandidato(), false) . ", ";
		$sql .= "codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . ", ";
		$sql .= "descIdiomaIso2=" . $aux->qstr($cEntidad->getDescIdiomaIso2(), false) . ", ";
		$sql .= "idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . ", ";
		$sql .= "descPrueba=" . $aux->qstr($cEntidad->getDescPrueba(), false) . ", ";
		$sql .= "finalizado=" . $aux->qstr($cEntidad->getFinalizado(), false) . ", ";
		$sql .= "leidoInstrucciones=" . $aux->qstr($cEntidad->getLeidoInstrucciones(), false) . ", ";
		$sql .= "leidoEjemplos=" . $aux->qstr($cEntidad->getLeidoEjemplos(), false) . ", ";
		$sql .= "minutos_test=" . $aux->qstr($cEntidad->getMinutos_test(), false) . ", ";
		$sql .= "segundos_test=" . $aux->qstr($cEntidad->getSegundos_test(), false) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " AND codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][Respuestas_pruebasDB]";
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
			$sql  ="DELETE FROM respuestas_pruebas ";
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
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][Respuestas_pruebasDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}else{
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
				$cRespuestas_pruebas_itemsDB = new Respuestas_pruebas_itemsDB($aux);
				$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
				$cRespuestas_pruebas_items->setIdEmpresa($cEntidad->getIdEmpresa());
				$cRespuestas_pruebas_items->setIdProceso($cEntidad->getIdProceso());
				$cRespuestas_pruebas_items->setIdCandidato($cEntidad->getIdCandidato());
				$cRespuestas_pruebas_items->setIdPrueba($cEntidad->getIdPrueba());
				$cRespuestas_pruebas_items->setCodIdiomaIso2($cEntidad->getCodIdiomaIso2());
				$cRespuestas_pruebas_itemsDB->borrar($cRespuestas_pruebas_items);				
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
	
		$sql = "SELECT *  FROM respuestas_pruebas WHERE ";
		$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " AND codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setDescEmpresa($arr['descEmpresa']);
					$cEntidad->setIdProceso($arr['idProceso']);
					$cEntidad->setDescProceso($arr['descProceso']);
					$cEntidad->setIdCandidato($arr['idCandidato']);
					$cEntidad->setDescCandidato($arr['descCandidato']);
					$cEntidad->setCodIdiomaIso2($arr['codIdiomaIso2']);
					$cEntidad->setDescIdiomaIso2($arr['descIdiomaIso2']);
					$cEntidad->setIdPrueba($arr['idPrueba']);
					$cEntidad->setDescPrueba($arr['descPrueba']);
					$cEntidad->setFinalizado($arr['finalizado']);
					$cEntidad->setLeidoInstrucciones($arr['leidoInstrucciones']);
					$cEntidad->setLeidoEjemplos($arr['leidoEjemplos']);
					$cEntidad->setMinutos_test($arr['minutos_test']);
					$cEntidad->setSegundos_test($arr['segundos_test']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][Respuestas_pruebasDB]";
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
		$sql.="SELECT * FROM respuestas_pruebas ";
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false);
		}
		if ($cEntidad->getDescEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descEmpresa) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescEmpresa() . "%") . ")";
		}
		if ($cEntidad->getIdProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false);
		}
		if ($cEntidad->getDescProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descProceso) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescProceso() . "%") . ")";
		}
		if ($cEntidad->getIdCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false);
		}
		if ($cEntidad->getDescCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descCandidato) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescCandidato() . "%") . ")";
		}
		if ($cEntidad->getCodIdiomaIso2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false);
		}
		if ($cEntidad->getDescIdiomaIso2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descIdiomaIso2) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescIdiomaIso2() . "%") . ")";
		}
		if ($cEntidad->getIdPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false);
		}
		if ($cEntidad->getDescPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descPrueba) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescPrueba() . "%") . ")";
		}
		if ($cEntidad->getFinalizado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="finalizado>=" . $aux->qstr($cEntidad->getFinalizado(), false);
		}
		if ($cEntidad->getFinalizadoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="finalizado<=" . $aux->qstr($cEntidad->getFinalizadoHast(), false);
		}
		if ($cEntidad->getLeidoInstrucciones() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="leidoInstrucciones>=" . $aux->qstr($cEntidad->getLeidoInstrucciones(), false);
		}
		if ($cEntidad->getLeidoInstruccionesHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="leidoInstrucciones<=" . $aux->qstr($cEntidad->getLeidoInstruccionesHast(), false);
		}
		if ($cEntidad->getLeidoEjemplos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="leidoEjemplos>=" . $aux->qstr($cEntidad->getLeidoEjemplos(), false);
		}
		if ($cEntidad->getLeidoEjemplosHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="leidoEjemplos<=" . $aux->qstr($cEntidad->getLeidoEjemplosHast(), false);
		}
		if ($cEntidad->getMinutos_test() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(minutos_test) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getMinutos_test() . "%") . ")";
		}
		if ($cEntidad->getSegundos_test() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(segundos_test) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getSegundos_test() . "%") . ")";
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
		$sql.="SELECT * FROM respuestas_pruebas ";
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresa IN(" . $cEntidad->getIdEmpresa() . ") ";
		}
		if ($cEntidad->getDescEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descEmpresa) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescEmpresa() . "%") . ")";
		}
		if ($cEntidad->getIdProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false);
		}
		if ($cEntidad->getDescProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descProceso) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescProceso() . "%") . ")";
		}
		if ($cEntidad->getIdCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false);
		}
		if ($cEntidad->getDescCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descCandidato) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescCandidato() . "%") . ")";
		}
		if ($cEntidad->getCodIdiomaIso2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false);
		}
		if ($cEntidad->getDescIdiomaIso2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descIdiomaIso2) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescIdiomaIso2() . "%") . ")";
		}
		if ($cEntidad->getIdPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false);
		}
		if ($cEntidad->getDescPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descPrueba) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescPrueba() . "%") . ")";
		}
		if ($cEntidad->getFinalizado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="finalizado>=" . $aux->qstr($cEntidad->getFinalizado(), false);
		}
		if ($cEntidad->getFinalizadoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="finalizado<=" . $aux->qstr($cEntidad->getFinalizadoHast(), false);
		}
		if ($cEntidad->getLeidoInstrucciones() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="leidoInstrucciones>=" . $aux->qstr($cEntidad->getLeidoInstrucciones(), false);
		}
		if ($cEntidad->getLeidoInstruccionesHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="leidoInstrucciones<=" . $aux->qstr($cEntidad->getLeidoInstruccionesHast(), false);
		}
		if ($cEntidad->getLeidoEjemplos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="leidoEjemplos>=" . $aux->qstr($cEntidad->getLeidoEjemplos(), false);
		}
		if ($cEntidad->getLeidoEjemplosHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="leidoEjemplos<=" . $aux->qstr($cEntidad->getLeidoEjemplosHast(), false);
		}
		if ($cEntidad->getMinutos_test() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(minutos_test) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getMinutos_test() . "%") . ")";
		}
		if ($cEntidad->getSegundos_test() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(segundos_test) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getSegundos_test() . "%") . ")";
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
	function readListaFinalizado($cEntidad)
	{
		$aux			= $this->conn;
	
		$sql="";
		$and = false;
		$sql.="SELECT a.* FROM respuestas_pruebas a, respuestas_pruebas_items b ";
		$sql.=" WHERE ";
		$sql.=" a.idProceso=b.idProceso AND ";
		$sql.=" a.idPrueba=b.idPrueba AND ";
		$sql.=" a.idCandidato =b.idCandidato ";
		
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false);
		}
		if ($cEntidad->getDescEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(a.descEmpresa) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescEmpresa() . "%") . ")";
		}
		if ($cEntidad->getIdProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false);
		}
		if ($cEntidad->getDescProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(a.descProceso) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescProceso() . "%") . ")";
		}
		if ($cEntidad->getIdCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false);
		}
		if ($cEntidad->getDescCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(a.descCandidato) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescCandidato() . "%") . ")";
		}
		if ($cEntidad->getCodIdiomaIso2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false);
		}
		if ($cEntidad->getDescIdiomaIso2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(a.descIdiomaIso2) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescIdiomaIso2() . "%") . ")";
		}
		if ($cEntidad->getIdPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false);
		}
		if ($cEntidad->getDescPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(a.descPrueba) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescPrueba() . "%") . ")";
		}
		if ($cEntidad->getFinalizado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.finalizado=" . $aux->qstr($cEntidad->getFinalizado(), false);
		}
		if ($cEntidad->getFinalizadoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.finalizado<=" . $aux->qstr($cEntidad->getFinalizadoHast(), false);
		}
		if ($cEntidad->getLeidoInstrucciones() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.leidoInstrucciones>=" . $aux->qstr($cEntidad->getLeidoInstrucciones(), false);
		}
		if ($cEntidad->getLeidoInstruccionesHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.leidoInstrucciones<=" . $aux->qstr($cEntidad->getLeidoInstruccionesHast(), false);
		}
		if ($cEntidad->getLeidoEjemplos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.leidoEjemplos>=" . $aux->qstr($cEntidad->getLeidoEjemplos(), false);
		}
		if ($cEntidad->getLeidoEjemplosHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.leidoEjemplos<=" . $aux->qstr($cEntidad->getLeidoEjemplosHast(), false);
		}
		if ($cEntidad->getMinutos_test() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(a.minutos_test) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getMinutos_test() . "%") . ")";
		}
		if ($cEntidad->getSegundos_test() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(a.segundos_test) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getSegundos_test() . "%") . ")";
		}
		if ($cEntidad->getFecAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.fecAlta>=" . $aux->qstr($cEntidad->getFecAlta(), false);
		}
		if ($cEntidad->getFecAltaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.fecAlta<=" . $aux->qstr($cEntidad->getFecAltaHast(), false);
		}
		if ($cEntidad->getFecMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.fecMod>=" . $aux->qstr($cEntidad->getFecMod(), false);
		}
		if ($cEntidad->getFecModHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.fecMod<=" . $aux->qstr($cEntidad->getFecModHast(), false);
		}
		if ($cEntidad->getUsuAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.usuAlta=" . $aux->qstr($cEntidad->getUsuAlta(), false);
		}
		if ($cEntidad->getUsuMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="a.usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false);
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

		$sql = "UPDATE respuestas_pruebas SET  ";
		return $retorno;
	}
}//Fin de la Clase Respuestas_pruebasDB
?>