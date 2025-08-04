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
		f.LSTIdEmpresa.value=getmultiSeleccion(f.elements['LSTIdEmpresa[]']);
		f.LSTFecPrueba.value=cFechaFormat(f.LSTFecPrueba.value);
		f.LSTFecPruebaHast.value=cFechaFormat(f.LSTFecPruebaHast.value);
		f.LSTFecAltaProceso.value=cFechaFormat(f.LSTFecAltaProceso.value);
		f.LSTFecAltaProcesoHast.value=cFechaFormat(f.LSTFecAltaProcesoHast.value);
		return true;
	}else	return false;
}
function getmultiSeleccion(obj){
	var sValor="";
	for (var i=0; i < obj.length; i++){
		if (obj.options[i].selected && obj.options[i].value!=""){
			sValor+="," + obj.options[i].value;
		}
	}
	if(sValor!=""){
		sValor=sValor.substring(1,sValor.length);
	}
	return	sValor;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vString("<?php echo constant("STR_EMPRESA");?>:",getmultiSeleccion(f.elements['LSTIdEmpresa[]']), 255, false);
	msg +=vNumber("<?php echo constant("STR_PROCESO");?>:",f.LSTIdProceso.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_CANDIDATO");?>:",f.LSTIdCandidato.value, 11, false);
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.LSTNombre.value,255, false);
	msg +=vString("<?php echo constant("STR_APELLIDO1");?>:",f.LSTApellido1.value,255, false);
	msg +=vString("<?php echo constant("STR_APELLIDO2");?>:",f.LSTApellido2.value,255, false);
	msg +=vString("<?php echo constant("STR_EMAIL");?>:",f.LSTEmail.value,255, false);
	msg +=vNumber("<?php echo constant("STR_PRUEBA");?>:",f.LSTIdPrueba.value, 11, false);
	msg +=vDate("Fecha de Prueba:",f.LSTFecPrueba.value,10,false);
	msg +=vDate("Fecha de Prueba <?php echo constant("STR_HASTA");?>:",f.LSTFecPruebaHast.value,10,false);
	msg +=vNumber("<?php echo constant("STR_BAREMO");?>:",f.LSTIdBaremo.value, 11, false);
	msg +=vDate("Fecha de Alta Proceso:",f.LSTFecAltaProceso.value,10,false);
	msg +=vDate("Fecha de Alta Proceso <?php echo constant("STR_HASTA");?>:",f.LSTFecAltaProcesoHast.value,10,false);
	msg +=vNumber("<?php echo constant("STR_SEXO");?>:", f.LSTIdSexo.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_EDAD");?>:", f.LSTIdEdad.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_FORMACION");?>:", f.LSTIdFormacion.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_NIVEL");?>:", f.LSTIdNivel.value, 11, false);
	msg +=vNumber("<?php echo constant("STR_AREA");?>:", f.LSTIdArea.value, 11, false);
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
<body onload="_body_onload();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
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
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdEmpresa[]"><?php echo constant("STR_EMPRESA");?></label>&nbsp;</td>
					<td><?php $comboEMPRESAS->setNombre("LSTIdEmpresa[]");?><?php echo $comboEMPRESAS->getHTMLCombo("6","cajatexto",$cEntidad->getIdEmpresa()," multiple=\"multiple\" ","");?></td>
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
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTNombre"><?php echo constant("STR_NOMBRE");?></label>&nbsp;</td>
					<td><input type="text" id="LSTNombre" name="LSTNombre" value="<?php echo $cEntidad->getNombre();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTApellido1"><?php echo constant("STR_APELLIDO1");?></label>&nbsp;</td>
					<td><input type="text" id="LSTApellido1" name="LSTApellido1" value="<?php echo $cEntidad->getApellido1();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTApellido2"><?php echo constant("STR_APELLIDO2");?></label>&nbsp;</td>
					<td><input type="text" id="LSTApellido2" name="LSTApellido2" value="<?php echo $cEntidad->getApellido2();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTEmail"><?php echo constant("STR_EMAIL");?></label>&nbsp;</td>
					<td><input type="text" id="LSTEmail" name="LSTEmail" value="<?php echo $cEntidad->getEmail();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdPrueba"><?php echo constant("STR_PRUEBA");?></label>&nbsp;</td>
						<td><div id="comboIdPrueba"><?php $comboPRUEBAS->setNombre("LSTIdPrueba");?><?php echo $comboPRUEBAS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdPrueba(),"onchange=\"javascript:cambiaIdPrueba()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFecPrueba"><?php echo constant("STR_FECHA_DE_PRUEBA");?></label>&nbsp;</td>
					<?php if ($cEntidad->getFecPrueba() != "" && $cEntidad->getFecPrueba() != "0000-00-00" && $cEntidad->getFecPrueba() != "0000-00-00 00:00:00"){
						$cEntidad->setFecPrueba($conn->UserDate($cEntidad->getFecPrueba(),constant("USR_FECHA"),false));
					}else{
						$cEntidad->setFecPrueba("");
					}
					?>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecPrueba','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecPrueba" name="LSTFecPrueba" value="<?php echo $cEntidad->getFecPrueba();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<?php if ($cEntidad->getFecPruebaHast() != "" && $cEntidad->getFecPruebaHast() != "0000-00-00" && $cEntidad->getFecPruebaHast() != "0000-00-00 00:00:00"){
								$cEntidad->setFecPruebaHast($conn->UserDate($cEntidad->getFecPruebaHast(),constant("USR_FECHA"),false));
								}else{
								$cEntidad->setFecPrueba("");
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecPruebaHast','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecPruebaHast" name="LSTFecPruebaHast" value="<?php echo $cEntidad->getFecPruebaHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdBaremo"><?php echo constant("STR_BAREMO");?></label>&nbsp;</td>
						<td><div id="comboIdBaremo"><?php $comboBAREMOS->setNombre("LSTIdBaremo");?><?php echo $comboBAREMOS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdBaremo(),"onchange=\"javascript:cambiaIdBaremo()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFecAltaProceso"><?php echo constant("STR_FECHA_DE_ALTA_PROCESO");?></label>&nbsp;</td>
					<?php if ($cEntidad->getFecAltaProceso() != "" && $cEntidad->getFecAltaProceso() != "0000-00-00" && $cEntidad->getFecAltaProceso() != "0000-00-00 00:00:00"){
						$cEntidad->setFecAltaProceso($conn->UserDate($cEntidad->getFecAltaProceso(),constant("USR_FECHA"),false));
					}else{
						$cEntidad->setFecAltaProceso("");
					}
					?>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecAltaProceso','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecAltaProceso" name="LSTFecAltaProceso" value="<?php echo $cEntidad->getFecAltaProceso();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<?php if ($cEntidad->getFecAltaProcesoHast() != "" && $cEntidad->getFecAltaProcesoHast() != "0000-00-00" && $cEntidad->getFecAltaProcesoHast() != "0000-00-00 00:00:00"){
								$cEntidad->setFecAltaProcesoHast($conn->UserDate($cEntidad->getFecAltaProcesoHast(),constant("USR_FECHA"),false));
								}else{
								$cEntidad->setFecAltaProceso("");
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecAltaProcesoHast','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecAltaProcesoHast" name="LSTFecAltaProcesoHast" value="<?php echo $cEntidad->getFecAltaProcesoHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
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
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdFormacion"><?php echo constant("STR_IDFORMACION");?></label>&nbsp;</td>
					<td><?php $comboFORMACIONES->setNombre("LSTIdFormacion");?><?php echo $comboFORMACIONES->getHTMLCombo("1","cajatexto",$cEntidad->getIdFormacion()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTDescFormacion"><?php echo constant("STR_FORMACION");?></label>&nbsp;</td>
					<td><input type="text" id="LSTDescFormacion" name="LSTDescFormacion" value="<?php echo $cEntidad->getDescFormacion();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
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
					<td nowrap="nowrap" width="140" class="grisb"><?php echo constant("STR_ORDENAR_POR");?>&nbsp;</td>
					<td>
						<select id="LSTOrderBy" name="LSTOrderBy" size="1" class="cajatexto">
							<?php $sOrderBy = $cEntidad->getOrderBy();?>
							<option style='color:#000000;' value='' <?php echo (empty($sOrderBy)) ? "selected='selected'" : "";?>><?php echo constant("SLC_OPCION");?></option>
							<option style='color:#000000;' value='descEmpresa' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descEmpresa') ? "selected='selected'" : "";?>><?php echo constant("STR_EMPRESA");?></option>
							<option style='color:#000000;' value='descProceso' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descProceso') ? "selected='selected'" : "";?>><?php echo constant("STR_PROCESO");?></option>
							<option style='color:#000000;' value='nombre' <?php echo (!empty($sOrderBy) && $sOrderBy == 'nombre') ? "selected='selected'" : "";?>><?php echo constant("STR_NOMBRE");?></option>
							<option style='color:#000000;' value='apellido1' <?php echo (!empty($sOrderBy) && $sOrderBy == 'apellido1') ? "selected='selected'" : "";?>><?php echo constant("STR_APELLIDO1");?></option>
							<option style='color:#000000;' value='apellido2' <?php echo (!empty($sOrderBy) && $sOrderBy == 'apellido2') ? "selected='selected'" : "";?>><?php echo constant("STR_APELLIDO2");?></option>
							<option style='color:#000000;' value='email' <?php echo (!empty($sOrderBy) && $sOrderBy == 'email') ? "selected='selected'" : "";?>><?php echo constant("STR_EMAIL");?></option>
							<option style='color:#000000;' value='descPrueba' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descPrueba') ? "selected='selected'" : "";?>><?php echo constant("STR_PRUEBA");?></option>
							<option style='color:#000000;' value='fecPrueba' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fecPrueba') ? "selected='selected'" : "";?>><?php echo constant("STR_FECHA_DE_PRUEBA");?></option>
							<option style='color:#000000;' value='descBaremo' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descBaremo') ? "selected='selected'" : "";?>><?php echo constant("STR_BAREMO");?></option>
							<option style='color:#000000;' value='fecAltaProceso' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fecAltaProceso') ? "selected='selected'" : "";?>><?php echo constant("STR_FECHA_DE_ALTA_PROCESO");?></option>
							<option style='color:#000000;' value='correctas' <?php echo (!empty($sOrderBy) && $sOrderBy == 'correctas') ? "selected='selected'" : "";?>><?php echo constant("STR_CORRECTAS");?></option>
							<option style='color:#000000;' value='contestadas' <?php echo (!empty($sOrderBy) && $sOrderBy == 'contestadas') ? "selected='selected'" : "";?>><?php echo constant("STR_CONTESTADAS");?></option>
							<option style='color:#000000;' value='percentil' <?php echo (!empty($sOrderBy) && $sOrderBy == 'percentil') ? "selected='selected'" : "";?>><?php echo constant("STR_PERCENTIL");?></option>
							<option style='color:#000000;' value='ir' <?php echo (!empty($sOrderBy) && $sOrderBy == 'ir') ? "selected='selected'" : "";?>><?php echo constant("STR_IR");?></option>
							<option style='color:#000000;' value='ip' <?php echo (!empty($sOrderBy) && $sOrderBy == 'ip') ? "selected='selected'" : "";?>><?php echo constant("STR_IP");?></option>
							<option style='color:#000000;' value='por' <?php echo (!empty($sOrderBy) && $sOrderBy == 'por') ? "selected='selected'" : "";?>><?php echo constant("STR_POR");?></option>
							<option style='color:#000000;' value='estilo' <?php echo (!empty($sOrderBy) && $sOrderBy == 'estilo') ? "selected='selected'" : "";?>><?php echo constant("STR_ESTILO");?></option>
							<option style='color:#000000;' value='descSexo' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descSexo') ? "selected='selected'" : "";?>><?php echo constant("STR_SEXO");?></option>
							<option style='color:#000000;' value='descEdad' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descEdad') ? "selected='selected'" : "";?>><?php echo constant("STR_EDAD");?></option>
							<option style='color:#000000;' value='descFormacion' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descFormacion') ? "selected='selected'" : "";?>><?php echo constant("STR_FORMACION");?></option>
							<option style='color:#000000;' value='descNivel' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descNivel') ? "selected='selected'" : "";?>><?php echo constant("STR_NIVEL");?></option>
							<option style='color:#000000;' value='descArea' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descArea') ? "selected='selected'" : "";?>><?php echo constant("STR_AREA");?></option>
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
	<input type="hidden" name="LSTIdEmpresa" value="" />
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_BUSCAR");?>" />
	<input type="hidden" name="export_aptitudinales_next_page" value="1" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" />
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdEmpresa(){
								var f= document.forms[0];
								$("#comboIdProceso").show().load("jQuery.php",{sPG:"comboprocesos",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdProceso",fJSProp:"cambiaIdProceso",LSTIdEmpresa:f.LSTIdEmpresa.value,vSelected:"<?php echo $cEntidad->getIdProceso();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdCandidato").show().load("jQuery.php",{sPG:"combocandidatos",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdCandidato",fJSProp:"cambiaIdCandidato",LSTIdEmpresa:f.LSTIdEmpresa.value,LSTIdProceso:f.LSTIdProceso.value,vSelected:"<?php echo $cEntidad->getIdCandidato();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdBaremo").show().load("jQuery.php",{sPG:"combobaremos",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdBaremo",fJSProp:"cambiaIdBaremo",LSTIdPrueba:f.LSTIdPrueba.value,vSelected:"<?php echo $cEntidad->getIdBaremo();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");}).fadeIn("slow");}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdProceso(){
								var f= document.forms[0];
								$("#comboIdCandidato").show().load("jQuery.php",{sPG:"combocandidatos",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdCandidato",fJSProp:"cambiaIdCandidato",LSTIdEmpresa:f.LSTIdEmpresa.value,LSTIdProceso:f.LSTIdProceso.value,vSelected:"<?php echo $cEntidad->getIdCandidato();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdBaremo").show().load("jQuery.php",{sPG:"combobaremos",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdBaremo",fJSProp:"cambiaIdBaremo",LSTIdPrueba:f.LSTIdPrueba.value,vSelected:"<?php echo $cEntidad->getIdBaremo();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdCandidato(){								
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdPrueba(){
								var f= document.forms[0];
								$("#comboIdBaremo").show().load("jQuery.php",{sPG:"combobaremos",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdBaremo",fJSProp:"cambiaIdBaremo",LSTIdPrueba:f.LSTIdPrueba.value,vSelected:"<?php echo $cEntidad->getIdBaremo();?>",sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdBaremo(){								
							}
							//]]>
						</script></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>