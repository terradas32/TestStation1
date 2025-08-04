<?php
		$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false),"","fecMod");
		$sDescInforme = $comboTIPOS_INFORMES->getDescripcionCombo($_POST['fIdTipoInforme']);

		$sNombre_infome = "ELT";
		$sDescInforme = "Advanced";

		$cNivelesjerarquicos = new Nivelesjerarquicos();
		$cNivelesjerarquicos->setIdNivel($cCandidato->getIdNivel());
		$cNivelesjerarquicos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cNivelesjerarquicos = $cNivelesjerarquicosDB->readEntidad($cNivelesjerarquicos);
		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");

		@set_time_limit(0);
		ini_set("memory_limit","1024M");

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
					<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/pruebaKPMG/resetCSS.css"/>
					<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/pruebaKPMG/style.css"/>
					<title>' . $cPruebas->getIdPrueba() . ' - ' . $_sBaremo . '</title>
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
						        <p class="textos">' . constant("STR_SR_A") . ' '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
						    </td>
						    <td class="logo">' . $sNombre_infome . " " . $sDescInforme . '
						    <!-- <img src="'.constant("DIR_WS_GESTOR").'estilosInformes/pruebaKPMG/img/logo-pequenio.jpg" title="logo"/> -->
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
						        <p class="textos">' . constant("STR_SR_A") . ' '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
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
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("3");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$sDescInforme = $cTextos_secciones->getTexto();

		$sHtml.= '
			<div class="pagina portada">
		    	<img src="' . constant("DIR_WS_GESTOR") . 'graf/pruebaKPMG/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"><img src="' . constant("DIR_WS_GESTOR") . 'estilosInformes/pruebaKPMG/img/logo.jpg" /></h1>';
		$sHtml.= 		'<div id="txt_nombre_infome"><p>' . $sNombre_infome . '</p></div>';
		$sHtml.= 		'<div id="txt_infome"><p>' . $sDescInforme . '</p></div>';
		$sHtml.= 		'<div id="txt_puntos_infome"><p>' . str_repeat(".", 80) . '</p></div>';
		$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_DEF_CANDIDATO") . ':</strong><br />' . constant("STR_SR_A") . '. ' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2() . '</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong><br />' . $cUtilidades->PrintDate(date("Y-m-d"), $_POST['fCodIdiomaIso2']) . '</p>
				</div>
				<div id="ts">
					<img src="' . constant("DIR_WS_GESTOR") . 'graf/TS.jpg" />
				</div>

		    	<!-- <h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2> -->
			</div>
			<!--FIN DIV PAGINA-->
			';
//		$sHtml.=	constant("_NEWPAGE");
		//FIN PORTADA

	$iVERBAL = 0;
	$iNUMERICO = 0;
	$iLOGICO = 0;
	$iINGLES = 0;
	$iINGLESL = 0;
	$iINGLES_GLOBAL = 0;

//Sacamos la bateria de pruebas

    $aAleatoriasI = array(48,56,57);	//Inglés
    $aAleatoriasIL = array(58,59,60);	//Inglés Listening

	require_once(constant("DIR_WS_COM") . "Proceso_pruebas_candidato/Proceso_pruebas_candidatoDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas_candidato/Proceso_pruebas_candidato.php");

	$cProceso_pruebas_candidato = new Proceso_pruebas_candidato();
	$cProceso_pruebas_candidatoDB = new Proceso_pruebas_candidatoDB($conn);

	$cProceso_pruebas_candidato = new Proceso_pruebas_candidato();
	$cProceso_pruebas_candidato->setIdEmpresa($_POST['fIdEmpresa']);
    $cProceso_pruebas_candidato->setIdProceso($_POST['fIdProceso']);
    $cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
    $cProceso_pruebas_candidato->setOrderBy("orden");
	$cProceso_pruebas_candidato->setOrder("ASC");

	$sqlProceso_pruebas_candidato = $cProceso_pruebas_candidatoDB->readLista($cProceso_pruebas_candidato);
    $listaProcesosPruebas = $conn->Execute($sqlProceso_pruebas_candidato);
    $iListaPruebas = $listaProcesosPruebas->recordCount();

    if($iListaPruebas > 0)
    {	while(!$listaProcesosPruebas->EOF)
		{
			$cPruebas = new Pruebas();
			$cPruebas->setIdPrueba($listaProcesosPruebas->fields['idPrueba']);
			$cPruebas->setCodIdiomaIso2($listaProcesosPruebas->fields['codIdiomaIso2']);
			$cPruebas = $cPruebasDB->readEntidad($cPruebas);

			if (in_array($listaProcesosPruebas->fields['idPrueba'], $aAleatoriasI))
			{
				$iINGLES = getPDPrueba($cCandidato, $cPruebas);
				generaPrueba($listaProcesosPruebas->fields['codIdiomaIso2'], $listaProcesosPruebas->fields['idPrueba']);
			}
			if (in_array($listaProcesosPruebas->fields['idPrueba'], $aAleatoriasIL))
			{
				$iINGLESL = getPDPrueba($cCandidato, $cPruebas);
				$iINGLES_GLOBAL = $iINGLES + $iINGLESL;
				generaPrueba($listaProcesosPruebas->fields['codIdiomaIso2'], $listaProcesosPruebas->fields['idPrueba']);
			}


			$listaProcesosPruebas->MoveNext();
		}
    }

    $IR = 0.00;
    $IP = 0.00;
    $POR = 0;
    $sRan_test= "";


//FIN - Sacamos la bateria de pruebas

		$sHtml.='
			<div class="pagina">'. $sHtmlCab;

		$sHtml.='	<table width="100%" border="0">';
		$sHtml.='		<tr>
    						<td width="80%">';

    	//TITULO INTRODUCCIÓN
		$sHtml.='				<p style="text-align:center;background:#ff411d;height:25px;padding:5px;">
    								<font style="font-size: 21px;color:#FFFFFF;font-weight: bold;">' . mb_strtoupper(constant('STR_INTRODUCCION'), 'UTF-8').'</font>
    							</p>';
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("1");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
    	// TEXTO INTRODUCCIÓN

		$sHtml.="<br />" . $cTextos_secciones->getTexto();

		$sHtml.='				<p align="justify" style="font-size: 18px;padding-bottom: 10px;">'.constant("STR_ESTE_INFORME_REPRESENTA").'</p>
								<p align="left">
									<ul>
										<li style="list-style: square;padding:10px;">
    										<font style="font-size: 18px;color: #cc3300;font-weight: bold;">'.constant("STR_PUNTUACION_DIRECTA").':</font><br /><br />
    										<font style="font-size: 18px;color: #000000;">'.constant("STR_EXP_PUNT_DIRECTA").'</font>
	    								</li>
	    								<li style="list-style: square;padding:10px;">
    										<font style="font-size: 18px;color: #cc3300;font-weight: bold;">'.constant("STR_PUNTUACION_PERCENTIL").':</font><br /><br />
    										<font style="font-size: 18px;color: #000000;">'.constant("STR_EXP_PUNT_PECENTIL").'</font>
	    								</li>
	    								<li style="list-style: square;padding:10px;">
    										<font style="font-size: 18px;color: #cc3300;font-weight: bold;">'.constant("STR_INDICE_RAPIDEZ").':</font><br /><br />
    										<font style="font-size: 18px;color: #000000;">'.constant("STR_EXP_INDICE_RAPIDEZ").'</font>
	    								</li>
	    								<li style="list-style: square;padding:10px;">
    										<font style="font-size: 18px;color: #cc3300;font-weight: bold;">'.constant("STR_INDICE_PRECISION").':</font><br /><br />
    										<font style="font-size: 18px;color: #000000;">'.constant("STR_EXP_INDICE_PRECISION").'</font>
	    								</li>
	    								<li style="list-style: square;padding:10px;">
    										<font style="font-size: 18px;color: #cc3300;font-weight: bold;">'.constant("STR_PRODUCTO_RENDIMIENTO").':</font><br /><br />
    										<font style="font-size: 18px;color: #000000;">'.constant("STR_EXP_PRODUCTO_RENDIMIENTO").'</font>
	    								</li>
	    								<li style="list-style: square;padding:10px;">
    										<font style="font-size: 18px;color: #cc3300;font-weight: bold;">'.constant("STR_ESTILO_PROCESAMIENTO_MENTAL").':</font><br /><br />
    										<font style="font-size: 18px;color: #000000;">'.constant("STR_EXP_ESTILO_PROCESAMIENTO_MENTAL").'</font>
	    								</li>
	    							</ul>
	    							<br />
	    							<br />
	    							<br /><br /><br />
								</p>';
		$sHtml.='			</td>
    					</tr>
    				</table>
        	</div>
        	<!--FIN DIV PAGINA-->
    				';

function generaPrueba($sCodIdiomaIso2, $sIdPrueba){
	global $sHtml;
	global $sHtmlCab;
	global $conn;
	global $cTextos_seccionesDB;
	global $cCandidato;
	global $cRango_ir;
	global $listaRespItems;
	global $listaItemsPrueba;
	global $cRangos_textosDB;
	global $cRangos_irDB;
	global $cRangos_ipDB;
	global $cPruebas;

	global $aAleatoriasIL;
	global $sNombre;
	global $spath;
	global $sDirImg;
	global $iVERBAL;
	global $iLOGICO;
	global $iNUMERICO;
	global $iINGLES;
	global $iINGLESL;
	global $iINGLES_GLOBAL;
	global $sRan_test;
	global $IR;
	global $IP;
	global $POR;
	global $iPDirecta;
	global $iPercentil;


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
						$cRespuestasPruebasItems->setIdPrueba($sIdPrueba);
						$cRespuestasPruebasItems->setIdEmpresa($_POST['fIdEmpresa']);
						$cRespuestasPruebasItems->setIdProceso($_POST['fIdProceso']);
						$cRespuestasPruebasItems->setCodIdiomaIso2($sCodIdiomaIso2);
						$cRespuestasPruebasItems->setOrderBy("idItem");
						$cRespuestasPruebasItems->setOrder("ASC");

						$cIt = new Items();
						$cIt->setIdPrueba($sIdPrueba);
						$cIt->setIdPruebaHast($sIdPrueba);
						$cIt->setCodIdiomaIso2($sCodIdiomaIso2);
						$sqlItemsPrueba= $cItemsDB->readLista($cIt);
						$listaItemsPrueba = $conn->Execute($sqlItemsPrueba);
						// Montamos la lista de respuestas para los parámetros enviados.

						$sqlRespItems = $cRespuestasPruebasItemsDB->readLista($cRespuestasPruebasItems);
						$listaRespItems = $conn->Execute($sqlRespItems);

						//Inicializamos la puntuación directa y el percentil que más tarde transformaremos
						$iPDirecta = 0;
						$iPercentil = 0;
							while(!$listaRespItems->EOF){

								//Leemos el item para saber cual es la opción correcta
								$cItem = new Items();
								$cItem->setIdItem($listaRespItems->fields['idItem']);
								$cItem->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cItem->setCodIdiomaIso2($sCodIdiomaIso2);
								$cItem = $cItemsDB->readEntidad($cItem);

								//Leemos la opción para saber en código de la misma
								$cOpcion = new Opciones();
								$cOpcion->setIdItem($listaRespItems->fields['idItem']);
								$cOpcion->setIdPrueba($listaRespItems->fields['idPrueba']);
								$cOpcion->setIdOpcion($listaRespItems->fields['idOpcion']);
								$cOpcion->setCodIdiomaIso2($sCodIdiomaIso2);
								$cOpcion = $cOpcionesDB->readEntidad($cOpcion);


								//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
								if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
									//echo $listaRespItems->fields['idItem'] . " - bien <br />";
									//Si es correcta, para este tipo de pruebas
									//Seteo a 1 el campo valor
									$sSQLValor = "UPDATE respuestas_pruebas_items SET valor=1 ";
									$sSQLValor .= " WHERE";
									$sSQLValor .= " idEmpresa='" . $_POST['fIdEmpresa'] . "'";
									$sSQLValor .= " AND idProceso='" . $_POST['fIdProceso'] . "'";
									$sSQLValor .= " AND idCandidato='" . $_POST['fIdCandidato'] . "'";
									$sSQLValor .= " AND codIdiomaIso2='" . $sCodIdiomaIso2 . "'";
									$sSQLValor .= " AND idPrueba='" . $listaRespItems->fields['idPrueba'] . "'";
									$sSQLValor .= " AND idItem='" . $listaRespItems->fields['idItem'] . "'";
//									echo "<br />correcta:: " . $sSQLValor;
									$conn->Execute($sSQLValor);

									//Si coincide se le suma uno a la PDirecta.
									$iPDirecta++;
								}

								$listaRespItems->MoveNext();
							}

							$cBaremos_resultados = new Baremos_resultados();
							$cBaremos_resultados->setIdBaremo($_POST['fIdBaremo']);
							$cBaremos_resultados->setIdPrueba($sIdPrueba);

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

//							echo "pDirecta: " . $iPDirecta . "<br />";
//							echo "pPercentil: " . $iPercentil . "<br />";



		$sHtml.='
			<div class="pagina">'. $sHtmlCab;
    	$sHtml.='	<table width="100%" border="0">';
		$sHtml.='		<tr>
    						<td width="80%">';

		$sUltimoItemRespondido = 0;
		if ($listaRespItems->recordCount() > 0){
			$sUltimoItemRespondido = $listaRespItems->MoveLast();
			$sUltimoItemRespondido = $listaRespItems->fields['idItem'];
		}
		$IR = 0.00;
		$IP = 0.00;
//		$IR = number_format($listaRespItems->recordCount() / $listaItemsPrueba->recordCount(),2);
		//IR= Último ítem respondido por el candidato/Nº total de ítems de la prueba.
		if ($listaItemsPrueba->recordCount() > 0){
			$IR = number_format($sUltimoItemRespondido / $listaItemsPrueba->recordCount(),2);
		}
		$sIR = str_replace("." , "," , $IR);
//		$IP = number_format($iPDirecta/$listaRespItems->recordCount() ,2);
		//IP= Aciertos/Último ítem respondido por el candidato
		if ($sUltimoItemRespondido > 0){
			$IP = number_format($iPDirecta / $sUltimoItemRespondido ,2);
		}
		$sIP = str_replace("." , "," , $IP);
		$POR = number_format($IR*$IP ,2);
		$sPOR = str_replace("." , "," , number_format($POR ,2));

		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("2");
		$cTextos_secciones->setCodIdiomaIso2('es');
		$cTextos_secciones->setIdPrueba($sIdPrueba);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
    	// TEXTO INTRODUCCIÓN



    	$sHtml.='				<p style="text-align:center;background:#004080;height:25px;padding:5px;">
    								<font style="font-size: 21px;color:#FFFFFF;font-weight: bold;">' . mb_strtoupper(html_entity_decode($cTextos_secciones->getTexto(),ENT_QUOTES,"UTF-8"), 'UTF-8').'</font>
    							</p>
    						</td>
    					</tr>
						<tr>
    						<td width="80%">
    							<p style="height:15px;padding:5px;">
    								<font style="font-size: 11px;font-weight: bold;">' . $cPruebas->getCodigo() . '</font>
    							</p>
    						</td>
    					</tr>
    					<tr>
    						<td width="60%" align="center" >
    							<table class="resetTable" cellspacing="30" cellpadding="15" border="0" width="85%">
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">'.constant("STR_NUM_TOTAL_PREGUNTAS").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 18px;color: #6e6e6e;font-weight: bold;">'. $listaItemsPrueba->recordCount().'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ACERTADAS").'(P.D.)</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 18px;color: #6e6e6e;font-weight: bold;">'.$iPDirecta.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">'.constant("STR_PUNTUACION_PERCENTIL").'(P.C.)</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 18px;color: #6e6e6e;font-weight: bold;">'.$iPercentil.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">'.constant("STR_INDICE_RAPIDEZ").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 18px;color: #6e6e6e;font-weight: bold;">'.$sIR.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">'.constant("STR_INDICE_PRECISION").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 18px;color: #6e6e6e;font-weight: bold;">'.$sIP.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">'.constant("STR_PRODUCTO_RENDIMIENTO").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 18px;color: #6e6e6e;font-weight: bold;">'.$sPOR.'</font>
    									</td>
    								</tr>
    							</table>
    						</td>
    					</tr>';
    	$iWhidth = 1;
    	$sHtml.='		<tr>
    						<td width="80%" align="center">
    							<table width="90%" cellspacing="0" cellpadding="0" style="background:#c0c0c0;border-left:3px solid #004080;border-right:3px solid #004080;">
    								<tr>
    									<td width="19%" align="center" style="height:60px;border:2px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">'.constant("STR_PUNT_INFORMES").'</font>
    									<td>
    									<td width="27%" align="center" style="height:60px;border:2px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">' . mb_strtoupper(constant("STR_BAJO_POTENCIAL"), 'UTF-8').'</font>
    									<td>
    									<td width="27%" align="center" style="height:60px;border:2px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">' . mb_strtoupper(constant("STR_MEDIO_POTENCIAL"), 'UTF-8').'</font>
    									<td>
    									<td width="27%" align="center" style="height:60px;border:2px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">' . mb_strtoupper(constant("STR_ALTO_POTENCIAL"), 'UTF-8').'</font>
    									<td>
    								</tr>
    							</table>
    							<table width="90%" cellspacing="0" cellpadding="0" style="border-left:4px solid #004080;border-bottom:3px solid #004080;border-right:4px solid #004080;">
    								<tr>
    									<td width="19%" align="center" style="height:45px;border-right:2px solid #004080;">
    										<font style="font-size: 18px;color: #000000;font-weight: bold;">PD = '.$iPDirecta.'</font>
    									<td>
    									<td width="81%" style="height:45px;border-left:2px solid #004080;">
    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'numeritosEstandar.jpg'.'" style="width:100%;">
    									<td>
    								</tr>
    								<tr>
    									<td width="19%" align="center" style="height:40px;border-right:2px solid #004080;">
    										<font style="font-size: 18px;color: #000000;font-weight: bold;">PC = '.$iPercentil.' %</font>
    									<td>
    									<td width="81%" style="height:40px;border-left:2px solid #004080;vertical-align:middle;">
    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'bodoque_gigante.jpg'.'" style="width:'.$iWhidth*$iPercentil.'%;height:35px;">
    									<td>
    								</tr>
    							</table>
    						</td>
    					</tr>
    					<tr>
    						<td height="40">&nbsp;
    						</td>
    					</tr>
    	';

    	//Miramos lo que tenemos que pintar de los textos segun los rangos.
		$cRangos_textos = new Rangos_textos();
		$cRangos_textos->setIdPrueba($sIdPrueba);
		$cRangos_textos->setCodIdiomaIso2($sCodIdiomaIso2);
    	$cRangos_textos->setIdTipoInforme($idTipoInforme);
    	$cRangos_textos->setOrderBy("`idIr` ASC, `idIp` DESC");
    	$cRangos_ir = new Rangos_ir();

    	$sqlRangosTextos = $cRangos_textosDB->readLista($cRangos_textos);


//		echo "<br />" . $sqlRangosTextos;
		$listaRangosTextos = $conn->Execute($sqlRangosTextos);

		$caseIr = 0;

		$caseIp = 0;
		$bEncontrado=false;
		$bEncontradoIp=false;

		$idRango="";
		$idRangoIp="";

		if($listaRangosTextos->recordCount()>0)
		{
    	$sHtml.='		<tr>
    						<td>
    							<p style="text-align:center;background:#004080;height:25px;padding:5px;">
    								<font style="font-size: 21px;color:#FFFFFF;font-weight: bold;">' . mb_strtoupper(constant("STR_ESTILO_PROCESAMIENTO_MENTAL"), 'UTF-8').'</font>
    							</p>
    						</td>
    					</tr>
    					<tr>
    						<td width="95%">';

			while(!$listaRangosTextos->EOF)
			{
				if(!$bEncontrado)
				{
					$cRango_ir = new Rangos_ir();

					$cRango_ir->setIdRangoIr($listaRangosTextos->fields['idIr']);
					$cRango_ir = $cRangos_irDB->readEntidad($cRango_ir);
					$aRangoSup = explode(" " , $cRango_ir->getRangoSup());
					$aRangoInf = explode(" " , $cRango_ir->getRangoInf());

					$sSignoSup = $aRangoSup[0];
					$sPuntSup = $aRangoSup[1];

					$sSignoInf = $aRangoInf[0];
					$sPuntInf = $aRangoInf[1];


//					if(($IR $sSignoSup $sPuntSup) && ($IR $sSignoInf $sPuntInf)){
//						echo $IR . " es :" . $sSignoSup . " que " . $sPuntSup . " y " . $sSignoInf . " que " . $sPuntInf;
//					}
//					echo "<br />sup: " . $sSignoSup . " inf: " . $sSignoInf;
//					echo "<br />Punt sup: " . $sPuntSup . " Punt inf: " . $sPuntInf;
//					echo "<br />IR: " . $IR ;
//					echo "<br />";

					$idRango="";

					switch ($sSignoSup)
					{
						case "<":
							switch ($sSignoInf)
							{
								case "<":
									if($IR < $sPuntSup && $IR < $sPuntInf){
										//echo $IR . " es : < que " . $sPuntSup . " y < que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case "<=":
									if($IR < $sPuntSup && $IR <= $sPuntInf){
//										echo "<br />" . $IR . " es : < que " . $sPuntSup . " y <= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">":
									if($IR < $sPuntSup && $IR > $sPuntInf){
										//echo $IR . " es : < que " . $sPuntSup . " y > que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">=":
									if($IR < $sPuntSup && $IR >= $sPuntInf){
										//echo $IR . " es : < que " . $sPuntSup . " y >= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
							}
							break;
						case "<=":
							switch ($sSignoInf)
							{
								case "<":
									if($IR <= $sPuntSup && $IR < $sPuntInf){
										//echo $IR . " es : <= que " . $sPuntSup . " y < que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case "<=":
									if($IR <= $sPuntSup && $IR <= $sPuntInf){
										//echo $IR . " es : <= que " . $sPuntSup . " y <= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">":
									if($IR <= $sPuntSup && $IR > $sPuntInf){
//										echo "<br />" . $IR . " es : <= que " . $sPuntSup . " y > que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">=":
									if($IR <= $sPuntSup && $IR >= $sPuntInf){
										//echo $IR . " es : <= que " . $sPuntSup . " y >= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
							}
							break;
						case ">":
							switch ($sSignoInf)
							{
								case "<":
									if($IR > $sPuntSup && $IR < $sPuntInf){
										//echo $IR . " es : > que " . $sPuntSup . " y < que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case "<=":
									if($IR > $sPuntSup && $IR <= $sPuntInf){
										//echo $IR . " es : > que " . $sPuntSup . " y <= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">":
									if($IR > $sPuntSup && $IR > $sPuntInf){
										//echo $IR . " es : > que " . $sPuntSup . " y > que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">=":
									if($IR > $sPuntSup && $IR >= $sPuntInf){
										//echo $IR . " es : > que " . $sPuntSup . " y >= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
							}
							break;
						case ">=":
							switch ($sSignoInf)
							{
								case "<":
									if($IR >= $sPuntSup && $IR < $sPuntInf){
										//echo $IR . " es : >= que " . $sPuntSup . " y < que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case "<=":
									if($IR >= $sPuntSup && $IR <= $sPuntInf){
										//echo $IR . " es : >= que " . $sPuntSup . " y <= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">":
									if($IR >= $sPuntSup && $IR > $sPuntInf){
										//echo $IR . " es : >= que " . $sPuntSup . " y > que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">=":
									if($IR >= $sPuntSup && $IR >= $sPuntInf){
										//echo $IR . " es : >= que " . $sPuntSup . " y >= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
							}
							break;
					}
				}
//				echo "<br />rango " . $idRango . "<br />";


				if(!$bEncontradoIp && !empty($idRango))
				{
					$cRango_ip = new Rangos_ip();

					$cRango_ip->setIdRangoIr($idRango);
					$cRango_ip->setIdRangoIp($listaRangosTextos->fields['idIp']);
					$cRango_ip = $cRangos_ipDB->readEntidad($cRango_ip);
					$aRangoSupIp = explode(" " , $cRango_ip->getRangoSup());
					$aRangoInfIp = explode(" " , $cRango_ip->getRangoInf());

					$sSignoSupIp = $aRangoSupIp[0];
					$sPuntSupIp = $aRangoSupIp[1];
//					echo "<br />sSignoSupIp::" . $sSignoSupIp;
//					echo "<br />sPuntSupIp::" . $sPuntSupIp;
					$sSignoInfIp = $aRangoInfIp[0];
					$sPuntInfIp = $aRangoInfIp[1];
//					echo "<br />sSignoInfIp::" . $sSignoInfIp;
//					echo "<br />sPuntInfIp::" . $sPuntInfIp;
					//Ahora lo hacemos para El IP
					$idRangoIp="";

					switch ($sSignoSupIp)
					{
						case "<":
							switch ($sSignoInfIp)
							{
								case "<":
									if($IP < $sPuntSupIp && $IP < $sPuntInfIp){
//										echo "<br />A::" . $IP . " es : < que " . $sPuntSupIp . " y < que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case "<=":
									if($IP < $sPuntSupIp && $IP <= $sPuntInfIp){
//										echo "<br />B::" . $IP . " es : < que " . $sPuntSupIp . " y <= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">":
									if($IP < $sPuntSupIp && $IP > $sPuntInfIp){
//										echo "<br />C::" . $IP . " es : < que " . $sPuntSupIp . " y > que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">=":
									if($IP < $sPuntSupIp && $IP >= $sPuntInfIp){
//										echo "<br />D::" . $IP . " es : < que " . $sPuntSupIp . " y >= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
							}
							break;
						case "<=":
							switch ($sSignoInfIp)
							{
								case "<":
									if($IP <= $sPuntSupIp && $IP < $sPuntInfIp){
//										echo "<br />E::" . $IP . " es : <= que " . $sPuntSupIp . " y < que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case "<=":
									if($IP <= $sPuntSupIp && $IP <= $sPuntInfIp){
//										echo "<br />F::" . $IP . " es : <= que " . $sPuntSupIp . " y <= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">":
									if($IP <= $sPuntSupIp && $IP > $sPuntInfIp){
//										echo "<br />G::" . $IP . " es : <= que " . $sPuntSupIp . " y > que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">=":
									if($IP <= $sPuntSupIp && $IP >= $sPuntInfIp){
//										echo "<br />H::" . $IP . " es : <= que " . $sPuntSupIp . " y >= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
							}
							break;
						case ">":
							switch ($sSignoInfIp)
							{
								case "<":
									if($IP > $sPuntSupIp && $IP < $sPuntInfIp){
//										echo "<br />I::" . $IP . " es : > que " . $sPuntSupIp . " y < que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case "<=":
									if($IP > $sPuntSupIp && $IP <= $sPuntInfIp){
//										echo "<br />J::" . $IP . " es : > que " . $sPuntSupIp . " y <= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">":
									if($IP > $sPuntSupIp && $IP > $sPuntInfIp){
//										echo "<br />K::" . $IP . " es : > que " . $sPuntSupIp . " y > que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">=":
									if($IP > $sPuntSupIp && $IP >= $sPuntInfIp){
//										echo "<br />L::" . $IP . " es : > que " . $sPuntSupIp . " y >= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
							}
							break;
						case ">=":
							switch ($sSignoInfIp)
							{
								case "<":
									if($IP >= $sPuntSupIp && $IP < $sPuntInfIp){
//										echo "<br />M::" . $IP . " es : >= que " . $sPuntSupIp . " y < que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case "<=":
									if($IP >= $sPuntSupIp && $IP <= $sPuntInfIp){
//										echo "<br />N::" . $IP . " es : >= que " . $sPuntSupIp . " y <= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">":
									if($IP >= $sPuntSupIp && $IP > $sPuntInfIp){
//										echo "<br />Ñ::" . $IP . " es : >= que " . $sPuntSupIp . " y > que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">=":
									if($IP >= $sPuntSupIp && $IP >= $sPuntInfIp){
//										echo "<br />O::" . $IP . " es : >= que " . $sPuntSupIp . " y >= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
							}
							break;
					}
				}
				$listaRangosTextos->MoveNext();
			}
			if (empty($idRango) || empty($idRangoIp)){
				echo "ERROR calculo de rangos. Contacte con el Administrador.";
				exit;
			}
	//		echo "<br />idRango::" . $idRango . " idRangoIp::" . $idRangoIp;
			$cRan_test = new Rangos_textos();
			$cRan_test->setIdPrueba($sIdPrueba);
			$cRan_test->setCodIdiomaIso2($sCodIdiomaIso2);
	    	$cRan_test->setIdTipoInforme($idTipoInforme);
	    	$cRan_test->setIdIp($idRangoIp);
	    	$cRan_test->setIdIr($idRango);

	    	$cRan_test = $cRangos_textosDB->readEntidad($cRan_test);
	    	$sRan_test = $cRan_test->getTexto();
	    	$sHtml .= $cRan_test->getTexto();


		    $sHtml.='			</td>
	    					</tr>';
		}
		if (in_array($sIdPrueba, $aAleatoriasIL))
		{

			$sImg = "KPMG" . $sNombre . ".png";
		    $_PathImg = $spath . $sDirImg;
		    $sCadena = "RESUMEN RESULTADOS";
			grafResumen(400, 300, $sCadena, $_PathImg, $sImg, $iVERBAL, $iLOGICO, $iNUMERICO, $iINGLES, $iINGLESL);

		    $sHtml.='
    					<tr>
    						<td width="95%" align="center">
			    				<img src="' . constant("DIR_WS_GESTOR") . $sDirImg . $sImg . '" border="0"  alt="" title="" />
			    			</td>
	    				</tr>';
		}

    	$sHtml.='		</table>
        	</div>
        	<!--FIN DIV PAGINA-->
    			';
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
//		$sNombre = $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $cPruebas->getNombre();
		$sDirImg="imgInformes/";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");

		$_fichero = $spath . $sDirImg . $sNombre . ".html";
		//$cEntidad->chk_dir($spath . $sDirImg, 0777);

		if(is_file($_fichero)){
			unlink($_fichero);
		}
		error_log(utf8_decode($sHtmlInicio . $sHtml . $sHtmlFin), 3, $_fichero);
	}
//		error_reporting(E_ALL);
//		ini_set("display_errors","1");
		if (ini_get("pcre.backtrack_limit") < 2000000) { ini_set("pcre.backtrack_limit",2000000); };
		@set_time_limit(0);
		@define("OUTPUT_FILE_DIRECTORY", $spath . $sDirImg);
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
                                                      'top'     => 4,
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
/*
		$footer_html    = '
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="100%" align="center"><p style="font-size:10px;"> ' . mb_strtoupper($cPruebas->getNombre(), 'UTF-8') .' '.constant("STR_PIE_INFORMES").'</p></td>
				</tr>
			</table>
			';
*/
		$footer_html    = '
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="100px" align="left"><p style="font-size:10px;"> ' . $_sBaremo . ' ' . '</p></td>
					<td width="100%" align="center"><p style="font-size:10px;"> ' . constant("STR_PIE_INFORMES").'</p></td>
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

	function getPDPrueba($cCandidato, $cPrueba){
		global $conn;
		$iPDirecta = 0;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/Opciones.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valores.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valoresDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/Items.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/ItemsDB.php");

		$cRespuestas_pruebas_itemsDB = new Respuestas_pruebas_itemsDB($conn);
		$cItemsDB = new ItemsDB($conn);

		$cOpcionesDB = new OpcionesDB($conn);
		$cOpciones_valoresDB = new Opciones_valoresDB($conn);

		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();

		$cRespuestas_pruebas_items->setIdEmpresa($cCandidato->getIdEmpresa());
		$cRespuestas_pruebas_items->setIdProceso($cCandidato->getIdProceso());
		$cRespuestas_pruebas_items->setIdCandidato($cCandidato->getIdCandidato());
		$cRespuestas_pruebas_items->setIdPrueba($cPrueba->getIdPrueba());
		$cRespuestas_pruebas_items->setCodIdiomaIso2($cPrueba->getCodIdiomaIso2());
		$cRespuestas_pruebas_items->setOrderBy("idItem");
		$cRespuestas_pruebas_items->setOrder("ASC");

		$cIt = new Items();
		$cIt->setIdPrueba($cPrueba->getIdPrueba());
		$cIt->setIdPruebaHast($cPrueba->getIdPrueba());
		$cIt->setCodIdiomaIso2($cPrueba->getCodIdiomaIso2());
		$sqlItemsPrueba= $cItemsDB->readLista($cIt);
		$listaItemsPrueba = $conn->Execute($sqlItemsPrueba);

		// Montamos la lista de respuestas para los parámetros enviados.
		$sqlRespItems = $cRespuestas_pruebas_itemsDB->readLista($cRespuestas_pruebas_items);
		$listaRespItems = $conn->Execute($sqlRespItems);

		if($listaRespItems->recordCount() > 0)
		{
			while(!$listaRespItems->EOF){

				//Leemos el item para saber cual es la opción correcta
				$cItem = new Items();
				$cItem->setIdItem($listaRespItems->fields['idItem']);
				$cItem->setIdPrueba($listaRespItems->fields['idPrueba']);
				$cItem->setCodIdiomaIso2($cPrueba->getCodIdiomaIso2());
				$cItem = $cItemsDB->readEntidad($cItem);

				//Leemos la opción para saber en código de la misma
				$cOpcion = new Opciones();
				$cOpcion->setIdItem($listaRespItems->fields['idItem']);
				$cOpcion->setIdPrueba($listaRespItems->fields['idPrueba']);
				$cOpcion->setIdOpcion($listaRespItems->fields['idOpcion']);
				$cOpcion->setCodIdiomaIso2($cPrueba->getCodIdiomaIso2());
				$cOpcion = $cOpcionesDB->readEntidad($cOpcion);

				//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
				if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
					//echo $listaRespItems->fields['idItem'] . " - bien <br />";
					//Si coincide se le suma uno a la PDirecta.
					$iPDirecta++;
				}
				$listaRespItems->MoveNext();
			}
		}
		$iPDirecta = (($iPDirecta*100) / $listaItemsPrueba->recordCount());
		return $iPDirecta;
	}
?>
