<?php
require_once("../../Empresa/include/Configuracion.php");
require_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/lang/es.php");
define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
include(constant("DIR_ADODB") . 'adodb.inc.php');
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_usuarios/Empresas_usuariosDB.php");
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_usuarios/Empresas_usuarios.php");
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "/Utilidades.php");

include_once ('../../Empresa/include/conexion.php');

//Conexión a e-Cases
include_once ('../../Empresa/include/conexionECases.php');
//FIN Conexión a e-Cases
$cUtilidades	= new Utilidades();
$cEmpresasDB	= new EmpresasDB($conn);  // Entidad DB
$cEmpresas	= new Empresas();  // Entidad
$cEmpresas_usuariosDB	= new Empresas_usuariosDB($conn);  // Entidad DB
$cEmpresas_usuarios	= new Empresas_usuarios();  // Entidad

class WS_Service
{

	/**
    * Metodo que devuelve la session para un usuario válido.
    *
	* @param string $usuario
	* @param string $password
	* @return string
	*/
	public function login($usuario, $password)
	{
		global $cUtilidades;
		global $cEmpresasDB;
		global $cEmpresas;
		global $cEmpresas_usuariosDB;
		global $cEmpresas_usuarios;
		global $conn;
		$strMensaje="";
		$token = "";
		if (!empty($usuario) && !empty($password))
		{
			$cEmpresas->setUsuario($usuario);
			$cEmpresas->setPassword($password);
			$cEmpresas_usuarios->setUsuario($usuario);
			$cEmpresas_usuarios->setPassword($password);

	        $bEncontradoUsuario = $cUtilidades->chkChar($usuario);
	        $bEncontradoPassword = $cUtilidades->chkChar($password);

	        if (!$bEncontradoPassword && !$bEncontradoUsuario)
	        {
	        	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_accesos/Empresas_accesosDB.php");
            require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_accesos/Empresas_accesos.php");
	        	//Introducimos los intentos de acceso
						$cEmpresas_accesos = new Empresas_accesos();
						$cEmpresas_accesosDB = new Empresas_accesosDB($conn);
						$cEmpresas_accesos->setIP($_SERVER['REMOTE_ADDR']);
						$cEmpresas_accesos->setLogin($cEmpresas->getUsuario());
	        	$sqlEmpresas_accesos = $cEmpresas_accesosDB->readLista($cEmpresas_accesos);
	          $aEmpresas_accesos = $conn->Execute($sqlEmpresas_accesos);
		        if ($aEmpresas_accesos->RecordCount() >= 5){
		        	$strMensaje = constant("ERR_FORM_LOGIN");
		        	throw new SoapFault("MaxAccess", "Login:: " . $strMensaje);
						}
	        	if (empty($strMensaje))
	          {
		    			$cEmpresas_accesos->setUsuAlta("0");
		    			$cEmpresas_accesos->setUsuMod("0");

	    				$cEmpresas_accesosDB->insertar($cEmpresas_accesos);

	        		$rowUser = $cEmpresasDB->Login($cEmpresas);
							$_usrEmpresa = true;
							if (empty($rowUser["idEmpresa"])){
								//Miramos si en un usuario de los nuevos en Entidad Empresas_usuarios
								$rowUser = $cEmpresas_usuariosDB->Login($cEmpresas_usuarios);
								$_usrEmpresa = false;
	//        			echo "<br />NUEVO";
							}
	        		if (!empty($rowUser["usuario"]) && $rowUser["usuario"] == $usuario )
	        		{
								//Borramos los acceso erroneos anteriorres
		            $cEmpresas_accesos->setIP("");
								$cEmpresas_accesosDB->borrarPorLogin($cEmpresas_accesos);
								//Dejamos sólo este login
								$cEmpresas_accesos->setIP($_SERVER['REMOTE_ADDR']);
								$cEmpresas_accesos->setLogin($cEmpresas->getUsuario());
								$cEmpresas_accesosDB->insertar($cEmpresas_accesos);

								if ($_usrEmpresa){
			        			//Miramos si tiene perfiles asignados
			        			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfilesDB.php");
			        			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfiles.php");
			        			$cEmpresasUPDB	= new Empresas_perfilesDB($conn);  // Entidad DB
			        			$cEmpresasUP		= new Empresas_perfiles();  // Entidad
			        			$cEmpresasUP->setIdEmpresa($rowUser["idEmpresa"]);
			        			$sSQLUP = $cEmpresasUPDB->readLista($cEmpresasUP);
			        			$listaUP = $conn->Execute($sSQLUP);
			        			$i="0";
			        			$sUP = "";
			        			while ($arr = $listaUP->FetchRow()){
			        				$sUP .= "," . $arr["idPerfil"];
			        				$i++;
			        			}
								}else {
									//Miramos los perfiles asignados al usuario empresa en Empresas_usuarios_perfiles
	        				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_usuarios_perfiles/Empresas_usuarios_perfilesDB.php");
	        				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_usuarios_perfiles/Empresas_usuarios_perfiles.php");
	        				$cEntidadUPDB	= new Empresas_usuarios_perfilesDB($conn);  // Entidad DB
	        				$cEntidadUP		= new Empresas_usuarios_perfiles();  // Entidad
	        				$cEntidadUP->setIdEmpresa($rowUser["idEmpresa"]);
	        				$cEntidadUP->setIdUsuario($rowUser["idUsuario"]);
	        				$sSQLUP = $cEntidadUPDB->readLista($cEntidadUP);
	        				$listaUP = $conn->Execute($sSQLUP);
	        				$i="0";
	        				$sUP = "";
	        				while ($arr = $listaUP->FetchRow()){
	        					$sUP .= "," . $arr["idPerfil"];
	        					$i++;
	        				}
								}

	        			if ($i > 0){
	        				$sUP = substr($sUP,1);
	        				//Miramos las funcionalidades para el / los Perfiles.
	        				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles_funcionalidades/Empresas_perfiles_funcionalidadesDB.php");
	        				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_perfiles_funcionalidades/Empresas_perfiles_funcionalidades.php");
	        				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
	        				$comboFUNCIONALIDADES	= new Combo($conn,"fIdFuncionalidad","idFuncionalidad","url","url","wi_funcionalidades","",constant("SLC_OPCION"),"");
	        				$cEmpresasPFDB	= new Empresas_perfiles_funcionalidadesDB($conn);  // Entidad DB
	        				$cEmpresasPF		= new Empresas_perfiles_funcionalidades();  // Entidad
	        				$cEmpresasPF->setIdPerfil($sUP);
	        				$cEmpresasPF->setOrderBy("idFuncionalidad, modificar, borrar ASC");
	        				$sSQLPF = $cEmpresasPFDB->readLista($cEmpresasPF);
	        				$listaPF = $conn->Execute($sSQLPF);
	        				$i="0";
	        				$sPF = "";
	        				$aPF = array();
	        				$aFAcceso = array();
	        				$sIdFuncionalidad="";
	        				while ($arr = $listaPF->FetchRow()){
	        					$sPK = $comboFUNCIONALIDADES->getDescripcionCombo($arr["idFuncionalidad"]);
	        					$aPF[$sPK]["idFuncionalidad"] = $arr["idFuncionalidad"];
	        					$aPF[$sPK]["nombreFuncionalidad"] = $sPK;
	        					$aPF[$sPK]["modificar"] =   $arr["modificar"];
	        					$aPF[$sPK]["borrar"] =   $arr["borrar"];
	        					$aFAcceso[$i] = $arr["idFuncionalidad"];
	        					$i++;
	        				}
	        				if ($i > 0 ){
	        					$cEmpresas->setIdEmpresa($rowUser["idEmpresa"]);
										if (!$_usrEmpresa){
											$cEmpresas_usuarios->setIdEmpresa($rowUser["idEmpresa"]);
											$cEmpresas_usuarios->setIdUsuario($rowUser["idUsuario"]);
										}
										if (empty($rowUser["token"]))
										{
												$token =md5(uniqid('', true));
										}else{
											if ($rowUser["idEmpresa"] == "5136"){	//Grupo Tragsa 5136
	        							//Permitimos multisesión
	        							$token =$rowUser["token"];
	        						}else{
			                  $token =md5(uniqid('', true));
	        						}
	        					}
										$cEmpresas->setToken($token);
	                  $cEmpresas_usuarios->setToken($token);

	                  if ($_usrEmpresa){
	                  	$cEmpresasDB->ActualizaToken($cEmpresas);
	                  	//Actualizamos el último login
	                  	if ($cEntidadDB->ultimoLogin($cEmpresas) == false)
	                  	{
	                  			throw new SoapFault("LastAccess", "Login:: " . constant("ERR"));
	                  	}
	                  }else{
	                  	//Usuarios de una empresa NUEVO
	                  	$cEmpresas_usuariosDB->ActualizaToken($cEmpresas_usuarios);
	                  	//Actualizamos el último login
	                  	if ($cEmpresas_usuariosDB->ultimoLogin($cEmpresas_usuarios) == false)
	                  	{
	                  			throw new SoapFault("LastAccess", "Login:: " . constant("ERR"));
	                  	}
	                  }

	            		}else{
	        					$strMensaje = '* ' . constant("ERR_NO_AUTORIZADO");
	        					throw new SoapFault("AutAccess", "Login:: " . $strMensaje);
	        				}
	        			}else{
	        				$strMensaje = constant("ERR_NO_AUTORIZADO");
	        				throw new SoapFault("AutAccess", "Login:: " . $strMensaje);
	        			}
	        		}else {
	        			$strMensaje = constant("ERR_FORM_LOGIN");
	        			throw new SoapFault("MaxAccess", "Login:: " . $strMensaje);
	        		}
	    		}
	        }else{
	        	throw new SoapFault("ErrCaracter", "Login:: " . "Caracteres no permitidos en el login");
	        }
		}else{
	  		throw new SoapFault("FaltanDatos", "Login:: " . "Faltan datos");
	  }
		// $oggetto = new stdClass();
		// $oggetto->session = $token;
		// return $oggetto;
		//return new SoapVar('<session token="'.$token.'">'.$token.'</session>', XSD_ANYXML);
		//return new SoapVar($token, XSD_STRING, null, null, null );
		return new SoapVar($token, XSD_STRING);
		//return new SoapVar($token, XSD_STRING, null, null, 'token', 'http://framework.zend.com');
		//return $token;
	}

	/**
    * Metodo que devuelve las pruebas disponibles
    *
	* @param string $sesion
	* @param string $idioma <optional>
	* @return array
	*/
	public function listarPruebas($sesion, $idioma = '')
	{
		global $cUtilidades;
		global $cEmpresasDB;
		global $cEmpresas;
		global $cEmpresas_usuariosDB;
		global $cEmpresas_usuarios;
		global $conn;
		$strMensaje="";
		$retorno = array();
		$cEmpresas->setToken($sesion);
		$cEmpresas = $cEmpresasDB->usuarioPorToken($cEmpresas);

		if ($cEmpresas->getIdEmpresa() != "")
		{
			if ($cEmpresas->getIdsPruebas() != "")
			{
				$aEmpresaTest = explode(",", $cEmpresas->getIdsPruebas());
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
				$comboPRUEBASGROUP	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","","","bajaLog=0 AND listar=1","","idPrueba","");
				$cPruebasDB = new PruebasDB($conn);
				$k=0;
				for ($i = 0; $i < sizeof($aEmpresaTest); $i++){
					$cPruebas = new Pruebas();
					$cPruebas->setIdPrueba($aEmpresaTest[$i]);
					$cPruebas->setIdPruebaHast($aEmpresaTest[$i]);
					$cPruebas->setBajaLog(0);
					$cPruebas->setBajaLogHast(0);
					if (!empty($idioma)){
						$cPruebas->setCodIdiomaIso2($idioma);
					}
					$sSQL = $cPruebasDB->readLista($cPruebas);
					$rs = $conn->Execute($sSQL);
					while (!$rs->EOF)
					{
						$retorno[$k]['idPrueba'] = $rs->fields['idPrueba'];
						$retorno[$k]['nombre'] = $rs->fields['nombre'];
						$retorno[$k]['codIdiomaIso2'] = $rs->fields['codIdiomaIso2'];
						$k++;
						$rs->MoveNext();
					}
				}

			}else{
				throw new SoapFault("SinPruebas", "listarPruebas::" . "Sin pruebas asignadas.");
			}
	  	}else{
	  		throw new SoapFault("SessionOut", "listarPruebas::" . "Su sesión ha caducado");
	  	}
	    return $retorno;
	}

	/**
    * Metodo que devuelve las pruebas asignadas en una Convocatoria
    *
	* @param string $sesion
	* @param string $idConvocatoria
	* @return array
	*/
	public function listarPruebasConvocatoria($sesion, $idConvocatoria)
	{
		global $cUtilidades;
		global $cEmpresasDB;
		global $cEmpresas;
		global $cEmpresas_usuariosDB;
		global $cEmpresas_usuarios;
		global $conn;
		$strMensaje="";
		$retorno = array();
		$cEmpresas->setToken($sesion);
		$cEmpresas = $cEmpresasDB->usuarioPorToken($cEmpresas);

		if ($cEmpresas->getIdEmpresa() != "")
		{
			if ($cEmpresas->getIdsPruebas() != "")
			{
				if (!empty($idConvocatoria))
				{
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
					$cProceso_pruebasDB = new Proceso_pruebasDB($conn);
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
					$cPruebasDB = new PruebasDB($conn);

					$cProceso_pruebas = new Proceso_pruebas();
					$cProceso_pruebas->setIdProceso($idConvocatoria);
					$cProceso_pruebas->setIdEmpresa($cEmpresas->getIdEmpresa());

					$sql =  $cProceso_pruebasDB->readLista($cProceso_pruebas);
					$rsPP = $conn->Execute($sql);
					$k=0;
					$p=0;
					while (!$rsPP->EOF)
					{
							$cPruebas = new Pruebas();
							$cPruebas->setIdPrueba($rsPP->fields['idPrueba']);
							$cPruebas->setIdPruebaHast($rsPP->fields['idPrueba']);
							$cPruebas->setBajaLog(0);
							$cPruebas->setBajaLogHast(0);
							$cPruebas->setCodIdiomaIso2($PPrs->fields['codIdiomaIso2']);
							$sSQL = $cPruebasDB->readLista($cPruebas);
							$rsP = $conn->Execute($sSQL);
							while (!$rsP->EOF)
							{
								$retorno[$p]['idPrueba'] = $rsP->fields['idPrueba'];
								$retorno[$p]['idTipoPrueba'] = $rsP->fields['idTipoPrueba'];
								$retorno[$p]['nombre'] = $rsP->fields['nombre'];
								$retorno[$p]['codIdiomaIso2'] = $rsP->fields['codIdiomaIso2'];
								$p++;
								$rsP->MoveNext();
							}
							$rsPP->MoveNext();
					}
				}else{
					throw new SoapFault("FaltanDatos", "listarPruebasConvocatoria:: " . "Faltan datos");
				}
			}else{
				throw new SoapFault("SinPruebas", "listarPruebasConvocatoria::" . "Sin pruebas asignadas.");
			}
	  }else{
	  	throw new SoapFault("SessionOut", "listarPruebasConvocatoria::" . "Su sesión ha caducado");
	  }
	  return $retorno;
	}

	/**
    * Metodo que devuelve la lista de candidatos de una convocatoria
    *
	* @param string $sesion
	* @param int $idConvocatoria
	* @return array
	*/
	public function listarCandidatos($sesion, $idConvocatoria)
	{
		global $cUtilidades;
		global $cEmpresasDB;
		global $cEmpresas;
		global $cEmpresas_usuariosDB;
		global $cEmpresas_usuarios;
		global $conn;
		$strMensaje="";
		$retorno = array();
		$cEmpresas->setToken($sesion);
		$cEmpresas = $cEmpresasDB->usuarioPorToken($cEmpresas);

		if ($cEmpresas->getIdEmpresa() != "")
		{
			if (!empty($idConvocatoria))
			{
				//Consultamos los datos del proceso / convocatoria
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
				$cProcesosDB = new ProcesosDB($conn);
				$cProcesos = new Procesos();
				$cProcesos->setIdEmpresa($cEmpresas->getIdEmpresa());
				$cProcesos->setIdProceso($idConvocatoria);
				$cProcesos = $cProcesosDB->readEntidad($cProcesos);

				if ($cProcesos->getNombre() == "")
				{
					throw new SoapFault("ConvNoExist", "listarCandidatos:: " . "Convocatoria no existe");
				}

				//Sacamos todos los candidatos de la convocatoria
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
				$cCandidatos = new Candidatos();
				$cCandidatosDB = new CandidatosDB($conn);

				$cCandidatos->setIdEmpresa($cEmpresas->getIdEmpresa());
				$cCandidatos->setIdEmpresaHast($cEmpresas->getIdEmpresa());
				$cCandidatos->setIdProceso($idConvocatoria);
				$cCandidatos->setIdProcesoHast($idConvocatoria);
				$sql =  $cCandidatosDB->readLista($cCandidatos);
				$rs = $conn->Execute($sql);
				$k=0;
				while ($arr = $rs->FetchRow()){
					$retorno[$k] = $arr;
					$k++;
				}
			}else{
				throw new SoapFault("FaltanDatos", "listarCandidatos:: " . "Faltan datos");
			}
	  	}else{
	  		throw new SoapFault("SessionOut", "listarCandidatos::" . "Su sesión ha caducado");
	  	}
	    return $retorno;
	}

	/**
    * Metodo que devuelve las puntuaciones optenidas de un candidato en una prueba en una convocatoria.
    *
	* @param string $sesion
	* @param int $idConvocatoria
	* @param int $idPrueba
	* @param string $idCandidato
	* @return array
	*/
	public function listarPuntuaciones($sesion, $idConvocatoria, $idPrueba, $idCandidato)
	{

		global $cUtilidades;
		global $cEmpresasDB;
		global $cEmpresas;
		global $cEmpresas_usuariosDB;
		global $cEmpresas_usuarios;
		global $conn;
		$strMensaje="";
		$retorno = array();
		$cEmpresas->setToken($sesion);
		$cEmpresas = $cEmpresasDB->usuarioPorToken($cEmpresas);

		if ($cEmpresas->getIdEmpresa() != "")
		{
			if (!empty($idConvocatoria) && !empty($idPrueba) && !empty($idCandidato))
			{
				//Consultamos los datos del proceso / convocatoria
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
				$cProcesosDB = new ProcesosDB($conn);
				$cProcesos = new Procesos();
				$cProcesos->setIdEmpresa($cEmpresas->getIdEmpresa());
				$cProcesos->setIdProceso($idConvocatoria);
				$cProcesos = $cProcesosDB->readEntidad($cProcesos);

				if ($cProcesos->getNombre() == "")
				{
					throw new SoapFault("ConvNoExist", "listarPuntuaciones:: " . "Convocatoria no existe");
				}
				if ($cEmpresas->getIdsPruebas() == "")
				{
					throw new SoapFault("SinPruebas", "listarPuntuaciones::" . "Sin pruebas asignadas.");
				}
				//Sacamos los test disponibles para la empresa
				$aEmpresaTest = explode(",", $cEmpresas->getIdsPruebas());
				if (!in_array($idPrueba, $aEmpresaTest)){
					throw new SoapFault("ErrorTest", "listarPuntuaciones::" . "No existe el test " . $idPrueba);
				}
				//Sacamos los datos del candidato
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
				$cCandidatos = new Candidatos();
				$cCandidatosDB = new CandidatosDB($conn);

				$cCandidatos->setIdEmpresa($cEmpresas->getIdEmpresa());
				$cCandidatos->setIdEmpresaHast($cEmpresas->getIdEmpresa());
				$cCandidatos->setIdProceso($idConvocatoria);
				$cCandidatos->setIdProcesoHast($idConvocatoria);
				$cCandidatos->setIdCandidato($idCandidato);
				$cCandidatos->setIdCandidatoHast($idCandidato);
				$cCandidatos = $cCandidatosDB->readEntidad($cCandidatos);
				if ($cCandidatos->getNombre() == "")
				{
					throw new SoapFault("CandidatoNoExist", "listarPuntuaciones:: " . "Candidato no existe");
				}
				////////////
				//Miramos en que idioma ha realizado el candidato esa prueba
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
				$cRespuestas_pruebas = new Respuestas_pruebas();
				$cRespuestas_pruebasDB = new Respuestas_pruebasDB($conn);
				$cRespuestas_pruebas->setIdEmpresa($cEmpresas->getIdEmpresa());
				$cRespuestas_pruebas->setIdEmpresaHast($cEmpresas->getIdEmpresa());
				$cRespuestas_pruebas->setIdProceso($idConvocatoria);
				$cRespuestas_pruebas->setIdProcesoHast($idConvocatoria);
				$cRespuestas_pruebas->setIdPrueba($idPrueba);
				$cRespuestas_pruebas->setIdPruebaHast($idPrueba);
				$cRespuestas_pruebas->setIdCandidato($idCandidato);
				$cRespuestas_pruebas->setIdCandidatoHast($idCandidato);
				$cRespuestas_pruebas->setOrderBy("fecMod");
				$cRespuestas_pruebas->setOrder("ASC");
				$sSQL= $cRespuestas_pruebasDB->readLista($cRespuestas_pruebas);
				$rsRP = $conn->Execute($sSQL);
				$codIdiomaIso2 = "es";
				while (!$rsRP->EOF)
				{
					$codIdiomaIso2 = $rsRP->fields['codIdiomaIso2'];
					$rsRP->MoveNext();
				}
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
				$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
				$cRespuestas_pruebas_itemsDB = new Respuestas_pruebas_itemsDB($conn);
				$cRespuestas_pruebas_items->setIdEmpresa($cEmpresas->getIdEmpresa());
				$cRespuestas_pruebas_items->setIdEmpresaHast($cEmpresas->getIdEmpresa());
				$cRespuestas_pruebas_items->setIdProceso($idConvocatoria);
				$cRespuestas_pruebas_items->setIdProcesoHast($idConvocatoria);
				$cRespuestas_pruebas_items->setIdPrueba($idPrueba);
				$cRespuestas_pruebas_items->setIdPruebaHast($idPrueba);
				$cRespuestas_pruebas_items->setIdCandidato($idCandidato);
				$cRespuestas_pruebas_items->setIdCandidatoHast($idCandidato);
				$cRespuestas_pruebas_items->setIdCandidatoHast($idCandidato);
				$cRespuestas_pruebas_items->setCodIdiomaIso2($codIdiomaIso2);
				$cRespuestas_pruebas_items->setOrderBy("idEmpresa,idProceso,codIdiomaIso2,idPrueba,idCandidato,orden");
				$cRespuestas_pruebas_items->setOrder("ASC");
				$sSQL= $cRespuestas_pruebas_itemsDB->readLista($cRespuestas_pruebas_items);
				$rsRPI = $conn->Execute($sSQL);

				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/ItemsDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/Items.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/Opciones.php");

				$cOpcionesDB = new OpcionesDB($conn);
				$cItemsDB = new ItemsDB($conn);
				while (!$rsRPI->EOF)
				{
					$cOpciones	= new Opciones();  // Entidad
					$cOpciones->setCodIdiomaIso2($rsRPI->fields['codIdiomaIso2']);
					$cOpciones->setIdPrueba($rsRPI->fields['idPrueba']);
					$cOpciones->setIdItem($rsRPI->fields['idItem']);
					$cOpciones->setIdOpcion($rsRPI->fields['idOpcion']);
					$cOpciones = $cOpcionesDB->readEntidad($cOpciones);

					$cItems	= new Items();  // Entidad
					$cItems->setCodIdiomaIso2($rsRPI->fields['codIdiomaIso2']);
					$cItems->setIdPrueba($rsRPI->fields['idPrueba']);
					$cItems->setIdItem($rsRPI->fields['idItem']);
					$cItems = $cItemsDB->readEntidad($cItems);
					$sValor="";
					$sValor=$cUtilidades->getValorCalculadoPRUEBAS($rsRPI, $cItems, $conn);

					$rsRPI->MoveNext();
			    }

				//Inicializamos la puntuación directa y el percentil que más tarde transformaremos
				$iRespuestas = 0;
				$iPDirecta = 0;
				$iErrores = 0;
				$iPercentil = 0;
				$descPrueba = "";
				$iNumOpciones = 0;
				$iListaItemsPrueba=0;
				$rsRPI->MoveFirst();
				while (!$rsRPI->EOF)
				{

		        	if ($iRespuestas == 0){
		        		//RECOGEMOS EL NOMBRE
		        		$descPrueba = $rsRPI->fields['descPrueba'];
			        	//Miramos las opciones de un item
			        	$cOpciones	= new Opciones();  // Entidad
						$cOpciones->setCodIdiomaIso2($rsRPI->fields['codIdiomaIso2']);
						$cOpciones->setIdPrueba($rsRPI->fields['idPrueba']);
						$cOpciones->setIdItem($rsRPI->fields['idItem']);
						$sSQLOpciones = $cOpcionesDB->readLista($cOpciones);
						$rsOpciones = $conn->Execute($sSQLOpciones);
						$iNumOpciones = $rsOpciones->NumRows();

						$cItems	= new Items();  // Entidad
						$cItems->setCodIdiomaIso2($rsRPI->fields['codIdiomaIso2']);
						$cItems->setIdPrueba($rsRPI->fields['idPrueba']);
						$sSQLItems = $cItemsDB->readLista($cItems);
						$rsItems = $conn->Execute($sSQLItems);
			        	$iListaItemsPrueba= $rsItems->NumRows();
			        }


			        //Leemos el item para saber cual es la opción correcta
			        $cItems	= new Items();  // Entidad
					$cItems->setCodIdiomaIso2($rsRPI->fields['codIdiomaIso2']);
					$cItems->setIdPrueba($rsRPI->fields['idPrueba']);
					$cItems->setIdItem($rsRPI->fields['idItem']);
					$cItems = $cItemsDB->readEntidad($cItems);

					$cOpciones	= new Opciones();  // Entidad
					$cOpciones->setCodIdiomaIso2($rsRPI->fields['codIdiomaIso2']);
					$cOpciones->setIdPrueba($rsRPI->fields['idPrueba']);
					$cOpciones->setIdItem($rsRPI->fields['idItem']);
					$cOpciones->setIdOpcion($rsRPI->fields['idOpcion']);
					$cOpciones = $cOpcionesDB->readEntidad($cOpciones);

					//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
					if(strtoupper($cItems->getCorrecto()) == strtoupper($cOpciones->getCodigo()))
					{
						//echo $listaRespItems->fields['idItem'] . " - bien <br />";
						//Si coincide se le suma uno a la PDirecta.
							$iPDirecta++;
					}else{
							$iErrores++;
					}
					$iRespuestas++;
					$rsRPI->MoveNext();
				}

					//IR= Último ítem respondido por el candidato/Nº total de ítems de la prueba.
			        $IR = number_format($iRespuestas / $iListaItemsPrueba,2);
					$IP = number_format($iPDirecta/$iRespuestas ,2);
					$POR = number_format($IR*$IP ,2);
					//Como error son las no acertadas de las q ha respondido
					$ERRORES= $iErrores;
					$iPercentil=0;	//Pendiente cuando tengamos baremos
					$sPDF="";
					$sNombre = $cUtilidades->SEOTitulo($descPrueba . "_" . $cCandidatos->getNombre() . "_" . $cCandidatos->getApellido1() . "_" . $cCandidatos->getMail() . "_" . $cEmpresas->getIdEmpresa() . "_" . $idConvocatoria . "_" . "1" . "_" . 'es');
					$sDirImg="imgInformes/";
					$_ficheroPDF = constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sDirImg . $sNombre . ".pdf";
					if(is_file($_ficheroPDF)){
						$sPDF = file_get_contents($_ficheroPDF);
					}
					$retorno[0]['idProceso'] = $idConvocatoria;
					$retorno[0]['idPrueba'] = $idPrueba;
					$retorno[0]['idCandidato'] = $idCandidato;
					$retorno[0]['RESPUESTAS'] = $iRespuestas;
					$retorno[0]['PDIRECTA'] = $iPDirecta;
					$retorno[0]['ERRORES'] = $ERRORES;
					$retorno[0]['IR'] = $IR;
					$retorno[0]['IP'] = $IP;
					$retorno[0]['POR'] = $POR;
					$retorno[0]['PDF'] = base64_encode($sPDF);
					$retorno[0]['PTS'] = $iPDirecta-($ERRORES*(1/($iNumOpciones)));
					$retorno[0]['OPCIONES'] = $iNumOpciones;

			}else{
				throw new SoapFault("FaltanDatos", "listarPuntuaciones:: " . "Faltan datos");
			}
	  	}else{
	  		throw new SoapFault("SessionOut", "listarPuntuaciones::" . "Su sesión ha caducado");
	  	}

	    return $retorno;
	}

	/**
    * Metodo que inserta una nueva convocatoria y devuelve el id
    * Las fechas son en formato yyyy-mm-dd hh:mm
    * Los test son los id de las pruebas de TS, pueden ir separados por comas ej:(16,26,24)
    * Los langtest son los idiomas ISO2 para cada prueba de TS, pueden ir separados por comas ej:(es,en,es)
    * El  iModoRealizacion es la forma de envio de los datos de acceso al candidato:
    * 	1 - Continuo (envia los datos de acceso al candidato).
    *	2 - Administrado (envia los datos de acceso al correo de la empresa)
    *
	* @param string $sesion
	* @param string $nombre
	* @param string $fecInicio
	* @param string $fecFin
	* @param string $test
	* @param string $langtest
	* @param int	$iModoRealizacion
	* @return int
	*/
	public function insertarConvocatoria($sesion, $nombre, $fecInicio, $fecFin, $test, $langtest, $iModoRealizacion)
	{
		global $cUtilidades;
		global $cEmpresasDB;
		global $cEmpresas;
		global $cEmpresas_usuariosDB;
		global $cEmpresas_usuarios;
		global $conn;
		$strMensaje="";
		$retorno = array();
		$cEmpresas->setToken($sesion);
		$cEmpresas = $cEmpresasDB->usuarioPorToken($cEmpresas);

		$retorno = 0;

		if ($cEmpresas->getIdEmpresa() != "")
		{
			if (!empty($nombre) && !empty($fecInicio) && !empty($fecFin) && !empty($test) && !empty($langtest))
			{
				$aFecInicio = explode(" ", $fecInicio);
				$aFecFin = explode(" ", $fecFin);
				$ifecInicio = strtotime($fecInicio);
				$ifecFin = strtotime($fecFin);
				if (!is_int($ifecInicio) || !is_int($ifecFin)){
					throw new SoapFault("ErrorFechas", "insertarConvocatoria::" . "Fecha erronea");
				}
				if ((sizeof($aFecInicio) != 2) || (sizeof($aFecFin) != 2)){
					throw new SoapFault("ErrorFormato", "insertarConvocatoria::" . "Formato de fechas erroneo");
				}else{
					$aPart1Inicio = explode("-", $aFecInicio[0]);
					$aPart2Inicio = explode(":", $aFecInicio[1]);
					$aPart1Fin = explode("-", $aFecFin[0]);
					$aPart2Fin = explode(":", $aFecFin[1]);
					if ((sizeof($aPart1Inicio) != 3) || (sizeof($aPart1Fin) != 3)
						|| sizeof($aPart2Inicio) != 2 || (sizeof($aPart2Fin) != 2))
					{
						throw new SoapFault("ErrorFormato", "insertarConvocatoria::" . "Formato de fechas erroneo formato yyyy-mm-dd hh:mm");
					}
				}
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
				$cProcesosDB = new ProcesosDB($conn);
				$cProcesos = new Procesos();
				$cProcesos->setIdEmpresa($cEmpresas->getIdEmpresa());
				$cProcesos->setNombre($nombre);
				$cProcesos->setFechaInicio($fecInicio);
				$cProcesos->setFechaFin($fecFin);
				$cProcesos->setIdModoRealizacion($iModoRealizacion);
				$cProcesos->setUsuAlta($cEmpresas->getIdEmpresa());
				$cProcesos->setUsuMod($cEmpresas->getIdEmpresa());
				$newId	= $cProcesosDB->insertar($cProcesos);

				if (!empty($newId))
				{
					$cProcesos->setIdProceso($newId);

					$aTest = explode(",", $test);
					$aLangTest = explode(",", $langtest);

					//Sacamos los test disponibles para la empresa
					if ($cEmpresas->getIdsPruebas() != "")
					{
						$aEmpresaTest = explode(",", $cEmpresas->getIdsPruebas());

						for ($i = 0; $i < sizeof($aTest); $i++)
						{
							if (!in_array($aTest[$i], $aEmpresaTest)){
								//Borramos la convocatoria
								$this->borrarConvocatoria($sesion, $newId);
								throw new SoapFault("ErrorTest", "insertarConvocatoria::" . "No existe el test " . $aTest[$i]);
							}
						}
						//Chequeamos que para cada test tiene especificado el idioma
						if (sizeof($aLangTest) != sizeof($aTest)){
							$this->borrarConvocatoria($sesion, $newId);
							throw new SoapFault("ErrorTest", "insertarConvocatoria::" . "Especificque el idioma para cada test");
						}

						$sLangTestValidos = "es,en";
						$aLangTestValidos = explode(",", $sLangTestValidos);
						//Chequeamos que los idiomas introducidos son validos (es,en)
						for ($i = 0; $i < sizeof($aLangTest); $i++)
						{
							if (!in_array($aLangTest[$i], $aLangTestValidos)){
								$this->borrarConvocatoria($sesion, $newId);
								throw new SoapFault("ErrorTest", "insertarConvocatoria::" . "Idioma para el test " . $aTest[$i] . " invalido::" . $aLangTest[$i]);
							}
						}

						$codIdiomaIso2Prueba = "es";
						$CodIdiomaInforme = "es";
						$IdBaremo = 1;	//Baremo estandar
						$IdTipoInforme = 1;	//Estándar Ej NIPS

						//Insertamos los test
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
						$cProceso_pruebasDB = new Proceso_pruebasDB($conn);

						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
						$cProceso_baremosDB = new Proceso_baremosDB($conn);

						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
						$cProceso_informesDB = new Proceso_informesDB($conn);

						for ($i = 0; $i < sizeof($aTest); $i++)
						{
	//						if ($aTest[$i] == $sIdTest){
								$cProceso_pruebas = new Proceso_pruebas();
								$cProceso_pruebas->setIdProceso($newId);
								$cProceso_pruebas->setIdEmpresa($cEmpresas->getIdEmpresa());
								$cProceso_pruebas->setIdPrueba($aTest[$i]);
								$cProceso_pruebas->setCodIdiomaIso2($aLangTest[$i]);
								$cProceso_pruebas->setUsuAlta($cEmpresas->getIdEmpresa());
								$cProceso_pruebas->setUsuMod($cEmpresas->getIdEmpresa());
								$cProceso_pruebasDB->insertar($cProceso_pruebas);

								$cProceso_baremos = new Proceso_baremos();
								$cProceso_baremos->setIdProceso($newId);
								$cProceso_baremos->setIdEmpresa($cEmpresas->getIdEmpresa());
								$cProceso_baremos->setCodIdiomaIso2($aLangTest[$i]);
								$cProceso_baremos->setIdPrueba($aTest[$i]);
								$cProceso_baremos->setIdBaremo($IdBaremo);
								$cProceso_baremos->setUsuAlta($cEmpresas->getIdEmpresa());
								$cProceso_baremos->setUsuMod($cEmpresas->getIdEmpresa());
								$cProceso_baremosDB->insertar($cProceso_baremos);

								$cProceso_informes = new Proceso_informes();
								$cProceso_informes->setIdProceso($newId);
								$cProceso_informes->setIdEmpresa($cEmpresas->getIdEmpresa());

								$cProceso_informes->setIdPrueba($aTest[$i]);
								$cProceso_informes->setIdBaremo($IdBaremo);
								$cProceso_informes->setCodIdiomaIso2($aLangTest[$i]);
								$cProceso_informes->setCodIdiomaInforme($CodIdiomaInforme);
								$cProceso_informes->setIdTipoInforme($IdTipoInforme);
								$cProceso_informes->setUsuAlta($cEmpresas->getIdEmpresa());
								$cProceso_informes->setUsuMod($cEmpresas->getIdEmpresa());
								$cProceso_informesDB->insertar($cProceso_informes);
//							}
						}
						$retorno = $newId;
					}else{
						throw new SoapFault("SinPruebas", "insertarConvocatoria::" . "Sin pruebas asignadas.");
					}
				}else{
					throw new SoapFault("SqlError", "insertarConvocatoria:: Error SQL");
				}
			}else{
				throw new SoapFault("FaltanDatos", "insertarConvocatoria:: " . "Faltan datos");
			}
	  	}else{
	  		throw new SoapFault("SessionOut", "insertarConvocatoria::" . "Su sesión ha caducado");
	  	}
	    return $retorno;
	}

	/**
    * Metodo que Modifica los datos básicos de una convocatoria
    * Las fechas son en formato yyyy-mm-dd hh:mm
    * Los test son los id de las pruebas de TS, pueden ir separados por comas ej:(16,26,24)
    * Los langtest son los idiomas ISO2 para cada prueba de TS, pueden ir separados por comas ej:(es,en,es)
    *
	* @param string $sesion
	* @param int $idConvocatoria
	* @param string $nombre <optional>
	* @param string $fecInicio <optional>
	* @param string $fecFin <optional>
	* @param string $test <optional>
	* @param string $langtest <optional>
	* @return boolean
	*/
	public function modificarConvocatoria($sesion, $idConvocatoria, $nombre = "" , $fecInicio = "", $fecFin = "", $test = "", $langtest = "")
	{
		global $cUtilidades;
		global $cEmpresasDB;
		global $cEmpresas;
		global $cEmpresas_usuariosDB;
		global $cEmpresas_usuarios;
		global $conn;
		$strMensaje="";
		$cEmpresas->setToken($sesion);
		$cEmpresas = $cEmpresasDB->usuarioPorToken($cEmpresas);

		$retorno = false;

		if ($cEmpresas->getIdEmpresa() != "")
		{
			if (!empty($idConvocatoria))
			{
				//Consultamos los datos del proceso / convocatoria
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
				$cProcesosDB = new ProcesosDB($conn);
				$cProcesos = new Procesos();
				$cProcesos->setIdEmpresa($cEmpresas->getIdEmpresa());
				$cProcesos->setIdProceso($idConvocatoria);
				$cProcesos = $cProcesosDB->readEntidad($cProcesos);

				if ($cProcesos->getNombre() != "")
				{
					if (!empty($nombre) || !empty($fecInicio) || !empty($fecFin) || !empty($test) || !empty($langtest))
					{
						//Comprobamos que no tiene candidatos informados
						//En ese caso no permitimos la modificación de ningun dato.
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
						$cCandidatos = new Candidatos();
						$cCandidatosDB = new CandidatosDB($conn);

						$cCandidatos->setIdEmpresa($cEmpresas->getIdEmpresa());
						$cCandidatos->setIdProceso($idConvocatoria);
						$sqlInformados =  $cCandidatosDB->readListaInformados($cCandidatos);
						$listaInformados = $conn->Execute($sqlInformados);

						if($listaInformados->recordCount() > 0)
						{
							throw new SoapFault("ErrorInformados", "modificarConvocatoria::" . "No se ha podido realizar la operación, ya hay candidatos informados");
						}

						if (!empty($nombre)){
							$cProcesos->setNombre($nombre);
						}
						if (!empty($fecInicio)){
							$aFecInicio = explode(" ", $fecInicio);
							$ifecInicio = strtotime($fecInicio);
							if (!is_int($ifecInicio)){
								throw new SoapFault("ErrorFechas", "modificarConvocatoria::" . "Fecha erronea");
							}
							if ((sizeof($aFecInicio) != 2)){
								throw new SoapFault("ErrorFormato", "modificarConvocatoria::" . "Formato de fechas erroneo");
							}else{
								$aPart1Inicio = explode("-", $aFecInicio[0]);
								$aPart2Inicio = explode(":", $aFecInicio[1]);
								if ((sizeof($aPart1Inicio) != 3) || sizeof($aPart2Inicio) != 2 )
								{
									throw new SoapFault("modificarConvocatoria", "Formato de fechas erroneo formato yyyy-mm-dd hh:mm");
								}
							}
							$cProcesos->setFechaInicio($fecInicio);
						}
						if (!empty($fecFin)){
							$aFecFin = explode(" ", $fecFin);
							$ifecFin = strtotime($fecFin);
							if (!is_int($ifecFin)){
								throw new SoapFault("ErrorFechas", "modificarConvocatoria::" . "Fecha erronea");
							}
							if ((sizeof($aFecFin) != 2)){
								throw new SoapFault("ErrorFormato", "modificarConvocatoria::" . "Formato de fechas erroneo");
							}else{
								$aPart1Fin = explode("-", $aFecFin[0]);
								$aPart2Fin = explode(":", $aFecFin[1]);
								if ((sizeof($aPart1Fin) != 3) || (sizeof($aPart2Fin) != 2))
								{
									throw new SoapFault("modificarConvocatoria", "Formato de fechas erroneo formato yyyy-mm-dd hh:mm");
								}
							}
							$cProcesos->setFechaFin($fecFin);
						}

						if (!empty($test) && !empty($langtest))
						{
							$aTest = explode(",", $test);
							$aLangTest = explode(",", $langtest);

							//Sacamos los test disponibles para la empresa
							if ($cEmpresas->getIdsPruebas() != "")
							{
								$aEmpresaTest = explode(",", $cEmpresas->getIdsPruebas());

								for ($i = 0; $i < sizeof($aTest); $i++)
								{
									if (!in_array($aTest[$i], $aEmpresaTest)){
										throw new SoapFault("ErrorTest", "modificarConvocatoria::" . "No existe el test " . $aTest[$i]);
									}
								}
								//Chequeamos que para cada test tiene especificado el idioma
								if (sizeof($aLangTest) != sizeof($aTest)){
									throw new SoapFault("ErrorTest", "modificarConvocatoria::" . "Especificque el idioma para cada test");
								}

								$sLangTestValidos = "es,en";
								$aLangTestValidos = explode(",", $sLangTestValidos);
								//Chequeamos que los idiomas introducidos son validos (es,en)
								for ($i = 0; $i < sizeof($aLangTest); $i++)
								{
									if (!in_array($aLangTest[$i], $aLangTestValidos)){
										throw new SoapFault("ErrorTest", "modificarConvocatoria::" . "Idioma para el test " . $aTest[$i] . " invalido::" . $aLangTest[$i]);
									}
								}

								$codIdiomaIso2Prueba = "es";
								$CodIdiomaInforme = "es";
								$IdBaremo = 1;	//Baremo estandar
								$IdTipoInforme = 1;	//Estándar Ej NIPS

								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
								$cProceso_pruebasDB = new Proceso_pruebasDB($conn);

								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
								$cProceso_baremosDB = new Proceso_baremosDB($conn);

								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
								$cProceso_informesDB = new Proceso_informesDB($conn);

								//Borramos los test viejos del proceso
								$cProceso_pruebas = new Proceso_pruebas();
								$cProceso_pruebas->setIdProceso($idConvocatoria);
								$cProceso_pruebas->setIdEmpresa($cEmpresas->getIdEmpresa());
								$cProceso_pruebasDB->borrar($cProceso_pruebas);

								$cProceso_baremos = new Proceso_baremos();
								$cProceso_baremos->setIdProceso($idConvocatoria);
								$cProceso_baremos->setIdEmpresa($cEmpresas->getIdEmpresa());
								$cProceso_baremosDB->borrar($cProceso_baremos);

								$cProceso_informes = new Proceso_informes();
								$cProceso_informes->setIdProceso($idConvocatoria);
								$cProceso_informes->setIdEmpresa($cEmpresas->getIdEmpresa());
								$cProceso_informesDB->borrar($cProceso_informes);

								//Insertamos los test
								for ($i = 0; $i < sizeof($aTest); $i++)
								{
									$cProceso_pruebas = new Proceso_pruebas();
									$cProceso_pruebas->setIdProceso($idConvocatoria);
									$cProceso_pruebas->setIdEmpresa($cEmpresas->getIdEmpresa());
									$cProceso_pruebas->setIdPrueba($aTest[$i]);
									$cProceso_pruebas->setCodIdiomaIso2($aLangTest[$i]);
									$cProceso_pruebas->setUsuAlta($cEmpresas->getIdEmpresa());
									$cProceso_pruebas->setUsuMod($cEmpresas->getIdEmpresa());
									$cProceso_pruebasDB->insertar($cProceso_pruebas);

									$cProceso_baremos = new Proceso_baremos();
									$cProceso_baremos->setIdProceso($idConvocatoria);
									$cProceso_baremos->setIdEmpresa($cEmpresas->getIdEmpresa());
									$cProceso_baremos->setCodIdiomaIso2($aLangTest[$i]);
									$cProceso_baremos->setIdPrueba($aTest[$i]);
									$cProceso_baremos->setIdBaremo($IdBaremo);
									$cProceso_baremos->setUsuAlta($cEmpresas->getIdEmpresa());
									$cProceso_baremos->setUsuMod($cEmpresas->getIdEmpresa());
									$cProceso_baremosDB->insertar($cProceso_baremos);

									$cProceso_informes = new Proceso_informes();
									$cProceso_informes->setIdProceso($idConvocatoria);
									$cProceso_informes->setIdEmpresa($cEmpresas->getIdEmpresa());
									$cProceso_informes->setIdPrueba($aTest[$i]);
									$cProceso_informes->setIdBaremo($IdBaremo);
									$cProceso_informes->setCodIdiomaIso2($aLangTest[$i]);
									$cProceso_informes->setCodIdiomaInforme($CodIdiomaInforme);
									$cProceso_informes->setIdTipoInforme($IdTipoInforme);
									$cProceso_informes->setUsuAlta($cEmpresas->getIdEmpresa());
									$cProceso_informes->setUsuMod($cEmpresas->getIdEmpresa());
									$cProceso_informesDB->insertar($cProceso_informes);
								}
							}else{
								throw new SoapFault("SinPruebas", "modificarConvocatoria::" . "Sin pruebas asignadas.");
							}
						}
						$cProcesos->setUsuAlta($cEmpresas->getIdEmpresa());
						$cProcesos->setUsuMod($cEmpresas->getIdEmpresa());
						$retorno = $cProcesosDB->modificar($cProcesos);
					}else{
						throw new SoapFault("FaltanDatos", "modificarConvocatoria:: " . "No ha especificado datos  para cambiar");
					}
				}else{
					throw new SoapFault("ConvNoExist", "modificarConvocatoria:: " . "Convocatoria no existe");
				}
			}else{
				throw new SoapFault("FaltanDatos", "modificarConvocatoria:: " . "Faltan datos");
			}
		}else{
	  		throw new SoapFault("SessionOut", "modificarConvocatoria::" . "Su sesión ha caducado");
	  	}
	    return $retorno;
	}

	/**
    * Metodo que Borra una convocatoria y todo lo asignado a la misma
    *
	* @param string $sesion
	* @param int $idConvocatoria
	* @return boolean
	*/
	public function borrarConvocatoria($sesion, $idConvocatoria)
	{

		global $cUtilidades;
		global $cEmpresasDB;
		global $cEmpresas;
		global $cEmpresas_usuariosDB;
		global $cEmpresas_usuarios;
		global $conn;
		$strMensaje="";
		$cEmpresas->setToken($sesion);
		$cEmpresas = $cEmpresasDB->usuarioPorToken($cEmpresas);

		$retorno = false;

		if ($cEmpresas->getIdEmpresa() != "")
		{
			if (!empty($idConvocatoria))
			{
				//Consultamos los datos del proceso / convocatoria
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
				$cProcesosDB = new ProcesosDB($conn);
				$cProcesos = new Procesos();
				$cProcesos->setIdEmpresa($cEmpresas->getIdEmpresa());
				$cProcesos->setIdProceso($idConvocatoria);
				$cProcesos = $cProcesosDB->readEntidad($cProcesos);

				if ($cProcesos->getNombre() != "")
				{
					//Comprobamos que no tiene candidatos informados
					//En ese caso no permitimos el borrado.
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
					$cCandidatos = new Candidatos();
					$cCandidatosDB = new CandidatosDB($conn);

					$cCandidatos->setIdEmpresa($cEmpresas->getIdEmpresa());
					$cCandidatos->setIdProceso($idConvocatoria);
					$sqlInformados =  $cCandidatosDB->readListaInformados($cCandidatos);
					$listaInformados = $conn->Execute($sqlInformados);

					if($listaInformados->recordCount() > 0)
					{
						throw new SoapFault("ErrorInformados", "borrarConvocatoria::" . "No se ha podido realizar la operación, ya hay candidatos informados");
					}
					//Borramos todas las posibles asignaciones con el proceso
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
					$cProceso_informes = new Proceso_informes();
					$cProceso_informesDB = new Proceso_informesDB($conn);
					$cProceso_informes->setIdEmpresa($cEmpresas->getIdEmpresa());
					$cProceso_informes->setIdProceso($idConvocatoria);
					if ($cProceso_informesDB->borrar($cProceso_informes))
					{
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
						$cProceso_baremos = new Proceso_baremos();
						$cProceso_baremosDB = new Proceso_baremosDB($conn);
						$cProceso_baremos->setIdEmpresa($cEmpresas->getIdEmpresa());
						$cProceso_baremos->setIdProceso($idConvocatoria);
						if ($cProceso_baremosDB->borrar($cProceso_baremos))
						{
							require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
							require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
							$cProceso_pruebas = new Proceso_pruebas();
							$cProceso_pruebasDB = new Proceso_pruebasDB($conn);
							$cProceso_pruebas->setIdEmpresa($cEmpresas->getIdEmpresa());
							$cProceso_pruebas->setIdProceso($idConvocatoria);
							if ($cProceso_pruebasDB->borrar($cProceso_pruebas))
							{
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
								$cCandidatos = new Candidatos();
								$cCandidatosDB = new CandidatosDB($conn);
								$cCandidatos->setIdEmpresa($cEmpresas->getIdEmpresa());
								$cCandidatos->setIdProceso($idConvocatoria);
								if ($cCandidatosDB->borrar($cCandidatos))
								{
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Correos_proceso/Correos_procesoDB.php");
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Correos_proceso/Correos_proceso.php");
									$cCorreos_proceso = new Correos_proceso();
									$cCorreos_procesoDB = new Correos_procesoDB($conn);
									$cCorreos_proceso->setIdEmpresa($cEmpresas->getIdEmpresa());
									$cCorreos_proceso->setIdProceso($idConvocatoria);
									if ($cCorreos_procesoDB->borrar($cCorreos_proceso))
									{
										//Borramos el registro de la Entidad.
										$cProcesos->setUsuAlta($cEmpresas->getIdEmpresa());
										$cProcesos->setUsuMod($cEmpresas->getIdEmpresa());
										if ($cProcesosDB->borrar($cProcesos))
										{
											$retorno = true;
										}else{
											throw new SoapFault("ErrorProceso", "borrarConvocatoria:: " . "Procesos:: 411 error");
										}
									}else{
										throw new SoapFault("ErrorProceso", "borrarConvocatoria:: " . "Correos_proceso:: 411 error");
									}
								}else{
									throw new SoapFault("ErrorProceso", "borrarConvocatoria:: " . "Candidatos:: 411 error");
								}
							}else{
								throw new SoapFault("ErrorProceso", "borrarConvocatoria:: " . "Proceso_pruebas:: 411 error");
							}
						}else{
							throw new SoapFault("ErrorProceso", "borrarConvocatoria:: " . "Proceso_baremos:: 411 error");
						}
					}else{
						throw new SoapFault("ErrorProceso", "borrarConvocatoria:: " . "Proceso_informes:: 411 error");
					}
				}else{
					throw new SoapFault("ConvNoExist", "borrarConvocatoria:: " . "Convocatoria no existe");
				}
			}else{
				throw new SoapFault("FaltanDatos", "borrarConvocatoria:: " . "Faltan datos");
			}
		}else{
	  		throw new SoapFault("SessionOut", "borrarConvocatoria::" . "Su sesión ha caducado");
	  	}
	    return $retorno;

	}

	/**
    * Metodo que Asigna un candidato a una convocatoria
    *
	* @param string $sesion
	* @param int $idConvocatoria
	* @param string $mail
	* @param string $nombre
	* @param string $apellido1
	* @param string $apellido2 <optional>
	* @param string $dni <optional>
	* @param int $idTratamiento <optional>
	* Tratamientos
	*	1 => 'Sr.'
	*	2 => 'Sra.'
	* @return int idCandidato
	*/
	public function AltaCandidatoConv($sesion, $idConvocatoria, $nombre, $mail, $apellido1, $apellido2="", $dni="", $idTratamiento="")
	{
		global $cUtilidades;
		global $cEmpresasDB;
		global $cEmpresas;
		global $cEmpresas_usuariosDB;
		global $cEmpresas_usuarios;
		global $conn;
		$strMensaje="";
		$cEmpresas->setToken($sesion);
		$cEmpresas = $cEmpresasDB->usuarioPorToken($cEmpresas);

		$retorno = -1;

		if ($cEmpresas->getIdEmpresa() != "")
		{
			if (!empty($idConvocatoria))
			{
				//Consultamos los datos del proceso / convocatoria
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
				$cProcesosDB = new ProcesosDB($conn);
				$cProcesos = new Procesos();
				$cProcesos->setIdEmpresa($cEmpresas->getIdEmpresa());
				$cProcesos->setIdProceso($idConvocatoria);
				$cProcesos = $cProcesosDB->readEntidad($cProcesos);

				if ($cProcesos->getNombre() != "")
				{
					if (!empty($nombre) && !empty($apellido1) && !empty($mail))
					{
						//Comprobamos que no está ya dado de alta el correo
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
						$cCandidatos = new Candidatos();
						$cCandidatosDB = new CandidatosDB($conn);

						$cCandidatos->setIdEmpresa($cEmpresas->getIdEmpresa());
						$cCandidatos->setIdProceso($idConvocatoria);
						$cCandidatos->setMail($mail);
						$cCandidatos =  $cCandidatosDB->consultaPorMail($cCandidatos);

						if($cCandidatos->getIdCandidato() != "")
						{
							throw new SoapFault("ErrorAsignado", "AltaCandidatoConv::" . "El candidato ya está asignado a esta convocatoria");
						}

						$cCandidatos->setNombre($nombre);
						$cCandidatos->setApellido1($apellido1);
						$cCandidatos->setMail($mail);
						if (!empty($apellido2)){
							$cCandidatos->setApellido2($apellido2);
						}
						if (!empty($dni)){
							$cCandidatos->setDni($dni);
						}
						if (!empty($idTratamiento)){
							$cCandidatos->setIdTratamiento($idTratamiento);
						}
						$retorno = $cCandidatosDB->insertar($cCandidatos);

					}else{
						throw new SoapFault("FaltanDatos", "AltaCandidatoConv:: " . "Faltan datos");
					}
				}else{
					throw new SoapFault("ConvNoExist", "AltaCandidatoConv:: " . "Convocatoria no existe");
				}
			}else{
				throw new SoapFault("FaltanDatos", "AltaCandidatoConv:: " . "Faltan datos");
			}
		}else{
	  		throw new SoapFault("SessionOut", "AltaCandidatoConv::" . "Su sesión ha caducado");
	  	}
	    return $retorno;
	}

	/**
    * Metodo que Modifica los datos de un candidato asignado a una convocatoria
    *
	* @param string $sesion
	* @param int $idConvocatoria
	* @param int $idCandidato
	* @param string $nombre <optional>
	* @param string $mail <optional>
	* @param string $apellido1 <optional>
	* @param string $apellido2 <optional>
	* @param string $dni <optional>
	* @param int $idTratamiento <optional>
	* @param string $Pass <optional>
	* @return boolean
	*/
	public function ModificarCandidatoConv($sesion, $idConvocatoria, $idCandidato, $nombre, $mail, $apellido1, $apellido2, $dni, $idTratamiento, $Pass)
	{

		global $cUtilidades;
		global $cEmpresasDB;
		global $cEmpresas;
		global $cEmpresas_usuariosDB;
		global $cEmpresas_usuarios;
		global $conn;
		global $connECases;
		$strMensaje="";
		$cEmpresas->setToken($sesion);
		$cEmpresas = $cEmpresasDB->usuarioPorToken($cEmpresas);

		$retorno = false;
		if ($cEmpresas->getIdEmpresa() != "")
		{
			if (!empty($idConvocatoria))
			{
				//Consultamos los datos del proceso / convocatoria
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
				$cProcesosDB = new ProcesosDB($conn);
				$cProcesos = new Procesos();
				$cProcesos->setIdEmpresa($cEmpresas->getIdEmpresa());
				$cProcesos->setIdProceso($idConvocatoria);
				$cProcesos = $cProcesosDB->readEntidad($cProcesos);

				if ($cProcesos->getNombre() != "")
				{
					if (!empty($idCandidato))
					{
						//Comprobamos que esté asignado a la convocatoria
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
						$cCandidatos = new Candidatos();
						$cCandidatosDB = new CandidatosDB($conn);

						$cCandidatos->setIdEmpresa($cEmpresas->getIdEmpresa());
						$cCandidatos->setIdProceso($idConvocatoria);
						$cCandidatos->setIdCandidato($idCandidato);
						$cCandidatos =  $cCandidatosDB->readEntidad($cCandidatos);

						if($cCandidatos->getNombre() == "")
						{
							throw new SoapFault("ErrorAsignado", "ModificarCandidatoConv::" . "El candidato No está asignado a esta convocatoria");
						}
						//Comprobamos que esté ya Informado
//						if($cCandidatos->getInformado() == 1)
//						{
//							throw new SoapFault("ErrorInformado", "ModificarCandidatoConv::" . "El candidato ya ha sido informado, no se realizar la operación seleccionada");
//						}
						if (!empty($nombre)){
							$cCandidatos->setNombre($nombre);
						}
						if (!empty($apellido1)){
							$cCandidatos->setApellido1($apellido1);
						}
						if (!empty($mail)){
							$cCandidatos->setMail($mail);
						}
						if (!empty($apellido2)){
							$cCandidatos->setApellido2($apellido2);
						}
						if (!empty($dni)){
							$cCandidatos->setDni($dni);
						}
						if (!empty($idTratamiento)){
							$cCandidatos->setIdTratamiento($idTratamiento);
						}
						//Seteando estos metodos a vacio no los modifica
						if (!empty($Pass)){
							$cCandidatos->setPassword($Pass);
							$cCandidatos->setInformado("1");
							$token =md5(uniqid('', true));
							$cCandidatos->setToken($token);
							$cCandidatosDB->ActualizaToken($cCandidatos);
						}else {
							$cCandidatos->setPassword("");
							$cCandidatos->setInformado("");
						}
						////
						$cCandidatos->setUsuAlta($cEmpresas->getIdEmpresa());
						$cCandidatos->setUsuMod($cEmpresas->getIdEmpresa());
						$retorno = $cCandidatosDB->modificar($cCandidatos);
						if ($retorno){
							//Miro si tiene pruebas de e-cases en la bateria
							require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
							require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
							require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
							require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
							$cProceso_pruebasDB = new Proceso_pruebasDB($conn);

							//Sacamos todas las pruebas del proceso
							$cProceso_pruebas = new Proceso_pruebas();
							$cProceso_pruebas->setIdProceso($idConvocatoria);
							$cProceso_pruebas->setIdEmpresa($cEmpresas->getIdEmpresa());
							$sSQLPP = $cProceso_pruebasDB->readLista($cProceso_pruebas);
							$rsPP = $conn->Execute($sSQLPP);
							$idsPruebas="";
							if ($rsPP->NumRows() > 0){
								$_iCaseUsuarioIdioma=1;
								while (!$rsPP->EOF)
								{
									//Recogemos los id's de las pruebas
					    			$idsPruebas.="," . $rsPP->fields['idPrueba'];
					    			$cPru = new Pruebas();
					    			$cPruDB = new PruebasDB($conn);
					    			$cPru->setIdPrueba($rsPP->fields['idPrueba']);
					    			$cPru->setIdPruebaHast($rsPP->fields['idPrueba']);
                    $cPru->setCodIdiomaIso2($rsPP->fields['codIdiomaIso2']);
					    			$cPru->setBajaLog(0);
					    			$cPru->setBajaLogHast(0);
					    			$sSQLPru = $cPruDB->readLista($cPru);
					    			$rsPru = $conn->Execute($sSQLPru);
					    			while (!$rsPru->EOF)
					    			{
					    				if ($rsPru->fields['idTipoPrueba'] == "17")
					    				{
					    					if ($rsPP->fields['codIdiomaIso2'] == "en"){
					    						$_iCaseUsuarioIdioma=2;
					    						break;
					    					}
					    				}
					    				$rsPru->MoveNext();
					    			}
									$rsPP->MoveNext();
								}
								if (!empty($idsPruebas)){
									$idsPruebas = substr($idsPruebas, 1);
									$aIdsPruebas = explode(",", $idsPruebas);
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
									$comboPRUEBASGROUP	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","","","bajaLog=0 AND listar=1","","idPrueba","");
									$cPruebasDB = new PruebasDB($conn);
									for ($i = 0; $i < sizeof($aIdsPruebas); $i++){
										$cPruebas = new Pruebas();
										$cPruebas->setIdPrueba($aIdsPruebas[$i]);
										$cPruebas->setIdPruebaHast($aIdsPruebas[$i]);
										$cPruebas->setBajaLog(0);
										$cPruebas->setBajaLogHast(0);
										$sSQL = $cPruebasDB->readLista($cPruebas);
										$rs = $conn->Execute($sSQL);
										while (!$rs->EOF)
										{
											if ($rs->fields['idTipoPrueba'] == "17")
											{
												//Miramos si la simulación existe y sacamos el id de cliente
												$sSQLECases = "SELECT * FROM simulacion WHERE nombre=" . $connECases->qstr($rs->fields['codigo'], false);
												$rsCeCases = $connECases->Execute($sSQLECases);
												$_iCaseCliente=0;	//Default People
												$_iCaseSimulacion=0;
												if ($rsCeCases->NumRows() > 0){
													while (!$rsCeCases->EOF){
										    			$_iCaseCliente =$rsCeCases->fields['cliente_id'];
										    			$_iCaseSimulacion=$rsCeCases->fields['id'];
														$rsCeCases->MoveNext();
													}
												}
												//Miramos si ya está dado de alta
												$sSQLECases = "SELECT * FROM users WHERE email=" . $connECases->qstr($cCandidatos->getMail(), false);
												$rsCeCases = $connECases->Execute($sSQLECases);
												if (function_exists('password_hash')) {
											        // php >= 5.5
											        $sPass = password_hash($Pass, PASSWORD_BCRYPT);
											    } else {
											        $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
											        $salt = base64_encode($salt);
											        $salt = str_replace('+', '.', $salt);
											        $sPass = crypt($Pass, '$2y$10$' . $salt . '$');
												}
												$sTK=$cUtilidades->quickRandom(60);
												$idUsrCase=0;
												if ($rsCeCases->NumRows() > 0)
												{
													//Existe, le cambiamos la Pass y el idioma por que es con el que tiene que realizar la simulación
													$sSQLECases = 'UPDATE users SET
													password=' . $connECases->qstr($sPass, false) . '
													,alternativo=' . $connECases->qstr($sTK, false) . '
													,id_idiom=' . $connECases->qstr($_iCaseUsuarioIdioma, false) . '
													WHERE ID=' . $rsCeCases->fields['ID'];
													$rsUCase = $connECases->Execute($sSQLECases);
													$idUsrCase=$rsCeCases->fields['ID'];
												}else{
													//No existe, lo insertamos
													$sSQLECases = '
													INSERT INTO users (id_rol, id_cliente, id_idiom, first_name, last_name, password, email, trato, state, created_at, updated_at, remember_token, evaluado, temporal, finalizado, alternativo, logged)
													VALUES (\'3\', \'' . $_iCaseCliente . '\', \'' . $_iCaseUsuarioIdioma . '\', \'' . $cCandidatos->getNombre() . '\', \'' . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . '\', \'' . $sPass .'\', \'' . $cCandidatos->getMail() . '\', \'1\', \'1\', now(), now(), NULL, \'0\', \'0\', \'0\', \'' . $sTK . '\', \'0\');';

													$rsUCase = $connECases->Execute($sSQLECases);
													$sSQLECases = "SELECT * FROM users WHERE email=" . $connECases->qstr($cCandidatos->getMail(), false);
													$rsCeCases = $connECases->Execute($sSQLECases);
													if ($rsCeCases->NumRows() <= 0)
													{
														throw new SoapFault("AltaECases", "ModificarCandidatoConv:: " . "Error alta usuario e-Cases");
													}else {
														while (!$rsCeCases->EOF){
											    			$idUsrCase=$rsCeCases->fields['ID'];
															$rsCeCases->MoveNext();
														}
													}
												}
												//throw new SoapFault("AltaECases", "ModificarCandidatoConv:--U: " . $idUsrCase . " Cli::" . $_iCaseCliente . " Simu::" . $_iCaseSimulacion);
												if ($idUsrCase !=0 && $_iCaseCliente !=0 && $_iCaseSimulacion!=0){
													//Miramos si ya está asignado a la simulación, si no lo está lo asignamos
													$sSQLECases = "SELECT * FROM simulacion_usuario WHERE id_simulacion=" . $connECases->qstr($_iCaseSimulacion, false) . " AND id_usuario=" . $connECases->qstr($idUsrCase, false);
													$rsCeCases = $connECases->Execute($sSQLECases);
													if ($rsCeCases->NumRows() <= 0){
											    		$sSQLECases = 'INSERT INTO simulacion_usuario (id_simulacion, id_usuario, id_evaluador, mail_bienvenida, estado, tiempo, finalizado)
														VALUES (\'' . $_iCaseSimulacion . '\', \'' . $idUsrCase . '\', NULL, \'0\', \'0\', \'00:00:00\', \'0\');';
											    		$rsUCase = $connECases->Execute($sSQLECases);
													}

												}else{
													throw new SoapFault("AltaECases", "ModificarCandidatoConv:: " . "Error alta e-Cases");
												}
												break;
											}
											$rs->MoveNext();
										}
									}
								}

							}else{
								throw new SoapFault("SinPruebas", "ModificarCandidatoConv::" . "Sin pruebas asignadas.");
							}
						}else{
							throw new SoapFault("SQLERR", "ModificarCandidatoConv::" . "Error modificando datos Candidato.");
						}
					}else{
						throw new SoapFault("FaltanDatos", "ModificarCandidatoConv:: " . "Faltan datos");
					}
				}else{
					throw new SoapFault("ConvNoExist", "ModificarCandidatoConv:: " . "Convocatoria no existe");
				}
			}else{
				throw new SoapFault("FaltanDatos", "ModificarCandidatoConv:: " . "Faltan datos");
			}
		}else{
	  		throw new SoapFault("SessionOut", "ModificarCandidatoConv::" . "Su sesión ha caducado");
	  	}
	    return $retorno;
	}

	/**
    * Metodo que Desasigna un candidato de una convocatoria
    *
	* @param string $sesion
	* @param string $idConvocatoria
	* @param string $idCandidato
	* @return boolean
	*/
	public function BorrarCandidatoConv($sesion, $idConvocatoria, $idCandidato)
	{
		global $cUtilidades;
		global $cEmpresasDB;
		global $cEmpresas;
		global $cEmpresas_usuariosDB;
		global $cEmpresas_usuarios;
		global $conn;
		$strMensaje="";
		$cEmpresas->setToken($sesion);
		$cEmpresas = $cEmpresasDB->usuarioPorToken($cEmpresas);

		$retorno = false;

		if ($cEmpresas->getIdEmpresa() != "")
		{
			if (!empty($idConvocatoria))
			{
				//Consultamos los datos del proceso / convocatoria
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
				$cProcesosDB = new ProcesosDB($conn);
				$cProcesos = new Procesos();
				$cProcesos->setIdEmpresa($cEmpresas->getIdEmpresa());
				$cProcesos->setIdProceso($idConvocatoria);
				$cProcesos = $cProcesosDB->readEntidad($cProcesos);

				if ($cProcesos->getNombre() != "")
				{
					if (!empty($idCandidato))
					{
						//Comprobamos que esté asignado a la convocatoria
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
						require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
						$cCandidatos = new Candidatos();
						$cCandidatosDB = new CandidatosDB($conn);

						$cCandidatos->setIdEmpresa($cEmpresas->getIdEmpresa());
						$cCandidatos->setIdProceso($idConvocatoria);
						$cCandidatos->setIdCandidato($idCandidato);
						$cCandidatos =  $cCandidatosDB->readEntidad($cCandidatos);

						if($cCandidatos->getNombre() == "")
						{
							throw new SoapFault("ErrorAsignado", "BorrarCandidatoConv::" . "El candidato No está asignado a esta convocatoria");
						}
						//Comprobamos que esté ya Informado
						if($cCandidatos->getInformado() == 1)
						{
							throw new SoapFault("ErrorInformado", "BorrarCandidatoConv::" . "El candidato ya ha sido informado, no se realizar la operación seleccionada");
						}
						////
						$cCandidatos->setUsuAlta($cEmpresas->getIdEmpresa());
						$cCandidatos->setUsuMod($cEmpresas->getIdEmpresa());
						$retorno = $cCandidatosDB->borrar($cCandidatos);
					}else{
						throw new SoapFault("FaltanDatos", "BorrarCandidatoConv:: " . "Faltan datos");
					}
				}else{
					throw new SoapFault("ConvNoExist", "BorrarCandidatoConv:: " . "Convocatoria no existe");
				}
			}else{
				throw new SoapFault("FaltanDatos", "BorrarCandidatoConv:: " . "Faltan datos");
			}
		}else{
	  		throw new SoapFault("SessionOut", "BorrarCandidatoConv::" . "Su sesión ha caducado");
	  	}
	    return $retorno;
	}
	/**
    * Metodo que envia los datos de acceso a las pruebas a los candidatos pendientes de informar
    *
	* @param string $sesion
	* @param string $idConvocatoria
	* @param string $idCandidato <optional> Si se especifica se envia o reenvia los datos de acceso al candidato (las contraseña cambiará)
	* @return boolean
	*/
	public function informarCandidatos($sesion, $idConvocatoria, $idCandidato)
	{
		global $cUtilidades;
		global $cEmpresasDB;
		global $cEmpresas;
		global $cEmpresas_usuariosDB;
		global $cEmpresas_usuarios;
		global $conn;
		$strMensaje="";
		$cEmpresas->setToken($sesion);
		$cEmpresas = $cEmpresasDB->usuarioPorToken($cEmpresas);

		$sMSG_JS_ERROR = "";
		$sMSG_JS_RESUMEN = "";
		$sqlCandidatos="";

		$retorno = "";

		if ($cEmpresas->getIdEmpresa() != "")
		{
			if (!empty($idConvocatoria))
			{
				//Consultamos los datos del proceso / convocatoria
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
				$cProcesosDB = new ProcesosDB($conn);
				$cProcesos = new Procesos();
				$cProcesos->setIdEmpresa($cEmpresas->getIdEmpresa());
				$cProcesos->setIdProceso($idConvocatoria);
				$cProcesos = $cProcesosDB->readEntidad($cProcesos);

				if ($cProcesos->getNombre() != "")
				{
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Envios/EnviosDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Envios/Envios.php");
					$cEnvios = new Envios();
					$cEnviosDB = new EnviosDB($conn);
					$cEnvios->setIdEmpresa($cEmpresas->getIdEmpresa());
					$cEnvios->setIdProceso($cProcesos->getIdProceso());
					$cEnvios->setIdTipoCorreo("1");
					$cEnvios->setIdCorreo("1");

					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
					$cCandidatos = new Candidatos();
					$cCandidatosDB = new CandidatosDB($conn);

					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
					$cProceso_pruebasDB = new Proceso_pruebasDB($conn);

					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
					$cProceso_baremosDB = new Proceso_baremosDB($conn);

					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
					$cProceso_informesDB = new Proceso_informesDB($conn);

					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas_empresas/Informes_pruebas_empresasDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas_empresas/Informes_pruebas_empresas.php");
					$cInformes_pruebas_empresasDB	= new Informes_pruebas_empresasDB($conn);

					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebasDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebas.php");
					$cInformes_pruebasDB	= new Informes_pruebasDB($conn);

					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Correos_proceso/Correos_procesoDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Correos_proceso/Correos_proceso.php");
					$cCorreos_procesoDB = new Correos_procesoDB($conn);

					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Correos/CorreosDB.php");
					require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Correos/Correos.php");
					$cCorreosDB = new CorreosDB($conn);

					//Verificamos que no tenga dos correos con el mismo
					//tipo para esa empresa, proceso.
					$cCorreosProceso = new Correos_proceso();
					$cCorreosProceso->setIdEmpresa($cEmpresas->getIdEmpresa());
					$cCorreosProceso->setIdProceso($idConvocatoria);
					$cCorreosProceso->setIdTipoCorreo("1");
					$sqlValida =	$cCorreos_procesoDB->readLista($cCorreosProceso);
					$rsValida = $conn->Execute($sqlValida);
					if ($rsValida->NumRows() <= 0){
						//Realizamos la asignación del correo al proceso
						//Consultamos el correo estandar de envio
						$cCorreos = new Correos();
						$cCorreos->setIdCorreo("1");
						$cCorreos->setIdTipoCorreo("1");
						$cCorreos = $cCorreosDB->readEntidad($cCorreos);

						$cCorreos->setIdEmpresa($cEmpresas->getIdEmpresa());
						$cCorreos->setUsuAlta($cEmpresas->getIdEmpresa());
						$cCorreos->setUsuMod($cEmpresas->getIdEmpresa());
						$cCorreosDB->insertar($cCorreos);

						$cCorreosProceso = new Correos_proceso();
						$cCorreosProceso->setIdEmpresa($cCorreos->getIdEmpresa());
						$cCorreosProceso->setIdProceso($idConvocatoria);
						$cCorreosProceso->setIdTipoCorreo($cCorreos->getIdTipoCorreo());
						$cCorreosProceso->setIdCorreo($cCorreos->getIdCorreo());
						$cCorreosProceso->setAsunto($cCorreos->getAsunto());
						$cCorreosProceso->setDescripcion($cCorreos->getDescripcion());
						$cCorreosProceso->setCuerpo($cCorreos->getCuerpo());
						$cCorreosProceso->setNombre($cCorreos->getNombre());
						$cCorreosProceso->setUsuAlta($cEmpresas->getIdEmpresa());
						$cCorreosProceso->setUsuMod($cEmpresas->getIdEmpresa());
						$cCorreos_procesoDB->insertar($cCorreosProceso);
					}

					if (!empty($idCandidato))
					{
						//Comprobamos que esté asignado a la convocatoria
                        $cCand = new Candidatos();
						$cCand->setIdEmpresa($cEmpresas->getIdEmpresa());
						$cCand->setIdProceso($idConvocatoria);
						$cCand->setIdCandidato($idCandidato);
						$cCand =  $cCandidatosDB->readEntidad($cCand);

						if($cCand->getNombre() == "")
						{
							throw new SoapFault("ErrorAsignado", "informarCandidatos::" . "El candidato No está asignado a esta convocatoria");
						}
						//Enviamos o reenviamos los datos de acceso SÓLO a ese candidato
						$cCandidatos = new Candidatos();
						$cEnvios->setIdCandidato($cCand->getIdCandidato());
						$cCandidatos->setIdCandidatoIN($cEnvios->getIdCandidato());
						$cCandidatos->setIdEmpresa($cEnvios->getIdEmpresa());
						$cCandidatos->setIdProceso($cEnvios->getIdProceso());
						$sqlCandidatos = $cCandidatosDB->readListaIN($cCandidatos);

					}else{
						//Enviamos los datos de acceso a todos los candidatos
						//que esten pendientes de informar
						//Si son más de 100 los limitamos
						$cCandidatos = new Candidatos();
						$cCandidatos->setIdEmpresa($cEnvios->getIdEmpresa());
						$cCandidatos->setIdProceso($cEnvios->getIdProceso());
						$cCandidatos->setInformado("0");
						$cCandidatos->setInformadoHast("0");
						$sqlCandidatos = $cCandidatosDB->readListaIN($cCandidatos);
						$rsCandidatos = $conn->Execute($sqlCandidatos);
						$iTotalCandidatosInicio	=	$rsCandidatos->RecordCount();
						if ($iTotalCandidatosInicio > 100){
							$sqlCandidatos .= " LIMIT 0 , 100 ";
						}

					}
					$rsCandidatos = $conn->Execute($sqlCandidatos);
					$iTotalCandidatos	=	$rsCandidatos->RecordCount();
					$iDonglesDeEmpresa	=	$cEmpresas->getDongles();
					$iDonglesADescontarUnitario	=	0;
					$iDonglesADescontar	=	0;

					$cProceso_informes	=	new Proceso_informes();
					$cProceso_informes->setIdEmpresa($cEnvios->getIdEmpresa());
					$cProceso_informes->setIdProceso($cEnvios->getIdProceso());
					$sqlProceso_informes = $cProceso_informesDB->readLista($cProceso_informes);
					$rsProceso_informes = $conn->Execute($sqlProceso_informes);

					//Miramos por cada informe seleccionado la tarifa a descontar
					while (!$rsProceso_informes->EOF)
					{
						//Cambiar Dongels por Cliente/Prueba/Informe
		    			//Miramos si tiene definido dongles por empresa
		    			$cInformes_pruebas = new Informes_pruebas_empresas();
		    			$cInformes_pruebas->setIdPrueba($rsProceso_informes->fields['idPrueba']);
		    			$cInformes_pruebas->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
		    			$cInformes_pruebas->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
		    			$cInformes_pruebas->setIdEmpresa($rsProceso_informes->fields['idEmpresa']);

						$sql_IPE = $cInformes_pruebas_empresasDB->readLista($cInformes_pruebas);
						$rsIPE = $conn->Execute($sql_IPE);
		    			if ($rsIPE->NumRows() > 0){
		    				$cInformes_pruebas = $cInformes_pruebas_empresasDB->readEntidad($cInformes_pruebas);
		    			}else {
							$cInformes_pruebas	=	new Informes_pruebas();
							$cInformes_pruebas->setIdPrueba($rsProceso_informes->fields['idPrueba']);
							$cInformes_pruebas->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
							$cInformes_pruebas->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
							$cInformes_pruebas = $cInformes_pruebasDB->readEntidad($cInformes_pruebas);
		    			}

		    			$iDonglesADescontarUnitario += $cInformes_pruebas->getTarifa();
						$rsProceso_informes->MoveNext();
					}

					$iDonglesADescontar	=	($iDonglesADescontarUnitario * $iTotalCandidatos);
					//Verificamos si esa empresa es por contrato o prepago
					//Si es por prepago verificamos si tiene suficientes dongles
					$bPrepago = $cEmpresas->getPrepago();
					if (!empty($bPrepago)){
						//Es de prepago hay que verificar los dongles
						if ($iDonglesADescontar > $iDonglesDeEmpresa){
							//Hay que descontar mas dongles que los que tiene cargados la empresa,
							//Se lanza mensaje de error.
							$sMSG_JS_ERROR="La Empresa " . $cEmpresas->getNombre() . " - No Dispone de suficientes Unidades para efectuar la operación.\\n";
							$sMSG_JS_ERROR.="\\tUnidades Disponibles: " . $iDonglesDeEmpresa . " Unidades.\\n";
							$sMSG_JS_ERROR.="\\tUnidades a consumir: " . $iDonglesADescontar . " Unidades.\\n\\n";
							$sMSG_JS_ERROR.="Por favor recargue un mínimo de:\\n ";
							$sMSG_JS_ERROR.="\\t" . ($iDonglesADescontar - $iDonglesDeEmpresa) . " Unidades.\\n ";

							throw new SoapFault("ErrorSaldo", "informarCandidatos::" . $sMSG_JS_ERROR);
						}
					}else{
						//Es de contrato, No se hacer la verificación de dongles
					}


					@set_time_limit(0);
					ini_set("memory_limit","512M");

					$sFrom=$cEmpresas->getMail();	//Cuenta de correo de la empresa

					$sFromName=$cEmpresas->getNombre();	//Nombre de la empresa
					$cProcesos->setIdProceso($cEnvios->getIdProceso());
					$cProcesos->setIdEmpresa($cEnvios->getIdEmpresa());
					$cProcesos = $cProcesosDB->readEntidad($cProcesos);
					$IdModoRealizacion = $cProcesos->getIdModoRealizacion();

					// EnvioContrasenas == 1 Individuales
					// EnvioContrasenas == 2 Todas en 1 sólo correo
					$EnvioContrasenas = $cProcesos->getEnvioContrasenas();
					$sTodas1Correo = "";
					$cCandidatosDB = new CandidatosDB($conn);
					$sNOEnviados= "";
					$iTotal = $rsCandidatos->RecordCount();
					$iFallidos = 0;
					$aMailsNOEnviados = (empty($sMailsNOEnviados)) ? array() : explode(",", $sMailsNOEnviados);
					switch ($cEnvios->getIdTipoCorreo())
					{
						case "1":	//Envio o Reenvio de información
							//Recorremos los candidatos, enviamos el correo
							//y lo damos de alta en en la tabla envios.
							//Esta forma es incremental, teniendo un histórico de envios.
							$i=0;
							$sBody = "";
							$sAltBody = "";
							$sSubject = "";
							while (!$rsCandidatos->EOF){
								$cCandidatos = new Candidatos();
								$cCandidatos->setIdEmpresa($cEnvios->getIdEmpresa());
								$cCandidatos->setIdProceso($cEnvios->getIdProceso());
								$cCandidatos->setIdCandidato($rsCandidatos->fields['idCandidato']);
								$cCandidatos = $cCandidatosDB->readEntidad($cCandidatos);
								$newPass= $cUtilidades->newPass();
								$sUsuario=$cCandidatos->getMail();

								//Administrado y enviar todas las contraseñas en 1 sólo correo
								if ($EnvioContrasenas == "2"){
									$cNotificaciones	= new Notificaciones();
									$cNotificaciones->setIdTipoNotificacion(8);	//Usuarios y contraseña juntos
									$cNotificaciones = $cNotificacionesDB->readEntidad($cNotificaciones);
									$cNotificaciones = $cNotificacionesDB->parseaHTML($cNotificaciones, null, $cProcesos, null, $cCandidatos, null, null, $sUsuario, $newPass);

									$sSubject=$cNotificaciones->getAsunto();
									$sBody.=$cNotificaciones->getCuerpo();
									$sAltBody.="\\n" . strip_tags($cNotificaciones->getCuerpo());

									//Actualizamos el usuario con la nueva contraseña
									//Lo ponemos como informado
									$cCandidatos->setPassword($newPass);
									$cCandidatos->setInformado(1);
									$cCandidatos = $cCandidatosDB->modificar($cCandidatos);
									$cEnvios_hist	= new Envios();
									$cEnvios_hist->setIdEmpresa($cEnvios->getIdEmpresa());
									$cEnvios_hist->setIdProceso($cEnvios->getIdProceso());
									$cEnvios_hist->setIdTipoCorreo($cEnvios->getIdTipoCorreo());
									$cEnvios_hist->setIdCorreo($cEnvios->getIdCorreo());
									$cEnvios_hist->setIdCandidato($rsCandidatos->fields['idCandidato']);
									$cEnvios_hist->setUsuAlta($cEmpresas->getIdEmpresa());
									$cEnvios_hist->setUsuMod($cEmpresas->getIdEmpresa());
									$cEnviosDB->insertar($cEnvios_hist);
								}else{
									$cCorreos_proceso = new Correos_proceso();
									$cCorreos_proceso->setIdEmpresa($cEnvios->getIdEmpresa());
									$cCorreos_proceso->setIdProceso($cEnvios->getIdProceso());
									$cCorreos_proceso->setIdTipoCorreo($cEnvios->getIdTipoCorreo());
                                    $sSQLCP = $cCorreos_procesoDB->readLista($cCorreos_proceso);
                                    $rsCP = $conn->Execute($sSQLCP);
                                    $idCorreo= 1;
                            	    while ($arr = $rsCP->FetchRow()){
	        				           $idCorreo = $arr["idCorreo"];
	        			            }

                                    $cEnvios->setIdCorreo($idCorreo);
									$cCorreos_proceso->setIdCorreo($cEnvios->getIdCorreo());

									$cCorreos_proceso = $cCorreos_procesoDB->readEntidad($cCorreos_proceso);
									$cCorreos_proceso = $cCorreos_procesoDB->parseaHTML($cCorreos_proceso, $cCandidatos, $cProcesos, $cEmpresas, $sUsuario, $newPass);

									$sSubject=$cCorreos_proceso->getAsunto();
									$sBody=$cCorreos_proceso->getCuerpo();
									$sAltBody=strip_tags($cCorreos_proceso->getCuerpo());

									if (!in_array(strtolower($cCandidatos->getMail()), $aMailsNOEnviados))
									{

										if (!$this->enviaEmail($cEmpresas, $cCandidatos, $cCorreos_proceso, $IdModoRealizacion)){
											//informamos de los emails q no se han podido enviar.
											$iFallidos++;
											$sMSG_JS_ERROR="No se ha podido enviar correos a las siguientes direcciones:\\n";
											$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
											$sMailsNOEnviados.= "," . $cCandidatos->getMail();
										}else{
											//Actualizamos el usuario con la nueva contraseña
											//Lo ponemos como informado
											$cCandidatos->setPassword($newPass);
											$cCandidatos->setInformado(1);
											$OK = $cCandidatosDB->modificar($cCandidatos);
											$cEnvios_hist	= new Envios();
											$cEnvios_hist->setIdEmpresa($cEnvios->getIdEmpresa());
											$cEnvios_hist->setIdProceso($cEnvios->getIdProceso());
											$cEnvios_hist->setIdTipoCorreo($cEnvios->getIdTipoCorreo());
											$cEnvios_hist->setIdCorreo($cEnvios->getIdCorreo());
											$cEnvios_hist->setIdCandidato($rsCandidatos->fields['idCandidato']);
											$cEnvios_hist->setUsuAlta($cEmpresas->getIdEmpresa());
											$cEnvios_hist->setUsuMod($cEmpresas->getIdEmpresa());
											$cEnviosDB->insertar($cEnvios_hist);
											$sTypeError	=	date('d/m/Y H:i:s') . " Correo enviado FROM::[" . $sFrom . "] TO::[" . $cCandidatos->getMail() . "]";
											error_log($sTypeError . " ->\t" . $cCorreos_proceso->getCuerpo() . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));

										}
									}
								}

								$i++;
								$rsCandidatos->MoveNext();
							}
							//Administrado y enviar todas las contraseñas en 1 sólo correo
							if ($EnvioContrasenas == "2"){
								if (!empty($sBody)){
									$cCorreos_proceso = new Correos_proceso();
									$cCorreos_proceso->setIdEmpresa($cEnvios->getIdEmpresa());
									$cCorreos_proceso->setIdProceso($cEnvios->getIdProceso());
									$cCorreos_proceso->setIdTipoCorreo($cEnvios->getIdTipoCorreo());
									$cCorreos_proceso->setIdCorreo($cEnvios->getIdCorreo());
									$cCorreos_proceso = $cCorreos_procesoDB->readEntidad($cCorreos_proceso);

									$cCorreos_proceso->setAsunto($sSubject);
									$cCorreos_proceso->setCuerpo($sBody);

									if (!$this->enviaEmail($cEmpresas, $cCandidatos, $cCorreos_proceso, $IdModoRealizacion)){
										//informamos de los emails q no se han podido enviar.
										$iFallidos++;
										$sMSG_JS_ERROR="No se ha podido enviar correos a las siguientes direcciones:\\n";
										$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
										$sMailsNOEnviados.= "," . $cCandidatos->getMail();
									}
								}
							}
							break;
						case "2":	//Confirmación
							//Miramos por candidato si se le ha enviado
							// previamente el correo de envio.
							while (!$rsCandidatos->EOF){
								$cCandidatos = new Candidatos();
								$cCandidatos->setIdEmpresa($cEnvios->getIdEmpresa());
								$cCandidatos->setIdProceso($cEnvios->getIdProceso());
								$cCandidatos->setIdCandidato($rsCandidatos->fields['idCandidato']);
								$cCandidatos = $cCandidatosDB->readEntidad($cCandidatos);
								$cEnvios_hist = new Envios();
								$cEnvios_hist->setIdEmpresa($cEnvios->getIdEmpresa());
								$cEnvios_hist->setIdProceso($cEnvios->getIdProceso());
								$cEnvios_hist->setIdTipoCorreo(1); //Envio
								$cEnvios_hist->setIdCorreo($cEnvios->getIdCorreo());
								$cEnvios_hist->setIdCandidato($rsCandidatos->fields['idCandidato']);
								$sqlEnvios_hist = $cEnviosDB->readLista($cEnvios_hist);
								//echo "<br />" . $sqlEnvios_hist;
								$rsEnvios_hist = $conn->Execute($sqlEnvios_hist);
								if ($rsEnvios_hist->RecordCount() <= 0){
									$iFallidos++;
									$sMSG_JS_ERROR="No se ha enviado previamente el correo de información\\nSE HA CANCELADO EL PROCESO DE ENVÍO.\\n";
									$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
									$sMailsNOEnviados.= "," . $cCandidatos->getMail();
									$iFallidos = $iTotal;
								}
								$rsCandidatos->MoveNext();
							}
							if (empty($sNOEnviados))
							{
								//Miramos que tenga el corre definido en el cuerpo
								//la etiqueta @acceso_password@
							    $sLiteral = "@acceso_password@";
		        				if (strpos($cCorreos_proceso->getCuerpo(), $sLiteral)){
									//continue;
								}else{
									$iFallidos++;
									$sMSG_JS_ERROR="El correo de confirmación no contiene la etiqueta adecuada de @acceso_password@\\nSE HA CANCELADO EL PROCESO DE ENVÍO.\\n";
									$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
									$sMailsNOEnviados.= "," . $cCandidatos->getMail();
									$iFallidos = $iTotal;
								}
								if (empty($sNOEnviados))
								{
									$rsCandidatos->Move(0); //Posicionamos en el primer registro.
									while (!$rsCandidatos->EOF){
										$cCandidatos = new Candidatos();
										$cCandidatos->setIdEmpresa($cEnvios->getIdEmpresa());
										$cCandidatos->setIdProceso($cEnvios->getIdProceso());
										$cCandidatos->setIdCandidato($rsCandidatos->fields['idCandidato']);
										$cCandidatos = $cCandidatosDB->readEntidad($cCandidatos);
										$newPass= $cUtilidades->newPass();
										$sUsuario=$cCandidatos->getMail();
										$cCorreos_proceso = new Correos_proceso();
										$cCorreos_proceso->setIdEmpresa($cEnvios->getIdEmpresa());
										$cCorreos_proceso->setIdProceso($cEnvios->getIdProceso());
										$cCorreos_proceso->setIdTipoCorreo($cEnvios->getIdTipoCorreo());
										$cCorreos_proceso->setIdCorreo($cEnvios->getIdCorreo());

										$cCorreos_proceso = $cCorreos_procesoDB->readEntidad($cCorreos_proceso);
										$cCorreos_proceso = $cCorreos_procesoDB->parseaHTML($cCorreos_proceso, $cCandidatos, $cProcesos, $sUsuario, $newPass);

										$sSubject=$cCorreos_proceso->getAsunto();
										$sBody=$cCorreos_proceso->getCuerpo();
										$sAltBody=strip_tags($cCorreos_proceso->getCuerpo());

										if (!$this->enviaEmail($cEmpresas, $cCandidatos, $cCorreos_proceso, $IdModoRealizacion)){
											//informamos de los emails q no se han podido enviar.
											$iFallidos++;
											$sMSG_JS_ERROR="No se ha podido enviar correos a las siguientes direcciones:\\n";
											$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
											$sMailsNOEnviados.= "," . $cCandidatos->getMail();
										}else{
											//Actualizamos el usuario con la nueva contraseña
											//Lo ponemos como informado
											$cCandidatos->setPassword($newPass);
											$cCandidatos->setInformado(1);
											$cCandidatos = $cCandidatosDB->modificar($cCandidatos);
											$cEnvios_hist	= new Envios();
											$cEnvios_hist->setIdEmpresa($cEnvios->getIdEmpresa());
											$cEnvios_hist->setIdProceso($cEnvios->getIdProceso());
											$cEnvios_hist->setIdTipoCorreo($cEnvios->getIdTipoCorreo());
											$cEnvios_hist->setIdCorreo($cEnvios->getIdCorreo());
											$cEnvios_hist->setIdCandidato($rsCandidatos->fields['idCandidato']);
											$cEnvios_hist->setUsuAlta($cEmpresas->getIdEmpresa());
											$cEnvios_hist->setUsuMod($cEmpresas->getIdEmpresa());
											$cEnviosDB->insertar($cEnvios_hist);
										}
										$i++;
										$rsCandidatos->MoveNext();
									}
								}
							}
							break;
						default:
							break;
					}

					if (!empty($sMailsNOEnviados)){
						if (substr($sMailsNOEnviados, 0, 1) == ","){
							$sMailsNOEnviados = substr($sMailsNOEnviados, 1);
							$aMailsNOEnviados = explode(",", $sMailsNOEnviados);
						}
					}
					$iTotalEnviados += $iTotal;
					if (empty($sNOEnviados))
					{
						if ($iTotalEnviados >= $iTotalCandidatosInicio)
						{
							$bReenviar = "0";
		 					$retorno = "Se han enviado " . ($iTotalCandidatosInicio-sizeof($aMailsNOEnviados)) . "Correos de un total de " . $iTotalCandidatosInicio . "\n" . $cCandidatosDB->ver_errores();

						}else{
		//					echo "<br />iTotalEnviados::" . $iTotalEnviados;
		//					echo "<br />iTotalCandidatosInicio::" . $iTotalCandidatosInicio;
						}
					}else{
						if ($iTotalEnviados >= $iTotalCandidatosInicio)
						{
							if (!empty($sMailsNOEnviados))
							{
								$aMailsNOEnviados = explode(",", $sMailsNOEnviados);
								$sMSG_JS_ERROR="-No se ha podido enviar correos a las siguientes direcciones:\\n";
								$sNOEnviados= "";
								for ($i=0, $max = sizeof($aMailsNOEnviados); $i < $max; $i++)
								{
									$sNOEnviados.=  " [" . $aMailsNOEnviados[$i] . "]\\n";
								}
								$bReenviar = "0";
								$retorno = $sMSG_JS_ERROR . $sNOEnviados . $sMSG_JS_RESUMEN;
							}
						}
					}

				}else{
					throw new SoapFault("ConvNoExist", "informarCandidatos:: " . "Convocatoria no existe");
				}
			}else{
				throw new SoapFault("FaltanDatos", "informarCandidatos:: " . "Faltan datos");
			}
		}else{
	  		throw new SoapFault("SessionOut", "informarCandidatos::" . "Su sesión ha caducado");
	  	}
	    return $retorno;
	}

	private function enviaEmail(&$cEmpresa, &$cCandidato, &$cCorreos_proceso, $IdModoRealizacion){

		global $conn;

		$sSubject=$cCorreos_proceso->getAsunto();
		$sBody=$cCorreos_proceso->getCuerpo();
		$sAltBody=strip_tags($cCorreos_proceso->getCuerpo());
		if (empty($sSubject) || empty($sBody)){
			$sTypeError	=	date('d/m/Y H:i:s') . " *** Correo VACIO FROM::[" . $cEmpresa->getMail() . "] TO::[" . $cCandidato->getMail() . "]";
			error_log($sTypeError . " ->\tSUBJECT::" . $sSubject . "\tBODY::" . $sBody . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));
			return false;
		}
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "/_Email/class.phpmailer.php");

		//instanciamos un objeto de la clase phpmailer al que llamamos
		//por ejemplo mail
		$mail = new PHPMailer(true);  //PHPMailer instance with exceptions enabled
$mail->CharSet = 'utf-8';
$mail->Debugoutput = 'html';

		//Con PluginDir le indicamos a la clase phpmailer donde se
		//encuentra la clase smtp que como he comentado al principio de
		//este ejemplo va a estar en el subdirectorio includes
		$mail->PluginDir = constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "/_Email/";

		//Con la propiedad Mailer le indicamos que vamos a usar un
		//servidor smtp
		$mail->Mailer = $mail->Mailer = constant("MAILER");;

		//Asignamos a Host el nombre de nuestro servidor smtp
		$mail->Host = constant("HOSTMAIL");

		//Le indicamos que el servidor smtp requiere autenticaciÃ³n
		$mail->SMTPAuth = true;

		//Le decimos cual es nuestro nombre de usuario y password
		$mail->Username = constant("MAILUSERNAME");
		$mail->Password = constant("MAILPASSWORD");

		//Indicamos cual es nuestra dirección de correo y el nombre que
		//queremos que vea el usuario que lee nuestro correo
		//$mail->From = $cEmpresa->getMail();
		$mail->From = constant("MAILUSERNAME");
		$mail->AddReplyTo($cEmpresa->getMail(), $cEmpresa->getNombre());
		$mail->FromName = $cEmpresa->getNombre();

		//Asignamos asunto y cuerpo del mensaje
		//El cuerpo del mensaje lo ponemos en formato html, haciendo
		//que se vea en negrita
		$mail->Subject = $sSubject;
		$mail->Body = $sBody;

		//Definimos AltBody por si el destinatario del correo no admite
		//email con formato html
		$mail->AltBody = $sAltBody;

		//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar
		//una cuenta gratuita y voy a usar attachments, por tanto lo pongo a 120
		$mail->Timeout=120;

		//Indicamos el fichero a adjuntar si el usuario seleccionÃ³ uno en el formulario
		$archivo="none";
		if ($archivo !="none") {
			$mail->AddAttachment($archivo,$archivo_name);
		}
		//Indicamos cuales son las direcciones de destino del correo
		if ($IdModoRealizacion == "2"){	//Administrado SE ENVIAN A LA EMPRESA
			$mail->AddAddress($cEmpresa->getMail(), $cEmpresa->getNombre());
			if($cEmpresa->getMail2()!=""){
				$mail->AddAddress($cEmpresa->getMail2(), $cEmpresa->getNombre());
			}
			if($cEmpresa->getMail3()!=""){
				$mail->AddAddress($cEmpresa->getMail3(), $cEmpresa->getNombre());
			}
		}else{
			$mail->AddAddress($cCandidato->getMail(), $cCandidato->getNombre() . " " . $cCandidato->getApellido1() . " " . $cCandidato->getApellido2());
		}

		//se envia el mensaje, si no ha habido problemas la variable $success
		//tendra el valor true
		$exito=false;
		//Si el mensaje no ha podido ser enviado se realizaran 2 intentos mas
		//como mucho para intentar enviar el mensaje, cada intento se hara 2 s
		//segundos despues del anterior, para ello se usa la funcion sleep
	 	$intentos=1;
	   	while((!$exito)&&($intentos<2)&&($mail->ErrorInfo!="SMTP Error: Data not accepted"))
	   	{
		   sleep(rand(0, 2));
	     	   //echo $mail->ErrorInfo;
	     	   $exito = $mail->Send();
	     	   $intentos=$intentos+1;
	   	}

		//La clase phpmailer tiene un pequeño bug y es que cuando envia un mail con
		//attachment la variable ErrorInfo adquiere el valor Data not accepted, dicho
		//valor no debe confundirnos ya que el mensaje ha sido enviado correctamente
		if ($mail->ErrorInfo=="SMTP Error: Data not accepted") {
			$exito=true;
		}
		// Borro las direcciones de destino establecidas anteriormente
	    $mail->ClearAddresses();
	    return $exito;
	}




}

?>
