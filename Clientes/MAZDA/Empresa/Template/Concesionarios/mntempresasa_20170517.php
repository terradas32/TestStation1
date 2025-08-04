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
	//Inicializamos los valores por de fecto para nuevo concesionario
	$cEntidad->setDistribuidor("0");
	$cEntidad->setPrepago("1");
	$cEntidad->setNcandidatos("0");
	
	$_POST["_clicado"] = (!empty($_POST["_clicado"])) ? $_POST["_clicado"] : "-1";
	$_POST["_block"] = (!empty($_POST["_block"])) ? $_POST["_block"] : "0";
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, Wi2.22 www.azulpomodoro.com" />
		
<title><?php echo constant("NOMBRE_SITE");?></title>
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<link rel="stylesheet" href="estilos/estilos-candidato.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . 'msg_error_JS.php');?>
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
	msg +=vString("<?php echo constant("STR_NOMBRE");?> del Concesionario:",f.fNombre.value,255,true);

	if (!f.fCifOK.checked){
		msg +=vCif("<?php echo constant("STR_CIF");?>:",f.fCif.value,255,false);
	}
//	msg +=vString("<?php echo constant("STR_USUARIO");?>:",f.fUsuario.value,255,true);
	
// 	if(f.fPassword != null || f.fConfPassword != null){
//		msg +=vString("<?php echo constant("STR_PASSWORD");?>:",f.fPassword.value,255,true);
//		msg +=vString("<?php echo constant("STR_CONF_PASSWORD");?>:",f.fConfPassword.value,255,true);
// 		if (f.fPassword.value != f.fConfPassword.value){
//			msg +="<?php echo constant("ERR_FORM_PASS_CONF");?>\n";
// 		}
// 	}

//	msg +=vString("<?php echo constant("STR_LOGO");?>:",f.fPathLogo.value,500,false);
	msg +=vEmail("<?php echo constant("STR_EMAIL");?>:",f.fMail.value,255,true);
	msg +=vEmail("<?php echo constant("STR_EMAIL_2");?>:",f.fMail2.value,255,false);
	msg +=vEmail("<?php echo constant("STR_EMAIL_3");?>:",f.fMail3.value,255,false);
	msg +=vNumber("<?php echo constant("STR_DONGLES");?>:",f.fDongles.value,11,true);
	msg +=vString("<?php echo constant("STR_PAIS");?>:",f.fIdPais.value,3,false);
	msg +=vString("<?php echo constant("STR_DIRECCION");?>:",f.fDireccion.value,255,true);
	msg +=vString("Persona Contacto:",f.fPersonaContacto.value,255,true);
	msg +=vString("Tlf. Contacto:",f.fTlfContacto.value,255,true);
	msg +=vString("<?php echo constant("STR_PRUEBAS");?>:",f.fIdsPruebas.value,5000,true);

if (msg != "") {
	alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
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
	if (eval("document.getElementById('_block')") != null){
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
<!-- <body onload="autoComplete();_body_onload();cambiadentrode();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();"> -->
<body onload="autoComplete();_body_onload();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32"  border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
		<form name="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar('<?php echo $_POST['MODO'];?>');" >
<?php
$HELP="xx";

?>
<div id="contenedor">
<?php include_once(constant("DIR_WS_INCLUDE") . 'cabeceraLogin.php');?>
	<div id="envoltura" style="width: 991px !important;">
		<div id="contenido" style="margin: 0 0 0 75px !important;">
		<div style="width: 100%">
		
				<table cellspacing="0" cellpadding="5" width="100%" border="0">
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td colspan="2" nowrap="nowrap" class="negro" valign="top"><?php echo constant("STR_LOS_CAMPOS_MARCADOS_CON");?> <input type="text" name="--" value="" class="obliga" style="width:25px;height:10px;" onfocus="blur();" /> <?php echo constant("STR_OBLIGATORIOS");?></td>
					</tr>
					<tr><td colspan="3" align="center" class="naranjab"><?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){echo(constant("STR_ANIADIR"));}else{echo(constant("STR_MODIFICAR"));}?></td></tr>
					<tr><td colspan="3"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top"><?php echo constant("STR_NOMBRE");?> del Concesionario&nbsp;</td>
						<td><input type="text" name="fNombre" value="<?php echo $cEntidad->getNombre();?>" class="obliga" style="width: 40% !important;" onchange="javascript:trim(this);" /></td>
					</tr>
<!-- 
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top"><?php echo constant("STR_DENTRO_DE");?> ...&nbsp;</td>
						<td><?php $comboDENTRO_DE->setNombre("fDentroDe");?><?php echo $comboDENTRO_DE->getHTMLComboMenu("1","cajatexto",$cEntidad->getDentroDe(),"style='width: 40% !important;' disabled='disabled' onchange=\"javascript:cambiadentrode()\"","");?></td>
					</tr>
 -->
					<input type="hidden" name="fDentroDe" value="141" onchange="javascript:trim(this);" />
<!-- 					
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top"><?php echo constant("STR_DESPUES_DE");?> ...&nbsp;</td>
						<td><div id="combodespuesde"><?php $comboDESPUES_DE->setNombre("fDespuesDe");?><?php echo $comboDESPUES_DE->getHTMLComboNull("1","cajatexto",$cEntidad->getDespuesDe(),"style='width: 40% !important;' onchange=\"javascript:cambiadespuesde()\" ","");?></div></td>
					</tr>
-->					
					<input type="hidden" name="fDespuesDe" value="" onchange="javascript:trim(this);" />					
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top"><?php echo constant("STR_CIF");?>&nbsp;</td>
						<td><input type="text" name="fCif" value="<?php echo $cEntidad->getCif();?>" class="cajatexto"  style='width:40% !important;' onchange="javascript:trim(this);" /><input type="checkbox" name="fCifOK" /><?php echo constant("STR_VERIFICADO");?></td>
					</tr>
<!-- 
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
						<td nowrap="nowrap" width="160" class="negrob" valign="top"><?php echo constant("STR_USUARIO");?>&nbsp;</td>
						<td><input type="text" <?php echo $sDisabled;?> name="fUsuario" value="<?php echo $cEntidad->getUsuario();?>" class="obliga" style="width: 40% !important;"  onchange="javascript:trim(this);" /></td>
					</tr>
 -->					
					<input type="hidden" name="fUsuario" value="<?php echo $cEntidad->getUsuario();?>" />
<!-- 
				<?php if(isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){?>
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top"><?php echo constant("STR_PASSWORD");?>&nbsp;</td>
						<td ><input type="password" name="fPassword" value="" class="obliga" style="width: 40% !important;" onchange="javascript:trim(this);" /></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="5" height="20" alt="" /></td>
						<td width="90" class="negrob" valign="top"><?php echo constant("STR_CONF_PASSWORD");?></td>
						<td><input type="password" name="fConfPassword" value="" class="obliga" style="width: 40% !important;" /></td>
					</tr>
				<?php }?>
-->
				<input type="hidden" name="fPassword" value="" />
<!-- 
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top"><?php echo constant("STR_LOGO");?>&nbsp;</td>
						<td><input onkeydown="blur();" onkeypress="blur();" type="file" name="fPathLogo" class="cajatexto"  />&nbsp;270 x 90</td>
					</tr>
						<?php if ($cEntidad->getPathLogo() != "")
						{
							$img=@getimagesize(constant("HTTP_SERVER") . $cEntidad->getPathLogo());
							$bIimg = (empty($img)) ? 0 : 1;
						?>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td width="160" class="negrob" valign="top">&nbsp;</td>
								<td>&nbsp;<a class="azul" href="#" onclick="abrirVentana('<?php echo $bIimg;?>','<?php echo base64_encode(constant("HTTP_SERVER") . $cEntidad->getPathLogo());?>');"><?php echo str_replace("_","&nbsp;", basename($cEntidad->getPathLogo()));?></a>&nbsp;<input onmouseover="this.style.cursor='pointer'" type="checkbox" name="cfPathLogo" />&nbsp;<?php echo constant("STR_QUITAR");?></td>
							</tr>
						<?php } ?>
-->
					<input type="hidden" name="fPathLogo" />
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td colspan="2" >
							<table cellspacing="0" cellpadding="0" width="100%" border="0">
								<tr>
									<td nowrap="nowrap" width="157" class="negrob" valign="top"><?php echo constant("STR_DIRECCION");?>&nbsp;</td>
									<td nowrap="nowrap" width="246" ><input type="text" name="fDireccion" value="<?php echo $cEntidad->getDireccion();?>" class="obliga" style="width: 100% !important;"  onchange="javascript:trim(this);" /></td>
									<td width="8"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
									<?php
										if ($cEntidad->getIdPais() ==""){
											$cEntidad->setIdPais("724");
										}
									?>
									<td nowrap="nowrap" width="170" class="negrob" valign="top"><?php echo constant("STR_PAIS");?>&nbsp;</td>
									<td nowrap="nowrap" width="191" ><?php $comboWI_PAISES->setNombre("fIdPais");?><?php echo $comboWI_PAISES->getHTMLCombo("1","cajatexto",$cEntidad->getIdPais(),"style='width: 100% !important;' ","");?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top">Tlf. Contacto&nbsp;</td>
						<td><input type="text" name="fTlfContacto" value="<?php echo $cEntidad->getTlfContacto();?>" class="obliga" style="width: 40% !important;"  onchange="javascript:trim(this);" /></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top">Persona Contacto&nbsp;</td>
						<td><input type="text" name="fPersonaContacto" value="<?php echo $cEntidad->getPersonaContacto();?>" class="obliga" style="width: 40% !important;"  onchange="javascript:trim(this);" /></td>
					</tr>

					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td colspan="2" >
							<table cellspacing="0" cellpadding="0" width="100%" border="0">
								<tr>
									<td nowrap="nowrap" width="157" class="negrob" valign="top"><?php echo constant("STR_EMAIL");?>&nbsp;</td>
									<td nowrap="nowrap" width="246" ><input type="text" name="fMail" value="<?php echo $cEntidad->getMail();?>" class="obliga" style="width: 100% !important;"  onchange="javascript:trim(this);" /></td>
									<td width="8"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
									<td nowrap="nowrap" width="170" class="negrob" valign="top"><?php echo constant("STR_EMAIL_2");?>&nbsp;</td>
									<td nowrap="nowrap" width="191" ><input type="text" name="fMail2" value="<?php echo $cEntidad->getMail2();?>" class="cajatexto" style="width: 100% !important;" onchange="javascript:trim(this);" /></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top"><?php echo constant("STR_EMAIL_3");?>&nbsp;</td>
						<td><input type="text" name="fMail3" value="<?php echo $cEntidad->getMail3();?>" class="cajatexto" style="width: 40% !important;" onchange="javascript:trim(this);" /></td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top">Nº Cuenta corriente&nbsp;</td>
						<td class="negro">
							<input type="text" class="cajatextoCuenta" style="background-color: #ececec; border: 1px solid #5f5f5f;" maxlength="4" size="2" name="fEntidad" value="<?php echo $cEntidad->getEntidad();?>"  onchange="javascript:trim(this);" /> &nbsp;/&nbsp;
							<input type="text" class="cajatextoCuenta" style="background-color: #ececec; border: 1px solid #5f5f5f;" maxlength="4" size="2" name="fOficina" value="<?php echo $cEntidad->getOficina();?>"   onchange="javascript:trim(this);" />&nbsp;/&nbsp;
							<input type="text" class="cajatextoCuenta" style="background-color: #ececec; border: 1px solid #5f5f5f;" maxlength="2" size="1" name="fDc" value="<?php echo $cEntidad->getDc();?>"   onchange="javascript:trim(this);" />&nbsp;/&nbsp;
							<input type="text" class="cajatextoCuenta" style="background-color: #ececec; border: 1px solid #5f5f5f;" maxlength="10" size="19" name="fCuenta" value="<?php echo $cEntidad->getCuenta();?>"   onchange="javascript:trim(this);" />
						</td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top">Nº Unidades a contratar</td>
						<td><input type="text" name="fDongles" value="<?php echo $cEntidad->getDongles();?>" class="obliga" style="width: 40% !important;"  onchange="javascript:trim(this);" /></td>
					</tr>					
<!-- 
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top"><?php echo constant("STR_DISTRIBUIDOR");?>&nbsp;</td>
						<td>
							<input type="radio" name="fDistribuidor" id="fDistribuidor1" value="1"  <?php echo ($cEntidad->getDistribuidor() != "" && strtoupper($cEntidad->getDistribuidor()) == "1") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="fDistribuidor1"><?php echo constant("STR_SI");?></label>&nbsp;<input type="radio" name="fDistribuidor" id="fDistribuidor0" value="0"  <?php echo ($cEntidad->getDistribuidor() != "" && strtoupper($cEntidad->getDistribuidor()) == "0") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="fDistribuidor0"><?php echo constant("STR_NO");?></label></td>
					</tr>
-->
					<input type="hidden" name="fDistribuidor" id="fDistribuidor0" value="0" />
<!-- 
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top"><?php echo constant("STR_PREPAGO");?>&nbsp;</td>
						<td><input type="radio" name="fPrepago" id="fPrepago1" value="1" <?php echo ($cEntidad->getPrepago() != "" && strtoupper($cEntidad->getPrepago()) == "1") ? "checked=\"checked\"" : "";?> onclick="javascript:chkPrepago(this);" />&nbsp;<label for="fPrepago1"><?php echo constant("STR_PREPAGO");?></label>&nbsp;<input type="radio" name="fPrepago" id="fPrepago0" value="0"  <?php echo ($cEntidad->getPrepago() != "" && strtoupper($cEntidad->getPrepago()) == "0") ? "checked=\"checked\"" : "";?> onclick="javascript:chkPrepago(this);" /><label for="fPrepago0"><?php echo constant("STR_CONTRATO");?></label></td>
					</tr>
 -->
					<input type="hidden" name="fPrepago" id="fPrepago1" value="1" />
					<input type="hidden" name="fNcandidatos" value="0" />
<!--
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top"><?php echo constant("STR_NU_CANDIDATOS");?>&nbsp;</td>
						<td><input type="text" name="fNcandidatos" value="<?php echo $cEntidad->getNcandidatos();?>" class="cajatexto" style="width: 40% !important;" onchange="javascript:trim(this);" /></td>
					</tr>
--> 

<!-- 
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top"><?php echo constant("STR_UMBRAL_DE_AVISO");?>&nbsp;</td>
						<td><input type="text" name="fUmbral_aviso" value="<?php echo $cEntidad->getUmbral_aviso();?>" class="cajatexto"  onchange="javascript:trim(this);" /></td>
					</tr>
-->
					<input type="hidden" name="fUmbral_aviso" value="<?php echo $cEntidad->getUmbral_aviso();?>" class="cajatexto" style="width: 40% !important;" onchange="javascript:trim(this);" />
<input type="hidden" name="fIdsPruebas" value="81,82,83,84,85,86,87" />
<input type="hidden" id="fNombreCan" name="fNombreCan" value="on">
<input type="hidden" id="fApellido1" name="fApellido1" value="on">
<input type="hidden" id="fApellido2" name="fApellido2" value="on">
<input type="hidden" id="fMailCan" name="fMailCan" value="on">
<input type="hidden" id="fEdad" name="fEdad" value="">
<input type="hidden" id="fSexo" name="fSexo" value="">
<input type="hidden" id="fNivel" name="fNivel" value="">
<input type="hidden" id="fFormacion" name="fFormacion" value="">	
<input type="hidden" id="fArea" name="fArea" value="">
<input type="hidden" id="fTelefono" name="fTelefono" value="on">
<input type="hidden" id="fSectorMB" name="fSectorMB" value="on">
<input type="hidden" id="fConcesionMB" name="fConcesionMB" value="on">
<input type="hidden" id="fBaseMB" name="fBaseMB" value="on">
<input type="hidden" id="fFecNacimientoMB" name="fFecNacimientoMB" value="on">
<input type="hidden" id="fEspecialidadMB" name="fEspecialidadMB" value="on">
<input type="hidden" id="fNivelConocimientoMB" name="fNivelConocimientoMB" value="on">
					
<?php				if(isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_MODIFICAR"))){?>
				
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF')?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="160" class="negrob" valign="top"></td>
						<td><input type="button" value="<?php echo constant("STR_CAMBIAR_CONTRASENA");?>" id="fCrea" class="botonesgrandes" onclick="javascript:cargalapagina('Empresas/cambia_pass','contrasenia');"/></td>
					</tr>
					<tr>
					    <td colspan="3">
					        <div id="contrasenia"></div>  
					    </td>
					</tr>
				
				<?php }?>
					<tr><td colspan="3" ><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				</table>
				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td class="negro"><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_CANCELAR");?>" onclick="javascript:document.forms[0].MODO.value=666;lon();document.forms[0].submit();" /></td>
						<td ><input type="submit" <?php echo ($_bModificar) ? "" : "";?>  class="botones" id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_GUARDAR");?>" /></td>
					</tr>
				</table>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="MODO" value="<?php echo (!empty($_POST['MODO'])) ? $_POST['MODO'] : '';?>" />
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
			$("#combodespuesde").show().load("jQuery.php",{sPG:"combodespuesdeEmpresas",bBus:"0",multiple:"0",nLineas:"1",bObliga:"0",fDisabled:"1",fDentroDe:f.fDentroDe.value,vSelected:"<?php echo $cEntidad->getDespuesDe();?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
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
     <?php //include_once(constant("DIR_WS_INCLUDE") . 'menus.php');?>
    <?php //include_once(constant("DIR_WS_INCLUDE") . 'derecha.php');?>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
<input type="hidden" name="empresas_next_page" value="<?php echo (isset($_POST['empresas_next_page'])) ? $cUtilidades->validaXSS($_POST['empresas_next_page']) : "1";?>" />
</form>
<script type="text/javascript">// Script para Autocompletar "off" y que valide con la W3C
	autoComplete();
</script>
</body></html>
