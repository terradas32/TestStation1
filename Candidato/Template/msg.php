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
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
	require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	require_once(constant("DIR_WS_COM") . "Correos_proceso/Correos_procesoDB.php");
	require_once(constant("DIR_WS_COM") . "Correos_proceso/Correos_proceso.php");
	require_once(constant("DIR_WS_COM") . "Correos/CorreosDB.php");
	require_once(constant("DIR_WS_COM") . "Correos/Correos.php");
	require_once(constant("DIR_WS_COM") . "Envios/EnviosDB.php");
	require_once(constant("DIR_WS_COM") . "Envios/Envios.php");
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");


include_once ('include/conexion.php');

$cEntidadIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
$cEntidadIdiomas	= new Idiomas();  // Entidad
$cEntidadIdiomas->setActivoBack(1);
$sqlIdiomas = $cEntidadIdiomasDB->readLista($cEntidadIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);

$cEntidadDB	= new CandidatosDB($conn);  // Entidad DB
$cEntidad	= new Candidatos();  // Entidad
$cEnviosDB	= new EnviosDB($conn);  // Entidad DB
$cEnvios	= new Envios();  // Entidad
$cUtilidades	= new Utilidades();

$cProcesosDB	= new ProcesosDB($conn);
$cProcesos	= new Procesos();
$cEmpresas = new Empresas();
$cEmpresasDB = new EmpresasDB($conn);

$sLogo = '<img alt="' . constant("NOMBRE_EMPRESA") . '" src="' . constant("DIR_WS_GRAF") . 'logo.png" border="0" />';

if (!empty($_GET['s']))
{
	$cEntidad->setToken($_GET["s"]);
	$cEntidad = $cEntidadDB->candidatoPorToken($cEntidad);
	if ($cEntidad->getIdEmpresa() != "")
	{
		//Sacamos la información del proceso
		$cProcesos->setIdEmpresa($cEntidad->getIdEmpresa());
		$cProcesos->setIdProceso($cEntidad->getIdProceso());
		$cProcesos = $cProcesosDB->readEntidad($cProcesos);

		//Sacamos la información de la Empresa
		$cEmpresas->setIdEmpresa($cEntidad->getIdEmpresa());
		$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);

		if ($cEmpresas->getPathLogo() != ""){
			$size = list($anchura, $altura) = @getimagesize(constant("DIR_WS_GESTOR") . $cEmpresas->getPathLogo());
			$anchura=$size[0];
			$altura=$size[1];
			if ($altura > 85){
				$altura = 85;
			}
				$altura.="px";
				$sLogo = '<img title="' . $cEmpresas->getNombre() . '" alt="' . $cEmpresas->getNombre() . '" src="' . constant("DIR_WS_GESTOR") . $cEmpresas->getPathLogo() . '" height="' . $altura . '" />';
		}else{
			$sLogo = $cEmpresas->getNombre();
		}
	}
}
?>
<!doctype html>
<html lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, WI2.2 www.negociainternet.com" />
<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos-comunes.css" type="text/css" />
	<link rel="stylesheet" href="estilos/estilos-candidato.css" type="text/css" />
     <script src="codigo/eventos.js"></script>
</head>
<body>
<div id="pagina">
    <div id="head" class="candidato">
        <div class="logo">
					<a href="index.php" title="Inicio">
						<?php
							if ($cProcesos->getProcesoConfidencial() != "1"){
								echo $sLogo;
							}
						?>
					</a>
        </div><!-- Fin de logo -->
    <h1><?php echo constant("STR_ADMINISTRACION");?></h1>
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
		<label>&nbsp;</label>
		<label>&nbsp;</label>
		<input name="fAceptar" type="submit" class="botones" value="<?php echo constant("STR_ACEPTAR");?>" />
        </form>
        <div id="error"><p><?php echo $strMensaje;?>&nbsp;</p></div>
        </div><!-- Fin de accesos -->
    </div><!-- Fin de cuerpo -->
    <div id="pie">
        <p class="dweb"><a href="http://www.negociainternet.com" title="Diseño Web"><?php echo constant("STR_DISENO_DESARROLLO");?></a></p>
        <p class="copy">Psicólogos Empresariales - <?php echo constant("STR_DERECHOS_RESERVADOS");?></p>
        <!-- <p class="copy dweb"><a href="<?php echo constant("HTTP_SERVER")?>legal.html" target="_blank" title="<?php echo constant("STR_AVISO_LEGAL");?>"><?php echo constant("STR_AVISO_LEGAL");?></a></p> -->
    </div><!-- Fin de pie -->
</div><!-- Fin de la pagina -->
</body>
</html>
