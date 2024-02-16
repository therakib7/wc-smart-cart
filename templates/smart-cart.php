<?php
/**
 * Template for showing smart cart on sidebar
 * 
 * @since 0.1.0
 *
 * @return void
 */

use aThemes\WCSmartCart\Helpers\Keys;

$cart = WC()->cart->get_cart();

// Check if there are cart items
if ( empty( $cart ) ) {
	return;
}

// Get the last added cart item
$last_item = end( $cart );

// Get product ID
$product_id = $last_item['product_id'];

if ( ! $product_id ) {
	return;
}

// Get product object
$product = wc_get_product( $product_id );

// Get product title
$product_title = $product->get_name();

// Get product image
$product_image_id = $product->get_image_id();

// Get settings data
$settings = [];
$option = get_option( Keys::SETTINGS );
if ( $option ) {
    $settings = $option;
} else {
    $settings['layout'] = 'one';
    $settings['position'] = 'top';
}

$classes = 'wc-smart-cart';

// add layout class
if ( $settings['layout'] === 'one' ) {
    $classes .= ' wc-smart-cart-layout-one';
} else {
    $classes .= ' wc-smart-cart-layout-two';
}

// add position class
if ( $settings['position'] === 'top' ) {
    $classes .= ' wc-smart-cart-position-top';
} else {
    $classes .= ' wc-smart-cart-position-bottom';
}
?>

<div 
    class="<?php echo esc_attr( $classes ); ?>" 
    style="<?php
        if ( $product_image_id && $settings['layout'] === 'two' ) {
            echo 'background-image: url(' . esc_url( wp_get_attachment_image_url( $product_image_id, 'medium' ) ) . ')';
        }
    ?>"
>
    <?php if ( $settings['layout'] === 'two' ) { echo '<div class="wc-smart-cart-overlay">'; } ?>

    <button class="wc-smart-cart-close">&times;</button>

    <p class="wc-smart-cart-label"><?php esc_html_e( 'Added To Cart', 'wc-smart-cart' ); ?></p>

    <div class="wc-smart-cart-content">
        <?php if ( $product_image_id && $settings['layout'] === 'one' ) : ?>
            <img 
                class="wc-smart-cart-img"
                src="<?php echo esc_url( wp_get_attachment_image_url( $product_image_id, 'thumbnail' ) ); ?>"
                alt="<?php echo esc_html( $product_title ); ?>"
            />
        <?php endif; ?>

        <div class="wc-smart-cart-details">
            <h3 class="wc-smart-cart-title"><?php echo esc_html( $product_title ); ?></h3>
            <p class="wc-smart-cart-price"><span class="wc-smart-cart-price-label"><?php esc_html_e( 'Price:', 'wc-smart-cart' ); ?></span> <?php echo wp_kses( wc_price( $product->get_price() ), array( 'span' => array() ) ); ?></p>
        </div>
    </div>

    <a class="wc-smart-cart-btn" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'View Cart', 'wc-smart-cart' ); ?></a>
    
    <?php if ( $settings['layout'] === 'two' ) { echo '</div>'; } ?>
</div>
