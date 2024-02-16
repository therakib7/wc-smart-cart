/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';

const NotFound = () => {
	return (
		<div className="wc-smart-cart-404">
			<h3>{ __( '404 Not Found', 'wc-smart-cart' ) }</h3>
		</div>
	);
};
export default NotFound;
