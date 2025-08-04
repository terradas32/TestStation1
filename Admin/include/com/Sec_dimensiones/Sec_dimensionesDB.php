<?php 

/**
 * Realiza la operaciones de alta, baja, modificación, borrado,
 * consulta de registros y consulta de numero de elementos sobre
 * la tabla sec_dimensiones
 **/
class Sec_dimensionesDB
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

			$sql = "INSERT INTO sec_dimensiones (";
			$sql .= "IDTIPOPRUEBA" . ",";
			$sql .= "IDCOMPETENCIA" . ",";
			$sql .= "IDDIMENSION" . ",";
			$sql .= "CODIGO" . ",";
			$sql .= "NOMBRE" . ",";
			$sql .= "PUNTUACIONMIN" . ",";
			$sql .= "PUNTUACIONMAX" . ",";
			$sql .= "fecAlta" . ",";
			$sql .= "fecMod" . ",";
			$sql .= "usuAlta" . ",";
			$sql .= "usuMod" . ")";
			$sql .= " VALUES (";
			$sql .= $aux->qstr($cEntidad->getIDTIPOPRUEBA(), false) . ",";
			$sql .= $aux->qstr($cEntidad->getIDCOMPETENCIA(), false) . ",";
			$sql .= $aux->qstr($newId, false) . ",";
			$sql .= $aux->qstr($cEntidad->getCODIGO(), false) . ",";
			$sql .= $aux->qstr($cEntidad->getNOMBRE(), false) . ",";
			$sql .= $aux->qstr($cEntidad->getPUNTUACIONMIN(), false) . ",";
			$sql .= $aux->qstr($cEntidad->getPUNTUACIONMAX(), false) . ",";
			$sql .= $aux->sysTimeStamp . ",";
			$sql .= $aux->sysTimeStamp . ",";
			$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
			$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
			

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

		$sql = "SELECT MAX(IDDIMENSION) AS Max FROM sec_dimensiones ";
		$sql .=" WHERE IDTIPOPRUEBA=" . $aux->qstr($cEntidad->getIDTIPOPRUEBA(), false) . " AND IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA(), false) . " ";
		$rs = $aux->Execute($sql);
		$newId=0;
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

			$sql = "UPDATE sec_dimensiones SET ";
			$sql .= "IDTIPOPRUEBA=" . $aux->qstr($cEntidad->getIDTIPOPRUEBA(), false) . ", ";
			$sql .= "IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA(), false) . ", ";
			$sql .= "IDDIMENSION=" . $aux->qstr($cEntidad->getIDDIMENSION(), false) . ", ";
			$sql .= "CODIGO=" . $aux->qstr($cEntidad->getCODIGO(), false) . ", ";
			$sql .= "NOMBRE=" . $aux->qstr($cEntidad->getNOMBRE(), false) . ", ";
			$sql .= "PUNTUACIONMIN=" . $aux->qstr($cEntidad->getPUNTUACIONMIN(), false) . ", ";
			$sql .= "PUNTUACIONMAX=" . $aux->qstr($cEntidad->getPUNTUACIONMAX(), false) . ", ";
			$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
			$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
			$sql .= " WHERE ";
			$sql .="IDTIPOPRUEBA=" . $aux->qstr($cEntidad->getIDTIPOPRUEBA(), false) . " AND IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA(), false) . " AND IDDIMENSION=" . $aux->qstr($cEntidad->getIDDIMENSION(), false) . " ";
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
			$sql ="DELETE FROM sec_dimensiones ";
			$sql .=" WHERE ";
			if ($cEntidad->getIDTIPOPRUEBA() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql .="IDTIPOPRUEBA=" . $aux->qstr($cEntidad->getIDTIPOPRUEBA(), false) . " ";
			}
			if ($cEntidad->getIDCOMPETENCIA() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql .="IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA(), false) . " ";
			}
			if ($cEntidad->getIDDIMENSION() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql .="IDDIMENSION=" . $aux->qstr($cEntidad->getIDDIMENSION(), false) . " ";
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

		$sql = "SELECT IDTIPOPRUEBA,IDCOMPETENCIA,IDDIMENSION,CODIGO,NOMBRE,PUNTUACIONMIN,PUNTUACIONMAX,fecAlta,fecMod,usuAlta,usuMod FROM sec_dimensiones WHERE ";
		$sql .="IDTIPOPRUEBA=" . $aux->qstr($cEntidad->getIDTIPOPRUEBA(), false) . " AND IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA(), false) . " AND IDDIMENSION=" . $aux->qstr($cEntidad->getIDDIMENSION(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){

					$cEntidad->setIDTIPOPRUEBA($arr['IDTIPOPRUEBA']);
					$cEntidad->setIDCOMPETENCIA($arr['IDCOMPETENCIA']);
					$cEntidad->setIDDIMENSION($arr['IDDIMENSION']);
					$cEntidad->setCODIGO($arr['CODIGO']);
					$cEntidad->setNOMBRE($arr['NOMBRE']);
					$cEntidad->setPUNTUACIONMIN($arr['PUNTUACIONMIN']);
					$cEntidad->setPUNTUACIONMAX($arr['PUNTUACIONMAX']);
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
	function consultarConCod($cEntidad) 
	{

		$aux			= $this->conn;

		$sql = "SELECT IDTIPOPRUEBA,IDCOMPETENCIA,IDDIMENSION,CODIGO,NOMBRE,PUNTUACIONMIN,PUNTUACIONMAX," . "fecAlta" . "," . "fecMod" . ",usuAlta,usuMod FROM sec_dimensiones WHERE ";
		$sql .="IDTIPOPRUEBA=" . $aux->qstr($cEntidad->getIDTIPOPRUEBA(), false) . " AND IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA(), false) . " AND CODIGO=" . $aux->qstr($cEntidad->getCODIGO(), false) . " ";

		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){

					$cEntidad->setIDTIPOPRUEBA($arr['IDTIPOPRUEBA']);
					$cEntidad->setIDCOMPETENCIA($arr['IDCOMPETENCIA']);
					$cEntidad->setIDDIMENSION($arr['IDDIMENSION']);
					$cEntidad->setCODIGO($arr['CODIGO']);
					$cEntidad->setNOMBRE($arr['NOMBRE']);
					$cEntidad->setPUNTUACIONMIN($arr['PUNTUACIONMIN']);
					$cEntidad->setPUNTUACIONMAX($arr['PUNTUACIONMAX']);
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
		$aux			= $this->conn;
	
		$sql="";
		$and = false;
		
		$sql .="SELECT * FROM sec_dimensiones ";
		if ($cEntidad->getIDTIPOPRUEBA() != null && $cEntidad->getIDTIPOPRUEBA() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDTIPOPRUEBA=" . $aux->qstr($cEntidad->getIDTIPOPRUEBA(), false);
		}
		if ($cEntidad->getIDCOMPETENCIA() != null && $cEntidad->getIDCOMPETENCIA() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA(), false);
		}
		if ($cEntidad->getIDDIMENSION() != null && $cEntidad->getIDDIMENSION() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDDIMENSION=" . $aux->qstr($cEntidad->getIDDIMENSION(), false);
		}
		if ($cEntidad->getCODIGO() != null && $cEntidad->getCODIGO() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(CODIGO) LIKE UPPER(" . "%" . $aux->qstr($cEntidad->getCODIGO(), false) . "%" . ")";
		}
		if ($cEntidad->getNOMBRE() != null && $cEntidad->getNOMBRE() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(NOMBRE) LIKE UPPER(" . "%" . $aux->qstr($cEntidad->getNOMBRE(), false) . "%" . ")";
		}
		if ($cEntidad->getPUNTUACIONMIN() != null && $cEntidad->getPUNTUACIONMIN() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="PUNTUACIONMIN>=" . $aux->qstr($cEntidad->getPUNTUACIONMIN(), false);
		}
		if ($cEntidad->getPUNTUACIONMINHast() != null && $cEntidad->getPUNTUACIONMINHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="PUNTUACIONMIN<=" . $aux->qstr($cEntidad->getPUNTUACIONMINHast(), false);
		}
		if ($cEntidad->getPUNTUACIONMAX() != null && $cEntidad->getPUNTUACIONMAX() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="PUNTUACIONMAX>=" . $aux->qstr($cEntidad->getPUNTUACIONMAX(), false);
		}
		if ($cEntidad->getPUNTUACIONMAXHast() != null && $cEntidad->getPUNTUACIONMAXHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="PUNTUACIONMAX<=" . $aux->qstr($cEntidad->getPUNTUACIONMAXHast(), false);
		}
		if ($cEntidad->getFecAlta() != null && $cEntidad->getFecAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta>=" . $aux->qstr($cEntidad->getFecAlta());
		}
		if ($cEntidad->getFecAltaHast() != null && $cEntidad->getFecAltaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sHora = "23:59:59";
			$sql .="fecAlta<=" . $aux->qstr($cEntidad->getFecAltaHast() . $sHora, false);
		}
		if ($cEntidad->getFecAlta() != null && $cEntidad->getFecAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta>=" . $aux->qstr($cEntidad->getFecAlta());
		}
		if ($cEntidad->getFecAltaHast() != null && $cEntidad->getFecAltaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sHora = "23:59:59";
			$sql .="fecAlta<=" . $aux->qstr($cEntidad->getFecAltaHast() . $sHora, false);
		}
		if ($cEntidad->getFecMod() != null && $cEntidad->getFecMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sHora = "23:59:59";
			$sql .="fecMod>=" . $aux->qstr($cEntidad->getFecMod(), false);
		}
		if ($cEntidad->getFecModHast() != null && $cEntidad->getFecModHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sHora = "23:59:59";
			$sql .="fecMod<=" . $aux->qstr($cEntidad->getFecModHast() . $sHora, false);
		}
		if ($cEntidad->getFecMod() != null && $cEntidad->getFecMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod>=" . $aux->qstr($cEntidad->getFecMod());
		}
		if ($cEntidad->getFecModHast() != null && $cEntidad->getFecModHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sHora = "23:59:59";
			$sql .="fecMod<=" . $aux->qstr($cEntidad->getFecModHast() . $sHora, false);
		}
		if ($cEntidad->getUsuAlta() != null && $cEntidad->getUsuAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="usuAlta=" . $aux->qstr($cEntidad->getUsuAlta(), false);
		}
		if ($cEntidad->getUsuMod() != null && $cEntidad->getUsuMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false);
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



} //Fin de la Clase Sec_dimensionesDB
?>