<?php
// Incluimos la librerias necesarias
// require_once(constant('DOMPDF_DIR') . 'autoload.inc.php');
// require_once(constant('DOMPDF_DIR') . 'lib/html5lib/Parser.php');
// require_once(constant('DOMPDF_DIR') . 'lib/php-font-lib/src/FontLib/Autoloader.php');
// require_once(constant('DOMPDF_DIR') . 'lib/php-svg-lib/src/autoload.php');
// require_once(constant('DOMPDF_DIR') . 'src/Autoloader.php');
// require_once(constant('DOMPDF_DIR') . 'src/FontMetrics.php');
// Dompdf\Autoloader::register();

require_once(constant('DOMPDF_VENDOR_DIR') . 'autoload.php');
// Reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\FontMetrics;

// Set options to enable embedded PHP
$options = new Options();
$options->set('debugKeepTemp', false); 
$options->set('isPhpEnabled', true);
$options->set('enable_remote', true);
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', true); 


$dompdf = new Dompdf($options);

header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');

// Definimos el tamaño y orientación del papel que queremos.
//$dompdf->set_paper("A4");
 $dompdf->set_paper("A4", "portrait");


// $paper_size = array(0,0,360,360);
// $dompdf->set_paper($paper_size);
//$dompdf->set_paper(0, 0, 794, 1204);

// Cargamos el contenido HTML.
    // ob_start();
    // include $_fichero;
    // $html_para_pdf = ob_get_clean();
    //$dompdf->load_html($html_para_pdf);

    //$dompdf->load_html($sHtml);
    $_html_tratado = utf8_encode(file_get_contents($_fichero));
    $mystring = HTTP_SERVER;
    $findme   = '.test';    //DESARROLLO
    $pos = strpos($mystring, $findme);
    if ($pos === false) {
        $servidor = str_replace('/Admin/', '', HTTP_SERVER);
        $_html_tratado = str_replace($servidor, "http://localhost", utf8_encode(file_get_contents($_fichero)));
    } 
    $dompdf->load_html($_html_tratado);


    $dompdf->render(); //este comando renderiza el PDF

    // $font = $dompdf->getFontMetrics()->get_font("helvetica", "bold");
    // $dompdf->getCanvas()->page_text(72, 18, "Header: {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0,0,0));

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
    file_put_contents($spath . $sDirImg . $sNombre . '.pdf', $output); //guarda el PDF en un fichero llamado mipdf.pdf

 ?>
