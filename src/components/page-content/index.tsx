import React, { ReactNode } from 'react';

interface Props {
	className?: string;
	children: ReactNode;
}

const PageContent: React.FC< Props > = ( { className = '', children } ) => {
	return (
		<div className={ `wc-smart-cart-content ${ className }` }>
			{ children }
		</div>
	);
};

export default PageContent;
