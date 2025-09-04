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
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_usuarios/Empresas_usuariosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_usuarios/Empresas_usuarios.php");

		$cEntidadDB	= new EmpresasDB($conConexion);  // Entidad DB
		$cEntidad	= new Empresas();  // Entidad
		$cEmpresas_usuariosDB	= new Empresas_usuariosDB($conConexion);  // Entidad DB
		$cEmpresas_usuarios	= new Empresas_usuarios();  // Entidad

		if (!empty($_POST['sTK']))
		{
			$cEntidad = getUsuarioToken($conConexion);

//			echo "<br />SEGURIDAD::isLogado::" . get_class($cEntidad);

			if ($cEntidad->getIdEmpresa() != null &&
					$cEntidad->getIdEmpresa() != "" &&
					$cEntidad->getUsuario() != "" &&
					get_class($cEntidad) == "Empresas" ){
					$bRetorno =true;
			}else{

//				echo "<br />1TRUE ->isLogado::" . $cEntidad->getIdEmpresa();
//				echo "<br />2TRUE ->isLogado::" . $cEntidad->getUsuario();
//				echo "<br />3TRUE ->isLogado::" . get_class($cEntidad);

				if ($cEntidad->getIdEmpresa() != null &&
						$cEntidad->getIdEmpresa() != "" &&
						$cEntidad->getUsuario() != "" &&
						get_class($cEntidad) == "Empresas_usuarios" ){
//					echo "<br />TRUE ->isLogado::" . get_class($cEntidad);
					$bRetorno =true;
				}else{
//					echo "<br />FALSE ->isLogado::" . get_class($cEntidad);
					$bRetorno =false;
				}
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
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_usuarios/Empresas_usuariosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_usuarios/Empresas_usuarios.php");

		$cEntidadDB	= new EmpresasDB($conConexion);  // Entidad DB
		$cEntidad	= new Empresas();  // Entidad
		$cEmpresas_usuariosDB	= new Empresas_usuariosDB($conConexion);  // Entidad DB
		$cEmpresas_usuarios	= new Empresas_usuarios();  // Entidad

		if (!empty($_POST['sTK']))
		{
			$cEntidad->setToken($_POST['sTK']);
			$cEntidadDB     = new EmpresasDB($conConexion);
			$cEntidad = $cEntidadDB->usuarioPorToken($cEntidad);

			if ($cEntidad->getIdEmpresa() != null &&
					$cEntidad->getIdEmpresa() != "" &&
					$cEntidad->getUsuario() != "" )
			{
//				echo "<br />SEGURIDAD::getUsuarioToken::Es una empresa";
				//continue;
			}else{
//				echo "<br />SEGURIDAD::getUsuarioToken::NOO Es una empresa";
				$cEmpresas_usuarios->setToken($_POST['sTK']);
				$cEmpresas_usuariosDB     = new Empresas_usuariosDB($conConexion);
				$cEntidad = $cEmpresas_usuariosDB->usuarioPorToken($cEmpresas_usuarios);
//				echo "<br />SEGURIDAD::getUsuarioToken::NOO Es una empresa despues";
			}
		}
		return $cEntidad;
	}

	function isUsuarioActivo($conConexion)
	{
		$bRetorno = false;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_usuarios/Empresas_usuariosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_usuarios/Empresas_usuarios.php");

		$cEntidadEmpresasDB	= new EmpresasDB($conConexion);  // Entidad DB
		$cEntidadEmpresas	= new Empresas();  // Entidad
		$cEmpresas_usuariosDB	= new Empresas_usuariosDB($conConexion);  // Entidad DB
		$cEmpresas_usuarios	= new Empresas_usuarios();  // Entidad

		$bRetorno = isLogado($conConexion);
		if ($bRetorno){
			$cEntidadEmpresas = getUsuarioToken($conConexion);
			if (get_class($cEntidadEmpresas) == "Empresas"){
				$cEmpresasDB = new EmpresasDB($conConexion);
				$bRetorno= $cEmpresasDB->isUsuarioActivo($cEntidadEmpresas);
			}else{
				$cEmpresas_usuarios = getUsuarioToken($conConexion);
				$cEmpresas_usuariosDB = new Empresas_usuariosDB($conConexion);
				$bRetorno= $cEmpresas_usuariosDB->isUsuarioActivo($cEmpresas_usuarios);
			}
		}
		return $bRetorno;
	}

	function getMenus($conConexion)
	{

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_usuarios/Empresas_usuariosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_usuarios/Empresas_usuarios.php");


		$cEntidadEmpresasDB	= new EmpresasDB($conConexion);  // Entidad DB
		$cEntidadEmpresas	= new Empresas();  // Entidad
		$cEmpresas_usuariosDB	= new Empresas_usuariosDB($conConexion);  // Entidad DB
		$cEmpresas_usuarios	= new Empresas_usuarios();  // Entidad

		$_usrEmpresa = true;

		$sRetorno = "";				//Menús con los resultados de las funcionalidades a las que tiene acceso
		$sSQL;

		if (!empty($_POST['sTK']))
		{
			$cEntidadEmpresas->setToken($_POST['sTK']);
			$cEntidadEmpresasDB     = new EmpresasDB($conConexion);
			$cEntidadEmpresas = getUsuarioToken($conConexion);
//			echo "<br>//-->>::" . get_class($cEntidadEmpresas);
			if (get_class($cEntidadEmpresas) == "Empresas"){
				$cEntidadEmpresas = $cEntidadEmpresasDB->usuarioPorToken($cEntidadEmpresas);
			}else{
				$_usrEmpresa = false;
				$cEmpresas_usuariosDB = new Empresas_usuariosDB($conConexion);
				$cEmpresas_usuarios = $cEmpresas_usuariosDB->usuarioPorToken($cEntidadEmpresas);
			}


			$vPerfiles = null;					//vector para obtener resultados de los perfiles por empresa
			$vFuncionalidades = null;				//vector para obtener resultados de las funcionalidades x perfil

			if ($_usrEmpresa){
				//Miramos si tiene perfiles asignados
	            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfilesDB.php");
	            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfiles.php");
		       	$cEntidadEmpresas_perfilesDB	= new Empresas_perfilesDB($conConexion);  // Entidad DB
	    		$cEntidadEmpresas_perfiles    	= new Empresas_perfiles();  // Entidad

				$cEntidadEmpresas_perfiles->setIdEmpresa($cEntidadEmpresas->getIdEmpresa());
				$vPerfiles = $conConexion->Execute($cEntidadEmpresas_perfilesDB->readLista($cEntidadEmpresas_perfiles));
			}else {
//				echo "<br />NUEVO ELSE--";
   				//Miramos los perfiles asignados al usuario empresa en Empresas_usuarios_perfiles
   				require_once(constant("DIR_WS_COM") . "Empresas_usuarios_perfiles/Empresas_usuarios_perfilesDB.php");
   				require_once(constant("DIR_WS_COM") . "Empresas_usuarios_perfiles/Empresas_usuarios_perfiles.php");
   				$cEntidadEmpresas_usuarios_perfilesDB	= new Empresas_usuarios_perfilesDB($conConexion);  // Entidad DB
   				$cEntidadEmpresas_usuarios_perfiles		= new Empresas_usuarios_perfiles();  // Entidad
//   				echo "<br>Empresa:" . $cEmpresas_usuarios->getIdEmpresa();
//   				echo "<br>Usuario:" . $cEmpresas_usuarios->getIdUsuario();
   				$cEntidadEmpresas_usuarios_perfiles->setIdEmpresa($cEmpresas_usuarios->getIdEmpresa());
   				$cEntidadEmpresas_usuarios_perfiles->setIdUsuario($cEmpresas_usuarios->getIdUsuario());
   				$sSQLUP = $cEntidadEmpresas_usuarios_perfilesDB->readLista($cEntidadEmpresas_usuarios_perfiles);
//   				echo "<br />-->***-->" . $sSQLUP;
   				$vPerfiles =$conConexion->Execute($sSQLUP);

        	}
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
			if (get_class($cEntidadEmpresas) == "Empresas"){
				$_EmpresaLogada = $cEntidadEmpresas->getIdEmpresa();
				$_Timezone			= $cEntidadEmpresas->getTimezone();
				$_Prepago				= $cEntidadEmpresas->getPrepago();
				$_PathLogo			= $cEntidadEmpresas->getPathLogo();
				$_Dongles				= $cEntidadEmpresas->getDongles();
				$sPuebasEmpresa = $cEntidadEmpresas->getIdsPruebas();
			}else{
				$_dataUSR="Empresa_usuario.php";
				$cEmpr = new Empresas();
				$cEmprDB = new EmpresasDB($conConexion);
				$_EmpresaLogada = $cEntidadEmpresas->getIdEmpresa();
				$cEmpr->setIdEmpresa($_EmpresaLogada);
				$cEmpr = $cEmprDB->readEntidad($cEmpr);
				$cEntidadEmpresas=$cEmpr;
				$_Timezone			= $cEmpr->getTimezone();
				$_Prepago				= $cEmpr->getPrepago();
				$_PathLogo			= $cEmpr->getPathLogo();
				$_Dongles				= $cEmpr->getDongles();
				$sPuebasEmpresa	= $cEmpr->getIdsPruebas();
			}
			//Miramos si tiene perfiles asignados
      require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfilesDB.php");
      require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfiles.php");
	    $cEntidadEmpresas_perfilesDB	= new Empresas_perfilesDB($conConexion);  // Entidad DB
    	$cEntidadEmpresas_perfiles    	= new Empresas_perfiles();  // Entidad
    	$cEntidadEmpresas_perfiles->setIdEmpresa($cEntidadEmpresas->getIdEmpresa());
			$vPerfiles = $conConexion->Execute($cEntidadEmpresas_perfilesDB->readLista($cEntidadEmpresas_perfiles));
			//miramos si nos ha llegado algun resultado
      if ($vPerfiles->RecordCount() <= 0){
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

	function excel_encrypt_payload(array $payload, string $key): string {
		$iv = random_bytes(12); // GCM IV recomendado: 96 bits
		$json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		$tag = '';
		$cipher = openssl_encrypt($json, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
		// token = iv (12) + tag (16) + cipher
		return rtrim(strtr(base64_encode($iv . $tag . $cipher), '+/', '-_'), '=');
	}

	function excel_decrypt_token(string $token, string $key): ?array {
		$bin = base64_decode(strtr($token, '-_', '+/'), true);
		if ($bin === false || strlen($bin) < 12 + 16) return null;
		$iv  = substr($bin, 0, 12);
		$tag = substr($bin, 12, 16);
		$cipher = substr($bin, 28);
		$json = openssl_decrypt($cipher, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
		if ($json === false) return null;
		$data = json_decode($json, true);
		return is_array($data) ? $data : null;
	}

	function excel_sign(string $token, string $hmacKey): string {
		return hash_hmac('sha256', $token, $hmacKey);
	}

	function excel_safe_equals(string $a, string $b): bool {
		return function_exists('hash_equals') ? hash_equals($a, $b) : $a === $b;
	}

	function is_safe_entity_name($nombre) {
		return (bool)preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $nombre);
	}

	function obtenerClavePlantilla(): ?string
	{
		static $cache = null;
		if ($cache !== null) return $cache;

		$incluyente = null;

		// 1) Intentar con el frame que incluye directamente este archivo
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		foreach ($trace as $frame) {
			if (!empty($frame['file']) && $frame['file'] !== __FILE__ && basename($frame['file']) !== 'SeguridadTemplate.php') {
				$incluyente = $frame['file'];
				break;
			}
		}

		// 2) Fallback: si no encontramos, probamos con el script principal
		if ($incluyente === null && !empty($_SERVER['SCRIPT_FILENAME'])) {
			$incluyente = $_SERVER['SCRIPT_FILENAME'];
		}

		if ($incluyente === null) {
			return $cache = null;
		}

		$base = basename($incluyente); // p.ej. "mntABC123sl.php"

		// 3) Extraer <clave> de "mnt<clave>sl.php"
		if (preg_match('/^mnt(?P<clave>.+)(?=l(?:[A-Za-z0-9_-]+)?\.php$)/i', $base, $m)) {
			return $cache = $m['clave'];
		}

		// 4) Si no coincide el patrón, no podemos inferir la clave
		return $cache = 'tabla_export';
	}
?>
