/**
 * Main settings panel
 * @since 0.1.0
 */

/**
 * External dependencies
 */
import { __ } from "@wordpress/i18n";

/**
 * Internal dependencies
 */
import Topbar from '@components/topbar';
import PageContent from '@components/page-content';
import './_style.scss';

const Settings = () => {

	const loading = false;

	return (
		<>
			<Topbar label={__("Settings", "wc-smart-cart")}>
				{!loading && <>
					<button
						// onClick={handleSubmit}
						className="wc-smart-cart-submit"
					>
						{__("Save Changes", "wc-smart-cart")}
					</button>
				</>}
			</Topbar>

			<PageContent>
				<div className="wc-smart-cart-settings">
					{__("Settings", "wc-smart-cart")}
				</div>
			</PageContent>
		</>
	);
};

export default Settings;
