<?php
//Añadimos este if para preguntar por la conexxión ya que al ser datosprofesionales.php un include
// no lo tiene definida y así al dar volver no fallará y al hacer el include desde el login funcionará correctamente también
//ya que ya la tendrá definida y no eejecutará el código.
//if (!isset($conn)){
//	header('Content-Type: text/html; charset=utf-8');
//	header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
//	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
//	header('Cache-Control: post-check=0, pre-check=0', false);
//	header('Pragma: no-cache');


	ob_start();
	require_once('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	//include_once(constant("DIR_WS_INCLUDE") . 'SeguridadCandidatos.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	if (!defined('ADODB_ASSOC_CASE')) {
		define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	}
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");

	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");

	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");

include_once ('include/conexion.php');
//}
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }

    $cCandidato = new Candidatos();
	$cCandidatosDB = new CandidatosDB($conn);

	$cCandidato  = $_cEntidadCandidatoTK;

	if (empty($_POST['fIdEmpresa'])){
        $_POST['fIdEmpresa'] = $cCandidato->getIdEmpresa();
    }

    $cEmpresas = new Empresas();
    $cEmpresasDB = new EmpresasDB($conn);
    $cEmpresas->setIdEmpresa($cCandidato->getIdEmpresa());
    $cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);

    $cProceso_pruebas = new Proceso_pruebas();
    $cProceso_pruebasDB = new Proceso_pruebasDB($conn);
    $cProceso_pruebas->setIdEmpresa($cCandidato->getIdEmpresa());
    $cProceso_pruebas->setIdProceso($cCandidato->getIdProceso());
    $cProceso_pruebas->setIdPrueba("121");	//SEAS
    $sqlProcPruebas = $cProceso_pruebasDB->readLista($cProceso_pruebas);
    $listaProcesosPruebas = $conn->Execute($sqlProcPruebas);
    $bSeas = false;
    if ($listaProcesosPruebas->recordCount() > 0)
    {
    	//$bSeas = true;
    	$bSeas = false;	//Finalmente no queremos que salga autoevaluación
    }
    $comboAREAS			= new Combo($conn,"fIdArea","idArea","nombre","Descripcion","areas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboEDADES		= new Combo($conn,"fIdEdad","idEdad","nombre","Descripcion","edades","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","idEdad","");
    $comboNIVELES		= new Combo($conn,"fIdNivel","idNivel","nombre","Descripcion","nivelesjerarquicos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboFORMACIONES 	= new Combo($conn,"fIdFormacion","idFormacion","nombre","Descripcion","formaciones","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboSEXOS			= new Combo($conn,"fIdSexo","idSexo","nombre","Descripcion","sexos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboSECTORESMB	= new Combo($conn,"fSectorMB","idSectorMB","descripcion","Descripcion","sectoresmb","","","","","","");
    $comboESPECIALIDADESMB	= new Combo($conn,"fEspecialidadMB","idEspecialidadMB","descripcion","Descripcion","especialidadesmb","","","","","","");
    $CATEGORIASPROFESIONALES	= new Combo($conn,"fCategoriaForjanor","idCategoria","descripcion","Descripcion","categoriasProfesionales","","","","","","");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
    <script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
    <script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/eventos.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jquery.tools.min.js"></script>
<script language="javascript" type="text/javascript">
//<![CDATA[
           <?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php");?>
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg2 =vString("<?php echo "Acepta Mazda";?>:",f.fAceptaMazda.value,2,true);
	if (msg2 != ""){
		msg +="En la ventana de condiciones legales,debe marcar Sí o No acepta\ncomunicar sus datos de carácter personal y los resultados de su\nevaluación a Mazda Automóviles España, S.A.\n";
	}
	<?php
	if ($cEmpresas->getNombreCan() == 'on'){echo "\n" . 'msg +=vString("' . constant("STR_NOMBRE") . ':",f.fNombre.value,255,true);';}
	if ($cEmpresas->getApellido1() == 'on'){echo "\n" . 'msg +=vString("' . constant("STR_APELLIDO1") . ':",f.fApellido1.value,255,true);';}
	if ($cEmpresas->getApellido2() == 'on'){echo "\n" . 'msg +=vString("' . constant("STR_APELLIDO2") . ':",f.fApellido2.value,255,true);';}
	if ($cEmpresas->getMailCan() == 'on'){echo "\n" . 'msg +=vString("' . constant("STR_MAIL") . ':",f.fMailCan.value,255,true);';}
	if ($cEmpresas->getNifCan() == 'on'){echo "\n" . 'msg +=vString("' . constant("STR_DNI") . ':",f.fNifCan.value,255,true);';}
	if ($cEmpresas->getEdad() == 'on'){echo "\n" . 'msg +=vNumber("' . constant("STR_EDAD") . ':",f.fIdEdad.value,11,true);';}
	if ($cEmpresas->getSexo() == 'on'){echo "\n" . 'msg +=vNumber("' . constant("STR_SEXO") . ':",f.fIdSexo.value,11,true);';}
	if ($cEmpresas->getNivel() == 'on'){echo "\n" . 'msg +=vNumber("' . constant("STR_NIVEL_ACTUAL") . ':",f.fIdNivel.value,11,true);';}
	if ($cEmpresas->getArea() == 'on'){echo "\n" . 'msg +=vNumber("' . constant("STR_AREA") . ':",f.fIdArea.value,11,true);';}
	if ($cEmpresas->getFormacion() == 'on'){echo "\n" . 'msg +=vNumber("' . constant("STR_FORMACION_ACADEMICA") . ':",f.fIdFormacion.value,11,true);';}
	if ($cEmpresas->getTelefono() == 'on'){echo "\n" . 'msg +=vString("' . constant("STR_TELEFONO") . ':",f.fTelefono.value,20,true);';}
	if ($cEmpresas->getSectorMB() == 'on'){echo "\n" . 'msg +=vString("' . constant("STR_SECTOR") . ':",f.fSectorMB.value,255,true);';}
	if ($cEmpresas->getConcesionMB() == 'on'){echo "\n" . 'msg +=vString("' . constant("STR_CONCESION") . ':",f.fConcesionMB.value,2500,true);';}
	if ($cEmpresas->getBaseMB() == 'on'){echo "\n" . 'msg +=vString("Base:",f.fBaseMB.value,2500,true);';}

	if ($cEmpresas->getFecNacimientoMB() == 'on'){echo "\n" . 'msg +=vDate("' . constant("STR_FECHA_NACIMIENTO") . ':",f.fFechaNacimiento.value,10,true);';}
	if ($cEmpresas->getEspecialidadMB() == 'on'){echo "\n" . 'msg +=vString("' . constant("STR_ESPECIALIDAD") . ':",f.fEspecialidadMB.value,20,true);';}
	if ($bSeas){
		if ($cEmpresas->getNivelConocimientoMB() == 'on'){echo "\n" . 'msg +=vNumber("' . constant("STR_NIVEL_CONOCIMIENTO_TECNICO") . ':",f.fNivelConocimientoMB.value,2,true);';}
	}
	if ($cEmpresas->getPuestoEvaluar() == 'on'){echo "\n" . 'msg +=vString("' . "Puesto a evaluar" . ':",f.fPuestoEvaluar.value,255,true);';}
	if ($cEmpresas->getCategoriaForjanor() == 'on'){echo "\n" . 'msg +=vString("' . "Categoría profesional" . ':",f.fCategoriaForjanor.value,255,true);';}
	if ($cEmpresas->getResponsableDirecto() == 'on'){echo "\n" . 'msg +=vString("' . "Responsable directo" . ':",f.fResponsableDirecto.value,255,true);';}

	?>

if (msg != "") {
	alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
	return false;
}else return true;
}
function getmultiSeleccion(obj){
	var sValor="";
	for (var i=0; i < obj.length; i++){
		if (obj.options[i].selected && obj.options[i].value!=""){
			sValor+="," + obj.options[i].value;
		}
	}
	if(sValor!=""){
		sValor=sValor.substring(1,sValor.length);
	}
	return	sValor;
}
var adWin;
function checkAviso() {
	var f=document.forms[0];
	if (adWin.closed) {
	} else setTimeout("checkAviso()",1);
}
function pan_aviso(){

	var tamanox=window.screen.availWidth;
	var tamanoy=window.screen.availHeight;

	var posicionx=0;
	var posiciony=0;
	var direccion="<?php echo Constant("HTTP_SERVER");?>avisoLegal.php?fLang=<?php echo $sLang;?>";
	adWin = window.open("",'ventana','fullscreen=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=yes,resizable=0');
	adWin.resizeTo(tamanox,tamanoy);
	adWin.moveTo(posicionx,posiciony);
	adWin.location=direccion;
	adWin.focus();
	checkAviso();
}
function setIdsSectores(iCont){
	var f= document.forms[0];
	var sIds = "";
	f.fSectorMB.value = "";
	for(i=0; i < iCont; i++ ){
		if (eval("document.forms[0].fIdSectorChk"+i)!=null){
			aIdSectores = eval("document.forms[0].fIdSectorChk"+i);
			if (aIdSectores.type == "checkbox"){
				if (aIdSectores.checked){
					sIds +="," + aIdSectores.value;
				}
			}
		}
	}
	if (sIds != ""){
		sIds = sIds.substring(1, sIds.length);
		f.fSectorMB.value = sIds;
	}
}

function enviar()
{
	var f=document.forms[0];
	if(f.fAcepta.checked){
		if (validaForm()){
			<?php
			if ($cEmpresas->getFecNacimientoMB() == 'on'){
				echo "f.fFechaNacimiento.value=cFechaFormat(f.fFechaNacimiento.value);";
			}
			?>

			return true;
		}else	return false;
	}
	alert("<?php echo constant("STR_DEBE_ACEPTAR_LOS_TERMINOS");?>");
	return false;
}
function noBack(){
////////////////////////////////
	window.history.forward();
}
onclick="if (document.getElementById('Mas').style.display == 'none'){document.getElementById('Mas').style.display='block';}else{document.getElementById('Mas').style.display='none';}"
window.history.forward();
//]]>
</script>
</head>
<body onload="noBack();" >
<form name="form" method="post" action="responsabilidades.php" onsubmit="return enviar();">
<?php
$HELP="xx";
?>
<div id="contenedor">
	<?php include_once(constant("DIR_WS_INCLUDE") . 'cabecera.php');?>
	<div id="envoltura">
		<div id="contenido">
			<div style="width: 100%;">
		     	 <table border="0" cellpadding="0" cellspacing="0" width="95%" >
		        	<tr>
		        		<td class="negro" colspan="2">
			        		<font class="titulo"><strong><?php echo constant("STR_DATOS_TITULO");?></strong></font>
			        	<!-- 	<br /><br />
							<?php echo constant("STR_DATOS_SUBTITULO");?>
						 -->
							<br /><br />
							<?php echo constant("STR_DATOS_TEXTO");?>
							<br /><br />
		        		</td>
		        	</tr>
		        	<tr>
		        		<td valign="top" colspan="2">
		        			<table align="center" border="0" cellpadding="4" cellspacing="10" width="50%">
		        			<?php
		        			if ($cEmpresas->getNombreCan() == 'on'){ ?>
		        				<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getNombreCan() == 'on'){echo constant('STR_NOMBRE')?>:<br /><br /><input autocomplete="off" class="obliga" maxlength="255" size="9" name="fNombre" value="<?php echo $cCandidato->getNombre();?>" onchange="javascript:trim(this);" type="text"><?php }?></td>
					        	</tr>
					        <?php }
					        if ($cEmpresas->getApellido1() == 'on'){ ?>
		        				<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getApellido1() == 'on'){echo constant('STR_APELLIDO1')?>:<br /><br /><input autocomplete="off" class="obliga" maxlength="255" size="9" name="fApellido1" value="<?php echo $cCandidato->getApellido1();?>" onchange="javascript:trim(this);" type="text"><?php }?></td>
					        	</tr>
					        <?php }
					        if ($cEmpresas->getApellido2() == 'on'){ ?>
		        				<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getApellido2() == 'on'){echo constant('STR_APELLIDO2')?>:<br /><br /><input autocomplete="off" class="obliga" maxlength="255" size="9" name="fApellido2" value="<?php echo $cCandidato->getApellido2();?>" onchange="javascript:trim(this);" type="text"><?php }?></td>
					        	</tr>
					        <?php }
					        if ($cEmpresas->getMailCan() == 'on'){ ?>
		        				<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getMailCan() == 'on'){echo constant('STR_MAIL')?>:<br /><br /><input autocomplete="off" class="obliga" maxlength="255" size="9" name="fMailCan" value="<?php echo $cCandidato->getMail();?>" onchange="javascript:trim(this);" type="text"><?php }?></td>
					        	</tr>
					        <?php }

								if (!empty($_POST["blind"]))
								{
								?>
		        				<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getMailCan() == 'on'){echo constant('STR_PASSWORD')?>:<br /><br /><input autocomplete="off" class="obliga" maxlength="255" size="9" name="fPassword" value="" onchange="javascript:trim(this);" type="text"><?php }?></td>
					        	</tr>
					        	<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getMailCan() == 'on'){echo constant('STR_CONF_PASSWORD')?>:<br /><br /><input autocomplete="off" class="obliga" maxlength="255" size="9" name="fConfPassword" value="" onchange="javascript:trim(this);" type="text"><?php }?></td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getNifCan() == 'on'){ ?>
		        				<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getNifCan() == 'on'){echo constant('STR_DNI')?>:<br /><br /><input autocomplete="off" class="obliga" maxlength="255" size="9" name="fNifCan" value="<?php echo $cCandidato->getDni();?>" onchange="javascript:trim(this);" type="text"><?php }?></td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getEdad() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getEdad() == 'on'){echo constant('STR_EDAD');?>:<br /><br /><?php $comboEDADES->setNombre("fIdEdad");?><?php echo $comboEDADES->getHTMLCombo("1","obliga", $cCandidato->getIdEdad()," ","");}?></td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getSexo() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getSexo() == 'on'){echo constant('STR_SEXO');?>:<br /><br /><?php $comboSEXOS->setNombre("fIdSexo");?><?php echo $comboSEXOS->getHTMLCombo("1","obliga",$cCandidato->getIdSexo()," ","");}?></td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getNivel() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getNivel() == 'on'){echo constant('STR_NIVEL_ACTUAL')?>:<br /><br /><?php $comboNIVELES->setNombre("fIdNivel");?><?php echo $comboNIVELES->getHTMLCombo("1","obliga",$cCandidato->getIdNivel()," ","");}?></td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getFormacion() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getFormacion() == 'on'){echo constant('STR_FORMACION_ACADEMICA');?>:<br /><br /><?php $comboFORMACIONES->setNombre("fIdFormacion");?><?php echo $comboFORMACIONES->getHTMLCombo("1","obliga",$cCandidato->getIdFormacion()," ","");}?></td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getArea() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getArea() == 'on'){echo constant('STR_AREA')?>:<br /><br /><?php $comboAREAS->setNombre("fIdArea");?><?php echo $comboAREAS->getHTMLCombo("1","obliga",$cCandidato->getIdArea()," ","");}?></td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getTelefono() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getTelefono() == 'on'){echo constant('STR_TELEFONO')?>:<br /><br /><input autocomplete="off" class="obliga" maxlength="20" size="9" name="fTelefono" value="<?php echo $cCandidato->getTelefono();?>" onchange="javascript:trim(this);" type="text"><?php }?></td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getSectorMB() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob">
					        		<?php
					        		if ($cCandidato->getSectorMB() != ""){
					        			$aSectorMB = explode(",", $cCandidato->getSectorMB());
					        			$cCandidato->setSectorMB($aSectorMB);
					        		}

					        		?>
					        		<?php
					        		$sSECTORESMB1="";
					        		if ($cEmpresas->getSectorMB() == 'on')
					        		{
					        			echo constant('STR_SECTOR');?>:

					        		<?php
					        		$rsSECTORESMB = $comboSECTORESMB->getDatos();
					        		$iSECTORESMB		= $rsSECTORESMB->RecordCount();

					        		if (is_array($cCandidato->getSectorMB())) {
					        			$aSECTORESMB =  $cCandidato->getSectorMB();
					        		}else{
					        			if ($cCandidato->getSectorMB() != ""){
					        				$aSECTORESMB =  explode(",",$cCandidato->getSectorMB());
					        			}else{
					        				$aSECTORESMB = array();
					        			}
					        		}

					        		$sAsIdSECTORESMB		= $comboSECTORESMB->getIdKey();
//					        		echo "<br />sAsIdSECTORESMB::" . $sAsIdSECTORESMB;
					        		$sAsSECTORESMB		= $comboSECTORESMB->getDescKey();
//					        		echo "<br />sAsSECTORESMB::" . $sAsSECTORESMB;
					        		$sDefaultSECTORESMB	= $comboSECTORESMB->getDefault();
//					        		echo "<br />sDefaultSECTORESMB::" . $sDefaultSECTORESMB;
					        		$sSECTORESMB1 = '';
					        		$i=0;

					        		$rsSECTORESMB->Move(0);	//Posicionamos en el primer registro.
					        		$sChecked = "";
					        		$sDisabled = "";
					        		$sIdsSECTORESMB = "";
					        		while (!$rsSECTORESMB->EOF)
					        		{
					        			$sChecked = "";
					        			if (in_array($rsSECTORESMB->fields[$sAsIdSECTORESMB], $aSECTORESMB)){
					        				$sIdsSECTORESMB .= "," . $rsSECTORESMB->fields[$sAsIdSECTORESMB];
					        				$sChecked='checked=\"checked\"';
					        			}
					        			if ($i==0){
					        				$sSECTORESMB1 .= '<ul>';
					        			}
					        			if (($i%6) == 0){
					        				$sSECTORESMB1 .= '</ul><ul>';
					        			}
					        			$sSECTORESMB1 .= '<li style="display:inline"><input ' . $sDisabled . ' id="fIdSectorChk' . $i . '" name="fIdSectorChk' . $i . '" type="checkbox" ' . $sChecked . ' onclick="setIdsSectores(' . $iSECTORESMB . ');" value="' . $rsSECTORESMB->fields[$sAsIdSECTORESMB] . '">' . '<label for="fIdSectorChk' . $i . '" title="' . $rsSECTORESMB->fields[$sAsSECTORESMB] . '">' . $rsSECTORESMB->fields[$sAsSECTORESMB] . '</label>';
					        			$sSECTORESMB1 .= '</li>';
					        			$i++;
					        			$rsSECTORESMB->MoveNext();
					        		}
					        		if (!empty($sIdsSECTORESMB)){
					        			$sIdsSECTORESMB = substr($sIdsSECTORESMB,1);
					        		}
					        		$sSECTORESMB1 .= '<input type=hidden name="fSectorMB" value="' . $sIdsSECTORESMB . '">';

					        		}
					        		echo $sSECTORESMB1;
					?>

					        		</td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getConcesionMB() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getConcesionMB() == 'on'){echo constant('STR_CONCESION')?>:<br /><br /><textarea autocomplete="off" class="obliga" rows="3" cols="50"  name="fConcesionMB" onchange="javascript:trim(this);" ><?php echo $cCandidato->getConcesionMB();?></textarea><?php }?></td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getBaseMB() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getBaseMB() == 'on'){echo "Base"?>:<br /><br /><textarea autocomplete="off" class="obliga" rows="3" cols="50"  name="fBaseMB" onchange="javascript:trim(this);" ><?php echo $cCandidato->getBaseMB();?></textarea><?php }?></td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getFecNacimientoMB() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getFecNacimientoMB() == 'on'){
					        		 if ($cCandidato->getFechaNacimiento() != "" && $cCandidato->getFechaNacimiento() != "0000-00-00" && $cCandidato->getFechaNacimiento() != "0000-00-00 00:00:00"){
										$cCandidato->setFechaNacimiento($conn->UserDate($cCandidato->getFechaNacimiento(),constant("USR_FECHA"),false));
					        		 }

					        			echo constant('STR_FECHA_NACIMIENTO')?>:<br /><br /><input autocomplete="off" class="obliga" style=" width: 25%;" maxlength="10" size="9" name="fFechaNacimiento" value="<?php echo $cCandidato->getFechaNacimiento();?>" onchange="javascript:trim(this);" type="text">&nbsp;(dd/mm/aaaa)<?php }?></td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getEspecialidadMB() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob">
					        		<?php
					        		$sDisabledEspecialidad="";
					        		if ($cCandidato->getEspecialidadMB() != ""){
					        			$aEspecialidadMB = explode(",", $cCandidato->getEspecialidadMB());
					        			$sDisabledEspecialidad="disabled";
					        			$cCandidato->setEspecialidadMB($aEspecialidadMB);
					        		}

					        		?>
					        		<?php if ($cEmpresas->getEspecialidadMB() == 'on'){echo constant('STR_ESPECIALIDAD');?>:<br /><br /><?php $comboESPECIALIDADESMB->setNombre("fEspecialidadMB");?><?php echo $comboESPECIALIDADESMB->getHTMLCombo("1","obliga",$cCandidato->getEspecialidadMB(),$sDisabledEspecialidad,"");}?></td>
					        	</tr>
								<?php
								}
				        		if ($bSeas)
				        		{
						        	?>
						        	<tr>
						        		<td class="negrob"><?php if ($cEmpresas->getNivelConocimientoMB() == 'on'){echo constant('STR_NIVEL_CONOCIMIENTO_TECNICO')?>:<br /><br /><input autocomplete="off" class="obliga" style="width: 5%;"  maxlength="2" size="9" name="fNivelConocimientoMB" value="<?php echo $cCandidato->getNivelConocimientoMB();?>" onchange="javascript:trim(this);" type="text">&nbsp;[1-10]<?php }?></td>
						        	</tr>
								<?php
								}
								if ($cEmpresas->getPuestoEvaluar() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getPuestoEvaluar() == 'on'){echo "Puesto a evaluar"?>:<br /><br /><input autocomplete="off" class="obliga" maxlength="255" size="9" name="fPuestoEvaluar" value="<?php echo $cCandidato->getPuestoEvaluar();?>" onchange="javascript:trim(this);" type="text"><?php }?></td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getCategoriaForjanor() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getCategoriaForjanor() == 'on'){echo "Categoría profesional del puesto a evaluar"?>:<br /><br /><?php $CATEGORIASPROFESIONALES->setNombre("fCategoriaForjanor");?><?php echo $CATEGORIASPROFESIONALES->getHTMLCombo("1","obliga",$cCandidato->getCategoriaForjanor()," ","");}?></td>
					        	</tr>
								<?php
								}
								if ($cEmpresas->getResponsableDirecto() == 'on'){ ?>
					        	<tr>
					        		<td class="negrob"><?php if ($cEmpresas->getResponsableDirecto() == 'on'){echo "Responsable directo"?>:<br /><br /><input autocomplete="off" class="obliga" maxlength="255" size="9" name="fResponsableDirecto" value="<?php echo $cCandidato->getResponsableDirecto();?>" onchange="javascript:trim(this);" type="text"><?php }?></td>
					        	</tr>
								<?php
								}
								?>
					        	<tr>
					        		<td class="negrob" onclick="javascript:pan_aviso();document.forms[0].fAcepta.checked=true;">
												<input type="hidden" name="fAceptaMazda" id="fAceptaMazda" value="<?php echo $cCandidato->getAceptaMazda();?>" >
					        			<?php
					        			$sCheckedCOND="";
					        			$sCheckDISPLAY="";
					        			$sIdArea=$cCandidato->getIdArea();
					        			$sIdEdad=$cCandidato->getIdEdad();
					        			$sIdSexo=$cCandidato->getIdSexo();
					        			$sIdNivel=$cCandidato->getIdNivel();
					        			$sIdFormacion=$cCandidato->getIdFormacion();
					        			$sNombre=$cCandidato->getNombre();
					        			$sApellido1=$cCandidato->getApellido1();
					        			$sApellido2=$cCandidato->getApellido2();
					        			$sMail=$cCandidato->getMail();
					        			$sNif=$cCandidato->getDni();
					        			$sTelefono=$cCandidato->getTelefono();
					        			$sSectorMB=$cCandidato->getSectorMB();
					        			$sConcesionMB=$cCandidato->getConcesionMB();
					        			$sBaseMB=$cCandidato->getBaseMB();

					        			$sFecNacimientoMB=$cCandidato->getFechaNacimiento();
					        			$sEspecialidadMB=$cCandidato->getEspecialidadMB();
					        			if ($bSeas){
					        				$sNivelConocimientoMB=$cCandidato->getNivelConocimientoMB();
					        			}


					        			$sPuestoEvaluar=$cCandidato->getPuestoEvaluar();
					        			$sCategoriaForjanor=$cCandidato->getCategoriaForjanor();
					        			$sResponsableDirecto=$cCandidato->getResponsableDirecto();

										$iCondiciones = 0;
										$iOK = 0;
					        			if ($cEmpresas->getEdad() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sIdEdad)){
					        					$iOK ++;
					        				}
					        			}
					        			if ($cEmpresas->getSexo() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sIdSexo)){
					        					$iOK ++;
					        				}
					        			}
					        			if ($cEmpresas->getNivel() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sIdNivel)){
					        					$iOK ++;
					        				}
					        			}
					        			if ($cEmpresas->getArea() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sIdArea)){
					        					$iOK ++;
					        				}
					        			}
					        			if ($cEmpresas->getFormacion() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sIdFormacion)){
					        					$iOK ++;
					        				}
					        			}
					        			if ($cEmpresas->getNombreCan() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sNombre)){
					        					$iOK ++;
					        				}
					        			}
					        			if ($cEmpresas->getApellido1() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sApellido1)){
					        					$iOK ++;
					        				}
					        			}
					        			if ($cEmpresas->getApellido2() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sApellido2)){
					        					$iOK ++;
					        				}
					        			}

					        			if ($cEmpresas->getMailCan() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sMail)){
					        					$iOK ++;
					        				}
					        			}
					        			if ($cEmpresas->getNifCan() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sNif)){
					        					$iOK ++;
					        				}
					        			}


					        			if ($cEmpresas->getTelefono() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sTelefono)){
					        					$iOK ++;
					        				}
					        			}
					        			if ($cEmpresas->getSectorMB() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sSectorMB)){
					        					$iOK ++;
					        				}
					        			}
					        			if ($cEmpresas->getConcesionMB() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sConcesionMB)){
					        					$iOK ++;
					        				}
					        			}
					        			if ($cEmpresas->getBaseMB() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sBaseMB)){
					        					$iOK ++;
					        				}
					        			}

					        			if ($cEmpresas->getFecNacimientoMB() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sFecNacimientoMB)){
					        					$iOK ++;
					        				}
					        			}
					        			if ($cEmpresas->getEspecialidadMB() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sEspecialidadMB)){
					        					$iOK ++;
					        				}
					        			}
					        			if ($bSeas){
					        				if ($cEmpresas->getNivelConocimientoMB() == 'on'){
					        					$iCondiciones ++;
					        					if (!empty($sNivelConocimientoMB)){
					        						$iOK ++;
					        					}
					        				}
					        			}


					        			if ($cEmpresas->getPuestoEvaluar() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sPuestoEvaluar)){
					        					$iOK ++;
					        				}
					        			}

					        			if ($cEmpresas->getCategoriaForjanor() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sCategoriaForjanor)){
					        					$iOK ++;
					        				}
					        			}

					        			if ($cEmpresas->getResponsableDirecto() == 'on'){
					        				$iCondiciones ++;
					        				if (!empty($sResponsableDirecto)){
					        					$iOK ++;
					        				}
					        			}


					        			if(	$iCondiciones == $iOK )
					        			{
					        				$sCheckedCOND="checked=\"checked\" disabled=\"disabled\"";
										}
										if ($cEmpresas->getAvisoLegal() != "1")
										{
											$sCheckDISPLAY=' style="display:none;" ';
											$sCheckedCOND="checked=\"checked\" disabled=\"disabled\"";
										}
										?>
			        					<input <?php echo $sCheckDISPLAY;?> type="checkbox" id="fAcepta" <?php echo $sCheckedCOND;?> /> &nbsp;<a style="color:#000;" href="#_" <?php echo $sCheckDISPLAY;?>><?php echo constant("STR_CONDICIONES_LEGALES");?></a>
				        			</td>
					        	</tr>
					        	<tr>
					        		<td>&nbsp;</td>
					        	</tr>
		        			</table>
		        		</td>
		        	</tr>
		        	<tr>
		        		<td align="right" valign="top" style="padding-right: 10px;padding-top: 30px;">
		        		<input type="submit" value="<?php echo constant("STR_CONTINUAR");?>" class="botones" />
		        		</td>
		        	</tr>
		        </table>
		    </div>

		</div>
	</div>
     <?php //include_once(constant("DIR_WS_INCLUDE") . 'menus.php');?>
    <?php //include_once(constant("DIR_WS_INCLUDE") . 'derecha.php');?>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
<?php
if (!empty($_POST["blind"]))
{
?>
	<input type="hidden" name="blind" value="<?php echo $_POST["blind"];?>" />
<?php
}
?>

<input type="hidden" name="sTKCandidatos" value="<?php echo $cCandidato->getToken();?>" />
<input type="hidden" name="fIdEmpresa" value="<?php echo $cCandidato->getIdEmpresa();?>" />
<input type="hidden" name="fIdProceso" value="<?php echo $cCandidato->getIdProceso();?>" />
<input type="hidden" name="fIdCandidato" value="<?php echo $cCandidato->getIdCandidato();?>" />
<input type="hidden" name="fMail" value="<?php echo $cCandidato->getMail();?>" />
<input type="hidden" name="fNif" value="<?php echo $cCandidato->getDni();?>" />
<input type="hidden" name="fLang" value="<?php echo $sLang;?>" />

</form>

</body>
</html>
