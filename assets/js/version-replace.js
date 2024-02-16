const fs = require( 'fs-extra' );
const replace = require( 'replace-in-file' );

const pluginFiles = [ 'includes/**/*', 'src/*', 'wc-smart-cart.php' ];

const { version } = JSON.parse( fs.readFileSync( 'package.json' ) );

replace( {
	files: pluginFiles,
	from: /WC_SMART_CART_SINCE/g,
	to: version,
} );
