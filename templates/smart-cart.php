<?php

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

?>

<div class="wc-smart-cart">
    <button class="wc-smart-cart-close">&times;</button>

    <p class="wc-smart-cart-label"><?php esc_html_e( 'Added To Cart', 'wc-smart-cart' ); ?></p>

    <div class="wc-smart-cart-content">
        <?php if ( $product_image_id ) : ?>
            <img 
                class="wc-smart-cart-img"
                src="<?php echo esc_url( wp_get_attachment_image_url( $product_image_id, 'medium' ) ); ?>"
                alt="<?php echo esc_html( $product_title ); ?>"
            />
        <?php endif; ?>

        <div class="wc-smart-cart-details">
            <h3 class="wc-smart-cart-title"><?php echo esc_html( $product_title ); ?></h3>
            <p class="wc-smart-cart-price"><span class="wc-smart-cart-price-label"><?php esc_html_e( 'Price:', 'wc-smart-cart' ); ?></span> <?php echo wc_price( $product->get_price() ); ?></p>
        </div>
    </div>

    <a class="wc-smart-cart-btn" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'View Cart', 'wc-smart-cart' ); ?></a>
</div>
