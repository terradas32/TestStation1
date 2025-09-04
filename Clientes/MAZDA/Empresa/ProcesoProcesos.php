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
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
	require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
	require_once(constant("DIR_WS_COM") . "Baremos/BaremosDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos/Baremos.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_WS_COM") . "Candidatos_back/Candidatos_backDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos_back/Candidatos_back.php");
	require_once(constant("DIR_WS_COM") . "Ficheros_carga/Ficheros_cargaDB.php");
	require_once(constant("DIR_WS_COM") . "Ficheros_carga/Ficheros_carga.php");
	require_once(constant("DIR_WS_COM") . "Correos/CorreosDB.php");
	require_once(constant("DIR_WS_COM") . "Correos/Correos.php");
	require_once(constant("DIR_WS_COM") . "Correos_proceso/Correos_procesoDB.php");
	require_once(constant("DIR_WS_COM") . "Correos_proceso/Correos_proceso.php");
	require_once(constant("DIR_WS_COM") . "Tipos_correos/Tipos_correosDB.php");
	require_once(constant("DIR_WS_COM") . "Tipos_correos/Tipos_correos.php");
	require_once(constant("DIR_WS_COM") . "Tipos_informes/Tipos_informesDB.php");
	require_once(constant("DIR_WS_COM") . "Tipos_informes/Tipos_informes.php");
	require_once(constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");

include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new ProcesosDB($conn);  // Entidad DB
	$cEntidad	= new Procesos();  // Entidad

	$cProcesoBaremosDB	= new Proceso_baremosDB($conn);  // Entidad DB
	$cProcesoInformesDB	= new Proceso_InformesDB($conn);  // Entidad DB
	$cProcesoPruebasDB	= new Proceso_pruebasDB($conn);  // Entidad DB
	$cPruebasDB	= new PruebasDB($conn);  // Entidad DB
	$cIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
	$cPruebas	= new Pruebas();  // Entidad DB
	$cBaremosDB = new BaremosDB($conn);
	$cCandidatosDB	= new CandidatosDB($conn);  // Entidad DB
	$cCandidatos_backDB	= new Candidatos_backDB($conn);  // Entidad DB
	$cFicheros_cargaDB	= new Ficheros_cargaDB($conn);  // Entidad DB
	$cCorreos_procesoDB	= new Correos_procesoDB($conn);  // Entidad DB
	$cCorreosDB	= new CorreosDB($conn);  // Entidad DBç
	$cTiposCorreosDB	= new Tipos_correosDB($conn);  // Entidad DB

	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	$sHijos = "";

	require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
	require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
	$cEmpresaPadre = new Empresas();
	$cEmpresaPadreDB = new EmpresasDB($conn);
	$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
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
	$comboEMPRESAS	= new Combo($conn,"fIdEmpresa","idEmpresa","nombre","Descripcion","empresas","",constant("SLC_OPCION"),"idEmpresa IN(" . $sHijos . ")","","nombre,orden");
	$comboTIPOS_CORREOS	= new Combo($conn,"fIdTipoCorreo","idTipoCorreo","nombre","Descripcion","tipos_correos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","fecMod");
	$comboPRUEBASGROUP	= new Combo($conn,"fIdPrueba","idPrueba",$conn->Concat("descripcion","' - '","nombre"),"Descripcion","pruebas","",constant("SLC_OPCION"),$sSQLPruebaIN . "AND bajaLog=0 AND listar=1","","","idprueba");
	$comboMODO_REALIZACION	= new Combo($conn,"fIdModoRealizacion","idModoRealizacion","descripcion","Descripcion","modo_realizacion","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","fecMod");
	$comboWI_USUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","");
	$comboBATERIAS	= new Combo($conn,"fIdBateria","idBateria","nombre","Descripcion","baterias","",constant("SLC_OPCION"), "cliente='MAZDA' AND visible=1","","","");
	$comboEMK_CHARSETS	= new Combo($conn,"fCodificacion","codigo",$conn->Concat("codigo", "' - '", "descripcion"),"Descripcion","emk_charsets","",constant("SLC_OPCION"),"","","fecMod DESC");

	/*
	echo('modo:' . $_POST['MODO']);
	die();
	*/

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
				$cEntidad->setIdProceso($newId);
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/ProcesoProcesos/mntpruebasa.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			$vaPruebas="";
			$vaPruebas = (isset($_POST['vaPruebas']) && !empty($_POST['vaPruebas'])) ? $_POST['vaPruebas'] : "";
			if($vaPruebas ==""){
//			if (!empty($vaPruebas)){
				if ($cEntidadDB->modificar($cEntidad))
				{
					$_POST['MODO']=constant("MNT_ALTA");
					include('Template/ProcesoProcesos/mntpruebasa.php');
				}else{
					?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
					$_POST['MODO']=constant("MNT_MODIFICAR");
					include('Template/ProcesoProcesos/mntprocesosa.php');
				}

			}else{
				include('Template/ProcesoProcesos/mntpruebasa.php');
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
			if (!empty($_POST['procesos_next_page']) && $_POST['procesos_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}
			$pager = new ADODB_Pager($conn,$sql,'procesos');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/ProcesoProcesos/mntprocesosl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setIdEmpresa($_cEntidadUsuarioTK->getIdEmpresa());
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/ProcesoProcesos/mntprocesosa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);

			$cCandidato = new Candidatos();

			$cCandidato->setIdEmpresa($cEntidad->getIdEmpresa());
			$cCandidato->setIdProceso($cEntidad->getIdProceso());
			$sqlInformados =  $cCandidatosDB->readListaInformados($cCandidato);

			$listaInformados = $conn->Execute($sqlInformados);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/ProcesoProcesos/mntprocesosa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/ProcesoProcesos/mntprocesos.php');
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
				if (!empty($_POST['procesos_next_page']) && $_POST['procesos_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'procesos');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/ProcesoProcesos/mntprocesosl.php');
			break;
		case constant("MNT_AGREGAPRUEBA"):
			$newIdPP="";
			if(isset($_POST['fBorra']) && $_POST['fBorra']!="")
			{
				$cProcesoPruebas	= new Proceso_pruebas();  // Entidad
				$cProcesoPruebas->setIdProceso($_POST['fIdProceso']);
				$cProcesoPruebas->setIdEmpresa($_POST['fIdEmpresa']);
				//Como hay que borrar por bateria completa, se borran TODAS
				//$cProcesoPruebas->setIdPrueba($_POST['fIdPrueba']);
				//$cProcesoPruebas->setCodIdiomaIso2($_POST['fIdioma']);
				if (!$cProcesoPruebasDB->borrar($cProcesoPruebas))
				{
					?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cProcesoPruebasDB->ver_errores();?>");</script><?php
				}else{
					$cProcesoBaremos = new Proceso_baremos();  // Entidad
					$cProcesoBaremos->setIdProceso($_POST['fIdProceso']);
					$cProcesoBaremos->setIdEmpresa($_POST['fIdEmpresa']);
					//Como hay que borrar por bateria completa, se borran TODAS
					//$cProcesoBaremos->setIdPrueba($_POST['fIdPrueba']);
					//$cProcesoBaremos->setIdBaremo((!empty($_POST['fIdBaremo'])) ? $_POST['fIdBaremo'] : "0");
					$cProcesoBaremos->setCodIdiomaIso2($_POST['fIdioma']);

					if (!$cProcesoBaremosDB->borrar($cProcesoBaremos)){
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cProcesoBaremosDB->ver_errores();?>");</script><?php
					}else{
						$cProcesoInformes = new Proceso_informes();  // Entidad
						$cProcesoInformes->setIdProceso($_POST['fIdProceso']);
						$cProcesoInformes->setIdEmpresa($_POST['fIdEmpresa']);
						//Como hay que borrar por bateria completa, se borran TODAS
						//$cProcesoInformes->setIdPrueba($_POST['fIdPrueba']);
						//$cProcesoInformes->setIdBaremo((!empty($_POST['fIdBaremo'])) ? $_POST['fIdBaremo'] : "0");
						//$cProcesoInformes->setCodIdiomaIso2($_POST['fIdioma']);

						if (!$cProcesoInformesDB->borrar($cProcesoInformes)){
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cProcesoInformesDB->ver_errores();?>");</script><?php
						}
					}
				}
			}
			if(isset($_POST['fAniade']) && $_POST['fAniade']!="")
			{
				if(isset($_POST['fIdBateria']) && $_POST['fIdBateria']!="")
				{
					//Sacamos las pruenas que componen la bateria en el orden especificado
					$sSQL = "SELECT * FROM baterias_pruebas WHERE idBateria=" . $_POST['fIdBateria'] . " ORDER BY orden ";
					$rsBateriasPruebas = $conn->Execute($sSQL);
					if ($rsBateriasPruebas->NumRows() <= 0){
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\nNo se han podido asignar las pruebas.");</script><?php
						break;
					}else{
						while (!$rsBateriasPruebas->EOF)
						{

/////////////////////////////////////
							$cProcesoPruebas	= new Proceso_pruebas();  // Entidad
							$cProcesoPruebas->setIdProceso($_POST['fIdProceso']);
							$cProcesoPruebas->setIdEmpresa($_POST['fIdEmpresa']);
							$cProcesoPruebas->setIdPrueba($rsBateriasPruebas->fields['idPrueba']);


							$cProcesoBaremos = new Proceso_baremos();  // Entidad
							$cProcesoBaremos->setIdProceso($_POST['fIdProceso']);
							$cProcesoBaremos->setIdEmpresa($_POST['fIdEmpresa']);
							$cProcesoBaremos->setIdPrueba($rsBateriasPruebas->fields['idPrueba']);
							$cProcesoBaremos->setIdBaremo((!empty($_POST['fIdBaremo'])) ? $_POST['fIdBaremo'] : "0");

							$cProcesoInformes = new Proceso_informes();  // Entidad
							$cProcesoInformes->setIdProceso($_POST['fIdProceso']);
							$cProcesoInformes->setIdEmpresa($_POST['fIdEmpresa']);
							$cProcesoInformes->setIdPrueba($rsBateriasPruebas->fields['idPrueba']);
							$cProcesoInformes->setIdBaremo((!empty($_POST['fIdBaremo'])) ? $_POST['fIdBaremo'] : "0");
							$cProcesoInformes->setCodIdiomaIso2($_POST['fIdioma']);

							$cProcesoInformes->setCodIdiomaInforme($_POST['fIdiomaInforme']);
			//				$cProcesoInformes->setIdTipoInforme($_POST['fIdTipoInforme']);
							$aInformes= array();
							for ($i=0, $max = 200; $i < $max; $i++){
								if(isset($_POST["fIdTipoInforme" . $i]) && !empty($_POST["fIdTipoInforme" . $i])){
									$aInformes[]=$_POST["fIdTipoInforme" . $i];
								}
							}	//Fin del for
							$sql = $cProcesoPruebasDB->readLista($cProcesoPruebas);
							$lPP = $conn->Execute($sql);
							if($lPP->recordCount()>0){
								?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\nEsa prueba ya está dada de alta para este proceso,\n si quieres darla de alta en otro idioma que el ya especificado, borrala de la lista de debajo.");</script><?php

							}else{
								$cProcesoPruebas->setCodIdiomaIso2($_POST['fIdioma']);
								$cProcesoPruebas->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
								$cProcesoPruebas->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());

								$cProcesoBaremos->setCodIdiomaIso2($_POST['fIdioma']);
								$cProcesoBaremos->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
								$cProcesoBaremos->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());

								$newIdPP = $cProcesoPruebasDB->insertar($cProcesoPruebas);
								$newIdPB = $cProcesoBaremosDB->insertar($cProcesoBaremos);

								$i=0;
								$cProcesoInformes->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
								$cProcesoInformes->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
//								while($i < count($aInformes)) {
//									$cProcesoInformes->setIdTipoInforme($aInformes[$i]);
									$cProcesoInformes->setIdTipoInforme($rsBateriasPruebas->fields['idTipoInforme']);

									$newIdPI = $cProcesoInformesDB->insertar($cProcesoInformes);
//									$i++;
//								}
							}
							$cProcesoPruebas	= new Proceso_pruebas();  // Entidad

							$cProcesoPruebas->setIdProceso($_POST['fIdProceso']);
							$cProcesoPruebas->setIdEmpresa($_POST['fIdEmpresa']);
							$cProcesoPruebas->setOrderBy("orden");
							$cProcesoPruebas->setOrder("ASC");
							$sqlProcPrueba = $cProcesoPruebasDB->readLista($cProcesoPruebas);

							$listaProcesoPruebas = $conn->Execute($sqlProcPrueba);

							$aPruebas = Array();

							$i=0;
							if($listaProcesoPruebas->recordCount()>0){
								while(!$listaProcesoPruebas->EOF){
									$cPrueba = new Pruebas();
									$cPrueba->setIdPrueba($listaProcesoPruebas->fields['idPrueba']);
									$cPrueba->setCodIdiomaIso2($listaProcesoPruebas->fields['codIdiomaIso2']);
									$cPrueba = $cPruebasDB->readEntidad($cPrueba);
									$cIdiomas = new Idiomas();
									$cIdiomas->setCodIso2($listaProcesoPruebas->fields['codIdiomaIso2']);
									$cIdiomas = $cIdiomasDB->readEntidad($cIdiomas);

									$cBaremosProc = new Proceso_Baremos();
									$cBaremosProc->setIdEmpresa($listaProcesoPruebas->fields['idEmpresa']);
									$cBaremosProc->setIdProceso($listaProcesoPruebas->fields['idProceso']);
									$cBaremosProc->setIdPrueba($listaProcesoPruebas->fields['idPrueba']);
									$cBaremosProc->setCodIdiomaIso2($listaProcesoPruebas->fields['codIdiomaIso2']);

									$sqlBar = $cProcesoBaremosDB->readLista($cBaremosProc);
									$listaBaremos = $conn->Execute($sqlBar);

									if($listaBaremos->recordCount()>0){
										while(!$listaBaremos->EOF){
											$cBaremosProc->setIdBaremo($listaBaremos->fields['idBaremo']);
											$listaBaremos->MoveNext();
										}
									}
									$cBaremosProc = $cProcesoBaremosDB->readEntidad($cBaremosProc);

									$cBaremos = new Baremos();
									$cBaremos->setIdPrueba($cBaremosProc->getIdPrueba());
									$cBaremos->setIdBaremo($cBaremosProc->getIdBaremo());

									$cBaremos = $cBaremosDB->readEntidad($cBaremos);


									$cProcesoInformesProc = new Proceso_informes();
									$cProcesoInformesProc->setIdEmpresa($listaProcesoPruebas->fields['idEmpresa']);
									$cProcesoInformesProc->setIdProceso($listaProcesoPruebas->fields['idProceso']);
									$cProcesoInformesProc->setCodIdiomaIso2($listaProcesoPruebas->fields['codIdiomaIso2']);
									$cProcesoInformesProc->setIdPrueba($listaProcesoPruebas->fields['idPrueba']);
									$cProcesoInformesProc->setIdBaremo($cBaremosProc->getIdBaremo());

									$sqlProcesoInformesProc = $cProcesoInformesDB->readLista($cProcesoInformesProc);
									$listaProcesoInformesProc = $conn->Execute($sqlProcesoInformesProc);

									$sCodIdiomaInforme = "";
									$sIdTiposInformes = "";
									$sDescTiposInformes = "";
									if($listaProcesoInformesProc->recordCount()>0){
										while(!$listaProcesoInformesProc->EOF){
											$sCodIdiomaInforme = $listaProcesoInformesProc->fields['codIdiomaInforme'];
											$sIdTiposInformes .= "," . $listaProcesoInformesProc->fields['idTipoInforme'];
											$listaProcesoInformesProc->MoveNext();
										}
									}
									if (!empty($sIdTiposInformes)){
										$sIdTiposInformes = substr($sIdTiposInformes, 1);
										$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sCodIdiomaInforme, false),"","fecMod");
										$sDescTiposInformes = $comboTIPOS_INFORMES->getDescripcionComboBR($sIdTiposInformes);
									}
									$cIdiomasInfomes = new Idiomas();
									$cIdiomasInfomes->setCodIso2($sCodIdiomaInforme);
									$cIdiomasInfomes = $cIdiomasDB->readEntidad($cIdiomasInfomes);

									$aPruebas[$i] =
									$cPrueba->getIdPrueba() . "," .
									$cPrueba->getCodIdiomaIso2() . "," .
									$cPrueba->getNombre() . "," .
									$cIdiomas->getNombre() . "," .
									$cBaremos->getIdBaremo() . "," .
									$cBaremos->getNombre() . "," .
									$sCodIdiomaInforme . "," .
									$cIdiomasInfomes->getNombre() . "," .
									str_replace(",", constant("CHAR_SEPARA"), $sIdTiposInformes) . "," .
									$sDescTiposInformes;


									$i++;
									$listaProcesoPruebas->MoveNext();
								}
							}

/////////////////////////////////////

							$rsBateriasPruebas->MoveNext();
						}
					}
				}else{
					$cProcesoPruebas	= new Proceso_pruebas();  // Entidad
					$cProcesoPruebas->setIdProceso($_POST['fIdProceso']);
					$cProcesoPruebas->setIdEmpresa($_POST['fIdEmpresa']);
					$cProcesoPruebas->setIdPrueba($_POST['fIdPrueba']);


					$cProcesoBaremos = new Proceso_baremos();  // Entidad
					$cProcesoBaremos->setIdProceso($_POST['fIdProceso']);
					$cProcesoBaremos->setIdEmpresa($_POST['fIdEmpresa']);
					$cProcesoBaremos->setIdPrueba($_POST['fIdPrueba']);
					$cProcesoBaremos->setIdBaremo((!empty($_POST['fIdBaremo'])) ? $_POST['fIdBaremo'] : "0");

					$cProcesoInformes = new Proceso_informes();  // Entidad
					$cProcesoInformes->setIdProceso($_POST['fIdProceso']);
					$cProcesoInformes->setIdEmpresa($_POST['fIdEmpresa']);
					$cProcesoInformes->setIdPrueba($_POST['fIdPrueba']);
					$cProcesoInformes->setIdBaremo((!empty($_POST['fIdBaremo'])) ? $_POST['fIdBaremo'] : "0");
					$cProcesoInformes->setCodIdiomaIso2($_POST['fIdioma']);

					$cProcesoInformes->setCodIdiomaInforme($_POST['fIdiomaInforme']);
	//				$cProcesoInformes->setIdTipoInforme($_POST['fIdTipoInforme']);
					$aInformes= array();
					for ($i=0, $max = 200; $i < $max; $i++){
						if(isset($_POST["fIdTipoInforme" . $i]) && !empty($_POST["fIdTipoInforme" . $i])){
							$aInformes[]=$_POST["fIdTipoInforme" . $i];
						}
					}	//Fin del for
					$sql = $cProcesoPruebasDB->readLista($cProcesoPruebas);
					$lPP = $conn->Execute($sql);
					if($lPP->recordCount()>0){
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\nEsa prueba ya está dada de alta para este proceso,\n si quieres darla de alta en otro idioma que el ya especificado, borrala de la lista de debajo.");</script><?php

					}else{
						$cProcesoPruebas->setCodIdiomaIso2($_POST['fIdioma']);
						$cProcesoPruebas->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
						$cProcesoPruebas->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());

						$cProcesoBaremos->setCodIdiomaIso2($_POST['fIdioma']);
						$cProcesoBaremos->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
						$cProcesoBaremos->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());

						$newIdPP = $cProcesoPruebasDB->insertar($cProcesoPruebas);
						$newIdPB = $cProcesoBaremosDB->insertar($cProcesoBaremos);

						$i=0;
						$cProcesoInformes->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
						$cProcesoInformes->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
						while($i < count($aInformes)) {
							$cProcesoInformes->setIdTipoInforme($aInformes[$i]);
							$newIdPI = $cProcesoInformesDB->insertar($cProcesoInformes);
							$i++;
						}
					}
					$cProcesoPruebas	= new Proceso_pruebas();  // Entidad

					$cProcesoPruebas->setIdProceso($_POST['fIdProceso']);
					$cProcesoPruebas->setIdEmpresa($_POST['fIdEmpresa']);
					$cProcesoPruebas->setOrderBy("orden");
					$cProcesoPruebas->setOrder("ASC");
					$sqlProcPrueba = $cProcesoPruebasDB->readLista($cProcesoPruebas);

					$listaProcesoPruebas = $conn->Execute($sqlProcPrueba);

					$aPruebas = Array();

					$i=0;
					if($listaProcesoPruebas->recordCount()>0){
						while(!$listaProcesoPruebas->EOF){
							$cPrueba = new Pruebas();
							$cPrueba->setIdPrueba($listaProcesoPruebas->fields['idPrueba']);
							$cPrueba->setCodIdiomaIso2($listaProcesoPruebas->fields['codIdiomaIso2']);
							$cPrueba = $cPruebasDB->readEntidad($cPrueba);
							$cIdiomas = new Idiomas();
							$cIdiomas->setCodIso2($listaProcesoPruebas->fields['codIdiomaIso2']);
							$cIdiomas = $cIdiomasDB->readEntidad($cIdiomas);

							$cBaremosProc = new Proceso_Baremos();
							$cBaremosProc->setIdEmpresa($listaProcesoPruebas->fields['idEmpresa']);
							$cBaremosProc->setIdProceso($listaProcesoPruebas->fields['idProceso']);
							$cBaremosProc->setIdPrueba($listaProcesoPruebas->fields['idPrueba']);
							$cBaremosProc->setCodIdiomaIso2($listaProcesoPruebas->fields['codIdiomaIso2']);

							$sqlBar = $cProcesoBaremosDB->readLista($cBaremosProc);
							$listaBaremos = $conn->Execute($sqlBar);

							if($listaBaremos->recordCount()>0){
								while(!$listaBaremos->EOF){
									$cBaremosProc->setIdBaremo($listaBaremos->fields['idBaremo']);
									$listaBaremos->MoveNext();
								}
							}
							$cBaremosProc = $cProcesoBaremosDB->readEntidad($cBaremosProc);

							$cBaremos = new Baremos();
							$cBaremos->setIdPrueba($cBaremosProc->getIdPrueba());
							$cBaremos->setIdBaremo($cBaremosProc->getIdBaremo());

							$cBaremos = $cBaremosDB->readEntidad($cBaremos);


							$cProcesoInformesProc = new Proceso_informes();
							$cProcesoInformesProc->setIdEmpresa($listaProcesoPruebas->fields['idEmpresa']);
							$cProcesoInformesProc->setIdProceso($listaProcesoPruebas->fields['idProceso']);
							$cProcesoInformesProc->setCodIdiomaIso2($listaProcesoPruebas->fields['codIdiomaIso2']);
							$cProcesoInformesProc->setIdPrueba($listaProcesoPruebas->fields['idPrueba']);
							$cProcesoInformesProc->setIdBaremo($cBaremosProc->getIdBaremo());

							$sqlProcesoInformesProc = $cProcesoInformesDB->readLista($cProcesoInformesProc);
							$listaProcesoInformesProc = $conn->Execute($sqlProcesoInformesProc);

							$sCodIdiomaInforme = "";
							$sIdTiposInformes = "";
							$sDescTiposInformes = "";
							if($listaProcesoInformesProc->recordCount()>0){
								while(!$listaProcesoInformesProc->EOF){
									$sCodIdiomaInforme = $listaProcesoInformesProc->fields['codIdiomaInforme'];
									$sIdTiposInformes .= "," . $listaProcesoInformesProc->fields['idTipoInforme'];
									$listaProcesoInformesProc->MoveNext();
								}
							}
							if (!empty($sIdTiposInformes)){
								$sIdTiposInformes = substr($sIdTiposInformes, 1);
								$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sCodIdiomaInforme, false),"","fecMod");
								$sDescTiposInformes = $comboTIPOS_INFORMES->getDescripcionComboBR($sIdTiposInformes);
							}
							$cIdiomasInfomes = new Idiomas();
							$cIdiomasInfomes->setCodIso2($sCodIdiomaInforme);
							$cIdiomasInfomes = $cIdiomasDB->readEntidad($cIdiomasInfomes);

							$aPruebas[$i] =
							$cPrueba->getIdPrueba() . "," .
							$cPrueba->getCodIdiomaIso2() . "," .
							$cPrueba->getNombre() . "," .
							$cIdiomas->getNombre() . "," .
							$cBaremos->getIdBaremo() . "," .
							$cBaremos->getNombre() . "," .
							$sCodIdiomaInforme . "," .
							$cIdiomasInfomes->getNombre() . "," .
							str_replace(",", constant("CHAR_SEPARA"), $sIdTiposInformes) . "," .
							$sDescTiposInformes;


							$i++;
							$listaProcesoPruebas->MoveNext();
						}
					}

				}
			}else{
				$cProcesoPruebas	= new Proceso_pruebas();  // Entidad

				$cProcesoPruebas->setIdProceso($_POST['fIdProceso']);
				$cProcesoPruebas->setIdEmpresa($_POST['fIdEmpresa']);
				$cProcesoPruebas->setOrderBy("orden");
				$cProcesoPruebas->setOrder("ASC");
				$sqlProcPrueba = $cProcesoPruebasDB->readLista($cProcesoPruebas);

				$listaProcesoPruebas = $conn->Execute($sqlProcPrueba);

				$aPruebas = Array();

				$i=0;
				if($listaProcesoPruebas->recordCount()>0){
					while(!$listaProcesoPruebas->EOF){
						$cPrueba = new Pruebas();
						$cPrueba->setIdPrueba($listaProcesoPruebas->fields['idPrueba']);
						$cPrueba->setCodIdiomaIso2($listaProcesoPruebas->fields['codIdiomaIso2']);
						$cPrueba = $cPruebasDB->readEntidad($cPrueba);
						$cIdiomas = new Idiomas();
						$cIdiomas->setCodIso2($listaProcesoPruebas->fields['codIdiomaIso2']);
						$cIdiomas = $cIdiomasDB->readEntidad($cIdiomas);

						$cBaremosProc = new Proceso_Baremos();
						$cBaremosProc->setIdEmpresa($listaProcesoPruebas->fields['idEmpresa']);
						$cBaremosProc->setIdProceso($listaProcesoPruebas->fields['idProceso']);
						$cBaremosProc->setIdPrueba($listaProcesoPruebas->fields['idPrueba']);
						$cBaremosProc->setCodIdiomaIso2($listaProcesoPruebas->fields['codIdiomaIso2']);

						$sqlBar = $cProcesoBaremosDB->readLista($cBaremosProc);
						$listaBaremos = $conn->Execute($sqlBar);

						if($listaBaremos->recordCount()>0){
							while(!$listaBaremos->EOF){
								$cBaremosProc->setIdBaremo($listaBaremos->fields['idBaremo']);
								$listaBaremos->MoveNext();
							}
						}
						$cBaremosProc = $cProcesoBaremosDB->readEntidad($cBaremosProc);

						$cBaremos = new Baremos();
						$cBaremos->setIdPrueba($cBaremosProc->getIdPrueba());
						$cBaremos->setIdBaremo($cBaremosProc->getIdBaremo());

						$cBaremos = $cBaremosDB->readEntidad($cBaremos);

						$cProcesoInformesProc = new Proceso_informes();
						$cProcesoInformesProc->setIdEmpresa($listaProcesoPruebas->fields['idEmpresa']);
						$cProcesoInformesProc->setIdProceso($listaProcesoPruebas->fields['idProceso']);
						$cProcesoInformesProc->setCodIdiomaIso2($listaProcesoPruebas->fields['codIdiomaIso2']);
						$cProcesoInformesProc->setIdPrueba($listaProcesoPruebas->fields['idPrueba']);
						$cProcesoInformesProc->setIdBaremo($cBaremosProc->getIdBaremo());

						$sqlProcesoInformesProc = $cProcesoInformesDB->readLista($cProcesoInformesProc);
						$listaProcesoInformesProc = $conn->Execute($sqlProcesoInformesProc);

						$sCodIdiomaInforme = "";
						$sIdTiposInformes = "";
						$sDescTiposInformes = "";
						if($listaProcesoInformesProc->recordCount()>0){
							while(!$listaProcesoInformesProc->EOF){
								$sCodIdiomaInforme = $listaProcesoInformesProc->fields['codIdiomaInforme'];
								$sIdTiposInformes .= "," . $listaProcesoInformesProc->fields['idTipoInforme'];
								$listaProcesoInformesProc->MoveNext();
							}
						}
						if (!empty($sIdTiposInformes)){
							$sIdTiposInformes = substr($sIdTiposInformes, 1);
							$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sCodIdiomaInforme, false),"","fecMod");
							$sDescTiposInformes = $comboTIPOS_INFORMES->getDescripcionComboBR($sIdTiposInformes);
						}
						$cIdiomasInfomes = new Idiomas();
						$cIdiomasInfomes->setCodIso2($sCodIdiomaInforme);
						$cIdiomasInfomes = $cIdiomasDB->readEntidad($cIdiomasInfomes);

						$aPruebas[$i] =
						$cPrueba->getIdPrueba() . "," .
						$cPrueba->getCodIdiomaIso2() . "," .
						$cPrueba->getNombre() . "," .
						$cIdiomas->getNombre() . "," .
						$cBaremos->getIdBaremo() . "," .
						$cBaremos->getNombre() . "," .
						$sCodIdiomaInforme . "," .
						$cIdiomasInfomes->getNombre() . "," .
						str_replace(",", constant("CHAR_SEPARA"), $sIdTiposInformes) . "," .
						$sDescTiposInformes;

						$i++;
						$listaProcesoPruebas->MoveNext();
					}
				}
			}
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/ProcesoProcesos/pruebasasignadas.php');
			break;
		case constant("MNT_LISTAIDIOMAS"):
			$sMensaje = "";
			$aIdiomas[]="";
			$cBaremos = new Baremos();
			$ii=0;
			$i=0;
			if(isset($_POST['fIdPrueba']) && !empty($_POST['fIdPrueba'])){
				$cPruebas=  new Pruebas();
				$cPruebas->setIdPrueba($_POST['fIdPrueba']);
				$cPruebas->setBajaLog("0");
				$cPruebas->setBajaLogHast("0");
				$sqlPruebas= $cPruebasDB->readLista($cPruebas);
				$listaPruebas = $conn->Execute($sqlPruebas);
				while(!$listaPruebas->EOF){
					$cIdiomas = new Idiomas();
					$cIdiomas->setCodIso2($listaPruebas->fields['codIdiomaIso2']);
					$cIdiomas = $cIdiomasDB->readEntidad($cIdiomas);

					$aIdiomas[$i] = $listaPruebas->fields['codIdiomaIso2'] . "," . $cIdiomas->getNombre();
					$i++;
					$listaPruebas->MoveNext();
				}

				$cBaremos->setIdPrueba($_POST['fIdPrueba']);

			}else{
				$cBaremos->setIdPrueba("-5");
			}
			$bPintaBaremo=true;
			//Comprobamos si es de tipo prisma (Personalidad), ya que este tipo no tiene baremo por prueba,
			//Si no por escalas
			$cEscalas_items=  new Escalas_items();
			$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
			$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
			$sqlEscalas_items= $cEscalas_itemsDB->readLista($cEscalas_items);
			$rsEscalas_items = $conn->Execute($sqlEscalas_items);
			//////////////////////
			if($rsEscalas_items->recordCount() > 0){
				$bPintaBaremo=false;
			}
			$sqlBaremos = $cBaremosDB->readLista($cBaremos);
			$listaBaremos = $conn->Execute($sqlBaremos);
			if(isset($_POST['fIdPrueba']) && $_POST['fIdPrueba']!=""){
				if($listaBaremos->recordCount()==0){
					$sMensaje = constant("MSG_SIN_BAREMO_ASIGNADO");
				}
			}
			//Para los informes
//			$cTipos_informesDB = new Tipos_informesDB($conn);
			$cInformes_pruebasDB = new Informes_pruebasDB($conn);
			$sMensajeInforme = "";
			$aIdiomasInformes[]="";
			$cTipos_informes = new Tipos_informes();
			if(isset($_POST['fIdPrueba']) && $_POST['fIdPrueba']!=""){
				$cInformes_pruebas=  new Informes_pruebas();
				$cInformes_pruebas->setIdPrueba($_POST['fIdPrueba']);
				$sqlInformes_pruebas= $cInformes_pruebasDB->readLista($cInformes_pruebas);
				$sqlInformes_pruebas.=" GROUP BY codIdiomaIso2";
//				echo $sqlInformes_pruebas;
				$listaInformes_pruebas = $conn->Execute($sqlInformes_pruebas);

				while(!$listaInformes_pruebas->EOF){
					$cIdiomas = new Idiomas();
					$cIdiomas->setCodIso2($listaInformes_pruebas->fields['codIdiomaIso2']);
					$cIdiomas = $cIdiomasDB->readEntidad($cIdiomas);

					$aIdiomasInformes[$ii] = $listaInformes_pruebas->fields['codIdiomaIso2'] . "," . $cIdiomas->getNombre();
					$ii++;
					$listaInformes_pruebas->MoveNext();
				}

			}
			//Fin Informes
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/ProcesoProcesos/checksidiomas.php');
			break;
		case constant("MNT_ANIADECANDIDATOS"):

			$cProcesoPruebas	= new Proceso_pruebas();  // Entidad

			$cProcesoPruebas->setIdProceso($_POST['fIdProceso']);
			$cProcesoPruebas->setIdEmpresa($_POST['fIdEmpresa']);
			$cProcesoPruebas->setOrderBy("orden");
			$cProcesoPruebas->setOrder("ASC");
			$sqlProcPrueba = $cProcesoPruebasDB->readLista($cProcesoPruebas);

			$listaProcesoPruebas = $conn->Execute($sqlProcPrueba);

			$aPruebas = Array();

			$i=0;
			if($listaProcesoPruebas->recordCount() > 0)
			{

				$sTipoAlta = (!empty($_POST['fTipoAlta'])) ? $_POST['fTipoAlta'] : "";
				if(empty($sTipoAlta)){
					include('Template/ProcesoProcesos/mntcandidatosa.php');
					break;
				}else{
					if($sTipoAlta==1){
						include('Template/ProcesoProcesos/cargamasiva.php');
						break;
					}
					if($sTipoAlta==2){
						include('Template/ProcesoProcesos/altamanual.php');
						break;
					}
				}
			}else{
//				$_POST['MODO']=constant("MNT_CONSULTAR");
//				$_POST['ORIGEN'] = constant("MNT_LISTAR");
				$cEntidad = readEntidad($cEntidad);
				$vaPruebas="";
				$vaPruebas = (isset($_POST['vaPruebas']) && !empty($_POST['vaPruebas'])) ? $_POST['vaPruebas'] : "";
				if($vaPruebas ==""){
					?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ASIG_PRUEBA");?>");</script><?php
					include('Template/ProcesoProcesos/mntpruebasa.php');
				}
			}

		case constant("MNT_LISTACANDIDATOS"):

			if(isset($_POST['fBorra']) && $_POST['fBorra']!=""){
				if ($_POST['fIdCandidato'] == "*"){ //Borrar todos
					$cCandidatos	= new Candidatos();  // Entidad
					$cCandidatos->setIdProceso($_POST['fIdProceso']);
					$cCandidatos->setIdEmpresa($_POST['fIdEmpresa']);
					//Saco la lista de candidatos para la empresa proceso
					$sSQLCandidatos = $cCandidatosDB->readLista($cCandidatos);
					$rsCandidatos = $conn->Execute($sSQLCandidatos);
					while(!$rsCandidatos->EOF){
						$cCandidatos->setIdCandidato($rsCandidatos->fields['idCandidato']);
						if (!$cCandidatosDB->borrar($cCandidatos))
						{
							?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cCandidatosDB->ver_errores();?>");</script><?php
						}
						$rsCandidatos->MoveNext();
					}
				}else{
					$cCandidatos	= new Candidatos();  // Entidad
					$cCandidatos->setIdProceso($_POST['fIdProceso']);
					$cCandidatos->setIdEmpresa($_POST['fIdEmpresa']);
					$cCandidatos->setIdCandidato($_POST['fIdCandidato']);
					if (!$cCandidatosDB->borrar($cCandidatos))
					{
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cCandidatosDB->ver_errores();?>");</script><?php
					}
				}
			}
			if(isset($_POST['fAniade']) && $_POST['fAniade']!="")
			{
				//Para MAZDA al añadir el primer candidato se le descuentan 300 unidades
				// 1º Miramos si ya hay un candidatos
				$cCandi	= new Candidatos();  // Entidad
				$cCandi->setIdProceso($_POST['fIdProceso']);
				$cCandi->setIdEmpresa($_POST['fIdEmpresa']);
				$sql = $cCandidatosDB->readLista($cCandi);
				$lTotCan = $conn->Execute($sql);
				switch ($lTotCan->recordCount())
				{
					case 0:
						//Es el primero que se da de alta, se descuentan 300 Unidades.
						//Descontamos
						$cEmpr = new Empresas();
						$cEmprDB = new EmpresasDB($conn);
						$cEmpr->setIdEmpresa($_POST['fIdEmpresa']);
						$cEmpr = $cEmprDB->readEntidad($cEmpr);
						$iDonglesDeEmpresa	=	$cEmpr->getDongles();
						$iDonglesADescontar	=	300;
						$bPrepago = $cEmpr->getPrepago();
						if (!empty($bPrepago)){
							//Es de prepago hay que verificar los dongles
							if ($iDonglesADescontar > $iDonglesDeEmpresa){
								//Hay que descontar mas dongles que los que tiene cargados la empresa,
								//Se lanza mensaje de error.
								$sMSG_JS_ERROR="" . $cEmpr->getNombre() . " - " . constant("STR_NO_DISPONE_DE_SUFICIENTES_UNIDADES_PARA_EFECTUAR_LA_OPERACION") . ".\\n";
								$sMSG_JS_ERROR.="\\t" . constant("STR_UNIDADES_DISPONIBLES") . ": " . $iDonglesDeEmpresa . " " . constant("STR_UNIDADES") . ".\\n";
								$sMSG_JS_ERROR.="\\t" . constant("STR_UNIDADES_A_CONSUMIR") . ": " . $iDonglesADescontar . " " . constant("STR_UNIDADES") . ".\\n\\n";
								$sMSG_JS_ERROR.="" . constant("STR_POR_FAVOR_RECARGUE_UN_MINIMO_DE") . ":\\n ";
								$sMSG_JS_ERROR.="\\t" . ($iDonglesADescontar - $iDonglesDeEmpresa) . " " . constant("STR_UNIDADES") . ".\\n ";
							?><script language="javascript" type="text/javascript">alert("<?php echo $sMSG_JS_ERROR;?>");</script><?php
								break;
							}else{
								//Introducimos en consumos y descontamos Unidades

							}
						}
						$cCandidatos	= new Candidatos();  // Entidad
						$cCandidatos->setIdProceso($_POST['fIdProceso']);
						$cCandidatos->setIdEmpresa($_POST['fIdEmpresa']);
						$cCandidatos->setMail($_POST['fMail']);

						$sql = $cCandidatosDB->readLista($cCandidatos);
						$lC = $conn->Execute($sql);
						if($lC->recordCount()>0){
							?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\nEste candidato ya está asignado a este proceso");</script><?php
						}else{
							$cCandidatos->setNombre($_POST['fNombre']);
							$cCandidatos->setApellido1($_POST['fApellido1']);
							$cCandidatos->setApellido2($_POST['fApellido2']);
							$cCandidatos->setDni($_POST['fDni']);
							$cCandidatos->setInformado("0");
							$cCandidatos->setFinalizado("0");
							$cCandidatos->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
							$cCandidatos->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());

							$newIdC = $cCandidatosDB->insertar($cCandidatos);
						}
						//Introducimos en consumos y descontamos Unidades
							$iDonglesDeEmpresa = $iDonglesDeEmpresa-300;
							$sSQL = "UPDATE empresas SET dongles= " . $iDonglesDeEmpresa . " WHERE idEmpresa=" . $cEmpr->getIdEmpresa();
							$conn->Execute($sSQL);

							//2º Miramos los datos del proceso
							require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
							require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
							$cProcesos = new Procesos();
							$cProcesosDB = new ProcesosDB($conn);

							$cProcesos->setIdEmpresa($cEmpr->getIdEmpresa());
							$cProcesos->setIdProceso($_POST['fIdProceso']);

							$cProcesos = $cProcesosDB->readEntidad($cProcesos);

							require_once(constant("DIR_WS_COM") . "Consumos/ConsumosDB.php");
							require_once(constant("DIR_WS_COM") . "Consumos/Consumos.php");
							$cConsumosDB = new ConsumosDB($conn);
							$cConsumos = new Consumos();
							$cConsumos->setIdEmpresa($cEmpr->getIdEmpresa());
							$cConsumos->setIdProceso($cProcesos->getIdProceso());
							$cConsumos->setIdCandidato($newIdC);
							$cConsumos->setCodIdiomaIso2("es");
							$cConsumos->setIdPrueba("0");
							$cConsumos->setCodIdiomaInforme("es");
							$cConsumos->setIdTipoInforme("1");
							$cConsumos->setIdBaremo("1");

							$cConsumos->setNomEmpresa($cEmpr->getNombre());
							$cConsumos->setNomProceso($cProcesos->getNombre());
							$cConsumos->setNomCandidato($_POST['fNombre']);
							$cConsumos->setApellido1($_POST['fApellido1']);
							$cConsumos->setApellido2($_POST['fApellido2']);
							$cConsumos->setDni($_POST['fDni']);
							$cConsumos->setMail($_POST['fMail']);
							$cConsumos->setNomPrueba("");
							$cConsumos->setNomInforme("Completo");
							$cConsumos->setNomBaremo("1");
							$cConsumos->setConcepto("Pack de 3 Candidatos");
							$cConsumos->setUnidades("300");
							$cConsumos->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
							$cConsumos->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
							$idConsumo = $cConsumosDB->insertar($cConsumos);
						break;
					case 1:
					case 2:
						//Ya hay 1 o 2 con lo que se permite el tercero.
						$cCandidatos	= new Candidatos();  // Entidad
						$cCandidatos->setIdProceso($_POST['fIdProceso']);
						$cCandidatos->setIdEmpresa($_POST['fIdEmpresa']);
						$cCandidatos->setMail($_POST['fMail']);

						$sql = $cCandidatosDB->readLista($cCandidatos);
						$lC = $conn->Execute($sql);
						if($lC->recordCount()>0){
							?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\nEste candidato ya está asignado a este proceso");</script><?php

						}else{
							$cCandidatos->setNombre($_POST['fNombre']);
							$cCandidatos->setApellido1($_POST['fApellido1']);
							$cCandidatos->setApellido2($_POST['fApellido2']);
							$cCandidatos->setDni($_POST['fDni']);
							$cCandidatos->setInformado("0");
							$cCandidatos->setFinalizado("0");
							$cCandidatos->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
							$cCandidatos->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());

							$newIdC = $cCandidatosDB->insertar($cCandidatos);
						}
						break;
					case 3:
						//ya hay 3, NO se permiten más altas
						?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\nSólo se permiten 3 Candidatos por proceso.");</script><?php
						break;
				} // end switch
			}	//FIN de aniade
			if(isset($_POST['fVolcar']) && $_POST['fVolcar']!=""){
				$cCandidatosBack	= new Candidatos_back();  // Entidad

				$idEmpresa = $_POST['fIdEmpresa'];
				$idProceso= $_POST['fIdProceso'];

				$cCandidatosBack->setIdEmpresa($idEmpresa);
				$cCandidatosBack->setIdProceso($idProceso);

				$sqlCandBack = $cCandidatos_backDB->readLista($cCandidatosBack);
				$listaCandBack = $conn->Execute($sqlCandBack);
				if($listaCandBack->recordCount()>0){
					while(!$listaCandBack->EOF){
						$cCandidatos = new Candidatos();

						$cCandidatos->setIdEmpresa($idEmpresa);
						$cCandidatos->setIdProceso($idProceso);
						$cCandidatos->setMail($listaCandBack->fields['mail']);

						$cCandidatos = $cCandidatosDB->consultaPorMail($cCandidatos);

						if($cCandidatos->getIdCandidato()!=""){
							$cCandidatos->setNombre($listaCandBack->fields['nombre']);
							$cCandidatos->setApellido1($listaCandBack->fields['apellido1']);
							$cCandidatos->setApellido2($listaCandBack->fields['apellido2']);

							$cCandidatosDB->modificar($cCandidatos);
						}else{
							$cCandidatos->setNombre($listaCandBack->fields['nombre']);
							$cCandidatos->setApellido1($listaCandBack->fields['apellido1']);
							$cCandidatos->setApellido2($listaCandBack->fields['apellido2']);
							$cCandidatos->setInformado("0");
							$cCandidatos->setFinalizado("0");
							$cCandidatos->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
							$cCandidatos->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());

							$newIdC = $cCandidatosDB->insertar($cCandidatos);
						}

						$listaCandBack->MoveNext();
					}
				}
			}
			$cCandidatos	= new Candidatos();  // Entidad

			$cCandidatos->setIdProceso($_POST['fIdProceso']);
			$cCandidatos->setIdEmpresa($_POST['fIdEmpresa']);
			$sqlCandi = $cCandidatosDB->readLista($cCandidatos);

			$listaCandidatos = $conn->Execute($sqlCandi);

			include('Template/ProcesoProcesos/mntcandidatosl.php');
			break;
		case constant("MNT_CARGACANDIDATOS"):

			$cFich = new Ficheros_carga();
			session_start();
			$IdCargaFichero = $_SESSION["IdCargaFichero" . constant("NOMBRE_SESSION")];
			$cFich->setIdFichero($IdCargaFichero);
			$cFich = $cFicheros_cargaDB->readEntidad($cFich);
/*
			$cFicheros->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
			$cFicheros->setOrderBy('fecAlta');
			$cFicheros->setOrder('DESC');

			$sql = $cFicheros_cargaDB->readLista($cFicheros);
			$listaFich = $conn->Execute($sql);

			$cFich = new Ficheros_carga();

			if($listaFich->recordCount()>0){
				$i=0;
				while(!$listaFich->EOF && $i<1){
					$cFich->setIdFichero($listaFich->fields['idFichero']);
					$cFich = $cFicheros_cargaDB->readEntidad($cFich);
					$i++;
					$listaFich->MoveNext();
				}
			}
*/
			$cEntidad = readEntidad($cEntidad);
//Inicio Pedro
			$_POST['fFichero']=$cFich->getFichero();
		    $_POST['fSrc_type'] = $cFich->getTipo();

		    $_POST['fSeparadorCampos']=";";
			if($_POST['fSeparadorCamposSel']==""){
		    	$_POST['fSeparadorCamposSel']=";";
		    }
		    $_POST['fCodificacion']="ISO-8859-1";
		    if($_POST['fCodificacionSel']==""){
		    	$_POST['fCodificacionSel']="ISO-8859-1";
		    }

		    $_POST['fEntrecomillado']='"';
			   if($_POST['fEntrecomilladoSel']==""){
		    	$_POST['fEntrecomilladoSel']='"';
		    }

		    $_POST['fCabeceras']="0";
			if($_POST['fCabecerasSel']==""){
		    	$_POST['fCabecerasSel']="0";
		    }
//Fin Pedro
/*			if(!$cCandidatos_backDB->importar($cFich->getFichero() , $cEntidad, $cFich)){
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cCandidatos_backDB->ver_errores();?>");</script><?php
				include('Template/ProcesoProcesos/cargamasiva.php');
			}else{
				include('Template/ProcesoProcesos/resumen.php');
			}
*/
		    include('Template/ProcesoProcesos/previsualizar.php');
			break;
		case constant("MNT_CARGACANDIDATOS_DEFINICION"):
			include('Template/ProcesoProcesos/definirCampos.php');
			break;
		case constant("MNT_CARGACANDIDATOS_FINALIZAR"):
			$newId	= $_POST['fIdFichero'];
			$iImportados	= 0;
			if (!empty($newId)){
				$cEntidad = readEntidad($cEntidad);
//				$cEntidad = $cEntidadDB->readEntidad($cEntidad);
				$cCandidatos_backDB = new Candidatos_backDB($conn);
				$idGrupos = "";

				$aRespuesta = $cCandidatos_backDB->importarSuscriptoresCSV($_POST['fCampos'], $_POST['fFichero'], $_POST['fSrc_type'], $_POST['fSeparadorCampos'], $_POST['fCodificacion'], $_POST['fEntrecomillado'], $_POST['fCabeceras'], $idGrupos, $cEntidad);
			}
//			if (!empty($iImportados)){
//				?><script language="javascript" type="text/javascript">alert("<?php echo sprintf(constant("CONF_ALTA_VARIOS"), $aRespuesta['Importados'], $aRespuesta['Total']);?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
//
//				$cEntidad	= new Candidatos_back();  // Entidad
//				$cEntidad->setOrderBy("fecMod");
//				$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
//				$cEntidad->setOrder("DESC");
//				$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
//				$_POST["LSTOrderBy"] = "fecMod";
//				$_POST["LSTOrder"] = "DESC";
//				$_POST['MODO']    = constant("MNT_ALTA");
//				include('Template/ProcesoProcesos/previsualizar.php');
//			}else{
//				$_POST['MODO']=constant("MNT_LISTAR");
//				include('Template/ProcesoProcesos/previsualizar.php');
//			}
//			include('Template/ProcesoProcesos/definirCampos.php');
			?><script language="javascript" type="text/javascript">
				listacandidato();
			</script><?php

			break;
		case constant("MNT_ESCOGECORREOS"):
			include('Template/ProcesoProcesos/mntcorreosa.php');
			break;
		case constant("MNT_LISTACORREOS"):

			$cCorreosProceso = new Correos_proceso();
			$cTiposCorreos = new Tipos_correos();

			if(isset($_POST['fBorra']) && $_POST['fBorra']!=""){
				$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresaAsig']);
				$cCorreosProceso->setIdProceso($_POST['fIdProcesoAsig']);
				$cCorreosProceso->setIdTipoCorreo($_POST['fIdTipoCorreoAsig']);
				$cCorreosProceso->setIdCorreo($_POST['fIdCorreoAsig']);

				if(!$cCorreos_procesoDB->borrar($cCorreosProceso)){
					?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cCorreos_procesoDB->ver_errores();?>");</script><?php
				}

				$cCorreosProceso = new Correos_proceso();
				$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresaAsig']);
				$cCorreosProceso->setIdProceso($_POST['fIdProcesoAsig']);
			}else{
				$sAccion = (!empty($_POST['fAccion'])) ? $_POST['fAccion'] : "";
				//echo "<br />-->" . $sAccion;
				switch ($sAccion)
				{
					case "1":	//Añadir al proceso
						//Verificamos que no tenga dos correos con el mismo
						//tipo para esa empresa, proceso.
						$cCorreosProceso = new Correos_proceso();
						$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresa']);
						$cCorreosProceso->setIdProceso($_POST['fIdProceso']);
						$cCorreosProceso->setIdTipoCorreo($_POST['fIdTipoCorreo']);
						$sqlValida =	$cCorreos_procesoDB->readLista($cCorreosProceso);
						$rsValida = $conn->Execute($sqlValida);
						if ($rsValida->NumRows() > 0){
							?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\nYa ha incluido un correo del tipo: <?php echo $comboTIPOS_CORREOS->getDescripcionCombo($_POST['fIdTipoCorreo']);?>.\nNo se ha incluido en el proceso.");</script><?php
							break;
						}
						//Verificamos que si es de tipo confirmación,
						//previamente ha introducido uno de tipo envio
						if ($cCorreosProceso->getIdTipoCorreo() == 2)
						{
							$cCorreosProceso = new Correos_proceso();
							$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresa']);
							$cCorreosProceso->setIdProceso($_POST['fIdProceso']);
							$cCorreosProceso->setIdTipoCorreo(1);	//Envio
							$sqlValida =	$cCorreos_procesoDB->readLista($cCorreosProceso);
							$rsValida = $conn->Execute($sqlValida);
							if ($rsValida->NumRows() <= 0){
								?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\nAntes de incluir un correo de tipo Confirmación debe incluir uno de tipo Envio.\nNo se ha incluido en el proceso.");</script><?php
								break;
							}else{
								//verificamos que el contenido del correo
								//lleva el tag de @acceso_password@
								$sCuerpo = $_POST['fCuerpoNew'];
								$sLiteral = "@acceso_password@";
								if (strpos($sCuerpo, $sLiteral)===FALSE){
									?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\nUn correo de Confirmación debe tener en el cuerpo del mensaje la etiqueta '@acceso_password@'.\nNo se ha incluido en el proceso.");</script><?php
									break;
								}
							}
						}
						$cCorreosProceso = new Correos_proceso();
						$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresa']);
						$cCorreosProceso->setIdProceso($_POST['fIdProceso']);
						$cCorreosProceso->setIdTipoCorreo($_POST['fIdTipoCorreo']);
						$cCorreosProceso->setIdCorreo($_POST['fIdCorreo']);
						$cCorreosProceso->setAsunto($_POST['fAsuntoNew']);
						$cCorreosProceso->setDescripcion($_POST['fDescripcionNew']);
						$cCorreosProceso->setCuerpo($_POST['fCuerpoNew']);
						$cCorreosProceso->setNombre($_POST['fNombreNew']);
						$cCorreosProceso->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
						$cCorreosProceso->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());

						$cCorreos_procesoDB->insertar($cCorreosProceso);

						$cCorreosProceso = new Correos_proceso();
						$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresa']);
						$cCorreosProceso->setIdProceso($_POST['fIdProceso']);
						break;
					case "2":	//Guardar como Plantilla
						$cCorreos = new Correos();

						$cCorreos->setIdTipoCorreo($_POST['fIdTipoCorreo']);
						$cCorreos->setIdEmpresa($_POST['fIdEmpresa']);
						$cCorreos->setAsunto($_POST['fAsuntoNew']);
						$cCorreos->setDescripcion($_POST['fDescripcionNew']);
						$cCorreos->setCuerpo($_POST['fCuerpoNew']);
						$cCorreos->setNombre($_POST['fNombreNew']);
						//verificamos que el contenido del correo
						//lleva el tag de @acceso_password@
						if ($cCorreos->getIdTipoCorreo() == 2)
						{
							$sCuerpo = $_POST['fCuerpoNew'];
							$sLiteral = "@acceso_password@";
							if (strpos($sCuerpo, $sLiteral)===FALSE){
								?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\nUn correo de Confirmación debe tener en el cuerpo del mensaje la etiqueta '@acceso_password@'.\nNo se ha incluido como plantilla.");</script><?php
								break;
							}
						}
						$cCorreos->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
						$cCorreos->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());

						$cCorreosDB->insertar($cCorreos);
						$cCorreos_procesoDB->insertar($cCorreosProceso);

						$cCorreosProceso = new Correos_proceso();
						$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresa']);
						$cCorreosProceso->setIdProceso($_POST['fIdProceso']);
						break;
					case "3":	//Guardar como Plantilla y añadir al proceso
						$cCorreosProceso = new Correos_proceso();
						$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresa']);
						$cCorreosProceso->setIdProceso($_POST['fIdProceso']);
						$cCorreosProceso->setIdTipoCorreo($_POST['fIdTipoCorreo']);
						$sqlValida =	$cCorreos_procesoDB->readLista($cCorreosProceso);
						$rsValida = $conn->Execute($sqlValida);
						if ($rsValida->NumRows() > 0){
							?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\nYa ha incluido un correo del tipo: <?php echo $comboTIPOS_CORREOS->getDescripcionCombo($_POST['fIdTipoCorreo']);?>.\nNo se ha incluido en el proceso.");</script><?php
							break;
						}
						//Verificamos que si es de tipo confirmación,
						//previamente ha introducido uno de tipo envio
						if ($cCorreosProceso->getIdTipoCorreo() == 2)
						{
							$cCorreosProceso = new Correos_proceso();
							$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresa']);
							$cCorreosProceso->setIdProceso($_POST['fIdProceso']);
							$cCorreosProceso->setIdTipoCorreo(1);	//Envio
							$sqlValida =	$cCorreos_procesoDB->readLista($cCorreosProceso);
							$rsValida = $conn->Execute($sqlValida);
							if ($rsValida->NumRows() <= 0){
								?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\nAntes de incluir un correo de tipo Confirmación debe incluir uno de tipo Envio.\nNo se ha incluido en el proceso ni plantilla.");</script><?php
								break;
							}else{
								//verificamos que el contenido del correo
								//lleva el tag de @acceso_password@
								$sCuerpo = $_POST['fCuerpoNew'];
								$sLiteral = "@acceso_password@";
								if (strpos($sCuerpo, $sLiteral)===FALSE){
									?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\nUn correo de Confirmación debe tener en el cuerpo del mensaje la etiqueta '@acceso_password@'.\nNo se ha incluido en el proceso ni plantilla.");</script><?php
									break;
								}
							}
						}
						$cCorreosProceso = new Correos_proceso();
						$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresa']);
						$cCorreosProceso->setIdProceso($_POST['fIdProceso']);
						$cCorreosProceso->setIdTipoCorreo($_POST['fIdTipoCorreo']);
						$cCorreosProceso->setIdCorreo($_POST['fIdCorreo']);
						$cCorreosProceso->setAsunto($_POST['fAsuntoNew']);
						$cCorreosProceso->setDescripcion($_POST['fDescripcionNew']);
						$cCorreosProceso->setCuerpo($_POST['fCuerpoNew']);
						$cCorreosProceso->setNombre($_POST['fNombreNew']);
						$cCorreosProceso->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
						$cCorreosProceso->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());

						if($cCorreos_procesoDB->insertar($cCorreosProceso)){

							$cCorreos = new Correos();

							$cCorreos->setIdTipoCorreo($_POST['fIdTipoCorreo']);
							$cCorreos->setIdEmpresa($_POST['fIdEmpresa']);
							$cCorreos->setAsunto($_POST['fAsuntoNew']);
							$cCorreos->setDescripcion($_POST['fDescripcionNew']);
							$cCorreos->setCuerpo($_POST['fCuerpoNew']);
							$cCorreos->setNombre($_POST['fNombreNew']);
							$cCorreos->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
							$cCorreos->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());

							$cCorreosDB->insertar($cCorreos);
						}
						$cCorreos_procesoDB->insertar($cCorreosProceso);

						$cCorreosProceso = new Correos_proceso();
						$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresa']);
						$cCorreosProceso->setIdProceso($_POST['fIdProceso']);
						break;
					case "4":
						$cCorreosProceso = new Correos_proceso();
						$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresa']);
						$cCorreosProceso->setIdProceso($_POST['fIdProceso']);
						$cCorreosProceso->setIdTipoCorreo($_POST['fIdTipoCorreo']);
						$cCorreosProceso->setIdCorreo($_POST['fIdCorreoNew']);

						$cCorreosProceso = $cCorreos_procesoDB->readEntidad($cCorreosProceso);

						$cCorreosProceso->setAsunto($_POST['fAsuntoNew']);
						$cCorreosProceso->setDescripcion($_POST['fDescripcionNew']);
						$cCorreosProceso->setCuerpo($_POST['fCuerpoNew']);
						$cCorreosProceso->setNombre($_POST['fNombreNew']);

						$cCorreos_procesoDB->modificar($cCorreosProceso);

						$cCorreosProceso = new Correos_proceso();
						$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresa']);
						$cCorreosProceso->setIdProceso($_POST['fIdProceso']);
						break;
					case "5":
						$cCorreosProceso = new Correos_proceso();
						$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresa']);
						$cCorreosProceso->setIdProceso($_POST['fIdProceso']);
						$cCorreosProceso->setIdTipoCorreo($_POST['fIdTipoCorreo']);
						$cCorreosProceso->setIdCorreo($_POST['fIdCorreoNew']);

						$cCorreosProceso = $cCorreos_procesoDB->readEntidad($cCorreosProceso);

						$cCorreosProceso->setAsunto($_POST['fAsuntoNew']);
						$cCorreosProceso->setDescripcion($_POST['fDescripcionNew']);
						$cCorreosProceso->setCuerpo($_POST['fCuerpoNew']);
						$cCorreosProceso->setNombre($_POST['fNombreNew']);

						if($cCorreos_procesoDB->modificar($cCorreosProceso)){

							$cCorreos = new Correos();

							$cCorreos->setIdTipoCorreo($_POST['fIdTipoCorreo']);
							$cCorreos->setAsunto($_POST['fAsuntoNew']);
							$cCorreos->setDescripcion($_POST['fDescripcionNew']);
							$cCorreos->setCuerpo($_POST['fCuerpoNew']);
							$cCorreos->setNombre($_POST['fNombreNew']);
							$cCorreos->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
							$cCorreos->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());

							$cCorreosDB->insertar($cCorreos);
						}
						$cCorreosProceso = new Correos_proceso();
						$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresa']);
						$cCorreosProceso->setIdProceso($_POST['fIdProceso']);
						break;
					default:
						$cCorreosProceso = new Correos_proceso();
						$cCorreosProceso->setIdEmpresa((!empty($_POST['fIdEmpresa'])) ? $_POST['fIdEmpresa'] : "");
						$cCorreosProceso->setIdProceso((!empty($_POST['fIdProceso'])) ? $_POST['fIdProceso'] : "");
						break;
				} // end switch
			}
			$sqlCP = $cCorreos_procesoDB->readLista($cCorreosProceso);
			//echo $sqlCP;
			$listaCP = $conn->Execute($sqlCP);

			include('Template/ProcesoProcesos/listacorreos.php');
			break;
		case constant("MNT_NUEVOCORREO"):

			if(isset($_POST['fConsulta']) && $_POST['fConsulta']!=""){

				$cCorreosProceso = new Correos_proceso();
				$cTiposCorreos = new Tipos_correos();

				$cCorreos = new Correos();

				$cCorreos->setIdTipoCorreo($_POST['fIdTipoCorreo']);
				$cCorreos->setIdCorreo($_POST['fIdCorreo']);

				$cCorreos = $cCorreosDB->readEntidad($cCorreos);

				include('Template/ProcesoProcesos/consultacorreo.php');
				break;
			}

			if(isset($_POST['fNuevo']) && $_POST['fNuevo']!=""){

				include('Template/ProcesoProcesos/correonuevo.php');
				break;
			}

			if(isset($_POST['fConsultaAsignados']) && $_POST['fConsultaAsignados']!=""){

				$cCorreosProceso = new Correos_proceso();
				$cTiposCorreos = new Tipos_correos();


				$cCorreosProceso->setIdEmpresa($_POST['fIdEmpresaAsig']);
				$cCorreosProceso->setIdProceso($_POST['fIdProcesoAsig']);
				$cCorreosProceso->setIdTipoCorreo($_POST['fIdTipoCorreoAsig']);
				$cCorreosProceso->setIdCorreo($_POST['fIdCorreoAsig']);

				$cCorreosProceso = $cCorreos_procesoDB->readEntidad($cCorreosProceso);

				include('Template/ProcesoProcesos/correoasignado.php');
				break;
			}
		default:
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
//			$_POST['MODO']    = constant("MNT_LISTAR");
//			include('Template/ProcesoProcesos/mntprocesos.php');
//			break;
//			$cEntidad = readLista($cEntidad);
			if ($cEntidad->getIdEmpresa() == ""){
					$cEntidad->setIdEmpresa($sHijos);
			}
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}else{
				if (!empty($_POST['procesos_next_page']) && $_POST['procesos_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'procesos');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/ProcesoProcesos/mntprocesosl.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdProceso((isset($_POST["fIdProceso"])) ? $_POST["fIdProceso"] : "");
		$cEntidad->setIdEmpresa((isset($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : "");
		$cEntidad->setNombre((isset($_POST["fNombre"])) ? $_POST["fNombre"] : "");
		$cEntidad->setDescripcion((isset($_POST["fDescripcion"])) ? $_POST["fDescripcion"] : "");
		$cEntidad->setObservaciones((isset($_POST["fObservaciones"])) ? $_POST["fObservaciones"] : "");

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

?>
