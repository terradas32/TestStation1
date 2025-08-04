<<<<<<< HEAD
<?php
	/***********************************************************************
	* Valida si un usuario está logado y no ha terminado el tiempo de sesión.
	* @param conexion Conexión de la base de datos
	* @return boolean true si está logado
	***********************************************************************/
	function isLogado($conConexion)
	{
		$bRetorno =false;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
		$cEntidadDB	= new UsuariosDB($conConexion);  // Entidad DB
		$cEntidad	= new Usuarios();  // Entidad

		if (!empty($_POST['sTK']))
		{
			$cEntidad = getUsuarioToken($conConexion);
			if ($cEntidad->getIdUsuario() != null && 
					$cEntidad->getIdUsuario() != "" && 
					$cEntidad->getLogin() != "" ){
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
	function getUsuarioToken($conConexion)
	{
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
		$cEntidadDB	= new UsuariosDB($conConexion);  // Entidad DB
		$cEntidad	= new Usuarios();  // Entidad

		if (!empty($_POST['sTK']))
		{
			$cEntidad->setToken($_POST['sTK']);
			$cEntidadDB     = new UsuariosDB($conConexion);
			$cEntidad = $cEntidadDB->usuarioPorToken($cEntidad);
		}
		return $cEntidad;
	}

	function isUsuarioActivo($conConexion)
	{
		$bRetorno = false;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
		$cEntidadUsuariosDB	= new UsuariosDB($conConexion);  // Entidad DB
		$cEntidadUsuarios	= new Usuarios();  // Entidad
			   
		$bRetorno = isLogado($conConexion);
		if ($bRetorno){
			$cEntidadUsuarios = getUsuarioToken($conConexion);
			$cUsuariosDB = new UsuariosDB($conConexion);
			$bRetorno= $cUsuariosDB->isUsuarioActivo($cEntidadUsuarios);
		}
		return $bRetorno;
	}

	function getMenus($conConexion)
	{
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
		$cEntidadUsuariosDB	= new UsuariosDB($conConexion);  // Entidad DB
		$cEntidadUsuarios	= new Usuarios();  // Entidad
		
		$sRetorno = "";				//Menús con los resultados de las funcionalidades a las que tiene acceso
		$sSQL;

		if (!empty($_POST['sTK']))
		{
			$cEntidadUsuarios->setToken($_POST['sTK']);
			$cEntidadUsuariosDB     = new UsuariosDB($conConexion);
			$cEntidadUsuarios = $cEntidadUsuariosDB->usuarioPorToken($cEntidadUsuarios);
			$vPerfiles = null;					//vector para obtener resultados de los perfiles por usuario
			$vFuncionalidades = null;				//vector para obtener resultados de las funcionalidades x perfil

			//Miramos si tiene perfiles asignados
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfilesDB.php");
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfiles.php");
	       	$cEntidadUsuarios_perfilesDB	= new Usuarios_perfilesDB($conConexion);  // Entidad DB
    		$cEntidadUsuarios_perfiles    	= new Usuarios_perfiles();  // Entidad
		
			$cEntidadUsuarios_perfiles->setIdUsuario($cEntidadUsuarios->getIdUsuario());
			$vPerfiles = $conConexion->Execute($cEntidadUsuarios_perfilesDB->readLista($cEntidadUsuarios_perfiles));
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
                require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
                require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidades.php");
                $cEntidadPerfiles_funcionalidadesDB	= new Perfiles_funcionalidadesDB($conConexion);  // Entidad DB
                $cEntidadPerfiles_funcionalidades    	= new Perfiles_funcionalidades();  // Entidad
    		
				$cEntidadPerfiles_funcionalidades->setIdPerfil($sUP);
				$cEntidadPerfiles_funcionalidades->setOrderBy("idFuncionalidad, modificar, borrar ASC");
				$vFuncionalidades = $conConexion->Execute($cEntidadPerfiles_funcionalidadesDB->readLista($cEntidadPerfiles_funcionalidades));

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
		$vPerfiles = null;					//vector para obtener resultados de los perfiles por usuario
		$vFuncionalidades = null;				//vector para obtener resultados de las funcionalidades x perfil
		$hDatos;
		$retorno = isLogado($conConexion);
		if ($retorno){
			$cEntidadUsuarios = getUsuarioToken($conConexion);
			//Miramos si tiene perfiles asignados
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfilesDB.php");
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfiles.php");
	       	$cEntidadUsuarios_perfilesDB	= new Usuarios_perfilesDB($conConexion);  // Entidad DB
    		$cEntidadUsuarios_perfiles    	= new Usuarios_perfiles();  // Entidad
		
			$cEntidadUsuarios_perfiles->setIdUsuario($cEntidadUsuarios->getIdUsuario());
			$vPerfiles = $conConexion->Execute($cEntidadUsuarios_perfilesDB->readLista($cEntidadUsuarios_perfiles));
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
		$vPerfiles = null;					//vector para obtener resultados de los perfiles por usuario
		$vFuncionalidadesPerfiles = null;				//vector para obtener resultados de las funcionalidades x perfil
		$vFuncionalidades = null;				//vector para obtener resultados de las funcionalidades x perfil
		$hDatos = null;
		$hDatosRetorno = null;
		$hDatosFuncionalidad = null;

		$retorno = isLogado($conConexion);
		if ($retorno){
			$cEntidadUsuarios = getUsuarioToken($conConexion);
			//Miramos si tiene perfiles asignados
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfilesDB.php");
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfiles.php");
	       	$cEntidadUsuarios_perfilesDB	= new Usuarios_perfilesDB($conConexion);  // Entidad DB
    		$cEntidadUsuarios_perfiles    	= new Usuarios_perfiles();  // Entidad
    		$cEntidadUsuarios_perfiles->setIdUsuario($cEntidadUsuarios->getIdUsuario());
			$vPerfiles = $conConexion->Execute($cEntidadUsuarios_perfilesDB->readLista($cEntidadUsuarios_perfiles));
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
                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidades.php");
                    $cEntidadPerfiles_funcionalidadesDB	= new Perfiles_funcionalidadesDB($conConexion);  // Entidad DB
                    $cEntidadPerfiles_funcionalidades    	= new Perfiles_funcionalidades();  // Entidad
        		
    				$cEntidadPerfiles_funcionalidades->setIdPerfil($sUP);
    				$cEntidadPerfiles_funcionalidades->setOrderBy("idFuncionalidad, modificar, borrar ASC");
    				$vFuncionalidadesPerfiles = $conConexion->Execute($cEntidadPerfiles_funcionalidadesDB->readLista($cEntidadPerfiles_funcionalidades));
				

                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Funcionalidades/FuncionalidadesDB.php");
                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Funcionalidades/Funcionalidades.php");
                    $cEntidadFuncionalidadesDB	= new FuncionalidadesDB($conConexion);  // Entidad DB
                    $cEntidadFuncionalidades    	= new Funcionalidades();  // Entidad
					$cEntidadFuncionalidades->setUrl($sFuncionalidad);
                    $vFuncionalidades = $conConexion->Execute($cEntidadFuncionalidadesDB->readListaUrl($cEntidadFuncionalidades));
//                    if ($vFuncionalidades->RecordCount() <= 0 || $vFuncionalidades->RecordCount() > 1){
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
=======
<?php
	/***********************************************************************
	* Valida si un usuario está logado y no ha terminado el tiempo de sesión.
	* @param conexion Conexión de la base de datos
	* @return boolean true si está logado
	***********************************************************************/
	function isLogado($conConexion)
	{
		$bRetorno =false;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
		$cEntidadDB	= new UsuariosDB($conConexion);  // Entidad DB
		$cEntidad	= new Usuarios();  // Entidad

		if (!empty($_POST['sTK']))
		{
			$cEntidad = getUsuarioToken($conConexion);
			if ($cEntidad->getIdUsuario() != null && 
					$cEntidad->getIdUsuario() != "" && 
					$cEntidad->getLogin() != "" ){
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
	function getUsuarioToken($conConexion)
	{
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
		$cEntidadDB	= new UsuariosDB($conConexion);  // Entidad DB
		$cEntidad	= new Usuarios();  // Entidad

		if (!empty($_POST['sTK']))
		{
			$cEntidad->setToken($_POST['sTK']);
			$cEntidadDB     = new UsuariosDB($conConexion);
			$cEntidad = $cEntidadDB->usuarioPorToken($cEntidad);
		}
		return $cEntidad;
	}

	function isUsuarioActivo($conConexion)
	{
		$bRetorno = false;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
		$cEntidadUsuariosDB	= new UsuariosDB($conConexion);  // Entidad DB
		$cEntidadUsuarios	= new Usuarios();  // Entidad
			   
		$bRetorno = isLogado($conConexion);
		if ($bRetorno){
			$cEntidadUsuarios = getUsuarioToken($conConexion);
			$cUsuariosDB = new UsuariosDB($conConexion);
			$bRetorno= $cUsuariosDB->isUsuarioActivo($cEntidadUsuarios);
		}
		return $bRetorno;
	}

	function getMenus($conConexion)
	{

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
		$cEntidadUsuariosDB	= new UsuariosDB($conConexion);  // Entidad DB
		$cEntidadUsuarios	= new Usuarios();  // Entidad
		
		$sRetorno = "";				//Menús con los resultados de las funcionalidades a las que tiene acceso
		$sSQL;

		if (!empty($_POST['sTK']))
		{
			$cEntidadUsuarios->setToken($_POST['sTK']);
			$cEntidadUsuariosDB     = new UsuariosDB($conConexion);
			$cEntidadUsuarios = $cEntidadUsuariosDB->usuarioPorToken($cEntidadUsuarios);
			$vPerfiles = null;					//vector para obtener resultados de los perfiles por usuario
			$vFuncionalidades = null;				//vector para obtener resultados de las funcionalidades x perfil

			//Miramos si tiene perfiles asignados
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfilesDB.php");
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfiles.php");
	       	$cEntidadUsuarios_perfilesDB	= new Usuarios_perfilesDB($conConexion);  // Entidad DB
    		$cEntidadUsuarios_perfiles    	= new Usuarios_perfiles();  // Entidad
		
			$cEntidadUsuarios_perfiles->setIdUsuario($cEntidadUsuarios->getIdUsuario());
			$vPerfiles = $conConexion->Execute($cEntidadUsuarios_perfilesDB->readLista($cEntidadUsuarios_perfiles));
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
                require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
                require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidades.php");
                $cEntidadPerfiles_funcionalidadesDB	= new Perfiles_funcionalidadesDB($conConexion);  // Entidad DB
                $cEntidadPerfiles_funcionalidades    	= new Perfiles_funcionalidades();  // Entidad
    		
				$cEntidadPerfiles_funcionalidades->setIdPerfil($sUP);
				$cEntidadPerfiles_funcionalidades->setOrderBy("idFuncionalidad, modificar, borrar ASC");
				$vFuncionalidades = $conConexion->Execute($cEntidadPerfiles_funcionalidadesDB->readLista($cEntidadPerfiles_funcionalidades));

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
		$vPerfiles = null;					//vector para obtener resultados de los perfiles por usuario
		$vFuncionalidades = null;				//vector para obtener resultados de las funcionalidades x perfil
		$hDatos;
		$retorno = isLogado($conConexion);
		if ($retorno){
			$cEntidadUsuarios = getUsuarioToken($conConexion);
			//Miramos si tiene perfiles asignados
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfilesDB.php");
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfiles.php");
	       	$cEntidadUsuarios_perfilesDB	= new Usuarios_perfilesDB($conConexion);  // Entidad DB
    		$cEntidadUsuarios_perfiles    	= new Usuarios_perfiles();  // Entidad
		
			$cEntidadUsuarios_perfiles->setIdUsuario($cEntidadUsuarios->getIdUsuario());
			$vPerfiles = $conConexion->Execute($cEntidadUsuarios_perfilesDB->readLista($cEntidadUsuarios_perfiles));
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
		$vPerfiles = null;					//vector para obtener resultados de los perfiles por usuario
		$vFuncionalidadesPerfiles = null;				//vector para obtener resultados de las funcionalidades x perfil
		$vFuncionalidades = null;				//vector para obtener resultados de las funcionalidades x perfil
		$hDatos = null;
		$hDatosRetorno = null;
		$hDatosFuncionalidad = null;

		$retorno = isLogado($conConexion);
		if ($retorno){
			$cEntidadUsuarios = getUsuarioToken($conConexion);
			//Miramos si tiene perfiles asignados
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfilesDB.php");
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfiles.php");
	       	$cEntidadUsuarios_perfilesDB	= new Usuarios_perfilesDB($conConexion);  // Entidad DB
    		$cEntidadUsuarios_perfiles    	= new Usuarios_perfiles();  // Entidad
    		$cEntidadUsuarios_perfiles->setIdUsuario($cEntidadUsuarios->getIdUsuario());
			$vPerfiles = $conConexion->Execute($cEntidadUsuarios_perfilesDB->readLista($cEntidadUsuarios_perfiles));
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
                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidades.php");
                    $cEntidadPerfiles_funcionalidadesDB	= new Perfiles_funcionalidadesDB($conConexion);  // Entidad DB
                    $cEntidadPerfiles_funcionalidades    	= new Perfiles_funcionalidades();  // Entidad
        		
    				$cEntidadPerfiles_funcionalidades->setIdPerfil($sUP);
    				$cEntidadPerfiles_funcionalidades->setOrderBy("idFuncionalidad, modificar, borrar ASC");
    				$vFuncionalidadesPerfiles = $conConexion->Execute($cEntidadPerfiles_funcionalidadesDB->readLista($cEntidadPerfiles_funcionalidades));
				

                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Funcionalidades/FuncionalidadesDB.php");
                    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Funcionalidades/Funcionalidades.php");
                    $cEntidadFuncionalidadesDB	= new FuncionalidadesDB($conConexion);  // Entidad DB
                    $cEntidadFuncionalidades    	= new Funcionalidades();  // Entidad
					$cEntidadFuncionalidades->setUrl($sFuncionalidad);
                    $vFuncionalidades = $conConexion->Execute($cEntidadFuncionalidadesDB->readListaUrl($cEntidadFuncionalidades));
//                    if ($vFuncionalidades->RecordCount() <= 0 || $vFuncionalidades->RecordCount() > 1){
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
>>>>>>> ef67b2adad35376e7004f53c2ad7cef5f1096846
