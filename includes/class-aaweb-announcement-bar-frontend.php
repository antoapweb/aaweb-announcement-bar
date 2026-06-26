<?php
/**
 * Frontend output.
 *
 * @package AAWEB_Announcement_Bar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend class.
 */
class AAWEB_Announcement_Bar_Frontend {

	/**
	 * Settings helper.
	 *
	 * @var AAWEB_Announcement_Bar_Settings
	 */
	private $settings_helper;

	/**
	 * Rendered flag.
	 *
	 * @var bool
	 */
	private $rendered = false;

	/**
	 * Constructor.
	 *
	 * @param AAWEB_Announcement_Bar_Settings $settings_helper Settings helper.
	 */
	public function __construct( $settings_helper ) {
		$this->settings_helper = $settings_helper;

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_body_open', array( $this, 'render' ), 5 );
		add_action( 'wp_footer', array( $this, 'render_fallback' ), 5 );
		add_shortcode( 'aaweb_announcement_bar_preview', array( $this, 'shortcode_preview' ) );
	}

	/**
	 * Enqueue frontend CSS/JS when needed.
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		$settings = $this->settings_helper->get();

		if ( ! $this->should_display( $settings ) && ! is_admin() ) {
			return;
		}

		wp_enqueue_style(
			'aaweb-ab-public',
			AAWEB_AB_URL . 'assets/css/public.css',
			array(),
			AAWEB_AB_VERSION
		);

		wp_enqueue_script(
			'aaweb-ab-public',
			AAWEB_AB_URL . 'assets/js/public.js',
			array(),
			AAWEB_AB_VERSION,
			true
		);
	}

	/**
	 * Fallback render for themes without wp_body_open.
	 *
	 * @return void
	 */
	public function render_fallback() {
		if ( did_action( 'wp_body_open' ) ) {
			return;
		}

		$this->render();
	}

	/**
	 * Render live bar.
	 *
	 * @return void
	 */
	public function render() {
		if ( $this->rendered ) {
			return;
		}

		$settings = $this->settings_helper->get();

		if ( ! $this->should_display( $settings ) ) {
			return;
		}

		$this->rendered = true;

		echo $this->get_markup( $settings, false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Shortcode preview.
	 *
	 * @return string
	 */
	public function shortcode_preview() {
		$settings = $this->settings_helper->get();

		wp_enqueue_style( 'aaweb-ab-public', AAWEB_AB_URL . 'assets/css/public.css', array(), AAWEB_AB_VERSION );

		return $this->get_markup( $settings, true );
	}

	/**
	 * Check display rules.
	 *
	 * @param array<string,mixed> $settings Settings.
	 * @return bool
	 */
	private function should_display( $settings ) {
		if ( empty( $settings['enabled'] ) || is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
			return false;
		}

		if ( ! empty( $_GET['elementor-preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return false;
		}

		if ( ! $this->is_within_schedule( $settings ) || ! $this->device_matches( $settings ) || $this->excluded_by_page_id( $settings ) ) {
			return false;
		}

		if ( 'homepage' === $settings['display_rule'] && ! is_front_page() ) {
			return false;
		}

		if ( 'exclude_wc_pages' === $settings['display_rule'] && $this->is_woocommerce_excluded_page() ) {
			return false;
		}

		return true;
	}

	/**
	 * Check WooCommerce excluded pages.
	 *
	 * @return bool
	 */
	private function is_woocommerce_excluded_page() {
		return ( function_exists( 'is_cart' ) && is_cart() )
			|| ( function_exists( 'is_checkout' ) && is_checkout() )
			|| ( function_exists( 'is_account_page' ) && is_account_page() );
	}

	/**
	 * Schedule check.
	 *
	 * @param array<string,mixed> $settings Settings.
	 * @return bool
	 */
	private function is_within_schedule( $settings ) {
		$today = current_time( 'Y-m-d' );

		if ( ! empty( $settings['start_date'] ) && $today < $settings['start_date'] ) {
			return false;
		}

		if ( ! empty( $settings['end_date'] ) && $today > $settings['end_date'] ) {
			return false;
		}

		return true;
	}

	/**
	 * Device check.
	 *
	 * @param array<string,mixed> $settings Settings.
	 * @return bool
	 */
	private function device_matches( $settings ) {
		$is_mobile = function_exists( 'wp_is_mobile' ) && wp_is_mobile();

		if ( 'desktop' === $settings['device_visibility'] && $is_mobile ) {
			return false;
		}

		if ( 'mobile' === $settings['device_visibility'] && ! $is_mobile ) {
			return false;
		}

		return true;
	}

	/**
	 * Page ID exclusions.
	 *
	 * @param array<string,mixed> $settings Settings.
	 * @return bool
	 */
	private function excluded_by_page_id( $settings ) {
		if ( empty( $settings['exclude_page_ids'] ) ) {
			return false;
		}

		$ids = array_filter( array_map( 'absint', explode( ',', (string) $settings['exclude_page_ids'] ) ) );

		return is_singular() && in_array( get_queried_object_id(), $ids, true );
	}

	/**
	 * Render markup.
	 *
	 * @param array<string,mixed> $settings Settings.
	 * @param bool                $preview Preview mode.
	 * @return string
	 */
	private function get_markup( $settings, $preview = false ) {
		$content_html = $this->get_content_html( $settings );
		$button_html  = $this->get_button_html( $settings );

		if ( '' === trim( wp_strip_all_tags( $content_html . $button_html ) ) && '' === trim( $content_html . $button_html ) ) {
			return '';
		}

		$bar_id       = $preview ? 'aaweb-announcement-bar-preview' : 'aaweb-announcement-bar';
		$hash         = $this->get_version_hash( $settings );
		$dismissible  = ! empty( $settings['dismissible'] ) && ! $preview;
		$wrapper_attr = $this->get_wrapper_attributes( $settings, $bar_id, $hash, $dismissible );

		ob_start();
		?>
		<div <?php echo $wrapper_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div class="aaweb-ab-inner">
				<div class="aaweb-ab-main">
					<?php if ( '' !== $button_html && 'before' === $settings['button_position'] ) : ?>
						<div class="aaweb-ab-cta-wrap"><?php echo $button_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>
					<div class="aaweb-ab-content"><?php echo $content_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php if ( '' !== $button_html && 'after' === $settings['button_position'] ) : ?>
						<div class="aaweb-ab-cta-wrap"><?php echo $button_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>
				</div>
				<?php if ( $dismissible ) : ?>
					<button type="button" class="aaweb-ab-close" aria-label="<?php esc_attr_e( 'Close announcement', 'aaweb-announcement-bar' ); ?>">&times;</button>
				<?php endif; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get wrapper attributes.
	 *
	 * @param array<string,mixed> $settings Settings.
	 * @param string              $bar_id ID.
	 * @param string              $hash Version hash.
	 * @param bool                $dismissible Dismissible.
	 * @return string
	 */
	private function get_wrapper_attributes( $settings, $bar_id, $hash, $dismissible ) {
		$classes = array(
			'aaweb-ab-wrap',
			'aaweb-ab-align-' . sanitize_html_class( $settings['horizontal_align'] ),
			'aaweb-ab-valign-' . sanitize_html_class( $settings['vertical_align'] ),
		);

		if ( ! empty( $settings['sticky'] ) ) {
			$classes[] = 'is-sticky';
		}

		if ( ! empty( $settings['border_bottom'] ) ) {
			$classes[] = 'has-border-bottom';
		}

		if ( $dismissible ) {
			$classes[] = 'is-dismissible';
		}

		if ( ! empty( $settings['custom_class'] ) ) {
			$classes[] = sanitize_html_class( $settings['custom_class'] );
		}

		$style_parts = array(
			'--aaweb-ab-bg:' . esc_attr( $settings['background_color'] ),
			'--aaweb-ab-text:' . esc_attr( $settings['text_color'] ),
			'--aaweb-ab-link:' . esc_attr( $settings['link_color'] ),
			'--aaweb-ab-border:' . esc_attr( $settings['border_color'] ),
			'--aaweb-ab-font-size:' . (int) $settings['font_size'] . 'px',
			'--aaweb-ab-font-weight:' . esc_attr( $settings['font_weight'] ),
			'--aaweb-ab-btn-bg:' . esc_attr( $settings['button_bg_color'] ),
			'--aaweb-ab-btn-text:' . esc_attr( $settings['button_text_color'] ),
			'--aaweb-ab-btn-hover-bg:' . esc_attr( $settings['button_hover_bg_color'] ),
			'--aaweb-ab-btn-hover-text:' . esc_attr( $settings['button_hover_text_color'] ),
			'--aaweb-ab-btn-border:' . esc_attr( $settings['button_border_color'] ),
			'--aaweb-ab-btn-border-width:' . (int) $settings['button_border_width'] . 'px',
			'--aaweb-ab-btn-radius:' . (int) $settings['button_radius'] . 'px',
			'--aaweb-ab-btn-py:' . (int) $settings['button_padding_y'] . 'px',
			'--aaweb-ab-btn-px:' . (int) $settings['button_padding_x'] . 'px',
			'--aaweb-ab-btn-font-size:' . (int) $settings['button_font_size'] . 'px',
			'--aaweb-ab-btn-font-weight:' . esc_attr( $settings['button_font_weight'] ),
			'--aaweb-ab-btn-ml:' . (int) $settings['button_margin_left'] . 'px',
			'--aaweb-ab-btn-mr:' . (int) $settings['button_margin_right'] . 'px',
		);

		if ( ! empty( $settings['container_width'] ) ) {
			$style_parts[] = '--aaweb-ab-width:' . (int) $settings['container_width'] . 'px';
		}

		$style = implode( ';', $style_parts ) . ';';

		return sprintf(
			'id="%1$s" class="%2$s" role="status" aria-label="%3$s" style="%4$s" data-dismiss-days="%5$d" data-version-hash="%6$s"',
			esc_attr( $bar_id ),
			esc_attr( implode( ' ', $classes ) ),
			esc_attr__( 'Site announcement', 'aaweb-announcement-bar' ),
			esc_attr( $style ),
			(int) $settings['dismiss_days'],
			esc_attr( $hash )
		);
	}

	/**
	 * Get safe content HTML without CTA link.
	 *
	 * @param array<string,mixed> $settings Settings.
	 * @return string
	 */
	private function get_content_html( $settings ) {
		if ( 'html' === $settings['content_mode'] && ! empty( $settings['html_content'] ) ) {
			return wp_kses_post( $settings['html_content'] );
		}

		return esc_html( $settings['text'] );
	}

	/**
	 * Get CTA button markup.
	 *
	 * @param array<string,mixed> $settings Settings.
	 * @return string
	 */
	private function get_button_html( $settings ) {
		if ( empty( $settings['button_enabled'] ) || empty( $settings['link_url'] ) || empty( $settings['link_text'] ) ) {
			return '';
		}

		$classes = array( 'aaweb-ab-button' );

		if ( 'desktop' === $settings['button_visibility'] ) {
			$classes[] = 'aaweb-ab-button-desktop-only';
		}

		if ( 'mobile' === $settings['button_visibility'] ) {
			$classes[] = 'aaweb-ab-button-mobile-only';
		}

		if ( 'none' !== $settings['button_animation'] ) {
			$classes[] = 'has-animation-' . sanitize_html_class( $settings['button_animation'] );
		}

		$rel = '_blank' === $settings['link_target'] ? ' rel="noopener noreferrer"' : '';

		return sprintf(
			'<a href="%1$s" class="%2$s" target="%3$s"%4$s>%5$s</a>',
			esc_url( $settings['link_url'] ),
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( $settings['link_target'] ),
			$rel, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html( $settings['link_text'] )
		);
	}

	/**
	 * Get content version hash for dismiss cookies.
	 *
	 * @param array<string,mixed> $settings Settings.
	 * @return string
	 */
	private function get_version_hash( $settings ) {
		$source = wp_json_encode(
			array(
				'content_mode'      => $settings['content_mode'],
				'text'              => $settings['text'],
				'html_content'      => $settings['html_content'],
				'button_enabled'    => $settings['button_enabled'],
				'link_url'          => $settings['link_url'],
				'link_text'         => $settings['link_text'],
				'button_position'   => $settings['button_position'],
				'horizontal_align'  => $settings['horizontal_align'],
				'vertical_align'    => $settings['vertical_align'],
				'button_visibility' => $settings['button_visibility'],
				'start_date'        => $settings['start_date'],
				'end_date'          => $settings['end_date'],
				'device_visibility' => $settings['device_visibility'],
			)
		);

		return substr( md5( (string) $source ), 0, 12 );
	}
}
