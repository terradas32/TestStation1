<?php 
function convertir_fecha($fecha_datetime){
	//Esta función convierte la fecha del formato DATETIME de SQL a formato DD-MM-YYYY HH:mm:ss
	$fecha = explode("-",$fecha_datetime);
	$hora = explode(":",$fecha[2]);
	$fecha_hora = explode(" ",$hora[0]);
	$fecha_convertida = $fecha_hora[0].'/'.$fecha[1].'/'.$fecha[0].' '.$fecha_hora[1].':'.$hora[1].':'.$hora[2];
	return $fecha_convertida;
}

require_once(constant("DIR_WS_COM") . 'Idiomas/Idiomas.php');
require_once(constant("DIR_WS_COM") . 'Idiomas/IdiomasDB.php');
$cIdiomasDB = new IdiomasDB($conn);
$cIdiomas = new Idiomas();
$cIdiomas->setActivoBack(1);
$cIdiomas->setOrder("ASC");
$cIdiomas->setOrderBy("orden");
$sqlIdiomas = $cIdiomasDB->readLista($cIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);
$sHijos = "";
if (empty($_POST["fHijos"]))
{
	require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
	require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
	$cEmpresaPadre = new Empresas();
	$cEmpresaPadreDB = new EmpresasDB($conn);
	$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
//	$_EmpresaLogada = constant("EMPRESA_PE");
	$sHijos = $cEmpresaPadreDB->getHijos($_EmpresaLogada);
	if (!empty($sHijos)){
		$sHijos .= $_EmpresaLogada;
	}else{
		$sHijos = $_EmpresaLogada;
	}
}else{
	$sHijos = $_POST["fHijos"];
}
//	echo $sHijos;exit;
?>
<input type="hidden" name="fHijos" value="<?php echo $sHijos;?>" />
    <div id="head" class="empresa">
        <div class="logo">
        <a href="<?php echo constant("HTTP_SERVER_FRONT");?>index.php?fLang=<?php echo $sLang;?>" title="<?php echo constant("STR_INICIO");?>"><img src="graf/logo.jpg" alt="<?php echo constant("NOMBRE_EMPRESA");?> <?php echo constant("STR_INICIO");?>" title="<?php echo constant("NOMBRE_EMPRESA");?> <?php echo constant("STR_INICIO");?>" /></a>
        </div><!-- Fin de logo -->
    <h1 style="padding: 97px 157px 0;"><?php echo constant("STR_EMPRESA");?></h1>
    </div><!-- Fin de la cabecera -->