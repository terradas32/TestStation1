<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


require_once('include/Configuracion.php');
include_once('include/Idiomas.php');

define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
include(constant("DIR_ADODB") . 'adodb.inc.php');
require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");

include_once ('include/conexion.php');

$cEntidadIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
$cEntidadIdiomas	= new Idiomas();  // Entidad
$cEntidadIdiomas->setActivoBack(1);
$sqlIdiomas = $cEntidadIdiomasDB->readLista($cEntidadIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);

$cEntidadDB	= new CandidatosDB($conn);  // Entidad DB
$cEntidad	= new Candidatos();  // Entidad
$strMensaje="";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, WI2.2 www.azulpomodoro.com" />
<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos-comunes.css" type="text/css" />
	<link rel="stylesheet" href="estilos/estilos-candidato.css" type="text/css" />
    <script language="javascript" type="text/javascript" src="codigo/eventos.js"></script>
<script language="javascript">
    function cerrar(){
		//setTimeout("self.close();",11000);
    }
    resizeTo(opener.document.documentElement.clientWidth-50,opener.document.documentElement.clientHeight);
    function setAceptMazda(){
      var f=document.forms[0];
      for (counter = 0; counter < f.fAceptaMazda.length; counter++){
        if (f.fAceptaMazda[counter].checked){
          window.opener.document.getElementById("fAceptaMazda").value = f.fAceptaMazda[counter].value;
        }
      }
    }
</script>
</head>
<body onload="">
<form name="form" method="post">
<div id="aviso">
    <div id="head" class="candidato">
        <div class="logo">
        <a href="index.php" title="Inicio"><img src="graf/logo.jpg" alt="#" title="#" /></a>
        </div><!-- Fin de logo -->
    <h1><?php echo constant("STR_CANDIDATO");?></h1>
    </div><!-- Fin de la cabecera -->
    <div id="cuerpo">
        <div id="aviso" class="acc_cand">
        <h2><?php echo constant("STR_AVISO_LEGAL");?></h2>
		<p>
			<?php echo constant("MSG_AVISO_LEGAL_P1");?>
		</p>
		<p>
			<?php echo constant("MSG_AVISO_LEGAL_P2");?>
		</p>
		<p>
			<?php echo constant("MSG_AVISO_LEGAL_P3");?>
		</p>
    <p>
			<?php echo constant("MSG_AVISO_LEGAL_P4");?>
		</p>
    <p>
			<?php echo constant("MSG_AVISO_LEGAL_P5");?>
		</p>
    <p>
      <input type="radio" name="fAceptaMazda" style="width: auto !important;" id="fAceptaMazda1" value="1" onclick="javascript:setAceptMazda();"><label for="fAceptaMazda1"><?php echo constant("STR_SI");?></label></input>&nbsp;<input type="radio" style="width: auto !important;" name="fAceptaMazda" id="fAceptaMazda0" value="0" onclick="javascript:setAceptMazda();"><label for="fAceptaMazda0"><?php echo constant("STR_NO");?></label></input>
    </p>
		<p>
      <br><br><br><br>
			<input name="fAceptar" type="button" onclick="javascript:self.close();" class="botones" value="<?php echo constant("STR_ACEPTAR");?>" />
		</p>
        </div><!-- Fin de accesos -->
    </div><!-- Fin de cuerpo -->
    <div id="pie">
        <!-- <p class="dweb"><a href="http://www.azulpomodoro.com" title="Diseño Web"><?php echo constant("STR_DISENO_DESARROLLO");?></a></p>
         --><p class="copy">Psicólogos Empresariales - <?php echo constant("STR_DERECHOS_RESERVADOS");?></p>
    </div><!-- Fin de pie -->
</div><!-- Fin de la pagina -->
</form>
</body>
</html>
