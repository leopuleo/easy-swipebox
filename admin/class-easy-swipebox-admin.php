<?php
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package       EasySwipeBox
 * @subpackage    EasySwipeBox/includes
 * @author     		leopuleo
 */
class Easy_Swipebox_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $options_autodetect;

	private $options_gallery;

	private $options_lightbox;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $options_autodetect, $options_gallery, $options_lightbox )  {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->options_autodetect = $options_autodetect;
		$this->options_gallery = $options_gallery;
		$this->options_lightbox = $options_lightbox;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.1
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.1
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-swipebox-admin.js', array( 'wp-color-picker' ), $this->version, false );

	}

	/**
	 * Add menu page
	 *
	 * @since    1.1
	 */

	public function add_setting_page() {

	    add_submenu_page(
	    	'options-general.php',
	    	apply_filters( $this->plugin_name . '-settings-page-title', __( 'Easy SwipeBox Settings', $this->plugin_name )),
	    	apply_filters($this->plugin_name . '-settings-page-title', __('Easy SwipeBox', $this->plugin_name)),
	    	'install_plugins',
	    	$this->plugin_name . '-settings',
	    	array( $this, 'easySwipeBox_plugin_settings_page' )
	    );
  	}

  	public function easy_swipebox_settings_init() {

  		// Register Settings
		register_setting('easySwipeBox_autodetect', 'easySwipeBox_autodetect', array($this, 'sanitize_autodetect'));
		register_setting('easySwipeBox_lightbox', 'easySwipeBox_lightbox', array($this, 'sanitize_lightbox'));
		register_setting('easySwipeBox_gallery', 'easySwipeBox_gallery', array($this, 'sanitize_gallery'));
		register_setting('easySwipeBox_overview', 'easySwipeBox_overview');

        // Section: General Settings
		add_settings_section(
			'general_section',
			__( 'Autodetect', $this->plugin_name ),
			array( $this, 'general_section_render' ),
			'easySwipeBox_autodetect'
		);

		// Section: Lightbox Settings
		add_settings_section(
			'lightbox_section',
			__( 'Lightbox settings', $this->plugin_name ),
			array( $this, 'lightbox_section_render' ),
			'easySwipeBox_lightbox'
		);

		 // Section: Gallery Settings
		add_settings_section(
			'gallery_section',
			__( 'Gallery settings', $this->plugin_name ),
			array( $this, 'gallery_section_render' ),
			'easySwipeBox_gallery'
		);

		// Section: Overview
		add_settings_section(
			'description_section',
			__( 'Overview', $this->plugin_name ),
			array( $this, 'description_section_render' ),
			'easySwipeBox_overview'
		);

		// Field: General Settings -> Autodetect Image
		add_settings_field(
			'autodetect_image',
			__( 'Image links', $this->plugin_name ),
			array( $this, 'autodetect_image_render' ),
			'easySwipeBox_autodetect',
			'general_section'
		);

		// Field: General Settings -> Autodetect Video
		add_settings_field(
			'autodetect_video',
			__( 'Video links', $this->plugin_name ),
			array( $this, 'autodetect_video_render' ),
			'easySwipeBox_autodetect',
			'general_section'
		);

		// Field: General Settings -> Autodetect Exclude
		add_settings_field(
			'autodetect_exclude',
			__( 'Exclude links', $this->plugin_name ),
			array( $this, 'autodetect_exclude_render' ),
			'easySwipeBox_autodetect',
			'general_section'
		);

		// Field: Lightbox Settings -> Animation
		add_settings_field(
			'animation',
			__( 'Animation type', $this->plugin_name ),
			array( $this, 'animation_render' ),
			'easySwipeBox_lightbox',
			'lightbox_section'
		);

		// Field: Lightbox Settings -> SVG
		add_settings_field(
			'svg',
			__( 'Use SVG Icons', $this->plugin_name ),
			array( $this, 'svg_render' ),
			'easySwipeBox_lightbox',
			'lightbox_section'
		);

		// Field: Lightbox Settings -> Navigation Mobile
		add_settings_field(
			'remove_navigation_mobile',
			__( 'Navigation bar', $this->plugin_name ),
			array( $this, 'remove_navigation_mobile_render' ),
			'easySwipeBox_lightbox',
			'lightbox_section'
		);

		// Field: Lightbox Settings -> Close Button Mobile
		add_settings_field(
			'remove_close_button_mobile',
			__( 'Close button', $this->plugin_name ),
			array( $this, 'remove_close_button_mobile_render' ),
			'easySwipeBox_lightbox',
			'lightbox_section'
		);

		// Field: Lightbox Settings -> Hide Bar Delay
		add_settings_field(
			'hide_bars_delay',
			__( 'Hide Bars', $this->plugin_name ),
			array( $this, 'hide_bars_delay_render' ),
			'easySwipeBox_lightbox',
			'lightbox_section'
		);

		// Field: Lightbox Settings -> Video Max Width
		add_settings_field(
			'video_max_width',
			__( 'Video max width', $this->plugin_name ),
			array( $this, 'video_max_width_render' ),
			'easySwipeBox_lightbox',
			'lightbox_section'
		);

		// Field: Lightbox Settings -> Vimeo Color
		add_settings_field(
			'vimeo_color',
			__( 'Vimeo controllers color', $this->plugin_name ),
			array( $this, 'vimeo_color_render' ),
			'easySwipeBox_lightbox',
			'lightbox_section'
		);

		// Field: Lightbox Settings -> Loop at the end
		add_settings_field(
			'loop_at_end',
			__( 'Loop at the end', $this->plugin_name ),
			array( $this, 'loop_at_end_render' ),
			'easySwipeBox_lightbox',
			'lightbox_section'
		);

		// Field: Lightbox Settings -> Autoplay Video
		add_settings_field(
			'autoplay_videos',
			__( 'Autoplay videos', $this->plugin_name ),
			array( $this, 'autoplay_videos_render' ),
			'easySwipeBox_lightbox',
			'lightbox_section'
		);

		// Field: General Settings -> Gallery rel
		add_settings_field(
			'gallery_rel',
			__( 'Group images', $this->plugin_name ),
			array( $this, 'gallery_rel_render' ),
			'easySwipeBox_gallery',
			'gallery_section'
		);

	}


	// Section: General Setting
	public function general_section_render(  ) {
		?>
			<p>
				<?php _e('Select one or more options, <strong>Easy SwipeBox</strong> automatically detects the media type and add <code>class="swipebox"</code> to their links.', $this->plugin_name); ?><br>
            	<?php _e('By default, <strong>Easy SwipeBox</strong> detects automatically links to <strong>images</strong> (jpg / jpeg / gif / png) and <strong>videos</strong> (Youtube / Vimeo).', $this->plugin_name); ?><br><br>
            	<?php _e('If you like to exclude a single image or video from autodetection enter the class that groups these elements.</code>.', $this->plugin_name); ?><br>
            	<?php _e('By default, <strong>Easy Swipebox</strong> uses <code>class="no-swipebox"</code>.', $this->plugin_name); ?><br>
			</p>
		<?php
	}

	public function autodetect_image_render(  ) {
		?>
		<label>
			<input type="hidden" id="hidden_easySwipeBox_autodetect[image]" name="easySwipeBox_autodetect[image]" value="0" />
			<input id="easySwipeBox_autodetect[image]" type="checkbox" name="easySwipeBox_autodetect[image]" value="1" <?php if ($this->options_autodetect['image'] == 1) echo 'checked="checked"'; ?> />
			<?php _e('Add SwipeBox to image links by default', $this->plugin_name); ?>
			<em>(<?php _e('jpg / jpeg / gif / png', $this->plugin_name); ?>)</em>
		</label>
		<?php
	}

	public function autodetect_video_render(  ) {
		?>
		<label>
			<input type="hidden" id="hidden_easySwipeBox_autodetect[video]" name="easySwipeBox_autodetect[video]" value="0" />
			<input id="easySwipeBox_autodetect[video]" type="checkbox" name="easySwipeBox_autodetect[video]" value="1" <?php if ($this->options_autodetect['video'] == 1) echo 'checked="checked"'; ?> />
			<?php _e('Add SwipeBox to video links by default', $this->plugin_name); ?>
		    <em>(<?php _e('Youtube / Vimeo', $this->plugin_name); ?>)</em>
		</label>
		<?php
	}

	public function autodetect_exclude_render(  ) {
		?>
		<label>
			<input id="easySwipeBox_autodetect[class_exclude]" type="text" name="easySwipeBox_autodetect[class_exclude]" value="<?php echo $this->options_autodetect['class_exclude']; ?>" /><br>
			<em><?php _e('Enter the class that groups the media you would like to exclude from autodetection (Default: <code>no-swipebox</code>).', $this->plugin_name); ?></em>
		</label>
		<?php
	}

	// Section: Lightbox Settings
	public function lightbox_section_render(  ) {
		?>
			<p><?php _e('In this page you can customize the Swipebox lightbox behaviour. Discover more about <strong><a href="http://brutaldesign.github.io/swipebox/?source=easy-swipebox-wp-plugin" target="_blank">SwipeBox options</a></strong>.', $this->plugin_name); ?><br>
			</p>
		<?php
	}

	public function animation_render(  ) {
		?>
		<label>
			<input id="easySwipeBox_lightbox[useCSS]" type="radio" name="easySwipeBox_lightbox[useCSS]" value="1" <?php if ($this->options_lightbox['useCSS'] == 1) echo 'checked="checked"'; ?> /><?php _e('CSS', $this->plugin_name); ?>
			<input id="easySwipeBox_lightbox[useJquery]" type="radio" name="easySwipeBox_lightbox[useCSS]" value="0" <?php if ($this->options_lightbox['useCSS'] == 0) echo 'checked="checked"'; ?> /><?php _e('Jquery', $this->plugin_name); ?><br>
			<em><?php _e('Select the method used to render the lightbox. Use Jquery if you are having problems with old browsers (Default: CSS).', $this->plugin_name); ?></em>
		</label>
		<?php
	}

	public function svg_render(  ) {
		?>
		<label>
			<input type="hidden" id="hidden_easySwipeBox_lightbox[useSVG]" name="easySwipeBox_lightbox[useSVG]" value="0" />
			<input id="easySwipeBox_lightbox[useSVG]" type="checkbox" name="easySwipeBox_lightbox[useSVG]" value="1" <?php if ($this->options_lightbox['useSVG'] == 1) echo 'checked="checked"'; ?> />
			<strong><?php _e('Use SVG Icons', $this->plugin_name); ?></strong><br>
			<em><?php _e('Disable this option if you are having problems with navigation icons not visible on some devices (Default: true).', $this->plugin_name); ?></em>
		</label>
		<?php
	}

	public function remove_navigation_mobile_render(  ) {
		?>
		<label>
			<input type="hidden" id="hidden_easySwipeBox_lightbox[removeBarsOnMobile]" name="easySwipeBox_lightbox[removeBarsOnMobile]" value="0" />
			<input id="easySwipeBox_lightbox[removeBarsOnMobile]" type='checkbox' name='easySwipeBox_lightbox[removeBarsOnMobile]' value='1' <?php if ($this->options_lightbox['removeBarsOnMobile'] == 1) echo 'checked="checked"'; ?> />
			<strong><?php _e('Hide navigation bar on mobile', $this->plugin_name); ?></strong><br>
			<em><?php _e('Select this options if you like to hide the navigation bar on mobile devices (Default: true).', $this->plugin_name); ?></em>
		</label>
		<?php
	}

	public function remove_close_button_mobile_render(  ) {
		?>
		<label>
			<input type="hidden" id="hidden_easySwipeBox_lightbox[hideCloseButtonOnMobile]" name="easySwipeBox_lightbox[hideCloseButtonOnMobile]" value="0" />
			<input id="easySwipeBox_lightbox[hideCloseButtonOnMobile]" type='checkbox' name='easySwipeBox_lightbox[hideCloseButtonOnMobile]' value='1' <?php if ($this->options_lightbox['hideCloseButtonOnMobile'] == 1) echo 'checked="checked"'; ?> />
			<strong><?php _e('Hide close button on mobile', $this->plugin_name); ?></strong><br>
			<em><?php _e('Select this options if you like to hide the close button on mobile devices (Default: true).', $this->plugin_name); ?></em>
		</label>
		<?php
	}

	public function hide_bars_delay_render(  ) {
		?>
		<label>
			<input id="easySwipeBox_lightbox[hideBarsDelay]" type="number" name="easySwipeBox_lightbox[hideBarsDelay]" value="<?php echo $this->options_lightbox['hideBarsDelay'];?>" />
			<strong><?php _e('ms', $this->plugin_name); ?></strong><br>
			<em><?php _e('Enter the value in milliseconds after you want to hide the navigation bar (Default: 3000 ms).', $this->plugin_name); ?></em>
		</label>
		<?php
	}

	public function video_max_width_render(  ) {
		?>
		<label>
			<input id="easySwipeBox_lightbox[videoMaxWidth]" type='number' name='easySwipeBox_lightbox[videoMaxWidth]' value="<?php echo $this->options_lightbox['videoMaxWidth'];?>" />
			<strong><?php _e('px', $this->plugin_name); ?></strong><br>
			<em><?php _e('Enter the video max width opened in the lightbox (Default: 1140 px).', $this->plugin_name); ?></em>
		</label>
		<?php
	}

	public function vimeo_color_render(  ) {
		?>
		<label>
			<input id="easySwipeBox_lightbox[vimeoColor]" class="color-field" type='text' name='easySwipeBox_lightbox[vimeoColor]' value="<?php echo empty($this->options_lightbox['vimeoColor']) ? '#cccccc' : $this->options_lightbox['vimeoColor'];?>" /><br>
			<em><?php _e('Select the color used for Vimeo controllers (Default: #cccccc).', $this->plugin_name); ?></em>
		</label>
		<?php
	}

	public function loop_at_end_render(  ) {
		?>
		<label>
			<input type="hidden" id="hidden_easySwipeBox_lightbox[loopAtEnd]" name="easySwipeBox_lightbox[loopAtEnd]" value="0" />
			<input id="easySwipeBox_lightbox[loopAtEnd]" type="checkbox" name="easySwipeBox_lightbox[loopAtEnd]" value="1" <?php if ($this->options_lightbox['loopAtEnd'] == 'true') echo 'checked="checked"'; ?> />
			<strong><?php _e('Loop', $this->plugin_name); ?></strong><br>
			<em><?php _e('Select this options if you like to loop back to the first image after the last is reached (Default: false).', $this->plugin_name); ?></em>
		</label>
		<?php
	}

	public function autoplay_videos_render(  ) {
		?>
		<label>
			<input type="hidden" id="hidden_easySwipeBox_lightbox[autoplayVideos]" name="easySwipeBox_lightbox[autoplayVideos]" value="0" />
			<input id="easySwipeBox_lightbox[autoplayVideos]" type='checkbox' name='easySwipeBox_lightbox[autoplayVideos]' value='1' <?php if ($this->options_lightbox['autoplayVideos'] == 1) echo 'checked="checked"'; ?> />
			<strong><?php _e('Play video at opening', $this->plugin_name); ?></strong><br>
			<em><?php _e('Select this options if you like to autoplay video at opening (Default: false).', $this->plugin_name); ?></em>
		</label>
		<?php
	}


	// Section: Gallery Setting
	public function gallery_section_render(  ) {
		?>
			<p><?php _e('In this page youn can manage Easy SwipeBox behaviour with galleries.', $this->plugin_name); ?></p>
		<?php
	}

	public function gallery_rel_render(  ) {
		?>
		<label>
			<input type="hidden" id="hidden_easySwipeBox_gallery[gallery_rel]" name="easySwipeBox_gallery[gallery_rel]" value="0" />
			<input id="easySwipeBox_gallery[gallery_rel]" type='checkbox' name='easySwipeBox_gallery[gallery_rel]' value='1' <?php if ($this->options_gallery['gallery_rel'] == 1) echo 'checked="checked"'; ?> />
			<strong><?php _e('Group images in gallery', $this->plugin_name); ?></strong><br>
			<em><?php _e('Enable this option if you like to group all links to image or video in one gallery (Default: false).', $this->plugin_name); ?></em>
		</label>
		<?php
	}

	// Section: General Settings
	public function description_section_render(  ) {
		?>
			<p><?php _e('The options in this section are provided by the plugin <strong>Easy Swipebox</strong> and determines the Media Lightbox behaviour controlled by <strong><a href="http://brutaldesign.github.io/swipebox/?source=easy-swipebox-wp-plugin" target="_blank">SwipeBox</a></strong>.
', $this->plugin_name); ?><br>
            <?php _e('Please, for contributions, issues or questions visit the <strong><a href="https://github.com/leopuleo/easy-swipebox" target="_blank">Github Repo</a></strong>.', $this->plugin_name); ?></p>

            <h3><?php _e('Plugin main features', $this->plugin_name); ?></h3>
            <ol>
            	<li><?php _e('Enqueuing of SwipeBox Javascript and CSS files. Set <code>WP_DEBUG</code> to true for the uncompressed files.', $this->plugin_name); ?></li>
            	<li><?php _e('Autodetection of links to images or videos. You can exclude/include media types from the autodetection section here below.', $this->plugin_name); ?></li>
            </ol>
            <hr>
		<?php
	}

	public function easySwipeBox_plugin_settings_page(  ) {

		?>
		<form method="post" action="options.php">
			<div class="wrap">
			<h2><?php _e('Easy SwipeBox Settings', $this->plugin_name); ?></h2>

			<?php
            if( isset( $_GET[ 'tab' ] ) ){
              	$active_tab = $_GET[ 'tab' ];
            } else {
            	$active_tab = 'general_options';
            }
            ?>

            <h2 class="nav-tab-wrapper">
                <a href="<?php echo admin_url('options-general.php?page=easy-swipebox-settings&tab=general_options');?>" class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php _e('General Settings', $this->plugin_name); ?></a>
                <a href="<?php echo admin_url('options-general.php?page=easy-swipebox-settings&tab=lightbox_options');?>" class="nav-tab <?php echo $active_tab == 'lightbox_options' ? 'nav-tab-active' : ''; ?>"><?php _e('SwipeBox Settings', $this->plugin_name); ?></a>
                <a href="<?php echo admin_url('options-general.php?page=easy-swipebox-settings&tab=gallery_options');?>" class="nav-tab <?php echo $active_tab == 'gallery_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Gallery Settings', $this->plugin_name); ?></a>
                <a href="<?php echo admin_url('options-general.php?page=easy-swipebox-settings&tab=overview');?>" class="nav-tab <?php echo $active_tab == 'overview' ? 'nav-tab-active' : ''; ?>"><?php _e('Overview', $this->plugin_name); ?></a>
            </h2>

				<?php

				switch ($active_tab) {
					case 'general_options':
						settings_fields( 'easySwipeBox_autodetect' );
						do_settings_sections( 'easySwipeBox_autodetect' );
						submit_button();
						break;

					case 'lightbox_options':
						settings_fields( 'easySwipeBox_lightbox' );
						do_settings_sections( 'easySwipeBox_lightbox' );
						submit_button();
						break;

					case 'gallery_options':
						settings_fields( 'easySwipeBox_gallery' );
						do_settings_sections( 'easySwipeBox_gallery' );
						submit_button();
						break;

					case 'overview':
						settings_fields( 'easySwipeBox_overview' );
						do_settings_sections( 'easySwipeBox_overview' );
						break;

					default:
						break;
				}

				?>
			</div>
		</form>
	<?php
	}

	public function sanitize_autodetect( $input ) {
        $valid_input = array();

        if( isset( $input['image'] ) )
            $valid_input['image'] = (bool)( $input['image'] );

        if( isset( $input['video'] ) )
            $valid_input['video'] = (bool)( $input['video'] );

        if( isset( $input['class_exclude'] ) )
            $valid_input['class_exclude'] = sanitize_key(( $input['class_exclude'] ));

        return $valid_input;
	}

	public function sanitize_lightbox( $input ) {
        $valid_input = array();

        if( isset( $input['useCSS'] ) )
            $valid_input['useCSS'] = (bool)( $input['useCSS'] );

        if( isset( $input['useSVG'] ) )
            $valid_input['useSVG'] = (bool)( $input['useSVG'] );

        if( isset( $input['removeBarsOnMobile'] ) )
            $valid_input['removeBarsOnMobile'] = (bool)( $input['removeBarsOnMobile'] );

        if( isset( $input['hideCloseButtonOnMobile'] ) )
            $valid_input['hideCloseButtonOnMobile'] = (bool)( $input['hideCloseButtonOnMobile'] );

        if( isset( $input['hideBarsDelay'] ) )
            $valid_input['hideBarsDelay'] = absint( $input['hideBarsDelay'] );

        if( isset( $input['videoMaxWidth'] ) )
            $valid_input['videoMaxWidth'] = absint( $input['videoMaxWidth'] );

        if( isset( $input['vimeoColor'] ) )

        	$vimeo_color = strip_tags( stripslashes( $input['vimeoColor'] ) );

		    //Check if is a valid hex color
		    if( FALSE === $this->check_color( $vimeo_color ) ) {

		        // Set the error message
		        add_settings_error( 'easySwipeBox_lightbox', 'easySwipebox_vimeo_error', __('Insert a valid color for Vimeo controllers', $this->plugin_name), 'error' ); // $setting, $code, $message, $type

		        // Get the previous valid value
		        $valid_input['vimeoColor'] = $this->options_lightbox['vimeoColor'];

		    } else {

		        $valid_input['vimeoColor'] = $vimeo_color;

		    }

        if( isset( $input['loopAtEnd'] ) )
            $valid_input['loopAtEnd'] = (bool)( $input['loopAtEnd'] );

        if( isset( $input['autoplayVideos'] ) )
            $valid_input['autoplayVideos'] = (bool)( $input['autoplayVideos'] );

        return $valid_input;
	}

	public function sanitize_gallery( $input ) {
        $valid_input = array();

        if( isset( $input['gallery_rel'] ) )
            $valid_input['gallery_rel'] = (bool)( $input['gallery_rel'] );

        return $valid_input;
	}

	public function check_color( $value) {

	    if ( preg_match( '/^#?(?:[0-9a-f]{3}){1,2}$/i', $value) ) { // if user insert a HEX color with #
	        return true;
	    }

	    return false;
	}

}
