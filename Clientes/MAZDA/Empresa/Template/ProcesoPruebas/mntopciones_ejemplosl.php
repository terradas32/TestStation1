<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }

	$clave = obtenerClavePlantilla();
	$payload = [
		'sql'   => $sql,
		'nombre'=> $clave,
		'ts'    => time(),
		'nonce' => bin2hex(random_bytes(8)),
	];

	$token = excel_encrypt_payload($payload, EXCEL_ENC_KEY);
	$sig   = excel_sign($token, EXCEL_HMAC_KEY);
	$urlExcel = 'sqlToExcel.php?fSQLtoEXCEL='.base64_encode($token).'&signature='.base64_encode($sig);
?>
<?php
	$cPrueba = new Pruebas();
	$cPrueba->setIdPrueba($cOpcionesEjemplos->getIdPrueba());
	$cPrueba->setCodIdiomaIso2($cOpcionesEjemplos->getCodIdiomaIso2());
	$cPrueba = $cEntidadDB->readEntidad($cPrueba);

	$cIdiomas2->setCodIso2($cPrueba->getCodIdiomaIso2());
	$cIdiomas2 = $cIdiomas2DB->readEntidad($cIdiomas2);
	
	$cEjemplo1 = new Ejemplos();
	$cEjemplo1->setIdPrueba($cPrueba->getIdPrueba());
	$cEjemplo1->setCodIdiomaIso2($cPrueba->getCodIdiomaIso2());
	$cEjemplo1->setIdEjemplo($cOpcionesEjemplos->getIdEjemplo());
	$cEjemplo1 = $cEjemplosDB->readEntidad($cEjemplo1);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
<title><?php echo constant("NOMBRE_SITE");?></title>
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php");?>
function enviar(Modo)
{
	var f=document.forms[0];
	f.fOpcionEjemplo.value=1;
	if (validaForm()){
		lon();
		f.MODO.value = Modo;
		f.submit();
	}else	return false;
}
function validaForm()
{
	return true;
}
function setPK(idOpcion,idPrueba,codIdiomaIso2,idEjemplo)
{
	var f=document.forms[0];
	f.fIdOpcion.value=idOpcion;
	f.fIdPrueba.value=idPrueba;
	f.fCodIdiomaIso2.value=codIdiomaIso2;
	f.fIdEjemplo.value=idEjemplo;
}
function confBorrar(Modo,sMsg)
{
	if (confirm(sMsg))
		enviar(Modo);
}
function abrirVentana(bImg, file){
	preurl = "view.php?bImg=" + bImg + "&File=" + file;
	prename = "File";
	var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
	miv.focus();
}
function enviarMenu(pagina, modo)
{
	var f=document.forms[0];
	if (pagina != 'null'){
		lon();
		f.MODO.value = modo;
		f.action = pagina;
		f.submit();
	}
}
function setTitulo(titulo)
{
	if (eval("document.getElementById('cabecera-menu-seleccionado').innerHTML") != null){
		document.getElementById('cabecera-menu-seleccionado').innerHTML=titulo;
		document.forms[0]._TituloOpcion.value=titulo;
	}
}
function block(idBlock){
	for (i=0;i<200; i++){
		if (eval("document.getElementById('block" + i + "')") != null){
			eval("document.getElementById('block" + i + "').style.display = 'none'");
		}
	}
	if (eval("document.getElementById('block" + idBlock + "')") != null){
		eval("document.getElementById('block" + idBlock + "').style.display = 'block'");
	}
	document.forms[0]._block.value=idBlock;
}
onclick="if (document.getElementById('Mas').style.display == 'none'){document.getElementById('Mas').style.display='block';}else{document.getElementById('Mas').style.display='none';}"
//]]>
</script>
<script language="javascript" type="text/javascript">
//<![CDATA[
function _body_onload(){	loff();	}
function _body_onunload(){	lon();	}
//]]>
</script>
</head>
<body onload="_body_onload();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');"  onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
<?php ob_start();?>
	<table cellspacing="1" cellpadding="2" border="0" width="100%">
		<tr>
			<td width="10"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<?php 
			$strOrderASC = "&nbsp;<img src='" . constant('DIR_WS_GRAF') . "asc_order.gif' width='7' height='7' border='0' alt='' />&nbsp;";
			$strOrderDESC = "&nbsp;<img src='" . constant('DIR_WS_GRAF') . "desc_order.gif' width='7' height='7' border='0' alt='' />&nbsp;";
			?>
			<?php  
			$sPinta = "";
			$sOrden = "ASC";
			if ($cOpcionesEjemplos->getOrderBy() == "idOpcion"){
				if ($cOpcionesEjemplos->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_ID_OPCION"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderOpEjBy.value='idOpcion';document.forms[0].LSTOrderOpEj.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_ID_OPCION"));?>';return true"><?php echo constant("STR_ID_OPCION");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cOpcionesEjemplos->getOrderBy() == "codigo"){
				if ($cOpcionesEjemplos->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_CODIGO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderOpEjBy.value='codigo';document.forms[0].LSTOrderOpEj.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_CODIGO"));?>';return true"><?php echo constant("STR_CODIGO");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cOpcionesEjemplos->getOrderBy() == "descripcion"){
				if ($cOpcionesEjemplos->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_DESCRIPCION"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderOpEjBy.value='descripcion';document.forms[0].LSTOrderOpEj.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_DESCRIPCION"));?>';return true"><?php echo constant("STR_DESCRIPCION");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cOpcionesEjemplos->getOrderBy() == "bajaLog"){
				if ($cOpcionesEjemplos->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_BAJA_LOG"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderOpEjBy.value='bajaLog';document.forms[0].LSTOrderOpEj.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_BAJA_LOG"));?>';return true"><?php echo constant("STR_BAJA_LOG");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center"><img src="<?php echo constant('DIR_WS_GRAF');?>menos.gif" width="9" height="9" border="0" alt="<?php echo constant("STR_BORRAR");?>" /></td>
		</tr>
		<?php	$i=0;
		while (!$lista->EOF)
		{
		?>
		<tr onmouseover="this.bgColor='<?php echo constant("ONMOUSEOVER");?>'" onmouseout="this.bgColor='<?php echo constant("ONMOUSEOUT");?>'" bgcolor="<?php echo constant("ONMOUSEOUT");?>">
			<td bgcolor="<?php echo constant("BG_COLOR");?>"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idOpcion']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idEjemplo']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idOpcion']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['idOpcion'], "<b><i><u><strong><br /><br />")),0,11);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idOpcion']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idEjemplo']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['codigo']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['codigo'], "<b><i><u><strong><br /><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idOpcion']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idEjemplo']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descripcion']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descripcion'], "<b><i><u><strong><br /><br />")),0,255);?></td>
			<td class="tddatoslista" align="center" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEjemplo']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['bajaLog']);?>"><?php echo (empty($lista->fields['bajaLog'])) ? "<img src='" . constant("DIR_WS_GRAF") . "boloverde.gif' width='9' height='9' border='0' alt='" . constant("STR_ACTIVO") . "'>" : "<img src='" . constant("DIR_WS_GRAF") . "bolorojo.gif' width='9' height='9' border='0' alt='" . constant("STR_NO_ACTIVO") . "'>";?></td>
			<td class="negro" align="center" valign="middle"><?php if($_bBorrar) {?><a href="#" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idOpcion']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idEjemplo']);?>');confBorrar(<?php echo constant("MNT_BORRAR");?>,'<?php echo constant("DEL_GENERICO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>menos.gif" width="9" height="9" border="0" alt="<?php echo constant("STR_BORRAR");?>" /></a><?php } ?></td>
		</tr>
		<?php $i++;
		$lista->MoveNext();
		} ?>
	</table>
	<?php $s = ob_get_contents();
	ob_end_clean();
	$pager->Render($s);?>

<form name="form" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar(document.forms[0].MODO.value);">
<?php $HELP="xx";
$aBuscador= $cOpcionesEjemplos->getBusqueda();
?>
<div id="contenedor">
<?php include (constant("DIR_WS_INCLUDE") . "cabecera.php");?>
	<div id="envoltura">
		<div id="contenido">
		<div style="width: 100%">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td nowrap="nowrap" align="left" class="naranjab" valign="top"></td>
			<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td width="100%">
				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td nowrap="nowrap" class="azul" valign="top" width="35%"><b>Prueba:</b> <?php echo $cPrueba->getNombre();?></td>
						<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
						<td nowrap="nowrap" class="azul" valign="top" width="35%"></td>
						<td width="30%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
					</tr>
					<tr>
						<td colspan ="4" width="100%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="15" border="0" alt="" /></td>
					</tr>
					<tr>
						<td nowrap="nowrap" class="azul" valign="top" width="35%"><b>Idioma:</b> <?php echo $cIdiomas2->getNombre();?></td>
						<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
						<td nowrap="nowrap" class="azul" valign="top" width="35%"></td>
						<td width="30%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
					</tr>
					<tr>
						<td colspan ="4" width="100%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="15" border="0" alt="" /></td>
					</tr>
					<tr>
						<td nowrap="nowrap" class="azul" valign="top" width="35%"><b>Id del Ejemplo:</b> <?php echo $cEjemplo1->getIdEjemplo();?></td>
						<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
						<td nowrap="nowrap" class="azul" valign="top" width="35%"><b>Respuesta correcta:</b> <?php echo $cEjemplo1->getCorrecto();?></td>
						<td width="30%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
					</tr>
					<?php if($cEjemplo1->getEnunciado() !=""){?>
						<tr>
							<td colspan ="4" width="100%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="15" border="0" alt="" /></td>
						</tr>
						
						<tr>
							<td colspan="4" nowrap="nowrap" class="azul" valign="top" width="100%"><b>Enunciado:</b> <?php echo $cEjemplo1->getEnunciado();?></td>
						</tr>
					<?php 	
					}
					if($cEjemplo1->getDescripcion() !=""){?>
					
						<tr>
							<td colspan ="4" width="100%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="15" border="0" alt="" /></td>
						</tr>
						<tr>
							<td colspan ="4" nowrap="nowrap" class="azul" valign="top" width="100%"><b>Descripción:</b> <?php echo $cEjemplo1->getDescripcion();?></td>
						</tr>
					<?php 
					}?>
				</table>
			</td>
		</tr>
		<tr><td colspan="4"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td></tr>
		<tr><td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td colspan="3" bgcolor="#036"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
		</tr>
		<tr><td colspan="4"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="10"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td width="100%" colspan="2" class="naranjab"><?php echo sprintf(constant("STR_LISTA_DE_"),str_replace('_', ' ', constant("STR_OPCIONESEJEMPLOS")));?>
			<a href="<?php echo($urlExcel); ?>"><img src="<?php echo constant('DIR_WS_GRAF');?>excel.gif" width="34" height="35" align="right" border="0" alt="<?php echo constant("STR_EXPORTAR_A_EXCEL");?>" /></a></td>
		</tr>
		<tr>
			<td width="10"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td nowrap="nowrap" width="15%" class="azul" align="left">(<font class="naranja"><?php echo $pager->getFooter();?></font>)</td>
			<td width="85%" class="azul" align="left"><?php echo $pager->getHeader();?></td>
		</tr>
	</table>
	<br />
	<?php echo $pager->getGrid();?>
	<br />
	<table cellspacing="0" width="100%" cellpadding="0" border="0">
		<tr>
			<td width="10"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td nowrap="nowrap" width="15%" class="azul" align="left">(<font class="naranja"><?php echo $pager->getFooter();?></font>)</td>
			<td width="85%" class="azul" align="left"><?php echo $pager->getHeader();?></td>
		</tr>
	</table>
	<br />
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="document.forms[0].MODO.value=<?php echo constant("MNT_CONSULTAR");?>;document.forms[0].fEjemplo.value=1;document.forms[0].submit();" /></td>
			<td><input type="submit" class="botones" name="btnAdd" value="Lista Ejemplos" onclick="document.forms[0].MODO.value=<?php echo constant("MNT_LISTAR");?>;document.forms[0].fEjemplo.value=1" /></td>
			<td><input type="submit" class="botones" id="bid-buscador" name="btnAdd" value="<?php echo constant("STR_BUSCADOR");?>" onclick="document.forms[0].MODO.value=<?php echo constant("MNT_BUSCAR");?>" /></td>
			<td><input type="submit" class="botones" id="bid-alta" name="btnAdd" value="<?php echo constant("STR_ALTA");?>" onclick="document.forms[0].MODO.value=<?php echo constant("MNT_NUEVO");?>;document.forms[0].fOpcionEjemplo.value=1" /></td>
		</tr>
	</table>
	</div>
</div>
	<br />
	<input type="hidden" name="fOpcion" value="" />
	<input type="hidden" name="fOpcionEjemplo" value="" />
	<input type="hidden" name="fEjemplo" value="" />
	<input type="hidden" name="fIdOpcion" value="" />
	<input type="hidden" name="fIdEjemplo" value="<?php echo $cOpcionesEjemplos->getIdEjemplo()?>" />
	<input type="hidden" name="fIdPrueba" value="<?php echo $cOpcionesEjemplos->getIdPrueba()?>" />
	<input type="hidden" name="fCodIdiomaIso2" value="<?php echo $cOpcionesEjemplos->getCodIdiomaIso2()?>" />
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_LISTAR");?>" />
	<input type="hidden" name="fReordenar" value="" />
	<input type="hidden" name="LSTIdOpcionHast" value="<?php echo (isset($_POST['LSTIdOpcionHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdOpcionHast']) : "";?>" />
	<input type="hidden" name="LSTIdOpcion" value="<?php echo (isset($_POST['LSTIdOpcion'])) ? $cUtilidades->validaXSS($_POST['LSTIdOpcion']) : "";?>" />
	<input type="hidden" name="LSTIdPruebaHast" value="<?php echo (isset($_POST['LSTIdPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPruebaHast']) : "";?>" />
	<input type="hidden" name="LSTIdPrueba" value="<?php echo (isset($_POST['LSTIdPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTIdPrueba']) : "";?>" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo (isset($_POST['LSTCodIdiomaIso2'])) ? $cUtilidades->validaXSS($_POST['LSTCodIdiomaIso2']) : "";?>" />
	<input type="hidden" name="LSTIdEjemploHast" value="<?php echo (isset($_POST['LSTIdEjemploHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEjemploHast']) : "";?>" />
	<input type="hidden" name="LSTIdEjemplo" value="<?php echo (isset($_POST['LSTIdEjemplo'])) ? $cUtilidades->validaXSS($_POST['LSTIdEjemplo']) : "";?>" />
	<input type="hidden" name="LSTDescripcion" value="<?php echo (isset($_POST['LSTDescripcion'])) ? $cUtilidades->validaXSS($_POST['LSTDescripcion']) : "";?>" />
	<input type="hidden" name="LSTCodigo" value="<?php echo (isset($_POST['LSTCodigo'])) ? $cUtilidades->validaXSS($_POST['LSTCodigo']) : "";?>" />
	<input type="hidden" name="LSTBajaLogHast" value="<?php echo (isset($_POST['LSTBajaLogHast'])) ? $cUtilidades->validaXSS($_POST['LSTBajaLogHast']) : "";?>" />
	<input type="hidden" name="LSTBajaLog" value="<?php echo (isset($_POST['LSTBajaLog'])) ? $cUtilidades->validaXSS($_POST['LSTBajaLog']) : "";?>" />
	<input type="hidden" name="LSTFecAltaHast" value="<?php echo (isset($_POST['LSTFecAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecAltaHast']) : "";?>" />
	<input type="hidden" name="LSTFecAlta" value="<?php echo (isset($_POST['LSTFecAlta'])) ? $cUtilidades->validaXSS($_POST['LSTFecAlta']) : "";?>" />
	<input type="hidden" name="LSTFecModHast" value="<?php echo (isset($_POST['LSTFecModHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecModHast']) : "";?>" />
	<input type="hidden" name="LSTFecMod" value="<?php echo (isset($_POST['LSTFecMod'])) ? $cUtilidades->validaXSS($_POST['LSTFecMod']) : "";?>" />
	<input type="hidden" name="LSTUsuAltaHast" value="<?php echo (isset($_POST['LSTUsuAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAltaHast']) : "";?>" />
	<input type="hidden" name="LSTUsuAlta" value="<?php echo (isset($_POST['LSTUsuAlta'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAlta']) : "";?>" />
	<input type="hidden" name="LSTUsuModHast" value="<?php echo (isset($_POST['LSTUsuModHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuModHast']) : "";?>" />
	<input type="hidden" name="LSTUsuMod" value="<?php echo (isset($_POST['LSTUsuMod'])) ? $cUtilidades->validaXSS($_POST['LSTUsuMod']) : "";?>" />
	<input type="hidden" name="LSTOrderOpEjBy" value="<?php echo (isset($_POST['LSTOrderOpEjBy'])) ? $cUtilidades->validaXSS($_POST['LSTOrderOpEjBy']) : "";?>" />
	<input type="hidden" name="LSTOrderOpEj" value="<?php echo (isset($_POST['LSTOrderOpEj'])) ? $cUtilidades->validaXSS($_POST['LSTOrderOpEj']) : "";?>" />
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $cUtilidades->validaXSS($_POST['LSTLineasPagina']) : constant("CNF_LINEAS_PAGINA");?>" />
	<input type="hidden" name="opcionesejemplos_next_page" value="<?php echo (isset($_POST['opcionesejemplos_next_page'])) ? $cUtilidades->validaXSS($_POST['opcionesejemplos_next_page']) : "1";?>" />
	</div>
<?php include (constant("DIR_WS_INCLUDE") . 'menus.php');?>
<?php //include (constant("DIR_WS_INCLUDE") . 'derecha.php');?>
<?php include (constant("DIR_WS_INCLUDE") . 'pie.php');?></div>
</form>

</body></html>