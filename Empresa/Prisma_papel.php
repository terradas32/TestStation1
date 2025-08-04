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
	require_once(constant("DIR_WS_COM") . "Prisma_papel/Prisma_papelDB.php");
	require_once(constant("DIR_WS_COM") . "Prisma_papel/Prisma_papel.php");

include_once ('include/conexion.php');

		$connMssql = NewADOConnection('ado_mssql');
		$connMssql->charPage = 65001;	//utf8
		$dsn ='Provider=SQLNCLI;Server=' . constant("DB_HOST_MSD") . ';Database=' . constant("DB_DATOS_MSD") . ';Uid=' . constant("DB_USUARIO_MSD") . ';Pwd=' . constant("DB_PASSWORD_MSD");
		$connMssql->Connect($dsn);
		$connMssql->SetFetchMode(constant("ADODB_FETCH_ASSOC"));
		if (empty($connMssql)){
	        echo(constant("ERR") . " MS SQL SERVER");
			exit;
	    }

	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new Prisma_papelDB($conn);  // Entidad DB
	$cEntidad	= new Prisma_papel();  // Entidad

	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';

	$comboWI_USUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","fecMod");

	//echo('modo:' . $_POST['MODO']);

	if (!isset($_POST["MODO"])){
	session_start();
    $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_NO_AUTORIZADO");
	header("Location: " . constant("HTTP_SERVER") . "msg.php");
	exit;
	}
	switch ($_POST['MODO'])
	{
		case constant("MNT_ALTA"):
			$cEntidad	= readEntidad($cEntidad);
			$newId	= $cEntidadDB->insertar($cEntidad);
			if (!empty($newId)){
			?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
				if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
				{
					if (!empty($_POST['prisma_papel_next_page']) && $_POST['prisma_papel_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}
					$pager = new ADODB_Pager($conn,$sql,'prisma_papel');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Prisma_papel/mntprisma_papell.php');
				}else{
					$cEntidad	= new Prisma_papel();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Prisma_papel/mntprisma_papela.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Prisma_papel/mntprisma_papela.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			if ($cEntidadDB->modificar($cEntidad))
			{
				if (!empty($_POST['prisma_papel_next_page']) && $_POST['prisma_papel_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}
				$pager = new ADODB_Pager($conn,$sql,'prisma_papel');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Prisma_papel/mntprisma_papell.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Prisma_papel/mntprisma_papela.php');
			}
			break;
		case constant("MNT_BORRAR"):
			$cEntidad = readEntidad($cEntidad);
			if (!$cEntidadDB->borrar($cEntidad))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php 
			}
					if (!empty($_POST['prisma_papel_next_page']) && $_POST['prisma_papel_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}
			$pager = new ADODB_Pager($conn,$sql,'prisma_papel');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Prisma_papel/mntprisma_papell.php');
			break;
		case constant("MNT_NUEVO"):
			set_time_limit(0);
			$sMsgResumen='';
			if(!empty($_POST['fUsuariosBien'])){
				$sMsgResumen.='<ul>';
				$aUsuarios = explode("," , $_POST['fUsuariosBien']);
				for($i=0;$i<sizeof($aUsuarios);$i++){
					$aUsuario = explode("-" , $aUsuarios[$i]);
					$usuario = $aUsuario[0]."-".substr($aUsuario[1],3,strlen($aUsuario[1]));

					if($cUtilidades->verifica_url("http://snegocia1:90/test-station/prisma/generaPdf_NI.asp?usr=".$aUsuarios[$i]."")){
						$sMsgResumen .="<li><a class=\"negro\" target=\"_blank\" href=\"http://snegocia1/PsicologosEmpresariales/ASP/test-station.com/informes/".$aUsuarios[$i].".pdf\" title=\"Pdf ".$usuario."\">Descargar Pdf usuario ".$usuario."</a></li>";
					}else{
						$sMsgResumen .='<li>Error en el usuario'. $usuario . '<br /></li>';
					}
				}
				$sMsgResumen .="</ul>";
			}else{
				$sMsgResumen = 'No hay usuarios para los que generar Pdf';
				exit;
			}
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Prisma_papel/mntprisma_papela.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Prisma_papel/mntprisma_papela.php');
			break;
		case constant("MNT_BUSCAR"):
			$cEntidad = new Prisma_papel();
			$cEntidad->setOrderBy("carga");
			$cEntidad->setOrder("ASC");
			$sqlPorCarga = $cEntidadDB->readListaPorCarga($cEntidad);
			$listaCargas = $conn->Execute($sqlPorCarga);
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Prisma_papel/mntprisma_papel.php');
			break;
		case constant("MNT_LISTAR"):
			set_time_limit(0);
			$cEntidad = new Prisma_papel();
			$cEntidad->setCarga($_POST['fCarga']);
			$cEntidad->setCargaHast($_POST['fCarga']);
			$cEntidad->setOrderBy('orden');
			$cEntidad->setOrder('ASC');
			$sqlPrismas = $cEntidadDB->readLista($cEntidad);
			$listaPrismas = $conn->Execute($sqlPrismas);
			$sSinContestarTodas="";
			$sMalContestadas="";
			$sBienContestadas = "";
			$sUsuariosBienContestadas = "";
			$iBien=0;
			$iMal=0;
			$sEmpresa="PRUEBA PE";
			$sCarga = $_POST['fCarga'];
			$sqlRespuestas="";
			$sqlUsuarios="";
			$sqlInforme="";
			$sqlInforme1="";
			if($listaPrismas->recordCount()>0){

				//$sqlUsuarios= "INSERT INTO usuarios (titulo, nombre, apellido1, mail, pass, sexo, enviado_mail, fecha_alta, proceso, proceso_terminado, empresa) VALUES";
				while(!$listaPrismas->EOF){
					$sCadena = $listaPrismas->fields['prisma'];
					$sLCadena = strlen($sCadena);
//					$aUsuario = explode("-" , $listaPrismas->fields['usuario']);
//					$usuario = $aUsuario[0]."-".substr($aUsuario[1],3,strlen($aUsuario[1]));
					$usuario = $listaPrismas->fields['usuario'];
					$codigo = $listaPrismas->fields['codigo'];

					// Si la cadena contiene algún espacio quiere decir que han dejado preguntas sin responder
					$aConEspacios = explode(" " , $sCadena);
					//Si la cadena contiene algún interrogante quiere decir que han respondido en la misma pregunta la misma mejor y peor.
					$aConInterrogantes = explode("?" , $sCadena);
					$sSexo="";
					if($listaPrismas->fields['sexo']=="V"){
						$sSexo="M";
					}else{
						$sSexo="F";
					}
					if(($sLCadena < 192) || (sizeof($aConEspacios) > 1) || (sizeof($aConInterrogantes) > 1)){
						//echo strlen($listaPrismas->fields['prisma']) . " NO OK<br />";

						$sMalContestadas .= ", " . $listaPrismas->fields['codigo'];
						$iMal++;
					}else{
						$sBienContestadas .= ", " . $listaPrismas->fields['codigo'];
						$sUsuariosBienContestadas .= "," . $listaPrismas->fields['usuario'];
						$sqlUsuarios.= "INSERT INTO usuarios (titulo, nombre, apellido1, mail, pass, sexo, enviado_mail, fecha_alta, proceso, proceso_terminado, empresa,confirmacion,idioma,fecha_caducidad,finalizado,modo,activo,mail_envio,generadoPDF) VALUES";
						$sqlUsuarios.= "('SR' ,'". $codigo ."','". $codigo ."','jsola@negociainternet.com','".$usuario."','".$sSexo."','0','".date('d/m/Y H:i:s')."','".date('dmY').$sCarga."','0','".$sEmpresa."','1','es','25/05/2011 0:00:00','1','C','1','gsm@psicologosempresariales.es','".date('d/m/Y H:i:s')."');";
						//echo strlen($listaPrismas->fields['prisma']) . " OK<br />";

						$sqlInforme.= "INSERT INTO informes_usuarios (usuario, informe, idioma, enviado, fecha_envio, fecha_alta, activo) VALUES";
						$sqlInforme.= "('".$usuario."' ,'56','es','1','".date('d/m/Y H:i:s')."','".date('d/m/Y H:i:s')."','1');";
						$sqlInforme.= "INSERT INTO informes_usuarios (usuario, informe, idioma, enviado, fecha_envio, fecha_alta, activo) VALUES";
						$sqlInforme.= "('".$usuario."' ,'57','es','1','".date('d/m/Y H:i:s')."','".date('d/m/Y H:i:s')."','1');";

						$i=0;
						$iPagina=1;
						$iPregunta=1;
						$iRespuesta ="1";
						$sPregunta = "";
						$sqlRespuestas="";

						while($i < $sLCadena){
							if($i%2==0){
								$sqlRespuestas="INSERT INTO prisma_resp (usuario, pagina, pregunta, mejor, peor, tiempo) VALUES ";
								$sqlRespuestas.="('".$usuario."' , ".$iPagina.",".$iPregunta.",'" .$sCadena[$i] . "', ";
							}else{
								$sqlRespuestas.= "'".$sCadena[$i] ."',0);";
//								if($connMssql->Execute($sqlRespuestas) === false){
//									echo(constant("ERR"));
//									exit;
//								}
//								if(($sLCadena-$i)>1){
//									$sqlRespuestas.="INSERT INTO prisma_resp (usuario, pagina, pregunta, mejor, peor, tiempo) VALUES ";
//								}
							}

							//echo $usuario . " " . $sCadena[$i] . " Pregunta: " . $iPregunta . " Pagina: " . $iPagina ."<br />";

							if(($i+1)%2==0){
								$iPregunta++;
								$iPagina++;
							}
							$i++;
						}
						$iBien++;
						//Para hacer un recuento de las respuestas de la lista.
//						$sqlRecuento= "SELECT * FROM prisma_resp where usuario='".$usuario."'";
//						$rs= $connMssql->Execute($sqlRecuento);
//						echo $iBien . " - ". $usuario . ": ". $rs->recordCount() . "<br />";

						//Para borrar los usuarios y las respuestas de la lista.
//						$sqlBorradoRespuestas= "DELETE FROM prisma_resp where usuario='".$usuario."';";
//						if($connMssql->Execute($sqlBorradoRespuestas) === false){
//							echo(constant("ERR"));
//							exit;
//						}
//						$sqlBorradoUsuarios= "DELETE FROM usuarios where pass='".$usuario."';";
//						if($connMssql->Execute($sqlBorradoUsuarios) === false){
//							echo(constant("ERR"));
//							exit;
//						}
						//Borramos los informes de cada usuario
//						$sqlBorradoInformes= "DELETE FROM informes_usuarios where usuario='".$usuario."';";
//						if($connMssql->Execute($sqlBorradoInformes) === false){
//							echo(constant("ERR"));
//							exit;
//						}

					}
					$listaPrismas->MoveNext();
				}
			}
//			echo $sqlInforme;
//

//			if($connMssql->Execute($sqlUsuarios) === false){
//				echo(constant("ERR"));
//				exit;
//			}
//			if($connMssql->Execute($sqlInforme) === false){
//				echo(constant("ERR"));
//				exit;
//			}
//



			$sMalContestadas = substr($sMalContestadas, 1 , strlen($sMalContestadas));
			$sBienContestadas = substr($sBienContestadas, 1 , strlen($sBienContestadas));
			$sUsuariosBienContestadas = substr($sUsuariosBienContestadas, 1 , strlen($sUsuariosBienContestadas));

			include('Template/Prisma_papel/mntprisma_papell.php');
			break;
		default:
			$cEntidad = new Prisma_papel();
			$cEntidad->setOrderBy("carga");
			$cEntidad->setOrder("ASC");
			$sqlPorCarga = $cEntidadDB->readListaPorCarga($cEntidad);
			$listaCargas = $conn->Execute($sqlPorCarga);
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Prisma_papel/mntprisma_papel.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdPrisma((isset($_POST["fIdPrisma"])) ? $_POST["fIdPrisma"] : "");
		$cEntidad->setUsuario((isset($_POST["fUsuario"])) ? $_POST["fUsuario"] : "");
		$cEntidad->setCodigo((isset($_POST["fCodigo"])) ? $_POST["fCodigo"] : "");
		$cEntidad->setFacultad((isset($_POST["fFacultad"])) ? $_POST["fFacultad"] : "");
		$cEntidad->setSexo((isset($_POST["fSexo"])) ? $_POST["fSexo"] : "");
		$cEntidad->setPrisma((isset($_POST["fPrisma"])) ? $_POST["fPrisma"] : "");
		$cEntidad->setOrden((isset($_POST["fOrden"])) ? $_POST["fOrden"] : "");
		$cEntidad->setCarga((isset($_POST["fCarga"])) ? $_POST["fCarga"] : "");
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
		$cEntidad->setIdPrisma((isset($_POST["LSTIdPrisma"]) && $_POST["LSTIdPrisma"] != "") ? $_POST["LSTIdPrisma"] : "");	$cEntidad->setBusqueda(constant("STR_ID_PRISMA"), (isset($_POST["LSTIdPrisma"]) && $_POST["LSTIdPrisma"] != "" ) ? $_POST["LSTIdPrisma"] : "");
		$cEntidad->setIdPrismaHast((isset($_POST["LSTIdPrismaHast"]) && $_POST["LSTIdPrismaHast"] != "") ? $_POST["LSTIdPrismaHast"] : "");	$cEntidad->setBusqueda(constant("STR_ID_PRISMA") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdPrismaHast"]) && $_POST["LSTIdPrismaHast"] != "" ) ? $_POST["LSTIdPrismaHast"] : "");
		$cEntidad->setUsuario((isset($_POST["LSTUsuario"]) && $_POST["LSTUsuario"] != "") ? $_POST["LSTUsuario"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO_DE_ARIO"), (isset($_POST["LSTUsuario"]) && $_POST["LSTUsuario"] != "" ) ? $_POST["LSTUsuario"] : "");
		$cEntidad->setCodigo((isset($_POST["LSTCodigo"]) && $_POST["LSTCodigo"] != "") ? $_POST["LSTCodigo"] : "");	$cEntidad->setBusqueda(constant("STR_CODIGO"), (isset($_POST["LSTCodigo"]) && $_POST["LSTCodigo"] != "" ) ? $_POST["LSTCodigo"] : "");
		$cEntidad->setFacultad((isset($_POST["LSTFacultad"]) && $_POST["LSTFacultad"] != "") ? $_POST["LSTFacultad"] : "");	$cEntidad->setBusqueda(constant("STR_FACULTAD"), (isset($_POST["LSTFacultad"]) && $_POST["LSTFacultad"] != "" ) ? $_POST["LSTFacultad"] : "");
		$cEntidad->setSexo((isset($_POST["LSTSexo"]) && $_POST["LSTSexo"] != "") ? $_POST["LSTSexo"] : "");	$cEntidad->setBusqueda(constant("STR_SEXO"), (isset($_POST["LSTSexo"]) && $_POST["LSTSexo"] != "" ) ? $_POST["LSTSexo"] : "");
		$cEntidad->setPrisma((isset($_POST["LSTPrisma"]) && $_POST["LSTPrisma"] != "") ? $_POST["LSTPrisma"] : "");	$cEntidad->setBusqueda(constant("STR_PRISMA"), (isset($_POST["LSTPrisma"]) && $_POST["LSTPrisma"] != "" ) ? $_POST["LSTPrisma"] : "");
		$cEntidad->setOrden((isset($_POST["LSTOrden"]) && $_POST["LSTOrden"] != "") ? $_POST["LSTOrden"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN"), (isset($_POST["LSTOrden"]) && $_POST["LSTOrden"] != "" ) ? $_POST["LSTOrden"] : "");
		$cEntidad->setOrdenHast((isset($_POST["LSTOrdenHast"]) && $_POST["LSTOrdenHast"] != "") ? $_POST["LSTOrdenHast"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN") . " " . constant("STR_HASTA"), (isset($_POST["LSTOrdenHast"]) && $_POST["LSTOrdenHast"] != "" ) ? $_POST["LSTOrdenHast"] : "");
		$cEntidad->setCarga((isset($_POST["LSTCarga"]) && $_POST["LSTCarga"] != "") ? $_POST["LSTCarga"] : "");	$cEntidad->setBusqueda(constant("STR_CARGA"), (isset($_POST["LSTCarga"]) && $_POST["LSTCarga"] != "" ) ? $_POST["LSTCarga"] : "");
		$cEntidad->setCargaHast((isset($_POST["LSTCargaHast"]) && $_POST["LSTCargaHast"] != "") ? $_POST["LSTCargaHast"] : "");	$cEntidad->setBusqueda(constant("STR_CARGA") . " " . constant("STR_HASTA"), (isset($_POST["LSTCargaHast"]) && $_POST["LSTCargaHast"] != "" ) ? $_POST["LSTCargaHast"] : "");
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
