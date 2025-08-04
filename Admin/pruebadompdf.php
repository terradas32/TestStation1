<?php

// require __DIR__.'/vendor/autoload.php';
//
// use Spipu\Html2Pdf\Html2Pdf;
//
// $html2pdf = new Html2Pdf();
// $html2pdf->writeHTML('<h1>HelloWorld</h1>This is my first test');
// $html2pdf->output();


// Incluimos la librerias necesarias
require('include/Configuracion.php');
require_once(constant('DOMPDF_DIR') . 'autoload.inc.php');


require_once(constant('DOMPDF_DIR') . 'lib/html5lib/Parser.php');
require_once(constant('DOMPDF_DIR') . 'lib/php-font-lib/src/FontLib/Autoloader.php');
require_once(constant('DOMPDF_DIR') . 'lib/php-svg-lib/src/autoload.php');
require_once(constant('DOMPDF_DIR') . 'src/Autoloader.php');
require_once(constant('DOMPDF_DIR') . 'src/FontMetrics.php');
Dompdf\Autoloader::register();
// Reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\FontMetrics;

// Set options to enable embedded PHP
$options = new Options();
$options->set('isPhpEnabled', 'true');
$options->set('enable_remote', 'true');
$options->set('defaultFont', 'Arial');


$dompdf = new Dompdf($options);

// Definimos el tamaño y orientación del papel que queremos.
//$dompdf->set_paper("A4");
 $dompdf->set_paper("A4", "portrait");


 $footer_html    =  mb_strtoupper("nombre prueba", 'UTF-8') . str_repeat(" ", 70) . "La reproducción total o parcial de este Informe por cualquier medio, infringe los derechos de Copyright.";
 $sDirImg="imgInformes/";
 $spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
$sNombre = "prismlucasisaza-mejialucasisazamgmailcom510213128es1";
 $_fichero = $spath . $sDirImg . $sNombre . ".html";
     $dompdf->load_html(utf8_encode(file_get_contents($_fichero)));


     $dompdf->render(); //este comando renderiza el PDF

     //Esto es lo que imprime en el PDF el numero de paginas
     $canvas = $dompdf->get_canvas();
     $footer = $canvas->open_object();
     $w = $canvas->get_width();
     $h = $canvas->get_height();
     $canvas->page_text($w-60,$h-28," {PAGE_NUM} / {PAGE_COUNT}", $dompdf->getFontMetrics()->get_font('helvetica'),6);
     $canvas->page_text($w-580,$h-28,$footer_html, $dompdf->getFontMetrics()->get_font('helvetica'),6);

     $canvas->close_object();
     $canvas->add_object($footer,"all");
     //
     $output = $dompdf->output(); //extrae el contenido renderizado del PDF

     $nombreDelDocumento = "prueba.pdf";
$bytes = file_put_contents($nombreDelDocumento, $output);
?>
