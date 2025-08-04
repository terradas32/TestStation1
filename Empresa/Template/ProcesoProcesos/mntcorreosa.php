<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }

    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
    $cEmpresas = new Empresas();
    $cEmpresasDB = new EmpresasDB($conn);
    $cCiegosDB = new CandidatosDB($conn);

    $_idEmpresa=(!empty($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : $_POST["fIdEmpresaAsig"];
    $_idProceso=(!empty($_POST["fIdProceso"])) ? $_POST["fIdProceso"] : $_POST["fIdProcesoAsig"];
   	$cEmpresas->setIdEmpresa($_idEmpresa);
   	$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo constant("NOMBRE_SITE");?></title>
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<link rel="stylesheet" href="estilos/jquery.alerts.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jquery.alert.js"></script>
  <script src="https://cdn.tiny.cloud/1/19u4q91vla6r5niw2cs7kaymfs18v3j11oizctq52xltyrf4/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

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
function enviarNuevo(modo)
{
	var f=document.forms[0];
	f.MODO.value =modo;
	f.submit();
}
function validaForm(cuerpo)
{
	var f=document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo constant("STR_TIPO_CORREO");?>:",f.fIdTipoCorreoNew.value,11,true);
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombreNew.value,255,true);
	msg +=vString("<?php echo constant("STR_DESCRIPCION");?>:",f.fDescripcionNew.value,1000,false);
	msg +=vString("<?php echo constant("STR_ASUNTO");?>:",f.fAsuntoNew.value,255,true);
	msg +=vString("<?php echo constant("STR_CUERPO");?>:",cuerpo,16777215,true);
if (msg != "") {
	jAlert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.","<?php echo constant("STR_NOTIFICACION");?>");
	return false;
}else return true;
}
function validaFormSinTipo(cuerpo)
{
	var f=document.forms[0];
	var msg="";
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombreNew.value,255,true);
	msg +=vString("<?php echo constant("STR_DESCRIPCION");?>:",f.fDescripcionNew.value,1000,false);
	msg +=vString("<?php echo constant("STR_ASUNTO");?>:",f.fAsuntoNew.value,255,true);
	msg +=vString("<?php echo constant("STR_CUERPO");?>:",cuerpo,16777215,true);
if (msg != "") {
	jAlert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.","<?php echo constant("STR_NOTIFICACION");?>");
	return false;
}else return true;
}
function abrirVentana(bImg, file){
	preurl = "view.php?bImg=" + bImg + "&File=" + file;
	prename = "File";
	var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
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
	if (eval("document.getElementById('cabecera-menu-seleccionado').innerHTML") != null){
		document.getElementById('cabecera-menu-seleccionado').innerHTML=titulo;
		document.forms[0]._TituloOpcion.value=titulo;
	}
}
function cambiacorreos(pag)
{
	var f = document.forms[0];
	f.sPG.value = pag;
	var paginacargada = "jQuery.php";
	f.MODO.value="<?php echo constant('MNT_CARGACANDIDATOS');?>";
	$("div#muestracorreo").hide();
	$("div#combocorreos").load(paginacargada, $("form").serializeArray());
}
function nuevaplantilla()
{
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	$("div#muestracorreo").hide().load(paginacargada,{fNuevo:"1",MODO:"<?php echo constant('MNT_NUEVOCORREO')?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
}
function cancela()
{
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	$("div#muestracorreo").hidde();
}
function cargaplantilla()
{
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";

	if(f.fIdCorreo.value!=""){
    $("div#muestracorreo").show();
    $("div#DatosCorreo").hide().load(paginacargada,{fConsulta:"1",fIdTipoCorreo:f.fIdTipoCorreo.value,fIdCorreo:f.fIdCorreo.value,fIdEmpresa:f.fIdEmpresa.value,fIdProceso:f.fIdProceso.value,MODO:"<?php echo constant('MNT_NUEVOCORREO')?>", fLang:"<?php echo $sLang;?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }, function() {
        $("#fNombreNew").val($("#fNombreDato").val());
        $("#fAsuntoNew").val($("#fAsuntoDato").val());
        $("#fDescripcionNew").val($("#fDescripcionDato").val());
        var vCuerpoNew = $("#fCuerpoDato").val();
        tinymce.get('fCuerpoNew').focus();
        tinymce.activeEditor.setContent(vCuerpoNew);
    });
	}else{
    $("div#muestracorreo").hide();
	}
}
function consultacorreoproceso(idEmpresa,idProceso,idTipoCorreo,idCorreo){
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	f.fIdTipoCorreo.value=idTipoCorreo;
	cambiacorreos('combocorreos');
  setTimeout(function(){
	   f.fIdCorreo.value=idCorreo;
  }, 1000); //sleep

	$("div#DatosCorreo").hide().load(paginacargada,
	{
		fConsultaAsignados:"1",
		fIdEmpresaAsig:idEmpresa,
		fIdProcesoAsig:idProceso,
		fIdTipoCorreoAsig:idTipoCorreo,
		fIdCorreoAsig:idCorreo,
		MODO:"<?php echo constant('MNT_NUEVOCORREO')?>",
		fLang:"<?php echo $sLang;?>",
		sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
	}, function() {
      $("#fNombreNew").val($("#fNombreDato").val());
      $("#fAsuntoNew").val($("#fAsuntoDato").val());
      $("#fDescripcionNew").val($("#fDescripcionDato").val());
      var vCuerpoNew = $("#fCuerpoDato").val();
      tinymce.get('fCuerpoNew').focus();
      tinymce.activeEditor.setContent(vCuerpoNew);
      $("div#muestracorreo").show();
  });

}
function borraasignados(idEmpresa,idProceso,idTipoCorreo,idCorreo){

	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	$("div#listacorreos").hide().load(paginacargada,
	{
		fBorra:"1",
		fIdEmpresaAsig:idEmpresa,
		fIdProcesoAsig:idProceso,
		fIdTipoCorreoAsig:idTipoCorreo,
		fIdCorreoAsig:idCorreo,
		MODO:"<?php echo constant('MNT_LISTACORREOS')?>",
		fLang:"<?php echo $sLang;?>",
		sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
	}).fadeIn("slow");
}
function listacorreos()
{
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	$("div#listacorreos").hide().load(paginacargada,
			{fIdProceso: f.fIdProceso.value,
			fIdEmpresa: f.fIdEmpresa.value,
			MODO:"<?php echo constant('MNT_LISTACORREOS')?>",
			fLang:"<?php echo $sLang;?>",
			sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }
	).fadeIn("slow");
}
function validaEnvioCorreo(){
	var f = document.forms[0];

	if (document.forms[0].fIListaCorreos.value > "0"){
		document.forms[0].action='EnviarCorreos.php';
		document.forms[0].MODO.value=<?php echo constant('MNT_NUEVO')?>;
		block('4');
		document.forms[0]._clicado.value=29;
		document.forms[0].submit()
	}else{
		guardaplantilla();
	}
}
function guardaplantilla(){
	var f = document.forms[0];

  var myf = $('#fCuerpoNew_ifr');
  var editorContent = $('#tinymce[data-id="fCuerpoNew"]', myf.contents()).html();
	//alert(editorContent);

	var paginacargada = "ProcesoProcesos.php";
	if(f.fIdTipoCorreoNew != null){
		if (f.fIListaCorreos.value == 0){
			if(validaForm(editorContent)){
				$("div#listacorreos").hide().load(paginacargada,
					{
						fAccion:f.fAccion.value,
						fIdProceso: f.fIdProceso.value,
						fIdEmpresa: f.fIdEmpresa.value,
						fIdTipoCorreo: f.fIdTipoCorreoNew.value,
						fIdCorreo: f.fIdCorreo.value,
						fAsuntoNew: f.fAsuntoNew.value,
						fNombreNew: f.fNombreNew.value,
						fDescripcionNew: f.fDescripcionNew.value,
						fCuerpoNew: editorContent,
						MODO:"<?php echo constant('MNT_LISTACORREOS')?>",
						fLang:"<?php echo $sLang;?>",
						sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
					}
				).fadeIn("slow");
			}
		}else{
			if(validaFormSinTipo(editorContent)){
				document.forms[0].action='EnviarCorreos.php';
				document.forms[0].MODO.value=<?php echo constant('MNT_NUEVO')?>;
				block('4');
				document.forms[0]._clicado.value=29;
				document.forms[0].submit();
			}
		}

	}else{
		if(validaFormSinTipo(editorContent)){
			$("div#listacorreos").hide().load(paginacargada,
			{
					fAccion:f.fAccion.value,
					fIdProceso: f.fIdProceso.value,
					fIdEmpresa: f.fIdEmpresa.value,
					fIdTipoCorreo: f.fIdTipoCorreo.value,
					fIdCorreo: f.fIdCorreo.value,
					fAsuntoNew: f.fAsuntoNew.value,
					fNombreNew: f.fNombreNew.value,
					fDescripcionNew: f.fDescripcionNew.value,
					fCuerpoNew: editorContent,
					MODO:"<?php echo constant('MNT_LISTACORREOS')?>",
					fLang:"<?php echo $sLang;?>",
					sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
				}
			).fadeIn("slow",function(){
					document.forms[0].action='EnviarCorreos.php';
					document.forms[0].MODO.value=<?php echo constant('MNT_NUEVO')?>;
					block('4');
					document.forms[0]._clicado.value=29;
					document.forms[0].submit();
				});
		}
	}
}
function programarEnvio(){
	var f = document.forms[0];
	var myf  = "";

	if (f.fIListaCorreos.value > "0"){
		//Ya tiene un correo asignado, mostramos la capa de tiempo programado
		$("div#programarEnvio").show(1000);
	}else{
    var myf = $('#fCuerpoNew_ifr');
    var editorContent = $('#tinymce[data-id="fCuerpoNew"]', myf.contents()).html();

		var paginacargada = "ProcesoProcesos.php";
		if(f.fIdTipoCorreoNew != null){
			if (f.fIListaCorreos.value == 0){
				if(validaForm(editorContent)){
					$("div#listacorreos").hide().load(paginacargada,
						{
							fAccion:f.fAccion.value,
							fIdProceso: f.fIdProceso.value,
							fIdEmpresa: f.fIdEmpresa.value,
							fIdTipoCorreo: f.fIdTipoCorreoNew.value,
							fIdCorreo: f.fIdCorreo.value,
							fAsuntoNew: f.fAsuntoNew.value,
							fNombreNew: f.fNombreNew.value,
							fDescripcionNew: f.fDescripcionNew.value,
							fCuerpoNew: editorContent,
							MODO:"<?php echo constant('MNT_LISTACORREOS')?>",
							fLang:"<?php echo $sLang;?>",
							sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
						}
					).fadeIn("slow",function(){	$("div#programarEnvio").show(1000);	});
				}
			}
		}else{
			if(validaFormSinTipo(editorContent)){
				$("div#listacorreos").hide().load(paginacargada,
					{
						fAccion:f.fAccion.value,
						fIdProceso: f.fIdProceso.value,
						fIdEmpresa: f.fIdEmpresa.value,
						fIdTipoCorreo: f.fIdTipoCorreo.value,
						fIdCorreo: f.fIdCorreo.value,
						fAsuntoNew: f.fAsuntoNew.value,
						fNombreNew: f.fNombreNew.value,
						fDescripcionNew: f.fDescripcionNew.value,
						fCuerpoNew: editorContent,
						MODO:"<?php echo constant('MNT_LISTACORREOS')?>",
						fLang:"<?php echo $sLang;?>",
						sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
					}
				).fadeIn("slow",function(){	$("div#programarEnvio").show(1000);	});
			}
		}
	}
}
function guardaProgramarEnvio(modo){
	var f = document.forms[0];
	var paginacargada = "ProcesoProcesos.php";
	var	hora="";
	var msg="";
	<?php
		$sHoraInicio= "";
		$aInicio=explode(" ", $cEntidad->getFechaInicio());
		$sHoraInicio= substr($aInicio[1], 0, 5);  // HH:MM
		$aHoraInicio= explode(":", $sHoraInicio);
	?>
	var fechaI = "<?php echo $conn->UserDate($cEntidad->getFechaInicio(),constant("USR_FECHA"),false);?>";	var horaI="<?php echo $aHoraInicio[0];?>";	var minI="<?php echo $aHoraInicio[1];?>";
	<?php
		$sHoraFin= "";
		$aFin=explode(" ", $cEntidad->getFechaFin());
		$sHoraFin= substr($aFin[1], 0, 5);  // HH:MM
		$aHoraFin= explode(":", $sHoraFin);
	?>
	var fechaF = "<?php echo $conn->UserDate($cEntidad->getFechaFin(),constant("USR_FECHA"),false);?>";	var horaF="<?php echo $aHoraFin[0];?>";	var minF="<?php echo $aHoraFin[1];?>";
	<?php	$sFecha = date("d/m/Y");	$sHoraJS = date("G");	$sMin = date("i");

 	$dt=new datetime("now",new datetimezone($cEmpresas->getTimezone()));
	$fecActual = gmdate("Y-m-d H:i:s",(time()+$dt->getOffset()));	//Fecha actual de la Zona horaria
	$aFEC = explode(" ", $fecActual);
	$aHOR = explode(":", $aFEC[1]);
	$sFecha = $conn->UserDate($aFEC[0],constant("USR_FECHA"),false);
	$sHoraJS = intval($aHOR[0], 10);
	$sMin = $aHOR[1];
	?>
	var fSistema = toJSDate("<?php echo $sFecha?>","dd/mm/yyyy", <?php echo $sHoraJS?>, <?php echo $sMin?>);

	msg +=vDate("<?php echo constant("STR_FECHA");?>:",f.fFecEnvioProgramado.value,10,true);
	msg +=vString("<?php echo constant("STR_HORA_DE_INICIO");?>:",f.fHoraInicioProgramado.value,5,true);
	hora = f.fHoraInicioProgramado.value;
	a=hora.charAt(0);
	b=hora.charAt(1);
	c=hora.charAt(2);
	d=hora.charAt(3);
	if (a>=2 && b>3){
		msg +="\nHora programada incorrecta.";
	}
	if(hora.length!=5 || c!= ":"){
		msg +="\n<?php echo constant("ERR_HORA_PROGRAMADA_FORMATO");?>";
	}
	if (d>5) {
		msg +="\n<?php echo constant("ERR_MINUTOS_PROGRAMADA_MAXIMO");?>";
	}
	if (msg != "") {
		jAlert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.","<?php echo constant("STR_NOTIFICACION");?>");
	}else{
		var dFI = toJSDate(fechaI,"dd/mm/yyyy", horaI, minI);	var dFF = toJSDate(fechaF,"dd/mm/yyyy", horaF, minF);
		var fDesde   = dFI;	var fHasta   = dFF;	var HP= f.fHoraInicioProgramado.value;
		var aIP=HP.charAt(0);	var bIP=HP.charAt(1);	var cIP=HP.charAt(2);	var dIP=HP.charAt(3);	var eIP=HP.charAt(4);
		var horaIP = aIP + bIP;	var minIP = dIP + eIP;
		var fProgramada = toJSDate(f.fFecEnvioProgramado.value,"dd/mm/yyyy", horaIP, minIP);
		var bOk = false;
		if((fProgramada >= fDesde) && (fProgramada <= fHasta)){	bOk=true;	}
		if (bOk){	if (fProgramada < fSistema){	bOk = false;	}}
		if (bOk){
			var sFecEnvioProgramado = cFechaFormat(f.fFecEnvioProgramado.value);
			f.fBtnOkGuardaProgramarEnvio.disabled=true;
			$("div#programarEnvioOK").hide().load(paginacargada,
			{
				fIdProceso: f.fIdProceso.value,
				fIdEmpresa: f.fIdEmpresa.value,
				fFecEnvioProgramado: sFecEnvioProgramado,
				fHoraInicioProgramado: f.fHoraInicioProgramado.value,
				MODO:modo,
				fLang:"<?php echo $sLang;?>",
				sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
			},function( response, status, xhr )
			{
				if ( response == "OK" ) {
					$( "div#programarEnvioOK" ).html("");
					document.forms[0].fBtnOkGuardaProgramarEnvio.disabled=false;
					jAlert("<?php echo constant("STR_ENVIO_PROGRAMADO");?>","<?php echo constant("STR_NOTIFICACION");?>");
          if (document.forms[0].fIListaCorreos.value > "0"){
            document.forms[0].action='EnviarCorreos.php';
            document.forms[0].MODO.value=<?php echo constant('MNT_LISTAR');?>;
            block('4');
            document.forms[0]._clicado.value=29;
            document.forms[0].submit();
          }
				}
			}).fadeIn("slow");
		}else{
			jAlert("<?php echo constant("ERR_FECHA_PROGRAMADA_RANGO");?>");
		}
	}
}
function guardaasignados(){
	var f = document.forms[0];

  var myf = $('#fCuerpoNew_ifr');
  var editorContent = $('#tinymce[data-id="fCuerpoNew"]', myf.contents()).html();
  //alert(editorContent);

	var paginacargada = "ProcesoProcesos.php";

		if(validaForm(editorContent)){
			$("div#listacorreos").hide().load(paginacargada,
				{
					fAccion:f.fAccion.value,
					fIdCorreoNew:f.fIdCorreoNew.value,
					fIdProceso: f.fIdProceso.value,
					fIdEmpresa: f.fIdEmpresa.value,
					fIdTipoCorreo: f.fIdTipoCorreoNew.value,
					fAsuntoNew: f.fAsuntoNew.value,
					fNombreNew: f.fNombreNew.value,
					fDescripcionNew: f.fDescripcionNew.value,
					fCuerpoNew: editorContent,
					MODO:"<?php echo constant('MNT_LISTACORREOS')?>",
					fLang:"<?php echo $sLang;?>",
					sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
				}
			).fadeIn("slow");
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
<body onload="_body_onload();listacorreos();cambiacorreos('combocorreos');block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');"  onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0"  title="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" alt="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>

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
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" class="negro" valign="top"><?php echo constant("STR_LOS_CAMPOS_MARCADOS_CON");?> <input type="text" name="--" value="" class="obliga" style="width:25px;height:10px;" onfocus="blur();" /> <?php echo constant("STR_OBLIGATORIOS");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td width="100%" style="border-bottom: 1px solid #000000; height:35px">
						<ul class="listaProceso">
							<li><span><img src="./graf/iconsMenu/Tools.png" title="Datos proceso" alt="Datos proceso" /></span><?php echo constant("STR_DATOS_PROCESO");?></li>
							<li class="mArrow">&nbsp;</li>
							<li><span><img src="./graf/iconsMenu/Folder.png" title="Pruebas" alt="Pruebas" /></span><?php echo constant("STR_PRUEBAS");?></li>
							<li class="mArrow">&nbsp;</li>
							<li><span><img src="./graf/iconsMenu/User.png" title="Candidatos" alt="Candidatos" /></span><?php echo constant("STR_CANDIDATOS");?></li>
							<li class="mArrow">&nbsp;</li>
							<li class="mActivo"><span><img src="./graf/iconsMenu/mail.png" title="Comunicación" alt="Comunicación" /></span><b style="color:#FFB200"><?php echo constant("STR_COMUNICACION");?></b></li>
						</ul>
					</td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td width="100%" colspan="2" >
						<table cellspacing="0" cellpadding="0" width="100%" border="0">
							<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr>
								<td width="50%" style="border-right: 1px solid #000000;" valign="top">
									<table width="100%" style="padding: 5px;" border="0">
										<tr>
											<td nowrap="nowrap" width="50" class="negrob" valign="top"><?php echo constant("STR_TIPO_CORREO");?>&nbsp;</td>
											<td width="175"><?php $comboTIPOS_CORREOS->setNombre("fIdTipoCorreo");?><?php echo $comboTIPOS_CORREOS->getHTMLCombo("1","obliga",""," onchange=\"javascript:cambiacorreos('combocorreos');\"","");?></td>
										</tr>
										<tr>
											<td nowrap="nowrap" width="50" class="negrob" valign="top"><?php echo constant("STR_NOMBRE");?>&nbsp;</td>
											<td width="175">
												<div id="combocorreos" style="width:175"></div>
											</td>
										</tr>
										<tr><td colspan="2" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
<!--   									<tr><td colspan="2"><input type="button" class="botones" name="fNuevo" value="Nuevo" onclick="javascript:nuevaplantilla();"/></td></tr>-->
										<tr><td colspan="2" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
									</table>
								</td>
								<td width="60%" valign="top">
									<div id="listacorreos" style="padding: 5px;"></div>
								</td>
							</tr>
							<tr><td colspan="2" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr><td colspan="2" bgcolor="#000000" style="height:1px;"></td></tr>
							<tr><td colspan="2" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr>
								<td colspan="2">
                  <div id="DatosCorreo">
                  </div>

									<div id="muestracorreo">
                    <table cellspacing="2" cellpadding="0" width="100%" border="0">
                    <!--  1::cambiado por nacho para que no se vea ni se pueda cambiar el nombre de la plantilla seleccionada -->
                    	<tr style="display:none">
                    		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
                    		<td nowrap="nowrap" class="negrob" valign="top"><?php echo constant("STR_NOMBRE");?>&nbsp;</td>
                    		<td><input type="text" id="fNombreNew" name="fNombreNew" value="" class="obliga"  onchange="javascript:trim(this);" /></td>
                    	</tr>
                    	<tr>
                    		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
                    		<td nowrap="nowrap" class="negrob" valign="top"><?php echo constant("STR_ASUNTO");?>&nbsp;</td>
                    		<td><input type="text" id="fAsuntoNew" name="fAsuntoNew" value="" class="obliga"  onchange="javascript:trim(this);" /></td>
                    	</tr>
                    	<tr>
                    		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
                    		<td nowrap="nowrap" class="negrob" valign="top"><?php echo constant("STR_CUERPO");?>&nbsp;</td>
                    		<td><textarea cols="1" id="fCuerpoNew" data-id="fCuerpoNew" name="fCuerpoNew" rows="6" class="obliga tinymce"  onchange="javascript:trim(this);"></textarea></td>
                    	</tr>

                    <input type="hidden" id="fDescripcionNew" name="fDescripcionNew" value="" class="cajatexto"  onchange="javascript:trim(this);" />
                    	<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
                    	<tr>
                    		<td colspan="3" ><a href="#_" onclick="document.getElementById('litCorr').style.display='block';" ><img alt="<?php echo constant("STR_AYUDA");?>" title="<?php echo constant("STR_AYUDA");?>" src="<?php echo constant('DIR_WS_GRAF');?>help.gif" ></a>
                    			<div id="litCorr" style="display:none" >
                    				<table cellspacing="2" cellpadding="0" width="100%" border="0">
                    					<?php include_once('include/literales_correos.php');?>
                    				</table>
                    			</div>
                    		</td>
                    	</tr>

                    <!-- Modificado por nacho para que solo se pueda seleccionar Añadir al proceso -->

                    	<tr style="display:none">
                    		<td width="100%" colspan="3">
                    			<table width="100%" border="0">
                    				<tr>
                    <!--				<td width="50"><input type="button" class="botones" id="bid-cancel" value="<?php //echo constant('STR_CANCELAR')?>" onclick="javascript:cancela();"/></td>-->
                    						<td width="275">
                    						<select name="fAccion" class="cajatexto">
                    	  					<option value="" selected><?php echo constant('SLC_OPCION')?></option>
                    							<option value="1">Añadir al proceso</option>
                    <!--						<option value="2">Guardar como Plantilla</option>-->
                    <!--						<option value="3">Guardar como Plantilla y añadir al proceso</option>-->
                    						</select>
                    					</td>
                    					<td><input type="button" class="botones" id="bid-ok" value="<?php echo constant('STR_ACEPTAR')?>" onclick="javascript:guardaplantilla();"/></td>

                    				</tr>
                    			</table>
                    		</td>
                    	</tr>

                    	<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
                    	<tr><td colspan="3" bgcolor="#000000" style="height:1px;"></td></tr>
                    	<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
                    </table>
                  </div>

								</td>
							</tr>
							<tr>
								<td>
									<table cellspacing="0" cellpadding="0" border="0">
										<tr>
											<td>
												<ul>
													<?php
													require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
													require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
													require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
													require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
													$cEmpresas = new Empresas();
													$cEmpresasDB = new EmpresasDB($conn);
													$cCiegosDB = new CandidatosDB($conn);

													$_idEmpresa=(!empty($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : $_POST["fIdEmpresaAsig"];
													$_idProceso=(!empty($_POST["fIdProceso"])) ? $_POST["fIdProceso"] : $_POST["fIdProcesoAsig"];
													if (!empty($_idEmpresa) && !empty($_idProceso))
													{
														$cCiegos = new Candidatos();
														$cCiegos->setIdEmpresa($_idEmpresa);
														$cCiegos->setIdProceso($_idProceso);
														$sqlCCiegos = $cCiegosDB->readLista($cCiegos);
														$rsCCiegos = $conn->Execute($sqlCCiegos);
														$sCCiega="";
														while (!$rsCCiegos->EOF)
														{
															if (empty($rsCCiegos->fields['mail'])){
																$sCCiega="Sí";
															}
															$rsCCiegos->MoveNext();
														}
														$cEmpresas->setIdEmpresa($_idEmpresa);
														$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
														$sCiega = $cEmpresas->getAltaCiega();
                            $sPrecargada = $cEmpresas->getAltaPrecargada();
														$iCiega = $cEmpresas->getDongles();

														$sDescuentaMatriz = $cEmpresas->getDescuentaMatriz();
														$cMatrizDng = new Empresas();
														if (!empty($sDescuentaMatriz)){
															$cMatrizDng->setIdEmpresa($sDescuentaMatriz);
															$cMatrizDng = $cEmpresasDB->readEntidad($cMatrizDng);
															$iCiega = $cMatrizDng->getDongles();
														}else{
															$iCiega = $cEmpresas->getDongles();
														}
														if (!empty($sCiega) && !empty($sCCiega) && !empty($iCiega)){
													?>
														<li style="list-style:lower-roman;"><strong class="naranja"><?php echo constant("STR_ALTAS_ANONIMAS");?></strong>:&nbsp;<?php echo constant("HTTP_SERVER_FRONT") . "Candidato/blind.php?h=" . str_replace("=","", base64_encode($_idEmpresa . constant("CHAR_SEPARA") .  $_idProceso));?></li>
													<?php
														}
                            if (!empty($sPrecargada) && !empty($iCiega)){
                          ?>
                            <li style="list-style:lower-roman;"><strong class="naranja"><?php echo "Altas precargadas";?></strong>:&nbsp;<?php echo constant("HTTP_SERVER_FRONT") . "Candidato/verify.php?h=" . str_replace("=","", base64_encode($_idEmpresa . constant("CHAR_SEPARA") .  $_idProceso));?></li>
                          <?php
                            }
													}
													?>
													</ul>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td colspan="2" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
							<tr><td colspan="2" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>

					</table>
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="enviarNuevo(<?php echo constant('MNT_ANIADECANDIDATOS')?>);" /></td>
<!--							<td> <input type="button" class="botonesgrandes" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> name="fBtnEnviarCorreos" value="<?php echo constant("STR_ENVIAR_CORREOS");?>" onclick="javascript:guardaplantilla();"/></td>-->
							<td> <input type="button" class="botonesgrandes" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> name="fBtnEnviarCorreos" value="<?php echo constant("STR_ENVIAR_CORREOS");?>" onclick="javascript:validaEnvioCorreo();"/></td>
<!--							<td> <input type="button" class="botonesgrandes" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> name="fBtnOk" value="<?php echo constant("STR_ENVIAR_MAS_TARDE");?>" onclick="javascript:guardaplantilla();javascript:enviarNuevo(<?php echo constant('MNT_LISTAR')?>);"/></td>-->
							<td> <input type="button" class="botonesgrandes" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> name="fBtnOk" value="<?php echo constant("STR_ENVIAR_MAS_TARDE");?>" onclick="javascript:enviarNuevo(<?php echo constant('MNT_LISTAR')?>);"/></td>
							<td> <input type="button" class="botonesgrandes" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> name="fBtnProgramarEnvio" value="<?php echo constant("STR_PROGRAMAR_ENVIO");?>" onclick="javascript:programarEnvio();"/></td>
							<td>
								<div id="programarEnvio">
									<table >
										<tr>
											<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
											<td nowrap="nowrap" width="80" class="negrob" valign="top"><?php echo constant("STR_FECHA");?>&nbsp;</td>
											<?php
											$sHoraInicioProgramado= "23:59";
											if ($cEntidad->getFecEnvioProgramado() != "" && $cEntidad->getFecEnvioProgramado() != "0000-00-00" && $cEntidad->getFecEnvioProgramado() != "0000-00-00 00:00:00"){
												$aInicio=explode(" ", $cEntidad->getFecEnvioProgramado());
												$sHoraInicioProgramado= substr($aInicio[1], 0, 5);  // HH:MM
												$cEntidad->setFecEnvioProgramado($conn->UserDate($cEntidad->getFecEnvioProgramado(),constant("USR_FECHA"),false));
											}
											?>
											<td>
												<?php
												if($_POST['MODO'] == constant('MNT_MODIFICAR')){
													if($listaInformados->recordCount()>0){?>
														<img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO');?>" align="bottom" />&nbsp;<input type="text" readonly="readonly" name="fFecEnvioProgramado" value="<?php echo $cEntidad->getFecEnvioProgramado();?>" class="obliga" style="width:75px;" onchange="javascript:trim(this);" />&nbsp;<input type="text" readonly="readonly" name="fHoraInicioProgramado" value="<?php echo $sHoraInicioProgramado;?>" class="obliga" style="width:40px;" onchange="javascript:trim(this);" />
												<?php
													}else{?>
														<a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=fFecEnvioProgramado','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO');?>" align="bottom" /></a>&nbsp;<input type="text" name="fFecEnvioProgramado" value="<?php echo $cEntidad->getFecEnvioProgramado();?>" class="obliga" style="width:75px;" onchange="javascript:trim(this);" />&nbsp;<input type="text" name="fHoraInicioProgramado" value="<?php echo $sHoraInicioProgramado;?>" class="obliga" style="width:40px;" onchange="javascript:trim(this);" />
												<?php
													}
												}else{?>
													<a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=fFecEnvioProgramado','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO');?>" align="bottom" /></a>&nbsp;<input type="text" name="fFecEnvioProgramado" value="<?php echo $cEntidad->getFecEnvioProgramado();?>" class="obliga" style="width:75px;" onchange="javascript:trim(this);" />&nbsp;<input type="text" name="fHoraInicioProgramado" value="<?php echo $sHoraInicioProgramado;?>" class="obliga" style="width:40px;" onchange="javascript:trim(this);" />

												<?php
												}?>
											</td>
											<td>
												<div id="timezone" ><?php echo ($cEmpresas->getTimezone() != "") ? '<span style="color:green;margin-left: 10px;">' . constant("STR_ZONA_HORARIA") . ': ' . $cEmpresas->getTimezone() . '</span>' :  '<span style="color:red;">' . constant("STR_ZONA_HORARIA") . ': ' . constant("STR_NO_DEFINIDA") . '</span>'; ?></div>
											</td>
											<td >
												<input type="button" <?php echo ($_bModificar) ? "" : "disabled";?>  class="botones" id="bid-ok" name="fBtnOkGuardaProgramarEnvio" value="<?php echo constant("STR_GUARDAR");?>" onclick="guardaProgramarEnvio(<?php echo constant('MNT_PROGRAMAR_ENVIO')?>);" />
												<div id="programarEnvioOK"></div>
											</td>
										</tr>
										<tr>
											<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
											<td colspan="4"><?php echo constant("MSG_ENVIO_PROGRAMADO_EJECUCION");?></td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
</div>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />

	<input type="hidden" name="fBorra" value="" />
	<input type="hidden" name="fConsulta" value="" />
	<input type="hidden" name="fNuevo" value="" />
	<input type="hidden" name="fConsultaAsignados" value="" />
	<input type="hidden" name="fIdCorreoAsig" value="" />
	<input type="hidden" name="fIdEmpresaAsig" value="" />
	<input type="hidden" name="fIdProcesoAsig" value="" />
	<input type="hidden" name="fIdTipoCorreoAsig" value="" />
	<input type="hidden" name="vaPruebas" value="" />
	<input type="hidden" name="fIdEmpresa" value="<?php echo (isset($_POST['fIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['fIdEmpresa']) : "";?>" />
	<input type="hidden" name="fIdProceso" value="<?php echo (isset($_POST['fIdProceso'])) ? $cUtilidades->validaXSS($_POST['fIdProceso']) : "";?>" />
	<input type="hidden" name="fCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" />
	<input type="hidden" name="LSTIdTipoCorreoHast" value="<?php echo (isset($_POST['LSTIdTipoCorreoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoCorreoHast']) : "";?>" />
	<input type="hidden" name="LSTIdTipoCorreo" value="<?php echo (isset($_POST['LSTIdTipoCorreo'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoCorreo']) : "";?>" />
	<input type="hidden" name="LSTIdCorreoHast" value="<?php echo (isset($_POST['LSTIdCorreoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdCorreoHast']) : "";?>" />
	<input type="hidden" name="LSTIdCorreo" value="<?php echo (isset($_POST['LSTIdCorreo'])) ? $cUtilidades->validaXSS($_POST['LSTIdCorreo']) : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $cUtilidades->validaXSS($_POST['LSTNombre']) : "";?>" />
	<input type="hidden" name="LSTDescripcion" value="<?php echo (isset($_POST['LSTDescripcion'])) ? $cUtilidades->validaXSS($_POST['LSTDescripcion']) : "";?>" />
	<input type="hidden" name="LSTCuerpo" value="<?php echo (isset($_POST['LSTCuerpo'])) ? $cUtilidades->validaXSS($_POST['LSTCuerpo']) : "";?>" />
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
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?></div>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function(){
      tinymce.init({
        selector: '.tinymce',
        plugins: [
          'advlist autolink lists link image charmap print preview anchor',
          'searchreplace visualblocks code fullscreen',
          'insertdatetime media table paste imagetools wordcount'
        ],
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
      });

  });
</script>
</form>

</body></html>
