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
function setPK(id)
{
	var f=document.forms[0];
	f.fId.value=id;
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
			if ($cEntidad->getOrderBy() == "id"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_ID"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='id';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_ID"));?>';return true"><?php echo constant("STR_ID");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "CODIGO"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_CODIGO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='CODIGO';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_CODIGO"));?>';return true"><?php echo constant("STR_CODIGO");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "EDAD"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_EDAD"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='EDAD';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_EDAD"));?>';return true"><?php echo constant("STR_EDAD");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "SEXO"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_SEXO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='SEXO';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_SEXO"));?>';return true"><?php echo constant("STR_SEXO");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "NOMBRE"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_NOMBRE"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='NOMBRE';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_NOMBRE"));?>';return true"><?php echo constant("STR_NOMBRE");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "APELLIDO1"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_APELLIDO_1"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='APELLIDO1';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_APELLIDO_1"));?>';return true"><?php echo constant("STR_APELLIDO_1");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "APELLIDO2"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_APELLIDO_2"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='APELLIDO2';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_APELLIDO_2"));?>';return true"><?php echo constant("STR_APELLIDO_2");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "RESULTADO"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_RESULTADO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='RESULTADO';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_RESULTADO"));?>';return true"><?php echo constant("STR_RESULTADO");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "ORDEN"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_ORDEN"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='ORDEN';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_ORDEN"));?>';return true"><?php echo constant("STR_ORDEN");?><?php echo $sPinta;?></a></td>
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
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['id']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['id'], "<b><i><u><strong><br><br />")),0,11);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['CODIGO']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['CODIGO'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['EDAD']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['EDAD'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['SEXO']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['SEXO'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['NOMBRE']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['NOMBRE'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['APELLIDO1']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['APELLIDO1'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['APELLIDO2']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['APELLIDO2'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['RESULTADO']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['RESULTADO'], "<b><i><u><strong><br><br />")),0,2500);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['ORDEN']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['ORDEN'], "<b><i><u><strong><br><br />")),0,11);?></td>
			<td class="tddatoslista" valign="top" align="center" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['fecAlta']);?>"><script language="javascript" type="text/javascript">document.write(formatThisDate('<?php echo $lista->fields['fecAlta'];?>'));</script></td>
			<td class="tddatoslista" valign="top" align="center" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['fecMod']);?>"><script language="javascript" type="text/javascript">document.write(formatThisDate('<?php echo $lista->fields['fecMod'];?>'));</script></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['usuAlta']);?>"><?php echo $comboWI_USUARIOS->getDescripcionCombo($lista->fields['usuAlta']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['usuMod']);?>"><?php echo $comboWI_USUARIOS->getDescripcionCombo($lista->fields['usuMod']);?></td>
			<td class="negro" align="center" valign="middle"><?php if($_bBorrar) {?><a href="#" onclick="javascript:setPK('<?php echo addslashes($lista->fields['id']);?>');confBorrar(<?php echo constant("MNT_BORRAR");?>,'<?php echo constant("DEL_GENERICO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>menos.gif" width="9" height="9" border="0" alt="<?php echo constant("STR_BORRAR");?>" /></a><?php } ?></td>
		</tr>
		<?php $i++;
		$lista->MoveNext();
		} ?>
	</table>
	<?php $s = ob_get_contents();
	ob_end_clean();
	$pager->Render($s);?>

<form name="form" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
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
			<td colspan="3" bgcolor="#FF8F19"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
		</tr>
		<tr><td colspan="4"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="10"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td width="100%" colspan="2" class="naranjab"><?php echo sprintf(constant("STR_LISTA_DE_"),str_replace('_', ' ', constant("STR_PRUEBASPAPEL")));?><a href="#_" onclick="javascript:document.forms[0].action='sqlToExcel.php';document.forms[0].submit();document.forms[0].action='<?php echo $_SERVER['PHP_SELF'];?>';"><img src="<?php echo constant('DIR_WS_GRAF');?>excel.gif" width="34" height="35" align="right" border="0" alt="<?php echo constant("STR_EXPORTAR_A_EXCEL");?>" /></a></td>
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
	<input type="hidden" name="fId" value="" />
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_LISTAR");?>" />
	<input type="hidden" name="fReordenar" value="" />
	<input type="hidden" name="fSQLtoEXCEL" value="<?php echo base64_encode($sql . constant("CHAR_SEPARA") . "pruebas_papel");?>" />
	<input type="hidden" name="LSTIdHast" value="<?php echo (isset($_POST['LSTIdHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdHast']) : "";?>" />
	<input type="hidden" name="LSTId" value="<?php echo (isset($_POST['LSTId'])) ? $cUtilidades->validaXSS($_POST['LSTId']) : "";?>" />
	<input type="hidden" name="LSTCODIGO" value="<?php echo (isset($_POST['LSTCODIGO'])) ? $cUtilidades->validaXSS($_POST['LSTCODIGO']) : "";?>" />
	<input type="hidden" name="LSTEDAD" value="<?php echo (isset($_POST['LSTEDAD'])) ? $cUtilidades->validaXSS($_POST['LSTEDAD']) : "";?>" />
	<input type="hidden" name="LSTSEXO" value="<?php echo (isset($_POST['LSTSEXO'])) ? $cUtilidades->validaXSS($_POST['LSTSEXO']) : "";?>" />
	<input type="hidden" name="LSTNOMBRE" value="<?php echo (isset($_POST['LSTNOMBRE'])) ? $cUtilidades->validaXSS($_POST['LSTNOMBRE']) : "";?>" />
	<input type="hidden" name="LSTAPELLIDO1" value="<?php echo (isset($_POST['LSTAPELLIDO1'])) ? $cUtilidades->validaXSS($_POST['LSTAPELLIDO1']) : "";?>" />
	<input type="hidden" name="LSTAPELLIDO2" value="<?php echo (isset($_POST['LSTAPELLIDO2'])) ? $cUtilidades->validaXSS($_POST['LSTAPELLIDO2']) : "";?>" />
	<input type="hidden" name="LSTRESULTADO" value="<?php echo (isset($_POST['LSTRESULTADO'])) ? $cUtilidades->validaXSS($_POST['LSTRESULTADO']) : "";?>" />
	<input type="hidden" name="LSTORDENHast" value="<?php echo (isset($_POST['LSTORDENHast'])) ? $cUtilidades->validaXSS($_POST['LSTORDENHast']) : "";?>" />
	<input type="hidden" name="LSTORDEN" value="<?php echo (isset($_POST['LSTORDEN'])) ? $cUtilidades->validaXSS($_POST['LSTORDEN']) : "";?>" />
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
	<input type="hidden" name="pruebas_papel_next_page" value="<?php echo (isset($_POST['pruebas_papel_next_page'])) ? $cUtilidades->validaXSS($_POST['pruebas_papel_next_page']) : "1";?>" />
</div>
<?php include (constant("DIR_WS_INCLUDE") . 'menus.php');?>
<?php //include (constant("DIR_WS_INCLUDE") . 'derecha.php');?>
<?php include (constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
</form>
</body></html>