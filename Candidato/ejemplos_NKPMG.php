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
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Ejemplos/EjemplosDB.php");
	require_once(constant("DIR_WS_COM") . "Ejemplos/Ejemplos.php");
	require_once(constant("DIR_WS_COM") . "Ejemplos_ayudas/Ejemplos_ayudasDB.php");
	require_once(constant("DIR_WS_COM") . "Ejemplos_ayudas/Ejemplos_ayudas.php");

	require_once(constant("DIR_WS_COM") . "Combo.php");
	

include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");


	$cEjemplos_ayudasDB = new Ejemplos_ayudasDB($conn);
	$cRespPruebasDB = new Respuestas_pruebasDB($conn);
    
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
	$sEstiloOpciones = $cPruebas->getEstiloOpciones();
	
	if($sPreguntasPorPagina<1){
		$sPreguntasPorPagina=1;
	}
	
    $cProceso_pruebas->setIdEmpresa($cCandidato->getIdEmpresa());
    $cProceso_pruebas->setIdProceso($cCandidato->getIdProceso());
    
    $cRespPruebas = new Respuestas_pruebas();
    $cRespPruebas->setIdProceso($_POST['fIdProceso']);
    $cRespPruebas->setIdEmpresa($_POST['fIdEmpresa']);
    $cRespPruebas->setIdCandidato($_POST['fIdCandidato']);
    $cRespPruebas->setIdPrueba($_POST['fIdPrueba']);
    $cRespPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    
    $cRespPruebas = $cRespPruebasDB->readEntidad($cRespPruebas);
    
    $cRespPruebas->setLeidoInstrucciones("1");
    $cRespPruebasDB->modificar($cRespPruebas);
    
    $sqlProcPruebas = $cProceso_pruebasDB->readLista($cProceso_pruebas);
    $listaProcesosPruebas = $conn->Execute($sqlProcPruebas);
    
    $iTamaniolistaEjemplos=0;
    
    $cEjemplos = new Ejemplos();
    $cEjemplos->setIdPrueba($_POST['fIdPrueba']);
    $cEjemplos->setIdPruebaHast($_POST['fIdPrueba']);
    $cEjemplos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    $cEjemplos->setOrderBy("orden");
    $cEjemplos->setOrder("ASC");
    
    $sqlEjemplos = $cEjemplosDB->readLista($cEjemplos);
    $listaEjemplos = $conn->Execute($sqlEjemplos);
    
    $iTamaniolistaEjemplos = $listaEjemplos->recordCount();
    
    $comboAREAS			= new Combo($conn,"fIdArea","idArea","nombre","Descripcion","areas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboEDADES		= new Combo($conn,"fIdEdad","idEdad","nombre","Descripcion","edades","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboNIVELES		= new Combo($conn,"fIdNivel","idNivel","nombre","Descripcion","nivelesjerarquicos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboFORMACIONES 	= new Combo($conn,"fIdFormacion","idFormacion","nombre","Descripcion","formaciones","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboSEXOS			= new Combo($conn,"fIdSexo","idSexo","nombre","Descripcion","sexos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    
    $iPaginas = $iTamaniolistaEjemplos / $sPreguntasPorPagina;
										 
	if($iTamaniolistaEjemplos % $sPreguntasPorPagina !=0){
		$iPaginas = intval($iPaginas) + 1;
	}
	$saltaAPrueba=false;
	if($iTamaniolistaEjemplos==0){
		$saltaAPrueba=true;	
	}
	$cEjemplos_ayudas = new Ejemplos_ayudas();
    $cEjemplos_ayudas->setIdPrueba($_POST['fIdPrueba']);
    $cEjemplos_ayudas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    $cEjemplos_ayudas->setIdAyuda("1");
    $cEjemplos_ayudas = $cEjemplos_ayudasDB->readEntidad($cEjemplos_ayudas);
    
    $sTime=0;
//    if($cRespPruebas->getMinutos_test() !="" && $cRespPruebas->getSegundos_test() !=""){
//    	$sTime = ($cRespPruebas->getMinutos_test()*60) + $cRespPruebas->getSegundos_test();
//    }else{
//    	$sTime=0;
//    }
    $sDisplay= '';
    //Se le asigna 8h que es el tiempo máximo ya que el
    //Cronometro no tiene horas
    //Le ponemos al cronometro horas y le establecemos 8h
    if($cPruebas->getDuracion2() == 0){
		$cPruebas->setDuracion2(480);	
		$sDisplay= 'display:none;';
	}
    $segundos=$cPruebas->getDuracion2()*60 - $sTime;
?>
<!doctype html>
<html lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<link rel="stylesheet" href="estilos/apple-overlay.css" type="text/css" />
    <link media="screen" rel="stylesheet" type="text/css" href="estilos/jquery.epiclock.css"/>
    <link media="screen" rel="stylesheet" type="text/css" href="estilos/epiclock.retro-countdown.css"/>
     <script src="codigo/comun.js"></script>
	 <script src="codigo/common.js"></script>
	 <script src="codigo/eventos.js"></script>
	 <script src="codigo/noback.js"></script>
	 <script src="codigo/jQuery1.4.2.js"></script>
	 <script src="codigo/jquery.tools.min.js"></script>
     <script src="codigo/jquery.dateformat.min.js"></script>
     <script src="codigo/jquery.epiclock.min.js"></script>
     <script src="codigo/epiclock.retro-countdown.min.js"></script>
<script   >
//<![CDATA[
           
//Cargo dos variables:
//segundos que lo utilizo para guardar cada cierto tiempo.
//segundosActuales que lo utilizo para llevar un contador actualizado cada segundo de el tiempo
var segundos = <?php echo $segundos?>;
var segundosActuales =<?php echo $segundos?>;

function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
        lon();
		return true;
	}else	return false;
}

function ocultomuestro(atras, siguiente, fin){

	var displayAtras ="";
	var displaySiguiente ="";
	var displayFin ="";
	var displayBusca ="";
	
	if(atras == 0){
		displayAtras = 'none';
	}else{
		if(atras == 1){
			displayAtras = 'block';
		}
	}
	if(siguiente == 0){
		displaySiguiente = 'none';
	}else{
		if(siguiente == 1){
			displaySiguiente = 'block';
		}
	}
	if(fin == 0){
		displayFin = 'none';
		$.epiclock('#countdown-retro').toggle();
	}else{
		if(fin == 1){
			$.epiclock('#countdown-retro').toggle();
			displayFin = 'block';
		}
	}
	<?php if (empty($sDisplay)){?>
	document.getElementById("lequedan").style.display = displayFin;
	<?php }?>
	document.getElementById("divatras").style.display = displayAtras;
	document.getElementById("divsiguiente").style.display = displaySiguiente;
	document.getElementById("divfin").style.display = displayFin;
	
}

function siguiente(){
	lon();
	var f = document.forms[0];
	
	var orden ="";
	if(f.fOrden.value=="" || f.fOrden.value== 1){
		orden = parseInt(1) + parseInt(<?php echo $sPreguntasPorPagina?>);
	}else{
		orden = parseInt(f.fOrden.value) + parseInt(<?php echo $sPreguntasPorPagina?>);
	}
	f.fOrden.value=orden;
	//f.fPaginaSel.value = parseInt(f.fPaginaSel.value) + parseInt(1);

	var paginacargada = "cuerpoejemplo.php";
	$("div#cuerpoejemplos").show().load(paginacargada,{
		fOrden:orden,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>" 
	},function(){
		loff();		
	}).fadeIn("slow");
}
function anterior(){
	lon();
	var f = document.forms[0];
	var orden ="";

	if(f.fOrden.value=="" || f.fOrden.value== 1){
		orden = 1;
	}else{
		orden = parseInt(f.fOrden.value) - parseInt(<?php echo $sPreguntasPorPagina?>);
	}

	f.fPaginaSel.value = parseInt(f.fPaginaSel.value) - parseInt(1);
	f.fOrden.value=orden;

	var paginacargada = "cuerpoejemplo.php";
	$("div#cuerpoejemplos").show().load(paginacargada,{
		fOrden:orden,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken();?>" 
	},function(){
		loff();		
	}).fadeIn("slow");
}
function cargapregunta(){
	lon();
	var f = document.forms[0];

	var paginacargada = "cuerpoejemplo.php";
	$("div#cuerpoejemplos").load(paginacargada,{
		fPaginas:<?php echo $iPaginas?>,
		fOrden:"1",
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>" 
	},function(){
		loff();		
	}).fadeIn("slow");
}

function comenzar(){
	lon();
	var f = document.forms[0];
	f.fEjemplosLeidos.value='1';
	f.submit(); 
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
	<style>
	
	/* black version of the overlay. simply uses a different background image */
	div.apple_overlay.black {
		background-image:url(graf/sp.gif);	
		color:#000;
	}
	
	div.apple_overlay h2 {
		margin:10px 0 -9px 0;
		font-weight:bold;
		font-size:14px;
	}
	
	div.black h2 {
		color:#000;
	}
	
	#triggers {
		margin-top:10px;
		/*text-align:center;*/
	}
	
	#triggers img {
		background-color:#fff;
		padding:2px;
		border:1px solid #ccc;
		margin:2px 5px;
		cursor:pointer;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
	}
	</style>
</head>
<body onload="<?php echo ($saltaAPrueba)?"comenzar()" : "";?>;cargapregunta();" >
<form name="form" method="post" action="Prueba.php">
<?php
$HELP="xx";
?>
<div id="contenedor">
	<div id="lequedan" class="negro" style="<?php echo $sDisplay;?>position:absolute;top:75px;left:60%;"><?php echo constant("STR_LE_QUEDAN");?>:&nbsp;
		<span id="countdown-retro" class="negrob"></span>
	</div>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'cabeceraejemplos.php');?>
	<div id="envoltura">
		<div id="contenido">
			<div style="width: 95%">		
			<?php
			if ($cPruebas->getCabecera() != ""){ 
			?> 			
				<div id="cabeceraprueba" style="width:100%;background: #FFFFFF;">
					<img src="<?php echo constant("DIR_WS_GESTOR") . $cPruebas->getCabecera();?>" />
				</div>
		<?php
			}?>
				<div id="cuerpoejemplos" style="width:100%;background: #FFFFFF;">
					
				</div>
				
				<div id="pieprueba" style="width:100%;background: #FFFFFF;" align="right">
					
					<table cellspacing="5" align="center" border="0"  width="100%" >	
						<tr>
							<?php //echo "páginas: " . intval($iPaginas);
							//Comprobamos la página que llega de forma inicial para la 
							//carga de los botones de navegación de la prueba.
							if(isset($_POST['fOrden'])){
								if($_POST['fOrden'] !="" && $_POST['fOrden'] !=1){
									if($_POST['fOrden'] == $iPaginas){
										$displayAtras="block";	
										$displaySig="none";
										$displayFin="block";
									}else{
										$displayAtras="block";	
										$displaySig="block";
										$displayFin="none";
									}
								}else{
									$displayAtras="none";	
									$displaySig="block";
									$displayFin="none";
								}
							}else{
								if($iPaginas==1){
									$displayAtras="none";	
									$displaySig="none";
									$displayFin="block";	
								}else{
									
									$displayAtras="none";	
									$displaySig="block";
									$displayFin="none";
								}				
							}?>
							<td>
								<div id="divatras" style="display: <?php echo $displayAtras?>;">
									<table>	
										<tr>
											<td>
												<input type="button" class="botones" name="fAtras" value="<?php echo constant("STR_ANTERIOR");?>" onclick="javascript:anterior();"/>
											</td>
										</tr>
									</table>
									
								</div>
							</td>
							<td>
								<div id="divsiguiente" style="display: <?php echo $displaySig?>;">
									<table align="right">
										<tr>
											<td align="right" valign="top" style="padding-top: 30px;">
												<input type="button" class="botones" name="fSigue" value="<?php echo constant("STR_CONTINUAR");?>" onclick="javascript:siguiente();"/>
											</td>
										</tr>
									</table>
								</div>
							</td>
							<td>
								<div id="divfin" style="display: <?php echo $displayFin?>;">
									<table align="right">
										<tr>
											<td align="right" valign="top" style="padding-top: 30px;">
												<input type="button" class="botones" name="fSigue" value="<?php echo constant("STR_COMENZAR");?>" onclick="javascript:comenzar();"/>
											</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
						
				</div>
		    </div>
		    <div class="apple_overlay" id="ayuda" >
				<div class="details">
					<br />
					<h2><?php echo strtoupper(constant("STR_AYUDA") . " " . $cPruebas->getNombre()) . " " . $cPruebas->getDescripcion();?> </h2>
					<br />
					<p>
						<?php echo $cEjemplos_ayudas->getTexto()?>
					</p>
				</div>
			</div>


			<!-- make all links with the 'rel' attribute open overlays -->
			<script>
			
			$(function() {
				$("#triggers a[rel]").overlay({effect: 'apple'});
			});
			
<?php		if($cPruebas->getDuracion2() > 0)
			{?>
			
				$(function (){
					$('#countdown-retro').epiclock({mode: $.epiclock.modes.timer, offset: {seconds: <?php echo $segundos?>}, format: 'x{h} i{m} s{s}'}).bind('timer', function ()
	                {
	                    comenzar();
	                });
				 });
	
				//Mando guardar cada 10 segspara controlar las posibles caídas.
				
				setInterval("seteaTime()",5000);
	
				function seteaTime(idItem,idOpcion,orden,nOpciones){
					segundos= segundos-5;
					var f = document.forms[0];
					var i=0;
				}
				//control actualizado del tiempo.
				setInterval("refresca()",1000);
	            function refresca(){
	            	segundosActuales = segundosActuales-1;
	            }
<?php		}?>			
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
<input type="hidden" name="fOrden" value="1" />
<input type="hidden" name="fBuscaPrimera" value="" />
<input type="hidden" name="fFinalizar" value="" />
<input type="hidden" name="fPosicionInicioLista" value="0" />
<input type="hidden" name="fPaginaSel" value="1" />
<input type="hidden" name="sTKCandidatos" value="<?php echo $cCandidato->getToken();?>" />
<input type="hidden" name="fEjemplosLeidos" value="" />
</form>

</body>
</html>