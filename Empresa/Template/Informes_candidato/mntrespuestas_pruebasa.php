<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
	$n_descargas=0;
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Consumos/ConsumosDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Consumos/Consumos.php");
		
	$oConsumos = new Consumos();
	$oConsumosDB = new ConsumosDB($conn);
		
	$oConsumos->setIdEmpresa($cEmpresa->getIdEmpresa());
	$oConsumos->setIdProceso($cProceso->getIdProceso());
	$oConsumos->setIdCandidato($cCandidato->getIdCandidato());
	$oConsumos->setCodIdiomaIso2($cEntidad->getCodIdiomaIso2());
	$oConsumos->setIdPrueba($cEntidad->getIdPrueba());
	$oConsumos->setCodIdiomaInforme($cProceso_informes->getCodIdiomaInforme());
	$oConsumos->setIdTipoInforme($cProceso_informes->getIdTipoInforme());
	$sSQLConsumos = $oConsumosDB->readLista($oConsumos);
//	echo $sSQLConsumos;
	$rsConsumos = $conn->Execute($sSQLConsumos);
	$n_descargas = $rsConsumos->recordCount();
	if ($n_descargas > 0 ){
		$n_descargas = $n_descargas-1;
	}
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
		lon();
		f.fIdProceso.value=f.elements['fIdProceso'].value;
		f.fIdCandidato.value=f.elements['fIdCandidato'].value;
		f.fIdPrueba.value=f.elements['fIdPrueba'].value;
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo constant("STR_EMPRESA");?>:",f.fIdEmpresa.value,11,true);
	msg +=vNumber("<?php echo constant("STR_PROCESO");?>:",f.elements['fIdProceso'].value,11,true);
	msg +=vNumber("<?php echo constant("STR_CANDIDATO");?>:",f.elements['fIdCandidato'].value,11,true);
	msg +=vString("<?php echo constant("STR_IDIOMA");?>:",f.fCodIdiomaIso2.value,2,true);
	msg +=vNumber("<?php echo constant("STR_PRUEBA");?>:",f.elements['fIdPrueba'].value,11,true);
	aFinalizado = document.forms[0].fFinalizado;
	sId = "";
	for(i=0; i < aFinalizado.length; i++ ){
		if (aFinalizado[i].type == "radio" && aFinalizado[i].name == "fFinalizado"){
			if (aFinalizado[i].checked)
			{
				sId = aFinalizado[i].value;
			}
		}
	}
	msg +=vNumber("<?php echo constant("STR_FINALIZADO");?>:",sId,11,true);
if (msg != "") {
	jAlert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.","<?php echo constant("STR_NOTIFICACION");?>");
	return false;
}else return true;
}
function abrirVentana(bImg, file){
	var f = document.forms[0];
	var paginacargada = "../Admin/Informes_candidato.php";
	if(f.fIdTipoInforme.value!="" && f.fIdPrueba.value!="" && f.fCodIdiomaIso2.value!="" && f.fIdBaremo.value!=""){
		$("div#exportInforme").empty();  
		$("div#exportInforme").load(paginacargada,{
			fIdPrueba: f.fIdPrueba.value,
			fIdTipoInforme: f.fIdTipoInforme.value,
			fIdEmpresa: f.fIdEmpresa.value,
			fIdProceso: f.fIdProceso.value,
			fIdCandidato: f.fIdCandidato.value,
			fCodIdiomaIso2: f.fCodIdiomaIso2.value,
			fIdBaremo: f.fIdBaremo.value,
			fCodIdiomaIso2Prueba: f.fCodIdiomaIso2Prueba.value,
			MODO:"<?php echo constant('MNT_DESCUENTA_DONGLES')?>",
			fLang:"<?php echo $sLang;?>",  
			sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" });
		muestraLoad();
		
		preurl = "view.php?bImg=" + bImg + "&File=" + file;
		prename = "File";
		var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
		miv.focus();
	}else{
		jAlert("Debe escoger una prueba, un baremo, un tipo de informe y un idioma.","<?php echo constant("STR_NOTIFICACION");?>");
	}
}
function abrirVentanaHTML(bImg, file){
	var f = document.forms[0];
	var paginacargada = "../Admin/Informes_candidato.php";
	if(f.fIdTipoInforme.value!="" && f.fIdPrueba.value!="" && f.fCodIdiomaIso2.value!="" && f.fIdBaremo.value!=""){
		$("div#exportInformeHTML").empty();  
		$("div#exportInformeHTML").load(paginacargada,{
			fIdPrueba: f.fIdPrueba.value,
			fIdTipoInforme: f.fIdTipoInforme.value,
			fIdEmpresa: f.fIdEmpresa.value,
			fIdProceso: f.fIdProceso.value,
			fIdCandidato: f.fIdCandidato.value,
			fCodIdiomaIso2: f.fCodIdiomaIso2.value,
			fIdBaremo: f.fIdBaremo.value,
			fCodIdiomaIso2Prueba: f.fCodIdiomaIso2Prueba.value,
			MODO:"<?php echo constant('MNT_DESCUENTA_DONGLES_HTML')?>",
			fLang:"<?php echo $sLang;?>",  
			sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" });
		muestraLoadHTML();
		
		preurl = "view.php?bImg=" + bImg + "&File=" + file;
		prename = "File";
		var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
		miv.focus();
	}else{
		jAlert("Debe escoger una prueba, un baremo, un tipo de informe y un idioma.","<?php echo constant("STR_NOTIFICACION");?>");
	}
}
function abrirVentanaWORD(bImg, file){
	var f = document.forms[0];
	var paginacargada = "../Admin/Informes_candidato.php";
	if(f.fIdTipoInforme.value!="" && f.fIdPrueba.value!="" && f.fCodIdiomaIso2.value!="" && f.fIdBaremo.value!=""){
		$("div#exportInformeWORD").empty();  
		$("div#exportInformeWORD").load(paginacargada,{
			fIdPrueba: f.fIdPrueba.value,
			fIdTipoInforme: f.fIdTipoInforme.value,
			fIdEmpresa: f.fIdEmpresa.value,
			fIdProceso: f.fIdProceso.value,
			fIdCandidato: f.fIdCandidato.value,
			fCodIdiomaIso2: f.fCodIdiomaIso2.value,
			fIdBaremo: f.fIdBaremo.value,
			fCodIdiomaIso2Prueba: f.fCodIdiomaIso2Prueba.value,
			MODO:"<?php echo constant('MNT_DESCUENTA_DONGLES_WORD')?>",
			fLang:"<?php echo $sLang;?>",  
			sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" });
		muestraLoadWORD();
		
		preurl = "view.php?bImg=" + bImg + "&File=" + file;
		prename = "File";
		var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
		miv.focus();
	}else{
		jAlert("Debe escoger una prueba, un baremo, un tipo de informe y un idioma.","<?php echo constant("STR_NOTIFICACION");?>");
	}
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

function cambiaidiomas(){
	var f = document.forms[0];
	var paginacargada = "Informes_candidato.php";
	$("div#idiomasInforme").load(paginacargada,{
		fIdPrueba: f.fIdPrueba.value,
		fIdTipoInforme: f.fIdTipoInforme.value,
		MODO:"<?php echo constant('MNT_LISTAIDIOMAS')?>",
		fLang:"<?php echo $sLang;?>",  
		sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" });
}
function cambiaInformes(){
	var f = document.forms[0];
	var paginacargada = "Informes_candidato.php";
	$("div#tiposInformes").load(paginacargada,{
		fIdPrueba: f.fIdPrueba.value,
		fIdTipoInforme:"<?php echo $cProceso_informes->getIdTipoInforme();?>",
        fCodIdiomaInforme: "<?php echo $cProceso_informes->getCodIdiomaInforme();?>",
		MODO:"<?php echo constant('MNT_LISTATIPOS')?>",
		fLang:"<?php echo $sLang;?>",  
		sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" });
	$("div#baremos").load(paginacargada,{
		fIdPrueba: f.fIdPrueba.value,
		fIdBaremo:"<?php echo $cProceso_informes->getIdBaremo();?>",
		MODO:"<?php echo constant('MNT_LISTABAREMOS')?>",
		fLang:"<?php echo $sLang;?>",  
		sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" });
	$("div#idiomasInforme").load(paginacargada,{
		fIdPrueba: f.fIdPrueba.value,
		fIdTipoInforme: "<?php echo $cProceso_informes->getIdTipoInforme();?>",
		fCodIdiomaInforme: "<?php echo $cProceso_informes->getCodIdiomaInforme();?>",
		MODO:"<?php echo constant('MNT_LISTAIDIOMAS')?>",
		fLang:"<?php echo $sLang;?>",  
		sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" });
}
function exporta(){

	var f = document.forms[0];
	var paginacargada = "../Admin/Informes_candidato.php";
	if(f.fIdTipoInforme.value!="" && f.fIdPrueba.value!="" && f.fCodIdiomaIso2.value!="" && f.fIdBaremo.value!=""){
		$("div#exportInforme").empty();  
		$("div#exportInforme").load(paginacargada,{
			fIdPrueba: f.fIdPrueba.value,
			fIdTipoInforme: f.fIdTipoInforme.value,
			fIdEmpresa: f.fIdEmpresa.value,
			fIdProceso: f.fIdProceso.value,
			fIdCandidato: f.fIdCandidato.value,
			fCodIdiomaIso2: f.fCodIdiomaIso2.value,
			fIdBaremo: f.fIdBaremo.value,
			fCodIdiomaIso2Prueba: f.fCodIdiomaIso2Prueba.value,
			MODO:"<?php echo constant('MNT_EXPORTA')?>",
			fLang:"<?php echo $sLang;?>",  
			sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" });
		muestraLoad();
	}else{
		jAlert("Debe escoger una prueba, un baremo, un tipo de informe y un idioma.","<?php echo constant("STR_NOTIFICACION");?>");
	}
}
function exportaHTML(){

	var f = document.forms[0];
	var paginacargada = "../Admin/Informes_candidato.php";
	if(f.fIdTipoInforme.value!="" && f.fIdPrueba.value!="" && f.fCodIdiomaIso2.value!="" && f.fIdBaremo.value!=""){
		$("div#exportInformeHTML").empty();  
		$("div#exportInformeHTML").load(paginacargada,{
			fIdPrueba: f.fIdPrueba.value,
			fIdTipoInforme: f.fIdTipoInforme.value,
			fIdEmpresa: f.fIdEmpresa.value,
			fIdProceso: f.fIdProceso.value,
			fIdCandidato: f.fIdCandidato.value,
			fCodIdiomaIso2: f.fCodIdiomaIso2.value,
			fIdBaremo: f.fIdBaremo.value,
			fCodIdiomaIso2Prueba: f.fCodIdiomaIso2Prueba.value,
			MODO:"<?php echo constant('MNT_EXPORTA_HTML')?>",
			fLang:"<?php echo $sLang;?>",  
			sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" });
		muestraLoadHTML();
	}else{
		jAlert("Debe escoger una prueba, un baremo, un tipo de informe y un idioma.","<?php echo constant("STR_NOTIFICACION");?>");
	}
}
function exportaWORD(){

	var f = document.forms[0];
	var paginacargada = "../Admin/Informes_candidato.php";
	if(f.fIdTipoInforme.value!="" && f.fIdPrueba.value!="" && f.fCodIdiomaIso2.value!="" && f.fIdBaremo.value!=""){
		$("div#exportInformeWORD").empty();  
		$("div#exportInformeWORD").load(paginacargada,{
			fIdPrueba: f.fIdPrueba.value,
			fIdTipoInforme: f.fIdTipoInforme.value,
			fIdEmpresa: f.fIdEmpresa.value,
			fIdProceso: f.fIdProceso.value,
			fIdCandidato: f.fIdCandidato.value,
			fCodIdiomaIso2: f.fCodIdiomaIso2.value,
			fIdBaremo: f.fIdBaremo.value,
			fCodIdiomaIso2Prueba: f.fCodIdiomaIso2Prueba.value,
			MODO:"<?php echo constant('MNT_EXPORTA_WORD')?>",
			fLang:"<?php echo $sLang;?>",  
			sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" });
		muestraLoadWORD();
	}else{
		jAlert("Debe escoger una prueba, un baremo, un tipo de informe y un idioma.","<?php echo constant("STR_NOTIFICACION");?>");
	}
}

function muestraLoad(){
	document.getElementById("btnExportar").style.display = 'none';
	document.getElementById("imgLoad").style.display = 'block';
}
function muestraLoadHTML(){
	document.getElementById("btnExportarHTML").style.display = 'none';
	document.getElementById("imgLoadHTML").style.display = 'block';
}
function muestraLoadWORD(){
	document.getElementById("btnExportarWORD").style.display = 'none';
	document.getElementById("imgLoadWORD").style.display = 'block';
}
function escondeLoad(){
	document.getElementById("btnExportar").style.display = 'block';
	document.getElementById("imgLoad").style.display = 'none';
}
function escondeLoadHTML(){
	document.getElementById("btnExportarHTML").style.display = 'block';
	document.getElementById("imgLoadHTML").style.display = 'none';
}
function escondeLoadWORD(){
	document.getElementById("btnExportarWORD").style.display = 'block';
	document.getElementById("imgLoadWORD").style.display = 'none';
}
function muestraBoton(){
	document.getElementById("btnExportar").style.display = 'block';
	document.getElementById("imgLoad").style.display = 'none';
}
function muestraBotonHTML(){
	document.getElementById("btnExportarHTML").style.display = 'block';
	document.getElementById("imgLoadHTML").style.display = 'none';
}
function muestraBotonWORD(){
	document.getElementById("btnExportarWORD").style.display = 'block';
	document.getElementById("imgLoadWORD").style.display = 'none';
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
<body onload="_body_onload();cambiaInformes();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0"  title="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" alt="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
	
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
			<table cellspacing="2" cellpadding="2" width="100%" border="0">
				<tr><td colspan="3" align="center" class="naranjab"><?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){echo(constant("STR_ANIADIR"));}else{echo(constant("STR_MODIFICAR"));}?></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_EMPRESA");?>&nbsp;</td>
					<td><?php echo $cEmpresa->getNombre()?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_PROCESO");?>&nbsp;</td>
						<td><?php echo $cProceso->getNombre()?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_CANDIDATO");?>&nbsp;</td>
						<td><?php echo $cCandidato->getNombre() . " " . $cCandidato->getApellido1() . " " . $cCandidato->getApellido2()?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdPrueba"><?php echo constant("STR_PRUEBA");?></label>&nbsp;</td>
					<td>
					<?php 
						if(sizeof($aPruebas)>0)
						{?>
							<select class="obliga" name="fIdPrueba" onchange="cambiaInformes();">
								<option value=""><?php echo constant('SLC_OPCION')?></option>
								<?php
								$sSelected=""; 
								for($i=0;$i<sizeof($aPruebas);$i++)
								{
									$cPruebas = new Pruebas();
									$cPruebas = $aPruebas[$i];
									if ($cPruebas->getIdPrueba() == $cEntidad->getIdPrueba()){
										$sSelected= "selected=\"selected\"";
										
									}else{
										$sSelected="";
									}
									?>
									<option value="<?php echo $cPruebas->getIdPrueba()?>" <?php echo $sSelected?> ><?php echo $cPruebas->getNombre()?></option>
								
						<?php	}?>
							
							</select>	
						<?php
						}else{
							echo constant("MSG_CANDIDATO_SIN_PRUEBAS_FINALIZADSA_PROCESO");
						} ?>
					</td>
				</tr>
				<?php 
				if(sizeof($aPruebas)>0){?>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdBaremo"><?php echo constant("STR_BAREMO");?></label>&nbsp;</td>
						<td><div id="baremos">
								<select class="obliga" name="fIdBaremo">
									<option value=""><?php echo constant('SLC_OPCION')?></option>
								</select>
							</div>
						</td>
					</tr>	
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdTipoInforme"><?php echo constant("STR_TIPOSINFORMES");?></label>&nbsp;</td>
						<td><div id="tiposInformes">
								<select class="obliga" name="fIdTipoInforme">
									<option value=""><?php echo constant('SLC_OPCION')?></option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fCodIdiomaIso2"><?php echo constant("STR_IDIOMA");?></label>&nbsp;</td>
						<td><div id="idiomasInforme">
								<select name="fCodIdiomaIso2" class="obliga">
									<option value=""><?php echo constant('SLC_OPCION')?></option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top">nº Descargas&nbsp;</td>
						<td><div id="n_descargas"><?php echo $n_descargas;?></div>
						</td>
					</tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="40" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"></td>
						<td valign="bottom">
							<div id="btnExportar">
								<input type="button" name="fExporta" class="botonesgrandes" value="Exportar PDF" onclick="javascript:exporta();"/>
							</div>
							<div id="imgLoad" style="display:none;">
								<img src="<?php echo constant("HTTP_SERVER") . constant("DIR_WS_GRAF") . "loader-informes.gif"?>" />
							</div>
							<div id="exportInforme">
							<br />
							<br />
							</div>
						</td>
					</tr>
<!-- 
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="40" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"></td>
						<td valign="bottom">
							<div id="btnExportarHTML">
								<input type="button" name="fExportaHTML" class="botonesgrandes" value="Exportar HTML" onclick="javascript:exportaHTML();"/>
							</div>
							<div id="imgLoadHTML" style="display:none;">
								<img src="<?php echo constant("HTTP_SERVER") . constant("DIR_WS_GRAF") . "loader-informes.gif"?>" />
							</div>
							<div id="exportInformeHTML">
							<br />
							<br />
							</div>
						</td>
					</tr>
 -->
<?php 
if ($cEntidad->getIdPrueba() == "39")
{
?>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="40" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"></td>
						<td valign="bottom">
							<div id="btnExportarWORD">
								<input type="button" name="fExportaWORD" class="botonesgrandes" value="Exportar WORD" onclick="javascript:exportaWORD();"/>
							</div>
							<div id="imgLoadWORD" style="display:none;">
								<img src="<?php echo constant("HTTP_SERVER") . constant("DIR_WS_GRAF") . "loader-informes.gif"?>" />
							</div>
							<div id="exportInformeWORD">
							<br />
							<br />
							</div>
						</td>
					</tr>
<?php 
}
?>					
				<?php
				} ?>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="javascript:document.forms[0].MODO.value=document.forms[0].ORIGEN.value;lon();document.forms[0].submit();" /></td>
		</tr>
	</table>
	</div>
	
</div>
	<input type="hidden" name="fCodIdiomaIso2Prueba" value="<?php echo $cEntidad->getCodIdiomaIso2()?>" />
	<input type="hidden" name="fIdEmpresa" value="<?php echo $cEmpresa->getIdEmpresa()?>" />
	<input type="hidden" name="fIdProceso" value="<?php echo $cProceso->getIdProceso()?>" />
	<input type="hidden" name="fIdCandidato" value="<?php echo $cCandidato->getIdCandidato()?>" />
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="LSTIdEmpresaHast" value="<?php echo (isset($_POST['LSTIdEmpresaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaHast']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresa" value="<?php echo (isset($_POST['LSTIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresa']) : "";?>" />
	<input type="hidden" name="LSTIdProcesoHast" value="<?php echo (isset($_POST['LSTIdProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdProcesoHast']) : "";?>" />
	<input type="hidden" name="LSTIdProceso" value="<?php echo (isset($_POST['LSTIdProceso'])) ? $cUtilidades->validaXSS($_POST['LSTIdProceso']) : "";?>" />
	<input type="hidden" name="LSTIdCandidatoHast" value="<?php echo (isset($_POST['LSTIdCandidatoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidatoHast']) : "";?>" />
	<input type="hidden" name="LSTIdCandidato" value="<?php echo (isset($_POST['LSTIdCandidato'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidato']) : "";?>" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo (isset($_POST['LSTCodIdiomaIso2'])) ? $cUtilidades->validaXSS($_POST['LSTCodIdiomaIso2']) : "";?>" />
	<input type="hidden" name="LSTIdPruebaHast" value="<?php echo (isset($_POST['LSTIdPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPruebaHast']) : "";?>" />
	<input type="hidden" name="LSTIdPrueba" value="<?php echo (isset($_POST['LSTIdPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTIdPrueba']) : "";?>" />
	<input type="hidden" name="LSTFinalizadoHast" value="<?php echo (isset($_POST['LSTFinalizadoHast'])) ? $cUtilidades->validaXSS($_POST['LSTFinalizadoHast']) : "";?>" />
	<input type="hidden" name="LSTFinalizado" value="<?php echo (isset($_POST['LSTFinalizado'])) ? $cUtilidades->validaXSS($_POST['LSTFinalizado']) : "";?>" />
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
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $cUtilidades->validaXSS($_POST['LSTLineasPagina']) : constant("CNF_LINEAS_PAGINA");?>" />
	<input type="hidden" name="respuestas_pruebas_next_page" value="<?php echo (isset($_POST['respuestas_pruebas_next_page'])) ? $cUtilidades->validaXSS($_POST['respuestas_pruebas_next_page']) : "1";?>" />
	</div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>
