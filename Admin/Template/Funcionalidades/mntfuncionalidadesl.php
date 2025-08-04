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
		<meta name="generator" content="WIZARD, Wi2.22 www.negociainternet.com" />
		
<title><?php echo constant("NOMBRE_SITE");?></title>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />

	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . 'msg_error_JS.php');?>
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
function setPK(idFuncionalidad)
{
	var f=document.forms[0];
	f.fIdFuncionalidad.value=idFuncionalidad;
}
function confBorrar(Modo,sMsg)
{
	if (confirm(sMsg))
		enviar(Modo);
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
	if (eval("document.getElementById('TituloSup').innerHTML") != null){
		document.getElementById('TituloSup').innerHTML=titulo;
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
<body onload="_body_onload();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32"  border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
<?php ob_start();?>
	<table cellspacing="1" cellpadding="2" border="0" width="100%">
		<tr>
			<td width="10"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<?php 
			$strOrderASC = "&nbsp;<img src='" . constant("DIR_WS_GRAF") . "asc_order.gif' width='7' height='7' border='0' alt='' />&nbsp;";
			$strOrderDESC = "&nbsp;<img src='" . constant("DIR_WS_GRAF") . "desc_order.gif' width='7' height='7' border='0' alt='' />&nbsp;";
			?>
			<?php  
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "idFuncionalidad"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_ID"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='idFuncionalidad';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_ID_FUNCIONALIDAD"));?>';return true"><?php echo constant("STR_ID");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "nombre"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_NOMBRE"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='nombre';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_NOMBRE"));?>';return true"><?php echo constant("STR_NOMBRE");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "indentacion"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_INDENTACION"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='indentacion';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_INDENTACION"));?>';return true"><?php echo constant("STR_INDENTACION");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "url"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_URL"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='url';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_URL"));?>';return true"><?php echo constant("STR_URL");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "popUp"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_POPUP"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='popUp';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_POPUP"));?>';return true"><?php echo constant("STR_POPUP");?><?php echo $sPinta;?></a></td>
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
			if ($cEntidad->getOrderBy() == "publico"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_PUBLICO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='publico';document.forms[0].LSTPublico.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_PUBLICO"));?>';return true"><?php echo constant("STR_PUBLICO");?><?php echo $sPinta;?></a></td>
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
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_FEC_ALTA"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='fecAlta';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_FEC_ALTA"));?>';return true"><?php echo constant("STR_FEC_ALTA");?><?php echo $sPinta;?></a></td>
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
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_FEC_MOD"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='fecMod';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_FEC_MOD"));?>';return true"><?php echo constant("STR_FEC_MOD");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center"><img src="<?php echo constant("DIR_WS_GRAF");?>menos.gif" width="9" height="9" border="0" alt="<?php echo constant("STR_BORRAR");?>" /></td>
		</tr>
		<?php	$i=0;
		while (!$lista->EOF)
		{
		?>
		<tr  onmouseover="this.bgColor='<?php echo constant("ONMOUSEOVER");?>'" onmouseout="this.bgColor='<?php echo constant("ONMOUSEOUT");?>'" bgcolor="<?php echo constant("ONMOUSEOUT");?>">
			<td bgcolor="<?php echo constant("BG_COLOR");?>"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo $lista->fields['idFuncionalidad'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['idFuncionalidad']));?>"><?php echo substr(str_replace("\n","<br />",strip_tags(html_entity_decode($lista->fields['idFuncionalidad']), "<b><i><u><strong><br><br />")),0,11);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo $lista->fields['idFuncionalidad'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['nombre']));?>"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="<?php echo $lista->fields['indentacion']*14;?>" height="1" border="0" alt="" /><?php echo substr(str_replace("\n","<br />",strip_tags(html_entity_decode($lista->fields['nombre']), "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo $lista->fields['idFuncionalidad'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['indentacion']));?>"><?php echo substr(str_replace("\n","<br />",strip_tags(html_entity_decode($lista->fields['indentacion']), "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo $lista->fields['idFuncionalidad'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['url']));?>"><?php echo substr(str_replace("\n","<br />",strip_tags(html_entity_decode($lista->fields['url']), "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo $lista->fields['idFuncionalidad'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['popUp']));?>"><?php echo substr(str_replace("\n","<br />",strip_tags(html_entity_decode($lista->fields['popUp']), "<b><i><u><strong><br><br />")),0,2);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo $lista->fields['idFuncionalidad'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['orden']));?>"><?php echo substr(str_replace("\n","<br />",strip_tags(html_entity_decode($lista->fields['orden']), "<b><i><u><strong><br><br />")),0,11);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo $lista->fields['idFuncionalidad'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['publico']));?>"><?php echo substr(str_replace("\n","<br />",strip_tags(html_entity_decode($lista->fields['publico']), "<b><i><u><strong><br><br />")),0,2);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo $lista->fields['idFuncionalidad'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['fecAlta']));?>"><script language="javascript" type="text/javascript">document.write(formatThisDate('<?php echo $lista->fields['fecAlta'];?>'));</script></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo $lista->fields['idFuncionalidad'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['fecMod']));?>"><script language="javascript" type="text/javascript">document.write(formatThisDate('<?php echo $lista->fields['fecMod'];?>'));</script></td>
			<td class="tddatoslista" align="center" valign="middle"><a href="javascript:setPK('<?php echo $lista->fields['idFuncionalidad'];?>');confBorrar(<?php echo constant("MNT_BORRAR");?>,'<?php echo constant("DEL_GENERICO");?>');"><?php echo ($_bBorrar) ? "<img src='" . constant("DIR_WS_GRAF") . "menos.gif' width='9' height='9' border=\"0\" alt=\"" . constant("STR_BORRAR") . "\" />" : "";?></a></td>
		</tr>
		<?php $i++;
		$lista->MoveNext();
		} ?>
	</table>
	<?php $s = ob_get_contents();
	ob_end_clean();
	$pager->Render($s);?>
<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar(document.forms[0].MODO.value);">
<?php
$HELP="xx";
$aBuscador= $cEntidad->getBusqueda();?>
<div id="contenedor">
	<?php include_once(constant("DIR_WS_INCLUDE") . 'cabecera.php');?>
	<div id="envoltura">
		<div id="contenido">
			
			<div style="width: 100%">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td nowrap="nowrap" align="left" class="naranjab" valign="top"><?php echo constant("STR_PARAMETROS_DE_BUSQUEDA");?></td>
			<td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td width="100%">
				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
					<?php 	$iTotal = sizeof($aBuscador);
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
								$sCol1.=$aBuscador[$x][0] . ":&nbsp;<font class='negrob'>" .  $aBuscador[$x][1] . "</font><br /><br />";
							}else{
								$sCol2.=$aBuscador[$x][0] . ":&nbsp;<font class='negrob'>" .  $aBuscador[$x][1] . "</font><br /><br />";
							}
						}
					}
					?>
						<td nowrap="nowrap" class="azul" valign="top" width="35%"><?php echo $sCol1;?></td>
						<td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="10" height="1" border="0" alt="" /></td>
						<td nowrap="nowrap" class="azul" valign="top" width="35%"><?php echo $sCol2;?></td>
						<td width="30%"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="1" border="0" alt="" /></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td colspan="4"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="10" height="1" border="0" alt="" /></td></tr>
		<tr><td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td colspan="3" bgcolor="#FF8F19"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="1" border="0" alt="" /></td>
		</tr>
		<tr><td colspan="4"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width='10'><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td width="100%" colspan="2" align='left' class="naranjab"><?php echo sprintf(constant("STR_LISTA_DE_"),str_replace('_', ' ', 'funcionalidades'));?></td>
		</tr>
		<tr>
			<td width='10'><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td nowrap="nowrap" width='15%' class="azul" align='left'>(<font class="naranja"><?php echo $pager->getFooter();?></font>)</td>
			<td width='85%' class="azul" align='left'><?php echo $pager->getHeader();?></td>
		</tr>
	</table>
	<br />
	<?php echo $pager->getGrid();?>
	<br />
	<table cellspacing="0" width="100%" cellpadding="0" border="0">
		<tr>
			<td width='10'><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td nowrap="nowrap" width='15%' class="azul" align='left'>(<font class="naranja"><?php echo $pager->getFooter();?></font>)</td>
			<td width='85%' class="azul" align='left'><?php echo $pager->getHeader();?></td>
		</tr>
	</table>
	<br />
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td class="negro"><input type="submit" class="botones" id="bid-buscador" name="btnAdd" value="<?php echo constant("STR_BUSCADOR");?>" onclick="document.forms[0].MODO.value=<?php echo constant("MNT_BUSCAR");?>" /></td>
			<td class="negro"><input type="submit" <?php echo ($_bModificar) ? "" : "disabled";?> class="botones" id="bid-alta" name="btnAdd" value="<?php echo constant("STR_ALTA");?>" onclick="document.forms[0].MODO.value=<?php echo constant("MNT_NUEVO");?>" /></td>
		</tr>
	</table>
	<br />
	<input type="hidden" name="fIdFuncionalidad" value="" />
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_LISTAR");?>" />
	<input type="hidden" name="fReordenar" value="" />
	<input type="hidden" name="LSTIdFuncionalidad" value="<?php echo (isset($_POST['LSTIdFuncionalidad'])) ? $_POST['LSTIdFuncionalidad'] : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $_POST['LSTNombre'] : "";?>" />
	<input type="hidden" name="LSTDescripcion" value="<?php echo (isset($_POST['LSTDescripcion'])) ? $_POST['LSTDescripcion'] : "";?>" />
	<input type="hidden" name="LSTIdPadre" value="<?php echo (isset($_POST['LSTIdPadre'])) ? $_POST['LSTIdPadre'] : "";?>" />
	<input type="hidden" name="LSTUrl" value="<?php echo (isset($_POST['LSTUrl'])) ? $_POST['LSTUrl'] : "";?>" />
	<input type="hidden" name="LSTPopUp" value="<?php echo (isset($_POST['LSTPopUp'])) ? $_POST['LSTPopUp'] : "";?>" />
	<input type="hidden" name="LSTOrden" value="<?php echo (isset($_POST['LSTOrden'])) ? $_POST['LSTOrden'] : "";?>" />
	<input type="hidden" name="LSTIndentacion" value="<?php echo (isset($_POST['LSTIndentacion'])) ? $_POST['LSTIndentacion'] : "";?>" />
	<input type="hidden" name="LSTBgFile" value="<?php echo (isset($_POST['LSTBgFile'])) ? $_POST['LSTBgFile'] : "";?>" />
	<input type="hidden" name="LSTBgColor" value="<?php echo (isset($_POST['LSTBgColor'])) ? $_POST['LSTBgColor'] : "";?>" />
	<input type="hidden" name="LSTModoDefecto" value="<?php echo (isset($_POST['LSTModoDefecto'])) ? $_POST['LSTModoDefecto'] : "";?>" />
	<input type="hidden" name="LSTPublico" value="<?php echo (isset($_POST['LSTPublico'])) ? $_POST['LSTPublico'] : "";?>" />
	<input type="hidden" name="LSTFecAlta" value="<?php echo (isset($_POST['LSTFecAlta'])) ? $_POST['LSTFecAlta'] : "";?>" />
	<input type="hidden" name="LSTFecMod" value="<?php echo (isset($_POST['LSTFecMod'])) ? $_POST['LSTFecMod'] : "";?>" />
	<input type="hidden" name="LSTUsuAlta" value="<?php echo (isset($_POST['LSTUsuAlta'])) ? $_POST['LSTUsuAlta'] : "";?>" />
	<input type="hidden" name="LSTUsuMod" value="<?php echo (isset($_POST['LSTUsuMod'])) ? $_POST['LSTUsuMod'] : "";?>" />
	<input type="hidden" name="LSTOrderBy" value="<?php echo (isset($_POST['LSTOrderBy'])) ? $_POST['LSTOrderBy'] : "";?>" />
	<input type="hidden" name="LSTOrder" value="<?php echo (isset($_POST['LSTOrder'])) ? $_POST['LSTOrder'] : "";?>" />
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $_POST['LSTLineasPagina'] : constant("CNF_LINEAS_PAGINA");?>" />
	<input type="hidden" name="funcionalidades_next_page" value="<?php echo (isset($_POST['funcionalidades_next_page'])) ? $cUtilidades->validaXSS($_POST['funcionalidades_next_page']) : "1";?>" />
</div>
		    
		</div>
	</div>
     <?php include_once(constant("DIR_WS_INCLUDE") . 'menus.php');?>
    <?php //include_once(constant("DIR_WS_INCLUDE") . 'derecha.php');?>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
</form>
</body></html>