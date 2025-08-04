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
	<link rel="stylesheet" href="estilos/jquery.alerts.css" type="text/css" />
	<link rel="stylesheet" type="text/css" href="estilos/autosuggest_inquisitor.css" media="screen" charset="utf-8" />
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jquery.alert.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/bsn.AutoSuggest_2.1.3_comp.js"></script>
<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php");?>

function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
		seleccionarMultiple('LSTIdEmpresa[]');
		seleccionarMultiple('LSTIdProceso[]');
		f.LSTFecPrueba.value=cFechaFormat(f.LSTFecPrueba.value);
		f.LSTFecPruebaHast.value=cFechaFormat(f.LSTFecPruebaHast.value);
		lon();
		return true;
	}else	return false;
}
var oSuggest="";
function setOptions(obj, name, id){
	var f=document.forms[0];
	document.getElementById('idProceso').value="";
	//Miramos las empresas seleccionadas
	ObjEmpresas=document.forms[0].elements["LSTIdEmpresa[]"];
	ii=ObjEmpresas.length;
	sEmpresasInit="<?php echo $_EmpresaLogada;?>";
	sEmpresas="";
	var bTodos=false;
	var bInit=true;
	for(i=0; i < ii; i++ ){
		bInit=false;
		if (ObjEmpresas.options[i].value != " "){
			sEmpresas+="," + ObjEmpresas.options[i].value;
		}else{
			bTodos=true;
		}
	}
	if (bTodos){
		sEmpresasInit="<?php echo $sHijos;?>";
	}else{
		if (bInit){
			sEmpresasInit="<?php echo $_EmpresaLogada;?>";
		}
	}

	sCall = "autosuggest.php?json=true&limit=50&list=export_personalidad&vName=" + name + "&idName=" + id + "&";

	if (sEmpresas !=""){
		sEmpresas = sEmpresas.substring(1);
		sCall += "LSTIdEmpresa=" + sEmpresas + "&";
	}else{
		sCall += "LSTIdEmpresa=" + sEmpresasInit + "&";
	}
	if (f.LSTFecPrueba.value != ""){
		sLSTFecPrueba=cFechaFormat(f.LSTFecPrueba.value);
		sCall += "LSTFecPrueba=" + sLSTFecPrueba + "&";
	}
	if (f.LSTFecPruebaHast.value !=""){
		sLSTFecPruebaHast=cFechaFormat(f.LSTFecPruebaHast.value);
		sCall += "LSTFecPruebaHast=" + sLSTFecPruebaHast + "&";
	}
	var msg=validaFechaInicio();

	if (msg == "") {
		oSuggest = {
			script:sCall,
			varname:"input",
			json:true,
			shownoresults:true,
			noresults: "<?php echo constant("STR_EL_REGISTRO_NO_EXISTE");?>",
			maxresults:50,
			callback: function (obj) { document.getElementById('idProceso').value = obj.id; }
		};
		var as_json = new bsn.AutoSuggest(obj.name, oSuggest);
	}else{
		if(!obj.readOnly){
			jAlert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.","<?php echo constant("STR_NOTIFICACION");?>");
		}
	}
}
function validaFechaInicio(){
  return "";
}
function validaFechaInicioEMPRESA(){
	var f=document.forms[0];
	var msg="";
	<?php
		$fecha = date('Y-m-d');
		$nuevafecha = strtotime( '-1 year' , strtotime ( $fecha ) ) ;
		$nuevafecha = date( 'd/m/Y' , $nuevafecha );
		$sFecha =$nuevafecha;
	?>

	var dFI = toJSDate(f.LSTFecPrueba.value,"dd/mm/yyyy", "", "");
	var dFF = toJSDate(f.LSTFecPruebaHast.value,"dd/mm/yyyy", "", "");
	var fDesde   = dFI;
	var fHasta   = dFF;
	var fSistema = toJSDate("<?php echo $sFecha;?>","dd/mm/yyyy", "", "");
	if (fDesde < fSistema){
		msg +="<?php echo constant("STR_FECHA_DESDE_NO_PUEDE_SER_MENOR_QUE");?> <?php echo $sFecha;?>.\n";
	}
	if (fDesde > fHasta){
		msg +="<?php echo constant("STR_DESDE_NO_PUEDE_SER_MAYOR_QUE_FECHA_HASTA");?>\n";
	}
	return msg;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vDate("<?php echo constant("STR_FECHA_DE_PRUEBA");?>:",f.LSTFecPrueba.value,10,true);
	msg +=vDate("<?php echo constant("STR_FECHA_DE_PRUEBA");?> <?php echo constant("STR_HASTA");?>:",f.LSTFecPruebaHast.value,10,false);
	msg+=validaFechaInicio();
	var Obj=document.forms[0].elements["LSTDescPrueba[]"];
	msg +=vString("<?php echo constant("STR_PRUEBA");?>:",Obj.value,255,true);
	if (msg != "") {
		jAlert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.","<?php echo constant("STR_NOTIFICACION");?>");
		return false;
	}else return true;
}
function abrirCalendario(page, titulo){
	var miC=window.open(page, titulo,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=149,Height=148');
	miC.focus();
}
function cambiaPruebas(){
	var f= document.forms[0];
	if (seleccionarMultiple('LSTIdEmpresa[]')){
		if (seleccionarMultiple('LSTIdProceso[]')){
			$("#bid-buscar").attr('disabled','disabled');
			$("#LSTFecPruebaHast").attr('readonly','readonly');
			$("#LSTDescProceso0").attr('readonly','readonly');

			seleccionarMultiple('LSTIdEmpresa[]');
			seleccionarMultiple('LSTIdProceso[]');

			f.LSTFecPrueba.value=cFechaFormat(f.LSTFecPrueba.value);
			f.LSTFecPruebaHast.value=cFechaFormat(f.LSTFecPruebaHast.value);
			$("#pruebas").hide().load("pruebasPersonalidad.php",$("form").serializeArray(),function(){
				var sDesde=formatThisDate(f.LSTFecPrueba.value);
				var sHasta=formatThisDate(f.LSTFecPruebaHast.value);
			    setTimeout(function(){
					f.LSTFecPrueba.value=sDesde;
					f.LSTFecPruebaHast.value=sHasta;
					$("#bid-buscar").removeAttr('disabled');
					$("#LSTFecPruebaHast").removeAttr('readonly');
					$("#LSTDescProceso0").removeAttr('readonly');

        }, 2000);
			}).fadeIn("slow");
		}
	}
}

function anadirEmpresa(nombre,texto,valor){
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
			cambiaPruebas();
		}
	}
}
function quitarEmpresa(nombre,texto,valor){
	var f=document.forms[0];
	if (valor !=""){
    document.getElementById('btnAddEmpTIT').disabled=true;
    document.getElementById('fBtnDelEmpTIT').disabled=true;

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
			anadirEmpresa(nombre,aTexto[i],aValor[i]);
		}
		cambiaPruebas();
	}
  setTimeout(function(){
    document.getElementById('btnAddEmpTIT').disabled=false;
    document.getElementById('fBtnDelEmpTIT').disabled=false;
  },2000);

}
function seleccionarMultiple(nombre){
	ObjCombo=document.forms[0].elements[nombre];
	for(i=0; i < ObjCombo.length; i++ ){
		ObjCombo.options[i].selected = true;
	}
	return true;
}
function validaRepeEmpresa(nombre,valor){
	var bAniadir=false;
	if (valor !=""){
		ObjCombo=document.forms[0].elements[nombre];
		ii=ObjCombo.length;
		if (ii > 0){
			var sPKTxt = "";
			for(i=0; i < ii; i++ ){
				sPKTxt = ObjCombo.options[i].value;
				if (sPKTxt != valor){
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
function validaAniadirEmpresa(nombre){
	var f=document.forms[0];
  document.getElementById('btnAddEmpTIT').disabled=true;
  document.getElementById('fBtnDelEmpTIT').disabled=true;

	texto = f.LSTIdEmpresa0.options[f.LSTIdEmpresa0.options.selectedIndex].text;
	valor = f.LSTIdEmpresa0.value;
	if (validaRepeEmpresa(nombre, f.LSTIdEmpresa0.value)){
		anadirEmpresa(nombre,texto,valor);

	}else {
		jAlert("<?php echo constant("STR_REPETIDO");?>","<?php echo constant("STR_NOTIFICACION");?>");
	}
  setTimeout(function(){
    document.getElementById('btnAddEmpTIT').disabled=false;
    document.getElementById('fBtnDelEmpTIT').disabled=false;
  },2000);

}
function anadirPrueba(nombre,texto,valor){
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
function quitarPrueba(nombre,texto,valor){
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
			anadirPrueba(nombre,aTexto[i],aValor[i]);
		}
	}
}
function validaRepePrueba(nombre,valor){
	var bAniadir=false;
	if (valor !=""){
		ObjCombo=document.forms[0].elements[nombre];
		ii=ObjCombo.length;
		if (ii > 0){
			var sPKTxt = "";
			for(i=0; i < ii; i++ ){
				sPKTxt = ObjCombo.options[i].value;
				if (sPKTxt != valor){
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
function validaAniadirPrueba(nombre){
	var f=document.forms[0];
	texto = f.LSTDescPrueba0.options[f.LSTDescPrueba0.options.selectedIndex].text;
	valor = f.LSTDescPrueba0.value;
	if (validaRepePrueba(nombre, f.LSTDescPrueba0.value)){
		anadirPrueba(nombre,texto,valor);
	}else {
		jAlert("<?php echo constant("STR_REPETIDO");?>","<?php echo constant("STR_NOTIFICACION");?>");
	}
}
function anadirProceso(nombre,texto,valor){
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
			cambiaPruebas();
		}
	}
}
function quitarProceso(nombre,texto,valor){
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
			anadirProceso(nombre,aTexto[i],aValor[i]);
		}
		cambiaPruebas();
	}
}
function validaRepeProceso(nombre,valor){
	var bAniadir=false;
	if (valor !=""){
		ObjCombo=document.forms[0].elements[nombre];
		ii=ObjCombo.length;
		if (ii > 0){
			var sPKTxt = "";
			for(i=0; i < ii; i++ ){
				sPKTxt = ObjCombo.options[i].value;
				if (sPKTxt != valor){
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
function validaAniadirProceso(nombre){
	var f=document.forms[0];
	texto = f.LSTDescProceso0.value;
	valor = f.idProceso.value;
	if (validaRepeProceso(nombre, f.LSTDescProceso0.value)){
		anadirProceso(nombre,texto,valor);
	}else {
		jAlert("<?php echo constant("STR_REPETIDO");?>","<?php echo constant("STR_NOTIFICACION");?>");
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
<body onload="_body_onload();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');cambiaPruebas();" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>

		<form name="form" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar();">
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
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdEmpresa0"><?php echo constant("STR_EMPRESA");?></label>&nbsp;</td>
					<td >
						<table cellspacing="0" cellpadding="0" width="99%" border="0">
							<tr>
							<?php $sOption		= "<option value=' '>" . constant("STR_TODOS") . "</option>";?>
								<td width="49%" valign="top"><?php $comboEMPRESAS->setNombre("LSTIdEmpresa0");?><?php echo $comboEMPRESAS->getHTMLCombo("1","cajatexto",$cEntidad->getIdEmpresa(),"  ",$sOption);?></td>
								<td width="50" valign="top">
									<table cellspacing="0" cellpadding="0" width="100%" border="0" >
										<tr>
											<td align="center"><input type="button" class="botoncuadrado" style="color:Red;" onclick="javascript:validaAniadirEmpresa('LSTIdEmpresa[]');" name="btnAddTIT" id="btnAddEmpTIT" value=">" /></td>
										</tr>
										<tr>
											<td align="center"><input type="button" class="botoncuadrado" style="color:Red;" onclick="javascript:quitarEmpresa('LSTIdEmpresa[]',document.forms[0].elements['LSTIdEmpresa[]'].value,document.forms[0].elements['LSTIdEmpresa[]'].value);" name="fBtnDelTIT" id="fBtnDelEmpTIT" value="<" /></td>
										</tr>
									</table>
								</td>
								<td width="49%">
									<select multiple="multiple" size="6" id="LSTIdEmpresa[]" name="LSTIdEmpresa[]" class="cajatexto" style="width:100%;"></select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTDescProceso"><?php echo constant("STR_PROCESO");?></label>&nbsp;</td>
					<td >
						<table cellspacing="0" cellpadding="0" width="99%" border="0">
							<tr>
								<td width="49%" valign="top">
									<input type="text" id="LSTDescProceso0" name="LSTDescProceso0" value="" class="cajatexto" onfocus="javascript:setOptions(this, 'descProceso', 'idProceso');" onchange="javascript:trim(this);" />
									<input type="hidden" id="idProceso" value="" style="font-size: 10px; width: 20px;" />
								</td>
								<td width="50" valign="top">
									<table cellspacing="0" cellpadding="0" width="100%" border="0" >
										<tr>
											<td align="center"><input type="button" class="botoncuadrado" style="color:Red;" onclick="javascript:validaAniadirProceso('LSTIdProceso[]')" name="btnAddTIT" value=">" /></td>
										</tr>
										<tr>
											<td align="center"><input type="button" class="botoncuadrado" style="color:Red;" onclick="javascript:quitarProceso('LSTIdProceso[]',document.forms[0].elements['LSTIdProceso[]'].value,document.forms[0].elements['LSTIdProceso[]'].value);" name="fBtnDelTIT" value="<" /></td>
										</tr>
									</table>
								</td>
								<td width="49%">
									<select multiple="multiple" size="6" id="LSTIdProceso[]" name="LSTIdProceso[]" class="cajatexto" style="width:100%;"></select>
								</td>
							</tr>
						</table>

					</td>
				</tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTDescPrueba[]"><?php echo constant("STR_PRUEBA");?></label>&nbsp;</td>
					<td>
						<div id="pruebas">
							<table cellspacing="0" cellpadding="0" width="99%" border="0">
								<tr>
									<td width="48.5%" valign="top"><?php $comboPRUEBAS->setNombre("LSTDescPrueba[]");?><?php echo $comboPRUEBAS->getHTMLCombo("1","obliga",$cEntidad->getDescPrueba()," ","");?></td>
									<td width="50" valign="top">
										<table cellspacing="0" cellpadding="0" width="100%" border="0" >
											<tr>
												<td align="center" width="32">&nbsp;</td>
											</tr>
											<tr>
												<td align="center" width="32">&nbsp;</td>
											</tr>
										</table>
									</td>
									<td width="49%">&nbsp;</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>

				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFecPrueba"><?php echo constant("STR_FECHA_DE_PRUEBA");?></label>&nbsp;</td>
					<?php if ($cEntidad->getFecPrueba() != "" && $cEntidad->getFecPrueba() != "0000-00-00" && $cEntidad->getFecPrueba() != "0000-00-00 00:00:00"){
						$cEntidad->setFecPrueba($conn->UserDate($cEntidad->getFecPrueba(),constant("USR_FECHA"),false));
					}else{
						$fecha = date('Y-m-d');
						$nuevafecha = strtotime( '-1 year' , strtotime ( $fecha ) ) ;
						$nuevafecha = date( 'd/m/Y' , $nuevafecha );
						$cEntidad->setFecPrueba($nuevafecha);
					}
					?>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecPrueba','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecPrueba" name="LSTFecPrueba" value="<?php echo $cEntidad->getFecPrueba();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<?php if ($cEntidad->getFecPruebaHast() != "" && $cEntidad->getFecPruebaHast() != "0000-00-00" && $cEntidad->getFecPruebaHast() != "0000-00-00 00:00:00"){
								$cEntidad->setFecPruebaHast($conn->UserDate($cEntidad->getFecPruebaHast(),constant("USR_FECHA"),false));
								}else{
									$fecha = date('d/m/Y');
									$cEntidad->setFecPruebaHast($fecha);
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecPruebaHast','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecPruebaHast" name="LSTFecPruebaHast" value="<?php echo $cEntidad->getFecPruebaHast();?>" class="cajatexto" style="width:75px;"  /></td>
							</tr>
						</table>
					</td>
				</tr>
<!--
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdBaremo"><?php echo constant("STR_BAREMO");?></label>&nbsp;</td>
						<td><div id="comboIdBaremo"><?php $comboBAREMOS->setNombre("LSTIdBaremo");?><?php echo $comboBAREMOS->getHTMLComboNull("1","cajatexto",$cEntidad->getIdBaremo(),"onchange=\"javascript:cambiaIdBaremo()\" ","");?></div></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTFecAltaProceso"><?php echo constant("STR_FECHA_DE_ALTA_PROCESO");?></label>&nbsp;</td>
					<?php if ($cEntidad->getFecAltaProceso() != "" && $cEntidad->getFecAltaProceso() != "0000-00-00" && $cEntidad->getFecAltaProceso() != "0000-00-00 00:00:00"){
						$cEntidad->setFecAltaProceso($conn->UserDate($cEntidad->getFecAltaProceso(),constant("USR_FECHA"),false));
					}else{
						$cEntidad->setFecAltaProceso("");
					}
					?>
					<td>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_DESDE");?>&nbsp;</td>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecAltaProceso','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecAltaProceso" name="LSTFecAltaProceso" value="<?php echo $cEntidad->getFecAltaProceso();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" class="negrob"><?php echo constant("STR_HASTA");?>&nbsp;</td>
								<?php if ($cEntidad->getFecAltaProcesoHast() != "" && $cEntidad->getFecAltaProcesoHast() != "0000-00-00" && $cEntidad->getFecAltaProcesoHast() != "0000-00-00 00:00:00"){
								$cEntidad->setFecAltaProcesoHast($conn->UserDate($cEntidad->getFecAltaProcesoHast(),constant("USR_FECHA"),false));
								}else{
								$cEntidad->setFecAltaProceso("");
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=LSTFecAltaProcesoHast','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant("STR_CALENDARIO");?>" align="bottom" /></a>&nbsp;<input type="text" id="LSTFecAltaProcesoHast" name="LSTFecAltaProcesoHast" value="<?php echo $cEntidad->getFecAltaProcesoHast();?>" class="cajatexto" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdSexo"><?php echo constant("STR_SEXO");?></label>&nbsp;</td>
					<td><?php $comboSEXOS->setNombre("LSTIdSexo");?><?php echo $comboSEXOS->getHTMLCombo("1","cajatexto",$cEntidad->getIdSexo()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdEdad"><?php echo constant("STR_EDAD");?></label>&nbsp;</td>
					<td><?php $comboEDADES->setNombre("LSTIdEdad");?><?php echo $comboEDADES->getHTMLCombo("1","cajatexto",$cEntidad->getIdEdad()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdFormacion"><?php echo constant("STR_FORMACION");?></label>&nbsp;</td>
					<td><?php $comboFORMACIONES->setNombre("LSTIdFormacion");?><?php echo $comboFORMACIONES->getHTMLCombo("1","cajatexto",$cEntidad->getIdFormacion()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdNivel"><?php echo constant("STR_NIVEL");?></label>&nbsp;</td>
					<td><?php $comboNIVELESJERARQUICOS->setNombre("LSTIdNivel");?><?php echo $comboNIVELESJERARQUICOS->getHTMLCombo("1","cajatexto",$cEntidad->getIdNivel()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTIdArea"><?php echo constant("STR_AREA");?></label>&nbsp;</td>
					<td><?php $comboAREAS->setNombre("LSTIdArea");?><?php echo $comboAREAS->getHTMLCombo("1","cajatexto",$cEntidad->getIdArea()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="LSTUsuMod"><?php echo constant("STR_USUARIO_DE_MODIFICACION");?></label>&nbsp;</td>
					<td><?php $comboWI_USUARIOS->setNombre("LSTUsuMod");?><?php echo $comboWI_USUARIOS->getHTMLCombo("1","cajatexto",$cEntidad->getUsuMod()," ","");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="grisb"><?php echo constant("STR_ORDENAR_POR");?>&nbsp;</td>
					<td>
						<select id="LSTOrderBy" name="LSTOrderBy" size="1" class="cajatexto">
							<?php $sOrderBy = $cEntidad->getOrderBy();?>
							<option style='color:#000000;' value='' <?php echo (empty($sOrderBy)) ? "selected='selected'" : "";?>><?php echo constant("SLC_OPCION");?></option>
							<option style='color:#000000;' value='descEmpresa' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descEmpresa') ? "selected='selected'" : "";?>><?php echo constant("STR_EMPRESA");?></option>
							<option style='color:#000000;' value='descProceso' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descProceso') ? "selected='selected'" : "";?>><?php echo constant("STR_PROCESO");?></option>
							<option style='color:#000000;' value='nombre' <?php echo (!empty($sOrderBy) && $sOrderBy == 'nombre') ? "selected='selected'" : "";?>><?php echo constant("STR_NOMBRE");?></option>
							<option style='color:#000000;' value='apellido1' <?php echo (!empty($sOrderBy) && $sOrderBy == 'apellido1') ? "selected='selected'" : "";?>><?php echo constant("STR_APELLIDO1");?></option>
							<option style='color:#000000;' value='apellido2' <?php echo (!empty($sOrderBy) && $sOrderBy == 'apellido2') ? "selected='selected'" : "";?>><?php echo constant("STR_APELLIDO2");?></option>
							<option style='color:#000000;' value='email' <?php echo (!empty($sOrderBy) && $sOrderBy == 'email') ? "selected='selected'" : "";?>><?php echo constant("STR_EMAIL");?></option>
							<option style='color:#000000;' value='descPrueba' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descPrueba') ? "selected='selected'" : "";?>><?php echo constant("STR_PRUEBA");?></option>
							<option style='color:#000000;' value='fecPrueba' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fecPrueba') ? "selected='selected'" : "";?>><?php echo constant("STR_FECHA_DE_PRUEBA");?></option>
							<option style='color:#000000;' value='descBaremo' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descBaremo') ? "selected='selected'" : "";?>><?php echo constant("STR_BAREMO");?></option>
							<option style='color:#000000;' value='fecAltaProceso' <?php echo (!empty($sOrderBy) && $sOrderBy == 'fecAltaProceso') ? "selected='selected'" : "";?>><?php echo constant("STR_FECHA_DE_ALTA_PROCESO");?></option>
							<option style='color:#000000;' value='correctas' <?php echo (!empty($sOrderBy) && $sOrderBy == 'correctas') ? "selected='selected'" : "";?>><?php echo constant("STR_CORRECTAS");?></option>
							<option style='color:#000000;' value='contestadas' <?php echo (!empty($sOrderBy) && $sOrderBy == 'contestadas') ? "selected='selected'" : "";?>><?php echo constant("STR_CONTESTADAS");?></option>
							<option style='color:#000000;' value='percentil' <?php echo (!empty($sOrderBy) && $sOrderBy == 'percentil') ? "selected='selected'" : "";?>><?php echo constant("STR_PERCENTIL");?></option>
							<option style='color:#000000;' value='ir' <?php echo (!empty($sOrderBy) && $sOrderBy == 'ir') ? "selected='selected'" : "";?>><?php echo constant("STR_IR");?></option>
							<option style='color:#000000;' value='ip' <?php echo (!empty($sOrderBy) && $sOrderBy == 'ip') ? "selected='selected'" : "";?>><?php echo constant("STR_IP");?></option>
							<option style='color:#000000;' value='por' <?php echo (!empty($sOrderBy) && $sOrderBy == 'por') ? "selected='selected'" : "";?>><?php echo constant("STR_POR");?></option>
							<option style='color:#000000;' value='descSexo' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descSexo') ? "selected='selected'" : "";?>><?php echo constant("STR_SEXO");?></option>
							<option style='color:#000000;' value='descEdad' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descEdad') ? "selected='selected'" : "";?>><?php echo constant("STR_EDAD");?></option>
							<option style='color:#000000;' value='descFormacion' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descFormacion') ? "selected='selected'" : "";?>><?php echo constant("STR_FORMACION");?></option>
							<option style='color:#000000;' value='descNivel' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descNivel') ? "selected='selected'" : "";?>><?php echo constant("STR_NIVEL");?></option>
							<option style='color:#000000;' value='descArea' <?php echo (!empty($sOrderBy) && $sOrderBy == 'descArea') ? "selected='selected'" : "";?>><?php echo constant("STR_AREA");?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="grisb"><?php echo constant("STR_ORDEN");?>&nbsp;</td>
					<td>
						<select id="LSTOrder" name="LSTOrder" size="1" class="cajatexto">
							<?php $sOrder = $cEntidad->getOrder();?>
							<option style='color:#000000;' value='' <?php echo (empty($sOrder)) ? "selected='selected'" : "";?>><?php echo constant("SLC_OPCION");?></option>
							<option style='color:#000000;' value='ASC' <?php echo (!empty($sOrder) && $sOrder == 'ASC') ? "selected='selected'" : "";?>><?php echo constant("STR_ASCENDENTE");?></option>
							<option style='color:#000000;' value='DESC' <?php echo (!empty($sOrder) && $sOrder == 'DESC') ? "selected='selected'" : "";?>><?php echo constant("STR_DESCENDENTE");?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="grisb"><?php echo constant("STR_LINEAS_POR_PAGINA");?>&nbsp;</td>
					<td><input class="cajatexto" style="width:40px;" type="text" id="LSTLineasPagina" name="LSTLineasPagina" value="<?php echo ($cEntidad->getLineasPagina() != "") ? $cEntidad->getLineasPagina() : constant("CNF_LINEAS_PAGINA");?>" />
					</td>
				</tr>
 -->
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3" ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3" ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" ><?php echo "";//constant("MSG_ANTIGUEDAD_EXPORT_INFO");?> </td></tr>
				<tr><td colspan="3" ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
			</table>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td><input type="submit" class="botones" id="bid-buscar" name="fBtnAdd" value="<?php echo constant("STR_BUSCAR");?>" /></td>
				</tr>
			</table>
	</div>
</div>

	<input type="hidden" name="LSTOrderBy" value="<?php echo $cEntidad->getOrderBy();?>" />
	<input type="hidden" name="LSTOrder" value="<?php echo $cEntidad->getOrder();?>" />

	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_BUSCAR");?>" />
	<input type="hidden" name="export_personalidad_next_page" value="1" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" />
</div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>
