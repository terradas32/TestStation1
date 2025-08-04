<?php
// Ignorar los abortos hechos por el usuario y permitir que el script
// se ejecute para siempre
ignore_user_abort(true);
set_time_limit(0);

header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

$_bIsProceso = false;
$_sPATH_CONFIG="";
$_proceso=(isset($argv[1])) ? $argv[1] : "";
if ($_proceso == ""){
	$_bIsProceso = false;	
}else{
	//Parámetros por orden
	// es proceso 1
	// MODO 627 -- MNT_EXPORT
	// fIdTipoInforme
	// fCodIdiomaIso2
	// fIdPrueba
	// fIdEmpresa
	// fIdProceso
	// fIdCandidato
	// fCodIdiomaIso2Prueba
	// fIdBaremo
	$_bIsProceso = true;
	$_POST['MODO'] = $argv[2];
	$_POST['fIdTipoInforme'] = $argv[3];
	$_POST['fCodIdiomaIso2'] = $argv[4];
	$_POST['fIdPrueba'] = $argv[5];
	$_POST['fIdEmpresa'] = $argv[6];
	$_POST['fIdProceso'] = $argv[7];
	$_POST['fIdCandidato'] = $argv[8];
	$_POST['fCodIdiomaIso2Prueba'] = $argv[9];
	$_POST['fIdBaremo'] = $argv[10];
	$_sPATH_CONFIG = 'C:\Datos\xampp\htdocs/TestStation/Admin/';
}

ob_start();
	require($_sPATH_CONFIG . 'include/Configuracion.php');
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . 'Idiomas.php');
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . 'Seguridad.php');
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas_empresas/Informes_pruebas_empresasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas_empresas/Informes_pruebas_empresas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_informes/Tipos_informesDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_informes/Tipos_informes.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos/BaremosDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos/Baremos.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_competencias/Baremos_competenciasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_competencias/Baremos_competencias.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
	
	
include_once ('include/conexion.php');
	
	//require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
	$cUtilidades	= new Utilidades();
	
	$cTipos_informesDB	= new Tipos_informesDB($conn);  // Entidad DB
	$cIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
	$cBaremosDB	= new BaremosDB($conn);  // Entidad DB
	$cBaremos_competenciasDB	= new Baremos_competenciasDB($conn);  // Entidad DB
	$cInformes_pruebasDB	= new Informes_pruebasDB($conn);  // Entidad DB
	$cInformes_pruebas_empresasDB	= new Informes_pruebas_empresasDB($conn);  // Entidad DB
	$cEmpresasDB	= new EmpresasDB($conn);  // Entidad DB
	$cCandidatosDB	= new CandidatosDB($conn);  // Entidad DB
	$cProcesosDB	= new ProcesosDB($conn);  // Entidad DB
	$cPruebasDB	= new PruebasDB($conn);  // Entidad DB
	$cProceso_informesDB = new Proceso_informesDB($conn);	// Entidad DB
	
	$cEntidadDB	= new Respuestas_pruebasDB($conn);  // Entidad DB
	$cEntidad	= new Respuestas_pruebas();  // Entidad
	
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	//Sacamos el literal del baremo para pintarlo en los informes si lo tiene
	$cBaremos = new Baremos();
	$cBaremosDB = new BaremosDB($conn);
	$cBaremos->setIdBaremo($_POST['fIdBaremo']);
	$cBaremos->setIdPrueba($_POST['fIdPrueba']);
	$cBaremos = $cBaremosDB->readEntidad($cBaremos);
	$_sBaremo = $cBaremos->getNombre();
	
	$sHijos = "";
	$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . 'Empresas/Empresas.php');
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
	
	$cEmpresaPadre = new Empresas();
	$cEmpresaPadreDB = new EmpresasDB($conn);
	//	$_EmpresaLogada = constant("EMPRESA_PE");
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
	
	if (empty($_POST["fIdEmpresa"])){
		$_POST["fIdEmpresa"] = $_EmpresaLogada;
	}
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
	
	$comboEMPRESAS	= new Combo($conn,"fIdEmpresa","idEmpresa","nombre","Descripcion","empresas","",constant("SLC_OPCION"),"idEmpresa IN(" . $sHijos . ")","","orden");
	$comboDESC_EMPRESAS	= new Combo($conn,"_fDescEmpresa","idEmpresa","nombre","Descripcion","empresas","","","","","fecMod");
	$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","",constant("SLC_OPCION"),"","","fecMod");
	$comboDESC_PROCESOS	= new Combo($conn,"_fDescProceso","idProceso","nombre","Descripcion","procesos","","","","","fecMod");
	$comboCANDIDATOS	= new Combo($conn,"fIdCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","",constant("SLC_OPCION"),"","","nombre,apellido1,apellido2,mail");
	$comboDESC_CANDIDATOS	= new Combo($conn,"_fDescCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","",constant("SLC_OPCION"),"","","fecMod");
	$comboWI_IDIOMAS	= new Combo($conn,"fCodIdiomaIso2","codIdiomaIso2","nombre","Descripcion","wi_idiomas","",constant("SLC_OPCION"),"activoFront=1","","fecMod");
	$comboDESC_WI_IDIOMAS	= new Combo($conn,"_fDescIdiomaIso2","codIdiomaIso2","nombre","Descripcion","wi_idiomas","","","","","fecMod");
//	$comboPRUEBAS	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboPRUEBAS	= new Combo($conn,"fIdPrueba","idPrueba",$conn->Concat("nombre","' - '","descripcion"),"Descripcion","pruebas","",constant("SLC_OPCION"),$sSQLPruebaIN . "AND bajaLog=0","","","idprueba");
	$comboDESC_PRUEBAS	= new Combo($conn,"_fDescPrueba","idPrueba","nombre","Descripcion","pruebas","","","","","fecMod");
	$comboWI_USUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","fecMod");
	$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
//	echo('modo:' . $_POST['MODO']);
	
	if (!isset($_POST["MODO"])){
		session_start();
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "04000 - " . constant("ERR_NO_AUTORIZADO");
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}
	switch ($_POST['MODO'])
	{
		case constant("MNT_ALTA"):
			$cEntidad	= readEntidad($cEntidad);
			$newId	= $cEntidadDB->insertar($cEntidad);
			if (!empty($newId)){
			?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
				{
					$cEntidad = readLista($cEntidad);
					if ($cEntidad->getIdEmpresa() == ""){
							$cEntidad->setIdEmpresa($sHijos);
					}
					if (!empty($_POST['respuestas_pruebas_next_page']) && $_POST['respuestas_pruebas_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}
					$pager = new ADODB_Pager($conn,$sql,'respuestas_pruebas');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Informes_candidato_zip/mntrespuestas_pruebasl.php');
				}else{
					$cEntidad	= new Respuestas_pruebas();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Informes_candidato_zip/mntrespuestas_pruebasa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Informes_candidato_zip/mntrespuestas_pruebasa.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			if ($cEntidadDB->modificar($cEntidad))
			{
				$cEntidad = readLista($cEntidad);
				if ($cEntidad->getIdEmpresa() == ""){
						$cEntidad->setIdEmpresa($sHijos);
				}
				if (!empty($_POST['respuestas_pruebas_next_page']) && $_POST['respuestas_pruebas_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
				$pager = new ADODB_Pager($conn,$sql,'respuestas_pruebas');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Informes_candidato_zip/mntrespuestas_pruebasl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Informes_candidato_zip/mntrespuestas_pruebasa.php');
			}
			break;
		case constant("MNT_BORRAR"):
			$cEntidad = readEntidad($cEntidad);
			if (!$cEntidadDB->borrar($cEntidad))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
			}
			$cEntidad = readLista($cEntidad);
			if ($cEntidad->getIdEmpresa() == ""){
					$cEntidad->setIdEmpresa($sHijos);
			}
			if (!empty($_POST['respuestas_pruebas_next_page']) && $_POST['respuestas_pruebas_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}
			$pager = new ADODB_Pager($conn,$sql,'respuestas_pruebas');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Informes_candidato_zip/mntrespuestas_pruebasl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Informes_candidato_zip/mntrespuestas_pruebasa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			
			$cRespPruebas = new Respuestas_pruebas();
			$cCandidato = new Candidatos();
			$cProceso = new Procesos();
			$cEmpresa =  new Empresas();
			$cProceso_informes =  new Proceso_informes();
			
			//Cargamos el listado de pruebas finalizadas para el proceso y el candidato indicado
			$cRespPruebas->setIdEmpresa($cEntidad->getIdEmpresa());
			$cRespPruebas->setIdProceso($cEntidad->getIdProceso());
			$cRespPruebas->setIdCandidato($cEntidad->getIdCandidato());
			$cRespPruebas->setFinalizado("1");
			$cRespPruebas->setFinalizadoHast("1");
			$sqlPruebasFin = $cEntidadDB->readListaIN($cRespPruebas);
			$listaPruebasFin = $conn->Execute($sqlPruebasFin);
			
			$aPruebas =array();
			if($listaPruebasFin->recordCount()){
				$i=0;
				while(!$listaPruebasFin->EOF){
					$cPrueba = new Pruebas();
					
					$cPrueba->setIdPrueba($listaPruebasFin->fields['idPrueba']);
					$cPrueba->setCodIdiomaIso2($listaPruebasFin->fields['codIdiomaIso2']);
					
					$cPrueba =  $cPruebasDB->readEntidad($cPrueba);
					
					$aPruebas[$i] = $cPrueba;
					
					$i++;
					$listaPruebasFin->MoveNext();
				}

			}
			
			$cCandidato->setIdEmpresa($cEntidad->getIdEmpresa());
			$cCandidato->setIdProceso($cEntidad->getIdProceso());
			$cCandidato->setIdCandidato($cEntidad->getIdCandidato());
			
			$cCandidato = $cCandidatosDB->readEntidad($cCandidato);
			
			$cProceso->setIdProceso($cEntidad->getIdProceso());
			$cProceso->setIdEmpresa($cEntidad->getIdEmpresa());
			$cProceso = $cProcesosDB->readEntidad($cProceso);
			
			$cEmpresa->setIdEmpresa($cEntidad->getIdEmpresa());
			$cEmpresa = $cEmpresasDB->readEntidad($cEmpresa);
			
			$cProceso_informes->setIdEmpresa($cEntidad->getIdEmpresa());
			$cProceso_informes->setIdProceso($cEntidad->getIdProceso());
			$cProceso_informes->setIdPrueba($cEntidad->getIdPrueba());
			$cProceso_informes->setCodIdiomaIso2($cEntidad->getCodIdiomaIso2());
			$sSQLProceso_informes = $cProceso_informesDB->readLista($cProceso_informes);
			$rsProceso_informes = $conn->Execute($sSQLProceso_informes);
			$cProceso_informes->setCodIdiomaInforme($rsProceso_informes->fields['codIdiomaInforme']);
			$cProceso_informes->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
//			$cProceso_informes->setIdBaremo($rsProceso_informes->fields['idBaremo']);
			$cProceso_informes = $cProceso_informesDB->readEntidad($cProceso_informes);
			
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Informes_candidato_zip/mntrespuestas_pruebasa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Informes_candidato_zip/mntrespuestas_pruebas.php');
			break;
		case constant("MNT_LISTAR"):
			$cEntidad = readLista($cEntidad);
			if ($cEntidad->getIdEmpresa() == ""){
					$cEntidad->setIdEmpresa($sHijos);
			}
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}else{
				if (!empty($_POST['respuestas_pruebas_next_page']) && $_POST['respuestas_pruebas_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
			}
//			echo "<br />" . $sql;
			$pager = new ADODB_Pager($conn,$sql,'respuestas_pruebas');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			$LnPag = 999999;
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			
		$valid_files = array();
		$NO_valid_files = array();
		$error="";
		$zip_name="";
		$GETzip_name="";
		while (!$lista->EOF)
		{
			
			$cPrueba = new Pruebas();
			$cPrueba->setIdPrueba($lista->fields['idPrueba']);
			$cPrueba->setCodIdiomaIso2($lista->fields['codIdiomaIso2']);
			$cPrueba =  $cPruebasDB->readEntidad($cPrueba);
			
			$cCandidato = new Candidatos();			
			$cCandidato->setIdEmpresa($lista->fields['idEmpresa']);
			$cCandidato->setIdProceso($lista->fields['idProceso']);
			$cCandidato->setIdCandidato($lista->fields['idCandidato']);
			$cCandidato = $cCandidatosDB->readEntidad($cCandidato);

			$cProceso_informes =  new Proceso_informes();
			$cProceso_informes->setIdEmpresa($lista->fields['idEmpresa']);
			$cProceso_informes->setIdProceso($lista->fields['idProceso']);
			$cProceso_informes->setIdPrueba($lista->fields['idPrueba']);
			$cProceso_informes->setCodIdiomaIso2($lista->fields['codIdiomaIso2']);
			$sSQLProceso_informes = $cProceso_informesDB->readLista($cProceso_informes);
//			echo "<br />" . $sSQLProceso_informes;
			$rsProceso_informes = $conn->Execute($sSQLProceso_informes);
			$cProceso_informes->setCodIdiomaInforme($rsProceso_informes->fields['codIdiomaInforme']);
			$cProceso_informes->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
			$cProceso_informes = $cProceso_informesDB->readEntidad($cProceso_informes);

			//Miramos si ya esiste el pdf en caso que no esista lo generamos
//			$sNombre = $cUtilidades->SEOTitulo($cPrueba->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $lista->fields['idEmpresa'] . "_" . $lista->fields['idProceso'] . "_" . $rsProceso_informes->fields['idTipoInforme'] . "_" . $rsProceso_informes->fields['codIdiomaInforme']);
			$_sIdBaremo = $rsProceso_informes->fields['idBaremo'];
//			echo "<br />" . $sSQLProceso_informes;
			$_sIdBaremo = (empty($_sIdBaremo)) ? 1 : $_sIdBaremo;
			$sNombre = $cUtilidades->SEOTitulo($cPrueba->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $lista->fields['idEmpresa'] . "_" . $lista->fields['idProceso'] . "_" . $rsProceso_informes->fields['idTipoInforme'] . "_" . $rsProceso_informes->fields['codIdiomaInforme'] . "_" . $_sIdBaremo);
			$sDirImg="imgInformes/";
			$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
			$_ficheroPDF = $spath . $sDirImg . $sNombre . ".pdf";
			
			
					
			if(is_file($_ficheroPDF)){
				$valid_files[] = $_ficheroPDF;
//				echo "<br />Fichero encontrado:: " . $_ficheroPDF;
			}else{
				$NO_valid_files[] = $_ficheroPDF;
//				$error .= "<br />¡Fichero NO ENCONTRADO! - Se manda Generar :: " . basename($_ficheroPDF);
				$error .= "<br />¡Fichero NO ENCONTRADO! :: " . basename($_ficheroPDF);
//					//Mandamos Generar el informe para que esté disponible en la descarga
//					//Parámetros por orden
//					// es proceso 1
//					// MODO 627
//					// fIdTipoInforme Prisma Informe completo 3
//					// fCodIdiomaIso2 Idioma del informe es
//					// fIdPrueba Prueba prisma 24
//					// fIdEmpresa Id de empresa  3788
//					// fIdProceso Id del proceso 3
//					// fIdCandidato Id Candidato 1
//					// fCodIdiomaIso2Prueba Idioma prueba es
//					// fIdBaremo Id Baremo, prisma no tiene , le pasamos 1
//
//				    $cInformes_pruebas = new Informes_pruebas_empresas();
//	    			$cInformes_pruebas->setIdPrueba($lista->fields['idPrueba']);
//	    			$cInformes_pruebas->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
//	    			$cInformes_pruebas->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
//	    			$cInformes_pruebas->setIdEmpresa($rsProceso_informes->fields['idEmpresa']);
//	    			
//					$sql_IPE = $cInformes_pruebas_empresasDB->readLista($cInformes_pruebas);
//					$rsIPE = $conn->Execute($sql_IPE);
//	    			if ($rsIPE->NumRows() > 0){
//	    				$cInformes_pruebas = $cInformes_pruebas_empresasDB->readEntidad($cInformes_pruebas);
//	    			}else {
//		    			$cInformes_pruebas = new Informes_pruebas();
//		    			$cInformes_pruebas->setIdPrueba($lista->fields['idPrueba']);
//		    			$cInformes_pruebas->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
//		    			$cInformes_pruebas->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
//						$cInformes_pruebas = $cInformes_pruebasDB->readEntidad($cInformes_pruebas);	    				
//		   			}
//				
//					$cmdPost = constant("DIR_WS_GESTOR") . 'Informes_candidato.php?MODO=627&fIdTipoInforme=' . $cInformes_pruebas->getIdTipoInforme() . '&fCodIdiomaIso2=' . $cInformes_pruebas->getCodIdiomaIso2() . '&fIdPrueba=' . $lista->fields['idPrueba'] . '&fIdEmpresa=' . $lista->fields['idEmpresa'] . '&fIdProceso=' . $lista->fields['idProceso'] . '&fIdCandidato=' . $lista->fields['idCandidato'] . '&fCodIdiomaIso2Prueba=' . $rsProceso_informes->fields['codIdiomaInforme'] . '&fIdBaremo=' . $_sIdBaremo;
////					echo "<br />" . $cmdPost;
//					$cUtilidades->backgroundPost($cmdPost);
				
			}			
						
			$lista->MoveNext();
		}

if(count($valid_files) > 0){
	
    $zip = new ZipArchive();
    $zip_name = $spath . $sDirImg . time(). ".zip";
    $GETzip_name = $sDirImg . time(). ".zip"; 
    if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE){
        $error .= "<br />No se ha podido crear el archivo ZIP en este momento.";
    }else{
	    foreach($valid_files as $file){
	    	if(!$zip->addFile($file, basename($file))){
	        	$error .= "<br />No se ha podido añadir el fichero::" . basename($file);
	    	}
	    }
	    $zip->close();
    }
} else {
    $error .= "<br />No existen informes validos para la descarga.";
}		 
			$lista->MoveFirst();
			include('Template/Informes_candidato_zip/mntrespuestas_pruebasl.php');
			break;
		case constant("MNT_LISTAIDIOMAS"):
			$aIdiomas[]="";
			
			if((isset($_POST['fIdPrueba']) && $_POST['fIdPrueba']!="") && (isset($_POST['fIdTipoInforme']) && $_POST['fIdTipoInforme']!="")){
				$cInformes_pruebas	= new Informes_pruebas();
				$cInformes_pruebas->setIdPrueba($_POST['fIdPrueba']);
				$cInformes_pruebas->setIdTipoInforme($_POST['fIdTipoInforme']);
				$sqlPruebas= $cInformes_pruebasDB->readLista($cInformes_pruebas);
				$listaPruebas = $conn->Execute($sqlPruebas);
				//echo $sqlPruebas . "<br />";
				$i=0;
				while(!$listaPruebas->EOF){
					$cIdiomas = new Idiomas();
					$cIdiomas->setCodIso2($listaPruebas->fields['codIdiomaIso2']);
					$cIdiomas = $cIdiomasDB->readEntidad($cIdiomas);
					
					$aIdiomas[$i] = $cIdiomas;
					$i++;
					$listaPruebas->MoveNext();
				}
			}
			include('Template/Informes_candidato_zip/listaidiomas.php');
			break;
		case constant("MNT_LISTATIPOS"):
			
			if(isset($_POST['fIdPrueba']) && $_POST['fIdPrueba']!=""){
				$cInformes_pruebas	= new Informes_pruebas();
				$cInformes_pruebas->setIdPrueba($_POST['fIdPrueba']);
				$cInformes_pruebas->setCodIdiomaIso2($sLang);
				
				$sqlTipos= $cInformes_pruebasDB->readLista($cInformes_pruebas);
				$listaTipos = $conn->Execute($sqlTipos);
			}
			include('Template/Informes_candidato_zip/checkstipos.php');
			break;
			
		case constant("MNT_LISTABAREMOS"):
			if(isset($_POST['fIdPrueba']) && $_POST['fIdPrueba']!=""){
				$sMensaje ="";
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
				//Comprobamos si es de tipo prisma (Personalidad), 
				//ya que este tipo no tiene baremo por prueba,
				//Si no por escalas
				$bPintaBaremo=true;
				$cEscalas_items=  new Escalas_items();
				$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
				$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
				$sqlEscalas_items= $cEscalas_itemsDB->readLista($cEscalas_items);
				$rsEscalas_items = $conn->Execute($sqlEscalas_items);
				//////////////////////
				if($rsEscalas_items->recordCount() > 0){
					$bPintaBaremo=false;
				}
				$cBaremos	= new Baremos();
				$cBaremos->setIdPrueba($_POST['fIdPrueba']);
				$cBaremos->setIdPruebaHast($_POST['fIdPrueba']);
				
				$sqlBaremos= $cBaremosDB->readLista($cBaremos);
				$listaBaremos = $conn->Execute($sqlBaremos);
			}
			include('Template/Informes_candidato_zip/listabaremos.php');
			break;
		case constant("MNT_EXPORTA"):
				
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/ItemsDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/Items.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/Opciones.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valoresDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valores.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados_competencias/Baremos_resultados_competenciasDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados_competencias/Baremos_resultados_competencias.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Nivelesjerarquicos/NivelesjerarquicosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Nivelesjerarquicos/Nivelesjerarquicos.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Secciones_informes/Secciones_informesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Secciones_informes/Secciones_informes.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_secciones/Textos_seccionesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_secciones/Textos_secciones.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textos.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_irDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_ir.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ipDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ip.php");
				
				$cTextos_seccionesDB = new Textos_seccionesDB($conn);
				$cSecciones_informesDB = new Secciones_informesDB($conn);
				$cRangos_ipDB = new Rangos_ipDB($conn);
				$cRangos_irDB = new Rangos_irDB($conn);
				$cRangos_textosDB = new Rangos_textosDB($conn);
				
			    //Cambiar Dongels por Cliente/Prueba/Informe
	    		//Miramos si tiene definido dongles por empresa
    			$cInformesPruebasTrf = new Informes_pruebas_empresas();
    			$cInformesPruebasTrf->setIdPrueba($_POST['fIdPrueba']);
    			$cInformesPruebasTrf->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    			$cInformesPruebasTrf->setIdTipoInforme($_POST['fIdTipoInforme']);
    			$cInformesPruebasTrf->setIdEmpresa($_POST['fIdEmpresa']);
    			
				$sql_IPE = $cInformes_pruebas_empresasDB->readLista($cInformesPruebasTrf);
				$rsIPE = $conn->Execute($sql_IPE);
    			if ($rsIPE->NumRows() > 0){
    				$cInformesPruebasTrf = $cInformes_pruebas_empresasDB->readEntidad($cInformesPruebasTrf);
    			}else {
					$cInformesPruebasTrf = new Informes_pruebas();
					$cInformesPruebasTrf->setIdPrueba($_POST['fIdPrueba']);
					$cInformesPruebasTrf->setIdTipoInforme($_POST['fIdTipoInforme']);
					$cInformesPruebasTrf->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
					$cInformesPruebasTrf= $cInformes_pruebasDB->readEntidad($cInformesPruebasTrf);	    				
	   			}
				
				$cEmpresaDng = new Empresas();
				$cEmpresaDng->setIdEmpresa($_POST['fIdEmpresa']);
				$cEmpresaDng = $cEmpresasDB->readEntidad($cEmpresaDng);
				
				$dTotalCoste=$cInformesPruebasTrf->getTarifa();
				
				$bDescargar=false;
				if ($cEmpresaDng->getPrepago() == 0 || $_EmpresaLogada == constant("EMPRESA_PE")){
					//Descargamos siempre si la empresa logada es PE o NO es por prepago 
					$bDescargar=true;
				}else{
					if($cInformesPruebasTrf->getTarifa() <= $cEmpresaDng->getDongles()){
						$bDescargar=true;
					}else{
						$bDescargar=false;
					}
				}
				$bPDFGenerado = false;	//Indica si el fichero fue generado previamente
				if($bDescargar)
				{
					$cCandidato = new Candidatos();
					$cCandidato->setIdEmpresa($_POST['fIdEmpresa']);
					$cCandidato->setIdProceso($_POST['fIdProceso']);
					$cCandidato->setIdCandidato($_POST['fIdCandidato']);
					
					$cCandidato =  $cCandidatosDB->readEntidad($cCandidato);
					
					$cPruebas = new Pruebas();
					$cPruebas->setIdPrueba($_POST['fIdPrueba']);
					$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
					$cPruebas = $cPruebasDB->readEntidad($cPruebas);
					
					//Miramos si ya esiste el pdf en caso que no esista lo generamos
//					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa'] . "_" . $_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2']);
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa'] . "_" . $_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
					$_ficheroPDF = $spath . $sDirImg . $sNombre . ".pdf";
		
					if(is_file($_ficheroPDF)){
						//De momento que siempre lo genere, si se quiere cambiar habría 
						//que incluir el idioma en el nombre del fichero 
						//y cambiarlo en los templates de generación
						$bPDFGenerado = true;
					}else{
						$bPDFGenerado = false;
					}
					if (!$bPDFGenerado)
					{
						$cRespuestasPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
						$cItemsDB = new ItemsDB($conn);
						$cNivelesjerarquicosDB = new NivelesjerarquicosDB($conn);
						$cOpcionesDB = new OpcionesDB($conn);
						$cOpciones_valoresDB = new Opciones_valoresDB($conn);
						$cBaremos_resultadosDB = new Baremos_resultadosDB($conn);
						
						$idTipoPrueba = $cPruebas->getIdTipoPrueba();
						
						$idTipoInforme=$_POST['fIdTipoInforme'];
						
						$cRespuestasPruebasItems = new Respuestas_pruebas_items();
						
						$cRespuestasPruebasItems->setIdCandidato($_POST['fIdCandidato']);
						$cRespuestasPruebasItems->setIdPrueba($_POST['fIdPrueba']);
						$cRespuestasPruebasItems->setIdEmpresa($_POST['fIdEmpresa']);
						$cRespuestasPruebasItems->setIdProceso($_POST['fIdProceso']);
						$cRespuestasPruebasItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$cRespuestasPruebasItems->setOrderBy("idItem");
						$cRespuestasPruebasItems->setOrder("ASC");
						
						$cIt = new Items();
						$cIt->setIdPrueba($_POST['fIdPrueba']);
						$cIt->setIdPruebaHast($_POST['fIdPrueba']);
						$cIt->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$sqlItemsPrueba= $cItemsDB->readLista($cIt);
						$listaItemsPrueba = $conn->Execute($sqlItemsPrueba);
						// Montamos la lista de respuestas para los parámetros enviados. 
						
						$sqlRespItems = $cRespuestasPruebasItemsDB->readLista($cRespuestasPruebasItems);
						$listaRespItems = $conn->Execute($sqlRespItems);
						
						//Inicializamos la puntuación directa y el percentil que más tarde transformaremos
						$iPDirecta = 0;
						$iPercentil = 0;
						if($listaRespItems->recordCount()>0)
						{
							while(!$listaRespItems->EOF){
								
								//Leemos el item para saber cual es la opción correcta
								$cItem = new Items();
								$cItem->setIdItem($listaRespItems->fields['idItem']);
								$cItem->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cItem->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cItem = $cItemsDB->readEntidad($cItem);
								
								//Leemos la opción para saber en código de la misma
								$cOpcion = new Opciones();
								$cOpcion->setIdItem($listaRespItems->fields['idItem']);
								$cOpcion->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cOpcion->setIdOpcion($listaRespItems->fields['idOpcion']);
								$cOpcion->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cOpcion = $cOpcionesDB->readEntidad($cOpcion);
								
								
								//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
								if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
									//echo $listaRespItems->fields['idItem'] . " - bien <br />";
									//Si coincide se le suma uno a la PDirecta.
									$iPDirecta++;
								}
								
								$listaRespItems->MoveNext();
							}
							
							$cBaremos_resultados = new Baremos_resultados();
							$cBaremos_resultados->setIdBaremo($_POST['fIdBaremo']);
							$cBaremos_resultados->setIdPrueba($_POST['fIdPrueba']);
							
							$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
							$listaBaremosResultados = $conn->Execute($sqlBaremosResultados);
							$ipMin=0;
							$ipMax=0;
							// Recorremos la lista de los valores del baremo seleccionado para mirar el percentil que 
							// corresponde con la puntuación directa obtenida.
							if($listaBaremosResultados->recordCount()>0){
								while(!$listaBaremosResultados->EOF){
									
									$ipMin = $listaBaremosResultados->fields['puntMin'];
									$ipMax = $listaBaremosResultados->fields['puntMax'];
									if($ipMin<=$iPDirecta && $iPDirecta<=$ipMax){
										$iPercentil = $listaBaremosResultados->fields['puntBaremada'];
									}
									$listaBaremosResultados->MoveNext();
								}
							}
							
							//echo "pDirecta: " . $iPDirecta . "<br />";
							//echo "pPercentil: " . $iPercentil . "<br />";
							
							
							include('constantesInformes/' .	$_POST['fCodIdiomaIso2'] .'.php');
							switch ($cPruebas->getIdTipoPrueba())
								{
								case 6:	//Motivaviones
								case 7:	//Personalidad
								// Tipos de prueba de personalidad tipo Prisma
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_itemsDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_items.php");
								
								$cTipos_competenciasDB = new Tipos_competenciasDB($conn);
								$cCompetenciasDB = new CompetenciasDB($conn);
								$cCompetencias_itemsDB = new Competencias_itemsDB($conn);
								$cBloquesDB = new BloquesDB($conn);
								$cEscalasDB = new EscalasDB($conn);
								$cEscalas_itemsDB = new Escalas_itemsDB($conn);
								include('Template/Informes_candidato_zip/generaPrueba' . $cPruebas->getIdPrueba() . '.php');
									break;
								default:
									//Poner switch por prueba PEDRO
									switch ($_POST['fIdPrueba'])
									{
										case 8:	//ELT
											include('Template/Informes_candidato_zip/generaPrueba' . $_POST['fIdPrueba'] . '.php');
											break;
										case 32:	//CIP
											include('Template/Informes_candidato_zip/generaTipo' . $idTipoInforme . '.php');
											break;
										case 42:	//SOP
											include('Template/Informes_candidato_zip/generaPrueba' . $_POST['fIdPrueba'] . '.php');
											break;
										default:
											include('Template/Informes_candidato_zip/generaTipo' . $idTipoInforme . '.php');
											break;
									} // end switch
									break;
							}
							//Miramos si hay que descontar y cuanto para informar al usuario.
							$dTotalCoste = getDescuentoDongles($cCandidato->getIdEmpresa(), $cCandidato->getIdProceso(), $cCandidato->getIdCandidato(), $cPruebas->getCodIdiomaIso2(), $cPruebas->getIdPrueba(), $cInformesPruebasTrf->getCodIdiomaIso2(), $cInformesPruebasTrf->getIdTipoInforme(), $_POST['fIdBaremo'], $dTotalCoste, false);
							$sMsgDescuento = sprintf(constant("MSG_SE_LE_DESCONTARAN_X_DONGLES"),$dTotalCoste);
							$sNombre .= ".pdf";
							?>
								<script>
								muestraBoton();
								</script>
							<?php 
							echo "<br /><br />" . $sMsgDescuento . "<br />";
							echo "<a href=\"#_\" title=\"Descargar\" onclick=\"javascript:abrirVentana('0','". base64_encode(constant('HTTP_SERVER'). $sDirImg . $sNombre)."');\">" . constant("STR_DESCARGAR") . "</a>";
		//					header ( "Expires: 0");
		//					header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
		//					header ( "Pragma: no-cache" );
		//					header ( "Content-type: application/octet-stream; name=" . $sNombreFicheroExcel . ".xls");
		//					header ( "Content-Disposition: attachment; filename=" . $sNombreFicheroExcel . ".xls"); 
		//					header ( "Content-Description: MID Gera excel" );
		//					print  ( $binDatosExcel);
						}else{
							?>
							<script>
								escondeLoad();
							</script>
							<?php 
							echo "<br />" . constant("MSG_SIN_RESPUESTAS_PARA_LA_PRUEBA");
						}
						$sTypeError	=	date('d/m/Y H:i:s') . " Generado desde [" . $_SERVER['REMOTE_ADDR'] . "] [" . $sNombre . "][" . constant("MNT_EXPORTA") . "][Empresa][ZIP]";
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}else{	
						//Si ya está generado sacamos el enlace
						//Miramos si hay que descontar y cuanto para informar al usuario.
						$dTotalCoste = getDescuentoDongles($cCandidato->getIdEmpresa(), $cCandidato->getIdProceso(), $cCandidato->getIdCandidato(), $cPruebas->getCodIdiomaIso2(), $cPruebas->getIdPrueba(), $cInformesPruebasTrf->getCodIdiomaIso2(), $cInformesPruebasTrf->getIdTipoInforme(), $_POST['fIdBaremo'], $dTotalCoste, false);
						$sMsgDescuento = sprintf(constant("MSG_SE_LE_DESCONTARAN_X_DONGLES"),$dTotalCoste);
						$sNombre .= ".pdf";
						?>
							<script>
							muestraBoton();
							</script>
						<?php 
						echo "<br /><br />" . $sMsgDescuento . "<br />";
						echo "<a href=\"#_\" title=\"" . constant("STR_DESCARGAR") . "\" onclick=\"javascript:abrirVentana('0','". base64_encode(constant('HTTP_SERVER'). $sDirImg . $sNombre)."');\">" . constant("STR_DESCARGAR") . "</a>";
					}
				}else{
					?>
					<script>
						escondeLoad();
					</script>
					<?php 
					echo "<br />" . constant("MSG_SIN_DONGLES_PARA_VER_INFORME");
				}
			break;
		case constant("MNT_EXPORTA_HTML"):
				
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/ItemsDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/Items.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/Opciones.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valoresDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valores.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados_competencias/Baremos_resultados_competenciasDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados_competencias/Baremos_resultados_competencias.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Nivelesjerarquicos/NivelesjerarquicosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Nivelesjerarquicos/Nivelesjerarquicos.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Secciones_informes/Secciones_informesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Secciones_informes/Secciones_informes.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_secciones/Textos_seccionesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_secciones/Textos_secciones.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textos.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_irDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_ir.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ipDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ip.php");
				
				
				$cTextos_seccionesDB = new Textos_seccionesDB($conn);
				$cSecciones_informesDB = new Secciones_informesDB($conn);
				$cRangos_ipDB = new Rangos_ipDB($conn);
				$cRangos_irDB = new Rangos_irDB($conn);
				$cRangos_textosDB = new Rangos_textosDB($conn);
				
				//Cambiar Dongels por Cliente/Prueba/Informe
	    		//Miramos si tiene definido dongles por empresa
    			$cInformesPruebasTrf = new Informes_pruebas_empresas();
    			$cInformesPruebasTrf->setIdPrueba($_POST['fIdPrueba']);
    			$cInformesPruebasTrf->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    			$cInformesPruebasTrf->setIdTipoInforme($_POST['fIdTipoInforme']);
    			$cInformesPruebasTrf->setIdEmpresa($_POST['fIdEmpresa']);
    			
				$sql_IPE = $cInformes_pruebas_empresasDB->readLista($cInformesPruebasTrf);
				$rsIPE = $conn->Execute($sql_IPE);
    			if ($rsIPE->NumRows() > 0){
    				$cInformesPruebasTrf = $cInformes_pruebas_empresasDB->readEntidad($cInformesPruebasTrf);
    			}else {
					$cInformesPruebasTrf = new Informes_pruebas();
					$cInformesPruebasTrf->setIdPrueba($_POST['fIdPrueba']);
					$cInformesPruebasTrf->setIdTipoInforme($_POST['fIdTipoInforme']);
					$cInformesPruebasTrf->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
					$cInformesPruebasTrf= $cInformes_pruebasDB->readEntidad($cInformesPruebasTrf);	    				
	   			}
				
				$cEmpresaDng = new Empresas();
				$cEmpresaDng->setIdEmpresa($_POST['fIdEmpresa']);
				$cEmpresaDng = $cEmpresasDB->readEntidad($cEmpresaDng);
				
				$dTotalCoste=$cInformesPruebasTrf->getTarifa();
				
				$bDescargar=false;
				if ($cEmpresaDng->getPrepago() == 0 || $_EmpresaLogada == constant("EMPRESA_PE")){
					//Descargamos siempre si la empresa logada es PE o NO es por prepago 
					$bDescargar=true;
				}else{
					if($cInformesPruebasTrf->getTarifa() <= $cEmpresaDng->getDongles()){
						$bDescargar=true;
					}else{
						$bDescargar=false;
					}
				}
				$bPDFGenerado = false;	//Indica si el fichero fue generado previamente
				if($bDescargar)
				{
					$cCandidato = new Candidatos();
					$cCandidato->setIdEmpresa($_POST['fIdEmpresa']);
					$cCandidato->setIdProceso($_POST['fIdProceso']);
					$cCandidato->setIdCandidato($_POST['fIdCandidato']);
					
					$cCandidato =  $cCandidatosDB->readEntidad($cCandidato);
					
					$cPruebas = new Pruebas();
					$cPruebas->setIdPrueba($_POST['fIdPrueba']);
					$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
					$cPruebas = $cPruebasDB->readEntidad($cPruebas);
					
					//Miramos si ya esiste el pdf en caso que no esista lo generamos
//					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa'] . "_" . $_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2']);
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa'] . "_" . $_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
					$_ficheroPDF = $spath . $sDirImg . $sNombre . ".html";
		
					if(is_file($_ficheroPDF)){
						//De momento que siempre lo genere, si se quiere cambiar habría 
						//que incluir el idioma en el nombre del fichero 
						//y cambiarlo en los templates de generación
						$bPDFGenerado = false;
					}else{
						$bPDFGenerado = false;
					}
					if (!$bPDFGenerado)
					{
						$cRespuestasPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
						$cItemsDB = new ItemsDB($conn);
						$cNivelesjerarquicosDB = new NivelesjerarquicosDB($conn);
						$cOpcionesDB = new OpcionesDB($conn);
						$cOpciones_valoresDB = new Opciones_valoresDB($conn);
						$cBaremos_resultadosDB = new Baremos_resultadosDB($conn);
						
						$idTipoPrueba = $cPruebas->getIdTipoPrueba();
						
						$idTipoInforme=$_POST['fIdTipoInforme'];
						
						$cRespuestasPruebasItems = new Respuestas_pruebas_items();
						
						$cRespuestasPruebasItems->setIdCandidato($_POST['fIdCandidato']);
						$cRespuestasPruebasItems->setIdPrueba($_POST['fIdPrueba']);
						$cRespuestasPruebasItems->setIdEmpresa($_POST['fIdEmpresa']);
						$cRespuestasPruebasItems->setIdProceso($_POST['fIdProceso']);
						$cRespuestasPruebasItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$cRespuestasPruebasItems->setOrderBy("idItem");
						$cRespuestasPruebasItems->setOrder("ASC");
						
						$cIt = new Items();
						$cIt->setIdPrueba($_POST['fIdPrueba']);
						$cIt->setIdPruebaHast($_POST['fIdPrueba']);
						$cIt->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$sqlItemsPrueba= $cItemsDB->readLista($cIt);
						$listaItemsPrueba = $conn->Execute($sqlItemsPrueba);
						// Montamos la lista de respuestas para los parámetros enviados. 
						
						$sqlRespItems = $cRespuestasPruebasItemsDB->readLista($cRespuestasPruebasItems);
						$listaRespItems = $conn->Execute($sqlRespItems);
						
						//Inicializamos la puntuación directa y el percentil que más tarde transformaremos
						$iPDirecta = 0;
						$iPercentil = 0;
						if($listaRespItems->recordCount()>0)
						{
							while(!$listaRespItems->EOF){
								
								//Leemos el item para saber cual es la opción correcta
								$cItem = new Items();
								$cItem->setIdItem($listaRespItems->fields['idItem']);
								$cItem->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cItem->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cItem = $cItemsDB->readEntidad($cItem);
								
								//Leemos la opción para saber en código de la misma
								$cOpcion = new Opciones();
								$cOpcion->setIdItem($listaRespItems->fields['idItem']);
								$cOpcion->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cOpcion->setIdOpcion($listaRespItems->fields['idOpcion']);
								$cOpcion->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cOpcion = $cOpcionesDB->readEntidad($cOpcion);
								
								
								//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
								if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
									//echo $listaRespItems->fields['idItem'] . " - bien <br />";
									//Si coincide se le suma uno a la PDirecta.
									$iPDirecta++;
								}
								
								$listaRespItems->MoveNext();
							}
							
							$cBaremos_resultados = new Baremos_resultados();
							$cBaremos_resultados->setIdBaremo($_POST['fIdBaremo']);
							$cBaremos_resultados->setIdPrueba($_POST['fIdPrueba']);
							
							$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
							$listaBaremosResultados = $conn->Execute($sqlBaremosResultados);
							$ipMin=0;
							$ipMax=0;
							// Recorremos la lista de los valores del baremo seleccionado para mirar el percentil que 
							// corresponde con la puntuación directa obtenida.
							if($listaBaremosResultados->recordCount()>0){
								while(!$listaBaremosResultados->EOF){
									
									$ipMin = $listaBaremosResultados->fields['puntMin'];
									$ipMax = $listaBaremosResultados->fields['puntMax'];
									if($ipMin <= $iPDirecta && $iPDirecta <= $ipMax){
										$iPercentil = $listaBaremosResultados->fields['puntBaremada'];
									}
									$listaBaremosResultados->MoveNext();
								}
							}
							
							//echo "pDirecta: " . $iPDirecta . "<br />";
							//echo "pPercentil: " . $iPercentil . "<br />";
							
							
							include('constantesInformes/' .	$_POST['fCodIdiomaIso2'] .'.php');
													switch ($cPruebas->getIdTipoPrueba())
							{
								case 6:	//Motivaviones
								case 7:	//Personalidad
									// Tipos de prueba de personalidad tipo Prisma
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_itemsDB.php");
									require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_items.php");
									
									$cTipos_competenciasDB = new Tipos_competenciasDB($conn);
									$cCompetenciasDB = new CompetenciasDB($conn);
									$cCompetencias_itemsDB = new Competencias_itemsDB($conn);
									$cBloquesDB = new BloquesDB($conn);
									$cEscalasDB = new EscalasDB($conn);
									$cEscalas_itemsDB = new Escalas_itemsDB($conn);
									include('Template/Informes_candidato_zip/generaPrueba' . $cPruebas->getIdPrueba() . '.php');
									break;
								default:
								//Poner switch por prueba PEDRO
								switch ($_POST['fIdPrueba'])
								{
									case 8:	//ELT
										include('Template/Informes_candidato_zip/generaPrueba' . $_POST['fIdPrueba'] . '.php');
										break;
									case 32:	//CIP
										include('Template/Informes_candidato_zip/generaTipo' . $idTipoInforme . '.php');
										break;
									case 42:	//SOP
										include('Template/Informes_candidato_zip/generaPrueba' . $_POST['fIdPrueba'] . '.php');
										break;
									default:
										include('Template/Informes_candidato_zip/generaTipo' . $idTipoInforme . '.php');
										break;
								} // end switch
									break;
							}
							//Miramos si hay que descontar y cuanto para informar al usuario.
							$dTotalCoste = getDescuentoDongles($cCandidato->getIdEmpresa(), $cCandidato->getIdProceso(), $cCandidato->getIdCandidato(), $cPruebas->getCodIdiomaIso2(), $cPruebas->getIdPrueba(), $cInformesPruebasTrf->getCodIdiomaIso2(), $cInformesPruebasTrf->getIdTipoInforme(), $_POST['fIdBaremo'], $dTotalCoste, false);
							$sMsgDescuento = sprintf(constant("MSG_SE_LE_DESCONTARAN_X_DONGLES"),$dTotalCoste);
							$sNombre .= ".html";
							?>
								<script>
								muestraBotonHTML();
								</script>
							<?php 
							echo "<br /><br />" . $sMsgDescuento . "<br />";
							echo "<a href=\"#_\" title=\"Descargar\" onclick=\"javascript:abrirVentanaHTML('0','". base64_encode(constant('HTTP_SERVER'). $sDirImg . $sNombre)."');\">" . constant("STR_DESCARGAR") . "</a>";
		//					header ( "Expires: 0");
		//					header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
		//					header ( "Pragma: no-cache" );
		//					header ( "Content-type: application/octet-stream; name=" . $sNombreFicheroExcel . ".xls");
		//					header ( "Content-Disposition: attachment; filename=" . $sNombreFicheroExcel . ".xls"); 
		//					header ( "Content-Description: MID Gera excel" );
		//					print  ( $binDatosExcel);
						}else{
							?>
							<script>
								escondeLoadHTML();
							</script>
							<?php 
							echo "<br />" . constant("MSG_SIN_RESPUESTAS_PARA_LA_PRUEBA");
						}
						$sTypeError	=	date('d/m/Y H:i:s') . " RE-Generado desde [" . $_SERVER['REMOTE_ADDR'] . "] [" . $sNombre . "][" . constant("MNT_EXPORTA_HTML") . "][Empresa][ZIP]";
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}else{	
						//Si ya está generado sacamos el enlace
						//Miramos si hay que descontar y cuanro para informar al usuario.
						$sMsgDescuento = sprintf(constant("MSG_SE_LE_DESCONTARAN_X_DONGLES"),$dTotalCoste);
						$sNombre .= ".html";
						?>
							<script>
							muestraBotonHTML();
							</script>
						<?php 
						echo "<br /><br />" . $sMsgDescuento . "<br />";
						echo "<a href=\"#_\" title=\"" . constant("STR_DESCARGAR") . "\" onclick=\"javascript:abrirVentanaHTML('0','". base64_encode(constant('HTTP_SERVER'). $sDirImg . $sNombre)."');\">" . constant("STR_DESCARGAR") . "</a>";
					}
				}else{
					?>
					<script>
						escondeLoadHTML();
					</script>
					<?php 
					echo "<br />" . constant("MSG_SIN_DONGLES_PARA_VER_INFORME");
				}
			break;
		case constant("MNT_EXPORTA_WORD"):
				
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/ItemsDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/Items.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/Opciones.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valoresDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valores.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados_competencias/Baremos_resultados_competenciasDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados_competencias/Baremos_resultados_competencias.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Nivelesjerarquicos/NivelesjerarquicosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Nivelesjerarquicos/Nivelesjerarquicos.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Secciones_informes/Secciones_informesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Secciones_informes/Secciones_informes.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_secciones/Textos_seccionesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_secciones/Textos_secciones.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textos.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_irDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_ir.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ipDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ip.php");
				
				
				$cTextos_seccionesDB = new Textos_seccionesDB($conn);
				$cSecciones_informesDB = new Secciones_informesDB($conn);
				$cRangos_ipDB = new Rangos_ipDB($conn);
				$cRangos_irDB = new Rangos_irDB($conn);
				$cRangos_textosDB = new Rangos_textosDB($conn);
				
				//Cambiar Dongels por Cliente/Prueba/Informe
	    		//Miramos si tiene definido dongles por empresa
    			$cInformesPruebasTrf = new Informes_pruebas_empresas();
    			$cInformesPruebasTrf->setIdPrueba($_POST['fIdPrueba']);
    			$cInformesPruebasTrf->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    			$cInformesPruebasTrf->setIdTipoInforme($_POST['fIdTipoInforme']);
    			$cInformesPruebasTrf->setIdEmpresa($_POST['fIdEmpresa']);
    			
				$sql_IPE = $cInformes_pruebas_empresasDB->readLista($cInformesPruebasTrf);
				$rsIPE = $conn->Execute($sql_IPE);
    			if ($rsIPE->NumRows() > 0){
    				$cInformesPruebasTrf = $cInformes_pruebas_empresasDB->readEntidad($cInformesPruebasTrf);
    			}else {
					$cInformesPruebasTrf = new Informes_pruebas();
					$cInformesPruebasTrf->setIdPrueba($_POST['fIdPrueba']);
					$cInformesPruebasTrf->setIdTipoInforme($_POST['fIdTipoInforme']);
					$cInformesPruebasTrf->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
					$cInformesPruebasTrf= $cInformes_pruebasDB->readEntidad($cInformesPruebasTrf);	    				
	   			}
				
				$cEmpresaDng = new Empresas();
				$cEmpresaDng->setIdEmpresa($_POST['fIdEmpresa']);
				$cEmpresaDng = $cEmpresasDB->readEntidad($cEmpresaDng);
				
				$dTotalCoste=$cInformesPruebasTrf->getTarifa();
				$bDescargar=false;
				if ($cEmpresaDng->getPrepago() == 0 || $_EmpresaLogada == constant("EMPRESA_PE")){
					//Descargamos siempre si la empresa logada es PE o NO es por prepago 
					$bDescargar=true;
				}else{
					if($cInformesPruebasTrf->getTarifa() <= $cEmpresaDng->getDongles()){
						$bDescargar=true;
					}else{
						$bDescargar=false;
					}
				}
				$bPDFGenerado = false;	//Indica si el fichero fue generado previamente
				if($bDescargar)
				{
					$cCandidato = new Candidatos();
					$cCandidato->setIdEmpresa($_POST['fIdEmpresa']);
					$cCandidato->setIdProceso($_POST['fIdProceso']);
					$cCandidato->setIdCandidato($_POST['fIdCandidato']);
					
					$cCandidato =  $cCandidatosDB->readEntidad($cCandidato);
					
					$cPruebas = new Pruebas();
					$cPruebas->setIdPrueba($_POST['fIdPrueba']);
					$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
					$cPruebas = $cPruebasDB->readEntidad($cPruebas);
					
					//Miramos si ya esiste el pdf en caso que no esista lo generamos
//					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa'] . "_" . $_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2']);
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa'] . "_" . $_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
					$_ficheroPDF = $spath . $sDirImg . $sNombre . ".rtf";
		
					if(is_file($_ficheroPDF)){
						$bPDFGenerado = true;
							}else{
						$bPDFGenerado = false;
					}
					if (!$bPDFGenerado)
					{
						$cRespuestasPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
						$cItemsDB = new ItemsDB($conn);
						$cNivelesjerarquicosDB = new NivelesjerarquicosDB($conn);
						$cOpcionesDB = new OpcionesDB($conn);
						$cOpciones_valoresDB = new Opciones_valoresDB($conn);
						$cBaremos_resultadosDB = new Baremos_resultadosDB($conn);
						
						$idTipoPrueba = $cPruebas->getIdTipoPrueba();
						
						$idTipoInforme=$_POST['fIdTipoInforme'];
						
						$cRespuestasPruebasItems = new Respuestas_pruebas_items();
						
						$cRespuestasPruebasItems->setIdCandidato($_POST['fIdCandidato']);
						$cRespuestasPruebasItems->setIdPrueba($_POST['fIdPrueba']);
						$cRespuestasPruebasItems->setIdEmpresa($_POST['fIdEmpresa']);
						$cRespuestasPruebasItems->setIdProceso($_POST['fIdProceso']);
						$cRespuestasPruebasItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$cRespuestasPruebasItems->setOrderBy("idItem");
						$cRespuestasPruebasItems->setOrder("ASC");
						
						$cIt = new Items();
						$cIt->setIdPrueba($_POST['fIdPrueba']);
						$cIt->setIdPruebaHast($_POST['fIdPrueba']);
						$cIt->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$sqlItemsPrueba= $cItemsDB->readLista($cIt);
						$listaItemsPrueba = $conn->Execute($sqlItemsPrueba);
						// Montamos la lista de respuestas para los parámetros enviados. 
						
						$sqlRespItems = $cRespuestasPruebasItemsDB->readLista($cRespuestasPruebasItems);
						$listaRespItems = $conn->Execute($sqlRespItems);
						
						//Inicializamos la puntuación directa y el percentil que más tarde transformaremos
						$iPDirecta = 0;
						$iPercentil = 0;
						if($listaRespItems->recordCount()>0)
						{
							while(!$listaRespItems->EOF){
								
								//Leemos el item para saber cual es la opción correcta
								$cItem = new Items();
								$cItem->setIdItem($listaRespItems->fields['idItem']);
								$cItem->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cItem->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cItem = $cItemsDB->readEntidad($cItem);
								
								//Leemos la opción para saber en código de la misma
								$cOpcion = new Opciones();
								$cOpcion->setIdItem($listaRespItems->fields['idItem']);
								$cOpcion->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cOpcion->setIdOpcion($listaRespItems->fields['idOpcion']);
								$cOpcion->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cOpcion = $cOpcionesDB->readEntidad($cOpcion);
								
								
								//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
								if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
									//echo $listaRespItems->fields['idItem'] . " - bien <br />";
									//Si coincide se le suma uno a la PDirecta.
									$iPDirecta++;
								}
								
								$listaRespItems->MoveNext();
							}
							
							$cBaremos_resultados = new Baremos_resultados();
							$cBaremos_resultados->setIdBaremo($_POST['fIdBaremo']);
							$cBaremos_resultados->setIdPrueba($_POST['fIdPrueba']);
							
							$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
							$listaBaremosResultados = $conn->Execute($sqlBaremosResultados);
							$ipMin=0;
							$ipMax=0;
							// Recorremos la lista de los valores del baremo seleccionado para mirar el percentil que 
							// corresponde con la puntuación directa obtenida.
							if($listaBaremosResultados->recordCount()>0){
								while(!$listaBaremosResultados->EOF){
									
									$ipMin = $listaBaremosResultados->fields['puntMin'];
									$ipMax = $listaBaremosResultados->fields['puntMax'];
									if($ipMin <= $iPDirecta && $iPDirecta <= $ipMax){
										$iPercentil = $listaBaremosResultados->fields['puntBaremada'];
									}
									$listaBaremosResultados->MoveNext();
								}
							}
							
							//echo "pDirecta: " . $iPDirecta . "<br />";
							//echo "pPercentil: " . $iPercentil . "<br />";
							
							
							include('constantesInformes/' .	$_POST['fCodIdiomaIso2'] .'.php');
							switch ($cPruebas->getIdTipoPrueba())
							{
								case 6:	//Motivaviones
								case 7:	//Personalidad
								// Tipos de prueba de personalidad tipo Prisma
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_itemsDB.php");
								require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_items.php");
								
								$cTipos_competenciasDB = new Tipos_competenciasDB($conn);
								$cCompetenciasDB = new CompetenciasDB($conn);
								$cCompetencias_itemsDB = new Competencias_itemsDB($conn);
								$cBloquesDB = new BloquesDB($conn);
								$cEscalasDB = new EscalasDB($conn);
								$cEscalas_itemsDB = new Escalas_itemsDB($conn);
								include('Template/Informes_candidato_zip/generaPrueba'.$cPruebas->getIdPrueba().'.php');
									break;
								default:
									//Poner switch por prueba PEDRO
									switch ($_POST['fIdPrueba'])
									{
										case 8:	//ELT
											include('Template/Informes_candidato_zip/generaPrueba' . $_POST['fIdPrueba'] . '.php');
											break;
										case 32:	//CIP
											include('Template/Informes_candidato_zip/generaTipo' . $idTipoInforme . '.php');
											break;
										case 42:	//SOP
											include('Template/Informes_candidato_zip/generaPrueba' . $_POST['fIdPrueba'] . '.php');
											break;
										default:
											include('Template/Informes_candidato_zip/generaTipo' . $idTipoInforme . '.php');
											break;
									} // end switch
									break;
							}
							//Miramos si hay que descontar y cuanto para informar al usuario.
							$dTotalCoste = getDescuentoDongles($cCandidato->getIdEmpresa(), $cCandidato->getIdProceso(), $cCandidato->getIdCandidato(), $cPruebas->getCodIdiomaIso2(), $cPruebas->getIdPrueba(), $cInformesPruebasTrf->getCodIdiomaIso2(), $cInformesPruebasTrf->getIdTipoInforme(), $_POST['fIdBaremo'], $dTotalCoste, false);
							$sMsgDescuento = sprintf(constant("MSG_SE_LE_DESCONTARAN_X_DONGLES"),$dTotalCoste);
							$sNombre .= ".rtf";
							?>
								<script>
								muestraBotonWORD();
								</script>
							<?php 
							echo "<br /><br />" . $sMsgDescuento . "<br />";
							echo "<a href=\"#_\" title=\"Descargar\" onclick=\"javascript:abrirVentanaWORD('0','". base64_encode(constant('HTTP_SERVER'). $sDirImg . $sNombre)."');\">" . constant("STR_DESCARGAR") . "</a>";
		//					header ( "Expires: 0");
		//					header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
		//					header ( "Pragma: no-cache" );
		//					header ( "Content-type: application/octet-stream; name=" . $sNombreFicheroExcel . ".xls");
		//					header ( "Content-Disposition: attachment; filename=" . $sNombreFicheroExcel . ".xls"); 
		//					header ( "Content-Description: MID Gera excel" );
		//					print  ( $binDatosExcel);
						}else{
							?>
							<script>
								escondeLoadWORD();
							</script>
							<?php 
							echo "<br />" . constant("MSG_SIN_RESPUESTAS_PARA_LA_PRUEBA");
						}
						$sTypeError	=	date('d/m/Y H:i:s') . " Generado desde [" . $_SERVER['REMOTE_ADDR'] . "] [" . $sNombre . "][" . constant("MNT_EXPORTA_WORD") . "][Empresa][ZIP]";
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}else{	
						//Si ya está generado sacamos el enlace
						//Miramos si hay que descontar y cuanto para informar al usuario.
						$dTotalCoste = getDescuentoDongles($cCandidato->getIdEmpresa(), $cCandidato->getIdProceso(), $cCandidato->getIdCandidato(), $cPruebas->getCodIdiomaIso2(), $cPruebas->getIdPrueba(), $cInformesPruebasTrf->getCodIdiomaIso2(), $cInformesPruebasTrf->getIdTipoInforme(), $_POST['fIdBaremo'], $dTotalCoste, false);
						$sMsgDescuento = sprintf(constant("MSG_SE_LE_DESCONTARAN_X_DONGLES"),$dTotalCoste);
						$sNombre .= ".rtf";
						?>
							<script>
							muestraBotonWORD();
							</script>
						<?php 
						echo "<br /><br />" . $sMsgDescuento . "<br />";
						echo "<a href=\"#_\" title=\"" . constant("STR_DESCARGAR") . "\" onclick=\"javascript:abrirVentanaWORD('0','". base64_encode(constant('HTTP_SERVER'). $sDirImg . $sNombre)."');\">" . constant("STR_DESCARGAR") . "</a>";
					}
				}else{
					?>
					<script>
						escondeLoadWORD();
					</script>
					<?php 
					echo "<br />" . constant("MSG_SIN_DONGLES_PARA_VER_INFORME");
				}
			break;
		case constant("MNT_DESCUENTA_DONGLES"):
				
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/ItemsDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/Items.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/Opciones.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valoresDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valores.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Nivelesjerarquicos/NivelesjerarquicosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Nivelesjerarquicos/Nivelesjerarquicos.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Secciones_informes/Secciones_informesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Secciones_informes/Secciones_informes.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_secciones/Textos_seccionesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_secciones/Textos_secciones.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textos.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_irDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_ir.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ipDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ip.php");
				
				
				$cTextos_seccionesDB = new Textos_seccionesDB($conn);
				$cSecciones_informesDB = new Secciones_informesDB($conn);
				$cRangos_ipDB = new Rangos_ipDB($conn);
				$cRangos_irDB = new Rangos_irDB($conn);
				$cRangos_textosDB = new Rangos_textosDB($conn);
				
				//Cambiar Dongels por Cliente/Prueba/Informe
	    		//Miramos si tiene definido dongles por empresa
    			$cInformesPruebasTrf = new Informes_pruebas_empresas();
    			$cInformesPruebasTrf->setIdPrueba($_POST['fIdPrueba']);
    			$cInformesPruebasTrf->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    			$cInformesPruebasTrf->setIdTipoInforme($_POST['fIdTipoInforme']);
    			$cInformesPruebasTrf->setIdEmpresa($_POST['fIdEmpresa']);
    			
				$sql_IPE = $cInformes_pruebas_empresasDB->readLista($cInformesPruebasTrf);
				$rsIPE = $conn->Execute($sql_IPE);
    			if ($rsIPE->NumRows() > 0){
    				$cInformesPruebasTrf = $cInformes_pruebas_empresasDB->readEntidad($cInformesPruebasTrf);
    				$cInformes_pruebas_empresasDB->addContInforme($cInformesPruebasTrf);
    			}else {
					$cInformesPruebasTrf = new Informes_pruebas();
					$cInformesPruebasTrf->setIdPrueba($_POST['fIdPrueba']);
					$cInformesPruebasTrf->setIdTipoInforme($_POST['fIdTipoInforme']);
					$cInformesPruebasTrf->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
					$cInformesPruebasTrf= $cInformes_pruebasDB->readEntidad($cInformesPruebasTrf);
					$cInformes_pruebasDB->addContInforme($cInformesPruebasTrf);	    				
	   			}
				
//				echo "<br />Entro PDF";
				$cEmpresaDng = new Empresas();
				$cEmpresaDng->setIdEmpresa($_POST['fIdEmpresa']);
				$cEmpresaDng = $cEmpresasDB->readEntidad($cEmpresaDng);
				
				$dTotalCoste=$cInformesPruebasTrf->getTarifa();
				
				$bDescargar=false;
				if ($cEmpresaDng->getPrepago() == 0 || $_EmpresaLogada == constant("EMPRESA_PE")){
					//Descargamos siempre si la empresa logada es PE o NO es por prepago 
					$bDescargar=true;
				}else{
					if($cInformesPruebasTrf->getTarifa() <= $cEmpresaDng->getDongles()){
						$bDescargar=true;
					}else{
						$bDescargar=false;
					}
				}
				$bPDFGenerado = false;	//Indica si el fichero fue generado previamente
				if($bDescargar)
				{
					$cCandidato = new Candidatos();
					$cCandidato->setIdEmpresa($_POST['fIdEmpresa']);
					$cCandidato->setIdProceso($_POST['fIdProceso']);
					$cCandidato->setIdCandidato($_POST['fIdCandidato']);
					
					$cCandidato =  $cCandidatosDB->readEntidad($cCandidato);
					
					$cPruebas = new Pruebas();
					$cPruebas->setIdPrueba($_POST['fIdPrueba']);
					$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
					$cPruebas = $cPruebasDB->readEntidad($cPruebas);
					
					
					//Miramos si ya esiste el pdf en caso que no esista lo generamos
//					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa'] . "_" . $_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2']);
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa'] . "_" . $_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
					$_ficheroPDF = $spath . $sDirImg . $sNombre . ".pdf";
		
					if(is_file($_ficheroPDF)){
						//De momento que siempre lo genere, si se quiere cambiar habría 
						//que incluir el idioma en el nombre del fichero 
						//y cambiarlo en los templates de generación
						$bPDFGenerado = true;
					}else{
						$bPDFGenerado = false;
					}
//					echo "<br />Generado::" . $bPDFGenerado;
					if (!$bPDFGenerado)
					{
						$cRespuestasPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
						$cItemsDB = new ItemsDB($conn);
						$cNivelesjerarquicosDB = new NivelesjerarquicosDB($conn);
						$cOpcionesDB = new OpcionesDB($conn);
						$cOpciones_valoresDB = new Opciones_valoresDB($conn);
						$cBaremos_resultadosDB = new Baremos_resultadosDB($conn);
						
						$idTipoPrueba = $cPruebas->getIdTipoPrueba();
						
						$idTipoInforme=$_POST['fIdTipoInforme'];
						
						$cRespuestasPruebasItems = new Respuestas_pruebas_items();
						
						$cRespuestasPruebasItems->setIdCandidato($_POST['fIdCandidato']);
						$cRespuestasPruebasItems->setIdPrueba($_POST['fIdPrueba']);
						$cRespuestasPruebasItems->setIdEmpresa($_POST['fIdEmpresa']);
						$cRespuestasPruebasItems->setIdProceso($_POST['fIdProceso']);
						$cRespuestasPruebasItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$cRespuestasPruebasItems->setOrderBy("idItem");
						$cRespuestasPruebasItems->setOrder("ASC");
						
						$cIt = new Items();
						$cIt->setIdPrueba($_POST['fIdPrueba']);
						$cIt->setIdPruebaHast($_POST['fIdPrueba']);
						$cIt->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$sqlItemsPrueba= $cItemsDB->readLista($cIt);
						$listaItemsPrueba = $conn->Execute($sqlItemsPrueba);
						// Montamos la lista de respuestas para los parámetros enviados. 
						
						$sqlRespItems = $cRespuestasPruebasItemsDB->readLista($cRespuestasPruebasItems);
						$listaRespItems = $conn->Execute($sqlRespItems);
						
						//Inicializamos la puntuación directa y el percentil que más tarde transformaremos
						$iPDirecta = 0;
						$iPercentil = 0;
						if($listaRespItems->recordCount()>0)
						{
							while(!$listaRespItems->EOF){
								
								//Leemos el item para saber cual es la opción correcta
								$cItem = new Items();
								$cItem->setIdItem($listaRespItems->fields['idItem']);
								$cItem->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cItem->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cItem = $cItemsDB->readEntidad($cItem);
								
								//Leemos la opción para saber en código de la misma
								$cOpcion = new Opciones();
								$cOpcion->setIdItem($listaRespItems->fields['idItem']);
								$cOpcion->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cOpcion->setIdOpcion($listaRespItems->fields['idOpcion']);
								$cOpcion->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cOpcion = $cOpcionesDB->readEntidad($cOpcion);
								
								
								//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
								if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
									//echo $listaRespItems->fields['idItem'] . " - bien <br />";
									//Si coincide se le suma uno a la PDirecta.
									$iPDirecta++;
								}
								
								$listaRespItems->MoveNext();
							}
							
							$cBaremos_resultados = new Baremos_resultados();
							$cBaremos_resultados->setIdBaremo($_POST['fIdBaremo']);
							$cBaremos_resultados->setIdPrueba($_POST['fIdPrueba']);
							
							$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
							$listaBaremosResultados = $conn->Execute($sqlBaremosResultados);
							$ipMin=0;
							$ipMax=0;
							// Recorremos la lista de los valores del baremo seleccionado para mirar el percentil que 
							// corresponde con la puntuación directa obtenida.
							if($listaBaremosResultados->recordCount()>0){
								while(!$listaBaremosResultados->EOF){
									
									$ipMin = $listaBaremosResultados->fields['puntMin'];
									$ipMax = $listaBaremosResultados->fields['puntMax'];
									if($ipMin<=$iPDirecta && $iPDirecta<=$ipMax){
										$iPercentil = $listaBaremosResultados->fields['puntBaremada'];
									}
									$listaBaremosResultados->MoveNext();
								}
							}
							
							//Miramos si hay que descontar y cuanro para informar al usuario.
							$dTotalCoste = getDescuentoDongles($cCandidato->getIdEmpresa(), $cCandidato->getIdProceso(), $cCandidato->getIdCandidato(), $cPruebas->getCodIdiomaIso2(), $cPruebas->getIdPrueba(), $cInformesPruebasTrf->getCodIdiomaIso2(), $cInformesPruebasTrf->getIdTipoInforme(), $_POST['fIdBaremo'], $dTotalCoste, true);
							$sMsgDescuento = sprintf(constant("MSG_DESCONTADOS_X_DONGLES"),$dTotalCoste);
							$sNombre .= ".pdf";
							?>
								<script>
								muestraBoton();
								</script>
							<?php 
							echo "<br /><br /><b>" . $sMsgDescuento . "</b><br />";
//							echo "<a href=\"#_\" title=\"Descargar\" onclick=\"javascript:abrirVentana('0','". base64_encode(constant('HTTP_SERVER'). $sDirImg . $sNombre)."');\">" . constant("STR_DESCARGAR") . "</a>";
						}else{
							?>
							<script>
								escondeLoad();
							</script>
							<?php 
							echo "<br />" . constant("MSG_SIN_RESPUESTAS_PARA_LA_PRUEBA");
						}
						$sTypeError	=	date('d/m/Y H:i:s') . " Generado desde [" . $_SERVER['REMOTE_ADDR'] . "] [" . $sNombre . "][" . constant("MNT_DESCUENTA_DONGLES") . "][Empresa][ZIP]";
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}else{	
						//Si ya está generado sacamos el enlace
						//Miramos si hay que descontar y cuanro para informar al usuario.
						$dTotalCoste = getDescuentoDongles($cCandidato->getIdEmpresa(), $cCandidato->getIdProceso(), $cCandidato->getIdCandidato(), $cPruebas->getCodIdiomaIso2(), $cPruebas->getIdPrueba(), $cInformesPruebasTrf->getCodIdiomaIso2(), $cInformesPruebasTrf->getIdTipoInforme(), $_POST['fIdBaremo'], $dTotalCoste, true);
//						echo "<br />Entro ELSE::" . $dTotalCoste; 
						$sMsgDescuento = sprintf(constant("MSG_DESCONTADOS_X_DONGLES"),$dTotalCoste);
						$sNombre .= ".pdf";
						?>
							<script>
							muestraBoton();
							</script>
						<?php 
						echo "<br /><br /><b>" . $sMsgDescuento . "</b><br />";
//						echo "<a href=\"#_\" title=\"" . constant("STR_DESCARGAR") . "\" onclick=\"javascript:abrirVentana('0','". base64_encode(constant('HTTP_SERVER'). $sDirImg . $sNombre)."');\">" . constant("STR_DESCARGAR") . "</a>";
					}
				}else{
					?>
					<script>
						escondeLoad();
					</script>
					<?php 
					echo "<br />" . constant("MSG_SIN_DONGLES_PARA_VER_INFORME");
				}
			break;
		case constant("MNT_DESCUENTA_DONGLES_HTML"):
				
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/ItemsDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/Items.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/Opciones.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valoresDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valores.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Nivelesjerarquicos/NivelesjerarquicosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Nivelesjerarquicos/Nivelesjerarquicos.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Secciones_informes/Secciones_informesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Secciones_informes/Secciones_informes.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_secciones/Textos_seccionesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_secciones/Textos_secciones.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textos.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_irDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_ir.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ipDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ip.php");
				
				
				$cTextos_seccionesDB = new Textos_seccionesDB($conn);
				$cSecciones_informesDB = new Secciones_informesDB($conn);
				$cRangos_ipDB = new Rangos_ipDB($conn);
				$cRangos_irDB = new Rangos_irDB($conn);
				$cRangos_textosDB = new Rangos_textosDB($conn);
				
				//Cambiar Dongels por Cliente/Prueba/Informe
	    		//Miramos si tiene definido dongles por empresa
    			$cInformesPruebasTrf = new Informes_pruebas_empresas();
    			$cInformesPruebasTrf->setIdPrueba($_POST['fIdPrueba']);
    			$cInformesPruebasTrf->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    			$cInformesPruebasTrf->setIdTipoInforme($_POST['fIdTipoInforme']);
    			$cInformesPruebasTrf->setIdEmpresa($_POST['fIdEmpresa']);
    			
				$sql_IPE = $cInformes_pruebas_empresasDB->readLista($cInformesPruebasTrf);
				$rsIPE = $conn->Execute($sql_IPE);
    			if ($rsIPE->NumRows() > 0){
    				$cInformesPruebasTrf = $cInformes_pruebas_empresasDB->readEntidad($cInformesPruebasTrf);
    				$cInformes_pruebas_empresasDB->addContInforme($cInformesPruebasTrf);
    			}else {
					$cInformesPruebasTrf = new Informes_pruebas();
					$cInformesPruebasTrf->setIdPrueba($_POST['fIdPrueba']);
					$cInformesPruebasTrf->setIdTipoInforme($_POST['fIdTipoInforme']);
					$cInformesPruebasTrf->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
					$cInformesPruebasTrf= $cInformes_pruebasDB->readEntidad($cInformesPruebasTrf);
					$cInformes_pruebasDB->addContInforme($cInformesPruebasTrf);	    				
	   			}
				
//				echo "Entro HTML";
				$cEmpresaDng = new Empresas();
				$cEmpresaDng->setIdEmpresa($_POST['fIdEmpresa']);
				$cEmpresaDng = $cEmpresasDB->readEntidad($cEmpresaDng);
				
				$dTotalCoste=$cInformesPruebasTrf->getTarifa();
				
				$bDescargar=false;
				if ($cEmpresaDng->getPrepago() == 0 || $_EmpresaLogada == constant("EMPRESA_PE")){
					//Descargamos siempre si la empresa logada es PE o NO es por prepago 
					$bDescargar=true;
				}else{
					if($cInformesPruebasTrf->getTarifa() <= $cEmpresaDng->getDongles()){
						$bDescargar=true;
					}else{
						$bDescargar=false;
					}
				}
				$bPDFGenerado = false;	//Indica si el fichero fue generado previamente
				if($bDescargar)
				{
					$cCandidato = new Candidatos();
					$cCandidato->setIdEmpresa($_POST['fIdEmpresa']);
					$cCandidato->setIdProceso($_POST['fIdProceso']);
					$cCandidato->setIdCandidato($_POST['fIdCandidato']);
					
					$cCandidato =  $cCandidatosDB->readEntidad($cCandidato);
					
					$cPruebas = new Pruebas();
					$cPruebas->setIdPrueba($_POST['fIdPrueba']);
					$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
					$cPruebas = $cPruebasDB->readEntidad($cPruebas);
					
					
					//Miramos si ya esiste el pdf en caso que no esista lo generamos
//					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa'] . "_" . $_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2']);
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa'] . "_" . $_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
					$_ficheroPDF = $spath . $sDirImg . $sNombre . ".html";
		
					if(is_file($_ficheroPDF)){
						//De momento que siempre lo genere, si se quiere cambiar habría 
						//que incluir el idioma en el nombre del fichero 
						//y cambiarlo en los templates de generación
						$bPDFGenerado = true;
					}else{
						$bPDFGenerado = false;
					}
					if (!$bPDFGenerado)
					{
						$cRespuestasPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
						$cItemsDB = new ItemsDB($conn);
						$cNivelesjerarquicosDB = new NivelesjerarquicosDB($conn);
						$cOpcionesDB = new OpcionesDB($conn);
						$cOpciones_valoresDB = new Opciones_valoresDB($conn);
						$cBaremos_resultadosDB = new Baremos_resultadosDB($conn);
						
						$idTipoPrueba = $cPruebas->getIdTipoPrueba();
						
						$idTipoInforme=$_POST['fIdTipoInforme'];
						
						$cRespuestasPruebasItems = new Respuestas_pruebas_items();
						
						$cRespuestasPruebasItems->setIdCandidato($_POST['fIdCandidato']);
						$cRespuestasPruebasItems->setIdPrueba($_POST['fIdPrueba']);
						$cRespuestasPruebasItems->setIdEmpresa($_POST['fIdEmpresa']);
						$cRespuestasPruebasItems->setIdProceso($_POST['fIdProceso']);
						$cRespuestasPruebasItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$cRespuestasPruebasItems->setOrderBy("idItem");
						$cRespuestasPruebasItems->setOrder("ASC");
						
						$cIt = new Items();
						$cIt->setIdPrueba($_POST['fIdPrueba']);
						$cIt->setIdPruebaHast($_POST['fIdPrueba']);
						$cIt->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$sqlItemsPrueba= $cItemsDB->readLista($cIt);
						$listaItemsPrueba = $conn->Execute($sqlItemsPrueba);
						// Montamos la lista de respuestas para los parámetros enviados. 
						
						$sqlRespItems = $cRespuestasPruebasItemsDB->readLista($cRespuestasPruebasItems);
						$listaRespItems = $conn->Execute($sqlRespItems);
						
						//Inicializamos la puntuación directa y el percentil que más tarde transformaremos
						$iPDirecta = 0;
						$iPercentil = 0;
						if($listaRespItems->recordCount()>0)
						{
							while(!$listaRespItems->EOF){
								
								//Leemos el item para saber cual es la opción correcta
								$cItem = new Items();
								$cItem->setIdItem($listaRespItems->fields['idItem']);
								$cItem->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cItem->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cItem = $cItemsDB->readEntidad($cItem);
								
								//Leemos la opción para saber en código de la misma
								$cOpcion = new Opciones();
								$cOpcion->setIdItem($listaRespItems->fields['idItem']);
								$cOpcion->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cOpcion->setIdOpcion($listaRespItems->fields['idOpcion']);
								$cOpcion->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cOpcion = $cOpcionesDB->readEntidad($cOpcion);
								
								//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
								if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
									//echo $listaRespItems->fields['idItem'] . " - bien <br />";
									//Si coincide se le suma uno a la PDirecta.
									$iPDirecta++;
								}
								
								$listaRespItems->MoveNext();
							}
							
							$cBaremos_resultados = new Baremos_resultados();
							$cBaremos_resultados->setIdBaremo($_POST['fIdBaremo']);
							$cBaremos_resultados->setIdPrueba($_POST['fIdPrueba']);
							
							$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
							$listaBaremosResultados = $conn->Execute($sqlBaremosResultados);
							$ipMin=0;
							$ipMax=0;
							// Recorremos la lista de los valores del baremo seleccionado para mirar el percentil que 
							// corresponde con la puntuación directa obtenida.
							if($listaBaremosResultados->recordCount()>0){
								while(!$listaBaremosResultados->EOF){
									
									$ipMin = $listaBaremosResultados->fields['puntMin'];
									$ipMax = $listaBaremosResultados->fields['puntMax'];
									if($ipMin <= $iPDirecta && $iPDirecta <= $ipMax){
										$iPercentil = $listaBaremosResultados->fields['puntBaremada'];
									}
									$listaBaremosResultados->MoveNext();
								}
							}
							
							//Miramos si hay que descontar y cuanro para informar al usuario.
							$dTotalCoste = getDescuentoDongles($cCandidato->getIdEmpresa(), $cCandidato->getIdProceso(), $cCandidato->getIdCandidato(), $cPruebas->getCodIdiomaIso2(), $cPruebas->getIdPrueba(), $cInformesPruebasTrf->getCodIdiomaIso2(), $cInformesPruebasTrf->getIdTipoInforme(), $_POST['fIdBaremo'], $dTotalCoste, true);
							$sMsgDescuento = sprintf(constant("MSG_DESCONTADOS_X_DONGLES"),$dTotalCoste);
							$sNombre .= ".html";
							?>
								<script>
								muestraBotonHTML();
								</script>
							<?php 
							echo "<br /><br /><b>" . $sMsgDescuento . "</b><br />";
//							echo "<a href=\"#_\" title=\"Descargar\" onclick=\"javascript:abrirVentana('0','". base64_encode(constant('HTTP_SERVER'). $sDirImg . $sNombre)."');\">" . constant("STR_DESCARGAR") . "</a>";
						}else{
							?>
							<script>
								escondeLoadHTML();
							</script>
							<?php 
							echo "<br />" . constant("MSG_SIN_RESPUESTAS_PARA_LA_PRUEBA");
						}
						$sTypeError	=	date('d/m/Y H:i:s') . " Generado desde [" . $_SERVER['REMOTE_ADDR'] . "] [" . $sNombre . "][" . constant("MNT_DESCUENTA_DONGLES_HTML") . "][Empresa][ZIP]";
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}else{	
						//Si ya está generado sacamos el enlace
						//Miramos si hay que descontar y cuanro para informar al usuario.
						$dTotalCoste = getDescuentoDongles($cCandidato->getIdEmpresa(), $cCandidato->getIdProceso(), $cCandidato->getIdCandidato(), $cPruebas->getCodIdiomaIso2(), $cPruebas->getIdPrueba(), $cInformesPruebasTrf->getCodIdiomaIso2(), $cInformesPruebasTrf->getIdTipoInforme(), $_POST['fIdBaremo'], $dTotalCoste, true);
						$sMsgDescuento = sprintf(constant("MSG_DESCONTADOS_X_DONGLES"),$dTotalCoste);
						$sNombre .= ".html";
						?>
							<script>
							muestraBotonHTML();
							</script>
						<?php 
						echo "<br /><br /><b>" . $sMsgDescuento . "</b><br />";
//						echo "<a href=\"#_\" title=\"" . constant("STR_DESCARGAR") . "\" onclick=\"javascript:abrirVentana('0','". base64_encode(constant('HTTP_SERVER'). $sDirImg . $sNombre)."');\">" . constant("STR_DESCARGAR") . "</a>";
					}
				}else{
					?>
					<script>
						escondeLoadHTML();
					</script>
					<?php 
					echo "<br />" . constant("MSG_SIN_DONGLES_PARA_VER_INFORME");
				}
			break;
		case constant("MNT_DESCUENTA_DONGLES_WORD"):
				
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/ItemsDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/Items.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/Opciones.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valoresDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valores.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Nivelesjerarquicos/NivelesjerarquicosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Nivelesjerarquicos/Nivelesjerarquicos.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Secciones_informes/Secciones_informesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Secciones_informes/Secciones_informes.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_secciones/Textos_seccionesDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_secciones/Textos_secciones.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textosDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textos.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_irDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_ir.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ipDB.php");
				require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ip.php");
				
				
				$cTextos_seccionesDB = new Textos_seccionesDB($conn);
				$cSecciones_informesDB = new Secciones_informesDB($conn);
				$cRangos_ipDB = new Rangos_ipDB($conn);
				$cRangos_irDB = new Rangos_irDB($conn);
				$cRangos_textosDB = new Rangos_textosDB($conn);
				
				//Cambiar Dongels por Cliente/Prueba/Informe
	    		//Miramos si tiene definido dongles por empresa
    			$cInformesPruebasTrf = new Informes_pruebas_empresas();
    			$cInformesPruebasTrf->setIdPrueba($_POST['fIdPrueba']);
    			$cInformesPruebasTrf->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    			$cInformesPruebasTrf->setIdTipoInforme($_POST['fIdTipoInforme']);
    			$cInformesPruebasTrf->setIdEmpresa($_POST['fIdEmpresa']);
    			
				$sql_IPE = $cInformes_pruebas_empresasDB->readLista($cInformesPruebasTrf);
				$rsIPE = $conn->Execute($sql_IPE);
    			if ($rsIPE->NumRows() > 0){
    				$cInformesPruebasTrf = $cInformes_pruebas_empresasDB->readEntidad($cInformesPruebasTrf);
    				$cInformes_pruebas_empresasDB->addContInforme($cInformesPruebasTrf);
    			}else {
					$cInformesPruebasTrf = new Informes_pruebas();
					$cInformesPruebasTrf->setIdPrueba($_POST['fIdPrueba']);
					$cInformesPruebasTrf->setIdTipoInforme($_POST['fIdTipoInforme']);
					$cInformesPruebasTrf->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
					$cInformesPruebasTrf= $cInformes_pruebasDB->readEntidad($cInformesPruebasTrf);
					$cInformes_pruebasDB->addContInforme($cInformesPruebasTrf);	    				
	   			}
				
//				echo "Entro WORD";
				$cEmpresaDng = new Empresas();
				$cEmpresaDng->setIdEmpresa($_POST['fIdEmpresa']);
				$cEmpresaDng = $cEmpresasDB->readEntidad($cEmpresaDng);
				
				$dTotalCoste=$cInformesPruebasTrf->getTarifa();
				
				$bDescargar=false;
				if ($cEmpresaDng->getPrepago() == 0 || $_EmpresaLogada == constant("EMPRESA_PE")){
					//Descargamos siempre si la empresa logada es PE o NO es por prepago 
					$bDescargar=true;
				}else{
					if($cInformesPruebasTrf->getTarifa() <= $cEmpresaDng->getDongles()){
						$bDescargar=true;
					}else{
						$bDescargar=false;
					}
				}
				$bPDFGenerado = false;	//Indica si el fichero fue generado previamente
				if($bDescargar)
				{
					$cCandidato = new Candidatos();
					$cCandidato->setIdEmpresa($_POST['fIdEmpresa']);
					$cCandidato->setIdProceso($_POST['fIdProceso']);
					$cCandidato->setIdCandidato($_POST['fIdCandidato']);
					
					$cCandidato =  $cCandidatosDB->readEntidad($cCandidato);
					
					$cPruebas = new Pruebas();
					$cPruebas->setIdPrueba($_POST['fIdPrueba']);
					$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
					$cPruebas = $cPruebasDB->readEntidad($cPruebas);
					
					
					//Miramos si ya esiste el pdf en caso que no esista lo generamos
//					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa'] . "_" . $_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2']);
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa'] . "_" . $_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
					$_ficheroPDF = $spath . $sDirImg . $sNombre . ".html";
		
					if(is_file($_ficheroPDF)){
						//De momento que siempre lo genere, si se quiere cambiar habría 
						//que incluir el idioma en el nombre del fichero 
						//y cambiarlo en los templates de generación
						$bPDFGenerado = true;
					}else{
						$bPDFGenerado = false;
					}
					if (!$bPDFGenerado)
					{
						$cRespuestasPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
						$cItemsDB = new ItemsDB($conn);
						$cNivelesjerarquicosDB = new NivelesjerarquicosDB($conn);
						$cOpcionesDB = new OpcionesDB($conn);
						$cOpciones_valoresDB = new Opciones_valoresDB($conn);
						$cBaremos_resultadosDB = new Baremos_resultadosDB($conn);
						
						$idTipoPrueba = $cPruebas->getIdTipoPrueba();
						
						$idTipoInforme=$_POST['fIdTipoInforme'];
						
						$cRespuestasPruebasItems = new Respuestas_pruebas_items();
						
						$cRespuestasPruebasItems->setIdCandidato($_POST['fIdCandidato']);
						$cRespuestasPruebasItems->setIdPrueba($_POST['fIdPrueba']);
						$cRespuestasPruebasItems->setIdEmpresa($_POST['fIdEmpresa']);
						$cRespuestasPruebasItems->setIdProceso($_POST['fIdProceso']);
						$cRespuestasPruebasItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$cRespuestasPruebasItems->setOrderBy("idItem");
						$cRespuestasPruebasItems->setOrder("ASC");
						
						$cIt = new Items();
						$cIt->setIdPrueba($_POST['fIdPrueba']);
						$cIt->setIdPruebaHast($_POST['fIdPrueba']);
						$cIt->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$sqlItemsPrueba= $cItemsDB->readLista($cIt);
						$listaItemsPrueba = $conn->Execute($sqlItemsPrueba);
						// Montamos la lista de respuestas para los parámetros enviados. 
						
						$sqlRespItems = $cRespuestasPruebasItemsDB->readLista($cRespuestasPruebasItems);
						$listaRespItems = $conn->Execute($sqlRespItems);
						
						//Inicializamos la puntuación directa y el percentil que más tarde transformaremos
						$iPDirecta = 0;
						$iPercentil = 0;
						if($listaRespItems->recordCount()>0)
						{
							while(!$listaRespItems->EOF){
								
								//Leemos el item para saber cual es la opción correcta
								$cItem = new Items();
								$cItem->setIdItem($listaRespItems->fields['idItem']);
								$cItem->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cItem->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cItem = $cItemsDB->readEntidad($cItem);
								
								//Leemos la opción para saber en código de la misma
								$cOpcion = new Opciones();
								$cOpcion->setIdItem($listaRespItems->fields['idItem']);
								$cOpcion->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cOpcion->setIdOpcion($listaRespItems->fields['idOpcion']);
								$cOpcion->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cOpcion = $cOpcionesDB->readEntidad($cOpcion);
								
								//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
								if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
									//echo $listaRespItems->fields['idItem'] . " - bien <br />";
									//Si coincide se le suma uno a la PDirecta.
									$iPDirecta++;
								}
								
								$listaRespItems->MoveNext();
							}
							
							$cBaremos_resultados = new Baremos_resultados();
							$cBaremos_resultados->setIdBaremo($_POST['fIdBaremo']);
							$cBaremos_resultados->setIdPrueba($_POST['fIdPrueba']);
							
							$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
							$listaBaremosResultados = $conn->Execute($sqlBaremosResultados);
							$ipMin=0;
							$ipMax=0;
							// Recorremos la lista de los valores del baremo seleccionado para mirar el percentil que 
							// corresponde con la puntuación directa obtenida.
							if($listaBaremosResultados->recordCount()>0){
								while(!$listaBaremosResultados->EOF){
									
									$ipMin = $listaBaremosResultados->fields['puntMin'];
									$ipMax = $listaBaremosResultados->fields['puntMax'];
									if($ipMin <= $iPDirecta && $iPDirecta <= $ipMax){
										$iPercentil = $listaBaremosResultados->fields['puntBaremada'];
									}
									$listaBaremosResultados->MoveNext();
								}
							}
							
							//Miramos si hay que descontar y cuanro para informar al usuario.
							$dTotalCoste = getDescuentoDongles($cCandidato->getIdEmpresa(), $cCandidato->getIdProceso(), $cCandidato->getIdCandidato(), $cPruebas->getCodIdiomaIso2(), $cPruebas->getIdPrueba(), $cInformesPruebasTrf->getCodIdiomaIso2(), $cInformesPruebasTrf->getIdTipoInforme(), $_POST['fIdBaremo'], $dTotalCoste, true);
							$sMsgDescuento = sprintf(constant("MSG_DESCONTADOS_X_DONGLES"),$dTotalCoste);
							$sNombre .= ".html";
							?>
								<script>
								muestraBotonWORD();
								</script>
							<?php 
							echo "<br /><br /><b>" . $sMsgDescuento . "</b><br />";
//							echo "<a href=\"#_\" title=\"Descargar\" onclick=\"javascript:abrirVentana('0','". base64_encode(constant('HTTP_SERVER'). $sDirImg . $sNombre)."');\">" . constant("STR_DESCARGAR") . "</a>";
						}else{
							?>
							<script>
								escondeLoadWORD();
							</script>
							<?php 
							echo "<br />" . constant("MSG_SIN_RESPUESTAS_PARA_LA_PRUEBA");
						}
						$sTypeError	=	date('d/m/Y H:i:s') . " Generado desde [" . $_SERVER['REMOTE_ADDR'] . "] [" . $sNombre . "][" . constant("MNT_DESCUENTA_DONGLES_WORD") . "][Empresa][ZIP]";
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}else{	
						//Si ya está generado sacamos el enlace
						//Miramos si hay que descontar y cuanro para informar al usuario.
						$dTotalCoste = getDescuentoDongles($cCandidato->getIdEmpresa(), $cCandidato->getIdProceso(), $cCandidato->getIdCandidato(), $cPruebas->getCodIdiomaIso2(), $cPruebas->getIdPrueba(), $cInformesPruebasTrf->getCodIdiomaIso2(), $cInformesPruebasTrf->getIdTipoInforme(), $_POST['fIdBaremo'], $dTotalCoste, true);
						$sMsgDescuento = sprintf(constant("MSG_DESCONTADOS_X_DONGLES"),$dTotalCoste);
						$sNombre .= ".html";
						?>
							<script>
							muestraBotonWORD();
							</script>
						<?php 
						echo "<br /><br /><b>" . $sMsgDescuento . "</b><br />";
//						echo "<a href=\"#_\" title=\"" . constant("STR_DESCARGAR") . "\" onclick=\"javascript:abrirVentana('0','". base64_encode(constant('HTTP_SERVER'). $sDirImg . $sNombre)."');\">" . constant("STR_DESCARGAR") . "</a>";
					}
				}else{
					?>
					<script>
						escondeLoadWORD();
					</script>
					<?php 
					echo "<br />" . constant("MSG_SIN_DONGLES_PARA_VER_INFORME");
				}
			break;
		default:
			$cEntidad->setCodIdiomaIso2($sLang);
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Informes_candidato_zip/mntrespuestas_pruebas.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdEmpresa((isset($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : "");
		global $comboDESC_EMPRESAS;
		$sIdEmpresa = (isset($_POST["fIdEmpresa"])  ? $_POST["fIdEmpresa"] : "");
		$cEntidad->setDescEmpresa($comboDESC_EMPRESAS->getDescripcionCombo($sIdEmpresa));
		$cEntidad->setIdProceso((isset($_POST["fIdProceso"])) ? $_POST["fIdProceso"] : "");
		global $comboDESC_PROCESOS;
		$sIdProceso = (isset($_POST["fIdProceso"])  ? $_POST["fIdProceso"] : "");
		$cEntidad->setDescProceso($comboDESC_PROCESOS->getDescripcionCombo($sIdProceso));
		$cEntidad->setIdCandidato((isset($_POST["fIdCandidato"])) ? $_POST["fIdCandidato"] : "");
		global $comboDESC_CANDIDATOS;
		$sIdCandidato = (isset($_POST["fIdCandidato"])  ? $_POST["fIdCandidato"] : "");
		$cEntidad->setDescCandidato($comboDESC_CANDIDATOS->getDescripcionCombo($sIdCandidato));
		$cEntidad->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"])) ? $_POST["fCodIdiomaIso2"] : "");
		global $comboDESC_WI_IDIOMAS;
		$sCodIdiomaIso2 = (isset($_POST["fCodIdiomaIso2"])  ? $_POST["fCodIdiomaIso2"] : "");
		$cEntidad->setDescIdiomaIso2($comboDESC_WI_IDIOMAS->getDescripcionCombo($sCodIdiomaIso2));
		$cEntidad->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
		global $comboDESC_PRUEBAS;
		$sIdPrueba = (isset($_POST["fIdPrueba"])  ? $_POST["fIdPrueba"] : "");
		$cEntidad->setDescPrueba($comboDESC_PRUEBAS->getDescripcionCombo($sIdPrueba));
		$cEntidad->setFinalizado((isset($_POST["fFinalizado"])) ? $_POST["fFinalizado"] : "");
		$cEntidad->setLeidoInstrucciones((isset($_POST["fLeidoInstrucciones"])) ? $_POST["fLeidoInstrucciones"] : "");
		$cEntidad->setLeidoEjemplos((isset($_POST["fLeidoEjemplos"])) ? $_POST["fLeidoEjemplos"] : "");
		$cEntidad->setMinutos_test((isset($_POST["fMinutos_test"])) ? $_POST["fMinutos_test"] : "");
		$cEntidad->setSegundos_test((isset($_POST["fSegundos_test"])) ? $_POST["fSegundos_test"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
		return $cEntidad;
	}
	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readLista($cEntidad){
		global $conn;
		global $cUtilidades;
		global $comboEMPRESAS;
		$cEntidad->setIdEmpresa((isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "") ? $_POST["LSTIdEmpresa"] : "");	$cEntidad->setBusqueda(constant("STR_EMPRESA"), (isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "" ) ? $comboEMPRESAS->getDescripcionCombo($_POST["LSTIdEmpresa"]) : "");
		$cEntidad->setDescEmpresa((isset($_POST["LSTDescEmpresa"]) && $_POST["LSTDescEmpresa"] != "") ? $_POST["LSTDescEmpresa"] : "");	$cEntidad->setBusqueda(constant("STR_EMPRESA"), (isset($_POST["LSTDescEmpresa"]) && $_POST["LSTDescEmpresa"] != "" ) ? $_POST["LSTDescEmpresa"] : "");
		global $comboPROCESOS;
		$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","","","idEmpresa=" . $conn->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $conn->qstr($cEntidad->getIdProceso(), false),"","fecMod");
		$cEntidad->setIdProceso((isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "") ? $_POST["LSTIdProceso"] : "");	$cEntidad->setBusqueda(constant("STR_PROCESO"), (isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "" ) ? $comboPROCESOS->getDescripcionCombo($_POST["LSTIdProceso"]) : "");
		$cEntidad->setDescProceso((isset($_POST["LSTDescProceso"]) && $_POST["LSTDescProceso"] != "") ? $_POST["LSTDescProceso"] : "");	$cEntidad->setBusqueda(constant("STR_PROCESO"), (isset($_POST["LSTDescProceso"]) && $_POST["LSTDescProceso"] != "" ) ? $_POST["LSTDescProceso"] : "");
		global $comboCANDIDATOS;
		$comboCANDIDATOS	= new Combo($conn,"fIdCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","",constant("SLC_OPCION"),"idEmpresa=" . $conn->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $conn->qstr($cEntidad->getIdProceso(), false),"","nombre,apellido1,apellido2,mail");
		$cEntidad->setIdCandidato((isset($_POST["LSTIdCandidato"]) && $_POST["LSTIdCandidato"] != "") ? $_POST["LSTIdCandidato"] : "");	$cEntidad->setBusqueda(constant("STR_CANDIDATO"), (isset($_POST["LSTIdCandidato"]) && $_POST["LSTIdCandidato"] != "" ) ? $comboCANDIDATOS->getDescripcionCombo($_POST["LSTIdCandidato"]) : "");
		$cEntidad->setDescCandidato((isset($_POST["LSTDescCandidato"]) && $_POST["LSTDescCandidato"] != "") ? $_POST["LSTDescCandidato"] : "");	$cEntidad->setBusqueda(constant("STR_CANDIDATO"), (isset($_POST["LSTDescCandidato"]) && $_POST["LSTDescCandidato"] != "" ) ? $_POST["LSTDescCandidato"] : "");
		global $comboWI_IDIOMAS;
		$cEntidad->setCodIdiomaIso2((isset($_POST["LSTCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "") ? $_POST["LSTCodIdiomaIso2"] : "");	$cEntidad->setBusqueda(constant("STR_IDIOMA"), (isset($_POST["LSTCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "" ) ? $comboWI_IDIOMAS->getDescripcionCombo($_POST["LSTCodIdiomaIso2"]) : "");
		$cEntidad->setDescIdiomaIso2((isset($_POST["LSTDescIdiomaIso2"]) && $_POST["LSTDescIdiomaIso2"] != "") ? $_POST["LSTDescIdiomaIso2"] : "");	$cEntidad->setBusqueda(constant("STR_IDIOMA"), (isset($_POST["LSTDescIdiomaIso2"]) && $_POST["LSTDescIdiomaIso2"] != "" ) ? $_POST["LSTDescIdiomaIso2"] : "");
		global $comboPRUEBAS;
		$cEntidad->setIdPrueba((isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "") ? $_POST["LSTIdPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_PRUEBA"), (isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "" ) ? $comboPRUEBAS->getDescripcionCombo($_POST["LSTIdPrueba"]) : "");
		$cEntidad->setDescPrueba((isset($_POST["LSTDescPrueba"]) && $_POST["LSTDescPrueba"] != "") ? $_POST["LSTDescPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_PRUEBA"), (isset($_POST["LSTDescPrueba"]) && $_POST["LSTDescPrueba"] != "" ) ? $_POST["LSTDescPrueba"] : "");
		$cEntidad->setFinalizado("1");
		$cEntidad->setFinalizadoHast("1");
		$cEntidad->setLeidoInstrucciones((isset($_POST["LSTLeidoInstrucciones"]) && $_POST["LSTLeidoInstrucciones"] != "") ? $_POST["LSTLeidoInstrucciones"] : "");	$cEntidad->setBusqueda(constant("STR_LEIDO_INSTRUCCIONES"), (isset($_POST["LSTLeidoInstrucciones"]) && $_POST["LSTLeidoInstrucciones"] != "" ) ? $_POST["LSTLeidoInstrucciones"] : "");
		$cEntidad->setLeidoInstruccionesHast((isset($_POST["LSTLeidoInstruccionesHast"]) && $_POST["LSTLeidoInstruccionesHast"] != "") ? $_POST["LSTLeidoInstruccionesHast"] : "");	$cEntidad->setBusqueda(constant("STR_LEIDO_INSTRUCCIONES") . " " . constant("STR_HASTA"), (isset($_POST["LSTLeidoInstruccionesHast"]) && $_POST["LSTLeidoInstruccionesHast"] != "" ) ? $_POST["LSTLeidoInstruccionesHast"] : "");
		$cEntidad->setLeidoEjemplos((isset($_POST["LSTLeidoEjemplos"]) && $_POST["LSTLeidoEjemplos"] != "") ? $_POST["LSTLeidoEjemplos"] : "");	$cEntidad->setBusqueda(constant("STR_LEIDO_EJEMPLOS"), (isset($_POST["LSTLeidoEjemplos"]) && $_POST["LSTLeidoEjemplos"] != "" ) ? $_POST["LSTLeidoEjemplos"] : "");
		$cEntidad->setLeidoEjemplosHast((isset($_POST["LSTLeidoEjemplosHast"]) && $_POST["LSTLeidoEjemplosHast"] != "") ? $_POST["LSTLeidoEjemplosHast"] : "");	$cEntidad->setBusqueda(constant("STR_LEIDO_EJEMPLOS") . " " . constant("STR_HASTA"), (isset($_POST["LSTLeidoEjemplosHast"]) && $_POST["LSTLeidoEjemplosHast"] != "" ) ? $_POST["LSTLeidoEjemplosHast"] : "");
		$cEntidad->setMinutos_test((isset($_POST["LSTMinutos_test"]) && $_POST["LSTMinutos_test"] != "") ? $_POST["LSTMinutos_test"] : "");	$cEntidad->setBusqueda(constant("STR_MINUTOS_TEST"), (isset($_POST["LSTMinutos_test"]) && $_POST["LSTMinutos_test"] != "" ) ? $_POST["LSTMinutos_test"] : "");
		$cEntidad->setSegundos_test((isset($_POST["LSTSegundos_test"]) && $_POST["LSTSegundos_test"] != "") ? $_POST["LSTSegundos_test"] : "");	$cEntidad->setBusqueda(constant("STR_SEGUNDOS_TEST"), (isset($_POST["LSTSegundos_test"]) && $_POST["LSTSegundos_test"] != "" ) ? $_POST["LSTSegundos_test"] : "");
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

	
/******************************************************************
* Funciones generales para todas las pruebas
******************************************************************/
	/*
	* Devuelve la información de los dongles a descontar si hay que descontar 
	* y 0 si no hay que descontar
	*/
	function getDescuentoDongles($idEmpresa, $idProceso, $idCandidato, $codIdiomaIso2, $idPrueba, $codIdiomaIntorme, $idTipoInforme, $idBaremo, $dTotalCoste, $bDescontar=false){
		global $conn;
		$iRetorno = $dTotalCoste;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Consumos/ConsumosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Consumos/Consumos.php");
		
		$oConsumos = new Consumos();
		$oConsumosDB = new ConsumosDB($conn);
		
		$oConsumos->setIdEmpresa($idEmpresa);
		$oConsumos->setIdProceso($idProceso);
		$oConsumos->setIdCandidato($idCandidato);
		$oConsumos->setCodIdiomaIso2($codIdiomaIso2);
		$oConsumos->setIdPrueba($idPrueba);
		$oConsumos->setCodIdiomaInforme($codIdiomaIntorme);
		$oConsumos->setIdTipoInforme($idTipoInforme);
		$oConsumos->setOrderBy("idConsumo");
		$oConsumos->setOrder("ASC");
		$sSQLConsumos = $oConsumosDB->readLista($oConsumos);
//		echo "<br />" . $sSQLConsumos;
		$rsConsumos = $conn->Execute($sSQLConsumos);
		if($rsConsumos->recordCount() == 0){
			//NO hemos encontrado ninguún registro, con lo que hay que 
			//Descontar, normalmente son peticiones en otro idioma u
			// Otro informe
			$iRetorno = $dTotalCoste;
		}else{
		if($rsConsumos->recordCount() == 1){
			//Hemos encontrado un registro, con lo que ya se le ha descontado
			//En la finalización de la prueba del candidato
			$iRetorno = 0;
		}else{
				//Miro si ha pasado un año desde que se genreró la priemra vez
				$iDias = diff_dte($rsConsumos->fields['fecAlta'], "now");
//				echo "<br />-->" . $iDias;
				if ($iDias > 365){
					//Miro a ver si ya solicitó una regeneración
					$oConsumos = new Consumos();
					$oConsumosDB = new ConsumosDB($conn);
					
					$oConsumos->setIdEmpresa($idEmpresa);
					$oConsumos->setIdProceso($idProceso);
					$oConsumos->setIdCandidato($idCandidato);
					$oConsumos->setCodIdiomaIso2($codIdiomaIso2);
					$oConsumos->setIdPrueba($idPrueba);
					$oConsumos->setCodIdiomaInforme($codIdiomaIntorme);
					$oConsumos->setIdTipoInforme($idTipoInforme);
					$oConsumos->setUnidades(1);
					$oConsumos->setUnidadesHast(999999);
					$oConsumos->setOrderBy("idConsumo");
					$oConsumos->setOrder("DESC");
					$sSQLConsumos = $oConsumosDB->readLista($oConsumos);
//					echo "<br />**//-->" . $sSQLConsumos;
					$rsConsumos = $conn->Execute($sSQLConsumos);
					if ($rsConsumos->recordCount() > 1 ){
						//Ya ha solicitado una regeneración, miro la fecha
						// de la última x eso está ordenado DESC
						$iDias = diff_dte($rsConsumos->fields['fecAlta'], "now");
//						echo "<br />**//-->" . $iDias;
						if ($iDias > 365){
							$iRetorno = $dTotalCoste;
						}else {
							$iRetorno = 0;
						}
					}else {
						$iRetorno = 0;
					}
				}else{
					//Si no ha pasado más de un año no cobro
					$iRetorno = 0;
				}
	//			if ($rsConsumos->recordCount() > 1 ){
	//				//Hemos encontrado MAS de un registro, con lo que ya se le ha descontado
	//				// y se lo descargó, hay que volver ha cobrar si lo quiere.
	//				$iRetorno = $dTotalCoste;
	//			}
			}
		}
		if ($bDescontar){
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_informes/Tipos_informesDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_informes/Tipos_informes.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos/BaremosDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos/Baremos.php");
								
			$cEmpresas = new Empresas();
			$cEmpresasDB = new EmpresasDB($conn);
			$cEmpresas->setIdEmpresa($idEmpresa);
			$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
			
			$cProcesos = new Procesos();
			$cProcesosDB = new ProcesosDB($conn);
			$cProcesos->setIdEmpresa($idEmpresa);
			$cProcesos->setIdProceso($idProceso);
			$cProcesos = $cProcesosDB->readEntidad($cProcesos);
			
			$cCandidatos = new Candidatos();
			$cCandidatosDB = new CandidatosDB($conn);
			$cCandidatos->setIdEmpresa($idEmpresa);
			$cCandidatos->setIdProceso($idProceso);
			$cCandidatos->setIdCandidato($idCandidato);
			$cCandidatos = $cCandidatosDB->readEntidad($cCandidatos);

			$cPruebas = new Pruebas();
			$cPruebasDB = new PruebasDB($conn);
			$cPruebas->setCodIdiomaIso2($codIdiomaIso2);
			$cPruebas->setIdPrueba($idPrueba);
			$cPruebas = $cPruebasDB->readEntidad($cPruebas);
			
			$cTipos_informes = new Tipos_informes();
			$cTipos_informesDB = new Tipos_informesDB($conn);
			$cTipos_informes->setCodIdiomaIso2($codIdiomaIntorme);
			$cTipos_informes->setIdTipoInforme($idTipoInforme);
			$cTipos_informes = $cTipos_informesDB->readEntidad($cTipos_informes);
			
			$cBaremos = new Baremos();
			$cBaremosDB = new BaremosDB($conn);
			$cBaremos->setIdBaremo($idBaremo);
			$cBaremos->setIdPrueba($idPrueba);
			$cBaremos = $cBaremosDB->readEntidad($cBaremos);
			$oConsumos->setIdBaremo($idBaremo);
			$oConsumos->setNomEmpresa($cEmpresas->getNombre());
			$oConsumos->setNomProceso($cProcesos->getNombre());
			$oConsumos->setNomCandidato($cCandidatos->getNombre());
			$oConsumos->setApellido1($cCandidatos->getApellido1());
			$oConsumos->setApellido2($cCandidatos->getApellido2());
			$oConsumos->setDni($cCandidatos->getDni());
			$oConsumos->setMail($cCandidatos->getMail());
			$oConsumos->setNomPrueba($cPruebas->getNombre());
			$oConsumos->setNomInforme($cTipos_informes->getNombre());
			$oConsumos->setNomBaremo($cBaremos->getNombre());
			$oConsumos->setConcepto(constant("STR_DESCARGA_DE_INTORME"));
			$oConsumos->setUnidades($iRetorno);
			$oConsumos->setUsuAlta($cCandidatos->getIdCandidato());
			$oConsumos->setUsuMod($cCandidatos->getIdCandidato());
			
			$idAlta = $oConsumosDB->insertar($oConsumos);
			if ($iRetorno > 0 ){
	    		//Lo descontamos de la empresa
	    		$dResto= ($cEmpresas->getDongles() - $dTotalCoste);
	    		$cEmpresas->setDongles($dResto);
	    		$cEmpresasDB->modificarSinPass($cEmpresas);
			}
		}
		return $iRetorno;
	}
	
	/**
	* Chequea si el directorio existe, si no, lo crea con atributos (chmod=777).
	* @param String destpath
	* @return boolean
	*/
	function chk_dir($path, $mode = 0777) //creates directory tree recursively
	{
		$dirs = explode('/', $path);
		$pos = strrpos($path, ".");
		if ($pos === false) { // note: three equal signs
			// not found, means path ends in a dir not file
			$subamount=0;
		}else	$subamount=1;
		
		for ($c=0;$c < count($dirs) - $subamount; $c++) {
			$thispath="";
			for ($cc=0; $cc <= $c; $cc++)
				$thispath .= $dirs[$cc] . '/';
			if (!file_exists($thispath)){
				$oldumask = umask(0);
				mkdir($thispath, $mode);
				umask($oldumask);
			}
		}
		return true;
	}
	
	function diff_dte($date1, $date2)
	{
		if (!is_integer($date1)) $date1 = strtotime($date1);
	    if (!is_integer($date2)) $date2 = strtotime($date2);  
	    return floor(abs($date1 - $date2) / 60 / 60 / 24);
	} 
/******************************************************************
* FIN de Funciones generales para todas las pruebas
******************************************************************/
	
?>