<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, WI2.2 www.azulpomodoro.com" />
<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link media="screen" type="text/css" href="<?php echo constant("HTTPS_SERVER");?>estilos/jquery.bxslider.css" rel="stylesheet" />
	<link rel="stylesheet" href="estilos/estilos-comunes.css" type="text/css" />
	<link rel="stylesheet" href="estilos/estilos-candidato.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
    <script language="javascript" type="text/javascript" src="codigo/eventos.js"></script>
    <script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jquery.tools.min.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo constant("HTTPS_SERVER");?>codigo/jquery-1.11.0.min.js"></script>
	<?php include_once('include/codigos.php');?>
	<?php include_once 'include/codigosHome.php';?>
	<script type="text/javascript">
        function autoComplete()
        {
                var i = 0;
                // Recorres los elementos INPUT del documento
                for(var node; node = document.getElementsByTagName('input')[i]; i++){
                // Obtienes el tipo de INPUT
                var type = node.getAttribute('type').toLowerCase();
                // Si es del tipo TEXT deshabilitas su autocompletado
                if(type == 'text'){
                    node.setAttribute('autocomplete', 'off');
                }
            }
        }
    </script>
    <script language="javascript" type="text/javascript">
	//<![CDATA[
	function cambiaIdioma(){
		var f=document.forms[0];
		location.replace("<?php echo constant("HTTPS_SERVER") ;?>?fLang=" + f.fIdiomas.value);
	}
	//]]>
	</script>
</head>
<body onload="autoComplete();">
<div id="pagina">
    <div id="head" class="empresa">
        <div class="logo">
        <a href="<?php echo constant("HTTP_SERVER_FRONT");?>index.php?fLang=<?php echo $sLang;?>" title="<?php echo constant("STR_INICIO");?>">
            <!-- <img src="graf/logo.jpg" alt="<?php echo constant("NOMBRE_EMPRESA");?> <?php echo constant("STR_INICIO");?>" title="<?php echo constant("NOMBRE_EMPRESA");?> <?php echo constant("STR_INICIO");?>" /> -->
            <img src="https://test-station.com/Admin/graf/logo.jpg" alt="<?php echo constant("NOMBRE_EMPRESA");?> <?php echo constant("STR_INICIO");?>" title="<?php echo constant("NOMBRE_EMPRESA");?> <?php echo constant("STR_INICIO");?>" />
        </a>
        </div><!-- Fin de logo -->
    <h1><?php echo constant("STR_EMPRESA");?></h1>
    </div><!-- Fin de la cabecera -->
    <div id="cuerpo">
        <div id="accesos" class="acc_cand">
			<div class="cargaSlider">
				<p style="text-align: center;"><img alt="Cargando" src="<?php echo constant('HTTPS_SERVER')?>estilos/images/ajax-loader.png" /></p>
				<script type="text/javascript">
					$(function(){
						cargaSlider();
					})
				</script>
			</div>
        <h2><?php echo constant("STR_CONTROL_ACCESO");?></h2>
           <p><?php echo constant("STR_TEXTO_ACCESO");?></p>
        <form method="post" name="login" action="<?php echo $_SERVER['PHP_SELF'];?>" >
               <div id="banderas">
<!--             <ul class="band_portada"> -->
            	<select style="visibility:hidden;" name="fIdiomas" class="<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>" onchange="javascript:cambiaIdioma();" >


            	<?php
		            while (!$listaIdiomas->EOF)
		            {
		            ?>
                  		<option title="<?php echo $listaIdiomas->fields['nombre'];?>" value="<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>" <?php echo ($sLang == $listaIdiomas->fields['codIdiomaIso2']) ? "selected=\"selected\"" : "";?> ><?php echo $listaIdiomas->fields['nombre'];?></option>
<!--                        <li class="<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>"><a href="<?php echo constant("HTTPS_SERVER") ;?>?fLang=<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>" title="<?php echo $listaIdiomas->fields['nombre'];?>"><?php echo $listaIdiomas->fields['nombre'];?></a></li>-->
                    <?php
		              $listaIdiomas->MoveNext();
		            }
                ?>
            	</select>
<!--            </ul> -->
        </div><!-- Fin de las banderas -->
        <label><?php echo constant("STR_LOGIN");?></label>
        <input type="text" name="fLogin" class="obliga cajaInput" />
        <label><?php echo constant("STR_PASSWORD");?></label>
        <input type="password" name="fPwd" class="obliga cajaInput" />
        <input type="hidden" name="fLang" id="fLang" value="<?php echo $sLang;?>" />
        <label><a href="password_olvidada.php?fLang=<?php echo $sLang;?>"  class="gcont" title="<?php echo constant("STR_RECUERDAME_LA_CLAVE");?>"><?php echo constant("STR_GESTION_CONTRASENA");?></a></label>
        <input name="fGo" type="submit" class="btn_acceder" value="<?php echo constant("STR_ENTRAR");?>" />
        </form>
        <div id="error"><p><?php echo $strMensaje;?>&nbsp;</p></div>
        </div><!-- Fin de accesos -->
    </div><!-- Fin de cuerpo -->
    <div id="pie">
        <!-- <p class="dweb"><a href="http://www.azulpomodoro.com" target="_blank" title="<?php echo constant("STR_DISENO_DESARROLLO");?>"><?php echo constant("STR_DISENO_DESARROLLO");?></a></p>
         --><p class="copy dweb"><a href="http://www.people-experts.com" target="_blank" title="Expertos en personas"><?php echo constant("NOMBRE_EMPRESA");?></a> - <?php echo constant("STR_DERECHOS_RESERVADOS");?></p>
        <p class="copy dweb"><a href="<?php echo constant("HTTPS_SERVER")?>legal.html" target="_blank" title="<?php echo constant("STR_AVISO_LEGAL");?>"><?php echo constant("STR_AVISO_LEGAL");?></a></p>
    </div><!-- Fin de pie -->
</div><!-- Fin de la pagina -->
<script type="text/javascript">// Script para Autocompletar "off" y que valide con la W3C
	autoComplete();
</script>
</body>
</html>
