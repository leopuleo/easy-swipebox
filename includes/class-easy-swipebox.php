<?php

namespace EasySwipeBox;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link            https://github.com/leopuleo/easy-swipebox
 * @since           1.1.0
 * @package         EasySwipeBox
 *
 * @subpackage      EasySwipeBox/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package       EasySwipeBox
 * @subpackage    EasySwipeBox/includes
 * @author        leopuleo
 */

class EasySwipeBox {

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.1.0
   * @access   protected
   * @var      EasySwipeboxLoader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.1.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.1.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  /**
   * The main dir of this plugin.
   *
   * @since    1.1.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $plugin_basename;

  /**
   * Autodetect options of this plugin.
   *
   * @since    1.1.0
   * @access   protected
   * @var      array    $options_autodetect    The options for autodetect functionalies.
   */
  protected $options_autodetect;

  /**
   * Lightbox options of this plugin.
   *
   * @since    1.1.0
   * @access   protected
   * @var      array    $options_lightbox    The options for lightbox behaviour and appereance.
   */
  protected $options_lightbox;

  /**
   * Advanced options of this plugin.
   *
   * @since    1.1.0
   * @access   protected
   * @var      array    $options_advanced    The advanced options for the plugin.
   */
  protected $options_advanced;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.1.0
   */
  public function __construct() {

    $this->plugin_name = 'easy-swipebox';
    $this->version = '1.1.2';
    $this->plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_name . '.php');

    // Define defaults for autodetect options
    $this->defaults_autodetect = array (
      'image' => 1,
      'video' => 1,
      'class_exclude' => '.no-swipebox'
    );

    // Define defaults for lightbox options
    $this->defaults_lightbox = array (
      'useCSS' => 1,
          'useSVG' => 1,
          'removeBarsOnMobile' => 1,
          'hideCloseButtonOnMobile' => 0,
          'hideBarsDelay' => '3000',
          'videoMaxWidth' => '1140',
          'vimeoColor' => '#cccccc',
          'loopAtEnd' => 0,
          'autoplayVideos' => 0
    );

    // Define defaults for advanced options
    $this->defaults_advanced = array (
      'loadingPlace' => 'footer',
      'debugMode' => 0
    );

    $this->options_autodetect = wp_parse_args(get_option('easySwipeBox_autodetect'), $this->defaults_autodetect);
    $this->options_lightbox = wp_parse_args(get_option('easySwipeBox_lightbox'), $this->defaults_lightbox);
    $this->options_advanced = wp_parse_args(get_option('easySwipeBox_advanced'), $this->defaults_advanced);

    $this->loadDependencies();
    $this->setLocale();
    $this->defineAdminHooks();
    $this->definePublicHooks();
  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
   * - Plugin_Name_i18n. Defines internationalization functionality.
   * - Plugin_Name_Admin. Defines all hooks for the admin area.
   * - Plugin_Name_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.1.0
   * @access   private
   */
  private function loadDependencies() {

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-easy-swipebox-loader.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-easy-swipebox-i18n.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-easy-swipebox-admin.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-easy-swipebox-public.php';

    $this->loader = new EasySwipeboxLoader();

  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.1.0
   * @access   private
   */
  private function setLocale() {

    $plugin_i18n = new EasySwipeBoxi18n();
    $plugin_i18n->setDomain($this->getPluginName());

    $this->loader->addAction('plugins_loaded', $plugin_i18n, 'loadPluginTextdomain');

  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.1.0
   * @access   private
   */
  private function defineAdminHooks() {

    $plugin_admin = new EasySwipeboxAdmin($this->getPluginName(), $this->getVersion(), $this->getOptionsAutodetect(), $this->getOptionsLightbox(), $this->getOptionsAdvanced(), $this->getPluginBasename());

    $this->loader->addAction('admin_enqueue_scripts', $plugin_admin, 'EnqueueStyles');
    $this->loader->addAction('admin_enqueue_scripts', $plugin_admin, 'EnqueueScripts');
    $this->loader->addAction('admin_menu', $plugin_admin, 'AddSettingPage');
    $this->loader->addAction('admin_init', $plugin_admin, 'SettingsInit');
    $this->loader->addFilter('plugin_action_links_' . $this->plugin_basename, $plugin_admin, 'addPluginLinks');

  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    1.1.0
   * @access   private
   */
  private function definePublicHooks() {

    $plugin_public = new EasySwipeBoxPublic($this->getPluginName(), $this->getVersion(), $this->getOptionsAutodetect(), $this->getOptionsLightbox(), $this->getOptionsAdvanced());

    $this->loader->addAction('wp_enqueue_scripts', $plugin_public, 'enqueueStyles');
    $this->loader->addAction('wp_enqueue_scripts', $plugin_public, 'enqueueScripts');

  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.1.0
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.1.0
   * @return    string    The name of the plugin.
   */
  public function getPluginName() {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.1.0
   * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
   */
  public function getLoader() {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.1.0
   * @return    string    The version number of the plugin.
   */
  public function getVersion() {
    return $this->version;
  }

  /**
   * Retrieve the main dir of the plugin.
   *
   * @since     1.1.0
   * @return    string    The version number of the plugin.
   */
  public function getPluginBasename() {
    return $this->plugin_basename;
  }

  /**
   * Retrieve the options for autodetect.
   *
   * @since     1.1.0
   * @return    array    The options for autodetect.
   */
  public function getOptionsAutodetect() {
    return $this->options_autodetect;
  }

  /**
   * Retrieve the options for lightbox settings.
   *
   * @since     1.1.0
   * @return    array    The options for lightbox setting.
   */
  public function getOptionsLightbox() {
    return $this->options_lightbox;
  }

  /**
   * Retrieve the options for advanced settings.
   *
   * @since     1.1.0
   * @return    array    The options for advanced setting.
   */
  public function getOptionsAdvanced() {
    return $this->options_advanced;
  }
}
