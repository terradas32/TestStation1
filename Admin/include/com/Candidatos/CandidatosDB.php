<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla candidatos
**/
class CandidatosDB
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

		$sql = "INSERT INTO candidatos (";
		$sql .= "idCandidato" . ",";
		$sql .= "idEmpresa" . ",";
		$sql .= "idProceso" . ",";
		$sql .= "nombre" . ",";
		$sql .= "apellido1" . ",";
		$sql .= "apellido2" . ",";
		$sql .= "dni" . ",";
		$sql .= "mail" . ",";
		$sql .= "idTratamiento" . ",";
		$sql .= "idSexo" . ",";
		$sql .= "idEdad" . ",";
		$sql .= "fechaNacimiento" . ",";
		$sql .= "idPais" . ",";
		$sql .= "idProvincia" . ",";
		$sql .= "idMunicipio" . ",";
		$sql .= "idZona" . ",";
		$sql .= "direccion" . ",";
		$sql .= "codPostal" . ",";
		$sql .= "idFormacion" . ",";
		$sql .= "idNivel" . ",";
		$sql .= "idArea" . ",";
		$sql .= "telefono" . ",";
		$sql .= "estadoCivil" . ",";
		$sql .= "nacionalidad" . ",";
		$sql .= "informado" . ",";
		$sql .= "pagoTpv" . ",";
		$sql .= "finalizado" . ",";
		$sql .= "fechaFinalizado" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ",";
		$sql .= "token" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($newId, false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdEmpresa(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdProceso(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNombre(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getApellido1(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getApellido2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDni(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getMail(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdTratamiento(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdSexo(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdEdad(), false) . ",";
		$sql .= $aux->DBDate($cEntidad->getFechaNacimiento()) . ",";
		$sql .= $aux->qstr($cEntidad->getIdPais(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdProvincia(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdMunicipio(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdZona(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDireccion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCodPostal(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdFormacion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdNivel(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdArea(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getTelefono(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getEstadoCivil(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNacionalidad(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getInformado(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPagoTpv(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getFinalizado(), false) . ",";
		$sql .= $aux->DBDate($cEntidad->getFechaFinalizado()) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ",";
		$sql .= $aux->qstr(md5(uniqid('', true)), false) . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][CandidatosDB]";
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

		$sql  = "SELECT MAX(idCandidato) AS Max FROM candidatos ";
		$sql  .=" WHERE idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " ";
		$rs = $aux->Execute($sql);
		$newId=0;
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][CandidatosDB]";
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

		$sql = "UPDATE candidatos SET ";
		$sql .= "idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . ", ";
		$sql .= "idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ", ";
		$sql .= "idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . ", ";
		$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
		$sql .= "apellido1=" . $aux->qstr($cEntidad->getApellido1(), false) . ", ";
		$sql .= "apellido2=" . $aux->qstr($cEntidad->getApellido2(), false) . ", ";
		$sql .= "dni=" . $aux->qstr($cEntidad->getDni(), false) . ", ";
		$sql .= "mail=" . $aux->qstr($cEntidad->getMail(), false) . ", ";
		$sql .= (trim($cEntidad->getPassword()) != "") ? "password=" . $aux->qstr(password_hash($cEntidad->getPassword(), PASSWORD_BCRYPT), false) . "," : "";
		$sql .= "idTratamiento=" . $aux->qstr($cEntidad->getIdTratamiento(), false) . ", ";
		$sql .= "idSexo=" . $aux->qstr($cEntidad->getIdSexo(), false) . ", ";
		$sql .= "idEdad=" . $aux->qstr($cEntidad->getIdEdad(), false) . ", ";
		$sql .= "fechaNacimiento=" . $aux->DBDate($cEntidad->getFechaNacimiento()) . ",";
		$sql .= "idPais=" . $aux->qstr($cEntidad->getIdPais(), false) . ", ";
		$sql .= "idProvincia=" . $aux->qstr($cEntidad->getIdProvincia(), false) . ", ";
		$sql .= "idMunicipio=" . $aux->qstr($cEntidad->getIdMunicipio(), false) . ", ";
		$sql .= "idZona=" . $aux->qstr($cEntidad->getIdZona(), false) . ", ";
		$sql .= "direccion=" . $aux->qstr($cEntidad->getDireccion(), false) . ", ";
		$sql .= "codPostal=" . $aux->qstr($cEntidad->getCodPostal(), false) . ", ";
		$sql .= "idFormacion=" . $aux->qstr($cEntidad->getIdFormacion(), false) . ", ";
		$sql .= "idNivel=" . $aux->qstr($cEntidad->getIdNivel(), false) . ", ";
		$sql .= "idArea=" . $aux->qstr($cEntidad->getIdArea(), false) . ", ";
		$sql .= "telefono=" . $aux->qstr($cEntidad->getTelefono(), false) . ", ";
		$sql .= "estadoCivil=" . $aux->qstr($cEntidad->getEstadoCivil(), false) . ", ";
		$sql .= "nacionalidad=" . $aux->qstr($cEntidad->getNacionalidad(), false) . ", ";
		$sql .= (trim($cEntidad->getInformado()) != "") ? "informado=" . $aux->qstr($cEntidad->getInformado(), false) . "," : "";
		$sql .= "finalizado=" . $aux->qstr($cEntidad->getFinalizado(), false) . ", ";
		$sql .= "fechaFinalizado=" . $aux->DBDate($cEntidad->getFechaFinalizado()) . ",";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) . " ";
		$sql .= " WHERE ";
		$sql .="idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " AND idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " ";
//		echo $sql;exit;
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][CandidatosDB]";
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
		require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
		require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
		require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");

		$aux			= $this->conn;
		$this->msg_Error			= array();
		$and			= false;
		$retorno			= true;
		$sql="";

		$cProceso = new Procesos();
		$cProcesoDB = new ProcesosDB($aux);
		$cCandidato = new Candidatos();

		$cCandidato->setIdEmpresa($cEntidad->getIdEmpresa());
		$cCandidato->setIdProceso($cEntidad->getIdProceso());
		$cCandidato->setIdCandidato($cEntidad->getIdCandidato());
		$sqlInformados =  $this->readListaInformados($cCandidato);
		$listaInformados = $aux->Execute($sqlInformados);

		if($listaInformados->recordCount()>0){

			$cCandidato = $this->readEntidad($cCandidato);

			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	= "No se ha podido eliminar esta asignación del Candidato '" . $cCandidato->getNombre()  . " " .$cCandidato->getApellido1() ." " .$cCandidato->getApellido2() ."',\\n el candidato ya ha sido informado.";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}

		if ($retorno){
			//Borramos el registro de la Entidad.
			$sql  ="DELETE FROM candidatos ";
			$sql  .="WHERE ";
			if ($cEntidad->getIdCandidato() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " ";
			}
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
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][CandidatosDB]";
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

		$sql = "SELECT * FROM candidatos WHERE ";
		$sql  .="idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " AND idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdCandidato($arr['idCandidato']);
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setIdProceso($arr['idProceso']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setApellido1($arr['apellido1']);
					$cEntidad->setApellido2($arr['apellido2']);
					$cEntidad->setDni($arr['dni']);
					$cEntidad->setMail($arr['mail']);
					$cEntidad->setPassword($arr['password']);
					$cEntidad->setIdTratamiento($arr['idTratamiento']);
					$cEntidad->setIdSexo($arr['idSexo']);
					$cEntidad->setIdEdad($arr['idEdad']);
					$cEntidad->setFechaNacimiento($arr['fechaNacimiento']);
					$cEntidad->setIdPais($arr['idPais']);
					$cEntidad->setIdProvincia($arr['idProvincia']);
					$cEntidad->setIdMunicipio($arr['idMunicipio']);
					$cEntidad->setIdZona($arr['idZona']);
					$cEntidad->setDireccion($arr['direccion']);
					$cEntidad->setCodPostal($arr['codPostal']);
					$cEntidad->setIdFormacion($arr['idFormacion']);
					$cEntidad->setIdNivel($arr['idNivel']);
					$cEntidad->setIdArea($arr['idArea']);
					$cEntidad->setTelefono($arr['telefono']);
					$cEntidad->setEstadoCivil($arr['estadoCivil']);
					$cEntidad->setNacionalidad($arr['nacionalidad']);
					$cEntidad->setSectorMB($arr['sectorMB']);
					$cEntidad->setCodIso2PaisProcedencia($arr['codIso2PaisProcedencia']);

					$cEntidad->setConcesionMB($arr['concesionMB']);
					$cEntidad->setBaseMB($arr['baseMB']);

					$cEntidad->setEspecialidadMB($arr['especialidadMB']);
					$cEntidad->setNivelConocimientoMB($arr['nivelConocimientoMB']);
					$cEntidad->setPagoTpv($arr['pagoTpv']);

					$cEntidad->setInformado($arr['informado']);
					$cEntidad->setFinalizado($arr['finalizado']);
					$cEntidad->setFechaFinalizado($arr['fechaFinalizado']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
					$cEntidad->setUltimoLogin($arr['ultimoLogin']);
					$cEntidad->setToken($arr['token']);
					$cEntidad->setUltimaAcc($arr['ultimaAcc']);
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][CandidatosDB]";
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
	function consultaPorMail($cEntidad)
	{
		$aux			= $this->conn;

		$sql = "SELECT *  FROM candidatos WHERE ";
		$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND UPPER(mail) =" . $aux->qstr(strtoupper($cEntidad->getMail()), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdCandidato($arr['idCandidato']);
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setIdProceso($arr['idProceso']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setApellido1($arr['apellido1']);
					$cEntidad->setApellido2($arr['apellido2']);
					$cEntidad->setDni($arr['dni']);
					$cEntidad->setMail($arr['mail']);
					$cEntidad->setPassword($arr['password']);
					$cEntidad->setIdTratamiento($arr['idTratamiento']);
					$cEntidad->setIdSexo($arr['idSexo']);
					$cEntidad->setIdEdad($arr['idEdad']);
					$cEntidad->setFechaNacimiento($arr['fechaNacimiento']);
					$cEntidad->setIdPais($arr['idPais']);
					$cEntidad->setIdProvincia($arr['idProvincia']);
					$cEntidad->setIdMunicipio($arr['idMunicipio']);
					$cEntidad->setIdZona($arr['idZona']);
					$cEntidad->setDireccion($arr['direccion']);
					$cEntidad->setCodPostal($arr['codPostal']);
					$cEntidad->setIdFormacion($arr['idFormacion']);
					$cEntidad->setIdNivel($arr['idNivel']);
					$cEntidad->setIdArea($arr['idArea']);
					$cEntidad->setTelefono($arr['telefono']);
					$cEntidad->setEstadoCivil($arr['estadoCivil']);
					$cEntidad->setNacionalidad($arr['nacionalidad']);
					$cEntidad->setSectorMB($arr['sectorMB']);
					$cEntidad->setCodIso2PaisProcedencia($arr['codIso2PaisProcedencia']);

					$cEntidad->setConcesionMB($arr['concesionMB']);
					$cEntidad->setBaseMB($arr['baseMB']);

					$cEntidad->setEspecialidadMB($arr['especialidadMB']);
					$cEntidad->setNivelConocimientoMB($arr['nivelConocimientoMB']);
					$cEntidad->setPagoTpv($arr['pagoTpv']);

					$cEntidad->setInformado($arr['informado']);
					$cEntidad->setFinalizado($arr['finalizado']);
					$cEntidad->setFechaFinalizado($arr['fechaFinalizado']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
					$cEntidad->setUltimoLogin($arr['ultimoLogin']);
					$cEntidad->setToken($arr['token']);
					$cEntidad->setUltimaAcc($arr['ultimaAcc']);
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][CandidatosDB]";
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
		$sql.="SELECT * FROM candidatos ";
		if ($cEntidad->getIdCandidatoIN() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCandidato IN (" . $cEntidad->getIdCandidatoIN() . ")";
		}
		if ($cEntidad->getIdCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false);
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
		if ($cEntidad->getPassword() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(password) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getPassword() . "%") . ")";
		}
		if ($cEntidad->getIdTratamiento() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTratamiento=" . $aux->qstr($cEntidad->getIdTratamiento(), false);
		}
		if ($cEntidad->getIdSexo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idSexo=" . $aux->qstr($cEntidad->getIdSexo(), false);
		}
		if ($cEntidad->getIdEdad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEdad=" . $aux->qstr($cEntidad->getIdEdad(), false);
		}
		if ($cEntidad->getFechaNacimiento() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaNacimiento>=" . $aux->qstr($cEntidad->getFechaNacimiento(), false);
		}
		if ($cEntidad->getFechaNacimientoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaNacimiento<=" . $aux->qstr($cEntidad->getFechaNacimientoHast(), false);
		}
		if ($cEntidad->getIdPais() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPais=" . $aux->qstr($cEntidad->getIdPais(), false);
		}
		if ($cEntidad->getIdProvincia() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idProvincia=" . $aux->qstr($cEntidad->getIdProvincia(), false);
		}
		if ($cEntidad->getIdMunicipio() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idMunicipio=" . $aux->qstr($cEntidad->getIdMunicipio(), false);
		}
		if ($cEntidad->getIdZona() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idZona=" . $aux->qstr($cEntidad->getIdZona(), false);
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
		if ($cEntidad->getIdFormacion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idFormacion=" . $aux->qstr($cEntidad->getIdFormacion(), false);
		}
		if ($cEntidad->getIdNivel() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idNivel=" . $aux->qstr($cEntidad->getIdNivel(), false);
		}
		if ($cEntidad->getIdArea() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idArea=" . $aux->qstr($cEntidad->getIdArea(), false);
		}
		if ($cEntidad->getTelefono() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(telefono) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getTelefono() . "%") . ")";
		}
		if ($cEntidad->getEstadoCivil() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(estadoCivil) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getEstadoCivil() . "%") . ")";
		}
		if ($cEntidad->getNacionalidad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nacionalidad) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNacionalidad() . "%") . ")";
		}
		if ($cEntidad->getSectorMB() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(sectorMB) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getSectorMB() . "%") . ")";
		}

		if ($cEntidad->getCodIso2PaisProcedencia() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(codIso2PaisProcedencia) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCodIso2PaisProcedencia() . "%") . ")";
		}
		if ($cEntidad->getConcesionMB() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(concesionMB) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getConcesionMB() . "%") . ")";
		}
		if ($cEntidad->getBaseMB() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(baseMB) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getBaseMB() . "%") . ")";
		}

		if ($cEntidad->getEspecialidadMB() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(especialidadMB) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getEspecialidadMB() . "%") . ")";
		}
		if ($cEntidad->getNivelConocimientoMB() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nivelConocimientoMB) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNivelConocimientoMB() . "%") . ")";
		}
		if ($cEntidad->getPagoTpv() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(pagoTpv) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getPagoTpv() . "%") . ")";
		}
		if ($cEntidad->getInformado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="informado>=" . $aux->qstr($cEntidad->getInformado(), false);
		}
		if ($cEntidad->getInformadoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="informado<=" . $aux->qstr($cEntidad->getInformadoHast(), false);
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
		if ($cEntidad->getFechaFinalizado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaFinalizado>=" . $aux->qstr($cEntidad->getFechaFinalizado(), false);
		}
		if ($cEntidad->getFechaFinalizadoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaFinalizado<=" . $aux->qstr($cEntidad->getFechaFinalizadoHast(), false);
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
			$sql .="UPPER(token) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getToken() . "%") . ")";
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
		$sql.="SELECT * FROM candidatos ";
		if ($cEntidad->getIdCandidatoIN() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCandidato IN (" . $cEntidad->getIdCandidatoIN() . ")";
		}
		if ($cEntidad->getIdCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false);
		}
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
		if ($cEntidad->getPassword() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(password) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getPassword() . "%") . ")";
		}
		if ($cEntidad->getIdTratamiento() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTratamiento=" . $aux->qstr($cEntidad->getIdTratamiento(), false);
		}
		if ($cEntidad->getIdSexo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idSexo=" . $aux->qstr($cEntidad->getIdSexo(), false);
		}
		if ($cEntidad->getIdEdad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEdad=" . $aux->qstr($cEntidad->getIdEdad(), false);
		}
		if ($cEntidad->getFechaNacimiento() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaNacimiento>=" . $aux->qstr($cEntidad->getFechaNacimiento(), false);
		}
		if ($cEntidad->getFechaNacimientoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaNacimiento<=" . $aux->qstr($cEntidad->getFechaNacimientoHast(), false);
		}
		if ($cEntidad->getIdPais() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPais=" . $aux->qstr($cEntidad->getIdPais(), false);
		}
		if ($cEntidad->getIdProvincia() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idProvincia=" . $aux->qstr($cEntidad->getIdProvincia(), false);
		}
		if ($cEntidad->getIdMunicipio() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idMunicipio=" . $aux->qstr($cEntidad->getIdMunicipio(), false);
		}
		if ($cEntidad->getIdZona() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idZona=" . $aux->qstr($cEntidad->getIdZona(), false);
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
		if ($cEntidad->getIdFormacion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idFormacion=" . $aux->qstr($cEntidad->getIdFormacion(), false);
		}
		if ($cEntidad->getIdNivel() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idNivel=" . $aux->qstr($cEntidad->getIdNivel(), false);
		}
		if ($cEntidad->getIdArea() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idArea=" . $aux->qstr($cEntidad->getIdArea(), false);
		}
		if ($cEntidad->getTelefono() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(telefono) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getTelefono() . "%") . ")";
		}
		if ($cEntidad->getEstadoCivil() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(estadoCivil) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getEstadoCivil() . "%") . ")";
		}
		if ($cEntidad->getNacionalidad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nacionalidad) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNacionalidad() . "%") . ")";
		}
		if ($cEntidad->getInformado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="informado>=" . $aux->qstr($cEntidad->getInformado(), false);
		}
		if ($cEntidad->getInformadoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="informado<=" . $aux->qstr($cEntidad->getInformadoHast(), false);
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
		if ($cEntidad->getFechaFinalizado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaFinalizado>=" . $aux->qstr($cEntidad->getFechaFinalizado(), false);
		}
		if ($cEntidad->getFechaFinalizadoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fechaFinalizado<=" . $aux->qstr($cEntidad->getFechaFinalizadoHast(), false);
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
			$sql .="UPPER(token) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getToken() . "%") . ")";
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
	function readListaInformados($cEntidad)
	{
		$aux			= $this->conn;

		$sql="";
		$and = false;
		$sql.="SELECT * FROM candidatos ";
		if ($cEntidad->getIdCandidato() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false);
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

		$sql .= $this->getSQLWhere($and);
		$and = true;
		$sql .="informado= 1 ";

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

		$sql = "SELECT  * FROM candidatos WHERE ";
		$sql  .="mail=" . $aux->qstr($cEntidad->getMail(), false);// . " AND ";
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

		$sql = "SELECT  * FROM candidatos WHERE ";
		$sql  .="mail=" . $aux->qstr($cEntidad->getMail(), false) . " AND ";
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
		$and			= false;
		$sql = "SELECT * FROM candidatos ";
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
		if ($cEntidad->getMail() != ""){
			$sql .= $this->getSQLAnd($and);
			$and = true;
			$sql  .="UPPER(mail) =" . $aux->qstr(strtoupper($cEntidad->getMail()), false) . " ";
		}

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

		$sql = "UPDATE candidatos SET ";
		$sql .= "ultimoLogin=" . $aux->sysTimeStamp . " ";
		$sql .= " WHERE ";
		$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " ";

		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][EmpresasDB]";
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
		$sql = "UPDATE candidatos SET ";
		$sql .="token=" . $aux->qstr($cEntidad->getToken(), false) . ", ";
		$sql .="ultimaAcc=" . $aux->sysTimeStamp . " ";
		$sql .=" WHERE ";
		$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND UPPER(mail) =" . $aux->qstr(strtoupper($cEntidad->getMail()), false) . " ";

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
	function candidatoPorToken($cEntidad)
	{

		$aux			= $this->conn;

		$sql = "SELECT * FROM candidatos ";
		$sql  .= "WHERE ";
		$sql  .= "token=" . $aux->qstr($cEntidad->getToken(), false) . " ";

		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setIdProceso($arr['idProceso']);
					$cEntidad->setIdCandidato($arr['idCandidato']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setApellido1($arr['apellido1']);
					$cEntidad->setApellido2($arr['apellido2']);
					$cEntidad->setDni($arr['dni']);
					$cEntidad->setMail($arr['mail']);
					$cEntidad->setPassword($arr['password']);
					$cEntidad->setIdSexo($arr['idSexo']);
					$cEntidad->setIdEdad($arr['idEdad']);
					$cEntidad->setFechaNacimiento($arr['fechaNacimiento']);
					$cEntidad->setIdPais($arr['idPais']);
					$cEntidad->setIdProvincia($arr['idProvincia']);
					//$cEntidad->setIdCiudad($arr['idCiudad']);
					$cEntidad->setIdZona($arr['idZona']);
					$cEntidad->setDireccion($arr['direccion']);
					$cEntidad->setCodPostal($arr['codPostal']);
					$cEntidad->setIdFormacion($arr['idFormacion']);
					$cEntidad->setIdNivel($arr['idNivel']);
					$cEntidad->setIdArea($arr['idArea']);
					//$cEntidad->setIdTipoTelefono($arr['idTipoTelefono']);
					$cEntidad->setTelefono($arr['telefono']);
					$cEntidad->setEstadoCivil($arr['estadoCivil']);
					$cEntidad->setNacionalidad($arr['nacionalidad']);
					$cEntidad->setSectorMB($arr['sectorMB']);
					$cEntidad->setCodIso2PaisProcedencia($arr['codIso2PaisProcedencia']);

					$cEntidad->setConcesionMB($arr['concesionMB']);
					$cEntidad->setBaseMB($arr['baseMB']);

					$cEntidad->setEspecialidadMB($arr['especialidadMB']);
					$cEntidad->setNivelConocimientoMB($arr['nivelConocimientoMB']);
					$cEntidad->setPagoTpv($arr['pagoTpv']);

					$cEntidad->setInformado($arr['informado']);
					$cEntidad->setFinalizado($arr['finalizado']);
					$cEntidad->setFechaFinalizado($arr['fechaFinalizado']);
					$cEntidad->setUltimoLogin($arr['ultimoLogin']);
					$cEntidad->setToken($arr['token']);
					$cEntidad->setUltimaAcc($arr['ultimaAcc']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
					$cEntidad->setToken($arr['token']);
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][CandidatosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;

		}
		return $cEntidad;

	}

	function isCandidatoActivo($cEntidad)
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
		$sql .="FROM candidatos ";
		$sql .="WHERE ";
		$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND UPPER(mail) =" . $aux->qstr(strtoupper($cEntidad->getMail()), false) . " ";
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
		$this->sSQL = $_SESSION['SQLCandidatosDB'];
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

		$sql = "UPDATE candidatos SET  ";
		return $retorno;
	}
<<<<<<< HEAD

	/******************************************************************************************
	* Devuelve una cadena para insertar un valor de
	* secuencia clave de tipo ID.
	* @return String nuevo id.
	*****************************************************************************************/
	function decuentaConsumoXAdministracion($cCandidato)
	{
		$aux			= $this->conn;
		$retorno=true;
		
		require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
		require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
		require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
		require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
		require_once(constant("DIR_WS_COM") . "Pruebas_empresas/Pruebas_empresasDB.php");
		require_once(constant("DIR_WS_COM") . "Pruebas_empresas/Pruebas_empresas.php");
		require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
		require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
		require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
		require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
		require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
		require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
		require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
		require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
		require_once(constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebasDB.php");
		require_once(constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebas.php");
		require_once(constant("DIR_WS_COM") . "Consumos/ConsumosDB.php");
		require_once(constant("DIR_WS_COM") . "Consumos/Consumos.php");
		require_once(constant("DIR_WS_COM") . "Tipos_informes/Tipos_informesDB.php");
		require_once(constant("DIR_WS_COM") . "Tipos_informes/Tipos_informes.php");
		require_once(constant("DIR_WS_COM") . "Baremos/BaremosDB.php");
		require_once(constant("DIR_WS_COM") . "Baremos/Baremos.php");

		$cProceso_pruebasDB				= new Proceso_pruebasDB($aux);
		$cPruebasDB						= new PruebasDB($aux); 
		$cPruebas_empresasDB			= new Pruebas_empresasDB($aux); 
		$cEmpresaConsumoDB				= new EmpresasDB($aux);
		$cProcesosDB					= new ProcesosDB($aux);
		$cProceso_baremosDB				= new Proceso_baremosDB($aux);
		$cProceso_informesDB			= new Proceso_informesDB($aux);
		$cInformes_pruebasDB			= new Informes_pruebasDB($aux);
		$cConsumosDB					= new ConsumosDB($aux);
		$cTipos_informesDB				= new Tipos_informesDB($aux);
		$cBaremosDB						= new BaremosDB($aux);
	
		$cProceso_pruebas	=	new Proceso_pruebas();
		$cProceso_pruebas->setIdEmpresa($cCandidato->getIdEmpresa());
		$cProceso_pruebas->setIdProceso($cCandidato->getIdProceso());

		$sqlProceso_pruebas = $cProceso_pruebasDB->readLista($cProceso_pruebas);
		$rsProceso_pruebas = $aux->Execute($sqlProceso_pruebas);
		//Hay que descontar las Unidades a la empresa,
		//registrarlo en la tabla de consumos con el concepto Administración.

		//1º Miramos los datos de la empresa
		$cEmpresaConsumo = new Empresas();
		$cEmpresaConsumo->setIdEmpresa($cCandidato->getIdEmpresa());
		$cEmpresaConsumo = $cEmpresaConsumoDB->readEntidad($cEmpresaConsumo);

		//2º Miramos los datos del proceso
		$cProcesos = new Procesos();
		$cProcesos->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());
		$cProcesos->setIdProceso($cCandidato->getIdProceso());

		$cProcesos = $cProcesosDB->readEntidad($cProcesos);

		$dTotalCoste=0;

		$_idBaremo ="-1";
		$_sBaremo ="N/A"; //not applicable or not available
		
		while (!$rsProceso_pruebas->EOF)
		{
	    	//3º Miramos que baremo x defecto se aplica a la prueba
			$cProceso_baremos = new Proceso_baremos();
	    	$cProceso_baremos->setIdEmpresa($rsProceso_pruebas->fields['idEmpresa']);
	    	$cProceso_baremos->setIdProceso($rsProceso_pruebas->fields['idProceso']);
	    	$cProceso_baremos->setCodIdiomaIso2($rsProceso_pruebas->fields['codIdiomaIso2']);
	    	$cProceso_baremos->setIdPrueba($rsProceso_pruebas->fields['idPrueba']);

	    	$sSQL = $cProceso_baremosDB->readLista($cProceso_baremos);
			//Nos devuelve sólo 1 registro al consultar por prueba
	    	$rsProceso_baremos = $aux->Execute($sSQL);

	    	while(!$rsProceso_baremos->EOF)
	    	{

				//4º Miramos información del baremo
	    		$_sBaremo ="";
	    		$_idBaremo =$rsProceso_baremos->fields['idBaremo'];
	    		$_idBaremo = (!empty($_idBaremo)) ? $_idBaremo : "1";
	    		//Sacamos el literal del baremo 
    			$cBaremos = new Baremos();
    			$cBaremos->setIdBaremo($_idBaremo);
    			$cBaremos->setIdPrueba($rsProceso_baremos->fields['idPrueba']);
    			$cBaremos = $cBaremosDB->readEntidad($cBaremos);
    			$_sBaremo = $cBaremos->getNombre();

		    	$rsProceso_baremos->MoveNext();
	    	}

			//5º Miramos información del informe
			$cProceso_informes = new Proceso_informes();
			$cProceso_informes->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());
			$cProceso_informes->setIdProceso($cProcesos->getIdProceso());
			$cProceso_informes->setCodIdiomaIso2($rsProceso_pruebas->fields['codIdiomaIso2']);
			$cProceso_informes->setIdPrueba($rsProceso_pruebas->fields['idPrueba']);
			$cProceso_informes->setIdBaremo($_idBaremo);
			$sSQL = $cProceso_informesDB->readLista($cProceso_informes);
			$rsProceso_informes = $aux->Execute($sSQL);
			
			$cInformes_pruebas = new Informes_pruebas();
			while(!$rsProceso_informes->EOF)
			{
				//5º Miramos los datos del informe
				$cInformes_pruebas = new Informes_pruebas();
				$cInformes_pruebas->setIdPrueba($rsProceso_informes->fields['idPrueba']);
				$cInformes_pruebas->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
				$cInformes_pruebas->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
				$cInformes_pruebas = $cInformes_pruebasDB->readEntidad($cInformes_pruebas);

				//Sacamos los datos del informe para grabarlo
				$cTipos_informes = new Tipos_informes();
				$cTipos_informes->setCodIdiomaIso2($cInformes_pruebas->getCodIdiomaIso2());
				$cTipos_informes->setIdTipoInforme($cInformes_pruebas->getIdTipoInforme());
				$cTipos_informes = $cTipos_informesDB->readEntidad($cTipos_informes);

				$rsProceso_informes->MoveNext();
			}

			//Miramos si tiene definido Tarifa Administración por empresa
			$cAdministracion = new Pruebas_empresas();
			$cAdministracion->setIdPrueba($rsProceso_pruebas->fields['idPrueba']);
			$cAdministracion->setCodIdiomaIso2($rsProceso_pruebas->fields['codIdiomaIso2']);
			$cAdministracion->setIdEmpresa($rsProceso_pruebas->fields['idEmpresa']);
			
			$sql_Administracion = $cPruebas_empresasDB->readLista($cAdministracion);
			$rsAdministracion = $aux->Execute($sql_Administracion);

			if ($rsAdministracion->NumRows() > 0){
				$cAdministracion = $cPruebas_empresasDB->readEntidad($cAdministracion);
			}else{
				$cAdministracion = new Pruebas();
				$cAdministracion->setIdPrueba($rsProceso_pruebas->fields['idPrueba']);
				$cAdministracion->setCodIdiomaIso2($rsProceso_pruebas->fields['codIdiomaIso2']);
				$cAdministracion = $cPruebasDB->readEntidad($cAdministracion);
			}

			$dTotalCoste += $cAdministracion->getTarifa();
			//6º Insertamos por cada prueba una línea en Consumo en concepto Administración

			$cPruebasD = new Pruebas();
			$cPruebasD->setIdPrueba($rsProceso_pruebas->fields['idPrueba']);
			$cPruebasD->setCodIdiomaIso2($rsProceso_pruebas->fields['codIdiomaIso2']);
			$cPruebasD = $cPruebasDB->readEntidad($cPruebasD);
			
			$cConsumos = new Consumos();
			$cConsRead = new Consumos();
			$cConsumos->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());
			$cConsRead->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());

			$cConsumos->setIdProceso($cProcesos->getIdProceso());
			$cConsRead->setIdProceso($cProcesos->getIdProceso());

			$cConsumos->setIdCandidato($cCandidato->getIdCandidato());
			$cConsRead->setIdCandidato($cCandidato->getIdCandidato());

			$cConsumos->setCodIdiomaIso2($rsProceso_pruebas->fields['codIdiomaIso2']);
			$cConsRead->setCodIdiomaIso2($rsProceso_pruebas->fields['codIdiomaIso2']);

			$cConsumos->setIdPrueba($rsProceso_pruebas->fields['idPrueba']);
			$cConsRead->setIdPrueba($rsProceso_pruebas->fields['idPrueba']);

			$cConsumos->setCodIdiomaInforme($cInformes_pruebas->getCodIdiomaIso2());
			$cConsRead->setCodIdiomaInforme($cInformes_pruebas->getCodIdiomaIso2());
			//No se pone ya que hay pruebas con más de 1 y sólo insertamos una línea de 
			//Consumo por prueba y candidato. $cInformes_pruebas->getIdTipoInforme()
			$sIdTipoInforme = "-1";
			$sNomInforme = "NA";
			$sConcepto = constant("STR_ADMINISTRACION");

			$cConsumos->setIdTipoInforme($sIdTipoInforme);
			$cConsRead->setIdTipoInforme($sIdTipoInforme);

			$cConsumos->setIdBaremo($_idBaremo);
			$cConsRead->setIdBaremo($_idBaremo);

			$cConsumos->setNomEmpresa($cEmpresaConsumo->getNombre());
			$cConsumos->setNomProceso($cProcesos->getNombre());
			$cConsumos->setNomCandidato($cCandidato->getNombre());
			$cConsumos->setApellido1($cCandidato->getApellido1());
			$cConsumos->setApellido2($cCandidato->getApellido2());
			$cConsumos->setDni($cCandidato->getDni());
			$cConsumos->setMail($cCandidato->getMail());

			$cConsumos->setNomPrueba($cPruebasD->getNombre());
			$cConsumos->setNomInforme($sNomInforme);
			$cConsumos->setNomBaremo($_sBaremo);

			$cConsumos->setConcepto($sConcepto);
			$cConsRead->setConcepto($sConcepto);

			$cConsumos->setUnidades($cAdministracion->getTarifa());
			$cConsumos->setUsuAlta($cCandidato->getUsuAlta());
			$cConsumos->setUsuMod($cCandidato->getUsuMod());
			//Revisamos si ya se le ha cobrado, si se actualiza la página, no hay que cobrar dos veces
			$sqlConsumos = $cConsumosDB->readLista($cConsRead);

			$rsConsRead = $aux->Execute($sqlConsumos);
			$iConsRead = $rsConsRead->NumRows();
			if ($iConsRead <= 0)
			{
				$idConsumo = $cConsumosDB->insertar($cConsumos);
				$sDescuentaMatriz = $cEmpresaConsumo->getDescuentaMatriz();
				if (!empty($sDescuentaMatriz)){
					//Consultamos los datos de la empresa a la que realmente se le descontará
					$cMatrizConsumo = new Empresas();
					$cMatrizConsumoDB = new EmpresasDB($aux);
					$cMatrizConsumo->setIdEmpresa($sDescuentaMatriz);
					$cMatrizConsumo = $cMatrizConsumoDB->readEntidad($cMatrizConsumo);
					$sSQL = "UPDATE consumos SET descuentaMatriz=" . $sDescuentaMatriz . ", ";
					$sSQL .= "nomDescuentaMatriz=" . $aux->qstr($cMatrizConsumo->getNombre(), false) . " ";
					$sSQL .= "WHERE ";
					$sSQL .= "idEmpresa=" . $cConsumos->getIdEmpresa() . " AND ";
					$sSQL .= "idProceso=" . $cConsumos->getIdProceso() . " AND ";
					$sSQL .= "idCandidato=" . $cConsumos->getIdCandidato() . " AND ";
					$sSQL .= "codIdiomaIso2='" . $cConsumos->getCodIdiomaIso2() . "' AND ";
					$sSQL .= "idPrueba='" . $cConsumos->getIdPrueba() . "' AND ";
					$sSQL .= "codIdiomaInforme='" . $cConsumos->getCodIdiomaInforme() . "' AND ";
					$sSQL .= "idTipoInforme='" . $cConsumos->getIdTipoInforme() . "' ";
					$aux->Execute($sSQL);
					$sTypeError=date('d/m/Y H:i:s') . " Descontando unidades de la Matriz [" . $sDescuentaMatriz . "] : ";
					$sTypeError.= $sSQL;
					error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				}else {
					$sSQL = "UPDATE consumos SET descuentaMatriz=" . $cEmpresaConsumo->getIdEmpresa() . ", ";
					$sSQL .= "nomDescuentaMatriz=" . $aux->qstr($cEmpresaConsumo->getNombre(), false) . " ";
					$sSQL .= "WHERE ";
					$sSQL .= "idEmpresa=" . $cConsumos->getIdEmpresa() . " AND ";
					$sSQL .= "idProceso=" . $cConsumos->getIdProceso() . " AND ";
					$sSQL .= "idCandidato=" . $cConsumos->getIdCandidato() . " AND ";
					$sSQL .= "codIdiomaIso2='" . $cConsumos->getCodIdiomaIso2() . "' AND ";
					$sSQL .= "idPrueba='" . $cConsumos->getIdPrueba() . "' AND ";
					$sSQL .= "codIdiomaInforme='" . $cConsumos->getCodIdiomaInforme() . "' AND ";
					$sSQL .= "idTipoInforme='" . $cConsumos->getIdTipoInforme() . "' ";
					$aux->Execute($sSQL);
				}

			}else{
				$sTypeError=date('d/m/Y H:i:s') . " Las unidades ya fueron descontadas el día : ";
				$sTypeError.= $rsConsRead->fields['fecAlta'] . " [Emp:" . $cConsRead->getIdEmpresa() . " - Pro:" . $cConsRead->getIdProceso() . " - Can:" . $cConsRead->getIdCandidato() . " - Pru. iso2:" . $cConsRead->getCodIdiomaIso2() . " - Pru:" . $cConsRead->getIdPrueba() . " - inf. iso2:" . $cConsRead->getCodIdiomaInforme() . " - bar:" . $cConsRead->getIdBaremo() . " - concepto:" . $cConsRead->getConcepto() . "]";
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}
			$rsProceso_pruebas->MoveNext();
		}
		if ($dTotalCoste > 0 ){
			//Lo descontamos de la empresa
			$sDescuentaMatriz = $cEmpresaConsumo->getDescuentaMatriz();
			if (!empty($sDescuentaMatriz)){
				//Consultamos los datos de la empresa a la que realmente se le descontará Matriz
				$cMatrizConsumo = new Empresas();
				$cMatrizConsumoDB = new EmpresasDB($aux);
				$cMatrizConsumo->setIdEmpresa($sDescuentaMatriz);
				$cMatrizConsumo = $cMatrizConsumoDB->readEntidad($cMatrizConsumo);
				$dResto= ($cMatrizConsumo->getDongles() - $dTotalCoste);
				$cMatrizConsumo->setDongles($dResto);
				$cMatrizConsumo->setPassword("");	//Para que no modifique la pass
				$cMatrizConsumoDB->modificar($cMatrizConsumo);

				$sTypeError=date('d/m/Y H:i:s') . " Descontadas [" . $dTotalCoste . "] unidades de la Matriz [" . $sDescuentaMatriz . "] : ";
				$sTypeError.= $sSQL;
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}else {
				$dResto= ($cEmpresaConsumo->getDongles() - $dTotalCoste);
				$cEmpresaConsumo->setDongles($dResto);
				$cEmpresaConsumo->setPassword("");	//Para que no modifique la pass
				$cEmpresaConsumoDB->modificar($cEmpresaConsumo);
			}

		}

		return $retorno;
	}
=======
>>>>>>> ef67b2adad35376e7004f53c2ad7cef5f1096846
}//Fin de la Clase CandidatosDB
?>
