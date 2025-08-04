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
	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas_empresas/Informes_pruebas_empresasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas_empresas/Informes_pruebas_empresas.php");
	
include_once ('include/conexion.php');
//}

    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
	$_sPrefijo = "";
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
    	$_sPrefijo = "../../";
    }

	//require_once($_sPrefijo . "include/Servired/confServired.php");
    $cCandidato = new Candidatos();

    $cCandidato  = $_cEntidadCandidatoTK;
 
    $cEmpresas = new Empresas();
    $cEmpresasDB = new EmpresasDB($conn);
    $cEmpresas->setIdEmpresa($cCandidato->getIdEmpresa());
    $cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
    
    $cProceso_informesDB = new Proceso_informesDB($conn);	// Entidad DB
    $cProceso_informes = new Proceso_informes();	// Entidad DB
    $cInformes_pruebasDB	= new Informes_pruebasDB($conn);  // Entidad DB
    $cInformes_pruebas_empresasDB	= new Informes_pruebas_empresasDB($conn);  // Entidad DB
        
    $comboAREAS			= new Combo($conn,"fIdArea","idArea","nombre","Descripcion","areas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboEDADES		= new Combo($conn,"fIdEdad","idEdad","nombre","Descripcion","edades","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","idEdad","");
    $comboNIVELES		= new Combo($conn,"fIdNivel","idNivel","nombre","Descripcion","nivelesjerarquicos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboFORMACIONES 	= new Combo($conn,"fIdFormacion","idFormacion","nombre","Descripcion","formaciones","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboSEXOS			= new Combo($conn,"fIdSexo","idSexo","nombre","Descripcion","sexos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
?>
<!doctype html>
<html lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
     <script src="codigo/codigo.js"></script>
     <script src="codigo/comun.js"></script>
	 <script src="codigo/common.js"></script>
	 <script src="codigo/eventos.js"></script>
	 <script src="codigo/noback.js"></script>
	 <script src="codigo/jquery.tools.min.js"></script>
<script   >
//<![CDATA[
           <?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php");?>
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	
	msg +=vNumber("<?php echo constant("STR_EDAD");?>:",f.fIdEdad.value,11,true);
	msg +=vNumber("<?php echo constant("STR_SEXO");?>:",f.fIdSexo.value,11,true);
	msg +=vNumber("<?php echo constant("STR_NIVEL_ACTUAL");?>:",f.fIdNivel.value,11,true);
	msg +=vNumber("<?php echo constant("STR_AREA");?>:",f.fIdArea.value,11,true);
	msg +=vNumber("<?php echo constant("STR_FORMACION_ACADEMICA");?>:",f.fIdFormacion.value,11,true);
	
if (msg != "") {
	alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
	return false;
}else return true;
}
function Pago(page){
	var f= document.forms[0];
	if (page !=""){
		f.sPG.value="recargasa";
		$("#pagoIN").show().load("jQuery.php",$("form").serializeArray()).fadeIn("slow");
		f.ACCION.value=page;
	}
}
function chkPago(){
	var f= document.forms[0];
	f.sPG.value="OkSeguir";
	$("#okSeguir").show().load("jQuery.php",$("form").serializeArray()).fadeIn("slow");
}

function enviar()
{
	var f=document.forms[0];
	if(f.fAcepta.checked){
		if (validaForm()){
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
<form name="form" method="post" action="pruebas.php" onsubmit="return enviar();">
<?php
$HELP="xx";
?>
<div id="contenedor">
	<?php include_once(constant("DIR_WS_INCLUDE") . 'cabecera.php');?>
	<div id="envoltura">
		<div id="contenido">
			<div style="width: 100%;">
				<?php
				//Miramos cuanto hay que descontar según las pruebas y los informes asignados en el proceso.
				$cProceso_informes->setIdEmpresa($cCandidato->getIdEmpresa());
				$cProceso_informes->setIdProceso($cCandidato->getIdProceso());
				$sSQLProceso_informes = $cProceso_informesDB->readLista($cProceso_informes);
				//echo "<br />->Proceso_informes:: " . $sSQLProceso_informes;
				$rsProceso_informes = $conn->Execute($sSQLProceso_informes);
				$intentaRecargar=0;				
				while(!$rsProceso_informes->EOF){
					//Cambiar Dongels por Cliente/Prueba/Informe
					//Miramos si tiene definido dongles por empresa
					$cInformesPruebasTrf = new Informes_pruebas_empresas();
					$cInformesPruebasTrf->setIdPrueba($rsProceso_informes->fields['idPrueba']);
					$cInformesPruebasTrf->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
					$cInformesPruebasTrf->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
					$cInformesPruebasTrf->setIdEmpresa($cCandidato->getIdEmpresa());
						
					$sql_IPE = $cInformes_pruebas_empresasDB->readLista($cInformesPruebasTrf);
//					echo "<br />Informes_pruebas_empresas::" . $sql_IPE;
					$rsIPE = $conn->Execute($sql_IPE);
					if ($rsIPE->NumRows() > 0){
						$cInformesPruebasTrf = $cInformes_pruebas_empresasDB->readEntidad($cInformesPruebasTrf);
//						echo "<br />InformesPruebasEmpTrf::" . $cInformesPruebasTrf->getPrecio();
					}else {
						$cInformesPruebasTrf = new Informes_pruebas();
						$cInformesPruebasTrf->setIdPrueba($rsProceso_informes->fields['idPrueba']);
						$cInformesPruebasTrf->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
						$cInformesPruebasTrf->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
						$cInformesPruebasTrf= $cInformes_pruebasDB->readEntidad($cInformesPruebasTrf);
//						echo "<br />InformesPruebasTrf::" . $cInformesPruebasTrf->getPrecio();
					}
					
					$intentaRecargar += ($cInformesPruebasTrf->getPrecio() == "") ? 0 : $cInformesPruebasTrf->getPrecio();
					
					$rsProceso_informes->MoveNext();
				}

				if ($intentaRecargar <= 0){
					error_log(date('d/m/Y H:i:s') . " ¡ATENCIÓN! -> Se intenta cobrar IMPORTE 0 por TPV.\n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));
					echo constant("ERR_ADMINISTRADOR");
					exit;
				}
				switch ($_sTipoTpv)
				{
					case "1":	//ServiRed
						include_once($_sPrefijo . "include/Servired/camposServired.php");
						break;
					case "2":	//Sistema 4B
						include_once($_sPrefijo . "include/4b/campos4b.php");
						break;
					case "3":	//Euro 6000
						
						break;
					case "4":	//PayPal
					
						break;
						
				}
				?> 			
		     	 <table align="center" border="0" cellpadding="0" cellspacing="0" width="47%" >
		        	<tr>
		        		<td class="negro" colspan="2">
			        		<font class="titulo"><strong><?php echo constant("STR_TPV_DATOS_TITULO");?></strong></font>	
			        		<font style="font-family: calibri;font-size: 14pt;"><br /><br />
							<?php echo sprintf(constant("STR_TPV_DATOS_SUBTITULO"), "<strong>" . number_format($intentaRecargar ,2) . "</strong>");?>
							<br /><br />
							<?php echo constant("STR_TPV_DATOS_TEXTO");?>
							<br /><br />
							</font>
		        		</td>
		        	</tr>
		        	<tr>
						<td align="center" >
							<a class="negro" href="bank.php" title="<?php  echo constant("STR_TPV_PAGAR");?>" name="fBtn<?php  echo str_replace(" ", "", $cTipos_tpv->getDescripcion());?>" onclick="javascript:Pago('<?php  echo $cTipos_tpv->getSERVICE_ACTION();?>');" target="_blank" style="font-size:1.1em;" >
								<span class="pagar"><?php echo constant("STR_TPV_PAGAR");?></span>&nbsp;&nbsp;
								<span class="imgPagar"><img src="graf/Tarjetas.jpg" title="<?php  echo constant("STR_TPV_PAGAR");?>" alt="<?php  echo constant("STR_TPV_PAGAR");?>" border="0" width="45" height="45" /></span>
							</a>
						</td>
		        	</tr>
		        	<tr>
		        		<td align="right" valign="top" style="padding-right: 10px;padding-top: 30px;">
		        		<!--  <input type="submit" value="<?php echo constant("STR_CONTINUAR");?>" class="botones" />-->
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
<div id="pagoIN" style="display: none;"></div>
<input type="hidden" name="ACCION" value="" />
<input type="hidden" name="sTKCandidatos" value="<?php echo $cCandidato->getToken();?>" />
<input type="hidden" name="fIdEmpresa" value="<?php echo $cCandidato->getIdEmpresa();?>" />
<input type="hidden" name="fIdProceso" value="<?php echo $cCandidato->getIdProceso();?>" />
<input type="hidden" name="fIdCandidato" value="<?php echo $cCandidato->getIdCandidato();?>" />
<input type="hidden" name="fMail" value="<?php echo $cCandidato->getMail();?>" />
<input type="hidden" name="fLang" value="<?php echo $sLang;?>" />

<input type="hidden" name="fIdEdad" value="<?php echo $cCandidato->getIdEdad();?>" />
<input type="hidden" name="fIdSexo" value="<?php echo $cCandidato->getIdSexo();?>" />
<input type="hidden" name="fIdNivel" value="<?php echo $cCandidato->getIdNivel();?>" />
<input type="hidden" name="fIdFormacion" value="<?php echo $cCandidato->getIdFormacion();?>" />
<input type="hidden" name="fIdArea" value="<?php echo $cCandidato->getIdArea();?>" />

<input type="hidden" name="sPG" value="" />
<?php
	include_once("Template/OkSeguir.php");
?>
<script>
	setInterval("chkPago()",2000);
</script>
</form>

</body>
</html>
