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
		if($nBloques > 0){
			while(!$listaBloques->EOF){
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
				        if($nEscalas_items > 0){
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
//					       			echo "<br />" . $listaEscalas_items->fields['idItem'];
					       			$iPd += getInversoPrisma($cRespuestas_pruebas_items->getIdOpcion());
					       		}
										
								$listaEscalas_items->MoveNext();
				        	}
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
//				       	echo "<br />---------->[" . $sPosi . "][" . $iPBaremada . "]";
				       	$aPuntuaciones[$sPosi] =  $iPBaremada;
				       	
				        $listaEscalas->MoveNext();
			 		}
			 	}
			 	$listaBloques->MoveNext();
			 }
		 }
	// FIN CALCULOS GLOBALES ESCALAS
	
		//Se pone el xq el tipo de informe nos dice el tipo de competencia
		$idTipoCompetencia = 1;	//Jefe de personal		
		switch ($_POST['fIdTipoInforme'])
		{
			case 29:	//Jefe de personal
				$idTipoCompetencia = 1;
				break;
			case 30:
				$idTipoCompetencia = 2;
				break;
			case 31:
				$idTipoCompetencia = 3;
				break;
			case 32:
				$idTipoCompetencia = 4;
				break;
			case 33:
				$idTipoCompetencia = 5;
				break;
			case 34:
				$idTipoCompetencia = 6;
				break;
			case 35:
				$idTipoCompetencia = 7;
				break;
			case 36:
				$idTipoCompetencia = 8;
				break;
			case 37:
				$idTipoCompetencia = 9;
				break;
			case 38:
				$idTipoCompetencia = 10;
				break;
			case 39:
				$idTipoCompetencia = 11;
				break;
			case 40:
				$idTipoCompetencia = 12;
				break;
			case 41:
				$idTipoCompetencia = 13;
				break;
			case 42:
				$idTipoCompetencia = 14;
				break;
			case 43:
				$idTipoCompetencia = 15;
				break;
			case 44:
				$idTipoCompetencia = 16;
				break;
			case 45:
				$idTipoCompetencia = 17;
				break;
			case 46:
				$idTipoCompetencia = 18;
				break;
			case 47:
				$idTipoCompetencia = 19;
				break;
			case 48:
				$idTipoCompetencia = 20;
				break;
			case 49:
				$idTipoCompetencia = 21;
				break;
			case 50:
				$idTipoCompetencia = 22;
				break;
			case 51:
				$idTipoCompetencia = 23;
				break;
			case 52:
				$idTipoCompetencia = 24;
				break;
		}
		

	//CALCULOS GLOBALES COMPETENCIAS
		$cBaremos_resultados_competenciasDB = new Baremos_resultados_competenciasDB($conn);
		$cTipos_competencias = new Tipos_competencias();
		$cTipos_competencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTipos_competencias->setIdPrueba($_POST['fIdPrueba']);
		$cTipos_competencias->setIdTipoCompetencia($idTipoCompetencia);
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
				        	$sItemsxCompetencia = "";
				        	while(!$listaCompetencias_items->EOF){
				        		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
				        		$cRespuestas_pruebas_items->setIdEmpresa($_POST['fIdEmpresa']);
								$cRespuestas_pruebas_items->setIdProceso($_POST['fIdProceso']);
								$cRespuestas_pruebas_items->setIdCandidato($_POST['fIdCandidato']);
								$cRespuestas_pruebas_items->setIdPrueba($_POST['fIdPrueba']);
								$cRespuestas_pruebas_items->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cRespuestas_pruebas_items->setIdItem($listaCompetencias_items->fields['idItem']);
								$sItemsxCompetencia .= "+<b>". $listaCompetencias_items->fields['idItem'] . "</b>";
								$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);
//				        		echo "<br />ITEM:" . $listaCompetencias_items->fields['idItem'] . " - opcion:: " . $cRespuestas_pruebas_items->getIdOpcion() . " DESC:: " . $cRespuestas_pruebas_items->getDescOpcion();
								//MEJOR => 2 PEOR => 0 VACIO => 1
								switch ($cRespuestas_pruebas_items->getIdOpcion())
								{
									case '1':	// Mejor
										$iPdCompetencias += 2;
										break;
									case '2':	// Peor
										$iPdCompetencias += 0;
										break;
									default:	// Sin contestar opcion 0 en respuestas
										$iPdCompetencias += 1;
										break;
								}
								$listaCompetencias_items->MoveNext();
				        	}
				        }
				       	$sPosiCompetencias = $listaTipoCompetencia->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia'];
				       	
//				       	echo "<br />" . $listaTipoCompetencia->fields['nombre'] . "-" . $listaCompetencias->fields['nombre'];
//				       	echo "<br />Items de la competencia:: " . $sItemsxCompetencia;
//				       	echo "<br />PDC:: " . $iPdCompetencias;
//				       	echo "<br />        nº ITEMS C:: " . $nCompetencias_items;
//				       	echo "<br />        P. Máx teórica:: " . ($nCompetencias_items*2);
//				       	echo "<br />        PDC/nº ITEMS C == " . ($iPdCompetencias/$nCompetencias_items);
//				       	echo "<br />        Redondeo (PDC/nº ITEMS C) == " . round((($iPdCompetencias*8)/($nCompetencias_items*2)), 0);
				       	$iPuntuacion = round((($iPdCompetencias*8)/($nCompetencias_items*2)), 0);
				       	if ($iPuntuacion < 1){
				       		$iPuntuacion=1;
				       	}
			 			if ($iPuntuacion > 8){
				       		$iPuntuacion=8;
				       	}
				       	$aPuntuacionesCompetencias[$sPosiCompetencias] =  $iPuntuacion;
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
//		@set_time_limit(-1);
		ini_set("memory_limit","1024M");
//		ini_set("max_execution_time","600");
		//$comboPREFIJOS	= new Combo($aux,"fIdPrefijo","idPrefijo","prefijo","Descripcion","prefijos","","","","","");
		define ('_NEWPAGE', '<!--NewPage-->');
		$_HEADER = '';
		$sHtmlCab	= '';
		$sHtml		= '';
		$sHtmlFin	= '';
		//$aux			= $this->conn;
		
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
		
		$sHtmlInicio='
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/clece/resetCSS.css"/>';
					if($_POST['fIdTipoInforme'] != 11){
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/clece/style.css"/>';
					}else{
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/clece/styleNarrativos.css"/>';
					}
		$sHtmlInicio.='
					<title>Prisma</title>
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
					<table>
						<tr>
		    				<td class="nombre">
						        <p class="textos">Sr/a '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
						    </td>
						    <td class="logo">
						    	<img src="'.constant("DIR_WS_GESTOR").'estilosInformes/clece/img/logo-pequenio.jpg" title="logo"/>
						    </td>
						    <td class="fecha">
						        <p class="textos">' . date("d/m/Y") . '
						    </td>
					    </tr>
				    </table>
				</div>
		';
		$_HEADERz='<div class="cabecera">
					<table>
						<tr>
		    				<td class="nombre">
						        <p class="textos">Sr/a '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
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
		    	<img src="'.constant("DIR_WS_GESTOR").'graf/clece/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"><img src="'.constant("DIR_WS_GESTOR").'estilosInformes/clece/img/logo.jpg" /></h1>';
			if($_POST['fIdTipoInforme']!=11){
				$sHtml.= 		'<div id="txt_infome"><p>' . $sDescInforme . '</p></div>';
			}else{
				$sHtml.= 		'<div id="txt_infome_narrativo"><p>' . $sDescInforme . '</p></div>';
			}
		$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_NOMBRE_APELLIDOS") . ':</strong> '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong> '.date("d/m/Y").'</p>
				</div>
		    	<h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2>
			</div>
			<!--FIN DIV PAGINA-->
			';
//		$sHtml.=	constant("_NEWPAGE");
		//FIN PORTADA

		switch ($_POST['fIdTipoInforme'])
		{
			case(28);	//Perfil Básico Narrativo
			   	$sHtml.= generaNarrativoPrisma($aPuntuaciones, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
				break;
			default;//Informe competencias
			
				//PAGINAS INFORME DE COMPETENCIAS
				$sHtml.= informeCompetencias($aPuntuacionesCompetencias, $sHtmlCab, $_POST['fCodIdiomaIso2'], $idTipoCompetencia);
				
				//DEFINICIONES DE LAS COMPETENCIAS(Sólo html)
				$sHtml.= informeCompetenciasDefiniciones($sHtmlCab, $_POST['fCodIdiomaIso2'], $idTipoCompetencia);
				break;

		}
		
		$sHtml.= '
			<div class="pagina portada" id="contraportada">
    			<img id="imgContraportada" src="' . constant("DIR_WS_GESTOR") . 'graf/contraportada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
			</div>
			<!--FIN DIV PAGINA-->
		';
		
		
	if (!empty($sHtml))
	{
		$replace = array('@', '.');
//		$sNombre = $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" .$_POST['fIdTipoInforme'] . "_" . $cPruebas->getNombre();
		$sDirImg="imgInformes/";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
		
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
		require_once(constant('HTML2PS_DIR') . 'config.inc.php');
		require_once(constant('HTML2PS_DIR') . 'pipeline.factory.class.php');
		$g_baseurl = constant('DIR_WS_GESTOR') . $sDirImg . $sNombre . '.html';
		$GLOBALS['g_config'] = array(
                             'compress'      => '',
                             'cssmedia'      => 'Screen',
                             'debugbox'      => '',
                             'debugnoclip'   => '',
                             'draw_page_border'	=>   '',
                             'encoding'      => '',
                             'html2xhtml'    => 1,
                             'imagequality_workaround' => '',
                             'landscape'     => '',
                             'margins'       => array(
                                                      'left'    => 15,
                                                      'right'   => 15,
                                                      'top'     => 14,
                                                      'bottom'  => 10
                                                      ),
                             'media'         => 'A4',
                             'method'        => 'fpdf',
                             'mode'          => 'html',
                             'output'        => 2,
                             'pagewidth'     => 794,
                             'pdfversion'    => '1.3',
                             'ps2pdf'        => '',
                             'pslevel'       => 3,
                             'renderfields'  => 1,
                             'renderforms'   => '',
                             'renderimages'  => 1,
                             'renderlinks'   => '',
                             'scalepoints'   => 1,
                             'smartpagebreak' => 1,
                             'transparency_workaround' => ''
                             );
		parse_config_file(constant('HTML2PS_DIR') . 'html2ps.config');
		$g_media = Media::predefined($GLOBALS['g_config']['media']);
		$g_media->set_landscape($GLOBALS['g_config']['landscape']);
		$g_media->set_margins($GLOBALS['g_config']['margins']);
		$g_media->set_pixels($GLOBALS['g_config']['pagewidth']);
		
		$pipeline = new Pipeline;
		$pipeline->configure($GLOBALS['g_config']);
		// Configure the fetchers
		if (extension_loaded('curl')) {
			require_once(constant('HTML2PS_DIR') . 'fetcher.url.curl.class.php');
			$pipeline->fetchers = array(new FetcherUrlCurl());
		} else {
			require_once(constant('HTML2PS_DIR') . 'fetcher.url.class.php');
			$pipeline->fetchers[] = new FetcherURL();
		};
		
		// Configure the data filters
		$pipeline->data_filters[] = new DataFilterDoctype();
		$pipeline->data_filters[] = new DataFilterUTF8($GLOBALS['g_config']['encoding']);
		if ($GLOBALS['g_config']['html2xhtml']) {
			$pipeline->data_filters[] = new DataFilterHTML2XHTML();
		} else {
			$pipeline->data_filters[] = new DataFilterXHTML2XHTML();
		};
		
		$pipeline->parser = new ParserXHTML();
		
		// "PRE" tree filters
		
		$pipeline->pre_tree_filters = array();
		
		$header_html    = $_HEADER;
		$footer_html    = '
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="100%" align="center"><p style="font-size:10px;"> '. strtoupper($cPruebas->getNombre()) .' '.constant("STR_PIE_INFORMES").'</p></td>
				</tr>
			</table>
			';
		//$footer_html = $footer_html;
		$filter = new PreTreeFilterHeaderFooter($header_html, $footer_html);
		$pipeline->pre_tree_filters[] = $filter;
		
		if ($GLOBALS['g_config']['renderfields']) {
			$pipeline->pre_tree_filters[] = new PreTreeFilterHTML2PSFields();
		};
		// 
		
		if ($GLOBALS['g_config']['method'] === 'ps') {
			$pipeline->layout_engine = new LayoutEnginePS();
		} else {
			$pipeline->layout_engine = new LayoutEngineDefault();
		};
		
		$pipeline->post_tree_filters = array();
		
		// Configure the output format
		if ($GLOBALS['g_config']['pslevel'] == 3) {
			$image_encoder = new PSL3ImageEncoderStream();
		} else {
			$image_encoder = new PSL2ImageEncoderStream();
		};
		
		switch ($GLOBALS['g_config']['method']) {
			case 'fastps':
				if ($GLOBALS['g_config']['pslevel'] == 3) {
					$pipeline->output_driver = new OutputDriverFastPS($image_encoder);
				} else {
					$pipeline->output_driver = new OutputDriverFastPSLevel2($image_encoder);
				};
				break;
			case 'pdflib':
				$pipeline->output_driver = new OutputDriverPDFLIB16($GLOBALS['g_config']['pdfversion']);
				break;
			case 'fpdf':
				$pipeline->output_driver = new OutputDriverFPDF();
				break;
			case 'png':
				$pipeline->output_driver = new OutputDriverPNG();
				break;
			case 'pcl':
				$pipeline->output_driver = new OutputDriverPCL();
				break;
			default:
				die("Unknown output method");
		};
		
		// Setup watermark
		$watermark_text = '';
		if ($watermark_text != '') {
			$pipeline->add_feature('watermark', array('text' => $watermark_text));
		};
		
		if ($GLOBALS['g_config']['debugbox']) {
			$pipeline->output_driver->set_debug_boxes(true);
		}
		
		if ($GLOBALS['g_config']['draw_page_border']) {
			$pipeline->output_driver->set_show_page_border(true);
		}
		
		if ($GLOBALS['g_config']['ps2pdf']) {
			$pipeline->output_filters[] = new OutputFilterPS2PDF($GLOBALS['g_config']['pdfversion']);
		}
		
		if ($GLOBALS['g_config']['compress'] && $GLOBALS['g_config']['method'] == 'fastps') {
			$pipeline->output_filters[] = new OutputFilterGZip();
		}
		$process_mode='';
		if ($process_mode == 'batch') {
			$filename = "batch";
		} else {
			$filename = $g_baseurl;
		};
		
		switch ($GLOBALS['g_config']['output']) {
			case 0:
				$pipeline->destination = new DestinationBrowser($filename);
				break;
			case 1:
				$pipeline->destination = new DestinationDownload($filename);
				break;
			case 2:
				$pipeline->destination = new DestinationFile($filename, '');
				$pipeline->destination->set_filename($sNombre);
				break;
		};
		
		// Add additional requested features
		$toc_location = '';	//after o before
		if (!empty($toc_location)) {
			$pipeline->add_feature('toc', array('location' => $toc_location));
		}
		$automargins = '';
		if (!empty($automargins)) {
			$pipeline->add_feature('automargins', array());
		};
		
		// Start the conversion
		
		$time = time();
		if ($process_mode == 'batch') {
			$batch = array();
		
			for ($i=0; $i<count($batch); $i++) {
				if (trim($batch[$i]) != "") {
					if (!preg_match("/^https?:/",$batch[$i])) {
						$batch[$i] = "http://".$batch[$i];
					}
				};
			};
		
			$status = $pipeline->process_batch($batch, $g_media);
		} else {
			$status = $pipeline->process($g_baseurl, $g_media);
		};

		error_log(sprintf("Processing of '%s' completed in %u seconds", $g_baseurl, time() - $time), 3, constant("DIR_FS_PATH_NAME_LOG"));
		
		if ($status == null) {
			print($pipeline->error_message());
			error_log("Error in conversion pipeline", 3, constant("DIR_FS_PATH_NAME_LOG"));
			die();
		}else{
			//$cEntidad->setPdf($sDirImg . $sNombre . '.pdf');
			//$this->modificarPDF($cEntidad);
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
	//Funcion que devuelve un texto a la parte del informe de competencias de prisma	
	function textoPuntuacion($puntuacion){
		$str="";
		if($puntuacion ==1 || $puntuacion==2){
			$str="ÁREA CLAVE DE MEJORA";	
		}
		if($puntuacion ==3 || $puntuacion==4){
			$str="ÁREA POTENCIAL DESARROLLO";	
		}
		if($puntuacion ==5 || $puntuacion==6){
			$str="ÁREA EN DESARROLLO";	
		}
		if($puntuacion ==7 || $puntuacion==8){
			$str="ÁREA POTENCIAL FORTALEZA";	
		}
		if($puntuacion ==9 || $puntuacion==10){
			$str="ÁREA DE FORTALEZA";	
		}
		return $str;
	}
	// Si llega MEJOR devolver 0
	// Si llega PEOR devolver 2
	// Si llega BLANCO devolver 1
	function getInversoPrisma($valor){	
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
//		if($valor==2){$inv=0;}
//		if($valor==1){$inv=1;}	
//		if($valor==0){$inv=2;}
//		echo "<br />id::" . $valor .  " - valor::" . $inv;
		return $inv;
	}
	
	
	/*
	 * INFORME ORIENTADO A COMPETENCIAS
	 * PÁGINA COMPETENCIAS PARA LA GESTIÓN
	 * PÁGINA COMPETENCIAS TECNICAS
	 * PÁGINA COMPETENCIAS PARA EL DESARROLLO DEL NEGOCIO
	 */
	function informeCompetencias($aPuntuaciones , $sHtmlCab, $idIdioma, $idTipoCompetencia){
		
		global $conn;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Perfiles_ideales/Perfiles_idealesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Perfiles_ideales/Perfiles_ideales.php");
		
		
		$cTipos_competenciaDB = new Tipos_competenciasDB($conn);
		$cCompetenciasDB = new CompetenciasDB($conn);
		$cPerfiles_idealesDB = new Perfiles_idealesDB($conn);		
		
		$sHtml= '
		<div class="pagina">'. $sHtmlCab;
		// PÁGINA INFORME ORIENTADO COMPETENCIAS
		$sHtml.= '
			<div class="desarrollo">
	      		<h2 class="subtitulo">' . constant("STR_PRISMA_INFORME_ORIENTADO_A_COMPETENCIAS") . '</h2>
	        	<div class="caja" style="margin-bottom:40px;">
	            	<h3 class="encabezado">' . strtoupper(constant("STR_INTRODUCCION")) . '</h3>
	            	<p class="textos">' . constant("STR_PRISMA_INFORME_ORIENTADO_A_COMPETENCIAS_INTRO_P1") . '</p>
					<p class="textos">' . constant("STR_PRISMA_INFORME_ORIENTADO_A_COMPETENCIAS_INTRO_P2") . '</p>
					<p class="textos">' . constant("STR_PRISMA_INFORME_ORIENTADO_A_COMPETENCIAS_INTRO_P3") . '</p>
					<p class="textos">' . constant("STR_PRISMA_INFORME_ORIENTADO_A_COMPETENCIAS_INTRO_P4") . '</p>
					<p class="textos">' . constant("STR_PRISMA_INFORME_ORIENTADO_A_COMPETENCIAS_INTRO_P5") . '</p>
	            </div><!--FIN DIV CAJA-->
				<table class="competencias" width="730" border="0" cellspacing="0" cellpadding="0">';
            
			$cTipoCompetencia = new Tipos_competencias();
			$cTipoCompetencia->setCodIdiomaIso2($idIdioma);
			$cTipoCompetencia->setIdPrueba($_POST['fIdPrueba']);
			$cTipoCompetencia->setIdTipoCompetencia($idTipoCompetencia);
			$cTipoCompetencia = $cTipos_competenciaDB->readEntidad($cTipoCompetencia);
			$sHtml.= '<tr>
                        <td colspan="3" bgcolor="#ffffff" style="border-bottom:3px solid #93b1d3;">
                        <h2 class="subtitulo" style="line-height:32px; color:#475464">' . $cTipoCompetencia->getNombre() . '</h2></td>
                      </tr>	';
			
			$cCompetencia = new Competencias();
			$cCompetencia->setCodIdiomaIso2($idIdioma);
			$cCompetencia->setIdPrueba($_POST['fIdPrueba']);
			$cCompetencia->setIdTipoCompetencia($idTipoCompetencia);
			$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencia);
//			echo $sqlCompetencias;
			$listaCompetencias = $conn->Execute($sqlCompetencias);
			$nCompetencia =$listaCompetencias->recordCount();
			//PÁGINA COMPETENCIAS PARA LA GESTIÓN
			if($nCompetencia>0){
				$iContC = 0;
				while(!$listaCompetencias->EOF){
					$cPerfiles_ideales = new Perfiles_ideales();
					$cPerfiles_ideales->setCodIdiomaIso2($idIdioma);
					$cPerfiles_ideales->setIdPrueba($_POST['fIdPrueba']);
					$cPerfiles_ideales->setIdTipoCompetencia($listaCompetencias->fields['idTipoCompetencia']);
					$cPerfiles_ideales->setIdCompetencia($listaCompetencias->fields['idCompetencia']);
					$cPerfiles_ideales = $cPerfiles_idealesDB->readEntidad($cPerfiles_ideales);
					
					if ($iContC == 4){
						$sHtml.= '</table>
				      		</div><!--FIN DIV DESARROLLO-->
						</div><!--FIN DIV PAGINA-->';

						$sHtml.= '
						<div class="pagina">'. $sHtmlCab;
						// PÁGINA INFORME ORIENTADO COMPETENCIAS
						$sHtml.= '
							<div class="desarrollo">
								<table class="competencias" width="730" border="0" cellspacing="0" cellpadding="0">';
				            
							$cTipoCompetencia = new Tipos_competencias();
							$cTipoCompetencia->setCodIdiomaIso2($idIdioma);
							$cTipoCompetencia->setIdPrueba($_POST['fIdPrueba']);
							$cTipoCompetencia->setIdTipoCompetencia($idTipoCompetencia);
							$cTipoCompetencia = $cTipos_competenciaDB->readEntidad($cTipoCompetencia);
							$sHtml.= '<tr>
				                        <td colspan="3" bgcolor="#ffffff" style="border-bottom:3px solid #93b1d3;border-top:3px solid #93b1d3;">
				                        <h2 class="subtitulo" style="line-height:32px; color:#475464">' . $cTipoCompetencia->getNombre() . '</h2></td>
				                      </tr>	';
										
					}
					$iPuntacion = $aPuntuaciones[$listaCompetencias->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia']];
					$sDESC = $listaCompetencias->fields['descripcion'];
					$aDESC = explode("<!--SEPARADOR-->",$sDESC);					
//					echo "<br />" . $listaCompetencias->fields['nombre'] . ": " . $listaCompetencias->fields['idTipoCompetencia'] . " - " . $listaCompetencias->fields['idCompetencia'];
//					echo "<br />iPuntacion:: " . $iPuntacion;
					$sHtml.= ' <tr class="pLinea">
		                        <td class="tablaTitu"><p>' . $listaCompetencias->fields['nombre'] . '</p></td>
		                        <td class="descripcion" valign="top">
									<p class="textos">' . nl2br($aDESC[1]) . '</p>
		                        </td>
		                        <td class="subTabla">
		                        	<table width="220" border="0" cellspacing="0" cellpadding="0">
		                        		<tr>
		                                	<td colspan="10" style="height:5px;">&nbsp;</td>
		                          		</tr>
		                              	<tr>
			                                <td rowspan="2">&nbsp;</td>
			                                <td class="cel"><p>1</p></td>
			                                <td class="cel"><p>2</p></td>
			                                <td class="cel"><p>3</p></td>
			                                <td class="cel"><p>4</p></td>
			                                <td class="cel"><p>5</p></td>
			                                <td class="cel"><p>6</p></td>
			                                <td class="cel"><p>7</p></td>
			                                <td class="cel"><p>8</p></td>
			                                <td rowspan="2">&nbsp;</td>
		                              	</tr>
		                              	<tr>';
		                              			if($iPuntacion==1){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/clece/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
						                        if($iPuntacion==2){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/clece/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==3){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/clece/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==4){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/clece/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==5){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/clece/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
												if($iPuntacion==6){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/clece/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==7){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/clece/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==8){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/clece/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
		                       $sHtml.='</tr>
		                          		<tr>
		                                	<td colspan="10" style="height:20px;">&nbsp;</td>
		                          		</tr>
		                              	<tr>
			                                <td rowspan="2">&nbsp;</td>
			                                <td class="cel" colspan="8"><p>Perfil ideal</p></td>
			                                <td rowspan="2">&nbsp;</td>
		                              	</tr>
		                              	<tr>';
										$sHtml.= getHTMLPerfilIdeal($cPerfiles_ideales->getPuntuacionMin(), $cPerfiles_ideales->getPuntuacionMax());
		                       $sHtml.='</tr>
		                          		<tr>
		                                	<td colspan="10" style="height:5px;">&nbsp;</td>
		                          		</tr>
		                        	</table>';

								//FIN Para el perfil ideal		                       
		                       $sHtml.='
		                        </td>
		                      </tr>';
		            $iContC++;
					$listaCompetencias->MoveNext();
				}
			}          
        $sHtml.= '</table>
      	</div><!--FIN DIV DESARROLLO-->
      </div>
      <!--FIN DIV PAGINA-->';
		
	
		return $sHtml;
	}
	
	function informeCompetenciasDefiniciones($sHtmlCab, $idIdioma, $idTipoCompetencia){
			
				global $conn;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");
		
		
		$cTipos_competenciaDB = new Tipos_competenciasDB($conn);
		$cCompetenciasDB = new CompetenciasDB($conn);
		
			
		$sHtml= '
			<div class="pagina">'. $sHtmlCab;
			$sHtml.= '
			<div class="desarrollo">
				<h2 class="subtitulo">' . constant("STR_PRISMA_DEFINICION_DE_LAS_COMPETENCIAS") . '</h2>
	            <table class="definiciones" width="730" border="1" cellspacing="0" cellpadding="0">';
			$cCompetencia = new Competencias();
			$cCompetencia->setCodIdiomaIso2($idIdioma);
			$cCompetencia->setIdPrueba($_POST['fIdPrueba']);
			$cCompetencia->setIdTipoCompetencia($idTipoCompetencia);
			$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencia);
			$listaCompetencias = $conn->Execute($sqlCompetencias);
			$nCompetencia =$listaCompetencias->recordCount();
			if($nCompetencia > 0){
				while(!$listaCompetencias->EOF){
					$sDESC = $listaCompetencias->fields['descripcion'];
					$aDESC = explode("<!--SEPARADOR-->",$sDESC);
					$sHtml.= ' <tr>
		                    <td class="tablaTitu"><p>' . $listaCompetencias->fields['nombre'] . '</p></td>
		                    <td class="descripcion"><p class="textos">' . $aDESC[0] . '</p></td>  
		                  </tr>';
					$listaCompetencias->MoveNext();
				}
			}		
		                  
		$sHtml.= '
		             </table>
					</div><!--FIN DIV DESARROLLO-->
				</div>
				<!--FIN DIV PAGINA-->';
		//FIN DEFINICIONES COMPETENCIAS
		return $sHtml;
	}
	
	function getHTMLPerfilIdeal($pMin, $pMax){
		$sHtml='';
		for ($i=1; $i < 9; $i++){
			if (($i >= $pMin) && ($i <= $pMax)){
				$sHtml.='<td class="cel color">&nbsp;</td>';	
			}else{
				$sHtml.='<td class="cel">&nbsp;</td>';
			}
		}
	return $sHtml;
	}
	
	function generaNarrativoPrisma($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
{
		
		global $conn;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalas.php");
		
		$cBloquesDB = new BloquesDB($conn);
		$cEscalasDB = new EscalasDB($conn);
		$cTextos_escalasDB = new Textos_escalasDB($conn);
		$sHtml='';
		//PÁGINA INTRODUCCIÓN, 1
//		$sHtml.='
//			<div class="pagina">'. $sHtmlCab;
//		$sHtml.= '
//        		<h2 class="subtitulo_prisma" style="color:#000000">' . constant("STR_INTRODUCCION_CAPS") . '</h2>
//            	<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getApellido1() , constant("PRISMA_NARRATIVO_INTP1"))  . '</p><br />
//				<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getApellido1() , constant("PRISMA_NARRATIVO_INTP2")) . '</p><br />
//				<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getApellido1() , constant("PRISMA_NARRATIVO_INTP3")) . '</p><br />
//                <p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getApellido1() , constant("PRISMA_NARRATIVO_INTP4")) . '</p><br />
//				<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getApellido1() , constant("PRISMA_NARRATIVO_INTP5")) . '</p>
//        </div>
//        <!--FIN DIV PAGINA-->';
//		$sHtml.=	constant("_NEWPAGE");
		
		//FIN PÁGINA INTRODUCCIÓN, 1
		$sHtml.='
			<div class="pagina">'. $sHtmlCab;

		// PÁGINA PERFIL PERSONALIDAD LABORAL

		// ENERGÍAS Y MOTIVACIONES
		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$aMejoras = array();
		$aFuertes = array();
		$i=0;
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($idIdioma);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
//		echo $sqlBloques;
		$listaBloques = $conn->Execute($sqlBloques);
		
		$iPosiImg=0;
		$iPGlobal = 0;
		$nBloques= $listaBloques->recordCount();

		$listaBloques->MoveFirst();
		
		$sHtml.= '
		        	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>
		            ';
//		$sHtml.= '
//		        	<h2 class="subtitulo_prisma" style="color:#000000;">&nbsp;</h2>
//		            ';
		
		
		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(1);
	 	$cEscalas->setIdBloqueHast(1);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//		echo $sqlEscalas;
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){
		
	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
		        
	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);
	 			
	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
//	 			echo $sqlTextos;
	 			$listaTextos = $conn->Execute($sqlTextos);
	 			
	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}
	 					
	 					$listaTextos->MoveNext();
	 				}
	 			}
	 			
				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		            
		        $listaEscalas->MoveNext();
	 		}
	 	}
	 	$listaBloques->MoveNext();
		$sHtml.='	
		<br />
      	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>
			';
//		$sHtml.='	
//		<br />
//     	<h2 class="subtitulo_prisma" style="color:#000000;">&nbsp;</h2>
//			';
	 	
		
		
		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(2);
	 	$cEscalas->setIdBloqueHast(2);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){
		
	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
		        
	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);
	 			
	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);
	 			
	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}
	 					
	 					$listaTextos->MoveNext();
	 				}
	 			}
	 			
				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
	
		        $listaEscalas->MoveNext();
	 		}
	 	}
		$sHtml.='';	

 $sHtml.=' 
    </div>
    <!--FIN DIV PAGINA-->';
		$sHtml.=	constant("_NEWPAGE");
 	$sHtml.='
 	<div class="pagina">'. $sHtmlCab;
		// PÁGINA PERFIL PERSONALIDAD LABORAL
		
		
		$nBloques= $listaBloques->recordCount();

		$listaBloques->MoveNext();
		
		$sHtml.= '
				<h2 class="subtitulo_prisma" style="color:#000000;">'.constant("STR_ESTILO_ORENTACION_NARR_PRISMA").'</h2>
		        	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>';
//		$sHtml.= '
//				<h2 class="subtitulo_prisma" style="color:#000000;">&nbsp;</h2>
//	        	';
		
		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(3);
	 	$cEscalas->setIdBloqueHast(3);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){
		
	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
		        
	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);
	 			
	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);
	 			
	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}
	 					
	 					$listaTextos->MoveNext();
	 				}
	 			}
	 			
				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		            
		        $listaEscalas->MoveNext();
	 		}
	 	}
	 	$listaBloques->MoveNext();
		$sHtml.='

      	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>
			';
//		$sHtml.='
//
//      	<h2 class="subtitulo_prisma" style="color:#000000;">&nbsp;</h2>
//			';
		
		
		
		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(4);
	 	$cEscalas->setIdBloqueHast(4);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){
		
	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
		        
	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);
	 			
	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);
	 			
	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}
	 					
	 					$listaTextos->MoveNext();
	 				}
	 			}
	 			
				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		            
		        $listaEscalas->MoveNext();
	 		}
	 	}
			
		
		$listaBloques->MoveNext();
		$sHtml.='

      	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>
			';
//		$sHtml.='
//
//      	<h2 class="subtitulo_prisma" style="color:#000000;">&nbsp;</h2>
//			';
		
		
		
		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(5);
	 	$cEscalas->setIdBloqueHast(5);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){
		
	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
		        
	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);
	 			
	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);
	 			
	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}
	 					
	 					$listaTextos->MoveNext();
	 				}
	 			}
	 			
				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		            
		        $listaEscalas->MoveNext();
	 		}
	 	}
		$sHtml.='';	
		
 $sHtml.=' 
    </div>
    <!--FIN DIV PAGINA-->';
		$sHtml.=	constant("_NEWPAGE");
 $sHtml.='
 	<div class="pagina">'. $sHtmlCab;
		//$sHtml.='<div class="pagina">';
		// PÁGINA PERFIL PERSONALIDAD LABORAL
		
		
		$nBloques= $listaBloques->recordCount();

		$listaBloques->MoveNext();
		
		$sHtml.= '
						<h2 class="subtitulo_prisma" style="color:#000000;">'.constant("STR_ESTILO_PENSAMIENTO_NARR_PRISMA").'</h2>
		        	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>
		           ';
//		$sHtml.= '
//						<h2 class="subtitulo_prisma" style="color:#000000;">&nbsp;</h2>
//		           ';
		
		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(6);
	 	$cEscalas->setIdBloqueHast(6);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){
		
	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
		        
	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);
	 			
	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);
	 			
	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}
	 					
	 					$listaTextos->MoveNext();
	 				}
	 			}
	 			
				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		            
		        $listaEscalas->MoveNext();
	 		}
	 	}
	 	$listaBloques->MoveNext();
		$sHtml.='
      	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>';
//		$sHtml.='
//      	<h2 class="subtitulo_prisma" style="color:#000000;">&nbsp;</h2>';
		
		
		
		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(7);
	 	$cEscalas->setIdBloqueHast(7);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){
		
	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
		        
	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);
	 			
	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);
	 			
	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}
	 					
	 					$listaTextos->MoveNext();
	 				}
	 			}
	 			
				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . "(" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . "(" . $iPBaremada . ")";
		       	}
		            
		        $listaEscalas->MoveNext();
	 		}
	 	}
			
		
		$listaBloques->MoveNext();
		$sHtml.='<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>';
//		$sHtml.='<h2 class="subtitulo_prisma" style="color:#000000;">&nbsp;</h2>';
		
		
		
		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(8);
	 	$cEscalas->setIdBloqueHast(8);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){
		
	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
		        
	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);
	 			
	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);
	 			
	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}
	 					
	 					$listaTextos->MoveNext();
	 				}
	 			}
	 			
				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		            
		        $listaEscalas->MoveNext();
	 		}
	 	}
		
		// PÁGINA PERFIL PERSONALIDAD LABORAL
$listaBloques->MoveNext();
		
		$sHtml.= '<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>';
//		$sHtml.= '<h2 class="subtitulo_prisma" style="color:#000000;">&nbsp;</h2>';
		
		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(9);
	 	$cEscalas->setIdBloqueHast(9);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){
		
	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
		        
	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);
	 			
	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);
	 			
	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}
	 					
	 					$listaTextos->MoveNext();
	 				}
	 			}
	 			
				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		            
		        $listaEscalas->MoveNext();
	 		}
	 	}

		
 $sHtml.=' 
    </div>
    <!--FIN DIV PAGINA-->';
 
     return $sHtml;
	}
/******************************************************************
* FIN Funciones para la generación del Informe
******************************************************************/
?>