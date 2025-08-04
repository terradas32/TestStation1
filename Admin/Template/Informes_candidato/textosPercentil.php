<?php


$sTextosPercentil='';
if (!isset($idTipoInforme)) {
    if (isset($_POST['fIdTipoInforme'])) {
        $idTipoInforme = $_POST['fIdTipoInforme'];
    }
}

$sSQL = "SELECT texto FROM textos_percentil WHERE min <= " . $iPercentil . " AND max >= " . $iPercentil . " AND idPrueba=" . $cPruebas->getIdPrueba() . " AND codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false) . " AND idTipoInforme=" . $idTipoInforme;
$rsTextosPercentil = $conn->Execute($sSQL);
if ($rsTextosPercentil->recordCount() > 0) {
    $sTextosPercentil = "<br />";
    while (!$rsTextosPercentil->EOF) {
        $sTextosPercentil .= $rsTextosPercentil->fields['texto'];
        $rsTextosPercentil->MoveNext();
    }
}
