<?php
/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla funcionalidades
**/
class FuncionalidadesDB
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
		$this->msg_Error	= array();
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
		$newId			= $this->getSiguienteId($cEntidad);
		
		require_once(constant("DIR_WS_COM") . "Upload.php");
		$img0 = new Upload("fBgFile");
		$sDirImg="imgFuncionalidades";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if (!$img0->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img0->get_errores();
 			return 0;
		}else{
			$cEntidad->setBgFile($img0->getPath_WS());
		}
		
		//Se mira si se tiene que ordenar despues de alguna opción
		if ($cEntidad->getDespuesDe() == "" && $cEntidad->getDentroDe() == ""){
			
			//Lo ponemos el primero y de primer nivel
			// recoradmos que "" en DentroDe es "menu principal" y "" en DespuesDe es  "primero"
			$cEntidad->setOrden(0);
			$cEntidad->setIdPadre('');
			$cEntidad->setIndentacion(0);	//Raiz
			$this->getListaOrden($cEntidad);
			$cEntidad->setOrden(1);
			 
		}else{
			//Instanciomos un nuvo objeto entidad
			require_once(constant("DIR_WS_COM") . "Funcionalidades/Funcionalidades.php");
			$cEntidadPadre	= new Funcionalidades();  // Entidad
			
			if ($cEntidad->getDespuesDe() != "" )
			{
				//Leemos los datos de la opción (Padre) lo ponemos al mismo nivel
				//que el seleccionado, pero debajo de el.
				$cEntidadPadre->setOrden($cEntidad->getDespuesDe());
				$cEntidadPadre = $this->readEntidadOrden($cEntidadPadre);
				//Se mira cuantos hijos tiene el padre y los hijos de este...
				$sHijos = $this->getHijos($cEntidadPadre->getIdFuncionalidad());
				if (empty($sHijos)){
					$iHijos = 0;
				}else{
					$aHijos = explode(',',substr($sHijos, 0, strlen($sHijos)-1));
					$iHijos = sizeof($aHijos);
				}
				$cEntidad->setOrden($cEntidadPadre->getOrden() + $iHijos + 1);
				$cEntidad->setIdPadre($cEntidadPadre->getIdPadre());
				$cEntidad->setIndentacion($cEntidadPadre->getIndentacion());
				$cEntidadPadre->setOrden($cEntidadPadre->getOrden() + $iHijos);
				$this->getListaOrden($cEntidadPadre);
			}elseif($cEntidad->getDentroDe() != "" ){
				//Leemos los datos de la opción (Padre) de la q colgará
				$cEntidadPadre->setOrden($cEntidad->getDentroDe());
				$cEntidadPadre = $this->readEntidadOrden($cEntidadPadre);
				$cEntidad->setOrden($cEntidadPadre->getOrden()+1);
				$cEntidad->setIdPadre($cEntidadPadre->getIdFuncionalidad());
				$cEntidad->setIndentacion($cEntidadPadre->getIndentacion()+1);
				$this->getListaOrden($cEntidadPadre);
			}
		}
		
		$sql = "INSERT INTO wi_funcionalidades (";
		$sql .= "idFuncionalidad" . ",";
		$sql .= "nombre" . ",";
		$sql .= "descripcion" . ",";
		$sql .= "idPadre" . ",";
		$sql .= "url" . ",";
		$sql .= "popUp" . ",";
		$sql .= "orden" . ",";
		$sql .= "indentacion" . ",";
		$sql .= "bgFile" . ",";
		$sql .= "bgColor" . ",";
		$sql .= "modoDefecto" . ",";
		$sql .= "iconosMenu" . ",";
		$sql .= "publico" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($newId, false) . ",";
		$sql .= $aux->qstr($cEntidad->getNombre(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDescripcion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdPadre(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUrl(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPopUp(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getOrden(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIndentacion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getBgFile(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getBgColor(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getModoDefecto(), false) . ",";
		$sql .= $aux->qstr(implode(',', $cEntidad->getIconosMenu()), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPublico(), false) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
		
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][FuncionalidadesDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			return 0;
		}else{
			//Miramos si la funcionalidad es de uso público
			if ($cEntidad->getPublico() != ""){
				require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
				require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidades.php");
				$cEntidadPF = new Perfiles_funcionalidades();
				$cEntidadPF->setIdFuncionalidad($newId);
				$cEntidadPFDB = new Perfiles_funcionalidadesDB($aux);
				// 1. Borramos las asignaciones previas de la funcionalidad con los perfiles
				$cEntidadPFDB->borrar($cEntidadPF);
				// 2. Asignamos la funcionalidad a todos los perfiles
				require_once(constant("DIR_WS_COM") . "Perfiles/PerfilesDB.php");
				require_once(constant("DIR_WS_COM") . "Perfiles/Perfiles.php");
				$cEntidadP = new Perfiles();
				$cEntidadPDB = new PerfilesDB($aux);
				$sql = $cEntidadPDB->readLista($cEntidadP);
				$rsPerfiles = $aux->Execute($sql);
				while (!$rsPerfiles->EOF)
				{
					$sql = "INSERT INTO wi_perfiles_funcionalidades (";
					$sql .= "idPerfil" . ",";
					$sql .= "idFuncionalidad" . ",";
					$sql .= "modificar" . ",";
					$sql .= "borrar" . ",";
					$sql .= "fecAlta" . ",";
					$sql .= "fecMod" . ",";
					$sql .= "usuAlta" . ",";
					$sql .= "usuMod" . ")";
					$sql .= " VALUES (";
					$sql .= $aux->qstr($rsPerfiles->fields['idPerfil'], false) . ",";
					$sql .= $aux->qstr($newId, false) . ",";
					$sql .= $aux->qstr('on', false) . ",";
					$sql .= $aux->qstr('on', false) . ",";
					$sql .= $aux->sysTimeStamp . ",";
					$sql .= $aux->sysTimeStamp . ",";
					$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
					$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
					
					if($aux->Execute($sql) === false){
						$this->msg_Error	= array();
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][FuncionalidadesDB]";
						$this->msg_Error[]	= $sTypeError;
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						return 0;
					}
					$rsPerfiles->MoveNext();
				}
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
	
		$sql  = "SELECT MAX(idFuncionalidad) AS Max FROM wi_funcionalidades ";
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
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][FuncionalidadesDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}
		return $newId;
	}
	
	
/************************************************************************************************* 
	modifica y reordena los datos de la entidad, incluyendo a sus hijos.
															   
	$cEntidadVar --> entidad comodin para pasar datos por valor
	$cEntidad --> Datos de la funcionalidad que vamos a modificar, (datos sin modificar).
	$cDentroDe --> Datos de la funcionalidad DENTRODE de la que vamos a depender.
	$cDespuesDe --> Datos de la entidad DESPUESDE de la que vamos a depender.
																			 
	$aHijosPosActual --> Array con las ID de los hijos de la posicion actual.
	$iHijosPosActual --> Entero con el numero de hijos da la posiscion actual de la funcionalidad.
	$aHijosPosFinal --> Array con las ID de los hijos de la posicion final.
	$iHijosPosFinal --> Entero con el numero de hijos da la posiscion final a la que vamos a mover la funcionalidad.
**************************************************************************************************/	
	function modificar($cEntidad)
	{
		$aux			= $this->conn;
		$i=0;
		require_once(constant("DIR_WS_COM") . "Upload.php");
		
		$img0 = new Upload("fBgFile");					
		$sDirImg="imgFuncionalidades";					
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if (!$img0->UploadFile($spath . $sDirImg))		
		{
			$this->msg_Error = $img0->get_errores();	
			return false;								
		}else{
			$cEntidad->setBgFile($img0->getPath_WS());	
		}
		
		require_once(constant("DIR_WS_COM") . "Funcionalidades/FuncionalidadesDB.php");
		require_once(constant("DIR_WS_COM") . "Funcionalidades/Funcionalidades.php");
		// Entidad antes del cambio BBDD
		$cEntidadBack	= new Funcionalidades();  // Entidad
		$cEntidadBack->setIdFuncionalidad($cEntidad->getIdFuncionalidad());
		$cEntidadBack	= $this->readEntidad($cEntidadBack);
		//
		
		//si no hay ningun cambio de orden 'Dentro De' o 'Despues De', 
		//se modifican los datos básicos.
		if (($cEntidad->getDentroDe() == $cEntidadBack->getDentroDe()) &&
			($cEntidad->getDespuesDe() == $cEntidadBack->getOrden()) )
		{
			$sql = "UPDATE wi_funcionalidades SET ";
			$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
			$sql .= "descripcion=" . $aux->qstr($cEntidad->getDescripcion(), false) . ", ";
			$sql .= "url=" . $aux->qstr($cEntidad->getUrl(), false) . ", ";
			$sql .= "popUp=" . $aux->qstr($cEntidad->getPopUp(), false) . ", ";
			if ($cEntidad->getBgFile() != "")
				$sql .= "bgFile=" . $aux->qstr($cEntidad->getBgFile(), false) . ", ";
			$sql .= "bgColor=" . $aux->qstr($cEntidad->getBgColor(), false) . ", ";
			$sql .= "modoDefecto=" . $aux->qstr($cEntidad->getModoDefecto(), false) . ", ";
			$sql .= "iconosMenu=" . $aux->qstr(implode(',', $cEntidad->getIconosMenu()), false) . ", ";
			$sql .= "publico=" . $aux->qstr($cEntidad->getPublico(), false) . ", ";
			$sql .= "fecMod=" . $aux->sysTimeStamp . ", ";
			$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
			$sql .= " WHERE ";
			$sql .="idFuncionalidad=" . $aux->qstr($cEntidad->getIdFuncionalidad(), false) . " ";
			$retorno=true;
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][FuncionalidadesDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}else{
				//Miramos si la funcionalidad es de uso público
				if ($cEntidad->getPublico() != ""){
					require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
					require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidades.php");
					$cEntidadPF = new Perfiles_funcionalidades();
					$cEntidadPF->setIdFuncionalidad($cEntidad->getIdFuncionalidad());
					$cEntidadPFDB = new Perfiles_funcionalidadesDB($aux);
					// 1. Borramos las asignaciones previas de la funcionalidad con los perfiles
					$cEntidadPFDB->borrar($cEntidadPF);
					// 2. Asignamos la funcionalidad a todos los perfiles
					require_once(constant("DIR_WS_COM") . "Perfiles/PerfilesDB.php");
					require_once(constant("DIR_WS_COM") . "Perfiles/Perfiles.php");
					$cEntidadP = new Perfiles();
					$cEntidadPDB = new PerfilesDB($aux);
					$sql = $cEntidadPDB->readLista($cEntidadP);
					$rsPerfiles = $aux->Execute($sql);
					while (!$rsPerfiles->EOF)
					{
						$sql = "INSERT INTO wi_perfiles_funcionalidades (";
						$sql .= "idPerfil" . ",";
						$sql .= "idFuncionalidad" . ",";
						$sql .= "modificar" . ",";
						$sql .= "borrar" . ",";
						$sql .= "fecAlta" . ",";
						$sql .= "fecMod" . ",";
						$sql .= "usuAlta" . ",";
						$sql .= "usuMod" . ")";
						$sql .= " VALUES (";
						$sql .= $aux->qstr($rsPerfiles->fields['idPerfil'], false) . ",";
						$sql .= $aux->qstr($cEntidad->getIdFuncionalidad(), false) . ",";
						$sql .= $aux->qstr('on', false) . ",";
						$sql .= $aux->qstr('on', false) . ",";
						$sql .= $aux->sysTimeStamp . ",";
						$sql .= $aux->sysTimeStamp . ",";
						$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
						$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
						
						if($aux->Execute($sql) === false){
							$this->msg_Error	= array();
							$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][FuncionalidadesDB]";
							$this->msg_Error[]	= $sTypeError;
							error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
							return 0;
						}
						$rsPerfiles->MoveNext();
					}
				}else{
					//Antes era público ahora NO
					if ($cEntidadBack->getPublico() != ""){
						//Quitamos todos los accesos menos la del administrador idPerfil == 0
						require_once(constant("DIR_WS_COM") . "Perfiles/PerfilesDB.php");
						require_once(constant("DIR_WS_COM") . "Perfiles/Perfiles.php");
						require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
						require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidades.php");
						$cEntidadPF		= new Perfiles_funcionalidades();
						$cEntidadPFDB	= new Perfiles_funcionalidadesDB($aux);
						$cEntidadP = new Perfiles();
						$cEntidadPDB = new PerfilesDB($aux);
						$sql = $cEntidadPDB->readLista($cEntidadP);
						$rsPerfiles = $aux->Execute($sql);
						while (!$rsPerfiles->EOF)
						{
							if ($rsPerfiles->fields['idPerfil'] != 0){
								$cEntidadPF		= new Perfiles_funcionalidades();
								$cEntidadPF->setIdPerfil($rsPerfiles->fields['idPerfil']);
								// 1. Borramos las asignaciones previas de la funcionalidad con los perfiles
								//Menos la del perfil de administrador
								$cEntidadPFDB->borrar($cEntidadPF);
							}
							$rsPerfiles->MoveNext();
						}
					}
				}
			}
		}else{ 
			//si hay cambio de orden 'Dentro De' o 'Despues De' 
			
			$cDentroDe		= new Funcionalidades();  // Entidad
			$cDespuesDe	= new Funcionalidades();  // Entidad
			$cEntidadVar	= new Funcionalidades();  // Entidad
			
			$cDentroDe->setOrden($cEntidad->getDentroDe());
			$cDentroDe = $this->readEntidadOrden($cDentroDe);
			
			$cDespuesDe->setOrden($cEntidad->getDespuesDe());
			$cDespuesDe = $this->readEntidadOrden($cDespuesDe);
			
			//Recuperamos todos los hijos de la entidad antes de ser modificada.
			$sHijosPosActual = $this->getHijos($cEntidadBack->getIdFuncionalidad());

			if (empty($sHijosPosActual)){
				$iHijosPosActual = 0;
			}else{
				$aHijosPosActual = explode(',',substr($sHijosPosActual, 0, strlen($sHijosPosActual)-1));
				$iHijosPosActual = sizeof($aHijosPosActual);
			}
			
			//Miramos si hay cambio de posición 'Dentro De'
			if ($cEntidad->getDentroDe() != $cEntidadBack->getDentroDe())
			{
				// una padre no se puede mover dentro de sus hijos o de si mismo
				for ($i=0; $i < $iHijosPosActual; $i++)
				{
					if (($cDentroDe->getIdFuncionalidad() == $aHijosPosActual[$i] 
						|| $cDentroDe->getIdFuncionalidad() == $cEntidadBack->getIdFuncionalidad()))
					{
						$this->msg_Error	= array();
						$sTypeError	=	date('d/m/Y H:i:s') . " Error [" . constant("MNT_MODIFICAR") . "][FuncionalidadesDB][Accion no permitida, mover el Padre dentro de sus hijos o de si mismo.]";
						$this->msg_Error[]	= $sTypeError;
						error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						return false;
					}
				}
			}
			
			//Miramos si hay cambio de orden 'Despues De'
			if ($cEntidad->getDespuesDe() != $cEntidadBack->getOrden())
			{
				//Recuperamos todos los hijos de la entidad despuesDe
				//donde se insertará el registro.
				$sHijosPosFinal = $this->getHijos($cDespuesDe->getIdFuncionalidad());
				if (empty($sHijosPosFinal)){
					$iHijosPosFinal = 0;
				}else{
					$aHijosPosFinal = explode(',',substr($sHijosPosFinal, 0, strlen($sHijosPosFinal)-1));
					$iHijosPosFinal = sizeof($aHijosPosFinal);	
				}
				
			}
			if ($iHijosPosActual > 0)
			{
				// si movemos el padre movemos sus hijos
				for ($i = 0; $i <= $iHijosPosActual; $i++)
				{
					// reordenamos la lista desde la posicion a cambiar
					if ($cDespuesDe->getOrden() == ""){	//El primero
						$cEntidadVar->setOrden($cDentroDe->getOrden() + $i);
					}else{
						$cEntidadVar->setOrden($cDespuesDe->getOrden() + $i + $iHijosPosFinal);
					}
					$this->getListaOrden($cEntidadVar);
				
					//actualiza datos y posiones
					$sql = "UPDATE wi_funcionalidades SET ";
					if ($i == 0){
						if ($cEntidad->getDentroDe() == ""){ //Es el raiz
							$sql .= "idPadre=" . 0 . ", ";
							$sql .= "indentacion=" . 0 . ", ";
						}else{
							$sql .= "idPadre=" . $aux->qstr($cDentroDe->getIdFuncionalidad(), false) . ", ";
							$sql .= "indentacion=" . $aux->qstr($cDentroDe->getIndentacion()+1, false) . ", ";
						}
						if ($cEntidad->getDespuesDe() == ""){
							$sql .= "orden=" . $aux->qstr($cDentroDe->getOrden() + $i + 1, false) . ", ";
						}else{
							$sql .= "orden=" . $aux->qstr($cDespuesDe->getOrden()+$i+1+$iHijosPosFinal, false) . ", ";
						}
						if ($cEntidad->getBgFile() != "")
							$sql .= "bgFile=" . $aux->qstr($cEntidad->getBgFile(), false) . ", ";
						$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
						$sql .= "descripcion=" . $aux->qstr($cEntidad->getDescripcion(), false) . ", ";
						$sql .= "url=" . $aux->qstr($cEntidad->getUrl(), false) . ", ";
						$sql .= "popUp=" . $aux->qstr($cEntidad->getPopUp(), false) . ", ";
						$sql .= "bgColor=" . $aux->qstr($cEntidad->getBgColor(), false) . ", ";
						$sql .= "modoDefecto=" . $aux->qstr($cEntidad->getModoDefecto(), false) . ", ";
						$sql .= "iconosMenu=" . $aux->qstr(implode(',', $cEntidad->getIconosMenu()), false) . ", ";
						$sql .= "publico=" . $aux->qstr($cEntidad->getPublico(), false) . ", ";
						$sql .= "fecMod=" . $aux->sysTimeStamp . ", ";
						$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
						$sql .= " WHERE ";
						$sql .="idFuncionalidad=" . $aux->qstr($cEntidad->getIdFuncionalidad(), false) . " ";
						
					}else{
						if ($cEntidad->getDespuesDe() == ""){
							$sql .= "orden=" . $aux->qstr($cDentroDe->getOrden()+$i+1, false) . ", ";
						}else{
							$sql .= "orden=" . $aux->qstr($cDespuesDe->getOrden()+$i+1+$iHijosPosFinal, false) . ", ";
						}
						if ($cEntidad->getDentroDe() == ""){ // Raiz
							$cEntidadVar->setIdFuncionalidad($aHijosPosActual[$i-1]);
							$this->readEntidad($cEntidadVar);						
							$sql .= "indentacion=" . ($cEntidadVar->getIndentacion() - $cEntidadBack->getIndentacion());
						}else{
							$sql .= "indentacion=" . (($cDentroDe->getIndentacion()+1)- $cEntidad->getIndentacion()) . " + indentacion ";
						}
						$sql .= " WHERE ";
						$sql .="idFuncionalidad=" . $aHijosPosActual[$i-1] . " ";
					}
					$retorno=true;
					if($aux->Execute($sql) === false){
						$retorno=false;
						$this->msg_Error	= array();
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][FuncionalidadesDB]";
						$this->msg_Error[]	= $sTypeError;
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}
//****************************
					if ($i == 0){
						//Miramos si la funcionalidad es de uso público
						if ($cEntidad->getPublico() != ""){
							require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
							require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidades.php");
							$cEntidadPF = new Perfiles_funcionalidades();
							$cEntidadPF->setIdFuncionalidad($cEntidad->getIdFuncionalidad());
							$cEntidadPFDB = new Perfiles_funcionalidadesDB($aux);
							// 1. Borramos las asignaciones previas de la funcionalidad con los perfiles
							$cEntidadPFDB->borrar($cEntidadPF);
							// 2. Asignamos la funcionalidad a todos los perfiles
							require_once(constant("DIR_WS_COM") . "Perfiles/PerfilesDB.php");
							require_once(constant("DIR_WS_COM") . "Perfiles/Perfiles.php");
							$cEntidadP = new Perfiles();
							$cEntidadPDB = new PerfilesDB($aux);
							$sql = $cEntidadPDB->readLista($cEntidadP);
							$rsPerfiles = $aux->Execute($sql);
							while (!$rsPerfiles->EOF)
							{
								$sql = "INSERT INTO wi_perfiles_funcionalidades (";
								$sql .= "idPerfil" . ",";
								$sql .= "idFuncionalidad" . ",";
								$sql .= "modificar" . ",";
								$sql .= "borrar" . ",";
								$sql .= "fecAlta" . ",";
								$sql .= "fecMod" . ",";
								$sql .= "usuAlta" . ",";
								$sql .= "usuMod" . ")";
								$sql .= " VALUES (";
								$sql .= $aux->qstr($rsPerfiles->fields['idPerfil'], false) . ",";
								$sql .= $aux->qstr($cEntidad->getIdFuncionalidad(), false) . ",";
								$sql .= $aux->qstr('on', false) . ",";
								$sql .= $aux->qstr('on', false) . ",";
								$sql .= $aux->sysTimeStamp . ",";
								$sql .= $aux->sysTimeStamp . ",";
								$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
								$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
								
								if($aux->Execute($sql) === false){
									$this->msg_Error	= array();
									$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][FuncionalidadesDB]";
									$this->msg_Error[]	= $sTypeError;
									error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
									return 0;
								}
								$rsPerfiles->MoveNext();
							}
						}else{
							//Antes era público ahora NO
							if ($cEntidadBack->getPublico() != ""){
								//Quitamos todos los accesos menos la del administrador idPerfil == 0
								require_once(constant("DIR_WS_COM") . "Perfiles/PerfilesDB.php");
								require_once(constant("DIR_WS_COM") . "Perfiles/Perfiles.php");
								require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
								require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidades.php");
								$cEntidadPF		= new Perfiles_funcionalidades();
								$cEntidadPFDB	= new Perfiles_funcionalidadesDB($aux);
								$cEntidadP = new Perfiles();
								$cEntidadPDB = new PerfilesDB($aux);
								$sql = $cEntidadPDB->readLista($cEntidadP);
								$rsPerfiles = $aux->Execute($sql);
								while (!$rsPerfiles->EOF)
								{
									if ($rsPerfiles->fields['idPerfil'] != 0){
										$cEntidadPF		= new Perfiles_funcionalidades();
										$cEntidadPF->setIdPerfil($rsPerfiles->fields['idPerfil']);
										// 1. Borramos las asignaciones previas de la funcionalidad con los perfiles
										//Menos la del perfil de administrador
										$cEntidadPF->borrar($cEntidadPF);
									}
									$rsPerfiles->MoveNext();
								}
							}
						}
					}
//****************************
				}//Fin for
			}else{
				//No tiene hijos
				// reordenamos la lista desde la posicion a cambiar
				if ($cDespuesDe->getOrden() == ""){	//El primero
					$cEntidadVar->setOrden($cDentroDe->getOrden() + $i);
				}else{
					$cEntidadVar->setOrden($cDespuesDe->getOrden() + $i + $iHijosPosFinal);
				}
				$this->getListaOrden($cEntidadVar);
				
				//actualiza datos y posiones
				$sql = "UPDATE wi_funcionalidades SET ";
				if ($cEntidad->getDentroDe() == ""){ //Es el raiz
					$sql .= "idPadre=" . 0 . ", ";
					$sql .= "indentacion=" . 0 . ", ";
				}else{
					$sql .= "idPadre=" . $aux->qstr($cDentroDe->getIdFuncionalidad(), false) . ", ";
					$sql .= "indentacion=" . $aux->qstr($cDentroDe->getIndentacion()+1, false) . ", ";
				}
				if ($cEntidad->getDespuesDe() == ""){
					$sql .= "orden=" . $aux->qstr($cDentroDe->getOrden() + $i + 1, false) . ", ";
				}else{
					$sql .= "orden=" . $aux->qstr($cDespuesDe->getOrden()+$i+1+$iHijosPosFinal, false) . ", ";
				}
				if ($cEntidad->getBgFile() != "")
					$sql .= "bgFile=" . $aux->qstr($cEntidad->getBgFile(), false) . ", ";
				$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
				$sql .= "descripcion=" . $aux->qstr($cEntidad->getDescripcion(), false) . ", ";
				$sql .= "url=" . $aux->qstr($cEntidad->getUrl(), false) . ", ";
				$sql .= "popUp=" . $aux->qstr($cEntidad->getPopUp(), false) . ", ";
				$sql .= "bgColor=" . $aux->qstr($cEntidad->getBgColor(), false) . ", ";
				$sql .= "modoDefecto=" . $aux->qstr($cEntidad->getModoDefecto(), false) . ", ";
				$sql .= "iconosMenu=" . $aux->qstr(implode(',', $cEntidad->getIconosMenu()), false) . ", ";
				$sql .= "publico=" . $aux->qstr($cEntidad->getPublico(), false) . ", ";
				$sql .= "fecMod=" . $aux->sysTimeStamp . ", ";
				$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
				$sql .= " WHERE ";
				$sql .="idFuncionalidad=" . $aux->qstr($cEntidad->getIdFuncionalidad(), false) . " ";
				$retorno=true;
				if($aux->Execute($sql) === false){
					$retorno=false;
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][FuncionalidadesDB]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				}else{
					//Miramos si la funcionalidad es de uso público
					if ($cEntidad->getPublico() != ""){
						require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
						require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidades.php");
						$cEntidadPF = new Perfiles_funcionalidades();
						$cEntidadPF->setIdFuncionalidad($cEntidad->getIdFuncionalidad());
						$cEntidadPFDB = new Perfiles_funcionalidadesDB($aux);
						// 1. Borramos las asignaciones previas de la funcionalidad con los perfiles
						$cEntidadPFDB->borrar($cEntidadPF);
						// 2. Asignamos la funcionalidad a todos los perfiles
						require_once(constant("DIR_WS_COM") . "Perfiles/PerfilesDB.php");
						require_once(constant("DIR_WS_COM") . "Perfiles/Perfiles.php");
						$cEntidadP = new Perfiles();
						$cEntidadPDB = new PerfilesDB($aux);
						$sql = $cEntidadPDB->readLista($cEntidadP);
						$rsPerfiles = $aux->Execute($sql);
						while (!$rsPerfiles->EOF)
						{
							$sql = "INSERT INTO wi_perfiles_funcionalidades (";
							$sql .= "idPerfil" . ",";
							$sql .= "idFuncionalidad" . ",";
							$sql .= "modificar" . ",";
							$sql .= "borrar" . ",";
							$sql .= "fecAlta" . ",";
							$sql .= "fecMod" . ",";
							$sql .= "usuAlta" . ",";
							$sql .= "usuMod" . ")";
							$sql .= " VALUES (";
							$sql .= $aux->qstr($rsPerfiles->fields['idPerfil'], false) . ",";
							$sql .= $aux->qstr($cEntidad->getIdFuncionalidad(), false) . ",";
							$sql .= $aux->qstr('on', false) . ",";
							$sql .= $aux->qstr('on', false) . ",";
							$sql .= $aux->sysTimeStamp . ",";
							$sql .= $aux->sysTimeStamp . ",";
							$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
							$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
							
							if($aux->Execute($sql) === false){
								$this->msg_Error	= array();
								$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][FuncionalidadesDB]";
								$this->msg_Error[]	= $sTypeError;
								error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
								return 0;
							}
							$rsPerfiles->MoveNext();
						}
					}else{
						//Antes era público ahora NO
						if ($cEntidadBack->getPublico() != ""){
							//Quitamos todos los accesos menos la del administrador idPerfil == 0
							require_once(constant("DIR_WS_COM") . "Perfiles/PerfilesDB.php");
							require_once(constant("DIR_WS_COM") . "Perfiles/Perfiles.php");
							require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
							require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidades.php");
							$cEntidadPF		= new Perfiles_funcionalidades();
							$cEntidadPFDB	= new Perfiles_funcionalidadesDB($aux);
							$cEntidadP = new Perfiles();
							$cEntidadPDB = new PerfilesDB($aux);
							$sql = $cEntidadPDB->readLista($cEntidadP);
							$rsPerfiles = $aux->Execute($sql);
							while (!$rsPerfiles->EOF)
							{
								if ($rsPerfiles->fields['idPerfil'] != 0){
									$cEntidadPF		= new Perfiles_funcionalidades();
									$cEntidadPF->setIdPerfil($rsPerfiles->fields['idPerfil']);
									// 1. Borramos las asignaciones previas de la funcionalidad con los perfiles
									//Menos la del perfil de administrador
									$cEntidadPF->borrar($cEntidadPF);
								}
								$rsPerfiles->MoveNext();
							}
						}
					}
				}
			}

			//reordenamos la entidad desde 1 por si hay huecos
			$cEntidadVar->setOrden(0);
			$this->getListaRenumera($cEntidadVar);
		} // fin if cambio de orden
		return $retorno;
	} // //fin modificar

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
		$retorno = is_file(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
		if (!$retorno){
			$retorno=false;
			$sTypeError	=	date('d/m/Y H:i:s') . " Error Class Not Found [" . constant("MNT_BORRAR") . "][FuncionalidadesDB::Perfiles_funcionalidadesDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->	" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
		//Borramos las dependencias (borrado Físico).
		if ($retorno)
		{
			require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
			require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidades.php");
			$cPerfiles_funcionalidadesDB	= new Perfiles_funcionalidadesDB($aux);  // Entidad DB
			$cPerfiles_funcionalidades	= new Perfiles_funcionalidades();  // Entidad
			$cPerfiles_funcionalidades->setIdFuncionalidad($cEntidad->getIdFuncionalidad());
			if (!$cPerfiles_funcionalidadesDB->borrar($cPerfiles_funcionalidades))
			{
				$retorno=false;
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][FuncionalidadesDB::Perfiles_funcionalidadesDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->	" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}
		}
		if ($retorno){
			//Antes de borrar sacamos los datos de la entidad para reordenar
			$cEntidad = $this->readEntidad($cEntidad);

			 //Actualizacion de las dependencias Padre-Hijo
			$sql  ="UPDATE wi_funcionalidades SET idPadre=" . $cEntidad->getIdPadre() . ", indentacion=" . $cEntidad->getIndentacion();
			$sql  .=" WHERE idPadre=".$cEntidad->getIdFuncionalidad();
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][FuncionalidadesDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}
			//Borramos el registro de la Entidad (borrado Físico).
			$sql  ="DELETE FROM wi_funcionalidades ";
			$sql  .="WHERE ";
			if ($cEntidad->getIdFuncionalidad() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idFuncionalidad=". $aux->qstr($cEntidad->getIdFuncionalidad(), false) . " ";
			}
			
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][FuncionalidadesDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}else{
				$this->getListaOrdenDel($cEntidad);
			}
		}else $retorno=false;
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
		$sql = "SELECT * FROM wi_funcionalidades WHERE ";
		$sql  .="idFuncionalidad=" . $aux->qstr($cEntidad->getIdFuncionalidad(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdFuncionalidad($arr['idFuncionalidad']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setDescripcion($arr['descripcion']);
					$cEntidad->setIdPadre($arr['idPadre']);
					$cEntidad->setUrl($arr['url']);
					$cEntidad->setPopUp($arr['popUp']);
					$cEntidad->setDespuesDe($arr['orden']);
					$cEntidad->setOrden($arr['orden']);
					$cEntidad->setIndentacion($arr['indentacion']);
					$cEntidad->setBgFile($arr['bgFile']);
					$cEntidad->setBgColor($arr['bgColor']);
					$cEntidad->setModoDefecto($arr['modoDefecto']);
					$cEntidad->setIconosMenu(explode(',', $arr['iconosMenu']));
					$cEntidad->setPublico($arr['publico']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
			require_once(constant("DIR_WS_COM") . "Funcionalidades/Funcionalidades.php");	
			$cEntidadPadre	= new Funcionalidades();  // Entidad
		
//			$cEntidadPadre = $cEntidad;
			$cEntidadPadre->setIdPadre($cEntidad->getIdPadre());
			$cEntidadPadre = $this->readEntidadPadre($cEntidadPadre);
			$cEntidad->setDentroDe($cEntidadPadre->getOrden());
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][FuncionalidadesDB]";
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
	* Recupera un registro de tipo entidad que su id es igual a su padre.
	*************************************************************************/
	function readEntidadPadre($cEntidad)
	{
		$aux			= $this->conn;
		$sql = "SELECT * FROM wi_funcionalidades WHERE ";
		$sql  .="idFuncionalidad=" . $aux->qstr($cEntidad->getIdPadre(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			$cEntidad->setOrden("");
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdFuncionalidad($arr['idFuncionalidad']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setDescripcion($arr['descripcion']);
					$cEntidad->setIdPadre($arr['idPadre']);
					$cEntidad->setUrl($arr['url']);
					$cEntidad->setPopUp($arr['popUp']);
					$cEntidad->setOrden($arr['orden']);
					$cEntidad->setIndentacion($arr['indentacion']);
					$cEntidad->setBgFile($arr['bgFile']);
					$cEntidad->setBgColor($arr['bgColor']);
					$cEntidad->setModoDefecto($arr['modoDefecto']);
					$cEntidad->setIconosMenu(explode(',', $arr['iconosMenu']));
					$cEntidad->setPublico($arr['publico']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidadPadre][FuncionalidadesDB]";
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
		$sql.="SELECT * FROM wi_funcionalidades ";
		if ($cEntidad->getIdFuncionalidad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idFuncionalidad=" . $aux->qstr($cEntidad->getIdFuncionalidad(), false);
		}
		if ($cEntidad->getNombre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nombre) LIKE UPPER('%" . $cEntidad->getNombre() . "%')";
		}
		if ($cEntidad->getDescripcion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descripcion) LIKE UPPER('%" . $cEntidad->getDescripcion() . "%')";
		}
		if ($cEntidad->getIdPadre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPadre=" . $aux->qstr($cEntidad->getIdPadre(), false);
		}
		if ($cEntidad->getUrl() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(url) LIKE UPPER('%" . $cEntidad->getUrl() . "%')";
		}
		if ($cEntidad->getPopUp() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(popUp) LIKE UPPER('%" . $cEntidad->getPopUp() . "%')";
		}
		if ($cEntidad->getOrden() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(orden) LIKE UPPER('%" . $cEntidad->getOrden() . "%')";
		}
		if ($cEntidad->getIndentacion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(indentacion) LIKE UPPER('%" . $cEntidad->getIndentacion() . "%')";
		}
		if ($cEntidad->getBgFile() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(bgFile) LIKE UPPER('%" . $cEntidad->getBgFile() . "%')";
		}
		if ($cEntidad->getBgColor() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(bgColor) LIKE UPPER('%" . $cEntidad->getBgColor() . "%')";
		}
		if ($cEntidad->getModoDefecto() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(modoDefecto) = UPPER('" . $cEntidad->getModoDefecto() . "')";
		}
		if ($cEntidad->getFecAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(fecAlta) LIKE UPPER('%" . $cEntidad->getFecAlta() . "%')";
		}
		if ($cEntidad->getFecMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(fecMod) LIKE UPPER('%" . $cEntidad->getFecMod() . "%')";
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
		}else{
			$sql .=" ORDER BY orden";
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
	function readListaUrl($cEntidad)
	{
	
		$aux			= $this->conn;
	
		$sql="";
		$and = false;
		$sql.="SELECT * FROM wi_funcionalidades ";
		if ($cEntidad->getIdFuncionalidad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idFuncionalidad=" . $aux->qstr($cEntidad->getIdFuncionalidad(), false);
		}
		if ($cEntidad->getNombre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nombre) LIKE UPPER('%" . $cEntidad->getNombre() . "%')";
		}
		if ($cEntidad->getDescripcion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(descripcion) LIKE UPPER('%" . $cEntidad->getDescripcion() . "%')";
		}
		if ($cEntidad->getIdPadre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPadre=" . $aux->qstr($cEntidad->getIdPadre(), false);
		}
		if ($cEntidad->getUrl() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="url=" . $aux->qstr($cEntidad->getUrl(), false);
		}
		if ($cEntidad->getPopUp() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(popUp) LIKE UPPER('%" . $cEntidad->getPopUp() . "%')";
		}
		if ($cEntidad->getOrden() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(orden) LIKE UPPER('%" . $cEntidad->getOrden() . "%')";
		}
		if ($cEntidad->getIndentacion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(indentacion) LIKE UPPER('%" . $cEntidad->getIndentacion() . "%')";
		}
		if ($cEntidad->getBgFile() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(bgFile) LIKE UPPER('%" . $cEntidad->getBgFile() . "%')";
		}
		if ($cEntidad->getBgColor() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(bgColor) LIKE UPPER('%" . $cEntidad->getBgColor() . "%')";
		}
		if ($cEntidad->getModoDefecto() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(modoDefecto) = UPPER('" . $cEntidad->getModoDefecto() . "')";
		}
		if ($cEntidad->getFecAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(fecAlta) LIKE UPPER('%" . $cEntidad->getFecAlta() . "%')";
		}
		if ($cEntidad->getFecMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(fecMod) LIKE UPPER('%" . $cEntidad->getFecMod() . "%')";
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
		}else{
			$sql .=" ORDER BY orden";
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
		$sql = "UPDATE wi_funcionalidades SET  ";
		if (strtolower($cEntidad->getBgFile()) == "on"){
			$cEntidad->setBgFile('');
			$sql .= "bgFile=" . $aux->qstr($cEntidad->getBgFile(), false) . ", ";
		}
		$sql = substr($sql,0,strlen($sql)-2);
		$sql .= " WHERE ";
		$sql .="idFuncionalidad=" . $aux->qstr($cEntidad->getIdFuncionalidad(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][SpotDB::quitaImagen()]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
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
	function readEntidadOrden($cEntidad)
	{
		$aux			= $this->conn;
	
		$sql = "SELECT * FROM wi_funcionalidades WHERE ";
		$sql  .="orden=" . $aux->qstr($cEntidad->getOrden(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdFuncionalidad($arr['idFuncionalidad']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setDescripcion($arr['descripcion']);
					$cEntidad->setIdPadre($arr['idPadre']);
					$cEntidad->setUrl($arr['url']);
					$cEntidad->setPopUp($arr['popUp']);
					$cEntidad->setOrden($arr['orden']);
					$cEntidad->setIndentacion($arr['indentacion']);
					$cEntidad->setBgFile($arr['bgFile']);
					$cEntidad->setBgColor($arr['bgColor']);
					$cEntidad->setModoDefecto($arr['modoDefecto']);
					$cEntidad->setIconosMenu(explode(',', $arr['iconosMenu']));
					$cEntidad->setPublico($arr['publico']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidadOrden][FuncionalidadesDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}
		return $cEntidad;
	}
	
	/******************************************************************************************
	* Devuelve una cadena para insertar un valor de secuencia.
	* @return String nuevo orden.
	*****************************************************************************************/
	function getSiguienteOrden()
	{
		$aux			= $this->conn;
	
		$sql  = "SELECT MAX(orden) AS Max FROM wi_funcionalidades ";
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
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getSiguienteOrden][FuncionalidadesDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}
		return $newId;
	}

	/*************************************************************************
	* Lista en la base de datos recogiendo la información
	* recibida por la entidad, Este metodo se utiliza para búsquedas
	* de uno o varios registros.
	* @param entidad Entidad con la información de la Búsqueda
	* @return Vector Lista recuperada
	*************************************************************************/
	function getListaOrden($cEntidad)
	{
		$aux			= $this->conn;
		
		$sql="";
		$and = false;
		$sql.="SELECT * FROM wi_funcionalidades ";
		if ($cEntidad->getOrden() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="orden > " . $cEntidad->getOrden();
		}
		$sql .=" ORDER BY orden";
		$lista = $aux->Execute($sql);
		
		if ($lista === false){
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getListaOrden][FuncionalidadesDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}else{
			while (!$lista->EOF)
			{
				$sql = "UPDATE wi_funcionalidades SET ";
				$sql .= "orden=" . $aux->qstr($lista->fields['orden']+1, false) . " ";
				$sql .= " WHERE ";
				$sql .="idFuncionalidad=" . $aux->qstr($lista->fields['idFuncionalidad'], false) . " ";
				if($aux->Execute($sql) === false){
        			echo(constant("ERR"));
        			$this->msg_Error	= array();
        			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getListaOrden{While}][FuncionalidadesDB]";
        			$this->msg_Error[]	= $sTypeError;
        			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
        			exit;
				}
				$lista->MoveNext();
			}
		}
		return true;
	}

	/*************************************************************************
	* Lista en la base de datos recogiendo la información
	* recibida por la entidad, Este metodo se utiliza para búsquedas
	* de uno o varios registros.
	* @param entidad Entidad con la información de la Búsqueda
	* @return Vector Lista recuperada
	*************************************************************************/
	function getListaOrdenDel($cEntidad)
	{
		$aux			= $this->conn;
		
		$sql="";
		$and = false;
		$sql.="SELECT * FROM wi_funcionalidades ";
		if ($cEntidad->getOrden() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="orden > " . $cEntidad->getOrden();
		}
		$sql .=" ORDER BY orden";
		$lista = $aux->Execute($sql);
		
		if ($lista === false){
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getListaOrdenDel][FuncionalidadesDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}else{
			while (!$lista->EOF)
			{
				$sql = "UPDATE wi_funcionalidades SET ";
				$sql .= "orden=" . $aux->qstr($lista->fields['orden']-1, false) . " ";
				$sql .= " WHERE ";
				$sql .="idFuncionalidad=" . $aux->qstr($lista->fields['idFuncionalidad'], false) . " ";
				if($aux->Execute($sql) === false){
        			echo(constant("ERR"));
        			$this->msg_Error	= array();
        			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getListaOrdenDel{While}][FuncionalidadesDB]";
        			$this->msg_Error[]	= $sTypeError;
        			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
        			exit;
				}
				$lista->MoveNext();
			}
		}
		return true;
	}
	
	/*************************************************************************
	* Lista y reordena la base de datos desde una posicion dada
	* recogiendo la información recibida por la entidad
	* @param entidad Entidad con la información de la Búsqueda
	* @return Vector Lista recuperada
	*************************************************************************/
	function getListaRenumera($cEntidad)
	{
		$aux			= $this->conn;
		$sql="";
		$and = false;
		$sql.="SELECT * FROM wi_funcionalidades ";
		if ($cEntidad->getOrden() == 0){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .=" orden > " . $cEntidad->getOrden();
		}
		$sql .=" ORDER BY orden";

		$lista = $aux->Execute($sql);
		
		if ($lista === false){
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getListaRenumera][FuncionalidadesDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}else{
			$i=1; //iniciamos contador = orden
			while (!$lista->EOF)
			{
				$sql = "UPDATE wi_funcionalidades SET ";
				$sql .= "orden = " . $i . " ";
				$sql .= " WHERE ";
				$sql .="idFuncionalidad=" . $aux->qstr($lista->fields['idFuncionalidad'], false) . " ";
				if($aux->Execute($sql) === false){
        			echo(constant("ERR"));
        			$this->msg_Error	= array();
        			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getListaRenumera{while}][FuncionalidadesDB]";
        			$this->msg_Error[]	= $sTypeError;
        			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
        			exit;
				}
				$i++;
				$lista->MoveNext();
			}
		}
		return true;
	}// fin getListaReordena
	
	
	/**
	* Devuelve los idMenus de los hijos de un Menú.
	* @param $sSplit	Cadena de menús afectados
    	**/
	function getHijos($id)
	{
		$aux = $this->conn;
		$sSplit = '';
		$sql ="SELECT idFuncionalidad FROM wi_funcionalidades WHERE ";
		$sql .="idPadre=" . $aux->qstr($id, false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$sSplit.= $arr['idFuncionalidad'] . "," . $this->getHijos($arr['idFuncionalidad']);
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getHijos][FuncionalidadesDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}
		return $sSplit;
		
	}

	/**
	* Devuelve los idMenus de los hijos inmediatos.
	* @param $sSplit	Cadena de menús afectados
    	**/
	function getHijo($id)
	{
		$aux = $this->conn;
		$sSplit = '';
		$sql ="SELECT idFuncionalidad FROM wi_funcionalidades WHERE ";
		$sql .="idPadre=" . $aux->qstr($id, false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$sSplit.= $arr['idFuncionalidad'] . ",";
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getHijo][FuncionalidadesDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}
		return $sSplit;
		
	}



	/*************************************************************************
	* Lista en la base de datos recogiendo la información
	* recibida por la entidad, Este metodo se utiliza para búsquedas
	* de uno o varios registros.
	* @param entidad Entidad con la información de la Búsqueda
	* @return Vector Lista recuperada
	*************************************************************************/
	function readListaAcceso($cEntidad)
	{
		$aux			= $this->conn;
		
		$sql="";
		$and = false;
		$sql.="SELECT * FROM wi_funcionalidades ";
		$sql .= $this->getSQLWhere($and);
		$and = true;
		$sql .="idFuncionalidad IN (" . $cEntidad->getIdFuncionalidad() . ")";
		$sql .=" ORDER BY orden";
		
		return $sql;
	}

	/*************************************************************************
	* Lista en la base de datos recogiendo la información
	* recibida por la entidad, Este metodo se utiliza para búsquedas
	* de uno o varios registros.
	* @param entidad Entidad con la información de la Búsqueda
	* @return Vector Lista recuperada
	*************************************************************************/
	function getMenus($sSQL)
	{
		$aux			= $this->conn;
		$sMenus = "";
		require_once(constant("DIR_WS_COM") . "Combo.php");
		$comboFUNCIONALIDADES	= new	Combo($aux,"IdFuncionalidad","IdFuncionalidad","nombre","Descripcion","wi_funcionalidades","","","","","orden");
		$lista = $aux->Execute($sSQL);
		$i=0;
		$iBlock=0;
		$bCerrarDiv = false;
		while (!$lista->EOF)
		{
			if (empty($lista->fields['modoDefecto'])){
				$sModo = constant("MNT_BUSCAR");
			}else{
				$sModo = constant($lista->fields['modoDefecto']);
			}
			if ($lista->fields['indentacion'] == 0)
			{
				if ($i > 0 && $bCerrarDiv){
					$sMenus .= '</div>';
					$bCerrarDiv = false;
					$iBlock++;
				}
				if (empty($lista->fields['url'])){
					$sUrl = "bienvenida.php";
				}else{
					$sUrl = $lista->fields['url'];
				}
				$sMenus .= '
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td><img src="graf/sp.gif" width="1" style="height:5px" border="0" alt="" /></td>
					    </tr>
					</table>
					<table cellspacing="0" width="100%" cellpadding="0" border="0">
					';
					if (empty($lista->fields['popUp'])){
						$sMenus .= "<tr onmouseover=\"javascript:overTR(this,'White');cambia(" . $i . ",1);\" onmouseout =\"javascript:outTR(this,'" . $lista->fields['bgColor'] . "');cambia(" . $i . ",0)\" bgcolor=\"" . $lista->fields['bgColor'] . "\">";
					}else{
						$sMenus .= '
							<tr onclick="javascript:expandingWindow(\'' . $sUrl . '\');" onmouseover="javascript:overTR(this,\'White\');cambia(' . $i . ',1);" onmouseout ="javascript:outTR(this,\'' . $lista->fields['bgColor'] . '\');cambia(' . $i . ',0)" bgcolor="' . $lista->fields['bgColor'] . '">
							';
					}
					$sMenus .= '
							<td ';
					if (!empty($lista->fields['bgFile'])){
						$sMenus .= ' style="background:url(' . constant("HTTP_SERVER") . $lista->fields['bgFile'] . ') no-repeat;" ';
					}
				if (!empty($lista->fields['bgColor']))
				{
					$sMenus .= ' style="background-color:' . $lista->fields['bgColor'] . ';"';
				}
				$sMenus .= ' title="' . $lista->fields['descripcion'] . '">
								<table cellspacing="0" cellpadding="0" border="0" width="100%">
									<tr>
										<td valign="top" onclick="javascript:cambia(\'' . $i . '\',2);block(\'' . $iBlock . '\');setTitulo(\'' . addslashes($lista->fields['nombre']) . '\');enviarMenu(\'' . $sUrl . '\',\'' . $sModo . '\');"><img src="graf/sp.gif" width="10" style="height:10px" name="a' . $i . '" border="0" alt="" /></td>
										';
										if (empty($lista->fields['popUp']))
										{
				$sMenus .= '			<td onclick="javascript:cambia(\'' . $i . '\',2);block(\'' . $iBlock . '\');setTitulo(\'' . addslashes($lista->fields['nombre']) . '\');enviarMenu(\'' . $sUrl . '\',\'' . $sModo . '\');" onmouseout="window.status=\'\';return true" onmouseover="window.status=\'' . addslashes($lista->fields['nombre']) . '\';return true" class="negrob" width="100%">' . $lista->fields['nombre'] . '</td>
										<td onclick="javascript:cambia(\'' . $i . '\',2);block(\'' . $iBlock . '\');setTitulo(\'' . addslashes($lista->fields['nombre']) . '\');enviarMenu(\'' . $sUrl . '\',\'' . $sModo . '\');"><img src="graf/sp.gif" width="9" style="height:9px" border="0" alt="" /></td>';
										}else{
				$sMenus .= '			<td onmouseout="window.status=\'\';return true" onmouseover="window.status=\'' . addslashes($lista->fields['nombre']) . '\';return true" class="negrob" width="100%">' . $lista->fields['nombre'] . '</td>
										<td ><img src="graf/sp.gif" width="9" style="height:9px" border="0" alt="" /></td>';
										}
				$aIconosMenu = explode(',', $lista->fields['iconosMenu']);
				$sCual = '';
				if (in_array("MNT_NUEVO", $aIconosMenu)){
					$sCual .= '1';
				}
				if (in_array("MNT_BUSCAR", $aIconosMenu)){
					$sCual .= '2';
				}
				switch ($sCual)
				{
					case '1':
						$sMenus .= '<td colspan="2" onclick="javascript:cambia(\'' . $i . '\',2);block(\'' . $iBlock . '\');setTitulo(\'' . addslashes($lista->fields['nombre']) . '\');enviarMenu(\'' . $sUrl . '\',\'' . constant("MNT_NUEVO") . '\');" onmouseout="window.status=\'\';return true" onmouseover="window.status=\'' . constant("STR_NUEVO") . ' >>> ' . addslashes($lista->fields['nombre']) . '\';return true"><img src="graf/mas.gif" width="9" hspace="3" style="height:9px" border="0" alt="' . constant("STR_NUEVO") . '" /></td>';
						break;
					case '12':
						$sMenus .= '<td onclick="javascript:cambia(\'' . $i . '\',2);block(\'' . $iBlock . '\');setTitulo(\'' . addslashes($lista->fields['nombre']) . '\');enviarMenu(\'' . $sUrl . '\',\'' . constant("MNT_NUEVO") . '\');" onmouseout="window.status=\'\';return true" onmouseover="window.status=\'' . constant("STR_NUEVO") . ' >>> ' . addslashes($lista->fields['nombre']) . '\';return true"><img src="graf/mas.gif" width="9" hspace="3" style="height:9px" border="0" alt="' . constant("STR_NUEVO") . '" /></td>';
						$sMenus .= '<td onclick="javascript:cambia(\'' . $i . '\',2);block(\'' . $iBlock . '\');setTitulo(\'' . addslashes($lista->fields['nombre']) . '\');enviarMenu(\'' . $sUrl . '\',\'' . constant("MNT_INICIO") . '\');" onmouseout="window.status=\'\';return true" onmouseover="window.status=\'' . constant("STR_BUSCADOR") . ' >>> ' . addslashes($lista->fields['nombre']) . '\';return true"><img src="graf/buscar.gif" width="9" hspace="3" style="height:9px" border="0" alt="' . constant("STR_BUSCAR") . '" /></td>';
						break;
					case '2':
						$sMenus .= '<td onclick="javascript:cambia(\'' . $i . '\',2);block(\'' . $iBlock . '\');setTitulo(\'' . addslashes($lista->fields['nombre']) . '\');enviarMenu(\'' . $sUrl . '\',\'' . constant("MNT_INICIO") . '\');" onmouseout="window.status=\'\';return true" onmouseover="window.status=\'' . constant("STR_BUSCADOR") . ' >>> ' . addslashes($lista->fields['nombre']) . '\';return true"><img src="graf/buscar.gif" width="9" hspace="3" style="height:9px" border="0" alt="' . constant("STR_BUSCAR") . '" /></td>';
						break;
				}
				$sMenus .= '		</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td><img src="graf/sp.gif" width="1" style="height:5px" border="0" alt="" /></td>
						</tr>
					</table>
				<div id="block' . $iBlock . '" style="display:none;">
				';
				$bCerrarDiv = true;
			}else{
				if (empty($lista->fields['bgColor'])){
					$bgColor="#FFFFFF";
				}else{
					$bgColor=$lista->fields['bgColor'];
				}
				if (empty($lista->fields['url'])){
					$sUrl = "bienvenida.php";
				}else{
					$sUrl = $lista->fields['url'];
				}
				$sMenus .= '
					<table cellspacing="1" width="100%" cellpadding="1" border="0">';
				$sPopUp = 'onclick="javascript:cambia(' . $i . ',2);"';
				if (!empty($lista->fields['popUp'])){
					$sPopUp = '';
				}
				$sMenus .= '
						<tr onmouseover="javascript:overTR(this,\'#EFEFEF\');cambia(' . $i . ',1);" onmouseout ="javascript:outTR(this,\'\');cambia(' . $i . ',0)">
							<td ' . $sPopUp . ' valign="top" style="background:transparent;"><img src="graf/sp.gif" width="10" style="height:10px" name="a' . $i . '" border="0" alt="" /></td>
							';
				if (empty($lista->fields['popUp'])){
					$sMenus .= '
							<td class="submenu" width="100%" bgcolor="' . $bgColor . '" onclick="javascript:cambia(' . $i . ',2);setTitulo(\'' . addslashes($comboFUNCIONALIDADES->getDescripcionCombo($lista->fields['idPadre']) . ' - ' . $lista->fields['nombre']) . '\');enviarMenu(\'' . $sUrl . '\',\'' . $sModo . '\',\'' . $lista->fields['bgColor'] . '\');" onmouseout="window.status=\'\';return true" onmouseover="window.status=\'' . addslashes($lista->fields['nombre']) . '\';return true" title="' . $lista->fields['descripcion'] . '"><img src="graf/sp.gif" width="' . 5*$lista->fields['indentacion'] . '" style="height:5px" border="0" alt="" />' . $lista->fields['nombre'] . '</td>
							<td onclick="javascript:cambia(' . $i . ',2);enviarMenu(\'' . $sUrl . '\',\'' . $sModo . '\');"><img src="graf/sp.gif" width="1" style="height:1.5px" border="0" alt="" /></td>';
					$aIconosMenu = explode(',', $lista->fields['iconosMenu']);
					
					$sCual = '';
					if (in_array("MNT_NUEVO", $aIconosMenu)){
						$sCual .= '1';
					}
					if (in_array("MNT_BUSCAR", $aIconosMenu)){
						$sCual .= '2';
					}
					switch ($sCual)
					{
						case '1':
							$sMenus .= '<td colspan="2" onclick="javascript:cambia(' . $i . ',2);enviarMenu(\'' . $sUrl . '\',\'' . constant("MNT_NUEVO") . '\');" onmouseout="window.status=\'\';return true" onmouseover="window.status=\'' . constant("STR_NUEVO") . ' >>> ' . addslashes($lista->fields['nombre']) . '\';return true"><img src="graf/mas.gif" width="9" hspace="1" style="height:9px" border="0" alt="' . constant("STR_NUEVO") . '" /></td>';
							break;
						case '12':
							$sMenus .= '<td onclick="javascript:cambia(' . $i . ',2);enviarMenu(\'' . $sUrl . '\',\'' . constant("MNT_NUEVO") . '\');" onmouseout="window.status=\'\';return true" onmouseover="window.status=\'' . constant("STR_NUEVO") . ' >>> ' . addslashes($lista->fields['nombre']) . '\';return true"><img src="graf/mas.gif" width="9" hspace="1" style="height:9px" border="0" alt="' . constant("STR_NUEVO") . '" /></td>';
							$sMenus .= '<td onclick="javascript:cambia(' . $i . ',2);enviarMenu(\'' . $sUrl . '\',\'' . constant("MNT_INICIO") . '\');" onmouseout="window.status=\'\';return true" onmouseover="window.status=\'' . constant("STR_BUSCADOR") . ' >>> ' . addslashes($lista->fields['nombre']) . '\';return true"><img src="graf/buscar.gif" width="9" hspace="1" style="height:9px" border="0" alt="' . constant("STR_BUSCAR") . '" /></td>';
							break;
						case '2':
							$sMenus .= '<td onclick="javascript:cambia(' . $i . ',2);enviarMenu(\'' . $sUrl . '\',\'' . constant("MNT_INICIO") . '\');" onmouseout="window.status=\'\';return true" onmouseover="window.status=\'' . constant("STR_BUSCADOR") . ' >>> ' . addslashes($lista->fields['nombre']) . '\';return true"><img src="graf/buscar.gif" width="9" hspace="1" style="height:9px" border="0" alt="' . constant("STR_BUSCAR") . '" /></td>';
							break;
					}
				}else{
					$sMenus .= '
							<td class="submenu" width="100%" bgcolor="' . $bgColor . '" onclick="javascript:expandingWindow(\'' . $sUrl . '\');"><img src="graf/sp.gif" width="' . 5*$lista->fields['indentacion'] . '" style="height:5px" border="0" alt="" /><a href="#" onmouseout="window.status=\'\';return true" onmouseover="window.status=\'' . addslashes($lista->fields['nombre']) . '\';return true" title="' . $lista->fields['descripcion'] . '">' . $lista->fields['nombre'] . '</a></td>
							';
				}
				$sMenus .= '
						</tr>
					</table>
				';
			}
			$i++;
			$lista->MoveNext();
		} 
		if ($bCerrarDiv){
			$sMenus .= '</div>';
		}
		return $sMenus;
	}

}//Fin de la Clase FuncionalidadesDB
?>