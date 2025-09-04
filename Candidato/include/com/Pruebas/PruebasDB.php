<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla pruebas
**/
class PruebasDB
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
		$img0 = new Upload("fLogoPrueba");
		$img0->bAutoRenombrar = false;
		$img0->bSobreEscribir = true;
		$img0->image_mini = false;	// crear mini.
		$img0->image_mini_x = 25;	// Ancho por defecto de la miñatura.
		//$img0->image_resize = true;	// redimensionar la imagen
		//$img0->image_x = 100;	// Ancho por defecto
		$img0->jpeg_quality = 75;
		$img1 = new Upload("fCapturaPantalla");
		$img1->bAutoRenombrar = false;
		$img1->bSobreEscribir = true;
		$img1->image_mini = false;	// crear mini.
		$img1->image_mini_x = 25;	// Ancho por defecto de la miñatura.
		//$img1->image_resize = false;	// redimensionar la imagen
		//$img1->image_x = 150;	// Ancho por defecto
		$img1->jpeg_quality = 75;
		$img2 = new Upload("fCabecera");
		$img2->bAutoRenombrar = false;
		$img2->bSobreEscribir = true;
		$img2->image_mini = false;	// crear mini.
		$img2->image_mini_x = 25;	// Ancho por defecto de la miñatura.
		$img2->jpeg_quality = 75;
		$sDirImg="imgPruebas/" . $cEntidad->getIdPrueba() . "/" . $cEntidad->getCodIdiomaIso2();
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if (!$img0->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img0->get_errores();
			return 0;
		}else{
			$cEntidad->setLogoPrueba($img0->getPath_WS());
		}
		if (!$img1->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img1->get_errores();
			return 0;
		}else{
			$cEntidad->setCapturaPantalla($img1->getPath_WS());
		}
		if (!$img2->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img2->get_errores();
			return 0;
		}else{
			$cEntidad->setCabecera($img2->getPath_WS());
		}
        if($cEntidad->getIdPrueba() != ""){
        	$newId = $cEntidad->getIdPrueba();
        }else{
        	$newId = $this->getSiguienteId($cEntidad);
        }
		$sql = "INSERT INTO pruebas (";
		$sql .= "codIdiomaIso2" . ",";
		$sql .= "idPrueba" . ",";
		$sql .= "codigo" . ",";
		$sql .= "nombre" . ",";
		$sql .= "descripcion" . ",";
		$sql .= "idTipoPrueba" . ",";
		$sql .= "observaciones" . ",";
		$sql .= "duracion" . ",";
		$sql .= "duracion2" . ",";
		$sql .= "logoPrueba" . ",";
		$sql .= "capturaPantalla" . ",";
		$sql .= "cabecera" . ",";
//		$sql .= "preguntasPorPagina" . ",";
//		$sql .= "estiloOpciones" . ",";
//		$sql .= "permiteBlancos" . ",";
		$sql .= "bajaLog" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . ",";
		$sql .= $aux->qstr($newId, false) . ",";
		$sql .= $aux->qstr($cEntidad->getCodigo(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNombre(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescripcion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdTipoPrueba(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getObservaciones(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDuracion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDuracion2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getLogoPrueba(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCapturaPantalla(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCabecera(), false) . ",";
//		$sql .= $aux->qstr($cEntidad->getPreguntasPorPagina(), false) . ",";
//		$sql .= $aux->qstr($cEntidad->getEstiloOpciones(), false) . ",";
//		$sql .= $aux->qstr($cEntidad->getPermiteBlancos(), false) . ",";
		
		$sql .= $aux->qstr($cEntidad->getBajaLog(), false) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][PruebasDB]";
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
	
		$sql  = "SELECT MAX(idPrueba) AS Max FROM pruebas ";
		$sql  .=" WHERE codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " ";
		$rs = $aux->Execute($sql);
		$newId=0;
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][PruebasDB]";
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
		$img0 = new Upload("fLogoPrueba");
		$img1 = new Upload("fCapturaPantalla");
		$img2 = new Upload("fCabecera");
		$sDirImg="imgPruebas/" . $cEntidad->getIdPrueba() . "/" . $cEntidad->getCodIdiomaIso2();
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if (!$img0->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img0->get_errores();
			return false;
		}else{
			$cEntidad->setLogoPrueba($img0->getPath_WS());
		}
		if (!$img1->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img1->get_errores();
			return false;
		}else{
			$cEntidad->setCapturaPantalla($img1->getPath_WS());
		}
		if (!$img2->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img2->get_errores();
			return false;
		}else{
			$cEntidad->setCabecera($img2->getPath_WS());
		}
		$sql = "UPDATE pruebas SET ";
		$sql .= "codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . ", ";
		$sql .= "idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . ", ";
		$sql .= "codigo=" . $aux->qstr($cEntidad->getCodigo(), false) . ", ";
		$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
		$sql .= "descripcion=" . $aux->qstr($cEntidad->getDescripcion(), false) . ", ";
		$sql .= "idTipoPrueba=" . $aux->qstr($cEntidad->getIdTipoPrueba(), false) . ", ";
		$sql .= "observaciones=" . $aux->qstr($cEntidad->getObservaciones(), false) . ", ";
		$sql .= "duracion=" . $aux->qstr($cEntidad->getDuracion(), false) . ", ";
		$sql .= "duracion2=" . $aux->qstr($cEntidad->getDuracion2(), false) . ", ";
		if ($cEntidad->getLogoPrueba() != "")
			$sql .= "logoPrueba=" . $aux->qstr($cEntidad->getLogoPrueba(), false) . ", ";
		if ($cEntidad->getCapturaPantalla() != "")
			$sql .= "capturaPantalla=" . $aux->qstr($cEntidad->getCapturaPantalla(), false) . ", ";
		if ($cEntidad->getCabecera() != "")
			$sql .= "cabecera=" . $aux->qstr($cEntidad->getCabecera(), false) . ", ";
//		$sql .= "preguntasPorPagina=" . $aux->qstr($cEntidad->getPreguntasPorPagina(), false) . ", ";
//		$sql .= "estiloOpciones=" . $aux->qstr($cEntidad->getEstiloOpciones(), false) . ", ";
//		$sql .= "permiteBlancos=" . $aux->qstr($cEntidad->getPermiteBlancos(), false) . ", ";
		
		$sql .= "bajaLog=" . $aux->qstr($cEntidad->getBajaLog(), false) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][PruebasDB]";
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
			$sql  ="DELETE FROM pruebas ";
			$sql  .="WHERE ";
			if ($cEntidad->getIdPrueba() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
			}
			if ($cEntidad->getCodIdiomaIso2() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " ";
			}
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][PruebasDB]";
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
	
		$sql = "SELECT *  FROM pruebas WHERE ";
		$sql  .="codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " AND idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setCodIdiomaIso2($arr['codIdiomaIso2']);
					$cEntidad->setIdPrueba($arr['idPrueba']);
					$cEntidad->setCodigo($arr['codigo']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setDescripcion($arr['descripcion']);
					$cEntidad->setIdTipoPrueba($arr['idTipoPrueba']);
					$cEntidad->setIdTipoRazonamiento($arr['idTipoRazonamiento']);
					$cEntidad->setIdTipoNivel($arr['idTipoNivel']);
					
					$cEntidad->setObservaciones($arr['observaciones']);
					$cEntidad->setDuracion($arr['duracion']);
					$cEntidad->setDuracion2($arr['duracion2']);
					$cEntidad->setLogoPrueba($arr['logoPrueba']);
					$cEntidad->setCapturaPantalla($arr['capturaPantalla']);
					$cEntidad->setCabecera($arr['cabecera']);
					$cEntidad->setPreguntasPorPagina($arr['preguntasPorPagina']);
					$cEntidad->setEstiloOpciones($arr['estiloOpciones']);
					$cEntidad->setPermiteBlancos($arr['permiteBlancos']);
										
					$cEntidad->setBajaLog($arr['bajaLog']);
					$cEntidad->setNecesitara($arr['necesitara']);
					$cEntidad->setNum_preguntas_max_tri($arr['num_preguntas_max_tri']);
					
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][PruebasDB]";
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
		$sql.="SELECT * FROM pruebas ";
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
		if ($cEntidad->getCodigo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(codigo) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCodigo() . "%") . ")";
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
		if ($cEntidad->getIdTipoPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTipoPrueba=" . $aux->qstr($cEntidad->getIdTipoPrueba(), false);
		}
		
		if ($cEntidad->getIdTipoRazonamiento() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTipoRazonamiento=" . $aux->qstr($cEntidad->getIdTipoRazonamiento(), false);
		}
		if ($cEntidad->getIdTipoNivel() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTipoNivel=" . $aux->qstr($cEntidad->getIdTipoNivel(), false);
		}
		
		if ($cEntidad->getObservaciones() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(observaciones) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getObservaciones() . "%") . ")";
		}
		if ($cEntidad->getDuracion() != "" && $cEntidad->getDuracion() != 0){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(duracion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDuracion() . "%") . ")";
		}
		if ($cEntidad->getDuracion2() != "" && $cEntidad->getDuracion2() != 0){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(duracion2) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDuracion2() . "%") . ")";
		}
		if ($cEntidad->getLogoPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(logoPrueba) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getLogoPrueba() . "%") . ")";
		}
		if ($cEntidad->getCapturaPantalla() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(capturaPantalla) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCapturaPantalla() . "%") . ")";
		}
		if ($cEntidad->getCabecera() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(cabecera) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCabecera() . "%") . ")";
		}
		if ($cEntidad->getPreguntasPorPagina() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="preguntasPorPagina>=" . $aux->qstr($cEntidad->getPreguntasPorPagina(), false);
		}
		if ($cEntidad->getPreguntasPorPaginaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="preguntasPorPagina<=" . $aux->qstr($cEntidad->getPreguntasPorPaginaHast(), false);
		}
		
		if ($cEntidad->getEstiloOpciones() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(estiloOpciones) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getEstiloOpciones() . "%") . ")";
		}
		if ($cEntidad->getPermiteBlancos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(permiteBlancos) = UPPER(" . $aux->qstr("" . $cEntidad->getPermiteBlancos() . "") . ")";
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

	/*************************************************************************
	* Lista en la base de datos recogiendo la información
	* recibida por la entidad, Este metodo se utiliza para búsquedas
	* de uno o varios registros.
	* @param entidad Entidad con la información de la Búsqueda
	* @return Vector Lista recuperada
	*************************************************************************/
	function readListaGroup($cEntidad)
	{
		$aux			= $this->conn;
	
		$sql="";
		$and = false;
		$sql.="SELECT * FROM pruebas ";
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
		if ($cEntidad->getCodigo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(codigo) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCodigo() . "%") . ")";
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
		if ($cEntidad->getIdTipoPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTipoPrueba=" . $aux->qstr($cEntidad->getIdTipoPrueba(), false);
		}
		
		if ($cEntidad->getIdTipoRazonamiento() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTipoRazonamiento=" . $aux->qstr($cEntidad->getIdTipoRazonamiento(), false);
		}
		if ($cEntidad->getIdTipoNivel() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idTipoNivel=" . $aux->qstr($cEntidad->getIdTipoNivel(), false);
		}
		
		if ($cEntidad->getObservaciones() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(observaciones) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getObservaciones() . "%") . ")";
		}
		if ($cEntidad->getDuracion() != "" && $cEntidad->getDuracion() != 0){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(duracion) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDuracion() . "%") . ")";
		}
		if ($cEntidad->getDuracion2() != "" && $cEntidad->getDuracion2() != 0){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(duracion2) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDuracion2() . "%") . ")";
		}
		if ($cEntidad->getLogoPrueba() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(logoPrueba) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getLogoPrueba() . "%") . ")";
		}
		if ($cEntidad->getCapturaPantalla() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(capturaPantalla) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCapturaPantalla() . "%") . ")";
		}
		if ($cEntidad->getCabecera() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(cabecera) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getCabecera() . "%") . ")";
		}
		if ($cEntidad->getPreguntasPorPagina() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="preguntasPorPagina>=" . $aux->qstr($cEntidad->getPreguntasPorPagina(), false);
		}
		if ($cEntidad->getPreguntasPorPaginaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="preguntasPorPagina<=" . $aux->qstr($cEntidad->getPreguntasPorPaginaHast(), false);
		}
		
		if ($cEntidad->getEstiloOpciones() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(estiloOpciones) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getEstiloOpciones() . "%") . ")";
		}
		if ($cEntidad->getPermiteBlancos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(permiteBlancos) = UPPER(" . $aux->qstr("" . $cEntidad->getPermiteBlancos() . "") . ")";
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
		$sql .=" GROUP BY idprueba ";
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

	function getUltimaSQL()
	{
		$this->sSQL = $_SESSION['SQLPruebasDB'];
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

		$sql = "UPDATE pruebas SET  ";
		if (strtolower($cEntidad->getLogoPrueba()) == "on"){
			$cEntidad->setLogoPrueba('');
			$sql .= "logoPrueba=" . $aux->qstr($cEntidad->getLogoPrueba(), false) . ", ";
		}
		if (strtolower($cEntidad->getCapturaPantalla()) == "on"){
			$cEntidad->setCapturaPantalla('');
			$sql .= "capturaPantalla=" . $aux->qstr($cEntidad->getCapturaPantalla(), false) . ", ";
		}
		if (strtolower($cEntidad->getCabecera()) == "on"){
			$cEntidad->setCabecera('');
			$sql .= "cabecera=" . $aux->qstr($cEntidad->getCabecera(), false) . ", ";
		}
		$sql = substr($sql,0,strlen($sql)-2);
		$sql .= " WHERE ";
		$sql .="idPrueba=" . $aux->qstr($cEntidad->getIdPrueba(), false) . " AND codIdiomaIso2=" . $aux->qstr($cEntidad->getCodIdiomaIso2(), false) . " ";
		$cEntidadAnterior = $this->readEntidad($cEntidad);
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][PruebasDBDB::quitaImagen()]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}else{
			@unlink($spath . $cEntidadAnterior->getLogoPrueba());
			//Borramos la miñatura
			$name= basename($cEntidadAnterior->getLogoPrueba());
			$namePath=str_replace($name,"m" . $name, $cEntidadAnterior->getLogoPrueba());
			@unlink($spath . $namePath);
			@unlink($spath . $cEntidadAnterior->getCapturaPantalla());
			//Borramos la miñatura
			$name= basename($cEntidadAnterior->getCapturaPantalla());
			$namePath=str_replace($name,"m" . $name, $cEntidadAnterior->getCapturaPantalla());
			@unlink($spath . $namePath);
			@unlink($spath . $cEntidadAnterior->getCabecera());
			//Borramos la miñatura
			$name= basename($cEntidadAnterior->getCabecera());
			$namePath=str_replace($name,"m" . $name, $cEntidadAnterior->getCabecera());
			@unlink($spath . $namePath);
		}
		return $retorno;
	}
}//Fin de la Clase PruebasDB
?>