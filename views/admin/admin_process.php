<?php


add_action("admin_init", function(){

	add_settings_section('Ztools_settings_options', __('Ztools Settings', 'ztools'), null, 'Ztools_setting');

	add_settings_field('Ztools_setting_header1', __('User Panel Settings', 'ztools'), 'Ztools_setting_header1_callback'
		, 'Ztools_setting', 'Ztools_settings_options');
	add_settings_field('Ztools_setting_add_planet_url', __('Add Planet Url', 'ztools'), 'Ztools_setting_add_planet_url_callback'
		, 'Ztools_setting', 'Ztools_settings_options');
	add_settings_field('Ztools_setting_edit_planet_url', __('Edit Planet Url', 'ztools'), 'Ztools_setting_edit_planet_url_callback'
		, 'Ztools_setting', 'Ztools_settings_options');
	add_settings_field('Ztools_setting_show_planets_url', __('Show Planets Url', 'ztools'), 'Ztools_setting_show_planets_url_callback'
		, 'Ztools_setting', 'Ztools_settings_options');
	add_settings_field('Ztools_setting_posts_pp', __('Posts Per Page', 'ztools'), 'Ztools_setting_posts_pp_callback'
		, 'Ztools_setting', 'Ztools_settings_options');
/////////////////////////////////
	add_settings_field('Ztools_setting_header2', __('Planet Settings', 'ztools'), 'Ztools_setting_header2_callback'
		, 'Ztools_setting', 'Ztools_settings_options');
	add_settings_field('Ztools_excerpt_len', __('excerpt Length', 'ztools'), 'Ztools_excerpt_len_callback'
		, 'Ztools_setting', 'Ztools_settings_options');
	add_settings_field('Ztools_planet_posts_pp', __('Posts Per Page', 'ztools'), 'Ztools_planet_posts_pp_callback'
		, 'Ztools_setting', 'Ztools_settings_options');
	add_settings_field('Ztools_planet_posts_cat_pp', __('Posts Per Page (for category)', 'ztools'), 'Ztools_planet_posts_cat_pp_callback'
		, 'Ztools_setting', 'Ztools_settings_options');
	add_settings_field('Ztools_planet_login_url', __('Login Url', 'ztools'), 'Ztools_planet_login_url_callback'
		, 'Ztools_setting', 'Ztools_settings_options');

/////////////////////////////////
/////////////////////////////////

	add_settings_section('exchange_rate_settings_options', __('Exchange Rate Settings', 'ztools'), null, 'exchange_rate_settings');
	add_settings_field('Ztools_exrate_dollar', __('Dollar Value', 'ztools'), 'Ztools_exrate_dollar_callback'
		, 'exchange_rate_settings', 'exchange_rate_settings_options');
	add_settings_field('Ztools_exrate_yuan', __('Yuan Value', 'ztools'), 'Ztools_exrate_yuan_callback'
		, 'exchange_rate_settings', 'exchange_rate_settings_options');



	register_setting('Ztools_settings_options', 'ztools_add_planet_url', 'sanitize_text_field');
	register_setting('Ztools_settings_options', 'ztools_edit_planet_url', 'sanitize_text_field');
	register_setting('Ztools_settings_options', 'ztools_show_planets_url', 'sanitize_text_field');
	register_setting('Ztools_settings_options', 'ztools_post_pp', 'sanitize_text_field');
	/////////////////////////////////
	register_setting('Ztools_settings_options', 'ztools_excerptLen', 'sanitize_text_field');
	register_setting('Ztools_settings_options', 'ztools_planet_postPP', 'sanitize_text_field');
	register_setting('Ztools_settings_options', 'ztools_planet_cat_postPP', 'sanitize_text_field');
	register_setting('Ztools_settings_options', 'ztools_planet_loginUrl', 'sanitize_text_field');
	/////////////////////////////////
	register_setting('exchange_rate_settings_options', 'Ztools_exrate_dollar', 'sanitize_text_field');
	register_setting('exchange_rate_settings_options', 'Ztools_exrate_yuan', 'sanitize_text_field');
});


function Ztools_setting_header1_callback() {
	echo '</br><hr>';
}
function Ztools_setting_add_planet_url_callback(){
	echo '<input class="ltr left-align" type="url" name="ztools_add_planet_url" id="ztools_add_planet_url" 
	value="' . get_option('ztools_add_planet_url','https://sisoog.com/planet-add') . '" 
	 style="max-width: 100%;min-width: 400px" required/>';
}
function Ztools_setting_edit_planet_url_callback(){
	echo '<input class="ltr left-align" type="url" name="ztools_edit_planet_url" id="ztools_edit_planet_url" 
	value="' . get_option('ztools_edit_planet_url','https://sisoog.com/planet-edit') . '" 
	 style="max-width: 100%;min-width: 400px" required/>';
}
function Ztools_setting_show_planets_url_callback(){
	echo '<input class="ltr left-align" type="url" name="ztools_show_planets_url" id="ztools_show_planets_url" 
	value="' . get_option('ztools_show_planets_url', 'https://sisoog.com/my_planet/') . '" 
	 style="max-width: 100%;min-width: 400px" required/>';
}
function Ztools_setting_posts_pp_callback(){
	echo '<input class="ltr left-align" type="number" name="ztools_post_pp" id="ztools_post_pp" 
	value="' . get_option('ztools_post_pp', 50) . '" 
	 style="max-width: 100%;min-width: 400px" required/>';
}
/////////////////////////////////
function Ztools_setting_header2_callback() {
	echo '</br><hr>';
}
function Ztools_excerpt_len_callback(){
	echo '<input class="ltr left-align" type="number" name="ztools_excerptLen" id="ztools_excerptLen" 
	value="' . get_option('ztools_excerptLen', 45) . '" 
	  style="max-width: 100%;min-width: 400px" required/>';
}
function Ztools_planet_posts_pp_callback(){
	echo '<input class="ltr left-align" type="number" name="ztools_planet_postPP" id="ztools_planet_postPP" 
	value="' . get_option('ztools_planet_postPP', 50) . '" 
	 style="max-width: 100%;min-width: 400px" required/>';
}
function Ztools_planet_posts_cat_pp_callback(){
	echo '<input class="ltr left-align" type="number" name="ztools_planet_cat_postPP" id="ztools_planet_cat_postPP" 
	value="' . get_option('ztools_planet_cat_postPP', 50) . '" 
	 style="max-width: 100%;min-width: 400px" required/>';
}
function Ztools_planet_login_url_callback(){
	echo '<input class="ltr left-align" type="url" name="ztools_planet_loginUrl" id="ztools_planet_loginUrl" 
	value="' . get_option('ztools_planet_loginUrl', 'https://sisoog.com/login/') . '" 
	 style="max-width: 100%;min-width: 400px" required/>';
}
/////////////////////////////////
function Ztools_exrate_dollar_callback(){
	echo '<input class="ltr left-align" type="text" name="Ztools_exrate_dollar" id="Ztools_exrate_dollar" 
	value="' . get_option('Ztools_exrate_dollar', 25000) . '" 
	  style="max-width: 100%;min-width: 400px" required/>';
}
function Ztools_exrate_yuan_callback(){
	echo '<input class="ltr left-align" type="text" name="Ztools_exrate_yuan" id="Ztools_exrate_yuan" 
	value="' . get_option('Ztools_exrate_yuan', 3900) . '" 
	  style="max-width: 100%;min-width: 400px" required/>';
}

