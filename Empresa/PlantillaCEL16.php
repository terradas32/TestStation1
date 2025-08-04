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
	require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
	require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
	require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
	require_once(constant("DIR_WS_COM") . "Items/ItemsDB.php");
	require_once(constant("DIR_WS_COM") . "Items/Items.php");
	require_once(constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
	require_once(constant("DIR_WS_COM") . "Opciones/Opciones.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	
include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
	$cUtilidades	= new Utilidades();

	$_POST['fIdPrueba'] = 12;
	$_POST['fCodIdiomaIso2'] = "es";
	
	$cEntidadDB	= new ProcesosDB($conn);  // Entidad DB
	$cEntidad	= new Procesos();  // Entidad
	$cProceso_baremosDB	= new Proceso_baremosDB($conn);  // Entidad DB
	$cProceso_baremos	= new Proceso_baremos();  // Entidad
	$cProcesoInformesDB	= new Proceso_InformesDB($conn);  // Entidad DB
	$cProcesoInformes	= new Proceso_Informes();  // Entidad 
	$cProcesoPruebasDB	= new Proceso_pruebasDB($conn);  // Entidad DB
	$cProcesoPruebas	= new Proceso_pruebas();  // Entidad
	$cCandidatosDB	= new CandidatosDB($conn);  // Entidad DB
	$cCandidatos	= new Candidatos();  // Entidad
	$cRespuestas_pruebasDB	= new Respuestas_pruebasDB($conn);  // Entidad DB
	$cRespuestas_pruebas	= new Respuestas_pruebas();  // Entidad
	$cRespuestas_pruebas_itemsDB	= new Respuestas_pruebas_itemsDB($conn);  // Entidad DB
	$cRespuestas_pruebas_items	= new Respuestas_pruebas_items();  // Entidad
	$cItemsDB = new ItemsDB($conn);
	$cItems = new Items();
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	$sHijos = "";
	
	require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
	require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
	$cEmpresaPadre = new Empresas();
	$cEmpresaPadreDB = new EmpresasDB($conn);
	$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
	//$_EmpresaLogada = constant("EMPRESA_PE");
	if (empty($_POST["fHijos"]))
	{
		$sHijos = $cEmpresaPadreDB->getHijos($_EmpresaLogada);
		if (!empty($sHijos)){
			$sHijos .= $_EmpresaLogada;
		}else{
			$sHijos = $_EmpresaLogada;
		}
	}else{
		$sHijos = $_POST["fHijos"];
	}
	$sSQLPruebaIN = "";
	if (!empty($_POST["fIdEmpresa"])){
		$cEmpresaPadre->setIdEmpresa($_POST["fIdEmpresa"]);
		$cEmpresaPadre = $cEmpresaPadreDB->readEntidad($cEmpresaPadre);
		$sSQLPruebaIN = $cEmpresaPadre->getIdsPruebas();
		if (!empty($sSQLPruebaIN)){
			//chequeamos si el primer caracter es una coma
			if (substr($sSQLPruebaIN, 0, 1) == ","){
				$sSQLPruebaIN = substr($sSQLPruebaIN, 1);
			}
			$sSQLPruebaIN = " idPrueba IN (" . $sSQLPruebaIN . ") ";
		}
	}
    $comboAREAS			= new Combo($conn,"fIdArea","idArea","nombre","Descripcion","areas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboEDADES		= new Combo($conn,"fIdEdad","idEdad","nombre","Descripcion","edades","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboNIVELES		= new Combo($conn,"fIdNivel","idNivel","nombre","Descripcion","nivelesjerarquicos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboFORMACIONES 	= new Combo($conn,"fIdFormacion","idFormacion","nombre","Descripcion","formaciones","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboSEXOS			= new Combo($conn,"fIdSexo","idSexo","nombre","Descripcion","sexos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
	
	$comboEMPRESAS	= new Combo($conn,"fIdEmpresa","idEmpresa","nombre","Descripcion","empresas","",constant("SLC_OPCION"),"idEmpresa IN(" . $sHijos . ")","","orden");
	$comboTIPOS_CORREOS	= new Combo($conn,"fIdTipoCorreo","idTipoCorreo","nombre","Descripcion","tipos_correos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","fecMod");
	$comboPRUEBASGROUP	= new Combo($conn,"fIdPrueba","idPrueba",$conn->Concat("descripcion","' - '","nombre"),"Descripcion","pruebas","",constant("SLC_OPCION"),$sSQLPruebaIN,"","","idprueba");
	$comboMODO_REALIZACION	= new Combo($conn,"fIdModoRealizacion","idModoRealizacion","descripcion","Descripcion","modo_realizacion","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","fecMod");
	$comboWI_USUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","");
	
	$comboEMK_CHARSETS	= new Combo($conn,"fCodificacion","codigo",$conn->Concat("codigo", "' - '", "descripcion"),"Descripcion","emk_charsets","",constant("SLC_OPCION"),"","","fecMod DESC");
	
//	echo('modo:' . $_POST['MODO']);

	if (!isset($_POST['MODO'])){
		session_start();
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "04000 - " . constant("ERR_NO_AUTORIZADO");
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}

	switch ($_POST['MODO'])
	{
		case constant("MNT_ALTA"):
			$cEntidad	= readEntidad($cEntidad);
			if ($cEntidad->getIdProceso() ==""){
				$newId	= $cEntidadDB->insertar($cEntidad);
				$cEntidad->setIdProceso($newId);
				$cProceso_baremos->setIdProceso($cEntidad->getIdProceso());
				$IdPB = $cProceso_baremosDB->insertar($cProceso_baremos);
				$cProcesoInformes->setIdProceso($cEntidad->getIdProceso());
				$IdPI = $cProcesoInformesDB->insertar($cProcesoInformes);
				$cProcesoPruebas->setIdProceso($cEntidad->getIdProceso());
				$IdPP = $cProcesoPruebasDB->insertar($cProcesoPruebas);
			}else{
				$newId = $cEntidad->getIdProceso();
			}
			if (!empty($newId)){
				$cEntidad->setIdProceso($newId);
				$cCandidatos->setIdProceso($cEntidad->getIdProceso());
				$IdC = $cCandidatosDB->insertar($cCandidatos);
				$cRespuestas_pruebas->setIdCandidato($IdC);
				$cRespuestas_pruebas->setIdProceso($cEntidad->getIdProceso());
				$IdRP = $cRespuestas_pruebasDB->insertar($cRespuestas_pruebas);
				$sMIdItem = "";
				$sMIdOpcion = "";
				$sPIdItem = "";
				$sPIdOpcion = "";
				$iXOrdenI = 1;
				$iXOrdenF = 3;
				
				for ($i=1; $i < 53; $i++)
 				{
 					$aClicadosM = explode("-", $_POST["fIdOpcionMejor" . $i]);
 					$sMIdItem = $aClicadosM[1];
 					$sMIdOpcion = $aClicadosM[2];
 					trataOpciones($iXOrdenI, $iXOrdenF, $cEntidad, $IdC, $sMIdItem, $sMIdOpcion);
 					if (isset($_POST["fIdOpcionPeor" . $i])){
	 					$aClicadosP = explode("-", $_POST["fIdOpcionPeor" . $i]);
	 					$sPIdItem = $aClicadosP[1];
	 					$sPIdOpcion = $aClicadosP[2];
	 				    trataOpciones($iXOrdenI, $iXOrdenF, $cEntidad, $IdC, $sPIdItem, $sPIdOpcion);
 					}					
 					$iXOrdenI = $iXOrdenF + 1;
 					if ($i < 32){
 						//Primera parte opcionaes A, B y C	
 						$iXOrdenF = $iXOrdenF + 3;
 					}else{
 						//Segunda parte opcionaes A y B
 						$iXOrdenF = $iXOrdenF + 2;
 					}
 				}
				//Mandamos Generar el informe para que esté disponible en la descarga
				//Parámetros por orden
				// es proceso 1
				// MODO 627
				// fIdTipoInforme Prisma Informe completo 3
				// fCodIdiomaIso2 Idioma del informe es
				// fIdPrueba Prueba prisma 1
				// fIdEmpresa Id de empresa  3788
				// fIdProceso Id del proceso 3
				// fIdCandidato Id Candidato 1
				// fCodIdiomaIso2Prueba Idioma prueba es
				// fIdBaremo Id Baremo, prisma no tiene , le pasamos 1
													
				$cmdPost = constant("DIR_WS_GESTOR") . 'Informes_candidato.php?MODO=627&fIdTipoInforme=' . $cProcesoInformes->getIdTipoInforme() . '&fCodIdiomaIso2=' . $cProcesoInformes->getCodIdiomaInforme() . '&fIdPrueba=' . $cProcesoInformes->getIdPrueba() . '&fIdEmpresa=' . $cEntidad->getIdEmpresa() . '&fIdProceso=' . $cEntidad->getIdProceso() . '&fIdCandidato=' . $IdC . '&fCodIdiomaIso2Prueba=' . $cProcesoInformes->getCodIdiomaIso2() . '&fIdBaremo=1';
//				echo $cmdPost;exit;
				$cUtilidades->backgroundPost($cmdPost);
    
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/PlantillaRespuestas/cel16a.php');
			}
			break;
		case constant("MNT_NUEVO"):
			$_POST["fIdProceso"] = "";
			$cEntidad->setIdEmpresa($_EmpresaLogada);
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/PlantillaRespuestas/cel16a.php');
			break;
		default:
			$_POST["fIdProceso"] = "";
			$cEntidad->setIdEmpresa($_EmpresaLogada);
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/PlantillaRespuestas/cel16a.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		global $cCandidatos;
		global $cProceso_baremos;
		global $cProcesoInformes;
		global $cProcesoPruebas;
		global $cRespuestas_pruebas;
		global $comboEMPRESAS;
		
		$cEntidad->setIdEmpresa((isset($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : "");
		$cEntidad->setIdProceso((isset($_POST["fIdProceso"])) ? $_POST["fIdProceso"] : "");
		$cEntidad->setNombre((isset($_POST["fNombreProceso"])) ? $_POST["fNombreProceso"] : "");
		$cEntidad->setDescripcion((isset($_POST["fDescripcion"])) ? $_POST["fDescripcion"] : "");
		$cEntidad->setDescripcion("Alta candidato desde plantilla CEL16 [" . date('d/m/Y H:i:s') . "]");
		$cEntidad->setObservaciones((isset($_POST["fObservaciones"])) ? $_POST["fObservaciones"] : "");
		$cEntidad->setIdModoRealizacion(2);
		
		$sFechaHoraInicio =(isset($_POST["fFechaInicio"])) ? $_POST["fFechaInicio"] : "";
		$sHoraInicio =(isset($_POST["fHoraInicio"])) ? $_POST["fHoraInicio"] : "";
		if (!empty($sFechaHoraInicio) && !empty($sHoraInicio)){
			//2011-03-30 00:00:00
			$sFechaHoraInicio .= " " . $sHoraInicio . ":00";
		}
		$cEntidad->setFechaInicio($sFechaHoraInicio);
		
		$sFechaHoraFin =(isset($_POST["fFechaFin"])) ? $_POST["fFechaFin"] : "";
		$sHoraFin =(isset($_POST["fHoraFin"])) ? $_POST["fHoraFin"] : "";
		if (!empty($sFechaHoraFin) && !empty($sHoraFin)){
			//2011-03-30 00:00:00
			$sFechaHoraFin .= " " . $sHoraFin . ":00";
		} 
		$cEntidad->setFechaFin($sFechaHoraFin);
		$cEntidad->setIdModoRealizacion((isset($_POST["fIdModoRealizacion"])) ? $_POST["fIdModoRealizacion"] : "");
		$cEntidad->setEnvioContrasenas((isset($_POST["fEnvioContrasenas"])) ? $_POST["fEnvioContrasenas"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
		
		$cProceso_baremos->setIdEmpresa($cEntidad->getIdEmpresa());
		$cProceso_baremos->setIdProceso($cEntidad->getIdProceso());
		$cProceso_baremos->setIdPrueba($_POST['fIdPrueba']);
		$cProceso_baremos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cProceso_baremos->setIdBaremo(0);
		$cProceso_baremos->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cProceso_baremos->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cProceso_baremos->setUsuAlta($_cEntidadUsuarioTK->getUsuario());
		$cProceso_baremos->setUsuMod($_cEntidadUsuarioTK->getUsuario());
		
		$cProcesoInformes->setIdEmpresa($cEntidad->getIdEmpresa());
		$cProcesoInformes->setIdProceso($cEntidad->getIdProceso());
		$cProcesoInformes->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cProcesoInformes->setIdPrueba($_POST['fIdPrueba']);
		$cProcesoInformes->setCodIdiomaInforme($_POST['fCodIdiomaIso2']);
		$cProcesoInformes->setIdTipoInforme(9);
		$cProcesoInformes->setIdBaremo(0);
		$cProcesoInformes->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cProcesoInformes->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cProcesoInformes->setUsuAlta($_cEntidadUsuarioTK->getUsuario());
		$cProcesoInformes->setUsuMod($_cEntidadUsuarioTK->getUsuario());
		
		$cProcesoPruebas->setIdEmpresa($cEntidad->getIdEmpresa());
		$cProcesoPruebas->setIdProceso($cEntidad->getIdProceso());
		$cProcesoPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cProcesoPruebas->setIdPrueba($_POST['fIdPrueba']);
		$cProcesoPruebas->setOrden(1);
		$cProcesoPruebas->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cProcesoPruebas->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cProcesoPruebas->setUsuAlta($_cEntidadUsuarioTK->getUsuario());
		$cProcesoPruebas->setUsuMod($_cEntidadUsuarioTK->getUsuario());
				
		$cCandidatos->setIdEmpresa($cEntidad->getIdEmpresa());
		$cCandidatos->setIdProceso($cEntidad->getIdProceso());
		$cCandidatos->setNombre((isset($_POST["fNombre"])) ? $_POST["fNombre"] : "");
		$cCandidatos->setApellido1((isset($_POST["fApellido1"])) ? $_POST["fApellido1"] : "");
		$cCandidatos->setApellido2((isset($_POST["fApellido2"])) ? $_POST["fApellido2"] : "");
		$cCandidatos->setMail($cUtilidades->SEOTitulo($cCandidatos->getNombre()) . "@" . $cUtilidades->SEOTitulo($cCandidatos->getApellido1()) . '.com');
		$cCandidatos->setPassword($cUtilidades->newPass());
		$cCandidatos->setIdEdad((isset($_POST["fIdEdad"])) ? $_POST["fIdEdad"] : "");
		$cCandidatos->setIdSexo((isset($_POST["fIdSexo"])) ? $_POST["fIdSexo"] : "");
		$cCandidatos->setIdFormacion((isset($_POST["fIdFormacion"])) ? $_POST["fIdFormacion"] : "");
		$cCandidatos->setIdArea((isset($_POST["fIdArea"])) ? $_POST["fIdArea"] : "");
		$cCandidatos->setInformado(1);
		$cCandidatos->setFinalizado(1);
		$cCandidatos->setFechaFinalizado($sFechaHoraFin);
		$cCandidatos->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cCandidatos->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cCandidatos->setUsuAlta($_cEntidadUsuarioTK->getUsuario());
		$cCandidatos->setUsuMod($_cEntidadUsuarioTK->getUsuario());
		
		$cRespuestas_pruebas->setIdEmpresa($cEntidad->getIdEmpresa());
		$cRespuestas_pruebas->setDescEmpresa($comboEMPRESAS->getDescripcionCombo($cEntidad->getIdEmpresa()));
		$cRespuestas_pruebas->setIdProceso($cEntidad->getIdProceso());
		$cRespuestas_pruebas->setDescProceso($cEntidad->getNombre());
		$cRespuestas_pruebas->setIdCandidato($cCandidatos->getIdCandidato());
		$cRespuestas_pruebas->setDescCandidato($cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " (" . $cCandidatos->getMail() . ")" );
		$cRespuestas_pruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cRespuestas_pruebas->setDescIdiomaIso2('Español');
		$cRespuestas_pruebas->setIdPrueba($_POST['fIdPrueba']);
		$cRespuestas_pruebas->setDescPrueba('CEL 16');
		$cRespuestas_pruebas->setFinalizado(1);
		$cRespuestas_pruebas->setLeidoEjemplos(1);
		$sDif=date("H:i:s", strtotime("00:00:00") + strtotime($sHoraFin) - strtotime($sHoraInicio) );
		$aDif = explode(":", $sDif);
		$sMin = 0;
		$sSeg = 0;
		if (sizeof($aDif) > 2 ){
			//Tiene horas
			$sMin = (($aDif[0]*60) + $aDif[1]);
			$sSeg = $aDif[2];
		}else{
			$sMin = $aDif[1];
			$sSeg = $aDif[2];
		}
		$cRespuestas_pruebas->setMinutos_test($sMin);
		$cRespuestas_pruebas->setSegundos_test($sSeg);
		$cRespuestas_pruebas->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cRespuestas_pruebas->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cRespuestas_pruebas->setUsuAlta($_cEntidadUsuarioTK->getUsuario());
		$cRespuestas_pruebas->setUsuMod($_cEntidadUsuarioTK->getUsuario());
		
		return $cEntidad;
	}
	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readLista($cEntidad){
		global $conn;
		global $cUtilidades;
		$cEntidad->setIdProceso((isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "") ? $_POST["LSTIdProceso"] : "");	$cEntidad->setBusqueda(constant("STR_ID_PROCESO"), (isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "" ) ? $_POST["LSTIdProceso"] : "");
		$cEntidad->setIdProcesoHast((isset($_POST["LSTIdProcesoHast"]) && $_POST["LSTIdProcesoHast"] != "") ? $_POST["LSTIdProcesoHast"] : "");	$cEntidad->setBusqueda(constant("STR_ID_PROCESO") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdProcesoHast"]) && $_POST["LSTIdProcesoHast"] != "" ) ? $_POST["LSTIdProcesoHast"] : "");
		global $comboEMPRESAS;
		$cEntidad->setIdEmpresa((isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "") ? $_POST["LSTIdEmpresa"] : "");	$cEntidad->setBusqueda(constant("STR_EMPRESA"), (isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "" ) ? $comboEMPRESAS->getDescripcionCombo($_POST["LSTIdEmpresa"]) : "");
		$cEntidad->setNombre((isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "") ? $_POST["LSTNombre"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE"), (isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "" ) ? $_POST["LSTNombre"] : "");
		$cEntidad->setDescripcion((isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "") ? $_POST["LSTDescripcion"] : "");	$cEntidad->setBusqueda(constant("STR_DESCRIPCION"), (isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "" ) ? $_POST["LSTDescripcion"] : "");
		$cEntidad->setObservaciones((isset($_POST["LSTObservaciones"]) && $_POST["LSTObservaciones"] != "") ? $_POST["LSTObservaciones"] : "");	$cEntidad->setBusqueda(constant("STR_OBSERVACIONES"), (isset($_POST["LSTObservaciones"]) && $_POST["LSTObservaciones"] != "" ) ? $_POST["LSTObservaciones"] : "");
		$cEntidad->setFechaInicio((isset($_POST["LSTFechaInicio"]) && $_POST["LSTFechaInicio"] != "") ? $_POST["LSTFechaInicio"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_INICIO"), (isset($_POST["LSTFechaInicio"]) && $_POST["LSTFechaInicio"] != "" ) ? $conn->UserDate($_POST["LSTFechaInicio"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFechaInicioHast((isset($_POST["LSTFechaInicioHast"]) && $_POST["LSTFechaInicioHast"] != "") ? $_POST["LSTFechaInicioHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_INICIO") . " " . constant("STR_HASTA"), (isset($_POST["LSTFechaInicioHast"]) && $_POST["LSTFechaInicioHast"] != "" ) ? $conn->UserDate($_POST["LSTFechaInicioHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFechaFin((isset($_POST["LSTFechaFin"]) && $_POST["LSTFechaFin"] != "") ? $_POST["LSTFechaFin"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_FIN"), (isset($_POST["LSTFechaFin"]) && $_POST["LSTFechaFin"] != "" ) ? $conn->UserDate($_POST["LSTFechaFin"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFechaFinHast((isset($_POST["LSTFechaFinHast"]) && $_POST["LSTFechaFinHast"] != "") ? $_POST["LSTFechaFinHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_FIN") . " " . constant("STR_HASTA"), (isset($_POST["LSTFechaFinHast"]) && $_POST["LSTFechaFinHast"] != "" ) ? $conn->UserDate($_POST["LSTFechaFinHast"],constant("USR_FECHA"),false) : "");
		global $comboMODO_REALIZACION;
		$cEntidad->setIdModoRealizacion((isset($_POST["LSTIdModoRealizacion"]) && $_POST["LSTIdModoRealizacion"] != "") ? $_POST["LSTIdModoRealizacion"] : "");	$cEntidad->setBusqueda(constant("STR_MODO_REALIZACION_PRUEBAS"), (isset($_POST["LSTIdModoRealizacion"]) && $_POST["LSTIdModoRealizacion"] != "" ) ? $comboTIPOS_PROCESO->getDescripcionCombo($_POST["LSTIdModoRealizacion"]) : "");
		$cEntidad->setFecAlta((isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "") ? $_POST["LSTFecAlta"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA"), (isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "" ) ? $conn->UserDate($_POST["LSTFecAlta"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecAltaHast((isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "") ? $_POST["LSTFecAltaHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "" ) ? $conn->UserDate($_POST["LSTFecAltaHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecMod((isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "") ? $_POST["LSTFecMod"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_MODIFICACION"), (isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "" ) ? $conn->UserDate($_POST["LSTFecMod"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecModHast((isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "") ? $_POST["LSTFecModHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_MODIFICACION") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "" ) ? $conn->UserDate($_POST["LSTFecModHast"],constant("USR_FECHA"),false) : "");
		global $comboWI_USUARIOS;
		$cEntidad->setUsuAlta((isset($_POST["LSTUsuAlta"]) && $_POST["LSTUsuAlta"] != "") ? $_POST["LSTUsuAlta"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO_DE_ALTA"), (isset($_POST["LSTUsuAlta"]) && $_POST["LSTUsuAlta"] != "" ) ? $comboWI_USUARIOS->getDescripcionCombo($_POST["LSTUsuAlta"]) : "");
		$cEntidad->setUsuMod((isset($_POST["LSTUsuMod"]) && $_POST["LSTUsuMod"] != "") ? $_POST["LSTUsuMod"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO_DE_MODIFICACION"), (isset($_POST["LSTUsuMod"]) && $_POST["LSTUsuMod"] != "" ) ? $comboWI_USUARIOS->getDescripcionCombo($_POST["LSTUsuMod"]) : "");
		$cEntidad->setOrderBy((!empty($_POST["LSTOrderBy"])) ? $_POST["LSTOrderBy"] : "fecMod");	$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), (!empty($_POST["LSTOrderBy"])) ? $_POST["LSTOrderBy"] : "fecMod");
		$cEntidad->setOrder((!empty($_POST["LSTOrder"])) ? $_POST["LSTOrder"] : "DESC");	$cEntidad->setBusqueda(constant("STR_ORDEN"), (!empty($_POST["LSTOrder"])) ? $_POST["LSTOrder"] : "DESC");
		$cEntidad->setLineasPagina((!empty($_POST["LSTLineasPagina"]) && is_numeric($_POST["LSTLineasPagina"])) ? $_POST["LSTLineasPagina"] : constant("CNF_LINEAS_PAGINA"));
		$_POST["LSTLineasPagina"] = $cEntidad->getLineasPagina();
		return $cEntidad;
	}
	/*
	* "Lee" los parametros recibidos en el request, los asocia a
	* una determinada Entidad para borrar las imagenes seleccionadas.
	*/
	function quitaImg($cEntidad, $cEntidadDB, $bBorrar= false){
		$bLlamada=false;
		if ($bBorrar){
			setBorradoRegistro();
		}
		if ($bLlamada){
			$cEntidadDB->quitaImagen($cEntidad);
		}
	}
	/*
	* "Setea" el request, para el borrado de imagenes
	* cuando es un borrado del registro.
	*/
	function setBorradoRegistro(){
	}

	
	function trataOpciones($iXOrdenI, $iXOrdenF, $cEntidad, $IdC, $sIdItem, $sIdOpcion)
	{
		global $conn;
		global $cRespuestas_pruebas_itemsDB;
		global $cItemsDB;
	    $cItems = new Items();
	    
	    // Monto una lista de items con los items ya que mando los parámetros
	    // fInicio y fFin y se lo asigno al orden y al ordenHast.
	    
	    $cItems->setIdPrueba($_POST["fIdPrueba"]);
	    $cItems->setIdPruebaHast($_POST["fIdPrueba"]);
	    $cItems->setOrden($iXOrdenI);
	    $cItems->setOrdenHast($iXOrdenF);
	    $cItems->setCodIdiomaIso2($_POST["fCodIdiomaIso2"]);
	    
	    $sqlItems = $cItemsDB->readLista($cItems);
//	    echo "<br />IdItem:: " . $sIdItem . " - IdOpcion:: " . $sIdOpcion . " SQL:: " . $sqlItems; 
	    $listaItems = $conn->Execute($sqlItems);
	    
	    while(!$listaItems->EOF)
	    {
	    	$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
		    $cRespuestas_pruebas_items->setIdEmpresa($cEntidad->getIdEmpresa());
			$cRespuestas_pruebas_items->setIdProceso($cEntidad->getIdProceso());
			$cRespuestas_pruebas_items->setIdCandidato($IdC);
			$cRespuestas_pruebas_items->setIdPrueba($_POST["fIdPrueba"]);
			$cRespuestas_pruebas_items->setCodIdiomaIso2($_POST["fCodIdiomaIso2"]);
			$cRespuestas_pruebas_items->setIdItem($listaItems->fields['idItem']);
			$cRespuestas_pruebas_items->setOrden($listaItems->fields['orden']);
			$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsDB->readEntidad($cRespuestas_pruebas_items);
			
			//Miro si el item que estoy mirando en la lista se corresponde al que he modificado en el cuestionario.
			if($cRespuestas_pruebas_items->getIdItem() == $sIdItem){
				//Machaco el valor que ya tiene en base de datos e inserto el nuevo registro.
				$cRespuestas_pruebas_items->setIdOpcion($sIdOpcion);
				$cRespuestas_pruebas_itemsDB->borrar($cRespuestas_pruebas_items);
				$cRespuestas_pruebas_itemsDB->insertar($cRespuestas_pruebas_items);
			}else{
				//Si no coinciden miro a ver si ya habia dada de alta esa misma opción para este grupo de items
				if($cRespuestas_pruebas_items->getIdOpcion() == $sIdOpcion){
					//Si la hay le doy el valor 0 y borro e inserto
					$cRespuestas_pruebas_items->setIdOpcion("0");
					$cRespuestas_pruebas_itemsDB->borrar($cRespuestas_pruebas_items);
					$cRespuestas_pruebas_itemsDB->insertar($cRespuestas_pruebas_items);
				}else{
					//Si no es el caso, pregunto si no hay opción, por lo tanto no existe el registro
					// y lo doy de alta con idOpcion 0
					if($cRespuestas_pruebas_items->getIdOpcion() == ""){
						$cRespuestas_pruebas_items->setIdOpcion("0");
						$cRespuestas_pruebas_itemsDB->borrar($cRespuestas_pruebas_items);
						$cRespuestas_pruebas_itemsDB->insertar($cRespuestas_pruebas_items);
					}
					// Si no, no hago nada puesto que tiene que haber una de cada en cada grupo de items
				}
			}
			$listaItems->MoveNext();
	    } 
	}
?>