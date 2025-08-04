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
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo constant("STR_EMPRESA");?>:",f.fIdEmpresa.value,11,true);
	msg +=vNumber("<?php echo constant("STR_PROCESO");?>:",f.fIdProceso.value,11,true);
	msg +=vNumber("<?php echo constant("STR_TIPO_CORREO");?>:",f.fIdTipoCorreo.value,11,true);
	msg +=vNumber("<?php echo constant("STR_CORREO");?>:",f.fIdCorreo.value,11,true);
	msg +=vNumber("<?php echo constant("STR_CANDIDATOS");?>:",f.fIdCandidato.value,11,true);
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
<body onload="_body_onload();cambiaIdEmpresa();cambiaIdProceso();setTimeout('cambiaIdTipoCorreo()',1000);block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0"  title="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" alt="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
	
		<form name="form" id="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>" onsubmit="return enviar('<?php echo $_POST["MODO"];?>');">
<?php 
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
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdEmpresa"><?php echo constant("STR_EMPRESA");?></label>&nbsp;</td>
					<td><?php $comboEMPRESAS->setNombre("fIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLComboMenu("1","obliga",$cEntidad->getIdEmpresa()," onchange=\"javascript:cambiaIdEmpresa()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdProceso"><?php echo constant("STR_PROCESO");?></label>&nbsp;</td>
					<td><div id="comboIdProceso"><?php $comboPROCESOS->setNombre("fIdProceso");?><?php echo $comboPROCESOS->getHTMLComboNull("1","obliga",$cEntidad->getIdProceso(),"onchange=\"javascript:cambiaIdProceso()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" class="negrob" valign="top"><?php echo constant("STR_TIPO_CORREO");?>&nbsp;</td>
					<td><?php $comboTIPOS_CORREOS->setNombre("fIdTipoCorreo");?><?php echo $comboTIPOS_CORREOS->getHTMLCombo("1","obliga",(!empty($_POST["fIdTipoCorreo"]) ? $_POST["fIdTipoCorreo"] : "")," onchange=\"javascript:cambiaIdTipoCorreo()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" class="negrob" valign="top"><?php echo constant("STR_CORREO");?>&nbsp;</td>
					<td><div id="comboIdCorreo"><?php $comboCORREOS_PROCESO->setNombre("fIdCorreo");?><?php echo $comboCORREOS_PROCESO->getHTMLComboNull("1","obliga",(!empty($_POST["fIdCorreo"]) ? $_POST["fIdCorreo"] : ""),"onchange=\"javascript:cambiaIdCorreo()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdCandidato"><?php echo constant("STR_CANDIDATOS");?></label>&nbsp;</td>
						<td><div id="comboIdCandidato"><?php $comboCANDIDATOS->setNombre("fIdCandidato");?><?php echo $comboCANDIDATOS->getHTMLComboNull("20","obliga",$cEntidad->getIdCandidato(),"onchange=\"javascript:cambiaIdCandidato()\" ","");?></div></td>
				</tr>
				<tr>
				<?php
				if ($cEntidad->getInformado() == ""){
					$cEntidad->setInformado("0");
				} 
				?>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fInformado"><?php echo constant("STR_INFORMADO");?></label>&nbsp;</td>
					<td>
						<input type="radio" onclick="javascript:cambiaIdProceso();" name="fInformado" id="fInformado1" value="1"  <?php echo ($cEntidad->getInformado() != "" && strtoupper($cEntidad->getInformado()) == "1") ? "checked=\"checked\"" : "";?> />
						&nbsp;
						<label for="fInformado1">Sí</label>&nbsp;
						<input type="radio" onclick="javascript:cambiaIdProceso();" name="fInformado" id="fInformado0" value="0"  <?php echo ($cEntidad->getInformado() != "" && strtoupper($cEntidad->getInformado()) == "0") ? "checked=\"checked\"" : "";?> />&nbsp;
						<label for="fInformado0">No</label>&nbsp;
						<input type="hidden" name="fInformadoHast" value="0" />
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
		</tr>
	</table>
	</div>
</div>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="fCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" />
	<input type="hidden" name="LSTIdCandidatoHast" value="<?php echo (isset($_POST['LSTIdCandidatoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidatoHast']) : "";?>" />
	<input type="hidden" name="LSTIdCandidato" value="<?php echo (isset($_POST['LSTIdCandidato'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidato']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresaHast" value="<?php echo (isset($_POST['LSTIdEmpresaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaHast']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresa" value="<?php echo (isset($_POST['LSTIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresa']) : "";?>" />
	<input type="hidden" name="LSTIdProcesoHast" value="<?php echo (isset($_POST['LSTIdProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdProcesoHast']) : "";?>" />
	<input type="hidden" name="LSTIdProceso" value="<?php echo (isset($_POST['LSTIdProceso'])) ? $cUtilidades->validaXSS($_POST['LSTIdProceso']) : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $cUtilidades->validaXSS($_POST['LSTNombre']) : "";?>" />
	<input type="hidden" name="LSTApellido1" value="<?php echo (isset($_POST['LSTApellido1'])) ? $cUtilidades->validaXSS($_POST['LSTApellido1']) : "";?>" />
	<input type="hidden" name="LSTApellido2" value="<?php echo (isset($_POST['LSTApellido2'])) ? $cUtilidades->validaXSS($_POST['LSTApellido2']) : "";?>" />
	<input type="hidden" name="LSTDni" value="<?php echo (isset($_POST['LSTDni'])) ? $cUtilidades->validaXSS($_POST['LSTDni']) : "";?>" />
	<input type="hidden" name="LSTMail" value="<?php echo (isset($_POST['LSTMail'])) ? $cUtilidades->validaXSS($_POST['LSTMail']) : "";?>" />
	<input type="hidden" name="LSTPassword" value="<?php echo (isset($_POST['LSTPassword'])) ? $cUtilidades->validaXSS($_POST['LSTPassword']) : "";?>" />
	<input type="hidden" name="LSTIdTratamientoHast" value="<?php echo (isset($_POST['LSTIdTratamientoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdTratamientoHast']) : "";?>" />
	<input type="hidden" name="LSTIdTratamiento" value="<?php echo (isset($_POST['LSTIdTratamiento'])) ? $cUtilidades->validaXSS($_POST['LSTIdTratamiento']) : "";?>" />
	<input type="hidden" name="LSTIdSexoHast" value="<?php echo (isset($_POST['LSTIdSexoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdSexoHast']) : "";?>" />
	<input type="hidden" name="LSTIdSexo" value="<?php echo (isset($_POST['LSTIdSexo'])) ? $cUtilidades->validaXSS($_POST['LSTIdSexo']) : "";?>" />
	<input type="hidden" name="LSTIdEdadHast" value="<?php echo (isset($_POST['LSTIdEdadHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEdadHast']) : "";?>" />
	<input type="hidden" name="LSTIdEdad" value="<?php echo (isset($_POST['LSTIdEdad'])) ? $cUtilidades->validaXSS($_POST['LSTIdEdad']) : "";?>" />
	<input type="hidden" name="LSTFechaNacimientoHast" value="<?php echo (isset($_POST['LSTFechaNacimientoHast'])) ? $cUtilidades->validaXSS($_POST['LSTFechaNacimientoHast']) : "";?>" />
	<input type="hidden" name="LSTFechaNacimiento" value="<?php echo (isset($_POST['LSTFechaNacimiento'])) ? $cUtilidades->validaXSS($_POST['LSTFechaNacimiento']) : "";?>" />
	<input type="hidden" name="LSTIdPais" value="<?php echo (isset($_POST['LSTIdPais'])) ? $cUtilidades->validaXSS($_POST['LSTIdPais']) : "";?>" />
	<input type="hidden" name="LSTIdProvincia" value="<?php echo (isset($_POST['LSTIdProvincia'])) ? $cUtilidades->validaXSS($_POST['LSTIdProvincia']) : "";?>" />
	<input type="hidden" name="LSTIdMunicipio" value="<?php echo (isset($_POST['LSTIdMunicipio'])) ? $cUtilidades->validaXSS($_POST['LSTIdMunicipio']) : "";?>" />
	<input type="hidden" name="LSTIdZona" value="<?php echo (isset($_POST['LSTIdZona'])) ? $cUtilidades->validaXSS($_POST['LSTIdZona']) : "";?>" />
	<input type="hidden" name="LSTDireccion" value="<?php echo (isset($_POST['LSTDireccion'])) ? $cUtilidades->validaXSS($_POST['LSTDireccion']) : "";?>" />
	<input type="hidden" name="LSTCodPostal" value="<?php echo (isset($_POST['LSTCodPostal'])) ? $cUtilidades->validaXSS($_POST['LSTCodPostal']) : "";?>" />
	<input type="hidden" name="LSTIdFormacionHast" value="<?php echo (isset($_POST['LSTIdFormacionHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdFormacionHast']) : "";?>" />
	<input type="hidden" name="LSTIdFormacion" value="<?php echo (isset($_POST['LSTIdFormacion'])) ? $cUtilidades->validaXSS($_POST['LSTIdFormacion']) : "";?>" />
	<input type="hidden" name="LSTIdNivelHast" value="<?php echo (isset($_POST['LSTIdNivelHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdNivelHast']) : "";?>" />
	<input type="hidden" name="LSTIdNivel" value="<?php echo (isset($_POST['LSTIdNivel'])) ? $cUtilidades->validaXSS($_POST['LSTIdNivel']) : "";?>" />
	<input type="hidden" name="LSTIdAreaHast" value="<?php echo (isset($_POST['LSTIdAreaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdAreaHast']) : "";?>" />
	<input type="hidden" name="LSTIdArea" value="<?php echo (isset($_POST['LSTIdArea'])) ? $cUtilidades->validaXSS($_POST['LSTIdArea']) : "";?>" />
	<input type="hidden" name="LSTTelefono" value="<?php echo (isset($_POST['LSTTelefono'])) ? $cUtilidades->validaXSS($_POST['LSTTelefono']) : "";?>" />
	<input type="hidden" name="LSTEstadoCivil" value="<?php echo (isset($_POST['LSTEstadoCivil'])) ? $cUtilidades->validaXSS($_POST['LSTEstadoCivil']) : "";?>" />
	<input type="hidden" name="LSTNacionalidad" value="<?php echo (isset($_POST['LSTNacionalidad'])) ? $cUtilidades->validaXSS($_POST['LSTNacionalidad']) : "";?>" />
	<input type="hidden" name="LSTInformadoHast" value="<?php echo (isset($_POST['LSTInformadoHast'])) ? $cUtilidades->validaXSS($_POST['LSTInformadoHast']) : "";?>" />
	<input type="hidden" name="LSTInformado" value="<?php echo (isset($_POST['LSTInformado'])) ? $cUtilidades->validaXSS($_POST['LSTInformado']) : "";?>" />
	<input type="hidden" name="LSTFinalizadoHast" value="<?php echo (isset($_POST['LSTFinalizadoHast'])) ? $cUtilidades->validaXSS($_POST['LSTFinalizadoHast']) : "";?>" />
	<input type="hidden" name="LSTFinalizado" value="<?php echo (isset($_POST['LSTFinalizado'])) ? $cUtilidades->validaXSS($_POST['LSTFinalizado']) : "";?>" />
	<input type="hidden" name="LSTFechaFinalizadoHast" value="<?php echo (isset($_POST['LSTFechaFinalizadoHast'])) ? $cUtilidades->validaXSS($_POST['LSTFechaFinalizadoHast']) : "";?>" />
	<input type="hidden" name="LSTFechaFinalizado" value="<?php echo (isset($_POST['LSTFechaFinalizado'])) ? $cUtilidades->validaXSS($_POST['LSTFechaFinalizado']) : "";?>" />
	<input type="hidden" name="LSTFecAltaHast" value="<?php echo (isset($_POST['LSTFecAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecAltaHast']) : "";?>" />
	<input type="hidden" name="LSTFecAlta" value="<?php echo (isset($_POST['LSTFecAlta'])) ? $cUtilidades->validaXSS($_POST['LSTFecAlta']) : "";?>" />
	<input type="hidden" name="LSTFecModHast" value="<?php echo (isset($_POST['LSTFecModHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecModHast']) : "";?>" />
	<input type="hidden" name="LSTFecMod" value="<?php echo (isset($_POST['LSTFecMod'])) ? $cUtilidades->validaXSS($_POST['LSTFecMod']) : "";?>" />
	<input type="hidden" name="LSTUsuAltaHast" value="<?php echo (isset($_POST['LSTUsuAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAltaHast']) : "";?>" />
	<input type="hidden" name="LSTUsuAlta" value="<?php echo (isset($_POST['LSTUsuAlta'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAlta']) : "";?>" />
	<input type="hidden" name="LSTUsuModHast" value="<?php echo (isset($_POST['LSTUsuModHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuModHast']) : "";?>" />
	<input type="hidden" name="LSTUsuMod" value="<?php echo (isset($_POST['LSTUsuMod'])) ? $cUtilidades->validaXSS($_POST['LSTUsuMod']) : "";?>" />
	<input type="hidden" name="LSTUltimoLoginHast" value="<?php echo (isset($_POST['LSTUltimoLoginHast'])) ? $cUtilidades->validaXSS($_POST['LSTUltimoLoginHast']) : "";?>" />
	<input type="hidden" name="LSTUltimoLogin" value="<?php echo (isset($_POST['LSTUltimoLogin'])) ? $cUtilidades->validaXSS($_POST['LSTUltimoLogin']) : "";?>" />
	<input type="hidden" name="LSTToken" value="<?php echo (isset($_POST['LSTToken'])) ? $cUtilidades->validaXSS($_POST['LSTToken']) : "";?>" />
	<input type="hidden" name="LSTUltimaAccHast" value="<?php echo (isset($_POST['LSTUltimaAccHast'])) ? $cUtilidades->validaXSS($_POST['LSTUltimaAccHast']) : "";?>" />
	<input type="hidden" name="LSTUltimaAcc" value="<?php echo (isset($_POST['LSTUltimaAcc'])) ? $cUtilidades->validaXSS($_POST['LSTUltimaAcc']) : "";?>" />
	<input type="hidden" name="LSTOrderBy" value="<?php echo (isset($_POST['LSTOrderBy'])) ? $cUtilidades->validaXSS($_POST['LSTOrderBy']) : "";?>" />
	<input type="hidden" name="LSTOrder" value="<?php echo (isset($_POST['LSTOrder'])) ? $cUtilidades->validaXSS($_POST['LSTOrder']) : "";?>" />
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $cUtilidades->validaXSS($_POST['LSTLineasPagina']) : constant("CNF_LINEAS_PAGINA");?>" />
	<input type="hidden" name="candidatos_next_page" value="<?php echo (isset($_POST['candidatos_next_page'])) ? $cUtilidades->validaXSS($_POST['candidatos_next_page']) : "1";?>" />
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdEmpresa(){
								var f= document.forms[0];
								$("#comboIdProceso").show().load("jQuery.php",{sPG:"comboprocesos",bBus:"0",multiple:"0",nLineas:"1",bObliga:"1",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdProceso",fJSProp:"cambiaIdProceso",fIdEmpresa:f.fIdEmpresa.value,vSelected:"<?php echo $cEntidad->getIdProceso();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdCandidato").show().load("jQuery.php",{sPG:"combocandidatosenvio",bBus:"0",multiple:"0",nLineas:"20",bObliga:"1",multiple:"1",fNombreCampo:"fIdCandidato",fJSProp:"cambiaIdCandidato",fIdEmpresa:f.fIdEmpresa.value,fIdProceso:f.fIdProceso.value,vSelected:"<?php echo $cEntidad->getIdCandidato();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");}).fadeIn("slow");
								cambiaIdTipoCorreo();
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdProceso(){
								var f= document.forms[0];
								aInformado = document.forms[0].fInformado;
								sId = "0";
								for(i=0; i < aInformado.length; i++ ){
									if (aInformado[i].type == "radio" && aInformado[i].name == "fInformado"){
										if (aInformado[i].checked)
										{
											sId = aInformado[i].value;
										}
									}
								}
								f.fInformadoHast.value=sId;
								if (f.fInformadoHast.value != ""){
									setTimeout(function(){
										$("#comboIdCandidato").show().load("jQuery.php",{sPG:"combocandidatosenvio",bBus:"0",multiple:"0",nLineas:"20",bObliga:"1",multiple:"1",fNombreCampo:"fIdCandidato",fJSProp:"cambiaIdCandidato",fIdEmpresa:f.fIdEmpresa.value,fIdProceso:f.fIdProceso.value,fInformado:f.fInformadoHast.value,vSelected:"<?php echo $cEntidad->getIdCandidato();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){cambiaIdTipoCorreo();}).fadeIn("slow");
									},2000)
								}
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdTipoCorreo(){
								var f= document.forms[0];
								if (f.fIdTipoCorreo.value ==""){
									f.fIdTipoCorreo.value = 1;
								}
								$("#comboIdCorreo").show().load("jQuery.php",{sPG:"combocorreos_proceso",bBus:"0",multiple:"0",nLineas:"1",bObliga:"1",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdCorreo",fJSProp:"cambiaIdCorreo",fIdEmpresa:f.fIdEmpresa.value,fIdProceso:f.fIdProceso.value,fIdTipoCorreo:f.fIdTipoCorreo.value,vSelected:"",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdCorreo(){								
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
	<input type="hidden" name="candidatos_next_page" value="<?php echo (isset($_POST['candidatos_next_page'])) ? $cUtilidades->validaXSS($_POST['candidatos_next_page']) : "1";?>" />