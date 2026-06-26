=== AAWEB Announcement Bar ===
Contributors: antoapweb
Tags: announcement bar, notification bar, top bar, woocommerce, banner
Requires at least: 6.8
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.4.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight announcement bar with scheduling, dismiss button, device visibility and WooCommerce-safe display rules.

== Description ==

AAWEB Announcement Bar helps site owners add a clean top announcement bar without heavy page builders or marketing suites.

Features include:

* Enable or disable the bar from Settings.
* Plain text or safe HTML content.
* Optional CTA link.
* Custom colors, font size, font weight and container width.
* Sticky mode.
* Dismiss button with cookie-based hide period.
* Start and end dates.
* Desktop-only, mobile-only or all-device visibility.
* Sitewide, homepage-only or WooCommerce checkout-safe display rules.
* Manual page ID exclusions.
* Preview shortcode.

The plugin is intentionally lightweight and does not connect to any external services.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/` or install the ZIP from Plugins > Add New.
2. Activate the plugin.
3. Go to Settings > AAWEB Announcement Bar.
4. Configure your message and visibility rules.

== Frequently Asked Questions ==

= Does it work with WooCommerce? =

Yes. It includes an option to hide the bar from cart, checkout and my account pages.

= Does it work with Elementor? =

Yes. The bar renders through `wp_body_open`, which is supported by modern themes. A footer fallback is included for older themes.

= Can I add HTML? =

Yes. Safe HTML is allowed and filtered through WordPress post HTML sanitization.

= Can visitors close the bar? =

Yes. Enable the dismiss option and choose after how many days the bar should appear again.

= Is the plugin lightweight? =

Yes. It only loads a small CSS and JavaScript file on the frontend.

== Screenshots ==

1. Main dashboard and content settings.
2. CTA button customization options.
3. Design and styling controls.
4. Design and styling controls.
5. Display rules and scheduling options.

== Changelog ==

= 1.4.2 =
* Changed the default announcement bar state to disabled on new installations.

= 1.4.1 =
* Show only the active content editor based on Plain text / Safe HTML mode.

= 1.1.0 =
* Added full CTA button styling settings.
* Added CTA hover colors, border, radius, padding, font, margins, position, device visibility, and animations.
* Improved frontend layout for text, CTA button, and close button.

= 1.0.0 =
* Initial release.


== Changelog ==

= 1.4.1 =
* Show only the active content editor based on Plain text / Safe HTML mode.

= 1.1.0 =
* Added full CTA button styling settings.
* Added CTA hover colors, border, radius, padding, font, margins, position, device visibility, and animations.
* Improved frontend layout for text, CTA button, and close button.

= 1.0.3 =
* Removed manual textdomain loading to satisfy current WordPress.org Plugin Check recommendations.
