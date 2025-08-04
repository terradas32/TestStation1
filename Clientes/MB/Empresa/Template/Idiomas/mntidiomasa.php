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
<title>Azul Pomodoro</title>
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
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vString("<?php echo constant("STR_COD_ISO2")?>:",f.fCodIso2.value,2,true);
	msg +=vString("<?php echo constant("STR_NOMBRE")?>:",f.fNombre.value,255,true);
	msg +=vString("<?php echo constant("STR_IMAGEN")?>:",f.fPathImagen.value,255,false);
	msg +=vNumber("<?php echo constant("STR_ORDEN")?>:",f.fOrden.value,11,false);
	aActivoFront = document.getElementsByName('fActivoFront');
	sId = "";
	for(i=0; i < aActivoFront.length; i++ ){
		if (aActivoFront[i].type == "radio" && aActivoFront[i].name == "fActivoFront"){
			if (aActivoFront[i].checked){
				sId = aActivoFront[i].value;
			}
		}
	}
	msg +=vString("<?php echo constant("STR_ACTIVO_EN_EL_FRONT")?>:",sId,2,true);
	aActivoBack = document.getElementsByName('fActivoBack');
	sId = "";
	for(i=0; i < aActivoBack.length; i++ ){
		if (aActivoBack[i].type == "radio" && aActivoBack[i].name == "fActivoBack"){
			if (aActivoBack[i].checked){
				sId = aActivoBack[i].value;
			}
		}
	}
	msg +=vString("<?php echo constant("STR_ACTIVO_EN_EL_BACK")?>:",sId,2,true);
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
		<form name="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar('<?php echo $_POST['MODO'];?>');">
<?php 
if ($_POST['MODO'] == constant("MNT_ALTA"))	$HELP="xx";
else	$HELP="xx";

?>
<div id="contenedor">
	<?php include_once(constant("DIR_WS_INCLUDE") . 'cabecera.php');?>
	<div id="envoltura">
		<div id="contenido">
			
			<div style="width: 100%">
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tr><td colspan="3" align="center" class="naranjab"><?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){echo(constant("STR_ANIADIR"));}else{echo(constant("STR_MODIFICAR"));}?></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_COD_ISO2")?>&nbsp;</td>
					<td><input type="text" name="fCodIso2" value="<?php echo $cEntidad->getCodIso2();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_NOMBRE")?>&nbsp;</td>
					<td><input type="text" name="fNombre" value="<?php echo $cEntidad->getNombre();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_IMAGEN")?>&nbsp;</td>
					<td><input onkeydown="blur();" onkeypress="blur();" type="file" name="fPathImagen" class="cajatexto"  /></td>
				</tr>
					<?php if ($cEntidad->getPathImagen() != "")
					{
						$img=@getimagesize(constant("HTTP_SERVER") . $cEntidad->getPathImagen());
						$bIimg = (empty($img)) ? 0 : 1;
					?>
						<tr>
							<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
							<td width="140" class="negrob" valign="top">&nbsp;</td>
							<td>&nbsp;<a class="azul" href="#" onclick="abrirVentana('<?php echo $bIimg;?>','<?php echo base64_encode(constant("HTTP_SERVER") . $cEntidad->getPathImagen());?>');"><?php echo str_replace("_","&nbsp;", basename($cEntidad->getPathImagen()));?></a>&nbsp;<input onmouseover="this.style.cursor='pointer'" type="checkbox" name="cfPathImagen" />&nbsp;<?php echo constant("STR_QUITAR");?></td>
						</tr>
					<?php } ?>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_ORDEN")?>&nbsp;</td>
					<td><input type="text" name="fOrden" value="<?php echo $cEntidad->getOrden();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_ACTIVO_EN_EL_FRONT")?>&nbsp;</td>
					<td><input type="radio" name="fActivoFront" id="fActivoFront1" value="1"  <?php echo ($cEntidad->getActivoFront() != "" && strtoupper($cEntidad->getActivoFront()) == "1") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="fActivoFront1">Sí</label>&nbsp;<input type="radio" name="fActivoFront" id="fActivoFront0" value="0"  <?php echo ($cEntidad->getActivoFront() != "" && strtoupper($cEntidad->getActivoFront()) == "0") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="fActivoFront0">No</label>&nbsp;</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_ACTIVO_EN_EL_BACK")?>&nbsp;</td>
					<td><input type="radio" name="fActivoBack" id="fActivoBack1" value="1"  <?php echo ($cEntidad->getActivoBack() != "" && strtoupper($cEntidad->getActivoBack()) == "1") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="fActivoBack1">Sí</label>&nbsp;<input type="radio" name="fActivoBack" id="fActivoBack0" value="0"  <?php echo ($cEntidad->getActivoBack() != "" && strtoupper($cEntidad->getActivoBack()) == "0") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="fActivoBack0">No</label>&nbsp;</td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="document.forms[0].MODO.value=document.forms[0].ORIGEN.value;document.forms[0].submit();" /></td>
			<td><input type="submit" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ACEPTAR");?>" /></td>
		</tr>
	</table>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="fFecAlta" value="<?php echo $cEntidad->getFecAlta();?>" />
	<input type="hidden" name="fFecMod" value="<?php echo $cEntidad->getFecMod();?>" />
	<input type="hidden" name="fUsuAlta" value="<?php echo $cEntidad->getUsuAlta();?>" />
	<input type="hidden" name="fUsuMod" value="<?php echo $cEntidad->getUsuMod();?>" />
	<input type="hidden" name="LSTCodIso2" value="<?php echo (isset($_POST['LSTCodIso2'])) ? $_POST['LSTCodIso2'] : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $_POST['LSTNombre'] : "";?>" />
	<input type="hidden" name="LSTPathImagen" value="<?php echo (isset($_POST['LSTPathImagen'])) ? $_POST['LSTPathImagen'] : "";?>" />
	<input type="hidden" name="LSTOrden" value="<?php echo (isset($_POST['LSTOrden'])) ? $_POST['LSTOrden'] : "";?>" />
	<input type="hidden" name="LSTActivoFront" value="<?php echo (isset($_POST['LSTActivoFront'])) ? $_POST['LSTActivoFront'] : "";?>" />
	<input type="hidden" name="LSTActivoBack" value="<?php echo (isset($_POST['LSTActivoBack'])) ? $_POST['LSTActivoBack'] : "";?>" />
	<input type="hidden" name="LSTFecAltaHast" value="<?php echo (isset($_POST['LSTFecAltaHast'])) ? $_POST['LSTFecAltaHast'] : "";?>" />
	<input type="hidden" name="LSTFecAlta" value="<?php echo (isset($_POST['LSTFecAlta'])) ? $_POST['LSTFecAlta'] : "";?>" />
	<input type="hidden" name="LSTFecModHast" value="<?php echo (isset($_POST['LSTFecModHast'])) ? $_POST['LSTFecModHast'] : "";?>" />
	<input type="hidden" name="LSTFecMod" value="<?php echo (isset($_POST['LSTFecMod'])) ? $_POST['LSTFecMod'] : "";?>" />
	<input type="hidden" name="LSTUsuAlta" value="<?php echo (isset($_POST['LSTUsuAlta'])) ? $_POST['LSTUsuAlta'] : "";?>" />
	<input type="hidden" name="LSTUsuMod" value="<?php echo (isset($_POST['LSTUsuMod'])) ? $_POST['LSTUsuMod'] : "";?>" />
	<input type="hidden" name="LSTOrderBy" value="<?php echo (isset($_POST['LSTOrderBy'])) ? $_POST['LSTOrderBy'] : "";?>" />
	<input type="hidden" name="LSTOrder" value="<?php echo (isset($_POST['LSTOrder'])) ? $_POST['LSTOrder'] : "";?>" />
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $_POST['LSTLineasPagina'] : constant("CNF_LINEAS_PAGINA");?>" />
	<input type="hidden" name="idiomas_next_page" value="<?php echo (isset($_POST['idiomas_next_page'])) ? $cUtilidades->validaXSS($_POST['idiomas_next_page']) : "1";?>" />
</div>
		    
		</div>
	</div>
     <?php include_once(constant("DIR_WS_INCLUDE") . 'menus.php');?>
    <?php //include_once(constant("DIR_WS_INCLUDE") . 'derecha.php');?>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
</form>
</body></html>