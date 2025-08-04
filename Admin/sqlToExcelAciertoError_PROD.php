<?php
	require_once('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "ToXLS.php");
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	require_once(constant("DIR_WS_COM") . "Items/ItemsDB.php");
	require_once(constant("DIR_WS_COM") . "Items/Items.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");

include_once ('include/conexion.php');

	$cUtilidades	= new Utilidades();
//	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	$cRespuestas_pruebas	= new Respuestas_pruebas();  // Entidad
	$cRespuestas_pruebasDB	= new Respuestas_pruebasDB($conn);  // Entidad
	$cItems	= new Items();  // Entidad
	$cItemsDB	= new ItemsDB($conn);  // Entidad

	$_idEmpresa="";
	$_descEmpresa="";
	$_idProceso="";
	$_descProceso="";
	$_codIdiomaIso2="";
	$_descIdiomaIso2="";
	$_idPrueba="";
	$_descPrueba="";
	$_idCandidato="";

	$sDESCListaExcel = "";
	$aArray =explode(constant("CHAR_SEPARA"), base64_decode($_REQUEST['fSQLtoEXCEL']));
	$sql = $aArray[0];
	$nombre = $aArray[1];
	if (!empty($sql)){
		$sEntidad = ucfirst($nombre);
		require_once(constant("DIR_WS_COM") . $sEntidad . "/" . $sEntidad . ".php");
		$cEntidad	= new $sEntidad();  // Entidad
		$sDESCListaExcel = $cEntidad->getDESCListaExcel();
	}else{
		echo constant("ERR");
		exit;
	}
//	echo "<br />" . $sql;exit;
	$vVector = $conn->Execute($sql);
	//Los modifico con los valores pulsados ya tratados
	trataValores($vVector, $nombre);
	$aCabExcel		=	array("descProceso","nomPrueba","item","valor","DNI/ N. ID");
	$aPK	=	array("Proceso","Prueba","Item","Valor","Candidato");


		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversos.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");

		$cItems_inversosDB = new Items_inversosDB($conn);
		$cItems_inversos = new Items_inversos();

		$cItems_inversos->setIdPrueba($_idPrueba);
		$cItems_inversos->setIdPruebaHast($_idPrueba);
		$sqlInversos = $cItems_inversosDB->readLista($cItems_inversos);
//		echo "<br />" . $sqlInversos;
		$listaInversos = $conn->Execute($sqlInversos);
		$nInversos = $listaInversos->recordCount();
		$aInversos = array();
		if($nInversos>0){
			$i=0;
			while(!$listaInversos->EOF){
				$aInversos[$i] = $listaInversos->fields['idItem'];
				$i++;
				$listaInversos->MoveNext();
			}
		}
		$cBloquesDB = new BloquesDB($conn);
		$cEscalasDB = new EscalasDB($conn);

		$aPuntuaciones = array();
		$aPuntuacionesConsistencia = array();
		$aPuntuacionesCompetencias = array();


	$sSQLCandidatos = str_replace("respuestas_pruebas_items", "respuestas_pruebas", $sql);
	$sSQLCandidatos = str_replace("idProceso,idCandidato,idItem ASC", "idCandidato, idProceso", $sSQLCandidatos);
//	echo "<br />" . $sSQLCandidatos;exit;
	$vVectorCandidatos = $conn->Execute($sSQLCandidatos);

	$buf = "";
	$buf .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	$buf .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"es\">";
	// put in some style
	$buf .= "<head>" .
	"<title>Export</title>" .
	"<meta name=\"language\" content=\"es\" />" .
	"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	$buf .= "</head>";

	// generate the body
	$buf .= "<body>";
	$buf .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	$buf .= "<tr>";
	$buf .= "<td align=\"center\"><b>" . $_descPrueba . "</b></td>";

	//Recorro el vector de usuarios y pinto sus DNI'S
	while (!$vVectorCandidatos->EOF){
		$buf .= "<td align=\"center\">" . $vVectorCandidatos->fields['descCandidato'] . "</td>";
		$vVectorCandidatos->MoveNext();
	}

	$primerUsuario =0;
	$vVectorCandidatos->Move(0);
	$respBD = new Respuestas_pruebas_itemsDB($conn);
	//$vVector->Move(0);
	$cItems->setIdPrueba($_idPrueba);
	$cItems->setCodIdiomaIso2($_codIdiomaIso2);
	$cItems->setOrderBy("orden");
	$cItems->setOrder("ASC");
	$sSQLItems = $cItemsDB->readLista($cItems);
	$vItems = $conn->Execute($sSQLItems);
	//Comienzo a recorrer el while para pintar los items con sus valores
	while (!$vItems->EOF  )
	{
		$buf .= "<tr>";
		$buf .= "<td align=\"center\">" . $vItems->fields['orden'] . "</td>";
		while (!$vVectorCandidatos->EOF){
			$resp = new Respuestas_pruebas_items();
			$resp->setIdEmpresa($vVectorCandidatos->fields['idEmpresa']);
			$resp->setIdProceso($vVectorCandidatos->fields['idProceso']);
			$resp->setIdCandidato($vVectorCandidatos->fields['idCandidato']);
			$resp->setCodIdiomaIso2($vVectorCandidatos->fields['codIdiomaIso2']);
			$resp->setIdPrueba($vItems->fields['idPrueba']);
			$resp->setIdItem($vItems->fields['idItem']);
			$resp = $respBD->readEntidad($resp);
			$buf .= "<td align=\"center\">" . $resp->getValor() . "</td>";
			$vVectorCandidatos->MoveNext();
		}
		//Inicializamos para siguiente fila
		$vVectorCandidatos->Move(0);
		$buf .= "</tr>";
		$vItems->MoveNext();
	}  //Fin del while

		$sHtml ="";
		switch ($_idPrueba)
		{
			case 10:	//Tipo Prisma TEC
			case 12:	//Cel16
			case 13:	//Tipo Prisma CML
			case 24:	//Prisma
				$vVectorCandidatos->Move(0);
				while (!$vVectorCandidatos->EOF){
					calculosGlobalesEscala($vVectorCandidatos->fields['idCandidato'], $vVectorCandidatos->fields['idProceso']);
					$vVectorCandidatos->MoveNext();
				}

					// ENERGÍAS Y MOTIVACIONES
					$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
					$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
					$i=0;
					$cEscalas_items=  new Escalas_items();
					$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
					$cEscalas_items->setIdPrueba($_idPrueba);
					$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
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
					$cBloques->setCodIdiomaIso2($_codIdiomaIso2);
					$cBloques->setIdBloque($sBloques);
					$cBloques->setOrderBy("idBloque");
					$cBloques->setOrder("ASC");
					$sqlBloques = $cBloquesDB->readLista($cBloques);
					$listaBloques = $conn->Execute($sqlBloques);

					$iPosiImg=0;
					$iPGlobal = 0;
					$nBloques= $listaBloques->recordCount();

					if($nBloques>0){
						while(!$listaBloques->EOF){

							$cEscalas = new Escalas();
						 	$cEscalas->setCodIdiomaIso2($_codIdiomaIso2);
						 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
						 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
						 	$cEscalas->setOrderBy("idEscala");
						 	$cEscalas->setOrder("ASC");
						 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
						 	$listaEscalas = $conn->Execute($sqlEscalas);
						 	$nEscalas=$listaEscalas->recordCount();
//						 	echo $nEscalas;exit;
						 	if($nEscalas > 0){
						 		while(!$listaEscalas->EOF){
						 			$bPrimeraVuelta = true;
						 			$vVectorCandidatos->Move(0);
								    $sHtml.='<tr>';
									while (!$vVectorCandidatos->EOF){
								        $iPBaremada = $aPuntuaciones[$vVectorCandidatos->fields['idCandidato']][$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
								        if ($bPrimeraVuelta){
									        $sHtml.='
												<td align="center"><strong>' . $listaEscalas->fields['nombre'] . '</strong></td>
											';
								        }
								        $sHtml.='
								        	<td align="center"><strong>' . $iPBaremada . '</strong></td>
											';
							       		$bPrimeraVuelta = false;
										$vVectorCandidatos->MoveNext();
									}
								    $sHtml.='
								       	</tr>
								       	';
							        $listaEscalas->MoveNext();
						 		}
						 	}
						 	$iPosiImg++;
						 	$listaBloques->MoveNext();
						 }
					 }
					$bPrimeraVuelta = true;
					$vVectorCandidatos->Move(0);
                    if ($_idPrueba == 24){
					$sHtml.='<tr>';
    						while (!$vVectorCandidatos->EOF){
    					        if ($bPrimeraVuelta){
    						        $sHtml.='
    									<td align="center"><strong>Consistencia</strong></td>
    								';
    					        }
    					        $sHtml.='
    					        	<td align="center"><strong>' . $aPuntuacionesConsistencia[$vVectorCandidatos->fields['idCandidato']] . '</strong></td>
    								';
    				       		$bPrimeraVuelta = false;
    							$vVectorCandidatos->MoveNext();
    						}
    					    $sHtml.='
    					       	</tr>
					       	';
                    }

				break;
			case 11:
				while (!$vVectorCandidatos->EOF){
					calculosGlobalesCompetencia($vVectorCandidatos->fields['idCandidato'], $vVectorCandidatos->fields['idProceso']);
					$vVectorCandidatos->MoveNext();
				}

				//CALCULOS GLOBALES COMPETENCIAS
				$cCompetenciasDB = new CompetenciasDB($conn);
				$cCompetencias_itemsDB = new Competencias_itemsDB($conn);
				$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
				$cTipos_competenciasDB = new Tipos_competenciasDB($conn);
				$cTipos_competencias = new Tipos_competencias();
				$cTipos_competencias->setCodIdiomaIso2($_codIdiomaIso2);
				$cTipos_competencias->setCodIdiomaIso2('es');
				$cTipos_competencias->setIdPrueba($_idPrueba);
				$cTipos_competencias->setOrderBy("idTipoCompetencia");
				$cTipos_competencias->setOrder("ASC");
				$sqlTipos_competencias = $cTipos_competenciasDB->readLista($cTipos_competencias);
		//      	echo "<br />-->" . $sqlTipos_competencias . "";
				$listaTipoCompetencia = $conn->Execute($sqlTipos_competencias);
				$nTCompetencias= $listaTipoCompetencia->recordCount();
		//		$aPuntuacionesCompetencias = array();
				if($nTCompetencias>0){
					while(!$listaTipoCompetencia->EOF){

						$cCompetencias = new Competencias();
					 	$cCompetencias->setCodIdiomaIso2($_codIdiomaIso2);
						$cCompetencias->setCodIdiomaIso2('es');
					 	$cCompetencias->setIdTipoCompetencia($listaTipoCompetencia->fields['idTipoCompetencia']);
					 	$cCompetencias->setIdTipoCompetenciaHast($listaTipoCompetencia->fields['idTipoCompetencia']);
					 	$cCompetencias->setIdPrueba($_idPrueba);
					 	$cCompetencias->setOrderBy("idCompetencia");
					 	$cCompetencias->setOrder("ASC");
					 	$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		//			 	echo "<br />" . $sqlCompetencias . "";
					 	$listaCompetencias = $conn->Execute($sqlCompetencias);
					 	$nCompetencias=$listaCompetencias->recordCount();
					 	if($nCompetencias >0){
					 		while(!$listaCompetencias->EOF){

					 			$bPrimeraVuelta = true;
						 		$vVectorCandidatos->Move(0);
								$sHtml.='<tr>';
								while (!$vVectorCandidatos->EOF){
									$sPosiCompetencias = $listaTipoCompetencia->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia'];
							        if ($bPrimeraVuelta){
								        $sHtml.='
											<td align="center"><strong>' . $listaCompetencias->fields['nombre'] . '</strong></td>
										';
							        }
								    $sHtml.='
								    	<td align="center"><strong>' . $aPuntuacionesCompetencias[$vVectorCandidatos->fields['idCandidato']][$sPosiCompetencias] . '</strong></td>
									';
							       	$bPrimeraVuelta = false;
									$vVectorCandidatos->MoveNext();
								}
								$sHtml.='
								   	</tr>
								';
						        $listaCompetencias->MoveNext();
					 		}
					 	}
					 	$listaTipoCompetencia->MoveNext();
					 }
				 }

				//FIN CALCULOS GLOBALES COMPETENCIAS
				break;
		} // end switch



	$buf .= $sHtml;

	$buf .= "</table>";
	$buf .= "</body>";
	$buf .= "</html>";

//echo $buf;exit;
	if (empty($nombre)){
		$nombre = "ExcelFile";
	}
	if (empty($_REQUEST['fPintaCabecera'])){
		$_REQUEST['fPintaCabecera'] = true;
	}
	if (empty($_REQUEST['fSepararCabecera'])){
		$_REQUEST['fSepararCabecera'] = false;
	}

	// Genera arquivo(xls)

	header ("Cache-Control: no-cache, must-revalidate");
	header ("Content-Description: NegociaInternet Generador de XLS");
	header ("Content-Disposition: attachment; filename=" . $nombre . ".xls");
	header ('Content-Transfer-Encoding: binary');
	header ('Content-Type: application/force-download');
	header ('Content-Type: application/octet-stream');
	header ("Content-type: application/x-msexcel");
	header ("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
	header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
	header ("Pragma: no-cache");
	print  ( $buf);

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

	function trataValores($rs, $nombre){
		$i=0;
		while (!$rs->EOF){
			setValor($rs, $nombre, $i);
			$i++;
			$rs->MoveNext();
		}
	}
	function setValor($rsLine, $nombre, $i){
		global $conn;
		global $cUtilidades;
		global $_idEmpresa;
		global $_descEmpresa;
		global $_idProceso;
		global $_descProceso;
		global $_codIdiomaIso2;
		global $_descIdiomaIso2;
		global $_idPrueba;
		global $_descPrueba;
		global $_idCandidato;

		require_once(constant("DIR_WS_COM") . "Items/ItemsDB.php");
		require_once(constant("DIR_WS_COM") . "Items/Items.php");
		$cItems = new Items();
		$cItems->setCodIdiomaIso2($rsLine->fields['codIdiomaIso2']);
		$cItems->setIdPrueba($rsLine->fields['idPrueba']);
		$cItems->setIdItem($rsLine->fields['idItem']);
//		$cItems->setIdOpcion($rsLine->fields['idOpcion']);

		$cItemsDB = new ItemsDB($conn);
		$cItems = $cItemsDB->readEntidad($cItems);
		$sValor="";
		$sValor=$cUtilidades->getValorCalculadoPRUEBAS($rsLine,$cItems, $conn);
		if ($i==0){
			$_idEmpresa=$rsLine->fields['idEmpresa'];
			$_descEmpresa=$rsLine->fields['descEmpresa'];
			$_idProceso=$rsLine->fields['idProceso'];
			$_descProceso=$rsLine->fields['descProceso'];
			$_codIdiomaIso2=$rsLine->fields['codIdiomaIso2'];
			$_descIdiomaIso2=$rsLine->fields['descIdiomaIso2'];
			$_idPrueba=$rsLine->fields['idPrueba'];
			$_descPrueba=$rsLine->fields['descPrueba'];
			$_idCandidato=$rsLine->fields['idCandidato'];
		}
		$sWHERE = "";
		$sWHERE .= " WHERE idEmpresa='" . $rsLine->fields['idEmpresa'] . "' ";
		$sWHERE .= " AND idProceso='" . $rsLine->fields['idProceso'] . "' ";
		$sWHERE .= " AND idCandidato='" . $rsLine->fields['idCandidato'] . "' ";
		$sWHERE .= " AND codIdiomaIso2='" . $rsLine->fields['codIdiomaIso2'] . "' ";
		$sWHERE .= " AND idPrueba='" . $rsLine->fields['idPrueba'] . "' ";
		$sWHERE .= " AND idItem='" . $rsLine->fields['idItem'] . "' ";
		$sWHERE .= " AND idOpcion='" . $rsLine->fields['idOpcion'] . "' ";

		$sSQL = " UPDATE " . $nombre . " SET valor='" . $sValor . "' " . $sWHERE;
//		echo "<br />" . $sSQL;
		$conn->Execute($sSQL);
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
	function calculosGlobalesEscala($_idCandidato, $_idProceso){

		global $conn;
		global $cUtilidades;
		global $_codIdiomaIso2;
		global $_idPrueba;
		global $_idEmpresa;
		//global $_idProceso;
		global $cBloquesDB;
		global $cEscalasDB;
		global $aInversos;
		global $aPuntuaciones;
		global $aPuntuacionesConsistencia;
		global $aPuntuacionesCompetencias;
		global $cItems;
		global $cItemsDB;
		$iPGlobal = 0;
		$iTotalEscalas=0;
		// CÁLCULOS GLOBALES PARA ESCALAS,
		// Se hace fuera y los metemos en un array para
		// reutilizarlo en varias funciones
		$cEscalas_items=  new Escalas_items();
		$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
		$cEscalas_items->setIdPrueba($_idPrueba);
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
		$cBloques->setCodIdiomaIso2($_codIdiomaIso2);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
//		echo "<br />" . $sqlBloques;
		$listaBloques = $conn->Execute($sqlBloques);
		$nBloques= $listaBloques->recordCount();

		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_codIdiomaIso2);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iTotalEscalas +=$nEscalas;
			 		while(!$listaEscalas->EOF){
				        $cEscalas_items = new Escalas_items();
				        $cEscalas_items->setIdEscala($listaEscalas->fields['idEscala']);
				        $cEscalas_items->setIdEscalaHast($listaEscalas->fields['idEscala']);
				        $cEscalas_items->setIdBloque($listaEscalas->fields['idBloque']);
				        $cEscalas_items->setIdBloqueHast($listaEscalas->fields['idBloque']);
				        $cEscalas_items->setIdPrueba($_idPrueba);
				        $cEscalas_items->setOrderBy("idItem");
				        $cEscalas_items->setOrder("ASC");
				        $sqlEscalas_items = $cEscalas_itemsDB->readLista($cEscalas_items);
//						echo "<br />" . $sqlEscalas_items;
				        $listaEscalas_items = $conn->Execute($sqlEscalas_items);
				        $nEscalas_items =$listaEscalas_items->recordCount();

				        $iPd = 0;
				        if($nEscalas_items > 0){
				        	while(!$listaEscalas_items->EOF)
				        	{
				        		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();

				        		$cRespuestas_pruebas_items->setIdEmpresa($_idEmpresa);
								$cRespuestas_pruebas_items->setIdProceso($_idProceso);
								$cRespuestas_pruebas_items->setIdCandidato($_idCandidato);
								$cRespuestas_pruebas_items->setIdPrueba($_idPrueba);
								$cRespuestas_pruebas_items->setCodIdiomaIso2($_codIdiomaIso2);
								$cRespuestas_pruebas_items->setIdItem($listaEscalas_items->fields['idItem']);
								$sSQLRPI = $cRespuestas_pruebas_itemsBD->readLista($cRespuestas_pruebas_items);
								$vRPI = $conn->Execute($sSQLRPI);

								$cItems = new Items();
								$cItems->setCodIdiomaIso2($_codIdiomaIso2);
								$cItems->setIdPrueba($_idPrueba);
								$cItems->setIdItem($listaEscalas_items->fields['idItem']);
								$cItemsDB = new ItemsDB($conn);
								$cItems = $cItemsDB->readEntidad($cItems);

								$iPd += $cUtilidades->getValorCalculadoPRUEBAS($vRPI, $cItems, $conn);
/*
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
*/
								$listaEscalas_items->MoveNext();
				        	}
				        }

				        $cBaremos_resultado = new Baremos_resultados();
				        $cBaremos_resultado->setIdBaremo(0);
				        $cBaremos_resultado->setIdPrueba($_idPrueba);
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
				       	$aPuntuaciones[$_idCandidato][$sPosi] =  $iPBaremada;
				       	$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);
				        $listaEscalas->MoveNext();
			 		}
			 	}
			 	$listaBloques->MoveNext();
			 }
		 }
//		 echo "<br />" . $iTotalEscalas;exit;
		 $consistencia = baremo_C(number_format(sqrt($iPGlobal/$iTotalEscalas)*100 ,0));
		 $aPuntuacionesConsistencia[$_idCandidato] =  $consistencia;
	// FIN CALCULOS GLOBALES ESCALAS

	}

	function calculosGlobalesCompetencia($_idCandidato, $_idProceso){

		global $conn;
		global $cUtilidades;
		global $_codIdiomaIso2;
		global $_idPrueba;
		global $_idEmpresa;
		//global $_idProceso;
		global $cBloquesDB;
		global $cEscalasDB;
		global $aInversos;
		global $aPuntuaciones;
		global $aPuntuacionesCompetencias;
		global $cItems;
		global $cItemsDB;

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados_competencias/Baremos_resultados_competenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados_competencias/Baremos_resultados_competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_itemsDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_items.php");

		//CALCULOS GLOBALES COMPETENCIAS
		$cBaremos_resultados_competenciasDB = new Baremos_resultados_competenciasDB($conn);
		$cCompetenciasDB = new CompetenciasDB($conn);
		$cCompetencias_itemsDB = new Competencias_itemsDB($conn);
		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cTipos_competenciasDB = new Tipos_competenciasDB($conn);
		$cTipos_competencias = new Tipos_competencias();
		$cTipos_competencias->setCodIdiomaIso2($_codIdiomaIso2);
		$cTipos_competencias->setCodIdiomaIso2('es');
		$cTipos_competencias->setIdPrueba($_idPrueba);
		$cTipos_competencias->setOrderBy("idTipoCompetencia");
		$cTipos_competencias->setOrder("ASC");
		$sqlTipos_competencias = $cTipos_competenciasDB->readLista($cTipos_competencias);
//      	echo "<br />-->" . $sqlTipos_competencias . "";
		$listaTipoCompetencia = $conn->Execute($sqlTipos_competencias);
		$nTCompetencias= $listaTipoCompetencia->recordCount();
//		$aPuntuacionesCompetencias = array();
		if($nTCompetencias>0){
			while(!$listaTipoCompetencia->EOF){

				$cCompetencias = new Competencias();
			 	$cCompetencias->setCodIdiomaIso2($_codIdiomaIso2);
				$cCompetencias->setCodIdiomaIso2('es');
			 	$cCompetencias->setIdTipoCompetencia($listaTipoCompetencia->fields['idTipoCompetencia']);
			 	$cCompetencias->setIdTipoCompetenciaHast($listaTipoCompetencia->fields['idTipoCompetencia']);
			 	$cCompetencias->setIdPrueba($_idPrueba);
			 	$cCompetencias->setOrderBy("idCompetencia");
			 	$cCompetencias->setOrder("ASC");
			 	$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
//			 	echo "<br />" . $sqlCompetencias . "";
			 	$listaCompetencias = $conn->Execute($sqlCompetencias);
			 	$nCompetencias=$listaCompetencias->recordCount();
			 	if($nCompetencias >0){
			 		while(!$listaCompetencias->EOF){

				        $cCompetencias_items = new Competencias_items();
				        $cCompetencias_items->setIdCompetencia($listaCompetencias->fields['idCompetencia']);
				        $cCompetencias_items->setIdCompetenciaHast($listaCompetencias->fields['idCompetencia']);
				        $cCompetencias_items->setIdTipoCompetencia($listaCompetencias->fields['idTipoCompetencia']);
				        $cCompetencias_items->setIdTipoCompetenciaHast($listaCompetencias->fields['idTipoCompetencia']);
				        $cCompetencias_items->setIdPrueba($_idPrueba);
				        $cCompetencias_items->setOrderBy("idItem");
				        $cCompetencias_items->setOrder("ASC");
				        $sqlCompetencias_items = $cCompetencias_itemsDB->readLista($cCompetencias_items);
//				       	echo "<br />" . $sqlCompetencias_items . "";
//				       	echo "<br />" . $listaCompetencias->fields['nombre'];
				        $listaCompetencias_items = $conn->Execute($sqlCompetencias_items);
				        $nCompetencias_items =$listaCompetencias_items->recordCount();
				        $iPdCompetencias = 0;
				        if($nCompetencias_items>0){
				        	while(!$listaCompetencias_items->EOF){
				        		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
				        		$cRespuestas_pruebas_items->setIdEmpresa($_idEmpresa);
								$cRespuestas_pruebas_items->setIdProceso($_idProceso);
								$cRespuestas_pruebas_items->setIdCandidato($_idCandidato);
								$cRespuestas_pruebas_items->setIdPrueba($_idPrueba);
								$cRespuestas_pruebas_items->setCodIdiomaIso2($_codIdiomaIso2);
								$cRespuestas_pruebas_items->setIdItem($listaCompetencias_items->fields['idItem']);

								$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);
//				        		echo "<br />ITEM:" . $listaCompetencias_items->fields['idItem'] . " - opcion:: " . $cRespuestas_pruebas_items->getIdOpcion() . " DESC:: " . $cRespuestas_pruebas_items->getDescOpcion();
								//MEJOR => 2 PEOR => 0 VACIO => 1
								switch ($cRespuestas_pruebas_items->getIdOpcion())
								{
									case '1':
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
				        $cBaremos_resultado_competencias->setIdPrueba($_idPrueba);
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
				       	$aPuntuacionesCompetencias[$_idCandidato][$sPosiCompetencias] =  $iPdCompetencias;
//				       	echo "<br />" . $listaCompetencias->fields['nombre'] . "[" . $sPosiCompetencias . "] - PD: " . $iPdCompetencias . " PBaremada: " . $iPBaremadaCompetencias;
				        $listaCompetencias->MoveNext();
			 		}
			 	}
			 	$listaTipoCompetencia->MoveNext();
			 }
		 }

		//FIN CALCULOS GLOBALES COMPETENCIAS
	}
?>
