<?php
include ($_SERVER["DOCUMENT_ROOT"] . "/jpgraph/jpgraph.php");
include ($_SERVER["DOCUMENT_ROOT"] . "/jpgraph/jpgraph_line.php");

// Some data
$datay=array($iVERBAL, $iLOGICO, $iNUMERICO, $iINGLES, $iINGLESL);
if ($bIdeal){
    $data2y = array(14,18,33,29,18);
}
// A nice graph with anti-aliasing
$graph = new Graph($width, $height,"auto");
$graph->img->SetMargin(40,40,80,40);
$graph->SetScale('textlin',0,100);
$graph->yaxis->SetLabelFormat('%2d%%');
$graph->SetFrame(false); 
$graph->SetMarginColor('white');
$graph->img->SetAntiAliasing("white");

$graph->SetShadow();
$graph->title->Set($sCadena);

// Use built in font
$graph->title->SetFont(FF_FONT1,FS_BOLD);

// Slightly adjust the legend from it's default position in the
// top right corner. 
$graph->legend->Pos(0.37,0.08,0.05,0.05);

// Setup X-axis labels
$a = array("Verbal","Lógica","Cálculo","Inglés", "Listening");
$graph->xaxis->SetTickLabels($a);


// Create the first line
$p1 = new LinePlot($datay);
$p1->mark->SetType(MARK_FILLEDCIRCLE);
$p1->mark->SetFillColor("#004080");
$p1->mark->SetWidth(4);
$p1->SetColor("#004080");
$p1->SetCenter();
$p1->SetLegend("Resultados");
$graph->Add($p1);

if ($bIdeal){
    // ... and the second
    $p2 = new LinePlot($data2y);
    $p2->mark->SetType(MARK_FILLEDCIRCLE);
    $p2->mark->SetFillColor("orange");
    $p2->mark->SetWidth(4);
    $p2->SetColor("orange");
    $p2->SetCenter();
    $p2->SetLegend("Perfil Ideal");
    $graph->Add($p2);
}
// Output line
//$graph->Stroke();

?>


