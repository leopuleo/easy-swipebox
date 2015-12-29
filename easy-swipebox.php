<?php

/**
 * Easy SwipeBox bootstrap file
 *
 * @link              https://github.com/leopuleo/easy-swipebox
 * @since             1.1
 * @package           EasySwipeBox
 *
 * @wordpress-plugin
 * Plugin Name: Easy SwipeBox
 * Plugin URI: https://github.com/leopuleo
 * Description: This plugin enable <a href="http://brutaldesign.github.io/swipebox/">SwipeBox jQuery extension</a> on all links to image or Video (Youtube / Vimeo).
 * Version:           1.1
 * Author: Leonardo Giacone
 * Author URI: https://github.com/leopuleo
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-swipebox
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
  die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-easy-swipebox-activator.php
 */
function activate_easy_swipebox() {
  require_once plugin_dir_path(__FILE__) . 'includes/class-easy-swipebox-activator.php';
  EasySwipeBoxActivator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-easy-swipebox-deactivator.php
 */
function deactivate_easy_swipebox() {
  require_once plugin_dir_path(__FILE__) . 'includes/class-easy-swipebox-deactivator.php';
  EasySwipeBoxDeactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_easy_swipebox');
register_deactivation_hook(__FILE__, 'deactivate_easy_swipebox');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-easy-swipebox.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1
 */
function run_easy_swipebox() {

  $plugin = new EasySwipeBox\EasySwipeBox();
  $plugin->run();

}
run_easy_swipebox();
