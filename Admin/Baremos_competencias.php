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
	require_once(constant("DIR_WS_COM") . "Baremos_competencias/Baremos_competenciasDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos_competencias/Baremos_competencias.php");
	require_once(constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
	require_once(constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
	require_once(constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
	require_once(constant("DIR_WS_COM") . "Competencias/Competencias.php");
	require_once(constant("DIR_WS_COM") . "Competencias_items/Competencias_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Competencias_items/Competencias_items.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
		
include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
	$cUtilidades	= new Utilidades();
	
	$cPruebasDB	= new PruebasDB($conn);  // Entidad DB
	
	$cEntidadDB	= new Baremos_competenciasDB($conn);  // Entidad DB
	$cEntidad	= new Baremos_competencias();  // Entidad
	
	$cCompetenciasItemsDB = new Competencias_itemsDB($conn);
	$cTipos_competenciasDB = new Tipos_competenciasDB($conn);
	
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	
	$comboPRUEBASGROUP	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","",constant("SLC_OPCION"),"","","","idprueba");
	$comboPRUEBAS	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","",constant("SLC_OPCION"),"","","");
	$comboWI_USUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","");
	
	
	//echo('modo:' . $_POST['MODO']);
	
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
			$newId	= $cEntidadDB->insertar($cEntidad);
			if (!empty($newId)){
			?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
				{
					if (!empty($_POST['baremos_next_page']) && $_POST['baremos_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}
					$pager = new ADODB_Pager($conn,$sql,'baremos');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Baremos_competencias/mntbaremosl.php');
				}else{
					$cEntidad	= new Baremos_competencias();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Baremos_competencias/mntbaremosa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Baremos_competencias/mntbaremosa.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			if ($cEntidadDB->modificar($cEntidad))
			{
				if (!empty($_POST['baremos_next_page']) && $_POST['baremos_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}
				$pager = new ADODB_Pager($conn,$sql,'baremos');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Baremos_competencias/mntbaremosl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Baremos_competencias/mntbaremosa.php');
			}
			break;
		case constant("MNT_BORRAR"):
			$cEntidad = readEntidad($cEntidad);
			if (!$cEntidadDB->borrar($cEntidad))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
			}
			if (!empty($_POST['baremos_next_page']) && $_POST['baremos_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}
			$pager = new ADODB_Pager($conn,$sql,'baremos');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Baremos_competencias/mntbaremosl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Baremos_competencias/mntbaremosa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidadCompleta($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Baremos_competencias/mntbaremosa.php');
			break;
		case constant("MNT_BUSCAR"):
//			echo "idPrueba: " . $_POST['fIdPrueba'];
			$cPruebas	= new Pruebas();  // Entidad DB
			$sqlPruebasGroup = $cPruebasDB->readListaGroup($cPruebas);
			$listaPruebasGroup = $conn->Execute($sqlPruebasGroup);
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			$sIdPrueba = (isset($_POST['fIdPrueba'])) ? $_POST['fIdPrueba'] : "";
			
			$cEntidad->setIdPrueba($sIdPrueba);
			include('Template/Baremos_competencias/mntbaremos.php');
			break;
		case constant("MNT_LISTAR"):
			$cEntidad = readLista($cEntidad);
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readLista($cEntidad);
			}else{
				if (!empty($_POST['baremos_next_page']) && $_POST['baremos_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readLista($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'baremos');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Baremos_competencias/mntbaremosl.php');
			break;
			
		case constant("MNT_ANIADEBAREMO"):
			include('Template/Baremos_competencias/aniadebaremo.php');
			break;
			
		case constant("MNT_LISTABAREMOS"):
			if(isset($_POST['fAniade']) && $_POST['fAniade']!=""){
				$cEntidad = readEntidad($cEntidad);
				$newId = $cEntidadDB->insertar($cEntidad);
				?><script>cierraaniade();</script><?php 
			}
			if(isset($_POST['fBorra']) && $_POST['fBorra']!=""){
				$cEntidad = readEntidad($cEntidad);
				$cEntidadDB->borrar($cEntidad);
			}
			$cEntidad = new Baremos_competencias();
			$cEntidad->setIdPrueba($_POST['fIdPrueba']);
			if(isset($_POST['fIdCompetencia']) && isset($_POST['fIdTipoCompetencia'])){
				$cEntidad->setIdCompetencia($_POST['fIdCompetencia']);
				$cEntidad->setIdCompetenciaHast($_POST['fIdCompetencia']);
				$cEntidad->setIdTipoCompetencia($_POST['fIdTipoCompetencia']);
				$cEntidad->setIdTipoCompetenciaHast($_POST['fIdTipoCompetencia']);
			}
			$sqlBaremos= $cEntidadDB->readLista($cEntidad);
			//echo $sqlBaremos . "<br />";
			$listaBaremos = $conn->Execute($sqlBaremos);
			
			include('Template/Baremos_competencias/listabaremos.php');
			break;
			
		case constant("MNT_COMPRUEBAESCALAS"):
			
			$cCompetenciasItems = new Competencias_items();
			$cCompetenciasItems->setIdPrueba($_POST['fIdPrueba']);
			$cCompetenciasItems->setOrderBy("idTipoCompetencia");
			$cCompetenciasItems->setOrder("ASC");
			$sql = $cCompetenciasItemsDB->readListaGroupTipo($cCompetenciasItems);
			$lista = $conn->Execute($sql);
				
			include('Template/Baremos_competencias/competencias.php');
			
			
			break;
			
		case constant("MNT_LISTAPUNTBAREMOS"):
			require_once(constant("DIR_WS_COM") . "Baremos_resultados_competencias/Baremos_resultados_competenciasDB.php");
			require_once(constant("DIR_WS_COM") . "Baremos_resultados_competencias/Baremos_resultados_competencias.php");	
			$cBarRes = new Baremos_resultados_competencias();
			$cBarResDB = new Baremos_resultados_competenciasDB($conn);
			
			if(isset($_POST['fAniade']) && $_POST['fAniade']!=""){
				$cBarRes->setIdBaremo((isset($_POST["fIdBaremo"])) ? $_POST["fIdBaremo"] : "");
				$cBarRes->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
				$cBarRes->setIdTipoCompetencia((isset($_POST["fIdTipoCompetencia"])) ? $_POST["fIdTipoCompetencia"] : "0");
				$cBarRes->setIdCompetencia((isset($_POST["fIdCompetencia"])) ? $_POST["fIdCompetencia"] : "0");
				$cBarRes->setPuntMin((isset($_POST["fPuntMinima"])) ? $_POST["fPuntMinima"] : "");
				$cBarRes->setPuntMax((isset($_POST["fPuntMaxima"])) ? $_POST["fPuntMaxima"] : "");
				$cBarRes->setPuntBaremada((isset($_POST["fPuntBaremada"])) ? $_POST["fPuntBaremada"] : "");
				$newIdRes = $cBarResDB->insertar($cBarRes);
			}
			if(isset($_POST['fBorra']) && $_POST['fBorra']!=""){
				$cBarRes->setIdResultado((isset($_POST["fIdResultado"])) ? $_POST["fIdResultado"] : "");
				$cBarResDB->borrar($cBarRes);
			}

			$cBarRes1 = new Baremos_resultados_competencias();
			$cBarRes1->setOrderBy('puntMin');
			$cBarRes1->setOrder('ASC');	
			$cBarRes1->setIdBaremo((isset($_POST["fIdBaremo"])) ? $_POST["fIdBaremo"] : "");
			$cBarRes1->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
			$cBarRes1->setIdTipoCompetencia((isset($_POST["fIdTipoCompetencia"])) ? $_POST["fIdTipoCompetencia"] : "0");
			$cBarRes1->setIdCompetencia((isset($_POST["fIdCompetencia"])) ? $_POST["fIdCompetencia"] : "0");
			$sqlRes = $cBarResDB->readLista($cBarRes1);
			$listaResultados = $conn->Execute($sqlRes);
			
			include('Template/Baremos_competencias/listapuntuaciones.php');
			break;	
		default:
			$cPruebas	= new Pruebas();  // Entidad DB
			$sqlPruebasGroup = $cPruebasDB->readListaGroup($cPruebas);
			$listaPruebasGroup = $conn->Execute($sqlPruebasGroup);
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Baremos_competencias/mntbaremos.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdBaremo((isset($_POST["fIdBaremo"])) ? $_POST["fIdBaremo"] : "");
		$cEntidad->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
		$cEntidad->setIdTipoCompetencia((isset($_POST["fIdTipoCompetencia"])) ? $_POST["fIdTipoCompetencia"] : "0");
		$cEntidad->setIdCompetencia((isset($_POST["fIdCompetencia"])) ? $_POST["fIdCompetencia"] : "0");
		$cEntidad->setNombre((isset($_POST["fNombre"])) ? $_POST["fNombre"] : "");
		$cEntidad->setDescripcion((isset($_POST["fDescripcion"])) ? $_POST["fDescripcion"] : "");
		$cEntidad->setObservaciones((isset($_POST["fObservaciones"])) ? $_POST["fObservaciones"] : "");
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
		$cEntidad->setIdBaremo((isset($_POST["LSTIdBaremo"]) && $_POST["LSTIdBaremo"] != "") ? $_POST["LSTIdBaremo"] : "");	$cEntidad->setBusqueda(constant("STR_BAREMO"), (isset($_POST["LSTIdBaremo"]) && $_POST["LSTIdBaremo"] != "" ) ? $_POST["LSTIdBaremo"] : "");
		$cEntidad->setIdBaremoHast((isset($_POST["LSTIdBaremoHast"]) && $_POST["LSTIdBaremoHast"] != "") ? $_POST["LSTIdBaremoHast"] : "");	$cEntidad->setBusqueda(constant("STR_BAREMO") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdBaremoHast"]) && $_POST["LSTIdBaremoHast"] != "" ) ? $_POST["LSTIdBaremoHast"] : "");
		global $comboPRUEBAS;
		$cEntidad->setIdPrueba((isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "") ? $_POST["LSTIdPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_ID_PRUEBA"), (isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "" ) ? $comboPRUEBAS->getDescripcionCombo($_POST["LSTIdPrueba"]) : "");
		$cEntidad->setNombre((isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "") ? $_POST["LSTNombre"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE"), (isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "" ) ? $_POST["LSTNombre"] : "");
		$cEntidad->setDescripcion((isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "") ? $_POST["LSTDescripcion"] : "");	$cEntidad->setBusqueda(constant("STR_DESCRIPCION"), (isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "" ) ? $_POST["LSTDescripcion"] : "");
		$cEntidad->setObservaciones((isset($_POST["LSTObservaciones"]) && $_POST["LSTObservaciones"] != "") ? $_POST["LSTObservaciones"] : "");	$cEntidad->setBusqueda(constant("STR_OBSERVACIONES"), (isset($_POST["LSTObservaciones"]) && $_POST["LSTObservaciones"] != "" ) ? $_POST["LSTObservaciones"] : "");
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

?>