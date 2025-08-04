<?php 
if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../include/Configuracion.php");
}
    include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
  	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
	$sDongles = "";
	$sEmpresa = "";
	$sMensajeDongles="";
	if (isset($_POST["fDongles"]) && !empty($_POST["fDongles"])){
		$sDongles = $_POST["fDongles"];
	}
	if (isset($_POST["fEmpresa"]) && !empty($_POST["fEmpresa"])){
		$sEmpresa = $_POST["fEmpresa"];
	}
	if (!empty($sEmpresa) && !empty($sDongles)){
		$sql = "INSERT INTO consumos (empresa, concepto, unidades) VALUES";
		$sql .= "('" . $sEmpresa . "','Descarga masiva EXCEL', '-" . $sDongles . "')";
		$conn->Execute($sql);
		$sql = "UPDATE empresas SET dongles=dongles-" . $sDongles ." WHERE usuario='" . $sEmpresa . "'";
		$conn->Execute($sql);
		$sMensajeDongles="Se le han descontado " . $sDongles . " dongles.";
	}else{
		echo("DISCOUNT::" . constant("ERR"));
		exit;
	}
?>
<table cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td colspan="2" class="rojob">
			<?php echo $sMensajeDongles;?>
		</td>
	</tr>
</table>