<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<?php
	$cPrueba = new Pruebas();
	$cPrueba->setIdPrueba($cOpcionesEjemplos->getIdPrueba());
	$cPrueba->setCodIdiomaIso2($cOpcionesEjemplos->getCodIdiomaIso2());
	$cPrueba = $cEntidadDB->readEntidad($cPrueba);

	$cIdiomas2->setCodIso2($cPrueba->getCodIdiomaIso2());
	$cIdiomas2 = $cIdiomas2DB->readEntidad($cIdiomas2);
	
	$cEjemplo1 = new Ejemplos();
	$cEjemplo1->setIdPrueba($cPrueba->getIdPrueba());
	$cEjemplo1->setCodIdiomaIso2($cPrueba->getCodIdiomaIso2());
	$cEjemplo1->setIdEjemplo($cOpcionesEjemplos->getIdEjemplo());
	$cEjemplo1 = $cEjemplosDB->readEntidad($cEjemplo1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
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
	f.fOpcionEjemplo.value=1;
	if (validaForm()){
		lon();
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo constant("STR_PRUEBA");?>:",f.fIdPrueba.value,11,true);
	msg +=vString("<?php echo constant("STR_IDIOMA");?>:",f.fCodIdiomaIso2.value,2,true);
	msg +=vNumber("<?php echo constant("STR_ESTADO");?>:",f.fIdEjemplo.value,11,true);
	msg +=vString("<?php echo constant("STR_DESCRIPCION");?>:",f.fDescripcion.value,255,false);
	msg +=vString("<?php echo constant("STR_IMAGEN");?>:",f.fPathOpcion.value,255,false);
	msg +=vString("<?php echo constant("STR_CODIGO");?>:",f.fCodigo.value,255,false);
	msg +=vNumber("<?php echo constant("STR_BAJA_LOG");?>:",f.fBajaLog.value,2,true);
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
onclick="if (document.getElementById('Mas').style.display == 'none'){document.getElementById('Mas').style.display='block';}else{document.getElementById('Mas').style.display='none';}"
//]]>
</script>
<script language="javascript" type="text/javascript">
//<![CDATA[
function _body_onload(){	loff();	}
function _body_onunload(){	lon();	}
//]]>
</script>
</head>
<body onload="_body_onload();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');"  onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0"  title="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" alt="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
	
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
			
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
				<tr>
					<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
					<td nowrap="nowrap" align="left" class="naranjab" valign="top"></td>
					<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="azul" valign="top" width="35%"><b>Prueba:</b> <?php echo $cPrueba->getNombre();?></td>
								<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
								<td nowrap="nowrap" class="azul" valign="top" width="35%"></td>
								<td width="30%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
							</tr>
							<tr>
								<td colspan ="4" width="100%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="15" border="0" alt="" /></td>
							</tr>
							<tr>
								<td nowrap="nowrap" class="azul" valign="top" width="35%"><b>Idioma:</b> <?php echo $cIdiomas2->getNombre();?></td>
								<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
								<td nowrap="nowrap" class="azul" valign="top" width="35%"></td>
								<td width="30%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
							</tr>
							<tr>
								<td colspan ="4" width="100%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="15" border="0" alt="" /></td>
							</tr>
							<tr>
								<td nowrap="nowrap" class="azul" valign="top" width="35%"><b>Id del Ejemplo:</b> <?php echo $cEjemplo1->getIdEjemplo();?></td>
								<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
								<td nowrap="nowrap" class="azul" valign="top" width="35%"><b>Respuesta correcta:</b> <?php echo $cEjemplo1->getCorrecto();?></td>
								<td width="30%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
							</tr>
							<?php if($cEjemplo1->getEnunciado() !=""){?>
								<tr>
									<td colspan ="4" width="100%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="15" border="0" alt="" /></td>
								</tr>
								
								<tr>
									<td colspan="4" nowrap="nowrap" class="azul" valign="top" width="100%"><b>Enunciado:</b> <?php echo $cEjemplo1->getEnunciado();?></td>
								</tr>
							<?	
							}
							if($cEjemplo1->getDescripcion() !=""){?>
							
								<tr>
									<td colspan ="4" width="100%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="15" border="0" alt="" /></td>
								</tr>
								<tr>
									<td colspan ="4" nowrap="nowrap" class="azul" valign="top" width="100%"><b>Descripción:</b> <?php echo $cEjemplo1->getDescripcion();?></td>
								</tr>
							<?php 
							}?>
						</table>
					</td>
				</tr>
				<tr><td colspan="4"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td></tr>
				<tr><td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
					<td colspan="3" bgcolor="#FF8F19"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
				</tr>
				<tr><td colspan="4"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
			</table>
			
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tr><td colspan="3" align="center" class="naranjab"><?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){echo(constant("STR_ANIADIR"));}else{echo(constant("STR_MODIFICAR"));}?></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_DESCRIPCION");?>&nbsp;</td>
					<td><input type="text" name="fDescripcion" value="<?php echo $cOpcionesEjemplos->getDescripcion();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_IMAGEN");?>&nbsp;</td>
					<td><input onkeydown="blur();" onkeypress="blur();" type="file" name="fPathOpcion" class="cajatexto"  /></td>
				</tr>
				<?php if ($cOpcionesEjemplos->getPathOpcion() != "")
				{
					$img=@getimagesize(constant("HTTP_SERVER") . $cOpcionesEjemplos->getPathOpcion());
					$bIimg = (empty($img)) ? 0 : 1;
				?>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td width="140" class="negrob" valign="top">&nbsp;</td>
						<td>&nbsp;<a class="azul" href="#" onclick="abrirVentana('<?php echo $bIimg;?>','<?php echo base64_encode(constant("HTTP_SERVER") . $cOpcionesEjemplos->getPathOpcion());?>');"><?php echo str_replace("_","&nbsp;", basename($cOpcionesEjemplos->getPathOpcion()));?></a>&nbsp;<input onmouseover="this.style.cursor='pointer'" type="checkbox" name="cfPathOpcion" />&nbsp;<?php echo constant("STR_QUITAR");?></td>
					</tr>
				<?php } ?>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_CODIGO");?>&nbsp;</td>
					<td><input type="text" name="fCodigo" value="<?php echo $cOpcionesEjemplos->getCodigo();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_BAJA_LOG");?>&nbsp;</td>
					
				    <td>
					   <select name="fBajaLog" size="1">
						  <option value="0" <?php echo ($cOpcionesEjemplos->getBajaLog() == "0") ? "selected" : "";?>><?php echo constant("STR_ACTIVO");?></option>
						  <option value="1" <?php echo ($cOpcionesEjemplos->getBajaLog() == "1") ? "selected" : "";?>><?php echo constant("STR_NO_ACTIVO");?></option>
					   </select>
				    </td>
                    
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="document.forms[0].MODO.value=document.forms[0].ORIGEN.value;document.forms[0].fOpcionEjemplo.value=1;document.forms[0].fCodIdiomaIso2.disabled=false;document.forms[0].fIdPrueba.disabled=false;document.forms[0].submit();" /></td>
			<td><input type="submit" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ACEPTAR");?>" /></td>
		</tr>
	</table>
	</div>
</div>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="fOpcionEjemplo" value="" />
	<input type="hidden" name="fIdOpcion" value="<?php echo $cOpcionesEjemplos->getIdOpcion();?>" />
	<input type="hidden" name="fIdEjemplo" value="<?php echo $cOpcionesEjemplos->getIdEjemplo()?>" />
	<input type="hidden" name="fIdPrueba" value="<?php echo $cOpcionesEjemplos->getIdPrueba()?>" />
	<input type="hidden" name="fCodIdiomaIso2" value="<?php echo $cOpcionesEjemplos->getCodIdiomaIso2()?>" />
	<input type="hidden" name="fFecAlta" value="<?php echo $cOpcionesEjemplos->getFecAlta();?>" />
	<input type="hidden" name="fFecMod" value="<?php echo $cOpcionesEjemplos->getFecMod();?>" />
	<input type="hidden" name="fUsuAlta" value="<?php echo $cOpcionesEjemplos->getUsuAlta();?>" />
	<input type="hidden" name="fUsuMod" value="<?php echo $cOpcionesEjemplos->getUsuMod();?>" />
	<input type="hidden" name="LSTIdOpcionHast" value="<?php echo (isset($_POST['LSTIdOpcionHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdOpcionHast']) : "";?>" />
	<input type="hidden" name="LSTIdOpcion" value="<?php echo (isset($_POST['LSTIdOpcion'])) ? $cUtilidades->validaXSS($_POST['LSTIdOpcion']) : "";?>" />
	<input type="hidden" name="LSTIdPruebaHast" value="<?php echo (isset($_POST['LSTIdPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPruebaHast']) : "";?>" />
	<input type="hidden" name="LSTIdPrueba" value="<?php echo (isset($_POST['LSTIdPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTIdPrueba']) : "";?>" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo (isset($_POST['LSTCodIdiomaIso2'])) ? $cUtilidades->validaXSS($_POST['LSTCodIdiomaIso2']) : "";?>" />
	<input type="hidden" name="LSTIdEjemploHast" value="<?php echo (isset($_POST['LSTIdEjemploHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEjemploHast']) : "";?>" />
	<input type="hidden" name="LSTIdEjemplo" value="<?php echo (isset($_POST['LSTIdEjemplo'])) ? $cUtilidades->validaXSS($_POST['LSTIdEjemplo']) : "";?>" />
	<input type="hidden" name="LSTDescripcion" value="<?php echo (isset($_POST['LSTDescripcion'])) ? $cUtilidades->validaXSS($_POST['LSTDescripcion']) : "";?>" />
	<input type="hidden" name="LSTCodigo" value="<?php echo (isset($_POST['LSTCodigo'])) ? $cUtilidades->validaXSS($_POST['LSTCodigo']) : "";?>" />
	<input type="hidden" name="LSTBajaLogHast" value="<?php echo (isset($_POST['LSTBajaLogHast'])) ? $cUtilidades->validaXSS($_POST['LSTBajaLogHast']) : "";?>" />
	<input type="hidden" name="LSTBajaLog" value="<?php echo (isset($_POST['LSTBajaLog'])) ? $cUtilidades->validaXSS($_POST['LSTBajaLog']) : "";?>" />
	<input type="hidden" name="LSTFecAltaHast" value="<?php echo (isset($_POST['LSTFecAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecAltaHast']) : "";?>" />
	<input type="hidden" name="LSTFecAlta" value="<?php echo (isset($_POST['LSTFecAlta'])) ? $cUtilidades->validaXSS($_POST['LSTFecAlta']) : "";?>" />
	<input type="hidden" name="LSTFecModHast" value="<?php echo (isset($_POST['LSTFecModHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecModHast']) : "";?>" />
	<input type="hidden" name="LSTFecMod" value="<?php echo (isset($_POST['LSTFecMod'])) ? $cUtilidades->validaXSS($_POST['LSTFecMod']) : "";?>" />
	<input type="hidden" name="LSTUsuAltaHast" value="<?php echo (isset($_POST['LSTUsuAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAltaHast']) : "";?>" />
	<input type="hidden" name="LSTUsuAlta" value="<?php echo (isset($_POST['LSTUsuAlta'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAlta']) : "";?>" />
	<input type="hidden" name="LSTUsuModHast" value="<?php echo (isset($_POST['LSTUsuModHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuModHast']) : "";?>" />
	<input type="hidden" name="LSTUsuMod" value="<?php echo (isset($_POST['LSTUsuMod'])) ? $cUtilidades->validaXSS($_POST['LSTUsuMod']) : "";?>" />
	<input type="hidden" name="LSTOrderOpEjBy" value="<?php echo (isset($_POST['LSTOrderOpEjBy'])) ? $cUtilidades->validaXSS($_POST['LSTOrderOpEjBy']) : "";?>" />
	<input type="hidden" name="LSTOrderOpEj" value="<?php echo (isset($_POST['LSTOrderOpEj'])) ? $cUtilidades->validaXSS($_POST['LSTOrderOpEj']) : "";?>" />
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $cUtilidades->validaXSS($_POST['LSTLineasPagina']) : constant("CNF_LINEAS_PAGINA");?>" />
	<input type="hidden" name="opcionesejemplos_next_page" value="<?php echo (isset($_POST['opcionesejemplos_next_page'])) ? $cUtilidades->validaXSS($_POST['opcionesejemplos_next_page']) : "1";?>" />
	</div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?></div>
</form>

</body></html>