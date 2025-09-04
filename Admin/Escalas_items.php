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
	require_once(constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
	require_once(constant("DIR_WS_COM") . "Items/ItemsDB.php");
	require_once(constant("DIR_WS_COM") . "Items/Items.php");
	
include_once ('include/conexion.php');
	
	$cUtilidades	= new Utilidades();
	
	$cEntidadDB	= new Escalas_itemsDB($conn);  // Entidad DB
	$cEntidad	= new Escalas_items();  // Entidad
	
	$cItemsDB	= new ItemsDB($conn);  // Items DB
	$cItems	= new Items();  // Items
	
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	
	$comboBLOQUES	= new Combo($conn,"fIdBloque","idBloque","nombre","Descripcion","bloques","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboESCALAS	= new Combo($conn,"fIdEscala","idBloque","nombre","Descripcion","escalas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboPRUEBAS	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboITEMS	= new Combo($conn,"fIdItem","idItem","descripcion","Descripcion","items","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboWI_USUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","fecMod");
	
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
					if (!empty($_POST['escalas_items_next_page']) && $_POST['escalas_items_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}
					$pager = new ADODB_Pager($conn,$sql,'escalas_items');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Escalas_items/mntescalas_itemsl.php');
				}else{
					$cEntidad	= new Escalas_items();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Escalas_items/mntescalas_itemsa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Escalas_items/mntescalas_itemsa.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			if ($cEntidadDB->modificar($cEntidad))
			{
				if (!empty($_POST['escalas_items_next_page']) && $_POST['escalas_items_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}
				$pager = new ADODB_Pager($conn,$sql,'escalas_items');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Escalas_items/mntescalas_itemsl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Escalas_items/mntescalas_itemsa.php');
			}
			break;
		case constant("MNT_BORRAR"):
			$cEntidad = readEntidad($cEntidad);
			if (!$cEntidadDB->borrar($cEntidad))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
			}
					if (!empty($_POST['escalas_items_next_page']) && $_POST['escalas_items_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}
			$pager = new ADODB_Pager($conn,$sql,'escalas_items');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Escalas_items/mntescalas_itemsl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Escalas_items/mntescalas_itemsa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Escalas_items/mntescalas_itemsa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Escalas_items/mntescalas_items.php');
			break;
		case constant("MNT_LISTAR"):
			$cEntidad = readLista($cEntidad);
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readLista($cEntidad);
			}else{
				if (!empty($_POST['escalas_items_next_page']) && $_POST['escalas_items_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readLista($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'escalas_items');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Escalas_items/mntescalas_itemsl.php');
			break;
		case constant("MNT_CARGAITEMS"):
			$bPinta=false;

			if(isset($_POST['idPrueba']) && $_POST['idPrueba'] !="" && isset($_POST['idEscala']) && $_POST['idEscala'] !="" && isset($_POST['idBloque']) && $_POST['idBloque'] !=""){
				$cItems = new Items();
				$cItems->setIdPrueba($_POST['idPrueba']);
				$cItems->setIdPruebaHast($_POST['idPrueba']);
				$cItems->setCodIdiomaIso2($sLang);
				$cItems->setOrderBy("orden");
				$cItems->setOrder("ASC");
				$sql = $cItemsDB->listaItemsNoAsignados($cItems);
//				echo "<br />" . $sql;
				$listaItems = $conn->Execute($sql);
				
				$cEntidad->setIdPrueba($_POST['idPrueba']);
				$cEntidad->setIdPruebaHast($_POST['idPrueba']);
				$cEntidad->setIdBloque($_POST['idBloque']);
				$cEntidad->setIdBloqueHast($_POST['idBloque']);
				$cEntidad->setIdEscala($_POST['idEscala']);
				$cEntidad->setIdEscalaHast($_POST['idEscala']);
				$cEntidad->setOrderBy("idItem");
				$cEntidad->setOrder("ASC");
				$sqlEscalaItems = $cEntidadDB->readLista($cEntidad);
				$listaEscalaItems = $conn->Execute($sqlEscalaItems);
				$bPinta=true;
			}
			
			include('Template/Escalas_items/listasItems.php');
			break;
		case constant("MNT_GUARDAASIGNADOS"):
			
			$idsItems = "";
			$idsItems = substr($_POST['idsItems'] , 1, strlen($_POST['idsItems']));
			
			$aIds = explode("," , $idsItems);
			
			if(isset($_POST['fAniadir']) && $_POST['fAniadir']==1){
				for($i=0;$i<sizeof($aIds);$i++){
					
					$cEscalaitem = new Escalas_items();
					$cEscalaitem->setIdBloque($_POST['idBloque']);
					$cEscalaitem->setIdEscala($_POST['idEscala']);
					$cEscalaitem->setIdPrueba($_POST['idPrueba']);
					$cEscalaitem->setIdItem($aIds[$i]);
					
					$cEntidadDB->insertar($cEscalaitem);
				}
			}
			if(isset($_POST['fQuitar']) && $_POST['fQuitar']==1){
				for($i=0;$i<sizeof($aIds);$i++){
					
					$cEscalaitem = new Escalas_items();
					$cEscalaitem->setIdBloque($_POST['idBloque']);
					$cEscalaitem->setIdEscala($_POST['idEscala']);
					$cEscalaitem->setIdPrueba($_POST['idPrueba']);
					$cEscalaitem->setIdItem($aIds[$i]);
					
					$cEntidadDB->borrar($cEscalaitem);
				}
			}
			
			if(isset($_POST['fLimpiar']) && $_POST['fLimpiar']==1){

				$cEscalaitem = new Escalas_items();
				$cEscalaitem->setIdBloque($_POST['idBloque']);
				$cEscalaitem->setIdEscala($_POST['idEscala']);
				$cEscalaitem->setIdPrueba($_POST['idPrueba']);
				//$cEscalaitem->setIdItem($aIds[$i]);
				
				$cEntidadDB->borrar($cEscalaitem);
			}
			//Una vez introducidos o borrados los datos volvemos a cargar las listas con los datos actualizados
		
			$cItems = new Items();
			$cItems->setIdPrueba($_POST['idPrueba']);
			$cItems->setIdPruebaHast($_POST['idPrueba']);
			$cItems->setCodIdiomaIso2($sLang);
			$cItems->setOrderBy("orden");
			$cItems->setOrder("ASC");
			$sql = $cItemsDB->listaItemsNoAsignados($cItems);
			$listaItems = $conn->Execute($sql);
			
			$cEntidad->setIdPrueba($_POST['idPrueba']);
			$cEntidad->setIdPruebaHast($_POST['idPrueba']);
			$cEntidad->setIdBloque($_POST['idBloque']);
			$cEntidad->setIdBloqueHast($_POST['idBloque']);
			$cEntidad->setIdEscala($_POST['idEscala']);
			$cEntidad->setIdEscalaHast($_POST['idEscala']);
			$cEntidad->setOrderBy("idItem");
			$cEntidad->setOrder("ASC");
			$sqlEscalaItems = $cEntidadDB->readLista($cEntidad);
			$listaEscalaItems = $conn->Execute($sqlEscalaItems);
			
			$bPinta=true;
			
			include('Template/Escalas_items/listasItems.php');
		break;
			
			
		default:
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Escalas_items/mntescalas_items.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdBloque((isset($_POST["fIdBloque"])) ? $_POST["fIdBloque"] : "");
		$cEntidad->setIdEscala((isset($_POST["fIdEscala"])) ? $_POST["fIdEscala"] : "");
		$cEntidad->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
		$cEntidad->setIdItem((isset($_POST["fIdItem"])) ? $_POST["fIdItem"] : "");
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
		global $comboBLOQUES;
		$cEntidad->setIdBloque((isset($_POST["LSTIdBloque"]) && $_POST["LSTIdBloque"] != "") ? $_POST["LSTIdBloque"] : "");	$cEntidad->setBusqueda(constant("STR_BLOQUE"), (isset($_POST["LSTIdBloque"]) && $_POST["LSTIdBloque"] != "" ) ? $comboBLOQUES->getDescripcionCombo($_POST["LSTIdBloque"]) : "");
		global $comboESCALAS;
		$cEntidad->setIdEscala((isset($_POST["LSTIdEscala"]) && $_POST["LSTIdEscala"] != "") ? $_POST["LSTIdEscala"] : "");	$cEntidad->setBusqueda(constant("STR_ESCALA"), (isset($_POST["LSTIdEscala"]) && $_POST["LSTIdEscala"] != "" ) ? $comboESCALAS->getDescripcionCombo($_POST["LSTIdEscala"]) : "");
		global $comboPRUEBAS;
		$cEntidad->setIdPrueba((isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "") ? $_POST["LSTIdPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_PRUEBA"), (isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "" ) ? $comboPRUEBAS->getDescripcionCombo($_POST["LSTIdPrueba"]) : "");
		global $comboITEMS;
		$cEntidad->setIdItem((isset($_POST["LSTIdItem"]) && $_POST["LSTIdItem"] != "") ? $_POST["LSTIdItem"] : "");	$cEntidad->setBusqueda(constant("STR_ITEM"), (isset($_POST["LSTIdItem"]) && $_POST["LSTIdItem"] != "" ) ? $comboITEMS->getDescripcionCombo($_POST["LSTIdItem"]) : "");
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
//ob_end_flush();
?>