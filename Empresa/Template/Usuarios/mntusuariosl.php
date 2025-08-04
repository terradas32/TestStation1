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
	<link rel="stylesheet" href="estilos/jquery.alerts.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jquery.alert.js"></script>
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
function setPK(idUsuario)
{
	var f=document.forms[0];
	f.fIdUsuario.value=idUsuario;
}
function confBorrar(Modo,sMsg)
{
	jConfirm(sMsg, "<?php echo constant("STR_CONFIRMACION");?>", function(r) {  
		    if(r) {  
		enviar(Modo);
				    }  
		}); 
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
<body onload="_body_onload();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1"?>');setClicado('<?php echo $_POST["_clicado"];?>');"  onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
<?php ob_start();?>
	<table cellspacing="1" cellpadding="2" border="0" width="100%">
		<tr>
			<td width='10'><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width='10' height="1" border="0" alt="" /></td>
			<?php 
			$strOrderASC = "&nbsp;<img src='" . constant('DIR_WS_GRAF') . "asc_order.gif' width='7' height='7' border='0' alt='' />&nbsp;";
			$strOrderDESC = "&nbsp;<img src='" . constant('DIR_WS_GRAF') . "desc_order.gif' width='7' height='7' border='0' alt='' />&nbsp;";
			?>
			<?php  
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "idUsuario"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_ID"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='idUsuario';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_ID"));?>';return true"><?php echo constant("STR_ID");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "login"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_LOGIN"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='login';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_LOGIN"));?>';return true"><?php echo constant("STR_LOGIN");?><?php echo $sPinta;?></a></td>
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
			if ($cEntidad->getOrderBy() == "apellido1"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_PRIMER_APELLIDO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='apellido1';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_PRIMER_APELLIDO"));?>';return true"><?php echo constant("STR_PRIMER_APELLIDO");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "apellido2"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_SEGUNDO_APELLIDO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='apellido2';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_SEGUNDO_APELLIDO"));?>';return true"><?php echo constant("STR_SEGUNDO_APELLIDO");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "email"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_EMAIL"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='email';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_EMAIL"));?>';return true"><?php echo constant("STR_EMAIL");?><?php echo $sPinta;?></a></td>
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
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "fecBaja"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_FEC_BAJA"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='fecBaja';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_FEC_BAJA"));?>';return true"><?php echo constant("STR_FEC_BAJA");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "bajaLog"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_BAJA_LOG"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='bajaLog';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_BAJA_LOG"));?>';return true"><?php echo constant("STR_BAJA_LOG");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_TIPO_DE_USUARIO");?></td>
			<td class="tdnaranjab" align="center"><img src="<?php echo constant("DIR_WS_GRAF");?>menos.gif" width='9' height='9' alt="<?php echo constant("STR_BORRAR");?>" border="0" /></td>
		</tr>
		<?php	$i=0;
		while (!$lista->EOF)
		{
		?>
		<tr onmouseover="this.bgColor='<?php echo constant("ONMOUSEOVER");?>'" onmouseout="this.bgColor='<?php echo constant("ONMOUSEOUT");?>'" bgcolor="<?php echo constant("ONMOUSEOUT");?>">
			<td bgcolor="<?php echo constant("BG_COLOR");?>"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width='10' height="1" border="0" alt="" /></td>
			<td class="negro" valign="top" style="cursor:pointer;" onclick="javascript:setPK('<?php echo $lista->fields['idUsuario'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['idUsuario']));?>"><?php echo substr(str_replace("\n","<br />",strip_tags(html_entity_decode($lista->fields['idUsuario']), "<b><i><u><strong><br><br />")),0,11);?></td>
			<td class="negro" valign="top" style="cursor:pointer;" onclick="javascript:setPK('<?php echo $lista->fields['idUsuario'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['login']));?>"><?php echo substr(str_replace("\n","<br />",strip_tags(html_entity_decode($lista->fields['login']), "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="negro" valign="top" style="cursor:pointer;" onclick="javascript:setPK('<?php echo $lista->fields['idUsuario'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['nombre']));?>"><?php echo substr(str_replace("\n","<br />",strip_tags(html_entity_decode($lista->fields['nombre']), "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="negro" valign="top" style="cursor:pointer;" onclick="javascript:setPK('<?php echo $lista->fields['idUsuario'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['apellido1']));?>"><?php echo substr(str_replace("\n","<br />",strip_tags(html_entity_decode($lista->fields['apellido1']), "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="negro" valign="top" style="cursor:pointer;" onclick="javascript:setPK('<?php echo $lista->fields['idUsuario'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['apellido2']));?>"><?php echo substr(str_replace("\n","<br />",strip_tags(html_entity_decode($lista->fields['apellido2']), "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="negro" valign="top" style="cursor:pointer;" onclick="javascript:setPK('<?php echo $lista->fields['idUsuario'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['email']));?>"><?php echo substr(str_replace("\n","<br />",strip_tags(html_entity_decode($lista->fields['email']), "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="negro" valign="top" style="cursor:pointer;" onclick="javascript:setPK('<?php echo $lista->fields['idUsuario'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['fecAlta']));?>"><script language="javascript" type="text/javascript">document.write(formatThisDate('<?php echo $lista->fields['fecAlta'];?>'));</script></td>
			<td class="negro" valign="top" style="cursor:pointer;" onclick="javascript:setPK('<?php echo $lista->fields['idUsuario'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['fecMod']));?>"><script language="javascript" type="text/javascript">document.write(formatThisDate('<?php echo $lista->fields['fecMod'];?>'));</script></td>
			<td class="negro" valign="top" style="cursor:pointer;" onclick="javascript:setPK('<?php echo $lista->fields['idUsuario'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['fecBaja']));?>"><script language="javascript" type="text/javascript">document.write(formatThisDate('<?php echo $lista->fields['fecBaja'];?>'));</script></td>
			<td class="negro" align="center"><a href="#" onclick="javascript:setPK('<?php echo $lista->fields['idUsuario'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['bajaLog']));?>" class="negro"><?php echo (empty($lista->fields['bajaLog'])) ? "<img src=\"" . constant("DIR_WS_GRAF") . "boloverde.gif\" width='9' height='9' border='0' alt=\"" . constant("STR_ACTIVO") . "\" />" : "<img src=\"" . constant("DIR_WS_GRAF") . "bolorojo.gif\" width='9' height='9' border='0' alt=\"" . constant("STR_NO_ACTIVO") . "\" />";?></a></td>
			<td class="negro" valign="top" style="cursor:pointer;" onclick="javascript:setPK('<?php echo $lista->fields['idUsuario'];?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags(html_entity_decode($lista->fields['idUsuarioTipo']));?>"><?php echo substr($comboUSUARIOS_TIPOS->getDescripcionCombo($lista->fields['idUsuarioTipo']),0,11);?></td>
			<td class="negro" align="center"><?php if (!empty($lista->fields['idUsuario'])){;?><a href="javascript:setPK('<?php echo $lista->fields['idUsuario'];?>');confBorrar(<?php echo constant("MNT_BORRAR");?>,'<?php echo constant("DEL_GENERICO");?>');"><?php echo ($_bBorrar) ? "<img src='" . constant("DIR_WS_GRAF") . "menos.gif' width='9' height='9' border='0' alt=\"" . constant("STR_BORRAR") . "\">" : "";?></a><?php }else{echo("&nbsp;");};?></td>
		</tr>
		<?php $i++;
		$lista->MoveNext();
		} ?>
	</table>
	<?php $s = ob_get_contents();
	ob_end_clean();
	$pager->Render($s);?>
<!-- Inicio -->
<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar(document.forms[0].MODO.value);">
<?php
$HELP="xx";

?>
<div id="contenedor">
	<?php include_once(constant("DIR_WS_INCLUDE") . 'cabecera.php');?>
	<div id="envoltura">
		<div id="contenido">
			
			<div style="width: 100%">
<?php $aBuscador= $cEntidad->getBusqueda();?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width='10' height="1" border="0" alt="" /></td>
			<td nowrap="nowrap" align='left' class="naranjab" valign="top"><?php echo constant("STR_PARAMETROS_DE_BUSQUEDA");?></td>
			<td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width='10' height="1" border="0" alt="" /></td>
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
						<td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width='10' height="1" border="0" alt="" /></td>
						<td nowrap="nowrap" class="azul" valign="top" width="35%"><?php echo $sCol2;?></td>
						<td width="30%"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="1" border="0" alt="" /></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td colspan="4"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width='10' height="1" alt="" /></td></tr>
		<tr><td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width='10' height="1" alt="" /></td>
			<td colspan="3" bgcolor="#FF8F19"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="1" alt="" /></td>
		</tr>
		<tr><td colspan="4"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width='10'><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width='10' height="1" border="0" alt="" /></td>
			<td width="100%" colspan="2" align='left' class="naranjab"><?php echo sprintf(constant("STR_LISTA_DE_"),str_replace('_', ' ', 'usuarios'));?></td>
		</tr>
		<tr>
			<td width='10'><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width='10' height="1" border="0" alt="" /></td>
			<td nowrap="nowrap" width='15%' class="azul" align='left'>(<font class="naranja"><?php echo $pager->getFooter();?></font>)</td>
			<td width='85%' class="azul" align='left'><?php echo $pager->getHeader();?></td>
		</tr>
	</table>
	<br />
	<?php echo $pager->getGrid();?>
	<br />
	<table cellspacing="0" width="100%" cellpadding="0" border="0">
		<tr>
			<td width='10'><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width='10' height="1" border="0" alt="" /></td>
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
	<input type="hidden" name="fIdUsuario" value="" />
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_LISTAR");?>" />
	<input type="hidden" name="fReordenar" value="" />
	<input type="hidden" name="LSTIdUsuario" value="<?php echo (isset($_POST['LSTIdUsuario'])) ? $_POST['LSTIdUsuario'] : "";?>" />
	<input type="hidden" name="LSTLogin" value="<?php echo (isset($_POST['LSTLogin'])) ? $_POST['LSTLogin'] : "";?>" />
	<input type="hidden" name="LSTPassword" value="<?php echo (isset($_POST['LSTPassword'])) ? $_POST['LSTPassword'] : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $_POST['LSTNombre'] : "";?>" />
	<input type="hidden" name="LSTApellido1" value="<?php echo (isset($_POST['LSTApellido1'])) ? $_POST['LSTApellido1'] : "";?>" />
	<input type="hidden" name="LSTApellido2" value="<?php echo (isset($_POST['LSTApellido2'])) ? $_POST['LSTApellido2'] : "";?>" />
	<input type="hidden" name="LSTEmail" value="<?php echo (isset($_POST['LSTEmail'])) ? $_POST['LSTEmail'] : "";?>" />
	<input type="hidden" name="LSTLoginCorreo" value="<?php echo (isset($_POST['LSTLoginCorreo'])) ? $_POST['LSTLoginCorreo'] : "";?>" />
	<input type="hidden" name="LSTPasswordCorreo" value="<?php echo (isset($_POST['LSTPasswordCorreo'])) ? $_POST['LSTPasswordCorreo'] : "";?>" />
	<input type="hidden" name="LSTFecAltaHast" value="<?php echo (isset($_POST['LSTFecAltaHast'])) ? $_POST['LSTFecAltaHast'] : "";?>" />
	<input type="hidden" name="LSTFecAlta" value="<?php echo (isset($_POST['LSTFecAlta'])) ? $_POST['LSTFecAlta'] : "";?>" />
	<input type="hidden" name="LSTFecModHast" value="<?php echo (isset($_POST['LSTFecModHast'])) ? $_POST['LSTFecModHast'] : "";?>" />
	<input type="hidden" name="LSTFecMod" value="<?php echo (isset($_POST['LSTFecMod'])) ? $_POST['LSTFecMod'] : "";?>" />
	<input type="hidden" name="LSTUsuAlta" value="<?php echo (isset($_POST['LSTUsuAlta'])) ? $_POST['LSTUsuAlta'] : "";?>" />
	<input type="hidden" name="LSTUsuMod" value="<?php echo (isset($_POST['LSTUsuMod'])) ? $_POST['LSTUsuMod'] : "";?>" />
	<input type="hidden" name="LSTFecBajaHast" value="<?php echo (isset($_POST['LSTFecBajaHast'])) ? $_POST['LSTFecBajaHast'] : "";?>" />
	<input type="hidden" name="LSTFecBaja" value="<?php echo (isset($_POST['LSTFecBaja'])) ? $_POST['LSTFecBaja'] : "";?>" />
	<input type="hidden" name="LSTBajaLog" value="<?php echo (isset($_POST['LSTBajaLog'])) ? $_POST['LSTBajaLog'] : "";?>" />
	<input type="hidden" name="LSTUltimoLoginHast" value="<?php echo (isset($_POST['LSTUltimoLoginHast'])) ? $_POST['LSTUltimoLoginHast'] : "";?>" />
	<input type="hidden" name="LSTUltimoLogin" value="<?php echo (isset($_POST['LSTUltimoLogin'])) ? $_POST['LSTUltimoLogin'] : "";?>" />
	<input type="hidden" name="LSTIdUsuarioTipo" value="<?php echo (isset($_POST['LSTIdUsuarioTipo'])) ? $_POST['LSTIdUsuarioTipo'] : "";?>" />
	<input type="hidden" name="LSTOrderBy" value="<?php echo (isset($_POST['LSTOrderBy'])) ? $_POST['LSTOrderBy'] : "";?>" />
	<input type="hidden" name="LSTOrder" value="<?php echo (isset($_POST['LSTOrder'])) ? $_POST['LSTOrder'] : "";?>" />
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $_POST['LSTLineasPagina'] : constant("CNF_LINEAS_PAGINA");?>" />
	<input type="hidden" name="usuarios_next_page" value="<?php echo (isset($_POST['usuarios_next_page'])) ? $cUtilidades->validaXSS($_POST['usuarios_next_page']) : "1";?>" />
</div>
		    
		</div><!-- fin de contenido -->
	</div><!-- fin de envoltura -->
     <?php include_once(constant("DIR_WS_INCLUDE") . 'menus.php');?>
    <?php //include_once(constant("DIR_WS_INCLUDE") . 'derecha.php');?>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
</form>
</body></html>