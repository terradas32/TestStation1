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
		lon();
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
//	msg +=vString("<?php echo constant("STR_PASSWORD");?>:",f.fPass.value,255,true);
	msg +=vString("<?php echo constant("STR_EMPRESA");?>:",f.LSTIdEmpresa.value,255,true);
	msg +=vString("<?php echo constant("STR_PROCESO");?>:",f.LSTIdProceso.value,255,false);
	msg +=vDate("<?php echo constant("STR_PERÍODO_DE_TIEMPO");?> <?php echo constant("STR_DESDE");?>:",f.LSTFecDesde.value,10,false);
	msg +=vDate("<?php echo constant("STR_PERÍODO_DE_TIEMPO");?> <?php echo constant("STR_HASTA");?>:",f.LSTFecHasta.value,10,false);
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
}
function descuentaDongles(){
	alert("*1*");
		$("#consumos").show().load("jQueryMs.php",{sPG:"Ms_descuentaDongles",fDongles:"<?php echo $iConsumos;?>",fEmpresa:"<?php echo $sEmpresa;?>"}).fadeIn("slow");
		alert("*2*");	
}
//]]>
</script>
<script language="javascript" type="text/javascript">
//<![CDATA[
function _body_onload(){	loff();	}
function _body_onunload(){	lon();	}
//]]>
</script>
</head>
<body onload="_body_onload();cambiaIdEmpresa();" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
	
		<form name="form" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar();">
<?php 
$HELP="xx";
?>
<div id="contenedor">
	<div id="envoltura">
		<div id="contenido" style="margin:0 0px 0 20px;">
		<div style="width: 100%">
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tr>
					<td align="center" colspan="2" class="naranjab">PROCESO EXPORTACION EXCEL TEST-STATION</td>
				</tr>
				<tr>
					<td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td>
				</tr>
				<tr>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdEmpresa">Empresa</label>&nbsp;</td>
					<td><?php $comboEMPRESAS->setNombre("LSTIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLComboMenu("1","obliga",(!empty($_REQUEST['LSTIdEmpresa'])) ? $_REQUEST['LSTIdEmpresa'] : ""," onchange=\"javascript:cambiaIdEmpresa()\"","");?></td>
				</tr>
				<tr>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdProceso">Proceso</label>&nbsp;</td>
					<td><div id="comboIdProceso"><?php $comboPROCESOS->setNombre("LSTIdProceso");?><?php echo $comboPROCESOS->getHTMLComboNull("1","cajatexto",(!empty($_REQUEST['LSTIdProceso'])) ? $_REQUEST['LSTIdProceso'] : "","onchange=\"javascript:cambiaIdProceso()\" ","");?></div></td>
				</tr>
				<tr>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFecDesde"><?php echo constant("STR_PERÍODO_DE_TIEMPO");?></label>&nbsp;</td>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>

								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><input type="text" id="LSTFecDesde" name="LSTFecDesde" value="01/01/2012" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="graf/sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<?php 
								if ($_REQUEST['LSTFecHasta'] != "" && $_REQUEST['LSTFecHasta'] != "0000-00-00" && $cEntidad->getFechaInicio() != "0000-00-00 00:00:00"){
									$aInicio=explode(" ", $_REQUEST['LSTFecHasta']);
									$sHoraInicio= substr($aInicio[1], 0, 5);  // HH:MM
									$_REQUEST['LSTFecHasta'] = $conn->UserDate($_REQUEST['LSTFecHasta'],constant("USR_FECHA"),false);
								}
								?>
								<td><?php echo $_REQUEST['LSTFecHasta'];exit;?><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecHasta','Calendario');"><img src="graf/icon_calendario.gif" width="22" height="18" border="0" alt="Calendario" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecHasta" name="LSTFecHasta" value="<?php echo (!empty($_REQUEST['LSTFecHasta'])) ? $_REQUEST['LSTFecHasta'] : "31/12/9998";?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
			</table>
			<div id="Volver" <?php echo ($sPaso > 0) ? "" : "style=\"display:none\""?>>
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
<!--						<a <?php if ($bPrepago){?>onclick="javascript:descuentaDongles();"<?php }?> href="toExcel.php?fSQLtoEXCEL=<?php echo base64_encode("SELECT * FROM export_especial WHERE id=" . $newId . " AND empresa='" . $sEmpresa . "' " . constant("CHAR_SEPARA") . "export_especial");?>"><img src="<?php echo constant('DIR_WS_GRAF');?>excel.gif" width="34" height="35" align="right" border="0" alt="<?php echo constant("STR_EXPORTAR_A_EXCEL");?>" /></a>-->
						<a href="toExcel.php?fSQLtoEXCEL=<?php echo base64_encode("SELECT * FROM export_especial WHERE id=" . $newId . " AND empresa='" . $sEmpresa . "' " . constant("CHAR_SEPARA") . "export_especial");?>"><img src="<?php echo constant('DIR_WS_GRAF');?>excel.gif" width="34" height="35" align="right" border="0" alt="<?php echo constant("STR_EXPORTAR_A_EXCEL");?>" /></a>
					<?php 
					}
					?>
					</td>
				</tr>
			</table>
			</div>
			<div id="consumos">&nbsp;</div>
			<div id="Comenzar" <?php echo ($sPaso > 0) ? "style=\"display:none\"" : ""?>>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td><input type="submit" class="botones" id="bid-ok" name="fBtnAdd" value="<?php echo constant("STR_GENERAR");?>" /></td>
<!-- 				<td><?php echo constant("STR_PASSWORD");?>:&nbsp;<input type="password" class="obliga" id="pass" name="fPass" value="<?php echo (!empty($_REQUEST['fPass'])) ? $_REQUEST['fPass'] : "";?>" /></td> -->
				</tr>
			</table>
			</div>
	</div>
</div>
	<input type="hidden" name="fPaso" value="<?php echo $sPaso;?>" />
	<input type="hidden" name="fPasosNext" value="<?php echo $_POST['fPasosNext'];?>" />
	<input type="hidden" name="LSTIdEmpresaOrigen" value="<?php echo $_REQUEST['LSTIdEmpresaOrigen'];?>" />
	
</div>
	<script language="javascript" type="text/javascript">
		//<![CDATA[
		function cambiaIdEmpresa(){
			var f= document.forms[0];
			$("#comboIdProceso").show().load("jQueryMs.php",{sPG:"Ms_comboprocesos",bBus:"1",multiple:"0",nLineas:"1",bObliga:"0",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"LSTIdProceso",fJSProp:"cambiaIdProceso",LSTIdEmpresa:f.LSTIdEmpresa.value,vSelected:"<?php echo (!empty($_REQUEST['LSTIdProceso'])) ? $_REQUEST['LSTIdProceso'] : "";?>"}).fadeIn("slow");
		}
		//]]>
	</script>
	<script language="javascript" type="text/javascript">
		//<![CDATA[
		function cambiaIdProceso(){								
		}
		//]]>
	</script>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>