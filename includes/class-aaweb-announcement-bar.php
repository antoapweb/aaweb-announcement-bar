<?php
/**
 * Main plugin class.
 *
 * @package AAWEB_Announcement_Bar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin loader.
 */
final class AAWEB_Announcement_Bar {

	/**
	 * Singleton instance.
	 *
	 * @var AAWEB_Announcement_Bar|null
	 */
	private static $instance = null;

	/**
	 * Settings object.
	 *
	 * @var AAWEB_Announcement_Bar_Settings
	 */
	public $settings;

	/**
	 * Get instance.
	 *
	 * @return AAWEB_Announcement_Bar
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->settings = new AAWEB_Announcement_Bar_Settings();

		new AAWEB_Announcement_Bar_Admin( $this->settings );
		new AAWEB_Announcement_Bar_Frontend( $this->settings );

		add_filter( 'plugin_action_links_' . plugin_basename( AAWEB_AB_FILE ), array( $this, 'plugin_action_links' ) );
	}

	/**
	 * Add settings shortcut.
	 *
	 * @param array<string> $links Plugin links.
	 * @return array<string>
	 */
	public function plugin_action_links( $links ) {
		$settings_link = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( admin_url( 'options-general.php?page=aaweb-announcement-bar' ) ),
			esc_html__( 'Settings', 'aaweb-announcement-bar' )
		);

		array_unshift( $links, $settings_link );

		return $links;
	}
}
