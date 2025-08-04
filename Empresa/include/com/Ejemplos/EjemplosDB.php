<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla ejemplos
**/
class EjemplosDB
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
		$img0 = new Upload("fPath1");
		$img0->bAutoRenombrar = false;
		$img0->bSobreEscribir = true;
		$img0->image_mini = false;	// crear mini.
		$img0->image_mini_x = 25;	// Ancho por defecto de la miñatura.
		$img0->image_resize = false;	// redimensionar la imagen
		$img0->image_x = 150;	// Ancho por defecto
		$img0->jpeg_quality = 75;
		$img1 = new Upload("fPath2");
		$img1->bAutoRenombrar = false;
		$img1->bSobreEscribir = true;
		$img1->image_mini = false;	// crear mini.
		$img1->image_mini_x = 25;	// Ancho por defecto de la miñatura.
		$img1->image_resize = false;	// redimensionar la imagen
		$img1->image_x = 150;	// Ancho por defecto
		$img1->jpeg_quality = 75;
		$img2 = new Upload("fPath3");
		$img2->bAutoRenombrar = false;
		$img2->bSobreEscribir = true;
		$img2->image_mini = false;	// crear mini.
		$img2->image_mini_x = 25;	// Ancho por defecto de la miñatura.
		$img2->image_resize = false;	// redimensionar la imagen
		$img2->image_x = 150;	// Ancho por defecto
		$img2->jpeg_quality = 75;
		$img3 = new Upload("fPath4");
		$img3->bAutoRenombrar = false;
		$img3->bSobreEscribir = true;
		$img3->image_mini = false;	// crear mini.
		$img3->image_mini_x = 25;	// Ancho por defecto de la miñatura.
		$img3->image_resize = false;	// redimensionar la imagen
		$img3->image_x = 150;	// Ancho por defecto
		$img3->jpeg_quality = 75;
		$sDirImg="imgEjemplos";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
		if (!$img0->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img0->get_errores();
			return 0;
		}else{
			$cEntidad->setPath1($img0->getPath_WS());
		}
		if (!$img1->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img1->get_errores();
			return 0;
		}else{
			$cEntidad->setPath2($img1->getPath_WS());
		}
		if (!$img2->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img2->get_errores();
			return 0;
		}else{
			$cEntidad->setPath3($img2->getPath_WS());
		}
		if (!$img3->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img3->get_errores();
			return 0;
		}else{
			$cEntidad->setPath4($img3->getPath_WS());
		}
		
                if($cEntidad->getIdEjemplo() != ""){
                    $newId = $cEntidad->getIdEjemplo();
        		}else{
                    $newId = $this->getSiguienteId($cEntidad);
        		}
		$sql = "INSERT INTO ejemplos (";
		$sql .= "idEjemplo" . ",";
		$sql .= "codIdiomaIso2" . ",";
		$sql .= "idPrueba" . ",";
		$sql .= "enunciado" . ",";
		$sql .= "descripcion" . ",";
		$sql .= "path1" . ",";
		$sql .= "path2" . ",";
		$sql .= "path3" . ",";
		$sql .= "path4" . ",";
		$sql .= "correcto" . ",";
		$sql .= "orden" . ",";
		$sql .= "bajaLog" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($newId, false) . ",";
		$sql .= $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdPrueba(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getEnunciado(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescripcion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPath1(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPath2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPath3(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPath4(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCorrecto(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getOrden(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getBajaLog(), false) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][EjemplosDB]";
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
	
		$sql  = "SELECT MAX(idEjemplo) AS Max FROM ejemplos ";
		$sql  .=" WHERE codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$rs = $aux->Execute($sql);
		$newId=0;
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][EjemplosDB]";
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
		$img0 = new Upload("fPath1");
		$img0->bAutoRenombrar = false;
		$img0->bSobreEscribir = true;
		$img0->image_mini = false;	// crear mini.
		$img0->image_mini_x = 25;	// Ancho por defecto de la miñatura.
		$img0->image_resize = false;	// redimensionar la imagen
		$img0->image_x = 150;	// Ancho por defecto
		$img0->jpeg_quality = 75;
		$img1 = new Upload("fPath2");
		$img1->bAutoRenombrar = false;
		$img1->bSobreEscribir = true;
		$img1->image_mini = false;	// crear mini.
		$img1->image_mini_x = 25;	// Ancho por defecto de la miñatura.
		$img1->image_resize = false;	// redimensionar la imagen
		$img1->image_x = 150;	// Ancho por defecto
		$img1->jpeg_quality = 75;
		$img2 = new Upload("fPath3");
		$img2->bAutoRenombrar = false;
		$img2->bSobreEscribir = true;
		$img2->image_mini = false;	// crear mini.
		$img2->image_mini_x = 25;	// Ancho por defecto de la miñatura.
		$img2->image_resize = false;	// redimensionar la imagen
		$img2->image_x = 150;	// Ancho por defecto
		$img2->jpeg_quality = 75;
		$img3 = new Upload("fPath4");
		$img3->bAutoRenombrar = false;
		$img3->bSobreEscribir = true;
		$img3->image_mini = false;	// crear mini.
		$img3->image_mini_x = 25;	// Ancho por defecto de la miñatura.
		$img3->image_resize = false;	// redimensionar la imagen
		$img3->image_x = 150;	// Ancho por defecto
		$img3->jpeg_quality = 75;
		$sDirImg="imgEjemplos";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
		if (!$img0->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img0->get_errores();
			return false;
		}else{
			$cEntidad->setPath1($img0->getPath_WS());
		}
		if (!$img1->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img1->get_errores();
			return false;
		}else{
			$cEntidad->setPath2($img1->getPath_WS());
		}
		if (!$img2->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img2->get_errores();
			return false;
		}else{
			$cEntidad->setPath3($img2->getPath_WS());
		}
		if (!$img3->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img3->get_errores();
			return false;
		}else{
			$cEntidad->setPath4($img3->getPath_WS());
		}
	
		$sql = "UPDATE ejemplos SET ";
		$sql .= "idEjemplo=" . $aux->qstr($cEntidad->getIdEjemplo(), false) . ", ";
		$sql .= "codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . ", ";
		$sql .= "idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . ", ";
		$sql .= "enunciado=" . $aux->qstr($cEntidad->getEnunciado(), false) . ", ";
		$sql .= "descripcion=" . $aux->qstr($cEntidad->getDescripcion(), false) . ", ";
		if ($cEntidad->getPath1() != "")
			$sql .= "path1=" . $aux->qstr($cEntidad->getPath1(), false) . ", ";
		if ($cEntidad->getPath2() != "")
			$sql .= "path2=" . $aux->qstr($cEntidad->getPath2(), false) . ", ";
		if ($cEntidad->getPath3() != "")
			$sql .= "path3=" . $aux->qstr($cEntidad->getPath3(), false) . ", ";
		if ($cEntidad->getPath4() != "")
			$sql .= "path4=" . $aux->qstr($cEntidad->getPath4(), false) . ", ";
		$sql .= "correcto=" . $aux->qstr($cEntidad->getCorrecto(), false) . ", ";
		$sql .= "orden=" . $aux->qstr($cEntidad->getOrden(), false) . ", ";
		$sql .= "bajaLog=" . $aux->qstr($cEntidad->getBajaLog(), false) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idEjemplo=" . $aux->qstr($cEntidad->getIdEjemplo(), false) . " AND codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][EjemplosDB]";
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
		$sql = "UPDATE ejemplos SET ";
		$sql .= "bajaLog='1'";
			$sql  .="WHERE ";
			if ($cEntidad->getIdEjemplo() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idEjemplo=" . $aux->qstr($cEntidad->getIdEjemplo(), false) . " ";
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
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][EjemplosDB]";
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
	
		$sql = "SELECT *  FROM ejemplos WHERE ";
		$sql  .="idEjemplo=" . $aux->qstr($cEntidad->getIdEjemplo(), false) . " AND codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdEjemplo($arr['idEjemplo']);
					$cEntidad->setCodIdiomaIso2($arr['codIdiomaIso2']);
					$cEntidad->setIdPrueba($arr['idPrueba']);
					$cEntidad->setEnunciado($arr['enunciado']);
					$cEntidad->setDescripcion($arr['descripcion']);
					$cEntidad->setPath1($arr['path1']);
					$cEntidad->setPath2($arr['path2']);
					$cEntidad->setPath3($arr['path3']);
					$cEntidad->setPath4($arr['path4']);
					$cEntidad->setCorrecto($arr['correcto']);
					$cEntidad->setOrden($arr['orden']);
					$cEntidad->setBajaLog($arr['bajaLog']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][EjemplosDB]";
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
		$sql.="SELECT * FROM ejemplos ";
		if ($cEntidad->getIdEjemplo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEjemplo=" . $aux->qstr($cEntidad->getIdEjemplo(), false);
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
		if ($cEntidad->getEnunciado() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(enunciado) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getEnunciado() . "%") . ")";
		}
		if ($cEntidad->getDescripcion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descripcion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDescripcion() . "%") . ")";
		}
		if ($cEntidad->getPath1() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(path1) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getPath1() . "%") . ")";
		}
		if ($cEntidad->getPath2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(path2) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getPath2() . "%") . ")";
		}
		if ($cEntidad->getPath3() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(path3) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getPath3() . "%") . ")";
		}
		if ($cEntidad->getPath4() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(path4) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getPath4() . "%") . ")";
		}
		if ($cEntidad->getCorrecto() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(correcto) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCorrecto() . "%") . ")";
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
		if ($cEntidad->getBajaLog() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="bajaLog>=" . $aux->qstr($cEntidad->getBajaLog(), false);
		}
		if ($cEntidad->getBajaLogHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="bajaLog<=" . $aux->qstr($cEntidad->getBajaLogHast(), false);
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
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");

		$sql = "UPDATE ejemplos SET  ";
		if (strtolower($cEntidad->getPath1()) == "on"){
			$cEntidad->setPath1('');
			$sql .= "path1=" . $aux->qstr($cEntidad->getPath1(), false) . ", ";
		}
		if (strtolower($cEntidad->getPath2()) == "on"){
			$cEntidad->setPath2('');
			$sql .= "path2=" . $aux->qstr($cEntidad->getPath2(), false) . ", ";
		}
		if (strtolower($cEntidad->getPath3()) == "on"){
			$cEntidad->setPath3('');
			$sql .= "path3=" . $aux->qstr($cEntidad->getPath3(), false) . ", ";
		}
		if (strtolower($cEntidad->getPath4()) == "on"){
			$cEntidad->setPath4('');
			$sql .= "path4=" . $aux->qstr($cEntidad->getPath4(), false) . ", ";
		}
		$sql = substr($sql,0,strlen($sql)-2);
		$sql .= " WHERE ";
		$sql .="idEjemplo=" . $aux->qstr($cEntidad->getIdEjemplo(), false) . " AND codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$cEntidadAnterior = $this->readEntidad($cEntidad);
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][EjemplosDBDB::quitaImagen()]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}else{
			@unlink($spath . $cEntidadAnterior->getPath1());
			//Borramos la miñatura
			$name= basename($cEntidadAnterior->getPath1());
			$namePath=str_replace($name,"m" . $name, $cEntidadAnterior->getPath1());
			@unlink($spath . $namePath);
			@unlink($spath . $cEntidadAnterior->getPath2());
			//Borramos la miñatura
			$name= basename($cEntidadAnterior->getPath2());
			$namePath=str_replace($name,"m" . $name, $cEntidadAnterior->getPath2());
			@unlink($spath . $namePath);
			@unlink($spath . $cEntidadAnterior->getPath3());
			//Borramos la miñatura
			$name= basename($cEntidadAnterior->getPath3());
			$namePath=str_replace($name,"m" . $name, $cEntidadAnterior->getPath3());
			@unlink($spath . $namePath);
			@unlink($spath . $cEntidadAnterior->getPath4());
			//Borramos la miñatura
			$name= basename($cEntidadAnterior->getPath4());
			$namePath=str_replace($name,"m" . $name, $cEntidadAnterior->getPath4());
			@unlink($spath . $namePath);
		}
		return $retorno;
	}
}//Fin de la Clase EjemplosDB
?>