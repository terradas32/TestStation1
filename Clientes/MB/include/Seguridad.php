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

					if ($vFuncionalidades->RecordCount() <= 0 || $vFuncionalidades->RecordCount() > 1){
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
