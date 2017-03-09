<?php
/**
 * @wordpress-plugin
 * Plugin Name:       WordPress Social Cover Generator v1.0
 * Plugin URI:        http://plugin-name.com/
 * Description:       A plugin.
 * Version:           1.0.0
 * Author:            Bozhidar Slaveykov
 * Author URI:        http://
 * License:           MIT
 */
 
 /**
 * Category Widget Version
 */
define('BC_CW_PLUGIN_VERSION', '1.0.0');

/**
 * Root Path
 */
define('BC_CW_PLUGIN', __FILE__);
/**
 * Plugin Base Name
 */
define('BC_CW_PLUGIN_BASENAME', plugin_basename(BC_CW_PLUGIN));
/**
 * Plugin Name
 */
define('BC_CW_PLUGIN_NAME', trim(dirname(BC_CW_PLUGIN_BASENAME), '/'));



function register_font_Menu(){
    add_menu_page( 
        __( 'SCG Fonts', 'textdomain' ),
        'SCG Fonts',
        'manage_options',
        'scg-fonts',
        'html_fonts_page',
        'dashicons-translation',
        6
    ); 
}
add_action('admin_menu', 'register_font_Menu');
 
function html_fonts_page(){
	//$html = 'test';
	//esc_html_e($html, 'textdomain');  
	
	
	//var_dump(getAllFonts());
	
   ?>
   
   <div class="wrap">
   <div id="icon-options-general" class="icon32">
   <br>
   </div>
   <h2>Social Cover Generator</h2>
   </div>
   
<?php
}


add_filter('the_content', 'featured_image_before_content');

function featured_image_before_content($content) {
	
	if (is_singular('post') && has_post_thumbnail()){
		$thumbnail = get_the_post_thumbnail();
		//$content = $thumbnail . $content;

	}

	return $content;
}


// Our custom post type function
function create_post_type_covers() {
	register_post_type('scg-covers',
	// CPT Options
		array(
			'labels' => array(
				'name' => __('SCG Covers'),
				'singular_name' => __('Cover'),
				'all_items' => __('Show all covers'),
				'add_new' => __('Add new cover'),
				'edit' => __( 'Edit cover' ),
				'edit_item' => __( 'Edit cover' ),
			),
			'public' => true,
			'publicly_queryable' => true,
			'query_var' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'covers'),
			'taxonomies' => array('scg-cats'),
			'supports' => array('title', 'editor', 'thumbnail'),
			'register_meta_box_cb' => 'add_covers_metaboxes'  
			
		)
	);
}
add_action('init', 'create_post_type_covers');

function add_covers_metaboxes() {
	add_meta_box('wpt_events_location', 'SCG Fields', 'wpt_events_location', 'scg-covers', 'normal', 'high');
}

require_once("functions.php");
require_once("ajax_work.php");
require_once("editor.php");
require_once("generator.php");
require_once("fields.php");
require_once("fonts.php");
require_once("taxonomy.php");
require_once("widget.php");