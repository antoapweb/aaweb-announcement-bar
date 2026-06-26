<?php
/**
 * Admin settings page.
 *
 * @package AAWEB_Announcement_Bar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin class.
 */
class AAWEB_Announcement_Bar_Admin {

	/**
	 * Settings helper.
	 *
	 * @var AAWEB_Announcement_Bar_Settings
	 */
	private $settings_helper;

	/**
	 * Constructor.
	 *
	 * @param AAWEB_Announcement_Bar_Settings $settings_helper Settings helper.
	 */
	public function __construct( $settings_helper ) {
		$this->settings_helper = $settings_helper;

		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Add settings page.
	 *
	 * @return void
	 */
	public function add_menu() {
		add_options_page(
			esc_html__( 'AAWEB Announcement Bar', 'aaweb-announcement-bar' ),
			esc_html__( 'AAWEB Announcement Bar', 'aaweb-announcement-bar' ),
			'manage_options',
			'aaweb-announcement-bar',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'aaweb_ab_group',
			AAWEB_Announcement_Bar_Settings::OPTION_KEY,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this->settings_helper, 'sanitize' ),
			)
		);
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @param string $hook Current hook.
	 * @return void
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'settings_page_aaweb-announcement-bar' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'aaweb-ab-admin',
			AAWEB_AB_URL . 'assets/css/admin.css',
			array(),
			AAWEB_AB_VERSION
		);

		wp_enqueue_script(
			'aaweb-ab-admin',
			AAWEB_AB_URL . 'assets/js/admin.js',
			array(),
			AAWEB_AB_VERSION,
			true
		);
	}

	/**
	 * Render settings page.
	 *
	 * @return void
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings     = $this->settings_helper->get();
		$enabled_text = ! empty( $settings['enabled'] ) ? __( 'Enabled', 'aaweb-announcement-bar' ) : __( 'Disabled', 'aaweb-announcement-bar' );
		?>
		<div class="wrap aaweb-ab-admin-wrap">
			<form method="post" action="options.php" class="aaweb-ab-admin-form">
				<?php settings_fields( 'aaweb_ab_group' ); ?>

				<div class="aaweb-ab-hero">
					<div class="aaweb-ab-hero-main">
						<div class="aaweb-ab-brandmark">AA</div>
						<div>
							<p class="aaweb-ab-kicker">AAWEB Plugin Suite</p>
							<h1><?php esc_html_e( 'Announcement Bar', 'aaweb-announcement-bar' ); ?></h1>
							<p><?php esc_html_e( 'Build a polished top notification bar with CTA button styling, scheduling, responsive rules, WooCommerce exclusions and visitor dismiss control.', 'aaweb-announcement-bar' ); ?></p>
						</div>
					</div>
					<div class="aaweb-ab-hero-actions">
						<div class="aaweb-ab-status">
							<span class="aaweb-ab-dot <?php echo ! empty( $settings['enabled'] ) ? 'is-on' : 'is-off'; ?>"></span>
							<span><?php echo esc_html( $enabled_text ); ?></span>
						</div>
						<?php submit_button( __( 'Save settings', 'aaweb-announcement-bar' ), 'primary large', 'submit', false, array( 'class' => 'aaweb-ab-top-save' ) ); ?>
					</div>
				</div>

				<div class="aaweb-ab-summary-row">
					<div class="aaweb-ab-stat-card"><strong><?php esc_html_e( 'Display', 'aaweb-announcement-bar' ); ?></strong><span><?php echo esc_html( ucfirst( str_replace( '_', ' ', (string) $settings['display_rule'] ) ) ); ?></span></div>
					<div class="aaweb-ab-stat-card"><strong><?php esc_html_e( 'Devices', 'aaweb-announcement-bar' ); ?></strong><span><?php echo esc_html( ucfirst( (string) $settings['device_visibility'] ) ); ?></span></div>
					<div class="aaweb-ab-stat-card"><strong><?php esc_html_e( 'CTA', 'aaweb-announcement-bar' ); ?></strong><span><?php echo ! empty( $settings['button_enabled'] ) ? esc_html__( 'Active', 'aaweb-announcement-bar' ) : esc_html__( 'Off', 'aaweb-announcement-bar' ); ?></span></div>
					<div class="aaweb-ab-stat-card"><strong><?php esc_html_e( 'Width', 'aaweb-announcement-bar' ); ?></strong><span><?php echo ! empty( $settings['container_width'] ) ? esc_html( (int) $settings['container_width'] . 'px' ) : esc_html__( 'Full width', 'aaweb-announcement-bar' ); ?></span></div>
				</div>

				<div class="aaweb-ab-shell">
					<nav class="aaweb-ab-tabs" aria-label="<?php esc_attr_e( 'Announcement bar settings sections', 'aaweb-announcement-bar' ); ?>">
						<a href="#aaweb-ab-panel-content" class="is-active" data-aaweb-ab-tab="content"><span>✍</span><?php esc_html_e( 'Content', 'aaweb-announcement-bar' ); ?></a>
						<a href="#aaweb-ab-panel-button" data-aaweb-ab-tab="button"><span>↗</span><?php esc_html_e( 'CTA Button', 'aaweb-announcement-bar' ); ?></a>
						<a href="#aaweb-ab-panel-design" data-aaweb-ab-tab="design"><span>🎨</span><?php esc_html_e( 'Design', 'aaweb-announcement-bar' ); ?></a>
						<a href="#aaweb-ab-panel-rules" data-aaweb-ab-tab="rules"><span>👁</span><?php esc_html_e( 'Rules', 'aaweb-announcement-bar' ); ?></a>
						<a href="#aaweb-ab-panel-advanced" data-aaweb-ab-tab="advanced"><span>⚙</span><?php esc_html_e( 'Advanced', 'aaweb-announcement-bar' ); ?></a>
					</nav>

					<div class="aaweb-ab-content-area">
						<div class="aaweb-ab-panels">
							<section id="aaweb-ab-panel-content" class="aaweb-ab-panel is-active" data-aaweb-ab-panel="content">
								<?php $this->section_heading( '✍', __( 'Content & message', 'aaweb-announcement-bar' ), __( 'Choose plain text or safe HTML and control the announcement copy.', 'aaweb-announcement-bar' ) ); ?>
								<div class="aaweb-ab-card-grid">
									<?php $this->checkbox_field( 'enabled', __( 'Enable announcement bar', 'aaweb-announcement-bar' ), $settings, __( 'Turn the frontend bar on or off.', 'aaweb-announcement-bar' ) ); ?>
									<?php $this->select_field( 'content_mode', __( 'Content mode', 'aaweb-announcement-bar' ), $settings, array( 'text' => __( 'Plain text', 'aaweb-announcement-bar' ), 'html' => __( 'Safe HTML', 'aaweb-announcement-bar' ) ) ); ?>
								</div>
								<div class="aaweb-ab-content-mode-field" data-aaweb-ab-content-field="text">
									<?php $this->textarea_field( 'text', __( 'Text message', 'aaweb-announcement-bar' ), $settings, 4, __( 'Used when content mode is Plain text.', 'aaweb-announcement-bar' ) ); ?>
								</div>
								<div class="aaweb-ab-content-mode-field" data-aaweb-ab-content-field="html">
									<?php $this->textarea_field( 'html_content', __( 'HTML content', 'aaweb-announcement-bar' ), $settings, 7, __( 'Used when content mode is Safe HTML. WordPress safe post HTML filtering is applied.', 'aaweb-announcement-bar' ) ); ?>
								</div>
							</section>

							<section id="aaweb-ab-panel-button" class="aaweb-ab-panel" data-aaweb-ab-panel="button">
								<?php $this->section_heading( '↗', __( 'CTA button', 'aaweb-announcement-bar' ), __( 'Style the call-to-action independently from the text so both stay aligned and clickable.', 'aaweb-announcement-bar' ) ); ?>
								<div class="aaweb-ab-card-grid">
									<?php $this->checkbox_field( 'button_enabled', __( 'Enable CTA button', 'aaweb-announcement-bar' ), $settings ); ?>
									<?php $this->select_field( 'link_target', __( 'Open link', 'aaweb-announcement-bar' ), $settings, array( '_self' => __( 'Same tab', 'aaweb-announcement-bar' ), '_blank' => __( 'New tab', 'aaweb-announcement-bar' ) ) ); ?>
									<?php $this->text_field( 'link_url', __( 'Button URL', 'aaweb-announcement-bar' ), $settings, 'url', 'https://example.com' ); ?>
									<?php $this->text_field( 'link_text', __( 'Button text', 'aaweb-announcement-bar' ), $settings ); ?>
									<?php $this->select_field( 'button_position', __( 'Button position', 'aaweb-announcement-bar' ), $settings, array( 'after' => __( 'After content', 'aaweb-announcement-bar' ), 'before' => __( 'Before content', 'aaweb-announcement-bar' ) ) ); ?>
									<?php $this->select_field( 'button_visibility', __( 'Button visibility', 'aaweb-announcement-bar' ), $settings, array( 'all' => __( 'All devices', 'aaweb-announcement-bar' ), 'desktop' => __( 'Desktop only', 'aaweb-announcement-bar' ), 'mobile' => __( 'Mobile only', 'aaweb-announcement-bar' ) ) ); ?>
									<?php $this->select_field( 'button_animation', __( 'Button animation', 'aaweb-announcement-bar' ), $settings, array( 'none' => __( 'None', 'aaweb-announcement-bar' ), 'pulse' => __( 'Pulse', 'aaweb-announcement-bar' ), 'bounce' => __( 'Bounce', 'aaweb-announcement-bar' ), 'glow' => __( 'Glow', 'aaweb-announcement-bar' ) ) ); ?>
								</div>
								<div class="aaweb-ab-subtitle"><?php esc_html_e( 'Button style', 'aaweb-announcement-bar' ); ?></div>
								<div class="aaweb-ab-card-grid aaweb-ab-card-grid-3">
									<?php $this->text_field( 'button_bg_color', __( 'Background', 'aaweb-announcement-bar' ), $settings, 'color' ); ?>
									<?php $this->text_field( 'button_text_color', __( 'Text color', 'aaweb-announcement-bar' ), $settings, 'color' ); ?>
									<?php $this->text_field( 'button_hover_bg_color', __( 'Hover background', 'aaweb-announcement-bar' ), $settings, 'color' ); ?>
									<?php $this->text_field( 'button_hover_text_color', __( 'Hover text', 'aaweb-announcement-bar' ), $settings, 'color' ); ?>
									<?php $this->text_field( 'button_border_color', __( 'Border color', 'aaweb-announcement-bar' ), $settings, 'color' ); ?>
									<?php $this->number_field( 'button_border_width', __( 'Border width', 'aaweb-announcement-bar' ), $settings, 0, 8, 'px' ); ?>
									<?php $this->number_field( 'button_radius', __( 'Radius', 'aaweb-announcement-bar' ), $settings, 0, 999, 'px' ); ?>
									<?php $this->number_field( 'button_padding_y', __( 'Padding Y', 'aaweb-announcement-bar' ), $settings, 0, 30, 'px' ); ?>
									<?php $this->number_field( 'button_padding_x', __( 'Padding X', 'aaweb-announcement-bar' ), $settings, 0, 60, 'px' ); ?>
									<?php $this->number_field( 'button_font_size', __( 'Font size', 'aaweb-announcement-bar' ), $settings, 10, 40, 'px' ); ?>
									<?php $this->select_field( 'button_font_weight', __( 'Font weight', 'aaweb-announcement-bar' ), $settings, array( '400' => '400', '500' => '500', '600' => '600', '700' => '700' ) ); ?>
									<?php $this->number_field( 'button_margin_left', __( 'Margin left', 'aaweb-announcement-bar' ), $settings, 0, 80, 'px' ); ?>
									<?php $this->number_field( 'button_margin_right', __( 'Margin right', 'aaweb-announcement-bar' ), $settings, 0, 80, 'px' ); ?>
								</div>
							</section>

							<section id="aaweb-ab-panel-design" class="aaweb-ab-panel" data-aaweb-ab-panel="design">
								<?php $this->section_heading( '🎨', __( 'Design & layout', 'aaweb-announcement-bar' ), __( 'Control colors, typography, width and the horizontal / vertical alignment of text and button.', 'aaweb-announcement-bar' ) ); ?>
								<div class="aaweb-ab-card-grid aaweb-ab-card-grid-3">
									<?php $this->text_field( 'background_color', __( 'Background', 'aaweb-announcement-bar' ), $settings, 'color' ); ?>
									<?php $this->text_field( 'text_color', __( 'Text color', 'aaweb-announcement-bar' ), $settings, 'color' ); ?>
									<?php $this->text_field( 'link_color', __( 'Link color', 'aaweb-announcement-bar' ), $settings, 'color' ); ?>
									<?php $this->text_field( 'border_color', __( 'Border color', 'aaweb-announcement-bar' ), $settings, 'color' ); ?>
									<?php $this->number_field( 'font_size', __( 'Font size', 'aaweb-announcement-bar' ), $settings, 10, 40, 'px' ); ?>
									<?php $this->select_field( 'font_weight', __( 'Font weight', 'aaweb-announcement-bar' ), $settings, array( '400' => '400', '500' => '500', '600' => '600', '700' => '700' ) ); ?>
								</div>
								<div class="aaweb-ab-card-grid">
									<?php $this->number_field( 'container_width', __( 'Container width', 'aaweb-announcement-bar' ), $settings, 0, 2400, 'px', __( 'Leave empty or 0 for full width.', 'aaweb-announcement-bar' ), true ); ?>
									<?php $this->select_field( 'horizontal_align', __( 'Horizontal alignment', 'aaweb-announcement-bar' ), $settings, array( 'left' => __( 'Left', 'aaweb-announcement-bar' ), 'center' => __( 'Center', 'aaweb-announcement-bar' ), 'right' => __( 'Right', 'aaweb-announcement-bar' ) ) ); ?>
									<?php $this->select_field( 'vertical_align', __( 'Vertical alignment', 'aaweb-announcement-bar' ), $settings, array( 'top' => __( 'Top', 'aaweb-announcement-bar' ), 'center' => __( 'Middle', 'aaweb-announcement-bar' ), 'bottom' => __( 'Bottom', 'aaweb-announcement-bar' ) ) ); ?>
								</div>
								<div class="aaweb-ab-card-grid">
									<?php $this->checkbox_field( 'sticky', __( 'Sticky at top', 'aaweb-announcement-bar' ), $settings ); ?>
									<?php $this->checkbox_field( 'border_bottom', __( 'Show bottom border', 'aaweb-announcement-bar' ), $settings ); ?>
								</div>
							</section>

							<section id="aaweb-ab-panel-rules" class="aaweb-ab-panel" data-aaweb-ab-panel="rules">
								<?php $this->section_heading( '👁', __( 'Visibility & schedule', 'aaweb-announcement-bar' ), __( 'Choose where, when and on which devices the bar appears.', 'aaweb-announcement-bar' ) ); ?>
								<div class="aaweb-ab-card-grid">
									<?php $this->select_field( 'display_rule', __( 'Display rule', 'aaweb-announcement-bar' ), $settings, array( 'sitewide' => __( 'Sitewide', 'aaweb-announcement-bar' ), 'homepage' => __( 'Homepage only', 'aaweb-announcement-bar' ), 'exclude_wc_pages' => __( 'Everywhere except cart / checkout / account', 'aaweb-announcement-bar' ) ) ); ?>
									<?php $this->select_field( 'device_visibility', __( 'Devices', 'aaweb-announcement-bar' ), $settings, array( 'all' => __( 'All devices', 'aaweb-announcement-bar' ), 'desktop' => __( 'Desktop only', 'aaweb-announcement-bar' ), 'mobile' => __( 'Mobile only', 'aaweb-announcement-bar' ) ) ); ?>
									<?php $this->text_field( 'exclude_page_ids', __( 'Exclude page IDs', 'aaweb-announcement-bar' ), $settings, 'text', '12,34,56', __( 'Comma separated IDs.', 'aaweb-announcement-bar' ) ); ?>
									<?php $this->text_field( 'start_date', __( 'Start date', 'aaweb-announcement-bar' ), $settings, 'date' ); ?>
									<?php $this->text_field( 'end_date', __( 'End date', 'aaweb-announcement-bar' ), $settings, 'date' ); ?>
								</div>
								<div class="aaweb-ab-card-grid">
									<?php $this->checkbox_field( 'dismissible', __( 'Allow visitors to close the bar', 'aaweb-announcement-bar' ), $settings, __( 'Uses a small browser cookie/local storage key so the bar stays hidden for the selected days.', 'aaweb-announcement-bar' ) ); ?>
									<?php $this->number_field( 'dismiss_days', __( 'Show again after', 'aaweb-announcement-bar' ), $settings, 1, 365, __( 'days', 'aaweb-announcement-bar' ) ); ?>
								</div>
							</section>

							<section id="aaweb-ab-panel-advanced" class="aaweb-ab-panel" data-aaweb-ab-panel="advanced">
								<?php $this->section_heading( '⚙', __( 'Advanced', 'aaweb-announcement-bar' ), __( 'Developer-friendly options and preview helper.', 'aaweb-announcement-bar' ) ); ?>
								<div class="aaweb-ab-card-grid">
									<?php $this->text_field( 'custom_class', __( 'Custom CSS class', 'aaweb-announcement-bar' ), $settings, 'text', 'my-announcement-bar' ); ?>
									<div class="aaweb-ab-codebox">
										<span><?php esc_html_e( 'Preview shortcode', 'aaweb-announcement-bar' ); ?></span>
										<code>[aaweb_announcement_bar_preview]</code>
										<small><?php esc_html_e( 'The live bar is rendered automatically with wp_body_open when enabled.', 'aaweb-announcement-bar' ); ?></small>
									</div>
								</div>
							</section>
						</div>

						<aside class="aaweb-ab-preview-panel" aria-label="<?php esc_attr_e( 'Live preview', 'aaweb-announcement-bar' ); ?>">
							<div class="aaweb-ab-preview-header">
								<div>
									<strong><?php esc_html_e( 'Live preview', 'aaweb-announcement-bar' ); ?></strong>
									<span><?php esc_html_e( 'Visual guide based on current fields.', 'aaweb-announcement-bar' ); ?></span>
								</div>
								<span class="aaweb-ab-preview-badge"><?php esc_html_e( 'Preview', 'aaweb-announcement-bar' ); ?></span>
							</div>
							<div class="aaweb-ab-device-frame">
								<div class="aaweb-ab-live-preview aaweb-ab-live-align-<?php echo esc_attr( $settings['horizontal_align'] ); ?> aaweb-ab-live-valign-<?php echo esc_attr( $settings['vertical_align'] ); ?>" data-aaweb-ab-live-preview>
									<div class="aaweb-ab-live-main">
										<span class="aaweb-ab-live-text"><?php echo esc_html( $settings['text'] ); ?></span>
										<a href="#" class="aaweb-ab-live-button"><?php echo esc_html( $settings['link_text'] ); ?></a>
									</div>
									<button type="button" class="aaweb-ab-live-close" aria-label="<?php esc_attr_e( 'Close preview', 'aaweb-announcement-bar' ); ?>">×</button>
								</div>
								<div class="aaweb-ab-fake-page"><span></span><span></span><span></span></div>
							</div>
							<div class="aaweb-ab-tips">
								<strong><?php esc_html_e( 'Layout note', 'aaweb-announcement-bar' ); ?></strong>
								<p><?php esc_html_e( 'Text and CTA button are rendered inside the same flex row. The close button stays isolated on the right so it cannot overlap the CTA.', 'aaweb-announcement-bar' ); ?></p>
							</div>
						</aside>
					</div>
				</div>

				<div class="aaweb-ab-savebar">
					<div>
						<strong><?php esc_html_e( 'AAWEB Announcement Bar', 'aaweb-announcement-bar' ); ?></strong>
						<span><?php esc_html_e( 'Save your settings to apply them on the frontend.', 'aaweb-announcement-bar' ); ?></span>
					</div>
					<?php submit_button( __( 'Save settings', 'aaweb-announcement-bar' ), 'primary large', 'submit', false ); ?>
				</div>
			</form>
		</div>
		<?php
	}

	/**
	 * Section heading.
	 *
	 * @param string $icon Icon.
	 * @param string $title Title.
	 * @param string $description Description.
	 * @return void
	 */
	private function section_heading( $icon, $title, $description ) {
		?>
		<div class="aaweb-ab-section-heading">
			<span class="aaweb-ab-section-icon"><?php echo esc_html( $icon ); ?></span>
			<div>
				<h2><?php echo esc_html( $title ); ?></h2>
				<p><?php echo esc_html( $description ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Field name helper.
	 *
	 * @param string $key Field key.
	 * @return string
	 */
	private function field_name( $key ) {
		return AAWEB_Announcement_Bar_Settings::OPTION_KEY . '[' . $key . ']';
	}

	/**
	 * Render checkbox.
	 *
	 * @param string              $key Key.
	 * @param string              $label Label.
	 * @param array<string,mixed> $settings Settings.
	 * @param string              $description Description.
	 * @return void
	 */
	private function checkbox_field( $key, $label, $settings, $description = '' ) {
		?>
		<label class="aaweb-ab-toggle">
			<input type="checkbox" name="<?php echo esc_attr( $this->field_name( $key ) ); ?>" value="1" <?php checked( ! empty( $settings[ $key ] ) ); ?>>
			<span class="aaweb-ab-toggle-ui" aria-hidden="true"></span>
			<span class="aaweb-ab-toggle-text">
				<strong><?php echo esc_html( $label ); ?></strong>
				<?php if ( '' !== $description ) : ?>
					<small><?php echo esc_html( $description ); ?></small>
				<?php endif; ?>
			</span>
		</label>
		<?php
	}

	/**
	 * Render text input.
	 *
	 * @param string              $key Key.
	 * @param string              $label Label.
	 * @param array<string,mixed> $settings Settings.
	 * @param string              $type Input type.
	 * @param string              $placeholder Placeholder.
	 * @param string              $description Description.
	 * @return void
	 */
	private function text_field( $key, $label, $settings, $type = 'text', $placeholder = '', $description = '' ) {
		?>
		<label class="aaweb-ab-field aaweb-ab-field-<?php echo esc_attr( $type ); ?>">
			<span><?php echo esc_html( $label ); ?></span>
			<input type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( $this->field_name( $key ) ); ?>" value="<?php echo esc_attr( $settings[ $key ] ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>">
			<?php if ( '' !== $description ) : ?>
				<small><?php echo esc_html( $description ); ?></small>
			<?php endif; ?>
		</label>
		<?php
	}

	/**
	 * Render textarea.
	 *
	 * @param string              $key Key.
	 * @param string              $label Label.
	 * @param array<string,mixed> $settings Settings.
	 * @param int                 $rows Rows.
	 * @param string              $description Description.
	 * @return void
	 */
	private function textarea_field( $key, $label, $settings, $rows = 4, $description = '' ) {
		?>
		<label class="aaweb-ab-field aaweb-ab-field-textarea">
			<span><?php echo esc_html( $label ); ?></span>
			<textarea name="<?php echo esc_attr( $this->field_name( $key ) ); ?>" rows="<?php echo esc_attr( $rows ); ?>"><?php echo esc_textarea( $settings[ $key ] ); ?></textarea>
			<?php if ( '' !== $description ) : ?>
				<small><?php echo esc_html( $description ); ?></small>
			<?php endif; ?>
		</label>
		<?php
	}

	/**
	 * Render number.
	 *
	 * @param string              $key Key.
	 * @param string              $label Label.
	 * @param array<string,mixed> $settings Settings.
	 * @param int                 $min Min.
	 * @param int                 $max Max.
	 * @param string              $suffix Suffix.
	 * @param string              $description Description.
	 * @param bool                $allow_empty Allow empty value.
	 * @return void
	 */
	private function number_field( $key, $label, $settings, $min, $max, $suffix = '', $description = '', $allow_empty = false ) {
		$value = isset( $settings[ $key ] ) ? $settings[ $key ] : '';
		if ( $allow_empty && empty( $value ) ) {
			$value = '';
		}
		?>
		<label class="aaweb-ab-field aaweb-ab-inline-field">
			<span><?php echo esc_html( $label ); ?></span>
			<div class="aaweb-ab-number-wrap">
				<input type="number" min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>" step="1" name="<?php echo esc_attr( $this->field_name( $key ) ); ?>" value="<?php echo esc_attr( $value ); ?>">
				<?php if ( '' !== $suffix ) : ?>
					<em><?php echo esc_html( $suffix ); ?></em>
				<?php endif; ?>
			</div>
			<?php if ( '' !== $description ) : ?>
				<small><?php echo esc_html( $description ); ?></small>
			<?php endif; ?>
		</label>
		<?php
	}

	/**
	 * Render select.
	 *
	 * @param string               $key Key.
	 * @param string               $label Label.
	 * @param array<string,mixed>  $settings Settings.
	 * @param array<string,string> $options Options.
	 * @return void
	 */
	private function select_field( $key, $label, $settings, $options ) {
		?>
		<label class="aaweb-ab-field">
			<span><?php echo esc_html( $label ); ?></span>
			<select name="<?php echo esc_attr( $this->field_name( $key ) ); ?>">
				<?php foreach ( $options as $value => $text ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $settings[ $key ], $value ); ?>><?php echo esc_html( $text ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<?php
	}
}
