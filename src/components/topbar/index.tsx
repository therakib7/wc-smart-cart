import React, { ReactNode } from 'react';

interface Props {
	label: string;
	children: ReactNode;
}

const Topbar: React.FC< Props > = ( { label, children } ) => {
	return (
		<div className="wc-smart-cart-topbar">
			<div className="wc-smart-cart-topbar-content flex justify-between items-center">
				<h2 className="wc-smart-cart-topbar-label text-gray-900">
					{ label }
				</h2>
				<div className="wc-smart-cart-topbar-action">{ children }</div>
			</div>
		</div>
	);
};

export default Topbar;
