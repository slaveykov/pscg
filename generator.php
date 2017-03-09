<?php
function add_form_to_front_cover($contentIn) {
	
	$post = get_post();
	$taxonomy_names = get_post_taxonomies($post->ID);

	if(strpos($_SERVER['REQUEST_URI'],"scg-cats")){
		return $contentIn;
	}
	
	if(isset($taxonomy_names[0]) && $taxonomy_names[0]=="scg-cats"){

		$fields_query = array(
				'meta_key'         => 'cover_id',
				'meta_value'       => $post->ID,
				'post_type' => 'fields'
		);
		$fields = get_posts($fields_query);
		
		if(!empty($fields)){
			
		$fields_json = array();
	   	foreach ($fields as $field){
	   		$custom_field = get_post_custom($field->ID);
		   	$fields_json[$field->ID]['id'] = $field->ID;
	   		$fields_json[$field->ID]['name'] = $custom_field['input_name'][0];
	   		$fields_json[$field->ID]['text_color'] = $custom_field['input_text_color'][0];
	   		$fields_json[$field->ID]['text_font_id'] = $custom_field['input_text_font_id'][0];
	   		$fields_json[$field->ID]['text_size'] = $custom_field['input_text_size'][0];
	   		$fields_json[$field->ID]['text_angle'] = $custom_field['input_text_angle'][0];
	   		$fields_json[$field->ID]['text_margin_left'] = $custom_field['input_text_margin_left'][0];
	   		$fields_json[$field->ID]['text_margin_top'] = $custom_field['input_text_margin_top'][0];
	   		$fields_json[$field->ID]['text_example'] = $custom_field['input_text_example'][0];
	   		$fields_json[$field->ID]['text_shadow_x'] = $custom_field['input_text_shadow_x'][0];
	   		$fields_json[$field->ID]['text_shadow_y'] = $custom_field['input_text_shadow_y'][0];
	   		$fields_json[$field->ID]['text_shadow_blur'] = $custom_field['input_text_shadow_blur'][0];
	   		$fields_json[$field->ID]['text_shadow_color'] = $custom_field['input_text_shadow_color'][0];
	   		$fields_json[$field->ID]['text_centred'] = $custom_field['input_text_centred'][0];
	   		$cover_preview = $custom_field['cover_example_preview'][0];
	   	}
	   	
	   	$dir = plugin_dir_url(__FILE__);
	   	
	   	$cover_image_path = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
	   	list($width_canvas, $height_canvas, $type, $attr) = getimagesize($cover_image_path[0]);
	   	$width_canvas = $cover_image_path[1];
	   	$height_canvas = $cover_image_path[2];
	   	
	   	if($cover_preview != false){
	   		
	   		$image = '';
	   		$title_attribute = get_the_title( $post->ID );
	   		$image .= '<figure class="post-featured-image">';
	   		$image .= '<a href="' . get_permalink() . '" title="'.the_title( '', '', false ).'">';
	   		$image .= '<img src="'.$cover_preview.'" id="scg-generated-image" />';
	   		$image .= '</a>';
	   		$image .= '</figure>';
	   		$content = $image;
	   		
	   	} else { 
		   	if( has_post_thumbnail() ) {
		   		
		   		$image = '';
		   		$title_attribute = get_the_title( $post->ID );
		   		$image .= '<figure class="post-featured-image">';
		   		$image .= '<a href="' . get_permalink() . '" title="'.the_title( '', '', false ).'">';
		   		$image .= get_the_post_thumbnail($post->ID, "", array('id'=>'scg-generated-image', 'title' => esc_attr($title_attribute), 'alt' => esc_attr($title_attribute))).'</a>';
		   		$image .= '</figure>';
		   	
		   		$content = $image;
		   	}
	   	}
	   	if(!isset($content)){
	   		$content = '';
	   	}
	   	
	   	$content .= $contentIn;
	   	
	   	$fonts_json = array();
	   	$content .= "<style>";
	   	foreach(getAllFonts() as $font){
	   		$fonts_json[$font['id']] = $font['name'];
	   		$content .= '
	   		@font-face
		   	   {
		   	   		font-family: "'.$font['name'].'";
		   	   		src: url("'.$font['url'].'");
		   	   }
	   		';
	   	}
	   	$content .= "</style>";
	   	
		$content .= '
		<style>
		.generator_form{
		
		}
		.generator_form input[type="text"]{
			margin:0px;
		}
		.generator_form textarea{
			margin:0px;
		}
		.generator_form input[type="submit"]{
		
		}
		.generator_form h4{
			font-size:15px;
		}
		.generator_form span{
			font-size:12px;
		}
       	.generator_form table tr td{
			border:0px;
		}
		</style>
		
		<script src="'.$dir.'js/fabric_front.js"></script>
		<script src="'.$dir.'js/phpscg.js"></script>
		<script src="'.$dir.'js/serialize.js"></script>
		<script>
        	jQuery.noConflict();
			(function($) {
			  $(function() {
					$("#scg-form").submit(function(e) {
						var generating_data = {
							post: $("#scg-form").serializeControls(),
							img: "'.$cover_image_path[0].'",
							width: "'.$width_canvas.'",
							height: "'.$height_canvas.'",
							fonts: '.json_encode($fonts_json).',
							fields: '.json_encode($fields_json).'
						};
						generateCoverImage(generating_data);
					    e.preventDefault();
					});
			  });
			})(jQuery);
        </script>
		<div class="generator_form">
		<form id="scg-form" method="post">
		<table style="border:0px;">';
		$hiddens = '';
		
		$tr_fields = array();
		$itr = 0;
		foreach ($fields as $field){
			$custom_field = get_post_custom($field->ID);
			$input_order = (int) $custom_field['input_order'][0];
			$tr_fields[$itr]['data'] = array();
			$tr_fields[$itr]['order'] = $input_order;
			$itr++;
		}
		
		//print_r(array_sort($tr_fields, 'order', SORT_DESC));
		//die(); 
		
		foreach ($fields as $field){
			
			$custom_field = get_post_custom($field->ID);
			
			if($custom_field['input_type'][0]=="textarea"){
				$content .= ' 
				<tr>
				<td style="width:20%;">
				<h4>'.$custom_field['input_name'][0].':</h4>
				</td>
				<td style="width:50%;">
				<textarea maxlength="'.$custom_field['input_max_chars'][0].'" name="InputID['.$field->ID.'][text]"></textarea>
				<span> (Characters Limit : '.$custom_field['input_max_chars'][0].') </span>
				</td>
				</tr>';
			}
			
			if($custom_field['input_type'][0]=="text"){
				$content .= '
				<tr>
				<td style="width:20%;">
				<h4>'.$custom_field['input_name'][0].':</h4>
				</td>
				<td style="width:50%;">
				<input type="text" value="" name="InputID['.$field->ID.'][text]" maxlength="'.$custom_field['input_max_chars'][0].'" />
				<span> (Characters Limit : '.$custom_field['input_max_chars'][0].') </span>		
				</td>
				</tr>';
			}
			
			if($custom_field['input_type'][0]=="hidden"){
				$hiddens .= '
				<input type="hidden" value="'.$custom_field['input_text_example'][0].'" name="InputID['.$field->ID.'][text]" />
				';
			}
			
		}
		
		$content .= '
		<tr>
		<td style="width:20%;"></td>
		<td style="width:50%;">
		'.$hiddens.'
		<input type="hidden" name="cover_id" value="'.$post->ID.'" />			
       	<input type="hidden" name="action" value="ajaxGenerateCover" />
		<input type="submit" value="Generate Cover" />
		</td>
		<td style="padding-left:10px;"></td>
		</tr>
		</table>
		</form>
		</div>';
			
		}
	}
	return $content;
}
add_filter('the_content', 'add_form_to_front_cover');

/*
// Clases
require 'classes/Box.php';
require 'classes/TextWrapping.php';
require 'classes/Color.php';
require 'classes/VerticalAlignment.php';
require 'classes/HorizontalAlignment.php';
*/

//$content .= '<iframe><canvas width="'.$width_canvas.'" height="'.$height_canvas.'" id="ceb"></canvas></iframe>';

// <script src="https://code.jquery.com/jquery-1.6.2.js"></script>

/*
 //var loading = "http://www.cgi.com/sites/all/themes/cgi/images/loading_icon.gif";
var url = "wp-admin/admin-ajax.php";

//$("#scg-generated-image").hide().attr("src",loading).fadeIn("slow");

$.ajax({
		type: "POST",
		url: url,
		data: $("#scg-form").serialize(),
		success: function(data)
		{
		$("#scg-generated-image").hide().attr("src",data).fadeIn("slow");
		}
		});

*/


/*
 function ajaxGenerateCover_OLD() {

if(!isset($_POST['cover_id'])){
exit('Please try again. Invalid cover id.');
}

$cover_id = (int) htmlspecialchars($_POST['cover_id']);
$cover_details = get_post_custom($cover_id);
$cover_image_path = wp_get_attachment_image_src(get_post_thumbnail_id($cover_id), 'full');

if(empty($cover_image_path)){
exit('Please, first add image.');
}

if(empty($cover_details)){
exit('Please try again. Invalid cover id.');
}

// Cover size in integer
$cover_width = 850;
$cover_height = 315;

// Create the image
$im = imagecreatetruecolor($cover_width, $cover_height);
$im = ImageCreateFromAny($cover_image_path[0]);
$box = new Box($im);

foreach($_POST["InputID"] as $key => $value){

$key = (int) $key;
$text = htmlspecialchars($_POST["InputID"][$key]['text']);
 
$rowInput = get_post_custom($key);
$input_font_id = $rowInput['input_text_font_id'][0];
$font_file = get_attached_file($input_font_id); // Full path

$rgb = hex2rgb("#".$rowInput['input_text_color'][0]);
$rgb2 = hex2rgb("#".$rowInput['input_text_shadow_color'][0]);

$text_color = imagecolorallocate($im, $rgb[0], $rgb[1], $rgb[2]);
$textSize = $rowInput['input_text_size'][0];
$textSize = ($textSize*3)/4;
$text_angle = $rowInput['input_text_angle'][0];
$bbox = imagettfbbox($textSize, 0, $font_file, $text);
$margin_top = abs($bbox[5]);
$margin_top = $rowInput['input_text_margin_top'][0] + $margin_top;

$box->setFontFace($font_file);
$box->setFontSize($rowInput['input_text_size'][0]);
$box->setFontColor(new Color($rgb[0], $rgb[1], $rgb[2]));

if($rowInput['input_text_centred'][0] != 0){
$box->setBox(0,$rowInput['input_text_margin_top'][0], $cover_width, $cover_height);
$box->setTextAlign('center', 'top');
}else{
if($text_angle>0){
//$rowInput['input_text_margin_top'][0] = $rowInput['input_text_margin_top'][0] - 4;
//;$rowInput['input_text_margin_left'][0] = $rowInput['input_text_margin_left'][0] + 18;
}
$box->setBox($rowInput['input_text_margin_left'][0], $rowInput['input_text_margin_top'][0], $cover_width, $cover_height);
}

$box->setLineHeight(1.4);
$box->setAngle(-$text_angle);
$box->setTextShadow(new Color($rgb2[0], $rgb2[1], $rgb2[2]), $rowInput['input_text_shadow_x'][0], $rowInput['input_text_shadow_y'][0]);
$box->draw($text);

}

$upload_dir = wp_upload_dir();
$randomName = "Generated-Cover-".date("m.d.y").'-'.time();
imagejpeg($im, $upload_dir['path']."/$randomName.jpg", 100);
if(file_exists($upload_dir['path']."/$randomName.jpg")==true){
//echo 'File exists.';
}else{
echo 'Cant generate.';
}
die($upload_dir['url']."/$randomName.jpg");
}
*/