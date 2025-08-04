<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla correos
**/
class CorreosDB
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
		$sql="";
		require_once(constant("DIR_WS_COM") . "Correos/Correos.php");
		$cCorreo= new Correos();

		$cCorreo->setNombre($cEntidad->getNombre());

		$cCorreo = $this->consultaPorNombre($cCorreo);

		if($cCorreo->getIdCorreo()==""){
			$newId = $this->getSiguienteId($cEntidad);

			$sql = "INSERT INTO correos (";
			$sql .= "idCorreo" . ",";
			$sql .= "idTipoCorreo" . ",";
			$sql .= "idEmpresa" . ",";
			$sql .= "nombre" . ",";
			$sql .= "asunto" . ",";
			$sql .= "cuerpo" . ",";
			$sql .= "descripcion" . ",";
			$sql .= "fecAlta" . ",";
			$sql .= "fecMod" . ",";
			$sql .= "usuAlta" . ",";
			$sql .= "usuMod" . ")";
			$sql .= " VALUES (";
			$sql .= $aux->qstr($newId, false) . ",";
			$sql .= $aux->qstr($cEntidad->getIdTipoCorreo(), false) . ",";
			$sql .= $aux->qstr($cEntidad->getIdEmpresa(), false) . ",";
			$sql .= $aux->qstr($cEntidad->getNombre(), false) . ",";
			$sql .= $aux->qstr($cEntidad->getAsunto(), false) . ",";
			$sql .= $aux->qstr($cEntidad->getCuerpo(), false) . ",";
			$sql .= $aux->qstr($cEntidad->getDescripcion(), false) . ",";
			$sql .= $aux->sysTimeStamp . ",";
			$sql .= $aux->sysTimeStamp . ",";
			$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
			$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
			if($aux->Execute($sql) === false){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][CorreosDB]";
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
		}else{
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . "nombre Existe" . constant("MNT_ALTA") . "][CorreosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			return 0;
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

		$sql  = "SELECT MAX(idCorreo) AS Max FROM correos ";
		$sql  .=" WHERE idTipoCorreo=" . $aux->qstr($cEntidad->getIdTipoCorreo(), false) . " ";
		$sql  .=" AND  idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$rs = $aux->Execute($sql);
		$newId=0;
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][CorreosDB]";
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
//		echo "idAnterior: " .  $_POST['fIdTipoCorreoAnterior'];
//		exit;
		$sql="";
		$retorno=true;

		require_once(constant("DIR_WS_COM") . "Correos/Correos.php");
		$cCorreo= new Correos();

		if($_POST['fIdTipoCorreoAnterior']!= $cEntidad->getIdTipoCorreo()){
			$tipoAModificar = $cEntidad->getIdTipoCorreo();
			$cEntidad->setIdTipoCorreo($_POST['fIdTipoCorreoAnterior']);
			$this->borrar($cEntidad);
			$cEntidad->setIdTipoCorreo($tipoAModificar);
			$newId = $this->getSiguienteId($cEntidad);
			$cEntidad->setIdCorreo($newId);
			$this->insertar($cEntidad);
		}else{
			$cCorreo->setNombre($cEntidad->getNombre());

			$cCorreo = $this->consultaPorNombre($cCorreo);

			if($cCorreo->getIdCorreo()==""){

				$sql = "UPDATE correos SET ";
				$sql .= "idCorreo=" . $aux->qstr($cEntidad->getIdCorreo(), false) . ", ";
				$sql .= "idTipoCorreo=" . $aux->qstr($cEntidad->getIdTipoCorreo(), false) . ", ";
				$sql .= "idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ", ";
				$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
				$sql .= "asunto=" . $aux->qstr($cEntidad->getAsunto(), false) . ", ";
				$sql .= "cuerpo=" . $aux->qstr($cEntidad->getCuerpo(), false) . ", ";
				$sql .= "descripcion=" . $aux->qstr($cEntidad->getDescripcion(), false) . ", ";
				$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
				$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
				$sql .= " WHERE ";
				$sql .="idCorreo=" . $aux->qstr($cEntidad->getIdCorreo(), false) . " AND idTipoCorreo=" . $aux->qstr($cEntidad->getIdTipoCorreo(), false) . " AND idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
			}else{
				if($cCorreo->getIdCorreo()== $cEntidad->getIdCorreo() && $cCorreo->getIdTipoCorreo() == $cEntidad->getIdTipoCorreo()){
					$sql = "UPDATE correos SET ";
					$sql .= "idCorreo=" . $aux->qstr($cEntidad->getIdCorreo(), false) . ", ";
					$sql .= "idTipoCorreo=" . $aux->qstr($cEntidad->getIdTipoCorreo(), false) . ", ";
					$sql .= "idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ", ";
					$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
					$sql .= "asunto=" . $aux->qstr($cEntidad->getAsunto(), false) . ", ";
					$sql .= "cuerpo=" . $aux->qstr($cEntidad->getCuerpo(), false) . ", ";
					$sql .= "descripcion=" . $aux->qstr($cEntidad->getDescripcion(), false) . ", ";
					$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
					$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
					$sql .= " WHERE ";
					$sql .="idCorreo=" . $aux->qstr($cEntidad->getIdCorreo(), false) . " AND idTipoCorreo=" . $aux->qstr($cEntidad->getIdTipoCorreo(), false) . " AND idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
				}else{
					$retorno=false;
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . "Nombre existe" .constant("MNT_MODIFICAR") . "][CorreosDB]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					return false;
				}
			}
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][CorreosDB]";
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
			$sql  ="DELETE FROM correos ";
			$sql  .="WHERE ";
			if ($cEntidad->getIdCorreo() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idCorreo=" . $aux->qstr($cEntidad->getIdCorreo(), false) . " ";
			}
			if ($cEntidad->getIdTipoCorreo() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idTipoCorreo=" . $aux->qstr($cEntidad->getIdTipoCorreo(), false) . " ";
			}
			if ($cEntidad->getIdEmpresa() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
			}
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][CorreosDB]";
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

		$sql = "SELECT *  FROM correos WHERE ";
		$sql  .="idCorreo=" . $aux->qstr($cEntidad->getIdCorreo(), false) . " AND idTipoCorreo=" . $aux->qstr($cEntidad->getIdTipoCorreo(), false) . " AND idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdCorreo($arr['idCorreo']);
					$cEntidad->setIdTipoCorreo($arr['idTipoCorreo']);
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setAsunto($arr['asunto']);
					$cEntidad->setCuerpo($arr['cuerpo']);
					$cEntidad->setDescripcion($arr['descripcion']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][CorreosDB]";
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

	function consultaPorNombre($cEntidad)
	{
		$aux			= $this->conn;

		$sql = "SELECT *  FROM correos WHERE ";
		$sql  .="UPPER(nombre) = " . $aux->qstr(strtoupper($cEntidad->getNombre()), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdCorreo($arr['idCorreo']);
					$cEntidad->setIdTipoCorreo($arr['idTipoCorreo']);
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setAsunto($arr['asunto']);
					$cEntidad->setCuerpo($arr['cuerpo']);
					$cEntidad->setDescripcion($arr['descripcion']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][CorreosDB]";
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
		$sql.="SELECT * FROM correos ";
		if ($cEntidad->getIdCorreo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCorreo=" . $aux->qstr($cEntidad->getIdCorreo(), false);
		}
		if ($cEntidad->getIdTipoCorreo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTipoCorreo=" . $aux->qstr($cEntidad->getIdTipoCorreo(), false);
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
		if ($cEntidad->getAsunto() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(asunto) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getAsunto() . "%") . ")";
		}
		if ($cEntidad->getCuerpo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(cuerpo) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCuerpo() . "%") . ")";
		}
		if ($cEntidad->getDescripcion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descripcion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescripcion() . "%") . ")";
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
		$sql.="SELECT * FROM correos ";
		if ($cEntidad->getIdCorreo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idCorreo=" . $aux->qstr($cEntidad->getIdCorreo(), false);
		}
		if ($cEntidad->getIdTipoCorreo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTipoCorreo=" . $aux->qstr($cEntidad->getIdTipoCorreo(), false);
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
		if ($cEntidad->getAsunto() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(asunto) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getAsunto() . "%") . ")";
		}
		if ($cEntidad->getCuerpo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(cuerpo) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCuerpo() . "%") . ")";
		}
		if ($cEntidad->getDescripcion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descripcion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescripcion() . "%") . ")";
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

		$sql = "UPDATE correos SET  ";
		return $retorno;
	}
}//Fin de la Clase CorreosDB
?>
