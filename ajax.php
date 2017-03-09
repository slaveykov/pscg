<?php
/**
 * WordPress Ajax Process Execution
 *
 * @package WordPress
 * @subpackage Administration
 *
 * @link https://codex.wordpress.org/AJAX_in_Plugins
 */

/**
 * Executing Ajax process.
 *
 * @since 2.1.0
 */
define( 'DOING_AJAX', true );
if ( ! defined( 'WP_ADMIN' ) ) {
	define( 'WP_ADMIN', true );
}

/** Load WordPress Bootstrap */
$main_directory =  dirname(__FILE__);
$main_directory = str_replace("\wp-content\plugins\phpscg","",$main_directory);
$main_directory = str_replace("/wp-content/plugins/phpscg","",$main_directory);

require_once( $main_directory. '/wp-load.php' );

/** Allow for cross-domain requests (from the front end). */
send_origin_headers();

// Require an action parameter
if ( empty( $_REQUEST['action'] ) )
	die('0');

/** Load WordPress Administration APIs */
require_once( ABSPATH . 'wp-admin/includes/admin.php' );

/** Load Ajax Handlers for WordPress Core */
require_once( ABSPATH . 'wp-admin/includes/ajax-actions.php' );

@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
@header( 'X-Robots-Tag: noindex' );

send_nosniff_header();
nocache_headers();

/** This action is documented in wp-admin/admin.php */
do_action( 'admin_init' );

do_action('scg_ajax_' . $_REQUEST['ajax_action']);

// Default status
die('0');