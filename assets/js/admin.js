(function () {
	'use strict';

	var optionKey = 'aaweb_ab_settings';

	function field(name) {
		return document.querySelector('[name="' + optionKey + '[' + name + ']"]');
	}

	function value(name, fallback) {
		var el = field(name);
		if (!el) {
			return fallback;
		}
		if ('checkbox' === el.type) {
			return el.checked ? '1' : '';
		}
		return '' !== el.value ? el.value : fallback;
	}

	function applyTabs() {
		var tabs = document.querySelectorAll('[data-aaweb-ab-tab]');
		var panels = document.querySelectorAll('[data-aaweb-ab-panel]');
		tabs.forEach(function (tab) {
			tab.addEventListener('click', function (event) {
				event.preventDefault();
				var key = tab.getAttribute('data-aaweb-ab-tab');
				tabs.forEach(function (item) { item.classList.remove('is-active'); });
				panels.forEach(function (panel) { panel.classList.remove('is-active'); });
				tab.classList.add('is-active');
				var panel = document.querySelector('[data-aaweb-ab-panel="' + key + '"]');
				if (panel) {
					panel.classList.add('is-active');
				}
			});
		});
	}


	function toggleContentModeFields() {
		var mode = value('content_mode', 'text');
		document.querySelectorAll('[data-aaweb-ab-content-field]').forEach(function (wrap) {
			var target = wrap.getAttribute('data-aaweb-ab-content-field');
			if (target === mode) {
				wrap.hidden = false;
				wrap.classList.add('is-active');
			} else {
				wrap.hidden = true;
				wrap.classList.remove('is-active');
			}
		});
	}

	function updatePreview() {
		var preview = document.querySelector('[data-aaweb-ab-live-preview]');
		if (!preview) {
			return;
		}

		var main = preview.querySelector('.aaweb-ab-live-main');
		var text = preview.querySelector('.aaweb-ab-live-text');
		var button = preview.querySelector('.aaweb-ab-live-button');
		var close = preview.querySelector('.aaweb-ab-live-close');
		var mode = value('content_mode', 'text');
		var message = 'html' === mode ? value('html_content', '') : value('text', 'Write your announcement message here.');
		var buttonEnabled = '1' === value('button_enabled', '1');
		var buttonText = value('link_text', 'Learn more');
		var buttonPosition = value('button_position', 'after');
		var hAlign = value('horizontal_align', 'center');
		var vAlign = value('vertical_align', 'center');
		var radius = value('button_radius', '999');
		var py = value('button_padding_y', '6');
		var px = value('button_padding_x', '14');

		preview.style.background = value('background_color', '#184436');
		preview.style.color = value('text_color', '#ffffff');
		preview.className = preview.className.replace(/aaweb-ab-live-align-\w+/g, '').replace(/aaweb-ab-live-valign-\w+/g, '');
		preview.classList.add('aaweb-ab-live-align-' + hAlign);
		preview.classList.add('aaweb-ab-live-valign-' + vAlign);

		if (text) {
			text.textContent = message.replace(/<[^>]*>/g, '').trim() || 'Write your announcement message here.';
			text.style.fontSize = value('font_size', '14') + 'px';
			text.style.fontWeight = value('font_weight', '500');
			text.style.color = value('text_color', '#ffffff');
		}

		if (button) {
			button.textContent = buttonText || 'Learn more';
			button.style.display = buttonEnabled ? 'inline-flex' : 'none';
			button.style.background = value('button_bg_color', '#ffffff');
			button.style.color = value('button_text_color', '#184436');
			button.style.borderColor = value('button_border_color', '#ffffff');
			button.style.borderWidth = value('button_border_width', '1') + 'px';
			button.style.borderRadius = radius + 'px';
			button.style.padding = py + 'px ' + px + 'px';
			button.style.fontSize = value('button_font_size', '14') + 'px';
			button.style.fontWeight = value('button_font_weight', '700');
			button.style.marginLeft = value('button_margin_left', '0') + 'px';
			button.style.marginRight = value('button_margin_right', '0') + 'px';
		}

		if (main && button && text) {
			if ('before' === buttonPosition && main.firstElementChild !== button) {
				main.insertBefore(button, text);
			} else if ('after' === buttonPosition && main.lastElementChild !== button) {
				main.appendChild(button);
			}
		}

		if (close) {
			close.style.display = '1' === value('dismissible', '') ? 'block' : 'none';
		}
	}

	document.addEventListener('DOMContentLoaded', function () {
		applyTabs();
		toggleContentModeFields();
		updatePreview();
		document.querySelectorAll('.aaweb-ab-admin-form input, .aaweb-ab-admin-form select, .aaweb-ab-admin-form textarea').forEach(function (input) {
			input.addEventListener('input', function () {
				toggleContentModeFields();
				updatePreview();
			});
			input.addEventListener('change', function () {
				toggleContentModeFields();
				updatePreview();
			});
		});
	});
})();
