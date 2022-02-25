<?php
/*
* Plugin Name: Comment Rating
* Description: test plugin
* Version: 1.0.0
* Author: reza hajizade
* Author URI: rezahajizade.com

* Requires PHP: 5.6
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * plugin admin side
 */
require plugin_dir_path( __FILE__ ) . 'admin/class-comment-rating-admin.php';

/**
 * plugin comment rate system and API
 */
require plugin_dir_path( __FILE__ ) . 'includes/class_comment_rate.php';