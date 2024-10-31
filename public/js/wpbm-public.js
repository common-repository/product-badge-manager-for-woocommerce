(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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

	jQuery(document).ready(function() {
		jQuery(".badge_post_main").each(function() {
			var a = jQuery(this).attr("data-margintop"),
				r = jQuery(this).attr("data-marginbottom");

			jQuery(this).css({
				"margin-top": a,
				"margin-bottom": r
			})
		});

		jQuery(".spp_badge_wrapper_selector").parent().css({
			position: "relative"
		});

		jQuery(".cpp_badge_wrapper_selector").parent().css({
			position: "relative"
		})
	});
})( jQuery );
