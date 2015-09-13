<?php

/**
 * Easy SwipeBox Class
 */
class easySwipeBox
{
    
    /***********************
    REGISTER SCRIPTS
    ***********************/
    public static function easySwipeBox_register_scripts() {
        
        if (is_admin()) return;
        
        // first get rid of previously registered variants of jquery.swipebox by other plugins or themes
        wp_deregister_script('swipebox');
        wp_deregister_script('jquery.swipebox');
        wp_deregister_script('jquery_swipebox');
        wp_deregister_script('jquery-swipebox');
        
        // register main swipebox script
        if (defined('WP_DEBUG') && true == WP_DEBUG) {
        	wp_register_script('jquery-swipebox', EASY_SWIPEBOX_PLUGINURL . 'assets/js/jquery.swipebox.js', array('jquery'), EASY_SWIPEBOX_VERSION, true);
        } else { 
        	wp_register_script('jquery-swipebox', EASY_SWIPEBOX_PLUGINURL . 'assets/js/jquery.swipebox.min.js', array('jquery'), EASY_SWIPEBOX_VERSION, true);
        }

        // Register Swipebox Init + Autodetect scripts
        wp_register_script('jquery-swipebox-init', EASY_SWIPEBOX_PLUGINURL . 'assets/js/jquery.init.js', array('jquery'), EASY_SWIPEBOX_VERSION, true);
        wp_register_script('jquery-autodetect-swipebox-image', EASY_SWIPEBOX_PLUGINURL . 'assets/js/jquery.init.image.js', array('jquery'), EASY_SWIPEBOX_VERSION, true);
        wp_register_script('jquery-autodetect-swipebox-video', EASY_SWIPEBOX_PLUGINURL . 'assets/js/jquery.init.video.js', array('jquery'), EASY_SWIPEBOX_VERSION, true);
    }
    public static function easySwipeBox_enqueue_scripts() {
        // Enqueue scripts (Swipebox + Init) 
        wp_enqueue_script('jquery-swipebox');
        wp_enqueue_script('jquery-swipebox-init');
    }

    public static function easySwipeBox_enqueue_autodetect() {
        // Set default - On plugin activation all autodetection options value is 1
        $defaults = array(
          'image' => 1,
          'video' => 1,
        );

        $easySwipeBox_autodetect = wp_parse_args(get_option('easySwipeBox_autodetect'), $defaults);

        // Enqueue autodetect image script
        if ($easySwipeBox_autodetect['image'] == 1) {
        	 wp_enqueue_script('jquery-autodetect-swipebox-image');
        }

        // Enqueue autodetect video script
        if ($easySwipeBox_autodetect['video'] == 1) {
        	 wp_enqueue_script('jquery-autodetect-swipebox-video');
        }
    }

    /***********************
    REGISTER STYLES
    ***********************/
    public static function easySwipeBox_register_style() {

        // register style
        wp_dequeue_style('swipebox');
        wp_dequeue_style('jquery.swipebox');
        wp_dequeue_style('jquery_swipebox');
        wp_dequeue_style('jquery-swipebox');

        if (defined('WP_DEBUG') && true == WP_DEBUG){
            wp_enqueue_style('swipebox', EASY_SWIPEBOX_PLUGINURL . 'assets/css/swipebox.css', false, EASY_SWIPEBOX_VERSION, 'screen');
        } else { 
            wp_enqueue_style('swipebox', EASY_SWIPEBOX_PLUGINURL . 'assets/css/swipebox.min.css', false, EASY_SWIPEBOX_VERSION, 'screen');
        }
    }
    
    /**********************
    ADMIN SETTINGS
    **********************/
    public static function easySwipeBox_create_menu() {
        
        //create new menu under settings page
        $page_title = __('Easy SwipeBox Settings', EASY_SWIPEBOX_TEXTDOMAIN);
        $menu_title = __('Easy SwipeBox', EASY_SWIPEBOX_TEXTDOMAIN);
        $capability = 'install_plugins';
        $menu_slug = 'easy-swipebox-settings';
        $function = 'easySwipeBox_plugin_settings_page';
        add_submenu_page('options-general.php', $page_title, $menu_title, $capability, $menu_slug, $function);
        
        //call register settings function
        add_action('admin_init', 'register_easySwipeBox_settings');

        function register_easySwipeBox_settings() {
            
	        //register autodetect settings
	        register_setting('easySwipeBox_options', 'easySwipeBox_autodetect');

        }

        // Define autodetect settings page options
        function easySwipeBox_plugin_settings_page() {
		?>
		<div class="wrap">
		    <h1><?php
		            _e('Easy SwipeBox Settings', EASY_SWIPEBOX_TEXTDOMAIN); ?></h1>
		    <form method="post" action="options.php">
		        <?php

		            settings_fields('easySwipeBox_options');
		            do_settings_sections('easySwipeBox_options');

                    // Set defaut values
                    $defaults = array(
                      'image' => 1,
                      'video' => 1,
                    );

                    $easySwipeBox_autodetect = wp_parse_args(get_option('easySwipeBox_autodetect'), $defaults);

				?>


                <p><?php _e('The options in this section are provided by the plugin <strong>Easy Swipebox</strong> and determines the Media Lightbox behaviour controlled by <strong><a href="http://brutaldesign.github.io/swipebox/?source=easy-swipebox-wp-plugin" target="_blank">SwipeBox</a></strong>.
', EASY_SWIPEBOX_TEXTDOMAIN); ?><br>
                <?php _e('Please, for contributions, issues or questions visit the <strong><a href="https://github.com/leopuleo/easy-swipebox" target="_blank">Github Repo</a></strong>.', EASY_SWIPEBOX_TEXTDOMAIN); ?>
                 
                <h3><?php _e('Plugin main features', EASY_SWIPEBOX_TEXTDOMAIN); ?></h3>
                <ol>
                <li><?php _e('Enqueuing of SwipeBox Javascript and CSS files. Set <code>WP_DEBUG</code> to true for the uncompressed files.', EASY_SWIPEBOX_TEXTDOMAIN); ?></li>
                <li><?php _e('Autodetection of links to images or videos. You can exclude/include media types from the autodetection section here below.', EASY_SWIPEBOX_TEXTDOMAIN); ?></li>
                </ol>

		        <table class="form-table">
		        <h3><?php _e('Autodetection', EASY_SWIPEBOX_TEXTDOMAIN); ?></h3>
		        <p><?php _e('Select one or more options, <strong>Easy SwipeBox</strong> automatically detects the media type and add <code>class="swipebox"</code> to their links.', EASY_SWIPEBOX_TEXTDOMAIN); ?><br>
                <?php _e('Otherwise, add <code>class="swipebox"</code> yourself to make the magic happen.', EASY_SWIPEBOX_TEXTDOMAIN); ?><br>
                <?php _e('By default, <strong>Easy SwipeBox</strong> detects automatically links to <strong>images</strong> (jpg / jpeg / gif / png) and <strong>videos</strong> (Youtube / Vimeo).', EASY_SWIPEBOX_TEXTDOMAIN); ?><br>
				</p>
		            <tr valign="top">
		                <th scope="row">
		                    <?php _e('Media', EASY_SWIPEBOX_TEXTDOMAIN); ?>
		                </th>
		                <td>
		                	<label>
			                	<input type="hidden" id="hidden_easySwipeBox_autodetect[image]" name="easySwipeBox_autodetect[image]" value="0" />
			                    <input id="easySwipeBox_autodetect[image]" name="easySwipeBox_autodetect[image]" type="checkbox" value="1" <?php if ($easySwipeBox_autodetect['image'] == 1) echo 'checked="checked"'; ?> />
			                    <strong><?php _e('Images', EASY_SWIPEBOX_TEXTDOMAIN); ?></strong> 
			                    <em>(<?php _e('jpg / jpeg / gif / png', EASY_SWIPEBOX_TEXTDOMAIN); ?>)</em>
			                </label><br>
		                    
		                    <label>
			                    <input type="hidden" id="hidden_easySwipeBox_autodetect[video]" name="easySwipeBox_autodetect[video]" value="0" />
			                    <input id="easySwipeBox_autodetect[video]" name="easySwipeBox_autodetect[video]" type="checkbox" value="1" <?php if ($easySwipeBox_autodetect['video'] == 1) echo 'checked="checked"'; ?> />
		                    	<strong><?php  _e('Videos', EASY_SWIPEBOX_TEXTDOMAIN); ?></strong> 
		                    	<em>(<?php _e('Youtube / Vimeo', EASY_SWIPEBOX_TEXTDOMAIN); ?>)</em>
		                    </label>
		                </td>
		            </tr>
		        </table>

		        <?php submit_button(); ?>
		    </form>
		</div>
		<?php
        }
    }
    
    /**********************
    PLUGIN LIST LINK
    **********************/
    public static function easySwipeBox_add_plugin_links($links) {
        $mylinks = array('<a href="' . admin_url('options-general.php?page=easy-swipebox-settings') . '">' . __('Settings', EASY_SWIPEBOX_TEXTDOMAIN) . '</a>',);
        return array_merge($links, $mylinks);
    }
    
    /**********************
    RUN
    **********************/
    static function run() {
        
        // HOOKS //
        add_action('wp_enqueue_scripts', array(__CLASS__, 'easySwipeBox_register_style'), 999);
        add_action('wp_print_scripts', array(__CLASS__, 'easySwipeBox_register_scripts'), 999);
        add_action('wp_footer', array(__CLASS__, 'easySwipeBox_enqueue_autodetect'));
        add_action('wp_footer', array(__CLASS__, 'easySwipeBox_enqueue_scripts'));
        add_action('admin_menu', array(__CLASS__, 'easySwipeBox_create_menu'));
        add_filter('plugin_action_links_' . EASY_SWIPEBOX_PLUGINBASENAME, array(__CLASS__, 'easySwipeBox_add_plugin_links'));
    }
}
