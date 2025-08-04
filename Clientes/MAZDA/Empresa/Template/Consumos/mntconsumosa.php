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
		f.fIdProceso.value=f.elements['fIdProceso'].value;
		f.fIdCandidato.value=f.elements['fIdCandidato'].value;
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo constant("STR_EMPRESA");?>:",f.fIdEmpresa.value,11,true);
	msg +=vNumber("<?php echo constant("STR_PROCESO");?>:",f.elements['fIdProceso'].value,11,true);
	msg +=vNumber("<?php echo constant("STR_CANDIDATO");?>:",f.elements['fIdCandidato'].value,11,true);
	msg +=vString("<?php echo constant("STR_IDIOMA_PRUEBA");?>:",f.fCodIdiomaIso2.value,2,true);
	msg +=vNumber("<?php echo constant("STR_PRUEBA");?>:",f.fIdPrueba.value,11,true);
	msg +=vString("<?php echo constant("STR_IDIOMA_INFORME");?>:",f.fCodIdiomaInforme.value,2,true);
	msg +=vNumber("<?php echo constant("STR_INFORME");?>:",f.fIdTipoInforme.value,11,true);
	msg +=vNumber("<?php echo constant("STR_BAREMO");?>:",f.fIdBaremo.value,11,true);
	msg +=vString("<?php echo constant("STR_NOMBRE_EMPRESA");?>:",f.fNomEmpresa.value,255,true);
	msg +=vString("<?php echo constant("STR_NOMBRE_PROCESO");?>:",f.fNomProceso.value,255,true);
	msg +=vString("<?php echo constant("STR_NOMBRE_CANDIDATO");?>:",f.fNomCandidato.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO1");?>:",f.fApellido1.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO2");?>:",f.fApellido2.value,255,true);
	msg +=vString("<?php echo constant("STR_DNI");?>:",f.fDni.value,255,false);
	msg +=vString("<?php echo constant("STR_MAIL");?>:",f.fMail.value,255,true);
	msg +=vString("<?php echo constant("STR_PRUEBA");?>:",f.fNomPrueba.value,255,true);
	msg +=vString("<?php echo constant("STR_INFORME");?>:",f.fNomInforme.value,255,true);
	msg +=vString("<?php echo constant("STR_BAREMO");?>:",f.fNomBaremo.value,255,true);
	msg +=vString("<?php echo constant("STR_CONCEPTO");?>:",f.fConcepto.value,255,true);
	msg +=vNumber("<?php echo constant("STR_UNIDADES");?>:",f.fUnidades.value,11,true);
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
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNomEmpresa"><?php echo constant("STR_NOMBRE_EMPRESA");?></label>&nbsp;</td>
					<td><input type="text" disabled="disabled" id="fNomEmpresa" name="fNomEmpresa" value="<?php echo $cEntidad->getNomEmpresa();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNomProceso"><?php echo constant("STR_NOMBRE_PROCESO");?></label>&nbsp;</td>
					<td><input type="text" disabled="disabled" id="fNomProceso" name="fNomProceso" value="<?php echo $cEntidad->getNomProceso();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNomPrueba"><?php echo constant("STR_PRUEBA");?></label>&nbsp;</td>
					<td><input type="text" disabled="disabled" id="fNomPrueba" name="fNomPrueba" value="<?php echo $cEntidad->getNomPrueba();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fCodIdiomaIso2"><?php echo constant("STR_IDIOMA_PRUEBA");?></label>&nbsp;</td>
					<td><?php $comboWI_IDIOMAS->setNombre("fCodIdiomaIso2");?><?php echo $comboWI_IDIOMAS->getHTMLCombo("1","obliga",$cEntidad->getCodIdiomaIso2(),"disabled=\"disabled\" ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNomCandidato"><?php echo constant("STR_NOMBRE_CANDIDATO");?></label>&nbsp;</td>
					<td><input type="text" disabled="disabled" id="fNomCandidato" name="fNomCandidato" value="<?php echo $cEntidad->getNomCandidato();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fApellido1"><?php echo constant("STR_APELLIDO1");?></label>&nbsp;</td>
					<td><input type="text" disabled="disabled" id="fApellido1" name="fApellido1" value="<?php echo $cEntidad->getApellido1();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fApellido2"><?php echo constant("STR_APELLIDO2");?></label>&nbsp;</td>
					<td><input type="text" disabled="disabled" id="fApellido2" name="fApellido2" value="<?php echo $cEntidad->getApellido2();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fDni"><?php echo constant("STR_DNI");?></label>&nbsp;</td>
					<td><input type="text" disabled="disabled" id="fDni" name="fDni" value="<?php echo $cEntidad->getDni();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fMail"><?php echo constant("STR_MAIL");?></label>&nbsp;</td>
					<td><input type="text" disabled="disabled" id="fMail" name="fMail" value="<?php echo $cEntidad->getMail();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNomInforme"><?php echo constant("STR_INFORME");?></label>&nbsp;</td>
					<td><input type="text" disabled="disabled" id="fNomInforme" name="fNomInforme" value="<?php echo $cEntidad->getNomInforme();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fCodIdiomaInforme"><?php echo constant("STR_IDIOMA_INFORME");?></label>&nbsp;</td>
					<td><?php $comboWI_IDIOMAS->setNombre("fCodIdiomaInforme");?><?php echo $comboWI_IDIOMAS->getHTMLCombo("1","obliga",$cEntidad->getCodIdiomaInforme(),"disabled=\"disabled\" ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdTipoInforme"><?php echo constant("STR_INFORME");?></label>&nbsp;</td>
					<td><?php $comboTIPOS_INFORMES->setNombre("fIdTipoInforme");?><?php echo $comboTIPOS_INFORMES->getHTMLCombo("1","obliga",$cEntidad->getIdTipoInforme(),"disabled=\"disabled\" ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNomBaremo"><?php echo constant("STR_BAREMO");?></label>&nbsp;</td>
					<td><input type="text" disabled="disabled" id="fNomBaremo" name="fNomBaremo" value="<?php echo $cEntidad->getNomBaremo();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fConcepto"><?php echo constant("STR_CONCEPTO");?></label>&nbsp;</td>
					<td><input type="text" disabled="disabled" id="fConcepto" name="fConcepto" value="<?php echo $cEntidad->getConcepto();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fUnidades"><?php echo constant("STR_UNIDADES");?></label>&nbsp;</td>
					<td><input type="text" disabled="disabled" id="fUnidades" name="fUnidades" value="<?php echo $cEntidad->getUnidades();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="javascript:document.forms[0].MODO.value=document.forms[0].ORIGEN.value;document.forms[0].submit();" /></td>
		</tr>
	</table>
	</div>
</div>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="LSTIdEmpresaHast" value="<?php echo (isset($_POST['LSTIdEmpresaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaHast']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresa" value="<?php echo (isset($_POST['LSTIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresa']) : "";?>" />
	<input type="hidden" name="LSTIdProcesoHast" value="<?php echo (isset($_POST['LSTIdProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdProcesoHast']) : "";?>" />
	<input type="hidden" name="LSTIdProceso" value="<?php echo (isset($_POST['LSTIdProceso'])) ? $cUtilidades->validaXSS($_POST['LSTIdProceso']) : "";?>" />
	<input type="hidden" name="LSTIdCandidatoHast" value="<?php echo (isset($_POST['LSTIdCandidatoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidatoHast']) : "";?>" />
	<input type="hidden" name="LSTIdCandidato" value="<?php echo (isset($_POST['LSTIdCandidato'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidato']) : "";?>" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo (isset($_POST['LSTCodIdiomaIso2'])) ? $cUtilidades->validaXSS($_POST['LSTCodIdiomaIso2']) : "";?>" />
	<input type="hidden" name="LSTIdPruebaHast" value="<?php echo (isset($_POST['LSTIdPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPruebaHast']) : "";?>" />
	<input type="hidden" name="LSTIdPrueba" value="<?php echo (isset($_POST['LSTIdPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTIdPrueba']) : "";?>" />
	<input type="hidden" name="LSTCodIdiomaInforme" value="<?php echo (isset($_POST['LSTCodIdiomaInforme'])) ? $cUtilidades->validaXSS($_POST['LSTCodIdiomaInforme']) : "";?>" />
	<input type="hidden" name="LSTIdTipoInformeHast" value="<?php echo (isset($_POST['LSTIdTipoInformeHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoInformeHast']) : "";?>" />
	<input type="hidden" name="LSTIdTipoInforme" value="<?php echo (isset($_POST['LSTIdTipoInforme'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoInforme']) : "";?>" />
	<input type="hidden" name="LSTIdBaremoHast" value="<?php echo (isset($_POST['LSTIdBaremoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdBaremoHast']) : "";?>" />
	<input type="hidden" name="LSTIdBaremo" value="<?php echo (isset($_POST['LSTIdBaremo'])) ? $cUtilidades->validaXSS($_POST['LSTIdBaremo']) : "";?>" />
	<input type="hidden" name="LSTNomEmpresa" value="<?php echo (isset($_POST['LSTNomEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTNomEmpresa']) : "";?>" />
	<input type="hidden" name="LSTNomProceso" value="<?php echo (isset($_POST['LSTNomProceso'])) ? $cUtilidades->validaXSS($_POST['LSTNomProceso']) : "";?>" />
	<input type="hidden" name="LSTNomCandidato" value="<?php echo (isset($_POST['LSTNomCandidato'])) ? $cUtilidades->validaXSS($_POST['LSTNomCandidato']) : "";?>" />
	<input type="hidden" name="LSTApellido1" value="<?php echo (isset($_POST['LSTApellido1'])) ? $cUtilidades->validaXSS($_POST['LSTApellido1']) : "";?>" />
	<input type="hidden" name="LSTApellido2" value="<?php echo (isset($_POST['LSTApellido2'])) ? $cUtilidades->validaXSS($_POST['LSTApellido2']) : "";?>" />
	<input type="hidden" name="LSTDni" value="<?php echo (isset($_POST['LSTDni'])) ? $cUtilidades->validaXSS($_POST['LSTDni']) : "";?>" />
	<input type="hidden" name="LSTMail" value="<?php echo (isset($_POST['LSTMail'])) ? $cUtilidades->validaXSS($_POST['LSTMail']) : "";?>" />
	<input type="hidden" name="LSTNomPrueba" value="<?php echo (isset($_POST['LSTNomPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTNomPrueba']) : "";?>" />
	<input type="hidden" name="LSTNomInforme" value="<?php echo (isset($_POST['LSTNomInforme'])) ? $cUtilidades->validaXSS($_POST['LSTNomInforme']) : "";?>" />
	<input type="hidden" name="LSTNomBaremo" value="<?php echo (isset($_POST['LSTNomBaremo'])) ? $cUtilidades->validaXSS($_POST['LSTNomBaremo']) : "";?>" />
	<input type="hidden" name="LSTConcepto" value="<?php echo (isset($_POST['LSTConcepto'])) ? $cUtilidades->validaXSS($_POST['LSTConcepto']) : "";?>" />
	<input type="hidden" name="LSTUnidadesHast" value="<?php echo (isset($_POST['LSTUnidadesHast'])) ? $cUtilidades->validaXSS($_POST['LSTUnidadesHast']) : "";?>" />
	<input type="hidden" name="LSTUnidades" value="<?php echo (isset($_POST['LSTUnidades'])) ? $cUtilidades->validaXSS($_POST['LSTUnidades']) : "";?>" />
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
	<input type="hidden" name="consumos_next_page" value="<?php echo (isset($_POST['consumos_next_page'])) ? $cUtilidades->validaXSS($_POST['consumos_next_page']) : "1";?>" />
</div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>
	<input type="hidden" name="consumos_next_page" value="<?php echo (isset($_POST['consumos_next_page'])) ? $cUtilidades->validaXSS($_POST['consumos_next_page']) : "1";?>" />