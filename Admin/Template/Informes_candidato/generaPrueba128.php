<?php
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversosDB.php");
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversos.php");
$cItems_inversosDB = new Items_inversosDB($conn);
$cItems_inversos = new Items_inversos();

		$cItems_inversos->setIdPrueba($_POST['fIdPrueba']);
		$cItems_inversos->setIdPruebaHast($_POST['fIdPrueba']);
		$sqlInversos = $cItems_inversosDB->readLista($cItems_inversos);
//		echo "<br />" . $sqlInversos;
		$listaInversos = $conn->Execute($sqlInversos);
		$nInversos = $listaInversos->recordCount();
		$aInversos = array();
		if($nInversos > 0){
			$i=0;
			while(!$listaInversos->EOF){
				$aInversos[$i] = $listaInversos->fields['idItem'];
				$i++;
				$listaInversos->MoveNext();
			}
		}
		$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false),"","fecMod");
		$sDescInforme = $comboTIPOS_INFORMES->getDescripcionCombo($_POST['fIdTipoInforme']);
		$sDescInforme=constant("STR_PRISMA_PERFIL_DE_COMPETENCIAS_128");
		// CÁLCULOS GLOBALES PARA ESCALAS,
		// Se hace fuera y los metemos en un array para
		// reutilizarlo en varias funciones
		$cEscalas_items=  new Escalas_items();
		$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
		$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
		$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
		//echo "<br />sqlEscalas_items::" . $sqlEscalas_items . "";
		$rsEscalas_items = $conn->Execute($sqlEscalas_items);
		$sBloques = "";
		while(!$rsEscalas_items->EOF){
			$sBloques .="," . $rsEscalas_items->fields['idBloque'];
			$rsEscalas_items->MoveNext();
		}
		//echo "<br />222-->sBloques::" . $sBloques;
		if (!empty($sBloques)){
			$sBloques = substr($sBloques,1);
		}
		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
		//echo "<br />" . $sqlBloques;
		$listaBloques = $conn->Execute($sqlBloques);
		$nBloques= $listaBloques->recordCount();
		$aPuntuaciones = array();
		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
				//echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		while(!$listaEscalas->EOF){
				        $cEscalas_items = new Escalas_items();
				        $cEscalas_items->setIdEscala($listaEscalas->fields['idEscala']);
				        $cEscalas_items->setIdEscalaHast($listaEscalas->fields['idEscala']);
				        $cEscalas_items->setIdBloque($listaEscalas->fields['idBloque']);
				        $cEscalas_items->setIdBloqueHast($listaEscalas->fields['idBloque']);
				        $cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
				        $cEscalas_items->setOrderBy("idItem");
				        $cEscalas_items->setOrder("ASC");
				        $sqlEscalas_items = $cEscalas_itemsDB->readLista($cEscalas_items);
//echo "<br />////////->sqlEscalas_items" . $sqlEscalas_items;
				        $listaEscalas_items = $conn->Execute($sqlEscalas_items);
				        $nEscalas_items =$listaEscalas_items->recordCount();

				        $iPd = 0;
				        if($nEscalas_items > 0)
								{
				        	while(!$listaEscalas_items->EOF){
				        		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();

				        		$cRespuestas_pruebas_items->setIdEmpresa($_POST['fIdEmpresa']);
								$cRespuestas_pruebas_items->setIdProceso($_POST['fIdProceso']);
								$cRespuestas_pruebas_items->setIdCandidato($_POST['fIdCandidato']);
								$cRespuestas_pruebas_items->setIdPrueba($_POST['fIdPrueba']);
								$cRespuestas_pruebas_items->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cRespuestas_pruebas_items->setIdItem($listaEscalas_items->fields['idItem']);

								$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);

				        		if(array_search($listaEscalas_items->fields['idItem'], $aInversos) === false){
									//MEJOR => 2 PEOR => 0 VACIO => 1
									switch ($cRespuestas_pruebas_items->getIdOpcion())
									{
										case '1':	// Mejor
											$iPd += 2;
											break;
										case '2':	// Peor
											$iPd += 0;
											break;
										default:	// Sin contestar opcion 0 en respuestas
											$iPd += 1;
											break;
									}
//					       			$iPd = $iPd + $cRespuestas_pruebas_items->getIdOpcion();
					       		}else{
//echo "<br />" . $listaEscalas_items->fields['idItem'];
					       			$iPd += getInversoCel16($cRespuestas_pruebas_items->getIdOpcion());
					       		}

								$listaEscalas_items->MoveNext();
				        	}

					        $cBaremos_resultado = new Baremos_resultados();
					        $cBaremos_resultado->setIdBaremo($_POST['fIdBaremo']);
					        $cBaremos_resultado->setIdPrueba($_POST['fIdPrueba']);
					        $cBaremos_resultado->setIdBloque($listaEscalas->fields['idBloque']);
					        $cBaremos_resultado->setIdEscala($listaEscalas->fields['idEscala']);

					        $sqlBaremos_resultado = $cBaremos_resultadoDB->readLista($cBaremos_resultado);
							//echo "<br />iPd:: " . $iPd . " - " . $sqlBaremos_resultado;
					        $listaBaremos_resultado = $conn->Execute($sqlBaremos_resultado);

					        $iPBaremada=0;
					        $nBaremos = $listaBaremos_resultado->recordCount();
					        if($nBaremos > 0){
					        	while(!$listaBaremos_resultado->EOF)
					        	{
					        		if($iPd <= $listaBaremos_resultado->fields['puntMax'] && $iPd >= $listaBaremos_resultado->fields['puntMin'])
					        		{
					        			$iPBaremada = 	$listaBaremos_resultado->fields['puntBaremada'];
					        		}
					        		$listaBaremos_resultado->MoveNext();
					        	}
					        }
					       	$sPosi = $listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'];
					       	$scolor="#000";
					       	if ($listaBloques->fields['idBloque'] >= "67"){
					       		$scolor="green";
					       	}
									//echo "<br /><span style='color:" . $scolor . "'>---------->[" . $sPosi . "][" . $listaBloques->fields['nombre'] . "-" . $listaEscalas->fields['nombre'] . "][PD:" . $iPd . "][PB:" . $iPBaremada . "]</span>";
					       	$aPuntuaciones[$sPosi] =  $iPBaremada;
				        }
				        $listaEscalas->MoveNext();
			 		}
			 	}
			 	$listaBloques->MoveNext();
			 }
		 }
	// FIN CALCULOS GLOBALES ESCALAS


		$cNivelesjerarquicos = new Nivelesjerarquicos();
		$cNivelesjerarquicos->setIdNivel($cCandidato->getIdNivel());
		$cNivelesjerarquicos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cNivelesjerarquicos = $cNivelesjerarquicosDB->readEntidad($cNivelesjerarquicos);

		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");

		@set_time_limit(0);
		ini_set("memory_limit","1024M");
//		ini_set("max_execution_time","600");
		//$comboPREFIJOS	= new Combo($aux,"fIdPrefijo","idPrefijo","prefijo","Descripcion","prefijos","","","","","");
		define ('_NEWPAGE', '<!--NewPage-->');
		$_HEADER = '';
		$sHtmlCab	= '';
		$sHtml		= '';
		$sHtmlFin	= '';
		//$aux			= $this->conn;

		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");

		$sHtmlInicio='
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/cel16_128/resetCSS.css"/>';
		$sHtmlInicio.= '<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/cel16_128/style.css"/>';
		$sHtmlInicio.='
					<title>Cel16 ASITUR</title>
					<style type="text/css">
					<!--
					-->
					</style>
				</head>
			<body>';
$sHtmlFin .='
	</body>
	</html>';

	//$sFechaCon = $this->convertir_fecha($cEntidadEmpresas->getFechaInscripcion());

	//$sFecha = explode(" " , $sFechaCon);
	$sHtmlCab .='<div class="cabecera">
					<table width="100%" border="0">
						<tr>
		    				<td class="nombre">
										<p class="textos">' . constant("STR_SR_A") . ' ' . $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
						    </td>
						    <td class="logo">
						    	<img src="'.constant("DIR_WS_GESTOR").'estilosInformes/cel16_128/img/logo-informe.jpg" title="logo"/>
						    </td>
						    <td class="fecha">
						        <p class="textos">' . date("d/m/Y") . '</p>
						    </td>
					    </tr>
				    </table>
				</div>
		';
		$_HEADERz='<div class="cabecera">
					<table>
						<tr>
		    				<td class="nombre">
						        <p class="textos">' . constant("STR_SR_A") . ' ' . $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
						    </td>
						    <td class="logo">
						    	<img src="" />
						    </td>
						    <td class="fecha">
						        <p class="textos">' . date("d/m/Y") . '</p>
						    </td>
					    </tr>
				    </table>
				</div>
		';
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("3");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);

		//PORTADA
		$sHtml.= '
			<div class="pagina portada">
		    	<img src="' . constant("DIR_WS_GESTOR") . 'graf/cel16_128/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"><!-- <img src="'.constant("DIR_WS_GESTOR") . 'estilosInformes/cel16_128/img/logo.jpg" /> --></h1>';
		$sHtml.= '<div id="txt_infome"><p>' . $sDescInforme . '</p></div>';
		$sHtml.= '<div id="txt_infome2"><p>CEL16</p></div>';
		$sHtml.= '<div id="txt_infome3"><p>Agente Telefónico</p></div>';
		$sHtml.= 		'<div id="txt_puntos_infome"><p>' . str_repeat(".", 41) . '</p></div>';
		$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_NOMBRE_APELLIDOS") . ':</strong> '. $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2() .'</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong> '.date("d/m/Y").'</p>
				</div>
		    	<!-- <h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2> -->
			</div>
			<!--FIN DIV PAGINA-->
			<hr>
			';
//		$sHtml.=	constant("_NEWPAGE");
		//FIN PORTADA
	    $aSQLPuntuacionesPPL = array();
	    $aSQLPuntuacionesC =  array();

		switch ($_POST['fIdTipoInforme'])
		{
			case(60);//Informe competencias
				$sHtml.= informeSintesisCompetencias($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2']);
				$sHtml.= informeCompetencias($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2']);
				break;

		}
		//print_r($aSQLPuntuacionesPPL);
// 		$sHtml.= '
// 			<div class="pagina portada" id="contraportada">
//     			<img id="imgContraportada" src="' . constant("DIR_WS_GESTOR") . 'graf/contraportada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
// 			</div>
// 			<!--FIN DIV PAGINA-->
// 		';

if (!isset($NOGenerarFICHERO_INFORME))
{
  if (!empty($sHtml))
	{
		$replace = array('@', '.');
//		$sNombre = $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" .$_POST['fIdTipoInforme'] . "_" . $cPruebas->getNombre();
		$sDirImg="imgInformes/";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");

		$_fichero = $spath . $sDirImg . $sNombre . ".html";
		//$cEntidad->chk_dir($spath . $sDirImg, 0777);

		if(is_file($_fichero)){
			unlink($_fichero);
		}
		error_log(utf8_decode($sHtmlInicio . $sHtml . $sHtmlFin), 3, $_fichero);
	}
	//Si ha pulsado PDF
	if ($_POST['MODO'] != constant("MNT_EXPORTA_HTML"))
	{
//		error_reporting(E_ALL);
//		ini_set("display_errors","1");
		if (ini_get("pcre.backtrack_limit") < 2000000) { ini_set("pcre.backtrack_limit",2000000); };
		@set_time_limit(0);
		@define("OUTPUT_FILE_DIRECTORY", $spath . $sDirImg);
//		echo "LLEGO PDF";exit;

		$header_html    = $_HEADER;

    $footer_html    =  mb_strtoupper("PEOPLE EXPERTS", 'UTF-8') . str_repeat(" ", 70) . "©CEL16 Psicólogos Empresariales y Asociados SA.";
		//$footer_html = $footer_html;
		include("generaDOMPDF.php");

		//$footer_html = $footer_html;

		//


	}
}
/******************************************************************
* Funciones para la generación del Informe
******************************************************************/

	function baremo_C($pd)
	{
		if ($pd <=120){ $baremo_C=1;}
		if ($pd >=121 && $pd <=139){$baremo_C=2;}
		if ($pd >=140 && $pd <=158){$baremo_C=3;}
		if ($pd >=159 && $pd <=177){$baremo_C=4;}
		if ($pd >=178 && $pd <=195){$baremo_C=5;}
		if ($pd >=196 && $pd <=214){$baremo_C=6;}
		if ($pd >=215 && $pd <=233){$baremo_C=7;}
		if ($pd >=234 && $pd <=252){$baremo_C=8;}
		if ($pd >=253 && $pd <=270){$baremo_C=9;}
		if ($pd >=271){ $baremo_C=10;}
		return $baremo_C;
	}

	//-------------------------------
	// Baremos para la segunda parte
	//-------------------------------
	//escala I: Estilo Impulsor antes Innovar - Arriesgar
	function baremo_celB1($pd){
		if ($pd <=1){	$baremo_celB1=1;	}
		if ($pd ==2){	$baremo_celB1=2;	}
		if ($pd ==3){	$baremo_celB1=3;	}
		if ($pd ==4){	$baremo_celB1=4;	}
		if ($pd ==5){	$baremo_celB1=5;	}
		if ($pd ==6){	$baremo_celB1=6;	}
		if ($pd ==7){	$baremo_celB1=7;	}
		if ($pd ==8){	$baremo_celB1=8;	}
		if ($pd ==9){	$baremo_celB1=9;	}
		if ($pd >=10){	$baremo_celB1=10;	}
		return $baremo_celB1;
	}

	//escala T: Estilo Teórico antes Teorizar - Sistematizar
	function baremo_celB2($pd){
		if ($pd <=1){	$baremo_celB2=1;	}
		if ($pd ==2){	$baremo_celB2=2;	}
		if ($pd ==3){	$baremo_celB2=3;	}
		if ($pd ==4){	$baremo_celB2=4;	}
		if ($pd ==5){	$baremo_celB2=5;	}
		if ($pd ==6){	$baremo_celB2=6;	}
		if ($pd ==7){	$baremo_celB2=7;	}
		if ($pd ==8){	$baremo_celB2=8;	}
		if ($pd ==9){	$baremo_celB2=9;	}
		if ($pd >=10){	$baremo_celB2=10;	}
		return $baremo_celB2;
	}

	//escala A: Estilo Analítico antes Analizar - Observar
	function baremo_celB3($pd){
		if ($pd <=1){	$baremo_celB3=1;	}
		if ($pd ==2){	$baremo_celB3=2;	}
		if ($pd ==3){	$baremo_celB3=3;	}
		if ($pd ==4){	$baremo_celB3=4;	}
		if ($pd ==5){	$baremo_celB3=5;	}
		if ($pd ==6){	$baremo_celB3=6;	}
		if ($pd ==7){	$baremo_celB3=7;	}
		if ($pd ==8){	$baremo_celB3=8;	}
		if ($pd ==9){	$baremo_celB3=9;	}
		if ($pd >=10){	$baremo_celB3=10;	}
		return $baremo_celB3;
	}

	//escala P: Estilo Operativo antes Practicar - Actuar
	function baremo_celB4($pd){
		if ($pd <=1){	$baremo_celB4=1;	}
		if ($pd ==2){	$baremo_celB4=2;	}
		if ($pd ==3){	$baremo_celB4=3;	}
		if ($pd ==4){	$baremo_celB4=4;	}
		if ($pd ==5){	$baremo_celB4=5;	}
		if ($pd ==6){	$baremo_celB4=6;	}
		if ($pd ==7){	$baremo_celB4=7;	}
		if ($pd ==8){	$baremo_celB4=8;	}
		if ($pd ==9){	$baremo_celB4=9;	}
		if ($pd >=10){	$baremo_celB4=10;	}
		return $baremo_celB4;
	}
	// Si llega MEJOR devolver 0
	// Si llega PEOR devolver 2
	// Si llega BLANCO devolver 1
	function getInversoCel16($valor){
		$inv=0;

		//MEJOR => 2 PEOR => 0 VACIO => 1
		switch ($valor)
		{
			case '1':	// Mejor
				$inv = 0;
				break;
			case '2':	// Peor
				$inv = 2;
				break;
			default:	// Sin contestar opcion 0 en respuestas
				$inv = 1;
				break;
		}
		return $inv;
	}

	function informeSintesisCompetencias($aPuntuaciones , $sHtmlCab, $idIdioma){

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $cRespuestas_pruebas_itemsBD;
		global $aInversos;
		global $cBaremos_resultadoDB;


		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);

		$cEscalas_items=  new Escalas_items();
		$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
		$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
		$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
		//echo "<br />-->informeSintesisCompetencias::sqlEscalas_items::" . $sqlEscalas_items;
		$rsEscalas_items = $conn->Execute($sqlEscalas_items);
		$sBloques = "";
		while(!$rsEscalas_items->EOF){
			$sBloques .="," . $rsEscalas_items->fields['idBloque'];
			$rsEscalas_items->MoveNext();
		}
		//echo "<br />1111-->informeSintesisCompetencias::sBloques::" . $sBloques;
		if (!empty($sBloques)){
			$sBloques = substr($sBloques,1);
		}
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($idIdioma);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
		$listaBloques = $conn->Execute($sqlBloques);


		$sHtml= '
			<div class="pagina">'. $sHtmlCab;

		$sHtml.='
				<div class="desarrollo">
					<h2 class="subtitulo">' . constant("STR_PRISMA_SINTESIS_DEL_PERFIL_DE_COMPETENCIAS_128") . '</h2>
        			<div class="caja" style="margin-bottom:40px;">
		            	<h3 class="encabezado">' . mb_strtoupper(constant("STR_INTRODUCCION"), 'UTF-8') . '</h3>
		            	<p class="textos">' . constant("STR_PRISMA_SINTESIS_DEL_PERFIL_DE_COMPETENCIAS_128_INTRO_P1") . '</p>
						<p class="textos">' . constant("STR_PRISMA_SINTESIS_DEL_PERFIL_DE_COMPETENCIAS_128_INTRO_P2") . '</p>
						<p class="textos">' . constant("STR_PRISMA_SINTESIS_DEL_PERFIL_DE_COMPETENCIAS_128_INTRO_P3") . '</p>
			          </div><!--FIN DIV CAJA-->
			          <table class="sintesis" border="0" cellspacing="0" cellpadding="0">';

		$nBloques= $listaBloques->recordCount();

		if($nBloques > 0){
			$sHtml.='
							<tr>
			                  <td colspan="7" style="background:#fff;"><h2 class="subtitulo">' . mb_strtoupper(constant("STR_COMPETENCIAS"), 'UTF-8') . '</h2></td>
			                </tr>
			         ';
			$sHtml.='
			                <tr>
			                  <td colspan="2" style="background:#6a6a6b;">&nbsp;</td>
			                  <td class="cel">' . constant("STR_105_AREA_CLAVE_DE_MEJORA_BR") . '</td>
			                  <td class="cel">' . constant("STR_105_AREA_DE_POTENCIAL_DESARROLLO") . '</td>
			                  <td class="cel">' . constant("STR_105_AREA_DE_DESARROLLO_2BR") . '</td>
			                  <td class="cel">' . constant("STR_105_AREA_DE_POTENCIAL_FORTALEZA") . '</td>
			                  <td class="cel">' . constant("STR_105_AREA_DE_FORTALEZA_2BR") . '</td>
			                </tr>';
			$iPBloque = 0;
			$iPGlobal= 0;
			$iPBloque1to5 = 0;
			$iPGlobal1to5= 0;
			while(!$listaBloques->EOF){
				$iPBloque=0;
				$iPBloque1to5 = 0;
				$cEscalas = new Escalas();
				$cEscalas->setCodIdiomaIso2($idIdioma);
				$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
				$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
				$cEscalas->setOrderBy("idEscala");
				$cEscalas->setOrder("ASC");
				$sqlEscalas = $cEscalasDB->readLista($cEscalas);
				//echo "<br />informeSintesisCompetencias::sqlEscalas:: " . $sqlEscalas;
				$listaEscalas = $conn->Execute($sqlEscalas);
				$nEscalas=$listaEscalas->recordCount();
				if($nEscalas > 0){
					while(!$listaEscalas->EOF){
						$cEsc_items=  new Escalas_items();
						$cEsc_itemsDB=  new Escalas_itemsDB($conn);
						$cEsc_items->setIdPrueba($_POST['fIdPrueba']);
						$cEsc_items->setIdBloque($listaBloques->fields['idBloque']);
						$cEsc_items->setIdEscala($listaEscalas->fields['idEscala']);
						$sqlEsc_items= $cEsc_itemsDB->readLista($cEsc_items);
						//echo "<br />-****->informeSintesisCompetencias::sqlEsc_items::" . $sqlEsc_items;
						$rsEsc_items = $conn->Execute($sqlEsc_items);
						if ($rsEsc_items->recordCount() > 0)
						{
							$iEscala = 0;
							if ($listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'] == "72-2"){
								//Escala inversa REDES DE COLABORACIÓN - AUTONOMIÍA
								//echo "<br />INV ->informeSintesisCompetencias::[" . $listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'] . "] ESCALA inv ANTES: " . $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
								//echo "<br />INV ->informeSintesisCompetencias::[" . $listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'] . "] ESCALA inv DESPUES: " . getInversoEscala($aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']]);
								$iEscala = getInversoEscala($aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']]);
							}else{
			 					$iEscala = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
							}
							$iPBloque +=$iEscala;
							$iPBloque1to5+=getEscala_1to5($iEscala);

							//echo "<br />[" . $listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'] . "] - " . $listaBloques->fields['nombre'] . " " . $listaEscalas->fields['nombre'] . " (<strong>" . $iEscala . "</strong>)";
				 			$iPGlobal += ($iEscala - 5.5) * ($iEscala - 5.5);
							//echo "<br />iPGlobal::($iEscala - 5.5) * ($iEscala - 5.5)" . $iPGlobal;
				 			$iPGlobal1to5 += (getEscala_1to5($iEscala) - 5.5) * (getEscala_1to5($iEscala) - 5.5);

						}
			 			$listaEscalas->MoveNext();
					}

					if ($listaBloques->fields['idBloque'] >= "90")
					{
							//Solo pintamos las competencias que son especificas de Asitur
						//echo "<br />" . $listaBloques->fields['nombre'] . " round((" . $iPBloque . "/" . $nEscalas . "),0) == <strong style='color:green'>" . round(($iPBloque / $nEscalas),0) . "</strong>";
						$iPBloque = round(($iPBloque / $nEscalas),0);
						$iPBloque1to5 = round(($iPBloque1to5 / $nEscalas),0);
						$listaEscalas->MoveFirst();

				        $sHtml.='
					        <tr>
			                  <td class="tablaTitu" style="text-align: left;padding-left: 5px;">' . mb_strtoupper($listaBloques->fields['nombre'], 'UTF-8') . '</td>
			                  <td class="descripcion"><p>' . $listaBloques->fields['descripcion'] . '</p></td>';
			 				//if($iPBloque==1 || $iPBloque==2){
			 				if($iPBloque1to5==1){
								//$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasBajo.JPG" alt="' . getEscala_1to5($iPBloque) . '" title="' . getEscala_1to5($iPBloque) . '" /></td>';
			 					$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasBajo.JPG" alt="' . $iPBloque1to5 . '" title="' . $iPBloque1to5 . '" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
					       	//if($iPBloque==3 || $iPBloque==4){
					       	if($iPBloque1to5==2){
								//$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasMB.JPG" alt="' . getEscala_1to5($iPBloque) . '" title="' . getEscala_1to5($iPBloque) . '" /></td>';
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasMB.JPG" alt="' . $iPBloque1to5 . '" title="' . $iPBloque1to5 . '" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
					       	//if($iPBloque==5 || $iPBloque==6){
					       	if($iPBloque1to5==3){
								//$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasMedio.JPG" alt="' . getEscala_1to5($iPBloque) . '" title="' . getEscala_1to5($iPBloque) . '" /></td>';
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasMedio.JPG" alt="' . $iPBloque1to5 . '" title="' . $iPBloque1to5 . '" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
					       	//if($iPBloque==7 || $iPBloque==8){
					       	if($iPBloque1to5==4){
								//$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasMA.JPG" alt="' . getEscala_1to5($iPBloque) . '" title="' . getEscala_1to5($iPBloque) . '" /></td>';
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasMA.JPG" alt="' . $iPBloque1to5 . '" title="' . $iPBloque1to5 . '" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
					       	//if($iPBloque==9 || $iPBloque==10){
					       	if($iPBloque1to5==5){
								//$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasAlto.JPG" alt="' . getEscala_1to5($iPBloque) . '" title="' . getEscala_1to5($iPBloque) . '" /></td>';
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasAlto.JPG" alt="' . $iPBloque1to5 . '" title="' . $iPBloque1to5 . '" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
		               	$sHtml.='
			               	</tr>';
					}

			 	}
			 	$listaBloques->MoveNext();
			 }
		 }

		 ////////////////
		 // Se toman las 16 escalas del CEL16 normal para la consistencia.
		 //$consistencia = baremo_C(number_format(sqrt($iPGlobal/16)*100 ,0));
//		 echo "<br />A----------->iPGlobal::" . $iPGlobal;
//		 echo "<br />A----------->number_format(sqrt(iPGlobal/16)*100 ,0)= " . number_format(sqrt($iPGlobal/16)*100 ,0);
		 $consistencia = baremo_C(number_format(sqrt($iPGlobal/16)*100 ,0));
//		 echo "<br />A----------->consistencia::" . $consistencia;
		 //$consistencia1to5 = baremo_C(number_format(sqrt($iPGlobal1to5/16)*100 ,0));
		 $consistencia1to5 = baremo_C(number_format(sqrt($iPGlobal1to5/16)*100 ,0));
		 ///////////////


		$sHtml.='
		<tr>
		<td colspan="7" class="celS" >&nbsp;</td>
		</tr>
			<tr>
			    <td align="center" class="tablaTitu" style="background: #FF8939;" colspan="3" >CONSISTENCIA. Grado de congruencia en las respuestas</td>
			    <td align="center" class="descripcion" colspan="4" ><span style="font-weight: bold;">' . $consistencia . '</span> (escala de 1 a 10)</td>
			    ';
		               	$sHtml.='
			               	</tr>';
         $sHtml.='
						</table>
					</div><!--FIN DIV DESARROLLO-->
				</div>
				<!--FIN DIV PAGINA-->';
		return $sHtml;
	}


	function informeCompetencias($aPuntuaciones , $sHtmlCab, $idIdioma){

		global $conn;

		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $cRespuestas_pruebas_itemsBD;
		global $aInversos;
		global $cBaremos_resultadoDB;
		global $cPruebas;
		global $cProceso;
		global $cRespPruebas;

		$sSQLExport = "";
		global $aSQLPuntuacionesPPL;
		global $aSQLPuntuacionesC;

		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);

		$cEscalas_items=  new Escalas_items();
		$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
		$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
		$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
		$rsEscalas_items = $conn->Execute($sqlEscalas_items);
		$sBloques = "";
		while(!$rsEscalas_items->EOF){
			$sBloques .="," . $rsEscalas_items->fields['idBloque'];
			$rsEscalas_items->MoveNext();
		}
		//echo "<br />1111-->sBloques::" . $sBloques;
		if (!empty($sBloques)){
			$sBloques = substr($sBloques,1);
		}
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($idIdioma);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
		$listaBloques = $conn->Execute($sqlBloques);


		$sHtml= '
			<div class="pagina">'. $sHtmlCab;

		$sHtml.='
				<div class="desarrollo">
					<h2 class="subtitulo">' . constant("STR_128_DETALLE DIMENSIONES_COMPETENCIAS") . '</h2>
        			<div class="caja" style="margin-bottom:30px;">
		            	<p class="textos">' . constant("STR_128_INFORME_ORIENTADO_A_COMPETENCIAS_INTRO_P1") . '</p>
			          </div><!--FIN DIV CAJA-->
			          <table class="sintesis" border="0" cellspacing="0" cellpadding="0">';

		$nBloques= $listaBloques->recordCount();

		if($nBloques > 0){
			$iPBloque = 0;
			$iPGlobal= 0;
			$iPBloque1to5 = 0;
			$iPGlobal1to5=0;
			while(!$listaBloques->EOF){
				$iPBloque=0;
				$iPBloque1to5=0;
				$cEscalas = new Escalas();
				$cEscalas->setCodIdiomaIso2($idIdioma);
				$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
				$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
				$cEscalas->setOrderBy("idEscala");
				$cEscalas->setOrder("ASC");
				$sqlEscalas = $cEscalasDB->readLista($cEscalas);
				//echo "<br />" . $sqlEscalas;
				$listaEscalas = $conn->Execute($sqlEscalas);
				$nEscalas=$listaEscalas->recordCount();


				if($nEscalas > 0){
					while(!$listaEscalas->EOF){
						$cEsc_items=  new Escalas_items();
						$cEsc_itemsDB=  new Escalas_itemsDB($conn);
						$cEsc_items->setIdPrueba($_POST['fIdPrueba']);
						$cEsc_items->setIdBloque($listaBloques->fields['idBloque']);
						$cEsc_items->setIdEscala($listaEscalas->fields['idEscala']);
						$sqlEsc_items= $cEsc_itemsDB->readLista($cEsc_items);
						//echo "<br />-****->sqlEsc_items::" . $sqlEsc_items;
						$rsEsc_items = $conn->Execute($sqlEsc_items);
						if ($rsEsc_items->recordCount() > 0)
						{

							$iEscala = 0;
							if ($listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'] == "72-2"){
								//Escala inversa REDES DE COLABORACIÓN - AUTONOMIÍA
								//echo "<br />[" . $listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'] . "] ESCALA inv ANTES: " . $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
								//echo "<br />[" . $listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'] . "] ESCALA inv DESPUES: " . getInversoEscala($aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']]);
								$iEscala = getInversoEscala($aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']]);
							}else{
				 				$iEscala = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
							}
							$iPBloque +=$iEscala;
							$iPBloque1to5+=getEscala_1to5($iEscala);
//							echo "<br />[" . $listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'] . "] - " . $listaBloques->fields['nombre'] . " " . $listaEscalas->fields['nombre'] . " (<strong>" . $iEscala . "</strong>)";
				 			$iPGlobal += ($iEscala - 5.5) * ($iEscala - 5.5);
//				 			echo "<br />iPGlobal::($iEscala - 5.5) * ($iEscala - 5.5)" . $iPGlobal;
				 			$iPGlobal1to5 += (getEscala_1to5($iEscala) - 5.5) * (getEscala_1to5($iEscala) - 5.5);
						}
			 			$listaEscalas->MoveNext();
					}
					if ($listaBloques->fields['idBloque'] >= "67")
					{	//Solo pintamos las competencias que son especificas de Asitur

						//echo "<br />" . $listaBloques->fields['nombre'] . " round((" . $iPBloque . "/" . $nEscalas . "),0) == <strong style='color:green'>" . round(($iPBloque / $nEscalas),0) . "</strong>";
						$iPBloque = round(($iPBloque / $nEscalas),0);
						$iPBloque1to5 = round(($iPBloque1to5 / $nEscalas),0);
						$listaEscalas->MoveFirst();

						// 					$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
						// 					$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("COMPETENCIAS", false) . "," . $conn->qstr($listaBloques->fields['idBloque'], false) . "," . $conn->qstr($listaBloques->fields['nombre'], false) . "," . $conn->qstr($listaBloques->fields['descripcion'], false) . "," . $conn->qstr(getEscala_1to5($iPBloque), false) . ",now());\n";
						// 					$aSQLPuntuacionesC[] = $sSQLExport;
						$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("COMPETENCIAS", false) . "," . $conn->qstr($listaBloques->fields['idBloque'], false) . "," . $conn->qstr($listaBloques->fields['nombre'], false) . "," . $conn->qstr($listaBloques->fields['descripcion'], false) . "," . $conn->qstr($iPBloque1to5, false) . ",now());\n";
						$aSQLPuntuacionesC[] = $sSQLExport;

						$sHtml.='
								<tr>
									<td colspan="2" style="background:#fff;">
										<h2 class="subtitulo" style="border-right: 0 #fff;line-height:32px; color:#475464">' . mb_strtoupper($listaBloques->fields['nombre'], 'UTF-8') . '</h2>
									</td>
									<td align="center" colspan="5" style="background:#fff;">
										<span class="textos" style="font-weight: normal;font-size: 11px;color: #595959;">Puntuación obtenida (escala 1 a 5 ): <strong style="font-weight: bold;color: #000;">' . $iPBloque1to5 . '</strong></span>
									</td>
				                </tr>
				         ';
						$sHtml.='
								<tr>
									<td colspan="2" style="background:#6a6a6b;">&nbsp;</td>
						            <td class="cel" height="25"><p>' . constant("STR_PRISMA_BAJO") . '</p></td>
					    	        <td class="cel" height="25"><p>' . constant("STR_PRISMA_ME_BA") . '</p></td>
					        	    <td class="cel" height="25"><p>' . constant("STR_PRISMA_MEDIO") . '</p></td>
					            	<td class="cel" height="25"><p>' . constant("STR_PRISMA_ME_AL") . '</p></td>
						            <td class="cel last" height="25"><p>' . constant("STR_PRISMA_ALTO") . '</p></td>
								</tr>';
						while(!$listaEscalas->EOF)
						{
							$iPEscala = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

							$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
							$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($listaBloques->fields['idBloque'], false) . "," . $conn->qstr($listaBloques->fields['nombre'], false) . "," . $conn->qstr($listaEscalas->fields['idEscala'], false) . "," . $conn->qstr($listaEscalas->fields['nombre'], false) . "," . $conn->qstr($listaEscalas->fields['descripcion'], false) . "," . $conn->qstr($iPEscala, false) . ",now());\n";
							$aSQLPuntuacionesPPL[] = $sSQLExport;

							$sHtml.='
							        <tr>
					                  <td class="tablaTitu" style="width: 130px;background: #595959;text-align: left;padding-left: 5px;">' . mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8') . '</td>
					                  <td class="descripcion" style="width: 300px;"><p>' . $listaEscalas->fields['descripcion'] . '</p></td>';

							if($iPEscala==1 || $iPEscala==2){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasBajo.JPG" alt="' . getEscala_1to5($iPEscala) . '" title="' . getEscala_1to5($iPEscala) . '" /></td>';
							}else{
								$sHtml.='<td class="celS">&nbsp;</td>';
							}
							if($iPEscala==3 || $iPEscala==4){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasMB.JPG" alt="' . getEscala_1to5($iPEscala) . '" title="' . getEscala_1to5($iPEscala) . '" /></td>';
							}else{
								$sHtml.='<td class="celS">&nbsp;</td>';
							}
							if($iPEscala==5 || $iPEscala==6){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasMedio.JPG" alt="' . getEscala_1to5($iPEscala) . '" title="' . getEscala_1to5($iPEscala) . '" /></td>';
							}else{
								$sHtml.='<td class="celS">&nbsp;</td>';
							}
							if($iPEscala==7 || $iPEscala==8){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasMA.JPG" alt="' . getEscala_1to5($iPEscala) . '" title="' . getEscala_1to5($iPEscala) . '" /></td>';
							}else{
								$sHtml.='<td class="celS">&nbsp;</td>';
							}
							if($iPEscala==9 || $iPEscala==10){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/cel16_128/graficasAlto.JPG" alt="' . getEscala_1to5($iPEscala) . '" title="' . getEscala_1to5($iPEscala) . '" /></td>';
							}else{
								$sHtml.='<td class="celS">&nbsp;</td>';
							}
							$sHtml.='
					               	</tr>';
							$listaEscalas->MoveNext();
						}
					}
				}
				$listaBloques->MoveNext();
			}
		}

		 ////////////////
		 // Se toman las 16 escalas del CEL16 normal para la consistencia.
		 //$consistencia = baremo_C(number_format(sqrt($iPGlobal/16)*100 ,0));
//		 echo "<br />A----------->iPGlobal::" . $iPGlobal;
//		 echo "<br />A----------->number_format(sqrt(iPGlobal/16)*100 ,0)= " . number_format(sqrt($iPGlobal/16)*100 ,0);
		 $consistencia = baremo_C(number_format(sqrt($iPGlobal/16)*100 ,0));
//		 echo "<br />A----------->consistencia::" . $consistencia;
		 //$consistencia1to5 = baremo_C(number_format(sqrt($iPGlobal1to5/16)*100 ,0));
		 $consistencia1to5 = baremo_C(number_format(sqrt($iPGlobal1to5/16)*100 ,0));
		 ///////////////


		$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
		//$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr(0, false) . "," . $conn->qstr(constant("STR_PRISMA_G_C"), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr(constant("STR_PRISMA_G_C"), false) . "," . $conn->qstr(constant("STR_PRISMA_STR_PRISMA_G_C_TXT"), false) . "," . $conn->qstr(getEscala_1to5($consistencia), false) . ",now());\n";
		$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr(0, false) . "," . $conn->qstr(constant("STR_PRISMA_G_C"), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr(constant("STR_PRISMA_G_C"), false) . "," . $conn->qstr(constant("STR_PRISMA_STR_PRISMA_G_C_TXT"), false) . "," . $conn->qstr($consistencia, false) . ",now());\n";
		$aSQLPuntuacionesPPL[] = $sSQLExport;

// 		$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
// 		$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr(0, false) . "," . $conn->qstr(constant("STR_PRISMA_STR_PRISMA_G_C_TXT"), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr(constant("STR_PRISMA_STR_PRISMA_G_C_TXT"), false) . "," . $conn->qstr(constant("STR_PRISMA_STR_PRISMA_G_C_TXT"), false) . "," . $conn->qstr(getEscala_1to5($consistencia), false) . ",now());\n";
// 		$aSQLPuntuacionesC[] = $sSQLExport;

		$sHtml.='
				</table>
			</div><!--FIN DIV DESARROLLO-->
		</div>
		<!--FIN DIV PAGINA-->';
		return $sHtml;
	}

	function getEscala_1to5($iEscala10){
		$iRetorno=0;
		if($iEscala10==1 || $iEscala10==2){
			$iRetorno= 1;
		}
		if($iEscala10==3 || $iEscala10==4){
			$iRetorno= 2;
		}
		if($iEscala10==5 || $iEscala10==6){
			$iRetorno= 3;
		}
		if($iEscala10==7 || $iEscala10==8){
			$iRetorno= 4;
		}
		if($iEscala10==9 || $iEscala10==10){
			$iRetorno= 5;
		}
		return $iRetorno;
	}

	function getInversoEscala($iEscala10){
		$iRetorno=0;
		if($iEscala10==1){
			$iRetorno= 10;
		}
		if($iEscala10==2){
			$iRetorno= 9;
		}
		if($iEscala10==3){
			$iRetorno= 8;
		}
		if($iEscala10==4){
			$iRetorno= 7;
		}
		if($iEscala10==5){
			$iRetorno= 6;
		}
		if($iEscala10==6){
			$iRetorno= 5;
		}
		if($iEscala10==7){
			$iRetorno= 4;
		}
		if($iEscala10==8){
			$iRetorno= 3;
		}
		if($iEscala10==9){
			$iRetorno= 2;
		}
		if($iEscala10==10){
			$iRetorno= 1;
		}
		return $iRetorno;
	}
/******************************************************************
* FIN Funciones para la generación del Informe
******************************************************************/
?>
