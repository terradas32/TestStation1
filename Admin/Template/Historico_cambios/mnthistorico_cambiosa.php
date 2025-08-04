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
		f.fFecCambio.value=cFechaFormat(f.fFecCambio.value);
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vDate("<?php echo constant("STR_FECHA_DE_CAMBIO");?>:",f.fFecCambio.value,10,true);
	msg +=vString("<?php echo constant("STR_FUNCIONALIDAD");?>:",f.fFuncionalidad.value,255,true);
	msg +=vString("<?php echo constant("STR_MODO");?>:",f.fModo.value,255,true);
	msg +=vString("<?php echo constant("STR_QUERY");?>:",f.fQuery.value,4294967295,true);
	msg +=vString("<?php echo constant("STR_IP");?>:",f.fIp.value,255,true);
	msg +=vNumber("<?php echo constant("STR_USUARIO");?>:",f.fIdUsuario.value,11,true);
	msg +=vNumber("<?php echo constant("STR_ID_USUARIO_TIPO");?>:",f.fIdUsuarioTipo.value,11,true);
	msg +=vString("<?php echo constant("STR_LOGIN");?>:",f.fLogin.value,255,true);
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombre.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO1");?>:",f.fApellido1.value,255,false);
	msg +=vString("<?php echo constant("STR_APELLIDO2");?>:",f.fApellido2.value,255,false);
	msg +=vEmail("<?php echo constant("STR_EMAIL");?>:",f.fEmail.value,255,false);
if (msg != "") {
	alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
	return false;
}else return true;
}
function abrirVentana(bImg, file){
	preurl = "view.php?bImg=" + bImg + "&File=" + file;
	prename = "File";
	var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
	miv.focus();
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
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0"  title="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" alt="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
	
		<form name="form" id="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>" onsubmit="return enviar('<?php echo $_POST["MODO"];?>');">
<?
if ($_POST['MODO'] == constant("MNT_ALTA"))	$HELP="xx";
else	$HELP="xx";
?>
<div id="contenedor">
<?php include (constant("DIR_WS_INCLUDE") . "cabecera.php");?>
	<div id="envoltura">
		<div id="contenido">
		<div style="width: 100%">
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tr><td colspan="3" align="center" class="naranjab"><?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){echo(constant("STR_ANIADIR"));}else{echo(constant("STR_MODIFICAR"));}?></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fFecCambio"><?php echo constant("STR_FECHA_DE_CAMBIO");?></label>&nbsp;</td>
					<?php if ($cEntidad->getFecCambio() != "" && $cEntidad->getFecCambio() != "0000-00-00" && $cEntidad->getFecCambio() != "0000-00-00 00:00:00"){
						$cEntidad->setFecCambio($conn->UserDate($cEntidad->getFecCambio(),constant("USR_FECHA"),false));
					}else{
						//Palabras especiales (tomorrow, yesterday, ago, fortnight, now, today, day, week, month, year, hour, minute, min, second, sec)
						$date = date('Y-m-d', strtotime('+10 year'));
						$cEntidad->setFecCambio($date);
						$cEntidad->setFecCambio($conn->UserDate($cEntidad->getFecCambio(),constant("USR_FECHA"),false));
					}
					?>
					<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=fFecCambio','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO');?>" align="bottom" /></a>&nbsp;<input type="text" id="fFecCambio" name="fFecCambio" value="<?php echo $cEntidad->getFecCambio();?>" class=cajatexto id="tid-obliga" style="width:75px;" onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fFuncionalidad"><?php echo constant("STR_FUNCIONALIDAD");?></label>&nbsp;</td>
					<td><input type="text" id="fFuncionalidad" name="fFuncionalidad" value="<?php echo $cEntidad->getFuncionalidad();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fModo"><?php echo constant("STR_MODO");?></label>&nbsp;</td>
					<td><input type="text" id="fModo" name="fModo" value="<?php echo $cEntidad->getModo();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fQuery"><?php echo constant("STR_QUERY");?></label>&nbsp;</td>
					<td><textarea id="fQuery" name="fQuery" rows="6" cols="1" class="obliga"  onchange="javascript:trim(this);"><?php echo $cEntidad->getQuery();?></textarea></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIp"><?php echo constant("STR_IP");?></label>&nbsp;</td>
					<td><input type="text" id="fIp" name="fIp" value="<?php echo $cEntidad->getIp();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdUsuario"><?php echo constant("STR_USUARIO");?></label>&nbsp;</td>
					<td><?php $comboWI_USUARIOS->setNombre("fIdUsuario");?><?php echo $comboWI_USUARIOS->getHTMLCombo("1","obliga",$cEntidad->getIdUsuario()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdUsuarioTipo"><?php echo constant("STR_ID_USUARIO_TIPO");?></label>&nbsp;</td>
					<td><?php $comboWI_USUARIOS_TIPOS->setNombre("fIdUsuarioTipo");?><?php echo $comboWI_USUARIOS_TIPOS->getHTMLCombo("1","obliga",$cEntidad->getIdUsuarioTipo()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fLogin"><?php echo constant("STR_LOGIN");?></label>&nbsp;</td>
					<td><input type="text" id="fLogin" name="fLogin" value="<?php echo $cEntidad->getLogin();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNombre"><?php echo constant("STR_NOMBRE");?></label>&nbsp;</td>
					<td><input type="text" id="fNombre" name="fNombre" value="<?php echo $cEntidad->getNombre();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fApellido1"><?php echo constant("STR_APELLIDO1");?></label>&nbsp;</td>
					<td><input type="text" id="fApellido1" name="fApellido1" value="<?php echo $cEntidad->getApellido1();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fApellido2"><?php echo constant("STR_APELLIDO2");?></label>&nbsp;</td>
					<td><input type="text" id="fApellido2" name="fApellido2" value="<?php echo $cEntidad->getApellido2();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fEmail"><?php echo constant("STR_EMAIL");?></label>&nbsp;</td>
					<td><input type="text" id="fEmail" name="fEmail" value="<?php echo $cEntidad->getEmail();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="javascript:document.forms[0].MODO.value=document.forms[0].ORIGEN.value;lon();document.forms[0].submit();" /></td>
			<td><input type="submit" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ACEPTAR");?>" /></td>
		</tr>
	</table>
	</div>
</div>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="fId" value="<?php echo (empty($_POST['AddIdioma'])) ? $cEntidad->getId() : $_POST['fId'];?>" />
	<input type="hidden" name="fCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" />
	<input type="hidden" name="LSTIdHast" value="<?php echo (isset($_POST['LSTIdHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdHast']) : "";?>" />
	<input type="hidden" name="LSTId" value="<?php echo (isset($_POST['LSTId'])) ? $cUtilidades->validaXSS($_POST['LSTId']) : "";?>" />
	<input type="hidden" name="LSTFecCambioHast" value="<?php echo (isset($_POST['LSTFecCambioHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecCambioHast']) : "";?>" />
	<input type="hidden" name="LSTFecCambio" value="<?php echo (isset($_POST['LSTFecCambio'])) ? $cUtilidades->validaXSS($_POST['LSTFecCambio']) : "";?>" />
	<input type="hidden" name="LSTFuncionalidad" value="<?php echo (isset($_POST['LSTFuncionalidad'])) ? $cUtilidades->validaXSS($_POST['LSTFuncionalidad']) : "";?>" />
	<input type="hidden" name="LSTModo" value="<?php echo (isset($_POST['LSTModo'])) ? $cUtilidades->validaXSS($_POST['LSTModo']) : "";?>" />
	<input type="hidden" name="LSTQuery" value="<?php echo (isset($_POST['LSTQuery'])) ? $cUtilidades->validaXSS($_POST['LSTQuery']) : "";?>" />
	<input type="hidden" name="LSTIp" value="<?php echo (isset($_POST['LSTIp'])) ? $cUtilidades->validaXSS($_POST['LSTIp']) : "";?>" />
	<input type="hidden" name="LSTIdUsuarioHast" value="<?php echo (isset($_POST['LSTIdUsuarioHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdUsuarioHast']) : "";?>" />
	<input type="hidden" name="LSTIdUsuario" value="<?php echo (isset($_POST['LSTIdUsuario'])) ? $cUtilidades->validaXSS($_POST['LSTIdUsuario']) : "";?>" />
	<input type="hidden" name="LSTIdUsuarioTipoHast" value="<?php echo (isset($_POST['LSTIdUsuarioTipoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdUsuarioTipoHast']) : "";?>" />
	<input type="hidden" name="LSTIdUsuarioTipo" value="<?php echo (isset($_POST['LSTIdUsuarioTipo'])) ? $cUtilidades->validaXSS($_POST['LSTIdUsuarioTipo']) : "";?>" />
	<input type="hidden" name="LSTLogin" value="<?php echo (isset($_POST['LSTLogin'])) ? $cUtilidades->validaXSS($_POST['LSTLogin']) : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $cUtilidades->validaXSS($_POST['LSTNombre']) : "";?>" />
	<input type="hidden" name="LSTApellido1" value="<?php echo (isset($_POST['LSTApellido1'])) ? $cUtilidades->validaXSS($_POST['LSTApellido1']) : "";?>" />
	<input type="hidden" name="LSTApellido2" value="<?php echo (isset($_POST['LSTApellido2'])) ? $cUtilidades->validaXSS($_POST['LSTApellido2']) : "";?>" />
	<input type="hidden" name="LSTEmail" value="<?php echo (isset($_POST['LSTEmail'])) ? $cUtilidades->validaXSS($_POST['LSTEmail']) : "";?>" />
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
	<input type="hidden" name="wi_historico_cambios_next_page" value="<?php echo (isset($_POST['wi_historico_cambios_next_page'])) ? $cUtilidades->validaXSS($_POST['wi_historico_cambios_next_page']) : "1";?>" /></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>
	<input type="hidden" name="wi_historico_cambios_next_page" value="<?php echo (isset($_POST['wi_historico_cambios_next_page'])) ? $cUtilidades->validaXSS($_POST['wi_historico_cambios_next_page']) : "1";?>" />