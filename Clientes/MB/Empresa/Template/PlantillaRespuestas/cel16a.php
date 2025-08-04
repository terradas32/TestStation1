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
		
<title><?php echo constant("NOMBRE_SITE");?></title>
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<link rel="stylesheet" type="text/css" href="estilos/estilos16.css"/>
	<link rel="stylesheet" type="text/css" href="estilos/prettyCheckboxes.css"/>
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
	<script src="https://cdn.tiny.cloud/1/19u4q91vla6r5niw2cs7kaymfs18v3j11oizctq52xltyrf4/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script><script>tinymce.init({ selector:'.tinymce' });</script>
	<script type="text/javascript" src="codigo/prettyCheckboxes.js"></script>
	<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$("input[type=radio]").prettyCheckboxes();
//		$('span.holder').click(function(){
//			var numero = $(this).attr('value');
//			chkIgual(numero,$(this));
//		})
	});
	</script>


<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php");?>
function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
				
		var	hora = "" + f.fHoraInicioH.value + ":" + f.fHoraInicioM.value;
		if(hora.length!=5){
			alert("Formato de Hora INICIO incorrecto, el formato debe ser: HH:MM");
			return false;
		}
		
		a=hora.charAt(0);
		b=hora.charAt(1);
		c=hora.charAt(2);
		d=hora.charAt(3);
		if (a>=2 && b>3) {
			alert("hora INICIO incorrecta");
			return false;
		}
		if(c!= ":"){
			alert("El formato hora INICIO debe ser HH:MM");
			return false;	
		}
		if (d>5) {
			alert("minutos hora INICIO incorrectos");
			return false;
		}

		var	horaF = "" + f.fHoraFinH.value + ":" + f.fHoraFinM.value;
		if(horaF.length!=5){
			alert("Formato de Hora FIN incorrecto, el formato debe ser: HH:MM");
			return false;
		}

		aF=horaF.charAt(0);
		bF=horaF.charAt(1);
		cF=horaF.charAt(2);
		dF=horaF.charAt(3);
		if (aF>=2 && bF>3) {
			alert("hora FIN incorrecta");
			return false;
		}
		if(cF!= ":"){
			alert("El formato hora FIN debe ser HH:MM");
			return false;	
		}
		if (dF>5) {
			alert("minutos hora FIN incorrectos");
			return false;
		}
//		if (!CheckTime(hora)){
//			return false;
//		}
//		if (!CheckTime(horaF)){
//			return false;
//		}
			
		lon();
		f.fFechaInicio.value=cFechaFormat(f.fFechaInicio.value);
		f.fFechaFin.value = f.fFechaInicio.value;
		f.fHoraInicio.value = hora;
		f.fHoraFin.value = horaF;
		return true;
	}else	return false;
}
function chkRadioMP()
{
	var f=document.forms[0];
	var sRetorno="";
	for(k=1; k < 33; k++ ){
		aRespM = eval("document.forms[0].fIdOpcionMejor" + k);
		aRespP = eval("document.forms[0].fIdOpcionPeor" + k);
		sId = "";
		for(i=0; i < aRespM.length; i++ ){
			if (aRespM[i].type == "radio" && aRespM[i].name == "fIdOpcionMejor" + k){
				if (aRespM[i].checked)
				{
					if (aRespP[i].checked){
						//aRespP[i].checked = false;
						sId = "";
						break;
					}else{
						sId = aRespM[i].value;
					}
				}
			}
		}
		
		if (sId != ""){
			sId = "";
			for(i=0; i < aRespP.length; i++ ){
				if (aRespP[i].type == "radio" && aRespP[i].name == "fIdOpcionPeor" + k){
					if (aRespP[i].checked)
					{
						if (aRespM[i].checked){
							//aRespM[i].checked = false;
							sId = "";
							break;
						}else{
							sId = aRespP[i].value;
						}
					}
				}
			}
			if (sId == ""){
				if (sRetorno==""){
					sRetorno="\nHan sido señaladas en rojo las opciones de respuesta con error.\n\tRevise las respuestas desde la 1 a la 32.\n\tRECUERDE:\n\tLa misma opción NO puede ser Mejor y Peor.";
				}
				document.getElementById("numReg" + k).style.color="red";
			}else{
				document.getElementById("numReg" + k).style.color="";
			}
		}else{
			if (sRetorno==""){
				sRetorno="\nHan sido señaladas en rojo las opciones de respuesta con error.\n\tRevise las respuestas desde la 1 a la 32.\n\tRECUERDE:\n\t\tLa misma opción NO puede ser Mejor y Peor.";
			}
			 document.getElementById("numReg" + k).style.color="red";
		}
	}
	return sRetorno;
}

function chkRadioM()
{
	var f=document.forms[0];
	var sRetorno="";
	for(k=33; k < 53; k++ ){
		aRespM = eval("document.forms[0].fIdOpcionMejor" + k);
		sId = "";
		for(i=0; i < aRespM.length; i++ ){
			if (aRespM[i].type == "radio" && aRespM[i].name == "fIdOpcionMejor" + k){
				if (aRespM[i].checked)
				{
					sId = aRespM[i].value;
				}
			}
		}
		
		if (sId == ""){
			if (sRetorno==""){
				sRetorno="\nHan sido señaladas en rojo las opciones de respuesta con error.\n\tRevise las respuestas desde la 33 a la 52.";
			}
			document.getElementById("numReg" + k).style.color="red";
		}else{
			document.getElementById("numReg" + k).style.color="";
		}
	}
	return sRetorno;
}

function chkIgual(l, oObj)
{
	var f=document.forms[0];
	aRespM = eval("document.forms[0].fIdOpcionMejor" + l);
	aRespP = eval("document.forms[0].fIdOpcionPeor" + l);
	if(oObj.name == "fIdOpcionMejor" + l){
		for(i=0; i < aRespM.length; i++ ){
			if (aRespM[i].type == "radio" && aRespM[i].name == "fIdOpcionMejor" + l){
				if (aRespM[i].checked){
//					aRespP[i].checked = false;
					
				}
			}
		}
	}
	if(oObj.name == "fIdOpcionPeor" + l){
		for(i=0; i < aRespP.length; i++ ){
			if (aRespP[i].type == "radio" && aRespP[i].name == "fIdOpcionPeor" + l){
				if (aRespP[i].checked){
//					aRespM[i].checked = false;
				}
			}
		}
	}
	//oObj.checked = true;
}

function enviarNuevo(modo)
{
	var f=document.forms[0];
	f.MODO.value =modo;
	f.submit();
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";

	
	msg +=vNumber("<?php echo constant("STR_EMPRESA");?>:",f.fIdEmpresa.value,11,true);
	msg +=vString("<?php echo constant("STR_PROCESO");?>:",f.fNombreProceso.value,255,true);
	msg +=vDate("<?php echo constant("STR_FECHA_DE_INICIO");?>:",f.fFechaInicio.value,10,true);

	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombre.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO_1");?>:",f.fApellido1.value,255,true);
	msg +=vString("<?php echo constant("STR_APELLIDO_2");?>:",f.fApellido2.value,255,false);
	msg +=vNumber("<?php echo constant("STR_HORA_DE_INICIO");?> - hh:",f.fHoraInicioH.value,2,true);
	msg +=vNumber("<?php echo constant("STR_HORA_DE_INICIO");?> - mi:",f.fHoraInicioM.value,2,true);
	msg +=vNumber("<?php echo constant("STR_HORA_DE_FIN");?> - hh:",f.fHoraFinH.value,2,true);
	msg +=vNumber("<?php echo constant("STR_HORA_DE_FIN");?> - mi:",f.fHoraFinM.value,2,true);

	msg +=vNumber("<?php echo constant("STR_EDAD");?>:",f.fIdEdad.value,11,true);
	msg +=vNumber("<?php echo constant("STR_SEXO");?>:",f.fIdSexo.value,11,true);
	msg +=vNumber("<?php echo constant("STR_FORMACION_ACADEMICA");?>:",f.fIdFormacion.value,11,true);
	msg +=vNumber("<?php echo constant("STR_AREA_PROFESIONAL");?>:",f.fIdArea.value,11,true);
	
	if (msg == "") {
			
		<?php 
			$sFecha = date("d/m/Y");
			$sHoraJS = date("G");
			$sMin = date("i");
		?>
		var	HI = "" + f.fHoraInicioH.value + ":" + f.fHoraInicioM.value;
		var aI=HI.charAt(0);
		var bI=HI.charAt(1);
		var cI=HI.charAt(2);
		var dI=HI.charAt(3);
		var eI=HI.charAt(4);
		var horaI = aI + bI;
		var minI = dI + eI;

		var	HF = "" + f.fHoraFinH.value + ":" + f.fHoraFinM.value;
		var aF=HF.charAt(0);
		var bF=HF.charAt(1);
		var cF=HF.charAt(2);
		var dF=HF.charAt(3);
		var eF=HF.charAt(4);
		var horaF = aF + bF;
		var minF = dF + eF;
		
		var dFI = toJSDate(f.fFechaInicio.value,"dd/mm/yyyy", horaI, minI);
		var dFF = toJSDate(f.fFechaInicio.value,"dd/mm/yyyy", horaF, minF);
		var fDesde   = dFI;
		var fHasta   = dFF;
		var fSistema = toJSDate("<?php echo $sFecha?>","dd/mm/yyyy", <?php echo $sHoraJS?>, <?php echo $sMin?>);


		if (fDesde > fHasta)
			msg +="No puede ser mayor que la fecha Fin\n";
		if (msg != "")
			msg ="Fec. Inicio:\n" + msg;
	}
	if (msg == ""){
		if (dFI > dFF){
			msg ="La fecha hora Inicio no puede ser mayor que la de Fin\n";
		}
	}
	msg += chkRadioMP();
	msg += chkRadioM();
if (msg != "") {
	alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
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
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0"  title="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" alt="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
	
		<form name="form" id="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>" onsubmit="return enviar('<?php echo $_POST["MODO"];?>');">
<?php 
if ($_POST['MODO'] == constant("MNT_ALTA"))	$HELP="xx";
else	$HELP="xx";
?>
<div id="contenedor">
<?php include (constant("DIR_WS_INCLUDE") . "cabecera.php");?>
	<div id="envoltura">
		<div id="contenido">
		<div style="width: 100%">
	<div id="pagina12">	
    	<fieldset>
        	<p class="par12">
        	<label for="empresa12">Empresa</label>
			<?php 
				$sReadOnly="";
				if ($cEntidad->getIdProceso() != ""){
					$sReadOnly = " onchange='this.value=" . $cEntidad->getIdEmpresa() . "'";
				}
			?>
        	<?php $comboEMPRESAS->setNombre("fIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLComboMenu("1","obliga",$cEntidad->getIdEmpresa(), $sReadOnly,"");
        		$sReadOnly="";
				if ($cEntidad->getIdProceso() != ""){
					$sReadOnly = ' readonly="readonly"';
				}
        	?>
        	
            </p>
            <p class="par12" >
            	<label for="proceso12"><?php echo constant("STR_PROCESO");?></label>
            	<input type="text" <?php echo $sReadOnly;?> name="fNombreProceso" id="proceso12" value="<?php echo $cEntidad->getNombre();?>" class="obliga" />
            </p>
            <p class="par12" style="margin-left: 20%;">
            <?php 
			$sHoraInicio= "";
			$sHoraInicioH= "";
			$sHoraInicioM= "";
			if ($cEntidad->getFechaInicio() != "" && $cEntidad->getFechaInicio() != "0000-00-00" && $cEntidad->getFechaInicio() != "0000-00-00 00:00:00"){
				$aInicio=explode(" ", $cEntidad->getFechaInicio());
				$sHoraInicio= substr($aInicio[1], 0, 5);  // HH:MM
				$aInicioHM=explode(":", $sHoraInicio);
				$sHoraInicioH= $aInicioHM[0];
				$sHoraInicioM= $aInicioHM[1];
													
				$cEntidad->setFechaInicio($conn->UserDate($cEntidad->getFechaInicio(),constant("USR_FECHA"),false));
			}else{
				
				$date = date('Y-m-d H:i:s');
				$cEntidad->setFechaInicio($date);
				$aInicio=explode(" ", $cEntidad->getFechaInicio());
				$sHoraInicio= substr($aInicio[1], 0, 5);  // HH:MM
				$aInicioHM=explode(":", $sHoraInicio);
//				$sHoraInicioH= $aInicioHM[0];
//				$sHoraInicioM= $aInicioHM[1];
				$cEntidad->setFechaInicio($conn->UserDate($cEntidad->getFechaInicio(),constant("USR_FECHA"),false));
			}
			?>
            
                <label for="fecha"><?php echo constant("STR_FECHA");?> : DD/MM/YYYY</label>
                <a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=fFechaInicio','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO');?>" align="bottom" /></a>&nbsp;<input type="text" name="fFechaInicio" value="<?php echo $cEntidad->getFechaInicio();?>" class="obliga" style="width:75px;float:left;margin-right: 5px;" onchange="javascript:trim(this);" />
            </p>
        </fieldset>
	<div id="recargaProceso12">
    	<fieldset id="date">
            <p class="par12">
                <label for="name"><?php echo constant("STR_NOMBRE");?></label>
                <input type="text" name="fNombre" id="name" value="" class="obliga" /> 
            </p>
            <p class="par12">
                <label for="fApellido1"><?php echo constant("STR_APELLIDO_1");?></label>
                <input type="text" name="fApellido1" id="fApellido1" value="" class="obliga" /> 
            </p>
            <p class="par12">
                <label for="fApellido2"><?php echo constant("STR_APELLIDO_2");?></label>
                <input type="text" name="fApellido2" id="fApellido2" value="" /> 
            </p>
            <p class="par12 horas">
                <label for="horaInicio"><?php echo constant("STR_HORA_DE_INICIO");?></label>
                <input type="text" name="fHoraInicioH" id="horaInicio" value="<?php echo $sHoraInicioH;?>" class="obliga" style="width:20px;" onchange="javascript:trim(this);" />
                <span class="time">:</span> 
                <input type="text" name="fHoraInicioM" id="minutosInicio" value="<?php echo $sHoraInicioM;?>" class="obliga" style="width:20px;" onchange="javascript:trim(this);" />
            </p>
            <p class="par12 horas">
			<?php 
			$sHoraFin="";
			$sHoraFinH= "";
			$sHoraFinM= "";
			if ($cEntidad->getFechaFin() != "" && $cEntidad->getFechaFin() != "0000-00-00" && $cEntidad->getFechaFin() != "0000-00-00 00:00:00"){
				$aFin=explode(" ", $cEntidad->getFechaFin());
				$sHoraFin= substr($aFin[1], 0, 5);  // HH:MM
				$aFinHM=explode(":", $sHoraFin);
				$sHoraFinH= $aFinHM[0];
				$sHoraFinM= $aFinHM[1];
				$cEntidad->setFechaFin($conn->UserDate($cEntidad->getFechaFin(),constant("USR_FECHA"),false));
			}else{
				//Palabras especiales (tomorrow, yesterday, ago, fortnight, now, today, day, week, month, year, hour, minute, min, second, sec)
				$date = date('Y-m-d 23:59:59', strtotime('+1 week'));
				$cEntidad->setFechaFin($date);
				$aFin=explode(" ", $cEntidad->getFechaFin());
				$sHoraFin= substr($aFin[1], 0, 5);  // HH:MM
				$aFinHM=explode(":", $sHoraFin);
//				$sHoraFinH= $aFinHM[0];
//				$sHoraFinM= $aFinHM[1];
				$cEntidad->setFechaFin($conn->UserDate($cEntidad->getFechaFin(),constant("USR_FECHA"),false));
			}
			?>

                <label for="horaFin"><?php echo constant("STR_HORA_DE_FIN");?></label>
                <input type="text" name="fHoraFinH" id="horaFin" value="<?php echo $sHoraFinH;?>" class="obliga" style="width:20px;" onchange="javascript:trim(this);" />
                <span class="time">:</span> 
                <input type="text" name="fHoraFinM" id="minutosFin" value="<?php echo $sHoraFinM;?>" class="obliga" style="width:20px;" onchange="javascript:trim(this);" />
            </p>
        </fieldset>
    	<fieldset id="datosPersonales">
            <p class="par12">
        	<label for="edad"><?php echo constant('STR_EDAD');?></label> 
			<?php $comboEDADES->setNombre("fIdEdad");?><?php echo $comboEDADES->getHTMLCombo("1","obliga", ""," ","");?>
            </p>
            <p class="par12">
        	<label for="sexo"><?php echo constant('STR_SEXO');?></label> 
			<?php $comboSEXOS->setNombre("fIdSexo");?><?php echo $comboSEXOS->getHTMLCombo("1","obliga",""," ","");?>
            </p>
            <p class="par12">
        	<label for="formacionAcad"><?php echo constant('STR_FORMACION_ACADEMICA');?></label> 
        	<?php $comboFORMACIONES->setNombre("fIdFormacion");?><?php echo $comboFORMACIONES->getHTMLCombo("1","obliga",""," ","");?>
            </p>
            <p class="par12">
        	<label for="areaPrefesional"><?php echo constant('STR_AREA_PROFESIONAL');?></label> 
        	<?php $comboAREAS->setNombre("fIdArea");?><?php echo $comboAREAS->getHTMLCombo("1","obliga",""," ","");?>
            </p>
        </fieldset>
		<div id="respuestas12">
 			<?php
 			$iCont=0;
 			$iContPart2=0;
 			$iContPart2Final=0;    
 			$sTResp = '';
 			for ($i=1; $i < 53; $i++)
 			{

 				if ($i == 1 || $i == 13 || $i == 25 || $i == 37 || $i == 49 ){
 					if ($i > 1){
 						$sTResp .= '</div><!-- Fin div columna -->';
 					}
 					$sTResp .= '<div class="columna">';
 				}
 				$iCont++;
 				if ($i < 33)
 				{
	 				$sTResp .= '
	                <div class="result">
	                    <span class="numPreg" id="numReg' . $i . '">' . $i . '</span>
	                    <span class="mp">
	                        <font>M</font>
	                        <font>P</font>	
	                    </span>
	                    <span class="radios">
	                        <span class="inputCheck">
	                            <label for="opcionM-' . $iCont . '" tabindex="' . $i . '"></label>
	                            <input type="radio" name="fIdOpcionMejor' . $i . '" id="opcionM-' . $iCont . '" value="M-' . $iCont . '-1" onclick="chkIgual(' . $i . ', this);" />
	                            <font>A</font>
	                            <label for="opcionP-' . $iCont . '" tabindex="' . ($i+1) . '"></label>
	                            <input type="radio" name="fIdOpcionPeor' . $i . '" id="opcionP-' . $iCont . '" value="P-' . $iCont . '-2" onclick="chkIgual(' . $i . ', this);" />
	                        </span>';
	 				$iCont++;
	 				$sTResp .= '	                        
	                        <span class="inputCheck">
	                            <label for="opcionM-' . $iCont . '" tabindex="' . ($i+2) . '"></label>
	                            <input type="radio" name="fIdOpcionMejor' . $i . '" id="opcionM-' . $iCont . '" value="M-' . $iCont . '-1" onclick="chkIgual(' . $i . ', this);" />
	                            <font>B</font>
	                            <label for="opcionP-' . $iCont . '" tabindex="' . ($i+3) . '"></label>
	                            <input type="radio" name="fIdOpcionPeor' . $i . '" id="opcionP-' . $iCont . '" value="P-' . $iCont . '-2" onclick="chkIgual(' . $i . ', this);" />
	                        </span>';
	 				$iCont++;
	 				$sTResp .= '
	                        <span class="inputCheck">
	                            <label for="opcionM-' . $iCont . '" tabindex="' . ($i+4) . '"></label>
	                            <input type="radio" name="fIdOpcionMejor' . $i . '" id="opcionM-' . $iCont . '"value="M-' . $iCont . '-1" onclick="chkIgual(' . $i . ', this);" />
	                            <font>C</font>
	                            <label for="opcionP-' . $iCont . '" tabindex="' . ($i+5) . '"></label>
	                            <input type="radio" name="fIdOpcionPeor' . $i . '" id="opcionP-' . $iCont . '" value="P-' . $iCont . '-2" onclick="chkIgual(' . $i . ', this);" />
	                        </span>
	                    </span>
	                </div><!-- Fin caja result-->
	 				';
 				}else{
 					$iContPart2++;
 					$iTempItem =($iCont+$iContPart2);
 					if ($iTempItem >= 151){
 						$iContPart2Final++;
 						$iTempItem = $iTempItem-$iContPart2Final;
 					} 
 					// de la 32 en adelante
	 				$sTResp .= '
	                <div class="result">
	                    <span class="numPreg" id="numReg' . $i . '">' . $i . '</span>
	                    <span class="mp">
	                        <font style="line-height:40px;">M</font>
	                    </span>
	                    <span class="radios">
	                    <br />
	                        <span class="inputCheck">
	                            <label for="opcionM-' . $iCont . '" tabindex="' . $i . '"></label>
	                            <input type="radio" name="fIdOpcionMejor' . $i . '" id="opcionM-' . $iCont . '" value="M-' . $iTempItem . '-1" />
	                            <font>A</font>
	                        </span>';
	 				$iCont++;
	 				$iTempItem =($iCont+$iContPart2);
 					if ($iTempItem >= 151){
 						$iTempItem = $iTempItem-$iContPart2Final;
 					} 
	 				$sTResp .= '	                        
	                        <span class="inputCheck">
	                            <label for="opcionM-' . $iCont . '" tabindex="' . ($i+2) . '"></label>
	                            <input type="radio" name="fIdOpcionMejor' . $i . '" id="opcionM-' . $iCont . '" value="M-' . $iTempItem . '-1" />
	                            <font>B</font>
	                        </span>';
	 				$sTResp .= '
	                    </span>
	                </div><!-- Fin caja result-->
	 				';
 				}
 			}
 			$sTResp .= '</div><!-- Fin div columna -->';
 			echo $sTResp;
 			?>
            
        </div><!-- Fin div respuestas12-->
    </div><!-- Fin div recargaProceso12-->
    </div>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td><input type="button" class="botones" id="bid-alta" name="btnAdd" value="<?php echo constant("STR_NUEVO");?>" onclick="document.forms[0].MODO.value=document.forms[0].ORIGEN.value;document.forms[0].submit();" /></td>
					<td><input type="submit" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ACEPTAR");?>" /></td>
				</tr>
			</table>

		</div>
	</div>
	<input type="hidden" name="ANT_FECHAINICIO" value="<?php echo $cEntidad->getFechaInicio();?>" />
	<input type="hidden" name="ANT_HORA" value="<?php echo $sHoraInicio;?>" />
	<input type="hidden" name="fFechaFin" value="<?php echo $cEntidad->getFechaFin();?>" />
	<input type="hidden" name="fHoraInicio" value="<?php echo $sHoraInicio?>" />
	<input type="hidden" name="fHoraFin" value="<?php echo $sHoraFin?>" />
	
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="fIdProceso" value="<?php echo (empty($_POST['fIdProceso'])) ? $cEntidad->getIdProceso() : $_POST['fIdProceso'];?>" />
	
	<input type="hidden" name="LSTIdProcesoHast" value="<?php echo (isset($_POST['LSTIdProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdProcesoHast']) : "";?>" />
	<input type="hidden" name="LSTIdProceso" value="<?php echo (isset($_POST['LSTIdProceso'])) ? $cUtilidades->validaXSS($_POST['LSTIdProceso']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresaHast" value="<?php echo (isset($_POST['LSTIdEmpresaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaHast']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresa" value="<?php echo (isset($_POST['LSTIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresa']) : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $cUtilidades->validaXSS($_POST['LSTNombre']) : "";?>" />
	<input type="hidden" name="LSTDescripcion" value="<?php echo (isset($_POST['LSTDescripcion'])) ? $cUtilidades->validaXSS($_POST['LSTDescripcion']) : "";?>" />
	<input type="hidden" name="LSTObservaciones" value="<?php echo (isset($_POST['LSTObservaciones'])) ? $cUtilidades->validaXSS($_POST['LSTObservaciones']) : "";?>" />
	<input type="hidden" name="LSTFechaInicioHast" value="<?php echo (isset($_POST['LSTFechaInicioHast'])) ? $cUtilidades->validaXSS($_POST['LSTFechaInicioHast']) : "";?>" />
	<input type="hidden" name="LSTFechaInicio" value="<?php echo (isset($_POST['LSTFechaInicio'])) ? $cUtilidades->validaXSS($_POST['LSTFechaInicio']) : "";?>" />
	<input type="hidden" name="LSTFechaFinHast" value="<?php echo (isset($_POST['LSTFechaFinHast'])) ? $cUtilidades->validaXSS($_POST['LSTFechaFinHast']) : "";?>" />
	<input type="hidden" name="LSTFechaFin" value="<?php echo (isset($_POST['LSTFechaFin'])) ? $cUtilidades->validaXSS($_POST['LSTFechaFin']) : "";?>" />
	<input type="hidden" name="LSTIdTipoProcesoHast" value="<?php echo (isset($_POST['LSTIdTipoProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoProcesoHast']) : "";?>" />
	<input type="hidden" name="LSTIdTipoProceso" value="<?php echo (isset($_POST['LSTIdTipoProceso'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoProceso']) : "";?>" />
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
	<input type="hidden" name="procesos_next_page" value="<?php echo (isset($_POST['procesos_next_page'])) ? $cUtilidades->validaXSS($_POST['procesos_next_page']) : "1";?>" /></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?></div>
</form>

</body></html>

