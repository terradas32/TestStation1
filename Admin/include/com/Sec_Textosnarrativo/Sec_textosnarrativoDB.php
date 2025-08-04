<?php 
/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla sec_textos_narrativo
**/
class Sec_textosnarrativoDB 
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
	function __construct($conn)
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
		
			//$newId = String.valueOf((intval(getSiguienteId(conConexion,"SELECT " . SQL.cGetId("IDTEXTO") . " FROM sec_textos_narrativo"))));
			$newId = $this->getSiguienteId($cEntidad);
			require_once(constant("DIR_WS_COM") . "Sec_dimensiones/Sec_dimensionesDB.php");
			require_once(constant("DIR_WS_COM") . "Sec_dimensiones/Sec_dimensiones.php");
			
			$cDimension = new Sec_dimensiones();
			$cDimensionDB = new Sec_dimensionesDB($aux);
			
			//Consultamos la dimensión sobre la que se va a hacer la inserción para ver
			//las puntuaciones mínimas y máximas.
			$cDimension->setIDTIPOPRUEBA($cEntidad->getIDTIPOPRUEBA());
			$cDimension->setIDCOMPETENCIA($cEntidad->getIDCOMPETENCIA());
			$cDimension->setIDDIMENSION($cEntidad->getIDDIMENSION());
			
			$cDimension = $cDimensionDB->consultar($cDimension);
			//String $sqlComprobacion = "select max(puntMax) from sec_textos_narrativo where idTipoPrueba = 3";
			
			//Puntuación mínima menor que la mínima.
			if(intval($cEntidad->getPUNTMIN()) < intval($cDimension->getPUNTUACIONMIN())){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . "La puntuación mínima introducida es menor que la mínima de la dimensión que es de ". $cDimension->getPUNTUACIONMIN() . ". [" . constant("MNT_ALTA") . "][Sec_textosnarrativoDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				return "0";
			}
			
			//Puntuación mínima mayor que la máxima.
			if(intval($cEntidad->getPUNTMIN())> intval($cDimension->getPUNTUACIONMAX())){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . "La puntuación mínima introducida es mayor que la máxima de la dimensión que es de " . $cDimension->getPUNTUACIONMAX() . ". [" . constant("MNT_ALTA") . "][Sec_textosnarrativoDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				return "0";
			}
			
			//Puntuación máxima mayor que la máxima.
			if(intval($cEntidad->getPUNTMAX())> intval($cDimension->getPUNTUACIONMAX())){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . "La puntuación máxima introducida es mayor que la máxima de la dimensión que es de " . $cDimension->getPUNTUACIONMAX() . ". [" . constant("MNT_ALTA") . "][Sec_textosnarrativoDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				return "0";
			}
			
			$puntMaxIntroducida = $this->calculaMaximaIntroducida($cEntidad);
			
			
			if($puntMaxIntroducida > intval($cEntidad->getPUNTMIN())){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . "Ya has introducido un texto para ese rango de valores, el valor máximo introducido hasta ahora es de " . $puntMaxIntroducida . " . [" . constant("MNT_ALTA") . "][Sec_textosnarrativoDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				return "0";
				
			}else{
				if($puntMaxIntroducida!=0){
					if(($puntMaxIntroducida+1) > intval($cEntidad->getPUNTMIN())){
						$this->msg_Error	= array();
						$sTypeError	=	date('d/m/Y H:i:s') . "Debe introducir los rangos de los textos de forma consecutiva, el valor máximo introducido hasta ahora es de " . $puntMaxIntroducida . " . [" . constant("MNT_ALTA") . "][Sec_textosnarrativoDB]";
						$this->msg_Error[]	= $sTypeError;
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						return "0";
					}
				}
			}
			
			$sCuerpo = str_replace("'", "''", $cEntidad->getTEXTO());
	
		$sql = "INSERT INTO sec_textos_narrativo (";
		$sql .= "IDTEXTO" . ",";
		$sql .= "IDTIPOPRUEBA" . ",";
		$sql .= "IDCOMPETENCIA" . ",";
		$sql .= "IDDIMENSION" . ",";
		$sql .= "PUNTMIN" . ",";
		$sql .= "PUNTMAX" . ",";
		$sql .= "TEXTO" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($newId) . ",";
		$sql .= $aux->qstr($cEntidad->getIDTIPOPRUEBA()) . ",";
		$sql .= $aux->qstr($cEntidad->getIDCOMPETENCIA()) . ",";
		$sql .= $aux->qstr($cEntidad->getIDDIMENSION()) . ",";
		$sql .= $aux->qstr($cEntidad->getPUNTMIN()) . ",";
		$sql .= $aux->qstr($cEntidad->getPUNTMAX()) . ",";
		$sql .= "'" . $sCuerpo . "',";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta()) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod()) . ")";
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
			
		$sql  = "SELECT MAX(IDTEXTO) AS Max FROM sec_textos_narrativo ";
		$sql  .="";
		$rs = $aux->Execute($sql);
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
			require_once(constant("DIR_WS_COM") . "Sec_dimensiones/Sec_dimensionesDB.php");
			require_once(constant("DIR_WS_COM") . "Sec_dimensiones/Sec_dimensiones.php");
			
			$cDimension = new Sec_dimensiones();
			$cDimensionDB = new Sec_dimensionesDB($aux);
		
			
			//Consultamos la dimensión sobre la que se va a hacer la inserción para ver
			//las puntuaciones mínimas y máximas.
			$cDimension->setIDTIPOPRUEBA($cEntidad->getIDTIPOPRUEBA());
			$cDimension->setIDCOMPETENCIA($cEntidad->getIDCOMPETENCIA());
			$cDimension->setIDDIMENSION($cEntidad->getIDDIMENSION());
			
			$cDimension = $cDimensionDB->consultar($cDimension);
			//String $sqlComprobacion = "select max(puntMax) from sec_textos_narrativo where idTipoPrueba = 3";
			
			//Puntuación mínima menor que la mínima.
			if(intval($cEntidad->getPUNTMIN())< intval($cDimension->getPUNTUACIONMIN())){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . "La puntuación mínima introducida es menor que la mínima de la dimensión que es de ". $cDimension->getPUNTUACIONMIN() . ". [" . constant("MNT_ALTA") . "][Sec_textosnarrativoDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				return false;
			}
			
			//Puntuación mínima mayor que la máxima.
			if(intval($cEntidad->getPUNTMIN())> intval($cDimension->getPUNTUACIONMAX())){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . "La puntuación mínima introducida es mayor que la máxima de la dimensión que es de " . $cDimension->getPUNTUACIONMAX() . ". [" . constant("MNT_ALTA") . "][Sec_textosnarrativoDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				return false;
			}
			
			//Puntuación máxima mayor que la máxima.
			if(intval($cEntidad->getPUNTMAX())> intval($cDimension->getPUNTUACIONMAX())){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . "La puntuación máxima introducida es mayor que la máxima de la dimensión que es de " . $cDimension->getPUNTUACIONMAX() . ". [" . constant("MNT_ALTA") . "][Sec_textosnarrativoDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				return false;
			}
			
			$puntMaxIntroducida = $this->calculaMaximaIntroducida($cEntidad);

			require_once(constant("DIR_WS_COM") . "Sec_textosnarrativo/Sec_textosnarrativo.php");
			
			
			$cTexto = new Sec_textosnarrativo();
			$cTexto->setPUNTMAX(String.valueOf($puntMaxIntroducida));
			$cTexto->setIDTIPOPRUEBA($cEntidad->getIDTIPOPRUEBA());
			$cTexto->setIDCOMPETENCIA($cEntidad->getIDCOMPETENCIA());
			$cTexto->setIDDIMENSION($cEntidad->getIDDIMENSION());
			
			$cTexto = $this->consultarCompleta($cTexto);
			
			
			if($puntMaxIntroducida > intval($cEntidad->getPUNTMIN())){
				if($cTexto->getIDTEXTO() != $cEntidad->getIDTEXTO()){
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . "Ya has introducido un texto para ese rango de valores, el valor máximo introducido hasta ahora es de " . $puntMaxIntroducida . ". [" . constant("MNT_ALTA") . "][Sec_textosnarrativoDB]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					return false;
				}
			}else{
				if(($puntMaxIntroducida+1) > intval($cEntidad->getPUNTMIN())){
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . "Debe introducir los rangos de los textos de forma consecutiva, el valor máximo introducido hasta ahora es de " . $puntMaxIntroducida . ". [" . constant("MNT_ALTA") . "][Sec_textosnarrativoDB]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					return false;
				}
			}
			
			$sCuerpo = str_replace("'", "''", $cEntidad->getTEXTO());
			$sql = "UPDATE sec_textos_narrativo SET ";
			$sql .= "IDTEXTO=" . $aux->qstr($cEntidad->getIDTEXTO()) . ", ";
			$sql .= "IDTIPOPRUEBA=" . $aux->qstr($cEntidad->getIDTIPOPRUEBA()) . ", ";
			$sql .= "IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA()) . ", ";
			$sql .= "IDDIMENSION=" . $aux->qstr($cEntidad->getIDDIMENSION()) . ", ";
			$sql .= "PUNTMIN=" . $aux->qstr($cEntidad->getPUNTMIN()) . ", ";
			$sql .= "PUNTMAX=" . $aux->qstr($cEntidad->getPUNTMAX()) . ", ";
			$sql .= "TEXTO='" . $sCuerpo . "', ";
			$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
			$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod()) ;
			$sql .= " WHERE ";
			$sql .="IDTEXTO=" . $aux->qstr($cEntidad->getIDTEXTO()) . " ";
			
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
	* Calcula la mánxima puntuación introducida para una dimensión en concreto
	* @param entidad Entidad con la información basica a consultar
	* @return int iPunt
	*************************************************************************/
	function calculaMaximaIntroducida($cEntidad){
		
		$aux			= $this->conn;
		$iPunt = 0;
		
			$sql  ="select max(puntMax) AS Max from sec_textos_narrativo ";
			$sql  .=" WHERE IDTIPOPRUEBA=" . $cEntidad->getIDTIPOPRUEBA() . " AND IDCOMPETENCIA=" . $cEntidad->getIDCOMPETENCIA() . " AND IDDIMENSION= " . $cEntidad->getIDDIMENSION() . " ";

			$rs = $aux->Execute($sql);
			if ($rs){
				while ($arr = $rs->FetchRow()){
					$iPunt = intval($arr['Max']);
				}
			}else{
				
				echo(constant("ERR"));
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][OpcionesDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				exit;
			
			}

		return $iPunt;
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
			$sql  ="DELETE FROM sec_textos_narrativo ";
			$sql  .=" WHERE ";
			if ($cEntidad->getIDTEXTO() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="IDTEXTO=" . $aux->qstr($cEntidad->getIDTEXTO()) . " ";
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
	* @return $cEntidad con la información recuperada.
	*************************************************************************/
	function consultar($cEntidad)
	{
		
		$aux			= $this->conn;
			
		$sql = "SELECT IDTEXTO,IDTIPOPRUEBA,IDCOMPETENCIA,IDDIMENSION,PUNTMIN,PUNTMAX,TEXTO," . "fecAlta" . "," . "fecMod" . ",usuAlta,usuMod FROM sec_textos_narrativo WHERE ";
		$sql  .="IDTEXTO=" . $aux->qstr($cEntidad->getIDTEXTO()) . " ";
		
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
		
					$cEntidad->setIDTEXTO($arr['IDTEXTO']);
					$cEntidad->setIDTIPOPRUEBA($arr['IDTIPOPRUEBA']);
					$cEntidad->setIDCOMPETENCIA($arr['IDCOMPETENCIA']);
					$cEntidad->setIDDIMENSION($arr['IDDIMENSION']);
					$cEntidad->setPUNTMIN($arr['PUNTMIN']);
					$cEntidad->setPUNTMAX($arr['PUNTMAX']);
					$cEntidad->setTEXTO($arr['TEXTO']);
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
	* @return $cEntidad con la información recuperada.
	*************************************************************************/
	function consultarCompleta($cEntidad)
	{
		
		$aux			= $this->conn;
	
		$sql = "SELECT IDTEXTO,IDTIPOPRUEBA,IDCOMPETENCIA,IDDIMENSION,PUNTMIN,PUNTMAX,TEXTO," . "fecAlta" . "," . "fecMod" . ",usuAlta,usuMod FROM sec_textos_narrativo WHERE ";
		$sql  .="IDTIPOPRUEBA=" . $aux->qstr($cEntidad->getIDTIPOPRUEBA()) . " AND IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA()) . " AND IDDIMENSION=" . $aux->qstr($cEntidad->getIDDIMENSION()) . " AND PUNTMAX=" . $aux->qstr($cEntidad->getPUNTMAX()) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
		
					$cEntidad->setIDTEXTO($arr['IDTEXTO']);
					$cEntidad->setIDTIPOPRUEBA($arr['IDTIPOPRUEBA']);
					$cEntidad->setIDCOMPETENCIA($arr['IDCOMPETENCIA']);
					$cEntidad->setIDDIMENSION($arr['IDDIMENSION']);
					$cEntidad->setPUNTMIN($arr['PUNTMIN']);
					$cEntidad->setPUNTMAX($arr['PUNTMAX']);
					$cEntidad->setTEXTO($arr['TEXTO']);
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
	
		$sql="";
		$and = false;
		$sql.="SELECT * FROM sec_textos_narrativo ";
		if ($cEntidad->getIDTEXTO() != null && $cEntidad->getIDTEXTO() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDTEXTO=" . $aux->qstr($cEntidad->getIDTEXTO());
		}
		if ($cEntidad->getIDTIPOPRUEBA() != null && $cEntidad->getIDTIPOPRUEBA() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDTIPOPRUEBA=" . $aux->qstr($cEntidad->getIDTIPOPRUEBA());
		}
		if ($cEntidad->getIDCOMPETENCIA() != null && $cEntidad->getIDCOMPETENCIA() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA());
		}
		if ($cEntidad->getIDDIMENSION() != null && $cEntidad->getIDDIMENSION() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDDIMENSION=" . $aux->qstr($cEntidad->getIDDIMENSION());
		}
		if ($cEntidad->getPUNTMIN() != null && $cEntidad->getPUNTMIN() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="PUNTMIN>=" . $aux->qstr($cEntidad->getPUNTMIN());
		}
		if ($cEntidad->getPUNTMINHast() != null && $cEntidad->getPUNTMINHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="PUNTMIN<=" . $aux->qstr($cEntidad->getPUNTMINHast());
		}
		if ($cEntidad->getPUNTMAX() != null && $cEntidad->getPUNTMAX() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="PUNTMAX>=" . $aux->qstr($cEntidad->getPUNTMAX());
		}
		if ($cEntidad->getPUNTMAXHast() != null && $cEntidad->getPUNTMAXHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="PUNTMAX<=" . $aux->qstr($cEntidad->getPUNTMAXHast());
		}
		if ($cEntidad->getTEXTO() != null && $cEntidad->getTEXTO() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(TEXTO) LIKE UPPER('%" . $cEntidad->getTEXTO() . "%')";
		}
		if ($cEntidad->getFecAlta() != null && $cEntidad->getFecAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta>=" . $aux->qstr($cEntidad->getFecAlta());
		}
		if ($cEntidad->getFecAltaHast() != null && $cEntidad->getFecAltaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta<=" . $aux->qstr($cEntidad->getFecAltaHast());
		}
		if ($cEntidad->getFecAlta() != null && $cEntidad->getFecAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta>=" . $aux->qstr($cEntidad->getFecAlta());
		}
		if ($cEntidad->getFecAltaHast() != null && $cEntidad->getFecAltaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta<=" . $aux->qstr($cEntidad->getFecAltaHast());
		}
		if ($cEntidad->getFecMod() != null && $cEntidad->getFecMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod>=" . $aux->qstr($cEntidad->getFecMod());
		}
		if ($cEntidad->getFecModHast() != null && $cEntidad->getFecModHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod<=" . $aux->qstr($cEntidad->getFecModHast());
		}
		if ($cEntidad->getFecMod() != null && $cEntidad->getFecMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod>=" . $aux->qstr($cEntidad->getFecMod());
		}
		if ($cEntidad->getFecModHast() != null && $cEntidad->getFecModHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod<=" . $aux->qstr($cEntidad->getFecModHast());
		}
		if ($cEntidad->getUsuAlta() != null && $cEntidad->getUsuAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="usuAlta=" . $aux->qstr($cEntidad->getUsuAlta());
		}
		if ($cEntidad->getUsuMod() != null && $cEntidad->getUsuMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="usuMod=" . $aux->qstr($cEntidad->getUsuMod());
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
	
	/*************************************************************************
	* Lista en la base de datos recogiendo la información
	* recibida por la entidad, Este metodo se utiliza para búsquedas
	* de uno o varios registros.
	* @param entidad Entidad con la información de la Búsqueda
	* @return Vector Lista recuperada
	*************************************************************************/
	function readListaTexto($cEntidad)
	{
	
		$sql="";
		$and = false;
		$sql.="SELECT * FROM sec_textos_narrativo ";
		if ($cEntidad->getIDTEXTO() != null && $cEntidad->getIDTEXTO() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDTEXTO=" . $aux->qstr($cEntidad->getIDTEXTO());
		}
		if ($cEntidad->getIDTIPOPRUEBA() != null && $cEntidad->getIDTIPOPRUEBA() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDTIPOPRUEBA=" . $aux->qstr($cEntidad->getIDTIPOPRUEBA());
		}
		if ($cEntidad->getIDCOMPETENCIA() != null && $cEntidad->getIDCOMPETENCIA() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDCOMPETENCIA=" . $aux->qstr($cEntidad->getIDCOMPETENCIA());
		}
		if ($cEntidad->getIDDIMENSION() != null && $cEntidad->getIDDIMENSION() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDDIMENSION=" . $aux->qstr($cEntidad->getIDDIMENSION());
		}
		if ($cEntidad->getPUNTMIN() != null && $cEntidad->getPUNTMIN() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="PUNTMIN>=" . $aux->qstr($cEntidad->getPUNTMIN());
		}
		if ($cEntidad->getPUNTMAX() != null && $cEntidad->getPUNTMAX() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="PUNTMAX<=" . $aux->qstr($cEntidad->getPUNTMAX());
		}
		if ($cEntidad->getTEXTO() != null && $cEntidad->getTEXTO() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(TEXTO) LIKE UPPER('%" . $cEntidad->getTEXTO() . "%')";
		}
		if ($cEntidad->getFecAlta() != null && $cEntidad->getFecAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta>=" . $aux->qstr($cEntidad->getFecAlta());
		}
		if ($cEntidad->getFecAltaHast() != null && $cEntidad->getFecAltaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta<=" . $aux->qstr($cEntidad->getFecAltaHast());
		}
		if ($cEntidad->getFecAlta() != null && $cEntidad->getFecAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta>=" . $aux->qstr($cEntidad->getFecAlta());
		}
		if ($cEntidad->getFecAltaHast() != null && $cEntidad->getFecAltaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta<=" . $aux->qstr($cEntidad->getFecAltaHast());
		}
		if ($cEntidad->getFecMod() != null && $cEntidad->getFecMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod>=" . $aux->qstr($cEntidad->getFecMod());
		}
		if ($cEntidad->getFecModHast() != null && $cEntidad->getFecModHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod<=" . $aux->qstr($cEntidad->getFecModHast());
		}
		if ($cEntidad->getFecMod() != null && $cEntidad->getFecMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod>=" . $aux->qstr($cEntidad->getFecMod());
		}
		if ($cEntidad->getFecModHast() != null && $cEntidad->getFecModHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod<=" . $aux->qstr($cEntidad->getFecModHast());
		}
		if ($cEntidad->getUsuAlta() != null && $cEntidad->getUsuAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="usuAlta=" . $aux->qstr($cEntidad->getUsuAlta());
		}
		if ($cEntidad->getUsuMod() != null && $cEntidad->getUsuMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="usuMod=" . $aux->qstr($cEntidad->getUsuMod());
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


}//Fin de la Clase Sec_textosnarrativoDB
?>