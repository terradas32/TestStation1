<?php
 # Script 1

/*
 * This page creates a simple image.
 * The image makes use of a TrueType font.
 */

// Establish image factors:
$text = 'Sample text';
$font_size = 10; // Font size is in pixels.
$font_file = 'arial.ttf'; // This is the path to your font file.

// Retrieve bounding box:
$grados=90;
$type_space = imagettfbbox($font_size, $grados, $font_file, $text);

// Determine image width and height, 10 pixels are added for 5 pixels padding:
if ($grados==90){
	$image_height = abs($type_space[4] - $type_space[0]) + 10;
	$image_width = abs($type_space[5] - $type_space[1]) + 5;
}else{
	$image_width = abs($type_space[4] - $type_space[0]) + 10;
	$image_height = abs($type_space[5] - $type_space[1]) + 5;
}

// Create image:
//Horizontal

if ($grados==90){
	$image = imagecreatetruecolor($image_height, $image_width);
}else{
	$image = imagecreatetruecolor($image_width, $image_height);
}

// Allocate text and background colors (RGB format):
$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);

// Fill image:
imagefill($image, 0, 0, $bg_color);

// Fix starting x and y coordinates for the text:
$x = 5; // Padding of 5 pixels.
$y = $image_height - 5; // So that the text is vertically centered.


// Add TrueType text to image:
if ($grados==90){
	imagettftext($image, $font_size, $grados, $y, $x, $text_color, $font_file, $text);
}else{
	imagettftext($image, $font_size, $grados, $x, $y, $text_color, $font_file, $text);
}


// Generate and send image to browser:
header('Content-type: image/png');
imagepng($image);

// Destroy image in memory to free-up resources:
imagedestroy($image);

?>