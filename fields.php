<?php
// Our custom post type function
function create_post_type_fields() {

register_post_type('fields',
	// CPT Options
		array(
			'labels' => array(
				'name' => __('SCG Fields'),
				'singular_name' => __('Field'),
				'all_items' => __('Show all fields'),
				'add_new' => __('Add new field'),
				'edit' => __( 'Edit field' ),
				'edit_item' => __('Edit field'),
			),
			'public' => false,
			'publicly_queriable' => true,
			'show_ui' => true,
			'exclude_from_search' => true,
			'show_in_nav_menus' => false, 
			'has_archive' => false,
			'rewrite' => false,
			'taxonomies' => array('scg_fields'),
			'supports' => array('title'),
			'register_meta_box_cb' => 'custom_fields_meta_box' 
			
		)
	);
	
}
add_action('init', 'create_post_type_fields');






function custom_fields_meta_box($post) { 
	add_meta_box(
	'cf_meta_box_1', 
	esc_html__('Custom field settings', 'text-domain'),
	'cfmb_calback',
	'fields',
	'advanced',
	'high');
}
//add_action('add_meta_boxes_post', 'custom_fields_meta_box', 10, 3);
 
function cfmb_calback($post) {
	
	global $post;
	$custom = get_post_custom($post->ID);
	
	echo '
	<style>
	#post-body #normal-sortables {
		min-height: 0px;
	}
	.width99 
	{width:99%;
	}
	</style>
	';
	
	$wpb_all_query = new WP_Query(array('post_type'=>'scg-covers', 'post_status'=>'publish', 'posts_per_page'=>-1));
	?>
	
	<p>
		<label>Cover:</label><br />
		<select name="cover_id" id="cover_id" class="width99">
		<?php if ($wpb_all_query->have_posts()) : ?>
		<?php while ($wpb_all_query->have_posts()) : $wpb_all_query->the_post(); ?>
		<option value="<?php the_id(); ?>"><?php the_title(); ?></option>
		 <?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
		<?php else : ?>
		<?php endif; ?>
		</select>
	</p>
	
	<p>
		<label>Name:</label><br />
		<input type="text" id="input_name" name="input_name" value="<?= @$custom["input_name"][0] ?>" class="width99" />
	</p>
	<p>
		<label>Max Chars:</label><br />
		<input type="number" name="input_max_chars" value="<?= @$custom["input_max_chars"][0] ?>" class="width99" />
	</p>
	<p>
		<label>Text example:</label><br />
		<input type="text" name="input_text_example" value="<?= @$custom["input_text_example"][0] ?>" class="width99" />
	</p>
	<p>
		<label>Type:</label><br />
		<select name="input_type" class="width99">
		<option value="text">Text</option>
		<option value="textarea">Textarea</option>
		<option value="hidden">Static Text</option>
		</select>
	</p>
	<p>
	<label>Centred text:</label>
	<br />
	<select name="input_text_centred" class="width99" >
	<option value="0">No</option>
	<option value="1">Yes</option>
	</select>
	</p>
	<p>
		<label>Order:</label><br />
		<input type="text" name="input_order" value="<?= @$custom["input_order"][0] ?>" class="width99" />
	</p>
	<script>
	jQuery(document).ready(function($) {

	function htmlDecode(value) {
		return $("<textarea/>").html(value).text();
	}

	function htmlEncode(value) {
		return $('<textarea/>').text(value).html();
	}
		
	var cover_id = new Array();
	<?php if ($wpb_all_query->have_posts()) : ?>
	<?php while ($wpb_all_query->have_posts()) : $wpb_all_query->the_post(); ?>
	cover_id[<?php the_id(); ?>] = htmlDecode('<?php (the_title()); ?>');
	 <?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
	<?php endif; ?>

		
	$('#input_name').keyup(function() {
		var selected_cover_id = $("#cover_id").attr('value');
		$('#title').val(cover_id[selected_cover_id] + " - " + $(this).val());
		$("#title-prompt-text").remove();
		// $(this).val() // get the current value of the input field.
	});

	$('#cover_id').change(function() {
		var selected_cover_id = $("#cover_id").attr('value');
		$('#title').val(cover_id[selected_cover_id] + " - " + $("#input_name").attr('value'));
		$("#title-prompt-text").remove();
		// $(this).val() // get the current value of the input field.
	});
	
	});
	</script>
<?php
}

function save_cafe_custom_fields(){
	
  global $post;
 
  if ($post)
  {
  	update_post_meta($post->ID, "input_order", @$_POST[input_order]);
  	update_post_meta($post->ID, "cover_id", @$_POST["cover_id"]);
	update_post_meta($post->ID, "input_name", @$_POST["input_name"]);
    update_post_meta($post->ID, "input_max_chars", (int) @$_POST["input_max_chars"]);
    update_post_meta($post->ID, "input_text_example", @$_POST["input_text_example"]);
    update_post_meta($post->ID, "input_type", @$_POST["input_type"]);
    update_post_meta($post->ID, "input_text_centred", @$_POST["input_text_centred"]);
  }
  
}
//add_action('admin_init', 'custom_fields_meta_box');
add_action('save_post', 'save_cafe_custom_fields' );