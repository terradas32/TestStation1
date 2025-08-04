<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<?php
	$aPerfilFuncionalidades=array();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, Wi2.22 www.negociainternet.com" />
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
		seleccionarMultiple('fPerfilFuncionalidades[]');
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo constant("STR_PERFIL");?>:",f.fIdPerfil.value,11,false);
	msg +=vNumber("<?php echo constant("STR_FUNCIONALIDAD");?>:",f.fIdFuncionalidad.value,11,false);
	msg +=vString("<?php echo constant("STR_MODIFICAR");?>:",f.fModificar.value,2,false);
	msg +=vString("<?php echo constant("STR_BORRAR");?>:",f.fBorrar.value,2,false);
	if (f.elements['fPerfilFuncionalidades[]'].options[0] != null){
		msg +=vString("<?php echo constant("STR_PERFIL_FUNCIONALIDAD");?>:",f.elements['fPerfilFuncionalidades[]'].options[0].value,11,true);
	}else{
		msg +=vString("<?php echo constant("STR_PERFIL_FUNCIONALIDAD");?>:","",11,true);
	}
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
function anadir(nombre,texto,valor){
	if (valor !=""){
		ObjCombo=document.forms[0].elements[nombre];
		ii=ObjCombo.length;
		bAniadir=true;
		for(i=0; i < ii; i++ ){
			if (ObjCombo.options[i].value == valor){
				bAniadir=false;
			}
		}
		if (bAniadir){
			ObjCombo.length++;
			ObjCombo.options[ObjCombo.length-1].value=valor;
			ObjCombo.options[ObjCombo.length-1].text=texto;
		}
	}
}
function quitar(nombre,texto,valor){
	var f=document.forms[0];
	if (valor !=""){
		ii=0
		var aTexto = new Array();
		var aValor = new Array();
		
		ObjCombo=document.forms[0].elements[nombre];
		for(i=0; i < ObjCombo.length; i++ ){
			if (ObjCombo.options[i].value != valor){
				aValor[ii] = ObjCombo.options[i].value;
				aTexto[ii] = ObjCombo.options[i].text;
				ii++;
			}
		}
		ObjCombo.length=0;
		for(i=0; i < aValor.length; i++ ){
			anadir(nombre,aTexto[i],aValor[i]);
		}
	}
}
function seleccionarMultiple(nombre){
	ObjCombo=document.forms[0].elements[nombre];
	for(i=0; i < ObjCombo.length; i++ ){
		ObjCombo.options[i].selected = true;
	}
}
function setMultiple(){
	var f=document.forms[0];
	var sPerfilFuncionalidades = "<?php echo $cEntidad->getPerfilFuncionalidades();?>";
	var aPerfilFuncionalidades = sPerfilFuncionalidades.split(",");
	for(var i=0; i < aPerfilFuncionalidades.length; i++){
		aTIT = aPerfilFuncionalidades[i].split("|");
		texto = aTIT[0] + " - " + aTIT[1] + " - " + aTIT[2];
		valor = aTIT[0] + "|" + aTIT[1] + "|" + aTIT[2];
		anadir('fPerfilFuncionalidades[]',texto,valor);
	}
}
function validaRepe(nombre,valor){
	var bAniadir=false;
	if (valor !=""){
		ObjCombo=document.forms[0].elements[nombre];
		ii=ObjCombo.length;
		if (ii > 0){
			var sPKTxt = "";
			for(i=0; i < ii; i++ ){
				sPKTxt = ObjCombo.options[i].value;
				aPKTxt = sPKTxt.split('|');
				if (aPKTxt[0] + aPKTxt[1]  != valor){
					bAniadir=true;
				}else{
					bAniadir=false;
					break;
				}
			}
		}else{
			bAniadir=true;
		}
	}
	return bAniadir;
}
function validaAniadir(nombre){
	var f=document.forms[0];
	var sValorMod="";
	var sValorDel="";
	if (f.fIdPerfil.value !="" &&
		f.fIdFuncionalidad.value !="" ){
		texto = f.fIdPerfil.options[f.fIdPerfil.options.selectedIndex].text + " - " + f.fIdFuncionalidad.options[f.fIdFuncionalidad.options.selectedIndex].text;
		if (f.fModificar.checked){
		 texto = texto + " - <?php echo constant("STR_MODIFICAR");?>: on";
		 sValorMod='on';
		}else{
			texto = texto + " - <?php echo constant("STR_MODIFICAR");?>: ";
		}
		if (f.fBorrar.checked){
		 texto = texto + " - <?php echo constant("STR_BORRAR");?>: on";
		 sValorDel='on';
		}else{
			texto = texto + " - <?php echo constant("STR_BORRAR");?>: ";
		}
		valor = f.fIdPerfil.value + "|" + f.fIdFuncionalidad.value + "|" + sValorMod + "|" + sValorDel;
		if (validaRepe(nombre, f.fIdPerfil.value + f.fIdFuncionalidad.value)){
			anadir(nombre,texto,valor);
		}else {
			jAlert("<?php echo constant("STR_REPETIDO");?>")
		}
	}else{
		jAlert("<?php echo constant("SLC_PERFIL_FUNCIONALIDAD");?>.");
	}
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
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
		<form name="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar('<?php echo $_POST['MODO'];?>');">
<?php
$HELP="xx";

?>
<div id="contenedor">
	<?php include_once(constant("DIR_WS_INCLUDE") . 'cabecera.php');?>
	<div id="envoltura">
		<div id="contenido">
			
			<div style="width: 100%">
			<table cellspacing="0" cellpadding="0"  border="0" width="100%">
				<tr><td colspan="3" align="center" class="naranjab"><?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){echo(constant("STR_ANIADIR"));}else{echo(constant("STR_MODIFICAR"));}?></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td colspan="3">
								<table cellspacing="0" cellpadding="0" width="95%" border="0" >
									<tr>
										<td style="width:5px" rowspan="8"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
										<td style="width:130" class="negrob"><?php echo constant("STR_PERFIL");?>&nbsp;</td>
										<td style="width:5px" rowspan="8"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
										<td rowspan="8" valign="top">
											<select multiple="multiple" size="6" name="fPerfilFuncionalidades[]" class="cajatexto" style="width:100%;">
											<?php 
												if ( is_array($cEntidad->getPerfilFuncionalidades() )){
													$aPerfilFuncionalidades = $cEntidad->getPerfilFuncionalidades();
												}else{
													$sPerfilFuncionalidades = $cEntidad->getPerfilFuncionalidades();
													if (!empty($sPerfilFuncionalidades)){
														$aPerfilFuncionalidades = explode(",", $sPerfilFuncionalidades);
													}
												}
												for ($i=0; $i < sizeof($aPerfilFuncionalidades); $i++){
													$aPsFs = explode("|", $aPerfilFuncionalidades[$i]);
													echo "<option value=\"" . $aPsFs[0] . "|" . $aPsFs[1] . "|" . $aPsFs[2] . "|" . $aPsFs[3] . "\">" . $comboEMP_PERFILES->getDescripcionCombo($aPsFs[0]) . " - " . $comboFUNCIONALIDADES->getDescripcionCombo($aPsFs[1]) . " - " . constant("STR_MODIFICAR") . ": " . $aPsFs[2] . " - " . constant("STR_BORRAR") . ": " . $aPsFs[3] . "</option>";
												}
											?>
											</select>
											<br />
											<table cellspacing="0" cellpadding="0" width="100%" border="0" >
												<tr>
													<td align="center"><input type="button" class="botones" style="color:Red;" onclick="javascript:validaAniadir('fPerfilFuncionalidades[]')" name="btnAddTIT" value="<?php echo constant("STR_ANIADIR");?>" /></td>
													<td align="center"><input type="button" class="botones" style="color:Red;" onclick="javascript:quitar('fPerfilFuncionalidades[]',document.forms[0].elements['fPerfilFuncionalidades[]'].value,document.forms[0].elements['fPerfilFuncionalidades[]'].value);" name="fBtnDelTIT" value="<?php echo constant("STR_BORRAR");?>" /></td>
												</tr>
											</table>
										</td>
										<td style="width:5px" rowspan="8"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
									</tr>
									<tr>
										<td style="width:130" class="negrob"><?php $comboEMP_PERFILES->setNombre("fIdPerfil");?><?php echo $comboEMP_PERFILES->getHTMLCombo("1","cajatexto","","","");?></td>
									</tr>
									<tr>
										<td style="width:130" class="negrob"><?php echo constant("STR_FUNCIONALIDAD");?>&nbsp;</td>
									</tr>
									<tr>
										<td style="width:25%" class="negrob"><?php $comboFUNCIONALIDADES->setNombre("fIdFuncionalidad");?><?php echo $comboFUNCIONALIDADES->getHTMLComboMenu("1","cajatexto","","","");?></td>
									</tr>
									<tr>
										<td nowrap="nowrap" style="width:140" class="negrob"><?php echo constant("STR_MODIFICAR");?>&nbsp;</td>
									</tr>
									<tr>
										<td nowrap="nowrap" style="width:140" class="negrob"><input type="checkbox" name="fModificar" /></td>
									</tr>
									<tr>
										<td nowrap="nowrap" style="width:140" class="negrob"><?php echo constant("STR_BORRAR");?>&nbsp;</td>
									</tr>
									<tr>
										<td nowrap="nowrap" style="width:140" class="negrob"><input type="checkbox" name="fBorrar" /></td>
									</tr>
								</table>
								<table cellspacing="0" cellpadding="0" width="100%" border="0" >
									<tr>
										<td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td>
									</tr>
								</table>
					</td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3" ><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td class="negro"><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="document.forms[0].MODO.value=document.forms[0].ORIGEN.value;document.forms[0].submit();" /></td>
			<td ><input type="submit" <?php echo ($_bModificar) ? "" : "disabled";?> class="botones" id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_GUARDAR");?>" /></td>
		</tr>
	</table>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="LSTIdPerfil" value="<?php echo (isset($_POST['LSTIdPerfil'])) ? $_POST['LSTIdPerfil'] : "";?>" />
	<input type="hidden" name="LSTIdFuncionalidad" value="<?php echo (isset($_POST['LSTIdFuncionalidad'])) ? $_POST['LSTIdFuncionalidad'] : "";?>" />
	<input type="hidden" name="LSTModificar" value="<?php echo (isset($_POST['LSTModificar'])) ? $_POST['LSTModificar'] : "";?>" />
	<input type="hidden" name="LSTBorrar" value="<?php echo (isset($_POST['LSTBorrar'])) ? $_POST['LSTBorrar'] : "";?>" />
	<input type="hidden" name="LSTFecAlta" value="<?php echo (isset($_POST['LSTFecAlta'])) ? $_POST['LSTFecAlta'] : "";?>" />
	<input type="hidden" name="LSTFecMod" value="<?php echo (isset($_POST['LSTFecMod'])) ? $_POST['LSTFecMod'] : "";?>" />
	<input type="hidden" name="LSTUsuAlta" value="<?php echo (isset($_POST['LSTUsuAlta'])) ? $_POST['LSTUsuAlta'] : "";?>" />
	<input type="hidden" name="LSTUsuMod" value="<?php echo (isset($_POST['LSTUsuMod'])) ? $_POST['LSTUsuMod'] : "";?>" />
	<input type="hidden" name="LSTOrderBy" value="<?php echo (isset($_POST['LSTOrderBy'])) ? $_POST['LSTOrderBy'] : "";?>" />
	<input type="hidden" name="LSTOrder" value="<?php echo (isset($_POST['LSTOrder'])) ? $_POST['LSTOrder'] : "";?>" />
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $_POST['LSTLineasPagina'] : "10";?>" />
	<input type="hidden" name="empresas_perfiles_funcionalidades_next_page" value="<?php echo (isset($_POST['empresas_perfiles_funcionalidades_next_page'])) ? $cUtilidades->validaXSS($_POST['empresas_perfiles_funcionalidades_next_page']) : "1";?>" />
</div>
		    
		</div><!-- fin de contenido -->
	</div><!-- fin de envoltura -->
     <?php include_once(constant("DIR_WS_INCLUDE") . 'menus.php');?>
    <?php //include_once(constant("DIR_WS_INCLUDE") . 'derecha.php');?>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
</form>
</body></html>