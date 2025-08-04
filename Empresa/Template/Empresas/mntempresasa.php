<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
    //Si la logada es ella misma, bloqueo casi todos los campos.
    $bLogada = 1;
//    echo  $cEntidad->getIdEmpresa();
//    echo "<br>";
//    echo $_cEntidadUsuarioTK->getIdEmpresa();
    if(isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_MODIFICAR"))){
    	if ( $cEntidad->getIdEmpresa() == $_cEntidadUsuarioTK->getIdEmpresa()){
    		$bLogada = 1;
    	}else{
    		$bLogada = 0;
    	}
    }else{
		$bLogada = 0;
    }
	$rsPRUEBASGROUP		= $comboPRUEBASGROUP->getDatos();
	$iPRUEBASGROUP		= $rsPRUEBASGROUP->RecordCount();

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
        setIdsPruebas('<?php echo $iPRUEBASGROUP;?>');
        f.fNombre.disabled=false;
		f.fDentroDe.disabled=false;
		f.fDespuesDe.disabled=false;
        f.fCif.disabled=false;
        f.fUsuario.disabled=false;
        f.fDistribuidor[0].disabled=false;
        f.fDistribuidor[1].disabled=false;
        f.fPrepago[0].disabled=false;
        f.fPrepago[1].disabled=false;
        f.fNcandidatos.disabled=false;
        f.fDongles.disabled=false;
		f.fEntidad.disabled=false;
		f.fOficina.disabled=false;
		f.fDc.disabled=false;
		f.fCuenta.disabled=false;
		f.fNombreCan.disabled=false;
		f.fApellido1.disabled=false;
		f.fApellido2.disabled=false;
		f.fMailCan.disabled=false;
		f.fNifCan.disabled=false;
		f.fEdad.disabled=false;
		f.fSexo.disabled=false;
		f.fNivel.disabled=false;
		f.fFormacion.disabled=false;
		f.fArea.disabled=false;
		f.fTelefono.disabled=false;

		return true;
	}else	return false;
}
function validaForm()
{

	var f=document.forms[0];
	var msg="";
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombre.value,255,true);

	if (!f.fCifOK.checked){
		msg +=vCif("<?php echo constant("STR_CIF");?>:",f.fCif.value,255,false);
	}
	var bOK = false;
	aDistribuidor = document.getElementsByName('fDistribuidor');
	for(i=0; i < aDistribuidor.length; i++ ){
		if (aDistribuidor[i].type == "radio" && aDistribuidor[i].name == "fDistribuidor"){
			if (aDistribuidor[i].checked){
				bOK = true;
				break;
			}
		}
	}
	if (!bOK){
		msg +=vString("<?php echo constant("STR_DISTRIBUIDOR");?>:","",1,true);
	}
	bOK = false;
	aPrepago = document.getElementsByName('fPrepago');
	for(i=0; i < aPrepago.length; i++ ){
		if (aPrepago[i].type == "radio" && aPrepago[i].name == "fPrepago"){
			if (aPrepago[i].checked){
				bOK = true;
				break;
			}
		}
	}
	if (!bOK){
		msg +=vString("<?php echo constant("STR_PREPAGO");?>:","",1,true);
	}else{
		if (aPrepago[0].checked){
			msg +=vNumber("<?php echo constant("STR_UMBRAL_DE_AVISO");?>:",f.fUmbral_aviso.value,11,true);
		}else{
			//Contrato
			msg +=vNumber("<?php echo constant("STR_NU_CANDIDATOS");?>:",f.fNcandidatos.value,11,true);
		}
	}
	msg +=vString("<?php echo constant("STR_USUARIO");?>:",f.fUsuario.value,255,true);

	if(f.fPassword != null || f.fConfPassword != null){
		msg +=vString("<?php echo constant("STR_PASSWORD");?>:",f.fPassword.value,255,true);
		msg +=vString("<?php echo constant("STR_CONF_PASSWORD");?>:",f.fConfPassword.value,255,true);
		if (f.fPassword.value != f.fConfPassword.value){
			msg +="<?php echo constant("ERR_FORM_PASS_CONF");?>\n";
		}
	}

	msg +=vString("<?php echo constant("STR_LOGO");?>:",f.fPathLogo.value,500,false);
	msg +=vEmail("<?php echo constant("STR_EMAIL");?>:",f.fMail.value,255,true);
	msg +=vEmail("<?php echo constant("STR_EMAIL_2");?>:",f.fMail2.value,255,false);
	msg +=vEmail("<?php echo constant("STR_EMAIL_3");?>:",f.fMail3.value,255,false);
	msg +=vNumber("<?php echo constant("STR_DONGLES");?>:",f.fDongles.value,11,false);
	msg +=vString("<?php echo constant("STR_PAIS");?>:",f.fIdPais.value,3,true);
	msg +=vString("<?php echo constant("STR_ZONA_HORARIA");?>:",f.fTimezone.value,100,true);
	msg +=vString("<?php echo constant("STR_DIRECCION");?>:",f.fDireccion.value,255,true);
	msg +=vString("<?php echo constant("STR_PERSONA_CONTACTO");?>:",f.fPersonaContacto.value,255,false);
	msg +=vString("<?php echo constant("STR_TLF_CONTACTO");?>:",f.fTlfContacto.value,255,false);
	msg +=vString("<?php echo constant("STR_PRUEBAS");?>:",f.fIdsPruebas.value,5000,true);

if (msg != "") {
	jAlert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.","<?php echo constant("STR_NOTIFICACION");?>");
	return false;
}else return true;
}
function abrirVentana(bImg,file){
	preurl = "view.php?bImg="+ bImg + "&File=" + file;
	prename = "File";
	var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no");
	miv.focus();
}
function chkPrepago(obj){
	var f=document.forms[0];
	if (obj.value == 1){
		//Pepago
		f.fNcandidatos.value = 0;
		f.fNcandidatos.setAttribute('readonly','readonly');
		f.fNcandidatos.setAttribute("class", "cajatexto");
		f.fUmbral_aviso.value = "100"; //Ud. Dongles
		f.fUmbral_aviso.removeAttribute('readOnly');
		f.fUmbral_aviso.setAttribute("class", "obliga");
	}else{
		//Contrato
		f.fNcandidatos.value = "";
		f.fNcandidatos.removeAttribute('readOnly');
		f.fNcandidatos.setAttribute("class", "obliga");
		f.fUmbral_aviso.value = "";
		f.fUmbral_aviso.setAttribute('readonly','readonly');
		f.fUmbral_aviso.setAttribute("class", "cajatexto");
	}
}
function setDonde(oObject){
	var f=document.forms[0];
	if (oObject.name == "fDentroDe"){
		f.fDespuesDe.selectedIndex=0;
	}else if (oObject.name == "fDespuesDe"){
			f.fDentroDe.selectedIndex=0;
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
	var f=document.forms[0];
    var bLogada = "<?php echo $bLogada;?>";
    f.fDongles.disabled=true;
    if (bLogada == "1"){
        f.fNombre.disabled=true;
		f.fDentroDe.disabled=true;
		f.fDespuesDe.disabled=true;
        f.fCif.disabled=true;
        f.fUsuario.disabled=true;
        f.fDistribuidor[0].disabled=true;
        f.fDistribuidor[1].disabled=true;
        f.fPrepago[0].disabled=true;
        f.fPrepago[1].disabled=true;
        f.fNcandidatos.disabled=true;
        f.fDongles.disabled=true;
		f.fEntidad.disabled=true;
		f.fOficina.disabled=true;
		f.fDc.disabled=true;
		f.fCuenta.disabled=true;
    }
}
function cargalapagina(pagina, nombreDiv){
	var f= document.forms[0];
	f.sPG.value=pagina;
	$("#"+nombreDiv).hide().load("jQuery.php",$("form").serializeArray()).fadeIn("slow");
	document.forms[0].fCrea.style.display = 'none';
}

function cierraPagina(nombreDiv){
	var f= document.forms[0];
	f.fPassword.value = null;
  f.fConfPassword.value = null;
	$("#"+nombreDiv).empty();
	document.forms[0].fCrea.style.display = 'block';
}
function setIdsPruebas(iCont){
	var f= document.forms[0];
	var sIds = "";
	for(i=0; i < iCont; i++ ){
		if (eval("document.forms[0].fIdPrueba"+i)!=null){
			aIdPrueba = eval("document.forms[0].fIdPrueba"+i);
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
<body onload="autoComplete();_body_onload();cambiadentrode();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32"  border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
		<form name="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar('<?php echo $_POST['MODO'];?>');" >
<?php
$HELP="xx";

?>
<div id="contenedor">
<?php include_once(constant("DIR_WS_INCLUDE") . 'cabecera.php');?>
	<div id="envoltura">
		<div id="contenido">
		<div style="width: 100%">

				<table cellspacing="0" cellpadding="0" width="100%" border="0">
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td colspan="2" nowrap="nowrap" class="negro" valign="top"><?php echo constant("STR_LOS_CAMPOS_MARCADOS_CON");?> <input type="text" name="--" value="" class="obliga" style="width:25px;height:10px;" onfocus="blur();" /> <?php echo constant("STR_OBLIGATORIOS");?></td>
					</tr>
					<tr><td colspan="3" align="center" class="naranjab"><?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){echo(constant("STR_ANIADIR"));}else{echo(constant("STR_MODIFICAR"));}?></td></tr>
					<tr><td colspan="3"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_NOMBRE");?>&nbsp;</td>
						<td><input type="text" name="fNombre" value="<?php echo $cEntidad->getNombre();?>" class="obliga" onchange="javascript:trim(this);" /></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_DENTRO_DE");?> ...&nbsp;</td>
						<td><?php $comboDENTRO_DE->setNombre("fDentroDe");?><?php echo $comboDENTRO_DE->getHTMLComboMenu("1","cajatexto",$cEntidad->getDentroDe(),"onchange=\"javascript:cambiadentrode()\"","");?></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_DESPUES_DE");?> ...&nbsp;</td>
						<td><div id="combodespuesde"><?php $comboDESPUES_DE->setNombre("fDespuesDe");?><?php echo $comboDESPUES_DE->getHTMLComboNull("1","cajatexto",$cEntidad->getDespuesDe(),"onchange=\"javascript:cambiadespuesde()\" ","");?></div></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_CIF");?>&nbsp;</td>
						<td><input type="text" name="fCif" value="<?php echo $cEntidad->getCif();?>" class="cajatexto"  style='width:75%;' onchange="javascript:trim(this);" /><input type="checkbox" name="fCifOK" /><?php echo constant("STR_VERIFICADO");?></td>
					</tr>
					<tr>
						<?php
						$sDisabled = "";
						if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){
							$sDisabled = "";
						}else{
							$sDisabled = "disabled=\"disabled\"";
						}
						?>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_USUARIO");?>&nbsp;</td>
						<td><input type="text" <?php echo $sDisabled;?> name="fUsuario" value="<?php echo $cEntidad->getUsuario();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
					</tr>


				<?php if(isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){?>
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_PASSWORD");?>&nbsp;</td>
						<td ><input type="password" name="fPassword" value="" class="obliga" onchange="javascript:trim(this);" /></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" alt="" /></td>
						<td width="90" class="negrob" valign="top"><?php echo constant("STR_CONF_PASSWORD");?></td>
						<td><input type="password" name="fConfPassword" value="" class="obliga" /></td>
					</tr>
				<?php }?>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_LOGO");?>&nbsp;</td>
						<td><input onkeydown="blur();" onkeypress="blur();" type="file" name="fPathLogo" class="cajatexto"  />&nbsp;270 x 90</td>
					</tr>
						<?php if ($cEntidad->getPathLogo() != "")
						{
							$img=@getimagesize(constant("HTTP_SERVER") . $cEntidad->getPathLogo());
							$bIimg = (empty($img)) ? 0 : 1;
						?>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td width="140" class="negrob" valign="top">&nbsp;</td>
								<td>&nbsp;<a class="azul" href="#" onclick="abrirVentana('<?php echo $bIimg;?>','<?php echo base64_encode(constant("HTTP_SERVER") . $cEntidad->getPathLogo());?>');"><?php echo str_replace("_","&nbsp;", basename($cEntidad->getPathLogo()));?></a>&nbsp;<input onmouseover="this.style.cursor='pointer'" type="checkbox" name="cfPathLogo" />&nbsp;<?php echo constant("STR_QUITAR");?></td>
							</tr>
						<?php } ?>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_EMAIL");?>&nbsp;</td>
						<td><input type="text" name="fMail" value="<?php echo $cEntidad->getMail();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_EMAIL_2");?>&nbsp;</td>
						<td><input type="text" name="fMail2" value="<?php echo $cEntidad->getMail2();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_EMAIL_3");?>&nbsp;</td>
						<td><input type="text" name="fMail3" value="<?php echo $cEntidad->getMail3();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_DISTRIBUIDOR");?>&nbsp;</td>
						<td>
							<input type="radio" name="fDistribuidor" id="fDistribuidor1" value="1" <?php echo ($cEntidad->getDistribuidor() != "" && strtoupper($cEntidad->getDistribuidor()) == "1") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="fDistribuidor1"><?php echo constant("STR_SI");?></label>&nbsp;<input type="radio" name="fDistribuidor" id="fDistribuidor0" value="0" <?php echo ($cEntidad->getDistribuidor() != "" && strtoupper($cEntidad->getDistribuidor()) == "0") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="fDistribuidor0"><?php echo constant("STR_NO");?></label>
						</td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_PREPAGO");?>&nbsp;</td>
						<td><input type="radio" name="fPrepago" id="fPrepago1" value="1" <?php echo ($cEntidad->getPrepago() != "" && strtoupper($cEntidad->getPrepago()) == "1") ? "checked=\"checked\"" : "";?> onclick="javascript:chkPrepago(this);" />&nbsp;<label for="fPrepago1"><?php echo constant("STR_PREPAGO");?></label>&nbsp;<input type="radio" name="fPrepago" id="fPrepago0" value="0" <?php echo ($cEntidad->getPrepago() != "" && strtoupper($cEntidad->getPrepago()) == "0") ? "checked=\"checked\"" : "";?> onclick="javascript:chkPrepago(this);" /><label for="fPrepago0"><?php echo constant("STR_CONTRATO");?></label></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_NU_CANDIDATOS");?>&nbsp;</td>
						<td><input type="text" name="fNcandidatos" value="<?php echo $cEntidad->getNcandidatos();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_DONGLES");?>&nbsp;</td>
						<td><input type="text" name="fDongles" value="<?php echo $cEntidad->getDongles();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_DATOS_BANCARIOS");?>&nbsp;</td>
						<td class="negro">
							<?php echo constant("STR_ENTIDAD");?>: <input type="text" class="cajatextoCuenta" maxlength="4" size="2" name="fEntidad" value="<?php echo $cEntidad->getEntidad();?>"  onchange="javascript:trim(this);" /> &nbsp;&nbsp;
							<?php echo constant("STR_OFICINA");?>: <input type="text" class="cajatextoCuenta" maxlength="4" size="2" name="fOficina" value="<?php echo $cEntidad->getOficina();?>"   onchange="javascript:trim(this);" />&nbsp;&nbsp;
							<?php echo constant("STR_DC");?>: <input type="text" class="cajatextoCuenta" maxlength="2" size="1" name="fDc" value="<?php echo $cEntidad->getDc();?>"   onchange="javascript:trim(this);" />&nbsp;&nbsp;
							<?php echo constant("STR_CUENTA");?>: <input type="text" class="cajatextoCuenta" maxlength="10" size="9" name="fCuenta" value="<?php echo $cEntidad->getCuenta();?>"   onchange="javascript:trim(this);" />&nbsp;&nbsp;
						</td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_PAIS");?>&nbsp;</td>
						<td><?php $comboWI_PAISES->setNombre("fIdPais");?><?php echo $comboWI_PAISES->getHTMLCombo("1","obliga",$cEntidad->getIdPais()," ","");?></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_ZONA_HORARIA");?>&nbsp;</td>
						<td><select class="obliga" name="fTimezone" id="fTimezone" size="1"><?php echo $cUtilidades->getOptiosSelect_timezone_list($cEntidad->getTimezone());?></select></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_DIRECCION");?>&nbsp;</td>
						<td><input type="text" name="fDireccion" value="<?php echo $cEntidad->getDireccion();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_PERSONA_CONTACTO");?>&nbsp;</td>
						<td><input type="text" name="fPersonaContacto" value="<?php echo $cEntidad->getPersonaContacto();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_TLF_CONTACTO");?>&nbsp;</td>
						<td><input type="text" name="fTlfContacto" value="<?php echo $cEntidad->getTlfContacto();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
					</tr>

					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_UMBRAL_DE_AVISO");?>&nbsp;</td>
						<td><input type="text" name="fUmbral_aviso" value="<?php echo $cEntidad->getUmbral_aviso();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_PRUEBAS");?>&nbsp;</td>
<?php
					//Recogemos para esa empresa las pruebas activadas
					$aPruebas = explode(",",$cEntidad->getIdsPruebas());
					$sAsIdPRUEBASGROUP		= $comboPRUEBASGROUP->getIdKey();
					$sAsPRUEBASGROUP		= $comboPRUEBASGROUP->getDescKey();
					$sDefaultPRUEBASGROUP	= $comboPRUEBASGROUP->getDefault();
					$sPRUEBASGROUP1 = '';
					$i=0;
					$rsPRUEBASGROUP->Move(0); //Posicionamos en el primer registro.
					$sChecked = "";
					$sDisabled = "";
					if (!$bLogada){
						$sDisabled = "";
					}else{
						$sDisabled = "disabled=\"disabled\"";
					}
					$sIdsPruebas = "";
					while (!$rsPRUEBASGROUP->EOF)
					{
						$sChecked = "";
						if (in_array($rsPRUEBASGROUP->fields[$sAsIdPRUEBASGROUP], $aPruebas)){
							$sIdsPruebas .= "," . $rsPRUEBASGROUP->fields[$sAsIdPRUEBASGROUP];
							$sChecked='checked=\"checked\"';
						}
						if ($i==0){
							$sPRUEBASGROUP1 .= '<tr>';
						}
						if (($i%6) == 0){
							$sPRUEBASGROUP1 .= '</tr><tr>';
						}
						$sPRUEBASGROUP1 .= '<td style="padding-right:20px"><input ' . $sDisabled . ' id="fIdPrueba' . $i . '" name="fIdPrueba' . $i . '" type="checkbox" ' . $sChecked . ' onclick="setIdsPruebas(' . $iPRUEBASGROUP . ');" value="' . $rsPRUEBASGROUP->fields[$sAsIdPRUEBASGROUP] . '">' . '<label for="fIdPrueba' . $i . '" title="' . $rsPRUEBASGROUP->fields[$sAsPRUEBASGROUP] . '">' . $rsPRUEBASGROUP->fields[$sAsPRUEBASGROUP] . '</label>';
						$sPRUEBASGROUP1 .= '</td>';
						$i++;
						$rsPRUEBASGROUP->MoveNext();
					}
					if (!empty($sIdsPruebas)){
						$sIdsPruebas = substr($sIdsPruebas,1);
					}
					$sPRUEBASGROUP1 .= '<input type=hidden name="fIdsPruebas" value="' . $sIdsPruebas . '">';
?>
						<td><table><tr><?php echo $sPRUEBASGROUP1;?></tr></table></td>
					</tr>
					<tr>
					    <td colspan="3">
					        &nbsp;
					    </td>
					</tr>
					<?php
						$sChecked='';
						if(isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){
							$sChecked=' checked=\"checked\" ';
						}
					?>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td width="140" class="negrob" valign="top"><?php echo constant("STR_DATOS_TITULO");?>&nbsp;<!--<br /><font style="font-size:8px;color:orange;">Active la información a solicitar al candidato</font> --></td>
						<td>
							<table border =1>
								<tr>
									<td style="padding-right:20px"><input disabled="disabled" <?php echo ($cEntidad->getNombreCan() != "" && strtoupper($cEntidad->getNombreCan()) == "ON") ? "checked=\"checked\"" : "";?> id="fNombreCan" name="fNombreCan" type="checkbox"><label for="fNombreCan" title="<?php echo constant("STR_NOMBRE");?>"><?php echo constant("STR_NOMBRE");?></label></td>
									<td style="padding-right:20px"><input disabled="disabled" <?php echo ($cEntidad->getApellido1() != "" && strtoupper($cEntidad->getApellido1()) == "ON") ? "checked=\"checked\"" : "";?> id="fApellido1" name="fApellido1" type="checkbox"><label for="fApellido1" title="<?php echo constant("STR_APELLIDO1");?>"><?php echo constant("STR_APELLIDO1");?></label></td>
									<td style="padding-right:20px"><input disabled="disabled" <?php echo ($cEntidad->getApellido2() != "" && strtoupper($cEntidad->getApellido2()) == "ON") ? "checked=\"checked\"" : "";?> id="fApellido2" name="fApellido2" type="checkbox"><label for="fApellido2" title="<?php echo constant("STR_APELLIDO2");?>"><?php echo constant("STR_APELLIDO2");?></label></td>
									<td style="padding-right:20px"><input disabled="disabled" <?php echo ($cEntidad->getMailCan() != "" && strtoupper($cEntidad->getMailCan()) == "ON") ? "checked=\"checked\"" : "";?> id="fMailCan" name="fMailCan" type="checkbox"><label for="fMailCan" title="<?php echo constant("STR_MAIL");?>"><?php echo constant("STR_MAIL");?></label></td>
									<td style="padding-right:20px"><input disabled="disabled" <?php echo ($cEntidad->getNifCan() != "" && strtoupper($cEntidad->getNifCan()) == "ON") ? "checked=\"checked\"" : "";?> id="fNifCan" name="fNifCan" type="checkbox"><label for="fNifCan" title="<?php echo constant("STR_DNI");?>"><?php echo constant("STR_DNI");?></label></td>
									<td style="padding-right:20px"><input disabled="disabled" <?php echo $sChecked;?> <?php echo ($cEntidad->getEdad() != "" && strtoupper($cEntidad->getEdad()) == "ON") ? "checked=\"checked\"" : "";?> id="fEdad" name="fEdad" type="checkbox"><label for="fEdad" title="<?php echo constant("STR_EDAD");?>"><?php echo constant("STR_EDAD");?></label></td>
									<td style="padding-right:20px"><input disabled="disabled" <?php echo $sChecked;?> <?php echo ($cEntidad->getSexo() != "" && strtoupper($cEntidad->getSexo()) == "ON") ? "checked=\"checked\"" : "";?> id="fSexo" name="fSexo" type="checkbox"><label for="fSexo" title="<?php echo constant("STR_SEXO");?>:"><?php echo constant("STR_SEXO");?>:</label></td>
									<td style="padding-right:20px"><input disabled="disabled" <?php echo $sChecked;?> <?php echo ($cEntidad->getNivel() != "" && strtoupper($cEntidad->getNivel()) == "ON") ? "checked=\"checked\"" : "";?> id="fNivel" name="fNivel" type="checkbox"><label for="fNivel" title="<?php echo constant("STR_NIVEL");?>"><?php echo constant("STR_NIVEL");?></label></td>
<!--
								</tr>
								<tr>
 -->
									<td style="padding-right:20px"><input disabled="disabled" <?php echo $sChecked;?> <?php echo ($cEntidad->getFormacion() != "" && strtoupper($cEntidad->getFormacion()) == "ON") ? "checked=\"checked\"" : "";?> id="fFormacion" name="fFormacion" type="checkbox"><label for="fFormacion" title="<?php echo constant("STR_FORMACION");?>"><?php echo constant("STR_FORMACION");?></label></td>
									<td style="padding-right:20px"><input disabled="disabled" <?php echo $sChecked;?> <?php echo ($cEntidad->getArea() != "" && strtoupper($cEntidad->getArea()) == "ON") ? "checked=\"checked\"" : "";?> id="fArea" name="fArea" type="checkbox"><label for="fArea" title="<?php echo constant("STR_AREA");?>"><?php echo constant("STR_AREA");?></label></td>
									<td style="padding-right:20px"><input disabled="disabled" <?php echo ($cEntidad->getTelefono() != "" && strtoupper($cEntidad->getTelefono()) == "ON") ? "checked=\"checked\"" : "";?> id="fTelefono" name="fTelefono" type="checkbox"><label for="fTelefono" title="<?php echo constant("STR_TELEFONO");?>"><?php echo constant("STR_TELEFONO");?></label></td>
								</tr>
							</table>
						</td>
					</tr>

<?php				if(isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_MODIFICAR"))){?>
					<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF')?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>

					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF')?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"></td>
						<td><input type="button" value="<?php echo constant("STR_CAMBIAR_CONTRASENA");?>" id="fCrea" class="botonesgrandes" onclick="javascript:cargalapagina('Empresas/cambia_pass','contrasenia');"/></td>
					</tr>
					<tr>
					    <td colspan="3">
					        <div id="contrasenia"></div>
					    </td>
					</tr>

				<?php }?>
					<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
					<tr><td colspan="3" ><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
					<tr><td colspan="3" width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				</table>
				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td class="negro"><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="javascript:document.forms[0].MODO.value=document.forms[0].ORIGEN.value;lon();document.forms[0].submit();" /></td>
						<td ><input type="submit" <?php echo ($_bModificar) ? "" : "disabled";?>  class="botones" id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_GUARDAR");?>" /></td>
					</tr>
				</table>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="fIdEmpresa" value="<?php echo (empty($_POST['AddIdioma'])) ? $cEntidad->getIdEmpresa() : $_POST['fIdEmpresa'];?>" />
	<input type="hidden" name="LSTIdEmpresa" value="<?php echo (isset($_POST['LSTIdEmpresa'])) ? $_POST['LSTIdEmpresa'] : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $_POST['LSTNombre'] : "";?>" />
	<input type="hidden" name="LSTDescripcion" value="<?php echo (isset($_POST['LSTDescripcion'])) ? $_POST['LSTDescripcion'] : "";?>" />
	<input type="hidden" name="LSTIdPadre" value="<?php echo (isset($_POST['LSTIdPadre'])) ? $_POST['LSTIdPadre'] : "";?>" />
	<input type="hidden" name="LSTUrl" value="<?php echo (isset($_POST['LSTUrl'])) ? $_POST['LSTUrl'] : "";?>" />
	<input type="hidden" name="LSTPopUp" value="<?php echo (isset($_POST['LSTPopUp'])) ? $_POST['LSTPopUp'] : "";?>" />
	<input type="hidden" name="LSTOrden" value="<?php echo (isset($_POST['LSTOrden'])) ? $_POST['LSTOrden'] : "";?>" />
	<input type="hidden" name="LSTIndentacion" value="<?php echo (isset($_POST['LSTIndentacion'])) ? $_POST['LSTIndentacion'] : "";?>" />
	<input type="hidden" name="LSTBgFile" value="<?php echo (isset($_POST['LSTBgFile'])) ? $_POST['LSTBgFile'] : "";?>" />
	<input type="hidden" name="LSTBgColor" value="<?php echo (isset($_POST['LSTBgColor'])) ? $_POST['LSTBgColor'] : "";?>" />
	<input type="hidden" name="LSTModoDefecto" value="<?php echo (isset($_POST['LSTModoDefecto'])) ? $_POST['LSTModoDefecto'] : "";?>" />
	<input type="hidden" name="LSTPublico" value="<?php echo (isset($_POST['LSTPublico'])) ? $_POST['LSTPublico'] : "";?>" />
	<input type="hidden" name="LSTCif" value="<?php echo (isset($_POST['LSTCif'])) ? $cUtilidades->validaXSS($_POST['LSTCif']) : "";?>" />
	<input type="hidden" name="LSTUsuario" value="<?php echo (isset($_POST['LSTUsuario'])) ? $cUtilidades->validaXSS($_POST['LSTUsuario']) : "";?>" />
	<input type="hidden" name="LSTPassword" value="<?php echo (isset($_POST['LSTPassword'])) ? $cUtilidades->validaXSS($_POST['LSTPassword']) : "";?>" />
	<input type="hidden" name="LSTPathLogo" value="<?php echo (isset($_POST['LSTPathLogo'])) ? $cUtilidades->validaXSS($_POST['LSTPathLogo']) : "";?>" />
	<input type="hidden" name="LSTMail" value="<?php echo (isset($_POST['LSTMail'])) ? $cUtilidades->validaXSS($_POST['LSTMail']) : "";?>" />
	<input type="hidden" name="LSTMail2" value="<?php echo (isset($_POST['LSTMail2'])) ? $cUtilidades->validaXSS($_POST['LSTMail2']) : "";?>" />
	<input type="hidden" name="LSTMail3" value="<?php echo (isset($_POST['LSTMail3'])) ? $cUtilidades->validaXSS($_POST['LSTMail3']) : "";?>" />
	<input type="hidden" name="LSTDistribuidor" value="<?php echo (isset($_POST['LSTDistribuidor'])) ? $cUtilidades->validaXSS($_POST['LSTDistribuidor']) : "";?>" />
	<input type="hidden" name="LSTPrepago" value="<?php echo (isset($_POST['LSTPrepago'])) ? $cUtilidades->validaXSS($_POST['LSTPrepago']) : "";?>" />
	<input type="hidden" name="LSTNcandidatosHast" value="<?php echo (isset($_POST['LSTNcandidatosHast'])) ? $cUtilidades->validaXSS($_POST['LSTNcandidatosHast']) : "";?>" />
	<input type="hidden" name="LSTNcandidatos" value="<?php echo (isset($_POST['LSTNcandidatos'])) ? $cUtilidades->validaXSS($_POST['LSTNcandidatos']) : "";?>" />
	<input type="hidden" name="LSTDonglesHast" value="<?php echo (isset($_POST['LSTDonglesHast'])) ? $cUtilidades->validaXSS($_POST['LSTDonglesHast']) : "";?>" />
	<input type="hidden" name="LSTDongles" value="<?php echo (isset($_POST['LSTDongles'])) ? $cUtilidades->validaXSS($_POST['LSTDongles']) : "";?>" />
	<input type="hidden" name="LSTIdPais" value="<?php echo (isset($_POST['LSTIdPais'])) ? $cUtilidades->validaXSS($_POST['LSTIdPais']) : "";?>" />
	<input type="hidden" name="LSTDireccion" value="<?php echo (isset($_POST['LSTDireccion'])) ? $cUtilidades->validaXSS($_POST['LSTDireccion']) : "";?>" />
	<input type="hidden" name="LSTUmbral_avisoHast" value="<?php echo (isset($_POST['LSTUmbral_avisoHast'])) ? $cUtilidades->validaXSS($_POST['LSTUmbral_avisoHast']) : "";?>" />
	<input type="hidden" name="LSTUmbral_aviso" value="<?php echo (isset($_POST['LSTUmbral_aviso'])) ? $cUtilidades->validaXSS($_POST['LSTUmbral_aviso']) : "";?>" />
	<input type="hidden" name="LSTOrdenHast" value="<?php echo (isset($_POST['LSTOrdenHast'])) ? $cUtilidades->validaXSS($_POST['LSTOrdenHast']) : "";?>" />
	<input type="hidden" name="LSTOrden" value="<?php echo (isset($_POST['LSTOrden'])) ? $cUtilidades->validaXSS($_POST['LSTOrden']) : "";?>" />
	<input type="hidden" name="LSTIndentacionHast" value="<?php echo (isset($_POST['LSTIndentacionHast'])) ? $cUtilidades->validaXSS($_POST['LSTIndentacionHast']) : "";?>" />
	<input type="hidden" name="LSTIndentacion" value="<?php echo (isset($_POST['LSTIndentacion'])) ? $cUtilidades->validaXSS($_POST['LSTIndentacion']) : "";?>" />
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
	<input type="hidden" name="empresas_next_page" value="<?php echo (isset($_POST['empresas_next_page'])) ? $cUtilidades->validaXSS($_POST['empresas_next_page']) : "1";?>" />

	<script language="javascript" type="text/javascript">
		//<![CDATA[
		function cambiadentrode(){
			var f= document.forms[0];
			$("#combodespuesde").show().load("jQuery.php",{sPG:"combodespuesdeEmpresas",bBus:"0",multiple:"0",nLineas:"1",bObliga:"0",fDisabled:"<?php echo $bLogada;?>",fDentroDe:f.fDentroDe.value,vSelected:"<?php echo $cEntidad->getDespuesDe();?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
		}
		//]]>
	</script>
	<script language="javascript" type="text/javascript">
		//<![CDATA[
		function cambiadespuesde(){
		}
		//]]>
	</script>
</div>

		</div>
	</div>
     <?php include_once(constant("DIR_WS_INCLUDE") . 'menus.php');?>
    <?php //include_once(constant("DIR_WS_INCLUDE") . 'derecha.php');?>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
<input type="hidden" name="empresas_next_page" value="<?php echo (isset($_POST['empresas_next_page'])) ? $cUtilidades->validaXSS($_POST['empresas_next_page']) : "1";?>" />
</form>
<script type="text/javascript">// Script para Autocompletar "off" y que valide con la W3C
	autoComplete();
</script>
</body></html>
