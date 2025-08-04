<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


require_once('include/Configuracion.php');
include_once('include/Idiomas.php');

//define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
include(constant("DIR_ADODB") . 'adodb.inc.php');
require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");

include_once ('include/conexion.php');

$cEntidadIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
$cEntidadIdiomas	= new Idiomas();  // Entidad
$cEntidadIdiomas->setActivoBack(1);
$sqlIdiomas = $cEntidadIdiomasDB->readLista($cEntidadIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);

$cEntidadDB	= new EmpresasDB($conn);  // Entidad DB
$cEntidad	= new Empresas();  // Entidad
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
    <script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
    <script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
  <script language="javascript" type="text/javascript" src="codigo/common.js"></script>
  <script language="javascript" type="text/javascript" src="codigo/eventos.js"></script>
  <script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
  <script language="javascript" type="text/javascript" src="codigo/jquery.tools.min.js"></script>
<script language="javascript">
    function enviar()
    {
    	var f=document.forms[0];
    	if(f.fAceptaCondiciones.checked && f.fAceptaTerminos.checked){
    			return true;
    		}else{
          alert("<?php echo constant("STR_DEBE_ACEPTAR_LOS_TERMINOS");?>");
          return false;
        }
    }

</script>
</head>
<body onload="noBack();" >
<form name="form" method="post" action="bienvenida.php" onsubmit="return enviar();">
<div id="aviso">
    <div id="head" class="empresa">
        <div class="logo">
        <a href="index.php" title="Inicio"><img src="graf/logo.jpg" alt="#" title="#" /></a>
        </div><!-- Fin de logo -->
    <h1><?php echo constant("STR_EMPRESA");?></h1>
    </div><!-- Fin de la cabecera -->
    <div id="cuerpo" style="">
        <div id="aviso" class="acc_cand" style="padding: 0 0 0 7%;">
        <h2><?php echo constant("STR_AVISO_LEGAL");?></h2>
		<p>
			PSICOLOGOS EMPRESARIALES ASOCIADOS, S.A. (PEOPLE EXPERTS) como responsable del tratamiento se obliga a cumplir con lo dispuesto en el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo de 27 de abril de 2016 relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de estos datos (RGPD), en los siguientes puntos:
		</p>
    <p>
          PSICOLOGOS EMPRESARIALES ASOCIADOS S.A. deberá informar a los candidatos y a permitir el ejercicio de los derechos de acceso, rectificación, supresión, limitación del tratamiento, portabilidad de los datos y a retirar los consentimientos dados,
          <br /><br />
          PSICOLOGOS EMPRESARIALES ASOCIADOS S.A. deberá comunicar de forma inmediata al CLIENTE (Concesionario) el ejercicio de dichos derechos a los efectos del cumplimiento por parte del CLIENTE (Concesionario) respecto de la información a éste cedida por PSICOLOGOS EMPRESARIALES ASOCIADOS, S.A., debiendo el CONCESIONARIO comunicar el cumplimiento de dichas solicitudes a PSICOLOGOS EMPRESARIALES ASOCIADOS S.A.
		</p>
    <table>
        <tr>
          <td>
            <?php
            $sCheckDISPLAY="";
            $sCheckedCOND="";
            ?>
            <input style="width: 20px;" <?php echo $sCheckDISPLAY;?> type="checkbox" name="fAceptaCondiciones" id="fAceptaCondiciones" <?php echo $sCheckedCOND;?> /> &nbsp;<a style="color:#000;" href="#_" <?php echo $sCheckDISPLAY;?>><label for="fAceptaCondiciones"><?php echo constant("STR_CONDICIONES_LEGALES");?></label></a>

          </td>
    </tr>
    </table>
    <br /><br />

    <h2><?php echo "Condiciones Generales";?></h2>
    <p>
      <strong>1. Información general y datos de contacto</strong>
      <br /><br />
1.1. Le damos la bienvenida a la plataforma de apoyo a los procesos de selección de asesores comerciales y asesores de servicio de Mazda España. Esta plataforma ha sido desarrollada por Psicólogos Empresariales S.A. (People Experts) con domicilio en Paseo Pintor Rosales, 44 1º izquierda 28008 Madrid con CIF A78301934.
<br /><br />
1.2. Si desea contactar con People Experts, puede hacerlo escribiéndonos a la dirección mencionada en el párrafo anterior o a la dirección de correo electrónico:
<br /><br />
info@people-experts.com
<br /><br />
<strong>2. Objeto y aceptación</strong>
<br /><br />
Este texto (en adelante, las “Condiciones Generales”) regula el uso de los contenidos y la contratación de servicios que ofrecemos desde People Experts. A través de la plataforma de apoyo a la selección, por lo que cuando acepte estas condiciones y términos de uso como concesionario-cliente (en adelante, el “Usuario”) queda automáticamente vinculado al cumplimiento de estas Condiciones Generales, lo que supone e implica que ha leído, comprende y acepta, sin limitaciones ni reservas, los avisos legales disponibles en la plataforma.
<br /><br />
<strong>3. Descripción y condiciones de los servicios</strong>
<br /><br />
3.1. La plataforma de apoyo a la selección permite a los concesionarios pertenecientes a la Red de Mazda España, dar de alta procesos de selección y agregar hasta un máximo de 3 candidatos a los que aplicar pruebas específicas para evaluar su grado de adecuación al perfil de asesor comercial o asesor de servicio definido en la Marca. Para dar de alta un proceso y evaluar a 3 candidatos, es necesario recargar 300 unidades en la plataforma. Estas unidades tienen un precio de 225€.
<br /><br />
3.2. Las unidades tienen una vigencia de un mes de manera que, si transcurrido dicho mes no se han dado de alta los 3 candidatos que permite el proceso, estas caducarán y será necesario efectuar una nueva recarga de 300 unidades.
<br /><br />

    </p>

    <table>
        <tr>
          <td>
            <?php
            $sCheckDISPLAY="";
            $sCheckedCOND="";
            ?>
            <input style="width: 20px;" <?php echo $sCheckDISPLAY;?> type="checkbox" name="fAceptaTerminos" id="fAceptaTerminos" <?php echo $sCheckedCOND;?> /> &nbsp;<a style="color:#000;" href="#_" <?php echo $sCheckDISPLAY;?>><label for="fAceptaTerminos"><?php echo "Acepto las Condiciones Generales";?></label></a>

          </td>
    </tr>
    </table>
    <br /><br />

    <table>
    <tr>
          <td>
            <input name="fAceptar" type="submit" class="botones" value="<?php echo constant("STR_ACEPTAR");?>" />
          </td>
        </tr>
    </table>
        </div><!-- Fin de accesos -->
    </div><!-- Fin de cuerpo -->
    <!-- <div id="pie">
        <p class="dweb"><a href="http://www.azulpomodoro.com" title="Diseño Web"><?php echo constant("STR_DISENO_DESARROLLO");?></a></p>
        <p class="copy">Psicólogos Empresariales - <?php echo constant("STR_DERECHOS_RESERVADOS");?></p>
    </div><!-- Fin de pie -->
</div><!-- Fin de la pagina -->
<input type="hidden" name="sTK" value="<?php echo $_POST['sTK'];?>" />
<input type="hidden" name="MODO" value="<?php echo $_POST["MODO"];?>" />

</form>
</body>
</html>
