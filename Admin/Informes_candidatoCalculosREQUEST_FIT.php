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
	$_POST['MODO'] = $_REQUEST['MODO'];
	$_POST['fIdTipoInforme'] = $_REQUEST['fIdTipoInforme'];
	$_POST['fCodIdiomaIso2'] = $_REQUEST['fCodIdiomaIso2'];
	$_POST['fIdPrueba'] = $_REQUEST['fIdPrueba'];
	$_POST['fIdEmpresa'] = $_REQUEST['fIdEmpresa'];
	$_POST['fIdProceso'] = $_REQUEST['fIdProceso'];
	$_POST['fIdCandidato'] = $_REQUEST['fIdCandidato'];
	$_POST['fCodIdiomaIso2Prueba'] = $_REQUEST['fCodIdiomaIso2Prueba'];
	$_POST['fIdBaremo'] = $_REQUEST['fIdBaremo'];
	//$_sPATH_CONFIG = 'C:\xampp\htdocs/TestStation/Admin/';


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
	//echo('***modo:' . $_POST['MODO']);

	if (!isset($_POST["MODO"])){
		$_POST["MODO"] ="627";
	}
	switch ($_POST['MODO'])
	{
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

				$dTotalCoste=$cInformesPruebasTrf->getTarifa();

				$bDescargar=false;
				//echo "<br />Prepago::" . $_sPrepago;
				//echo "<br />Empresa::" . $cEmpresaDng->getIdEmpresa();
				//echo "<br />Dongles::" . $cEmpresaDng->getDongles();
				//echo "<br />Tarifa::" . $cInformesPruebasTrf->getTarifa();
				//echo "<br />_EmpresaLogada::" . $_EmpresaLogada;
				//echo "<br />EMPRESA_PE::" . constant("EMPRESA_PE");
				//echo "<br />HTTP_REFERER::" . $_SERVER["HTTP_REFERER"];
				$cCandidato = new Candidatos();
				$cCandidato->setIdEmpresa($_POST['fIdEmpresa']);
				$cCandidato->setIdProceso($_POST['fIdProceso']);
				$cCandidato->setIdCandidato($_POST['fIdCandidato']);

				$cCandidato =  $cCandidatosDB->readEntidad($cCandidato);

				$cPruebas = new Pruebas();
				$cPruebas->setIdPrueba($_POST['fIdPrueba']);
				$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
				$cPruebas = $cPruebasDB->readEntidad($cPruebas);
				if ($_EmpresaLogada == constant("EMPRESA_PE") || $sLlamada == "Candidato"){
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

							//Pasamos este parametro para que no genere fisicamente el fichero de informe
							//$NOGenerarFICHERO_INFORME=true;

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
									include('Template/Informes_candidato/generaPrueba_fit.php');

									//Para las de Personalidad
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

									
									break;
							}
						}
						$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Generado desde [" . $_SERVER['REMOTE_ADDR'] . "] [" . $sNombre . "][" . constant("MNT_EXPORTA") . "][" . $sLlamada . "]";
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}
				}
			break;
	} // end switch

	/**
	* Chequea si el directorio existe, si no, lo crea con atributos (chmod=777).
	* @param String destpath
	* @return boolean
	*/
	function chk_dir($path, $mode = 0777) : bool //creates directory tree recursively
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
