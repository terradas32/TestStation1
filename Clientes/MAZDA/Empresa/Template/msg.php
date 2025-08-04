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
</head>
<body>
<div id="pagina">
    <div id="head" class="candidato">
        <div class="logo">
        <a href="index.php" title="Inicio"><img src="graf/logo.jpg" alt="#" title="#" /></a>
        </div><!-- Fin de logo -->
    <h1><?php echo constant("STR_EMPRESA");?></h1>
    </div><!-- Fin de la cabecera -->
        <div id="banderas">
            <ul class="band_portada">
            </ul>
        </div><!-- Fin de las banderas -->
    <div id="cuerpo">
        <div id="accesos" class="acc_cand">
        <h2><?php echo constant("STR_CONTROL_ACCESO");?></h2>
           <p></p>
		<form method="post" name="error" action="<?php echo constant("HTTP_SERVER");?>" >
		<label><?php echo constant("ERR_FORM_ERROR");?>&nbsp;</label>
		<label>&nbsp;</label>
		<input name="fAceptar" type="submit" class="botones" value="<?php echo constant("STR_ACEPTAR");?>" />
        </form>
        <div id="error"><p><?php echo $strMensaje;?>&nbsp;</p></div>
        </div><!-- Fin de accesos -->
    </div><!-- Fin de cuerpo -->
    <div id="pie">
        <p class="dweb"><a href="http://www.azulpomodoro.com" title="Diseño Web"><?php echo constant("STR_DISENO_DESARROLLO");?></a></p>
        <p class="copy">Psicólogos Empresariales - <?php echo constant("STR_DERECHOS_RESERVADOS");?></p>
    </div><!-- Fin de pie -->
</div><!-- Fin de la pagina -->
</body>
</html>