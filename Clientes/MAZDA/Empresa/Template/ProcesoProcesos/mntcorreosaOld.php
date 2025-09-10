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
		return true;
	}else	return false;
}
function enviarNuevo(modo)
{
	var f=document.forms[0];
	f.MODO.value =modo;
	f.submit();
}
function validaForm(cuerpo)
{
	var f=document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo constant("STR_TIPO_CORREO");?>:",f.fIdTipoCorreoNew.value,11,true);
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombreNew.value,255,true);
	msg +=vString("<?php echo constant("STR_DESCRIPCION");?>:",f.fDescripcionNew.value,1000,false);
	msg +=vString("<?php echo constant("STR_ASUNTO");?>:",f.fAsuntoNew.value,255,true);
	msg +=vString("<?php echo constant("STR_CUERPO");?>:",cuerpo,16777215,true);
if (msg != "") {
	jAlert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.","<?php echo constant("STR_NOTIFICACION");?>");
	return false;
}else return true;
}
function validaFormSinTipo(cuerpo)
{
	var f=document.forms[0];
	var msg="";
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombreNew.value,255,true);
	msg +=vString("<?php echo constant("STR_DESCRIPCION");?>:",f.fDescripcionNew.value,1000,false);
	msg +=vString("<?php echo constant("STR_ASUNTO");?>:",f.fAsuntoNew.value,255,true);
	msg +=vString("<?php echo constant("STR_CUERPO");?>:",cuerpo,16777215,true);
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
function cambiacorreos(pag)
{
	var f = document.forms[0];
	f.sPG.value = pag;

	var paginacargada = "jQuery.php";
	f.MODO.value="<?php echo constant('MNT_CARGACANDIDATOS');?>";
	$("div#muestracorreo").empty();
	$("div#combocorreos").load(paginacargada, $("form").serializeArray());
}
function nuevaplantilla()
{
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	$("div#muestracorreo").hide().load(paginacargada,{fNuevo:"1",MODO:"<?php echo constant('MNT_NUEVOCORREO')?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
}
function cancela()
{
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	$("div#muestracorreo").empty();
}
function cargaplantilla()
{
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";

	if(f.fIdCorreo.value!=""){
		$("div#muestracorreo").hide().load(paginacargada,{fConsulta:"1",fIdTipoCorreo:f.fIdTipoCorreo.value,fIdCorreo:f.fIdCorreo.value,MODO:"<?php echo constant('MNT_NUEVOCORREO')?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
	}else{
		$("div#muestracorreo").empty();
	}
}
function consultacorreoproceso(idEmpresa,idProceso,idTipoCorreo,idCorreo){
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";

	$("div#muestracorreo").hide().load(paginacargada,
	{
		fConsultaAsignados:"1",
		fIdEmpresaAsig:idEmpresa,
		fIdProcesoAsig:idProceso,
		fIdTipoCorreoAsig:idTipoCorreo,
		fIdCorreoAsig:idCorreo,
		MODO:"<?php echo constant('MNT_NUEVOCORREO')?>",
		fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
	}).fadeIn("slow");

}
function borraasignados(idEmpresa,idProceso,idTipoCorreo,idCorreo){

	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	$("div#listacorreos").hide().load(paginacargada,
	{
		fBorra:"1",
		fIdEmpresaAsig:idEmpresa,
		fIdProcesoAsig:idProceso,
		fIdTipoCorreoAsig:idTipoCorreo,
		fIdCorreoAsig:idCorreo,
		MODO:"<?php echo constant('MNT_LISTACORREOS')?>",
		fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
	}).fadeIn("slow");
}
function listacorreos()
{
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	$("div#listacorreos").hide().load(paginacargada,
			{fIdProceso: f.fIdProceso.value,
			fIdEmpresa: f.fIdEmpresa.value,
			MODO:"<?php echo constant('MNT_LISTACORREOS')?>",
			fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }
	).fadeIn("slow");
}
function guardaplantilla(){
	var f = document.forms[0];

  var myf = $('#fCuerpoNew_ifr');
  var editorContent = $('#tinymce[data-id="fCuerpoNew"]', myf.contents()).html();
	//alert(editorContent);

	var paginacargada = "ProcesoProcesos.php";
	if(f.fIdTipoCorreoNew != null){
		if(validaForm(editorContent)){
			$("div#listacorreos").hide().load(paginacargada,
				{
					fAccion:f.fAccion.value,
					fIdProceso: f.fIdProceso.value,
					fIdEmpresa: f.fIdEmpresa.value,
					fIdTipoCorreo: f.fIdTipoCorreoNew.value,
					fIdCorreo: f.fIdCorreo.value,
					fAsuntoNew: f.fAsuntoNew.value,
					fNombreNew: f.fNombreNew.value,
					fDescripcionNew: f.fDescripcionNew.value,
					fCuerpoNew: editorContent,
					MODO:"<?php echo constant('MNT_LISTACORREOS')?>",
					fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
				}
			).fadeIn("slow");
		}

	}else{
		if(validaFormSinTipo(editorContent)){
			$("div#listacorreos").hide().load(paginacargada,
				{
					fAccion:f.fAccion.value,
					fIdProceso: f.fIdProceso.value,
					fIdEmpresa: f.fIdEmpresa.value,
					fIdTipoCorreo: f.fIdTipoCorreo.value,
					fIdCorreo: f.fIdCorreo.value,
					fAsuntoNew: f.fAsuntoNew.value,
					fNombreNew: f.fNombreNew.value,
					fDescripcionNew: f.fDescripcionNew.value,
					fCuerpoNew: editorContent,
					MODO:"<?php echo constant('MNT_LISTACORREOS')?>",
					fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
				}
			).fadeIn("slow");
		}
	}
}
function guardaasignados(){
	var f = document.forms[0];

  var myf = $('#fCuerpoNew_ifr');
  var editorContent = $('#tinymce[data-id="fCuerpoNew"]', myf.contents()).html();
	//alert(editorContent);

	var paginacargada = "ProcesoProcesos.php";

		if(validaForm(editorContent)){
			$("div#listacorreos").hide().load(paginacargada,
				{
					fAccion:f.fAccion.value,
					fIdCorreoNew:f.fIdCorreoNew.value,
					fIdProceso: f.fIdProceso.value,
					fIdEmpresa: f.fIdEmpresa.value,
					fIdTipoCorreo: f.fIdTipoCorreoNew.value,
					fAsuntoNew: f.fAsuntoNew.value,
					fNombreNew: f.fNombreNew.value,
					fDescripcionNew: f.fDescripcionNew.value,
					fCuerpoNew: editorContent,
					MODO:"<?php echo constant('MNT_LISTACORREOS')?>",
					fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
				}
			).fadeIn("slow");
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
<body onload="_body_onload();listacorreos();cambiacorreos('combocorreos');block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');"  onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0"  title="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" alt="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>

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
			<table cellspacing="3" cellpadding="0" width="100%" border="0">
				<tr>
					<td colspan="2" width="100%" style="border-bottom: 1px solid #000000; height:35px">
						1. Datos del proceso ---> 2. Asignación de pruebas y baremos ---> 3. Candidatos ---> <b style="color:#FFB200">4. Asignación de correos</b>
					</td>
				</tr>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" width="100%" border="0">
							<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr>
								<td width="40%" style="border-right: 1px solid #000000;" valign="top">
									<table width="100%" style="padding: 5px;">
										<tr>
											<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
											<td nowrap="nowrap" width="50" class="negrob" valign="top"><?php echo constant("STR_TIPO_CORREO");?>&nbsp;</td>
											<td width="175"><?php $comboTIPOS_CORREOS->setNombre("fIdTipoCorreo");?><?php echo $comboTIPOS_CORREOS->getHTMLCombo("1","obliga",""," onchange=\"javascript:cambiacorreos('combocorreos')\"","");?></td>
										</tr>
										<tr>
											<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
											<td nowrap="nowrap" width="50" class="negrob" valign="top"><?php echo constant("STR_NOMBRE");?>&nbsp;</td>
											<td width="175">
												<div id="combocorreos" style="width:175"></div>
											</td>
										</tr>
										<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
										<tr><td colspan="3"><input type="button" class="botones" name="fNuevo" value="Nuevo" onclick="javascript:nuevaplantilla();"/></td></tr>
										<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
									</table>
								</td>
								<td width="60%" valign="top">
									<div id="listacorreos" style="padding: 5px;"></div>
								</td>
							</tr>
							<tr><td colspan="2" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr><td colspan="2" bgcolor="#000000" style="height:1px;"></td></tr>
							<tr><td colspan="2" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr>
								<td colspan="2">
									<div id="muestracorreo"></div>
								</td>
							</tr>
							<tr><td colspan="2" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
							<tr><td colspan="2" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>

					</table>
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="enviarNuevo(<?php echo constant('MNT_ANIADECANDIDATOS')?>);" /></td>
							<td><!-- <input type="submit" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ACEPTAR");?>" onclick="document.forms[0].MODO.value=<?php echo constant('MNT_ESCOGECORREOS')?>"/> --></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
</div>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />

	<input type="hidden" name="fBorra" value="" />
	<input type="hidden" name="fConsulta" value="" />
	<input type="hidden" name="fNuevo" value="" />
	<input type="hidden" name="fConsultaAsignados" value="" />
	<input type="hidden" name="fIdCorreoAsig" value="" />
	<input type="hidden" name="fIdEmpresaAsig" value="" />
	<input type="hidden" name="fIdProcesoAsig" value="" />
	<input type="hidden" name="fIdTipoCorreoAsig" value="" />
	<input type="hidden" name="vaPruebas" value="" />
	<input type="hidden" name="fIdEmpresa" value="<?php echo (isset($_POST['fIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['fIdEmpresa']) : "";?>" />
	<input type="hidden" name="fIdProceso" value="<?php echo (isset($_POST['fIdProceso'])) ? $cUtilidades->validaXSS($_POST['fIdProceso']) : "";?>" />
	<input type="hidden" name="fCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" />
	<input type="hidden" name="LSTIdTipoCorreoHast" value="<?php echo (isset($_POST['LSTIdTipoCorreoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoCorreoHast']) : "";?>" />
	<input type="hidden" name="LSTIdTipoCorreo" value="<?php echo (isset($_POST['LSTIdTipoCorreo'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoCorreo']) : "";?>" />
	<input type="hidden" name="LSTIdCorreoHast" value="<?php echo (isset($_POST['LSTIdCorreoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdCorreoHast']) : "";?>" />
	<input type="hidden" name="LSTIdCorreo" value="<?php echo (isset($_POST['LSTIdCorreo'])) ? $cUtilidades->validaXSS($_POST['LSTIdCorreo']) : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $cUtilidades->validaXSS($_POST['LSTNombre']) : "";?>" />
	<input type="hidden" name="LSTDescripcion" value="<?php echo (isset($_POST['LSTDescripcion'])) ? $cUtilidades->validaXSS($_POST['LSTDescripcion']) : "";?>" />
	<input type="hidden" name="LSTCuerpo" value="<?php echo (isset($_POST['LSTCuerpo'])) ? $cUtilidades->validaXSS($_POST['LSTCuerpo']) : "";?>" />
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
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $cUtilidades->validaXSS($_POST['LSTLineasPagina']) : constant("CNF_LINEAS_PAGINA");?>" /></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?></div>
</form>

</body></html>
