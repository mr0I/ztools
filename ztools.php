<?php
/**
 * Plugin Name: Zeus Tools
 * Plugin URI: https://sisoog.com/
 * Description: Zeus Light Tools For Sisoog WebSite.
 * Version: 1.6
 * Author: <a href="http://sisoog.com/user/zeus">Zeus</a>
 * Author URI: https://www.sisoog.com
 * Text Domain: ztools
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) )
{
	die( 'Invalid request.' );
}

require_once 'planet.php';
require_once 'zaparat.php';
//add_action( 'the_content', 'my_thank_you_text' );
//function my_thank_you_text ( $content )
//{
//    return $content .= '<p>Thank you for reading!</p>';
//}


// for Translation //
add_action('plugins_loaded', function(){
	load_plugin_textdomain('ztools', false, basename(plugin_dir_path(__FILE__)) . '/languages/');
});


define('PLANET_JS', plugin_dir_url(__FILE__) . 'js/');
define('PLANET_CSS', plugin_dir_url(__FILE__) . 'css/');


 add_action( 'wp_enqueue_scripts', function(){
 	// scripts
 	wp_enqueue_script('chosen_js', PLANET_JS.'chosen.js');
 	wp_enqueue_script('myscript', PLANET_JS.'script.js', array('jquery', 'media-upload'));
 	wp_localize_script( 'myscript', 'PlanetAjax', array(
 	 	'ajaxurl' => admin_url( 'admin-ajax.php' ),
 	 	'security' => wp_create_nonce( '(H+MbPeShVmYq3t6' )
 	 ));
 	 wp_enqueue_media();
 	// styles
 	wp_enqueue_style( 'chosen_css', PLANET_CSS . 'chosen/chosen.css');
 	wp_enqueue_style( 'styles', PLANET_CSS . 'ztools-styles.css');
 });


 add_action( 'admin_enqueue_scripts', function(){
	 // scripts
	 wp_enqueue_script('adminScript', PLANET_JS.'admin/adminScripts.js', array('jquery', 'media-upload'));
	 wp_localize_script( 'adminScript', 'PlanetAdminAjax', array(
		 'ajaxurl' => admin_url( 'admin-ajax.php' ),
		 'security' => wp_create_nonce( 'ZIabP5Vi&oU$a^' ),
		 'REQUEST_TIMEOUT' => 10000,
	 ));
 	// styles
 	wp_enqueue_style( 'adminStyles', PLANET_CSS . 'admin/adminStyles.css');
 });



