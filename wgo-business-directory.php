<?php
/*
Plugin Name: WGO Bussines directory
Plugin URI: http://http://www.whatsgoingon.es/wgo-bussines-directory
Description: WGO Business Directory Plugin for WordPress, is designed to be used for managing a WordPress business directory listing service on your website.
Author: Oscar Andreu
Version: 0.2
Author URI: http://www.whatsgoingon.es
*/
/*  Copyright 2012 Oscar Andreu (oscarandreu@whatsgoingon.es)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/


// ==================
// = Plugin Version =
// ==================
define( 'WGO_BD_VERSION', '0.8' );

// ====================
// = Database Version =
// ====================
define( 'WGO_BD_DB_VERSION', 2 );


define('WGO_BD_BASE_DIR', __DIR__.'/');
define('WGO_BD_BASE_URL', plugin_dir_url( __FILE__ ).'/');

define('WGO_BD_IMAGES', WGO_BD_BASE_URL . 'images/');
define('WGO_BD_CSS', WGO_BD_BASE_URL . 'css/');
define('WGO_BD_CLASSES', WGO_BD_BASE_DIR . 'classes/');
define('WGO_BD_INC', WGO_BD_BASE_DIR . 'inc/');
define('WGO_BD_JS', WGO_BD_BASE_URL . 'js/');

include_once(WGO_BD_CLASSES.'wgo_db_BusinessClass.php');
include_once(WGO_BD_CLASSES.'wgo_db_BusinessGuiClass.php');
include_once(WGO_BD_CLASSES.'wgo_db_CityClass.php');
include_once(WGO_BD_CLASSES.'wgo_db_PackageClass.php');
include_once(WGO_BD_CLASSES.'wgo_db_CategoryClass.php');
include_once(WGO_BD_CLASSES.'wgo_db_Utils.php');
include_once(WGO_BD_CLASSES.'wgo_db_GuiHelper.php');

include_once(WGO_BD_INC.'functions.php');
include_once(WGO_BD_INC.'inc_install_func.php');
include_once(WGO_BD_INC.'inc_admin_menu_hooks.php');
include_once(WGO_BD_INC.'inc_wgo_settings_page.php');
include_once(WGO_BD_INC.'inc_wgo_packages_page.php');
include_once(WGO_BD_INC.'inc_wgo_categories_page.php');
include_once(WGO_BD_INC.'inc_wgo_listings_page.php');
include_once(WGO_BD_INC.'inc_wgo_edit_listing_page.php');
include_once(WGO_BD_INC.'inc_wgo_edit_images_page.php');
include_once(WGO_BD_INC.'inc_display_listings.php');
include_once(WGO_BD_INC.'inc_thankyou_page_function.php');
include_once(WGO_BD_INC.'inc_wgo_display_weatherMap.php');

function includeAdminCSS() {
	wp_enqueue_style('wgo-backend.css', WGO_BD_CSS . 'wgo-backend.css');
	wp_enqueue_style('thickbox');
	
	wp_enqueue_script("jquery");
	wp_enqueue_script("jquery-ui-core");
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('my-upload'); 
}

function includePublicCSS() {
	wp_enqueue_style('wgo-frontend.css', WGO_BD_CSS.'wgo-frontend.css');
	//wp_enqueue_style('styles.css', WGO_BD_CSS.'styles.css');
	wp_enqueue_style('jquery-ui-1.8.18.custom.css', WGO_BD_CSS.'jquery-ui-1.8.18.custom.css');
	

	wp_enqueue_script("jquery");
	wp_enqueue_script("jquery-ui-dialog");
	
	echo '<script src="'.WGO_BD_BASE_URL.'js/slider.js"></script>';
	
	wp_enqueue_script("jquery");
	wp_enqueue_script("jquery-ui-dialog");

	wp_enqueue_script("jquery");
	wp_enqueue_script("jquery-ui-dialog");	

	wp_clear_scheduled_hook('load_city_logos_hook');	
	if ( !wp_next_scheduled( 'load_city_logos_hook' ) ) {
		wp_schedule_event(time(), 'hourly', 'load_city_logos_hook');
		CityClass::getWeatherLogos();
	} 
}


function load_city_logos_hook(){
	CityClass::getWeatherLogos();
}

add_action( 'load_city_logos_hook', 'load_city_logos_hook' );
add_action('admin_head','includeAdminCSS');
add_action('wp_head','includePublicCSS');

register_activation_hook(__FILE__,'wgo_db_install');

function register_wgo_settings() { // whitelist options
	register_setting( 'wgo_options_group', 'wgo_use_map' );
	register_setting( 'wgo_options_group', 'wgo_ppbizname' );
	register_setting( 'wgo_options_group', 'wgo_ppemail' );
	register_setting( 'wgo_options_group', 'wgo_ppcurrency' );
	register_setting( 'wgo_options_group', 'wgo_state_province_field' );
	register_setting( 'wgo_options_group', 'wgo_buttimg' );
	register_setting( 'wgo_options_group', 'wgo_butttext' );
	register_setting( 'wgo_options_group', 'wgo_page_id' );
	register_setting( 'wgo_options_group', 'wgo_step_one_message' );
	register_setting( 'wgo_options_group', 'wgo_step_two_message' );
	register_setting( 'wgo_options_group', 'wgo_thank_you_message' );
	register_setting( 'wgo_options_group', 'wgo_bail_message' );
}

add_shortcode('wgo-listings','display_wgo_listings');
add_shortcode('wgo-form','display_wgo_form');
add_shortcode('wgo-substatus','thankyou_page_function');
add_shortcode('wgo-weather_map','wgo_display_weather_map');
/*legacy shortcodes*/
add_shortcode('gd-listings','display_wgo_listings');
add_shortcode('gd-form','display_wgo_form');
add_shortcode('gd-substatus','thankyou_page_function');
add_shortcode('gd-weather_map','wgo_display_weather_map');



if (isset($_GET['page']) && $_GET['page'] == 'my_plugin_page') {
	add_action('admin_print_scripts', 'my_admin_scripts');
	add_action('admin_print_styles', 'my_admin_styles');
}