<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla procesos
**/
class ProcesosDB
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

		$sql = "INSERT INTO procesos (";
		$sql .= "idProceso" . ",";
		$sql .= "idEmpresa" . ",";
		$sql .= "nombre" . ",";
		$sql .= "descripcion" . ",";
		$sql .= "observaciones" . ",";
		$sql .= "fechaInicio" . ",";
		$sql .= "fechaFin" . ",";
		$sql .= "idModoRealizacion" . ",";
		$sql .= "procesoConfidencial" . ",";

		$sql .= "envioContrasenas" . ",";
		$sql .= "fecEnvioProgramado" . ",";
		$sql .= "bajaLog" . ",";

		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($newId, false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdEmpresa(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNombre(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescripcion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getObservaciones(), false) . ",";
		$sql .= $aux->DBDate($cEntidad->getFechaInicio()) . ",";
		$sql .= $aux->DBDate($cEntidad->getFechaFin()) . ",";
		$sql .= $aux->qstr($cEntidad->getIdModoRealizacion(), false) . ",";
		$sql .= $aux->qstr(($cEntidad->getProcesoConfidencial() != "") ? $cEntidad->getProcesoConfidencial() : "0", false) . ",";
		$sql .= $aux->qstr(($cEntidad->getEnvioContrasenas() != "") ? $cEntidad->getEnvioContrasenas() : "1", false) . ",";
		if ($cEntidad->getFecEnvioProgramado() != ""){
			$sql .= $aux->DBDate($cEntidad->getFecEnvioProgramado()) . ",";
		}else{
			$sql .= "NULL ,";
		}

		$sql .= $aux->qstr(($cEntidad->getBajaLog() != "") ? $cEntidad->getBajaLog() : "0", false) . ",";

		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][ProcesosDB]";
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

		$sql  = "SELECT MAX(idProceso) AS Max FROM procesos ";
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
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][ProcesosDB]";
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

		$sql = "UPDATE procesos SET ";
		$sql .= "idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . ", ";
		$sql .= "idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ", ";
		$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
		$sql .= "descripcion=" . $aux->qstr($cEntidad->getDescripcion(), false) . ", ";
		$sql .= "observaciones=" . $aux->qstr($cEntidad->getObservaciones(), false) . ", ";
		$sql .= "fechaInicio=" . $aux->DBDate($cEntidad->getFechaInicio()) . ",";
		$sql .= "fechaFin=" . $aux->DBDate($cEntidad->getFechaFin()) . ",";
		$sql .= "idModoRealizacion=" . $aux->qstr($cEntidad->getIdModoRealizacion(), false) . ", ";
		$sql .= "envioContrasenas=" . $aux->qstr($cEntidad->getEnvioContrasenas(), false) . ", ";
		$sql .= "procesoConfidencial=" . $aux->qstr($cEntidad->getProcesoConfidencial(), false) . ", ";

		if ($cEntidad->getFecEnvioProgramado() != ""){
			$sql .= "fecEnvioProgramado=" . $aux->DBDate($cEntidad->getFecEnvioProgramado()) . ", ";
		}
		$sql .= "bajaLog=" . $aux->qstr(($cEntidad->getBajaLog() != "") ? $cEntidad->getBajaLog() : "0", false) . ", ";

		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][ProcesosDB]";
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
		require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
		require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
		require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
		require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
		require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
		require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
		require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
		require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
		require_once(constant("DIR_WS_COM") . "Correos_proceso/Correos_procesoDB.php");
		require_once(constant("DIR_WS_COM") . "Correos_proceso/Correos_proceso.php");

		require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");

		$aux			= $this->conn;
		$this->msg_Error			= array();
		$and			= false;
		$retorno			= true;
		$sql="";


		//Comprobamos que el proceso que intentamos borrar no tiene candidatos informados

		$cProceso = new Procesos();
		$cCandidato = new Candidatos();
		$cCandidatoDB = new CandidatosDB($aux);

		$cCandidato->setIdEmpresa($cEntidad->getIdEmpresa());
		$cCandidato->setIdProceso($cEntidad->getIdProceso());
		$sqlInformados =  $cCandidatoDB->readListaInformados($cCandidato);

		$listaInformados = $aux->Execute($sqlInformados);

		if($listaInformados->recordCount()>0){

			$cProceso->setIdEmpresa($cEntidad->getIdEmpresa());
			$cProceso->setIdProceso($cEntidad->getIdProceso());
			$cProceso = $this->readEntidad($cProceso);

			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	= "No se ha podido eliminar el proceso '" . $cProceso->getNombre() ."'.";
			$this->msg_Error[]	= $sTypeError;
			$sTypeError	= "Ya tiene candidatos informados.";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}

		if ($retorno){
			//Borramos todas las posibles asignaciones con el proceso
			$cProcesoInformes	= new Proceso_informes();  // Entidad
			$cProcesoInformesDB	= new Proceso_informesDB($aux);  // Entidad
			$cProcesoInformes->setIdEmpresa($cEntidad->getIdEmpresa());
			$cProcesoInformes->setIdProceso($cEntidad->getIdProceso());

			if (!$cProcesoInformesDB->borrar($cProcesoInformes)){
				$retorno=false;
				$this->msg_Error	= array();
				$this->msg_Error[]	= $cProcesoInformesDB->msg_Error;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}else{
				$cProcesoBaremos	= new Proceso_baremos();  // Entidad
				$cProcesoBaremosDB	= new Proceso_baremosDB($aux);  // Entidad
				$cProcesoBaremos->setIdEmpresa($cEntidad->getIdEmpresa());
				$cProcesoBaremos->setIdProceso($cEntidad->getIdProceso());
				if (!$cProcesoBaremosDB->borrar($cProcesoBaremos)){
					$retorno=false;
					$this->msg_Error	= array();
					$this->msg_Error[]	= $cProcesoBaremosDB->msg_Error;
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				}else{
					$cProcesoPruebas	= new Proceso_pruebas();  // Entidad
					$cProcesoPruebasDB	= new Proceso_pruebasDB($aux);  // Entidad
					$cProcesoPruebas->setIdEmpresa($cEntidad->getIdEmpresa());
					$cProcesoPruebas->setIdProceso($cEntidad->getIdProceso());
					if (!$cProcesoPruebasDB->borrar($cProcesoPruebas)){
						$retorno=false;
						$this->msg_Error	= array();
						$this->msg_Error[]	= $cProcesoPruebasDB->msg_Error;
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}else{
						$cCandidato			= new Candidatos();
						$cCandidatoDB		= new CandidatosDB($aux);
						$cCandidato->setIdEmpresa($cEntidad->getIdEmpresa());
						$cCandidato->setIdProceso($cEntidad->getIdProceso());
						if (!$cCandidatoDB->borrar($cCandidato)){
							$retorno=false;
							$this->msg_Error	= array();
							$this->msg_Error[]	= $cCandidatoDB->msg_Error;
							error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						}else{
							$cCorreosProcesos			= new Correos_proceso();
							$cCorreosProcesosDB			= new Correos_procesoDB($aux);
							$cCorreosProcesos->setIdEmpresa($cEntidad->getIdEmpresa());
							$cCorreosProcesos->setIdProceso($cEntidad->getIdProceso());
							if (!$cCorreosProcesosDB->borrar($cCorreosProcesos)){
								$retorno=false;
								$this->msg_Error	= array();
								$this->msg_Error[]	= $cCorreosProcesosDB->msg_Error;
								error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
							}else{
								//Borramos el registro de la Entidad.
								$sql  ="DELETE FROM procesos ";
								$sql  .="WHERE ";
								if ($cEntidad->getIdProceso() != ""){
									$sql .= $this->getSQLAnd($and);
									$and = true;
									$sql  .="idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " ";
								}
								if ($cEntidad->getIdEmpresa() != ""){
									$sql .= $this->getSQLAnd($and);
									$and = true;
									$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
								}
								if($aux->Execute($sql) === false){
									$retorno=false;
									$this->msg_Error	= array();
									$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesosDB]";
									$this->msg_Error[]	= $sTypeError;
									error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
								}
							}
						}
					}
				}
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

		$sql = "SELECT *  FROM procesos WHERE ";
		$sql  .="idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdProceso($arr['idProceso']);
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setDescripcion($arr['descripcion']);
					$cEntidad->setObservaciones($arr['observaciones']);
					$cEntidad->setFechaInicio($arr['fechaInicio']);
					$cEntidad->setFechaFin($arr['fechaFin']);
					$cEntidad->setIdModoRealizacion($arr['idModoRealizacion']);
					$cEntidad->setProcesoConfidencial($arr['procesoConfidencial']);
					$cEntidad->setEnvioContrasenas($arr['envioContrasenas']);
					$cEntidad->setFecEnvioProgramado($arr['fecEnvioProgramado']);
					$cEntidad->setBajaLog($arr['bajaLog']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][ProcesosDB]";
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
		$sql.="SELECT * FROM procesos ";
		if ($cEntidad->getIdProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false);
		}
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false);
		}
		if ($cEntidad->getNombre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nombre) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNombre() . "%") . ")";
		}
		if ($cEntidad->getDescripcion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descripcion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescripcion() . "%") . ")";
		}
		if ($cEntidad->getObservaciones() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(observaciones) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getObservaciones() . "%") . ")";
		}
		if ($cEntidad->getFechaInicio() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaInicio>=" . $aux->qstr($cEntidad->getFechaInicio(), false);
		}
		if ($cEntidad->getFechaInicioHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaInicio<=" . $aux->qstr($cEntidad->getFechaInicioHast(), false);
		}
		if ($cEntidad->getFechaFin() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaFin>=" . $aux->qstr($cEntidad->getFechaFin(), false);
		}
		if ($cEntidad->getFechaFinHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaFin<=" . $aux->qstr($cEntidad->getFechaFinHast(), false);
		}
		if ($cEntidad->getIdModoRealizacion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idModoRealizacion=" . $aux->qstr($cEntidad->getIdModoRealizacion(), false);
		}
		if ($cEntidad->getProcesoConfidencial() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="procesoConfidencial=" . $aux->qstr($cEntidad->getProcesoConfidencial(), false);
		}
		if ($cEntidad->getEnvioContrasenas() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="envioContrasenas=" . $aux->qstr($cEntidad->getEnvioContrasenas(), false);
		}
		if ($cEntidad->getFecEnvioProgramado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecEnvioProgramado=" . $aux->qstr($cEntidad->getFecEnvioProgramado(), false);
		}
		if ($cEntidad->getBajaLog() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="bajaLog=" . $aux->qstr($cEntidad->getBajaLog(), false);
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
		$sql.="SELECT * FROM procesos ";
		if ($cEntidad->getIdProceso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false);
		}
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresa IN (" . $cEntidad->getIdEmpresa() . ") ";
		}
		if ($cEntidad->getNombre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nombre) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNombre() . "%") . ")";
		}
		if ($cEntidad->getDescripcion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descripcion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescripcion() . "%") . ")";
		}
		if ($cEntidad->getObservaciones() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(observaciones) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getObservaciones() . "%") . ")";
		}
		if ($cEntidad->getFechaInicio() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaInicio>=" . $aux->qstr($cEntidad->getFechaInicio(), false);
		}
		if ($cEntidad->getFechaInicioHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaInicio<=" . $aux->qstr($cEntidad->getFechaInicioHast(), false);
		}
		if ($cEntidad->getFechaFin() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaFin>=" . $aux->qstr($cEntidad->getFechaFin(), false);
		}
		if ($cEntidad->getFechaFinHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaFin<=" . $aux->qstr($cEntidad->getFechaFinHast(), false);
		}
		if ($cEntidad->getIdModoRealizacion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idModoRealizacion=" . $aux->qstr($cEntidad->getIdModoRealizacion(), false);
		}
		if ($cEntidad->getProcesoConfidencial() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="procesoConfidencial=" . $aux->qstr($cEntidad->getProcesoConfidencial(), false);
		}
		if ($cEntidad->getEnvioContrasenas() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="envioContrasenas=" . $aux->qstr($cEntidad->getEnvioContrasenas(), false);
		}
		if ($cEntidad->getFecEnvioProgramado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecEnvioProgramado=" . $aux->qstr($cEntidad->getFecEnvioProgramado(), false);
		}
		if ($cEntidad->getBajaLog() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="bajaLog=" . $aux->qstr($cEntidad->getBajaLog(), false);
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

		$sql = "UPDATE procesos SET  ";
		return $retorno;
	}
}//Fin de la Clase ProcesosDB
?>
