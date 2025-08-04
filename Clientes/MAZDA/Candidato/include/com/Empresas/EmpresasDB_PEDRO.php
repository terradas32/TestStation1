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
	function EmpresasDB(&$conn)
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

		//Miramos si ya esiste una empresa con ese login
		$iCont = 0;
		$sql  = "SELECT COUNT(idEmpresa) AS Max FROM empresas ";
		$sql .= "WHERE ";
		$sql .= "UPPER(usuario)=" . $aux->qstr(strtoupper($cEntidad->getUsuario()), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$iCont = $arr['Max'];
			}
		}
		if ($iCont > 0 ){
			//Existe un usuario Empresa con ese Login
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error Usuario Empresa Existe Login [" . constant("MNT_ALTA") . "][EmpresasDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			return 0;
		}

		$newId			= $this->getSiguienteId($cEntidad);

		require_once(constant("DIR_WS_COM") . "Upload.php");
		$img0 = new Upload("fPathLogo");
		$img0->image_x = 275;
		$sDirImg="imgEmpresas";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if (!$img0->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img0->get_errores();
 			return 0;
		}else{
			$cEntidad->setPathLogo($img0->getPath_WS());
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
			require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
			$cEntidadPadre	= new Empresas();  // Entidad

			if ($cEntidad->getDespuesDe() != "" )
			{
				//Leemos los datos de la opción (Padre) lo ponemos al mismo nivel
				//que el seleccionado, pero debajo de el.
				$cEntidadPadre->setOrden($cEntidad->getDespuesDe());
				$cEntidadPadre = $this->readEntidadOrden($cEntidadPadre);
				//Se mira cuantos hijos tiene el padre y los hijos de este...
				$sHijos = $this->getHijos($cEntidadPadre->getIdEmpresa());
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
				$cEntidad->setIdPadre($cEntidadPadre->getIdEmpresa());
				$cEntidad->setIndentacion($cEntidadPadre->getIndentacion()+1);
				$this->getListaOrden($cEntidadPadre);
			}
		}

		$sql = "INSERT INTO empresas (";
		$sql .= "idEmpresa" . ",";
		$sql .= "nombre" . ",";
		$sql .= "cif" . ",";
		$sql .= (trim($cEntidad->getUsuario()) != "") ? "usuario" . "," : "";
	  	$sql .= (trim($cEntidad->getPassword()) != "") ? "password" . "," : "";
		$sql .= "mail" . ",";
		$sql .= "mail2" . ",";
		$sql .= "mail3" . ",";
		$sql .= "distribuidor" . ",";
		$sql .= "prepago" . ",";
		$sql .= "ncandidatos" . ",";
		$sql .= "dongles" . ",";
		$sql .= "entidad" . ",";
		$sql .= "oficina" . ",";
		$sql .= "dc" . ",";
		$sql .= "cuenta" . ",";
		$sql .= "idPais" . ",";
		$sql .= "direccion" . ",";
		$sql .= "umbral_aviso" . ",";
		$sql .= "pathLogo" . ",";
		$sql .= "idPadre" . ",";
		$sql .= "orden" . ",";
		$sql .= "indentacion" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr($newId, false) . ",";
		$sql .= $aux->qstr($cEntidad->getNombre(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCif(), false) . ",";
		$sql .= (trim($cEntidad->getUsuario()) != "") ? $aux->qstr($cEntidad->getUsuario(), false) . "," : "";
    	$sql .= (trim($cEntidad->getPassword()) != "") ? $aux->qstr(password_hash($cEntidad->getPassword(), PASSWORD_BCRYPT), false) . "," : "";
		$sql .= $aux->qstr($cEntidad->getMail(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getMail2(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getMail3(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDistribuidor(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPrepago(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getNcandidatos(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDongles(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getEntidad(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getOficina(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDc(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getCuenta(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdPais(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getDireccion(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getUmbral_aviso(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getPathLogo(), false) . ",";
		$sql .= $aux->qstr($cEntidad->getIdPadre(), false) . ",";
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
		}else{
			//Miramos si la empresa es de uso público
			if ($cEntidad->getPublico() != ""){
			}
		}
		require_once(constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfiles.php");
		require_once(constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfilesDB.php");

		$cEmpPerfil = new Empresas_perfiles();
		$cEmpPerfilDB = new Empresas_perfilesDB($aux);

		$cEmpPerfil->setIdEmpresa($newId);
		$cEmpPerfilDB->borrar($cEmpPerfil);

		if($cEntidad->getDistribuidor()=="1") {
			$cEmpPerfil->setIdPerfil("0");
		}else{
			$cEmpPerfil->setIdPerfil("1");
		}

		$cEmpPerfilDB->insertar($cEmpPerfil);
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


/*************************************************************************************************
	modifica y reordena los datos de la entidad, incluyendo a sus hijos.

	$cEntidadVar --> entidad comodin para pasar datos por valor
	$cEntidad --> Datos de la empresa que vamos a modificar, (datos sin modificar).
	$cDentroDe --> Datos de la empresa DENTRODE de la que vamos a depender.
	$cDespuesDe --> Datos de la entidad DESPUESDE de la que vamos a depender.

	$aHijosPosActual --> Array con las ID de los hijos de la posicion actual.
	$iHijosPosActual --> Entero con el numero de hijos da la posiscion actual de la empresa.
	$aHijosPosFinal --> Array con las ID de los hijos de la posicion final.
	$iHijosPosFinal --> Entero con el numero de hijos da la posiscion final a la que vamos a mover la empresa.
**************************************************************************************************/
	function modificar($cEntidad)
	{
		$aux			= $this->conn;
		$i=0;
		require_once(constant("DIR_WS_COM") . "Upload.php");

		$img0 = new Upload("fPathLogo");
		$img0->image_x = 275;
		$sDirImg="imgEmpresas";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		if (!$img0->UploadFile($spath . $sDirImg))
		{
			$this->msg_Error = $img0->get_errores();
			return false;
		}else{
			$cEntidad->setPathLogo($img0->getPath_WS());
		}

		require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
		require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
		// Entidad antes del cambio BBDD
		$cEntidadBack	= new Empresas();  // Entidad
		$cEntidadBack->setIdEmpresa($cEntidad->getIdEmpresa());
		$cEntidadBack	= $this->readEntidad($cEntidadBack);
		//

		//si no hay ningun cambio de orden 'Dentro De' o 'Despues De',
		//se modifican los datos básicos.
		if (($cEntidad->getDentroDe() == $cEntidadBack->getDentroDe()) &&
			($cEntidad->getDespuesDe() == $cEntidadBack->getOrden()) )
		{
			$sql = "UPDATE empresas SET ";
			$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
			$sql .= "cif=" . $aux->qstr($cEntidad->getCif(), false) . ", ";
	  		$sql .= (trim($cEntidad->getUsuario()) != "") ? "usuario=" . $aux->qstr($cEntidad->getUsuario(), false) . "," : "";
//En Candidatos NO se modifica la pass ya que básicamente se desuentan donguel de empresa
//	  		$sql .= (trim($cEntidad->getPassword()) != "") ? "password=" . $aux->qstr(password_hash($cEntidad->getPassword(), PASSWORD_BCRYPT), false) . "," : "";
			$sql .= "mail=" . $aux->qstr($cEntidad->getMail(), false) . ", ";
			$sql .= "mail2=" . $aux->qstr($cEntidad->getMail2(), false) . ", ";
			$sql .= "mail3=" . $aux->qstr($cEntidad->getMail3(), false) . ", ";
			$sql .= "distribuidor=" . $aux->qstr($cEntidad->getDistribuidor(), false) . ", ";
			$sql .= "prepago=" . $aux->qstr($cEntidad->getPrepago(), false) . ", ";
			$sql .= "ncandidatos=" . $aux->qstr($cEntidad->getNcandidatos(), false) . ", ";
			$sql .= "dongles=" . $aux->qstr($cEntidad->getDongles(), false) . ", ";
			$sql .= "entidad=" . $aux->qstr($cEntidad->getEntidad(), false) . ", ";
			$sql .= "oficina=" . $aux->qstr($cEntidad->getOficina(), false) . ", ";
			$sql .= "dc=" . $aux->qstr($cEntidad->getDc(), false) . ", ";
			$sql .= "cuenta=" . $aux->qstr($cEntidad->getCuenta(), false) . ", ";
			$sql .= "idPais=" . $aux->qstr($cEntidad->getIdPais(), false) . ", ";
			$sql .= "direccion=" . $aux->qstr($cEntidad->getDireccion(), false) . ", ";
			$sql .= "umbral_aviso=" . $aux->qstr($cEntidad->getUmbral_aviso(), false) . ", ";
			if ($cEntidad->getPathLogo() != "")
				$sql .= "pathLogo=" . $aux->qstr($cEntidad->getPathLogo(), false) . ", ";
			$sql .= "fecMod=" . $aux->sysTimeStamp . ", ";
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
			}else{
				//Miramos si la empresa es de uso público
				if ($cEntidad->getPublico() != ""){
				}else{
					//Antes era público ahora NO
					if ($cEntidadBack->getPublico() != ""){
					}
				}
			}
		}else{
			//si hay cambio de orden 'Dentro De' o 'Despues De'

			$cDentroDe		= new Empresas();  // Entidad
			$cDespuesDe	= new Empresas();  // Entidad
			$cEntidadVar	= new Empresas();  // Entidad

			$cDentroDe->setOrden($cEntidad->getDentroDe());
			$cDentroDe = $this->readEntidadOrden($cDentroDe);

			$cDespuesDe->setOrden($cEntidad->getDespuesDe());
			$cDespuesDe = $this->readEntidadOrden($cDespuesDe);

			//Recuperamos todos los hijos de la entidad antes de ser modificada.
			$sHijosPosActual = $this->getHijos($cEntidadBack->getIdEmpresa());
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
					if (($cDentroDe->getIdEmpresa() == $aHijosPosActual[$i]
						|| $cDentroDe->getIdEmpresa() == $cEntidadBack->getIdEmpresa()))
					{
						$this->msg_Error	= array();
						$sTypeError	=	date('d/m/Y H:i:s') . " Error [" . constant("MNT_MODIFICAR") . "][EmpresasDB][Accion no permitida, mover el Padre dentro de sus hijos o de si mismo.]";
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
				$sHijosPosFinal = $this->getHijos($cDespuesDe->getIdEmpresa());
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
				for ($i = 0; $i < $iHijosPosActual; $i++)
				{
					// reordenamos la lista desde la posicion a cambiar
					if ($cDespuesDe->getOrden() == ""){	//El primero
						$cEntidadVar->setOrden($cDentroDe->getOrden() + $i);
					}else{
						$cEntidadVar->setOrden($cDespuesDe->getOrden() + $i + $iHijosPosFinal);
					}
					$this->getListaOrden($cEntidadVar);

					//actualiza datos y posiones
					$sql = "UPDATE empresas SET ";
					if ($i == 0){
						if ($cEntidad->getDentroDe() == ""){ //Es el raiz
							$sql .= "idPadre=" . 0 . ", ";
							$sql .= "indentacion=" . 0 . ", ";
						}else{
							$sql .= "idPadre=" . $aux->qstr($cDentroDe->getIdEmpresa(), false) . ", ";
							$sql .= "indentacion=" . $aux->qstr($cDentroDe->getIndentacion()+1, false) . ", ";
						}
						if ($cEntidad->getDespuesDe() == ""){
							$sql .= "orden=" . $aux->qstr($cDentroDe->getOrden() + $i + 1, false) . ", ";
						}else{
							$sql .= "orden=" . $aux->qstr($cDespuesDe->getOrden()+$i+1+$iHijosPosFinal, false) . ", ";
						}
						if ($cEntidad->getPathLogo() != "")
							$sql .= "pathLogo=" . $aux->qstr($cEntidad->getPathLogo(), false) . ", ";
						$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
						$sql .= "cif=" . $aux->qstr($cEntidad->getCif(), false) . ", ";
				  		$sql .= (trim($cEntidad->getUsuario()) != "") ? "usuario=" . $aux->qstr($cEntidad->getUsuario(), false) . "," : "";
				  		$sql .= (trim($cEntidad->getPassword()) != "") ? "password=" . $aux->qstr(password_hash($cEntidad->getPassword(), PASSWORD_BCRYPT), false) . "," : "";
						$sql .= "mail=" . $aux->qstr($cEntidad->getMail(), false) . ", ";
						$sql .= "mail2=" . $aux->qstr($cEntidad->getMail2(), false) . ", ";
						$sql .= "mail3=" . $aux->qstr($cEntidad->getMail3(), false) . ", ";
						$sql .= "distribuidor=" . $aux->qstr($cEntidad->getDistribuidor(), false) . ", ";
						$sql .= "prepago=" . $aux->qstr($cEntidad->getPrepago(), false) . ", ";
						$sql .= "ncandidatos=" . $aux->qstr($cEntidad->getNcandidatos(), false) . ", ";
						$sql .= "dongles=" . $aux->qstr($cEntidad->getDongles(), false) . ", ";
						$sql .= "entidad=" . $aux->qstr($cEntidad->getEntidad(), false) . ", ";
						$sql .= "oficina=" . $aux->qstr($cEntidad->getOficina(), false) . ", ";
						$sql .= "dc=" . $aux->qstr($cEntidad->getDc(), false) . ", ";
						$sql .= "cuenta=" . $aux->qstr($cEntidad->getCuenta(), false) . ", ";
						$sql .= "idPais=" . $aux->qstr($cEntidad->getIdPais(), false) . ", ";
						$sql .= "direccion=" . $aux->qstr($cEntidad->getDireccion(), false) . ", ";
						$sql .= "umbral_aviso=" . $aux->qstr($cEntidad->getUmbral_aviso(), false) . ", ";
						$sql .= "fecMod=" . $aux->sysTimeStamp . ", ";
						$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
						$sql .= " WHERE ";
						$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";

					}else{
						if ($cEntidad->getDespuesDe() == ""){
							$sql .= "orden=" . $aux->qstr($cDentroDe->getOrden()+$i+1, false) . ", ";
						}else{
							$sql .= "orden=" . $aux->qstr($cDespuesDe->getOrden()+$i+1+$iHijosPosFinal, false) . ", ";
						}
						if ($cEntidad->getDentroDe() == ""){ // Raiz
							$cEntidadVar->setIdEmpresa($aHijosPosActual[$i-1]);
							$this->readEntidad($cEntidadVar);
							$sql .= "indentacion=" . ($cEntidadVar->getIndentacion() - $cEntidad->getIndentacion());
						}else{
							$sql .= "indentacion=" . (($cDentroDe->getIndentacion()+1)- $cEntidad->getIndentacion()) . " + indentacion ";
						}
						$sql .= " WHERE ";
						$sql .="idEmpresa=" . $aHijosPosActual[$i-1] . " ";
					}
					$retorno=true;
					if($aux->Execute($sql) === false){
						$retorno=false;
						$this->msg_Error	= array();
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][EmpresasDB]";
						$this->msg_Error[]	= $sTypeError;
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}
//****************************
					if ($i == 0){
						//Miramos si la empresa es de uso público
						if ($cEntidad->getPublico() != ""){
						}else{
							//Antes era público ahora NO
							if ($cEntidadBack->getPublico() != ""){
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
				$sql = "UPDATE empresas SET ";
				if ($cEntidad->getDentroDe() == ""){ //Es el raiz
					$sql .= "idPadre=" . 0 . ", ";
					$sql .= "indentacion=" . 0 . ", ";
				}else{
					$sql .= "idPadre=" . $aux->qstr($cDentroDe->getIdEmpresa(), false) . ", ";
					$sql .= "indentacion=" . $aux->qstr($cDentroDe->getIndentacion()+1, false) . ", ";
				}
				if ($cEntidad->getDespuesDe() == ""){
					$sql .= "orden=" . $aux->qstr($cDentroDe->getOrden() + $i + 1, false) . ", ";
				}else{
					$sql .= "orden=" . $aux->qstr($cDespuesDe->getOrden()+$i+1+$iHijosPosFinal, false) . ", ";
				}
				if ($cEntidad->getPathLogo() != "")
					$sql .= "pathLogo=" . $aux->qstr($cEntidad->getPathLogo(), false) . ", ";
				$sql .= "nombre=" . $aux->qstr($cEntidad->getNombre(), false) . ", ";
				$sql .= "cif=" . $aux->qstr($cEntidad->getCif(), false) . ", ";
	  			$sql .= (trim($cEntidad->getUsuario()) != "") ? "usuario=" . $aux->qstr($cEntidad->getUsuario(), false) . "," : "";
	  			$sql .= (trim($cEntidad->getPassword()) != "") ? "password=" . $aux->qstr(password_hash($cEntidad->getPassword(), PASSWORD_BCRYPT), false) . "," : "";
				$sql .= "mail=" . $aux->qstr($cEntidad->getMail(), false) . ", ";
				$sql .= "mail2=" . $aux->qstr($cEntidad->getMail2(), false) . ", ";
				$sql .= "mail3=" . $aux->qstr($cEntidad->getMail3(), false) . ", ";
				$sql .= "distribuidor=" . $aux->qstr($cEntidad->getDistribuidor(), false) . ", ";
				$sql .= "prepago=" . $aux->qstr($cEntidad->getPrepago(), false) . ", ";
				$sql .= "ncandidatos=" . $aux->qstr($cEntidad->getNcandidatos(), false) . ", ";
				$sql .= "dongles=" . $aux->qstr($cEntidad->getDongles(), false) . ", ";
				$sql .= "entidad=" . $aux->qstr($cEntidad->getEntidad(), false) . ", ";
				$sql .= "oficina=" . $aux->qstr($cEntidad->getOficina(), false) . ", ";
				$sql .= "dc=" . $aux->qstr($cEntidad->getDc(), false) . ", ";
				$sql .= "cuenta=" . $aux->qstr($cEntidad->getCuenta(), false) . ", ";
				$sql .= "idPais=" . $aux->qstr($cEntidad->getIdPais(), false) . ", ";
				$sql .= "direccion=" . $aux->qstr($cEntidad->getDireccion(), false) . ", ";
				$sql .= "umbral_aviso=" . $aux->qstr($cEntidad->getUmbral_aviso(), false) . ", ";
				$sql .= "fecMod=" . $aux->sysTimeStamp . ", ";
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
				}else{
					//Miramos si la empresa es de uso público
					if ($cEntidad->getPublico() != ""){
					}else{
						//Antes era público ahora NO
						if ($cEntidadBack->getPublico() != ""){
						}
					}
				}
			}

			//reordenamos la entidad desde 1 por si hay huecos
			$cEntidadVar->setOrden(0);
			$this->getListaRenumera($cEntidadVar);
		} // fin if cambio de orden

		if($retorno){
			require_once(constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfiles.php");
			require_once(constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfilesDB.php");

			$cEmpPerfil = new Empresas_perfiles();
			$cEmpPerfilDB = new Empresas_perfilesDB($aux);

			$cEmpPerfil->setIdEmpresa($cEntidad->getIdEmpresa());

			$cEmpPerfilDB->borrar($cEmpPerfil);

			if($cEntidad->getDistribuidor()=="1") {
				$cEmpPerfil->setIdPerfil("0");
			}else{
				$cEmpPerfil->setIdPerfil("1");
			}
			$cEmpPerfilDB->insertar($cEmpPerfil);
		}
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
		$sql = "";

		//Antes de hacer nada miramos si la empresa es Pisicologos en ese caso NO dejamos borrar
		if ($cEntidad->getIdEmpresa() == constant("EMPRESA_PE")){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error No se puede Borrar el NODO principal (" . constant("NOMBRE_EMPRESA") . ") [" . constant("MNT_BORRAR") . "][EmpresasDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\tNO se puede borrar" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}

		if ($retorno){
			//Antes de borrar sacamos los datos de la entidad para reordenar
			$cEntidad = $this->readEntidad($cEntidad);

			 //Actualizacion de las dependencias Padre-Hijo
			$sql  ="UPDATE empresas SET idPadre=" . $cEntidad->getIdPadre() . ", indentacion=" . $cEntidad->getIndentacion();
			$sql  .=" WHERE idPadre=" . $cEntidad->getIdEmpresa();
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][EmpresasDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			}
			//Borramos el registro de la Entidad (borrado Físico).
			$sql  ="DELETE FROM empresas ";
			$sql  .="WHERE ";
			if ($cEntidad->getIdEmpresa() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idEmpresa=". $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
			}

			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][EmpresasDB]";
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
		$sql = "SELECT * FROM empresas WHERE ";
		$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setCif($arr['cif']);
					$cEntidad->setUsuario($arr['usuario']);
					$cEntidad->setPassword($arr['password']);
					$cEntidad->setMail($arr['mail']);
					$cEntidad->setMail2($arr['mail2']);
					$cEntidad->setMail3($arr['mail3']);
					$cEntidad->setDistribuidor($arr['distribuidor']);
					$cEntidad->setAvisoLegal($arr['avisoLegal']);
					$cEntidad->setPrepago($arr['prepago']);
					$cEntidad->setNcandidatos($arr['ncandidatos']);
					$cEntidad->setDongles($arr['dongles']);
					$cEntidad->setEntidad($arr['entidad']);
					$cEntidad->setOficina($arr['oficina']);
					$cEntidad->setDc($arr['dc']);
					$cEntidad->setCuenta($arr['cuenta']);
					$cEntidad->setIdPais($arr['idPais']);
					$cEntidad->setDireccion($arr['direccion']);
					$cEntidad->setUmbral_aviso($arr['umbral_aviso']);
					$cEntidad->setIdsPruebas($arr['idsPruebas']);

					$cEntidad->setUltimoLogin($arr['ultimoLogin']);
					$cEntidad->setToken($arr['token']);
					$cEntidad->setUltimaAcc($arr['ultimaAcc']);
					$cEntidad->setIdPadre($arr['idPadre']);
					$cEntidad->setOrden($arr['orden']);
					$cEntidad->setDespuesDe($arr['orden']);	//	MUY IMPORTANTE
					$cEntidad->setIndentacion($arr['indentacion']);
					$cEntidad->setPathLogo($arr['pathLogo']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
			require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
			$cEntidadPadre	= new Empresas();  // Entidad

//			$cEntidadPadre = $cEntidad;
			$cEntidadPadre->setIdPadre($cEntidad->getIdPadre());
			$cEntidadPadre = $this->readEntidadPadre($cEntidadPadre);
			$cEntidad->setDentroDe($cEntidadPadre->getOrden());
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

function readEntidadSinPass($cEntidad)
	{
		$aux			= $this->conn;
		$sql = "SELECT * FROM empresas WHERE ";
		$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setCif($arr['cif']);
					$cEntidad->setUsuario($arr['usuario']);
					//$cEntidad->setPassword($arr['password']);
					$cEntidad->setMail($arr['mail']);
					$cEntidad->setMail2($arr['mail2']);
					$cEntidad->setMail3($arr['mail3']);
					$cEntidad->setDistribuidor($arr['distribuidor']);
					$cEntidad->setAvisoLegal($arr['avisoLegal']);
					$cEntidad->setPrepago($arr['prepago']);
					$cEntidad->setNcandidatos($arr['ncandidatos']);
					$cEntidad->setDongles($arr['dongles']);
					$cEntidad->setEntidad($arr['entidad']);
					$cEntidad->setOficina($arr['oficina']);
					$cEntidad->setDc($arr['dc']);
					$cEntidad->setCuenta($arr['cuenta']);
					$cEntidad->setIdPais($arr['idPais']);
					$cEntidad->setDireccion($arr['direccion']);
					$cEntidad->setUmbral_aviso($arr['umbral_aviso']);
					$cEntidad->setIdsPruebas($arr['idsPruebas']);

					$cEntidad->setUltimoLogin($arr['ultimoLogin']);
					$cEntidad->setToken($arr['token']);
					$cEntidad->setUltimaAcc($arr['ultimaAcc']);

					$cEntidad->setIdPadre($arr['idPadre']);
					$cEntidad->setDespuesDe($arr['orden']);
					$cEntidad->setOrden($arr['orden']);
					$cEntidad->setIndentacion($arr['indentacion']);
					$cEntidad->setPathLogo($arr['pathLogo']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
			require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
			$cEntidadPadre	= new Empresas();  // Entidad

//			$cEntidadPadre = $cEntidad;
			$cEntidadPadre->setIdPadre($cEntidad->getIdPadre());
			$cEntidadPadre = $this->readEntidadPadre($cEntidadPadre);
			$cEntidad->setDentroDe($cEntidadPadre->getOrden());
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
		$sql = "SELECT * FROM empresas WHERE ";
		$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdPadre(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			$cEntidad->setOrden("");
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setCif($arr['cif']);
					$cEntidad->setUsuario($arr['usuario']);
					$cEntidad->setPassword($arr['password']);
					$cEntidad->setMail($arr['mail']);
					$cEntidad->setMail2($arr['mail2']);
					$cEntidad->setMail3($arr['mail3']);
					$cEntidad->setDistribuidor($arr['distribuidor']);
					$cEntidad->setAvisoLegal($arr['avisoLegal']);
					$cEntidad->setPrepago($arr['prepago']);
					$cEntidad->setNcandidatos($arr['ncandidatos']);
					$cEntidad->setDongles($arr['dongles']);
					$cEntidad->setEntidad($arr['entidad']);
					$cEntidad->setOficina($arr['oficina']);
					$cEntidad->setDc($arr['dc']);
					$cEntidad->setCuenta($arr['cuenta']);
					$cEntidad->setIdPais($arr['idPais']);
					$cEntidad->setDireccion($arr['direccion']);
					$cEntidad->setUmbral_aviso($arr['umbral_aviso']);
					$cEntidad->setIdsPruebas($arr['idsPruebas']);
					$cEntidad->setUltimoLogin($arr['ultimoLogin']);
					$cEntidad->setToken($arr['token']);
					$cEntidad->setUltimaAcc($arr['ultimaAcc']);
					$cEntidad->setIdPadre($arr['idPadre']);
					$cEntidad->setOrden($arr['orden']);
					$cEntidad->setIndentacion($arr['indentacion']);
					$cEntidad->setPathLogo($arr['pathLogo']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidadPadre][EmpresasDB]";
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
			$sql .="idEmpresa IN(" . $cEntidad->getIdEmpresa() . ")";
		}
		if ($cEntidad->getNombre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nombre) LIKE UPPER('%" . $cEntidad->getNombre() . "%')";
		}

		if ($cEntidad->getCif() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(cif) LIKE UPPER('%" . $cEntidad->getCif() . "%')";
		}
		if ($cEntidad->getUsuario() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(usuario) LIKE UPPER('%" . $cEntidad->getUsuario() . "%')";
		}
		if ($cEntidad->getPassword() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(password) LIKE UPPER('%" . $cEntidad->getPassword() . "%')";
		}
		if ($cEntidad->getPathLogo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(pathLogo) LIKE UPPER('%" . $cEntidad->getPathLogo() . "%')";
		}
		if ($cEntidad->getMail() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(mail) LIKE UPPER('%" . $cEntidad->getMail() . "%')";
		}
		if ($cEntidad->getMail2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(mail2) LIKE UPPER('%" . $cEntidad->getMail2() . "%')";
		}
		if ($cEntidad->getMail3() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(mail3) LIKE UPPER('%" . $cEntidad->getMail3() . "%')";
		}
		if ($cEntidad->getDistribuidor() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(distribuidor) LIKE UPPER('%" . $cEntidad->getDistribuidor() . "%')";
		}
		if ($cEntidad->getPrepago() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(prepago) LIKE UPPER('%" . $cEntidad->getPrepago() . "%')";
		}
		if ($cEntidad->getNcandidatos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(ncandidatos) LIKE UPPER('%" . $cEntidad->getNcandidatos() . "%')";
		}
		if ($cEntidad->getDongles() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(dongles) LIKE UPPER('%" . $cEntidad->getDongles() . "%')";
		}
		if ($cEntidad->getEntidad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(entidad) LIKE UPPER('%" . $cEntidad->getEntidad() . "%')";
		}
		if ($cEntidad->getOficina() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(oficina) LIKE UPPER('%" . $cEntidad->getOficina() . "%')";
		}
		if ($cEntidad->getDc() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(dc) LIKE UPPER('%" . $cEntidad->getDc() . "%')";
		}
		if ($cEntidad->getCuenta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(cuenta) LIKE UPPER('%" . $cEntidad->getCuenta() . "%')";
		}
		if ($cEntidad->getIdPais() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(idPais) LIKE UPPER('%" . $cEntidad->getIdPais() . "%')";
		}
		if ($cEntidad->getDireccion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(direccion) LIKE UPPER('%" . $cEntidad->getDireccion() . "%')";
		}
		if ($cEntidad->getUmbral_aviso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(umbral_aviso) LIKE UPPER('%" . $cEntidad->getUmbral_aviso() . "%')";
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
			$sql .="UPPER(token) LIKE UPPER('%" . $cEntidad->getToken() . "%')";
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

		if ($cEntidad->getIdPadre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPadre=" . $aux->qstr($cEntidad->getIdPadre(), false);
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
		if ($cEntidad->getPathLogo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(pathLogo) LIKE UPPER('%" . $cEntidad->getPathLogo() . "%')";
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
		$sql.="SELECT * FROM empresas ";
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false);
		}
		if ($cEntidad->getNombre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(nombre) LIKE UPPER('%" . $cEntidad->getNombre() . "%')";
		}

		if ($cEntidad->getCif() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(cif) LIKE UPPER('%" . $cEntidad->getCif() . "%')";
		}
		if ($cEntidad->getUsuario() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(usuario) LIKE UPPER('%" . $cEntidad->getUsuario() . "%')";
		}
		if ($cEntidad->getPassword() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(password) LIKE UPPER('%" . $cEntidad->getPassword() . "%')";
		}
		if ($cEntidad->getMail() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(mail) LIKE UPPER('%" . $cEntidad->getMail() . "%')";
		}
		if ($cEntidad->getMail2() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(mail2) LIKE UPPER('%" . $cEntidad->getMail2() . "%')";
		}
		if ($cEntidad->getMail3() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(mail3) LIKE UPPER('%" . $cEntidad->getMail3() . "%')";
		}
		if ($cEntidad->getDistribuidor() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(distribuidor) LIKE UPPER('%" . $cEntidad->getDistribuidor() . "%')";
		}
		if ($cEntidad->getAvisoLegal() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(avisoLegal) LIKE UPPER('%" . $cEntidad->getAvisoLegal() . "%')";
		}

		if ($cEntidad->getPrepago() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(prepago) LIKE UPPER('%" . $cEntidad->getPrepago() . "%')";
		}
		if ($cEntidad->getNcandidatos() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(ncandidatos) LIKE UPPER('%" . $cEntidad->getNcandidatos() . "%')";
		}
		if ($cEntidad->getDongles() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(dongles) LIKE UPPER('%" . $cEntidad->getDongles() . "%')";
		}
		if ($cEntidad->getEntidad() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(entidad) LIKE UPPER('%" . $cEntidad->getEntidad() . "%')";
		}
		if ($cEntidad->getOficina() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(oficina) LIKE UPPER('%" . $cEntidad->getOficina() . "%')";
		}
		if ($cEntidad->getDc() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(dc) LIKE UPPER('%" . $cEntidad->getDc() . "%')";
		}
		if ($cEntidad->getCuenta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(cuenta) LIKE UPPER('%" . $cEntidad->getCuenta() . "%')";
		}
		if ($cEntidad->getIdPais() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(idPais) LIKE UPPER('%" . $cEntidad->getIdPais() . "%')";
		}
		if ($cEntidad->getDireccion() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(direccion) LIKE UPPER('%" . $cEntidad->getDireccion() . "%')";
		}
		if ($cEntidad->getUmbral_aviso() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(umbral_aviso) LIKE UPPER('%" . $cEntidad->getUmbral_aviso() . "%')";
		}
		if ($cEntidad->getIdsPruebas() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(idsPruebas) LIKE UPPER('%" . $cEntidad->getIdsPruebas() . "%')";
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
			$sql .="UPPER(token) LIKE UPPER('%" . $cEntidad->getToken() . "%')";
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

		if ($cEntidad->getIdPadre() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idPadre=" . $aux->qstr($cEntidad->getIdPadre(), false);
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
		if ($cEntidad->getPathLogo() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(pathLogo) LIKE UPPER('%" . $cEntidad->getPathLogo() . "%')";
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
		$sql = "UPDATE empresas SET  ";
		if (strtolower($cEntidad->getPathLogo()) == "on"){
			$cEntidad->setPathLogo('');
			$sql .= "pathLogo=" . $aux->qstr($cEntidad->getPathLogo(), false) . ", ";
		}
		$sql = substr($sql,0,strlen($sql)-2);
		$sql .= " WHERE ";
		$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
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

		$sql = "SELECT * FROM empresas WHERE ";
		$sql  .="orden=" . $aux->qstr($cEntidad->getOrden(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setCif($arr['cif']);
					$cEntidad->setUsuario($arr['usuario']);
					$cEntidad->setPassword($arr['password']);
					$cEntidad->setMail($arr['mail']);
					$cEntidad->setMail2($arr['mail2']);
					$cEntidad->setMail3($arr['mail3']);
					$cEntidad->setDistribuidor($arr['distribuidor']);
					$cEntidad->setAvisoLegal($arr['avisoLegal']);
					$cEntidad->setPrepago($arr['prepago']);
					$cEntidad->setNcandidatos($arr['ncandidatos']);
					$cEntidad->setDongles($arr['dongles']);
					$cEntidad->setEntidad($arr['entidad']);
					$cEntidad->setOficina($arr['oficina']);
					$cEntidad->setDc($arr['dc']);
					$cEntidad->setCuenta($arr['cuenta']);
					$cEntidad->setIdPais($arr['idPais']);
					$cEntidad->setDireccion($arr['direccion']);
					$cEntidad->setUmbral_aviso($arr['umbral_aviso']);
					$cEntidad->setIdsPruebas($arr['idsPruebas']);

					$cEntidad->setUltimoLogin($arr['ultimoLogin']);
					$cEntidad->setToken($arr['token']);
					$cEntidad->setUltimaAcc($arr['ultimaAcc']);
					$cEntidad->setIdPadre($arr['idPadre']);
					$cEntidad->setOrden($arr['orden']);
					$cEntidad->setIndentacion($arr['indentacion']);
					$cEntidad->setPathLogo($arr['pathLogo']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidadOrden][EmpresasDB]";
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

		$sql  = "SELECT MAX(orden) AS Max FROM empresas ";
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
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getSiguienteOrden][EmpresasDB]";
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
		$sql.="SELECT * FROM empresas ";
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
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getListaOrden][EmpresasDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}else{
			while (!$lista->EOF)
			{
				$sql = "UPDATE empresas SET ";
				$sql .= "orden=" . $aux->qstr($lista->fields['orden']+1, false) . " ";
				$sql .= " WHERE ";
				$sql .="idEmpresa=" . $aux->qstr($lista->fields['idEmpresa'], false) . " ";
				if($aux->Execute($sql) === false){
        			echo(constant("ERR"));
        			$this->msg_Error	= array();
        			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getListaOrden{While}][EmpresasDB]";
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
		$sql.="SELECT * FROM empresas ";
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
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getListaOrdenDel][EmpresasDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}else{
			while (!$lista->EOF)
			{
				$sql = "UPDATE empresas SET ";
				$sql .= "orden=" . $aux->qstr($lista->fields['orden']-1, false) . " ";
				$sql .= " WHERE ";
				$sql .="idEmpresa=" . $aux->qstr($lista->fields['idEmpresa'], false) . " ";
				if($aux->Execute($sql) === false){
        			echo(constant("ERR"));
        			$this->msg_Error	= array();
        			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getListaOrdenDel{While}][EmpresasDB]";
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
		$sql.="SELECT * FROM empresas ";
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
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getListaRenumera][EmpresasDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}else{
			$i=1; //iniciamos contador = orden
			while (!$lista->EOF)
			{
				$sql = "UPDATE empresas SET ";
				$sql .= "orden = " . $i . " ";
				$sql .= " WHERE ";
				$sql .="idEmpresa=" . $aux->qstr($lista->fields['idEmpresa'], false) . " ";
				if($aux->Execute($sql) === false){
        			echo(constant("ERR"));
        			$this->msg_Error	= array();
        			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getListaRenumera{while}][EmpresasDB]";
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
		$sql ="SELECT idEmpresa FROM empresas WHERE ";
		$sql .="idPadre=" . $aux->qstr($id, false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$sSplit.= $arr['idEmpresa'] . "," . $this->getHijos($arr['idEmpresa']);
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getHijos][EmpresasDB]";
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
		$sql ="SELECT idEmpresa FROM empresas WHERE ";
		$sql .="idPadre=" . $aux->qstr($id, false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$sSplit.= $arr['idEmpresa'] . ",";
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getHijo][EmpresasDB]";
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
		$sql.="SELECT * FROM empresas ";
		$sql .= $this->getSQLWhere($and);
		$and = true;
		$sql .="idEmpresa IN (" . $cEntidad->getIdEmpresa() . ")";
		$sql .=" ORDER BY orden";

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

		$sql = "SELECT * FROM empresas WHERE ";
		$sql  .="usuario=" . $aux->qstr($cEntidad->getUsuario(), false);// . " AND ";
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
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [Login][EmpresasDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}

		//Antiguo
				$sql = "SELECT * FROM empresas WHERE ";
				$sql  .="usuario=" . $aux->qstr($cEntidad->getUsuario(), false) . " AND ";
				$sql  .="password=" . $aux->qstr(md5($cEntidad->getPassword()), false) . " ";

				$rs = $aux->Execute($sql);
				if ($rs){
					while ($arr = $rs->FetchRow()){
						return $arr;
					}
				}else{
					echo(constant("ERR"));
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [Login][EmpresasDB]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					exit;
				}
		return array();
	}

	function getLogin($cEntidad)
	{
		$aux			= $this->conn;

		$sql = "SELECT * FROM empresas WHERE ";
		$sql  .="usuario=" . $aux->qstr($cEntidad->getUsuario(), false);
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				return $arr;
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [getLogin][EmpresasDB]";
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

		$sql = "UPDATE empresas SET ";
		$sql .= "ultimoLogin=" . $aux->sysTimeStamp . " ";
		$sql .= " WHERE ";
		$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";

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
		$sql = "UPDATE empresas SET ";
		$sql .="token=" . $aux->qstr($cEntidad->getToken(), false) . ", ";
		$sql .="ultimaAcc=" . $aux->sysTimeStamp . " ";
		$sql .=" WHERE ";
		$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "-Token][EmpresasDB]";
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
	 * @return Empresas Informacion recuperada
	 *************************************************************************/
	function usuarioPorToken($cEntidad)
	{

		$aux			= $this->conn;

		$sql = "SELECT * FROM empresas ";
		$sql  .= "WHERE ";
		$sql  .= "token=" . $aux->qstr($cEntidad->getToken(), false) . " ";
//		$sql  .= "AND ";
//		$sql  .= "bajaLog=0";

		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setNombre($arr['nombre']);
					$cEntidad->setCif($arr['cif']);
					$cEntidad->setUsuario($arr['usuario']);
					$cEntidad->setPassword($arr['password']);
					$cEntidad->setMail($arr['mail']);
					$cEntidad->setMail2($arr['mail2']);
					$cEntidad->setMail3($arr['mail3']);
					$cEntidad->setDistribuidor($arr['distribuidor']);
					$cEntidad->setAvisoLegal($arr['avisoLegal']);
					$cEntidad->setPrepago($arr['prepago']);
					$cEntidad->setNcandidatos($arr['ncandidatos']);
					$cEntidad->setDongles($arr['dongles']);
					$cEntidad->setEntidad($arr['entidad']);
					$cEntidad->setOficina($arr['oficina']);
					$cEntidad->setDc($arr['dc']);
					$cEntidad->setCuenta($arr['cuenta']);
					$cEntidad->setIdPais($arr['idPais']);
					$cEntidad->setDireccion($arr['direccion']);
					$cEntidad->setUmbral_aviso($arr['umbral_aviso']);
					$cEntidad->setIdsPruebas($arr['idsPruebas']);

					$cEntidad->setUltimoLogin($arr['ultimoLogin']);
					$cEntidad->setToken($arr['token']);
					$cEntidad->setUltimaAcc($arr['ultimaAcc']);

					$cEntidad->setIdPadre($arr['idPadre']);
					$cEntidad->setDespuesDe($arr['orden']);
					$cEntidad->setOrden($arr['orden']);
					$cEntidad->setIndentacion($arr['indentacion']);
					$cEntidad->setPathLogo($arr['pathLogo']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [usuarioPorToken][EmpresasDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}
		return $cEntidad;

	}

	function isUsuarioActivo($cEntidad)
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
		$sql .="FROM empresas ";
		$sql .="WHERE ";
		$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
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
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [isUsuarioActivo][EmpresasDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}

		if ($iTiempoMinutos <= constant("TIMEOUT_SESION")){
			$bRetorno = $this->ActualizaToken($cEntidad);
		}

		return $bRetorno;

	}
}//Fin de la Clase EmpresasDB
?>
