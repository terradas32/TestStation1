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
		f.fFechaNacimiento.value=cFechaFormat(f.fFechaNacimiento.value);
		f.fIdProvincia.value=f.elements['fIdProvincia'].value;
		f.fIdMunicipio.value=f.elements['fIdMunicipio'].value;
		f.fIdZona.value=f.elements['fIdZona'].value;
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo constant("STR_EMPRESA");?>:",f.fIdEmpresa.value,11,true);
	msg +=vNumber("<?php echo constant("STR_PROCESO");?>:",f.fIdProceso.value,11,true);
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombre.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO_1");?>:",f.fApellido1.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO_2");?>:",f.fApellido2.value,255,true);
if (!f.fDniOK.checked){
	msg +=vNif("<?php echo constant("STR_NIF");?>:",f.fDni.value,255,false);
}
	msg +=vEmail("<?php echo constant("STR_EMAIL");?>:",f.fMail.value,255,true);
	msg +=vNumber("<?php echo constant("STR_TRATAMIENTO");?>:",f.fIdTratamiento.value,11,true);
	msg +=vNumber("<?php echo constant("STR_SEXO");?>:",f.fIdSexo.value,11,false);
	msg +=vNumber("<?php echo constant("STR_EDAD");?>:",f.fIdEdad.value,11,false);
	msg +=vDate("<?php echo constant("STR_FECHA_DE_NACIMIENTO");?>:",f.fFechaNacimiento.value,10,false);
	msg +=vString("<?php echo constant("STR_PAIS");?>:",f.fIdPais.value,3,false);
	msg +=vString("<?php echo constant("STR_PROVINCIA");?>:",f.elements['fIdProvincia'].value,2,false);
	msg +=vString("<?php echo constant("STR_MUNICIPIO");?>:",f.elements['fIdMunicipio'].value,5,false);
	msg +=vString("<?php echo constant("STR_ZONA");?>:",f.elements['fIdZona'].value,255,false);
	msg +=vString("<?php echo constant("STR_DIRECCION");?>:",f.fDireccion.value,255,false);
	msg +=vString("<?php echo constant("STR_CODIGO_POSTAL");?>:",f.fCodPostal.value,255,false);
	msg +=vNumber("<?php echo constant("STR_FORMACION");?>:",f.fIdFormacion.value,11,false);
	msg +=vNumber("<?php echo constant("STR_NIVEL");?>:",f.fIdNivel.value,11,false);
	msg +=vNumber("<?php echo constant("STR_AREA");?>:",f.fIdArea.value,11,false);
	msg +=vString("<?php echo constant("STR_TELEFONO");?>:",f.fTelefono.value,255,false);
	msg +=vString("<?php echo constant("STR_ESTADO_CIVIL");?>:",f.fEstadoCivil.value,255,false);
	msg +=vString("<?php echo constant("STR_NACIONALIDAD");?>:",f.fNacionalidad.value,255,false);
if (msg != "") {
	jAlert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.","<?php echo constant("STR_NOTIFICACION");?>");
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
<body onload="_body_onload();cambiaIdPais();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
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
					<td><?php $comboEMPRESAS->setNombre("fIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLComboMenu("1","obliga",$cEntidad->getIdEmpresa()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdProceso"><?php echo constant("STR_PROCESO");?></label>&nbsp;</td>
					<td><?php $comboPROCESOS->setNombre("fIdProceso");?><?php echo $comboPROCESOS->getHTMLCombo("1","obliga",$cEntidad->getIdProceso()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNombre"><?php echo constant("STR_NOMBRE");?></label>&nbsp;</td>
					<td><input type="text" id="fNombre" name="fNombre" value="<?php echo $cEntidad->getNombre();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fApellido1"><?php echo constant("STR_APELLIDO_1");?></label>&nbsp;</td>
					<td><input type="text" id="fApellido1" name="fApellido1" value="<?php echo $cEntidad->getApellido1();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fApellido2"><?php echo constant("STR_APELLIDO_2");?></label>&nbsp;</td>
					<td><input type="text" id="fApellido2" name="fApellido2" value="<?php echo $cEntidad->getApellido2();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fDni"><?php echo constant("STR_NIF");?></label>&nbsp;</td>
					<td><input type="text" id="fDni" name="fDni" value="<?php echo $cEntidad->getDni();?>" class="cajatexto"  style='width:75%;' onchange="javascript:trim(this);" /><input type="checkbox" name="fDniOK" /><?php echo constant("STR_VERIFICADO");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fMail"><?php echo constant("STR_EMAIL");?></label>&nbsp;</td>
					<td><input type="text" id="fMail" name="fMail" value="<?php echo $cEntidad->getMail();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdTratamiento"><?php echo constant("STR_TRATAMIENTO");?></label>&nbsp;</td>
					<td><?php $comboTRATAMIENTOS->setNombre("fIdTratamiento");?><?php echo $comboTRATAMIENTOS->getHTMLCombo("1","obliga",$cEntidad->getIdTratamiento()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdSexo"><?php echo constant("STR_SEXO");?></label>&nbsp;</td>
					<td><?php $comboSEXOS->setNombre("fIdSexo");?><?php echo $comboSEXOS->getHTMLCombo("1","cajatexto",$cEntidad->getIdSexo()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdEdad"><?php echo constant("STR_EDAD");?></label>&nbsp;</td>
					<td><?php $comboEDADES->setNombre("fIdEdad");?><?php echo $comboEDADES->getHTMLCombo("1","cajatexto",$cEntidad->getIdEdad()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fFechaNacimiento"><?php echo constant("STR_FECHA_DE_NACIMIENTO");?></label>&nbsp;</td>
					<?php if ($cEntidad->getFechaNacimiento() != "" && $cEntidad->getFechaNacimiento() != "0000-00-00" && $cEntidad->getFechaNacimiento() != "0000-00-00 00:00:00"){
						$cEntidad->setFechaNacimiento($conn->UserDate($cEntidad->getFechaNacimiento(),constant("USR_FECHA"),false));
					}else{
						//Palabras especiales (tomorrow, yesterday, ago, fortnight, now, today, day, week, month, year, hour, minute, min, second, sec)
						$date = date('Y-m-d', strtotime('+10 year'));
						$cEntidad->setFechaNacimiento($date);
						$cEntidad->setFechaNacimiento($conn->UserDate($cEntidad->getFechaNacimiento(),constant("USR_FECHA"),false));
					}
					?>
					<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=fFechaNacimiento','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO');?>" align="bottom" /></a>&nbsp;<input type="text" id="fFechaNacimiento" name="fFechaNacimiento" value="<?php echo $cEntidad->getFechaNacimiento();?>" class=cajatexto style="width:75px;" onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdPais"><?php echo constant("STR_PAIS");?></label>&nbsp;</td>
					<td><?php $comboWI_PAISES->setNombre("fIdPais");?><?php echo $comboWI_PAISES->getHTMLCombo("1","cajatexto",$cEntidad->getIdPais()," onchange=\"javascript:cambiaIdPais()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdProvincia"><?php echo constant("STR_PROVINCIA");?></label>&nbsp;</td>
						<td><div id="comboIdProvincia"><?php $comboWI_PROVINCIAS->setNombre("fIdProvincia");?><?php echo $comboWI_PROVINCIAS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdProvincia(),"onchange=\"javascript:cambiaIdProvincia()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdMunicipio"><?php echo constant("STR_MUNICIPIO");?></label>&nbsp;</td>
						<td><div id="comboIdMunicipio"><?php $comboWI_MUNICIPIOS->setNombre("fIdMunicipio");?><?php echo $comboWI_MUNICIPIOS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdMunicipio(),"onchange=\"javascript:cambiaIdMunicipio()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdZona"><?php echo constant("STR_ZONA");?></label>&nbsp;</td>
						<td><div id="comboIdZona"><?php $comboWI_ZONAS->setNombre("fIdZona");?><?php echo $comboWI_ZONAS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdZona(),"onchange=\"javascript:cambiaIdZona()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fDireccion"><?php echo constant("STR_DIRECCION");?></label>&nbsp;</td>
					<td><input type="text" id="fDireccion" name="fDireccion" value="<?php echo $cEntidad->getDireccion();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fCodPostal"><?php echo constant("STR_CODIGO_POSTAL");?></label>&nbsp;</td>
					<td><input type="text" id="fCodPostal" name="fCodPostal" value="<?php echo $cEntidad->getCodPostal();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdFormacion"><?php echo constant("STR_FORMACION");?></label>&nbsp;</td>
					<td><?php $comboFORMACIONES->setNombre("fIdFormacion");?><?php echo $comboFORMACIONES->getHTMLCombo("1","cajatexto",$cEntidad->getIdFormacion()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdNivel"><?php echo constant("STR_NIVEL");?></label>&nbsp;</td>
					<td><?php $comboNIVELESJERARQUICOS->setNombre("fIdNivel");?><?php echo $comboNIVELESJERARQUICOS->getHTMLCombo("1","cajatexto",$cEntidad->getIdNivel()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdArea"><?php echo constant("STR_AREA");?></label>&nbsp;</td>
					<td><?php $comboAREAS->setNombre("fIdArea");?><?php echo $comboAREAS->getHTMLCombo("1","cajatexto",$cEntidad->getIdArea()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fTelefono"><?php echo constant("STR_TELEFONO");?></label>&nbsp;</td>
					<td><input type="text" id="fTelefono" name="fTelefono" value="<?php echo $cEntidad->getTelefono();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fEstadoCivil"><?php echo constant("STR_ESTADO_CIVIL");?></label>&nbsp;</td>
					<td><input type="text" id="fEstadoCivil" name="fEstadoCivil" value="<?php echo $cEntidad->getEstadoCivil();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNacionalidad"><?php echo constant("STR_NACIONALIDAD");?></label>&nbsp;</td>
					<td><input type="text" id="fNacionalidad" name="fNacionalidad" value="<?php echo $cEntidad->getNacionalidad();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
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
	<input type="hidden" name="fIdCandidato" value="<?php echo (empty($_POST['AddIdioma'])) ? $cEntidad->getIdCandidato() : $_POST['fIdCandidato'];?>" />
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
	<input type="hidden" name="LSTUsuAlta" value="<?php echo (isset($_POST['LSTk'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAlta']) : "";?>" />
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
	<input type="hidden" name="candidatos_back_next_page" value="<?php echo (isset($_POST['candidatos_back_next_page'])) ? $cUtilidades->validaXSS($_POST['candidatos_back_next_page']) : "1";?>" />
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdPais(){
								var f= document.forms[0];
								$("#comboIdProvincia").show().load("jQuery.php",{sPG:"combowi_provincias",bBus:"0",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdProvincia",fJSProp:"cambiaIdProvincia",fCodIdiomaIso2:f.fCodIdiomaIso2.value,fIdPais:f.fIdPais.value,vSelected:"<?php echo $cEntidad->getIdProvincia();?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdMunicipio").show().load("jQuery.php",{sPG:"combowi_municipios",bBus:"0",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdMunicipio",fJSProp:"cambiaIdMunicipio",fCodIdiomaIso2:f.fCodIdiomaIso2.value,fIdProvincia:f.fIdProvincia.value,vSelected:"<?php echo $cEntidad->getIdMunicipio();?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdZona").show().load("jQuery.php",{sPG:"combowi_zonas",bBus:"0",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdZona",fJSProp:"cambiaIdZona",fCodIdiomaIso2:f.fCodIdiomaIso2.value,fIdMunicipio:f.fIdMunicipio.value,vSelected:"<?php echo $cEntidad->getIdZona();?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");}).fadeIn("slow");}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdProvincia(){
								var f= document.forms[0];
								$("#comboIdMunicipio").show().load("jQuery.php",{sPG:"combowi_municipios",bBus:"0",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdMunicipio",fJSProp:"cambiaIdMunicipio",fCodIdiomaIso2:f.fCodIdiomaIso2.value,fIdProvincia:f.fIdProvincia.value,vSelected:"<?php echo $cEntidad->getIdMunicipio();?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdZona").show().load("jQuery.php",{sPG:"combowi_zonas",bBus:"0",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdZona",fJSProp:"cambiaIdZona",fCodIdiomaIso2:f.fCodIdiomaIso2.value,fIdMunicipio:f.fIdMunicipio.value,vSelected:"<?php echo $cEntidad->getIdZona();?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdMunicipio(){
								var f= document.forms[0];
								$("#comboIdZona").show().load("jQuery.php",{sPG:"combowi_zonas",bBus:"0",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdZona",fJSProp:"cambiaIdZona",fCodIdiomaIso2:f.fCodIdiomaIso2.value,fIdMunicipio:f.fIdMunicipio.value,vSelected:"<?php echo $cEntidad->getIdZona();?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
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
	<input type="hidden" name="candidatos_back_next_page" value="<?php echo (isset($_POST['candidatos_back_next_page'])) ? $cUtilidades->validaXSS($_POST['candidatos_back_next_page']) : "1";?>" />