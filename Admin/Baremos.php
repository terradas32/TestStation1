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
	require_once(constant("DIR_WS_COM") . "Baremos/BaremosDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos/Baremos.php");
	require_once(constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
	require_once(constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
	require_once(constant("DIR_WS_COM") . "Bloques/Bloques.php");
		
include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
	$cUtilidades	= new Utilidades();
	
	$cEntidadDB	= new BaremosDB($conn);  // Entidad DB
	$cEntidad	= new Baremos();  // Entidad
	
	$cEscalasItemsDB = new Escalas_itemsDB($conn);
	$cBloquesDB = new BloquesDB($conn);
	
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
					include('Template/Baremos/mntbaremosl.php');
				}else{
					$cEntidad	= new Baremos();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Baremos/mntbaremosa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Baremos/mntbaremosa.php');
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
				include('Template/Baremos/mntbaremosl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Baremos/mntbaremosa.php');
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
			include('Template/Baremos/mntbaremosl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Baremos/mntbaremosa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidadCompleta($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Baremos/mntbaremosa.php');
			break;
		case constant("MNT_BUSCAR"):
//			echo "idPrueba: " . $_POST['fIdPrueba'];
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			$sIdPrueba = (isset($_POST['fIdPrueba'])) ? $_POST['fIdPrueba'] : "";
			$cEntidad->setIdPrueba($sIdPrueba);
			include('Template/Baremos/mntbaremos.php');
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
			include('Template/Baremos/mntbaremosl.php');
			break;
			
		case constant("MNT_ANIADEBAREMO"):
			include('Template/Baremos/aniadebaremo.php');
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
			$cEntidad = new Baremos();
			$cEntidad->setIdPrueba($_POST['fIdPrueba']);
			if(isset($_POST['fIdEscala']) && isset($_POST['fIdBloque'])){
				$cEntidad->setIdEscala($_POST['fIdEscala']);
				$cEntidad->setIdEscalaHast($_POST['fIdEscala']);
				$cEntidad->setIdBloque($_POST['fIdBloque']);
				$cEntidad->setIdBloqueHast($_POST['fIdBloque']);
			}
			$sqlBaremos= $cEntidadDB->readLista($cEntidad);
			//echo $sqlBaremos . "<br />";
			$listaBaremos = $conn->Execute($sqlBaremos);
			
			include('Template/Baremos/listabaremos.php');
			break;
			
		case constant("MNT_COMPRUEBAESCALAS"):
			
			$cEscalasItems = new Escalas_items();
			$cEscalasItems->setIdPrueba($_POST['fIdPrueba']);
			$cEscalasItems->setIdPruebaHast($_POST['fIdPrueba']);
			$sqlEscalas = $cEscalasItemsDB->readLista($cEscalasItems);
			$listaEscalas = $conn->Execute($sqlEscalas);
			//echo $sqlEscalas . "<br />";
			if($listaEscalas->recordCount()>0){
				$cEscalasItems->setOrderBy("idBloque");
				$cEscalasItems->setOrder("ASC");
				$sql = $cEscalasItemsDB->readListaGroupBloque($cEscalasItems);
				$lista = $conn->Execute($sql);
				
				include('Template/Baremos/escalas.php');
				?><script>
					$("#aniadebaremo").empty();
					$("#listabaremos").empty();
					listabaremos();
				</script><?php 
			}else{
				?><script>
				if(document.forms[0].fIdBloque!=null){
					document.forms[0].fIdBloque.value="";
				}
				if(document.forms[0].fIdEscala!=null){
					document.forms[0].fIdEscala.value="";
				}
				listabaremos('',0);</script><?php 
			}
			
			break;
			
		case constant("MNT_LISTAPUNTBAREMOS"):
			require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
			require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");	
			$cBarRes = new Baremos_resultados();
			$cBarResDB = new Baremos_resultadosDB($conn);
			
			if(isset($_POST['fAniade']) && $_POST['fAniade']!=""){
				$cBarRes->setIdBaremo((isset($_POST["fIdBaremo"])) ? $_POST["fIdBaremo"] : "");
				$cBarRes->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
				$cBarRes->setIdBloque((isset($_POST["fIdBloque"])) ? $_POST["fIdBloque"] : "0");
				$cBarRes->setIdEscala((isset($_POST["fIdEscala"])) ? $_POST["fIdEscala"] : "0");
				$cBarRes->setPuntMin((isset($_POST["fPuntMinima"])) ? $_POST["fPuntMinima"] : "");
				$cBarRes->setPuntMax((isset($_POST["fPuntMaxima"])) ? $_POST["fPuntMaxima"] : "");
				$cBarRes->setPuntBaremada((isset($_POST["fPuntBaremada"])) ? $_POST["fPuntBaremada"] : "");
				$newIdRes = $cBarResDB->insertar($cBarRes);
			}
			if(isset($_POST['fBorra']) && $_POST['fBorra']!=""){
				$cBarRes->setIdResultado((isset($_POST["fIdResultado"])) ? $_POST["fIdResultado"] : "");
				$cBarResDB->borrar($cBarRes);
			}

			$cBarRes1 = new Baremos_resultados();
			$cBarRes1->setOrderBy('puntMin');
			$cBarRes1->setOrder('ASC');	
			$cBarRes1->setIdBaremo((isset($_POST["fIdBaremo"])) ? $_POST["fIdBaremo"] : "");
			$cBarRes1->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
			$cBarRes1->setIdBloque((isset($_POST["fIdBloque"])) ? $_POST["fIdBloque"] : "0");
			$cBarRes1->setIdEscala((isset($_POST["fIdEscala"])) ? $_POST["fIdEscala"] : "0");
			$sqlRes = $cBarResDB->readLista($cBarRes1);
			$listaResultados = $conn->Execute($sqlRes);
			
			include('Template/Baremos/listapuntuaciones.php');
			break;	
		default:
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Baremos/mntbaremos.php');
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
		$cEntidad->setIdBloque((isset($_POST["fIdBloque"])) ? $_POST["fIdBloque"] : "0");
		$cEntidad->setIdEscala((isset($_POST["fIdEscala"])) ? $_POST["fIdEscala"] : "0");
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