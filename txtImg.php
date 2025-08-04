<?php

function calculateTextBox($text,$fontFile,$fontSize,$fontAngle) {
    /************
    simple function that calculates the *exact* bounding box (single pixel precision).
    The function returns an associative array with these keys:
    left, top:  coordinates you will pass to imagettftext
    width, height: dimension of the image you have to create
    *************/
    $rect = imagettfbbox($fontSize,$fontAngle,$fontFile,$text);
    $minX = min(array($rect[0],$rect[2],$rect[4],$rect[6]));
    $maxX = max(array($rect[0],$rect[2],$rect[4],$rect[6]));
    $minY = min(array($rect[1],$rect[3],$rect[5],$rect[7]));
    $maxY = max(array($rect[1],$rect[3],$rect[5],$rect[7]));
   
    return array(
     "left"   => abs($minX) - 1,
     "top"    => abs($minY) - 1,
     "width"  => $maxX - $minX,
     "height" => $maxY - $minY,
     "box"    => $rect
    );
}

function TxtToIMG($text_string, $grados=90)
{
	// Reemplace la ruta por la de su propia fuente
	putenv('GDFONTPATH=' . realpath('.'));
	
	$font_ttf        = "arial.ttf";
	$font_size        = 10;
	$text_angle        = 90;
	$text_padding    = 5; // Img padding - around text
	
	$the_box        = calculateTextBox($text_string, $font_ttf, $font_size, $text_angle);
	
	
	$imgWidth    = $the_box["width"] + $text_padding;
	$imgHeight    = $the_box["height"] + $text_padding;
	
	
	$image = imagecreatetruecolor($imgWidth,$imgHeight);
	$bg_color = imagecolorallocate($image, 255, 255, 255);
	$text_color = imagecolorallocate($image, 0, 0, 0);
	imagefill($image, 0, 0, $bg_color);
	
	
	imagettftext($image, $font_size, $text_angle, $the_box["left"] + ($imgWidth / 2) - ($the_box["width"] / 2), $the_box["top"] + ($imgHeight / 2) - ($the_box["height"] / 2), $text_color, $font_ttf, $text_string);
	$_objImg = imagepng($image);
	
	// Destroy image in memory to free-up resources:
	imagedestroy($image);
	return $_objImg;
}
if (!empty($_REQUEST["s"])){
	echo TxtToIMG($_REQUEST["s"], 0);
}
?> 