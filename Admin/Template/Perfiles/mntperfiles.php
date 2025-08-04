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
	if (validaForm()){
	    lon();
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo constant("STR_ID_PERFIL");?>:",f.LSTIdPerfil.value,11,false);
	msg +=vString("<?php echo constant("STR_DESCRIPCION");?>:",f.LSTDescripcion.value,255,false);
if (msg != "") {
	alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
	return false;
}else return true;
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
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" alt="" /><strong>Por favor espere.<br />Cargando ...</strong></p></td></tr></table></div></td></tr></table>
	<!-- Inicio -->
		<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar('<?php echo $_POST['MODO'];?>');">
<?php
$HELP="xx";

?>
<div id="contenedor">
	<?php include_once(constant("DIR_WS_INCLUDE") . 'cabecera.php');?>
	<div id="envoltura">
		<div id="contenido">
			
			<div style="width: 100%">
		<table cellspacing="0" cellpadding="0" width="98%" border="0" >
		 <tr>
		  <td>
			<table cellspacing="0" cellpadding="0" width="100%" border="0" >
				<tr><td colspan="3" align="center" class="naranjab"><?php echo constant("STR_BUSCADOR");?></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob"><?php echo constant("STR_ID_PERFIL");?>&nbsp;</td>
					<td ><input type="text" name="LSTIdPerfil" value="<?php echo $cEntidad->getIdPerfil();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob"><?php echo constant("STR_DESCRIPCION");?>&nbsp;</td>
					<td ><input type="text" name="LSTDescripcion" value="<?php echo $cEntidad->getDescripcion();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" alt="" /></td>
					<td nowrap="nowrap" width="140" class="grisb"><?php echo constant("STR_ORDENAR_POR");?>&nbsp;</td>
					<td >
						<select name='LSTOrderBy' size='1' class='cajatexto'>
							<?php $sOrderBy = $cEntidad->getOrderBy();?>
							<option style='color:#000000;' value='' <?php echo (empty($sOrderBy)) ? "selected=\"selected\"" : "";?>><?php echo constant("SLC_OPCION");?></option>
							<option style='color:#000000;' value='idPerfil' <?php echo (!empty($sOrderBy) && $sOrderBy == 'idPerfil') ? "selected=\"selected\"" : "";?>><?php echo constant("STR_ID_PERFIL");?></option>
							<option style='color:#000000;' value='descripcion' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descripcion') ? "selected=\"selected\"" : "";?>><?php echo constant("STR_DESCRIPCION");?></option>
							<option style='color:#000000;' value='fecAlta' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fecAlta') ? "selected=\"selected\"" : "";?>><?php echo constant("STR_FEC_ALTA");?></option>
							<option style='color:#000000;' value='fecMod' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fecMod') ? "selected=\"selected\"" : "";?>><?php echo constant("STR_FEC_MOD");?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" alt="" /></td>
					<td nowrap="nowrap" width="140" class="grisb"><?php echo constant("STR_ORDEN");?>&nbsp;</td>
					<td >
						<select name='LSTOrder' size='1' class='cajatexto'>
							<?php $sOrder = $cEntidad->getOrder();?>
							<option style='color:#000000;' value='' <?php echo (empty($sOrder)) ? "selected=\"selected\"" : "";?>><?php echo constant("SLC_OPCION");?></option>
							<option style='color:#000000;' value='ASC' <?php echo (!empty($sOrder) && $sOrder == 'ASC') ? "selected=\"selected\"" : "";?>><?php echo constant("STR_ASCENDENTE");?></option>
							<option style='color:#000000;' value='DESC' <?php echo (!empty($sOrder) && $sOrder == 'DESC') ? "selected=\"selected\"" : "";?>><?php echo constant("STR_DESCENDENTE");?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" alt="" /></td>
					<td nowrap="nowrap" width="140" class="grisb"><?php echo constant("STR_LINEAS_POR_PAGINA");?>&nbsp;</td>
					<td ><input class="cajatexto" style="width:40;" type="text" name="LSTLineasPagina" value="<?php echo ($cEntidad->getLineasPagina() != "") ? $cEntidad->getLineasPagina() : "10";?>" />
					</td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3" ><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
			</table>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td align="center"><input type="submit" class="botones" id="bid-buscar" name="fBtnAdd" value="<?php echo constant("STR_BUSCAR");?>" /></td>
				</tr>
			</table>
          </td>
        </tr> 
 </table>
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_BUSCAR");?>" />
	<input type="hidden" name="perfiles_next_page" value="1" />
</div>
		    
		</div><!-- fin de contenido -->
	</div><!-- fin de envoltura -->
     <?php include_once(constant("DIR_WS_INCLUDE") . 'menus.php');?>
    <?php //include_once(constant("DIR_WS_INCLUDE") . 'derecha.php');?>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
</form>
</body></html>