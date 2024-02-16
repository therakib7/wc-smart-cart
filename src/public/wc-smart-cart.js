/**
 * This script initializes and manages various functionalities for the web page.
 * It includes methods for initialization, setting up event listeners,
 *
 * @since 0.1.0
 */

import './wc-smart-cart.scss';

(function (window, document, $, undefined) {
	'use strict';

	/**
	 * wcSmartCartInit object contains methods for initializing and setting up
	 * the functionalities of the webpage.
	 */
	let wcSmartCartInit = {
		/**
		 * Initializes the wcSmartCartInit object.
		 * Calls the setup and methods functions.
		 */
		init: function (e) {
			wcSmartCartInit.selector();
			wcSmartCartInit.methods();
		},

		/**
		 * Sets up commonly used selectors.
		 */
		selector: function (e) {
			this._window = $(window),
				this._document = $(document),
				this._body = $('body'),
				this._html = $('html')
		},

		/**
		 * Groups the methods to be called for functionality initialization.
		 */
		methods: function (e) {
			wcSmartCartInit.showSmartCart();
		},

		/**
		 * Show WC cart when added to cart
		 * 
		 * @since 1.0.0
		 * 
		 */
		showSmartCart: function () {

			jQuery(document.body).on('added_to_cart wc-blocks_added_to_cart', function (event, fragments, cart_hash, button) {

				jQuery.ajax({
					url: wc_smart_cart_params.ajax_url,
					type: 'POST',
					data: {
						action: 'wc_smart_cart_content',
						nonce: wc_smart_cart_params.nonce
					},
					success: function (data) {
						if (data.success) {
							// Add cart content to container
							jQuery('#wc-smart-cart-container').html(data.data);

							// Show cart with slide animation
							jQuery('#wc-smart-cart-container .wc-smart-cart').animate({ right: '30px' }, 'slow');

							// Attach click event to close button
							jQuery('#wc-smart-cart-container .wc-smart-cart-close').on('click', function (e) {
								e.preventDefault();
								wcSmartCartInit.closeSmartCart();
							});

							// Automatically close the cart after specific seconds
							setTimeout(function () {
								wcSmartCartInit.closeSmartCart();
							}, wc_smart_cart_params.close_after); // 5000 milliseconds = 5 seconds
						}
					}
				});
			});

		},

		/**
		 * Close WC cart when added to cart
		 * 
		 * @since 1.0.0
		 * 
		 */
		closeSmartCart: function () {
			jQuery('#wc-smart-cart-container .wc-smart-cart').animate({ right: '-330px' }, 'slow', function () {
				// Clear cart content after animation completes
				jQuery('#wc-smart-cart-container').html('');
			});
		}
	}

	// Trigger the initialization.
	wcSmartCartInit.init();

})(window, document, jQuery);