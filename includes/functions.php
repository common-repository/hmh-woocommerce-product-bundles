<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//Rename the button on the Product page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'ts_product_add_cart_button' );
function ts_product_add_cart_button( $label ) {
	foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {
		$product = $values['data'];
		if( get_the_ID() == $product->get_id() ) {
			$label = esc_html__('Already added to Cart. Add again?', 'hmh-woo-product-bundles');
		}
	}

	return $label;
}

//Rename the button on the Shop page 
add_filter( 'woocommerce_product_add_to_cart_text', 'ts_shop_add_cart_button', 99, 2 );
function ts_shop_add_cart_button( $label, $product ) {

	if ( $product->get_type() == 'simple' && $product->is_purchasable() && $product->is_in_stock() ) 
	{
		foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {
			$_product = $values['data'];
			if( get_the_ID() == $_product->get_id() ) {
				$label = esc_html__('Already added to Cart. Add again?', 'hmh-woo-product-bundles');
			}
		} 
	}
	return $label; 
}

if (get_option('Hmh_Wpb_location_displayed') == 1) {
	add_action( 'woocommerce_before_add_to_cart_button', 'hmh_bundle_product_field', 10 );
}elseif(get_option('Hmh_Wpb_location_displayed') == 2){
	add_action( 'woocommerce_after_add_to_cart_button', 'hmh_bundle_product_field', 10 );
}else{
	?>
		<style>
			.hmh-woo-product-bundled-items{
				display: none;
			}
		</style>
	<?php
}

add_filter( 'woocommerce_is_purchasable', 'pewc_filter_is_purchasable', 10, 2 );
// add to cart
add_filter( 'woocommerce_add_cart_item_data', 'hmh_add_bundle_product_to_cart_item_data', 10, 3 );
// custom add to cart price
add_action( 'woocommerce_before_calculate_totals', 'add_custom_price', 20, 1);
// show product bundles
if (get_option('Hmh_Wpb_hide_product_bundle') == 'yes_text') {
	add_filter( 'woocommerce_get_item_data', 'hmh_bundle_product_cart_items', 10, 2 );
	add_action('woocommerce_checkout_create_order_line_item','hmh_bundle_product_to_order_items',10,4);
}
// Show order
add_action( 'woocommerce_checkout_create_order_line_item', 'hmh_add_order_item_meta', 10, 3 );
add_filter( 'woocommerce_order_item_name', 'hmh_cart_item_name', 10, 2 );
add_filter( 'woocommerce_order_formatted_line_subtotal', 'hmh_order_formatted_line_subtotal', 10, 2 );
// Admin order
add_filter( 'woocommerce_hidden_order_itemmeta', 'hmh_hidden_order_itemmeta', 10, 1 );
// Add phone and email to order admin
add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 50, 2 );

add_filter('woocommerce_currency_symbol', 'hawkdive_currency_symbol', 10, 2);
function hawkdive_currency_symbol( $currency_symbol, $currency ) {
     switch( $currency ) {
          case $currency_symbol = 'VND'; 
          break;
     }
     return $currency_symbol;
}

// Output product bundles field.
function hmh_bundle_product_field() {
	global $product, $wpdb;

	$products = $wpdb->get_results ("
		    SELECT * FROM $wpdb->posts 
		    WHERE post_status = 'publish' and post_type = 'product'",OBJECT_K);


	$symbol = 'VND';
	$symbol_thousand = '.';
	$decimal_place = 3;

	$products_b = get_post_meta( get_the_ID(), '_gift_product_bundle', true);
	$instocks = get_post_meta( get_the_ID(), '_stock_status', false);
	$hmh_limit_min = get_post_meta( get_the_ID(), '_hmh_limit_each_min', true);
	$hmh_limit_max = get_post_meta( get_the_ID(), '_hmh_limit_each_max', true);

	if ( !$products_b) {
	    return;
	}

	do_action( 'gift_card_before_add_to_cart_form' );  ?>

	<table class="hmh-woo-product-bundled-items">                
		<tbody>
		<?php 
			foreach ($products_b as $key => $pb) {
				foreach ($products as $pro) {
					if($pro->ID == esc_attr($key)) {
                        $prices = get_post_meta($pro->ID, '_price', true);
                        $prices_sales = get_post_meta($pro->ID, '_regular_price', true);

				        ?>
					<tr>
						<?php if (get_option('Hmh_Wpb_show_thumbnails') == 'yes') : ?>
					        <td class="hmh-woo-product-bundled-item-image"> 
								<img src="<?php  echo wp_get_attachment_url( get_post_thumbnail_id($pro->ID));   ?>" alt="images/png">
							</td>
						<?php endif; ?>
				        <td class="hmh-woo-product-bundled-item-data">
				            <h3><a href="<?php echo esc_url( $pro->guid ); ?>">
			                	<?php echo esc_attr( $pb["'quantity'"] ) . ' x ' . esc_html__( $pro->post_title ); ?>
			                	</a>
			                </h3>
				            
			            	<?php 
				            	if ( !empty($instocks[0]) == 'instock' ) { ?>
									<div class="hmh-product-bundled-item-instock"> <?php esc_html_e('In stock', 'hmh-woo-product-bundles'); ?> </div>
				                <?php 
				            	} else { 
				            	?>
				                    <div class="hmh-product-bundled-item-outofstock"> <?php esc_html_e( 'Out of stock', 'hmh-woo-product-bundles' ); ?> </div>
				            <?php 
				            	}  

						 	if (get_option('Hmh_Wpb_show_prices') == 'yes') {
						 		if ($prices) {
						 		?>
	                        	<p id="prices_<?php esc_attr_e($pro->ID); ?>" class="hmh-price">
	                        		<input type="hidden" name="hmh_price[<?php esc_attr_e( $prices ); ?>]" value="<?php esc_attr_e( $prices ); ?>">
	                        		<?php $pri = number_format($prices, $decimal_place, $symbol_thousand , $symbol_thousand); echo esc_attr__( $pri ). ' ' .$symbol;?></p>
	                        	<?php }else { ?>
	                        	<p id="prices_sales_<?php esc_attr_e($pro->ID); ?>" class="hmh-price-sales">
									<input type="hidden" name="hmh_price[<?php esc_attr_e( $prices_sales ); ?>]" value="<?php esc_attr_e( $prices_sales ); ?>">
	                        		<?php $sal = number_format($prices_sales, $decimal_place, $symbol_thousand , $symbol_thousand); echo esc_attr__( $sal ). ' ' .$symbol; ?> ?></p>
							<?php 
								}
							}
							?>

						</td>
						<td class="hmh-woo-product-bundled-item-limit-each">
							<?php if (get_option('Hmh_Wpb_show_quantity') == 'yes') :?>
								<input type="number" name="_hmh_product_bundle[<?php esc_attr_e($key); ?>]['quantity']" min="<?php esc_attr_e( $hmh_limit_min ); ?>" max="<?php esc_attr_e( $hmh_limit_max ); ?>" value="<?php esc_attr_e( $pb["'quantity'"] ); ?>">
							<?php else : ?>
								<input type="hidden" name="_hmh_product_bundle[<?php esc_attr_e($key); ?>]['quantity']" min="<?php esc_attr_e( $hmh_limit_min ); ?>" max="<?php esc_attr_e( $hmh_limit_max ); ?>" value="<?php esc_attr_e( $pb["'quantity'"] ); ?>">
							<?php endif; ?>
						</td>
				    </tr>
					<?php 	
					}
				}
			} 
		?>
	    </tbody>
	</table>

	<?php do_action( 'gift_card_after_add_to_cart_form' );
}

/**
 * Prevent products being purchased from archive
 * @return Boolean
 */
function pewc_filter_is_purchasable( $is_purchasable, $product ) {
	if( is_archive() ) {
		return false;
	}
	return $is_purchasable;
}

// custom price in cart
function add_custom_price( $cart_obj ) {
	global $wpdb;

	$products = $wpdb->get_results ("
		    SELECT * FROM $wpdb->posts 
		    WHERE post_status = 'publish' and post_type = 'product'",OBJECT_K);

    // This is necessary for WC 3.0+
    if ( is_admin() && ! defined( 'WPINC' ) )
        return;
    // Avoiding hook repetition (when using price calculations for example)
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;
	// Loop through cart items
	foreach ( $cart_obj->get_cart() as $cart_item ) {
    	if ( isset($cart_item['_hmh_product_bundle']) && isset($cart_item['_hmh_price']) ) {
			$total_price = 0;

			foreach ($products as $product) {
			foreach ($cart_item['_hmh_product_bundle'] as $key => $value) {
		  	foreach ($cart_item['_hmh_price'] as $price) {
		  		$price = $price;
				$quantity = $value["'quantity'"];
				
				if ($product->ID == $key && get_post_meta($product->ID, '_price', true) == $price) 
				{
					$total_pb = $price * $quantity;
					$total_price = $total_pb + $total_price;
					$total = $cart_item['data']->get_regular_price() + $total_price;

					$cart_item['data']->set_price( $total );
				}
			}}}
	    }else{
		    $cart_item['data']->set_price( $cart_item['data']->get_regular_price() );
		}
	} 
}

// Add product bundles to cart item.
function hmh_add_bundle_product_to_cart_item_data($cart_item_data, $product_id, $variation_id) {
    global $woocommerce;
    $new_value = array();
	if ( isset($_POST['_hmh_product_bundle']) || isset($_POST['hmh_price'] )) {
    	$new_value['_hmh_product_bundle'] = sanitize_text_or_array_field( $_POST['_hmh_product_bundle'] );
    	$new_value['_hmh_price'] = sanitize_text_or_array_field( $_POST['hmh_price'] );
    }

    if(empty($cart_item_data)) {
        return $new_value;
    } else {
        return array_merge($cart_item_data, $new_value);
    }
}
add_filter('woocommerce_get_cart_item_from_session', 'hmh_get_cart_items_from_session', 1, 3 );
function hmh_get_cart_items_from_session($item,$values,$key) {
    if (array_key_exists( '_hmh_product_bundle', $values ) ) {
        $item['_hmh_product_bundle'] = $values['_hmh_product_bundle'];
    }
    return $item;
}

// Display product bundles in the cart.
function hmh_bundle_product_cart_items( $item_data, $cart_item ) {
	if ( empty( $cart_item['_hmh_product_bundle'] ) ) {
		return $item_data;
	}

	$symbol = 'VND';
	$symbol_thousand = '.';
	$decimal_place = 3;

	$hmh_items = $cart_item['_hmh_product_bundle'];
	$hmh_items_str = '';
	if ( is_array( $hmh_items ) && count( $hmh_items ) > 0 ) {
		foreach ( $hmh_items as $key => $hmh_items ) {
			$hmh_item_id = esc_attr($key);
			$hmh_item_qty = $hmh_items["'quantity'"];
			$price_totals1 = $hmh_item_qty * get_post_meta($key, '_price', true);
			$price_totals2 = $hmh_item_qty * get_post_meta($key, '_regular_price', true);

			if ($price_totals1) {
				$price_total = number_format($price_totals1, $decimal_place, $symbol_thousand , $symbol_thousand);
			}else{
				$price_total = number_format($price_totals2, $decimal_place, $symbol_thousand , $symbol_thousand);
			}
			if ( !empty($hmh_item_qty) > 0) {
				$hmh_items_str .= '+ '. get_the_title( $hmh_item_id ) . ' x ' . $hmh_item_qty . ' = ' . $price_total .' '. $symbol . '<br/>';
			}
		}
	}
	$hmh_items_str = trim( $hmh_items_str, '<br/>' );
	// show product bundle to cart
	$item_data[]     = array(
		'key'     => esc_html__( 'Bundled Products', 'hmh-woo-product-bundles' ),
		'value'   => $hmh_items_str,
		'display' => '',
	);
	return $item_data;
}

// Add product bundles to order.
function hmh_bundle_product_to_order_items( $item, $cart_item_key, $values, $order ) {
	if ( empty( $values['_hmh_product_bundle'] ) ) 
		return;
	if ( empty( $values['_hmh_product_bundle'] ) ) 
		return;

	$hmh_items_str = '';

	foreach ($values['_hmh_product_bundle'] as $key => $value) {
		$title = get_the_title( $key );
		$quantity = $value["'quantity'"];

		if ( !empty($quantity) > 0 ) {
			$hmh_items_str .= '<p class="hmh-pb-order">+ <a href="'.get_permalink($key).'">' . get_the_title( $key ) . '</a> × ' . $quantity . '</p>';
		}
	}
	$hmh_items_str = trim( $hmh_items_str, '; ' );
	// show product bundle to order user and admin
	$item->add_meta_data( esc_html__('Bundled Products', 'hmh-woo-product-bundles'), $hmh_items_str );
}

// Order item
function hmh_add_order_item_meta( $item, $cart_item_key, $values ) {
	if ( isset( $values['hmh_parent_id'] ) ) {
		// use _ to hide the data
		$item->update_meta_data( '_hmh_parent_id', $values['hmh_parent_id'] );
	}
	if ( isset( $values['_hmh_product_bundle'] ) ) {
		// use _ to hide the data
		$item->update_meta_data( '_hmh_product_bundle', $values['_hmh_product_bundle'] );
	}
	if ( isset( $values['hmh_price'] ) ) {
		// use _ to hide the data
		$item->update_meta_data( '_hmh_price', $values['hmh_price'] );
	}
}

// Show product bundle to check order frontend
function hmh_cart_item_name( $name, $item ) {
	if ( isset( $item['_hmh_product_bundle'] ) && ! empty( $item['_hmh_product_bundle'] ) ) {
		if ( ( strpos( $name, '</a>' ) !== false ) ) {
			return $name;
		} else {
			foreach ($item['_hmh_product_bundle'] as $key => $value) {
				$title = get_the_title( $key );
				$quantity = $value["'quantity'"];
				echo '+ ' . esc_html__( get_the_title( $key ) ) . ' x '. esc_attr__( $quantity );
				echo "<br/>";
			}
			return get_the_title( $item['_hmh_product_bundle'] ) . ' &rarr; ' . strip_tags( $name );
		}
	} else {
		return $name;
	}
}

// Plus price order user front-end
function hmh_order_formatted_line_subtotal( $subtotal, $item ) {
	if ( isset( $item['_hmh_parent_id'] ) ) {
		return '';
	} elseif ( isset( $item['_hmh_product_bundle'], $item['_hmh_price'] ) ) {
		return wc_price( $item['_hmh_price'] * $item["'quantity'"] );
	}
	return $subtotal;
}

// Admin order
$hidden_order_itemmeta = apply_filters(
	'woocommerce_hidden_order_itemmeta', array(
		'_hmh_parent_id',
		'_hmh_product_bundle',
		'_hmh_price',
		'hmh_parent_id',
		'hmh_product_bundle',
		'hmh_price'
	)
);
function hmh_hidden_order_itemmeta( $hidden_meta ) {
  	$hidden_meta[] = 'product';
  	return $hidden_meta;
}

// Customer email and phone in order column to admin orders list
function custom_orders_list_column_content( $column, $post_id ) {
	global $post, $the_order;
	
    if ( empty( $the_order ) || $the_order->get_id() != $post->ID ) {
        $the_order = wc_get_order( $post->ID );
    }
    if ( $column == 'order_number' )
    {
        if( $phone = $the_order->get_billing_phone() ){
            $phone_wp_dashicon = '<span class="dashicons dashicons-phone"></span> ';
            ?>
				<br><strong><a href="#"> <?php echo bb_esc_html( $phone_wp_dashicon . $phone ); ?> </a></strong>
			<?php
        }
        if( $email = $the_order->get_billing_email() ){
			$email_wp_dashicon = '<span class="dashicons dashicons-email-alt2"></span> ';
			?>
            	<br><strong><a href="#"> <?php echo bb_esc_html( $email_wp_dashicon . $email ); ?> </a></strong>
			<?php
        }
    }
}
