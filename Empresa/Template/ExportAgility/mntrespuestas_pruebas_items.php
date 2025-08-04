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
function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
		lon();
		f.LSTIdProceso.value=f.elements['LSTIdProceso'].value;
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo constant("STR_EMPRESA");?>:", f.LSTIdEmpresa.value, 11, true);
	msg +=vNumber("<?php echo constant("STR_PROCESO");?>:",f.elements['LSTIdProceso'].value, 11, true);
	msg +=vNumber("<?php echo constant("STR_CANDIDATO");?>:",f.elements['LSTIdCandidato'].value, 11, false);
	if (msg != "") {
		jAlert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.","<?php echo constant("STR_NOTIFICACION");?>");
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
					<td><?php $comboEMPRESAS->setNombre("LSTIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLComboMenu("1","obliga",$cEntidad->getIdEmpresa()," onchange=\"javascript:cambiaIdEmpresa()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdProceso"><?php echo constant("STR_PROCESO");?></label>&nbsp;</td>
						<td><div id="comboIdProceso"><?php $comboPROCESOS->setNombre("LSTIdProceso");?><?php echo $comboPROCESOS->getHTMLComboNull("1","obliga",$cEntidad->getIdProceso(),"onchange=\"javascript:cambiaIdProceso()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdCandidato"><?php echo constant("STR_CANDIDATO");?></label>&nbsp;</td>
						<td><div id="comboIdCandidato"><?php $comboCANDIDATOS->setNombre("LSTIdCandidato");?><?php echo $comboCANDIDATOS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdCandidato(),"onchange=\"javascript:cambiaIdCandidato()\" ","");?></div></td>
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
	<input type="hidden" name="LSTLineasPagina" value="99999" />
	<input type="hidden" name="LSTOrderBy" value="idProceso,idPrueba,idCandidato,idItem" />
	<input type="hidden" name="LSTOrder" value="ASC" />
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_BUSCAR");?>" />
	<input type="hidden" name="respuestas_pruebas_items_next_page" value="1" />
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdEmpresa(){
								var f= document.forms[0];
								$("#comboIdProceso").show().load("jQuery.php",{sPG:"comboprocesosAgility",bBus:"1",multiple:"0",nLineas:"1",bObliga:"1",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdProceso",fJSProp:"cambiaIdProceso",LSTIdEmpresa:f.LSTIdEmpresa.value,vSelected:"<?php echo $cEntidad->getIdProceso();?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdCandidato").show().load("jQuery.php",{sPG:"combocandidatosAgility",bBus:"1",multiple:"1",nLineas:"20",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdCandidato",fJSProp:"cambiaIdCandidato",LSTIdEmpresa:f.LSTIdEmpresa.value,LSTIdProceso:f.LSTIdProceso.value,vSelected:"<?php echo $cEntidad->getIdCandidato();?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdProceso(){
								var f= document.forms[0];
								$("#comboIdCandidato").show().load("jQuery.php",{sPG:"combocandidatosAgility",bBus:"1",multiple:"1",nLineas:"20",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdCandidato",fJSProp:"cambiaIdCandidato",LSTIdEmpresa:f.LSTIdEmpresa.value,LSTIdProceso:f.LSTIdProceso.value,vSelected:"<?php echo $cEntidad->getIdCandidato();?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdCandidato(){								
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