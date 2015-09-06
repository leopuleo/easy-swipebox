<?php
/*
Plugin Name: Easy SwipeBox
Plugin URI: https://github.com/leopuleo
Description: This plugin enable <a href="http://brutaldesign.github.io/swipebox/">SwipeBox jQuery extension</a> on all links to image or Video (Youtube / Vimeo).
Text Domain: easy-swipebox
Domain Path: lang
Version: 1.0
Author: Leonardo Giacone
Author URI: https://github.com/leopuleo
*/

/*  
Copyright 2015  Leonardo Giacone  (email : leonardo.giacone@gmail.com)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/


 
/**************
 * CONSTANTS
 **************/

define( 'EASY_SWIPEBOX_VERSION', '1.0' );
define( 'SWIPEBOX_VERSION', '1.4.1' );
define( 'EASY_SWIPEBOX_PLUGINBASENAME', plugin_basename(__FILE__) );
define( 'EASY_SWIPEBOX_PLUGINFILE', basename(__FILE__) );

// Check if easy-swipebox.php is moved one dir up like in WPMU's /mu-plugins/
// or if plugins_url() returns the main plugins dir location as it does on 
// a Debian repository install.
// NOTE: WP_PLUGIN_URL causes problems when installed in /mu-plugins/
if( !stristr( plugins_url( '', __FILE__ ), '/easy-swipebox' ) )
	define( 'EASY_SWIPEBOX_SUBDIR', 'easy-swipebox/' );
else
	define( 'EASY_SWIPEBOX_SUBDIR', '' );

define( 'EASY_SWIPEBOX_PLUGINDIR', dirname(__FILE__) . '/' . EASY_SWIPEBOX_SUBDIR );
define( 'EASY_SWIPEBOX_PLUGINURL', plugins_url( '/' . EASY_SWIPEBOX_SUBDIR, __FILE__ ) );
define( 'EASY_SWIPEBOX_TEXTDOMAIN', 'easy-swipebox' );

/**************
 *   CLASS
 **************/
require_once(EASY_SWIPEBOX_PLUGINDIR . 'easy-swipebox-class.php');

easySwipeBox::run();
