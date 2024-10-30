<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

add_action( 'plugins_loaded', 'hmh_register_product_bundles' );
function hmh_register_product_bundles () {
	class WC_Product_Gift_Card extends WC_Product {
		public function __construct( $product ) {
			$this->product_type = 'gift_card'; // Tên loại sản phẩm tùy chỉnh
			parent::__construct( $product );
			//thêm chức năng bổ sung tại đây
		}
    }
}

add_filter( 'product_type_selector', 'hmh_add_gift_card_type' );
function hmh_add_gift_card_type ( $type ) {
	// Khóa phải giống hệt như trong lớp product_type
	$type[ 'gift_card' ] = esc_html__( 'HMH Product Bundles', 'hmh-woo-product-bundles' );
	return $type;
}

// tab mới
add_filter( 'woocommerce_product_data_tabs', 'hmh_gift_card_tab' );
function hmh_gift_card_tab( $tabs) {
	// Khóa phải giống hệt như trong lớp product_type
	// Thêm tabs option mới
	$tabs['gift_card'] = array(
		'label'	 => esc_html__( 'HMH Product Bundles', 'hmh-woo-product-bundles' ),
		'target' => 'gift_card_options',
		'class'  => ('show_if_gift_card'),
	);
	return $tabs;
}
// Hiển thị thông tin cần thêm trong tabs options
add_action( 'woocommerce_product_data_panels', 'hmh_gift_card_options_product_tab_content' );
function hmh_gift_card_options_product_tab_content() {
	global $wpdb, $woocommerce, $product;

	$symbol = 'VND';
	$symbol_thousand = '.';
	$decimal_place = 3;

	$products = $wpdb->get_results ("
	    SELECT * FROM $wpdb->posts WHERE post_status = 'publish' and post_type = 'product'",OBJECT_K);
	$pbs 		= get_post_meta( get_the_ID(), '_gift_product_bundle', true);

    $checked1   = get_post_meta(get_the_ID(), '_hmh_disable_auto_price', true);
    $checked2   = get_post_meta(get_the_ID(), '_hmh_optional_products', true);
    $discounts  = get_post_meta(get_the_ID(), '_hmh_discount', true);
    $each_min   = get_post_meta(get_the_ID(), '_hmh_limit_each_min', true);
    $each_max   = get_post_meta(get_the_ID(), '_hmh_limit_each_max', true);
    $whole_min  = get_post_meta(get_the_ID(), '_hmh_limit_whole_min', true);
    $whole_max  = get_post_meta(get_the_ID(), '_hmh_limit_whole_max', true);

	?>
	<div id='gift_card_options' class='panel woocommerce_options_panel'><?php
	?><div class='options_group'>
		<p class="form-field">
			<label><?php esc_html_e( 'Gift Product Bundles ', 'hmh-woo-product-bundles' ); ?></label>
			<select id="choose_gift_product_bundle">
				<?php 
					foreach ($products as $product) {
						$image = wp_get_attachment_url( get_post_thumbnail_id($product->ID));
						$prices = get_post_meta($product->ID, '_price', true);
                        $prices_sales = get_post_meta($product->ID, '_regular_price', true);
					?>
						<option id="hmh-opt-id-<?php esc_attr_e( $product->ID ); ?>" data-src="<?php echo esc_url( $image ); ?>" value="<?php esc_attr_e($product->ID); ?>">
							<?php esc_html_e( $product->post_title);?>
						</option>

						<option id="price_sales_<?php esc_attr_e($product->ID); ?>" data-sales="<?php esc_attr_e( $prices_sales ); ?>" class="hmh_display_none"><?php esc_attr_e( $prices_sales ); ?></option>
						<option id="price_<?php esc_attr_e($product->ID); ?>" data-prices="<?php esc_attr_e( $prices ); ?>" class="hmh_display_none"><?php esc_attr_e( $prices ); ?></option>
					<?php
					}
				 ?>
			</select>
			<input type="button" id="add_gift_product_bundle" class="hmh-btn-add" value="Add Products" />
		</p>

		<?php
			if (empty($pbs)) {
			    ?>
				<p><?php esc_html_e('You have no gift products yet!', 'hmh-woo-product-bundles'); ?></p>
				<table id="list_gif_product_bundle" data-id="<?php esc_attr_e( get_the_ID() ); ?>" class="table_product_bundle">
                    <thead>
                        <tr>
                            <th><?php  esc_html_e( 'Images', 'hmh-woo-product-bundles' ); ?></th>
                            <th><?php  esc_html_e( 'Products', 'hmh-woo-product-bundles' ); ?></th>
                            <th><?php  esc_html_e( 'Price', 'hmh-woo-product-bundles' ); ?></th>
                            <th><?php  esc_html_e( 'Quantity', 'hmh-woo-product-bundles' ); ?></th>
                            <th><?php  esc_html_e( 'Remove', 'hmh-woo-product-bundles' ); ?></th>
                        </tr>
                    </thead>
                    <tbody class="hmhpb-products">
                    </tbody>
                </table>
                <?php
			}else{
				?>
				<table id="list_gif_product_bundle" data-id="<?php esc_attr_e( get_the_ID() ); ?>" class="table_product_bundle">
					<thead>
						<tr>
							<th><?php  esc_html_e( 'Images', 'hmh-woo-product-bundles' ); ?></th>
							<th><?php  esc_html_e( 'Products', 'hmh-woo-product-bundles' ); ?></th>
							<th><?php  esc_html_e( 'Price', 'hmh-woo-product-bundles' ); ?></th>
							<th><?php  esc_html_e( 'Quantity', 'hmh-woo-product-bundles' ); ?></th>
							<th><?php  esc_html_e( 'Remove', 'hmh-woo-product-bundles' ); ?></th>
						</tr>
					</thead>
					<tbody class="hmhpb-products">
					<?php 
						foreach ($pbs as $key => $pb) {
                                foreach ($products as $prod) {
                                    if($prod->ID == esc_attr($key)) {
                                    ?>
							<tr>
								<td  class="hmh-item-image"><img src="<?php echo wp_get_attachment_url( get_post_thumbnail_id($prod->ID));  ?>" alt="images/png"></td>
								<td class="hmh-product">
									<?php 
										esc_html_e( $prod->post_title );
								 	?>
								</td>
                                <td class="hmh-product">
									<?php
                                        $prices = get_post_meta($prod->ID, '_price', true);
                                        $prices_sales = get_post_meta($prod->ID, '_regular_price', true);
								 	?>
								 	<?php if ($prices) : ?>
                                    	<p id="prices_<?php esc_attr_e($prod->ID)?>" class="hmh-price" data-price="<?php esc_attr_e( $prices ); ?>">
                                    		<?php $pri = number_format($prices, $decimal_place, $symbol_thousand , $symbol_thousand); esc_attr_e( $pri ). ' ' .$symbol;?></p>
                                    <?php else : ?>
                                    	<p id="prices_sales_<?php esc_attr_e($prod->ID)?>" class="hmh-price-sales" data-price_sale="<?php esc_attr_e( $prices_sales ); ?>">
                                    		<?php $sal = number_format($prices_sales, $decimal_place, $symbol_thousand , $symbol_thousand); esc_attr_e( $sal ). ' ' .$symbol; ?> ?></p>
									<?php endif; ?>
								</td>
								<td class="hmh-quantity">
                                    <div class="box-input">
                                        <input id="hmh-pb-quantity-<?php esc_attr_e($key) ?>" class="hmhpd-quantity" min="1" type="number" name="_gift_product_bundle[<?php esc_attr_e($key); ?>]['quantity']" value="<?php esc_attr_e( $pb["'quantity'"] ); ?>" >
                                    </div>
                                </td>
								<td><button type="button" class="remove_bundle"><?php esc_html_e( 'Remove', 'hmh-woo-product-bundles' ); ?></button></td>
							</tr>
							<?php
								}
							}
						}
					?>
					</tbody>
				</table>
			<?php
			}
		?>

		<table class="table_product_bundle">
			<tbody>
            <tr class="hmh_tr_space">
                <th><?php esc_html_e( 'Regular price', 'hmh-woo-product-bundles' ) . ' (' . get_woocommerce_currency_symbol() . ')'; ?></th>
                <td>
                    <span id="hmh_regular_price"></span>
                </td>
            </tr>
            <tr class="hmh_tr_space">
                <th><?php esc_html_e( 'Enable discount?', 'hmh-woo-product-bundles' ); ?></th>
                <td>
                	<?php
            		?>
            			<input id="hmh_disable_auto_price" name="_hmh_disable_auto_price" type="checkbox" value="<?php esc_attr_e( $checked1 ? $checked1 : '' ); ?>" checked="checked">
            		<?php
                	?>
                    <label for="hmh_disable_auto_price"><?php esc_html_e('Disable auto calculate price?', 'hmh-woo-product-bundles'); ?></label> <?php esc_html_e('If yes','hmh-woo-product-bundles'); ?>, <a id="hmh_set_regular_price"> <?php esc_html_e('Click here to set price', 'hmh-woo-product-bundles'); ?> </a> <?php esc_html_e('by manually.', 'hmh-woo-product-bundles'); ?>
                </td>
            </tr>
            <?php
                ?>
                <tr class="hmh_tr_space hmh_show_auto_price">
                    <th><?php esc_html_e( 'Discount', 'hmh-woo-product-bundles' ); ?></th>
                    <td>
                        <input id="hmh_discount" name="_hmh_discount" type="number" min="0" step="0.0001" max="99.9999" value="<?php esc_attr_e( $checked1 ? '0' : $discounts ); ?>">%
                    </td>
                </tr>
                <?php
            ?>
            <tr class="hmh_tr_space">
                <th><?php esc_html_e( 'Optional products', 'hmh-woo-product-bundles' ); ?></th>
                <td>
                	<input id='hmh_optional_products' name='_hmh_optional_products' type='checkbox' value='<?php esc_attr_e( $checked2 ? $checked2 : "" ); ?>' checked='checked'>   
                    <label for="hmh_optional_products"><?php esc_html_e('Buyer can change the quantity of bundled products?', 'hmh-woo-product-bundles'); ?></label>
                </td>
            </tr>
            <?php
            if ($checked2) :
            ?>
            <tr class="hmh_tr_space hmh_show_limit_item_products">
                <th><?php esc_html_e( 'Limit of each item', 'hmh-woo-product-bundles' ); ?></th>
                <td>
                    <?php esc_html_e( 'Min', 'hmh-woo-product-bundles' ); ?>
                    <input name="_hmh_limit_each_min" type="number" min="0" value="<?php esc_attr_e( $checked2 ? $each_min : '' ); ?>">
                    <?php esc_html_e( 'Max', 'hmh-woo-product-bundles' ); ?>
                    <input name="_hmh_limit_each_max" type="number" min="1" value="<?php esc_attr_e( $checked2 ? $each_max : '' ); ?>">
                    <input id="hmh_limit_each_min_default" name="hmh_limit_each_min_default" type="checkbox">
                    <label for="hmh_limit_each_min_default"><?php esc_html_e('Use default quantity as min?', 'hmh-woo-product-bundles'); ?></label>
                </td>
            </tr>
            <tr class="hmh_tr_space hmh_show_limit_item_products">
                <th><?php esc_html_e( 'Limit of whole items', 'hmh-woo-product-bundles' ); ?></th>
                <td>
                    <?php esc_html_e( 'Min', 'hmh-woo-product-bundles' ); ?>
                    <input name="_hmh_limit_whole_min" type="number" min="1" value="<?php esc_attr_e( $checked2 ? $whole_min : '' ); ?>">
                    <?php esc_html_e( 'Max', 'hmh-woo-product-bundles' ); ?>
                    <input name="_hmh_limit_whole_max" type="number" min="2" value="<?php esc_attr_e( $checked2 ? $whole_max : '' );  ?>">
                </td>
            </tr>
            <?php else : ?>
                <tr class="hmh_tr_space hmh_show_limit_item_products">
                    <th><?php esc_html_e( 'Limit of each item', 'hmh-woo-product-bundles' ); ?></th>
                    <td>
                        <?php esc_html_e( 'Min', 'hmh-woo-product-bundles' ); ?>
                        <input name="_hmh_limit_each_min" type="number" min="0" value="">
                        <?php esc_html_e( 'Max', 'hmh-woo-product-bundles' ); ?>
                        <input name="_hmh_limit_each_max" type="number" min="1" value="">
                        <input id="hmh_limit_each_min_default" name="hmh_limit_each_min_default" type="checkbox">
                        <label for="hmh_limit_each_min_default"><?php esc_html_e('Use default quantity as min?', 'hmh-woo-product-bundles'); ?></label>
                    </td>
                </tr>
                <tr class="hmh_tr_space hmh_show_limit_item_products">
                    <th><?php esc_html_e( 'Limit of whole items', 'hmh-woo-product-bundles' ); ?></th>
                    <td>
                        <?php esc_html_e( 'Min', 'hmh-woo-product-bundles' ); ?>
                        <input name="_hmh_limit_whole_min" type="number" min="1" value="">
                        <?php esc_html_e( 'Max', 'hmh-woo-product-bundles' ); ?><input name="_hmh_limit_whole_max" type="number" min="2" value="">
                    </td>
                </tr>
            <?php endif; ?>
        	</tbody>
		</table>
		
		</div>
	</div>
	<?php
}
// Lưu và xử lý thông tin của tab options
add_action( 'woocommerce_process_product_meta', 'hmh_save_gift_card_options_field' );
function hmh_save_gift_card_options_field( $post_id ) {
	if ( isset( $_POST['_gift_product_bundle'] ) ) {
		update_post_meta( $post_id, '_gift_product_bundle', sanitize_text_or_array_field( $_POST['_gift_product_bundle']) );
	}else{
		delete_post_meta( $post_id, '_gift_product_bundle', sanitize_text_or_array_field( $_POST['_gift_product_bundle']) );
	}

	if (isset($_POST['_hmh_disable_auto_price'])) {
		update_post_meta( $post_id, '_hmh_disable_auto_price', sanitize_text_field( $_POST['_hmh_disable_auto_price']) );
	}else{
		delete_post_meta( $post_id, '_hmh_disable_auto_price', sanitize_text_field($_POST['_hmh_disable_auto_price']) );
	}

	if (isset($_POST['_hmh_discount'])) {
		update_post_meta( $post_id, '_hmh_discount', sanitize_text_field($_POST['_hmh_discount']) );
	}else{
		delete_post_meta( $post_id, '_hmh_discount', sanitize_text_field($_POST['_hmh_discount']) );
	}

	if (isset($_POST['_hmh_optional_products'])) {
		update_post_meta( $post_id, '_hmh_optional_products', sanitize_text_field( $_POST['_hmh_optional_products']) );
	}else{
		delete_post_meta( $post_id, '_hmh_optional_products', sanitize_text_field($_POST['_hmh_optional_products']) );
	}

	if (isset($_POST['_hmh_limit_each_min'])) {
		update_post_meta( $post_id, '_hmh_limit_each_min', sanitize_text_field($_POST['_hmh_limit_each_min']) );
	}else{
		delete_post_meta( $post_id, '_hmh_limit_each_min', sanitize_text_field($_POST['_hmh_limit_each_min']) );
	}

	if (isset($_POST['_hmh_limit_each_max'])) {
		update_post_meta( $post_id, '_hmh_limit_each_max', sanitize_text_field($_POST['_hmh_limit_each_max']) );
	}else{
		delete_post_meta( $post_id, '_hmh_limit_each_max', sanitize_text_field($_POST['_hmh_limit_each_max']) );
	}

	if (isset($_POST['_hmh_limit_whole_min'])) {
		update_post_meta( $post_id, '_hmh_limit_whole_min', sanitize_text_field($_POST['_hmh_limit_whole_min']) );
	}else{
		delete_post_meta( $post_id, '_hmh_limit_whole_min', sanitize_text_field($_POST['_hmh_limit_whole_min']) );
	}

	if (isset($_POST['_hmh_limit_whole_max'])) {
		update_post_meta( $post_id, '_hmh_limit_whole_max', sanitize_text_field($_POST['_hmh_limit_whole_max']) );
	}else{
		delete_post_meta( $post_id, '_hmh_limit_whole_max', sanitize_text_field($_POST['_hmh_limit_whole_max']) );
	}
}

function product_price($priceFloat) {
$symbol = 'đ';
$symbol_thousand = '.';
$decimal_place = 0;
$price = number_format($priceFloat, $decimal_place, '', $symbol_thousand);
return $price.$symbol;
}