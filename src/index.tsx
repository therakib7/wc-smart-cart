/**
 * Main root file of react admin panel
 *
 * @since 0.1.0
 */

/**
 * External dependencies
 */
import { StrictMode, createRoot } from '@wordpress/element';
import { QueryClientProvider } from '@tanstack/react-query';
import { queryClient } from '@utils/react-query';

/**
 * Internal dependencies
 */
import App from './App';
import { AlertProvider } from '@components/alert/Provider';
import '@styles/main.scss';

const rootElement = document.getElementById('wc-smart-cart');
if (rootElement) {
	createRoot(rootElement).render(
		<StrictMode>
			<QueryClientProvider client={queryClient}>
				<AlertProvider>
					<App />
				</AlertProvider>
			</QueryClientProvider>
		</StrictMode>
	);
}
