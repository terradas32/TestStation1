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
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
	require_once(constant("DIR_WS_COM") . "Opciones/Opciones.php");
	require_once(constant("DIR_WS_COM") . "Items/ItemsDB.php");
	require_once(constant("DIR_WS_COM") . "Items/Items.php");
	require_once(constant("DIR_WS_COM") . "Opciones_ejemplos/Opciones_ejemplosDB.php");
	require_once(constant("DIR_WS_COM") . "Opciones_ejemplos/Opciones_ejemplos.php");
	require_once(constant("DIR_WS_COM") . "Ejemplos/EjemplosDB.php");
	require_once(constant("DIR_WS_COM") . "Ejemplos/Ejemplos.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	
include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
	$cUtilidades	= new Utilidades();
	
	$cEntidadDB	= new PruebasDB($conn);  // Entidad DB
	$cEntidad	= new Pruebas();  // Entidad
	
	$cOpcionesDB	= new OpcionesDB($conn);  // Opciones DB
	$cOpciones	= new Opciones();  // Opciones
	
	$cOpcionesEjemplosDB	= new Opciones_ejemplosDB($conn);  // Items DB
	$cOpcionesEjemplos	= new Opciones_ejemplos();  // Items
	
	$cEjemplosDB	= new EjemplosDB($conn);  // Opciones DB
	$cEjemplos		= new Ejemplos();  // Opciones
	
	$cItemsDB	= new ItemsDB($conn);  // Items DB
	$cItems	= new Items();  // Items
	
	$cIdiomas2DB	= new IdiomasDB($conn);  // Entidad DB
	$cIdiomas2	= new Idiomas();  // Entidad
	
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	
	$comboPRUEBAS	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboITEMS	= new Combo($conn,"fIdItem","idItem","idItem","Descripcion","items","",constant("SLC_OPCION"),"","","");
	$comboITEMS	= new Combo($conn,"fIdEjemplo","idEjemplo","idEjemplo","Descripcion","ejemplos","",constant("SLC_OPCION"),"","","");
	$comboWI_IDIOMAS	= new Combo($conn,"fCodIdiomaIso2","codIdiomaIso2","nombre","Descripcion","wi_idiomas","",constant("SLC_OPCION"),"activoFront=1","","");
	$comboTIPOS_PRUEBA	= new Combo($conn,"fIdTipoPrueba","idTipoPrueba","descripcion","Descripcion","tipos_prueba","",constant("SLC_OPCION"),"","","");
	$comboUSUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","");
	
	//echo('modo:' . $_POST['MODO'] . '<br />');
	//echo('Origen:' . $_POST['ORIGEN']);
	
	if (!isset($_POST['MODO'])){
		session_start();
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "04000 - " . constant("ERR_NO_AUTORIZADO");
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}
	switch ($_POST['MODO'])
	{
		case constant("MNT_ALTA"):
			if(empty($_POST['fItem']) && empty($_POST['fOpcion']) && empty($_POST['fEjemplo']) && empty($_POST['fOpcionEjemplo'])){
				$cEntidad	= readEntidad($cEntidad);
				$newId	= $cEntidadDB->insertar($cEntidad);
				if (!empty($newId)){
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
					
					if(isset($_POST['fSeguir']) && $_POST['fSeguir']==1){
						
						$cItems = readListaItems($cItems);
						$cItems->getIdPrueba();
						if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
							$bInit=true;
							$sql = $cItemsDB->readLista($cItems);
						}else{
							if (!empty($_POST['items_next_page']) && $_POST['items_next_page'] > 1 ){
								$bInit=false;
								$sql = $cItemsDB->readLista($cItems);
							}else{
								$bInit=true;
								$sql = $cItemsDB->readLista($cItems);
							}
						}
						$pager = new ADODB_Pager($conn,$sql,'items');
						if ($bInit)	$pager->curr_page=1;
						$pager->showPageLinks = true;
						$LnPag = $cItems->getLineasPagina();
						if (!empty($LnPag) && is_numeric ($LnPag)){
							$pager->setRows($LnPag);
						}else{
							$pager->setRows(constant("CNF_LINEAS_PAGINA"));
						}
						$lista=$pager->getRS();
						include('Template/ProcesoPruebas/mntitemsl.php');
					}else{
					
						if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
						{
							if (!empty($_POST['pruebas_next_page']) && $_POST['pruebas_next_page'] > 1 ){
								$bInit=false;
								$sql = $cEntidadDB->readLista(readLista($cEntidad));
							}else{
								$bInit=true;
								$sql = $cEntidadDB->readLista(readLista($cEntidad));
							}
							$pager = new ADODB_Pager($conn,$sql,'pruebas');
							if ($bInit)	$pager->curr_page=1;
							$pager->showPageLinks = true;
							$LnPag = $cEntidad->getLineasPagina();
							if (!empty($LnPag) && is_numeric ($LnPag)){
								$pager->setRows($LnPag);
							}else{
								$pager->setRows(constant("CNF_LINEAS_PAGINA"));
							}
							$lista=$pager->getRS();
							include('Template/ProcesoPruebas/mntpruebasl.php');
						}else{
							$cEntidad	= new Pruebas();  // inicializamos la Entidad
							$_POST['MODO']    = constant("MNT_ALTA");
							include('Template/ProcesoPruebas/mntpruebasa.php');
						}
					}
				}else{
					?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
					$_POST['MODO']=constant("MNT_ALTA");
					include('Template/ProcesoPruebas/mntpruebasa.php');
				}
				break;
			}else{
				if(!empty($_POST['fItem']) && $_POST['fItem']==1){
					$cItems	= readEntidadItems($cItems);
					$newId	= $cItemsDB->insertar($cItems);
					if (!empty($newId)){
					?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
						
						if(isset($_POST['fSeguir']) && $_POST['fSeguir']==1){
							$cOpciones = new Opciones();
							$cOpciones = readListaOpciones($cOpciones);
							$cOpciones->setIdItem($newId);
							$cOpciones->getIdPrueba();
							if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
								$bInit=true;
								$sql = $cOpcionesDB->readLista($cOpciones);
							}else{
								if (!empty($_POST['opciones_next_page']) && $_POST['opciones_next_page'] > 1 ){
									$bInit=false;
									$sql = $cOpcionesDB->readLista($cOpciones);
								}else{
									$bInit=true;
									$sql = $cOpcionesDB->readLista($cOpciones);
								}
							}
							$pager = new ADODB_Pager($conn,$sql,'opciones');
							if ($bInit)	$pager->curr_page=1;
							$pager->showPageLinks = true;
							$LnPag = $cOpciones->getLineasPagina();
							if (!empty($LnPag) && is_numeric ($LnPag)){
								$pager->setRows($LnPag);
							}else{
								$pager->setRows(constant("CNF_LINEAS_PAGINA"));
							}
							$lista=$pager->getRS();
							include('Template/ProcesoPruebas/mntopcionesl.php');
						}else{	
							if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
							{
								$cItems= new Items();
								if (!empty($_POST['items_next_page']) && $_POST['items_next_page'] > 1 ){
									$bInit=false;
									$sql = $cItemsDB->readLista(readListaItems($cItems));
								}else{
									$bInit=true;
									$sql = $cItemsDB->readLista(readListaItems($cItems));
								}
								$pager = new ADODB_Pager($conn,$sql,'items');
								if ($bInit)	$pager->curr_page=1;
								$pager->showPageLinks = true;
								$LnPag = $cItems->getLineasPagina();
								if (!empty($LnPag) && is_numeric ($LnPag)){
									$pager->setRows($LnPag);
								}else{
									$pager->setRows(constant("CNF_LINEAS_PAGINA"));
								}
								$lista=$pager->getRS();
								include('Template/ProcesoPruebas/mntitemsl.php');
							}else{
								$cItems	= new Items();  // inicializamos la Entidad
								$_POST['MODO']    = constant("MNT_ALTA");
								include('Template/ProcesoPruebas/mntitemsa.php');
							}
						}
					}else{
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
						$_POST['MODO']=constant("MNT_ALTA");
						include('Template/ProcesoPruebas/mntitemsa.php');
					}
					break;
				}
				
				if(!empty($_POST['fOpcion']) && $_POST['fOpcion']==1){
					$cOpciones	= readEntidadOpciones($cOpciones);
					$newId	= $cOpcionesDB->insertar($cOpciones);
					if (!empty($newId)){
					?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
						if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
						{
							$cOpciones = new Opciones();
							if (!empty($_POST['opciones_next_page']) && $_POST['opciones_next_page'] > 1 ){
								$bInit=false;
								$sql = $cOpcionesDB->readLista(readListaOpciones($cOpciones));
							}else{
								$bInit=true;
								$sql = $cOpcionesDB->readLista(readListaOpciones($cOpciones));
							}
							$pager = new ADODB_Pager($conn,$sql,'opciones');
							if ($bInit)	$pager->curr_page=1;
							$pager->showPageLinks = true;
							$LnPag = $cOpciones->getLineasPagina();
							if (!empty($LnPag) && is_numeric ($LnPag)){
								$pager->setRows($LnPag);
							}else{
								$pager->setRows(constant("CNF_LINEAS_PAGINA"));
							}
							$lista=$pager->getRS();
							include('Template/ProcesoPruebas/mntopcionesl.php');
						}else{
							$cOpciones	= new Opciones();  // inicializamos la Entidad
							$_POST['MODO']    = constant("MNT_ALTA");
							include('Template/ProcesoPruebas/mntopcionesa.php');
						}
					}else{
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
						$_POST['MODO']=constant("MNT_ALTA");
						include('Template/ProcesoPruebas/mntopcionesa.php');
					}
					break;
				}
				
				if(!empty($_POST['fEjemplo']) && $_POST['fEjemplo']==1){
					$cEjemplos	= readEntidadEjemplos($cEjemplos);
					$newId	= $cEjemplosDB->insertar($cEjemplos);
					if (!empty($newId)){
					?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
						
						if(isset($_POST['fSeguir']) && $_POST['fSeguir']==1){
							$cOpcionesEjemplos = new Opciones_ejemplos();
							$cOpcionesEjemplos = readListaOpcionesEjemplos($cOpcionesEjemplos);
							$cOpcionesEjemplos->setIdEjemplo($newId);
							$cOpcionesEjemplos->getIdPrueba();
							if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
								$bInit=true;
								$sql = $cOpcionesEjemplosDB->readLista($cOpcionesEjemplos);
							}else{
								if (!empty($_POST['opcionesejemplos_next_page']) && $_POST['opcionesejemplos_next_page'] > 1 ){
									$bInit=false;
									$sql = $cOpcionesEjemplosDB->readLista($cOpcionesEjemplos);
								}else{
									$bInit=true;
									$sql = $cOpcionesEjemplosDB->readLista($cOpcionesEjemplos);
								}
							}
							$pager = new ADODB_Pager($conn,$sql,'opcionesejemplos');
							if ($bInit)	$pager->curr_page=1;
							$pager->showPageLinks = true;
							$LnPag = $cOpcionesEjemplos->getLineasPagina();
							if (!empty($LnPag) && is_numeric ($LnPag)){
								$pager->setRows($LnPag);
							}else{
								$pager->setRows(constant("CNF_LINEAS_PAGINA"));
							}
							$lista=$pager->getRS();
							include('Template/ProcesoPruebas/mntopciones_ejemplosl.php');
						}else{	
							if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
							{
								$cEjemplos= new Ejemplos();
												if (!empty($_POST['ejemplos_next_page']) && $_POST['ejemplos_next_page'] > 1 ){
									$bInit=false;
									$sql = $cEjemplosDB->readLista(readListaEjemplos($cEjemplos));
								}else{
									$bInit=true;
									$sql = $cEjemplosDB->readLista(readListaEjemplos($cEjemplos));
								}
								$pager = new ADODB_Pager($conn,$sql,'ejemplos');
								if ($bInit)	$pager->curr_page=1;
								$pager->showPageLinks = true;
								$LnPag = $cEjemplos->getLineasPagina();
								if (!empty($LnPag) && is_numeric ($LnPag)){
									$pager->setRows($LnPag);
								}else{
									$pager->setRows(constant("CNF_LINEAS_PAGINA"));
								}
								$lista=$pager->getRS();
								include('Template/ProcesoPruebas/mntejemplosl.php');
							}else{
								$cEjemplos	= new Ejemplos();  // inicializamos la Entidad
								$_POST['MODO']    = constant("MNT_ALTA");
								include('Template/ProcesoPruebas/mntejemplosa.php');
							}
						}
					}else{
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
						$_POST['MODO']=constant("MNT_ALTA");
						include('Template/ProcesoPruebas/mntejemplosa.php');
					}
					break;
				}
				
				if(isset($_POST['fOpcionEjemplo']) || $_POST['fOpcionEjemplo']==1){
					$cOpcionesEjemplos	= readEntidadOpcionesEjemplos($cOpcionesEjemplos);
					$newId	= $cOpcionesEjemplosDB->insertar($cOpcionesEjemplos);
					if (!empty($newId)){
					?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
						if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
						{
							$cOpciones = new Opciones();
							if (!empty($_POST['opcionesejemplos_next_page']) && $_POST['opcionesejemplos_next_page'] > 1 ){
								$bInit=false;
								$sql = $cOpcionesEjemplosDB->readLista(readListaOpcionesEjemplos($cOpcionesEjemplos));
							}else{
								$bInit=true;
								$sql = $cOpcionesEjemplosDB->readLista(readListaOpcionesEjemplos($cOpcionesEjemplos));
							}
							$pager = new ADODB_Pager($conn,$sql,'opcionesejemplos');
							if ($bInit)	$pager->curr_page=1;
							$pager->showPageLinks = true;
							$LnPag = $cOpcionesEjemplos->getLineasPagina();
							if (!empty($LnPag) && is_numeric ($LnPag)){
								$pager->setRows($LnPag);
							}else{
								$pager->setRows(constant("CNF_LINEAS_PAGINA"));
							}
							$lista=$pager->getRS();
							include('Template/ProcesoPruebas/mntopciones_ejemplosl.php');
						}else{
							$cOpcionesEjemplos	= new Opciones_ejemplos();  // inicializamos la Entidad
							$_POST['MODO']    = constant("MNT_ALTA");
							include('Template/ProcesoPruebas/mntopciones_ejemplosa.php');
						}
					}else{
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
						$_POST['MODO']=constant("MNT_ALTA");
						include('Template/ProcesoPruebas/mntopciones_ejemplosa.php');
					}
					break;
				}
					
					
			}
		case constant("MNT_MODIFICAR"):
			if(empty($_POST['fItem']) && empty($_POST['fOpcion']) && empty($_POST['fEjemplo']) && empty($_POST['fOpcionEjemplo']))
			{
				$cEntidad = readEntidad($cEntidad);
				quitaImgPruebas($cEntidad, $cEntidadDB, false);
				if ($cEntidadDB->modificar($cEntidad))
				{
					if(isset($_POST['fSeguir']) && $_POST['fSeguir']==1){
						
						$cItems = readListaItems($cItems);
						$cItems->getIdPrueba();
						if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
							$bInit=true;
							$sql = $cItemsDB->readLista($cItems);
						}else{
							if (!empty($_POST['items_next_page']) && $_POST['items_next_page'] > 1 ){
								$bInit=false;
								$sql = $cItemsDB->readLista($cItems);
							}else{
								$bInit=true;
								$sql = $cItemsDB->readLista($cItems);
							}
						}
						$pager = new ADODB_Pager($conn,$sql,'items');
						if ($bInit)	$pager->curr_page=1;
						$pager->showPageLinks = true;
						$LnPag = $cItems->getLineasPagina();
						if (!empty($LnPag) && is_numeric ($LnPag)){
							$pager->setRows($LnPag);
						}else{
							$pager->setRows(constant("CNF_LINEAS_PAGINA"));
						}
						$lista=$pager->getRS();
						include('Template/ProcesoPruebas/mntitemsl.php');
					}else{
						if (!empty($_POST['pruebas_next_page']) && $_POST['pruebas_next_page'] > 1 ){
							$bInit=false;
							$sql = $cEntidadDB->readLista(readLista($cEntidad));
						}else{
							$bInit=true;
							$sql = $cEntidadDB->readLista(readLista($cEntidad));
						}
						$pager = new ADODB_Pager($conn,$sql,'pruebas');
						if ($bInit)	$pager->curr_page=1;
						$pager->showPageLinks = true;
						$LnPag = $cEntidad->getLineasPagina();
						if (!empty($LnPag) && is_numeric ($LnPag)){
							$pager->setRows($LnPag);
						}else{
							$pager->setRows(constant("CNF_LINEAS_PAGINA"));
						}
						$lista=$pager->getRS();
						include('Template/ProcesoPruebas/mntpruebasl.php');
					}
				
				}else{
					?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
					$_POST['MODO']=constant("MNT_MODIFICAR");
					include('Template/ProcesoPruebas/mntpruebasa.php');
				}
				break;
			}else{
				if(isset($_POST['fItem']) && $_POST['fItem']==1)
				{
					$cItems = readEntidadItems($cItems);
					quitaImg($cItems, $cItemsDB, false);
					if ($cItemsDB->modificar($cItems))
					{
						if(isset($_POST['fSeguir']) && $_POST['fSeguir']==1){
							$cOpciones = new Opciones();
							$cOpciones = readListaOpciones($cOpciones);
							$cOpciones->setIdItem($cItems->getIdItem());
							$cOpciones->getIdPrueba();
							if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
								$bInit=true;
								$sql = $cOpcionesDB->readLista($cOpciones);
							}else{
								if (!empty($_POST['opciones_next_page']) && $_POST['opciones_next_page'] > 1 ){
									$bInit=false;
									$sql = $cOpcionesDB->readLista($cOpciones);
								}else{
									$bInit=true;
									$sql = $cOpcionesDB->readLista($cOpciones);
								}
							}
							$pager = new ADODB_Pager($conn,$sql,'opciones');
							if ($bInit)	$pager->curr_page=1;
							$pager->showPageLinks = true;
							$LnPag = $cOpciones->getLineasPagina();
							if (!empty($LnPag) && is_numeric ($LnPag)){
								$pager->setRows($LnPag);
							}else{
								$pager->setRows(constant("CNF_LINEAS_PAGINA"));
							}
							$lista=$pager->getRS();
							include('Template/ProcesoPruebas/mntopcionesl.php');
						}else{
							$cItems = new Items();
							$cItems = readListaItems($cItems);
							$cItems->getIdPrueba();
							$bInit=true;
							$sql = $cItemsDB->readLista(readListaItems($cItems));
							
							$pager = new ADODB_Pager($conn,$sql,'items');
							if ($bInit)	$pager->curr_page=1;
							$pager->showPageLinks = true;
							$LnPag = $cItems->getLineasPagina();
							if (!empty($LnPag) && is_numeric ($LnPag)){
								$pager->setRows($LnPag);
							}else{
								$pager->setRows(constant("CNF_LINEAS_PAGINA"));
							}
							$lista=$pager->getRS();
							include('Template/ProcesoPruebas/mntitemsl.php');
						}
					
					}else{
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
						$_POST['MODO']=constant("MNT_MODIFICAR");
						include('Template/ProcesoPruebas/mntitemsa.php');
					}
					break;	
				}
				if(isset($_POST['fOpcion']) && $_POST['fOpcion']==1)
				{
					$cOpciones = readEntidadOpciones($cOpciones);
					if ($cOpcionesDB->modificar($cOpciones))
					{
						$cOpciones = new Opciones();
						$cOpciones = readListaOpciones($cOpciones);
						$bInit=true;
						$sql = $cOpcionesDB->readLista(readListaOpciones($cOpciones));
							
						$pager = new ADODB_Pager($conn,$sql,'opciones');
						if ($bInit)	$pager->curr_page=1;
						$pager->showPageLinks = true;
						$LnPag = $cOpciones->getLineasPagina();
						if (!empty($LnPag) && is_numeric ($LnPag)){
							$pager->setRows($LnPag);
						}else{
							$pager->setRows(constant("CNF_LINEAS_PAGINA"));
						}
						$lista=$pager->getRS();
						include('Template/ProcesoPruebas/mntopcionesl.php');
						
					
					}else{
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
						$_POST['MODO']=constant("MNT_MODIFICAR");
						include('Template/ProcesoPruebas/mntopcionesa.php');
					}
					break;	
				}
				if(isset($_POST['fEjemplo']) && $_POST['fEjemplo']==1)
				{
					//Modificamos los ekemplos
					$cEjemplos = readEntidadEjemplos($cEjemplos);
					quitaImgEjemplos($cEjemplos, $cEjemplosDB, false);
					if ($cEjemplosDB->modificar($cEjemplos))
					{
						if(isset($_POST['fSeguir']) && $_POST['fSeguir']==1){
							$cOpcionesEjemplos = new Opciones_ejemplos();
							$cOpcionesEjemplos = readListaOpcionesEjemplos($cOpcionesEjemplos);
							$cOpcionesEjemplos->setIdEjemplo($cEjemplos->getIdEjemplo());
							$cOpcionesEjemplos->getIdPrueba();
							if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
								$bInit=true;
								$sql = $cOpcionesEjemplosDB->readLista($cOpcionesEjemplos);
							}else{
								if (!empty($_POST['opcionesejemplos_next_page']) && $_POST['opcionesejemplos_next_page'] > 1 ){
									$bInit=false;
									$sql = $cOpcionesEjemplosDB->readLista($cOpcionesEjemplos);
								}else{
									$bInit=true;
									$sql = $cOpcionesEjemplosDB->readLista($cOpcionesEjemplos);
								}
							}
							$pager = new ADODB_Pager($conn,$sql,'opcionesejemplos');
							if ($bInit)	$pager->curr_page=1;
							$pager->showPageLinks = true;
							$LnPag = $cOpcionesEjemplos->getLineasPagina();
							if (!empty($LnPag) && is_numeric ($LnPag)){
								$pager->setRows($LnPag);
							}else{
								$pager->setRows(constant("CNF_LINEAS_PAGINA"));
							}
							$lista=$pager->getRS();
							include('Template/ProcesoPruebas/mntopciones_ejemplosl.php');
						}else{
							$cEjemplos = new Ejemplos();
							$cEjemplos = readListaEjemplos($cEjemplos);
							$cEjemplos->getIdPrueba();
							$bInit=true;
							$sql = $cEjemplosDB->readLista(readListaEjemplos($cEjemplos));
							
							$pager = new ADODB_Pager($conn,$sql,'ejemplos');
							if ($bInit)	$pager->curr_page=1;
							$pager->showPageLinks = true;
							$LnPag = $cEjemplos->getLineasPagina();
							if (!empty($LnPag) && is_numeric ($LnPag)){
								$pager->setRows($LnPag);
							}else{
								$pager->setRows(constant("CNF_LINEAS_PAGINA"));
							}
							$lista=$pager->getRS();
							include('Template/ProcesoPruebas/mntejemplosl.php');
						}
					
					}else{
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
						$_POST['MODO']=constant("MNT_MODIFICAR");
						include('Template/ProcesoPruebas/mntejemplosa.php');
					}
					break;	
				}
				if(isset($_POST['fOpcionEjemplo']) && $_POST['fOpcionEjemplo']==1){
					$cOpcionesEjemplos = readEntidadOpciones($cOpcionesEjemplos);
					if ($cOpcionesEjemplosDB->modificar($cOpcionesEjemplos))
					{
						$cOpcionesEjemplos = new Opciones_ejemplos();
						$cOpcionesEjemplos = readListaOpcionesEjemplos($cOpcionesEjemplos);
						$bInit=true;
						$sql = $cOpcionesEjemplosDB->readLista(readListaOpcionesEjemplos($cOpcionesEjemplos));
							
						$pager = new ADODB_Pager($conn,$sql,'opcionesejemplos');
						if ($bInit)	$pager->curr_page=1;
						$pager->showPageLinks = true;
						$LnPag = $cOpcionesEjemplos->getLineasPagina();
						if (!empty($LnPag) && is_numeric ($LnPag)){
							$pager->setRows($LnPag);
						}else{
							$pager->setRows(constant("CNF_LINEAS_PAGINA"));
						}
						$lista=$pager->getRS();
						include('Template/ProcesoPruebas/mntopciones_ejemplosl.php');
						
					
					}else{
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
						$_POST['MODO']=constant("MNT_MODIFICAR");
						include('Template/ProcesoPruebas/mntopciones_ejemplosa.php');
					}
					break;	
				}
			
			}
			
		case constant("MNT_BORRAR"):
			$cEntidad = readEntidad($cEntidad);
			if (!$cEntidadDB->borrar($cEntidad))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
			}
			if (!empty($_POST['pruebas_next_page']) && $_POST['pruebas_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}
			$pager = new ADODB_Pager($conn,$sql,'pruebas');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/ProcesoPruebas/mntpruebasl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setCodIdiomaIso2($sLang);
			if(empty($_POST['fItem']) && empty($_POST['fOpcion']) && empty($_POST['fEjemplo']) && empty($_POST['fOpcionEjemplo'])){
				
				if(isset($_POST['fAniade']) && $_POST['fAniade']!=""){
					$cEntidad = readEntidad($cEntidad);
					$cEntidad->setCodIdiomaIso2("");
					
					$_POST['fDisabled'] = "1";
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/ProcesoPruebas/mntpruebasa.php');
					break;
				}else{
					$_POST['fIdPrueba'] = "";
					$_POST['fDisabled'] = "";
					
					$cEntidad->setIdPrueba("");
					$cEntidad->setOrderBy("fecMod");
					$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
					$cEntidad->setOrder("DESC");
					$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
					$_POST["LSTOrderBy"] = "fecMod";
					$_POST["LSTOrder"] = "DESC";
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/ProcesoPruebas/mntpruebasa.php');
					break;
				}
			}else{
				if(isset($_POST['fItem']) && $_POST['fItem']==1){
					$cItems->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
					$cItems->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"])) ? $_POST["fCodIdiomaIso2"] : "");
					$cItems->setOrderBy("fecMod");
					$cItems->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
					$cItems->setOrder("DESC");
					$cItems->setBusqueda(constant("STR_ORDEN"), "DESC");
					$_POST["LSTOrderBy"] = "fecMod";
					$_POST["LSTOrder"] = "DESC";
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/ProcesoPruebas/mntitemsa.php');
					break;
				}
				if(isset($_POST['fOpcion']) && $_POST['fOpcion']==1){
					$cOpciones->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
					$cOpciones->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"])) ? $_POST["fCodIdiomaIso2"] : "");
					$cOpciones->setIdItem((isset($_POST["fIdItem"])) ? $_POST["fIdItem"] : "");
					$cOpciones->setOrderBy("fecMod");
					$cOpciones->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
					$cOpciones->setOrder("DESC");
					$cOpciones->setBusqueda(constant("STR_ORDEN"), "DESC");
					$_POST["LSTOrderBy"] = "fecMod";
					$_POST["LSTOrder"] = "DESC";
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/ProcesoPruebas/mntopcionesa.php');
					break;
				}
				if(isset($_POST['fEjemplo']) && $_POST['fEjemplo']==1){
					$cEjemplos->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
					$cEjemplos->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"])) ? $_POST["fCodIdiomaIso2"] : "");
					$cEjemplos->setOrderBy("fecMod");
					$cEjemplos->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
					$cEjemplos->setOrder("DESC");
					$cEjemplos->setBusqueda(constant("STR_ORDEN"), "DESC");
					$_POST["LSTOrderBy"] = "fecMod";
					$_POST["LSTOrder"] = "DESC";
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/ProcesoPruebas/mntejemplosa.php');
					break;
				}
				if(isset($_POST['fOpcionEjemplo']) && $_POST['fOpcionEjemplo']==1){
					$cOpcionesEjemplos->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
					$cOpcionesEjemplos->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"])) ? $_POST["fCodIdiomaIso2"] : "");
					$cOpcionesEjemplos->setIdEjemplo((isset($_POST["fIdEjemplo"])) ? $_POST["fIdEjemplo"] : "");
					$cOpcionesEjemplos->setOrderBy("fecMod");
					$cOpcionesEjemplos->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
					$cOpcionesEjemplos->setOrder("DESC");
					$cOpcionesEjemplos->setBusqueda(constant("STR_ORDEN"), "DESC");
					$_POST["LSTOrderBy"] = "fecMod";
					$_POST["LSTOrder"] = "DESC";
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/ProcesoPruebas/mntopciones_ejemplosa.php');
					break;
				}
			}
		case constant("MNT_CONSULTAR"):
			if(empty($_POST['fItem']) && empty($_POST['fOpcion']) && empty($_POST['fEjemplo']) && empty($_POST['fOpcionEjemplo'])){
				$cEntidad = readEntidad($cEntidad);
				$cEntidad = $cEntidadDB->readEntidad($cEntidad);
				$_POST['MODO']    = constant("MNT_MODIFICAR");
				include('Template/ProcesoPruebas/mntpruebasa.php');
				break;
			}else{
				if(isset($_POST['fItem']) && $_POST['fItem']=="1"){
					$cItems = readEntidadItems($cItems);
					$cItems = $cItemsDB->readEntidad($cItems);
					$_POST['MODO']    = constant("MNT_MODIFICAR");
					include('Template/ProcesoPruebas/mntitemsa.php');
					break;
				}
				if(isset($_POST['fOpcion']) && $_POST['fOpcion']=="1"){
					$cOpciones = readEntidadOpciones($cOpciones);
					$cOpciones = $cOpcionesDB->readEntidad($cOpciones);
					$_POST['MODO']    = constant("MNT_MODIFICAR");
					include('Template/ProcesoPruebas/mntopcionesa.php');
					break;
				}
				if(isset($_POST['fEjemplo']) && $_POST['fEjemplo']=="1"){
					$cEjemplos = readEntidadEjemplos($cEjemplos);
					$cEjemplos = $cEjemplosDB->readEntidad($cEjemplos);
					$_POST['MODO']    = constant("MNT_MODIFICAR");
					include('Template/ProcesoPruebas/mntejemplosa.php');
					break;
				}
				if(isset($_POST['fOpcionEjemplo']) && $_POST['fOpcionEjemplo']=="1"){
					$cOpcionesEjemplos = readEntidadOpcionesEjemplos($cOpcionesEjemplos);
					$cOpcionesEjemplos = $cOpcionesEjemplosDB->readEntidad($cOpcionesEjemplos);
					$_POST['MODO']    = constant("MNT_MODIFICAR");
					include('Template/ProcesoPruebas/mntopciones_ejemplosa.php');
					break;
				}
			}
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/ProcesoPruebas/mntpruebas.php');
			break;
		case constant("MNT_LISTAR"):
			if(empty($_POST['fItem']) && empty($_POST['fOpcion']) && empty($_POST['fEjemplo']) && empty($_POST['fOpcionEjemplo'])){
				$cEntidad = readLista($cEntidad);
				if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
					$bInit=true;
					$sql = $cEntidadDB->readLista($cEntidad);
				}else{
					if (!empty($_POST['pruebas_next_page']) && $_POST['pruebas_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readLista($cEntidad);
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readLista($cEntidad);
					}
				}
				$pager = new ADODB_Pager($conn,$sql,'pruebas');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/ProcesoPruebas/mntpruebasl.php');
				break;
			}else{
				if(isset($_POST['fItem']) && $_POST['fItem']==1){
					$cItems = readListaItems($cItems);
					if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
						$bInit=true;
						$sql = $cItemsDB->readLista($cItems);
					}else{
						if (!empty($_POST['items_next_page']) && $_POST['items_next_page'] > 1 ){
							$bInit=false;
							$sql = $cItemsDB->readLista($cItems);
						}else{
							$bInit=true;
							$sql = $cItemsDB->readLista($cItems);
						}
					}
					$pager = new ADODB_Pager($conn,$sql,'items');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cItems->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/ProcesoPruebas/mntitemsl.php');
					break;	
				}
				if(isset($_POST['fOpcion']) && $_POST['fOpcion']==1){
					$cOpciones = readListaOpciones($cOpciones);
					if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
						$bInit=true;
						$sql = $cOpcionesDB->readLista($cOpciones);
					}else{
						if (!empty($_POST['opciones_next_page']) && $_POST['opciones_next_page'] > 1 ){
							$bInit=false;
							$sql = $cOpcionesDB->readLista($cOpciones);
						}else{
							$bInit=true;
							$sql = $cOpcionesDB->readLista($cOpciones);
						}
					}
					$pager = new ADODB_Pager($conn,$sql,'opciones');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cOpciones->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/ProcesoPruebas/mntopcionesl.php');
					break;	
				}
				if(isset($_POST['fEjemplo']) && $_POST['fEjemplo']==1){
					$cEjemplos = readListaEjemplos($cEjemplos);
					if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
						$bInit=true;
						$sql = $cEjemplosDB->readLista($cEjemplos);
					}else{
						if (!empty($_POST['ejemplos_next_page']) && $_POST['ejemplos_next_page'] > 1 ){
							$bInit=false;
							$sql = $cEjemplosDB->readLista($cEjemplos);
						}else{
							$bInit=true;
							$sql = $cEjemplosDB->readLista($cEjemplos);
						}
					}
					$pager = new ADODB_Pager($conn,$sql,'ejemplos');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEjemplos->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/ProcesoPruebas/mntejemplosl.php');
					break;	
				}
				if(isset($_POST['fOpcionEjemplo']) && $_POST['fOpcionEjemplo']==1){
					$cOpcionesEjemplos = readListaOpcionesEjemplos($cOpcionesEjemplos);
					if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
						$bInit=true;
						$sql = $cOpcionesEjemplosDB->readLista($cOpcionesEjemplos);
					}else{
						if (!empty($_POST['opcionesejemplos_next_page']) && $_POST['opcionesejemplos_next_page'] > 1 ){
							$bInit=false;
							$sql = $cOpcionesEjemplosDB->readLista($cOpcionesEjemplos);
						}else{
							$bInit=true;
							$sql = $cOpcionesEjemplosDB->readLista($cOpcionesEjemplos);
						}
					}
					//echo $sql . "<br />" . $bInit;exit;
					$pager = new ADODB_Pager($conn,$sql,'opcionesejemplos');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cOpcionesEjemplos->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/ProcesoPruebas/mntopciones_ejemplosl.php');
					break;	
				}
			}
		default:
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/ProcesoPruebas/mntpruebas.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
		$cEntidad->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"])) ? $_POST["fCodIdiomaIso2"] : "");
		$cEntidad->setCodigo((isset($_POST["fCodigo"])) ? $_POST["fCodigo"] : "");
		$cEntidad->setNombre((isset($_POST["fNombre"])) ? $_POST["fNombre"] : "");
		$cEntidad->setDescripcion((isset($_POST["fDescripcion"])) ? $_POST["fDescripcion"] : "");
		$cEntidad->setIdTipoPrueba((isset($_POST["fIdTipoPrueba"])) ? $_POST["fIdTipoPrueba"] : "");
		$cEntidad->setObservaciones((isset($_POST["fObservaciones"])) ? $_POST["fObservaciones"] : "");
		$cEntidad->setDuracion((isset($_POST["fDuracion"])) ? $_POST["fDuracion"] : "");
		$cEntidad->setBajaLog((isset($_POST["fBajaLog"])) ? $_POST["fBajaLog"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getUsuario());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getUsuario());
		return $cEntidad;
	}
	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readLista($cEntidad){
		global $conn;
		global $cUtilidades;
		$cEntidad->setIdPrueba((isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "") ? $_POST["LSTIdPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_ID"), (isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "" ) ? $_POST["LSTIdPrueba"] : "");
		$cEntidad->setIdPruebaHast((isset($_POST["LSTIdPruebaHast"]) && $_POST["LSTIdPruebaHast"] != "") ? $_POST["LSTIdPruebaHast"] : "");	$cEntidad->setBusqueda(constant("STR_ID") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdPruebaHast"]) && $_POST["LSTIdPruebaHast"] != "" ) ? $_POST["LSTIdPruebaHast"] : "");
		global $comboWI_IDIOMAS;
		$cEntidad->setCodIdiomaIso2((isset($_POST["LSTCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "") ? $_POST["LSTCodIdiomaIso2"] : "");	$cEntidad->setBusqueda(constant("STR_IDIOMA"), (isset($_POST["LSTCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "" ) ? $comboWI_IDIOMAS->getDescripcionCombo($_POST["LSTCodIdiomaIso2"]) : "");
		$cEntidad->setCodigo((isset($_POST["LSTCodigo"]) && $_POST["LSTCodigo"] != "") ? $_POST["LSTCodigo"] : "");	$cEntidad->setBusqueda(constant("STR_CODIGO"), (isset($_POST["LSTCodigo"]) && $_POST["LSTCodigo"] != "" ) ? $_POST["LSTCodigo"] : "");
		$cEntidad->setNombre((isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "") ? $_POST["LSTNombre"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE"), (isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "" ) ? $_POST["LSTNombre"] : "");
		$cEntidad->setDescripcion((isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "") ? $_POST["LSTDescripcion"] : "");	$cEntidad->setBusqueda(constant("STR_DESCRIPCION"), (isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "" ) ? $_POST["LSTDescripcion"] : "");
		global $comboTIPOS_PRUEBA;
		$cEntidad->setIdTipoPrueba((isset($_POST["LSTIdTipoPrueba"]) && $_POST["LSTIdTipoPrueba"] != "") ? $_POST["LSTIdTipoPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_TIPO_PRUEBA"), (isset($_POST["LSTIdTipoPrueba"]) && $_POST["LSTIdTipoPrueba"] != "" ) ? $comboTIPOS_PRUEBA->getDescripcionCombo($_POST["LSTIdTipoPrueba"]) : "");
		$cEntidad->setObservaciones((isset($_POST["LSTObservaciones"]) && $_POST["LSTObservaciones"] != "") ? $_POST["LSTObservaciones"] : "");	$cEntidad->setBusqueda(constant("STR_OBSERVACIONES"), (isset($_POST["LSTObservaciones"]) && $_POST["LSTObservaciones"] != "" ) ? $_POST["LSTObservaciones"] : "");
		$cEntidad->setDuracion((isset($_POST["LSTDuracion"]) && $_POST["LSTDuracion"] != "") ? $_POST["LSTDuracion"] : "");	$cEntidad->setBusqueda(constant("STR_DURACION"), (isset($_POST["LSTDuracion"]) && $_POST["LSTDuracion"] != "" ) ? $_POST["LSTDuracion"] : "");
		$cEntidad->setBajaLog((isset($_POST["LSTBajaLog"]) && $_POST["LSTBajaLog"] != "") ? $_POST["LSTBajaLog"] : "");	$cEntidad->setBusqueda(constant("STR_BAJA_LOG"), (isset($_POST["LSTBajaLog"]) && $_POST["LSTBajaLog"] != "" ) ? $_POST["LSTBajaLog"] : "");
		$cEntidad->setBajaLogHast((isset($_POST["LSTBajaLogHast"]) && $_POST["LSTBajaLogHast"] != "") ? $_POST["LSTBajaLogHast"] : "");	$cEntidad->setBusqueda(constant("STR_BAJA_LOG") . " " . constant("STR_HASTA"), (isset($_POST["LSTBajaLogHast"]) && $_POST["LSTBajaLogHast"] != "" ) ? $_POST["LSTBajaLogHast"] : "");
		$cEntidad->setFecAlta((isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "") ? $_POST["LSTFecAlta"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA"), (isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "" ) ? $conn->UserDate($_POST["LSTFecAlta"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecAltaHast((isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "") ? $_POST["LSTFecAltaHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "" ) ? $conn->UserDate($_POST["LSTFecAltaHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecMod((isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "") ? $_POST["LSTFecMod"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_MODIFICACION"), (isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "" ) ? $conn->UserDate($_POST["LSTFecMod"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecModHast((isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "") ? $_POST["LSTFecModHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_MODIFICACION") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "" ) ? $conn->UserDate($_POST["LSTFecModHast"],constant("USR_FECHA"),false) : "");
		global $comboUSUARIOS;
		$cEntidad->setUsuAlta((isset($_POST["LSTUsuAlta"]) && $_POST["LSTUsuAlta"] != "") ? $_POST["LSTUsuAlta"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO_DE_ALTA"), (isset($_POST["LSTUsuAlta"]) && $_POST["LSTUsuAlta"] != "" ) ? $comboUSUARIOS->getDescripcionCombo($_POST["LSTUsuAlta"]) : "");
		$cEntidad->setUsuMod((isset($_POST["LSTUsuMod"]) && $_POST["LSTUsuMod"] != "") ? $_POST["LSTUsuMod"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO_DE_MODIFICACION"), (isset($_POST["LSTUsuMod"]) && $_POST["LSTUsuMod"] != "" ) ? $comboUSUARIOS->getDescripcionCombo($_POST["LSTUsuMod"]) : "");
		$cEntidad->setOrderBy((!empty($_POST["LSTOrderBy"])) ? $_POST["LSTOrderBy"] : "fecMod");	$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), (!empty($_POST["LSTOrderBy"])) ? $_POST["LSTOrderBy"] : "fecMod");
		$cEntidad->setOrder((!empty($_POST["LSTOrder"])) ? $_POST["LSTOrder"] : "DESC");	$cEntidad->setBusqueda(constant("STR_ORDEN"), (!empty($_POST["LSTOrder"])) ? $_POST["LSTOrder"] : "DESC");
		$cEntidad->setLineasPagina((!empty($_POST["LSTLineasPagina"]) && is_numeric($_POST["LSTLineasPagina"])) ? $_POST["LSTLineasPagina"] : constant("CNF_LINEAS_PAGINA"));
		$_POST["LSTLineasPagina"] = $cEntidad->getLineasPagina();
		return $cEntidad;
	}
	
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidadItems(&$cItems){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cItems->setIdItem((isset($_POST["fIdItem"])) ? $_POST["fIdItem"] : "");
		$cItems->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
		$cItems->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"])) ? $_POST["fCodIdiomaIso2"] : "");
		$cItems->setEnunciado((isset($_POST["fEnunciado"])) ? $_POST["fEnunciado"] : "");
		$cItems->setDescripcion((isset($_POST["fDescripcionItem"])) ? $_POST["fDescripcionItem"] : "");
		$cItems->setPath1((isset($_POST["fPath1"])) ? $_POST["fPath1"] : "");
		$cItems->setPath2((isset($_POST["fPath2"])) ? $_POST["fPath2"] : "");
		$cItems->setPath3((isset($_POST["fPath3"])) ? $_POST["fPath3"] : "");
		$cItems->setPath4((isset($_POST["fPath4"])) ? $_POST["fPath4"] : "");
		$cItems->setCorrecto((isset($_POST["fCorrecto"])) ? $_POST["fCorrecto"] : "");
		$cItems->setOrden((isset($_POST["fOrden"])) ? $_POST["fOrden"] : "");
		$cItems->setBajaLog((isset($_POST["fBajaLog"])) ? $_POST["fBajaLog"] : "");
		$cItems->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cItems->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cItems->setUsuAlta($_cEntidadUsuarioTK->getUsuario());
		$cItems->setUsuMod($_cEntidadUsuarioTK->getUsuario());
		return $cItems;
	}
	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readListaItems(&$cItems){
		global $conn;
		global $cUtilidades;
		global $comboPRUEBAS;
		$cItems->setIdPrueba((isset($_POST["fIdPrueba"]) && $_POST["fIdPrueba"] != "") ? $_POST["fIdPrueba"] : "");	$cItems->setBusqueda(constant("STR_PRUEBA"), (isset($_POST["fIdPrueba"]) && $_POST["fIdPrueba"] != "" ) ? $comboPRUEBAS->getDescripcionCombo($_POST["fIdPrueba"]) : "");
		global $comboWI_IDIOMAS;
		$cItems->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"]) && $_POST["fCodIdiomaIso2"] != "") ? $_POST["fCodIdiomaIso2"] : "");	$cItems->setBusqueda(constant("STR_IDIOMA"), (isset($_POST["fCodIdiomaIso2"]) && $_POST["fCodIdiomaIso2"] != "" ) ? $comboWI_IDIOMAS->getDescripcionCombo($_POST["fCodIdiomaIso2"]) : "");
		$cItems->setOrderBy((!empty($_POST["LSTOrderItBy"])) ? $_POST["LSTOrderItBy"] : "orden");	$cItems->setBusqueda(constant("STR_ORDENAR_POR"), (!empty($_POST["LSTOrderItBy"])) ? $_POST["LSTOrderItBy"] : "orden");
		$cItems->setOrder((!empty($_POST["LSTOrderIt"])) ? $_POST["LSTOrderIt"] : "ASC");	$cItems->setBusqueda(constant("STR_ORDEN"), (!empty($_POST["LSTOrderIt"])) ? $_POST["LSTOrderIt"] : "ASC");
		return $cItems;
	}
		/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidadOpciones(&$cOpciones){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cOpciones->setIdOpcion((isset($_POST["fIdOpcion"])) ? $_POST["fIdOpcion"] : "");
		$cOpciones->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
		$cOpciones->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"])) ? $_POST["fCodIdiomaIso2"] : "");
		$cOpciones->setIdItem((isset($_POST["fIdItem"])) ? $_POST["fIdItem"] : "");
		$cOpciones->setDescripcion((isset($_POST["fDescripcion"])) ? $_POST["fDescripcion"] : "");
		$cOpciones->setCodigo((isset($_POST["fCodigo"])) ? $_POST["fCodigo"] : "");
		$cOpciones->setBajaLog((isset($_POST["fBajaLog"])) ? $_POST["fBajaLog"] : "");
		$cOpciones->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cOpciones->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cOpciones->setUsuAlta($_cEntidadUsuarioTK->getUsuario());
		$cOpciones->setUsuMod($_cEntidadUsuarioTK->getUsuario());
		return $cOpciones;
	}
	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readListaOpciones(&$cOpciones){
		global $conn;
		global $cUtilidades;
		global $comboPRUEBAS;
		$cOpciones->setIdPrueba((isset($_POST["fIdPrueba"]) && $_POST["fIdPrueba"] != "") ? $_POST["fIdPrueba"] : "");	$cOpciones->setBusqueda(constant("STR_PRUEBA"), (isset($_POST["fIdPrueba"]) && $_POST["LSTIdPrueba"] != "" ) ? $comboPRUEBAS->getDescripcionCombo($_POST["fIdPrueba"]) : "");
		global $comboWI_IDIOMAS;
		$cOpciones->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"]) && $_POST["fCodIdiomaIso2"] != "") ? $_POST["fCodIdiomaIso2"] : "");	$cOpciones->setBusqueda(constant("STR_IDIOMA"), (isset($_POST["LfCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "" ) ? $comboWI_IDIOMAS->getDescripcionCombo($_POST["fCodIdiomaIso2"]) : "");
		global $comboITEMS;
		$cOpciones->setIdItem((isset($_POST["fIdItem"]) && $_POST["fIdItem"] != "") ? $_POST["fIdItem"] : "");	$cOpciones->setBusqueda(constant("STR_ITEM"), (isset($_POST["fIdItem"]) && $_POST["LSTIdItem"] != "" ) ? $comboITEMS->getDescripcionCombo($_POST["fIdItem"]) : "");
		$cOpciones->setOrderBy((!empty($_POST["LSTOrderOpBy"])) ? $_POST["LSTOrderOpBy"] : "idOpcion");	$cOpciones->setBusqueda(constant("STR_ORDENAR_POR"), (!empty($_POST["LSTOrderOpBy"])) ? $_POST["LSTOrderOpBy"] : "idOpcion");
		$cOpciones->setOrder((!empty($_POST["LSTOrderOp"])) ? $_POST["LSTOrderOp"] : "ASC");	$cOpciones->setBusqueda(constant("STR_ORDEN"), (!empty($_POST["LSTOrderOp"])) ? $_POST["LSTOrderOp"] : "ASC");
		return $cOpciones;
	}
	
		/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidadEjemplos(&$cEjemplos){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEjemplos->setIdEjemplo((isset($_POST["fIdEjemplo"])) ? $_POST["fIdEjemplo"] : "");
		$cEjemplos->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
		$cEjemplos->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"])) ? $_POST["fCodIdiomaIso2"] : "");
		$cEjemplos->setEnunciado((isset($_POST["fEnunciado"])) ? $_POST["fEnunciado"] : "");
		$cEjemplos->setDescripcion((isset($_POST["fDescripcionItem"])) ? $_POST["fDescripcionItem"] : "");
		$cEjemplos->setPath1((isset($_POST["fPath1"])) ? $_POST["fPath1"] : "");
		$cEjemplos->setPath2((isset($_POST["fPath2"])) ? $_POST["fPath2"] : "");
		$cEjemplos->setPath3((isset($_POST["fPath3"])) ? $_POST["fPath3"] : "");
		$cEjemplos->setPath4((isset($_POST["fPath4"])) ? $_POST["fPath4"] : "");
		$cEjemplos->setCorrecto((isset($_POST["fCorrecto"])) ? $_POST["fCorrecto"] : "");
		$cEjemplos->setOrden((isset($_POST["fOrden"])) ? $_POST["fOrden"] : "");
		$cEjemplos->setBajaLog((isset($_POST["fBajaLog"])) ? $_POST["fBajaLog"] : "");
		$cEjemplos->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEjemplos->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEjemplos->setUsuAlta($_cEntidadUsuarioTK->getUsuario());
		$cEjemplos->setUsuMod($_cEntidadUsuarioTK->getUsuario());
		return $cEjemplos;
	}
	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readListaEjemplos(&$cEjemplos){
		global $conn;
		global $cUtilidades;
		global $comboPRUEBAS;
		$cEjemplos->setIdPrueba((isset($_POST["fIdPrueba"]) && $_POST["fIdPrueba"] != "") ? $_POST["fIdPrueba"] : "");	$cEjemplos->setBusqueda(constant("STR_PRUEBA"), (isset($_POST["fIdPrueba"]) && $_POST["fIdPrueba"] != "" ) ? $comboPRUEBAS->getDescripcionCombo($_POST["fIdPrueba"]) : "");
		global $comboWI_IDIOMAS;
		$cEjemplos->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"]) && $_POST["fCodIdiomaIso2"] != "") ? $_POST["fCodIdiomaIso2"] : "");	$cEjemplos->setBusqueda(constant("STR_IDIOMA"), (isset($_POST["fCodIdiomaIso2"]) && $_POST["fCodIdiomaIso2"] != "" ) ? $comboWI_IDIOMAS->getDescripcionCombo($_POST["fCodIdiomaIso2"]) : "");
		$cEjemplos->setOrderBy((!empty($_POST["LSTOrderEjBy"])) ? $_POST["LSTOrderEjBy"] : "orden");	$cEjemplos->setBusqueda(constant("STR_ORDENAR_POR"), (!empty($_POST["LSTOrderEjBy"])) ? $_POST["LSTOrderEjBy"] : "orden");
		$cEjemplos->setOrder((!empty($_POST["LSTOrderEj"])) ? $_POST["LSTOrderEj"] : "ASC");	$cEjemplos->setBusqueda(constant("STR_ORDEN"), (!empty($_POST["LSTOrderEj"])) ? $_POST["LSTOrderEj"] : "ASC");
		return $cEjemplos;
	}
		/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidadOpcionesEjemplos(&$cOpcionesEjemplos){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cOpcionesEjemplos->setIdOpcion((isset($_POST["fIdOpcion"])) ? $_POST["fIdOpcion"] : "");
		$cOpcionesEjemplos->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
		$cOpcionesEjemplos->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"])) ? $_POST["fCodIdiomaIso2"] : "");
		$cOpcionesEjemplos->setIdEjemplo((isset($_POST["fIdEjemplo"])) ? $_POST["fIdEjemplo"] : "");
		$cOpcionesEjemplos->setDescripcion((isset($_POST["fDescripcion"])) ? $_POST["fDescripcion"] : "");
		$cOpcionesEjemplos->setCodigo((isset($_POST["fCodigo"])) ? $_POST["fCodigo"] : "");
		$cOpcionesEjemplos->setBajaLog((isset($_POST["fBajaLog"])) ? $_POST["fBajaLog"] : "");
		$cOpcionesEjemplos->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cOpcionesEjemplos->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cOpcionesEjemplos->setUsuAlta($_cEntidadUsuarioTK->getUsuario());
		$cOpcionesEjemplos->setUsuMod($_cEntidadUsuarioTK->getUsuario());
		return $cOpcionesEjemplos;
	}
	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readListaOpcionesEjemplos(&$cOpcionesEjemplos){
		global $conn;
		global $cUtilidades;
		global $comboPRUEBAS;
		$cOpcionesEjemplos->setIdPrueba((isset($_POST["fIdPrueba"]) && $_POST["fIdPrueba"] != "") ? $_POST["fIdPrueba"] : "");	$cOpcionesEjemplos->setBusqueda(constant("STR_PRUEBA"), (isset($_POST["fIdPrueba"]) && $_POST["LSTIdPrueba"] != "" ) ? $comboPRUEBAS->getDescripcionCombo($_POST["fIdPrueba"]) : "");
		global $comboWI_IDIOMAS;
		$cOpcionesEjemplos->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"]) && $_POST["fCodIdiomaIso2"] != "") ? $_POST["fCodIdiomaIso2"] : "");	$cOpcionesEjemplos->setBusqueda(constant("STR_IDIOMA"), (isset($_POST["LfCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "" ) ? $comboWI_IDIOMAS->getDescripcionCombo($_POST["fCodIdiomaIso2"]) : "");
		global $comboITEMS;
		$cOpcionesEjemplos->setIdEjemplo((isset($_POST["fIdEjemplo"]) && $_POST["fIdEjemplo"] != "") ? $_POST["fIdEjemplo"] : "");	$cOpcionesEjemplos->setBusqueda(constant("STR_EJEMPLO"), (isset($_POST["fIdEjemplo"]) && $_POST["LSTIdEjemplo"] != "" ) ? $comboEJEMPLOS->getDescripcionCombo($_POST["fIdEjemplo"]) : "");
		$cOpcionesEjemplos->setOrderBy((!empty($_POST["LSTOrderOpEjBy"])) ? $_POST["LSTOrderOpEjBy"] : "idOpcion");	$cOpcionesEjemplos->setBusqueda(constant("STR_ORDENAR_POR"), (!empty($_POST["LSTOrderOpEjBy"])) ? $_POST["LSTOrderOpEjBy"] : "idOpcion");
		$cOpcionesEjemplos->setOrder((!empty($_POST["LSTOrderOpEj"])) ? $_POST["LSTOrderOpEj"] : "ASC");	$cOpcionesEjemplos->setBusqueda(constant("STR_ORDEN"), (!empty($_POST["LSTOrderOpEj"])) ? $_POST["LSTOrderOpEj"] : "ASC");
		return $cOpcionesEjemplos;
	}
	
	/*
	* "Lee" los parametros recibidos en el request, los asocia a
	* una determinada Entidad para borrar las imagenes seleccionadas.
	*/
	function quitaImgEjemplos(&$cItems, &$cItemsDB, $bBorrar= false){
		$bLlamada=false;
		if ($bBorrar){
			setBorradoRegistro();
		}
		if (!empty($_POST['cfPath1']) && strtoupper($_POST['cfPath1']) == 'ON'){
			$cItems->setPath1($_POST['cfPath1']);
			$bLlamada=true;
		}
		if (!empty($_POST['cfPath2']) && strtoupper($_POST['cfPath2']) == 'ON'){
			$cItems->setPath2($_POST['cfPath2']);
			$bLlamada=true;
		}
		if (!empty($_POST['cfPath3']) && strtoupper($_POST['cfPath3']) == 'ON'){
			$cItems->setPath3($_POST['cfPath3']);
			$bLlamada=true;
		}
		if (!empty($_POST['cfPath4']) && strtoupper($_POST['cfPath4']) == 'ON'){
			$cItems->setPath4($_POST['cfPath4']);
			$bLlamada=true;
		}
		if ($bLlamada){
			$cItemsDB->quitaImagen($cItems);
		}
	}
	/*
	* "Lee" los parametros recibidos en el request, los asocia a
	* una determinada Entidad para borrar las imagenes seleccionadas.
	*/
	function quitaImg(&$cEjemplos, &$cEjemplosDB, $bBorrar= false){
		$bLlamada=false;
		if ($bBorrar){
			setBorradoRegistro();
		}
		if (!empty($_POST['cfPath1']) && strtoupper($_POST['cfPath1']) == 'ON'){
			$cEjemplos->setPath1($_POST['cfPath1']);
			$bLlamada=true;
		}
		if (!empty($_POST['cfPath2']) && strtoupper($_POST['cfPath2']) == 'ON'){
			$cEjemplos->setPath2($_POST['cfPath2']);
			$bLlamada=true;
		}
		if (!empty($_POST['cfPath3']) && strtoupper($_POST['cfPath3']) == 'ON'){
			$cEjemplos->setPath3($_POST['cfPath3']);
			$bLlamada=true;
		}
		if (!empty($_POST['cfPath4']) && strtoupper($_POST['cfPath4']) == 'ON'){
			$cEjemplos->setPath4($_POST['cfPath4']);
			$bLlamada=true;
		}
		if ($bLlamada){
			$cEjemplosDB->quitaImagen($cEjemplos);
		}
	}
	/*
	* "Setea" el request, para el borrado de imagenes
	* cuando es un borrado del registro.
	*/
	function setBorradoRegistro(){
			$_POST['cfPath1'] = 'on';
			$_POST['cfPath2'] = 'on';
			$_POST['cfPath3'] = 'on';
			$_POST['cfPath4'] = 'on';
	}
		/*
	* "Lee" los parametros recibidos en el request, los asocia a
	* una determinada Entidad para borrar las imagenes seleccionadas.
	*/
	function quitaImgPruebas($cEntidad, $cEntidadDB, $bBorrar= false){
		$bLlamada=false;
		//echo $_POST['cfCapturaPantalla'];
		//echo "Entro";
		if ($bBorrar){
			setBorradoRegistroPruebas();
		}
		if (!empty($_POST['cfLogoPrueba']) && strtoupper($_POST['cfLogoPrueba']) == 'ON'){
			$cEntidad->setLogoPrueba($_POST['cfLogoPrueba']);
			$bLlamada=true;
		}
		if (!empty($_POST['cfCapturaPantalla']) && strtoupper($_POST['cfCapturaPantalla']) == 'ON'){
			$cEntidad->setCapturaPantalla($_POST['cfCapturaPantalla']);
			$bLlamada=true;
		}
		if (!empty($_POST['cfCabecera']) && strtoupper($_POST['cfCabecera']) == 'ON'){
			$cEntidad->setCabecera($_POST['cfCabecera']);
			$bLlamada=true;
		}
		if ($bLlamada){
			$cEntidadDB->quitaImagen($cEntidad);
		}
	}
	/*
	* "Setea" el request, para el borrado de imagenes
	* cuando es un borrado del registro.
	*/
	function setBorradoRegistroPruebas(){
			$_POST['cfLogoPrueba'] = 'on';
			$_POST['cfCapturaPantalla'] = 'on';
	}
?>