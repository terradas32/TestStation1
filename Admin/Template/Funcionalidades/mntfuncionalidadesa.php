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
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombre.value,255,true);
	msg +=vString("<?php echo constant("STR_DESCRIPCION");?>:",f.fDescripcion.value,255,false);
	msg +=vString("<?php echo constant("STR_URL");?>:",f.fUrl.value,255,false);
	msg +=vString("<?php echo constant("STR_POPUP");?>:",f.fPopUp.value,2,false);
	msg +=vString("<?php echo constant("STR_BG_FILE");?>:",f.fBgFile.value,255,false);
	msg +=vString("<?php echo constant("STR_BG_COLOR");?>:",f.fBgColor.value,255,false);
if (msg != "") {
	alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
	return false;
}else return true;
}
function abrirVentana(bImg,file){
	preurl = "view.php?bImg="+ bImg + "&File=" + file;
	prename = "File";
	var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no");
	miv.focus();
}
function setDonde(oObject){
	var f=document.forms[0];
	if (oObject.name == "fDentroDe"){
		f.fDespuesDe.selectedIndex=0;
	}else if (oObject.name == "fDespuesDe"){
			f.fDentroDe.selectedIndex=0;
	}
}
// Sobreescribinos la función
function doColor(oColor) {
	if (oColor.type == 'text'){
		oColor.style.backgroundColor=oColor.value;
	}
	var f=document.forms[0];
	f.fBgColor.value=oColor.style.backgroundColor.toUpperCase();
	f.fBgColor.style.backgroundColor=oColor.style.backgroundColor.toUpperCase();
	oPopup.hide();
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
<body onload="_body_onload();cambiadentrode();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32"  border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
		<form name="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar('<?php echo $_POST['MODO'];?>');">
<?php
$HELP="xx";

?>
<div id="contenedor">
	<?php include_once(constant("DIR_WS_INCLUDE") . 'cabecera.php');?>
	<div id="envoltura">
		<div id="contenido">

			<div style="width: 100%">
			<table cellspacing="0" cellpadding="0" width="100%" border="0" >
				<tr><td colspan="3" align="center" class="naranjab"><?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){echo(constant("STR_ANIADIR"));}else{echo(constant("STR_MODIFICAR"));}?></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_NOMBRE");?>&nbsp;</td>
					<td><input type="text" name="fNombre" value="<?php echo $cEntidad->getNombre();?>" class="obliga" onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_DESCRIPCION");?>&nbsp;</td>
					<td><input type="text" name="fDescripcion" value="<?php echo $cEntidad->getDescripcion();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_DENTRO_DE");?> ...&nbsp;</td>
					<td><?php $comboDENTRO_DE->setNombre("fDentroDe");?><?php echo $comboDENTRO_DE->getHTMLComboMenu("1","cajatexto",$cEntidad->getDentroDe(),"onchange=\"javascript:cambiadentrode()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_DESPUES_DE");?> ...&nbsp;</td>
					<td><div id="combodespuesde"><?php $comboDESPUES_DE->setNombre("fDespuesDe");?><?php echo $comboDESPUES_DE->getHTMLComboNull("1","cajatexto",$cEntidad->getDespuesDe(),"onchange=\"javascript:cambiadespuesde()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_URL");?>&nbsp;</td>
					<td><input type="text" name="fUrl" value="<?php echo $cEntidad->getUrl();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_POPUP");?>&nbsp;</td>
					<td><input type="checkbox" name="fPopUp" <?php echo ($cEntidad->getPopUp() != "" && strtoupper($cEntidad->getPopUp()) == "ON") ? "checked=\"checked\"" : "";?> /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob"><?php echo constant("STR_ENTRADA_POR_DEFECTO");?>&nbsp;</td>
					<td>
						<select name='fModoDefecto' size='1' class="cajatexto">
							<?php $sModoDefecto = $cEntidad->getModoDefecto();?>
							<option style='color:#000000;' value='MNT_INICIO' <?php echo (!empty($sModoDefecto) && $sModoDefecto == "MNT_INICIO") ? "selected='selected'" : "";?>><?php echo constant("STR_BUSCADOR");?></option>
							<option style='color:#000000;' value='MNT_NUEVO' <?php echo (!empty($sModoDefecto) && $sModoDefecto == "MNT_NUEVO") ? "selected='selected'" : "";?>><?php echo constant("STR_ALTA");?></option>
							<option style='color:#000000;' value='MNT_LISTAR' <?php echo (!empty($sModoDefecto) && $sModoDefecto == "MNT_LISTAR") ? "selected='selected'" : "";?>><?php echo constant("STR_LISTA");?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob"><?php echo constant("STR_ICONOS_DIRECTOS_DEL_MENU");?>&nbsp;</td>
					<td>
						<select name='fIconosMenu[]' size='3' class="cajatexto" multiple="multiple">
							<?php if (!is_array($cEntidad->getIconosMenu())){
									$aIconosMenu = array();
								}else{
									$aIconosMenu = $cEntidad->getIconosMenu();
								}
							?>
							<option style='color:#000000;' value='MNT_BUSCAR' <?php echo (in_array("MNT_BUSCAR", $aIconosMenu)) ? "selected='selected'" : "";?>><?php echo constant("STR_BUSCADOR");?></option>
							<option style='color:#000000;' value='MNT_NUEVO' <?php echo (in_array("MNT_NUEVO", $aIconosMenu)) ? "selected='selected'" : "";?>><?php echo constant("STR_ALTA");?></option>
						</select>
					</td>
				</tr>

				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_BG_FILE");?>&nbsp;</td>
					<td><input onkeydown="blur();" onkeypress="blur();" type="file" name="fBgFile" class="cajatexto"  /></td>
				</tr>
					<?php if ($cEntidad->getBgFile() != "")
					{
						$img=@getimagesize(constant("HTTP_SERVER") . $cEntidad->getBgFile());
						$bIimg = (empty($img)) ? 0 : 1;
					?>
						<tr>
							<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
							<td width="140" class="negrob" valign="top">&nbsp;</td>
							<td>&nbsp;<a class="azul" href="#" onclick="abrirVentana('<?php echo $bIimg;?>','<?php echo constant("HTTP_SERVER") . $cEntidad->getBgFile();?>');"><?php echo str_replace("_","&nbsp;", basename($cEntidad->getBgFile()));?></a>&nbsp;<input onmouseover="this.style.cursor='pointer';this.style.cursor='pointer';" type="checkbox" name="cfBgFile" />&nbsp;<?php echo constant("STR_QUITAR");?></td>
						</tr>
					<?php } ?>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_BG_COLOR");?>&nbsp;</td>
					<td><input type="text" name="fBgColor" value="<?php echo $cEntidad->getBgColor();?>" class="cajatexto" onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_PUBLICO");?>&nbsp;</td>
					<td><input type="checkbox" name="fPublico" <?php echo ($cEntidad->getPublico() != "" && strtoupper($cEntidad->getPublico()) == "ON") ? "checked=\"checked\"" : "";?> /></td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3" ><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td class="negro"><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="javascript:document.forms[0].MODO.value=document.forms[0].ORIGEN.value;lon();document.forms[0].submit();" /></td>
			<td ><input type="submit" <?php echo ($_bModificar) ? "" : "disabled";?>  class="botones" id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_GUARDAR");?>" /></td>
		</tr>
	</table>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="fIdFuncionalidad" value="<?php echo $cEntidad->getIdFuncionalidad();?>" />
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

	<script language="javascript" type="text/javascript">
		//<![CDATA[
		function cambiadentrode(){
			var f= document.forms[0];								
			$("#combodespuesde").show().load("jQuery.php",{sPG:"combodespuesde",bBus:"0",multiple:"0",nLineas:"1",bObliga:"0",fDentroDe:f.fDentroDe.value,vSelected:"<?php echo $cEntidad->getDespuesDe();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
		}
		//]]>
	</script>
	<script language="javascript" type="text/javascript">
		//<![CDATA[
		function cambiadespuesde(){								
		}
		//]]>
	</script>
</div>
		    
		</div>
	</div>
     <?php include_once(constant("DIR_WS_INCLUDE") . 'menus.php');?>
    <?php //include_once(constant("DIR_WS_INCLUDE") . 'derecha.php');?>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
<input type="hidden" name="funcionalidades_next_page" value="<?php echo (isset($_POST['funcionalidades_next_page'])) ? $cUtilidades->validaXSS($_POST['funcionalidades_next_page']) : "1";?>" />
</form>
</body></html>