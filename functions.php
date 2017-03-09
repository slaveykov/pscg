<?php

function ImageCreateFromAny($filepath) {
	$type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
	$allowedTypes = array(
			1,  // [] gif
			2,  // [] jpg
			3,  // [] png
			6   // [] bmp
	);
	if (!in_array($type, $allowedTypes)) {
		return false;
	}
	switch ($type) {
		case 1 :
			$im = imageCreateFromGif($filepath);
			break;
		case 2 :
			$im = imageCreateFromJpeg($filepath);
			break;
		case 3 :
			$im = imageCreateFromPng($filepath);
			break;
		case 6 :
			$im = imageCreateFromBmp($filepath);
			break;
	}
	return $im;
}
function hex2rgb($hex) {
	$hex = str_replace("#", "", $hex);

	if(strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	$rgb = array($r, $g, $b);
	//return implode(",", $rgb); // returns the rgb values separated by commas
	return $rgb; // returns an array with the rgb values
}
function showImageError($path = ''){
	// Set the content-type
	header('Content-Type: image/jpg');
	// Create the image
	$im = imagecreatetruecolor(850, 315);

	// Create some colors
	$white = imagecolorallocate($im, 255, 255, 255);
	$black = imagecolorallocate($im, 53, 85, 112);
	imagefilledrectangle($im, 0, 0, 850, 315, $black);

	// The text to draw
	$second_text = 'ERROR! CANT LOAD IMAGE';
	// Replace path by your own font path
	$second_font = $path.'fonts/error.ttf';
	$second_textSize = 25;
	$second_margin_left = 255;
	$second_margin_top = 170;
	// Add the text
	imagettftext($im, $second_textSize, 0, $second_margin_left, $second_margin_top, $white, $second_font, $second_text);


	// Using imagepng() results in clearer text compared with imagejpeg()
	$randomName = "Generated-Cover-".date("m.d.y").'-'.time();
	imagejpeg($im, $path."generated/$randomName.jpg", 100);

	die($path."generated/$randomName.jpg");
}

function array_sort($array, $on, $order=SORT_ASC)
{
	$new_array = array();
	$sortable_array = array();

	if (count($array) > 0) {
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				foreach ($v as $k2 => $v2) {
					if ($k2 == $on) {
						$sortable_array[$k] = $v2;
					}
				}
			} else {
				$sortable_array[$k] = $v;
			}
		}

		switch ($order) {
			case SORT_ASC:
				asort($sortable_array);
				break;
			case SORT_DESC:
				arsort($sortable_array);
				break;
		}

		foreach ($sortable_array as $k => $v) {
			$new_array[$k] = $array[$k];
		}
	}

	return $new_array;
}