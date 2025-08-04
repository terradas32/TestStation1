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
	require_once(constant("DIR_WS_COM") . "Items/ItemsDB.php");
	require_once(constant("DIR_WS_COM") . "Items/Items.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once(constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
	require_once(constant("DIR_WS_COM") . "Opciones/Opciones.php");
	require_once(constant("DIR_WS_COM") . "Pruebas_ayudas/Pruebas_ayudasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas_ayudas/Pruebas_ayudas.php");
	require_once(constant("DIR_WS_COM") . "Combo.php");
	

include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");


	
	$cPruebas_ayudasDB = new Pruebas_ayudasDB($conn);
	$cRespPruebasDB = new Respuestas_pruebasDB($conn);
    
    $cCandidato = new Candidatos();
    $cCandidato  = $_cEntidadCandidatoTK;
    
    $cProceso_pruebas = new Proceso_pruebas();
    $cProceso_pruebasDB = new Proceso_pruebasDB($conn);
    $cPruebasDB = new PruebasDB($conn);
    $cItemsDB = new ItemsDB($conn);
    
    $cPruebas = new Pruebas();
    
    $cPruebas->setIdPrueba($_POST['fIdPrueba']);
	$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	
	$cPruebas = $cPruebasDB->readEntidad($cPruebas);
    
	$sPreguntasPorPagina = $cPruebas->getPreguntasPorPagina();
	$sEstiloOpciones = $cPruebas->getEstiloOpciones();
	
	$aPreguntasPorPagina = explode("-", $sPreguntasPorPagina); 
	$iPreguntasPorPagina = count($aPreguntasPorPagina);
//	echo  $iPreguntasPorPagina;exit;
	$bMultiPagina = false;
	if ($iPreguntasPorPagina > 1){
		//Quiere decir que le llegan en formato 5-6-5-5-5-4-6-4 por ejemplo las preguntas por página
		$bMultiPagina = true;
	}else{
		if($sPreguntasPorPagina < 1){
			$sPreguntasPorPagina = 1;
		}
	}
	 
	$iLineas = $sPreguntasPorPagina;
    if (empty($_POST["fPaginaSel"])){
    	$_POST["fPaginaSel"]=1;
    }
	if ($bMultiPagina){
		$iLineas = $aPreguntasPorPagina[$_POST["fPaginaSel"]-1];
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
    
    if($cRespPruebas->getFinalizado()==""){
    	$cRespPruebas->setFinalizado("0");
    	$cRespPruebas->setLeidoEjemplos("0");
    	$cRespPruebas->setLeidoInstrucciones("0");
    	
    	$cRespPruebasDB->insertar($cRespPruebas);
    }
    
    $leidoIns=false;
	$leidoEjemplos=false;
    
	if(isset($_POST['fEjemplosLeidos']) && $_POST['fEjemplosLeidos']!=""){
    	$cRespPruebas->setLeidoEjemplos("1");
    	$cRespPruebasDB->modificar($cRespPruebas);
    }else{
	    
	    if($cRespPruebas->getLeidoInstrucciones()== 0){
	    	$leidoIns=true;
	    }else{
	    	if($cRespPruebas->getLeidoEjemplos() == 0){
	    		$leidoEjemplos=true;
	    	}
	    }
    }
    $sqlProcPruebas = $cProceso_pruebasDB->readLista($cProceso_pruebas);
    $listaProcesosPruebas = $conn->Execute($sqlProcPruebas);
    
    $cItemListar = new Items();
    $cItemListar->setIdPrueba($_POST['fIdPrueba']);
    $cItemListar->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    $cItemListar->setOrderBy("orden");
    $cItemListar->setOrder("ASC");
    
    $sqlItems = $cItemsDB->readLista($cItemListar);
    $listaItems = $conn->Execute($sqlItems);
    
    $iTamaniolistaItems = $listaItems->recordCount();
    
    $comboAREAS			= new Combo($conn,"fIdArea","idArea","nombre","Descripcion","areas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboEDADES		= new Combo($conn,"fIdEdad","idEdad","nombre","Descripcion","edades","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboNIVELES		= new Combo($conn,"fIdNivel","idNivel","nombre","Descripcion","nivelesjerarquicos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboFORMACIONES 	= new Combo($conn,"fIdFormacion","idFormacion","nombre","Descripcion","formaciones","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboSEXOS			= new Combo($conn,"fIdSexo","idSexo","nombre","Descripcion","sexos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    
    if ($bMultiPagina){
    	$iPaginas = $iPreguntasPorPagina;	//Contador del array Multipágina
    }else{
    	$iPaginas = $iTamaniolistaItems / $sPreguntasPorPagina;
    	if($iTamaniolistaItems % $sPreguntasPorPagina !=0){
			$iPaginas = intval($iPaginas) + 1;
		}
    }
										 
	$b7=false;
	$bBtnAtrasMostrar=true;
	$bBtnBuscarPrimeraSinResponder=true;
	$sStyleMostrarPreguntas = '';
	$sValidarPantalla = '';
	
	//1 -->360º
	//2 -->Aptitudes
	//3 -->Competencias
	//4 -->Estilo de Aprendizaje
	//5 -->Inglés
	//9 -->Intereses Profesionales
	//6 -->Motivaciones
	//7 -->Personalidad
	//8 -->Varias
//	echo $cPruebas->getIdTIpoPrueba();
	switch ($cPruebas->getIdTIpoPrueba())
	{
		case "2":	//2 -->Aptitudes
		case "5":	//5 -->Ingles ELT
	    	$sStyleMostrarPreguntas = 'style="display:none;"';
			break;
		case "3":	//3 -->Competencias
	    	$sStyleMostrarPreguntas = 'style="display:none;"';
	    	$bBtnAtrasMostrar=false;
	    	$bBtnBuscarPrimeraSinResponder=false;
			$sValidarPantalla = "valida1Opcion('" . $sPreguntasPorPagina . "');";
			break;
			break;
		case "6":	//6 -->Motivaciones
	    	$sStyleMostrarPreguntas = 'style="display:none;"';
	    	$bBtnAtrasMostrar=false;
	    	$bBtnBuscarPrimeraSinResponder=false;
	    	 
	    	$cOpciones = new Opciones();
			$cOpcionesDB = new OpcionesDB($conn);
			$cOpciones->setIdPrueba($_POST['fIdPrueba']);
			$cOpciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			$cOpciones->setIdItem(1);
			$cOpciones->setBajaLog("0");
			$sqlOpciones = $cOpcionesDB->readLista($cOpciones);
			$listaOpciones = $conn->Execute($sqlOpciones);
			$i_Opciones = $listaOpciones->recordCount();
			$sOpciones = '';
			while(!$listaOpciones->EOF){
				$sOpciones .= ',' . $listaOpciones->fields['descripcion'];
				$listaOpciones->MoveNext();
			}
			if (!empty($sOpciones)){
				$sOpciones = substr($sOpciones,1);
			}
			if ($bMultiPagina){
				$sPxP = "document.forms[0].fPaginaSel.value"; 
			}else{
				$sPxP = "'" . $sPreguntasPorPagina . "'";
			}
			
			$sValidarPantalla = "mejorpeor(" . $sPxP . ", '" . $sOpciones . "');";
			break;
		case "7":	//7 -->Personalidad
	    	$b7=true;
	    	$sStyleMostrarPreguntas = 'style="display:none;"';
	    	$bBtnAtrasMostrar=false;
	    	$bBtnBuscarPrimeraSinResponder=false;
	    	 
	    	$cOpciones = new Opciones();
			$cOpcionesDB = new OpcionesDB($conn);
			$cOpciones->setIdPrueba($_POST['fIdPrueba']);
			$cOpciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			$cOpciones->setIdItem(1);
			$cOpciones->setBajaLog("0");
			$sqlOpciones = $cOpcionesDB->readLista($cOpciones);
			$listaOpciones = $conn->Execute($sqlOpciones);
			$i_Opciones = $listaOpciones->recordCount();
			$sOpciones = '';
			while(!$listaOpciones->EOF){
				$sOpciones .= ',' . $listaOpciones->fields['descripcion'];
				$listaOpciones->MoveNext();
			}
			if (!empty($sOpciones)){
				$sOpciones = substr($sOpciones,1);
			}
			if ($bMultiPagina){
				$sPxP = "document.forms[0].fPaginaSel.value"; 
			}else{
				$sPxP = "'" . $sPreguntasPorPagina . "'";
			}
			if ($cPruebas->getIdPrueba() == "11" || 
				$cPruebas->getIdPrueba() == "5" ||
				$cPruebas->getIdPrueba() == "22"){
				$sValidarPantalla = "validaMejor(" . $sPxP . ", '" . $sOpciones . "');";
			}else{
				$sValidarPantalla = "mejorpeor(" . $sPxP . ", '" . $sOpciones . "');";	
			}
			break;
		case "9":	//9 -->Intereses Profesionales CIP / SOP
	    	$sStyleMostrarPreguntas = 'style="display:none;"';
	    	$bBtnAtrasMostrar=false;
	    	$bBtnBuscarPrimeraSinResponder=false;
			$sValidarPantalla = "validaTipo9('" . $sPreguntasPorPagina . "');";
			break;
		case "8":	//8 -->Varias
	    	$sStyleMostrarPreguntas = 'style="display:none;"';
			break;
		default:
			break;
	} // end switch
	
    $cPruebas_ayudas = new Pruebas_ayudas();
    $cPruebas_ayudas->setIdPrueba($cPruebas->getIdPrueba());
    $cPruebas_ayudas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    $cPruebas_ayudas->setIdAyuda("1");
    $cPruebas_ayudas = $cPruebas_ayudasDB->readEntidad($cPruebas_ayudas);
    
    $sTime="";
    if($cRespPruebas->getMinutos_test() !="" && $cRespPruebas->getSegundos_test() !=""){
    	$sTime = ($cRespPruebas->getMinutos_test()*60) + $cRespPruebas->getSegundos_test();
    }else{
    	$sTime=0;
    }
    $sDisplay= '';
    //Si la prueba no tiene tiempo,
    //ahora resulta que hay que saber lo que a tardado,
    //Se le asigna 60min que es el tiempo máximo ya que el
    //Cronometro no tiene horas
    //Le ponemos al cronometro horas y le establecemos 8h
    if($cPruebas->getDuracion() == 0){
		$cPruebas->setDuracion(480);	
		$sDisplay= 'display:none;';
	}
    $segundos=$cPruebas->getDuracion()*60 - $sTime;
    
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
<?php
// bAnt nos indica en pruebas que permite blancos si fue pulsada ya la opción, en ese caso
// No se inserta, solo se borra.
?>
function abrirVentana(bImg, file){
	var f = document.forms[0];
	var paginacargada = "Audio.php";
	$("div#audio").load(paginacargada,{
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fIdEmpresa: f.fIdEmpresa.value,
		fIdProceso: f.fIdProceso.value,
		fIdCandidato: f.fIdCandidato.value,
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>" });
	preurl = "view.php?bImg=" + bImg + "&File=" + file;
	prename = "File";
	var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
	miv.focus();
}
var bAnt="";
           
function rediInst(){
	document.forms[0].action = 'instrucciones.php';
	document.forms[0].submit();
}
function rediEjemplos(){
	document.forms[0].action = 'ejemplos.php';
	document.forms[0].submit();
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo constant("STR_EDAD");?>:",f.fIdEdad.value,11,true);
	msg +=vString("<?php echo constant("STR_SEXO");?>:",f.fIdSexo.value,11,true);
	msg +=vString("<?php echo constant("STR_NIVEL_ACTUAL");?>:",f.fIdNivel.value,255,true);
	msg +=vString("<?php echo constant("STR_AREA");?>:",f.fIdArea.value,11,true);
	msg +=vString("<?php echo constant("STR_FORMACION_ACADEMICA");?>:",f.fIdFormacion.value,255,true);
if (msg != "") {
	alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
	return false;
}else return true;
}
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
	}else{
		if(fin == 1){
			displayFin = 'block';
		}
	}


	document.getElementById("divatras").style.display = displayAtras;
	document.getElementById("divsiguiente").style.display = displaySiguiente;
	document.getElementById("divfin").style.display = displayFin;
	
}
<?php 
if ($bMultiPagina){
	echo "var aPreguntasPorPagina=new Array();\n\t";
	$i=0;
	while($i < $iPaginas){
		echo "aPreguntasPorPagina[" . $i . "]=" . $aPreguntasPorPagina[$i] . ";\n\t";
		$i++;
	}	
}
?>
function veapregunta(){
	lon();
	var f = document.forms[0];
	 
	f.fOrden.value = f.fPreguntas.value;
	f.fPaginaSel.value = parseInt(f.fPreguntas.selectedIndex) + parseInt(1);
	var paginacargada = "cuerpoprueba.php";
	$("div#cuerpoprueba").show().load(paginacargada,{
		fOrden:f.fPreguntas.value,
		fPaginaSel:f.fPaginaSel.value,
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
function siguiente(){
	lon();
	var f = document.forms[0];
	var orden ="";
	if(f.fOrden.value=="" || f.fOrden.value== 1){
		orden = parseInt(1) + parseInt(<?php echo $iLineas;?>);
	}else{
	<?php 
	if ($bMultiPagina){
		echo "var iAnterior=0;\n\t";
		echo "for(var i=0; i < f.fPaginaSel.value; i++){\n\t";
		echo "		iAnterior = iAnterior + aPreguntasPorPagina[i];\n\t";
		echo "}\n\t";
	?>
		orden = parseInt(iAnterior+1);
	<?php
	}else{
	?>
		orden = parseInt(f.fOrden.value) + parseInt(<?php echo $iLineas;?>);
	<?php
	 } 
	?>
	}
	f.fOrden.value=orden;
	f.fPaginaSel.value = parseInt(f.fPaginaSel.value) + parseInt(1);
	f.fPreguntas.selectedIndex =  parseInt(f.fPaginaSel.value)- parseInt(1);
	var paginacargada = "cuerpoprueba.php";
	$("div#cuerpoprueba").show().load(paginacargada,{
		fOrden:orden,
		fPaginaSel:f.fPaginaSel.value,
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
		<?php 
		if ($bMultiPagina){
			echo "var iAnterior=0;\n\t";
			echo "var sPS = (parseInt(f.fPaginaSel.value)-1);";
			echo "var resta = 0;";
			echo "for(var i=0; i < f.fPaginaSel.value; i++){\n\t";
			//echo "alert(aPreguntasPorPagina[i]);";
			echo "	if (i == sPS){\n\t";
			//echo "		alert(f.fPaginaSel.value);";
			echo "		resta= aPreguntasPorPagina[i-1];";
			echo "		break;\n\t";
			echo "	}else{\n\t";
			echo "		iAnterior = iAnterior + aPreguntasPorPagina[i];\n\t";
			echo "	}\n\t";
			echo "}\n\t";
		?>
			//alert(f.fOrden.value);
			//alert(resta);
			orden = parseInt(f.fOrden.value) - parseInt(resta);
		<?php
		}else{
		?>
			orden = parseInt(f.fOrden.value) - parseInt(<?php echo $iLineas;?>);
		<?php
		 } 
		?>
		
	}

	f.fPaginaSel.value = parseInt(f.fPaginaSel.value) - parseInt(1);
	f.fPreguntas.selectedIndex =  parseInt(f.fPaginaSel.value)- parseInt(1);
	f.fOrden.value=orden;

	var paginacargada = "cuerpoprueba.php";
//	alert(f.fPaginaSel.value);
	$("div#cuerpoprueba").show().load(paginacargada,{
		fOrden:orden,
		fPaginaSel:f.fPaginaSel.value,
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
function cargapregunta(){
	lon();
	var f = document.forms[0];

	var paginacargada = "cuerpoprueba.php";
	$("div#cuerpoprueba").load(paginacargada,{
		fPaginas:<?php echo $iPaginas?>,
		fOrden:"1",
		fPaginaSel:f.fPaginaSel.value,
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

function buscaprimera(){
	lon();
	var f = document.forms[0];

	var paginacargada = "cuerpoprueba.php";
	$("div#cuerpoprueba").show().load(paginacargada,{
		fBuscaPrimera:"1",
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

function guardarespuesta(idItem,idOpcion,orden,nOpciones){
	lon();
	var f = document.forms[0];
	var i=0;
	var element = document.getElementsByName("fIdOpcion"+idItem);
	for(i=0;i<nOpciones;i++){
		element[i].disabled=true;
	}
	var paginacargada = "guardarespuesta.php";
	$("div#guardarespuesta").load(paginacargada,{
		fOrden:"1",
		fPaginaSel:f.fPaginaSel.value,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		fIdItem:idItem,
		fIdOpcion:idOpcion,
		forden:orden,
		fnOpciones:nOpciones,
		fNOInsertatOpcion:bAnt,
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>" 
	},function(){
		loff();		
	}).fadeIn("slow");
	
}

function permiteBlanco(obj){
	if (bAnt == true){
		obj.checked=false;
	}
}
function setFocus(obj){
	bAnt = obj.checked;
}
function guardarespuestatipo6(idItem,idOpcion,orden,nOpciones,obj,inicio,fin,sOpciones){
	lon();
	var f = document.forms[0];
	for (i=0; i < f.elements.length; i++){
		if (f.elements[i].type == "checkbox"){
			if (f.elements[i].checked){
				f.elements[i].disabled=false;
			}
		}
	}

	var paginacargada = "guardarespuestatipo6.php";
	$("div#guardarespuesta").load(paginacargada,{
		fOrden:"1",
		fPaginaSel:f.fPaginaSel.value,
		fInicio:inicio,
		fFin:fin,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		fIdItem:idItem,
		fIdOpcion:idOpcion,
		forden:orden,
		fnOpciones:nOpciones,
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>" 
	},function(){
		var nCheckbox=0;
		var iMaxCheckbox=2;
		for (i=0; i < f.elements.length; i++){
			if (f.elements[i].type == "checkbox"){
				if (f.elements[i].checked){
					nCheckbox++;
					f.elements[i].disabled=true;
				}
			}
		}
		if (nCheckbox == iMaxCheckbox){
			//Pongo todos los check a disabled
			for (i=0; i < f.elements.length; i++){
				if (f.elements[i].type == "checkbox"){
					f.elements[i].disabled=true;
				}
			}
			if (f.fPaginaSel.value==48){
				terminar6();
			}else{
				siguiente();
			}
		}

		loff();		
	}).fadeIn("slow");
}

function guardarespuestatipo7(idItem,idOpcion,orden,nOpciones,obj,inicio,fin,sOpciones){
	lon();
	var f = document.forms[0];
	var i=0;
	var aOpciones = new Array();
	if (sOpciones != ""){
		aOpciones = sOpciones.split(",");
	}
	var aElements= new Array();
	for(i=0; i < aOpciones.length;i++){
		aElements[i] = document.getElementsByName("fIdOpcion" + aOpciones[i]);
	}
	for(i=0; i < nOpciones; i++){
		for(j=0; j < aElements.length;j++){
			aElements[j][i].disabled=true;
		}
	}
	for(i=0; i < nOpciones; i++){
		for(j=0; j < aElements.length;j++){
			if(aElements[j][i].checked){
				for(k=0; k < aElements.length;k++){
					aElements[k][i].checked=false;
				}
				obj.checked = true;
				aElements[j][i].checked = true;
			}
		}
	}

	var paginacargada = "guardarespuestatipo7.php";
	$("div#guardarespuesta").load(paginacargada,{
		fOrden:"1",
		fPaginaSel:f.fPaginaSel.value,
		fInicio:inicio,
		fFin:fin,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		fIdItem:idItem,
		fIdOpcion:idOpcion,
		forden:orden,
		fnOpciones:nOpciones,
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>" 
	},function(){
		loff();		
	}).fadeIn("slow");
}

function terminar(){
	var f = document.forms[0];
	var sMsg = "<?php echo constant("MSG_ESTAS_SEGURO_DE_FINALIZAR_LA_PRUEBA");?>"
	if (confirm(sMsg)){
		lon();
		f.fFinalizar.value="1";
		f.fTiempoFinal.value=segundosActuales;
		f.action = "pruebas.php";
		lon();
		f.submit(); 
	}
}
function terminar6(){
	var f = document.forms[0];
	lon();
	f.fFinalizar.value="1";
	f.fTiempoFinal.value=segundosActuales;
	f.action = "pruebas.php";
	f.submit(); 

}
function terminarPorTiempo(){
	lon();
	var f = document.forms[0];
	f.fFinalizar.value="1";
	f.fFinalizarPorTiempo.value="1";
	f.fTiempoFinal.value=segundosActuales;
	f.action = "pruebas.php";
	f.submit(); 
}
function mejorpeor(nOpciones, sOpciones){
	var f = document.forms[0];
	<?php
	// Si es la prueba 12 (cel16), es como prisma hasta la página 32
	// a partir de la 33 sólo se pinta mejor
	// En nOpciones nos pasa el número de página 
	if ($bMultiPagina){
		if ($_POST['fIdPrueba'] == 12)
		{
			//CEL16
	?>
		nOpciones = aPreguntasPorPagina[parseInt(nOpciones)-1];
		if (nOpciones < 3){
			sOpciones = sOpciones.substring(0,sOpciones.lastIndexOf(","));
			<?php 
			//con return Rompemos el flujo para que no siga haciendo el resto de la función
			?>
			return validaMejor(nOpciones, sOpciones);
		}
	//	alert(nOpciones);
	<?php
		 } 
	}
	?>
	var i=0;
	var aOpciones = new Array();
	if (sOpciones != ""){
		aOpciones = sOpciones.split(",");
	}
	var aElements= new Array();
	for(i=0; i < aOpciones.length;i++){
		aElements[i] = document.getElementsByName("fIdOpcion" + aOpciones[i]);
	}
	
	var a1deCada= new Array();
	for(i=0; i < nOpciones; i++){
		for(j=0; j < aElements.length;j++){
			if(aElements[j][i].checked){
				a1deCada[i] = 1;
				break;
			}else{
				a1deCada[i] = 0;
			}
		}
	}
	if (eval(a1deCada.toString().replace(/,/g,"+")) < aOpciones.length){
		alert("<?php echo constant('STR_DEBE_SELECCIONAR_UNA_OPCION_DE_CADA_UNA_DE_LAS_SIGUIENTES')?>:\n\t" + strip_tags(aOpciones.toString().replace(/,/g,", ")) + ".");
	}else{
		siguiente();
	}
}
function strip_tags(input, allowed)
{
	allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
	commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
	return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	});
}
function validaMejor(nOpciones, sOpcion){
	var f = document.forms[0];
	var i=0;
	var aElements= document.getElementsByName("fIdOpcion" + sOpcion);
	
	var bChecked= false;
	for(i=0; i < aElements.length; i++){
		if(aElements[i].checked){
			bChecked= true;
			break;
		}else{
			bChecked= false;
		}
	}
	if (!bChecked){
		alert("<?php echo constant('SLC_OPCION')?>:\n\t" + sOpcion + ".");
	}else{
		siguiente();
	}
	return false;
}
Array.prototype.inArray = function (value)
{
	var i;
	for (i=0; i < this.length; i++) {
		if (this[i] === value) {
			return true;
		}
	}
	return false;
};
function valida1Opcion(nOpciones){
	
	var f = document.forms[0];
	var i=0;
	var aOpciones = new Array();
	var sOpciones = "";
	var frm = document.getElementById("form");
	var iCont=0;
	for (i=0; i < frm.elements.length; i++){
		if (frm.elements[i].type == "radio"){
			if (!aOpciones.inArray(frm.elements[i].name)) {
				aOpciones[iCont] = frm.elements[i].name;
				iCont++;
			}
		}
	}
	var aElements= new Array();
	for(i=0; i < aOpciones.length;i++){
		aElements[i] = document.getElementsByName(aOpciones[i]);
	}
	var iContador = 0;
	for(i=0; i < aElements.length; i++){
		aObjOpt = aElements[i];
		for(j=0; j < aObjOpt.length; j++ ){
			if (aObjOpt[j].checked){
				iContador++;
			}
		}
	}
	if (iContador < nOpciones){
		alert("<?php echo constant('SLC_OPCION')?>.");
	}else{
		siguiente();
	}
}

function validaTipo9(nOpciones){
	
	var f = document.forms[0];
	var i=0;
	var aOpciones = new Array();
	var sOpciones = "";
	var frm = document.getElementById("form");
	var iCont=0;
	for (i=0; i < frm.elements.length; i++){
		if (frm.elements[i].type == "radio"){
			if (!aOpciones.inArray(frm.elements[i].name)) {
				aOpciones[iCont] = frm.elements[i].name;
				iCont++;
			}
		}
	}
	var aElements= new Array();
	for(i=0; i < aOpciones.length;i++){
		aElements[i] = document.getElementsByName(aOpciones[i]);
	}
	var iContador = 0;
	for(i=0; i < aElements.length; i++){
		aObjOpt = aElements[i];
		for(j=0; j < aObjOpt.length; j++ ){
			if (aObjOpt[j].checked){
				iContador++;
			}
		}
	}
	if (iContador < nOpciones){
		alert("<?php echo constant('STR_DEBE_SELECCIONAR_UNA_OPCION_DE_CADA_PREGUNTA')?>.");
	}else{
		siguiente();
	}
}
onclick="if (document.getElementById('Mas').style.display == 'none'){document.getElementById('Mas').style.display='block';}else{document.getElementById('Mas').style.display='none';}"

//]]>
</script>
	<style type="text/css">
	
	/* black version of the overlay. simply uses a different background image */
	div.apple_overlay.black {
		background-image:url(<?php echo constant("HTTP_SERVER");?>estilos/images/transparent.png);		
		color:#fff;
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
<script   >
//<![CDATA[
function _body_onload(){	loff();	}
function _body_onunload(){	lon();	}
//]]>
</script>
</head>
<body onload="javascript:_body_onload();<?php echo ($leidoIns) ? "rediInst();" : "";?><?php echo ($leidoEjemplos)? "rediEjemplos();" : "";?>buscaprimera();" onunload="_body_onunload();">
<form name="form" id="form" method="post" action="Prueba.php">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0"  title="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" alt="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
<?php
$HELP="xx";
?>
<div id="contenedor">
	<div class="blanco" style="<?php echo $sDisplay;?>position:absolute;top:75px;left:60%;"><?php echo constant("STR_LE_QUEDAN");?>:&nbsp;
		<span id="countdown-retro" class="blancob"></span>
   		<span id="countdown-retro2" class="blancob"></span>
	</div>
<?php	include_once(constant("DIR_WS_INCLUDE") . 'cabecerapruebas.php');?>
	<div id="envoltura">
		<div id="contenido">
			<div style="width: 100%">
			<?php
			if ($cPruebas->getCabecera() != ""){ 
			?> 			
				<div id="cabeceraprueba">
					<img src="<?php echo constant("DIR_WS_GESTOR") . $cPruebas->getCabecera();?>" />
				</div>
		<?php
			}?>
				<div id="cuerpoprueba">
					
				</div>
				<div id="pieprueba" width="100%">
<?php 
			if ($bBtnBuscarPrimeraSinResponder){
?>
					<table cellspacing="0" border="0" width="95%" >
						<tr>
							<td valign="top" align="right">
								<div id="divbusca">
									<input type="button" class="botonesgrandes" name="fSigue" value="<?php echo constant("BUSCAR_PRIMERA_SIN_RESPONDER");?>" onclick="javascript:buscaprimera();" />
								</div>
							</td>
						</tr>
					</table>
<?php 
			} // end if
?>
					<table cellspacing="5" border="0" width="95%" >	
						<tr>
							<td valign="top" style="padding-top: 10px;">
								<select <?php echo $sStyleMostrarPreguntas; ?> name="fPreguntas" onchange="javascript:veapregunta();">
									<?php // Cargamos el combo de selección de pregunta. 
										
										if($iPaginas > 0){
											$i=0;
											$iOrden = 1;
											$iAnterior=0;
											while($i < $iPaginas){
												if($i==0){
													$iOrden = 1;
												}else{
													//Quiere decir que le llegan en formato 3-6-5-5-5-4-6-4 por ejemplo las preguntas por página
													if ($bMultiPagina){
														if ($bMultiPagina){
															if ($i > 1){
																$iAnterior += $aPreguntasPorPagina[$i-1];
															}else{
																$iAnterior = $aPreguntasPorPagina[$i-1]+1;
															}
														}
														$iOrden = $iAnterior;
													}else{
														$iOrden = $iOrden + $sPreguntasPorPagina;
													}
												}?>
											
												<option value="<?php echo $iOrden?>"><?php echo constant("IR_A_PAGINA");?> <?php echo $i+1 ?></option>
											
									<?php		$i++;
										}
									}?>
								</select>
								
							</td>
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
								$displayAtras="none";	
								$displaySig="block";
								$displayFin="none";	
							}?>
							<td valign="top" style="padding-top: 10px;">
								<div id="divatras" style="display: <?php echo $displayAtras?>;">
<?php								if($bBtnAtrasMostrar){?>
										<table>	
											<tr>
												<td>
													<input type="button" class="botones" name="fAtras" value="<?php echo constant("STR_ANTERIOR");?>" onclick="javascript:anterior();"/>
												</td>
											</tr>
										</table>
<?php								}?>
								</div>
							</td>
							<td >
								<div id="divsiguiente" style="display: <?php echo $displaySig?>;">
									<table align="right">	
										<tr>
											<td align="right" valign="top" style="padding-top: 10px;">
												<input type="button" class="botones" name="fSigue" value="<?php echo constant("STR_SIGUIENTE");?>" onclick="javascript:<?php echo (!empty($sValidarPantalla)) ? $sValidarPantalla : "siguiente();";?>" />
											</td>
											
										</tr>
									</table>
								</div>
							</td>
							<td>
								<div id="divfin" style="display: <?php echo $displayFin?>;">
									<table align="right">
										<tr>
											<td align="right" valign="top" style="padding-top: 10px;">
												<input type="button" class="botones" name="fSigue" value="<?php echo constant("FINALIZAR");?>" onclick="javascript:terminar();"/>
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
						<?php echo $cPruebas_ayudas->getTexto()?>&nbsp;
					</p>
				</div>
			</div>


			<!-- make all links with the 'rel' attribute open overlays -->
			<script   >
			
			$(function() {
				$("#triggers a[rel]").overlay();
			});
			
<?php		if($cPruebas->getDuracion() > 0)
			{?>
			
				$(function (){
					$('#countdown-retro').epiclock({mode: $.epiclock.modes.timer, offset: {seconds: <?php echo $segundos?>}, format: 'x{h} i{m} s{s}'}).bind('timer', function ()
	                {
						
	                    alert("<?php echo constant("TU_TIEMPO_SE_HA_AGOTADO");?>");
	                    terminarPorTiempo();
	                });
				 });
	
				//Mando guardar cada 10 segspara controlar las posibles caídas.
				
				setInterval("seteaTime()",5000);
	
				function seteaTime(idItem,idOpcion,orden,nOpciones){
					segundos= segundos-5;
					var f = document.forms[0];
					var i=0;
					
					var paginacargada = "guardatiempo.php";
					$("div#guardatiempo").load(paginacargada,{
						fOrden:"1",
						fIdEmpresa:f.fIdEmpresa.value,
						fIdProceso:f.fIdProceso.value,
						fIdCandidato:f.fIdCandidato.value,
						fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
						fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
						fSegundos:segundos,
						fTiempo:<?php echo $cPruebas->getDuracion()*60?>,
						sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>" 
					});
					
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
<div id="guardatiempo"></div>
<input type="hidden" name="fIdEmpresa" value="<?php echo $cCandidato->getIdEmpresa();?>" />
<input type="hidden" name="fIdProceso" value="<?php echo $cCandidato->getIdProceso();?>" />
<input type="hidden" name="fIdCandidato" value="<?php echo $cCandidato->getIdCandidato();?>" />
<input type="hidden" name="fMail" value="<?php echo $cCandidato->getMail();?>" />
<input type="hidden" name="fIdPrueba" value="<?php echo $_POST['fIdPrueba']?>" />
<input type="hidden" name="fCodIdiomaIso2" value="<?php echo $_POST['fCodIdiomaIso2']?>" />
<input type="hidden" name="fOrden" value="1" />
<input type="hidden" name="fBuscaPrimera" value="" />
<input type="hidden" name="fFinalizar" value="" />
<input type="hidden" name="fFinalizarPorTiempo" value="" />
<input type="hidden" name="fTiempoFinal" value="<?php echo $segundos?>" />
<input type="hidden" name="fTiempoPrueba" value="<?php echo $cPruebas->getDuracion()?>" />
<input type="hidden" name="fPosicionInicioLista" value="0" />
<input type="hidden" name="fPaginaSel" value="1" />
<input type="hidden" name="sTKCandidatos" value="<?php echo $cCandidato->getToken();?>" />
<input type="hidden" name="fLang" value="<?php echo $sLang;?>" />
</form>

</body>
</html>