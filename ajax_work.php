<?php
function ajaxGenerateCover() {
	
	$img = $_POST ['base_64'];
	
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	
	$data = base64_decode($img);
	$im = imagecreatefromstring($data);
	
	if ($im !== false) {
		
		header('Content-Type: image/jpg');
		
		$upload_dir = wp_upload_dir();
		$randomName = "Generated-Cover-" . date("m.d.y") . '-' . time();
		imagejpeg($im, $upload_dir ['path'] . "/$randomName.jpg", 100);
		imagedestroy($im);
		
		die($upload_dir ['url'] . "/$randomName.jpg");
	} else {
		die('An error occurred.');
	}
}

add_action('wp_ajax_nopriv_ajaxGenerateCover', 'ajaxGenerateCover');
add_action('wp_ajax_ajaxGenerateCover', 'ajaxGenerateCover');