<?php
/**
 * Settings helper.
 *
 * @package AAWEB_Announcement_Bar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings storage and sanitization.
 */
class AAWEB_Announcement_Bar_Settings {

	/**
	 * Option key.
	 */
	const OPTION_KEY = 'aaweb_ab_settings';

	/**
	 * Get defaults.
	 *
	 * @return array<string,mixed>
	 */
	public function defaults() {
		return array(
			'enabled'           => 0,
			'content_mode'      => 'text',
			'text'              => __( 'Write your announcement message here.', 'aaweb-announcement-bar' ),
			'html_content'      => '',
			'background_color'  => '#184436',
			'text_color'        => '#ffffff',
			'link_color'        => '#ffffff',
			'border_color'      => '#184436',
			'font_size'         => 14,
			'font_weight'       => '500',
			'horizontal_align'  => 'center',
			'vertical_align'    => 'center',
			'button_enabled'        => 1,
			'link_url'              => '',
			'link_text'             => __( 'Learn more', 'aaweb-announcement-bar' ),
			'link_target'           => '_self',
			'button_bg_color'       => '#ffffff',
			'button_text_color'     => '#184436',
			'button_hover_bg_color' => '#f2f2f2',
			'button_hover_text_color' => '#184436',
			'button_border_color'   => '#ffffff',
			'button_border_width'   => 1,
			'button_radius'         => 999,
			'button_padding_y'      => 6,
			'button_padding_x'      => 14,
			'button_font_size'      => 14,
			'button_font_weight'    => '700',
			'button_position'       => 'after',
			'button_margin_left'    => 0,
			'button_margin_right'   => 0,
			'button_visibility'     => 'all',
			'button_animation'      => 'none',
			'display_rule'      => 'sitewide',
			'exclude_page_ids'  => '',
			'dismissible'       => 0,
			'dismiss_days'      => 7,
			'start_date'        => '',
			'end_date'          => '',
			'device_visibility' => 'all',
			'sticky'            => 0,
			'border_bottom'     => 0,
			'container_width'   => 1400,
			'custom_class'      => '',
		);
	}

	/**
	 * Get saved settings.
	 *
	 * @return array<string,mixed>
	 */
	public function get() {
		$saved = get_option( self::OPTION_KEY, array() );

		if ( ! is_array( $saved ) ) {
			$saved = array();
		}

		return wp_parse_args( $saved, $this->defaults() );
	}

	/**
	 * Sanitize settings.
	 *
	 * @param mixed $input Raw input.
	 * @return array<string,mixed>
	 */
	public function sanitize( $input ) {
		$defaults = $this->defaults();
		$input    = is_array( $input ) ? $input : array();

		$output = array(
			'enabled'           => ! empty( $input['enabled'] ) ? 1 : 0,
			'content_mode'      => isset( $input['content_mode'] ) ? sanitize_key( $input['content_mode'] ) : $defaults['content_mode'],
			'text'              => isset( $input['text'] ) ? sanitize_textarea_field( wp_unslash( $input['text'] ) ) : $defaults['text'],
			'html_content'      => isset( $input['html_content'] ) ? wp_kses_post( wp_unslash( $input['html_content'] ) ) : '',
			'background_color'  => isset( $input['background_color'] ) ? sanitize_hex_color( wp_unslash( $input['background_color'] ) ) : $defaults['background_color'],
			'text_color'        => isset( $input['text_color'] ) ? sanitize_hex_color( wp_unslash( $input['text_color'] ) ) : $defaults['text_color'],
			'link_color'        => isset( $input['link_color'] ) ? sanitize_hex_color( wp_unslash( $input['link_color'] ) ) : $defaults['link_color'],
			'border_color'      => isset( $input['border_color'] ) ? sanitize_hex_color( wp_unslash( $input['border_color'] ) ) : $defaults['border_color'],
			'font_size'         => isset( $input['font_size'] ) ? absint( $input['font_size'] ) : $defaults['font_size'],
			'font_weight'       => isset( $input['font_weight'] ) ? sanitize_key( wp_unslash( $input['font_weight'] ) ) : $defaults['font_weight'],
			'horizontal_align'  => isset( $input['horizontal_align'] ) ? sanitize_key( wp_unslash( $input['horizontal_align'] ) ) : $defaults['horizontal_align'],
			'vertical_align'    => isset( $input['vertical_align'] ) ? sanitize_key( wp_unslash( $input['vertical_align'] ) ) : $defaults['vertical_align'],
			'button_enabled'        => ! empty( $input['button_enabled'] ) ? 1 : 0,
			'link_url'              => isset( $input['link_url'] ) ? esc_url_raw( trim( wp_unslash( $input['link_url'] ) ) ) : '',
			'link_text'             => isset( $input['link_text'] ) ? sanitize_text_field( wp_unslash( $input['link_text'] ) ) : $defaults['link_text'],
			'link_target'           => isset( $input['link_target'] ) ? sanitize_key( wp_unslash( $input['link_target'] ) ) : $defaults['link_target'],
			'button_bg_color'       => isset( $input['button_bg_color'] ) ? sanitize_hex_color( wp_unslash( $input['button_bg_color'] ) ) : $defaults['button_bg_color'],
			'button_text_color'     => isset( $input['button_text_color'] ) ? sanitize_hex_color( wp_unslash( $input['button_text_color'] ) ) : $defaults['button_text_color'],
			'button_hover_bg_color' => isset( $input['button_hover_bg_color'] ) ? sanitize_hex_color( wp_unslash( $input['button_hover_bg_color'] ) ) : $defaults['button_hover_bg_color'],
			'button_hover_text_color' => isset( $input['button_hover_text_color'] ) ? sanitize_hex_color( wp_unslash( $input['button_hover_text_color'] ) ) : $defaults['button_hover_text_color'],
			'button_border_color'   => isset( $input['button_border_color'] ) ? sanitize_hex_color( wp_unslash( $input['button_border_color'] ) ) : $defaults['button_border_color'],
			'button_border_width'   => isset( $input['button_border_width'] ) ? absint( $input['button_border_width'] ) : $defaults['button_border_width'],
			'button_radius'         => isset( $input['button_radius'] ) ? absint( $input['button_radius'] ) : $defaults['button_radius'],
			'button_padding_y'      => isset( $input['button_padding_y'] ) ? absint( $input['button_padding_y'] ) : $defaults['button_padding_y'],
			'button_padding_x'      => isset( $input['button_padding_x'] ) ? absint( $input['button_padding_x'] ) : $defaults['button_padding_x'],
			'button_font_size'      => isset( $input['button_font_size'] ) ? absint( $input['button_font_size'] ) : $defaults['button_font_size'],
			'button_font_weight'    => isset( $input['button_font_weight'] ) ? sanitize_key( wp_unslash( $input['button_font_weight'] ) ) : $defaults['button_font_weight'],
			'button_position'       => isset( $input['button_position'] ) ? sanitize_key( wp_unslash( $input['button_position'] ) ) : $defaults['button_position'],
			'button_margin_left'    => isset( $input['button_margin_left'] ) ? absint( $input['button_margin_left'] ) : $defaults['button_margin_left'],
			'button_margin_right'   => isset( $input['button_margin_right'] ) ? absint( $input['button_margin_right'] ) : $defaults['button_margin_right'],
			'button_visibility'     => isset( $input['button_visibility'] ) ? sanitize_key( wp_unslash( $input['button_visibility'] ) ) : $defaults['button_visibility'],
			'button_animation'      => isset( $input['button_animation'] ) ? sanitize_key( wp_unslash( $input['button_animation'] ) ) : $defaults['button_animation'],
			'display_rule'      => isset( $input['display_rule'] ) ? sanitize_key( wp_unslash( $input['display_rule'] ) ) : $defaults['display_rule'],
			'exclude_page_ids'  => isset( $input['exclude_page_ids'] ) ? $this->sanitize_page_ids( wp_unslash( $input['exclude_page_ids'] ) ) : '',
			'dismissible'       => ! empty( $input['dismissible'] ) ? 1 : 0,
			'dismiss_days'      => isset( $input['dismiss_days'] ) ? absint( $input['dismiss_days'] ) : $defaults['dismiss_days'],
			'start_date'        => isset( $input['start_date'] ) ? sanitize_text_field( wp_unslash( $input['start_date'] ) ) : '',
			'end_date'          => isset( $input['end_date'] ) ? sanitize_text_field( wp_unslash( $input['end_date'] ) ) : '',
			'device_visibility' => isset( $input['device_visibility'] ) ? sanitize_key( wp_unslash( $input['device_visibility'] ) ) : $defaults['device_visibility'],
			'sticky'            => ! empty( $input['sticky'] ) ? 1 : 0,
			'border_bottom'     => ! empty( $input['border_bottom'] ) ? 1 : 0,
			'container_width'   => ( isset( $input['container_width'] ) && '' !== trim( (string) wp_unslash( $input['container_width'] ) ) ) ? absint( $input['container_width'] ) : 0,
			'custom_class'      => isset( $input['custom_class'] ) ? sanitize_html_class( wp_unslash( $input['custom_class'] ) ) : '',
		);

		if ( ! in_array( $output['content_mode'], array( 'text', 'html' ), true ) ) {
			$output['content_mode'] = 'text';
		}

		if ( '' === $output['text'] ) {
			$output['text'] = $defaults['text'];
		}

		foreach ( array( 'background_color', 'text_color', 'link_color', 'border_color', 'button_bg_color', 'button_text_color', 'button_hover_bg_color', 'button_hover_text_color', 'button_border_color' ) as $color_key ) {
			if ( empty( $output[ $color_key ] ) ) {
				$output[ $color_key ] = $defaults[ $color_key ];
			}
		}

		$output['font_size']           = min( 40, max( 10, (int) $output['font_size'] ) );
		$output['dismiss_days']        = max( 1, (int) $output['dismiss_days'] );
		if ( 0 < (int) $output['container_width'] ) {
			$output['container_width'] = min( 2400, max( 400, (int) $output['container_width'] ) );
		} else {
			$output['container_width'] = 0;
		}
		$output['button_border_width'] = min( 8, max( 0, (int) $output['button_border_width'] ) );
		$output['button_radius']       = min( 999, max( 0, (int) $output['button_radius'] ) );
		$output['button_padding_y']    = min( 30, max( 0, (int) $output['button_padding_y'] ) );
		$output['button_padding_x']    = min( 60, max( 0, (int) $output['button_padding_x'] ) );
		$output['button_font_size']    = min( 40, max( 10, (int) $output['button_font_size'] ) );
		$output['button_margin_left']  = min( 80, max( 0, (int) $output['button_margin_left'] ) );
		$output['button_margin_right'] = min( 80, max( 0, (int) $output['button_margin_right'] ) );

		if ( ! in_array( $output['font_weight'], array( '400', '500', '600', '700' ), true ) ) {
			$output['font_weight'] = '500';
		}

		if ( ! in_array( $output['horizontal_align'], array( 'left', 'center', 'right' ), true ) ) {
			$output['horizontal_align'] = 'center';
		}

		if ( ! in_array( $output['vertical_align'], array( 'top', 'center', 'bottom' ), true ) ) {
			$output['vertical_align'] = 'center';
		}

		if ( ! in_array( $output['button_font_weight'], array( '400', '500', '600', '700' ), true ) ) {
			$output['button_font_weight'] = '700';
		}

		if ( ! in_array( $output['link_target'], array( '_self', '_blank' ), true ) ) {
			$output['link_target'] = '_self';
		}

		if ( ! in_array( $output['button_position'], array( 'before', 'after' ), true ) ) {
			$output['button_position'] = 'after';
		}

		if ( ! in_array( $output['button_visibility'], array( 'all', 'desktop', 'mobile' ), true ) ) {
			$output['button_visibility'] = 'all';
		}

		if ( ! in_array( $output['button_animation'], array( 'none', 'pulse', 'bounce', 'glow' ), true ) ) {
			$output['button_animation'] = 'none';
		}

		if ( ! in_array( $output['display_rule'], array( 'sitewide', 'homepage', 'exclude_wc_pages' ), true ) ) {
			$output['display_rule'] = 'sitewide';
		}

		if ( ! in_array( $output['device_visibility'], array( 'all', 'desktop', 'mobile' ), true ) ) {
			$output['device_visibility'] = 'all';
		}

		if ( ! $this->is_valid_date( $output['start_date'] ) ) {
			$output['start_date'] = '';
		}

		if ( ! $this->is_valid_date( $output['end_date'] ) ) {
			$output['end_date'] = '';
		}

		return $output;
	}

	/**
	 * Sanitize comma separated page IDs.
	 *
	 * @param string $value Raw value.
	 * @return string
	 */
	private function sanitize_page_ids( $value ) {
		$ids = array_filter( array_map( 'absint', array_map( 'trim', explode( ',', $value ) ) ) );

		return implode( ',', array_unique( $ids ) );
	}

	/**
	 * Validate date string.
	 *
	 * @param string $date Date.
	 * @return bool
	 */
	private function is_valid_date( $date ) {
		return '' === $date || 1 === preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date );
	}
}
