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
function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
		lon();
		aInformado = document.forms[0].LSTInformado;
		sId = "";
		for(i=0; i < aInformado.length; i++ ){
			if (aInformado[i].type == "radio" && aInformado[i].name == "LSTInformado"){
				if (aInformado[i].checked)
				{
					sId = aInformado[i].value;
				}
			}
		}
		f.LSTInformadoHast.value=sId;
		aFinalizado = document.forms[0].LSTFinalizado;
		sId = "";
		for(i=0; i < aFinalizado.length; i++ ){
			if (aFinalizado[i].type == "radio" && aFinalizado[i].name == "LSTFinalizado"){
				if (aFinalizado[i].checked)
				{
					sId = aFinalizado[i].value;
				}
			}
		}
		f.LSTFinalizadoHast.value=sId;
		f.LSTFechaFinalizado.value=cFechaFormat(f.LSTFechaFinalizado.value);
		f.LSTFechaFinalizadoHast.value=cFechaFormat(f.LSTFechaFinalizadoHast.value);
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
	msg +=vNumber("<?php echo constant("STR_EMPRESA");?>:", f.LSTIdEmpresa.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_PROCESO");?>:", f.LSTIdProceso.value, 11, false);
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.LSTNombre.value,255, false);
	msg +=vString("<?php echo constant("STR_APELLIDO_1");?>:",f.LSTApellido1.value,255, false);
	msg +=vString("<?php echo constant("STR_APELLIDO_2");?>:",f.LSTApellido2.value,255, false);
if (!f.LSTDniOK.checked){
	msg +=vNif("<?php echo constant("STR_NIF");?>:",f.LSTDni.value,255, false);
}
	msg +=vEmail("<?php echo constant("STR_EMAIL");?>:",f.LSTMail.value,255, false);
	aInformado = document.forms[0].LSTInformado;
	sId = "";
	for(i=0; i < aInformado.length; i++ ){
		if (aInformado[i].type == "radio" && aInformado[i].name == "LSTInformado"){
			if (aInformado[i].checked)
			{
				sId = aInformado[i].value;
			}
		}
	}
	msg +=vNumber("<?php echo constant("STR_INFORMADO");?>:",sId,11,false);
	aFinalizado = document.forms[0].LSTFinalizado;
	sId = "";
	for(i=0; i < aFinalizado.length; i++ ){
		if (aFinalizado[i].type == "radio" && aFinalizado[i].name == "LSTFinalizado"){
			if (aFinalizado[i].checked)
			{
				sId = aFinalizado[i].value;
			}
		}
	}
	msg +=vNumber("<?php echo constant("STR_FINALIZADO");?>:",sId,11,false);
	msg +=vDate("Fecha de Finalizado:",f.LSTFechaFinalizado.value,10,false);
	msg +=vDate("Fecha de Finalizado <?php echo constant("STR_HASTA");?>:",f.LSTFechaFinalizadoHast.value,10,false);
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
<body onload="_body_onload();cambiaIdEmpresa();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
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
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdEmpresa"><?php echo constant("STR_EMPRESA");?></label>&nbsp;</td>
					<td><?php $comboEMPRESAS->setNombre("LSTIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLComboMenu("1","cajatexto",$cEntidad->getIdEmpresa()," onchange=\"javascript:cambiaIdEmpresa()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdProceso"><?php echo constant("STR_PROCESO");?></label>&nbsp;</td>
					<td><div id="comboIdProceso"><?php $comboPROCESOS->setNombre("LSTIdProceso");?><?php echo $comboPROCESOS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdProceso(),"onchange=\"javascript:cambiaIdProceso()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTNombre"><?php echo constant("STR_NOMBRE");?></label>&nbsp;</td>
					<td><input type="text" id="LSTNombre" name="LSTNombre" value="<?php echo $cEntidad->getNombre();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTApellido1"><?php echo constant("STR_APELLIDO_1");?></label>&nbsp;</td>
					<td><input type="text" id="LSTApellido1" name="LSTApellido1" value="<?php echo $cEntidad->getApellido1();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTApellido2"><?php echo constant("STR_APELLIDO_2");?></label>&nbsp;</td>
					<td><input type="text" id="LSTApellido2" name="LSTApellido2" value="<?php echo $cEntidad->getApellido2();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTDni"><?php echo constant("STR_NIF");?></label>&nbsp;</td>
					<td><input type="text" id="LSTDni" name="LSTDni" value="<?php echo $cEntidad->getDni();?>" class="cajatexto"  style='width:75%;' onchange="javascript:trim(this);" /><input type="checkbox" name="LSTDniOK" /><?php echo constant("STR_VERIFICADO");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTMail"><?php echo constant("STR_EMAIL");?></label>&nbsp;</td>
					<td><input type="text" id="LSTMail" name="LSTMail" value="<?php echo $cEntidad->getMail();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTInformado"><?php echo constant("STR_INFORMADO");?></label>&nbsp;</td>
					<td><input type="radio" name="LSTInformado" id="LSTInformado1" value="1"  <?php echo ($cEntidad->getInformado() != "" && strtoupper($cEntidad->getInformado()) == "1") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTInformado1">Sí</label>&nbsp;<input type="radio" name="LSTInformado" id="LSTInformado0" value="0"  <?php echo ($cEntidad->getInformado() != "" && strtoupper($cEntidad->getInformado()) == "0") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTInformado0">No</label>&nbsp;<input type="hidden" name="LSTInformadoHast" value="<?php echo $cEntidad->getInformado();?>" /></td>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFinalizado"><?php echo constant("STR_FINALIZADO");?></label>&nbsp;</td>
					<td><input type="radio" name="LSTFinalizado" id="LSTFinalizado1" value="1"  <?php echo ($cEntidad->getFinalizado() != "" && strtoupper($cEntidad->getFinalizado()) == "1") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTFinalizado1">Sí</label>&nbsp;<input type="radio" name="LSTFinalizado" id="LSTFinalizado0" value="0"  <?php echo ($cEntidad->getFinalizado() != "" && strtoupper($cEntidad->getFinalizado()) == "0") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTFinalizado0">No</label>&nbsp;<input type="hidden" name="LSTFinalizadoHast" value="<?php echo $cEntidad->getFinalizado();?>" /></td>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFechaFinalizado"><?php echo constant("STR_FECHA_DE_FINALIZADO");?></label>&nbsp;</td>
					<?php if ($cEntidad->getFechaFinalizado() != "" && $cEntidad->getFechaFinalizado() != "0000-00-00" && $cEntidad->getFechaFinalizado() != "0000-00-00 00:00:00"){
						$cEntidad->setFechaFinalizado($conn->UserDate($cEntidad->getFechaFinalizado(),constant("USR_FECHA"),false));
					}else{
						$cEntidad->setFechaFinalizado("");
					}
					?>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFechaFinalizado','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFechaFinalizado" name="LSTFechaFinalizado" value="<?php echo $cEntidad->getFechaFinalizado();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<?php if ($cEntidad->getFechaFinalizadoHast() != "" && $cEntidad->getFechaFinalizadoHast() != "0000-00-00" && $cEntidad->getFechaFinalizadoHast() != "0000-00-00 00:00:00"){
								$cEntidad->setFechaFinalizadoHast($conn->UserDate($cEntidad->getFechaFinalizadoHast(),constant("USR_FECHA"),false));
								}else{
								$cEntidad->setFechaFinalizado("");
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFechaFinalizadoHast','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFechaFinalizadoHast" name="LSTFechaFinalizadoHast" value="<?php echo $cEntidad->getFechaFinalizadoHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
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
							<option style='color:#000000;' value='idCandidato' <?php echo (!empty($sOrderBy) && $sOrderBy == 'idCandidato') ? "selected='selected'" : "";?>><?php echo constant("STR_ID_CANDIDATO");?></option>
							<option style='color:#000000;' value='nombre' <?php echo (!empty($sOrderBy) && $sOrderBy == 'nombre') ? "selected='selected'" : "";?>><?php echo constant("STR_NOMBRE");?></option>
							<option style='color:#000000;' value='apellido1' <?php echo (!empty($sOrderBy) && $sOrderBy == 'apellido1') ? "selected='selected'" : "";?>><?php echo constant("STR_APELLIDO_1");?></option>
							<option style='color:#000000;' value='apellido2' <?php echo (!empty($sOrderBy) && $sOrderBy == 'apellido2') ? "selected='selected'" : "";?>><?php echo constant("STR_APELLIDO_2");?></option>
							<option style='color:#000000;' value='dni' <?php echo (!empty($sOrderBy) && $sOrderBy == 'dni') ? "selected='selected'" : "";?>><?php echo constant("STR_NIF");?></option>
							<option style='color:#000000;' value='mail' <?php echo (!empty($sOrderBy) && $sOrderBy == 'mail') ? "selected='selected'" : "";?>><?php echo constant("STR_EMAIL");?></option>
							<option style='color:#000000;' value='fechaNacimiento' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fechaNacimiento') ? "selected='selected'" : "";?>><?php echo constant("STR_FECHA_DE_NACIMIENTO");?></option>
							<option style='color:#000000;' value='direccion' <?php echo (!empty($sOrderBy) && $sOrderBy == 'direccion') ? "selected='selected'" : "";?>><?php echo constant("STR_DIRECCION");?></option>
							<option style='color:#000000;' value='codPostal' <?php echo (!empty($sOrderBy) && $sOrderBy == 'codPostal') ? "selected='selected'" : "";?>><?php echo constant("STR_CODIGO_POSTAL");?></option>
							<option style='color:#000000;' value='telefono' <?php echo (!empty($sOrderBy) && $sOrderBy == 'telefono') ? "selected='selected'" : "";?>><?php echo constant("STR_TELEFONO");?></option>
							<option style='color:#000000;' value='estadoCivil' <?php echo (!empty($sOrderBy) && $sOrderBy == 'estadoCivil') ? "selected='selected'" : "";?>><?php echo constant("STR_ESTADO_CIVIL");?></option>
							<option style='color:#000000;' value='nacionalidad' <?php echo (!empty($sOrderBy) && $sOrderBy == 'nacionalidad') ? "selected='selected'" : "";?>><?php echo constant("STR_NACIONALIDAD");?></option>
							<option style='color:#000000;' value='informado' <?php echo (!empty($sOrderBy) && $sOrderBy == 'informado') ? "selected='selected'" : "";?>><?php echo constant("STR_INFORMADO");?></option>
							<option style='color:#000000;' value='finalizado' <?php echo (!empty($sOrderBy) && $sOrderBy == 'finalizado') ? "selected='selected'" : "";?>><?php echo constant("STR_FINALIZADO");?></option>
							<option style='color:#000000;' value='fechaFinalizado' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fechaFinalizado') ? "selected='selected'" : "";?>><?php echo constant("STR_FECHA_DE_FINALIZADO");?></option>
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
	<input type="hidden" name="candidatos_next_page" value="1" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" />
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdEmpresa(){
								var f= document.forms[0];
								$("#comboIdProceso").show().load("jQuery.php",{sPG:"comboprocesos",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdProceso",fJSProp:"cambiaIdProceso",LSTIdEmpresa:f.LSTIdEmpresa.value,vSelected:"<?php echo $cEntidad->getIdProceso();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdProceso(){								
							}
							//]]>
						</script>
	</div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>