<?php
/**
 * Easy SwipeBox Class
 */
class easySwipeBox {

	/***********************
	    REGISTER SCRIPTS
	 ***********************/

	public static function register_scripts() {	
	
	    if ( is_admin() ) return;
	    
		// first get rid of previously registered variants of jquery.swipebox by other plugins or themes
		wp_deregister_script('swipebox');
		wp_deregister_script('jquery.swipebox');
		wp_deregister_script('jquery_swipebox');
		wp_deregister_script('jquery-swipebox');

		// register main swipebox script
		if ( defined('WP_DEBUG') && true == WP_DEBUG )
			wp_register_script('jquery-swipebox', EASY_SWIPEBOX_PLUGINURL.'assets/js/jquery.swipebox.js', array('jquery'), EASY_SWIPEBOX_VERSION, true);
		else
			wp_register_script('jquery-swipebox', EASY_SWIPEBOX_PLUGINURL.'assets/js/jquery.swipebox.min.js', array('jquery'), EASY_SWIPEBOX_VERSION, true);
	}

	public static function main_script() {

		wp_register_script('jquery-swipebox-init', EASY_SWIPEBOX_PLUGINURL.'assets/js/jquery.init.js', array('jquery'), EASY_SWIPEBOX_VERSION, true);
		
	}

	public static function enqueue_footer_scripts() {
		wp_enqueue_script('jquery-swipebox-init');
		wp_enqueue_script('jquery-swipebox');
	}

	public static function enqueue_styles() {

		// register style
		wp_dequeue_style('swipebox');
		if ( defined('WP_DEBUG') && true == WP_DEBUG )
			wp_enqueue_style('swipebox', EASY_SWIPEBOX_PLUGINURL.'assets/css/swipebox.css', false, EASY_SWIPEBOX_VERSION, 'screen');
		else
			wp_enqueue_style('swipebox', EASY_SWIPEBOX_PLUGINURL.'assets/css/swipebox.min.css', false, EASY_SWIPEBOX_VERSION, 'screen');
	}


	/**********************
	         RUN
	 **********************/

	static function run() {

		// HOOKS //
		add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_styles'), 999);
		add_action('wp_print_scripts', array(__CLASS__, 'main_script'), 999);
		add_action('wp_print_scripts', array(__CLASS__, 'register_scripts'), 999);
		add_action('wp_footer', array(__CLASS__, 'enqueue_footer_scripts'));
	}

}
