<?php 
/**
 * Realiza la operaciones de alta, baja, modificación, borrado,
 * consulta de registros y consulta de numero de elementos sobre
 * la tabla sec_perfiles_procesos
 **/
class Sec_perfilesprocesosDB 
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
		
		$iCont;
		$sql = "";

		require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
		require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
		
		$procesos = new Procesos();
		$procesosBD = new ProcesosBD($aux);
		$procesos->setIDPROCESO($cEntidad->getIDPROCESO());
		$procesos = $procesosBD->consultar(procesos);
		
		require_once(constant("DIR_WS_COM") . "Sec_Perfilespuestos/Sec_PerfilespuestosDB.php");
		require_once(constant("DIR_WS_COM") . "Sec_Perfilespuestos/Sec_Perfilespuestos.php");
		
		$perfPuestos =  new Sec_Perfilespuestos();
		$perfPuestosBD = new Sec_PerfilespuestosDB($aux);

		$perfPuestos->setIDPERFILPUESTO($cEntidad->getIDPERFILPUESTO());
		$perfPuestos = $perfPuestosBD->consultar($perfPuestos);
		$iCont = 0;
		

		$sql  = "SELECT COUNT(IDPERFILPROCESO) AS Max FROM sec_perfiles_procesos ";
		$sql .= "WHERE ";
		$sql .="IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO());


		//Ejecucion de la query
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$iCont = $arr['Max'];
			}
		}else{
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [icount][Sec_perfilesprocesosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		}


		if ($iCont > 0 ){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " El perfil ya está asignado al proceso.";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			return 0;
		}
		

		$newId = $this->getSiguienteId($cEntidad);	


		$sql = "INSERT INTO sec_perfiles_procesos (";
		$sql .= "IDPERFILPROCESO" . ",";
		$sql .= "IDPROCESO" . ",";
		$sql .= "DESCPROCESO" . ",";
		$sql .= "IDPERFILPUESTO" . ",";
		$sql .= "DESCPERFILPUESTO" . ",";
		$sql .= "BAJALOG" . ",";
		$sql .= "fecAlta" . ",";
		$sql .= "fecMod" . ",";
		$sql .= "usuAlta" . ",";
		$sql .= "usuMod" . ")";
		$sql .= " VALUES (";
		$sql .= $aux->qstr(newId) . ",";
		$sql .= $aux->qstr($cEntidad->getIDPROCESO()) . ",";
		$sql .= $aux->qstr($procesos->getDESCRIPCION()) . ",";
		$sql .= $aux->qstr($cEntidad->getIDPERFILPUESTO()) . ",";
		$sql .= $aux->qstr($perfPuestos->getNOMBRE()) . ",";
		$sql .= $aux->qstr($cEntidad->getBAJALOG()) . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->sysTimeStamp . ",";
		$sql .= $aux->qstr($cEntidad->getUsuAlta()) . ",";
		$sql .= $aux->qstr($cEntidad->getUsuMod()) . ")";
		
		if($aux->Execute($sql) === false){
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Sec_perfilesprocesosDB]";
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

			$sql  = "SELECT MAX(IDPERFILPROCESO) AS Max FROM sec_perfiles_procesos ";
			$sql  .="";
			log.write("SQL de Ejecucion: " . $sql.toString());

		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][Sec_perfilesprocesosDB]";
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
		$sql = "";
		$aux			= $this->conn;
			
		$sql = "UPDATE sec_perfiles_procesos SET ";
		$sql .= "IDPERFILPROCESO=" . $aux->qstr($cEntidad->getIDPERFILPROCESO()) . ", ";
		$sql .= "IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO()) . ", ";
		$sql .= "DESCPROCESO=" . $aux->qstr($cEntidad->getDESCPROCESO()) . ", ";
		$sql .= "IDPERFILPUESTO=" . $aux->qstr($cEntidad->getIDPERFILPUESTO()) . ", ";
		$sql .= "DESCPERFILPUESTO=" . $aux->qstr($cEntidad->getDESCPERFILPUESTO()) . ", ";
		$sql .= "BAJALOG=" . $aux->qstr($cEntidad->getBAJALOG()) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ",";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod()) . ", ";
		if ($cEntidad->getBAJALOG() == "0"){
			$sql .= "fecBaja=NULL ";
		}else{
			$sql .= "fecBaja=" . $aux->sysTimeStamp . " ";
			//Miramos si hay un c$andidato informado con el perfil
			require_once(constant("DIR_WS_COM") . "Sec_convocatoriascandidatos/Sec_convocatoriascandidatosDB.php");
			require_once(constant("DIR_WS_COM") . "Sec_convocatoriascandidatos/Sec_convocatoriascandidatos.php");
			$cConvocatoriascandidatos = new Sec_convocatoriascandidatos();
			$cConvocatoriascandidatosBD = new Sec_convocatoriascandidatosDB($aux);
			//$cConvocatoriascandidatos->setIDPROCESO($cEntidad->getIDPROCESO());
			$cConvocatoriascandidatos->setIDPERFILPUESTO($cEntidad->getIDPERFILPUESTO());
			//$cConvocatoriascandidatos->setINFORMADO("1");
			$vConvocatoriascandidatos = $cConvocatoriascandidatosBD->listar($cConvocatoriascandidatos);
			if ($vConvocatoriascandidatos->recordCount() > 0 ){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " No se puede desactivar la asignación, ";
				$this->msg_Error[]	= $sTypeError;
				$sTypeError	=	" Hay c$andidatos que ya han sido asignados para participar con este perfil";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				return 0;
			}
		}
		$sql .= " WHERE ";
		$sql .="IDPERFILPROCESO=" . $aux->qstr($cEntidad->getIDPERFILPROCESO()) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][Sec_perfilesprocesosDB]";
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
		
			$cEntidad = $this->consultar($cEntidad);
			//Se listan todos los c$andidatos por si hay alguno informado, 
			//en ese caso no se puede borrar el perfil_proceso y las dependencias
			require_once(constant("DIR_WS_COM") . "Sec_convocatoriascandidatos/Sec_convocatoriascandidatosDB.php");
			require_once(constant("DIR_WS_COM") . "Sec_convocatoriascandidatos/Sec_convocatoriascandidatos.php");
			$convcand =  new Sec_convocatoriascandidatos();
			$convcandBD = new Sec_ConvocatoriascandidatosDB($aux);
			$convcand->setIDPERFILPUESTO($cEntidad->getIDPERFILPUESTO());
			$convcand->setIDPROCESO($cEntidad->getIDPROCESO());
			$lista =  array();
			$lista = $convcandBD->listarPorSalasConv($convcand);
			$j = 0;
			$contInf = 0;
//			while($j<$lista.size()){
			while(!$lista->EOF){

				if(Integer.parseInt($lista.get($j).getINFORMADO()) > 0){
					$contInf++;
				}
				$j++;
				$lista->MoveNext();
			}

			if ($contInf > 0 ){
				//Si el contador es mayor que 0 quiere decir que al menos hay un c$andidato informado,
				//por lo que no podremos modificar el registro.
				//Cargamos el mensaje de error.
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . "Ha sido imposible borrar la asignación, ";
				$this->msg_Error[]	= $sTypeError;
				$sTypeError	=	"Hay c$andidatos que ya han sido informados para participar.";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				return 0;
			}else{
				//Si no tiene ninguno c$andidato informado, se puede borrar la asignacion de perfil_proceso
				//y los c$andidatos asignados, ya que si no tiene perfil no dejaria asignar.
				$j=0;
				if($lista->recordCount() > 0){
					//Recorremos la lista que nos devuelve el método
//					while($j<$lista.size()){
					while(!$lista->EOF){
						//Comprobamos si el c$andidato correspondiente a la posicion en la lista ha sido informado ya.
						$convcand =  new Sec_convocatoriascandidatos();
						$convcand->setUsuMod($cEntidad->getUsuMod());
						$convcand->setIDCONVOCATORIACANDIDATO($lista.get($j).getIDCONVOCATORIACANDIDATO());
						$convcandBD->borrar($convcand);
						$j++;
						$lista->MoveNext();
					}
				}
				
				//Borramos el registro de la Entidad.
				$sql = "DELETE FROM sec_perfiles_procesos ";
				$sql  .=" WHERE ";
				if ($cEntidad->getIDPROCESO() != ""){
					$sql .= $this->getSQLAnd($and);
					$and = true;
					$sql  .="IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO()) . " ";
				}
				if ($cEntidad->getIDPERFILPROCESO() != ""){
					$sql .= $this->getSQLAnd($and);
					$and = true;
					$sql  .="IDPERFILPROCESO=" . $aux->qstr($cEntidad->getIDPERFILPROCESO()) . " ";
				}
				if ($cEntidad->getIDPERFILPUESTO() != ""){
					$sql .= $this->getSQLAnd($and);
					$and = true;
					$sql  .="IDPERFILPUESTO=" . $aux->qstr($cEntidad->getIDPERFILPUESTO()) . " ";
				}
			}
		if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][Sec_perfilesprocesosDB]";
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


		$sql = "SELECT IDPERFILPROCESO,IDPROCESO,DESCPROCESO,IDPERFILPUESTO,DESCPERFILPUESTO,BAJALOG,fecBaja,fecAlta,fecMod,usuAlta,usuMod FROM sec_perfiles_procesos WHERE ";
		$sql  .="IDPERFILPROCESO=" . $aux->qstr($cEntidad->getIDPERFILPROCESO()) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
		
					$cEntidad->setIDPERFILPROCESO($arr['IDPERFILPROCESO']);
					$cEntidad->setIDPROCESO($arr['IDPROCESO']);
					$cEntidad->setDESCPROCESO($arr['DESCPROCESO']);
					$cEntidad->setIDPERFILPUESTO($arr['IDPERFILPUESTO']);
					$cEntidad->setDESCPERFILPUESTO($arr['DESCPERFILPUESTO']);
					$cEntidad->setBAJALOG($arr['BAJALOG']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][Sec_perfilesprocesosDB]";
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
	function consultaPorProceso($cEntidad) 
	{

		$aux			= $this->conn;


		$sql = "SELECT IDPERFILPROCESO,IDPROCESO,DESCPROCESO,IDPERFILPUESTO,DESCPERFILPUESTO,BAJALOG,fecBaja,fecAlta,fecMod,usuAlta,usuMod FROM sec_perfiles_procesos WHERE ";
		$sql  .="IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO()) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
		
					$cEntidad->setIDPERFILPROCESO($arr['IDPERFILPROCESO']);
					$cEntidad->setIDPROCESO($arr['IDPROCESO']);
					$cEntidad->setDESCPROCESO($arr['DESCPROCESO']);
					$cEntidad->setIDPERFILPUESTO($arr['IDPERFILPUESTO']);
					$cEntidad->setDESCPERFILPUESTO($arr['DESCPERFILPUESTO']);
					$cEntidad->setBAJALOG($arr['BAJALOG']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][Sec_perfilesprocesosDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			exit;
		
		}
		return $cEntidad;

	}
	
	/*************************************************************************
	 * Consulta en la base de datos recogiendo la información
	 * recibida por la entidad consultándola por proceso, 
	 * esta forma de consultar genera un <b>solo</b> 
	 * registro conteniendo la información
	 * de la entidad recibida. Este metodo se utiliza para efectuar
	 * consultas concretas de un solo registro.
	 * @param entidad Entidad con la información basica a consultar
	 * @exception Exception Error al ejecutar la acción
	 *  en la base de datos
	 * @return $cEntidad con la información recuperada.
	 *************************************************************************/
	function consultarPorPorceso($cEntidad) 
	{

		$aux			= $this->conn;


		$sql = "SELECT IDPERFILPROCESO,IDPROCESO,DESCPROCESO,IDPERFILPUESTO,DESCPERFILPUESTO,BAJALOG,fecBaja,fecAlta,fecMod,usuAlta,usuMod FROM sec_perfiles_procesos WHERE ";
		$sql  .="IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO()) . " ";
		
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
		
					$cEntidad->setIDPERFILPROCESO($arr['IDPERFILPROCESO']);
					$cEntidad->setIDPROCESO($arr['IDPROCESO']);
					$cEntidad->setDESCPROCESO($arr['DESCPROCESO']);
					$cEntidad->setIDPERFILPUESTO($arr['IDPERFILPUESTO']);
					$cEntidad->setDESCPERFILPUESTO($arr['DESCPERFILPUESTO']);
					$cEntidad->setBAJALOG($arr['BAJALOG']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
			}
		
		}else{
			
			echo(constant("ERR"));
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [readEntidad][Sec_perfilesprocesosDB]";
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
		$sql.="SELECT * FROM sec_perfiles_procesos ";
		if ($cEntidad->getIDPERFILPROCESO() != null && $cEntidad->getIDPERFILPROCESO() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDPERFILPROCESO >=" . $aux->qstr($cEntidad->getIDPERFILPROCESO());
		}
		if ($cEntidad->getIDPERFILPROCESOHast() != null && $cEntidad->getIDPERFILPROCESOHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDPERFILPROCESO <=" . $aux->qstr($cEntidad->getIDPERFILPROCESOHast());
		}
		if ($cEntidad->getIDPROCESO() != null && $cEntidad->getIDPROCESO() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDPROCESO=" . $aux->qstr($cEntidad->getIDPROCESO());
		}
		if ($cEntidad->getDESCPROCESO() != null && $cEntidad->getDESCPROCESO() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(DESCPROCESO) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDESCPROCESO() . "%") . ")";
		}
		if ($cEntidad->getIDPERFILPUESTO() != null && $cEntidad->getIDPERFILPUESTO() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="IDPERFILPUESTO=" . $aux->qstr($cEntidad->getIDPERFILPUESTO());
		}
		if ($cEntidad->getDESCPERFILPUESTO() != null && $cEntidad->getDESCPERFILPUESTO() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(DESCPERFILPUESTO) LIKE UPPER(" . $aux->qstr("%" . $cEntidad->getDESCPERFILPUESTO() . "%") . ")";
		}
		if ($cEntidad->getBAJALOG() != null && $cEntidad->getBAJALOG() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="BAJALOG=" . $aux->qstr($cEntidad->getBAJALOG());
		}
		if ($cEntidad->getBAJALOGHast() != null && $cEntidad->getBAJALOGHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="BAJALOG=" . $aux->qstr($cEntidad->getBAJALOGHast());
		}
		if ($cEntidad->getFecBaja() != null && $cEntidad->getFecBaja() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecBaja>=" . SQL.cFecha($cEntidad->getFecBaja());
		}
		if ($cEntidad->getFecBajaHast() != null && $cEntidad->getFecBajaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sHora = "23:59:59";
			$sql .="fecBaja<=" . SQL.cFechaHora($cEntidad->getFecBajaHast() . $sHora);
		}
		if ($cEntidad->getFecAlta() != null && $cEntidad->getFecAlta() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecAlta>=" . SQL.cFecha($cEntidad->getFecAlta());
		}
		if ($cEntidad->getFecAltaHast() != null && $cEntidad->getFecAltaHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sHora = "23:59:59";
			$sql .="fecAlta<=" . SQL.cFechaHora($cEntidad->getFecAltaHast() . $sHora);
		}
		if ($cEntidad->getFecMod() != null && $cEntidad->getFecMod() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="fecMod>=" . SQL.cFecha($cEntidad->getFecMod());
		}
		if ($cEntidad->getFecModHast() != null && $cEntidad->getFecModHast() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sHora = "23:59:59";
			$sql .="fecMod<=" . SQL.cFechaHora($cEntidad->getFecModHast() . $sHora);
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

		//ejecutamos la query	
		com.ni.log.write("SQL Listar: " . $sql.toString());
		return ServicioBase.consultar(conConexion, $sql.toString());
	}

	function getSQLWhere($bFlag)
	{
		if (!bFlag)	return " WHERE ";
		else	return " AND ";
	}

	function getSQLAnd($bFlag)
	{
		if (bFlag)	return " AND ";
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



}//Fin de la Clase Sec_perfilesprocesosDB
?>