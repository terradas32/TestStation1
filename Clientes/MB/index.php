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
require_once(constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
require_once(constant("DIR_WS_COM") . "Usuarios/Usuarios.php");

include_once ('include/conexion.php');
	
$cEntidadIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
$cEntidadIdiomas	= new Idiomas();  // Entidad
$cEntidadIdiomas->setActivoBack(1);
$sqlIdiomas = $cEntidadIdiomasDB->readLista($cEntidadIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, WI2.2 www.azulpomodoro.com" />
<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link media="screen" type="text/css" href="<?php echo constant("HTTP_SERVER");?>estilos/jquery.bxslider.css" rel="stylesheet" />
	<link rel="stylesheet" href="estilos/estilos-comunes.css" type="text/css" />
	<script type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
	<script type="text/javascript" src="codigo/jquery.tools.min.js"></script>
	<script type="text/javascript" src="<?php echo constant("HTTP_SERVER");?>codigo/jquery-1.11.0.min.js"></script>
	<?php include_once('include/codigos.php');?>
	<?php include_once 'include/codigosHome.php';?>	
<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href="estilos/ie.css" />
<![endif]-->
<script language="javascript" type="text/javascript">
//<![CDATA[
function cambiaIdioma(){
	var f=document.forms[0];
	location.replace("<?php echo constant("HTTP_SERVER") ;?>?fLang=" + f.fIdiomas.value);
}
//]]>
</script> 
</head>
<body>
<form name="form" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<div id="pagina">

    <div id="head" class="portada">
        <div class="logo">
            <a href="http://www.people-experts.com/" target="_blank" title="<?php echo constant("STR_INICIO");?>"><img src="graf/logo.jpg" alt="<?php echo constant("NOMBRE_EMPRESA");?> <?php echo constant("STR_INICIO");?>" title="<?php echo constant("NOMBRE_EMPRESA");?> <?php echo constant("STR_INICIO");?>" /></a>
        </div><!-- Fin de logo -->
        <div id="banderas">
<!--             <ul class="band_portada"> -->
            	<select style="visibility:hidden;" name="fIdiomas" class="<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>" onchange="javascript:cambiaIdioma();" >
            	<?php
		            while (!$listaIdiomas->EOF)
		            {
		            ?>
                  		<option title="<?php echo $listaIdiomas->fields['nombre'];?>" value="<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>" <?php echo ($sLang == $listaIdiomas->fields['codIdiomaIso2']) ? "selected=\"selected\"" : "";?> ><?php echo $listaIdiomas->fields['nombre'];?></option>
<!--                        <li class="<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>"><a href="<?php echo constant("HTTP_SERVER") ;?>?fLang=<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>" title="<?php echo $listaIdiomas->fields['nombre'];?>"><?php echo $listaIdiomas->fields['nombre'];?></a></li>-->
                    <?php
		              $listaIdiomas->MoveNext();
		            }
                ?>
            	</select>
<!--            </ul> -->
				<h1>Test Station</h1>
        </div><!-- Fin de las banderas -->
    	
    </div><!-- Fin de la cabecera -->
    <div class="cargaSlider">
				<p style="text-align: center;"><img alt="Cargando" src="<?php echo constant('HTTP_SERVER')?>estilos/images/ajax-loader.png" /></p>
				<script type="text/javascript">
					$(function(){
						cargaSlider();
					})
				</script>
	</div>
    <div id="cuerpo">
        <div id="accesos">
            <ul id="lista_accesos">
                <li class="candidatos"><a href="<?php echo constant("HTTP_SERVER");?>Candidato/?fLang=<?php echo $sLang;?>" title="<?php echo constant("STR_CANDIDATO") ;?>"><?php echo constant("STR_CANDIDATO") ;?></a></li>
                <li class="empresa"><a href="<?php echo constant("HTTP_SERVER");?>Empresa/?fLang=<?php echo $sLang;?>" title="<?php echo constant("STR_EMPRESA");?>"><?php echo constant("STR_EMPRESA") ;?></a></li>
<!--            <li class="administracion"><a href="<?php echo constant("HTTP_SERVER");?>Admin/?fLang=<?php echo $sLang;?>" title="<?php echo constant("STR_ADMINISTRACION") ;?>"><?php echo constant("STR_ADMINISTRACION") ;?></a></li> -->
            </ul>
        </div><!-- Fin de accesos -->
    </div><!-- Fin de cuerpo -->
<!--     
    <div id="banners">
		<a href="http://pe-station.com/" target="_blank" title="Todas las herramientas on-line para los proyectos de RRHH"><img border="0" src="<?php echo constant("HTTP_SERVER");?>graf/pe_banner_780x184_06F.gif" alt="Todas las herramientas on-line para los proyectos de RRHH" title="Todas las herramientas on-line para los proyectos de RRHH" /></a>
    </div>
 -->
    <div id="pie">
        <p class="dweb"><a href="http://www.azulpomodoro.com" target="_blank" title="<?php echo constant("STR_DISENO_DESARROLLO");?>"><?php echo constant("STR_DISENO_DESARROLLO");?></a></p>
        <p class="copy dweb"><a href="http://www.people-experts.com" target="_blank" title="Expertos en personas"><?php echo constant("NOMBRE_EMPRESA");?></a> - <?php echo constant("STR_DERECHOS_RESERVADOS");?></p>
    </div><!-- Fin de pie -->
</div><!-- Fin de la pagina -->
<input type="hidden" name="fLang" value="<?php echo $sLang;?>" />
</form>
</body>
</html>