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
	<!-- <script src="https://cdn.tiny.cloud/1/19u4q91vla6r5niw2cs7kaymfs18v3j11oizctq52xltyrf4/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->
	<script language="javascript" type="text/javascript" src="codigo/tinymce/tinymce.min.js"></script>
  <script>tinymce.init({ selector:'.tinymce' });</script>

<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php");?>
function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
		lon();
		f.fIdPrueba.value=f.elements['fIdPrueba'].value;
		f.fIdTipoInforme.value=f.elements['fIdTipoInforme'].value;
		f.fIdIp.value=f.elements['fIdIp'].value;
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
  var myf = $('#fTexto_ifr');
  var editorContent = $('#tinymce[data-id="fTexto"]', myf.contents()).html();
	msg +=vString("<?php echo constant("STR_IDIOMA");?>:",f.fCodIdiomaIso2.value,2,true);
	msg +=vNumber("<?php echo constant("STR_PRUEBA");?>:",f.elements['fIdPrueba'].value,11,true);
	msg +=vNumber("<?php echo constant("STR_TIPO_INFORME");?>:",f.elements['fIdTipoInforme'].value,11,true);
	msg +=vNumber("<?php echo constant("STR_IR");?>:",f.fIdIr.value,11,true);
	msg +=vNumber("<?php echo constant("STR_IP");?>:",f.elements['fIdIp'].value,11,true);
	msg +=vString("<?php echo constant("STR_TEXTO");?>:",editorContent,333333,true);
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
<body onload="_body_onload();cambiaCodIdiomaIso2();cambiaIdIr();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
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
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fCodIdiomaIso2"><?php echo constant("STR_IDIOMA");?></label>&nbsp;</td>
					<td><?php $comboWI_IDIOMAS->setNombre("fCodIdiomaIso2");?><?php echo $comboWI_IDIOMAS->getHTMLCombo("1","obliga",$cEntidad->getCodIdiomaIso2()," onchange=\"javascript:cambiaCodIdiomaIso2()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdPrueba"><?php echo constant("STR_PRUEBA");?></label>&nbsp;</td>
						<td><div id="comboIdPrueba"><?php $comboPRUEBAS->setNombre("fIdPrueba");?><?php echo $comboPRUEBAS->getHTMLComboNull("1","obliga",$cEntidad->getIdPrueba(),"onchange=\"javascript:cambiaIdPrueba()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdTipoInforme"><?php echo constant("STR_TIPO_INFORME");?></label>&nbsp;</td>
						<td><div id="comboIdTipoInforme"><?php $comboINFORMES_PRUEBAS->setNombre("fIdTipoInforme");?><?php echo $comboINFORMES_PRUEBAS->getHTMLComboNull("1","obliga",$cEntidad->getIdTipoInforme(),"onchange=\"javascript:cambiaIdTipoInforme()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdIr"><?php echo constant("STR_IR");?></label>&nbsp;</td>
					<td><?php $comboRANGOS_IR->setNombre("fIdIr");?><?php echo $comboRANGOS_IR->getHTMLCombo("1","obliga",$cEntidad->getIdIr()," onchange=\"javascript:cambiaIdIr()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdIp"><?php echo constant("STR_IP");?></label>&nbsp;</td>
						<td><div id="comboIdIp"><?php $comboRANGOS_IP->setNombre("fIdIp");?><?php echo $comboRANGOS_IP->getHTMLComboNull("1","obliga",$cEntidad->getIdIp(),"onchange=\"javascript:cambiaIdIp()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fTexto"><?php echo constant("STR_TEXTO");?></label>&nbsp;</td>
					<td><textarea class="tinymce" data-id="fTexto" id="fTexto" name="fTexto"><?php echo  $cEntidad->getTexto();?></textarea>

							</td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="javascript:document.forms[0].MODO.value=document.forms[0].ORIGEN.value;lon();document.forms[0].submit();" /></td>
			<td><input type="submit" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ACEPTAR");?>" /></td>
			<td><input type="submit" <?php if($_POST['MODO']!=323){echo 'disabled="disabled"';}?> class="botonesbig" id="" name="btnAddIdioma" value="<?php echo constant("STR_ANADIR_EN_OTRO_IDIOMA");?>" onclick="javascript:document.forms[0].MODO.value=<?php echo constant("MNT_NUEVO");?>;document.forms[0].AddIdioma.value=1" /></td>
		</tr>
	</table>
	</div>
</div>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo (isset($_POST['LSTCodIdiomaIso2'])) ? $cUtilidades->validaXSS($_POST['LSTCodIdiomaIso2']) : "";?>" />
	<input type="hidden" name="LSTIdPruebaHast" value="<?php echo (isset($_POST['LSTIdPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPruebaHast']) : "";?>" />
	<input type="hidden" name="LSTIdPrueba" value="<?php echo (isset($_POST['LSTIdPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTIdPrueba']) : "";?>" />
	<input type="hidden" name="LSTIdTipoInformeHast" value="<?php echo (isset($_POST['LSTIdTipoInformeHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoInformeHast']) : "";?>" />
	<input type="hidden" name="LSTIdTipoInforme" value="<?php echo (isset($_POST['LSTIdTipoInforme'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoInforme']) : "";?>" />
	<input type="hidden" name="LSTIdIrHast" value="<?php echo (isset($_POST['LSTIdIrHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdIrHast']) : "";?>" />
	<input type="hidden" name="LSTIdIr" value="<?php echo (isset($_POST['LSTIdIr'])) ? $cUtilidades->validaXSS($_POST['LSTIdIr']) : "";?>" />
	<input type="hidden" name="LSTIdIpHast" value="<?php echo (isset($_POST['LSTIdIpHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdIpHast']) : "";?>" />
	<input type="hidden" name="LSTIdIp" value="<?php echo (isset($_POST['LSTIdIp'])) ? $cUtilidades->validaXSS($_POST['LSTIdIp']) : "";?>" />
	<input type="hidden" name="LSTTextoHast" value="<?php echo (isset($_POST['LSTTextoHast'])) ? $cUtilidades->validaXSS($_POST['LSTTextoHast']) : "";?>" />
	<input type="hidden" name="LSTTexto" value="<?php echo (isset($_POST['LSTTexto'])) ? $cUtilidades->validaXSS($_POST['LSTTexto']) : "";?>" />
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
	<input type="hidden" name="rangos_textos_next_page" value="<?php echo (isset($_POST['rangos_textos_next_page'])) ? $cUtilidades->validaXSS($_POST['rangos_textos_next_page']) : "1";?>" />
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaCodIdiomaIso2(){
								var f= document.forms[0];
								$("#comboIdPrueba").show().load("jQuery.php",{sPG:"combopruebas",bBus:"0",multiple:"0",nLineas:"1",bObliga:"1",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdPrueba",fJSProp:"cambiaIdPrueba",fCodIdiomaIso2:f.fCodIdiomaIso2.value,vSelected:"<?php echo $cEntidad->getIdPrueba();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdTipoInforme").show().load("jQuery.php",{sPG:"comboinformes_pruebas",bBus:"0",multiple:"0",nLineas:"1",bObliga:"1",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdTipoInforme",fJSProp:"cambiaIdTipoInforme",fIdPrueba:f.fIdPrueba.value,fCodIdiomaIso2:f.fCodIdiomaIso2.value,vSelected:"<?php echo $cEntidad->getIdTipoInforme();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdPrueba(){
								var f= document.forms[0];
								$("#comboIdTipoInforme").show().load("jQuery.php",{sPG:"comboinformes_pruebas",bBus:"0",multiple:"0",nLineas:"1",bObliga:"1",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdTipoInforme",fJSProp:"cambiaIdTipoInforme",fIdPrueba:f.fIdPrueba.value,fCodIdiomaIso2:f.fCodIdiomaIso2.value,vSelected:"<?php echo $cEntidad->getIdTipoInforme();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdTipoInforme(){
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdIr(){
								var f= document.forms[0];
								$("#comboIdIp").show().load("jQuery.php",{sPG:"comborangos_ip",bBus:"0",multiple:"0",nLineas:"1",bObliga:"1",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdIp",fJSProp:"cambiaIdIp",fIdRangoIr:f.fIdIr.value,vSelected:"<?php echo $cEntidad->getIdIp();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdIp(){
							}
							//]]>
						</script></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>
	<input type="hidden" name="rangos_textos_next_page" value="<?php echo (isset($_POST['rangos_textos_next_page'])) ? $cUtilidades->validaXSS($_POST['rangos_textos_next_page']) : "1";?>" />
