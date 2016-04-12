<?php

namespace EasySwipeBox;

if (!defined('ABSPATH')) {
   exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @package       EasySwipeBox
 * @subpackage    EasySwipeBox/includes
 * @author        leopuleo
 */
class EasySwipeboxAdmin {

  /**
   * The ID of this plugin.
   *
   * @since    1.1.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.1.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Loading the autodetect options
   *
   * @since    1.1.0
   * @access   private
   * @var      array    $options_autodetect    The autodetection options.
   */
  private $options_autodetect;

  /**
   * Loading the lightbox options
   *
   * @since    1.1.0
   * @access   private
   * @var      array    $options_lightbox    The lightbox options.
   */
  private $options_lightbox;

  /**
   * Loading the lightbox options
   *
   * @since    1.1.0
   * @access   private
   * @var      array    $options_advanced    The advanced options.
   */
  private $options_advanced;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.1.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   * @param      string    $options_autodetect       The autodetection options.
   * @param      string    $options_lightbox    The lightbox options.
   */
  public function __construct($plugin_name, $version, $options_autodetect, $options_lightbox, $options_advanced) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->options_autodetect = $options_autodetect;
    $this->options_lightbox = $options_lightbox;
    $this->options_advanced = $options_advanced;

  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.1.0
   * @access   public
   */
  public function enqueueStyles() {
    wp_enqueue_style('wp-color-picker');
  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.1.0
   * @access   public
   */
  public function enqueueScripts() {
    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/easy-swipebox-admin.js', array( 'wp-color-picker'), $this->version, false);
  }

  /**
   * Register the plugin link in plugins list page.
   *
   * @since    1.1.0
   * @access   public
   */
  public function addPluginLinks($links) {
    $custom_links = array(
      '<a href="' . admin_url('options-general.php?page=easy-swipebox-settings') . '">' . __('Settings', $this->plugin_name) . '</a>'
    );
    return array_merge($links, $custom_links);
  }

  /**
   * Add plugin option page
   *
   * @since    1.1.0
   * @access   public
   */
  public function addSettingPage() {
    add_submenu_page(
      'options-general.php',
      apply_filters($this->plugin_name . '-settings-page-title', __('Easy SwipeBox Settings', $this->plugin_name)),
      apply_filters($this->plugin_name . '-settings-page-title', __('Easy SwipeBox', $this->plugin_name)),
      'install_plugins',
      $this->plugin_name . '-settings',
      array($this, 'easySwipeBoxSettingsPage')
    );
  }

  /**
   * Register setting page sections and fields
   *
   * @since    1.1.0
   * @access   public
   */
  public function settingsInit() {

    // Register Settings
    register_setting('easySwipeBox_autodetect', 'easySwipeBox_autodetect', array($this, 'sanitizeAutodetect'));
    register_setting('easySwipeBox_lightbox', 'easySwipeBox_lightbox', array($this, 'sanitizeLightbox'));
    register_setting('easySwipeBox_advanced', 'easySwipeBox_advanced', array($this, 'sanitizeAdvanced'));
    register_setting('easySwipeBox_overview', 'easySwipeBox_overview');

    // Section: Lightbox Settings
    add_settings_section(
      'lightbox_section',
      __('Lightbox settings', $this->plugin_name),
      array($this, 'lightboxSectionRender'),
      'easySwipeBox_lightbox'
    );

    // Section: Autodetect Settings
    add_settings_section(
      'autodetect_section',
      __('Autodetect settings', $this->plugin_name),
      array($this, 'autodetectSectionRender'),
      'easySwipeBox_autodetect'
    );

    // Section: Advanced Settings
    add_settings_section(
      'advanced_section',
      __('Advanced settings', $this->plugin_name),
      array($this, 'advancedtSectionRender'),
      'easySwipeBox_advanced'
    );

    // Section: Overview
    add_settings_section(
      'description_section',
      __('Overview', $this->plugin_name),
      array($this, 'descriptionSectionRender'),
      'easySwipeBox_overview'
    );

    // Field: Lightbox Settings -> Animation
    add_settings_field(
      'animation',
      __('Animation type', $this->plugin_name),
      array($this, 'animationRender'),
      'easySwipeBox_lightbox',
      'lightbox_section'
    );

    // Field: Lightbox Settings -> SVG
    add_settings_field(
      'svg',
      __('Use SVG Icons', $this->plugin_name),
      array($this, 'svgRender'),
      'easySwipeBox_lightbox',
      'lightbox_section'
    );

    // Field: Lightbox Settings -> Navigation Mobile
    add_settings_field(
      'remove_navigation_mobile',
      __('Navigation bar', $this->plugin_name),
      array($this, 'removeNavigationMobileRender'),
      'easySwipeBox_lightbox',
      'lightbox_section'
    );

    // Field: Lightbox Settings -> Close Button Mobile
    add_settings_field(
      'remove_close_button_mobile',
      __('Close button', $this->plugin_name),
      array($this, 'removeCloseButtonMobileRender'),
      'easySwipeBox_lightbox',
      'lightbox_section'
    );

    // Field: Lightbox Settings -> Hide Bar Delay
    add_settings_field(
      'hide_bars_delay',
      __('Hide Bars', $this->plugin_name),
      array($this, 'hideBarsDelayRender'),
      'easySwipeBox_lightbox',
      'lightbox_section'
    );

    // Field: Lightbox Settings -> Video Max Width
    add_settings_field(
      'video_max_width',
      __('Video max width', $this->plugin_name),
      array($this, 'videoMaxWidthRender'),
      'easySwipeBox_lightbox',
      'lightbox_section'
    );

    // Field: Lightbox Settings -> Vimeo Color
    add_settings_field(
      'vimeo_color',
      __('Vimeo controllers color', $this->plugin_name),
      array($this, 'vimeoColorRender'),
      'easySwipeBox_lightbox',
      'lightbox_section'
    );

    // Field: Lightbox Settings -> Loop at the end
    add_settings_field(
      'loop_at_end',
      __('Loop at the end', $this->plugin_name),
      array($this, 'loopAtEndRender'),
      'easySwipeBox_lightbox',
      'lightbox_section'
    );

    // Field: Lightbox Settings -> Autoplay Video
    add_settings_field(
      'autoplay_videos',
      __('Autoplay videos', $this->plugin_name),
      array($this, 'autoplayVideosRender'),
      'easySwipeBox_lightbox',
      'lightbox_section'
    );

    // Field: General Settings -> Autodetect Image
    add_settings_field(
      'autodetect_image',
      __('Image links', $this->plugin_name),
      array( $this, 'autodetectImageRender' ),
      'easySwipeBox_autodetect',
      'autodetect_section'
    );

    // Field: General Settings -> Autodetect Video
    add_settings_field(
      'autodetect_video',
      __('Video links', $this->plugin_name),
      array($this, 'autodetectVideoRender'),
      'easySwipeBox_autodetect',
      'autodetect_section'
    );

    // Field: General Settings -> Autodetect Exclude
    add_settings_field(
      'autodetect_exclude',
      __('Exclude links', $this->plugin_name),
      array($this, 'autodetectExcludeRender'),
      'easySwipeBox_autodetect',
      'autodetect_section'
    );

    // Field: Advanced Settings -> Loading Place
    add_settings_field(
      'loading_place',
      __('Loading place', $this->plugin_name),
      array($this, 'loadingPlaceRender'),
      'easySwipeBox_advanced',
      'advanced_section'
    );

    // Field: Advanced Settings -> Debug Mode
    add_settings_field(
      'debug_mode',
      __('Debug Mode', $this->plugin_name),
      array($this, 'debugModeRender'),
      'easySwipeBox_advanced',
      'advanced_section'
    );
  }

  /**
   * Render setting page sections and fields
   *
   * @since    1.1.0
   * @access   public
   */

  // Section: Lightbox Settings
  public function lightboxSectionRender() {
    ?>
      <p><?php _e('In this page you can customize the SwipeBox lightbox behaviour. Discover more about <strong><a href="http://brutaldesign.github.io/swipebox/?source=easy-swipebox-wp-plugin" target="_blank">SwipeBox options</a></strong>.', $this->plugin_name); ?><br>
      </p>
    <?php
  }

  public function animationRender() {
    ?>
      <input id="easySwipeBox_lightbox[useCSS]" type="radio" name="easySwipeBox_lightbox[useCSS]" value="1" <?php if ($this->options_lightbox['useCSS'] == 1) {echo 'checked="checked"';} ?> /><?php _e('CSS', $this->plugin_name); ?>
      <input id="easySwipeBox_lightbox[useJquery]" type="radio" name="easySwipeBox_lightbox[useCSS]" value="0" <?php if ($this->options_lightbox['useCSS'] == 0) {echo 'checked="checked"';} ?> /><?php _e('Jquery', $this->plugin_name); ?><br>
      <em><?php _e('Select the method used to render the animations. Use jQuery if you are having problems with old browsers (Default: CSS).', $this->plugin_name); ?></em>
    <?php
  }

  public function svgRender() {
    ?>
    <label>
      <input type="hidden" id="hidden_easySwipeBox_lightbox[useSVG]" name="easySwipeBox_lightbox[useSVG]" value="0" />
      <input id="easySwipeBox_lightbox[useSVG]" type="checkbox" name="easySwipeBox_lightbox[useSVG]" value="1" <?php if ($this->options_lightbox['useSVG'] == 1) {echo 'checked="checked"';} ?> />
      <strong><?php _e('Use SVG Icons', $this->plugin_name); ?></strong><br>
      <em><?php _e('Disable this option if you are having problems with navigation icons not visible on some devices (Default: true).', $this->plugin_name); ?></em>
    </label>
    <?php
  }

  public function removeNavigationMobileRender() {
    ?>
    <label>
      <input type="hidden" id="hidden_easySwipeBox_lightbox[removeBarsOnMobile]" name="easySwipeBox_lightbox[removeBarsOnMobile]" value="0" />
      <input id="easySwipeBox_lightbox[removeBarsOnMobile]" type='checkbox' name='easySwipeBox_lightbox[removeBarsOnMobile]' value='1' <?php if ($this->options_lightbox['removeBarsOnMobile'] == 1) {echo 'checked="checked"';} ?> />
      <strong><?php _e('Hide navigation bar on mobile', $this->plugin_name); ?></strong><br>
      <em><?php _e('Select this options if you like to hide the navigation bar on mobile devices (Default: true).', $this->plugin_name); ?></em>
    </label>
    <?php
  }

  public function removeCloseButtonMobileRender() {
    ?>
    <label>
      <input type="hidden" id="hidden_easySwipeBox_lightbox[hideCloseButtonOnMobile]" name="easySwipeBox_lightbox[hideCloseButtonOnMobile]" value="0" />
      <input id="easySwipeBox_lightbox[hideCloseButtonOnMobile]" type='checkbox' name='easySwipeBox_lightbox[hideCloseButtonOnMobile]' value='1' <?php if ($this->options_lightbox['hideCloseButtonOnMobile'] == 1) {echo 'checked="checked"';} ?> />
      <strong><?php _e('Hide close button on mobile', $this->plugin_name); ?></strong><br>
      <em><?php _e('Select this options if you like to hide the close button on mobile devices (Default: false).', $this->plugin_name); ?></em>
    </label>
    <?php
  }

  public function hideBarsDelayRender() {
    ?>
    <label>
      <input id="easySwipeBox_lightbox[hideBarsDelay]" type="number" name="easySwipeBox_lightbox[hideBarsDelay]" value="<?php echo $this->options_lightbox['hideBarsDelay'];?>" />
      <strong><?php _e('ms', $this->plugin_name); ?></strong><br>
      <em><?php _e('Enter the value in milliseconds after you want to hide the navigation bar (Default: 3000 ms).', $this->plugin_name); ?></em>
    </label>
    <?php
  }

  public function videoMaxWidthRender() {
    ?>
    <label>
      <input id="easySwipeBox_lightbox[videoMaxWidth]" type='number' name='easySwipeBox_lightbox[videoMaxWidth]' value="<?php echo $this->options_lightbox['videoMaxWidth'];?>" />
      <strong><?php _e('px', $this->plugin_name); ?></strong><br>
      <em><?php _e('Enter the video max width opened in the lightbox (Default: 1140 px).', $this->plugin_name); ?></em>
    </label>
    <?php
  }

  public function vimeoColorRender() {
    ?>
    <label>
      <input id="easySwipeBox_lightbox[vimeoColor]" class="color-field" type='text' name='easySwipeBox_lightbox[vimeoColor]' value="<?php echo empty($this->options_lightbox['vimeoColor']) ? '#cccccc' : $this->options_lightbox['vimeoColor'];?>" /><br>
      <em><?php _e('Select the color used for Vimeo controllers (Default: #cccccc).', $this->plugin_name); ?></em>
    </label>
    <?php
  }

  public function loopAtEndRender() {
    ?>
    <label>
      <input type="hidden" id="hidden_easySwipeBox_lightbox[loopAtEnd]" name="easySwipeBox_lightbox[loopAtEnd]" value="0" />
      <input id="easySwipeBox_lightbox[loopAtEnd]" type="checkbox" name="easySwipeBox_lightbox[loopAtEnd]" value="1" <?php if ($this->options_lightbox['loopAtEnd'] == 'true') {echo 'checked="checked"';} ?> />
      <strong><?php _e('Loop', $this->plugin_name); ?></strong><br>
      <em><?php _e('Select this options if you like to loop back to the first image after the last is reached (Default: false).', $this->plugin_name); ?></em>
    </label>
    <?php
  }

  public function autoplayVideosRender() {
    ?>
    <label>
      <input type="hidden" id="hidden_easySwipeBox_lightbox[autoplayVideos]" name="easySwipeBox_lightbox[autoplayVideos]" value="0" />
      <input id="easySwipeBox_lightbox[autoplayVideos]" type='checkbox' name='easySwipeBox_lightbox[autoplayVideos]' value='1' <?php if ($this->options_lightbox['autoplayVideos'] == 1) {echo 'checked="checked"';} ?> />
      <strong><?php _e('Play video at opening', $this->plugin_name); ?></strong><br>
      <em><?php _e('Select this options if you like to autoplay video at opening (Default: false).', $this->plugin_name); ?></em>
    </label>
    <?php
  }

  // Section: Autodetect
  public function autodetectSectionRender() {
    ?>
      <p>
        <?php _e('Select one or more options, <strong>Easy SwipeBox</strong> automatically detects the media type and add <code>class="swipebox"</code> to their links.', $this->plugin_name); ?><br>
              <?php _e('By default, <strong>Easy SwipeBox</strong> detects automatically links to <strong>images</strong> (jpg / jpeg / gif / png) and <strong>videos</strong> (Youtube / Vimeo).', $this->plugin_name); ?><br><br>
              <?php _e('If you like to exclude some images or videos from autodetection enter the selector that groups these elements.', $this->plugin_name); ?><br>
              <?php _e('By default, <strong>Easy SwipeBox</strong> uses <code>.no-swipebox</code>.', $this->plugin_name); ?><br>
      </p>
    <?php
  }

  public function autodetectImageRender() {
    ?>
    <label>
      <input type="hidden" id="hidden_easySwipeBox_autodetect[image]" name="easySwipeBox_autodetect[image]" value="0" />
      <input id="easySwipeBox_autodetect[image]" type="checkbox" name="easySwipeBox_autodetect[image]" value="1" <?php if ($this->options_autodetect['image'] == 1) {echo 'checked="checked"';}?>
      />
      <?php _e('Add SwipeBox to image links by default', $this->plugin_name); ?>
      <em>(<?php _e('jpg / jpeg / gif / png', $this->plugin_name); ?>)</em>
    </label>
    <?php
  }

  public function autodetectVideoRender() {
    ?>
    <label>
      <input type="hidden" id="hidden_easySwipeBox_autodetect[video]" name="easySwipeBox_autodetect[video]" value="0" />
      <input id="easySwipeBox_autodetect[video]" type="checkbox" name="easySwipeBox_autodetect[video]" value="1" <?php if ($this->options_autodetect['video'] == 1) {echo 'checked="checked"';} ?> />
      <?php _e('Add SwipeBox to video links by default', $this->plugin_name); ?>
        <em>(<?php _e('Youtube / Vimeo', $this->plugin_name); ?>)</em>
    </label>
    <?php
  }

  public function autodetectExcludeRender() {
    ?>
    <label>
      <input id="easySwipeBox_autodetect[class_exclude]" type="text" name="easySwipeBox_autodetect[class_exclude]" value="<?php echo $this->options_autodetect['class_exclude']; ?>" /><br>
      <em><?php _e('Enter the selector that groups the media you would like to exclude from autodetection. Use commas to separate multiple selectors (Default: <code>.no-swipebox</code>).', $this->plugin_name); ?></em>
    </label>
    <?php
  }

  // Section: Advanced Settings
  public function advancedtSectionRender() {
    ?>
      <p><?php _e('In this page you can customize the Easy SwipeBox advanced settings.', $this->plugin_name); ?> <?php _e('Please be carefull, the wrong settings combination can break your site.', $this->plugin_name); ?><br>
      </p>
    <?php
  }

  public function loadingPlaceRender() {
    ?>
      <input id="easySwipeBox_advanced[loadingPlace]" type="radio" name="easySwipeBox_advanced[loadingPlace]" value="header" <?php if ($this->options_advanced['loadingPlace'] == 'header') {echo 'checked="checked"';} ?> /><?php _e('Header', $this->plugin_name); ?>
      <input id="easySwipeBox_advanced[loadingPlace]" type="radio" name="easySwipeBox_advanced[loadingPlace]" value="footer" <?php if ($this->options_advanced['loadingPlace'] == 'footer') {echo 'checked="checked"';} ?> /><?php _e('Footer', $this->plugin_name); ?><br>
      <em><?php _e('Select where all the lightbox scripts should be placed. (Default: Footer).', $this->plugin_name); ?></em>
    <?php
  }

  public function debugModeRender() {
    ?>
    <label>
      <input type="hidden" id="hidden_easySwipeBox_advanced[debugMode]" name="easySwipeBox_advanced[debugMode]" value="0" />
      <input id="easySwipeBox_advanced[debugMode]" type='checkbox' name='easySwipeBox_advanced[debugMode]' value='1' <?php if ($this->options_advanced['debugMode'] == 1) {echo 'checked="checked"';} ?> />
      <strong><?php _e('Enable Debug', $this->plugin_name); ?></strong><br>
      <em><?php _e('Select this options if you like to enqueue uncompressed CSS and JavaScript files (Default: false).', $this->plugin_name); ?></em>
    </label>
    <?php
  }

  // Section: Overview
  public function descriptionSectionRender() {
    ?>
      <p><?php _e('The options in this section are provided by the plugin <strong>Easy Swipebox</strong> and determines the Media Lightbox behaviour controlled by <strong><a href="http://brutaldesign.github.io/swipebox/?source=easy-swipebox-wp-plugin" target="_blank">SwipeBox</a></strong>.
', $this->plugin_name); ?></p>
      <hr>

      <h3><?php _e('Plugin main features', $this->plugin_name); ?></h3>
      <ol>
        <li><?php _e('Enqueuing of SwipeBox Javascript and CSS files.', $this->plugin_name); ?></li>
        <li><?php _e('Customization of SwipeBox lightbox appereance and behaviour from the <strong>Lightbox Settings</strong> page.', $this->plugin_name); ?></li>
        <li><?php _e('Autodetection of links to images or videos. You can exclude/include media types from the <strong>Autodetection Settings</strong> page.', $this->plugin_name); ?></li>
        <li><?php _e('Other geek settings in the <strong>Advanced Settings</strong> page.', $this->plugin_name); ?></li>
      </ol>
      <hr>

      <h3><?php _e('Contribution', $this->plugin_name); ?></h3>
      <p><?php _e('There are many ways to contribute to this plugin:', $this->plugin_name); ?></p>
      <ol>
        <li><?php _e('Report a bug, submit pull request or new feature proposal: visit the <strong><a href="https://github.com/leopuleo/easy-swipebox" target="_blank">Github Repo</a></strong>.', $this->plugin_name); ?></li>
        <li><?php _e('Translate it in your language: visit the <strong><a href="https://translate.wordpress.org/projects/wp-plugins/easy-swipebox" target="_blank">WordPress translation page</a></strong>.', $this->plugin_name); ?></li>
        <li><?php _e('Rate it 5 stars on <strong><a href="https://wordpress.org/support/view/plugin-reviews/easy-swipebox?filter=5#postform" target="_blank">WordPress.org</a></strong>.', $this->plugin_name); ?></li>
        <li><?php _e('<strong><a href="//paypal.me/LeonardoGiacone" target="_blank">Buy me a beer!</a></strong>', $this->plugin_name); ?></li>

      </ol>
      <hr>

      <h3><?php _e('Support', $this->plugin_name); ?></h3>
      <p><strong><?php _e('Need help?', $this->plugin_name); ?></strong>
      <?php _e('Read the <strong><a href="https://wordpress.org/plugins/easy-swipebox/faq/" target="_blank">FAQ</a></strong> or visit the <strong><a href="https://wordpress.org/support/plugin/easy-swipebox" target="_blank">WordPress.org support page</a></strong> / <strong><a href="https://github.com/leopuleo/easy-swipebox/issues" target="_blank">Github Issue Tracker</a></strong>.', $this->plugin_name); ?></p>
      <p><strong><?php _e('Note:', $this->plugin_name); ?></strong> <?php _e('this plugin use SwipeBox jQuery plugin as lightbox solution. For any issues or pull requests related to SwipeBox appereance or behaviour please visit the <strong><a href="http://brutaldesign.github.io/swipebox/?source=easy-swipebox-wp-plugin" target="_blank">SwipeBox Repo</a></strong>.', $this->plugin_name); ?></p>
    <?php
  }

  /**
   * Render the setting form and tabs
   *
   * @since    1.1.0
   * @access   public
   */
  public function easySwipeBoxSettingsPage() {

    ?>
    <form method="post" action="options.php">
      <div class="wrap">
      <h2><?php _e('Easy SwipeBox Settings', $this->plugin_name); ?></h2>

    <?php
    if (isset($_GET['tab'])) {
        $active_tab = $_GET['tab'];
    } else {
      $active_tab = 'lightbox_options';
    }
    ?>

    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url('options-general.php?page=easy-swipebox-settings&tab=lightbox_options');?>" class="nav-tab <?php echo $active_tab == 'lightbox_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Lightbox', $this->plugin_name); ?></a>
        <a href="<?php echo admin_url('options-general.php?page=easy-swipebox-settings&tab=autodetect_options');?>" class="nav-tab <?php echo $active_tab == 'autodetect_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Autodetect', $this->plugin_name); ?></a>
        <a href="<?php echo admin_url('options-general.php?page=easy-swipebox-settings&tab=advanced_options');?>" class="nav-tab <?php echo $active_tab == 'advanced_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Advanced', $this->plugin_name); ?></a>
        <a href="<?php echo admin_url('options-general.php?page=easy-swipebox-settings&tab=overview');?>" class="nav-tab <?php echo $active_tab == 'overview' ? 'nav-tab-active' : ''; ?>"><?php _e('Overview', $this->plugin_name); ?></a>
    </h2>

    <?php
    switch ($active_tab) {
      case 'lightbox_options':
        settings_fields('easySwipeBox_lightbox');
        do_settings_sections('easySwipeBox_lightbox');
        submit_button();
        break;

      case 'autodetect_options':
        settings_fields('easySwipeBox_autodetect');
        do_settings_sections('easySwipeBox_autodetect');
        submit_button();
        break;

      case 'advanced_options':
        settings_fields('easySwipeBox_advanced');
        do_settings_sections('easySwipeBox_advanced');
        submit_button();
        break;

      case 'overview':
        settings_fields('easySwipeBox_overview');
        do_settings_sections('easySwipeBox_overview');
        break;

      default:
        break;
    }
    ?>
      </div>
    </form>
  <?php
  }

  /**
   * Sanitize lightbox fields
   *
   * @since    1.1.0
   * @access   public
   */
  public function sanitizeLightbox($input) {
    $valid_input = array();

    if (isset($input['useCSS'])) {
      $valid_input['useCSS'] = (bool)($input['useCSS']);
    }
    if (isset($input['useSVG'])) {
      $valid_input['useSVG'] = (bool)($input['useSVG']);
    }
    if (isset($input['removeBarsOnMobile'])) {
      $valid_input['removeBarsOnMobile'] = (bool)($input['removeBarsOnMobile']);
    }
    if (isset($input['hideCloseButtonOnMobile'])) {
      $valid_input['hideCloseButtonOnMobile'] = (bool)($input['hideCloseButtonOnMobile']);
    }
    if (isset($input['hideBarsDelay'])) {
      $valid_input['hideBarsDelay'] = absint($input['hideBarsDelay']);
    }
    if (isset($input['videoMaxWidth'])) {
      $valid_input['videoMaxWidth'] = absint($input['videoMaxWidth']);
    }
    if (isset($input['vimeoColor'])) {
      $vimeo_color = strip_tags(stripslashes($input['vimeoColor']));

      //Check if is a valid hex color
      if (false === $this->checkColor($vimeo_color)) {
        // Set the error message
        add_settings_error('easySwipeBox_lightbox', 'easySwipebox_vimeo_error', __('Insert a valid color for Vimeo controllers', $this->plugin_name), 'error'); // $setting, $code, $message, $type

        // Get the previous valid value
        $valid_input['vimeoColor'] = $this->options_lightbox['vimeoColor'];
      } else {
        $valid_input['vimeoColor'] = $vimeo_color;
      }
    }
    if (isset($input['loopAtEnd'])) {
      $valid_input['loopAtEnd'] = (bool)($input['loopAtEnd']);
    }
    if (isset($input['autoplayVideos'])) {
      $valid_input['autoplayVideos'] = (bool)($input['autoplayVideos']);
    }
    return $valid_input;
  }

  /**
   * Sanitize autodetect fields
   *
   * @since    1.1.0
   * @access   public
   */
  public function sanitizeAutodetect($input) {
    $valid_input = array();

    if (isset($input['image'])) {
      $valid_input['image'] = (bool)($input['image']);
    }

    if (isset($input['video'])) {
      $valid_input['video'] = (bool)($input['video']);
    }

    if (isset($input['class_exclude'])) {
      $valid_input['class_exclude'] = sanitize_text_field($input['class_exclude']);
    }
    return $valid_input;
  }


  /**
   * Sanitize advanced fields
   *
   * @since    1.1.0
   * @access   public
   */
  public function sanitizeAdvanced($input) {
    $valid_input = array();

    if (isset($input['loadingPlace'])) {
      $valid_input['loadingPlace'] = sanitize_text_field($input['loadingPlace']);
    }

    if (isset($input['debugMode'])) {
      $valid_input['debugMode'] = (bool)($input['debugMode']);
    }

    return $valid_input;
  }


  /**
   * Check if color is a valid HEX.
   *
   * @since    1.1.0
   * @access   public
   */
  public function checkColor($value) {

    if (preg_match('/^#?(?:[0-9a-f]{3}){1,2}$/i', $value)) { // if user insert a HEX color with #
        return true;
    }
    return false;
  }
}
