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
	<script language="javascript" type="text/javascript" src="codigo/ajaxfileupload.js"></script>
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
function cambiaCodificacion(){
	var f= document.forms[0];
	f.fCodificacionSel.value = f.fCodificacion.value;
}
function cambiaEntrecomillado(){
	var f= document.forms[0];
	f.fEntrecomilladoSel.value = f.fEntrecomillado.value;
}
function cambiaSeparador(){
	var f= document.forms[0];
	f.fSeparadorCamposSel.value = f.fSeparadorCampos.value;
}
function cambiaSinCabeceras(){
	var f= document.forms[0];
	f.fCabecerasSel.value = f.fCabeceras.value;
}
function enviarNuevo(modo)
{
	var f=document.forms[0];
	f.MODO.value =modo;
	f.submit();
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombre.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO1");?>:",f.fApellido1.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO2");?>:",f.fApellido2.value,255,false);
	msg +=vEmail("<?php echo constant("STR_EMAIL");?>:",f.fMail.value,255,true);
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
function listacandidato(){
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	$("div#listadecandidatos").hide().load(paginacargada,{fIdProceso: f.fIdProceso.value,fIdEmpresa: f.fIdEmpresa.value,MODO:"<?php echo constant('MNT_LISTACANDIDATOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
}
function aniadecandidato(){
	var f = document.forms[0];
	var msg="";
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombre.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO1");?>:",f.fApellido1.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO2");?>:",f.fApellido2.value,255,false);
	msg +=vEmail("<?php echo constant("STR_EMAIL");?>:",f.fMail.value,255,true);
	if (msg != "") {
		alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
	}else{
		var paginacargada = "ProcesoProcesos.php";
		$("div#listadecandidatos").hide().load(paginacargada,{fIdProceso: f.fIdProceso.value,fIdEmpresa: f.fIdEmpresa.value,fNombre: f.fNombre.value, fApellido1: f.fApellido1.value, fApellido2: f.fApellido2.value, fDni: f.fDni.value, fMail: f.fMail.value,fPagoTpv: f.fPagoTpv.value,fAniade:"1",MODO:"<?php echo constant('MNT_LISTACANDIDATOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" },function(){var f = document.forms[0];f.fNombre.value="";f.fApellido1.value="";f.fApellido2.value="";f.fDni.value="";f.fMail.value="";}).fadeIn("slow");
	}
}
function aniadeblind(){
	var f = document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo "Nº de Altas";?>:",f.fNumAltas.value,11,true);
	if (msg != "") {
		alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
	}else{
		var paginacargada = "ProcesoProcesos.php";
		$("div#listadecandidatos").hide().load(paginacargada,{fIdProceso: f.fIdProceso.value,fIdEmpresa: f.fIdEmpresa.value,fNumAltas: f.fNumAltas.value,fPagoTpv: f.fPagoTpv.value,fBlind:"1",MODO:"<?php echo constant('MNT_LISTACANDIDATOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" },function(){var f = document.forms[0];f.fNumAltas.value="";}).fadeIn("slow");
	}
}
function volcar(){
	var f = document.forms[0];
	var msg="";

	var paginacargada = "ProcesoProcesos.php";
	$("div#listadecandidatos").hide().load(paginacargada,{fIdProceso: f.fIdProceso.value,fIdEmpresa: f.fIdEmpresa.value,fVolcar:"1",MODO:"<?php echo constant('MNT_LISTACANDIDATOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");

}
function borracandidato(idCandidato){
	var f = document.forms[0];

	if(confirm('<?php echo constant("DEL_GENERICO");?>')){
		var paginacargada = "ProcesoProcesos.php";
		$("div#listadecandidatos").hide().load(paginacargada,{fIdProceso: f.fIdProceso.value,fIdEmpresa: f.fIdEmpresa.value,fIdCandidato: idCandidato,fBorra:"1",MODO:"<?php echo constant('MNT_LISTACANDIDATOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
	}
}
function volcar(){
	var f = document.forms[0];

	var paginacargada = "ProcesoProcesos.php";
	$("div#listadecandidatos").hide().load(paginacargada,{fIdProceso: f.fIdProceso.value,fIdEmpresa: f.fIdEmpresa.value,fVolcar:"1",MODO:"<?php echo constant('MNT_LISTACANDIDATOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
	$("div#carga").empty();
}
function cargaalta(){
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	if(f.fTipoAlta.value==""){
		$("div#carga").empty();
	}else{
		$("div#carga").hide().load(paginacargada, {fTipoAlta:f.fTipoAlta.value, fIdEmpresa: f.fIdEmpresa.value, fIdProceso: f.fIdProceso.value,MODO:"<?php echo constant('MNT_ANIADECANDIDATOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
	}
}
function definirCampos(){
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	f.MODO.value="<?php echo constant('MNT_CARGACANDIDATOS_DEFINICION');?>";
	$("div#carga").hide().load(paginacargada, $("form").serializeArray()).fadeIn("slow");
}

function ajaxFileUpload()
{
	var retorno = true;
	$("#loading")
	.ajaxStart(function(){
		$(this).show();
	})
	.ajaxComplete(function(){
		$(this).hide();
		var f = document.forms[0];
		var paginacargada = "ProcesoProcesos.php";
		f.MODO.value="<?php echo constant('MNT_CARGACANDIDATOS');?>";
		$("div#carga").hide().load(paginacargada, $("form").serializeArray()).fadeIn("slow");
	});
	$.ajaxFileUpload
	(
		{
			url:'<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? constant("HTTPS_SERVER") : constant("HTTP_SERVER")) ?>cargafichero.php',
			secureuri:false,
			fileElementId:'fFichero',
			dataType: 'json',
			success: function (data, status)
			{
				if(typeof(data.error) != 'undefined')
				{
					if(data.error != '')
					{
						//alert(data.error);
						retorno = false;
					}else
					{
						//alert(data.msg);
						retorno = true;
					}
				}
			},
			error: function (data, status, e)
			{
				//alert(e);
				retorno = false;
			}
		}
	)
	return retorno;
}
function volvercandidatomasivo(){

	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	f.MODO.value="<?php echo constant('MNT_CARGACANDIDATOS');?>";
	$("div#carga").hide().load(paginacargada, $("form").serializeArray()).fadeIn("slow");

}
function finalizarCandidatoMasivo(){
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	f.MODO.value="<?php echo constant('MNT_CARGACANDIDATOS_FINALIZAR');?>";
	$("div#carga").hide().load(paginacargada, $("form").serializeArray()).fadeIn("slow");
}

function aniadecandidatomasivo(){
	var f = document.forms[0];
	if (f.fFichero.value != ""){
		var paginacargada = "ProcesoProcesos.php";
		if(ajaxFileUpload()){

		}
	}else{
		alert("Introduzca un fichero CSV.");
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
<body onload="_body_onload();listacandidato();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');"  onunload="_body_onunload();">
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
						<ul class="listaProceso">
							<li><span><img src="./graf/iconsMenu/Tools.png" title="Datos proceso" alt="Datos proceso" /></span>Datos Proceso</li>
							<li class="mArrow">&nbsp;</li>
							<li><span><img src="./graf/iconsMenu/Folder.png" title="Pruebas" alt="Pruebas" /></span>Pruebas</li>
							<li class="mArrow">&nbsp;</li>
							<li class="mActivo"><span><img src="./graf/iconsMenu/User.png" title="Candidatos" alt="Candidatos" /></span><b style="color:#FFB200">Candidatos</b></li>
							<li class="mArrow">&nbsp;</li>
							<li><span><img src="./graf/iconsMenu/mail.png" title="Comunicación" alt="Comunicación" /></span>Comunicación</li>
						</ul>
					</td>
				</tr>
				<tr>
					<td width="20%" valign="top" style="border-right: 2px solid #000000;">
						<ul>
							<li>
								<a href="#" onclick ="javascript:enviarNuevo(<?php echo constant('MNT_NUEVO') ?>)" class="negrob">Nuevo proceso</a>
							</li>
							<li>
								<a href="#" onclick ="javascript:enviarNuevo(<?php echo constant('MNT_CONSULTAR') ?>)" class="negrob">Detalle del proceso</a>
							</li>
							<li>
								<a href="#" onclick ="javascript:enviarNuevo(<?php echo constant('MNT_LISTAR') ?>)" class="negrob">Volver a la lista</a>
							</li>
							<li>
								<a href="#" onclick ="javascript:enviarNuevo(0)" class="negrob">Volver a buscar</a>
							</li>
							<li>
								<a href="#" onclick="document.forms[0].vaPruebas.value=1;document.forms[0].MODO.value=<?php echo constant('MNT_MODIFICAR')?>;document.forms[0].submit();" class="negrob">Volver a las pruebas</a>
							</li>
						</ul>
					</td>
					<td width="80%">
						<table cellspacing="0" cellpadding="0" width="100%" border="0">
							<tr><td colspan="3" align="center" class="negrob">Selecciona el tipo de carga de candidatos</td></tr>
							<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr>
								<td colspan="3" align="center">
									<select name="fTipoAlta" onchange="javascript:cargaalta();">
										<option value=""><?php echo constant('SLC_OPCION');?></option>
										<option value="1">Carga masiva</option>
										<option value="2">Alta manual</option>
										<?php
										if (strtoupper($cEmpTPV->getAltaCiega()) == "ON"){
											echo '<option value="3">Alta anónima</option>';
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<div id="carga"></div>
								</td>
							</tr>
							<tr>
								<td colspan="3" style="height:10px;" >
									&nbsp;
								</td>
							</tr>
							<tr>
								<td colspan="3" style="height:1px;" bgcolor="#000000">
								</td>
							</tr>
							<tr>
								<td colspan="3" style="height:10px;" >
									&nbsp;
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<div style="max-height: 300px;overflow-y:scroll;" id="listadecandidatos"></div>
								</td>
							</tr>
							<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
							<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
						</table>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="document.forms[0].vaPruebas.value=1;document.forms[0].MODO.value=<?php echo constant('MNT_MODIFICAR')?>;document.forms[0].submit();" /></td>
								<td><input type="button" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> id="bid-ok" name="fBtnOk" value="Seguir" onclick="javascript:enviarNuevo('<?php echo constant('MNT_ESCOGECORREOS')?>')"/></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
	</div>
</div>
	<input type="hidden" name="fCodificacionSel" value="" />
	<input type="hidden" name="fEntrecomilladoSel" value="" />
	<input type="hidden" name="fSeparadorCamposSel" value="" />
	<input type="hidden" name="fCabecerasSel" value="" />
	<input type="hidden" name="fVolcar" value="" />
	<input type="hidden" name="fMasivo" value="" />
	<input type="hidden" name="fBorra" value="" />
	<input type="hidden" name="fAniade" value="" />
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="vaPruebas" value="" />
	<input type="hidden" name="fIdEmpresa" value="<?php echo (isset($_POST['fIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['fIdEmpresa']) : "";?>" />
	<input type="hidden" name="fIdProceso" value="<?php echo (isset($_POST['fIdProceso'])) ? $cUtilidades->validaXSS($_POST['fIdProceso']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresaHast" value="<?php echo (isset($_POST['LSTIdEmpresaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaHast']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresa" value="<?php echo (isset($_POST['LSTIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresa']) : "";?>" />
	<input type="hidden" name="LSTIdProcesoHast" value="<?php echo (isset($_POST['LSTIdProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdProcesoHast']) : "";?>" />
	<input type="hidden" name="LSTIdProceso" value="<?php echo (isset($_POST['LSTIdProceso'])) ? $cUtilidades->validaXSS($_POST['LSTIdProceso']) : "";?>" />
	<input type="hidden" name="LSTIdCandidatoHast" value="<?php echo (isset($_POST['LSTIdCandidatoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidatoHast']) : "";?>" />
	<input type="hidden" name="LSTIdCandidato" value="<?php echo (isset($_POST['LSTIdCandidato'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidato']) : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $cUtilidades->validaXSS($_POST['LSTNombre']) : "";?>" />
	<input type="hidden" name="LSTApellido1" value="<?php echo (isset($_POST['LSTApellido1'])) ? $cUtilidades->validaXSS($_POST['LSTApellido1']) : "";?>" />
	<input type="hidden" name="LSTApellido2" value="<?php echo (isset($_POST['LSTApellido2'])) ? $cUtilidades->validaXSS($_POST['LSTApellido2']) : "";?>" />
	<input type="hidden" name="LSTDni" value="<?php echo (isset($_POST['LSTDni'])) ? $cUtilidades->validaXSS($_POST['LSTDni']) : "";?>" />
	<input type="hidden" name="LSTMail" value="<?php echo (isset($_POST['LSTMail'])) ? $cUtilidades->validaXSS($_POST['LSTMail']) : "";?>" />
	<input type="hidden" name="LSTPassword" value="<?php echo (isset($_POST['LSTPassword'])) ? $cUtilidades->validaXSS($_POST['LSTPassword']) : "";?>" />
	<input type="hidden" name="LSTSexo" value="<?php echo (isset($_POST['LSTSexo'])) ? $cUtilidades->validaXSS($_POST['LSTSexo']) : "";?>" />
	<input type="hidden" name="LSTFechaNacimientoHast" value="<?php echo (isset($_POST['LSTFechaNacimientoHast'])) ? $cUtilidades->validaXSS($_POST['LSTFechaNacimientoHast']) : "";?>" />
	<input type="hidden" name="LSTFechaNacimiento" value="<?php echo (isset($_POST['LSTFechaNacimiento'])) ? $cUtilidades->validaXSS($_POST['LSTFechaNacimiento']) : "";?>" />
	<input type="hidden" name="LSTIdPaisHast" value="<?php echo (isset($_POST['LSTIdPaisHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPaisHast']) : "";?>" />
	<input type="hidden" name="LSTIdPais" value="<?php echo (isset($_POST['LSTIdPais'])) ? $cUtilidades->validaXSS($_POST['LSTIdPais']) : "";?>" />
	<input type="hidden" name="LSTIdProvinciaHast" value="<?php echo (isset($_POST['LSTIdProvinciaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdProvinciaHast']) : "";?>" />
	<input type="hidden" name="LSTIdProvincia" value="<?php echo (isset($_POST['LSTIdProvincia'])) ? $cUtilidades->validaXSS($_POST['LSTIdProvincia']) : "";?>" />
	<input type="hidden" name="LSTIdCiudadHast" value="<?php echo (isset($_POST['LSTIdCiudadHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdCiudadHast']) : "";?>" />
	<input type="hidden" name="LSTIdCiudad" value="<?php echo (isset($_POST['LSTIdCiudad'])) ? $cUtilidades->validaXSS($_POST['LSTIdCiudad']) : "";?>" />
	<input type="hidden" name="LSTIdZonaHast" value="<?php echo (isset($_POST['LSTIdZonaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdZonaHast']) : "";?>" />
	<input type="hidden" name="LSTIdZona" value="<?php echo (isset($_POST['LSTIdZona'])) ? $cUtilidades->validaXSS($_POST['LSTIdZona']) : "";?>" />
	<input type="hidden" name="LSTDireccion" value="<?php echo (isset($_POST['LSTDireccion'])) ? $cUtilidades->validaXSS($_POST['LSTDireccion']) : "";?>" />
	<input type="hidden" name="LSTCodPostal" value="<?php echo (isset($_POST['LSTCodPostal'])) ? $cUtilidades->validaXSS($_POST['LSTCodPostal']) : "";?>" />
	<input type="hidden" name="LSTIdFormacionHast" value="<?php echo (isset($_POST['LSTIdFormacionHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdFormacionHast']) : "";?>" />
	<input type="hidden" name="LSTIdFormacion" value="<?php echo (isset($_POST['LSTIdFormacion'])) ? $cUtilidades->validaXSS($_POST['LSTIdFormacion']) : "";?>" />
	<input type="hidden" name="LSTIdNivelHast" value="<?php echo (isset($_POST['LSTIdNivelHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdNivelHast']) : "";?>" />
	<input type="hidden" name="LSTIdNivel" value="<?php echo (isset($_POST['LSTIdNivel'])) ? $cUtilidades->validaXSS($_POST['LSTIdNivel']) : "";?>" />
	<input type="hidden" name="LSTIdAreaHast" value="<?php echo (isset($_POST['LSTIdAreaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdAreaHast']) : "";?>" />
	<input type="hidden" name="LSTIdArea" value="<?php echo (isset($_POST['LSTIdArea'])) ? $cUtilidades->validaXSS($_POST['LSTIdArea']) : "";?>" />
	<input type="hidden" name="LSTIdTipoTelefonoHast" value="<?php echo (isset($_POST['LSTIdTipoTelefonoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoTelefonoHast']) : "";?>" />
	<input type="hidden" name="LSTIdTipoTelefono" value="<?php echo (isset($_POST['LSTIdTipoTelefono'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoTelefono']) : "";?>" />
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
	<input type="hidden" name="LSTOrderBy" value="<?php echo (isset($_POST['LSTOrderBy'])) ? $cUtilidades->validaXSS($_POST['LSTOrderBy']) : "";?>" />
	<input type="hidden" name="LSTOrder" value="<?php echo (isset($_POST['LSTOrder'])) ? $cUtilidades->validaXSS($_POST['LSTOrder']) : "";?>" />
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $cUtilidades->validaXSS($_POST['LSTLineasPagina']) : constant("CNF_LINEAS_PAGINA");?>" /></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?></div>
</form>

</body></html>
