<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, Wi2.22 www.azulpomodoro.com" />
		
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
function setPK(idEmpresa,idProceso,idCandidato,codIdiomaIso2,idPrueba,idItem)
{
	var f=document.forms[0];
	f.fIdEmpresa.value=idEmpresa;
	f.fIdProceso.value=idProceso;
	f.fIdCandidato.value=idCandidato;
	f.fCodIdiomaIso2.value=codIdiomaIso2;
	f.fIdPrueba.value=idPrueba;
	f.fIdItem.value=idItem;
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
onclick="javascript:if (document.getElementById('Mas').style.display == 'none'){document.getElementById('Mas').style.display='block';}else{document.getElementById('Mas').style.display='none';}"
//]]>
</script>
<script language="javascript" type="text/javascript">
//<![CDATA[
function _body_onload(){	loff();	}
function _body_onunload(){	lon();	}
//]]>
</script>
</head>
<body onload="_body_onload();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
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
			if ($cEntidad->getOrderBy() == "descEmpresa"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_EMPRESA"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descEmpresa';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_EMPRESA"));?>';return true"><?php echo constant("STR_EMPRESA");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descProceso"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_PROCESO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descProceso';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_PROCESO"));?>';return true"><?php echo constant("STR_PROCESO");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descCandidato"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_CANDIDATO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descCandidato';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_CANDIDATO"));?>';return true"><?php echo constant("STR_CANDIDATO");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descIdiomaIso2"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_IDIOMA"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descIdiomaIso2';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_IDIOMA"));?>';return true"><?php echo constant("STR_IDIOMA");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descPrueba"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_PRUEBA"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descPrueba';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_PRUEBA"));?>';return true"><?php echo constant("STR_PRUEBA");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descItem"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_ITEM"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descItem';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_ITEM"));?>';return true"><?php echo constant("STR_ITEM");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descOpcion"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_OPCION"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descOpcion';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_OPCION"));?>';return true"><?php echo constant("STR_OPCION");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "orden"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_ORDEN"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='orden';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_ORDEN"));?>';return true"><?php echo constant("STR_ORDEN");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "fecAlta"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_FECHA_DE_ALTA"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='fecAlta';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_FECHA_DE_ALTA"));?>';return true"><?php echo constant("STR_FECHA_DE_ALTA");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "fecMod"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_FECHA_DE_MODIFICACION"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='fecMod';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_FECHA_DE_MODIFICACION"));?>';return true"><?php echo constant("STR_FECHA_DE_MODIFICACION");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_USUARIO_DE_ALTA");?></td>
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_USUARIO_DE_MODIFICACION");?></td>
			<td class="tdnaranjab" align="center"><img src="<?php echo constant('DIR_WS_GRAF');?>menos.gif" width="9" height="9" border="0" alt="<?php echo constant("STR_BORRAR");?>" /></td>
		</tr>
		<?php	$i=0;
		while (!$lista->EOF)
		{
		?>
		<tr onmouseover="this.bgColor='<?php echo constant("ONMOUSEOVER");?>'" onmouseout="this.bgColor='<?php echo constant("ONMOUSEOUT");?>'" bgcolor="<?php echo constant("ONMOUSEOUT");?>">
			<td bgcolor="<?php echo constant("BG_COLOR");?>"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descEmpresa']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descEmpresa'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descProceso']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descProceso'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descCandidato']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descCandidato'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descIdiomaIso2']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descIdiomaIso2'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descPrueba']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descPrueba'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descItem']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descItem'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descOpcion']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descOpcion'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['orden']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['orden'], "<b><i><u><strong><br><br />")),0,11);?></td>
			<td class="tddatoslista" valign="top" align="center" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['fecAlta']);?>"><script language="javascript" type="text/javascript">document.write(formatThisDate('<?php echo $lista->fields['fecAlta'];?>'));</script></td>
			<td class="tddatoslista" valign="top" align="center" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['fecMod']);?>"><script language="javascript" type="text/javascript">document.write(formatThisDate('<?php echo $lista->fields['fecMod'];?>'));</script></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['usuAlta']);?>"><?php echo $comboWI_USUARIOS->getDescripcionCombo($lista->fields['usuAlta']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['usuMod']);?>"><?php echo $comboWI_USUARIOS->getDescripcionCombo($lista->fields['usuMod']);?></td>
			<td class="negro" align="center" valign="middle"><?php if($_bBorrar) {?><a href="#" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');confBorrar(<?php echo constant("MNT_BORRAR");?>,'<?php echo constant("DEL_GENERICO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>menos.gif" width="9" height="9" border="0" alt="<?php echo constant("STR_BORRAR");?>" /></a><?php } ?></td>
		</tr>
		<?php $i++;
		$lista->MoveNext();
		} ?>
	</table>
	<?php $s = ob_get_contents();
	ob_end_clean();
	$pager->Render($s);?>

<form name="form" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"  onsubmit="return enviar(document.forms[0].MODO.value);">
<?php $HELP="xx";
$aBuscador= $cEntidad->getBusqueda();
?>
<div id="contenedor">
<?php include (constant("DIR_WS_INCLUDE") . "cabecera.php");?>
	<div id="envoltura">
		<div id="contenido">
		<div style="width: 100%">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td nowrap="nowrap" align="left" class="naranjab" valign="top"><?php echo constant("STR_PARAMETROS_DE_BUSQUEDA");?></td>
			<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td width="100%">
				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
					<?php $iTotal = sizeof($aBuscador);
					if ($iTotal > 0 ) {
						//Calculo los ficheros por columna.
						$col1=$iTotal/2;
						$col2=$col1;
						$resto=$iTotal%2;
						switch ($resto){
							case 1:
								$col1=(substr($col1,0,strpos($col1, '.')) + 1);
								break;
							case 2:
								$col1=(substr($col2,0,strpos($col2, '.')) + 1);
								$col2=(substr($col2,0,strpos($col2, '.')) + 1);
								break;
						}
						$sCol1="";
						$sCol2="";
						for ($x=0; $x < $iTotal; $x++) {
							if ($x < $col1){
								$sCol1.=$cUtilidades->validaXSS($aBuscador[$x][0]) . ":&nbsp;<font class='negrob'>" .  $cUtilidades->validaXSS($aBuscador[$x][1]) . "</font><br /><br />";
							}else{
								$sCol2.=$cUtilidades->validaXSS($aBuscador[$x][0]) . ":&nbsp;<font class='negrob'>" .  $cUtilidades->validaXSS($aBuscador[$x][1]) . "</font><br /><br />";
							}
						}
					}
					?>
						<td nowrap="nowrap" class="azul" valign="top" width="35%"><?php echo $sCol1;?></td>
						<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
						<td nowrap="nowrap" class="azul" valign="top" width="35%"><?php echo $sCol2;?></td>
						<td width="30%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
					</tr>
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
			<td width="100%" colspan="2" class="naranjab"><?php echo sprintf(constant("STR_LISTA_DE_"),str_replace('_', ' ', constant("STR_RESPUESTASPRUEBASITEMS")));?><a href="#_" onclick="javascript:document.forms[0].action='sqlToExcelAciertoError.php';document.forms[0].submit();document.forms[0].action='<?php echo $_SERVER['PHP_SELF'];?>';"><img src="<?php echo constant('DIR_WS_GRAF');?>excel.gif" width="34" height="35" align="right" border="0" alt="<?php echo constant("STR_EXPORTAR_A_EXCEL");?>" /></a></td>
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
			<td><input type="submit" class="botones" id="bid-buscador" name="btnAdd" value="<?php echo constant("STR_BUSCADOR");?>" onclick="javascript:document.forms[0].MODO.value=<?php echo constant("MNT_BUSCAR");?>" /></td>
			<td><input type="submit" class="botones" id="bid-alta" name="btnAdd" value="<?php echo constant("STR_ALTA");?>" onclick="javascript:document.forms[0].MODO.value=<?php echo constant("MNT_NUEVO");?>" /></td>
		</tr>
	</table>
	</div>
</div>
	<br />
	<input type="hidden" name="fIdEmpresa" value="" />
	<input type="hidden" name="fIdProceso" value="" />
	<input type="hidden" name="fIdCandidato" value="" />
	<input type="hidden" name="fCodIdiomaIso2" value="" />
	<input type="hidden" name="fIdPrueba" value="" />
	<input type="hidden" name="fIdItem" value="" />
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_LISTAR");?>" />
	<input type="hidden" name="fReordenar" value="" />
	<input type="hidden" name="fSQLtoEXCEL" value="<?php echo base64_encode($sql . constant("CHAR_SEPARA") . "respuestas_pruebas_items");?>" />
	<input type="hidden" name="LSTIdEmpresaHast" value="<?php echo (isset($_POST['LSTIdEmpresaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaHast']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresa" value="<?php echo (isset($_POST['LSTIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresa']) : "";?>" />
	<input type="hidden" name="LSTDescEmpresa" value="<?php echo (isset($_POST['LSTDescEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTDescEmpresa']) : "";?>" />
	<input type="hidden" name="LSTIdProcesoHast" value="<?php echo (isset($_POST['LSTIdProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdProcesoHast']) : "";?>" />
	<input type="hidden" name="LSTIdProceso" value="<?php echo (isset($_POST['LSTIdProceso'])) ? $cUtilidades->validaXSS($_POST['LSTIdProceso']) : "";?>" />
	<input type="hidden" name="LSTDescProceso" value="<?php echo (isset($_POST['LSTDescProceso'])) ? $cUtilidades->validaXSS($_POST['LSTDescProceso']) : "";?>" />
	<input type="hidden" name="LSTIdCandidatoHast" value="<?php echo (isset($_POST['LSTIdCandidatoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidatoHast']) : "";?>" />
	<input type="hidden" name="LSTIdCandidato" value="<?php echo (isset($_POST['LSTIdCandidato'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidato']) : "";?>" />
	<input type="hidden" name="LSTDescCandidato" value="<?php echo (isset($_POST['LSTDescCandidato'])) ? $cUtilidades->validaXSS($_POST['LSTDescCandidato']) : "";?>" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo (isset($_POST['LSTCodIdiomaIso2'])) ? $cUtilidades->validaXSS($_POST['LSTCodIdiomaIso2']) : "";?>" />
	<input type="hidden" name="LSTDescIdiomaIso2" value="<?php echo (isset($_POST['LSTDescIdiomaIso2'])) ? $cUtilidades->validaXSS($_POST['LSTDescIdiomaIso2']) : "";?>" />
	<input type="hidden" name="LSTIdPruebaHast" value="<?php echo (isset($_POST['LSTIdPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPruebaHast']) : "";?>" />
	<input type="hidden" name="LSTIdPrueba" value="<?php echo (isset($_POST['LSTIdPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTIdPrueba']) : "";?>" />
	<input type="hidden" name="LSTDescPrueba" value="<?php echo (isset($_POST['LSTDescPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTDescPrueba']) : "";?>" />
	<input type="hidden" name="LSTIdItemHast" value="<?php echo (isset($_POST['LSTIdItemHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdItemHast']) : "";?>" />
	<input type="hidden" name="LSTIdItem" value="<?php echo (isset($_POST['LSTIdItem'])) ? $cUtilidades->validaXSS($_POST['LSTIdItem']) : "";?>" />
	<input type="hidden" name="LSTDescItem" value="<?php echo (isset($_POST['LSTDescItem'])) ? $cUtilidades->validaXSS($_POST['LSTDescItem']) : "";?>" />
	<input type="hidden" name="LSTIdOpcionHast" value="<?php echo (isset($_POST['LSTIdOpcionHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdOpcionHast']) : "";?>" />
	<input type="hidden" name="LSTIdOpcion" value="<?php echo (isset($_POST['LSTIdOpcion'])) ? $cUtilidades->validaXSS($_POST['LSTIdOpcion']) : "";?>" />
	<input type="hidden" name="LSTDescOpcion" value="<?php echo (isset($_POST['LSTDescOpcion'])) ? $cUtilidades->validaXSS($_POST['LSTDescOpcion']) : "";?>" />
	<input type="hidden" name="LSTOrdenHast" value="<?php echo (isset($_POST['LSTOrdenHast'])) ? $cUtilidades->validaXSS($_POST['LSTOrdenHast']) : "";?>" />
	<input type="hidden" name="LSTOrden" value="<?php echo (isset($_POST['LSTOrden'])) ? $cUtilidades->validaXSS($_POST['LSTOrden']) : "";?>" />
	<input type="hidden" name="LSTFecAltaHast" value="<?php echo (isset($_POST['LSTFecAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecAltaHast']) : "";?>" />
	<input type="hidden" name="LSTFecAlta" value="<?php echo (isset($_POST['LSTFecAlta'])) ? $cUtilidades->validaXSS($_POST['LSTFecAlta']) : "";?>" />
	<input type="hidden" name="LSTFecModHast" value="<?php echo (isset($_POST['LSTFecModHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecModHast']) : "";?>" />
	<input type="hidden" name="LSTFecMod" value="<?php echo (isset($_POST['LSTFecMod'])) ? $cUtilidades->validaXSS($_POST['LSTFecMod']) : "";?>" />
	<input type="hidden" name="LSTUsuAltaHast" value="<?php echo (isset($_POST['LSTUsuAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAltaHast']) : "";?>" />
	<input type="hidden" name="LSTUsuAlta" value="<?php echo (isset($_POST['LSTUsuAlta'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAlta']) : "";?>" />
	<input type="hidden" name="LSTUsuModHast" value="<?php echo (isset($_POST['LSTUsuModHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuModHast']) : "";?>" />
	<input type="hidden" name="LSTUsuMod" value="<?php echo (isset($_POST['LSTUsuMod'])) ? $cUtilidades->validaXSS($_POST['LSTUsuMod']) : "";?>" />
	<input type="hidden" name="LSTOrderBy" value="<?php echo (isset($_POST['LSTOrderBy'])) ? $cUtilidades->validaXSS($_POST['LSTOrderBy']) : "";?>" />
	<input type="hidden" name="LSTOrder" value="<?php echo (isset($_POST['LSTOrder'])) ? $cUtilidades->validaXSS($_POST['LSTOrder']) : "";?>" />
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $cUtilidades->validaXSS($_POST['LSTLineasPagina']) : constant("CNF_LINEAS_PAGINA");?>" />
	<input type="hidden" name="respuestas_pruebas_items_next_page" value="<?php echo (isset($_POST['respuestas_pruebas_items_next_page'])) ? $cUtilidades->validaXSS($_POST['respuestas_pruebas_items_next_page']) : "1";?>" />
</div>
<?php include (constant("DIR_WS_INCLUDE") . 'menus.php');?>
<?php //include (constant("DIR_WS_INCLUDE") . 'derecha.php');?>
<?php include (constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
</form>
</body></html>