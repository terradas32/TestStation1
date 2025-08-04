<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('./include/Configuracion.php');
	$_REQUEST["fLang"] = $_REQUEST["fCodIdiomaIso2"];
	include('include/Idiomas.php');
	//include_once(constant("DIR_WS_INCLUDE") . 'SeguridadCandidatos.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Instrucciones_pruebas/Instrucciones_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Instrucciones_pruebas/Instrucciones_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Ejemplos/EjemplosDB.php");
	require_once(constant("DIR_WS_COM") . "Ejemplos/Ejemplos.php");
	require_once(constant("DIR_WS_COM") . "Combo.php");

include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");


	
	//$cRespPruebasDB = new Respuestas_pruebasDB($conn);
    
    $cCandidato = new Candidatos();
    $cCandidato  = $_cEntidadCandidatoTK;
    
    $cProceso_pruebas = new Proceso_pruebas();
    $cProceso_pruebasDB = new Proceso_pruebasDB($conn);
    $cPruebasDB = new PruebasDB($conn);
   	$cEjemplosDB = new EjemplosDB($conn);
    
    $cPruebas = new Pruebas();
    
    $cPruebas->setIdPrueba($_POST['fIdPrueba']);
	$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	
	$cPruebas = $cPruebasDB->readEntidad($cPruebas);
    
	$sPreguntasPorPagina = $cPruebas->getPreguntasPorPagina();
	
	if($sPreguntasPorPagina<1){
		$sPreguntasPorPagina=1;
	}
	
    $cProceso_pruebas->setIdEmpresa($cCandidato->getIdEmpresa());
    $cProceso_pruebas->setIdProceso($cCandidato->getIdProceso());
    
    $cInstrucciones_pruebas = new Instrucciones_pruebas();
    $cInstrucciones_pruebasDB = new Instrucciones_pruebasDB($conn);
    
    //Miro si esisten instrucciones en el idoma que ha entrado el candidato,
    // si no le pongo en el idioma que se seleccionó de la prueba
    $cInstrucciones_pruebas->setIdPrueba($_POST['fIdPrueba']);   
    $cInstrucciones_pruebas->setCodIdiomaIso2($_REQUEST['fLang']);
    $sqlInstrucciones_pruebas = $cInstrucciones_pruebasDB->readLista($cInstrucciones_pruebas);
//    echo $sqlInstrucciones_pruebas;
    $rsInstrucciones_pruebas = $conn->Execute($sqlInstrucciones_pruebas);
    if ($rsInstrucciones_pruebas->NumRows() <= 0){
    	$cInstrucciones_pruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    }
    $cInstrucciones_pruebas = $cInstrucciones_pruebasDB->readEntidadPorPrueba($cInstrucciones_pruebas);
	
    $cEjemplos = new Ejemplos();
    $cEjemplos->setIdPrueba($_POST['fIdPrueba']);
    $cEjemplos->setIdPruebaHast($_POST['fIdPrueba']);
    $cEjemplos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    $cEjemplos->setOrderBy("orden");
    $cEjemplos->setOrder("ASC");
    
    $sqlEjemplos = $cEjemplosDB->readLista($cEjemplos);
//    echo $sqlEjemplos;
    $listaEjemplos = $conn->Execute($sqlEjemplos);
    
    $iTamaniolistaEjemplos = $listaEjemplos->recordCount();
?>
<!doctype html>
<html lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<link rel="stylesheet" href="estilos/apple-overlay.css" type="text/css" />
     <script src="codigo/comun.js"></script>
	 <script src="codigo/common.js"></script>
	 <script src="codigo/eventos.js"></script>
	 <script src="codigo/noback.js"></script>
	 <script src="codigo/jQuery1.4.2.js"></script>
	 <script src="codigo/jquery.tools.min.js"></script>
<script   >
//<![CDATA[  
Array.prototype.in_array=function(){ 
    for(var j in this){ 
        if(this[j]==arguments[0]){ 
            return true; 
        } 
    } 
    return false;     
}
function cargaListening(){
	var aListening=['58','59','60'];	//KPMG
	if(aListening.in_array(<?php echo $cPruebas->getIdPrueba();?>)){
		var f = document.forms[0];
		var paginacargada = "cargaAudio.php";
		$("div#audio").load(paginacargada,{
			fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
			fIdEmpresa: f.fIdEmpresa.value,
			fIdProceso: f.fIdProceso.value,
			fIdCandidato: f.fIdCandidato.value,
			fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
			sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>" });
	} 
}  

//]]>   
 </script> 
</head>
<body onload="document.getElementById('triggers').style.display = 'none';">
<form name="form" method="post" action="ejemplos.php">
<?php
$HELP="xx";
?>
<div id="contenedor">
	<?php include_once(constant("DIR_WS_INCLUDE") . 'cabecerapruebas.php');?>
	<div id="envoltura">
		<div id="contenido">
			<div style="width: 95%">
			<?php
			if ($cPruebas->getCabecera() != ""){ 
			?> 			
				<div id="cabeceraprueba">
					<img src="<?php echo constant("DIR_WS_GESTOR") . $cPruebas->getCabecera();?>" />
				</div>
		<?php
			}?>
				<p>
					<font class="titulo"><?php echo constant("STR_LEA_INSTRUCCIONES")?></font>	
				</p>
				<div>   
					<?php echo $cInstrucciones_pruebas->getTexto();?> 
				</div>
				
				<div id="pieprueba" align="right" style="padding-top: 30px;">
					<input type="submit" value="<?php echo ($iTamaniolistaEjemplos>0)?constant("STR_CONTINUAR"):constant("STR_COMENZAR")?>" class="botones"/>
				</div>
		    </div>


			<!-- make all links with the 'rel' attribute open overlays -->
			<script   >
			
			$(function() {
				$("#triggers img[rel]").overlay({effect: 'apple'});
			});
			</script>
		    
		</div>
	</div>
</div>
<div id="guardarespuesta"></div>
<input type="hidden" name="fIdEmpresa" value="<?php echo $cCandidato->getIdEmpresa();?>" />
<input type="hidden" name="fIdProceso" value="<?php echo $cCandidato->getIdProceso();?>" />
<input type="hidden" name="fIdCandidato" value="<?php echo $cCandidato->getIdCandidato();?>" />
<input type="hidden" name="fMail" value="<?php echo $cCandidato->getMail();?>" />
<input type="hidden" name="fIdPrueba" value="<?php echo $_POST['fIdPrueba']?>" />
<input type="hidden" name="fCodIdiomaIso2" value="<?php echo $_POST['fCodIdiomaIso2']?>" />
<input type="hidden" name="sTKCandidatos" value="<?php echo $cCandidato->getToken();?>" />
<script>cargaListening();</script>
</form> 
      
</body>
</html>      