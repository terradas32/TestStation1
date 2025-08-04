<?php
	/***********************************************************************
	* Valida si una Empresa está logada y no ha terminado el tiempo de sesión.
	* @param conexion Conexión de la base de datos
	* @return boolean true si está logado
	***********************************************************************/
	function isLogado($conConexion)
	{
		$bRetorno =false;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
		$cEntidadDB	= new EmpresasDB($conConexion);  // Entidad DB
		$cEntidad	= new Empresas();  // Entidad
		
		if (!empty($_POST['sTK']))
		{
			$cEntidad = getUsuarioToken($conConexion);
			if ($cEntidad->getIdEmpresa() != null && 
					$cEntidad->getIdEmpresa() != "" && 
					$cEntidad->getUsuario() != "" ){
				$bRetorno =true;
			}else{
				$bRetorno =false;
			}
		}
		return $bRetorno;
	}
	/***********************************************************************
	* Devuelve un objeto de tipo Empresa validando por token.
	* @param conexion Conexión de la base de datos
	* @return object Empresas
	***********************************************************************/
	function getUsuarioToken($conConexion)
	{
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
		$cEntidadDB	= new EmpresasDB($conConexion);  // Entidad DB
		$cEntidad	= new Empresas();  // Entidad

		if (!empty($_POST['sTK']))
		{
			$cEntidad->setToken($_POST['sTK']);
			$cEntidadDB     = new EmpresasDB($conConexion);
			$cEntidad = $cEntidadDB->usuarioPorToken($cEntidad);
		}
		return $cEntidad;
	}


	function isUsuarioActivo($conConexion)
	{
		$bRetorno = false;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
		$cEntidadEmpresasDB	= new EmpresasDB($conConexion);  // Entidad DB
		$cEntidadEmpresas	= new Empresas();  // Entidad
			   
		$bRetorno = isLogado($conConexion);
		if ($bRetorno){
			$cEntidadEmpresas = getUsuarioToken($conConexion);
			$cEmpresasDB = new EmpresasDB($conConexion);
			$bRetorno= $cEmpresasDB->isUsuarioActivo($cEntidadEmpresas);
		}
		return $bRetorno;
	}

	function getMenus($conConexion)
	{

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
		$cEntidadEmpresasDB	= new EmpresasDB($conConexion);  // Entidad DB
		$cEntidadEmpresas	= new Empresas();  // Entidad
		
		$sRetorno = "";				//Menús con los resultados de las funcionalidades a las que tiene acceso
		$sSQL;

		if (!empty($_POST['sTK']))
		{
			$cEntidadEmpresas->setToken($_POST['sTK']);
			$cEntidadEmpresasDB     = new EmpresasDB($conConexion);
			$cEntidadEmpresas = $cEntidadEmpresasDB->usuarioPorToken($cEntidadEmpresas);
			$vPerfiles = null;					//vector para obtener resultados de los perfiles por empresa
			$vFuncionalidades = null;				//vector para obtener resultados de las funcionalidades x perfil

			//Miramos si tiene perfiles asignados
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfilesDB.php");
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfiles.php");
	       	$cEntidadEmpresas_perfilesDB	= new Empresas_perfilesDB($conConexion);  // Entidad DB
    		$cEntidadEmpresas_perfiles    	= new Empresas_perfiles();  // Entidad
		
			$cEntidadEmpresas_perfiles->setIdEmpresa($cEntidadEmpresas->getIdEmpresa());
			$vPerfiles = $conConexion->Execute($cEntidadEmpresas_perfilesDB->readLista($cEntidadEmpresas_perfiles));
			//miramos si nos ha llegado algun resultado

            $i=0;
			$sUP = "";
            while (!$vPerfiles->EOF)
			{
			     
				$sUP .= "," . $vPerfiles->fields['idPerfil'];
				$i++;
				$vPerfiles->MoveNext();
			}
			if ($i > 0){
				$sUP = substr($sUP, 1);
				//Miramos las funcionalidades para el / los Perfiles.
                require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles_funcionalidades/Empresas_perfiles_funcionalidadesDB.php");
                require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles_funcionalidades/Empresas_perfiles_funcionalidades.php");
                $cEntidadEmpresas_perfiles_funcionalidadesDB	= new Empresas_perfiles_funcionalidadesDB($conConexion);  // Entidad DB
                $cEntidadEmpresas_perfiles_funcionalidades    	= new Empresas_perfiles_funcionalidades();  // Entidad
    		
				$cEntidadEmpresas_perfiles_funcionalidades->setIdPerfil($sUP);
				$cEntidadEmpresas_perfiles_funcionalidades->setOrderBy("idFuncionalidad, modificar, borrar ASC");
				$vFuncionalidades = $conConexion->Execute($cEntidadEmpresas_perfiles_funcionalidadesDB->readLista($cEntidadEmpresas_perfiles_funcionalidades));

				$sFA = "";
				$i=0;
				while (!$vFuncionalidades->EOF)
				{
				    $sFA .= "," . $vFuncionalidades->fields['idFuncionalidad'];
					$i++;
					$vFuncionalidades->MoveNext();
				}
				if ($i > 0){
					//Funcionalidades a las que tiene acceso
					$sFA = substr($sFA, 1);
                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Funcionalidades/FuncionalidadesDB.php");
                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Funcionalidades/Funcionalidades.php");
                    $cEntidadFuncionalidadesDB	= new FuncionalidadesDB($conConexion);  // Entidad DB
                    $cEntidadFuncionalidades    	= new Funcionalidades();  // Entidad
					$cEntidadFuncionalidades->setIdFuncionalidad($sFA);
					$sSQL = $cEntidadFuncionalidadesDB->readListaAcceso($cEntidadFuncionalidades);
					$sRetorno = $cEntidadFuncionalidadesDB->getMenus($sSQL);
				}
			}
		}
		return $sRetorno;
	}
	function isAccesFuncionalidadNombre($sFuncionalidad, $conConexion)
	{
		$retorno =false;
		$vPerfiles = null;					//vector para obtener resultados de los perfiles por Empresa
		$vFuncionalidades = null;				//vector para obtener resultados de las funcionalidades x perfil
		$hDatos;
		$retorno = isLogado($conConexion);
		if ($retorno){
			$cEntidadEmpresas = getUsuarioToken($conConexion);
			//Miramos si tiene perfiles asignados
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfilesDB.php");
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfiles.php");
	       	$cEntidadEmpresas_perfilesDB	= new Empresas_perfilesDB($conConexion);  // Entidad DB
    		$cEntidadEmpresas_perfiles    	= new Empresas_perfiles();  // Entidad
		
			$cEntidadEmpresas_perfiles->setIdEmpresa($cEntidadEmpresas->getIdEmpresa());
			$vPerfiles = $conConexion->Execute($cEntidadEmpresas_perfilesDB->readLista($cEntidadEmpresas_perfiles));
			//miramos si nos ha llegado algun resultado
			if ($vPerfiles.size() <= 0)
			{
				$retorno =false;
			}else{
				$i=0;
				$sUP = "";
                while (!$vPerfiles->EOF)
                {
    				$sUP .= "," . $vPerfiles->fields['idPerfil'];
    				$i++;
    				$vPerfiles->MoveNext();
				}
				if ($i > 0){
				    $sUP = substr($sUP, 1);
					//Miramos las funcionalidades para el / los Perfiles.
					$cEntidadPerfilesFuncionalidades;						// Entidad
					$cEntidadPerfilesFuncionalidadesDB;
					$cEntidadPerfilesFuncionalidades = new PerfilesFuncionalidades();
					$cEntidadPerfilesFuncionalidadesDB = new PerfilesFuncionalidadesDB($conConexion);
					$cEntidadPerfilesFuncionalidades.setIdPerfil($sUP);
					$cEntidadPerfilesFuncionalidades.setOrderBy("idFuncionalidad, modificar, borrar ASC");
					$vFuncionalidades =$conConexion->Execute($cEntidadPerfilesFuncionalidadesDB->readLista($cEntidadPerfilesFuncionalidades));

    				$i=0;
    				$bEntrontrado = false;
	       			while (!$vFuncionalidades->EOF)
		      		{
			     	    if ($sFuncionalidad == $vFuncionalidades->fields['idFuncionalidad'])
			     	    {
			     	    	$bEntrontrado =true;
							break;
                        }
				        $i++;
					   $vFuncionalidades->MoveNext();
				    }
					$retorno = $bEntrontrado;
				} else{
					$retorno =false;
				}
			}
		}
		return $retorno;
	}
	
	function permisosFuncionalidadNombre($sFuncionalidad, $conConexion)
	{
		$retorno =false;
		$vPerfiles = null;					//vector para obtener resultados de los perfiles por Empresa
		$vFuncionalidadesPerfiles = null;				//vector para obtener resultados de las funcionalidades x perfil
		$vFuncionalidades = null;				//vector para obtener resultados de las funcionalidades x perfil
		$hDatos = null;
		$hDatosRetorno = null;
		$hDatosFuncionalidad = null;

		$retorno = isLogado($conConexion);
		if ($retorno){
			$cEntidadEmpresas = getUsuarioToken($conConexion);
			//Miramos si tiene perfiles asignados
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfilesDB.php");
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfiles.php");
	       	$cEntidadEmpresas_perfilesDB	= new Empresas_perfilesDB($conConexion);  // Entidad DB
    		$cEntidadEmpresas_perfiles    	= new Empresas_perfiles();  // Entidad
    		$cEntidadEmpresas_perfiles->setIdEmpresa($cEntidadEmpresas->getIdEmpresa());
			$vPerfiles = $conConexion->Execute($cEntidadEmpresas_perfilesDB->readLista($cEntidadEmpresas_perfiles));
			//miramos si nos ha llegado algun resultado
            if ($vPerfiles->RecordCount() <= 0)
			{
                $retorno =false;
			}else{
				$i=0;
				$sUP = "";
				while (!$vPerfiles->EOF)
				{
					$sUP .= "," . $vPerfiles->fields['idPerfil'];
					$i++;
					$vPerfiles->MoveNext();
				}
				if ($i > 0){
                    $sUP = substr($sUP, 1);
    				//Miramos las funcionalidades para el / los Perfiles.
                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles_funcionalidades/Empresas_perfiles_funcionalidadesDB.php");
                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles_funcionalidades/Empresas_perfiles_funcionalidades.php");
                    $cEntidadEmpresas_perfiles_funcionalidadesDB	= new Empresas_perfiles_funcionalidadesDB($conConexion);  // Entidad DB
                    $cEntidadEmpresas_perfiles_funcionalidades    	= new Empresas_perfiles_funcionalidades();  // Entidad
        		
    				$cEntidadEmpresas_perfiles_funcionalidades->setIdPerfil($sUP);
    				$cEntidadEmpresas_perfiles_funcionalidades->setOrderBy("idFuncionalidad, modificar, borrar ASC");
    				$vFuncionalidadesPerfiles = $conConexion->Execute($cEntidadEmpresas_perfiles_funcionalidadesDB->readLista($cEntidadEmpresas_perfiles_funcionalidades));
				

                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Funcionalidades/FuncionalidadesDB.php");
                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Funcionalidades/Funcionalidades.php");
                    $cEntidadFuncionalidadesDB	= new FuncionalidadesDB($conConexion);  // Entidad DB
                    $cEntidadFuncionalidades    	= new Funcionalidades();  // Entidad
					$cEntidadFuncionalidades->setUrl($sFuncionalidad);
                    $vFuncionalidades = $conConexion->Execute($cEntidadFuncionalidadesDB->readListaUrl($cEntidadFuncionalidades));
                    //if ($vFuncionalidades->RecordCount() <= 0 || $vFuncionalidades->RecordCount() > 1){
					if ($vFuncionalidades->RecordCount() <= 0 ){
						$retorno =false;
					}else{
						$k=0;
						$idFuncionalidad = "";
						while (!$vFuncionalidades->EOF)
						{
							$idFuncionalidad = $vFuncionalidades->fields['idFuncionalidad'];
        					$vFuncionalidades->MoveNext();
						}
						$i=0;
						$bEntrontrado = false;
						while (!$vFuncionalidadesPerfiles->EOF)
						{
						    $hDatosRetorno = $vFuncionalidadesPerfiles;
							if ($idFuncionalidad == $vFuncionalidadesPerfiles->fields['idFuncionalidad'])
							{
								$bEntrontrado =true;
								break;
							}
							$i++;
							$vFuncionalidadesPerfiles->MoveNext();
						}
						$retorno = $bEntrontrado;
					}
				} else{
					$retorno =false;
				}
			}
		}
		return $hDatosRetorno;
	}
?>
