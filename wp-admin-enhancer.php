<?php
/**
 * Plugin Name: WP Admin Enhancer
 * Description: Plugin to enhance the look and feel of WordPress admin pages.
 * Version: 1.0
 * Author: Devagus
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'WAE_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'WAE_DIR_URL', plugin_dir_url( __FILE__ ) );

require_once WAE_DIR_PATH . 'core/ModuleBootstrap.php';


new \WpAdminEnhancer\Core\ModuleBootstrap();
