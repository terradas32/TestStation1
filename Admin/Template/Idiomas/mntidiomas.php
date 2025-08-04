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
		
<title>Negocia Internet</title>
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . 'msg_error_JS.php');?>
function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
		lon();
		f.LSTFecAlta.value=cFechaFormat(f.LSTFecAlta.value);
		f.LSTFecAltaHast.value=cFechaFormat(f.LSTFecAltaHast.value);
		f.LSTFecMod.value=cFechaFormat(f.LSTFecMod.value);
		f.LSTFecModHast.value=cFechaFormat(f.LSTFecModHast.value);
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vString("<?php echo constant("STR_COD_ISO2")?>:",f.LSTCodIso2.value,2, false);
	msg +=vString("<?php echo constant("STR_NOMBRE")?>:",f.LSTNombre.value,255, false);
	msg +=vString("<?php echo constant("STR_IMAGEN")?>:",f.LSTPathImagen.value,255, false);
	msg +=vNumber("<?php echo constant("STR_ORDEN")?>:", f.LSTOrden.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_ORDEN")?> <?php echo constant("STR_HASTA");?>:", f.LSTOrdenHast.value, 11, false);
	msg +=vDate("<?php echo constant("STR_FEC_ALTA")?>:",f.LSTFecAlta.value,10,false);
	msg +=vDate("<?php echo constant("STR_FEC_ALTA")?> <?php echo constant("STR_HASTA");?>:",f.LSTFecAltaHast.value,10,false);
	msg +=vDate("<?php echo constant("STR_FEC_MOD")?>:",f.LSTFecMod.value,10,false);
	msg +=vDate("<?php echo constant("STR_FEC_MOD")?> <?php echo constant("STR_HASTA");?>:",f.LSTFecModHast.value,10,false);
	if (msg != "") {
		alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
		return false;
	}else return true;
}
function abrirCalendario(page, titulo){
	var miC=window.open(page, titulo,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=149,Height=148');
	miC.focus();
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
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
		<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar('<?php echo $_POST['MODO'];?>');">
<?php 
$HELP="xx";

?>
<div id="contenedor">
	<?php include_once(constant("DIR_WS_INCLUDE") . 'cabecera.php');?>
	<div id="envoltura">
		<div id="contenido">
			
			<div style="width: 100%">
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tr><td colspan="3" align="center" class="naranjab"><?php echo constant("STR_BUSCADOR");?></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_COD_ISO2")?>&nbsp;</td>
					<td><input type="text" name="LSTCodIso2" value="<?php echo $cEntidad->getCodIso2();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_NOMBRE")?>&nbsp;</td>
					<td><input type="text" name="LSTNombre" value="<?php echo $cEntidad->getNombre();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_IMAGEN")?>&nbsp;</td>
					<td><input type="text" name="LSTPathImagen" value="<?php echo $cEntidad->getPathImagen();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_ORDEN")?>&nbsp;</td>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><input type="text" name="LSTOrden" value="<?php echo $cEntidad->getOrden();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<td><input type="text" name="LSTOrdenHast" value="<?php echo $cEntidad->getOrdenHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_ACTIVO_EN_EL_FRONT")?>&nbsp;</td>
					<td><input type="radio" name="LSTActivoFront" id="LSTActivoFront1" value="1"  <?php echo ($cEntidad->getActivoFront() != "" && strtoupper($cEntidad->getActivoFront()) == "1") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTActivoFront1">Sí</label>&nbsp;<input type="radio" name="LSTActivoFront" id="LSTActivoFront0" value="0"  <?php echo ($cEntidad->getActivoFront() != "" && strtoupper($cEntidad->getActivoFront()) == "0") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTActivoFront0">No</label>&nbsp;</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_ACTIVO_EN_EL_BACK")?>&nbsp;</td>
					<td><input type="radio" name="LSTActivoBack" id="LSTActivoBack1" value="1"  <?php echo ($cEntidad->getActivoBack() != "" && strtoupper($cEntidad->getActivoBack()) == "1") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTActivoBack1">Sí</label>&nbsp;<input type="radio" name="LSTActivoBack" id="LSTActivoBack0" value="0"  <?php echo ($cEntidad->getActivoBack() != "" && strtoupper($cEntidad->getActivoBack()) == "0") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTActivoBack0">No</label>&nbsp;</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_FEC_ALTA")?>&nbsp;</td>
					<?php if ($cEntidad->getFecAlta() != "" && $cEntidad->getFecAlta() != "0000-00-00" && $cEntidad->getFecAlta() != "0000-00-00 00:00:00"){
						$cEntidad->setFecAlta($conn->UserDate($cEntidad->getFecAlta(),constant("USR_FECHA"),false));
					}else{
						$cEntidad->setFecAlta("");
					}
					?>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecAlta','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant("DIR_WS_GRAF");?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" name="LSTFecAlta" value="<?php echo $cEntidad->getFecAlta();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<?php if ($cEntidad->getFecAltaHast() != "" && $cEntidad->getFecAltaHast() != "0000-00-00" && $cEntidad->getFecAltaHast() != "0000-00-00 00:00:00"){
								$cEntidad->setFecAltaHast($conn->UserDate($cEntidad->getFecAltaHast(),constant("USR_FECHA"),false));
								}else{
								$cEntidad->setFecAlta("");
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecAltaHast','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant("DIR_WS_GRAF");?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" name="LSTFecAltaHast" value="<?php echo $cEntidad->getFecAltaHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_FEC_MOD")?>&nbsp;</td>
					<?php if ($cEntidad->getFecMod() != "" && $cEntidad->getFecMod() != "0000-00-00" && $cEntidad->getFecMod() != "0000-00-00 00:00:00"){
						$cEntidad->setFecMod($conn->UserDate($cEntidad->getFecMod(),constant("USR_FECHA"),false));
					}else{
						$cEntidad->setFecMod("");
					}
					?>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecMod','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant("DIR_WS_GRAF");?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" name="LSTFecMod" value="<?php echo $cEntidad->getFecMod();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<?php if ($cEntidad->getFecModHast() != "" && $cEntidad->getFecModHast() != "0000-00-00" && $cEntidad->getFecModHast() != "0000-00-00 00:00:00"){
								$cEntidad->setFecModHast($conn->UserDate($cEntidad->getFecModHast(),constant("USR_FECHA"),false));
								}else{
								$cEntidad->setFecMod("");
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecModHast','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant("DIR_WS_GRAF");?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" name="LSTFecModHast" value="<?php echo $cEntidad->getFecModHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="grisb"><?php echo constant("STR_ORDENAR_POR");?>&nbsp;</td>
					<td>
						<select name="LSTOrderBy" size="1" class="cajatexto">
							<?php $sOrderBy = $cEntidad->getOrderBy();?>
							<option style='color:#000000;' value='' <?php echo (empty($sOrderBy)) ? "selected='selected'" : "";?>><?php echo constant("SLC_OPCION");?></option>
							<option style='color:#000000;' value='codIdiomaIso2' <?php echo (!empty($sOrderBy) && $sOrderBy == 'codIdiomaIso2') ? "selected='selected'" : "";?>><?php echo constant("STR_COD_ISO2")?></option>
							<option style='color:#000000;' value='nombre' <?php echo (!empty($sOrderBy) && $sOrderBy == 'nombre') ? "selected='selected'" : "";?>><?php echo constant("STR_NOMBRE")?></option>
							<option style='color:#000000;' value='pathImagen' <?php echo (!empty($sOrderBy) && $sOrderBy == 'pathImagen') ? "selected='selected'" : "";?>><?php echo constant("STR_IMAGEN")?></option>
							<option style='color:#000000;' value='orden' <?php echo (!empty($sOrderBy) && $sOrderBy == 'orden') ? "selected='selected'" : "";?>><?php echo constant("STR_ORDEN")?></option>
							<option style='color:#000000;' value='activoFront' <?php echo (!empty($sOrderBy) && $sOrderBy == 'activoFront') ? "selected='selected'" : "";?>><?php echo constant("STR_ACTIVO_EN_EL_FRONT")?></option>
							<option style='color:#000000;' value='activoBack' <?php echo (!empty($sOrderBy) && $sOrderBy == 'activoBack') ? "selected='selected'" : "";?>><?php echo constant("STR_ACTIVO_EN_EL_BACK")?></option>
							<option style='color:#000000;' value='fecAlta' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fecAlta') ? "selected='selected'" : "";?>><?php echo constant("STR_FEC_ALTA")?></option>
							<option style='color:#000000;' value='fecMod' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fecMod') ? "selected='selected'" : "";?>><?php echo constant("STR_FEC_MOD")?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="grisb"><?php echo constant("STR_ORDEN");?>&nbsp;</td>
					<td>
						<select name="LSTOrder" size="1" class="cajatexto">
							<?php $sOrder = $cEntidad->getOrder();?>
							<option style='color:#000000;' value='' <?php echo (empty($sOrder)) ? "selected='selected'" : "";?>><?php echo constant("SLC_OPCION");?></option>
							<option style='color:#000000;' value='ASC' <?php echo (!empty($sOrder) && $sOrder == 'ASC') ? "selected='selected'" : "";?>><?php echo constant("STR_ASCENDENTE");?></option>
							<option style='color:#000000;' value='DESC' <?php echo (!empty($sOrder) && $sOrder == 'DESC') ? "selected='selected'" : "";?>><?php echo constant("STR_DESCENDENTE");?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="grisb"><?php echo constant("STR_LINEAS_POR_PAGINA");?>&nbsp;</td>
					<td><input class="cajatexto" style="width:40;" type="text" name="LSTLineasPagina" value="<?php echo ($cEntidad->getLineasPagina() != "") ? $cEntidad->getLineasPagina() : constant("CNF_LINEAS_PAGINA");?>" />
					</td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3" ><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
			</table>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td><input type="submit" class="botones" id="bid-buscar" name="fBtnAdd" value="<?php echo constant("STR_BUSCAR");?>" /></td>
				</tr>
			</table>
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_BUSCAR");?>" />
	<input type="hidden" name="idiomas_next_page" value="1" />
</div>
		    
		</div>
	</div>
     <?php include_once(constant("DIR_WS_INCLUDE") . 'menus.php');?>
    <?php //include_once(constant("DIR_WS_INCLUDE") . 'derecha.php');?>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
</form>
</body></html>