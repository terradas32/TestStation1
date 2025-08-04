<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

ob_start();
	require_once('../include/Configuracion.php');
	include_once('../include/Idiomas.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, Wi2.22 www.negociainternet.com" />
<?php include('../include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="../estilos/estilos.css" type="text/css" />
</head>
<body >
<div id="contenedor">
	    <div id="head" class="empresa">
        <div class="logo">
        <a href="<?php echo constant("HTTP_SERVER_FRONT");?>index.php?fLang=<?php echo $sLang;?>" title="<?php echo constant("STR_INICIO");?>"><img src="graf/logo.jpg" alt="<?php echo constant("NOMBRE_EMPRESA");?> <?php echo constant("STR_INICIO");?>" title="<?php echo constant("NOMBRE_EMPRESA");?> <?php echo constant("STR_INICIO");?>" /></a>
        </div><!-- Fin de logo -->
    <h1><?php echo constant("STR_EMPRESA");?></h1>
    </div><!-- Fin de la cabecera -->
	<div id="envoltura">
		<div id="contenido">
			<div style="width: 100%"> 			
		     	 <div id="videotutoriales">
					<ul>
					<?php
					$the_array = Array();
					$handle = opendir('.');
					while (false !== ($file = readdir($handle))) {
					   if ($file != "." && $file != ".." && $file != "index.php") {
					   $the_array[] = $file;
					   }
					}
					closedir($handle);
					sort ($the_array);
					
					foreach($the_array as $val){
						$info = pathinfo($val);
						echo "<li><a class=\"enlacesidiomas\" target=\"_blank\" href=\"" . rawurlencode($val) . "\">" . utf8_encode($info['filename']) . "</a></li>";
					} 
					?>
					</ul>
				</div>
		    </div>
		</div>
	</div>
	<?php include_once('../' . constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>

</body>
</html>