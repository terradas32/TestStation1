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
		
<title><?php echo constant("NOMBRE_SITE");?></title>
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
	<script src="https://cdn.tiny.cloud/1/19u4q91vla6r5niw2cs7kaymfs18v3j11oizctq52xltyrf4/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script><script>tinymce.init({ selector:'.tinymce' });</script>

<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php");?>
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
	msg +=vNumber("<?php echo constant("STR_ID_PRUEBA");?>:",f.fIdPrueba.value,11,true);
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombre.value,255,true);
	msg +=vString("<?php echo constant("STR_DESCRIPCION");?>:",f.fDescripcion.value,255,true);
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
function listapuntuaciones(){
	var f = document.forms[0];
	var paginacargada = "Baremos.php";
	$("div#listapuntuaciones").hide().load(paginacargada,{fIdPrueba: f.fIdPrueba.value,fIdBaremo: f.fIdBaremo.value,fIdBloque: f.fIdBloque.value,fIdEscala: f.fIdEscala.value,MODO:"<?php echo constant('MNT_LISTAPUNTBAREMOS')?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
}
function aniadepunt(){
	var f = document.forms[0];
	var paginacargada = "Baremos.php";
	$("div#listapuntuaciones").hide().load(paginacargada,{fAniade:"1",fIdBaremo: f.fIdBaremo.value,fIdPrueba: f.fIdPrueba.value,fIdBloque: f.fIdBloque.value,fIdEscala: f.fIdEscala.value,fPuntMinima: f.fPuntMinima.value,fPuntMaxima: f.fPuntMaxima.value,fPuntBaremada: f.fPuntBaremada.value,MODO:"<?php echo constant('MNT_LISTAPUNTBAREMOS')?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
	f.fPuntMinima.value="";
	f.fPuntMaxima.value="";
	f.fPuntBaremada.value="";
	
}
function borrarresult(idResultado,idBaremo,idPrueba,idBloque,idEscala){
	var f = document.forms[0];
	if(confirm('<?php echo constant("DEL_GENERICO");?>')){
		var paginacargada = "Baremos.php";
		$("div#listapuntuaciones").hide().load(paginacargada,{fBorra:"1",fIdResultado: idResultado,fIdBaremo: idBaremo,fIdPrueba: idPrueba,fIdBloque: idBloque,fIdEscala: idEscala,MODO:"<?php echo constant('MNT_LISTAPUNTBAREMOS')?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
	}
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
<body onload="_body_onload();listapuntuaciones();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');"  onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0"  title="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" alt="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
	
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
			<table cellspacing="5" cellpadding="0" width="100%" border="0">
			<tr>
				<td width="50%">
					<table cellspacing="5" cellpadding="0" width="100%" border="0">
						
						<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
						<tr>
							<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
							<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_NOMBRE");?>&nbsp;</td>
							<td class="negro"><?php echo $cEntidad->getNombre();?></td>
						</tr>
						<tr>
							<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
							<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_DESCRIPCION");?>&nbsp;</td>
							<td class="negro"><?php echo $cEntidad->getDescripcion();?></td>
						</tr>
						<tr>
							<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
							<td nowrap="nowrap" width="140" class="negrob" valign="top">Puntuación Mínima&nbsp;</td>
							<td><input type="text" name="fPuntMinima" value="" class="obliga"  onchange="javascript:trim(this);" style="width: 100px;"/></td>
						</tr>
						<tr>
							<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
							<td nowrap="nowrap" width="140" class="negrob" valign="top">Puntuación Máxima&nbsp;</td>
							<td><input type="text" name="fPuntMaxima" value="" class="obliga"  onchange="javascript:trim(this);" style="width: 100px;"/></td>
						</tr>
						<tr>
							<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
							<td nowrap="nowrap" width="140" class="negrob" valign="top">Puntuación Baremada&nbsp;</td>
							<td><input type="text" name="fPuntBaremada" value="" class="obliga"  onchange="javascript:trim(this);" style="width: 100px;"/></td>
						</tr>
						<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
						<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
						<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
					</table>
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="document.forms[0].MODO.value=document.forms[0].ORIGEN.value;document.forms[0].submit();" /></td>
							<td><input type="button" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ANIADIR");?>" onclick="javascript:aniadepunt();"/></td>
						</tr>
					</table>
				</td>
				<td width="50%" valign="top">
					<table cellspacing="5" cellpadding="0" border="0">		
						<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
						<tr>
							<td colspan="3">
								<div id="listapuntuaciones"></div>	
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
</div>
	<input type="hidden" name="fBorra" value="" />
	<input type="hidden" name="fIdResultado" value="" />
	<input type="hidden" name="fAniade" value="" />
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="fIdPrueba" value="<?php echo (empty($_POST['AddIdioma'])) ? $cEntidad->getIdPrueba() : $_POST['fIdPrueba'];?>" />
	<input type="hidden" name="fIdBaremo" value="<?php echo (empty($_POST['AddIdioma'])) ? $cEntidad->getIdBaremo() : $_POST['fIdBaremo'];?>" />
	<input type="hidden" name="fIdBloque" value="<?php echo (empty($_POST['AddIdioma'])) ? $cEntidad->getIdBloque() : $_POST['fIdBloque'];?>" />
	<input type="hidden" name="fIdEscala" value="<?php echo (empty($_POST['AddIdioma'])) ? $cEntidad->getIdEscala() : $_POST['fIdEscala'];?>" />
	<input type="hidden" name="LSTIdBaremoHast" value="<?php echo (isset($_POST['LSTIdBaremoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdBaremoHast']) : "";?>" />
	<input type="hidden" name="LSTIdBaremo" value="<?php echo (isset($_POST['LSTIdBaremo'])) ? $cUtilidades->validaXSS($_POST['LSTIdBaremo']) : "";?>" />
	<input type="hidden" name="LSTIdPruebaHast" value="<?php echo (isset($_POST['LSTIdPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPruebaHast']) : "";?>" />
	<input type="hidden" name="LSTIdPrueba" value="<?php echo (isset($_POST['LSTIdPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTIdPrueba']) : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $cUtilidades->validaXSS($_POST['LSTNombre']) : "";?>" />
	<input type="hidden" name="LSTDescripcion" value="<?php echo (isset($_POST['LSTDescripcion'])) ? $cUtilidades->validaXSS($_POST['LSTDescripcion']) : "";?>" />
	<input type="hidden" name="LSTObservaciones" value="<?php echo (isset($_POST['LSTObservaciones'])) ? $cUtilidades->validaXSS($_POST['LSTObservaciones']) : "";?>" />
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
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $cUtilidades->validaXSS($_POST['LSTLineasPagina']) : constant("CNF_LINEAS_PAGINA");?>" /></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?></div>
</form>

</body></html>