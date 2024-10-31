(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	setTimeout( () => {
		const selectElement = document.querySelector('.spp_bade_position .csf-fieldset select');
		const valuesToDisable = [
			'pro_only',
			'pro_only_1',
			'pro_only_2',
			'pro_only_3',
			'pro_only_4',
			'pro_only_5',
			'pro_only_6',
			'pro_only_7',
			'pro_only_8',
			'pro_only_9',
			'pro_only_10',
			'pro_only_11',
			'pro_only_12',
		];
		// Loop through each option and add the "disabled" attribute
		selectElement.querySelectorAll('option').forEach(option => {
			if (valuesToDisable.includes(option.value)) {
				option.disabled = true;
			}
		});
	}, 1000 );

	$(document).ready(function($) {

		let inputField = $('.pro_only input, .pro_only .csf--switcher');
    
		// Check if the input field exists
		if (inputField.length) {
			// Disable the input field
			inputField.prop('disabled', true);
		}

	});

})( jQuery );
