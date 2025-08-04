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
	<script src="https://cdn.tiny.cloud/1/19u4q91vla6r5niw2cs7kaymfs18v3j11oizctq52xltyrf4/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script>tinymce.init({ selector:'.tinymce' });</script>

<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php");?>
function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
		lon();
		f.fFechaInicio.value=cFechaFormat(f.fFechaInicio.value);
		f.fFechaFin.value=cFechaFormat(f.fFechaFin.value);
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
  var myf = $('#fObservaciones_ifr');
  var editorContent = $('#tinymce[data-id="fObservaciones"]', myf.contents()).html();
	msg +=vNumber("<?php echo constant("STR_EMPRESA");?>:",f.fIdEmpresa.value,11,true);
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombre.value,255,true);
	msg +=vString("<?php echo constant("STR_DESCRIPCION");?>:",f.fDescripcion.value,255,false);
	msg +=vString("<?php echo constant("STR_OBSERVACIONES");?>:",editorContent,4000,false);
	msg +=vDate("<?php echo constant("STR_FECHA_DE_INICIO");?>:",f.fFechaInicio.value,10,true);
	msg +=vDate("<?php echo constant("STR_FECHA_DE_FIN");?>:",f.fFechaFin.value,10,true);
	msg +=vNumber("<?php echo constant("STR_MODO_REALIZACION_PRUEBAS");?>:",f.fIdModoRealizacion.value,11,true);
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
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdEmpresa"><?php echo constant("STR_EMPRESA");?></label>&nbsp;</td>
					<td><?php $comboEMPRESAS->setNombre("fIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLComboMenu("1","obliga",$cEntidad->getIdEmpresa()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNombre"><?php echo constant("STR_NOMBRE");?></label>&nbsp;</td>
					<td><input type="text" id="fNombre" name="fNombre" value="<?php echo $cEntidad->getNombre();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fDescripcion"><?php echo constant("STR_DESCRIPCION");?></label>&nbsp;</td>
					<td><input type="text" id="fDescripcion" name="fDescripcion" value="<?php echo $cEntidad->getDescripcion();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fObservaciones"><?php echo constant("STR_OBSERVACIONES");?></label>&nbsp;</td>
					<td><textarea class="tinymce" data-id="fObservaciones" id="fObservaciones" name="fObservaciones"><?php echo  $cEntidad->getObservaciones();?></textarea>

							</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fFechaInicio"><?php echo constant("STR_FECHA_DE_INICIO");?></label>&nbsp;</td>
					<?php if ($cEntidad->getFechaInicio() != "" && $cEntidad->getFechaInicio() != "0000-00-00" && $cEntidad->getFechaInicio() != "0000-00-00 00:00:00"){
						$cEntidad->setFechaInicio($conn->UserDate($cEntidad->getFechaInicio(),constant("USR_FECHA"),false));
					}else{
						//Palabras especiales (tomorrow, yesterday, ago, fortnight, now, today, day, week, month, year, hour, minute, min, second, sec)
						$date = date('Y-m-d', strtotime('+10 year'));
						$cEntidad->setFechaInicio($date);
						$cEntidad->setFechaInicio($conn->UserDate($cEntidad->getFechaInicio(),constant("USR_FECHA"),false));
					}
					?>
					<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=fFechaInicio','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO');?>" align="bottom" /></a>&nbsp;<input type="text" id="fFechaInicio" name="fFechaInicio" value="<?php echo $cEntidad->getFechaInicio();?>" class=cajatexto id="tid-obliga" style="width:75px;" onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fFechaFin"><?php echo constant("STR_FECHA_DE_FIN");?></label>&nbsp;</td>
					<?php if ($cEntidad->getFechaFin() != "" && $cEntidad->getFechaFin() != "0000-00-00" && $cEntidad->getFechaFin() != "0000-00-00 00:00:00"){
						$cEntidad->setFechaFin($conn->UserDate($cEntidad->getFechaFin(),constant("USR_FECHA"),false));
					}else{
						//Palabras especiales (tomorrow, yesterday, ago, fortnight, now, today, day, week, month, year, hour, minute, min, second, sec)
						$date = date('Y-m-d', strtotime('+10 year'));
						$cEntidad->setFechaFin($date);
						$cEntidad->setFechaFin($conn->UserDate($cEntidad->getFechaFin(),constant("USR_FECHA"),false));
					}
					?>
					<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=fFechaFin','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO');?>" align="bottom" /></a>&nbsp;<input type="text" id="fFechaFin" name="fFechaFin" value="<?php echo $cEntidad->getFechaFin();?>" class=cajatexto id="tid-obliga" style="width:75px;" onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdModoRealizacion"><?php echo constant("STR_MODO_REALIZACION_PRUEBAS");?></label>&nbsp;</td>
					<td><?php $comboMODO_REALIZACION->setNombre("fIdModoRealizacion");?><?php echo $comboMODO_REALIZACION->getHTMLCombo("1","obliga",$cEntidad->getIdModoRealizacion()," ","");?></td>
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
	<input type="hidden" name="fIdProceso" value="<?php echo (empty($_POST['AddIdioma'])) ? $cEntidad->getIdProceso() : $_POST['fIdProceso'];?>" />
	<input type="hidden" name="fCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" />
	<input type="hidden" name="LSTIdProcesoHast" value="<?php echo (isset($_POST['LSTIdProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdProcesoHast']) : "";?>" />
	<input type="hidden" name="LSTIdProceso" value="<?php echo (isset($_POST['LSTIdProceso'])) ? $cUtilidades->validaXSS($_POST['LSTIdProceso']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresaHast" value="<?php echo (isset($_POST['LSTIdEmpresaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaHast']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresa" value="<?php echo (isset($_POST['LSTIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresa']) : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $cUtilidades->validaXSS($_POST['LSTNombre']) : "";?>" />
	<input type="hidden" name="LSTDescripcion" value="<?php echo (isset($_POST['LSTDescripcion'])) ? $cUtilidades->validaXSS($_POST['LSTDescripcion']) : "";?>" />
	<input type="hidden" name="LSTObservaciones" value="<?php echo (isset($_POST['LSTObservaciones'])) ? $cUtilidades->validaXSS($_POST['LSTObservaciones']) : "";?>" />
	<input type="hidden" name="LSTFechaInicioHast" value="<?php echo (isset($_POST['LSTFechaInicioHast'])) ? $cUtilidades->validaXSS($_POST['LSTFechaInicioHast']) : "";?>" />
	<input type="hidden" name="LSTFechaInicio" value="<?php echo (isset($_POST['LSTFechaInicio'])) ? $cUtilidades->validaXSS($_POST['LSTFechaInicio']) : "";?>" />
	<input type="hidden" name="LSTFechaFinHast" value="<?php echo (isset($_POST['LSTFechaFinHast'])) ? $cUtilidades->validaXSS($_POST['LSTFechaFinHast']) : "";?>" />
	<input type="hidden" name="LSTFechaFin" value="<?php echo (isset($_POST['LSTFechaFin'])) ? $cUtilidades->validaXSS($_POST['LSTFechaFin']) : "";?>" />
	<input type="hidden" name="LSTIdModoRealizacionHast" value="<?php echo (isset($_POST['LSTIdModoRealizacionHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdModoRealizacionHast']) : "";?>" />
	<input type="hidden" name="LSTIdModoRealizacion" value="<?php echo (isset($_POST['LSTIdModoRealizacion'])) ? $cUtilidades->validaXSS($_POST['LSTIdModoRealizacion']) : "";?>" />
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
	<input type="hidden" name="procesos_next_page" value="<?php echo (isset($_POST['procesos_next_page'])) ? $cUtilidades->validaXSS($_POST['procesos_next_page']) : "1";?>" /></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>
	<input type="hidden" name="procesos_next_page" value="<?php echo (isset($_POST['procesos_next_page'])) ? $cUtilidades->validaXSS($_POST['procesos_next_page']) : "1";?>" />
