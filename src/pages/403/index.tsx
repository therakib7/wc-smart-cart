/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';

const NoPermission = () => {
	return (
		<div className="wc-smart-cart-403">
			<h3>{ __( 'Permission Denied!', 'wc-smart-cart' ) }</h3>
		</div>
	);
};
export default NoPermission;
