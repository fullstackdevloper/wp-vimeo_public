<?php

/**
 * Plugin Name: Wp Vimeo Upload
 * Plugin URI: https://example.com/
 * Description: A wordpress plugin to upload videos to vimeo and listing
 * Version: 1.0.0
 * Author: Example
 * Author URI: https://profiles.wordpress.org/wp-vimeo/
 * Text Domain: wp-vimeo
 * Domain Path: /i18n/languages/
 *
 * @package wp-vimeo
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define ACTIVITYHUB_PLUGIN_FILE.
if (!defined('WP_VIMEO_PLUGIN_FILE')) {
    define('WP_VIMEO_PLUGIN_FILE', __FILE__);
}

// Include the main ActivityHub class.
if (!class_exists('WpVimeo')) {
    include_once dirname(__FILE__) . '/inc/classWpVimeo.php';
}

/**
 * Main instance of WpVimeo.
 *
 * Returns the main instance of WpVimeo.
 *
 * @since  1.0.0
 * @return WpVimeo
 */
function WpVimeo() {
    return WpVimeo::instance();
}

// Global for backwards compatibility.
$GLOBALS['WpVimeo'] = WpVimeo();
