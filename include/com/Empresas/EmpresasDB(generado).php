<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla empresas
**/
class EmpresasDB
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
	
		require_once(constant("DIR_WS_COM") . "Upload.php");
		$img0 = new Upload("fPathLogo");
		$img0->bAutoRenombrar = false;
		$img0->bSobreEscribir = true;
		$img0->image_mini = true;	// crear mini.
		$img0->image_mini_x = 25;	// Ancho por defecto de la miñatura.
		$img0->image_resize = true;	// redimensionar la imagen
		$img0->image_x = 150;	// Ancho por defecto
		$img0->jpeg_quality = 75;
		$sDirImg="imgEmpresas";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if (!$img0->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img0->get_errores();
			return 0;
		}else{
			$cEntidad->setPathLogo($img0->getPath_WS());
		}
		$sql = "INSERT INTO empresas (";
		$sql .= "idEmpresa" . ",";
		$sql .= "idPadre" . ",";
		$sql .= "nombre" . ",";
		$sql .= "cif" . ",";
		$sql .= "usuario" . ",";
		$sql .= "password" . ",";
		$sql .= "pathLogo" . ",";
		$sql .= "mail" . ",";
		$sql .= "mail2" . ",";
		$sql .= "mail3" . ",";
		$sql .= "distribuidor" . ",";
		$sql .= "prepago" . ",";
		$sql .= "ncandidatos" . ",";
		$sql .= "dongles" . ",";
		$sql .= "idPais" . ",";
		$sql .= "direccion" . ",";
		$sql .= "umbral_aviso" . ",";
		$sql .= "orden" . ",";
		$sql .= "indentacion" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($newId, false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdPadre(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNombre(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCif(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuario(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPassword(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPathLogo(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getMail(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getMail2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getMail3(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDistribuidor(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPrepago(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNcandidatos(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDongles(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdPais(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDireccion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUmbral_aviso(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getOrden(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIndentacion(), false) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][EmpresasDB]";
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
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : "0");
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : "0");
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
	
		$sql  = "SELECT MAX(idEmpresa) AS Max FROM empresas ";
		$sql  .="";
		$rs = $aux->Execute($sql);
		$newId=0;
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][EmpresasDB]";
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
		require_once(constant("DIR_WS_COM") . "Upload.php");
		$img0 = new Upload("fPathLogo");
		$sDirImg="imgEmpresas";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if (!$img0->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img0->get_errores();
			return false;
		}else{
			$cEntidad->setPathLogo($img0->getPath_WS());
		}
	
		$sql = "UPDATE empresas SET ";
		$sql .= "idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ", ";
		$sql .= "idPadre=" . $aux->qstr($cEntidad->getIdPadre(), false) . ", ";
		$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
		$sql .= "cif=" . $aux->qstr($cEntidad->getCif(), false) . ", ";
		$sql .= "usuario=" . $aux->qstr($cEntidad->getUsuario(), false) . ", ";
		$sql .= "password=" . $aux->qstr($cEntidad->getPassword(), false) . ", ";
		if ($cEntidad->getPathLogo() != "")
			$sql .= "pathLogo=" . $aux->qstr($cEntidad->getPathLogo(), false) . ", ";
		$sql .= "mail=" . $aux->qstr($cEntidad->getMail(), false) . ", ";
		$sql .= "mail2=" . $aux->qstr($cEntidad->getMail2(), false) . ", ";
		$sql .= "mail3=" . $aux->qstr($cEntidad->getMail3(), false) . ", ";
		$sql .= "distribuidor=" . $aux->qstr($cEntidad->getDistribuidor(), false) . ", ";
		$sql .= "prepago=" . $aux->qstr($cEntidad->getPrepago(), false) . ", ";
		$sql .= "ncandidatos=" . $aux->qstr($cEntidad->getNcandidatos(), false) . ", ";
		$sql .= "dongles=" . $aux->qstr($cEntidad->getDongles(), false) . ", ";
		$sql .= "idPais=" . $aux->qstr($cEntidad->getIdPais(), false) . ", ";
		$sql .= "direccion=" . $aux->qstr($cEntidad->getDireccion(), false) . ", ";
		$sql .= "umbral_aviso=" . $aux->qstr($cEntidad->getUmbral_aviso(), false) . ", ";
		$sql .= "orden=" . $aux->qstr($cEntidad->getOrden(), false) . ", ";
		$sql .= "indentacion=" . $aux->qstr($cEntidad->getIndentacion(), false) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][EmpresasDB]";
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
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : "0");
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : "0");
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
			$sql  ="DELETE FROM empresas ";
			$sql  .="WHERE ";
			if ($cEntidad->getIdEmpresa() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
			}
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][EmpresasDB]";
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
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : "0");
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : "0");
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
	
		$sql = "SELECT *  FROM empresas WHERE ";
		$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setIdPadre($arr['idPadre']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setCif($arr['cif']);
					$cEntidad->setUsuario($arr['usuario']);
					$cEntidad->setPassword($arr['password']);
					$cEntidad->setPathLogo($arr['pathLogo']);
					$cEntidad->setMail($arr['mail']);
					$cEntidad->setMail2($arr['mail2']);
					$cEntidad->setMail3($arr['mail3']);
					$cEntidad->setDistribuidor($arr['distribuidor']);
					$cEntidad->setPrepago($arr['prepago']);
					$cEntidad->setNcandidatos($arr['ncandidatos']);
					$cEntidad->setDongles($arr['dongles']);
					$cEntidad->setIdPais($arr['idPais']);
					$cEntidad->setDireccion($arr['direccion']);
					$cEntidad->setUmbral_aviso($arr['umbral_aviso']);
					$cEntidad->setOrden($arr['orden']);
					$cEntidad->setIndentacion($arr['indentacion']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][EmpresasDB]";
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
		$sql.="SELECT * FROM empresas ";
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false);
		}
		if ($cEntidad->getIdPadre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPadre>=" . $aux->qstr($cEntidad->getIdPadre(), false);
		}
		if ($cEntidad->getIdPadreHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPadre<=" . $aux->qstr($cEntidad->getIdPadreHast(), false);
		}
		if ($cEntidad->getNombre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nombre) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getNombre() . "%") . ")";
		}
		if ($cEntidad->getCif() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(cif) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCif() . "%") . ")";
		}
		if ($cEntidad->getUsuario() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(usuario) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getUsuario() . "%") . ")";
		}
		if ($cEntidad->getPassword() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(password) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getPassword() . "%") . ")";
		}
		if ($cEntidad->getPathLogo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(pathLogo) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getPathLogo() . "%") . ")";
		}
		if ($cEntidad->getMail() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(mail) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getMail() . "%") . ")";
		}
		if ($cEntidad->getMail2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(mail2) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getMail2() . "%") . ")";
		}
		if ($cEntidad->getMail3() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(mail3) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getMail3() . "%") . ")";
		}
		if ($cEntidad->getDistribuidor() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(distribuidor) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDistribuidor() . "%") . ")";
		}
		if ($cEntidad->getPrepago() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(prepago) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getPrepago() . "%") . ")";
		}
		if ($cEntidad->getNcandidatos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="ncandidatos>=" . $aux->qstr($cEntidad->getNcandidatos(), false);
		}
		if ($cEntidad->getNcandidatosHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="ncandidatos<=" . $aux->qstr($cEntidad->getNcandidatosHast(), false);
		}
		if ($cEntidad->getDongles() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="dongles>=" . $aux->qstr($cEntidad->getDongles(), false);
		}
		if ($cEntidad->getDonglesHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="dongles<=" . $aux->qstr($cEntidad->getDonglesHast(), false);
		}
		if ($cEntidad->getIdPais() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPais=" . $aux->qstr($cEntidad->getIdPais(), false);
		}
		if ($cEntidad->getDireccion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(direccion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDireccion() . "%") . ")";
		}
		if ($cEntidad->getUmbral_aviso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="umbral_aviso>=" . $aux->qstr($cEntidad->getUmbral_aviso(), false);
		}
		if ($cEntidad->getUmbral_avisoHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="umbral_aviso<=" . $aux->qstr($cEntidad->getUmbral_avisoHast(), false);
		}
		if ($cEntidad->getOrden() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="orden>=" . $aux->qstr($cEntidad->getOrden(), false);
		}
		if ($cEntidad->getOrdenHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="orden<=" . $aux->qstr($cEntidad->getOrdenHast(), false);
		}
		if ($cEntidad->getIndentacion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="indentacion>=" . $aux->qstr($cEntidad->getIndentacion(), false);
		}
		if ($cEntidad->getIndentacionHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="indentacion<=" . $aux->qstr($cEntidad->getIndentacionHast(), false);
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
		$_SESSION['SQLEmpresasDB'] = $sql;
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
		$this->sSQL = $_SESSION['SQLEmpresasDB'];
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

		$sql = "UPDATE empresas SET  ";
		if (strtolower($cEntidad->getPathLogo()) == "on"){
			$cEntidad->setPathLogo('');
			$sql .= "pathLogo=" . $aux->qstr($cEntidad->getPathLogo(), false) . ", ";
		}
		$sql = substr($sql,0,strlen($sql)-2);
		$sql .= " WHERE ";
		$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$cEntidadAnterior = $this->readEntidad($cEntidad);
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][EmpresasDBDB::quitaImagen()]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}else{
			@unlink($spath . $cEntidadAnterior->getPathLogo());
			//Borramos la miñatura
			$name= basename($cEntidadAnterior->getPathLogo());
			$namePath=str_replace($name,"m" . $name, $cEntidadAnterior->getPathLogo());
			@unlink($spath . $namePath);
		}
		return $retorno;
	}
}//Fin de la Clase EmpresasDB
?>