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

		// CÁLCULOS GLOBALES PARA ESCALAS,
		// Se hace fuera y los metemos en un array para
		// reutilizarlo en varias funciones
		$cEscalas_items=  new Escalas_items();
		$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
		$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
		$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
//		echo "<br />sqlEscalas_items::" . $sqlEscalas_items . "";
		$rsEscalas_items = $conn->Execute($sqlEscalas_items);
		$sBloques = "";
		while(!$rsEscalas_items->EOF){
			$sBloques .="," . $rsEscalas_items->fields['idBloque'];
			$rsEscalas_items->MoveNext();
		}
//		echo "<br />222-->sBloques::" . $sBloques;
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
//		echo "<br />" . $sqlBloques;
		$listaBloques = $conn->Execute($sqlBloques);
		$nBloques= $listaBloques->recordCount();
		$aPuntuaciones = array();
		$LnPag = 3;
		$aEI = array();
		if($nBloques > 0)
		{
			while(!$listaBloques->EOF)
			{
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
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
//						echo "<br />" . $sqlEscalas_items;
				        $listaEscalas_items = $conn->Execute($sqlEscalas_items);
				        $nEscalas_items =$listaEscalas_items->recordCount();

				        $iPd = 0;
				        $iCurr_page=1;
				        if($nEscalas_items > 0)
				        {
							$sEI = "";
							while(!$listaEscalas_items->EOF){
								$sEI .="," . $listaEscalas_items->fields['idItem'];
								$listaEscalas_items->MoveNext();
							}
							if (!empty($sEI)){
								$sEI = substr($sEI,1);
								$aEI = explode(",", $sEI);
							}

			        		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
			        		$cRespuestas_pruebas_items->setIdEmpresa($_POST['fIdEmpresa']);
							$cRespuestas_pruebas_items->setIdProceso($_POST['fIdProceso']);
							$cRespuestas_pruebas_items->setIdCandidato($_POST['fIdCandidato']);
							$cRespuestas_pruebas_items->setIdPrueba($_POST['fIdPrueba']);
							$cRespuestas_pruebas_items->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
							$sSQLRPIAux = $cRespuestas_pruebas_itemsBD->readLista($cRespuestas_pruebas_items);
//							echo "<br />TRATANDO::" . $listaBloques->fields['nombre'] . "->" . $listaEscalas->fields['nombre'] . " ITEMS DE ESCALA::(" . $sEI . ")";
							//48 es el numero de páginas 144 / 3 = 48
				        	for ($j = 0; $j < 48; $j++)
							{
								$iInicio = $j * 3;

								$str = " ORDER BY fecMod ASC LIMIT " . $iInicio . ", 3";
								$sSQLRPI = $sSQLRPIAux . $str;

//								echo "<br />Página::" . $iCurr_page;
//								echo "<br />SQL Paginado::" . $sSQLRPI;

								$vRPI = $conn->Execute($sSQLRPI);
								$iPd += $cUtilidades->getValorCalculadoPRUEBAS($vRPI, $aEI, $conn);
//								echo "<br >PD DE LA ESCALA::" . $iPd;
								$iCurr_page++;
							}

					        $cBaremos_resultado = new Baremos_resultados();
					        $cBaremos_resultado->setIdBaremo($_POST['fIdBaremo']);
					        $cBaremos_resultado->setIdPrueba($_POST['fIdPrueba']);
					        $cBaremos_resultado->setIdBloque($listaEscalas->fields['idBloque']);
					        $cBaremos_resultado->setIdEscala($listaEscalas->fields['idEscala']);

					        $sqlBaremos_resultado = $cBaremos_resultadoDB->readLista($cBaremos_resultado);
	//						echo "<br />iPd:: " . $iPd . " - " . $sqlBaremos_resultado;
					        $listaBaremos_resultado = $conn->Execute($sqlBaremos_resultado);

					        $iPBaremada=0;
					        $nBaremos = $listaBaremos_resultado->recordCount();
					        if($nBaremos > 0)
					        {
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
//					       	echo "<br />---------->[" . $sPosi . "][PD::" . $iPd . "][" . $iPBaremada . "]";
					       	$aPuntuaciones[$sPosi] =  $iPBaremada;
				        }
					    $listaEscalas->MoveNext();
			 		}
			 	}
			 	$listaBloques->MoveNext();
			 }
		 }
	// FIN CALCULOS GLOBALES ESCALAS

	//CALCULOS GLOBALES COMPETENCIAS
		$cBaremos_resultados_competenciasDB = new Baremos_resultados_competenciasDB($conn);
		$cTipos_competencias = new Tipos_competencias();
		$cTipos_competencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTipos_competencias->setIdPrueba($_POST['fIdPrueba']);
		$cTipos_competencias->setOrderBy("idTipoCompetencia");
		$cTipos_competencias->setOrder("ASC");
		$sqlTipos_competencias = $cTipos_competenciasDB->readLista($cTipos_competencias);
//      	echo "<br />-->" . $sqlTipos_competencias . "";
		$listaTipoCompetencia = $conn->Execute($sqlTipos_competencias);
		$nTCompetencias= $listaTipoCompetencia->recordCount();
		$aPuntuacionesCompetencias = array();
		if($nTCompetencias > 0){
			while(!$listaTipoCompetencia->EOF){

				$cCompetencias = new Competencias();
			 	$cCompetencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cCompetencias->setIdTipoCompetencia($listaTipoCompetencia->fields['idTipoCompetencia']);
			 	$cCompetencias->setIdTipoCompetenciaHast($listaTipoCompetencia->fields['idTipoCompetencia']);
			 	$cCompetencias->setIdPrueba($_POST['fIdPrueba']);
			 	$cCompetencias->setOrderBy("idCompetencia");
			 	$cCompetencias->setOrder("ASC");
			 	$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
//			 	echo "<br />" . $sqlCompetencias . "";
			 	$listaCompetencias = $conn->Execute($sqlCompetencias);
			 	$nCompetencias=$listaCompetencias->recordCount();
			 	if($nCompetencias > 0){
			 		while(!$listaCompetencias->EOF){

				        $cCompetencias_items = new Competencias_items();
				        $cCompetencias_items->setIdCompetencia($listaCompetencias->fields['idCompetencia']);
				        $cCompetencias_items->setIdCompetenciaHast($listaCompetencias->fields['idCompetencia']);
				        $cCompetencias_items->setIdTipoCompetencia($listaCompetencias->fields['idTipoCompetencia']);
				        $cCompetencias_items->setIdTipoCompetenciaHast($listaCompetencias->fields['idTipoCompetencia']);
				        $cCompetencias_items->setIdPrueba($_POST['fIdPrueba']);
				        $cCompetencias_items->setOrderBy("idItem");
				        $cCompetencias_items->setOrder("ASC");
				        $sqlCompetencias_items = $cCompetencias_itemsDB->readLista($cCompetencias_items);
//				       	echo "<br />" . $sqlCompetencias_items . "";
//				       	echo "<br />" . $listaCompetencias->fields['nombre'];
				        $listaCompetencias_items = $conn->Execute($sqlCompetencias_items);
				        $nCompetencias_items =$listaCompetencias_items->recordCount();
				        $iPdCompetencias = 0;
				        if($nCompetencias_items > 0){
				        	while(!$listaCompetencias_items->EOF){
				        		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
				        		$cRespuestas_pruebas_items->setIdEmpresa($_POST['fIdEmpresa']);
								$cRespuestas_pruebas_items->setIdProceso($_POST['fIdProceso']);
								$cRespuestas_pruebas_items->setIdCandidato($_POST['fIdCandidato']);
								$cRespuestas_pruebas_items->setIdPrueba($_POST['fIdPrueba']);
								$cRespuestas_pruebas_items->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cRespuestas_pruebas_items->setIdItem($listaCompetencias_items->fields['idItem']);

								$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);
//				        		echo "<br />ITEM:" . $listaCompetencias_items->fields['idItem'] . " - opcion:: " . $cRespuestas_pruebas_items->getIdOpcion() . " DESC:: " . $cRespuestas_pruebas_items->getDescOpcion();
								//MEJOR => 2 PEOR => 0 VACIO => 1
								switch ($cRespuestas_pruebas_items->getIdOpcion())
								{
									case 'A':	// Mejor
										$iPdCompetencias += 2;
										break;
									case 'B':	// Peor
										$iPdCompetencias += 1;
										break;
									default:	// Sin contestar opcion 0 en respuestas
										$iPdCompetencias += 0;
										break;
								}
								$listaCompetencias_items->MoveNext();
				        	}
				        }
				        $cBaremos_resultado_competencias = new Baremos_resultados_competencias();
				        $cBaremos_resultado_competencias->setIdBaremo($_POST['fIdBaremo']);
				        $cBaremos_resultado_competencias->setIdPrueba($_POST['fIdPrueba']);
				        $cBaremos_resultado_competencias->setIdTipoCompetencia($listaCompetencias->fields['idTipoCompetencia']);
				        $cBaremos_resultado_competencias->setIdCompetencia($listaCompetencias->fields['idCompetencia']);

				        $sqlBaremos_resultado_competencia = $cBaremos_resultados_competenciasDB->readLista($cBaremos_resultado_competencias);
//				        echo $sqlBaremos_resultado_competencia . "<br />";
				        $listaBaremos_resultado_competencia = $conn->Execute($sqlBaremos_resultado_competencia);
//				        echo $iPdCompetencias . "<br />";
				        $iPBaremadaCompetencias=0;
				        $nBaremosC = $listaBaremos_resultado_competencia->recordCount();
				        if($nBaremosC>0){
				        	while(!$listaBaremos_resultado_competencia->EOF){

				        		if($iPdCompetencias <= $listaBaremos_resultado_competencia->fields['puntMax'] && $iPdCompetencias >= $listaBaremos_resultado_competencia->fields['puntMin']){
				        			$iPBaremadaCompetencias = 	$listaBaremos_resultado_competencia->fields['puntBaremada'];
				        		}
				        		$listaBaremos_resultado_competencia->MoveNext();
				        	}
				        }

				       	$sPosiCompetencias = $listaTipoCompetencia->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia'];
				       	$aPuntuacionesCompetencias[$sPosiCompetencias] =  $iPBaremadaCompetencias;
//				       	echo "<br />" . $listaCompetencias->fields['nombre'] . " - PD: " . $iPdCompetencias . " PBaremada: " . $iPBaremadaCompetencias;
				        $listaCompetencias->MoveNext();
			 		}
			 	}
			 	$listaTipoCompetencia->MoveNext();
			 }
		 }

		//FIN CALCULOS GLOBALES COMPETENCIAS

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
					<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/cml/resetCSS.css"/>';
					if($_POST['fIdTipoInforme'] != 11){
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/cml/style.css"/>';
					}else{
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/cml/styleNarrativos.css"/>';
					}
		$sHtmlInicio.='
					<title>CML</title>
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
						    	<img src="'.constant("DIR_WS_GESTOR").'estilosInformes/cml/img/logo-pequenio.jpg" title="logo"/>
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
		//PORTADA
		$sHtml.= '
			<div class="pagina portada">
		    	<img src="' . constant("DIR_WS_GESTOR") . 'graf/cml/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"><img src="' . constant("DIR_WS_GESTOR") . 'estilosInformes/cml/img/logo.jpg" /></h1>';
			if($_POST['fIdTipoInforme']!=11){
				$sHtml.= 		'<div id="txt_infome"><p>' . constant("STR_CML_INFORME_DE_MOTIVACIONES_LABORALES") . '</p></div>';
			}else{
				$sHtml.= 		'<div id="txt_infome_narrativo"><p>' . constant("STR_CML_INFORME_DE_MOTIVACIONES_LABORALES") . '</p></div>';
			}
		$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_NOMBRE_APELLIDOS") . ':</strong> '. $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2() .'</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong> '.date("d/m/Y").'</p>
				</div>
		    	<h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2>
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
			case(24);//Informe Completo
				//FUNCIÓN PARA generar la página de cml INFORME EXPERTO
			   	$sHtml.= informeExperto($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2']);
			   	$sHtml.= informeExpertoPag2($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2']);
				break;
		}

		$sHtml.= '
			<div class="pagina portada" id="contraportada">
    			<img id="imgContraportada" src="' . constant("DIR_WS_GESTOR") . 'graf/contraportada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
			</div>
			<!--FIN DIV PAGINA-->
		';

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

    $footer_html    =  mb_strtoupper($cPruebas->getNombre(), 'UTF-8') . str_repeat(" ", 70) . constant("STR_PIE_INFORMES");
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
		if ($pd<=132){ $baremo_C=1;}
		if ($pd>=133 && $pd<=148){$baremo_C=2;}
		if ($pd>=149 && $pd<=164){$baremo_C=3;}
		if ($pd>=165 && $pd<=180){$baremo_C=4;}
		if ($pd>=181 && $pd<=197){$baremo_C=5;}
		if ($pd>=198 && $pd<=213){$baremo_C=6;}
		if ($pd>=214 && $pd<=229){$baremo_C=7;}
		if ($pd>=230 && $pd<=245){$baremo_C=8;}
		if ($pd>=246 && $pd<=262){$baremo_C=9;}
		if ($pd>=263){ $baremo_C=10;}
		return $baremo_C;
	}
	//Funcion que devuelve un texto a la parte del informe de competencias de cml
	function textoDefinicion($puntuacion){
		$str="";
		if($puntuacion ==1 || $puntuacion==2){
			$str=constant("STR_PRISMA_NUNCA");	//"NUNCA";
		}
		if($puntuacion ==3 || $puntuacion==4){
			$str=constant("STR_PRISMA_CASI_NUNCA");	//"CASI NUNCA";
		}
		if($puntuacion ==5 || $puntuacion==6){
			$str=constant("STR_PRISMA_A_VECES");	//"A VECES";
		}
		if($puntuacion ==7 || $puntuacion==8){
			$str=constant("STR_PRISMA_CASI_SIEMPRE");	//"CASI SIEMPRE";
		}
		if($puntuacion ==9 || $puntuacion==10){
			$str=constant("STR_PRISMA_SIEMPRE");	//"SIEMPRE";
		}
		return $str;
	}
	//Funcion que devuelve un texto a la parte del informe de competencias de cml
	function textoPuntuacion($puntuacion){
		$str="";
		if($puntuacion ==1 || $puntuacion==2){
			$str=constant("STR_PRISMA_AREA_CLAVE_DE_MEJORA");	//"ÁREA CLAVE DE MEJORA";
		}
		if($puntuacion ==3 || $puntuacion==4){
			$str=constant("STR_PRISMA_AREA_POTENCIAL_DESARROLLO");	//"ÁREA POTENCIAL DESARROLLO";
		}
		if($puntuacion ==5 || $puntuacion==6){
			$str=constant("STR_PRISMA_AREA_EN_DESARROLLO");	//"ÁREA EN DESARROLLO";
		}
		if($puntuacion ==7 || $puntuacion==8){
			$str=constant("STR_PRISMA_AREA_POTENCIAL_FORTALEZA");	//"ÁREA POTENCIAL FORTALEZA";
		}
		if($puntuacion ==9 || $puntuacion==10){
			$str=constant("STR_PRISMA_AREA_DE_FORTALEZA");	//"ÁREA DE FORTALEZA";
		}
		return $str;
	}
	// Si llega MEJOR devolver 0
	// Si llega PEOR devolver 2
	// Si llega BLANCO devolver 1
	function getInversoCML($valor){
		$inv=0;

		//MEJOR => 2 PEOR => 0 VACIO => 1
		switch ($valor)
		{
			case 'A':	// Mejor
				$inv = 0;
				break;
			case 'B':	// Peor
				$inv = 2;
				break;
			default:	// Sin contestar opcion 0 en respuestas
				$inv = 1;
				break;
		}
//		if($valor==2){$inv=0;}
//		if($valor==1){$inv=1;}
//		if($valor==0){$inv=2;}
//		echo "<br />id::" . $valor .  " - valor::" . $inv;
		return $inv;
	}

	/*
	 * INTERPRETACIÓN DE INFORME EXPERTO.
	 */
	function informeExperto($aPuntuaciones, $sHtmlCab, $idIdioma)
	{

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $cTextos_secciones;
		global $cTextos_seccionesDB;

		global $aSQLPuntuacionesPPL;
		global $aSQLPuntuacionesC;

		global $cPruebas;
		global $cProceso;
		global $cRespPruebas;

		$sSQLExport ="";

		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("39");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($_POST['fIdTipoInforme']);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);


		$sHtml='
			<div class="pagina">'. $sHtmlCab;
		$sHtml.= '
				<div class="desarrollo">
		        	<h2 class="subtitulo">' . mb_strtoupper(constant("STR_INTRODUCCION"), 'UTF-8') . '</h2>
        		    <div class="caja">
            			<p class="textos">' . $cTextos_secciones->getTexto() . '</p>
		            </div>
        		</div>
        		<!--FIN DIV DESARROLLO-->
        	</div>
        	<!--FIN DIV PAGINA-->
          <hr>
        	';

		$sHtml.='
			<div class="pagina">'. $sHtmlCab;
		// PÁGINA PERFIL PERSONALIDAD LABORAL
		$sHtml.='
				<div class="desarrollo">
		       		<h2 class="subtitulo">' . constant("STR_CML_PERFIL_DE_MOTIVACIONES") . '</h2>
					<br />
			        <table id="personalidad" border="1" >
			        ';
					$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
					$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
					$i=0;
					$cEscalas_items=  new Escalas_items();
					$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
					$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
					$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
//					echo "<br />" . $sqlEscalas_items;
					$rsEscalas_items = $conn->Execute($sqlEscalas_items);
					$sBloques = "";
					while(!$rsEscalas_items->EOF){
						$sBloques .="," . $rsEscalas_items->fields['idBloque'];
						$rsEscalas_items->MoveNext();
					}
					if (!empty($sBloques)){
						$sBloques = substr($sBloques,1);
					}
					$cBloques = new Bloques();
					$cBloques->setCodIdiomaIso2($idIdioma);
					$cBloques->setIdBloque($sBloques);
					$cBloques->setOrderBy("idBloque");
					$cBloques->setOrder("ASC");
					$sqlBloques = $cBloquesDB->readLista($cBloques);
//					echo "<br />" . $sqlBloques;
					$listaBloques = $conn->Execute($sqlBloques);

					$iPosiImg=0;
					$iPGlobal = 0;
					$nBloques= $listaBloques->recordCount();
					if($nBloques>0){
						while(!$listaBloques->EOF  && ($listaBloques->fields['idBloque'] < 43)){

							$cEscalas = new Escalas();
						 	$cEscalas->setCodIdiomaIso2($idIdioma);
						 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
						 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
						 	$cEscalas->setOrderBy("idEscala");
						 	$cEscalas->setOrder("ASC");
						 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
						 	$listaEscalas = $conn->Execute($sqlEscalas);
						 	$nEscalas=$listaEscalas->recordCount();
						 	if($nEscalas > 0){
						 		$bPrimeraVuelta = true;
						 		while(!$listaEscalas->EOF){

							        if($bPrimeraVuelta){
							        	$sHtml.='
							          <tr>
							            <td colspan="7" style="border:1px solid #ffffff;text-align:center;">&nbsp;</td>
							          </tr>
							          <tr>
							          	<td class="letra" valign="middle"><h2 class="letra">' . getLetra($listaBloques->fields['idBloque']) . '</h2></td>
							            <td class="azul" valign="middle"><h2>' . $listaBloques->fields['nombre'] . '</h2></td>
							            <td class="celI" height="25"><p>' . constant("STR_PRISMA_BAJO") . '</p></td>
							            <td class="celI" height="25"><p>' . constant("STR_PRISMA_ME_BA") . '</p></td>
							            <td class="celI" height="25"><p>' . constant("STR_PRISMA_MEDIO") . '</p></td>
							            <td class="celI" height="25"><p>' . constant("STR_PRISMA_ME_AL") . '</p></td>
							            <td class="celI last" height="25"><p>' . constant("STR_PRISMA_ALTO") . '</p></td>
							          </tr>';
							        }
							        $iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
							        $sHtml.='<tr>';
							        $sHtml.='
							        	<td class="number"><p>' . $iPBaremada . '</p></td>
							        	<td class="tablaTitu" ><p class="tablaTitu" ><strong>' . $listaEscalas->fields['nombre'] . '</strong></p>
										<p class="descripcion">' . nl2br($listaEscalas->fields['descripcion']) . '</p></td>
										';


							        $sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
							        $sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($listaBloques->fields['idBloque'], false) . "," . $conn->qstr($listaBloques->fields['nombre'], false) . "," . $conn->qstr($listaEscalas->fields['idEscala'], false) . "," . $conn->qstr($listaEscalas->fields['nombre'], false) . "," . $conn->qstr($listaEscalas->fields['descripcion'], false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
							        $aSQLPuntuacionesPPL[] = $sSQLExport;


							       	if($iPBaremada==1 || $iPBaremada==2){
										$sHtml.='
										<td class="simbol"><p><img src="' . constant("DIR_WS_GESTOR") . 'graf/cml/graficasBajo.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></p></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol"></td>
							       		';
							       	}
							       	if($iPBaremada==3 || $iPBaremada==4){
										$sHtml.='
										<td class="simbol"><p><img src="' . constant("DIR_WS_GESTOR").'graf/cml/graficasMB.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></p></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol"></td>
							       		';
							       	}
							       	if($iPBaremada==5 || $iPBaremada==6){
										$sHtml.='
										<td class="simbol"><p><img src="'.constant("DIR_WS_GESTOR").'graf/cml/graficasMedio.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></p></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol"></td>
							       		';
							       	}
							       	if($iPBaremada==7 || $iPBaremada==8){
										$sHtml.='
										<td class="simbol"><p><img src="'.constant("DIR_WS_GESTOR").'graf/cml/graficasMA.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></p></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol"></td>
							       		';
							       	}
							       	if($iPBaremada==9 || $iPBaremada==10){
										$sHtml.='
										<td class="simbol"><p><img src="'.constant("DIR_WS_GESTOR").'graf/cml/graficasAlto.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></p></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol"></td>
							       		';
							       	}
							       	$sHtml.='
							       	</tr>
							       	';
							       	$bPrimeraVuelta = false;
							        $iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);
							        $listaEscalas->MoveNext();
						 		}
						 	}
						 	$sHtml.='
					           <tr>
					             <td colspan="7" style="border:1px solid #ffffff;text-align:center;">&nbsp;</td>
					           </tr>
					           <tr>
					             <td colspan="7" style="border:1px solid #ffffff;text-align:center;">&nbsp;</td>
					           </tr>
						 		<tr>
							    	<td colspan="7" style="border:1px solid #ffffff;text-align:left;color: #000000;">' . constant("STR_CML_COMENTARIOS") . ':</td>
								</tr>
							    <tr>
							    	<td colspan="7" style="border:1px solid #ffffff;text-align:center;"><hr></td>
							    </tr>
					           <tr>
					             <td colspan="7" style="border:1px solid #ffffff;text-align:center;">&nbsp;</td>
					           </tr>
							    <tr>
							    	<td colspan="7" style="border:1px solid #ffffff;text-align:center;"><hr></td>
							    </tr>
					           <tr>
					             <td colspan="7" style="border:1px solid #ffffff;text-align:center;">&nbsp;</td>
					           </tr>
							    ';
						 	$iPosiImg++;
						 	$listaBloques->MoveNext();
						 }
					 }
					$sHtml.='
			          <tr>
			            <td colspan="7" style="border:1px solid #ffffff;text-align:center;">&nbsp;</td>
			          </tr>
					</table>
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
        <hr>
    		';

		return $sHtml;
	}

		/*
	 * INTERPRETACIÓN DE INFORME EXPERTO.
	 */
	function informeExpertoPag2($aPuntuaciones, $sHtmlCab, $idIdioma)
	{

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $cTextos_secciones;
		global $cTextos_seccionesDB;

		global $aSQLPuntuacionesPPL;
		global $aSQLPuntuacionesC;

		$sSQLExport ="";
		global $cPruebas;
		global $cProceso;
		global $cRespPruebas;

		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("39");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($_POST['fIdTipoInforme']);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);


		$sHtml='
			<div class="pagina">'. $sHtmlCab;
		// PÁGINA PERFIL PERSONALIDAD LABORAL
		$sHtml.='
				<div class="desarrollo">
					<h2 class="subtitulo">' . constant("STR_CML_PERFIL_DE_MOTIVACIONES") . '</h2>
					<br />
			        <table id="personalidad" border="1" >
			        ';
					$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
					$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
					$i=0;
					$cEscalas_items=  new Escalas_items();
					$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
					$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
					$cEscalas_items->setIdBloque(43);
					$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
//					echo "<br />" . $sqlEscalas_items;
					$rsEscalas_items = $conn->Execute($sqlEscalas_items);
					$sBloques = "";
					while(!$rsEscalas_items->EOF){
						$sBloques .="," . $rsEscalas_items->fields['idBloque'];
						$rsEscalas_items->MoveNext();
					}
					if (!empty($sBloques)){
						$sBloques = substr($sBloques,1);
					}

					$cBloques = new Bloques();
					$cBloques->setCodIdiomaIso2($idIdioma);
					$cBloques->setIdBloque($sBloques);
					$cBloques->setOrderBy("idBloque");
					$cBloques->setOrder("ASC");
					$sqlBloques = $cBloquesDB->readLista($cBloques);
//					echo "<br />" . $sqlBloques;
					$listaBloques = $conn->Execute($sqlBloques);

					$iPosiImg=0;
					$iPGlobal = 0;
					$nBloques= $listaBloques->recordCount();
					if($nBloques>0){
						while(!$listaBloques->EOF){

							$cEscalas = new Escalas();
						 	$cEscalas->setCodIdiomaIso2($idIdioma);
						 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
						 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
						 	$cEscalas->setOrderBy("idEscala");
						 	$cEscalas->setOrder("ASC");
						 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
						 	$listaEscalas = $conn->Execute($sqlEscalas);
						 	$nEscalas=$listaEscalas->recordCount();
						 	if($nEscalas > 0){
						 		$bPrimeraVuelta = true;
						 		while(!$listaEscalas->EOF){

							        if($bPrimeraVuelta){
							        	$sHtml.='
							          <tr>
							            <td colspan="7" style="border:1px solid #ffffff;text-align:center;">&nbsp;</td>
							          </tr>
							          <tr>
							          	<td class="letra" valign="middle"><h2 class="letra">' . getLetra($listaBloques->fields['idBloque']) . '</h2></td>
							            <td class="azul" valign="middle"><h2>' . $listaBloques->fields['nombre'] . '</h2></td>
							            <td class="celI" height="25"><p>' . constant("STR_PRISMA_BAJO") . '</p></td>
							            <td class="celI" height="25"><p>' . constant("STR_PRISMA_ME_BA") . '</p></td>
							            <td class="celI" height="25"><p>' . constant("STR_PRISMA_MEDIO") . '</p></td>
							            <td class="celI" height="25"><p>' . constant("STR_PRISMA_ME_AL") . '</p></td>
							            <td class="celI last" height="25"><p>' . constant("STR_PRISMA_ALTO") . '</p></td>
							          </tr>';
							        }
							        $iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
							        $sHtml.='<tr>';
							        $sHtml.='
							        	<td class="number"><p>' . $iPBaremada . '</p></td>
							        	<td class="tablaTitu" ><p class="tablaTitu" ><strong>' . $listaEscalas->fields['nombre'] . '</strong></p>
										<p class="descripcion">' . nl2br($listaEscalas->fields['descripcion']) . '</p></td>
										';

							        $sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
							        $sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($listaBloques->fields['idBloque'], false) . "," . $conn->qstr($listaBloques->fields['nombre'], false) . "," . $conn->qstr($listaEscalas->fields['idEscala'], false) . "," . $conn->qstr($listaEscalas->fields['nombre'], false) . "," . $conn->qstr($listaEscalas->fields['descripcion'], false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
							        $aSQLPuntuacionesPPL[] = $sSQLExport;

							       	if($iPBaremada==1 || $iPBaremada==2){
										$sHtml.='
										<td class="simbol"><p><img src="' . constant("DIR_WS_GESTOR") . 'graf/cml/graficasBajo.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></p></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol"></td>
							       		';
							       	}
							       	if($iPBaremada==3 || $iPBaremada==4){
										$sHtml.='
										<td class="simbol"><p><img src="' . constant("DIR_WS_GESTOR").'graf/cml/graficasMB.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></p></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol"></td>
							       		';
							       	}
							       	if($iPBaremada==5 || $iPBaremada==6){
										$sHtml.='
										<td class="simbol"><p><img src="'.constant("DIR_WS_GESTOR").'graf/cml/graficasMedio.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></p></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol"></td>
							       		';
							       	}
							       	if($iPBaremada==7 || $iPBaremada==8){
										$sHtml.='
										<td class="simbol"><p><img src="'.constant("DIR_WS_GESTOR").'graf/cml/graficasMA.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></p></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol"></td>
							       		';
							       	}
							       	if($iPBaremada==9 || $iPBaremada==10){
										$sHtml.='
										<td class="simbol"><p><img src="'.constant("DIR_WS_GESTOR").'graf/cml/graficasAlto.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></p></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol"></td>
							       		';
							       	}
							       	$sHtml.='
							       	</tr>
							       	';
							       	$bPrimeraVuelta = false;
							        $iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);
							        $listaEscalas->MoveNext();
						 		}
						 	}
						 	$sHtml.='
					           <tr>
					             <td colspan="7" style="border:1px solid #ffffff;text-align:center;">&nbsp;</td>
					           </tr>
					           <tr>
					             <td colspan="7" style="border:1px solid #ffffff;text-align:center;">&nbsp;</td>
					           </tr>
						 		<tr>
							    	<td colspan="7" style="border:1px solid #ffffff;text-align:left;color: #000000;">' . constant("STR_CML_COMENTARIOS") . ':</td>
								</tr>
							    <tr>
							    	<td colspan="7" style="border:1px solid #ffffff;text-align:center;"><hr></td>
							    </tr>
					           <tr>
					             <td colspan="7" style="border:1px solid #ffffff;text-align:center;">&nbsp;</td>
					           </tr>
							    <tr>
							    	<td colspan="7" style="border:1px solid #ffffff;text-align:center;"><hr></td>
							    </tr>
					           <tr>
					             <td colspan="7" style="border:1px solid #ffffff;text-align:center;">&nbsp;</td>
					           </tr>
							    ';
						 	$iPosiImg++;
						 	$listaBloques->MoveNext();
						 }
					 }
					$sHtml.='
			          <tr>
			            <td colspan="7" style="border:1px solid #ffffff;text-align:center;">&nbsp;</td>
			          </tr>
					</table>
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
				<hr>
    		';

		return $sHtml;
	}

	function getLetra($iBloque){

		$sRetorno="";

		switch ($iBloque)
		{
			case '41':
				$sRetorno="E";
				break;
			case '42':
				$sRetorno="S";
				break;
			case '43':
				$sRetorno="A";
				break;
		}
		return $sRetorno;
	}
/******************************************************************
* FIN Funciones para la generación del Informe
******************************************************************/
?>
