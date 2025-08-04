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
	alert("<?php echo constant("STR_DEBE_ACEPTAR");?>");
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
		     	 <table border="0" cellpadding="0" cellspacing="0" width="95%" >
		        	<tr>
		        		<td class="negro" colspan="2">
			        		<font class="titulo"><strong><?php echo constant("STR_SU_COMPROMISO");?></strong></font>
							<br /><br />
							<?php echo constant("STR_SU_COMPROMISO_TEXTO");?>
							<br /><br />
		        		</td>
		        	</tr>
		        	<tr>
		        		<td valign="top" colspan="2">
					        	<tr>
					        		<td class="negrob" onclick="javascript:document.forms[0].fAcepta.checked=true;">
					        			<?php

									$sCheckedCOND="";
					        		$sCheckDISPLAY="";
									$iCondiciones = 0;
									$iOK = 0;
									if (isset($_POST['fAceptaMazda']) && $_POST['fAceptaMazda'] != ""){
										$cCandidato->setAceptaMazda($_POST['fAceptaMazda']);
									}

									if (isset($_POST['fNombre']) && $_POST['fNombre'] != ""){
										$cCandidato->setNombre($_POST['fNombre']);
									}
									if (isset($_POST['fApellido1']) && $_POST['fApellido1'] != ""){
										$cCandidato->setApellido1($_POST['fApellido1']);
									}
									if (isset($_POST['fApellido2']) && $_POST['fApellido2'] != ""){
										$cCandidato->setApellido2($_POST['fApellido2']);
									}
									if (isset($_POST['fMailCan']) && $_POST['fMailCan'] != ""){
										$cCandidato->setMail($_POST['fMailCan']);
									}
									if (isset($_POST['fNifCan']) && $_POST['fNifCan'] != ""){
										$cCandidato->setDni($_POST['fNifCan']);
									}
						            if (isset($_POST['fIdEdad']) && $_POST['fIdEdad'] != ""){
						    		    $cCandidato->setIdEdad($_POST['fIdEdad']);
						            }
									if (isset($_POST['fIdSexo']) && $_POST['fIdSexo'] != ""){
										$cCandidato->setIdSexo($_POST['fIdSexo']);
									}
									if (isset($_POST['fIdNivel']) && $_POST['fIdNivel'] != ""){
										$cCandidato->setIdNivel($_POST['fIdNivel']);
									}
									if (isset($_POST['fIdArea']) && $_POST['fIdArea'] != ""){
						    		    $cCandidato->setIdArea($_POST['fIdArea']);
						            }
						            if (isset($_POST['fIdFormacion']) && $_POST['fIdFormacion'] != ""){
						    		    $cCandidato->setIdFormacion($_POST['fIdFormacion']);
						            }
						            if (isset($_POST['fTelefono']) && $_POST['fTelefono'] != ""){
						            	$cCandidato->setTelefono($_POST['fTelefono']);
						            }
						            if (isset($_POST['fSectorMB']) && $_POST['fSectorMB'] != ""){
						            	$cCandidato->setSectorMB($_POST['fSectorMB']);
						            }
						            if (isset($_POST['fConcesionMB']) && $_POST['fConcesionMB'] != ""){
						            	$cCandidato->setConcesionMB($_POST['fConcesionMB']);
						            }
						            if (isset($_POST['fBaseMB']) && $_POST['fBaseMB'] != ""){
						            	$cCandidato->setBaseMB($_POST['fBaseMB']);
						            }
						            if (isset($_POST['fFechaNacimiento']) && $_POST['fFechaNacimiento'] != ""){
						            	$cCandidato->setFechaNacimiento($_POST['fFechaNacimiento']);
						            }
						            if (isset($_POST['fEspecialidadMB']) && $_POST['fEspecialidadMB'] != ""){
						            	$cCandidato->setEspecialidadMB($_POST['fEspecialidadMB']);
						            }
					            	if (isset($_POST['fNivelConocimientoMB']) && $_POST['fNivelConocimientoMB'] != ""){
					            		$cCandidato->setNivelConocimientoMB($_POST['fNivelConocimientoMB']);
					            	}
						            if (isset($_POST['fPuestoEvaluar']) && $_POST['fPuestoEvaluar'] != ""){
						            	$cCandidato->setPuestoEvaluar($_POST['fPuestoEvaluar']);
						            }
						            if (isset($_POST['fResponsableDirecto']) && $_POST['fResponsableDirecto'] != ""){
						            	$cCandidato->setResponsableDirecto($_POST['fResponsableDirecto']);
						            }
						            if (isset($_POST['fCategoriaForjanor']) && $_POST['fCategoriaForjanor'] != ""){
						            	$cCandidato->setCategoriaForjanor($_POST['fCategoriaForjanor']);
						            }

							    	if ($cCandidato->getIdCandidato() != ""){
							      		$cCandidatosDB->modificar($cCandidato);
							        }else{
							        	echo(constant("ERR") . " C not found.");
							            exit;
							        }

												?>
			        					<input <?php echo $sCheckDISPLAY;?> type="checkbox" id="fAcepta" <?php echo $sCheckedCOND;?> /> &nbsp;<a href="#_" <?php echo $sCheckDISPLAY;?>><?php echo constant("STR_ACEPTO");?></a>
												<br /><br /><?php echo constant("STR_SI_NO_ACEPTO_TEXTO");?>
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
