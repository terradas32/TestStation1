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
		f.LSTIdProceso.value=f.elements['LSTIdProceso'].value;
		f.LSTIdCandidato.value=f.elements['LSTIdCandidato'].value;
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
	msg +=vNumber("<?php echo constant("STR_ID_RECARGA");?>:", f.LSTIdRecarga.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_ID_RECARGA");?> <?php echo constant("STR_HASTA");?>:", f.LSTIdRecargaHast.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_EMPRESA");?>:", f.LSTIdEmpresa.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_PROCESO");?>:",f.elements['LSTIdProceso'].value, 11, false);
	msg +=vNumber("<?php echo constant("STR_CANDIDATO");?>:",f.elements['LSTIdCandidato'].value, 11, false);
	msg +=vString("<?php echo constant("STR_LOCALIZADOR");?>:",f.LSTLocalizador.value,255, false);
	msg +=vString("<?php echo constant("STR_DESCRIPCION");?>:",f.LSTDescripcion.value,255, false);
	msg +=vNumber("<?php echo constant("STR_IMP_BASE");?>:", f.LSTImpBase.value, 13, false);
	msg +=vNumber("<?php echo constant("STR_IMP_BASE");?> <?php echo constant("STR_HASTA");?>:", f.LSTImpBaseHast.value, 13, false);
	msg +=vNumber("<?php echo constant("STR_IMP_IMPUESTOS");?>:", f.LSTImpImpuestos.value, 13, false);
	msg +=vNumber("<?php echo constant("STR_IMP_IMPUESTOS");?> <?php echo constant("STR_HASTA");?>:", f.LSTImpImpuestosHast.value, 13, false);
	msg +=vNumber("<?php echo constant("STR_IMP_BASE_IMPUESTOS");?>:", f.LSTImpBaseImpuestos.value, 13, false);
	msg +=vNumber("<?php echo constant("STR_IMP_BASE_IMPUESTOS");?> <?php echo constant("STR_HASTA");?>:", f.LSTImpBaseImpuestosHast.value, 13, false);
	msg +=vEmail("<?php echo constant("STR_EMAIL");?>:",f.LSTEmail.value,255, false);
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.LSTNombre.value,255, false);
	msg +=vString("<?php echo constant("STR_APELLIDOS");?>:",f.LSTApellidos.value,255, false);
	msg +=vString("<?php echo constant("STR_DIRECCION");?>:",f.LSTDireccion.value,255, false);
	msg +=vString("<?php echo constant("STR_COD_POSTAL");?>:",f.LSTCodPostal.value,10, false);
	msg +=vString("<?php echo constant("STR_CIUDAD");?>:",f.LSTCiudad.value,255, false);
	msg +=vString("<?php echo constant("STR_TELEFONO1");?>:",f.LSTTelefono1.value,255, false);
	msg +=vString("<?php echo constant("STR_COD_ESTADO");?>:",f.LSTCodEstado.value,255, false);
	msg +=vString("<?php echo constant("STR_COD_AUTORIZACION");?>:",f.LSTCodAutorizacion.value,255, false);
	msg +=vString("<?php echo constant("STR_COD_ERROR");?>:",f.LSTCodError.value,255, false);
	msg +=vString("<?php echo constant("STR_DESC_ERROR");?>:",f.LSTDesError.value,16777215, false);
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
	
		<form name="form" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar('<?echo $_POST['MODO'];?>');">
<?php 
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
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdRecarga"><?php echo constant("STR_ID_RECARGA");?></label>&nbsp;</td>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><input type="text" id="LSTIdRecarga" name="LSTIdRecarga" value="<?php echo $cEntidad->getIdRecarga();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<td><input type="text" id="LSTIdRecargaHast" name="LSTIdRecargaHast" value="<?php echo $cEntidad->getIdRecargaHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdEmpresa"><?php echo constant("STR_EMPRESA");?></label>&nbsp;</td>
					<td><?php $comboEMPRESAS->setNombre("LSTIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLCombo("1","cajatexto",$cEntidad->getIdEmpresa()," onchange=\"javascript:cambiaIdEmpresa()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdProceso"><?php echo constant("STR_PROCESO");?></label>&nbsp;</td>
						<td><div id="comboIdProceso"><?php $comboPROCESOS->setNombre("LSTIdProceso");?><?php echo $comboPROCESOS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdProceso(),"onchange=\"javascript:cambiaIdProceso()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdCandidato"><?php echo constant("STR_CANDIDATO");?></label>&nbsp;</td>
						<td><div id="comboIdCandidato"><?php $comboCANDIDATOS->setNombre("LSTIdCandidato");?><?php echo $comboCANDIDATOS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdCandidato(),"onchange=\"javascript:cambiaIdCandidato()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTLocalizador"><?php echo constant("STR_LOCALIZADOR");?></label>&nbsp;</td>
					<td><input type="text" id="LSTLocalizador" name="LSTLocalizador" value="<?php echo $cEntidad->getLocalizador();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTDescripcion"><?php echo constant("STR_DESCRIPCION");?></label>&nbsp;</td>
					<td><input type="text" id="LSTDescripcion" name="LSTDescripcion" value="<?php echo $cEntidad->getDescripcion();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTImpBase"><?php echo constant("STR_IMP_BASE");?></label>&nbsp;</td>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><input type="text" id="LSTImpBase" name="LSTImpBase" value="<?php echo $cEntidad->getImpBase();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<td><input type="text" id="LSTImpBaseHast" name="LSTImpBaseHast" value="<?php echo $cEntidad->getImpBaseHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTImpImpuestos"><?php echo constant("STR_IMP_IMPUESTOS");?></label>&nbsp;</td>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><input type="text" id="LSTImpImpuestos" name="LSTImpImpuestos" value="<?php echo $cEntidad->getImpImpuestos();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<td><input type="text" id="LSTImpImpuestosHast" name="LSTImpImpuestosHast" value="<?php echo $cEntidad->getImpImpuestosHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTImpBaseImpuestos"><?php echo constant("STR_IMP_BASE_IMPUESTOS");?></label>&nbsp;</td>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><input type="text" id="LSTImpBaseImpuestos" name="LSTImpBaseImpuestos" value="<?php echo $cEntidad->getImpBaseImpuestos();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<td><input type="text" id="LSTImpBaseImpuestosHast" name="LSTImpBaseImpuestosHast" value="<?php echo $cEntidad->getImpBaseImpuestosHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTEmail"><?php echo constant("STR_EMAIL");?></label>&nbsp;</td>
					<td><input type="text" id="LSTEmail" name="LSTEmail" value="<?php echo $cEntidad->getEmail();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTNombre"><?php echo constant("STR_NOMBRE");?></label>&nbsp;</td>
					<td><input type="text" id="LSTNombre" name="LSTNombre" value="<?php echo $cEntidad->getNombre();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTApellidos"><?php echo constant("STR_APELLIDOS");?></label>&nbsp;</td>
					<td><input type="text" id="LSTApellidos" name="LSTApellidos" value="<?php echo $cEntidad->getApellidos();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTDireccion"><?php echo constant("STR_DIRECCION");?></label>&nbsp;</td>
					<td><input type="text" id="LSTDireccion" name="LSTDireccion" value="<?php echo $cEntidad->getDireccion();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTCodPostal"><?php echo constant("STR_COD_POSTAL");?></label>&nbsp;</td>
					<td><input type="text" id="LSTCodPostal" name="LSTCodPostal" value="<?php echo $cEntidad->getCodPostal();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTCiudad"><?php echo constant("STR_CIUDAD");?></label>&nbsp;</td>
					<td><input type="text" id="LSTCiudad" name="LSTCiudad" value="<?php echo $cEntidad->getCiudad();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTTelefono1"><?php echo constant("STR_TELEFONO1");?></label>&nbsp;</td>
					<td><input type="text" id="LSTTelefono1" name="LSTTelefono1" value="<?php echo $cEntidad->getTelefono1();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTCodEstado"><?php echo constant("STR_COD_ESTADO");?></label>&nbsp;</td>
					<td><input type="text" id="LSTCodEstado" name="LSTCodEstado" value="<?php echo $cEntidad->getCodEstado();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTCodAutorizacion"><?php echo constant("STR_COD_AUTORIZACION");?></label>&nbsp;</td>
					<td><input type="text" id="LSTCodAutorizacion" name="LSTCodAutorizacion" value="<?php echo $cEntidad->getCodAutorizacion();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTCodError"><?php echo constant("STR_COD_ERROR");?></label>&nbsp;</td>
					<td><input type="text" id="LSTCodError" name="LSTCodError" value="<?php echo $cEntidad->getCodError();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTDesError"><?php echo constant("STR_DESC_ERROR");?></label>&nbsp;</td>
					<td><input type="text" id="LSTDesError" name="LSTDesError" value="<?php echo $cEntidad->getDesError();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
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
							<option style='color:#000000;' value='idRecarga' <?php echo (!empty($sOrderBy) && $sOrderBy == 'idRecarga') ? "selected='selected'" : "";?>><?php echo constant("STR_ID_RECARGA");?></option>
							<option style='color:#000000;' value='localizador' <?php echo (!empty($sOrderBy) && $sOrderBy == 'localizador') ? "selected='selected'" : "";?>><?php echo constant("STR_LOCALIZADOR");?></option>
							<option style='color:#000000;' value='descripcion' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descripcion') ? "selected='selected'" : "";?>><?php echo constant("STR_DESCRIPCION");?></option>
							<option style='color:#000000;' value='impBase' <?php echo (!empty($sOrderBy) && $sOrderBy == 'impBase') ? "selected='selected'" : "";?>><?php echo constant("STR_IMP_BASE");?></option>
							<option style='color:#000000;' value='impImpuestos' <?php echo (!empty($sOrderBy) && $sOrderBy == 'impImpuestos') ? "selected='selected'" : "";?>><?php echo constant("STR_IMP_IMPUESTOS");?></option>
							<option style='color:#000000;' value='impBaseImpuestos' <?php echo (!empty($sOrderBy) && $sOrderBy == 'impBaseImpuestos') ? "selected='selected'" : "";?>><?php echo constant("STR_IMP_BASE_IMPUESTOS");?></option>
							<option style='color:#000000;' value='email' <?php echo (!empty($sOrderBy) && $sOrderBy == 'email') ? "selected='selected'" : "";?>><?php echo constant("STR_EMAIL");?></option>
							<option style='color:#000000;' value='nombre' <?php echo (!empty($sOrderBy) && $sOrderBy == 'nombre') ? "selected='selected'" : "";?>><?php echo constant("STR_NOMBRE");?></option>
							<option style='color:#000000;' value='apellidos' <?php echo (!empty($sOrderBy) && $sOrderBy == 'apellidos') ? "selected='selected'" : "";?>><?php echo constant("STR_APELLIDOS");?></option>
							<option style='color:#000000;' value='direccion' <?php echo (!empty($sOrderBy) && $sOrderBy == 'direccion') ? "selected='selected'" : "";?>><?php echo constant("STR_DIRECCION");?></option>
							<option style='color:#000000;' value='codPostal' <?php echo (!empty($sOrderBy) && $sOrderBy == 'codPostal') ? "selected='selected'" : "";?>><?php echo constant("STR_COD_POSTAL");?></option>
							<option style='color:#000000;' value='ciudad' <?php echo (!empty($sOrderBy) && $sOrderBy == 'ciudad') ? "selected='selected'" : "";?>><?php echo constant("STR_CIUDAD");?></option>
							<option style='color:#000000;' value='telefono1' <?php echo (!empty($sOrderBy) && $sOrderBy == 'telefono1') ? "selected='selected'" : "";?>><?php echo constant("STR_TELEFONO1");?></option>
							<option style='color:#000000;' value='codEstado' <?php echo (!empty($sOrderBy) && $sOrderBy == 'codEstado') ? "selected='selected'" : "";?>><?php echo constant("STR_COD_ESTADO");?></option>
							<option style='color:#000000;' value='codAutorizacion' <?php echo (!empty($sOrderBy) && $sOrderBy == 'codAutorizacion') ? "selected='selected'" : "";?>><?php echo constant("STR_COD_AUTORIZACION");?></option>
							<option style='color:#000000;' value='codError' <?php echo (!empty($sOrderBy) && $sOrderBy == 'codError') ? "selected='selected'" : "";?>><?php echo constant("STR_COD_ERROR");?></option>
							<option style='color:#000000;' value='desError' <?php echo (!empty($sOrderBy) && $sOrderBy == 'desError') ? "selected='selected'" : "";?>><?php echo constant("STR_DESC_ERROR");?></option>
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
	<input type="hidden" name="candidatos_pagos_tpv_next_page" value="1" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" />
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdEmpresa(){
								var f= document.forms[0];
								$("#comboIdProceso").show().load("jQuery.php",{sPG:"comboprocesos",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdProceso",fJSProp:"cambiaIdProceso",LSTIdEmpresa:f.LSTIdEmpresa.value,vSelected:"<?php echo $cEntidad->getIdProceso();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdCandidato").show().load("jQuery.php",{sPG:"combocandidatos",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdCandidato",fJSProp:"cambiaIdCandidato",LSTIdEmpresa:f.LSTIdEmpresa.value,LSTIdProceso:f.LSTIdProceso.value,vSelected:"<?php echo $cEntidad->getIdCandidato();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdProceso(){
								var f= document.forms[0];
								$("#comboIdCandidato").show().load("jQuery.php",{sPG:"combocandidatos",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdCandidato",fJSProp:"cambiaIdCandidato",LSTIdEmpresa:f.LSTIdEmpresa.value,LSTIdProceso:f.LSTIdProceso.value,vSelected:"<?php echo $cEntidad->getIdCandidato();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdCandidato(){								
							}
							//]]>
						</script></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>