<?php
/**
 * Plugin Name: Your Shortcodes
 * Plugin URI: http://www.sujinc.com/
 * Description: Your Shortcodes finds all shortcodes which you can use.
 * Version: 1.0.0
 * Author: Sujin 수진 Choi
 * Author URI: http://www.sujinc.com/
 * License: GPLv2 or later
 * Text Domain: your-shortcodes
 */

if ( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

# 상수 할당
if ( !defined( 'YOURSC_PLUGIN_NAME' ) ) {
	$basename = trim( dirname( plugin_basename( __FILE__ ) ), '/' );
	if ( !is_dir( WP_PLUGIN_DIR . '/' . $basename ) ) {
		$basename = explode( '/', $basename );
		$basename = array_pop( $basename );
	}

	define( 'YOURSC_PLUGIN_NAME', $basename );
}

if ( !defined( 'YOURSC_PLUGIN_FILE_NAME' ) )
	define( 'YOURSC_PLUGIN_FILE_NAME', basename(__FILE__) );

if ( !defined( 'YOURSC_TEXTDOMAIN' ) )
	define( 'YOURSC_TEXTDOMAIN', 'shortcode-finder' );

if ( !defined( 'YOURSC_PLUGIN_DIR' ) )
	define( 'YOURSC_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . YOURSC_PLUGIN_NAME );

if ( !defined( 'YOURSC_PLUGIN_URL' ) )
	define( 'YOURSC_PLUGIN_URL', WP_PLUGIN_URL . '/' . YOURSC_PLUGIN_NAME );

if ( !defined( 'YOURSC_CLASS_DIR' ) )
	define( 'YOURSC_CLASS_DIR', YOURSC_PLUGIN_DIR . '/classes' );

if ( !defined( 'YOURSC_VIEW_DIR' ) )
	define( 'YOURSC_VIEW_DIR', YOURSC_PLUGIN_DIR . '/views' );

if ( !defined( 'YOURSC_ASSETS_URL' ) )
	define( 'YOURSC_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets' );

if ( !defined( 'YOURSC_VERSION_KEY' ) )
    define( 'YOURSC_VERSION_KEY', 'YOURSC_version' );

if ( !defined( 'YOURSC_VERSION_NUM' ) )
    define( 'YOURSC_VERSION_NUM', '1.0.0' );

# 부릉부릉
include_once( YOURSC_CLASS_DIR . '/wp-hack/abstract.admin_page.php');
include_once( YOURSC_CLASS_DIR . '/shortcode_info.php');
include_once( YOURSC_CLASS_DIR . '/admin-page.php');

include_once( YOURSC_CLASS_DIR . '/init.php');
YOURSC_init::initialize();
