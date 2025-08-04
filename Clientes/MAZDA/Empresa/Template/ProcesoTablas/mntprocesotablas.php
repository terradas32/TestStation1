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
	if (msg != "") {
		alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
		return false;
	}else return true;
}
function cuentaAtras() {
	var f=document.forms[0];
	if (f.fContador.value <= 0){
		lon();
		f.submit();
	}else{
		f.fContador.value = f.fContador.value - 1;
	}	
}
var bFinal = false;
var id = "";
function initCuentaAtras(sPaso){
	if (sPaso > 0){
		if (!bFinal){
			id = setInterval("cuentaAtras()", 1000);
			setTimeout("clearInterval("+id+")",7000);
		}
	}
}
function finCuentaAtras(){
	bFinal = true;
	setTimeout("clearInterval("+id+")",0);
	var oCan = document.getElementById("Cancelar");
	oCan.style.display="none";
	var oCom = document.getElementById("Comenzar");
	oCom.style.display="block";
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
<body onload="_body_onload();initCuentaAtras(<?php echo $sPaso;?>);" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
	
		<form name="form" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar();">
<?php 
$HELP="xx";
?>
<div id="contenedor">
	<div id="envoltura">
		<div id="contenido">
		<div style="width: 100%">
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tr>
					<td align="center" class="naranjab">PROCESO IMPORTATIÓN TEST-STATION (ANTIGUO) --> NEW TEST-TATION</td>
				</tr>
				<tr>
					<td ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 1) ? "grisb" : "negrob"?>" valign="top">1:: IMPORTAR empresas</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 2) ? "grisb" : "negrob"?>" valign="top">2:: IMPORTAR administradores</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 3) ? "grisb" : "negrob"?>" valign="top">3:: IMPORTAR test</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 4) ? "grisb" : "negrob"?>" valign="top">4:: carga Competencias</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 5) ? "grisb" : "negrob"?>" valign="top">5:: carga Escalas</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 6) ? "grisb" : "negrob"?>" valign="top">6:: carga Items Prueba("nips", "o", "es");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 7) ? "grisb" : "negrob"?>" valign="top">7:: carga Baremos Resultados();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 8) ? "grisb" : "negrob"?>" valign="top">8:: carga Items Prueba("nips", "o", "pt");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 9) ? "grisb" : "negrob"?>" valign="top">9:: carga Items Prueba("nips1", "n", "es");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 10) ? "grisb" : "negrob"?>" valign="top">10:: carga Items Prueba("nips1", "n", "en");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 11) ? "grisb" : "negrob"?>" valign="top">11:: carga Items Prueba("nips1", "n", "pt");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 12) ? "grisb" : "negrob"?>" valign="top">12:: carga Items Prueba("vips", "y", "es");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 13) ? "grisb" : "negrob"?>" valign="top">13:: carga Items Prueba("vips", "y", "en"); NO HAY EN INGLÉS</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 14) ? "grisb" : "negrob"?>" valign="top">14:: carga Items Prueba("vips", "y", "pt");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 15) ? "grisb" : "negrob"?>" valign="top">15:: carga Items Prueba("vips1", "v", "es");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 16) ? "grisb" : "negrob"?>" valign="top">16:: carga Items Prueba("vips1", "v", "en");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 17) ? "grisb" : "negrob"?>" valign="top">17:: carga Items Prueba("vips1", "v", "pt");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 18) ? "grisb" : "negrob"?>" valign="top">18:: carga Items Prueba("prisma", "w", "es");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 19) ? "grisb" : "negrob"?>" valign="top">19:: carga Items Prueba("prisma", "w", "cat");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 20) ? "grisb" : "negrob"?>" valign="top">20:: carga Items Prueba("prisma", "w", "en");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 21) ? "grisb" : "negrob"?>" valign="top">21:: carga Items Prueba("prisma", "w", "pt");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 22) ? "grisb" : "negrob"?>" valign="top">22:: carga Escalas Competencias Prisma("w");</td>
				</tr>
    			<tr>
					<td class="<?php echo ($sPaso > 23) ? "grisb" : "negrob"?>" valign="top">23:: carga Items Inversos Prisma("w");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 24) ? "grisb" : "negrob"?>" valign="top">24:: carga Items Prueba("cpl", "g", "es");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 25) ? "grisb" : "negrob"?>" valign="top">25:: carga Items Prueba("cpl32", "q", "es");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 26) ? "grisb" : "negrob"?>" valign="top">26:: carga Items Prueba("cpl32", "q", "en");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 27) ? "grisb" : "negrob"?>" valign="top">27:: carga Items Prueba("cpl32", "q", "cat");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 28) ? "grisb" : "negrob"?>" valign="top">28::carga Items Prueba("cpl32", "q", "pt");</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 29) ? "grisb" : "negrob"?>" valign="top">29::carga Areas();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 30) ? "grisb" : "negrob"?>" valign="top">30::cargaBaremos(16);//nips</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 31) ? "grisb" : "negrob"?>" valign="top">31::cargaBaremos(24);//prisma</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 32) ? "grisb" : "negrob"?>" valign="top">32::cargaBaremos(26);//vips;</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 33) ? "grisb" : "negrob"?>" valign="top">33::borrarCandidatos();//mySQL</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 34) ? "grisb" : "negrob"?>" valign="top">34::cargaCorreosBase();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 35) ? "grisb" : "negrob"?>" valign="top">35::cargaCorreosLiterales();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 36) ? "grisb" : "negrob"?>" valign="top">36::borrarCorreosProceso();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 37) ? "grisb" : "negrob"?>" valign="top">37::borrarDescargasInformes();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 38) ? "grisb" : "negrob"?>" valign="top">38::cargaEdades();</td>
				</tr>				
				<tr>
					<td class="<?php echo ($sPaso > 39) ? "grisb" : "negrob"?>" valign="top">39::cargaEjemplosPruebas();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 40) ? "grisb" : "negrob"?>" valign="top">40::borrar:&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;empresas_accesos,
						&nbsp;&nbsp;&nbsp;&nbsp;ficheros_carga,
						&nbsp;&nbsp;&nbsp;&nbsp;peticiones_dongles,
						&nbsp;&nbsp;&nbsp;&nbsp;proceso_baremos,
						&nbsp;&nbsp;&nbsp;&nbsp;proceso_pruebas,
						&nbsp;&nbsp;&nbsp;&nbsp;respuestas_pruebas_items
						<br />&nbsp;&nbsp;&nbsp;&nbsp;respuestas_pruebas,
						&nbsp;&nbsp;&nbsp;&nbsp;envios
						&nbsp;&nbsp;&nbsp;&nbsp;wi_historico_cambios
						&nbsp;&nbsp;&nbsp;&nbsp;proceso_informes
						&nbsp;&nbsp;&nbsp;&nbsp;procesos
					</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 41) ? "grisb" : "negrob"?>" valign="top">41::cargaFormaciones();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 42) ? "grisb" : "negrob"?>" valign="top">42::cargaInformes_pruebas();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 43) ? "grisb" : "negrob"?>" valign="top">43::cargaInstrucciones_pruebas();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 44) ? "grisb" : "negrob"?>" valign="top">44::cargaNivelesJerarquicos();</td>
				</tr>	
				<tr>
					<td class="<?php echo ($sPaso > 45) ? "grisb" : "negrob"?>" valign="top">45::cargaNotificaciones();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 46) ? "grisb" : "negrob"?>" valign="top">46::cargaOpciones_ejemplos();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 47) ? "grisb" : "negrob"?>" valign="top">47::cargaSexos();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 48) ? "grisb" : "negrob"?>" valign="top">48::cargaOpciones_ItemsESP();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 49) ? "grisb" : "negrob"?>" valign="top">49::cargaEjemplos_ayudas();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 50) ? "grisb" : "negrob"?>" valign="top">50::cargaPruebas_ayudas();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 51) ? "grisb" : "negrob"?>" valign="top">51::actualizaItems_Nips();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 52) ? "grisb" : "negrob"?>" valign="top">52::cargaTablasInformes();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 53) ? "grisb" : "negrob"?>" valign="top">53::cargaSignos();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 54) ? "grisb" : "negrob"?>" valign="top">54::cargaSeccionesInformes();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 55) ? "grisb" : "negrob"?>" valign="top">55::cargaTextosSecciones();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 56) ? "grisb" : "negrob"?>" valign="top">56::cargaRangosIr();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 57) ? "grisb" : "negrob"?>" valign="top">57::cargaRangosIp();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 58) ? "grisb" : "negrob"?>" valign="top">58::cargaRangosTextos();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 59) ? "grisb" : "negrob"?>" valign="top">59::cargaEmk_charsets();</td>
				</tr>
				<tr>
					<td class="<?php echo ($sPaso > 60) ? "grisb" : "negrob"?>" valign="top">60::cargaModo_realizacion();</td>
				</tr>				
				

				<tr><td ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td ><input type="text" style="width:20px;" name="fContador" disabled="disabled" value="1" /></td>
				</tr>
				<tr><td ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
			</table>
			<div id="Cancelar" <?php echo ($sPaso > 0) ? "" : "style=\"display:none\""?>>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
			<?php
				if ($sPaso > 0)
				{?>
					<td><input type="button" class="botones" id="bid-cancel" name="fBtnAdd" value="Cancelar" onclick="javascript:finCuentaAtras();" /></td>
			<?php
				}?>
				</tr>
			</table>
			</div>
			<div id="Comenzar" <?php echo ($sPaso > 0) ? "style=\"display:none\"" : ""?>>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td><input type="submit" class="botones" id="bid-ok" name="fBtnAdd" value="Empezar" /></td>
					<td><?php echo constant("STR_PASSWORD");?>:&nbsp;<input type="password" class="obliga" id="pass" name="fPass" value="<?php echo (!empty($_POST['fPass'])) ? $_POST['fPass'] : "";?>" /></td>
				</tr>
			</table>
			</div>
	</div>
</div>
	<input type="hidden" name="fPaso" value="<?php echo $sPaso;?>" />
	<input type="hidden" name="fPasosNext" value="<?php echo $_POST['fPasosNext'];?>" />
</div>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>