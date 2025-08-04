<?php

/**
* Realiza la operaciones de alta, baja, modificación, borrado,
* consulta de registros y consulta de numero de elementos sobre
* la tabla perfiles_empresas
**/
class Perfiles_empresasDB
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
		$newId		= 1;
	
		$aPerfilEmpresas = $cEntidad->getPerfilEmpresas();
		$iSize = sizeof($aPerfilEmpresas);
		for ($i=0; $i < $iSize; $i++){
			$aPsFs = explode("|", $aPerfilEmpresas[$i]);
			$cEntidad->setIdPerfil($aPsFs[0]);
			$cEntidad->setIdEmpresa($aPsFs[1]);
			if (!$this->borrar($cEntidad)){
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . ":::" . constant("MNT_BORRAR") . "][Perfiles_empresasDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				return 0;
			}else{
				//Alta
				$sql = "INSERT INTO perfiles_empresas (";
				$sql .= "idPerfil" . ",";
				$sql .= "idEmpresa" . ",";
				$sql .= "modificar" . ",";
				$sql .= "borrar" . ",";
				$sql .= "fecAlta" . ",";
				$sql .= "fecMod" . ",";
				$sql .= "usuAlta" . ",";
				$sql .= "usuMod" . ")";
				$sql .= " VALUES (";
				$sql .= $aux->qstr($aPsFs[0], false) . ",";
				$sql .= $aux->qstr($aPsFs[1], false) . ",";
				$sql .= $aux->qstr($aPsFs[2], false) . ",";
				$sql .= $aux->qstr($aPsFs[3], false) . ",";
				$sql .= $aux->sysTimeStamp . ",";
				$sql .= $aux->sysTimeStamp . ",";
				$sql .= $aux->qstr($cEntidad->getUsuAlta(), false) . ",";
				$sql .= $aux->qstr($cEntidad->getUsuMod(), false) . ")";
				if($aux->Execute($sql) === false){
					$this->msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Perfiles_empresasDB]";
					$this->msg_Error[]	= $sTypeError;
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					return 0;
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
	
		$sql  = "SELECT MAX() AS Max FROM perfiles_empresas ";
		$sql  .=" WHERE idPerfil=" . $aux->qstr($cEntidad->getIdPerfil(), false) . " AND idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$rs = $aux->Execute($sql);
		$newId=0;
		if ($rs){
			while ($arr = $rs->FetchRow()){
				$newId = $arr['Max'] + 1;
			}
		}else{
			echo("Error SQL:\n" . $sql);
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
/*		$aux			= $this->conn;
	
		$sql = "UPDATE perfiles_empresas SET ";
		$sql .= "idPerfil=" . $aux->qstr($cEntidad->getIdPerfil(), false) . ", ";
		$sql .= "idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . ", ";
		$sql .= "modificar=" . $aux->qstr($cEntidad->getModificar(), false) . ", ";
		$sql .= "borrar=" . $aux->qstr($cEntidad->getBorrar(), false) . ", ";
		$sql .= "fecMod=" . $aux->sysTimeStamp . ", ";
		$sql .= "usuMod=" . $aux->qstr($cEntidad->getUsuMod(), false) ;
		$sql .= " WHERE ";
		$sql .="idPerfil=" . $aux->qstr($cEntidad->getIdPerfil(), false) . " AND idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$retorno=true;
		if($aux->Execute($sql) === false){
			$retorno=false;
			$this->msg_Error	= array();
			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "][Perfiles_empresasDB]";
			$this->msg_Error[]	= $sTypeError;
			error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
		return $retorno;
*/
		return $this->insertar($cEntidad);
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
			$sql  ="DELETE FROM perfiles_empresas ";
			$sql  .="WHERE ";
			if ($cEntidad->getIdPerfil() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idPerfil=" . $aux->qstr($cEntidad->getIdPerfil(), false) . " ";
			}
			if ($cEntidad->getIdEmpresa() != ""){
				$sql .= $this->getSQLAnd($and);
				$and = true;
				$sql  .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
			}
			if($aux->Execute($sql) === false){
				$retorno=false;
				$this->msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][Perfiles_empresasDB]";
				$this->msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
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
	$cEntidad->getPerfilEmpresas();
		$sql = "SELECT  idPerfil, idEmpresa, modificar, borrar, fecAlta, fecMod, usuAlta, usuMod FROM perfiles_empresas WHERE ";
		$sql  .="idPerfil=" . $aux->qstr($cEntidad->getIdPerfil(), false) . " AND idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false) . " ";
		$rs = $aux->Execute($sql);
		if ($rs){
			while ($arr = $rs->FetchRow()){
					$cEntidad->setIdPerfil($arr['idPerfil']);
					$cEntidad->setIdEmpresa($arr['idEmpresa']);
					$cEntidad->setModificar($arr['modificar']);
					$cEntidad->setBorrar($arr['borrar']);
					$cEntidad->setFecAlta($arr['fecAlta']);
					$cEntidad->setFecMod($arr['fecMod']);
					$cEntidad->setUsuAlta($arr['usuAlta']);
					$cEntidad->setUsuMod($arr['usuMod']);
					$cEntidad->setPerfilEmpresas($arr['idPerfil'] . "|" . $arr['idEmpresa'] . "|" . $arr['modificar'] . "|" . $arr['borrar']);
			}
		}else{
			echo("Error SQL:\n" . $sql);
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
		$sql.="SELECT * FROM perfiles_empresas ";
		if ($cEntidad->getIdPerfil() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
//			$sql .="idPerfil=" . $aux->qstr($cEntidad->getIdPerfil(), false);
			$sql .="idPerfil IN (" . $cEntidad->getIdPerfil() . ")";
		}
		if ($cEntidad->getIdEmpresa() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="idEmpresa=" . $aux->qstr($cEntidad->getIdEmpresa(), false);
		}
		if ($cEntidad->getModificar() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(modificar) LIKE UPPER('%" . $cEntidad->getModificar() . "%')";
		}
		if ($cEntidad->getBorrar() != ""){
			$sql .= $this->getSQLWhere($and);
			$and = true;
			$sql .="UPPER(borrar) LIKE UPPER('%" . $cEntidad->getBorrar() . "%')";
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
		}
		$this->sSQL=$sql;
		$_SESSION['SQLPerfiles_empresasDB'] = $sql;
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
		$this->sSQL = $_SESSION['SQLPerfiles_empresasDB'];
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
		$sql = "UPDATE perfiles_empresas SET  ";
		return $retorno;
	}
}//Fin de la Clase Perfiles_empresasDB
?>