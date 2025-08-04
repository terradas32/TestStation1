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
	$_sBaremo="";
	//Sacamos el literal del baremo para pintarlo en los informes si lo tiene
	if (isset($_POST['fIdBaremo']) && isset($_POST['fIdPrueba']))
	{
		$cBaremos = new Baremos();
		$cBaremosDB = new BaremosDB($conn);
		$cBaremos->setIdBaremo(isset($_POST['fIdBaremo']) ? $_POST['fIdBaremo'] : "1");
		$cBaremos->setIdPrueba($_POST['fIdPrueba']);
		$cBaremos = $cBaremosDB->readEntidad($cBaremos);
		$_sBaremo = $cBaremos->getNombre();
	}
	$sHijos = "";
	if (empty($_POST["fHijos"]))
	{
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . 'Empresas/Empresas.php');
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
		$cEmpresaPadre = new Empresas();
		$cEmpresaPadreDB = new EmpresasDB($conn);
	//	$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
	//Se llama desde Empresas y Candidatos para la generación de informes
	$sLlamada="Candidato";
		if (!empty($_SERVER["HTTP_REFERER"])){
			if (strpos($_SERVER["HTTP_REFERER"], '/Admin/') === false) {
				$_EmpresaLogada = $_POST['fIdEmpresa'];
				$sLlamada="Empresa";
				if (strpos($_SERVER["HTTP_REFERER"], '/Empresa/') === false) {
					$sLlamada="Candidato";
				}
			}else{
				$sLlamada="Admin";
				$_EmpresaLogada = constant("EMPRESA_PE");
			}
		}else{
			$_EmpresaLogada = constant("EMPRESA_PE");
		}
		$sHijos = $cEmpresaPadreDB->getHijos($_EmpresaLogada);
		if (!empty($sHijos)){
			$sHijos .= $_EmpresaLogada;
		}else{
			$sHijos = $_EmpresaLogada;
		}
	}else{
		$sHijos = $_POST["fHijos"];
	}
	$comboEMPRESAS	= new Combo($conn,"fIdEmpresa","idEmpresa","nombre","Descripcion","empresas","",constant("SLC_OPCION"),"idEmpresa IN(" . $sHijos . ")","","orden");
	$comboDESC_EMPRESAS	= new Combo($conn,"_fDescEmpresa","idEmpresa","nombre","Descripcion","empresas","","","","","fecMod");
	$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","",constant("SLC_OPCION"),"","","fecMod");
	$comboDESC_PROCESOS	= new Combo($conn,"_fDescProceso","idProceso","nombre","Descripcion","procesos","","","","","fecMod");
	$comboCANDIDATOS	= new Combo($conn,"fIdCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","",constant("SLC_OPCION"),"","","nombre,apellido1,apellido2,mail");
	$comboDESC_CANDIDATOS	= new Combo($conn,"_fDescCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","",constant("SLC_OPCION"),"","","fecMod");
	$comboWI_IDIOMAS	= new Combo($conn,"fCodIdiomaIso2","codIdiomaIso2","nombre","Descripcion","wi_idiomas","",constant("SLC_OPCION"),"activoFront=1","","fecMod");
	$comboDESC_WI_IDIOMAS	= new Combo($conn,"_fDescIdiomaIso2","codIdiomaIso2","nombre","Descripcion","wi_idiomas","","","","","fecMod");
	$comboPRUEBAS	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboDESC_PRUEBAS	= new Combo($conn,"_fDescPrueba","idPrueba","nombre","Descripcion","pruebas","","","","","fecMod");
	$comboWI_USUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","fecMod");
	$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	//echo('modo:' . $_POST['MODO']);

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
					include('Template/Informes_candidato/mntrespuestas_pruebasl.php');
				}else{
					$cEntidad	= new Respuestas_pruebas();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Informes_candidato/mntrespuestas_pruebasa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Informes_candidato/mntrespuestas_pruebasa.php');
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
				include('Template/Informes_candidato/mntrespuestas_pruebasl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Informes_candidato/mntrespuestas_pruebasa.php');
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
			include('Template/Informes_candidato/mntrespuestas_pruebasl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Informes_candidato/mntrespuestas_pruebasa.php');
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
			$cProceso_informes->setIdBaremo($rsProceso_informes->fields['idBaremo']);
			$cProceso_informes = $cProceso_informesDB->readEntidad($cProceso_informes);

			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Informes_candidato/mntrespuestas_pruebasa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Informes_candidato/mntrespuestas_pruebas.php');
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
			include('Template/Informes_candidato/mntrespuestas_pruebasl.php');
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
			include('Template/Informes_candidato/listaidiomas.php');
			break;
		case constant("MNT_LISTATIPOS"):

			if(isset($_POST['fIdPrueba']) && $_POST['fIdPrueba']!=""){
				$cInformes_pruebas	= new Informes_pruebas();
				$cInformes_pruebas->setIdPrueba($_POST['fIdPrueba']);
				$cInformes_pruebas->setCodIdiomaIso2($sLang);

				$sqlTipos= $cInformes_pruebasDB->readLista($cInformes_pruebas);
				$listaTipos = $conn->Execute($sqlTipos);
			}
			include('Template/Informes_candidato/checkstipos.php');
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
			include('Template/Informes_candidato/listabaremos.php');
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
				//--
				$cEmpresaDng = new Empresas();
				$cEmpresaDng->setIdEmpresa($_POST['fIdEmpresa']);
				$cEmpresaDng = $cEmpresasDB->readEntidad($cEmpresaDng);

				$_sPrepago = "1";
				//Miramos si hay que descontar de la Matriz
				$sDescuentaMatriz = $cEmpresaDng->getDescuentaMatriz();
				$cMatrizDng = new Empresas();
				if (!empty($sDescuentaMatriz)){
					$cMatrizDng->setIdEmpresa($sDescuentaMatriz);
					$cMatrizDng = $cEmpresasDB->readEntidad($cMatrizDng);
					$_sPrepago = $cMatrizDng->getPrepago();
				}else{
					$_sPrepago = $cEmpresaDng->getPrepago();
				}

				$dTotalCoste=$cInformesPruebasTrf->getTarifa();

				$bDescargar=false;
//				echo "<br />Prepago::" . $_sPrepago;
//				echo "<br />Empresa::" . $cEmpresaDng->getIdEmpresa();
//				echo "<br />Dongles::" . $cEmpresaDng->getDongles();
//				echo "<br />Tarifa::" . $cInformesPruebasTrf->getTarifa();
//				echo "<br />_EmpresaLogada::" . $_EmpresaLogada;
//				echo "<br />EMPRESA_PE::" . constant("EMPRESA_PE");
				echo "<br />HTTP_REFERER::" . $_SERVER["HTTP_REFERER"];
				$cCandidato = new Candidatos();
				$cCandidato->setIdEmpresa($_POST['fIdEmpresa']);
				$cCandidato->setIdProceso($_POST['fIdProceso']);
				$cCandidato->setIdCandidato($_POST['fIdCandidato']);

				$cCandidato =  $cCandidatosDB->readEntidad($cCandidato);

				$cPruebas = new Pruebas();
				$cPruebas->setIdPrueba($_POST['fIdPrueba']);
				$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
				$cPruebas = $cPruebasDB->readEntidad($cPruebas);

				if ($_sPrepago == 0 || $_EmpresaLogada == constant("EMPRESA_PE") || $sLlamada == "Candidato"){
					//Descargamos siempre si la empresa logada es PE o NO es por prepago
					$bDescargar=true;
				}else{
					//Miramos si ya esiste el pdf, si existe el pdf es que ya fueron descontadas las unidades y no ha pasado un año
					//Con lo que no habria que descontar nada aunque no tenga unidades
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
					$_ficheroPDF = $spath . $sDirImg . $sNombre . ".pdf";
					if(is_file($_ficheroPDF)){
						$bDescargar=true;
					}else{
						if (!empty($sDescuentaMatriz)){
							if($cInformesPruebasTrf->getTarifa() <= $cMatrizDng->getDongles()){
								$bDescargar=true;
							}else{
								$bDescargar=false;
							}
						}else{
							if($cInformesPruebasTrf->getTarifa() <= $cEmpresaDng->getDongles()){
								$bDescargar=true;
							}else{
								if ($cEmpresaDng->getDongles() == 0){
									$bDescargar=true;
								}else{
									$bDescargar=false;
								}

							}
						}
					}
				}
				$bPDFGenerado = false;	//Indica si el fichero fue generado previamente
				if($bDescargar)
				{

					//Miramos si ya esiste el pdf en caso que no esista lo generamos
//					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2']);
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
					$_ficheroPDF = $spath . $sDirImg . $sNombre . ".pdf";
					echo "<br>" . $_ficheroPDF;
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
						echo "<br>IF**" . $bPDFGenerado;
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

								$_sValor =  $cUtilidades->getValorCalculadoPRUEBAS($listaRespItems, $cOpcion, $conn);

								//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
								if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
									//echo $listaRespItems->fields['idItem'] . " - bien <br />";
									//Si es correcta, para este tipo de pruebas
									//Seteo a 1 el campo valor
									$sSQLValor = "UPDATE respuestas_pruebas_items SET valor=" . $conn->qstr($_sValor, false);
									$sSQLValor .= " WHERE";
									$sSQLValor .= " idEmpresa='" . $_POST['fIdEmpresa'] . "'";
									$sSQLValor .= " AND idProceso='" . $_POST['fIdProceso'] . "'";
									$sSQLValor .= " AND idCandidato='" . $_POST['fIdCandidato'] . "'";
									$sSQLValor .= " AND codIdiomaIso2='" . $_POST['fCodIdiomaIso2Prueba'] . "'";
									$sSQLValor .= " AND idPrueba='" . $listaRespItems->fields['idPrueba'] . "'";
									$sSQLValor .= " AND idItem='" . $listaRespItems->fields['idItem'] . "'";
//									echo "<br />//A*****--->correcta:: " . $sSQLValor;
									$conn->Execute($sSQLValor);

									//Si coincide se le suma uno a la PDirecta.
									$iPDirecta++;
								}

								$listaRespItems->MoveNext();
							}

							$cBaremos_resultados = new Baremos_resultados();
							$cBaremos_resultados->setIdBaremo($_POST['fIdBaremo']);
							$cBaremos_resultados->setIdPrueba($_POST['fIdPrueba']);

							$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
//							echo "<br />A" . $sqlBaremosResultados . "<br />";
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

// 							echo "pDirecta: " . $iPDirecta . "<br />";
// 							echo "pPercentil: " . $iPercentil . "<br />";


							include('constantesInformes/' .	$_POST['fCodIdiomaIso2'] .'.php');

							$cRespPruebas = new Respuestas_pruebas();
							$cRespPruebasDB = new Respuestas_pruebasDB($conn);
							$cRespPruebas->setIdEmpresa($cCandidato->getIdEmpresa());
							$cRespPruebas->setIdProceso($cCandidato->getIdProceso());
							$cRespPruebas->setIdCandidato($cCandidato->getIdCandidato());
							$cRespPruebas->setIdPrueba($cPruebas->getIdPrueba());
							$cRespPruebas->setCodIdiomaIso2($cPruebas->getCodIdiomaIso2());
							$cRespPruebas = $cRespPruebasDB->readEntidad($cRespPruebas);

							$cProceso = new Procesos();
							$cProcesoDB	= new ProcesosDB($conn);
							$cProceso->setIdEmpresa($cCandidato->getIdEmpresa());
							$cProceso->setIdProceso($cCandidato->getIdProceso());
							$cProceso = $cProcesoDB->readEntidad($cProceso);

							echo "<br />TIPO::" . $cPruebas->getIdTipoPrueba();
							switch ($cPruebas->getIdTipoPrueba())
							{
								case 6:	//Motivaviones
								case 7:	//Personalidad
								case 11:	//FLASH tipo Personalidad
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
									echo "<br />Inicio Generación:: " . date('d/m/Y H:i:s');
									
									include('Template/Informes_candidato/generaPrueba' . $cPruebas->getIdPrueba() . '.php');
									echo "<br />He generado el informe en HTML y PDF:: " . date('d/m/Y H:i:s');
									echo "<br />Inicio Generación Puntuaciones:: " . date('d/m/Y H:i:s');
									//Para las de Personalidad
									if (isset($aSQLPuntuacionesPPL) || isset($aSQLPuntuacionesC))
									{
										//Se guardan los resultados calculados en respuestas pruebas

										$cRespPruebas = new Respuestas_pruebas();
										$cRespPruebasDB = new Respuestas_pruebasDB($conn);
										$cRespPruebas->setIdEmpresa($cCandidato->getIdEmpresa());
										$cRespPruebas->setIdProceso($cCandidato->getIdProceso());
										$cRespPruebas->setIdCandidato($cCandidato->getIdCandidato());
										$cRespPruebas->setIdPrueba($cPruebas->getIdPrueba());
										$cRespPruebas->setCodIdiomaIso2($cPruebas->getCodIdiomaIso2());
										$cRespPruebas = $cRespPruebasDB->readEntidad($cRespPruebas);

										$cProceso = new Procesos();
										$cProcesoDB	= new ProcesosDB($conn);
										$cProceso->setIdEmpresa($cCandidato->getIdEmpresa());
										$cProceso->setIdProceso($cCandidato->getIdProceso());
										$cProceso = $cProcesoDB->readEntidad($cProceso);

										//$_sBaremo=$cBaremos->getNombre();

										//Miramos primero si ya está guardado
										$sSQL = "DELETE FROM export_personalidad ";
										$sSQL .= " WHERE ";
										$sSQL .= " idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false);
										$sSQL .= " AND idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false);
										$sSQL .= " AND idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false);
										$sSQL .= " AND idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false);
										$sSQL .= " AND idBaremo=" . $conn->qstr($_POST["fIdBaremo"], false);
										$sSQL .= " AND idTipoInforme=" . $conn->qstr($_POST['fIdTipoInforme'], false);

										$rsCuantos = $conn->Execute($sSQL);
										if ($rsCuantos->NumRows() <= 0)
										{
											$cobrado=0;
											if ($sLlamada=="Candidato"){
												$cobrado=1;
											}
											$sSQL = "INSERT INTO export_personalidad (idEmpresa, descEmpresa, idProceso, descProceso, idCandidato, nombre, apellido1, apellido2, email, dni, idPrueba, descPrueba, fecPrueba, idBaremo, descBaremo, idTipoInforme, descTipoInforme, fecAltaProceso, idSexo,idEdad, idFormacion, idNivel, idArea, cobrado, fecAlta, fecMod, usuAlta, usuMod) VALUES ";
											$sSQL .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getDescEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cCandidato->getNombre(), false) . "," . $conn->qstr($cCandidato->getApellido1(), false) . "," . $conn->qstr($cCandidato->getApellido2(), false) . "," . $conn->qstr($cCandidato->getMail(), false) . "," . $conn->qstr($cCandidato->getDni(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST["fIdBaremo"], false) . "," . $conn->qstr($_sBaremo, false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($sDescInforme, false) . "," . $conn->qstr($cProceso->getFecAlta(), false) . "," . $conn->qstr($cCandidato->getIdSexo(), false) . "," . $conn->qstr($cCandidato->getIdEdad(), false) . "," . $conn->qstr($cCandidato->getIdFormacion(), false) . "," . $conn->qstr($cCandidato->getIdNivel(), false) . "," . $conn->qstr($cCandidato->getIdArea(), false) . "," . $cobrado . ",now(),now()," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . ");";
											//echo "<br />1::" . $sSQL;
											//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones PERSONALIDAD [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] SQL: " . $sSQL;
											//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
											$conn->Execute($sSQL);

											//descSexo,descEdad,descFormacion,descNivel,descArea
											$sSQLUPDATE = "UPDATE export_personalidad ep, sexos s SET ep.descSexo=s.nombre WHERE ep.idSexo=s.idSexo AND s.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
											$conn->Execute($sSQLUPDATE);
											// 										echo "<br />" . $sSQLUPDATE;
											$sSQLUPDATE = "UPDATE export_personalidad ep, edades e SET ep.descEdad=e.nombre WHERE ep.idEdad=e.idEdad AND e.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
											$conn->Execute($sSQLUPDATE);
											// 										echo "<br />" . $sSQLUPDATE;
											$sSQLUPDATE = "UPDATE export_personalidad ep, formaciones f SET ep.descFormacion=f.nombre WHERE ep.idFormacion=f.idFormacion AND f.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
											$conn->Execute($sSQLUPDATE);
											//echo "<br />" . $sSQLUPDATE;
											$sSQLUPDATE = "UPDATE export_personalidad ep, nivelesjerarquicos n SET ep.descNivel=n.nombre WHERE ep.idNivel=n.idNivel AND n.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
											$conn->Execute($sSQLUPDATE);
											// 										echo "<br />" . $sSQLUPDATE;
											$sSQLUPDATE = "UPDATE export_personalidad ep, areas a SET ep.descArea=a.nombre WHERE ep.idArea=a.idArea AND a.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
											$conn->Execute($sSQLUPDATE);
											// 										echo "<br />" . $sSQLUPDATE;
											$sSQLUPDATE = "UPDATE export_personalidad ep, candidatos c SET ep.codIso2PaisProcedencia=c.codIso2PaisProcedencia WHERE ep.idEmpresa=c.idEmpresa AND ep.idProceso=c.idProceso AND ep.idCandidato=c.idCandidato AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
											$conn->Execute($sSQLUPDATE);
											// 										echo "<br />" . $sSQLUPDATE;
											//echo "<br />" . print_r($aSQLPuntuacionesPPL);
											if (isset($aSQLPuntuacionesPPL) && count($aSQLPuntuacionesPPL) > 0)
											{
												$sValidar = "DELETE FROM export_personalidad_laboral ";
												$sValidar .= " WHERE ";
												$sValidar .= " idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false);
												$sValidar .= " AND idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false);
												$sValidar .= " AND idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false);
												$sValidar .= " AND idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false);
												$sValidar .= " AND idBaremo=" . $conn->qstr($_POST["fIdBaremo"], false);
												$sValidar .= " AND idTipoInforme=" . $conn->qstr($_POST['fIdTipoInforme'], false);
												$rsValidar = $conn->Execute($sValidar);

												if ($rsValidar->recordCount() == 0){
													for ($c=0;$c < count($aSQLPuntuacionesPPL); $c++){
														$conn->Execute($aSQLPuntuacionesPPL[$c]);
														//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones PERSONALIDAD [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] SQL: " . $aSQLPuntuacionesPPL[$c];
														//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
														//sleep(1);
													}
												}
											}
											//echo "<br />" . print_r($aSQLPuntuacionesC);
											if (isset($aSQLPuntuacionesC) && count($aSQLPuntuacionesC) > 0)
											{
												$sValidar = "DELETE FROM export_personalidad_competencias ";
												$sValidar .= " WHERE ";
												$sValidar .= " idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false);
												$sValidar .= " AND idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false);
												$sValidar .= " AND idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false);
												$sValidar .= " AND idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false);
												$sValidar .= " AND idBaremo=" . $conn->qstr($_POST["fIdBaremo"], false);
												$sValidar .= " AND idTipoInforme=" . $conn->qstr($_POST['fIdTipoInforme'], false);
												$rsValidar = $conn->Execute($sValidar);

												if ($rsValidar->recordCount() == 0){
													for ($c=0;$c < count($aSQLPuntuacionesC); $c++){
														$conn->Execute($aSQLPuntuacionesC[$c]);
														//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones PERSONALIDAD [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] SQL: " . $aSQLPuntuacionesC[$c];
														//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
														//sleep(1);
													}
													$conn->Execute($sSQLIdioma);

												}
											}
										}
										echo "<br />He generado las puntuaciones, previamente las he borrado para esta prueba:: " . date('d/m/Y H:i:s:v');
									}else{
										$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] Sin calculos en informe: idEmpresa: " . $_POST['fIdEmpresa'] . " idProceso: " . $_POST['fIdProceso'] . " idCandidato: " . $_POST['fIdCandidato'] . " idPrueba: " . $_POST['fIdPrueba'];
										error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
									}
									break;
								default:
									//Poner switch por prueba PEDRO
									switch ($_POST['fIdPrueba'])
									{
										case 48:	//KPMG
										case 56:	//KPMG
										case 57:	//KPMG
										case 58:	//KPMG
										case 59:	//KPMG
										case 60:	//KPMG
											include('Template/Informes_candidato/generaPruebaKPMG.php');
											break;
										case 8:	//ELT
										case 73:	//REDACCION es
										case 74:	//REDACCION en
											include('Template/Informes_candidato/generaPrueba' . $_POST['fIdPrueba'] . '.php');
											break;
										case 32:	//CIP
											include('Template/Informes_candidato/generaTipo' . $idTipoInforme . '.php');

											//Para las de Personalidad

											if (isset($aSQLPuntuacionesPPL) || isset($aSQLPuntuacionesC))
											{

												//Se guardan los resultados calculados en respuestas pruebas

												$cRespPruebas = new Respuestas_pruebas();
												$cRespPruebasDB = new Respuestas_pruebasDB($conn);
												$cRespPruebas->setIdEmpresa($cCandidato->getIdEmpresa());
												$cRespPruebas->setIdProceso($cCandidato->getIdProceso());
												$cRespPruebas->setIdCandidato($cCandidato->getIdCandidato());
												$cRespPruebas->setIdPrueba($cPruebas->getIdPrueba());
												$cRespPruebas->setCodIdiomaIso2($cPruebas->getCodIdiomaIso2());
												$cRespPruebas = $cRespPruebasDB->readEntidad($cRespPruebas);

												$cProceso = new Procesos();
												$cProcesoDB	= new ProcesosDB($conn);
												$cProceso->setIdEmpresa($cCandidato->getIdEmpresa());
												$cProceso->setIdProceso($cCandidato->getIdProceso());
												$cProceso = $cProcesoDB->readEntidad($cProceso);

												//$_sBaremo = $cBaremos->getNombre();

												//Miramos primero si ya está guardado
												$sSQL = "SELECT * FROM export_personalidad ";
												$sSQL .= " WHERE ";
												$sSQL .= " idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false);
												$sSQL .= " AND idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false);
												$sSQL .= " AND idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false);
												$sSQL .= " AND idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false);
												$sSQL .= " AND idBaremo=" . $conn->qstr($_POST["fIdBaremo"], false);
												$sSQL .= " AND idTipoInforme=" . $conn->qstr($_POST['fIdTipoInforme'], false);

												$rsCuantos = $conn->Execute($sSQL);
												if ($rsCuantos->NumRows() <= 0)
												{

													$cobrado=0;
													if ($sLlamada=="Candidato"){
														$cobrado=1;
													}
													$sSQL = "INSERT INTO export_personalidad (idEmpresa, descEmpresa, idProceso, descProceso, idCandidato, nombre, apellido1, apellido2, email, dni, idPrueba, descPrueba, fecPrueba, idBaremo, descBaremo, idTipoInforme, descTipoInforme, fecAltaProceso, idSexo,idEdad, idFormacion, idNivel, idArea, cobrado, fecAlta, fecMod, usuAlta, usuMod) VALUES ";
													$sSQL .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getDescEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cCandidato->getNombre(), false) . "," . $conn->qstr($cCandidato->getApellido1(), false) . "," . $conn->qstr($cCandidato->getApellido2(), false) . "," . $conn->qstr($cCandidato->getMail(), false) . "," . $conn->qstr($cCandidato->getDni(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST["fIdBaremo"], false) . "," . $conn->qstr($_sBaremo, false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($sDescInforme, false) . "," . $conn->qstr($cProceso->getFecAlta(), false) . "," . $conn->qstr($cCandidato->getIdSexo(), false) . "," . $conn->qstr($cCandidato->getIdEdad(), false) . "," . $conn->qstr($cCandidato->getIdFormacion(), false) . "," . $conn->qstr($cCandidato->getIdNivel(), false) . "," . $conn->qstr($cCandidato->getIdArea(), false) . "," . $cobrado . ",now(),now()," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . ");";

													//echo "<br />2::" . $sSQL;
													//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones PERSONALIDAD [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] SQL: " . $sSQL;
													//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
													$conn->Execute($sSQL);

													//descSexo,descEdad,descFormacion,descNivel,descArea
													$sSQLUPDATE = "UPDATE export_personalidad ep, sexos s SET ep.descSexo=s.nombre WHERE ep.idSexo=s.idSexo AND s.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
													$conn->Execute($sSQLUPDATE);
													// 										echo "<br />" . $sSQLUPDATE;
													$sSQLUPDATE = "UPDATE export_personalidad ep, edades e SET ep.descEdad=e.nombre WHERE ep.idEdad=e.idEdad AND e.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
													$conn->Execute($sSQLUPDATE);
													// 										echo "<br />" . $sSQLUPDATE;
													$sSQLUPDATE = "UPDATE export_personalidad ep, formaciones f SET ep.descFormacion=f.nombre WHERE ep.idFormacion=f.idFormacion AND f.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
													$conn->Execute($sSQLUPDATE);
													//echo "<br />" . $sSQLUPDATE;
													$sSQLUPDATE = "UPDATE export_personalidad ep, nivelesjerarquicos n SET ep.descNivel=n.nombre WHERE ep.idNivel=n.idNivel AND n.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
													$conn->Execute($sSQLUPDATE);
													// 										echo "<br />" . $sSQLUPDATE;
													$sSQLUPDATE = "UPDATE export_personalidad ep, areas a SET ep.descArea=a.nombre WHERE ep.idArea=a.idArea AND a.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
													$conn->Execute($sSQLUPDATE);
													// 										echo "<br />" . $sSQLUPDATE;
													$sSQLUPDATE = "UPDATE export_personalidad ep, candidatos c SET ep.codIso2PaisProcedencia=c.codIso2PaisProcedencia WHERE ep.idEmpresa=c.idEmpresa AND ep.idProceso=c.idProceso AND ep.idCandidato=c.idCandidato AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
													$conn->Execute($sSQLUPDATE);
													// 										echo "<br />" . $sSQLUPDATE;
													//echo "<br />" . print_r($aSQLPuntuacionesPPL);
													if (isset($aSQLPuntuacionesPPL) && count($aSQLPuntuacionesPPL) > 0)
													{
														$sValidar = "SELECT * FROM export_personalidad_laboral ";
														$sValidar .= " WHERE ";
														$sValidar .= " idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false);
														$sValidar .= " AND idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false);
														$sValidar .= " AND idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false);
														$sValidar .= " AND idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false);
														$sValidar .= " AND idBaremo=" . $conn->qstr($_POST["fIdBaremo"], false);
														$sValidar .= " AND idTipoInforme=" . $conn->qstr($_POST['fIdTipoInforme'], false);
														$rsValidar = $conn->Execute($sValidar);

														if ($rsValidar->recordCount() == 0){
															for ($c=0;$c < count($aSQLPuntuacionesPPL); $c++){
																//echo "<br />" . $aSQLPuntuacionesPPL[$c];
																$conn->Execute($aSQLPuntuacionesPPL[$c]);
																//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones PERSONALIDAD [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] SQL: " . $aSQLPuntuacionesPPL[$c];
																//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
																//sleep(1);
															}
														}
													}
													if (isset($aSQLPuntuacionesC) && count($aSQLPuntuacionesC) > 0)
													{
														$sValidar = "SELECT * FROM export_personalidad_competencias ";
														$sValidar .= " WHERE ";
														$sValidar .= " idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false);
														$sValidar .= " AND idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false);
														$sValidar .= " AND idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false);
														$sValidar .= " AND idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false);
														$sValidar .= " AND idBaremo=" . $conn->qstr($_POST["fIdBaremo"], false);
														$sValidar .= " AND idTipoInforme=" . $conn->qstr($_POST['fIdTipoInforme'], false);
														$rsValidar = $conn->Execute($sValidar);

														if ($rsValidar->recordCount() == 0){
															for ($c=0;$c < count($aSQLPuntuacionesC); $c++){
																//echo "<br />" . $aSQLPuntuacionesC[$c];
																$conn->Execute($aSQLPuntuacionesC[$c]);
																//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones PERSONALIDAD [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] SQL: " . $aSQLPuntuacionesC[$c];
																//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
																//sleep(1);
															}
														}
													}
												}
											}else{
												$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] Sin calculos en informe: idEmpresa: " . $_POST['fIdEmpresa'] . " idProceso: " . $_POST['fIdProceso'] . " idCandidato: " . $_POST['fIdCandidato'] . " idPrueba: " . $_POST['fIdPrueba'];
												error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
											}


											break;
										case 42:	//SOP
											include('Template/Informes_candidato/generaPrueba' . $_POST['fIdPrueba'] . '.php');

											//Para las de Personalidad
											if (isset($aSQLPuntuacionesPPL) || isset($aSQLPuntuacionesC))
											{
												//Se guardan los resultados calculados en respuestas pruebas

												$cRespPruebas = new Respuestas_pruebas();
												$cRespPruebasDB = new Respuestas_pruebasDB($conn);
												$cRespPruebas->setIdEmpresa($cCandidato->getIdEmpresa());
												$cRespPruebas->setIdProceso($cCandidato->getIdProceso());
												$cRespPruebas->setIdCandidato($cCandidato->getIdCandidato());
												$cRespPruebas->setIdPrueba($cPruebas->getIdPrueba());
												$cRespPruebas->setCodIdiomaIso2($cPruebas->getCodIdiomaIso2());
												$cRespPruebas = $cRespPruebasDB->readEntidad($cRespPruebas);

												$cProceso = new Procesos();
												$cProcesoDB	= new ProcesosDB($conn);
												$cProceso->setIdEmpresa($cCandidato->getIdEmpresa());
												$cProceso->setIdProceso($cCandidato->getIdProceso());
												$cProceso = $cProcesoDB->readEntidad($cProceso);

												//$_sBaremo = $cBaremos->getNombre();

												//Miramos primero si ya está guardado
												$sSQL = "SELECT * FROM export_personalidad ";
												$sSQL .= " WHERE ";
												$sSQL .= " idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false);
												$sSQL .= " AND idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false);
												$sSQL .= " AND idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false);
												$sSQL .= " AND idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false);
												$sSQL .= " AND idBaremo=" . $conn->qstr($_POST["fIdBaremo"], false);
												$sSQL .= " AND idTipoInforme=" . $conn->qstr($_POST['fIdTipoInforme'], false);

												$rsCuantos = $conn->Execute($sSQL);
												if ($rsCuantos->NumRows() <= 0)
												{
													$cobrado=0;
													if ($sLlamada=="Candidato"){
														$cobrado=1;
													}
													$sSQL = "INSERT INTO export_personalidad (idEmpresa, descEmpresa, idProceso, descProceso, idCandidato, nombre, apellido1, apellido2, email, dni, idPrueba, descPrueba, fecPrueba, idBaremo, descBaremo, idTipoInforme, descTipoInforme, fecAltaProceso, idSexo,idEdad, idFormacion, idNivel, idArea, cobrado, fecAlta, fecMod, usuAlta, usuMod) VALUES ";
													$sSQL .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getDescEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cCandidato->getNombre(), false) . "," . $conn->qstr($cCandidato->getApellido1(), false) . "," . $conn->qstr($cCandidato->getApellido2(), false) . "," . $conn->qstr($cCandidato->getMail(), false) . "," . $conn->qstr($cCandidato->getDni(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST["fIdBaremo"], false) . "," . $conn->qstr($_sBaremo, false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($sDescInforme, false) . "," . $conn->qstr($cProceso->getFecAlta(), false) . "," . $conn->qstr($cCandidato->getIdSexo(), false) . "," . $conn->qstr($cCandidato->getIdEdad(), false) . "," . $conn->qstr($cCandidato->getIdFormacion(), false) . "," . $conn->qstr($cCandidato->getIdNivel(), false) . "," . $conn->qstr($cCandidato->getIdArea(), false) . "," . $cobrado . ",now(),now()," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . ");";

													//echo "<br />3::" . $sSQL;
													//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones PERSONALIDAD [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] SQL: " . $sSQL;
													//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
													$conn->Execute($sSQL);

													//descSexo,descEdad,descFormacion,descNivel,descArea
													$sSQLUPDATE = "UPDATE export_personalidad ep, sexos s SET ep.descSexo=s.nombre WHERE ep.idSexo=s.idSexo AND s.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
													$conn->Execute($sSQLUPDATE);
													// 										echo "<br />" . $sSQLUPDATE;
													$sSQLUPDATE = "UPDATE export_personalidad ep, edades e SET ep.descEdad=e.nombre WHERE ep.idEdad=e.idEdad AND e.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
													$conn->Execute($sSQLUPDATE);
													// 										echo "<br />" . $sSQLUPDATE;
													$sSQLUPDATE = "UPDATE export_personalidad ep, formaciones f SET ep.descFormacion=f.nombre WHERE ep.idFormacion=f.idFormacion AND f.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
													$conn->Execute($sSQLUPDATE);
													//echo "<br />" . $sSQLUPDATE;
													$sSQLUPDATE = "UPDATE export_personalidad ep, nivelesjerarquicos n SET ep.descNivel=n.nombre WHERE ep.idNivel=n.idNivel AND n.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
													$conn->Execute($sSQLUPDATE);
													// 										echo "<br />" . $sSQLUPDATE;
													$sSQLUPDATE = "UPDATE export_personalidad ep, areas a SET ep.descArea=a.nombre WHERE ep.idArea=a.idArea AND a.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
													$conn->Execute($sSQLUPDATE);
													// 										echo "<br />" . $sSQLUPDATE;
													$sSQLUPDATE = "UPDATE export_personalidad ep, candidatos c SET ep.codIso2PaisProcedencia=c.codIso2PaisProcedencia WHERE ep.idEmpresa=c.idEmpresa AND ep.idProceso=c.idProceso AND ep.idCandidato=c.idCandidato AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
													$conn->Execute($sSQLUPDATE);
													// 										echo "<br />" . $sSQLUPDATE;
													//echo "<br />" . print_r($aSQLPuntuacionesPPL);
													if (isset($aSQLPuntuacionesPPL) && count($aSQLPuntuacionesPPL) > 0)
													{
														$sValidar = "SELECT * FROM export_personalidad_laboral ";
														$sValidar .= " WHERE ";
														$sValidar .= " idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false);
														$sValidar .= " AND idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false);
														$sValidar .= " AND idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false);
														$sValidar .= " AND idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false);
														$sValidar .= " AND idBaremo=" . $conn->qstr($_POST["fIdBaremo"], false);
														$sValidar .= " AND idTipoInforme=" . $conn->qstr($_POST['fIdTipoInforme'], false);
														$rsValidar = $conn->Execute($sValidar);

														if ($rsValidar->recordCount() == 0){
															for ($c=0;$c < count($aSQLPuntuacionesPPL); $c++){
																//echo "<br />" . $aSQLPuntuacionesPPL[$c];
																$conn->Execute($aSQLPuntuacionesPPL[$c]);
																//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones PERSONALIDAD [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] SQL: " . $aSQLPuntuacionesPPL[$c];
																//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
																//sleep(1);
															}
														}
													}
													if (isset($aSQLPuntuacionesC) && count($aSQLPuntuacionesC) > 0)
													{
														$sValidar = "SELECT * FROM export_personalidad_competencias ";
														$sValidar .= " WHERE ";
														$sValidar .= " idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false);
														$sValidar .= " AND idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false);
														$sValidar .= " AND idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false);
														$sValidar .= " AND idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false);
														$sValidar .= " AND idBaremo=" . $conn->qstr($_POST["fIdBaremo"], false);
														$sValidar .= " AND idTipoInforme=" . $conn->qstr($_POST['fIdTipoInforme'], false);
														$rsValidar = $conn->Execute($sValidar);

														if ($rsValidar->recordCount() == 0){
															for ($c=0;$c < count($aSQLPuntuacionesC); $c++){
																//echo "<br />" . $aSQLPuntuacionesC[$c];
																$conn->Execute($aSQLPuntuacionesC[$c]);
																//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones PERSONALIDAD [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] SQL: " . $aSQLPuntuacionesC[$c];
																//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
																//sleep(1);
															}
														}
													}
												}
											}else{
												$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] Sin calculos en informe: idEmpresa: " . $_POST['fIdEmpresa'] . " idProceso: " . $_POST['fIdProceso'] . " idCandidato: " . $_POST['fIdCandidato'] . " idPrueba: " . $_POST['fIdPrueba'];
												error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
											}

											break;
										default:
											include('Template/Informes_candidato/generaTipo' . $idTipoInforme . '.php');
											break;
									} // end switch

									if (($cPruebas->getIdTipoPrueba() == 2 || $cPruebas->getIdTipoPrueba() == 5) )
									{
 										//Se guardan los resultados calculados en respuestas pruebas
 										$cRespPruebas = new Respuestas_pruebas();
 										$cRespPruebasDB = new Respuestas_pruebasDB($conn);
 										$cRespPruebas->setIdEmpresa($cCandidato->getIdEmpresa());
 										$cRespPruebas->setIdProceso($cCandidato->getIdProceso());
 										$cRespPruebas->setIdCandidato($cCandidato->getIdCandidato());
 										$cRespPruebas->setIdPrueba($cPruebas->getIdPrueba());
 										$cRespPruebas->setCodIdiomaIso2($cPruebas->getCodIdiomaIso2());
 										$cRespPruebas = $cRespPruebasDB->readEntidad($cRespPruebas);

 										$cProceso = new Procesos();
										$cProcesoDB	= new ProcesosDB($conn);
										$cProceso->setIdEmpresa($cCandidato->getIdEmpresa());
										$cProceso->setIdProceso($cCandidato->getIdProceso());
										$cProceso = $cProcesoDB->readEntidad($cProceso);

										//Miramos primero si ya está guardado
										$sSQL = "SELECT * FROM export_aptitudinales ";
										$sSQL .= " WHERE ";
										$sSQL .= " idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false);
										$sSQL .= " AND idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false);
										$sSQL .= " AND idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false);
										$sSQL .= " AND idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false);
										$sSQL .= " AND idBaremo=" . $conn->qstr($_POST["fIdBaremo"], false);

										$rsCuantos = $conn->Execute($sSQL);
										if ($rsCuantos->NumRows() <= 0)
										{
	 										$sRan_test = strip_tags($sRan_test);
											$sRan_test = trim($sRan_test,"\n\r");
											$sRan_test = html_entity_decode($sRan_test);

											//$_sBaremo = $cBaremos->getNombre();
											$cobrado=0;
											if ($sLlamada=="Candidato"){
												$cobrado=1;
											}
	 										$sSQL = "INSERT INTO export_aptitudinales (idEmpresa, descEmpresa, idProceso, descProceso, idCandidato, nombre, apellido1, apellido2, email, dni, idPrueba, descPrueba, fecPrueba, idBaremo, descBaremo, fecAltaProceso, correctas, contestadas,percentil, ir, ip, por, estilo, idSexo,idEdad, idFormacion, idNivel, idArea, cobrado, fecAlta, fecMod, usuAlta, usuMod) VALUES ";
	 										$sSQL .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getDescEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cCandidato->getNombre(), false) . "," . $conn->qstr($cCandidato->getApellido1(), false) . "," . $conn->qstr($cCandidato->getApellido2(), false) . "," . $conn->qstr($cCandidato->getMail(), false) . "," . $conn->qstr($cCandidato->getDni(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST["fIdBaremo"], false) . "," . $conn->qstr($_sBaremo, false) . "," . $conn->qstr($cProceso->getFecAlta(), false) . "," . $conn->qstr($iPDirecta, false) . "," . $conn->qstr($listaRespItems->recordCount(), false) . "," . $conn->qstr($iPercentil, false) . "," . $conn->qstr($IR, false) . "," . $conn->qstr($IP, false) . "," . $conn->qstr($POR, false) . "," . $conn->qstr($sRan_test, false) . "," . $conn->qstr($cCandidato->getIdSexo(), false) . "," . $conn->qstr($cCandidato->getIdEdad(), false) . "," . $conn->qstr($cCandidato->getIdFormacion(), false) . "," . $conn->qstr($cCandidato->getIdNivel(), false) . "," . $conn->qstr($cCandidato->getIdArea(), false) . "," . $cobrado . ",now(),now()," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . ");";
	 										$conn->Execute($sSQL);
	 										//echo "<br />2::" . $sSQL;
	 										//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] SQL: " . $sSQL;
	 										//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));


	 										//descSexo,descEdad,descFormacion,descNivel,descArea
	 										$sSQLUPDATE = "UPDATE export_aptitudinales ea, sexos s SET ea.descSexo=s.nombre WHERE ea.idSexo=s.idSexo AND s.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ea.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ea.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ea.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ea.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
	 										$conn->Execute($sSQLUPDATE);
	// 										echo "<br />" . $sSQLUPDATE;
	 										$sSQLUPDATE = "UPDATE export_aptitudinales ea, edades e SET ea.descEdad=e.nombre WHERE ea.idEdad=e.idEdad AND e.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ea.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ea.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ea.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ea.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
	 										$conn->Execute($sSQLUPDATE);
	// 										echo "<br />" . $sSQLUPDATE;
											$sSQLUPDATE = "UPDATE export_aptitudinales ea, formaciones f SET ea.descFormacion=f.nombre WHERE ea.idFormacion=f.idFormacion AND f.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ea.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ea.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ea.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ea.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
											$conn->Execute($sSQLUPDATE);
	 										//echo "<br />" . $sSQLUPDATE;
											$sSQLUPDATE = "UPDATE export_aptitudinales ea, nivelesjerarquicos n SET ea.descNivel=n.nombre WHERE ea.idNivel=n.idNivel AND n.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ea.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ea.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ea.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ea.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
											$conn->Execute($sSQLUPDATE);
	// 										echo "<br />" . $sSQLUPDATE;
	 										$sSQLUPDATE = "UPDATE export_aptitudinales ea, areas a SET ea.descArea=a.nombre WHERE ea.idArea=a.idArea AND a.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ea.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ea.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ea.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ea.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
	 										$conn->Execute($sSQLUPDATE);
	// 										echo "<br />" . $sSQLUPDATE;
	 										$sSQLUPDATE = "UPDATE export_aptitudinales ea, candidatos c SET ea.codIso2PaisProcedencia=c.codIso2PaisProcedencia WHERE ea.idEmpresa=c.idEmpresa AND ea.idProceso=c.idProceso AND ea.idCandidato=c.idCandidato AND ea.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ea.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ea.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ea.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
	 										$conn->Execute($sSQLUPDATE);
	 										//echo "<br />" . $sSQLUPDATE;
										}
 									}
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
							//No tiene respuestas, no se genera el informe.
							if (($cPruebas->getIdTipoPrueba() == 2 || $cPruebas->getIdTipoPrueba() == 5) )
							{
								$cRespPruebas = new Respuestas_pruebas();
								$cRespPruebasDB = new Respuestas_pruebasDB($conn);
								$cRespPruebas->setIdEmpresa($_POST['fIdEmpresa']);
								$cRespPruebas->setIdProceso($_POST['fIdProceso']);
								$cRespPruebas->setIdCandidato($_POST['fIdCandidato']);
								$cRespPruebas->setIdPrueba($_POST['fIdPrueba']);
								$cRespPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cRespPruebas = $cRespPruebasDB->readEntidad($cRespPruebas);

								$cProceso = new Procesos();
								$cProcesoDB	= new ProcesosDB($conn);
								$cProceso->setIdEmpresa($_POST['fIdEmpresa']);
								$cProceso->setIdProceso($_POST['fIdProceso']);
								$cProceso = $cProcesoDB->readEntidad($cProceso);

								//Miramos primero si ya está guardado
								$sSQL = "SELECT * FROM export_aptitudinales ";
								$sSQL .= " WHERE ";
								$sSQL .= " idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false);
								$sSQL .= " AND idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false);
								$sSQL .= " AND idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false);
								$sSQL .= " AND idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false);
								$sSQL .= " AND idBaremo=" . $conn->qstr($_POST["fIdBaremo"], false);
								//echo "<br />" . $sSQL;;
								$rsCuantos = $conn->Execute($sSQL);
								if ($rsCuantos->NumRows() <= 0)
								{
									$sRan_test = "";
									$iPDirecta="";
									$contestadas= 0;
									$iPercentil=NULL;
									$IR=NULL;
									$IP=NULL;
									$POR=NULL;

									$cobrado=0;
									if ($sLlamada=="Candidato"){
										$cobrado=1;
									}
									$sSQL = "INSERT INTO export_aptitudinales (idEmpresa, descEmpresa, idProceso, descProceso, idCandidato, nombre, apellido1, apellido2, email, dni, idPrueba, descPrueba, fecPrueba, idBaremo, descBaremo, fecAltaProceso, correctas, contestadas,percentil, ir, ip, por, estilo, idSexo,idEdad, idFormacion, idNivel, idArea, cobrado, fecAlta, fecMod, usuAlta, usuMod) VALUES ";
									$sSQL .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getDescEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cCandidato->getNombre(), false) . "," . $conn->qstr($cCandidato->getApellido1(), false) . "," . $conn->qstr($cCandidato->getApellido2(), false) . "," . $conn->qstr($cCandidato->getMail(), false) . "," . $conn->qstr($cCandidato->getDni(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST["fIdBaremo"], false) . "," . $conn->qstr($_sBaremo, false) . "," . $conn->qstr($cProceso->getFecAlta(), false) . "," . $conn->qstr($iPDirecta, false) . "," . $conn->qstr($contestadas, false) . "," . $conn->qstr($iPercentil, false) . "," . $conn->qstr($IR, false) . "," . $conn->qstr($IP, false) . "," . $conn->qstr($POR, false) . "," . $conn->qstr($sRan_test, false) . "," . $conn->qstr($cCandidato->getIdSexo(), false) . "," . $conn->qstr($cCandidato->getIdEdad(), false) . "," . $conn->qstr($cCandidato->getIdFormacion(), false) . "," . $conn->qstr($cCandidato->getIdNivel(), false) . "," . $conn->qstr($cCandidato->getIdArea(), false) . "," . $cobrado . ",now(),now()," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . ");";
									$conn->Execute($sSQL);
									//echo "<br />1::" . $sSQL;
									//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Guardando puntuaciones [" . $_SERVER['REMOTE_ADDR'] . "][" . $sLlamada . "][" . constant("MNT_EXPORTA") . "] SQL: " . $sSQL;
									//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));

									//descSexo,descEdad,descFormacion,descNivel,descArea
									$sSQLUPDATE = "UPDATE export_aptitudinales ea, sexos s SET ea.descSexo=s.nombre WHERE ea.idSexo=s.idSexo AND s.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ea.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ea.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ea.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ea.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
									$conn->Execute($sSQLUPDATE);
									// 										echo "<br />" . $sSQLUPDATE;
									$sSQLUPDATE = "UPDATE export_aptitudinales ea, edades e SET ea.descEdad=e.nombre WHERE ea.idEdad=e.idEdad AND e.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ea.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ea.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ea.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ea.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
									$conn->Execute($sSQLUPDATE);
									// 										echo "<br />" . $sSQLUPDATE;
									$sSQLUPDATE = "UPDATE export_aptitudinales ea, formaciones f SET ea.descFormacion=f.nombre WHERE ea.idFormacion=f.idFormacion AND f.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ea.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ea.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ea.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ea.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
									$conn->Execute($sSQLUPDATE);
									//echo "<br />" . $sSQLUPDATE;
									$sSQLUPDATE = "UPDATE export_aptitudinales ea, nivelesjerarquicos n SET ea.descNivel=n.nombre WHERE ea.idNivel=n.idNivel AND n.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ea.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ea.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ea.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ea.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
									$conn->Execute($sSQLUPDATE);
									// 										echo "<br />" . $sSQLUPDATE;
									$sSQLUPDATE = "UPDATE export_aptitudinales ea, areas a SET ea.descArea=a.nombre WHERE ea.idArea=a.idArea AND a.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ea.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ea.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ea.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ea.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
									$conn->Execute($sSQLUPDATE);
									// 										echo "<br />" . $sSQLUPDATE;
	 								$sSQLUPDATE = "UPDATE export_aptitudinales ea, candidatos c SET ea.codIso2PaisProcedencia=c.codIso2PaisProcedencia WHERE ea.idEmpresa=c.idEmpresa AND ea.idProceso=c.idProceso AND ea.idCandidato=c.idCandidato AND ea.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ea.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ea.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ea.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
	 								$conn->Execute($sSQLUPDATE);
									//echo "<br />" . $sSQLUPDATE;
								}
							}
							?>
							<script>
								escondeLoad();
							</script>
							<?php
							echo "<br />" . constant("MSG_SIN_RESPUESTAS_PARA_LA_PRUEBA");
						}
						//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Generado desde [" . $_SERVER['REMOTE_ADDR'] . "] [" . $sNombre . "][" . constant("MNT_EXPORTA") . "][" . $sLlamada . "]";
						//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}else{
						echo "<br>ELSE**" . $bPDFGenerado;
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

				$_sPrepago = "1";
				//Miramos si hay que descontar de la Matriz
				$sDescuentaMatriz = $cEmpresaDng->getDescuentaMatriz();
				$cMatrizDng = new Empresas();
				if (!empty($sDescuentaMatriz)){
					$cMatrizDng->setIdEmpresa($sDescuentaMatriz);
					$cMatrizDng = $cEmpresasDB->readEntidad($cMatrizDng);
					$_sPrepago = $cMatrizDng->getPrepago();
				}else{
					$_sPrepago = $cEmpresaDng->getPrepago();
				}

				$dTotalCoste=$cInformesPruebasTrf->getTarifa();

				$bDescargar=false;

				$cCandidato = new Candidatos();
				$cCandidato->setIdEmpresa($_POST['fIdEmpresa']);
				$cCandidato->setIdProceso($_POST['fIdProceso']);
				$cCandidato->setIdCandidato($_POST['fIdCandidato']);

				$cCandidato =  $cCandidatosDB->readEntidad($cCandidato);

				$cPruebas = new Pruebas();
				$cPruebas->setIdPrueba($_POST['fIdPrueba']);
				$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
				$cPruebas = $cPruebasDB->readEntidad($cPruebas);

				if ($_sPrepago == 0 || $_EmpresaLogada == constant("EMPRESA_PE") || $sLlamada == "Candidato"){
					//Descargamos siempre si la empresa logada es PE o NO es por prepago
					$bDescargar=true;
				}else{
					//Miramos si ya esiste el pdf, si existe el pdf es que ya fueron descontadas las unidades y no ha pasado un año
					//Con lo que no habria que descontar nada aunque no tenga unidades
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
					$_ficheroPDF = $spath . $sDirImg . $sNombre . ".html";

					if(is_file($_ficheroPDF)){
						$bDescargar=true;
					}else{
						if($cInformesPruebasTrf->getTarifa() <= $cEmpresaDng->getDongles()){
							$bDescargar=true;
						}else{
							$bDescargar=false;
						}
					}
				}
				$bPDFGenerado = false;	//Indica si el fichero fue generado previamente
				if($bDescargar)
				{

					//Miramos si ya esiste el pdf en caso que no esista lo generamos
//					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2']);
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
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

								$_sValor =  $cUtilidades->getValorCalculadoPRUEBAS($listaRespItems, $cOpcion, $conn);

								//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
								if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
									//echo $listaRespItems->fields['idItem'] . " - bien <br />";
									//Si es correcta, para este tipo de pruebas
									//Seteo a 1 el campo valor
//									$sSQLValor = "UPDATE respuestas_pruebas_items SET valor=1 ";
									$sSQLValor = "UPDATE respuestas_pruebas_items SET valor=" . $conn->qstr($_sValor, false);
									$sSQLValor .= " WHERE";
									$sSQLValor .= " idEmpresa='" . $_POST['fIdEmpresa'] . "'";
									$sSQLValor .= " AND idProceso='" . $_POST['fIdProceso'] . "'";
									$sSQLValor .= " AND idCandidato='" . $_POST['fIdCandidato'] . "'";
									$sSQLValor .= " AND codIdiomaIso2='" . $_POST['fCodIdiomaIso2Prueba'] . "'";
									$sSQLValor .= " AND idPrueba='" . $listaRespItems->fields['idPrueba'] . "'";
									$sSQLValor .= " AND idItem='" . $listaRespItems->fields['idItem'] . "'";
//									echo "<br />//B*****--->correcta:: " . $sSQLValor;
									$conn->Execute($sSQLValor);

									//Si coincide se le suma uno a la PDirecta.
									$iPDirecta++;
								}

								$listaRespItems->MoveNext();
							}

							$cBaremos_resultados = new Baremos_resultados();
							$cBaremos_resultados->setIdBaremo($_POST['fIdBaremo']);
							$cBaremos_resultados->setIdPrueba($_POST['fIdPrueba']);

							$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
//							echo "<br />B" . $sqlBaremosResultados . "<br />";
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

//							echo "pDirecta: " . $iPDirecta . "<br />";
//							echo "pPercentil: " . $iPercentil . "<br />";

							$cRespPruebas = new Respuestas_pruebas();
							$cRespPruebasDB = new Respuestas_pruebasDB($conn);
							$cRespPruebas->setIdEmpresa($cCandidato->getIdEmpresa());
							$cRespPruebas->setIdProceso($cCandidato->getIdProceso());
							$cRespPruebas->setIdCandidato($cCandidato->getIdCandidato());
							$cRespPruebas->setIdPrueba($cPruebas->getIdPrueba());
							$cRespPruebas->setCodIdiomaIso2($cPruebas->getCodIdiomaIso2());
							$cRespPruebas = $cRespPruebasDB->readEntidad($cRespPruebas);

							$cProceso = new Procesos();
							$cProcesoDB	= new ProcesosDB($conn);
							$cProceso->setIdEmpresa($cCandidato->getIdEmpresa());
							$cProceso->setIdProceso($cCandidato->getIdProceso());
							$cProceso = $cProcesoDB->readEntidad($cProceso);

							include('constantesInformes/' .	$_POST['fCodIdiomaIso2'] .'.php');
							switch ($cPruebas->getIdTipoPrueba())
							{
								case 6:	//Motivaviones
								case 7:	//Personalidad
								case 11:	//FLASH tipo Personalidad
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
									include('Template/Informes_candidato/generaPrueba' . $cPruebas->getIdPrueba() . '.php');
									break;
								default:
									//Poner switch por prueba PEDRO
									switch ($_POST['fIdPrueba'])
									{
										case 48:	//KPMG
										case 56:	//KPMG
										case 57:	//KPMG
										case 58:	//KPMG
										case 59:	//KPMG
										case 60:	//KPMG
											include('Template/Informes_candidato/generaPruebaKPMG.php');
											break;
										case 8:	//ELT
										case 73:	//REDACCION es
										case 74:	//REDACCION en
											include('Template/Informes_candidato/generaPrueba' . $_POST['fIdPrueba'] . '.php');
											break;
										case 32:	//CIP
											include('Template/Informes_candidato/generaTipo' . $idTipoInforme . '.php');
											break;
										case 42:	//SOP
											include('Template/Informes_candidato/generaPrueba' . $_POST['fIdPrueba'] . '.php');
											break;
										default:
											include('Template/Informes_candidato/generaTipo' . $idTipoInforme . '.php');
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
						//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " RE-Generado desde [" . $_SERVER['REMOTE_ADDR'] . "] [" . $sNombre . "][" . constant("MNT_EXPORTA_HTML") . "][" . $sLlamada . "]";
						//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
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

				$_sPrepago = "1";
				//Miramos si hay que descontar de la Matriz
				$sDescuentaMatriz = $cEmpresaDng->getDescuentaMatriz();
				$cMatrizDng = new Empresas();
				if (!empty($sDescuentaMatriz)){
					$cMatrizDng->setIdEmpresa($sDescuentaMatriz);
					$cMatrizDng = $cEmpresasDB->readEntidad($cMatrizDng);
					$_sPrepago = $cMatrizDng->getPrepago();
				}else{
					$_sPrepago = $cEmpresaDng->getPrepago();
				}

				$dTotalCoste=$cInformesPruebasTrf->getTarifa();

				$bDescargar=false;

				$cCandidato = new Candidatos();
				$cCandidato->setIdEmpresa($_POST['fIdEmpresa']);
				$cCandidato->setIdProceso($_POST['fIdProceso']);
				$cCandidato->setIdCandidato($_POST['fIdCandidato']);

				$cCandidato =  $cCandidatosDB->readEntidad($cCandidato);

				$cPruebas = new Pruebas();
				$cPruebas->setIdPrueba($_POST['fIdPrueba']);
				$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
				$cPruebas = $cPruebasDB->readEntidad($cPruebas);

				if ($_sPrepago == 0 || $_EmpresaLogada == constant("EMPRESA_PE") || $sLlamada == "Candidato"){
					//Descargamos siempre si la empresa logada es PE o NO es por prepago
					$bDescargar=true;
				}else{
					//Miramos si ya esiste el pdf, si existe el pdf es que ya fueron descontadas las unidades y no ha pasado un año
					//Con lo que no habria que descontar nada aunque no tenga unidades
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
					$_ficheroPDF = $spath . $sDirImg . $sNombre . ".rtf";

					if(is_file($_ficheroPDF)){
						$bDescargar=true;
					}else{
						if($cInformesPruebasTrf->getTarifa() <= $cEmpresaDng->getDongles()){
							$bDescargar=true;
						}else{
							$bDescargar=false;
						}
					}
				}
				$bPDFGenerado = false;	//Indica si el fichero fue generado previamente
				if($bDescargar)
				{
					//Miramos si ya esiste el pdf en caso que no esista lo generamos
//					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2']);
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
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

								$_sValor =  $cUtilidades->getValorCalculadoPRUEBAS($listaRespItems, $cOpcion, $conn);

								//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
								if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
									//echo $listaRespItems->fields['idItem'] . " - bien <br />";
									//Si es correcta, para este tipo de pruebas
									//Seteo a 1 el campo valor
//									$sSQLValor = "UPDATE respuestas_pruebas_items SET valor=1 ";
									$sSQLValor = "UPDATE respuestas_pruebas_items SET valor=" . $conn->qstr($_sValor, false);
									$sSQLValor .= " WHERE";
									$sSQLValor .= " idEmpresa='" . $_POST['fIdEmpresa'] . "'";
									$sSQLValor .= " AND idProceso='" . $_POST['fIdProceso'] . "'";
									$sSQLValor .= " AND idCandidato='" . $_POST['fIdCandidato'] . "'";
									$sSQLValor .= " AND codIdiomaIso2='" . $_POST['fCodIdiomaIso2Prueba'] . "'";
									$sSQLValor .= " AND idPrueba='" . $listaRespItems->fields['idPrueba'] . "'";
									$sSQLValor .= " AND idItem='" . $listaRespItems->fields['idItem'] . "'";
//									echo "<br />//C*****--->correcta:: " . $sSQLValor;
									$conn->Execute($sSQLValor);

									//Si coincide se le suma uno a la PDirecta.
									$iPDirecta++;
								}

								$listaRespItems->MoveNext();
							}

							$cBaremos_resultados = new Baremos_resultados();
							$cBaremos_resultados->setIdBaremo($_POST['fIdBaremo']);
							$cBaremos_resultados->setIdPrueba($_POST['fIdPrueba']);

							$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
//							echo "<br />C" . $sqlBaremosResultados . "<br />";
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

//							echo "pDirecta: " . $iPDirecta . "<br />";
//							echo "pPercentil: " . $iPercentil . "<br />";


							include('constantesInformes/' .	$_POST['fCodIdiomaIso2'] .'.php');
							switch ($cPruebas->getIdTipoPrueba())
							{
								case 6:	//Motivaviones
								case 7:	//Personalidad
								case 11:	//FLASH tipo Personalidad
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
									include('Template/Informes_candidato/generaPrueba' . $cPruebas->getIdPrueba() . '.php');
									break;
								default:
									//Poner switch por prueba PEDRO
									switch ($_POST['fIdPrueba'])
									{
										case 48:	//KPMG
										case 56:	//KPMG
										case 57:	//KPMG
										case 58:	//KPMG
										case 59:	//KPMG
										case 60:	//KPMG
											include('Template/Informes_candidato/generaPruebaKPMG.php');
											break;
										case 8:	//ELT
										case 73:	//REDACCION es
										case 74:	//REDACCION en
											include('Template/Informes_candidato/generaPrueba' . $_POST['fIdPrueba'] . '.php');
											break;
										case 32:	//CIP
											include('Template/Informes_candidato/generaTipo' . $idTipoInforme . '.php');
											break;
										case 42:	//SOP
											include('Template/Informes_candidato/generaPrueba' . $_POST['fIdPrueba'] . '.php');
											break;
										default:
											include('Template/Informes_candidato/generaTipo' . $idTipoInforme . '.php');
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
						//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Generado desde [" . $_SERVER['REMOTE_ADDR'] . "] [" . $sNombre . "][" . constant("MNT_EXPORTA_WORD") . "][" . $sLlamada . "]";
						//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
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

				$_sPrepago = "1";
				//Miramos si hay que descontar de la Matriz
				$sDescuentaMatriz = $cEmpresaDng->getDescuentaMatriz();
				$cMatrizDng = new Empresas();
				if (!empty($sDescuentaMatriz)){
					$cMatrizDng->setIdEmpresa($sDescuentaMatriz);
					$cMatrizDng = $cEmpresasDB->readEntidad($cMatrizDng);
					$_sPrepago = $cMatrizDng->getPrepago();
				}else{
					$_sPrepago = $cEmpresaDng->getPrepago();
				}

				$dTotalCoste=$cInformesPruebasTrf->getTarifa();

				$bDescargar=false;

				$cCandidato = new Candidatos();
				$cCandidato->setIdEmpresa($_POST['fIdEmpresa']);
				$cCandidato->setIdProceso($_POST['fIdProceso']);
				$cCandidato->setIdCandidato($_POST['fIdCandidato']);

				$cCandidato =  $cCandidatosDB->readEntidad($cCandidato);

				$cPruebas = new Pruebas();
				$cPruebas->setIdPrueba($_POST['fIdPrueba']);
				$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
				$cPruebas = $cPruebasDB->readEntidad($cPruebas);

				if ($_sPrepago == 0 || $_EmpresaLogada == constant("EMPRESA_PE") || $sLlamada == "Candidato"){
					//Descargamos siempre si la empresa logada es PE o NO es por prepago
					$bDescargar=true;
				}else{
					//Miramos si ya esiste el pdf, si existe el pdf es que ya fueron descontadas las unidades y no ha pasado un año
					//Con lo que no habria que descontar nada aunque no tenga unidades
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
					$_ficheroPDF = $spath . $sDirImg . $sNombre . ".pdf";

					if(is_file($_ficheroPDF)){
						$bDescargar=true;
					}else{
						if($cInformesPruebasTrf->getTarifa() <= $cEmpresaDng->getDongles()){
							$bDescargar=true;
						}else{
							$bDescargar=false;
						}
					}
				}
				$bPDFGenerado = false;	//Indica si el fichero fue generado previamente
				if($bDescargar)
				{

					//Miramos si ya esiste el pdf en caso que no esista lo generamos
//					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2']);
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
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

								$_sValor =  $cUtilidades->getValorCalculadoPRUEBAS($listaRespItems, $cOpcion, $conn);

								//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
								if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
									//echo $listaRespItems->fields['idItem'] . " - bien <br />";
									//Si es correcta, para este tipo de pruebas
									//Seteo a 1 el campo valor
//									$sSQLValor = "UPDATE respuestas_pruebas_items SET valor=1 ";
									$sSQLValor = "UPDATE respuestas_pruebas_items SET valor=" . $conn->qstr($_sValor, false);
									$sSQLValor .= " WHERE";
									$sSQLValor .= " idEmpresa='" . $_POST['fIdEmpresa'] . "'";
									$sSQLValor .= " AND idProceso='" . $_POST['fIdProceso'] . "'";
									$sSQLValor .= " AND idCandidato='" . $_POST['fIdCandidato'] . "'";
									$sSQLValor .= " AND codIdiomaIso2='" . $_POST['fCodIdiomaIso2Prueba'] . "'";
									$sSQLValor .= " AND idPrueba='" . $listaRespItems->fields['idPrueba'] . "'";
									$sSQLValor .= " AND idItem='" . $listaRespItems->fields['idItem'] . "'";
//									echo "<br />//D*****--->correcta:: " . $sSQLValor;
									$conn->Execute($sSQLValor);

									//Si coincide se le suma uno a la PDirecta.
									$iPDirecta++;
								}

								$listaRespItems->MoveNext();
							}

							$cBaremos_resultados = new Baremos_resultados();
							$cBaremos_resultados->setIdBaremo($_POST['fIdBaremo']);
							$cBaremos_resultados->setIdPrueba($_POST['fIdPrueba']);

							$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
//							echo "<br />D" . $sqlBaremosResultados . "<br />";
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
						//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Generado desde [" . $_SERVER['REMOTE_ADDR'] . "] [" . $sNombre . "][" . constant("MNT_DESCUENTA_DONGLES") . "][" . $sLlamada . "]";
						//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
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
					$sSQL = "UPDATE export_aptitudinales SET ";
					$sSQL .= " cobrado=1 ";
					$sSQL .= " WHERE ";
					$sSQL .= " idEmpresa=" . $conn->qstr($cCandidato->getIdEmpresa(), false);
					$sSQL .= " AND idProceso=" . $conn->qstr($cCandidato->getIdProceso(), false);
					$sSQL .= " AND idPrueba=" . $conn->qstr($cPruebas->getIdPrueba(), false);
					$sSQL .= " AND idCandidato=" . $conn->qstr($cCandidato->getIdCandidato(), false);
					$sSQL .= " AND idBaremo=" . $conn->qstr($_POST['fIdBaremo'], false);
					$conn->Execute($sSQL);
					$sSQL = "UPDATE export_personalidad SET ";
					$sSQL .= " cobrado=1 ";
					$sSQL .= " WHERE ";
					$sSQL .= " idEmpresa=" . $conn->qstr($cCandidato->getIdEmpresa(), false);
					$sSQL .= " AND idProceso=" . $conn->qstr($cCandidato->getIdProceso(), false);
					$sSQL .= " AND idPrueba=" . $conn->qstr($cPruebas->getIdPrueba(), false);
					$sSQL .= " AND idCandidato=" . $conn->qstr($cCandidato->getIdCandidato(), false);
					$sSQL .= " AND idBaremo=" . $conn->qstr($_POST['fIdBaremo'], false);
					$sSQL .= " AND idTipoInforme=" . $conn->qstr($cInformesPruebasTrf->getIdTipoInforme(), false);
					$conn->Execute($sSQL);
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

				$_sPrepago = "1";
				//Miramos si hay que descontar de la Matriz
				$sDescuentaMatriz = $cEmpresaDng->getDescuentaMatriz();
				$cMatrizDng = new Empresas();
				if (!empty($sDescuentaMatriz)){
					$cMatrizDng->setIdEmpresa($sDescuentaMatriz);
					$cMatrizDng = $cEmpresasDB->readEntidad($cMatrizDng);
					$_sPrepago = $cMatrizDng->getPrepago();
				}else{
					$_sPrepago = $cEmpresaDng->getPrepago();
				}

				$dTotalCoste=$cInformesPruebasTrf->getTarifa();

				$bDescargar=false;

				$cCandidato = new Candidatos();
				$cCandidato->setIdEmpresa($_POST['fIdEmpresa']);
				$cCandidato->setIdProceso($_POST['fIdProceso']);
				$cCandidato->setIdCandidato($_POST['fIdCandidato']);

				$cCandidato =  $cCandidatosDB->readEntidad($cCandidato);

				$cPruebas = new Pruebas();
				$cPruebas->setIdPrueba($_POST['fIdPrueba']);
				$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
				$cPruebas = $cPruebasDB->readEntidad($cPruebas);

				if ($_sPrepago == 0 || $_EmpresaLogada == constant("EMPRESA_PE") || $sLlamada == "Candidato"){
					//Descargamos siempre si la empresa logada es PE o NO es por prepago
					$bDescargar=true;
				}else{
					//Miramos si ya esiste el pdf, si existe el pdf es que ya fueron descontadas las unidades y no ha pasado un año
					//Con lo que no habria que descontar nada aunque no tenga unidades
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
					$_ficheroPDF = $spath . $sDirImg . $sNombre . ".html";

					if(is_file($_ficheroPDF)){
						$bDescargar=true;
					}else{
						if($cInformesPruebasTrf->getTarifa() <= $cEmpresaDng->getDongles()){
							$bDescargar=true;
						}else{
							$bDescargar=false;
						}
					}
				}
				$bPDFGenerado = false;	//Indica si el fichero fue generado previamente
				if($bDescargar)
				{
					//Miramos si ya esiste el pdf en caso que no esista lo generamos
//					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2']);
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
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

								$_sValor =  $cUtilidades->getValorCalculadoPRUEBAS($listaRespItems, $cOpcion, $conn);

								//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
								if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
									//echo $listaRespItems->fields['idItem'] . " - bien <br />";
									//Si es correcta, para este tipo de pruebas
									//Seteo a 1 el campo valor
//									$sSQLValor = "UPDATE respuestas_pruebas_items SET valor=1 ";
									$sSQLValor = "UPDATE respuestas_pruebas_items SET valor=" . $conn->qstr($_sValor, false);
									$sSQLValor .= " WHERE";
									$sSQLValor .= " idEmpresa='" . $_POST['fIdEmpresa'] . "'";
									$sSQLValor .= " AND idProceso='" . $_POST['fIdProceso'] . "'";
									$sSQLValor .= " AND idCandidato='" . $_POST['fIdCandidato'] . "'";
									$sSQLValor .= " AND codIdiomaIso2='" . $_POST['fCodIdiomaIso2Prueba'] . "'";
									$sSQLValor .= " AND idPrueba='" . $listaRespItems->fields['idPrueba'] . "'";
									$sSQLValor .= " AND idItem='" . $listaRespItems->fields['idItem'] . "'";
//									echo "<br />//E*****--->correcta:: " . $sSQLValor;
									$conn->Execute($sSQLValor);

									//Si coincide se le suma uno a la PDirecta.
									$iPDirecta++;
								}

								$listaRespItems->MoveNext();
							}

							$cBaremos_resultados = new Baremos_resultados();
							$cBaremos_resultados->setIdBaremo($_POST['fIdBaremo']);
							$cBaremos_resultados->setIdPrueba($_POST['fIdPrueba']);

							$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
//							echo "<br />E" . $sqlBaremosResultados . "<br />";
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
						//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Generado desde [" . $_SERVER['REMOTE_ADDR'] . "] [" . $sNombre . "][" . constant("MNT_DESCUENTA_DONGLES_HTML") . "][" . $sLlamada . "]";
						//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
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

				$_sPrepago = "1";
				//Miramos si hay que descontar de la Matriz
				$sDescuentaMatriz = $cEmpresaDng->getDescuentaMatriz();
				$cMatrizDng = new Empresas();
				if (!empty($sDescuentaMatriz)){
					$cMatrizDng->setIdEmpresa($sDescuentaMatriz);
					$cMatrizDng = $cEmpresasDB->readEntidad($cMatrizDng);
					$_sPrepago = $cMatrizDng->getPrepago();
				}else{
					$_sPrepago = $cEmpresaDng->getPrepago();
				}

				$dTotalCoste=$cInformesPruebasTrf->getTarifa();

				$bDescargar=false;

				$cCandidato = new Candidatos();
				$cCandidato->setIdEmpresa($_POST['fIdEmpresa']);
				$cCandidato->setIdProceso($_POST['fIdProceso']);
				$cCandidato->setIdCandidato($_POST['fIdCandidato']);

				$cCandidato =  $cCandidatosDB->readEntidad($cCandidato);

				$cPruebas = new Pruebas();
				$cPruebas->setIdPrueba($_POST['fIdPrueba']);
				$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
				$cPruebas = $cPruebasDB->readEntidad($cPruebas);

				if ($_sPrepago == 0 || $_EmpresaLogada == constant("EMPRESA_PE") || $sLlamada == "Candidato"){
					//Descargamos siempre si la empresa logada es PE o NO es por prepago
					$bDescargar=true;
				}else{
					//Miramos si ya esiste el pdf, si existe el pdf es que ya fueron descontadas las unidades y no ha pasado un año
					//Con lo que no habria que descontar nada aunque no tenga unidades
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
					$_ficheroPDF = $spath . $sDirImg . $sNombre . ".html";

					if(is_file($_ficheroPDF)){
						$bDescargar=true;
					}else{
						if($cInformesPruebasTrf->getTarifa() <= $cEmpresaDng->getDongles()){
							$bDescargar=true;
						}else{
							$bDescargar=false;
						}
					}
				}
				$bPDFGenerado = false;	//Indica si el fichero fue generado previamente
				if($bDescargar)
				{
					//Miramos si ya esiste el pdf en caso que no esista lo generamos
//					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2']);
					$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cCandidato->getNombre() . "_" . $cCandidato->getApellido1() . "_" . $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $_POST['fIdTipoInforme'] . "_" . $_POST['fCodIdiomaIso2'] . "_" . $_POST['fIdBaremo']);
					$sDirImg="imgInformes/";
					$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
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

								$_sValor =  $cUtilidades->getValorCalculadoPRUEBAS($listaRespItems, $cOpcion, $conn);

								//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
								if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
									//echo $listaRespItems->fields['idItem'] . " - bien <br />";
									//Si es correcta, para este tipo de pruebas
									//Seteo a 1 el campo valor
//									$sSQLValor = "UPDATE respuestas_pruebas_items SET valor=1 ";
									$sSQLValor = "UPDATE respuestas_pruebas_items SET valor=" . $conn->qstr($_sValor, false);
									$sSQLValor .= " WHERE";
									$sSQLValor .= " idEmpresa='" . $_POST['fIdEmpresa'] . "'";
									$sSQLValor .= " AND idProceso='" . $_POST['fIdProceso'] . "'";
									$sSQLValor .= " AND idCandidato='" . $_POST['fIdCandidato'] . "'";
									$sSQLValor .= " AND codIdiomaIso2='" . $_POST['fCodIdiomaIso2Prueba'] . "'";
									$sSQLValor .= " AND idPrueba='" . $listaRespItems->fields['idPrueba'] . "'";
									$sSQLValor .= " AND idItem='" . $listaRespItems->fields['idItem'] . "'";
//									echo "<br />//F*****--->correcta:: " . $sSQLValor;
									$conn->Execute($sSQLValor);

									//Si coincide se le suma uno a la PDirecta.
									$iPDirecta++;
								}

								$listaRespItems->MoveNext();
							}

							$cBaremos_resultados = new Baremos_resultados();
							$cBaremos_resultados->setIdBaremo($_POST['fIdBaremo']);
							$cBaremos_resultados->setIdPrueba($_POST['fIdPrueba']);

							$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
//							echo "<br />F" . $sqlBaremosResultados . "<br />";
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
						//$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Generado desde [" . $_SERVER['REMOTE_ADDR'] . "] [" . $sNombre . "][" . constant("MNT_DESCUENTA_DONGLES_WORD") . "][" . $sLlamada . "]";
						//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}else{
						//Si ya está generado sacamos el enlace
						//Miramos si hay que descontar y cuanro para informar al usuario.
						$dTotalCoste = getDescuentoDongles($cCandidato->getIdEmpresa(), $cCandidato->getIdProceso(), $cCandidato->getIdCandidato(), $cPruebas->getCodIdiomaIso2(), $cPruebas->getIdPrueba(), $cInformesPruebasTrf->getCodIdiomaIso2(), $cInformesPruebasTrf->getIdTipoInforme(), $_POST['fIdBaremo'], $dTotalCoste, true);
						$sMsgDescuento = sprintf(constant("MSG_DESCONTADOS_X_DONGLES"),$dTotalCoste);
						$sNombre .= ".rtf";
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
			include('Template/Informes_candidato/mntrespuestas_pruebas.php');
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
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getIdUsuario());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getIdUsuario());
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
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");

		$oConsumos = new Consumos();
		$oConsumosDB = new ConsumosDB($conn);

		$oConsumos->setIdEmpresa($idEmpresa);
		$oConsumos->setIdProceso($idProceso);
		$oConsumos->setIdCandidato($idCandidato);
		$oConsumos->setCodIdiomaIso2($codIdiomaIso2);
		$oConsumos->setIdPrueba($idPrueba);
		$oConsumos->setCodIdiomaInforme($codIdiomaIntorme);
		$oConsumos->setIdTipoInforme($idTipoInforme);
		$oConsumos->setIdBaremo($idBaremo);
		$oConsumos->setOrderBy("idConsumo");
		$oConsumos->setOrder("ASC");
		$sSQLConsumos = $oConsumosDB->readLista($oConsumos);
//		echo "<br />" . $sSQLConsumos;
		$rsConsumos = $conn->Execute($sSQLConsumos);
		if($rsConsumos->recordCount() == 0){
			//NO hemos encontrado ninguún registro, Hay que descontar, finalización de prueba del candidato.
			$iRetorno = $dTotalCoste;
		}else{
			if($rsConsumos->recordCount() == 1){
				//Hemos encontrado un registro, con lo que ya se le ha descontado
				//En la finalización de la prueba del candidato
				$iRetorno = 0;
			}else{
				//Miro si ha pasado un año desde que se genreró la priemra vez
				$iDias = diff_dte($rsConsumos->fields['fecAlta'], "now");
				//echo "<br />-->" . $iDias;
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
					//$oConsumos->setUnidades(1);
					//$oConsumos->setUnidadesHast(999999);
					$oConsumos->setOrderBy("idConsumo");
					$oConsumos->setOrder("DESC");
					$sSQLConsumos = $oConsumosDB->readLista($oConsumos);
					//echo "<br />**//-->" . $sSQLConsumos;
					$rsConsumos = $conn->Execute($sSQLConsumos);
					if($rsConsumos->recordCount() > 1){
						//Ya ha solicitado una regeneración, o ha descargado el informe, miro la fecha
						// de la última x eso está ordenado DESC
						$iDias = diff_dte($rsConsumos->fields['fecAlta'], "now");
						//echo "<br />**//-->" . $iDias;
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
//		echo "<br />bDescontar->" . $bDescontar;
		$cEmpresas = new Empresas();
		$cEmpresasDB = new EmpresasDB($conn);
		$cEmpresas->setIdEmpresa($idEmpresa);
		$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);

		if ($bDescontar){

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
				$sDescuentaMatriz = $cEmpresas->getDescuentaMatriz();
				if (!empty($sDescuentaMatriz)){
					//Consultamos los datos de la empresa a la que realmente se le descontará Matriz
					$cMatrizConsumo = new Empresas();
					$cMatrizConsumoDB = new EmpresasDB($conn);
					$cMatrizConsumo->setIdEmpresa($sDescuentaMatriz);
					$cMatrizConsumo = $cMatrizConsumoDB->readEntidad($cMatrizConsumo);
					$dResto= ($cMatrizConsumo->getDongles() - $dTotalCoste);
					$cMatrizConsumo->setDongles($dResto);
					$cMatrizConsumoDB->modificarSinPass($cMatrizConsumo);
					$sSQL = "UPDATE consumos SET descuentaMatriz=" . $sDescuentaMatriz . ", ";
					$sSQL .= "nomDescuentaMatriz=" . $conn->qstr($cMatrizConsumo->getNombre(), false) . " ";
					$sSQL .= "WHERE ";
					$sSQL .= "idEmpresa=" . $oConsumos->getIdEmpresa() . " AND ";
					$sSQL .= "idProceso=" . $oConsumos->getIdProceso() . " AND ";
					$sSQL .= "idCandidato=" . $oConsumos->getIdCandidato() . " AND ";
					$sSQL .= "codIdiomaIso2='" . $oConsumos->getCodIdiomaIso2() . "' AND ";
					$sSQL .= "idPrueba='" . $oConsumos->getIdPrueba() . "' AND ";
					$sSQL .= "codIdiomaInforme='" . $oConsumos->getCodIdiomaInforme() . "' AND ";
					$sSQL .= "idTipoInforme='" . $oConsumos->getIdTipoInforme() . "' ";
					$conn->Execute($sSQL);

					//$sTypeError=date('d/m/Y H:i:s') . " Descontadas [" . $dTotalCoste . "] unidades de la Matriz [" . $sDescuentaMatriz . "] : ";
					//error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				}else {
		    		//Lo descontamos de la empresa
		    		$dResto= ($cEmpresas->getDongles() - $dTotalCoste);
		    		$cEmpresas->setDongles($dResto);
		    		$cEmpresasDB->modificarSinPass($cEmpresas);
		    		$sSQL = "UPDATE consumos SET descuentaMatriz=" . $cEmpresas->getIdEmpresa() . ", ";
		    		$sSQL .= "nomDescuentaMatriz=" . $conn->qstr($cEmpresas->getNombre(), false) . " ";
		    		$sSQL .= "WHERE ";
		    		$sSQL .= "idEmpresa=" . $oConsumos->getIdEmpresa() . " AND ";
		    		$sSQL .= "idProceso=" . $oConsumos->getIdProceso() . " AND ";
		    		$sSQL .= "idCandidato=" . $oConsumos->getIdCandidato() . " AND ";
		    		$sSQL .= "codIdiomaIso2='" . $oConsumos->getCodIdiomaIso2() . "' AND ";
		    		$sSQL .= "idPrueba='" . $oConsumos->getIdPrueba() . "' AND ";
		    		$sSQL .= "codIdiomaInforme='" . $oConsumos->getCodIdiomaInforme() . "' AND ";
		    		$sSQL .= "idTipoInforme='" . $oConsumos->getIdTipoInforme() . "' ";
		    		$conn->Execute($sSQL);

				}
			}
		}
		//Actualizamos siempre la empresa de descuento
		$sDescuentaMatriz = $cEmpresas->getDescuentaMatriz();
		if (!empty($sDescuentaMatriz)){
			$cMatrizConsumo = new Empresas();
			$cMatrizConsumoDB = new EmpresasDB($conn);
			$cMatrizConsumo->setIdEmpresa($sDescuentaMatriz);
			$cMatrizConsumo = $cMatrizConsumoDB->readEntidad($cMatrizConsumo);
			//Consultamos los datos de la empresa a la que realmente se le descontará Matriz
			$sSQL = "UPDATE consumos SET descuentaMatriz=" . $sDescuentaMatriz . ", ";
			$sSQL .= "nomDescuentaMatriz=" . $conn->qstr($cMatrizConsumo->getNombre(), false) . " ";
			$sSQL .= "WHERE ";
			$sSQL .= "idEmpresa=" . $oConsumos->getIdEmpresa() . " AND ";
			$sSQL .= "idProceso=" . $oConsumos->getIdProceso() . " AND ";
			$sSQL .= "idCandidato=" . $oConsumos->getIdCandidato() . " AND ";
			$sSQL .= "codIdiomaIso2='" . $oConsumos->getCodIdiomaIso2() . "' AND ";
			$sSQL .= "idPrueba='" . $oConsumos->getIdPrueba() . "' AND ";
			$sSQL .= "codIdiomaInforme='" . $oConsumos->getCodIdiomaInforme() . "' AND ";
			$sSQL .= "idTipoInforme='" . $oConsumos->getIdTipoInforme() . "' ";
			$conn->Execute($sSQL);
		}else {
			$sSQL = "UPDATE consumos SET descuentaMatriz=" . $cEmpresas->getIdEmpresa() . ", ";
			$sSQL .= "nomDescuentaMatriz=" . $conn->qstr($cEmpresas->getNombre(), false) . " ";
			$sSQL .= "WHERE ";
			$sSQL .= "idEmpresa=" . $oConsumos->getIdEmpresa() . " AND ";
			$sSQL .= "idProceso=" . $oConsumos->getIdProceso() . " AND ";
			$sSQL .= "idCandidato=" . $oConsumos->getIdCandidato() . " AND ";
			$sSQL .= "codIdiomaIso2='" . $oConsumos->getCodIdiomaIso2() . "' AND ";
			$sSQL .= "idPrueba='" . $oConsumos->getIdPrueba() . "' AND ";
			$sSQL .= "codIdiomaInforme='" . $oConsumos->getCodIdiomaInforme() . "' AND ";
			$sSQL .= "idTipoInforme='" . $oConsumos->getIdTipoInforme() . "' ";
			$conn->Execute($sSQL);
		}

		//Actualizamos los ids y las Descripciones de la tabla de consumos
		$sSQLUPDATE = "UPDATE consumos c, candidatos ca SET c.idSexo=ca.idSexo,c.idEdad=ca.idEdad,c.idFormacion=ca.idFormacion,c.idNivel=ca.idNivel,c.idArea=ca.idArea WHERE ca.idEmpresa=" . $conn->qstr($oConsumos->getIdEmpresa(), false) . " AND ca.idProceso=" . $conn->qstr($oConsumos->getIdProceso(), false) . " AND ca.idCandidato=" . $conn->qstr($oConsumos->getIdCandidato(), false) . " AND c.idEmpresa=ca.idEmpresa  AND c.idProceso=ca.idProceso AND c.idCandidato=ca.idCandidato;";
		$conn->Execute($sSQLUPDATE);
		//echo "<br />" . $sSQLUPDATE;
		$sSQLUPDATE = "UPDATE consumos c, sexos s SET c.descSexo=s.nombre WHERE c.idSexo=s.idSexo AND s.codIdiomaIso2=" . $conn->qstr($oConsumos->getCodIdiomaIso2(), false) . " AND c.idEmpresa=" . $conn->qstr($oConsumos->getIdEmpresa(), false) . " AND c.idProceso=" . $conn->qstr($oConsumos->getIdProceso(), false) . " AND c.idCandidato=" . $conn->qstr($oConsumos->getIdCandidato(), false) . " AND c.idPrueba=" . $conn->qstr($oConsumos->getIdPrueba(), false) . ";";
		$conn->Execute($sSQLUPDATE);
		//echo "<br />" . $sSQLUPDATE;
		$sSQLUPDATE = "UPDATE consumos c, edades e SET c.descEdad=e.nombre WHERE c.idEdad=e.idEdad AND e.codIdiomaIso2=" . $conn->qstr($oConsumos->getCodIdiomaIso2(), false) . " AND c.idEmpresa=" . $conn->qstr($oConsumos->getIdEmpresa(), false) . " AND c.idProceso=" . $conn->qstr($oConsumos->getIdProceso(), false) . " AND c.idCandidato=" . $conn->qstr($oConsumos->getIdCandidato(), false) . " AND c.idPrueba=" . $conn->qstr($oConsumos->getIdPrueba(), false) . ";";
		$conn->Execute($sSQLUPDATE);
		//echo "<br />" . $sSQLUPDATE;
		$sSQLUPDATE = "UPDATE consumos c, formaciones f SET c.descFormacion=f.nombre WHERE c.idFormacion=f.idFormacion AND f.codIdiomaIso2=" . $conn->qstr($oConsumos->getCodIdiomaIso2(), false) . " AND c.idEmpresa=" . $conn->qstr($oConsumos->getIdEmpresa(), false) . " AND c.idProceso=" . $conn->qstr($oConsumos->getIdProceso(), false) . " AND c.idCandidato=" . $conn->qstr($oConsumos->getIdCandidato(), false) . " AND c.idPrueba=" . $conn->qstr($oConsumos->getIdPrueba(), false) . ";";
		$conn->Execute($sSQLUPDATE);
		//echo "<br />" . $sSQLUPDATE;
		$sSQLUPDATE = "UPDATE consumos c, nivelesjerarquicos n SET c.descNivel=n.nombre WHERE c.idNivel=n.idNivel AND n.codIdiomaIso2=" . $conn->qstr($oConsumos->getCodIdiomaIso2(), false) . " AND c.idEmpresa=" . $conn->qstr($oConsumos->getIdEmpresa(), false) . " AND c.idProceso=" . $conn->qstr($oConsumos->getIdProceso(), false) . " AND c.idCandidato=" . $conn->qstr($oConsumos->getIdCandidato(), false) . " AND c.idPrueba=" . $conn->qstr($oConsumos->getIdPrueba(), false) . ";";
		$conn->Execute($sSQLUPDATE);
		//echo "<br />" . $sSQLUPDATE;
		$sSQLUPDATE = "UPDATE consumos c, areas a SET c.descArea=a.nombre WHERE c.idArea=a.idArea AND a.codIdiomaIso2=" . $conn->qstr($oConsumos->getCodIdiomaIso2(), false) . " AND c.idEmpresa=" . $conn->qstr($oConsumos->getIdEmpresa(), false) . " AND c.idProceso=" . $conn->qstr($oConsumos->getIdProceso(), false) . " AND c.idCandidato=" . $conn->qstr($oConsumos->getIdCandidato(), false) . " AND c.idPrueba=" . $conn->qstr($oConsumos->getIdPrueba(), false) . ";";
		$conn->Execute($sSQLUPDATE);
		//echo "<br />" . $sSQLUPDATE;
		$sSQLUPDATE = "UPDATE consumos c, candidatos ca SET c.codIso2PaisProcedencia=ca.codIso2PaisProcedencia WHERE c.idEmpresa=ca.idEmpresa AND c.idProceso=ca.idProceso AND c.idCandidato=ca.idCandidato AND c.idEmpresa=" . $conn->qstr($oConsumos->getIdEmpresa(), false) . " AND c.idProceso=" . $conn->qstr($oConsumos->getIdProceso(), false) . " AND c.idCandidato=" . $conn->qstr($oConsumos->getIdCandidato(), false) . " AND c.idPrueba=" . $conn->qstr($oConsumos->getIdPrueba(), false) . ";";
		$conn->Execute($sSQLUPDATE);
		// 										echo "<br />" . $sSQLUPDATE;
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
