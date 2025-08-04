<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla web_slider
**/
class Web_sliderDB
{

	/**
	* Declaración de las variables de Entidad.
	**/
	protected $conn; //Conexión con la BBDD
	protected $sSQL; //Última query ejecutada
	protected $msg_Error; //Array con los mensajes de Warning y Errores

	/**
	* Crea un objeto de la clase y almacena en él la
	* conexión que utilizará con la base de datos
	* @param conn Conexion a traves de la cual
	* realizar las operaciones sobre la base de datos
	**/
	public function __construct($conn)
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
	public function insertar($cEntidad)
	{
		$aux			= $this->conn;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
		$cUtilidades	= new Utilidades();

		$newId = $this->getSiguienteId($cEntidad);

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Upload.php");
		$img0 = new Upload("fWeb_sliderPathImagen");
		$img0->bAutoRenombrar = true;
		$img0->bSobreEscribir = true;
		$img0->image_mini = false;	// crear mini.
		$img0->image_mini_x = 200;	// Ancho por defecto de la miñatura.
		$img0->image_resize = true;	// redimensionar la imagen
		$img0->image_x = 1920;	// Ancho por defecto
		$img0->jpeg_quality = 75;
		// Tamaño de la imagen recortada
		$pasaImgSize0 = false;
		if(!empty($_FILES['fWeb_sliderPathImagen']['tmp_name'])){
			$imageSize0 = getimagesize($_FILES['fWeb_sliderPathImagen']['tmp_name']);
			$anchura0 = $imageSize0[0];
			$altura0 = $imageSize0[1];
			if($anchura0 >= 1920 && $altura0 >= 900){
				$pasaImgSize0 = true;
				if (!is_array($this->msg_Error))
					$this->msg_Error	= array();
				$sTypeError	=	constant("STR_IMAGEN_ADJUNTA") . " " . constant("STR_RECORTE_IMAGEN") . ".";
				$this->msg_Error[]	= $sTypeError;
			}else{
				if (!is_array($this->msg_Error))
					$this->msg_Error	= array();
				$sTypeError	=	constant("STR_IMAGEN_ADJUNTA") . " " . constant("STR_TAMANO_NO_VALIDO") . ".";
				$this->msg_Error[]	= $sTypeError;
				return 0;
			}
		}
		$sDirImg="imgWeb_slider";
		$iContador = 0;
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if($pasaImgSize0 == true){
			if (!$img0->UploadFile($spath . $sDirImg))
			{
				$this->msg_Error = $img0->get_errores();
				return 0;
			}else{
				$cEntidad->setPathImagen($img0->getPath_WS());
			}
		}
				$sql = "INSERT INTO web_slider (";
				$sql .= "idSlider" . ",";
				$sql .= "code" . ",";
				$sql .= "titulo" . ",";
				$sql .= "pathImagen" . ",";
				$sql .= "urlDestino" . ",";
				$sql .= "orden" . ",";
				$sql .= "fecAlta" . ",";
				$sql .= "fecMod" . ",";
				$sql .= "usuAlta" . ",";
				$sql .= "usuMod" . ")";
				$sql .= " VALUES (";
				$sql .= $aux->qstr($newId, false) . ",";
				$sql .= $aux->qstr($cEntidad->getCode(), false) . ",";
				$sql .= $aux->qstr($cEntidad->getTitulo(), false) . ",";
				$sql .= $aux->qstr($cEntidad->getPathImagen(), false) . ",";
				$sql .= $aux->qstr($cEntidad->getUrlDestino(), false) . ",";
				$sql .= $aux->qstr($cEntidad->getOrden(), false) . ",";
				$sql .= $aux->sysTimeStamp . ",";
				$sql .= $aux->sysTimeStamp . ",";
				$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
				$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
				if($aux->Execute($sql) === false){
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][" . get_class($this) . "]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " ->\tError::" . $aux->ErrorMsg() . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					return 0;
				}
				else{
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambiosDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambios.php");
					$cEntidadHistorico_cambiosDB	= new Historico_cambiosDB($aux);  // Entidad DB Historico_cambios
					$cEntidadHistorico_cambios		= new Historico_cambios();  // Entidad Historico_cambios
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Wi_usuarios/Wi_usuariosDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Wi_usuarios/Wi_usuarios.php");
					$_cEntidadWi_usuariosDB	= new Wi_usuariosDB($aux);
					$_cEntidadWi_usuarios		= new Wi_usuarios();
					$_cEntidadWi_usuarios->setIdUsuario($cEntidad->getUsuAlta());
					$_cEntidadWi_usuariosDB->readEntidad($_cEntidadWi_usuarios);
					$cEntidadHistorico_cambios->setFuncionalidad(basename($_SERVER['PHP_SELF']));
					$cEntidadHistorico_cambios->setModo(constant("MNT_ALTA"));
					$cEntidadHistorico_cambios->setQuery($sql);
					$cEntidadHistorico_cambios->setIp($_SERVER['REMOTE_ADDR']);
					$cEntidadHistorico_cambios->setIdUsuario($_cEntidadWi_usuarios->getIdUsuario());
					$cEntidadHistorico_cambios->setIdUsuarioTipo($_cEntidadWi_usuarios->getIdUsuarioTipo());
					$cEntidadHistorico_cambios->setLogin($_cEntidadWi_usuarios->getLogin());
					$cEntidadHistorico_cambios->setNombre($_cEntidadWi_usuarios->getNombre());
					$cEntidadHistorico_cambios->setApellido1($_cEntidadWi_usuarios->getApellido1());
					$cEntidadHistorico_cambios->setApellido2($_cEntidadWi_usuarios->getApellido2());
					$cEntidadHistorico_cambios->setEmail($_cEntidadWi_usuarios->getEmail());
					$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : "0");
					$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : "0");
					$HistId	= $cEntidadHistorico_cambiosDB->insertar($cEntidadHistorico_cambios);
					if (empty($HistId)){
						$this->msg_Error	= array();
						$sTypeError	=	date('d/m/Y H:i:s') . " Error [GUARDANDO HISTORICO][" . constant("MNT_ALTA") . "][" . get_class($this) . "]";
						$this->msg_Error[]	= $sTypeError;
						error_log($sTypeError . " ->\tError::" . $aux->ErrorMsg() . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
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
	private function getSiguienteId($cEntidad)
	{
		$aux			= $this->conn;

		$sql  = "SELECT MAX(idSlider) AS Max FROM web_slider ";
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
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][" . get_class($this) . "]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\tError::" . $aux->ErrorMsg() . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
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
	public function modificar($cEntidad)
	{
		$aux			= $this->conn;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
		$cUtilidades	= new Utilidades();
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Upload.php");
		$img0 = new Upload("fWeb_sliderPathImagen");
		$img0->bAutoRenombrar = true;
		$img0->bSobreEscribir = true;
		$img0->image_mini = false;	// crear mini.
		$img0->image_mini_x = 200;	// Ancho por defecto de la miñatura.
		$img0->image_resize = true;	// redimensionar la imagen
		$img0->image_x = 1920;	// Ancho por defecto
		$img0->jpeg_quality = 75;
		// Tamaño de la imagen recortada
		$pasaImgSize0 = false;
		if(!empty($_FILES['fWeb_sliderPathImagen']['tmp_name'])){
			$imageSize0 = getimagesize($_FILES['fWeb_sliderPathImagen']['tmp_name']);
			$anchura0 = $imageSize0[0];
			$altura0 = $imageSize0[1];
			if($anchura0 >= 1920 && $altura0 >= 900){
				$pasaImgSize0 = true;
				if (!is_array($this->msg_Error))
					$this->msg_Error	= array();
				$sTypeError	=	constant("STR_IMAGEN_ADJUNTA") . " " . constant("STR_RECORTE_IMAGEN") . ".";
				$this->msg_Error[]	= $sTypeError;
			}else{
				if (!is_array($this->msg_Error))
					$this->msg_Error	= array();
				$sTypeError	=	constant("STR_IMAGEN_ADJUNTA") . " " . constant("STR_TAMANO_NO_VALIDO") . ".";
				$this->msg_Error[]	= $sTypeError;
				return 0;
				$retorno = false;
			}
		}
		$sDirImg="imgWeb_slider";
		$iContador = 0;
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if($pasaImgSize0 == true){
			if (!$img0->UploadFile($spath . $sDirImg))
			{
				$this->msg_Error = $img0->get_errores();
				return 0;
			}else{
				$cEntidad->setPathImagen($img0->getPath_WS());
			}
		}

		$sql = "UPDATE web_slider SET ";
		$sql .= "idSlider=" . $aux->qstr($cEntidad->getIdSlider(), false) . ", ";
		$sql .= "code=" . $aux->qstr($cEntidad->getCode(), false) . ", ";
		$sql .= "titulo=" . $aux->qstr($cEntidad->getTitulo(), false) . ", ";
		if ($cEntidad->getPathImagen() != "")
			$sql .= "pathImagen=" . $aux->qstr($cEntidad->getPathImagen(), false) . ", ";
		$sql .= "urlDestino=" . $aux->qstr($cEntidad->getUrlDestino(), false) . ", ";
		$sql .= "orden=" . $aux->qstr($cEntidad->getOrden(), false) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idSlider=" . $aux->qstr($cEntidad->getIdSlider(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][" . get_class($this) . "]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\tError::" . $aux->ErrorMsg() . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
		else{
			$this->setDesNomalizacion($cEntidad);
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambiosDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambios.php");
			$cEntidadHistorico_cambiosDB	= new Historico_cambiosDB($aux);  // Entidad DB Historico_cambios
			$cEntidadHistorico_cambios		= new Historico_cambios();  // Entidad Historico_cambios
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Wi_usuarios/Wi_usuariosDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Wi_usuarios/Wi_usuarios.php");
			$_cEntidadWi_usuariosDB	= new Wi_usuariosDB($aux);
			$_cEntidadWi_usuarios		= new Wi_usuarios();
			$_cEntidadWi_usuarios->setIdUsuario($cEntidad->getUsuAlta());
			$_cEntidadWi_usuariosDB->readEntidad($_cEntidadWi_usuarios);
			$cEntidadHistorico_cambios->setFuncionalidad(basename($_SERVER['PHP_SELF']));
			$cEntidadHistorico_cambios->setModo(constant("MNT_MODIFICAR"));
			$cEntidadHistorico_cambios->setQuery($sql);
			$cEntidadHistorico_cambios->setIp($_SERVER['REMOTE_ADDR']);
			$cEntidadHistorico_cambios->setIdUsuario($_cEntidadWi_usuarios->getIdUsuario());
			$cEntidadHistorico_cambios->setIdUsuarioTipo($_cEntidadWi_usuarios->getIdUsuarioTipo());
			$cEntidadHistorico_cambios->setLogin($_cEntidadWi_usuarios->getLogin());
			$cEntidadHistorico_cambios->setNombre($_cEntidadWi_usuarios->getNombre());
			$cEntidadHistorico_cambios->setApellido1($_cEntidadWi_usuarios->getApellido1());
			$cEntidadHistorico_cambios->setApellido2($_cEntidadWi_usuarios->getApellido2());
			$cEntidadHistorico_cambios->setEmail($_cEntidadWi_usuarios->getEmail());
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : "0");
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : "0");
			$HistId	= $cEntidadHistorico_cambiosDB->insertar($cEntidadHistorico_cambios);
			if (empty($HistId)){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error [GUARDANDO HISTORICO][" . constant("MNT_ALTA") . "][" . get_class($this) . "]";
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
	public function borrar($cEntidad)
	{
		$aux			= $this->conn;
		$this->msg_Error			= array();
		$and			= false;
		$retorno			= true;

		if ($retorno){
				$sql  ="DELETE FROM web_slider ";
				$sql  .="WHERE ";
				if ($cEntidad->getIdSlider() != ""){
					$sql .= $this->getSQLAnd($and);
					$and = true;
					$sql  .="idSlider=" . $aux->qstr($cEntidad->getIdSlider(), false) . " ";
				}
				if($aux->Execute($sql) === false){
					$retorno=false;
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][" . get_class($this) . "]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " ->\tError::" . $aux->ErrorMsg() . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				}
		}else $retorno=false;
		if ($retorno){
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambiosDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambios.php");
			$cEntidadHistorico_cambiosDB	= new Historico_cambiosDB($aux);  // Entidad DB Historico_cambios
			$cEntidadHistorico_cambios		= new Historico_cambios();  // Entidad Historico_cambios
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Wi_usuarios/Wi_usuariosDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Wi_usuarios/Wi_usuarios.php");
			$_cEntidadWi_usuariosDB	= new Wi_usuariosDB($aux);
			$_cEntidadWi_usuarios		= new Wi_usuarios();
			$_cEntidadWi_usuarios->setIdUsuario($cEntidad->getUsuAlta());
			$_cEntidadWi_usuariosDB->readEntidad($_cEntidadWi_usuarios);
			$cEntidadHistorico_cambios->setFuncionalidad(basename($_SERVER['PHP_SELF']));
			$cEntidadHistorico_cambios->setModo(constant("MNT_BORRAR"));
			$cEntidadHistorico_cambios->setQuery($sql);
			$cEntidadHistorico_cambios->setIp($_SERVER['REMOTE_ADDR']);
			$cEntidadHistorico_cambios->setIdUsuario($_cEntidadWi_usuarios->getIdUsuario());
			$cEntidadHistorico_cambios->setIdUsuarioTipo($_cEntidadWi_usuarios->getIdUsuarioTipo());
			$cEntidadHistorico_cambios->setLogin($_cEntidadWi_usuarios->getLogin());
			$cEntidadHistorico_cambios->setNombre($_cEntidadWi_usuarios->getNombre());
			$cEntidadHistorico_cambios->setApellido1($_cEntidadWi_usuarios->getApellido1());
			$cEntidadHistorico_cambios->setApellido2($_cEntidadWi_usuarios->getApellido2());
			$cEntidadHistorico_cambios->setEmail($_cEntidadWi_usuarios->getEmail());
			$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : "0");
			$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : "0");
			$HistId	= $cEntidadHistorico_cambiosDB->insertar($cEntidadHistorico_cambios);
			if (empty($HistId)){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error [GUARDANDO HISTORICO][" . constant("MNT_ALTA") . "][" . get_class($this) . "]";
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
	public function readEntidad($cEntidad)
	{
		$aux			= $this->conn;

		$sql = "SELECT *  FROM web_slider WHERE ";
		$sql  .="idSlider=" . $aux->qstr($cEntidad->getIdSlider(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$cEntidad->setIdSlider($arr['idSlider']);
				$cEntidad->setCode($arr['code']);
				$cEntidad->setTitulo($arr['titulo']);
				$cEntidad->setPathImagen($arr['pathImagen']);
				$cEntidad->setUrlDestino($arr['urlDestino']);
				$cEntidad->setOrden($arr['orden']);
				$cEntidad->setFecAlta($arr['fecAlta']);
				$cEntidad->setFecMod($arr['fecMod']);
				$cEntidad->setUsuAlta($arr['usuAlta']);
				$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][" . get_class($this) . "]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\tError::" . $aux->ErrorMsg() . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
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
	public function readLista($cEntidad)
	{
		$aux			= $this->conn;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
		$cUtilidades	= new Utilidades();

		$sql="";
		$and = false;
		$sql.="SELECT * FROM web_slider ";
		if ($cEntidad->getIdSlider() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idSlider=" . $aux->qstr($cEntidad->getIdSlider(), false);
		}
		if ($cEntidad->getCode() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(code) = UPPER(" . $aux->qstr("" . $cEntidad->getCode() . "") . ")";
		}
		if ($cEntidad->getTitulo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(titulo) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getTitulo() . "%") . ")";
		}
		if ($cEntidad->getPathImagen() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(pathImagen) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getPathImagen() . "%") . ")";
		}
		if ($cEntidad->getUrlDestino() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(urlDestino) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getUrlDestino() . "%") . ")";
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
		if ($cEntidad->getFecAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta>=" . $aux->qstr($cUtilidades->cFechaToMySQL($cEntidad->getFecAlta()), false);
		}
		if ($cEntidad->getFecAltaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta<=" . $aux->qstr($cUtilidades->cFechaToMySQL($cEntidad->getFecAltaHast()), false);
		}
		if ($cEntidad->getFecMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod>=" . $aux->qstr($cUtilidades->cFechaToMySQL($cEntidad->getFecMod()), false);
		}
		if ($cEntidad->getFecModHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod<=" . $aux->qstr($cUtilidades->cFechaToMySQL($cEntidad->getFecModHast()), false);
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
		if ($cEntidad->getGroupBy() != ""){
			$sql .=" GROUP BY " . $cEntidad->getGroupBy();
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

	protected function getSQLWhere($bFlag)
	{
		if (!$bFlag)	return " WHERE ";
		else	return " AND ";
	}

	protected function getSQLAnd($bFlag)
	{
		if ($bFlag)	return " AND ";
		else	return " ";
	}

	public function ver_errores($type=1)
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

	public function quitaImagen($cEntidad)
	{
		$aux			= $this->conn;
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Web_slider/Web_slider.php");
		$cEntidadAnterior	= new Web_slider();
		$cEntidadAnterior->setIdSlider($cEntidad->getIdSlider());
		$cEntidadAnterior = $this->readEntidad($cEntidadAnterior);

		$sql = "UPDATE web_slider SET  ";
		if (strtolower($cEntidad->getPathImagen()) == "on"){
			$sql .= "pathImagen= '' , ";
		}
		$sql = substr($sql,0,strlen($sql)-2);
		$sql .= " WHERE ";
		$sql .="idSlider=" . $aux->qstr($cEntidad->getIdSlider(), false) . " ";

		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][" . get_class($this) . "::quitaImagen()]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\tError::" . $aux->ErrorMsg() . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}else{
			if (strtolower($cEntidad->getPathImagen()) == "on"){
				$cEntidad->setPathImagen('');
				if ($cEntidadAnterior->getPathImagen() != ""){
					//Compruebo si hay más registros que utilicen la imagen
					$sql = "SELECT * FROM web_slider WHERE pathImagen LIKE " . $aux->qstr("%" . $cEntidadAnterior->getPathImagen(), false);
					$rs = $aux->Execute($sql);
					if ($rs->NumRows() < 1){
						@unlink($spath . $cEntidadAnterior->getPathImagen());
						//Borramos la miñatura
						$name= basename($cEntidadAnterior->getPathImagen());
						$namePath=str_replace($name,"m" . $name, $cEntidadAnterior->getPathImagen());
						@unlink($spath . $namePath);
					}
				}
			}
		}
		return $retorno;
	}
	/**
	* Devuelve si hay inconsistencia entre los idiomas activados y los dados de alta de un registro.
	* @param entidad Entidad a verificar
	* @return Boolean
	**/
	public function getIsAddIdioma($cEntidad)
	{
		$aux				= $this->conn;
		$nIdiomas			= 0;
		$nIdiomasActivos	= 0;
		$sql = "SELECT count(*) AS nIdiomas  FROM web_slider WHERE ";
		$sql  .="idSlider=" . $aux->qstr($cEntidad->getIdSlider(), false);
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$nIdiomas = $arr['nIdiomas'];
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getIsAddIdioma][" . get_class($this) . "][nIdiomas]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\tError::" . $aux->ErrorMsg() . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;

		}
		$sql = "SELECT count(*) AS nIdiomasActivos  FROM wi_idiomas ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$nIdiomasActivos = $arr['nIdiomasActivos'];
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getIsAddIdioma][" . get_class($this) . "][nIdiomasActivos]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\tError::" . $aux->ErrorMsg() . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;

		}
		return ($nIdiomas < $nIdiomasActivos);
	}
	/**
	* Devuelve los idiomas activos en el front preparados para un sql IN.
	* @return String
	**/
	public function getIdiomasFrontIN()
	{
		$aux				= $this->conn;
		$sRetorno			= "";
		$sql = "SELECT *  FROM wi_idiomas ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$sRetorno .= ",'" . $arr['codIdiomaIso2'] . "'";
			}
		}else{

			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getIdiomasFrontIN][" . get_class($this) . "]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\tError::" . $aux->ErrorMsg() . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;

		}
		if (!empty($sRetorno)){
			$sRetorno = substr($sRetorno, 1);
			$sRetorno = " IN (" . $sRetorno . ") ";
		}
		return $sRetorno;
	}
	/***********************************************************************
	* Inserta una entidad en la base de datos para un idioma.
	* @param entidad Entidad a insertar con Datos
	* @return long Numero de ID de la entidad
	* @exception Exception Error al ejecutar la acción
	*  en la base de datos
	***********************************************************************/
	public function insertarIdioma($cEntidad)
	{
		$aux			= $this->conn;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
		$cUtilidades	= new Utilidades();

		$newId = $cEntidad->getIdSlider();

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Upload.php");
		$img0 = new Upload("fWeb_sliderPathImagen");
		$img0->bAutoRenombrar = true;
		$img0->bSobreEscribir = true;
		$img0->image_mini = false;	// crear mini.
		$img0->image_mini_x = 200;	// Ancho por defecto de la miñatura.
		$img0->image_resize = true;	// redimensionar la imagen
		$img0->image_x = 1920;	// Ancho por defecto
		$img0->jpeg_quality = 75;
		// Tamaño de la imagen recortada
		$pasaImgSize0 = false;
		if(!empty($_FILES['fWeb_sliderDBPathImagen']['tmp_name'])){
			$imageSize0 = getimagesize($_FILES['fWeb_sliderDBPathImagen']['tmp_name']);
			$anchura0 = $imageSize0[0];
			$altura0 = $imageSize0[1];
			if($anchura0 > 1920 && $altura0 > 900){
				$pasaImgSize0 = true;
				if (!is_array($this->msg_Error))
					$this->msg_Error	= array();
				$sTypeError	=	constant("STR_IMAGEN_ADJUNTA") . " " . constant("STR_RECORTE_IMAGEN") . ".";
				$this->msg_Error[]	= $sTypeError;
			}else{
				if (!is_array($this->msg_Error))
					$this->msg_Error	= array();
				$sTypeError	=	constant("STR_IMAGEN_ADJUNTA") . " " . constant("STR_TAMANO_NO_VALIDO") . ".";
				$this->msg_Error[]	= $sTypeError;
				$retorno = false;
			}
		}
		$sDirImg="imgWeb_slider";
		$iContador = 0;
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if($pasaImgSize0 == true){
			if (!$img0->UploadFile($spath . $sDirImg))
			{
				$this->msg_Error = $img0->get_errores();
				return 0;
			}else{
				$cEntidad->setPathImagen($img0->getPath_WS());
			}
		}
				$sql = "INSERT INTO web_slider (";
				$sql .= "idSlider" . ",";
				$sql .= "code" . ",";
				$sql .= "titulo" . ",";
				$sql .= "pathImagen" . ",";
				$sql .= "urlDestino" . ",";
				$sql .= "orden" . ",";
				$sql .= "fecAlta" . ",";
				$sql .= "fecMod" . ",";
				$sql .= "usuAlta" . ",";
				$sql .= "usuMod" . ")";
				$sql .= " VALUES (";
				$sql .= $aux->qstr($newId, false) . ",";
				$sql .= $aux->qstr($cEntidad->getCode(), false) . ",";
				$sql .= $aux->qstr($cEntidad->getTitulo(), false) . ",";
				$sql .= $aux->qstr($cEntidad->getPathImagen(), false) . ",";
				$sql .= $aux->qstr($cEntidad->getUrlDestino(), false) . ",";
				$sql .= $aux->qstr($cEntidad->getOrden(), false) . ",";
				$sql .= $aux->sysTimeStamp . ",";
				$sql .= $aux->sysTimeStamp . ",";
				$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
				$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
				if($aux->Execute($sql) === false){
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][" . get_class($this) . "]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " ->\tError::" . $aux->ErrorMsg() . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					return 0;
				}
				else{
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambiosDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Historico_cambios/Historico_cambios.php");
					$cEntidadHistorico_cambiosDB	= new Historico_cambiosDB($aux);  // Entidad DB Historico_cambios
					$cEntidadHistorico_cambios		= new Historico_cambios();  // Entidad Historico_cambios
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Wi_usuarios/Wi_usuariosDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Wi_usuarios/Wi_usuarios.php");
					$_cEntidadWi_usuariosDB	= new Wi_usuariosDB($aux);
					$_cEntidadWi_usuarios		= new Wi_usuarios();
					$_cEntidadWi_usuarios->setIdUsuario($cEntidad->getUsuAlta());
					$_cEntidadWi_usuariosDB->readEntidad($_cEntidadWi_usuarios);
					$cEntidadHistorico_cambios->setFuncionalidad(basename($_SERVER['PHP_SELF']));
					$cEntidadHistorico_cambios->setModo(constant("MNT_ALTA"));
					$cEntidadHistorico_cambios->setQuery($sql);
					$cEntidadHistorico_cambios->setIp($_SERVER['REMOTE_ADDR']);
					$cEntidadHistorico_cambios->setIdUsuario($_cEntidadWi_usuarios->getIdUsuario());
					$cEntidadHistorico_cambios->setIdUsuarioTipo($_cEntidadWi_usuarios->getIdUsuarioTipo());
					$cEntidadHistorico_cambios->setLogin($_cEntidadWi_usuarios->getLogin());
					$cEntidadHistorico_cambios->setNombre($_cEntidadWi_usuarios->getNombre());
					$cEntidadHistorico_cambios->setApellido1($_cEntidadWi_usuarios->getApellido1());
					$cEntidadHistorico_cambios->setApellido2($_cEntidadWi_usuarios->getApellido2());
					$cEntidadHistorico_cambios->setEmail($_cEntidadWi_usuarios->getEmail());
					$cEntidadHistorico_cambios->setUsuAlta(($cEntidad->getUsuAlta() != "") ? $cEntidad->getUsuAlta() : "0");
					$cEntidadHistorico_cambios->setUsuMod(($cEntidad->getUsuMod() != "") ? $cEntidad->getUsuMod() : "0");
					$HistId	= $cEntidadHistorico_cambiosDB->insertar($cEntidadHistorico_cambios);
					if (empty($HistId)){
						$this->msg_Error	= array();
						$sTypeError	=	date('d/m/Y H:i:s') . " Error [GUARDANDO HISTORICO][" . constant("MNT_ALTA") . "][" . get_class($this) . "]";
						$this->msg_Error[]	= $sTypeError;
						error_log($sTypeError . " ->\tError::" . $aux->ErrorMsg() . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						header('Location: ' . constant("HTTP_SERVER") . 'index.php');
						exit;
					}
				}

	return $newId;
}


	/**
	* Revisa si esta clase está desnormaliada en otras entidades,
	* y alctualiza las descripciones en las dependencias con la clave de esta.
	* @return void
	**/
	public function setDesNomalizacion($cEntidad)
	{
		$aux			= $this->conn;
		$sql = "SELECT * FROM  wi_desnormalizacion WHERE tablaAuxiliar = " . $aux->qstr('web_slider', false);
		$rs = $aux->Execute($sql);
		while (!$rs->EOF)
		{
			$sGetMetodo="get" . ucfirst($rs->fields['campoAuxiliar']);
			$sql = "UPDATE " . $rs->fields['tablaDesnormalizada'] . " SET ";
			$sql .= $rs->fields['campoDesnormalizado'] . "=" . $aux->qstr($cEntidad->$sGetMetodo(), false);
			$sql .= " WHERE ";
			$sql .= "idSlider = " . $aux->qstr($cEntidad->getIdSlider(), false);
			if($aux->Execute($sql) === false){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][" . get_class($this) . "]::setDesNomalizacion";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\tError::" . $aux->ErrorMsg() . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}
			$rs->MoveNext();
		}
	}
}//Fin de la Clase Web_sliderDB
?>
