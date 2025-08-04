<?php
include ("../jpgraph.php");
include ("../jpgraph_pie.php");
include ("../jpgraph_pie3d.php");

// Some data
$data = array(10,27,45,75,89);

// Create the Pie Graph.
$graph = new PieGraph(475,200,"auto");
$graph->SetShadow();

// Set A title for the plot
$graph->title->Set("% PUESTOS POR NIVELES");
$graph->title->SetFont(FF_VERDANA,FS_BOLD,12); 
$graph->title->SetColor("black");
//$graph->legend->Pos(X,Y);
$graph->legend->Pos(0.1,0.2);

// Create 3D pie plot
$p1 = new PiePlot3d($data);
$p1->SetTheme("earth");	//Colores predifinidos.
$p1->SetCenter(0.3);
$p1->SetSize(85);

// Adjust projection angle
$p1->SetAngle(30);

// Adjsut angle for first slice
$p1->SetStartAngle(50);

// As a shortcut you can easily explode one numbered slice with
for ($i=0, $max = sizeof($data); $i < $max; $i++){
	$p1->ExplodeSlice($i);
}
// Setup slice values
$p1->value->SetFont(FF_ARIAL,FS_BOLD,11);
$p1->value->SetColor("navy");


$p1->SetLegends(array("GERENCIA","DIRECTORES","GESTORES","MANDOS INT.","TECNICOS","OPERARIOS"));

$graph->Add($p1);
$graph->Stroke();

?>


