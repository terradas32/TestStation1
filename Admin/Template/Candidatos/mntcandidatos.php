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
		f.LSTFechaNacimiento.value=cFechaFormat(f.LSTFechaNacimiento.value);
		f.LSTFechaNacimientoHast.value=cFechaFormat(f.LSTFechaNacimientoHast.value);
		f.LSTIdProvincia.value=f.elements['LSTIdProvincia'].value;
		f.LSTIdMunicipio.value=f.elements['LSTIdMunicipio'].value;
		f.LSTIdZona.value=f.elements['LSTIdZona'].value;
		aInformado = document.forms[0].LSTInformado;
		sId = "";
		for(i=0; i < aInformado.length; i++ ){
			if (aInformado[i].type == "radio" && aInformado[i].name == "LSTInformado"){
				if (aInformado[i].checked)
				{
					sId = aInformado[i].value;
				}
			}
		}
		f.LSTInformadoHast.value=sId;
		aFinalizado = document.forms[0].LSTFinalizado;
		sId = "";
		for(i=0; i < aFinalizado.length; i++ ){
			if (aFinalizado[i].type == "radio" && aFinalizado[i].name == "LSTFinalizado"){
				if (aFinalizado[i].checked)
				{
					sId = aFinalizado[i].value;
				}
			}
		}
		f.LSTFinalizadoHast.value=sId;
		f.LSTFechaFinalizado.value=cFechaFormat(f.LSTFechaFinalizado.value);
		f.LSTFechaFinalizadoHast.value=cFechaFormat(f.LSTFechaFinalizadoHast.value);
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
	msg +=vNumber("<?php echo constant("STR_ID_CANDIDATO");?>:", f.LSTIdCandidato.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_ID_CANDIDATO");?> <?php echo constant("STR_HASTA");?>:", f.LSTIdCandidatoHast.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_EMPRESA");?>:", f.LSTIdEmpresa.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_PROCESO");?>:",f.elements['LSTIdProceso'].value, 11, false);
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.LSTNombre.value,255, false);
	msg +=vString("<?php echo constant("STR_APELLIDO_1");?>:",f.LSTApellido1.value,255, false);
	msg +=vString("<?php echo constant("STR_APELLIDO_2");?>:",f.LSTApellido2.value,255, false);
if (!f.LSTDniOK.checked){
	msg +=vNif("<?php echo constant("STR_NIF");?>:",f.LSTDni.value,255, false);
}
	msg +=vEmail("<?php echo constant("STR_EMAIL");?>:",f.LSTMail.value,255, false);
	msg +=vNumber("<?php echo constant("STR_TRATAMIENTO");?>:", f.LSTIdTratamiento.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_SEXO");?>:", f.LSTIdSexo.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_EDAD");?>:", f.LSTIdEdad.value, 11, false);
	msg +=vDate("Fecha de Nacimiento:",f.LSTFechaNacimiento.value,10,false);
	msg +=vDate("Fecha de Nacimiento <?php echo constant("STR_HASTA");?>:",f.LSTFechaNacimientoHast.value,10,false);
	msg +=vString("<?php echo constant("STR_PAIS");?>:",f.LSTIdPais.value,3, false);
	msg +=vString("<?php echo constant("STR_PROVINCIA");?>:",f.elements['LSTIdProvincia'].value,2, false);
	msg +=vString("<?php echo constant("STR_MUNICIPIO");?>:",f.elements['LSTIdMunicipio'].value,5, false);
	msg +=vString("<?php echo constant("STR_ZONA");?>:",f.elements['LSTIdZona'].value,255, false);
	msg +=vString("<?php echo constant("STR_DIRECCION");?>:",f.LSTDireccion.value,255, false);
	msg +=vString("<?php echo constant("STR_CODIGO_POSTAL");?>:",f.LSTCodPostal.value,255, false);
	msg +=vNumber("<?php echo constant("STR_FORMACION");?>:", f.LSTIdFormacion.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_NIVEL");?>:", f.LSTIdNivel.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_AREA");?>:", f.LSTIdArea.value, 11, false);
	msg +=vString("<?php echo constant("STR_TELEFONO");?>:",f.LSTTelefono.value,255, false);
	msg +=vString("<?php echo constant("STR_ESTADO_CIVIL");?>:",f.LSTEstadoCivil.value,255, false);
	msg +=vString("<?php echo constant("STR_NACIONALIDAD");?>:",f.LSTNacionalidad.value,255, false);
	aInformado = document.forms[0].LSTInformado;
	sId = "";
	for(i=0; i < aInformado.length; i++ ){
		if (aInformado[i].type == "radio" && aInformado[i].name == "LSTInformado"){
			if (aInformado[i].checked)
			{
				sId = aInformado[i].value;
			}
		}
	}
	msg +=vNumber("<?php echo constant("STR_INFORMADO");?>:",sId,11,false);
	aFinalizado = document.forms[0].LSTFinalizado;
	sId = "";
	for(i=0; i < aFinalizado.length; i++ ){
		if (aFinalizado[i].type == "radio" && aFinalizado[i].name == "LSTFinalizado"){
			if (aFinalizado[i].checked)
			{
				sId = aFinalizado[i].value;
			}
		}
	}
	msg +=vNumber("<?php echo constant("STR_FINALIZADO");?>:",sId,11,false);
	msg +=vDate("Fecha de Finalizado:",f.LSTFechaFinalizado.value,10,false);
	msg +=vDate("Fecha de Finalizado <?php echo constant("STR_HASTA");?>:",f.LSTFechaFinalizadoHast.value,10,false);
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
<body onload="_body_onload();cambiaIdEmpresa();cambiaIdPais();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
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
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdCandidato"><?php echo constant("STR_ID_CANDIDATO");?></label>&nbsp;</td>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><input type="text" id="LSTIdCandidato" name="LSTIdCandidato" value="<?php echo $cEntidad->getIdCandidato();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<td><input type="text" id="LSTIdCandidatoHast" name="LSTIdCandidatoHast" value="<?php echo $cEntidad->getIdCandidatoHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdEmpresa"><?php echo constant("STR_EMPRESA");?></label>&nbsp;</td>
					<td><?php $comboEMPRESAS->setNombre("LSTIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLComboMenu("1","cajatexto",$cEntidad->getIdEmpresa()," onchange=\"javascript:cambiaIdEmpresa()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdProceso"><?php echo constant("STR_PROCESO");?></label>&nbsp;</td>
						<td><div id="comboIdProceso"><?php $comboPROCESOS->setNombre("LSTIdProceso");?><?php echo $comboPROCESOS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdProceso(),"onchange=\"javascript:cambiaIdProceso()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTNombre"><?php echo constant("STR_NOMBRE");?></label>&nbsp;</td>
					<td><input type="text" id="LSTNombre" name="LSTNombre" value="<?php echo $cEntidad->getNombre();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTApellido1"><?php echo constant("STR_APELLIDO_1");?></label>&nbsp;</td>
					<td><input type="text" id="LSTApellido1" name="LSTApellido1" value="<?php echo $cEntidad->getApellido1();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTApellido2"><?php echo constant("STR_APELLIDO_2");?></label>&nbsp;</td>
					<td><input type="text" id="LSTApellido2" name="LSTApellido2" value="<?php echo $cEntidad->getApellido2();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTDni"><?php echo constant("STR_NIF");?></label>&nbsp;</td>
					<td><input type="text" id="LSTDni" name="LSTDni" value="<?php echo $cEntidad->getDni();?>" class="cajatexto"  style='width:75%;' onchange="javascript:trim(this);" /><input type="checkbox" name="LSTDniOK" /><?php echo constant("STR_VERIFICADO");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTMail"><?php echo constant("STR_EMAIL");?></label>&nbsp;</td>
					<td><input type="text" id="LSTMail" name="LSTMail" value="<?php echo $cEntidad->getMail();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdTratamiento"><?php echo constant("STR_TRATAMIENTO");?></label>&nbsp;</td>
					<td><?php $comboTRATAMIENTOS->setNombre("LSTIdTratamiento");?><?php echo $comboTRATAMIENTOS->getHTMLCombo("1","cajatexto",$cEntidad->getIdTratamiento()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdSexo"><?php echo constant("STR_SEXO");?></label>&nbsp;</td>
					<td><?php $comboSEXOS->setNombre("LSTIdSexo");?><?php echo $comboSEXOS->getHTMLCombo("1","cajatexto",$cEntidad->getIdSexo()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdEdad"><?php echo constant("STR_EDAD");?></label>&nbsp;</td>
					<td><?php $comboEDADES->setNombre("LSTIdEdad");?><?php echo $comboEDADES->getHTMLCombo("1","cajatexto",$cEntidad->getIdEdad()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFechaNacimiento"><?php echo constant("STR_FECHA_DE_NACIMIENTO");?></label>&nbsp;</td>
					<?php if ($cEntidad->getFechaNacimiento() != "" && $cEntidad->getFechaNacimiento() != "0000-00-00" && $cEntidad->getFechaNacimiento() != "0000-00-00 00:00:00"){
						$cEntidad->setFechaNacimiento($conn->UserDate($cEntidad->getFechaNacimiento(),constant("USR_FECHA"),false));
					}else{
						$cEntidad->setFechaNacimiento("");
					}
					?>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFechaNacimiento','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFechaNacimiento" name="LSTFechaNacimiento" value="<?php echo $cEntidad->getFechaNacimiento();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<?php if ($cEntidad->getFechaNacimientoHast() != "" && $cEntidad->getFechaNacimientoHast() != "0000-00-00" && $cEntidad->getFechaNacimientoHast() != "0000-00-00 00:00:00"){
								$cEntidad->setFechaNacimientoHast($conn->UserDate($cEntidad->getFechaNacimientoHast(),constant("USR_FECHA"),false));
								}else{
								$cEntidad->setFechaNacimiento("");
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFechaNacimientoHast','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFechaNacimientoHast" name="LSTFechaNacimientoHast" value="<?php echo $cEntidad->getFechaNacimientoHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdPais"><?php echo constant("STR_PAIS");?></label>&nbsp;</td>
					<td><?php $comboWI_PAISES->setNombre("LSTIdPais");?><?php echo $comboWI_PAISES->getHTMLCombo("1","cajatexto",$cEntidad->getIdPais()," onchange=\"javascript:cambiaIdPais()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdProvincia"><?php echo constant("STR_PROVINCIA");?></label>&nbsp;</td>
						<td><div id="comboIdProvincia"><?php $comboWI_PROVINCIAS->setNombre("LSTIdProvincia");?><?php echo $comboWI_PROVINCIAS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdProvincia(),"onchange=\"javascript:cambiaIdProvincia()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdMunicipio"><?php echo constant("STR_MUNICIPIO");?></label>&nbsp;</td>
						<td><div id="comboIdMunicipio"><?php $comboWI_MUNICIPIOS->setNombre("LSTIdMunicipio");?><?php echo $comboWI_MUNICIPIOS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdMunicipio(),"onchange=\"javascript:cambiaIdMunicipio()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdZona"><?php echo constant("STR_ZONA");?></label>&nbsp;</td>
						<td><div id="comboIdZona"><?php $comboWI_ZONAS->setNombre("LSTIdZona");?><?php echo $comboWI_ZONAS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdZona(),"onchange=\"javascript:cambiaIdZona()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTDireccion"><?php echo constant("STR_DIRECCION");?></label>&nbsp;</td>
					<td><input type="text" id="LSTDireccion" name="LSTDireccion" value="<?php echo $cEntidad->getDireccion();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTCodPostal"><?php echo constant("STR_CODIGO_POSTAL");?></label>&nbsp;</td>
					<td><input type="text" id="LSTCodPostal" name="LSTCodPostal" value="<?php echo $cEntidad->getCodPostal();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdFormacion"><?php echo constant("STR_FORMACION");?></label>&nbsp;</td>
					<td><?php $comboFORMACIONES->setNombre("LSTIdFormacion");?><?php echo $comboFORMACIONES->getHTMLCombo("1","cajatexto",$cEntidad->getIdFormacion()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdNivel"><?php echo constant("STR_NIVEL");?></label>&nbsp;</td>
					<td><?php $comboNIVELESJERARQUICOS->setNombre("LSTIdNivel");?><?php echo $comboNIVELESJERARQUICOS->getHTMLCombo("1","cajatexto",$cEntidad->getIdNivel()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdArea"><?php echo constant("STR_AREA");?></label>&nbsp;</td>
					<td><?php $comboAREAS->setNombre("LSTIdArea");?><?php echo $comboAREAS->getHTMLCombo("1","cajatexto",$cEntidad->getIdArea()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTTelefono"><?php echo constant("STR_TELEFONO");?></label>&nbsp;</td>
					<td><input type="text" id="LSTTelefono" name="LSTTelefono" value="<?php echo $cEntidad->getTelefono();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTEstadoCivil"><?php echo constant("STR_ESTADO_CIVIL");?></label>&nbsp;</td>
					<td><input type="text" id="LSTEstadoCivil" name="LSTEstadoCivil" value="<?php echo $cEntidad->getEstadoCivil();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTNacionalidad"><?php echo constant("STR_NACIONALIDAD");?></label>&nbsp;</td>
					<td><input type="text" id="LSTNacionalidad" name="LSTNacionalidad" value="<?php echo $cEntidad->getNacionalidad();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTInformado"><?php echo constant("STR_INFORMADO");?></label>&nbsp;</td>
					<td><input type="radio" name="LSTInformado" id="LSTInformado1" value="1"  <?php echo ($cEntidad->getInformado() != "" && strtoupper($cEntidad->getInformado()) == "1") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTInformado1">Sí</label>&nbsp;<input type="radio" name="LSTInformado" id="LSTInformado0" value="0"  <?php echo ($cEntidad->getInformado() != "" && strtoupper($cEntidad->getInformado()) == "0") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTInformado0">No</label>&nbsp;<input type="hidden" name="LSTInformadoHast" value="<?php echo $cEntidad->getInformado();?>" /></td>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFinalizado"><?php echo constant("STR_FINALIZADO");?></label>&nbsp;</td>
					<td><input type="radio" name="LSTFinalizado" id="LSTFinalizado1" value="1"  <?php echo ($cEntidad->getFinalizado() != "" && strtoupper($cEntidad->getFinalizado()) == "1") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTFinalizado1">Sí</label>&nbsp;<input type="radio" name="LSTFinalizado" id="LSTFinalizado0" value="0"  <?php echo ($cEntidad->getFinalizado() != "" && strtoupper($cEntidad->getFinalizado()) == "0") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="LSTFinalizado0">No</label>&nbsp;<input type="hidden" name="LSTFinalizadoHast" value="<?php echo $cEntidad->getFinalizado();?>" /></td>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFechaFinalizado"><?php echo constant("STR_FECHA_DE_FINALIZADO");?></label>&nbsp;</td>
					<?php if ($cEntidad->getFechaFinalizado() != "" && $cEntidad->getFechaFinalizado() != "0000-00-00" && $cEntidad->getFechaFinalizado() != "0000-00-00 00:00:00"){
						$cEntidad->setFechaFinalizado($conn->UserDate($cEntidad->getFechaFinalizado(),constant("USR_FECHA"),false));
					}else{
						$cEntidad->setFechaFinalizado("");
					}
					?>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFechaFinalizado','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFechaFinalizado" name="LSTFechaFinalizado" value="<?php echo $cEntidad->getFechaFinalizado();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<?php if ($cEntidad->getFechaFinalizadoHast() != "" && $cEntidad->getFechaFinalizadoHast() != "0000-00-00" && $cEntidad->getFechaFinalizadoHast() != "0000-00-00 00:00:00"){
								$cEntidad->setFechaFinalizadoHast($conn->UserDate($cEntidad->getFechaFinalizadoHast(),constant("USR_FECHA"),false));
								}else{
								$cEntidad->setFechaFinalizado("");
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFechaFinalizadoHast','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFechaFinalizadoHast" name="LSTFechaFinalizadoHast" value="<?php echo $cEntidad->getFechaFinalizadoHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
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
							<option style='color:#000000;' value='idCandidato' <?php echo (!empty($sOrderBy) && $sOrderBy == 'idCandidato') ? "selected='selected'" : "";?>><?php echo constant("STR_ID_CANDIDATO");?></option>
							<option style='color:#000000;' value='nombre' <?php echo (!empty($sOrderBy) && $sOrderBy == 'nombre') ? "selected='selected'" : "";?>><?php echo constant("STR_NOMBRE");?></option>
							<option style='color:#000000;' value='apellido1' <?php echo (!empty($sOrderBy) && $sOrderBy == 'apellido1') ? "selected='selected'" : "";?>><?php echo constant("STR_APELLIDO_1");?></option>
							<option style='color:#000000;' value='apellido2' <?php echo (!empty($sOrderBy) && $sOrderBy == 'apellido2') ? "selected='selected'" : "";?>><?php echo constant("STR_APELLIDO_2");?></option>
							<option style='color:#000000;' value='dni' <?php echo (!empty($sOrderBy) && $sOrderBy == 'dni') ? "selected='selected'" : "";?>><?php echo constant("STR_NIF");?></option>
							<option style='color:#000000;' value='mail' <?php echo (!empty($sOrderBy) && $sOrderBy == 'mail') ? "selected='selected'" : "";?>><?php echo constant("STR_EMAIL");?></option>
							<option style='color:#000000;' value='fechaNacimiento' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fechaNacimiento') ? "selected='selected'" : "";?>><?php echo constant("STR_FECHA_DE_NACIMIENTO");?></option>
							<option style='color:#000000;' value='direccion' <?php echo (!empty($sOrderBy) && $sOrderBy == 'direccion') ? "selected='selected'" : "";?>><?php echo constant("STR_DIRECCION");?></option>
							<option style='color:#000000;' value='codPostal' <?php echo (!empty($sOrderBy) && $sOrderBy == 'codPostal') ? "selected='selected'" : "";?>><?php echo constant("STR_CODIGO_POSTAL");?></option>
							<option style='color:#000000;' value='telefono' <?php echo (!empty($sOrderBy) && $sOrderBy == 'telefono') ? "selected='selected'" : "";?>><?php echo constant("STR_TELEFONO");?></option>
							<option style='color:#000000;' value='estadoCivil' <?php echo (!empty($sOrderBy) && $sOrderBy == 'estadoCivil') ? "selected='selected'" : "";?>><?php echo constant("STR_ESTADO_CIVIL");?></option>
							<option style='color:#000000;' value='nacionalidad' <?php echo (!empty($sOrderBy) && $sOrderBy == 'nacionalidad') ? "selected='selected'" : "";?>><?php echo constant("STR_NACIONALIDAD");?></option>
							<option style='color:#000000;' value='informado' <?php echo (!empty($sOrderBy) && $sOrderBy == 'informado') ? "selected='selected'" : "";?>><?php echo constant("STR_INFORMADO");?></option>
							<option style='color:#000000;' value='finalizado' <?php echo (!empty($sOrderBy) && $sOrderBy == 'finalizado') ? "selected='selected'" : "";?>><?php echo constant("STR_FINALIZADO");?></option>
							<option style='color:#000000;' value='fechaFinalizado' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fechaFinalizado') ? "selected='selected'" : "";?>><?php echo constant("STR_FECHA_DE_FINALIZADO");?></option>
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
	<input type="hidden" name="candidatos_next_page" value="1" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" />
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdEmpresa(){
								var f= document.forms[0];
								$("#comboIdProceso").show().load("jQuery.php",{sPG:"comboprocesos",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdProceso",fJSProp:"cambiaIdProceso",LSTIdEmpresa:f.LSTIdEmpresa.value,vSelected:"<?php echo $cEntidad->getIdProceso();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdProceso(){
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdPais(){
								var f= document.forms[0];
								$("#comboIdProvincia").show().load("jQuery.php",{sPG:"combowi_provincias",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdProvincia",fJSProp:"cambiaIdProvincia",LSTCodIdiomaIso2:f.LSTCodIdiomaIso2.value,LSTIdPais:f.LSTIdPais.value,vSelected:"<?php echo $cEntidad->getIdProvincia();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdMunicipio").show().load("jQuery.php",{sPG:"combowi_municipios",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdMunicipio",fJSProp:"cambiaIdMunicipio",LSTCodIdiomaIso2:f.LSTCodIdiomaIso2.value,LSTIdProvincia:f.LSTIdProvincia.value,vSelected:"<?php echo $cEntidad->getIdMunicipio();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdZona").show().load("jQuery.php",{sPG:"combowi_zonas",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdZona",fJSProp:"cambiaIdZona",LSTCodIdiomaIso2:f.LSTCodIdiomaIso2.value,LSTIdMunicipio:f.LSTIdMunicipio.value,vSelected:"<?php echo $cEntidad->getIdZona();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");}).fadeIn("slow");}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdProvincia(){
								var f= document.forms[0];
								$("#comboIdMunicipio").show().load("jQuery.php",{sPG:"combowi_municipios",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdMunicipio",fJSProp:"cambiaIdMunicipio",LSTCodIdiomaIso2:f.LSTCodIdiomaIso2.value,LSTIdProvincia:f.LSTIdProvincia.value,vSelected:"<?php echo $cEntidad->getIdMunicipio();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdZona").show().load("jQuery.php",{sPG:"combowi_zonas",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdZona",fJSProp:"cambiaIdZona",LSTCodIdiomaIso2:f.LSTCodIdiomaIso2.value,LSTIdMunicipio:f.LSTIdMunicipio.value,vSelected:"<?php echo $cEntidad->getIdZona();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdMunicipio(){
								var f= document.forms[0];
								$("#comboIdZona").show().load("jQuery.php",{sPG:"combowi_zonas",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdZona",fJSProp:"cambiaIdZona",LSTCodIdiomaIso2:f.LSTCodIdiomaIso2.value,LSTIdMunicipio:f.LSTIdMunicipio.value,vSelected:"<?php echo $cEntidad->getIdZona();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdZona(){								
							}
							//]]>
						</script></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>