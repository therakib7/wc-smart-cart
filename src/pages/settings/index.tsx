/**
 * External dependencies
 */
import { useReducer, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useQueryClient, useMutation, useQuery } from '@tanstack/react-query';
import { toast } from 'react-toastify';

/**
 * Internal dependencies
 */
import Spinner from '@components/preloader/spinner';
import Topbar from '@components/topbar';
import PageContent from '@components/page-content';
import { get, add } from '@utils/api';
import { reducer, initState } from './reducer';

/**
 * Settings
 *
 * @since 0.1.0
 */
const Settings = () => {
	const queryClient = useQueryClient();
	const [ state, dispatch ] = useReducer( reducer, initState );
	const { loading, saving, form } = state;

	const displayConditionOptions = [
		{
			label: __( 'All Pages', 'wc-smart-cart' ),
			value: 'all',
		},
		{
			label: __( 'Shop Page', 'wc-smart-cart' ),
			value: 'shop',
		},
		{
			label: __( 'Product Archive', 'wc-smart-cart' ),
			value: 'product-archive',
		},
		{
			label: __( 'Product Categories', 'wc-smart-cart' ),
			value: 'product-categories',
		},
		{
			label: __( 'Product Tags', 'wc-smart-cart' ),
			value: 'product-tags',
		},
		{
			label: __( 'Product Archive Attributes', 'wc-smart-cart' ),
			value: 'product-attributes',
		},
		{
			label: __( 'Cart', 'wc-smart-cart' ),
			value: 'cart',
		},
		{
			label: __( 'Single Product', 'wc-smart-cart' ),
			value: 'product-single',
		},
	];

	const { data, isLoading } = useQuery( {
		queryKey: [ 'settings' ],
		queryFn: () => get( 'settings' ),
	} );

	useEffect( () => {
		if ( data ) {
			const { form } = data;
			dispatch( { type: 'set_form', payload: form } );

			if ( form.display_condition.includes( 'all' ) ) {
				const updatedDisplayConditions = displayConditionOptions.map(
					( option ) => option.value
				);
				dispatch( {
					type: 'set_form',
					payload: {
						...form,
						[ 'display_condition' ]: updatedDisplayConditions,
					},
				} );
			}
		}
	}, [ data ] );

	useEffect( () => {
		dispatch( { type: 'set_loading', payload: isLoading } );
	}, [ isLoading ] );

	const submitMutation = useMutation( {
		mutationFn: () => add( 'settings', { ...form } ),
		onSuccess: () => {
			toast.success( __( 'Successfully Changed', 'wc-smart-cart' ) );
			queryClient.invalidateQueries( { queryKey: [ 'settings' ] } );
			dispatch( { type: 'set_saving', payload: false } );
		},
		onError: () => {
			dispatch( { type: 'set_saving', payload: false } );
		},
	} );

	const handleChange = ( e: any ) => {
		const { name, value } = e.target;
		dispatch( { type: 'set_form', payload: { ...form, [ name ]: value } } );
	};

	const handleChangeSwitch = ( name: string, value: string ) => {
		dispatch( { type: 'set_form', payload: { ...form, [ name ]: value } } );
	};

	const handleChangeCheckboxes = ( e: any ) => {
		const { name, value, checked } = e.target;

		if ( value === 'all' ) {
			// If "All Pages" checkbox is clicked, toggle all other checkboxes accordingly
			const updatedDisplayConditions = checked
				? displayConditionOptions.map( ( option ) => option.value )
				: [];

			dispatch( {
				type: 'set_form',
				payload: { ...form, [ name ]: updatedDisplayConditions },
			} );
		} else {
			// For other checkboxes, handle as usual
			const updatedDisplayConditions = checked
				? [ ...form.display_condition, value ]
				: form.display_condition.filter(
						( condition ) => condition !== value
				  );

			dispatch( {
				type: 'set_form',
				payload: { ...form, [ name ]: updatedDisplayConditions },
			} );
		}
	};

	const handleSubmit = async () => {
		dispatch( { type: 'set_saving', payload: true } );
		submitMutation.mutate();
	};

	return (
		<>
			<Topbar
				label={ __(
					'WooCommerce Smart Cart Settings',
					'wc-smart-cart'
				) }
			>
				{ ! loading && (
					<button
						onClick={ handleSubmit }
						className="wc-smart-cart-submit"
						disabled={ saving }
					>
						{ __( 'Save Changes', 'wc-smart-cart' ) }
					</button>
				) }
			</Topbar>

			<PageContent>
				{ loading && <Spinner /> }

				{ ! loading && (
					<div className="wc-smart-cart-settings wc-smart-cart-form">
						<div className="wc-smart-cart-field">
							<label>{ __( 'Layout', 'wc-smart-cart' ) }</label>
							<div className="wc-smart-cart-field-img-switch">
								<button
									type="button"
									name="layout"
									value="one"
									onClick={ () =>
										handleChangeSwitch( 'layout', 'one' )
									}
									className={
										form.layout === 'one' ? 'selected' : ''
									}
								>
									<img
										src="https://img001.prntscr.com/file/img001/xzSPRQrWTLW_xokXWqQQJA.png"
										alt="Layout One"
									/>
								</button>
								<button
									type="button"
									name="layout"
									value="two"
									onClick={ () =>
										handleChangeSwitch( 'layout', 'two' )
									}
									className={
										form.layout === 'two' ? 'selected' : ''
									}
								>
									<img
										src="https://img001.prntscr.com/file/img001/Kc9YjGlaRRqiAIYdXoVY6Q.png"
										alt="Layout Two"
									/>
								</button>
							</div>
						</div>

						<div className="wc-smart-cart-field">
							<label>{ __( 'Position', 'wc-smart-cart' ) }</label>
							<div className="wc-smart-cart-field-button-switch">
								<button
									type="button"
									name="position"
									value="top"
									onClick={ handleChange }
									className={
										form.position === 'top'
											? 'selected'
											: ''
									}
								>
									{ __( 'Top', 'wc-smart-cart' ) }
								</button>
								<button
									type="button"
									name="position"
									value="bottom"
									onClick={ handleChange }
									className={
										form.position === 'bottom'
											? 'selected'
											: ''
									}
								>
									{ __( 'Bottom', 'wc-smart-cart' ) }
								</button>
							</div>
						</div>

						<div className="wc-smart-cart-field">
							<label>
								{ __(
									'Close After (Seconds)',
									'wc-smart-cart'
								) }
							</label>
							<div className="wc-smart-cart-field-range">
								<input
									type="range"
									min="1"
									max="20"
									name="close_after"
									value={ form.close_after }
									onChange={ handleChange }
									className="range-slider"
									style={ {
										background: `linear-gradient(to right, #3264fe ${
											( form.close_after / 20 ) * 100
										}%, #ccd6ff ${
											( form.close_after / 20 ) * 100
										}%)`,
									} }
								/>
								<input
									type="number"
									min="1"
									max="20"
									name="close_after"
									value={ form.close_after }
									onChange={ handleChange }
								/>
							</div>
						</div>

						<div className="wc-smart-cart-field">
							<label>
								{ __( 'Display Conditions', 'wc-smart-cart' ) }
							</label>
							<div className="wc-smart-cart-field-checkboxes">
								{ displayConditionOptions.map( ( option ) => (
									<label key={ option.value }>
										<input
											type="checkbox"
											name="display_condition"
											value={ option.value }
											checked={ form.display_condition.includes(
												option.value
											) }
											onChange={ handleChangeCheckboxes }
										/>
										{ option.label }
									</label>
								) ) }
							</div>
						</div>
					</div>
				) }
			</PageContent>
		</>
	);
};

export default Settings;
