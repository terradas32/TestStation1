<?php

header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	include_once(constant("DIR_WS_INCLUDE") . 'Seguridad.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "Export_personalidad/Export_personalidadDB.php");
	require_once(constant("DIR_WS_COM") . "Export_personalidad/Export_personalidad.php");
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
include_once ('include/conexion.php');

	//require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new Export_personalidadDB($conn);  // Entidad DB
	$cEntidad	= new Export_personalidad();  // Entidad
	$cEmpresasDB	= new EmpresasDB($conn);  // Entidad DB
	$cEmpresas	= new Empresas();  // Entidad

	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	$sHijos = "";
	include_once('include/type_empresa_usuario.php');

	if (empty($_POST["fHijos"]))
	{
		require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
		require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
		$cEmpresaPadre = new Empresas();
		$cEmpresaPadreDB = new EmpresasDB($conn);

	//	$_EmpresaLogada = constant("EMPRESA_PE");
		$sHijos = $cEmpresaPadreDB->getHijos($_EmpresaLogada);
		if (!empty($sHijos)){
			$sHijos .= $_EmpresaLogada;
		}else{
			$sHijos = $_EmpresaLogada;
		}
	}else{
		$sHijos = $_POST["fHijos"];
	}
	//$comboPRUEBAS	= new Combo($conn,"fIdPrueba","nombre","nombre","nombre","pruebas","","","idTipoPrueba IN (2,5) AND idPrueba IN (" . $sPuebasEmpresa . ") ","","","nombre");

	$cEntidad = readLista($cEntidad);

	if ($cEntidad->getIdEmpresa() == ""){
		$cEntidad->setIdEmpresa($sHijos);
	}
	$sql = $cEntidadDB->readLista($cEntidad);
	$sql = str_replace("*", "idPrueba", $sql);
	$sql = $sql . " GROUP BY idPrueba";
	//echo "<br />" . $sql;
	$rsPruebas = $conn->Execute($sql);
	$sPuebasEmpresa="";
	while (!$rsPruebas->EOF)
	{
		$sPuebasEmpresa .="," . $rsPruebas->fields['idPrueba'];
		$rsPruebas->MoveNext();
	}
	if (!empty($sPuebasEmpresa)){
		$sPuebasEmpresa =substr($sPuebasEmpresa, 1);
	}else{
		$sPuebasEmpresa="0";
	}

	$comboPRUEBAS	= new Combo($conn,"fIdPrueba","nombre","nombre","nombre","pruebas","",constant("SLC_OPCION"),"idTipoPrueba NOT IN (2,5) AND idPrueba IN (" . $sPuebasEmpresa . ") AND idPrueba NOT IN (41,49,80,97,98,99,100) ","","","nombre");

	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readLista($cEntidad){
		global $conn;
		global $cUtilidades;
		global $comboEMPRESAS;
		global $_EmpresaLogada;
		$_Empresas = "";
		if (isset($_POST["LSTIdEmpresa"])){
			if (is_array($_POST["LSTIdEmpresa"])){
				if (!in_array(" ", $_POST["LSTIdEmpresa"])){
					$_Empresas = implode(",", $_POST["LSTIdEmpresa"]);
				}
			}else{
				$_Empresas = $_POST["LSTIdEmpresa"];
			}
		}else{
			$_Empresas = $_EmpresaLogada;
		}
		$cEntidad->setIdEmpresa($_Empresas);


		$_IdProcesos = "";
		if (isset($_POST["LSTIdProceso"])){
			if (is_array($_POST["LSTIdProceso"])){
				if (!in_array(" ", $_POST["LSTIdProceso"])){
					$sIdProcesos = implode(",", $_POST["LSTIdProceso"]);
					$aIdProcesos = explode(",", $sIdProcesos);
					for ($i=0, $max = sizeof($aIdProcesos); $i < $max; $i++){
						if ($max > 1){
							$_IdProcesos .=",'" . addslashes($aIdProcesos[$i]) . "'";
						}else{
							$_IdProcesos .="," . addslashes($aIdProcesos[$i]) . "";
						}
					}
					if (!empty($_IdProcesos)){
						if ($_IdProcesos != ",''"){
							$_IdProcesos = substr($_IdProcesos, 1);
						}else {
							$_IdProcesos="";
						}
					}
				}
			}else{
				$_IdProcesos = $_POST["LSTIdProceso"];
			}
		}
		$cEntidad->setIdProceso($_IdProcesos);

		$cEntidad->setFecPrueba((isset($_POST["LSTFecPrueba"]) && $_POST["LSTFecPrueba"] != "") ? $_POST["LSTFecPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_PRUEBA"), (isset($_POST["LSTFecPrueba"]) && $_POST["LSTFecPrueba"] != "" ) ? $conn->UserDate($_POST["LSTFecPrueba"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecPruebaHast((isset($_POST["LSTFecPruebaHast"]) && $_POST["LSTFecPruebaHast"] != "") ? $_POST["LSTFecPruebaHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_PRUEBA") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecPruebaHast"]) && $_POST["LSTFecPruebaHast"] != "" ) ? $conn->UserDate($_POST["LSTFecPruebaHast"],constant("USR_FECHA"),false) : "");
		return $cEntidad;
	}

?>
							<table cellspacing="0" cellpadding="0" width="99%" border="0">
								<tr>
									<td width="48.5%" valign="top"><?php $comboPRUEBAS->setNombre("LSTDescPrueba[]");?><?php echo $comboPRUEBAS->getHTMLCombo("1","obliga",$cEntidad->getDescPrueba()," ","");?></td>
									<td width="50" valign="top">
										<table cellspacing="0" cellpadding="0" width="100%" border="0" >
											<tr>
												<td align="center" width="32">&nbsp;</td>
											</tr>
											<tr>
												<td align="center" width="32">&nbsp;</td>
											</tr>
										</table>
									</td>
									<td width="49%">&nbsp;</td>
								</tr>
							</table>
