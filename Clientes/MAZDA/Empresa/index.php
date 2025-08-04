<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


require_once('include/Configuracion.php');
include_once('include/Idiomas.php');

define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
include(constant("DIR_ADODB") . 'adodb.inc.php');
require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");

include_once ('include/conexion.php');

$cEntidadIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
$cEntidadIdiomas	= new Idiomas();  // Entidad
$cEntidadIdiomas->setActivoBack(1);
$sqlIdiomas = $cEntidadIdiomasDB->readLista($cEntidadIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);

$cEntidadDB	= new EmpresasDB($conn);  // Entidad DB
$cEntidad	= new Empresas();  // Entidad

$sHijos = "";
if(!empty($_cEntidadUsuarioTK)){
	if (empty($_POST["fHijos"]))
	{
		require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
		require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
		$cEmpresaPadre = new Empresas();
		$cEmpresaPadreDB = new EmpresasDB($conn);
		$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
		//$_EmpresaLogada = constant("EMPRESA_PE");
		$sHijos = $cEmpresaPadreDB->getHijos($_EmpresaLogada);
		if (!empty($sHijos)){
			$sHijos .= $_EmpresaLogada;
		}else{
			$sHijos = $_EmpresaLogada;
		}
	}else{
		$sHijos = $_POST["fHijos"];
	}
}

$strMensaje="";
if (isset($_POST['fGo'])){
	if ((!empty($_POST['fLogin'])) && (!empty($_POST['fPwd'])))
	{
		$cEntidad->setUsuario($_POST['fLogin']);
		$cEntidad->setPassword($_POST['fPwd']);
		require_once(constant("DIR_WS_COM") . "/Utilidades.php");
		$cUtilidades	= new Utilidades();
        $bEncontradoUsuario = $cUtilidades->chkChar($_POST['fLogin']);
        $bEncontradoPassword = $cUtilidades->chkChar($_POST['fPwd']);

		if (!$bEncontradoPassword && !$bEncontradoUsuario)
        {

            require_once(constant("DIR_WS_COM") . "Empresas_accesos/Empresas_accesosDB.php");
            require_once(constant("DIR_WS_COM") . "Empresas_accesos/Empresas_accesos.php");
        	//Introducimos los intentos de acceso
			$cEmpresas_accesos = new Empresas_accesos();
			$cEmpresas_accesosDB = new Empresas_accesosDB($conn);
			$cEmpresas_accesos->setIP($_SERVER['REMOTE_ADDR']);
			$cEmpresas_accesos->setLogin($cEntidad->getUsuario());

			$sqlEmpresas_accesos = $cEmpresas_accesosDB->readLista($cEmpresas_accesos);
            $aEmpresas_accesos = $conn->Execute($sqlEmpresas_accesos);
	        if ($aEmpresas_accesos->RecordCount() >= 5){
                $strMensaje = constant("ERR_FORM_LOGIN");
			}
			if (empty($strMensaje))
            {
    			$cEmpresas_accesos->setUsuAlta("0");
    			$cEmpresas_accesos->setUsuMod("0");

    			$cEmpresas_accesosDB->insertar($cEmpresas_accesos);

        		$rowUser = $cEntidadDB->Login($cEntidad);
        		if (!empty($rowUser["usuario"]) && $rowUser["usuario"] == $_POST['fLogin'] )
        		{
					//Borramos los acceso erroneos anteriorres
            		$cEmpresas_accesos->setIP("");
					$cEmpresas_accesosDB->borrarPorLogin($cEmpresas_accesos);
					//Dejamos sólo este login
					$cEmpresas_accesos->setIP($_SERVER['REMOTE_ADDR']);
					$cEmpresas_accesos->setLogin($cEntidad->getUsuario());
					$cEmpresas_accesosDB->insertar($cEmpresas_accesos);

        			//Miramos si tiene perfiles asignados
        			require_once(constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfilesDB.php");
        			require_once(constant("DIR_WS_COM") . "Empresas_perfiles/Empresas_perfiles.php");
        			$cEntidadUPDB	= new Empresas_perfilesDB($conn);  // Entidad DB
        			$cEntidadUP		= new Empresas_perfiles();  // Entidad
        			$cEntidadUP->setIdEmpresa($rowUser["idEmpresa"]);
        			$sSQLUP = $cEntidadUPDB->readLista($cEntidadUP);
        			$listaUP = $conn->Execute($sSQLUP);
        			$i="0";
        			$sUP = "";
        			while ($arr = $listaUP->FetchRow()){
        				$sUP .= "," . $arr["idPerfil"];
        				$i++;
        			}

        			if ($i > 0){
        				$sUP = substr($sUP,1);
        				//Miramos las funcionalidades para el / los Perfiles.
        				require_once(constant("DIR_WS_COM") . "Empresas_perfiles_funcionalidades/Empresas_perfiles_funcionalidadesDB.php");
        				require_once(constant("DIR_WS_COM") . "Empresas_perfiles_funcionalidades/Empresas_perfiles_funcionalidades.php");
        				require_once(constant("DIR_WS_COM") . "Combo.php");
        				$comboFUNCIONALIDADES	= new Combo($conn,"fIdFuncionalidad","idFuncionalidad","url","url","wi_funcionalidades","",constant("SLC_OPCION"),"");
        				$cEntidadPFDB	= new Empresas_perfiles_funcionalidadesDB($conn);  // Entidad DB
        				$cEntidadPF		= new Empresas_perfiles_funcionalidades();  // Entidad
        				$cEntidadPF->setIdPerfil($sUP);
        				$cEntidadPF->setOrderBy("idFuncionalidad, modificar, borrar ASC");
        				$sSQLPF = $cEntidadPFDB->readLista($cEntidadPF);
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
        					$cEntidad->setIdEmpresa($rowUser["idEmpresa"]);
                            $token =md5(uniqid('', true));
                            $cEntidad->setToken($token);
                            $cEntidadDB->ActualizaToken($cEntidad);

        					//Actualizamos el último login
        					if ($cEntidadDB->ultimoLogin($cEntidad) == false)
        					{
        						echo constant("ERR");
        						exit;
        					}
        					//Seteamos el token y las variables necesarias
        					$_POST['sTK'] = $token;
        					$_POST["MODO"] = "0";

                  $sSQL = "SELECT aceptaCondiciones, aceptaTerminos FROM empresas WHERE idEmpresa=" . $cEntidad->getIdEmpresa();
                  $rs = $conn->Execute($sSQL);
                  $sAceptaCondiciones="";
                  $sAceptaTerminos="";

                  if ($rs){
                    while ($arr = $rs->FetchRow()){
                      $sAceptaCondiciones = $arr['aceptaCondiciones'];
                      $sAceptaTerminos = $arr['aceptaTerminos'];
                    }
                  }
                  if (empty($sAceptaCondiciones) || empty($sAceptaTerminos)){
                    include('avisoLegal.php');
                  }else{
                    include('bienvenida.php');
                  }


            			}else{
        					$strMensaje = '* ' . constant("ERR_NO_AUTORIZADO");
        				}
        			}else{

        				$strMensaje = constant("ERR_NO_AUTORIZADO");
        			}
        		}else $strMensaje = constant("ERR_FORM_LOGIN");
    		}
		}else $strMensaje = constant("ERR_FORM_LOGIN");
	}else $strMensaje = constant("ERR_FORM_LOGIN");
	if (!empty($strMensaje)){
        include('Template/login.php');
    }
}else{
	include('Template/login.php');
}
?>
