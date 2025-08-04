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
		lon();
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vString("<?php echo constant("STR_PASSWORD");?>:",f.fPass.value,255,true);
	msg +=vString("Empresa:",f.LSTIdEmpresa.value,255,true);
	msg +=vDate("Periodo de tiempo Desde:",f.LSTFecDesde.value,10,true);
	msg +=vDate("Periodo de tiempo Hasta:",f.LSTFecHasta.value,10,true);
	if (msg != "") {
		alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
		return false;
	}else return true;
}
function abrirCalendario(page, titulo){
	var miC=window.open(page, titulo,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=149,Height=148');
	miC.focus();
}
function muestraExcel(){
	var f=document.forms[0];
	var oCan = document.getElementById("Excel");
	oCan.style.display="block";
	var oCom = document.getElementById("Comenzar");
	oCom.style.display="none";
	f.fPass.disabled=true;
	f.LSTIdEmpresa.disabled=true;
	f.LSTFecDesde.disabled=true;
	f.LSTFecHasta.disabled=true;
}
function inicio(){
	var f=document.forms[0];
	var oCan = document.getElementById("Excel");
	oCan.style.display="none";
	var oCom = document.getElementById("Comenzar");
	oCom.style.display="block";
	f.fPass.disabled=true;
	f.LSTIdEmpresa.disabled=false;
	f.LSTFecDesde.disabled=false;
	f.LSTFecHasta.disabled=false;
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
<body onload="_body_onload();" onunload="_body_onunload();">
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
					<td ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td>
				</tr>
				<tr>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdEmpresa">Empresa</label>&nbsp;</td>
					<td><?php $comboEMPRESAS->setNombre("LSTIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLCombo("1","obliga",(!empty($_REQUEST['fIdEmpresa'])) ? $_REQUEST['fIdEmpresa'] : "","","");?></td>
				</tr>
				<tr>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFecDesde"><?php echo constant("STR_PERÍODO_DE_TIEMPO");?></label>&nbsp;</td>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>

								<td nowrap="nowrap" class="negrob">Desde&nbsp;</td>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecDesde','Calendario');"><img src="graf/icon_calendario.gif" width="22" height="18" border="0" alt="Calendario" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecDesde" name="LSTFecDesde" value="<?php echo (!empty($_REQUEST['LSTFecDesde'])) ? $_REQUEST['LSTFecDesde'] : "";?>" class="obliga" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="graf/sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob">Hasta&nbsp;</td>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecHasta','Calendario');"><img src="graf/icon_calendario.gif" width="22" height="18" border="0" alt="Calendario" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecHasta" name="LSTFecHasta" value="<?php echo (!empty($_REQUEST['LSTFecHasta'])) ? $_REQUEST['LSTFecHasta'] : "";?>" class="obliga" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
			</table>
			<div id="Excel" style="display:none">
			<?php 
			$sql="  SELECT * FROM usuarios ";
			$sql.=" WHERE (fecha_alta >= '01/01/2011 00:00:00') ";
			$sql.=" AND (fecha_alta <= '31/04/2011 23:59:59') ";
			$sql.=" AND (empresa = 'techint')";
			$sql.=" AND (pass LIKE 'o-%') OR (pass LIKE 'y-%')";	// NIPS o VIPS
			?>
					<table cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td><input type="button" onclick="javascript:inicio();" class="botones" id="bid-volver" name="fBtnAdd" value="Volver" /></td>
						<td width="100%"><a href="toExcel.php?fSQLtoEXCEL=<?php echo base64_encode($sql . constant("CHAR_SEPARA") . "areas");?>"><img src="<?php echo constant('DIR_WS_GRAF');?>excel.gif" width="34" height="35" border="0" alt="<?php echo constant("STR_EXPORTAR_A_EXCEL");?>" /></a></td>
					</tr>
				</table>
			</div>
			<div id="Comenzar" >
				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td><input type="submit" class="botones" id="bid-ok" name="fBtnAdd" value="Generar" /></td>
						<td><?php echo constant("STR_PASSWORD");?>:&nbsp;<input type="password" class="obliga" id="pass" name="fPass" value="<?php echo (!empty($_REQUEST['fPass'])) ? $_REQUEST['fPass'] : "";?>" /></td>
					</tr>
				</table>
			</div>
	</div>
</div>
</div>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>