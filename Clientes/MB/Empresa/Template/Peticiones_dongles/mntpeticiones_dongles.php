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
function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
		lon();
		aEstado = document.forms[0].LSTEstado;
		sId = "";
		for(i=0; i < aEstado.length; i++ ){
			if (aEstado[i].type == "radio" && aEstado[i].name == "LSTEstado"){
				if (aEstado[i].checked)
				{
					sId = aEstado[i].value;
				}
			}
		}
		f.LSTEstadoHast.value=sId;
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
	msg +=vNumber("<?php echo constant("STR_IDPETICION");?>:", f.LSTIdPeticion.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_IDPETICION");?> <?php echo constant("STR_HASTA");?>:", f.LSTIdPeticionHast.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_EMPRESA");?>:", f.LSTIdEmpresa.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_EMPRESA_RECEPTORA");?>:", f.LSTIdEmpresaReceptora.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_EMPRESA_RECEPTORA");?> <?php echo constant("STR_HASTA");?>:", f.LSTIdEmpresaReceptoraHast.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_NUMERO_DE_DONGLES");?>:", f.LSTNDongles.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_NUMERO_DE_DONGLES");?> <?php echo constant("STR_HASTA");?>:", f.LSTNDonglesHast.value, 11, false);
	aEstado = document.forms[0].LSTEstado;
	sId = "";
	for(i=0; i < aEstado.length; i++ ){
		if (aEstado[i].type == "radio" && aEstado[i].name == "LSTEstado"){
			if (aEstado[i].checked)
			{
				sId = aEstado[i].value;
			}
		}
	}
	msg +=vNumber("<?php echo constant("STR_ESTADO");?>:",sId,11,false);
	msg +=vDate("Fecha de Alta:",f.LSTFecAlta.value,10,false);
	msg +=vDate("Fecha de Alta <?php echo constant("STR_HASTA");?>:",f.LSTFecAltaHast.value,10,false);
	msg +=vDate("Fecha de Modificación:",f.LSTFecMod.value,10,false);
	msg +=vDate("Fecha de Modificación <?php echo constant("STR_HASTA");?>:",f.LSTFecModHast.value,10,false);
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
	
		<form name="form" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar('<?php echo $_POST['MODO'];?>');">
<?
$HELP="xx";
?>
<div id="contenedor">
<?php include (constant("DIR_WS_INCLUDE") . "cabecera.php");?>
	<div id="envoltura">
		<div id="contenido">
		<div style="width: 100%">
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tr><td colspan="3" align="center" class="naranjab"><?php echo constant("STR_BUSCADOR");?></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdPeticion"><?php echo constant("STR_IDPETICION");?></label>&nbsp;</td>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><input type="text" id="LSTIdPeticion" name="LSTIdPeticion" value="<?php echo $cEntidad->getIdPeticion();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<td><input type="text" id="LSTIdPeticionHast" name="LSTIdPeticionHast" value="<?php echo $cEntidad->getIdPeticionHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdEmpresa"><?php echo constant("STR_EMPRESA");?></label>&nbsp;</td>
					<td><?php $comboEMPRESAS->setNombre("LSTIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLComboMenu("1","cajatexto",$cEntidad->getIdEmpresa()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdEmpresaReceptora"><?php echo constant("STR_EMPRESA_RECEPTORA");?></label>&nbsp;</td>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><input type="text" id="LSTIdEmpresaReceptora" name="LSTIdEmpresaReceptora" value="<?php echo $cEntidad->getIdEmpresaReceptora();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<td><input type="text" id="LSTIdEmpresaReceptoraHast" name="LSTIdEmpresaReceptoraHast" value="<?php echo $cEntidad->getIdEmpresaReceptoraHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTNDongles"><?php echo constant("STR_NUMERO_DE_DONGLES");?></label>&nbsp;</td>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><input type="text" id="LSTNDongles" name="LSTNDongles" value="<?php echo $cEntidad->getNDongles();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<td><input type="text" id="LSTNDonglesHast" name="LSTNDonglesHast" value="<?php echo $cEntidad->getNDonglesHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTEstado"><?php echo constant("STR_ESTADO");?></label>&nbsp;</td>
					<td><input type="radio" name="LSTEstado" id="LSTEstado2" value="2"  <?php echo ($cEntidad->getEstado() != "" && strtoupper($cEntidad->getEstado()) == "2") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTEstado2"><?php echo constant("STR_RECHAZADA");?></label>&nbsp;<input type="radio" name="LSTEstado" id="LSTEstado1" value="1"  <?php echo ($cEntidad->getEstado() != "" && strtoupper($cEntidad->getEstado()) == "1") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTEstado1"><?php echo constant("STR_ACEPTADA");?></label>&nbsp;<input type="radio" name="LSTEstado" id="LSTEstado0" value="0"  <?php echo ($cEntidad->getEstado() != "" && strtoupper($cEntidad->getEstado()) == "0") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTEstado0"><?php echo constant("STR_PENDIENTE");?></label>&nbsp;<input type="hidden" name="LSTEstadoHast" value="<?php echo $cEntidad->getEstado();?>" /></td>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFecAlta"><?php echo constant("STR_FECHA_DE_ALTA");?></label>&nbsp;</td>
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
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecAlta','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecAlta" name="LSTFecAlta" value="<?php echo $cEntidad->getFecAlta();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<?php if ($cEntidad->getFecAltaHast() != "" && $cEntidad->getFecAltaHast() != "0000-00-00" && $cEntidad->getFecAltaHast() != "0000-00-00 00:00:00"){
								$cEntidad->setFecAltaHast($conn->UserDate($cEntidad->getFecAltaHast(),constant("USR_FECHA"),false));
								}else{
								$cEntidad->setFecAlta("");
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecAltaHast','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecAltaHast" name="LSTFecAltaHast" value="<?php echo $cEntidad->getFecAltaHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFecMod"><?php echo constant("STR_FECHA_DE_MODIFICACION");?></label>&nbsp;</td>
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
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecMod','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecMod" name="LSTFecMod" value="<?php echo $cEntidad->getFecMod();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<?php if ($cEntidad->getFecModHast() != "" && $cEntidad->getFecModHast() != "0000-00-00" && $cEntidad->getFecModHast() != "0000-00-00 00:00:00"){
								$cEntidad->setFecModHast($conn->UserDate($cEntidad->getFecModHast(),constant("USR_FECHA"),false));
								}else{
								$cEntidad->setFecMod("");
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecModHast','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecModHast" name="LSTFecModHast" value="<?php echo $cEntidad->getFecModHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="grisb"><?php echo constant("STR_ORDENAR_POR");?>&nbsp;</td>
					<td>
						<select id="LSTOrderBy" name="LSTOrderBy" size="1" class="cajatexto">
							<?php $sOrderBy = $cEntidad->getOrderBy();?>
							<option style='color:#000000;' value='' <?php echo (empty($sOrderBy)) ? "selected='selected'" : "";?>><?php echo constant("SLC_OPCION");?></option>
							<option style='color:#000000;' value='idPeticion' <?php echo (!empty($sOrderBy) && $sOrderBy == 'idPeticion') ? "selected='selected'" : "";?>><?php echo constant("STR_IDPETICION");?></option>
							<option style='color:#000000;' value='idEmpresaReceptora' <?php echo (!empty($sOrderBy) && $sOrderBy == 'idEmpresaReceptora') ? "selected='selected'" : "";?>><?php echo constant("STR_EMPRESA_RECEPTORA");?></option>
							<option style='color:#000000;' value='nDongles' <?php echo (!empty($sOrderBy) && $sOrderBy == 'nDongles') ? "selected='selected'" : "";?>><?php echo constant("STR_NUMERO_DE_DONGLES");?></option>
							<option style='color:#000000;' value='estado' <?php echo (!empty($sOrderBy) && $sOrderBy == 'estado') ? "selected='selected'" : "";?>><?php echo constant("STR_ESTADO");?></option>
							<option style='color:#000000;' value='fecAlta' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fecAlta') ? "selected='selected'" : "";?>><?php echo constant("STR_FECHA_DE_ALTA");?></option>
							<option style='color:#000000;' value='fecMod' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fecMod') ? "selected='selected'" : "";?>><?php echo constant("STR_FECHA_DE_MODIFICACION");?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="grisb"><?php echo constant("STR_ORDEN");?>&nbsp;</td>
					<td>
						<select id="LSTOrder" name="LSTOrder" size="1" class="cajatexto">
							<?php $sOrder = $cEntidad->getOrder();?>
							<option style='color:#000000;' value='' <?php echo (empty($sOrder)) ? "selected='selected'" : "";?>><?php echo constant("SLC_OPCION");?></option>
							<option style='color:#000000;' value='ASC' <?php echo (!empty($sOrder) && $sOrder == 'ASC') ? "selected='selected'" : "";?>><?php echo constant("STR_ASCENDENTE");?></option>
							<option style='color:#000000;' value='DESC' <?php echo (!empty($sOrder) && $sOrder == 'DESC') ? "selected='selected'" : "";?>><?php echo constant("STR_DESCENDENTE");?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="grisb"><?php echo constant("STR_LINEAS_POR_PAGINA");?>&nbsp;</td>
					<td><input class="cajatexto" style="width:40px;" type="text" id="LSTLineasPagina" name="LSTLineasPagina" value="<?php echo ($cEntidad->getLineasPagina() != "") ? $cEntidad->getLineasPagina() : constant("CNF_LINEAS_PAGINA");?>" />
					</td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3" ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
			</table>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td><input type="submit" class="botones" id="bid-buscar" name="fBtnAdd" value="<?php echo constant("STR_BUSCAR");?>" /></td>
				</tr>
			</table>
	</div>
</div>
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_BUSCAR");?>" />
	<input type="hidden" name="peticiones_dongles_next_page" value="1" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" /></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>