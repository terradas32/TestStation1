<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
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
	require_once(constant("DIR_WS_COM") . "Ejemplos/EjemplosDB.php");
	require_once(constant("DIR_WS_COM") . "Ejemplos/Ejemplos.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once(constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
	require_once(constant("DIR_WS_COM") . "Opciones/Opciones.php");
	require_once(constant("DIR_WS_COM") . "Opciones_ejemplos/Opciones_ejemplosDB.php");
	require_once(constant("DIR_WS_COM") . "Opciones_ejemplos/Opciones_ejemplos.php");
	require_once(constant("DIR_WS_COM") . "Combo.php");


	include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

    $cRespPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
	$cPruebasDB = new PruebasDB($conn);
    $cEjemplosDB = new EjemplosDB($conn);


    $cCandidato = new Candidatos();
    $cCandidato  = $_cEntidadCandidatoTK;

    $cProceso_pruebas = new Proceso_pruebas();
    $cProceso_pruebasDB = new Proceso_pruebasDB($conn);


    $cPruebas = new Pruebas();

    $cEjemplosListar = new Ejemplos();
    $cEjemplosListar->setIdPrueba($_POST['fIdPrueba']);
    $cEjemplosListar->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    $cEjemplosListar->setOrderBy("orden");
    $cEjemplosListar->setOrder("ASC");

    $sqlEjemplos = $cEjemplosDB->readLista($cEjemplosListar);
    $listaEjemplos = $conn->Execute($sqlEjemplos);

    $iTamaniolistaEjemplos = $listaEjemplos->recordCount();

    $cEjemplos = new Ejemplos();

    $cPruebas = new Pruebas();

    $cPruebas->setIdPrueba($_POST['fIdPrueba']);
	$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);

	$cPruebas = $cPruebasDB->readEntidad($cPruebas);

	$sPreguntasPorPagina = $cPruebas->getPreguntasPorPagina();
	if (!is_numeric($sPreguntasPorPagina))
	{
		$sPreguntasPorPagina = 1;
	}
    if($sPreguntasPorPagina<1){
		$sPreguntasPorPagina=1;
	}
	$iPaginas = $iTamaniolistaEjemplos / $sPreguntasPorPagina;
	$iPaginas = ($iPaginas < 1) ? 1 : $iPaginas;
	if(isset($_POST['fOrden'])){
//		echo "Orden : " . $_POST['fOrden'] . "<br />";
//		echo "TamañoLista : " . $iTamaniolistaEjemplos . "<br />";
//		echo "iPaginas : " . $iPaginas . "<br />";
//		echo "sPreguntasPorPagina : " . $sPreguntasPorPagina . "<br />";
		if($_POST['fOrden'] !="" && $_POST['fOrden'] !=1){
			if($_POST['fOrden']+$sPreguntasPorPagina > $iTamaniolistaEjemplos){
				$cEjemplos->setOrden($_POST['fOrden']);?>
				<script>
					ocultomuestro(1,0,1);
				</script>
			<?php }else{
				$cEjemplos->setOrden($_POST['fOrden']);?>
				<script>
					ocultomuestro(1,1,0);
				</script>
		<?php	}
		}else{
			if($iPaginas==1){
				$cEjemplos->setOrden('1');?>
				<script>
					ocultomuestro(0,0,1);
				</script>
		<?php	}else{
				$cEjemplos->setOrden('1');?>
				<script>
					ocultomuestro(0,1,0);
				</script>
			<?php }
		}
	}else{
		$cEjemplos->setOrden('1');?>
			<script>
				ocultomuestro(0,1,0);
			</script>
	<?php }


    $cEjemplos->setIdPrueba($_POST['fIdPrueba']);
    $cEjemplos->setIdPruebaHast($_POST['fIdPrueba']);
	$cEjemplos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	$cEjemplos->setOrden($cEjemplos->getOrden());
	$cEjemplos->setOrdenHast(($cEjemplos->getOrden() + $sPreguntasPorPagina)-1);
	$cEjemplos->setOrderBy('orden');
	$cEjemplos->setOrder('ASC');

	$sqlEjemplos = $cEjemplosDB->readLista($cEjemplos);
	$listEjemplos = $conn->Execute($sqlEjemplos);
	$listEjemplos->MoveFirst();

//Miramos si es de tipo de personalidad(prisma, clp...)
if($cPruebas->getIdTipoPrueba() !='7'){
	include('include/ejemploTipoPruebaNormal.php');
}else{
	include('include/ejemploTipoPrueba7.php');
}?>
<input type="hidden" name="fIdItem" value="<?php echo $listEjemplos->fields['idEjemplo']?>" />
