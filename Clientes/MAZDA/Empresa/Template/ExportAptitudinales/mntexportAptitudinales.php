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
		f.fPasosNext.value = parseInt(f.fPasosNext.value,10) + 1;
		f.LSTFecDesde.value=cFechaFormat(f.LSTFecDesde.value);
		f.LSTFecHasta.value=cFechaFormat(f.LSTFecHasta.value);
		lon();
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vString("<?php echo constant("STR_EMPRESA");?>:",f.LSTIdEmpresa.value,255,true);
	msg +=vString("<?php echo constant("STR_PROCESO");?>:",f.LSTIdProceso.value,255,true);
	msg +=vDate("<?php echo constant("STR_PERÍODO_DE_TIEMPO");?> <?php echo constant("STR_DESDE");?>:",f.LSTFecDesde.value,10,false);
	msg +=vDate("<?php echo constant("STR_PERÍODO_DE_TIEMPO");?> <?php echo constant("STR_HASTA");?>:",f.LSTFecHasta.value,10,false);
	if (f.fIdsPruebas != null){
		msg +=vString("<?php echo constant("STR_PRUEBAS");?>:",f.fIdsPruebas.value,5000,true);
	}
	if (msg == ""){
		//Proceso o rango de fechas es obligatorio
		if (f.LSTIdProceso.value == ""){
			if (f.LSTFecDesde.value == "" || f.LSTFecHasta.value == ""){
				msg +="\tAcote el rango de fechas Desde y Hasta completo o seleccione proceso.\n";
			}
		}
	}
	if (msg != "") {
		alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
		return false;
	}else return true;
}
function abrirCalendario(page, titulo){
	var miC=window.open(page, titulo,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=149,Height=148');
	miC.focus();
}
function Atras(){
	var oCan = document.getElementById("Volver");
	oCan.style.display="none";
	var oCom = document.getElementById("Comenzar");
	oCom.style.display="block";
	var f=document.forms[0];
	f.fPasosNext.value = parseInt(f.fPasosNext.value,10) - 1;
	f.MODO.value=0;
	f.submit();
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
function setIdsPruebas(iCont){
	var f= document.forms[0];
	var sIds = "";
	for(i=0; i < iCont; i++ ){
		if (eval("document.forms[0].LSTIdPrueba"+i)!=null){
			aIdPrueba = eval("document.forms[0].LSTIdPrueba"+i);
			if (aIdPrueba.type == "checkbox"){
				if (aIdPrueba.checked){
					sIds +="," + aIdPrueba.value; 
				}
			}
		}
	}
	if (sIds != ""){
		sIds = sIds.substring(1, sIds.length);
		f.fIdsPruebas.value = sIds;
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
<body onload="_body_onload();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');cambiaIdEmpresa();" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
	
		<form name="form" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar('<?php echo $_POST['MODO'];?>');">
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
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTSigno"><?php echo constant("STR_EMPRESA");?></label>&nbsp;</td>
					<td><?php $comboEMPRESAS->setNombre("LSTIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLComboMenu("1","obliga",""," onchange=\"javascript:cambiaIdEmpresa()\"","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdProceso"><?php echo constant("STR_PROCESO");?></label>&nbsp;</td>
						<td><div id="comboIdProceso"><?php $comboPROCESOS->setNombre("LSTIdProceso");?><?php echo $comboPROCESOS->getHTMLComboNull("1","obliga","","onchange=\"javascript:cambiaIdProceso()\" ","");?></div></td>
				</tr>
				<tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_PRUEBAS");?>&nbsp;</td>
						<td>
							<div id="pruebasAptitudinal">
								<?php echo constant("STR_SELECCIONE_UNA_EMPRESA_Y_PROCESO")?>.
							</div>
					</td>
				</tr>
					
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFecDesde"><?php echo constant("STR_PERÍODO_DE_TIEMPO");?></label>&nbsp;</td>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>

								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><input type="text" id="LSTFecDesde" name="LSTFecDesde" value="01/01/2012" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="graf/sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<?php 
								if (!empty($_REQUEST['LSTFecHasta'])){
									$aInicio=explode(" ", $_REQUEST['LSTFecHasta']);
									$_REQUEST['LSTFecHasta'] = $conn->UserDate($_REQUEST['LSTFecHasta'],constant("USR_FECHA"),false);
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecHasta','Calendario');"><img src="graf/icon_calendario.gif" width="22" height="18" border="0" alt="Calendario" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecHasta" name="LSTFecHasta" value="<?php echo (!empty($_REQUEST['LSTFecHasta'])) ? $_REQUEST['LSTFecHasta'] : "31/12/9999";?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3" ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
			</table>
			<div id="Volver" <?php echo ($sPaso > 0) ? "" : "style=\"display:none\""?>>
			<div id="Comenzar" <?php echo ($sPaso > 0) ? "style=\"display:none\"" : ""?>>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td><input type="submit" class="botones" id="bid-ok" name="fBtnAdd" value="<?php echo constant("STR_GENERAR");?>" /></td>
<!-- 				<td><?php echo constant("STR_PASSWORD");?>:&nbsp;<input type="password" class="obliga" id="pass" name="fPass" value="<?php echo (!empty($_REQUEST['fPass'])) ? $_REQUEST['fPass'] : "";?>" /></td> -->
				</tr>
			</table>
			</div>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td class="error" colspan="2" ><?php echo $sMensaje;?></td>
				</tr>
				<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td><input type="button" class="botones" id="bid-volver" name="fBtnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="javascript:Atras();" /></td>
					<td width="140" >&nbsp;
					<?php
					if ($bExportar){
					?>
						<a href="toExcelAptitudinal.php?fSQLtoEXCEL=<?php echo base64_encode("SELECT * FROM export_especial WHERE id=" . $newId . " AND empresa='" . $sEmpresa . "' " . constant("CHAR_SEPARA") . "export_especial". constant("CHAR_SEPARA") . $_POST['fIdsPruebas']);?>"><img src="<?php echo constant('DIR_WS_GRAF');?>excel.gif" width="34" height="35" align="right" border="0" alt="<?php echo constant("STR_EXPORTAR_A_EXCEL");?>" /></a>
					<?php 
					}
					?>
					</td>
				</tr>
			</table>
			</div>

			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td><input type="submit" class="botones" id="bid-ok" name="fBtnAdd" value="<?php echo constant("STR_GENERAR");?>" /></td>
				</tr>
			</table>
	</div>
</div>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdEmpresa(){
								var f= document.forms[0];
								$("#comboIdProceso").show().load("jQuery.php",{sPG:"comboprocesos",bBus:"1",multiple:"0",nLineas:"1",bObliga:"1",bgColor:"<?php echo constant("BG_COLOR");?>",fNombreCampo:"LSTIdProceso",fJSProp:"cambiaIdProceso",LSTIdEmpresa:f.LSTIdEmpresa.value,vSelected:"", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"},function(){$("#comboIdCandidato").show().load("jQuery.php",{sPG:"combocandidatos",bBus:"1",multiple:"1",nLineas:"20",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdCandidato",fJSProp:"cambiaIdCandidato",LSTIdEmpresa:f.LSTIdEmpresa.value,LSTIdProceso:f.LSTIdProceso.value,vSelected:"", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaIdProceso(){
								var f= document.forms[0];
								$("#pruebasAptitudinal").show().load("jQuery.php",{sPG:"pruebasAptitudinales",bBus:"1",multiple:"0",nLineas:"1",bObliga:"1",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdPruebas",fJSProp:"cambiaPruebasAptitudinales",LSTIdEmpresa:f.LSTIdEmpresa.value,LSTIdProceso:f.LSTIdProceso.value,vSelected:"", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
							}
							//]]>
						</script>
						<script language="javascript" type="text/javascript">
							//<![CDATA[
							function cambiaPruebasAptitudinales(){

							}
							//]]>
						</script>
	<input type="hidden" name="fPaso" value="<?php echo $sPaso;?>" />
	<input type="hidden" name="fPasosNext" value="<?php echo $_POST['fPasosNext'];?>" />
	<input type="hidden" name="LSTIdEmpresaOrigen" value="<?php echo $_REQUEST['LSTIdEmpresaOrigen'];?>" />

	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_BUSCAR");?>" />
	<input type="hidden" name="signos_next_page" value="1" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" /></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>