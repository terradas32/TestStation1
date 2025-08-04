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
		f.fIdCompetencia.value=f.elements['fIdCompetencia'].value;
		f.fIdItem.value=f.elements['fIdItem'].value;
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo constant("STR_TIPO_COMPETENCIA");?>:",f.fIdTipoCompetencia.value,11,true);
	msg +=vNumber("<?php echo constant("STR_COMPETENCIA");?>:",f.elements['fIdCompetencia'].value,11,true);
	msg +=vNumber("<?php echo constant("STR_PRUEBA");?>:",f.fIdPrueba.value,11,true);
	msg +=vNumber("<?php echo constant("STR_ITEM");?>:",f.elements['fIdItem'].value,11,true);
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
function listaItems(){
	var f = document.forms[0];

	var paginacargada = "Competencias_items.php";
	$("div#lItems").show().load(paginacargada,{
		idPrueba: f.fIdPrueba.value,
		idCompetencia: f.fIdCompetencia.value,
		idTipoCompetencia: f.fIdTipoCompetencia.value,
		MODO:"<?php echo constant('MNT_CARGAITEMS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" 
	}).fadeIn("slow");
	
}
function anadir(){
	var f=document.forms[0];
	var sItems = "";
	var i=0;
	ObjCombo=document.forms[0].elements["fListaPrincipal"];
	
	for(i=0; i < ObjCombo.length; i++ ){
		if(ObjCombo.options[i].selected){
			sItems += "," + ObjCombo.options[i].value;
		}
	}
	if(f.fIdPrueba.value!="" && f.fIdCompetencia.value!="" && f.fIdTipoCompetencia.value && sItems!=""){
		var paginacargada = "Competencias_items.php";
		$("div#lItems").show().load(paginacargada,{
			idPrueba: f.fIdPrueba.value,
			idCompetencia: f.fIdCompetencia.value,
			idTipoCompetencia: f.fIdTipoCompetencia.value,
			idsItems: sItems,
			fAniadir:"1",
			MODO:"<?php echo constant('MNT_GUARDAASIGNADOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" 
		}).fadeIn("slow");
	}else{
		alert("Debe seleccionar al menos un item del seleccionable 'Items pendientes de asignar'.");
	}
}
function quitar(){
	var f=document.forms[0];
	var sItems = "";
	var i=0;
	ObjCombo=document.forms[0].elements["fItems"];
	
	for(i=0; i < ObjCombo.length; i++ ){
		if(ObjCombo.options[i].selected){
			sItems += "," + ObjCombo.options[i].value;
		}
	}
	if(f.fIdPrueba.value!="" && f.fIdCompetencia.value!="" && f.fIdTipoCompetencia.value && sItems!=""){
		var paginacargada = "Competencias_items.php";
		$("div#lItems").show().load(paginacargada,{
			idPrueba: f.fIdPrueba.value,
			idCompetencia: f.fIdCompetencia.value,
			idTipoCompetencia: f.fIdTipoCompetencia.value,
			idsItems: sItems,
			fQuitar:"1",
			MODO:"<?php echo constant('MNT_GUARDAASIGNADOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" 
		}).fadeIn("slow");
	}else{
		alert("Debe seleccionar al menos un item del seleccionable 'Items asignados'.");
	}
	
}

function limpiar(){
	var f=document.forms[0];
	var sItems = "";
	var i=0;

	if(f.fIdPrueba.value!="" && f.fIdCompetencia.value!="" && f.fIdTipoCompetencia.value){
		var paginacargada = "Competencias_items.php";
		$("div#lItems").show().load(paginacargada,{
			idPrueba: f.fIdPrueba.value,
			idCompetencia: f.fIdCompetencia.value,
			idTipoCompetencia: f.fIdTipoCompetencia.value,
			idsItems: sItems,
			fQuitar:"1",
			MODO:"<?php echo constant('MNT_GUARDAASIGNADOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" 
		}).fadeIn("slow");
	}else{
		alert("Debe seleccionar un bloque, una escala y una prueba para poder limpiar items asignados.");
	}
	
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
<body onload="_body_onload();cambiaIdTipoCompetencia();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');listaItems();" onunload="_body_onunload();">
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
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdTipoCompetencia"><?php echo constant("STR_TIPO_COMPETENCIA");?></label>&nbsp;</td>
					<td><?php $comboTIPOS_COMPETENCIAS->setNombre("fIdTipoCompetencia");?><?php echo $comboTIPOS_COMPETENCIAS->getHTMLCombo("1","obliga",$cEntidad->getIdTipoCompetencia()," onchange=\"javascript:cambiaIdTipoCompetencia()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdCompetencia"><?php echo constant("STR_COMPETENCIA");?></label>&nbsp;</td>
						<td><div id="comboIdCompetencia"><?php $comboCOMPETENCIAS->setNombre("fIdCompetencia");?><?php echo $comboCOMPETENCIAS->getHTMLComboNull("1","obliga",$cEntidad->getIdCompetencia(),"onchange=\"javascript:cambiaIdCompetencia()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdPrueba"><?php echo constant("STR_PRUEBA");?></label>&nbsp;</td>
					<td><?php $comboPRUEBAS->setNombre("fIdPrueba");?><?php echo $comboPRUEBAS->getHTMLCombo("1","obliga",$cEntidad->getIdPrueba()," onchange=\"javascript:listaItems()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdItem"><?php echo constant("STR_ITEM");?></label>&nbsp;</td>
					<td>
						<div id="lItems"></div>
					</td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
<!--	<table cellspacing="0" cellpadding="0" border="0">-->
<!--		<tr>-->
<!--			<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="javascript:document.forms[0].MODO.value=document.forms[0].ORIGEN.value;lon();document.forms[0].submit();" /></td>-->
<!--			<td><input type="submit" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ACEPTAR");?>" /></td>-->
<!--		</tr>-->
<!--	</table>-->
	</div>
</div>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="fCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" />
	<input type="hidden" name="LSTIdTipoCompetenciaHast" value="<?php echo (isset($_POST['LSTIdTipoCompetenciaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoCompetenciaHast']) : "";?>" />
	<input type="hidden" name="LSTIdTipoCompetencia" value="<?php echo (isset($_POST['LSTIdTipoCompetencia'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoCompetencia']) : "";?>" />
	<input type="hidden" name="LSTIdCompetenciaHast" value="<?php echo (isset($_POST['LSTIdCompetenciaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdCompetenciaHast']) : "";?>" />
	<input type="hidden" name="LSTIdCompetencia" value="<?php echo (isset($_POST['LSTIdCompetencia'])) ? $cUtilidades->validaXSS($_POST['LSTIdCompetencia']) : "";?>" />
	<input type="hidden" name="LSTIdPruebaHast" value="<?php echo (isset($_POST['LSTIdPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPruebaHast']) : "";?>" />
	<input type="hidden" name="LSTIdPrueba" value="<?php echo (isset($_POST['LSTIdPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTIdPrueba']) : "";?>" />
	<input type="hidden" name="LSTIdItemHast" value="<?php echo (isset($_POST['LSTIdItemHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdItemHast']) : "";?>" />
	<input type="hidden" name="LSTIdItem" value="<?php echo (isset($_POST['LSTIdItem'])) ? $cUtilidades->validaXSS($_POST['LSTIdItem']) : "";?>" />
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
	<input type="hidden" name="competencias_items_next_page" value="<?php echo (isset($_POST['competencias_items_next_page'])) ? $cUtilidades->validaXSS($_POST['competencias_items_next_page']) : "1";?>" />
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdTipoCompetencia(){
								var f= document.forms[0];
								$("#comboIdCompetencia").show().load("jQuery.php",{sPG:"combocompetencias",bBus:"0",multiple:"0",nLineas:"1",bObliga:"1",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdCompetencia",fJSProp:"cambiaIdCompetencia",fCodIdiomaIso2:f.fCodIdiomaIso2.value,fIdTipoCompetencia:f.fIdTipoCompetencia.value,vSelected:"<?php echo $cEntidad->getIdCompetencia();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdCompetencia(){	
								var f= document.forms[0];
								listaItems();							
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdPrueba(){
								var f= document.forms[0];
								$("#comboIdItem").show().load("jQuery.php",{sPG:"comboitems",bBus:"0",multiple:"0",nLineas:"1",bObliga:"1",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdItem",fJSProp:"cambiaIdItem",fCodIdiomaIso2:f.fCodIdiomaIso2.value,fIdPrueba:f.fIdPrueba.value,vSelected:"<?php echo $cEntidad->getIdItem();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdItem(){								
							}
							//]]>
						</script></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>
	<input type="hidden" name="competencias_items_next_page" value="<?php echo (isset($_POST['competencias_items_next_page'])) ? $cUtilidades->validaXSS($_POST['competencias_items_next_page']) : "1";?>" />