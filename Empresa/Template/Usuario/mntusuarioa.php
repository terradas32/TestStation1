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
	<link rel="stylesheet" href="estilos/jquery.alerts.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jquery.alert.js"></script>
<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . 'msg_error_JS.php');?>
function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
        lon();
		f.fLogin.disabled=false;
		f.fIdUsuarioTipo.disabled=false;
		return true;
	}else	return false;
}
function validaForm()
{

	var f=document.forms[0];
	var msg="";
	msg +=vString("<?php echo constant("STR_LOGIN");?>:",f.fLogin.value,255,true);
	msg +=vString("<?php echo constant("STR_PASSWORD");?>:",f.fPassword.value,255,true);
	msg +=vString("<?php echo constant("STR_CONF_PASSWORD");?>:",f.fConfPassword.value,255,true);
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombre.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO");?> 1:",f.fApellido1.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO");?> 2:",f.fApellido2.value,255,false);
	msg +=vEmail("<?php echo constant("STR_EMAIL");?>:",f.fEmail.value,255,false);
	msg +=vString("<?php echo constant("STR_DATOS_DE_CORREO") . "\\n\\t" . constant("STR_LOGIN");?>:",f.fLoginCorreo.value,255,false);
	msg +=vString("<?php echo constant("STR_DATOS_DE_CORREO") . "\\n\\t" . constant("STR_PASSWORD");?>:",f.fPasswordCorreo.value,255,false);
	msg +=vString("<?php echo constant("STR_DATOS_DE_CORREO") . "\\n\\t";?>Conf. Password:",f.fConfPasswordCorreo.value,255,false);
	if (f.fPassword.value != f.fConfPassword.value)
		msg +="<?php echo constant("ERR_FORM_PASS_CONF");?>\n"
	if (f.fPasswordCorreo.value != f.fConfPasswordCorreo.value)
		msg +="<?php echo constant("STR_DATOS_DE_CORREO") . "\\n\\t" . constant("ERR_FORM_PASS_CONF");?>\n"
		
	msg +=vNumber("<?php echo constant("STR_TIPO_DE_USUARIO");?>:",f.fIdUsuarioTipo.value,11,true);

if (msg != "") {
	jAlert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.","<?php echo constant("STR_NOTIFICACION");?>");
	return false;
}else return true;
}
function abrirVentana(bImg,file){
	preurl = "view.php?bImg="+ bImg +"&File=" + file;
	prename = "File";
	var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no");
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
	if (eval("document.getElementById('TituloSup').innerHTML") != null){
		document.getElementById('TituloSup').innerHTML=titulo;
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
function autoComplete()
{
    var i = 0;
    // Recorres los elementos INPUT del documento
	for(var node; node = document.getElementsByTagName('input')[i]; i++){
        // Obtienes el tipo de INPUT
        var type = node.getAttribute('type').toLowerCase();
        // Si es del tipo TEXT deshabilitas su autocompletado
        if(type == 'text'){
            node.setAttribute('autocomplete', 'off');
        }
    }
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
<body onload="autoComplete();_body_onload();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
		<form name="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar();">
<?php
$HELP="xx";

?>
<div id="contenedor">
<?php include_once(constant("DIR_WS_INCLUDE") . 'cabecera.php');?>
	<div id="envoltura">
		<div id="contenido">
			<div style="width: 100%"> 
			<table cellspacing="0" cellpadding="0" width="100%" border="0" >
				<tr><td colspan="3" align="center" class="naranjab"><?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){echo(constant("STR_ANIADIR"));}else{echo(constant("STR_MODIFICAR"));}?>*******</td></tr>
				<tr><td colspan="3"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_TIPO_DE_USUARIO");?>&nbsp;***********</td>
					<td ><?php $comboUSUARIOS_TIPOS->setNombre("fIdUsuarioTipo");?><?php echo $comboUSUARIOS_TIPOS->getHTMLCombo("1","obliga",$cEntidad->getIdUsuarioTipo()," disabled='disabled'","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_LOGIN");?>&nbsp;</td>
					<td ><input type="text" name="fLogin" value="<?php echo $cEntidad->getLogin();?>" class="obliga" <?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_MODIFICAR"))){echo("disabled='disabled'");}?> onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_PASSWORD");?>&nbsp;</td>
					<td ><input type="password" name="fPassword" value="" class="obliga" onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td width="90" class="negrob" valign="top"><?php echo constant("STR_CONF_PASSWORD");?></td>
					<td><input type="password" name="fConfPassword" value="" class="obliga" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_NOMBRE");?>&nbsp;</td>
					<td ><input type="text" name="fNombre" value="<?php echo $cEntidad->getNombre();?>" class="obliga" onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_APELLIDO");?> 1&nbsp;</td>
					<td ><input type="text" name="fApellido1" value="<?php echo $cEntidad->getApellido1();?>" class="obliga" onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_APELLIDO");?> 2&nbsp;</td>
					<td ><input type="text" name="fApellido2" value="<?php echo $cEntidad->getApellido2();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_EMAIL");?>&nbsp;</td>
					<td ><input type="text" name="fEmail" value="<?php echo $cEntidad->getEmail();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_LOGIN_DE_CORREO");?>&nbsp;</td>
					<td ><input type="text" name="fLoginCorreo" value="<?php echo $cEntidad->getLoginCorreo();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_PASSWORD_DE_CORREO");?>&nbsp;</td>
					<td ><input type="password" name="fPasswordCorreo" value="<?php echo $cEntidad->getPasswordCorreo();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td width="90" class="negrob" valign="top"><?php echo constant("STR_CONF_PASSWORD");?></td>
					<td><input type="password" name="fConfPasswordCorreo" value="<?php echo $cEntidad->getPasswordCorreo();?>" class="cajatexto" /></td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3" ><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
			</table>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td ><input type="submit" <?php echo ($_bModificar) ? "" : "disabled='disabled'";?> class="botones" id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_GUARDAR");?>" /></td>
				</tr>
			</table>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="fIdUsuario" value="<?php echo $cEntidad->getIdUsuario();?>" />
	<input type="hidden" name="LSTIdUsuario" value="<?php echo (isset($_POST['LSTIdUsuario'])) ? $_POST['LSTIdUsuario'] : "";?>" />
	<input type="hidden" name="LSTLogin" value="<?php echo (isset($_POST['LSTLogin'])) ? $_POST['LSTLogin'] : "";?>" />
	<input type="hidden" name="LSTPassword" value="<?php echo (isset($_POST['LSTPassword'])) ? $_POST['LSTPassword'] : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $_POST['LSTNombre'] : "";?>" />
	<input type="hidden" name="LSTApellido1" value="<?php echo (isset($_POST['LSTApellido1'])) ? $_POST['LSTApellido1'] : "";?>" />
	<input type="hidden" name="LSTApellido2" value="<?php echo (isset($_POST['LSTApellido2'])) ? $_POST['LSTApellido2'] : "";?>" />
	<input type="hidden" name="LSTEmail" value="<?php echo (isset($_POST['LSTEmail'])) ? $_POST['LSTEmail'] : "";?>" />
	<input type="hidden" name="LSTLoginCorreo" value="<?php echo (isset($_POST['LSTLoginCorreo'])) ? $_POST['LSTLoginCorreo'] : "";?>" />
	<input type="hidden" name="LSTPasswordCorreo" value="<?php echo (isset($_POST['LSTPasswordCorreo'])) ? $_POST['LSTPasswordCorreo'] : "";?>" />
	<input type="hidden" name="LSTFecAltaHast" value="<?php echo (isset($_POST['LSTFecAltaHast'])) ? $_POST['LSTFecAltaHast'] : "";?>" />
	<input type="hidden" name="LSTFecAlta" value="<?php echo (isset($_POST['LSTFecAlta'])) ? $_POST['LSTFecAlta'] : "";?>" />
	<input type="hidden" name="LSTFecModHast" value="<?php echo (isset($_POST['LSTFecModHast'])) ? $_POST['LSTFecModHast'] : "";?>" />
	<input type="hidden" name="LSTFecMod" value="<?php echo (isset($_POST['LSTFecMod'])) ? $_POST['LSTFecMod'] : "";?>" />
	<input type="hidden" name="LSTUsuAlta" value="<?php echo (isset($_POST['LSTUsuAlta'])) ? $_POST['LSTUsuAlta'] : "";?>" />
	<input type="hidden" name="LSTUsuMod" value="<?php echo (isset($_POST['LSTUsuMod'])) ? $_POST['LSTUsuMod'] : "";?>" />
	<input type="hidden" name="LSTFecBajaHast" value="<?php echo (isset($_POST['LSTFecBajaHast'])) ? $_POST['LSTFecBajaHast'] : "";?>" />
	<input type="hidden" name="LSTFecBaja" value="<?php echo (isset($_POST['LSTFecBaja'])) ? $_POST['LSTFecBaja'] : "";?>" />
	<input type="hidden" name="LSTBajaLog" value="<?php echo (isset($_POST['LSTBajaLog'])) ? $_POST['LSTBajaLog'] : "";?>" />
	<input type="hidden" name="LSTUltimoLoginHast" value="<?php echo (isset($_POST['LSTUltimoLoginHast'])) ? $_POST['LSTUltimoLoginHast'] : "";?>" />
	<input type="hidden" name="LSTUltimoLogin" value="<?php echo (isset($_POST['LSTUltimoLogin'])) ? $_POST['LSTUltimoLogin'] : "";?>" />
	<input type="hidden" name="LSTIdUsuarioTipo" value="<?php echo (isset($_POST['LSTIdUsuarioTipo'])) ? $_POST['LSTIdUsuarioTipo'] : "";?>" />
	<input type="hidden" name="LSTOrderBy" value="<?php echo (isset($_POST['LSTOrderBy'])) ? $_POST['LSTOrderBy'] : "";?>" />
	<input type="hidden" name="LSTOrder" value="<?php echo (isset($_POST['LSTOrder'])) ? $_POST['LSTOrder'] : "";?>" />
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $_POST['LSTLineasPagina'] : constant("CNF_LINEAS_PAGINA");?>" />
</div>
		    
		</div><!-- fin de contenido -->
	</div><!-- fin de envoltura -->
     <?php include_once(constant("DIR_WS_INCLUDE") . 'menus.php');?>
    <?php //include_once(constant("DIR_WS_INCLUDE") . 'derecha.php');?>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
<script type="text/javascript">// Script para Autocompletar "off" y que valide con la W3C
	autoComplete();
</script>
</form>
</body></html>