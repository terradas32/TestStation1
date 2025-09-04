<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla candidatos
**/
class Candidatos_backDB
{

	/**
	* Declaración de las variables de Entidad.
	**/
		var $conn; //Conexión con la BBDD

		var $sSQL; //Última query ejecutada

		var $msg_Error; //Array con los mensajes de Warning y Errores

		var $bMagic; //Magic Quotes

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
		$this->bMagic = true;
	}

	function masiva($cEntidad)
	{
		$aux			= $this->conn;
	echo "viene";
		require_once(constant("DIR_WS_COM") . "Upload.php");
		$img0 = new Upload("fFichero");
		$img0->bAutoRenombrar = false;
		$img0->bSobreEscribir = true;
		$img0->image_mini = true;	// crear mini.
		$img0->image_mini_x = 25;	// Ancho por defecto de la miñatura.
		$img0->image_resize = true;	// redimensionar la imagen
		$img0->image_x = 150;	// Ancho por defecto
		$img0->jpeg_quality = 75;
		$sDirImg="ficheros";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if (!$img0->UploadFile($spath . $sDirImg))
		{

			$this->msg_Error = $img0->get_errores();
			return 0;
		}else{
			if (!$this->importar($img0, $cEntidad)){
				return 0;
			}
		}
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

		$sql = "INSERT INTO candidatos_back (";
		$sql .= "idEmpresa" . ",";
		$sql .= "idProceso" . ",";
		$sql .= "idCandidato" . ",";
		$sql .= "nombre" . ",";
		$sql .= "apellido1" . ",";
		$sql .= "apellido2" . ",";
		$sql .= "dni" . ",";
		$sql .= "mail" . ",";
		$sql .= "password" . ",";
		$sql .= "sexo" . ",";
		$sql .= "fechaNacimiento" . ",";
		$sql .= "idPais" . ",";
		$sql .= "idProvincia" . ",";
		$sql .= "idCiudad" . ",";
		$sql .= "idZona" . ",";
		$sql .= "direccion" . ",";
		$sql .= "codPostal" . ",";
		$sql .= "idFormacion" . ",";
		$sql .= "idNivel" . ",";
		$sql .= "idArea" . ",";
		$sql .= "idTipoTelefono" . ",";
		$sql .= "telefono" . ",";
		$sql .= "estadoCivil" . ",";
		$sql .= "nacionalidad" . ",";
		$sql .= "informado" . ",";
		$sql .= "finalizado" . ",";
		$sql .= "fechaFinalizado" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($cEntidad->getIdEmpresa(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdProceso(), false) . ",";
		$sql .= $aux->qstr($newId, false) . ",";
		$sql .= $aux->qstr($cEntidad->getNombre(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getApellido1(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getApellido2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDni(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getMail(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPassword(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getSexo(), false) . ",";
		$sql .= $aux->DBDate($cEntidad->getFechaNacimiento()) . ",";
		$sql .= $aux->qstr($cEntidad->getIdPais(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdProvincia(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdCiudad(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdZona(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDireccion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCodPostal(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdFormacion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdNivel(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdArea(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdTipoTelefono(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getTelefono(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getEstadoCivil(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNacionalidad(), false) . ",";
		$sql .= $aux->qstr(intval($cEntidad->getInformado()), false) . ",";
		$sql .= $aux->qstr(intval($cEntidad->getFinalizado()), false) . ",";
		$sql .= $aux->DBDate($cEntidad->getFechaFinalizado()) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
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
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : $cEntidad->getIdCandidato());
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : $cEntidad->getIdCandidato());
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

		$sql  = "SELECT MAX(idCandidato) AS Max FROM candidatos_back ";
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

		$sql = "UPDATE candidatos_back SET ";
		$sql .= "idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ", ";
		$sql .= "idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . ", ";
		$sql .= "idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . ", ";
		$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
		$sql .= "apellido1=" . $aux->qstr($cEntidad->getApellido1(), false) . ", ";
		$sql .= "apellido2=" . $aux->qstr($cEntidad->getApellido2(), false) . ", ";
		$sql .= "dni=" . $aux->qstr($cEntidad->getDni(), false) . ", ";
		$sql .= "mail=" . $aux->qstr($cEntidad->getMail(), false) . ", ";
		$sql .= "password=" . $aux->qstr($cEntidad->getPassword(), false) . ", ";
		$sql .= "sexo=" . $aux->qstr($cEntidad->getSexo(), false) . ", ";
		$sql .= "fechaNacimiento=" . $aux->DBDate($cEntidad->getFechaNacimiento()) . ",";
		$sql .= "idPais=" . $aux->qstr($cEntidad->getIdPais(), false) . ", ";
		$sql .= "idProvincia=" . $aux->qstr($cEntidad->getIdProvincia(), false) . ", ";
		$sql .= "idCiudad=" . $aux->qstr($cEntidad->getIdCiudad(), false) . ", ";
		$sql .= "idZona=" . $aux->qstr($cEntidad->getIdZona(), false) . ", ";
		$sql .= "direccion=" . $aux->qstr($cEntidad->getDireccion(), false) . ", ";
		$sql .= "codPostal=" . $aux->qstr($cEntidad->getCodPostal(), false) . ", ";
		$sql .= "idFormacion=" . $aux->qstr($cEntidad->getIdFormacion(), false) . ", ";
		$sql .= "idNivel=" . $aux->qstr($cEntidad->getIdNivel(), false) . ", ";
		$sql .= "idArea=" . $aux->qstr($cEntidad->getIdArea(), false) . ", ";
		$sql .= "idTipoTelefono=" . $aux->qstr($cEntidad->getIdTipoTelefono(), false) . ", ";
		$sql .= "telefono=" . $aux->qstr($cEntidad->getTelefono(), false) . ", ";
		$sql .= "estadoCivil=" . $aux->qstr($cEntidad->getEstadoCivil(), false) . ", ";
		$sql .= "nacionalidad=" . $aux->qstr($cEntidad->getNacionalidad(), false) . ", ";
		$sql .= "informado=" . $aux->qstr(intval($cEntidad->getInformado()), false) . ", ";
		$sql .= "finalizado=" . $aux->qstr(intval($cEntidad->getFinalizado()), false) . ", ";
		$sql .= "fechaFinalizado=" . $aux->DBDate($cEntidad->getFechaFinalizado()) . ",";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " ";
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
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : $cEntidad->getIdCandidato());
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : $cEntidad->getIdCandidato());
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
			$sql  ="DELETE FROM candidatos_back ";
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
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : $cEntidad->getIdCandidato());
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : $cEntidad->getIdCandidato());
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

		$sql = "SELECT *  FROM candidatos_back WHERE ";
		$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND idCandidato=" . $aux->qstr($cEntidad->getIdCandidato(), false) . " ";
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
					$cEntidad->setSexo($arr['sexo']);
					$cEntidad->setFechaNacimiento($arr['fechaNacimiento']);
					$cEntidad->setIdPais($arr['idPais']);
					$cEntidad->setIdProvincia($arr['idProvincia']);
					$cEntidad->setIdCiudad($arr['idCiudad']);
					$cEntidad->setIdZona($arr['idZona']);
					$cEntidad->setDireccion($arr['direccion']);
					$cEntidad->setCodPostal($arr['codPostal']);
					$cEntidad->setIdFormacion($arr['idFormacion']);
					$cEntidad->setIdNivel($arr['idNivel']);
					$cEntidad->setIdArea($arr['idArea']);
					$cEntidad->setIdTipoTelefono($arr['idTipoTelefono']);
					$cEntidad->setTelefono($arr['telefono']);
					$cEntidad->setEstadoCivil($arr['estadoCivil']);
					$cEntidad->setNacionalidad($arr['nacionalidad']);
					$cEntidad->setInformado($arr['informado']);
					$cEntidad->setFinalizado($arr['finalizado']);
					$cEntidad->setFechaFinalizado($arr['fechaFinalizado']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
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

		$sql = "SELECT *  FROM candidatos_back WHERE ";
		$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $aux->qstr($cEntidad->getIdProceso(), false) . " AND UPPER(mail) =" . $aux->qstr(strtoupper($cEntidad->getMail()), false) . " ";
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
					$cEntidad->setSexo($arr['sexo']);
					$cEntidad->setFechaNacimiento($arr['fechaNacimiento']);
					$cEntidad->setIdPais($arr['idPais']);
					$cEntidad->setIdProvincia($arr['idProvincia']);
					$cEntidad->setIdCiudad($arr['idCiudad']);
					$cEntidad->setIdZona($arr['idZona']);
					$cEntidad->setDireccion($arr['direccion']);
					$cEntidad->setCodPostal($arr['codPostal']);
					$cEntidad->setIdFormacion($arr['idFormacion']);
					$cEntidad->setIdNivel($arr['idNivel']);
					$cEntidad->setIdArea($arr['idArea']);
					$cEntidad->setIdTipoTelefono($arr['idTipoTelefono']);
					$cEntidad->setTelefono($arr['telefono']);
					$cEntidad->setEstadoCivil($arr['estadoCivil']);
					$cEntidad->setNacionalidad($arr['nacionalidad']);
					$cEntidad->setInformado($arr['informado']);
					$cEntidad->setFinalizado($arr['finalizado']);
					$cEntidad->setFechaFinalizado($arr['fechaFinalizado']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
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
		$sql.="SELECT * FROM candidatos_back ";
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
		if ($cEntidad->getSexo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(sexo) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getSexo() . "%") . ")";
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
			$sql .="idPais>=" . $aux->qstr($cEntidad->getIdPais(), false);
		}
		if ($cEntidad->getIdPaisHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPais<=" . $aux->qstr($cEntidad->getIdPaisHast(), false);
		}
		if ($cEntidad->getIdProvincia() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idProvincia>=" . $aux->qstr($cEntidad->getIdProvincia(), false);
		}
		if ($cEntidad->getIdProvinciaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idProvincia<=" . $aux->qstr($cEntidad->getIdProvinciaHast(), false);
		}
		if ($cEntidad->getIdCiudad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCiudad>=" . $aux->qstr($cEntidad->getIdCiudad(), false);
		}
		if ($cEntidad->getIdCiudadHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCiudad<=" . $aux->qstr($cEntidad->getIdCiudadHast(), false);
		}
		if ($cEntidad->getIdZona() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idZona>=" . $aux->qstr($cEntidad->getIdZona(), false);
		}
		if ($cEntidad->getIdZonaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idZona<=" . $aux->qstr($cEntidad->getIdZonaHast(), false);
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
			$sql .="idFormacion>=" . $aux->qstr($cEntidad->getIdFormacion(), false);
		}
		if ($cEntidad->getIdFormacionHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idFormacion<=" . $aux->qstr($cEntidad->getIdFormacionHast(), false);
		}
		if ($cEntidad->getIdNivel() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idNivel>=" . $aux->qstr($cEntidad->getIdNivel(), false);
		}
		if ($cEntidad->getIdNivelHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idNivel<=" . $aux->qstr($cEntidad->getIdNivelHast(), false);
		}
		if ($cEntidad->getIdArea() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idArea>=" . $aux->qstr($cEntidad->getIdArea(), false);
		}
		if ($cEntidad->getIdAreaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idArea<=" . $aux->qstr($cEntidad->getIdAreaHast(), false);
		}
		if ($cEntidad->getIdTipoTelefono() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTipoTelefono>=" . $aux->qstr($cEntidad->getIdTipoTelefono(), false);
		}
		if ($cEntidad->getIdTipoTelefonoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTipoTelefono<=" . $aux->qstr($cEntidad->getIdTipoTelefonoHast(), false);
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
			$sql .="informado>=" . $aux->qstr(intval($cEntidad->getInformado()), false);
		}
		if ($cEntidad->getInformadoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="informado<=" . $aux->qstr($cEntidad->getInformadoHast(), false);
		}
		if ($cEntidad->getFinalizado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="finalizado>=" . $aux->qstr(intval($cEntidad->getFinalizado()), false);
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
		if ($cEntidad->getOrderBy() != ""){
			$sql .=" ORDER BY " . $cEntidad->getOrderBy();
			if ($cEntidad->getOrder() != ""){
				$sql .=" " . $cEntidad->getOrder();
			}
		}
		$this->sSQL=$sql;
		$_SESSION['SQLCandidatosDB'] = $sql;
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

		$sql = "UPDATE candidatos_back SET  ";
		return $retorno;
	}

	function importar($sFichero, $cEntidad, &$cFicheroCarga, $sDelimitador=",", $sEncerrado='"')
	{
		$retorno		= 1;
		$bMagic = true;
		//Vamos a pasar los datos de un fichero con lo que no tiene el escapado automatico de PHP y hay que escaparlo
		if (false){
			$bMagic = false;
		}
		$iCuentaNuevos =0;
		$iCuentaModificados =0;
		if (function_exists('mime_content_type')) {
			$src_type = mime_content_type($sFichero);
		} else if (function_exists('finfo_file')) {
			$info = finfo_open(FILEINFO_MIME);
			$src_type = finfo_file($info, $sFichero);
			finfo_close($info);
		}

		if ($src_type == '') {
			if($cFicheroCarga->getTipo()!=""){
				$src_type = $cFicheroCarga->getTipo();
			}else{
				$src_type = "application/unknown";
			}
		}

		switch ($src_type)
		{

			//case "application/octet-stream":
			case "application/vnd.ms-excel":
			case "text/comma-separated-values":
//				$fc = iconv('ISO-8859-1', 'utf-8', file_get_contents($sFichero));
//				file_put_contents($sFichero, $fc);
//				//$fp = fopen($sFichero, "r");
				$fp = @fopen($sFichero,"r");

				if (!$fp) {
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . " Error NO se encuentra el fichero [" . $sFichero . "]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " \n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					return 0;
				}
				$aSRCCabecera;
				$iSRCColumnas = 0;
				$i = 0;
				while (($data = fgetcsv($fp, 32000, $sDelimitador, $sEncerrado )) !== FALSE)
				{
					if ($i == 0)
					{
						$aSRCCabecera	=	$data;
						$iSRCColumnas = sizeof($aSRCCabecera);
					}else{
						break;
					}
					$i++;
				}
				fclose($fp);

				//Plantillas para confirmación del fichero
				$aCabecera = $this->getCabFile();
				$iColumnas = sizeof($aCabecera);
				//////////////////////////////////////////

				if ($iSRCColumnas != $iColumnas){
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . " Error columnas [Tiene: " . $iSRCColumnas  . "][Se espera: " . $iColumnas . "]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					return 0;
				}else{
					$aCab=array_keys($aCabecera);
					for ($i=0; $i < $iColumnas; $i++){
						if (trim($aSRCCabecera[$i]) != trim($aCab[$i]))
						{
							$this->msg_Error	= array();
							$sTypeError	=	date('d/m/Y H:i:s') . " Error Formato columnas [Se espera: " . $aCab[$i] . " en columna " . ($i+1) . "][No existe]";
							$this->msg_Error[]	= $sTypeError;
							error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
							return 0;
						}
					}
				}
				$retorno = is_file(constant("DIR_WS_COM") . "Candidatos_back/Candidatos_backDB.php");
				if (!$retorno){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error Class Not Found [" . constant("MNT_ALTA") . "][Imp_rentsica_articulosDB::_rentsica_articulosDB]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " \n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					return 0;
				}
				if ($retorno){
					require_once(constant("DIR_WS_COM") . "Candidatos_back/Candidatos_backDB.php");
					require_once(constant("DIR_WS_COM") . "Candidatos_back/Candidatos_back.php");
					$cCandidatos_backDB	= new Candidatos_backDB($aux);  // Entidad DB
					$cCandidatos_back	= new Candidatos_back();  // Entidad

					$cCandidatos_back->setIdEmpresa($cEntidad->getIdEmpresa());
					$cCandidatos_back->setIdProceso($cEntidad->getIdProceso());

//					$fc = iconv('ISO-8859-1', 'utf-8', file_get_contents($sFichero));
//					file_put_contents($sFichero, $fc);
					//$fp = fopen($sFichero, "r");
					$fp = @fopen($sFichero,"r");

					if (!$fp) {
						$this->msg_Error	= array();
						$sTypeError	=	date('d/m/Y H:i:s') . " Error NO se encuentra el fichero [" . $sFichero . "]";
						$this->msg_Error[]	= $sTypeError;
						error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						return 0;
					}
//					$sSQL = 'TRUNCATE TABLE `candidatos_back` ';
//					if($aux->Execute($sSQL) === false){
//						echo("<br>Error SQL:<br>" . $sSQL);
//						exit;
//					}

					@set_time_limit(0);
					$iCont = 0;
					$i = 0;
					while (($data = fgetcsv($fp, 32000, $sDelimitador, $sEncerrado )) !== FALSE)
					{
						if ($i > 0)
						{
							if(strlen($data[0]) > 0)
							{

								for($j=0; $j < $iColumnas; $j++)
								{
									if($aCabecera[$aSRCCabecera[$j]] != "")
									{
    					   				$funcionSet = "set" . $aCabecera[trim($aSRCCabecera[$j])];
    					   				$funcionGet = "get" . $aCabecera[trim($aSRCCabecera[$j])];
    					   				$sValor = @trim($data[$j], ' "”');
    					   				$iLong = strlen($sValor);
    					   				$pos = strrpos($sValor, '"');
    					   				if ($pos !== false) {
    					   				    $sValor = substr($sValor, 0, $pos);
                                        }
                                        $pos = strrpos($sValor, '”');
    					   				if ($pos !== false) {
    					   				    $sValor = substr($sValor, 0, $pos);
                                        }
                                        $sValor = addslashes($sValor);
       									$cCandidatos_back->$funcionSet($sValor);
					   				}
								}
    							$cCandidatos_backDB->bMagic = $bMagic;
    							if($cCandidatos_back->$funcionGet() != ""){
        							$cCand = new Candidatos_back();
        							$cCand->setIdEmpresa($cEntidad->getIdEmpresa());
        							$cCand->setIdProceso($cEntidad->getIdProceso());
        							$cCand->setMail($cCandidatos_back->getMail());

        							$cCand = $this->consultaPorMail($cCand);

        							if($cCand->getIdCandidato()==""){
        								$iCuentaNuevos++;
										$this->insertar($cCandidatos_back);
									}else{
        								$iCuentaModificados++;
        								$this->modificar($cCandidatos_back);
        							}
        							$iCont++;
    							}
							}
						}
						$i++;
					}
					if (empty($iCont)){
						$this->msg_Error	= array();
						$sTypeError	=	date('d/m/Y H:i:s') . " Error NO hay datos que cargar [" . constant("MNT_ALTA") . "][[Imp_rentsica_articulosDB][_rentsica_articulosDB]";
						$this->msg_Error[]	= $sTypeError;
						error_log($sTypeError . " \n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						$retorno=0;
					}
					@unlink($fp);
					fclose($fp);

				}
				break;
			default:
				$retorno = 0;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error Tipo Fichero  [" . $cFicheroCarga->getTipo() . "][Formato no soportado]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				break;
		} // end switch
		echo "<br />Candidatos nuevos: " . $iCuentaNuevos . "<br />";
		echo "<br />Candidatos modificados: " . $iCuentaModificados . "<br />";
		return $retorno;
	}

	function getCabFile()
	{
		$campos=array("Nombre"=>"Nombre",
            "Apellido1"=>"Apellido1",
			"Apellido2"=>"Apellido2",
			"Mail"=>"Mail");
		return $campos;
	}

	function seProcedure($cEntidad){
		$aux			= $this->conn;

		/*************************************************************************************************
			Insertamos en el histórico
		 ************************************************************************************************/

 		$sql = "INSERT INTO `hist_rentsica_articulos` (`idFichero`, `Codigo`, `Referencia`, `Nombre`, `Catalogo`, `Lineadeproducto`, `Importe`, `Iva`,`Grafico`) ";
 		$sql .= "SELECT " . $cEntidad->getIdFicheroSabana() . " AS idCarga, `Codigo`, `Referencia`, `Nombre`, `Catalogo`, `Lineadeproducto`, `Importe`, `Iva`, `Grafico` ";
 		$sql .= "FROM _rentsica_articulos;";
		if($aux->Execute($sql) === false){
			echo("<br>Error SQL:<br>" . $sql);
		}

		$sql = 'DELETE FROM `shop_productos` ';
		$sql.= 'WHERE Lineadeproducto <> 0';
		if($aux->Execute($sql) === false){
            echo("<br>Error SQL:<br>" . $sql);
		}

 		$sql = "INSERT INTO `shop_productos` (idCategoria, idProveedor, nombre, idFabricante, modelo, idEstado, codBar, impuesto, pvp, baseImponible, precioProveedor, referencia, precioMedioProducto, stock, novedad, fecDisponible, descripcion, peso, urlProducto, firma, idTipoFirma, pathProducto1,  Lineadeproducto, fecAlta, fecMod, usuAlta, usuMod) ";
        $sql .= "SELECT  Catalogo, '' AS idProveedor,  Nombre, '' AS idFabricante, '' AS modelo, '' AS idEstado, '' AS codBar, Iva, (((Importe*Iva)/100)+Importe) AS pvp, Importe, '' AS precioProveedor, Referencia, '' AS precioMedioProducto, '' AS stock, '' AS novedad, now() AS fecDisponible, Nombre AS descripcion, '' AS peso, '' AS urlProducto, '' AS firma, '' AS idTipoFirma, CONCAT('imgShop_productos/', Grafico) AS pathProducto1, Lineadeproducto, now() AS fecAlta, now() AS fecMod, " . $cEntidad->getUsuAlta() . " AS usuAlta, " . $cEntidad->getUsuAlta() . " AS usuMod ";
 		$sql .= "FROM _rentsica_articulos;";
		if($aux->Execute($sql) === false){
			echo("<br>Error SQL:<br>" . $sql);
		}
	}
	/*************************************************************************************************
		Recogemos la última carga válida del histórico
		Se Borra el último intento de carga en el historico ya que ha habido alguna ERROR
		en la importación.
	 ************************************************************************************************/
	function getHistorico($cEntidad){
		$aux			= $this->conn;
		$retorno		= 1;

		$sSQL = "DELETE FROM `hist_rentsica_articulos` WHERE idFichero=" . $cEntidad->getIdFicheroSabana();
		if($aux->Execute($sSQL) === false){
			$retorno = 0;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error Tipo SQL  [" . $sSQL . "][Imp_rentsica_articulosDB::getHistorico(DELETE)]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
		$sSQL = 'TRUNCATE TABLE `_rentsica_articulos` ';
		if($aux->Execute($sSQL) === false){
			$retorno = 0;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error Tipo SQL  [" . $sSQL . "][Imp_rentsica_articulosDB::getHistorico(TRUNCATE)]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
 		$sSQL = "INSERT INTO `_rentsica_articulos` (`Codigo`, `Referencia`, `Nombre`, `Catalogo`, `Lineadeproducto`, `Importe`, `Iva`,`Grafico`)";
 		$sSQL .= "SELECT `Codigo`, `Referencia`, `Nombre`, `Catalogo`, `Lineadeproducto`, `Importe`, `Iva`,`Grafico` ";
 		$sSQL .= "FROM hist_rentsica_articulos ";
 		$sSQL .= "WHERE `idFichero`=" . ($cEntidad->getIdFicheroSabana() -1);
		if($aux->Execute($sSQL) === false){
			$retorno = 0;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error Tipo SQL  [" . $sSQL . "][Imp_rentsica_articulosDB::getHistorico(LOAD HISTORICO)]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}

		return $retorno;
	}
}//Fin de la Clase Candidatos_backDB
?>
