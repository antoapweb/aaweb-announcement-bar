<?php
/**
 * Plugin Name: AAWEB Announcement Bar
 * Plugin URI: https://antoapweb.gr/aaweb-announcement-bar/
 * Description: Add a lightweight, customizable announcement bar with scheduling, dismiss button, device visibility, page rules, and WooCommerce exclusions.
 * Version: 1.4.2
 * Requires at least: 6.8
 * Requires PHP: 7.4
 * Tested up to: 7.0
 * Author: AAWEB - Apostolou Antonios
 * Author URI: https://antoapweb.gr
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: aaweb-announcement-bar
 * Domain Path: /languages
 *
 * @package AAWEB_Announcement_Bar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AAWEB_AB_VERSION', '1.4.1' );
define( 'AAWEB_AB_FILE', __FILE__ );
define( 'AAWEB_AB_DIR', plugin_dir_path( __FILE__ ) );
define( 'AAWEB_AB_URL', plugin_dir_url( __FILE__ ) );

require_once AAWEB_AB_DIR . 'includes/class-aaweb-announcement-bar-settings.php';
require_once AAWEB_AB_DIR . 'includes/class-aaweb-announcement-bar-admin.php';
require_once AAWEB_AB_DIR . 'includes/class-aaweb-announcement-bar-frontend.php';
require_once AAWEB_AB_DIR . 'includes/class-aaweb-announcement-bar.php';

add_action( 'plugins_loaded', 'aaweb_ab_bootstrap' );

/**
 * Bootstrap plugin.
 *
 * @return void
 */
function aaweb_ab_bootstrap() {
	AAWEB_Announcement_Bar::instance();
}
