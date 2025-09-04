<?php

header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

require_once('include/Configuracion.php');
include_once('include/Idiomas.php');

define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
include(constant("DIR_ADODB") . 'adodb.inc.php');
require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
require_once(constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
require_once(constant("DIR_WS_COM") . "Usuarios/Usuarios.php");

include_once('include/conexion.php');

$cEntidadIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
$cEntidadIdiomas	= new Idiomas();  // Entidad
$cEntidadIdiomas->setActivoBack(1);
$sqlIdiomas = $cEntidadIdiomasDB->readLista($cEntidadIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);

$cEntidadDB	= new UsuariosDB($conn);  // Entidad DB
$cEntidad	= new Usuarios();  // Entidad
//phpinfo();

$sHijos = "";
if (empty($_POST["fHijos"])) {
	require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
	require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
	$cEmpresaPadre = new Empresas();
	$cEmpresaPadreDB = new EmpresasDB($conn);
	//	$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
	$_EmpresaLogada = constant("EMPRESA_PE");
	$sHijos = $cEmpresaPadreDB->getHijos($_EmpresaLogada);
	if (!empty($sHijos)) {
		$sHijos .= $_EmpresaLogada;
	} else {
		$sHijos = $_EmpresaLogada;
	}
} else {
	$sHijos = $_POST["fHijos"];
}

$strMensaje = "";
if (isset($_POST['fGo'])) {
	if ((!empty($_POST['fLogin'])) && (!empty($_POST['fPwd']))) {
		

		$cEntidad->setLogin($_POST['fLogin']);
		$cEntidad->setPassword($_POST['fPwd']);

		require_once(constant("DIR_WS_COM") . "/Utilidades.php");
		$cUtilidades	= new Utilidades();
		$bEncontradoUsuario = $cUtilidades->chkChar($_POST['fLogin']);
		$bEncontradoPassword = $cUtilidades->chkChar($_POST['fPwd']);


		if (!$bEncontradoPassword && !$bEncontradoUsuario) {

			require_once(constant("DIR_WS_COM") . "Usuarios_accesos/Usuarios_accesosDB.php");
			require_once(constant("DIR_WS_COM") . "Usuarios_accesos/Usuarios_accesos.php");
			//Introducimos los intentos de acceso
			$cUsuarios_accesos = new Usuarios_accesos();
			$cUsuarios_accesosDB = new Usuarios_accesosDB($conn);
			$cUsuarios_accesos->setIP($_SERVER['REMOTE_ADDR']);
			$cUsuarios_accesos->setLogin($cEntidad->getLogin());
			$sqlUsuarios_accesos = $cUsuarios_accesosDB->readLista($cUsuarios_accesos);
			$aUsuarios_accesos = $conn->Execute($sqlUsuarios_accesos);

			if ($aUsuarios_accesos->RecordCount() >= 5) {
				$strMensaje = constant("ERR_FORM_LOGIN");
			}
			
			if (empty($strMensaje)) {

				$cUsuarios_accesos->setUsuAlta("0");
				$cUsuarios_accesos->setUsuMod("0");

				$cUsuarios_accesosDB->insertar($cUsuarios_accesos);
				$rowUser = $cEntidadDB->Login($cEntidad);

				if (!empty($rowUser["login"]) && $rowUser["login"] == $_POST['fLogin']) {
					$cUsuarios_accesos->setIP("");
					$cUsuarios_accesosDB->borrar($cUsuarios_accesos);

					//Miramos si tiene perfiles asignados
					require_once(constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfilesDB.php");
					require_once(constant("DIR_WS_COM") . "Usuarios_perfiles/Usuarios_perfiles.php");
					$cEntidadUPDB	= new Usuarios_perfilesDB($conn);  // Entidad DB
					$cEntidadUP		= new Usuarios_perfiles();  // Entidad
					$cEntidadUP->setIdUsuario($rowUser["idUsuario"]);
					$sSQLUP = $cEntidadUPDB->readLista($cEntidadUP);
					$listaUP = $conn->Execute($sSQLUP);
					$i = "0";
					$sUP = "";
					while ($arr = $listaUP->FetchRow()) {
						$sUP .= "," . $arr["idPerfil"];
						$i++;
					}
					if ($i > 0) {
						$sUP = substr($sUP, 1);
						//Miramos las funcionalidades para el / los Perfiles.
						require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidadesDB.php");
						require_once(constant("DIR_WS_COM") . "Perfiles_funcionalidades/Perfiles_funcionalidades.php");
						require_once(constant("DIR_WS_COM") . "Combo.php");
						$comboFUNCIONALIDADES	= new Combo($conn, "fIdFuncionalidad", "idFuncionalidad", "url", "url", "wi_funcionalidades", "", constant("SLC_OPCION"), "");
						$cEntidadPFDB	= new Perfiles_funcionalidadesDB($conn);  // Entidad DB
						$cEntidadPF		= new Perfiles_funcionalidades();  // Entidad
						$cEntidadPF->setIdPerfil($sUP);
						$cEntidadPF->setOrderBy("idFuncionalidad, modificar, borrar ASC");
						$sSQLPF = $cEntidadPFDB->readLista($cEntidadPF);
						$listaPF = $conn->Execute($sSQLPF);
						$i = "0";
						$sPF = "";
						$aPF = array();
						$aFAcceso = array();
						$sIdFuncionalidad = "";
						while ($arr = $listaPF->FetchRow()) {
							$sPK = $comboFUNCIONALIDADES->getDescripcionCombo($arr["idFuncionalidad"]);
							$aPF[$sPK]["idFuncionalidad"] = $arr["idFuncionalidad"];
							$aPF[$sPK]["nombreFuncionalidad"] = $sPK;
							$aPF[$sPK]["modificar"] =   $arr["modificar"];
							$aPF[$sPK]["borrar"] =   $arr["borrar"];
							$aFAcceso[$i] = $arr["idFuncionalidad"];
							$i++;
						}
						if ($i > 0) {
							$cEntidad->setIdUsuario($rowUser["idUsuario"]);
							$token = md5(uniqid('', true));
							$cEntidad->setToken($token);
							$cEntidadDB->ActualizaToken($cEntidad);

							//Actualizamos el último login
							if ($cEntidadDB->ultimoLogin($cEntidad) == false) {
								echo constant("ERR");
								exit;
							}
							//Seteamos el token y las variables necesarias
							$_POST['sTK'] = $token;
							$_POST["MODO"] = "0";
							include('bienvenida.php');
						} else {
							$strMensaje = '* ' . constant("ERR_NO_AUTORIZADO");
						}
					} else {
						$strMensaje = constant("ERR_NO_AUTORIZADO");
					}
				} else $strMensaje = constant("ERR_FORM_LOGIN");
			}
		} else $strMensaje = constant("ERR_FORM_LOGIN");
	} else $strMensaje = constant("ERR_FORM_LOGIN");
	if (!empty($strMensaje)) {
		include('Template/login.php');
	}
} else {
	include('Template/login.php');
}
