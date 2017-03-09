<?php

function my_myme_types($mime_types){
    $mime_types['ttf'] = 'font/ttf';
    $mime_types['otf'] = 'font/otf';
    $mime_types['ttf'] = 'font/truetype';
    return $mime_types;
}
add_filter('upload_mimes', 'my_myme_types', 1, 1); 


function getAllFonts(){
	$query_fonts_args = array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'font',
		'post_status'    => 'inherit',
		'posts_per_page' => - 1,
	);
	$query_fonts = new WP_Query($query_fonts_args);
	$fonts = array();
	$font_i = 0;
	foreach ($query_fonts->posts as $font) {
		$fonts[$font_i]['name'] = $font->post_title;
		$fonts[$font_i]['url'] = wp_get_attachment_url($font->ID);
		$fonts[$font_i]['id'] = $font->ID;
		$font_i++;
	}
	return $fonts;
}