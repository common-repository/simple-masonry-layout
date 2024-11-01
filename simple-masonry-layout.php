<?php
/*
 * Plugin Name: Simple Masonry Layout
 * Plugin URI: http://wordpress.org/plugins/simple-masonry-layout/
 * Description: With simple shortcode, Masonry Layout in action.
 * Author: Raju Tako
 * Version: 2.0.2
 * Author URI: https://www.linkedin.com/in/raju-tako-b1067153/
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* Plugin version */
define( 'SIMPLEMASONRYLAYOUT', '2.0.1' );

define( 'SIMPLEMASONRYLAYOUT_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'SIMPLEMASONRYLAYOUT_DIR_URL', plugin_dir_url( __FILE__ ) );

require_once SIMPLEMASONRYLAYOUT_DIR_PATH . 'includes/class.simple.masonry.admin.php';
require_once SIMPLEMASONRYLAYOUT_DIR_PATH . 'includes/class.simple.masonry.front.php';

// Check if the required classes exist before using them
if ( class_exists( 'SimpleMasonryLayout\SimpleMasonryAdmin' ) ) {
    SimpleMasonryLayout\SimpleMasonryAdmin::getInstance();
}

if ( class_exists( 'SimpleMasonryLayout\\SimpleMasonryFront' ) ) {
    SimpleMasonryLayout\SimpleMasonryFront::getInstance();
}
