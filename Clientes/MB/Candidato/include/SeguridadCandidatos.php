<?php
	/***********************************************************************
	* Valida si un usuario está logado y no ha terminado el tiempo de sesión.
	* @param conexion Conexión de la base de datos
	* @return boolean true si está logado
	***********************************************************************/
	function isLogado($conConexion)
	{
		$bRetorno =false;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
		$cEntidadDB	= new CandidatosDB($conConexion);  // Entidad DB
		$cEntidad	= new Candidatos();  // Entidad

		if (!empty($_POST['sTKCandidatos']))
		{
			$cEntidad = getCandidatoToken($conConexion);
			if ($cEntidad->getIdCandidato() != null && 
					$cEntidad->getIdCandidato() != "" && 
					$cEntidad->getMail() != "" ){
				$bRetorno =true;
			}else{
				$bRetorno =false;
			}
		}
		return $bRetorno;
	}
	/***********************************************************************
	* Devuelve un objeto de tipo Usuario validando por token.
	* @param conexion Conexión de la base de datos
	* @return object Usuarios
	***********************************************************************/
	function getCandidatoToken($conConexion)
	{
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
		$cEntidadDB	= new CandidatosDB($conConexion);  // Entidad DB
		$cEntidad	= new Candidatos();  // Entidad

		if (!empty($_POST['sTKCandidatos']))
		{
			$cEntidad->setToken($_POST['sTKCandidatos']);
			$cEntidadDB     = new CandidatosDB($conConexion);
			$cEntidad = $cEntidadDB->candidatoPorToken($cEntidad);
		}
		return $cEntidad;
	}

	function isCandidatoActivo($conConexion)
	{
		$bRetorno = false;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
		$cEntidadDB	= new CandidatosDB($conConexion);  // Entidad DB
		$cEntidad	= new Candidatos();  // Entidad
			   
		$bRetorno = isLogado($conConexion);
		if ($bRetorno){
			$cEntidadCandidatos = getCandidatoToken($conConexion);
			$cCandidatosDB = new CandidatosDB($conConexion);
			$bRetorno= $cCandidatosDB->isCandidatoActivo($cEntidadCandidatos);
		}
		return $bRetorno;
	}
?>
